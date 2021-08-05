<?php
if (!empty($thisarray['p2'])){
    $sql="select " . (DBTYPE == 'oracle' ? "to_char(imgdata) as imgdata" : "imgdata") . " from config_diagrams where desid=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($thisarray['p2']));
    if($val = $q->fetch(PDO::FETCH_ASSOC)){ ?>
<div class="card">
<div class="card-body">
<img src="<?php echo $val["imgdata"];?>" class="img img-fluid">
</div>
</div>
    <?php
    }
}
?>