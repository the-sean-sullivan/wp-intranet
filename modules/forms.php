<?php

/**
 * The forms module.
 *
 * @package    Srs_Intranet
 * @subpackage Srs_Intranet/modules
 * @author     Sean Sullivan <me@seanrsullivan.com>
 */

include_once SRS_FILE_PATH . '/admin/class-srs-intranet-admin.php';
include_once SRS_FILE_PATH . '/public/class-srs-intranet-public.php';

$active = Srs_Intranet_Admin::activate_modules( 'forms' );

if ( $active == 1 ) :

	class Srs_Intranet_Forms {

		/**
		 * Add "nice name" for module
		 *
		 * @since 4.1.0
		 */
		static function nice_name() {
			return 'Forms';
		}

		/**
		 * Register Custom Post Type - Forms
		 *
		 * @since 1.0.0
		 */
		function forms_post_type() {
			Srs_Intranet_Admin::custom_post_types( 'Forms', 'Form', 'forms', 6, 'dashicons-media-text');
		}

		/**
		 * Creates the dashboard page.
		 *
		 * Added post update to publish if page exists in 3.0.0
		 *
		 * @since 1.0.0
		 */
		public static function create_forms_dashboard() {
			Srs_Intranet_Admin::create_pages('Forms', 'forms', 'dashboard');
		}

		/**
		 * Assigns custom template to dashboard page.
		 *
		 * @since 1.0.0
		 */
		function forms_dashboard_template( $page_template ) {
			if ( is_page( 'forms' ) )
		        $page_template = SRS_FILE_PATH . '/public/templates/forms-dashboard.php';

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
	        'key' => 'group_57ebef5d4b72d',
	        'title' => 'Forms',
	        'fields' => array (
	            array (
	                'key' => 'field_57ebefa253820',
	                'label' => 'File',
	                'name' => 'file',
	                'type' => 'file',
	                'instructions' => '',
	                'required' => 0,
	                'conditional_logic' => 0,
	                'wrapper' => array (
	                    'width' => '50',
	                    'class' => '',
	                    'id' => '',
	                ),
	                'return_format' => 'array',
	                'library' => 'all',
	                'min_size' => '',
	                'max_size' => '',
	                'mime_types' => '',
	            ),
	            array (
	                'key' => 'field_57ebf1cf53821',
	                'label' => 'Screenshot',
	                'name' => 'screenshot',
	                'type' => 'image',
	                'instructions' => '',
	                'required' => 0,
	                'conditional_logic' => 0,
	                'wrapper' => array (
	                    'width' => '50',
	                    'class' => '',
	                    'id' => '',
	                ),
	                'return_format' => 'array',
	                'preview_size' => 'thumbnail',
	                'library' => 'all',
	                'min_width' => '',
	                'min_height' => '',
	                'min_size' => '',
	                'max_width' => '',
	                'max_height' => '',
	                'max_size' => '',
	                'mime_types' => '',
	            ),
	            array (
	                'key' => 'field_57ebf1d753822',
	                'label' => 'Description',
	                'name' => 'description',
	                'type' => 'textarea',
	                'instructions' => 'If needed.',
	                'required' => 0,
	                'conditional_logic' => 0,
	                'wrapper' => array (
	                    'width' => '',
	                    'class' => '',
	                    'id' => '',
	                ),
	                'default_value' => '',
	                'placeholder' => '',
	                'maxlength' => '',
	                'rows' => 3,
	                'new_lines' => 'wpautop',
	            ),
	        ),
	        'location' => array (
	            array (
	                array (
	                    'param' => 'post_type',
	                    'operator' => '==',
	                    'value' => 'forms',
	                ),
	            ),
	        ),
	        'menu_order' => 0,
	        'position' => 'normal',
	        'style' => 'seamless',
	        'label_placement' => 'top',
	        'instruction_placement' => 'label',
	        'hide_on_screen' => array (
	            0 => 'permalink',
	            1 => 'the_content',
	            2 => 'excerpt',
	            3 => 'custom_fields',
	            4 => 'discussion',
	            5 => 'comments',
	            6 => 'revisions',
	            7 => 'slug',
	            8 => 'author',
	            9 => 'format',
	            10 => 'page_attributes',
	            11 => 'featured_image',
	            12 => 'categories',
	            13 => 'tags',
	            14 => 'send-trackbacks',
	        ),
	        'active' => 1,
	        'description' => '',
	    ));

	endif;

else :

	class Srs_Intranet_Forms {

		/**
		 * Updated dashboard to "draft" when module is inactive.
		 *
		 * @since 3.0.0
		 */
		public static function create_forms_dashboard() {
			Srs_Intranet_Admin::update_pages('forms-dash', 'dashboard');
		}

	}

endif;
