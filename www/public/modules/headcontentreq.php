<header class="topbar">
    <nav class="navbar top-navbar navbar-expand-md navbar-light">
        <div class="navbar-header">
            <a class="navbar-brand" href="//<?php echo $_SERVER['HTTP_HOST'];?>//p=welcome">
                <b>
                <img data-bs-toggle="tooltip" src="/assets/images/midleo-logo-white.svg"
                        alt="Midleo CORE" title="Midleo CORE"
                        class="light-logo" />
                        <img data-bs-toggle="tooltip" src="/assets/images/midleo-icon-logo-white.svg"
                        alt="Midleo CORE" title="Midleo CORE"
                        class="light-logo-icon" />
                </b>
            </a>
            <a class="sidebartoggler hide-xs text-white"  href="javascript:void(0)"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-left" xlink:href="/assets/images/icon/midleoicons.svg#i-left" /></svg></a>
        </div>
        <div class="navbar-collapse">
            <ul class="navbar-nav mr-auto mt-md-0">
            <ol class="breadcrumb hide-xs">
      <li class="breadcrumb-item"><a href="/console/?" target="_parent">Home</a></li>
      <?php if(!empty($breadcrumb["text2"])){?>
        <li class="breadcrumb-item"><a href="<?php echo $breadcrumb["link"];?>" target="_parent"><?php echo $breadcrumb["text"];?></a></li>
        <li class="breadcrumb-item active"><?php echo $breadcrumb["text2"];?></li>
        <?php } else { ?>
          <li class="breadcrumb-item active"><?php echo $breadcrumb["text"];?></li>
        <?php } ?>
     </ol>
            </ul>
            <ul class="navbar-nav my-lg-0">
                <li class="nav-item d-none d-md-block search-box"> <a
                        class="nav-link d-none d-md-block text-muted waves-effect waves-dark"
                        href="javascript:void(0)"><span class="itemicon"><i class="mdi mdi-magnify"></i></span></a>
                    <form class="app-search p-relative" method="post" action="/info">
                        <input type="text" name="searchkey" class="form-control" placeholder="Search in Knowledge Base"
                            style="width:100%">
                        <button type="submit" name="sa" style="display:none"></button>
                        <a class="srh-btn"><i class="mdi mdi-close mdi-24px"></i></a>
                    </form>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href=""
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: inherit;">
                            <img  class="profile-pic" src="<?php echo !empty($uavatar)?$uavatar:"/assets/images/avatar.svg";?>" alt="user" ></a>
                    <div class="dropdown-menu dropdown-menu-right scale-up pb-0 pt-0">
                        <ul class="dropdown-user">
                        <li>
                                <div class="dw-user-box">
                                    <div class="u-img"> 
                                    <img class="img rounded-circle" src="<?php echo !empty($uavatar)?$uavatar:"/assets/images/avatar.svg";?>" alt="user" >
                                    </div>
                                    <div class="u-text">
                                        <h4><?php echo $usname;?></h4>
                                        <a href="/reqprofile" style="padding: initial;display: inherit;">Profile</a>
                                    </div>
                                </div>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li><a href="/info/" class="waves-effect waves-dark"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-kn-b" xlink:href="/assets/images/icon/midleoicons.svg#i-kn-b"/></svg>&nbsp;Knowledge base<span class="text-end"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-right" xlink:href="/assets/images/icon/midleoicons.svg#i-right"/></svg></span></a></li>
                            <li><a href="/reqlogout" class="waves-effect waves-dark"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-logout" xlink:href="/assets/images/icon/midleoicons.svg#i-logout"/></svg>&nbsp;Logout<span class="text-end"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-right" xlink:href="/assets/images/icon/midleoicons.svg#i-right"/></svg></span></a></li>
                        </ul>
                    </div>
                </li>
                &nbsp;&nbsp;&nbsp;
                <div class="theme-switch-wrapper">
                    <label class="theme-switch" for="checkbox">
                        <input type="checkbox" id="checkbox" />
                        <div class="slidersw">
                        <svg class="itemicon midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-night" xlink:href="/assets/images/icon/midleoicons.svg#i-night"/></svg> 
                        </div>
                    </label>
                </div>
            </ul>
        </div>
    </nav>
</header>
<aside class="left-sidebar"  ng-app="ngSysApp" ng-controller="ngsysCtrl"><?php include "sidebar.php";?></aside>