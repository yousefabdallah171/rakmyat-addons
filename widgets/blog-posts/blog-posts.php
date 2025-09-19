<?php

if (!defined('ABSPATH')) {
    exit;
}

class Rakmyat_Blog_Posts_Widget extends Rakmyat_Base_Widget {

    public function get_name() {
        return 'rakmyat-blog-posts';
    }

    public function get_title() {
        return __('Blog Posts', 'rakmyat-addons');
    }

    public function get_icon() {
        return 'eicon-posts-grid';
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Query', 'rakmyat-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => __('Posts Per Page', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 6,
                'min' => 1,
                'max' => 20,
            ]
        );

        $this->add_control(
            'post_categories',
            [
                'label' => __('Categories', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_post_categories(),
                'description' => __('Leave empty to show all categories', 'rakmyat-addons'),
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => __('Order By', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'date' => __('Date', 'rakmyat-addons'),
                    'title' => __('Title', 'rakmyat-addons'),
                    'menu_order' => __('Menu Order', 'rakmyat-addons'),
                    'random' => __('Random', 'rakmyat-addons'),
                ],
                'default' => 'date',
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => __('Order', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'desc' => __('Descending', 'rakmyat-addons'),
                    'asc' => __('Ascending', 'rakmyat-addons'),
                ],
                'default' => 'desc',
            ]
        );

        $this->end_controls_section();

        // Layout Section
        $this->start_controls_section(
            'layout_section',
            [
                'label' => __('Layout', 'rakmyat-addons'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => __('Columns', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
            ]
        );

        $this->add_control(
            'show_image',
            [
                'label' => __('Show Featured Image', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'rakmyat-addons'),
                'label_off' => __('Hide', 'rakmyat-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label' => __('Show Title', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'rakmyat-addons'),
                'label_off' => __('Hide', 'rakmyat-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_excerpt',
            [
                'label' => __('Show Excerpt', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'rakmyat-addons'),
                'label_off' => __('Hide', 'rakmyat-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'excerpt_length',
            [
                'label' => __('Excerpt Length', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 20,
                'min' => 10,
                'max' => 100,
                'condition' => [
                    'show_excerpt' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_date',
            [
                'label' => __('Show Date', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'rakmyat-addons'),
                'label_off' => __('Hide', 'rakmyat-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_author',
            [
                'label' => __('Show Author', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'rakmyat-addons'),
                'label_off' => __('Hide', 'rakmyat-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_read_more',
            [
                'label' => __('Show Read More', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'rakmyat-addons'),
                'label_off' => __('Hide', 'rakmyat-addons'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'read_more_text',
            [
                'label' => __('Read More Text', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Read More', 'rakmyat-addons'),
                'condition' => [
                    'show_read_more' => 'yes',
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
            'posts_gap',
            [
                'label' => __('Posts Gap', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .rakmyat-blog-posts' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rakmyat-blog-posts .post-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .rakmyat-blog-posts .post-title',
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => __('Content Color', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rakmyat-blog-posts .post-excerpt' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'meta_color',
            [
                'label' => __('Meta Color', 'rakmyat-addons'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rakmyat-blog-posts .post-meta' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render_widget($settings) {
        $args = [
            'post_type' => 'post',
            'posts_per_page' => $settings['posts_per_page'],
            'orderby' => $settings['orderby'],
            'order' => $settings['order'],
            'post_status' => 'publish',
        ];

        if (!empty($settings['post_categories'])) {
            $args['category__in'] = $settings['post_categories'];
        }

        $query = new WP_Query($args);

        if (!$query->have_posts()) {
            echo '<p>' . __('No posts found.', 'rakmyat-addons') . '</p>';
            return;
        }

        $columns_class = 'columns-' . $settings['columns'];
        if (isset($settings['columns_tablet'])) {
            $columns_class .= ' columns-tablet-' . $settings['columns_tablet'];
        }
        if (isset($settings['columns_mobile'])) {
            $columns_class .= ' columns-mobile-' . $settings['columns_mobile'];
        }
        ?>
        <div class="rakmyat-blog-posts <?php echo esc_attr($columns_class); ?>">
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <article class="blog-post">
                    <?php if ($settings['show_image'] === 'yes' && has_post_thumbnail()) : ?>
                        <div class="post-thumbnail">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium'); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="post-content">
                        <?php if ($settings['show_title'] === 'yes') : ?>
                            <h3 class="post-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                        <?php endif; ?>

                        <?php if ($settings['show_date'] === 'yes' || $settings['show_author'] === 'yes') : ?>
                            <div class="post-meta">
                                <?php if ($settings['show_date'] === 'yes') : ?>
                                    <span class="post-date"><?php echo get_the_date(); ?></span>
                                <?php endif; ?>

                                <?php if ($settings['show_author'] === 'yes') : ?>
                                    <span class="post-author"><?php _e('by', 'rakmyat-addons'); ?> <?php the_author(); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($settings['show_excerpt'] === 'yes') : ?>
                            <div class="post-excerpt">
                                <?php echo wp_trim_words(get_the_excerpt(), $settings['excerpt_length'], '...'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($settings['show_read_more'] === 'yes') : ?>
                            <div class="post-read-more">
                                <a href="<?php the_permalink(); ?>" class="read-more-btn">
                                    <?php echo esc_html($settings['read_more_text']); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
        <?php
        wp_reset_postdata();
    }

    private function get_post_categories() {
        $categories = get_categories();
        $options = [];

        foreach ($categories as $category) {
            $options[$category->term_id] = $category->name;
        }

        return $options;
    }
}