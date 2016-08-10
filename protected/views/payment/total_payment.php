<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/26
 * Time: 17:52
 */
include_once dirname(dirname(__FILE__)).'/sidebar.php';
?>
<script type="text/javascript" src="./assets/js/pdfobject.js"></script>
<link rel="stylesheet" href="assets/css/amazeui.datatables.css"/>
<script src="assets/js/amazeui.datatables.min.js"></script>
<div class="admin-content">
    <div class="am-tabs">
        <div class="am-tabs-bd">
            <form action="<?php echo $this->createUrl('payment/paymenttotal');?>" method="post">
                <div class="am-u-md-12">
                    <div class="am-panel am-panel-default">
                        <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-4'}">Filters<span class="am-icon-chevron-down am-fr" ></span></div>
                        <div id="collapse-panel-4" class="am-panel-bd am-collapse am-in">
                            <div class="am-g">
                                <div class="am-u-sm-3 am-u-md-3"> Invoice Month :</div>
                                <div class="am-u-sm-6 am-u-md-6" >
                                    <p><input type="text" name="count_date" id="count_date" class="am-form-field" value="<?php echo $count_date?>" data-am-datepicker="{format: 'yyyy-mm', viewMode: 'months', minViewMode: 'months'}" readonly ></p>
                                </div>
                            </div>
                            <div class="am-g">
                                <div class="am-u-sm-3 am-u-md-3"><button class="am-btn am-btn-warning am-btn-xs" type="submit">GO</button></div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="am-tabs">
        <div class="am-tabs-bd">
            <form action="<?php echo $this->createUrl('payment/sitepayoutinfo');?>" method="post">
                <div class="am-u-md-12">
                    <div class="am-panel am-panel-default">
                        <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-1'}">Invoice<span class="am-icon-chevron-down am-fr" ></span></div>
                        <div id="collapse-panel-1" class="am-panel-bd am-collapse am-in">
                            <div class="am-g">
                                <table class="am-table am-table-striped am-table-bordered am-table-compact" id="total_payment">
                                    <thead>
                                    <tr>
                                        <?php if(in_array($this->user['groupid'],$this->manager_group)){
                                       if($this->user['groupid'] == FINANCE_GROUP_ID){
                                           echo '<td>Payee</td>';
                                           echo '<td>Amount</td>';
                                           echo '<td>Amount Paid</td>';
                                           echo '<td>Invoice PDF</td>';
                                           echo '<td>Sign PDF</td>';
                                           echo '<td>Status</td>';
                                       }else{
                                           echo '
                                        <td>Site Id</td>
                                        <td>Company</td>
                                        <td>Amount</td>
                                        <td>Extra</td>
                                        <td>Amount Paid</td>
                                        <td>Invoice PDF</td>
                                        <td>Sign PDF</td>
                                        <td>Status</td>
                                        <td>Operation</td>';
                                       }
                                    }else{
                                        echo "<td>Count Date</td>";
                                        echo "<td>Balance</td>";
                                        echo "<td>Invoice PDF</td>";
                                        echo "<td>Sign PDF</td>";
                                        echo "<td>Status</td>";
                                    }
                                    ?>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(!empty($total_payment)){
                                        if(in_array($this->user['groupid'],$this->manager_group)) {
                                            if($this->user['groupid'] == FINANCE_GROUP_ID){
                                                foreach ($total_payment as $item) {
                                                    $extra = empty($item['extra']) ? 0 : $item['extra'];
                                                    $amount = empty($item['amount']) ? 0 : $item['amount'];
                                                    $amount -=  FEE;
                                                    if ($amount <= 0) {
                                                        continue;
                                                    }
                                                    /* status
                                                     *   0 is checked but not send sign pdf
                                                     *   1 is send sign pdf but not check the pdf
                                                     *   2 is check the pdf but not paid
                                                     *   3 is paid
                                                    */
                                                    $pdf = '';
                                                    $pdf_name = '"' . $item['pdf'] . '"';
                                                    $postback_pdf_name = '"' . $item['pdf_back'] . '"';
                                                    $amount_paid = '';
                                                    if (in_array($this->user['groupid'], array(ADMIN_GROUP_ID, AM_GROUP_ID))) {
                                                        $pdf = empty($item['pdf']) ? "<input class='am-btn-primary' onclick='javascript:setUpload({$item['id']},0)' type='button' value='Upload'>" : "<a  class='am-badge am-badge-warning' onclick='javascript:openPDF($pdf_name)'>View</a>";
                                                    } else {
                                                        $pdf = empty($item['pdf']) ? "" : "<a  class='am-badge am-badge-warning' onclick='javascript:openPDF($pdf_name)'>View</a>";
                                                    }
                                                    if (in_array($this->user['groupid'], array(AFF_GROUP_ID, ADMIN_GROUP_ID))) {
                                                        $postback_pdf = empty($item['pdf_back']) ? "<input class='am-btn-primary' onclick='javascript:setUpload({$item['id']},1)' type='button' value='Upload'>" : "<a  class='am-badge am-badge-warning' onclick='javascript:openPDF($postback_pdf_name)'>View</a>";
                                                    } else {
                                                        $postback_pdf = empty($item['pdf_back']) ? "" : "<a  class='am-badge am-badge-warning' onclick='javascript:openPDF($postback_pdf_name)'>View</a>";
                                                    }
                                                    if (1 == $item['status']) {
                                                        $paid_column = "<a class='am-badge am-badge-success'>Check Processing</a>";
                                                    } elseif (3 == $item['status']) {
                                                        $paid_column = "<a class='am-badge am-badge-success'>Paid</a>";
                                                        $amount_paid_sql = "select amount_paid from joy_count_record WHERE siteid = {$item['site_id']} AND count_date = {$item['count_date']}";
                                                        $command = Yii::app()->db->createCommand($amount_paid_sql);
                                                        $amount_paid_result = $command->queryRow($command);
                                                        if(!empty($amount_paid_result)){
                                                            $amount_paid = $amount_paid_result['amount_paid'];
                                                        }
                                                    } elseif (2 == $item['status']) {
                                                        $paid_column = "<a class='am-badge am-badge-success'>Not Paid</a>";
                                                    } else {
                                                        $paid_column = "<a class='am-badge am-badge-warning'>Not Send</a>";
                                                    }
                                                    echo '<tr>';
                                                    echo "<td>{$item['beneficiary']}</td>";
                                                    echo "<td>$amount</td>";
                                                    echo "<td>$amount_paid</td>";
                                                    echo "<td>$pdf</td>";
                                                    echo "<td>$postback_pdf</td>";
                                                    echo "<td id='paid_column_{$item['id']}'>$paid_column</td>";
                                                    echo '</tr>';
                                                }
                                            }else {
                                                foreach ($total_payment as $item) {
                                                    if ($item['amount'] == 0) {
                                                        continue;
                                                    }
                                                    /* status
                                                     *   0 is checked but not send sign pdf
                                                     *   1 is send sign pdf but not check the pdf
                                                     *   2 is check the pdf but not paid
                                                     *   3 is paid
                                                    */
                                                    $pdf = '';
                                                    $pdf_name = '"' . $item['pdf'] . '"';
                                                    $postback_pdf_name = '"' . $item['pdf_back'] . '"';
                                                    if (in_array($this->user['groupid'], array(ADMIN_GROUP_ID, AM_GROUP_ID))) {
                                                        $pdf = empty($item['pdf']) ? "<input class='am-btn-primary' onclick='javascript:setUpload({$item['id']},0)' type='button' value='Upload'>" : "<a  class='am-badge am-badge-warning' onclick='javascript:openPDF($pdf_name)'>View</a>";
                                                    } else {
                                                        $pdf = empty($item['pdf']) ? "" : "<a  class='am-badge am-badge-warning' onclick='javascript:openPDF($pdf_name)'>View</a>";
                                                    }
                                                    if (in_array($this->user['groupid'], array(AFF_GROUP_ID, ADMIN_GROUP_ID))) {
                                                        $postback_pdf = empty($item['pdf_back']) ? "<input class='am-btn-primary' onclick='javascript:setUpload({$item['id']},1)' type='button' value='Upload'>" : "<a  class='am-badge am-badge-warning' onclick='javascript:openPDF($postback_pdf_name)'>View</a>";
                                                    } else {
                                                        $postback_pdf = empty($item['pdf_back']) ? "" : "<a  class='am-badge am-badge-warning' onclick='javascript:openPDF($postback_pdf_name)'>View</a>";
                                                    }
                                                    if (1 == $item['status']) {
                                                        $paid_column = "<a class='am-badge am-badge-success'>Check Processing</a>";
                                                    } elseif (3 == $item['status']) {
                                                        $paid_column = "<a class='am-badge am-badge-success'>Paid</a>";
                                                    } elseif (2 == $item['status']) {
                                                        $paid_column = "<a class='am-badge am-badge-success'>Not Paid</a>";
                                                    } else {
                                                        $paid_column = "<a class='am-badge am-badge-warning'>Not Send</a>";
                                                    }
                                                    $extra = empty($item['extra']) ? 0 : $item['extra'];
                                                    $amount = empty($item['amount']) ? 0 : $item['amount'];
                                                    $amount_paid = $amount;
                                                    $amount = round($amount - $extra,2);
                                                    echo "<tr id='column_{$item['id']}'>";
                                                    echo "<td>{$item['site_id']}</td>";
                                                    echo "<td>{$item['company']}</td>";
                                                    echo "<td>$amount</td>";
                                                    echo "<td>$extra</td>";
                                                    echo "<td>$amount_paid</td>";
                                                    echo "<td>$pdf</td>";
                                                    echo "<td>$postback_pdf</td>";
                                                    echo "<td id='paid_column_{$item['id']}'>$paid_column</td>";
                                                    echo "<td id='option_{$item['id']}'>";
                                                    if (in_array($this->user['groupid'], $this->manager_group)) {
                                                        if (($this->user['groupid'] == BUSINESS_GROUP_ID && 0 == $item['status']) || ($this->user['groupid'] == ADMIN_GROUP_ID && 0 == $item['status'])) {
                                                            echo "<a  onclick='sendInvoice({$item['id']},0)' class='am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only'><span class='am-icon-edit'></span> Send</a>";
                                                        }
                                                        if (in_array($this->user['groupid'], array(BUSINESS_GROUP_ID, ADMIN_GROUP_ID)) && 1 == $item['status'] && !empty($item['pdf_back'])) {
                                                            echo "<a  onclick='sendInvoice({$item['id']},2)' class='am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only'><span class='am-icon-edit'></span> Check</a>";
                                                        }
                                                    }
                                                    echo '</td>';
                                                    echo "</tr>";
                                                }
                                            }
                                        }else{
                                            foreach ($total_payment as $item) {
                                                if($item['status'] == 0){
                                                    continue;
                                                }
                                                $amount = empty($item['amount']) ? 0 : $item['amount'];
                                                $extra = empty($item['extra']) ? 0 : $item['extra'];
                                                if (1 == $item['status']) {
                                                    $paid_column = "<a class='am-badge am-badge-success'>Check Processing</a>";
                                                } elseif (3 == $item['status']) {
                                                    $paid_column = "<a class='am-badge am-badge-success'>Paid</a>";
                                                } elseif (2 == $item['status']) {
                                                    $paid_column = "<a class='am-badge am-badge-success'>Not Paid</a>";
                                                } else {
                                                    $paid_column = "<a class='am-badge am-badge-warning'>Not Send</a>";
                                                }
                                                $pdf = '';
                                                $pdf_name = '"' . $item['pdf'] . '"';
                                                $postback_pdf_name = '"' . $item['pdf_back'] . '"';
                                                $pdf = empty($item['pdf']) ? "" : "<a  class='am-badge am-badge-warning' onclick='javascript:openPDF($pdf_name)'>View</a>";
                                                if(1 == $item['status']){
                                                    $postback_upload = "<input class='am-btn-primary' onclick='javascript:setUpload({$item['id']},1)' type='button' value='Upload'>";
                                                }else{
                                                    $postback_upload = '';
                                                }
                                                $postback_view = empty($item['pdf_back']) ? '' : "<a  class='am-badge am-badge-warning' onclick='javascript:openPDF($postback_pdf_name)'>View</a>";
                                                echo "<tr>";
                                                echo "<td>{$item['count_date']}</td>";
                                                echo "<td>$amount</td>";
                                                echo "<td>$pdf</td>";
                                                echo "<td>$postback_view  $postback_upload</td>";
                                                echo "<td id='paid_column_{$item['id']}'>$paid_column</td>";
                                                echo "</tr>";
                                            }
                                        }
                                    }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <?php if(in_array($this->user['groupid'],array(ADMIN_GROUP_ID,FINANCE_GROUP_ID))){ ?>
                <div class="am-u-md-12">
                    <div class="am-u-sm-3 am-u-md-3">
                        <button class="am-btn am-btn-warning am-btn-xs" type="button" onclick="javascript:window.location.href='<?php echo $this->createUrl('payment/downloadmonthexcel')?>'">Download Month Excel</button>
                    </div>
                    <form id="upload_month_payment" name="upform" enctype="multipart/form-data" method="post" action="<?php echo $this->createUrl('payment/uploadmonthexcel');?>">
                        <div class="am-u-sm-3 am-u-md-3">
                            <input  class="am-btn am-btn-primary  am-btn-xs" type="file" name="upfile" id="fileField" />
                        </div>
                        <div class="am-u-sm-3 am-u-end">
                            <button class="am-btn am-btn-warning am-btn-xs" type="submit">Upload Month Excel</button>
                        </div>
                    </form>
                    </div>
                </div>
            <?php } ?>

            <div class="am-u-md-12">
                <div class="am-panel am-panel-default">
                    <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-2'}">Pay Record<span class="am-icon-chevron-down am-fr" ></span></div>
                    <div id="collapse-panel-2" class="am-panel-bd am-collapse am-in">
                        <div class="am-g">
                            <table class="am-table am-table-striped am-table-bordered am-table-compact" id="total_record">
                                <thead>
                                    <tr>
                                        <?php if($this->user['groupid'] == SITE_GROUP_ID){
                                            echo "
                                        <td>Company</td>
                                        <td>Amount</td>
                                        <td>Extra</td>
                                        <td>Transaction Id</td>
                                        <td>Pay Date</td>
                                        <td>Status</td>
                                            ";
                                        }else{
                                        echo "
                                        <td>Site Id</td>
                                        <td>Company</td>
                                        <td>Amount</td>
                                        <td>Extra</td>
                                        <td>Amount Paid</td>
                                        <td>Transaction Id</td>
                                        <td>Pay Date</td>
                                        <td>Bank Name</td>
                                        <td>Finance</td>
                                        ";
                                        }?>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if(!empty($total_record)){
                                    foreach($total_record as $record){
                                        $amount_paid = empty($record['amount_paid']) ? 0 : $record['amount_paid'];
                                        $extra = empty($record['extra']) ? 0 : $record['extra'];
                                        $extra_detail_url = $this->createUrl('payment/extradetail',array('site_id'=>$record['site_id'],'count_date'=>$count_date));
                                        $amount = empty($record['amount']) ? 0 : $record['amount'];
                                        $amount_pay = $amount - $extra;
                                        if($this->user['groupid'] == SITE_GROUP_ID){
                                            echo "<tr>";
                                            echo "<td>{$record['company']}</td>";
                                            echo "<td>$amount_pay</td>";
                                            if(empty($extra)){
                                                echo "<td>$extra</td>";
                                            }else{
                                                echo "<td><a href='$extra_detail_url'>$extra</a></td>";
                                            }
                                            echo "<td>{$record['swift_number']}</td>";
                                            echo "<td>{$record['pay_date']}</td>";
                                            echo "<td><a class='am-badge am-badge-success'>Paid</a></td>";
                                            echo "</tr>";
                                        }else{
                                            echo '<tr>';
                                            echo "<td>{$record['site_id']}</td>";
                                            echo "<td>{$record['company']}</td>";
                                            echo "<td>$amount_pay</td>";
                                            if(empty($extra)){
                                                echo "<td>$extra</td>";
                                            }else{
                                                echo "<td><a href='$extra_detail_url'>$extra</a></td>";
                                            }
                                            if($this->user['userid'] == SITE_GROUP_ID){
                                                echo "<td>$amount</td>";
                                            }else{
                                                echo "<td>$amount_paid</td>";
                                            }
                                            echo "<td>{$record['swift_number']}</td>";
                                            echo "<td>{$record['pay_date']}</td>";
                                            echo "<td>{$record['bank_name']}</td>";
                                            echo "<td>{$record['finance']}</td>";
                                            echo '</tr>';
                                        }
                                    }
                                }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <?php if($this->user['groupid'] == SITE_GROUP_ID){ ?>
                <div class="am-u-md-12">
                    <div class="am-panel am-panel-default">
                        <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-2'}">Earning<span class="am-icon-chevron-down am-fr" ></span></div>
                        <div id="collapse-panel-2" class="am-panel-bd am-collapse am-in">
                            <div class="am-g">
                            </div>
                        </div>
                    </div>
                </div>
            <?php }?>
        </div>
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

<div class="am-modal am-modal-alert" tabindex="-1" id="upload_model">
    <div class="am-modal-dialog">
        <form method='post' id='pdf_form' enctype="multipart/form-data" action='<?php echo $this->createUrl('payment/uploadpdf');?>'>
            <div class="am-modal-hd">Upload PDF</div>
            <div class="am-modal-bd" id="upload_content">
                    <input id="upload_file"  name='upload_file' type='file'>
                    <input type='hidden' id="hidden_id" name='id' value=''>
                    <input type='hidden' id="hidden_type" name='type' value=''>
                    <input type="hidden" id="count_date" name="count_date" value="<?php echo $count_date;?>">
            </div>
            <div class="am-modal-footer">
                <input type='submit' class='am-btn-primary' value='Upload'>
            </div>
        </form>
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
<script>
    $(function(){
        $('#total_payment').DataTable();
        $('#total_record').DataTable();
    });

    var setUpload = function(id){
        $("#hidden_id").val(id);
        $("#hidden_type").val(1);
        $("#upload_model").modal();
    };
    $('#pdf_form').submit(function () {
        if(!$('#upload_file').val()){
            return false;
        }
    });
    var openPDF = function (name) {
        window.open("<?php echo $this->createUrl('payment/viewPDF');?>&name=" + name);
    };

    var sendInvoice = function (id,type) {
        var str = '';
        var fee = '';
        if(0 == type)
            str = 'Are you sure send invoice?';
        else if(1 == type)
            str = 'Are you sure pay for it?';
        else if(2 == type)
            str = 'Are you sure check for it?';
        if(confirm(str)){
            if(2 == type){
                var fee_name = '#check_' + id;
                fee = $(fee_name).is('checked');
            }
            var paid_column_value = '';
            if(type == 0){
                paid_column_value = "<a class='am-badge am-badge-success'>Check Processing</a>";
            }else if(type == 2){
                paid_column_value = "<a class='am-badge am-badge-success'>Not Paid</a>";
            }else if(type == 1){
                paid_column_value = "<a class='am-badge am-badge-success'>Paid</a>";
            }
            var paid_column = '#paid_column_' + id;
            var option_column = '#option_' + id;
            $.ajax({
                type: 'POST',
                url: '<?php echo $this->createUrl('payment/sendandpay');?>' ,
                data: {id:id,type:type,fee:fee},
                async:false,
                success: function(data){
                    if(1 == data.status){
                        $(paid_column).html('');
                        $(paid_column).html(paid_column_value);
                        $(option_column).children().remove();
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

    $('#upload_month_payment').submit(function () {
        if(!$('#fileField').val()){
            alert('please select a pdf!');
            return false;
        }
    });
</script>
