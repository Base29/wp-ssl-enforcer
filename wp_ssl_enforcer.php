<?php
/*
 * Plugin Name: WordPress SSL (HTTPS) Enforcer
 * Plugin URI: https://github.com/Base29/wp-ssl-enforcer
 * Description: A simple wordpress plugin to enforce HTTPS if the SSL certificate is installed for the domain.
 * Author: Base29
 * Author URI: http://www.base29.com
 * Version: 1.0
 * Text Domain: wp-ssl-enforcer
 * Domain Path: /languages
 */

class WSE {
    
    function __construct(){
        add_action('admin_menu', array($this, 'wse_admin_menu'));
        add_action('admin_init', array($this, 'wse_settings'));
        add_action('init', array($this, 'wse_force_https'));
        add_action('admin_init', array($this, 'wse_force_admin_https'));
    }
    
    function wse_settings(){
        register_setting('wse_plugin_settings', 'wse_domain_to_force_https');
        register_setting('wse_plugin_settings', 'wse_force_https_on_admin');
    }
    
    // Add admin menu
    function wse_admin_menu() {
        add_submenu_page('options-general.php', 'WP SSL Enforcer Settings', 'WP SSL Enforcer', 'manage_options', 'wse_settings', array($this, 'wse_settings_page'));
    }
    
        // Settings page
    function wse_settings_page() {

        echo '<div class="wrap">';
        echo '<h1>' . get_admin_page_title() . '</h1>';
        ?>
        <form action="options.php" method="post">

            <?php settings_fields('wse_plugin_settings'); ?>
            <table width="50%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 18px;">
                <?php if(is_multisite()): ?>
                <tr>
                    <td><h4><label><?php _e('Domain Name', 'wp-ssl-enforcer') ?></label></h4></td>
                    <td><input type="text" name="wse_domain_to_force_https" value="<?php echo get_option('wse_domain_to_force_https') ?>"></td>
                </tr>
                <?php endif ?>
                
                <tr>
                    <td style="padding:0 0 30px"><h4><label><?php _e('Force SSL on Admin', 'wp-ssl-enforcer') ?></label></h4></td>
                    <td style="padding:0 0 30px"><input type="checkbox" value="1" <?php checked(1, get_option('wse_force_https_on_admin'), TRUE); ?> name="wse_force_https_on_admin" ></td>
                </tr>
                
                <tr>
                    <td><input type="submit" value="<?php _e('Save Changes', 'wp-ssl-enforcer'); ?>" name="submit" id="submit" class="button button-primary"></td>
                </tr>
            </table>
        </form>
<?php echo get_option('wse_force_https_on_admin'); ?>
        <?php
        echo '</div>';
    }
    
    function wse_force_https(){
        
        if(is_multisite()){
        
            $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $domain = get_option('wse_domain_to_force_https');

            if(!is_ssl() && strpos($url, $domain)){
                wp_redirect('https://' . $url, 301);
                exit();
            }
        } else {
            if(!is_ssl()){
                wp_redirect('https://' . $_SERVER['HTTP_POST'] . $_SERVER['REQUEST_URI'], 301);
                exit();
            }
        }

    }
    
    function wse_force_admin_https(){
        $c = get_option('wse_force_https_on_admin');
        if($c == 1){
            force_ssl_admin(TRUE);
        } else {
            force_ssl_admin(FALSE);
        }
    }
}

new WSE;
