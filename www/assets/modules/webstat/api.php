<?php
class Class_webstatapi
{
    public static function getPage($thisarray)
    {
        global $website;
        global $maindir;
        session_start();
        $err = array();
        $msg = array();
        if (!empty($thisarray["p1"]) && (!empty($_SESSION['requser']) || !empty($_SESSION['user']))) {
            switch ($thisarray["p1"]) {
                case 'readqm':Class_webstatapi::readIBMmq();  break;
                default:echo json_encode(array('error' => true, 'type' => "error", 'errorlog' => "please use the API correctly."));exit;
            }
        } else {echo json_encode(array('error' => true, 'type' => "error", 'errorlog' => "please use the API correctly."));exit;}
    }
   public static function readIBMmq(){
       
   }
    
}
