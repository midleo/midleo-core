<?php
$modulelist["mail"]["name"]="Mail controller";
$modulelist["mail"]["system"]=true;
class notiffClass{ 
  public static function body_parts($part = array("demokey"=>"demoval")){
    $bodypart="";
    foreach ($part as $key=>$val) {
     $bodypart.="<tr><td style='padding:5px;border: none; border-collapse: collapse!important; border-spacing: 0!important; -webkit-text-size-adjust: 100%;  color: #666; text-align: right; width: 200px; border-bottom: 1px solid #ccc;'>$key</td><td style='padding:5px;border-bottom: 1px solid #ccc;'>$val</td></tr>";
    }
    return $bodypart;
  }
  public static function mail_template_head($static){
	$header=(defined('varheader')?varheader:"")."<p>".$static."</p><p>&nbsp;</p>";
	return $header;
  }
   public static function mail_template_foot($static){
	$footer=$static.(defined('varfooter')?varfooter:"");	
	return $footer;
  }
  public static function mail_template($header,$body,$footer){
    $htmlbody = $header."<p><strong>More information:</strong></p><table style='border:none;background-color:#fff;table-layout:fixed;-webkit-text-size-adjust:100%;' border='0px' width='100%' cellspacing='0' cellpadding='0' align='center' bgcolor='#fff'><tbody>$body</tbody></table>".$footer;
    return $htmlbody;
  }
  public static function mail_template_sample($body){
    $htmlbody = "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><style type='text/css'>html, body, form, fieldset, h1, h2, h3, h4, h5, h6, p, pre, blockquote, ul, ol, dl, address { margin:0; padding:0;}</style></head>"
      ."<body>".$body."</body></html>";
    return $htmlbody;
  }
}
function send_mailfinal($from,$to,$subject,$header="welcome",$footer="regards",$body=array("info"=>"demo"),$template="full",$fileatt=array("name"=>"link"),$priority=null){
	if($template=="full"){
    $intbody=notiffClass::body_parts($body);
    $header=notiffClass::mail_template_head($header);
    $footer=notiffClass::mail_template_foot($footer);
   }
   $htmlbody=$template=="full"?notiffClass::mail_template($header,$intbody,$footer):notiffClass::mail_template_sample($header,$body["info"],$footer);
   $headers  = 'MIME-Version: 1.0' . "\r\n";
   $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
   if($priority=="yes"){ $headers .= "X-Priority: 1 (Highest)\n"; $headers .= "X-MSMail-Priority: High\n"; $headers .= "Importance: High\n"; } 
   $headers .= 'From: '.$from. "\r\n";
   mail( $to, stripslashes( strip_tags( $subject ) ), stripslashes( $htmlbody ), $headers );
}