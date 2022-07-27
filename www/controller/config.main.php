<?php
$debug=false;
if($debug){
   ini_set('display_errors', 1);
   ini_set('display_startup_errors', 1);
   error_reporting(E_ALL);
} else {
   ini_set('display_errors','Off');
   ini_set('display_startup_errors', 0);
}
if(!$corebaseurl){
    include "config.db.php";
    include "config.vars.php";
}
$modulelist = array();
if (!empty($website) && !is_array($website)) {$website = json_decode($website, true);}
if (!empty($website['varheader'])) {define("varheader", $website['varheader']);}
if (!empty($website['varfooter'])) {define("varfooter", $website['varfooter']);}
$website['corebase']=$corebaseurl?$corebaseurl:"";
$maindir = dirname(dirname(__FILE__));
foreach (glob(dirname(__FILE__) . "/modules/*/config.php") as $filename) {if (file_exists($filename)) {include $filename;}}
foreach (glob(dirname(dirname(__FILE__)) . "/assets/modules/*/config.php") as $filename) {if (file_exists($filename)) {include $filename;}}
define("DBTYPE", $dbtype);
define("DB_HOST", $dbhost);
define("DB_USER", $dbuser);
define("DB_PASS", $dbpass);
define("DB_NAME", $dbname);
if (!empty($website['datetime'])) {date_default_timezone_set($website['datetime']);}
define("COOKIE_TIME_OUT", 2);
if (!empty($odbc_driver_name)) {define("odbc_driver_name", $odbc_driver_name);}
if (!empty($website['gitreposurl'])) {define("gitreposurl", $website['gitreposurl']);}
if (!empty($website['gitreposuser'])) {define("gitreposuser", $website['gitreposuser']);}
if (!empty($website['gitrepospass'])) {define("gitrepospass", $website['gitrepospass']);}
if (!empty($website['gitbinpath'])) {define("gitbinpath", $website['gitbinpath']);}
if (!empty($website['proxy_host'])) {define("proxy_host", $website['proxy_host']);}
if (!empty($website['proxy_port'])) {define("proxy_port", $website['proxy_port']);}
if (!empty($website['smtp_host'])) {
    ini_set('SMTP', $website['smtp_host']);
    ini_set('smtp_port', $website['smtp_port']);
}
$typeq = array(
    'qlocal' => "Local",
    'qmodel' => "Model",
    'qalias' => "Alias",
    'qremote' => "Remote",
);
$typeobj = array(
    'qm' => "IBM Qmanager",
    'queues' => "Queue",
    'topics' => "Topic",
    'subs' => "Subscription",
    'channels' => "Channel",
);
$typejob = array(
    '1' => "Daily",
    '2' => "Weekly",
    '3' => "Monthly",
    '4' => "Hourly",
);
$typesrv = array(
    'qm' => "IBM Qmanager",
    'fte' => "IBM FTE Agent",
    'ibmiib' => "IBM IIB",
    'tibems' => "Tibco EMS",
    'tomcat' => "Apache Tomcat",
    'ibmwas' => "IBM Websphere AS",
);
$jobstatus = array(
    '0' => array(
        "name" => "New",
        "statcolor" => "secondary",
    ),
    '1' => array(
        "name" => "Completed",
        "statcolor" => "success",
    ),
    '2' => array(
        "name" => "Pending",
        "statcolor" => "info",
    ),
    '3' => array(
        "name" => "Error",
        "statcolor" => "danger",
    ),
);
$ibmmqchlciph = array(
    "ECDHE_ECDSA_3DES_EDE_CBC_SHA256",
    "ECDHE_ECDSA_AES_128_CBC_SHA256",
    "ECDHE_ECDSA_AES_128_GCM_SHA256",
    "ECDHE_ECDSA_AES_256_CBC_SHA384",
    "ECDHE_ECDSA_AES_256_GCM_SHA384",
    "ECDHE_ECDSA_NULL_SHA256",
    "ECDHE_ECDSA_RC4_128_SHA256",
    "ECDHE_RSA_3DES_EDE_CBC_SHA256",
    "ECDHE_RSA_AES_128_CBC_SHA256",
    "ECDHE_RSA_AES_128_GCM_SHA256",
    "ECDHE_RSA_AES_256_CBC_SHA384",
    "ECDHE_RSA_AES_256_GCM_SHA384",
    "ECDHE_RSA_NULL_SHA256",
    "ECDHE_RSA_RC4_128_SHA256",
    "RC4_MD5_EXPORT",
    "FIPS_WITH_3DES_EDE_CBC_SHA",
    "FIPS_WITH_DES_CBC_SHA",
    "TLS_RSA_WITH_3DES_EDE_CBC_SHA",
    "TLS_RSA_WITH_AES_128_CBC_SHA",
    "TLS_RSA_WITH_AES_128_CBC_SHA256",
    "TLS_RSA_WITH_AES_128_GCM_SHA256",
    "TLS_RSA_WITH_AES_256_CBC_SHA",
    "TLS_RSA_WITH_AES_256_CBC_SHA256",
    "TLS_RSA_WITH_AES_256_GCM_SHA384",
    "TLS_RSA_WITH_DES_CBC_SHA",
    "NULL_MD5",
    "NULL_SHA",
    "TLS_RSA_WITH_NULL_SHA256",
    "RC4_MD5_US",
    "TLS_RSA_WITH_RC4_128_SHA256"
);
$accrights=array(
    "1"=>"Operator",
    "2"=>"Manager",
    "3"=>"Project manager",
    "4"=>"Administrator",
    "5"=>"Super User"
);
$monjobtype=array(
    "1"=>"Logs",
    "2"=>"Metrics",
    "3"=>"Network",
    "4"=>"Windows event log",
    "5"=>"Service"
);
$monjobprovider=array(
    "elk"=>"ELK stack",
    "nagios"=>"Nagios",
    "icigna"=>"Icigna",
    "midleomon"=>"Modleo monitoring",
);
$monjobsrv=array(
    "ibmmq"=>"IBM MQ",
);
$monaltype=array(
    "email"=>"Email",
    "hpsm"=>"HPSM Incident",
    "jira"=>"Jira ticket",
    "servicenow"=>"ServiceNow incident",
);
$gracclist = array(
    "tibcoadm"=>"Tibco Admin",
    "tibcoview"=>"Tibco Readonly",
    "pjm"=>"Project Manager",
    "chgm"=>"Change Manager",
    "unixadm"=>"Server Admin",
    "unixview"=>"Server Readonly",
    "appadm"=>"Application Admin",
    "appview"=>"Application Readonly",
    "ibmadm"=>"IBM MQ Admin",
    "ibmview"=>"IBM MQ Readonly",
    "appconfig"=>"Configuration View",
    "environment"=>"Environment View",
);
if (method_exists("Class_monitoring", "getPage") && is_callable(array("Class_monitoring", "getPage"))){
    $gracclist["monview"]="Monitoring View";
    $gracclist["monadm"]="Monitoring Admin";
}
if (method_exists("Class_draw", "getPage") && is_callable(array("Class_draw", "getPage"))){
    $gracclist["designer"]="Diagrams View";
}
if (method_exists("Class_automation", "getPage") && is_callable(array("Class_automation", "getPage"))){
    $gracclist["automation"]="Automation View";
}
$countries=array('AWS'=>'Amazon Cloud','GOOGL'=>'Google Cloud','AZURE'=>'Microsoft Azure','IBM'=>'IBM Cloud','AF'=>'Afghanistan','AX'=>'Aland Islands','AL'=>'Albania','DZ'=>'Algeria','AS'=>'American Samoa','AD'=>'Andorra','AO'=>'Angola','AI'=>'Anguilla','AQ'=>'Antarctica','AG'=>'Antigua And Barbuda','AR'=>'Argentina','AM'=>'Armenia','AW'=>'Aruba','AU'=>'Australia','AT'=>'Austria','AZ'=>'Azerbaijan','BS'=>'Bahamas','BH'=>'Bahrain','BD'=>'Bangladesh','BB'=>'Barbados','BY'=>'Belarus','BE'=>'Belgium','BZ'=>'Belize','BJ'=>'Benin','BM'=>'Bermuda','BT'=>'Bhutan','BO'=>'Bolivia','BA'=>'Bosnia And Herzegovina','BW'=>'Botswana','BV'=>'Bouvet Island','BR'=>'Brazil','IO'=>'British Indian Ocean Territory','BN'=>'Brunei Darussalam','BG'=>'Bulgaria','BF'=>'Burkina Faso','BI'=>'Burundi','KH'=>'Cambodia','CM'=>'Cameroon','CA'=>'Canada','CV'=>'Cape Verde','KY'=>'Cayman Islands','CF'=>'Central African Republic','TD'=>'Chad','CL'=>'Chile','CN'=>'China','CX'=>'Christmas Island','CC'=>'Cocos (Keeling) Islands','CO'=>'Colombia','KM'=>'Comoros','CG'=>'Congo','CD'=>'Congo, Democratic Republic','CK'=>'Cook Islands','CR'=>'Costa Rica','CI'=>'Cote D\'Ivoire','HR'=>'Croatia','CU'=>'Cuba','CY'=>'Cyprus','CZ'=>'Czech Republic','DK'=>'Denmark','DJ'=>'Djibouti','DM'=>'Dominica','DO'=>'Dominican Republic','EC'=>'Ecuador','EG'=>'Egypt','SV'=>'El Salvador','GQ'=>'Equatorial Guinea','ER'=>'Eritrea','EE'=>'Estonia','ET'=>'Ethiopia','FK'=>'Falkland Islands (Malvinas)','FO'=>'Faroe Islands','FJ'=>'Fiji','FI'=>'Finland','FR'=>'France','GF'=>'French Guiana','PF'=>'French Polynesia','TF'=>'French Southern Territories','GA'=>'Gabon','GM'=>'Gambia','GE'=>'Georgia','DE'=>'Germany','GH'=>'Ghana','GI'=>'Gibraltar','GR'=>'Greece','GL'=>'Greenland','GD'=>'Grenada','GP'=>'Guadeloupe','GU'=>'Guam','GT'=>'Guatemala','GG'=>'Guernsey','GN'=>'Guinea','GW'=>'Guinea-Bissau','GY'=>'Guyana','HT'=>'Haiti','HM'=>'Heard Island & Mcdonald Islands','VA'=>'Holy See (Vatican City State)','HN'=>'Honduras','HK'=>'Hong Kong','HU'=>'Hungary','IS'=>'Iceland','IN'=>'India','ID'=>'Indonesia','IR'=>'Iran, Islamic Republic Of','IQ'=>'Iraq','IE'=>'Ireland','IM'=>'Isle Of Man','IL'=>'Israel','IT'=>'Italy','JM'=>'Jamaica','JP'=>'Japan','JE'=>'Jersey','JO'=>'Jordan','KZ'=>'Kazakhstan','KE'=>'Kenya','KI'=>'Kiribati','KR'=>'Korea','KW'=>'Kuwait','KG'=>'Kyrgyzstan','LA'=>'Lao People\'s Democratic Republic','LV'=>'Latvia','LB'=>'Lebanon','LS'=>'Lesotho','LR'=>'Liberia','LY'=>'Libyan Arab Jamahiriya','LI'=>'Liechtenstein','LT'=>'Lithuania','LU'=>'Luxembourg','MO'=>'Macao','MK'=>'Macedonia','MG'=>'Madagascar','MW'=>'Malawi','MY'=>'Malaysia','MV'=>'Maldives','ML'=>'Mali','MT'=>'Malta','MH'=>'Marshall Islands','MQ'=>'Martinique','MR'=>'Mauritania','MU'=>'Mauritius','YT'=>'Mayotte','MX'=>'Mexico','FM'=>'Micronesia, Federated States Of','MD'=>'Moldova','MC'=>'Monaco','MN'=>'Mongolia','ME'=>'Montenegro','MS'=>'Montserrat','MA'=>'Morocco','MZ'=>'Mozambique','MM'=>'Myanmar','NA'=>'Namibia','NR'=>'Nauru','NP'=>'Nepal','NL'=>'Netherlands','AN'=>'Netherlands Antilles','NC'=>'New Caledonia','NZ'=>'New Zealand','NI'=>'Nicaragua','NE'=>'Niger','NG'=>'Nigeria','NU'=>'Niue','NF'=>'Norfolk Island','MP'=>'Northern Mariana Islands','NO'=>'Norway','OM'=>'Oman','PK'=>'Pakistan','PW'=>'Palau','PS'=>'Palestinian Territory, Occupied','PA'=>'Panama','PG'=>'Papua New Guinea','PY'=>'Paraguay','PE'=>'Peru','PH'=>'Philippines','PN'=>'Pitcairn','PL'=>'Poland','PT'=>'Portugal','PR'=>'Puerto Rico','QA'=>'Qatar','RE'=>'Reunion','RO'=>'Romania','RU'=>'Russian Federation','RW'=>'Rwanda','BL'=>'Saint Barthelemy','SH'=>'Saint Helena','KN'=>'Saint Kitts And Nevis','LC'=>'Saint Lucia','MF'=>'Saint Martin','PM'=>'Saint Pierre And Miquelon','VC'=>'Saint Vincent And Grenadines','WS'=>'Samoa','SM'=>'San Marino','ST'=>'Sao Tome And Principe','SA'=>'Saudi Arabia','SN'=>'Senegal','RS'=>'Serbia','SC'=>'Seychelles','SL'=>'Sierra Leone','SG'=>'Singapore','SK'=>'Slovakia','SI'=>'Slovenia','SB'=>'Solomon Islands','SO'=>'Somalia','ZA'=>'South Africa','GS'=>'South Georgia And Sandwich Isl.','ES'=>'Spain','LK'=>'Sri Lanka','SD'=>'Sudan','SR'=>'Suriname','SJ'=>'Svalbard And Jan Mayen','SZ'=>'Swaziland','SE'=>'Sweden','CH'=>'Switzerland','SY'=>'Syrian Arab Republic','TW'=>'Taiwan','TJ'=>'Tajikistan','TZ'=>'Tanzania','TH'=>'Thailand','TL'=>'Timor-Leste','TG'=>'Togo','TK'=>'Tokelau','TO'=>'Tonga','TT'=>'Trinidad And Tobago','TN'=>'Tunisia','TR'=>'Turkey','TM'=>'Turkmenistan','TC'=>'Turks And Caicos Islands','TV'=>'Tuvalu','UG'=>'Uganda','UA'=>'Ukraine','AE'=>'United Arab Emirates','GB'=>'United Kingdom','US'=>'United States','UM'=>'United States Outlying Islands','UY'=>'Uruguay','UZ'=>'Uzbekistan','VU'=>'Vanuatu','VE'=>'Venezuela','VN'=>'Viet Nam','VG'=>'Virgin Islands, British','VI'=>'Virgin Islands, U.S.','WF'=>'Wallis And Futuna','EH'=>'Western Sahara','YE'=>'Yemen','ZM'=>'Zambia','ZW'=>'Zimbabwe');
?>
