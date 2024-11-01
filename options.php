<?php

// create custom plugin settings menu
function limited_account_create_menu() {

	//create new users sub menu
  	add_users_page('Limited Account Settings', 'Limited Account', 'edit_users', 'limited_account', 'limited_account_settings_page');

	//call register settings function
	add_action( 'admin_init', 'register_limited_account_settings' );
}
add_action('admin_menu', 'limited_account_create_menu');

function register_limited_account_settings() {
	// Account Time Duration
	register_setting( 'limited_account_settings_group', 'limited_account_time_duration' );
  	register_setting( 'limited_account_settings_group', 'limited_account_error_message' );
  	register_setting( 'limited_account_settings_group', 'limited_account_remove_meta');	
}

function limited_account_settings_page() {
?>
<div class="wrap">
	<h2>Limited Account</h2>

    <form method="post" action="options.php">
            <?php settings_fields( 'limited_account_settings_group' ); ?>
            <?php do_settings_sections( 'limited_account_settings_group' ); ?>
      	
                <table class="form-table">
                  <!-- Account Duration -->
                    <tr valign="top">
                    	<th scope="row">Account time duration limit</th>
                    	<td>
                          	<input type="text" name="limited_account_time_duration" value="<?php echo get_option('limited_account_time_duration'); ?>" size="10" style="display:block;" />
                      		<span style="font-style:italic;">Please insert the value in seconds ex: 60(s) = 1(m), 3600(s) = 1(h), 86400(s) = 1(d). Default is 2592000 = 1 month</span>
                         </td>
                     </tr>
                  <!-- Error Message -->
                  <tr valign="top">
                    	<th scope="row">Error Message For Expired Accounts</th>
                    	<td>
                          	<input type="text" name="limited_account_error_message" value="<?php echo get_option('limited_account_error_message'); ?>" size="50" style="display:block;"/>
                      		<span style="font-style:italic;">Insert the error message that the user will see on the login page if their account status is "Deactivated"</span>
                         </td>
                     </tr>
                  <!-- Remove meta data -->
                     <tr valign="top">
                      	<th scope="row">Remove user metadata on plugin removal</th>
                    	<td>
                         	<input type="checkbox" name="limited_account_remove_meta"  value="1"  <?php checked( get_option('limited_account_remove_meta') , 1 ); ?> style="display:block;"/>
                      		<span style="font-style:italic;">This option will remove all metadata created by this plugin on plugin removal.</span>
                         </td>
                    </tr>
			</table>
            <?php submit_button(); ?>
    </form>
  
</div>
<?php } ?>