<?php
/**
 * Created by PhpStorm.
 * User: Vadim
 * Date: 2015-05-22
 * Time: 15:07
 */

namespace pluginname;

class FunctionalityFilter{

    private $post_type;

    public function __construct()
    {

        $this->post_type = 'ch_project_task';

        add_action('restrict_manage_posts', array($this, 'ch_restrict_manage_posts'), 10, 1);
        add_action('parse_query', array($this, 'ch_parse_query'), 10, 1);
    }


    public function ch_restrict_manage_posts()
    {
        global $wpdb, $wp_query;

        $qv = $wp_query->query_vars;

        if ( $qv['post_type'] == $this->post_type ) {

            $projects = PN_Project::get_list();

            echo '<select name="project_id">';
            echo '<option value="">' . __( 'All projects', PLUGINNAME_DOMAIN ) . '</option>';
            foreach( $projects as $project ) {
                $selected = ( !empty( $_GET['project_id'] ) AND $_GET['project_id'] == $project->post->ID ) ? 'selected="selected"' : '';
                echo '<option value="'.$project->post->ID.'" '.$selected.'>' . $project->post->post_title . '</option>';
            }
            echo '</select>';

        }
    }


    public function ch_parse_query($query)
    {
        if( is_admin() AND $query->query['post_type'] == $this->post_type ) {

            $qv = &$query->query_vars;
            $qv['meta_query'] = array();

            if( !empty( $_GET['project_id'] ) ) {

                $qv['meta_query'][] = array(
                    'field' => '_ch_task_project_ids',
                    'value' => $_GET['project_id'],
                );
            }

        }
    }
}

return new FunctionalityFilter();