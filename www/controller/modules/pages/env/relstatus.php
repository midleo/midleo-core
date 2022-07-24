<?php
function searcharray($value, $key, $array, $add="") {
    $arr=array();
    foreach ($array as $k => $val) {
        if (strpos($val[$key], $value) !== false) {
            if($add){
                if (strpos($val[$key], $add) !== false) {
                    $arr[$k]=$val[$key]." ver:".$val["version"]; 
                }
            } else { 
                $arr[$k]=$val[$key]." ver:".$val["version"]; 
            }
        }
    }
    return array_pop($arr);
 }
?>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="datainfo display nowrap table row-border" cellspacing="0" width="100%"
                style="margin-top:0px!important;">
                <thead>
                    <tr>
                        <th>Server</th>
                        <th>Software</th>
                        <th>Latest version</th>
                        <th>Currently installed</th>
                        <th>Upgrade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $sql="select releasename, relversion, latestver from env_releases";
$q = $pdo->prepare($sql);
$q->execute();
if ($zobj = $q->fetchAll()) {
    foreach ($zobj as $val) {
        if($val["latestver"]){
            $sqlin="select serverdns,serverprog from env_servers where serverprog like ?";
            $qin = $pdo->prepare($sqlin);
            $qin->execute(array("%".$val["releasename"]."%"));
            if ($zobjin = $qin->fetchAll()) {
                foreach ($zobjin as $valin) {
                   $arr=json_decode($valin["serverprog"],true);
                   $find=searcharray($val["releasename"],"name",$arr,$val["relversion"]);
                   if($find){ 
                    echo "<tr class='".((strpos($find, $val["latestver"]) !== false)?"alert-success":"alert-danger")."'><td>".$valin["serverdns"]."</td><td>".$val["releasename"]."</td><td>".$val["latestver"]."</td><td>".$find."</td><td>".((strpos($find, $val["latestver"]) !== false)?"No":"Yes")."</td></tr>"; 
                   }
                }
            }
        }
    }
}
?>
                </tbody>
            </table>
        </div>
    </div>
</div>