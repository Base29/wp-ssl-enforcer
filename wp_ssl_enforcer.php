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

add_action('admin_menu', 'wse_settings_page');

function wse_settings_page(){
    add_menu_page('WordPress SSL Enforcer', 'WP SSL Enforcer', 'manage_options', 'wse-ssl', 'wse_settings');
}

function wse_settings(){
    
$checkBox = $_POST['adminssl'];
$sbmt = $_POST['sbmt'];
    
    
        if(isset($checkBox)){
            $checked = "checked";
        } else {
            $checked = "unchecked";
        }
        
        if($checked == 'checked'){
            force_ssl_admin(TRUE);
            echo 'SSL enforced on Admin';
        } else {
            echo 'SSL enforcement on admin is disabled';
        }
    
    ?>
    <div class="wrap">
     
        <form method="post" action="">
            <table>
                <tr>
                    <td><input type="checkbox" value="<?php echo $checked; ?>" name="adminssl" <?php echo $checked; ?>/></td>
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