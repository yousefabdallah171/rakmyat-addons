<?php

if (!defined('ABSPATH')) {
    exit;
}

class RakmyatAddons_Dark_Mode {

    public function __construct() {
        add_action('wp_footer', [$this, 'add_dark_mode_toggle']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_dark_mode_script']);
    }

    public function add_dark_mode_toggle() {
        if (is_admin()) {
            return;
        }
        ?>
        <button class="rakmyat-dark-mode-toggle" id="rakmyat-dark-toggle" aria-label="Toggle Dark Mode">
            <i class="eicon-moon" id="dark-mode-icon"></i>
        </button>
        <?php
    }

    public function enqueue_dark_mode_script() {
        wp_add_inline_script('jquery', $this->get_dark_mode_script());
    }

    private function get_dark_mode_script() {
        return "
        document.addEventListener('DOMContentLoaded', function() {
            const darkToggle = document.getElementById('rakmyat-dark-toggle');
            const darkIcon = document.getElementById('dark-mode-icon');
            const body = document.body;

            // Check for saved dark mode preference
            const savedMode = localStorage.getItem('rakmyat-dark-mode');
            if (savedMode === 'dark') {
                body.classList.add('dark-mode');
                darkIcon.className = 'eicon-sun';
                darkToggle.classList.add('dark');
            }

            if (darkToggle) {
                darkToggle.addEventListener('click', function() {
                    body.classList.toggle('dark-mode');

                    if (body.classList.contains('dark-mode')) {
                        darkIcon.className = 'eicon-sun';
                        darkToggle.classList.add('dark');
                        localStorage.setItem('rakmyat-dark-mode', 'dark');
                    } else {
                        darkIcon.className = 'eicon-moon';
                        darkToggle.classList.remove('dark');
                        localStorage.setItem('rakmyat-dark-mode', 'light');
                    }

                    // Trigger custom event for widgets to respond
                    window.dispatchEvent(new CustomEvent('rakmyatDarkModeToggle', {
                        detail: { isDark: body.classList.contains('dark-mode') }
                    }));
                });
            }

            // Add dark mode class to all rakmyat widgets
            function updateWidgetDarkMode() {
                const widgets = document.querySelectorAll('.rakmyat-widget');
                widgets.forEach(widget => {
                    if (body.classList.contains('dark-mode')) {
                        widget.classList.add('dark-mode');
                    } else {
                        widget.classList.remove('dark-mode');
                    }
                });
            }

            // Update on page load
            updateWidgetDarkMode();

            // Update when dark mode is toggled
            window.addEventListener('rakmyatDarkModeToggle', updateWidgetDarkMode);
        });
        ";
    }
}