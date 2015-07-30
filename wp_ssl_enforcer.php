<?php

/*
 * Plugin Name: WordPress SSL (HTTPS) Enforcer
 * Plugin URI: https://github.com/Base29/wp-ssl-enforcer
 * Description: A simple wordpress plugin to enforce HTTPS if the SSL certificate is installed for the domain.
 * Author: Base29
 * Author URI: http://www.base29.com
 * Version: 1.0
 */



add_action('init', 'wse_force_https');

function wse_force_https(){
    if(!is_ssl()){
        wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301);
        exit();
    }
}

// registering setting
add_action('admin_init', 'wse_reg_settings');

function wse_reg_settings() {
    register_settings('wse_settings', 'wse_settings');
}


add_action('admin_menu', 'wse_settings_page');

function wse_settings_page(){
    add_menu_page('WordPress SSL Enforcer', 'WP SSL Enforcer', 'manage_options', 'wse-ssl', 'wse_settings');
}

function wse_settings(){
    
    $wse_setting = esc_attr( get_option('some_other_option') );
    
$checkBox = $_POST['adminssl'];
        
        if($wse_setting == 1){
            force_ssl_admin(TRUE);
            echo 'SSL enforced on Admin';
        } else {
            echo 'SSL enforcement on admin is disabled';
        }
    
    ?>
    <div class="wrap">
     
        <form method="post" action="options.php">
            <?php setting_fields('wse_settings'); ?>
            <table>
                <tr>
                    <td><input type="checkbox" value="1" name="adminssl" <?php checked($wse_setting, 1); ?> /></td>
                    <td><span>Enforce SSL in admin</span></td>
            </tr>
            <tr>
                <td><input type="submit" class="button-primary" value="Save Settings" name="sbmt" /></td>
            </tr>
            </table>
        </form>
    </div>

<?php 

}
