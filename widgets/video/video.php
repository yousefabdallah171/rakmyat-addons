<?php

if (!defined('ABSPATH')) {
    exit;
}

class Rakmyat_Video_Widget extends Rakmyat_Base_Widget {

    public function get_name() {
        return 'rakmyat-video';
    }

    public function get_title() {
        return __('Video Player', 'rakmyat-addons');
    }

    public function get_icon() {
        return 'eicon-youtube';
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Video', 'rakmyat-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'video_type',
            [
                'label' => __('Video Type', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'youtube' => __('YouTube', 'rakmyat-addons'),
                    'vimeo' => __('Vimeo', 'rakmyat-addons'),
                    'hosted' => __('Self Hosted', 'rakmyat-addons'),
                ],
                'default' => 'youtube',
            ]
        );

        $this->add_control(
            'youtube_url',
            [
                'label' => __('YouTube URL', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('https://www.youtube.com/watch?v=...', 'rakmyat-addons'),
                'condition' => [
                    'video_type' => 'youtube',
                ],
            ]
        );

        $this->add_control(
            'vimeo_url',
            [
                'label' => __('Vimeo URL', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('https://vimeo.com/...', 'rakmyat-addons'),
                'condition' => [
                    'video_type' => 'vimeo',
                ],
            ]
        );

        $this->add_control(
            'hosted_video',
            [
                'label' => __('Video File', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'media_types' => ['video'],
                'condition' => [
                    'video_type' => 'hosted',
                ],
            ]
        );

        $this->add_control(
            'show_play_icon',
            [
                'label' => __('Show Play Icon', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'rakmyat-addons'),
                'label_off' => __('Hide', 'rakmyat-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'thumbnail',
            [
                'label' => __('Custom Thumbnail', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
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
            'video_width',
            [
                'label' => __('Width', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 1200,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .rakmyat-video' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'play_icon_color',
            [
                'label' => __('Play Icon Color', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .rakmyat-video .play-icon' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_play_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'play_icon_size',
            [
                'label' => __('Play Icon Size', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .rakmyat-video .play-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_play_icon' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render_widget($settings) {
        $video_url = '';
        $embed_url = '';

        if ($settings['video_type'] === 'youtube' && !empty($settings['youtube_url'])) {
            $video_url = $settings['youtube_url'];
            $video_id = $this->get_youtube_id($video_url);
            $embed_url = 'https://www.youtube.com/embed/' . $video_id . '?autoplay=1';
        } elseif ($settings['video_type'] === 'vimeo' && !empty($settings['vimeo_url'])) {
            $video_url = $settings['vimeo_url'];
            $video_id = $this->get_vimeo_id($video_url);
            $embed_url = 'https://player.vimeo.com/video/' . $video_id . '?autoplay=1';
        } elseif ($settings['video_type'] === 'hosted' && !empty($settings['hosted_video']['url'])) {
            $video_url = $settings['hosted_video']['url'];
        }

        if (empty($video_url)) {
            return;
        }
        ?>
        <div class="rakmyat-video">
            <div class="video-container" data-video-type="<?php echo esc_attr($settings['video_type']); ?>" data-embed-url="<?php echo esc_attr($embed_url); ?>" data-video-url="<?php echo esc_attr($video_url); ?>">

                <?php if ($settings['video_type'] === 'hosted') : ?>
                    <video controls width="100%">
                        <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                <?php else : ?>
                    <div class="video-thumbnail" style="background-image: url('<?php echo esc_url($settings['thumbnail']['url']); ?>');">
                        <?php if ($settings['show_play_icon'] === 'yes') : ?>
                            <div class="play-icon">
                                <i class="eicon-play" aria-hidden="true"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="video-iframe" style="display: none;"></div>
                <?php endif; ?>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const videoContainers = document.querySelectorAll('.rakmyat-video .video-container');

            videoContainers.forEach(function(container) {
                const thumbnail = container.querySelector('.video-thumbnail');
                const iframeContainer = container.querySelector('.video-iframe');
                const embedUrl = container.getAttribute('data-embed-url');

                if (thumbnail && iframeContainer && embedUrl) {
                    thumbnail.addEventListener('click', function() {
                        const iframe = document.createElement('iframe');
                        iframe.setAttribute('src', embedUrl);
                        iframe.setAttribute('frameborder', '0');
                        iframe.setAttribute('allowfullscreen', 'true');
                        iframe.setAttribute('width', '100%');
                        iframe.setAttribute('height', '315');

                        iframeContainer.appendChild(iframe);
                        thumbnail.style.display = 'none';
                        iframeContainer.style.display = 'block';
                    });
                }
            });
        });
        </script>
        <?php
    }

    private function get_youtube_id($url) {
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
        return isset($match[1]) ? $match[1] : '';
    }

    private function get_vimeo_id($url) {
        preg_match('/(?:vimeo\.com\/(?:[^\/]*\/)*)([\d]+)/', $url, $match);
        return isset($match[1]) ? $match[1] : '';
    }
}