<?php
class Class_dropboxapi
{
    public static function getPage($thisarray)
    {
        header('Content-type:application/json;charset=utf-8'); 
        global $website;
        global $maindir;
        session_start();
        $err = array();
        $msg = array();
        if (!empty($thisarray["p1"]) && !empty($_SESSION['user'])) {
            switch ($thisarray["p1"]) {
                case 'updtag':Class_dropboxapi::updateTag($thisarray["p2"]);
                    break;
                default:echo json_encode(array('error' => true, 'type' => "error", 'errorlog' => "please use the API correctly."));exit;
            }
        } else {echo json_encode(array('error' => true, 'type' => "error", 'errorlog' => "please use the API correctly."));exit;}
    }
    public static function updateTag($d1){
        $pdo = pdodb::connect();
        if(isset($_POST["file_id"])){
            foreach($_POST as $key => $value) {
               $data[$key] = inputClass::filter($value);
            }
            $sql="select count(id) from external_files where fileid=?";
            $q = $pdo->prepare($sql);
            $q->execute(array($data["file_id"]));
            if($q->fetchColumn()>0){ 
                $sql="update external_files set tags=?, reqid=?, appcode=?, srvlist=?, appsrvlist=?, impuser=? where fileid=?";
                $q = $pdo->prepare($sql);
                $q->execute(array($data["tags"],$data["reqname"],$data["appname"],$data["serverlist"],$data["appserverlist"],$data["thisuser"],$data["file_id"]));
            } else {
               $sql="insert into external_files (tags,reqid,appcode,srvlist,appsrvlist,filetype,fileid,file_name,impuser,filelink) values (?,?,?,?,?,?,?,?,?,?)";
               $q = $pdo->prepare($sql);
               $q->execute(array($data["tags"],$data["reqname"],$data["appname"],$data["serverlist"],$data["appserverlist"],$data["file_type"],$data["file_id"],$data["file_name"],$data["thisuser"],$data["file_link"]));
            }
              echo json_encode(array('success'=>true ));
           } else {
             echo json_encode(array('success'=>false ));
           }
        pdodb::disconnect();
        gTable::closeAll();
    }
}