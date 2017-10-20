<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package themotion
 */

?>

</div><!-- .container -->
</div><!-- #content -->

<footer id="colophon" class="site-footer">
	<div class="container container-footer">
		<div class="footer-inner">

			<?php
			get_template_part( 'template-parts/footer', 'content' );

			get_sidebar( 'footer' );
			?>
		</div>


		<?php
		$themotion_footer_copyright = get_theme_mod(
			'themotion_footer_copyright',sprintf(
				/* translators: 1: Theme Name, 2: WordPress */
				__( 'Proudly powered by  %1$s | Theme: themotion powered by %2$s', 'themotion-lite' ),
				sprintf( '<a href="http://wordpress.org/" rel="nofollow">%s</a>', esc_html__( 'WordPress', 'themotion-lite' ) ),
				sprintf( '<a href="https://themeisle.com/" rel="nofollow">%s</a>', esc_html__( 'Themeisle', 'themotion-lite' ) )
			)
		);
		if ( ! empty( $themotion_footer_copyright ) ) {
		?>
			<div class="site-info">
				<?php
				echo wp_kses_post( $themotion_footer_copyright );
				?>
			</div><!-- .site-info -->
			<?php
		}
		?>




	</div><!-- .container-footer -->
</footer><!-- #colophon -->
</div><!-- #themotion-page -->

<?php wp_footer(); ?>

</body>
</html>
