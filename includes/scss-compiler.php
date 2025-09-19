<?php

if (!defined('ABSPATH')) {
    exit;
}

class RakmyatAddons_SCSS_Compiler {

    private $scss_dir;
    private $css_dir;

    public function __construct() {
        $this->scss_dir = RAKMYAT_ADDONS_PATH . 'assets/scss/';
        $this->css_dir = RAKMYAT_ADDONS_PATH . 'assets/';
    }

    public function compile_all() {
        $this->ensure_directories();

        // Compile main style.scss
        $this->compile_file('style.scss', 'style.css');

        // Dynamically find and compile all widget SCSS files
        $this->compile_widget_files();
    }

    private function compile_widget_files() {
        // Get all SCSS files except style.scss
        $scss_files = glob($this->scss_dir . '*.scss');

        foreach ($scss_files as $scss_path) {
            $scss_filename = basename($scss_path);

            // Skip the main style.scss file
            if ($scss_filename === 'style.scss') {
                continue;
            }

            $css_filename = str_replace('.scss', '.css', $scss_filename);
            $this->compile_file($scss_filename, $css_filename);
        }
    }

    private function compile_file($scss_file, $css_file) {
        $scss_path = $this->scss_dir . $scss_file;
        $css_path = $this->css_dir . $css_file;

        if (!file_exists($scss_path)) {
            return false;
        }

        $scss_content = file_get_contents($scss_path);

        // For individual widget files, prepend variables from style.scss
        if ($scss_file !== 'style.scss') {
            $variables_content = $this->extract_variables_from_style();
            $scss_content = $variables_content . "\n" . $scss_content;
        }

        $css_content = $this->simple_scss_compile($scss_content);

        if ($css_content !== false) {
            file_put_contents($css_path, $css_content);
            return true;
        }

        return false;
    }

    private function extract_variables_from_style() {
        $style_path = $this->scss_dir . 'style.scss';

        if (!file_exists($style_path)) {
            return '';
        }

        $style_content = file_get_contents($style_path);

        // Extract only variable declarations (lines starting with $)
        preg_match_all('/^\$[^:]+:[^;]+;/m', $style_content, $matches);

        return implode("\n", $matches[0]);
    }

    private function simple_scss_compile($scss_content) {
        // Remove comments
        $css = preg_replace('/\/\*.*?\*\//s', '', $scss_content);
        $css = preg_replace('/\/\/.*$/m', '', $css);

        // Process variables more carefully
        preg_match_all('/\$([a-zA-Z0-9_-]+)\s*:\s*([^;]+);/', $css, $variables);
        $var_map = [];

        for ($i = 0; $i < count($variables[1]); $i++) {
            $var_name = '$' . $variables[1][$i];
            $var_value = trim($variables[2][$i]);
            $var_map[$var_name] = $var_value;
        }

        // Remove variable declarations first
        $css = preg_replace('/\$[a-zA-Z0-9_-]+\s*:\s*[^;]+;\s*/', '', $css);

        // Replace variables with values (sort by length to handle longer names first)
        $var_names = array_keys($var_map);
        usort($var_names, function($a, $b) {
            return strlen($b) - strlen($a);
        });

        foreach ($var_names as $var_name) {
            $var_value = $var_map[$var_name];
            $css = str_replace($var_name, $var_value, $css);
        }

        // Process nesting (simple)
        $css = $this->process_nesting($css);

        // Clean up whitespace
        $css = preg_replace('/\s+/', ' ', $css);
        $css = str_replace(' {', '{', $css);
        $css = str_replace('{ ', '{', $css);
        $css = str_replace(' }', '}', $css);
        $css = str_replace(';}', '}', $css);

        return trim($css);
    }

    private function process_nesting($scss) {
        // Simple nesting processor for & selectors
        $scss = preg_replace_callback('/([^{}]+)\s*{\s*([^{}]*&[^{}]*{[^{}]*}[^{}]*)\s*}/s',
            function($matches) {
                $parent = trim($matches[1]);
                $content = $matches[2];

                // Extract nested rules with &
                preg_match_all('/&([^{}]*)\s*{\s*([^{}]*)\s*}/', $content, $nested);

                $result = '';

                // Add non-nested properties
                $clean_content = preg_replace('/&[^{}]*\s*{\s*[^{}]*\s*}/', '', $content);
                if (trim($clean_content)) {
                    $result .= $parent . ' {' . trim($clean_content) . '} ';
                }

                // Add nested rules
                for ($i = 0; $i < count($nested[0]); $i++) {
                    $nested_selector = $parent . trim($nested[1][$i]);
                    $nested_props = trim($nested[2][$i]);
                    $result .= $nested_selector . ' {' . $nested_props . '} ';
                }

                return $result;
            }, $scss);

        return $scss;
    }

    private function ensure_directories() {
        if (!file_exists($this->scss_dir)) {
            wp_mkdir_p($this->scss_dir);
        }

        if (!file_exists($this->css_dir)) {
            wp_mkdir_p($this->css_dir);
        }
    }
}