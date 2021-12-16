<?php
$modulelist["db"]["name"]="Main DB module";
$modulelist["db"]["system"]=true;
class pdodb
  {
    private static $dbName = DB_NAME ;
    private static $dbHost = DB_HOST ;
    private static $dbUsername = DB_USER;
    private static $dbUserPassword = DB_PASS;
    private static $cont  = null;
    public function __construct() {
      die('Init function is not allowed');
    }
    public static function connect()
    {
      if ( null == self::$cont )
      {   
        try
        {
          if(DBTYPE=="mysql"){
            $options = array(
              PDO::ATTR_PERSISTENT => true,
             //  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
              PDO::ATTR_CASE => PDO::CASE_NATURAL,
              PDO::MYSQL_ATTR_FOUND_ROWS => true,
              PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "utf8"'
            );
            self::$cont =  new PDO( "mysql:host=".self::$dbHost.";"."dbname=".self::$dbName, self::$dbUsername, self::$dbUserPassword, $options);
          } elseif(DBTYPE=="sqlite"){
            self::$cont =  new PDO( "sqlite:db/userdb.sqlite");
          } elseif(DBTYPE=="postgresql"){
            self::$cont =  new PDO("pgsql:host=".self::$dbHost.";port=5432;dbname=".self::$dbName.";user=".self::$dbUsername.";password=".self::$dbUserPassword );
            self::$cont->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          }  else {
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
class checkDB{
 public static function tableExists($pdo, $table,$dbtype) {
    try {
      if($dbtype=="oracle"){
        $result = $pdo->query("SELECT 1 FROM $table where ROWNUM <= 1");
      } else {
        $result = $pdo->query("SELECT 1 FROM $table LIMIT 1");
      }
    } catch (Exception $e) {
        return FALSE;
    }
   return $result !== FALSE;
 }  
}
class clearSESS{
  public static function template_end(){
    pdodb::disconnect();
    $pdosql = null;
  if(get_defined_vars()){
    $vars = array_keys(get_defined_vars());
    foreach($vars as $var) { unset(${"$var"}); }
  }
  if($GLOBALS){
    foreach (array_keys($GLOBALS) as $k) {
      unset($$k);
     if($k){ unset($k); } 
     if(isset($GLOBALS[$k])){ unset($GLOBALS[$k]); }
     }
  }
  }
}