<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/2
 * Time: 15:15
 */
include_once dirname(dirname(__FILE__)).'/sidebar.php';
?>
<link rel="stylesheet" href="assets/css/amazeui.datatables.css"/>
<script src="assets/js/amazeui.datatables.min.js"></script>
<script type="text/javascript" src="./assets/js/pdfobject.js"></script>
<!-- content start -->
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf">
            <strong class="am-text-primary am-text-lg">Payments</strong>
        </div>
    </div>
    <table class="am-table am-table-striped am-table-bordered am-table-compact" id="aff_payment">
        <thead>
        <tr>
            <td>Company</td>
           <td>Site ID</td>
            <td>Beneficiary</td>
            <td>Bank Name</td>
            <td>Bank Address</td>
            <td>Bank Account</td>
            <td>Swift Code</td>
            <td>Operation</td>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($payment)){
            foreach($payment as $item){
                echo "<tr>";
                $userinfo = JoySystemUser::getResult('company'," id = {$item['affid']}");
                if(!empty($userinfo)){
                    echo "<td>{$userinfo[0]['company']}</td>";
                }else{
                    echo "<td></td>";
                }
                echo "<td>{$item['affid']}</td>";
                echo "<td>{$item['beneficiary']}</td>";
                echo "<td>{$item['bank_name']}</td>";
                echo "<td>{$item['bank_address']}</td>";
                echo "<td>{$item['bank_account']}</td>";
                echo "<td>{$item['swift_code']}</td>";
                $td_val = '<td></td>';
                $url_request = $this->createUrl('affiliates/payment',array('action_type'=>'verifier')) . '&id=' . $item['id'];
                if($this->user['groupid'] == BUSINESS_GROUP_ID){
                    if($item['status'] == 0){
                        $td_val = "<td><a href='$url_request' class='am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only'>Check</a></td>";
                    }elseif($item['status'] == 2){
                        $td_val = "<td><a class='am-badge am-badge-success'>Checked</a></td>";
                    }
                }elseif($this->user['groupid'] == FINANCE_GROUP_ID){
                    if($item['status'] == 1){
                        $td_val = "<td><a href='$url_request' class='am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only'>Check</a></td>";
                    }elseif($item['status'] == 2){
                        $td_val = "<td><a class='am-badge am-badge-success'>Checked</a></td>";
                    }
                }
                echo "$td_val";
                echo "</tr>";
            }}?>
        </tbody>
    </table>
    <div class="am-btn-group am-btn-group-xs" style="padding-left: 2%">
        <button type="button" onclick="javascript:window.location.href='<?php echo $this->createUrl('payment/downloadpayment');?>'" class="am-btn am-btn-warning">Download As Excel</button>
    </div>

</div>

<div class="am-modal am-modal-alert" tabindex="-1" id="my-alert">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">Content</div>
        <div class="am-modal-bd" id="alertContent">

        </div>
        <div class="am-modal-footer">
            <span class="am-modal-btn">OK</span>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('#aff_payment').DataTable();
    });
</script>