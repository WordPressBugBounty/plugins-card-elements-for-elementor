<?php
/**
 * Registers the "Card Elements" category with Elementor.
 *
 * @package Card_Elements_For_Elementor
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Create profile card category into elementor.
 *
 * @return void
 */
function init_card_elements_category() {
	Plugin::instance()->elements_manager->add_category(
		'card-elements',
		array(
			'title' => esc_html__( 'Card Elements', 'card-elements-for-elementor' ),
			'icon'  => 'font',
		),
		1
	);
}

add_action( 'elementor/init', 'Elementor\init_card_elements_category' );
