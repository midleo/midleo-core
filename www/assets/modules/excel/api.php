<?php
class Class_excelimportapi{
  public static function getPage($thisarray){
    header('Content-type:application/json;charset=utf-8'); 
    global $website;
    global $maindir;
    session_start();
    $err = array();
    $msg = array();
    if(!empty($thisarray["p1"]) && !empty($_SESSION['user'])) {
    switch($thisarray["p1"]) {
      case 'importibmmq': Class_excelimportapi::importIBMMQ();  break;
      case 'importfw': Class_excelimportapi::importFW();  break;
      case 'importpj': Class_excelimportapi::importPJ();  break;
      default: echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"please use the API correctly."));exit;
      }
  } else { 
    echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"please use the API correctly."));exit;  
  }
  }
  public static function importIBMMQ(){
    $pdo = pdodb::connect();
    if (!empty($_FILES['dfile'])) { 
     $impfile = Excel::importIbmMQ($_FILES['dfile']);
     if (!empty($impfile["err"])) { $err[] = $impfile["err"]; }
     if (!empty($impfile["impobj"])) { $respok = "<h4>Imported MQ objects:</h4> " . $impfile["impobj"];}
     if (!empty($impfile["nimpobj"])) { $resperr = "<h4>Objects already exist:</h4> " . $impfile["nimpobj"];}
     if ($impfile["i"] > 0) {
        $sql = "insert into mqenv_imported_files (impfile,proj,impobjects,impby) values (?,?,?,?)";
        $q = $pdo->prepare($sql);
        $q->execute(array($impfile["filename"], $_POST["appid"], $impfile["i"], $_SESSION['user']));
       gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($_POST["appid"])), "Imported IBM MQ file with objects <a href='/env/import/".htmlspecialchars($_POST["appid"])."'>" . $impfile["filename"] . "</a><br>Imported objects: " . $impfile["impobj"]);
     } else {
        $resperr .= "<br><br><h4>No new objects were imported!</h4>";
     }
     if (file_exists("data/env/temp/" . $impfile["filename"])) { unlink("data/env/temp/" . $impfile["filename"]);}
        echo json_encode(array('respok'=>$respok,'resperr'=>$resperr,'lines'=>$impfile["i"],'errorlog'=>$err,'filename'=>$impfile["filename"]));exit;  
     } else {
        echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"File empty"),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);exit;  
     }
    pdodb::disconnect();
    exit;
  }
  public static function importFW(){
    $pdo = pdodb::connect();
    if (!empty($_FILES['dfile'])) { 
     $impfile = Excel::importFW($_FILES['dfile']); 
     if (!empty($impfile["err"])) { $err[] = $impfile["err"]; }
     if (!empty($impfile["impobj"])) { $respok = "<h4>Imported FW rules:</h4> " . $impfile["impobj"];}
     if (!empty($impfile["nimpobj"])) { $resperr = "<h4>Rules already exist:</h4> " . $impfile["nimpobj"];}
     if ($impfile["i"] > 0) {
      gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>htmlspecialchars($_POST["appid"])), "Imported Firewall rules file <a href='/env/firewall/".htmlspecialchars($_POST["appid"])."'>" . $impfile["filename"] . "</a><br>Imported objects: " . $impfile["impobj"]);
    } else {
      $resperr .= "<br><br><h4>No new rules were imported!</h4>";
   }
   if (file_exists("data/env/temp/" . $impfile["filename"])) { unlink("data/env/temp/" . $impfile["filename"]);}
      echo json_encode(array('respok'=>$respok,'resperr'=>$resperr,'lines'=>$impfile["i"],'errorlog'=>$err,'filename'=>$impfile["filename"]));exit;  
   } else {
      echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"File empty"),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);exit;  
   }
  pdodb::disconnect();
  exit;
  }
  public static function importPJ(){
   $pdo = pdodb::connect();
   if (!empty($_FILES['dfile'])) { 
    $impfile = Excel::importPJ($_FILES['dfile']); 
    if (!empty($impfile["err"])) { $err[] = $impfile["err"]; }
    if (!empty($impfile["impobj"])) { $respok = "<h4>Imported Projects:</h4> " . $impfile["impobj"];}
    if (!empty($impfile["nimpobj"])) { $resperr = "<h4>Projects already exist:</h4> " . $impfile["nimpobj"];}
    if ($impfile["i"] > 0) {
      gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>"system"), "Imported Projects from file <a href='/projects'>" . $impfile["filename"] . "</a><br>Imported projects: " . $impfile["impobj"]);
   } else {
     $resperr .= "<br><br><h4>No new projects were imported!</h4>";
  }
  if (file_exists("data/env/temp/" . $impfile["filename"])) { unlink("data/env/temp/" . $impfile["filename"]);}
     echo json_encode(array('respok'=>$respok,'resperr'=>$resperr,'lines'=>$impfile["i"],'errorlog'=>$err,'filename'=>$impfile["filename"]));exit;  
  } else {
     echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"File empty"),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);exit;  
  }
 pdodb::disconnect();
 exit;
 }
}