<?php
$modulelist["word"]["name"] = "Import Microsoft Word files and view the content";
include_once "inst.php";
class Class_word
{
    public static function getPage($thisarray)
    {
        global $installedapp;
        global $website;
        global $page;
        global $modulelist;
        global $maindir;
        if ($installedapp != "yes") {header("Location: /install");}
        sessionClass::page_protect(base64_encode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
        $err = array();
        $msg = array();
        $pdo = pdodb::connect();
        $data=sessionClass::getSessUserData(); foreach($data as $key=>$val){  ${$key}=$val; } 
        include "public/modules/css.php";
       echo '</head><body>'; ?>

<?php
$source = "test.docx";
if(file_exists($source)){

    $phpWord = \PhpOffice\PhpWord\IOFactory::load($source);
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
?>

<?php include "public/modules/footer.php";
        echo "</div></div>";
        include "public/modules/js.php";
        echo '<script src="/assets/js/tagsinput.min.js" type="text/javascript"></script>';
        if (empty($thisarray['p1'])) {
            echo '<script  type="text/javascript" src="/assets/js/dirPagination.js"></script><script type="text/javascript" src="/assets/js/ng-controller.js"></script>';
        }
        include "public/modules/template_end.php";
        echo '</body></html>';

    }
}