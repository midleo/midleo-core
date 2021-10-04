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
                $sql="delete from knowledge_info where cat_latname=?";
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
        require_once 'controller/vendor/autoload.php';
        $phpWord = \PhpOffice\PhpWord\IOFactory::load($source, 'ODText');
        $text = "";
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $ele1) {

            }
        }

    }
    public static function doc2text($source)
    {
        require_once 'controller/vendor/autoload.php';
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
        require_once 'controller/vendor/autoload.php';
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

}

echo Class_docapi::odt2text("/content/data/temp/VasilevCV.odt");

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
