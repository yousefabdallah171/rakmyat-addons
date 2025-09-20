<?php
/**
 * Rakmyat Heading Widget
 *
 * @package RakmyatAddons
 * @version 1.0.0
 */

namespace RakmyatAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) {
    exit;
}

class Heading extends Widget_Base
{
    public function get_name()
    {
        return 'rakmyat-heading';
    }

    public function get_title()
    {
        return __('Heading', 'rakmyat-addons');
    }

    public function get_icon()
    {
        return 'eicon-heading';
    }

    public function get_categories()
    {
        return ['rakmyat-addons'];
    }

    public function get_keywords()
    {
        return ['heading', 'title', 'text'];
    }

    public function get_style_depends()
    {
        return ['rakmyat-heading'];
    }

    protected function register_controls()
    {
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'rakmyat-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Title', 'rakmyat-addons'),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => __('Enter your title', 'rakmyat-addons'),
                'default' => __('This is a heading', 'rakmyat-addons'),
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => __('Link', 'rakmyat-addons'),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => '',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'size',
            [
                'label' => __('Size', 'rakmyat-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'small' => __('Small', 'rakmyat-addons'),
                    'default' => __('Default', 'rakmyat-addons'),
                    'medium' => __('Medium', 'rakmyat-addons'),
                    'large' => __('Large', 'rakmyat-addons'),
                    'xl' => __('XL', 'rakmyat-addons'),
                    'xxl' => __('XXL', 'rakmyat-addons'),
                ],
            ]
        );

        $this->add_control(
            'header_size',
            [
                'label' => __('HTML Tag', 'rakmyat-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'h2',
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => __('Alignment', 'rakmyat-addons'),
                'type' => Controls_Manager::CHOOSE,
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
                    'justify' => [
                        'title' => __('Justified', 'rakmyat-addons'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'section_title_style',
            [
                'label' => __('Title', 'rakmyat-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Text Color', 'rakmyat-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rakmyat-heading-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .rakmyat-heading-title',
            ]
        );

        $this->add_control(
            'blend_mode',
            [
                'label' => __('Blend Mode', 'rakmyat-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => __('Normal', 'rakmyat-addons'),
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'darken' => 'Darken',
                    'lighten' => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'difference' => 'Difference',
                    'exclusion' => 'Exclusion',
                    'hue' => 'Hue',
                    'luminosity' => 'Luminosity',
                ],
                'selectors' => [
                    '{{WRAPPER}} .rakmyat-heading-title' => 'mix-blend-mode: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        if (empty($settings['title'])) {
            return;
        }

        $this->add_render_attribute('title', 'class', 'rakmyat-heading-title');

        if (!empty($settings['size'])) {
            $this->add_render_attribute('title', 'class', 'rakmyat-size-' . $settings['size']);
        }

        $this->add_inline_editing_attributes('title');

        $title = $settings['title'];

        if (!empty($settings['link']['url'])) {
            $this->add_link_attributes('url', $settings['link']);
            $title = sprintf('<a %1$s>%2$s</a>', $this->get_render_attribute_string('url'), $title);
        }

        $title_html = sprintf('<%1$s %2$s>%3$s</%1$s>', $settings['header_size'], $this->get_render_attribute_string('title'), $title);

        echo $title_html;
    }

    protected function content_template()
    {
        ?>
        <#
        var title = settings.title;

        if ( '' === title ) {
            return;
        }

        view.addRenderAttribute( 'title', 'class', 'rakmyat-heading-title' );

        if ( '' !== settings.size ) {
            view.addRenderAttribute( 'title', 'class', 'rakmyat-size-' + settings.size );
        }

        view.addInlineEditingAttributes( 'title' );

        var headerSizeTag = settings.header_size,
            title_html = '<' + headerSizeTag  + ' ' + view.getRenderAttributeString( 'title' ) + '>' + title + '</' + headerSizeTag + '>';

        print( title_html );
        #>
        <?php
    }
}