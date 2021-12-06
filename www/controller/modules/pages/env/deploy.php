<?php 
if (method_exists("IBMMQ", "execJava") && is_callable(array("IBMMQ", "execJava"))){ 
if (sessionClass::checkAcc($acclist, "appadm,appview")) {
 ?>
<?php if(empty($thisarray['p2'])){ include "applist.php"; } else { 
    array_push($brarr,array(
        "title"=>"Information",
        "link"=>"#impinfo",
        "modal"=>true,
        "icon"=>"mdi-alert-outline",
        "active"=>false,
      ));
      array_push($brarr,array(
        "title"=>"Deploy package",
        "link"=>"#modal-depl-form",
        "nglink"=>"resetDeplForm()",
        "modal"=>true,
        "icon"=>"mdi-upload",
        "active"=>false,
      ));
    
    ?>
<div class="row">
    <div class="col-lg-12 pe-0 ps-0">
        <div class="card p-0">
            <table class="table table-vmiddle table-hover stylish-table mb-0">
            <thead>
                <tr>
                    <th class="text-center" style="width:50px;"></th>
                    <th class="text-center">Type</th>
                    <th class="text-center">Date</th>
                    <th class="text-center">Package ID</th>
                    <th class="text-center">Deployed in</th>
                    <th class="text-center">Deployed objects</th>
                    <th class="text-center">Deployed by</th>
                </tr>
            </thead>
            <tbody ng-init="getAlldepl('<?php echo $thisarray['p2'];?>')">
                <tr ng-hide="contentLoaded">
                    <td colspan="7" style="text-align:center;font-size:1.1em;"><i
                            class="mdi mdi-loading iconspin"></i>&nbsp;Loading...</td>
                </tr>
                <tr id="contloaded" class="hide"
                    dir-paginate="d in names | filter:search | orderBy:'-depldate' | itemsPerPage:10"
                    pagination-id="prodx">
                    <td class="text-center"><i
                            class="mdi mdi-{{d.depltype==1 ? 'close-outline text-danger' : 'check-outline text-success'}}"></i>
                    </td>
                    <td class="text-center">{{ d.deplenv }}</td>
                    <td class="text-center">{{ d.depldate }}</td>
                    <td class="text-center">{{ d.packuid }}</td>
                    <td class="text-center">{{ d.deployedin }}</td>
                    <td class="text-center">{{ d.deplobjects }}</td>
                    <td class="text-center">{{ d.deplby }}</td>
                </tr>
            </tbody>
        </table>
        <dir-pagination-controls pagination-id="prodx" boundary-links="true"
            on-page-change="pageChangeHandler(newPageNumber)" template-url="/assets/templ/pagination.tpl.html">
        </dir-pagination-controls>
        <div class="modal" id="impinfo" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-body container">
                If you want to deploy objects to remote server, you have to add info about it in <a
                            href="/env/appservers" target="_parent">Servers section</a><br><br>
                        <h4>QMANAGER</h4>Before deploying objects to Qmanager, you need to:<br>
                        &nbsp;&nbsp;&nbsp;- <b>install Java Runtime Environment</b><br>
                        &nbsp;&nbsp;&nbsp;- make sure your java runtime environment is not limiting the Chiphers. You
                        can download "Java Cryptography Extension (JCE) Unlimited Strength" for your java version and
                        extract the archive in folder: $JAVA_HOME\lib\security<br>
                        &nbsp;&nbsp;&nbsp;- create a SVRCONN channel with user and give permissions to it. It is good to
                        use mqm/ibmadmin as an MCA user for the channel, but if you want to use another user, here are
                        the authority configuration that needs to be added:<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- run the script auth-qm.sh under data/mqsc folder<br>
                        &nbsp;&nbsp;&nbsp;- secure the channel with SSL and configure correct SSLPEERNAME<br>
                        &nbsp;&nbsp;&nbsp;- create a SSL keystore with the same SSLPEERNAME<br>
                        &nbsp;&nbsp;&nbsp;- save the keystore not in the web folder, and provide read access to the user
                        that is using the webserver<br>
                        &nbsp;&nbsp;&nbsp;- save the path, password and SSLCIPHER under App servers section<br>
                        <br>
                        <h4>FILE TRANSFER</h4><br><br>
                        Before creating and deploying new file transfer, you need to:<br>
                        &nbsp;&nbsp;&nbsp;- <b>do the same steps as for the QMANAGER</b><br>
                        <h4>FLOWS</h4><br><br>

                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm deplbutns" data-bs-dismiss="modal"><i
                                    class="mdi mdi-close"></i>&nbsp;Close</button>
                </div>
                </div>
                </div>
        </div>
        <div class="modal" id="modal-depl-form" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="" method="post" name="form">
                    <div class="modal-header"><h4 class="modal-title">Select a package</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"><i class="mdi mdi-close"></i></button>
                     </div>
                        <div class="modal-body container form-modal">
                            <div class="form-group">
                                <label class="form-label">Package</label>
                                <input type="text" ng-model="depl.pkgid" class="form-control autocomplpack"
                                        placeholder="write the server name" />
                                    <input name="deplpkgid" type="text" id="deplpkgid" style="display:none;" />
                                    <input type="text" name="srvid" id="srvid" style="display:none;" />
                            </div>

                            <div class="form-group" ng-show="depl.pkgid">
                                <label class="form-label">Environment</label>
                                    <select name="deplenv" ng-model="depl.env" class="form-control">
                                        <option value="">Please select</option>
                                        <?php 
     foreach($menudataenv as $keyin=>$valin) { ?>
                                        <option value="<?php echo $valin['nameshort'];?>"><?php echo $valin['name'];?>
                                        </option>
                                        <?php  }
  ?>
                                    </select>
                            </div>
                            <div class="form-group" ng-show="depl.pkgid">
                                <label class="form-label">Request Number</label>
                                <input type="text" ng-model="depl.reqname" id="reqauto" class="form-control" />
                                    <input type="text"  id="reqname" name="reqname" style="display:none;" /> 
                            </div>

                            <div style="display:block;" class="text-center">
                                <div class="loading"><svg class="circular" viewBox="25 25 50 50">
                                        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2"
                                            stroke-miterlimit="10" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-light btn-sm deplbutns" data-bs-dismiss="modal"><i class="mdi mdi-close"></i>&nbsp;Cancel</button>
                            <button type="button" name="dodeployall" ng-show="depl.env"
                                ng-click="form.$valid && deployPKG('<?php echo $thisarray['p2'];?>');"
                                class="waves-effect waves-light btn btn-info btn-sm deplbutns"><i class="mdi mdi-upload"></i>&nbsp;Deploy</button>
                           
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal" id="modal-depl-response" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" style="width:900px;">
                <div class="modal-content">
                    <div class="modal-header">Deployment output</div>
                    <div class="modal-body p-0"
                        style="width:100%;min-height:300px;max-height:500px;overflow-x:hidden;overflow-y:scroll;">
                           
                        <pre>
        <p ng-show="responsedata.error" style="white-space: normal;word-break: break-word;margin-bottom:0px;" >Error:<br>{{responsedata.errorlog}}</p>
        <p ng-show="!responsedata.error" ng-repeat="(key,val) in responsedata" style="white-space: normal;word-break: break-word;margin-bottom:0px;"> request:<br/>{{val.request}}<br/><font style="color:red;">response: {{val.response}}</font><br/>  </p>
        </pre>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i
                                class="mdi mdi-close"></i>&nbsp;Close</button>
                    </div>
                </div>
            </div>
        </div>
        <?php // include $website['corebase']."public/modules/respform.php";?>

        </div>
</div>
</div>
<?php }}} else { echo "<div class='row'><div class='col-md-2'></div><div class='col-md-8'><div class='alert alert-danger text-center'>Deploy module not found!</div></div><div class='col-md-2'></div></div>";} ?>