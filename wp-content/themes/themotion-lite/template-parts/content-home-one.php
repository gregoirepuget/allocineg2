<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package themotion
 */?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'col-sm-12 col-md-6  recently-posted-item' ); ?>>
	<header class="entry-header">

		<?php
		$post    = get_post();
		if ( has_post_format( 'video' ) ) {
			themotion_show_video_post_thumbnail(
				$post, array(
					'class' => 'post-image-container',
					'placeholder' => true,
				)
			);
		} else {
		?>
			<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark">
				<?php
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'themotion-post-thumbnail' );
				} else {
					$default_image = get_template_directory_uri() . '/images/default-thumbnail.jpg';
					?>
					<img width="790" height="200" src="<?php echo esc_url( $default_image ); ?>" class="attachment-post-thumbnail wp-post-image" alt="">
					<?php
				}
				?>
			</a>
			<?php
		}
		?>

		<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark">
			<?php the_title( '<span class="home-entry-title">', '</span>' ); ?>
		</a>

		<div class="infowrap">
			<?php
			if ( function_exists( 'the_views' ) ) {
				echo '<a class="the-views-wrap">';
				the_views();
				echo '</a>';
			}
			if ( function_exists( 'dot_irecommendthis' ) ) {
				dot_irecommendthis();
			}
			?>
		</div>
		<div class="recently-posted-item-entry-meta">
			<span class="vcard author"><strong class="fn"><?php the_author(); ?></strong></span>
			<?php
			printf(
				'<time class="date updated published" datetime="%2$s">%1$s</time>',
				esc_html( get_the_time( get_option( 'date_format' ) ) ), esc_html( get_the_date( DATE_W3C ) )
			);
			?>
		</div>

	</header><!-- .entry-header -->
</article><!-- #post-## -->
