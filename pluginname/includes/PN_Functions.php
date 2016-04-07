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
        add_filter('index_post_type_list', array($this, 'sca_index_post_type_list'), 0, 1);
        add_filter('tax_term_list', array($this, 'sca_tax_term_list'), 0, 2);
        add_filter('posts_by_term', array($this, 'sca_posts_by_term'), 0, 3);
        add_filter('group_by_post_type', array($this , 'sca_group_by_post_type'), 0, 1);

        //Images
        add_filter('post_image_url', array($this , 'sca_post_image_url'), 0, 2);

        //Search
        add_filter('taxonomies_search', array($this , 'sca_taxonomies_search'), 0, 2);

        //Contact forms seven
        add_filter('wpcf7_before_send_mail', array($this, 'sca_dynamic_recipient'), 10, 2);
        add_filter('wpcf7_dynamic_recipient_address', array($this, 'sca_dynamic_recipient_address'), 10, 1);


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
     * @param $arr_list array of Post objects
     * @return array|bool
     */
    public function sca_index_post_type_list($arr_list)
    {
        if(!$arr_list || empty($arr_list))
            return false;

        $ret_arr = array();


        foreach($arr_list as $post)
        {
            $ret_arr[$post->ID] = $post;
        }

        return $ret_arr;

    }

    /**
     * @param $taxanomy
     * @param bool|false $args
     * @return mixed
     * Get taxonomy terms list
     */
    public function sca_tax_term_list($taxanomy, $args = false)
    {
        $args_def = array(
            'hide_empty' => false
        );

        $args = array_merge($args_def, $args);

        return get_terms($taxanomy, $args);
    }


    /**
     * @param $post_type
     * @param $taxanomy
     * @param $term
     * @return mixed
     * Get posts in term
     */
    public function sca_posts_by_term($post_type, $taxanomy, $term)
    {
        $args = array(
            'post_type' => $post_type,
            'posts_per_page'=>-1,
            'tax_query' => array(
                array(
                    'taxonomy' => $taxanomy,
                    'field' => 'id',
                    'terms' => $term->term_id
                )
            )
        );

        return get_posts($args);
    }


    /**
     * @param $posts
     * @return array|bool
     * Grouping posts by post type
     */
    public function sca_group_by_post_type($posts)
    {
        if(!$posts && !empty($posts))
            return false;

        $ret_arr = array();

        foreach ($posts as $post) {
            $ret_arr[$post->post_type][] = $post;
        }

        return $ret_arr;

    }


    //Image functions

    public function sca_post_image_url($post_id, $size = 'thumbnail')
    {
        $feat_image = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), $size );

        return $feat_image[0];
    }



    //Search functions

    /**
     * @param $query
     * @param bool|false $taxonomies
     * @return array
     * Modify instant-search-suggest plugin to search in taxonomies
     */
    public function sca_taxonomies_search($query, $taxonomies = false)
    {
        // taxononomies
        $tax_query = array();
        $tax_args = array();
        $tax_output = 'objects';
        $tax_operator = 'and';
        $tax = get_taxonomies( $tax_args, $tax_output, $tax_operator );

        if(!$taxonomies)
            $taxonomies = $tax;

        $results = wp_cache_get( 'sca_' . sanitize_title_with_dashes( $query ), 'sca_search' );


        if($results == false){


            if ( !empty( $taxonomies ) ) {

                foreach ( $taxonomies as $tax ) {

                    $tax_query[] = $tax->name;
                }
            }


            if ( !empty( $tax_query ) ) {

                $terms = get_terms( $tax_query, 'search='.$query );

                if ( ! empty( $terms ) ) {

                    foreach ( $terms as $term ) {

                        $results[] = array(
                            'title' => $term->name,
                            'permalink' => get_term_link( $term->name, $term->taxonomy ),
                            'taxonomy' => $taxonomies[$term->taxonomy]->labels->singular_name,
                            'count' => $term->count,
                            'type' => 'taxonomy'
                        );
                    }
                }
            }
        }

        if ( ! empty( $results ) ) {

            wp_cache_set( 'sca_' . sanitize_title_with_dashes( $query ), $results, 'sca_search', 3600 );

        }

        return $results;
    }



    //Contact forms 7

    /**
     * @param $form
     * Modifies recipient list to add custom address
     */
    public function sca_dynamic_recipient($form) {

        $mail = $form->properties;
        $properites = $form->get_properties();

        if (!isset($_POST['dynamic-recipient-id'])) {
            return;
        }

        $new_recipient = apply_filters('wpcf7_dynamic_recipient_address', $_POST['dynamic-recipient-id']);
        $properites['mail']['recipient'] .= ','. $new_recipient;

        $form->set_properties($properites);
    }



    public function sca_dynamic_recipient_address($id_job_offer)
    {
        $joboffer = SCA_JobOffer::getById($id_job_offer);

        return $joboffer->joboffer_recipients;
    }


    //Advanced custom post type plugin

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