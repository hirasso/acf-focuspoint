<?php

/*
Plugin Name: ACF: FocusPoint
Plugin URI: https://github.com/ooksanen/acf-focuspoint/
Description: Adds a new "FocusPoint" field type to Advanced Custom Fields allowing users to select a focal point on images.
Version: 1.2.1
Author: Oskari Oksanen
Author URI: https://oskarioksanen.fi
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('acffp_acf_plugin_focuspoint') ) :

class acffp_acf_plugin_focuspoint {

	// vars
	public static array $settings;


	/*
	*  __construct
	*
	*  This function will setup the class functionality
	*
	*  @type	function
	*  @date	17/02/2016
	*  @since	1.0.0
	*
	*  @param	void
	*  @return	void
	*/

	function __construct() {

		// settings
		// - these will be passed into the field class.
		self::$settings = array(
			'version'	=> '1.2.1',
			'url'		=> plugin_dir_url( __FILE__ ),
			'path'		=> plugin_dir_path( __FILE__ )
		);


		// include field
		add_action('acf/include_field_types', array($this, 'include_field')); // v5
		add_filter('acf/load_value/type=image', array($this, 'load_value_image'));
	}


	/*
	*  include_field
	*
	*  This function will include the field type class
	*
	*  @type	function
	*  @date	17/02/2016
	*  @since	1.0.0
	*
	*  @param	$version (int) major ACF version. Defaults to false
	*  @return	void
	*/

	function include_field( $version = false ) {

		// load textdomain
		load_plugin_textdomain( 'acffp', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' );

		// include
		include_once('fields/class-acffp-acf-field-focuspoint-v' . $version . '.php');
	}

	/**
	 * Load the value for an image field if it previously was a focuspoint field
	 */
	public function load_value_image($value) {
		if (
			$this->has_exact_keys($value, ['id', 'left', 'top'])
			&& is_numeric($value['id'])
			&& wp_attachment_is_image($value['id'])
			) {
			return intval($value['id']);
		}
		return $value;
	}

	/**
	 * Checks if an array has exactly the keys requested
	 */
	private function has_exact_keys($array, $keys) {
		if (!is_array($array)) {
			return false;
		}
		$arrayKeys = array_keys($array);
		sort($arrayKeys);
		sort($keys);
		return $arrayKeys === $keys;
	}

}


// initialize
new acffp_acf_plugin_focuspoint();


// class_exists check
endif;
