<?php
/*
 * This file will remove the user custom metadata if checked in options 
 * and than will remove the plugin option from the database
 */

// First let's remove the custom meta data created by plugin *user choice*
if( get_option( 'limited_account_remove_meta' ) == '1'  ){
  		$users = get_users();
    		foreach ($users as $user) {
        		delete_user_meta($user->ID, 'activation_date');
            	delete_user_meta($user->ID, 'account_status');
    		}
}

// Than remove all options related to plugin
delete_option( 'limited_account_time_duration' );
delete_option( 'limited_account_error_message' );
delete_option( 'limited_account_remove_meta' );
?>