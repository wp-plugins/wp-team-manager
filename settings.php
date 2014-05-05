<?php
// create custom plugin settings menu
add_action('admin_menu', 'tm_create_menu');

function tm_create_menu() {

	add_submenu_page( 'edit.php?post_type=team_manager', 'Settings', 'Settings', 'manage_options', 'team_manager', 'team_manager_setting_function' );

	//call register settings function
	add_action( 'admin_init', 'register_mysettings' );

}

function register_mysettings() {
	//register our settings
	register_setting( 'tm-settings-group', 'tm_social_size' );
	register_setting( 'tm-settings-group', 'tm_link_new_window' );
	register_setting( 'tm-settings-group', 'tm_custom_css' );
}

function team_manager_setting_function() {
?>
<div class="wrap"><div id="icon-tools" class="icon32"></div>
<h2>Team Manager Settings</h2>

<?php settings_errors(); ?>

<form method="post" action="options.php">
    <?php settings_fields( 'tm-settings-group' ); ?>
    <?php do_settings_sections( 'tm-settings-group' ); ?>
    <?php 
    $tm_social_size = get_option('tm_social_size');
    $tm_custom_css = get_option('tm_custom_css');
    $tm_link_new_window = get_option('tm_link_new_window');
     ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row"><label>Social Icons</label></th>
        <td>
			<select name="tm_social_size" id="tm_social_size">
				<option <?php if($tm_social_size==16){ echo 'selected';} ?> value="16">16 px</option>
				<option <?php if($tm_social_size==32){ echo 'selected';} ?> value="32">32 px</option>  
			</select>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><label>Open Links On New Window</label></th>
        <td>
				<input type="checkbox" name="tm_link_new_window" value="True" <?php if($tm_link_new_window=='True'){ echo 'checked';} ?>> Yes
        </td>
        </tr>        
        <tr valign="top">
        <th scope="row"><label>Custom Css:</label></th>
        <td>
        	
        	<textarea name="tm_custom_css" id="tm_custom_css" class="wp-editor-area" rows="10" cols="80"><?php echo $tm_custom_css; ?></textarea>
       		
        </td>
        </tr>       
         
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } ?>
