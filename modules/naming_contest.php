<?php

/**
 * The naming contest thingy.
 *
 * @package    Srs_Intranet
 * @subpackage Srs_Intranet/modules
 * @author     Sean Sullivan <me@seanrsullivan.com>
 */

include_once SRS_FILE_PATH . '/admin/class-srs-intranet-admin.php';
include_once SRS_FILE_PATH . '/public/class-srs-intranet-public.php';

$active = Srs_Intranet_Admin::activate_modules( 'naming_contest' );

if ( $active == 1 ) :

	class Srs_Intranet_Naming_Contest{

		/**
		 * Add "nice name" for module
		 *
		 * @since 4.2.0
		 */
		static function nice_name() {
			return 'Naming Contest';
		}

		/**
		 * Register Custom Post Type - Naming Contest
		 *
		 * @since 4.2.0
		 */
		function naming_contest_post_type() {
			Srs_Intranet_Admin::custom_post_types( 'Naming Contest', 'Comment', 'naming-contest', 50, 'dashicons-editor-help', false, 'comments');
		}

		/**
		 * Creates the naming contest dashboard page.
		 *
		 * @since 4.2.0
		 */
		function create_naming_contest_dashboard() {
		    Srs_Intranet_Admin::create_pages('Naming Contest Entries', 'naming-contest-dashboard', 'dashboard');
		}

		/**
		 * Assigns custom template to naming contest dashboard.
		 *
		 * @since 4.2.0
		 */
		function naming_contest_dashboard_template( $page_template ) {
			if ( is_page( 'naming-contest-dashboard' ) )
		        $page_template = SRS_FILE_PATH . '/public/templates/naming-contest-dashboard.php';

		    return $page_template;
		}

		/**
		 * Creates the naming contest entries form page.
		 *
		 * @since 4.2.0
		 */
		function create_naming_contest() {
		    Srs_Intranet_Admin::create_pages('Naming Contest Entry', 'naming-contest', 'dashboard');
		}

		/**
		 * Assigns custom template to OneTeam suggestion box form.
		 *
		 * @since 4.2.0
		 */
		function naming_contest_template( $page_template ) {
			if ( is_page( 'naming-contest' ) )
		        $page_template = SRS_FILE_PATH . '/public/templates/naming-contest.php';

		    return $page_template;
		}

	}


	/**
	 * Adds custom fields to both custom post types
	 *
	 * @since 4.2.0
	 */
	if( function_exists('acf_add_local_field_group') ):

		acf_add_local_field_group(array(
			'key' => 'group_5e271a3111e34',
			'title' => 'Naming Contest',
			'fields' => array(
				array(
					'key' => 'field_5e271a3b01d86',
					'label' => 'Entry',
					'name' => 'entry',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'naming-contest',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
		));

	endif;

else :

	class Srs_Intranet_Naming_Contest{

		/**
		 * Updated dashboard to "draft" when module is inactive.
		 *
		 * @since 4.2.0
		 */
		public static function create_naming_contest_dashboard() {
			Srs_Intranet_Admin::update_pages('naming-contest-dashboard', 'dashboard');
		}

		/**
		 * Updated form to "draft" when module is inactive.
		 *
		 * @since 4.2.0
		 */
		public static function create_naming_contest() {
		    Srs_Intranet_Admin::update_pages('naming-contest', 'dashboard');
		}

	}

endif;
