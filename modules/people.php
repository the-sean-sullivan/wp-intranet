<?php

/**
 * The people / phone list module.
 *
 * @package    Srs_Intranet
 * @subpackage Srs_Intranet/modules
 * @author     Sean Sullivan <me@seanrsullivan.com>
 */

include_once SRS_FILE_PATH . '/admin/class-srs-intranet-admin.php';
include_once SRS_FILE_PATH . '/public/class-srs-intranet-public.php';

$active = Srs_Intranet_Admin::activate_modules( 'people' );

if ( $active == 1 ) :

	class Srs_Intranet_People {

		/**
		 * Add "nice name" for module
		 *
		 * @since 4.1.0
		 */
		static function nice_name() {
			return 'Agency Directory';
		}

		/*------------------------------------*\
		    People Pages
		\*------------------------------------*/

		/**
		 * Register Custom Post Type - People
		 *
		 * @since 1.0.0
		 */
		public static function people_post_type() {
		    Srs_Intranet_Admin::custom_post_types( 'People', 'Employee', 'people', 4, 'dashicons-groups', true);
		}

		/*------------------------------------*\
		    Create Pages * Templates
		\*------------------------------------*/


		/* ===== People ===== */

		/**
		 * Creates the dashboard page.
		 *
		 * @since 1.0.0
		 */
		function create_people_dashboard() {
			Srs_Intranet_Admin::create_pages('People', 'people', 'dashboard');
		}

		/**
		 * Assigns custom template to login & dashboard page.
		 *
		 * @since 1.0.0
		 */
		function people_dashboard_template( $page_template ) {
			if ( is_page( 'people' ) )
		        $page_template = SRS_FILE_PATH . '/public/templates/people-dashboard.php';

		    return $page_template;
		}


		/* ===== Performance Review ===== */

		/**
		 * Creates the Performance Review page.
		 *
		 * @since 3.5.0
		 */
		function create_review_dashboard() {
			Srs_Intranet_Admin::create_pages('Performance Review', 'performance-review', 'dashboard');
		}

		/**
		 * Assigns custom template to Performance Review
		 *
		 * @since 3.5.0
		 */
		function review_template( $page_template ) {
			if ( is_page( 'performance-review' ) )
		        $page_template = SRS_FILE_PATH . '/public/templates/performance-review.php';

		    return $page_template;
		}


		/*------------------------------------*\
		    User Functions
		\*------------------------------------*/


		/**
		 * When a new user is registered, a new page will be created for them
		 *
		 * Added sort field and auto population of sort and email fields in 1.1.0
		 * Removed custom fields in 4.0.0
		 *
		 * @since 1.0.0
		 */
		function add_user_page( $user_id ) {

		    $user_info    = get_userdata($user_id);
		    $display_name = $user_info->display_name;

		    $user_page = array(
		        'post_title'  => $display_name,
		        'post_status' => 'publish',
		        'post_type'   => 'people'
		    );
		    $post_id = wp_insert_post( $user_page );
		}

	}

	/**
	 * Adds custom fields dashboard
	 *
	 * @since 4.1.1
	 */
	if( function_exists('acf_add_local_field_group') ):

		acf_add_local_field_group(array(
			'key' => 'group_5a1c6b57716e4',
			'title' => 'Additional Contacts',
			'fields' => array(
				array(
					'key' => 'field_5cddcfa5cdcf8',
					'label' => 'Contacts',
					'name' => 'contacts',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'collapsed' => 'field_5cddcfb5cdcf9',
					'min' => 0,
					'max' => 0,
					'layout' => 'block',
					'button_label' => 'Add Contact',
					'sub_fields' => array(
						array(
							'key' => 'field_5dcd994375a49',
							'label' => 'Contact Type',
							'name' => 'contact_type',
							'type' => 'select',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '33',
								'class' => '',
								'id' => '',
							),
							'choices' => array(
								'conference' => 'Conference Rooms',
								'misc' => 'Miscellaneous Contacts',
							),
							'default_value' => array(),
							'allow_null' => 0,
							'multiple' => 0,
							'ui' => 1,
							'ajax' => 0,
							'return_format' => 'label',
							'placeholder' => '',
						),
						array(
							'key' => 'field_5cf580cceee0f',
							'label' => 'Name',
							'name' => 'name',
							'type' => 'text',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '33',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'placeholder' => '',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
						),
						array(
							'key' => 'field_5cddced316ec2',
							'label' => 'Photo',
							'name' => 'photo',
							'type' => 'image',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '33',
								'class' => '',
								'id' => '',
							),
							'return_format' => 'url',
							'preview_size' => 'thumbnail',
							'library' => 'all',
							'min_width' => '',
							'min_height' => '',
							'min_size' => '',
							'max_width' => '',
							'max_height' => '',
							'max_size' => '',
							'mime_types' => 'jpg',
						),
						array(
							'key' => 'field_5cf580a5eee0c',
							'label' => 'Contacts Info',
							'name' => 'contacts_info',
							'type' => 'group',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'layout' => 'block',
							'sub_fields' => array(
								array(
									'key' => 'field_5cf58092eee0b',
									'label' => 'Email',
									'name' => 'email',
									'type' => 'email',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '50',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
								),
								array(
									'key' => 'field_5cf580cceee0d',
									'label' => 'DID Number',
									'name' => 'phone_number_extension',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '50',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'maxlength' => '',
								),
								array(
									'key' => 'field_5cf580e3eee0e',
									'label' => 'Personal Cell Phone',
									'name' => 'personal_cell_phone',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '50',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'maxlength' => '',
								),
								array(
									'key' => 'field_5cf57ff6eee0a',
									'label' => 'Nickname',
									'name' => 'nickname',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '50',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'maxlength' => '',
								),
								array(
									'key' => 'field_5cf58215c3c26',
									'label' => 'Office Map',
									'name' => 'office_map',
									'type' => 'office_map',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '',
										'class' => '',
										'id' => '',
									),
									0 => '',
								),
							),
						),
						array(
							'key' => 'field_5a79fad0965c4',
							'label' => 'Name sort',
							'name' => 'name_sort',
							'type' => 'text',
							'instructions' => 'You can ignore this. It is auto-filled upon saving.',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => 'acf-hidden',
								'id' => '',
							),
							'default_value' => '',
							'placeholder' => '',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'page',
						'operator' => '==',
						'value' => '44',
					),
				),
			),
			'menu_order' => 7,
			'position' => 'acf_after_title',
			'style' => 'seamless',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => array(
				0 => 'the_content',
				1 => 'excerpt',
				2 => 'discussion',
				3 => 'comments',
				4 => 'revisions',
				5 => 'author',
				6 => 'format',
				7 => 'featured_image',
				8 => 'tags',
				9 => 'send-trackbacks',
			),
			'active' => true,
			'description' => '',
		));

	endif;

else:

	class Srs_Intranet_People {

		/**
		 * Updated dashboard to "draft" when module is inactive.
		 *
		 * @since 3.0.0
		 */
		public static function create_people_dashboard() {
			Srs_Intranet_Admin::update_pages('people', 'dashboard');
		}

		public static function create_my_info_dashboard() {
			Srs_Intranet_Admin::update_pages('my-info', 'dashboard');
		}

		public static function create_review_dashboard() {
			Srs_Intranet_Admin::update_pages('performance-review', 'dashboard');
		}

	}

endif;
