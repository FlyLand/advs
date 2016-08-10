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
                    <td>Original Affiliate Id</td>
                    <td>Original Offer Id</td>
                    <td>Offer Id</td>
                    <td>Count</td>
                    </thead>
                    <tbody>
                    <?php if(!empty($data)){
                        foreach($data as $item){
                            echo "<tr>";
                            echo "<td>{$item['affid']}</td>";
                            echo "<td>{$item['original_affid']}</td>";
                            echo "<td>{$item['original_offerid']}</td>";
                            echo "<td>{$item['offerid']}</td>";
                            echo "<td>{$item['count']}</td>";
                            echo "</tr>";
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
        <div class="am-modal-hd">Content</div>
        <div class="am-modal-bd" id="alertContent">
            <form id="extra_form" action="<?php echo $this->createUrl('payment/extra',array('type'=>'add'));?>" method="post">
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
                <div class="am-u-sm-12">
                    <div class="am-u-sm-5 am-u-md-5 am-text-right">
                        <button type="submit" id="extra_button"  class="am-btn-xs am-btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="am-modal-footer">
            <span class="am-modal-btn">OK</span>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('#tab').DataTable();
    });
    var extra = function (id) {
        $('#invoice_id').val(id);
        $("#my-alert").modal();
    }
</script>
