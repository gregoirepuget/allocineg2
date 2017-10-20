<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package themotion
 */

get_header(); ?>

	<div class="content-wrap">

		<div id="primary" class="content-area post-single">
			<main id="main" class="site-main">

			<?php
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content', 'single' );

				the_post_navigation();

				?>

			</main><!-- #main -->
		</div><!-- #primary -->

		<?php
			get_sidebar();
		?>

	</div><!-- .content-wrap -->

	<div class="content-wrap content-comment-wrap">

	<?php
		// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) :
		comments_template();
		endif;

		endwhile; // End of the loop.
	?>

	</div><!-- .content-wrap -->

<?php
get_footer();

