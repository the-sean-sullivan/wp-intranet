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

$active = Srs_Intranet_Admin::activate_modules( 'vendor_database' );

if ( $active == 1 ) :

	class Srs_Intranet_Vendor_Database {

		/**
		 * Add "nice name" for module
		 *
		 * @since 4.1.0
		 */
		static function nice_name() {
			return 'Vendor Database';
		}

		/**
		 * Register Custom Post Type - Vendors
		 *
		 * @since 1.0.0
		 */
		function vendor_post_type() {
			Srs_Intranet_Admin::custom_post_types( 'Vendors', 'Vendor', 'vendors', 50, 'dashicons-index-card', false, 'comments');
		}

		/**
		 * Register Custom Taxonomy - Vendor Categories
		 *
		 * @since 1.0.0
		 */
		function vendors_cat_taxonomies() {
			Srs_Intranet_Admin::register_taxonomies( 'Vendor Categories', 'vendor-cat', 'vendors' );
		}

		/**
		 * Register Custom Taxonomy - Vendors Tags
		 *
		 * @since 1.0.0
		 */
		function vendors_taxonomies() {
			Srs_Intranet_Admin::register_taxonomies( 'Vendor Tags', 'vendor-tags', 'vendors', false );
		}

		/*------------------------------------*\
		    Create Pages & Templates
		\*------------------------------------*/

		/**
		 * Creates the dashboard page.
		 *
		 * @since 1.0.0
		 */
		function create_vendors_dashboard() {
			Srs_Intranet_Admin::create_pages('Vendors', 'vendor-database', 'dashboard');
		}

		/**
		 * Creates the add/edit page.
		 *
		 * @since 1.0.0
		 */
		function create_vendors_add_edit_page() {
			Srs_Intranet_Admin::create_pages('Add/Edit Vendors', 'vendors-edit', 'vendor-database', 'dashboard');
		}

		/**
		 * Assigns custom template to login & dashboard page.
		 *
		 * @since 1.0.0
		 */
		function vendors_dashboard_template( $page_template ) {
			if ( is_page( 'vendor-database' ) )
		        $page_template = SRS_FILE_PATH . '/public/templates/vendors-dashboard.php';

		    return $page_template;
		}

		/**
		 * Assigns custom template to add/edit page
		 *
		 * @since 1.0.0
		 */
		function vendors_add_edit_page_template( $page_template ) {
			if ( is_page( 'vendors-edit' ) )
		        $page_template = SRS_FILE_PATH . '/public/templates/vendors-edit.php';

		    return $page_template;
		}

		/**
		 * Assigns custom template to single post pages.
		 *
		 * @since 1.0.0
		 */
		function vendors_single_template( $single ) {
			global $wp_query, $post;

		    if ($post->post_type == 'vendors') :
		        if(file_exists(SRS_FILE_PATH . '/public/templates/vendors-single.php'))
		            return SRS_FILE_PATH . '/public/templates/vendors-single.php';
		    endif;

		    return $single;
		}

		/*------------------------------------*\
		    Comment Functions
		\*------------------------------------*/

		/**
		 * Allows user to update comments via AJAX
		 *
		 * @since 1.0.0
		 */
		function save_vendor_comment( $comment_id ) {
			$comment_id = $_POST['comment_id'];
			$comment = $_POST['comment'];

			$commentarr = array();
			$commentarr['comment_ID'] = $comment_id;
			$commentarr['comment_content'] = $comment;

			wp_update_comment( $commentarr );

			die();
		}

		/**
		 * Allows user to delete comments via AJAX
		 *
		 * @since 1.0.0
		 */
		function delete_vendor_comment( $comment_id ) {
			$comment_id = $_POST['comment_id'];

			wp_delete_comment( $comment_id, false );

			die();
		}

		/*------------------------------------*\
		    Video Stuff
		\*------------------------------------*/

		/**
		 * Get Video Thumbnails
		 *
		 * @since 1.0.0
		 */
		public static function get_video_thumbnail_uri( $video_uri ) {

				$thumbnail_uri = '';
				$video = Srs_Intranet_Vendors::parse_video_uri( $video_uri );

				// YouTube
				if ( $video['type'] == 'youtube' )
					$thumbnail_uri = 'http://img.youtube.com/vi/' . $video['id'] . '/hqdefault.jpg';

				// Vimeo
				if( $video['type'] == 'vimeo' )
					$thumbnail_uri = Srs_Intranet_Vendors::get_vimeo_thumbnail_uri( $video['id'] );

				// Default
				if( empty( $thumbnail_uri ) || is_wp_error( $thumbnail_uri ) )
					$thumbnail_uri = '';

				//return thumbnail uri
				return $thumbnail_uri;

		}

		// Parse the video uri/url to determine the video type/source and the video id
		public static function parse_video_uri( $url ) {

			// Parse the url
			$parse = parse_url( $url );
			$video_type = '';
			$video_id = '';

			// youtu.be
			if ( $parse['host'] == 'youtu.be' ) :
				$video_type = 'youtube';
				$video_id = ltrim( $parse['path'],'/' );
			endif;


			// youtube.com (with www)
			if ( ( $parse['host'] == 'youtube.com' ) || ( $parse['host'] == 'www.youtube.com' ) ) :
				$video_type = 'youtube';

				parse_str( $parse['query'] );

				$video_id = $v;

				if ( !empty( $feature ) )
					$video_id = end( explode( 'v=', $parse['query'] ) );

				if ( strpos( $parse['path'], 'embed' ) == 1 )
					$video_id = end( explode( '/', $parse['path'] ) );
			endif;


			// vimeo.com (with www.)
			if ( ( $parse['host'] == 'vimeo.com' ) || ( $parse['host'] == 'www.vimeo.com' ) ) :
				$video_type = 'vimeo';
				$video_id = ltrim( $parse['path'],'/' );
			endif;

			// If recognised type return video array
			if ( !empty( $video_type ) ) :

				$video_array = array(
					'type' => $video_type,
					'id' => $video_id
				);
				return $video_array;

			else :

				return false;

			endif;

		}

		// Takes a Vimeo video/clip ID and calls the Vimeo API v2 to get the large thumbnail URL.
		public static function get_vimeo_thumbnail_uri( $clip_id ) {
			$vimeo_api_uri = 'http://vimeo.com/api/v2/video/' . $clip_id . '.php';
			$vimeo_response = wp_remote_get( $vimeo_api_uri );

			if( is_wp_error( $vimeo_response ) ) :
				return $vimeo_response;
			else :
				$vimeo_response = unserialize( $vimeo_response['body'] );
				return $vimeo_response[0]['thumbnail_large'];
			endif;
		}

		/*------------------------------------*\
		    Lightbox Things
		\*------------------------------------*/

		/**
		 * Function to add new/update lightbox
		 *
		 * @since 1.0.0
		 */
		function add_lightbox() {

			parse_str($_POST['data'], $lightbox);

			$user_id       = $lightbox['user_id'];
			$value         = $lightbox['lightbox_value'];
			$lightbox_name = $lightbox['lightbox_name'];

			$lightbox_name = ( strpos($lightbox_name, 'lightbox_') !== false ) ? $lightbox_name : 'lightbox_' . $lightbox_name;

			foreach( $value as $lightbox_value ) :
				add_user_meta( $user_id, $lightbox_name, $lightbox_value);
			endforeach;

			die();

		}

		/**
		 * Function to remove lightbox item
		 *
		 * @since 1.0.0
		 */
		function remove_lightbox() {

			$user_id        = $_POST['user_id'];
			$lightbox_name  = $_POST['lb_name'];
			$lightbox_value = $_POST['item_id'];

			delete_user_meta( $user_id, $lightbox_name, $lightbox_value);

			die();

		}

		/**
		 * Function to delete lightbox
		 *
		 * @since 1.0.0
		 */
		function delete_lightbox() {

			parse_str($_POST['data'], $lightbox);

			$user_id        = $_POST['user_id'];
			$lightbox_name  = $lightbox['lb_db_name'];

			delete_user_meta( $user_id, $lightbox_name);

			die();

		}

	}

	/**
	 * Adds custom fields to both custom post types
	 *
	 * @since 1.0.0
	 */
	if( function_exists('acf_add_local_field_group') ):

	acf_add_local_field_group(array (
		'key' => 'group_5787d7931f2e5',
		'title' => 'Vendors',
		'fields' => array (
			array (
				'key' => 'field_5787d7a56768d',
				'label' => 'Category',
				'name' => 'vendor_category',
				'type' => 'taxonomy',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'taxonomy' => 'vendor-cat',
				'field_type' => 'checkbox',
				'allow_null' => 0,
				'add_term' => 1,
				'save_terms' => 1,
				'load_terms' => 1,
				'return_format' => 'id',
				'multiple' => 0,
			),
			array (
				'key' => 'field_57978e223bad7',
				'label' => 'Logo',
				'name' => 'vendor_logo',
				'type' => 'image',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
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
				'key' => 'field_5787d7f56768f',
				'label' => 'Contact Name',
				'name' => 'contact_name',
				'type' => 'text',
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
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
				'readonly' => 0,
				'disabled' => 0,
			),
			array (
				'key' => 'field_5787d7fb67690',
				'label' => 'Address',
				'name' => 'address',
				'type' => 'google_map',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'center_lat' => '',
				'center_lng' => '',
				'zoom' => '',
				'height' => '',
			),
			array (
				'key' => 'field_57978dfb3bad6',
				'label' => 'City',
				'name' => 'vendor_city',
				'type' => 'text',
				'instructions' => 'This is so we can display just the city name on the vendor page.',
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
				'key' => 'field_5787d81167691',
				'label' => 'Phone Number',
				'name' => 'phone_number',
				'type' => 'text',
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
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
				'readonly' => 0,
				'disabled' => 0,
			),
			array (
				'key' => 'field_5787d82467692',
				'label' => 'Email Address',
				'name' => 'email_address',
				'type' => 'email',
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
				'prepend' => '',
				'append' => '',
			),
			array (
				'key' => 'field_5787d9f16769e',
				'label' => 'Website',
				'name' => 'website',
				'type' => 'url',
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
			),
			array (
				'key' => 'field_5787d83567693',
				'label' => 'Represented By',
				'name' => 'represented_by',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array (
					'self' => 'Self',
					'company' => 'Company',
					'dk' => 'Don\'t Know',
				),
				'default_value' => array (
				),
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'ajax' => 0,
				'placeholder' => '',
				'disabled' => 0,
				'readonly' => 0,
			),
			array (
				'key' => 'field_5787d86f67694',
				'label' => 'Company',
				'name' => '',
				'type' => 'message',
				'instructions' => 'If represented by a company',
				'required' => 0,
				'conditional_logic' => array (
					array (
						array (
							'field' => 'field_5787d83567693',
							'operator' => '==',
							'value' => 'company',
						),
					),
				),
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => 'If represented by a company, please fill out information below as best as you can.',
				'new_lines' => 'wpautop',
				'esc_html' => 0,
			),
			array (
				'key' => 'field_5787d8ba67695',
				'label' => 'Company Name',
				'name' => 'rep_by_company_name',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array (
					array (
						array (
							'field' => 'field_5787d83567693',
							'operator' => '==',
							'value' => 'company',
						),
					),
				),
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
				'key' => 'field_5787d8c867696',
				'label' => 'Primary Contact',
				'name' => 'rep_by_primary_contact',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array (
					array (
						array (
							'field' => 'field_5787d83567693',
							'operator' => '==',
							'value' => 'company',
						),
					),
				),
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
				'key' => 'field_5787d8d867697',
				'label' => 'Phone Number',
				'name' => 'rep_by_phone_number',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array (
					array (
						array (
							'field' => 'field_5787d83567693',
							'operator' => '==',
							'value' => 'company',
						),
					),
				),
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
				'key' => 'field_5787d8f867698',
				'label' => 'Email Address',
				'name' => 'rep_by_email_address',
				'type' => 'email',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array (
					array (
						array (
							'field' => 'field_5787d83567693',
							'operator' => '==',
							'value' => 'company',
						),
					),
				),
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array (
				'key' => 'field_5787d92d67699',
				'label' => 'Capabilities',
				'name' => 'capabilities',
				'type' => 'taxonomy',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'taxonomy' => 'vendor-tag',
				'field_type' => 'checkbox',
				'allow_null' => 0,
				'add_term' => 1,
				'save_terms' => 1,
				'load_terms' => 1,
				'return_format' => 'id',
				'multiple' => 0,
			),
			array (
				'key' => 'field_5787d9506769a',
				'label' => 'Visual Samples',
				'name' => 'visual_samples',
				'type' => 'repeater',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'collapsed' => 'field_5787d99a6769d',
				'min' => '',
				'max' => '',
				'layout' => 'block',
				'button_label' => 'Add Sample',
				'sub_fields' => array (
					array (
						'key' => 'field_5787d99a6769d',
						'label' => 'Sample Type',
						'name' => 'sample_type',
						'type' => 'select',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'choices' => array (
							'Image' => 'Image',
							'Video (Embed)' => 'Video (Embed)',
							'Website' => 'Website',
						),
						'default_value' => array (
						),
						'allow_null' => 0,
						'multiple' => 0,
						'ui' => 0,
						'ajax' => 0,
						'placeholder' => '',
						'disabled' => 0,
						'readonly' => 0,
					),
					array (
						'key' => 'field_5787d9786769b',
						'label' => 'Image',
						'name' => 'image',
						'type' => 'image',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array (
							array (
								array (
									'field' => 'field_5787d99a6769d',
									'operator' => '==',
									'value' => 'Image',
								),
							),
						),
						'wrapper' => array (
							'width' => '',
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
						'key' => 'field_5787d9846769c',
						'label' => 'Video',
						'name' => 'video',
						'type' => 'oembed',
						'instructions' => 'Just paste URL to YouTube, Vimeo, etc here.',
						'required' => 0,
						'conditional_logic' => array (
							array (
								array (
									'field' => 'field_5787d99a6769d',
									'operator' => '==',
									'value' => 'Video (Embed)',
								),
							),
						),
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'width' => '',
						'height' => '',
					),
					array (
						'key' => 'field_5787ff68659d9',
						'label' => 'Description',
						'name' => 'description',
						'type' => 'text',
						'instructions' => 'A short description, if necessary. Two sentences at most.',
						'required' => 0,
						'conditional_logic' => array (
							array (
								array (
									'field' => 'field_5787d99a6769d',
									'operator' => '==',
									'value' => 'Image',
								),
							),
							array (
								array (
									'field' => 'field_5787d99a6769d',
									'operator' => '==',
									'value' => 'Video (Embed)',
								),
							),
						),
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
						'key' => 'field_578d3c6414df1',
						'label' => 'Website',
						'name' => 'website',
						'type' => 'url',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array (
							array (
								array (
									'field' => 'field_5787d99a6769d',
									'operator' => '==',
									'value' => 'Website',
								),
							),
						),
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
					),
					array (
						'key' => 'field_578d3c7514df2',
						'label' => 'Website Screenshot',
						'name' => 'website_image',
						'type' => 'image',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => array (
							array (
								array (
									'field' => 'field_5787d99a6769d',
									'operator' => '==',
									'value' => 'Website',
								),
							),
						),
						'wrapper' => array (
							'width' => '',
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
				),
			),
			array (
				'key' => 'field_578e44f310a10',
				'label' => 'Signed Vendor Agreement?',
				'name' => 'vendor_agreement',
				'type' => 'true_false',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => 'Check if yes.',
				'default_value' => 0,
			),
			array (
				'key' => 'field_578d41ef14df8',
				'label' => 'Vendor Rating',
				'name' => 'vendor_rating',
				'type' => 'radio',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array (
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4,
					5 => 5,
				),
				'allow_null' => 0,
				'other_choice' => 0,
				'save_other_choice' => 0,
				'default_value' => '',
				'layout' => 'horizontal',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'vendors',
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

	class Srs_Intranet_Vendor_Database {

		/**
		 * Updated dashboard to "draft" when module is inactive.
		 *
		 * @since 3.0.0
		 */
		public static function create_vendors_dashboard() {
			Srs_Intranet_Admin::update_pages('vendors-dash', 'dashboard');
		}

		public static function create_vendors_add_edit_page() {
			Srs_Intranet_Admin::update_pages('vendors-edit', 'vendors-dash', 'dashboard');
		}

	}

endif;
