<?php
$modulelist["gtable"]["name"] = "Main table module";
$modulelist["gtable"]["system"] = true;
class gTable
{
    public static function countAll($table, $where = null)
    {
        $pdo = pdodb::connect();
        $query = "select count(id) FROM " . $table . $where;
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
        pdodb::disconnect();
    }
    public static function update($table, $what = array("key" => "val"), $where = null)
    {
        if ($what["key"] != "val") {
            $pdo = pdodb::connect();
            $keys = "";
            foreach ($what as $key => $val) {
                $keys .= htmlspecialchars($key) . "='" . str_replace("'", "\"", htmlspecialchars($val)) . "',";
            }
            $query = "update " . $table . " set " . rtrim($keys, ',') . $where;
            $stmt = $pdo->prepare($query);
            if ($stmt->execute()) {
                return $table . " updated successfully";
            } else {
                return "There was a problem updating " . $table;
            }
            pdodb::disconnect();
        }
    }
    public static function create($table, $what = array("key" => "val"))
    {
        if ($what["key"] != "val") {
            $pdo = pdodb::connect();
            $keys = "";
            $vals = "";
            foreach ($what as $key => $val) {
                $keys .= htmlspecialchars($key) . ",";
                $vals .= "'" . htmlspecialchars($val) . "',";
            }
            $query = "insert into " . $table . " (" . rtrim($keys, ',') . ") values(" . rtrim($vals, ',') . ")";
            $stmt = $pdo->prepare($query);
            if ($stmt->execute()) {
                echo "Values inserted in " . $table . " successfully";
            } else {
                echo "There was a problem inserting in " . $table;
            }
            pdodb::disconnect();
        }
    }
    public static function delete($table, $where = null)
    {
        if (!empty($table)) {
            $pdo = pdodb::connect();
            $query = "delete from " . $table . $where;
            $stmt = $pdo->prepare($query);
            if ($stmt->execute()) {
                echo "Successfully deleted from " . $table;
            } else {
                echo "There was a problem deleting from " . $table;
            }
            pdodb::disconnect();
        }
    }
    public static function read($table, $what = "val", $where = null)
    {
        if (!empty($table)) {
            $pdo = pdodb::connect();
            $query = "select " . $what . " from " . $table . $where;
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            return $stmt->fetchColumn();
            pdodb::disconnect();
        }
    }
    public static function closeAll()
    {
        global $pdosql;
        global $GLOBALS;

        $pdosql = null;
        $vars = array_keys(get_defined_vars());
        foreach ($vars as $var) {unset(${"$var"});}
        foreach (array_keys($GLOBALS) as $k) {
            unset($$k);
            unset($k);
            unset($GLOBALS[$k]);
        }
    }
    public static function track($trackuser, $trackuserid,?array $trackdata, $trackinfo)
    {
        $pdo = pdodb::connect();
        $sql = "insert into tracking (who,whoid,what,projid,reqid,appid,srvid,appsrvid,wfid) values(?,?,?,?,?,?,?,?,?)";
        $q = $pdo->prepare($sql);
        $q->execute(array($trackuser,$trackuserid, $trackinfo, $trackdata["projid"], $trackdata["reqid"], $trackdata["appid"], $trackdata["srvid"], $trackdata["appsrvid"], $trackdata["wfid"]));
        pdodb::disconnect();
    }
    public static function dbsearch($what, $where, $tags)
    {
        $pdo = pdodb::connect();
        $sql = "update search set tags=? where what=? and swhere=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($tags, $what, $where));
        if ($q->rowCount() > 0) {} else {
            $sql = "insert into search (what,swhere,tags) values(?,?,?)";
            $q = $pdo->prepare($sql);
            $q->execute(array($what, $where, $tags));
        }
        pdodb::disconnect();
    }
    public static function rmsearch($what, $where)
    {
        $pdo = pdodb::connect();
        $sql = "delete from search where what=? and swhere=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($what, $where));
        pdodb::disconnect();
    }
}
