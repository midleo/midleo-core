<div class="sticky-top">
    <br>
    <h4><i class="mdi mdi-gesture-double-tap"></i>&nbsp;Navigation</h4>
    <br>
    <nav class="sidebar-nav">
        <ul id="sidebarnav">
            <li class="row">
                <a class="waves-effect waves-dark col-md-12 <?php echo $page=="dashboard"?"active":"";?>"
                    href="/cp/?"><i class="mdi mdi-view-dashboard-outline"></i>&nbsp;Dashboard</a>
            </li>
            <li class="row">
                <a class="waves-effect waves-dark col-md-12 <?php echo $page=="cpinfo"?"active":"";?>" href="/cpinfo"><i
                        class="mdi mdi-file-document-edit-outline"></i>&nbsp;Documentation</a>
            </li>
            <li class="row">
                <a class="waves-effect waves-dark col-md-12 <?php echo $page=="env"?"active":"";?>" href="/env/apps"><i
                        class="mdi mdi-server-network"></i>&nbsp;Environment</a>
            </li>
            <li class="row">
                <a class="waves-effect waves-dark col-md-12 <?php echo $page=="automation"?"active":"";?>"
                    href="/automation"><i class="mdi mdi-refresh-auto"></i>&nbsp;Automation</a>
            </li>
            <li class="row">
                <a class="waves-effect waves-dark col-md-12 <?php echo $page=="webstat"?"active":"";?>"
                    href="/webstat/charts"><i class="mdi mdi-chart-multiple"></i>&nbsp;Statistics</a>
            </li>
            <li class="row">
                <a class="waves-effect waves-dark col-md-12 <?php echo $page=="monitoring"?"active":"";?>"
                    href="/monitoring"><i class="mdi mdi-monitor-eye"></i>&nbsp;Monitoring</a>
            </li>
            <li class="row">
                <a class="waves-effect waves-dark col-md-12 <?php echo $page=="appconfig"?"active":"";?>"
                    href="/appconfig"><i class="mdi mdi-cogs"></i>&nbsp;Midleo Configuration</a>
            </li>
        </ul>
    </nav>
</div>