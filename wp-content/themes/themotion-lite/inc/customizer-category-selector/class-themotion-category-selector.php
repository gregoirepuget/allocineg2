<?php
/**
 * Category selector customizer control.
 *
 * @package themotion
 */


/**
 * Check for WP_Customize_control.
 */
if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return null;
}

/**
 * Category Selector Class
 */
class Themotion_Category_Selector extends WP_Customize_Control {

	/**
	 * Render the control.
	 */
	public function render_content() {
		$categories = get_categories(); ?>

		<span class="customize-control-title">
			<?php
			echo esc_html( $this->label );
			?>
		</span>

		<select <?php $this->link(); ?>>
			<option value="all"><?php esc_html_e( 'All', 'themotion-lite' ); ?></option>
			<?php
			foreach ( $categories as $cat ) {
				if ( $cat->count > 0 ) {
					echo '<option value="' . esc_attr( $cat->slug ) . '" ' . selected( $this->value(), $cat->slug ) . '>' . esc_html( $cat->cat_name ) . '</option>';
				}
			}
			?>
		</select>
		<?php
	}
}
