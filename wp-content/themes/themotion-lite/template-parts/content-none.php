<?php
/**
 * Template part for displaying a message that posts cannot be found.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package themotion
 */ ?>

<section class="no-results not-found">
	<header class="page-header-search-nothing">
		<h1 class="page-title search-nothing-found"><?php esc_html_e( 'Sorry, nothing found!', 'themotion-lite' ); ?></h1>
	</header><!-- .page-header -->

	<div class="page-content page-content-search">
		<?php
		if ( is_home() && current_user_can( 'publish_posts' ) ) :
		?>

			<p>
				<?php
				printf(
					wp_kses( /* translators: Link to new post */
						__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'themotion-lite' ), array(
							'a' => array(
								'href' => array(),
							),
						)
					), esc_url( admin_url( 'post-new.php' ) )
				);
				?>
				</p>

		<?php elseif ( is_search() ) : ?>

			<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'themotion-lite' ); ?></p>
			<?php
			get_search_form();
		else :
		?>

			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'themotion-lite' ); ?></p>
			<?php
			get_search_form();

		endif;
		?>
	</div><!-- .page-content -->
</section><!-- .no-results -->
