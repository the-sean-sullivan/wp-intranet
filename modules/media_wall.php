<?php

/**
 * The media wall module.
 *
 * @package    Srs_Intranet
 * @subpackage Srs_Intranet/modules
 * @author     Sean Sullivan <me@seanrsullivan.com>
 */

include_once SRS_FILE_PATH . '/admin/class-srs-intranet-admin.php';
include_once SRS_FILE_PATH . '/public/class-srs-intranet-public.php';

$active = Srs_Intranet_Admin::activate_modules( 'media_wall' );

if ( $active == 1 ) :

	class Srs_Intranet_Media_Wall {

		/*------------------------------------*\
		    Create Pages
		\*------------------------------------*/

		/**
		 * Add "nice name" for module
		 *
		 * @since 4.1.0
		 */
		static function nice_name() {
			return 'Media Wall';
		}

		/**
		 * Creates the media wall dashboard page.
		 *
		 * @since 1.0.0
		 */
		function create_media_wall_dashboard() {
			Srs_Intranet_Admin::create_pages('Media Wall', 'media-wall', 'dashboard');
		}

		/**
		 * Creates the view our sites page.
		 *
		 * @since 1.0.0
		 */
		function create_things_we_made() {
			Srs_Intranet_Admin::create_pages('Things We Made', 'things-we-made', 'media-wall', 'dashboard');
		}

		/**
		 * Creates the view inpirational sites page.
		 *
		 * @since 1.0.0
		 */
		function create_things_we_love() {
			Srs_Intranet_Admin::create_pages('Things We Love', 'things-we-love', 'media-wall', 'dashboard');
		}


		/*------------------------------------*\
		    Assign Templates
		\*------------------------------------*/

		/**
		 * Assigns custom template to media wall dashboard page.
		 *
		 * @since 1.0.0
		 */
		function media_wall_dashboard_template( $page_template ) {
		    if ( is_page( 'media-wall' ) )
		        $page_template = SRS_FILE_PATH . '/public/templates/media-wall-dashboard.php';

		    return $page_template;
		}

		/**
		 * Assigns custom template to media wall "Things We..." pages.
		 *
		 * @since 1.0.0
		 */
		function media_wall_sites_template( $page_template ) {
		    if ( is_page( array('things-we-love', 'things-we-made') ) )
		        $page_template = SRS_FILE_PATH . '/public/templates/media-wall-sites.php';

		    return $page_template;
		}

	}

	/*------------------------------------*\
	    ACF Fields
	\*------------------------------------*/


	/**
	 * Adds custom fields to both custom post types
	 *
	 * Removed Welcome Screen in 3.0.0
	 *
	 * @since 1.0.0
	 */
	if( function_exists('acf_add_local_field_group') ):

		acf_add_local_field_group(array (
			'key'    => 'group_55cb524650d4c',
			'title'  => 'Media Wall',
			'fields' => array (
				array (
					'sub_fields' => array (
						array (
							'default_value'     => '',
							'placeholder'       => '',
							'key'               => 'field_55cb5262d94e5',
							'label'             => 'URL',
							'name'              => 'site_url',
							'type'              => 'url',
							'instructions'      => '',
							'required'          => 1,
							'conditional_logic' => 0,
							'wrapper'           => array (
								'width' => 75,
								'class' => '',
								'id'    => '',
							),
						),
						array (
							'default_value'     => 1,
							'message'           => 'Make active in rotation.',
							'ui'                => 0,
							'ui_on_text'        => '',
							'ui_off_text'       => '',
							'key'               => 'field_55cb52c6d94e6',
							'label'             => 'Active',
							'name'              => 'site_active',
							'type'              => 'true_false',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array (
								'width' => 25,
								'class' => '',
								'id'    => '',
							),
						),
					),
					'min'               => 0,
					'max'               => 0,
					'layout'            => 'table',
					'button_label'      => 'Add Site',
					'collapsed'         => '',
					'key'               => 'field_55cb524ed94e4',
					'label'             => 'Sites',
					'name'              => 'sites',
					'type'              => 'repeater',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array (
						'width' => '',
						'class' => '',
						'id'    => '',
					),
				),
			),
			'location' => array (
				array (
					array (
						'param'    => 'page',
						'operator' => '==',
						'value'    => '38',
					),
				),
				array (
					array (
						'param'    => 'page',
						'operator' => '==',
						'value'    => '39',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'acf_after_title',
			'style'                 => 'seamless',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => array (
				0  => 'permalink',
				1  => 'excerpt',
				2  => 'custom_fields',
				3  => 'discussion',
				4  => 'comments',
				5  => 'revisions',
				6  => 'slug',
				7  => 'author',
				8  => 'format',
				9  => 'page_attributes',
				10 => 'featured_image',
				11 => 'categories',
				12 => 'tags',
				13 => 'send-trackbacks',
			),
			'active'      => 1,
			'description' => '',
		));

		endif;

else :

	class Srs_Intranet_Media_Wall {

		/**
		 * Updated dashboard to "draft" when module is inactive.
		 *
		 * @since 3.0.0
		 */
		public static function create_media_wall_dashboard() {
			Srs_Intranet_Admin::update_pages('media-wall', 'dashboard');
		}

		public static function create_things_we_made() {
			Srs_Intranet_Admin::update_pages('things-we-made', 'media-wall', 'dashboard');
		}

		public static function create_things_we_love() {
			Srs_Intranet_Admin::update_pages('things-we-love', 'media-wall', 'dashboard');
		}

	}

endif;
