<?php
/*
Plugin Name: WordPress Team Manager
Plugin URI: http://www.dynamicweblab.com/
Description: This plugin allows you to manage the members of your team or staff and display them using shortcode.
Author: Dynamic Web Lab
Version: 1.6.0
Author URI:http://www.dynamicweblab.com/
License: GPL2
*/
/*
  Copyright (c) 2013 Maidul Islam.

  This program is free software; you can redistribute it and/or
  modify it under the terms of the GNU General Public License
  as published by the Free Software Foundation; either version 2
  of the License, or (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */


if (!defined('WTM_VERSION_KEY'))
    define('WTM_VERSION_KEY', 'wtm_version');

if (!defined('WTM_VERSION_NUM'))
    define('WTM_VERSION_NUM', '1.6.0');

$new_version='1.6.0';

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


define( 'PLUGIN_DIR', dirname(__FILE__).'/' );

//include language

function wtm_load_textdomain()
{
// Localization

load_plugin_textdomain('wp-team-manager', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

}

// Add actions
add_action('init', 'wtm_load_textdomain');

//include Meta Box Class
require_once PLUGIN_DIR . '/meta-box.php';

//include Post Type
require_once PLUGIN_DIR . '/inc/customposttype.php';

//include Shortcode Generator
require_once PLUGIN_DIR . '/shortcode-generator.php';

//include Settings
require_once PLUGIN_DIR . '/settings.php';

/**
 * Add feature image on team_manager post type
 *
 * @since 0.1
 *
 *
 */
if ( function_exists( 'add_theme_support' ) ) { 
add_theme_support( 'post-thumbnails', array( 'team_manager' ) ); 
}

/**
 * GET feature image from team_manager post type
 *
 * @since 0.1
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

// ADD NEW COLUMN
function wptm_columns_head($defaults) {
    $defaults['featured_image'] = 'Featured Image';
    return $defaults;
}
 
// SHOW THE FEATURED IMAGE
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

// SHOW THE ID
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


function team_manager_admin_css() {
    global $post_type;
    $post_types = array(
                        'team_manager'
                  );
    if(in_array($post_type, $post_types))
    echo '<style type="text/css">#post-preview, #view-post-btn{display: none;}</style>';
}
add_action( 'admin_head-post-new.php', 'team_manager_admin_css' );
add_action( 'admin_head-post.php', 'team_manager_admin_css' );

?>
