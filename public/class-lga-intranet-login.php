<?php

/**
 * The login functionality of the plugin.
 *
 * @link       https://seansdesign.net
 * @since      1.0.0
 *
 * @package    Srs_Intranet
 * @subpackage Srs_Intranet/public
 */

/**
 * The login functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Srs_Intranet
 * @subpackage Srs_Intranet/public
 * @author     Sean Sullivan <me@seanrsullivan.com>
 */
class Srs_Intranet_Login {

	/**
	 * Redirect failed login to custom login page
	 *
	 * @since 2.1.3
	 */
	function login_failed() {
	    $login_page  = home_url( '/login/' );
	    wp_redirect( $login_page . '?login=failed' ); exit;
	}

	/**
	 * Verfiy username/pass
	 *
	 * @since 2.1.3
	 */
	function verify_username_password( $user, $username, $password ) {
	    $login_page  = home_url( '/login/' );
	    if( $username == "" || $password == "" ) :
	        wp_redirect( $login_page . "?login=empty" ); exit;
	    endif;
	}

	/**
	 * Function for lost passwords
	 *
	 * @since 2.1.5
	 */
	function lost_pass_callback() {

	    global $wpdb, $wp_hasher;

	    $nonce = $_POST['nonce'];

	    if ( ! wp_verify_nonce( $nonce, 'rs_user_lost_password_action' ) )
	        die ( 'Security checked!');

	    $user_login = $_POST['user_login'];

	    $errors = new WP_Error();

	    if ( empty( $user_login ) ) :
	        $errors->add('empty_username', __('<strong>ERROR</strong>: Enter a username or e-mail address.'));
	    elseif ( strpos( $user_login, '@' ) ) :
	        $user_data = get_user_by( 'email', trim( $user_login ) );
	        if ( empty( $user_data ) ) :
	            $errors->add('invalid_email', __('<strong>ERROR</strong>: There is no user registered with that email address.'));
	        endif;
	    else :
	        $login = trim( $user_login );
	        $user_data = get_user_by('login', $login);
	    endif;

	    // Fires before errors are returned from a password reset request.
	    do_action( 'lostpassword_post', $errors );

	    if ( $errors->get_error_code() || !$user_data ) :
	        $errors->add('invalidcombo', __('ERROR: Invalid username or email.'));
	        $errors->get_error_message( $errors->get_error_code() );
	    endif;

	    // Redefining user_login ensures we return the right case in the email.
	    $user_login = $user_data->user_login;
	    $user_email = $user_data->user_email;
	    $key = get_password_reset_key( $user_data );

	    if ( is_wp_error( $key ) )
	        return $key;

	    $message = __('Someone requested that the password be reset for the following account:') . "\r\n\r\n";
	    $message .= network_home_url( '/' ) . "\r\n\r\n";
	    $message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
	    $message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
	    $message .= __('To reset your password, visit the following address:') . "\r\n\r\n";
	    $message .= network_home_url( '/login' ) . "/?action=rp&key=$key&login=" . rawurlencode($user_login) . "\r\n";

	    if ( is_multisite() )
	        $blogname = $GLOBALS['current_site']->site_name;
	    else
	        $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	    $title = sprintf( __('[%s] Password Reset'), $blogname );

	    // Filter the subject of the password reset email.
	    $title = apply_filters( 'retrieve_password_title', $title, $user_login, $user_data );

	    // Filter the message body of the password reset mail.
	    $message = apply_filters( 'retrieve_password_message', $message, $key, $user_login, $user_data );

	    if ( wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) )
	        $success = 'Password reset. Please check your e-mail for the confirmation link.';
	    else
	        $errors->add('could_not_sent', __('The e-mail could not be sent.') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function.'), 'message');

	    // display error message
	    if ( $errors->get_error_code() )
	        echo '<div class="fail">'. $errors->get_error_message( $errors->get_error_code() ) .'</div>';
	    else
	        echo '<div class="success">'. $success . '</div>';

	    // return proper result
	    die();
	}

	/**
	 * Function for resetting passwords
	 *
	 * @since 2.1.5
	 */
	function reset_pass_callback() {

	    $errors = new WP_Error();
	    $nonce = $_POST['nonce'];

	    if ( ! wp_verify_nonce( $nonce, 'rs_user_reset_password_action' ) )
	        die ( 'Security checked!');

	    $pass1     = $_POST['pass1'];
	    $pass2     = $_POST['pass2'];
	    $key       = $_POST['user_key'];
	    $login     = $_POST['user_login'];
	    $redirect  = $_POST['redirect'];

	    $user = check_password_reset_key( $key, $login );

	    // check to see if user added some string
	    if( empty( $pass1 ) || empty( $pass2 ) )
	        $errors->add( 'password_required', __( 'Password is required field' ) );

	    // is pass1 and pass2 match?
	    if ( isset( $pass1 ) && $pass1 != $pass2 )
	        $errors->add( 'password_reset_mismatch', __( 'The passwords do not match.' ) );

	    // Fires before the password reset procedure is validated.
	    do_action( 'validate_password_reset', $errors, $user );

	    if ( ( ! $errors->get_error_code() ) && isset( $pass1 ) && !empty( $pass1 ) ) :
	        reset_password($user, $pass1);
	        $success = '<div class="success">Your password has been reset.</div>';
	    endif;

	    // display error message
	    if ( $errors->get_error_code() )
	        echo '<div class="fail">'. $errors->get_error_message( $errors->get_error_code() ) .'</div>';
	    else
	        echo $success;

	    // return proper result
	    die();
	}


	/**
	 * Localize the AJAX script
	 *
	 * @since 2.1.5
	 */
	function enqueue_login_script() {
		if ( is_page( 'login' ) ) :
			wp_enqueue_script( 'srs-request-ajax', plugin_dir_url( __FILE__ ) . '/js/srs-ajax-pass.js', array( 'jquery' ), '1.0.0', true);
			wp_localize_script( 'srs-request-ajax', 'reset_ajax', array(
			    'url'       => admin_url( 'admin-ajax.php' ),
			    'site_url'  => get_bloginfo('url'),
			    'theme_url' => get_bloginfo('template_directory')
			) );
		endif;
	}

}
