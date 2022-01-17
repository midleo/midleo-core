    <nav class="navbar top-navbar navbar-expand-md navbar-light ml-0">
        <div class="container">
            <a class="navbar-brand" href="//<?php echo $_SERVER['HTTP_HOST']; ?>//p=welcome">
                <img data-bs-toggle="tooltip"
                    src="/<?php echo $website['corebase'];?>assets/images/midleo-logo-white.svg" alt="Midleo CORE"
                    title="Midleo CORE" class="light-logo ml" />
                <img data-bs-toggle="tooltip"
                    src="/<?php echo $website['corebase'];?>assets/images/midleo-icon-logo-white.svg" alt="Midleo CORE"
                    title="Midleo CORE" class="light-logo-icon mli" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#midnav"
                aria-controls="midnav" aria-expanded="false" aria-label="Toggle navigation">
                <span  class="itemicon text-white"><i class="mdi mdi-view-headline mdi-24px"></i></span>
            </button>
            <div class="collapse navbar-collapse" id="midnav">
                <ul class="navbar-nav me-auto mb-lg-0">
                    <ol class="breadcrumb hide-xs">
                        <li class="breadcrumb-item"><a href="/info/">Home</a></li>
                        <?php if ($forumcase == "posts") {?>
                        <li class="breadcrumb-item"><a href="/info/category/<?php echo $blogcategory; ?>"
                                title="<?php echo $blogcategoryname; ?>"><?php echo $blogcategoryname; ?></a></li>
                        <li class="breadcrumb-item active"><?php echo $blogtitle; ?></li><?php } else {?><li
                            class="breadcrumb-item active"><a
                                href="<?php echo ($forumcase == "category") ? "/info/category/" . $keyws : (($forumcase == "tags") ? "/info/tags/" . $keyws : "/info/"); ?>"><?php echo ($forumcase == "category") ? $blogcategory : (($forumcase == "tags") ? $keyws : "Latest posts"); ?></a>
                        </li><?php }?>
                    </ol>
                </ul>
                <ul class="navbar-nav mb-lg-0 nlinks">
                    <?php if (!empty($_SESSION["user"])) {?>
                    <li class="nav-item d-none d-md-block search-box"> <a
                            class="nav-link d-none d-md-block text-muted waves-effect waves-dark"
                            href="javascript:void(0)"><span class="itemicon"><i
                                    class="mdi mdi-magnify mdi-24px"></i></span></a>
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
                    </li>
                    <?php } ?>
                    <?php if (!empty($_SESSION["user"])) {?>
                    <li class="nav-item">
                        <a data-bs-toggle="tooltip" title="Create new article"
                            class="nav-link text-muted waves-effect waves-dark" href="/cpinfo/"><span
                                class="itemicon"><i class="mdi mdi-square-edit-outline mdi-24px"></i></span></a>
                    </li>

                    
                    <li class="nav-item">
                        <a data-bs-toggle="tooltip" title="Control Panel"
                            class="nav-link text-muted waves-effect waves-dark" href="/cp/?"><span class="itemicon"><i
                                    class="mdi mdi-view-dashboard-outline mdi-24px"></i></span></a>
                    </li>
                    <?php } else {?>
                    <li class="nav-item">
                        <a data-bs-toggle="tooltip" title="Login Console"
                            class="nav-link text-muted waves-effect waves-dark" href="/mlogin/?"><span
                                class="itemicon"><i class="mdi mdi-login mdi-24px"></i></a>
                    </li>
                    <?php }?>
                    <li class="nav-item d-none d-sm-block d-md-none">
                        <a class="nav-link text-muted waves-effect waves-dark" data-bs-toggle="collapse" data-bs-target="#sidenavCollapse" aria-controls="sidenavCollapse" aria-expanded="false" aria-label="Toggle navigation">
                        <span  class="itemicon"><i class="mdi mdi-view-headline mdi-24px"></i></span>
                    </a>
                    </li>&nbsp;&nbsp;&nbsp;
                    <div class="theme-switch-wrapper">
                        <label class="theme-switch" for="checkbox">
                            <input type="checkbox" id="checkbox" />
                            <div class="slidersw itemicon">
                                <i class="mdi mdi-weather-night mdi-24px"></i>
                            </div>
                        </label>
                    </div>
                </ul>
            </div>
        </div>
    </nav>