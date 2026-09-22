<?php
/**
 * Post Card - Elementor widget class.
 *
 * @package Card_Elements_For_Elementor
 */

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Group_Control_Base;
use ElementorPro\Classes\Utils;
use ElementorPro\Modules\QueryControl\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Post Card Elementor widget.
 *
 * Registers the "Post Card" Elementor widget, which runs a `WP_Query` for
 * the site's blog posts (with configurable post count, columns, excerpt
 * length/source, category filtering, etc.) and displays them as a grid of
 * cards in one of several selectable styles. Style-specific markup lives
 * in the `include/post-card/` templates, selected via the
 * `post_card_style` control and included by `render()`.
 */
class Post_Card_Elementor_Widget extends Widget_Base {

	/**
	 * Get the widget name/slug used internally by Elementor.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'post-card-elementor-widget';
	}

	/**
	 * Get the widget title shown in the Elementor editor panel.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Post Card', 'card-elements-for-elementor' );
	}

	/**
	 * Get the widget icon shown in the Elementor editor panel.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	/**
	 * Get the category this widget is assigned to.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'card-elements' );
	}

	/**
	 * Adding the controls fields for the Card Elements.
	 *
	 * @return void
	 */
	protected function register_controls() {
		/*
		 * Start post card controls fields
		 */
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Post Layout', 'card-elements-for-elementor' ),
			)
		);

		$this->add_control(
			'post_card_style',
			array(
				'label'   => __( 'Post Card Style', 'card-elements-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'post-card-style-1' => esc_html__( 'Card Style 1', 'card-elements-for-elementor' ),
					'post-card-style-2' => esc_html__( 'Card Style 2', 'card-elements-for-elementor' ),
					'post-card-style-3' => esc_html__( 'Card Style 3 (PRO)', 'card-elements-for-elementor' ),
					'post-card-style-4' => esc_html__( 'Card Style 4 (PRO)', 'card-elements-for-elementor' ),
					'post-card-style-5' => esc_html__( 'Card Style 5 (PRO)', 'card-elements-for-elementor' ),
					'post-card-style-6' => esc_html__( 'Card Style 6', 'card-elements-for-elementor' ),
				),
				'default' => 'post-card-style-1',
			)
		);

		$this->add_responsive_control(
			'post_card_columns',
			array(
				'label'              => __( 'Columns', 'card-elements-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '3',
				'tablet_default'     => '2',
				'mobile_default'     => '1',
				'options'            => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'prefix_class'       => 'elementor-grid%s-',
				'frontend_available' => true,
				'selectors'          => array(
					'.elementor-msie {{WRAPPER}} .elementor-post-item' => 'width: calc( 100% / {{SIZE}} )',
				),
			)
		);

		$this->add_control(
			'number_of_posts',
			array(
				'label'   => __( 'Display No. of Posts', 'card-elements-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 6,
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'post_image_size',
				'exclude' => array( 'custom' ),
				'default' => 'medium_large',
			)
		);

		$this->add_control(
			'show_title',
			array(
				'label'     => __( 'Show Title', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_off' => __( 'Off', 'card-elements-for-elementor' ),
				'label_on'  => __( 'On', 'card-elements-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'     => __( 'Title HTML Tag', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				),
				'default'   => 'h2',
				'condition' => array(
					'show_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_excerpt',
			array(
				'label'     => __( 'Excerpt', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'card-elements-for-elementor' ),
				'label_off' => __( 'Hide', 'card-elements-for-elementor' ),
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'excerpt_length',
			array(
				'label'     => __( 'Excerpt Length', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => apply_filters( 'excerpt_length', 25 ),
				'condition' => array( 'show_excerpt' => 'yes' ),
			)
		);
		$this->add_control(
			'excerpt_from',
			array(
				'label'     => __( 'Excerpt From', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'content' => 'Content',
					'excerpt' => 'Excerpt',
				),
				'default'   => 'content',
				'condition' => array(
					'show_excerpt' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_read_more',
			array(
				'label'     => __( 'Read More', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'card-elements-for-elementor' ),
				'label_off' => __( 'Hide', 'card-elements-for-elementor' ),
				'default'   => 'yes',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'read_more_text',
			array(
				'label'     => __( 'Read More Text', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Read More »', 'card-elements-for-elementor' ),
				'condition' => array( 'show_read_more' => 'yes' ),
			)
		);

		$this->add_control(
			'show_meta_data',
			array(
				'label'     => __( 'Show Meta Data', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'card-elements-for-elementor' ),
				'label_off' => __( 'Hide', 'card-elements-for-elementor' ),
				'default'   => 'yes',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'meta_data',
			array(
				'label'       => __( 'Meta Data', 'card-elements-for-elementor' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT2,
				'default'     => array( 'author', 'date', 'comments', 'tags', 'category' ),
				'multiple'    => true,
				'condition'   => array( 'show_meta_data' => 'yes' ),
				'options'     => array(
					'author'   => __( 'Author', 'card-elements-for-elementor' ),
					'date'     => __( 'Date', 'card-elements-for-elementor' ),
					'comments' => __( 'Comments', 'card-elements-for-elementor' ),
					'tags'     => __( 'Tags', 'card-elements-for-elementor' ),
					'category' => __( 'Category', 'card-elements-for-elementor' ),
				),
				'separator'   => 'before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_post_query',
			array(
				'label' => esc_html__( 'Query', 'card-elements-for-elementor' ),
			)
		);

		$this->add_control(
			'blog_categories',
			array(
				'label'       => __( 'Categories', 'card-elements-for-elementor' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'description' => __( 'Select the categories you want to show', 'card-elements-for-elementor' ),
				'options'     => card_elements_post_categories(),
			)
		);

		$this->add_control(
			'advanced',
			array(
				'label' => __( 'Advanced', 'card-elements-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'post_card_orderby',
			array(
				'label'   => __( 'Order By', 'card-elements-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'post_date',
				'options' => array(
					'post_date'  => __( 'Date', 'card-elements-for-elementor' ),
					'post_title' => __( 'Title', 'card-elements-for-elementor' ),
					'menu_order' => __( 'Menu Order', 'card-elements-for-elementor' ),
					'rand'       => __( 'Random', 'card-elements-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'post_sort_order',
			array(
				'label'   => __( 'Sort By', 'card-elements-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => array(
					'asc'  => __( 'ASC', 'card-elements-for-elementor' ),
					'desc' => __( 'DESC', 'card-elements-for-elementor' ),
				),
			)
		);

		$this->end_controls_section();

		/*
		 * End post card control
		 */

		/*
		 * Start control style tab for post card
		 * Start name control style
		 */
		$this->start_controls_section(
			'section_design_layout',
			array(
				'label' => __( 'Layout', 'card-elements-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'post_card_grid_gap',
			array(
				'label'              => __( 'Grid Gap', 'card-elements-for-elementor' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => array(
					'size' => 30,
				),
				'range'              => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'frontend_available' => true,
				'selectors'          => array(
					'{{WRAPPER}} .post-card-grid-gap' => 'grid-column-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'post_card_row_gap',
			array(
				'label'              => __( 'Rows Gap', 'card-elements-for-elementor' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => array(
					'size' => 35,
				),
				'range'              => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'frontend_available' => true,
				'selectors'          => array(
					'{{WRAPPER}} .post-card-container,
				{{WRAPPER}} .post-card-style-6' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'post_card_border_radius',
			array(
				'label'      => __( 'Border Radius', 'card-elements-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .post-card-box-radius' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'post_card_style' => array( 'post-card-style-2', 'post-card-style-6' ),
				),
			)
		);

		$this->add_control(
			'alignment',
			array(
				'label'        => __( 'Alignment', 'card-elements-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'label_block'  => false,
				'options'      => array(
					'left'    => array(
						'title' => __( 'Left', 'card-elements-for-elementor' ),
						'icon'  => 'fa fa-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'card-elements-for-elementor' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'card-elements-for-elementor' ),
						'icon'  => 'fa fa-align-right',
					),
					'justify' => array(
						'title' => __( 'Justify', 'card-elements-for-elementor' ),
						'icon'  => 'fa fa-align-justify',
					),
				),
				'default'      => 'left',
				'prefix_class' => 'elementor-posts--align-',
				'selectors'    => array(
					'{{WRAPPER}} .post-card-alignment,
				 {{WRAPPER}} .card_align,
				 {{WRAPPER}} .card-category,
                 {{WRAPPER}} .card_title' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_design_image_layout',
			array(
				'label'     => __( 'Image', 'card-elements-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'post_card_style' => array( 'post-card-style-1', 'post-card-style-2' ),
				),
			)
		);

		$this->add_control(
			'img_border_radius',
			array(
				'label'      => __( 'Border Radius', 'card-elements-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .post-card-item_img, {{WRAPPER}} .elementor-post-item__overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs(
			'thumbnail_effects_tabs',
			array(
				'label'     => __( 'Image Thumbnail', 'card-elements-for-elementor' ),
				'condition' => array(
					'post_card_style' => array( 'post-card-style-1', 'post-card-style-2' ),
				),
			)
		);

		$this->start_controls_tab(
			'normal',
			array(
				'label' => __( 'Normal', 'card-elements-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'      => 'thumbnail_filters',
				'condition' => array(
					'post_card_style' => array( 'post-card-style-1', 'post-card-style-2' ),
				),
				'selector'  => '{{WRAPPER}} .post-card_thumbnail img',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hover',
			array(
				'label' => __( 'Hover', 'card-elements-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'      => 'thumbnail_hover_filters',
				'condition' => array(
					'post_card_style' => array( 'post-card-style-1', 'post-card-style-2' ),
				),
				'selector'  => '{{WRAPPER}} .elementor-post:hover .post-card_thumbnail:hover img',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_design_content_bg',
			array(
				'label'     => __( 'Background Color', 'card-elements-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'post_card_style' => array( 'post-card-style-1', 'post-card-style-2' ),
				),
			)
		);

		$this->add_control(
			'category_bg_color',
			array(
				'label'     => __( 'Category Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array( 'post_card_style' => 'post-card-style-1' ),
				'selectors' => array(
					'{{WRAPPER}} .post-card_category_background' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'date_bg_color',
			array(
				'label'     => __( 'Date Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .post-card_date' => 'background-color: {{VALUE}};',
				),
				'condition' => array( 'post_card_style' => 'post-card-style-1' ),
			)
		);

		$this->add_control(
			'content_box_bg_color',
			array(
				'label'     => __( 'Content Box Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .post-card-content-box, 
				{{WRAPPER}} .post-card-content-bg-box' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_design_content',
			array(
				'label' => __( 'Content', 'card-elements-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'date_style',
			array(
				'label' => __( 'Date', 'card-elements-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'date_color',
			array(
				'label'     => __( 'Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .post-card_date_color,
                {{WRAPPER}} .post-card-style-6 .card-time' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'category_style',
			array(
				'label' => __( 'Category', 'card-elements-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'category_color',
			array(
				'label'     => __( 'Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .post-card_category,
                {{WRAPPER}} .post-content .post-card_category,
                {{WRAPPER}} .post-card_category a,
                {{WRAPPER}} .post-card-style-6 .card-category' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'category_hover_color',
			array(
				'label'     => __( 'Hover Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .post-card_category,
                {{WRAPPER}} .post-card_category:hover span.cat-links a,
                {{WRAPPER}} .post-content .post-card_category a:hover,
                {{WRAPPER}} .post-card-style-6 .card-category' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'cateogry_typography',
				'selector' => '{{WRAPPER}} .post-card_category,
                {{WRAPPER}} .post-card_category a',
			)
		);

		$this->add_control(
			'category_spacing',
			array(
				'label'     => __( 'Spacing', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'condition' => array(
					'post_card_style' => array( 'post-card-style-2', 'post-card-style-3', 'post-card-style-4', 'post-card-style-5', 'post-card-style-6' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .post-card_category' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_title_style',
			array(
				'label'     => __( 'Title', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array( 'show_title' => 'yes' ),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .post-card_title,
                {{WRAPPER}} .post-card_title a,
                {{WRAPPER}} .post-card-style-6 .card-title a' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'show_title' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'title_typography',
				'selector'  =>
				'{{WRAPPER}} .post-card_title,
            {{WRAPPER}} .post-card_title a,
            {{WRAPPER}} .post-card-style-6 .card-title a',
				'condition' => array(
					'show_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'title_spacing',
			array(
				'label'     => __( 'Spacing', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .post-card_title,
		{{WRAPPER}} .post-card-style-6 .card-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'show_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'heading_excerpt_style',
			array(
				'label'     => __( 'Excerpt', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'show_excerpt' => 'yes',
				),
			)
		);

		$this->add_control(
			'excerpt_color',
			array(
				'label'     => __( 'Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .post-card_excerpt' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'show_excerpt' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'excerpt_typography',
				'selector'  => '{{WRAPPER}} .post-card_excerpt',
				'condition' => array(
					'show_excerpt' => 'yes',
				),
			)
		);

		$this->add_control(
			'excerpt_spacing',
			array(
				'label'     => __( 'Spacing', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .post-card_excerpt' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'show_excerpt' => 'yes',
				),
			)
		);

		$this->add_control(
			'heading_readmore_style',
			array(
				'label'     => __( 'Read More', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array( 'show_read_more' => 'yes' ),
			)
		);

		$this->add_control(
			'read_more_color',
			array(
				'label'     => __( 'Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .post-card_read-more' => 'color: {{VALUE}};',
				),
				'condition' => array( 'show_read_more' => 'yes' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'read_more_typography',
				'selector'  => '{{WRAPPER}} .post-card_read-more',
				'condition' => array( 'show_read_more' => 'yes' ),
			)
		);

		$this->add_control(
			'heading_meta_style',
			array(
				'label'     => __( 'Meta', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array( 'show_meta_data' => 'yes' ),
			)
		);

		$this->add_control(
			'meta_color',
			array(
				'label'     => __( 'Icon Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .post-card_meta-data span .fa,
                {{WRAPPER}} .post-card_meta-data span .fas,
                {{WRAPPER}} .post-card-style-6 .card-by span .fa' => 'color: {{VALUE}};',
				),
				'condition' => array( 'show_meta_data' => 'yes' ),
			)
		);

		$this->add_control(
			'meta_separator_color',
			array(
				'label'     => __( 'Text Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .post-card_meta-data span,
                {{WRAPPER}} .post-card_meta-data a,
                {{WRAPPER}} .post-card-style-6 .card-by a' => 'color: {{VALUE}};',
				),
				'condition' => array( 'show_meta_data' => 'yes' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'meta_typography',
				'selector'  =>
				'{{WRAPPER}} .post-card_meta-data,
            {{WRAPPER}} .post-meta i .fa,
            {{WRAPPER}} .post-card_meta-data i.fa,
            {{WRAPPER}} .post-card_meta-data i.fas,
            {{WRAPPER}} .post-card_meta-data span a,
            {{WRAPPER}} .post-card-style-6 .card-author,
            {{WRAPPER}} .post-card-style-6 .card-by a',
				'condition' => array( 'show_meta_data' => 'yes' ),
			)
		);

		$this->add_control(
			'meta_spacing',
			array(
				'label'     => __( 'Spacing', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .post-card_meta-data,
		{{WRAPPER}} .post-card-style-6 .card-by' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition' => array( 'show_meta_data' => 'yes' ),
			)
		);

		$this->end_controls_section();

		/*
		 * End control style tab for post card
		 */
	}

	/**
	 * Render the Post Card widget output on the front-end.
	 *
	 * Runs a `WP_Query` for published posts using the widget's settings
	 * (post count, category, offset, etc.), then for each post in the loop
	 * includes the template matching the `post_card_style` control value
	 * from `include/post-card/` (several style slugs share the same
	 * Pro-style template file, with the free plugin only exposing a subset
	 * of styles). The current post and `$settings` are consumed by the
	 * included template via variable scope, not passed as arguments.
	 *
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$card_style = $settings['post_card_style'];

		if ( '1' === $settings['post_card_columns'] ) {
			$column = 'elementor-grid-1';
		} elseif ( '2' === $settings['post_card_columns'] ) {
			$column = 'elementor-grid-2';
		} elseif ( '3' === $settings['post_card_columns'] ) {
			$column = 'elementor-grid-3';
		} elseif ( '4' === $settings['post_card_columns'] ) {
			$column = 'elementor-grid-4';
		} elseif ( '5' === $settings['post_card_columns'] ) {
			$column = 'elementor-grid-5';
		} elseif ( '6' === $settings['post_card_columns'] ) {
			$column = 'elementor-grid-6';
		}

		$args = array(
			'post_type'      => 'post',
			'posts_per_page' => $settings['number_of_posts'],
			'post_status'    => 'publish',
			'orderby'        => $settings['post_card_orderby'],
			'order'          => $settings['post_sort_order'],
		);

		if ( isset( $settings['blog_categories'] ) && ! empty( $settings['blog_categories'] ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'category',
				'field'    => 'ID',
				'terms'    => $settings['blog_categories'],
			);
		}

		$blog_posts = new \WP_Query( $args );

		if ( $blog_posts->have_posts() ) :
			?> 
			<div class="post_card grid-container post-card-grid-gap elementor-grid <?php echo esc_attr( $card_style . ' ' . $column ); ?>" > 
				<?php
				while ( $blog_posts->have_posts() ) :
					$blog_posts->the_post();

					switch ( $settings['post_card_style'] ) {
						case 'post-card-style-1':
							include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/post-card/elementor-post-card-1.php';  // Card Style 1.
							break;
						case 'post-card-style-2':
							include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/post-card/elementor-post-card-2.php';  // Card Style 2.
							break;
						case 'post-card-style-3':
							include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/post-card/elementor-post-card-pro.php';  // Card Style pro.
							break;
						case 'post-card-style-4':
							include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/post-card/elementor-post-card-pro.php';  // Card Style pro.
							break;
						case 'post-card-style-5':
							include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/post-card/elementor-post-card-pro.php';  // Card Style pro.
							break;
						case 'post-card-style-6':
							include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/post-card/elementor-post-card-6.php';  // Card Style 6.
							break;
						default:
							include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/post-card/elementor-post-card-1.php';  // Default Card Style 1.
							break;
					}

				endwhile;
				wp_reset_postdata();
				?>
			</div> 
			<?php
		endif;
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Post_Card_Elementor_Widget() );
