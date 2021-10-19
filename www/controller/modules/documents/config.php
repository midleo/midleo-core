<?php
$modulelist["documents"]["name"]="Documents module";
$modulelist["documents"]["system"]=true;
class documentClass{
  public static function rRD($dir){
    $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
    $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);
    foreach ( $ri as $file ) {
      $file->isDir() ?  rmdir($file) : unlink($file);
    }
   return true;
  }
  public static function getDirCont($dir, $filter = '', &$results = array()) {
    $files = scandir($dir);
    foreach($files as $key => $value){
        $path = realpath($dir.DIRECTORY_SEPARATOR.$value); 
        if(!is_dir($path)) {
            if(empty($filter) || preg_match('/\.'.$filter.'$/', $path)) $results[] = $path;
        } elseif($value != "." && $value != "..") {
          documentClass::getDirCont($path, $filter, $results);
        }
    }
    return $results;
 }
 public static function rCopy($src,$dst) { 
  $dir = opendir($src); 
  @mkdir($dst); 
  while(false !== ( $file = readdir($dir)) ) { 
      if (( $file != '.' ) && ( $file != '..' )) { 
          if ( is_dir($src . '/' . $file) ) { 
            documentClass::rCopy($src . '/' . $file,$dst . '/' . $file); 
          } 
          else { 
             if(file_exists($dst . '/' . $file)){
              unlink($dst . '/' . $file);
             }
             copy($src . '/' . $file,$dst . '/' . $file); 
          } 
      } 
  } 
  closedir($dir); 
 } 
 public static function houseKeep($dir,$filestrigger,$fileext){
  $files = glob($dir . "/*.".$fileext);
  if ($files){
   $fnum = count($files);
  }
  if($fnum>($filestrigger+1)){
  $files = scandir($dir, SCANDIR_SORT_DESCENDING);
  $leave_files = array($files[0], $files[1], $files[2], $files[3]);
  foreach( glob("$dir/*.".$fileext) as $file ) {
      if( !in_array(basename($file), $leave_files) )
          unlink($file);
    }
  } 
 }
 public static function cacheFile($time,$file,$src){
    if(file_exists($file)){
      if($time>=date ("Y-m-d H:i:s", filemtime($file))){
        $chw = curl_init();  
        curl_setopt($chw,CURLOPT_URL,$src);
        curl_setopt($chw,CURLOPT_RETURNTRANSFER,true);
        $json_outputw=curl_exec($chw);
        unlink($file);
        $myfile = fopen($file, "w");
        fwrite($myfile, $json_outputw);
        fclose($myfile);
      }
    } else {
      $chw = curl_init();  
      curl_setopt($chw,CURLOPT_URL,$src);
      curl_setopt($chw,CURLOPT_RETURNTRANSFER,true);
      $json_outputw=curl_exec($chw);
      $myfile = fopen($file, "w");
      fwrite($myfile, $json_outputw);
      fclose($myfile);
    }
    return $file;
  }
  public static function FilesArange($file)
  {
    $file_ary = array();
    $file_count = count($file['name']);
    $file_key = array_keys($file);
    for($i=0;$i<$file_count;$i++)
        {
          foreach($file_key as $val)
                  {
                    $file_ary[$i][$val] = $file[$val][$i];
                  }
        }
    return $file_ary;
  }
  public static function uploaddocument($theimputname,$folder){
    $uploadOk = 1;
    $target_file = $folder.basename($theimputname["name"]);
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    if (file_exists($target_file)) {
     // unlink($target_file);
      $msg='File <b>'.$theimputname["name"].'</b> exists.';
      $uploadOk = 0;
    }
    if($imageFileType == "exe" || $imageFileType == "php" || $imageFileType == "sh" ) {
      $msg='You cannot upload exe, sh or php.';
      $uploadOk = 0;
    }
    if ($uploadOk != 0) {
      if (move_uploaded_file($theimputname["tmp_name"], $target_file)) {
        $msg='The file <b>'.$theimputname["name"].'</b> was uploaded.';
      } else {
        $msg='Error.Try again!';
      }
    };
    return $msg;
  }
  public static function encryptIt( $payload ) {
    $key = 'Jlksd88jk0sd43lkassdkmkksd9lkasd';
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($payload, 'aes-256-cbc', $key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
  }
  public static function decryptIt( $garble ) {
    $key = 'Jlksd88jk0sd43lkassdkmkksd9lkasd';
    list($encrypted_data, $iv) = explode('::', base64_decode($garble), 2);
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);
  }
  public static function getDiagramDataById($diagramId) {
    $pdo = pdodb::connect(); 
    $sqlin="SELECT imgdata FROM config_diagrams where desid=?";
    $qin = $pdo->prepare($sqlin);
    $qin->execute(array($diagramId));
    if ($results = $qin->fetch(PDO::FETCH_ASSOC)) {
       $imageData = $results['imgdata'];
       if (!empty($imageData)) {
           return $imageData;
       }
    }
    return null;
    pdodb::disconnect();
  }
  public static  function replaceDiagramsWithImage($text) {
   return preg_replace_callback("#\[diagram=([a-z0-9]+)\]#i", function ($matches) {
     $imageData = documentClass::getDiagramDataById($matches[1]);
     if ($imageData === null) {
        return null;
     }
     return "<img style='max-width:100%;' src='{$imageData}'>";
   }, $text);
 }
}
