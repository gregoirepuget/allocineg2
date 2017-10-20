<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package themotion
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function themotion_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'themotion_body_classes' );


/**
 * Video Category Live Update Homepage B.
 */
function themotion_ajax_homeb( $themotion_video_category, $themotion_is_hidden ) {
	$args = array(
		'post_type'      => 'post',
		'posts_per_page' => '-1',
		'category_name'  => ! empty( $themotion_video_category ) && 'all' != $themotion_video_category ? esc_html( $themotion_video_category ) : '',
	);

	$the_query = new WP_Query( $args );

	if ( $the_query->have_posts() ) { ?>

		<section id="videos" class="home-section home-three-videos
				<?php
				if ( isset( $themotion_is_hidden ) && 'true' == $themotion_is_hidden ) {
					echo 'themotion-only-customizer';
				}
?>
">
			<div class="container">

				<?php
				$post_count = 0;
				while ( $the_query->have_posts() ) {
					$the_query->the_post();

					if ( 3 == $post_count ) {
						break;
					}
					$id = get_the_ID();
					if ( has_post_format( 'video', $id ) ) {
						$post    = get_post( $id );
						$content = apply_filters( 'the_content', $post->post_content );
						$embeds  = get_media_embedded_in_content( $content );

						if ( ! empty( $embeds ) ) {
						?>
							<div class="themotion-pageb-videos">
								<?php
								echo $embeds[0];
								$post_count ++;
								?>
							</div>
							<?php
						}
					}
				}
				?>

			</div>
		</section>
		<?php
		wp_reset_postdata();
	}// End if().

}

/**
 * Video Category Live Update Homepage A.
 */
function themotion_ajax_homea( $themotion_video_category ) {
	$args                     = array(
		'post_type'      => 'post',
		'posts_per_page' => '-1',
		'category_name'  => ! empty( $themotion_video_category ) && 'all' != $themotion_video_category ? esc_html( $themotion_video_category ) : '',
	);

	$the_query = new WP_Query( $args );
	if ( $the_query->have_posts() ) {
	?>
		<div class="featured-video-wrap">
			<!-- Slider -->
			<div class="themotion-playlist" id="slider">
				<!-- Top part of the slider -->
				<div class="themotion-current-item" id="carousel-bounding-box">
					<div class="carousel slide" id="myCarousel">
						<!-- Carousel items -->
						<div class="carousel-inner">
							<?php
							$active_was_set = 'false';
							if ( $the_query->have_posts() ) {
								while ( $the_query->have_posts() ) {
									$the_query->the_post();
									$id = get_the_ID();
									?>
									<div class="item slide-number-<?php echo esc_attr( $id ); ?> <?php
									if ( 'false' == $active_was_set ) {
										echo 'active';
										$active_was_set = 'true'; }
										?>
										" >
										<?php
										if ( has_post_format( 'video',$id ) ) {
											$post = get_post( $id );
											$content = apply_filters( 'the_content', $post->post_content );
											$embeds = get_media_embedded_in_content( $content );
											if ( ! empty( $embeds ) ) {
												echo $embeds[0];
											}
										} else {
											if ( has_post_thumbnail() ) {
												$thumb_id   = get_post_thumbnail_id();
												$thumb_meta = wp_get_attachment_metadata( $thumb_id );
												if ( ! empty( $thumb_id ) && 0 != $thumb_meta['width'] && 0 != $thumb_meta['height'] ) {
													if ( $thumb_meta['width'] / $thumb_meta['height'] > 1 ) {
														the_post_thumbnail( 'themotion-thumbnail-blog' );
													} else {
														the_post_thumbnail( 'themotion-thumbnail-blog-no-crop' );
													}
												}
											}
										}
										?>
									</div>
									<?php
								}
							}
							wp_reset_postdata();
							?>
						</div><!-- Carousel nav -->
					</div>
				</div>

				<div class="themotion-playlist-tracks" id="slider-thumbs">
					<!-- Bottom switcher of slider -->
					<?php
					if ( $the_query->have_posts() ) {
						$first = 'true';
						while ( $the_query->have_posts() ) {
							$the_query->the_post();
							$id = get_the_ID();
							$attached_video = get_attached_media( 'video', $id );

							if ( ! empty( $attached_video ) ) {
								foreach ( $attached_video as $video ) {
									$video_id = $video->ID;
									$video_meta = wp_get_attachment_metadata( $video_id );
									$video_length = $video_meta['length_formatted'];
									break;
								}
							}
							?>

							<div class="themotion-playlist-item 
							<?php
							if ( 'true' == $first ) {
								echo 'themotion-playlist-playing';
								$first = 'false';}
								?>
								" id="carousel-selector-<?php echo esc_attr( $id ); ?>" data-id="<?php echo esc_attr( $id ); ?>">
								<?php
								if ( has_post_thumbnail() ) {
									the_post_thumbnail( 'themotion-playlist-thumbnail' );
								} else {
									$video_thumbnail_url = themotion_get_thumbnail_url( $id );
									?>
									<img src="<?php echo esc_url( $video_thumbnail_url ); ?>" alt="<?php esc_html_e( 'Placeholder image','themotion-lite' ); ?>">
									<?php
								}
								?>
								<div class="themotion-playlist-caption">
									<span class="themotion-playlist-item-title"><?php the_title(); ?></span>
									<?php
									if ( ! empty( $video_length ) ) {
									?>
										<span class="themotion-video-time"><?php echo esc_html( $video_length ); ?></span>
										<?php
										$video_length = '';
									}
									?>
								</div>
							</div>
							<?php
						}// End while().
					}// End if().
					wp_reset_postdata();
					?>
				</div>
			</div>
		</div>
		<?php
	}// End if().
}


/**
 * Live Update About Us Page.
 */
function themotion_ajax_about( $themotion_video_category ) {
	$themotion_cat = ( ! empty( $themotion_video_category ) && 'all' != $themotion_video_category ? $themotion_video_category : '');
	$args = array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => 3,
		'ignore_sticky_posts' => 1,
	);
	if ( ! empty( $themotion_cat ) ) {
		$args['category_name'] = $themotion_cat;
	}
	$the_query = new WP_Query( $args );

	if ( $the_query->have_posts() ) {
	?>
		<style>
			.wp-video-shortcode video, video.wp-video-shortcode {
				height: 215px;
			}

			@media screen and (min-width: 768px) {
				.recently-posted-wrap .recently-posted-about,
				.recently-posted-wrap .recently-posted-about:nth-child(3n+1),
				.recently-posted-wrap .recently-posted-about:nth-child(3n+3) {
					padding: 0 8.3px;
				}
			}

		</style>
		<?php
		/* Start the Loop */
		while ( $the_query->have_posts() ) {
			$the_query->the_post();

			/*
			* Include the Post-Format-specific template for the content.
			* If you want to override this in a child theme, then include a file
			* called content-___.php (where ___ is the Post Format name) and that will be used instead.
			*/
			get_template_part( 'template-parts/content', 'about' );

		}

		the_posts_navigation(
			array(
				'prev_text' => sprintf( '&#8592; %s', __( 'Older Posts', 'themotion-lite' ) ),
				'next_text' => sprintf( '%s &#8594;', __( 'Newer Posts', 'themotion-lite' ) ),
			)
		);

		/* Restore original Post Data */
		wp_reset_postdata();
	} else {

		get_template_part( 'template-parts/content', 'none' );

	}// End if().

}

/**
 * Live Update Bottom of Home A.
 *
 * @param string $themotion_video_category Post category.
 * @param int    $themotion_post_nb Posts per page.
 */
function themotion_ajax_homea_bottom( $themotion_video_category, $themotion_post_nb = 6 ) {
	$themotion_cat = ( ! empty( $themotion_video_category ) && 'all' != $themotion_video_category ? $themotion_video_category : '');
	$args = array(
		'post_type'         => 'post',
		'post_status'       => 'publish',
		'posts_per_page'    => $themotion_post_nb,
		'ignore_sticky_posts' => 1,
	);
	if ( ! empty( $themotion_cat ) ) {
		$args['category_name'] = $themotion_cat;
	}
	$the_query = new WP_Query( $args );

	if ( $the_query->have_posts() ) {
	?>

		<?php
		/* Start the Loop */
		while ( $the_query->have_posts() ) {
			$the_query->the_post();

			/*
			* Include the Post-Format-specific template for the content.
			* If you want to override this in a child theme, then include a file
			* called content-___.php (where ___ is the Post Format name) and that will be used instead.
			*/
			get_template_part( 'template-parts/content', 'home-one' );

		}

		the_posts_navigation(
			array(
				'prev_text' => sprintf( '&#8592; %s', __( 'Older Posts', 'themotion-lite' ) ),
				'next_text' => sprintf( '%s &#8594;', __( 'Newer Posts', 'themotion-lite' ) ),
			)
		);

		/* Restore original Post Data */
		wp_reset_postdata();

	} else {

		get_template_part( 'template-parts/content', 'none' );

	}
}

add_action( 'wp_ajax_nopriv_request_post', 'themotion_requestpost' );
add_action( 'wp_ajax_request_post', 'themotion_requestpost' );


/**
 * Post Request.
 */
function themotion_requestpost() {
	$themotion_page = '';
	if ( isset( $_POST['page'] ) ) {
		$themotion_page = sanitize_text_field( wp_unslash( $_POST['page'] ) );
	}

	$themotion_video_category = '';
	if ( isset( $_POST['category'] ) ) {
		$themotion_video_category = sanitize_text_field( wp_unslash( $_POST['category'] ) );
	}

	$themotion_post_nb = 6;

	if ( isset( $_POST['postnb'] ) ) {
		$themotion_post_nb = number_format( wp_unslash( $_POST['postnb'] ) );
	}

	switch ( $themotion_page ) {
		case 'homeb':
			$themotion_is_hidden = 'false';
			if ( isset( $_POST['is_hidden'] ) ) {
				$themotion_is_hidden = sanitize_text_field( wp_unslash( $_POST['is_hidden'] ) );
			}
			themotion_ajax_homeb( $themotion_video_category, $themotion_is_hidden );
			break;
		case 'homea':
			themotion_ajax_homea( $themotion_video_category );
			break;
		case 'about':
			themotion_ajax_about( $themotion_video_category );
			break;
		case 'homea_bottom':
			themotion_ajax_homea_bottom( $themotion_video_category, $themotion_post_nb );
			break;
		case 'homea_post_nb':
			themotion_ajax_homea_bottom( $themotion_video_category, $themotion_post_nb );
			break;
		case 'homeb_bottom':
			$themotion_post_nb = 3;
			if ( isset( $_POST['postnb'] ) ) {
				$themotion_post_nb = number_format( wp_unslash( $_POST['postnb'] ) );
			}
			themotion_display_posts_on_b( $themotion_video_category, $themotion_post_nb, true );
			break;
		case 'homeb_post_nb':
			$themotion_post_nb = 3;
			if ( isset( $_POST['postnb'] ) ) {
				$themotion_post_nb = number_format( wp_unslash( $_POST['postnb'] ) );
			}
			themotion_display_posts_on_b( $themotion_video_category, $themotion_post_nb, true );
			break;
	}// End switch().
	die();
}


/**
 * Function which adds themotion-only-customizer class for customizer transport
 *
 * @param string $string String of classes to concat with the new class.
 * @param string $input Input to verify if the class should be added or not.
 *
 * @return string
 */
function themotion_show_in_customizer( $string, $input ) {
	if ( (empty( $input ) || $input === false) && is_customize_preview() ) {
		return $string . ' themotion-only-customizer';
	}
	return $string;
}
add_filter( 'themotion_show_in_customizer_filter', 'themotion_show_in_customizer', 10, 2 );


/**
 * Return thumbnail url from vimeo or youtube link.
 *
 * @param int $post_id Post id.
 *
 * @return bool|string
 */
function themotion_get_thumbnail_url( $post_id ) {

	$video_thumbnail_url = false;

	$content_post = get_post( $post_id );
	$content = $content_post->post_content;
	$content = apply_filters( 'the_content', $content );
	$content = str_replace( ']]>', ']]&gt;', $content );

	// Only check the first 800 characters of our post.
	$content = substr( $content, 0, 800 );

	$do_video_thumbnail = (
		$post_id
		&& ! has_post_thumbnail( $post_id )
		&& $content
		// Get the video and thumb URLs if they exist.
		&& ( preg_match( '/\/\/(www\.)?(youtu|youtube)\.(com|be)\/(watch|embed)?\/?(\?v=)?([a-zA-Z0-9\-\_]+)/', $content, $youtube_matches ) || preg_match( '#https?://(.+\.)?vimeo\.com/.*#i', $content, $vimeo_matches ) )
	);

	if ( ! $do_video_thumbnail ) {
		return $video_thumbnail_url;
	}

	$youtube_id = ! empty( $youtube_matches ) ? $youtube_matches[6] : '';

	$vimeo_id = '';
	if ( ! empty( $vimeo_matches ) ) {
		$data = preg_replace( '/ .*$/m', '', $vimeo_matches[0] );
		$vimeo_id = preg_replace( '/[^0-9]/', '', $data );
	}

	if ( $youtube_id ) {
		// Check to see if our max-res image exists.
		$remote_headers = wp_remote_head( 'http://img.youtube.com/vi/' . $youtube_id . '/maxresdefault.jpg' );
		$is_404 = ( 404 === wp_remote_retrieve_response_code( $remote_headers ) );
		$video_thumbnail_url = ( ! $is_404 ) ? 'http://img.youtube.com/vi/' . $youtube_id . '/mqdefault.jpg' : 'http://img.youtube.com/vi/' . $youtube_id . '/sddefault.jpg';

	} elseif ( $vimeo_id ) {

		$vimeo_data = wp_remote_get( 'http://www.vimeo.com/api/v2/video/' . intval( $vimeo_id ) . '.php' );
		if ( isset( $vimeo_data['response']['code'] ) && '200' == $vimeo_data['response']['code'] ) {
			$response = unserialize( $vimeo_data['body'] );
			$video_thumbnail_url = isset( $response[0]['thumbnail_large'] ) ? $response[0]['thumbnail_large'] : false;
		}
	}

	return $video_thumbnail_url;

}

/**
 * Show thumbnail for video posts.
 *
 * @param object $post Post.
 * @param array  $attr Thumbnail settings.
 */
function themotion_show_video_post_thumbnail( $post, $attr ) {
	$class = $attr['class'];
	$placeholder = false;
	if ( array_key_exists( 'placeholder', $attr ) ) {
		$placeholder = $attr['placeholder'];
	}
	$post_id = get_the_ID();
	$video_placholder = themotion_get_thumbnail_url( $post_id );
	$has_thumbnail = has_post_thumbnail() || $video_placholder !== false || $placeholder !== false;
	if ( $has_thumbnail ) {
	?>
		<div class="<?php echo esc_attr( $class ); ?>">
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( 'post-thumbnail' );
			} elseif ( $video_placholder !== false ) {
			?>
				<img src="<?php echo esc_url( $video_placholder ); ?>">
				<?php
			} else {
			?>
				<img width="790" height="200" src="<?php echo esc_url( get_template_directory_uri() . '/images/default-thumbnail.jpg' ); ?>" class="attachment-post-thumbnail wp-post-image" alt="">
				<?php
			}
				?>
			<span class="themotion-video-play-button">
				<i class="mejs-overlay-button themotion-play-icon"></i>
			</span>
		</div>
		<div class="themotion-lightbox">
			<div class="themotion-lightbox-inner">
				<?php
				$content = apply_filters( 'the_content', $post->post_content );
				$embeds = get_media_embedded_in_content( $content );
				if ( ! empty( $embeds ) ) {
					$video_type = themotion_video_type( $embeds[0] );
					switch ( $video_type ) {
						case 'youtube':
							$video_url = themotion_get_embeded_src( $embeds[0] );
							if ( ! empty( $video_url ) ) {
								$video_id = themotion_get_youtube_video_id( $video_url );
								if ( ! empty( $video_id ) ) {
									echo '<div class="youtube-player" data-id="' . esc_attr( $video_id ) . '"></div>';
								}
							}
							break;
						case 'vimeo':
							echo $embeds[0];
							break;
						case 'unknown':
							echo $embeds[0];
							break;
					}
				}
				?>
			</div>
		</div>
		<?php
	}
}
