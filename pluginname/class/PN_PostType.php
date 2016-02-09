<?php
/**
 * Created by PhpStorm.
 * User: Vadim
 * Date: 2015-09-03
 * Time: 09:30
 */


class PN_PostType extends PN_Base
{


    public $id;

    public $post;
    
    public static $post_type = 'pn_posttype';



    /**
     * Saving Room parameters to meta
     */
    public function save()
    {
//        $this->save_meta_value('_ch_room_main_color', $this->get_room_mainColor());
    }

    /**
     * Initializing object parameters from meta
     */
    public function init()
    {
//        $this->set_room_mainColor($this->get_meta_value('_'.self::$post_type.'_main_color'));
    }


    /**
     * Getting list of objects list
     */
    public function get_list_categorised($tax)
    {
        if(!$tax)
            $tax = self::$post_type.'_type';

        $post_type = self::get_class_post_type();

        $tax_terms = get_terms( $tax, 'parent=0&orderby=name&order=ASC');


        $ret_array = array();

        if ($tax_terms) {
            foreach ($tax_terms  as $tax_term) {
                $args = array(
                    'post_type'			=> $post_type,
                    "$tax"				=> $tax_term->slug,
                    'post_status'		=> 'publish',
                    'suppress_filters' => false,
                    'posts_per_page'	=> -1
                );

                $ret_array[$tax_term->term_id] =  get_posts($args);

            }
        }


        return $ret_array;
    }


    /**
     * @return mixed
     * Getting list of class objects
     */
    static public function get_list()
    {
        $args = array(
            'post_type' => self::$post_type,
            'posts_per_page' => '-1'
        );

        $posts = get_posts($args);

        array_walk($posts, array(__CLASS__, 'translate_to_class_object'));

        return $posts;
    }



    /**
     * Getting current class post_type
     */
    static public function get_class_post_type()
    {
        return self::$post_type;
    }
}