<?php
/**
 * Template part for displaying a message that posts cannot be found on Front page B.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package themotion
 */

themotion_template_two_posts_header();
if ( current_user_can( 'edit_theme_options' ) ) { ?>
	<article class="recently-item">
		<div class="recently-image-wrap">
			<?php
			$image_path = get_template_directory_uri() . '/images/default-thumbnail.jpg';
			?>
			<img width="790" height="200"
				 src="<?php echo esc_url( $image_path ); ?>"
				 class="attachment-post-thumbnail wp-post-image" alt="">
		</div>
		<div class="recently-content-wrap">
			<header class="entry-header">
				<h2 class="entry-title">
					<?php
					esc_html_e( 'Example  post', 'themotion-lite' );
					?>
				</h2>
			</header>
			<div class="entry-content">
				<?php
				esc_html_e( 'This is an example post and it is displayed only for admins. To display this section on your website, please add a new post.', 'themotion-lite' );
				?>
			</div>
		</div>
	</article>
	<?php
}
