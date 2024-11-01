<?php
/*
 * This file manges the account limitations during the user login 
 */

// Check user account status and activation date on login
function limited_account_login_controler($user_login, $user) {
	
  	// Get the neccessery data to work with
	$activation_date = get_user_meta($user->ID, 'activation_date', 'TRUE');
	$account_status = get_user_meta($user->ID, 'account_status', 'TRUE');
   	$current_date = date('d/m/Y H:i:s');
	if( get_option( 'limited_account_time_duration' ) ){	
  		$limited_account_settings = get_option( 'limited_account_time_duration' );
   	}else{
		$limited_account_settings = 2592000; // 1 month
	}
  	// Check first that is not a user manager trying to login
	if( $user->has_cap('edit_users') ){
     	return;
     	}
 
  	// Check first of the account wasn't deactivated already
  	elseif ( $account_status == 'Diactivated' ){
       	wp_logout();
       	wp_redirect( wp_login_url() . '?account_status=deactivated' );
     	exit;
	}
  
	// First time login so add the date and time stamp and update account status
	if ( $activation_date == '0' ){
      	update_user_meta( $user->ID, 'activation_date', $current_date );
		update_user_meta( $user->ID, 'account_status', 'Activated' );
       
	// Not first time logging in so check the date and time to see if the account is still valid
	} elseif ( $activation_date !== '0' ){
		$first_date = new DateTime($activation_date);
		$second_date = new DateTime($current_date);
		$diffrance = $second_date->getTimestamp() - $first_date->getTimestamp();
		
       	// If account has expired than update account status and prevent login
		if ( $diffrance > $limited_account_settings ){
			update_user_meta( $user->ID, 'account_status', 'Diactivated' );
			wp_logout();
            	wp_redirect( wp_login_url() . '?account_status=deactivated' );
     		exit;
		}
	}
  
}
add_action('wp_login', 'limited_account_login_controler', 10, 2);
?>