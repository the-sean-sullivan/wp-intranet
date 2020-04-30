<?php

/**
 * The applicants module.
 *
 * @package    Srs_Intranet
 * @subpackage Srs_Intranet/modules
 * @author     Sean Sullivan <me@seanrsullivan.com>
 */

include_once SRS_FILE_PATH . '/admin/class-srs-intranet-admin.php';
include_once SRS_FILE_PATH . '/public/class-srs-intranet-public.php';

$active = Srs_Intranet_Admin::activate_modules( 'applicants' );

if ( $active == 1 ) :

	class Srs_Intranet_Applicants {

		/**
		 * Add "nice name" for module
		 *
		 * @since 4.1.0
		 */
		static function nice_name() {
			return 'Candidate Tracker';
		}

		/**
		 * Register Custom Post Type - Job Applicants
		 *
		 * @since 1.0.0
		 */
		function applicants_post_type() {
			Srs_Intranet_Admin::custom_post_types( 'Job Applicants', 'Applicant', 'applicants', 50, 'dashicons-id-alt', false, 'comments');
		}

		/**
		 * Register Custom Taxonomy - Job Applicant Dept. (Category Type)
		 *
		 * @since 1.0.0
		 */
		function applicant_dept_taxonomies() {
			Srs_Intranet_Admin::register_taxonomies( 'Applicants Dept.', 'applicant-dept', 'applicants' );
		}

		/**
		 * Register Custom Taxonomy - Job Applicant Status (Tag Type)
		 *
		 * @since 1.0.0
		 */
		function applicant_taxonomies() {
			Srs_Intranet_Admin::register_taxonomies( 'Applicants Status', 'applicant-status', 'applicants', false );
		}

		/**
		 * Creates the dashboard page.
		 *
		 * Added post update to publish if page exists in 3.0.0
		 *
		 * @since 1.0.0
		 */
		public static function create_applicants_dashboard() {
		    Srs_Intranet_Admin::create_pages('Applicants', 'applicants', 'dashboard');
		}

		/**
		 * Assigns custom template to dashboard page.
		 *
		 * @since 1.0.0
		 */
		function applicants_dashboard_template( $page_template ) {
		    if ( is_page( 'applicants' ) )
		        $page_template = SRS_FILE_PATH . '/public/templates/applicants-dashboard.php';

		    return $page_template;
		}

		/**
		 * Assigns custom template to single post pages.
		 *
		 * @since 1.0.0
		 */
		function applicants_single_template($single) {
		    global $wp_query, $post;

		    if ($post->post_type == 'applicants') :
		        if(file_exists(SRS_FILE_PATH . '/public/templates/applicants-single.php'))
		            return SRS_FILE_PATH . '/public/templates/applicants-single.php';
		    endif;

		    return $single;
		}
	}

	/**
	 * Adds custom fields to both custom post types
	 *
	 * @since 1.0.0
	 */
	if( function_exists('acf_add_local_field_group') ):

	acf_add_local_field_group(array (
		'key'    => 'group_56a7b3d0625a1',
		'title'  => 'Applicants',
		'fields' => array (
	        array (
				'key'               => 'field_56a7b46043324',
				'label'             => 'Job Applying For',
				'name'              => 'job_applying_for',
				'type'              => 'text',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array (
					'width' => 75,
					'class' => '',
					'id'    => '',
	            ),
				'default_value' => '',
				'placeholder'   => '',
				'prepend'       => '',
				'append'        => '',
				'maxlength'     => '',
				'readonly'      => 0,
				'disabled'      => 0,
	        ),
	        array (
				'key'               => 'field_56e1c372191e0',
				'label'             => 'Job Number',
				'name'              => 'job_number',
				'type'              => 'text',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array (
					'width' => 25,
					'class' => '',
					'id'    => '',
	            ),
				'default_value' => '',
				'placeholder'   => '',
				'prepend'       => '',
				'append'        => '',
				'maxlength'     => '',
				'readonly'      => 0,
				'disabled'      => 0,
	        ),
	        array (
				'key'               => 'field_56a7b47a43325',
				'label'             => 'Job Department',
				'name'              => 'job_department',
				'type'              => 'taxonomy',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array (
					'width' => '',
					'class' => '',
					'id'    => '',
	            ),
				'taxonomy'      => 'applicant-dept',
				'field_type'    => 'select',
				'allow_null'    => 0,
				'add_term'      => 1,
				'save_terms'    => 1,
				'load_terms'    => 1,
				'return_format' => 'object',
				'multiple'      => 0,
	        ),
	        array (
				'key'               => 'field_56e2f0d572291',
				'label'             => 'Status',
				'name'              => 'status',
				'type'              => 'taxonomy',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array (
					'width' => '',
					'class' => '',
					'id'    => '',
	            ),
				'taxonomy'      => 'applicant-status',
				'field_type'    => 'select',
				'allow_null'    => 0,
				'add_term'      => 1,
				'save_terms'    => 1,
				'load_terms'    => 1,
				'return_format' => 'object',
				'multiple'      => 0,
	        ),
	        array (
				'key'               => 'field_56a7b3d74331d',
				'label'             => 'Name',
				'name'              => 'name',
				'type'              => 'text',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array (
					'width' => '',
					'class' => '',
					'id'    => '',
	            ),
				'default_value' => '',
				'placeholder'   => '',
				'prepend'       => '',
				'append'        => '',
				'maxlength'     => '',
				'readonly'      => 0,
				'disabled'      => 0,
	        ),
	        array (
				'key'               => 'field_56a7b3e84331f',
				'label'             => 'Phone',
				'name'              => 'phone_num',
				'type'              => 'text',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array (
					'width' => '',
					'class' => '',
					'id'    => '',
	            ),
				'default_value' => '',
				'placeholder'   => '',
				'prepend'       => '',
				'append'        => '',
				'maxlength'     => '',
				'readonly'      => 0,
				'disabled'      => 0,
	        ),
	        array (
				'key'               => 'field_56a7b40343320',
				'label'             => 'Email',
				'name'              => 'email_add',
				'type'              => 'email',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array (
					'width' => '',
					'class' => '',
					'id'    => '',
	            ),
				'default_value' => '',
				'placeholder'   => '',
				'prepend'       => '',
				'append'        => '',
	        ),
	        array (
				'key'               => 'field_56a7b41843321',
				'label'             => 'Questions/Comments',
				'name'              => 'comments',
				'type'              => 'wysiwyg',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array (
					'width' => '',
					'class' => '',
					'id'    => '',
	            ),
				'default_value' => '',
				'tabs'          => 'all',
				'toolbar'       => 'full',
				'media_upload'  => 1,
	        ),
	        array (
				'key'               => 'field_56a7b43a43323',
				'label'             => 'Website Portfolio',
				'name'              => 'website',
				'type'              => 'url',
				'instructions'      => 'If applicable',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array (
					'width' => '',
					'class' => '',
					'id'    => '',
	            ),
				'default_value' => '',
				'placeholder'   => '',
	        ),
	        array (
				'key'               => 'field_56e1e04d8b71f',
				'label'             => 'Resume',
				'name'              => 'resume',
				'type'              => 'textarea',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array (
					'width' => '',
					'class' => '',
					'id'    => '',
	            ),
				'default_value' => '',
				'placeholder'   => '',
				'maxlength'     => '',
				'rows'          => '',
				'new_lines'     => '',
				'readonly'      => 0,
				'disabled'      => 0,
	        ),
	        array (
				'key'               => 'field_56e6f9344db7f',
				'label'             => 'Notes',
				'name'              => 'notes',
				'type'              => 'textarea',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array (
					'width' => '',
					'class' => '',
					'id'    => '',
	            ),
				'default_value' => '',
				'placeholder'   => '',
				'maxlength'     => '',
				'rows'          => '',
				'new_lines'     => 'wpautop',
				'readonly'      => 0,
				'disabled'      => 0,
	        ),
	    ),
	    'location' => array (
	        array (
	            array (
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'applicants',
	            ),
	            array (
					'param'    => 'page_type',
					'operator' => '==',
					'value'    => 'top_level',
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
			11 => 'send-trackbacks',
	    ),
		'active'      => 1,
		'description' => '',
	));

	acf_add_local_field_group(array (
		'key'    => 'group_5744bce4b923c',
		'title'  => 'Survey',
		'fields' => array (
	        array (
				'key'               => 'field_5744c043701e8',
				'label'             => 'How would you rank the candidate\'s skill set?',
				'name'              => 'skill_set',
				'type'              => 'radio',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array (
					'width' => '',
					'class' => '',
					'id'    => '',
	            ),
	            'choices' => array (
	                1 => 1,
	                2 => 2,
	                3 => 3,
	                4 => 4,
	                5 => 5,
	            ),
				'allow_null'        => 0,
				'other_choice'      => 0,
				'save_other_choice' => 0,
				'default_value'     => '',
				'layout'            => 'horizontal',
	        ),
	        array (
				'key'               => 'field_5744c07a701e9',
				'label'             => 'How would you rank the candidate\'s experience?',
				'name'              => 'experience',
				'type'              => 'radio',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array (
					'width' => '',
					'class' => '',
					'id'    => '',
	            ),
	            'choices' => array (
	                1 => 1,
	                2 => 2,
	                3 => 3,
	                4 => 4,
	                5 => 5,
	            ),
				'allow_null'        => 0,
				'other_choice'      => 0,
				'save_other_choice' => 0,
				'default_value'     => '',
				'layout'            => 'horizontal',
	        ),
	        array (
				'key'               => 'field_5744c08d701ea',
				'label'             => 'How would you rank their ability to fit into our culture?',
				'name'              => 'culture_fit',
				'type'              => 'radio',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array (
					'width' => '',
					'class' => '',
					'id'    => '',
	            ),
	            'choices' => array (
	                1 => 1,
	                2 => 2,
	                3 => 3,
	                4 => 4,
	                5 => 5,
	            ),
				'allow_null'        => 0,
				'other_choice'      => 0,
				'save_other_choice' => 0,
				'default_value'     => '',
				'layout'            => 'horizontal',
	        ),
	        array (
				'key'               => 'field_5744c0a2701eb',
				'label'             => 'How strongly do you support hiring the candidate?',
				'name'              => 'hire_candidate',
				'type'              => 'radio',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array (
					'width' => '',
					'class' => '',
					'id'    => '',
	            ),
	            'choices' => array (
	                1 => 1,
	                2 => 2,
	                3 => 3,
	                4 => 4,
	                5 => 5,
	            ),
				'allow_null'        => 0,
				'other_choice'      => 0,
				'save_other_choice' => 0,
				'default_value'     => '',
				'layout'            => 'horizontal',
	        ),
	        array (
				'key'               => 'field_5744c0ba701ec',
				'label'             => 'Notes',
				'name'              => 'notes',
				'type'              => 'wysiwyg',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array (
					'width' => '',
					'class' => '',
					'id'    => '',
	            ),
				'default_value' => '',
				'tabs'          => 'visual',
				'toolbar'       => 'basic',
				'media_upload'  => 0,
	        ),
	    ),
	    'location' => array (
	        array (
	            array (
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'applicants',
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
			0  => 'the_content',
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

	class Srs_Intranet_Applicants {

		/**
		 * Updated dashboard to "draft" when module is inactive.
		 *
		 * @since 3.0.0
		 */
		public static function create_applicants_dashboard() {
			Srs_Intranet_Admin::update_pages('applicants', 'dashboard');
		}

	}

endif;
