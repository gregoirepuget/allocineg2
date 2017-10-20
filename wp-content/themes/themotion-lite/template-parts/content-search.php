<?php
/**
 * Template part for displaying results in search pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package themotion
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'search-post' ); ?>>
	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<?php
		$post_id = get_the_ID();
		themotion_post_info( 'categories',$post_id );
		themotion_post_info( 'tags',$post_id );
		?>
	</header><!-- .entry-header -->
	<div class="search-entry-meta">
		<span class="vcard author"><strong class="fn"><?php the_author(); ?></strong></span>
		<?php
		printf(
			'<time class="date updated published" datetime="%2$s">%1$s</time>',
			esc_html( get_the_time( get_option( 'date_format' ) ) ), esc_html( get_the_date( DATE_W3C ) )
		);
		?>
	</div>
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->

</article><!-- #post-## -->
