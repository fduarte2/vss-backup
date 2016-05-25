<?php

require_once $config->classPath.'/modules/common.php';

/**
*
*
* @package modules
* @access public
* @author Wolfram Kriesing <wolfram@kriesing.de>
* @version $Id$
*/
class modules_travel_kmLog extends modules_common
{

    var $table = TABLE_KMLOG;

    /**
    *   set the default join and order for a new instance
    *
    *   @author     Wolfram Kriesing <wk@visionp.de>
    */
    function modules_travel_kmLog()
    {
        parent::modules_common();
        $this->preset();
    }

    function preset()
    {
        $this->setJoin(TABLE_USER,TABLE_USER.'.id=user_id');
    }
    
    function save($data)
    {
        global $applError;

        $ret = true;
        
        // convert timestamp
        if ($data['timestamp_date']) {
            $date = explode('.',$data['timestamp_date']);
            $data['timestamp'] = mktime(0,0,0,$date[1],$date[0],$date[2]);
            unset($data['timestamp_date']);
        }
        // check data a bit
        if (!$data['timestamp']) {
            $applError->set('Please specify a valid date!');
            $ret = false;
        }
        
        $data['numKm'] = str_replace(',','.',$data['numKm']);
        if (!is_numeric($data['numKm'])) {
            $applError->set('\'km\' has to be numeric!');
            $ret = false;
        }
        if (!$ret) {
            return false;
        }
        return parent::save($data);
    }
    
}   // end of class

$kmLog = new modules_travel_kmLog;

?>
