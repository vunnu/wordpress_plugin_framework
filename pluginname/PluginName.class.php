<?php

define('PLUGINNAME_VERSION', '1.4.9');
define('PLUGINNAME_BASE', plugin_dir_path(__FILE__));
define('PLUGINNAME_BASE_URL', plugin_dir_url(__FILE__));
define('PLUGINNAME_TEMPLATE_PATH', plugin_dir_path(__FILE__) . 'templates/');
define('PLUGINNAME_RESOURCES_URL', PLUGINNAME_BASE_URL . 'src/resources/');
define('PLUGINNAME_MAIN_FILE', __FILE__);


class PluginName{


    public function __construct()
    {
        //Include Post Type Classes
        include_once(PLUGINNAME_BASE . '/class/Singleton.php');
        include_once(PLUGINNAME_BASE . '/class/PN_Base.php');
        include_once(PLUGINNAME_BASE . '/class/PN_PostType.php');
      


        //Include frontend functions
        include_once(PLUGINNAME_BASE . 'includes/PN_Functions.php');
        include_once(PLUGINNAME_BASE . 'includes/PN_Admin_Functions.php');

        //include Admin metaboxes
        include_once(PLUGINNAME_BASE . '/admin/metaboxes/PN_Metabox.php');

        //include admin filters and columns
        include_once(PLUGINNAME_BASE . 'admin/filters/PN_Filter.php');
        include_once(PLUGINNAME_BASE . 'admin/columns/PN_Column.php');

        //Loading scripts
//        add_action( 'init', array( $this, 'load_styles' ), 2 );
        add_action( 'init', array( $this, 'load_scripts' ), 2 );

        // Adding action to post type creation
        add_action( 'admin_print_scripts-post-new.php', array( $this, 'post_admin_scripts' ) );
        add_action( 'admin_print_scripts-post.php', array( $this, 'post_admin_scripts' ) );

        add_action( 'init', array( $this, 'register_post_types' ), 2 );
        add_action('init', array($this, 'load_plugin_textdomain'));
    }





    function post_admin_scripts()
    {
        global $post_type;

//        if (in_array($post_type, array('fund', 'document', 'fund_manager', 'fund_customer', 'chart'))) {
//
//            wp_register_style('bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css', array(), '1.0', 'all');
//            wp_enqueue_style('bootstrap'); // Enqueue it!
//        }

    }


    public function load_scripts()
    {
        if(is_admin())
        {
            wp_register_script('jscolor', PLUGINNAME_RESOURCES_URL . '../components/jscolor/jscolor.js', array('jquery'), '1.0.0');
            wp_enqueue_script('jscolor');
        }
    }


    // Including post types
    public function register_post_types()
    {

        // Add new custom post type
        $labels = array(
            'name' => _x('Room', 'Room plural name', 'wa'),
            'singular_name' => _x('Room', 'Room singular name', 'wa'),
            'menu_name' => _x('Rooms', 'admin menu', 'wa'),
            'name_admin_bar' => _x('Room', 'add new on admin bar', 'wa'),
            'add_new' => _x('Add New', 'fund', 'wa'),
            'add_new_item' => __('Add New Room', 'wa'),
            'new_item' => __('New Room', 'wa'),
            'edit_item' => __('Edit Room', 'wa'),
            'view_item' => __('View Room', 'wa'),
            'all_items' => __('Our Rooms', 'wa'),
            'search_items' => __('Search Room', 'wa'),
            'parent_item_colon' => __('Parent Room:', 'wa'),
            'not_found' => __('No funds found.', 'wa'),
            'not_found_in_trash' => __('No funds found in Trash.', 'wa')
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => __('room', 'wa')),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array('title', 'editor', 'thumbnail')
        );

        register_post_type('ch_room', $args);



        // Add new taxonomy, NOT hierarchical (like tags)
        $labels = array(
            'name'                       => _x( 'Room type', 'taxonomy general name', 'wa' ),
            'singular_name'              => _x( 'Room type', 'taxonomy singular name', 'wa' ),
            'search_items'               => __( 'Search asset class', 'wa' ),
            'popular_items'              => __( 'Popular asset class', 'wa' ),
            'all_items'                  => __( 'All Room types', 'wa' ),
            'parent_item'                => null,
            'parent_item_colon'          => null,
            'edit_item'                  => __( 'Edit Room type', 'wa' ),
            'update_item'                => __( 'Update Room type', 'wa' ),
            'add_new_item'               => __( 'Add New Room type', 'wa' ),
            'new_item_name'              => __( 'New Room type Name', 'wa' ),
            'separate_items_with_commas' => __( 'Separate Room types with commas', 'wa' ),
            'add_or_remove_items'        => __( 'Add or remove writers', 'wa' ),
            'choose_from_most_used'      => __( 'Choose from the most used Room types', 'wa' ),
            'not_found'                  => __( 'No Room types found.', 'wa' ),
            'menu_name'                  => __( 'Room types', 'wa' ),
        );

        $args = array(
            'hierarchical'          => true,
            'labels'                => $labels,
            'show_ui'               => true,
            'archive'               => true,
            'show_admin_column'     => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var'             => true,
            'rewrite'               => array( 'slug' => __('Room-type', 'wa') ),
        );

        register_taxonomy( 'ch_room_type', array('ch_room', ''), $args );

    }




    /**
     * Localisation
     */
    public function load_plugin_textdomain()
    {
        $locale = apply_filters('plugin_locale', get_locale(), 'wa');
        $dir = trailingslashit(WP_LANG_DIR);

        load_textdomain('cornerhotel', $dir . 'cornerhotel/cornerhotel-' . $locale . '.mo');
        load_plugin_textdomain('cornerhotel', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    
    
}


return new PluginName();