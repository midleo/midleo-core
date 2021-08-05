<?php
$modulelist["kanban"]["name"] = "Kanban Board";
$modulelist["kanban"]["css"][] = str_replace($maindir, "", dirname($filename)) . "/assets/css/kanban.min.css";
$modulelist["kanban"]["js"][] = str_replace($maindir, "", dirname($filename)) . "/assets/js/kanban.js";

class Class_kanban
{
    public static function getPage($thisarray)
    {
        global $bsteps;
        global $installedapp;
        global $website;
        global $page;
        global $modulelist;
        global $maindir;
        if (!empty($bsteps)) {
            $temp["bsteps"] = array();
            foreach (json_decode($bsteps, true) as $keyin => $valin) {
                $temp["bsteps"][$valin["nameshort"]]["name"] = $valin["name"];
                $temp["bsteps"][$valin["nameshort"]]["color"] = $valin["color"];
            }
        } else { $temp["bsteps"] = array(); }
        if ($installedapp != "yes") {header("Location: /install");}
        sessionClass::page_protect(base64_encode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
        $pdo = pdodb::connect();
        $err = array();
        $msg = array();
        $year = date("Y");
        $kanopts = "0";
        if (!empty($_POST["thisyear"])) {$year = htmlspecialchars($_POST["thisyear"]);}
        if (!empty($_POST["kanopts"])) {$kanopts = htmlspecialchars($_POST["kanopts"]);}
        $data=sessionClass::getSessUserData(); foreach($data as $key=>$val){  ${$key}=$val; } 
        if (!sessionClass::checkAcc($acclist, "kanban")) { header("Location:/cp/?");}
        include "public/modules/css.php";
        foreach ($modulelist["kanban"]["css"] as $csskey => $csslink) {
            if (!empty($csslink)) {?><link rel="stylesheet" type="text/css" href="<?php echo $csslink; ?>"><?php }
        }
        echo '</head><body class="fix-header card-no-border"><div id="main-wrapper">';
        $breadcrumb["text"]="Kanban board";
        include "public/modules/headcontent.php";?>
        <div class="page-wrapper"><div class="container-fluid">
        <br>
    <div class="form-group">
        <form method="post" action="" class="form-material">
        <div class="row">
      <div class="col-md-3">
        <select class="form-control topsearch" name="thisyear"  onchange="this.form.submit()">
         <?php echo (!empty($year) ? '<option value="' . $year . '">' . $year . '</option>' : '<option value="">Year</option>'); ?>
       <?php for ($i = -5; $i < 6; $i++) {?><option value="<?php echo date('Y', strtotime('+' . $i . ' year')); ?>" ><?php echo date('Y', strtotime('+' . $i . ' year')); ?></option><?php }?>
     <option value="">This year</option>
     </select>
        </div>
        <div class="col-md-9 text-end">
              <label class="btn btn-info <?php echo $kanopts == "0" ? "active" : ""; ?>"><input type="radio" class="btn-check" name="kanopts" value="0"  <?php echo $kanopts == "0" ? "checked" : ""; ?> onclick="this.form.submit();">My own</label>
              <label class="btn btn-info <?php echo $kanopts == "1" ? "active" : ""; ?>"><input type="radio" class="btn-check" name="kanopts" value="1"  <?php echo $kanopts == "1" ? "checked" : ""; ?> onclick="this.form.submit();">All</label>
        </div>
    </div>
    </form>
     </div>
   
<div class="card">
<div class="card-body" >


     <div id="kanban"></div>
     </div></div>
   </div>
  </div>
      <?php include "public/modules/footer.php";?>
      </div></div>

    <?php include "public/modules/js.php";?>
    <?php if(!empty($widarr) && !empty($ugrarr)){ ?>
    <?php foreach ($modulelist["kanban"]["js"] as $jskey => $jslink) {
            if (!empty($jslink)) {?><script type="text/javascript" src="<?php echo $jslink; ?>"></script><?php }
        }?>
    <script type="text/javascript">
    $('#kanban').kanban({
      <?php if (!empty($temp["bsteps"]) && count($temp["bsteps"])>0) {?>
        titles: [ 'Not defined', <?php foreach ($temp["bsteps"] as $keyin => $valin) {echo "'" . $valin["name"] . "',";}?>],
        colours: [ '#000', <?php foreach ($temp["bsteps"] as $keyin => $valin) {echo "'" . $valin["color"] . "',";}?>],
      <?php } else {?>
        titles: ['Not defined'],
        colours: ['#000'],
        <?php }?>
        <?php $sql = "
SELECT requests.sname,
        requests.projapproved,
        requests.wfstep,
        requests.projconfirmed,
        requests.reqname,
        requests.deadline,
        requests.assigned,
        requests.wfbstep,
        requests.deployed ,
        users.mainuser,
        users.email ,
        users.fullname,
        users.avatar
FROM requests
LEFT JOIN users
   ON requests.assigned = users.mainuser
WHERE 1=1" . ($kanopts == "0" ? "
       AND requests.assigned='" . htmlspecialchars($_SESSION['user']) . "'" : "") . "
       AND requests.wfunit IN (" . str_repeat('?,', count($ugrarr) - 1) . '?' . ")
       AND requests.wid IN (" . str_repeat('?,', count($widarr) - 1) . '?' . ")
       AND " . ((DBTYPE == "oracle" || DBTYPE == "postgresql") ? "EXTRACT(YEAR FROM created)=?" : "YEAR(created)=?");
        $q = $pdo->prepare($sql);
        $q->execute(array_merge($ugrarr, $widarr, array($year)));
        if ($zobj = $q->fetchAll()) {
            $arrreq = array();
            $id = 0;
            foreach ($zobj as $val) {
              $id++;
                $arrreq[] = array(
                    "id" => $id,
                    "title" => $val["reqname"],
                    "block" => (!empty($val["wfbstep"]) ? $temp["bsteps"][$val["wfbstep"]]["name"] : "Not defined"),
                    "link" => "/reqinfo/" . $val["sname"],
                    "link_text" => $val["sname"],
                    "footer" => !empty($val["assigned"]) ? $val["assigned"] : "not assigned",
                    "footer_avatar" => !empty($val["avatar"]) ? $val["avatar"] : "/assets/images/avatar.svg",
                    "footer_avatar_name" => !empty($val["fullname"]) ? $val["fullname"] : "",
                    "div_color" => (!empty($val["wfbstep"]) ? $temp["bsteps"][$val["wfbstep"]]["color"] : "#000"),
                );
            }
            echo "items: " . json_encode($arrreq);
        } else {echo "items: []";}?>
    });

</script>
<?php } ?>

    <?php }
}