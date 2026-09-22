<?php
/**
 * Post Card - shared rendering helper functions.
 *
 * Used by the Post Card 1/2/6 Elementor widget templates to render post
 * meta data (categories, author, tags, comment count, etc).
 *
 * @package Card_Elements_For_Elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'card_elements_sanitize_html_tag' ) ) {

	/**
	 * Restrict a user-supplied title HTML tag to a known-safe whitelist.
	 *
	 * The "Title HTML Tag" widget control is only ever offered as a select of
	 * these values, but widget settings are not guaranteed to be re-validated
	 * against that list when saved, so the value must be validated again here
	 * before it is used as a raw tag name.
	 *
	 * @param string $tag         The tag name to validate.
	 * @param string $default_tag The tag name to fall back to when $tag is not allowed.
	 * @return string
	 */
	function card_elements_sanitize_html_tag( $tag, $default_tag = 'h2' ) {
		$allowed_tags = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' );
		$tag          = strtolower( trim( (string) $tag ) );
		return in_array( $tag, $allowed_tags, true ) ? $tag : $default_tag;
	}

}

if ( ! function_exists( 'card_elements_post_categories' ) ) {

	/**
	 * Return post category array.
	 *
	 * @return array
	 */
	function card_elements_post_categories() {

		$terms = get_terms(
			array(
				'taxonomy'   => 'category',
				'hide_empty' => true,
			)
		);

		$options = array();
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$options[ $term->term_id ] = $term->name;
			}
		}

		return $options;
	}

}

if ( ! function_exists( 'card_elements_post_author' ) ) {

	/**
	 * Return post author array.
	 *
	 * @return array
	 */
	function card_elements_post_author() {

		$args    = array(
			'orderby'      => 'name',
			'order'        => 'ASC',
			'role__not_in' => array( 'customer', 'subscriber' ),
		);
		$authors = get_users( $args );

		$options = array();
		if ( ! empty( $authors ) && ! is_wp_error( $authors ) ) {
			foreach ( $authors as $author ) {
				$options[ $author->ID ] = $author->user_login;
			}
		}

		return $options;
	}

}

if ( ! function_exists( 'card_elements_post_tags' ) ) {

	/**
	 * Return post tags array.
	 *
	 * @return array
	 */
	function card_elements_post_tags() {

		$terms = get_terms(
			array(
				'taxonomy'   => 'post_tag',
				'hide_empty' => true,
			)
		);

		$options = array();
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$options[ $term->term_id ] = $term->name;
			}
		}

		return $options;
	}

}

if ( ! function_exists( 'post_card_posted_by' ) ) {

	/**
	 * Output the post author byline markup for Post Card 1/6.
	 *
	 * @return void
	 */
	function post_card_posted_by() {
		printf(
				/* translators: 1: SVG icon. 2: post author, only visible to screen readers. 3: author link. */
			'<span class="byauthor">%1$s<span class="screen-reader-text">%2$s</span><span class="author vcard"><a class="url fn n" href="%3$s">%4$s</a></span></span>',
			'<i class="fas fa-user" aria-hidden="true"></i>',
			esc_html__( 'Posted by', 'card-elements-for-elementor' ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author() )
		);
	}

}

if ( ! function_exists( 'post_card_2_posted_by' ) ) {

	/**
	 * Output the post author byline markup (with avatar) for Post Card 2.
	 *
	 * @return void
	 */
	function post_card_2_posted_by() {

			// Prepare the formatted string with placeholders.
			$author_link_format = '<span class="byauthor">%1$s<span class="screen-reader-text">%2$s</span><span class="author vcard"><a class="url fn n" href="%3$s">%4$s</a></span></span>';
			// Prepare dynamic parts that will replace the placeholders.
			$avatar      = get_avatar( get_the_author_meta( 'ID' ), 40 );
			$by_text     = esc_html__( 'By', 'card-elements-for-elementor' );
			$author_url  = esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );
			$author_name = esc_html( get_the_author() );

			// Use sprintf() to replace placeholders in the string with dynamic content.
			$formatted_author_link = sprintf( $author_link_format, $avatar, $by_text, $author_url, $author_name );

			// Output the result.
			echo wp_kses_post( $formatted_author_link );
	}

}

if ( ! function_exists( 'post_card_posted_on' ) ) { // Not used.

	/**
	 * Output the post published/updated date markup. Currently unused by any card template.
	 *
	 * @return void
	 */
	function post_card_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		printf(
			'<span class="posted-on">%1$s<a href="%2$s" rel="bookmark">%3$s</a></span>',
			'<i class="fa fa-clock-o" aria-hidden="true"></i>',
			esc_url( get_permalink() ),
			wp_kses_post( $time_string )
		);
	}

}

if ( ! function_exists( 'post_card_posted_categories' ) ) {

	/**
	 * Output the post categories markup.
	 *
	 * @return void
	 */
	function post_card_posted_categories() {
		$categories_list = get_the_category_list( __( ', ', 'card-elements-for-elementor' ) );
		if ( $categories_list ) {
			printf(
					/* translators: 1: SVG icon. 2: posted in label, only visible to screen readers. 3: list of categories. */
				'<span class="cat-links">%1$s<span class="screen-reader-text">%2$s</span>%3$s</span>',
				'',
				esc_html__( 'Posted in', 'card-elements-for-elementor' ),
				wp_kses_post( $categories_list )
			); // WPCS: XSS OK.
		}
	}

}

if ( ! function_exists( 'post_card_posted_tag' ) ) {

	/**
	 * Output the post tags markup.
	 *
	 * @return void
	 */
	function post_card_posted_tag() {
		/* translators: used between list items, there is a space after the comma. */
		$tags_list = get_the_tag_list( '', __( ', ', 'card-elements-for-elementor' ) );
		if ( $tags_list ) {
			printf(
					/* translators: 1: SVG icon. 2: posted in label, only visible to screen readers. 3: list of tags. */
				'<div class="post-card-tags"><span class="tags-links">%1$s<span class="screen-reader-text">%2$s </span>%3$s</span> </div>',
				'<i class="fa fa-tags" aria-hidden="true"></i>',
				esc_html__( 'Tags:', 'card-elements-for-elementor' ),
				wp_kses_post( $tags_list )
			); // WPCS: XSS OK.
		}
	}

}

if ( ! function_exists( 'post_card_comment_count' ) ) {

	/**
	 * Output the post comment count/link markup.
	 *
	 * @return void
	 */
	function post_card_comment_count() {
		if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			echo '<i class="fa fa-comments" aria-hidden="true"></i>';

			/* translators: %s: Name of current post. Only visible to screen readers. */
			comments_popup_link( sprintf( __( 'Leave a comment<span class="screen-reader-text"> on %s</span>', 'card-elements-for-elementor' ), esc_html( get_the_title() ) ) );

			echo '</span>';
		}
	}

}

if ( ! function_exists( 'post_card_image_size' ) ) {

	/**
	 * Return the list of registered WordPress core image sizes. Currently unused by any card template.
	 *
	 * @return array
	 */
	function post_card_image_size() {
		global $_wp_additional_image_sizes;

		$wp_image_sizes = array();

		foreach ( get_intermediate_image_sizes() as $_size ) {

			if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ), true ) ) {

				$wp_image_sizes[ $_size ] = $_size . ' (' . get_option( "{$_size}_size_w" ) . 'X' . get_option( "{$_size}_size_h" ) . ')';
			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {

				$wp_image_sizes[ $_size ] = $_size . ' (' . $_wp_additional_image_sizes[ $_size ]['width'] . 'X' . $_wp_additional_image_sizes[ $_size ]['height'] . ')';
			}
		}

		return $wp_image_sizes;
	}

}
