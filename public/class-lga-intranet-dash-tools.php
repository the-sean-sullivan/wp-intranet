<?php

/**
 * The dashboard tools functionality of the plugin.
 *
 * @link       https://seansdesign.net
 * @since      1.0.0
 *
 * @package    Srs_Intranet
 * @subpackage Srs_Intranet/public
 */

include_once SRS_FILE_PATH . '/admin/class-srs-intranet-admin.php';

class Srs_Intranet_Dash_Tools {

	/**
	 * Register Custom Post Type - Events
	 *
	 * @since 1.0.0
	 */
	function events_post_type() {
        Srs_Intranet_Admin::custom_post_types( 'Events', 'Event', 'events', 6, 'dashicons-calendar-alt');
	}

	/**
	 * Register Custom Taxonomy - Events (Category Type)
	 *
	 * @since 1.0.0
	 */
	function dash_events_taxonomies() {
        Srs_Intranet_Admin::register_taxonomies( 'Event Type', 'event-type', 'events' );
	}

}

/*------------------------------------*\
    ACF Fields
\*------------------------------------*/


/**
 * Adds custom fields to both custom post types
 *
 * @since 1.0.0
 */
if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array (
        'key' => 'group_56fe80f0ca42f',
        'title' => 'Dashboard - Events',
        'fields' => array (
            array (
                'key' => 'field_56fe80f7316b5',
                'label' => 'Event Date',
                'name' => 'event_date',
                'type' => 'date_picker',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array (
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'display_format' => 'F j, Y',
                'return_format' => 'Ymd',
                'first_day' => 0,
            ),
            array (
                'key' => 'field_56fe81c7316b8',
                'label' => 'Event Type',
                'name' => 'event_type',
                'type' => 'taxonomy',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array (
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'taxonomy' => 'event-type',
                'field_type' => 'select',
                'allow_null' => 0,
                'add_term' => 1,
                'save_terms' => 1,
                'load_terms' => 0,
                'return_format' => 'object',
                'multiple' => 0,
            ),
            array (
                'key' => 'field_56fe812a316b7',
                'label' => 'Event Info',
                'name' => 'event_info',
                'type' => 'wysiwyg',
                'instructions' => 'This field is optional',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array (
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'basic',
                'media_upload' => 0,
            ),
        ),
        'location' => array (
            array (
                array (
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'events',
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
