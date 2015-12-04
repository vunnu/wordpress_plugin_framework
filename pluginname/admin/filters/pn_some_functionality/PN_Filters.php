<?php
/**
 * Created by PhpStorm.
 * User: vadimk
 * Date: 2014-11-21
 * Time: 10:29
 */

namespace PN_Task;

class PN_Filters {


    public function __construct() {

        // Include filter classes
        include_once('PN_Filter_ProjectTasks.php');

    }
}

return new PN_Filters();