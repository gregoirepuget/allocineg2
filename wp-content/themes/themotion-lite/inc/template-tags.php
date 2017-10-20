<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package themotion
 */

if ( ! function_exists( 'themotion_posted_on' ) ) {
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function themotion_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) != get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: Posted date */
			esc_html_x( 'Posted on %s', 'post date', 'themotion-lite' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		$byline = sprintf(
			/* translators: Author link */
			esc_html_x( 'by %s', 'post author', 'themotion-lite' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

	}
}

if ( ! function_exists( 'themotion_entry_footer' ) ) {
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function themotion_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' == get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'themotion-lite' ) );
			if ( $categories_list && themotion_categorized_blog() ) {
				/* translators: Categories list */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'themotion-lite' ) . '</span>', $categories_list ); // WPCS: XSS OK.
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html__( ', ', 'themotion-lite' ) );
			if ( $tags_list ) {
				/* translators: Tags list */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'themotion-lite' ) . '</span>', $tags_list ); // WPCS: XSS OK.
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses( /* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'themotion-lite' ), array(
							'span' => array(
								'class' => array(),
							),
						)
					), get_the_title()
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				/* translators: %s: Name of current post */
				esc_html__( 'Edit %s', 'themotion-lite' ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
}// End if().

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function themotion_categorized_blog() {
	$all_the_cool_cats = get_transient( 'themotion_categories' );
	if ( false === $all_the_cool_cats ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories(
			array(
				'fields'     => 'ids',
				'hide_empty' => 1,
				// We only need to know if there is more than one category.
				'number'     => 2,
			)
		);

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'themotion_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so themotion_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so themotion_categorized_blog should return false.
		return false;
	}
}

/**
 * Display social icons
 *
 * @since TheMotion 1.0
 */
function themotion_social_icons() {
	$themotion_social_icons = get_theme_mod(
		'themotion_social_icons', json_encode(
			array(
				array(
					'link' => 'facebook.com',
				),
				array(
					'link' => 'twitter.com',
				),
			)
		)
	);
	if ( ! empty( $themotion_social_icons ) ) {
		$themotion_social_icons_decoded = json_decode( $themotion_social_icons, true );
		if ( ! empty( $themotion_social_icons_decoded ) ) {
			foreach ( $themotion_social_icons_decoded as $social_icon ) {
				$link = ! empty( $social_icon['link'] ) ? apply_filters( 'themotion_translate_single_string',$social_icon['link'], 'Social links', 'Link' ) : '';
				if ( ! empty( $link ) ) { ?>
					<li>
						<a target="_blank" href="<?php echo esc_url( $link ); ?>">
						</a>
					</li>
					<?php
				}
			}
		}
	}
}

/**
 * Flush out the transients used in themotion_categorized_blog.
 */
function themotion_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'themotion_categories' );
}
add_action( 'edit_category', 'themotion_category_transient_flusher' );
add_action( 'save_post',     'themotion_category_transient_flusher' );



if ( ! function_exists( 'themotion_the_attached_image' ) ) {
	/**
	 * Print the attached image with a link to the next attached image.
	 *
	 * @since themotion 1.0
	 */
	function themotion_the_attached_image() {
		/**
		 * Filter the image attachment size to use.
		 *
		 * @since themotion 1.0
		 *
		 * @param array $size {
		 *
		 * @type int The attachment height in pixels.
		 * @type int The attachment width in pixels.
		 * }
		 */
		$attachment_size     = apply_filters( 'themotion_attachment_size', array( 724, 724 ) );
		$next_attachment_url = wp_get_attachment_url();
		$post                = get_post();

		/*
         * Grab the IDs of all the image attachments in a gallery so we can get the URL
         * of the next adjacent image in a gallery, or the first image (if we're
         * looking at the last image in a gallery), or, in a gallery of one, just the
         * link to that image file.
		 */
		$attachment_ids = get_posts(
			array(
				'post_parent'    => $post->post_parent,
				'fields'         => 'ids',
				'numberposts'    => - 1,
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'order'          => 'ASC',
				'orderby'        => 'menu_order ID',
			)
		);

		// If there is more than 1 attachment in a gallery...
		if ( count( $attachment_ids ) > 1 ) {
			foreach ( $attachment_ids as $attachment_id ) {
				if ( $attachment_id == $post->ID ) {
					$next_id = current( $attachment_ids );
					break;
				}
			}

			// get the URL of the next image attachment...
			if ( isset( $next_id ) ) {
				$next_attachment_url = get_attachment_link( $next_id );
			} // End if().
			else {
				$next_attachment_url = get_attachment_link( reset( $attachment_ids ) );
			}
		}

		printf(
			'<a href="%1$s" title="%2$s" rel="attachment">%3$s</a>',
			esc_url( $next_attachment_url ),
			the_title_attribute(
				array(
					'echo' => false,
				)
			),
			wp_get_attachment_image( $post->ID, $attachment_size )
		);
	}
}// End if().

/**
 * Get social icons
 *
 * @since 1.1.31
 */
function themotion_contact_page_social_icons() {

	if ( ! isset( $themotion_contact_hide_socials ) || 1 != $themotion_contact_hide_socials ) {
		?>
			<ul class="social-media-icons" >
		<?php
	} else {
		if ( is_customize_preview() ) {
			?>
			<ul class="social-media-icons themotion-only-customizer">
			<?php
		}
	}
	if ( ! isset( $themotion_contact_hide_socials ) || 1 != $themotion_contact_hide_socials || is_customize_preview() ) {
		themotion_social_icons();
		?>
		</ul>
		<?php
	}
}
