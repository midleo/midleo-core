<?php

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
            <div class="card-header"><h4>Profile</h4></div>
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
                <br>
                <a href="javascript:history.back(1)" class="btn btn-light btn-sm"><i
                        class="mdi mdi-arrow-left"></i>&nbsp;Back</a>

            </div>

        </div>
    </div>

    <div class="col-lg-8 col-md-12">
        <div class="card">
            <div class="card-header"><h4>Timeline - last 20 activities</h4></div>
            <div class="card-body">

<?php $sql="select * from tracking where whoid=? order by id desc limit 20";
$q = $pdo->prepare($sql);  
$q->execute(array($thisarray['p2'])); 
if($zobj = $q->fetchAll()){
foreach($zobj as $val) { 

?>
                <div class="sl-item mt-2 mb-3">
                    <div class="sl-left">
                        <div>
                            <div class="d-flex">
                                <h5 class="mb-0 font-weight-light"><a href="#"
                                        class="link"><?php echo $tmp["user"]["fullname"];?></a></h5>
                                <span class="sl-date text-muted ml-1"><?php echo textClass::ago($val["trackdate"]);?></span>
                            </div>
                            <p class="mt-2"> <?php echo $val["projid"];?><br> <?php echo $val["what"];?></p>
                        </div>
                    </div>
                </div>
                <hr>
<?php } } else { echo "No activity yet."; } ?>


            </div>
        </div>
    </div>
</div>

<?php } else {
    textClass::PageNotFound();
}