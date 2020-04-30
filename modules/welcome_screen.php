<?php

/**
 * The out list module.
 *
 * @package    Srs_Intranet
 * @subpackage Srs_Intranet/modules
 * @author     Sean Sullivan <me@seanrsullivan.com>
 */

include_once SRS_FILE_PATH . '/admin/class-srs-intranet-admin.php';
include_once SRS_FILE_PATH . '/public/class-srs-intranet-public.php';

$active = Srs_Intranet_Admin::activate_modules( 'welcome_screen' );

if ( $active == 1 ) :

	class Srs_Intranet_Welcome_Screen {

		/**
		 * Add "nice name" for module
		 *
		 * @since 4.1.0
		 */
		static function nice_name() {
			return 'Welcome Screen';
		}

		/*------------------------------------*\
		    Create Pages
		\*------------------------------------*/

		/**
		 * Creates the welcome screen dashboard page.
		 *
		 * @since 1.0.0
		 */
		function create_welcome_dashboard() {
			Srs_Intranet_Admin::create_pages('Welcome Screen - Edit', 'welcome-screen', 'dashboard');
		}

		/**
		 * Creates the welcome screen page.
		 *
		 * @since 1.0.0
		 */
		function create_welcome_view() {
			Srs_Intranet_Admin::create_pages('Welcome Screen', 'welcome-view', 'welcome-screen', 'dashboard');
		}

		/*------------------------------------*\
		    Assign Templates
		\*------------------------------------*/

		/**
		 * Assigns custom template to welcome screen dashboard page.
		 *
		 * @since 1.0.0
		 */
		function welcome_dash_template( $page_template ) {
		    if ( is_page( 'welcome-screen' ) )
		        $page_template = SRS_FILE_PATH . '/public/templates/welcome-screen-dashboard.php';

		    return $page_template;
		}

		/**
		 * Assigns custom template to welcome screen dashboard page.
		 *
		 * @since 1.0.0
		 */
		function welcome_screen_template( $page_template ) {
		    if ( is_page( 'welcome-view' ) )
		        $page_template = SRS_FILE_PATH . '/public/templates/welcome-screen-view.php';

		    return $page_template;
		}

	}

	/**
	 * Adds custom fields to both custom post types
	 *
	 * @since 1.0.0
	 */
	if( function_exists('acf_add_local_field_group') ):

		acf_add_local_field_group(array (
			'key'    => 'group_5628f51fc1ba3',
			'title'  => 'Welcome Screen',
			'fields' => array (
				array (
					'sub_fields' => array (
						array (
							'default_value'     => '',
							'maxlength'         => '',
							'placeholder'       => '',
							'prepend'           => '',
							'append'            => '',
							'key'               => 'field_5628f541d88c8',
							'label'             => 'Visitor Group Name',
							'name'              => 'visitor_group_name',
							'type'              => 'text',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array (
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'readonly' => 0,
							'disabled' => 0,
						),
						array (
							'default_value'     => '',
							'new_lines'         => 'br',
							'maxlength'         => '',
							'placeholder'       => '',
							'rows'              => '',
							'key'               => 'field_5628f55dd88c9',
							'label'             => 'Individuals',
							'name'              => 'individuals',
							'type'              => 'textarea',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array (
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'readonly' => 0,
							'disabled' => 0,
						),
						array (
							'default_value'     => 0,
							'message'           => '',
							'ui'                => 0,
							'ui_on_text'        => '',
							'ui_off_text'       => '',
							'key'               => 'field_56290d886e695',
							'label'             => 'Make Active',
							'name'              => 'active',
							'type'              => 'true_false',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array (
								'width' => '15%',
								'class' => '',
								'id'    => '',
							),
						),
					),
					'min'               => 0,
					'max'               => 0,
					'layout'            => 'table',
					'button_label'      => 'Add Visitor',
					'collapsed'         => '',
					'key'               => 'field_5628f525d88c7',
					'label'             => 'Visitors',
					'name'              => 'visitors',
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
						'value'    => '51',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => array (
				0  => 'excerpt',
				1  => 'custom_fields',
				2  => 'discussion',
				3  => 'comments',
				4  => 'revisions',
				5  => 'slug',
				6  => 'author',
				7  => 'format',
				8  => 'page_attributes',
				9  => 'featured_image',
				10 => 'categories',
				11 => 'tags',
				12 => 'send-trackbacks',
			),
			'active'      => 1,
			'description' => '',
		));

	endif;

else :

	class Srs_Intranet_Welcome_Screen {

		/**
		 * Updated dashboard to "draft" when module is inactive.
		 *
		 * @since 3.0.0
		 */
		public static function create_welcome_dashboard() {
			Srs_Intranet_Admin::update_pages('welcome-edit', 'dashboard');
		}

		public static function create_welcome_view() {
			Srs_Intranet_Admin::update_pages('welcome-screen', 'welcome-edit', 'dashboard');
		}

	}

endif;
