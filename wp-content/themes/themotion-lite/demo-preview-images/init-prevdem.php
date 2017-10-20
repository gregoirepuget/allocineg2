<?php
/**
 * Initialize preview on demo
 *
 * @author Themeisle
 * @package themotion
 * @version 1.1.2
 * @since 1.1.21
 */

/**
 * Check if it is demo preview
 *
 * @return bool
 */
function themotion_isprevdem() {
	$ti_theme = wp_get_theme();
	$theme_name = $ti_theme ->get( 'TextDomain' );
	$active_theme = themotion_get_raw_option( 'template' );
	return apply_filters( 'themotion_isprevdem', ( $active_theme != strtolower( $theme_name ) && ! is_child_theme() ) );
}

/**
 * All options or a single option val
 *
 * @param string $opt_name Option name.
 *
 * @return bool|mixed
 */
function themotion_get_raw_option( $opt_name ) {
	$alloptions = wp_cache_get( 'alloptions', 'options' );
	$alloptions = maybe_unserialize( $alloptions );
	return isset( $alloptions[ $opt_name ] ) ? maybe_unserialize( $alloptions[ $opt_name ] ) : false;
}

/**
 * Load functions if we're on demo preview.
 */
if ( themotion_isprevdem() ) {
	load_template( get_template_directory() . '/demo-preview-images/prevdem-functions.php' );
}
