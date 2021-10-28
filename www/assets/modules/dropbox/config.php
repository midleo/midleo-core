<?php
$modulelist["dropbox"]["name"] = "Dropbox controller";
$modulelist["dropbox"]["js"][] = str_replace($maindir, "", dirname($filename)) . "/assets/js/Dropbox-sdk.min.js";
$modulelist["dropbox"]["js"][] = str_replace($maindir, "", dirname($filename)) . "/assets/js/fetch.js";
$modulelist["dropbox"]["js"][] = str_replace($maindir, "", dirname($filename)) . "/assets/js/polyfill.min.js";
$modulelist["dropbox"]["js"][] = str_replace($maindir, "", dirname($filename)) . "/assets/js/utils.js";
include_once "api.php";
class Class_dropbox
{
    public static function getPage()
    {
        global $installedapp;
        global $website;
        global $page;
        global $modulelist;
        global $maindir;
        if ($installedapp != "yes") {header("Location: /install");}
        session_start();
        if (!empty($_SESSION["user"])) {sessionClass::page_protect(base64_encode("http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));}
        if (!empty($_SESSION["requser"])) {sessionClassreq::page_protectreq(base64_encode("http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));}
        $err = array();
        $msg = array();
        $pdo = pdodb::connect();
        if (!empty($_SESSION["user"])) {$data = sessionClass::getSessUserData();foreach ($data as $key => $val) {${$key} = $val;}}
        if (!empty($_SESSION["requser"])) {$data = sessionClassreq::getSessUserData();foreach ($data as $key => $val) {${$key} = $val;}}
        include $website['corebase']."public/modules/css.php";
        foreach ($modulelist["dropbox"]["js"] as $jskey => $jslink) {
            if (!empty($jslink)) {?><script type="text/javascript" src="<?php echo $jslink; ?>"></script>
<?php }
        }
        echo '<link rel="stylesheet" type="text/css" href="/'.$website['corebase'].'assets/js/datatables/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="/'.$website['corebase'].'assets/js/datatables/responsive.dataTables.min.css">';
        echo '<link rel="stylesheet" type="text/css" href="/'.$website['corebase'].'assets/css/jquery-ui.min.css">';
        echo '</head><body class="fix-header card-no-border"><div id="main-wrapper">';
        $breadcrumb["text"] = "DropBox";
        $breadcrumb["link"] = "/cp/?";
        $breadcrumb["special"] = '<a href="" id="authlink" data-bs-toggle="tooltip" title="Sign in to Dropbox" class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action"><i class="mdi mdi-login mdi-24px"></i>&nbsp;Sign in to Dropbox</a>';
        if ($thisarray["p1"] != "auth") {
            include $website['corebase']."public/modules/headcontent.php";
            echo '<div class="page-wrapper"><div class="container-fluid">';
            $brarr = array();
            array_push($brarr, array(
                "title" => "Create/edit articles",
                "link" => "/cpinfo",
                "icon" => "mdi-file-document-edit-outline",
                "active" => ($page == "cpinfo") ? "active" : "",
            ));
            array_push($brarr, array(
                "title" => "Import documents",
                "link" => "/docimport",
                "icon" => "mdi-upload",
                "active" => ($page == "docimport") ? "active" : "",
            ));
            if (sessionClass::checkAcc($acclist, "designer")) {
                array_push($brarr, array(
                    "title" => "View/Edit diagrams",
                    "link" => "/draw",
                    "icon" => "mdi-drawing",
                    "active" => ($page == "draw") ? "active" : "",
                ));
            }
            if (sessionClass::checkAcc($acclist, "odfiles") && !empty($website['odappid'])) {
                array_push($brarr, array(
                    "title" => "View/Map OneDrive files",
                    "link" => "/onedrive",
                    "icon" => "mdi-microsoft-onedrive",
                    "active" => ($page == "onedrive") ? "active" : "",
                ));
            }
?><div class="row pt-3">
    <div class="col-lg-2">
        <?php  include "public/modules/sidebar.php";?></div>
    <div class="col-lg-8">
        <nav class="breadcrumb authed-section" style="display:none;" id="od-breadcrumb"></nav><br>
        <div id="db-content ">
            <div class="col-lg-8 pre-auth-section" style="display:none;">
                <div class="container">
                    <h3 class="display-4">Not yet authorized </h3>
                    <p class="lead">Please use the sign in button in the right top to login to DropBox
                    </p>
                </div>
            </div>
            <div class="authed-section card p-0" style="display:none;">
                <table id="data-table" class="table table-vmiddle table-hover stylish-table mb-0 "
                    style="width:100%!important;">
                    <thead>
                        <tr>
                            <th data-column-id="id" data-identifier="true" data-visible="false" class="text-center">ID
                            </th>
                            <th class="text-center">File/folder</th>
                            <th class="text-center">Created</th>
                            <th class="text-center">Modified</th>
                            <th class="text-center">Size</th>
                            <th class="text-center">Tags</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="db-items"></tbody>
                </table>
                <div class="modal" id="addmap" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">

                        <div class="modal-content">
                            <div class="modal-header text-center">
                                Add mapping to application/server/request
                            </div>

                            <div class="modal-body"><br>
                                <div class="form-group row">
                                    <label class="form-control-label text-lg-right col-md-3"
                                        for="diagram_title">Name</label>
                                    <div class="col-md-9"><input type="text" id="file_title" name="file_title"
                                            class="form-control" disabled /> </div>
                                    <input type="text" id="file_name" name="file_name" value="" style="display:none;">
                                    <input type="text" id="file_size" name="file_size" value="" style="display:none;">
                                    <input type="text" id="file_id" name="file_id" value="" style="display:none;">
                                    <input type="text" id="file_link" name="file_link" value="" style="display:none;">
                                </div>
                                <div class="form-group row">
                                    <label class="form-control-label text-lg-right col-md-3">Tags</label>
                                    <div class="col-md-9"><input name="tags" id="tags" data-role="tagsinput" type="text"
                                            class="form-control"></div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-control-label text-lg-right col-md-3">Request Number</label>
                                    <div class="col-md-9"> <input type="text" id="reqauto" class="form-control" />
                                        <input type="text" id="reqname" name="reqname" style="display:none;" />
                                    </div>
                                </div>
                                <input type="hidden" id="file_type" value="dropbox">
                                <div class="form-group row">
                                    <label class="form-control-label text-lg-right col-md-3">Application</label>
                                    <div class="col-md-9">
                                        <input type="text" id="applauto" class="form-control"
                                            placeholder="write the application name or code" />
                                        <input type="text" id="appname" name="appname" style="display:none;" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-control-label text-lg-right col-md-3">Server</label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control autocomplsrv"
                                            placeholder="write the server name" />
                                        <input type="text" name="server" id="server" style="display:none;" />
                                        <input type="text" name="serverid" id="serverid" style="display:none;" />
                                        <input type="text" name="serverip" id="serverip" style="display:none;" />
                                        <input type="text" name="serverlist" id="serverlist" style="display:none;" />
                                        <input type="text" name="serverlistnames" id="serverlistnames"
                                            style="display:none;" />
                                        <div id="srvlistnames"></div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-light btn-sm" onclick="mkSrvlist()"><i
                                                class="mdi mdi-plus mdi-24px"></i></button>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-control-label text-lg-right col-md-3">Application Server</label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control autocomplappsrv"
                                            placeholder="write the applicaiton server name" />
                                        <input type="text" name="appserver" id="appserver" style="display:none;" />
                                        <input type="text" name="appserverid" id="appserverid" style="display:none;" />
                                        <input type="text" name="appserverlist" id="appserverlist"
                                            style="display:none;" />
                                        <input type="text" name="appserverlistnames" id="appserverlistnames"
                                            style="display:none;" />
                                        <div id="appsrvlistnames"></div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-light btn-sm" onclick="mkAppSrvlist()"><i
                                                class="mdi mdi-plus mdi-24px"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-light btn-sm" type="button"
                                    onclick="updtag('<?php echo $_SESSION["user"]; ?>');" name="addfiletag"><i
                                        class="mdi mdi-content-save"></i>&nbsp;Save</button>&nbsp;
                                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i
                                        class="mdi mdi-close"></i>&nbsp;Close</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div><br>

        </div>
    </div>
    <div class="col-lg-2">
        <?php include $website['corebase']."public/modules/breadcrumbin.php";?>
    </div>
</div>
<?php echo '</div></div>';
            include $website['corebase']."public/modules/footer.php";
            echo '</div></div>';
        }
        include $website['corebase']."public/modules/js.php"; ?>
<script src="/<?php echo $website['corebase'];?>assets/js/tagsinput.min.js" type="text/javascript"></script>
<script src="/<?php echo $website['corebase'];?>assets/js/datatables/jquery.dataTables.min.js"></script>
<script src="/<?php echo $website['corebase'];?>assets/js/datatables/dataTables.responsive.min.js"></script>
<script>
$('#data-table').DataTable({
    "oLanguage": {
        "sSearch": ""
    },
    dom: 'Bfrtip'
});
</script>
<script>
var CLIENT_ID = '<?php echo $website["dbclid"]; ?>';
var data = loadFromCookie();
if (data.access_token) {
    function getAccessTokenFromUrl() {
        return data.access_token;
    }
} else {
    function getAccessTokenFromUrl() {
        saveToCookie({
            "access_token": utils.parseQueryString(window.location.hash).access_token,
            "signedin": true
        });
        return utils.parseQueryString(window.location.hash).access_token;
    }
}

function isAuthenticated() {
    return !!getAccessTokenFromUrl();
}

function renderItems(items) {
    var filesContainer = document.getElementById('db-items');
    items.forEach(function(item) {
        var li = document.createElement('tr');
        console.log(JSON.stringify(item));
        var tagSet = new Set();
        var tagsout = "";
        let mapbtn = false;
        if (item['.tag'] == "folder") {
            tagSet.add("folder");
        } else {
            tagSet.add("file");
        }
        tagSet.forEach(function(item) {
            tagsout += "<span class='badge badge-info m-1'>" +
                item + "</span>";
        });
        if (item['.tag'] == "file" && typeof item.sharing_info !== 'undefined' && Object.keys(item.sharing_info)
            .length >
            0) {
            tagSet.add("shared");
            mapbtn = true;
        }
        var itemdata = "<tr><td>" + item.id + "</td><td><a href='#' " + (item['.tag'] == "folder" ?
                'onclick="showItems(\'' + item.path_lower + '\')"' : 'onclick="getItems(\'' + item.path_lower +
                '\', \'' + item.id.replace("id:", "") + '\')"') + ">" + item.name + "</a></td><td>" + (item
                .client_modified ? moment(item.client_modified)
                .format("YYYY-MM-DD H:mm") : "") + "</td><td>" + (item.server_modified ? moment(item
                .server_modified).format("YYYY-MM-DD H:mm") : "") +
            "</td><td>" + (item.size ? formatBytes(item.size) : "") + "</td><td>" + (tagsout ? tagsout : "") +
            "</td><td><div class='btn-group'><a data-bs-toggle='tooltip' data-bs-placement='top' title='Download' class='btn btn-light' id='downloaddb" +
            item.id.replace("id:", "") + "' style='display:none;'><i class='mdi mdi-cloud-download'></i></a>" +
            (mapbtn ? "<a data-bs-toggle='modal' id='addtagdb" + item.id.replace("id:", "") +
                "' style='display:none;' href='#addmap' onclick='$(\"#file_title\").val(\"" + item.name +
                "\");$(\"#file_size\").val(\"" + item.size + "\");$(\"#file_name\").val(\"" + item.name +
                "\");$(\"#file_id\").val(\"" + item.id.replace("id:", "") +
                "\");' class='waves-effect waves-light btn btn-light'><i data-bs-toggle='tooltip' data-bs-placement='top' title='add mapping' class='mdi mdi-server-plus'></i></a>" :
                "") +
            "</div></td></tr>"
        $('#data-table').DataTable().row.add($(itemdata)[0]).draw(
            false);

    });
}
if (isAuthenticated()) {
    showPageSection('authed-section');
    var dbx = new Dropbox.Dropbox({
        accessToken: getAccessTokenFromUrl()
    });
    showItems('');
} else {
    showPageSection('pre-auth-section');
    var dbx = new Dropbox.Dropbox({
        clientId: CLIENT_ID
    });
    var authUrl = dbx.auth.getAuthenticationUrl(
        '<?php echo $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"]; ?>/dropbox');
    document.getElementById('authlink').href = authUrl;
}
</script>
<?php echo '</body></html>';
        include $website['corebase']."public/modules/template_end.php";

    }
}