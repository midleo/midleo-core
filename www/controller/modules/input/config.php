<?php
$modulelist["input"]["name"]="Form input module";
$modulelist["input"]["system"]=true;
class inputClass{
  public static function isEmail($email){
    return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE;
  }
  public static function isUserID($username){
    if (preg_match('/^[a-z\d_]{5,20}$/i', $username)) {
      return true;
    } else {
      return false;
    }
  }  
  public static function isURL($url){
    if (preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $url)) {
      return true;
    } else {
      return false;
    }
  }
  public static function checkPwd($x,$y)
  {
    if(empty($x) || empty($y) ) { return false; }
    if (strlen($x) < 4 || strlen($y) < 4) { return false; }
    if (strcmp($x,$y) != 0) {
      return false;
    }
    return true;
  }
  public static function GenPwd($length = 7)
  {
    $password = "";
    $possible = "0123456789bcdfghjkmnpqrstvwxyz";
    $i = 0;
    while ($i < $length) {
      $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
      if (!strstr($password, $char)) {
        $password .= $char;
        $i++;
      }
    }
    return $password;
  }
  public static function GenKey($length = 7)
  {
    $password = "";
    $possible = "0123456789abcdefghijkmnopqrstuvwxyz";  
    $i = 0;
    while ($i < $length) {
      $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
      if (!strstr($password, $char)) {
        $password .= $char;
        $i++;
      }
    }
    return $password;
  }
  public static function PwdHash($pwd)
  {
    $options = array('cost' => 11);
    return password_hash($pwd, PASSWORD_BCRYPT, $options);
  }  
  public static function filter($data) {
    $data = trim(htmlentities(strip_tags($data)));
 //   if (get_magic_quotes_gpc())
 //     $data = stripslashes($data);  
    return $data;
  }
  public static function encode($num) {
    $scrambled = (240049382*$num + 37043083) % 308915753;
    return base_convert($scrambled, 10, 26);
  }
  public static function ChopStr($str, $len){
    if (strlen($str) < $len)
      return $str;
    $str = substr($str,0,$len);
    if ($spc_pos = strrpos($str," "))
      $str = substr($str,0,$spc_pos);
    return $str . "...";
  }
}