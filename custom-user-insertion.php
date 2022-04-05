<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.multidots.com/
 * @since             1.0.0
 * @package           Custom_User_Insertion
 *
 * @wordpress-plugin
 * Plugin Name:       Custom User Insertion
 * Plugin URI:        https://www.multidots.com/
 * Description:       you can add and search the user from the front end.
 * Version:           1.0.0
 * Author:            Hilay Trivedi
 * Author URI:        https://www.multidots.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       custom-user-insertion
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CUSTOM_USER_INSERTION_VERSION', '1.0.0' );
define( 'MYCAPTCHAKEY', '6LceHUgfAAAAALS2oYgFoM1Lh_BHgjw9XXEvoAYH' );
define( 'MYCAPTCHASECRETKEY', '6LceHUgfAAAAAG8lCU6-ttaxebZkYSQy-qwM7Omp' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-custom-user-insertion-activator.php
 */
function activate_custom_user_insertion() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-custom-user-insertion-activator.php';
	Custom_User_Insertion_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-custom-user-insertion-deactivator.php
 */
function deactivate_custom_user_insertion() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-custom-user-insertion-deactivator.php';
	Custom_User_Insertion_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_custom_user_insertion' );
register_deactivation_hook( __FILE__, 'deactivate_custom_user_insertion' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-custom-user-insertion.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_custom_user_insertion() {

	$plugin = new Custom_User_Insertion();
	$plugin->run();

}
run_custom_user_insertion();
