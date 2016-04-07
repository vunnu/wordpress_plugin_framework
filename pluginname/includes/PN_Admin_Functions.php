<?php
/**
 * Created by PhpStorm.
 * User: Vadim
 * Date: 2015-04-23
 * Time: 13:46
 */


class PN_Admin_Functions{


    public function __construct()
    {

        add_filter('image_upload_form', array( $this, 'pn_image_upload_form'), 0, 3);

        add_action('admin_menu', array( $this, 'pn_add_global_custom_options'), 0, 2);

        //Adding post type menu as submenu of another post type
        add_action('admin_menu', array( $this, 'pn_add_private_galleries'), 0, 2);

        add_action('admin_init', array($this, 'pn_update_options'));

        /**
         * ToDO: only started doing this one, so you have to finish. Add theme video support and complete file save
         */
        add_filter('video_upload_form', array( $this, 'ch_video_upload_form'), 0, 3);

        //taxonomy-term-image
        add_filter( 'taxonomy-term-image-taxonomy', array($this, 'sca_taxonomy_term_image_taxonomy'));

    }


    /**
     * Adding plugin option page admin menu
     */
    public function pn_add_global_custom_options()
    {
        add_options_page('Scandagra theme settings', 'Scandagra settings', 'manage_options', 'functions', array($this, 'theme_custom_options'));
    }


    public function pn_add_private_galleries()
    {
        global $submenu;

        $url = 'edit.php?post_type=sca_private_gallery';

        $submenu['edit.php?post_type=sca_gallery'][] = array(__('Private gallery', 'sca'), 'manage_options', $url);
    }


    public function pn_update_options() {

        if (!isset($_POST['scandagra_theme_options']))
            return false;

        $input_options = array();
        $input_options['objects'] = isset($_POST['objects']) ? $_POST['objects'] : '';
        $input_options['objects_slide'] = isset($_POST['objects_slide']) ? $_POST['objects_slide'] : '';
        $input_options['tags'] = isset($_POST['tags']) ? $_POST['tags'] : '';

        update_option('scandagra_theme_options', $input_options);

        wp_redirect('admin.php?page=pimorder-settings&msg=update');
    }


    public function pn_global_custom_options()
    {
        include_once(PLUGINNAME_BASE . '/settings.php');
    }

}

return new PN_Admin_Functions();