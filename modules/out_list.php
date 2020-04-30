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

$active = Srs_Intranet_Admin::activate_modules( 'out_list' );

if ( $active == 1 ) :

	class Srs_Intranet_Out_List{

		/**
		 * Add "nice name" for module
		 *
		 * @since 4.1.0
		 */
		static function nice_name() {
			return 'Out List';
		}

		/**
		 * Register Custom Post Type - Out List
		 *
		 * @since 2.0.0
		 */
		public static function out_list_post_type() {
			Srs_Intranet_Admin::custom_post_types( 'Out Lists', 'Out List', 'outlist', 4, 'dashicons-palmtree');
		}

		/**
		 * Creates the Out List page.
		 *
		 * @since 2.0.0
		 */
		function create_outlist_dashboard() {
			Srs_Intranet_Admin::create_pages('Out List', 'out-list', 'dashboard');
		}

		/**
		 * Assigns custom template to Out List
		 *
		 * @since 2.0.0
		 */
		function outlist_template( $page_template ) {
			if ( is_page( 'out-list' ) )
		        $page_template = SRS_FILE_PATH . '/public/templates/out-list.php';

		    return $page_template;
		}

		/**
		 * Assigns custom template to out list single post pages.
		 *
		 * @since 2.0.0
		 */
		function outlist_single_template( $single ) {
			global $wp_query, $post;

		    if ($post->post_type == 'outlist') :
		        if(file_exists(SRS_FILE_PATH . '/public/templates/out-list-single.php'))
		            return SRS_FILE_PATH . '/public/templates/out-list-single.php';
		    endif;

		    return $single;
		}

		/*------------------------------------*\
		    Cleanup
		\*------------------------------------*/

		/**
		 * Auto delete past dates
		 *
		 * @since 3.0.1
		 */
		function remove_past_dates() {
			$today = date('Ymd');

			$args = array(
		        'post_type'      => 'outlist',
		        'posts_per_page' => -1,
		        'meta_query'     => array(
		        	array(
		        		'key' => 'list_date',
		        		'value' => $today,
		        		'compare' => '<'
		        	)
		        )
		    );
		    $query_ads = new WP_Query($args);

		    if ($query_ads->have_posts()) : while($query_ads->have_posts()): $query_ads->the_post();
	            wp_delete_post(get_the_ID(), true);
		    endwhile; endif;
		}

		/**
		 * Schedule the auto delete
		 *
		 * @since 3.0.1
		 */
		function register_remove_past_dates() {
		    if( !wp_next_scheduled( 'expired_post_delete' ) )
		        wp_schedule_event( time(), 'daily', 'expired_post_delete' );

		}

	}

	/**
	 * Adds custom fields to both custom post types
	 *
	 * @since 1.0.0
	 */
	if( function_exists('acf_add_local_field_group') ):

		acf_add_local_field_group(array (
			'key'    => 'group_56f94bc91af2d',
			'title'  => 'Out List',
			'fields' => array (
				array (
					'display_format'    => 'F j, Y',
					'return_format'     => 'Ymd',
					'first_day'         => 1,
					'key'               => 'field_56f99de64e5b0',
					'label'             => 'List Date',
					'name'              => 'list_date',
					'type'              => 'date_picker',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array (
						'width' => '',
						'class' => '',
						'id'    => '',
					),
				),
				array (
					'sub_fields' => array (
						array (
							'post_type' => array (
								0 => 'people',
							),
							'taxonomy' => array (
							),
							'allow_null'        => 0,
							'multiple'          => 1,
							'return_format'     => 'object',
							'ui'                => 1,
							'key'               => 'field_56f94bdfe0ab4',
							'label'             => 'Name',
							'name'              => 'name',
							'type'              => 'post_object',
							'instructions'      => '',
							'required'          => 1,
							'conditional_logic' => 0,
							'wrapper'           => array (
								'width' => '50%',
								'class' => '',
								'id'    => '',
							),
						),
						array (
							'layout'  => 'horizontal',
							'choices' => array (
								'Appointment'      => 'Appointment',
								'Out With Clients' => 'Out With Clients',
								'Sick'             => 'Sick',
								'Vacation'         => 'Vacation',
								'Work From Home'   => 'Work From Home',
							),
							'default_value' => array (
							),
							'allow_custom'      => 0,
							'save_custom'       => 0,
							'toggle'            => 0,
							'return_format'     => 'value',
							'key'               => 'field_56f94c02e0ab5',
							'label'             => 'Reason',
							'name'              => 'reason',
							'type'              => 'checkbox',
							'instructions'      => '',
							'required'          => 1,
							'conditional_logic' => 0,
							'wrapper'           => array (
								'width' => '50%',
								'class' => '',
								'id'    => '',
							),
						),
						array (
							'default_value'     => '',
							'maxlength'         => '',
							'placeholder'       => '',
							'prepend'           => '',
							'append'            => '',
							'key'               => 'field_56f94c5ae0ab6',
							'label'             => 'Notes',
							'name'              => 'notes',
							'type'              => 'text',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => array (
								array (
									array (
										'field'    => 'field_56f94c02e0ab5',
										'operator' => '!=',
										'value'    => 'Out With Clients',
									),
								),
							),
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
							'maxlength'         => '',
							'placeholder'       => '',
							'prepend'           => '',
							'append'            => '',
							'key'               => 'field_5899f53e837c1',
							'label'             => 'Client',
							'name'              => 'clients',
							'type'              => 'text',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => array (
								array (
									array (
										'field'    => 'field_56f94c02e0ab5',
										'operator' => '==',
										'value'    => 'Out With Clients',
									),
								),
							),
							'wrapper' => array (
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'readonly' => 0,
							'disabled' => 0,
						),
					),
					'min'               => 0,
					'max'               => 0,
					'layout'            => 'block',
					'button_label'      => 'Add Person',
					'collapsed'         => 'field_56f94bdfe0ab4',
					'key'               => 'field_56f94bcde0ab3',
					'label'             => 'Out List',
					'name'              => 'out_list',
					'type'              => 'repeater',
					'instructions'      => 'Select the persons name(s), their reason for being out and if there it a note (IE: Out of appt. 9 - 10:30).',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array (
						'width' => '',
						'class' => '',
						'id'    => '',
					),
				),
				array (
					'default_value'     => '',
					'new_lines'         => 'wpautop',
					'maxlength'         => '',
					'placeholder'       => '',
					'rows'              => '',
					'key'               => 'field_56f94c97e0ab7',
					'label'             => 'Special Message',
					'name'              => 'special_message',
					'type'              => 'textarea',
					'instructions'      => 'Such as happy birthday, happy wedding, etc.',
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
			),
			'location' => array (
				array (
					array (
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'outlist',
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
				10 => 'page_attributes',
				11 => 'featured_image',
				12 => 'categories',
				13 => 'tags',
				14 => 'send-trackbacks',
			),
			'active'      => 1,
			'description' => '',
		));

	endif;

else :

	class Srs_Intranet_Out_List {

		/**
		 * Updated dashboard to "draft" when module is inactive.
		 *
		 * @since 3.0.0
		 */
		public static function create_outlist_dashboard() {
			Srs_Intranet_Admin::update_pages('out-list', 'dashboard');
		}

	}

endif;
