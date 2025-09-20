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

// Load Composer autoloader for SCSS support
if (file_exists(RAKMYAT_ADDONS_PATH . 'vendor/autoload.php')) {
    require_once RAKMYAT_ADDONS_PATH . 'vendor/autoload.php';
}

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
        $this->init_widget_system();

        // Initialize dark mode (if exists)
        if (class_exists('RakmyatAddons_Dark_Mode')) {
            new RakmyatAddons_Dark_Mode();
        }
    }

    private function includes() {
        // Load optional includes if they exist
        if (file_exists(RAKMYAT_ADDONS_PATH . 'includes/scss-compiler.php')) {
            require_once RAKMYAT_ADDONS_PATH . 'includes/scss-compiler.php';
        }
        if (file_exists(RAKMYAT_ADDONS_PATH . 'includes/dark-mode.php')) {
            require_once RAKMYAT_ADDONS_PATH . 'includes/dark-mode.php';
        }
    }

    /**
     * Initialize new widget system
     */
    public function init_widget_system()
    {
        // Load new widget system core
        require_once RAKMYAT_ADDONS_PATH . 'elements/core/widget-manager.php';

        // Initialize widget manager
        \RakmyatAddons\Core\Widget_Manager::instance();
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