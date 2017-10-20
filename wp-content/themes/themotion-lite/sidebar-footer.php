<?php
/**
 * The Footer Sidebar
 *
 * @package themotion
 */
if ( ! is_active_sidebar( 'footer-area' ) && ! is_active_sidebar( 'footer-area-2' ) && ! is_active_sidebar( 'footer-area-3' ) ) {
	return;
} ?>
<div class="themotion-bottom-sidebars">
	<?php
	if ( is_active_sidebar( 'footer-area' ) ) {
	?>
		<div itemscope itemtype="http://schema.org/WPSideBar" role="complementary" id="sidebar-widgets-area-1" class="col-sm-12 col-md-4 col-lg-4" aria-label="<?php esc_attr_e( 'Widgets Area 1','themotion-lite' ); ?>">
			<?php
			dynamic_sidebar( 'footer-area' );
			?>
		</div>
		<?php
	}
	if ( is_active_sidebar( 'footer-area-2' ) ) {
	?>
		<div itemscope itemtype="http://schema.org/WPSideBar" role="complementary" id="sidebar-widgets-area-2" class="col-sm-12 col-md-4 col-lg-4" aria-label="<?php esc_attr_e( 'Widgets Area 2','themotion-lite' ); ?>">
			<?php
			dynamic_sidebar( 'footer-area-2' );
			?>
		</div>
		<?php
	}
	if ( is_active_sidebar( 'footer-area-3' ) ) {
	?>
		<div itemscope itemtype="http://schema.org/WPSideBar" role="complementary" id="sidebar-widgets-area-3" class="col-sm-12 col-md-4 col-lg-4" aria-label="<?php esc_attr_e( 'Widgets Area 3','themotion-lite' ); ?>">
			<?php
			dynamic_sidebar( 'footer-area-3' );
			?>
		</div>
		<?php
	}
	?>
</div>
