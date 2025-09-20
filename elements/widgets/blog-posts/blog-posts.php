<?php
/**
 * Rakmyat Blog Posts Widget
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

class Blog_Posts extends Widget_Base
{
    public function get_name()
    {
        return 'rakmyat-blog-posts';
    }

    public function get_title()
    {
        return __('Blog Posts', 'rakmyat-addons');
    }

    public function get_icon()
    {
        return 'eicon-posts-grid';
    }

    public function get_categories()
    {
        return ['rakmyat-addons'];
    }

    public function get_keywords()
    {
        return ['blog', 'posts', 'grid', 'list'];
    }

    public function get_style_depends()
    {
        return ['rakmyat-blog-posts'];
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
            'posts_per_page',
            [
                'label' => __('Posts Per Page', 'rakmyat-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 6,
                'min' => 1,
                'max' => 20,
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => __('Layout', 'rakmyat-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => [
                    'grid' => __('Grid', 'rakmyat-addons'),
                    'list' => __('List', 'rakmyat-addons'),
                ],
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => __('Columns', 'rakmyat-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'condition' => [
                    'layout' => 'grid',
                ],
                'selectors' => [
                    '{{WRAPPER}} .rakmyat-blog-posts-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );

        $this->add_control(
            'show_excerpt',
            [
                'label' => __('Show Excerpt', 'rakmyat-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'excerpt_length',
            [
                'label' => __('Excerpt Length', 'rakmyat-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 20,
                'condition' => [
                    'show_excerpt' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $posts = get_posts([
            'post_type' => 'post',
            'posts_per_page' => $settings['posts_per_page'],
            'post_status' => 'publish',
        ]);

        if (empty($posts)) {
            echo '<p>' . __('No posts found.', 'rakmyat-addons') . '</p>';
            return;
        }

        $layout_class = $settings['layout'] === 'grid' ? 'rakmyat-blog-posts-grid' : 'rakmyat-blog-posts-list';

        echo '<div class="rakmyat-blog-posts ' . esc_attr($layout_class) . '">';

        foreach ($posts as $post) {
            setup_postdata($post);
            ?>
            <article class="rakmyat-blog-post">
                <?php if (has_post_thumbnail($post->ID)) : ?>
                    <div class="post-thumbnail">
                        <a href="<?php echo get_permalink($post->ID); ?>">
                            <?php echo get_the_post_thumbnail($post->ID, 'medium'); ?>
                        </a>
                    </div>
                <?php endif; ?>

                <div class="post-content">
                    <h3 class="post-title">
                        <a href="<?php echo get_permalink($post->ID); ?>">
                            <?php echo get_the_title($post->ID); ?>
                        </a>
                    </h3>

                    <div class="post-meta">
                        <span class="post-date"><?php echo get_the_date('', $post->ID); ?></span>
                        <span class="post-author"><?php echo get_the_author_meta('display_name', $post->post_author); ?></span>
                    </div>

                    <?php if ($settings['show_excerpt'] === 'yes') : ?>
                        <div class="post-excerpt">
                            <?php echo wp_trim_words(get_the_excerpt($post->ID), $settings['excerpt_length'], '...'); ?>
                        </div>
                    <?php endif; ?>

                    <a href="<?php echo get_permalink($post->ID); ?>" class="read-more">
                        <?php echo __('Read More', 'rakmyat-addons'); ?>
                    </a>
                </div>
            </article>
            <?php
        }

        echo '</div>';

        wp_reset_postdata();
    }
}