<?php
/**
 * Search form.
 *
 * @package themotion
 */
?>
<form role="search" method="get" class="search-form search-toggle" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php _ex( 'Search for:', 'label', 'themotion-lite' ); ?></span>
		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'themotion-lite' ); ?>" value="<?php echo get_search_query(); ?>" name="s" title="<?php esc_attr( _ex( 'Search for:', 'label', 'themotion-lite' ) ); ?>">
	</label>
	<input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'themotion-lite' ); ?>">
	<span class="search-quit"></span>
</form>
