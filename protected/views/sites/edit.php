<?php require_once dirname(dirname(__FILE__)) . '/sidebar.php';
$beneficiary = null;
$bank_name = null;
$bank_address = null;
$bank_account = null;
$swift_code = null;
$pee = null;
$status = null;
$edit = 'readonly';
?>
<style>
    div{
        padding-top: 10px;
    }
    .am-form label{
        color: red;
    }
</style>
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Site Edit</strong> </div>
    </div>
    <div class="am-g am-g-fixed">
        <form id="doForm" action="<?php echo $this->createUrl('affiliates/update',array('id'=>$site['id']));?>" method="post">
            <div class="am-u-md-12">
                <div class="am-panel am-panel-default">
                    <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-4'}">Details<span class="am-icon-chevron-down am-fr" ></span></div>
                    <div id="collapse-panel-4" class="am-panel-bd am-collapse am-in">
                        <div class="am-g">
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-2 am-u-md-2 am-text-right">Company:</div>
                                <label>*</label>
                                <div  class="am-u-sm-6 am-u-end">
                                    <input type="text" id="AdvertiserCompany" name="company" class="am-form-field" value="<?php echo $site['company']?>">
                                </div>
                            </div>
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-2 am-u-md-2 am-text-right"> Address 1:</div>
                                <div  class="am-u-sm-6 am-u-end">
                                    <input type="text" id="AdvertiserAddress1" name="address1" class="am-form-field" value="<?php echo $site['address']?>">
                                </div>
                            </div>

                            <div class="am-u-sm-12">
                                <div class="am-u-sm-2 am-u-md-2 am-text-right">E-mail Address:</div>
                                <label>*</label>
                                <div  class="am-u-sm-6 am-u-end">
                                    <input type="text" id="AdvertiserUserEmail" name="email"   class="am-form-field" value="<?php echo $site['email']?>">
                                </div>
                            </div>

                            <div class="am-u-sm-12">
                                <div class="am-u-sm-2 am-u-md-2 am-text-right">Country:</div>
                                <div  class="am-u-sm-6 am-u-end">
                                    <input type="text" id="AdvertiserCountry" name="country" class="am-form-field" value="<?php echo $site['country']?>">
                                </div>
                            </div>


                            <div class="am-u-sm-12">
                                <div class="am-u-sm-2 am-u-md-2 am-text-right">Phone:</div>
                                <div  class="am-u-sm-6 am-u-end">
                                    <input type="text" id="AdvertiserPhone" name="phone" class="am-form-field" value="<?php echo $site['phone']?>">
                                </div>
                            </div>

                            <?php if(in_array($this->user['groupid'],$this->manager_group)){ ?>
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-2 am-u-md-2 am-text-right">Account Manager:</div>
                                <div  class="am-u-sm-6 am-u-end">
                                    <select data-am-selected id="AdvertiserAccountManagerId" name="account_manager_id" >
                                        <option value=""></option>
                                        <?php if($business){ ?>
                                            <?php foreach($business as $val=>$key){
                                                $selected = '';
                                                if($key['id'] == $site['manager_userid']){
                                                    $selected = 'selected';
                                                }
                                                echo "<option $selected value='{$key['id']}'>{$key['id']}. '    ' . {$key['company']}</option>";
                                                ?>
                                            <?php }?>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-2 am-u-md-2 am-text-right">Account Status:</div>
                                <div  class="am-u-sm-6 am-u-end">
                                    <select data-am-selected id="AdvertiserStatus" name="status" >
                                        <option value="1">Active</option>
                                        <option value="0">Pending</option>
                                    </select>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">
                                    <button type="button" onclick="editAff()" class="am-btn-m am-btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <form id="payment_form" action="<?php echo $this->createUrl('affiliates/payment',array('site_id'=>$site['id'],'action_type'=>'add','payment_type'=>0));?>" method="post">
            <div class="am-u-md-12">
                <div class="am-panel am-panel-default">
                    <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-5'}">Payments<span class="am-icon-chevron-down am-fr" ></span></div>
                    <div id="collapse-panel-5" class="am-panel-bd am-collapse am-in">
                        <div class="am-g">
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-2 am-u-md-2 am-text-right">Beneficiary:</div>
                                <div  class="am-u-sm-6 am-u-end">
                                    <?php
                                    if(!empty($payment)) {
                                        $beneficiary = empty($payment['beneficiary']) ? '' : $payment['beneficiary'];
                                        $bank_name = empty($payment['bank_name']) ? '' : $payment['bank_name'];
                                        $bank_address = empty($payment['bank_address']) ? '' : $payment['bank_address'];
                                        $bank_account = empty($payment['bank_account']) ? '' : $payment['bank_account'];
                                        $swift_code = empty($payment['swift_code']) ? '' : $payment['swift_code'];
                                        $pee = empty($payment['pee']) ? '' : $payment['pee'];
                                        $status = empty($payment['status']) ? 0 : $payment['status'];
                                    }
                                    if(in_array($this->user['groupid'],$this->manager_group)){
                                        if(empty($status)){
                                            $edit = '';
                                        }
                                    }else{
                                        if(empty($status)){
                                            $edit = '';
                                        }
                                    }
                                    ?>
                                    <input type="text" id="beneficiary" <?php echo $edit?> name="beneficiary" class="am-form-field" value="<?php echo $beneficiary;?>">
                                </div>
                            </div>
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-2 am-u-md-2 am-text-right">Bank Name:</div>
                                <div  class="am-u-sm-6 am-u-end">
                                    <input type="text" id="bank_name" <?php echo $edit;?> name="bankname" class="am-form-field" value="<?php echo $bank_name;?>">
                                </div>
                            </div>
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-2 am-u-md-2 am-text-right">Bank Address:</div>
                                <div  class="am-u-sm-6 am-u-end">
                                    <input type="text" id="bank_address" <?php echo $edit;?> name="bankadd" class="am-form-field" value="<?php echo $bank_address?>">
                                </div>
                            </div>
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-2 am-u-md-2 am-text-right">Bank Account:</div>
                                <div  class="am-u-sm-6 am-u-end">
                                    <input type="text" id="bank_account" <?php echo $edit;?> name="bankacc" class="am-form-field" value="<?php echo $bank_account;?>">
                                </div>
                            </div>
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-2 am-u-md-2 am-text-right">Swift Code:</div>
                                <div  class="am-u-sm-6 am-u-end">
                                    <input type="text" id="swift_code" <?php echo $edit;?> name="swift_code" class="am-form-field" value="<?php echo $swift_code;?>">
                                </div>
                            </div>
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-2 am-u-md-2 am-text-right">Status:</div>
                                <div  class="am-u-sm-6 am-u-end">
                                    <?php if(in_array($this->user['groupid'],$this->manager_group)){
                                        if(1 == $status){
                                            echo "<a class='am-badge am-badge-warning'>Not Checked</a>";
                                        }elseif(2 == $status){
                                            echo "<a class='am-badge am-badge-success'>Checked</a>";
                                        }
                                    }else{
                                        if(1 == $status){
                                            echo "<a class='am-badge am-badge-warning'>Not Checked</a>";
                                        }elseif(2 == $status){
                                            echo "<a class='am-badge am-badge-success'>Checked</a>";
                                        }
                                    }?>
                                </div>
                            </div>
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">
                                    <button type="button" onclick="editPayment()" class="am-btn-m am-btn-primary">Save</button>
                                    <?php if(in_array($this->user['groupid'],array(ADMIN_GROUP_ID,BUSINESS_GROUP_ID))){
                                        if($status === 0){
                                            echo "<button type=\"button\" onclick='authentication(0)' class=\"am-btn-m am-btn-primary\">Authentication</button>";
                                        }
                                    }elseif(in_array($this->user['groupid'],array(ADMIN_GROUP_ID,FINANCE_GROUP_ID))){
                                        echo "<button type=\"button\" onclick='authentication(1)' class=\"am-btn-m am-btn-primary\">Authentication</button>";
                                    }?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <?php if(in_array($this->user['groupid'],$this->manager_group)){ ?>
        <form id="addAff" action="<?php echo $this->createUrl('affiliates/createaff');?>" method="post">
            <div class="am-u-md-12">
                <div class="am-panel am-panel-default">
                    <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-2'}">Add Affiliates<span class="am-icon-chevron-down am-fr" ></span></div>
                    <div id="collapse-panel-2" class="am-panel-bd am-collapse am-in">
                        <div id="collapse-panel-2" class="am-panel-bd am-collapse am-in">
                            <div class="am-g">
                                <div class="am-u-sm-12">
                                    <div class="am-u-sm-5 am-u-md-5 am-text-right">Title:</div>
                                    <div  class="element am-u-sm-3 am-u-end">
                                        <input type="text"  name="title" id="title" class="am-form-field" value="">
                                        <input type="hidden" name="siteid" value="<?php echo $site['id'];?>">
                                    </div>
                                </div>

                                <div class="am-u-sm-12">
                                    <div class="am-u-sm-5 am-u-md-5 am-text-right">
                                        <button type="button" onclick="addAff()" class="am-btn-m am-btn-primary">Create</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </form>

        <form id="addRel" action="<?php echo $this->createUrl('site/addrel',array('id'=>$site['id']));?>" method="post">
        <div class="am-u-md-12">
                <div class="am-panel am-panel-default">
                    <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-3'}">Rel<span class="am-icon-chevron-down am-fr" ></span></div>
                    <div id="collapse-panel-3" class="am-panel-bd am-collapse am-in">
                        <div class="am-g">
                            <table class="am-table am-table-striped am-table-hover">
                                <thead>
                                <tr>
                                    <td>Affiliate Id</td>
                                    <td>Title</td>
                                    <td>Operation</td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if(!empty($affids)){
                                    $aff_arr = explode(',',$affids);
                                    foreach($aff_arr as $aff){
                                        $user = JoySystemUser::model()->findByPk($aff);
                                        echo "<tr>";
                                        echo "<td>$aff</td>";
                                        if(!empty($user)) {
                                            echo "<td>{$user['title']}</td>";
                                        }
                                        echo "<td><button type='button' class='am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only' onclick='dl_rel({$site['id']},$aff)'>Delete</td>";
                                        echo "</tr>";
                                    }
                                }?>
                                </tbody>
                            </table>
                             <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">Add Relevance :</div>
                                <div  class="am-u-sm-3 am-u-end">
                                    <select id="affiliates" name="affiliates[]" multiple data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary',maxHeight: 200,searchBox: 1}"  style="display: none;padding-top: 20px">
                                        <?php if(!empty($affiliates)){
                                foreach($affiliates as $affiliate){
                                    if(!empty($affiliate)){
                                        $checked = '';
                                        if(isset($affiliate['checked'])) $checked = 'selected';
                                        echo "<option $checked value='{$affiliate['id']}'>{$affiliate['id']}</option>";
                                    }
                                }
                            }?>
                            </select>
                        </div>
                    </div>
                    <div class="am-u-sm-12">
                        <div class="am-u-sm-5 am-u-md-5 am-text-right">
                            <button type="button" onclick="addRel()" class="am-btn-m am-btn-primary">Save</button>
                        </div>
                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <?php }?>

        <form id="change_pd" action="<?php echo $this->createUrl('affiliates/update',array('id'=>$site['id']));?>" method="post">
            <div class="am-u-md-12">
                <div class="am-panel am-panel-default">
                    <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-1'}">Setting<span class="am-icon-chevron-down am-fr" ></span></div>
                    <div id="collapse-panel-1" class="am-panel-bd am-collapse am-in">
                        <div class="am-g">
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">Password:</div>
                                <label>*</label>
                                <div  class="am-u-sm-3 am-u-end">
                                    <input type="text" id="ad_password" name="password"  class="am-form-field" value="">
                                </div>
                            </div>
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">
                                    <button type="button" onclick="editPassword()" class="am-btn-m am-btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="am-modal am-modal-alert" tabindex="-1" id="my-alert">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">OFFER</div>
        <div class="am-modal-bd" id="alertContent">

        </div>
        <div class="am-modal-footer">
            <span class="am-modal-btn">OK</span>
        </div>
    </div>
</div>

<script>
    var addAff = function () {
        if(confirm('Are you sure to add the affiliate?')){
            $('#addAff').submit();
        }
    };
    var editPayment = function () {
        if(confirm('Are you sure to save the affiliate information?')){
            $('#payment_form').submit();
        }
    };
    
    var authentication = function () {
      if(confirm('Are you sure to authentication?')){
      }
    };
    
    var editPassword = function () {
        if(confirm('Are you sure to reset the password?')){
            $("#change_pd").submit();
        }
    };
    var addRel = function () {
        if('Are you sure to do this111?'){
            $("#addRel").submit();
        }
    };
    var dl_rel=function(site_id,id){
        if(confirm('Are you sure to do this?')){
            window.location.href = "<?php echo $this->createUrl('site/dl');?>&id="+id + "&site_id="+site_id;
        }
    };

    var chkEmail = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
    var editAff = function(){
        $("#doForm").submit();
    }
</script>
