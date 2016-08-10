<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/26
 * Time: 17:52
 */
$sites = JoySites::getCompanySite();
?>
<style>
    .pagination {
        display: inline-block;
        padding-left: 0;
        margin: 0;
        border-radius: 4px;
        font-size:14px;
    }
    .pagination>li {
        display: inline;
    }
    .pagination>.active>a, .pagination>.active>span, .pagination>.active>a:hover, .pagination>.active>span:hover, .pagination>.active>a:focus, .pagination>.active>span:focus {
        z-index: 2;
        color: #fff;
        cursor: pointer;
        background-color: #428bca;
        border-color: #428bca;
    }
    .pagination>li>a, .pagination>li>span {
        position: relative;
        float: left;
        padding: 2px 10px;
        margin-left: -1px;
        line-height: 1.428571429;
        text-decoration: none;
        background-color: #fff;
        border: 1px solid #ddd;
    }
</style>
<script type="text/javascript" src="./assets/js/pdfobject.js"></script>
<div class="admin-content">
        <form action="<?php echo $this->createUrl('payment/invoice');?>" method="post">
            <div class="am-u-md-12">
                <div class="am-panel am-panel-default">
                    <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-4'}">Filters<span class="am-icon-chevron-down am-fr" ></span></div>
                    <div id="collapse-panel-4" class="am-panel-bd am-collapse am-in">
                        <div class="am-g">
                            <div class="am-u-sm-3 am-u-md-3"> Invoice Month :</div>
                            <div class="am-u-sm-6 am-u-md-6" >
                                <p><input type="text" name="count_date" id="count_date" class="am-form-field" value="<?php echo $count_date?>" data-am-datepicker="{format: 'yyyy-mm', viewMode: 'months', minViewMode: 'months'}"" readonly /></p>
                            </div>
                        </div>
                        <?php if(in_array($this->user['groupid'],array(ADMIN_GROUP_ID,BUSINESS_GROUP_ID,AM_GROUP_ID,SITE_GROUP_ID))){ ?>
                            <div class="am-g">
                                <div class="am-u-sm-3 am-u-md-3"><p>Affiliate ID:</p></div>
                                <div class="am-u-sm-6 am-u-md-6">
                                    <select id="traffic" name="traffic[]" multiple data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary',maxHeight: 200,searchBox: 1}"  style="display: none;padding-top: 20px">
                                        <?php if(!empty($affids)){
                                            foreach($affids as $affid){
                                                if(!empty($affid)){
                                                    $checked = '';
                                                    if(isset($affid['checked'])) $checked = 'selected';
                                                    echo "<option $checked value='{$affid['id']}'>{$affid['id']}{$affid['company']}</option>";
                                                }
                                            }
                                        }?>
                                    </select>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if(in_array($this->user['groupid'],array(AM_GROUP_ID,ADMIN_GROUP_ID,MANAGER_GROUP_ID))){ ?>
                            <div class="am-g">
                                <div class="am-u-sm-3 am-u-md-3"><p>AM:</p></div>
                                <div class="am-u-sm-6 am-u-md-6">
                                    <select id="am_list_select" name="am_list_select[]" multiple data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary',maxHeight: 200,searchBox: 1}"  style="display: none;padding-top: 20px">
                                        <?php if(!empty($am_list)){
                                            foreach($am_list as $am){
                                                if(!empty($am)){
                                                    $checked = '';
                                                    if(isset($am['checked'])) $checked = 'selected';
                                                    echo "<option $checked value='{$am['id']}'>{$am['id']}{$am['company']}</option>";
                                                }
                                            }
                                        }?>
                                    </select>
                                </div>
                            </div>
                        <?php }?>
                        <div class="am-g">
                            <div class="am-u-sm-3 am-u-md-3"><button class="am-btn am-btn-warning am-btn-xs" type="submit">GO</button></div>
                            <?php if(in_array($this->user['groupid'],$this->manager_group) ){?>
                                <div class="am-u-sm-6 am-u-md-6" >
                                    <button type="button" onclick="javascript:window.location.href='<?php echo $this->createUrl('payment/outputexcel');?>'" class="am-btn am-btn-warning">Download As Excel</button>
                                </div>
                            <?php }?>
                        </div>
                        <div class="am-u-3" style="padding-top: 3%">
                            <p style="font-weight: 700">Unpaid Balance:   <span style="font-weight: 300"><?php echo empty($invoice['count']['not_paid']) ? 0 : $invoice['count']['not_paid'];?>$</span></p>
                            <p style="font-weight: 700">Minimum Payout:   <span style="font-weight: 300"><?php echo  MIN_PAYOUT;?>$</span></p>
                        </div>
                        <div class="am-u-sm-12">
                            <table class="am-table am-table-striped am-table-hover" id="payments">
                                <thead>
                                <tr>
                                    <?php
                                    if(in_array($this->user['groupid'],$this->manager_group)){
                                        echo '
                        <th class="minth2">Site ID</th>
                        <th class="minth2">Company</th>
                       <th class="minth2">Invoice Date</th>
                        <th class="minth2">Amount</th>
                        <th class="minth2">Amount Paid</th>
                        <th class="minth2">Invoice PDF</th>
                        <th class="minth2">Signed PDF</th>
                        <th class="minth2">AM</th>
                        <th class="minth2">Finance</th>
                        <th class="minth2">Status</th>
                        <th class="minth2">Pay Date</th>';
                                        if(in_array($this->user['groupid'],array(AM_GROUP_ID,ADMIN_GROUP_ID,FINANCE_GROUP_ID))){
                                            echo '<th  class="minth2">Operation</th>';
                                        }
                                    }else{
                                        echo '<th class="minth2">Invoice Date</th>';
                                        echo '<th class="minth2">Amount sent</th>';
                                        echo '<th class="minth2">Invoice PDF</th>';
                                        echo '<th class="minth2">Signed PDF</th>';
                                        echo '<th class="minth2">AM</th>';
                                        echo '<th class="minth2">Status</th>';
                                    }
                                    ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if(!empty($invoice['data'])){
                                    foreach($invoice['data'] as $item){
                                        $row_count = 0;
                                        $count_date = $item['count_date'];
                                        $arr = array();
                                        if(!empty($item['affids'])){
                                            $data = JoyInvoice::getResult("*"," and affid in ({$item['affids']}) and count_date='$count_date'");
                                            if(!empty($data)){
                                                $item['aff_data'] = $data;
                                                $row_count += count($data);
                                            }
                                        }
                                        $check_hide = '';
                                        $pay_hide = '';
                                        if ($item['status'] == 1 || $item['status'] == 2) {
                                            $check_hide = "style='display:none'";
                                        }
                                        if ($item['status'] == 2 || $item['status'] == 0) {
                                            $pay_hide = "style='display:none'";
                                        }
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
                                            $paid_column = "<a class='am-badge am-badge-warning'>Not Paid</a>";
                                        }elseif(1 == $item['status']){
                                            $paid_column = "<a class='am-badge am-badge-success'>Checked</a>";
                                        }elseif(2 == $item['status']){
                                            $paid_column = "<a class='am-badge am-badge-success'>Payed</a>";
                                        }
                                        if(in_array($this->user['groupid'] ,array(FINANCE_GROUP_ID,ADMIN_GROUP_ID,MANAGER_GROUP_ID))){
                                            $paid = "<input type='number' id='paid_{$item['id']}' style='width: 100%' name='paid_{$item['id']}' class='am-form-field' value='{$item['amount_paid']}'>";
                                        }else{
                                            $paid = "{$item['amount_paid']}";
                                        }
                                        if(in_array($this->user['groupid'],$this->manager_group)){
                                            if(isset($item['aff_data'])){
                                                echo "<tr>";
                                                echo "<td style='vertical-align:middle;' rowspan='$row_count'>{$item['site_id']}</td>";
                                                echo "<td style='vertical-align:middle;' rowspan='$row_count'>{$item['company']}</td>";
                                                $is_set = 0;
                                                foreach($item['aff_data'] as $param){
                                                    if($is_set == 1){
                                                        echo "<tr>";
                                                    }
                                                    $month = date('M',strtotime($param['count_date']));
                                                    echo "<td style='vertical-align:middle;'>$month</td>";
                                                    if($is_set == 0){
                                                        echo "<td style='vertical-align:middle;' align='center' rowspan='$row_count'>{$item['amount']}</td>";
                                                        echo "<td style='vertical-align:middle;' align='center' rowspan='$row_count'>$paid</td>";
                                                        echo "<td style='vertical-align:middle;' align='center' rowspan='$row_count'>$pdf</td>";
                                                        echo "<td style='vertical-align:middle;' align='center' rowspan='$row_count'>$postback_pdf</td>";
                                                        echo "<td style='vertical-align:middle;' align='center' rowspan='$row_count'>{$item['am']}</td>";
                                                        echo "<td style='vertical-align:middle;' align='center' rowspan='$row_count'>{$item['finace']}</td>";
                                                        echo "<td style='vertical-align:middle;' rowspan='$row_count'>$paid_column</td>";
                                                        echo "<td style='vertical-align:middle;' rowspan='$row_count'>{$item['pay_date']}</td>";
                                                        echo "<td style='vertical-align:middle;' rowspan='$row_count'>";
                                                        if($this->user['groupid'] == AM_GROUP_ID || ADMIN_GROUP_ID == $this->user['groupid']){
                                                            echo "<div $check_hide id='check_div_{$item['id']}'><button  id='checkButton_{$item['id']}' onclick='Check({$item['id']},0)' class='am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only'><span class='am-icon-edit'></span> Check</button></div>";
                                                        }
                                                        if($item['amount_paid'] > MIN_PAYOUT && in_array($this->user['groupid'],array(FINANCE_GROUP_ID,ADMIN_GROUP_ID))){
                                                            echo "<div $pay_hide id='pay_div_{$item['id']}'><button  id='payButton_{$item['id']}' onclick='Check({$item['id']},1)' class='am-btn am-btn-default am-btn-xs am-text-default am-hide-sm-only'><span class='am-icon-edit'></span> Pay</button></div>";
                                                        }
                                                        echo '</td>';
                                                        $is_set = 1;
                                                    }
                                                    echo "</tr>";
                                                }
                                                echo "</tr>";
                                            }else{
                                                echo "<tr>";
                                                echo "<td>{$item['site_id']}</td>";
                                                echo "<td>{$item['company']}</td>";
                                                $month = date('M',strtotime($item['count_date']));
                                                echo "<td>$month</td>";
                                                echo "<td>{$item['amount']}</td>";
                                                echo "<td>$paid</td>";
                                                echo "<td>$pdf</td>";
                                                echo "<td>$postback_pdf</td>";
                                                echo "<td>{$item['am']}</td>";
                                                echo "<td>{$item['finace']}</td>";
                                                echo "<td>$paid_column</td>";
                                                echo "<td>{$item['pay_date']}</td>";
                                                echo '<td>';
                                                if($this->user['groupid'] == AM_GROUP_ID || ADMIN_GROUP_ID == $this->user['groupid']){
                                                    echo "<div $check_hide id='check_div_{$item['id']}'><button  id='checkButton_{$item['id']}' onclick='Check({$item['id']},0)' class='am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only'><span class='am-icon-edit'></span> Check</button></div>";
                                                }
                                                if($item['amount_paid'] > MIN_PAYOUT && in_array($this->user['groupid'],array(FINANCE_GROUP_ID,ADMIN_GROUP_ID))){
                                                    echo "<div $pay_hide id='pay_div_{$item['id']}'><button  id='payButton_{$item['id']}' onclick='Check({$item['id']},1)' class='am-btn am-btn-default am-btn-xs am-text-default am-hide-sm-only'><span class='am-icon-edit'></span> Pay</button></div>";
                                                }
                                            }
                                            echo '</td>';
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
                            <hr>

                            <div class="am-cf"> 共 <?php echo $count?> 条记录
                                <div class="am-fr">
                                    <?php echo isset($fenyecode) ? $fenyecode : ''; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
