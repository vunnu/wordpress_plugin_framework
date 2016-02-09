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

        $post_types = array(
            array(
                'id' => PLUGINNAME_DOMAIN . '_product',
                'name_single' => 'Product',
                'name_plural' => 'Products',
                'args' => array(
                    'rewrite' => array('product' => __('', PLUGINNAME_DOMAIN)),
                    'supports' => array('title', 'editor', 'thumbnail')
                )
            )
        );


        $taxonomies = array(
            array(
                'id' => PLUGINNAME_DOMAIN . '_product_category',
                'name_single' => 'Product category',
                'name_plural' => 'Product categories',
                'posts' => array(PLUGINNAME_DOMAIN . '_product', ),
                'args' => array(
                    'rewrite' => array( 'slug' => __('Product-category', PLUGINNAME_DOMAIN) )
                )
            ),
        );

        foreach($post_types as $post_type)
        {
            // Add new custom post type
            $labels = array(
                'name' => _x($post_type['name_plural'], 'Product plural name', PLUGINNAME_DOMAIN),
                'singular_name' => _x($post_type['name_single'], 'Product singular name', PLUGINNAME_DOMAIN),
                'menu_name' => _x($post_type['name_plural'], 'admin menu', PLUGINNAME_DOMAIN),
                'name_admin_bar' => _x($post_type['name_single'], 'add new on admin bar', PLUGINNAME_DOMAIN),
                'add_new' => _x('Add New', $post_type['name_single'], PLUGINNAME_DOMAIN),
                'add_new_item' => __('Add New ' . $post_type['name_single'], PLUGINNAME_DOMAIN),
                'new_item' => __('New ' . $post_type['name_single'], PLUGINNAME_DOMAIN),
                'edit_item' => __('Edit ' . $post_type['name_single'], PLUGINNAME_DOMAIN),
                'view_item' => __('View ' . $post_type['name_single'], PLUGINNAME_DOMAIN),
                'all_items' => __('Our ' . $post_type['name_plural'], PLUGINNAME_DOMAIN),
                'search_items' => __('Search ' . $post_type['name_plural'], PLUGINNAME_DOMAIN),
                'parent_item_colon' => __('Parent ' . $post_type['name_single'] . ':', PLUGINNAME_DOMAIN),
                'not_found' => __('No '. $post_type['name_plural'] .' found.', PLUGINNAME_DOMAIN),
                'not_found_in_trash' => __('No '. $post_type['name_plural'] .' found in Trash.', PLUGINNAME_DOMAIN)
            );

            $args = array(
                'labels' => $labels,
                'public' => true,
                'publicly_queryable' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'query_var' => true,
                'capability_type' => 'post',
                'has_archive' => true,
                'hierarchical' => false,
                'menu_position' => null,
            );

            array_merge($args, $post_type['args']);

            register_post_type($post_type['id'], $args);
        }


        foreach ($taxonomies as $taxonomy) {

            // Add new taxonomy, NOT hierarchical (like tags)
            $labels = array(
                'name'                       => _x( $taxonomy['name_plural'], 'taxonomy general name', PLUGINNAME_DOMAIN ),
                'singular_name'              => _x( $taxonomy['name_plural'], 'taxonomy singular name', PLUGINNAME_DOMAIN ),
                'search_items'               => __( 'Search ' . $taxonomy['name_single'], PLUGINNAME_DOMAIN ),
                'popular_items'              => __( 'Popular ' . $taxonomy['name_plural'], PLUGINNAME_DOMAIN ),
                'all_items'                  => __( 'All ' . $taxonomy['name_plural'], PLUGINNAME_DOMAIN ),
                'parent_item'                => null,
                'parent_item_colon'          => null,
                'edit_item'                  => __( 'Edit ' . $taxonomy['name_single'], PLUGINNAME_DOMAIN ),
                'update_item'                => __( 'Update ' . $taxonomy['name_single'], PLUGINNAME_DOMAIN ),
                'add_new_item'               => __( 'Add New ' . $taxonomy['name_single'], PLUGINNAME_DOMAIN ),
                'new_item_name'              => __( 'New ' . $taxonomy['name_single'] . ' Name', PLUGINNAME_DOMAIN ),
                'separate_items_with_commas' => __( 'Separate ' . $taxonomy['name_plural'] . ' with commas', PLUGINNAME_DOMAIN ),
                'add_or_remove_items'        => __( 'Add or remove ' . $taxonomy['name_plural'], PLUGINNAME_DOMAIN ),
                'choose_from_most_used'      => __( 'Choose from the most used ' . $taxonomy['name_plural'], PLUGINNAME_DOMAIN ),
                'not_found'                  => __( 'No ' . $taxonomy['name_plural'] . ' found.', PLUGINNAME_DOMAIN ),
                'menu_name'                  => __( $taxonomy['name_plural'], PLUGINNAME_DOMAIN ),
            );

            $args = array(
                'hierarchical'          => true,
                'labels'                => $labels,
                'show_ui'               => true,
                'archive'               => true,
                'show_admin_column'     => true,
                'update_count_callback' => '_update_post_term_count',
                'query_var'             => true,
            );

            array_merge($args, $taxonomy['args']);

            register_taxonomy( $taxonomy['id'], $taxonomy['posts'], $args );
        }


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