<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Custom_User_Insertion
 * @subpackage Custom_User_Insertion/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Custom_User_Insertion
 * @subpackage Custom_User_Insertion/includes
 * @author     Hilay Trivedi <hilay.trivedi@multidos.com>
 */
class Custom_User_Insertion_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		if ( ! current_user_can( 'activate_plugins' ) ) return;
  
		global $wpdb;

		$login_file_path = plugin_dir_url(__FILE__).'/admin/templates/template-post-login.php';
		
		if ( null === $wpdb->get_row( "SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = 'new-page-slug'", 'ARRAY_A' ) ) {
		   
		  $current_user = wp_get_current_user();
		  
		  // create post object
		  $page_login = array(
			'post_title'  => __( 'Login' ),
			'post_status' => 'publish',
			'post_author' => $current_user->ID,
			'post_type'   => 'page',
			'comment_status' => 'closed',
            'ping_status'    => 'closed',
            'post_content'   => '',
            'post_status'    => 'publish',
            'menu_order'     => 0,
		  );
		  
		  // insert the post into the database
		  wp_insert_post( $page_login );

		  $page_register = array(
			'post_title'  => __( 'Register' ),
			'post_status' => 'publish',
			'post_author' => $current_user->ID,
			'post_type'   => 'page',
			'comment_status' => 'closed',
            'ping_status'    => 'closed',
            'post_content'   => '',
            'post_status'    => 'publish',
            'menu_order'     => 0,
		  );
		  
		  // insert the post into the database
		  wp_insert_post( $page_register );
		}
	}

}
