<?php
/*
Plugin Name: WordPress Team Manager
Plugin URI: http://www.dynamicweblab.com/
Description: This plugin allows you to manage the members of your team or staff and display them using shortcode.
Author: Maidul
Version: 1.6.1
Author URI:http://www.dynamicweblab.com/
Text Domain: wp-team-manager
Domain Path: /languages/
License: GPL2
*/
if (!defined('WTM_VERSION_KEY'))
    define('WTM_VERSION_KEY', 'wtm_version');

if (!defined('WTM_VERSION_NUM'))
    define('WTM_VERSION_NUM', '1.6.1');

if ( ! defined( 'WTM_BASE_FILE' ) )
    define( 'WTM_BASE_FILE', __FILE__ );

if ( ! defined( 'WTM_BASE_DIR' ) )
    define( 'WTM_BASE_DIR', dirname( WTM_BASE_FILE ) );

if (!defined('WTM_PLUGIN_DIR'))
    define( 'WTM_PLUGIN_DIR', dirname(__FILE__).'/' );

$new_version='1.6.1';

if (get_option(WTM_VERSION_KEY) != $new_version) {
    // Execute your upgrade logic here
  function wtm_activate() {
  $get_wtm_version = get_option( 'wtm_version' );
  $tm_custom_template = get_option( 'tm_custom_template' );

  if($get_wtm_version!='1.5.9' AND !empty($tm_custom_template)){
    delete_option( 'tm_custom_template' );
  }

  }
  register_activation_hook( __FILE__, 'wtm_activate' );

    // Then update the version value
    update_option(WTM_VERSION_KEY, $new_version);
}


//include language

function wtm_load_textdomain()
{
// Localization

load_plugin_textdomain('wp-team-manager', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

}

// Add actions
add_action('plugins_loaded', 'wtm_load_textdomain');

//include Meta Box Class
require_once WTM_PLUGIN_DIR . '/meta-box.php';

//include Post Type
require_once WTM_PLUGIN_DIR . '/inc/customposttype.php';

//include Shortcode Generator
require_once WTM_PLUGIN_DIR . '/shortcode-generator.php';

//include Settings
require_once WTM_PLUGIN_DIR . '/settings.php';

/**
 * Check if the current theme have feature image support.If not then enable the support
 *
 * @since 1.0.0
 * 
 * 
 */
function tm_add_thumbnail_support() {
  if(!current_theme_supports('post-thumbnails')) {
    add_theme_support( 'post-thumbnails', array( 'team_manager' ) );
  }
}
add_action('init', 'tm_add_thumbnail_support');

/**
 * Get feature image from team_manager post type
 *
 * @since 1.0
 *
 *
 */
function wptm_get_featured_image($post_ID) {
    $post_thumbnail_id = get_post_thumbnail_id($post_ID);
    if ($post_thumbnail_id) {
        $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'thumbnail');
        return $post_thumbnail_img[0];
    }
}
/**
 * Add new feature image column
 *
 * @since 1.5
 *
 *
 */
function wptm_columns_head($defaults) {
    $defaults['featured_image'] = __('Featured Image');
    return $defaults;
}
 /**
 * Show feature image on the admin
 *
 * @since 1.5
 *
 *
 */
function wptm_columns_content($column_name, $post_ID) {
    if ($column_name == 'featured_image') {
        $post_featured_image = wptm_get_featured_image($post_ID);
        if ($post_featured_image) {
            echo '<img src="' . $post_featured_image . '" />';
        }
    }
}

add_filter('manage_team_manager_posts_columns', 'wptm_columns_head');
add_action('manage_team_manager_posts_custom_column', 'wptm_columns_content', 10, 2);

 /**
 * Show team member id on the admin section
 *
 * @since 1.5
 *
 *
 */

function team_manager_posts_columns_id($defaults){
    $defaults['wps_post_id'] = __('ID');
    return $defaults;
}
function team_manager_posts_custom_id_columns($column_name, $id){
  if($column_name === 'wps_post_id'){
          echo $id;
    }
}

add_filter('manage_team_manager_posts_columns', 'team_manager_posts_columns_id', 5);
add_action('manage_team_manager_posts_custom_column', 'team_manager_posts_custom_id_columns', 5, 2);

/**
 * Admin css
 *
 * @since 1.0
 * 
 * Disable from version 1.6.1
 */

function team_manager_admin_css() {
    global $post_type;
    $post_types = array(
                        'team_manager'
                  );
    if(in_array($post_type, $post_types))
    echo '<style type="text/css">#post-preview, #view-post-btn{display: none;}</style>';
}
//add_action( 'admin_head-post-new.php', 'team_manager_admin_css' );
//add_action( 'admin_head-post.php', 'team_manager_admin_css' );


 /**
 * Get the custom template if is set
 *
 * @since 1.0
 */
 
function team_manager_get_template_hierarchy( $template ) {
 
    // Get the template slug
    $template_slug = rtrim( $template, '.php' );
    $template = $template_slug . '.php';
 
    // Check if a custom template exists in the theme folder, if not, load the plugin template file
    if ( $theme_file = locate_template( array( 'team_template/' . $template ) ) ) {
        $file = $theme_file;
    }
    else {
        $file = WTM_BASE_DIR . '/templates/' . $template;
    }
 
    return apply_filters( 'team_manager_template_' . $template, $file );
}
 
 
/**
 * Returns template file
 *
 * @since 1.6.1
 */
 
add_filter( 'template_include', 'team_manager_template_chooser');

function team_manager_template_chooser( $template ) {
 
    // Post ID
    $post_id = get_the_ID();
 
    // For all other CPT
    if ( get_post_type( $post_id ) != 'team_manager' ) {
        return $template;
    }
 
    // Else use custom template
    if ( is_single() ) {
        return team_manager_get_template_hierarchy( 'single' );
    }
 
}

?>
