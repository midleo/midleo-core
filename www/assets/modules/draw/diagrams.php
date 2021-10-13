<?php
class Class_diagrams
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
        $tmp["likesearch"]="";
        $data=sessionClass::getSessUserData(); foreach($data as $key=>$val){  ${$key}=$val; } 
        if(!empty($ugroups)){
            foreach($ugroups as $keyin=>$valin){
                $tmp["likesearch"].=" or accgroups like '%".$keyin."%'";
            }
        }
        $sactive=" author='".$_SESSION["user"]."'";
        $sactive.=" or public='1'".$tmp["likesearch"];
        $sactcat=" cattype='1'".$tmp["likesearch"];
        $sql="select count(id) from config_diagrams".(!empty($sactive)?" where".$sactive:"");
        $q = $pdo->prepare($sql);
        $q->execute();
        $blognum=$q->fetchColumn();
        $forumcase=!empty($thisarray["p1"])?$thisarray["p1"]:"";
        $keyws=!empty($thisarray["p2"])?$thisarray["p2"]:"";
        $page = (isset($thisarray["p1"]) && $thisarray["p1"]=="pn" && !empty($keyws))? $keyws:1;
        $prev = ($page - 1);
        $next = ($page + 1);
        $max_results = 4;
        $from = abs(($page * $max_results) - $max_results);
        $to = abs(($page * $max_results));
        if ($forumcase=="v") {
            $sql="SELECT category,desname FROM config_diagrams where desid=?";
            $q = $pdo->prepare($sql);
            $q->execute(array($keyws));
            if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
              $blogtitle=$zobj['desname']; $blogcategory=$zobj['category']; 
            } else { $noresult=1;}
          }
          if($forumcase=="category"){ if(!empty($keyws)){
            $sql="SELECT catname, category FROM knowledge_categories where category=?";
            $q = $pdo->prepare($sql);
            $q->execute(array($keyws));
            if($zobj = $q->fetch(PDO::FETCH_ASSOC)){
                $blogcatname=$zobj['catname']; $blogcat=$zobj['category'];
            } else { $blogcatname="wrong category!"; }
          } else { $blogcatname="Category is empty!"; }}
        include "public/modules/css.php";   
        echo '</head><body class="fix-header card-no-border"><div id="main-wrapper">';
        $breadcrumb["text"]="Diagrams"; 
     $brarr=array();
      array_push($brarr,array(
        "title"=>"Knowledge Base",
        "link"=>"/info",
        "midicon"=>"kn-b",
        "active"=>($page=="cpinfo")?"active":"",
      ));
      array_push($brarr, array(
        "title" => "Import documents",
        "link" => "/docimport",
        "midicon" => "deploy",
        "active" => ($page == "docimport") ? "active" : "",
    ));
   if (sessionClass::checkAcc($acclist, "designer")) {
    array_push($brarr,array(
      "title"=>"View/Edit diagrams",
      "link"=>"/draw",
      "midicon"=>"diagram",
      "active"=>($page=="draw")?"active":"",
    ));
  }
    if (sessionClass::checkAcc($acclist, "odfiles")) {
      array_push($brarr,array(
          "title"=>"View/Map OneDrive files",
        "link"=>"/onedrive",
        "midicon"=>"onedrive",
        "active"=>($page=="onedrive")?"active":"",
      ));
    }
    if (sessionClass::checkAcc($acclist, "dbfiles")) {
      array_push($brarr,array(
          "title"=>"View/Map Dropbox files",
        "link"=>"/dropbox",
        "midicon"=>"dropbox",
        "active"=>($page=="dropbox")?"active":"",
      ));
    }
        include "public/modules/headcontentdiagram.php";   
        echo '<div class="page-wrapper">'; ?>
              <div class="container-fluid">
      
                  <?php     echo '<div class="row pt-3"><div class="col-lg-2">';
                  include "public/modules/sidebardiagrams.php";
                  echo '</div><div class="col-md-8">';


        if ($forumcase=="v") { 
            $sql="SELECT id,desname,desdate,imgdata,author,tags FROM config_diagrams where desid=?".(!empty($sactive)?" and (".$sactive.")":""); 
            $q = $pdo->prepare($sql);
            $q->execute(array($keyws));
            if($zobj = $q->fetch(PDO::FETCH_ASSOC)){ ?>
                      <div class="card" style="box-shadow:none;">
                          <div class="card-body">
                              <h4 class="card-title"><?php echo $zobj['desname'];?></h4>
                              <h6 class="card-subtitle" style="margin-bottom: 0px;">Last update
                                  <?php echo textClass::ago($zobj['desdate']);?></h6>
                              <br>
                              <img src="<?php  echo $zobj['imgdata']; ?>" class="img-fluid">
                          </div>
                          
                      </div>
                      <?php if(!empty($zobj['tags'])){ $clientkeyws=$zobj['tags']; } ?>
                      
                      <?php
            } else { echo "<div class='alert alert-light'>No result found</div>";} $disnav="1"; 
          } else {                                                                                                                                                                                                                                                 
            if(isset($_POST['searchkey'])){
              $sql="SELECT id,desid,desname,desdate,imgdata,author,tags FROM config_diagrams where (lower(desname) like ? or xmldata like ?) ".(!empty($sactive)?" and".$sactive:"");
              $q = $pdo->prepare($sql);
              $q->execute(array("%".strtolower(htmlspecialchars($_POST['searchkey']))."%","%".htmlspecialchars($_POST['searchkey'])."%"));
            } elseif($forumcase=="tags"){
              if(!empty($keyws)){
                $clientkeyws=ltrim($keyws);
                $clientkeyws=rtrim($clientkeyws);
                $kt=explode(" ",$clientkeyws);
                foreach($kt as $key=>$val){ if($val<>" " and strlen($val) > 0){$qt .= " tags like '%$val%' or ";}}
                $qt=substr($qt,0,(strLen($qt)-3));
                $sql="SELECT id,desid,desname,desdate,imgdata,author,tags FROM config_diagrams where $qt ".(!empty($sactive)?" and ".$sactive:"");
              } else { 
               $sql="SELECT id,desid,desname,desdate,imgdata,author,tags FROM config_diagrams ".(!empty($sactive)?" where".$sactive:"");
              }
              $q = $pdo->prepare($sql);
              $q->execute(array());  
            } elseif($forumcase=="category"){
              $sql="SELECT id,desid,desname,desdate,imgdata,author,tags FROM config_diagrams where category=? ".(!empty($sactcat)?" and ".$sactcat:"");
              $q = $pdo->prepare($sql);
              $q->execute(array($keyws));
            } else {
              $sql="SELECT count(id) FROM config_diagrams".(!empty($sactive)?" where".$sactive:"");
              $q = $pdo->prepare($sql);
              $q->execute();                              
              $total_results = $q->fetchColumn();
              $total_pages = ceil($total_results / $max_results);
              if($dbtype=='postgresql'){
                  $sql="SELECT id,desid,desname,desdate,imgdata,author,tags FROM config_diagrams ".(!empty($sactive)?" where".$sactive:"")." order by id desc offset ".$from." LIMIT ".$max_results;
              } else {
                  $sql="SELECT id,desid,desname,desdate,imgdata,author,tags FROM config_diagrams ".(!empty($sactive)?" where".$sactive:"")." order by id desc LIMIT ".$from.",".$max_results;
              }
              $q = $pdo->prepare($sql);
              $q->execute();
            }
            if($zobj = $q->fetchAll()){
                echo "<ul class='row' style='padding:0px;'>";
              foreach($zobj as $val) {
                ?>

<li class="col-md-4" style="display: flex;">
            <div onclick="location.href='/diagrams/v/<?php echo $val['desid'];?>'" class="card waves-effect waves-dark" style="width:100%;">

                <div class="card-header">
                    <h4><?php echo $val['desname'];?></h4>
                </div>
                <div class="card-body">

                    <div class="row ">
                        <div class="col-md-12 text-start cardwf">
                            <p class="card-text">
                            <img src="<?php echo $val['imgdata'];?>" class="img-fluid"></p>
                            <div class="d-flex no-block align-items-center mb-3">
                            <?php  
                            if($val['tags']){
  $kt=explode(",",$val['tags']);
  $kt=array_unique($kt);
  shuffle($kt);
  foreach($kt as $key=>$val){ if($val<>" " and strlen($val) < 60 and strlen($val) > 0){ 
    $val=ltrim($val, ' ');
    $val=rtrim($val, ' '); ?><a class="waves-effect btn btn-light btn-sm" style="margin: 0 4px 6px 0;" href="/diagrams/tags/<?php echo $val;?>"><i class="mdi mdi-tag"></i>&nbsp;<?php echo $val;?></a><?php }} 
  }?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </li>


                     




                      <?php }
                    echo "</ul>";
                    } else { ?> <div class="card" style="box-shadow:none;">
                          <div class="card-body card-padding">
                              <div class="text-center">
                                  <p class="lead">There are no diagrams in this category</p>
                                  <p class="lead"><a href="/draw">Create one</a></p>
                              </div>
                          </div>
                      </div><?php } ?>
                      <?php if($blognum>0){?>
                      <nav style="padding-top:10px">
                          <ul class="pagination">
                              <?php if($page > 1)
          { ?>
                              <li class="page-item"><a class="page-link" href="/diagrams/pn/<?php echo $prev;?>">Previous</a></li>
                              <?php }
          for($i = 1; $i <= $total_pages; $i++)
              {
                if(($page) == $i)
                { ?>
                              <li class="page-item active"><a class="page-link"
                                      href="/diagrams/pn/<?php echo $i;?>"><?php echo $i;?></a></li>
                              <?php  }
                else
                { ?>
                              <li class="page-item"><a class="page-link" href="/diagrams/pn/<?php echo $i;?>"><?php echo $i;?></a>
                              </li>
                              <?php }
              }
          if($page < $total_pages)
          { ?>
                              <li class="page-item"><a class="page-link" href="/diagrams/pn/<?php echo $next;?>">Next</a></li>
                              <?php } ?>
                          </ul>
                      </nav>
                      <?php } 
                }

        echo '</div><div class="col-md-2">'; 
        include "public/modules/diagramsidebar.php";
        echo '</div></div></div></div>'; 
        include "public/modules/footer.php";
        include "public/modules/js.php";
        include "public/modules/template_end.php";
        if(!empty($text)){unset($text);}
         echo '</body></html>';
    }
}