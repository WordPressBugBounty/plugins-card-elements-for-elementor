<?php
/**
 * Testimonial Card - Elementor widget class.
 *
 * @package Card_Elements_For_Elementor
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Testimonial Card Elementor widget.
 *
 * Registers the "Testimonial Card" Elementor widget, which displays a
 * customer/client testimonial with a name, position, photo and quote in
 * one of several selectable card styles. Style-specific markup lives in
 * the `include/testimonial-card/` templates, selected via the
 * `testimonial_card_style` control and included by `render()`.
 */
class Testimonial_Card_Elementor_Widget extends Widget_Base {

	/**
	 * Get the widget name/slug used internally by Elementor.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'testimonial-card-elementor-widget';
	}

	/**
	 * Get the widget title shown in the Elementor editor panel.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Testimonial Card', 'card-elements-for-elementor' );
	}

	/**
	 * Get the widget icon shown in the Elementor editor panel.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-testimonial';
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
	 * Adding the controls fields for the testimonial card.
	 *
	 * @return void
	 */
	protected function register_controls() {
		/*
		 * Start testimonial card controls fields
		 */
		$this->start_controls_section(
			'section_items_data',
			array(
				'label' => esc_html__( 'Testimonial Card Items', 'card-elements-for-elementor' ),
			)
		);

		$this->add_control(
			'testimonial_card_style',
			array(
				'label'   => __( 'Testimonial Card Style', 'card-elements-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'testimonial-card-style-1' => esc_html__( 'Card Style 1', 'card-elements-for-elementor' ),
					'testimonial-card-style-2' => esc_html__( 'Card Style 2', 'card-elements-for-elementor' ),
					'testimonial-card-style-3' => esc_html__( 'Card Style 3 (PRO)', 'card-elements-for-elementor' ),
					'testimonial-card-style-4' => esc_html__( 'Card Style 4 (PRO)', 'card-elements-for-elementor' ),
					'testimonial-card-style-5' => esc_html__( 'Card Style 5 (PRO)', 'card-elements-for-elementor' ),
					'testimonial-card-style-6' => esc_html__( 'Card Style 6', 'card-elements-for-elementor' ),
				),
				'default' => 'testimonial-card-style-1',
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
				'label'       => __( 'Designation', 'card-elements-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Founder', 'card-elements-for-elementor' ),
				'placeholder' => __( 'Founder', 'card-elements-for-elementor' ),
			)
		);

		$this->add_control(
			'display_testimonial_description',
			array(
				'label'     => __( 'Display Testimonial Description', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => __( 'Show', 'card-elements-for-elementor' ),
				'label_off' => __( 'Hide', 'card-elements-for-elementor' ),
			)
		);

		$this->add_control(
			'testimonial_description',
			array(
				'label'     => esc_html__( 'Description', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::TEXTAREA,
				'condition' => array(
					'display_testimonial_description' => 'yes',
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
					'testimonial_card_style' => array( 'testimonial-card-style-none' ),
				),
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);

		$this->end_controls_section();

		/*
		 * End testimonial card controls fields
		 */
		$this->start_controls_section(
			'section_rating',
			array(
				'label'     => __( 'Rating', 'card-elements-for-elementor' ),
				// This condition previously duplicated the 'testimonial_card_style' key with two different
				// values; PHP silently kept only the last one ('testimonial-card-style-2'), so that is the
				// value kept here to avoid changing which style this section is shown for.
				'condition' => array(
					'testimonial_card_style' => array( 'testimonial-card-style-2' ),
				),
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
					'star_fontawesome' => 'Font Awesome',
					'star_unicode'     => 'Unicode',
				),
				'default'      => 'star_fontawesome',
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
						'icon'  => 'fab fa-star',
					),
					'outline' => array(
						'title' => __( 'Outline', 'card-elements-for-elementor' ),
						'icon'  => 'fab fa-star-o',
					),
				),
				'default'     => 'solid',
			)
		);

		$this->add_control(
			'title',
			array(
				'label'     => __( 'Title', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'        => __( 'Alignment', 'card-elements-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
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
						'title' => __( 'Justified', 'card-elements-for-elementor' ),
						'icon'  => 'fa fa-align-justify',
					),
				),
				'prefix_class' => 'elementor-star-rating--align-',
				'selectors'    => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		/*
		 * Start control style tab for testimonial card
		 * Start name control style
		 */
		$this->start_controls_section(
			'section_testimonial_name',
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
					'{{WRAPPER}} .elementor-testimonial-name-wrapper,
				 {{WRAPPER}} .testimonial6 .name' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'name_color',
			array(
				'label'     => __( 'Text Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-testimonial-name-wrapper,
				{{WRAPPER}} .testimonial6 .name'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .testimonial6 .pic' => 'border: 4px solid {{VALUE}}',
					'{{WRAPPER}} .testimonial6 .description' => 'border-bottom: 4px solid {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography_name',
				'selector' => '{{WRAPPER}} .elementor-testimonial-name-wrapper,
						{{WRAPPER}} .testimonial6 .name',
			)
		);

		$this->end_controls_section();

		/*
		 * Start position control style
		 */
		$this->start_controls_section(
			'section_testimonial_position',
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
					'{{WRAPPER}} .elementor-testimonial-position-wrapper,
				{{WRAPPER}} .testimonial6 .post' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .testimonial-card-style-2 .testimonial-position' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'position_color',
			array(
				'label'     => __( 'Text Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-testimonial-position-wrapper,
                    {{WRAPPER}} .testimonial6 .post' => 'color: {{VALUE}};',
					'{{WRAPPER}} .testimonial6 .pic,
                    {{WRAPPER}} .testimonial6 .description' => 'box-shadow: 0 7px rgba(0, 0, 0, 0.1), 0 5px {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography_position',
				'selector' => '{{WRAPPER}} .elementor-testimonial-position-wrapper,
                {{WRAPPER}} .testimonial6 .post',
			)
		);

		$this->end_controls_section();

		/*
		 * Start desription control style
		 */
		$this->start_controls_section(
			'section_testimonial_description',
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
					'{{WRAPPER}} .elementor-testimonial-description-wrapper,
				 {{WRAPPER}} .testimonial6 .description' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => __( 'Text Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-testimonial-description-wrapper,
				 {{WRAPPER}} .testimonial6 .description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography_description',
				'selector' => '{{WRAPPER}} .elementor-testimonial-description-wrapper,
						   {{WRAPPER}} .testimonial6 .description',
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
					'testimonial_card_style' => array( 'testimonial-card-style-1' ),
				),
			)
		);

		$this->add_control(
			'content_background_color',
			array(
				'label'     => __( 'Background Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'testimonial_card_style' => array( 'testimonial-card-style-1' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-content-background-color-wrapper' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_style',
			array(
				'label'     => __( 'Title', 'card-elements-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'title!' => '',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Text Color', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-star-rating__title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .elementor-star-rating__title',
			)
		);

		$this->add_responsive_control(
			'title_gap',
			array(
				'label'     => __( 'Gap', 'card-elements-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array(
					'body:not(.rtl) {{WRAPPER}}:not(.elementor-star-rating--align-justify) .elementor-star-rating__title' => 'margin-right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}}:not(.elementor-star-rating--align-justify) .elementor-star-rating__title' => 'margin-left: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_stars_style',
			array(
				'label'     => __( 'Stars', 'card-elements-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				// This condition previously duplicated the 'testimonial_card_style' key with two different
				// values; PHP silently kept only the last one ('testimonial-card-style-2'), so that is the
				// value kept here to avoid changing which style this section is shown for.
				'condition' => array(
					'testimonial_card_style' => array( 'testimonial-card-style-2' ),
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
		 * End control style tab for testimonial card
		 */
	}

	/**
	 * Get the rating value and scale for this testimonial.
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
	 * Render the Testimonial Card widget output on the front-end.
	 *
	 * Reads the `testimonial_card_style` control value and includes the
	 * matching template from `include/testimonial-card/` (several style
	 * slugs share the same Pro-style template file, with the free plugin
	 * only exposing a subset of styles). `$settings` is consumed by the
	 * included template via variable scope, not passed as an argument.
	 *
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$rating_data    = $this->get_rating();
		$textual_rating = $rating_data[0] . '/' . $rating_data[1];
		$icon           = '&#61445;';

		if ( 'star_fontawesome' === $settings['star_style'] ) {
			if ( 'outline' === $settings['unmarked_star_style'] ) {
				$icon = '&#61446;';
			}
		} elseif ( 'star_unicode' === $settings['star_style'] ) {
			$icon = '&#9733;';

			if ( 'outline' === $settings['unmarked_star_style'] ) {
				$icon = '&#9734;';
			}
		}

		$this->add_render_attribute(
			'icon_wrapper',
			array(
				'class'     => 'elementor-star-rating',
				'title'     => $textual_rating,
				'itemtype'  => 'http://schema.org/Rating',
				'itemscope' => '',
				'itemprop'  => 'reviewRating',
			)
		);

		$schema_rating = '<span itemprop="ratingValue" class="elementor-screen-only">' . $textual_rating . '</span>';
		$stars_element = '<div ' . $this->get_render_attribute_string( 'icon_wrapper' ) . '>' . $this->render_stars( $icon ) . ' ' . $schema_rating . '</div>';

		switch ( $settings['testimonial_card_style'] ) {
			case 'testimonial-card-style-1':
				include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/testimonial-card/elementor-testimonial-card-1.php';  // Card Style 1.
				break;
			case 'testimonial-card-style-2':
				include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/testimonial-card/elementor-testimonial-card-2.php';  // Card Style 2.
				break;
			case 'testimonial-card-style-3':
				include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/testimonial-card/elementor-testimonial-card-pro.php';  // Card Style pro.
				break;
			case 'testimonial-card-style-4':
				include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/testimonial-card/elementor-testimonial-card-pro.php';  // Card Style pro.
				break;
			case 'testimonial-card-style-5':
				include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/testimonial-card/elementor-testimonial-card-pro.php';  // Card Style pro.
				break;
			case 'testimonial-card-style-6':
				include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/testimonial-card/elementor-testimonial-card-6.php';  // Card Style 6.
				break;
			default:
				include CARD_ELEMENTS_ELEMENTOR_PATH . 'include/testimonial-card/elementor-testimonial-card-1.php';  // Default Card Style 1.
				break;
		}
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Testimonial_Card_Elementor_Widget() );
