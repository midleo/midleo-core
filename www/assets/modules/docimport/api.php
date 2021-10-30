<?php
class Class_docapi
{
    public static function getPage($thisarray)
    {
        global $website;
        global $maindir;
        global $env;
        session_start();
        header('Content-type:application/json;charset=utf-8');
        if (!empty($_SESSION['user'])) {
            switch ($thisarray["p1"]) {
                case 'getdoclist':Class_docapi::readDocs();
                    break;
                case 'import':Class_docapi::importDocs();
                    break;
                case 'export':Class_docapi::exportDocs();
                    break;
                case 'deldoc':Class_docapi::delDoc();
                    break;
                case 'readimp':Class_docapi::readImp();
                    break;
                default:echo json_encode(array('error' => true, 'type' => "error", 'errorlog' => "please use the API correctly."));exit;
            }
        } else {echo json_encode(array('error' => true, 'type' => "error", 'errorlog' => "please use the API correctly."));exit;}
    }
    public static function readImp()
    {
        $pdo = pdodb::connect();
        $data = json_decode(file_get_contents("php://input"));
        $sql = "select id,fileid,importedon,author,tags from env_docimport";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $zobj = $stmt->fetchAll();
        $newdata = array();
        foreach ($zobj as $val) {
            $data = array();
            $data['id'] = $val['id'];
            $data['fileid'] = base64_decode($val['fileid']);
            $data['fileorigid'] = $val['fileid'];
            $data['tags'] = $val['tags'];
            $data['importedon'] = $val['importedon'];
            $data['author'] = $val['author'];
            $newdata[] = $data;
        }
        pdodb::disconnect();
        echo json_encode($newdata, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);exit;
    }
    public static function readDocs()
    {
        $data = json_decode(file_get_contents("php://input"));
        $tmp["files"] = documentClass::getDirCont($data->dir, $data->ext);
        if ($tmp["files"]) {
            echo json_encode($tmp["files"], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;
        } else {
            echo json_encode(array('error' => true, 'type' => "error", 'errorlog' => "No " . $data->ext . " files in folder:" . $data->dir));
            exit;
        }
    }
    public static function delDoc()
    {
        $data = json_decode(file_get_contents("php://input"));
        if ($data->thisid) {
            $pdo = pdodb::connect();
            $sql = "delete from env_docimport where id=?";
            $q = $pdo->prepare($sql);
            if ($q->execute(array($data->thisid))) {
                $latname = textClass::cyr2lat(base64_decode($data->thisname));
                $latname = textClass::strreplace($latname);
                $sql = "delete from knowledge_info where cat_latname=?";
                $q = $pdo->prepare($sql);
                $q->execute(array($latname));
                gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => "system"), "Deleted document:" . base64_decode($data->thisname));
                echo json_encode(array('success' => true, 'type' => "success", 'resp' => base64_decode($data->thisname) . " deleted"), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(array('success' => true, 'type' => "success", 'resp' => base64_decode($data->thisname) . " not found"), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
            pdodb::disconnect();
        }
        exit;
    }
    public static function importPDF()
    {
        global $website;
        require_once $website['corebase'].'controller/vendor/autoload.php';
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile('public/temp/test.pdf');
        $text = $pdf->getText();
        echo $text;
        $details = $pdf->getDetails();

        foreach ($details as $property => $value) {
            if (is_array($value)) {
                $value = implode(', ', $value);
            }
            echo $property . ' => ' . $value . "\n";
        }
        /*  $mpdf = new \Mpdf\Mpdf([
    'format' => 'A4',
    'margin_left' => 5,
    'margin_right' => 5,
    'margin_top' => 5,
    'margin_bottom' => 10,
    'margin_header' => 5,
    'margin_footer' => 5,
    ]);
    $mpdf->SetProtection(array('print'));
    $mpdf->SetAuthor("MidlEO");
    $mpdf->showWatermarkText = false;
    $mpdf->SetDisplayMode('fullpage');
    $mpdf->SetTitle("Midleo core");
    $html="test";
    $pagecount = $mpdf->SetSourceFile('public/temp/test.pdf');
    $tplId = $mpdf->importPage($pagecount);
    $mpdf->useTemplate($tplId);
    $mpdf->WriteHTML($html);
    $mpdf->Output();
     */
    }
    public static function importDocs()
    {
        $data = json_decode(file_get_contents("php://input"));
        if ($data->ext == "docx" || $data->ext == "doc") {
            if (file_exists($data->filename)) {
                $pdo = pdodb::connect();
                $tmp["fileid"] = base64_encode($data->filename);
                $sql = "select id from env_docimport where fileid=?";
                $q = $pdo->prepare($sql);
                $q->execute(array($tmp["fileid"]));
                if ($zobj = $q->rowCount() > 0) {
                    pdodb::disconnect();
                    echo json_encode(array('success' => true, 'type' => "success", 'resp' => $data->filename . " already imported"), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    exit;
                } else {
                    $sql = "insert into env_docimport(fileid,author) values(?,?)";
                    $q = $pdo->prepare($sql);
                    $q->execute(array($tmp["fileid"], $_SESSION["user"]));
                    $latname = textClass::cyr2lat($data->filename);
                    $latname = textClass::strreplace($latname);
                    if ($data->ext == "docx") {
                        $content = Class_docapi::docx2text($data->filename);
                    } else {
                        $content = Class_docapi::doc2text($data->filename);
                    }
                    $sql = "INSERT INTO knowledge_info (cat_latname,category,cat_name,public,tags,author, cattext,accgroups) VALUES (?,?,?,?,?,?,?,?)";
                    $q = $pdo->prepare($sql);
                    $q->execute(array($latname, "documentation", $data->filename, "0", "documentation," . $data->ext, $_SESSION["user"], $content, !empty($_SESSION["userdata"]["ugrarr"]) ? json_encode($_SESSION["userdata"]["ugrarr"], true) : ""));
                    gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => "system"), "Imported word:" . htmlspecialchars($data->filename));
                    echo json_encode(array('success' => true, 'type' => "success", 'resp' => $data->filename . " imported"), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    pdodb::disconnect();
                    exit;
                }
            } else {
                echo json_encode(array('error' => true, 'type' => "error", 'errorlog' => "No such file:" . $data->filename));
                exit;
            }

        }

    }
    public static function odt2text($source)
    {
        global $website;
        require_once $website['corebase'].'controller/vendor/autoload.php';
        $phpWord = \PhpOffice\PhpWord\IOFactory::load($source, 'ODText');
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
        ob_start();
        $objWriter->save("php://output");
        $document = ob_get_clean();
        return $document;
    }
    public static function doc2text($source)
    {
        global $website;
        require_once $website['corebase'].'controller/vendor/autoload.php';
        $phpWord = \PhpOffice\PhpWord\IOFactory::load($source, 'MsDoc');
        $text = '';
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $ele1) {
                if (method_exists($ele1, 'getParagraphStyle')) {$paragraphStyle = $ele1->getParagraphStyle();}
                if ($paragraphStyle) {
                    $text .= '<p style="text-align:' . $paragraphStyle->getAlignment() . ';text-indent:20px;">';
                } else {
                    $text .= '<p>';
                }
                if ($ele1 instanceof \PhpOffice\PhpWord\Element\Text) {
                    $style = $ele1->getFontStyle();
                    $fontFamily = mb_convert_encoding($style->getName(), 'GBK', 'UTF-8');
                    $fontSize = $style->getSize();
                    $isBold = $style->isBold();
                    $styleString = '';
                    $fontFamily && $styleString .= "font-family:{$fontFamily};";
                    $fontSize && $styleString .= "font-size:{$fontSize}px;";
                    $isBold && $styleString .= "font-weight:bold;";
                    $text .= sprintf('<span style="%s">%s</span>',
                        $styleString,
                        mb_convert_encoding($ele1->getText(), 'GBK', 'UTF-8')
                    );
                } elseif ($ele1 instanceof \PhpOffice\PhpWord\Element\Image) {
                    $imageData = $ele1->getImageStringData(true);
                    $imageData = 'data:' . $ele1->getImageType() . ';base64,' . $imageData;
                    $text .= '<img src="' . $imageData . '" style="width:auto;height:auto">';
                } elseif ($ele1 instanceof \PhpOffice\PhpWord\Element\TextBreak) {
                    $text .= " \n";
                }
                $text .= '</p>';
            }
        }
        return mb_convert_encoding($text, 'UTF-8', 'GBK');
    }
    public static function docx2text($source)
    {
        global $website;
        require_once $website['corebase'].'controller/vendor/autoload.php';
        $phpWord = \PhpOffice\PhpWord\IOFactory::load($source);
        $text = '';
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $ele1) {
                if (method_exists($ele1, 'getParagraphStyle')) {$paragraphStyle = $ele1->getParagraphStyle();}
                if ($paragraphStyle) {
                    $text .= '<p style="text-align:' . $paragraphStyle->getAlignment() . ';text-indent:20px;">';
                } else {
                    $text .= '<p>';
                }
                if ($ele1 instanceof \PhpOffice\PhpWord\Element\TextRun) {
                    foreach ($ele1->getElements() as $ele2) {
                        if ($ele2 instanceof \PhpOffice\PhpWord\Element\Text) {
                            $style = $ele2->getFontStyle();
                            $fontFamily = mb_convert_encoding($style->getName(), 'GBK', 'UTF-8');
                            $fontSize = $style->getSize();
                            $isBold = $style->isBold();
                            $styleString = '';
                            $fontFamily && $styleString .= "font-family:{$fontFamily};";
                            $fontSize && $styleString .= "font-size:{$fontSize}px;";
                            $isBold && $styleString .= "font-weight:bold;";
                            $text .= sprintf('<span style="%s">%s</span>',
                                $styleString,
                                mb_convert_encoding($ele2->getText(), 'GBK', 'UTF-8')
                            );
                        } elseif ($ele2 instanceof \PhpOffice\PhpWord\Element\Image) {
                            $imageData = $ele2->getImageStringData(true);
                            $imageData = 'data:' . $ele2->getImageType() . ';base64,' . $imageData;
                            $text .= '<img src="' . $imageData . '" style="width:auto;height:auto">';
                        }
                    }
                }
                $text .= '</p>';
            }
        }
        return mb_convert_encoding($text, 'UTF-8', 'GBK');
    }
    public static function exportDocs()
    {
        global $website;
        if (isset($_POST['pdfexport'])) {
            require_once $website['corebase'].'controller/vendor/autoload.php';
            if (!empty($_POST['thistype'])) {
                $pdo = pdodb::connect();
                if($_POST['thistype']=="kbase"){
                    $sql="SELECT cat_name as thisname,cattext as thistext,author FROM knowledge_info where cat_latname=? and id=?";
                } elseif($_POST['thistype']=="diagrams"){
                    $sql="SELECT desname as thisname,imgdata as thistext FROM config_diagrams where desid=? and id=?";
                } else {
                    exit;
                }
                $q = $pdo->prepare($sql);
                $q->execute(array(htmlspecialchars($_POST['thisid']),htmlspecialchars($_POST['thisuid'])));
                if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
                    $mpdf = new \Mpdf\Mpdf([
                        'format' => 'A4',
                        'margin_left' => 5,
                        'margin_right' => 5,
                        'margin_top' => 5,
                        'margin_bottom' => 10,
                        'margin_header' => 5,
                        'margin_footer' => 5,
                      ]);
                      $mpdf->SetProtection(array('print'));  
                      $mpdf->SetAuthor("midleo.CORE");
                      $mpdf->showWatermarkText = false;
                      $mpdf->SetDisplayMode('fullpage');  
                      $mpdf->SetTitle(htmlspecialchars($_POST['thisid']));
                      $html='<html><head><meta charset="utf-8">
                      <style type="text/css">
                      body{font-weight:400;font-size:13px;line-height:1.42857143}
                      a,a:hover,a:focus{color:#00AFFF;}
                      .c-gray{color:#575757!important}
                      p{margin:0 0 9px}
                      .col-xs-6{width:45%;float:left;padding-right:15px;padding-left:15px;position:relative}
                      h4,.h4{font-size:17px}
                      h4,.h4,h5,.h5,h6,.h6{margin-top:9px;margin-bottom:9px}
                      h1,h2,h3,h4,h5,h6,.h1,.h2,.h3,.h4,.h5,.h6{font-family:inherit;font-weight:500;line-height:1.1;color:#000}
                      .text-muted{color:#777}
                      address{margin-bottom:18px;font-style:normal;line-height:1.42857143}
                      .col-xs-3{width:20%;float:left;padding-left:15px;padding-right:15px}
                      .col-xs-4{width:30%;float:left;padding-left:15px;padding-right:15px}
                      .brd-2{border-radius:2px}
                      .p-15{padding:15px!important}
                      .p-0{padding:0!important}
                      .m-b-25{margin-bottom:25px!important}
                      .m-t-25{margin-top:25px!important}
                      .c-white{color:#fff!important}
                      .f-300{font-weight:300!important}
                      .m-0{margin:0!important}
                      .m-b-5{margin-bottom:5px!important}
                      .row{margin-left:-15px;margin-right:-15px}
                      .text-left{text-align:left}
                      .text-right{text-align:right}
                      .list-inline{padding-left:0;list-style:none;margin-left:-5px}
                      ul,ol{margin-top:0;margin-bottom:9px}
                      .list-inline > li{display:inline-block;padding-left:5px;padding-right:5px}
                      .f-400{font-weight:400!important}
                      h4,.h4{font-size:17px}
                      h4,.h4,h5,.h5,h6,.h6{margin-top:9px;margin-bottom:9px;font-size:20px;}
                      .text-center{text-align:center}
                      table{border-collapse:collapse;border-spacing:0;width:100%}
                      .i-table td.highlight{font-size:14px;font-weight:500}
                      .i-table td{padding:5px;}
                      .i-table td.bordered{border-bottom:1px solid #e6e6e6;}
                      .i-table .highlight{background-color:#eee;border-bottom:1px solid #e6e6e6;padding:5px;}
                      .i-logo{width:40px;}
                      </style></head>
                      <body>';
                    $html.="<h4>".$zobj['thisname']."</h4><br>";
                    if($_POST['thistype']=="kbase"){
                        $html.=documentClass::replaceDiagramsWithImage($zobj['thistext']);
                    } elseif($_POST['thistype']=="diagrams"){
                        $html.="<img style='max-width:100%;' src='".$zobj['thistext']."'>";  
                    } else {
                        exit;
                    }
                    $html.='</body></html>'; 
                    $mpdf->WriteHTML($html);
                    $mpdf->Output('midleo.CORE_'.htmlspecialchars($_POST['thisid']).'.pdf','D');
                }
                pdodb::disconnect();
                exit;
            } else { echo json_encode(array('error' => true, 'type' => "error", 'errorlog' => "please use the API correctly."));exit; }
        } else { echo json_encode(array('error' => true, 'type' => "error", 'errorlog' => "please use the API correctly."));exit; }
    }

}

/*
require_once 'controller/vendor/autoload.php';

#use PhpOffice\PhpWord\Settings;
#define('CLI', (PHP_SAPI == 'cli') ? true : false);
#Settings::loadConfig();

#Settings::setOutputEscapingEnabled(true);

if(isset($_POST["importall"])){
$tmp["ext"]=$_POST["fileext"];
$tmp["files"]=documentClass::getDirCont($_POST["thisdir"],$tmp["ext"]);

if($tmp["files"]){
foreach($tmp["files"] as $key=>$val){
if($val && file_exists($val)){

$phpWord = \PhpOffice\PhpWord\IOFactory::load($val);
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
ob_start();
$objWriter->save("php://output");
$document = ob_get_clean();
if($document){
preg_match("/<body[^>]*>(.*?)<\/body>/is", $document, $matches);
$string = trim(preg_replace('/\s+/', ' ', $matches[1]));
echo($string);
}

}
}
}
}

 */
