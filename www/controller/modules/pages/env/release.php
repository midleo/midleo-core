<?php if (sessionClass::checkAcc($acclist, "appadm,appview")) { 
      array_push($brarr,array(
        "title"=>"Define new release",
        "link"=>"/env/release//?type=new",
        "icon"=>"mdi-plus",
        "active"=>false,
      ));
    ?>
<?php  if($_GET["type"]=="new"){ ?>
<div class="row">
    <div class="col-md-6">
        <div class="card ">
            <div class="card-header">
                <h4>Release definition</h4>
            </div>
            <div class="card-body form-material">
                <form action="" method="post" name="form">
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-5">Release name</label>
                        <div class="col-md-7">
                            <input name="release" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-5">Release period</label>
                        <div class="col-md-7">
                            <select name="relperiod" class="form-control">
                                <option value="1 day">Daily</option>
                                <option value="1 week">Weekly</option>
                                <option value="1 month">Monthly</option>
                                <option value="3 months">Once each 3 months</option>
                                <option value="6 months">Once each 6 months</option>
                                <option value="1 year">Yearly</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-5">Type</label>
                        <div class="col-md-7">
                            <select name="reltype" class="form-control">
                                <?php foreach($reltypes as $key=>$val){ ?>
                                <option value="<?php echo $key;?>"><?php echo $val;?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-5">Main version</label>
                        <div class="col-md-7">
                        <input name="relversion" type="text" value=""
                                class="form-control" placeholder="eg. 9, 2.2, 8.1">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-5">Version match text</label>
                        <div class="col-md-5">
                            <input name="versionmatch" type="text" value=""
                                class="form-control">
                        </div>
                        <div class="col-md-2 text-center">
                            <button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Some releases require additional matching text to be specified. For example LTS or z/OS"><i
                                    class="mdi mdi-information-variant mdi-18px"></i></button>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-5">Contact person</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" id="autocomplete">
                            <input type="text" name="contact" id="respusersselected" style="display:none;" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-5"></div>
                        <div class="col-md-7"><br>
                            <button type="submit" name="saverelease" class="btn btn-info"><i
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
    $sql="select * from env_releases where relid=?";
    $q = $pdo->prepare($sql); 
    $q->execute(array(htmlspecialchars($_GET["uid"]))); 
    if($zobj = $q->fetch(PDO::FETCH_ASSOC)){  ?>

<div class="row">
    <div class="col-md-6">
        <div class="card ">
            <div class="card-header">
                <h4>Release definition</h4>
            </div>
            <div class="card-body form-material">
                <form action="" method="post" name="form">
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-5">Release name</label>
                        <div class="col-md-7">
                            <input name="release" type="text" value="<?php echo $zobj["releasename"];?>"
                                class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-5">Release period</label>
                        <div class="col-md-7">
                            <select name="relperiod" class="form-control">
                                <option value="1 day" <?php echo $zobj["relperiod"]=="1 day"?"selected":"";?>>Daily
                                </option>
                                <option value="1 week" <?php echo $zobj["relperiod"]=="1 week"?"selected":"";?>>Weekly
                                </option>
                                <option value="1 month" <?php echo $zobj["relperiod"]=="1 month"?"selected":"";?>>
                                    Monthly</option>
                                <option value="3 months" <?php echo $zobj["relperiod"]=="3 months"?"selected":"";?>>Once
                                    each 3 months</option>
                                <option value="6 months" <?php echo $zobj["relperiod"]=="6 months"?"selected":"";?>>Once
                                    each 6 months</option>
                                <option value="1 year" <?php echo $zobj["relperiod"]=="1 year"?"selected":"";?>>Yearly
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-5">Type</label>
                        <div class="col-md-7">
                            <select name="reltype" class="form-control">
                                <?php foreach($reltypes as $key=>$val){ ?>
                                <option value="<?php echo $key;?>" <?php echo $zobj["reltype"]==$key?"selected":"";?>>
                                    <?php echo $val;?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-5">Main version</label>
                        <div class="col-md-7">
                        <input name="relversion" type="text" value="<?php echo $zobj["relversion"];?>"
                                class="form-control" placeholder="eg. 9, 2.2, 8.1">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-5">Version match text</label>
                        <div class="col-md-5">
                            <input name="versionmatch" type="text" value="<?php echo $zobj["versionmatch"];?>"
                                class="form-control">
                        </div>
                        <div class="col-md-2 text-center">
                            <button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Some releases require additional matching text to be specified. For example LTS or z/OS"><i
                                    class="mdi mdi-information-variant mdi-18px"></i></button>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-5">Contact person</label>
                        <label class="form-control-label text-lg-left col-md-4 chcontact"><a
                                href="/browse/<?php echo explode("#",$zobj["relcontact"])[0];?>/<?php echo explode("#",$zobj["relcontact"])[1];?>"
                                target="_blank"><?php echo explode("#",$zobj["relcontact"])[2];?></a></label>
                        <div class="form-control-label text-lg-right col-md-3 chcontact"><a
                                onclick="$('.chcontact').hide();$('.addcontact').show();">Change</a></div>
                        <div class="col-md-7 addcontact" style="display:none;">
                            <input type="text" class="form-control" id="autocomplete">
                            <input type="text" name="contact" id="respusersselected" style="display:none;" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-5"></div>
                        <div class="col-md-7"><br>
                            <button type="submit" name="updrelease" class="btn btn-info"><i
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


<?php } else { echo "Wrong ID";}} else { ?>
<div class="row">
    <div class="col-lg-12 ">
        <div class="card p-0">
            <table class="table table-vmiddle table-hover stylish-table mb-0">
                <thead>
                    <tr>
                        <th class="text-center">Release</th>
                        <th class="text-center">Period</th>
                        <th class="text-center">Type</th>
                        <th class="text-center">Main version</th>
                        <th class="text-center">Latest version</th>
                        <th class="text-center">Last check</th>
                        <th class="text-center">Contact</th>
                        <th class="text-center" width="100px">Action</th>
                    </tr>
                </thead>
                <tbody ng-init="getAllreleses()">
                    <tr ng-hide="contentLoaded">
                        <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
                        <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
                        <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
                        <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
                        <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
                        <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
                        <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
                        <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
                    </tr>
                    <tr id="contloaded" class="hide" dir-paginate="d in names | filter:search | itemsPerPage:10"
                        pagination-id="prodx">
                        <td class="text-center">{{ d.name }}</td>
                        <td class="text-center">{{ d.period }}</td>
                        <td class="text-center">{{ d.reltype }}</td>
                        <td class="text-center">{{ d.relversion }}</td>
                        <td class="text-center">{{ d.latestver }}</td>
                        <td class="text-center">{{ d.lastcheck }}</td>
                        <td class="text-center">{{ d.user }}</td>
                        <td>
                            <?php if (sessionClass::checkAcc($acclist, "appadm,appview")) { ?>
                            <div class="text-start d-grid gap-2 d-md-block">
                                <a href="/env/release//?type=edit&uid={{d.id}}"
                                    class="btn waves-effect btn-light btn-sm"><i
                                        class="mdi mdi-pencil mdi-18px"></i></a>
                                <?php if($_SESSION['user_level']>=3){?><button type="button"
                                    ng-click="delrelease(d.id,'<?php echo $_SESSION['user'];?>',d.name)"
                                    class="btn waves-effect btn-light btn-sm"><i
                                        class="mdi mdi-close"></i></button><?php } ?>
                            </div>
                            <?php } ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <dir-pagination-controls pagination-id="prodx" boundary-links="true"
                on-page-change="pageChangeHandler(newPageNumber)"
                template-url="/<?php echo $website['corebase'];?>assets/templ/pagination.tpl.html">
            </dir-pagination-controls>
        </div>
    </div>
</div>
<?php } ?>
<?php } ?>