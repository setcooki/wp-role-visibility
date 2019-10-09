<?php

namespace Setcooki\Wp\Role\Visibility;

use Setcooki\Wp\Role\Visibility\Traits\Singleton;

/**
 * Class Admin
 * @package Setcooki\Wp\Role\Visibility
 */
class Admin
{
    use Singleton;

    /**
     * @var null|\stdClass
     */
    public $menu = null;


    /**
     * @param Plugin $plugin
     */
    public function init(Plugin $plugin)
    {
        $this->menu = new \stdClass();
        add_action('admin_menu', function()
        {
            add_options_page
            (
                __('Role Visibility', ROLE_VISIBILITY_DOMAIN),
                __('Role Visibility', ROLE_VISIBILITY_DOMAIN),
                'manage_options',
                'role-visibility',
                array($this, 'menu')
            );
        });
    }


    /**
     *
     */
    public function menu()
    {
        $this->menu->error = '';
        $this->menu->success = false;

        if(strtolower($_SERVER['REQUEST_METHOD']) === 'post')
        {
            $settings = new \stdClass();
            $settings->types = (isset($_POST['wprv_types']) && !empty($_POST['wprv_types'])) ? (array)$_POST['wprv_types'] : [];
            update_option('wprv_settings', json_encode($settings));
            $this->menu->success = true;
        }

        $settings = json_decode(get_option('wprv_settings', new \stdClass()));
        $this->menu->settings = new \stdClass();
        $this->menu->settings->types = (array)$settings->types;

        ob_start();
        require_once ROLE_VISIBILITY_DIR . '/templates/admin/admin.php';
        echo ob_get_clean();
    }
}