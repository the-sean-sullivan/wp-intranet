<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://seansdesign.net
 * @since      1.0.0
 *
 * @package    Srs_Intranet
 * @subpackage Srs_Intranet/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Srs_Intranet
 * @subpackage Srs_Intranet/public
 * @author     Sean Sullivan <me@seanrsullivan.com>
 */
class Srs_Intranet_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/frontend.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name . '-plugins', plugin_dir_url( __FILE__ ) . 'js/plugins.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name . '-main', plugin_dir_url( __FILE__ ) . 'js/main.js', array( 'jquery' ), $this->version, true );
	}

	/**
	 * Renders the settings page (HTML & junk).
	 *
	 * @since    3.0.0
	 */
	public function display_plugin_setup_page() {
	    include_once SRS_FILE_PATH . '/admin/partials/srs-intranet-admin-display.php';
	}

	/**
	 * Removed admin bar for logged in users.
	 *
	 * @since 3.0.0
	 */
	function remove_admin_bar() {
		show_admin_bar( false );
	}

	/**
	 * Allow sessions to carry over ridirect link
	 *
	 * @since 2.1.5
	 */
	public function session_start() {
	    if( !session_id() ) session_start();
	}

	/**
	 * Allow redirection, even if my theme starts to send output to the browser
	 *
	 * @since 2.1.3
	 */
	public function output_buffer() {
	    ob_start();
	}

	/*------------------------------------*\
	    Get Post/Page Info
	\*------------------------------------*/


	/**
	 * Checks out pages by their IDs
	 *
	 * Added parent & top level variables to function in 3.0.0
	 *
	 * @since 2.0.0
	 */
	public static function page_exists_by_slug( $page_slug, $parent_slug = '', $top_level_slug = '') {
	    if ( $top_level_slug ) $page = get_page_by_path( $top_level_slug . '/' . $parent_slug . '/' . $page_slug );
	    elseif ( $parent_slug ) $page = get_page_by_path( $parent_slug . '/' . $page_slug );
	    else $page = get_page_by_path( $page_slug );

	    if ($page) return $page->post_name;
	    else return null;
	}


	/**
	 * Checks out pages by their IDs
	 *
	 * Added parent & top level variables to function in 3.0.0
	 *
	 * @since 2.0.0
	 */
	public static function get_id_by_slug( $page_slug = '', $parent_slug = '', $top_level_slug = '') {
		if ( $page_slug ) :
		    if ( $top_level_slug ) $page = get_page_by_path( $top_level_slug . '/' . $parent_slug . '/' . $page_slug );
		    elseif ( $parent_slug ) $page = get_page_by_path( $parent_slug . '/' . $page_slug );
		    else $page = get_page_by_path( $page_slug );
		else :
			if ( $top_level_slug ) $page = get_page_by_path( $top_level_slug . '/' . $parent_slug );
		    elseif ( $parent_slug ) $page = get_page_by_path( $parent_slug );
		endif;

	    if ($page) return $page->ID;
	    else return null;
	}



	/**
	 * Get the custom post ID by slug & post type
	 *
	 * @since 2.1.2
	 */
	public static function get_post_id( $slug, $post_type ) {
	    $query = new WP_Query(
	        array(
	            'name'      => $slug,
	            'post_type' => $post_type
	        )
	    );

	    if ( $query->have_posts() ) return $query->posts[0]->ID;
	    else return 0;
	}

	/**
	 * If page has parent, by slug
	 *
	 * @since 2.0.1
	 */
	public function is_tree( $pid ) {
	    global $post;

	    $pid = $this->get_id_by_slug( $pid );

	    if( is_page( $pid ) || in_array( $pid, $post->ancestors ) ) return true;
	    else return false;
	}

	/**
	 * Checks to see if the page has children
	 *
	 * @since 2.1.4
	 */
	public static function is_child( $parent = '' ) {
	    global $post;

	    $parent_obj = get_page( $post->post_parent, ARRAY_A );
	    $parent = (string) $parent;
	    $parent_array = (array) $parent;

	    if ( in_array( (string) $parent_obj['ID'], $parent_array ) ) return true;
	    elseif ( in_array( (string) $parent_obj['post_title'], $parent_array ) ) return true;
	    elseif ( in_array( (string) $parent_obj['post_name'], $parent_array ) ) return true;
	    else return false;
	}

	/**
	 * Redirect logout to login page
	 *
	 * @since 2.0.1
	 */
	function dash_logout(){
	    wp_redirect( site_url('/dashboard') ); exit();
	}

	/*------------------------------------*\
	    Permissions
	\*------------------------------------*/


	/**
	 * Check user permissions on front end.
	 *
	 * @since 3.0.0
	 */
	public static function user_permissions( $module ) {

		global $wpdb;

		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;

		$db_table = Srs_Intranet_Activator::register_permission_table();

	    $permissions = $wpdb->get_results( $wpdb->prepare("SELECT $module FROM $db_table WHERE user_id = %d", $user_id) );
	    foreach ( $permissions as $permission ) :
	    	$allow = $permission->$module;
	    endforeach;

	    return $allow;

	}


	/*------------------------------------*\
	    Navigation
	\*------------------------------------*/

	/**
	 * Main menu
	 *
	 * Added in the ability to add all possible links and removed HTML from frontend in 4.0.0
	 * Updated module select to only choose active modules in 3.0.1
	 * Removed widgets and updated to dynamic in 3.0.0
	 * Updated to use widgets in 2.0.0
	 *
	 * @since 1.1.0
	 */
	public static function main_menu( $top_links = array(), $post_links = array() ) {

		global $wpdb;

		$module_table     = Srs_Intranet_Activator::register_module_table();
		$permission_table = Srs_Intranet_Activator::register_permission_table();

		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;

		$modules = $wpdb->get_results ("SELECT * FROM $module_table WHERE active = 1");

		$menu ='<ul>';

			// If pre-links
			if ( $top_links ) :

				foreach ($top_links as $top_link) :
					$menu .= '<li><a href="' . $top_link['url'] . '">' . $top_link['title'] . '</a></li>';
				endforeach;

			endif;

			// Module links
			foreach ( $modules as $module ) :
				$the_module = $module->name;

				$permissions = $wpdb->get_results ( $wpdb->prepare ("SELECT $the_module FROM $permission_table WHERE user_id = %d AND $the_module = 1", $user_id) );

				foreach ( $permissions as $permission ) :
					$cols = $wpdb->get_col_info();
					$col = $cols[0];
					$module_info = $wpdb->get_results ( $wpdb->prepare ("SELECT name, nice_name FROM $module_table WHERE name = %s", $col) );
					foreach ($module_info as $mod_info) :
						$module_url = str_replace('_', '-', $mod_info->name);
						$module_name = $mod_info->nice_name;

						$menu .= '<li><a href="' . get_bloginfo("url") . '/dashboard/' . $module_url . '">' . $module_name . '</a></li>';
					endforeach;
				endforeach;
			endforeach;

			// If post links
			if ( $post_links ) :

				foreach ($post_links as $post_link) :
					$menu .= '<li><a href="' . $post_link['url'] . '">' . $post_link['title'] . '</a></li>';
				endforeach;

			endif;

		$menu .= '</ul>';

		return $menu;

	}

	/**
	 * Menu icons
	 *
	 * @since 1.1.0
	 */
	public static function main_menu_icons( $module, $icon ) {
		return ( array( $module, $icon ) );
	}


	/*------------------------------------*\
	    Helper Functions
	\*------------------------------------*/

	/**
	 * Converts display name to URL friendly slug
	 *
	 * @since 2.2.0
	 */
	public static function user_to_slug( $user_name ) {
	    $user_slug = preg_replace('/^-+|-+$/', '', strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $user_name)));
	    return $user_slug;
	}

	/**
	 * Finds email from display name
	 *
	 * @since 2.2.0
	 */
	public static function name_to_email( $user_name ) {
	    global $wpdb;
	    $user = $wpdb->get_results ( $wpdb->prepare ("SELECT user_email FROM $wpdb->users WHERE display_name = %s", $user_name) );
	    $user_email = $user[0]->user_email;
	    return $user_email;
	}

	/**
	 * Count page views.
	 *
	 * Moved to here from Applicants in 4.1.0
	 *
	 * @since 1.2.1
	 */
	public static function set_post_views($postID) {
	    $curr_user = get_current_user_id();
	    $count_key = 'post_views_count';
	    $count = get_post_meta($postID, $count_key, true);
	    if( $count == '' ) :

	        $count = 0;
	        delete_post_meta($postID, $count_key);
	        add_post_meta($postID, $count_key, '0');

	    else :

	        if ( is_single() && $curr_user !== 1 )
	            $count++; update_post_meta($postID, $count_key, $count);

	    endif;

	    return $count;
	}

	/*------------------------------------*\
	    Fancy Front End Interactions
	\*------------------------------------*/

	/**
	 * Infinite Scroll to main dash page.
	 *
	 * Added variable for loop file in 3.0.0
	 *
	 * @since 1.2.0
	 */
	function infinite_scroll(){
	    $paged      = $_POST['page_no'];
	    $meta_key   = $_POST['meta_key'];
	    $meta_value = $_POST['meta_value'];
	    $loop_file  = $_POST['loop_file'];

	    // Load the posts
	    include 'templates/' . $loop_file . '.php';

	    exit;
	}

	/**
	 * Live search results
	 *
	 * Added variable for loop file in 3.0.0
	 *
	 * @since 1.0.0
	 */
	public function load_search_results() {
	    if ( ! isset( $_POST['search'] ) ) exit;

	    define('SHORTINIT', true);

		$query     = $_POST['search'];
		$loop_file = $_POST['loop_file'];

		if ( isset( $_POST['view'] ) ) $view = $_POST['view'];

	    include 'templates/' . $loop_file . '.php';

	    exit;
	}

	/*------------------------------------*\
	    REST API Stuff
	\*------------------------------------*/

	/**
	 * Only show the REST API for logged in users
	 *
	 * @since 4.0.0
	 */
	public function rest_only_for_authorized_users( $wp_rest_server ) {
		if( !is_user_logged_in() ) wp_die('Sorry, you are not allowed to access this data.', 'Require Authentication', 403);
	}

	/**
	 * GET REST API endpoints
	 *
	 * @since 4.0.0
	 */
	static function rest_api_get( $endpoint, $method = 'GET' ) {
		list( $username, $password ) = SRS_Intranet_Public::rest_api_login();
		$wp_request_headers = array( 'Authorization' => 'Basic ' . base64_encode( $username . ':' . $password ) );

		$wp_request_url = 'https://WEBSITE.com/wp-json/wp/v2/' . $endpoint;
		$wp_post_response = wp_remote_request(
			$wp_request_url,
			array(
				'method'    => $method,
				'headers'   => $wp_request_headers
			)
		);

		if ( wp_remote_retrieve_response_code( $wp_post_response ) == 403 ) return false;

		$rest_api = wp_remote_retrieve_body( $wp_post_response );
		return json_decode($rest_api, true);
	}

	/**
	 * Privatize username and password
	 *
	 * @since 4.0.0
	 */
	private static function rest_api_login() {
    	$username = 'USERNAME';
	    $password = 'PASSWORD';

	    return array( $username, $password );
    }

}
