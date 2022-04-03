<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Custom_User_Insertion
 * @subpackage Custom_User_Insertion/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Custom_User_Insertion
 * @subpackage Custom_User_Insertion/includes
 * @author     Hilay Trivedi <hilay.trivedi@multidos.com>
 */
class Custom_User_Insertion {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Custom_User_Insertion_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'CUSTOM_USER_INSERTION_VERSION' ) ) {
			$this->version = CUSTOM_USER_INSERTION_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'custom-user-insertion';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Custom_User_Insertion_Loader. Orchestrates the hooks of the plugin.
	 * - Custom_User_Insertion_i18n. Defines internationalization functionality.
	 * - Custom_User_Insertion_Admin. Defines all hooks for the admin area.
	 * - Custom_User_Insertion_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-custom-user-insertion-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-custom-user-insertion-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-custom-user-insertion-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-custom-user-insertion-public.php';

		$this->loader = new Custom_User_Insertion_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Custom_User_Insertion_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Custom_User_Insertion_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Custom_User_Insertion_Admin( $this->get_plugin_name(), $this->get_version() );

		// Actions
		$this->loader->add_action( 'init', $plugin_admin, 'custom_posts' );
		$this->loader->add_action( 'init', $plugin_admin, 'custom_taxonomy' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'custom_user_admin_menu' );
		$this->loader->add_action('admin_init', $plugin_admin, 'custom_user_skills');
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'custom_metabox' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'custom_meta_box_saver' );
		$this->loader->add_action('manage_custom_user_posts_custom_column', $plugin_admin, 'adding_custom_user_posts_columns_data', 10, 2);

		// Filters
		$this->loader->add_filter( 'theme_page_templates', $plugin_admin, 'cu_add_page_template_to_dropdown' );
		$this->loader->add_filter('template_include', $plugin_admin, 'cu_change_page_template');
		$this->loader->add_filter('template_include', $plugin_admin, 'cu_archive_funciton');
		$this->loader->add_filter( 'manage_custom_user_posts_columns', $plugin_admin, 'manage_custom_user_posts_columns');

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Custom_User_Insertion_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_ajax_custom_search_listing_data', $plugin_public,'custom_search_listing_data_callback');
		$this->loader->add_action( 'wp_ajax_custom_user_insertion_form', $plugin_public,'custom_user_insertion_form_callback');
		$this->loader->add_action( 'wp_ajax_nopriv_custom_user_insertion_form', $plugin_public, 'custom_user_insertion_form' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Custom_User_Insertion_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
