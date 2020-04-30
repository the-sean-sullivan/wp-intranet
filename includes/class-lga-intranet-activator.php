<?php

/**
 * Fired during plugin activation
 *
 * @link       https://seansdesign.net
 * @since      1.0.0
 *
 * @package    Srs_Intranet
 * @subpackage Srs_Intranet/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Srs_Intranet
 * @subpackage Srs_Intranet/includes
 * @author     Sean Sullivan <me@seanrsullivan.com>
 */

include_once SRS_FILE_PATH . '/admin/class-srs-intranet-admin.php';
include_once SRS_FILE_PATH . '/public/class-srs-intranet-public.php';

class Srs_Intranet_Activator {

	/**
	 * Global Table Name (Permissions)
	 *
	 * Apps table removed in 2.0.0
	 *
	 * @since 1.0.0
	 */
	public static function register_permission_table() {
	    $user_permissions_table = 'wp_srs_user_permissions';
	    return $user_permissions_table;
	}

	/**
	 * Global Table Name (Modules)
	 *
	 * @since 3.0.0
	 */
	public static function register_module_table() {
	    $module_table = 'wp_srs_modules';
	    return $module_table;
	}

	/**
	 * Creates the permissions table
	 *
	 * Array to auto add module columns added and app table removed in version 2.0.0
	 *
	 * @since 2.0.0
	 */
	public static function create_permission_table(){

		global $wpdb;
		global $charset_collate;

	    //Call this manually as we may have missed the init hook
	    $db_table = Srs_Intranet_Activator::register_permission_table();

	    // Adds modules as columns
	    $files = Srs_Intranet_Admin::module_list();

	    $modules = array();
	    $modules[] = 'user_id bigint(20) unsigned NOT NULL default "0"';
	    $modules[] = 'user_name varchar(255) NOT NULL default "0"';

	    foreach ( $files as $file ) :
	        $modules[] = $file . ' INT(1) NOT NULL default "0"';
	    endforeach;

	    $create_col = join(",", $modules);

	    // User Permissions Table
	    $sql_create_user_table = "CREATE TABLE IF NOT EXISTS $db_table (
	        $create_col,
	        PRIMARY KEY  (user_id)
	        ) $charset_collate; ";
	    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	    dbDelta($sql_create_user_table);
	}


	/**
	 * Inserts a user into the database
	 *
	 * Fixed headers already sent error in version 2.2.1
	 *
	 * @since 1.0.0
	 */
	public static function insert_users() {

	    global $wpdb;

	    $db_table = Srs_Intranet_Activator::register_permission_table();
	    $users = Srs_Intranet_Admin::get_the_users();

		// Get current list of users from plugin DB
		$current_users = $wpdb->get_results ("SELECT user_id, user_name FROM $db_table");
		$current = array();
	    foreach ( $current_users as $current_user ) :
	    	$current[] = $current_user->user_id;
	   	endforeach;

	    // Get current list of active WP users
		$data = array();
	    foreach ( $users as $user ) :
	    	$data[] = $user->ID;
	   	endforeach;

	    // Compare both arrays
	    $some_data = array_diff($data, $current);

	    // Get IDs and display names from the compared arrays
	    $final_data = array();
	    foreach ($some_data as $work_data) :
	    	$user_data = get_userdata( $work_data );
	    	$final_data[] = array( 'user_id' => $user_data->ID, 'user_name' => $user_data->display_name );
	    endforeach;

	    // Insert into DB
	    foreach ( $final_data as $the_data ) :
	    	$wpdb->insert( $db_table, $the_data );
	    endforeach;

	}


	/**
	 * Creates the modules table
	 *
	 * @since 3.0.0
	 */
	public static function create_module_table(){

		global $wpdb;
		global $charset_collate;

	    //Call this manually as we may have missed the init hook
	    $db_table = Srs_Intranet_Activator::register_module_table();

	    // User Permissions Table
	    $sql_create_user_table = "CREATE TABLE IF NOT EXISTS $db_table (
	        id INT(5) NOT NULL AUTO_INCREMENT,
	        name VARCHAR(255) NOT NULL,
	        nice_name VARCHAR(255) NOT NULL,
	        active VARCHAR(1) NOT NULL default 0,
	        PRIMARY KEY  (id)
	        ) $charset_collate; ";
	    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	    dbDelta($sql_create_user_table);
	}


	/**
	 * Inserts modules into database
	 *
	 * Added "nice name" update in version 4.1.0
	 *
	 * @since 3.0.0
	 */
	public static function insert_modules() {

	    global $wpdb;

	    $db_table = Srs_Intranet_Activator::register_module_table();

	    // Get current list of modules from plugin DB
		$current_modules = $wpdb->get_results ("SELECT id, name, nice_name FROM $db_table");
		$current = array();
	    foreach ( $current_modules as $current_module ) :
	    	$current[] = $current_module->name;
	    	$current_nice_name[] = $current_module->nice_name ?? $current_module->name;
	   	endforeach;

	   	$current = array_combine($current, $current_nice_name);

	    // Get current list of active WP modules
	    $files = Srs_Intranet_Admin::module_list();
	    $data = array();
	    foreach ( $files as $file ) :
	    	$data[] = $file;

	    	// Update filename to match PHP class name
	    	$class_name = str_replace('_', ' ', $file);
	    	$class_namer = ucwords($class_name);
	    	$class_named = str_replace(' ', '_', $class_namer);

	    	// Check if mod is active and add specified "nice name" if so, otherwise use "fixed" file name.
	    	$mod_active = Srs_Intranet_Admin::activate_modules( $file );
			if ( $mod_active == 1 ) $data_nice_name[] = call_user_func('Srs_Intranet_' . $class_named . '::nice_name');
			else $data_nice_name[] = $class_namer;
	   	endforeach;

	   	$data = array_combine($data, $data_nice_name);

	    $current_count = count($current);
	    $data_count = count($data);

	    if ( $current_count > $data_count ) :

	    	// DELETE

	    	// Compare both arrays
		    $some_data = array_diff($current, $data);

		    // Get IDs and display names from the compared arrays
		    $final_data = array();
		    foreach ($some_data as $key => $value) :
		    	$final_data[] = array( 'name' => $key, 'nice_name' => $value );
		    endforeach;

		    // Delete from DB
		    foreach ( $final_data as $the_data ) :
		    	$wpdb->delete( $db_table, $the_data );
		    endforeach;

	    else :

	    	// INSERT

	    	// Compare both arrays
		    $some_data = array_diff($data, $current);

			// Get IDs and display names from the compared arrays
		    $final_data = array(); $update_data = array();
		    foreach ($some_data as $key => $value) :
		    	$final_data[] = array( 'name' => $key, 'nice_name' => $value );
		    endforeach;

		    // Insert/update DB
		    foreach ( $final_data as $the_data ) :
		    	$exists = $wpdb->get_results($wpdb->prepare( "SELECT ID, name FROM $db_table WHERE name = %s", $key));
		    	echo '<pre>'; print_r($the_data); echo '</pre>';
		    	if ( empty($exists) ) :

		    		$wpdb->insert( $db_table, $the_data );

		    	else :
		    		$nice_name =  $the_data['nice_name'];
		    		$name =  $the_data['name'];
		    		// $wpdb->update("UPDATE $db_table SET nice_name = $nice_name WHERE name = $name");
		    		$wpdb->update($db_table, array('nice_name' => $nice_name, 'name' => $name), array( 'name' => $name ));

		    	endif;
			endforeach;

	    endif;
	}


	/**
	 * Creates the dashboard page.
	 *
	 * @since 1.1.0
	 */
	public static function create_dashboard() {
		Srs_Intranet_Admin::create_pages('Dashboard', 'dashboard');
	}


	/**
	 * Assigns custom template to dashboard page.
	 *
	 * Added login function to dashboard template in 2.0.0
	 *
	 * @since 1.1.0
	 */
	function dash_page_template( $page_template ) {
		if ( is_page( 'dashboard' ) ) :
		    $page_template = SRS_FILE_PATH . '/public/templates/dashboard.php';
		endif;

		return $page_template;
	}


	/**
	 * Creates the login page.
	 *
	 * @since 2.1.1
	 */
	public static function create_login() {
		Srs_Intranet_Admin::create_pages('Login', 'login');
	}

	/**
	 * Assigns custom template to login page.
	 *
	 * @since 2.1.1
	 */
	function login_page_template( $page_template ) {
		if ( is_page( 'login' ) ) :
		    $page_template = SRS_FILE_PATH . '/public/templates/login.php';
		endif;

		return $page_template;
	}


	/**
	 * Creates the insufficient permissions page.
	 *
	 * @since 2.1.1
	 */
	public static function insufficient_perm() {
		Srs_Intranet_Admin::create_pages('Insufficient Permissions', 'insufficient-permissions');
	}

	/**
	 * Assigns custom template to insufficient permissions page.
	 *
	 * @since 2.1.2
	 */
	function insuf_perm_page_template( $page_template ) {
		if ( is_page( 'insufficient-permissions' ) ) :
		    $page_template = SRS_FILE_PATH . '/public/templates/insuf-perm.php';
		endif;

		return $page_template;
	}


	/**
	 * Renders the settings page (HTML & junk).
	 *
	 * @since 3.0.0
	 */
	public function display_plugin_setup_page() {
	    include_once( 'partials/srs-intranet-admin-display.php' );
	}

}
