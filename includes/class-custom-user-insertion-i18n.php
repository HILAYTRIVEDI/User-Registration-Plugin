<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Custom_User_Insertion
 * @subpackage Custom_User_Insertion/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Custom_User_Insertion
 * @subpackage Custom_User_Insertion/includes
 * @author     Hilay Trivedi <hilay.trivedi@multidos.com>
 */
class Custom_User_Insertion_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'custom-user-insertion',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
