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

        add_filter('image_upload_form', array( $this, 'ch_image_upload_form'), 0, 3);

        /**
         * ToDO: only started doing this one, so you have to finish. Add theme video support and complete file save
         */
        add_filter('video_upload_form', array( $this, 'ch_video_upload_form'), 0, 3);


    }

    public function global_custom_options()
    {
        include_once(PLUGINNAME_BASE . '/settings.php');
    }

}

return new PN_Admin_Functions();