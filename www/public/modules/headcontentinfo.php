<nav class="navbar top-navbar navbar-expand-md navbar-light">
    <div class="container">

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#midnav"
            aria-controls="midnav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="midnav">
            <ul class="navbar-nav me-auto mb-lg-0">
                <ol class="breadcrumb hide-xs">
                    <li class="breadcrumb-item"><a href="/?" target="_parent">Home</a></li>
                    <?php if(!empty($blogcatname)){?>
                    <li class="breadcrumb-item active"><a
                            href="/info/category/<?php echo $blogcatname;?>"><?php echo $blogcat;?></a></li>
                    <?php } ?>
                </ol>

            </ul>
            <ul class="navbar-nav mb-lg-0 nlinks">
                <li class="nav-item d-none d-md-block search-box"> <a
                        class="nav-link d-none d-md-block text-muted waves-effect waves-dark"
                        href="javascript:void(0)"><span class="itemicon"><svg class="midico midico-outline">
                                <use href="/assets/images/icon/midleoicons.svg#i-search"
                                    xlink:href="/assets/images/icon/midleoicons.svg#i-search" />
                            </svg></span></a>
                    <?php if(!empty($_SESSION["user"])){?>
                    <form class="app-search p-relative" method="post" action="/searchall">
                        <input type="text" name="fd" class="hs-input hasselect"
                            placeholder="Search for packages, objects, configuration">
                        <select name="st" class="hs-stype">
                            <option value="">Knowledge Base</option>
                            <option value="diagram">Diagrams</option>
                            <option value="file">Files</option>
                            <option value="tag">Tags</option>
                        </select>
                        <input type="hidden" name="sa" value="1">
                        <button type="submit" name="searchbut" style="display:none"></button>
                        <a class="srh-btn"><i class="mdi mdi-close mdi-24px"></i></a>
                    </form>
                    <?php } else { ?>
                    <form class="app-search p-relative" method="post" action="/info">
                        <input type="text" name="searchkey" class="form-control" placeholder="Search in Knowledge Base">
                        <button type="submit" name="sa" style="display:none"></button>
                        <a class="srh-btn"><i class="mdi mdi-close mdi-24px"></i></a>
                    </form>
                    <?php } ?>
                </li>
                <?php if(!empty($_SESSION["user"])){?>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="#"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="usrdrdown">
                        <img alt="user" class="img-fluid"
                            src="<?php echo !empty($uavatar)?$uavatar:"/assets/images/avatar.svg";?>"
                            style="width:22px;margin-top: -3px;">
                    </a>
                    <ul class="dropdown-menu usrdrdown" aria-labelledby="usrdrdown">
                    <li><a href="/cp/?" class="dropdown-item waves-effect waves-dark"><svg
                                    class="midico midico-outline">
                                    <use href="/assets/images/icon/midleoicons.svg#i-dashboard"
                                        xlink:href="/assets/images/icon/midleoicons.svg#i-dashboard" />
                                </svg>&nbsp;Dashboard<span class="text-end"><svg class="midico midico-outline">
                                        <use href="/assets/images/icon/midleoicons.svg#i-right"
                                            xlink:href="/assets/images/icon/midleoicons.svg#i-right" />
                                    </svg></span></a></li>
                    <li><a href="/profile/?" class="dropdown-item waves-effect waves-dark"><svg
                                    class="midico midico-outline">
                                    <use href="/assets/images/icon/midleoicons.svg#i-users"
                                        xlink:href="/assets/images/icon/midleoicons.svg#i-users" />
                                </svg>&nbsp;Profile<span class="text-end"><svg class="midico midico-outline">
                                        <use href="/assets/images/icon/midleoicons.svg#i-right"
                                            xlink:href="/assets/images/icon/midleoicons.svg#i-right" />
                                    </svg></span></a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a href="/info/" class="dropdown-item waves-effect waves-dark"><svg
                                    class="midico midico-outline">
                                    <use href="/assets/images/icon/midleoicons.svg#i-kn-b"
                                        xlink:href="/assets/images/icon/midleoicons.svg#i-kn-b" />
                                </svg>&nbsp;Knowledge base<span class="text-end"><svg class="midico midico-outline">
                                        <use href="/assets/images/icon/midleoicons.svg#i-right"
                                            xlink:href="/assets/images/icon/midleoicons.svg#i-right" />
                                    </svg></span></a></li>
                         <li><a href="/logout"
                                class="dropdown-item waves-effect waves-dark"><svg class="midico midico-outline">
                                    <use href="/assets/images/icon/midleoicons.svg#i-logout"
                                        xlink:href="/assets/images/icon/midleoicons.svg#i-logout" />
                                </svg>&nbsp;&nbsp;Logout<span class="text-end"><svg class="midico midico-outline">
                                        <use href="/assets/images/icon/midleoicons.svg#i-right"
                                            xlink:href="/assets/images/icon/midleoicons.svg#i-right" />
                                    </svg></span></a></li> 
                    </ul>
                </li>
                <?php } else { ?>
                <li class="nav-item">
                    <a data-bs-toggle="tooltip" title="Login Console"
                        class="dropdown-item nav-link text-muted waves-effect waves-dark" href="/mlogin/?"><i
                            class="mdi mdi-power"></i></a>
                </li>
                <?php } ?>&nbsp;
                <div class="theme-switch-wrapper">
                    <label class="theme-switch" for="checkbox">
                        <input type="checkbox" id="checkbox" />
                        <div class="slidersw" style="margin-top: 7px;">
                            <svg class="itemicon midico midico-outline">
                                <use href="/assets/images/icon/midleoicons.svg#i-night"
                                    xlink:href="/assets/images/icon/midleoicons.svg#i-night" />
                            </svg>
                        </div>
                    </label>
                </div>
            </ul>
        </div>
    </div>
</nav>
<aside class="left-sidebar" ng-app="ngSysApp" ng-controller="ngsysCtrl"><?php include "sidebarinfo.php";?></aside>