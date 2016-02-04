<?php
/**
 * Created by PhpStorm.
 * User: Vadim
 * Date: 2015-05-22
 * Time: 15:07
 */

namespace PN_PostType;

class Functionality{

    private $post_type;

    public function __construct()
    {

        $this->post_type = 'ch_project_task';

        add_action('manage_'.$this->post_type.'_posts_columns', array($this, 'ch_manage_project_task_posts_columns'), 10, 1);
        add_action( 'manage_'.$this->post_type.'_posts_custom_column', array($this,'ch_manage_project_task_posts_custom_column'), 10, 2 );
    }


    public function ch_manage_project_task_posts_columns($defaults)
    {
        $defaults['ch_project'] = __('Project', 'pn');

        return $defaults;
    }


    public function ch_manage_project_task_posts_custom_column($column_name, $post_id)
    {
        if ($column_name == 'ch_project') {

            $arr_ids = get_post_meta($post_id, '_ch_task_project_ids');

            foreach (array_unique($arr_ids) as $id)
            {
                $fund = PN_Project::getById($id);
                echo $fund->post->post_title . '<br>';
            }
        }
    }

}

return new Functionality();