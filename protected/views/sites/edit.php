<?php
$beneficiary = null;
$bank_name = null;
$bank_address = null;
$bank_account = null;
$swift_code = null;
$pee = null;
$status = null;
$edit = 'readonly';
?>
<link href="<?php echo Yii::app()->params['cssPath']?>css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo Yii::app()->params['cssPath']?>css/animate.min.css" rel="stylesheet">
<link href="<?php echo Yii::app()->params['cssPath']?>css/style.min862f.css?v=4.1.0" rel="stylesheet">
<script>
    $(document).ready(function(){$(".dataTables-example").dataTable();var oTable=$("#editable").dataTable();oTable.$("td").editable("http://www.zi-han.net/theme/example_ajax.php",{"callback":function(sValue,y){var aPos=oTable.fnGetPosition(this);oTable.fnUpdate(sValue,aPos[0],aPos[1])},"submitdata":function(value,settings){return{"row_id":this.parentNode.getAttribute("id"),"column":oTable.fnGetPosition(this)[2]}},"width":"90%","height":"100%"})});function fnClickAddRow(){$("#editable").dataTable().fnAddData(["Custom row","New row","New row","New row","New row"])};
</script>

<div class="row">
    <div class="col-sm-12">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Site Edit</h5>
            <div class="ibox-tools">
                <a class="collapse-link">
                    <i class="fa fa-chevron-up"></i>
                </a>
                <a class="dropdown-toggle" data-toggle="dropdown" href="form_basic.html#">
                    <i class="fa fa-wrench"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li><a href="form_basic.html#">选项1</a>
                    </li>
                    <li><a href="form_basic.html#">选项2</a>
                    </li>
                </ul>
                <a class="close-link">
                    <i class="fa fa-times"></i>
                </a>
            </div>
        </div>
        <div class="ibox-content">
            <form method="post" action="<?php echo $this->createUrl('affiliates/update',array('id'=>$site['id']));?>" class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Company</label>
                    <div class="col-sm-6">
                        <input type="text" name="title" value="<?php echo $site['company']?>" class="form-control">
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Address 1:</label>
                    <div class="col-sm-6">
                        <input type="text" name="company" value="<?php echo $site['address']?>" class="form-control">
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">E-mail Address:</label>
                    <div class="col-sm-6">
                        <input type="text" name="email" value="<?php echo  $site['email']?>" class="form-control">
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Country:</label>
                    <div class="col-sm-6">
                        <input type="text" name="country" value="<?php echo  $site['country']?>" class="form-control">
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Phone:</label>
                    <div class="col-sm-6">
                        <input type="text" name="phone" value="<?php echo  $site['phone']?>" class="form-control">
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Account Manager:</label>
                    <div class="col-sm-6">
                        <select class="form-control m-b" name="account_manager_id">
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
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Account Status::</label>
                    <div class="col-sm-6">
                        <select class="form-control m-b" name="status">
                            <option value="1">Active</option>
                            <option value="0">Pending</option>
                        </select>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <button class="btn btn-primary" onclick="editAff()" type="button">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>

    <div class="col-sm-12">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Site Edit</h5>
            <div class="ibox-tools">
                <a class="collapse-link">
                    <i class="fa fa-chevron-up"></i>
                </a>
                <a class="dropdown-toggle" data-toggle="dropdown" href="form_basic.html#">
                    <i class="fa fa-wrench"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li><a href="form_basic.html#">选项1</a>
                    </li>
                    <li><a href="form_basic.html#">选项2</a>
                    </li>
                </ul>
                <a class="close-link">
                    <i class="fa fa-times"></i>
                </a>
            </div>
        </div>
        <div class="ibox-content">
            <form method="post" action="<?php echo $this->createUrl('affiliates/payment',array('site_id'=>$site['id'],'action_type'=>'add','payment_type'=>0));?>" class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Beneficiary：</label>
                    <div class="col-sm-6">
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
                        <input type="text" id="beneficiary" <?php echo $edit?> name="beneficiary" class="form-control" value="<?php echo $beneficiary;?>">
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Bank Name:</label>
                    <div class="col-sm-6">
                        <input type="text" id="bank_name" <?php echo $edit;?> name="bankname" class="form-control" value="<?php echo $bank_name;?>">
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Bank Address:</label>
                    <div class="col-sm-6">
                        <input type="text" id="bank_address" <?php echo $edit;?> name="bankadd" class="form-control" value="<?php echo $bank_address?>">
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Bank Account:</label>
                    <div class="col-sm-6">
                        <input type="text" id="bank_account" <?php echo $edit;?> name="bankacc" class="form-control" value="<?php echo $bank_account;?>">
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Swift Code:</label>
                    <div class="col-sm-6">
                        <input type="text" id="swift_code" <?php echo $edit;?> name="swift_code" class="form-control" value="<?php echo $swift_code;?>">
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Status:</label>
                    <div class="col-sm-6">
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
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <button class="btn btn-primary" onclick="editPayment()" type="button">Save</button>
                    </div>
                    <div class="col-sm-6">
                        <?php if(in_array($this->user['groupid'],array(ADMIN_GROUP_ID,BUSINESS_GROUP_ID))){
                            if($status === 0){
                                echo "<button type=\"button\" onclick='authentication(0)' class=\"am-btn-m am-btn-primary\">Authentication</button>";
                            }
                        }elseif(in_array($this->user['groupid'],array(ADMIN_GROUP_ID,FINANCE_GROUP_ID))){
                            echo "<button type=\"button\" onclick='authentication(1)' class=\"am-btn-m am-btn-primary\">Authentication</button>";
                        }?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Site Edit</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="form_basic.html#">
                        <i class="fa fa-wrench"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="form_basic.html#">选项1</a>
                        </li>
                        <li><a href="form_basic.html#">选项2</a>
                        </li>
                    </ul>
                    <a class="close-link">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <form method="post" action="<?php echo $this->createUrl('affiliates/createaff');?>" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Title：</label>
                        <div class="col-sm-6">
                            <input type="text"  name="title" id="title" class="form-control" value="">
                            <input type="hidden" name="siteid" value="<?php echo $site['id'];?>">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                            <button class="btn btn-primary" onclick="addAff()" type="button">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Site Edit</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="form_basic.html#">
                        <i class="fa fa-wrench"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="form_basic.html#">选项1</a>
                        </li>
                        <li><a href="form_basic.html#">选项2</a>
                        </li>
                    </ul>
                    <a class="close-link">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <form method="post" action="<?php echo $this->createUrl('site/addrel',array('id'=>$site['id']));?>" class="form-horizontal">
                    <div class="form-group">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
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
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <select name="affiliates[]" class="form-control m-b" >
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
                </form>
            </div>
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
