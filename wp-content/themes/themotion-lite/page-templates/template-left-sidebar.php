<?php
/**
 * Template Name: Left Sidebar
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package themotion
 */

get_header();
?>

	</div><!-- .container -->

	<?php
	$page_id = get_the_ID();
	$themotion_stored_meta = get_post_meta( $page_id );
	if ( isset( $themotion_stored_meta['hide-header-checkbox'] ) ) {
		if ( $themotion_stored_meta['hide-header-checkbox'][0] !== 'yes' ) {
			themotion_page_header();
		}
	} else {
		themotion_page_header();
	}
	?>


	<div class="container">

		<div class="content-wrap content-area-left-sidebar">

			<?php
				get_sidebar();
			?>

			<div id="primary" class="content-area sidebar-left">
				<main id="main" class="site-main">

					<?php
					while ( have_posts() ) :
						the_post();

						get_template_part( 'template-parts/content', 'page' );

					?>

				</main><!-- #main -->
			</div><!-- #primary -->

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
