<?php
$modulelist["text"]["name"]="Text modification class";
$modulelist["text"]["system"]=true;
class url {
  private $site_path;
  public function __construct($site_path) {
    $this->site_path = $this->removeSlash($site_path);
  }
  private function removeSlash($string) {
    if($string[strlen($string) - 1] == '/')
      $string = rtrim($string, '/');
    return $string;
  }
  public function part($part) {
    $url = str_replace($this->site_path, '',$_SERVER['REQUEST_URI']);
    $url = explode('/', $url);
    if(isset($url[$part]))
      return $url[$part];    
    else
      return false;
  }
}
class textClass{
  public static function replaceMentions($text,$url) { 
    return preg_replace_callback("#\"midmention=([a-z0-9@]+)\"#i", function ($matches) use($url) {
      if($matches[1]){
        textClass::sendUserMention($matches[1],$url);
      }
      }, $text);
  }
  public static function sendUserMention($match,$url){
    $pdo = pdodb::connect();
    $sqlin="SELECT email,fullname FROM ".explode("@", $match)[0]."s where mainuser=?";
    $qin = $pdo->prepare($sqlin);
    $qin->execute(array(explode("@", $match)[1]));
    if ($zobjin = $qin->fetch(PDO::FETCH_ASSOC)) { 
        send_mailfinal(
          $website["system_mail"],
          $zobjin['email'],
          "[MidlEO] - mentions",
          "Hello ".$zobjin['fullname'].",<br>".$_SESSION["userdata"]["usname"]." mentioned you.",
          "<br><br>"."<a href=\"https://".$_SERVER['HTTP_HOST']."\" target=\"_blank\">Control panel</a>",
          $body=array(
            "User"=>$_SESSION["user"],
            "Url"=>"<a href=\"https://".$url."\" target=\"_blank\">".$url."</a>",
          ),
          "full"
        );
    } 
    pdodb::disconnect();
  }
  public static function initials($str) {
    $ret = '';
    foreach (explode(' ', $str) as $word)
        $ret .= strtoupper($word[0]);
    return $ret;
   }
  public static function PageNotFound(){
   echo '
   <div class="error-box">
   <div class="error-body text-center">
       <h1 class="error-title"><i class="mdi mdi-help-circle-outline mdi-24px"></i>&nbsp;404</h1>
       <h3 class="text-uppercase error-subtitle">OOPS!</h3>
       <p class="text mt-4 mb-4">This is awkward.. You are looking for something that does not actually exist.</p>
       <a href="/?" class="btn btn-light waves-effect waves-light mb-5">Back to home</a> </div>
</div>'
; 
  }
  public static function stage_array($source,$words,$benv=""){ 
    return (preg_replace_callback("'{(.*?)}'si", function($match) use ($words,$benv) {  
      if(isset($words[$match[0]]["val"])){ 
        if(is_array($words[$match[0]]["val"])){ 
           return ($words[$match[0]]["val"][$benv]); 
        } else {
        return ($words[$match[0]]["val"]); 
        }
      } else{
        return($match[0]); 
      }
    },  $source));
  }
  public static function ago($time) {
    $currentTime = date('Y-m-d H:i:s');
    $toTime = strtotime($currentTime);
    $fromTime = strtotime($time);
    $timeDiff = floor(abs($toTime - $fromTime) / 60);
    if ($timeDiff < 2) {
      $timeDiff = "Just now";
    } elseif ($timeDiff > 2 && $timeDiff < 60) {
      $timeDiff = floor(abs($timeDiff)) . " minutes ago";
    } elseif ($timeDiff > 60 && $timeDiff < 120) {
      $timeDiff = floor(abs($timeDiff / 60)) . " hour ago";
    } elseif ($timeDiff <= 1440) {
      $timeDiff = floor(abs($timeDiff / 60)) . " hours ago";
    } elseif ($timeDiff > 1440 && $timeDiff < 2880) {
      $timeDiff = floor(abs($timeDiff / 1440)) . " day ago";
    } elseif ($timeDiff > 2880) {
      $timeDiff = floor(abs($timeDiff / 1440)) . " days ago";
    }
    return $timeDiff;
  }
  public static function getTheDay($timestamp)
  {
    $curr_date=strtotime(date("Y-m-d H:i:s"));
    $the_date=strtotime($timestamp);
    $diff=floor(($curr_date-$the_date)/(60*60*24));
    if (date('Y.m.d',strtotime($timestamp)) === date('Y.m.d')) { return "Today"; }
    elseif (date('Y.m.d',strtotime($timestamp)) === date('Y.m.d', strtotime('-1 day'))) { return "Yesterday"; }
    else { return $diff." Days ago";}
  }
  public static function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
  }
  public static function word_limiter($input, $maxWords, $maxChars){
    $words = preg_split('/\s+/', $input);
    $words = array_slice($words, 0, $maxWords);
    $words = array_reverse($words);
    $chars = 0;
    $truncated = array();
    while(count($words) > 0)
    {
      $fragment = trim(array_pop($words));
      $chars += strlen($fragment);
      if($chars > $maxChars) break;
      $truncated[] = $fragment;
    }
    $result = implode(' ', $truncated);
    return $result . ($input == $result ? '' : '...');
  }
  public static function getRandomStr($length = 8){
    $seed = str_split('abcdefghijklmnopqrstuvwxyz'
                      .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                      .'0123456789');
    shuffle($seed);
    $rand = '';
    foreach (array_rand($seed, $length) as $k) $rand.=$seed[$k];
    return $rand;
  }
  public static function cyr2lat($result){
    $cyr = array("а","А","б","Б","в","В","г","Г","д","Д","е","Е","ж","Ж","з","З","и","И","й","Й","к","К","л","Л","м","М","н","Н","о","О","п","П","р","Р","с","С","т","Т","у","У","ф","Ф","х","Х","ц","Ц","ч","Ч","ш","Ш","щ","Щ","ь","ъ","Ъ","ю","Ю","я","Я");
    $lat = array("a","A","b","B","v","V","g","G","d","D","e","E","j","J","z","Z","i","I","i","I","k","K","l","L","m","M","n","N","o","O","p","P","r","R","s","S","t","T","u","U","f","F","h","H","c","C","ch","Ch","sh","Sh","sht","Sht","u","u","U","iu","Iu","q","Q");
    $bbc_num = count($cyr);
    $loop = 0;
    while($loop < $bbc_num)
    {
      $result = str_replace($cyr[$loop], $lat[$loop], $result);
      $loop++;
    }
    return $result;
  }
  public static function replace359($number){
    $number=str_replace("+","",$number);
    $first=substr($number,0,3);
    if ($first=="359") { $first="0"; } else { $first=$first; };
    $lenght=strlen($number);
    $number=$first.substr($number,lenght+3);
    return $number;
  }
  public static function strreplace($result,$str="-"){
    $result= preg_replace('/[\p{Z}\s]{2,}/u', ' ', $result);
    $result= str_replace(" ", "_", $result);
    $result= preg_replace("/[^A-Za-z0-9_]/","",$result);
    $result= str_replace("_", $str, $result);
    $result= strtolower($result);
    return $result;
  }
  public static function change_config($filePath, $newSettings) {
    $old = get_defined_vars();
    include($filePath);
    $new = get_defined_vars();
    $fileSettings = array_diff($new, $old);
    $fileSettings = array_merge($fileSettings, $newSettings);
    $newFileStr = "<?php\n\n";
    foreach ($fileSettings as $name => $val) {
      $newFileStr .= "\${$name} = " . var_export($val, true) . ";\n";
    }
    file_put_contents($filePath, $newFileStr);
  }
  public static function getL2Keys($array)
  {
    $result = array();
    foreach($array as $sub) {
      $result = array_merge($result, $sub);
    }        
    return array_unique(array_values($result));
  }
  public static function in_array_r($string, $array, $strict = false) {
    foreach ($array as $item) {
        if (($strict ? $item === $string : $item == $string) || (is_array($item) && textClass::in_array_r($string, $item, $strict))) {
            return true;
        }
    }
    return false;
  }
  public static function compare_multi_Arrays($array1, $array2){
    $result = array("more"=>array(),"less"=>array(),"diff"=>array());
    foreach($array1 as $k => $v) {
      if(is_array($v) && isset($array2[$k]) && is_array($array2[$k])){
        $sub_result = textClass::compare_multi_Arrays($v, $array2[$k]);
        //merge results
        foreach(array_keys($sub_result) as $key){
          if(!empty($sub_result[$key])){
            $result[$key] = array_merge_recursive($result[$key],array($k => $sub_result[$key]));
          }
        }
      }else{
        if(isset($array2[$k])){
          if($v !== $array2[$k]){
            $result["diff"][$k] = array("from"=>$v,"to"=>$array2[$k]);
          }
        }else{
          $result["more"][$k] = $v;
        }
      }
    }
    foreach($array2 as $k => $v) {
        if(!isset($array1[$k])){
            $result["less"][$k] = $v;
        }
    }
    return $result;
  }
}
class urlchangeClass{
  public static function EncodeURL($url)
  {
    $new = strtolower(str_replace(' ','_',$url));
    return($new);
  }
  public static function DecodeURL($url)
  {
    $new = ucwords(str_replace('_',' ',$url));
    return($new);
  }
}