<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Custom_User_Insertion
 * @subpackage Custom_User_Insertion/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Custom_User_Insertion
 * @subpackage Custom_User_Insertion/admin
 * @author     Hilay Trivedi <hilay.trivedi@multidos.com>
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) die("No Hacking!");

if( !class_exists('Custom_User_Insertion_Admin') ){

	class Custom_User_Insertion_Admin {

		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {
	
			wp_enqueue_style( "Custom_User_Insertion_css", plugin_dir_url( __FILE__ ) . 'css/custom-user-insertion-admin.css', array(), "1.0.0", 'all' );
	
		}
	
		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {
	
			wp_enqueue_script( "Custom_User_Insertion_js", plugin_dir_url( __FILE__ ) . 'js/custom-user-insertion-admin.js', array( 'jquery' ), "1.0.0", false );
	
		}

		/**
		 * Register the Custom admin page for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function custom_user_admin_menu() {
			add_menu_page(
				__( 'Custom Users skills', 'Custom_User_Insertion' ),
				__( 'Custom Users skills menu', 'Custom_User_Insertion' ),
				'manage_options',
				'custom-user-skills',
				array($this,'custom_user_admin_menu_content_callback'),
				'dashicons-schedule',
				7
			);
		}

		/**
		 * A call back funciton of custom admin page content
		 *
		 * @since    1.0.0
		 */
		public function custom_user_admin_menu_content_callback() {
			require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/custom-user-insertion-admin-display.php';
		}

		/**
		 * Register the settings section to the cutom admin page
		 *
		 * @since    1.0.0
		 */
		public function custom_user_skills()
		{
			//Adding general setting section
			add_settings_section(
				'custom_user_skills',
				'Custom User Skills',
				array($this,'custom_user_skills_callback'),
				'custom_user_skills'
			);
			register_setting('custom_user_skills', 'custom-user-admin-page__skill--list');
			register_setting('custom_user_skills', 'custom-user-admin-page__email');
		}

		/**
		 * Call back function for the custom setting section
		 *
		 * @since    1.0.0
		 */
		public function custom_user_skills_callback(){

			require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/custom_user_settings_callback.php';

		}


		/**
		 * Register the Custom Post Type for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function custom_posts(){
			// UI labels for Custom Post Type
			$labels = array(
				'name'                => _x( 'Custom Users', 'Post Type General Name'),
				'singular_name'       => _x( 'User', 'Post Type Singular Name'),
				'menu_name'           => __( 'Custom Users'),
				'parent_item_colon'   => __( 'Parent User'),
				'all_items'           => __( 'All Custom Users'),
				'view_item'           => __( 'View User'),
				'add_new_item'        => __( 'Add New User'),
				'add_new'             => __( 'Add New'),
				'edit_item'           => __( 'Edit User'),
				'update_item'         => __( 'Update User'),
				'search_items'        => __( 'Search User'),
				'not_found'           => __( 'Not Found'),
				'not_found_in_trash'  => __( 'Not found in Trash'),
			);
			 
			// options for Custom Post Type
			 
			$args = array(
				'label'               => __( 'users' ),
				'description'         => __( 'Custom user' ),
				'labels'              => $labels,
				'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
				'taxonomies'          => array( 'user_category','user_tag'),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => 5,
				'menu_icon'           => 'dashicons-groups',
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'capability_type'     => 'post',
				'show_in_rest' 		  => true,
		 
			);
			register_post_type( 'custom_user', $args );	 
		}

		/**
		 * Register the Custom Taxonomy for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function custom_taxonomy(){
			$labels = array(
				'name'              => _x( 'Custom User Categorys', 'taxonomy general name' ),
				'singular_name'     => _x( 'Custom User Category', 'taxonomy singular name' ),
				'search_items'      => __( 'Search Custom User Categorys' ),
				'all_items'         => __( 'All Custom User Categorys' ),
				'parent_item'       => __( 'Parent Custom User Category' ),
				'parent_item_colon' => __( 'Parent Custom User Category:' ),
				'edit_item'         => __( 'Edit Custom User Category' ),
				'update_item'       => __( 'Update Custom User Category' ),
				'add_new_item'      => __( 'Add New Custom User Category' ),
				'new_item_name'     => __( 'New Custom User Category Name' ),
				'menu_name'         => __( 'Custom User Category' ),
			);
		 
			$args = array(
				'hierarchical'      => true,
				'labels'            => $labels,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_rest' 		=> true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'user_category' ),
			);
		 
			register_taxonomy( 'user_category', array( 'custom_user' ), $args );

			$labels = array(
				'name' => _x( 'Custom User Tags', 'taxonomy general name' ),
				'singular_name' => _x( 'Custom User Tags', 'taxonomy singular name' ),
				'search_items' =>  __( 'Search Custom User Tags' ),
				'popular_items' => __( 'Popular Custom User Tags' ),
				'all_items' => __( 'All Custom User Tags' ),
				'parent_item' => null,
				'parent_item_colon' => null,
				'edit_item' => __( 'Edit Tag' ), 
				'update_item' => __( 'Update Tag' ),
				'add_new_item' => __( 'Add New Tag' ),
				'new_item_name' => __( 'New Tag Name' ),
				'separate_items_with_commas' => __( 'Separate tags with commas' ),
				'add_or_remove_items' => __( 'Add or remove tags' ),
				'choose_from_most_used' => __( 'Choose from the most used tags' ),
				'menu_name' => __( 'Custom User Tags' ),
			  ); 
			
			  register_taxonomy('user_tag',array( 'custom_user' ),array(
				'hierarchical' => false,
				'labels' => $labels,
				'show_ui' => true,
				'query_var' => true,
				'show_in_rest' 		=> true,
				'rewrite' => array( 'slug' => 'user_tag' ),
			  ));

		}

		/**
		 * Register the Custom Metabox for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function custom_metabox(){
			// add_meta_box('custom_user_email', 'User Email', array( $this, 'custom_user_email_html' ), 'custom_user','normal');
			// add_meta_box('custom_user_dob', 'User Date Of Birth', array( $this, 'custom_user_dob_html' ), 'custom_user','normal');
			// add_meta_box('custom_user_address', 'User Adress', array( $this, 'custom_user_address_html' ), 'custom_user','normal');
			// add_meta_box('custom_user_postal', 'User PostCode', array( $this, 'custom_user_postal_html' ), 'custom_user','normal');
			// add_meta_box('custom_user_skills', 'User Skills', array( $this, 'custom_user_skills_html' ), 'custom_user','normal');
			// add_meta_box('custom_user_hobby', 'User Hobbies', array( $this, 'custom_user_hobby_html' ), 'custom_user','normal');
			// add_meta_box('custom_user_first_name', 'User First Name', array( $this, 'custom_user_first_name_html' ), 'custom_user','normal');
			// add_meta_box('custom_user_lastname_name', 'User Last Name', array( $this, 'custom_user_lastname_name_html' ), 'custom_user','normal');
			// add_meta_box('custom_user_ratings', 'User Ratings ( Out Of 5 )', array( $this, 'custom_user_ratings_html' ), 'custom_user','normal');
			// add_meta_box('custom_user_password', 'User password', array( $this, 'custom_user_password_html' ), 'custom_user','normal');
			add_meta_box('custom_user_details', 'User Details', array( $this, 'custom_user_details_html' ), 'custom_user','normal');
		}
		
		public function custom_user_details_html($post){
			$user_password = get_post_meta( $post->ID,  'custom_user_password', true );
			$first_name = get_post_meta( $post->ID,  'custom_user_first_name', true );
			$last_name = get_post_meta( $post->ID,  'custom_user_lastname_name', true );
			$email = get_post_meta( $post->ID,  'custom_user_email', true );
			$dob = get_post_meta( $post->ID,  'custom_user_dob', true );
			$add = get_post_meta( $post->ID,  'custom_user_address', true );
			$add2 = get_post_meta( $post->ID,  'custom_user_address_two', true );
			$postal = get_post_meta( $post->ID,  'custom_user_postal', true );
			$skills = get_post_meta( $post->ID,  'custom_user_skills', true );
			$hobby = get_post_meta( $post->ID,  'custom_user_hobby', true );
			$ratings = get_post_meta( $post->ID,  'custom_user_ratings', true );
			?>
				<div class="custom_user_field--wrapper">
					<label for="custom_user_passwordfield" class="custom_meta_notes">User Password</label>
					<input type="text" value="<?php echo esc_attr($user_password)?>" placeholder="Password" name="custom_user_passwordfield" id="custom_user_passwordfield" class="custom_user_passwordfield--text" require>
				</div>
				<div class="custom_user_field--wrapper">
					<label for="custom_user_first_namefield" class="custom_meta_notes">First Name</label>
					<input type="text" placeholder="First Name" value="<?php echo esc_attr($first_name)?>" name="custom_user_first_namefield" id="custom_user_first_namefield" class="custom_user_first_namefield--text" require>
				</div>
				<div class="custom_user_field--wrapper">
					<label for="custom_user_lastname_namefield" class="custom_meta_notes">Last Name</label>
					<input type="text" placeholder="Last Name" value="<?php echo esc_attr($last_name)?>" name="custom_user_lastname_namefield" id="custom_user_lastname_namefield" class="custom_user_lastname_namefield--text" require>
				</div>
				<div class="custom_user_field--wrapper">
					<label for="custom_user_emailfield" class="custom_meta_notes">User Email</label>
					<input type="email" placeholder="Email Adress" value="<?php echo esc_attr($email)?>" name="custom_user_emailfield" id="custom_user_emailfield" class="custom_user_emailfield--text" require>
				</div>
				<div class="custom_user_field--wrapper">
					<label for="custom_user_dobfield" class="custom_meta_notes">User DOB</label>
					<input type="date" id="custom_user_dobfield" value="<?php echo esc_attr($dob)?>" name="custom_user_dobfield" class="user_input custom_user_dobfield--text" require>
				</div>
				<div class="custom_user_field--wrapper">
					<label for="custom_user_addressfield" class="custom_meta_notes">Adress 1</label>
					<input type="text" id="custom_user_addressfield" value="<?php echo esc_attr($add)?>" name="custom_user_addressfield" class="user_input custom_user_addressfield--text" placeholder="Adress 1">
					<label for="custom_user_addressfieldtwo" class="custom_meta_notes">Adress 2</label>
					<input type="text" id="custom_user_addressfieldtwo" value="<?php echo esc_attr($add2)?>" name="custom_user_addressfieldtwo" class="user_input custom_user_addressfield--text" placeholder="Adress 2">
				</div>
				<div class="custom_user_field--wrapper">
					<label for="custom_user_postalfield" class="custom_meta_notes">User Postal Code</label>
					<input type="number" pattern="[0-9]*" value="<?php echo esc_attr($postal)?>" placeholder="Postal Code" name="custom_user_postalfield" id="custom_user_postalfield" name="custom_user_postalfield" class="user_input custom_user_postalfield--text" >
				</div>
				<div class="custom_user_field--wrapper">
					<label for="custom_user_skillsfield" class="custom_meta_notes">Please enter your new skills seperated by ","</label>
					<input id="custom_user_skillsfield" value="<?php echo esc_attr($skills)?>" class="user_input custom_user_skillsfield--text" name="custom_user_skillsfield"/>
				</div>
				<div class="custom_user_field--wrapper">
					<label for="custom_user_hobbyfield" class="custom_meta_notes">Please enter your new hobbies seperated by " "</label>
					<input id="custom_user_hobbyfield" value="<?php echo esc_attr($hobby)?>" class="user_input custom_user_hobbyfield--text" name="custom_user_hobbyfield"/>
				</div>
				<div class="custom_user_field--wrapper">
					<label for="custom_user_ratingsfield" class="custom_meta_notes">User Ratings</label>
					<input type="number" placeholder="Ratings for users" value="<?php echo esc_attr($ratings)?>" name="custom_user_ratingsfield" id="custom_user_ratingsfield" class="custom_user_ratingsfield--text" max="5" min="1" require>
				</div>
			<?php
			wp_nonce_field( 'create_user_details_action', 'create_user_details', );
		}

		public function custom_meta_box_saver($post_id){

			if(isset($_POST) && !empty($_POST)){
				if ( ! isset( $_POST['create_user_details'] ) || ! wp_verify_nonce( $_POST['create_user_details'], 'create_user_details_action' )) {
					exit;
				} else {
				
					if(isset($_POST["custom_user_emailfield"])):
						update_post_meta($post_id, 'custom_user_email', sanitize_email ( $_POST["custom_user_emailfield"]) );
					endif;
					if(isset($_POST["custom_user_dobfield"])):
						update_post_meta($post_id, 'custom_user_dob', sanitize_text_field( $_POST["custom_user_dobfield"]) );
					endif;
					if(isset($_POST["custom_user_addressfield"])):
						update_post_meta($post_id, 'custom_user_address', sanitize_text_field( $_POST["custom_user_addressfield"]) );
					endif;
					if(isset($_POST["custom_user_addressfieldtwo"])):
						update_post_meta($post_id, 'custom_user_address_two', sanitize_text_field( $_POST["custom_user_addressfieldtwo"]) );
					endif;
					if(isset($_POST["custom_user_postalfield"])):
						update_post_meta($post_id, 'custom_user_postal', sanitize_text_field( $_POST["custom_user_postalfield"]) );
					endif;
					if(isset($_POST["custom_user_skillsfield"])):
						update_post_meta($post_id, 'custom_user_skills', sanitize_text_field($_POST["custom_user_skillsfield"]) );
					endif;
					if(isset($_POST["custom_user_hobbyfield"])):
						update_post_meta($post_id, 'custom_user_hobby', sanitize_text_field( $_POST["custom_user_hobbyfield"]) );
					endif;
					if(isset($_POST["custom_user_ratingsfield"])):
						update_post_meta($post_id, 'custom_user_ratings', sanitize_text_field( $_POST["custom_user_ratingsfield"]) );
					endif;
					
				}
			}
		}
		
		public function manage_custom_user_posts_columns($columns) {
			unset($columns['date']);
			unset($columns['title']);
			unset($columns['taxonomy-user_category']);
			unset($columns['comments']);
			return array_merge($columns, array(
				'title' => __('Name'),
				'email' => __('Email'),
				'custom_user_ratings' => __('Ratings'),
				'custom_user_dob' => __('Date of birth'),
				'taxonomy-user_category' => __('Category'),
				'date' => __('Inquiry Date'),
			));
		}

		
		public function adding_custom_user_posts_columns_data($columns) {
			global $post;
			switch ($columns) {
				case 'email':
					echo esc_html__(get_post_meta($post->ID, 'custom_user_email', true));
					break;
				case 'custom_user_ratings':
					echo esc_html__(get_post_meta($post->ID, 'custom_user_ratings', true));
					break;
				case 'custom_user_dob':
					echo esc_html__(get_post_meta($post->ID, 'custom_user_dob', true));
					break;
			}
		}

		public function cu_add_page_template_to_dropdown( $templates ) {
			$templates[plugin_dir_path( __FILE__ ) . 'templates/template-post-registration.php'] = __( 'Registration form template', 'text-domain' );
			$templates[plugin_dir_path( __FILE__ ) . 'templates/template-post-login.php'] = __( 'Login form template', 'text-domain' );
			
			return $templates;
		}

		public function cu_change_page_template($template) {
			if (is_page()) {
				$meta = get_post_meta(get_the_ID());

				if (!empty($meta['_wp_page_template'][0]) && $meta['_wp_page_template'][0] !== $template) {
					$template = $meta['_wp_page_template'][0];
				}
			}

			if(isset( $_GET['post_type']) && !empty( $_GET['post_type'] )) {
				if( $_GET['post_type'] === "user_category" ){
					$theme_files = 'archive-custom_user.php';
					$exists_in_theme = locate_template($theme_files, false);
					
					if ( $exists_in_theme !== '' ) {
					  return $exists_in_theme;
					} else {
					  return plugin_dir_path(__FILE__) . 'templates/archive-custom_user.php';
					}
				}
			}

			return $template;
		}

		public function my_custom_single_template($single) {

			global $post;
		
			/* Checks for single template by post type */
			if ( $post->post_type == 'custom_user' ) {
				if ( file_exists( plugin_dir_path( __FILE__ ) . 'templates/single-custom_user.php' ) ) {
					return plugin_dir_path( __FILE__ ) . 'templates/single-custom_user.php';
				}
			}
		
			return $single;
		
		}

		public function wp_page_template( $page_template ) {
			if ( is_page( 'Login' ) ) {
				$page_template = plugin_dir_path( __FILE__ ) . 'templates/template-post-login.php';
			} 
			if( is_page('Register') ){
				$page_template = plugin_dir_path( __FILE__ ) . 'templates/template-post-registration.php';
			}
			return $page_template;
		}
	}

	if( is_admin() ) {
		$Custom_User_Insertion_Admin = new Custom_User_Insertion_Admin;
	}

}
