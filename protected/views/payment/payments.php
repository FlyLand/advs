<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/26
 * Time: 17:52
 */
include_once dirname(dirname(__FILE__)) . '/sidebar.php';
?>
<link rel="stylesheet" href="assets/css/amazeui.datatables.css"/>
<script src="assets/js/amazeui.datatables.min.js"></script>
<script type="text/javascript" src="./assets/js/pdfobject.js"></script>
<!-- content start -->
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf">
            <strong class="am-text-primary am-text-lg">Payments</a></strong>
        </div>
    </div>
    <div class="am-u-3">
        <p style="font-weight: 700">Unpaid Balance:   <span style="font-weight: 300"><?php echo empty($invoice['count']['not_paid']) ? 0 : $invoice['count']['not_paid'];?>$</span></p>
        <p style="font-weight: 700">Minimum Payout:   <span style="font-weight: 300"><?php echo  MIN_PAYOUT;?>$</span></p>
    </div>
    <table class="am-table am-table-striped am-table-bordered am-table-compact" id="payments">
        <thead>
            <tr>
                <?php
                if(in_array($this->user['groupid'],$this->manager_group)){
                    echo '
                    <td>Affiliate ID</td>
                   <td>Invoice Date</td>
                    <td>Amount</td>
                    <td>Amount sent</td>
                    <td>Invoice PDF</td>
                    <td>Signed PDF</td>
                    <td>AM</td>
                    <td>Finance</td>
                    <td>Status</td>
                    <td>Pay Date</td>
                    ';
                if(in_array($this->user['groupid'],array(AM_GROUP_ID,ADMIN_GROUP_ID,FINANCE_GROUP_ID))){
                    echo '<td>Operation</td>';
                }
                }else{
                    echo '<td>Invoice Date</td>';
                    echo '<td>Amount sent</td>';
                    echo '<td>Invoice PDF</td>';
                    echo '<td>Signed PDF</td>';
                    echo '<td>AM</td>';
                    echo '<td>Status</td>';
                }
                ?>
            </tr>
        </thead>
        <tbody>
        <?php if(!empty($invoice['data'])){
                foreach($invoice['data'] as $item){
                    echo "<tr>";
                    $pdf = '';
                    $pdf_name = '"'.$item['pdf'].'"';
                    $postback_pdf_name = '"'.$item['postback_pdf'].'"';
                    if(in_array($this->user['groupid'],array(ADMIN_GROUP_ID,AM_GROUP_ID))) {
                        $pdf = empty($item['pdf']) ? "<input class='am-btn-primary' onclick='javascript:setUpload({$item['id']},0)' type='button' value='Upload'>" : "<a  class='am-badge am-badge-warning' onclick='javascript:openPDF($pdf_name)'>View</a>";
                    }else{
                        $pdf = empty($item['pdf']) ? "" : "<a  class='am-badge am-badge-warning' onclick='javascript:openPDF($pdf_name)'>View</a>";
                    }
                    if(in_array($this->user['groupid'],array(AFF_GROUP_ID,ADMIN_GROUP_ID))){
                        $postback_pdf = empty($item['postback_pdf']) ? "<input class='am-btn-primary' onclick='javascript:setUpload({$item['id']},1)' type='button' value='Upload'>" : "<a  class='am-badge am-badge-warning' onclick='javascript:openPDF($postback_pdf_name)'>View</a>";
                    }else{
                        $postback_pdf = empty($item['postback_pdf']) ? "" : "<a  class='am-badge am-badge-warning' onclick='javascript:openPDF($postback_pdf_name)'>View</a>";
                    }
                    if(0 == $item['status']){
                       $paid_column = "<td><a class='am-badge am-badge-warning'>Not Paid</a></td>";
                    }elseif(1 == $item['status']){
                        $paid_column = "<td><a class='am-badge am-badge-success'>Checked</a></td>";
                    }elseif(2 == $item['status']){
                        $paid_column = "<td><a class='am-badge am-badge-success'>Payed</a></td>";
                    }
                    if(in_array($this->user['groupid'],$this->manager_group)){
                        echo "<td>{$item['site_id']}</td>";
                        $month = date('M',strtotime($item['count_date']));
                        echo "<td>$month</td>";
                        echo "<td>{$item['amount']}</td>";
                        echo "<td>{$item['amount_paid']}</td>";
                        echo "<td>$pdf</td>";
                        echo "<td>$postback_pdf</td>";
                        echo "<td>{$item['am']}</td>";
                        echo "<td>{$item['finace']}</td>";
                        echo $paid_column;
                        echo '<td>';
                        $check_hide = '';
                        $pay_hide = '';
                        if ($item['status'] == 1 || $item['status'] == 2) {
                            $check_hide = "style='display:none'";
                        }
                        if ($item['status'] == 2 || $item['status'] == 0) {
                            $pay_hide = "style='display:none'";
                        }
                        if($this->user['groupid'] == AM_GROUP_ID || ADMIN_GROUP_ID == $this->user['groupid']){
                            echo "<div $check_hide id='check_div_{$item['id']}'><button  id='checkButton_{$item['id']}' onclick='Check({$item['id']},0)' class='am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only'><span class='am-icon-edit'></span> Check</button></div>";
                        }
                        if($item['amount_sent'] > MIN_PAYOUT && in_array($this->user['groupid'],array(FINANCE_GROUP_ID,ADMIN_GROUP_ID))){
                            echo "<div $pay_hide id='pay_div_{$item['id']}'><button  id='payButton_{$item['id']}' onclick='Check({$item['id']},1)' class='am-btn am-btn-default am-btn-xs am-text-default am-hide-sm-only'><span class='am-icon-edit'></span> Pay</button></div>";
                        }
                        echo '</td>';
                        echo "<td>{$item['pay_date']}</td>";
                    }else{
                        $month = date('M',strtotime($item['count_date']));
                        echo "<tr>";
                        echo "<td>{$item['id']}</td>";
                        echo "<td>{$month}</td>";
                        echo "<td>{$item['amount_paid']}</td>";
                        echo "<td>$pdf</td>";
                        echo "<td>$postback_pdf</td>";
                        echo "<td>{$item['am']}</td>";
                        echo $paid_column;
                    }
                }
            }else echo '';
            echo "</tr>";?>
        </tbody>
    </table>
</div>

<div class="am-btn-group am-btn-group-xs" style="padding-left: 2%">
    <button type="button" onclick="javascript:window.location.href='<?php echo $this->createUrl('payment/outputexcel');?>'" class="am-btn am-btn-warning">Download As Excel</button>
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

<div class="am-modal am-modal-alert" tabindex="-1" id="upload_model">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">Upload PDF</div>
        <div class="am-modal-bd" id="upload_content">
            <form method='post' id='pdf_form' enctype="multipart/form-data" action='<?php echo $this->createUrl('payment/uploadpdf');?>'><input id="upload_file"  name='upload_file' type='file'>
                <input type='hidden' id="hidden_id" name='id' value=''>
                <input type='hidden' id="hidden_type" name='type' value=''>
                <input type='submit' class='am-btn-primary' value='Upload'></form>
        </div>
        <div class="am-modal-footer">
            <span class="am-modal-btn">OK</span>
        </div>
    </div>
</div>

<div class="am-modal am-modal-alert" style="width: 700px;margin-top: 0;" tabindex="-1" id="preview_pdf">
    <div class="am-modal-dialog">
        <div class="am-modal-bd" id="preview_content">
        </div>
        <div class="am-modal-footer">
            <span class="am-modal-btn">OK</span>
        </div>
    </div>
</div>


<?php if(!empty($msg)){
    echo '<script>';
    echo "$('#alertContent').html('$msg');
                        $('#my-alert').modal();";
    echo '</script>';
}?>
<!-- content end -->
<script>
    var setUpload = function(id,type){
        $("#hidden_id").val(id);
        $("#hidden_type").val(type);
        $("#upload_model").modal();
    };
    $('#pdf_form').submit(function () {
        if(!$('#upload_file').val()){
            alert('please select a PDF file!');
            return false;
        }
    });
    $(function() {
        $('#payments').DataTable({
            bSort:true,
            bSortClasses:true,
            //跟数组下标一样，第一列从0开始，这里表格初始化时，第四列默认降序
            aaSorting: [[ 0, "desc" ]]
        });
    });
    var Check = function (id,type) {
        var str = '';
        if(0 == type)
             str = 'Are you sure check?';
        else if(1 == type)
             str = 'Are you sure pay for it?';
        if(str){
            $.ajax({
                type: 'POST',
                url: '<?php echo $this->createUrl('payment/check');?>' ,
                data: {id:id,type:type} ,
                async:false,
                success: function(data){
                    var  check_div = "#check_div_"+id;
                    var pay_div ="#pay_div_"+id;
                    if(1 == data.status){
                        if(0 == type){
                            $(check_div).remove();
                            if(data.payout > <?php echo MIN_PAYOUT?>){
                                $(pay_div).show();
                            }
                        }
                        if(1 == type){
                            $(pay_div).remove();

                        }
                        $("#alertContent").html('Success');
                        $("#my-alert").modal();
                    }else{
                        $("#alertContent").html('Failed');
                        $("#my-alert").modal();
                    }
                } ,
                dataType: 'json'
            })
        }
    };

    var openPDF = function (name) {
        window.open("<?php echo $this->createUrl('payment/viewPDF');?>&name=" + name);
    }
    </script>
