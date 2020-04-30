<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://seansdesign.net
 * @since             1.0.0
 * @package           Srs_Intranet
 *
 * @wordpress-plugin
 * Plugin Name:       Intranet
 * Plugin URI:        https://seansdesign.net
 * Description:       This is an intranet/modular permissions plugin for WordPress.
 * Version:           4.2.0
 * Author:            Sean Sullivan
 * Author URI:        https://seansdesign.net
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       srs-intranet
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) die;

define('SRS_FOLDER', dirname(plugin_basename(__FILE__)));
define('SRS_URL', get_bloginfo("url") . '/wp-content/plugins/' . SRS_FOLDER);
define('SRS_FILE_PATH', dirname(__FILE__));
define('SRS_DIR_NAME', basename(SRS_FILE_PATH));

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-srs-intranet-activator.php
 */
function activate_srs_intranet() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-srs-intranet-activator.php';
	Srs_Intranet_Activator::create_permission_table();
	Srs_Intranet_Activator::create_module_table();
	Srs_Intranet_Activator::insert_users();
	Srs_Intranet_Activator::insert_modules();
	Srs_Intranet_Activator::create_dashboard();
	Srs_Intranet_Activator::create_login();
	Srs_Intranet_Activator::insufficient_perm();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-srs-intranet-deactivator.php
 */
function deactivate_srs_intranet() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-srs-intranet-deactivator.php';
	Srs_Intranet_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_srs_intranet' );
register_deactivation_hook( __FILE__, 'deactivate_srs_intranet' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-srs-intranet.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_srs_intranet() {

	$plugin = new Srs_Intranet();
	$plugin->run();

}
run_srs_intranet();
