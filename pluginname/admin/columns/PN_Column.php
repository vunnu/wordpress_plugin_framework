<?php
/**
 * Created by PhpStorm.
 * User: vadimk
 * Date: 2014-11-21
 * Time: 10:57
 */

class PN_Column{

    public function __construct() {

        add_action( 'admin_init', array( $this, 'include_columns_handlers' ) );

    }


    /**
     * Include meta box handlers
     */
    public function include_columns_handlers() {

        include('pn_some_functionality/PN_Columns.php');

    }


}

return new PN_Column();