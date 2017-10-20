<?php
/**
 * The template for displaying image attachments
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage themotion
 * @since themotion 1.0
 */

get_header(); ?>

	</div><!-- .container -->

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'image-attachment' ); ?>>
				<header class="entry-header">
					<div class="attachment-container entry-header-content">
						<h1 class="entry-title"><?php the_title(); ?></h1>

						<div class="entry-meta">
							<span class="attachment-meta">
							<?php
							global $post;
							$post_title = get_the_title( $post->post_parent );

							printf( /* translators: 1: Date, 2: Category */
								esc_html__( 'Published on %1$s in %2$s', 'themotion-lite' ),
								/* translators: 1: datetime attribute, 2: Publish date */
								sprintf(
									'<time class="entry-date" datetime="%1$s">%2$s</time>',
									esc_attr( get_the_date( 'c' ) ),
									esc_html( get_the_date() )
								),
								/* translators: 1: Parent post link, 2: title attribute, 3: Post title */
								sprintf(
									'<a href="%1$s" title="%2$s" rel="gallery">%3$s</a>',
									esc_url( get_permalink( $post->post_parent ) ),
									esc_attr__( 'Return to ','themotion-lite' ) . esc_attr( $post_title ),
									esc_html( $post_title )
								)
							);
							?>
							</span>
							<span class="attachment-meta full-size-link">
								<?php
								$attachement_url = wp_get_attachment_url();
								$metadata = wp_get_attachment_metadata();

								/* translators: 1: Attachement url, 2: Title attribute, 3: Resolution, 4: Width, 5: Height */
								printf(
									'<a href="%1$s" title="%2$s">%3$s (%4$s &times; %5$s)</a>',
									esc_url( $attachement_url ),
									esc_attr__( 'Link to full-size image', 'themotion-lite' ) ,
									esc_html__( 'Full resolution', 'themotion-lite' ),
									esc_html( $metadata['width'] ),
									esc_html( $metadata['height'] )
								);
								?>
							</span>
							<div class="attachment-entry-meta">
								<span class="vcard author"><strong class="fn"><?php the_author(); ?></strong></span>
								<?php
								printf( /* translators: date */
									'<time class="date updated published" datetime="%2$s">%1$s</time>',
									esc_html( get_the_time( get_option( 'date_format' ) ) ), esc_html( get_the_date( DATE_W3C ) )
								);
								?>
							</div>
							<?php
							edit_post_link( esc_html__( 'Edit', 'themotion-lite' ), '<span class="edit-link">', '</span>' );
							?>
						</div><!-- .entry-meta -->
					</div>
				</header><!-- .entry-header -->

				<div class="entry-content">
					<div class="attachment-container entry-main-content">
						<div class="entry-attachment">
							<div class="attachment">
								<?php themotion_the_attached_image(); ?>

								<?php if ( has_excerpt() ) : ?>
									<div class="entry-caption">
										<?php the_excerpt(); ?>
									</div>
								<?php endif; ?>
							</div><!-- .attachment -->
						</div><!-- .entry-attachment -->

						<nav id="image-navigation" class="navigation image-navigation" role="navigation">
							<span class="nav-previous"><?php previous_image_link( false, __( '<span class="meta-nav">&larr;</span> Previous', 'themotion-lite' ) ); ?></span>
							<span class="nav-next"><?php next_image_link( false, __( 'Next <span class="meta-nav">&rarr;</span>', 'themotion-lite' ) ); ?></span>
						</nav><!-- #image-navigation -->

						<?php if ( ! empty( $post->post_content ) ) : ?>
							<div class="entry-description">
								<?php the_content(); ?>
								<?php
								wp_link_pages(
									array(
										'before' => '<div class="page-links">' . __( 'Pages:', 'themotion-lite' ),
										'after' => '</div>',
									)
								);
								?>
							</div><!-- .entry-description -->
						<?php endif; ?>
					</div>
				</div><!-- .entry-content -->
			</article><!-- #post -->

			<div class="attachment-form-container">
			<?php comments_template(); ?>
			</div>
		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>
