<?php
require_once 'controller/vendor/autoload.php'; 

use PhpOffice\PhpWord\Settings;
define('CLI', (PHP_SAPI == 'cli') ? true : false);
Settings::loadConfig();

Settings::setOutputEscapingEnabled(true);

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