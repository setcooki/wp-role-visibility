<?php
/*
Plugin Name: WP Role Visibility
Plugin URI: https://github.com/setcooki/wp-role-visibility
Description: Wordpress Make posts and page only visible for selected roles
Author: Frank Mueller <set@cooki.me>
Author URI: https://github.com/setcooki/
Issues: https://github.com/setcooki/wp-role-visibility/issues
Text Domain: wp-role-visibility
Version: 0.0.3
*/

if(!defined('ROLE_VISIBILITY_DOMAIN'))
{
    define('ROLE_VISIBILITY_DOMAIN', 'wp-role-visibility');
}
define('ROLE_VISIBILITY_DIR', dirname(__FILE__));
define('ROLE_VISIBILITY_NAME', basename(__FILE__, '.php'));
define('ROLE_VISIBILITY_FILE', __FILE__);
define('ROLE_VISIBILITY_URL', plugin_dir_url(ROLE_VISIBILITY_FILE));

if(!function_exists('role_visibility'))
{
    function role_visibility()
    {
        try
        {
            $options = [];
            require dirname(__FILE__) . '/lib/vendor/autoload.php';
            if(is_file(ROLE_VISIBILITY_DIR . '/inc/functions.php'))
            {
                require_once ROLE_VISIBILITY_DIR . '/inc/functions.php';
            }
            if(is_file(ROLE_VISIBILITY_DIR . '/inc/api.php'))
            {
                require_once ROLE_VISIBILITY_DIR . '/inc/api.php';
            }
            if(is_file(ROLE_VISIBILITY_DIR . '/config/config.php'))
            {
                $config = require_once ROLE_VISIBILITY_DIR . '/config/config.php';
            }
            if(is_file(ROLE_VISIBILITY_DIR . '/config.php'))
            {
                $config = require_once ROLE_VISIBILITY_DIR . '/config.php';
            }

            $plugin = \Setcooki\Wp\Role\Visibility\Plugin::getInstance($config);
            register_activation_hook(__FILE__, array($plugin, 'activate'));
            register_deactivation_hook(__FILE__, array($plugin, 'deactivate'));
            register_uninstall_hook(__FILE__, array(get_class($plugin), 'uninstall'));
            add_action('init', function() use ($plugin)
            {
                $plugin->init();
            });
        }
        catch(Exception $e)
        {
            @file_put_contents(ABSPATH . 'wp-content/logs/debug.log', $e->getMessage() . "\n", FILE_APPEND);
        }
    }
}
role_visibility();
