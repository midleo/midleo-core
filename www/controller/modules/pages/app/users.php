<div id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
<div class="row">
                  <div class="col-md-3 position-relative">
                      <input type="text" ng-model="search" class="form-control topsearch" placeholder="Find a user">
                      <span class="searchicon"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-search" xlink:href="/assets/images/icon/midleoicons.svg#i-search"/></svg></span>
                  </div>
                  <div class="col-md-9 text-end">
                  <?php if($_SESSION['user_level']>=3){?> 
                  <a data-bs-toggle="modal" class="waves-effect waves-light btn btn-info"  href="#modal-user-form" ng-click="showCreateUser()"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-add" xlink:href="/assets/images/icon/midleoicons.svg#i-add"/></svg>&nbsp;New</a>
                  <?php } ?>
                  </div>
                  </div><br>

<div class="card" >
        <div class="card-body p-0"> 
      
                  
                  <div class="row"><div class="col-md-12">
                    <table class="table  table-vmiddle table-hover stylish-table">
                      <thead>
                        <tr>
                        <th class="text-center" style="width:60px;"></th>
                          <th class="text-center">Fullname</th>
                          <th class="text-center">User</th>
                          <th class="text-center">Rights</th>
                          <th class="text-center" style="width:100px;"></th>
                        </tr>
                      </thead>
                      <tbody ng-init="getAllusers('<?php echo $_SESSION['user'];?>')">
                        <tr ng-hide="contentLoaded"><td colspan="5" style="text-align:center;font-size:1.1em;"><i class="mdi mdi-loading iconspin"></i>&nbsp;Loading...</td></tr>
                        <tr id="contloaded" class="hide" dir-paginate="d in names | filter:search | orderBy:'fullname':reverse | itemsPerPage:10" pagination-id="prodx">
                        <td class="text-center" style="padding: .5rem;"><span class="uavatar" style="background-color:{{d.uacolor}}">{{d.shortname}}</span></td>
                          <td class="text-center">{{ d.fullname | limitTo:textlimit }}{{d.fullname.length > textlimit ? '...' : ''}}</td>
                          <td class="text-center">{{ d.name | limitTo:textlimit }}{{d.name.length > textlimit ? '...' : ''}}</td>
                          <td class="text-center">{{ d.acc | limitTo:textlimit }}{{d.acc.length > textlimit ? '...' : ''}}</td>
                          <td>
                          <div class="btn-group" role="group">
                             <button ng-click="readusr(d.name,'<?php echo $_SESSION['user'];?>')" type="button"  class="btn waves-effect btn-sm btn-light"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-edit" xlink:href="/assets/images/icon/midleoicons.svg#i-edit"/></svg></button>
                             <?php if($_SESSION['user_level']>=3){?><button ng-click="delusr(d.name,'<?php echo $_SESSION['user'];?>')" type="button" class="btn waves-effect btn-light btn-sm" ><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-trash" xlink:href="/assets/images/icon/midleoicons.svg#i-trash"/></svg></button><?php } ?>
                              </div>
                            </td>
                        </tr>
                      </tbody>
                    </table>
                    <dir-pagination-controls pagination-id="prodx" boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="/assets/templ/pagination.tpl.html"></dir-pagination-controls>
                    <?php if($_SESSION['user_level']>=3){?><div class="modal" id="modal-user-form" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                      <div class="modal-header">
        <h4>Create/edit user</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
                        <form name="form" ng-app>
                          <div class="modal-body form-horizontal form-material">
                          
                          <div class="form-group row" ng-show="user.shortname">
                          <div class="col-md-3"><span class="uavatar float-end uabig" style="background-color:{{user.uacolor}}">{{user.shortname}}</span></div>
                          <div class="col-md-9"></div>
                          </div>
                             <div class="form-group row">
                              <label  class="form-control-label text-lg-right col-md-3" ng-class="{'has-error':!user.name}">User</label>
                              <div class="col-md-9"><input ng-required="true" ng-model="user.name" type="text" class="form-control"></div>
                            </div>
                            <div class="form-group row" ng-hide="user.authtype">
                              <label class="form-control-label text-lg-right col-md-3">Fullname</label>
                              <div class="col-md-9"><input ng-model="user.fullname" type="text" class="form-control"></div>
                            </div>
                            <div class="form-group row" ng-hide="user.authtype">
                              <label class="form-control-label text-lg-right col-md-3">Email</label>
                              <div class="col-md-9"><input ng-model="user.email" type="text" class="form-control"></div>
                            </div>
                            <?php $sql="select ldapserver,ldapinfo from ldap_config";
                                         $q = $pdo->prepare($sql);              
                                         $q->execute();      
                                         if($zobj = $q->fetchAll()){?>
                            <div class="form-group row ldap-group">
                              <label class="form-control-label text-lg-right col-md-3">Auth type</label>
                              <div class="col-md-9"> <select class="form-control" ng-model="user.authtype"><option value="">Default</option>
                                <?php foreach($zobj as $val) { ?><option value="<?php echo $val['ldapserver'];?>"><?php echo $val['ldapinfo'];?></option><?php } ?></select></div>
                            </div>
                            <?php } ?>
                            <div class="form-group row">
                              <label  class="form-control-label text-lg-right col-md-3" >Title</label>
                              <div class="col-md-9"><input class="form-control" ng-model="user.title" type="text"></div>
                            </div>
                            <div class="form-group row">
                              <label  class="form-control-label text-lg-right col-md-3" ng-class="{'has-error':!user.rights}">Rights</label>
                              <div class="col-md-9"><select class="form-control" ng-model="user.rights"><option value="">Please select</option>
                              <?php foreach($accrights as $keyin=>$valin){?><option value="<?php echo $keyin;?>"><?php echo $valin;?></option><?php } ?></select></div>
                            </div>
                            <div class="form-group row" ng-hide="user.authtype">
                              <label class="form-control-label text-lg-right col-md-3">Password</label>
                              <div class="col-md-9"><input ng-model="user.pass" type="password" class="form-control"></div>
                            </div>
                          </div>
                          <div class="modal-footer">
                          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x"/></svg>&nbsp;Close</button>
                            <a id="btn-create-user"  ng-hide="user.authtype" class="waves-effect btn btn-info btn-sm" ng-click="form.$valid && createuser('<?php echo $_SESSION['user'];?>')"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-check" xlink:href="/assets/images/icon/midleoicons.svg#i-check"/></svg>&nbsp;Create</a>
                            <a id="btn-create-user-ldap"  ng-show="user.authtype" class="waves-effect  btn btn-info btn-sm" ng-click="form.$valid && createuserldap('<?php echo $_SESSION['user'];?>')"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-check" xlink:href="/assets/images/icon/midleoicons.svg#i-check"/></svg>&nbsp;Add</a>
                            <a id="btn-update-user" class="waves-effect btn btn-info btn-sm" ng-click="form.$valid && updateuser('<?php echo $_SESSION['user'];?>')"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-save" xlink:href="/assets/images/icon/midleoicons.svg#i-save"/></svg>&nbsp;Save</a>
                           </div>
                        </form>
                      </div>
                    </div>
                    </div><?php } ?>
                    </div>
              </div>
                 </div>

               </div>