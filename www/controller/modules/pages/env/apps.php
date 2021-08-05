<?php if($_GET["type"]=="new"){ ?>
  <div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Create an application
                    <div class="float-end">
                        <a href="/env/apps" class="btn btn-light btn-sm"><svg class="midico midico-outline">
                                <use href="/assets/images/icon/midleoicons.svg#i-x"
                                    xlink:href="/assets/images/icon/midleoicons.svg#i-x" />
                            </svg>&nbsp;Cancel</a>&nbsp;

                    </div>
                </h4>
            </div>
            <div class="card-body">
                <form name="projform" action="" enctype="multipart/form-data" method="post">
                <div class="form-group row">
                        <label class="form-control-label text-lg-left col-md-3">Application Name</label>
                        <div class="col-md-9"><input required name="appname" id="appname" type="text" class="form-control"
                                value=""></div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-left col-md-3">Application Identifier</label>
                        <div class="col-md-9"><input required name="appcode" type="text" id="appcode" class="form-control"
                                value=""></div>
                    </div>
                    <div class="form-group row">
                                        <label class="form-control-label text-lg-left col-md-3">Tags</label>
                                        <div class="col-md-8"><input id="tags" data-role="tagsinput" name="tags"
                                                type="text" class="form-control" value="">
                                        </div>
                                        <div class="col-md-1" style="padding-left:0px;"><button type="button"
                                                class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="You can search this project with tags"><i
                                                    class="mdi mdi-information-variant mdi-18px"></i></button></div>
                                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-left col-md-3">Application Description</label>
                        <div class="col-md-9"><textarea name="appinfo"
                                class="form-control textarea"></textarea></div>
                    </div>
                   


                    <div class="form-group row" id="reqfiles">
                        <label class="form-control-label text-lg-right col-md-3"></label>
                        <div class="col-md-9">
                            <button type="button" id="docupload" onClick="getFile('dfile')" class="btn btn-light"><svg
                                    class="midico midico-outline">
                                    <use href="/assets/images/icon/midleoicons.svg#i-add"
                                        xlink:href="/assets/images/icon/midleoicons.svg#i-add" />
                                </svg>&nbsp;Upload file/files</button>
                            <div style='height: 0px;width: 0px; overflow:hidden;'><input type="file" name="dfile[]"
                                    id="dfile" onChange="sub(this,'docupload')" multiple="" /></div>
                            <br>
                            <ul id="fileList" class="list-unstyled">
                                <li>No Files Selected</li>
                            </ul>
                        </div>
                    </div>



                <div class="form-group row">
                        <label class="form-control-label text-lg-left col-md-3"></label>
                        <div class="col-md-9">
                            <button type="submit" name="addapp" class="btn btn-info"><svg
                                    class="midico midico-outline">
                                    <use href="/assets/images/icon/midleoicons.svg#i-check"
                                        xlink:href="/assets/images/icon/midleoicons.svg#i-check" />
                                </svg>&nbsp;Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php } elseif(!empty($_GET["app"])){ 
  if(isset($_POST["updapp"])){
    $sql="update config_app_codes set tags=?,appname=?, appinfo=? where appcode=?";
    $q = $pdo->prepare($sql); 
    if($q->execute(array(htmlspecialchars($_POST["tags"]),htmlspecialchars($_POST["appname"]),$_POST["appinfo"],htmlspecialchars($_GET["app"])))){       
        $msg[]="Application updated";
    } else {
        $err[]="Error updating the application";
    }
 
  }
  if($_GET["type"]=="edit"){
    $sql="select id,tags,appcode,appname,".(DBTYPE=='oracle'?"to_char(appinfo) as appinfo":"appinfo").",owner,".(DBTYPE=='oracle'?"to_char(appusers) as appusers":"appusers")." from config_app_codes where appcode=? and owner='".$_SESSION["user"]."'";
  } else {
    $sql="select id,tags,appcode,appname,".(DBTYPE=='oracle'?"to_char(appinfo) as appinfo":"appinfo").",owner,".(DBTYPE=='oracle'?"to_char(appusers) as appusers":"appusers")." from config_app_codes where appcode=?";
  }
  $q = $pdo->prepare($sql); 
  $q->execute(array(htmlspecialchars($_GET["app"]))); 
  if($zobj = $q->fetch(PDO::FETCH_ASSOC)){ 
    ?>
<div class="row ngctrl" id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
            <h4><?php if($_GET["type"]=="edit"){} else { ?><?php echo $zobj["appname"];?><?php } ?>
                    <?php if($_SESSION["user"]==$zobj["owner"]){?>
                    <div class="float-end">
                        <form name="projform" action="" method="post">
                        <?php if($_GET["type"]=="edit"){ ?>
                            <a href="/env/apps/?app=<?php echo $_GET["app"];?>"
                                class="btn btn-light btn-sm"><svg class="midico midico-outline">
                                    <use href="/assets/images/icon/midleoicons.svg#i-check"
                                        xlink:href="/assets/images/icon/midleoicons.svg#i-check" />
                                </svg>&nbsp;Done</a>&nbsp;
                        <?php } else { ?>
                            <a href="/env/apps/?app=<?php echo $_GET["app"];?>&type=edit"
                                class="btn btn-light btn-sm"><svg class="midico midico-outline">
                                    <use href="/assets/images/icon/midleoicons.svg#i-edit"
                                        xlink:href="/assets/images/icon/midleoicons.svg#i-edit" />
                                </svg>&nbsp;Edit</a>&nbsp;
                                <?php } ?>
                            <button type="button" ng-click="deleteapp('<?php echo $zobj["id"];?>','<?php echo $_GET["app"];?>','<?php echo $_SESSION["user"];?>','yes')" class="btn btn-light btn-sm"><svg
                                    class="midico midico-outline">
                                    <use href="/assets/images/icon/midleoicons.svg#i-x"
                                        xlink:href="/assets/images/icon/midleoicons.svg#i-x" />
                                </svg>&nbsp;Delete</button>

                        </form>
                    </div>
                    <?php } ?>
                </h4>
            </div>
            <div class="card-body">


            <?php if($_GET["type"]=="edit"){?>
                <form name="projform" action="" enctype="multipart/form-data" method="post">
          
                    <div class="form-group row">
                        <label class="form-control-label text-lg-left col-md-3">Application Name</label>
                        <div class="col-md-9"><input required name="appname" type="text" class="form-control"
                                value="<?php echo $zobj["appname"];?>"></div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-left col-md-3">Application identifier</label>
                        <div class="col-md-9"><input type="text" class="form-control" disabled
                                value="<?php echo $_GET["app"];?>"></div>
                    </div>
                    <div class="form-group row">
                                        <label class="form-control-label text-lg-left col-md-3">Tags</label>
                                        <div class="col-md-8"><input id="tags" data-role="tagsinput" name="tags"
                                                type="text" class="form-control" value="<?php echo $zobj["tags"];?>">
                                        </div>
                                        <div class="col-md-1" style="padding-left:0px;"><button type="button"
                                                class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="You can search for this application with tags"><i
                                                    class="mdi mdi-information-variant mdi-18px"></i></button></div>
                                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-left col-md-3">Application Description</label>
                        <div class="col-md-9"><textarea name="appinfo"
                                class="form-control textarea"><?php echo $zobj["appinfo"];?></textarea></div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="form-control-label text-lg-left col-md-3"></label>
                        <div class="col-md-9">
                            <button type="submit" name="updapp" class="btn btn-info"><svg
                                    class="midico midico-outline">
                                    <use href="/assets/images/icon/midleoicons.svg#i-check"
                                        xlink:href="/assets/images/icon/midleoicons.svg#i-check" />
                                </svg>&nbsp;Save</button>
                        </div>
                    </div>


                </form>
                <?php } else { ?>
                <h5 class="font-size-15 mt-1 mb-2">Application Details :</h5>
                <?php echo $zobj["appinfo"];?>
                <?php } ?>

            </div>
            <div class="card-footer">
            <div class="float-end">
                        <a href="/reports/apps/?app=<?php echo $_GET["app"];?>" class="btn btn-light btn-sm"><svg class="midico midico-outline">
                                <use href="/assets/images/icon/midleoicons.svg#i-app"
                                    xlink:href="/assets/images/icon/midleoicons.svg#i-app" />
                            </svg>&nbsp;Generate report</a>&nbsp;

                    </div>
                </div>
            </div>
            </div>
<div class="col-md-4">
<div class="card">
            <div class="card-header">
                <h4>Owner</h4>
            </div>
            <div class="card-body  p-0">
<?php 
$sql="select avatar,fullname,utitle,user_online,user_online_show from users where mainuser=?";
$qin = $pdo->prepare($sql); 
$qin->execute(array($zobj["owner"])); 
if($zobjin = $qin->fetch(PDO::FETCH_ASSOC)){ ?>
<div class="contact-widget position-relative">
<a href="/browse/user/<?php echo $zobj["owner"];?>" target="_blank" class="py-3 px-2 border-bottom d-block text-decoration-none">
 <div class="user-img position-relative d-inline-block mr-2"> 
 <img src="<?php echo !empty($zobjin["avatar"])?$zobjin["avatar"] : '/assets/images/avatar.svg' ;?>"
 width="40" alt="<?php echo $zobjin["fullname"];?>" data-bs-toggle="tooltip"
 data-bs-placement="top" title="<?php echo $zobjin["fullname"];?>" class="rounded-circle">
 <span class="profile-status pull-right d-inline-block position-absolute bg-<?php echo $zobjin["user_online_show"]==0?"secondary":($zobjin["user_online"]==1?"success":"danger");?> rounded-circle"></span>
</div>
 <div class="mail-contnet d-inline-block align-middle">
 <h5 class="my-1"><?php echo $zobjin["fullname"];?></h5> <span class="mail-desc font-12 text-truncate overflow-hidden badge badge-info"><?php echo !empty($zobjin["utitle"])?$zobjin["utitle"]:"No title";?></span>
</div>
</a>
</div>
<?php } ?>

            </div>
            </div>


            <div class="card">
            <div class="card-header">
                <h4>Team members</h4>
            </div>
            <div class="card-body  p-<?php echo $_GET["type"]=="edit"?"1":"0";?>">
            <?php if($_GET["type"]=="edit"){?>
            
            
                <div class="form-group row usersdiv" ng-init="readappgr('<?php echo $_GET["app"];?>','<?php echo $_SESSION['user'];?>')">
                   <div class="col-md-8">   
                       <input type="text" class="form-control" id="autocomplete" placeholder="Find a user">
                              <input type="text" ng-model="group.user" id="respusersselected" style="display:none;" value="">
                              </div> <div class="col-md-4"> <button type="button" class="waves-effect btn btn-light btn-sm" ng-click="addappgr('<?php echo $_GET["app"];?>','<?php echo $_SESSION["user"];?>')"><svg class="midico midico-outline">
                                <use href="/assets/images/icon/midleoicons.svg#i-add"
                                    xlink:href="/assets/images/icon/midleoicons.svg#i-add" />
                            </svg>&nbsp;Add</button> </div> </div>
                    <div class="grudivnt grudivtg" style="display:block;">
                               <ul style="margin: 0 auto;padding: 5px;" class="list-group list-group-flush">
                               <li style="padding:5px;" ng-repeat="(ukey, user) in respusers" class="list-group-item usr_{{ukey}}">{{user.uname}}<a class="float-end" ng-click="delappgr('<?php echo $_GET["app"];?>',ukey,user.type,'<?php echo $_SESSION['user'];?>')" style="cursor:pointer;"><svg class="midico midico-outline">
                                <use href="/assets/images/icon/midleoicons.svg#i-x"
                                    xlink:href="/assets/images/icon/midleoicons.svg#i-x" title="Delete" />
                            </svg></a></li>
                               </ul>
                               </div>
                            
                         

            
            <?php } else { ?>

                <?php if($zobj["appusers"]){ ?>
                    <div class="contact-widget position-relative">
                            <?php
   foreach(json_decode($zobj["appusers"],true) as $key=>$val){?>

<a href="/browse/user/<?php echo $key;?>" target="_blank" class="py-3 px-2 border-bottom d-block text-decoration-none">
 <div class="user-img position-relative d-inline-block mr-2"> 
 <img src="<?php echo !empty($val["uavatar"])?'/assets/images/users/'.$val["uavatar"] : '/assets/images/avatar.svg' ;?>"
width="40" alt="<?php echo $val["uname"];?>" data-bs-toggle="tooltip"
data-bs-placement="top" title="<?php echo $val["uname"];?>" class="rounded-circle">
</div>
 <div class="mail-contnet d-inline-block align-middle">
 <h5 class="my-1"><?php echo $val["uname"];?></h5> <span class="mail-desc font-12 text-truncate overflow-hidden badge badge-info"><?php echo $val["utitle"];?></span>
</div>
</a>
                            <?php } ?>
                </div>
                <?php } else { echo "<div class='alert'>No members yet</div>"; } ?>
                <?php } ?>
            </div>
        </div>


</div>

</div> 
<?php } else { echo "<div class='alert alert-light'>Wrong ID</div>"; }} else { ?>
<div class="row p-0">
  <div class="col-md-4 position-relative">
      <input type="text" ng-model="search" class="form-control topsearch" placeholder="Find an application">
      <span class="searchicon"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-search" xlink:href="/assets/images/icon/midleoicons.svg#i-search"/></svg> 
</span>
  </div>
  <div class="col-md-8 text-end">
<?php if ($_SESSION['user_level'] >= 3 && !in_array($thisarray['p1'], array("packages", "appservers", "servers", "import", "deploy", "flows", "fte"))) {?><button data-bs-toggle="tooltip" data-bs-placement="top" title="Export the objects in excel" type="button" class="waves-effect waves-light btn btn-light" ng-click="exportData('<?php echo $thisarray['p1']; ?>')">Export&nbsp;<svg class="midico midico-outline" ><use href="/assets/images/icon/midleoicons.svg#i-up" xlink:href="/assets/images/icon/midleoicons.svg#i-up"/></svg></button><?php }?>
 <span data-bs-toggle="tooltip" data-bs-placement="top" title="Create new application"><a href="/env/apps/?type=new" class="waves-effect waves-light btn btn-info" ><svg class="midico midico-outline" ><use href="/assets/images/icon/midleoicons.svg#i-add" xlink:href="/assets/images/icon/midleoicons.svg#i-add"/></svg>&nbsp;Create
</a></span>
  </div>
</div><br>

<div class="card ">
<div class="card-body p-0">

<div class="row"><div class="col-md-12">
  <table class="table table-vmiddle table-hover stylish-table mb-0">
    <thead>
      <tr>
        <th class="text-center">Identifier</th>
        <th class="text-center">Application</th>
        <th class="text-center">Owner</th>
        <th class="text-center">Tags</th>
        <th class="text-center" style="width:100px;">Action</th>
      </tr>
    </thead>
    <tbody ng-init="getAllapps()">
      <tr ng-hide="contentLoaded"><td colspan="5" style="text-align:center;font-size:1.1em;"><i class="mdi mdi-loading iconspin"></i>&nbsp;Loading...</td></tr>
      <tr id="contloaded" class="hide" dir-paginate="d in names | filter:search | orderBy:'appcode' | itemsPerPage:10" pagination-id="prodx">
        <td class="text-center"><a ng-click="addrecent('/env/apps',d.appcode,d.appname)" style="cursor:pointer;">{{ d.appcode }}</a></td>
        <td class="text-center">{{ d.appname }}</td>
        <td class="text-center">{{ d.owner }}</td>
        <td class="text-center"><span ng-if="d.tags" class="badge badge-secondary mr-1 mt-1" ng-repeat="(key,val) in d.tags"><a class="text-white" href="/searchall/?sa=y&st=tag&fd={{ val }}">{{val}}</a></span></td>
        <td>
        <div class="btn-group" role="group">
          <a href="/env/apps/?app={{d.appcode}}" class="btn waves-effect btn-light btn-sm"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-search" xlink:href="/assets/images/icon/midleoicons.svg#i-search"/></svg></a>
           <button ng-show="d.owner=='<?php echo $_SESSION["user"];?>'" type="button" ng-click="deleteapp(d.id,d.appcode,'<?php echo $_SESSION["user"];?>')" class="btn waves-effect btn-light btn-sm"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-trash" xlink:href="/assets/images/icon/midleoicons.svg#i-trash"/></svg></button>
           </div>
        </td>
      </tr>
    </tbody>
  </table>
  <dir-pagination-controls pagination-id="prodx" boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="/assets/templ/pagination.tpl.html"></dir-pagination-controls>
  </div>
</div> </div>
</div>
<?php } ?>