<?php
/**
 * Tour Card - Elementor widget class.
 *
 * @package Card_Elements_For_Elementor
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Tour Card Elementor widget.
 *
 * Registers the "Tour Card" Elementor widget, which displays a single tour
 * or travel package with an image, name, price, duration/group-size
 * details, description and call-to-action button, plus an optional
 * WhatsApp share link, in one of several selectable card styles.
 * Style-specific markup lives in the `include/tour-card/` templates,
 * selected via the `tour_card_style` control and included by `render()`.
 */
class Tour_Card_Elementor_Widget extends Widget_Base {

	/**
	 * Get the widget name/slug used internally by Elementor.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'tour-card-elementor-widget';
	}

	/**
	 * Get the widget title shown in the Elementor editor panel.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Tour Card', 'card-elements-for-elementor' );
	}

	/**
	 * Get the widget icon shown in the Elementor editor panel.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fas fa-bus-alt';
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
	 * Adding the controls fields for the tour card.
	 *
	 * @return void
	 */
	protected function register_controls() {
		/*
		 * Start tour card controls fields
		 */
		$this->start_controls_section(
			'section_items_data',
			array(
				'label' => esc_html__( 'Tour Card Items', 'card-elements-for-elementor' ),
			)
		);

		$this->add_control(
			'tour_card_style',
			array(
				'label'   => __( 'Tour Card Style', 'card-elements-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'tour-card-style-1' => esc_html__( 'Card Style 1', 'card-elements-for-elementor' ),
					'tour-card-style-2' => esc_html__( 'Card Style 2 (PRO)', 'card-elements-for-elementor' ),
					'tour-card-style-3' => esc_html__( 'Card Style 3 (PRO)', 'card-elements-for-elementor' ),
					'tour-card-style-4' => esc_html__( 'Card Style 4 (PRO)', 'card-elements-for-elementor' ),
					'tour-card-style-5' => esc_html__( 'Card Style 5 (PRO)', 'card-elements-for-elementor' ),
				),
				'default' => 'tour-card-style-1',
			)
		);

		$this->add_control(
			'place_name',
			array(
				'label'       => __( 'Place Name', 'card-elements-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Name', 'card-elements-for-elementor' ),
				'placeholder' => __( 'Enter place name', 'card-elements-for-elementor' ),
			)
		);

		$this->add_control(
			'place_image',
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
					'{{WRAPPER}} .tour-card-style-1 .tour-img-overlay,
                    {{WRAPPER}} .tour-card-style-2 .tour-main-container,
                    {{WRAPPER}} .tour-card-style-3 .tour-img-overlay,
                    {{WRAPPER}} .tour-card-style-4 .tour-img-overlay,
                    {{WRAPPER}} .tour-card-style-5 .tour-img-overlay' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tour_price',
			array(
				'label' => esc_html__( 'Cost', 'card-elements-for-elementor' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'tour_sale',
			array(
				'label'     => esc_html__( 'Add Sale Tag', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'label_on'  => __( 'Show', 'card-elements-for-elementor' ),
				'label_off' => __( 'Hide', 'card-elements-for-elementor' ),
			)
		);

		$this->add_control(
			'tour_sale_text',
			array(
				'label'     => esc_html__( 'Add Sale Text', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array(
					'tour_sale' => 'yes',
				),
			)
		);

		$this->add_control(
			'tour_sale_background',
			array(
				'label'     => esc_html__( 'Sale Background', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'tour_sale' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .tour-card-style-1 .elementor-tour-sale-wrapper,
                    {{WRAPPER}} .tour-card-style-2 .elementor-tour-sale-wrapper,
                    {{WRAPPER}} .tour-card-style-3 .elementor-tour-sale-wrapper,
                    {{WRAPPER}} .tour-card-style-4 .elementor-tour-sale-wrapper,
                    {{WRAPPER}} .tour-card-style-5 .elementor-tour-sale-wrapper' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'tour_sale_alignment',
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
				'condition' => array(
					'tour_sale' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-tour-sale-wrapper' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tour_sale_color',
			array(
				'label'     => esc_html__( 'Sale Text Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'tour_sale' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .tour-card-style-1 .elementor-tour-sale-wrapper .tour-sale-text,
                    {{WRAPPER}} .tour-card-style-2 .elementor-tour-sale-wrapper .tour-sale-text,
                    {{WRAPPER}} .tour-card-style-3 .elementor-tour-sale-wrapper .tour-sale-text,
                    {{WRAPPER}} .tour-card-style-4 .elementor-tour-sale-wrapper .tour-sale-text,
                    {{WRAPPER}} .tour-card-style-5 .elementor-tour-sale-wrapper .tour-sale-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tour_sale_border_radius',
			array(
				'label'      => __( 'Border Radius', 'card-elements-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'condition'  => array(
					'tour_sale' => 'yes',
				),
				'selectors'  => array(
					'{{WRAPPER}} .tour-card-style-1 .elementor-tour-sale-wrapper,
                    {{WRAPPER}} .tour-card-style-2 .elementor-tour-sale-wrapper,
                    {{WRAPPER}} .tour-card-style-3 .elementor-tour-sale-wrapper,
                    {{WRAPPER}} .tour-card-style-4 .elementor-tour-sale-wrapper,
                    {{WRAPPER}} .tour-card-style-5 .elementor-tour-sale-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'typography_sale',
				'condition' => array(
					'tour_sale' => 'yes',
				),
				'selector'  => '{{WRAPPER}} .elementor-tour-sale-wrapper .tour-sale-text',
			)
		);

		$this->add_control(
			'tour_sale_icon',
			array(
				'label'     => 'Sale Icon',
				'type'      => \Elementor\Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-tags',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'tour_sale' => 'yes',
				),
			)
		);

		$this->add_control(
			'icon_sale_color',
			array(
				'label'     => __( 'Icon Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tour-sale-icon i'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .tour-sale-icon svg *' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'sale_icon_size',
			array(
				'label'     => __( 'Sale Icon Size', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'condition' => array(
					'tour_sale' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .tour-sale-icon i'   => 'font-size: {{SIZE}}px;',
					'{{WRAPPER}} .tour-sale-icon svg' => 'width: {{SIZE}}px;',
				),
			)
		);

		$this->add_control(
			'tour_days_icon',
			array(
				'label'   => 'Days Icon',
				'type'    => \Elementor\Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-sun',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'tour_days',
			array(
				'label' => esc_html__( 'Days', 'card-elements-for-elementor' ),
				'type'  => Controls_Manager::NUMBER,
			)
		);

		$this->add_control(
			'tour_person_icon',
			array(
				'label'   => 'Person Icon',
				'type'    => \Elementor\Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-users',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'tour_person',
			array(
				'label' => esc_html__( 'Persons', 'card-elements-for-elementor' ),
				'type'  => Controls_Manager::NUMBER,
			)
		);

		$this->add_control(
			'tour_guide_icon',
			array(
				'label'   => 'Guide Icon',
				'type'    => \Elementor\Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-flag',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'tour_guides',
			array(
				'label'     => esc_html__( 'Guides', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'condition' => array(
					'tour_card_style' => array( 'tour-card-style-1', 'tour-card-style-2', 'tour-card-style-3', 'tour-card-style-4', 'tour-card-style-5' ),
				),
			)
		);

		$this->add_control(
			'display_whatsapp_share',
			array(
				'label'     => __( 'Whatsapp Share', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => __( 'Show', 'card-elements-for-elementor' ),
				'label_off' => __( 'Hide', 'card-elements-for-elementor' ),
			)
		);

		$this->add_control(
			'display_tour_description',
			array(
				'label'     => __( 'Display Description', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => __( 'Show', 'card-elements-for-elementor' ),
				'label_off' => __( 'Hide', 'card-elements-for-elementor' ),
			)
		);

		$this->add_control(
			'tour_description',
			array(
				'label'     => esc_html__( 'Description', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::TEXTAREA,
				'condition' => array(
					'display_tour_description' => 'yes',
				),
				'default'   => __( 'Lorem ipsum dolor sit amet, consectetur adipisci ng elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'card-elements-for-elementor' ),
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'       => __( 'Button Text', 'card-elements-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Book now', 'card-elements-for-elementor' ),
				'placeholder' => __( 'Button text', 'card-elements-for-elementor' ),
			)
		);

		$this->add_control(
			'tour_btn_link',
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
		 * End tour card controls fields
		 */

		$this->start_controls_section(
			'section_tour_icon',
			array(
				'label' => __( 'Icon', 'card-elements-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'type'      => Controls_Manager::SLIDER,
				'label'     => esc_html__( 'Icon Size', 'card-elements-for-elementor' ),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .tour-detail-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tour-detail-icon svg' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'tour_detail_icon_color',
			array(
				'label'     => __( 'Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tour-detail-icon i'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .tour-detail-icon svg *' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		/*
		 * Start control style tab for tour card
		 * Start name control style
		 */
		$this->start_controls_section(
			'section_tour_name',
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
					'{{WRAPPER}} .elementor-tour-name-wrapper' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'name_color',
			array(
				'label'     => __( 'Text Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-tour-name-wrapper' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography_name',
				'selector' => '{{WRAPPER}} .elementor-tour-name-wrapper',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'border',
				'selector'  => '{{WRAPPER}} .tour-card-style-3 .elementor-tour-name-wrapper,
                {{WRAPPER}} .tour-card-style-4 .elementor-tour-name-wrapper,
                {{WRAPPER}} .tour-card-style-5 .elementor-tour-name-wrapper,
                {{WRAPPER}} .tour-card-style-1 .elementor-tour-name-wrapper',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'tour_button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'card-elements-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tour-card-style-1 .elementor-tour-name-wrapper,
                    {{WRAPPER}} .tour-card-style-3 .elementor-tour-name-wrapper,
                    {{WRAPPER}} .tour-card-style-4 .elementor-tour-name-wrapper,
                    {{WRAPPER}} .tour-card-style-5 .elementor-tour-name-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/*
		 * Start position control style
		 */
		$this->start_controls_section(
			'section_tour_cost',
			array(
				'label' => __( 'Cost', 'card-elements-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'type_text_align',
			array(
				'label'     => __( 'Alignment', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
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
						'title' => __( 'Justified', 'card-elements-for-elementor' ),
						'icon'  => 'fa fa-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-tour-price-wrapper' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'position_color',
			array(
				'label'     => __( 'Text Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-tour-price-wrapper' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography_position',
				'selector' => '{{WRAPPER}} .elementor-tour-price-wrapper',
			)
		);

		$this->add_control(
			'separator_color',
			array(
				'label'     => __( 'Separator Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'tour_card_style' => array( 'tour-card-style-2' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .tour-content' => 'border-bottom-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		/*
		 * Start details control style
		 */
		$this->start_controls_section(
			'section_tour_details',
			array(
				'label' => __( 'Details', 'card-elements-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'details_text_color',
			array(
				'label'     => __( 'Text Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tour-detail-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography_detais_text',
				'selector' => '{{WRAPPER}} .tour-detail-text',
			)
		);

		$this->add_control(
			'details_background_color',
			array(
				'label'     => __( 'Background Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'tour_card_style' => array( 'tour-card-style-1' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .tour-detail-ul' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		/*
		 * Start desription control style
		 */
		$this->start_controls_section(
			'section_tour_description',
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
					'{{WRAPPER}} .elementor-tour-description-wrapper' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'descriptsion_color',
			array(
				'label'     => __( 'Text Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-tour-description-wrapper' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography_description',
				'selector' => '{{WRAPPER}} .elementor-tour-description-wrapper',
			)
		);

		$this->end_controls_section();

		/*
		 * Start button control style
		 */
		$this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'Button', 'card-elements-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'button_text_align',
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
				'condition' => array(
					'tour_card_style' => array( 'tour-card-style-2', 'tour-card-style-3', 'tour-card-style-4' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .tour-button' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'btn_border',
				'selector'  => '{{WRAPPER}} .tour-button a',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'tour_btn_border_radius',
			array(
				'label'      => __( 'Border Radius', 'card-elements-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tour-button a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .tour-button a',
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => __( 'Normal', 'card-elements-for-elementor' ),
			)
		);

		$this->add_control(
			'tour_button_text_color',
			array(
				'label'     => __( 'Text Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tour-button a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'background_color',
			array(
				'label'     => __( 'Background Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tour-button a' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label' => __( 'Hover', 'card-elements-for-elementor' ),
			)
		);

		$this->add_control(
			'tour_text_hover_color',
			array(
				'label'     => __( 'Text Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tour-button a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tour_button_background_hover_color',
			array(
				'label'     => __( 'Background Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tour-button a:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tour_button_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tour-button a:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .tour-button a',
			)
		);

		$this->add_responsive_control(
			'text_padding',
			array(
				'label'      => __( 'Padding', 'card-elements-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tour-button a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->end_controls_section();

		/*
		 * Start box control style
		 */
		$this->start_controls_section(
			'section_tour_contentbox',
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
					'{{WRAPPER}} .tour-card-style-1 .tour-container,
                    {{WRAPPER}} .tour-card-style-2 .tour-main-container,
                    {{WRAPPER}} .tour-card-style-3 .tour-img,
                    {{WRAPPER}} .tour-card-style-4 .tour-card-left,
                    {{WRAPPER}} .tour-card-style-4 .tour-card-right,
                    {{WRAPPER}} .tour-card-style-5 .tour-main-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'button_box_background_color',
			array(
				'label'     => __( 'Background Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tour-card-style-1 .tour-main-container,
                    {{WRAPPER}} .tour-card-style-2 .tour-main-container,
                    {{WRAPPER}} .tour-card-style-4 .tour-main-container,
                    {{WRAPPER}} .tour-card-style-5 .tour-main-container' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'image_border',
				'selector'  => '{{WRAPPER}} .tour-card-style-1,
                                {{WRAPPER}} .tour-card-style-2,
                                {{WRAPPER}} .tour-card-style-3,
                                {{WRAPPER}} .tour-card-style-4,
                                {{WRAPPER}} .tour-card-style-5',
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
					'{{WRAPPER}} .tour-card-style-1,
                    {{WRAPPER}} .tour-card-style-2,
                    {{WRAPPER}} .tour-card-style-3,
                    {{WRAPPER}} .tour-card-style-4,
                    {{WRAPPER}} .tour-card-style-5' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}} .tour-card-style-1, {{WRAPPER}} .tour-card-style-2',
			)
		);
		$this->end_controls_section();

		/*
		 * End control style tab for tour card
		 */
	}

	/**
	 * Render the Tour Card widget output on the front-end.
	 *
	 * Reads the `tour_card_style` control value and includes the matching
	 * template from `include/tour-card/` (several style slugs share the
	 * same Pro-style template file, with the free plugin only exposing a
	 * subset of styles). `$settings` is consumed by the included template
	 * via variable scope, not passed as an argument.
	 *
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$tour_btn_link = $settings['tour_btn_link'];
		$target        = $settings['tour_btn_link']['is_external'] ? ' target="_blank"' : '';
		$rel_values    = array();
		if ( $settings['tour_btn_link']['nofollow'] ) {
			$rel_values[] = 'nofollow';
		}
		if ( $settings['tour_btn_link']['is_external'] ) {
			$rel_values[] = 'noopener';
			$rel_values[] = 'noreferrer';
		}
		$rel = $rel_values ? ' rel="' . implode( ' ', array_unique( $rel_values ) ) . '"' : '';
		switch ( $settings['tour_card_style'] ) {
			case 'tour-card-style-1':
				include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/tour-card/elementor-tour-card-1.php';  // Card Style 1.
				break;
			case 'tour-card-style-2':
				include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/tour-card/elementor-tour-card-pro.php';  // Card Style 2.
				break;
			case 'tour-card-style-3':
				include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/tour-card/elementor-tour-card-pro.php';  // Card Style 3.
				break;
			case 'tour-card-style-4':
				include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/tour-card/elementor-tour-card-pro.php';  // Card Style 4.
				break;
			case 'tour-card-style-5':
				include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/tour-card/elementor-tour-card-pro.php';  // Card Style 5.

				break;
			default:
				include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/tour-card/elementor-tour-card-pro.php';  // Default Card Style 1.
				break;
		}
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Tour_Card_Elementor_Widget() );
