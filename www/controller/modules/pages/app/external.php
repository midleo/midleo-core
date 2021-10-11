<?php
  array_push($brarr,array(
    "title"=>"Save the configurtion",
    "link"=>"javascript:void(0)",
    "onclick"=>"document.getElementById('saveadm').click();",
    "midicon"=>"save",
    "active"=>true,
  ));
?>
<div class="row">
    <div class="col-md-8">
        <form method="post" action="" class="form-material">
            <?php if (method_exists("vc", "gitAdd") && is_callable(array("vc", "gitAdd"))){ ?>
            <div class="card">
                <div class="card-header">
                    <h4><i class="mdi mdi-git"></i>&nbsp;Version control
                        <div class="ribbon ribbon-default ribbon-right" style="font-size:small;"><svg
                                class="midico midico-outline">
                                <use href="/assets/images/icon/midleoicons.svg#i-warning"
                                    xlink:href="/assets/images/icon/midleoicons.svg#i-warning" />
                            </svg>&nbsp;for package automation
                        </div>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-4">GIT Type</label>
                        <div class="col-md-8">
                            <div class="btn-group" role="group" aria-label="git">
                                <input type="radio" id="gitgrgithub" class="btn-check" ng-click="ext.gittype='github'"
                                    name="conf#gittype" value="github"
                                    <?php echo $website['gittype']=="github"?"checked":"";?>>
                                <label class="btn btn-light btn-sm" for="gitgrgithub"><i
                                        class='mdi mdi-github'></i>&nbsp;GitHub</label>
                                <input type="radio" id="gitgrgitlab" class="btn-check" ng-click="ext.gittype='gitlab'"
                                    name="conf#gittype" value="gitlab"
                                    <?php echo $website['gittype']=="gitlab"?"checked":"";?>>
                                <label class="btn btn-light btn-sm" for="gitgrgitlab"><i
                                        class='mdi mdi-gitlab'></i>&nbsp;GitLab</label>
                                <input type="radio" id="gitgrbit" class="btn-check" ng-click="ext.gittype='bitbucket'"
                                    name="conf#gittype" value="bitbucket"
                                    <?php echo $website['gittype']=="bitbucket"?"checked":"";?>>
                                <label class="btn btn-light btn-sm" for="gitgrbit"><i
                                        class='mdi mdi-bitbucket'></i>&nbsp;Bitbucket</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row" ng-show="ext.gittype=='gitlab'">
                        <label class="form-control-label text-lg-right col-md-4">Gitlab Base URL</label>
                        <div class="col-md-8">
                            <input type="text" name="conf#gitreposurl"
                                value="https://<?php echo str_replace("https://","",isset($_POST['conf#gitreposurl'])?$_POST['conf#gitreposurl']:$website['gitreposurl']);?>"
                                class="form-control">
                        </div>
                    </div>
                    <div class="form-group row" ng-show="ext.gittype=='bitbucket'">
                        <label class="form-control-label text-lg-right col-md-4">Bitbucket API URL</label>
                        <div class="col-md-8">
                            <input type="text" name="conf#gitbbapi"
                                value="https://<?php echo str_replace("https://","",isset($_POST['conf#gitbbapi'])?$_POST['conf#gitbbapi']:$website['gitbbapi']);?>"
                                class="form-control">
                        </div>
                    </div>
                    <div class="form-group row" ng-show="ext.gittype=='github'">
                        <label class="form-control-label text-lg-right col-md-4">GitHub API URL</label>
                        <div class="col-md-8">
                            <input type="text" name="conf#githubapi"
                                value="https://<?php echo str_replace("https://","",isset($_POST['conf#githubapi'])?$_POST['conf#githubapi']:$website['githubapi']);?>"
                                class="form-control">
                        </div>
                    </div>
                    <div class="form-group row" ng-show="ext.gittype=='gitlab'">
                        <label class="form-control-label text-lg-right col-md-4">GitLab project ID</label>
                        <div class="col-md-8">
                            <input type="text" name="conf#gitpjid"
                                value="<?php echo isset($_POST['conf#gitpjid'])?$_POST['conf#gitpjid']:$website['gitpjid'];?>"
                                class="form-control">
                        </div>
                    </div>
                    <div class="form-group row " ng-show="ext.gittype=='gitlab'">
                        <label class="form-control-label text-lg-right col-md-4">Personal Access Token</label>
                        <div class="col-md-8">
                            <input type="text" name="conf#gittoken"
                                value="<?php echo isset($_POST['conf#gittoken'])?$_POST['conf#gittoken']:$website['gittoken'];?>"
                                class="form-control">
                        </div>
                    </div>
                    <div class="form-group row " ng-show="ext.gittype=='github'">
                        <label class="form-control-label text-lg-right col-md-4">Personal Access Token</label>
                        <div class="col-md-8">
                            <input type="text" name="conf#githtoken"
                                value="<?php echo isset($_POST['conf#githtoken'])?$_POST['conf#githtoken']:$website['githtoken'];?>"
                                class="form-control">
                        </div>
                    </div>
                    <div class="form-group row " ng-show="ext.gittype=='bitbucket'">
                        <label class="form-control-label text-lg-right col-md-4">Repo owner</label>
                        <div class="col-md-8">
                            <input type="text" name="conf#gitbbowner"
                                value="<?php echo isset($_POST['conf#gitbbowner'])?$_POST['conf#gitbbowner']:$website['gitbbowner'];?>"
                                class="form-control">
                        </div>
                    </div>
                    <div class="form-group row " ng-show="ext.gittype=='bitbucket'">
                        <label class="form-control-label text-lg-right col-md-4">Bitbucket repo</label>
                        <div class="col-md-8">
                            <input type="text" name="conf#gitbbrepo"
                                value="<?php echo isset($_POST['conf#gitbbrepo'])?$_POST['conf#gitbbrepo']:$website['gitbbrepo'];?>"
                                class="form-control">
                        </div>
                    </div>
                    <div class="form-group row " ng-show="ext.gittype=='bitbucket'">
                        <label class="form-control-label text-lg-right col-md-4">Bitbucket user</label>
                        <div class="col-md-8">
                            <input type="text" name="conf#gitbbuser"
                                value="<?php echo isset($_POST['conf#gitbbuser'])?$_POST['conf#gitbbuser']:$website['gitbbuser'];?>"
                                class="form-control">
                        </div>
                    </div>
                    <div class="form-group row " ng-show="ext.gittype=='bitbucket'">
                        <label class="form-control-label text-lg-right col-md-4">Bitbucket App password</label>
                        <div class="col-md-8">
                            <input type="text" name="conf#gitbbapppwd"
                                value="<?php echo isset($_POST['conf#gitbbapppwd'])?$_POST['conf#gitbbapppwd']:$website['gitbbapppwd'];?>"
                                class="form-control">
                        </div>
                    </div>
                    <div class="form-group row " ng-show="ext.gittype=='github'">
                        <label class="form-control-label text-lg-right col-md-4">Repo owner</label>
                        <div class="col-md-8">
                            <input type="text" name="conf#githubowner"
                                value="<?php echo isset($_POST['conf#githubowner'])?$_POST['conf#githubowner']:$website['githubowner'];?>"
                                class="form-control">
                        </div>
                    </div>
                    <div class="form-group row " ng-show="ext.gittype=='github'">
                        <label class="form-control-label text-lg-right col-md-4">Github repo</label>
                        <div class="col-md-8">
                            <input type="text" name="conf#githubrepo"
                                value="<?php echo isset($_POST['conf#githubrepo'])?$_POST['conf#githubrepo']:$website['githubrepo'];?>"
                                class="form-control">
                        </div>
                    </div>
                    <button type="submit" name="saveadm" id="saveadm" style="display:none;" >save</button>

                </div>
            </div>
            <?php } ?>
            <?php if (method_exists("Class_onedrive", "getPage") && is_callable(array("Class_onedrive", "getPage"))){ ?>
            <div class="card">
                <div class="card-header">
                    <h4><svg class="midico midico-outline">
                            <use href="/assets/images/icon/midleoicons.svg#i-onedrive"
                                xlink:href="/assets/images/icon/midleoicons.svg#i-onedrive" />
                        </svg>&nbsp;Microsoft OneDrive
                        <div class="ribbon ribbon-default ribbon-right" style="font-size:small;"><svg
                                class="midico midico-outline">
                                <use href="/assets/images/icon/midleoicons.svg#i-warning"
                                    xlink:href="/assets/images/icon/midleoicons.svg#i-warning" />
                            </svg>&nbsp;For OneDrive documents collaboration
                        </div>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-4">Redirect URIs</label>
                        <div class="col-md-8">
                            <p class="form-control-static text-uppercase">
                                <?php echo $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["HTTP_HOST"];?>/onedrive/auth</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-4">Application (client) ID</label>
                        <div class="col-md-8">
                            <input type="text" name="conf#odappid"
                                value="<?php echo isset($_POST['conf#odappid'])?$_POST['conf#odappid']:$website['odappid'];?>"
                                class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-4">Directory (tenant) ID</label>
                        <div class="col-md-8">
                            <input type="text" name="conf#odtenid"
                                value="<?php echo isset($_POST['conf#odtenid'])?$_POST['conf#odtenid']:$website['odtenid'];?>"
                                class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-4">OAuth 2.0 authorization
                            endpoint</label>
                        <div class="col-md-8">
                            <input type="text" name="conf#odauthep"
                                value="<?php echo isset($_POST['conf#odauthep'])?$_POST['conf#odauthep']:$website['odauthep'];?>"
                                class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
            <?php if (method_exists("Class_dropbox", "getPage") && is_callable(array("Class_dropbox", "getPage"))){ ?>
            <div class="card">
                <div class="card-header">
                    <h4><svg class="midico midico-outline">
                            <use href="/assets/images/icon/midleoicons.svg#i-dropbox"
                                xlink:href="/assets/images/icon/midleoicons.svg#i-dropbox" />
                        </svg>&nbsp;Dropbox
                        <div class="ribbon ribbon-default ribbon-right" style="font-size:small;"><svg
                                class="midico midico-outline">
                                <use href="/assets/images/icon/midleoicons.svg#i-warning"
                                    xlink:href="/assets/images/icon/midleoicons.svg#i-warning" />
                            </svg>&nbsp;For Dropbox documents collaboration
                        </div>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-4">Redirect URIs</label>
                        <div class="col-md-8">
                            <p class="form-control-static text-uppercase">
                                <?php echo $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["HTTP_HOST"];?>/dropbox</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-control-label text-lg-right col-md-4">Client ID</label>
                        <div class="col-md-8">
                            <input type="text" name="conf#dbclid"
                                value="<?php echo isset($_POST['conf#dbclid'])?$_POST['conf#dbclid']:$website['dbclid'];?>"
                                class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </form>
    </div>
    <div class="col-md-4">

        <div class="card">
            <div class="card-header">
                <h4>Eternal module Status</h4>
            </div>
            <div class="card-body">

                <table class="table browser mt-3 table-borderless">
                    <tbody>
                        <tr>
                            <td style="width:40px"><i class="mdi mdi-git mdi-24px"></i></td>
                            <td>Version Control</td>
                            <td class="text-end"><span
                                    class="badge badge-<?php echo !empty($website['gitreposurl'])?"success":"danger";?>"><?php echo !empty($website['gittype'])?$website['gittype']:"Disabled";?></span>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:40px"><svg class="midico midico-outline">
                                    <use href="/assets/images/icon/midleoicons.svg#i-onedrive"
                                        xlink:href="/assets/images/icon/midleoicons.svg#i-onedrive" />
                                </svg></td>
                            <td>Microsoft OneDrive</td>
                            <td class="text-end"><span
                                    class="badge badge-<?php echo !empty($website['odappid'])?"success":"danger";?>"><?php echo !empty($website['odappid'])?"Enabled":"Disabled";?></span>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:40px"><svg class="midico midico-outline">
                                    <use href="/assets/images/icon/midleoicons.svg#i-dropbox"
                                        xlink:href="/assets/images/icon/midleoicons.svg#i-dropbox" />
                                </svg></td>
                            <td>Dropbox</td>
                            <td class="text-end"><span
                                    class="badge badge-<?php echo !empty($website['dbclid'])?"success":"danger";?>"><?php echo !empty($website['dbclid'])?"Enabled":"Disabled";?></span>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>