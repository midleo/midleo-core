<nav class="navbar top-navbar sticky-top navbar-expand-md navbar-light">
    <div class="container">
        <a class="navbar-brand" href="//<?php echo $_SERVER['HTTP_HOST']; ?>//p=welcome">
            <img data-bs-toggle="tooltip" src="/<?php echo $website['corebase'];?>assets/images/midleo-logo-white.svg"
                alt="<?php if($blogtitle){ echo $blogtitle." :: midleo.CORE"; } elseif($website["env_name"]){ echo $website["env_name"]." :: midleo.CORE"; } else { ?>midleo.CORE<?php } ?>"
                title="<?php if($blogtitle){ echo $blogtitle." :: midleo.CORE"; } elseif($website["env_name"]){ echo $website["env_name"]." :: midleo.CORE"; } else { ?>midleo.CORE<?php } ?>"
                class="light-logo ml" />
            <img data-bs-toggle="tooltip"
                src="/<?php echo $website['corebase'];?>assets/images/midleo-icon-logo-white.svg"
                alt="<?php if($blogtitle){ echo $blogtitle." :: midleo.CORE"; } elseif($website["env_name"]){ echo $website["env_name"]." :: midleo.CORE"; } else { ?>midleo.CORE<?php } ?>"
                title="<?php if($blogtitle){ echo $blogtitle." :: midleo.CORE"; } elseif($website["env_name"]){ echo $website["env_name"]." :: midleo.CORE"; } else { ?>midleo.CORE<?php } ?>"
                class="light-logo-icon mli" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#midnav"
            aria-controls="midnav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="midnav">
            <ul class="navbar-nav me-auto mb-lg-0">
                <ol class="breadcrumb hide-xs">
                    <li class="breadcrumb-item"><a href="/?" target="_parent">Home</a></li>
                    <?php if(!empty($breadcrumb["text2"])){?>
                    <li class="breadcrumb-item"><a href="<?php echo $breadcrumb["link"];?>"
                            target="_parent"><?php echo $breadcrumb["text"];?></a></li>
                    <li class="breadcrumb-item active"><?php echo $breadcrumb["text2"];?></li>
                    <?php } else {  
                        if(!empty($breadcrumb["text"])){ ?>
                    <li class="breadcrumb-item active"><?php echo $breadcrumb["text"];?></li>
                    <?php } 
                    } ?>
                    <?php if(!empty($blogcatname)){?>
                    <li class="breadcrumb-item active"><a
                            href="/info/category/<?php echo $blogcatname;?>"><?php echo $blogcat;?></a></li>
                    <?php } ?>
                    <?php if($thisarray["p0"]=="info"){ if ($forumcase == "posts") {?>
                    <li class="breadcrumb-item"><a href="/info/category/<?php echo $blogcategory; ?>"
                            title="<?php echo $blogcategoryname; ?>"><?php echo $blogcategoryname; ?></a></li>
                    <li class="breadcrumb-item active"><?php echo $blogtitle; ?></li><?php } else {?><li
                        class="breadcrumb-item active"><a
                            href="<?php echo ($forumcase == "category") ? "/info/category/" . $keyws : (($forumcase == "tags") ? "/info/tags/" . $keyws : "/info/"); ?>"><?php echo ($forumcase == "category") ? $blogcategory : (($forumcase == "tags") ? $keyws : "Latest posts"); ?></a>
                    </li><?php } 
                    } ?>
                </ol>
            </ul>
            <ul class="navbar-nav mb-lg-0 nlinks">
                <li class="nav-item d-none d-md-block search-box"> <a data-bs-toggle="tooltip"
                        data-bs-placement="bottom" title="Search"
                        class="nav-link d-none d-md-block text-muted waves-effect waves-dark"
                        href="javascript:void(0)"><span class="itemicon"><i
                                class="mdi mdi-magnify mdi-24px"></i></span></a>
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
                <li class="nav-item">
                    <a data-bs-toggle="tooltip" title="Create new article"
                        class="nav-link text-muted waves-effect waves-dark" href="/cpinfo/"><span class="itemicon"><i
                                class="mdi mdi-square-edit-outline mdi-24px"></i></span></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="#"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="usrdrdown">
                        <img alt="user" class="img-fluid rounded"
                            src="<?php echo !empty($uavatar)?$uavatar:"/".$website['corebase']."assets/images/avatar.svg";?>"
                            style="width:22px;">
                    </a>
                    <ul class="dropdown-menu usrdrdown" aria-labelledby="usrdrdown">
                        <li><a href="/cp/?" class="dropdown-item waves-effect waves-dark"><i
                                    class="mdi mdi-monitor-dashboard"></i>&nbsp;Dashboard</a></li>
                        <li><a href="/profile/" class="dropdown-item waves-effect waves-dark"><i
                                    class="mdi mdi-account-outline"></i>&nbsp;Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a href="/info/" class="dropdown-item waves-effect waves-dark"><i
                                    class="mdi mdi-post-outline"></i>&nbsp;Knowledge base</a></li>
                        <li><a href="/logout" class="dropdown-item waves-effect waves-dark"><i
                                    class="mdi mdi-exit-to-app"></i>&nbsp;Logout</a></li>
                    </ul>
                </li>
                <?php } else { ?>
                <li class="nav-item">
                    <a data-bs-toggle="tooltip" title="Login Console"
                        class="nav-link text-muted waves-effect waves-dark" href="/mlogin/?"><span class="itemicon"><i
                                class="mdi mdi-login mdi-24px"></i></span></a>
                </li>
                <?php } ?>&nbsp;
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