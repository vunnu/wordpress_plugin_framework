<?php
/**
 * Created by PhpStorm.
 * User: Vadim
 * Date: 2015-04-23
 * Time: 13:46
 */


class PN_Functions{


    public function __construct()
    {

        add_action('admin_menu', array( $this, 'add_global_custom_options'), 0, 2);

        //Global functions
        add_filter('post_type_list', array($this, 'pn_post_type_list'), 0, 1);

        //Advanced custom post type plugin
        add_filter('taxonomy_custom_field', array($this, 'pn_taxonomy_custom_field'), 0, 3);

    }



    //Global functions

    /**
     * Adding plugin option page admin menu
     */
    public function add_global_custom_options()
    {
        add_options_page('Plugin options', 'Plugin options', 'manage_options', 'functions','global_custom_options');
    }


    /**
     * @param $post_type
     * @return mixed
     * Getting post type list
     */
    public function pn_post_type_list($post_type)
    {
        $args = array(
            'post_type' => $post_type,
            'posts_per_page' => -1
        );

        return get_posts($args);
    }


    /**
     * @param $taxonomy
     * @param $term_id
     * @param $field
     * @param string $size
     * @return mixed
     * Getting taxonomy custom field
     */
    public function pn_taxonomy_custom_field($taxonomy, $term_id, $field, $size = 'thumbnail')
    {
        $field = get_field($field, $taxonomy . '_' . $term_id);
        
        return $field;
    }


    
}

return new PN_Functions();