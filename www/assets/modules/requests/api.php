<?php
class Class_reqapi
{
    public static function getPage($thisarray)
    {
        global $website;
        global $maindir;
        session_start();
        $err = array();
        $msg = array();
        if (!empty($thisarray["p1"]) && !empty($_SESSION['user'])) {
            switch ($thisarray["p1"]) {
                case 'readreq':Class_reqapi::readRequest($thisarray["p2"]);  break;
                case 'readureq':Class_reqapi::readURequest($thisarray["p2"]); break;
                case 'delreq':Class_reqapi::delRequest();  break;
                case 'confirmreq': Class_reqapi::confirmReq(); break;
                case 'approvereq': Class_reqapi::approveReq(); break;  
                case 'deployreq': Class_reqapi::deployReq(); break;
                case 'addreq':Class_reqapi::addRequest(); break;
                default:echo json_encode(array('error' => true, 'type' => "error", 'errorlog' => "please use the API correctly."));exit;
            }
        } else {echo json_encode(array('error' => true, 'type' => "error", 'errorlog' => "please use the API correctly."));exit;}
    }
    public static function readRequest($d1)
    {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            if ($data->type == "opened" || empty($data->type)) {$assigned = " and (t.assigned!='done' and t.assigned!='canceled' " . (DBTYPE == 'oracle' ? "and NULLIF(t.assigned, '') IS NULL" : "") . ")";} elseif ($data->type == "completed") {$assigned = " and (t.assigned='done' or t.assigned='canceled')";} else { $assigned = " and t.assigned='" . $data->user . "'";}
            $sql = "select ".(DBTYPE=='oracle'?"to_char(w.wdata) as wdata":"w.wdata")." , t.* from requests t, config_workflows w where w.wid=t.wid and requser=?" . $assigned;
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute(array($data->user))) {
                $zobj = $stmt->fetchAll();
                $data = array();
                foreach ($zobj as $val) {
                    $data['name'] = $val['reqname'];
                    $data['reqabout'] = $val['reqtype'];
                    $data['sname'] = $val['sname'];
                    $data['statusicon'] = "mdi mdi-circle-outline statusicon  text-done";
                    $data['status'] = "1";
                    if (!empty($val['assigned']) && $val['assigned'] != "done" && $val['assigned'] != "canceled") {
                        $data['status'] = "2";
                        $data['statusicon'] = "mdi mdi-account-circle statusicon text-working";
                    }
                    if ($val['assigned'] == "done" || $val['assigned'] == "canceled") {
                        $data['status'] = "4";
                        $data['statusicon'] = "mdi mdi-circle statusicon text-notblink";
                    }
                    if (date("Y-m-d", strtotime($val['deadline'])) != "2001-01-01" && $val['assigned'] != "done" && $val['assigned'] != "canceled" && strtotime(date('Y-m-d', strtotime(date('Y-m-d') . " +3 days"))) >= strtotime(date("Y-m-d", strtotime($val['deadline'])))) {
                        $data['status'] = "0";
                        $data['statusicon'] = "mdi mdi-alert-circle statusicon text-red text-blink";
                    }
                    if(!empty($val["wdata"])){
                      $temparr=json_decode($val['wdata'],true);
                      $data['statusinfo']=($val['requser']==$val['assigned']?"secondary":"info");
                      $data['statusinfotxt']=($val['requser']==$val['assigned']?"Customer":$temparr["nodes"][$val["wfstep"]][0]["label"]); 
                    }
                    $data['reqactive']=($val['requser']==$val['assigned']?1:0);
                    $data['link'] = $val['sname'];
                    $data['created'] = date("d.m.y", strtotime($val['created']));
                    $data['deadline'] = (date("Y-m-d", strtotime($val['deadline'])) == "2001-01-01") ? "No" : date("d.m.y", strtotime($val['deadline']));
                    $data['assigned'] = !empty($val['assigned']) ? $val['assigned'] : "Not yet";
                    $data['delreqbut'] = !empty($val['assigned']) ? "display:none;" : "";
                    $newdata[] = $data;
                }
            }
            pdodb::disconnect();
            echo json_encode($newdata, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            gTable::closeAll();
    }
    public static function delRequest()
    {
        $pdo = pdodb::connect();
        $data = json_decode(file_get_contents("php://input"));
        //$sql="delete from requests where sname=? and (assigned is null or assigned='')";
        $sql = "update requests set assigned='canceled',deployed_by=? where sname=? and (assigned is null or assigned='')";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array(htmlspecialchars($data->user), $data->reqid))) {
            documentClass::rRD("data/requests/" . htmlspecialchars($data->reqid));
            echo "Request was deleted";
            gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("reqid"=>$data->reqid,"appid"=>"system"), "Canceled the request " . $data->reqid);
        } else {
            echo "Request cannot be deleted";
        }
        pdodb::disconnect();
        exit;
    }
    public static function readURequest($d1)
    {
        global $priorityarr;
        if ($d1 == "one") {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "select * from requests where and sname='" . $data->reqid . "'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            if ($zobj = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data = array();
                $data['name'] = $zobj['reqname'];
                $data['sname'] = $zobj['sname'];
                $data['info'] = $zobj['info'];
                $data['requser'] = $zobj['requser'];
                $data['efforts'] = $zobj['efforts'];
                $data['projname'] = $zobj['projnum'];
                $data['files'] = !empty($zobj['reqfile']) ? $zobj['sname'] : "";
                $data['filesname'] = !empty($zobj['reqfile']) ? $zobj['reqfile'] : "";
                $data['assign'] = !empty($zobj['assigned']) ? "" : "assign";
                $data['assigned'] = $zobj['assigned'];
                $data['confirmed'] = $zobj['projconfirmed'] == 1 ? "display:none;" : "display:inline-block;";
                $data['updreq'] = (!empty($zobj['assigned']) && $_SESSION['user'] == $zobj['assigned']) ? "display:inline-block;" : "display:none;";
                $data['assigndisp'] = !empty($zobj['assigned']) ? "display:none;" : "display:inline-block;";
                $data['assigntxt'] = !empty($zobj['assigned']) ? "" : "Assign";
                $data['deadline'] = $zobj['deadline'] == "2001-01-01" ? "No deadline" : date("Y-m-d", strtotime($zobj['deadline']));
            }
            pdodb::disconnect();
            echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            gTable::closeAll();
        } else {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            if(empty($_SESSION["userdata"]["widarr"])){ $_SESSION["userdata"]["widarr"] = array(); $argwidarr=0; } else { $argwidarr=1;} 
            if ($data->type == "opened" || empty($data->type)) {
                if (DBTYPE == 'oracle') {
                    $assigned = " and (NULLIF(t.assigned, 'done') IS NULL and NULLIF(t.assigned, 'canceled') IS NULL and NULLIF(t.assigned, '') IS NULL)";
                } else {
                    $assigned = " and (t.assigned!='done' and t.assigned!='canceled')";
                }
            } elseif ($data->type == "completed") {$assigned = " and (t.assigned='done' or t.assigned='canceled')";} else {if (DBTYPE == 'oracle') {
                $assigned = " and (NULLIF(t.assigned, 'done') IS NULL and NULLIF(t.assigned, 'canceled') IS NULL and NULLIF(t.assigned, '') IS NULL)";
            } else {
                $assigned = " and t.assigned is not NULL and t.assigned<>'done' and t.assigned<>'canceled'";
            }
            }

            if (!empty($data->grid)) {
                $partsgr = explode(",", $data->grid); 
                $partsgr[] = $_SESSION["user"];
            } else {
                $partsgr = array();
                $partsgr[] = $_SESSION["user"];
            }
            if(empty($_SESSION["userdata"]["widarr"])){
                $sql = "select " . (DBTYPE == 'oracle' ? "to_char(w.wdata) as wdata" : "w.wdata") . " , t.* from requests t, config_workflows w where w.wid=t.wid and (t.wfunit in (" . str_repeat('?,', count($partsgr) - 1) . '?' . ") or t.requser=?) " . $assigned;
                $stmt = $pdo->prepare($sql); 
                $stmt->execute(array_merge($partsgr, array($_SESSION["user"])));
            } elseif($data->own=="own") {
                $sql = "select " . (DBTYPE == 'oracle' ? "to_char(w.wdata) as wdata" : "w.wdata") . " , t.* from requests t, config_workflows w where w.wid=t.wid and t.wfunit in (" . str_repeat('?,', count($partsgr) - 1) . '?' . ") " . $assigned;
                $stmt = $pdo->prepare($sql); 
                $stmt->execute($partsgr);
            } else {
                $sql = "select " . (DBTYPE == 'oracle' ? "to_char(w.wdata) as wdata" : "w.wdata") . " , t.* from requests t, config_workflows w where w.wid=t.wid and (t.wfunit in (" . str_repeat('?,', count($partsgr) - 1) . '?' . ") or t.wid in (" . str_repeat('?,', count($_SESSION["userdata"]["widarr"]) - $argwidarr) . '?' . ")) " . $assigned;
                $stmt = $pdo->prepare($sql); 
                $stmt->execute(array_merge($partsgr, $_SESSION["userdata"]["widarr"]));
            } 
            $newdata = array();
            if ($zobj = $stmt->fetchAll()) {
                foreach ($zobj as $val) {
                    $data = array();
                    $data['reqid'] = $val['id'];
                    $data['reqabout'] = $val['reqtype'];
                    $data['filesname'] = !empty($val['reqfile']) ? $val['reqfile'] : "";
                    $data['sname'] = $val['sname'];
                    $data['statusicon'] = "mdi mdi-circle-outline statusicon  text-done";
                    $data['status'] = "1";
                    if (!empty($val["wdata"])) {
                        $temparr = json_decode($val['wdata'], true);
                        $data['statusinfo'] = ($val['requser'] == $val['assigned'] ? "secondary" : "info");
                        $data['statusinfotxt'] = ($val['requser'] == $val['assigned'] ? "Customer" : $temparr["nodes"][$val["wfstep"]][0]["label"]);
                    }
                    $data['priorityval'] = $val['priority'];
                    $data['priority'] = $priorityarr[$val['priority']];
                    if (!empty($val['assigned']) && $val['assigned'] != "done" && $val['assigned'] != "canceled") {
                        $data['status'] = "2";
                        $data['statusicon'] = "mdi mdi-account-circle statusicon text-working";
                    }
                    if ($val['assigned'] == "done" || $val['assigned'] == "canceled") {
                        $data['status'] = "4";
                        $data['statusicon'] = "mdi mdi-circle statusicon text-notblink";
                    }
                    if (date("Y-m-d", strtotime($val['deadline'])) != "2001-01-01" && $val['assigned'] != "done" && $val['assigned'] != "canceled" && strtotime(date('Y-m-d', strtotime(date('Y-m-d') . " +3 days"))) >= strtotime(date("Y-m-d", strtotime($val['deadline'])))) {
                        $data['status'] = "0";
                        $data['statusicon'] = "mdi mdi-alert-circle statusicon text-red text-blink";
                    }
                    $data['reqactive']=($_SESSION["user"]==$val['assigned']?1:0);
                    $data['sname'] = $val['sname'];
                    $data['name'] = $val['reqname'];
                    $data['requser'] = $val['requser'];
                    $data['changed'] = date("d F/Y", strtotime($val['modified']));
                    $data['deadline'] = (date("Y-m-d", strtotime($val['deadline'])) == "2001-01-01") ? "No" : date("d.m.y", strtotime($val['deadline']));
                    $data['assigned'] = !empty($val['assigned']) ? $val['assigned'] : "Not yet";
                    $newdata[] = $data;
                }
            }
            pdodb::disconnect();
            echo json_encode($newdata, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            gTable::closeAll();
        }
    }
    public static function confirmReq(){
        $pdo = pdodb::connect();
        $data = json_decode(file_get_contents("php://input"));
        $sql="update requests set projconfirmed=1 where sname=?";
        $q = $pdo->prepare($sql);
        $nowtime = new DateTime();
        $now=$nowtime->format('Y-m-d H:i').":00";
         if($q->execute(array(htmlspecialchars($data->reqid)))){
          $sql="update requests_confirmation set confuser=?, conffullname=?, confdate='".$now."' where reqid=? and projid=?";
          $q = $pdo->prepare($sql);
          $q->execute(array($_SESSION["user"],htmlspecialchars($data->fullname),htmlspecialchars($data->reqid),htmlspecialchars($data->project)));
          gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("reqid"=>$data->reqid,"appid"=>"system"), "Confirmed the request " . $data->reqid);
          echo "Request confirmed";
         }
        pdodb::disconnect(); 
        exit;
      }
      public static function approveReq(){
        $pdo = pdodb::connect();
        $data = json_decode(file_get_contents("php://input"));
        $sql="update requests set projapproved=1 where sname=?";
        $q = $pdo->prepare($sql);
        $nowtime = new DateTime();
        $now=$nowtime->format('Y-m-d H:i').":00";
         if($q->execute(array(htmlspecialchars($data->reqid)))){
          $sql="update requests_approval set appruser=?, apprfullname=?, apprdate='".$now."' where reqid=? and projid=?";
          $q = $pdo->prepare($sql);
          $q->execute(array($_SESSION["user"],htmlspecialchars($data->fullname),htmlspecialchars($data->reqid),htmlspecialchars($data->project)));
          $sql="select r.projnum,r.wid,w.wfcurcost from requests r, config_workflows w where r.sname=? and r.wid=w.wid"; 
          $q = $pdo->prepare($sql);
          if($q->execute(array(htmlspecialchars($data->reqid)))){
              $zobj = $q->fetch(PDO::FETCH_ASSOC);
              $sql="update config_projects set budgetspent=budgetspent+? where projcode=?";
              $q = $pdo->prepare($sql);
              $q->execute(array($zobj["wfcurcost"],$zobj["projnum"]));
          }
          gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("reqid"=>$data->reqid,"appid"=>"system"), "Approved the request " . $data->reqid);
          echo "Request approved";
         }
        pdodb::disconnect(); 
        exit;
       }
      public static function deployReq(){
        $pdo = pdodb::connect();
        $data = json_decode(file_get_contents("php://input"));
        $sql="select * from requests_deployments where reqid=?";
        $q = $pdo->prepare($sql);
         if($q->execute(array(htmlspecialchars($data->reqid)))){
           $zobj = $q->fetch(PDO::FETCH_ASSOC);
           $datain=json_decode($zobj['deployedin'],true);
           $penv=explode("#",$data->env);
           $datain[$penv[0]]["name"]=$penv[1];
           $datain[$penv[0]]["package"]=$data->pkgname;
           $datain[$penv[0]]["results"]="";
           $datain=json_encode($datain,true);
           $sql="update requests_deployments set deployedin=? where id=?";
           $q = $pdo->prepare($sql);
           $q->execute(array($datain,$zobj['id']));
           gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("reqid"=>$data->reqid,"appid"=>"system"), "Deleted package:".htmlspecialchars($data->pkgname)."in env:".$penv[1]);
           echo "Deployed";
         }
         pdodb::disconnect(); 
        exit;
      }
}
