<?php
/**
 * Created by PhpStorm.
 * User: Vadim
 * Date: 2015-04-27
 * Time: 08:47
 */


class PN_Meta_Box_ProjectFields{


    public $id;
    public $title;
    public $context;
    public $priority;
    public $post_types;

    public function __construct()
    {
        $this->id = 'ch_project_fields';
        $this->title = __('Project fields', 'wa');
        $this->context = 'side';
        $this->priority = 'default';
        $this->post_types = array('ch_project');

        add_action('save_post', array($this, 'meta_box_save'), 10, 1);

    }

    public function meta_box_inner($post)
    {

        $post_id = $post->ID;

        ?>

        <div class="row">
            <div>
                <label for="_ch_project_url">
                    <?php echo __('Project url', 'wa'); ?>
                </label>
                <input class="text" value="<?php echo get_post_meta($post_id, '_ch_project_url', true); ?>" name="_ch_project_url">
            </div>
        </div>


    <?php
    }

    function meta_box_save($post_id)
    {

        if (in_array(get_post_type($post_id), $this->post_types)) {


            $project = PN_Project::getById($post_id);
            if(isset($_POST['_ch_project_url']) && !empty($_POST['_ch_project_url']))
            {

                $project->set_project_url( $_POST['_ch_project_url'] );
                $project->save();
            }else{

                $project->set_project_url('');
                $project->save();
            }

        }


    }


}

return new PN_Meta_Box_ProjectFields();