<div class="card p-0 sticky-top" style="top:70px;z-index:100;">
  <div class="list-group ">
  <a href="/mqscout/qm/<?php echo $thisarray['p2'];?>" class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action <?php echo $thisarray['p1']=="qm"?"active":"";?>">Qmanager</a>
  <a href="/mqscout/queues/<?php echo $thisarray['p2'];?>" class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action <?php echo $thisarray['p1']=="queues"?"active":"";?>">Queues</a>
  <a href="/mqscout/channels/<?php echo $thisarray['p2'];?>" class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action <?php echo $thisarray['p1']=="channels"?"active":"";?>">Channels</a>
  <a href="/mqscout/topics/<?php echo $thisarray['p2'];?>" class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action <?php echo $thisarray['p1']=="topics"?"active":"";?>">Topics</a>
  <a href="/mqscout/subs/<?php echo $thisarray['p2'];?>" class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action <?php echo $thisarray['p1']=="subs"?"active":"";?>">Subscription</a>
  <a href="/mqscout/process/<?php echo $thisarray['p2'];?>" class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action <?php echo $thisarray['p1']=="process"?"active":"";?>">Process</a>
  <a href="/mqscout/service/<?php echo $thisarray['p2'];?>" class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action <?php echo $thisarray['p1']=="service"?"active":"";?>">Service</a>
  <a href="/mqscout/dlqh/<?php echo $thisarray['p2'];?>" class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action <?php echo $thisarray['p1']=="dlqh"?"active":"";?>">DLQH</a>
  <a href="/mqscout/authrec/<?php echo $thisarray['p2'];?>" class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action <?php echo $thisarray['p1']=="authrec"?"active":"";?>">Authrec</a>
  <hr class="mt-0 mb-0">
  <a href="/mqscout/vars/<?php echo $thisarray['p2'];?>" class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action <?php echo $thisarray['p1']=="vars"?"active":"";?>">Variables</a>
  <a href="/mqscout/packages/<?php echo $thisarray['p2'];?>" class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action <?php echo $thisarray['p1']=="packages"?"active":"";?>">Packages</a>
  <a href="/mqscout/deploy/<?php echo $thisarray['p2'];?>" class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action <?php echo $thisarray['p1']=="deploy"?"active":"";?>">Deploy</a>
  <a href="/mqscout/import/<?php echo $thisarray['p2'];?>" class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action <?php echo $thisarray['p1']=="import"?"active":"";?>">Import</a>
</div>
</div>
<?php 
$brtemp=array();
if($thisarray['p2']) { 
    $brtemp[]="QM:".$thisarray['p2']."<span><a href='/mqscout/qm/' target='_parent'><i class='mdi mdi-close'></i></a></span>"; 
}
if($thisarray['p3']){ 
    $brtemp[]="Project:".$thisarray['p3']."<span><a href='/mqscout/qm/".$thisarray['p2']."' target='_parent'><i class='mdi mdi-close'></i></a></span>";
}
?>