<?php

/**
 * The office map module.
 *
 * @package    Srs_Intranet
 * @subpackage Srs_Intranet/modules
 * @author     Sean Sullivan <me@seanrsullivan.com>
 */

include_once SRS_FILE_PATH . '/admin/class-srs-intranet-admin.php';
include_once SRS_FILE_PATH . '/public/class-srs-intranet-public.php';

$active = Srs_Intranet_Admin::activate_modules( 'office_map' );

if ( $active == 1 ) :

	class Srs_Intranet_Office_Map {

		/**
		 * Add "nice name" for module
		 *
		 * @since 4.1.0
		 */
		static function nice_name() {
			return 'Office Map';
		}

		/**
		 * Creates the Office Map page.
		 *
		 * @since 1.0.0
		 */
		function create_map_dashboard() {
			Srs_Intranet_Admin::create_pages('Office Map', 'office-map', 'people', 'dashboard');
		}

		/**
		 * Assigns custom template to office map.
		 *
		 * @since 1.0.0
		 */
		function map_template( $page_template ) {
			if ( is_page( 'office-map' ) )
		        $page_template = SRS_FILE_PATH . '/public/templates/office-map.php';

		    return $page_template;
		}

	}

else :

	class Srs_Intranet_Office_Map {

		/**
		 * Updated dashboard to "draft" when module is inactive.
		 *
		 * @since 3.0.0
		 */
		public static function create_map_dashboard() {
			Srs_Intranet_Admin::update_pages('office-map', 'people', 'dashboard');
		}

	}

endif;
