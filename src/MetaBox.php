<?php

namespace Setcooki\Wp\Role\Visibility;

use Setcooki\Wp\Role\Visibility\Traits\Singleton;

/**
 * Class Plugin
 * @package Setcooki\Wp\Role\Visibility
 */
class MetaBox
{
    use Singleton;



    /**
     * @param Plugin $plugin
     */
    public function init(Plugin $plugin)
    {
        add_action('add_meta_boxes', [$this, 'addMetaBox']);
    }


    /**
     *
     */
    public function addMetaBox()
    {
        $i = 0;
        $types = Plugin::$options['postTypes'];
        foreach($types as $type)
        {
            add_meta_box
            (
                'role_visibility_box' . $i,
                __('Show only for roles', ROLE_VISIBILITY_DOMAIN),
                [$this, 'metaBoxCallback'],
                $type,
                'side',
                'default',
                ['id' => $i]
            );
            $i++;
        }
    }


    /**
     * @param \WP_Post $post
     * @param $args
     */
    public function metaBoxCallback(\WP_Post $post, $args)
    {
        $roles = get_post_meta($post->ID, 'role_visibility', true);
        if(!empty($roles))
        {
            $roles = preg_split('=\s*\,+\s*=i', $roles);
        }else{
            $roles = [];
        }

        ob_start(); ?>
        <p style="display: none"><label for="role_visibility_select"><?php _e('Show only for roles', ROLE_VISIBILITY_DOMAIN); ?>:</label></p>
        <select name="role_visibility_select[]" id="role_visibility_select" class="postbox" size="5" multiple>
            <?php foreach(get_editable_roles() as $key => $role) { ?>
                <option value="<?php echo $key; ?>" <?php echo ((in_array($key, $roles)) ? 'selected="selected"' : ''); ?>><?php echo $role['name']; ?></option>
            <?php } ?>
        </select>
        <?php echo ob_get_clean();
    }
}