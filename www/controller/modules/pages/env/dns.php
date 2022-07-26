<?php if(empty($thisarray['p2'])){ include "applist.php"; } else {
    array_push($brarr,array(
        "title"=>"Export in excel",
        "link"=>"#",
        "nglink"=>"exportData('".$thisarray['p1']."')",
        "icon"=>"mdi-file-excel",
        "active"=>false,
      ));
      array_push($brarr,array(
        "title"=>"Define new",
        "link"=>"#modal-obj-form",
        "nglink"=>"showCreateFormDns()",
        "modal"=>true,
        "icon"=>"mdi-plus",
        "active"=>false,
      ));
    ?>
<div class="row">
    <div class="col-lg-12 ">
        <div class="card p-0">
            <table class="table table-vmiddle table-hover stylish-table mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">Application</th>
                            <th class="text-center">DNS Name</th>
                            <th class="text-center">TTL</th>
                            <th class="text-center">Class</th>
                            <th class="text-center">Type</th>
                            <th class="text-center">Record</th>
                            <th class="text-center" style="width:130px;">Action</th>
                        </tr>
                    </thead>
                    <tbody ng-init="getAlldns('<?php echo $thisarray['p2'];?>')">
                        <tr ng-hide="contentLoaded">
                        <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
                        <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
                        <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
                        <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
                        <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
                        <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
                        <td class="text-center placeholder-glow"><small class="placeholder col-12"></small></td>
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
                                <div class="text-start d-grid gap-2 d-md-block">
                                    <button type="button" ng-click="readOnedns(d.id)"
                                        class="btn waves-effect btn-light btn-sm"><i class="mdi mdi-pencil mdi-18px"></i></button>
                                    <?php if($_SESSION['user_level']>=3){?><button type="button"
                                        ng-click="deletedns(d.id,'<?php echo $_SESSION['user'];?>',d.dnsname,'<?php echo $thisarray['p2'];?>')"
                                        class="btn waves-effect btn-light btn-sm"><i class="mdi mdi-close"></i></button><?php } ?>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <dir-pagination-controls pagination-id="prodx" boundary-links="true"
                    on-page-change="pageChangeHandler(newPageNumber)" template-url="/<?php echo $website['corebase'];?>assets/templ/pagination.tpl.html">
                </dir-pagination-controls>
                <div class="modal" id="modal-obj-form" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form name="form" ng-app>
                                <div class="modal-body container form-material"
                                    style="width:100%;min-height:300px;max-height:500px;overflow-x:hidden;overflow-y:scroll;">
                                    <div class=" row">
                                        <label class="form-control-label text-lg-right col-md-3">Tags</label>
                                        <div class="col-md-8"><input id="tags" data-role="tagsinput" type="text"
                                                class="form-control form-control-sm"></div>
                                        <div class="col-md-1" style="padding-left:0px;"><button type="button"
                                                class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="You can search this object with tags"><i
                                                    class="mdi mdi-information-variant mdi-18px"></i></button></div>
                                    </div>
                                    <?php if(isset($modulelist["budget"]) && !empty($modulelist["budget"])){ ?>
                                    <input ng-model="dns.proj" style="display:none;"
                                        value="<?php echo $thisarray['p2'];?>">
                                    <?php } ?>
                                    <div class=" row">
                                        <label class="form-control-label text-lg-right col-md-3">Server</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control form-control-sm autocomplsrv"
                                                placeholder="write the server name" />
                                            <input type="text" id="server" style="display:none;" />
                                            <input type="text" id="serverid" style="display:none;" />
                                            <input type="text" id="serverip" style="display:none;" />
                                        </div>
                                    </div>
                                    <div class=" row">
                                        <label class="form-control-label text-lg-right col-md-3">DNS name</label>
                                        <div class="col-md-9"> <input ng-model="dns.dnsname" ng-required="true"
                                                type="text" class="form-control form-control-sm"></div>
                                    </div>
                                    <div class=" row">
                                        <label class="form-control-label text-lg-right col-md-3">TTL</label>
                                        <div class="col-md-9"> <input ng-model="dns.ttl" ng-required="true" type="text"
                                                class="form-control form-control-sm"></div>
                                    </div>
                                    <div class=" row">
                                        <label class="form-control-label text-lg-right col-md-3">Class</label>
                                        <div class="col-md-9">
                                            <select name="dnsclass" ng-model="dns.dnsclass" ng-required="true"
                                                class="form-control form-control-sm">
                                                <option value="">Please select</option>
                                                <option value="IN">Internet</option>
                                                <option value="CH">Chaos</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class=" row">
                                        <label class="form-control-label text-lg-right col-md-3">DNS Type</label>
                                        <div class="col-md-9">
                                            <select name="dnsclass" ng-model="dns.dnstype" ng-required="true"
                                                class="form-control form-control-sm">
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
                                    <div class=" row">
                                        <label class="form-control-label text-lg-right col-md-3">Record</label>
                                        <div class="col-md-9"> <textarea ng-model="dns.dnsrecord" ng-required="true"
                                                class="form-control form-control-sm"></textarea></div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="mdi mdi-close"></i>&nbsp;Close</button>
                               
                                    <?php if($_SESSION['user_level']>=3){?>
                                    <button type="button" id="btn-create-obj"
                                        class="waves-effect waves-light btn btn-info btn-sm"
                                        ng-click="form.$valid && createdns('<?php echo $thisarray['p2'];?>','<?php echo $_SESSION['user'];?>')"><i class="mdi mdi-check"></i>&nbsp;Create</button>
                                    <button type="button" id="btn-update-obj"
                                        class="waves-effect waves-light btn btn-info btn-sm"
                                        ng-click="updatedns('<?php echo $thisarray['p2'];?>','<?php echo $_SESSION['user'];?>')"><i class="mdi mdi-content-save-outline"></i>&nbsp;Save Changes</button>
                                    <?php } ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <?php } ?>