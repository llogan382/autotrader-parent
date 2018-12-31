<?php
/**
 * Autentificate
 *
 * To override this shortcode in a child theme, copy this file to your child theme's
 * theme_config/extensions/shortcodes/shortcodes/ folder.
 */

function tfuse_login($atts, $content = null)
{
    $return_html = '';
    if ( ! is_user_logged_in() )
    {
        $return_html = '<div class="widget-container widget_login">
            <h3>' . __('Login Form', 'tfuse') . '</h3>';
        $return_html .= '<form action="'. home_url().'/wp-login.php" method="post" name="loginform" id="loginform"  class="loginform">
                <p><label>'. __('Username', 'tfuse').'</label><br />
                    <input name="log" id="user_login" class="input" value="" size="20" tabindex="10" type="text">
                </p>
                <p><label>'. __('Password', 'tfuse').'</label><br />
                    <input name="pwd" id="user_pass" class="input" value="" size="20" tabindex="20" type="password">
                </p>
                <div class="forgetmenot input_styled checklist">
                    <input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90" checked />
                    <label for="rememberme">'. __('Remember Me', 'tfuse').'</label>
                </div>
                <p class="forget_password"><a href="'. home_url().'/wp-login.php?action=lostpassword">'. __('Forgot Password?', 'tfuse').'</a></p>
                <p class="submit">
                    <input type="submit" name="wp-submit" id="wp-submit" class="btn-submit" value="'.__('Login','tfuse').'" tabindex="100" />
                    <input type="hidden" name="redirect_to" value="'. home_url().'/wp-admin/" />
                    <input type="hidden" name="testcookie" value="1" />
                </p>
            </form>
        </div>';
    }
    return $return_html;
}

$atts = array(
    'name' => __('Login','tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the box shortcode.','tfuse'),
    'category' => 11,
    'options' => array(
    )
);

tf_add_shortcode('autentificate', 'tfuse_login', $atts);
