<?php
/*
 * This file manages the main backend operations
 */

// Add the new custom user meta on registration
function limited_account_add_new_meta($user_id) { 
   	add_user_meta( $user_id, 'activation_date', '0' );
	add_user_meta( $user_id, 'account_status', 'New User' );
}
add_action( 'user_register', 'limited_account_add_new_meta');

// Show the new custom meta on profile page
function limited_account_show_new_meta( $user ) { 
?>
	<h3>Extra profile information</h3>
	<table class="form-table">
		<tr>
			<th><label for="login-date-and-status">Account Status</label></th>
			<td>
				<p name="activation_date" id="activation_date" class="regular-text" ><?php echo esc_attr( get_the_author_meta( 'activation_date', $user->ID ) ); ?></p>
				<span class="description">Account activation date</span>
			</td>
            	
                 <?php // Lets get the expiry date too
                    $activation_date =  get_the_author_meta( 'activation_date', $user->ID );
                 if( $activation_date != '0' ){
			if( get_option('limited_account_time_duration') ){
                    		$account_duration = get_option('limited_account_time_duration');
			} else {
				$account_duration = '2592000'; // 1 month
			}
			$first_date = new DateTime($activation_date);
			$expiry_date_calc = $first_date->getTimestamp() + $account_duration;
                    $expiry_date = date_create("@$expiry_date_calc");
				?>
                 <td>
				<p name="expiry_date" id="expiry_date" class="regular-text" ><?php echo esc_attr( date_format($expiry_date, 'm/d/Y H:i:s') );?></p>
				<span class="description">Account expiry date</span>
			</td>
                <?php } ?>
			<td>
				<p name="account_status" id="account_status" class="regular-text" ><?php echo esc_attr( get_the_author_meta( 'account_status', $user->ID ) ); ?></p>
				<span class="description">Account status</span>
			</td>
		</tr>
	</table>
<?php 
}
add_action( 'show_user_profile', 'limited_account_show_new_meta' );
add_action( 'edit_user_profile', 'limited_account_show_new_meta' );

// Show user status on users list page
function limited_account_status_controler($actions, $user_object) {
  
	$account_status = get_user_meta($user_object->ID, 'account_status', 'TRUE');
  
  		if( in_array( $account_status, array('Activated','New User') ) ){
			$actions['user_status'] = "<a class='edit-user-status' href='" . admin_url( "users.php?action=account_status_deactivate&amp;user_id=$user_object->ID") . "'>" . __( 'Deactivate', 'bly03' ) . "</a>";
		}
  
  		elseif( $account_status == 'Deactivated' ){
			$actions['user_status'] = "<a class='edit-user-status' href='" . admin_url( "users.php?action=account_status_activate&amp;user_id=$user_object->ID") . "'>" . __( 'Activate', 'bly03' ) . "</a>";
            }
	return $actions;
}
add_filter('user_row_actions', 'limited_account_status_controler', 10, 2);

// Activate and deactivate users account depending on the request on users list
function limited_account_status_modifier(){
  
 	if ( !isset( $_GET['action'] ) )
     return;
	if( $_GET['action'] == 'account_status_activate' ){
		update_user_meta( $_GET['user_id'], 'activation_date', '0' );
		update_user_meta( $_GET['user_id'], 'account_status', 'New User' );
	} elseif( $_GET['action'] == 'account_status_deactivate' ){
		update_user_meta( $_GET['user_id'], 'account_status', 'Deactivated' );
	}
		
}
add_action('admin_head','limited_account_status_modifier');

// Show custom error message on login page if account is deactivated
function limited_account_login_error_message(){
  	if ( !isset( $_GET['account_status'] ) )
     return;
	 //check if that's the error you are looking for
    	if ( $_GET['account_status'] == 'deactivated') {
       	//its the right error so you can show it
       	$limited_account_message = get_option( 'limited_account_error_message' );
     	$error_message = '<div id="login_error">' . $limited_account_message . '</div>';
    	}
    	return $error_message;
}
add_filter('login_message','limited_account_login_error_message');
?>