<div class="row">
    <div class="col-lg-12 pe-0 ps-0">
        <div class="card p-0">
            <table class="table table-vmiddle table-hover stylish-table mb-0">
                <thead>
                    <tr>
                        <th class="text-center">APP code</th>
                        <th class="text-center">Information</th>
                        <th class="text-center">Created on</th>
                    </tr>
                </thead>
                <tbody ng-init="getAllapps()">
                    <tr ng-hide="contentLoaded">
                        <td colspan="5" style="text-align:center;font-size:1.1em;"><i
                                class="mdi mdi-loading iconspin"></i>&nbsp;Loading...</td>
                    </tr>
                    <tr id="contloaded" class="hide"
                        dir-paginate="d in names | filter:search | orderBy:'appcode' | itemsPerPage:10"
                        pagination-id="prodx">
                        <td class="text-center"><a href="/env/<?php echo $thisarray['p1'];?>/{{ d.appcode }}"
                                target="_parent">{{ d.appcode }}</a></td>
                        <td class="text-center">{{ d.appinfo }}</td>
                        <td class="text-center">{{ d.appcreated }}</td>
                    </tr>
                </tbody>
            </table>
            <dir-pagination-controls pagination-id="prodx" boundary-links="true"
                on-page-change="pageChangeHandler(newPageNumber)" template-url="/<?php echo $website['corebase'];?>assets/templ/pagination.tpl.html">
            </dir-pagination-controls>
        </div>
    </div>
</div>