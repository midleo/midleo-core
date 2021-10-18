<?php
class Class_vcapi
{
    public static function getPage($thisarray)
    {
        global $website;
        global $maindir;
        session_start();
        if (!empty($_SESSION['user'])) {
            header('Content-type:application/json;charset=utf-8');
            switch ($thisarray["p1"]) {
                case 'history':Class_vcapi::History();
                    break;
                case 'gitlist':Class_vcapi::readGitInfo();
                    break;
                default:echo json_encode(array('error' => true, 'type' => "error", 'errorlog' => "please use the API correctly."));exit;
            }
        } else {echo json_encode(array('error' => true, 'type' => "error", 'errorlog' => "please use the API correctly."));exit;}
    }
    public static function History()
    {
        $data = $_POST;
        $tmp = array();
        $tmp["error"] = false;
        if ($data['hsearch']) {
            $tmp["case"] = explode(":", $data['hsearch'])[0];
            $tmp["caseid"] = explode(":", $data['hsearch'])[1];
            if ($tmp["caseid"]) {
                $pdo = pdodb::connect();
                $sql = "select * from env_gituploads where packuid=?";
                $q = $pdo->prepare($sql);
                $q->execute(array($data['hsearch']));
                $zobj = $q->fetchAll();
                foreach ($zobj as $val) {
                    $data = array();
                    $data['gittype'] = $val['gittype'];
                    $data['commitid'] = $val['commitid'];
                    $data['packuid'] = $val['packuid'];
                    $data['fileplace'] = $val['fileplace'];
                    $data['steptime'] = $val['steptime'];
                    $data['steptype'] = $val['steptype'];
                    $data['stepuser'] = $val['stepuser'];
                    $newdata[] = $data;
                }
                pdodb::disconnect();
            } else {
                $tmp["error"] = true;
            }
        } else {
            $tmp["error"] = true;
        }
        if ($tmp["error"]) {
            echo json_encode(array('error' => true, 'type' => "error", 'errorlog' => "Missing mandatory parameter: hsearch"));
            exit;
        } else {
            echo json_encode($newdata, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);exit;
        }
    }
    public static function readGitInfo()
    {
        $data = json_decode(file_get_contents("php://input"));
        if (!$data->pkg) {
            echo json_encode(array(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);exit;
        }
        $pdo = pdodb::connect();
        $sql = "select * from env_gituploads where packuid=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($data->pkg));
        $zobj = $stmt->fetchAll();
        foreach ($zobj as $val) {
            $data = array();
            $data['gittype'] = $val['gittype'];
            $data['commitid'] = $val['commitid'];
            $data['packuid'] = $val['packuid'];
            $data['fileplace'] = $val['fileplace'];
            $data['steptime'] = $val['steptime'];
            $data['steptype'] = $val['steptype'];
            $data['stepuser'] = $val['stepuser'];
            $newdata[] = $data;
        }
        pdodb::disconnect();
        echo json_encode($newdata, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);exit;
    }
}
