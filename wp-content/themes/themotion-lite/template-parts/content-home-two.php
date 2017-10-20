<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package themotion
 */ ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'recently-item' ); ?>>
	<?php
	$post = get_post();
	if ( has_post_format( 'video' ) ) {
		themotion_show_video_post_thumbnail(
			$post, array(
				'class' => 'themotion-home2-video-thumbnail',
			)
		);
	} else {
		if ( has_post_thumbnail() ) {
		?>
			<a href="<?php echo esc_url( get_permalink() ); ?>" class="recently-image-wrap">
				<?php
					the_post_thumbnail( 'themotion-thumbnail-blog' );
					?>
			</a>
		<?php
		}
	}
	?>

	<div class="recently-content-wrap">
		<header class="entry-header">
			<?php
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			?>
		</header><!-- .entry-header -->
		<div class="recently-item-entry-meta">
			<span class="vcard author"><strong class="fn"><?php the_author(); ?></strong></span>
			<?php
			printf(
				'<time class="date updated published" datetime="%2$s">%1$s</time>',
				esc_html( get_the_time( get_option( 'date_format' ) ) ), esc_html( get_the_date( DATE_W3C ) )
			);
			?>
		</div>
		<div class="entry-content">
			<?php
			$pos = strpos( $post->post_content, '<!--more-->' );
			if ( $pos <= 0 ) {
				the_excerpt();
			} else {
				the_content(
					sprintf(
						wp_kses( /* translators: %s: Name of current post. */
							__( 'Read more %s <span class="meta-nav">&rarr;</span>', 'themotion-lite' ), array(
								'span' => array(
									'class' => array(),
								),
							)
						),
						the_title( '<span class="screen-reader-text">"', '"</span>', false )
					)
				);
			}

			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'themotion-lite' ),
					'after'  => '</div>',
				)
			);
			?>
		</div><!-- .entry-content -->
	</div>
</article><!-- #post-## -->
