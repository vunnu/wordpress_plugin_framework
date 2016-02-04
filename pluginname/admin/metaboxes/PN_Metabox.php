<?php
/**
 * Created by PhpStorm.
 * User: vadimk
 * Date: 2014-11-21
 * Time: 10:57
 */

class PN_Metabox{

	private $meta_boxes = array();

	public function __construct()
    {

		$this->meta_boxes[] = include('pn_post_type/Fields.php');


		add_action( 'admin_enqueue_scripts', array( $this, 'styles_and_scripts' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 10, 1 );
	}


	/**
	 * Add admin styles
	 */
	public function styles_and_scripts()
    {

		global $post, $wp_scripts;
	}


	/**
	 * Add meta boxes to edit product page
	 */
	public function add_meta_boxes() {

		foreach ( $this->meta_boxes as $meta_box ) {
			foreach ( $meta_box->post_types as $post_type ) {
				add_meta_box(
					$meta_box->id,
					$meta_box->title,
					array( $meta_box, 'meta_box_inner' ),
					$post_type,
					$meta_box->context,
					$meta_box->priority
				);
			}
		}
	}


}

return new PN_Metabox();