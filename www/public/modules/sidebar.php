<br><div class="sticky-top" style="top:70px;">
    <h4><i class="mdi mdi-gesture-double-tap"></i>&nbsp;Navigation</h4>
    <br>
    <div class="list-group">
    <a href="/cp/?" class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action <?php echo $page=="dashboard"?"active":"";?>"><i class="mdi mdi-view-dashboard-outline"></i>&nbsp;Dashboard</a>
    <a href="/cpinfo" class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action <?php echo $page=="cpinfo"?"active":"";?>"><i class="mdi mdi-file-document-edit-outline"></i>&nbsp;Documentation</a>
    <a href="/env/apps" class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action <?php echo $page=="env"?"active":"";?>"><i class="mdi mdi-server-network"></i>&nbsp;Environment</a>
    <a href="/automation" class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action <?php echo $page=="automation"?"active":"";?>"><i class="mdi mdi-refresh-auto"></i>&nbsp;Automation</a>
    <a href="/webstat/charts" class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action <?php echo $page=="webstat"?"active":"";?>"><i class="mdi mdi-chart-multiple"></i>&nbsp;Statistics</a>
    <a href="/monitoring" class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action <?php echo $page=="monitoring"?"active":"";?>"><i class="mdi mdi-monitor-eye"></i>&nbsp;Monitoring</a>
    <a href="/appconfig" class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action <?php echo $page=="appconfig"?"active":"";?>"><i class="mdi mdi-cogs"></i>&nbsp;Midleo Configuration</a>
<hr> 
<?php if (method_exists("IBMMQ", "execJava") && is_callable(array("IBMMQ", "execJava"))) { ?>
    <a href="/mqscout/qm" class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action <?php echo $page=="mqscout"?"active":"";?>"><i class="mdi mdi-application-cog-outline"></i>&nbsp;MQScout</a>
   <?php } ?>
   <?php if (sessionClass::checkAcc($acclist, "tibcoadm,tibcoview")) {?>
    <a href="/tibcoscout/queues" class="waves-effect waves-light list-group-item list-group-item-light list-group-item-action <?php echo $page=="tibcoscout"?"active":"";?>"><i class="mdi mdi-application-cog-outline"></i>&nbsp;Tibco Scout</a>
    <?php } ?>
</div>
</div>