<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://seansdesign.net
 * @since      3.0.0
 *
 * @package    Srs_Intranet
 * @subpackage Srs_Intranet/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Srs_Intranet
 * @subpackage Srs_Intranet/admin
 * @author     Sean Sullivan <me@seanrsullivan.com>
 */

include_once SRS_FILE_PATH . '/includes/class-srs-intranet-activator.php';

class Srs_Intranet_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    3.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    3.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    3.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/srs-intranet-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/srs-intranet-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Adds the settings page.
	 *
	 * Added sub-menu links in 3.0.0
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
		add_menu_page('Intranet', 'Intranet', 'activate_plugins', $this->plugin_name, array($this, 'display_plugin_setup_page'), 'dashicons-admin-multisite'); // The page
		add_submenu_page($this->plugin_name, 'Intranet', 'User Permissions', 'activate_plugins', $this->plugin_name, array($this, 'display_plugin_setup_page') ); // Link to Permissions
		add_submenu_page($this->plugin_name, 'Intranet', 'Modules', 'activate_plugins', $this->plugin_name . '&tab=modules', array($this, 'display_plugin_setup_page') ); // Link to Modules
	}

	/**
	 * Renders the settings page (HTML & junk).
	 *
	 * @since    3.0.0
	 */
	public function display_plugin_setup_page() {
	    include_once( 'partials/srs-intranet-admin-display.php' );
	}

	/**
	 * Gets list of all users from WP
	 *
	 * @since 1.0.0
	 */
	public static function get_the_users() {

	    $user_args = array(
	        'blog_id'      => $GLOBALS['blog_id'],
	        'orderby'      => 'display_name',
	        'order'        => 'ASC',
	        'count_total'  => false,
	        'fields'       => 'all'
	    );
	    $users = get_users($user_args);

	    return $users;
	}

	/**
	 * Get the file list of modules to create list
	 *
	 * @since 3.0.0
	 */
	public static function module_list() {
	    $files = array_slice(scandir( SRS_FILE_PATH . '/modules/'), 2);
	    $files = str_replace('.php', '', $files);
	    return $files;
	}


	/**
	 * Restrict access to WP Dash to only Admins
	 *
	 * Updated function to actually work in 3.0.1
	 *
	 * @since 1.1.0
	 */
	public function dont_show_dash() {
	   	if( is_admin() && !defined('DOING_AJAX') && ( current_user_can('subscriber') || current_user_can('editor') ) ) :
  	       	wp_redirect( site_url( '/dashboard' ) ); exit;
	    endif;
	}


	/**
	 * Redirect all users from WP login page to custom login page
	 *
	 * @since 2.1.3
	 */
	public function redirect_login_page() {
	    $login_page  = home_url( '/login/' );
	    $page_viewed = basename($_SERVER['REQUEST_URI']);

	    if( $page_viewed == "wp-login.php" && $_SERVER['REQUEST_METHOD'] == 'GET') :
	        wp_redirect($login_page); exit;
	    endif;
	}


	/**
	 * Updates database with user permissions
	 *
	 * Added check all ability in 3.0.2
	 * Added $wpdb->prepare to Update query in 3.0.1
	 * Updated function to user AJAX in 3.0.0
	 * Array to auto add module columns added in version 2.0.0
	 * Failure notice added in version 1.1.0
	 *
	 * @since 1.0.0
	 */
	public static function update_user() {

	    global $wpdb;

	    $db_table = Srs_Intranet_Activator::register_permission_table();

	    $module = $_POST['module'];
	   	$user_id = ( $_POST['user_id'] == 'undefined' ) ? '' : $_POST['user_id'];
	    $perm = $_POST['perm'];

	    // If has user ID then update user otherwise update entire column
	    if ( $user_id !== '' )
	    	$updated = $wpdb->query( $wpdb->prepare ( "UPDATE $db_table SET $module = %d WHERE user_id = %d", $perm, $user_id) ) or die(mysql_error());
	    else
	    	$updated = $wpdb->query( $wpdb->prepare ( "UPDATE $db_table SET $module = %d", $perm) ) or die(mysql_error());

	}

	/**
	 * Updates permission table with modules
	 *
	 * Added $wpdb->prepare to Update query in 3.0.1
	 *
	 * @since 3.0.0
	 */
	public static function update_permission_modules() {

		global $wpdb;

	    $db_table = Srs_Intranet_Activator::register_permission_table();

	    $modules = Srs_Intranet_Admin::module_list();

	    foreach ( $modules as $module ) :
	    	$current_modules = $wpdb->get_results ("SELECT * FROM $db_table");
		    if ( !$current_modules ) :
		    	$wpdb->query( $wpdb->prepare("ALTER TABLE $db_table ADD %s INT(1) NOT NULL DEFAULT 0", $module) );
		   	endif;
	    endforeach;

	}

	/**
	 * Retrieve list of active modiules
	 *
	 * Added "nice name" to return array in 4.1.0
	 *
	 * @since 3.0.1
	 */
	public static function active_modules() {

	    global $wpdb;

	    $db_table = Srs_Intranet_Activator::register_module_table();

	    $activated = $wpdb->get_results ("SELECT * FROM $db_table WHERE active = 1") or die(mysql_error());

		foreach ( $activated as $active ) :
			$active_modules[] = array( $active->name, $active->nice_name );
		endforeach;

		return $active_modules;
	}

	/**
	 * Updates module table with active/not active
	 *
	 * Added $wpdb->prepare to Update query in 3.0.1
	 *
	 * @since 3.0.0
	 */
	public static function update_modules() {

	    global $wpdb;

	    $db_table = Srs_Intranet_Activator::register_module_table();

	    $module = $_POST['module'];
	    $active = $_POST['active'];

	    $updated = $wpdb->query( $wpdb->prepare ( "UPDATE $db_table SET active = %d WHERE name = %s", $active, $module ) ) or die(mysql_error());
	}

	/**
	 * Turn on/off modules
	 *
	 * Added $wpdb->prepare to Update query in 3.0.1
	 *
	 * @since 3.0.0
	 */
	public static function activate_modules( $module ) {

	    global $wpdb;

	    $db_table = Srs_Intranet_Activator::register_module_table();

	    $modules = $wpdb->get_results( $wpdb->prepare ( "SELECT * FROM $db_table WHERE name = %s", $module) );
	    foreach ( $modules as $module ) :
	    	$active_state = $module->active;
	    endforeach;
	    return $active_state;

	}

	/**
	 * Set up the dash nav
	 *
	 * @since 3.0.0
	 */
	function dash_nav() {
		wp_nav_menu(
			array(
				'theme_location'  => 'nav-dash',
				'menu'            => 'nav_dash',
				'container'       => 'div',
				'container_class' => 'menu-{menu slug}-container',
				'container_id'    => '',
				'menu_class'      => 'menu',
				'menu_id'         => '',
				'echo'            => true,
				'fallback_cb'     => 'wp_page_menu',
				'before'          => '',
				'after'           => '',
				'link_before'     => '',
				'link_after'      => '',
				'items_wrap'      => '<ul>%3$s</ul>',
				'depth'           => 1,
				'walker'          => ''
			)
		);
	}

	/**
	 * Register dashboard nav
	 *
	 * @since 3.0.0
	 */
	function register_dash_nav() {
	    register_nav_menus(array( 'nav-dash' => 'Intranet Navigation', ));
	}


	/*------------------------------------*\
	    Helper Functions
	\*------------------------------------*/

	/**
	 * Helper function to create/update module pages
	 *
	 * @since 3.0.0
	 */
	public static function create_pages($page_title, $page_slug, $parent_slug = '', $top_level_slug = '') {

		$slug = Srs_Intranet_Public::page_exists_by_slug( $page_slug, $parent_slug, $top_level_slug );

	    if ( $slug ) :

	    	$dash_id = Srs_Intranet_Public::get_id_by_slug( $page_slug, $parent_slug, $top_level_slug );
	    	$activate = array(
				'ID'          => $dash_id,
				'post_status' => 'publish'
		  	);
			wp_update_post( $activate );

	    else :

	    	$dash_id = Srs_Intranet_Public::get_id_by_slug( '', $parent_slug, $top_level_slug );
	        $the_dash = array(
	            'post_title'   => $page_title,
	            'post_name'    => $page_slug,
	            'post_content' => '',
	            'post_status'  => 'publish',
	            'post_type'    => 'page',
	            'post_parent'  => $dash_id
	        );
	        wp_insert_post($the_dash);

	    endif;
	}

	/**
	 * Helper function to update page on module deactivation
	 *
	 * @since 3.0.0
	 */
	public static function update_pages($page_slug, $parent_slug = '', $top_level_slug = '') {

		$slug = Srs_Intranet_Public::page_exists_by_slug( $page_slug, $parent_slug, $top_level_slug );

	    if ( $slug ) :
			// If not active, make pages "draft".
			$dash_id = Srs_Intranet_Public::get_id_by_slug( $page_slug, $parent_slug, $top_level_slug );
			$deactivate = array(
				'ID'          => $dash_id,
				'post_status' => 'draft'
		  	);
			wp_update_post( $deactivate );
		endif;
	}

	/**
	 * Helper function to create custom post types
	 *
	 * @since 1.0.0
	 */
	public static function custom_post_types( $post_name, $singular, $slug, $menu_position = 4, $menu_icon, $show_in_rest = false, $comments = '') {
	    $labels = array(
	        'name'               => _x($post_name, 'post type general name'),
	        'singular_name'      => _x($post_name, 'post type singular name'),
	        'add_new'            => _x('Add New', $slug),
	        'add_new_item'       => __('Add New ' . $singular),
	        'edit_item'          => __('Edit ' . $singular),
	        'new_item'           => __('New ' . $singular),
	        'all_items'          => __('All ' . $post_name),
	        'view_item'          => __('View ' . $singular),
	        'search_items'       => __('Search ' . $post_name),
	        'not_found'          => __('No ' . $post_name . ' found'),
	        'not_found_in_trash' => __('No ' . $post_name . ' found in Trash'),
	        'parent_item_colon'  => '',
	        'menu_name'          => __($post_name)
	    );

	    $args = array(
			'labels'                => $labels,
			'public'                => true,
			'menu_icon'             => $menu_icon,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_nav_menus'     => true,
			'show_in_menu'          => true,
			'query_var'             => true,
			'rewrite'               => true,
			'capability_type'       => 'page',
			'has_archive'           => true,
			'hierarchical'          => true,
			'menu_position'         => 4,
			'show_in_nav_menus'     => true,
			'show_in_rest'          => $show_in_rest,
			'rest_base'             => $slug,
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'rewrite'               => array( 'slug' => $slug, 'with_front' => false ),
			'supports'              => array( 'title', 'editor', 'author', 'thumbnail', 'page-attributes', 'revisions', $comments )
	      );
	      register_post_type( $slug, $args );
	}

	/**
	 * Helper function to regiuster taxonomies
	 *
	 * @since 1.0.0
	 */
	public static function register_taxonomies( $tax_name, $tax_slug, $post_type, $tax_type = true ) {
	    register_taxonomy( $tax_slug,
	    	array ( 0 => $post_type, ),
	    	array(
				'hierarchical'   => $tax_type,
				'label'          => $tax_name,
				'show_ui'        => true,
				'query_var'      => true,
				'rewrite'        => array( 'slug' => $tax_slug ),
				'singular_label' => $tax_name
			)
    	);
	}


}
