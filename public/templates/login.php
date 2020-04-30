<?php global $wp_session; include 'dashboard-header.php'; /* Template Name: Login & Dashboard */ ?>

    <?php if ( !is_user_logged_in() ) : ?>

        <div class="row">
            <div class="medium-6 columns medium-centered">

                <?php

                    // For Login
                    $login  = ( isset( $_GET['login'] ) ) ? $_GET['login'] : 0;
                    if ( $login === "failed" )
                        echo '<div class="fail"><strong>ERROR:</strong> Invalid username and/or password.</div>';
                    elseif ( $login === "empty" )
                        echo '<div class="fail"><strong>ERROR:</strong> Username and/or Password is empty.</div>';
                    elseif ( $login === "false" )
                        echo '<div class="success">You are logged out.</div>';


                    if ( !$wp_session['redirect'] )
                        $redirect_url = get_bloginfo('url') . '/dashboard';
                    else
                        $redirect_url = $wp_session['redirect'];
                ?>

                <div id="login-form" class="fade">
                    <p><img src="<?php echo SRS_URL . '/public/images/login-logo.svg';?>" alt="" class="login-logo"></p>

                    <img src="<?php echo SRS_URL . '/public/images/icons/icon-intranet.svg';?>" alt="" class="intranet-icon">
                    <?php if ( isset($_GET['passwordreset'] ) ) : ?>

                        <form method="post" id="loginform" class="lostpass">
                            <div id="message"></div>
                            <h6>Password Reset</h6>
                            <?php
                                // this prevent automated script for unwanted spam
                                if ( function_exists( 'wp_nonce_field' ) )
                                    wp_nonce_field( 'rs_user_lost_password_action', 'rs_user_lost_password_nonce' );
                            ?>
                            <div class="username">
                                <input type="text" name="user_login" value="<?php echo esc_attr($user_login); ?>" size="20" id="user_login" tabindex="11" placeholder="Username or Email" />
                            </div>
                            <?php do_action( 'lostpassword_form' ); ?>
                            <input type="submit" id="submit" name="wp-submit" value="Get New Password" tabindex="14" class="user-submit full-width" />
                            <a href="/login" class="lost-pass">&laquo; Return to login</a>
                            <div id="loading">
                                <img src="<?php echo SRS_URL; ?>/public/images/ripple.svg" alt="" />
                            </div>
                        </form>

                    <?php elseif ( isset( $_GET['key'] ) ) : ?>

                        <?php
                            $errors = new WP_Error();
                            $user = check_password_reset_key($_GET['key'], $_GET['login']);

                            if ( is_wp_error( $user ) ) :
                                if ( $user->get_error_code() === 'expired_key' )
                                    $errors->add( 'expiredkey', __( 'Sorry, that key has expired. Please try again.' ) );
                                else
                                    $errors->add( 'invalidkey', __( 'Sorry, that key does not appear to be valid.' ) );
                            endif;

                            // display error message
                            if ( $errors->get_error_code() )
                                echo '<div class="fail">' . $errors->get_error_message( $errors->get_error_code() ) . '</div>';
                        ?>

                        <?php if ( is_wp_error( $user ) ) : ?>
                            <p>Your key seems to be expired or invalid.<br /><a href="/login/?passwordreset=yes">Please return</a> to the password reset page and request a new key.</p>
                        <?php elseif ( $success ) : ?>
                            <p>Your password has successfully been reset.<br /><a href="/login/">Please return</a> to the login page to login.</p>
                        <?php endif; ?>

                        <form method="post" autocomplete="off" id="loginform" class="resetpass">
                            <div id="message"></div>
                            <h6>Password Reset</h6>

                            <?php

                                // Remove query strings and redirect to login page on success
                                $url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                                $redirect = preg_replace('/\?.*/', '', $url);

                                // this prevent automated script for unwanted spam
                                if ( function_exists( 'wp_nonce_field' ) )
                                    wp_nonce_field( 'rs_user_reset_password_action', 'rs_user_reset_password_nonce' );
                            ?>

                            <input type="hidden" name="user_key" id="user_key" value="<?php echo esc_attr( $_GET['key'] ); ?>" autocomplete="off" />
                            <input type="hidden" name="user_login" id="user_login" value="<?php echo esc_attr( $_GET['login'] ); ?>" autocomplete="off" />
                            <input type="hidden" name="redirect" id="redirect" value="<?php echo $redirect; ?>" autocomplete="off" />

                            <div class="password">
                                <input type="password" name="pass1" id="pass1" value="" size="20" placeholder="New password" <?php echo ( is_wp_error( $user ) ) ? 'disabled' : ''; ?> />
                            </div>

                            <div class="password">
                                <input type="password" name="pass2" id="pass2" value="" size="20" placeholder="Confirm new password" <?php echo ( is_wp_error( $user ) ) ? 'disabled' : ''; ?> />
                            </div>

                            <p class="description indicator-hint">Your password should be at least seven characters long.</p>

                            <?php do_action( 'resetpass_form', $user ); ?>
                            <input type="submit" id="submit" name="wp-submit" id="wp-submit" value="Reset Password" tabindex="14" class="user-submit full-width" <?php echo ( is_wp_error( $user ) ) ? 'disabled' : ''; ?> />
                            <div id="loading">
                                <img src="<?php echo SRS_URL; ?>/public/images/ripple.svg" alt="" />
                            </div>
                        </form>

                    <?php else : ?>

                        <form method="post" action="<?php bloginfo('url'); ?>/wp-login.php" id="loginform">

                            <input type="hidden" name="redirect_to" value="<?php echo $redirect_url; ?>" />
                            <input type="hidden" name="user-cookie" value="1" />

                            <h6>Intranet</h6>
                            <div class="username">
                                <input type="text" name="log" value="" size="20" id="user_login" tabindex="11" placeholder="Username" />
                            </div>
                            <div class="password">
                                <input type="password" name="pwd" value="" size="20" id="user_pass" tabindex="12" placeholder="Password"/>
                            </div>
                            <div class="login_fields">
                                <div class="rememberme">
                                    <label for="rememberme">
                                        <input type="checkbox" name="rememberme" value="forever" checked="checked" id="rememberme" tabindex="13" /> Remember Me
                                    </label>
                                </div>
                                <?php do_action('login_form'); ?>
                                <input type="submit" name="user-submit" value="Sign in" tabindex="14" class="user-submit full-width" />
                                <a href="?passwordreset=yes" class="lost-pass">Lost your password?</a>
                            </div>
                        </form>

                    <?php endif; ?>
                </div> <!-- #login-form -->
            </div> <!-- .columns -->
        </div> <!-- .row -->


    <?php
        else :

            wp_redirect( get_site_url() . '/dashboard' ); exit;

        endif;
    ?>

<?php include 'dashboard-footer.php'; ?>
