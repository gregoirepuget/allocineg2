<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package themotion
 */

if ( ! function_exists( 'themotion_contact_top' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function themotion_contact_top() {

		$themotion_contact_header_image = get_theme_mod( 'themotion_contact_header_image', esc_url( get_template_directory_uri() . '/images/contact.jpg' ) );
		$themotion_contact_header_text = get_theme_mod( 'themotion_contact_header_text', esc_html__( 'FEEL FREE TO CONTACT US WITH ANY QUESTIONS OR COMMENTS','themotion-lite' ) );
		$themotion_contact_button_text = get_theme_mod( 'themotion_contact_button_text', esc_html__( 'Send us an email','themotion-lite' ) );
		$themotion_contact_button_link = get_theme_mod( 'themotion_contact_button_link', '#' );

		if ( ! empty( $themotion_contact_header_image ) || ! empty( $themotion_contact_header_text ) || ! empty( $themotion_contact_button_text ) ) { ?>
				<section id="top" class="contact-section about-top-area" <?php echo ( ! empty( $themotion_contact_header_image ) ? 'style="background-image: url(' . esc_url( $themotion_contact_header_image ) . ');"' : '' ); ?>>
				<?php
		} else {
			if ( is_customize_preview() ) {
			?>
					<section id="top" class="contact-section about-top-area themotion-only-customizer">
					<?php
			}
		}

		if ( ! empty( $themotion_contact_header_image ) || ! empty( $themotion_contact_header_text ) || ! empty( $themotion_contact_button_text ) || is_customize_preview() ) {
		?>
				<div class="container">
					<div class="about-top-area-inner">
						<?php
						if ( ! empty( $themotion_contact_header_text ) ) {
						?>
							<h1><?php echo esc_html( $themotion_contact_header_text ); ?></h1>
							<?php
						} else {
							if ( is_customize_preview() ) {
							?>
								<h1></h1>
								<?php
							}
						}

						$button_settings = array(
							'button_class' => 'btn themotion-scroll-to-section',
							'container_class' => 'about-top-area-inner',
						);
						themotion_display_customizer_button( $themotion_contact_button_text, $themotion_contact_button_link ,$button_settings );
						?>
					</div>
				</div>
			</section>
		<?php
		}
	}
endif;



if ( ! function_exists( 'themotion_contact_block' ) ) {
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function themotion_contact_block() {

		$themotion_contact_cl_title = get_theme_mod( 'themotion_contact_cl_title', esc_html__( 'Why the.motion','themotion-lite' ) );
		$themotion_contact_cl_text = get_theme_mod( 'themotion_contact_cl_text', esc_html__( 'Using best practices and a keen eye, we curated this video feed for the business beginner and experienced alike. We are a resource for creatives wanting to push their business forward.','themotion-lite' ) );
		$themotion_contact_cr_title = get_theme_mod( 'themotion_contact_cr_title', esc_html__( 'Get in touch','themotion-lite' ) );
		$themotion_contact_cr_b1_title = get_theme_mod( 'themotion_contact_cr_b1_title', esc_html__( 'The.Motion Headquarters','themotion-lite' ) );
		$themotion_contact_cr_b1_text = get_theme_mod( 'themotion_contact_cr_b1_text', esc_html__( '329 South Street Court - Albany, NY 83741','themotion-lite' ) );
		$themotion_contact_cr_b1_email = get_theme_mod( 'themotion_contact_cr_b1_email', esc_html__( 'start@example.com','themotion-lite' ) );
		$themotion_contact_cr_b1_phone = get_theme_mod( 'themotion_contact_cr_b1_phone', esc_html__( '(432) 203-3321','themotion-lite' ) );
		$themotion_contact_cr_b2_title = get_theme_mod( 'themotion_contact_cr_b2_title', esc_html__( 'THE.MOTION VIDEO RECORDING','themotion-lite' ) );
		$themotion_contact_cr_b2_text = get_theme_mod( 'themotion_contact_cr_b2_text', esc_html__( '153 East Fifth Avenue - New York, NY 83741','themotion-lite' ) );
		$themotion_contact_cr_b2_email = get_theme_mod( 'themotion_contact_cr_b2_email', esc_html__( 'recording@example.com','themotion-lite' ) );
		$themotion_contact_cr_b2_phone = get_theme_mod( 'themotion_contact_cr_b2_phone', esc_html__( '(324) 923-8321','themotion-lite' ) );
		$themotion_contact_hide_socials = get_theme_mod( 'themotion_contact_hide_socials' );
	?>

		<section id="contact" class="contact-section contact-block">
			<div class="container">


				<div class="contact-block-item-wrap">
					<?php
					if ( ! empty( $themotion_contact_cr_title ) || ! empty( $themotion_contact_cr_b1_title ) || ! empty( $themotion_contact_cr_b1_text )
								|| ! empty( $themotion_contact_cr_b1_email ) || ! empty( $themotion_contact_cr_b1_phone ) || ! empty( $themotion_contact_cr_b2_title )
								|| ! empty( $themotion_contact_cr_b2_text ) || ! empty( $themotion_contact_cr_b2_email ) || ! empty( $themotion_contact_cr_b2_phone ) ) {
									$class_to_add = 'col-md-6';
					} else {
						$class_to_add = 'col-md-12';
					}
					if ( ! isset( $themotion_contact_hide_socials ) || 1 != $themotion_contact_hide_socials || ! empty( $themotion_contact_cl_title ) || ! empty( $themotion_contact_cl_text ) ) {
					?>
						<div class="<?php echo esc_attr( $class_to_add ); ?> contact-block-item contact-block-left">
							<?php
							if ( ! empty( $themotion_contact_cl_title ) ) {
							?>
								<h3 class="contact-block-title"><?php echo wp_kses_post( $themotion_contact_cl_title ); ?></h3>
								<?php
							} else {
								if ( is_customize_preview() ) {
								?>
									<h3 class="contact-block-title themotion-only-customizer"></h3>
									<?php
								}
							}
							?>

							<div class="contact-block-content">
								<?php
								if ( ! empty( $themotion_contact_cl_text ) ) {
								?>
									<p><?php echo wp_kses_post( $themotion_contact_cl_text ); ?></p>
									<?php
								} else {
									if ( is_customize_preview() ) {
									?>
										<p class="themotion-only-customizer"></p>
										<?php
									}
								}
								if ( (bool) $themotion_contact_hide_socials !== true ) {
									themotion_contact_page_social_icons();
								}
								?>
							</div>
						</div>
					<?php
					} else {
						if ( is_customize_preview() ) {
						?>
							<div class="<?php echo esc_attr( $class_to_add ); ?> contact-block-item contact-block-left themotion-only-customizer">
								<h3 class="contact-block-title"></h3>
								<div class="contact-block-content">
									<p></p>
									<ul class="social-media-icons"></ul>
								</div>
							</div>
							<?php
						}
					}// End if().

					if ( ! isset( $themotion_contact_hide_socials ) || 1 != $themotion_contact_hide_socials || ! empty( $themotion_contact_cl_text ) || ! empty( $themotion_contact_cl_title ) ) {
						$class_to_add = 'col-md-6';
					} else {
						$class_to_add = 'col-md-12';
					}
					if ( ! empty( $themotion_contact_cr_title ) || ! empty( $themotion_contact_cr_b1_title ) || ! empty( $themotion_contact_cr_b1_text )
								|| ! empty( $themotion_contact_cr_b1_email ) || ! empty( $themotion_contact_cr_b1_phone ) || ! empty( $themotion_contact_cr_b2_title )
								|| ! empty( $themotion_contact_cr_b2_text ) || ! empty( $themotion_contact_cr_b2_email ) || ! empty( $themotion_contact_cr_b2_phone ) ) {
								?>

						<div class="<?php echo esc_attr( $class_to_add ); ?> contact-block-item contact-block-right">
							<?php
							if ( ! empty( $themotion_contact_cr_title ) ) {
							?>
								<h3 class="contact-block-title"><?php echo wp_kses_post( $themotion_contact_cr_title ); ?></h3>
								<?php
							} else {
								if ( is_customize_preview() ) {
								?>
									<h3 class="contact-block-title themotion-only-customizer"></h3>
									<?php
								}
							}
							?>


							<div class="contact-block-content-wrap">

								<div class="col-md-6 contact-block-content themotion-block-left">
									<?php
									if ( ! empty( $themotion_contact_cr_b1_title ) ) {
									?>
										<h6 class="contact-second-title"><?php echo wp_kses_post( $themotion_contact_cr_b1_title ); ?></h6>
										<?php
									} else {
										if ( is_customize_preview() ) {
										?>
											<h6 class="contact-second-title themotion-only-customizer"></h6>
											<?php
										}
									}

									if ( ! empty( $themotion_contact_cr_b1_text ) ) {
									?>
										<p class="themotion_contact_left"><?php echo wp_kses_post( $themotion_contact_cr_b1_text ); ?></p>
										<?php
									} else {
										if ( is_customize_preview() ) {
										?>
											<p class="themotion_contact_left themotion-only-customizer"></p>
											<?php
										}
									}

									if ( ! empty( $themotion_contact_cr_b1_email ) ) {
									?>
										<p class="contact-link contact-left-email"><a href="mailto:<?php echo wp_kses_post( $themotion_contact_cr_b1_email ); ?>"><?php echo wp_kses_post( $themotion_contact_cr_b1_email ); ?></a></p>
										<?php
									} else {
										if ( is_customize_preview() ) {
										?>
											<p class="contact-link contact-left-email themotion-only-customizer"><a href=""></a></p>
											<?php
										}
									}

									if ( ! empty( $themotion_contact_cr_b1_phone ) ) {
									?>
										<p class="contact-link contact-left-phone"><a href="tel:<?php echo wp_kses_post( $themotion_contact_cr_b1_phone ); ?>"><?php echo wp_kses_post( $themotion_contact_cr_b1_phone ); ?></a></p>
										<?php
									} else {
										if ( is_customize_preview() ) {
										?>
											<p class="contact-link contact-left-phone themotion-only-customizer"><a href=""></a></p>
											<?php
										}
									}
									?>
								</div>

								<div class="col-md-6 contact-block-content contact-block-content-second">
									<?php
									if ( ! empty( $themotion_contact_cr_b2_title ) ) {
									?>
										<h6 class="contact-second-title"><?php echo wp_kses_post( $themotion_contact_cr_b2_title ); ?></h6>
										<?php
									} else {
										if ( is_customize_preview() ) {
										?>
											<h6 class="contact-second-title themotion-only-customizer"></h6>
											<?php
										}
									}

									if ( ! empty( $themotion_contact_cr_b2_text ) ) {
									?>
										<p class="themotion_contact_right"><?php echo wp_kses_post( $themotion_contact_cr_b2_text ); ?></p>
										<?php
									} else {
										if ( is_customize_preview() ) {
										?>
											<p class="themotion_contact_right themotion-only-customizer"></p>
											<?php
										}
									}

									if ( ! empty( $themotion_contact_cr_b2_email ) ) {
									?>
										<p class="contact-link  contact-right-email"><a href="mailto:<?php echo wp_kses_post( $themotion_contact_cr_b2_email ); ?>"><?php echo wp_kses_post( $themotion_contact_cr_b2_email ); ?></a></p>
										<?php
									} else {
										if ( is_customize_preview() ) {
										?>
											<p class="contact-link contact-right-email themotion-only-customizer"><a href=""></a></p>
											<?php
										}
									}

									if ( ! empty( $themotion_contact_cr_b2_phone ) ) {
									?>
										<p class="contact-link contact-right-phone"><a href="tel:<?php echo wp_kses_post( $themotion_contact_cr_b2_phone ); ?>"><?php echo wp_kses_post( $themotion_contact_cr_b2_phone ); ?></a></p>
										<?php
									} else {
										if ( is_customize_preview() ) {
										?>
											<p class="contact-link contact-right-phone themotion-only-customizer"><a href=""></a></p>
											<?php
										}
									}
									?>
								</div>
							</div>
						</div>
						<?php
					} else {
						if ( is_customize_preview() ) {
						?>
							<div class="<?php echo esc_attr( $class_to_add ); ?> contact-block-item contact-block-right themotion-only-customizer">
								<h3 class="contact-block-title"></h3>
								<div class="contact-block-content-wrap">
									<div class="col-md-6 contact-block-content themotion-block-left">
										<h6 class="contact-second-title"></h6>
										<p class="themotion_contact_left"></p>
										<p class="contact-link contact-left-email"><a href=""></a></p>
										<p class="contact-link contact-left-phone"><a href=""></a></p>
									</div>

									<div class="col-md-6 contact-block-content contact-block-content-second">
										<h6 class="contact-second-title"></h6>
										<p class="themotion_contact_right"></p>
										<p class="contact-link  contact-right-email"><a href=""></a></p>
										<p class="contact-link contact-right-phone"><a href=""></a></p>
									</div>
								</div>
							</div>
							<?php
						}
					}// End if().
?>
				</div>
			</div>
		</section>

	<?php
	}
}// End if().


if ( ! function_exists( 'themotion_post_info' ) ) {
	/**
	 * Get post info.
	 */
	function themotion_post_info( $type, $post_id ) {
		if ( 'categories' == $type ) {
			$array_list = get_the_category( $post_id );
		} elseif ( 'tags' == $type ) {
			$array_list = wp_get_post_tags( $post_id );
		} elseif ( 'download_categories' == $type ) {
			$array_list = wp_get_post_terms( $post_id, 'download_category' );
		} elseif ( 'download_tags' == $type ) {
			$array_list = wp_get_post_terms( $post_id, 'download_tag' );
		}
		if ( ! empty( $array_list ) ) {
		?>
			<div class="
			<?php
			if ( ( 'categories' == $type ) || ( 'download_categories' == $type ) ) {
				echo 'categories-links';
			} elseif ( ( 'tags' == $type ) || ( 'download_tags' == $type ) ) {
				echo 'tags-links'; }
?>
">
				<?php
				$i = 0;
				$len = count( $array_list );
				if ( ! empty( $array_list ) ) {
					foreach ( $array_list as $item ) {
						if ( 'categories' == $type ) {
							$label_link = get_category_link( $item->term_id );
						} else {
							$label_link = get_tag_link( $item->term_id );
						}
						if ( $i < 2 || $i > 2 ) {
						?>
							<a href="<?php echo esc_url( $label_link ); ?>" rel="
												<?php
												if ( 'categories' == $type ) {
													echo 'category';
												} else {
													echo 'tag'; }
?>
">
								<?php
								echo esc_html( $item->name );
								?>
							</a>
							<?php
							if ( $i != $len - 1 && $i != 1 ) {
								echo esc_html__( ',', 'themotion-lite' );
							}
						}

						if ( $i == 2 ) {
						?>
							<span class="themotion-show-on-click" title="<?php esc_html_e( 'Show more categories','themotion-lite' ); ?>">
							<?php esc_html_e( '...','themotion-lite' ); ?>
						</span>
							<span class="themotion-cat-show-on-click">
							<?php
						}

						if ( $i == $len - 1 ) {
						?>
							</span>
							<?php
						}
						$i++;
					}
				}
				?>
			</div>
	<?php
		}// End if().
	}
}// End if().
