<?php
/**
 * Created by PhpStorm.
 * User: vadimk
 * Date: 2014-11-21
 * Time: 10:57
 */

class PN_Filter{

    private $name = 'pn_some_functionality';

	public function __construct() {

		add_action( 'admin_init', array( $this, 'include_filter_handlers' ) );

	}
    

	/**
	 * Include filter handlers
	 */
	public function include_filter_handlers() {


		include($this->name . '/PN_Filters.php');
	}


}

return new PN_Filter();