<style type="text/css">
</style>
<?php
/**
 * offer管理列表
 */
include_once dirname(dirname(__FILE__)).'/sidebar.php';
?>
<link rel="stylesheet" href="assets/css/amazeui.datatables.css"/>
<script src="assets/js/amazeui.datatables.min.js"></script>
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Report Detail</strong></div>
    </div>
    <div class="am-g am-g-fixed">
        <form action="<?php echo $this->createUrl('report/realtimereport');?>" method="post">
            <div class="am-u-md-12">
                <div class="am-panel am-panel-default">
                    <div class="am-panel-hd am-cf">
                        <div class="am-u-sm-12 am-u-md-12">
                            <div class="am-u-sm-6 am-u-md-6">
                                <div class="am-u-sm-3 am-u-md-3"> From :</div>
                                <div class="am-u-sm-9 am-u-md-9">
                                    <p><input type="text" name="sdate" id="sdate" class="am-form-field" value="<?php echo $sdate?>" data-am-datepicker="{theme: 'default'}" readonly /></p>
                                </div>
                            </div>
                            <div class="am-u-sm-6 am-u-md-6">
                                <div class="am-u-sm-3 am-u-md-3"> To :</div>
                                <div class="am-u-sm-9 am-u-md-9">
                                    <p><input type="text" name="edate" id="edate" class="am-form-field" value="<?php echo $edate?>" data-am-datepicker="{theme: 'default'}" readonly /></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="am-panel am-panel-default">
                    <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-4'}">Filters<span class="am-icon-chevron-down am-fr" ></span></div>
                    <div id="collapse-panel-4" class="am-panel-bd am-collapse am-in">
                        <?php if(in_array($this->user['groupid'],array(ADMIN_GROUP_ID,BUSINESS_GROUP_ID,AM_GROUP_ID,SITE_GROUP_ID))){ ?>
                            <div class="am-g">
                                <div class="am-u-sm-3 am-u-md-3"><p>Site ID:</p></div>
                                <div class="am-u-sm-6 am-u-md-6">
                                    <select id="sites" name="sites[]" multiple data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary',maxHeight: 200,searchBox: 1}"  style="display: none;padding-top: 20px">
                                        <?php if(!empty($sites)){
                                            foreach($sites as $site => $item){
                                                if(!empty($site)){
                                                    $checked = '';
                                                    if(isset($site['checked'])) $checked = 'selected';
                                                    echo "<option $checked value='{$site}'>{$item['id']}                {$item['company']}</option>";
                                                }
                                            }
                                        }?>
                                    </select>
                                </div>
                            </div>
                            <div class="am-g">
                                <div class="am-u-sm-3 am-u-md-3"><p>Affiliate ID:</p></div>
                                <div class="am-u-sm-6 am-u-md-6">
                                    <div id='traffic_0' class='am-my'>
                                        <select id='traffic' name='traffic[]' multiple data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary',maxHeight: 200,searchBox: 1}"  style="display: none;padding-top: 20px">
                                            <?php if(!empty($affs)){
                                                foreach($affs as $aff){
                                                    echo "<option value='{$aff['id']}'>{$aff['id']}</option>";
                                                }
                                            }?>
                                        </select>
                                    </div>
                                    <?php if(!empty($sites)){
                                        foreach($sites as $site=>$item){
                                            echo "<div id='traffic_$site' class='am-my'>";
                                            echo "<select id='traffic' name='traffic[]' multiple data-am-selected=\"{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary',maxHeight: 200,searchBox: 1}\"  style=\"display: none;padding-top: 20px\">";
                                            foreach($item['aff'] as $affid){
                                                $aff_params = JoySystemUser::model()->findByPk($affid);
                                                if(!empty($aff_params)){
                                                    echo "<option value='{$aff_params['id']}'>{$aff_params['id']}                  {$aff_params['title']}</option>";
                                                }
                                            }
                                            echo  "</select>";
                                            echo "</div>";
                                        }
                                    }?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="am-g">
                            <div class="am-u-sm-3 am-u-md-3"><p>Display By:</p></div>
                            <div class="am-u-sm-6 am-u-md-6">
                                <div class="am-u-sm-6">
                                    <label class="am-radio">
                                        <input type="radio" name="display_type" value="0" data-am-ucheck <?php if(0 == $display_type) echo 'checked';?>>Redistribute
                                    </label>
                                    <label class="am-radio">
                                        <input type="radio" name="display_type" value="0" data-am-ucheck <?php if(1 == $display_type) echo 'checked';?>>Group Hour
                                    </label>
                                    <label class="am-radio">
                                        <input type="radio" name="display_type" value="0" data-am-ucheck <?php if(2 == $display_type) echo 'checked';?>>Affiliate ID
                                    </label>
                                    <label class="am-radio">
                                        <input type="radio" name="display_type" value="0" data-am-ucheck <?php if(3 == $display_type) echo 'checked';?>>Site ID
                                    </label>
                                </div>
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
        <div class="am-g">
            <div class="am-u-sm-12">
                <form class="am-form">
                    <div class="am-panel-group" id="accordion">
                        <table class="am-table am-table-striped am-table-bordered am-table-compact" id="real_report">
                            <thread>
                                <tr>
                                    <?php if($display_type == 0){
                                        echo "
                                        <td>Site Id</td>
                                        <td>Affiliate Id</td>
                                        <td>Impression</td>
                                        <td>Request</td>
                                        <td>Operate</td>
    ";}?>
                                </tr>
                            </thread>
                        <?php
                       if(!empty($data)) {
                           if ($display_type == 0) {
                               foreach($data as $site_id=>$item){
                                   foreach($item as $aff=>$value){
                                       echo "<tr>";
                                       echo "<td>$site_id</td>";
                                       echo "<td>$aff</td>";
                                       echo "<td>{$value['impression']}</td>";
                                       $count = empty($value['count']) ? 0 : $value['count'];
                                       echo "<td>{$count}</td>";
                                       echo "<td><a href='javascript:winOpenByParams(\"$aff\")' class=\"am-btn am-btn-default am-btn-xs am-text-secondary\"><span class=\"fa fa-line-chart\"></span>More</a></td>";
                                       echo "</tr>";
                                   }
                               }
                           }
                       }?>
                    </div>
                    <hr>
                </form>
            </div>
            </div>
        </div>
<script>
    $(function() {
          $('#real_report').DataTable();
    });
    function winOpenByParams(params) {
        var url = "<?php echo $this->createUrl('report/dailyreportdetailmore');?>&affid="+params;
        mesg=open(url,"DisplayWindow","toolbar=no,menubar=no,location=no,scrollbars=yes",true);
    }
</script>
