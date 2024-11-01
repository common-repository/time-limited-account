<?php
/*
 * This file manages the account limitations if the user already loggedin
 */

// Check user account status and activation date while user is loggedin
function limited_account_loggedin_controler(){	
	
	// Get the neccessery data to work with
	$user_id = get_current_user_id();
	$activation_date = get_user_meta($user_id, 'activation_date', 'TRUE');
	$account_status = get_user_meta($user_id, 'account_status', 'TRUE');
   	$current_date = date('d/m/Y H:i:s');
	if( get_option( 'limited_account_time_duration' ) ){
  		$limited_account_settings = get_option( 'limited_account_time_duration' );
	}else{
		$limited_account_settings = 2592000; // 1 month
	}
   	
  	// Check first that is not a user manager trying to login
	if( is_user_logged_in() && current_user_can( 'edit_users' ) ){
     	return;
     }
  	
  	// Check if the user has no values for date and status and set them
  	elseif ( is_user_logged_in() && (empty( $activation_date ) or empty( $account_status )) ){
     	update_user_meta( $user_id, 'activation_date', $current_date );
       	update_user_meta( $user_id, 'account_status', 'Activated' );
     }
   	
  	// Check if the account has no date but the user is loggedin
  	elseif ( is_user_logged_in() && $activation_date == '0' ){
       	update_user_meta( $user_id, 'activation_date', $current_date );
  	}
  
  	// Check if the account wasn't deactivated already
  	elseif ( is_user_logged_in() && $account_status == 'Deactivated' ){
       	wp_logout();
       	wp_redirect( wp_login_url() . '?account_status=deactivated' );
     	exit;
  	}
  
  	// If account is still in active status than check expiery date
	elseif ( is_user_logged_in() && $account_status == 'Activated' ){
			$first_date = new DateTime($activation_date);
			$second_date = new DateTime($current_date);
			$diffrance = $second_date->getTimestamp() - $first_date->getTimestamp();
			
            	// If account has expired than logout the user and update account status
			if ( $diffrance > $limited_account_settings ){
				update_user_meta( $user_id, 'account_status', 'Deactivated' );
				wp_logout();
				wp_redirect( wp_login_url() . '?account_status=deactivated' ); 
				exit;
			}
	}

}
add_action( 'wp_head', 'limited_account_loggedin_controler' );
add_action( 'admin_head', 'limited_account_loggedin_controler' );
?>