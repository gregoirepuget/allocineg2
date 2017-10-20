<?php
/**
 * Themotion Theme Customizer.
 *
 * @package themotion
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function themotion_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->remove_control( 'header_textcolor' );
	$wp_customize->remove_control( 'background_color' );

	require_once get_template_directory() . '/inc/customizer-repeater/class/class-themotion-general-repeater.php';
	require_once get_template_directory() . '/inc/customizer-category-selector/class-themotion-category-selector.php';

	$wp_customize->get_control( 'display_header_text' )->priority = 2;
	$wp_customize->get_control( 'blogname' )->priority = 3;
	$wp_customize->get_control( 'blogdescription' )->priority = 4;
	$wp_customize->get_control( 'custom_logo' )->priority = 5;

	/* Control for social icons */
	$wp_customize->add_section(
		'themotion_social_media', array(
			'title' => esc_html__( 'Social Media Icons', 'themotion-lite' ),
			'priority'  => 40,
		)
	);
	$wp_customize->add_setting(
		'themotion_social_icons', array(
			'default'   => json_encode(
				array(
					array(
						'link' => 'facebook.com',
					),
					array(
						'link' => 'twitter.com',
					),
				)
			),
			'transport' => 'postMessage',
			'sanitize_callback' => 'themotion_sanitize_repeater',
		)
	);
	$wp_customize->add_control(
		new Themotion_General_Repeater(
			$wp_customize, 'themotion_social_icons', array(
				'label' => esc_html__( 'Add new social icon','themotion-lite' ),
				'section'   => 'themotion_social_media',
				'priority'  => 1,
				'themotion_link_control' => true,
			)
		)
	);

	/* Control for hiding social icons on contact page */

	$wp_customize->add_setting(
		'themotion_contact_hide_socials', array(
			'sanitize_callback' => 'themotion_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'themotion_contact_hide_socials', array(
			'type' => 'checkbox',
			'label' => esc_html__( 'Hide social icons?','themotion-lite' ),
			'description' => esc_html__( 'If you check this box, the social icons will disappear from Contact page.','themotion-lite' ),
			'section' => 'themotion_contact_cl_settings',
			'priority' => 1,
		)
	);

	/* === Homepage A settings === */
	$wp_customize->add_section(
		'themotion_home_a', array(
			'title'    => esc_html__( 'Home Page Option A', 'themotion-lite' ),
			'priority' => 50,
		)
	);

	$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

	$wp_customize->add_setting(
		'themotion_home1_video_category', array(
			'default'           => 'all',
			'transport'         => $selective_refresh,
			'sanitize_callback' => 'themotion_sanitize_category_dropdown',
		)
	);

	$wp_customize->add_control(
		new Themotion_Category_Selector(
			$wp_customize, 'themotion_home1_video_category', array(
				'label'     => esc_html__( 'Top Section Post Category', 'themotion-lite' ),
				'section'   => 'themotion_home_a',
				'priority'  => 1,
			)
		)
	);

	$wp_customize->add_setting(
		'themotion_home_a_bottom_posts_title', array(
			'default' => esc_html__( 'Recently Posted','themotion-lite' ),
			'sanitize_callback' => 'themotion_sanitize_text',
			'transport' => $selective_refresh,
		)
	);

	$wp_customize->add_control(
		'themotion_home_a_bottom_posts_title', array(
			'label'     => esc_html__( 'Bottom posts title', 'themotion-lite' ),
			'section'   => 'themotion_home_a',
			'priority'  => 6,
		)
	);

	$wp_customize->add_setting(
		'themotion_home_a_post_category', array(
			'default'           => 'all',
			'transport'         => $selective_refresh,
			'sanitize_callback' => 'themotion_sanitize_category_dropdown',
		)
	);

	$wp_customize->add_control(
		new Themotion_Category_Selector(
			$wp_customize, 'themotion_home_a_post_category', array(
				'label'     => esc_html__( 'Bottom posts category', 'themotion-lite' ),
				'section'   => 'themotion_home_a',
				'priority'  => 7,
			)
		)
	);

	$wp_customize->add_setting(
		'themotion_home_a_post_nb', array(
			'default'           => 6,
			'transport'         => $selective_refresh,
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'themotion_home_a_post_nb', array(
			'type'              => 'number',
			'label'     => esc_html__( 'Number of posts', 'themotion-lite' ),
			'section'   => 'themotion_home_a',
			'priority'  => 8,
		)
	);

	/* === Contact page === */
	$wp_customize->add_panel(
		'themotion_contact', array(
			'priority' => 65,
			'capability' => 'edit_theme_options',
			'title' => esc_html__( 'Contact page', 'themotion-lite' ),
		)
	);

	$wp_customize->add_section(
		'themotion_contact_header_settings', array(
			'title' => esc_html__( 'Header Settings', 'themotion-lite' ),
			'priority'  => 1,
			'panel' => 'themotion_contact',
		)
	);

	/* Header Image	*/
	$wp_customize->add_setting(
		'themotion_contact_header_image', array(
			'default' => esc_url( get_template_directory_uri() . '/images/contact.jpg' ),
			'sanitize_callback' => 'esc_url',
			'transport' => 'postMessage',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize, 'themotion_contact_header_image', array(
				'label'    => esc_html__( 'Header Image', 'themotion-lite' ),
				'section'  => 'themotion_contact_header_settings',
				'priority'    => 1,
			)
		)
	);

	/* Control for header text */
	$wp_customize->add_setting(
		'themotion_contact_header_text', array(
			'default' => esc_html__( 'FEEL FREE TO CONTACT US WITH ANY QUESTIONS OR COMMENTS','themotion-lite' ),
			'sanitize_callback' => 'themotion_sanitize_text',
			'transport' => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'themotion_contact_header_text', array(
			'label'     => esc_html__( 'Header text', 'themotion-lite' ),
			'section'   => 'themotion_contact_header_settings',
			'priority'  => 2,
		)
	);

	/* Control for button text*/
	$wp_customize->add_setting(
		'themotion_contact_button_text', array(
			'default' => esc_html__( 'Send us an email','themotion-lite' ),
			'sanitize_callback' => 'themotion_sanitize_text',
			'transport' => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'themotion_contact_button_text', array(
			'label'     => esc_html__( 'Button text', 'themotion-lite' ),
			'section'   => 'themotion_contact_header_settings',
			'priority'  => 3,
		)
	);

	/*  Latest posts */
	$wp_customize->add_section(
		'themotion_latest_posts', array(
			'title' => esc_html__( 'Latest posts', 'themotion-lite' ),
			'priority'  => 5,
			'panel' => 'themotion_about',
		)
	);

	$wp_customize->add_setting(
		'themotion_show_latest', array(
			'transport' => 'postMessage',
			'sanitize_callback' => 'themotion_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'themotion_show_latest', array(
			'type' => 'checkbox',
			'label' => esc_html__( 'Hide latest posts?','themotion-lite' ),
			'description' => esc_html__( 'If you check this box, latest posts will disappear from About page.','themotion-lite' ),
			'section' => 'themotion_latest_posts',
			'priority' => 1,
		)
	);

	$wp_customize->add_setting(
		'themotion_latest_posts_title', array(
			'default' => esc_html__( 'Recently Posted','themotion-lite' ),
			'sanitize_callback' => 'themotion_sanitize_text',
			'transport' => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'themotion_latest_posts_title', array(
			'label'     => esc_html__( 'Title', 'themotion-lite' ),
			'section'   => 'themotion_latest_posts',
			'priority'  => 2,
		)
	);

	$wp_customize->add_setting(
		'themotion_latest_posts_category', array(
			'default'           => 'all',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'themotion_sanitize_category_dropdown',
		)
	);

	$wp_customize->add_control(
		new Themotion_Category_Selector(
			$wp_customize, 'themotion_latest_posts_category', array(
				'label'     => esc_html__( 'Category', 'themotion-lite' ),
				'section'   => 'themotion_latest_posts',
				'priority'  => 3,
			)
		)
	);

	/* Control for button link*/
	$wp_customize->add_setting(
		'themotion_contact_button_link', array(
			'sanitize_callback' => 'esc_url',
			'transport' => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'themotion_contact_button_link', array(
			'label'     => esc_html__( 'Button URL', 'themotion-lite' ),
			'section'   => 'themotion_contact_header_settings',
			'priority'  => 4,
		)
	);

	$wp_customize->add_section(
		'themotion_contact_cl_settings', array(
			'title' => esc_html__( 'Content Left Settings', 'themotion-lite' ),
			'priority'  => 2,
			'panel' => 'themotion_contact',
		)
	);

	$wp_customize->add_setting(
		'themotion_contact_cl_title', array(
			'default' => esc_html__( 'WHY THE MOTION','themotion-lite' ),
			'sanitize_callback' => 'themotion_sanitize_text',
			'transport' => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'themotion_contact_cl_title', array(
			'label'     => esc_html__( 'Block Title', 'themotion-lite' ),
			'section'   => 'themotion_contact_cl_settings',
			'priority'  => 2,
		)
	);

	$wp_customize->add_setting(
		'themotion_contact_cl_text', array(
			'default'   => esc_html__( 'Using best practices and a keen eye, we curated this video feed for the business beginner and experienced alike. We are a resource for creatives wanting to push their business forward.','themotion-lite' ),
			'sanitize_callback' => 'themotion_sanitize_text',
			'transport' => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'themotion_contact_cl_text', array(
			'label'     => esc_html__( 'Text', 'themotion-lite' ),
			'type'      => 'textarea',
			'section'   => 'themotion_contact_cl_settings',
			'priority'  => 3,
		)
	);

	$wp_customize->add_section(
		'themotion_contact_cr_settings', array(
			'title' => esc_html__( 'Content Right Settings', 'themotion-lite' ),
			'priority'  => 3,
			'panel' => 'themotion_contact',
		)
	);

	$wp_customize->add_setting(
		'themotion_contact_cr_title', array(
			'default' => esc_html__( 'Get in touch','themotion-lite' ),
			'sanitize_callback' => 'themotion_sanitize_text',
			'transport' => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'themotion_contact_cr_title', array(
			'label'     => esc_html__( 'Block Title', 'themotion-lite' ),
			'section'   => 'themotion_contact_cr_settings',
			'priority'  => 1,
		)
	);

	$wp_customize->add_setting(
		'themotion_contact_cr_b1_title', array(
			'default' => esc_html__( 'The.Motion Headquarters','themotion-lite' ),
			'sanitize_callback' => 'themotion_sanitize_text',
			'transport' => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'themotion_contact_cr_b1_title', array(
			'label'     => esc_html__( 'Left side title', 'themotion-lite' ),
			'section'   => 'themotion_contact_cr_settings',
			'priority'  => 2,
		)
	);

	$wp_customize->add_setting(
		'themotion_contact_cr_b1_text', array(
			'default'   => esc_html__( '329 South Street Court - Albany, NY 83741','themotion-lite' ),
			'sanitize_callback' => 'themotion_sanitize_text',
			'transport' => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'themotion_contact_cr_b1_text', array(
			'label'     => esc_html__( 'Left side text', 'themotion-lite' ),
			'type'      => 'textarea',
			'section'   => 'themotion_contact_cr_settings',
			'priority'  => 3,
		)
	);

	$wp_customize->add_setting(
		'themotion_contact_cr_b1_email', array(
			'default' => esc_html__( 'start@example.com','themotion-lite' ),
			'sanitize_callback' => 'themotion_sanitize_text',
			'transport' => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'themotion_contact_cr_b1_email', array(
			'label'     => esc_html__( 'Left side email', 'themotion-lite' ),
			'section'   => 'themotion_contact_cr_settings',
			'priority'  => 3,
		)
	);

	$wp_customize->add_setting(
		'themotion_contact_cr_b1_phone', array(
			'default' => esc_html__( '(432) 203-3321','themotion-lite' ),
			'sanitize_callback' => 'themotion_sanitize_text',
			'transport' => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'themotion_contact_cr_b1_phone', array(
			'label'     => esc_html__( 'Left side phone', 'themotion-lite' ),
			'section'   => 'themotion_contact_cr_settings',
			'priority'  => 4,
		)
	);

	$wp_customize->add_setting(
		'themotion_contact_cr_b2_title', array(
			'default' => esc_html__( 'THE.MOTION VIDEO RECORDING','themotion-lite' ),
			'sanitize_callback' => 'themotion_sanitize_text',
			'transport' => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'themotion_contact_cr_b2_title', array(
			'label'     => esc_html__( 'Right side title', 'themotion-lite' ),
			'section'   => 'themotion_contact_cr_settings',
			'priority'  => 5,
		)
	);

	$wp_customize->add_setting(
		'themotion_contact_cr_b2_text', array(
			'default'   => esc_html__( '153 East Fifth Avenue - New York, NY 83741','themotion-lite' ),
			'sanitize_callback' => 'themotion_sanitize_text',
			'transport' => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'themotion_contact_cr_b2_text', array(
			'label'     => esc_html__( 'Right side text', 'themotion-lite' ),
			'type'      => 'textarea',
			'section'   => 'themotion_contact_cr_settings',
			'priority'  => 6,
		)
	);

	$wp_customize->add_setting(
		'themotion_contact_cr_b2_email', array(
			'default' => esc_html__( 'recording@example.com','themotion-lite' ),
			'sanitize_callback' => 'themotion_sanitize_text',
			'transport' => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'themotion_contact_cr_b2_email', array(
			'label'     => esc_html__( 'Right side email', 'themotion-lite' ),
			'section'   => 'themotion_contact_cr_settings',
			'priority'  => 7,
		)
	);

	$wp_customize->add_setting(
		'themotion_contact_cr_b2_phone', array(
			'default' => esc_html__( '(324) 923-8321','themotion-lite' ),
			'sanitize_callback' => 'themotion_sanitize_text',
			'transport' => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'themotion_contact_cr_b2_phone', array(
			'label'     => esc_html__( 'Right side phone', 'themotion-lite' ),
			'section'   => 'themotion_contact_cr_settings',
			'priority'  => 8,
		)
	);

	$wp_customize->add_control(
		'themotion_contact_cr_b2_phone', array(
			'label'     => esc_html__( 'Right side phone', 'themotion-lite' ),
			'section'   => 'themotion_contact_cr_settings',
			'priority'  => 8,
		)
	);

	/* === Featured image on single post === */
	$wp_customize->add_setting(
		'themotion_single_post_featured_image', array(
			'default' => 0,
			'sanitize_callback' => 'themotion_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'themotion_single_post_featured_image', array(
			'type'     => 'checkbox',
			'label'    => esc_html__( 'Display featured images on single posts', 'themotion-lite' ),
			'section'  => 'title_tagline',
			'priority' => 70,
		)
	);

	$wp_customize->get_control( 'header_image' )->section = 'themotion_header_settings';
	$wp_customize->get_control( 'header_image' )->priority = 5;
	$wp_customize->get_setting( 'header_image' )->transport = 'postMessage';
	$wp_customize->get_setting( 'header_image_data' )->transport = 'postMessage';

}
add_action( 'customize_register', 'themotion_customize_register' );

/**
 * Customizer selective refresh
 *
 * @since 1.1.31
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function themotion_customizer_selective_refresh( $wp_customize ) {
	// Abort if selective refresh is not available.
	if ( ! isset( $wp_customize->selective_refresh ) ) {
		return;
	}

	$wp_customize->selective_refresh->add_partial(
		'themotion_contact_header_text', array(
			'selector' => '.page-template-template-contact .about-top-area-inner h1',
			'render_callback' => 'themotion_contact_header_text_callback',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'themotion_contact_button_text', array(
			'selector' => '.page-template-template-contact .about-top-area-inner .btn',
			'container_inclusive' => true,
			'render_callback' => 'themotion_contact_header_button_text_callback',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'themotion_contact_button_link', array(
			'selector' => '.page-template-template-contact .about-top-area-inner .btn',
			'container_inclusive' => true,
			'render_callback' => 'themotion_contact_header_button_text_callback',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'themotion_contact_cl_title', array(
			'selector' => '.page-template-template-contact .contact-block-left .contact-block-title',
			'render_callback' => 'themotion_contact_cl_title_callback',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'themotion_contact_cl_text', array(
			'selector' => '.page-template-template-contact .contact-block-left .contact-block-content',
			'render_callback' => 'themotion_contact_cl_text_callback',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'themotion_contact_cr_title', array(
			'selector' => '.page-template-template-contact .contact-block-right .contact-block-title',
			'render_callback' => 'themotion_contact_cr_title_callback',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'themotion_contact_cr_b1_title', array(
			'selector' => '.page-template-template-contact .contact-block-right .themotion-block-left .contact-second-title',
			'render_callback' => 'themotion_contact_cr_b1_title_callback',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'themotion_contact_cr_b1_text', array(
			'selector' => '.page-template-template-contact .contact-block-right .themotion-block-left .themotion_contact_left',
			'render_callback' => 'themotion_contact_cr_b1_text_callback',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'themotion_contact_cr_b1_email', array(
			'selector' => '.page-template-template-contact .contact-block-right .themotion-block-left .contact-left-email a',
			'render_callback' => 'themotion_contact_cr_b1_email_callback',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'themotion_contact_cr_b1_phone', array(
			'selector' => '.page-template-template-contact .contact-block-right .themotion-block-left .contact-left-phone a',
			'render_callback' => 'themotion_contact_cr_b1_phone_callback',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'themotion_contact_cr_b2_title', array(
			'selector' => '.page-template-template-contact .contact-block-right .contact-block-content-second .contact-second-title',
			'render_callback' => 'themotion_contact_cr_b2_title_callback',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'themotion_contact_cr_b2_text', array(
			'selector' => '.page-template-template-contact .contact-block-right .contact-block-content-second .themotion_contact_right',
			'render_callback' => 'themotion_contact_cr_b2_text_callback',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'themotion_contact_cr_b2_email', array(
			'selector' => '.page-template-template-contact .contact-block-right .contact-block-content-second .contact-right-email a',
			'render_callback' => 'themotion_contact_cr_b2_email_callback',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'themotion_contact_cr_b2_phone', array(
			'selector' => '.page-template-template-contact .contact-block-right .contact-block-content-second .contact-right-phone a',
			'render_callback' => 'themotion_contact_cr_b2_phone_callback',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'themotion_home1_video_category', array(
			'selector' => '.page-template-template-homepage-one .featured-video-wrap',
			'container_inclusive' => true,
			'render_callback' => 'themotion_home1_show_video_category',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'themotion_home_a_bottom_posts_title', array(
			'selector' => '.page-template-template-homepage-one .recently-posted-title',
			'render_callback' => 'themotion_home_a_bottom_posts_title_callback',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'themotion_home_a_post_nb', array(
			'selector' => '.page-template-template-homepage-one .themotion-pagea-posts',
			'container_inclusive' => true,
			'render_callback' => 'themotion_home_a_show_post_category',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'themotion_home_a_post_category', array(
			'selector' => '.page-template-template-homepage-one .themotion-pagea-posts',
			'container_inclusive' => true,
			'render_callback' => 'themotion_home_a_show_post_category',
		)
	);

}
add_action( 'customize_register', 'themotion_customizer_selective_refresh' );


/**
 * Get contact page header title
 *
 * @since 1.1.31
 * @return string
 */
function themotion_contact_header_text_callback() {
	return get_theme_mod( 'themotion_contact_header_text' );
}

/**
 * Get contact page header button text and link
 *
 * @since 1.1.31
 */
function themotion_contact_header_button_text_callback() {
	$themotion_contact_button_text = get_theme_mod( 'themotion_contact_button_text', esc_html__( 'Send us an email','themotion-lite' ) );
	$themotion_contact_button_link = get_theme_mod( 'themotion_contact_button_link', '#' );

	$button_settings = array(
		'button_class' => 'btn themotion-scroll-to-section',
		'container_class' => 'about-top-area-inner',
	);
	themotion_display_customizer_button( $themotion_contact_button_text, $themotion_contact_button_link ,$button_settings );
}

/**
 * Get contact page left contact block title
 *
 * @since 1.1.31
 * @return string
 */
function themotion_contact_cl_title_callback() {
	return get_theme_mod( 'themotion_contact_cl_title' );
}

/**
 * Get contact page left contact block text
 *
 * @since 1.1.31
 * @return string
 */
function themotion_contact_cl_text_callback() {
	return get_theme_mod( 'themotion_contact_cl_text' );
}

/**
 * Get contact page right contact block title
 *
 * @since 1.1.31
 * @return string
 */
function themotion_contact_cr_title_callback() {
	return get_theme_mod( 'themotion_contact_cr_title' );
}

/**
 * Get contact page right contact block left container title
 *
 * @since 1.1.31
 * @return string
 */
function themotion_contact_cr_b1_title_callback() {
	return get_theme_mod( 'themotion_contact_cr_b1_title' );
}

/**
 * Get contact page right contact block left container text
 *
 * @since 1.1.31
 * @return string
 */
function themotion_contact_cr_b1_text_callback() {
	return get_theme_mod( 'themotion_contact_cr_b1_text' );
}

/**
 * Get contact page right contact block left container email
 *
 * @since 1.1.31
 * @return string
 */
function themotion_contact_cr_b1_email_callback() {
	return get_theme_mod( 'themotion_contact_cr_b1_email' );
}

/**
 * Get contact page right contact block left container phone
 *
 * @since 1.1.31
 * @return string
 */
function themotion_contact_cr_b1_phone_callback() {
	return get_theme_mod( 'themotion_contact_cr_b1_phone' );
}

/**
 * Get contact page right contact block right container title
 *
 * @since 1.1.31
 * @return string
 */
function themotion_contact_cr_b2_title_callback() {
	return get_theme_mod( 'themotion_contact_cr_b2_title' );
}

/**
 * Get contact page right contact block right container text
 *
 * @since 1.1.31
 * @return string
 */
function themotion_contact_cr_b2_text_callback() {
	return get_theme_mod( 'themotion_contact_cr_b2_text' );
}

/**
 * Get contact page right contact block right container email
 *
 * @since 1.1.31
 * @return string
 */
function themotion_contact_cr_b2_email_callback() {
	return get_theme_mod( 'themotion_contact_cr_b2_email' );
}

/**
 * Get contact page right contact block right container phone
 *
 * @since 1.1.31
 * @return string
 */
function themotion_contact_cr_b2_phone_callback() {
	return get_theme_mod( 'themotion_contact_cr_b2_phone' );
}

/**
 * Checkbox Sanitization
 */
function themotion_sanitize_checkbox( $input ) {
	return ( isset( $input ) && true == $input ? true : false );
}

/**
 * Number Sanitization
 */
function themotion_sanitize_number( $input ) {
	return ( ! empty( $input ) ? (int) $input : '');
}

/**
 * Get HomePage A banner Bottom posts title
 *
 * @since 1.1.31
 * @return string
 */
function themotion_home_a_bottom_posts_title_callback() {
	return get_theme_mod( 'themotion_home_a_bottom_posts_title' );
}


/**
 * Repeater Sanitization
 */
function themotion_sanitize_repeater( $input ) {
	if ( ! empty( $input ) ) {
		$input_decoded = json_decode( $input, true );
		if ( ! empty( $input_decoded ) ) {
			foreach ( $input_decoded as $iconk => $iconv ) {
				foreach ( $iconv as $key => $value ) {
					if ( 'link' == $key ) {
						$input_decoded [ $iconk ][ $key ] = esc_url( $value );
					}
				}
			}
			$result = json_encode( $input_decoded );
			return $result;
		}
	}
	return $input;
}

/**
 * Category Dropdown Sanitization
 */
function themotion_sanitize_category_dropdown( $input ) {
	$cat = get_category_by_slug( $input );
	if ( empty( $cat ) ) {
		return 'all';
	}
	return $input;
}

if ( ! function_exists( 'themotion_sanitize_text' ) ) {
	/**
	 * Text Sanitization
	 */
	function themotion_sanitize_text( $input ) {
		return wp_kses_post( force_balance_tags( $input ) );
	}
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function themotion_customize_preview_js() {
	wp_enqueue_script( 'themotion_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), THEMOTION_VERSION, true );
	wp_localize_script(
		'themotion_customizer', 'requestpost', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'placeholder' => esc_html__( 'Loading New Posts...','themotion-lite' ),
		)
	);
}
add_action( 'customize_preview_init', 'themotion_customize_preview_js' );

/**
 * Function to display a button with options from customizer.
 *
 * @since 1.1.31
 */
function themotion_display_customizer_button( $button_text, $button_link, $class = array(
	'button_class' => 'btn themotion-scroll-to-section',
	'container_class' => 'themotion-button-wrapper',
) ) {

	$themotion_go_to = '';
	if ( ! empty( $button_link ) && substr( $button_link, 0, 1 ) === '#' ) {
		$themotion_go_to = 'href="#" onclick="return false;" data-anchor="' . $button_link . '"';
	} elseif ( ! empty( $button_link ) ) {
		$themotion_go_to = 'href="' . esc_url( $button_link ) . '"';
	} ?>
	<div 
	<?php
	if ( ! empty( $class['container_class'] ) ) {
		echo 'class="' . esc_attr( $class['container_class'] ) . '"'; }
?>
>
		<?php
		if ( ! empty( $button_text ) && ! empty( $button_link ) ) {
		?>
			<a <?php echo wp_kses_post( $themotion_go_to ); ?> <?php
			if ( ! empty( $class['button_class'] ) ) {
				echo 'class="' . esc_attr( $class['button_class'] ) . '"';}
?>
>
				<?php
				echo esc_html( $button_text );
				?>
			</a>
			<?php
		}
		?>
	</div>
	<?php
}


/**
 * Get HomePage A video playlist
 *
 * @since 1.1.31
 */
function themotion_home1_show_video_category() {

	$themotion_home1_video_category = get_theme_mod( 'themotion_home1_video_category', 'all' );
	if ( empty( $themotion_home1_video_category ) || $themotion_home1_video_category == 'all' ) {
		$themotion_home1_video_category = '';
	}
	$args = array(
		'category_name'  => $themotion_home1_video_category,
		'post_type'      => 'post',
		'posts_per_page' => '-1',
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

							while ( $the_query->have_posts() ) {
								$the_query->the_post();
								$id = get_the_ID();
								?>
								<div class="item slide-number-<?php echo esc_attr( $id ); ?> <?php
								if ( $active_was_set == 'false' ) {
									echo 'active';
									$active_was_set = 'true';
								}
								?>
">
									<?php
									if ( has_post_format( 'video' ) ) {
										$post    = get_post();
										$content = apply_filters( 'the_content', $post->post_content );
										$embeds  = get_media_embedded_in_content( $content );
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
										} else {
											if ( has_post_thumbnail() ) {
												the_post_thumbnail( 'themotion-thumbnail-blog' );
											}
										}
									} else {
										if ( has_post_thumbnail() ) {
											the_post_thumbnail( 'themotion-thumbnail-blog' );
										}
									}
									?>
								</div>
								<?php
							}

							wp_reset_postdata();
							?>
						</div><!-- Carousel nav -->
					</div>
				</div>

				<div class="themotion-playlist-tracks" id="slider-thumbs">
					<!-- Bottom switcher of slider -->
					<?php
					$first = 'true';
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						$id             = get_the_ID();
						$attached_video = get_attached_media( 'video', $id );

						if ( ! empty( $attached_video ) ) {
							foreach ( $attached_video as $video ) {
								$video_id     = $video->ID;
								$video_meta   = wp_get_attachment_metadata( $video_id );
								$video_length = $video_meta['length_formatted'];
								break;
							}
						}
						?>

						<div class="themotion-playlist-item
								<?php
								if ( $first == 'true' ) {
									echo 'themotion-playlist-playing';
									$first = 'false';
								}
						?>
" id="carousel-selector-<?php echo esc_attr( $id ); ?>" data-id="<?php echo esc_attr( $id ); ?>">
							<?php
							if ( has_post_thumbnail() ) {
								the_post_thumbnail( 'themotion-playlist-thumbnail' );
							} else {
								$video_thumbnail_url = themotion_get_thumbnail_url( $id );
								if ( $video_thumbnail_url === false ) {
									$video_thumbnail_url = get_template_directory_uri() . '/images/small-empty-image.png';
								}
								?>
								<img src="<?php echo esc_url( $video_thumbnail_url ); ?>" alt="<?php esc_html_e( 'Placeholder image', 'themotion-lite' ); ?>">
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
					?>
				</div>
			</div>
		</div>
		<?php
		wp_reset_postdata();
	}// End if().

}


/**
 * Get HomePage A Bottom posts
 *
 * @since 1.1.31
 */
function themotion_home_a_show_post_category() {

	?>
	<div class="themotion-pagea-posts">
		<div class="recently-posted-wrap">
			<?php
			$themotion_home_a_post_nb       = get_theme_mod( 'themotion_home_a_post_nb', 6 );
			$themotion_home_a_post_category = get_theme_mod( 'themotion_home_a_post_category' );
			$args                           = array(
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'posts_per_page'      => ( ! empty( $themotion_home_a_post_nb ) ? (int) $themotion_home_a_post_nb : '6' ),
				'ignore_sticky_posts' => 1,
			);
			if ( ! empty( $themotion_home_a_post_category ) && $themotion_home_a_post_category != 'all' ) {
				$args['category_name'] = $themotion_home_a_post_category;
			}

			$the_query = new WP_Query( $args );

			if ( $the_query->have_posts() ) :
				?>

				<?php
				/* Start the Loop */
				while ( $the_query->have_posts() ) :
					$the_query->the_post();

					/*
                    * Include the Post-Format-specific template for the content.
                    * If you want to override this in a child theme, then include a file
                    * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                    */
					get_template_part( 'template-parts/content', 'home-one' );

				endwhile;

				the_posts_navigation(
					array(
						'prev_text' => sprintf( '&#8592; %s', __( 'Older Posts', 'themotion-lite' ) ),
						'next_text' => sprintf( '%s &#8594;', __( 'Newer Posts', 'themotion-lite' ) ),
					)
				);

				/* Restore original Post Data */
				wp_reset_postdata();

			else :

				get_template_part( 'template-parts/content', 'none' );

			endif;
			?>
		</div>
	</div>
	<?php
}
