<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/26
 * Time: 17:52
 */
?>
<link rel="stylesheet" href="assets/css/amazeui.datatables.css"/>
<script src="assets/js/amazeui.datatables.min.js"></script>
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Payment Detail</strong></div>
        <hr>
    </div>
    <div class="am-g">
        <div class="am-u-sm-12">
            <form class="am-form">
                <table class="am-table am-table-striped am-table-hover table-main" id="tab">
                    <thead>
                        <td>Affiliate ID</td>
                        <td>Month</td>
                        <td>Amount</td>
                        <td>Extra</td>
                        <td>Status</td>
                        <td>Operate</td>
                    </thead>
                    <tbody>
                    <?php if(!empty($data)){
                        foreach($data as $item){
                            if(1 == $item['status']){
                                $paid_column = "<a class='am-badge am-badge-success'>Checked</a>";
                            }elseif(2 == $item['status']){
                                $paid_column = "<a class='am-badge am-badge-success'>Paid</a>";
                            }else{
                                $paid_column = "<a class='am-badge am-badge-warning'>Not Checked</a>";
                            }
                            echo "<tr id='colum_{$item['id']}'>";
                            echo "<td>{$item['affid']}</td>";
                            $month = date('M',strtotime($item['count_date']));
                            echo "<td>$month</td>";
                            echo "<td>{$item['amount']}</td>";
                            $extra = empty($item['extra']) ? 0 : $item['extra'];
                            echo "<td>$extra</td>";
                            echo "<td>$paid_column</td>";
                            if($item['status'] == 0){
                                echo "<td><a href='javascript:extra({$item['id']})'>Extra</a></td>";
                            }else{
                                echo "<td></td>";
                            }
                        }
                    }?>
                        <tr></tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>
<div class="am-modal am-modal-alert" tabindex="-1" id="my-alert">
    <div class="am-modal-dialog">
        <form id="extra_form" action="<?php echo $this->createUrl('payment/extra',array('type'=>'add'));?>" method="post">
        <div class="am-modal-hd">Content</div>
        <div class="am-modal-bd" id="alertContent">
                <input hidden name="invoice_id" id="invoice_id">
                <div class="am-u-sm-12 am-u-12">
                    <div class="am-form-group">
                        <label for="extra1" style="text-align: left" class="am-u-sm-3 am-form-label">Extra 1:</label>
                        <div class="am-u-sm-6 am-u-end">
                            <input type="text" class="am-form-field" name="extra" value="">
                        </div>
                    </div>
                </div>
                <br>
                <br>
                <br>
                <br>
                <div class="am-u-sm-12 am-u-12">
                    <div class="am-form-group">
                        <label for="remark1" style="text-align: left" class="am-u-sm-3 am-form-label">Remark 1:</label>
                        <div class="am-u-sm-6 am-u-end">
                            <input type="text" class="am-form-field" name="remark" value="">
                        </div>
                    </div>
                </div>
        </div>
        <div class="am-modal-footer">
	            <button type="button" onclick="submit_extra()" id="extra_button" class="am-modal-btn">OK</button>

        </div>
        </form>
    </div>
</div>
<script>
    $(function() {
        $('#tab').DataTable();
    });
    var extra = function (id) {
        $('#invoice_id').val(id);
        $("#my-alert").modal();
    };
	    var submit_extra = function(){
        $('#extra_form').submit();
    };
</script>
