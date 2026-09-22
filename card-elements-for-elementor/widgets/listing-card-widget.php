<?php
/**
 * Listing Card - Elementor widget class.
 *
 * @package Card_Elements_For_Elementor
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Listing Card Elementor widget.
 *
 * Registers the "Listing Card" Elementor widget, which displays a single
 * listing (e.g. a property, product or place) with an image, name,
 * description, price and call-to-action button, plus an optional WhatsApp
 * share link, in one of several selectable card styles. Style-specific
 * markup lives in the `include/listing-card/` templates, selected via the
 * `listing_card_style` control and included by `render()`.
 */
class Listing_Card_Elementor_Widget extends Widget_Base {

	/**
	 * Get the widget name/slug used internally by Elementor.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'listing-card-elementor-widget';
	}

	/**
	 * Get the widget title shown in the Elementor editor panel.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Listing Card', 'card-elements-for-elementor' );
	}

	/**
	 * Get the widget icon shown in the Elementor editor panel.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-bullet-list';
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
	 * Adding the controls fields for the listing card.
	 *
	 * @return void
	 */
	protected function register_controls() {
		/* Start listing card controls fields */
		$this->start_controls_section(
			'section_items_data',
			array(
				'label' => esc_html__( 'Listing Card Items', 'card-elements-for-elementor' ),
			)
		);

		/* Listing Card Style */
		$this->add_control(
			'listing_card_style',
			array(
				'label'   => __( 'Listing Card Style', 'card-elements-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'listing-card-style-1' => esc_html__( 'Card Style 1', 'card-elements-for-elementor' ),
					'listing-card-style-2' => esc_html__( 'Card Style 2 (PRO)', 'card-elements-for-elementor' ),
					'listing-card-style-3' => esc_html__( 'Card Style 3 (PRO)', 'card-elements-for-elementor' ),
					'listing-card-style-4' => esc_html__( 'Card Style 4 (PRO)', 'card-elements-for-elementor' ),
					'listing-card-style-5' => esc_html__( 'Card Style 5 (PRO)', 'card-elements-for-elementor' ),
				),
				'default' => 'listing-card-style-1',
			)
		);

		/* Attach Image */
		$this->add_control(
			'select_image',
			array(
				'label'   => __( 'Image', 'card-elements-for-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => array(
					'active' => true,
				),
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);

		$this->add_control(
			'image_background_overlay',
			array(
				'label'     => __( 'Background Overlay', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .listing-card-style-1 .listing-img-overlay,
                    {{WRAPPER}} .listing-card-style-2 .listing-img-overlay,
                    {{WRAPPER}} .listing-card-style-3 .listing-img-overlay,
                    {{WRAPPER}} .listing-card-style-4 .listing-img-overlay,
                    {{WRAPPER}} .listing-card-style-5 .listing-img-overlay' => 'background-color: {{VALUE}};',
				),
			)
		);

		/* Name */
		$this->add_control(
			'name',
			array(
				'label'       => __( 'Name', 'card-elements-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Name', 'card-elements-for-elementor' ),
				'placeholder' => __( 'Enter name', 'card-elements-for-elementor' ),
			)
		);

		$this->add_control(
			'listing_whatsapp_share',
			array(
				'label'     => __( 'Whatsapp Share', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => __( 'Show', 'card-elements-for-elementor' ),
				'label_off' => __( 'Hide', 'card-elements-for-elementor' ),
			)
		);

		/* Show hide Description */
		$this->add_control(
			'display_listing_description',
			array(
				'label'     => __( 'Display Description', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => __( 'Show', 'card-elements-for-elementor' ),
				'label_off' => __( 'Hide', 'card-elements-for-elementor' ),
			)
		);

		/* Description */
		$this->add_control(
			'listing_description',
			array(
				'label'     => esc_html__( 'Description', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::TEXTAREA,
				'condition' => array(
					'display_listing_description' => 'yes',
				),
				'default'   => __( 'Lorem ipsum dolor sit amet, consectetur adipisci ng elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'card-elements-for-elementor' ),
			)
		);

		$this->add_control(
			'listing_price',
			array(
				'label'   => esc_html__( 'Price', 'card-elements-for-elementor' ),
				'default' => __( '10$', 'card-elements-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'       => __( 'Button Text', 'card-elements-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'condition'   => array(
					'listing_card_style' => array( 'listing-card-style-1', 'listing-card-style-4', 'listing-card-style-5' ),
				),
				'default'     => __( 'View more', 'card-elements-for-elementor' ),
				'placeholder' => __( 'Button text', 'card-elements-for-elementor' ),
			)
		);

		$this->add_control(
			'listing_btn_link',
			array(
				'label'         => esc_html__( 'URL (Link)', 'card-elements-for-elementor' ),
				'type'          => \Elementor\Controls_Manager::URL,
				'show_external' => true,
				'default'       => array(
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				),
			)
		);

		$this->end_controls_section();

		/*
		 * End listing card ratings controls fields
		 */

		$this->start_controls_section(
			'section_rating',
			array(
				'label' => __( 'Rating', 'card-elements-for-elementor' ),
			)
		);

		$this->add_control(
			'rating_scale',
			array(
				'label'   => __( 'Rating Scale', 'card-elements-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'5'  => '0-5',
					'10' => '0-10',
				),
				'default' => '5',
			)
		);

		$this->add_control(
			'rating',
			array(
				'label'   => __( 'Rating', 'card-elements-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 0,
				'max'     => 10,
				'step'    => 0.1,
				'default' => 5,
			)
		);

		$this->add_control(
			'star_style',
			array(
				'label'        => __( 'Icon', 'card-elements-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'star_fontawesome' => __( 'Font Awesome', 'card-elements-for-elementor' ),
					'star_unicode'     => __( 'Unicode', 'card-elements-for-elementor' ),
				),
				'default'      => 'star_unicode',
				'render_type'  => 'template',
				'prefix_class' => 'elementor--star-style-',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'unmarked_star_style',
			array(
				'label'       => __( 'Unmarked Style', 'card-elements-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'solid'   => array(
						'title' => __( 'Solid', 'card-elements-for-elementor' ),
						'icon'  => 'fa fa-star',
					),
					'outline' => array(
						'title' => __( 'Outline', 'card-elements-for-elementor' ),
						'icon'  => 'fa fa-star-o',
					),
				),
				'default'     => 'solid',
			)
		);

		$this->end_controls_section();

		/*
		 * End listing card controls fields
		 */

		/*
		 * Start control style tab for listing card
		 * Start name control style
		 */
		$this->start_controls_section(
			'section_listing_name',
			array(
				'label' => __( 'Name', 'card-elements-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'name_text_align',
			array(
				'label'     => __( 'Alignment', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'condition' => array(
					'listing_card_style' => array( 'listing-card-style-1', 'listing-card-style-3' ),
				),
				'options'   => array(
					'left'    => array(
						'title' => __( 'Left', 'card-elements-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'card-elements-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'card-elements-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => __( 'Justified', 'card-elements-for-elementor' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-listing-name-wrapper' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'name_color',
			array(
				'label'     => __( 'Text Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-listing-name-wrapper' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'name_bg_color',
			array(
				'label'     => __( 'Background Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'listing_card_style' => array( 'listing-card-style-2' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .wrapper-name,
                    {{WRAPPER}} .bg-color-name' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography_name',
				'selector' => '{{WRAPPER}} .elementor-listing-name-wrapper',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'border',
				'selector'  => '{{WRAPPER}} .listing-card-style-1 .elementor-listing-name-wrapper,
                            {{WRAPPER}} .listing-card-style-3 .elementor-listing-name-wrapper,
                            {{WRAPPER}} .listing-card-style-4 .elementor-listing-name-wrapper,
                            {{WRAPPER}} .listing-card-style-5 .elementor-listing-name-wrapper',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'recipe_button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'card-elements-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .listing-card-style-1 .elementor-listing-name-wrapper,
                    {{WRAPPER}} .listing-card-style-3 .elementor-listing-name-wrapper,
                    {{WRAPPER}} .listing-card-style-4 .elementor-listing-name-wrapper,
                    {{WRAPPER}} .listing-card-style-5 .elementor-listing-name-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/*
		 * Start price control style
		 */
		$this->start_controls_section(
			'section_listing_price',
			array(
				'label' => __( 'Price', 'card-elements-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'price_text_align',
			array(
				'label'     => __( 'Alignment', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'condition' => array(
					'listing_card_style' => array( 'listing-card-style-1', 'listing-card-style-3' ),
				),
				'options'   => array(
					'left'    => array(
						'title' => __( 'Left', 'card-elements-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'card-elements-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'card-elements-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => __( 'Justified', 'card-elements-for-elementor' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-listing-price-wrapper' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => __( 'Text Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-listing-price-wrapper' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'price_background_color',
			array(
				'label'     => __( 'Background Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'listing_card_style' => array( 'listing-card-style-2' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .wrapper-price ,
                    {{WRAPPER}} .actions ,
                    {{WRAPPER}} .bg-color' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography_position',
				'selector' => '{{WRAPPER}} .elementor-listing-price-wrapper',
			)
		);

		$this->end_controls_section();

		/*
		 * Start description control style
		 */
		$this->start_controls_section(
			'section_listing_description',
			array(
				'label' => __( 'Description', 'card-elements-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'description_text_align',
			array(
				'label'     => __( 'Alignment', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => __( 'Left', 'card-elements-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'card-elements-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'card-elements-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => __( 'Justified', 'card-elements-for-elementor' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-listing-description-wrapper' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => __( 'Text Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-listing-description-wrapper' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography_description',
				'selector' => '{{WRAPPER}} .elementor-listing-description-wrapper',
			)
		);

		$this->end_controls_section();

		/*
		 * Start Stars control style
		 */
		$this->start_controls_section(
			'section_stars_style',
			array(
				'label' => __( 'Stars', 'card-elements-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'star_align',
			array(
				'label'     => __( 'Alignment', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'     => array(
						'title' => __( 'Left', 'card-elements-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'   => array(
						'title' => __( 'Center', 'card-elements-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end' => array(
						'title' => __( 'Right', 'card-elements-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-star-rating__wrapper' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'     => __( 'Size', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'icon_space',
			array(
				'label'     => __( 'Spacing', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array(
					'body:not(.rtl) {{WRAPPER}} .elementor-star-rating i:not(:last-of-type)' => 'margin-right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .elementor-star-rating i:not(:last-of-type)' => 'margin-left: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'stars_color',
			array(
				'label'     => __( 'Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-star-rating i:before' => 'color: {{VALUE}}',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'stars_unmarked_color',
			array(
				'label'     => __( 'Unmarked Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-star-rating i' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		/*
		 * Start button control style
		 */
		$this->start_controls_section(
			'section_listing_button',
			array(
				'label'     => __( 'Button', 'card-elements-for-elementor' ),
				'condition' => array(
					'listing_card_style' => array( 'listing-card-style-1', 'listing-card-style-4', 'listing-card-style-5' ),
				),
				'tab'       => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'button_text_align',
			array(
				'label'     => __( 'Alignment', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'card-elements-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'card-elements-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'card-elements-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .listing-button a' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => __( 'Text Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .listing-button a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_text_hover_color',
			array(
				'label'     => __( 'Text Hover Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .listing-button a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_background_color',
			array(
				'label'     => __( 'Background Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .listing-button a' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_background_hover_color',
			array(
				'label'     => __( 'Background Hover Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .listing-button a:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography_button_text',
				'selector' => '{{WRAPPER}} .listing-button a',
			)
		);

		$this->end_controls_section();

		/*
		 * Start box control style
		 */
		$this->start_controls_section(
			'section_listing_contentbox',
			array(
				'label' => __( 'Box', 'card-elements-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'box_padding',
			array(
				'label'      => __( 'Padding', 'card-elements-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'devices'    => array( 'desktop', 'tablet', 'mobile' ),
				'selectors'  => array(
					'{{WRAPPER}} .listing-card-style-1 .listing-content,
                    {{WRAPPER}} .listing-card-style-2 .listing-content,
                    {{WRAPPER}} .listing-card-style-3 .listing-main-container,
                    {{WRAPPER}} .listing-card-style-4 .listing-img,
                    {{WRAPPER}} .listing-card-style-5 .listing-main-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'button_box_background_color',
			array(
				'label'     => __( 'Background Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .listing-card-style-1 .listing-main-container,
                    {{WRAPPER}} .listing-card-style-2 .listing-main-container,
                    {{WRAPPER}} .listing-card-style-3,
                    {{WRAPPER}} .listing-card-style-5 .listing-main-container' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'image_border',
				'selector'  => '{{WRAPPER}} .listing-card-style-1,
                {{WRAPPER}} .listing-card-style-2,
                {{WRAPPER}} .listing-card-style-3,
                {{WRAPPER}} .listing-card-style-4,
                {{WRAPPER}} .listing-card-style-5',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'border_radius',
			array(
				'label'      => __( 'Border Radius', 'card-elements-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'devices'    => array( 'desktop', 'tablet', 'mobile' ),
				'selectors'  => array(
					'{{WRAPPER}} .listing-card-style-1,
                    {{WRAPPER}} .listing-card-style-2,
                    {{WRAPPER}} .listing-card-style-3,
                    {{WRAPPER}} .listing-card-style-4,
                    {{WRAPPER}} .listing-card-style-5' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}} .listing-card-style-1,
                    {{WRAPPER}} .listing-card-style-2,
                    {{WRAPPER}} .listing-card-style-3',
			)
		);
		$this->end_controls_section();

		/*
		 * End control style tab for listing card
		 */
	}

	/**
	 * Get the rating value and scale for this listing.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return array {
	 *     @type float $rating       The (possibly clamped) rating value.
	 *     @type int   $rating_scale The maximum rating scale.
	 * }
	 */
	protected function get_rating() {
		$settings     = $this->get_settings_for_display();
		$rating_scale = (int) $settings['rating_scale'];
		$rating       = (float) $settings['rating'] > $rating_scale ? $rating_scale : $settings['rating'];

		return array( $rating, $rating_scale );
	}

	/**
	 * Render the star rating markup using the given icon for each star.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @param string $icon The HTML markup for a single star icon.
	 * @return string
	 */
	protected function render_stars( $icon ) {
		$rating_data    = $this->get_rating();
		$rating         = $rating_data[0];
		$floored_rating = (int) $rating;
		$stars_html     = '';

		for ( $stars = 1; $stars <= $rating_data[1]; $stars++ ) {
			if ( $stars <= $floored_rating ) {
				$stars_html .= '<i class="elementor-star-full">' . $icon . '</i>';
			} elseif ( $floored_rating + 1 === $stars && $rating !== $floored_rating ) {
				$stars_html .= '<i class="elementor-star-' . ( $rating - $floored_rating ) * 10 . '">' . $icon . '</i>';
			} else {
				$stars_html .= '<i class="elementor-star-empty">' . $icon . '</i>';
			}
		}

		return $stars_html;
	}

	/**
	 * Render the Listing Card widget output on the front-end.
	 *
	 * Reads the `listing_card_style` control value and includes the
	 * matching template from `include/listing-card/` (several style slugs
	 * share the same Pro-style template file, with the free plugin only
	 * exposing a subset of styles). `$settings` is consumed by the
	 * included template via variable scope, not passed as an argument.
	 *
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$listing_btn_link = $settings['listing_btn_link'];
		$target           = $settings['listing_btn_link']['is_external'] ? ' target="_blank"' : '';
		$rel_values       = array();
		if ( $settings['listing_btn_link']['nofollow'] ) {
			$rel_values[] = 'nofollow';
		}
		if ( $settings['listing_btn_link']['is_external'] ) {
			$rel_values[] = 'noopener';
			$rel_values[] = 'noreferrer';
		}
		$rel            = $rel_values ? ' rel="' . implode( ' ', array_unique( $rel_values ) ) . '"' : '';
		$rating_data    = $this->get_rating();
		$textual_rating = $rating_data[0] . '/' . $rating_data[1];
		$icon           = '&#61445;';

		if ( 'outline' === $settings['unmarked_star_style'] ) {
			$icon = '&#9734;';
		} elseif ( 'solid' === $settings['unmarked_star_style'] ) {
			$icon = '&#9733;';
		}

		$this->add_render_attribute(
			'icon_wrapper',
			array(
				'class'     => 'elementor-star-rating',
				'title'     => $textual_rating,
				'itemtype'  => esc_url( 'http://schema.org/Rating' ),
				'itemscope' => '',
				'itemprop'  => 'reviewRating',
			)
		);

		$schema_rating = '<span itemprop="ratingValue" class="elementor-screen-only">' . $textual_rating . '</span>';
		$stars_element = '<div ' . $this->get_render_attribute_string( 'icon_wrapper' ) . '>' . $this->render_stars( $icon ) . ' ' . $schema_rating . '</div>';

		switch ( $settings['listing_card_style'] ) {
			case 'listing-card-style-1':
				include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/listing-card/elementor-listing-card-1.php';  // Card Style 1.
				break;
			case 'listing-card-style-2':
				include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/listing-card/elementor-listing-card-pro.php';  // Card Style 2.
				break;
			case 'listing-card-style-3':
				include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/listing-card/elementor-listing-card-pro.php';  // Card Style 3.
				break;
			case 'listing-card-style-4':
				include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/listing-card/elementor-listing-card-pro.php';  // Card Style pro 4.
				break;
			case 'listing-card-style-5':
				include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/listing-card/elementor-listing-card-pro.php';  // Card Style pro 5.
				break;
			default:
				include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/listing-card/elementor-listing-card-1.php';  // Default Card Style 1.
				break;
		}
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Listing_Card_Elementor_Widget() );
