<?php

/**
 * The #OneTeam suggestion form.
 *
 * @package    Srs_Intranet
 * @subpackage Srs_Intranet/modules
 * @author     Sean Sullivan <me@seanrsullivan.com>
 */

include_once SRS_FILE_PATH . '/admin/class-srs-intranet-admin.php';
include_once SRS_FILE_PATH . '/public/class-srs-intranet-public.php';

$active = Srs_Intranet_Admin::activate_modules( 'comment_box' );

if ( $active == 1 ) :

	class Srs_Intranet_Comment_Box{

		/**
		 * Add "nice name" for module
		 *
		 * @since 4.1.0
		 */
		static function nice_name() {
			return 'OneTeam Comment Box';
		}

		/**
		 * Register Custom Post Type - OneTeam Suggestion Box
		 *
		 * @since 4.1.0
		 */
		function comment_form_post_type() {
			Srs_Intranet_Admin::custom_post_types( 'OneTeam Comment Box', 'Comment', 'comment-form', 50, 'dashicons-editor-help', false, 'comments');
		}

		/**
		 * Creates the OneTeam suggestion box dashboard page.
		 *
		 * @since 4.1.0
		 */
		function create_comment_form_dashboard() {
		    Srs_Intranet_Admin::create_pages('OneTeam Comment Box Dashboard', 'comment-form-dashboard', 'dashboard');
		}

		/**
		 * Assigns custom template to OneTeam suggestion box dashboard.
		 *
		 * @since 4.1.0
		 */
		function comment_form_dashboard_template( $page_template ) {
			if ( is_page( 'comment-form-dashboard' ) )
		        $page_template = SRS_FILE_PATH . '/public/templates/comment-form-dashboard.php';

		    return $page_template;
		}

		/**
		 * Creates the OneTeam suggestion box form page.
		 *
		 * @since 4.1.0
		 */
		function create_comment_form() {
		    Srs_Intranet_Admin::create_pages('OneTeam Comment Box', 'comment-comment-box', 'dashboard');
		}

		/**
		 * Assigns custom template to OneTeam suggestion box form.
		 *
		 * @since 4.1.0
		 */
		function comment_form_template( $page_template ) {
			if ( is_page( 'comment-comment-box' ) )
		        $page_template = SRS_FILE_PATH . '/public/templates/comment-form.php';

		    return $page_template;
		}

		/**
		 * Assigns custom template to single post pages.
		 *
		 * @since 4.1.0
		 */
		function comment_form_single_template( $single ) {
		    global $wp_query, $post;

		    if ($post->post_type == 'comment-form') :
		        if(file_exists(SRS_FILE_PATH . '/public/templates/comment-form-single.php'))
		            return SRS_FILE_PATH . '/public/templates/comment-form-single.php';
		    endif;

		    return $single;
		}

	}


	/**
	 * Adds custom fields to both custom post types
	 *
	 * @since 4.1.0
	 */
	if( function_exists('acf_add_local_field_group') ):

		acf_add_local_field_group(array(
			'key' => 'group_5d7696e7018c7',
			'title' => 'OneTeam Suggestion Box',
			'fields' => array(
				array(
					'key' => 'field_5d76972b41235',
					'label' => 'Comment Type',
					'name' => 'comment_type',
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
					'key' => 'field_5d76975c41236',
					'label' => 'Comment Topic',
					'name' => 'comment_topic',
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
					'key' => 'field_5d76978e41237',
					'label' => 'Comment',
					'name' => 'comment',
					'type' => 'textarea',
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
					'maxlength' => '',
					'rows' => '',
					'new_lines' => '',
				),
				array(
					'key' => 'field_5d7697a941238',
					'label' => 'Department',
					'name' => 'department',
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
			),
			'location' => array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'comment-form',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'seamless',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => array(
				0 => 'permalink',
				1 => 'the_content',
				2 => 'excerpt',
				3 => 'discussion',
				4 => 'comments',
				5 => 'revisions',
				6 => 'slug',
				7 => 'author',
				8 => 'format',
				9 => 'page_attributes',
				10 => 'featured_image',
				11 => 'categories',
				12 => 'tags',
				13 => 'send-trackbacks',
			),
			'active' => true,
			'description' => '',
		));

	endif;

else :

	class Srs_Intranet_Comment_Box{

		/**
		 * Updated dashboard to "draft" when module is inactive.
		 *
		 * @since 4.1.0
		 */
		public static function create_comment_form_dashboard() {
			Srs_Intranet_Admin::update_pages('comment-form-dashboard', 'dashboard');
		}

		/**
		 * Updated form to "draft" when module is inactive.
		 *
		 * @since 4.1.0
		 */
		public static function create_comment_form() {
		    Srs_Intranet_Admin::update_pages('comment-comment-box', 'dashboard');
		}

	}

endif;
