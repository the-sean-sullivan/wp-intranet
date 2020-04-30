<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://seansdesign.net
 * @since      3.0.1
 *
 * @package    Srs_Intranet
 * @subpackage Srs_Intranet/includes
 */

class Srs_Intranet {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    3.0.0
	 * @access   protected
	 * @var      Srs_Intranet_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    3.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    3.0.0
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
	 * @since    3.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'srs-intranet';
		$this->version = '3.0.0';

		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    3.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/* ===== Core Stuff ===== */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-srs-intranet-loader.php'; // Actions/Filters loader
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-srs-intranet-admin.php'; // Functions for Admin area
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-srs-intranet-public.php'; // Functions for Public area
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-srs-intranet-login.php'; // Login functions
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-srs-intranet-dash-tools.php'; // Dashboard tools
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-srs-intranet-activator.php'; // Activation functions

		/* ===== Modules ===== */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'modules/forms.php'; // Forms
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'modules/applicants.php'; // Applicants
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'modules/careers.php'; // Careers
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'modules/media_wall.php'; // Media Wall
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'modules/naming_contest.php'; // Naming Contest
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'modules/office_map.php'; // Office Map
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'modules/comment_box.php'; // OneTeam Suggestion Box
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'modules/out_list.php'; // Out List
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'modules/people.php'; // People
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'modules/pto_request.php'; // PTO Request
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'modules/vendor_database.php'; // Vendors
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'modules/welcome_screen.php'; // Welcome Screen

		$this->loader = new Srs_Intranet_Loader();

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    3.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Srs_Intranet_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_active = new Srs_Intranet_Activator( $this->get_plugin_name(), $this->get_version() );

		/* ====== Admin ====== */

		// Actions
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' ); // Add menu item
		$this->loader->add_action( 'wp_login_failed', $plugin_admin, 'login_failed' ); // Redirect failed login to custom login page
		// $this->loader->add_action( 'wp_ajax_nopriv_lost_pass', $plugin_admin, 'lost_pass_callback' ); // Lost passwords (logged out)
		// $this->loader->add_action( 'wp_ajax_lost_pass', $plugin_admin, 'lost_pass_callback' ); // Lost passwords (logged in)
		$this->loader->add_action( 'init', $plugin_admin, 'dont_show_dash' ); // Restrict access to WP Dash to only Admins
		$this->loader->add_action( 'init', $plugin_admin, 'redirect_login_page' ); // Redirect all users to WP login page to custom login page
		$this->loader->add_action( 'wp_ajax_nopriv_update_user', $plugin_admin, 'update_user' ); // Update User
		$this->loader->add_action( 'wp_ajax_update_user', $plugin_admin, 'update_user' ); // Update User
		$this->loader->add_action( 'wp_ajax_nopriv_update_modules', $plugin_admin, 'update_modules' ); // Update User
		$this->loader->add_action( 'wp_ajax_update_modules', $plugin_admin, 'update_modules' ); // Update User
		$this->loader->add_action( 'init', $plugin_admin, 'register_dash_nav'); // Add Dash Nav

		/* ====== Activate ====== */

		// Filters
		$this->loader->add_filter( 'page_template', $plugin_active, 'dash_page_template' );
		$this->loader->add_filter( 'page_template', $plugin_active, 'login_page_template' );
		$this->loader->add_filter( 'page_template', $plugin_active, 'insuf_perm_page_template' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    3.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public         = new Srs_Intranet_Public( $this->get_plugin_name(), $this->get_version() );
		$plugin_applicants     = new Srs_Intranet_Applicants( $this->get_plugin_name(), $this->get_version() );
		$plugin_careers        = new Srs_Intranet_Careers( $this->get_plugin_name(), $this->get_version() );
		$plugin_dash_tools     = new Srs_Intranet_Dash_Tools( $this->get_plugin_name(), $this->get_version() );
		$plugin_forms          = new Srs_Intranet_Forms( $this->get_plugin_name(), $this->get_version() );
		$plugin_login          = new Srs_Intranet_Login( $this->get_plugin_name(), $this->get_version() );
		$plugin_media_wall     = new Srs_Intranet_Media_Wall( $this->get_plugin_name(), $this->get_version() );
		$plugin_naming_contest = new Srs_Intranet_Naming_Contest( $this->get_plugin_name(), $this->get_version() );
		$plugin_office_map     = new Srs_Intranet_Office_Map( $this->get_plugin_name(), $this->get_version() );
		$plugin_comment_form   = new Srs_Intranet_Comment_Box( $this->get_plugin_name(), $this->get_version() );
		$plugin_out_list       = new Srs_Intranet_Out_List( $this->get_plugin_name(), $this->get_version() );
		$plugin_people         = new Srs_Intranet_People( $this->get_plugin_name(), $this->get_version() );
		$plugin_vacation       = new Srs_Intranet_Pto_Request( $this->get_plugin_name(), $this->get_version() );
		$plugin_vendors        = new Srs_Intranet_Vendor_Database( $this->get_plugin_name(), $this->get_version() );
		$plugin_welcome        = new Srs_Intranet_Welcome_Screen( $this->get_plugin_name(), $this->get_version() );

		/* ====== Public (General) ====== */

		// Actions
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_public, 'output_buffer' ); // Allow redirection
		$this->loader->add_action( 'init', $plugin_public, 'session_start', 1); // Sessions
		$this->loader->add_action( 'wp_logout', $plugin_public, 'dash_logout'); // Redirect logout to login
		$this->loader->add_action( 'wp_ajax_infinite_scroll', $plugin_public, 'infinite_scroll'); // Infinite scroll for logged in user
		$this->loader->add_action( 'wp_ajax_nopriv_infinite_scroll', $plugin_public, 'infinite_scroll'); // Infinite scroll for not logged in user
		$this->loader->add_action( 'wp_ajax_load_search_results', $plugin_public, 'load_search_results'); // Live search for logged in user
		$this->loader->add_action( 'wp_ajax_nopriv_load_search_results', $plugin_public, 'load_search_results'); // Live search for not logged in user
		$this->loader->add_action( 'after_setup_theme', $plugin_public, 'remove_admin_bar'); // Live search for not logged in user

		// Filters
		$this->loader->add_filter( 'rest_api_init', $plugin_public, 'rest_only_for_authorized_users', 99 );


		/* ====== Applicants ====== */

		// Actions
		if ( method_exists($plugin_applicants, 'applicants_post_type') ) $this->loader->add_action( 'init', $plugin_applicants, 'applicants_post_type' ); // Custom post type
		if ( method_exists($plugin_applicants, 'applicant_dept_taxonomies') ) $this->loader->add_action( 'init', $plugin_applicants, 'applicant_dept_taxonomies', 0 );
		if ( method_exists($plugin_applicants, 'applicant_taxonomies') ) $this->loader->add_action( 'init', $plugin_applicants, 'applicant_taxonomies' ); // Custom post type
		$this->loader->add_action( 'init', $plugin_applicants, 'create_applicants_dashboard' ); // Creates/updates forms dash

		// Filters
		if ( method_exists($plugin_applicants, 'applicants_dashboard_template') ) $this->loader->add_filter( 'page_template', $plugin_applicants, 'applicants_dashboard_template' ); // Dashboard template
		if ( method_exists($plugin_applicants, 'applicants_single_template') ) $this->loader->add_filter( 'single_template', $plugin_applicants, 'applicants_single_template'); // Single page template

		// Remove Actions
		$this->loader->remove_action( 'wp_head', $plugin_applicants, 'adjacent_posts_rel_link_wp_head', 10, 0);


		/* ====== Careers ====== */

		// Actions
		if ( method_exists($plugin_careers, 'careers_post_type') ) $this->loader->add_action( 'init', $plugin_careers, 'careers_post_type' ); // Custom post type
		if ( method_exists($plugin_careers, 'careers_taxonomies') ) $this->loader->add_action( 'init', $plugin_careers, 'careers_taxonomies', 0 );
		$this->loader->add_action( 'init', $plugin_careers, 'create_careers_dashboard' ); // Creates/updates forms dash
		$this->loader->add_action( 'init', $plugin_careers, 'create_careers_edit' ); // Creates/updates forms dash

		// Filters
		if ( method_exists($plugin_careers, 'careers_dashboard_template') ) $this->loader->add_filter( 'page_template', $plugin_careers, 'careers_dashboard_template' ); // Dashboard template
		if ( method_exists($plugin_careers, 'careers_edit_template') ) $this->loader->add_filter( 'page_template', $plugin_careers, 'careers_edit_template' ); // Edit template
		if ( method_exists($plugin_careers, 'careers_single_template') ) $this->loader->add_filter( 'single_template', $plugin_careers, 'careers_single_template'); // Single page template


		/* ====== Dashboard Tools ====== */

		// Actions
		$this->loader->add_action( 'init', $plugin_dash_tools, 'events_post_type' ); // Custom post type
		$this->loader->add_action( 'init', $plugin_dash_tools, 'dash_events_taxonomies', 0 ); // Event taxonomies


		/* ====== Forms ====== */

		// Actions
		if ( method_exists($plugin_forms, 'forms_post_type') ) $this->loader->add_action( 'init', $plugin_forms, 'forms_post_type' ); // Custom post type
		$this->loader->add_action( 'init', $plugin_forms, 'create_forms_dashboard' ); // Creates/updates forms dash

		// Filters
		if ( method_exists($plugin_forms, 'forms_dashboard_template') ) $this->loader->add_filter( 'page_template', $plugin_forms, 'forms_dashboard_template' ); // Dashboard template


		/* ====== Login ====== */

		// Actions
		$this->loader->add_action( 'wp_login_failed', $plugin_login, 'login_failed' ); // Failed login
		$this->loader->add_action( 'wp_ajax_nopriv_lost_pass', $plugin_login, 'lost_pass_callback' ); // Lost password
		$this->loader->add_action( 'wp_ajax_lost_pass', $plugin_login, 'lost_pass_callback' ); // Lost password
		$this->loader->add_action( 'wp_ajax_nopriv_reset_pass', $plugin_login, 'reset_pass_callback' ); // Reset password
		$this->loader->add_action( 'wp_ajax_reset_pass', $plugin_login, 'reset_pass_callback' ); // Reset password
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_login, 'enqueue_login_script' ); // Enqueue Ajax Script

		// Filters
		$this->loader->add_filter( 'authenticate', $plugin_login, 'verify_username_password', 1, 3); // Verfiy username/pass


		/* ====== Media Wall ====== */

		// Actions
		$this->loader->add_action( 'init', $plugin_media_wall, 'create_media_wall_dashboard' ); // Media Wall dash
		$this->loader->add_action( 'init', $plugin_media_wall, 'create_things_we_made' ); // Things We Made page
		$this->loader->add_action( 'init', $plugin_media_wall, 'create_things_we_love' ); // Things We Love page

		// Filters
		if ( method_exists($plugin_media_wall, 'media_wall_dashboard_template') ) $this->loader->add_filter( 'page_template', $plugin_media_wall, 'media_wall_dashboard_template' ); // Dash template
		if ( method_exists($plugin_media_wall, 'media_wall_sites_template') ) $this->loader->add_filter( 'page_template', $plugin_media_wall, 'media_wall_sites_template' ); // Sites template

		/* ====== Naming Contest ====== */

		// Actions
		if ( method_exists($plugin_naming_contest, 'naming_contest_post_type') ) $this->loader->add_action( 'init', $plugin_naming_contest, 'naming_contest_post_type' ); // Custom post type
		$this->loader->add_action( 'init', $plugin_naming_contest, 'create_naming_contest_dashboard' ); // Creates/updates forms dash
		$this->loader->add_action( 'init', $plugin_naming_contest, 'create_naming_contest' ); // Creates/updates forms

		// Filters
		if ( method_exists($plugin_naming_contest, 'naming_contest_dashboard_template') ) $this->loader->add_filter( 'page_template', $plugin_naming_contest, 'naming_contest_dashboard_template' ); // Dashboard template
		if ( method_exists($plugin_naming_contest, 'naming_contest_template') ) $this->loader->add_filter( 'page_template', $plugin_naming_contest, 'naming_contest_template' ); // Form template


		/* ====== Office Map ====== */

		// Actions
		$this->loader->add_action( 'init', $plugin_office_map, 'create_map_dashboard' ); // Create Map page

		// Filters
		if ( method_exists($plugin_office_map, 'map_template') ) $this->loader->add_filter( 'page_template', $plugin_office_map, 'map_template' ); // Map page template


		/* ====== OneTeam Suggestion Box ====== */

		// Actions
		if ( method_exists($plugin_comment_form, 'comment_form_post_type') ) $this->loader->add_action( 'init', $plugin_comment_form, 'comment_form_post_type' ); // Custom post type
		$this->loader->add_action( 'init', $plugin_comment_form, 'create_comment_form_dashboard' ); // Creates/updates forms dash
		$this->loader->add_action( 'init', $plugin_comment_form, 'create_comment_form' ); // Creates/updates forms

		// Filters
		if ( method_exists($plugin_comment_form, 'comment_form_dashboard_template') ) $this->loader->add_filter( 'page_template', $plugin_comment_form, 'comment_form_dashboard_template' ); // Dashboard template
		if ( method_exists($plugin_comment_form, 'comment_form_template') ) $this->loader->add_filter( 'page_template', $plugin_comment_form, 'comment_form_template' ); // Form template
		if ( method_exists($plugin_comment_form, 'comment_form_single_template') ) $this->loader->add_filter( 'single_template', $plugin_comment_form, 'comment_form_single_template'); // Single page template


		/* ====== Out List ====== */

		// Actions
		if ( method_exists($plugin_out_list, 'out_list_post_type') ) $this->loader->add_action( 'init', $plugin_out_list, 'out_list_post_type' ); // Create Out List custom post type
		if ( method_exists($plugin_out_list, 'register_remove_past_dates') ) $this->loader->add_action( 'wp', $plugin_out_list, 'register_remove_past_dates' ); // Auto delete past dates
		$this->loader->add_action( 'init', $plugin_out_list, 'create_outlist_dashboard' ); // Create Out List dash
		$this->loader->add_action( 'expired_post_delete', $plugin_out_list, 'remove_past_dates' ); // Auto delete past dates

		// Filters
		if ( method_exists($plugin_out_list, 'outlist_template') ) $this->loader->add_filter( 'page_template', $plugin_out_list, 'outlist_template' ); // Out List dash template
		if ( method_exists($plugin_out_list, 'outlist_single_template') ) $this->loader->add_filter( 'single_template', $plugin_out_list, 'outlist_single_template' ); // Out List singe template


		/* ===== People ===== */

		// Actions
		if ( method_exists($plugin_people, 'people_post_type') ) $this->loader->add_action( 'init', $plugin_people, 'people_post_type' ); // Create People custom post type
		$this->loader->add_action( 'init', $plugin_people, 'create_people_dashboard' ); // Create People dash page
		$this->loader->add_action( 'init', $plugin_people, 'create_review_dashboard' ); // Create Performance Review page
		if ( method_exists($plugin_people, 'add_user_page') ) $this->loader->add_action( 'user_register', $plugin_people, 'add_user_page'); // Create page when new user is added

		// Filters
		if ( method_exists($plugin_people, 'people_dashboard_template') ) $this->loader->add_filter( 'page_template', $plugin_people, 'people_dashboard_template' ); // People dash template
		if ( method_exists($plugin_people, 'review_template') ) $this->loader->add_filter( 'page_template', $plugin_people, 'review_template' ); // Performance Review template


		/* ===== Vendors ===== */

		// Actions
		if ( method_exists($plugin_vendors, 'vendor_post_type') ) $this->loader->add_action( 'init', $plugin_vendors, 'vendor_post_type' );
		if ( method_exists($plugin_vendors, 'vendors_cat_taxonomies') ) $this->loader->add_action( 'init', $plugin_vendors, 'vendors_cat_taxonomies', 0 );
		if ( method_exists($plugin_vendors, 'vendors_taxonomies') ) $this->loader->add_action( 'init', $plugin_vendors, 'vendors_taxonomies', 0 );
		if ( method_exists($plugin_vendors, 'create_vendors_dashboard') ) $this->loader->add_action( 'init', $plugin_vendors, 'create_vendors_dashboard' ); // Create People dash page
		if ( method_exists($plugin_vendors, 'create_vendors_add_edit_page') ) $this->loader->add_action( 'init', $plugin_vendors, 'create_vendors_add_edit_page' ); // Create Map
		$this->loader->add_action('wp_ajax_save_vendor_comment', $plugin_vendors, 'save_vendor_comment');
		$this->loader->add_action('wp_ajax_nopriv_save_vendor_comment', $plugin_vendors, 'save_vendor_comment');
		$this->loader->add_action('wp_ajax_delete_vendor_comment', $plugin_vendors, 'delete_vendor_comment');
		$this->loader->add_action('wp_ajax_nopriv_delete_vendor_comment', $plugin_vendors, 'delete_vendor_comment');
		$this->loader->add_action('wp_ajax_add_lightbox', $plugin_vendors, 'add_lightbox');
		$this->loader->add_action('wp_ajax_nopriv_add_lightbox', $plugin_vendors, 'add_lightbox');
		$this->loader->add_action('wp_ajax_remove_lightbox', $plugin_vendors, 'remove_lightbox');
		$this->loader->add_action('wp_ajax_nopriv_remove_lightbox', $plugin_vendors, 'remove_lightbox');
		$this->loader->add_action('wp_ajax_delete_lightbox', $plugin_vendors, 'delete_lightbox');
		$this->loader->add_action('wp_ajax_nopriv_delete_lightbox', $plugin_vendors, 'delete_lightbox');

		// Filters
		if ( method_exists($plugin_vendors, 'vendors_dashboard_template') ) $this->loader->add_filter( 'page_template', $plugin_vendors, 'vendors_dashboard_template' );
		if ( method_exists($plugin_vendors, 'vendors_add_edit_page_template') ) $this->loader->add_filter( 'page_template', $plugin_vendors, 'vendors_add_edit_page_template' );
		if ( method_exists($plugin_vendors, 'vendors_single_template') ) $this->loader->add_filter('single_template', $plugin_vendors, 'vendors_single_template');


		/* ===== Vacation Request ===== */

		// Actions
		$this->loader->add_action( 'init', $plugin_vacation, 'create_vacation_dashboard' ); // Create Vacation dash

		// Filters
		if ( method_exists($plugin_vacation, 'vacation_template') ) $this->loader->add_filter( 'page_template', $plugin_vacation, 'vacation_template' ); // Vacation dash template


		/* ===== Welcome Screen ===== */

		// Actions
		$this->loader->add_action( 'init', $plugin_welcome, 'create_welcome_dashboard' ); // Welcome dash
		$this->loader->add_action( 'init', $plugin_welcome, 'create_welcome_view' ); // Welcome Wiew page

		// Filters
		if ( method_exists($plugin_welcome, 'welcome_dash_template') ) $this->loader->add_filter( 'page_template', $plugin_welcome, 'welcome_dash_template' ); // Welcome dash template
		if ( method_exists($plugin_welcome, 'welcome_screen_template') ) $this->loader->add_filter( 'page_template', $plugin_welcome, 'welcome_screen_template' ); // Welcome view template

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    3.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     3.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     3.0.0
	 * @return    Srs_Intranet_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     3.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
