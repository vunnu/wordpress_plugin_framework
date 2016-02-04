<?php

define('PLUGINNAME_VERSION', '1.0.2');
define('PLUGINNAME_DOMAIN', 'pluginname');
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
        /*
         * Custom scripts for post type admin pages
         */

//        global $post_type;
//        if (in_array($post_type, array())) {
//
//            wp_register_style('bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css', array(), '1.0', 'all');
//            wp_enqueue_style('bootstrap'); // Enqueue it!
//        }

    }


    public function load_scripts()
    {
        if(is_admin())
        {
            /**
             * admin scripts here
             * wp_register_script('', PLUGINNAME_RESOURCES_URL . '../components/../', array(''), '');
             * wp_enqueue_script('');
             */
        }
    }


    // Including post types
    public function register_post_types()
    {

        // Add new custom post type
        $labels = array(
            'name' => _x('Room', 'Room plural name', PLUGINNAME_DOMAIN),
            'singular_name' => _x('Room', 'Room singular name', PLUGINNAME_DOMAIN),
            'menu_name' => _x('Rooms', 'admin menu', PLUGINNAME_DOMAIN),
            'name_admin_bar' => _x('Room', 'add new on admin bar', PLUGINNAME_DOMAIN),
            'add_new' => _x('Add New', 'fund', PLUGINNAME_DOMAIN),
            'add_new_item' => __('Add New Room', PLUGINNAME_DOMAIN),
            'new_item' => __('New Room', PLUGINNAME_DOMAIN),
            'edit_item' => __('Edit Room', PLUGINNAME_DOMAIN),
            'view_item' => __('View Room', PLUGINNAME_DOMAIN),
            'all_items' => __('Our Rooms', PLUGINNAME_DOMAIN),
            'search_items' => __('Search Room', PLUGINNAME_DOMAIN),
            'parent_item_colon' => __('Parent Room:', PLUGINNAME_DOMAIN),
            'not_found' => __('No funds found.', PLUGINNAME_DOMAIN),
            'not_found_in_trash' => __('No funds found in Trash.', PLUGINNAME_DOMAIN)
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => __('room', PLUGINNAME_DOMAIN)),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array('title', 'editor', 'thumbnail')
        );

        register_post_type('ch_room', $args);



        // Add new taxonomy, NOT hierarchical (like tags)
        $labels = array(
            'name'                       => _x( 'Room type', 'taxonomy general name', PLUGINNAME_DOMAIN ),
            'singular_name'              => _x( 'Room type', 'taxonomy singular name', PLUGINNAME_DOMAIN ),
            'search_items'               => __( 'Search asset class', PLUGINNAME_DOMAIN ),
            'popular_items'              => __( 'Popular asset class', PLUGINNAME_DOMAIN ),
            'all_items'                  => __( 'All Room types', PLUGINNAME_DOMAIN ),
            'parent_item'                => null,
            'parent_item_colon'          => null,
            'edit_item'                  => __( 'Edit Room type', PLUGINNAME_DOMAIN ),
            'update_item'                => __( 'Update Room type', PLUGINNAME_DOMAIN ),
            'add_new_item'               => __( 'Add New Room type', PLUGINNAME_DOMAIN ),
            'new_item_name'              => __( 'New Room type Name', PLUGINNAME_DOMAIN ),
            'separate_items_with_commas' => __( 'Separate Room types with commas', PLUGINNAME_DOMAIN ),
            'add_or_remove_items'        => __( 'Add or remove writers', PLUGINNAME_DOMAIN ),
            'choose_from_most_used'      => __( 'Choose from the most used Room types', PLUGINNAME_DOMAIN ),
            'not_found'                  => __( 'No Room types found.', PLUGINNAME_DOMAIN ),
            'menu_name'                  => __( 'Room types', PLUGINNAME_DOMAIN ),
        );

        $args = array(
            'hierarchical'          => true,
            'labels'                => $labels,
            'show_ui'               => true,
            'archive'               => true,
            'show_admin_column'     => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var'             => true,
            'rewrite'               => array( 'slug' => __('Room-type', PLUGINNAME_DOMAIN) ),
        );

        register_taxonomy( 'ch_room_type', array('ch_room', ''), $args );

    }




    /**
     * Localisation
     */
    public function load_plugin_textdomain()
    {
        $locale = apply_filters('plugin_locale', get_locale(), PLUGINNAME_DOMAIN);
        $dir = trailingslashit(WP_LANG_DIR);

        load_textdomain(PLUGINNAME_DOMAIN, $dir . PLUGINNAME_DOMAIN . DIRECTORY_SEPARATOR . PLUGINNAME_DOMAIN . '-' . $locale . '.mo');
        load_plugin_textdomain(PLUGINNAME_DOMAIN, false, dirname(plugin_basename(__FILE__)) . DIRECTORY_SEPARATOR . 'languages');
    }

    
    
}


return new PluginName();