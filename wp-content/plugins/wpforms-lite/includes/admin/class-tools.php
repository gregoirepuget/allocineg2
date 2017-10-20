<?php
/**
 * Tools admin page class.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.3.9
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2017, WPForms LLC
 */
class WPForms_Tools {

	/**
	 * The current active tab.
	 *
	 * @since 1.3.9
	 * @var array
	 */
	public $view;

	/**
	 * Template code if generated.
	 *
	 * @since 1.3.9
	 * @var string
	 */
	private $template = false;

	/**
	 * The available forms.
	 *
	 * @since 1.3.9
	 * @var array
	 */
	public $forms = false;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.3.9
	 */
	public function __construct() {

		// Maybe load tools page.
		add_action( 'admin_init', array( $this, 'init' ) );
	}

	/**
	 * Determing if the user is viewing the tools page, if so, party on.
	 *
	 * @since 1.3.9
	 */
	public function init() {

		// Check what page we are on.
		$page = isset( $_GET['page'] ) ? $_GET['page'] : '';

		// Only load if we are actually on the settings page.
		if ( 'wpforms-tools' === $page ) {

			// Determine the current active settings tab.
			$this->view = isset( $_GET['view'] ) ? esc_html( $_GET['view'] ) : 'importexport';

			// Retrieve available forms.
			$args  = array(
				'orderby' => 'title',
			);
			$this->forms = wpforms()->form->get( '', $args );

			add_action( 'wpforms_tools_init', array( $this, 'import_export_process' ) );
			add_action( 'wpforms_admin_page', array( $this, 'output'                ) );

			// Hook for addons.
			do_action( 'wpforms_tools_init' );
		}
	}

	/**
	 * Build the output for the Tools admin page.
	 *
	 * @since 1.3.9
	 */
	public function output() {

		?>
		<div id="wpforms-tools" class="wrap wpforms-admin-wrap">

			<ul class="wpforms-admin-tabs">
				<li><a href="<?php echo admin_url( 'admin.php?page=wpforms-tools&view=importexport' ); ?>" class="<?php echo 'importexport' === $this->view ? 'active' : ''; ?>"><?php _e( 'Import/Export', 'wpforms' ); ?></a></li>
				<li><a href="<?php echo admin_url( 'admin.php?page=wpforms-tools&view=system' ); ?>" class="<?php echo 'system' === $this->view ? 'active' : ''; ?>"><?php _e( 'System Info', 'wpforms' ); ?></a></li>
			</ul>

			<h1 class="wpforms-h1-placeholder"></h1>

			<?php
			if ( isset( $_GET['wpforms_notice'] ) && 'forms-imported' === $_GET['wpforms_notice'] ) {
				?>
				<div class="updated notice is-dismissible">
					<p><?php printf( __( 'Import was successfully finished. You can go and <a href="%s">check your forms</a>.', 'wpforms' ), admin_url( 'admin.php?page=wpforms-overview' ) ); ?></p>
				</div>
				<?php
			}
			?>

			<div class="wpforms-admin-content wpforms-admin-settings">

				<?php
				if ( 'system' === $this->view ) {
					$this->system_info_tab();
				} else {
					$this->import_export_tab();
				}
				?>

			</div>

		</div>
		<?php
	}

	/**
	 * Import/Export tab contents.
	 *
	 * @since 1.3.9
	 */
	public function import_export_tab() {

		 ?>
		<div class="wpforms-setting-row tools">
			<h3><?php _e( 'Form Import', 'wpforms' ); ?></h3>
			<p><?php _e( 'Select an export file.', 'wpforms' ); ?></p>
			<form method="post" enctype="multipart/form-data" action="<?php echo admin_url( 'admin.php?page=wpforms-tools&view=importexport' ); ?>">
				<div class="wpforms-file-upload">
					<input type="file" name="file" id="wpforms-tools-form-import" class="inputfile" data-multiple-caption="{count} files selected" accept=".json" />
					<label for="wpforms-tools-form-import">
						<span class="fld"><span class="placeholder"><?php _e( 'No file chosen', 'wpforms' ); ?></span></span>
						<strong class="wpforms-btn wpforms-btn-md wpforms-btn-light-grey">
							<i class="fa fa-upload" aria-hidden="true"></i> <?php _e( 'Choose a file&hellip;', 'wpforms' ); ?>
						</strong>
					</label>
				</div>
				<br>
				<input type="hidden" name="action" value="import_form">
				<button type="submit" name="submit-importexport" class="wpforms-btn wpforms-btn-md wpforms-btn-orange"><?php _e( 'Import', 'wpforms' ); ?></button>
				<?php wp_nonce_field( 'wpforms_import_nonce', 'wpforms-tools-importexport-nonce' ); ?>
			</form>
		</div>

		<div class="wpforms-setting-row tools">
			<h3 id="form-export"><?php _e( 'Form Export', 'wpforms' ); ?></h3>
			<p><?php _e( 'Form exports files can be used to create a backup of your forms or to import forms into another site.' ,'wpforms' ); ?></p>
			<form method="post" action="<?php echo admin_url( 'admin.php?page=wpforms-tools&view=importexport' ); ?>">
				<?php
				if ( ! empty( $this->forms ) ) {
					echo '<span class="choicesjs-select-wrap">';
						echo '<select id="wpforms-tools-form-export" class="choicesjs-select" name="forms[]" multiple data-placeholder="' . __( 'Select form(s)', 'wpforms' ) . '">';
							foreach ( $this->forms as $form ) {
								printf( '<option value="%d">%s</option>', $form->ID, esc_html( $form->post_title ) );
							}
						echo '</select>';
					echo '</span>';
				} else {
						echo '<p>' . __( 'You need to create a form before you can use form export.', 'wpforms' ) . '</p>';
				}
				?>
				<br>
				<input type="hidden" name="action" value="export_form">
				<?php wp_nonce_field( 'wpforms_import_nonce', 'wpforms-tools-importexport-nonce' ); ?>
				<button type="submit" name="submit-importexport" class="wpforms-btn wpforms-btn-md wpforms-btn-orange"><?php _e( 'Export', 'wpforms' ); ?></button>
			</form>
		</div>

		<div class="wpforms-setting-row tools">
			<h3 id="template-export"><?php _e( 'Form Template Export', 'wpforms' ); ?></h3>
			<?php
			if ( $this->template ) {
				echo '<p>' . __( 'The following code can be used to register your custom form template. Copy and paste the following code to your theme\'s functions.php file or include it within an external file.', 'wpforms' ) . '<p>';
				echo '<p>' . sprintf( __( 'For more information <a href="%s" target="blank" rel="noopener noreferrer">see our documentation</a>.', 'wpforms' ), 'https://wpforms.com/docs/how-to-create-a-custom-form-template/' ) . '<p>';
				echo '<textarea class="info-area" readonly>' . esc_textarea( $this->template ) . '</textarea><br>';
			}
			?>
			<p><?php _e( 'Select a form to generate PHP code that can be used to register a custom form template.', 'wpforms' ); ?></p>
			<form method="post" action="<?php echo admin_url( 'admin.php?page=wpforms-tools&view=importexport#template-export' ); ?>">
				<?php
				if ( ! empty( $this->forms ) ) {
					echo '<span class="choicesjs-select-wrap">';
						echo '<select id="wpforms-tools-form-template" class="choicesjs-select" name="form">';
							foreach ( $this->forms as $form ) {
								printf( '<option value="%d">%s</option>', $form->ID, esc_html( $form->post_title ) );
							}
						echo '</select>';
					echo '</span>';
				} else {
					echo '<p>' . __( 'You need to create a form before you can generate a template.', 'wpforms' ) . '</p>';
				}
				?>
				<br>
				<input type="hidden" name="action" value="export_template">
				<?php wp_nonce_field( 'wpforms_import_nonce', 'wpforms-tools-importexport-nonce' ); ?>
				<button type="submit" name="submit-importexport" class="wpforms-btn wpforms-btn-md wpforms-btn-orange"><?php _e( 'Export Template', 'wpforms' ); ?></button>
			</form>
		</div>
		<?php
	}

	/**
	 * System Info tab contents.
	 *
	 * @since 1.3.9
	 */
	public function system_info_tab() {

		?>
		<div class="wpforms-setting-row tools">
			<h3 id="form-export"><?php _e( 'System Information', 'wpforms' ); ?></h3>
			<textarea readonly="readonly" class="info-area"><?php echo $this->get_system_info(); ?></textarea>
		</div>
		<?php
	}

	/**
	 * Import/Export processing.
	 *
	 * @since 1.3.9
	 */
	public function import_export_process() {

		// Check for triggered save.
		if ( empty( $_POST['wpforms-tools-importexport-nonce'] ) || empty( $_POST['action'] ) || ! isset( $_POST['submit-importexport'] ) ) {
			return;
		}

		// Check for valid nonce and permission.
		if ( ! wp_verify_nonce( $_POST['wpforms-tools-importexport-nonce'], 'wpforms_import_nonce' ) || ! current_user_can( apply_filters( 'wpforms_manage_cap', 'manage_options' ) ) ) {
			return;
		}

		// Export Form(s).
		if ( 'export_form' === $_POST['action'] && ! empty( $_POST['forms'] ) ) {

			$export = array();
			$forms  = get_posts(
				array(
					'post_type'     => 'wpforms',
					'no_found_rows' => true,
					'nopaging'      => true,
					'post__in'      => array_map( 'intval', $_POST['forms'] ),
				)
			);

			foreach ( $forms as $form ) {
				$export[] = wpforms_decode( $form->post_content );
			}

			ignore_user_abort( true );

			if ( ! in_array( 'set_time_limit', explode( ',', ini_get( 'disable_functions' ) ), true ) ) {
				set_time_limit( 0 );
			}

			nocache_headers();
			header( 'Content-Type: application/json; charset=utf-8' );
			header( 'Content-Disposition: attachment; filename=wpforms-form-export-' . date( 'm-d-Y' ) . '.json' );
			header( 'Expires: 0' );

			echo wp_json_encode( $export );
			exit;
		}

		// Import Form(s).
		if ( 'import_form' === $_POST['action'] && ! empty( $_FILES['file']['tmp_name'] ) ) {

			$ext = strtolower( pathinfo( $_FILES['file']['name'], PATHINFO_EXTENSION ) );

			if ( 'json' !== $ext ) {
				wp_die(
					__( 'Please upload a valid .json form export file.', 'wpforms' ),
					__( 'Error', 'wpforms' ),
					array(
						'response' => 400,
					)
				);
			}

			$forms = json_decode( file_get_contents( $_FILES['file']['tmp_name'] ), true );

			if ( ! empty( $forms ) ) {

				foreach ( $forms as $form ) {

					$title  = ! empty( $form['settings']['form_title'] ) ? $form['settings']['form_title'] : '';
					$desc   = ! empty( $form['settings']['form_desc'] ) ? $form['settings']['form_desc'] : '';
					$new_id = wp_insert_post( array(
						'post_title'   => $title,
						'post_status'  => 'publish',
						'post_type'    => 'wpforms',
						'post_excerpt' => $desc,
					) );
					if ( $new_id ) {
						$form['id'] = $new_id;
						$new = array(
							'ID'           => $new_id,
							'post_content' => wpforms_encode( $form ),
						);
						wp_update_post( $new );
					}
				}
				wp_safe_redirect( admin_url( 'admin.php?page=wpforms-tools&view=importexport&wpforms_notice=forms-imported' ) );
				exit;
			}
		}

		// Export form template.
		if ( 'export_template' === $_POST['action'] && ! empty( $_POST['form'] ) ) {

			$args = array(
				'content_only' => true,
			);
			$form_data = wpforms()->form->get( absint( $_POST['form'] ), $args );

			if ( ! $form_data ) {
				return;
			}

			// Define basic data.
			$name  = sanitize_text_field( $form_data['settings']['form_title'] );
			$desc  = sanitize_text_field( $form_data['settings']['form_desc'] );
			$slug  = sanitize_key( str_replace( ' ', '_' , $form_data['settings']['form_title'] ) );
			$class = 'WPForms_Template_' . $slug;

			// Format template field and settings data.
			$data = $form_data;
			$data['meta']['template'] = $slug;
			$data['fields']           = wpforms_array_remove_empty_strings( $data['fields'] );
			$data['settings']         = wpforms_array_remove_empty_strings( $data['settings'] );

			unset( $data['id'] );

			$data = var_export( $data, true );
			$data = str_replace( '  ', "\t", $data );
			$data = preg_replace( '/([\t\r\n]+?)array/', 'array', $data );

			// Build the final template string.
			$this->template = <<<EOT
if ( class_exists( 'WPForms_Template' ) ) :
/**
 * {$name}
 * Template for WPForms.
 */
class {$class} extends WPForms_Template {

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Template name
		\$this->name = '{$name}';

		// Template slug
		\$this->slug = '{$slug}';

		// Template description
		\$this->description = '{$desc}';

		// Template field and settings
		\$this->data = {$data};
	}
}
new {$class};
endif;
EOT;
		} // End if().
	}

	/**
	 * Get system information.
	 *
	 * Based on a function from Easy Digital Downloads by Pippin Williamson.
	 *
	 * @link https://github.com/easydigitaldownloads/easy-digital-downloads/blob/master/includes/admin/tools.php#L470
	 * @since 1.3.9
	 * @return string
	 */
	public function get_system_info() {

		global $wpdb;

		// Get theme info.
		$theme_data = wp_get_theme();
		$theme      = $theme_data->Name . ' ' . $theme_data->Version;

		$return  = '### Begin System Info ###' . "\n\n";

		// WPForms info.
		$activated = get_option( 'wpforms_activated', array() );
		$return .= '-- WPForms Info' . "\n\n";
		if ( ! empty( $activated['pro'] ) ) {
			$date    = $activated['pro'] + ( get_option( 'gmt_offset' ) * 3600 );
			$return .= 'Pro:                      ' . date_i18n( __( 'M j, Y @ g:ia' ), $date ) . "\n";
		}
		if ( ! empty( $activated['lite'] ) ) {
			$date    = $activated['lite'] + ( get_option( 'gmt_offset' ) * 3600 );
			$return .= 'Lite:                     ' . date_i18n( __( 'M j, Y @ g:ia' ), $date ) . "\n";
		}

		// Now the basics...
		$return .= "\n" . '-- Site Info' . "\n\n";
		$return .= 'Site URL:                 ' . site_url() . "\n";
		$return .= 'Home URL:                 ' . home_url() . "\n";
		$return .= 'Multisite:                ' . ( is_multisite() ? 'Yes' : 'No' ) . "\n";

		// WordPress configuration.
		$return .= "\n" . '-- WordPress Configuration' . "\n\n";
		$return .= 'Version:                  ' . get_bloginfo( 'version' ) . "\n";
		$return .= 'Language:                 ' . ( defined( 'WPLANG' ) && WPLANG ? WPLANG : 'en_US' ) . "\n";
		$return .= 'Permalink Structure:      ' . ( get_option( 'permalink_structure' ) ? get_option( 'permalink_structure' ) : 'Default' ) . "\n";
		$return .= 'Active Theme:             ' . $theme . "\n";
		$return .= 'Show On Front:            ' . get_option( 'show_on_front' ) . "\n";
		// Only show page specs if frontpage is set to 'page'.
		if ( get_option( 'show_on_front' ) === 'page' ) {
			$front_page_id = get_option( 'page_on_front' );
			$blog_page_id = get_option( 'page_for_posts' );
			$return .= 'Page On Front:            ' . ( 0 != $front_page_id ? get_the_title( $front_page_id ) . ' (#' . $front_page_id . ')' : 'Unset' ) . "\n";
			$return .= 'Page For Posts:           ' . ( 0 != $blog_page_id ? get_the_title( $blog_page_id ) . ' (#' . $blog_page_id . ')' : 'Unset' ) . "\n";
		}
		$return .= 'ABSPATH:                  ' . ABSPATH . "\n";
		$return .= 'Table Prefix:             ' . 'Length: ' . strlen( $wpdb->prefix ) . '   Status: ' . ( strlen( $wpdb->prefix ) > 16 ? 'ERROR: Too long' : 'Acceptable' ) . "\n";
		$return .= 'WP_DEBUG:                 ' . ( defined( 'WP_DEBUG' ) ? WP_DEBUG ? 'Enabled' : 'Disabled' : 'Not set' ) . "\n";
		$return .= 'WPFORMS_DEBUG:            ' . ( defined( 'WPFORMS_DEBUG' ) ? WPFORMS_DEBUG ? 'Enabled' : 'Disabled' : 'Not set' ) . "\n";
		$return .= 'Memory Limit:             ' . WP_MEMORY_LIMIT . "\n";
		$return .= 'Registered Post Stati:    ' . implode( ', ', get_post_stati() ) . "\n";

		// @todo WPForms configuration/specific details.
		$return .= "\n" . '-- WordPress Uploads/Constants' . "\n\n";
		$return .= 'WP_CONTENT_DIR:           ' . ( defined( 'WP_CONTENT_DIR' ) ? WP_CONTENT_DIR ? WP_CONTENT_DIR : 'Disabled' : 'Not set' ) . "\n";
		$return .= 'WP_CONTENT_URL:           ' . ( defined( 'WP_CONTENT_URL' ) ? WP_CONTENT_URL ? WP_CONTENT_URL : 'Disabled' : 'Not set' ) . "\n";
		$return .= 'UPLOADS:                  ' . ( defined( 'UPLOADS' ) ? UPLOADS ? UPLOADS : 'Disabled' : 'Not set' ) . "\n";
		$uploads_dir = wp_upload_dir();
		$return .= 'wp_uploads_dir() path:    ' . $uploads_dir['path'] . "\n";
		$return .= 'wp_uploads_dir() url:     ' . $uploads_dir['url'] . "\n";
		$return .= 'wp_uploads_dir() basedir: ' . $uploads_dir['basedir'] . "\n";
		$return .= 'wp_uploads_dir() baseurl: ' . $uploads_dir['baseurl'] . "\n";

		// Get plugins that have an update.
		$updates = get_plugin_updates();

		// Must-use plugins.
		// NOTE: MU plugins can't show updates!
		$muplugins = get_mu_plugins();
		if ( count( $muplugins ) > 0 && ! empty( $muplugins ) ) {
			$return .= "\n" . '-- Must-Use Plugins' . "\n\n";

			foreach ( $muplugins as $plugin => $plugin_data ) {
				$return .= $plugin_data['Name'] . ': ' . $plugin_data['Version'] . "\n";
			}
		}

		// WordPress active plugins.
		$return .= "\n" . '-- WordPress Active Plugins' . "\n\n";

		$plugins = get_plugins();
		$active_plugins = get_option( 'active_plugins', array() );

		foreach ( $plugins as $plugin_path => $plugin ) {
			if ( ! in_array( $plugin_path, $active_plugins, true ) ) {
				continue;
			}
			$update = ( array_key_exists( $plugin_path, $updates ) ) ? ' (needs update - ' . $updates[ $plugin_path ]->update->new_version . ')' : '';
			$return .= $plugin['Name'] . ': ' . $plugin['Version'] . $update . "\n";
		}

		// WordPress inactive plugins.
		$return .= "\n" . '-- WordPress Inactive Plugins' . "\n\n";

		foreach ( $plugins as $plugin_path => $plugin ) {
			if ( in_array( $plugin_path, $active_plugins, true ) ) {
				continue;
			}
			$update = ( array_key_exists( $plugin_path, $updates ) ) ? ' (needs update - ' . $updates[ $plugin_path ]->update->new_version . ')' : '';
			$return .= $plugin['Name'] . ': ' . $plugin['Version'] . $update . "\n";
		}

		if ( is_multisite() ) {
			// WordPress Multisite active plugins.
			$return .= "\n" . '-- Network Active Plugins' . "\n\n";

			$plugins = wp_get_active_network_plugins();
			$active_plugins = get_site_option( 'active_sitewide_plugins', array() );

			foreach ( $plugins as $plugin_path ) {
				$plugin_base = plugin_basename( $plugin_path );
				if ( ! array_key_exists( $plugin_base, $active_plugins ) ) {
					continue;
				}
				$update = ( array_key_exists( $plugin_path, $updates ) ) ? ' (needs update - ' . $updates[ $plugin_path ]->update->new_version . ')' : '';
				$plugin  = get_plugin_data( $plugin_path );
				$return .= $plugin['Name'] . ': ' . $plugin['Version'] . $update . "\n";
			}
		}

		// Server configuration (really just versioning).
		$return .= "\n" . '-- Webserver Configuration' . "\n\n";
		$return .= 'PHP Version:              ' . PHP_VERSION . "\n";
		$return .= 'MySQL Version:            ' . $wpdb->db_version() . "\n";
		$return .= 'Webserver Info:           ' . $_SERVER['SERVER_SOFTWARE'] . "\n";

		// PHP configs... now we're getting to the important stuff.
		$return .= "\n" . '-- PHP Configuration' . "\n\n";
		$return .= 'Memory Limit:             ' . ini_get( 'memory_limit' ) . "\n";
		$return .= 'Upload Max Size:          ' . ini_get( 'upload_max_filesize' ) . "\n";
		$return .= 'Post Max Size:            ' . ini_get( 'post_max_size' ) . "\n";
		$return .= 'Upload Max Filesize:      ' . ini_get( 'upload_max_filesize' ) . "\n";
		$return .= 'Time Limit:               ' . ini_get( 'max_execution_time' ) . "\n";
		$return .= 'Max Input Vars:           ' . ini_get( 'max_input_vars' ) . "\n";
		$return .= 'Display Errors:           ' . ( ini_get( 'display_errors' ) ? 'On (' . ini_get( 'display_errors' ) . ')' : 'N/A' ) . "\n";

		// PHP extensions and such.
		$return .= "\n" . '-- PHP Extensions' . "\n\n";
		$return .= 'cURL:                     ' . ( function_exists( 'curl_init' ) ? 'Supported' : 'Not Supported' ) . "\n";
		$return .= 'fsockopen:                ' . ( function_exists( 'fsockopen' ) ? 'Supported' : 'Not Supported' ) . "\n";
		$return .= 'SOAP Client:              ' . ( class_exists( 'SoapClient' ) ? 'Installed' : 'Not Installed' ) . "\n";
		$return .= 'Suhosin:                  ' . ( extension_loaded( 'suhosin' ) ? 'Installed' : 'Not Installed' ) . "\n";

		// Session stuff
		$return .= "\n" . '-- Session Configuration' . "\n\n";
		$return .= 'Session:                  ' . ( isset( $_SESSION ) ? 'Enabled' : 'Disabled' ) . "\n";

		// The rest of this is only relevant is session is enabled.
		if ( isset( $_SESSION ) ) {
			$return .= 'Session Name:             ' . esc_html( ini_get( 'session.name' ) ) . "\n";
			$return .= 'Cookie Path:              ' . esc_html( ini_get( 'session.cookie_path' ) ) . "\n";
			$return .= 'Save Path:                ' . esc_html( ini_get( 'session.save_path' ) ) . "\n";
			$return .= 'Use Cookies:              ' . ( ini_get( 'session.use_cookies' ) ? 'On' : 'Off' ) . "\n";
			$return .= 'Use Only Cookies:         ' . ( ini_get( 'session.use_only_cookies' ) ? 'On' : 'Off' ) . "\n";
		}

		$return .= "\n" . '### End System Info ###';

		return $return;
	}
}
new WPForms_Tools;
