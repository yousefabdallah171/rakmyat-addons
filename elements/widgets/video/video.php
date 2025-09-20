<?php
/**
 * Rakmyat Video Widget
 *
 * @package RakmyatAddons
 * @version 1.0.0
 */

namespace RakmyatAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) {
    exit;
}

class Video extends Widget_Base
{
    public function get_name()
    {
        return 'rakmyat-video';
    }

    public function get_title()
    {
        return __('Video', 'rakmyat-addons');
    }

    public function get_icon()
    {
        return 'eicon-youtube';
    }

    public function get_categories()
    {
        return ['rakmyat-addons'];
    }

    public function get_keywords()
    {
        return ['video', 'youtube', 'vimeo', 'media'];
    }

    public function get_style_depends()
    {
        return ['rakmyat-video'];
    }

    protected function register_controls()
    {
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Video', 'rakmyat-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'video_type',
            [
                'label' => __('Video Type', 'rakmyat-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'youtube',
                'options' => [
                    'youtube' => __('YouTube', 'rakmyat-addons'),
                    'vimeo' => __('Vimeo', 'rakmyat-addons'),
                    'hosted' => __('Self Hosted', 'rakmyat-addons'),
                ],
            ]
        );

        $this->add_control(
            'youtube_url',
            [
                'label' => __('YouTube URL', 'rakmyat-addons'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://www.youtube.com/watch?v=...',
                'condition' => [
                    'video_type' => 'youtube',
                ],
            ]
        );

        $this->add_control(
            'vimeo_url',
            [
                'label' => __('Vimeo URL', 'rakmyat-addons'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://vimeo.com/...',
                'condition' => [
                    'video_type' => 'vimeo',
                ],
            ]
        );

        $this->add_control(
            'hosted_video',
            [
                'label' => __('Video File', 'rakmyat-addons'),
                'type' => Controls_Manager::MEDIA,
                'media_types' => ['video'],
                'condition' => [
                    'video_type' => 'hosted',
                ],
            ]
        );

        $this->add_control(
            'aspect_ratio',
            [
                'label' => __('Aspect Ratio', 'rakmyat-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => '16-9',
                'options' => [
                    '16-9' => '16:9',
                    '4-3' => '4:3',
                    '21-9' => '21:9',
                    '1-1' => '1:1',
                ],
                'selectors' => [
                    '{{WRAPPER}} .rakmyat-video-wrapper' => 'aspect-ratio: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => __('Autoplay', 'rakmyat-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'muted',
            [
                'label' => __('Muted', 'rakmyat-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $video_html = '';

        switch ($settings['video_type']) {
            case 'youtube':
                if (!empty($settings['youtube_url']['url'])) {
                    $video_id = $this->get_youtube_id($settings['youtube_url']['url']);
                    if ($video_id) {
                        $autoplay = $settings['autoplay'] === 'yes' ? '&autoplay=1' : '';
                        $muted = $settings['muted'] === 'yes' ? '&mute=1' : '';
                        $video_html = '<iframe src="https://www.youtube.com/embed/' . esc_attr($video_id) . '?rel=0' . $autoplay . $muted . '" frameborder="0" allowfullscreen></iframe>';
                    }
                }
                break;

            case 'vimeo':
                if (!empty($settings['vimeo_url']['url'])) {
                    $video_id = $this->get_vimeo_id($settings['vimeo_url']['url']);
                    if ($video_id) {
                        $autoplay = $settings['autoplay'] === 'yes' ? '&autoplay=1' : '';
                        $muted = $settings['muted'] === 'yes' ? '&muted=1' : '';
                        $video_html = '<iframe src="https://player.vimeo.com/video/' . esc_attr($video_id) . '?' . $autoplay . $muted . '" frameborder="0" allowfullscreen></iframe>';
                    }
                }
                break;

            case 'hosted':
                if (!empty($settings['hosted_video']['url'])) {
                    $autoplay = $settings['autoplay'] === 'yes' ? ' autoplay' : '';
                    $muted = $settings['muted'] === 'yes' ? ' muted' : '';
                    $video_html = '<video controls' . $autoplay . $muted . '><source src="' . esc_url($settings['hosted_video']['url']) . '" type="video/mp4">Your browser does not support the video tag.</video>';
                }
                break;
        }

        if (!empty($video_html)) {
            echo '<div class="rakmyat-video-wrapper rakmyat-aspect-ratio-' . esc_attr(str_replace('-', '_', $settings['aspect_ratio'])) . '">';
            echo $video_html;
            echo '</div>';
        } else {
            echo '<p>' . __('Please add a video URL.', 'rakmyat-addons') . '</p>';
        }
    }

    private function get_youtube_id($url)
    {
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/';
        preg_match($pattern, $url, $matches);
        return isset($matches[1]) ? $matches[1] : false;
    }

    private function get_vimeo_id($url)
    {
        $pattern = '/(?:vimeo\.com\/)([0-9]+)/';
        preg_match($pattern, $url, $matches);
        return isset($matches[1]) ? $matches[1] : false;
    }
}