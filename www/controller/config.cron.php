<?php
class Cron{
    public static function htmlspecialchars_decode($text)
    {
        return strtr($text, array_flip(get_html_translation_table(HTML_SPECIALCHARS)));
    }
    public static function startCron(){
    $pdo = pdodb::connect(); 
   // $now=date("Y-m-d H:i").":00"; 
    $nowtime = new DateTime();
    $now=$nowtime->format('Y-m-d H:i').":00";
    $sql="select id,proj,env,objid,connstr,objtype,deplenv from env_jobs where nrun='".$now."' and jenabled='1' and jobstatus='0'"; 
    $stmt = $pdo->prepare($sql);
    if($stmt->execute()){
      $zobj = $stmt->fetchAll();
      foreach($zobj as $val) {
        gTable::update("env_jobs",array("jobstatus"=>"2","lrun"=>$now)," where id='".$val["id"]."'");
        if($val["deplenv"]=="qm"){
          $deplarr=array();
          $deplarr["appl"]=$val["proj"];
          $deplarr["user"]="cronjob";
          $deplarr["job"]=true;
          $deplarr["type"]=str_replace("mqenv_mq_","",$val["objtype"]);
          $deplarr["deplinfo"]["qm"]=$val["connstr"];
          $deplarr["deplinfo"]["deplenv"]=$val["env"];
          $deplarr["deplinfo"]["selectedobj"][]=$val["objid"];
          $return=Class_deplapi::deploySelected(json_encode($deplarr)); 
          gTable::update("env_jobs",array("jobstatus"=>(json_decode($return)->error?"3":"1"),"lrun"=>$now,"jobdata"=>$return)," where id='".$val["id"]."'");
        } 
        if($val["deplenv"]=="fte"){
          $deplarr=array();
          $deplarr["appcode"]=$val["proj"];
          $deplarr["user"]="cronjob";
          $deplarr["job"]=true;
          $deplarr["fteids"][]=$val["objid"];
          $deplarr["env"]=$val["env"];
          $return=Class_deplapi::deployFte(json_encode($deplarr)); 
          gTable::update("env_jobs",array("jobstatus"=>(json_decode($return)->type=="error"?"3":"1"),"lrun"=>$now,"jobdata"=>$return)," where id='".$val["id"]."'");
        } 
        if($val["deplenv"]=="tibems"){
          $deplarr=array();
          $deplarr["job"]=true;
          $deplarr["env"]=$val["env"];
          $deplarr["user"]="cronjob";
          $deplarr["type"]=$val["objtype"];
          $deplarr["srv"]=$val["connstr"];
          $deplarr["appl"]=$val["proj"];
          $deplarr["objects"][]=$val["objid"];
          $return=Class_tibapi::deploySelected(json_encode($deplarr)); 
          $sql="update env_jobs set jobstatus=?, lrun=?, jobdata=? where id=?";
          $stmt = $pdo->prepare($sql);
          $stmt->execute(array((json_decode($return)->error?"3":"1"),$now,$return,$val["id"]));
        }
      }
    }
    //IBM MQ inventory
    $sql="select id,jobrepeat,".(DBTYPE=='oracle'?"to_char(connstr) as connstr":"connstr")." from env_jobs_mq where nrun='".$now."'"; 
    $stmt = $pdo->prepare($sql);
    if($stmt->execute()){
      $zobj = $stmt->fetchAll();
      foreach($zobj as $val) {
        if(!empty($val["jobrepeat"])){
          if($val["jobrepeat"]==1){
            $nowtime->modify('+1 day');
            $next=$nowtime->format('Y-m-d H:i').":00";
          } else if($val["jobrepeat"]==2){
            $nowtime->modify('+1 week');
            $next=$nowtime->format('Y-m-d H:i').":00";
          } else if($val["jobrepeat"]==3){
            $nowtime->modify('+1 month');
            $next=$nowtime->format('Y-m-d H:i').":00";
          } else if($val["jobrepeat"]==4){
            $nowtime->modify('+1 hour');
            $next=$nowtime->format('Y-m-d H:i').":00";
          }
        } else {
          $next=$now;
        }
        $output=IBMMQ::execJava("READ",$val["connstr"],"dis qm;");
        $return=$output[0];
        $sql="update env_jobs_mq set jobstatus=?, lrun=?, nrun=?, qminv=? where id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array((json_decode($return)->error?"3":"1"),$now,$next,$return,$val["id"]));
      }
    }
    pdodb::disconnect();
    clearSESS::template_end();
    exit;
    }
}

