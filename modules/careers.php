<?php

/**
 * The careers module.
 *
 * @package    Srs_Intranet
 * @subpackage Srs_Intranet/modules
 * @author     Sean Sullivan <me@seanrsullivan.com>
 */

include_once SRS_FILE_PATH . '/admin/class-srs-intranet-admin.php';
include_once SRS_FILE_PATH . '/public/class-srs-intranet-public.php';

$active = Srs_Intranet_Admin::activate_modules( 'careers' );

if ( $active == 1 ) :

	class Srs_Intranet_Careers {

		/**
		 * Add "nice name" for module
		 *
		 * @since 4.1.0
		 */
		static function nice_name() {
			return 'Careers';
		}

		/**
		 * Register Custom Post Type - Careers
		 *
		 * @since 1.0.0
		 */
		function careers_post_type() {
			Srs_Intranet_Admin::custom_post_types( 'Careers', 'Career', 'careers', 6, 'dashicons-megaphone');
		}

		/**
		 * Register Custom Taxonomy - Careers (Category Type)
		 *
		 * @since 1.0.0
		 */
		function careers_taxonomies() {
			Srs_Intranet_Admin::register_taxonomies( 'Job Categories', 'career-cat', 'careers' );
		}

		/*------------------------------------*\
		    Create Pages
		\*------------------------------------*/

		/**
		 * Creates the dashboard page.
		 *
		 * Added post update to publish if page exists in 3.0.0
		 *
		 * @since 1.0.0
		 */
		public static function create_careers_dashboard() {
			Srs_Intranet_Admin::create_pages('Careers', 'careers', 'dashboard');
		}

		/**
		 * Creates the dashboard edit page.
		 *
		 * Added post update to publish if page exists in 3.0.0
		 *
		 * @since 1.0.0
		 */
		public static function create_careers_edit() {
			Srs_Intranet_Admin::create_pages('Add/Edit Careers', 'careers-edit', 'careers', 'dashboard');
		}


		/*------------------------------------*\
		    Assign Page Templates
		\*------------------------------------*/

		/**
		 * Assigns custom template to dashboard page.
		 *
		 * @since 1.0.0
		 */
		public function careers_dashboard_template( $page_template ) {
			if ( is_page( 'careers' ) )
		        $page_template = SRS_FILE_PATH . '/public/templates/careers-dashboard.php';

		    return $page_template;
		}

		/**
		 * Assigns custom template to careers add/edit page.
		 *
		 * @since 1.1.0
		 */
		public function careers_edit_template( $page_template ) {
			if ( is_page( 'careers-edit' ) )
		        $page_template = SRS_FILE_PATH . '/public/templates/careers-single-edit.php';

		    return $page_template;
		}

		/**
		 * Assigns custom template to single post pages.
		 *
		 * @since 1.0.0
		 */
		function careers_single_template( $single ) {
			global $wp_query, $post;

		    if ($post->post_type == 'careers') :
		        if(file_exists(SRS_FILE_PATH . '/public/templates/careers-single.php'))
		            return SRS_FILE_PATH . '/public/templates/careers-single.php';
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
	        'key' => 'group_56a7aa91c196b',
	        'title' => 'Job Listing',
	        'fields' => array (
	            array (
	                'key' => 'field_56a7aac1506b5',
	                'label' => 'Job Headline',
	                'name' => 'job_headline',
	                'type' => 'text',
	                'instructions' => 'Put a fancy title here for the page/news listing.',
	                'required' => 0,
	                'conditional_logic' => 0,
	                'wrapper' => array (
	                    'width' => '',
	                    'class' => '',
	                    'id' => '',
	                ),
	                'default_value' => '',
	                'placeholder' => '',
	                'prepend' => '',
	                'append' => '',
	                'maxlength' => '',
	                'readonly' => 0,
	                'disabled' => 0,
	            ),
	            array (
	                'key' => 'field_56a7ab3f506b8',
	                'label' => 'Job Number',
	                'name' => 'job_number',
	                'type' => 'text',
	                'instructions' => '',
	                'required' => 0,
	                'conditional_logic' => 0,
	                'wrapper' => array (
	                    'width' => 25,
	                    'class' => '',
	                    'id' => '',
	                ),
	                'default_value' => '',
	                'placeholder' => '',
	                'prepend' => '',
	                'append' => '',
	                'maxlength' => '',
	                'readonly' => 0,
	                'disabled' => 0,
	            ),
	            array (
	                'key' => 'field_56a7ab53506b9',
	                'label' => 'Job Group',
	                'name' => 'job_group',
	                'type' => 'taxonomy',
	                'instructions' => '',
	                'required' => 0,
	                'conditional_logic' => 0,
	                'wrapper' => array (
	                    'width' => 70,
	                    'class' => '',
	                    'id' => '',
	                ),
	                'taxonomy' => 'career-cat',
	                'field_type' => 'select',
	                'allow_null' => 0,
	                'add_term' => 1,
	                'save_terms' => 1,
	                'load_terms' => 0,
	                'return_format' => 'object',
	                'multiple' => 0,
	            ),
	            array (
	                'key' => 'field_56fd4abff5bdc',
	                'label' => 'Job Excerpt',
	                'name' => 'job_excerpt',
	                'type' => 'textarea',
	                'instructions' => '',
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
	                'rows' => 5,
	                'new_lines' => '',
	                'readonly' => 0,
	                'disabled' => 0,
	            ),
	            array (
	                'key' => 'field_56a7aadf506b6',
	                'label' => 'Job Description',
	                'name' => 'job_description',
	                'type' => 'wysiwyg',
	                'instructions' => '',
	                'required' => 0,
	                'conditional_logic' => 0,
	                'wrapper' => array (
	                    'width' => '',
	                    'class' => '',
	                    'id' => '',
	                ),
	                'default_value' => '',
	                'tabs' => 'all',
	                'toolbar' => 'full',
	                'media_upload' => 0,
	            ),
	            array (
	                'key' => 'field_56a7ab0a506b7',
	                'label' => 'Equal Opportunity',
	                'name' => 'equal_opportunity',
	                'type' => 'wysiwyg',
	                'instructions' => 'Figured this doesn\'t change often, so it is pre-loaded. Change as needed.',
	                'required' => 0,
	                'conditional_logic' => 0,
	                'wrapper' => array (
	                    'width' => '',
	                    'class' => '',
	                    'id' => '',
	                ),
	                'default_value' => '<h2>An Equal Opportunity Employer</h2>

	    <p><em>COMPANY_NAME participates in E-Verify. COMPANY_NAME recognizes and values the diversity of its employees. We do not discriminate on the basis of race, color, religion, national origin, sex, sexual orientation, gender identity, age, disability, veteran status or genetic information. It is our intention that all qualified applicants are given equal opportunity and that selection decisions are based on job-related factors.</em></p>',
	                'tabs' => 'all',
	                'toolbar' => 'basic',
	                'media_upload' => 0,
	            ),
	            array (
	                'key' => 'field_56a7acb3506bb',
	                'label' => 'Online Form?',
	                'name' => 'online_form',
	                'type' => 'true_false',
	                'instructions' => '',
	                'required' => 0,
	                'conditional_logic' => 0,
	                'wrapper' => array (
	                    'width' => '',
	                    'class' => '',
	                    'id' => '',
	                ),
	                'message' => 'Uncheck if no online application form is needed.',
	                'default_value' => 1,
	            ),
	            array (
	                'key' => 'field_56a7ac14506ba',
	                'label' => 'Form Fields',
	                'name' => 'form_fields',
	                'type' => 'checkbox',
	                'instructions' => 'This is for you to select the form fields you would like to display on the job posting page. Choose wisely.',
	                'required' => 0,
	                'conditional_logic' => array (
	                    array (
	                        array (
	                            'field' => 'field_56a7acb3506bb',
	                            'operator' => '==',
	                            'value' => '1',
	                        ),
	                    ),
	                ),
	                'wrapper' => array (
	                    'width' => '',
	                    'class' => '',
	                    'id' => '',
	                ),
	                'choices' => array (
	                    'name' => 'Name',
	                    'phone_num' => 'Phone',
	                    'email_add' => 'Email',
	                    'website' => 'Website Portfolio',
	                    'comments' => 'Questions/Comments',
	                    'resume' => 'Resume Upload',
	                ),
	                'default_value' => array (
	                    'name' => 'name',
	                    'phone_num' => 'phone_num',
	                    'email_add' => 'email_add',
	                    'comments' => 'comments',
	                    'resume' => 'resume',
	                ),
	                'layout' => 'horizontal',
	                'toggle' => 1,
	            ),
	        ),
	        'location' => array (
	            array (
	                array (
	                    'param' => 'post_type',
	                    'operator' => '==',
	                    'value' => 'careers',
	                ),
	            ),
	        ),
	        'menu_order' => 0,
	        'position' => 'normal',
	        'style' => 'seamless',
	        'label_placement' => 'top',
	        'instruction_placement' => 'label',
	        'hide_on_screen' => array (
	            0 => 'the_content',
	            1 => 'excerpt',
	            2 => 'custom_fields',
	            3 => 'discussion',
	            4 => 'comments',
	            5 => 'author',
	            6 => 'format',
	            7 => 'featured_image',
	            8 => 'categories',
	            9 => 'tags',
	            10 => 'send-trackbacks',
	        ),
	        'active' => 1,
	        'description' => '',
	    ));

	endif;

else :

	class Srs_Intranet_Careers {

		/**
		 * Updated dashboard to "draft" when module is inactive.
		 *
		 * @since 3.0.0
		 */
		public static function create_careers_dashboard() {
			Srs_Intranet_Admin::update_pages('careers-dash', 'dashboard');
		}

		/**
		 * Updated dashboard to "draft" when module is inactive.
		 *
		 * @since 3.0.0
		 */
		public static function create_careers_edit() {
			Srs_Intranet_Admin::update_pages('careers-edit', 'careers-dash', 'dashboard');
		}

	}

endif;
