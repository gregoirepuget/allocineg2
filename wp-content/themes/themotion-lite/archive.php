<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package themotion
 */

get_header(); ?>

	</div><!-- .container -->

	<header class="page-header">
		<div class="container">
			<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
			<?php the_archive_description( '<div class="taxonomy-description">', '</div>' ); ?>
		</div>
	</header><!-- .page-header -->

	<div class="container">

		<div class="content-wrap">

			<div id="primary" class="content-area">
				<main id="main" class="site-main">

				<?php
				if ( have_posts() ) :
				?>

					<?php
					/* Start the Loop */
					while ( have_posts() ) :
						the_post();

						/*
                         * Include the Post-Format-specific template for the content.
                         * If you want to override this in a child theme, then include a file
                         * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'template-parts/content', get_post_format() );

					endwhile;

					the_posts_navigation(
						array(
							'prev_text' => sprintf( '&#8592; %s', esc_html__( 'Older Posts', 'themotion-lite' ) ),
							'next_text' => sprintf( '%s &#8594;', esc_html__( 'Newer Posts', 'themotion-lite' ) ),
						)
					);

				else :

					get_template_part( 'template-parts/content', 'none' );

				endif;
				?>

				</main><!-- #main -->
			</div><!-- #primary -->

			<?php
				get_sidebar();
			?>

		</div><!-- .content-wrap -->

<?php
get_footer();

