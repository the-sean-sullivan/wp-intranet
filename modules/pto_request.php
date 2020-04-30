<?php

/**
 * The PTO request module.
 *
 * @package    Srs_Intranet
 * @subpackage Srs_Intranet/modules
 * @author     Sean Sullivan <me@seanrsullivan.com>
 */

include_once SRS_FILE_PATH . '/admin/class-srs-intranet-admin.php';
include_once SRS_FILE_PATH . '/public/class-srs-intranet-public.php';

$active = Srs_Intranet_Admin::activate_modules( 'pto_request' );

if ( $active == 1 ) :

	class Srs_Intranet_Pto_Request{

		/**
		 * Add "nice name" for module
		 *
		 * @since 4.1.0
		 */
		static function nice_name() {
			return 'PTO Request';
		}

		/**
		 * Creates the Vacation Request page.
		 *
		 * @since 3.0.0
		 */
		function create_vacation_dashboard() {
		    Srs_Intranet_Admin::create_pages('PTO Request', 'pto-request', 'dashboard');
		}

		/**
		 * Assigns custom template to Vacation Request
		 *
		 * @since 3.0.0
		 */
		function vacation_template( $page_template ) {
			if ( is_page( 'pto-request' ) )
		        $page_template = SRS_FILE_PATH . '/public/templates/vacation-request.php';

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
			'key'    => 'group_57bf471b05dcd',
			'title'  => 'PTO Request',
			'fields' => array (
				array (
					'sub_fields' => array (
						array (
							'display_format'    => 'F j, Y',
							'return_format'     => 'm/d/Y',
							'first_day'         => 0,
							'key'               => 'field_57bf554cb1af5',
							'label'             => 'Dates',
							'name'              => 'dates',
							'type'              => 'date_picker',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array (
								'width' => '50',
								'class' => '',
								'id'    => '',
							),
						),
						array (
							'layout'  => 'vertical',
							'choices' => array (
								'full'  => 'Full Day',
								'half1' => 'Half Day (AM)',
								'half2' => 'Half Day (PM)',
							),
							'default_value'     => 'full',
							'other_choice'      => 0,
							'save_other_choice' => 0,
							'allow_null'        => 0,
							'return_format'     => 'value',
							'key'               => 'field_57bf55c7b1af6',
							'label'             => 'Type',
							'name'              => 'type',
							'type'              => 'radio',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array (
								'width' => '50',
								'class' => '',
								'id'    => '',
							),
						),
					),
					'min'               => 1,
					'max'               => 0,
					'layout'            => 'block',
					'button_label'      => 'Add Date',
					'collapsed'         => 'field_57bf554cb1af5',
					'key'               => 'field_57bf473b4e5d1',
					'label'             => 'Dates Requested',
					'name'              => 'dates_requested',
					'type'              => 'repeater',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array (
						'width' => '',
						'class' => 'pto-dates',
						'id'    => '',
					),
				),
			),
			'location' => array (
				array (
					array (
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'people',
					),
					array (
						'param'    => 'page_type',
						'operator' => '==',
						'value'    => 'child',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'seamless',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => array (
				0  => 'permalink',
				1  => 'the_content',
				2  => 'excerpt',
				3  => 'custom_fields',
				4  => 'discussion',
				5  => 'comments',
				6  => 'revisions',
				7  => 'slug',
				8  => 'author',
				9  => 'format',
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

	class Srs_Intranet_Pto_Request{

		/**
		 * Updated dashboard to "draft" when module is inactive.
		 *
		 * @since 3.0.0
		 */
		public static function create_vacation_dashboard() {
			Srs_Intranet_Admin::update_pages('pto-request', 'dashboard');
		}

	}

endif;
