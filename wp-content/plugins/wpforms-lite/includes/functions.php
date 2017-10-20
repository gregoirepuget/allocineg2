<?php
/**
 * Contains various functions that may be potentially used throughout
 * the WPForms plugin.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
 */

/**
 * Helper function to trigger displaying a form.
 *
 * @since 1.0.2
 *
 * @param mixed $form_id
 * @param bool $title
 * @param bool $desc
 */
function wpforms_display( $form_id = false, $title = false, $desc = false ) {

	wpforms()->frontend->output( $form_id, $title, $desc );
}

/**
 * Performs json_decode and unslash.
 *
 * @since 1.0.0
 *
 * @param string $data
 *
 * @return array|bool
 */
function wpforms_decode( $data ) {

	if ( ! $data || empty( $data ) ) {
		return false;
	}

	return wp_unslash( json_decode( $data, true ) );
}

/**
 * Performs json_encode and wp_slash.
 *
 * @since 1.3.1.3
 *
 * @param mixed $data
 *
 * @return string
 */
function wpforms_encode( $data = false ) {

	if ( empty( $data ) ) {
		return false;
	}

	return wp_slash( wp_json_encode( $data ) );
}

/**
 * Check if a string is a valid URL.
 *
 * @since 1.0.0
 *
 * @param string $url
 *
 * @return bool
 */
function wpforms_is_url( $url ) {

	if ( preg_match( '_^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))(?::\d{2,5})?(?:/[^\s]*)?$_iuS', trim( $url ) ) ) {
		return true;
	}

	return false;
}

/**
 * Get current URL.
 *
 * @since 1.0.0
 *
 * @return string
 */
function wpforms_current_url() {

	$url = ( ! empty( $_SERVER['HTTPS'] ) ) ? 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] : 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

	return esc_url_raw( $url );
}

/**
 * Object to array.
 *
 * @since 1.1.7
 *
 * @param object $object
 *
 * @return mixed
 */
function wpforms_object_to_array( $object ) {

	if ( ! is_object( $object ) && ! is_array( $object ) ) {
		return $object;
	}

	if ( is_object( $object ) ) {
		$object = get_object_vars( $object );
	}

	return array_map( 'wpforms_object_to_array', $object );
}

/**
 * Get the value of a specific WPForms setting.
 *
 * @since 1.0.0
 *
 * @param string $key
 * @param mixed $default
 * @param string $option
 *
 * @return mixed
 */
function wpforms_setting( $key, $default = false, $option = 'wpforms_settings' ) {

	$key     = wpforms_sanitize_key( $key );
	$options = get_option( $option, false );
	$value   = is_array( $options ) && ! empty( $options[ $key ] ) ? $options[ $key ] : $default;

	return $value;
}

/**
 * Sanitize key, primarily used for looking up options.
 *
 * @since 1.3.9
 *
 * @param string $key
 *
 * @return string
 */
function wpforms_sanitize_key( $key = '' ) {

	return preg_replace( '/[^a-zA-Z0-9_\-\.\:\/]/', '', $key );
}

/**
 * Check if form provided contains the specified field type.
 *
 * @since 1.0.5
 *
 * @param string $type
 * @param array $form
 * @param bool $multiple
 *
 * @return bool
 */
function wpforms_has_field_type( $type, $form, $multiple = false ) {

	$form_data = '';
	$field     = false;
	$type      = (array) $type;

	if ( $multiple ) {
		foreach ( $form as $single_form ) {
			$field = wpforms_has_field_type( $type, $single_form );
			if ( $field ) {
				break;
			}
		}

		return $field;
	} else {

		if ( is_object( $form ) && ! empty( $form->post_content ) ) {
			$form_data = wpforms_decode( $form->post_content );
		} elseif ( is_array( $form ) ) {
			$form_data = $form;
		}

		if ( empty( $form_data['fields'] ) ) {
			return false;
		}

		foreach ( $form_data['fields'] as $single_field ) {
			if ( in_array( $single_field['type'], $type, true ) ) {
				$field = true;
				break;
			}
		}

		return $field;
	}
}

/**
 * Checks if form provided contains page breaks, if so give details.
 *
 * @since 1.0.0
 *
 * @param mixed $form
 *
 * @return mixed
 */
function wpforms_has_pagebreak( $form = false ) {

	$form_data = '';
	$pagebreak = false;
	$pages     = 1;

	if ( is_object( $form ) && ! empty( $form->post_content ) ) {
		$form_data = wpforms_decode( $form->post_content );
	} elseif ( is_array( $form ) ) {
		$form_data = $form;
	}

	if ( empty( $form_data['fields'] ) ) {
		return false;
	}

	$fields = $form_data['fields'];

	foreach ( $fields as $field ) {
		if ( 'pagebreak' === $field['type'] && empty( $field['position'] ) ) {
			$pagebreak = true;
			$pages ++;
		}
	}

	if ( $pagebreak ) {
		return $pages;
	} else {
		return false;
	}
}

/**
 * Tries to find and return an top or bottom pagebreak.
 *
 * @since 1.2.1
 *
 * @param mixed $form
 * @param mixed $type
 *
 * @return array|bool
 */
function wpforms_get_pagebreak( $form = false, $type = false ) {

	$form_data = '';

	if ( is_object( $form ) && ! empty( $form->post_content ) ) {
		$form_data = wpforms_decode( $form->post_content );
	} elseif ( is_array( $form ) ) {
		$form_data = $form;
	}

	if ( empty( $form_data['fields'] ) ) {
		return false;
	}

	$fields = $form_data['fields'];
	$pages  = array();

	foreach ( $fields as $field ) {
		if ( 'pagebreak' === $field['type'] ) {
			$position = ! empty( $field['position'] ) ? $field['position'] : false;
			if ( 'pages' === $type && 'bottom' !== $position ) {
				$pages[] = $field;
			} elseif ( $position === $type ) {
				return $field;
			}
		}
	}

	if ( ! empty( $pages ) ) {
		return $pages;
	}

	return false;
}

/**
 * Returns information about pages if the form has multiple pages.
 *
 * @since 1.3.7
 *
 * @param mixed $form
 *
 * @return mixed false or an array
 */
function wpforms_get_pagebreak_details( $form = false ) {

	$form_data = '';
	$details   = array();
	$pages     = 1;

	if ( is_object( $form ) && ! empty( $form->post_content ) ) {
		$form_data = wpforms_decode( $form->post_content );
	} elseif ( is_array( $form ) ) {
		$form_data = $form;
	}

	if ( empty( $form_data['fields'] ) ) {
		return false;
	}

	foreach ( $form_data['fields'] as $field ) {
		if ( 'pagebreak' === $field['type'] ) {
			if ( empty( $field['position'] ) ) {
				$pages ++;
				$details['total']   = $pages;
				$details['pages'][] = $field;
			} elseif ( 'top' === $field['position'] ) {
				$details['top'] = $field;
			} elseif ( 'bottom' === $field['position'] ) {
				$details['bottom'] = $field;
			}
		}
	}

	if ( ! empty( $details ) ) {
		if ( empty( $details['top'] ) ) {
			$details['top'] = array();
		}
		if ( empty( $details['bottom'] ) ) {
			$details['bottom'] = array();
		}
		$details['current'] = 1;

		return $details;
	} else {
		return false;
	}
}

/**
 * Formats, sanitizes, and returns/echos HTML element ID, classes, attributes,
 * and data attributes.
 *
 * @since 1.3.7
 *
 * @param string $id
 * @param array $class
 * @param array $datas
 * @param array $atts
 * @param bool $echo
 *
 * @return string
 */
function wpforms_html_attributes( $id = '', $class = array(), $datas = array(), $atts = array(), $echo = false ) {

	$output = '';
	$id     = trim( $id );

	if ( ! empty( $id ) ) {
		$output = 'id="' . sanitize_html_class( $id ) . '" ';
	}

	if ( ! empty( $class ) ) {
		$output .= 'class="' . wpforms_sanitize_classes( $class, true ) . '" ';
	}

	if ( ! empty( $datas ) ) {
		foreach ( $datas as $data => $val ) {
			$output .= 'data-' . sanitize_html_class( $data ) . '="' . esc_attr( $val ) . '" ';
		}
	}

	if ( ! empty( $atts ) ) {
		foreach ( $atts as $att => $val ) {
			if ( '0' == $val || ! empty( $val ) ) {
				$output .= sanitize_html_class( $att ) . '="' . esc_attr( $val ) . '" ';
			}
		}
	}

	if ( $echo ) {
		echo trim( $output );
	} else {
		return trim( $output );
	}
}

/**
 * Sanitizes string of CSS classes.
 *
 * @since 1.2.1
 *
 * @param string $classes
 * @param bool $convert True will convert strings to array and vice versa.
 *
 * @return string|array
 */
function wpforms_sanitize_classes( $classes, $convert = false ) {

	$array = is_array( $classes );
	$css   = array();

	if ( ! empty( $classes ) ) {
		if ( ! $array ) {
			$classes = explode( ' ', trim( $classes ) );
		}
		foreach ( $classes as $class ) {
			$css[] = sanitize_html_class( $class );
		}
	}
	if ( $array ) {
		return $convert ? implode( ' ', $css ) : $css;
	} else {
		return $convert ? $css : implode( ' ', $css );
	}
}

/**
 * Convert a file size provided, such as "2M", to bytes.
 *
 * @since 1.0.0
 * @link http://stackoverflow.com/a/22500394
 *
 * @param string $size
 *
 * @return int
 */
function wpforms_size_to_bytes( $size ) {

	if ( is_numeric( $size ) ) {
		return $size;
	}

	$suffix = substr( $size, - 1 );
	$value  = substr( $size, 0, - 1 );

	switch ( strtoupper( $suffix ) ) {
		case 'P':
			$value *= 1024;
		case 'T':
			$value *= 1024;
		case 'G':
			$value *= 1024;
		case 'M':
			$value *= 1024;
		case 'K':
			$value *= 1024;
			break;
	}

	return $value;
}

/**
 * Convert bytes to megabytes (or in some cases KB).
 *
 * @since 1.0.0
 *
 * @param int $bytes
 *
 * @return string
 */
function wpforms_size_to_megabytes( $bytes ) {

	if ( $bytes < 1048676 ) {
		return number_format( $bytes / 1024, 1 ) . ' KB';
	} else {
		return round( number_format( $bytes / 1048576, 1 ) ) . ' MB';
	}
}

/**
 * Convert a file size provided, such as "2M", to bytes.
 *
 * @since 1.0.0
 * @link http://stackoverflow.com/a/22500394
 *
 * @param bool $bytes
 *
 * @return mixed
 */
function wpforms_max_upload( $bytes = false ) {

	$max = wp_max_upload_size();
	if ( $bytes ) {
		return $max;
	} else {
		return wpforms_size_to_megabytes( $max );
	}
}

/**
 * Retrieve actual fields from a form.
 *
 * Non-posting elements such as section divider, page break, and HTML are
 * automatically excluded. Optionally a white list can be provided.
 *
 * @since 1.0.0
 *
 * @param mixed $form
 * @param array $whitelist
 *
 * @return mixed boolean or array
 */
function wpforms_get_form_fields( $form = false, $whitelist = array() ) {

	// Accept form (post) object or form ID
	if ( is_object( $form ) ) {
		$form = wpforms_decode( $form->post_content );
	} elseif ( is_numeric( $form ) ) {
		$form = wpforms()->form->get(
			$form,
			array(
				'content_only' => true,
			)
		);
	}

	if ( ! is_array( $form ) || empty( $form['fields'] ) ) {
		return false;
	}

	// White list of field types to allow
	$allowed_form_fields = array(
		'text',
		'textarea',
		'select',
		'radio',
		'checkbox',
		'email',
		'address',
		'url',
		'name',
		'hidden',
		'date-time',
		'phone',
		'number',
		'file-upload',
		'payment-single',
		'payment-multiple',
		'payment-select',
		'payment-total',
	);
	$allowed_form_fields = apply_filters( 'wpforms_get_form_fields_allowed', $allowed_form_fields );

	$whitelist = ! empty( $whitelist ) ? $whitelist : $allowed_form_fields;

	$form_fields = $form['fields'];

	foreach ( $form_fields as $id => $form_field ) {
		if ( ! in_array( $form_field['type'], $whitelist, true ) ) {
			unset( $form_fields[ $id ] );
		}
	}

	return $form_fields;
}

/**
 * Get meta key value for a form field.
 *
 * @since 1.1.9
 *
 * @param int|string $id Field ID.
 * @param string $key Meta key.
 * @param mixed $form_data Form data array.
 *
 * @return string
 */
function wpforms_get_form_field_meta( $id = '', $key = '', $form_data = '' ) {

	if ( empty( $id ) || empty( $key ) || empty( $form_data ) ) {
		return '';
	}

	if ( ! empty( $form_data['fields'][ $id ]['meta'][ $key ] ) ) {
		return $form_data['fields'][ $id ]['meta'][ $key ];
	} else {
		return '';
	}
}

/**
 * Get meta key value for a form field.
 *
 * @since 1.3.1
 *
 * @param string $key Meta key.
 * @param string $value
 * @param mixed $form_data Form data array.
 *
 * @return string
 */
function wpforms_get_form_fields_by_meta( $key = '', $value = '', $form_data = '' ) {

	if ( empty( $key ) || empty( $value ) || empty( $form_data['fields'] ) ) {
		return false;
	}

	$found = array();

	foreach ( $form_data['fields'] as $id => $field ) {

		if ( ! empty( $field['meta'][ $key ] ) && $value == $field['meta'][ $key ] ) {
			$found[ $id ] = $field;
		}
	}

	if ( ! empty( $found ) ) {
		return $found;
	} else {
		return false;
	}
}

/**
 * US States
 *
 * @since 1.0.0
 * @return array
 */
function wpforms_us_states() {

	$states = array(
		'AL' => 'Alabama',
		'AK' => 'Alaska',
		'AZ' => 'Arizona',
		'AR' => 'Arkansas',
		'CA' => 'California',
		'CO' => 'Colorado',
		'CT' => 'Connecticut',
		'DE' => 'Delaware',
		'DC' => 'District of Columbia',
		'FL' => 'Florida',
		'GA' => 'Georgia',
		'HI' => 'Hawaii',
		'ID' => 'Idaho',
		'IL' => 'Illinois',
		'IN' => 'Indiana',
		'IA' => 'Iowa',
		'KS' => 'Kansas',
		'KY' => 'Kentucky',
		'LA' => 'Louisiana',
		'ME' => 'Maine',
		'MD' => 'Maryland',
		'MA' => 'Massachusetts',
		'MI' => 'Michigan',
		'MN' => 'Minnesota',
		'MS' => 'Mississippi',
		'MO' => 'Missouri',
		'MT' => 'Montana',
		'NE' => 'Nebraska',
		'NV' => 'Nevada',
		'NH' => 'New Hampshire',
		'NJ' => 'New Jersey',
		'NM' => 'New Mexico',
		'NY' => 'New York',
		'NC' => 'North Carolina',
		'ND' => 'North Dakota',
		'OH' => 'Ohio',
		'OK' => 'Oklahoma',
		'OR' => 'Oregon',
		'PA' => 'Pennsylvania',
		'RI' => 'Rhode Island',
		'SC' => 'South Carolina',
		'SD' => 'South Dakota',
		'TN' => 'Tennessee',
		'TX' => 'Texas',
		'UT' => 'Utah',
		'VT' => 'Vermont',
		'VA' => 'Virginia',
		'WA' => 'Washington',
		'WV' => 'West Virginia',
		'WI' => 'Wisconsin',
		'WY' => 'Wyoming',
	);

	return apply_filters( 'wpforms_us_states', $states );
}

/**
 * Countries.
 *
 * @since 1.0.0
 * @return array
 */
function wpforms_countries() {

	$countries = array(
		'AF' => 'Afghanistan',
		'AX' => 'Aland Islands',
		'AL' => 'Albania',
		'DZ' => 'Algeria',
		'AS' => 'American Samoa',
		'AD' => 'Andorra',
		'AO' => 'Angola',
		'AI' => 'Anguilla',
		'AQ' => 'Antarctica',
		'AG' => 'Antigua and Barbuda',
		'AR' => 'Argentina',
		'AM' => 'Armenia',
		'AW' => 'Aruba',
		'AC' => 'Ascension Island',
		'AU' => 'Australia',
		'AT' => 'Austria',
		'AZ' => 'Azerbaijan',
		'BS' => 'Bahamas',
		'BH' => 'Bahrain',
		'BD' => 'Bangladesh',
		'BB' => 'Barbados',
		'BY' => 'Belarus',
		'BE' => 'Belgium',
		'BZ' => 'Belize',
		'BJ' => 'Benin',
		'BM' => 'Bermuda',
		'BT' => 'Bhutan',
		'BO' => 'Bolivia',
		'BA' => 'Bosnia and Herzegovina',
		'BW' => 'Botswana',
		'BV' => 'Bouvet Island',
		'BR' => 'Brazil',
		'IO' => 'British Indian Ocean Territory',
		'VG' => 'British Virgin Islands',
		'BN' => 'Brunei',
		'BG' => 'Bulgaria',
		'BF' => 'Burkina Faso',
		'BI' => 'Burundi',
		'KH' => 'Cambodia',
		'CM' => 'Cameroon',
		'CA' => 'Canada',
		'IC' => 'Canary Islands',
		'CV' => 'Cape Verde',
		'BQ' => 'Caribbean Netherlands',
		'KY' => 'Cayman Islands',
		'CF' => 'Central African Republic',
		'EA' => 'Ceuta and Melilla',
		'TD' => 'Chad',
		'CL' => 'Chile',
		'CN' => 'China',
		'CX' => 'Christmas Island',
		'CP' => 'Clipperton Island',
		'CC' => 'Cocos (Keeling) Islands',
		'CO' => 'Colombia',
		'KM' => 'Comoros',
		'CD' => 'Congo (DRC)',
		'CG' => 'Congo (Republic)',
		'CK' => 'Cook Islands',
		'CR' => 'Costa Rica',
		'CI' => 'Côte d’Ivoire',
		'HR' => 'Croatia',
		'CU' => 'Cuba',
		'CW' => 'Curaçao',
		'CY' => 'Cyprus',
		'CZ' => 'Czech Republic',
		'DK' => 'Denmark (Danmark)',
		'DG' => 'Diego Garcia',
		'DJ' => 'Djibouti',
		'DM' => 'Dominica',
		'DO' => 'Dominican Republic',
		'EC' => 'Ecuador',
		'EG' => 'Egypt',
		'SV' => 'El Salvador',
		'GQ' => 'Equatorial Guinea',
		'ER' => 'Eritrea',
		'EE' => 'Estonia',
		'ET' => 'Ethiopia',
		'FK' => 'Falkland Islands',
		'FO' => 'Faroe Islands',
		'FJ' => 'Fiji',
		'FI' => 'Finland',
		'FR' => 'France',
		'GF' => 'French Guiana',
		'PF' => 'French Polynesia',
		'TF' => 'French Southern Territories',
		'GA' => 'Gabon',
		'GM' => 'Gambia',
		'GE' => 'Georgia',
		'DE' => 'Germany',
		'GH' => 'Ghana',
		'GI' => 'Gibraltar',
		'GR' => 'Greece',
		'GL' => 'Greenland',
		'GD' => 'Grenada',
		'GP' => 'Guadeloupe',
		'GU' => 'Guam',
		'GT' => 'Guatemala',
		'GG' => 'Guernsey',
		'GN' => 'Guinea',
		'GW' => 'Guinea-Bissau',
		'GY' => 'Guyana',
		'HT' => 'Haiti',
		'HM' => 'Heard & McDonald Islands',
		'HN' => 'Honduras',
		'HK' => 'Hong Kong',
		'HU' => 'Hungary',
		'IS' => 'Iceland',
		'IN' => 'India',
		'ID' => 'Indonesia',
		'IR' => 'Iran',
		'IQ' => 'Iraq',
		'IE' => 'Ireland',
		'IM' => 'Isle of Man',
		'IL' => 'Israel',
		'IT' => 'Italy',
		'JM' => 'Jamaica',
		'JP' => 'Japan',
		'JE' => 'Jersey',
		'JO' => 'Jordan',
		'KZ' => 'Kazakhstan',
		'KE' => 'Kenya',
		'KI' => 'Kiribati',
		'XK' => 'Kosovo',
		'KW' => 'Kuwait',
		'KG' => 'Kyrgyzstan',
		'LA' => 'Laos',
		'LV' => 'Latvia',
		'LB' => 'Lebanon',
		'LS' => 'Lesotho',
		'LR' => 'Liberia',
		'LY' => 'Libya',
		'LI' => 'Liechtenstein',
		'LT' => 'Lithuania',
		'LU' => 'Luxembourg',
		'MO' => 'Macau',
		'MK' => 'Macedonia (FYROM)',
		'MG' => 'Madagascar',
		'MW' => 'Malawi',
		'MY' => 'Malaysia',
		'MV' => 'Maldives',
		'ML' => 'Mali',
		'MT' => 'Malta',
		'MH' => 'Marshall Islands',
		'MQ' => 'Martinique',
		'MR' => 'Mauritania',
		'MU' => 'Mauritius',
		'YT' => 'Mayotte',
		'MX' => 'Mexico',
		'FM' => 'Micronesia',
		'MD' => 'Moldova',
		'MC' => 'Monaco',
		'MN' => 'Mongolia',
		'ME' => 'Montenegro',
		'MS' => 'Montserrat',
		'MA' => 'Morocco',
		'MZ' => 'Mozambique',
		'MM' => 'Myanmar',
		'NA' => 'Namibia',
		'NR' => 'Nauru',
		'NP' => 'Nepal',
		'NL' => 'Netherlands',
		'NC' => 'New Caledonia',
		'NZ' => 'New Zealand',
		'NI' => 'Nicaragua',
		'NE' => 'Niger',
		'NG' => 'Nigeria',
		'NU' => 'Niue',
		'NF' => 'Norfolk Island',
		'MP' => 'Northern Mariana Islands',
		'KP' => 'North Korea',
		'NO' => 'Norway',
		'OM' => 'Oman',
		'PK' => 'Pakistan',
		'PW' => 'Palau',
		'PS' => 'Palestine',
		'PA' => 'Panama',
		'PG' => 'Papua New Guinea',
		'PY' => 'Paraguay',
		'PE' => 'Peru',
		'PH' => 'Philippines',
		'PN' => 'Pitcairn Islands',
		'PL' => 'Poland',
		'PT' => 'Portugal',
		'PR' => 'Puerto Rico',
		'QA' => 'Qatar',
		'RE' => 'Réunion',
		'RO' => 'Romania',
		'RU' => 'Russia',
		'RW' => 'Rwanda',
		'BL' => 'Saint Barthélemy',
		'SH' => 'Saint Helena',
		'KN' => 'Saint Kitts and Nevis',
		'LC' => 'Saint Lucia',
		'MF' => 'Saint Martin',
		'PM' => 'Saint Pierre and Miquelon',
		'WS' => 'Samoa',
		'SM' => 'San Marino',
		'ST' => 'São Tomé and Príncipe',
		'SA' => 'Saudi Arabia',
		'SN' => 'Senegal',
		'RS' => 'Serbia',
		'SC' => 'Seychelles',
		'SL' => 'Sierra Leone',
		'SG' => 'Singapore',
		'SX' => 'Sint Maarten',
		'SK' => 'Slovakia',
		'SI' => 'Slovenia',
		'SB' => 'Solomon Islands',
		'SO' => 'Somalia',
		'ZA' => 'South Africa',
		'GS' => 'South Georgia & South Sandwich Islands',
		'KR' => 'South Korea',
		'SS' => 'South Sudan',
		'ES' => 'Spain',
		'LK' => 'Sri Lanka',
		'VC' => 'St. Vincent & Grenadines',
		'SD' => 'Sudan',
		'SR' => 'Suriname',
		'SJ' => 'Svalbard and Jan Mayen',
		'SZ' => 'Swaziland',
		'SE' => 'Sweden',
		'CH' => 'Switzerland',
		'SY' => 'Syria',
		'TW' => 'Taiwan',
		'TJ' => 'Tajikistan',
		'TZ' => 'Tanzania',
		'TH' => 'Thailand',
		'TL' => 'Timor-Leste',
		'TG' => 'Togo',
		'TK' => 'Tokelau',
		'TO' => 'Tonga',
		'TT' => 'Trinidad and Tobago',
		'TA' => 'Tristan da Cunha',
		'TN' => 'Tunisia',
		'TR' => 'Turkey',
		'TM' => 'Turkmenistan',
		'TC' => 'Turks and Caicos Islands',
		'TV' => 'Tuvalu',
		'UM' => 'U.S. Outlying Islands',
		'VI' => 'U.S. Virgin Islands',
		'UG' => 'Uganda',
		'UA' => 'Ukraine',
		'AE' => 'United Arab Emirates',
		'GB' => 'United Kingdom',
		'US' => 'United States',
		'UY' => 'Uruguay',
		'UZ' => 'Uzbekistan',
		'VU' => 'Vanuatu',
		'VA' => 'Vatican City',
		'VE' => 'Venezuela',
		'VN' => 'Vietnam',
		'WF' => 'Wallis and Futuna',
		'EH' => 'Western Sahara',
		'YE' => 'Yemen',
		'ZM' => 'Zambia',
		'ZW' => 'Zimbabwe',
	);

	return apply_filters( 'wpforms_countries', $countries );
}

/**
 * Calendar Months
 *
 * @since 1.3.7
 * @return array
 */
function wpforms_months() {

	$months = array(
		'Jan' => 'January',
		'Feb' => 'February',
		'Mar' => 'March',
		'Apr' => 'April',
		'May' => 'May',
		'Jun' => 'June',
		'Jul' => 'July',
		'Aug' => 'August',
		'Sep' => 'September',
		'Oct' => 'October',
		'Nov' => 'November',
		'Dec' => 'December',
	);

	return apply_filters( 'wpforms_months', $months );
}

/**
 * Calendar Days
 *
 * @since 1.3.7
 * @return array
 */
function wpforms_days() {

	$days = array(
		'Sun' => 'Sunday',
		'Mon' => 'Monday',
		'Tue' => 'Tuesday',
		'Wed' => 'Wednesday',
		'Thu' => 'Thursday',
		'Fri' => 'Friday',
		'Sat' => 'Saturday',
	);

	return apply_filters( 'wpforms_days', $days );
}

/**
 * Lookup user IP.
 *
 * There are many ways to do this, but we prefer the way EDD does it.
 * https://github.com/easydigitaldownloads/easy-digital-downloads/blob/master/includes/misc-functions.php#L163
 *
 * @since 1.2.5
 * @return string
 */
function wpforms_get_ip() {

	$ip = '127.0.0.1';

	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} elseif ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}

	// Fix potential CSV returned from $_SERVER variables
	$ip_array = array_map( 'trim', explode( ',', $ip ) );

	return $ip_array[0];
}

/**
 * Sanitizes hex color.
 *
 * @since 1.2.1
 *
 * @param string $color
 *
 * @return string
 */
function wpforms_sanitize_hex_color( $color ) {

	if ( empty( $color ) ) {
		return '';
	}

	// 3 or 6 hex digits, or the empty string.
	if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
		return $color;
	}

	return '';
}

/**
 * Sanitizes error message, primarily used during form frontend output.
 *
 * @since 1.3.7
 *
 * @param string $error
 *
 * @return string
 */
function wpforms_sanitize_error( $error = '' ) {

	$allow = array(
		'a'      => array(
			'href'  => array(),
			'title' => array(),
		),
		'br'     => array(),
		'em'     => array(),
		'strong' => array(),
		'p'      => array(),
	);

	return wp_kses( $error, $allow );
}

/**
 * Sanitizes a string, that can be a multiline.
 * If WP core `sanitize_textarea_field()` exists (after 4.7.0) - use it.
 * Otherwise - split onto separate lines, sanitize each one, merge again.
 *
 * @since 1.4.1
 *
 * @param string $string
 *
 * @return string If empty var is passed, or not a string - return unmodified. Otherwise - sanitize.
 */
function wpforms_sanitize_textarea_field( $string ) {

	if ( empty( $string ) || ! is_string( $string ) ) {
		return $string;
	}

	if ( function_exists( 'sanitize_textarea_field' ) ) {
		$string = sanitize_textarea_field( $string );
	} else {
		$string = implode( "\n", array_map( 'sanitize_text_field', explode( "\n", $string ) ) );
	}

	return $string;
}

/**
 * Sanitizes an array, that consists of values as strings.
 * After that - merge all array values into multiline string.
 *
 * @since 1.4.1
 *
 * @param array $array
 *
 * @return mixed If not an array is passed (or empty var) - return unmodified var. Otherwise - a merged array into multiline string.
 */
function wpforms_sanitize_array_combine( $array ) {

	if ( empty( $array ) || ! is_array( $array ) ) {
		return $array;
	}

	return implode( "\n", array_map( 'sanitize_text_field', $array ) );
}

/**
 * Detect if we should use a light or dark color based on the color given.
 *
 * @since 1.2.5
 * @link https://docs.woocommerce.com/wc-apidocs/source-function-wc_light_or_dark.html#608-627
 *
 * @param mixed $color
 * @param string $dark (default: '#000000').
 * @param string $light (default: '#FFFFFF').
 *
 * @return string
 */
function wpforms_light_or_dark( $color, $dark = '#000000', $light = '#FFFFFF' ) {

	$hex = str_replace( '#', '', $color );

	$c_r = hexdec( substr( $hex, 0, 2 ) );
	$c_g = hexdec( substr( $hex, 2, 2 ) );
	$c_b = hexdec( substr( $hex, 4, 2 ) );

	$brightness = ( ( $c_r * 299 ) + ( $c_g * 587 ) + ( $c_b * 114 ) ) / 1000;

	return $brightness > 155 ? $dark : $light;
}

/**
 * Builds and returns either a taxonomy or post type object that is
 * nests to accommodate any hierarchy.
 *
 * @since 1.3.9
 *
 * @param array $args
 * @param bool $flat
 *
 * @return bool|array
 */
function wpforms_get_hierarchical_object( $args = array(), $flat = false ) {

	if ( empty( $args['taxonomy'] ) && empty( $args['post_type'] ) ) {
		return false;
	}

	$children = array();
	$parents  = array();

	if ( ! empty( $args['post_type'] ) ) {

		$defaults   = array(
			'posts_per_page' => - 1,
			'orderby'        => 'title',
			'order'          => 'ASC',
		);
		$args       = wp_parse_args( $args, $defaults );
		$items      = get_posts( $args );
		$ref_parent = 'post_parent';
		$ref_id     = 'ID';
		$ref_name   = 'post_title';

	} elseif ( ! empty( $args['taxonomy'] ) ) {

		$defaults   = array(
			'hide_empty' => false,
		);
		$args       = wp_parse_args( $args, $defaults );
		$items      = get_terms( $args );
		$ref_parent = 'parent';
		$ref_id     = 'term_id';
		$ref_name   = 'name';
	}

	if ( empty( $items ) || is_wp_error( $items ) ) {
		return false;
	}

	foreach ( $items as $item ) {
		if ( $item->{$ref_parent} ) {
			$children[ $item->{$ref_id} ]     = $item;
			$children[ $item->{$ref_id} ]->ID = (int) $item->{$ref_id};
		} else {
			$parents[ $item->{$ref_id} ]     = $item;
			$parents[ $item->{$ref_id} ]->ID = (int) $item->{$ref_id};
		}
	}

	$children_count = count( $children );
	while ( $children_count >= 1 ) {
		foreach ( $children as $child ) {
			_wpforms_get_hierarchical_object_search( $child, $parents, $children, $ref_parent );
		}
	}

	if ( $flat ) {
		$parents_flat = array();
		_wpforms_get_hierarchical_object_flatten( $parents, $parents_flat, $ref_name );

		return $parents_flat;
	}

	return $parents;
}

/**
 * Searches a given array and finds the parent of the provided object.
 *
 * @since 1.3.9
 *
 * @param object $child
 * @param array $parents
 * @param array $children
 * @param string $ref_parent
 */
function _wpforms_get_hierarchical_object_search( $child, &$parents, &$children, $ref_parent ) {

	foreach ( $parents as $id => $parent ) {

		if ( $parent->ID === $child->{$ref_parent} ) {

			if ( empty( $parent->children ) ) {
				$parents[ $id ]->children = array(
					$child->ID => $child,
				);
			} else {
				$parents[ $id ]->children[ $child->ID ] = $child;
			}

			unset( $children[ $child->ID ] );

		} elseif ( ! empty( $parent->children ) && is_array( $parent->children ) ) {

			_wpforms_get_hierarchical_object_search( $child, $parent->children, $children, $ref_parent );
		}
	}
}

/**
 * Flattens a hierarchical object.
 *
 * @since 1.3.9
 *
 * @param array $array
 * @param array $output
 * @param string $ref_name
 * @param int $level
 */
function _wpforms_get_hierarchical_object_flatten( $array, &$output, $ref_name = 'name', $level = 0 ) {

	foreach ( $array as $key => $item ) {

		$indicator           = apply_filters( 'wpforms_hierarchical_object_indicator', '&mdash;' );
		$item->{$ref_name}   = str_repeat( $indicator, $level ) . ' ' . $item->{$ref_name};
		$item->depth         = $level + 1;
		$output[ $item->ID ] = $item;

		if ( ! empty( $item->children ) ) {

			_wpforms_get_hierarchical_object_flatten( $item->children, $output, $ref_name, $level + 1 );
			unset( $output[ $item->ID ]->children );
		}
	}
}

/**
 * Insert an array into another array before/after a certain key.
 *
 * @since 1.3.9
 * @link https://gist.github.com/scribu/588429
 *
 * @param array $array The initial array.
 * @param array $pairs The array to insert.
 * @param string $key The certain key.
 * @param string $position Where to insert the array - before or after the key.
 *
 * @return array
 */
function wpforms_array_insert( $array, $pairs, $key, $position = 'after' ) {

	$key_pos = array_search( $key, array_keys( $array ), true );
	if ( 'after' === $position ) {
		$key_pos ++;
	}

	if ( false !== $key_pos ) {
		$result = array_slice( $array, 0, $key_pos );
		$result = array_merge( $result, $pairs );
		$result = array_merge( $result, array_slice( $array, $key_pos ) );
	} else {
		$result = array_merge( $array, $pairs );
	}

	return $result;
}

/**
 * Recursively remove empty strings from an array.
 *
 * @since 1.3.9.1
 *
 * @param array $data
 *
 * @return array
 */
function wpforms_array_remove_empty_strings( $data ) {

	foreach ( $data as $key => $value ) {
		if ( is_array( $value ) ) {
			$data[ $key ] = wpforms_array_remove_empty_strings( $data[ $key ] );
		}

		if ( '' === $data[ $key ] ) {
			unset( $data[ $key ] );
		}
	}

	return $data;
}

/**
 * Debug mode bool.
 *
 * @since 1.2.3
 *
 * @return bool
 */
function wpforms_debug() {

	$debug = false;

	if ( ( defined( 'WPFORMS_DEBUG' ) && true === WPFORMS_DEBUG ) && is_super_admin() ) {
		$debug = true;
	}

	$debug_option = get_option( 'wpforms_debug' );

	if ( $debug_option ) {
		$current_user = wp_get_current_user();
		if ( $current_user->user_login === $debug_option ) {
			$debug = true;
		}
	}

	return apply_filters( 'wpforms_debug', $debug );
}

/**
 * Helper function to display debug data.
 *
 * @since 1.0.0
 *
 * @param mixed $data
 * @param bool $echo
 *
 * @return string
 */
function wpforms_debug_data( $data, $echo = true ) {

	if ( wpforms_debug() ) {

		$output = '<textarea style="background:#fff;margin: 20px 0;width:100%;height:500px;font-size:12px;font-family: Consolas,Monaco,monospace;direction: ltr;unicode-bidi: embed;line-height: 1.4;padding: 4px 6px 1px;" readonly>';

		$output .= "=================== WPFORMS DEBUG ===================\n\n";

		if ( is_array( $data ) || is_object( $data ) ) {
			$output .= ( print_r( $data, true ) );
		} else {
			$output .= $data;
		}

		$output .= '</textarea>';

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}
}

/**
 * Log helper.
 *
 * @since 1.0.0
 *
 * @param string $title
 * @param string $message
 * @param array $args
 */
function wpforms_log( $title = '', $message = '', $args = array() ) {

	// Require log title
	if ( empty( $title ) ) {
		return;
	}

	// Force logging everything when in debug mode
	if ( ! wpforms_debug() ) {

		/**
		 * Compare error levels to determine if we should log.
		 * Current supported levels:
		 * - Errors (error)
		 * - Spam (spam)
		 * - Entries (entry)
		 * - Payments (payment)
		 * - Providers (provider)
		 * - Conditional Logic (conditional_logic)
		 */
		$type   = ! empty( $args['type'] ) ? (array) $args['type'] : array( 'error' );
		$levels = array_intersect( $type, get_option( 'wpforms_logging', array() ) );
		if ( empty( $levels ) ) {
			return;
		}
	}

	// Meta.
	if ( ! empty( $args['form_id'] ) ) {
		$meta = array(
			'form' => absint( $args['form_id'] ),
		);
	} elseif ( ! empty( $args['meta'] ) ) {
		$meta = $args['meta'];
	} else {
		$meta = '';
	}

	// Parent.
	$parent = ! empty( $args['parent'] ) ? $args['parent'] : 0;

	// Make arrays and objects look nice
	if ( is_array( $message ) || is_object( $message ) ) {
		$message = '<pre>' . print_r( $message, true ) . '</pre>';
	}

	// Create log entry
	wpforms()->logs->add( $title, $message, $parent, $parent, $meta );
}

/**
 * Array replace recursive, for PHP 5.2.
 */
if ( ! function_exists( 'array_replace_recursive' ) ) :
	/**
	 * PHP-agnostic version of {@link array_replace_recursive()}.
	 *
	 * The array_replace_recursive() function is a PHP 5.3 function. WordPress
	 * currently supports down to PHP 5.2, so this method is a workaround
	 * for PHP 5.2.
	 *
	 * Note: array_replace_recursive() supports infinite arguments, but for our use-
	 * case, we only need to support two arguments.
	 *
	 * Subject to removal once WordPress makes PHP 5.3.0 the minimum requirement.
	 *
	 * @since 1.2.3
	 * @see http://php.net/manual/en/function.array-replace-recursive.php#109390
	 *
	 * @param  array $base Array with keys needing to be replaced.
	 * @param  array $replacements Array with the replaced keys.
	 *
	 * @return array
	 */
	function array_replace_recursive( $base = array(), $replacements = array() ) {
		// PHP 5.2-compatible version
		// http://php.net/manual/en/function.array-replace-recursive.php#109390.
		foreach ( array_slice( func_get_args(), 1 ) as $replacements ) {
			$bref_stack = array( &$base );
			$head_stack = array( $replacements );
			do {
				end( $bref_stack );
				$bref = &$bref_stack[ key( $bref_stack ) ];
				$head = array_pop( $head_stack );
				unset( $bref_stack[ key( $bref_stack ) ] );
				foreach ( array_keys( $head ) as $key ) {
					if ( isset( $key, $bref ) && is_array( $bref[ $key ] ) && is_array( $head[ $key ] ) ) {
						$bref_stack[] = &$bref[ $key ];
						$head_stack[] = $head[ $key ];
					} else {
						$bref[ $key ] = $head[ $key ];
					}
				}
			} while ( count( $head_stack ) );
		}

		return $base;
	}
endif;

/**
 * Check whether the current page is in AMP mode or not.
 * We need to check for specific functions, as there is no special AMP header.
 *
 * @since 1.4.1
 *
 * @return bool
 */
function wpforms_is_amp() {

	$is_amp = false;

	if (
		// AMP by Automattic; ampforwp
		( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) ||
		// Better AMP
		( function_exists( 'is_better_amp' ) && is_better_amp() )
	) {
		$is_amp = true;
	}

	return apply_filters( 'wpforms_is_amp', $is_amp );
}

/**
 * Decode special characters, both alpha- (<) and numeric-based (').
 *
 * @since 1.4.1
 *
 * @param string $string
 *
 * @return string
 */
function wpforms_decode_string( $string ) {

	if ( ! is_string( $string) ) {
		return $string;
	}

	return wp_kses_decode_entities( htmlspecialchars_decode( $string ) );
}

/**
 * Get a suffix for assets, `.min` if debug is enabled.
 *
 * @return string
 */
function wpforms_get_min_suffix() {
	return wpforms_debug() ? '.min' : '';
}
