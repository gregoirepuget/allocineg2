<?php
/**
 * Class Themotion_General_Repeater. Create repeater control in customizer.
 *
 * @package themotion
 */

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return null;
}

/**
 * Class Themotion_General_Repeater.
 */
class Themotion_General_Repeater extends WP_Customize_Control {

	/**
	 * Control type
	 *
	 * @var string
	 */
	public $type = 'range-value';

	/**
	 * Box title
	 *
	 * @var array
	 */
	private $boxtitle = array();

	/**
	 * Add new field button label.
	 *
	 * @var string
	 */
	private $add_field_label;

	/**
	 * Text field flag.
	 *
	 * @var bool
	 */
	private $customizer_repeater_text_control = false;

	/**
	 * Link field flag.
	 *
	 * @var bool
	 */
	private $customizer_repeater_link_control = false;


	/**
	 * Themotion_General_Repeater constructor.
	 *
	 * @param WP_Customize_Manager $manager Customizer manager.
	 * @param string               $id Control id.
	 * @param array                $args Control options.
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );
		/*Get options from customizer.php*/
		$this->add_field_label = esc_html__( 'Add new field', 'themotion-lite' );
		if ( ! empty( $args['add_field_label'] ) ) {
			$this->add_field_label = $args['add_field_label'];
		}

		$this->boxtitle = esc_html__( 'Customizer Repeater', 'themotion-lite' );
		if ( ! empty( $args['item_name'] ) ) {
			$this->boxtitle = $args['item_name'];
		} elseif ( ! empty( $this->label ) ) {
			$this->boxtitle = $this->label;
		}

		if ( ! empty( $args['themotion_text_control'] ) ) {
			$this->customizer_repeater_text_control = $args['themotion_text_control'];
		}

		if ( ! empty( $args['themotion_link_control'] ) ) {
			$this->customizer_repeater_link_control = $args['themotion_link_control'];
		}

	}

	/**
	 * Enqueue resources for the control
	 */
	public function enqueue() {

		wp_enqueue_style( 'themotion-repeater-admin-stylesheet', get_template_directory_uri() . '/inc/customizer-repeater/css/admin-style.css', array(), THEMOTION_VERSION );

		wp_enqueue_script( 'themotion_customizer-repeater-script', get_template_directory_uri() . '/inc/customizer-repeater/js/customizer_repeater.js', array( 'jquery', 'jquery-ui-draggable' ), THEMOTION_VERSION, true );

	}

	/**
	 * Render control content.
	 */
	public function render_content() {

		/*Get default options*/
		$this_default = json_decode( $this->setting->default );

		/*Get values (json format)*/
		$values = $this->value();

		/*Decode values*/
		$json = json_decode( $values );

		if ( ! is_array( $json ) ) {
			$json = array( $values );
		} ?>

		<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<div class="customizer-repeater-general-control-repeater customizer-repeater-general-control-droppable">
			<?php
			if ( ( count( $json ) == 1 && '' === $json[0] ) || empty( $json ) ) {
				if ( ! empty( $this_default ) ) {
					$this->iterate_array( $this_default );
					?>
					<input type="hidden"
						id="customizer-repeater-<?php echo esc_attr( $this->id ); ?>-colector" <?php esc_attr( $this->link() ); ?>
						class="customizer-repeater-colector"
						value="<?php echo esc_attr( json_encode( $this_default ) ); ?>"/>
					<?php
				} else {
					$this->iterate_array();
					?>
					<input type="hidden"
						id="customizer-repeater-<?php echo esc_attr( $this->id ); ?>-colector" <?php esc_attr( $this->link() ); ?>
						class="customizer-repeater-colector"/>
					<?php
				}
			} else {
				$this->iterate_array( $json );
				?>
				<input type="hidden" id="customizer-repeater-<?php echo esc_attr( $this->id ); ?>-colector" <?php esc_attr( $this->link() ); ?>
					class="customizer-repeater-colector" value="<?php echo esc_attr( $this->value() ); ?>"/>
				<?php
			}
			?>
		</div>
		<button type="button" class="button add_field customizer-repeater-new-field">
			<?php echo esc_html( $this->add_field_label ); ?>
		</button>
		<?php
	}

	/**
	 * Iterate through control value.
	 *
	 * @param array $array Control value.
	 */
	private function iterate_array( $array = array() ) {
		/*Counter that helps checking if the box is first and should have the delete button disabled*/
		$it = 0;
		if ( ! empty( $array ) ) {
			foreach ( $array as $icon ) {
			?>
				<div class="customizer-repeater-general-control-repeater-container customizer-repeater-draggable">
					<div class="customizer-repeater-customize-control-title">
						<?php echo esc_html( $this->boxtitle ); ?>
					</div>
					<div class="customizer-repeater-box-content-hidden">
						<?php
						$text = '';
						$link = '';

						if ( ! empty( $icon->text ) ) {
							$text = $icon->text;
						}
						if ( ! empty( $icon->link ) ) {
							$link = $icon->link;
						}
						if ( $this->customizer_repeater_text_control == true ) {
							$this->input_control(
								array(
									'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Text','themotion-lite' ), $this->id, 'customizer_repeater_text_control' ),
									'class' => 'customizer-repeater-text-control',
									'type'  => apply_filters( 'themotion_repeater_input_types_filter', 'textarea', $this->id, 'customizer_repeater_text_control' ),
								), $text
							);
						}
						if ( $this->customizer_repeater_link_control ) {
							$this->input_control(
								array(
									'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Link','themotion-lite' ), $this->id, 'customizer_repeater_link_control' ),
									'class' => 'customizer-repeater-link-control',
									'sanitize_callback' => 'esc_url_raw',
									'type'  => apply_filters( 'themotion_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link_control' ),
								), $link
							);
						}
						?>

						<button type="button" class="social-repeater-general-control-remove-field" 
						<?php
						if ( $it == 0 ) {
							echo 'style="display:none;"';
						}
?>
>
							<?php esc_html_e( 'Delete field', 'themotion-lite' ); ?>
						</button>

					</div>
				</div>

				<?php
				$it++;
			}// End foreach().
		} else {
		?>
			<div class="customizer-repeater-general-control-repeater-container">
				<div class="customizer-repeater-customize-control-title">
					<?php echo esc_html( $this->boxtitle ); ?>
				</div>
				<div class="customizer-repeater-box-content-hidden">
					<?php
					if ( $this->customizer_repeater_text_control == true ) {
						$this->input_control(
							array(
								'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Text','themotion-lite' ), $this->id, 'customizer_repeater_text_control' ),
								'class' => 'customizer-repeater-text-control',
								'type'  => apply_filters( 'themotion_repeater_input_types_filter', 'textarea', $this->id, 'customizer_repeater_text_control' ),
							)
						);
					}
					if ( $this->customizer_repeater_link_control == true ) {
						$this->input_control(
							array(
								'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Link','themotion-lite' ), $this->id, 'customizer_repeater_link_control' ),
								'class' => 'customizer-repeater-link-control',
								'type'  => apply_filters( 'themotion_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link_control' ),
							)
						);
					}
					?>

					<button type="button" class="social-repeater-general-control-remove-field button" style="display:none;">
						<?php esc_html_e( 'Delete field', 'themotion-lite' ); ?>
					</button>
				</div>
			</div>
			<?php
		}// End if().
	}

	/**
	 * Display control fields.
	 *
	 * @param array  $options Field options.
	 * @param string $value Field value.
	 */
	private function input_control( $options, $value = '' ) {
		?>
		<span class="customize-control-title"><?php echo esc_html( $options['label'] ); ?></span>
		<?php
		if ( ! empty( $options['type'] ) ) {
			switch ( $options['type'] ) {
				case 'textarea':
					$value = ! empty( $options['sanitize_callback'] ) ? call_user_func_array( $options['sanitize_callback'], array( $value ) ) : $value;
					?>
					<textarea class="<?php echo esc_attr( $options['class'] ); ?>" placeholder="<?php echo esc_attr( $options['label'] ); ?>"><?php echo esc_textarea( $value ); ?></textarea>
					<?php
					break;
			}
		} else {
			$value = ! empty( $options['sanitize_callback'] ) ? call_user_func_array( $options['sanitize_callback'], array( $value ) ) : $value;
			?>
			<input type="text" value="<?php echo esc_attr( $value ); ?>" class="<?php echo esc_attr( $options['class'] ); ?>" placeholder="<?php echo esc_attr( $options['label'] ); ?>"/>
			<?php
		}
	}
}
