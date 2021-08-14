<?php
$modulelist["excel"]["name"]="Import/Export to Excel";
include_once "api.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;    
  class Excel{
    public static function import($inputFileName,$inputFileType=null){
      if (!file_exists($inputFileName)) { 
        exit("Please upload file first.");
      }
      require_once 'controller/vendor/autoload.php'; 
      if($inputFileType=="ibmmq"){
        $array_data['qm']=array(
          'A'=>'active',
          'B'=>'proj',
          'C'=>'qmgr',
          'D'=>'descr',
          'E'=>'deadq',
          'F'=>'defxmitq',
          'G'=>'repos',
          'H'=>'reposnl',
          'I'=>'CCSID',
          'J'=>'maxhands',
          'K'=>'maxmsgl',
          'J'=>'maxumsg',
        );
        $array_data['prepost']=array(
          'A'=>'active',
          'B'=>'proj',
          'C'=>'qmgr',
          'D'=>'command',
        );
        $array_data['queues']=array(
          'A'=>'active',
          'B'=>'proj',
          'C'=>'qmgr',
          'D'=>'name',
          'E'=>'descr',
          'F'=>'type',
          'G'=>'usage',
          'H'=>'cluster',
          'I'=>'clusnl',
          'J'=>'maxmsgl',
          'K'=>'maxdepth',
          'L'=>'boqname',
          'M'=>'bothresh',
          'N'=>'target',
          'O'=>'rqmname',
          'P'=>'xmitq',
          'Q'=>'defbind',
        );
         $array_data['topics']=array(
          'A'=>'active',
          'B'=>'proj',
          'C'=>'qmgr',
          'D'=>'name',
          'E'=>'topicstr',
          'F'=>'descr',
          'G'=>'cluster',
          'H'=>'defpsist',
          'I'=>'pub',
          'J'=>'pubscope',
          'K'=>'sub',
          'L'=>'subscope',
          'M'=>'wildcard',
        );
        $array_data['subs']=array(
          'A'=>'active',
          'B'=>'proj',
          'C'=>'qmgr',
          'D'=>'name',
          'E'=>'topicstr',
          'F'=>'topicobj',
          'G'=>'dest',
          'H'=>'destqmgr',
          'I'=>'selector',
          'J'=>'subscope',
        );
        $array_data['authrec']=array(
          'A'=>'active',
          'B'=>'proj',
          'C'=>'qmgr',
          'D'=>'authtype',
          'E'=>'name',
          'F'=>'group',
          'G'=>'authority',
        );
        $array_data['channels']=array(
          'A'=>'active',
          'B'=>'proj',
          'C'=>'qmgr',
          'D'=>'name',
          'E'=>'descr',
          'F'=>'chltype',
          'G'=>'xmitq',
          'H'=>'locladdr',
          'I'=>'clusnl',
          'J'=>'cluster',
          'K'=>'conname',
          'L'=>'maxmsgl',
          'M'=>'mcauser',
          'N'=>'sslcauth',
          'O'=>'sslciph',
          'P'=>'sslpeer',
        );
        $array_data['nl']=array(
          'A'=>'active',
          'B'=>'proj',
          'C'=>'qmgr',
          'D'=>'name',
          'E'=>'descr',
          'F'=>'names',
        );
        $array_data['process']=array(
          'A'=>'active',
          'B'=>'proj',
          'C'=>'qmgr',
          'D'=>'name',
          'E'=>'descr',
          'F'=>'applicid',
          'G'=>'appltype',
          'H'=>'envrdata',
        );
        $array_data['dlqh']=array(
          'A'=>'active',
          'B'=>'proj',
          'C'=>'qmgr',
          'D'=>'name',
        );
        $array_data['vars']=array(
          'A'=>'active',
          'B'=>'proj',
          'C'=>'env',
          'D'=>'qmgr',
          'E'=>'name',
          'F'=>'val',
        );
      } elseif($inputFileType=="firewall"){
        $array_data['firewall']=array(
          'A'=>'active',
          'B'=>'proj',
          'C'=>'port',
          'D'=>'srcip',
          'E'=>'destip',
          'F'=>'srcdns',
          'G'=>'destdns',
          'H'=>'tags',
          'I'=>'info',
        );
      } elseif($inputFileType=="projects"){
        $array_data['projects']=array(
          'A'=>'active',
          'B'=>'projcode',
          'C'=>'projyear',
          'D'=>'tags',
          'E'=>'projinfo',
        );
      }
      if(!empty($array_data)){
        $spreadsheet = IOFactory::load($inputFileName);
        $loadedSheetNames = $spreadsheet->getSheetNames();
        foreach ($loadedSheetNames as $keysheet => $valsheet) {
        $spreadsheet->setActiveSheetIndex($keysheet);
        $rownum=$spreadsheet->getActiveSheet()->getHighestRow();
        for ($x = 0; $x <= $rownum; $x++) {
            if($spreadsheet->getActiveSheet()->getCell('A'.$x)->getValue()=="ACTIVE"){
              $row = $spreadsheet->getActiveSheet()->getRowIterator($x)->current();
              $cellIterator = $row->getCellIterator();
              $cellIterator->setIterateOnlyExistingCells(false); 
              $temparr=array();$i=0;
              foreach ($cellIterator as $cell) { 
                if($cell->getValue()!=null){ 
                    $temparr[$array_data[$valsheet][$cell->getColumn()]] = strtolower($cell->getValue());
                }
              }
              $data[strtolower($valsheet)][$i][]=$temparr;
              $i++;
            }
          } 
        }
        return str_replace(array("[[","]]"),array("[","]"),json_encode($data,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));  
      } else {
        return json_encode(array("err"=>"Import type not specified"),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
      }
    }
    public static function importIbmMQ($inputFile){
      $uplfile = documentClass::FilesArange($inputFile);
      $msg[] = documentClass::uploaddocument($uplfile[0], "data/env/temp/") . "<br>";
      $alldata = Excel::import("data/env/temp/" . $uplfile[0]["name"],"ibmmq");
      $data = json_decode($alldata, true);
      if (!empty($data)) {
          $pdo = pdodb::connect();
          $i = 0;
          $impobj = "";
          $nimpobj = "";
          foreach ($data as $key => $val) {
              if ($key == "vars") {
                  foreach ($val as $keyin => $valin) {
                      $objtype = "var";
                      $objname = $valin['name'];
                      $qmgr = $valin['qmgr'];
                      $proj = $valin['proj'];
                      unset($valin['active'], $valin['proj'], $valin['qmgr'], $valin['name']);
                      $i++;
                  }
              } elseif ($key == "prepost") {
                  foreach ($val as $keyin => $valin) {
                      $objtype = "prepost";
                      $objname = "command";
                      $qmgr = $valin['qmgr'];
                      $proj = $valin['proj'];
                      unset($valin['active'], $valin['proj'], $valin['qmgr'], $valin['name']);
                      $i++;
                  }
              } elseif ($key == "authrec") {
                  foreach ($val as $keyin => $valin) {
                      $objtype = $valin['authtype'];
                      $objname = $valin['name'];
                      $qmgr = $valin['qmgr'];
                      $proj = $valin['proj'];
                      unset($valin['active'], $valin['proj'], $valin['qmgr'], $valin['name']);
                      $authority = $valin['authority'];
                      $kt = explode(" ", $authority);
                      while (list($key, $val) = each($kt)) {
                          if ($val != " " && strlen($val) > 0) {
                              $valin['authrec'][] = htmlspecialchars($val);
                          }
                      }
                      unset($valin['authority']);
                      unset($valin['authtype']);
                      $sql = "select count(id) as total from mqenv_mq_authrec where qmgr=? and objname=? and proj=?";
                      $q = $pdo->prepare($sql);
                      $q->execute(array($qmgr, $objname, $proj));
                      if ($q->fetchColumn() == 0) {
                          $i++;
                          $objdata = json_encode($valin, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                          $sql = "insert into mqenv_mq_authrec (proj,qmgr,objname,objdata,projinfo) values(?,?,?,?,'imported')";
                          $q = $pdo->prepare($sql);
                          $q->execute(array($proj, $qmgr, $objname, $objdata));
                          $impobj .= $objname . ",";
                      } else {
                          $nimpobj .= $objname . ",";
                      }
                  }
              } elseif ($key == "dlqh") {
                  foreach ($val as $keyin => $valin) {
                      $objtype = "dlqh";
                      $objname = $valin['name'];
                      $qmgr = $valin['qmgr'];
                      unset($valin['active'], $valin['proj'], $valin['qmgr'], $valin['name']);
                      $sql = "select count(id) as total from mqenv_mq_" . $key . " where  qmgr=? and objname=? and proj=?";
                      $q = $pdo->prepare($sql);
                      $q->execute(array($qmgr, $objname, $proj));
                      if ($q->fetchColumn() == 0) {
                          $i++;
                          $sql = "insert into mqenv_mq_" . $key . " (proj,qmgr,objname,projinfo) values(?,?,?,'imported')";
                          $q = $pdo->prepare($sql);
                          $q->execute(array($proj, $qmgr, $objname));
                          $impobj .= $objname . ",&nbsp;";
                      } else {
                          $nimpobj .= $objname . ",&nbsp;";
                      }
                  }
              } elseif ($key == "fte") {
                  foreach ($val as $keyin => $valin) {
                      $i++;
                  }
              } else {
                  foreach ($val as $keyin => $valin) {
                      $qmgr = $valin['qmgr'];
                      $proj = $valin['proj'];
                      if ($key == "qm") {$objtype = "qmgr";
                          $objname = $valin['qmgr'];} elseif ($key == "queues") {$objtype = $valin['type'];
                          $objname = $valin['name'];} elseif ($key == "topics") {$objtype = "topic";
                          $objname = $valin['name'];} elseif ($key == "subs") {$objtype = "sub";
                          $objname = $valin['name'];} elseif ($key == "channels") {$objtype = "channel";
                          $objname = $valin['name'];} elseif ($key == "nl") {$objtype = "nl";
                          $objname = $valin['name'];} elseif ($key == "process") {$objtype = "process";
                          $objname = $valin['name'];}
                      unset($valin['proj'], $valin['qmgr']);
                      $sql = "select count(id) as total from mqenv_mq_" . $key . " where qmgr=? and objname=? and objtype=?";
                      $q = $pdo->prepare($sql);
                      $q->execute(array($qmgr, $objname, $objtype));
                      if ($q->fetchColumn() == 0) {
                          $i++;
                          $objdata = json_encode($valin, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                          $sql = "insert into mqenv_mq_" . $key . " (proj,qmgr,objname,objtype,objdata,projinfo) values(?,?,?,?,?,'imported')";
                          $q = $pdo->prepare($sql);
                          $q->execute(array($proj, $qmgr, $objname, $objtype, $objdata));
                          $impobj .= $objname . ",&nbsp;&nbsp;";
                      } else {
                          $nimpobj .= $objname . ",&nbsp;&nbsp;";
                      }
                  }
              }

          }
          pdodb::disconnect();
          return array("i"=>$i,"impobj"=>$impobj,"nimpobj"=>$nimpobj,"filename"=>$uplfile[0]["name"]);
        } else {
          return array("err"=>"Empty data");
        }
    }
    public static function importFW($inputFile){ 
      $uplfile = documentClass::FilesArange($inputFile);
      $msg[] = documentClass::uploaddocument($uplfile[0], "data/env/temp/") . "<br>";
      $alldata = Excel::import("data/env/temp/" . $uplfile[0]["name"],"firewall");
      $data = json_decode($alldata, true); 
      if (!empty($data)) {
          $pdo = pdodb::connect();
          $i = 0;
          $impobj = "";
          $nimpobj = ""; 
          foreach ($data["firewall"] as $keyin => $valin) { 
            $sql = "select count(id) as total from env_firewall where port=? and srcip=? and destip=?";
            $q = $pdo->prepare($sql);
            $q->execute(array($valin["port"], $valin["srcip"], $valin["destip"]));
            if ($q->fetchColumn() == 0) {
                $i++;
                $sql = "insert into env_firewall (proj,tags,port,srcip,destip,srcdns,destdns,info) values(?,?,?,?,?,?,?,?)";
                $q = $pdo->prepare($sql);
                $q->execute(array($valin["proj"],$valin["tags"].",imported",$valin["port"],$valin["srcip"],$valin["destip"],$valin["srcdns"],$valin["destdns"],$valin["info"]));
                $impobj .= $valin["destip"].":".$valin["port"] . ",&nbsp;&nbsp;";
            } else {
                $nimpobj .= $valin["destip"].":".$valin["port"] . ",&nbsp;&nbsp;";
            }

          }
          pdodb::disconnect();
          return array("i"=>$i,"impobj"=>$impobj,"nimpobj"=>$nimpobj,"filename"=>$uplfile[0]["name"]);
        } else {
          return array("err"=>"Empty data");
        }
    }
    public static function importPJ($inputFile){ 
      $uplfile = documentClass::FilesArange($inputFile);
      $msg[] = documentClass::uploaddocument($uplfile[0], "data/env/temp/") . "<br>";
      $alldata = Excel::import("data/env/temp/" . $uplfile[0]["name"],"projects");
      $data = json_decode($alldata, true); 
      if (!empty($data)) {
          $pdo = pdodb::connect();
          $i = 0;
          $impobj = "";
          $nimpobj = ""; 
          foreach ($data["projects"] as $keyin => $valin) { 
            $sql = "select count(id) as total from config_projects where projcode=? and projyear=?";
            $q = $pdo->prepare($sql);
            $q->execute(array($valin["projcode"], $valin["projyear"]));
            if ($q->fetchColumn() == 0) {
                $i++;
                $sql = "insert into config_projects (projcode,projyear,tags,projinfo) values(?,?,?,?)";
                $q = $pdo->prepare($sql);
                $q->execute(array($valin["projcode"],$valin["projyear"],$valin["tags"].",imported",$valin["projinfo"]));
                $impobj .= $valin["projcode"]."/".$valin["projyear"] . ",&nbsp;&nbsp;";
            } else {
                $nimpobj .= $valin["projcode"]."/".$valin["projyear"] . ",&nbsp;&nbsp;";
            }

          }
          pdodb::disconnect();
          return array("i"=>$i,"impobj"=>$impobj,"nimpobj"=>$nimpobj,"filename"=>$uplfile[0]["name"]);
        } else {
          return array("err"=>"Empty data");
        }
    }
  }