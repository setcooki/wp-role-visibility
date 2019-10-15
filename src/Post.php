<?php

namespace Setcooki\Wp\Role\Visibility;

use Setcooki\Wp\Role\Visibility\Traits\Singleton;

/**
 * Class Post
 * @package Setcooki\Wp\Role\Visibility
 */
class Post
{
    use Singleton;


    /**
     * @param Plugin $plugin
     */
    public function init(Plugin $plugin)
    {
        add_action('save_post', [$this, 'savePost'], 10, 1);
        add_action('pre_get_posts', [$this, 'preGetPost'], 10, 1);
        add_filter('wp_get_nav_menu_items', [$this, 'getNavMenuItems'], 10, 3);
    }


    /**
     * @param $post_id
     */
    public function savePost($post_id)
    {
        $roles = [];

        if(array_key_exists('role_visibility_select', $_POST))
        {
            foreach((array)$_POST['role_visibility_select'] as $role)
            {
                $role = get_role($role);
                if($role instanceof \WP_Role)
                {
                    $roles[] = $role->name;
                }
            }
            if(!empty($roles))
            {
                update_post_meta($post_id, 'role_visibility', implode(',', $roles));
            }else{
                update_post_meta($post_id, 'role_visibility', '');
            }
        }else{
            update_post_meta($post_id, 'role_visibility', '');
        }
    }


    /**
     * @param \WP_Query $query
     */
    public function preGetPost(\WP_Query $query)
    {
        $settings = json_decode(get_option('wprv_settings', new \stdClass()));

        if(isset($settings->target) && !empty($settings->target))
        {
            if($query->is_main_query() && $query->queried_object instanceof \WP_Post)
            {
                $roles = get_post_meta($query->queried_object->ID, 'role_visibility', true);
                if(!empty($roles))
                {
                    if(is_user_logged_in())
                    {
                        $roles = explode(',', trim($roles, ', '));
                        if(!empty($roles))
                        {
                            $e = 0;
                            foreach($roles as $role)
                            {
                                if(!in_array($role, wp_get_current_user()->roles))
                                {
                                    $e++;
                                }
                            }
                            if($e === sizeof($roles))
                            {
                                wp_redirect($settings->target);
                                exit;
                            }
                        }
                    }else{
                        wp_redirect($settings->target);
                        exit;
                    }
                }
            }
        }
    }


    /**
     * @param $items
     * @param $menu
     * @param $args
     * @return mixed
     */
    public function getNavMenuItems($items, $menu, $args)
    {
        $tmp = [];
        $cache = [];

        for($i = 0; $i < sizeof($items); $i++)
        {
            $roles = get_post_meta($items[$i]->object_id, 'role_visibility', true);
            if(!empty($roles))
            {
                $roles = explode(',', $roles);
                if(is_user_logged_in())
                {
                    $e = 0;
                    $user = wp_get_current_user();
                    foreach($roles as $role)
                    {
                        if(!in_array($role, $user->roles))
                        {
                            $e++;
                        }
                    }
                    if($e === sizeof($roles))
                    {
                        $tmp[] = $i;
                        $cache[] = $items[$i]->ID;
                    }
                }else{
                    $tmp[] = $i;
                    $cache[] = $items[$i]->ID;
                }
            }else{
                if(isset($items[$i]->menu_item_parent) && !empty($items[$i]->menu_item_parent) && in_array($items[$i]->menu_item_parent, $cache))
                {
                    $tmp[] = $i;
                    $cache[] = $items[$i]->ID;
                }
            }
        }

        if(!empty($tmp))
        {
            foreach($tmp as $i)
            {
                unset($items[$i]);
            }
        }

        return $items;
    }
}