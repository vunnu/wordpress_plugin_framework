<?php
/**
 * Created by PhpStorm.
 * User: vadimk
 * Date: 2014-11-21
 * Time: 10:29
 */

namespace PN_Task;

class PN_Columns {


    public function __construct() {

        // Include filter classes
        include_once('PN_Column_Functionality.php');

    }
}

return new PN_Columns();