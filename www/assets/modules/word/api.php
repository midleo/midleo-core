<?php
class Class_wordapi{
    public static function getPage($thisarray){
      global $website;
      global $maindir;
      global $env;
      session_start();
      header('Content-type:application/json;charset=utf-8'); 
      if(!empty($_SESSION['user'])) {
        switch($thisarray["p1"]) {
            case 'getdoclist':  Class_wordapi::readDocs();  break;
            case 'import': Class_wordapi::importDocs(); break;
            case 'readimp': Class_wordapi::readImp(); break;
            default: echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"please use the API correctly."));exit;
                    }
   } else { echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"please use the API correctly."));exit;  }
  }
  public static function readImp(){
    $pdo = pdodb::connect();
    $data = json_decode(file_get_contents("php://input"));
    $sql="select id,fileid,importedon,author,tags from env_worddoc";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $zobj = $stmt->fetchAll();
    $newdata=array();
      foreach($zobj as $val) {
        $data=array();
        $data['id']=$val['id'];
        $data['fileid']=base64_decode($val['fileid']);
        $data['tags']=$val['tags'];
        $data['importedon']=$val['importedon'];
        $data['author']=$val['author'];
        $newdata[]=$data;
      }
    pdodb::disconnect();
    echo json_encode($newdata,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE); exit;
  }
  public static function readDocs(){
    $data = json_decode(file_get_contents("php://input"));
    $tmp["files"]=documentClass::getDirCont($data->dir,$data->ext);
    if($tmp["files"]){
        echo json_encode($tmp["files"],JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        exit;
    } else {
        echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"No ".$data->ext." files in folder:".$data->dir));
        exit;  
    }
  }
  public static function importDocs(){
    $data = json_decode(file_get_contents("php://input"));
    if($data->ext=="docx"){
        if(file_exists($data->filename)){
            $pdo = pdodb::connect();
            $tmp["fileid"]=base64_encode($data->filename);
            $sql="select id from env_worddoc where fileid=?";
            $q = $pdo->prepare($sql); 
            $q->execute(array($tmp["fileid"]));
            if($zobj = $q->rowCount()>0){
               pdodb::disconnect();
               echo json_encode(array('success'=>true,'type'=>"success",'resp'=>$data->filename." already imported"),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
               exit;
            } else {
               $sql="insert into env_worddoc(fileid,author) values(?,?)";
               $q = $pdo->prepare($sql); 
               $q->execute(array($tmp["fileid"],$_SESSION["user"]));
               pdodb::disconnect();
               gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid"=>"system"), "Imported word:".htmlspecialchars($data->filename));
               echo json_encode(array('success'=>true,'type'=>"success",'resp'=>$data->filename." imported"),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
               exit;
            }
        } else {
            echo json_encode(array('error'=>true,'type'=>"error",'errorlog'=>"No such file:".$data->filename));
            exit; 
        }
     
  //      echo Class_wordapi::docx2text("/content/data/temp/santa_claus_en.docx");
     
   }

  }
  public static function docx2text($source)
  {
    require_once 'controller/vendor/autoload.php'; 
      $phpWord =\PhpOffice\PhpWord\IOFactory::load($source);
      $text = '';
      foreach ($phpWord->getSections() as $section) {
          foreach ($section->getElements() as $ele1) {
              $paragraphStyle = $ele1->getParagraphStyle();
              if ($paragraphStyle) {
                  $text .= '<p style="text-align:'. $paragraphStyle->getAlignment() .';text-indent:20px;">';
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
                          $text .= '<img src="'. $imageData .'" style="width:auto;height:auto">';
                      }
                  }
              }
              $text .= '</p>';
          }
      }
      return mb_convert_encoding($text, 'UTF-8', 'GBK');
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



echo docx2html("/content/data/temp/zaqvlenie_E_104_vasko.doc");
*/