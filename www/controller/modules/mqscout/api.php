<?php
class Class_mqapi
{
    public static function getPage($thisarray)
    {
        global $website;
        global $maindir;
        global $typesrv;
        session_start();
        $err = array();
        $msg = array();
        if (!empty($thisarray["p1"]) && !empty($_SESSION['user'])) {
            switch ($thisarray["p1"]) {
                case 'add':Class_mqapi::add($thisarray["p2"]);
                    break;
                case 'addvar':Class_mqapi::addVar();
                    break;
                case 'addfte':Class_mqapi::addfte();
                    break;
                case 'dell':Class_mqapi::dell($thisarray["p2"]);
                    break;
                case 'duplicate':Class_mqapi::duplicate($thisarray["p2"]);
                    break;
                case 'delvar':Class_mqapi::delVar();
                    break;
                case 'dellfte':Class_mqapi::delfte();
                    break;
                case 'dellflows':Class_mqapi::dellFlows();
                    break;
                case 'applications':Class_mqapi::Applications($thisarray["p2"]);
                    break;
                case 'dellflow':Class_mqapi::dellFlow();
                    break;
                case 'update':Class_mqapi::update($thisarray["p2"]);
                    break;
                case 'updatevar':Class_mqapi::updateVar();
                    break;
                case 'updatefte':Class_mqapi::updatefte();
                    break;
                case 'read':Class_mqapi::read($thisarray["p2"], $thisarray["p3"]);
                    break;
                case 'readfte':Class_mqapi::readfte($thisarray["p2"]);
                    break;
                case 'readflows':Class_mqapi::readFlows();
                    break;
                case 'readflow':Class_mqapi::readFlow();
                    break;
                case 'addflow':Class_mqapi::addFlow();
                    break;
                case 'readpack':Class_mqapi::readPackage();
                    break;
                case 'readimp':Class_mqapi::readImported();
                    break;
                case 'readdepl':Class_mqapi::readDeployments();
                    break;
                case 'mqsc':Class_mqapi::createMqsc($thisarray["p2"], $thisarray["p3"]);
                    break;
                case 'auth':Class_mqapi::createAuth($thisarray["p2"], $thisarray["p3"]);
                    break;
                case 'dlqh':Class_mqapi::createDlqh($thisarray["p2"]);
                    break;
                case 'fte':Class_mqapi::createFte($thisarray["p2"], $thisarray["p3"]);
                    break;
                default:echo json_encode(array('error' => true, 'type' => "error", 'errorlog' => "please use the API correctly."));exit;
            }
        } else {echo json_encode(array('error' => true, 'type' => "error", 'errorlog' => "please use the API correctly."));exit;}
    }
    public static function update($d1)
    {
        $pdo = pdodb::connect();
        $nowtime = new DateTime();
        $now = $nowtime->format('Y-m-d H:i') . ":00";
        $data = json_decode(file_get_contents("php://input"));
        $newobjects = array();
        foreach ($data->mq as $key => $val) {
            if (!empty($val)) {
                $newobjects[$key] = $val;
            }
            //   if(empty($val)){unset($newobjects[$key]);}
        }
        if ($data->mq->type == "auth") {
            $newobjects["authrec"] = array();
            foreach ($data->mq->authrec as $valauth) {
                if (!empty($valauth)) {
                    if (!in_array($valauth, $newobjects["authrec"])) {
                        $newobjects["authrec"][] = htmlspecialchars($valauth);
                    }
                }
            }
        }
        unset($newobjects['qid']);
        unset($newobjects['qmid']);
        $sql = "update mqenv_mq_" . $d1 . " set qmgr=?,objname=?,objdata=?,proj=?,changed='" . $now . "' where id=?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array($data->mq->qm, $data->mq->name, json_encode($newobjects, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), $data->mq->proj, $data->mq->qmid))) {
            gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => $data->mq->proj), "Updated " . $d1 . " with name:<a href='/env/" . $d1 . "/" . $data->mq->proj . "'>" . htmlspecialchars($data->mq->name) . "</a>");
            if (!empty(htmlspecialchars($data->mq->tags))) {
                gTable::dbsearch(htmlspecialchars(!empty($data->mq->name) ? $data->mq->name : $data->mq->qm), $_SERVER["HTTP_REFERER"], htmlspecialchars($data->mq->tags));
            }
            echo "Object was changed.";
        } else {
            echo "Unable to update object.";
        }
        pdodb::disconnect();
    }
    public static function dell($d1)
    {
        $pdo = pdodb::connect();
        $data = json_decode(file_get_contents("php://input"));
        $sql = "delete from mqenv_mq_" . $d1 . " where id=?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array($data->qmid))) {
            gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => $data->projid), "Deleted " . $d1 . " with name:<a href='/env/" . $d1 . "/" . $data->projid . "'>" . htmlspecialchars($data->qid) . "</a>");
            echo "Object was deleted.";
        } else {
            echo "Unable to delete object.";
        }
        pdodb::disconnect();
    }
    public static function add($d1)
    {
        $pdo = pdodb::connect();
        $data = json_decode(file_get_contents("php://input"));
        $newobjects = array();
        foreach ($data->mq as $key => $val) {
            $newobjects[$key] = $val;
        }
        if ($data->mq->type == "auth") {
            $newobjects["authrec"] = array();
            foreach ($data->mq->authrec as $valauth) {
                if ($valauth) {
                    $newobjects["authrec"][] = htmlspecialchars($valauth);
                }
            }
        }
        $sql = "insert into mqenv_mq_" . $d1 . " (proj,qmgr,objname,objtype,objdata,projinfo) values(?,?,?,?,?,'system')";
        $q = $pdo->prepare($sql);
        $q->execute(array(htmlspecialchars($data->projid), htmlspecialchars($data->mq->qm), htmlspecialchars($data->mq->name), htmlspecialchars($data->mq->type), json_encode($newobjects, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)));
        gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => $data->projid), "Created new " . $d1 . " with name:<a href='/env/" . $d1 . "/" . $data->projid . "'>" . htmlspecialchars($data->mq->name) . "</a>");
        if (!empty(htmlspecialchars($data->mq->tags))) {
            gTable::dbsearch(htmlspecialchars(!empty($data->mq->name) ? $data->mq->name : $data->mq->qm), $_SERVER["HTTP_REFERER"], htmlspecialchars($data->mq->tags));
        }
        echo $d1 . " was created.";
        pdodb::disconnect();
    }
    public static function read($d1, $d2)
    {
        if ($d2 == "one") {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            if ($d1 == "vars") {
                $sql = "select * from mqenv_vars where id=?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array($data->qmid));
                if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $objects['name'] = $row['varname'];
                    $objects['varname'] = $row['varname'];
                    $objects['varid'] = $row['id'];
                    $objects['proj'] = $row['proj'];
                    $objects['tags'] = $row['tags'];
                    $objects['vartype'] = $row['isarray'] == 1 ? "envrelated" : "envsame";
                    $objects['varvaluesame'] = $row['isarray'] == 1 ? "" : $row['varvalue'];
                    if ($row['isarray'] == 1) {
                        $tempobj = json_decode($row['varvalue'], true);
                        foreach ($tempobj as $key => $val) {
                            $objects['env'][$key] = $val;
                        }
                    }
                }
            } else {
                $sql = "SELECT id,qmgr,jobrun,jobid,proj,objname,objtype," . (DBTYPE == 'oracle' ? "to_char(objdata) as objdata" : "objdata") . " FROM mqenv_mq_" . $d1 . " where id=?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array($data->qmid));
                if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $objects = json_decode($row['objdata'], true);
                    $objects['name'] = $row['objname'];
                    $objects['qid'] = $row['objname'];
                    $objects['qmid'] = $row['id'];
                    $objects['jobrun'] = $row['jobrun'];
                    $objects['jobid'] = $row['jobid'];
                    $objects['proj'] = $row['proj'];
                    $objects['qm'] = $row['qmgr'];
                    $objects['type'] = strtolower($row['objtype']);
                    if (!empty($objects["authrec"])) {
                        foreach ($objects["authrec"] as $key => $val) {
                            unset($objects["authrec"][$key]);
                            $objects["authrec"][str_replace(array("+", "-"), "", $val)] = $val;
                        }
                    }
                }
            }
            pdodb::disconnect();
            echo json_encode($objects, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } else {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            if ($d1 == "vars") {
                $sql = "select * from mqenv_vars" . (!empty($data->projid) ? " where proj='" . $data->projid . "'" : "");
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $zobj = $stmt->fetchAll();
                $data = array();
                foreach ($zobj as $val) {
                    $data['name'] = $val['varname'];
                    $data['varname'] = $val['varname'];
                    $data['varvalue'] = $val['varvalue'];
                    $data['varid'] = $val['id'];
                    $data['proj'] = $val['proj'];
                    $newdata[] = $data;
                }
            } else {
                $sql = "SELECT id,jobrun,jobid,qmgr,proj,objname,objtype," . (DBTYPE == 'oracle' ? "to_char(objdata) as objdata" : "objdata") . " FROM mqenv_mq_" . $d1 . " where 1=1" .(!empty($data->qm) ? " and qmgr='" . $data->qm . "'" : ""). (!empty($data->projid) ? " and proj='" . $data->projid . "'" : "");
                $stmt = $pdo->prepare($sql); 
                $stmt->execute();
                $zobj = $stmt->fetchAll();
                $data = array();
                foreach ($zobj as $val) {
                    $data['objects'][$val['qmgr']][$val['objname']] = json_decode($val['objdata'], true);
                    $data['objects'][$val['qmgr']][$val['objname']]['name'] = $val['objname'];
                    $data['objects'][$val['qmgr']][$val['objname']]['qid'] = $val['objname'];
                    $data['objects'][$val['qmgr']][$val['objname']]['qm'] = $val['qmgr'];
                    $data['objects'][$val['qmgr']][$val['objname']]['qmid'] = $val['id'];
                    $data['objects'][$val['qmgr']][$val['objname']]['id'] = $val['id'];
                    $data['objects'][$val['qmgr']][$val['objname']]['appsrv'] = $val['qmgr'];
                    $data['objects'][$val['qmgr']][$val['objname']]['jobrun'] = $val['jobrun'];
                    $data['objects'][$val['qmgr']][$val['objname']]['jobid'] = $val['jobid'];
                    $data['objects'][$val['qmgr']][$val['objname']]['type'] = $val['objtype'];
                    $data['objects'][$val['qmgr']][$val['objname']]['proj'] = $val['proj'];
                    if($d1=="qm"){
                        $sqlin="select serverid,serverdns,serverip from env_appservers where serv_type='qm' and qmname=?";
                        $stmt = $pdo->prepare($sqlin);
                        $stmt->execute(array($val['qmgr']));
                        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $data['objects'][$val['qmgr']][$val['objname']]['server'] = $row["serverdns"];
                            $data['objects'][$val['qmgr']][$val['objname']]['serverid'] = $row["serverid"];
                        }
                    }
                    $newdata[] = $data['objects'][$val['qmgr']][$val['objname']];
                    $keys[] = array_keys($data['objects'][$val['qmgr']][$val['objname']]);
                }
                if (is_array($keys)) {
                    foreach (textClass::getL2Keys($keys) as $key) {
                        $d[$key] = "";
                    }
                }
            }
            pdodb::disconnect();
            if (!empty($d)) {
                echo "[" . json_encode($d, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "," . ltrim(json_encode($newdata, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), "[");
            } else {
                if (is_array($newdata)) {
                    echo "[" . ltrim(json_encode($newdata, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), "[");
                } else {
                    echo "[]";
                }
            }
        }
    }
    public static function addVar()
    {
        $pdo = pdodb::connect();
        $data = json_decode(file_get_contents("php://input"));
        foreach ($data->mq->env as $key => $val) {
            $varval[$key] = $val;
        }
        $vartype = $data->mq->vartype == "envrelated" ? 1 : 0;
        $varvalue = json_encode($varval, true);
        if ($data->mq->vartype == "envsame") {$varvalue = "";
            $varvalue = $data->mq->varvaluesame;}
        $sql = "insert into mqenv_vars (proj,varname,varvalue,isarray) values (?,?,?,?)";
        $q = $pdo->prepare($sql);
        if ($q->execute(array(htmlspecialchars($data->projid), strtoupper(htmlspecialchars($data->mq->name)), $varvalue, $vartype))) {
            gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => htmlspecialchars($data->projid)), "Defined new variable:<a href='/env/vars/" . htmlspecialchars($data->projid) . "'>" . htmlspecialchars($data->mq->name) . "</a>");
            if (!empty(htmlspecialchars($data->mq->tags))) {
                gTable::dbsearch(htmlspecialchars($data->mq->name), $_SERVER["HTTP_REFERER"], htmlspecialchars($data->mq->tags));
            }
            echo "The variable is saved";
        } else {
            echo "Problem saving the variable";
        }
        pdodb::disconnect();exit;
    }
    public static function updateVar()
    {
        $pdo = pdodb::connect();
        $data = json_decode(file_get_contents("php://input"));
        foreach ($data->mq->env as $key => $val) {
            $varval[$key] = $val;
        }
        $vartype = $data->mq->vartype == "envrelated" ? 1 : 0;
        $varvalue = json_encode($varval, true);
        if ($data->mq->vartype == "envsame") {$varvalue = "";
            $varvalue = $data->mq->varvaluesame;}
        $sql = "update mqenv_vars set varname=?, varvalue=?, isarray=?, tags=? where id=?";
        $q = $pdo->prepare($sql);
        if ($q->execute(array(strtoupper(htmlspecialchars($data->mq->name)), $varvalue, $vartype, htmlspecialchars($data->mq->tags), htmlspecialchars($data->mq->varid)))) {
            gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => htmlspecialchars($data->projid)), "Updated variable:<a href='/env/vars/" . htmlspecialchars($data->projid) . "'>" . htmlspecialchars($data->mq->name) . "</a>");
            if (!empty(htmlspecialchars($data->mq->tags))) {
                gTable::dbsearch(htmlspecialchars($data->mq->name), $_SERVER["HTTP_REFERER"], htmlspecialchars($data->mq->tags));
            }
            echo "The variable is updated";
        } else {
            echo "Problem updating the variable";
        }
        pdodb::disconnect();exit;
    }
    public static function delVar()
    {
        $pdo = pdodb::connect();
        $data = json_decode(file_get_contents("php://input"));
        $sql = "delete from mqenv_vars where id=?";
        $q = $pdo->prepare($sql);
        if ($q->execute(array(htmlspecialchars($data->varid)))) {
            gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => htmlspecialchars($data->projid)), "Deleted variable:<a href='/env/vars/" . htmlspecialchars($data->projid) . "'>" . htmlspecialchars($data->varname) . "</a>");
            echo "Variable deleted successfully";
        }
        pdodb::disconnect();exit;
    }
    public static function addfte()
    {
        $randomid = md5(uniqid(microtime()) . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
        $pdo = pdodb::connect();
        $data = json_decode(file_get_contents("php://input"));
        $keys = "";
        $vals = "";
        foreach ($data->mqfte as $key => $val) {
            $keys .= htmlspecialchars($key) . ",";
            $vals .= "'" . str_replace("'", "\"", htmlspecialchars($val)) . "',";
        }
        $sql = "insert into mqenv_mqfte (fteid,proj," . rtrim($keys, ',') . ") values(?,?," . rtrim($vals, ',') . ")";
        $q = $pdo->prepare($sql);
        if ($q->execute(array($randomid, htmlspecialchars($data->projid)))) {
            echo "FTE configuration was created";
            gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => htmlspecialchars($data->projid)), "Created new IBM MQ FTE configuration:<a href='/env/fte/" . htmlspecialchars($data->projid) . "'>" . htmlspecialchars($data->mqfte->mqftename) . "</a>");
            if (!empty(htmlspecialchars($data->mqfte->tags))) {
                gTable::dbsearch(htmlspecialchars($data->mqfte->mqftename), $_SERVER["HTTP_REFERER"], htmlspecialchars($data->mqfte->tags));
            }
        } else {
            echo "Error creating FTE configuration";
        }
        pdodb::disconnect();
    }
    public static function duplicate($d1)
    {
        $pdo = pdodb::connect();
        $data = json_decode(file_get_contents("php://input"));
        $sql = "insert into mqenv_mq_" . $d1 . " (proj,qmgr,objname,objtype,objdata,projinfo) select proj,qmgr,concat(objname,'.COPY'),objtype,objdata,projinfo from mqenv_mq_" . $d1 . " where id=?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array($data->qmid))) {
            echo "Object was copied.";
        } else {
            echo "Unable to copy object.";
        }
        pdodb::disconnect();
    }
    public static function delfte()
    {
        $pdo = pdodb::connect();
        $data = json_decode(file_get_contents("php://input"));
        $sql = "delete from mqenv_mqfte where fteid=?";
        $q = $pdo->prepare($sql);
        if ($q->execute(array(htmlspecialchars($data->fteid)))) {
            gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => htmlspecialchars($data->projid)), "Deleted file transfer configuration with ID:" . htmlspecialchars($data->fteid));
            echo "FTE configuration was deleted";
        } else {
            echo "Error deleting FTE configuration";
        }
        pdodb::disconnect();
        exit;
    }
    public static function dellFlows()
    {
        $pdo = pdodb::connect();
        $data = json_decode(file_get_contents("php://input"));
        $sql = "delete from iibenv_firewall where flowid=?";
        $q = $pdo->prepare($sql);
        if ($q->execute(array(htmlspecialchars($data->flowid)))) {
            documentClass::rRD("data/flows/env/" . htmlspecialchars($data->flowid));
            gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => htmlspecialchars($data->projid)), "Deleted flow configuration:<a href='/env/flows/" . htmlspecialchars($data->projid) . "'>" . htmlspecialchars($data->flowname) . "</a>");
            echo "Flow project was deleted";
        } else {
            echo "Error deleting flow project";
        }
        pdodb::disconnect();
    }
    public static function dellFlow()
    {
        $pdo = pdodb::connect();
        $data = json_decode(file_get_contents("php://input"));
        gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => htmlspecialchars($data->projid)), "Deleted file " . htmlspecialchars($data->file) . " in flow:<a href='/env/flows/" . htmlspecialchars($data->projid) . "'>" . htmlspecialchars($data->flowname) . "</a>");
        $thisfile = "data/flows/" . $data->type . "/" . htmlspecialchars($data->flowid) . "/" . htmlspecialchars($data->file);
        if (file_exists($thisfile)) {unlink($thisfile);
            echo "File deleted";} else {echo "File not found";}
        pdodb::disconnect();
        exit;
    }
    public static function readImported()
    {
        $pdo = pdodb::connect();
        $data = json_decode(file_get_contents("php://input"));
        $sql = "select * from mqenv_imported_files where proj=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($data->appid));
        $data = array();
        $data = $stmt->fetchAll(PDO::FETCH_CLASS);
        pdodb::disconnect();
        echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);exit;
    }
    public static function readDeployments()
    {
        global $env;
        $env = json_decode($env, true);
        $pdo = pdodb::connect();
        $data = json_decode(file_get_contents("php://input"));
        $sql = "select * from env_deployments where proj=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($data->appid));
        $zobj = $stmt->fetchAll();
        foreach ($zobj as $val) {
            $data = array();
            if (!empty($val['deplin'])) {
                $data['deployedin'] = $env[array_search($val['deplin'], array_column($env, 'nameshort'))]["name"];
            } else {
                $data['deployedin'] = "";
            }
            $data['depltype'] = $val['depltype'];
            $data['deplenv'] = $val['deplenv'];
            $data['depldate'] = $val['depldate'];
            $data['packuid'] = $val['packuid'];
            $data['deplobjects'] = $val['deplobjects'];
            $data['deplby'] = $val['deplby'];
            $data['packuid'] = $val['packuid'];
            $newdata[] = $data;
        }
        pdodb::disconnect();
        echo json_encode($newdata, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);exit;
    }
    public static function updatefte()
    {
        $pdo = pdodb::connect();
        $data = json_decode(file_get_contents("php://input"));
        $keys = "";
        foreach ($data->mqfte as $key => $val) {
            $keys .= htmlspecialchars($key) . "='" . str_replace("'", "\"", htmlspecialchars($val)) . "',";
        }
        $sql = "update mqenv_mqfte set " . rtrim($keys, ',') . " where fteid=?";
        $q = $pdo->prepare($sql);
        if ($q->execute(array(htmlspecialchars($data->mqfte->fteid)))) {
            gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => htmlspecialchars($data->projid)), "Updated IBM MQ FTE configuration:<a href='/env/fte/" . htmlspecialchars($data->projid) . "'>" . htmlspecialchars($data->mqfte->mqftename) . "</a>");
            if (!empty(htmlspecialchars($data->mqfte->tags))) {
                gTable::dbsearch(htmlspecialchars($data->mqfte->mqftename), $_SERVER["HTTP_REFERER"], htmlspecialchars($data->mqfte->tags));
            }
            echo "FTE configuration was updated";
        } else {
            echo "Error updating FTE configuration";
        }
        pdodb::disconnect();
    }
    public static function readfte($d1)
    {
        if ($d1 == "one") {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "select t.*," . (DBTYPE == 'oracle' ? "to_char(t.info) as info" : "t.info") . " from mqenv_mqfte t where t.fteid=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(htmlspecialchars($data->fteid)));
            $data = array();
            if ($zobj = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo json_encode($zobj, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            }
            pdodb::disconnect();
        } else {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "select t.*, t.mqftename as name,t.sourceagt as appsrv, " . (DBTYPE == 'oracle' ? "to_char(t.info) as info" : "t.info") . " from mqenv_mqfte t";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $data = array();
            $data = $stmt->fetchAll(PDO::FETCH_CLASS);
            pdodb::disconnect();
            echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }
    }
    public static function readFlows()
    {
        $pdo = pdodb::connect();
        $data = json_decode(file_get_contents("php://input"));
        if ($data->type == "env") {
            $sql = "select id,flowid,flowname,info," . (DBTYPE == 'oracle' ? "to_char(reqinfo) as reqinfo" : "reqinfo") . ",modified from iibenv_flows where projid=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(htmlspecialchars($data->projid)));
        } else {
            $sql = "select * from requests_data where reqid=? and reqtype='fte'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(htmlspecialchars($data->projid)));
        }
        $data = array();
        $data = $stmt->fetchAll(PDO::FETCH_CLASS);
        echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        pdodb::disconnect();
    }
    public static function readFlow()
    {
        $data = json_decode(file_get_contents("php://input"));
        $files = scandir("data/flows/" . $data->type . "/" . $data->flowid);
        $newdata = array();
        foreach ($files as $key => $value) {
            if (!in_array($value, array(".", ".."))) {
                $d['file'] = $value;
                if (method_exists("serverClass", "fsConvert") && is_callable(array("serverClass", "fsConvert"))) {
                    $d['size'] = filesize("data/flows/" . $data->type . "/" . $data->flowid . "/" . $value) == 0 ? filesize("data/flows/" . $data->type . "/" . $data->flowid . "/" . $value) : serverClass::fsConvert(filesize("data/flows/" . $data->type . "/" . $data->flowid . "/" . $value));
                } else {
                    $d['size'] = filesize("data/flows/" . $data->type . "/" . $data->flowid . "/" . $value);
                }
                $d['changed'] = date("d.m.Y H:i:s", filemtime("data/flows/" . $data->type . "/" . $data->flowid . "/" . $value));
                $newdata[] = $d;
            }
        }
        echo json_encode($newdata, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
    public static function addFlow()
    {
        $pdo = pdodb::connect();
        $uid = md5(uniqid(microtime()) . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
        $data = json_decode(file_get_contents("php://input"));
        $sql = "insert into iibenv_flows (projid,flowid,flowname,info) values(?,?,?,?)";
        $q = $pdo->prepare($sql);
        if ($q->execute(array(htmlspecialchars($data->projid), $uid, htmlspecialchars($data->flow->name), htmlspecialchars($data->flow->info)))) {
            if (!is_dir('data/flows/env/' . $uid)) {if (!mkdir('data/flows/env/' . $uid, 0755)) {echo "Cannot create flow dir<br>";}}
            gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => htmlspecialchars($data->projid)), "Created new message flow project:<a href='/env/flows/" . htmlspecialchars($data->projid) . "'>" . htmlspecialchars($data->flow->name) . "</a>");
            if (!empty(htmlspecialchars($data->flow->tags))) {
                gTable::dbsearch(htmlspecialchars($data->flow->name), $_SERVER["HTTP_REFERER"], htmlspecialchars($data->flow->tags));
            }
            echo "Flow project was created.";
        } else {
            echo "Unable to create flow project.";
        }
        pdodb::disconnect();
    }
    public static function createMqsc($d1, $d2, $d3 = "no", $inpdata = null)
    {
        global $maindir;
        global $env;
        $pdo = pdodb::connect();
        if ($d1 == "one") {
            if (!$inpdata) {
                $data = json_decode(file_get_contents("php://input"));
            } else {
                $data = json_decode($inpdata);
            }
            $arrayvars = json_decode("[{}]", true);
            $sql = "select varname,varvalue,isarray from mqenv_vars where proj=?";
            $q = $pdo->prepare($sql);
            $q->execute(array($d2));
            if ($zobj = $q->fetchAll()) {
                foreach ($zobj as $val) {
                    if ($val['isarray'] == 1) {
                        $arrayvars["{" . $val['varname'] . "}"] = array("isarray" => $val['isarray'], "val" => json_decode($val['varvalue'], true));
                    } else {
                        $arrayvars["{" . $val['varname'] . "}"] = array("isarray" => $val['isarray'], "val" => $val['varvalue']);
                    }
                }
            }
            if (!empty($env)) {$menudataenv = json_decode($env, true);} else { $menudataenv = array();}
            $sql = "SELECT id,proj,qmgr,objname,objtype," . (DBTYPE == 'oracle' ? "to_char(objdata) as objdata" : "objdata") . " FROM mqenv_mq_" . $data->what . " where id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($data->mq->qmid));
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $objects = json_decode($row['objdata'], true);
                $str = "";
                unset($objects['tags']);
                unset($objects['qm']);
                unset($objects['env']);
                unset($objects['jobrun']);
                unset($objects['jobid']);
                if ($d3 == "no") {
                    header("Access-Control-Allow-Origin: *");
                    header("Content-Type: text/html; charset=UTF-8");
                }
                if ($objects['type'] == "sub") {
                    $str .= "DELETE " . wordwrap("SUB('" . strtoupper($row['objname']) . "') \n", 63, "- \n ");
                }
                if ($data->what == "qm") {
                    $str .= "ALTER QMGR +\n";
                } else {
                    $str .= "DEFINE " . wordwrap(strtoupper($row['objtype']) . "('" . strtoupper($row['objname']) . "') + \n", 63, "- \n ");
                }
                if (!empty($objects['chltype'])) {
                    $str .= "   CHLTYPE(" . strtoupper($objects['chltype']) . ") +\n";
                    unset($objects['chltype']);
                }
                if (!empty($objects['maxmsgl'])) {
                    $str .= "   MAXMSGL(" . str_replace("?", "", mb_convert_encoding($objects['maxmsgl'], 'ASCII', 'UTF-8')) . ") +\n";
                    unset($objects['maxmsgl']);
                }
                unset($objects['name'], $objects['type'], $objects['active'], $objects['proj'], $objects['qmgr']);
                foreach ($objects as $key => $val) {
                    if (!empty($val)) {
                        if ($key == "trigger") {
                            $str .= "   " . strtoupper($val) . " +\n";
                        } else {
                            $str .= wordwrap("   " . strtoupper($key) . "(" . (!preg_match('/[^A-Za-z0-9]/', $val) ? ($key == "mcauser" ? "'" . $val . "'" : strtoupper($val)) : "'" . $val . "'") . ") +\n", 70, "- \n ");
                        }
                    }
                }
                if ($data->what == "qm") {
                    $str .= ";\n";
                } else {
                    $str .= "   REPLACE;\n";
                }
                $str = str_replace(array("- \n +", "+ \n -"), " + ", $str);
                if (is_array($menudataenv)) {foreach ($menudataenv as $keyenv => $valenv) {
                    $strarr[$valenv['nameshort']] = textClass::stage_array($str, $arrayvars, $valenv['nameshort']);
                }
                    if ($data->job) {
                        return json_encode(array('success' => true, 'type' => "success", 'resp' => $strarr), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    } else {
                        echo json_encode(array('success' => true, 'type' => "success", 'resp' => $strarr), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    }
                } else {
                    $str = textClass::stage_array($str, $arrayvars, "");
                    if ($data->job) {
                        return json_encode(array('success' => true, 'type' => "success", 'resp' => $str), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    } else {
                        echo json_encode(array('success' => true, 'type' => "success", 'resp' => $str), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    }
                }
            } else {
                if ($data->job) {
                    return json_encode(array('success' => false, 'type' => "error", 'resp' => "No such data"), JSON_UNESCAPED_UNICODE);
                } else {
                    echo json_encode(array('success' => false, 'type' => "error", 'resp' => "No such data"), JSON_UNESCAPED_UNICODE);
                }
            }
        } /*else {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: text/plain; charset=UTF-8");
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data->what)){
        } else {
        Class_mqapi::createMQSCALL($d2,$d3,htmlspecialchars($data->type));
        }
        }*/
        pdodb::disconnect();
    }
    public static function readPackage()
    {
        global $typesrv;
        global $env;
        $env = json_decode($env, true);
        $pdo = pdodb::connect();
        $data = json_decode(file_get_contents("php://input"));
        $sql = "select packuid,proj,packname,packuid,srvtype,gitprepared,deployedin,created_by,created_time,id from env_packages where proj=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($data->proj));
        $data = array();
        $zobj = $stmt->fetchAll();
        foreach ($zobj as $val) {
            if (!empty($val['deployedin'])) {
                $data['deployedin'] = array();
                foreach (json_decode($val['deployedin'], true) as $keyin => $valin) {
                    if (!empty($valin)) {
                        $data['deployedin'][] = $env[array_search($valin, array_column($env, 'nameshort'))]["name"];
                    }
                }

            } else {
                $data['deployedin'] = array("Not yet");
            }
            $data['packuid'] = $val['packuid'];
            $data['proj'] = $val['proj'];
            $data['typesrv'] = $typesrv[explode("#", $val['srvtype'], 2)[0]];
            $data['srvtype'] = explode("#", $val['srvtype'], 2)[0];
            $data['id'] = $val['id'];
            $data['name'] = $val['packname'];
            $data['isdeployed'] = !empty($val['deployedin']) ? true : false;
            $data['isgitprepared'] = $val['gitprepared'] == 0 ? false : true;
            $data['gitprepared'] = $val['gitprepared'] == 0 ? 'Not prepared' : 'Prepared';
            $data['gitpreparedspan'] = $val['gitprepared'] == 0 ? 'secondary' : 'info';
            $data['uid'] = $val['packuid'];
            $data['user'] = $val['created_by'];
            $data['released'] = date("d F/Y", strtotime($val['created_time']));
            $newdata[] = $data;
        }
        pdodb::disconnect();
        echo json_encode($newdata, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
    public static function createAuth($d1, $d2, $d3 = "no", $inpdata = null)
    {
        global $maindir;
        global $env;
        $pdo = pdodb::connect();
        if ($d1 == "one") {
            if (!$inpdata) {
                $data = json_decode(file_get_contents("php://input"));
            } else {
                $data = json_decode($inpdata);
            }
            $arrayvars = json_decode("[{}]", true);
            $sql = "select varname,varvalue,isarray from mqenv_vars where proj=?";
            $q = $pdo->prepare($sql);
            $q->execute(array($d2));
            if ($zobj = $q->fetchAll()) {
                foreach ($zobj as $val) {
                    if ($val['isarray'] == 1) {
                        $arrayvars["{" . $val['varname'] . "}"] = array("isarray" => $val['isarray'], "val" => json_decode($val['varvalue'], true));
                    } else {
                        $arrayvars["{" . $val['varname'] . "}"] = array("isarray" => $val['isarray'], "val" => $val['varvalue']);
                    }
                }
            }
            if (!empty($env)) {$menudataenv = json_decode($env, true);} else { $menudataenv = array();}
            if ($d3 == "no") {
                header("Access-Control-Allow-Origin: *");
                header("Content-Type: text/html; charset=UTF-8");
            }
            $sql = "SELECT qmgr,objname,objtype," . (DBTYPE == 'oracle' ? "to_char(objdata) as objdata" : "objdata") . " FROM mqenv_mq_authrec where id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($data->mq->qmid));
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $objects = json_decode($row['objdata'], true);
                $str = "";
                $str .= "#authority records for qmanager:" . $row['qmgr'] . "\n";
                $str .= "setmqaut -m " . $row['qmgr'] . " -n \"" . $row['objname'] . "\" -t " . $row['objtype'] . " -g \"" . $objects['group'] . "\"";
                foreach ($objects['authrec'] as $val) {
                    $str .= " " . $val;
                }
                $str .= "\n";
                if (is_array($menudataenv)) {
                    foreach ($menudataenv as $keyenv => $valenv) {
                        $strarr[$valenv['nameshort']] = textClass::stage_array($str, $arrayvars, $valenv['nameshort']);
                    }
                    if ($data->job) {
                        return json_encode(array('success' => true, 'type' => "success", 'resp' => $strarr), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    } else {
                        echo json_encode(array('success' => true, 'type' => "success", 'resp' => $strarr), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    }
                } else {
                    $str = textClass::stage_array($str, $arrayvars, "");
                    if ($data->job) {
                        return json_encode(array('success' => true, 'type' => "success", 'resp' => $str), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    } else {
                        echo json_encode(array('success' => true, 'type' => "success", 'resp' => $str), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    }
                }
            } else {
                if ($data->job) {
                    return json_encode(array('success' => false, 'type' => "error", 'resp' => "No such data"), JSON_UNESCAPED_UNICODE);
                } else {
                    echo json_encode(array('success' => false, 'type' => "error", 'resp' => "No such data"), JSON_UNESCAPED_UNICODE);
                }
            }
        } else {
            header("Access-Control-Allow-Origin: *");
            header("Content-Type: text/plain; charset=UTF-8");
            $data = json_decode(file_get_contents("php://input"));
            Class_mqapi::createAuthALL($d2, $d3, htmlspecialchars($data->type));
        }
        pdodb::disconnect();
    }
    public static function createDlqh($d1, $d2 = "no", $inpdata = null)
    {
        global $env;
        $pdo = pdodb::connect();
        $now = date('Y-m-d H:i:s');
        if (!$inpdata) {
            $data = json_decode(file_get_contents("php://input"));
        } else {
            $data = json_decode($inpdata);
        }
        if ($d2 == "no") {
            header("Access-Control-Allow-Origin: *");
            header("Content-Type: text/html; charset=UTF-8");
        }
        $sql = "SELECT qmgr,proj,objname,objtype," . (DBTYPE == 'oracle' ? "to_char(objdata) as objdata" : "objdata") . " FROM mqenv_mq_dlqh where id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($data->mq->qmid));
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $arrayvars = json_decode("[{}]", true);
            $sql = "select varname,varvalue,isarray from mqenv_vars where proj=?";
            $q = $pdo->prepare($sql);
            $q->execute(array($row["proj"]));
            if ($zobj = $q->fetchAll()) {
                foreach ($zobj as $val) {
                    if ($val['isarray'] == 1) {
                        $arrayvars["{" . $val['varname'] . "}"] = array("isarray" => $val['isarray'], "val" => json_decode($val['varvalue'], true));
                    } else {
                        $arrayvars["{" . $val['varname'] . "}"] = array("isarray" => $val['isarray'], "val" => $val['varvalue']);
                    }
                }
            }
            if (!empty($env)) {$menudataenv = json_decode($env, true);} else { $menudataenv = array();}
            $str = "";
            $str .= "\n*For automated triggering of DLQH, please define MQ process.\n*For manual - /usr/bin/runmqdlq SYSTEM.DEAD.LETTER.QUEUE " . $row['qmgr'] . " < /var/mqm/Rules.dlq\n\n";
            $str .= "*MQ rules\n";
            $str .= "*DLQH rules for qmanager:" . $row['qmgr'] . "\n";
            $str .= "*Released by:" . $_SESSION["user"] . "\n";
            $str .= "*Released on:" . $now . "\n\n";
            $str .= $row['objname'] . "\n";
            //echo $str;
            if (is_array($menudataenv)) {
                foreach ($menudataenv as $keyenv => $valenv) {
                    $strarr[$valenv['nameshort']] = textClass::stage_array($str, $arrayvars, $valenv['nameshort']);
                }
                if ($data->job) {
                    return json_encode(array('success' => true, 'type' => "success", 'resp' => $strarr), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                } else {
                    echo json_encode(array('success' => true, 'type' => "success", 'resp' => $strarr), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                }
            } else {
                $str = textClass::stage_array($str, $arrayvars, "");
                if ($data->job) {
                    return json_encode(array('success' => true, 'type' => "success", 'resp' => $str), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                } else {
                    echo json_encode(array('success' => true, 'type' => "success", 'resp' => $str), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                }
            }
        } else {
            if ($data->job) {
                return json_encode(array('success' => false, 'type' => "error", 'resp' => "No such data"), JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(array('success' => false, 'type' => "error", 'resp' => "No such data"), JSON_UNESCAPED_UNICODE);
            }
        }

        pdodb::disconnect();
    }
    public static function createAuthALL($d2, $d3, $d4)
    {
        $pdo = pdodb::connect();
        $sql = "SELECT qmgr,objname,objtype," . (DBTYPE == 'oracle' ? "to_char(objdata) as objdata" : "objdata") . " FROM mqenv_mq_authrec";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        if ($zobj = $stmt->fetchAll()) {
            $str = "";
            foreach ($zobj as $row) {
                $objects = json_decode($row['objdata'], true);
                $str .= "#authority records for qmanager:" . $row['qmgr'] . "\n";
                $str .= "setmqaut -m " . $row['qmgr'] . " -n \"" . $row['objname'] . "\" -t " . $row['objtype'] . " -g \"" . $objects['group'] . "\"";
                foreach ($objects['authrec'] as $val) {
                    $str .= " " . $val;
                }
                $str .= "\n";
            }
        }
        if ($d3 != "no") {
            if (file_exists($d3)) {unlink($d3);}
            file_put_contents($d3, $str);
        } else {
            echo $str;
        }
        pdodb::disconnect();
    }
    public static function createFte($d1, $d2, $d3 = "no", $inpdata = null)
    {
        global $env;
        $pdo = pdodb::connect();
        if ($d1 == "one") {
            if (!$inpdata) {
                $data = json_decode(file_get_contents("php://input"));
            } else {
                $data = json_decode($inpdata);
            }
            if ($data->job) {
                $sql = "SELECT * FROM mqenv_mqfte where id=?";
            } else {
                $sql = "SELECT * FROM mqenv_mqfte where fteid=?";
            }
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($data->mqfte->fteid));
            if ($zobj = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $arrayvars = json_decode("[{}]", true);
                $sql = "select varname,varvalue,isarray from mqenv_vars" . (!empty($d2) ? " where proj='" . $d2 . "'" : "");
                $q = $pdo->prepare($sql);
                $q->execute();
                if ($zobjin = $q->fetchAll()) {
                    foreach ($zobjin as $val) {
                        if ($val['isarray'] == 1) {
                            $arrayvars["{" . $val['varname'] . "}"] = array("isarray" => $val['isarray'], "val" => json_decode($val['varvalue'], true));
                        } else {
                            $arrayvars["{" . $val['varname'] . "}"] = array("isarray" => $val['isarray'], "val" => $val['varvalue']);
                        }
                    }
                }
                if (!empty($env)) {$menudataenv = json_decode($env, true);} else { $menudataenv = array();}
                if ($d3 == "no") {
                    header("Access-Control-Allow-Origin: *");
                    header("Content-Type: text/html; charset=UTF-8");
                }
                //generateFteXML($zobj['mqftename'],$zobj['batchsize'],$zobj['sourceagt'],$zobj['sourcequeue'],$zobj['sourcedir'],$zobj['regex'],$zobj['sourcefile'],$zobj['sourceagtqmgr'],$zobj['destagtqmgr'],$zobj['destagt'],$zobj['destqueue'],$zobj['postsourcecmd'],$zobj['postsourcecmdarg'],$zobj['postdestcmd'],$zobj['postdestcmdarg'],$zobj['sourcedisp'],$zobj['sourceccsid'],$zobj['destccsid'],$zobj['destdir'],$zobj['textorbinary']);

                $str = Class_mqapi::generateFteXML($zobj['mqftetype'], $zobj['mqftename'], $zobj['batchsize'], $zobj['sourceagt'], $zobj['sourcequeue'], $zobj['sourcedir'], $zobj['regex'], $zobj['sourcefile'], $zobj['sourceagtqmgr'], $zobj['destagtqmgr'], $zobj['destagt'], $zobj['destqueue'], $zobj['postsourcecmd'], $zobj['postsourcecmdarg'], $zobj['postdestcmd'], $zobj['postdestcmdarg'], $zobj['sourcedisp'], $zobj['sourceccsid'], $zobj['destccsid'], $zobj['destdir'], $zobj['destfile'], $zobj['textorbinary']);
                if (is_array($menudataenv)) {foreach ($menudataenv as $keyenv => $valenv) {
                    $strarr[$valenv['nameshort']] = textClass::stage_array($str, $arrayvars, $valenv['nameshort']);
                }
                    if ($data->job) {
                        return json_encode(array('success' => true, 'type' => "success", 'resp' => $strarr), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    } else {
                        echo json_encode(array('success' => true, 'type' => "success", 'resp' => $strarr), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    }
                } else {
                    $str = textClass::stage_array($str, $arrayvars, "");
                    if ($data->job) {
                        return json_encode(array('success' => true, 'type' => "success", 'resp' => $str), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    } else {
                        echo json_encode(array('success' => true, 'type' => "success", 'resp' => $str), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    }
                }
            } else {
                if ($data->job) {
                    return json_encode(array('success' => false, 'type' => "error", 'resp' => "No such data"), JSON_UNESCAPED_UNICODE);
                } else {
                    echo json_encode(array('success' => false, 'type' => "error", 'resp' => "No such data"), JSON_UNESCAPED_UNICODE);
                }
            }
        } else {
            header("Access-Control-Allow-Origin: *");
            header("Content-Type: text/plain; charset=UTF-8");
            $data = json_decode(file_get_contents("php://input"));
            //Class_mqapi::createFteALL($d2,$d3);
        }
        pdodb::disconnect();
    }
    public static function generateFteXML($ftetype, $name, $batchsize, $sourceagt, $sourcequeue, $sourcedir, $regex, $sourcefile, $sourceagtqmgr, $destagtqmgr, $destagt, $destqueue, $postsourcecmd, $postsourcecmdarg, $postdestcmd, $postdestcmdarg, $sourcedisp, $sourceccsid, $destccsid, $destdir, $destfile, $textorbinary, $isfile = "no")
    {
        $str = "";
        $str .= '<?xml version="1.0" encoding="UTF-8"?>
      <monitor:monitor xmlns:monitor="http://www.ibm.com/xmlns/wmqfte/7.0.1/MonitorDefinition" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" version="5.00" xsi:schemaLocation="http://www.ibm.com/xmlns/wmqfte/7.0.1/MonitorDefinition ./Monitor.xsd">';
        $str .= '<name>' . $name . '</name>';
        $str .= '<pollInterval units="minutes">10</pollInterval>';
        $str .= '<batch maxSize="' . $batchsize . '"/>';
        $str .= '<agent>' . $sourceagt . '</agent>';
        if (!empty($sourcequeue)) {
            $str .= '<resources><queue>' . $sourcequeue . '</queue></resources>';
            $str .= '<triggerMatch><conditions><allOf><condition><queueNotEmpty/></condition></allOf></conditions></triggerMatch>';
        } else {
            $str .= '<resources><directory recursionLevel="0">' . $sourcedir . '</directory></resources>';
            $str .= '<triggerMatch><conditions><allOf><condition><fileMatch><pattern' . ($regex == 1 ? ' type="regex"' : '') . '>' . $sourcefile . '</pattern></fileMatch></condition></allOf></conditions></triggerMatch>';
        }
        $str .= '<tasks><task><name/><transfer><request version="6.00" xsi:noNamespaceSchemaLocation="FileTransfer.xsd"><managedTransfer>';
        $str .= '<originator><hostName>' . (!empty($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : "example.com") . '</hostName><userID>mqm</userID></originator>';
        $str .= '<sourceAgent QMgr="' . $sourceagtqmgr . '" agent="' . $sourceagt . '"/>';
        $str .= '<destinationAgent QMgr="' . $destagtqmgr . '" agent="' . $destagt . '"/>';
        $str .= '<transferSet priority="1">';
        $str .= '<metaDataSet><metaData key="com.ibm.wmqfte.JobName">' . $name . '</metaData><metaData key="dataType">' . $name . '</metaData>';
        if (!empty($destqueue)) {$str .= '<metaData key="destinationQueue">' . $destqueue . '</metaData>';}
        $str .= '</metaDataSet>';
        if (!empty($postsourcecmd)) {
            $str .= '<postSourceCall><command retryCount="1" retryWait="30" successRC="0" type="executable" name="' . $postsourcecmd . '">';
            if (!empty($postsourcecmdarg)) {
                $kt = explode(" ", $postsourcecmdarg);
                while (list($key, $val) = each($kt)) {
                    if ($val != " " and strlen($val) > 0) {
                        $str .= '<argument>' . $val . '</argument>';
                    }
                }
            }
            $str .= '</command></postSourceCall>';
        }
        if (!empty($postdestcmd)) {
            $str .= '<postDestinationCall><command retryWait="30" successRC="0" type="executable" name="' . $postdestcmd . '">';
            if (!empty($postdestcmdarg)) {
                $kt = explode(" ", $postdestcmdarg);
                while (list($key, $val) = each($kt)) {
                    if ($val != " " and strlen($val) > 0) {
                        $str .= '<argument>' . $val . '</argument>';
                    }
                }
            }
            $str .= '</command></postDestinationCall>';
        }
        $str .= '<item checksumMethod="MD5" mode="' . $textorbinary . '">';
        if (!empty($sourcequeue)) {
            $str .= '<source disposition="leave" recursive="false" type="queue"><queue useGroups="false">${QueueName}@' . $sourceagtqmgr . '</queue></source>';
        } else {
            $str .= '<source disposition="' . $sourcedisp . '" recursive="false" type="file"><file ' . (!empty($sourceccsid) ? 'encoding="' . $sourceccsid . '"' : "") . '>${FilePath}</file></source>';
        }
        if ($ftetype == "f2f" || $ftetype == "q2f") {
            if (!empty($destfile)) {
                $str .= '<destination exist="overwrite" type="file"><file ' . (!empty($destccsid) ? 'encoding="' . $destccsid . '"' : "") . '>' . $destfile . '</file></destination>';
            } else {
                $str .= '<destination exist="overwrite" type="directory"><file ' . (!empty($destccsid) ? 'encoding="' . $destccsid . '"' : "") . '>' . $destdir . '</file></destination>';
            }
        }
        if ($ftetype == "f2q") {
            $str .= '<destination exist="overwrite" type="queue"><queue encoding="UTF-8" persistent="true">' . $destqueue . '</queue></destination>';
        }
        $str .= '</item>';
        $str .= '</transferSet></managedTransfer></request></transfer></task></tasks><originator><hostName>' . (!empty($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : "example.com") . '</hostName><userID>mqm</userID></originator><job><name/></job></monitor:monitor>';
        $dom = new DOMDocument;
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($str);
        $dom->formatOutput = true;
        if ($isfile == "no") {
            $txt = $dom->saveXml();
            /*    $txt.='<!--command for creating the transfer
            fteCreateMonitor.cmd/sh -ix '.$name.'.xml
            -->'; */
            $txt .= '';
            return $txt;
        } else {
            $dom->save($isfile);
            /*  $txt = '<!--command for creating the transfer
            fteCreateMonitor.cmd/sh -ix '.$name.'.xml
            -->'; */
            $txt = '';
            $myfile = file_put_contents($isfile, $txt . PHP_EOL, FILE_APPEND | LOCK_EX);
        }
    }
    public static function Applications($d1)
    {
        if ($d1 == "qmlist") {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));

            if (!empty(htmlspecialchars($data->pkg))) {
                $sql = "select " . (DBTYPE == 'oracle' ? "to_char(pkgobjects) as pkgobjects" : "pkgobjects") . " from env_packages where packuid=? and proj=?";
                $q = $pdo->prepare($sql);
                $q->execute(array(htmlspecialchars($data->pkg), htmlspecialchars($data->appl)));
                if ($zobjin = $q->fetch(PDO::FETCH_ASSOC)) {
                    $tmp = array();
                    foreach (json_decode($zobjin["pkgobjects"], true)[htmlspecialchars($data->appl)] as $keyin => $valin) {
                        $tmp[] = $keyin;

                    }
                }
            }
            echo json_encode($tmp, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            pdodb::disconnect();
            exit;
        } elseif ($d1 == "delete") {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "delete from config_app_codes where id=?";
            $q = $pdo->prepare($sql);
            if ($q->execute(array(htmlspecialchars($data->id)))) {
                gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => htmlspecialchars($data->appcode)), "Deleted application:" . htmlspecialchars($data->appcode));
                echo "Application was deleted";
            } else {
                echo "Error deleting application";
            }
            pdodb::disconnect();
        } else {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "select id,tags,appcode,appcreated,appname,owner from config_app_codes";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            if ($zobj = $stmt->fetchAll()) {
                foreach ($zobj as $val) {
                    $d['appcode'] = $val['appcode'];
                    $d['owner'] = $val['owner'];
                    $d['id'] = $val['id'];
                    $d['appcreated'] = $val['appcreated'];
                    $d['tags'] = !empty($val['tags']) ? explode(',', $val['tags']) : "";
                    $d['appname'] = strip_tags(textClass::word_limiter($val['appname'], 20, 80));
                    $newdata[] = $d;
                }
                pdodb::disconnect();
                echo json_encode($newdata, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);exit;
            } else {
                pdodb::disconnect();
                echo json_encode(array(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);exit;
            }
        }
    }
}