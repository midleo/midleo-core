<?php
class pdodb
  { 
    private static $cont  = null;
    public function __construct() {
      die('Init function is not allowed');
    }
    public static function connect()
    {
      global $dbuser;
      global $dbpass;
      global $dbtype;
      global $dbname;
      global $dbhost;
      if ( null == self::$cont )
      {   
        try
        {
          if($dbtype=="mysql"){
            $options = array(
              PDO::ATTR_PERSISTENT => true,
             //  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
              PDO::ATTR_CASE => PDO::CASE_NATURAL,
              PDO::MYSQL_ATTR_FOUND_ROWS => true,
              PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "utf8"'
            );
            self::$cont =  new PDO( "mysql:host=".$dbhost.";"."dbname=".$dbname, $dbuser, $dbpass, $options);
          } elseif($dbtype=="sqlite"){
            self::$cont =  new PDO( "sqlite:db/userdb.sqlite");
          } elseif($dbtype=="postgresql"){
            self::$cont =  new PDO("pgsql:dbname=".$dbname.";host=".$dbhost.";user=".$dbuser.";password=".$dbpass);
          } elseif($dbtype=="oracle"){
            $options = array(
              // PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
              PDO::ATTR_EMULATE_PREPARES => false,
              PDO::ATTR_CASE => PDO::CASE_LOWER,
              PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            );
            $dbtns = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=".$dbhost.")(PORT=".$oracle_port."))(CONNECT_DATA=(SERVICE_NAME=".$oracle_sn.")(SID=".$oracle_sid.")))";
            self::$cont = new PDO("oci:dbname=".$dbtns.";charset=utf8", $dbuser, $dbpass, $options);
          } elseif($dbtype=="mssql"){
            $mssqldriver = '{'.odbc_driver_name.'}';
            self::$cont = new PDO("odbc:Driver=$mssqldriver;Server=".$dbhost.";Database=".$dbname, $dbuser, $dbpass);
          } else {
            self::$cont =  new PDO("demodb","demouser","demopass");
          }
        }
        catch(PDOException $e)
        {
          $errmess=$e->getMessage();
          include "dbmaintenance.php";
          die;
        }
      }
      return self::$cont;
    }
    public static function disconnect()
    {
      self::$cont = null;
    }
  }
class sessionClass{
  public static function getviews($thistable,$thisuser){ 
    $pdo = pdodb::connect();
    $sql = "select month,views from $thistable where mainuser=? and month>DATE_SUB(now(), INTERVAL 4 MONTH) order by id desc"; 
    $q = $pdo->prepare($sql);
    $q->execute(array($thisuser)); 
    $in==0;
    $zobj = $q->fetchAll();
    foreach($zobj as $val) {
      $in++; $content[] = $val;
    }
    return json_encode($content);
    pdodb::disconnect();
    unset($q,$sql,$val,$zobj,$content);
  }
}