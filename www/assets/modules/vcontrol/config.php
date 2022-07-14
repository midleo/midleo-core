<?php
$modulelist["vcontrol"]["name"]="GIT Version control";
include_once "api.php";
class vc{
   public static function gitAdd(?string $file_encoding,$file_git,$file_place,$file_exist=false,$file_sha="",$textcont=false){
     global $website;
     if(in_array($website['gittype'],array("github","gitlab","bitbucket"))){
      if($website['gittype']=="gitlab"){
        $data_git = array(
        'branch'=>"master",
        'commit_message'=>'system upload for midleo.CORE from:'.$_SESSION["user"],
        'content'=> $textcont?$file_git:($file_encoding=="base64"?base64_encode(file_get_contents($file_git)):file_get_contents($file_git)),
        'author_name'=>$_SESSION["user"],
        );
        if($file_encoding=="base64"){
          $data_git['encoding']=$file_encoding;
        }
        $data_string_git = json_encode($data_git);
      }
      if($website['gittype']=="github"){
        $data_git = array(
            'message'=>'system upload for midleo.CORE from:'.$_SESSION["user"],
            'content'=> base64_encode(file_get_contents($file_git)),
            'committer'=> array(
              'name'=>$_SESSION["user"],
              'email' => 'system@midleo.app'
            ),        
         ); 
         if($file_exist){
           $data_git["sha"]=$file_sha;
         }
         $data_string_git = json_encode($data_git); 
      }
      if($website['gittype']=="bitbucket"){
        $data_git = array(
            'branch'=>"master",
            'message'=>'system upload for midleo.CORE from:'.$_SESSION["user"],
            $file_place => file_get_contents($file_git),  
            'author' => $_SESSION["user"]." @MIDLEO <system@midleo.app>",    
          ); 
        $data_string_git = http_build_query($data_git);
      }
      $ch = curl_init(); 
      if($website['gittype']=="gitlab"){
       curl_setopt($ch, CURLOPT_URL, $website['gitreposurl']."/api/v4/projects/".$website['gitpjid']."/repository/files/".urlencode($file_place));  
      }
      if($website['gittype']=="github"){
        curl_setopt($ch, CURLOPT_URL, $website['githubapi']."/repos/".$website['githubowner']."/".$website['githubrepo']."/contents/".urlencode($file_place)); 
      }
      if($website['gittype']=="bitbucket"){
        curl_setopt($ch, CURLOPT_URL, $website['gitbbapi']."/2.0/repositories/".$website['gitbbowner']."/".$website['gitbbrepo']."/src/");  
      }
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
      curl_setopt($ch, CURLOPT_POST, 1);
      if($website['gittype']=="gitlab"){
       curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $file_exist?"PUT":"POST");  
      }
      if($website['gittype']=="bitbucket"){
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
      }
      if($website['gittype']=="github"){
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); 
      }
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string_git);
   //   curl_setopt($ch, CURLOPT_FAILONERROR, true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      if($website['gittype']=="gitlab"){
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
         'Accept: application/json',
         'Content-Type: application/json',
         'PRIVATE-TOKEN: '.$website['gittoken']
         )
        );   
      }
      if($website['gittype']=="bitbucket"){
       curl_setopt($ch, CURLOPT_USERPWD, $website['gitbbuser'].":".$website['gitbbapppwd']);
       curl_setopt($ch, CURLAUTH_BASIC, 1);
       curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type:application/x-www-form-urlencoded',
          'User-Agent: midleo.CORE Agent',
          )
        );  
      }
      if($website['gittype']=="github"){
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
         'Accept: application/vnd.github.v3+json',
         'Content-Type:application/json',
         'User-Agent: midleo.CORE Agent',
         'Authorization: token '.$website['githtoken']
         )
       );
      }
      $result = curl_exec($ch);  
      if (curl_errno($ch)) {
        return array("err"=>curl_error($ch));
      }
      curl_close($ch);  
      return $result; 
     } else {
       return array("err"=>"wrong GIT configuration!");
     }
   }
   public static function gitDelete($file_place,$file_sha=""){
    global $website;
    if(in_array($website['gittype'],array("github","gitlab","bitbucket"))){
      if($website['gittype']=="gitlab"){
        $data_git = array(
        'branch'=>"master",
        'commit_message'=>'system delete for midleo.CORE from:'.$_SESSION["user"],
        'author_name'=>$_SESSION["user"],
        );
        $data_string_git = json_encode($data_git); 
      }
      if($website['gittype']=="github"){
        $data_git = array(
            'message'=>'system delete for midleo.CORE from:'.$_SESSION["user"],
            'sha'=> $file_sha,
         ); 
         $data_string_git = json_encode($data_git); 
      }
      if($website['gittype']=="bitbucket"){
        $data_git = array(
            'branch'=>"master",
            'message'=>'system delete for midleo.CORE from:'.$_SESSION["user"],
            'files' => $file_place,  
            'author' => $_SESSION["user"]." @MIDLEO <system@midleo.core>",    
          ); 
        $data_string_git = http_build_query($data_git);
      }
      $ch = curl_init();
      if($website['gittype']=="gitlab"){
        curl_setopt($ch, CURLOPT_URL, $website['gitreposurl']."/api/v4/projects/".$website['gitpjid']."/repository/files/".urlencode($file_place));  
       }
       if($website['gittype']=="bitbucket"){
        curl_setopt($ch, CURLOPT_URL, $website['gitbbapi']."/2.0/repositories/".$website['gitbbowner']."/".$website['gitbbrepo']."/src/");  
       }
       if($website['gittype']=="github"){
        curl_setopt($ch, CURLOPT_URL, $website['githubapi']."/repos/".$website['githubowner']."/".$website['githubrepo']."/contents/".urlencode($file_place)); 
       }
       curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
       if($website['gittype']=="gitlab" || $website['gittype']=="github"){
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE"); 
       }
       if($website['gittype']=="bitbucket"){
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
       }
       curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string_git);
     //  curl_setopt($ch, CURLOPT_FAILONERROR, true);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
       if($website['gittype']=="gitlab"){
         curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Accept: application/json',
          'Content-Type: application/json',
          'PRIVATE-TOKEN: '.$website['gittoken']
          )
         );   
       }
       if($website['gittype']=="bitbucket"){
        curl_setopt($ch, CURLOPT_USERPWD, $website['gitbbuser'].":".$website['gitbbapppwd']);
        curl_setopt($ch, CURLAUTH_BASIC, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
           'Content-Type:application/x-www-form-urlencoded',
           'User-Agent: midleo.CORE Agent',
           )
         );  
       }
       if($website['gittype']=="github"){
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
         'Accept: application/vnd.github.v3+json',
         'Content-Type:application/json',
         'User-Agent: midleo.CORE Agent',
         'Authorization: token '.$website['githtoken']
         )
       );
       }
       $result = curl_exec($ch);
       if (curl_errno($ch)) {
        return array("err"=>curl_error($ch));
      }
      curl_close($ch);  
      return $result;
     } else {
       return array("err"=>"wrong GIT configuration!");
     }
   }
   public static function GetCommitID($fileplace){
    global $website;
    if(in_array($website['gittype'],array("github","gitlab","bitbucket"))){
      $ch = curl_init();
      if($website['gittype']=="gitlab"){
        curl_setopt($ch, CURLOPT_URL, $website['gitreposurl']."/api/v4/projects/".$website['gitpjid']."/repository/commits/?path=".urlencode($fileplace));  
       }
       curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
       // if($website['gittype']=="gitlab"){
         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
       // }
     //   curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if($website['gittype']=="gitlab"){
          curl_setopt($ch, CURLOPT_HTTPHEADER, array(
           'Accept: application/json',
           'Content-Type: application/json',
           'PRIVATE-TOKEN: '.$website['gittoken']
           )
          );   
        }
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
         return array("err"=>curl_error($ch));
       }
       curl_close($ch);  
       return $result;
      } else {
        return array("err"=>"wrong GIT configuration!");
      }
   }
   public static function gitTreelist($fileplace){
    global $website;
    if(in_array($website['gittype'],array("github","gitlab","bitbucket"))){
      $ch = curl_init();
      if($website['gittype']=="gitlab"){
        curl_setopt($ch, CURLOPT_URL, $website['gitreposurl']."/api/v4/projects/".$website['gitpjid']."/repository/tree?recursive=true&path=".urlencode($fileplace));  
       }
       if($website['gittype']=="bitbucket"){
        curl_setopt($ch, CURLOPT_URL, $website['gitbbapi']."/2.0/repositories/".$website['gitbbowner']."/".$website['gitbbrepo']."/src/master/".urlencode($fileplace));  
       }
       if($website['gittype']=="github"){
        curl_setopt($ch, CURLOPT_URL, $website['githubapi']."/repos/".$website['githubowner']."/".$website['githubrepo']."/contents/".urlencode($fileplace)); 
       }
       curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
      // if($website['gittype']=="gitlab"){
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
      // }
    //   curl_setopt($ch, CURLOPT_FAILONERROR, true);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
       if($website['gittype']=="gitlab"){
         curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Accept: application/json',
          'Content-Type: application/json',
          'PRIVATE-TOKEN: '.$website['gittoken']
          )
         );   
       }
       if($website['gittype']=="bitbucket"){
        curl_setopt($ch, CURLOPT_USERPWD, $website['gitbbuser'].":".$website['gitbbapppwd']);
        curl_setopt($ch, CURLAUTH_BASIC, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
           'Content-Type:application/x-www-form-urlencoded',
           'User-Agent: midleo.CORE Agent',
           )
         );  
       }
       if($website['gittype']=="github"){
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
         'Accept: application/vnd.github.v3+json',
         'Content-Type:application/json',
         'User-Agent: midleo.CORE Agent',
         'Authorization: token '.$website['githtoken']
         )
       );
       }
       $result = curl_exec($ch);
       if (curl_errno($ch)) {
        return array("err"=>curl_error($ch));
      }
      curl_close($ch);  
      return $result;
     } else {
       return array("err"=>"wrong GIT configuration!");
     }
   }
   public static function gitGetRaw($fileplace){
    global $website;
    if(in_array($website['gittype'],array("github","gitlab","bitbucket"))){
      $ch = curl_init();
      if($website['gittype']=="gitlab"){
        curl_setopt($ch, CURLOPT_URL, $website['gitreposurl']."/api/v4/projects/".$website['gitpjid']."/repository/files/".urlencode($fileplace)."/raw?ref=master");  
       }
       if($website['gittype']=="github"){
        curl_setopt($ch, CURLOPT_URL, $website['githubapi']."/repos/".$website['githubowner']."/".$website['githubrepo']."/contents/".urlencode($fileplace)); 
       }
       if($website['gittype']=="bitbucket"){
        curl_setopt($ch, CURLOPT_URL, $website['gitbbapi']."/2.0/repositories/".$website['gitbbowner']."/".$website['gitbbrepo']."/src/master/".urlencode($fileplace));  
       }
       curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
      // if($website['gittype']=="gitlab" || $website['gittype']=="github"){
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
      // }
    //   curl_setopt($ch, CURLOPT_FAILONERROR, true);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
       if($website['gittype']=="gitlab"){
         curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Accept: application/json',
          'Content-Type: application/json',
          'PRIVATE-TOKEN: '.$website['gittoken']
          )
         );   
       }
       if($website['gittype']=="github"){
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
         'Accept: application/vnd.github.v3.raw',
         'User-Agent: midleo.CORE Agent',
         'Authorization: token '.$website['githtoken']
         )
       );
      }
      if($website['gittype']=="bitbucket"){
        curl_setopt($ch, CURLOPT_USERPWD, $website['gitbbuser'].":".$website['gitbbapppwd']);
        curl_setopt($ch, CURLAUTH_BASIC, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
           'Content-Type:application/x-www-form-urlencoded',
           'User-Agent: midleo.CORE Agent',
           )
         );  
       }
       $result = curl_exec($ch);
       if (curl_errno($ch)) {
        return array("err"=>curl_error($ch));
      }
      curl_close($ch);  
      return $result;
     } else {
       return array("err"=>"wrong GIT configuration!");
     }
   }
}
