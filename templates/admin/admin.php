<script type="text/javascript">
    (function ($) {
        $(document).ready(function(){});
    })(jQuery.noConflict());
</script>
<style type="text/css"></style>
<div class="wrap" id="wprv">
    <h2><?php _e('Role Visibility', ROLE_VISIBILITY_DOMAIN); ?></h2>
    <?php if(!empty($this->menu->success)){ ?>
        <div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">
            <p>
                <strong><?php _e('Settings saved',ROLE_VISIBILITY_DOMAIN); ?></strong>
            </p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text"><?php _e('Dismiss',ROLE_VISIBILITY_DOMAIN); ?></span>
            </button>
        </div>
    <?php } ?>
    <div id="poststuff">
        <?php if(!empty($this->menu->error)){ ?>
            <div class="notice notice-error is-dismissible">
                <p><?php echo $this->menu->error; ?></p>
            </div>
        <?php } ?>
        <form id="wprv-form" method="post" action="">
            <div class="postbox">
                <h3 class="hndle">
                    <label for="title"><?php _e('General Settings', ROLE_VISIBILITY_DOMAIN); ?></label>
                </h3>
                <div class="inside">
                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
                                <th scope="row"><?php _e('Enable role visibility for the following post types', ROLE_VISIBILITY_DOMAIN); ?></th>
                                <td data-scope="types">
                                    <select name="wprv_types[]" size="5" style="width:100%" multiple>
                                        <?php foreach(array_merge(get_post_types(['public' => true, '_builtin' => true]), get_post_types(['public' => true, '_builtin' => false])) as $key => $name) { ?>
                                            <option value="<?php echo $key; ?>" <?php echo ((in_array($key, $this->menu->settings->types)) ? 'selected="selected"' : ''); ?>><?php echo $name; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="submit">
                        <input type="submit" name="wprv_submit" class="button-primary" value="<?php echo __('Save', ROLE_VISIBILITY_DOMAIN); ?>" />
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>
