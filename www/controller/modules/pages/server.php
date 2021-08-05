<?php
class Class_server{
  public static function getPage($thisarray){
   header('Content-type:application/json;charset=utf-8'); 
    global $website;
    global $maindir;
    session_start();
    $err = array();
    $msg = array();
    if(!empty($thisarray["p1"]) && !empty($_SESSION['user'])) {
        switch($thisarray["p1"]) {
            case 'listupdhistory':  Class_server::listupdhist($thisarray["p2"]);  break;
            case 'backupapp':  Class_server::backupapp($thisarray["p2"]);  break;
            case 'downloadupd':  Class_server::downloadupd($thisarray["p2"]);  break;
            case 'saveupd':  Class_server::saveupd($thisarray["p2"]);  break;
            case 'restoreupd':  Class_server::restoreupd($thisarray["p2"]);  break;
            default: echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"please use the API correctly."));exit;
                    }
  } else { echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"please use the API correctly."));exit;  }
 }
 public static function listupdhist(){
    global $website;
    global $maindir;
    global $lastupdate;
    $url="https://midleo.com/api/checkupdate";
    $ch = curl_init();
    if(!empty($website['proxy_host'])){
    curl_setopt_array($ch, array(
    CURLOPT_PROXY=>$website['proxy_host'].":".$website['proxy_port'],
    CURLOPT_PROXYUSERPWD=>$_SESSION['user'].":".documentClass::decryptIt($_SESSION['usrpwd']),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FAILONERROR => true,
    CURLOPT_URL =>$url,
    CURLOPT_POST =>1,
    CURLOPT_CONNECTTIMEOUT => 30,
    CURLOPT_TIMEOUT=>30,
    CURLOPT_HTTPPROXYTUNNEL=>true,
    CURLOPT_SSL_VERIFYPEER=> false,
    CURLOPT_POSTFIELDS=>"lickey=".$website['licensekey']."&lastupdate=".$lastupdate,
    CURLOPT_USERAGENT => 'MidlEO app user agent:'.$website['licensekey']
    ));
    } else {
    curl_setopt_array($ch, array(
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_URL =>$url,
    CURLOPT_POST =>1,
    CURLOPT_FAILONERROR => true,
    CURLOPT_CONNECTTIMEOUT => 30,
    CURLOPT_TIMEOUT=>30,
    CURLOPT_SSL_VERIFYPEER=> false,
    CURLOPT_POSTFIELDS=>"lickey=".$website['licensekey']."&lastupdate=".$lastupdate,
    CURLOPT_USERAGENT => 'MidlEO app user agent:'.$website['licensekey']
    ));
    }
   $result= curl_exec ($ch);
   if (curl_errno($ch)) {
      echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>curl_error($ch)));
   } else {
     echo json_encode(array('success'=>true,'type'=>"success","code"=>curl_getinfo($ch, CURLINFO_HTTP_CODE),'resp'=>json_decode($result,true)),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
   }
   curl_close ($ch);
   exit;
 }
 public static function backupapp(){
    global $website;
    global $maindir;
    $msg = "";
    $zip = new ZipArchive();
    $zipfile = $maindir."/data/backup/".date('Ymd').".zip";
    if(!file_exists($zipfile)){
    if ($zip->open($zipfile, ZipArchive::CREATE)!==TRUE) {
       $msg="<li class='list-group-item'>Problem openning <$filename></li>";
    }
    $excludes = array(
      'files' => array(),
      'dirs' => array('data','images')
    );
    $filter=$excludes['dirs'];
    $files = new RecursiveIteratorIterator(
    new RecursiveCallbackFilterIterator(
     new RecursiveDirectoryIterator(
      $maindir,
      RecursiveDirectoryIterator::SKIP_DOTS
      ),
     function ($file, $key, $iterator) use ($filter) {
      return $file->isFile() || !in_array($file->getBaseName(), $filter);
     }
    )
     );
     foreach ($files as $name => $file)
       {  
         if (!$file->isDir() && !in_array($file->getFilename(),$excludes['files']))
         { 
         $filePath = $file->getRealPath();
         $relativePath = substr($filePath, strlen($maindir) + 1);
         $zip->addFile($filePath, $relativePath);
         }
      }
     $zip->close();
     $msg="<li class='list-group-item'>Backup created: ".date('d.m.Y')."</li>";
    } else { $msg="<li class='list-group-item'>You have already created archive for today:".date('d.m.Y')."</li>"; }
    echo json_encode(array('success'=>true,'type'=>"success",'resp'=>$msg),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
 }
 public static function downloadupd(){
    global $website;
    global $maindir;
    $resultmsg="";
    $url="https://midleo.com/api/getupdate";
    $ch = curl_init();
    if(!empty($website['proxy_host'])){
    curl_setopt_array($ch, array(
    CURLOPT_PROXY=>$website['proxy_host'].":".$website['proxy_port'],
    CURLOPT_PROXYUSERPWD=>$_SESSION['user'].":".documentClass::decryptIt($_SESSION['usrpwd']),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FAILONERROR => true,
    CURLOPT_URL =>$url,
    CURLOPT_POST =>1,
    CURLOPT_CONNECTTIMEOUT => 30,
    CURLOPT_TIMEOUT=>30,
    CURLOPT_HTTPPROXYTUNNEL=>true,
    CURLOPT_SSL_VERIFYPEER=> false,
    CURLOPT_POSTFIELDS=>"lickey=".$website['licensekey'],
    CURLOPT_USERAGENT => 'MidlEO app user agent:'.$website['licensekey']
    ));
    } else {
    curl_setopt_array($ch, array(
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_URL =>$url,
    CURLOPT_FAILONERROR => true,
    CURLOPT_POST =>1,
    CURLOPT_CONNECTTIMEOUT => 30,
    CURLOPT_TIMEOUT=>30,
    CURLOPT_SSL_VERIFYPEER=> false,
    CURLOPT_POSTFIELDS=>"lickey=".$website['licensekey'],
    CURLOPT_USERAGENT => 'MidlEO app user agent:'.$website['licensekey']
    ));
    }
   $result= curl_exec ($ch);
   if (curl_errno($ch)) {
      echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>curl_error($ch)));
   } else {
     unlink($maindir.'/data/temp/midleo_archive.zip');
     if (file_put_contents($maindir.'/data/temp/midleo_archive.zip', $result)){ $resultmsg="MIDLEO file downloaded successfully."; };  
     echo json_encode(array('success'=>true,'type'=>"success",'resp'=>$resultmsg),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
   }
   curl_close ($ch);
   exit; 
  }
  public static function restoreupd(){
   global $website;
   global $maindir;
   $resultmsg="";
   $zip = new ZipArchive();
   $zipfile = $maindir.'/data/temp/midleo_archive.zip';
   if ($zip->open($zipfile) === TRUE) {
      $zip->extractTo($maindir.'/data/temp/');
      $zip->close();
      $resultmsg="MIDLEO update file extracted.";
   } else {
      $resultmsg="Problem extracting the update file.";
   }
   documentClass::rCopy($maindir.'/data/temp/midleo/web',$maindir);
   documentClass::rRD($maindir.'/data/temp/midleo');
   documentClass::houseKeep($maindir.'/data/backup',"3","zip");
   echo json_encode(array('success'=>true,'type'=>"success",'resp'=>$resultmsg),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
   exit;
  }
  public static function saveupd(){
   global $website;
   global $maindir;
   if(!is_array($website)){ $website=json_decode($website,true); }
   $newSettings = array(
      'appver' => $_SESSION['appnewvernum'],
      'lastupdate' => date('Y-m-d')
   );
   textClass::change_config('controller/config.vars.php', $newSettings);
    gTable::track($_SESSION["userdata"]["usname"],$_SESSION['user'], array("appid"=>"system"), "Updated application to version:<a href='/appconfig/update'>".htmlspecialchars($_SESSION['appnewver'])."</a>");
    $_SESSION["appnewver"]="no";
    echo json_encode(array('success'=>true,'type'=>"success",'resp'=>"Data saved."),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    exit;
  }
}