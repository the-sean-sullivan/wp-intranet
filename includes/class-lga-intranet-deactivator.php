<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://seansdesign.net
 * @since      3.0.0
 *
 * @package    Srs_Intranet
 * @subpackage Srs_Intranet/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      3.0.0
 * @package    Srs_Intranet
 * @subpackage Srs_Intranet/includes
 * @author     Sean Sullivan <me@seanrsullivan.com>
 */
class Srs_Intranet_Deactivator {

	/**
	 * Flushes rewrite rules on deactivation.
	 *
	 * @since    3.0.0
	 */
	public static function deactivate() {
		flush_rewrite_rules();
	}

}
