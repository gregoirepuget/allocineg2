<?php
/**
 * The template for displaying comments.
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package themotion
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) :
	?>
		<h2 class="comments-title">
			<?php
				printf( // WPCS: XSS OK.
					/* translators: 1: post title */
					esc_html( _nx( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'themotion-lite' ) ),
					number_format_i18n( get_comments_number() ),
					'<span>' . get_the_title() . '</span>'
				);
			?>
		</h2>

		<?php

		$prev_link = get_previous_comments_link();
		$next_link = get_next_comments_link();

		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through?
		?>
		<nav id="comment-nav-above" class="navigation comment-navigation">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'themotion-lite' ); ?></h2>
			<div class="nav-links">
				<?php
				if ( $prev_link ) {
				?>
					<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'themotion-lite' ) ); ?></div>
				<?php
				}

				if ( $next_link ) {
				?>
						<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'themotion-lite' ) ); ?></div>
				<?php } ?>
			</div><!-- .nav-links -->
		</nav><!-- #comment-nav-above -->
		<?php
		endif; // Check for comment navigation.
		?>

		<ol class="comment-list">
			<?php
				wp_list_comments(
					array(
						'style'         => 'ol',
						'short_ping'    => true,
						'avatar_size'   => 74,
					)
				);
			?>
		</ol><!-- .comment-list -->

		<?php
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through?
		?>
		<nav id="comment-nav-below" class="navigation comment-navigation">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'themotion-lite' ); ?></h2>
			<div class="nav-links">
				<?php if ( $prev_link ) { ?>
					<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'themotion-lite' ) ); ?></div>
				<?php
}

if ( $next_link ) {
?>
					<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'themotion-lite' ) ); ?></div>
				<?php } ?>

			</div><!-- .nav-links -->
		</nav><!-- #comment-nav-below -->
		<?php
		endif; // Check for comment navigation.

	endif; // Check for have_comments().


	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>

		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'themotion-lite' ); ?></p>
	<?php
	endif;

	comment_form();
	?>

</div><!-- #comments -->
