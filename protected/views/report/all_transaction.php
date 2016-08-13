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
<link rel="stylesheet" href="assets/css/amazeui.datatables.css"/>
<script src="assets/js/amazeui.datatables.min.js"></script>
<div class="admin-content">
    <div class="am-tabs">
        <div class="am-tabs-bd">
            <form action="<?php echo $this->createUrl('report/dailyreportim');?>" method="post">
                <div class="am-u-md-12">
                    <div class="am-panel am-panel-default">
                        <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-4'}">Filters<span class="am-icon-chevron-down am-fr" ></span></div>
                        <div id="collapse-panel-4" class="am-panel-bd am-collapse am-in">
                            <div class="am-g">
                                <div class="am-u-sm-3 am-u-md-3">  Month :</div>
                                <div class="am-u-sm-6 am-u-md-6" >
                                    <p><input type="text" name="count_date" id="count_date" class="am-form-field" value="<?php echo $count_date?>" data-am-datepicker="{format: 'yyyy-mm-dd', viewMode: 'day', minViewMode: 'day'}" readonly ></p>
                                </div>
                            </div>
                            <div class="am-g">
                                <div class="am-u-sm-3 am-u-md-3"><button class="am-btn am-btn-warning am-btn-xs" type="submit">GO</button></div>
                            </div>
                        </div>
                    </div>
                    <div class="am-u-sm-12">
                        <div class="am-u-sm-3 am-u-md-3"><button class="am-btn am-btn-warning am-btn-xs" type="button"
                                                                 onclick="javascript:window.location.href='<?php echo $this->createUrl('report/downloadimpression')?>'">Download Impression</button></div>
                        <div class="am-u-sm-3 am-u-md-3"><button class="am-btn am-btn-warning am-btn-xs" type="button"
                                                                 onclick="javascript:window.location.href='<?php echo $this->createUrl('report/downloadincome')?>'">Download Income</button></div>
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
                        <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-1'}">Impression<span class="am-icon-chevron-down am-fr" ></span></div>
                        <div id="collapse-panel-1" class="am-panel-bd am-collapse am-in">
                            <div class="am-g">
                                <table class="am-table am-table-striped am-table-bordered am-table-compact" id="count_result">
                                    <thead>
                                    <tr>
                                        <td>Affiliate Id</td>
                                        <td>Offer Id</td>
                                        <td>Impression</td>
                                        <td>Transaction Income Count</td>
                                        <td>Revenue</td>
                                        <td>Payout</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(!empty($count_result)){
                                        foreach($count_result as $item){
                                            echo "<tr>";
                                            echo "<td>{$item['affid']}</td>";
                                            echo "<td>{$item['offerid']}</td>";
                                            echo "<td>{$item['impression']}</td>";
                                            echo "<td>{$item['revenue']}</td>";
                                            echo "<td>{$item['payout']}</td>";
                                            echo "<td>{$item['coun']}</td>";
                                            if($item['ispostbacked']){
                                                echo "<td>postbacked</td>";
                                            }else{
                                                echo "<td>not postbacked</td>";
                                            }
                                            echo "</tr>";
                                        }
                                    }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
    $('#count_result').DataTable();
</script>