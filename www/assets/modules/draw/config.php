<?php
$modulelist["draw"]["name"] = "Draw.io module - create Diagrams and connections";
include_once "api.php";
include_once "diagrams.php";
class Class_drawedit
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
        include "public/modules/css.php";?>
<script type="text/javascript">
var urlParams = (function() {
    var result = new Object();
    var params = window.location.search.slice(1).split('&');
    for (var i = 0; i < params.length; i++) {
        idx = params[i].indexOf('=');
        if (idx > 0) {
            result[params[i].substring(0, idx)] = params[i].substring(idx + 1);
        }
    }
    return result;
})();
urlParams['lang'] = 'en';
if (window.location.hash != null && window.location.hash.substring(0, 2) == '#P') {
    try {
        urlParams = JSON.parse(decodeURIComponent(window.location.hash.substring(2)));

        if (urlParams.hash != null) {
            window.location.hash = urlParams.hash;
        }
    } catch (e) {
        // ignore
    }
}
(function() {
    function addMeta(name, content, httpEquiv) {
        try {
            var s = document.createElement('meta');
            if (name != null) {
                s.setAttribute('name', name);
            }
            s.setAttribute('content', content);
            if (httpEquiv != null) {
                s.setAttribute('http-equiv', httpEquiv);
            }
            var t = document.getElementsByTagName('meta')[0];
            t.parentNode.insertBefore(s, t);
        } catch (e) {
            // ignore
        }
    };
})();
</script>
<link rel="stylesheet" type="text/css" href="/assets/modules/draw/vendor/js/croppie/croppie.min.css">
<link rel="stylesheet" type="text/css" href="/assets/modules/draw/vendor/styles/grapheditor.css">
<!--[if (IE 9)|(IE 10)]><!-->
<script type="text/vbscript">
    Function mxUtilsBinaryToArray(Binary)
				Dim i
				ReDim byteArray(LenB(Binary))
				For i = 1 To LenB(Binary)
					byteArray(i-1) = AscB(MidB(Binary, i, 1))
				Next
				mxUtilsBinaryToArray = byteArray
			End Function
		</script>
<!--<![endif]-->
<script type="text/javascript">
function mxscript(src, onLoad, id, dataAppKey, noWrite) {
    if (onLoad != null || noWrite) {
        var s = document.createElement('script');
        s.setAttribute('type', 'text/javascript');
        s.setAttribute('src', '/assets/modules/draw/vendor/' + src);
        var r = false;
        if (id != null) {
            s.setAttribute('id', id);
        }
        if (dataAppKey != null) {
            s.setAttribute('data-app-key', dataAppKey);
        }
        if (onLoad != null) {
            s.onload = s.onreadystatechange = function() {
                if (!r && (!this.readyState || this.readyState == 'complete')) {
                    r = true;
                    onLoad();
                }
            };
        }
        var t = document.getElementsByTagName('script')[0];
        if (t != null) {
            t.parentNode.insertBefore(s, t);
        }
    } else {
        document.write('<script src="' + '/assets/modules/draw/vendor/' + src + '"' + ((id != null) ? ' id="' + id +
                '" ' : '') +
            ((dataAppKey != null) ? ' data-app-key="' + dataAppKey + '" ' : '') + '></scr' + 'ipt>');
    }
};
var isLocalStorage = true;
(function() {
    mxscript('js/PreConfig_midleo.js');
    mxscript('js/app.min.js');
    mxscript('js/PostConfig.js');
})();
</script>
<?php echo '</head><body class="geEditor">'; ?>
<script type="text/javascript">
App.main();
</script>
<?php include "public/modules/template_end.php";
        echo '</body></html>';
    }
}
class Class_draw
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
        if (!sessionClass::checkAcc($acclist, "designer")) {header("Location:/?");}
        if (isset($_POST["savedesdata"])) {
            $hash = textClass::getRandomStr();
            $sql = "insert into config_diagrams(tags,reqid,appcode,srvlist,appsrvlist,desid,desuser,desname,imgdata,xmldata) values(?,?,?,?,?,?,?,?,?,?)";
            $q = $pdo->prepare($sql);
            if ($q->execute(array(htmlspecialchars($_POST["tags"]), htmlspecialchars($_POST["reqname"]), htmlspecialchars($_POST["appname"]), htmlspecialchars($_POST["serverlist"]), htmlspecialchars($_POST["appserverlist"]), $hash, $_SESSION['user'], htmlspecialchars($_POST["destitle"]), "", ""))) {
                gTable::track($_SESSION["userdata"]["usname"], $_SESSION['user'], array("appid" => htmlspecialchars($_POST["appname"]), "reqid" => htmlspecialchars($_POST["reqname"]), "srvid" => htmlspecialchars($_POST["serverlist"]), "appsrvid" => htmlspecialchars($_POST["appserverlist"])), "Created new diagram <a href='/draw/" . $hash . "'>" . htmlspecialchars($_POST["destitle"]) . "</a>");
                if (!empty(htmlspecialchars($_POST["tags"]))) {
                    gTable::dbsearch($hash, $_SERVER["HTTP_REFERER"], htmlspecialchars($_POST["tags"]));
                }
                header("Location: /draw/" . $hash);
            } else {
                $err[] = "This name already exist. Please use different one";
            }
        }
        if (isset($_POST["savefd"])) {
            $sql = "update config_diagrams set desname=?, tags=?, reqid=? where desid=?";
            $q = $pdo->prepare($sql);
            $q->execute(array(htmlspecialchars($_POST["desname"]), htmlspecialchars($_POST["tags"]), htmlspecialchars($_POST["reqname"]), $thisarray['p1']));
            if (!empty(htmlspecialchars($_POST["tags"]))) {
                gTable::dbsearch($thisarray['p1'], $_SERVER["HTTP_REFERER"], htmlspecialchars($_POST["tags"]));
            }
            $msg[] = "Diagram info updated";
        }
        include "public/modules/css.php";
        echo '<link rel="stylesheet" type="text/css" href="/assets/css/jquery-ui.min.css">';
        if (!empty($thisarray['p1'])) {?>
<script type="text/javascript">
function editDiagram(image) {
    $('.formcard').hide();
    $('.drawcard').removeClass('col-md-8').addClass('col-md-12');
    var image = document.getElementById(image);
    var initial = image.getAttribute('src');
    var getRef = document.getElementById("drawid");
    var parentDiv = getRef.parentNode;
    image.setAttribute('src', '/assets/modules/draw/vendor/images/ajax-loader.gif');
    var iframe = document.createElement('iframe');
    iframe.setAttribute('frameborder', '0');
    var close = function() {
        image.setAttribute('src', initial);
        parentDiv.removeChild(iframe);
        window.removeEventListener('message', receive);
        $("#image").show();
        $('.formcard').show();
        $('.drawcard').removeClass('col-md-12').addClass('col-md-8');
    };
    var receive = function(evt) {
        if (evt.data.length > 0) {
            var msg = JSON.parse(evt.data);
            if (msg.event == 'init') {
                iframe.contentWindow.postMessage(JSON.stringify({
                    action: 'load',
                    xmlpng: initial
                }), '*');
            } else if (msg.event == 'export') {
                close();
                image.setAttribute('src', msg.data);
                save("/drawapi/editgraph", msg);
            } else if (msg.event == 'save') {
                iframe.contentWindow.postMessage(JSON.stringify({
                    action: 'export',
                    format: 'xmlpng',
                    spin: 'Updating page'
                }), '*');
            } else if (msg.event == 'exit') {
                close();
            }
        }
    };
    window.addEventListener('message', receive);
    iframe.setAttribute('src',
        '/drawedit/?embed=1&ui=atlas&spin=1&modified=unsavedChanges&proto=json&title=<?php echo $thisarray['p1']; ?>'
    );
    parentDiv.insertBefore(iframe, getRef);
    $("#image").hide();
};

function save(url, msg) {
    if (url != null) {
        var req = new XMLHttpRequest();
        req.onreadystatechange = function() {
            if (req.readyState == 4) {
                if (req.status != 200 && req.status != 201) {
                    if (wnd != null) {
                        wnd.close();
                    }
                    alert('Error ' + req.status);
                } else if (wnd != null) {
                    wnd.location.href = url;
                }
            }
        };
        msg.desid = '<?php echo $thisarray['p1']; ?>';
        req.open("POST", url, true);
        req.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        req.send(JSON.stringify(msg));
        $('.formcard').show();
        $('.drawcard').removeClass('col-md-12').addClass('col-md-8');
    }
}
</script>
<style type="text/css">
iframe {
    margin: 0;
    border: none;
    position: relaive;
    width: 100%;
    height: 800px;
}
</style>
<?php }
        echo '</head><body class="fix-header card-no-border"><div id="main-wrapper">';
        $breadcrumb["text"] = "Graph editor";
        include "public/modules/headcontent.php";?>
<div class="page-wrapper">
    <div class="container-fluid">
        <?php
$brarr = array();
array_push($brarr, array(
    "title" => "Create new diagram",
    "link" => "#mnewdes",
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
            "title" => "Import documents",
            "link" => "/docimport",
            "midicon" => "deploy",
            "active" => ($page == "docimport") ? "active" : "",
        ));
        if (sessionClass::checkAcc($acclist, "odfiles") && !empty($website['odappid'])) {
            array_push($brarr, array(
                "title" => "View/Map OneDrive files",
                "link" => "/onedrive",
                "midicon" => "onedrive",
                "active" => ($page == "onedrive") ? "active" : "",
            ));
        }
        if (sessionClass::checkAcc($acclist, "dbfiles") && !empty($website['dbclid'])) {
            array_push($brarr, array(
                "title" => "View/Map Dropbox files",
                "link" => "/dropbox",
                "midicon" => "dropbox",
                "active" => ($page == "dropbox") ? "active" : "",
            ));
        }
        ?>
        <?php if (!empty($thisarray['p1'])) {
            $sql = "SELECT tags, desuser, desname, reqid, imgdata, accgroups FROM config_diagrams where binary desid=?";
            $q = $pdo->prepare($sql);
            $q->execute(array($thisarray['p1']));
            if ($zobj = $q->fetch(PDO::FETCH_ASSOC)) {
                ?>
        <div class="row pt-3">
            <div class="col-lg-2">
                <?php  include "public/modules/sidebar.php";?></div>
            <div class="col-lg-10">
                <div class="row">
                    <?php if ($_SESSION["user"] == $zobj["desuser"] || sessionClass::checkAcc($_SESSION["userdata"]["ugroups"], str_replace(array('"',"]","["),"",$zobj["accgroups"]))) { ?>

                    <div class="col-md-9 drawcard">
                        <?php } else {?>
                        <div class="col-md-12 drawcard">
                            <?php }?>
                            <div class="card">
                                <div class="card-body" id="drawid">
                                    <img id="image" style="max-width:100%;" src="<?php echo $zobj["imgdata"]; ?>" />
                                </div>
                            </div>

                        </div>
                        <?php if ($_SESSION["user"] == $zobj["desuser"] || sessionClass::checkAcc($_SESSION["userdata"]["ugroups"], str_replace(array('"',"]","["),"",$zobj["accgroups"]))) { ?>
                        <div class="col-md-3 formcard">
                            <form method="post" action="">
                                <div class="form-group">
                                    <input type="text" placeholder="Diagram Name" name="desname"
                                        value="<?php echo $zobj["desname"]; ?>" class="form-control"
                                        placeholder="diagram name">
                                </div>
                                <div class="form-group">
                                    <input name="tags" placeholder="Tags" id="tags" data-role="tagsinput" type="text"
                                        value="<?php echo $zobj["tags"]; ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <input type="text" placeholder="Tracking ID" id="reqauto" class="form-control"
                                        value="<?php echo $zobj["reqid"]; ?>" />
                                    <input type="text" id="reqname" name="reqname" value="<?php echo $zobj["reqid"]; ?>"
                                        style="display:none;" />
                                </div>
                                <br>
                                <div class="text-start d-grid gap-2 d-md-block">
                                    <button type="button" class="btn btn-light" data-bs-toggle="tooltip"
                                        title="Embed in article"
                                        onclick="cpclip('[diagram=<?php echo $thisarray["p1"]; ?>]');"><svg
                                            class="midico midico-outline">
                                            <use href="/assets/images/icon/midleoicons.svg#i-documents"
                                                xlink:href="/assets/images/icon/midleoicons.svg#i-documents" />
                                        </svg></button>
                                    <button type="button" class="btn btn-light" data-bs-toggle="tooltip" title="Edit"
                                        onclick="editDiagram('image');"><svg class="midico midico-outline">
                                            <use href="/assets/images/icon/midleoicons.svg#i-edit"
                                                xlink:href="/assets/images/icon/midleoicons.svg#i-edit" />
                                        </svg></button>
                                    <button type="submit" name="savefd" class="btn btn-light" data-bs-toggle="tooltip"
                                        title="Save"><svg class="midico midico-outline">
                                            <use href="/assets/images/icon/midleoicons.svg#i-save"
                                                xlink:href="/assets/images/icon/midleoicons.svg#i-save" />
                                        </svg></button>
                                </div>
                                <?php }?>
                            </form>
                        </div>
                    </div>
                    <?php } else {
                textClass::PageNotFound();
            }
        } else {?>
                    <div class="row pt-3">
                        <div class="col-lg-2">
                            <?php  include "public/modules/sidebar.php";?></div>
                        <div class="col-lg-10" id="ngApp" ng-app="ngApp" ng-controller="ngCtrl">
                            <div class="row">
                                <div class="col-lg-9">
                                    <div class="card p-0">
                                        <table class="table  table-vmiddle table-hover stylish-table mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Name</th>
                                                    <th class="text-center">Created date</th>
                                                    <th class="text-center">Created by</th>
                                                    <th class="text-center">Tags</th>
                                                    <th class="text-center" style="width:60px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody ng-init="getAllDes('grapheditor')">
                                                <tr ng-hide="contentLoaded">
                                                    <td colspan="5" style="text-align:center;font-size:1.1em;"><i
                                                            class="mdi mdi-loading iconspin"></i>&nbsp;Loading...</td>
                                                </tr>
                                                <tr id="contloaded" class="hide"
                                                    dir-paginate="d in names | filter:search | orderBy:'-released' | itemsPerPage:10"
                                                    pagination-id="prodx" ng-hide="d.deslatname==''">
                                                    <td class="text-center"><a
                                                            href="/draw/{{ d.desid}}">{{ d.desname}}</a></td>
                                                    <td class="text-center">{{ d.desdate }}</td>
                                                    <td class="text-center">{{ d.desuser }}</td>
                                                    <td class="text-center"><a ng-repeat="tag in d.tags.split(',')"
                                                            class="badge badge-secondary waves-effect"
                                                            style="margin-right:5px;margin-top:5px;"
                                                            href="/searchall/?sa=y&st=tag&fd={{ tag }}">{{ tag }}</a>
                                                    </td>
                                                    <td>
                                                        <?php if ($_SESSION['user_level'] >= "5") {?>
                                                        <button type="button"
                                                            ng-click="deldes(d.id,d.desid,'<?php echo $_SESSION["user"]; ?>')"
                                                            class="btn btn-light btn-sm bg waves-effect"><svg
                                                                class="midico midico-outline">
                                                                <use href="/assets/images/icon/midleoicons.svg#i-trash"
                                                                    xlink:href="/assets/images/icon/midleoicons.svg#i-trash" />
                                                            </svg></button>
                                                        <?php } else {?>
                                                        <button type="button"
                                                            class="btn btn-light btn-sm bg waves-effect"><i
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
                                <div class="col-lg-3">
                                <?php include "public/modules/filterbar.php"; ?>
                                    <?php include "public/modules/breadcrumbin.php";?>
                                </div>
                            </div>
                        </div>
                        <div class="modal" id="mnewdes" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form name="form" action="" method="post">
                                        <div class="modal-header text-center">
                                            <h4>Create new diagram</h4>
                                        </div>
                                        <div class="modal-body"><br>
                                            <div class="form-group row">
                                                <label class="form-control-label text-lg-right col-md-3"
                                                    for="diagram_title">Name</label>
                                                <div class="col-md-9"><input type="text" id="diagram_title"
                                                        name="destitle" class="form-control" required /> </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="form-control-label text-lg-right col-md-3">Tags</label>
                                                <div class="col-md-9"><input name="tags" id="tags" data-role="tagsinput"
                                                        type="text" class="form-control"></div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="form-control-label text-lg-right col-md-3">Request
                                                    Number</label>
                                                <div class="col-md-9"> <input type="text" id="reqauto"
                                                        class="form-control" required />
                                                    <input type="text" id="reqname" name="reqname"
                                                        style="display:none;" />
                                                </div>
                                            </div>
                                            <input type="hidden" name="destype" value="grapheditor">
                                            <div class="form-group row">
                                                <label
                                                    class="form-control-label text-lg-right col-md-3">Application</label>
                                                <div class="col-md-9">
                                                    <input type="text" id="applauto" class="form-control" required
                                                        placeholder="write the application name or code" />
                                                    <input type="text" id="appname" name="appname"
                                                        style="display:none;" />
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="form-control-label text-lg-right col-md-3">Server</label>
                                                <div class="col-md-7">
                                                    <input type="text" class="form-control autocomplsrv"
                                                        placeholder="write the server name" />
                                                    <input type="text" name="server" id="server"
                                                        style="display:none;" />
                                                    <input type="text" name="serverid" id="serverid"
                                                        style="display:none;" />
                                                    <input type="text" name="serverip" id="serverip"
                                                        style="display:none;" />
                                                    <input type="text" name="serverlist" id="serverlist"
                                                        style="display:none;" />
                                                    <input type="text" name="serverlistnames" id="serverlistnames"
                                                        style="display:none;" />
                                                    <div id="srvlistnames"></div>
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-light btn-sm"
                                                        onclick="mkSrvlist()"><i
                                                            class="mdi mdi-plus mdi-24px"></i></button>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="form-control-label text-lg-right col-md-3">Application
                                                    Server</label>
                                                <div class="col-md-7">
                                                    <input type="text" class="form-control autocomplappsrv"
                                                        placeholder="write the applicaiton server name" />
                                                    <input type="text" name="appserver" id="appserver"
                                                        style="display:none;" />
                                                    <input type="text" name="appserverid" id="appserverid"
                                                        style="display:none;" />
                                                    <input type="text" name="appserverlist" id="appserverlist"
                                                        style="display:none;" />
                                                    <input type="text" name="appserverlistnames" id="appserverlistnames"
                                                        style="display:none;" />
                                                    <div id="appsrvlistnames"></div>
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-light btn-sm"
                                                        onclick="mkAppSrvlist()"><i
                                                            class="mdi mdi-plus mdi-24px"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary btn-sm"
                                                data-bs-dismiss="modal"><svg class="midico midico-outline">
                                                    <use href="/assets/images/icon/midleoicons.svg#i-x"
                                                        xlink:href="/assets/images/icon/midleoicons.svg#i-x" />
                                                </svg>&nbsp;Close</button>
                                            <button class="btn btn-info btn-sm" type="submit" name="savedesdata"><i
                                                    class="mdi mdi-content-save"></i>&nbsp;Create</button>&nbsp;
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php }?>
                    </div>
                </div>
                <?php include "public/modules/footer.php";
        echo "</div></div>";
        include "public/modules/js.php";
        echo '<script src="/assets/js/tagsinput.min.js" type="text/javascript"></script>';
        if (empty($thisarray['p1'])) {
            echo '<script  type="text/javascript" src="/assets/js/dirPagination.js"></script><script type="text/javascript" src="/assets/js/ng-controller.js"></script>';
        }
        include "public/modules/template_end.php";
        echo '</body></html>';
    }
}