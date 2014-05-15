<?php
/*
Plugin Name: WordPress Team Manager
Plugin URI: http://www.dynamicweblab.com/
Description: This plugin allows you to manage the members of your team or staff and display them using shortcode.
Author: Dynamic Web Lab
Version: 1.5.4
Author URI:http://www.dynamicweblab.com/
License: GPL2
*/

if (!defined('WTM_VERSION_KEY'))
    define('WTM_VERSION_KEY', 'wtm_version');

if (!defined('WTM_VERSION_NUM'))
    define('WTM_VERSION_NUM', '1.5.4');

add_option(WTM_VERSION_KEY, WTM_VERSION_NUM);

define( 'PLUGIN_DIR', dirname(__FILE__).'/' );

//include language

load_plugin_textdomain('team_manager', false, basename( dirname( __FILE__ ) ) . '/languages' );

//include Meta Box Class
require_once PLUGIN_DIR . '/meta-box.php';

//include Shortcode Generator
require_once PLUGIN_DIR . '/shortcode-generator.php';

//include Settings
require_once PLUGIN_DIR . '/settings.php';

// add feature image on team_manager post type

if ( function_exists( 'add_theme_support' ) ) { 
add_theme_support( 'post-thumbnails', array( 'team_manager' ) ); 
}


function team_manager_featured_image_alttext($translation, $text, $domain) {
    global $post;
    if (is_object($post)){
		if ($post->post_type == 'team_manager') {
				$translations = get_translations_for_domain( $domain);
				if ( $text == 'Featured Image')
					return $translations->translate( 'Team Member Picture' );
				if ( $text == 'Set featured image')
					return $translations->translate( 'Select an image' );         
		}
    }
    return $translation;
}
add_filter('gettext', 'team_manager_featured_image_alttext', 10, 4);

//Adding the necessary actions
add_action('init', 'register_team_manager' );

//register the custom post type for the team manager
function register_team_manager() {

    $labels = array( 
        'name' => _x( 'Team', 'team_manager' ),
        'singular_name' => _x( 'Team Member', 'team_manager' ),
        'add_new' => _x( 'Add New Member', 'team_manager' ),
        'add_new_item' => _x( 'Add New ', 'team_manager' ),
        'edit_item' => _x( 'Edit Team Member ', 'team_manager' ),
        'new_item' => _x( 'New Team Member', 'team_manager' ),
        'view_item' => _x( 'View Team Members', 'team_manager' ),
        'search_items' => _x( 'Search Team Members', 'team_manager' ),
        'not_found' => _x( 'Not found any Team Member', 'team_manager' ),
        'not_found_in_trash' => _x( 'No Team Member found in Trash', 'team_manager' ),
        'parent_item_colon' => _x( 'Parent Team Member:', 'team_manager' ),
        'menu_name' => _x( 'Team', 'team_manager' ),
    );
	
    $args = array( 
        'labels' => $labels,
        'hierarchical' => false,        
        'supports' => array( 'title', 'thumbnail','editor','page-attributes'),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,       
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => true,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post',
		'menu_icon' => plugins_url( 'img/icon16.png',__FILE__),
		'rewrite' => array( 'slug' => 'team-manager' )

    );

    register_post_type( 'team_manager', $args );

    //register custom category for the team manager

    $labels = array(
        'name'                       => _x( 'Groups', 'team_manager' ),
        'singular_name'              => _x( 'Group', 'team_manager' ),
        'search_items'               => __( 'Search Groups' ),
        'popular_items'              => __( 'Popular Groups' ),
        'all_items'                  => __( 'All Groups' ),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'edit_item'                  => __( 'Edit Group' ),
        'update_item'                => __( 'Update Group' ),
        'add_new_item'               => __( 'Add New Group' ),
        'new_item_name'              => __( 'New Group Name' ),
        'separate_items_with_commas' => __( 'Separate Groups with commas' ),
        'add_or_remove_items'        => __( 'Add or remove Groups' ),
        'choose_from_most_used'      => __( 'Choose from the most used Groups' ),
        'not_found'                  => __( 'No Groups found.' ),
        'menu_name'                  => __( 'Team Groups' ),
    );

    $args = array(
        'hierarchical'          => true,
        'labels'                => $labels,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var'             => true,
        'rewrite'               => array( 'slug' => 'team_groups' ),
    );

    register_taxonomy( 'team_groups', 'team_manager', $args );

}

// add VCF file type upload support

add_filter('upload_mimes', 'custom_upload_mimes');
function custom_upload_mimes ( $existing_mimes=array() ) {
    // add your extension to the array
    $existing_mimes['vcf'] = 'text/x-vcard';
    return $existing_mimes;
}


/********************* BEGIN DEFINITION OF META BOXES ***********************/

$prefix = 'tm_';

$meta_boxes = array();

$meta_boxes[] = array(
    'id' => 'team_personal',                         // meta box id, unique per meta box
    'title' => 'Team Member Information',          // meta box title
    'pages' => array('team_manager'),  // post types, accept custom post types as well, default is array('post'); optional
    'context' => 'normal',                      // where the meta box appear: normal (default), advanced, side; optional
    'priority' => 'high',                       // order of meta box: high (default), low; optional

    'fields' => array(                          // list of meta fields
        
        array(
            'name' => 'Job Title',                  // field name
            'desc' => 'Job title of this team member.', // field description, optional
            'id' => $prefix . 'jtitle',              // field id, i.e. the meta key
            'type' => 'text',                       // text box
            'std' => 'Web Designer'                    // default value, optional
        ),
        array(
            'name' => 'Telephone',                  // field name
            'desc' => 'Telephone no of this team member.', // field description, optional
            'id' => $prefix . 'telephone',              // field id, i.e. the meta key
            'type' => 'text',                       // text box
            'std' => ''                    // default value, optional
        ),
        array(
            'name' => 'Location',                  // field name
            'desc' => 'Location of this team member.', // field description, optional
            'id' => $prefix . 'location',              // field id, i.e. the meta key
            'type' => 'text',                       // text box
            'std' => ''                    // default value, optional
        ),
        array(
            'name' => 'Web URL',                  // field name
            'desc' => 'Website url of this team member.', // field description, optional
            'id' => $prefix . 'web_url',              // field id, i.e. the meta key
            'type' => 'text',                       // text box
            'std' => ''                    // default value, optional
        ),                
        array(
            'name' => 'VCARD',
            'desc' => 'Upload your VCARD',
            'id' => $prefix . 'vcard',
            'type' => 'file'                        // file upload
        )
    )
);

// first meta box
$meta_boxes[] = array(
    'id' => 'team_social',                         // meta box id, unique per meta box
    'title' => 'Social Profile',          // meta box title
    'pages' => array('team_manager'),  // post types, accept custom post types as well, default is array('post'); optional
    'context' => 'normal',                      // where the meta box appear: normal (default), advanced, side; optional
    'priority' => 'high',                       // order of meta box: high (default), low; optional

    'fields' => array(                          // list of meta fields
        
        array(
            'name' => 'Facebook',                  // field name
            'desc' => 'Facebook profile or page link.', // field description, optional
            'id' => $prefix . 'flink',              // field id, i.e. the meta key
            'type' => 'text',                       // text box
            'std' => ''                    // default value, optional
        ),
        array(
            'name' => 'Twitter',                  // field name
            'desc' => 'Twitter profile link.', // field description, optional
            'id' => $prefix . 'tlink',              // field id, i.e. the meta key
            'type' => 'text',                       // text box
            'std' => ''                    // default value, optional
        ),
        array(
            'name' => 'LinkedIn',                  // field name
            'desc' => 'LinkedIn profile link.', // field description, optional
            'id' => $prefix . 'llink',              // field id, i.e. the meta key
            'type' => 'text',                       // text box
            'std' => ''                    // default value, optional
        ),
        array(
            'name' => 'Google Plus',                  // field name
            'desc' => 'Google Plus profile link.', // field description, optional
            'id' => $prefix . 'gplink',              // field id, i.e. the meta key
            'type' => 'text',                       // text box
            'std' => ''                    // default value, optional
        ),
        array(
            'name' => 'Dribbble',                  // field name
            'desc' => 'Dribbble profile link.', // field description, optional
            'id' => $prefix . 'dribbble',              // field id, i.e. the meta key
            'type' => 'text',                       // text box
            'std' => ''                    // default value, optional
        ),        
        array(
            'name' => 'Youtube',                  // field name
            'desc' => 'Youtube profile link.', // field description, optional
            'id' => $prefix . 'ylink',              // field id, i.e. the meta key
            'type' => 'text',                       // text box
            'std' => ''                    // default value, optional
        ),
        array(
            'name' => 'Vimeo',                  // field name
            'desc' => 'Vimeo profile link.', // field description, optional
            'id' => $prefix . 'vlink',              // field id, i.e. the meta key
            'type' => 'text',                       // text box
            'std' => ''                    // default value, optional
        ),
        array(
            'name' => 'Email',                  // field name
            'desc' => 'Email Id', // field description, optional
            'id' => $prefix . 'emailid',              // field id, i.e. the meta key
            'type' => 'text',                       // text box
            'std' => ''                    // default value, optional
        )
    )
);
/********************* BEGIN EXTENDING CLASS ***********************/

/**
 * Extend WTM_Meta_Box class
 * Add field type: 'taxonomy'
 */
class WTM_Meta_Box_Taxonomy extends WTM_Meta_Box {
    
    function add_missed_values() {
        parent::add_missed_values();
        
        // add 'multiple' option to taxonomy field with checkbox_list type
        foreach ($this->_meta_box['fields'] as $key => $field) {
            if ('taxonomy' == $field['type'] && 'checkbox_list' == $field['options']['type']) {
                $this->_meta_box['fields'][$key]['multiple'] = true;
            }
        }
    }
    
    // show taxonomy list
    function show_field_taxonomy($field, $meta) {
        global $post;
        
        if (!is_array($meta)) $meta = (array) $meta;
        
        $this->show_field_begin($field, $meta);
        
        $options = $field['options'];
        $terms = get_terms($options['taxonomy'], $options['args']);
        
        // checkbox_list
        if ('checkbox_list' == $options['type']) {
            foreach ($terms as $term) {
                echo "<input type='checkbox' name='{$field['id']}[]' value='$term->slug'" . checked(in_array($term->slug, $meta), true, false) . " /> $term->name<br/>";
            }
        }
        // select
        else {
            echo "<select name='{$field['id']}" . ($field['multiple'] ? "[]' multiple='multiple' style='height:auto'" : "'") . ">";
        
            foreach ($terms as $term) {
                echo "<option value='$term->slug'" . selected(in_array($term->slug, $meta), true, false) . ">$term->name</option>";
            }
            echo "</select>";
        }
        
        $this->show_field_end($field, $meta);
    }
}

foreach ($meta_boxes as $meta_box) {
    $wptm_box = new WTM_Meta_Box_Taxonomy($meta_box);
}

/********************* END DEFINITION OF META BOXES ***********************/


/********************* CSS enqueue functions ***********************/

function team_manager_add_css() {
            wp_register_style( 'team-manager-style', plugins_url( '/css/tm-style.css', __FILE__ ),array(),false,false);
            wp_enqueue_style( 'team-manager-style' );  

    }
add_action( 'wp_head', 'team_manager_add_css' ); 

function team_manager_add_custom_css()
    {
       echo '<style type="text/css" media="screen">';
       echo get_option('tm_custom_css');
       echo '</style>'; 
    } 
add_action( 'wp_head', 'team_manager_add_custom_css' ); 

?>
