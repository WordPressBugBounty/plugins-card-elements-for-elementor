<?php
/**
 * Listing Card 1 - Elementor widget template.
 *
 * @package Card_Elements_For_Elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<!-- Start Listing Card 1 -->
<div class="listing-card-style-1">
	<div class="listing-main-container">
		<div class="listing-img-content">
			<img src="<?php echo esc_url( $settings['select_image']['url'] ); ?>" class="listing-img" alt="<?php echo esc_html( $settings['name'] ); ?>" />
			<div class="listing-img-overlay"></div>
		</div>
		<div class="listing-content">
			<div class="elementor-listing-name-wrapper"><?php echo esc_html( $settings['name'] ); ?></div>
			<div class="elementor-listing-description-wrapper"><?php echo esc_html( $settings['listing_description'] ); ?></div>
			<div class="elementor-listing-price-wrapper"><?php echo esc_html( $settings['listing_price'] ); ?></div>
			<div class="elementor-star-rating__wrapper">
				<?php echo wp_kses_post( $stars_element ); ?>
			</div>
		</div>
		<div class="listing-button">
			<a href="<?php echo esc_url( $listing_btn_link['url'] ); ?>" 
			<?php
			// $target and $rel are built entirely from hardcoded attribute strings in the widget's render(), never from raw user input.
			echo wp_kses( $target, array() );
			echo wp_kses( $rel, array() );
			?>
			class="view-listing-btn"><?php echo esc_html( $settings['button_text'] ); ?></a>
		</div>

		<?php
		if ( 'yes' === $settings['listing_whatsapp_share'] ) {
			?>
			<div class="listing-card-share">
				<?php
				$request_uri        = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
				$request_uri        = 0 === strpos( $request_uri, '/' ) ? $request_uri : '';
				$whatsapp_share_url = add_query_arg( 'text', home_url( $request_uri ), 'https://api.whatsapp.com/send' );
				?>
				<a href="<?php echo esc_url( $whatsapp_share_url ); ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-whatsapp" aria-hidden="true"></i></a>
			</div>
			<?php
		} else {
			?>
			<div class="listing-card-share" style="display: none;">
			</div>
		<?php } ?>
	</div>
</div>
<!-- End Listing Card -->
