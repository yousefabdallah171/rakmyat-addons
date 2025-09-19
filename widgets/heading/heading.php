<?php

if (!defined('ABSPATH')) {
    exit;
}

class Rakmyat_Heading_Widget extends Rakmyat_Base_Widget {

    public function get_name() {
        return 'rakmyat-heading';
    }

    public function get_title() {
        return __('Heading', 'rakmyat-addons');
    }

    public function get_icon() {
        return 'eicon-heading';
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'rakmyat-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Title', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Amazing Heading', 'rakmyat-addons'),
                'placeholder' => __('Type your title here', 'rakmyat-addons'),
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => __('HTML Tag', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                ],
                'default' => 'h2',
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label' => __('Subtitle', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __('Optional subtitle', 'rakmyat-addons'),
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'rakmyat-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label' => __('Alignment', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'rakmyat-addons'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'rakmyat-addons'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'rakmyat-addons'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .rakmyat-heading' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rakmyat-heading h1, {{WRAPPER}} .rakmyat-heading h2, {{WRAPPER}} .rakmyat-heading h3, {{WRAPPER}} .rakmyat-heading h4, {{WRAPPER}} .rakmyat-heading h5, {{WRAPPER}} .rakmyat-heading h6' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .rakmyat-heading h1, {{WRAPPER}} .rakmyat-heading h2, {{WRAPPER}} .rakmyat-heading h3, {{WRAPPER}} .rakmyat-heading h4, {{WRAPPER}} .rakmyat-heading h5, {{WRAPPER}} .rakmyat-heading h6',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'title_text_shadow',
                'label' => __('Title Text Shadow', 'rakmyat-addons'),
                'selector' => '{{WRAPPER}} .rakmyat-heading h1, {{WRAPPER}} .rakmyat-heading h2, {{WRAPPER}} .rakmyat-heading h3, {{WRAPPER}} .rakmyat-heading h4, {{WRAPPER}} .rakmyat-heading h5, {{WRAPPER}} .rakmyat-heading h6',
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => __('Subtitle Color', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rakmyat-heading .subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .rakmyat-heading .subtitle',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'subtitle_text_shadow',
                'label' => __('Subtitle Text Shadow', 'rakmyat-addons'),
                'selector' => '{{WRAPPER}} .rakmyat-heading .subtitle',
            ]
        );

        $this->end_controls_section();
    }

    protected function render_widget($settings) {
        $title_tag = $settings['title_tag'];
        ?>
        <div class="rakmyat-heading">
            <?php if (!empty($settings['title'])) : ?>
                <<?php echo esc_attr($title_tag); ?> class="title">
                    <?php echo esc_html($settings['title']); ?>
                </<?php echo esc_attr($title_tag); ?>>
            <?php endif; ?>

            <?php if (!empty($settings['subtitle'])) : ?>
                <p class="subtitle"><?php echo esc_html($settings['subtitle']); ?></p>
            <?php endif; ?>
        </div>
        <?php
    }
}