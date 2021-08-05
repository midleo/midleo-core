<?php
$modulelist["install"]["name"]="Basic install module";
$modulelist["install"]["system"]=true;
  class install{
    public static function remove_comments(&$output)
    {
      $lines = explode("\n", $output);
      $output = "";
      $linecount = count($lines);
      $in_comment = false;
      for($i = 0; $i < $linecount; $i++)
          {
            if( preg_match("/^\/\*/", preg_quote($lines[$i])) )
            {
              $in_comment = true;
            }
            if( !$in_comment )
            {
              $output .= $lines[$i] . "\n";
            }
            if( preg_match("/\*\/$/", preg_quote($lines[$i])) )
            {
              $in_comment = false;
            }
          }
      unset($lines);
      return $output;
    }
    public static function remove_remarks($sql)
    {
      $lines = explode("\n", $sql);
      $sql = "";
      $linecount = count($lines);
      $output = "";
      for ($i = 0; $i < $linecount; $i++)
           {
             if (($i != ($linecount - 1)) || (strlen($lines[$i]) > 0))
             {
               if (isset($lines[$i][0]) && $lines[$i][0] != "#")
               {
                 $output .= $lines[$i] . "\n";
               }
               else
               {
                 $output .= "\n";
               }
               $lines[$i] = "";
             }
           }
      return $output;
    }
    public static function split_sql_file($sql, $delimiter)
    {
      $tokens = explode($delimiter, $sql);
      $sql = "";
      $output = array();
      $matches = array();
      $token_count = count($tokens);
      for ($i = 0; $i < $token_count; $i++)
           {
             if (($i != ($token_count - 1)) || (strlen($tokens[$i] > 0)))
             {
               $total_quotes = preg_match_all("/'/", $tokens[$i], $matches);
               $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$i], $matches);
               $unescaped_quotes = $total_quotes - $escaped_quotes;
               if (($unescaped_quotes % 2) == 0)
               {
                 $output[] = $tokens[$i];
                 $tokens[$i] = "";
               }
               else
               {
                 $temp = $tokens[$i] . $delimiter;
                 $tokens[$i] = "";
                 $complete_stmt = false;
                 for ($j = $i + 1; (!$complete_stmt && ($j < $token_count)); $j++)
                      {
                        $total_quotes = preg_match_all("/'/", $tokens[$j], $matches);
                        $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$j], $matches);
                        $unescaped_quotes = $total_quotes - $escaped_quotes;
                        if (($unescaped_quotes % 2) == 1)
                        {
                          $output[] = $temp . $tokens[$j];
                          $tokens[$j] = "";
                          $temp = "";
                          $complete_stmt = true;
                          $i = $j;
                        }
                        else
                        {
                          $temp .= $tokens[$j] . $delimiter;
                          $tokens[$j] = "";
                        }
                      }
               }
             }
           }
      return $output;
    }
  }
class Class_apiinst{
  public static function getPage($thisarray){
    global $installedapp;
    global $website;
    global $maindir;
    if(!empty($thisarray['p1'])) {
    switch($thisarray['p1']) {
      case 'db':  Class_apiinst::dbinst($thisarray['p2']);  break;
      default: echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"please use the API correctly."));exit;
                    }
  } else { echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"please use the API correctly."));exit;  } 
  }
  public static function dbinst($d1){
    if($d1=="check"){
      $data = json_decode(file_get_contents("php://input"));
      $options = array(
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "utf8"'
      ); 
      $dbtype=$data->db->type;
      try{
        if($dbtype=="mysql"){
          $dbh =  new PDO( "mysql:host=".$data->db->dbhost.";"."dbname=".$data->db->dbname, $data->db->dbuser, $data->db->dbpass, $options);
        } elseif($dbtype=="sqlite"){
          $dbh =  new PDO( "sqlite:db/userdb.sqlite");
        } elseif($dbtype=="postgresql"){
          $dbh = new PDO("pgsql:dbname=".$data->db->dbname.";host=".$data->db->dbhost.";user=".$data->db->dbuser.";password=".$data->db->dbpass);
        } elseif($dbtype=="oracle"){
          $dbtns = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=".$data->db->dbhost.")(PORT=".$data->db->oracle_port."))(CONNECT_DATA=(SERVICE_NAME=".$data->db->oracle_sn.")(SID=".$data->db->oracle_sid.")))";
          $dbh = new PDO("oci:dbname=" . $dbtns . ";charset=utf8", $data->db->dbuser, $data->db->dbpass, array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));
        } elseif($dbtype=="mssql"){
          $mssqldriver = '{'.$data->db->odbc_name.'}';
          $dbh = new PDO("odbc:Driver=$mssqldriver;Server=".$data->db->dbhost.";Database=".$data->db->dbname, $data->db->dbuser, $data->db->dbpass);
        } else {
          $dbh =  new PDO("demodb","demouser","demopass");
        }
        echo json_encode(array("success"=>true,"info"=>"Test connection successfull"));exit;
      }
      catch(PDOException $e)
      {
        $errmess=$e->getMessage();
        echo json_encode(array("success"=>false,"info"=>$errmess));exit;
      }
    }
  }
}
class Class_install{
  public static function getPage($thisarray){
    global $installedapp;
    global $website;
	  global $page;
    global $modulelist;
    global $maindir;
    if($installedapp=="yes"){
    header('Location: /?');
  } else{
    $dbtype=DBTYPE;
    if(!empty($dbtype)){     
      if(isset($_POST['doUserInst'])){
        $pdo = pdodb::connect();
        $data = json_decode(file_get_contents("php://input"));
        $uid=md5(uniqid(microtime()).$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].htmlspecialchars($_POST['usname']));
        $pwd = inputClass::PwdHash(htmlspecialchars($_POST['admpass']));
        $sql="insert into users(uuid,mainuser,email,pwd,fullname,user_level) values(?,?,?,?,?,'5')";
        $q = $pdo->prepare($sql);
        if($q->execute(array($uid,htmlspecialchars($_POST['admuser']),htmlspecialchars($_POST['admemail']),$pwd,htmlspecialchars($_POST['admfname'])))){
          $newSettings = array(
            'installedapp' => "yes",
          );
          textClass::change_config('controller/config.vars.php', $newSettings);
          sleep(2);
          header('Location: /?');
        } else {
          $err[]="Please check if all tables were created";
        }
        pdodb::disconnect();
      }   
      include "public/modules/css.php";    ?>
     </head>
<body id="ngApp" ng-app="ngApp" ng-controller="instCtrl">
<section id="wrapper">
        <div class="login-register">
        <div class="row">
        <div class="col-md-6" style="background-image:url(/assets/images/login-idea.svg);background-position:right;background-repeat: no-repeat;">
        </div>
        <div class="col-md-6 text-start" style="height:100%;">
        <div class="login-box card" style="width: 500px;">
                <div class="card-body">
                    <form class="form-horizontal form-material text-center" id="loginform" action="" method="post" ng-app>
  
                                <div style="position: absolute;right: 10px;top: 10px;">
                                    <span class="text-muted">v. Initial</span> 
                                </div>
                        <img data-bs-toggle="tooltip" src="/assets/images/midleo-logo-dark.svg" alt="Midleo CORE" title="Midleo CORE" class="light-logo" style="width:80px;" />
        <br>
                        <h3 class="p-2 rounded-title mb-3">Fill admin data</h3>
    
    

      <?php if($dbtype=="oracle"){ ?>
      <div class="alert alert-info">Please install the scripts from /data/db/oracle and then proceed</div>
      <?php } else if($dbtype=="postgresql"){ ?>
      <div class="alert alert-info">Please install the scripts from /data/db/postgresql and then proceed</div>
      <?php } ?>
    
          <div class="form-group row">
              <label class="form-control-label text-lg-right col-md-4">Admin Fullname</label>
              <div class="col-md-8">
              <input name="admfname" type="text" class="form-control" required="">
            </div>
          </div>
          <div class="form-group row">
              <label class="form-control-label text-lg-right col-md-4">Admin Email</label>
              <div class="col-md-8">
               <input name="admemail" type="text" class="form-control">
            </div>
          </div>
          <div class="form-group row">
              <label class="form-control-label text-lg-right col-md-4">Admin username</label>
              <div class="col-md-8">
              <input name="admuser" type="text" class="form-control" required="">
            </div>
          </div>
          <div class="form-group row">
              <label class="form-control-label text-lg-right col-md-4">Admin password</label>
              <div class="col-md-8">
               <input name="admpass" type="password" class="form-control" required="">
            </div>
          </div>
          <button type="submit" class="btn btn-success" name="doUserInst"><i class="mdi mdi-account-plus-outline"></i>&nbsp;Create</button>
         </form>
      </div>
      </div>
      </div>
    </div>
  </div>

<?php
echo '</div></div>';
include "public/modules/footer.php";
echo '</div></div>';
include "public/modules/js.php"; 
echo '</body></html>';
include "public/modules/template_end.php";
?>
<?php
      pdodb::disconnect();
    } else { 
      if(isset($_POST['doDBinstall'])){
        $newSettings = array(
          'dbtype' => htmlspecialchars($_POST['dbtype']),
          'dbhost' => htmlspecialchars($_POST['dbhost']),
          'dbuser' => htmlspecialchars($_POST['dbuser']),
          'dbpass' => htmlspecialchars($_POST['dbpass']),
          'dbname' => htmlspecialchars($_POST['dbname']),
          'oracle_port' => htmlspecialchars($_POST['oracle_port']),
          'oracle_sn' => htmlspecialchars($_POST['oracle_sn']),
          'oracle_sid' => htmlspecialchars($_POST['oracle_sid']),
          'odbc_name' => htmlspecialchars($_POST['odbc_name']),
        );
        textClass::change_config('controller/config.db.php', $newSettings);
        sleep(2);
        header('Location: /install/?');
      }
      if(isset($_POST['doDBsqlite'])){
      }
      include "public/modules/css.php";?>
        </head>
<body id="ngApp" ng-app="ngApp" ng-controller="instCtrl">
<section id="wrapper">
        <div class="login-register">
        <div class="row">
        <div class="col-md-6" style="background-image:url(/assets/images/login-idea.svg);background-position:right;background-repeat: no-repeat;">
        </div>
        <div class="col-md-6 text-start" style="height:100%;">
        <div class="login-box card" style="width: 500px;">
                <div class="card-body">
                    <form class="form-material text-center" id="form" name="form" action="" method="post" ng-app>
  
                                <div style="position: absolute;right: 10px;top: 10px;">
                                    <span class="text-muted">v. Initial</span> 
                                </div>
                        <img data-bs-toggle="tooltip" src="/assets/images/midleo-logo-dark.svg" alt="Midleo CORE" title="Midleo CORE" class="light-logo" style="width:80px;" />
        <br>
                        <h3 class="p-2 rounded-title mb-3">Select Database type</h3>



        
          <div class="form-group row">
              <label class="form-control-label text-lg-right col-md-4" ng-class=" {'has-error':!db.type}">Database type</label>
              <div class="col-md-8">
              <select ng-required="true" name="dbtype" ng-model="db.type" class="form-control">
                <option value="">Please select</option>
                <option value="mysql">Mysql Database</option>
                <option value="mssql">Microsoft SQL Database</option>
                <option value="sqlite">SQLite Database</option>
                <option value="oracle">Oracle Database</option>
                <option value="postgresql">PostgreSQL Database</option>
              </select>
              </div>
          </div>
          <div class="form-group row" ng-show="db.type=='mysql' || db.type=='oracle' || db.type=='mssql' || db.type=='postgresql'">
            
              <label class="form-control-label text-lg-right col-md-4" ng-class=" {'has-error':!db.dbhost}">Database Host</label>
              <div class="col-md-8">
              <input ng-model="db.dbhost" name="dbhost" ng-required="true" type="text" class="form-control">
            </div>
          </div>
          <div class="form-group row" ng-show="db.type=='mysql' || db.type=='oracle' || db.type=='mssql' || db.type=='postgresql'">
              <label class="form-control-label text-lg-right col-md-4" ng-class=" {'has-error':!db.dbname}">Database Name</label>
              <div class="col-md-8">
               <input ng-model="db.dbname" name="dbname" ng-required="true" type="text" class="form-control">
            </div>
          </div>
          <div class="form-group row" ng-show="db.type=='oracle'">
          <label class="form-control-label text-lg-right col-md-4">Oracle DB port</label>
          <div class="col-md-8">
              <input ng-model="db.oracle_port" name="oracle_port" type="text" class="form-control">
            </div>
          </div>
          <div class="form-group row" ng-show="db.type=='oracle'">
              <label class="form-control-label text-lg-right col-md-4">Oracle Service name</label>
              <div class="col-md-8">
                <input ng-model="db.oracle_sn" name="oracle_sn" type="text" class="form-control">
            </div>
          </div>
          <div class="form-group row" ng-show="db.type=='oracle'">
            <label class="form-control-label text-lg-right col-md-4">Oracle SID</label>
            <div class="col-md-8">
             <input ng-model="db.oracle_sid" name="oracle_sid" type="text" class="form-control">
            </div>
          </div>
          <div class="form-group row" ng-show="db.type=='mssql'">
              <label class="form-control-label text-lg-right col-md-4">ODBC driver name</label>
              <div class="col-md-8">
              <input ng-model="db.odbc_name" name="odbc_name" type="text" class="form-control">
            </div>
          </div>
          <div class="form-group row" ng-show="db.type=='mysql' || db.type=='oracle' || db.type=='mssql' || db.type=='postgresql'">
              <label class="form-control-label text-lg-right col-md-4" ng-class=" {'has-error':!db.dbuser}">Username</label>
              <div class="col-md-8">
              <input ng-required="true" name="dbuser" ng-model="db.dbuser" type="text" class="form-control">
            </div>
          </div>
          <div class="form-group row" ng-show="db.type=='mysql' || db.type=='oracle' || db.type=='mssql' || db.type=='postgresql'">
              <label class="form-control-label text-lg-right col-md-4" ng-class=" {'has-error':!db.dbpass}">Password</label>
              <div class="col-md-8">
              <input ng-required="true" name="dbpass" ng-model="db.dbpass" type="password" class="form-control">
            </div>
          </div>
          <button id="btn-db-check" ng-show="db.type=='mysql' || db.type=='oracle' || db.type=='mssql' || db.type=='postgresql'" type="button" class="btn btn-light" ng-click="form.$valid && checkdb()"><i class="mdi mdi-cog-sync-outline"></i>&nbsp;Test connection</button>
          <button ng-hide="db.type=='sqlite'" id="btn-db-install" type="submit" name="doDBinstall" class="btn btn-light" style="display:none;" disabled="disabled"><i class="mdi mdi-database-import"></i>&nbsp;Install</button>
          <button ng-show="db.type=='sqlite'" name="doDBsqlite" type="submit" class="btn btn-light"><i class="mdi mdi-database-import"></i>&nbsp;Install</button>
        </form>
      </div>
      </div>
      </div>
    </div>
  </div>



  <?php include "public/modules/footer.php";?>
  <?php include "public/modules/js.php";?>
  <script type="text/javascript" src="/assets/js/inst-controller.js"></script>
</body>
</html>
<?php include "public/modules/template_end.php";
 }
    }
  }
}