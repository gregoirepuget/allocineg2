<?php
/**
 * Template Name: Home Page Option A
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package themotion
 */

get_header(); ?>

	</div><!-- .container -->


	<section id="featured-videos" class="home-section featured-videos">
		<div class="container">

			<?php themotion_home1_show_video_category(); ?>

		</div>
	</section>

<?php

get_template_part( 'template-parts/ribbon', 'content' );

?>

	<div class="container">

	<div class="content-wrap">

		<div id="primary" class="content-area homepage-one">
			<main id="main" class="site-main">
				<?php
				$themotion_home_a_bottom_posts_title = get_theme_mod( 'themotion_home_a_bottom_posts_title', esc_html__( 'Recently Posted', 'themotion-lite' ) );
				if ( ! empty( $themotion_home_a_bottom_posts_title ) ) {
				?>
					<h3 class="recently-posted-title"><?php echo wp_kses_post( $themotion_home_a_bottom_posts_title ); ?></h3>
					<?php
				} elseif ( is_customize_preview() ) {
				?>
					<h3 class="recently-posted-title"></h3>
					<?php
				}
				?>

				<?php themotion_home_a_show_post_category(); ?>

			</main><!-- #main -->
		</div><!-- #primary -->

		<?php
		get_sidebar();
		?>
		<?php edit_post_link( __( 'Edit', 'themotion-lite' ), '<span class="edit-link">', '</span>' ); ?>

	</div><!-- .content-wrap -->

<?php
get_footer();
