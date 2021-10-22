<?php
class Class_drawapi
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
                case 'editgraph':Class_drawapi::editGraph();
                    break;
                case 'readdes':Class_drawapi::readDesign();
                    break;
                case 'deldesign':Class_drawapi::deleteDesign();
                    break;
                default:echo json_encode(array('error' => true, 'type' => "error", 'errorlog' => "please use the API correctly."));exit;
            }
        } else {
            echo json_encode(array('error' => true, 'type' => "error", 'errorlog' => "please use the API correctly."));exit;
        }
    }
    public static function editGraph()
    {
        global $website;
        $pdo = pdodb::connect();
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->xml)) {
            $nowtime = new DateTime();
            $now = $nowtime->format('Y-m-d H:i') . ":00";
            $xml = simplexml_load_string(stripslashes($data->xml));
            $sql = "update config_diagrams set xmldata=?, desdate=?, gitprepared='1', imgdata=?  where desid=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(urldecode(gzinflate(base64_decode($xml->diagram))), $now, $data->data, $data->desid));
            if ($website['gittype']) {
                $shagit = "";
                if ($website['gittype'] == "github" && $_POST["gitprepared"] == 1) {
                    $resp = vc::gitTreelist("articles/draw/" . $data->desid . ".txt");
                    $shagit = json_decode($resp, true)["sha"];
                }
                $return = vc::gitAdd("text", urldecode(gzinflate(base64_decode($xml->diagram))), "articles/draw/" . $data->desid . ".txt", ($data->gitprepared == 1 ? true : false), $shagit, true);
                if (empty($return["err"])) {
                    $tmp["gitupload"] = "articles/draw/" . $data->desid . ".txt";
                } else {
                    $msg[] = $return["err"];
                    $tmp["gitupload"] = false;
                }
                if ($tmp["gitupload"]) {
                    $resp = vc::GetCommitID($tmp["gitupload"]);
                    $lastcommit = json_decode($resp, true)[0]["id"];
                    if ($lastcommit) {
                        $sql = "insert into env_gituploads (gittype,commitid,packuid,fileplace,steptype,stepuser) values (?,?,?,?,?,?)";
                        $q = $pdo->prepare($sql);
                        $q->execute(array($website['gittype'], $lastcommit, "config_diagrams:" . $data->id, "articles/draw/" . $data->desid . ".txt", "prepare", $_SESSION["user"]));
                    }
                }
            }
            gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => "system"), "Updated diagram:<a href='/draw/" . $data->desid . "'>" . $data->desid . "</a>");
            echo "done";
        } else {
            echo "empty data";
        }
        pdodb::disconnect();
        exit;
    }
    public static function readDesign()
    {
        $pdo = pdodb::connect();
        //$data = json_decode(file_get_contents("php://input"));
        $tmp = array();
        $tmp["likesearch"] = " desuser='" . $_SESSION["user"] . "'";
        if (!empty($_SESSION["userdata"]["ugroups"])) {
            foreach ($_SESSION["userdata"]["ugroups"] as $keyin => $valin) {
                $tmp["likesearch"] .= " or accgroups like '%" . $keyin . "%'";
            }
        }
        $sql = "select id,reqid,desid,desdate,desuser,imgdata,desname,tags from config_diagrams where (" . $tmp["likesearch"] . ")";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $data = array();
        $data = $stmt->fetchAll(PDO::FETCH_CLASS);
        pdodb::disconnect();
        echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);exit;
    }
    public static function deleteDesign()
    {
        global $website;
        $pdo = pdodb::connect();
        $data = json_decode(file_get_contents("php://input"));
        $sql = "delete from config_diagrams where id=?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array($data->id))) {
            gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => "system"), "Deleted design:" . htmlspecialchars($data->desid));
            if ($website['gittype']) {
              $sql = "select id,commitid,fileplace from env_gituploads where packuid=? order by id desc LIMIT 1";
              $q = $pdo->prepare($sql);
              $q->execute(array("config_diagrams:" . htmlspecialchars($data->id)));
              if ($zobj = $q->fetch(PDO::FETCH_ASSOC)) {
                  $return = vc::gitDelete($zobj["fileplace"], $zobj["commitid"]);
                  $sql = "update env_gituploads set steptype='delete' where id=?";
                  $q = $pdo->prepare($sql);
                  $q->execute(array($zobj["id"]));
              }
            }
            echo "Design was deleted.";
        } else {
            echo "Unable to delete design.";
        }
        pdodb::disconnect();
    }
}
