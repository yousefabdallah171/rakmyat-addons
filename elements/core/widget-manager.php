<?php
/**
 * Rakmyat Addons Widget Manager
 * New simplified widget system without config files
 *
 * @package RakmyatAddons
 * @version 2.0.0
 */

namespace RakmyatAddons\Core;

if (!defined('ABSPATH')) {
    exit;
}

class Widget_Manager
{
    /**
     * Singleton instance
     */
    private static $_instance = null;

    /**
     * Widgets array
     */
    private $widgets = [];

    /**
     * Elements path
     */
    private $elements_path;

    /**
     * Elements URL
     */
    private $elements_url;

    /**
     * SCSS Compiler
     */
    private $scss_compiler;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Ensure constants are defined
        if (!defined('RAKMYAT_ADDONS_PATH') || !defined('RAKMYAT_ADDONS_URL')) {
            return;
        }

        $this->elements_path = RAKMYAT_ADDONS_PATH . 'elements/';
        $this->elements_url = RAKMYAT_ADDONS_URL . 'elements/';

        $this->init();
    }

    /**
     * Singleton instance
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Initialize the widget manager
     */
    private function init()
    {
        // Only initialize if Elementor is active
        if (!did_action('elementor/loaded')) {
            add_action('elementor/loaded', [$this, 'init']);
            return;
        }

        add_action('elementor/widgets/register', [$this, 'register_widgets'], 5);
        add_action('elementor/frontend/after_enqueue_scripts', [$this, 'enqueue_widget_assets']);
        add_action('elementor/editor/after_enqueue_scripts', [$this, 'enqueue_editor_assets']);
        add_action('wp_enqueue_scripts', [$this, 'register_widget_assets'], 5);

        // Initialize SCSS compiler if available
        $this->init_scss_compiler();
    }

    /**
     * Initialize SCSS compiler
     */
    private function init_scss_compiler()
    {
        if (class_exists('\ScssPhp\ScssPhp\Compiler')) {
            $this->scss_compiler = new \ScssPhp\ScssPhp\Compiler();
            $this->scss_compiler->setOutputStyle(\ScssPhp\ScssPhp\OutputStyle::COMPRESSED);

            // Set import paths for SCSS includes
            $this->scss_compiler->addImportPath($this->elements_path . 'assets/scss/');
        }
    }

    /**
     * Compile SCSS to CSS for a widget
     */
    private function compile_widget_scss($widget_name)
    {
        if (!$this->scss_compiler) {
            return false;
        }

        $scss_file = $this->elements_path . 'assets/scss/' . $widget_name . '/' . $widget_name . '.scss';

        if (!file_exists($scss_file)) {
            return false;
        }

        try {
            $scss_content = file_get_contents($scss_file);
            $css_content = $this->scss_compiler->compileString($scss_content)->getCss();

            // Create CSS output directory
            $css_dir = $this->elements_path . 'assets/css/' . $widget_name . '/';
            if (!is_dir($css_dir)) {
                wp_mkdir_p($css_dir);
            }

            // Write compiled CSS
            $css_file = $css_dir . $widget_name . '.css';
            file_put_contents($css_file, $css_content);

            return $css_file;

        } catch (\Exception $e) {
            // Log only in debug mode
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log("Rakmyat Addons Widget System: SCSS compilation error for {$widget_name}: " . $e->getMessage());
            }
            return false;
        }
    }

    /**
     * Discover widgets in elements/widgets directory
     */
    public function discover_widgets()
    {
        $widgets_dir = $this->elements_path . 'widgets/';

        if (!is_dir($widgets_dir)) {
            return;
        }

        $widget_folders = scandir($widgets_dir);

        if (!$widget_folders) {
            return;
        }

        foreach ($widget_folders as $folder) {
            // Skip . and .. directories
            if ($folder === '.' || $folder === '..') {
                continue;
            }

            $widget_folder_path = $widgets_dir . $folder;

            // Only process directories
            if (!is_dir($widget_folder_path)) {
                continue;
            }

            // Check if widget PHP file exists (widget_name.php)
            $widget_file = $widget_folder_path . '/' . $folder . '.php';
            if (!file_exists($widget_file)) {
                continue;
            }

            if ($this->load_widget($folder, $widget_folder_path)) {
                // Compile SCSS if available
                $this->compile_widget_scss($folder);
            }
        }
    }

    /**
     * Load individual widget
     */
    private function load_widget($widget_name, $widget_folder)
    {
        try {
            $widget_file = $widget_folder . '/' . $widget_name . '.php';

            // Load widget class (required)
            if (file_exists($widget_file)) {
                require_once $widget_file;

                $widget_class = $this->get_widget_class($widget_name);

                if (class_exists($widget_class)) {
                    $this->widgets[$widget_name] = $widget_class;
                    return true;
                } else {
                    if (defined('WP_DEBUG') && WP_DEBUG) {
                        error_log("Rakmyat Addons Widget System: Widget class {$widget_class} not found for {$widget_name}");
                    }
                    return false;
                }
            }

            return false;

        } catch (Exception $e) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log("Rakmyat Addons Widget System: Error loading widget {$widget_name}: " . $e->getMessage());
            }
            return false;
        }
    }

    /**
     * Get expected widget class name from folder name
     */
    private function get_widget_class($widget_name)
    {
        // Convert widget-name to Widget_Name format
        $class_name = str_replace('-', '_', $widget_name);
        $class_name = ucwords($class_name, '_');

        return "RakmyatAddons\\Widgets\\{$class_name}";
    }

    /**
     * Register widgets with Elementor
     */
    public function register_widgets($widgets_manager)
    {
        // Discover widgets only when we're ready to register them
        $this->discover_widgets();

        foreach ($this->widgets as $widget_name => $widget_class) {
            try {
                if (class_exists($widget_class)) {
                    $widget_instance = new $widget_class();
                    $widgets_manager->register($widget_instance);
                } else {
                    if (defined('WP_DEBUG') && WP_DEBUG) {
                        error_log("Rakmyat Addons Widget System: Cannot register widget {$widget_name}, class {$widget_class} does not exist");
                    }
                }
            } catch (Exception $e) {
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    error_log("Rakmyat Addons Widget System: Error registering widget {$widget_name}: " . $e->getMessage());
                }
            }
        }
    }

    /**
     * Register widget assets
     */
    public function register_widget_assets()
    {
        foreach ($this->widgets as $widget_name => $widget_class) {
            // Register CSS
            $css_file = $this->elements_path . 'assets/css/' . $widget_name . '/' . $widget_name . '.css';
            if (file_exists($css_file)) {
                $css_url = $this->elements_url . 'assets/css/' . $widget_name . '/' . $widget_name . '.css';
                wp_register_style(
                    'rakmyat-' . $widget_name,
                    $css_url,
                    [],
                    filemtime($css_file)
                );
            }

            // Register JS
            $js_file = $this->elements_path . 'assets/js/' . $widget_name . '/' . $widget_name . '.js';
            if (file_exists($js_file)) {
                $js_url = $this->elements_url . 'assets/js/' . $widget_name . '/' . $widget_name . '.js';
                wp_register_script(
                    'rakmyat-' . $widget_name,
                    $js_url,
                    ['jquery'],
                    filemtime($js_file),
                    true
                );
            }
        }
    }

    /**
     * Enqueue widget assets for frontend
     */
    public function enqueue_widget_assets()
    {
        global $post;

        // For Elementor pages, enqueue all widget assets
        if (is_admin() || (isset($post) && \Elementor\Plugin::$instance->db->is_built_with_elementor($post->ID))) {
            foreach ($this->widgets as $widget_name => $widget_class) {
                // Enqueue CSS
                if (wp_style_is('rakmyat-' . $widget_name, 'registered')) {
                    wp_enqueue_style('rakmyat-' . $widget_name);
                }

                // Enqueue JS
                if (wp_script_is('rakmyat-' . $widget_name, 'registered')) {
                    wp_enqueue_script('rakmyat-' . $widget_name);
                }
            }
        }
    }

    /**
     * Enqueue widget assets for editor
     */
    public function enqueue_editor_assets()
    {
        // For editor, enqueue same assets as frontend
        $this->enqueue_widget_assets();
    }

    /**
     * Get widget statistics
     */
    public function get_widget_stats()
    {
        return [
            'total_widgets' => count($this->widgets),
            'widgets' => array_keys($this->widgets),
            'memory_usage' => size_format(memory_get_usage()),
            'system_status' => 'Production Ready',
            'version' => '2.0.0',
        ];
    }

    /**
     * Force refresh
     */
    public function force_refresh()
    {
        $this->widgets = [];
        $this->discover_widgets();

        return $this->get_widget_stats();
    }
}