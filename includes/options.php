<?php
if (!function_exists('add_action')) {
    die();
}

add_action('admin_menu', 'testimonialsadminmenu');

function testimonialsadminmenu() {
    add_submenu_page('edit.php?post_type=app_testimonials', __('Settings'), __('Settings'), 'manage_options', 'testimonials-settings', 'testimonialsconfpage');
    
    //call register settings function
 add_action( 'admin_init', 'testimonialssettings' );
}

function testimonialssettings() {
       register_setting( 'testimonials-settings-group', 'site_key');
        register_setting( 'testimonials-settings-group', 'secret_key');
}

function testimonialsconfpage() {  ?>

    <style>
        /*
                CSS for Settings
        */
        .captcha {
            width: 30%;
            background: #fff;
            padding: 29px;
            border-radius: 2px;
        }
        input[type="text"] {
            width: 100%;
        }
    </style>
 <div class="wrap">


<div class="captcha">
  
<form method="post" action="options.php">
       <h3>Google reCAPTCHA</h3> <br>
       <div class="help-text">reCAPTCHA is a free service to protect your website from spam and abuse.</div>
        <?php settings_fields( 'testimonials-settings-group' ); ?>
        <table class="form-table">
            <tr valign="top">
            <th scope="row"><?php echo __('Site Key'); ?></th>
            <td><input type="text" name="site_key" value="<?php echo get_option('site_key'); ?>" autocomplete="off"/></td>
            </tr>
              <tr valign="top">
            <th scope="row"><?php echo __('Secret Key'); ?></th>
            <td><input type="text" name="secret_key" value="<?php echo get_option('secret_key'); ?>" autocomplete="off"/></td>
            </tr>

            
        </table>
        
        <?php submit_button(); ?>

    </form>

</div>

</div>

<?php }  ?>
