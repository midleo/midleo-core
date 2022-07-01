<?php
$modulelist["onedrive"]["name"] = "Microsoft OneDrive controller";
$modulelist["onedrive"]["js"][] = str_replace($maindir, "", dirname($filename)) . "/assets/js/odauth.js";
$modulelist["onedrive"]["js"][] = str_replace($maindir, "", dirname($filename)) . "/assets/js/odcfg.js";
$modulelist["onedrive"]["css"][] = str_replace($maindir, "", dirname($filename)) . "/assets/css/style.css";
include_once "api.php";
class Class_onedrive
{
    public static function getPage($thisarray)
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
        foreach ($modulelist["onedrive"]["css"] as $csskey => $csslink) {
            if (!empty($csslink)) {?>
<link rel="stylesheet" type="text/css" href="<?php echo $csslink; ?>"><?php }
        }
        foreach ($modulelist["onedrive"]["js"] as $jskey => $jslink) {
            if (!empty($jslink)) {?><script type="text/javascript" src="<?php echo $jslink; ?>"></script>
<?php }
        }
        if ($thisarray["p1"] == "auth") {
            echo '<script>onAuthCallback();</script>';
        }
        echo '<link rel="stylesheet" type="text/css" href="/'.$website['corebase'].'assets/js/datatables/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="/'.$website['corebase'].'assets/js/datatables/responsive.dataTables.min.css">';
    echo '<link rel="stylesheet" type="text/css" href="/'.$website['corebase'].'assets/css/jquery-ui.min.css">';
        echo '</head><body class="fix-header card-no-border"><div id="main-wrapper">';
        $breadcrumb["text"] = "One Drive";
            $breadcrumb["link"] = "/cp/?";
            $breadcrumb["special"] = '
        <a href="#" onclick="signInToOneDrive()" data-bs-toggle="tooltip" title="Sign in to OneDrive" class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action od-login" style="display:none;"><i class="mdi mdi-login mdi-24px"></i>&nbsp;Sign in to OneDrive</a>
        <a href="#" onclick="signOut()" data-bs-toggle="tooltip" title="Sign Out from OneDrive" class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action od-logoff" style="display: none;"><i class="mdi mdi-login mdi-24px"></i>&nbsp;Sign Out from OneDrive</a>
        ';
        if ($thisarray["p1"] != "auth") {
            include $website['corebase']."public/modules/headcontent.php";
            echo '<div class="page-wrapper"><div class="container-fluid">';
            $brarr=array();
            array_push($brarr,array(
                "title"=>"Create/edit articles",
                "link"=>"/cpinfo",
                "icon"=>"mdi-file-document-edit-outline",
                "active"=>($page=="cpinfo")?"active":"",
              ));
              array_push($brarr, array(
                "title" => "Import documents",
                "link" => "/docimport",
                "icon" => "mdi-upload",
                "active" => ($page == "docimport") ? "active" : "",
            ));
            if (sessionClass::checkAcc($acclist, "designer")) {
            array_push($brarr,array(
            "title"=>"View/Edit diagrams",
            "link"=>"/draw",
            "icon"=>"mdi-chart-scatter-plot-hexbin",
            "active"=>($page=="draw")?"active":"",
             ));
             }
              if (sessionClass::checkAcc($acclist, "dbfiles")) {
                array_push($brarr,array(
                    "title"=>"View/Map Dropbox files",
                  "link"=>"/dropbox",
                  "icon"=>"mdi-dropbox",
                  "active"=>($page=="dropbox")?"active":"",
                ));
              }
            ?>
<div class="row pt-3">
    <div class="col-lg-2 bg-white leftsidebar">
        <?php  include "public/modules/sidebar.php";?></div>
    <div class="col-lg-8">
        <div class="col-md-4 od-loading text-center">
            <div style="width:100%;"><i class="mdi mdi-loading iconspin"></i>&nbsp;Loading...</div>
        </div>
        <nav class="breadcrumb od-logoff-flex" id="od-breadcrumb"></nav><br>
        <div id="od-content ">
            <div class="col-lg-8">
                <div class="od-login">
                    <div class="container">
                        <h4 class="display-4">Not yet authorized </h4>
                        <p class="lead">Please use the sign in button in the right top to login to Microsoft OneDrive
                        </p>
                    </div>
                </div>
            </div>
            <div class="od-logoff card p-0">
                <table id="data-table-doc" class="table table-vmiddle table-hover stylish-table mb-0 ">
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
                    <tbody id="od-items"></tbody>
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
                                <input type="hidden" id="file_type" value="onedrive">
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
                                    onclick="updtag('<?php echo $_SESSION["user"];?>');" name="addfiletag"><i
                                        class="mdi mdi-content-save"></i>&nbsp;Save</button>&nbsp;
                                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i
                                        class="mdi mdi-close"></i>&nbsp;Close</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div><br>

            <!-- <div id="od-json"></div>-->

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
    $('#data-table-doc').DataTable({
        "oLanguage": {
            "sSearch": ""
        },
        dom: 'Bfrtip'
    });
    </script>
    <script>
    var baseUrl = getQueryVariable("baseUrl")
    msGraphApiRoot = (baseUrl) ? baseUrl : "https://graph.microsoft.com/v1.0/me";
    var data = loadFromCookie();
    if (data) {
        if (!baseUrl)
            msGraphApiRoot = data.apiRoot;
        showCustomLoginButton(!data.signedin)
    }
    var loadedForHash = "";
    $(window).bind('hashchange', function() {
        if (window.location.hash != loadedForHash) {
            loadedForHash = window.location.hash;
            odauth();
        }
        return false;
    });
    /*
    $(document).on({
        ajaxStart: function() {
            $('body').addClass('loading');
        },
        ajaxStop: function() {
            $('body').removeClass('loading');
        }
    });
    */
    function onAuthenticated(token, authWindow) {
        if (token) {
            if (authWindow) {
                removeLoginButton();
                authWindow.close();
            }
            (function($) {
                var path = "";
                var beforePath = "";
                var afterPath = "";
                if (window.location.hash.length > 1) {
                    path = window.location.hash.substr(1);
                    beforePath = ":";
                    afterPath = ":";
                }
                var odurl = msGraphApiRoot + "/drive/root" + beforePath + path + afterPath;
                var thumbnailSize = "large"
                var odquery = "?expand=thumbnails,children(expand=thumbnails(select=" + thumbnailSize + "))";
                $.ajax({
                    url: odurl + odquery,
                    dataType: 'json',
                    headers: {
                        "Authorization": "Bearer " + token
                    },
                    accept: "application/json;odata.metadata=none",
                    success: function(data) {
                        if (data) {
                            $('#od-items').empty();
                            //   $('#od-json').empty();
                            $('#data-table-doc').DataTable({responsive: true, "autoWidth": false, destroy: true}).clear().draw();
                            //  $("<code>").html(JSON.stringify(data)).appendTo("#od-json");
                            var decodedPath = decodeURIComponent(path);
                            updateBreadcrumb(decodedPath);
                            var children = data.children || data.value;
                            if (children && children.length > 0) {
                                $.each(children, function(i, item) {
                                    var tagSet = new Set();
                                    var tagsout = "";
                                    if (item.folder) {
                                        tagSet.add("folder");
                                    }
                                    if (item.file) {
                                        tagSet.add("file");
                                    }
                                    tagSet.forEach(function(item) {
                                        tagsout +=
                                            "<span class='badge badge-info m-1'>" +
                                            item + "</span>";
                                    });
                                    var itemdata = "<tr><td>" + item.id +
                                        "</td><td><a href='#" +
                                        path + "/" + encodeURIComponent(item.name) + "'>" + item
                                        .name + "</a></td><td>" + moment(item.createdDateTime)
                                        .format("YYYY-MM-DD H:mm") + "</td><td>" + moment(item
                                            .lastModifiedDateTime).format("YYYY-MM-DD H:mm") +
                                        "</td><td>" + formatBytes(item.size) + "</td><td>" + (
                                            tagsout ? tagsout : "") + "</td><td></td></tr>"

                                    /*
                                        if (item.thumbnails && item.thumbnails.length > 0) {
                                          var container = $("<div>").attr("class", "img-container").appendTo(tile)
                                          $("<img>").
                                            attr("src", item.thumbnails[0][thumbnailSize].url).
                                            appendTo(container);
                                        }
                                          */
                                    // $(itemdata).appendTo("#od-items");
                                    $('#data-table-doc').DataTable().row.add($(itemdata)[0])
                                        .draw(
                                            false);
                                });
                            } else if (data.file) {
                                let downloadUrl = data['@microsoft.graph.downloadUrl'];
                                let tagSet = new Set();
                                let mapbtn = false;
                                let tagsout = "";
                                if (data.folder) {
                                    tagSet.add("folder");
                                } else {
                                    tagSet.add("file");
                                }
                                if (typeof data.shared !== 'undefined' && Object.keys(data.shared)
                                    .length >
                                    0) {
                                    tagSet.add("shared");
                                    mapbtn = true;
                                }
                                tagSet.forEach(function(item) {
                                    tagsout += "<span class='badge badge-info m-1'>" + item +
                                        "</span>";
                                });

                                var itemdata = "<tr><td>" + data.id + "</td><td>" + data.name +
                                    "</td><td>" + moment(data.createdDateTime).format(
                                        "YYYY-MM-DD H:mm") +
                                    "</td><td>" + moment(data.lastModifiedDateTime).format(
                                        "YYYY-MM-DD H:mm") + "</td><td>" + formatBytes(data.size) +
                                    "</td><td>" + (tagsout ? tagsout : "") +
                                    "</td><td><div class='btn-group'><a data-bs-toggle='tooltip' data-bs-placement='top' title='Download' class='btn btn-light' href='" +
                                    downloadUrl +
                                    "'><i class='mdi mdi-cloud-download'></i></a>" +
                                    (mapbtn ?
                                        "<a data-bs-toggle='modal' href='#addmap' onclick='$(\"#file_title\").val(\"" +
                                        data.name + "\");$(\"#file_size\").val(\"" + data.size +
                                        "\");$(\"#file_name\").val(\"" + data.name +
                                        "\");$(\"#file_id\").val(\"" + data.id +
                                        "\");$(\"#file_link\").val(\"" + downloadUrl +
                                        "\");' class='waves-effect waves-light btn btn-light'><i data-bs-toggle='tooltip' data-bs-placement='top' title='add mapping' class='mdi mdi-server-plus'></i></a>" :
                                        "") +
                                    "</div></td></tr>"
                                //  $(itemdata).appendTo("#od-items");
                                $('#data-table-doc').DataTable().row.add($(itemdata)[0]).draw(false);
                                $('[data-bs-toggle="tooltip"]').tooltip();
                                /*
                                                  if (data.thumbnails && data.thumbnails.length > 0) {
                                                    $("<img>").
                                                      attr("src", data.thumbnails[0].large.url).
                                                      appendTo(tile);
                                                  }
                                */
                            } else {
                                $('<p>No items in this folder.</p>').appendTo('#od-items');
                            }
                        } else {
                            $('#od-items').empty();
                            $('<p>error.</p>').appendTo('#od-items');
                            //   $('#od-json').empty();
                        }
                    }
                });
            })(jQuery);
        } else {
            notify("Error signing in", "danger");
        }
    }
    odauth();
    </script>
    <?php echo '</body></html>';
        include $website['corebase']."public/modules/template_end.php";
    }
}