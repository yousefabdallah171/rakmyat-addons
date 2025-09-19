<?php
/**
 * Plugin Name: Rakmyat Addons
 * Description: Custom Elementor widgets for enhanced website functionality
 * Version: 1.0.0
 * Author: Rakmyat Team
 * Text Domain: rakmyat-addons
 */

if (!defined('ABSPATH')) {
    exit;
}

define('RAKMYAT_ADDONS_VERSION', '1.0.0');
define('RAKMYAT_ADDONS_FILE', __FILE__);
define('RAKMYAT_ADDONS_PATH', plugin_dir_path(__FILE__));
define('RAKMYAT_ADDONS_URL', plugin_dir_url(__FILE__));

class RakmyatAddons {

    private static $instance = null;

    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
    }

    public function init() {
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'elementor_notice']);
            return;
        }

        add_action('elementor/init', [$this, 'elementor_init']);
    }

    public function elementor_init() {
        $this->includes();
        $this->register_category();
        $this->compile_scss();
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);

        // Initialize dark mode
        new RakmyatAddons_Dark_Mode();
    }

    private function includes() {
        // Load core includes
        require_once RAKMYAT_ADDONS_PATH . 'includes/base-widget.php';
        require_once RAKMYAT_ADDONS_PATH . 'includes/scss-compiler.php';
        require_once RAKMYAT_ADDONS_PATH . 'includes/dark-mode.php';

        // Dynamically load all widgets
        $this->load_widgets();
    }

    private function load_widgets() {
        $widgets_path = RAKMYAT_ADDONS_PATH . 'widgets/';

        if (!is_dir($widgets_path)) {
            return;
        }

        $widget_dirs = glob($widgets_path . '*', GLOB_ONLYDIR);

        foreach ($widget_dirs as $widget_dir) {
            $widget_name = basename($widget_dir);
            $widget_file = $widget_dir . '/' . $widget_name . '.php';

            if (file_exists($widget_file)) {
                require_once $widget_file;
            }
        }
    }

    private function compile_scss() {
        // Only compile in admin or when styles don't exist
        if (is_admin() || !file_exists(RAKMYAT_ADDONS_PATH . 'assets/style.css')) {
            $scss_compiler = new RakmyatAddons_SCSS_Compiler();
            $scss_compiler->compile_all();
        }
    }

    private function register_category() {
        add_action('elementor/elements/categories_registered', function($elements_manager) {
            $elements_manager->add_category(
                'rakmyat',
                [
                    'title' => __('Rakmyat', 'rakmyat-addons'),
                    'icon' => 'fa fa-star',
                ]
            );
        });
    }

    public function register_widgets($widgets_manager) {
        $this->register_all_widgets($widgets_manager);
    }

    private function register_all_widgets($widgets_manager) {
        $widgets_path = RAKMYAT_ADDONS_PATH . 'widgets/';

        if (!is_dir($widgets_path)) {
            return;
        }

        $widget_dirs = glob($widgets_path . '*', GLOB_ONLYDIR);

        foreach ($widget_dirs as $widget_dir) {
            $widget_name = basename($widget_dir);
            $widget_file = $widget_dir . '/' . $widget_name . '.php';

            if (file_exists($widget_file)) {
                // Convert widget directory name to class name
                $class_name = $this->get_widget_class_name($widget_name);

                if (class_exists($class_name)) {
                    $widgets_manager->register(new $class_name());
                }
            }
        }
    }

    private function get_widget_class_name($widget_name) {
        // Convert widget directory name to class name
        // e.g., 'heading' -> 'Rakmyat_Heading_Widget'
        // e.g., 'blog-posts' -> 'Rakmyat_Blog_Posts_Widget'
        $parts = explode('-', $widget_name);
        $class_parts = array_map('ucfirst', $parts);
        return 'Rakmyat_' . implode('_', $class_parts) . '_Widget';
    }

    public function enqueue_styles() {
        wp_enqueue_style(
            'rakmyat-addons-style',
            RAKMYAT_ADDONS_URL . 'assets/style.css',
            [],
            RAKMYAT_ADDONS_VERSION
        );
    }

    public function elementor_notice() {
        echo '<div class="notice notice-warning is-dismissible">';
        echo '<p>' . __('Rakmyat Addons requires Elementor to be installed and activated.', 'rakmyat-addons') . '</p>';
        echo '</div>';
    }
}

RakmyatAddons::instance();