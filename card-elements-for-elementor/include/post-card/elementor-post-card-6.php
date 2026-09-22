<?php
/**
 * Post Card 6 - Elementor widget template.
 *
 * @package Card_Elements_For_Elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<div class="post-card-style-6 card card3_style" style="background-image: url('<?php echo esc_url( wp_get_attachment_url( get_post_thumbnail_id() ) ); ?>'); background-size: contain; background-repeat: no-repeat;">

	<div class="card-info-hover">

		<div class="card-clock-info">
			<?php
			if ( 'yes' === $settings['show_meta_data'] ) {
				if ( in_array( 'date', $settings['meta_data'], true ) ) {
					?>
					<span class="card-time"><?php echo get_the_date( 'd M, Y' ); ?></span>
					<?php
				}
			}
			?>
		</div>

	</div>

	<div class="card-img" style="background-image: url('<?php echo esc_url( wp_get_attachment_url( get_post_thumbnail_id() ) ); ?>');"></div>

	<a href="<?php the_permalink(); ?>" class="card-link">
		<div class="card-img-hover"></div>
	</a>

	<div class="card-info post-content post_bg">
		<div class="card-category card_meta post-card_category">
			<a class="category "><?php post_card_posted_categories(); ?></a>
		</div>
		<?php
		if ( isset( $settings['show_title'] ) && 'yes' === $settings['show_title'] ) {
			$title_tag = card_elements_sanitize_html_tag( $settings['title_tag'] );
			?>
			<<?php echo esc_attr( $title_tag ); ?> class="card-title card_title ">
			<a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
			</<?php echo esc_attr( $title_tag ); ?>>
			<?php } ?>
		<?php
		if ( isset( $settings['show_excerpt'] ) && 'yes' === $settings['show_excerpt'] ) {
			if ( 'content' === $settings['excerpt_from'] ) {
				$content = get_the_content();
			} elseif ( 'excerpt' === $settings['excerpt_from'] ) {
				$content = get_the_excerpt();
			} else {
				$content = get_the_content();
			}
			if ( 'yes' === $settings['show_read_more'] ) {
				$read_more = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="entry-read-more post-card_read-more"> &nbsp;' . esc_html( $settings['read_more_text'] ) . '</a>';
			} else {
				$read_more = '';
			}
			?>
							<p class="description post-card_excerpt post-card-alignment">
				<?php
					echo wp_kses_post( wp_trim_words( $content, $settings['excerpt_length'], $read_more ) );
				?>
				</p>
			<?php } ?> 
		<div class="card-by">
			<div class="card-author card_aling">
				<?php
				if ( 'yes' === $settings['show_meta_data'] ) {
					if ( in_array( 'author', $settings['meta_data'], true ) ) {
						post_card_posted_by();
					}
					if ( in_array( 'comments', $settings['meta_data'], true ) ) {
						post_card_comment_count();
					}
					if ( in_array( 'tags', $settings['meta_data'], true ) ) {
						post_card_posted_tag();
					}
				}
				?>
			</div>
		</div>
	</div>
</div>
