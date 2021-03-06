<?php 
$thiscolor=!empty($_GET['c'])?$_GET['c']:"28A7DF";
$borderradius="3px;";
header('Cache-Control: public');
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT');
header('Cache-Control: max-age=604800' );
header("Content-type: text/css");
$css='
:root { --border-radius: '.$borderradius.'; --usercolor:#'.$thiscolor.'; --fc-button-active-bg-color:#0380FE;}
/*.cardtr .list-group-item,.stickyside .list-group-item,.list-group .list-group-item{background-color:transparent;border:none;}*/
.list-group:not(.listdoc) .list-group-item{background-color:transparent;border:none;}
.loader{width:100%;height:100%;top:0;bottom:0;left:0;right:0;position:fixed;z-index:99999;background:#fff;}
.loader .cssload-speeding-wheel{position:absolute;top:calc(50% - 3.5px);left:calc(50% - 3.5px)}
.loading{ height:60px;display:none;position:relative;}
.btn-light,.btn-w,.btn-danger,.btn-info,.btn-outline-info,.btn-secondary{border-color:transparent;}
.btn-light:hover,.btn-w:hover,.btn-danger:hover,.btn-info:hover,.btn-outline-info:hover,.btn-secondary:hover{border-color:transparent;}
.btn-primary,.btn-outline-primary{box-shadow:0 2px 2px rgba(116,96,238,0.05)}
.btn-primary:hover,.btn-outline-primary:hover{box-shadow:0 8px 15px rgba(116,96,238,0.3)}
.btn-secondary,.btn-outline-secondary{box-shadow:0 2px 2px rgba(114,123,132,0.05)}
.btn-secondary:hover,.btn-outline-secondary:hover{box-shadow:0 8px 15px rgba(114,123,132,0.3)}
.btn-success,.btn-outline-success{box-shadow:0 2px 2px rgba(33,193,214,0.05)}
.btn-success:hover,.btn-outline-success:hover{box-shadow:0 8px 15px rgba(33,193,214,0.3)}
.btn-info,.btn-outline-info{box-shadow:0 2px 2px rgba(30,136,229,0.05)}
.btn-info:hover,.btn-outline-info:hover{box-shadow:0 8px 15px rgba(30,136,229,0.3)}
.btn-outline-info{color: #'.$thiscolor.';border-color: #'.$thiscolor.';}
.btn-outline-info:hover{background-color: #'.$thiscolor.';}
.btn-outline-info:active,.btn-outline-info:selected ,.btn-outline-info:focus {background-color: #'.$thiscolor.';}
.m-btn-theme,.btn-info{background:#'.$thiscolor.';color:#fff}
.m-btn-theme.active,.m-btn-theme:focus,.m-btn-theme:hover,.btn-info.active,.btn-info:focus,.btn-info:hover{background:#00D3FF;color:#fff}
.btn-warning,.btn-outline-warning{box-shadow:0 2px 2px rgba(255,178,43,0.05)}
.btn-warning:hover,.btn-outline-warning:hover{box-shadow:0 8px 15px rgba(255,178,43,0.3)}
.btn-danger,.btn-outline-danger{box-shadow:0 2px 2px rgba(252,75,108,0.05)}
.btn-danger:hover,.btn-outline-danger:hover{box-shadow:0 8px 15px rgba(252,75,108,0.3)}
.btn-outline-light{box-shadow:0 2px 2px rgba(242,244,248,0.05)}
.btn-outline-light:hover{box-shadow:0 8px 15px rgba(242,244,248,0.3)}
.btn-dark,.btn-outline-dark{box-shadow:0 2px 2px rgba(33,37,41,0.05)}
.btn-dark:hover,.btn-outline-dark:hover{box-shadow:0 8px 15px rgba(33,37,41,0.3)}
.btn-inverse,.btn-outline-inverse{box-shadow:0 2px 2px rgba(47,61,74,0.05)}
.btn-inverse:hover,.btn-outline-inverse:hover{box-shadow:0 8px 15px rgba(47,61,74,0.3)}
.btn-megna,.btn-outline-megna{box-shadow:0 2px 2px rgba(0,137,123,0.05)}
.btn-megna:hover,.btn-outline-megna:hover{box-shadow:0 8px 15px rgba(0,137,123,0.3)}
.btn-purple,.btn-outline-purple{box-shadow:0 2px 2px rgba(116,96,238,0.05)}
.btn-purple:hover,.btn-outline-purple:hover{box-shadow:0 8px 15px rgba(116,96,238,0.3)}
.btn-light-danger,.btn-outline-light-danger{box-shadow:0 2px 2px rgba(249,231,235,0.05)}
.btn-light-danger:hover,.btn-outline-light-danger:hover{box-shadow:0 8px 15px rgba(249,231,235,0.3)}
.btn-light-success,.btn-outline-light-success{box-shadow:0 2px 2px rgba(232,253,235,0.05)}
.btn-light-success:hover,.btn-outline-light-success:hover{box-shadow:0 8px 15px rgba(232,253,235,0.3)}
.btn-light-warning,.btn-outline-light-warning{box-shadow:0 2px 2px rgba(255,248,236,0.05)}
.btn-light-warning:hover,.btn-outline-light-warning:hover{box-shadow:0 8px 15px rgba(255,248,236,0.3)}
.btn-light-primary,.btn-outline-light-primary{box-shadow:0 2px 2px rgba(241,239,253,0.05)}
.btn-light-primary:hover,.btn-outline-light-primary:hover{box-shadow:0 8px 15px rgba(241,239,253,0.3)}
.btn-light-info,.btn-outline-light-info{box-shadow:0 2px 2px rgba(207,236,254,0.05)}
.btn-light-info:hover,.btn-outline-light-info:hover{box-shadow:0 8px 15px rgba(207,236,254,0.3)}
.btn-light-inverse,.btn-outline-light-inverse{box-shadow:0 2px 2px rgba(246,246,246,0.05)}
.btn-light-inverse:hover,.btn-outline-light-inverse:hover{box-shadow:0 8px 15px rgba(246,246,246,0.3)}
.btn-light-megna,.btn-outline-light-megna{box-shadow:0 2px 2px rgba(224,242,244,0.05)}
.btn-light-megna:hover,.btn-outline-light-megna:hover{box-shadow:0 8px 15px rgba(224,242,244,0.3)}
/*paddings*/
@media (min-width:992px) {
  .px-lg-0{padding-left:0!important;padding-right:0!important}
}
@media (min-width:992px) {
  .offset-lg-3{margin-left:25%}
  .py-lg-9{padding-bottom:4rem!important;padding-top:4rem!important}
  .px-lg-7{padding-left:2.5rem!important;padding-right:2.5rem!important}
}
@media (min-width:1200px) {
.offset-xl-2{margin-left:16.66667%}
.col-xl-8{flex:0 0 auto;width:66.66667%}
}
.py-7{padding-bottom:2rem!important;padding-top:1.5rem!important}
.row{--bs-gutter-x:2.5rem;--bs-gutter-y:0;display:flex;flex-wrap:wrap;margin-left:calc(var(--bs-gutter-x)*-.5);margin-right:calc(var(--bs-gutter-x)*-.5);margin-top:calc(var(--bs-gutter-y)*-1)}
.col{flex:1 0 0}
/*paddings*/
.h3, h3 {font-size: 1.3125rem;}
.h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6{font-weight: 400;}
.navbar-brand {font-size: 1.09375rem;z-index:30;}
h4, .h4 {font-size: 1.125rem;}
.text-muted {color: rgba(0,0,0,3) !important;}
h5, .h5 {font-size: 1rem;}
*{outline:none}
body{background:#fff;margin:0;overflow-x:hidden;font-family: "Ubuntu", sans-serif;font-size: 0.875rem;font-weight: 300;line-height: 1.5;color: #67757c;text-align: left;}
html{position:relative;min-height:100%;}
a,a:hover,a:focus{color:#'.$thiscolor.';text-decoration:none;}
a.link{color:#455a64}
a.link:hover,a.link:focus{color:#'.$thiscolor.'}
html body .mdi:before,html body .mdi-set{line-height:initial}
h1{line-height:40px}
h2{line-height:36px}
h3{line-height:30px}
h4{line-height:22px}
h5{line-height:18px;font-weight:400}
h6{line-height:16px;font-weight:400}
.display-5{font-size:3rem}
.display-6{font-size:36px}
html body blockquote{border-left:5px solid #'.$thiscolor.';border:1px solid rgba(120,130,140,0.13);padding:15px}
ol li{margin:5px 0}
.op-5{opacity:.5}
.op-7{opacity:.7}
html body .font-medium{font-weight:500}
html body .font-12{font-size:12px}
html body .font-16{font-size:16px}
html body .font-14{font-size:14px}
html body .font-10{font-size:10px}
html body .font-18{font-size:18px}
html body .font-20{font-size:20px}
html body .text-themecolor{color:#'.$thiscolor.'}
html body .bg-theme{background-color:#'.$thiscolor.'}
html body .bg-light-primary{background-color:#f1effd}
html body .bg-light-success{background-color:#e8fdeb}
html body .bg-light-info{background-color:#cfecfe}
html body .bg-light-extra{background-color:#ebf3f5}
html body .bg-light-warning{background-color:#fff8ec}
html body .bg-light-danger{background-color:#f9e7eb}
html body .bg-light-inverse{background-color:#f6f6f6}
.round{line-height:48px;width:45px;height:45px}
.round img{border-radius:100%}
.round-lg{line-height:65px;width:60px;height:60px;font-size:30px}
.pager li > a,.pager li > span{-moz-border-radius:'.$borderradius.';-webkit-border-radius:'.$borderradius.';border-radius:'.$borderradius.';color:#212529}
.cell{display:table-cell;vertical-align:middle}
.table thead th,.table th{font-weight:bold;}
.jqstooltip{width:auto!important;height:auto!important}
.waves-effect{position:relative;cursor:pointer;display:inline-block;overflow:hidden;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;-webkit-tap-highlight-color:transparent;vertical-align:middle;z-index:1;will-change:opacity,transform;-webkit-transition:all .1s ease-out;-moz-transition:all .1s ease-out;-o-transition:all .1s ease-out;-ms-transition:all .1s ease-out;transition:all .1s ease-out}
.waves-effect .waves-ripple{position:absolute;border-radius:50%;width:20px;height:20px;margin-top:-10px;margin-left:-10px;opacity:0;background:rgba(0,0,0,0.2);-webkit-transition:all .7s ease-out;-moz-transition:all .7s ease-out;-o-transition:all .7s ease-out;-ms-transition:all .7s ease-out;transition:all .7s ease-out;-webkit-transition-property:-webkit-transform,opacity;-moz-transition-property:-moz-transform,opacity;-o-transition-property:-o-transform,opacity;transition-property:transform,opacity;-webkit-transform:scale(0);-moz-transform:scale(0);-ms-transform:scale(0);-o-transform:scale(0);transform:scale(0);pointer-events:none}
.waves-effect.waves-light .waves-ripple{background-color:rgba(255,255,255,0.45)}
.waves-effect.waves-red .waves-ripple{background-color:rgba(244,67,54,0.7)}
.waves-effect.waves-yellow .waves-ripple{background-color:rgba(255,235,59,0.7)}
.waves-effect.waves-orange .waves-ripple{background-color:rgba(255,152,0,0.7)}
.waves-effect.waves-purple .waves-ripple{background-color:rgba(156,39,176,0.7)}
.waves-effect.waves-green .waves-ripple{background-color:rgba(76,175,80,0.7)}
.waves-effect.waves-teal .waves-ripple{background-color:rgba(0,150,136,0.7)}
html body .waves-notransition{-webkit-transition:none;-moz-transition:none;-o-transition:none;-ms-transition:none;transition:none}
.waves-circle{-webkit-transform:translateZ(0);-moz-transform:translateZ(0);-ms-transform:translateZ(0);-o-transform:translateZ(0);transform:translateZ(0);text-align:center;width:2.5em;height:2.5em;line-height:2.5em;border-radius:50%;-webkit-mask-image:none}
.waves-input-wrapper{border-radius:.2em;vertical-align:bottom}
.waves-input-wrapper .waves-button-input{position:relative;top:0;left:0;z-index:1}
.waves-block{display:block}
.btn.btn-warning{color:#fff}
.btn-md{padding:12px 55px;font-size:16px}
.btn-circle{border-radius:100%;width:40px;height:40px;padding:10px}
.btn-circle.btn-sm,.btn-group-sm > .btn-circle.btn{width:35px;height:35px;padding:8px 10px;font-size:14px}
.btn-circle.btn-lg,.btn-group-lg > .btn-circle.btn{width:50px;height:50px;font-size:18px;padding:11px 15px!important}
.btn-circle.btn-xl{width:70px;height:70px;padding:14px 15px;font-size:24px}
.btn-xs{padding:.25rem .5rem;font-size:10px}
.button-list button,.button-list a{margin:5px 12px 5px 0}
.btn-rounded{border-radius:60px}
.btn-rounded.btn-xs{padding:.25rem .5rem;font-size:10px}
.btn-rounded.btn-md{padding:12px 35px;font-size:16px}
.btn-facebook{color:#fff;background-color:#3b5998}
.btn-twitter{color:#fff;background-color:#55acee}
.btn-linkedin{color:#fff;background-color:#007bb6}
.btn-dribbble{color:#fff;background-color:#ea4c89}
.btn-googleplus{color:#fff;background-color:#dd4b39}
.btn-instagram{color:#fff;background-color:#3f729b}
.btn-pinterest{color:#fff;background-color:#cb2027}
.btn-dropbox{color:#fff;background-color:#007ee5}
.btn-flickr{color:#fff;background-color:#ff0084}
.btn-tumblr{color:#fff;background-color:#32506d}
.btn-skype{color:#fff;background-color:#00aff0}
.btn-youtube{color:#fff;background-color:#b00}
.btn-github{color:#fff;background-color:#171515}
.notify{position:relative;top:-25px;right:-7px}
.notify .heartbit{position:absolute;top:-20px;right:-4px;height:25px;width:25px;z-index:10;border:5px solid #fc4b6c;border-radius:70px;-moz-animation:heartbit 1s ease-out;-moz-animation-iteration-count:infinite;-o-animation:heartbit 1s ease-out;-o-animation-iteration-count:infinite;-webkit-animation:heartbit 1s ease-out;-webkit-animation-iteration-count:infinite;animation-iteration-count:infinite}
.notify .point{width:6px;height:6px;-webkit-border-radius:30px;-moz-border-radius:30px;border-radius:30px;background-color:#fc4b6c;position:absolute;right:6px;top:-10px}
@-moz-keyframes heartbit {
0%{-moz-transform:scale(0);opacity:0}
25%{-moz-transform:scale(0.1);opacity:.1}
50%{-moz-transform:scale(0.5);opacity:.3}
75%{-moz-transform:scale(0.8);opacity:.5}
100%{-moz-transform:scale(1);opacity:0}
}
@-webkit-keyframes heartbit {
0%{-webkit-transform:scale(0);opacity:0}
25%{-webkit-transform:scale(0.1);opacity:.1}
50%{-webkit-transform:scale(0.5);opacity:.3}
75%{-webkit-transform:scale(0.8);opacity:.5}
100%{-webkit-transform:scale(1);opacity:0}
}
.fileupload{overflow:hidden;position:relative}
.fileupload input.upload{cursor:pointer;filter:alpha(opacity=0);font-size:20px;margin:0;opacity:0;padding:0;position:absolute;right:0;top:0}
ul.list-style-none{margin:0;padding:0}
ul.list-style-none li{list-style:none}
ul.list-style-none li a{color:#67757c;padding:8px 0;display:block;text-decoration:none}
ul.list-style-none li a:hover{color:#'.$thiscolor.'}
.card{border-radius:6px;box-shadow:0 1px 1px rgba(0,0,0,.08);border:0px solid transparent;}
.card-no-border .shadow-none{box-shadow:none}
.card-fullscreen{position:fixed;top:0;left:0;width:100%;height:100%;z-index:9999;overflow:auto}
.card .card-header, .modal .modal-header, .modal .modal-footer{/*background:rgba(0,0,0,.02);*/border-bottom:0}
.css-bar:after{z-index:1}
.css-bar > i{z-index:10}
.fix-width{width:100%;max-width:1170px;margin:0 auto}
.progress{height:auto}
.card-group{margin-bottom:30px}
.tablesaw [type="checkbox"]:not(:checked),.tablesaw [type="checkbox"]:checked,.fixed-table-container [type="checkbox"]:not(:checked),.fixed-table-container [type="checkbox"]:checked{position:relative;left:0;opacity:1}
#main-wrapper{width:100%;/*overflow:hidden*/}
.container-fluid{padding:0 15px 15px 15px;}
@media (max-width: 767.98px) {
.container-fluid{padding:0 15px 15px}
.hide-xs{display:none;}
}
.navbar-brand img{height:30px;padding-left: 8px;margin-top: -4px;}
.navbar-brand span{color:#fff;text-transform:uppercase;}
.search-box .app-search{position:absolute;margin:0;display:block;z-index:110;width:100%;top:-1px;box-shadow:2px 0 10px rgba(0,0,0,0.2);display:none;left:0}
.search-box .app-search input{width:91%;padding:25px 40px 25px 20px;border-radius:0;font-size:17px;transition:.5s ease-in;height:54px}
.search-box .app-search .srh-btn{position:absolute;top:15px;cursor:pointer;background:#fff;width:15px;height:15px;right:20px;font-size:14px}
.logo-center .top-navbar .navbar-header{position:absolute;left:0;right:0;margin:0 auto}
@-webkit-keyframes rotate {
from{-webkit-transform:rotate(0deg)}
to{-webkit-transform:rotate(360deg)}
}
@-moz-keyframes rotate {
from{-moz-transform:rotate(0deg)}
to{-moz-transform:rotate(360deg)}
}
@keyframes rotate {
from{transform:rotate(0deg)}
to{transform:rotate(360deg)}
}
.page-titles{min-height:50px;}
.footer{bottom:0;color:#67757c;left:0;padding:17px 15px;position:absolute;right:0;border-top:1px solid rgba(120,130,140,0.13);background:#fff;text-align:right;}
.card{margin-bottom:15px}
.card .card-subtitle{font-weight:300;margin-bottom:15px;color:#a0a1a3}
.card-inverse .card-blockquote .blockquote-footer,.card-inverse .card-link,.card-inverse .card-subtitle,.card-inverse .card-text{color:rgba(255,255,255,0.65)}
.card-success{background:#21c1d6;border-color:#21c1d6}
.card-danger{background:#fc4b6c;border-color:#fc4b6c}
.card-warning{background:#ffb22b;border-color:#ffb22b}
.card-info{background:#1e88e5;border-color:#1e88e5}
.card-primary{background:#7460ee;border-color:#7460ee}
.card-dark{background:#2f3d4a;border-color:#2f3d4a}
.card-megna{background:#00897b;border-color:#00897b}
.button-group .btn{margin-bottom:5px;margin-right:5px}
.no-button-group .btn{margin-bottom:5px;margin-right:0}
.btn .text-active{display:none}
.btn.active .text-active{display:inline-block}
.btn.active .text{display:none}
.card-actions{float:right}
.card-actions a{cursor:pointer;color:#67757c;opacity:.7;padding-left:7px;font-size:13px}
.card-actions a:hover{opacity:1}
.card-columns .card{margin-bottom:20px}
.collapsing{-webkit-transition:height .08s ease;transition:height .08s ease}
.card-info{background:#1e88e5;border-color:#1e88e5}
.card-primary{background:#7460ee;border-color:#7460ee}
.breadcrumb-item,.breadcrumb-item a{color:#fff}
.breadcrumb-item.active, .breadcrumb-item a.active{opacity:.7;color:#fff;}
.breadcrumb-item + .breadcrumb-item::before{color:rgba(255,255,255,0.4)}
.breadcrumb{margin-bottom:0}
ul.list-icons{margin:0;padding:0}
ul.list-icons li{list-style:none;line-height:30px;margin:5px 0;transition:.2s ease-in}
ul.list-icons li a{color:#67757c}
ul.list-icons li a:hover{color:#'.$thiscolor.'}
ul.list-icons li i{font-size:13px;padding-right:8px}
ul.two-part{margin:0}
ul.two-part li{width:48.8%}
html body .accordion .card{margin-bottom:0}
.flot-chart{display:block;height:400px}
.flot-chart-content{width:100%;height:100%}
html body .jqstooltip,html body .flotTip{width:auto!important;height:auto!important;background:#212529;color:#fff;padding:5px 10px}
.chart{position:relative;display:inline-block;width:100px;height:100px;margin-top:20px;margin-bottom:20px;text-align:center}
.chart canvas{position:absolute;top:0;left:0}
.chart.chart-widget-pie{margin-top:5px;margin-bottom:5px}
.pie-chart > span{left:0;margin-top:-2px;position:absolute;right:0;text-align:center;top:50%;transform:translateY(-50%)}
.chart > span > img{left:0;position:absolute;right:0;text-align:center;top:50%;width:60%;height:60%;transform:translateY(-50%);margin:0 auto}
.percent{display:inline-block;line-height:100px;z-index:2;font-weight:600;font-size:18px;color:#212529}
.percent:after{content:"%";margin-left:.1em;font-size:.8em}
.c3-chart-arcs-title,.c3-legend-item{font-family:"Ubuntu",sans-serif;fill:#67757c}
html body #visitor .c3-chart-arcs-title{font-size:18px;fill:#d5d6d8}
.stylish-table thead th{font-weight:400;color:#636E82;border:0;border-bottom:1px;}
.stylish-table tbody td{vertical-align:middle}
.stylish-table tbody td h6{font-weight:500;margin-bottom:0;white-space:nowrap}
.stylish-table tbody td small{line-height:12px;white-space:nowrap}
#visitfromworld path.jvectormap-region.jvectormap-element{stroke-width:1px;stroke:#d5d6d8}
.jvectormap-zoomin,.jvectormap-zoomout,.jvectormap-goback{background:#d5d6d8}
.browser td{vertical-align:middle;padding-left:0}
#calendar .fc-today-button{display:none}
.calendar-events{padding:8px 10px;border:1px solid #fff;cursor:move}
.calendar-events:hover{border:1px dashed rgba(120,130,140,0.13)}
.calendar-events i{margin-right:8px}
.product-overview.table tbody tr td{vertical-align:middle}
.sparkchart{margin-bottom:-2px}
.btn-file{overflow:hidden;position:relative;vertical-align:middle}
.btn-file > input{position:absolute;top:0;right:0;margin:0;opacity:0;filter:alpha(opacity=0);font-size:23px;height:100%;width:100%;direction:ltr;cursor:pointer;border-radius:0}
.product-review{margin:0;padding:25px}
.product-review li{display:block;padding:20px 0;list-style:none}
.social-profile{text-align:center;background:rgba(7,10,43,0.8)}
.customtab{background: #fff;border-radius: 6px;}
.profile-tab li a.nav-link,.customtab li a.nav-link{border:0;padding:.8rem .6rem;}
.profile-tab li a.nav-link:hover,.customtab li a.nav-link:hover{color:#'.$thiscolor.'}
.bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn){width:100%}
.input-form .btn{padding:7px 12px}
input[type="radio"]{border-radius:'.$borderradius.';}
.form-control{border-radius:4px;height: auto;float:none;border: 0px;border-bottom: 1px solid #ccc;}
.form-control.focus,.form-control:focus{box-shadow:none;float:none}
.form-control-line .form-group{overflow:hidden}
.form-control-line .form-control{border:0;border-radius:0;padding-left:0;border-bottom:1px solid #d9d9d9}
.form-control:focus{border:0px;border-bottom:1px solid #'.$thiscolor.'}
.has-warning .bar:before,.has-warning .bar:after{background:#ffb22b}
.has-success .bar:before,.has-success .bar:after{background:#21c1d6}
.has-error .bar:before,.has-error .bar:after{background:#fc4b6c}
.has-warning .form-control:focus ~ label,.has-warning .form-control:valid ~ label{color:#ffb22b}
.has-success .form-control:focus ~ label,.has-success .form-control:valid ~ label{color:#21c1d6}
.has-error .form-control:focus ~ label,.has-error .form-control:valid ~ label{color:#fc4b6c}
.has-feedback label ~ .t-0{top:0}
.form-group.error input,.form-group.error select,.form-group.error textarea{border:1px solid #fc4b6c}
.form-group.validate input,.form-group.validate select,.form-group.validate textarea{border:1px solid #21c1d6}
.form-group.error .help-block ul{padding:0;color:#fc4b6c}
.form-group.error .help-block ul li{list-style:none}
.form-group.issue .help-block ul{padding:0;color:#ffb22b}
.form-group.issue .help-block ul li{list-style:none}
.dropzone{border:1px dashed #d9d9d9}
.dropzone .dz-message{padding:5% 0;margin:0}
table th{font-weight:400}
.datepicker table tr td.today,.datepicker table tr td.today.disabled,.datepicker table tr td.today.disabled:hover,.datepicker table tr td.today:hover{background:#'.$thiscolor.';color:#fff}
.datepicker td,.datepicker th{padding:5px 10px}
.icheck-list{float:left;padding-right:50px;padding-top:10px}
.icheck-list li{padding-bottom:5px}
.icheck-list li label{padding-left:10px}
.note-popover,.note-icon-caret{display:none}
.note-editor.note-frame{border:1px solid #d9d9d9}
.note-editor.note-frame .panel-heading{padding:6px 10px 10px;border-bottom:1px solid rgba(120,130,140,0.13)}
.table thead th,.table th{border:0}
.table-striped tbody tr:nth-of-type(odd){background:#F5F5F5}
.dt-buttons{display:inline-block;padding-top:5px;margin-bottom:15px;padding-right: 10px;float: right;}
.dt-buttons .dt-button{padding:5px 15px;border:0px solid transparent;border-radius:'.$borderradius.';background:#'.$thiscolor.';color:#fff;margin-right:3px}
.dt-buttons .dt-button:hover{background-color: #0380FE;border-color: #0380FE;}
div.dataTables_wrapper div.dataTables_info{padding:0px!important;}
.dataTables_info,.dataTables_length{display:inline-block;margin-left: 10px; margin-top: 20px;}
.dataTables_length{margin-top:10px}
.dataTables_length select{border:0;background-image:linear-gradient(#'.$thiscolor.',#'.$thiscolor.'),linear-gradient(#d9d9d9,#d9d9d9);background-size:0 2px,100% 1px;background-repeat:no-repeat;background-position:center bottom,center calc(100% - 1px);background-color:transparent;transition:background 0 ease-out;padding-bottom:5px;box-shadow:none}
.dataTables_length select:focus{outline:none;background-image:linear-gradient(#'.$thiscolor.',#'.$thiscolor.'),linear-gradient(#d9d9d9,#d9d9d9);background-size:100% 2px,100% 1px;box-shadow:none;transition-duration:.3s}
.dataTables_filter{float:left;margin-top:5px;padding-left: 10px;}
table.dataTable thead .sorting,table.dataTable thead .sorting_asc,table.dataTable thead .sorting_desc,table.dataTable thead .sorting_asc_disabled,table.dataTable thead .sorting_desc_disabled{background:transparent}
.dataTables_wrapper .dataTables_paginate{float:right;text-align:right;padding:.85em}
.dataTables_wrapper .dataTables_paginate .paginate_button{padding:4px;box-sizing:border-box;display:inline-block;min-width:1.5em;text-align:center;text-decoration:none;cursor:pointer;*cursor:hand;color:#67757c;border:1px solid transparent;border-radius:4px;}
.dataTables_wrapper .dataTables_paginate .paginate_button.current,.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover{color:#fff!important;border:1px solid #'.$thiscolor.';background-color:#'.$thiscolor.'}
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled,.dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover,.dataTables_wrapper .dataTables_paginate .paginate_button.disabled:active{cursor:default;color:#67757c;border:1px solid transparent;background:transparent;box-shadow:none}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover{color:#fff;border:1px solid #'.$thiscolor.';background-color:#'.$thiscolor.'}
.dataTables_wrapper .dataTables_paginate .paginate_button:active{outline:none;background-color:#67757c}
.dataTables_wrapper .dataTables_paginate .ellipsis{padding:0 1em}
.tablesaw-bar .btn-group label{color:#67757c!important}
.dt-bootstrap{display:block}
.grid-stack-item-content{background:#fff;color:#2b2b2b;text-align:center;font-size:20px}
.grid-stack > .grid-stack-item > .grid-stack-item-content{border:1px solid rgba(120,130,140,0.13)}
.bootstrap-switch,.bootstrap-switch .bootstrap-switch-container{border-radius:2px}
.bootstrap-switch .bootstrap-switch-handle-on{border-bottom-left-radius:2px;border-top-left-radius:2px}
.bootstrap-switch .bootstrap-switch-handle-off{border-bottom-right-radius:2px;border-top-right-radius:2px}
.bootstrap-switch .bootstrap-switch-handle-off.bootstrap-switch-primary,.bootstrap-switch .bootstrap-switch-handle-on.bootstrap-switch-primary{color:#fff;background:#7460ee}
.bootstrap-switch .bootstrap-switch-handle-off.bootstrap-switch-info,.bootstrap-switch .bootstrap-switch-handle-on.bootstrap-switch-info{color:#fff;background:#1e88e5}
.bootstrap-switch .bootstrap-switch-handle-off.bootstrap-switch-success,.bootstrap-switch .bootstrap-switch-handle-on.bootstrap-switch-success{color:#fff;background:#21c1d6}
.bootstrap-switch .bootstrap-switch-handle-off.bootstrap-switch-warning,.bootstrap-switch .bootstrap-switch-handle-on.bootstrap-switch-warning{color:#fff;background:#ffb22b}
.bootstrap-switch .bootstrap-switch-handle-off.bootstrap-switch-danger,.bootstrap-switch .bootstrap-switch-handle-on.bootstrap-switch-danger{color:#fff;background:#fc4b6c}
.bootstrap-switch .bootstrap-switch-handle-off.bootstrap-switch-default,.bootstrap-switch .bootstrap-switch-handle-on.bootstrap-switch-default{color:#212529;background:#f2f4f8}
.dp-selected[style]{background-color:#'.$thiscolor.'!important}
.datepaginator-sm .pagination li a,.datepaginator-lg .pagination li a,.datepaginator .pagination li a{padding:0 5px;height:60px;border:1px solid rgba(120,130,140,0.13);float:left;position:relative}
.model_img{cursor:pointer}
.show-grid{margin-bottom:10px;padding:0 15px}
.show-grid [class^=col-]{padding-top:10px;padding-bottom:10px;border:1px solid #d9d9d9;background-color:#f2f4f8}
.vtabs{display:table}
.vtabs .tabs-vertical{width:150px;border-bottom:0;border-right:1px solid rgba(120,130,140,0.13);display:table-cell;vertical-align:top}
.vtabs .tabs-vertical li .nav-link{color:#212529;margin-bottom:10px;border:0;border-radius:'.$borderradius.' 0px 0px '.$borderradius.'}
.vtabs .tab-content{display:table-cell;padding:20px;vertical-align:top}
.tabs-vertical li .nav-link.active,.tabs-vertical li .nav-link:hover,.tabs-vertical li .nav-link.active:focus{background:#'.$thiscolor.';border:0;color:#fff}
.customvtab .tabs-vertical li .nav-link.active,.customvtab .tabs-vertical li .nav-link:hover,.customvtab .tabs-vertical li .nav-link:focus{background:#fff;border:0;border-right:2px solid #'.$thiscolor.';margin-right:-1px;color:#'.$thiscolor.'}
.tabcontent-border{border:1px solid #F5F5F5;border-top:0}
.customtab2 li a.nav-link{border:0;margin-right:3px;color:#67757c}
.customtab2 li a.nav-link.active{background:#'.$thiscolor.';color:#fff}
.customtab2 li a.nav-link:hover{color:#fff;background:#'.$thiscolor.'}
.progress.active .progress-bar,.progress-bar.active{-webkit-animation:progress-bar-stripes 2s linear infinite;-o-animation:progress-bar-stripes 2s linear infinite;animation:progress-bar-stripes 2s linear infinite}
.progress-vertical{min-height:250px;height:250px;position:relative;display:inline-block;margin-bottom:0;margin-right:20px}
.progress-vertical-bottom{min-height:250px;height:250px;position:relative;display:inline-block;margin-bottom:0;margin-right:20px;transform:rotate(180deg)}
.progress-animated{-webkit-animation-duration:5s;-webkit-animation-name:myanimation;-webkit-transition:5s all;animation-duration:5s;animation-name:myanimation;transition:5s all}
@-webkit-keyframes myanimation {
from{width:0}
}
@keyframes myanimation {
from{width:0}
}
.jq-icon-info{background-color:#1e88e5;color:#fff}
.jq-icon-success{background-color:#21c1d6;color:#fff}
.jq-icon-error{background-color:#fc4b6c;color:#fff}
.jq-icon-warning{background-color:#ffb22b;color:#fff}
.alert-rounded{border-radius:60px}
.list-group a.list-group-item:hover{background:#0000001A}
.list-group-item.active{color:#000!important;background-color: rgba(245,248,250,.8) !important;border-radius: 3px;border-left: 3px solid #'.$thiscolor.'!important}
.list-group-item.disabled{color:#d5d6d8;background:#f2f4f8}
.dd3-handle:before{color:#67757c;top:7px}
.tooltip-item{background:rgba(0,0,0,0.1);cursor:pointer;display:inline-block;font-weight:500;padding:0 10px}
.tooltip-item::after{content:"";position:absolute;width:360px;height:20px;bottom:100%;left:50%;pointer-events:none;transform:translateX(-50%)}
.tooltip-content{position:absolute;z-index:9999;width:360px;left:50%;margin:0 0 20px -180px;bottom:100%;text-align:left;font-size:14px;line-height:30px;box-shadow:-5px -5px 15px rgba(48,54,61,0.2);background:#2b2b2b;opacity:0;cursor:default;pointer-events:none}
.tooltip-content img{position:relative;height:140px;display:block;float:left;margin-right:1em}
.tooltip-content::after{content:"";top:100%;left:50%;border:solid transparent;height:0;width:0;position:absolute;pointer-events:none;border-color:transparent;border-top-color:#2a3035;border-width:10px;margin-left:-10px}
.tooltip-text{font-size:14px;line-height:24px;display:block;padding:1.31em 1.21em 1.21em 0;color:#fff}
.error-box{background-color:#fff;width:100%}
.error-box .footer{width:100%;left:0;right:0}
.error-body{padding-top:5%}
.error-body h1{font-size:100px;font-weight:900;line-height:100px}
.error-box .error-title{font-size: 100px;font-weight: 900;line-height: 100px;}
.gmaps,.gmaps-panaroma{height:300px;height:300px;background:#f2f4f8;border-radius:3px}
.gmaps-overlay{display:block;text-align:center;color:#fff;font-size:16px;line-height:40px;background:#7460ee;border-radius:'.$borderradius.';padding:10px 20px}
.gmaps-overlay_arrow{left:50%;margin-left:-16px;width:0;height:0;position:absolute}
.gmaps-overlay_arrow.above{bottom:-15px;border-left:16px solid transparent;border-right:16px solid transparent;border-top:16px solid #7460ee}
.gmaps-overlay_arrow.below{top:-15px;border-left:16px solid transparent;border-right:16px solid transparent;border-bottom:16px solid #7460ee}
.jvectormap-zoomin,.jvectormap-zoomout{width:10px;height:10px;line-height:10px}
.jvectormap-zoomout{top:40px}
.search-listing{padding:0;margin:0}
.search-listing li{list-style:none;padding:15px 0;border-bottom:1px solid rgba(120,130,140,0.13)}
.search-listing li h3{margin:0;font-size:18px}
.search-listing li h3 a{color:#1e88e5}
.search-listing li h3 a:hover{text-decoration:underline}
.search-listing li a{color:#21c1d6}
.login-register{padding:6% 0;}
.login-box .footer{width:100%;left:0;right:0}
.login-box .social{display:block;margin-bottom:30px}
#recoverform{display:none}
.login-sidebar{padding:0;margin-top:0}
.login-sidebar .login-box{right:0;position:absolute;height:100%}
.minimal-faq .card{border:0}
.minimal-faq .card .card-header{background:#fff;padding:20px 0;margin-top:10px}
.minimal-faq .card .card-block{padding:15px 0}
.pricing-box{position:relative;text-align:center;margin-top:30px}
.featured-plan{margin-top:0}
.featured-plan .pricing-body{padding:60px 0;background:#ebf3f5;border:1px solid #ddd}
.featured-plan .price-table-content .price-row{border-top:1px solid rgba(120,130,140,0.13)}
.pricing-body{border-radius:0;border-top:1px solid rgba(120,130,140,0.13);border-bottom:5px solid rgba(120,130,140,0.13);vertical-align:middle;padding:30px 0;position:relative}
.pricing-body h2{position:relative;font-size:56px;margin:20px 0 10px;font-weight:500}
.pricing-body h2 span{position:absolute;font-size:15px;top:-10px;margin-left:-10px}
.price-table-content .price-row{padding:20px 0;border-top:1px solid rgba(120,130,140,0.13)}
.pricing-plan{padding:0 15px}
.pricing-plan .no-padding{padding:0}
.price-lable{position:absolute;top:-10px;padding:5px 10px;margin:0 auto;display:inline-block;width:100px;left:0;right:0}
.inbox-panel .list-group .list-group-item{border:0;border-radius:0;border-left:3px solid transparent}
.inbox-panel .list-group .list-group-item a{color:#67757c}
.inbox-panel .list-group .list-group-item.active,.inbox-panel .list-group .list-group-item:hover{background:#f2f4f8;border-left:3px solid #'.$thiscolor.'}
.inbox-center .unread td{font-weight:400}
.inbox-center td{vertical-align:middle;white-space:nowrap}
.inbox-center a{color:#67757c;padding:2px 0 3px;overflow:hidden;vertical-align:middle;text-overflow:ellipsis;white-space:nowrap;display:inline-block}
.inbox-center .checkbox{margin-top:-13px;height:20px}
.contact-page-aside{position:relative}
.left-aside{position:absolute;border-right:1px solid rgba(120,130,140,0.13);padding:20px;width:250px;height:100%}
.right-aside{padding:20px;margin-left:250px}
.contact-list td{vertical-align:middle;padding:25px 10px}
.contact-list td img{width:30px}
.list-style-none{margin:0;padding:0}
.list-style-none li{list-style:none;margin:0}
.list-style-none li.box-label a{font-weight:500}
.list-style-none li.divider{margin:10px 0;height:1px;background:rgba(120,130,140,0.13)}
.list-style-none li a{padding:15px 10px;display:block;color:#67757c}
.list-style-none li a:hover{color:#'.$thiscolor.'}
.list-style-none li a span{float:right}
.carousel-item-next,.carousel-item-prev,.carousel-item.active{display:block}
.plugin-details{display:none}
.plugin-details-active{display:block}
.form-control-line .form-control{box-shadow:none}
.docs-buttons .btn,.docs-data .input-group{margin-bottom:5px}
.fixed-table-container{overflow:hidden}
.table-responsive > .table-bordered{border:1px solid #dee2e6}
.note-toolbar{z-index:1}
@media (min-width: 768px) {
.profile-bg-height{max-height:187px}
.blog-img-height{max-height:332px}
}
@media (max-width: 991.98px) {
.month-table{height:unset!important}
.mainicon{display:none;}
.sqicon{display:inline-block;}
}
.profile-card .profile-img{max-height:401px}
.btn-list .btn{margin-bottom:12px;margin-left:8px}
.modal-title{margin-top:0}
.modal-full-width{width:95%;max-width:none}
.modal-top{margin:0 auto}
.modal-right{position:absolute;right:0;display:flex;flex-flow:column nowrap;justify-content:center;height:100%;margin:0;background-color:#fff;align-content:center;transform:translate(25%,0)!important}
.modal-right button.close{position:fixed;top:20px;right:20px;z-index:1}
.modal.show .modal-right{transform:translate(0,0)!important}
.modal-bottom{display:flex;flex-flow:column nowrap;-ms-flex-pack:end;justify-content:flex-end;height:100%;margin:0 auto;align-content:center}
.modal-colored-header{color:#fff;border-radius:0}
.modal-colored-header .close{color:#fff!important}
.modal-filled{color:#fff}
.modal-filled .modal-header{background-color:rgba(255,255,255,0.07)}
.modal-filled .modal-header,.modal-filled .modal-footer{border:none}
.modal-filled .close{color:#fff!important}
@media (max-width: 767.98px) {
.social-profile-first{padding-top:15%!important;max-height:193px}
.navbar{margin-left:2px;}
}
.left-sidebar{overflow-y: auto;position:absolute;width:300px;height:100%;top:0;z-index:20;background:#31353d;}
.fix-sidebar .left-sidebar{position:fixed}
@media (min-width: 992px) {
.fix-sidebar .navbar-header{position:fixed}
.fix-sidebar .navbar-collapse{margin-left:240px}
/*.navbar{margin-left:300px;}*/
.mainicon{display:inline-block;}
    .sqicon{display:none;}
}
.user-profile{position:relative;background-size:cover}
.user-profile .profile-img{width:50px;margin-left:30px;padding:35px 0;border-radius:100%}
.user-profile .profile-img::before{-webkit-animation:2.5s blow 0 linear infinite;animation:2.5s blow 0 linear infinite;position:absolute;content:"";width:50px;height:50px;top:35px;margin:0 auto;border-radius:50%;z-index:0}
@-webkit-keyframes blow {
0%{box-shadow:0 0 0 0 rgba(0,0,0,0.1);opacity:1;-webkit-transform:scale3d(1,1,0.5);transform:scale3d(1,1,0.5)}
50%{box-shadow:0 0 0 10px rgba(0,0,0,0.1);opacity:1;-webkit-transform:scale3d(1,1,0.5);transform:scale3d(1,1,0.5)}
100%{box-shadow:0 0 0 20px rgba(0,0,0,0.1);opacity:0;-webkit-transform:scale3d(1,1,0.5);transform:scale3d(1,1,0.5)}
}
@keyframes blow {
0%{box-shadow:0 0 0 0 rgba(0,0,0,0.1);opacity:1;-webkit-transform:scale3d(1,1,0.5);transform:scale3d(1,1,0.5)}
50%{box-shadow:0 0 0 10px rgba(0,0,0,0.1);opacity:1;-webkit-transform:scale3d(1,1,0.5);transform:scale3d(1,1,0.5)}
100%{box-shadow:0 0 0 20px rgba(0,0,0,0.1);opacity:0;-webkit-transform:scale3d(1,1,0.5);transform:scale3d(1,1,0.5)}
}
.user-profile .profile-img img{width:100%;border-radius:100%}
.user-profile .profile-text{padding:5px 0;position:relative}
.user-profile .profile-text > a{color:#fff!important;width:100%;padding:6px 30px;background:rgba(0,0,0,0.5);display:block}
.user-profile .profile-text > a:after{position:absolute;right:20px;top:20px}
.user-profile .dropdown-menu{left:0;right:0;width:180px;margin:0 auto}
.sidebar-footer a{color:#d5d6d8!important;padding:12px;width:33.3%;float:left;text-align:center;font-size:20px}
.collapse.in{display:block}
.message-box .message-widget a:hover,.message-box .message-center a:hover,.mailbox .message-widget a:hover,.mailbox .message-center a:hover{background:#f2f4f8}
.user-img .profile-status{border:2px solid #fff;height:15px;left:31px;top:-4px;width:15px}
.message-box .message-widget a .mail-contnet,.message-box .message-center a .mail-contnet,.mailbox .message-widget a .mail-contnet,.mailbox .message-center a .mail-contnet{width:75%}
.message-box .message-widget a .mail-contnet .mail-desc,.message-box .message-widget a .mail-contnet .time,.message-box .message-center a .mail-contnet .mail-desc,.message-box .message-center a .mail-contnet .time,.mailbox .message-widget a .mail-contnet .mail-desc,.mailbox .message-widget a .mail-contnet .time,.mailbox .message-center a .mail-contnet .mail-desc,.mailbox .message-center a .mail-contnet .time{color:#67757c}
.mailbox .message-center a .user-img img{width:40px}
.message-box .message-widget a .user-img img{width:45px}
.analytics-info li span{font-size:24px;vertical-align:middle}
.stats-row{margin-bottom:20px}
.stats-row .stat-item{display:inline-block;padding-right:15px}
.stats-row .stat-item + .stat-item{padding-left:15px;border-left:1px solid rgba(120,130,140,0.13)}
.comment-widgets .comment-row{border-left:3px solid #fff}
.comment-widgets .comment-row:hover,.comment-widgets .comment-row.active{border-color:#'.$thiscolor.'}
.comment-text:hover .comment-footer .action-icons,.comment-text.active .comment-footer .action-icons{visibility:visible}
.comment-footer .action-icons{visibility:hidden}
.comment-footer .action-icons a{vertical-align:middle;color:#d5d6d8}
.comment-footer .action-icons a:hover,.comment-footer .action-icons a.active{color:#1e88e5}
.todo-list li .checkbox label{color:#455a64}
.todo-list li .assignedto li img{width:30px}
.list-task .task-done span{text-decoration:line-through}
.calendar{float:left;margin-bottom:0}
.fc-view{margin-top:30px}
.none-border .modal-footer{border-top:none}
.fc-toolbar{margin-bottom:5px;margin-top:15px}
.fc-toolbar h2{font-size:18px;font-weight:500;line-height:30px;text-transform:uppercase}
.fc-day{background:#fff}
.fc-toolbar .fc-state-active,.fc-toolbar .ui-state-active,.fc-toolbar button:focus,.fc-toolbar button:hover,.fc-toolbar .ui-state-hover{z-index:0}
.fc-widget-header{border:0!important}
.fc-widget-content{border-color:rgba(120,130,140,0.13)!important}
.fc th.fc-widget-header{color:#67757c;font-size:13px;font-weight:300;line-height:20px;padding:7px 0;text-transform:uppercase}
.fc th.fc-sun,.fc th.fc-tue,.fc th.fc-thu,.fc th.fc-sat{background:#f2f7f8}
.fc th.fc-mon,.fc th.fc-wed,.fc th.fc-fri{background:#f2f7f8}
.fc-view{margin-top:0}
.fc-toolbar{margin:0;padding:24px 0}
.fc-button{background:#fff;border:1px solid rgba(120,130,140,0.13);color:#67757c;text-transform:capitalize}
.fc-button:hover{background:#f2f4f8;opacity:.8}
.fc-text-arrow{font-family:inherit;font-size:16px}
.fc-state-hover{background:#F5F5F5}
.fc-unthemed .fc-today{border:1px solid #fc4b6c;background:#f2f4f8!important}
.fc-state-highlight{background:#f0f0f0}
.fc-cell-overlay{background:#f0f0f0}
.fc-unthemed .fc-today{background:#fff}
.calendar-event{cursor:move;margin:10px 5px 0 0;padding:6px 10px;display:inline-block;color:#fff;min-width:140px;text-align:center;background:#1e88e5}
.calendar-event a{float:right;opacity:.6;font-size:10px;margin:4px 0 0 10px;color:#fff}
.fc-basic-view td.fc-week-number span{padding-right:5px}
.fc-basic-view .fc-day-number{padding:10px 15px;display:inline-block}
.steamline{position:relative;border-left:1px solid rgba(120,130,140,0.13);margin-left:20px}
.steamline .sl-left{float:left;margin-left:-20px;z-index:1;width:40px;line-height:40px;text-align:center;height:40px;border-radius:100%;color:#fff;background:#212529;margin-right:15px}
.steamline .sl-left img{max-width:40px}
.steamline .sl-right{padding-left:50px}
.steamline .sl-right .desc,.steamline .sl-right .inline-photos{margin-bottom:30px}
.steamline .sl-item{border-bottom:1px solid rgba(120,130,140,0.13);margin:20px 0}
.sl-date{font-size:10px;color:#d5d6d8}
.time-item{border-color:rgba(120,130,140,0.13);padding-bottom:1px;position:relative}
.time-item:before{content:" ";display:table}
.time-item:after{background-color:#fff;border-color:rgba(120,130,140,0.13);border-radius:10px;border-style:solid;border-width:2px;bottom:0;content:"";height:14px;left:0;margin-left:-8px;position:absolute;top:5px;width:14px}
.time-item-item:after{content:" ";display:table}
.item-info{margin-bottom:15px;margin-left:15px}
.item-info p{margin-bottom:10px!important}
.feeds .feed-item:hover{background:#ebf3f5}
.vert .carousel-item-next.carousel-item-left,.vert .carousel-item-prev.carousel-item-right{-webkit-transform:translate3d(0,0,0);transform:translate3d(0,0,0)}
.vert .carousel-item-next,.vert .active.carousel-item-right{-webkit-transform:translate3d(0,100%,0);transform:translate3d(0,100% 0)}
.vert .carousel-item-prev,.vert .active.carousel-item-left{-webkit-transform:translate3d(0,-100%,0);transform:translate3d(0,-100%,0)}
.social-widget .soc-header{padding:15px;text-align:center;font-size:36px;color:#fff}
.social-widget .soc-header.box-facebook{background:#3b5998}
.social-widget .soc-header.box-twitter{background:#00aced}
.social-widget .soc-header.box-google{background:#f86c6b}
.social-widget .soc-header.box-linkedin{background:#4875b4}
.social-widget .soc-content{display:flex;text-align:center}
.social-widget .soc-content div{padding:10px}
.social-widget .soc-content div h3{margin-bottom:0}
.gaugejs-box{position:relative;margin:0 auto}
.gaugejs-box canvas.gaugejs{width:100%!important;height:auto!important}
.social-profile-first{text-align:center;padding-top:22%;margin-bottom:103px}
.social-profile-first.bg-over{background:rgba(56,83,161,0.7)}
.social-profile-first .middle{vertical-align:middle}
.country-state{list-style:none;margin:0;padding:0 0 0 10px}
.country-state li{margin-top:30px;margin-bottom:10px}
.country-state h2{margin-bottom:0;font-weight:400}
.profiletimeline{margin-left:70px}
.profiletimeline .sl-left{margin-left:-60px;z-index:1}
.profiletimeline .sl-left img{max-width:40px}
.profiletimeline .sl-date{font-size:12px}
.profiletimeline .time-item{border-color:rgba(120,130,140,0.13);padding-bottom:1px;position:relative}
.profiletimeline .time-item:before{content:" ";display:table}
.profiletimeline .time-item:after{background-color:#fff;border-color:rgba(120,130,140,0.13);border-radius:10px;border-style:solid;border-width:2px;bottom:0;content:"";height:14px;left:0;margin-left:-8px;position:absolute;top:5px;width:14px}
.profiletimeline .time-item-item:after{content:" ";display:table}
.profiletimeline .item-info{margin-bottom:15px;margin-left:15px}
.profiletimeline .item-info p{margin-bottom:10px!important}
.blog-widget{margin-top:30px}
.blog-widget .blog-image img{border-radius:'.$borderradius.';margin-top:-45px;margin-bottom:20px;box-shadow:0 0 15px rgba(0,0,0,0.2)}
.weather-small h1{line-height:30px}
.weather-small sup{font-size:60%}
.little-profile .pro-img{margin-top:-80px;margin-bottom:20px}
.little-profile .pro-img img{width:128px;height:128px}
.bootstrap-touchspin .input-group-btn{align-items:normal}
.form-control:required:focus{border:0px;border-bottom:1px solid #E81625;box-shadow:none;}
.form-control-line .form-control.form-control-success:focus{border-bottom:1px solid #21c1d6}
.form-control-line .form-control.form-control-warning:focus{border-bottom:1px solid #ffb22b}
.form-control-line .form-control.form-control-danger:focus{border-bottom:1px solid #fc4b6c}
.form-control-label{line-height:36px;}
.form-control-danger,.form-control-success,.form-control-warning{padding-right:2.25rem;background:transparent;background-repeat:no-repeat;background-position:center right .5625rem;-webkit-background-size:1.125rem 1.125rem;background-size:1.125rem 1.125rem}
.has-success .col-form-label,.has-success .custom-control,.has-success .form-check-label,.has-success .form-control-label{color:#21c1d6}
/*.has-success .form-control-success{background-image:url(../images/icon/success.svg);background-repeat: no-repeat;background-position-x: right;}*/
.has-success .form-control{border:0px;border-bottom:1px solid #0CC44F;}
.has-warning .col-form-label,.has-warning .custom-control,.has-warning .form-check-label,.has-warning .form-control-label{color:#ffb22b}
/*.has-warning .form-control-warning{background-image:url(../images/icon/warning.svg);background-repeat: no-repeat;background-position-x: right;}*/
.has-warning .form-control{border:0px;border-bottom:1px solid #ffb22b}
.has-danger .col-form-label,.has-danger .custom-control,.has-danger .form-check-label,.has-danger .form-control-label{color:#fc4b6c}
/*.has-danger .form-control-danger{background-image:url(../images/icon/danger.svg);background-repeat: no-repeat;background-position-x: right;}*/
.has-danger .form-control{border:0px;border-bottom:1px solid #E81625;box-shadow:none;}
.input-group-addon [type="radio"]:not(:checked),.input-group-addon [type="radio"]:checked,.input-group-addon [type="checkbox"]:not(:checked),.input-group-addon [type="checkbox"]:checked{position:initial;opacity:1}
.custom-select{background:url(../images/custom-select.png) right .75rem center no-repeat;-webkit-appearance:none}
textarea{resize:none}
.form-control-sm{min-height:20px}
.form-control:disabled,.form-control[readonly]{opacity:.7}
form label{font-weight:400}
.form-group{margin-bottom:10px}
.form-horizontal label:not(.chbl){margin-bottom:0;line-height: 37px;}
.form-control-static{padding-top:0}
.form-bordered .form-group{border-bottom:1px solid rgba(120,130,140,0.13);padding-bottom:20px}
.card-inverse .card-blockquote,.card-inverse .card-footer,.card-inverse .card-header,.card-inverse .card-title{color:#fff}
.no-wrap td,.no-wrap th{white-space:nowrap}
@media (min-width: 1650px) {
.widget-app-columns{column-count:3}
}
@media (max-width: 1370px) {
.widget-app-columns{column-count:2}
}
@media (min-width: 1024px) {
/*.page-wrapper{margin-left:300px}
.footer{left:300px}*/
}
.card-footer{background-color:transparent;}
@media (max-width: 1023px) {
.widget-app-columns{column-count:1}
.inbox-center a{width:200px}
}
@media (min-width: 768px) {
.navbar-header{width:300px;flex-shrink:0}
.flex-wrap{flex-wrap:nowrap!important;-webkit-flex-wrap:nowrap!important}
}
@media (max-width: 767px) {
.mega-dropdown .dropdown-menu{height:480px;overflow:auto}
.comment-text .comment-footer .action-icons{display:block;padding:10px 0}
.vtabs .tabs-vertical{width:auto}
.footer{left:0}
.error-page .footer{position:fixed;bottom:0;z-index:10}
.error-box{position:relative;padding-bottom:60px}
.error-body{padding-top:10%}
.error-body h1{font-size:100px;font-weight:600;line-height:100px}
.login-register{position:relative;overflow:hidden}
.login-sidebar{padding:10% 0}
.login-sidebar .login-box{position:relative}
.left-aside{width:100%;position:relative;border:0}
.right-aside{margin-left:0}
.flex-wrap{flex-wrap:wrap!important;-webkit-flex-wrap:wrap!important}
}
.preloader{width:100%;height:100%;top:0;position:fixed;z-index:99999;background:#fff}
.preloader .cssload-speeding-wheel{position:absolute;top:calc(50% - 3.5px);left:calc(50% - 3.5px)}
.btn-primary,.btn-outline-primary{box-shadow:0 2px 2px rgba(116,96,238,0.05)}
.btn-primary:hover,.btn-outline-primary:hover{box-shadow:0 8px 15px rgba(116,96,238,0.3)}
.btn-secondary,.btn-outline-secondary{box-shadow:0 2px 2px rgba(114,123,132,0.05)}
.btn-secondary:hover,.btn-outline-secondary:hover{box-shadow:0 8px 15px rgba(114,123,132,0.3)}
.btn-success,.btn-outline-success{box-shadow:0 2px 2px rgba(33,193,214,0.05)}
.btn-success:hover,.btn-outline-success:hover{box-shadow:0 8px 15px rgba(33,193,214,0.3)}
.btn-info,.btn-theme,.btn-outline-info{box-shadow:0 2px 2px rgba(30,136,229,0.05)}
.btn-info:hover,.btn-theme:hover,.btn-outline-info:hover{box-shadow:0 8px 15px rgba(30,136,229,0.3)}
.btn-warning,.btn-outline-warning{box-shadow:0 2px 2px rgba(255,178,43,0.05)}
.btn-warning:hover,.btn-outline-warning:hover{box-shadow:0 8px 15px rgba(255,178,43,0.3)}
.btn-danger,.btn-outline-danger{box-shadow:0 2px 2px rgba(252,75,108,0.05)}
.btn-danger:hover,.btn-outline-danger:hover{box-shadow:0 8px 15px rgba(252,75,108,0.3)}
.btn-outline-light{box-shadow:0 2px 2px rgba(242,244,248,0.05)}
.btn-outline-light:hover{box-shadow:0 8px 15px rgba(242,244,248,0.3)}
.btn-dark,.btn-outline-dark{box-shadow:0 2px 2px rgba(33,37,41,0.05)}
.btn-dark:hover,.btn-outline-dark:hover{box-shadow:0 8px 15px rgba(33,37,41,0.3)}
.btn-inverse,.btn-outline-inverse{box-shadow:0 2px 2px rgba(47,61,74,0.05)}
.btn-inverse:hover,.btn-outline-inverse:hover{box-shadow:0 8px 15px rgba(47,61,74,0.3)}
.btn-megna,.btn-outline-megna{box-shadow:0 2px 2px rgba(0,137,123,0.05)}
.btn-megna:hover,.btn-outline-megna:hover{box-shadow:0 8px 15px rgba(0,137,123,0.3)}
.btn-purple,.btn-outline-purple{box-shadow:0 2px 2px rgba(116,96,238,0.05)}
.btn-purple:hover,.btn-outline-purple:hover{box-shadow:0 8px 15px rgba(116,96,238,0.3)}
.btn-light-danger,.btn-outline-light-danger{box-shadow:0 2px 2px rgba(249,231,235,0.05)}
.btn-light-danger:hover,.btn-outline-light-danger:hover{box-shadow:0 8px 15px rgba(249,231,235,0.3)}
.btn-light-success,.btn-outline-light-success{box-shadow:0 2px 2px rgba(232,253,235,0.05)}
.btn-light-success:hover,.btn-outline-light-success:hover{box-shadow:0 8px 15px rgba(232,253,235,0.3)}
.btn-light-warning,.btn-outline-light-warning{box-shadow:0 2px 2px rgba(255,248,236,0.05)}
.btn-light-warning:hover,.btn-outline-light-warning:hover{box-shadow:0 8px 15px rgba(255,248,236,0.3)}
.btn-light-primary,.btn-outline-light-primary{box-shadow:0 2px 2px rgba(241,239,253,0.05)}
.btn-light-primary:hover,.btn-outline-light-primary:hover{box-shadow:0 8px 15px rgba(241,239,253,0.3)}
.btn-light-info,.btn-outline-light-info{box-shadow:0 2px 2px rgba(207,236,254,0.05)}
.btn-light-info:hover,.btn-outline-light-info:hover{box-shadow:0 8px 15px rgba(207,236,254,0.3)}
.btn-light-inverse,.btn-outline-light-inverse{box-shadow:0 2px 2px rgba(246,246,246,0.05)}
.btn-light-inverse:hover,.btn-outline-light-inverse:hover{box-shadow:0 8px 15px rgba(246,246,246,0.3)}
.btn-light-megna,.btn-outline-light-megna{box-shadow:0 2px 2px rgba(224,242,244,0.05)}
.btn-light-megna:hover,.btn-outline-light-megna:hover{box-shadow:0 8px 15px rgba(224,242,244,0.3)}
#iconlist [type="radio"] + label{height:50px;}
form p,form .col-md-10 a,form .col-md-9 a{margin-bottom:10px;text-align:left;line-height: 37px;}
form p:last-child{margin-bottom:0}
[type="checkbox"]:not(:checked),[type="checkbox"]:checked{position:absolute;left:-9999px;opacity:0}
[type="checkbox"] + label{position:relative;padding-left:35px;cursor:pointer;display:inline-block;height:15px;line-height:25px;font-size:1rem;-webkit-user-select:none;-moz-user-select:none;-khtml-user-select:none;-ms-user-select:none}
[type="checkbox"] + label:before,[type="checkbox"]:not(.filled-in) + label:after{content:"";position:absolute;top:0;left:0;width:18px;height:18px;z-index:0;border:2px solid #5a5a5a;border-radius:1px;margin-top:2px;transition:.2s}
[type="checkbox"]:not(.filled-in) + label:after{border:0;-webkit-transform:scale(0);transform:scale(0)}
[type="checkbox"]:not(:checked):disabled + label:before{border:none;background-color:rgba(0,0,0,0.26)}
[type="checkbox"].tabbed:focus + label:after{-webkit-transform:scale(1);transform:scale(1);border:0;border-radius:50%;box-shadow:0 0 0 10px rgba(0,0,0,0.1);background-color:rgba(0,0,0,0.1)}
[type="checkbox"]:checked + label:before{top:-4px;left:-5px;width:12px;height:22px;border-top:2px solid transparent;border-left:2px solid transparent;border-right:2px solid #26a69a;border-bottom:2px solid #26a69a;-webkit-transform:rotate(40deg);transform:rotate(40deg);-webkit-backface-visibility:hidden;backface-visibility:hidden;-webkit-transform-origin:100% 100%;transform-origin:100% 100%}
[type="checkbox"]:checked:disabled + label:before{border-right:2px solid rgba(0,0,0,0.26);border-bottom:2px solid rgba(0,0,0,0.26)}
[type="checkbox"]:indeterminate + label:before{top:-11px;left:-12px;width:10px;height:22px;border-top:none;border-left:none;border-right:2px solid #26a69a;border-bottom:none;-webkit-transform:rotate(90deg);transform:rotate(90deg);-webkit-backface-visibility:hidden;backface-visibility:hidden;-webkit-transform-origin:100% 100%;transform-origin:100% 100%}
[type="checkbox"]:indeterminate:disabled + label:before{border-right:2px solid rgba(0,0,0,0.26);background-color:transparent}
[type="checkbox"].filled-in + label:after{border-radius:2px}
[type="checkbox"].filled-in + label:before,[type="checkbox"].filled-in + label:after{content:"";left:0;position:absolute;transition:border .25s,background-color .25s,width .2s .1s,height .2s .1s,top .2s .1s,left .2s .1s;z-index:1}
[type="checkbox"].filled-in:not(:checked) + label:before{width:0;height:0;border:3px solid transparent;left:6px;top:10px;-webkit-transform:rotateZ(37deg);transform:rotateZ(37deg);-webkit-transform-origin:20% 40%;transform-origin:100% 100%}
[type="checkbox"].filled-in:not(:checked) + label:after{height:20px;width:20px;background-color:transparent;border:2px solid #5a5a5a;top:0;z-index:0}
[type="checkbox"].filled-in:checked + label:before{top:0;left:1px;width:8px;height:13px;border-top:2px solid transparent;border-left:2px solid transparent;border-right:2px solid #fff;border-bottom:2px solid #fff;-webkit-transform:rotateZ(37deg);transform:rotateZ(37deg);-webkit-transform-origin:100% 100%;transform-origin:100% 100%}
[type="checkbox"].filled-in:checked + label:after{top:0;width:20px;height:20px;border:2px solid #26a69a;background-color:#26a69a;z-index:0}
[type="checkbox"].filled-in.tabbed:focus + label:after{border-radius:2px;border-color:#5a5a5a;background-color:rgba(0,0,0,0.1)}
[type="checkbox"].filled-in.tabbed:checked:focus + label:after{border-radius:2px;background-color:#26a69a;border-color:#26a69a}
[type="checkbox"].filled-in:disabled:not(:checked) + label:before{background-color:transparent;border:2px solid transparent}
[type="checkbox"].filled-in:disabled:not(:checked) + label:after{border-color:transparent;background-color:#BDBDBD}
[type="checkbox"].filled-in:disabled:checked + label:before{background-color:transparent}
[type="checkbox"].filled-in:disabled:checked + label:after{background-color:#BDBDBD;border-color:#BDBDBD}
.switch,.switch *{-webkit-user-select:none;-moz-user-select:none;-khtml-user-select:none;-ms-user-select:none}
.switch label{cursor:pointer}
.switch label input[type=checkbox]{opacity:0;width:0;height:0}
.switch label input[type=checkbox]:checked + .lever{background-color:#84c7c1}
.switch label input[type=checkbox]:checked + .lever:after{background-color:#26a69a;left:24px}
.switch label .lever{content:"";display:inline-block;position:relative;width:40px;height:15px;background-color:#818181;border-radius:15px;transition:background .3s ease;vertical-align:middle;margin:0 16px}
.switch label .lever:after{content:"";position:absolute;display:inline-block;width:21px;height:21px;background-color:#F1F1F1;border-radius:21px;box-shadow:0 1px 3px 1px rgba(0,0,0,0.4);left:-5px;top:-3px;transition:left .3s ease,background .3s ease,box-shadow .1s ease}
input[type=checkbox]:checked:not(:disabled) ~ .lever:active::after,input[type=checkbox]:checked:not(:disabled).tabbed:focus ~ .lever::after{box-shadow:0 1px 3px 1px rgba(0,0,0,0.4),0 0 0 15px rgba(38,166,154,0.1)}
input[type=checkbox]:not(:disabled) ~ .lever:active:after,input[type=checkbox]:not(:disabled).tabbed:focus ~ .lever::after{box-shadow:0 1px 3px 1px rgba(0,0,0,0.4),0 0 0 15px rgba(0,0,0,0.08)}
.switch input[type=checkbox][disabled] + .lever{cursor:default}
.switch label input[type=checkbox][disabled] + .lever:after,.switch label input[type=checkbox][disabled]:checked + .lever:after{background-color:#BDBDBD}
.scale-up{-webkit-transition:all .3s ease;transition:all .3s ease;-webkit-transform:scale(0);transform:scale(0);display:inline-block;transform-origin:right 0}
.scale-up-left{-webkit-transition:all .3s ease;transition:all .3s ease;-webkit-transform:scale(0);transform:scale(0);display:inline-block;transform-origin:left 0}
.show > .scale-up{transform:scale(1);transform-origin:right 0}
.show > .scale-up-left{transform:scale(1);transform-origin:left 0}
.well,pre{/*box-shadow:0 1px 4px 0 rgba(0,0,0,0.1)*/}
.page-titles .justify-content-end:last-child .d-flex{margin-right:10px}
.btn-circle.right-side-toggle{position:fixed;bottom:20px;right:20px;padding:25px}
@keyframes ripple {
0%{box-shadow:0 0 0 1px transparent}
50%{box-shadow:0 0 0 15px rgba(0,0,0,0.1)}
100%{box-shadow:0 0 0 15px transparent}
}
.bootstrap-select.btn-group .dropdown-menu{margin-top:-40px;/*box-shadow:0 1px 4px 0 rgba(0,0,0,0.1)*/}
.demo-checkbox label,.demo-radio-button label{min-width:200px;margin-bottom:20px}
.demo-swtich .demo-switch-title,.demo-swtich .switch{width:150px;margin-bottom:10px;display:inline-block}
[type="checkbox"] + label{padding-left:26px;line-height:21px;font-weight:400}
[type="checkbox"]:checked + label:before{top:-4px;left:-2px;width:11px;height:19px}
[type="checkbox"]:checked.chk-col-red + label:before{border-right:2px solid #fb3a3a;border-bottom:2px solid #fb3a3a}
[type="checkbox"]:checked.chk-col-pink + label:before{border-right:2px solid #E91E63;border-bottom:2px solid #E91E63}
[type="checkbox"]:checked.chk-col-purple + label:before{border-right:2px solid #7460ee;border-bottom:2px solid #7460ee}
[type="checkbox"]:checked.chk-col-deep-purple + label:before{border-right:2px solid #673AB7;border-bottom:2px solid #673AB7}
[type="checkbox"]:checked.chk-col-indigo + label:before{border-right:2px solid #3F51B5;border-bottom:2px solid #3F51B5}
[type="checkbox"]:checked.chk-col-blue + label:before{border-right:2px solid #00AFFF;border-bottom:2px solid #00AFFF}
[type="checkbox"]:checked.chk-col-light-blue + label:before{border-right:2px solid #03A9F4;border-bottom:2px solid #03A9F4}
[type="checkbox"]:checked.chk-col-cyan + label:before{border-right:2px solid #00BCD4;border-bottom:2px solid #00BCD4}
[type="checkbox"]:checked.chk-col-teal + label:before{border-right:2px solid #009688;border-bottom:2px solid #009688}
[type="checkbox"]:checked.chk-col-green + label:before{border-right:2px solid #'.$thiscolor.';border-bottom:2px solid #'.$thiscolor.'}
[type="checkbox"]:checked.chk-col-light-green + label:before{border-right:2px solid #8BC34A;border-bottom:2px solid #8BC34A}
[type="checkbox"]:checked.chk-col-lime + label:before{border-right:2px solid #CDDC39;border-bottom:2px solid #CDDC39}
[type="checkbox"]:checked.chk-col-yellow + label:before{border-right:2px solid #ffe821;border-bottom:2px solid #ffe821}
[type="checkbox"]:checked.chk-col-amber + label:before{border-right:2px solid #FFC107;border-bottom:2px solid #FFC107}
[type="checkbox"]:checked.chk-col-orange + label:before{border-right:2px solid #FF9800;border-bottom:2px solid #FF9800}
[type="checkbox"]:checked.chk-col-deep-orange + label:before{border-right:2px solid #FF5722;border-bottom:2px solid #FF5722}
[type="checkbox"]:checked.chk-col-brown + label:before{border-right:2px solid #795548;border-bottom:2px solid #795548}
[type="checkbox"]:checked.chk-col-grey + label:before{border-right:2px solid #9E9E9E;border-bottom:2px solid #9E9E9E}
[type="checkbox"]:checked.chk-col-blue-grey + label:before{border-right:2px solid #d5d6d8;border-bottom:2px solid #d5d6d8}
[type="checkbox"]:checked.chk-col-black + label:before{border-right:2px solid #000;border-bottom:2px solid #000}
[type="checkbox"]:checked.chk-col-white + label:before{border-right:2px solid #fff;border-bottom:2px solid #fff}
[type="checkbox"].filled-in:checked + label:after{top:0;width:20px;height:20px;border:2px solid #26a69a;background-color:#26a69a;z-index:0}
[type="checkbox"].filled-in:checked + label:before{border-right:2px solid #fff!important;border-bottom:2px solid #fff!important}
[type="checkbox"].filled-in:checked.chk-col-red + label:after{border:2px solid #fb3a3a;background-color:#fb3a3a}
[type="checkbox"].filled-in:checked.chk-col-pink + label:after{border:2px solid #E91E63;background-color:#E91E63}
[type="checkbox"].filled-in:checked.chk-col-purple + label:after{border:2px solid #7460ee;background-color:#7460ee}
[type="checkbox"].filled-in:checked.chk-col-deep-purple + label:after{border:2px solid #673AB7;background-color:#673AB7}
[type="checkbox"].filled-in:checked.chk-col-indigo + label:after{border:2px solid #3F51B5;background-color:#3F51B5}
[type="checkbox"].filled-in:checked.chk-col-blue + label:after{border:2px solid #00AFFF;background-color:#00AFFF}
[type="checkbox"].filled-in:checked.chk-col-light-blue + label:after{border:2px solid #03A9F4;background-color:#03A9F4}
[type="checkbox"].filled-in:checked.chk-col-cyan + label:after{border:2px solid #00BCD4;background-color:#00BCD4}
[type="checkbox"].filled-in:checked.chk-col-teal + label:after{border:2px solid #009688;background-color:#009688}
[type="checkbox"].filled-in:checked.chk-col-green + label:after{border:2px solid #'.$thiscolor.';background-color:#'.$thiscolor.'}
[type="checkbox"].filled-in:checked.chk-col-light-green + label:after{border:2px solid #8BC34A;background-color:#8BC34A}
[type="checkbox"].filled-in:checked.chk-col-lime + label:after{border:2px solid #CDDC39;background-color:#CDDC39}
[type="checkbox"].filled-in:checked.chk-col-yellow + label:after{border:2px solid #ffe821;background-color:#ffe821}
[type="checkbox"].filled-in:checked.chk-col-amber + label:after{border:2px solid #FFC107;background-color:#FFC107}
[type="checkbox"].filled-in:checked.chk-col-orange + label:after{border:2px solid #FF9800;background-color:#FF9800}
[type="checkbox"].filled-in:checked.chk-col-deep-orange + label:after{border:2px solid #FF5722;background-color:#FF5722}
[type="checkbox"].filled-in:checked.chk-col-brown + label:after{border:2px solid #795548;background-color:#795548}
[type="checkbox"].filled-in:checked.chk-col-grey + label:after{border:2px solid #9E9E9E;background-color:#9E9E9E}
[type="checkbox"].filled-in:checked.chk-col-blue-grey + label:after{border:2px solid #d5d6d8;background-color:#d5d6d8}
[type="checkbox"].filled-in:checked.chk-col-black + label:after{border:2px solid #000;background-color:#000}
[type="checkbox"].filled-in:checked.chk-col-white + label:after{border:2px solid #fff;background-color:#fff}
.theme-switch-wrapper{display:flex;align-items:center}
.theme-switch-wrapper em{margin-left:10px;color:#fff}
.theme-switch{display:inline-block;height:34px;position:relative;width:34px;margin-bottom:0}
.theme-switch input{display:none}
.slidersw{cursor:pointer;transition:.4s;margin-top: 4px;}
.hs-stype{border-radius:2px 0px 0px 2px;background:#f9f9f9;left:0;font-size:14px;color:#'.$thiscolor.';top:0;position:absolute;cursor:pointer;width:150px;height:100%;text-align:center;text-align-last:right; padding-right: 20px;  direction: rtl;-webkit-appearance: none;-moz-appearance: none;text-indent: 1px;text-overflow: "";border: 0px solid transparent;}
.hs-input{border:0;margin-left: 150px;}
.wa-stats{float:left;}
.wa-stats > span{margin-right:-1px;padding:7px 12px;/*border:1px solid #E0E0E0;*/float:left;font-weight:500;}
.wa-stats > span.active{color:#4caf50}
.wa-stats > span:first-child{border-radius:2px 0 0 2px}
.wa-stats > span:last-child{border-radius:0 2px 2px 0}
.wa-stats > span > i{line-height:100%;vertical-align:top;position:relative;top:2px;font-size:15px;margin-right:2px}
.blogstat{margin-top:-10px;}
.wa-users{float:right;padding:0!important;margin-right:-5px}
.wa-users > a{display:inline-block;margin-left:2px}
.wa-users > a > img{width:33px;height:33px;border-radius:50%}
.wa-users > a > img:hover{opacity:.85;filter:alpha(opacity=85)}
.sweet-alert{border-radius:2px;padding:10px 30px}
.sweet-alert h2{font-size:16px;font-weight:400;position:relative;z-index:1}
.sweet-alert .lead{font-size:13px}
.sweet-alert .btn{padding:6px 12px;font-size:13px;margin:20px 2px 0;box-shadow:none!important}
.twitter-typeahead { width: 100% }
.twitter-typeahead .tt-menu {
  width: 100%;
  background: #fff;
  border: 1px solid #ced4da;
  border-radius: 5px;
  padding: .75rem 0
}
.twitter-typeahead .tt-menu .tt-suggestion {
  padding: .25rem .75rem;
  cursor: pointer
}
.twitter-typeahead .tt-menu .tt-suggestion:hover {
  background-color: #007bff;
  color: #fff;
}
.twitter-typeahead .empty-message {
  padding: 5px 10px;
  text-align: center
}
.twitter-typeahead .rtl-typeahead .tt-menu {
  text-align: right
}
.twitter-typeahead .league-name {
  margin: 0 10px 5px;
  padding: 7px 5px 10px;
  border-bottom: 1px solid #ced4da;
}
.scrollable-dropdown .twitter-typeahead .tt-menu {
  max-height: 80px;
  overflow-y: auto
}
.bootstrap-maxlength {margin-top: .5rem}
.palette-user.bg{background-color:#'.$thiscolor.'}
.palette-user.text{color:#'.$thiscolor.'}
.palette-Teal.bg{background-color:#009688}
.palette-Teal.text{color:#009688}
.palette-White.bg {background-color: #ffffff;}
.palette-White.text {color: #ffffff;}
#pass-strength-result{background-color:#00AFFF; max-width:200px;padding:5px;text-align:center;display:none;font-size:15px;color:#fff;border-radius:'.$borderradius.'}
#pass-strength-result.bad{background-color:#E81625;font-size:15px;color:#fff;}
#pass-strength-result.good{background-color:#F3AB19;font-size:15px;color:#000;}
#pass-strength-result.short{background-color:#A51C2A;font-size:15px;color:#fff;}
#pass-strength-result.strong{background-color:#1BF16A;font-size:15px;color:#000;}
.has-error{color:#f44242;}input.ng-invalid {color:white;border-bottom:1px solid #f44242;}
.node circle {cursor: pointer;fill: #fff;stroke:#'.$thiscolor.';stroke-width: 2px;}
.node text { font-size: 11px;}
path.link { fill: none;stroke: #ccc; stroke-width: 2px;}
@keyframes blink { 50% { opacity: 0.0; }}
@-webkit-keyframes blink { 50% { opacity: 0.0;}}
.text-blink{animation: blink 1s step-start 0s infinite;-webkit-animation: blink 1s step-start 0s infinite;}
.text-notblink{animation:none;-webkit-animation:none;}
.statusicon{font-size:1.5em;} .text-working{color:#'.$thiscolor.';} .text-red{color:#E81625;}  .text-done{color:#008000;}
.btnnm{line-height:40px;width:50px;height:50px;padding:2px;border:3px solid #fff;}   
.mqtree li{margin:0;list-style-type:none;position:relative;padding:10px 5px 0}
.mqtree li::before{content:"";position:absolute;top:0;width:1px;height:100%;right:auto;left:-20px;border-left:1px solid #ccc;bottom:50px}
.mqtree li::after{content:"";position:absolute;top:25px;width:25px;height:10px;right:auto;left:-20px;border-top:1px solid #ccc}
.mqtree li a{display:inline-block;}
.mqtree > ul > li::before,.mqtree > ul > li::after{border:0}
.mqtree li:last-child::before{height:25px}
.mqtree li a:hover,.mqtree li a:hover+ul li a{background-color:#4caf50!important;border-color:#449d48!important;}
.mqtree li a:hover+ul li::after,.mqtree li a:hover+ul li::before,.mqtree li a:hover+ul::before,.mqtree li a:hover+ul ul::before{border-color:#000}
.dropdown:hover .dropdown-menu {display: block;}
.forum-item{padding:10px 20px;width:100%;}
.forum-itemall:hover{cursor:pointer;}
.forum-item .title{margin-top: 0px;margin-bottom: 15px;font-weight: 300;}
.forum-item a{text-decoration: none;font-weight:bold;}
.forum-item p{text-align:justify;}
.forum-item .title{color:#'.$thiscolor.'}
.forum-item .ffooter{float:right;color:rgba(0,0,0,0.4);}
.forum-item .mainposttext{display:none;}
.sectionTitle h2{font-size: 3rem;color: #45555F;font-weight: 400;}
.has-divider{border-bottom: 1px solid #E8E8E8;padding-bottom: 30px;}
.btnbox .btn{text-align:left}
.btnbox i{float:right}
.twitter-typeahead{width:100%}
.twitter-typeahead .tt-menu{min-width:200px;background:#fff;box-shadow:0 2px 10px rgba(0,0,0,0.2);display:block!important;z-index:2!important;-webkit-transform:scale(0);-ms-transform:scale(0);-o-transform:scale(0);transform:scale(0);opacity:0;filter:alpha(opacity=0);-webkit-transition:all;-o-transition:all;transition:all;-webkit-transition-duration:300ms;transition-duration:300ms;-webkit-backface-visibility:hidden;-moz-backface-visibility:hidden;backface-visibility:hidden;-webkit-transform-origin:top left;-moz-transform-origin:top left;-ms-transform-origin:top left;transform-origin:top left}
.twitter-typeahead .tt-menu.tt-open:not(.tt-empty){-webkit-transform:scale(1);-ms-transform:scale(1);-o-transform:scale(1);transform:scale(1);opacity:1;filter:alpha(opacity=100)}
.twitter-typeahead .tt-suggestion{padding:8px 17px;color:#333;cursor:pointer}
.twitter-typeahead .tt-suggestion:hover,.twitter-typeahead .tt-cursor{background-color:rgba(0,0,0,0.075)}
.twitter-typeahead .tt-hint{color:#818181!important}
.ms-body .mimg{padding-right:15px; float:left;}
.my-message .mimg{padding-left: 15px;float:right;}
.ms-body .mimg .avatar{width:40px;height:40px;background:#ddd;border-radius:50%;text-align:center;padding-top:11px;font-size:14px;line-height:14px;border: 1px solid rgba(0,0,0,.2);}
.my-message {text-align:right}
.my-message .msb-item{background-color:#'.$thiscolor.'!important;color:#fff}
.ngctrl .checkbox .input-helper:before{width: 21px;height: 21px;border-radius: 0px;}
.ngctrl .checkbox .input-helper:after{font-weight:normal;font-size: 14px;}
.h2menu{display:none;}
.scrollbtn{cursor:pointer;}
.kanban h5{font-size: 16px;padding: 15px;}
.alert-kanban{box-shadow: #ddd 2px 2px 3px;border: 1px solid #ccc;cursor:pointer;padding:0px!important;margin-bottom:5px;display:block;word-wrap: break-word;text-align: center;}
.alert-kanban .kfooter{text-align:right;font-size:10px;padding:5px;border-top:1px solid #F5F5F5;}
.alert-kanban .kbody{padding:5px;}
.alert-kanban:hover{background:#f1f1f1;}
.grudivnt{border-radius:6px;display:none;width:100%;padding:10px 5px;margin-top: 10px;border:1px solid rgba(0,0,0,0.2);}
.grudivtoggled{display:block!important;}
.list-comma::before {content: ", ";}
.list-comma:first-child::before {content: "";}
.profileprev {border:1px dashed #'.$thiscolor.';background-color:#fff;background-size:cover; margin-top:7px;width:165px;height:165px;cursor:move;}
.imgprofile{border-radius:50%!important;border:1px solid #'.$thiscolor.';}
.noUi-target{border-radius:0;box-shadow:none;border:0}
.noUi-background{background:#d4d4d4;box-shadow:none}
.noUi-horizontal{height:3px}
.noUi-horizontal .noUi-handle{top:-8px}
.noUi-vertical{width:3px}
.noUi-connect{background:#'.$thiscolor.'}
.noUi-horizontal .noUi-handle,.noUi-vertical .noUi-handle{width:19px;height:19px;border:0;border-radius:100%;box-shadow:none;-webkit-transition:box-shadow;-o-transition:box-shadow;transition:box-shadow;-webkit-transition-duration:.2s;transition-duration:.2s;cursor:pointer;position:relative;background-color:#'.$thiscolor.'}
.noUi-horizontal .noUi-handle:after,.noUi-horizontal .noUi-handle:before,.noUi-vertical .noUi-handle:after,.noUi-vertical .noUi-handle:before{display:none}
.noUi-horizontal .noUactive,.noUi-vertical .noUactive{box-shadow:0 0 0 13px rgba(0,0,0,.1)}
.noUi-tooltip{border:0;background:#d4d4d4;padding:5px 10px}
.timesheets  > tbody > tr > td,.timesheets  > tbody > tr > th,.timesheets  > tfoot > tr > td,.timesheets  > tfoot > tr > th,.timesheets  > thead > tr > td,.timesheets  > thead > tr > th{padding:6px;}
.timesheets .weekend{background:#ddd;}.timesheets td{text-align:center;vertical-align: middle;}
.timesheets thead td,.timesheets .final{font-weight:bold;}.timesheets{overflow-x:scroll;}  
.timesheets td{border:1px solid #ccc!important;}
.timesheets .nowrap{white-space:nowrap;word-break: keep-all;}
.timesheets {display: block;font-size:16px; width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;  -ms-overflow-style: -ms-autohiding-scrollbar;} 
.agtstatus td{color:#fff;}
.agtstatus td a{color:#fff;font-weight:bold;}
.actions{list-style:none;padding:0;z-index:3;margin:0;margin-left:10px;}
.actions > li{display:inline-block;vertical-align:baseline}
.actions > li > a{width:30px;height:30px;display:inline-block;text-align:center;padding-top:5px;border-radius:50%}
.actions > li > a > i{color:#adadad;font-size:20px}
.actions > li > a:hover{background-color:rgba(0,0,0,0.08)}
.actions > li > a:hover > i{color:#000}
.actions > li > a,.actions > li > a > i{-webkit-transition:all;-o-transition:all;transition:all;-webkit-transition-duration:400ms;transition-duration:400ms}
.actions.a-alt > li > a > i{color:#fff}
.badge{font-weight: 400;border-radius:'.$borderradius.';}
.dropdown-toggle::after {  display:none;}
.middrmbut{border:0px solid transparent;}
@media (min-width: 768px) {
.modal-dialog{width:600px;margin:30px auto; max-width:1000px!important;}
.modal-sm{width:300px}
}
@media (min-width: 992px) {
  .modal-lg{width:900px}
}
.invoice{padding:30px!important;-webkit-print-color-adjust:exact!important}
.invoice .card-header{background:#eee!important;padding:20px;margin:-10px -30px 25px}
.invoice .block-header{display:none}
.invoice .highlight{background:#eee!important}
.input-helper:before,.input-helper:after,.checkbox label:before,.radio label:before,.radio-inline:before,.checkbox-inline:before{-webkit-transition:all;-o-transition:all;transition:all;-webkit-transition-duration:250ms;transition-duration:250ms}
.checkbox label,.radio label,.radio-inline,.checkbox-inline{padding-left:30px;position:relative}
.checkbox label:before,.radio label:before,.radio-inline:before,.checkbox-inline:before{content:"";width:47px;height:47px;border-radius:50%;background-color:rgba(0,0,0,0.04);position:absolute;left:-15px;top:-15px;opacity:0;filter:alpha(opacity=0);-webkit-transform:scale(0);-ms-transform:scale(0);-o-transform:scale(0);transform:scale(0)}
.checkbox label:active:before,.radio label:active:before,.radio-inline:active:before,.checkbox-inline:active:before{opacity:1;filter:alpha(opacity=100);-webkit-transform:scale(1);-ms-transform:scale(1);-o-transform:scale(1);transform:scale(1)}
.checkbox label,.radio label{display:block}
.checkbox input,.radio input{top:0;left:0;margin-left:0!important;z-index:1;cursor:pointer;opacity:0;filter:alpha(opacity=0);margin-top:0}
.checkbox input:checked + .input-helper:before,.radio input:checked + .input-helper:before{border-color:#'.$thiscolor.'}
.checkbox .input-helper:before,.radio .input-helper:before,.checkbox .input-helper:after,.radio .input-helper:after{position:absolute;content:""}
.checkbox .input-helper:before,.radio .input-helper:before{left:0;border:2px solid #7a7a7a}
.checkbox.disabled,.radio.disabled{opacity:.6;filter:alpha(opacity=60)}
.checkbox input{width:17px;height:17px}
.checkbox input:checked + .input-helper:before{background-color:#'.$thiscolor.'}
.checkbox input:checked + .input-helper:after{opacity:1;filter:alpha(opacity=100);-webkit-transform:scale(1);-ms-transform:scale(1);-o-transform:scale(1);transform:scale(1)}
.checkbox .input-helper:before{top:6px;width:17px;height:17px;}
.checkbox .input-helper:after{opacity:0;filter:alpha(opacity=0);-webkit-transform:scale(0);-ms-transform:scale(0);-o-transform:scale(0);transform:scale(0);content:"\F012C";font-family:"Material Design Icons";position:absolute;font-size:16px;left:-2px;top:2px;color:#fff;font-weight:700}
.radio input{width:19px;height:19px}
.radio input:checked + .input-helper:after{-webkit-transform:scale(1);-ms-transform:scale(1);-o-transform:scale(1);transform:scale(1)}
.radio .input-helper:before{top:-1px;width:19px;height:19px;border-radius:50%}
.radio .input-helper:after{width:9px;height:9px;background:#'.$thiscolor.';border-radius:50%;top:4px;left:5px;-webkit-transform:scale(0);-ms-transform:scale(0);-o-transform:scale(0);transform:scale(0)}
.checkbox-inline,.radio-inline{vertical-align:top;margin-top:0;padding-left:25px}
.dropdown-menu{font-size:inherit;}
.text-primary{color:#'.$thiscolor.'!important}
.btn-primary {background-color: #'.$thiscolor.';border-color:#'.$thiscolor.';}
.table td, .table th{vertical-align:middle;/*padding:0.4rem*/}
pre{display:block;padding:8.5px;margin:0 0 9px;font-size:12px;line-height:1.2;word-break:break-all;word-wrap:break-word;color:#ccc;background-color:#333;border:1px solid #333;border-radius:2px}
pre code{padding:0;font-size:inherit;color:inherit;white-space:pre-wrap;background-color:transparent;border-radius:0}
.pre-scrollable{max-height:340px;overflow-y:scroll}
body[data-color="light"] .loader{background:#fff;}
body[data-color="light"] #loginform .rounded-title{color: #010101;}
body[data-color="light"] .page-titles{background:#fff;margin:0 -15px 15px;padding:0;box-shadow: 0px 2px 4px #00000014;}
body[data-color="light"] .h1,body[data-color="light"] .h2,body[data-color="light"] .h3,body[data-color="light"] .h4,body[data-color="light"] .h5,body[data-color="light"] .h6,body[data-color="light"] h1,body[data-color="light"] h2,body[data-color="light"] h3,body[data-color="light"] h4,body[data-color="light"] h5,body[data-color="light"] h6,body[data-color="light"] .ms-container .ms-selectable li.ms-elem-selectable,body[data-color="light"] .ms-container .ms-selection li.ms-elem-selection,body[data-color="light"] .close,body[data-color="light"] .page-item .page-link {color: #000;}
body[data-color="light"] .page-titles h3{margin-bottom:0;margin-top:0px}
body[data-color="light"] .page-titlesinfo{margin:0 -15px 15px;}
body[data-color="light"] .btn-light{color:#636464;background-color:transparent;border-color:transparent;}
body[data-color="light"] .btn-white{background-color:#fff;border-color:transparent;}
body[data-color="light"] .btn-white:hover{background-color:#fdfdfd;}
body[data-color="light"] .btn-w{background-color:#fff;border-color:#fff;}
body[data-color="light"] .btn-light:hover, body[data-color="light"] .maincard:hover .btn-light,body[data-color="light"] .btn-w:hover{background-color:#0000001A;}
body[data-color="light"] .page-titlesinfo h3{margin-bottom:0;margin-top:0px}
body[data-color="light"] .page-titlesinfo .breadcrumb{padding:0;background:transparent;font-size:20px}
body[data-color="light"] .page-titlesinfo .breadcrumb li{margin-top:0;margin-bottom:0}
body[data-color="light"] .page-titlesinfo .breadcrumb .breadcrumb-item + .breadcrumb-item::before{content:"\F0142";font-family:"Material Design Icons";color:#a6b7bf;font-size:22px}
body[data-color="light"] .page-titlesinfo .breadcrumb .breadcrumb-item.active{color:#d5d6d8}
body[data-color="light"] .page-wrapper{background:#f2f2f2;padding-bottom:60px}
body[data-color="light"] .preloader{width:100%;height:100%;top:0;position:fixed;z-index:99999;background:#fff}
body[data-color="light"] .preloader .cssload-speeding-wheel{position:absolute;top:calc(50% - 3.5px);left:calc(50% - 3.5px)}
body[data-color="light"] .btn-primary,.btn-outline-primary{box-shadow:0 2px 2px rgba(116,96,238,0.05)}
body[data-color="light"] .btn-primary:hover,body[data-color="light"] .btn-outline-primary:hover{box-shadow:0 8px 15px rgba(116,96,238,0.3)}
body[data-color="light"] .btn-secondary,body[data-color="light"] .btn-outline-secondary{box-shadow:0 2px 2px rgba(114,123,132,0.05)}
body[data-color="light"] .btn-secondary:hover,body[data-color="light"] .btn-outline-secondary:hover{box-shadow:0 8px 15px rgba(114,123,132,0.3)}
body[data-color="light"] .btn-inverse {color: #fff;background-color: #2f3d4a;border-color: #2f3d4a;box-shadow: 0 1px 0 rgba(255, 255, 255, 0.15);}
body[data-color="light"] .btn-success,body[data-color="light"] .btn-outline-success{box-shadow:0 2px 2px rgba(33,193,214,0.05)}
body[data-color="light"] .btn-success:hover,body[data-color="light"] .btn-outline-success:hover{box-shadow:0 8px 15px rgba(33,193,214,0.3)}
body[data-color="light"] .btn-info,body[data-color="light"] .btn-outline-info{box-shadow:0 2px 2px rgba(30,136,229,0.05)}
body[data-color="light"] .btn-info:hover,body[data-color="light"] .btn-outline-info:hover{box-shadow:0 8px 15px rgba(30,136,229,0.3)}
body[data-color="light"] .btn-warning,body[data-color="light"] .btn-outline-warning{box-shadow:0 2px 2px rgba(255,178,43,0.05)}
body[data-color="light"] .btn-warning:hover,body[data-color="light"] .btn-outline-warning:hover{box-shadow:0 8px 15px rgba(255,178,43,0.3)}
body[data-color="light"] .btn-danger,body[data-color="light"] .btn-outline-danger{box-shadow:0 2px 2px rgba(252,75,108,0.05)}
body[data-color="light"] .btn-danger:hover,body[data-color="light"] .btn-outline-danger:hover{box-shadow:0 8px 15px rgba(252,75,108,0.3)}
body[data-color="light"] .btn-light,body[data-color="light"] .btn-outline-light{box-shadow:0 2px 2px rgba(242,244,248,0.05)}
body[data-color="light"] .btn-outline-light:hover{box-shadow:0 8px 15px rgba(242,244,248,0.3)}
body[data-color="light"] .btn-dark,body[data-color="light"] .btn-outline-dark{box-shadow:0 2px 2px rgba(33,37,41,0.05)}
body[data-color="light"] .btn-dark:hover,body[data-color="light"] .btn-outline-dark:hover{box-shadow:0 8px 15px rgba(33,37,41,0.3)}
body[data-color="light"] .btn-inverse,body[data-color="light"] .btn-outline-inverse{box-shadow:0 2px 2px rgba(47,61,74,0.05)}
body[data-color="light"] .btn-inverse:hover,body[data-color="light"] .btn-outline-inverse:hover{box-shadow:0 8px 15px rgba(47,61,74,0.3)}
body[data-color="light"] .btn-megna,body[data-color="light"] .btn-outline-megna{box-shadow:0 2px 2px rgba(0,137,123,0.05)}
body[data-color="light"] .btn-megna:hover,body[data-color="light"] .btn-outline-megna:hover{box-shadow:0 8px 15px rgba(0,137,123,0.3)}
body[data-color="light"] .btn-purple,body[data-color="light"] .btn-outline-purple{box-shadow:0 2px 2px rgba(116,96,238,0.05)}
body[data-color="light"] .btn-purple:hover,body[data-color="light"] .btn-outline-purple:hover{box-shadow:0 8px 15px rgba(116,96,238,0.3)}
body[data-color="light"] .btn-light-danger,body[data-color="light"] .btn-outline-light-danger{box-shadow:0 2px 2px rgba(249,231,235,0.05)}
body[data-color="light"] .btn-light-danger:hover,body[data-color="light"] .btn-outline-light-danger:hover{box-shadow:0 8px 15px rgba(249,231,235,0.3)}
body[data-color="light"] .btn-light-success,body[data-color="light"] .btn-outline-light-success{box-shadow:0 2px 2px rgba(232,253,235,0.05)}
body[data-color="light"] .btn-light-success:hover,body[data-color="light"] .btn-outline-light-success:hover{box-shadow:0 8px 15px rgba(232,253,235,0.3)}
body[data-color="light"] .btn-light-warning,body[data-color="light"] .btn-outline-light-warning{box-shadow:0 2px 2px rgba(255,248,236,0.05)}
body[data-color="light"] .btn-light-warning:hover,body[data-color="light"] .btn-outline-light-warning:hover{box-shadow:0 8px 15px rgba(255,248,236,0.3)}
body[data-color="light"] .btn-light-primary,body[data-color="light"] .btn-outline-light-primary{box-shadow:0 2px 2px rgba(241,239,253,0.05)}
body[data-color="light"] .btn-light-primary:hover,body[data-color="light"] .btn-outline-light-primary:hover{box-shadow:0 8px 15px rgba(241,239,253,0.3)}
body[data-color="light"] .btn-light-info,body[data-color="light"] .btn-outline-light-info{box-shadow:0 2px 2px rgba(207,236,254,0.05)}
body[data-color="light"] .btn-light-info:hover,body[data-color="light"] .btn-outline-light-info:hover{box-shadow:0 8px 15px rgba(207,236,254,0.3)}
body[data-color="light"] .btn-light-inverse,body[data-color="light"] .btn-outline-light-inverse{box-shadow:0 2px 2px rgba(246,246,246,0.05)}
body[data-color="light"] .btn-light-inverse:hover,body[data-color="light"] .btn-outline-light-inverse:hover{box-shadow:0 8px 15px rgba(246,246,246,0.3)}
body[data-color="light"] .btn-light-megna,body[data-color="light"] .btn-outline-light-megna{box-shadow:0 2px 2px rgba(224,242,244,0.05)}
body[data-color="light"] .btn-light-megna:hover,body[data-color="light"] .btn-outline-light-megna:hover{box-shadow:0 8px 15px rgba(224,242,244,0.3)}
body[data-color="light"] .profile-tab li a.nav-link,body[data-color="light"] .customtab li a.nav-link{color:#414651;}
body[data-color="light"] a.link:hover,a.link:focus{color:#'.$thiscolor.'!important}
body[data-color="light"] .bg-theme{background-color:#'.$thiscolor.'!important}
body[data-color="light"] .pagination > .active > a,body[data-color="light"] .pagination > .active > span,body[data-color="light"] .pagination > .active > a:hover,body[data-color="light"] .pagination > .active > span:hover,body[data-color="light"] .pagination > .active > a:focus,body[data-color="light"] .pagination > .active > span:focus{background-color:#'.$thiscolor.';color: #fff;border-color:#'.$thiscolor.'}
body[data-color="light"] .text-themecolor{color:#'.$thiscolor.'!important}
body[data-color="light"] .navunderline .nav-link.active{border-bottom:2px solid #'.$thiscolor.'!important;}
body[data-color="light"] .profile-tab li a.nav-link:hover,body[data-color="light"] .customtab li a.nav-link:hover{color:#'.$thiscolor.'}
body[data-color="light"] .btn-themecolor,body[data-color="light"] .btn-themecolor.disabled{background:#'.$thiscolor.';color:#fff;border:1px solid #'.$thiscolor.'}
body[data-color="light"] .btn-themecolor:hover,body[data-color="light"] .btn-themecolor.disabled:hover{background:#'.$thiscolor.';opacity:.7;border:1px solid #'.$thiscolor.'}
body[data-color="light"] .btn-themecolor.active,body[data-color="light"] .btn-themecolor:focus,.btn-themecolor.disabled.active,.btn-themecolor.disabled:focus{background:#028ee1}
body[data-color="light"] .navbar{background:#'.$thiscolor.';}
body[data-color="light"] .navbar-nav .itemicon{color:#fff;}
.search-box .app-search{background:#fff;}
body[data-color="dark"] .dropdown-menu{background-color:#414651;color:#fff;}
body[data-color="dark"] .dropdown-menu a{color:#fff!important;}
body[data-color="dark"] .dropdown-menu > ul > li:hover a{background-color:#212529!important;}
body[data-color="dark"] .loader{background:#263238;}
body[data-color="dark"] .card {background: #49515e;}
body[data-color="dark"] #loginform .rounded-title{color: #f1f1f1;}
body[data-color="dark"] {background-color:#263238;}
.table tr:hover td,.taskfin{border-color:#'.$thiscolor.';border-top:1px solid #'.$thiscolor.'!important;border-bottom:1px solid #'.$thiscolor.'!important;}
body[data-color="dark"] .h1, body[data-color="dark"] .h2, body[data-color="dark"] .h3, body[data-color="dark"] .h4, body[data-color="dark"] .h5, body[data-color="dark"] .h6, body[data-color="dark"] h1, body[data-color="dark"] h2,body[data-color="dark"]  h3, body[data-color="dark"] h4, body[data-color="dark"] h5,body[data-color="dark"]  h6,body[data-color="dark"]  .ms-container .ms-selectable li.ms-elem-selectable,body[data-color="dark"]  .ms-container .ms-selection li.ms-elem-selection,body[data-color="dark"]  .close,body[data-color="dark"]  .page-item .page-link {color: #fff;}
body[data-color="dark"] .page-titles{background:#31353d;margin:0 -15px 15px;box-shadow: 0px 1px 0px #3d424d;}
body[data-color="dark"] .page-titles h3{margin-bottom:0;margin-top:0px}
body[data-color="dark"] .navbar{background:#31353d;}
body[data-color="dark"] .page-titles .breadcrumb{padding:0;background:transparent;font-size:14px}
body[data-color="dark"] .page-titles .breadcrumb li{margin-top:0;margin-bottom:0}
body[data-color="dark"] .page-titles .breadcrumb .breadcrumb-item + .breadcrumb-item::before{content:"\F0DBB";font-family:"Material Design Icons";color:#a6b7bf;font-size:15px}
body[data-color="dark"] .page-titles .breadcrumb .breadcrumb-item.active{color:#d5d6d8}
body[data-color="dark"] .page-titlesinfo{margin:0 -15px 15px;}
body[data-color="dark"] .page-titlesinfo h3{margin-bottom:0;margin-top:0px}
body[data-color="dark"] .page-titlesinfo .breadcrumb{padding:0;background:transparent;font-size:14px}
body[data-color="dark"] .page-titlesinfo .breadcrumb li{margin-top:0;margin-bottom:0}
body[data-color="dark"] .page-titlesinfo .breadcrumb .breadcrumb-item + .breadcrumb-item::before{content:"\F0DBB";font-family:"Material Design Icons";color:#a6b7bf;font-size:15px}
body[data-color="dark"] .page-titlesinfo .breadcrumb .breadcrumb-item.active{color:#d5d6d8}
body[data-color="dark"] .page-wrapper{background:#1c1e23;padding-bottom:60px}
body[data-color="dark"] .table thead th,body[data-color="dark"]  .table th{color:#fff;}
body[data-color="dark"] .list-group-item,body[data-color="dark"]  .checkbox label::before,body[data-color="dark"]  .btn-outline-primary,body[data-color="dark"]  .customvtab .tabs-vertical li .nav-link.active,body[data-color="dark"]  .customvtab .tabs-vertical li .nav-link:hover,body[data-color="dark"]  .customvtab .tabs-vertical li .nav-link:focus,body[data-color="dark"]  .bd-example-popover-static,body[data-color="dark"]  .highlight,body[data-color="dark"]  .custom-file-control {background:transparent;}
body[data-color="dark"] .list-group-item-light.list-group-item-action:focus,body[data-color="dark"] .list-group-item-light.list-group-item-action:hover{color:#'.$thiscolor.';}
body[data-color="dark"] .preloader{width:100%;height:100%;top:0;position:fixed;z-index:99999;background:#fff}
body[data-color="dark"] .preloader .cssload-speeding-wheel{position:absolute;top:calc(50% - 3.5px);left:calc(50% - 3.5px)}
body[data-color="dark"] .btn-primary,body[data-color="dark"] .btn-outline-primary{box-shadow:0 2px 2px rgba(116,96,238,0.05)}
body[data-color="dark"] .btn-primary:hover,body[data-color="dark"] .btn-outline-primary:hover{box-shadow:0 8px 15px rgba(116,96,238,0.3)}
body[data-color="dark"] .btn-secondary,body[data-color="dark"] .btn-outline-secondary{box-shadow:0 2px 2px rgba(114,123,132,0.05)}
body[data-color="dark"] .btn-secondary:hover,body[data-color="dark"] .btn-outline-secondary:hover{box-shadow:0 8px 15px rgba(114,123,132,0.3)}
body[data-color="dark"] .btn-inverse {color: #2f3d4a;background-color: #fff;border-color: #fff;box-shadow: 0 1px 0 rgba(255, 255, 255, 0.15);}
body[data-color="dark"] .btn-success,body[data-color="dark"] .btn-outline-success{box-shadow:0 2px 2px rgba(33,193,214,0.05)}
body[data-color="dark"] .btn-success:hover,body[data-color="dark"] .btn-outline-success:hover{box-shadow:0 8px 15px rgba(33,193,214,0.3)}
body[data-color="dark"] .btn-info,body[data-color="dark"] .btn-outline-info{box-shadow:0 2px 2px rgba(30,136,229,0.05)}
body[data-color="dark"] .btn-info:hover,body[data-color="dark"] .btn-outline-info:hover{box-shadow:0 8px 15px rgba(30,136,229,0.3)}
body[data-color="dark"] .btn-warning,body[data-color="dark"] .btn-outline-warning{box-shadow:0 2px 2px rgba(255,178,43,0.05)}
body[data-color="dark"] .btn-warning:hover,body[data-color="dark"] .btn-outline-warning:hover{box-shadow:0 8px 15px rgba(255,178,43,0.3)}
body[data-color="dark"] .btn-danger,body[data-color="dark"] .btn-outline-danger{box-shadow:0 2px 2px rgba(252,75,108,0.05)}
body[data-color="dark"] .btn-danger:hover,body[data-color="dark"] .btn-outline-danger:hover{box-shadow:0 8px 15px rgba(252,75,108,0.3)}
body[data-color="dark"] .btn-outline-light{box-shadow:0 2px 2px rgba(242,244,248,0.05)}
body[data-color="dark"] .btn-outline-light:hover{box-shadow:0 8px 15px rgba(242,244,248,0.3)}
body[data-color="dark"] .btn-dark:hover,body[data-color="dark"] .btn-outline-dark:hover{box-shadow:0 8px 15px rgba(33,37,41,0.3)}
body[data-color="dark"] .btn-inverse,body[data-color="dark"] .btn-outline-inverse{box-shadow:0 2px 2px rgba(47,61,74,0.05)}
body[data-color="dark"] .btn-inverse:hover,body[data-color="dark"] .btn-outline-inverse:hover{box-shadow:0 8px 15px rgba(47,61,74,0.3)}
body[data-color="dark"] .btn-megna,body[data-color="dark"] .btn-outline-megna{box-shadow:0 2px 2px rgba(0,137,123,0.05)}
body[data-color="dark"] .btn-megna:hover,body[data-color="dark"] .btn-outline-megna:hover{box-shadow:0 8px 15px rgba(0,137,123,0.3)}
body[data-color="dark"] .btn-purple,body[data-color="dark"] .btn-outline-purple{box-shadow:0 2px 2px rgba(116,96,238,0.05)}
body[data-color="dark"] .btn-purple:hover,body[data-color="dark"] .btn-outline-purple:hover{box-shadow:0 8px 15px rgba(116,96,238,0.3)}
body[data-color="dark"] .btn-light-danger,body[data-color="dark"] .btn-outline-light-danger{box-shadow:0 2px 2px rgba(249,231,235,0.05)}
body[data-color="dark"] .btn-light-danger:hover,body[data-color="dark"] .btn-outline-light-danger:hover{box-shadow:0 8px 15px rgba(249,231,235,0.3)}
body[data-color="dark"] .btn-light-success,body[data-color="dark"] .btn-outline-light-success{box-shadow:0 2px 2px rgba(232,253,235,0.05)}
body[data-color="dark"] .btn-light-success:hover,body[data-color="dark"] .btn-outline-light-success:hover{box-shadow:0 8px 15px rgba(232,253,235,0.3)}
body[data-color="dark"] .btn-light-warning,body[data-color="dark"] .btn-outline-light-warning{box-shadow:0 2px 2px rgba(255,248,236,0.05)}
body[data-color="dark"] .btn-light-warning:hover,body[data-color="dark"] .btn-outline-light-warning:hover{box-shadow:0 8px 15px rgba(255,248,236,0.3)}
body[data-color="dark"] .btn-light-primary,body[data-color="dark"] .btn-outline-light-primary{box-shadow:0 2px 2px rgba(241,239,253,0.05)}
body[data-color="dark"] .btn-light-primary:hover,body[data-color="dark"] .btn-outline-light-primary:hover{box-shadow:0 8px 15px rgba(241,239,253,0.3)}
body[data-color="dark"] .btn-light-info,body[data-color="dark"] .btn-outline-light-info{box-shadow:0 2px 2px rgba(207,236,254,0.05)}
body[data-color="dark"] .btn-light-info:hover,body[data-color="dark"] .btn-outline-light-info:hover{box-shadow:0 8px 15px rgba(207,236,254,0.3)}
body[data-color="dark"] .btn-light-inverse,body[data-color="dark"] .btn-outline-light-inverse{box-shadow:0 2px 2px rgba(246,246,246,0.05)}
body[data-color="dark"] .btn-light-inverse:hover,body[data-color="dark"] .btn-outline-light-inverse:hover{box-shadow:0 8px 15px rgba(246,246,246,0.3)}
body[data-color="dark"] .btn-light-megna,body[data-color="dark"] .btn-outline-light-megna{box-shadow:0 2px 2px rgba(224,242,244,0.05)}
body[data-color="dark"] .btn-light-megna:hover,body[data-color="dark"] .btn-outline-light-megna:hover{box-shadow:0 8px 15px rgba(224,242,244,0.3)}
body[data-color="dark"] .text-muted {color: rgba(255,255,255,3) !important;}
body[data-color="dark"] tr:hover td{color:#fff!important;}
body[data-color="dark"] a.link:hover,body[data-color="dark"] a.link:focus{color:#'.$thiscolor.'!important}
body[data-color="dark"] .bg-theme{background-color:#'.$thiscolor.'!important}
body[data-color="dark"] .pagination > .active > a,body[data-color="dark"] .pagination > .active > span,body[data-color="dark"] .pagination > .active > a:hover,body[data-color="dark"] .pagination > .active > span:hover,body[data-color="dark"] .pagination > .active > a:focus,body[data-color="dark"] .pagination > .active > span:focus{color:#fff;background-color:#'.$thiscolor.';border-color:#'.$thiscolor.'}
body[data-color="dark"] .text-themecolor{color:#'.$thiscolor.'!important}
body[data-color="dark"] .profile-tab li a.nav-link.active,body[data-color="dark"] .customtab li a.nav-link.active{color:#fff;}
body[data-color="dark"] .profile-tab li a.nav-link:hover,body[data-color="dark"] .customtab li a.nav-link:hover{color:#'.$thiscolor.'}
body[data-color="dark"] .btn-themecolor,body[data-color="dark"] .btn-themecolor.disabled{background:#'.$thiscolor.';color:#fff;border:1px solid #'.$thiscolor.'}
body[data-color="dark"] .btn-themecolor:hover,body[data-color="dark"] .btn-themecolor.disabled:hover{background:#'.$thiscolor.';opacity:.7;border:1px solid #'.$thiscolor.'}
body[data-color="dark"] .btn-themecolor.active,body[data-color="dark"] .btn-themecolor:focus,body[data-color="dark"] .btn-themecolor.disabled.active,body[data-color="dark"] .btn-themecolor.disabled:focus{background:#028ee1}
body[data-color="dark"] .btn-w{color:#fff;background-color:#49515e;border-color:#49515e}
body[data-color="dark"] .btn-light{color:#fff;background-color:transparent;border-color:transparent}
body[data-color="dark"] .btn-light:hover,body[data-color="dark"] .btn-w:hover{color:#fff;background-color:#5a6268;border-color:#545b62}
body[data-color="dark"] .btn-light.focus,body[data-color="dark"] .btn-light:focus{color:#fff;background-color:#5a6268;border-color:#545b62;/*box-shadow:0 0 0 .2rem rgba(130,138,145,.5)*/}
body[data-color="dark"] .btn-light.disabled,.btn-light:disabled{color:#fff;background-color:#6c757d;border-color:#6c757d}
body[data-color="dark"] .btn-light:not(:disabled):not(.disabled).active,.btn-light:not(:disabled):not(.disabled):active,.show>.btn-light.dropdown-toggle{color:#fff;background-color:#545b62;border-color:#4e555b}
body[data-color="dark"] .btn-light:not(:disabled):not(.disabled).active:focus,.btn-light:not(:disabled):not(.disabled):active:focus,.show>.btn-light.dropdown-toggle:focus{/*box-shadow:0 0 0 .2rem rgba(130,138,145,.5)*/}
body[data-color="dark"] p, body[data-color="dark"] a, body[data-color="dark"] label{color:#f1f1f1;}
body[data-color="dark"] .topsearch,body[data-color="dark"] .form-control:not(.modal-body .form-control){background:transparent;color:#fff;border-color: #CAD0DB;}
body[data-color="dark"] .modal-header h4{color: #67757c;}
body[data-color="dark"] .modal-body p, body[data-color="dark"] .modal-body a, body[data-color="dark"] .modal-body label{color:#000;}
body[data-color="dark"] .user-profile .profile-text a{color:#798699!important}
.sidebar-footer{background:#414651}
body[data-color="dark"] .table{color:#d6d7d8;}
body[data-color="dark"] .footer,body[data-color="dark"] .customtab,body[data-color="dark"] .leftsidebar{background-color:#383f48!important;color:#fff!important;}
.media{border-width:1px!important;}
.media{border:1px solid rgba(120,130,140,0.13);margin-bottom:10px;padding:15px;border-radius:.25rem;}
.media{overflow:visible}
.media:before,.media:after{content:" ";display:table}
.media:after{clear:both}
.media:before,.media:after{content:" ";display:table}
.media:after{clear:both}
.media > .pull-left{padding-right:15px}
.media > .pull-right{padding-left:15px}
.media-heading{font-size:14px;margin-bottom:10px}
.media-body{zoom:1;display:block;width:auto}
.media-object{border-radius:2px}
.media{margin-top:15px}
.media:first-child{margin-top:0}
.media,.media-body{zoom:1;overflow:hidden}
.media-object{display:block}
.media-object.img-thumbnail{max-width:none}
.media-right,.media > .pull-right{padding-left:10px}
.media-left,.media > .pull-left{padding-right:10px}
.media-left,.media-right,.media-body{display:table-cell;vertical-align:top}
.media-middle{vertical-align:middle}
.media-bottom{vertical-align:bottom}
.media-heading{margin-top:0;margin-bottom:5px}
.media-list{padding-left:0;list-style:none}
.media i{padding-right:5px;}
@media (min-width: 1100px) {
.no-sidebar .container-fluid { max-width: 1280px; } 
}
.no-sidebar .container-fluid{padding:50px 0;}
.no-sidebar .page-wrapper{margin-left:0px;}
.no-sidebar .footer{left:0px;}
.section{padding: 100px 0;}
.bg-cover {background-size: cover;}
.bg-center {  background-position: center;}
.opacity-8 {  opacity: .8;}
.mask { position: absolute; top: 0; left: 0; width: 100%; height: 100%;}
.dark-bg { background-color: #1d1d33;}
.effect-section {  position: relative; overflow: hidden;}
.text-upper {  text-transform: uppercase;}
.white-color {  color: #fff!important;}
.m-btn.m-btn-theme {
  background: #'.$thiscolor.';
  color: #fff;
}
.m-btn {
  display: inline-block;
  border-radius: 0;
  padding: 0rem 1rem;
  font-size: .8rem;
  cursor: pointer;
  border: 1px solid transparent;
}
.mb-sm-0, .my-sm-0 {
  margin-bottom: 0 !important;
}
.form-controls {
  height: calc(1.5em + 1.375rem + 2px);
  padding: .6875rem .75rem;
  font-size: 1rem;
  border: 1px solid #ced4da;
background-color: #fff;
}
.display-4{font-weight: 600;font-size: 3rem;}
.btngrev span{margin-bottom:5px;}
.modal-header{border-bottom: none;}
.modal-content{background-color: #F5F5F5;border:none;box-shadow: 0px 10px 20px #0000000F;border-radius: 6px;}
.modal-footer{border-top:none;padding: .5em;}
select.form-control {
  moz-appearance: none;
  -webkit-appearance: none;
}
select.form-control{
  background-image:
  linear-gradient(45deg, transparent 50%, gray 50%),
  linear-gradient(135deg, gray 50%, transparent 50%),
  linear-gradient(to right, #ccc, #ccc);
background-position:
  calc(100% - 20px) calc(1em + 2px),
  calc(100% - 15px) calc(1em + 2px),
  calc(100% - 2.5em) 0.5em;
background-size: 5px 5px,  5px 5px, 0;
background-repeat: no-repeat;
}
select.form-control:focus {
  background-image:
    linear-gradient(45deg, green 50%, transparent 50%),
    linear-gradient(135deg, transparent 50%, green 50%),
    linear-gradient(to right, #ccc, #ccc);
  background-position:
    calc(100% - 15px) 1em,
    calc(100% - 20px) 1em,
    calc(100% - 2.5em) 0.5em;
  background-size:
    5px 5px,
    5px 5px,
    0;
  background-repeat: no-repeat;
  border-color: green;
  outline: 0;
}
.nav-tabs{border-bottom:0px solid transparent;}
.nav-tabs .nav-link {  border-radius: 0px;}
.ui-autocomplete-loading {
  background: white url("../images/ui-anim_basic_16x16.gif") right center no-repeat;
}
.ui-autocomplete{z-index:9999!important;}
.reqbtngroup{display:inherit;}
.reqbtngroup > .reqbtngroup, .reqbtngroup > .btn{margin-right: 6px;margin-bottom:6px;}
.rte-autocomplete{position:absolute;top:0;left:0;display:block;z-index:1100;float:left;min-width:160px;padding:5px 0;margin:2px 0 0;list-style:none;background-color:#fff;border:1px solid #ccc;border:1px solid rgba(0,0,0,0.2);-webkit-border-radius:6px;-moz-border-radius:6px;border-radius:6px;-webkit-box-shadow:0 5px 10px rgba(0,0,0,0.2);-moz-box-shadow:0 5px 10px rgba(0,0,0,0.2);box-shadow:0 5px 10px rgba(0,0,0,0.2);-webkit-background-clip:padding-box;-moz-background-clip:padding;background-clip:padding-box;font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;font-size:14px}
.rte-autocomplete:before{content:"";display:inline-block;border-left:7px solid transparent;border-right:7px solid transparent;border-bottom:7px solid #ccc;border-bottom-color:rgba(0,0,0,0.2);position:absolute;top:-7px;left:9px}
.rte-autocomplete:after{content:"";display:inline-block;border-left:6px solid transparent;border-right:6px solid transparent;border-bottom:6px solid #fff;position:absolute;top:-6px;left:10px}
.rte-autocomplete > li.loading{background:url("../images/ui-anim_basic_16x16.gif") center no-repeat;height:16px}
.rte-autocomplete > li > a{display:block;padding:3px 20px;clear:both;font-weight:400;line-height:20px;color:#333;white-space:nowrap;text-decoration:none}
.rte-autocomplete >li > a:hover,.rte-autocomplete > li > a:focus,.rte-autocomplete:hover > a,.rte-autocomplete:focus > a{color:#fff;text-decoration:none;background-color:#0081c2;background-image:-moz-linear-gradient(top,#08c,#0077b3);background-image:-webkit-gradient(linear,0 0,0 100%,from(#08c),to(#0077b3));background-image:-webkit-linear-gradient(top,#08c,#0077b3);background-image:-o-linear-gradient(top,#08c,#0077b3);background-image:linear-gradient(to bottom,#08c,#0077b3);background-repeat:repeat-x;filter:progid:DXImageTransform.Microsoft}
.rte-autocomplete >.active > a,.rte-autocomplete > .active > a:hover,.rte-autocomplete > .active > a:focus{color:#fff;text-decoration:none;background-color:#0081c2;background-image:-moz-linear-gradient(top,#08c,#0077b3);background-image:-webkit-gradient(linear,0 0,0 100%,from(#08c),to(#0077b3));background-image:-webkit-linear-gradient(top,#08c,#0077b3);background-image:-o-linear-gradient(top,#08c,#0077b3);background-image:linear-gradient(to bottom,#08c,#0077b3);background-repeat:repeat-x;outline:0;filter:progid:DXImageTransform.Microsoft}
span#autocomplete {font-weight: bold;}
.card .bg-theme{border-radius:'.$borderradius.';}
.no-sort::after { display: none!important; }
.no-sort { pointer-events: none!important; cursor: default!important; }
.DTFC_LeftBodyLiner{width:auto!important;}
.uavatar{text-align: center;  color: #fff;  background-color: rgb(245, 245, 246);  user-select: none;border-radius: 50%;padding: 9px;width: 40px; height:40px; display: block;}
.uavatar.uabig{width: 100px;height: 100px;  font-size: 51px;}
@-ms-keyframes spin {
  from { -ms-transform: rotate(0deg); }
  to { -ms-transform: rotate(360deg); }
}
@-moz-keyframes spin {
  from { -moz-transform: rotate(0deg); }
  to { -moz-transform: rotate(360deg); }
}
@-webkit-keyframes spin {
  from { -webkit-transform: rotate(0deg); }
  to { -webkit-transform: rotate(360deg); }
}
@keyframes spin {
  from {   transform:rotate(0deg);  }
  to {  transform:rotate(360deg);  }
}
.iconspin:before {
animation-name: spin;
animation-duration: 700ms;
animation-iteration-count: infinite;
animation-timing-function: linear;
}
.itemtext{font-size: 12px;display: block;text-align: center;margin-top: -6px;}
.itemicon{text-align: center;width: 100%;display: inline-block;}
.swal2-select{border:1px solid #F5F5F5;border-radius:3px;}
.card-link{font-size:.98rem;color:#333;display:inline-block;margin-top:1rem;transition:color .3s;text-transform:uppercase}
@media screen and (prefers-reduced-motion:reduce) {
.card-link{transition:none}
}
.card-link:hover{color:#666}
.cardwf{min-height:140px;display: flex;  flex-direction: column;  justify-content: space-between;}
.cardwf .btn{width: max-content;}
.cardpj{min-height:140px;display: flex;  flex-direction: column;  justify-content: space-between;}
@media (max-width: 767.98px) {
  .no-sidebar .container-fluid{padding:50px 20px;}
  .card{margin-left:20px; margin-right:20px;}
}
.badge-info{background-color:#'.$thiscolor.';}
.badge-secondary{background-color:#49515E;}
.badge-success{background-color:#0CC44F;}
.badge-danger{background-color:#E81625;}
.badge-warning{background-color:#F3AB19, 100%;}
.btn-danger {background-color: #E81625; border-color: #E81625;}
.btn-danger:hover,.btn-danger:active {background-color: #FF3953; border-color: #FF3953;}
.btn-success {background-color: #0CC44F; border-color: #0CC44F;}
.btn-success:hover,.btn-success:active {background-color: #338A41; border-color: #338A41;}
.btn-primary:hover,.btn-primary:active {background-color: #00D3FF; border-color: #00D3FF;}
.ui-menu-item:hover{background-color:#'.$thiscolor.';}
.has-warning .bar:before,.has-warning .bar:after{background:#ffb22b}
.has-success .bar:before,.has-success .bar:after{background:#21c1d6}
.has-error .bar:before,.has-error .bar:after{background:#fc4b6c}
.has-warning .form-control:focus ~ label:not(.chbl),.has-warning .form-control:valid ~ label:not(.chbl){color:#ffb22b}
.has-success .form-control:focus ~ label:not(.chbl),.has-success .form-control:valid ~ label:not(.chbl){color:#21c1d6}
.has-error .form-control:focus ~ label:not(.chbl),.has-error .form-control:valid ~ label:not(.chbl){color:#fc4b6c}
.has-feedback label:not(.chbl) ~ .t-0{top:0}
.login-box .form-control{font-size:1.1rem;padding: 5px 0;padding-left: 30px !important;}
.login-box .form-group .inpi{position: absolute;left: 2px;top: 11px;font-size: 1.1rem;}
.favnav{display:none;font-size: 17px;top:9px;padding-left: 0px;cursor:pointer;position:relative;}
.br-but{position: fixed;
  bottom: 65px;
  right: 20px;
  padding: 0px;
  border-radius: 100%;
  width: 45px;
  height: 45px;
  font-size: 25px;
  line-height: 25px;}
.navtop{height:50px;width:100%;vertical-align:middle;padding: 10px 35px 10px 15px;}
.navtop a{text-decoration:none;width:100%;height:100%;cursor:pointer;display:block;margin-top: 6px;}
.navtop span{position: relative;top: -2px;}
.navtop span.close{position: relative;right: -190px;top: -38px;display:none;}
.topsearch{background: #FFFFFF 0% 0% no-repeat padding-box;
  border-radius: 6px;
  border-color: #e4e6ef;
  padding-right:37px !important;
}
.form-control:focus{border-color:#00AFFF;}
.searchicon{position:absolute;font-size: 25px;right: 15px;top: 0px;}
.card-header{background:transparent;}
.sidebar-footer a:hover i{color:#fff!important;}
.form-modal .form-label{color:#717171;}
.form-modal .form-control{background: #FFFFFF 0% 0% no-repeat padding-box;box-shadow: 0px 2px 4px #0000000F;border:none;}
  .nav-tabs .nav-link.disabled{color: #b6c7d6!important;}  
  .border-arrow {
    position: relative;
}
.nav-tabs .nav-link.active{background:transparent;color:#'.$thiscolor.'!important;}
body[data-color="dark"] .border-arrow:after{border-left-color: #1c1e23;}
.btn-info:not(:disabled):not(.disabled).active, .btn-info:not(:disabled):not(.disabled):active, .show > .btn-info.dropdown-toggle{background-color: #0380FE;border-color: #0380FE;}
.anim-tada{animation: tada 1.5s ease infinite;}
.table td, .table th{border-top:1px solid transparent;}
table.dataTable td, table.dataTable th{border-bottom:1px solid #ccc;}
.contact-widget a:hover{ background: #f2f4f8; }
body[data-color="dark"]{color:#dfdfdf;}
.tablenohover td,.tablenohover th,.tablenohover tr:hover td{border-top:0px solid transparent!important;border-bottom:0px solid transparent!important;}
.table-success {background-color: #c1eef4; }
.table-danger {background-color: #fecdd6; }
.br-0{border-radius:0px;}
.ribbon { padding: 0 20px; height: 30px; line-height: 30px; clear: left; position: absolute; top: 12px; left: -2px;  color: #fff; }
.ribbon-right {  left: auto; right: -2px; }
.ribbon-default { background: #0000001A; color:#000;}
.ribbon-info { background: #'.$thiscolor.'; color:#fff;}
.ribbon-warning { background: #F3AB19; color:#fff;}
.ribbon-danger { background: #E81625; color:#fff;}
.dataTables_filter{display:none;}
.alert-danger {color: rgb(97, 26, 21);background-color: rgb(253, 236, 234);border-color: transparent;}
.alert-success {color: rgb(30, 70, 32);background-color: rgb(237, 247, 237);border-color: transparent;}
.alert-info {color: rgb(13, 60, 97);background-color: rgb(232, 244, 253);border-color: transparent;}
.alert-warning {color: rgb(102, 60, 0); background-color: rgb(255, 244, 229);border-color: transparent;}
.alert-light{border-color: #D0D7E3;}
.card-border{border: 2px solid #D5DCE7;box-shadow:none;}
.card-border:hover{border: 2px solid #49515E;}
.catcard{font-size:1rem;}
.catcard:hover:not(.selected){color:#000;}
.catcard.selected{border: 2px solid #0CC44F;}
.selinfo{width: 25px;
  height: 25px;
  background-color: #0CC44F;
  border-radius: 50%;
  position: absolute;
  right: -13px;
  top: 50%;
  margin-top: -13px;
  color: #fff;
  text-align: center;
display:none;}
.selinfo.selected{display:block;}
.usrdrdown{transform: translate3d(-100px, 5px, 0px);}
.navbar-light .navbar-toggler{border-color: transparent;}
.sidebarinfo{margin-top: 49px!important;}
.ml-0{margin-left:0px;}
.rte-autocomplete li a img{width:24px;}
.brtr-3{border-top-right-radius:3px;}
.brtl-3{border-top-left-radius:3px;}
.brbr-3{border-bottom-right-radius:3px;}
.brbl-3{border-bottom-left-radius:3px;}
.tox-tinymce{border:0px solid transparent!important;border-top:1px solid #ccc!important;}
.list-group-item-light{font-size:.9rem;}
.nav-link{padding:0px;padding-top:5px;}
.navbar-expand-md .navbar-nav .dropdown-menu{z-index:9999;}
.card-login{box-shadow: 0 25px 75px rgba(16,30,54,.25);}
.br-4{border-radius: .475rem;}


#styleradio [type="radio"]:not(:checked),#styleradio [type="radio"]:checked{position:absolute;left:-9999px;opacity:0}
#styleradio [type="radio"]:not(:checked) + label,#styleradio [type="radio"]:checked + label{position:relative;padding-left:16px;cursor:pointer;display:inline-block;height:8px;line-height:8px;font-size:1rem;transition:.28s ease;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}
#styleradio [type="radio"] + label:before,#styleradio [type="radio"] + label:after{content:"";position:absolute;left:0;top:0;margin:0px;border-radius:50%;width:25px;height:25px;z-index:0;transition:.28s ease}
#styleradio [type="radio"]:not(:checked) + label:before,#styleradio [type="radio"]:not(:checked) + label:after,#styleradio [type="radio"]:checked + label:before,#styleradio [type="radio"]:checked + label:after,{border-radius:50%}
#styleradio [type="radio"]:not(:checked) + label:before,#styleradio [type="radio"]:not(:checked) + label:after{border:2px solid #5a5a5a}
#styleradio [type="radio"]:not(:checked) + label:after{-webkit-transform:scale(0.5);transform:scale(0.5)}
#styleradio [type="radio"]:checked + label:before{border:2px solid transparent;animation:ripple .2s linear forwards}
#styleradio [type="radio"]:checked + label:after{border:2px solid #26a69a;background-color:#26a69a;z-index:0;-webkit-transform:scale(1.02);transform:scale(1.02)}
#styleradio [type="radio"].tabbed:focus + label:before{box-shadow:0 0 0 10px rgba(0,0,0,0.1);animation:ripple .2s linear forwards}
#styleradio [type="radio"]:not(:checked) + label{padding-left:30px;height:25px;line-height:25px;font-weight:400}
#styleradio [type="radio"]:checked + label{padding-left:30px;height:25px;line-height:25px;font-weight:400}
.taskfin{--bs-table-accent-bg: var(--bs-table-hover-bg);}
@media (min-width:992px) {
  .sidenav{height:calc(100vh - 84px);overflow:auto;position:fixed;}
  .sidenav-start{left:0}
  .sidenav-end{right:0}
}
.list{list-style-type:none;margin-bottom:0;padding-left:0}
.list-item+.list-item{margin-top:.35rem}
.list-link{align-items:center;color:#506690;display:flex;flex-wrap:nowrap;font-size:.9375rem}
.list-link:focus,.list-link:hover{color:#335eea;text-decoration:none}
.leftsidebar{margin-top: -1rem; margin-bottom: -1.1rem; min-height: 90vh;}
.hidden{display:none;}
';
$css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
$css = str_replace(': ', ':', $css);
$css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
echo $css;