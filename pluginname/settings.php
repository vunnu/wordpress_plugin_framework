<?php
$pimorder_options = get_option('pimorder_options');
$pimorder_objects = isset($pimorder_options['objects']) ? $pimorder_options['objects'] : array();
$pimorder_tags = isset($pimorder_options['tags']) ? $pimorder_options['tags'] : array();
?>

<div class="wrap">
    <?php screen_icon('plugins'); ?>
    <h2><?php _e('Simple Custom Post Order Settings', 'pimorder'); ?></h2>
    <?php if (isset($_GET['msg'])) : ?>
        <div id="message" class="updated below-h2">
            <?php if ($_GET['msg'] == 'update') : ?>
                <p><?php _e('Settings Updated.','pimorder'); ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <form method="post">

        <?php if (function_exists('wp_nonce_field')) wp_nonce_field('nonce_pimorder'); ?>

        <div id="pimorder_select_objects">

            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><?php _e('Check to Sort Post Types', 'pimorder') ?></th> 
                        <td>
                            <label><input type="checkbox" id="pimorder_allcheck_objects"> <?php _e('Check All', 'pimorder') ?></label><br>
                            <?php
                            $post_types = get_post_types(array(
                                'show_ui' => true,
                                'show_in_menu' => true,
                                    ), 'objects');

                            foreach ($post_types as $post_type) {
                                if ($post_type->name == 'attachment')
                                    continue;
                                ?>
                                <label><input type="checkbox" name="objects[]" value="<?php echo $post_type->name; ?>" <?php
                                    if (isset($pimorder_objects) && is_array($pimorder_objects)) {
                                        if (in_array($post_type->name, $pimorder_objects)) {
                                            echo 'checked="checked"';
                                        }
                                    }
                                    ?>>&nbsp;<?php echo $post_type->label; ?></label><br>
                                    <?php
                                }
                                ?>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>


        <div id="pimorder_select_tags">
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><?php _e('Check to Sort Taxonomies', 'pimorder') ?></th> 
                        <td>
                            <label><input type="checkbox" id="pimorder_allcheck_tags"> <?php _e('Check All', 'pimorder') ?></label><br>
                            <?php
                            $taxonomies = get_taxonomies(array(
                                'show_ui' => true,
                                    ), 'objects');

                            foreach ($taxonomies as $taxonomy) {
                                if ($taxonomy->name == 'post_format')
                                    continue;
                                ?>
                                <label><input type="checkbox" name="tags[]" value="<?php echo $taxonomy->name; ?>" <?php
                                    if (isset($pimorder_tags) && is_array($pimorder_tags)) {
                                        if (in_array($taxonomy->name, $pimorder_tags)) {
                                            echo 'checked="checked"';
                                        }
                                    }
                                    ?>>&nbsp;<?php echo $taxonomy->label ?></label><br>
                                    <?php
                                }
                                ?>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div> 
        <p class="submit">
            <input type="submit" class="button-primary" name="pimorder_submit" value="<?php _e('Update', 'pimorder'); ?>">
        </p>

    </form>

</div>

<script>
    (function ($) {

        $("#pimorder_allcheck_objects").on('click', function () {
            var items = $("#pimorder_select_objects input");
            if ($(this).is(':checked'))
                $(items).prop('checked', true);
            else
                $(items).prop('checked', false);
        });

        $("#pimorder_allcheck_tags").on('click', function () {
            var items = $("#pimorder_select_tags input");
            if ($(this).is(':checked'))
                $(items).prop('checked', true);
            else
                $(items).prop('checked', false);
        });

    })(jQuery)
</script>