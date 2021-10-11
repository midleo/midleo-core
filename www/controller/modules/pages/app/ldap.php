<?php if (method_exists("ldap", "ldapconn") && is_callable(array("ldap", "ldapconn"))){ ?>
  <?php
array_push($brarr,array(
    "title"=>"Add new Ldap server",
    "link"=>"#modaladdldap",
    "modal"=>true,
    "midicon"=>"add",
    "active"=>true,
  ));
  ?>
            
                <form method="post" action="">
                   


                    <div class="modal" id="modaladdldap" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">Ldap server configuration</div>
                                <div class="modal-body form-horizontal form-material">
                                    <div class="form-group row">
                                        <label class="form-control-label text-lg-right col-md-4">Ldap server</label>
                                        <div class="col-md-8"> <input type="text" name="ldapserver" value=""
                                                class="form-control" placeholder="eg. ldap.server.lan"></div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="form-control-label text-lg-right col-md-4">Ldap port</label>
                                        <div class="col-md-8"> <input type="text" name="ldapport" value=""
                                                class="form-control" placeholder="eg 389"></div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="form-control-label text-lg-right col-md-4">Users ldap tree</label>
                                        <div class="col-md-8"> <input type="text" name="ldaptree" value=""
                                                class="form-control" placeholder="eg. ou=people,dc=domain,dc=com"></div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="form-control-label text-lg-right col-md-4">Groups ldap
                                            tree</label>
                                        <div class="col-md-8"> <input type="text" name="ldapgtree" value=""
                                                class="form-control" placeholder="eg. ou=groups,dc=domain,dc=com"></div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="form-control-label text-lg-right col-md-4">Information</label>
                                        <div class="col-md-8"> <input type="text" name="ldapinfo" value=""
                                                class="form-control" placeholder="Domain Com"></div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="addldap" class="btn btn-light btn-sm"><svg
                                            class="midico midico-outline">
                                            <use href="/assets/images/icon/midleoicons.svg#i-save"
                                                xlink:href="/assets/images/icon/midleoicons.svg#i-save" />
                                        </svg>&nbsp;Save</button>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><svg
                                            class="midico midico-outline">
                                            <use href="/assets/images/icon/midleoicons.svg#i-x"
                                                xlink:href="/assets/images/icon/midleoicons.svg#i-x" />
                                        </svg>&nbsp;Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php $sql="select id,ldapserver,ldapinfo from ldap_config";
$q = $pdo->prepare($sql);
  $q->execute();
  if($zobj = $q->fetchAll()){     ?>
  <div class="card p-0">
                    <table class="table table-vmiddle table-hover stylish-table mb-0">
                            <tbody>
                                <?php foreach($zobj as $val) { 
    echo "<tr id=\"".$val['id']."\"><td style='padding:15px;'>".$val['ldapserver']." (".$val['ldapinfo'].")</td><td style='width:50px;vertical-align:middle;'><button type='button' class='btn btn-danger btn-sm' onclick=\"delldap('".$val['ldapserver']."','".$val['id']."')\"><i class='mdi mdi-close'></i></button></td></tr>";
  } ?>
                            </tbody>
                        </table>
                        </div>
                    <?php } else { echo "<div class='alert alert-light col-6'>There are no ldap servers configured yet</div>";} ?>

                </form>
            

<?php } ?>