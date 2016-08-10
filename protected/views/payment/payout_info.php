<?php
include_once dirname(dirname(__FILE__)).'/sidebar.php';
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
<link rel="stylesheet" href="assets/css/amazeui.datatables.css"/>
<script src="assets/js/amazeui.datatables.min.js"></script>
<div class="admin-content">

    <div class="am-tabs" data-am-tabs>
        <ul class="am-tabs-nav am-nav am-nav-tabs">
            <li class="am-active"><a href="#tab1">Month</a></li>
            <li><a href="#tab2">Total</a></li>
        </ul>

        <div class="am-tabs-bd">
            <div class="am-tab-panel am-fade am-in am-active" id="tab1">
                <form action="<?php echo $this->createUrl('payment/sitepayoutinfo');?>" method="post">
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
                                        <div class="am-u-sm-3 am-u-md-3"><p>Site ID:</p></div>
                                        <div class="am-u-sm-6 am-u-md-6">
                                            <select id="traffic" name="traffic[]" multiple data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary',maxHeight: 200,searchBox: 1}"  style="display: none;padding-top: 20px">
                                                <?php if(!empty($sites)){
                                                    foreach($sites as $site){
                                                        if(!empty($site)){
                                                            $checked = '';
                                                            if(isset($site['checked'])) $checked = 'selected';
                                                            echo "<option $checked value='{$site['id']}'>{$site['id']}{$site['company']}</option>";
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
                                    <p style="font-weight: 700">Month Unpaid Amount:   <span style="font-weight: 300"><?php echo empty($invoice['count']['not_paid']) ? 0 : $invoice['count']['not_paid'];?>$</span></p>
                                    <p style="font-weight: 700">Unpaid Amount:   <span style="font-weight: 300"><?php echo empty($invoice['count']['unpaid_all']) ? 0 : $invoice['count']['unpaid_all'];?>$</span></p>
                                    <p style="font-weight: 700">Amount Paid:   <span style="font-weight: 300"><?php echo empty($invoice['count']['paid']) ? 0 : $invoice['count']['paid'];?>$</span></p>
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
                        <th class="minth2">Extra</th>
                        <th class="minth2">Amount Paid</th>
                        <th class="minth2">AM</th>
                        <th class="minth2">Status</th>
                        ';
                                                if(in_array($this->user['groupid'],array(AM_GROUP_ID,ADMIN_GROUP_ID,FINANCE_GROUP_ID))){
                                                    echo '<th  class="minth2">Operation</th>';
                                                }
                                            }else{
                                                echo '<th class="minth2">Invoice Date</th>';
                                                echo '<th class="minth2">Amount sent</th>';
                                                echo '<th class="minth2">AM</th>';
                                                echo '<th class="minth2">Status</th>';
                                            }
                                            ?>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(!empty($invoice['data'])){
                                            foreach($invoice['data'] as $item){
                                                $check_hide = '';
                                                $pay_hide = '';
                                                $paid_column = '';
                                                if ($item['status'] != 1) {
                                                    $check_hide = "style='display:none'";
                                                }
                                                if ($item['status'] != 2) {
                                                    $pay_hide = "style='display:none'";
                                                }
/*                                                $pdf = '';
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
                                                }*/
                                                if(1 == $item['status']){
                                                    $paid_column = "<a class='am-badge am-badge-success'>Checked</a>";
                                                }elseif(3 == $item['status']){
                                                    $paid_column = "<a class='am-badge am-badge-success'>Paid</a>";
                                                }else{
                                                    $paid_column = "<a class='am-badge am-badge-warning'>Not Checked</a>";
                                                }
                                                $extra = empty($item['extra']) ? 0 : $item['extra'];
                                                $paid = empty($item['amount_paid']) ?  0 : "{$item['amount_paid']}";
                                                $amount = empty($item['amount']) ? 0 : $item['amount'];
                                                $amount_paid = $amount;
                                                $amount -= $extra;
						$amount=number_format($amount,2);
                                                if(in_array($this->user['groupid'],$this->manager_group)){
                                                    echo "<tr id='column_{$item['id']}'>";
                                                    echo "<td>{$item['site_id']}</td>";
                                                    echo "<td>{$item['company']}</td>";
                                                    $month = date('M',strtotime($item['count_date']));
                                                    echo "<td>$month</td>";
                                                    echo "<td>{$amount}</td>";
/*                                                    echo "<td>$pdf</td>";
                                                    echo "<td>$postback_pdf</td>";*/
                                                    echo "<td>$extra</td>";
                                                    echo "<td></td>";
                                                    echo "<td>{$item['am']}</td>";
                                                    echo "<td>$paid_column</td>";
                                                    echo '<td>';
                                                    if(in_array($this->user['groupid'],$this->manager_group)){
                                                        echo "<a  href='javascript:WinOpenWithParams({$item['site_id']})' class='am-btn am-btn-default am-btn-xs am-text-secondary' ><span class='fa fa-line-chart'></span> More</a>";
                                                    }
                                                    echo "</td>";
                                                }else{
                                                    $month = date('M',strtotime($item['count_date']));
                                                    echo "<tr>";
                                                    echo "<td>{$item['id']}</td>";
                                                    echo "<td>{$month}</td>";
                                                    echo "<td></td>";
                                                    echo "<td>{$item['am']}</td>";
                                                    echo $paid_column;
                                                }
                                                echo "</tr>";
                                            }
                                        }else echo '';
                                        ;?>
                                        </tbody>
                                    </table>
                                    <hr>
                                    <?php if(in_array($this->user['groupid'],array(ADMIN_GROUP_ID,AM_GROUP_ID))){
                                        echo "<button type='button' class='am-btn am-btn-success am-round' onclick='checkall()'>Check</button>";

                                    }
                                    if(in_array($this->user['groupid'],array(FINANCE_GROUP_ID,ADMIN_GROUP_ID))){
                                        echo "<button type='button' class='am-btn am-btn-warning am-round' onclick='savepayout()'>Save</button>";
                                    }
                                    ?>
                                    <div class="am-cf"> Total <?php echo $count?> Records
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
            <div class="am-tab-panel am-fade" id="tab2">
                <table class="am-table am-table-striped am-table-bordered am-table-compact" id="total_payment">
                    <thead>
                    <tr>
                        <td>Site Id</td>
                        <td>Company</td>
                        <td>Not Paid</td>
                        <?php if(in_array($this->user['groupid'],array(ADMIN_GROUP_ID,FINANCE_GROUP_ID))){
                            echo "<td>Fee</td>";
                        }?>
                        <td>Operation</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($total_payment)){
                        $not_paid_all = 0;
                        $paid_all = 0;
                        $should_paid_all = 0;
                        foreach($total_payment as $item){
                            $paid = empty($item['paid']) ?  0 : $item['paid'];
                            $not_paid = empty($item['not_paid']) ? 0 : $item['not_paid'];
                            $paid_true = empty($item['paid_true']) ? 0 : $item['paid_true'];
                            $predict_paid = empty($item['predict_paid']) ? 0 : $item['predict_paid'];
                            if($not_paid == 0){
                                continue;
                            }
                            echo "<tr id='total_{$item['site_id']}'>";
                            echo "<td>{$item['site_id']}</td>";
                            echo "<td>{$item['company']}</td>";
                            $balance = $paid - $paid_true;
                            echo "<td>$not_paid</td>";
                            if(in_array($this->user['groupid'],array(ADMIN_GROUP_ID,FINANCE_GROUP_ID)) && $not_paid != 0){
                                echo "<td><input id='check_{$item['site_id']}' type='checkbox' name='fee'></td>";
                                echo "<td><a  href='javascript:saveTotalPayment({$item['site_id']})' class='am-btn am-btn-default am-btn-xs am-text-secondary' ><span class='fa fa-line-chart'></span> Pay</a></td>";
                            }else{
                                echo "<td></td>";
                            }
                            echo "</tr>";
                            $not_paid_all += $not_paid;
                            $paid_all += $paid;
                        }
                    }?>
                    </tbody>
                </table>
                <?php if(in_array($this->user['groupid'],array(FINANCE_GROUP_ID,ADMIN_GROUP_ID))){
                    echo "<button type='button' class='am-btn am-btn-warning am-round' onclick='saveTotalPayment()'>Pay All</button>";
                }?>
            </div>
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
    $('#total_payment').DataTable();
    var setUpload = function(id){
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
            str = 'Are you sure set invoice?';
        else if(1 == type)
            str = 'Are you sure pay for it?';
        if(confirm(str)){
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
                            $(pay_div).show();
                        }
                        if(1 == type){
                            $(pay_div).remove();
                        }
                        $("#alertContent").html('Success');
                        $("#my-alert").modal();
                        window.location.reload();
                    }else{
                        $("#alertContent").html('Failed');
                        $("#my-alert").modal();
                    }
                } ,
                dataType: 'json'
            })
        }
    };

    var WinOpenWithParams = function(site_id){
        var url = '<?php echo $this->createUrl('payment/detail');?>&sid=' + site_id + '&cd=' + '<?php echo $count_date;?>';
        mesg=open(url,"DisplayWindow","toolbar=no,menubar=no,location=no,scrollbars=yes",true);
    };

    var openPDF = function (name) {
        window.open("<?php echo $this->createUrl('payment/viewPDF');?>&name=" + name);
    };

    var savepayout =   function() {
        var i = $('#payments tr').size();
        var str = new Array(i);
        var cloumn_name = '';
        for (var t = 0; t < i - 1; t++) {
            str[t] = new Array(6);
            cloumn_name= $('#payments tr').eq(t + 1).attr('id');
            if($('#payments tr').eq(t + 1).find("td").eq(4).find("input ").val()){
                str[t][1] = cloumn_name.substring(7,cloumn_name.length);
                str[t][2] = $('#payments tr').eq(t + 1).find("td").eq(4).find("input ").val();
            }
        }
        var url_post = "<?php echo $this->createUrl('payment/savepayout');?>";
        $.ajax({
            type: 'POST',
            url: url_post,
            async: false,
            data: {data: str},
            success: function (msg) {
                alert(msg);
                location.reload(true);
            }
        });
    };

    var saveTotalPayment =   function(siteid = null) {
        var i = $('#total_payment tr').size();
        var str = new Array(i);
        var cloumn_name = '';
        if(siteid != null){
            var tr_name = '#total_' + siteid;
            var td = $(tr_name).find("td:nth-child(5)");
            var fee_value = td.children().is(':checked');
            var url_post = "<?php echo $this->createUrl('payment/savetotalpayment');?>";
            if(confirm('Are you sure pay for it?')) {
                $.ajax({
                    type:'POST',
                    url:url_post,
                    async: false,
                    data:{site:siteid,fee:fee_value},
                    success: function (msg) {
                        alert(msg);
                    }
            });
            }
        }else{
            if(confirm('Are you sure pay for it?')) {
                for (var t = 0; t < i - 1; t++) {
                    str[t] = new Array(6);
                    cloumn_name= $('#total_payment tr').eq(t + 1).attr('id');
                    str[t][1] = cloumn_name.substring(6,cloumn_name.length);
                }
                var url_post = "<?php echo $this->createUrl('payment/savetotalpayment');?>";
                $.ajax({
                    type: 'POST',
                    url: url_post,
                    async: false,
                    data: {data: str},
                    success: function (msg) {
                        alert(msg);
                    }
                });
            }
        }
    };

    var checkall =   function() {
        var i = $('#payments tr').size();
        var str = new Array(i);
        var cloumn_name = '';
        for (var t = 0; t < i - 1; t++) {
            str[t] = new Array(2);
            cloumn_name= $('#payments tr').eq(t + 1).attr('id');
            str[t][0] = cloumn_name.substring(7,cloumn_name.length);
        }
        var url_post = "<?php echo $this->createUrl('payment/checkall');?>";
        $.ajax({
            type: 'POST',
            url: url_post,
            async: false,
            data: {data: str},
            success: function (msg) {
                alert(msg);
                location.reload(true);
            }
        });
    };
   
</script>
