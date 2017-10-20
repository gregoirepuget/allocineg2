<?php
/**
 * Template part for displaying single downloads.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package themotion
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	if ( has_post_thumbnail() ) {
		echo '<a href="' . esc_url( get_permalink() ) . '" class="post-thumbnail" rel="bookmark">';
		the_post_thumbnail();
		echo '</a>';
	}
	?>

	<header class="entry-header">
		<?php
		if ( is_single() ) {
			the_title( '<h1 class="entry-title">', '</h1>' );
		} else {
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		}
		$post_id = get_the_ID();
		themotion_post_info( 'download_categories',$post_id );
		themotion_post_info( 'download_tags',$post_id );
		// echo '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" class="entry-author">' . get_the_author() . '</a>';
		edit_post_link( __( 'Edit', 'themotion-lite' ), '<span class="edit-link">', '</span>' );
		?>
	</header><!-- .entry-header -->
	<div class="single-download-entry-meta">
		<span class="vcard author"><strong class="fn"><?php the_author(); ?></strong></span>
		<?php
		printf(
			'<time class="date updated published" datetime="%2$s">%1$s</time>',
			esc_html( get_the_time( get_option( 'date_format' ) ) ), esc_html( get_the_date( DATE_W3C ) )
		);
		?>
	</div>
	<div class="entry-content">
		<?php

		the_content(
			sprintf(
				wp_kses( /* translators: %s: Name of current post. */
					__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'themotion-lite' ), array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			)
		);

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'themotion-lite' ),
				'after'  => '</div>',
			)
		);

		?>
	</div><!-- .entry-content -->

</article><!-- #post-## -->
