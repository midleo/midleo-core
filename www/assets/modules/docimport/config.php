<?php
$modulelist["docimport"]["name"] = "Import Documents";
include_once "api.php";
class Class_docimport
{
    public static function getPage($thisarray)
    {
        global $installedapp;
        global $website;
        global $page;
        global $modulelist;
        global $maindir;
        if ($installedapp != "yes") {header("Location: /install");}
        sessionClass::page_protect(base64_encode("http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
        $err = array();
        $msg = array();
        $pdo = pdodb::connect();
        $data = sessionClass::getSessUserData();foreach ($data as $key => $val) {${$key} = $val;}
        include "public/modules/css.php";
        echo '</head><body class="fix-header card-no-border"><div id="main-wrapper">';
        $breadcrumb["text"] = "Import documents";
        include "public/modules/headcontent.php";?>
<div class="page-wrapper">
    <div class="container-fluid">
        <?php
$brarr = array();
        array_push($brarr, array(
            "title" => "Import documents",
            "link" => "#mnewimp",
            "midicon" => "add",
            "modal" => true, 
            "active" => false,
        ));
        array_push($brarr, array(
            "title" => "Create/edit articles",
            "link" => "/cpinfo",
            "midicon" => "kn-b",
            "active" => ($page == "cpinfo") ? "active" : "",
        ));
        array_push($brarr, array(
            "title" => "View/Edit diagrams",
            "link" => "/draw",
            "midicon" => "diagram",
            "active" => ($page == "draw") ? "active" : "",
        ));
        if (sessionClass::checkAcc($acclist, "odfiles")) {
            array_push($brarr, array(
                "title" => "View/Map OneDrive files",
                "link" => "/onedrive",
                "midicon" => "onedrive",
                "active" => ($page == "onedrive") ? "active" : "",
            ));
        }
        if (sessionClass::checkAcc($acclist, "dbfiles")) {
            array_push($brarr, array(
                "title" => "View/Map Dropbox files",
                "link" => "/dropbox",
                "midicon" => "dropbox",
                "active" => ($page == "dropbox") ? "active" : "",
            ));
        }
        ?>
        <div class="row pt-3">
            <div class="col-lg-2">
                <?php  include "public/modules/sidebar.php";?></div>
            <div class="col-lg-10" id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
                <div class="row">
                    <div class="col-lg-9">
                        <div class="card p-0">
                            <table class="table  table-vmiddle table-hover stylish-table"
                                style="margin-top:0px !important;">
                                <thead>
                                    <tr>
                                        <th class="text-center">File</th>
                                        <th class="text-center">Imported date</th>
                                        <th class="text-center">Imported by</th>
                                        <th class="text-center">Tags</th>
                                        <th class="text-center" style="width:60px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody ng-init="getAlldocs()">
                                    <tr ng-hide="contentLoaded">
                                        <td colspan="5" style="text-align:center;font-size:1.1em;"><i
                                                class="mdi mdi-loading iconspin"></i>&nbsp;Loading...</td>
                                    </tr>
                                    <tr id="contloaded" class="hide"
                                        dir-paginate="d in names | filter:search | orderBy:'-released' | itemsPerPage:10"
                                        pagination-id="prodx" ng-hide="d.fileid==''">
                                        <td class="text-center">{{ d.fileid}}</td>
                                        <td class="text-center">{{ d.importedon }}</td>
                                        <td class="text-center"><a href="/browse/user/{{ d.author }}"
                                                target="_blank">{{ d.author }}</a></td>
                                        <td class="text-center"><a ng-repeat="tag in d.tags.split(',')"
                                                class="btn btn-sm btn-info waves-effect"
                                                style="margin-right:5px;margin-top:5px;"
                                                href="/searchall/?sa=y&st=tag&fd={{ tag }}">{{ tag }}</a></td>
                                        <td>
                                            <?php if ($_SESSION['user_level'] >= "5") {?>
                                            <button type="button" ng-click="deldoc(d.id,d.fileorigid)"
                                                class="btn btn-light btn-sm bg waves-effect"><svg
                                                    class="midico midico-outline">
                                                    <use href="/assets/images/icon/midleoicons.svg#i-trash"
                                                        xlink:href="/assets/images/icon/midleoicons.svg#i-trash" />
                                                </svg></button>
                                            <?php } else {?>
                                            <button type="button" class="btn btn-light btn-sm bg waves-effect"><i
                                                    class="mdi mdi-close"></i></button>
                                            <?php }?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <dir-pagination-controls pagination-id="prodx" boundary-links="true"
                                on-page-change="pageChangeHandler(newPageNumber)"
                                template-url="/assets/templ/pagination.tpl.html"></dir-pagination-controls>
                        </div>
                    </div>
                    <div class="modal" id="mnewimp" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header text-center">
                                    <h4>Batch import</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-sm-7">
                                            <input type="text" ng-model="doc.fileloc" class="form-control"
                                                placeholder="File location" aria-label="File location">
                                        </div>
                                        <div class="col-sm">
                                            <select class="form-select" ng-model="doc.filetype"
                                                aria-label="document type">
                                                <option value="">Type</option>
                                                <option value="docx">.docx</option>
                                                <option value="doc">.doc</option>
                                                <option value="odt">.odt</option>
                                                <option value="html">.html</option>
                                                <option value="pdf">.pdf</option>
                                            </select>
                                        </div>
                                        <div class="col-sm">
                                            <button type="button" class="btn btn-light"
                                                ng-click="scandir()">Scan</button>
                                        </div>
                                    </div>
                                    <hr>
                                    <ul class="list-group listdoc list-group-flush"
                                        style="width:100%;min-height:300px;max-height:500px;overflow-x:hidden;overflow-y:scroll;"
                                        ng-if="doclist">
                                        <li class="list-group-item" ng-repeat="(key, val) in doclist"
                                            ng-class="key<=tempkey?'list-group-item-success':''">{{val}}</li>
                                    </ul>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-info btn-sm" ng-if="doclist" type="button"
                                        ng-click="startImport()"><i
                                            class="mdi mdi-content-save"></i>&nbsp;Start</button>&nbsp;
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><svg
                                            class="midico midico-outline">
                                            <use href="/assets/images/icon/midleoicons.svg#i-x"
                                                xlink:href="/assets/images/icon/midleoicons.svg#i-x" />
                                        </svg>&nbsp;Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div style="display:block;">
                            <input type="text" ng-model="search" class="form-control  topsearch"
                                placeholder="find a document">
                            <span class="searchicon"><svg class="midico midico-outline">
                                    <use href="/assets/images/icon/midleoicons.svg#i-search"
                                        xlink:href="/assets/images/icon/midleoicons.svg#i-search" />
                                </svg><span>
                        </div>
                        <?php include "public/modules/breadcrumbin.php";?>
                    </div>
                </div>
            </div>
        </div>
        <?php include "public/modules/footer.php";
        echo "</div></div>";
        include "public/modules/js.php";
        echo '<script src="/assets/js/tagsinput.min.js" type="text/javascript"></script>';
        echo '<script  type="text/javascript" src="/assets/js/dirPagination.js"></script><script type="text/javascript" src="/assets/modules/docimport/assets/js/ng-controller.js"></script>';
        include "public/modules/template_end.php";
        echo '</body></html>';

    }
}