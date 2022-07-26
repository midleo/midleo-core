<?php
class Class_api
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
                case 'addappsrv':Class_api::addServer();
                    break;
                case 'duplicate':Class_api::duplicate($thisarray["p2"]);
                    break;
                case 'delldap':Class_api::delLdap();
                    break;
                case 'applications':Class_api::Applications($thisarray["p2"]);
                    break;
                case 'modules':Class_api::Modules($thisarray["p2"]);
                    break;
                case 'groups':Class_api::getGroups($thisarray["p2"]);
                    break;
                case 'dellappsrv':Class_api::dellAppsrv();
                    break;
                case 'updappsrv':Class_api::updAppsrv();
                    break;
                case 'updsrv':Class_api::updSrv();
                    break;
                case 'updatesystemnest':Class_api::updateSystemnest();
                    break;
                case 'users':Class_api::getUsers($thisarray["p2"]);
                    break;
                case 'readappsrv':Class_api::readAppsrv($thisarray["p2"]);
                    break;
                case 'readsrv':Class_api::readSrv($thisarray["p2"]);
                    break;
                case 'firewall':Class_api::Firewalls($thisarray["p2"]);
                    break;
                case 'dns':Class_api::DNS($thisarray["p2"]);
                    break;
                case 'readplaces':Class_api::readPlaces($thisarray["p2"]);
                    break;
                case 'readreleases':Class_api::readReleases($thisarray["p2"]);
                    break;
                case 'getallusrgr':Class_api::readAllusrGr($thisarray["p2"]);
                    break;
                case 'appgroups':Class_api::appGroups($thisarray["p2"]);
                    break;
                case 'delkni':Class_api::delKNBase();
                    break;
                default:echo json_encode(array('error' => true, 'type' => "error", 'errorlog' => "please use the API correctly."));exit;
            }
        } else {echo json_encode(array('error' => true, 'type' => "error", 'errorlog' => "please use the API correctly."));exit;}
    }
    public static function duplicate($d1)
    {
        $pdo = pdodb::connect();
        $data = json_decode(file_get_contents("php://input"));
        if ($d1 == "tibco") {
            $sql = "insert into tibco_obj (proj,srv,objname,objtype,objdata,projinfo) select proj,srv,concat(objname,'.COPY'),objtype,objdata,projinfo from tibco_obj where id=?";
        } else {
            $sql = "insert into env_appservers (proj,serv_type,tags,serverdns,serverip,serverid,port,qmname,qmchannel,agentname,brokername,execgname,info,sslenabled,sslkey,sslpass,sslcipher,srvuser,srvpass) select REPLACE(proj,proj,'" . $data->newappid . "'),serv_type,tags,serverdns,serverip,serverid,port,qmname,qmchannel,agentname,brokername,execgname,info,sslenabled,sslkey,sslpass,sslcipher,srvuser,srvpass from env_appservers where id=?";
        } 
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array($data->qmid))) {
            echo "Object was copied.";
        } else {
            echo "Unable to copy object.";
        }
        pdodb::disconnect();
    }
    public static function readAppsrv($d1)
    {
        global $typesrv;
        if ($d1 == "one") {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "select * from env_appservers where id=? and proj=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(htmlspecialchars($data->server), htmlspecialchars($data->appcode)));
            $data = array();
            if ($zobj = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo json_encode($zobj, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            }
            pdodb::disconnect();
        } else {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "select serverdns,appsrvname,port,id,serv_type,qmname from env_appservers where proj=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(htmlspecialchars($data->appcode)));
            $data = array();
            if ($zobj = $stmt->fetchAll()) {
                foreach ($zobj as $val) {
                    $d = array();
                    $d['server'] = $val['serverdns'];
                    $d['appsrvname'] = $val['appsrvname'];
                    $d['port'] = $val['port'];
                    $d['id'] = $val['id'];
                    $d['serv_type'] = $typesrv[$val['serv_type']];
                    $d['qmname'] = $val['qmname'];
                    $data[] = $d;
                }
            }
            pdodb::disconnect();
            echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }
    }
    public static function Firewalls($d1)
    {
        if ($d1 == "one") {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "select * from env_firewall where id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(htmlspecialchars($data->id)));
            $data = array();
            if ($zobj = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo json_encode($zobj, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            }
            pdodb::disconnect();
        } elseif ($d1 == "update") {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $keys = "";
            foreach ($data->fw as $key => $val) {
                $keys .= htmlspecialchars($key) . "='" . str_replace("'", "\"", htmlspecialchars($val)) . "',";
            }
            $sql = "update env_firewall set " . rtrim($keys, ',') . " where id=?";
            $q = $pdo->prepare($sql);
            if ($q->execute(array(htmlspecialchars($data->fw->id)))) {
                gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => htmlspecialchars($data->projid)), "Updated firewall configuration with DNS:<a href='/env/firewall/" . htmlspecialchars($data->projid) . "'>" . htmlspecialchars($data->fw->srcdns) . "</a>");
                if (!empty(htmlspecialchars($data->fw->tags))) {
                    gTable::dbsearch(htmlspecialchars($data->fw->srcdns), $_SERVER["HTTP_REFERER"], htmlspecialchars($data->fw->tags));
                }
                echo "Firewall configuration was updated";
            } else {
                echo "Error updating Firewall configuration";
            }
            pdodb::disconnect();
        } elseif ($d1 == "add") {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $keys = "";
            $vals = "";
            foreach ($data->fw as $key => $val) {
                $keys .= htmlspecialchars($key) . ",";
                $vals .= "'" . str_replace("'", "\"", htmlspecialchars($val)) . "',";
            }
            $sql = "insert into env_firewall (proj," . rtrim($keys, ',') . ") values(?," . rtrim($vals, ',') . ")";
            $q = $pdo->prepare($sql);
            if ($q->execute(array(htmlspecialchars($data->projid)))) {
                echo "Firewall rules were created";
                gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => htmlspecialchars($data->projid)), "Defined new firewall configuration with DNS:<a href='/env/firewall/" . htmlspecialchars($data->projid) . "'>" . htmlspecialchars($data->fw->srcdns) . "</a>");
                if (!empty(htmlspecialchars($data->fw->tags))) {
                    gTable::dbsearch(htmlspecialchars($data->fw->srcdns), $_SERVER["HTTP_REFERER"], htmlspecialchars($data->fw->tags));
                }
            } else {
                echo "Error creating Server configuration";
            }
            pdodb::disconnect();
        } elseif ($d1 == "delete") {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "delete from env_firewall where id=?";
            $q = $pdo->prepare($sql);
            if ($q->execute(array(htmlspecialchars($data->id)))) {
                gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => htmlspecialchars($data->projid)), "Deleted firewall configuration for DNS:<a href='/env/firewall/" . htmlspecialchars($data->projid) . "'>" . htmlspecialchars($data->dns) . "</a>");
                echo "Firewall configuration was deleted";
            } else {
                echo "Error deleting Firewall configuration";
            }
            pdodb::disconnect();
        } else {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "select * from env_firewall" . (!empty($data->appcode) ? " where proj='" . htmlspecialchars($data->appcode) . "'" : "");
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $data = array();
            $data = $stmt->fetchAll(PDO::FETCH_CLASS);
            pdodb::disconnect();
            echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }
    }
    public static function dellAppsrv()
    {
        $pdo = pdodb::connect();
        $data = json_decode(file_get_contents("php://input"));
        $sql = "delete from env_appservers where id=?";
        $q = $pdo->prepare($sql);
        if ($q->execute(array(htmlspecialchars($data->server)))) {
            gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => htmlspecialchars($data->appcode)), "Deleted server configuration for server:<a href='/env/appservers/" . htmlspecialchars($data->appcode) . "'>" . htmlspecialchars($data->server) . "</a>");
            echo "Server configuration was deleted";
        } else {
            echo "Error deleting Server configuration";
        }
        pdodb::disconnect();
    }
    public static function addServer()
    {
        $pdo = pdodb::connect();
        $data = json_decode(file_get_contents("php://input"));
        $keys = "";
        $vals = "";
        foreach ($data->serv as $key => $val) {
            if ($key == "srvpass") {
                $keys .= htmlspecialchars($key) . ",";
                $vals .= "'" . documentClass::encryptIt(str_replace("'", "\"", htmlspecialchars($val))) . "',";
            } else {
                $keys .= htmlspecialchars($key) . ",";
                $vals .= "'" . str_replace("'", "\"", htmlspecialchars($val)) . "',";
            }
        }
        $sql = "insert into env_appservers (proj," . rtrim($keys, ',') . ") values(?," . rtrim($vals, ',') . ")";
        $q = $pdo->prepare($sql);
        if ($q->execute(array(htmlspecialchars($data->appcode)))) {
            echo "Server configuration was created";
            gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => htmlspecialchars($data->appcode)), "Created new server configuration with name:<a href='/env/appservers/" . $data->appcode . "'>" . htmlspecialchars($data->serv->serverdns) . "</a>");
            if (!empty(htmlspecialchars($data->serv->tags))) {
                gTable::dbsearch(htmlspecialchars($data->serv->serverdns), $_SERVER["HTTP_REFERER"], htmlspecialchars($data->serv->tags));
            }
        } else {
            echo "Error creating Server configuration";
        }
        pdodb::disconnect();
    }
    public static function updAppsrv()
    {
        $pdo = pdodb::connect();
        $data = json_decode(file_get_contents("php://input"));
        $keys = "";
        $servid = $data->serv->id;
        unset($data->serv->id);
        foreach ($data->serv as $key => $val) {
            if ($key == "srvpass" && !empty($key)) {
                $keys .= htmlspecialchars($key) . "='" . documentClass::encryptIt(str_replace("'", "\"", htmlspecialchars($val))) . "',";
            } else {
                $keys .= htmlspecialchars($key) . "='" . str_replace("'", "\"", htmlspecialchars($val)) . "',";
            }
        }
        $sql = "update env_appservers set " . rtrim($keys, ',') . " where id=?";
        $q = $pdo->prepare($sql);
        if ($q->execute(array(htmlspecialchars($servid)))) {
            gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => htmlspecialchars($data->appcode)), "Updated Application server configuration for server:<a href='/env/appservers/" . htmlspecialchars($data->appcode) . "'>" . htmlspecialchars($data->serv->server) . "</a>");
            if (!empty(htmlspecialchars($data->serv->tags))) {
                gTable::dbsearch(htmlspecialchars($data->serv->server), $_SERVER["HTTP_REFERER"], htmlspecialchars($data->serv->tags));
            }
            echo "Server configuration was updated";
        } else {
            echo "Error updating Server configuration";
        }
        pdodb::disconnect();
    }
    public static function getUsers($d1)
    {
        global $installedapp;
        global $website;
        global $maindir;
        global $page;
        global $modulelist;
        global $accrights;
        if ($d1 == "recent") {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "select count(id) from users_recent where uuid=?";
            $q = $pdo->prepare($sql);
            $q->execute(array($_SESSION["user_id"]));
            if ($q->fetchColumn() > 0) {
                $sql = "select " . (DBTYPE == 'oracle' ? "to_char(recentdata) as recentdata" : "recentdata") . " from users_recent where uuid=?";
                $q = $pdo->prepare($sql);
                $q->execute(array($_SESSION["user_id"]));
                if ($zobj = $q->fetch(PDO::FETCH_ASSOC)) {
                    $tmparr = json_decode($zobj["recentdata"], true);
                    if (count($tmparr) >= 5) {
                        array_pop($tmparr);
                    }
                    $tmparr[$data->appid] = array(
                        "name" => $data->appname,
                        "link" => $data->link,
                    );
                    //array_unshift($tmparr, $tmparrn);
                    $sql = "update users_recent set recentdata=? where uuid=?";
                    $q = $pdo->prepare($sql);
                    $q->execute(array(json_encode($tmparr, true), $_SESSION["user_id"]));
                }
            } else {
                $tmparr = array();
                $tmparr[$data->appid] = array(
                    "name" => $data->appname,
                    "link" => $data->link,
                );
                //array_unshift($tmparr, $tmparrn);
                $sql = "insert into users_recent (uuid,recentdata) values (?,?)";
                $q = $pdo->prepare($sql);
                $q->execute(array($_SESSION["user_id"], json_encode($tmparr, true)));
            }
            pdodb::disconnect();
            $_SESSION["userdata"]["lastappid"] = $data->appid;
        } else if ($d1 == "add") {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "select count(id) from users where mainuser=?";
            $q = $pdo->prepare($sql);
            $q->execute(array(strtolower(htmlspecialchars($data->users->name))));
            if ($q->fetchColumn() > 0) {
                echo "User already exist!";
            } else {
                $uid = md5(uniqid(microtime()) . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
                $pwd = inputClass::PwdHash(htmlspecialchars($data->users->pass));
                $sql = "insert into users (uuid,mainuser,email,pwd,fullname,user_level,utitle) values (?,?,?,?,?,?,?)";
                $q = $pdo->prepare($sql);
                $q->execute(array($uid, strtolower(htmlspecialchars($data->users->name)), htmlspecialchars($data->users->email), $pwd, htmlspecialchars($data->users->fullname), !empty($data->users->rights) ? htmlspecialchars($data->users->rights) : "1", htmlspecialchars($data->users->title)));
                send_mailfinal(
                    $website["system_mail"],
                    htmlspecialchars($data->users->email),
                    "[MidlEO] user registration",
                    "Hello,<br>Welcome to Midleo!",
                    "<br><br>You can login " . "<a href=\"https://" . $_SERVER['HTTP_HOST'] . "/mlogin\" target=\"_blank\">here</a>",
                    $body = array(
                        "Username" => htmlspecialchars($data->users->name),
                        "Email" => htmlspecialchars($data->users->email),
                        "Password" => htmlspecialchars($data->users->pass),
                        "Title" => htmlspecialchars($data->users->utitle),
                        "Fullname" => htmlspecialchars($data->users->fullname),
                        "Group" => htmlspecialchars($data->users->effgroup),
                    ),
                    "full"
                );
                echo "User created successfully";
            }
            pdodb::disconnect();
        } else if ($d1 == "addldap") {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "select count(id) from users where mainuser=?";
            $q = $pdo->prepare($sql);
            $q->execute(array(strtolower(htmlspecialchars($data->users->name))));
            if ($q->fetchColumn() > 0) {
                echo "User already exist!";
            } else {
                $sql = "select * from ldap_config where ldapserver=?";
                $q = $pdo->prepare($sql);
                $q->execute(array(strtolower(htmlspecialchars($data->users->authtype))));
                $zobj = $q->fetch(PDO::FETCH_ASSOC);
                $ldap = ldap::ldapsearch($zobj['ldapserver'], "", $zobj['ldaptree'], $zobj['ldaptree'], strtolower(htmlspecialchars($data->users->name)), $zobj['ldapport']);
                $ldap = json_decode($ldap, true);
                if (!empty($ldap['error'])) {
                    echo $ldap['error'];
                } else {
                    if (!empty($ldap['user']['email'])) {
                        $uid = md5(uniqid(microtime()) . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . htmlspecialchars($data->users->name));
                        $new_pwd = inputClass::GenPwd();
                        $pwd = inputClass::PwdHash($new_pwd);
                        $sql = "insert into users (uuid,mainuser,email,pwd,fullname,user_level,ldap,ldapserver,utitle) values (?,?,?,?,?,'1',?,?,?)";
                        $q = $pdo->prepare($sql);
                        $q->execute(array($uid, strtolower(htmlspecialchars($data->users->name)), $ldap['user']['email'], $pwd, (!empty($ldap['user']['displayname']) ? $ldap['user']['displayname'] : "Display Name"), htmlspecialchars($data->users->rights), $zobj['ldapserver'], htmlspecialchars($data->users->title)));
                        echo "User added successfully";
                    } else {
                        echo "Error taking data for the user from ldap";
                    }
                }
            }
            pdodb::disconnect();
        } else if ($d1 == "update") {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            if (!empty(htmlspecialchars($data->users->pass))) {
                $sql = "update users set user_level=?, fullname=?, email=?, utitle=?, pwd=?, wid=? where mainuser=?";
                $q = $pdo->prepare($sql);
                $texec = $q->execute(array(htmlspecialchars($data->users->rights), htmlspecialchars($data->users->fullname), htmlspecialchars($data->users->email), htmlspecialchars($data->users->title), inputClass::PwdHash(htmlspecialchars($data->users->pass)), htmlspecialchars($data->users->wid), htmlspecialchars($data->users->name)));
            } else {
                $sql = "update users set user_level=?, fullname=?, email=?, utitle=?, wid=? where mainuser=?";
                $q = $pdo->prepare($sql);
                $texec = $q->execute(array(htmlspecialchars($data->users->rights), htmlspecialchars($data->users->fullname), htmlspecialchars($data->users->email), htmlspecialchars($data->users->title), htmlspecialchars($data->users->wid), htmlspecialchars($data->users->name)));
            }
            if ($texec) {
                echo "User " . htmlspecialchars($data->users->name) . " updated";
            } else {
                echo "User cannot be updated";
            }
            pdodb::disconnect();
            exit;
        } else if ($d1 == "readone") {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "select * from users where mainuser=?";
            $q = $pdo->prepare($sql);
            $q->execute(array(htmlspecialchars($data->user)));
            if ($zobj = $q->fetch(PDO::FETCH_ASSOC)) {
                $d['rights'] = !empty($zobj['user_level']) ? strval($zobj['user_level']) : "";
                $d['fullname'] = $zobj['fullname'];
                $d['name'] = $zobj['mainuser'];
                $d['title'] = $zobj['utitle'];
                $d['email'] = $zobj['email'];
                $d['wid'] = $zobj['wid'];
                $d['shortname'] = substr(textClass::initials($zobj['fullname']), 0, 2);
            }
            echo json_encode($d, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            pdodb::disconnect();
        } else if ($d1 == "del") {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "delete from users where mainuser=?";
            $q = $pdo->prepare($sql);
            if ($q->execute(array(htmlspecialchars($data->user)))) {
                echo "User " . htmlspecialchars($data->user) . " deleted";
            } else {
                echo "There was a problem while deleting this user";
            }
            pdodb::disconnect();
        } else {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "select * from users where not mainuser=?";
            $q = $pdo->prepare($sql);
            $q->execute(array($_SESSION["user"]));
            $zobj = $q->fetchAll();
            foreach ($zobj as $val) {
                $d['rights'] = $val['user_level'];
                $d['fullname'] = $val['fullname'];
                $d['acc'] = $accrights[$val['user_level']];
                $d['name'] = $val['mainuser'];
                $d['shortname'] = substr(textClass::initials($val['fullname']), 0, 2);
                $newdata[] = $d;
            }
            echo json_encode($newdata, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            pdodb::disconnect();
        }
    }
    public static function delLdap()
    {
        if (isset($_POST['ldapsrv'])) {
            $pdo = pdodb::connect();
            $sql = "delete from ldap_config where id=? and ldapserver=?";
            $q = $pdo->prepare($sql);
            if ($q->execute(array(htmlspecialchars($_POST['srvid']), htmlspecialchars($_POST['ldapsrv'])))) {
                gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => "system"), "Deleted ldap server configuration:" . htmlspecialchars($_POST['ldapsrv']));
                echo json_encode(array('success' => true));
            } else {
                echo json_encode(array('success' => false));
            }
            pdodb::disconnect();
            exit;
        } else {
            echo json_encode(array('error' => true, 'type' => "error", 'errorlog' => "Please specify blog ID"));exit;
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
    public static function getGroups($d1)
    {
        if ($d1 == "addusr") {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "select users from user_groups where group_latname=?";
            $q = $pdo->prepare($sql);
            $q->execute(array(htmlspecialchars($data->grid)));
            if ($zobj = $q->fetch(PDO::FETCH_ASSOC)) {
                if (!empty($zobj['users'])) {$tmpusers = json_decode($zobj['users'], true);} else { $tmpusers = json_decode("{}", true);}
                if (!is_array($tmpusers)) {$tmpusers = array();}
                $tmpusers[htmlspecialchars($data->uid)] = htmlspecialchars($data->uname);
                $sql = "update user_groups set users=? where group_latname=?";
                $q = $pdo->prepare($sql);
                $q->execute(array(json_encode($tmpusers, true), htmlspecialchars($data->grid)));
            }
            if ($data->utype == "user") {
                $sql = "select ugroups from users where mainuser=?";
                $q = $pdo->prepare($sql);
                $q->execute(array(htmlspecialchars($data->uid)));
                if ($zobj = $q->fetch(PDO::FETCH_ASSOC)) {
                    if (!empty($zobj['ugroups'])) {$tmpgroups = json_decode($zobj['ugroups'], true);} else { $tmpgroups = json_decode("{}", true);}
                    if (!is_array($tmpgroups)) {$tmpgroups = array();}
                    $tmpgroups[htmlspecialchars($data->grid)] = htmlspecialchars($data->group);
                    $sql = "update users set ugroups=? where mainuser=?";
                    $q = $pdo->prepare($sql);
                    $q->execute(array(json_encode($tmpgroups, true), htmlspecialchars($data->uid)));
                }
            }
            gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => "system"), "Added user <b>" . htmlspecialchars($data->uname) . "</b> to group <b>" . htmlspecialchars($data->group) . "</b>");
            echo "User added successfully";
            pdodb::disconnect();
            exit;
        } elseif ($d1 == "update") {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "update user_groups set group_email=?, acclist=? where group_latname=?";
            $q = $pdo->prepare($sql);
            $q->execute(array(htmlspecialchars($data->group->email), $data->modules, htmlspecialchars($data->group->id)));
            gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => "system"), "Updated group <b>" . htmlspecialchars($data->group->name) . "</b>");
            echo "Group updated successfully";
            pdodb::disconnect();
            exit;
        } elseif ($d1 == "add") {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "select count(id) from user_groups where group_name=?";
            $q = $pdo->prepare($sql);
            $q->execute(array(htmlspecialchars($data->group->name)));
            if ($q->fetchColumn() > 0) {
                echo "Group already exist!";
            } else {
                $latname = textClass::getRandomStr();
                $sql = "insert into user_groups(group_latname,group_name,group_email,acclist) values(?,?,?,?)";
                $q = $pdo->prepare($sql);
                $q->execute(array($latname, htmlspecialchars($data->group->name), htmlspecialchars($data->group->email), $data->modules));
                echo "Group created";
                gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => "system"), "Created new group <b>" . htmlspecialchars($data->group->name) . "</b>");
            }
            pdodb::disconnect();
            exit;
        } elseif ($d1 == "readone") {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "select * from user_groups where group_latname=?";
            $q = $pdo->prepare($sql);
            $q->execute(array(htmlspecialchars($data->group)));
            if ($zobj = $q->fetch(PDO::FETCH_ASSOC)) {
                if ($zobj['acclist']) {
                    $modules = json_decode($zobj['acclist'], true);
                    foreach ($modules as $key => $val) {$modules[$val] = $val;}
                    $d['modules'] = $modules;
                    $d['selectedid'] = !empty($d['modules']) ? json_decode($zobj['acclist'], true) : array();
                } else {
                    $d['selectedid'] = array();
                }
                $d['name'] = $zobj['group_name'];
                $d['latname'] = $zobj['group_latname'];
                $d['email'] = $zobj['group_email'];
                $d['users'] = json_decode($zobj['users'], true);
                $d["id"] = $zobj["group_latname"];
            }
            echo json_encode($d, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            pdodb::disconnect();
            exit;
        } elseif ($d1 == "del") {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "select id,group_latname,group_name,users from user_groups where group_latname=?";
            $q = $pdo->prepare($sql);
            $q->execute(array(htmlspecialchars($data->groupid)));
            $zobj = $q->fetch(PDO::FETCH_ASSOC);
            $groupname = $zobj["group_name"];
            if (!empty($zobj["group_latname"])) {
                $users = json_decode($zobj['users'], true);
                foreach ($users as $key => $val) {
                    $sql = "select count(id) from users where mainuser=?";
                    $q = $pdo->prepare($sql);
                    $q->execute(array($key));
                    if ($q->fetchColumn() > 0) {
                        $sql = "select ugroups from users where mainuser=?";
                        $q = $pdo->prepare($sql);
                        $q->execute(array($key));
                        if ($zobj = $q->fetch(PDO::FETCH_ASSOC)) {
                            if (!empty($zobj['ugroups'])) {$tmpgroups = json_decode($zobj['ugroups'], true);} else { $tmpgroups = json_decode("{}", true);}
                            if (!is_array($tmpgroups)) {$tmpgroups = array();}
                            unset($tmpgroups[htmlspecialchars($data->groupid)]);
                            $sql = "update users set ugroups=? where mainuser=?";
                            $q = $pdo->prepare($sql);
                            $q->execute(array(json_encode($tmpgroups, true), $key));
                        }
                    }
                }
                $sql = "delete from user_groups where group_latname=?";
                $q = $pdo->prepare($sql);
                $q->execute(array(htmlspecialchars($data->groupid)));
                gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => "system"), "Deleted group <b>" . $groupname . "</b>");
                echo "Group deleted successfully";
            }
            pdodb::disconnect();
            exit;
        } elseif ($d1 == "delusr") {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "select group_name,users from user_groups where group_latname=?";
            $q = $pdo->prepare($sql);
            $q->execute(array(htmlspecialchars($data->groupid)));
            if ($zobj = $q->fetch(PDO::FETCH_ASSOC)) {
                $groupname = $zobj["group_name"];
                if (!empty($zobj['users'])) {$tmpusers = json_decode($zobj['users'], true);} else { $tmpusers = json_decode("{}", true);}
                if (!is_array($tmpusers)) {$tmpusers = array();}
                unset($tmpusers[htmlspecialchars($data->userid)]);
                $sql = "update user_groups set users=? where group_latname=?";
                $q = $pdo->prepare($sql);
                $q->execute(array(json_encode($tmpusers, true), htmlspecialchars($data->groupid)));
            }
            $sql = "select count(id) from users where mainuser=?";
            $q = $pdo->prepare($sql);
            $q->execute(array(htmlspecialchars($data->userid)));
            if ($q->fetchColumn() > 0) {
                $sql = "select ugroups from users where mainuser=?";
                $q = $pdo->prepare($sql);
                $q->execute(array(htmlspecialchars($data->userid)));
                if ($zobj = $q->fetch(PDO::FETCH_ASSOC)) {
                    if (!empty($zobj['ugroups'])) {$tmpgroups = json_decode($zobj['ugroups'], true);} else { $tmpgroups = json_decode("{}", true);}
                    if (!is_array($tmpgroups)) {$tmpgroups = array();}
                    unset($tmpgroups[htmlspecialchars($data->groupid)]);
                    $sql = "update users set ugroups=? where mainuser=?";
                    $q = $pdo->prepare($sql);
                    $q->execute(array(json_encode($tmpgroups, true), htmlspecialchars($data->userid)));
                }
            }
            gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => "system"), "Removed user <b>" . htmlspecialchars($data->userid) . "</b> from group <b>" . $groupname . "</b>");
            echo "User deleted successfully";
            pdodb::disconnect();
            exit;
        } else {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "select * from user_groups";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            if ($zobj = $stmt->fetchAll()) {
                foreach ($zobj as $val) {
                    $d['id'] = $val['group_latname'];
                    $d['group_name'] = $val['group_name'];
                    $d['users'] = json_decode($val['users'], true);
                    $d['shortname'] = substr(textClass::initials($val['group_name']), 0, 2);
                    $newdata[] = $d;
                }
            }
            echo json_encode($newdata, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            pdodb::disconnect();
            exit;
        }
    }
    public static function Modules($d1)
    {
        if ($d1 == "delete") {
            $moddir = "assets/modules/";
            $thisid = htmlspecialchars($_POST['thisid']);
            if (file_exists($moddir . $thisid . "/config.php") && !empty($thisid)) {
                //    documentClass::rRD($moddir.$thisid);
                echo json_encode(array("text" => "Module " . $thisid . " deleted"), true);
            }
            exit;
        }
    }
    public static function readAllusrGr($d1)
    {
        if (isset($_POST['search'])) {
            $pdo = pdodb::connect();
            $data = array();
            $sql = "select mainuser,fullname,email, avatar, utitle from users WHERE fullname like'%" . htmlspecialchars($_POST['search']) . "%' or mainuser like'%" . htmlspecialchars($_POST['search']) . "%' limit 10";
            $q = $pdo->prepare($sql);
            $q->execute();
            if ($zobj = $q->fetchAll()) {
                foreach ($zobj as $val) {
                    $data[] = array("name" => $val['fullname'], "nameid" => $val['mainuser'], "email" => $val['email'], "avatar" => str_replace("/assets/images/users/", "", $val['avatar']), "utitle" => !empty($val['utitle']) ? $val['utitle'] : "No title", "type" => "user");
                }
            }
            if ($d1 == "all") {
                $sql = "select group_latname,group_name,group_email from user_groups WHERE group_name like'%" . htmlspecialchars($_POST['search']) . "%' limit 10";
                $q = $pdo->prepare($sql);
                $q->execute();
                if ($zobj = $q->fetchAll()) {
                    foreach ($zobj as $val) {
                        $data[] = array("name" => $val['group_name'], "nameid" => $val['group_latname'], "utitle" => "group", "avatar" => "", "email" => $val['group_email'], "type" => "group");
                    }
                }
            }
            $data[] = array("name" => "Return to Customer", "nameid" => "custret", "email" => "info@system", "avatar" => "", "utitle" => "customer", "type" => "user");
            echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            pdodb::disconnect();
            exit;
        }
    }
    public static function DNS($d1)
    {
        if ($d1 == "one") {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "select id,proj,tags,dnsname,dnsserv,ttl,dnsclass,dnstype," . (DBTYPE == 'oracle' ? "to_char(dnsrecord) as dnsrecord" : "dnsrecord") . " from env_dns where id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(htmlspecialchars($data->id)));
            $data = array();
            if ($zobj = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo json_encode($zobj, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            }
            pdodb::disconnect();
        } elseif ($d1 == "update") {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $keys = "";
            foreach ($data->dns as $key => $val) {
                $keys .= htmlspecialchars($key) . "='" . str_replace("'", "\"", htmlspecialchars($val)) . "',";
            }
            $sql = "update env_dns set " . rtrim($keys, ',') . " where id=?";
            $q = $pdo->prepare($sql);
            if ($q->execute(array(htmlspecialchars($data->dns->id)))) {
                gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => htmlspecialchars($data->projid)), "Updated DNS configuration:<a href='/env/dns/" . htmlspecialchars($data->projid) . "'>" . htmlspecialchars($data->dns->dnsname) . "</a>");
                if (!empty(htmlspecialchars($data->dns->tags))) {
                    gTable::dbsearch(htmlspecialchars($data->dns->dnsname), $_SERVER["HTTP_REFERER"], htmlspecialchars($data->dns->tags));
                }
                echo "DNS record was updated";
            } else {
                echo "Error updating DNS record";
            }
            pdodb::disconnect();
        } elseif ($d1 == "add") {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $keys = "";
            $vals = "";
            foreach ($data->dns as $key => $val) {
                $keys .= htmlspecialchars($key) . ",";
                $vals .= "'" . str_replace("'", "\"", htmlspecialchars($val)) . "',";
            }
            $sql = "insert into env_dns (proj," . rtrim($keys, ',') . ") values(?," . rtrim($vals, ',') . ")";
            $q = $pdo->prepare($sql);
            if ($q->execute(array(htmlspecialchars($data->projid)))) {
                echo "DNS record was created";
                gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => htmlspecialchars($data->projid)), "Defined DNS configuration:<a href='/env/dns/" . htmlspecialchars($data->projid) . "'>" . htmlspecialchars($data->dns->dnsname) . "</a>");
                if (!empty(htmlspecialchars($data->dns->tags))) {
                    gTable::dbsearch(htmlspecialchars($data->dns->dnsname), $_SERVER["HTTP_REFERER"], htmlspecialchars($data->dns->tags));
                }
            } else {
                echo "Error creating DNS records";
            }
            pdodb::disconnect();
        } elseif ($d1 == "delete") {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "delete from env_dns where id=?";
            $q = $pdo->prepare($sql);
            if ($q->execute(array(htmlspecialchars($data->id)))) {
                gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => htmlspecialchars($data->projid)), "Deleted DNS configuration:<a href='/env/dns/" . htmlspecialchars($data->projid) . "'>" . htmlspecialchars($data->dns) . "</a>");
                echo "DNS configuration was deleted";
            } else {
                echo "Error deleting DNS configuration";
            }
            pdodb::disconnect();
        } else {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "select id,proj,tags,dnsname,dnsserv,ttl,dnsclass,dnstype," . (DBTYPE == 'oracle' ? "to_char(dnsrecord) as dnsrecord" : "dnsrecord") . "  from env_dns" . (!empty($data->appcode) ? " where proj='" . htmlspecialchars($data->appcode) . "'" : "");
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $data = array();
            $data = $stmt->fetchAll(PDO::FETCH_CLASS);
            pdodb::disconnect();
            echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }
    }
    public static function readSrv($d1)
    {
        global $typesrv;
        if ($d1 == "one") {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "select id,tags,serverid,serverdns,servertype,serverip,servupdated,srvpublic,updperiod from env_servers where id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(htmlspecialchars($data->server)));
            $data = array();
            if ($zobj = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo json_encode($zobj, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            }
            pdodb::disconnect();
        } else {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $partsgrid = explode(",", $data->grid);
            $sql = "select id,tags,serverid,serverdns,servertype,serverip,servupdated from env_servers where groupid in (" . str_repeat('?,', count($partsgrid) - 1) . '?' . ")";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($partsgrid);
            $data = array();
            if ($zobj = $stmt->fetchAll()) {
                foreach ($zobj as $val) {
                    $d = array();
                    $d['tags'] = $val['tags'];
                    $d['id'] = $val['id'];
                    $d['serverid'] = $val['serverid'];
                    $d['server'] = $val['serverdns'];
                    $d['servertype'] = $val['servertype'];
                    $d['serverip'] = $val['serverip'];
                    $d['servupdated'] = $val['servupdated'];
                    $data[] = $d;
                }
            }
            pdodb::disconnect();
            echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }
    }
    public static function updSrv()
    {
        $pdo = pdodb::connect();
        $data = json_decode(file_get_contents("php://input"));
        $sql = "update env_servers set tags=?, pluid=?, srvpublic=? where id=?";
        $q = $pdo->prepare($sql);
        if ($q->execute(array(htmlspecialchars($data->serv->tags), htmlspecialchars($data->serv->place), htmlspecialchars($data->serv->srvpublic), htmlspecialchars($data->serv->id)))) {
            gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => htmlspecialchars($data->projid)), "Updated server configuration:<a href='/env/servers/" . htmlspecialchars($data->projid) . "'>" . htmlspecialchars($data->serv->serverdns) . "</a>");
            if (!empty(htmlspecialchars($data->serv->tags))) {
                gTable::dbsearch(htmlspecialchars($data->serv->serverdns), $_SERVER["HTTP_REFERER"], htmlspecialchars($data->serv->tags));
            }
            if ($data->serv->place) {
                $sql = "update env_places set plused=plused+1 where pluid=?";
                $q = $pdo->prepare($sql);
                $q->execute(array(htmlspecialchars($data->serv->place)));
            }
            echo "Server configuration was updated";
        } else {
            echo "Error updating Server configuration";
        }
        pdodb::disconnect();
    }
    public static function readPlaces($d1)
    {
        if ($d1 == "delete") {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "delete from env_places where pluid=?";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute(array(htmlspecialchars($data->id)))) {
                gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => "system"), "Deleted Place:" . htmlspecialchars($data->name));
                echo "Place was deleted";
            } else {
                echo "Error deleting Place configuration";
            }
            pdodb::disconnect();
        } else {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "select * from env_places";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $data = array();
            $zobj = $stmt->fetchAll();
            foreach ($zobj as $val) {
                $data['used'] = !empty($val['plused']) ? "Yes" : "No";
                $data['name'] = $val['placename'];
                $data['region'] = $val['plregion'];
                $data['city'] = $val['plcity'];
                $data['user'] = explode("#", $val['plcontact'])[2];
                $data['id'] = $val['pluid'];
                $newdata[] = $data;
            }
            pdodb::disconnect();
            echo json_encode($newdata, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }
    }
    public static function readReleases($d1)
    {
        if ($d1 == "delete") {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "delete from env_releases where relid=?";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute(array(htmlspecialchars($data->id)))) {
                gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => "system"), "Deleted Release:" . htmlspecialchars($data->name));
                echo "Release was deleted";
            } else {
                echo "Error deleting Release configuration";
            }
            pdodb::disconnect();
        } else {
            global $reltypes;
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "select * from env_releases";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $data = array();
            $zobj = $stmt->fetchAll();
            foreach ($zobj as $val) {
                $data['name'] = $val['releasename'];
                $data['period'] = $val['relperiod'];
                $data['reltype'] = $reltypes[$val['reltype']];
                $data['user'] = explode("#", $val['relcontact'])[2];
                $data['id'] = $val['relid'];
                $data['relversion'] = $val['relversion'];
                $data['latestver'] = $val['latestver'];
                $data['lastcheck'] = !empty($val['lastcheck'])?$val['lastcheck']:"Not yet";
                $newdata[] = $data;
            }
            pdodb::disconnect();
            echo json_encode($newdata, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }
    }
    public static function appGroups($d1)
    {
        if ($d1 == "readone") {
            header('Content-Type: application/json');
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "select " . (DBTYPE == 'oracle' ? "to_char(appusers) as appusers" : "appusers") . " from config_app_codes where appcode=?";
            $q = $pdo->prepare($sql);
            $q->execute(array(htmlspecialchars($data->appcode)));
            if ($zobj = $q->fetch(PDO::FETCH_ASSOC)) {
                echo $zobj['appusers'];
            }
            pdodb::disconnect();
            exit;
        } elseif ($d1 == "delusr") {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "select id, " . (DBTYPE == 'oracle' ? "to_char(appusers) as appusers" : "appusers") . " from config_app_codes where appcode=?";
            $q = $pdo->prepare($sql);
            $q->execute(array($data->appcode));
            if ($zobj = $q->fetch(PDO::FETCH_ASSOC)) {
                if (!empty($zobj['appusers'])) {$tmp = json_decode($zobj['appusers'], true);} else { $tmp = array();}
                if (!is_array($tmp)) {$tmp = array();}
                unset($tmp[htmlspecialchars($data->userid)]);
                $sql = "update config_app_codes set appusers=? where id=?";
                $q = $pdo->prepare($sql);
                $q->execute(array(json_encode($tmp, true), $zobj["id"]));
                $sql = "select id,appid from " . (htmlspecialchars($data->utype) == "group" ? "user_groups" : "users") . " where " . (htmlspecialchars($data->utype) == "group" ? "group_latname" : "mainuser") . "=?";
                $q = $pdo->prepare($sql);
                $q->execute(array(htmlspecialchars($data->userid)));
                if ($zobj = $q->fetch(PDO::FETCH_ASSOC)) {
                    if (!empty($zobj['appid'])) {$tmp = json_decode($zobj['appid'], true);} else { $tmp = array();}
                    if (!is_array($tmp)) {$tmp = array();}
                    unset($tmp[$data->appcode]);
                    $sql = "update " . (htmlspecialchars($data->utype) == "group" ? "user_groups" : "users") . " set appid=? where id=?";
                    $q = $pdo->prepare($sql);
                    $q->execute(array(json_encode($tmp, true), $zobj["id"]));
                }
            }
            gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => htmlspecialchars($data->appcode)), "Removed user <b>" . htmlspecialchars($data->userid) . "</b> from application <a href='/env/apps/?app=" . $data->appcode . ">" . $data->appcode . "</a>");
            echo "User deleted successfully";
            pdodb::disconnect();
            exit;
        } elseif ($d1 == "addusr") {
            $pdo = pdodb::connect();
            $data = json_decode(file_get_contents("php://input"));
            $sql = "select id, " . (DBTYPE == 'oracle' ? "to_char(appusers) as appusers" : "appusers") . " from config_app_codes where appcode=?";
            $q = $pdo->prepare($sql);
            $q->execute(array(htmlspecialchars($data->appcode)));
            if ($zobj = $q->fetch(PDO::FETCH_ASSOC)) {
                if (!empty($zobj['appusers'])) {$tmp = json_decode($zobj['appusers'], true);} else { $tmp = array();}
                if (!is_array($tmp)) {$tmp = array();}
                $tmp[htmlspecialchars($data->uid)] = array("type" => htmlspecialchars($data->utype), "uname" => htmlspecialchars($data->uname), "uemail" => htmlspecialchars($data->uemail), "uavatar" => htmlspecialchars($data->avatar), "utitle" => htmlspecialchars($data->utitle));
                $sql = "update config_app_codes set appusers=? where id=?";
                $q = $pdo->prepare($sql);
                $q->execute(array(json_encode($tmp, true), $zobj["id"]));

                $sql = "select id,appid from " . (htmlspecialchars($data->utype) == "group" ? "user_groups" : "users") . " where " . (htmlspecialchars($data->utype) == "group" ? "group_latname" : "mainuser") . "=?";
                $q = $pdo->prepare($sql);
                $q->execute(array(htmlspecialchars($data->uid)));
                if ($zobj = $q->fetch(PDO::FETCH_ASSOC)) {
                    if (!empty($zobj['appid'])) {$tmp = json_decode($zobj['appid'], true);} else { $tmp = array();}
                    if (!is_array($tmp)) {$tmp = array();}
                    $tmp[$data->appcode] = "1";
                    $sql = "update " . (htmlspecialchars($data->utype) == "group" ? "user_groups" : "users") . " set appid=? where id=?";
                    $q = $pdo->prepare($sql);
                    $q->execute(array(json_encode($tmp, true), $zobj["id"]));
                }

            }
            gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => htmlspecialchars($data->appcode)), "Added user <b>" . htmlspecialchars($data->uname) . "</b> to application <a href='/env/apps/?app=" . $data->appcode . "'>" . $data->appcode . "</a>");
            echo "User added successfully";
            pdodb::disconnect();
            exit;
        }

    }
    public static function updateSystemnest()
    {
        if (isset($_SESSION["user"]) && !empty($_POST['thistype'])) {
            textClass::change_config('controller/config.vars.php', array($_POST['thistype'] => $_POST['data']));
        } else {
            echo json_encode(array('error' => true, 'type' => "error", 'errorlog' => "Wrong data"));exit;
        }
    }
    public static function delKNBase()
    {
        if (!empty($_POST['thisid'])) {
            global $website;
            $pdo = pdodb::connect();
            $sql = "delete from knowledge_info where id=?";
            $q = $pdo->prepare($sql);
            $q->execute(array(htmlspecialchars($_POST["thisid"])));
            if ($website['gittype']) {
                $sql = "select id,commitid,fileplace from env_gituploads where packuid=? order by id desc LIMIT 1";
                $q = $pdo->prepare($sql);
                $q->execute(array("knowledge_info:" . htmlspecialchars($_POST["thisid"])));
                if ($zobj = $q->fetch(PDO::FETCH_ASSOC)) {
                    $return = vc::gitDelete($zobj["fileplace"], $zobj["commitid"]);
                    $sql = "update env_gituploads set steptype='delete' where id=?";
                    $q = $pdo->prepare($sql);
                    $q->execute(array($zobj["id"]));
                }
            }
            gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => "system"), "Deleted post:" . $_POST["thisname"]);
            pdodb::disconnect();
            exit;
        }
    }
}
