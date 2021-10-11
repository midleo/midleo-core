<?php
array_push($brarr,array(
    "title"=>"Back",
    "link"=>"javascript:history.back(1)",
    "icon"=>"mdi-arrow-left",
    "active"=>true,
  ));
 $sql="select t.email,t.fullname,t.ldapserver, t.avatar, t.user_online_show from users t where t.mainuser=?"; 
 $q = $pdo->prepare($sql);
 $q->execute(array($thisarray['p2']));
 $tmp=array();
 if($row = $q->fetch(PDO::FETCH_ASSOC)){ 
     $tmp["user"]=array(
        "fullname"=>$row['fullname'],
        "avatar"=>$row['avatar'],
        "ldap"=>$row['ldapserver'],
        "email"=>$row['email'],
        "type"=>"user"
     );
 } 
if(!empty($tmp["user"]) && is_array($tmp["user"])){ ?>

<div class="row">
    <div class="col-lg-4 col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Profile</h4>
            </div>
            <div class="card-body">
                <div class="mt-4 text-center">
                    <img src="<?php echo !empty($tmp["user"]["avatar"])?$tmp["user"]["avatar"]:"/assets/images/avatar.svg";?>"
                        class="rounded-circle" width="150" />
                    <h4 class="card-title mt-2"><?php echo $tmp["user"]["fullname"];?></h4>
                    <h6 class="card-subtitle"></h6>
                </div>
                <div>
                    <hr>
                </div>
                <?php if($tmp["user"]["email"]){ ?>
                <small class="text-muted">Email address </small>
                <h6><?php if(isset($_SESSION["requser"]) || isset($_SESSION["user"])){?><a
                        href="mailto:<?php echo $tmp["user"]["email"];?>"><?php echo $tmp["user"]["email"];?></a><?php } else { echo "Login to see it"; } ?>
                </h6>
                <?php } ?>
                <?php 
                if(!empty($tmp["user"]["ldap"])){ 
                    $sql="select ldapinfo from ldap_config where ldapserver=?";  
                    $q = $pdo->prepare($sql);  
                    $q->execute(array($tmp["user"]["ldap"]));  
                    if($rowin = $q->fetch(PDO::FETCH_ASSOC)){
                      echo '<small class="text-muted">Organisation</small><h6>'.$rowin['ldapinfo'].'</h6>';
                    }
                  }
                  ?>


            </div>

        </div>
    </div>

    <div class="col-lg-8 col-md-12">

        <?php $sql="select * from tracking where whoid=? order by id desc limit 20";
$q = $pdo->prepare($sql);  
$q->execute(array($thisarray['p2'])); 
if($zobj = $q->fetchAll()){
foreach($zobj as $val) { 

?>

        <div class="card  p-2 mb-3">
            <div class="d-flex align-items-start">
                <img class="me-2 avatar-sm rounded-circle" src="/assets/images/avatar.svg"
                    alt="<?php echo $tmp["user"]["fullname"];?>" style="width:40px;">
                <div class="w-100">
                    <h5 class="m-0"><?php echo $tmp["user"]["fullname"];?></h5>
                    <p class="text-muted"><small><i
                                class="mdi mdi-clock-outline"></i>&nbsp;<?php echo textClass::ago($val["trackdate"]);?></small>
                    </p>

                    <div>
                        <p>
                            <?php echo !empty($val["appid"])?'<a href="/env/apps/test'.$val["appid"].'" target="_blank"><i class="mdi mdi-application-brackets-outline"></i>&nbsp;'.$val["appid"]."</a><br>":"";?>
                            <?php echo !empty($val["projid"])?'<i class="mdi mdi-projector-screen-outline"></i>&nbsp;'.$val["projid"]."<br>":"";?>
                            <i class="mdi mdi-information-outline"></i>&nbsp;<?php echo $val["what"];?></p>
                    </div>

                </div>
            </div>

        </div>

        <?php } } else { echo "No activity yet."; } ?>


    </div>
</div>

<?php } else {
    textClass::PageNotFound();
}