<?php
$modulelist["pdf"]["name"] = "Import PDF files and view the content";
class Class_pdf
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
        include "public/modules/css.php";?>
<?php echo '</head><body>'; ?>



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