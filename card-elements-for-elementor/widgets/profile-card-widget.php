<?php
/**
 * Profile Card - Elementor widget class.
 *
 * @package Card_Elements_For_Elementor
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Profile Card Elementor widget.
 *
 * Registers the "Profile Card" Elementor widget, which displays a person's
 * name, position, photo, optional background image and description in one
 * of several selectable card styles. Used for team/staff profiles, author
 * bios, and similar "about a person" content. Style-specific markup lives
 * in the `include/profile-card/` templates, selected via the
 * `profile_card_style` control and included by `render()`.
 */
class Profile_Card_Elementor_Widget extends Widget_Base {

	/**
	 * Get the widget name/slug used internally by Elementor.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'profile-card-elementor-widget';
	}

	/**
	 * Get the widget title shown in the Elementor editor panel.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Profile Card', 'card-elements-for-elementor' );
	}

	/**
	 * Get the widget icon shown in the Elementor editor panel.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-icon-box';
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
		 * Start profile card controls fields
		 */
		$this->start_controls_section(
			'section_items_data',
			array(
				'label' => esc_html__( 'Profile Card Items', 'card-elements-for-elementor' ),
			)
		);

		$this->add_control(
			'profile_card_style',
			array(
				'label'   => __( 'Profile Card Style', 'card-elements-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'profile-card-style-1'  => esc_html__( 'Card Style 1', 'card-elements-for-elementor' ),
					'profile-card-style-2'  => esc_html__( 'Card Style 2', 'card-elements-for-elementor' ),
					'profile-card-style-3'  => esc_html__( 'Card Style 3', 'card-elements-for-elementor' ),
					'profile-card-style-4'  => esc_html__( 'Card Style 4', 'card-elements-for-elementor' ),
					'profile-card-style-5'  => esc_html__( 'Card Style 5', 'card-elements-for-elementor' ),
					'profile-card-style-6'  => esc_html__( 'Card Style 6 (PRO)', 'card-elements-for-elementor' ),
					'profile-card-style-7'  => esc_html__( 'Card Style 7 (PRO)', 'card-elements-for-elementor' ),
					'profile-card-style-8'  => esc_html__( 'Card Style 8 (PRO)', 'card-elements-for-elementor' ),
					'profile-card-style-9'  => esc_html__( 'Card Style 9 (PRO)', 'card-elements-for-elementor' ),
					'profile-card-style-10' => esc_html__( 'Card Style 10 (PRO)', 'card-elements-for-elementor' ),
					'profile-card-style-11' => esc_html__( 'Card Style 11', 'card-elements-for-elementor' ),
				),
				'default' => 'profile-card-style-1',
			)
		);

		$this->add_control(
			'name',
			array(
				'label'       => __( 'Name', 'card-elements-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'John Doe', 'card-elements-for-elementor' ),
				'placeholder' => __( 'Enter your name', 'card-elements-for-elementor' ),
			)
		);

		$this->add_control(
			'position',
			array(
				'label'       => __( 'Position', 'card-elements-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Developer', 'card-elements-for-elementor' ),
				'placeholder' => __( 'Developer', 'card-elements-for-elementor' ),
			)
		);

		$this->add_control(
			'display_profile_description',
			array(
				'label'     => __( 'Display Profile Description', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => __( 'Show', 'card-elements-for-elementor' ),
				'label_off' => __( 'Hide', 'card-elements-for-elementor' ),
				'condition' => array(
					'profile_card_style' => array(
						'profile-card-style-1',
						'profile-card-style-2',
						'profile-card-style-3',
						'profile-card-style-4',
						'profile-card-style-5',
					),
				),
			)
		);

		$this->add_control(
			'profile_description',
			array(
				'label'     => esc_html__( 'Description', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::TEXTAREA,
				'condition' => array(
					'display_profile_description' => 'yes',
					'profile_card_style'          => array(
						'profile-card-style-1',
						'profile-card-style-2',
						'profile-card-style-3',
						'profile-card-style-4',
						'profile-card-style-5',
					),
				),
				'default'   => __( 'Lorem ipsum dolor sit amet, consectetur adipisci ng elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'card-elements-for-elementor' ),
			)
		);

		$this->add_control(
			'profile_image',
			array(
				'label'   => __( 'Profile Image', 'card-elements-for-elementor' ),
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
			'profile_background_image',
			array(
				'label'     => __( 'Profile Background Image', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'profile_card_style' => array(
						'profile-card-style-1',
						'profile-card-style-2',
						'profile-card-style-3',
						'profile-card-style-4',
						'profile-card-style-5',
					),
				),
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);

		$this->end_controls_section();

		/*
		 * End profile card controls fields
		 */

		/*
		 * Start Social Media control for profile card
		 */
		$this->start_controls_section(
			'section_social_media',
			array(
				'label' => esc_html__( 'Social Media', 'card-elements-for-elementor' ),
			)
		);

		$this->add_control(
			'display_social_icon',
			array(
				'label'     => __( 'Display Social Icon', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => __( 'Show', 'card-elements-for-elementor' ),
				'label_off' => __( 'Hide', 'card-elements-for-elementor' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'social',
			array(
				'label'       => __( 'Icon', 'card-elements-for-elementor' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'default'     => 'fab fa-wordpress',
				'include'     => array(
					'fab fa-android',
					'fab fa-apple',
					'fab fa-behance',
					'fab fa-bitbucket',
					'fab fa-codepen',
					'fab fa-delicious',
					'fab fa-deviantart',
					'fab fa-digg',
					'fab fa-dribbble',
					'fab fa-envelope',
					'fab fa-facebook',
					'fab fa-flickr',
					'fab fa-foursquare',
					'fab fa-free-code-camp',
					'fab fa-github',
					'fab fa-gitlab',
					'fab fa-globe',
					'fab fa-google-plus',
					'fab fa-houzz',
					'fab fa-instagram',
					'fab fa-jsfiddle',
					'fab fa-link',
					'fab fa-linkedin',
					'fab fa-medium',
					'fab fa-meetup',
					'fab fa-mixcloud',
					'fab fa-odnoklassniki',
					'fab fa-pinterest',
					'fab fa-product-hunt',
					'fab fa-reddit',
					'fab fa-rss',
					'fab fa-shopping-cart',
					'fab fa-skype',
					'fab fa-slideshare',
					'fab fa-snapchat',
					'fab fa-soundcloud',
					'fab fa-spotify',
					'fab fa-stack-overflow',
					'fab fa-steam',
					'fab fa-stumbleupon',
					'fab fa-telegram',
					'fab fa-thumb-tack',
					'fab fa-tripadvisor',
					'fab fa-tumblr',
					'fab fa-twitch',
					'fab fa-twitter',
					'fab fa-vimeo',
					'fab fa-vk',
					'fab fa-weibo',
					'fab fa-weixin',
					'fab fa-whatsapp',
					'fab fa-wordpress',
					'fab fa-xing',
					'fab fa-yelp',
					'fab fa-youtube',
					'fab fa-500px',
				),
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'       => __( 'Link', 'card-elements-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'label_block' => true,
				'default'     => array(
					'is_external' => 'true',
				),
				'placeholder' => __( 'https://your-link.com', 'card-elements-for-elementor' ),
			)
		);

		$this->add_control(
			'social_icon_list',
			array(
				'label'       => __( 'Social Icons', 'card-elements-for-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'condition'   => array(
					'display_social_icon' => 'yes',
				),
				'default'     => array(
					array(
						'social' => 'fab fa-facebook',
					),
					array(
						'social' => 'fab fa-twitter',
					),
					array(
						'social' => 'fab fa-google-plus',
					),
					array(
						'social' => 'fab fa-wordpress',
					),
				),
				'title_field' => '<i class="{{ social }}"></i> {{{ social.replace( \'fab fa-\', \'\' ).replace( \'-\', \' \' ).replace( /\b\w/g, function( letter ){ return letter.toUpperCase() } ) }}}',
			)
		);

		$this->add_control(
			'shape',
			array(
				'label'        => __( 'Shape', 'card-elements-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'rounded',
				'options'      => array(
					'rounded' => __( 'Rounded', 'card-elements-for-elementor' ),
					'square'  => __( 'Square', 'card-elements-for-elementor' ),
					'circle'  => __( 'Circle', 'card-elements-for-elementor' ),
				),
				'prefix_class' => 'elementor-shape-',
			)
		);

		$this->add_responsive_control(
			'social_icon_align',
			array(
				'label'     => __( 'Alignment', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'card-elements-for-elementor' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'card-elements-for-elementor' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'card-elements-for-elementor' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .elementor-social-icons-wrapper' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		/*
		 * End Social Media control for profile card
		 */

		/*
		 * Start control style tab for profile card
		 * Start name control style
		 */
		$this->start_controls_section(
			'section_profile_name',
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
					'{{WRAPPER}} .elementor-profile-name-wrapper,
				 {{WRAPPER}} .profile-card-style-11 .name' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'name_color',
			array(
				'label'     => __( 'Text Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-profile-name-wrapper,
				{{WRAPPER}} .profile-card-style-11 .name' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography_name',
				'selector' => '{{WRAPPER}} .elementor-profile-name-wrapper,
						{{WRAPPER}} .profile-card-style-11 .name',
			)
		);

		$this->end_controls_section();

		/*
		 * End control style tab for profile card
		 */

		/*
		 * Start position control style
		 */
		$this->start_controls_section(
			'section_profile_position',
			array(
				'label' => __( 'Designation', 'card-elements-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'position_text_align',
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
					'{{WRAPPER}} .elementor-profile-position-wrapper,
		{{WRAPPER}} .profile-card-style-11 .position' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'position_color',
			array(
				'label'     => __( 'Text Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-profile-position-wrapper,
		{{WRAPPER}} .profile-card-style-11 .position' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography_position',
				'selector' => '{{WRAPPER}} .elementor-profile-position-wrapper,
                            {{WRAPPER}} .profile-card-style-11 .position',
			)
		);

		$this->end_controls_section();

		/*
		 * Start desription control style
		 */
		$this->start_controls_section(
			'section_profile_description',
			array(
				'label'     => __( 'Description', 'card-elements-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'profile_card_style' => array(
						'profile-card-style-1',
						'profile-card-style-2',
						'profile-card-style-3',
						'profile-card-style-4',
						'profile-card-style-5',
					),
				),
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
					'{{WRAPPER}} .elementor-profile-description-wrapper' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => __( 'Text Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-profile-description-wrapper' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography_description',
				'selector' => '{{WRAPPER}} .elementor-profile-description-wrapper',
			)
		);

		$this->end_controls_section();

		/*
		 * Start content background control style
		 */
		$this->start_controls_section(
			'section_content_background_style',
			array(
				'label'     => __( 'Content Box Style', 'card-elements-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'profile_card_style' => array(
						'profile-card-style-1',
						'profile-card-style-2',
						'profile-card-style-3',
						'profile-card-style-4',
						'profile-card-style-5',
					),
				),
			)
		);

		$this->add_control(
			'content_background_color',
			array(
				'label'     => __( 'Background Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-content-background-color-wrapper' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		/*
		 * End content background control style
		 */

		/*
		 * Start social media control style
		 */
		$this->start_controls_section(
			'section_social_style',
			array(
				'label' => __( 'Social Icon', 'card-elements-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'content_social_background_color',
			array(
				'label'     => __( 'Social Area Background Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'profile_card_style' => array( 'profile-card-style-1', 'profile-card-style-2', 'profile-card-style-11' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .team-member__socialmedia' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'   => __( 'Social Color', 'card-elements-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default' => __( 'Official Color', 'card-elements-for-elementor' ),
					'custom'  => __( 'Custom', 'card-elements-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'icon_primary_color',
			array(
				'label'     => __( 'Primary Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'icon_color' => 'custom',
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-social-icon:not(:hover)' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_secondary_color',
			array(
				'label'     => __( 'Secondary Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'icon_color' => 'custom',
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-social-icon:not(:hover) i' => 'color: {{VALUE}};',
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
						'min' => 6,
						'max' => 300,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-social-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_padding',
			array(
				'label'          => __( 'Padding', 'card-elements-for-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'selectors'      => array(
					'{{WRAPPER}} .elementor-social-icon' => 'padding: {{SIZE}}{{UNIT}};',
				),
				'default'        => array(
					'unit' => 'em',
				),
				'tablet_default' => array(
					'unit' => 'em',
				),
				'mobile_default' => array(
					'unit' => 'em',
				),
				'range'          => array(
					'em' => array(
						'min' => 0,
						'max' => 5,
					),
				),
			)
		);

		$icon_spacing = is_rtl() ? 'margin-left: {{SIZE}}{{UNIT}};' : 'margin-right: {{SIZE}}{{UNIT}};';

		$this->add_responsive_control(
			'icon_spacing',
			array(
				'label'     => __( 'Spacing', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'condition' => array(
					'profile_card_style' => array(
						'profile-card-style-1',
						'profile-card-style-2',
						'profile-card-style-3',
						'profile-card-style-4',
						'profile-card-style-5',
						'profile-card-style-6',
						'profile-card-style-11',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-social-icon:not(:last-child)' => $icon_spacing,
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'image_border', // We know this mistake - TODO: 'icon_border' (for hover control condition also).
				'selector'  => '{{WRAPPER}} .elementor-social-icon',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'border_radius',
			array(
				'label'      => __( 'Border Radius', 'card-elements-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/*
		 * End social media control style
		 */

		/*
		 * Start socail media hover control style
		 */
		$this->start_controls_section(
			'section_social_hover',
			array(
				'label' => __( 'Social Icon Hover', 'card-elements-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'hover_primary_color',
			array(
				'label'     => __( 'Primary Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'icon_color' => 'custom',
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-social-icon:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'hover_secondary_color',
			array(
				'label'     => __( 'Secondary Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'icon_color' => 'custom',
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-social-icon:hover i' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'hover_border_color',
			array(
				'label'     => __( 'Border Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'image_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-social-icon:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'hover_animation',
			array(
				'label' => __( 'Hover Animation', 'card-elements-for-elementor' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			)
		);

		$this->end_controls_section();

		/* start section */
		$this->start_controls_section(
			'section_background_style',
			array(
				'label'     => __( 'Content Background Color', 'card-elements-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'profile_card_style' => array(
						'profile-card-style-11',
					),
				),
			)
		);

		$this->add_control(
			'name_background_color',
			array(
				'label'     => __( 'Name', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .profile-card-style-11 .name' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'position_background_color',
			array(
				'label'     => __( 'Position', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .profile-card-style-11 .position' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'triangle_background_style',
			array(
				'label'     => __( 'Triangle Color', 'card-elements-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'profile_card_style' => array(
						'profile-card-style-11',
					),
				),
			)
		);

		$this->add_control(
			'triangle_background_color',
			array(
				'label'     => __( 'Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .profile-card-style-11 .triangle-div' => 'border-top: solid 30px {{VALUE}}; border-left: solid 30px {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		/*
		 * End control style tab for profile card
		 */
	}

	/**
	 * Render the Profile Card widget output on the front-end.
	 *
	 * Reads the `profile_card_style` control value and includes the
	 * matching template from `include/profile-card/` (several style slugs
	 * share the same Pro-style template file, with the free plugin only
	 * exposing a subset of styles). `$settings` and `$class_animation` are
	 * consumed by the included template via variable scope, not passed as
	 * arguments.
	 *
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$class_animation = '';

		if ( ! empty( $settings['hover_animation'] ) ) {
			$class_animation = ' elementor-animation-' . $settings['hover_animation'];
		}

		switch ( $settings['profile_card_style'] ) {
			case 'profile-card-style-1':
				include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/profile-card/elementor-profile-card-1.php';  // Card Style 1.
				break;
			case 'profile-card-style-2':
				include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/profile-card/elementor-profile-card-2.php';  // Card Style 2.
				break;
			case 'profile-card-style-3':
				include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/profile-card/elementor-profile-card-3.php';  // Card Style 3.
				break;
			case 'profile-card-style-4':
				include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/profile-card/elementor-profile-card-4.php';  // Card Style 4.
				break;
			case 'profile-card-style-5':
				include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/profile-card/elementor-profile-card-5.php';  // Card Style 5.
				break;
			case 'profile-card-style-6':
				include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/profile-card/elementor-profile-card-pro.php';  // Card Style pro.
				break;
			case 'profile-card-style-7':
				include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/profile-card/elementor-profile-card-pro.php';  // Card Style pro.
				break;
			case 'profile-card-style-8':
				include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/profile-card/elementor-profile-card-pro.php';  // Card Style pro.
				break;
			case 'profile-card-style-9':
				include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/profile-card/elementor-profile-card-pro.php';  // Card Style pro.
				break;
			case 'profile-card-style-10':
				include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/profile-card/elementor-profile-card-pro.php';  // Card Style pro.
				break;
			case 'profile-card-style-11':
				include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/profile-card/elementor-profile-card-11.php';  // Card Style 11.
				break;
			default:
				include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/profile-card/elementor-profile-card-1.php';  // Default Card Style 1.
				break;
		}
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Profile_Card_Elementor_Widget() );
