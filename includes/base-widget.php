<?php

if (!defined('ABSPATH')) {
    exit;
}

abstract class Rakmyat_Base_Widget extends \Elementor\Widget_Base {

    public function get_categories() {
        return ['rakmyat'];
    }

    public function get_icon() {
        return 'eicon-plug';
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        echo '<div class="rakmyat-widget">';
        $this->render_widget($settings);
        echo '</div>';
    }

    abstract protected function render_widget($settings);
}