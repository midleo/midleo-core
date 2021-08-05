<?php if(empty($thisarray['p2'])){ include "applist.php"; } else { ?>
    <div class="row p-2">
            <div class="col-md-3 position-relative">
                <input type="text" ng-model="search" class="form-control topsearch"
                    placeholder="Find a dns entry">
                    <span class="searchicon"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-search" xlink:href="/assets/images/icon/midleoicons.svg#i-search"/></svg>
            </div>
            <div class="col-md-9 text-end">
                    <?php if ($_SESSION['user_level'] >= 3 && !in_array($thisarray['p1'], array("packages", "appservers", "servers", "import", "deploy", "flows", "fte"))) {?><span><button
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Export the objects in excel" type="button"
                            class="waves-effect waves-light btn btn-light"
                            ng-click="exportData('<?php echo $thisarray['p1']; ?>')"><i
                                class="mdi mdi-file-excel-outline "></i>Export&nbsp;<svg class="midico midico-outline" ><use href="/assets/images/icon/midleoicons.svg#i-up" xlink:href="/assets/images/icon/midleoicons.svg#i-up"/></svg></button> </span><?php }?>
                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="Add new dns rule"><button
                            type="button" class="waves-effect waves-light btn btn-info" data-bs-toggle="modal"
                            href="#modal-obj-form"
                            ng-click="showCreateFormDns()"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-add" xlink:href="/assets/images/icon/midleoicons.svg#i-add"/></svg>&nbsp;Create</button></span>
                    
            </div>
        </div><br>
<div class="card ">
    <div class="card-body p-0">
        
        <div class="row">
            <div class="col-md-12">
                <table class="table table-vmiddle table-hover stylish-table mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">Application</th>
                            <th class="text-center">DNS Name</th>
                            <th class="text-center">TTL</th>
                            <th class="text-center">Class</th>
                            <th class="text-center">Type</th>
                            <th class="text-center">Record</th>
                            <th class="text-center" style="width:120px;">Action</th>
                        </tr>
                    </thead>
                    <tbody ng-init="getAlldns('<?php echo $thisarray['p2'];?>')">
                        <tr ng-hide="contentLoaded">
                            <td colspan="7" style="text-align:center;font-size:1.1em;"><i
                                    class="mdi mdi-loading iconspin"></i>&nbsp;Loading...</td>
                        </tr>
                        <tr id="contloaded" class="hide"
                            dir-paginate="d in names | filter:search | orderBy:'dnsname':reverse | itemsPerPage:10"
                            pagination-id="prodx">
                            <td class="text-center">{{ d.proj }}</td>
                            <td class="text-center">{{ d.dnsname}}</td>
                            <td class="text-center">{{ d.ttl}}</td>
                            <td class="text-center">{{ d.dnsclass}}</td>
                            <td class="text-center">{{ d.dnstype }}</td>
                            <td class="text-center" style="max-width:350px;">{{ d.dnsrecord }}</td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button type="button" ng-click="readOnedns(d.id)"
                                        class="btn waves-effect btn-light btn-sm"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-edit" xlink:href="/assets/images/icon/midleoicons.svg#i-edit"/></svg></button>
                                    <?php if($_SESSION['user_level']>=3){?><button type="button"
                                        ng-click="deletedns(d.id,'<?php echo $_SESSION['user'];?>',d.dnsname,'<?php echo $thisarray['p2'];?>')"
                                        class="btn waves-effect btn-light btn-sm"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x"/></svg></button><?php } ?>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <dir-pagination-controls pagination-id="prodx" boundary-links="true"
                    on-page-change="pageChangeHandler(newPageNumber)" template-url="/assets/templ/pagination.tpl.html">
                </dir-pagination-controls>
                <div class="modal" id="modal-obj-form" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header"><h4>DNS Entry</h4></div>
                            <form name="form" ng-app>
                                <div class="modal-body container form-material"
                                    style="width:100%;min-height:300px;max-height:500px;overflow-x:hidden;overflow-y:scroll;">
                                    <div class="form-group row">
                                        <label class="form-control-label text-lg-right col-md-3">Tags</label>
                                        <div class="col-md-8"><input id="tags" data-role="tagsinput" type="text"
                                                class="form-control"></div>
                                        <div class="col-md-1" style="padding-left:0px;"><button type="button"
                                                class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="You can search this object with tags"><i
                                                    class="mdi mdi-information-variant mdi-18px"></i></button></div>
                                    </div>
                                    <?php if(isset($modulelist["budget"]) && !empty($modulelist["budget"])){ ?>
                                    <input ng-model="dns.proj" style="display:none;"
                                        value="<?php echo $thisarray['p2'];?>">
                                    <?php } ?>
                                    <div class="form-group row">
                                        <label class="form-control-label text-lg-right col-md-3">Server</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control autocomplsrv"
                                                placeholder="write the server name" />
                                            <input type="text" id="server" style="display:none;" />
                                            <input type="text" id="serverid" style="display:none;" />
                                            <input type="text" id="serverip" style="display:none;" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="form-control-label text-lg-right col-md-3">DNS name</label>
                                        <div class="col-md-9"> <input ng-model="dns.dnsname" ng-required="true"
                                                type="text" class="form-control"></div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="form-control-label text-lg-right col-md-3">TTL</label>
                                        <div class="col-md-9"> <input ng-model="dns.ttl" ng-required="true" type="text"
                                                class="form-control"></div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="form-control-label text-lg-right col-md-3">Class</label>
                                        <div class="col-md-9">
                                            <select name="dnsclass" ng-model="dns.dnsclass" ng-required="true"
                                                class="form-control">
                                                <option value="">Please select</option>
                                                <option value="IN">Internet</option>
                                                <option value="CH">Chaos</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="form-control-label text-lg-right col-md-3">DNS Type</label>
                                        <div class="col-md-9">
                                            <select name="dnsclass" ng-model="dns.dnstype" ng-required="true"
                                                class="form-control">
                                                <option value="">Please select</option>
                                                <option value="A">Address record</option>
                                                <option value="AAAA">IPv6 address record</option>
                                                <option value="AFSDB">AFS database record </option>
                                                <option value="APL">Address Prefix List </option>
                                                <option value="CAA">Certification Authority Authorization </option>
                                                <option value="CDS">Child DS </option>
                                                <option value="CERT">Certificate record</option>
                                                <option value="CNAME">Canonical name record</option>
                                                <option value="CSYNC">Child-to-Parent Synchronization</option>
                                                <option value="DHCID">DHCP identifier</option>
                                                <option value="DLV">DNSSEC Lookaside Validation record</option>
                                                <option value="DNSKEY">DNS Key record</option>
                                                <option value="DS">Delegation signer</option>
                                                <option value="HINFO">Host Information</option>
                                                <option value="HIP">Host Identity Protocol</option>
                                                <option value="IPSECKEY">IPsec Key</option>
                                                <option value="KEY">Key record</option>
                                                <option value="KX">Key Exchanger record</option>
                                                <option value="LOC">Location record</option>
                                                <option value="MX">Mail exchange record</option>
                                                <option value="NAPTR">Naming Authority Pointer</option>
                                                <option value="NS">Name server record</option>
                                                <option value="NSEC">Next Secure record</option>
                                                <option value="OPENPGPKEY">OpenPGP public key record</option>
                                                <option value="PTR">PTR Resource Record</option>
                                                <option value="SIG">Signature</option>
                                                <option value="SRV">Service locator</option>
                                                <option value="SSHFP">SSH Public Key Fingerprint</option>
                                                <option value="TXT">Text record</option>
                                                <option value="URI">Uniform Resource Identifier</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="form-control-label text-lg-right col-md-3">Record</label>
                                        <div class="col-md-9"> <textarea ng-model="dns.dnsrecord" ng-required="true"
                                                class="form-control"></textarea></div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-x" xlink:href="/assets/images/icon/midleoicons.svg#i-x"/></svg>&nbsp;Close</button>
                               
                                    <?php if($_SESSION['user_level']>=3){?>
                                    <button type="button" id="btn-create-obj"
                                        class="waves-effect waves-light btn btn-info btn-sm"
                                        ng-click="form.$valid && createdns('<?php echo $thisarray['p2'];?>','<?php echo $_SESSION['user'];?>')"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-check" xlink:href="/assets/images/icon/midleoicons.svg#i-check"/></svg>&nbsp;Create</button>
                                    <button type="button" id="btn-update-obj"
                                        class="waves-effect waves-light btn btn-info btn-sm"
                                        ng-click="updatedns('<?php echo $thisarray['p2'];?>','<?php echo $_SESSION['user'];?>')"><svg class="midico midico-outline"><use href="/assets/images/icon/midleoicons.svg#i-save" xlink:href="/assets/images/icon/midleoicons.svg#i-save"/></svg>&nbsp;Save Changes</button>
                                    <?php } ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
        <?php } ?>