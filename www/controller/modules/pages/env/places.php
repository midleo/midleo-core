<?php if (sessionClass::checkAcc($acclist, "appadm,appview")) { 
      array_push($brarr,array(
        "title"=>"Create new place",
        "link"=>"/env/places/".$thisarray['p2']."/?type=new",
        "icon"=>"mdi-plus",
        "active"=>false,
      ));
    
    ?>
<?php  if($_GET["type"]=="new"){ ?>
<div class="row">
    <div class="col-md-8">
        <div class="card ">
            <div class="card-header">
                <h4>Region definition</h4>
            </div>
            <div class="card-body form-material">
                <form action="" method="post" name="form">
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-3">Place name</label>
                        <div class="col-md-9">
                            <input name="place" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-3">Tags</label>
                        <div class="col-md-8"><input id="tags" name="tags" data-role="tagsinput" type="text"
                                class="form-control"></div>
                        <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="You can search this object with tags"><i
                                    class="mdi mdi-information-variant mdi-18px"></i></button></div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-3">Region</label>
                        <div class="col-md-9"><select name="region" class="form-control">
                                <option value="">Please select</option>
                                <?php 
foreach($countries as $keyin=>$valin) { ?><option value="<?php echo $keyin;?>"><?php echo $valin;?></option><?php  }
?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-3">City</label>
                        <div class="col-md-9">
                            <input name="city" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-3">Type</label>
                        <div class="col-md-9">
                            <select name="type" class="form-control">
                                <option value="room">a Room</option>
                                <option value="datacenter">a Datacenter</option>
                                <option value="closet">a Closet</option>
                                <option value="closet">a Desk</option>
                                <option value="cloud">Cloud Provider</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-3">Contact person</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="autocomplete">
                            <input type="text" name="contact" id="respusersselected" style="display:none;" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3"></div>
                        <div class="col-md-9">
                            <button type="submit" name="saveplace" class="btn btn-info"><i
                                    class="mdi mdi-content-save-outline"></i>&nbsp;Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">


    </div>
</div>
<?php } else if($_GET["type"]=="edit"){ 
    $sql="select * from env_places where pluid=?";
    $q = $pdo->prepare($sql); 
    $q->execute(array(htmlspecialchars($_GET["uid"]))); 
    if($zobj = $q->fetch(PDO::FETCH_ASSOC)){  ?>

<div class="row">
    <div class="col-md-6">
        <div class="card ">
            <div class="card-header">
                <h4>Region definition</h4>
            </div>
            <div class="card-body form-material">
                <form action="" method="post" name="form">
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-3">Place name</label>
                        <div class="col-md-9">
                            <input name="place" type="text" value="<?php echo $zobj["placename"];?>"
                                class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-3">Tags</label>
                        <div class="col-md-8"><input id="tags" name="tags" data-role="tagsinput" type="text"
                                value="<?php echo $zobj["tags"];?>" class="form-control"></div>
                        <div class="col-md-1" style="padding-left:0px;"><button type="button" class="btn btn-light"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="You can search this object with tags"><i
                                    class="mdi mdi-information-variant mdi-18px"></i></button></div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-3">Region</label>
                        <div class="col-md-9"><select name="region" class="form-control">
                                <option value="">Please select</option>
                                <?php 
foreach($countries as $keyin=>$valin) { ?><option value="<?php echo $keyin;?>"
                                    <?php echo $zobj["plregion"]==$keyin?"selected":"";?>><?php echo $valin;?></option><?php  }
?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-3">City</label>
                        <div class="col-md-9">
                            <input name="city" type="text" value="<?php echo $zobj["plcity"];?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-3">Type</label>
                        <div class="col-md-9">
                            <select name="type" class="form-control">
                                <option value="room" <?php echo $zobj["pltype"]=="room"?"selected":"";?>>a Room</option>
                                <option value="datacenter" <?php echo $zobj["pltype"]=="datacenter"?"selected":"";?>>a
                                    Datacenter</option>
                                <option value="closet" <?php echo $zobj["pltype"]=="closet"?"selected":"";?>>a Closet
                                </option>
                                <option value="desk" <?php echo $zobj["pltype"]=="desk"?"selected":"";?>>a Desk</option>
                                <option value="cloud" <?php echo $zobj["pltype"]=="cloud"?"selected":"";?>>Cloud
                                    Provider</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-3">Contact person</label>
                        <label class="form-control-label text-lg-left col-md-6 chcontact"><a
                                href="/browse/<?php echo explode("#",$zobj["plcontact"])[0];?>/<?php echo explode("#",$zobj["plcontact"])[1];?>"
                                target="_blank"><?php echo explode("#",$zobj["plcontact"])[2];?></a></label>
                        <div class="form-control-label text-lg-right col-md-3 chcontact"><a
                                onclick="$('.chcontact').hide();$('.addcontact').show();">Change</a></div>
                        <div class="col-md-9 addcontact" style="display:none;">
                            <input type="text" class="form-control" id="autocomplete">
                            <input type="text" name="contact" id="respusersselected" style="display:none;" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3"></div>
                        <div class="col-md-9">
                            <button type="submit" name="updplace" class="btn btn-info"><i
                                    class="mdi mdi-content-save-outline"></i>&nbsp;Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card ">
            <div class="card-body p-0">
                <?php
$sql="select serverid,serverdns,servertype from env_servers where pluid=?"; 
$q = $pdo->prepare($sql);
$q->execute(array(htmlspecialchars($_GET["uid"]))); 
if($zobj = $q->fetchAll()){ ?>
                <table id="data-table" class="table table-hover stylish-table mb-0" aria-busy="false">
                    <thead>
                        <tr>
                            <th>Server</th>
                            <th>Type</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($zobj as $val) { ?>
                        <tr>
                            <td><a
                                    href="/browse/server/<?php echo $val['serverid'];?>"><?php echo $val['serverdns'];?></a>
                            </td>
                            <td><?php echo $val['servertype'];?></td>
                            <td> </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php } ?>
            </div>
        </div>
    </div>
</div>


<?php } else { echo "Wrong ID";}} else { ?>
<?php if(empty($thisarray['p2'])){ include "applist.php"; } else { ?>
<div class="row">
    <div class="col-lg-12 pe-0 ps-0">
        <div class="card p-0">
            <table class="table table-vmiddle table-hover stylish-table mb-0">
                <thead>
                    <tr>
                        <th class="text-center">Place</th>
                        <th class="text-center">Region</th>
                        <th class="text-center">City</th>
                        <th class="text-center">Used</th>
                        <th class="text-center">Contact</th>
                        <th class="text-center" width="100px">Action</th>
                    </tr>
                </thead>
                <tbody ng-init="getAllplaces('<?php echo $thisarray['p2'];?>')">
                    <tr ng-hide="contentLoaded">
                        <td colspan="6" style="text-align:center;font-size:1.1em;"><i
                                class="mdi mdi-loading iconspin"></i>&nbsp;Loading...</td>
                    </tr>
                    <tr id="contloaded" class="hide" dir-paginate="d in names | filter:search | itemsPerPage:10"
                        pagination-id="prodx">
                        <td class="text-center">{{ d.name }}</td>
                        <td class="text-center">{{ d.region }}</td>
                        <td class="text-center">{{ d.city }}</td>
                        <td class="text-center">{{ d.used }}</td>
                        <td class="text-center">{{ d.user }}</td>
                        <td>
                            <?php if (sessionClass::checkAcc($acclist, "appadm,appview")) { ?>
                            <div class="text-start d-grid gap-2 d-md-block">
                                <a href="/env/places/<?php echo $thisarray['p2'];?>/?type=edit&uid={{d.id}}"
                                    class="btn waves-effect btn-light btn-sm"><i
                                        class="mdi mdi-pencil mdi-18px"></i></a>
                                <?php if($_SESSION['user_level']>=3){?><button type="button"
                                    ng-click="delplace(d.id,'<?php echo $_SESSION['user'];?>',d.name,'<?php echo $thisarray['p2'];?>')"
                                    class="btn waves-effect btn-light btn-sm"><i
                                        class="mdi mdi-close"></i></button><?php } ?>
                            </div>
                            <?php } ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <dir-pagination-controls pagination-id="prodx" boundary-links="true"
                on-page-change="pageChangeHandler(newPageNumber)" template-url="/assets/templ/pagination.tpl.html">
            </dir-pagination-controls>
        </div>
    </div>
</div>
<?php } ?>
<?php } ?>
<?php } ?>