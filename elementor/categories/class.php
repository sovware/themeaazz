<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/accordion/class.php
 * 
 * @author  WpWax
 * @since   1.0
 * @version 1.0
*/ 

use WpWax\FindBiz\DirHelper;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

//Categories
class Categories extends Widget_Base
{
    public function get_name()
    {
        return 'categories';
    }

    public function get_title()
    {
        return __('All Categories', 'findbiz-core');
    }

    public function get_icon()
    {
        return 'findbiz-el-custom';
    }

    public function get_categories()
    {
        return ['findbiz_category'];
    }

    public function get_keywords()
    {
        return ['categories', 'all categories', 'listing categories'];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'categories',
            [
                'label' => __('All Categories', 'findbiz-core'),
            ]
        );

        $this->add_control(
            'logged_in',
            [
                'label'   => __('Show Only For Logged In User?', 'findbiz-core'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        $this->add_control(
            'types',
            [
                'label'    => __('Specify Listing Types', 'findbiz-core'),
                'type'     => Controls_Manager::SELECT2,
                'multiple' => true,
                'options'  => DirHelper::directorist_listing_types(),
            ]
        );

        $this->add_control(
            'default_types',
            [
                'label'    => __('Set Default Listing Type', 'findbiz-core'),
                'type'     => Controls_Manager::SELECT,
                'multiple' => true,
                'options'  => DirHelper::directorist_listing_types(),
            ]
        );

        $this->add_control(
            'view',
            [
                'label'   => __('View', 'findbiz-core'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'grid'    => esc_html__('Grid View', 'findbiz-core'),
                    'list' => esc_html__('List View', 'findbiz-core'),
                    'icon' => esc_html__('Icon View', 'findbiz-core'),
                    'carousel' => esc_html__('Carousel View', 'findbiz-core'),
                    'icon_carousel' => esc_html__('Icon Carousel View', 'findbiz-core'),
                ],
                'default' => 'grid',
            ]
        );

        $this->add_control(
            'row',
            [
                'label'   => esc_html__('Categories Per Row', 'findbiz-core'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    '6' => esc_html__('6 Items / Row', 'findbiz-core'),
                    '4' => esc_html__('4 Items / Row', 'findbiz-core'),
                    '3' => esc_html__('3 Items / Row', 'findbiz-core'),
                    '2' => esc_html__('2 Items / Row', 'findbiz-core'),
                ],
                'default' => '3',
            ]
        );

        $this->add_control(
            'number_cat',
            [
                'label'   => __('Number of categories to Show:', 'findbiz-core'),
                'type'    => Controls_Manager::NUMBER,
                'min'     => 1,
                'max'     => 100,
                'step'    => 1,
                'default' => 6,
            ]
        );

        $this->add_control(
            'order_by',
            [
                'label'   => esc_html__('Order by', 'findbiz-core'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'id',
                'options' => [
                    'id'    => esc_html__(' Cat ID', 'findbiz-core'),
                    'count' => esc_html__('Listing Count', 'findbiz-core'),
                    'name'  => esc_html__('Category name (A-Z)', 'findbiz-core'),
                    'slug'  => esc_html__('Select Category', 'findbiz-core'),
                ],
            ]
        );

        $this->add_control(
            'slug',
            [
                'label'     => esc_html__('Select Categories', 'findbiz-core'),
                'type'      => Controls_Manager::SELECT2,
                'multiple'  => true,
                'options'   => DirHelper::categories(),
                'condition' => [
                    'order_by' => 'slug'
                ]
            ]
        );

        $this->add_control(
            'order_list',
            [
                'label'   => esc_html__('Categories Order', 'findbiz-core'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'desc',
                'options' => array(
                    'asc'  => esc_html__(' ASC', 'findbiz-core'),
                    'desc' => esc_html__(' DESC', 'findbiz-core'),
                ),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'listings_cats',
            [
                'label' => __('Styling', 'findbiz-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title',
            [
                'label'  => __('Title Color', 'findbiz-core'),
                'type'   => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #directorist.atbd_wrapper .atbd_all_categories .atbd_category_single:not(.atbd_category_no_image) .cat-info .cat-name, {{WRAPPER}} #directorist.atbd_wrapper.atbdp-categories.atbdp-text-list .atbd_category_wrapper a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label'  => __('Subtitle Color', 'findbiz-core'),
                'type'   => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .atbd_category_single figure figcaption .cat-box .cat-info span, {{WRAPPER}} #directorist.atbd_wrapper .atbd_all_categories .atbd_category_single:not(.atbd_category_no_image) .cat-info .cat-count span, {{WRAPPER}} .findbiz-cat-view-icon_carousel #directorist.atbd_wrapper .atbd_all_categories .atbd_category_single:not(.atbd_category_no_image) figure figcaption .cat-box .cat-info .cat-count' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings      = $this->get_settings_for_display();
        $default_types = $settings['default_types'];
        $types         = $settings['types'] ? implode( ',', $settings['types'] ) : '';
        $slug          = $settings['slug'] ? implode( ',', $settings['slug'] ) : '';
        $views         = $settings['view'];
        $view          = ('grid' == $views) || ('list' == $views) ? $views : 'grid';
        $order_by      = $settings['order_by'];
        $order         = $settings['order_list'];
        $columns       = $settings['row'];
        $number_cat    = $settings['number_cat'];
        $logged_in     = $settings['logged_in'];

        ( 'icon_carousel' === $views) || ( 'carousel' === $views) ? add_action('findbiz_category_column', function () { echo esc_html('-carousel'); } ) : '';
        ( 'icon' === $views) || ( 'icon_carousel' === $views) ? add_filter('findbiz_category_image', '__return_false') : '';
        ( 'icon' != $views) && ( 'icon_carousel' != $views) ? add_filter('findbiz_category_icon', '__return_false') : '';
        ( 'icon' === $views) || ( 'grid' === $views) || ( 'list' === $views) ? add_filter('findbiz_category_carousel', '__return_false') : '';
        ?>
        <div class="findbiz-cat-view-<?php echo $views; ?>">
            <?php echo do_shortcode( '[directorist_all_categories view="'.$view.'" orderby="'.$order_by.'" order="'.$order.'" cat_per_page="'.$number_cat.'" columns="'.$columns.'" slug="'.$slug.'" logged_in_user_only="'.$logged_in.'" directory_type="'.$types.'" default_directory_type="'.$default_types.'"]' ); ?>
        </div>
        <?php
    }
}