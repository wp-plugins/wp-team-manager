<?php

/**
 * Add css file on theme
 *
 * @since 1.0
 */

function team_manager_add_css() {
            wp_register_style( 'team-manager-style', plugins_url( '/css/tm-style.css', __FILE__ ),array(),false,false);
            wp_enqueue_style( 'team-manager-style' );  

    }
add_action( 'wp_head', 'team_manager_add_css' ); 

/**
 * Add custom css on theme header
 *
 * @since 1.0
 */


function team_manager_add_custom_css()
    {
       echo '<style type="text/css" media="screen">';
       echo get_option('tm_custom_css');
       echo '</style>'; 
    } 
add_action( 'wp_head', 'team_manager_add_custom_css' ); 

/**
 * create plugin settings menu
 *
 * @since 1.0
 */

add_action('admin_menu', 'tm_create_menu');

function tm_create_menu() {

	add_submenu_page( 'edit.php?post_type=team_manager', 'Settings', 'Settings', 'manage_options', 'team_manager', 'team_manager_setting_function' );

	//call register settings function
	add_action( 'admin_init', 'register_tm_settings' );

}
/**
 * Register settings menu
 *
 * @since 1.0
 */
function register_tm_settings() {
	//register team manager settings
	register_setting( 'tm-settings-group', 'tm_social_size' );
	register_setting( 'tm-settings-group', 'tm_link_new_window' );
    register_setting( 'tm-settings-group', 'single_team_member_view' );
	register_setting( 'tm-settings-group', 'tm_custom_css' );
    register_setting( 'tm-settings-group', 'tm_custom_template' );
}
/**
 * Register settings function
 *
 * @since 1.0
 */
function team_manager_setting_function() {
?>
<div class="wrap">
<h2><?php _e('Team Manager settings', 'wp-team-manager'); ?></h2>

<?php settings_errors(); ?>

<form method="post" action="options.php">
    <?php settings_fields( 'tm-settings-group' ); ?>
    <?php do_settings_sections( 'tm-settings-group' ); ?>
    <?php 
    $tm_social_size = get_option('tm_social_size');
    $tm_custom_css = get_option('tm_custom_css');
    $tm_link_new_window = get_option('tm_link_new_window');
    $single_team_member_view = get_option('single_team_member_view');
    $tm_custom_template = get_option('tm_custom_template');
    if (empty($tm_custom_template)) {
    $tm_custom_template='
    <div class="%layout%">
    <div class="team-member-info">
    %image%
     %sociallinks%
    </div><div class="team-member-des">
    <h2 class="team-title">%title%</h2>
    <h4 class="team-position">%jobtitle%</h4>
    %content%
    %otherinfo%
    </div>
    </div>';
    }
     ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row"><label><?php _e('Social icon size','wp-team-manager'); ?></label></th>
        <td>
			<select name="tm_social_size" id="tm_social_size">
				<option <?php if($tm_social_size==16){ echo 'selected';} ?> value="16">16 px</option>
				<option <?php if($tm_social_size==32){ echo 'selected';} ?> value="32">32 px</option>  
			</select>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><label><?php _e('Open social links on new window','wp-team-manager'); ?></label></th>
        <td>
				<input type="checkbox" name="tm_link_new_window" value="True" <?php if($tm_link_new_window=='True'){ echo 'checked';} ?>> Yes
        </td>
        </tr>               
         
        <tr valign="top">
        <th scope="row"><label><?php _e('Disable single team member view','wp-team-manager'); ?></label></th>
        <td>
                <input type="checkbox" name="single_team_member_view" value="True" <?php if($single_team_member_view=='True'){ echo 'checked';} ?>> Yes
        </td>
        </tr> 


    </table>

    <!-- Template -->
    <h3 class="wptm_title"><?php _e('HTML Template', 'wp-team-manager'); ?></h3>
    <p><?php _e('Edit the HTML template if you want to customize it.', 'wp-team-manager'); ?></p>
    <p><?php _e('Here is the list of available tags.', 'wp-team-manager'); ?></p>
    <p><?php _e('<code>%title%</code> , <code>%content%</code> , <code>%image%</code>, <code>%sociallinks%</code>, <code>%jobtitle%</code>, <code>%%otherinfo%%</code>', 'wp-team-manager'); ?></p>
    <textarea name="tm_custom_template" id="tm_custom_template" class="wp-editor-area" rows="10" cols="80"><?php echo $tm_custom_template; ?></textarea>

    <!-- Custom CSS -->
    <h3 class="wptm_title"><?php _e('CSS', 'wp-team-manager'); ?></h3>
    <p><?php _e('Add custom CSS for Team Manager', 'wp-team-manager'); ?></p>
    <textarea name="tm_custom_css" id="tm_custom_css" class="wp-editor-area" rows="10" cols="80"><?php echo $tm_custom_css; ?></textarea>   

    <?php submit_button(); ?>

</form>

    <!-- Support -->
    <div id="wptm_support">
        <h3><?php _e('Support & bug report', 'wp-team-manager'); ?></h3>
        <p><?php printf(__('If you have some idea to improve this plugin or any bug to report, please email me at : <a href="%1$s">%2$s</a>', 'wp-team-manager'), 'mailto:info@dynamicweblab.com?subject=[wp-team-manager]', 'info@dynamicweblab.com'); ?></p>
        <p><?php printf(__('You like this plugin ? Then please provide some support by <a href="%1$s" target="_blank">voting for it</a> and/or says that <a href="%2$s" target="_blank">it works</a> for your WordPress installation on the official WordPress plugins repository.', 'wp-team-manager'), 'http://wordpress.org/plugins/wp-team-manager/', 'http://wordpress.org/plugins/wp-team-manager/'); ?></p>
    </div>

</div>
<?php } ?>
