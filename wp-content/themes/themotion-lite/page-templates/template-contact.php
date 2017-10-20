<?php
/**
 * Template Name: Contact
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package themotion
 */

get_header(); ?>

	</div><!-- .container -->

	<?php

		themotion_contact_top();
		themotion_contact_block();

	?>

	<div class="content-wrap-contact">

		<div class="container">

			<div class="content-wrap">

				<div id="primary" class="content-area contact-page">
					<main id="main" class="site-main">

						<?php
						while ( have_posts() ) :
							the_post();

							get_template_part( 'template-parts/content', 'contact' );

							// If comments are open or we have at least one comment, load up the comment template.
							if ( comments_open() || get_comments_number() ) :
								comments_template();
							endif;

						endwhile; // End of the loop.
						?>

					</main><!-- #main -->
				</div><!-- #primary -->

			</div><!-- .content-wrap -->

<?php
get_footer();
