<?php
/**
 * Theme info customizer controls.
 *
 * @package themotion
 * @author Themeisle
 * @version 1.2.3
 */

/**
 * Hook Theme Info section to customizer.
 *
 * @access public
 * @since 1.2.3
 * @param WP_Customize_Manager $wp_customize The wp_customize object.
 */
function themotion_theme_info_customize_register( $wp_customize ) {
	// Include upsell class.
	require_once( get_template_directory() . '/inc/customizer-pro/class/class-themotion-control-upsell.php' );

	// Add Theme Info Section.
	$wp_customize->add_section(
		'themotion_features_section', array(
			'title'    => __( 'View PRO version', 'themotion-lite' ),
			'priority' => 1,
		)
	);

	// Add upsells.
	$wp_customize->add_setting(
		'themotion_upsell_pro_features_main', array(
			'sanitize_callback' => 'esc_html',
		)
	);

	$wp_customize->add_control(
		new Themotion_Control_Upsell(
			$wp_customize, 'themotion_upsell_pro_features_main', array(
				'section'      => 'themotion_features_section',
				'priority'     => 1,
				'options'      => array(
					esc_html__( 'New frontpage template', 'themotion-lite' ),
					esc_html__( 'About Page template', 'themotion-lite' ),
					esc_html__( 'WPML/Polylang compatibility', 'themotion-lite' ),
					esc_html__( 'Support', 'themotion-lite' ),
				),
				'button_url'   => esc_url( 'https://themeisle.com/themes/themotion/' ),
				// xss ok
				'button_text'  => esc_html__( 'View PRO version', 'themotion-lite' ),
			)
		)
	);

}
add_action( 'customize_register', 'themotion_theme_info_customize_register' );

