<?php
include_once dirname(dirname(__FILE__)) . '/sidebar.php';
?>
<link rel="stylesheet" href="assets/css/amazeui.datatables.css"/>
<script src="assets/js/amazeui.datatables.min.js"></script>
<script src="assets/js/ui/jquery.ui.core.js"></script>
<script src="assets/js/ui/jquery.ui.widget.js"></script>
<script src="assets/js/ui/jquery.ui.position.js"></script>
<script src="assets/js/ui/jquery.ui.menu.js"></script>
<script src="assets/js/ui/jquery.ui.autocomplete.js"></script>
<link rel="stylesheet" href="assets/css/ui/demos.css">
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Daily Report</strong></div>
    </div>
    <div class="am-g am-g-fixed">
        <form action="<?php echo $this->createUrl('report/crreport');?>" method="post">
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
                                    <option value="0" selected>Select the site</option>
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
                                        <input type="radio" name="display_type" value="0" data-am-ucheck <?php if(0 == $display_type) echo 'checked';?>>
                                        Date
                                    </label>
                                    <label class="am-radio">
                                        <input type="radio" name="display_type" value="1" data-am-ucheck <?php if(1 == $display_type) echo 'checked';?>>
                                        Project
                                    </label>
                                    <?php if(in_array($this->user['groupid'],array(ADMIN_GROUP_ID,BUSINESS_GROUP_ID,AM_GROUP_ID,SITE_GROUP_ID))){ ?>
                                        <label class="am-radio">
                                            <input type="radio" name="display_type" value="2" data-am-ucheck <?php if(2 == $display_type) echo 'checked';?>>
                                            Affiliate ID
                                        </label>
                                    <?php } ?>
                                    <?php if(in_array($this->user['groupid'],$this->manager_group)){ ?>
                                        <label class="am-radio">
                                            <input type="radio" name="display_type" value="3" data-am-ucheck <?php if(3 == $display_type) echo 'checked';?>>
                                            Site ID
                                        </label>
                                    <?php }?>
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
    <div class="am-g am-g-fixed">
        <div class="am-u-sm-12">
            <?php if(in_array($this->user['groupid'],$this->manager_group)){ ?>
                <div class="am-u-sm-3 am-u-md-3"><button class="am-btn am-btn-warning am-btn-xs" type="button" onclick="javascript:window.location.href='<?php echo $this->createUrl('report/downloadcrexcel')?>'">Download As Excel</button></div>
            <?php }?>
        </div>
        <div class="am-u-sm-12">
                <table class="am-table am-table-striped am-table-bordered am-table-compact" id="crreport">
                    <thead>
                    <?php if($display_type == 0){
                        echo '<th class="table-title">Date</th>';
                    }elseif($display_type == 1){
                        echo '<th class="table-title">Project</th>';
                    }elseif($display_type == 2){
                        echo '<th class="table-title">Affiliate ID</th>';
                    }elseif($display_type == 3){
                        echo '<th class="table-title">Site ID</th>';
                    }?>
                        <th class="table-title">Impression</th>
                        <?php if(in_array($this->user['groupid'],$this->client_group)) {
                            echo '<th class="table-title" > Revenue</th >';
                        }elseif(in_array($this->user['groupid'],$this->manager_group)){
                            echo '<th class="table-title" > Payout</th >';
                            echo'<th class="table-title">Revenue</th>';
                            echo '<th class="table-title">Profit</th>';
                        }?>
                        <th class="table-title"></th>
                    </thead>
                    <tbody>
                    <?php if(!empty($all_report)){foreach($all_report as $report){
                        ?>
                        <tr>
                            <?php if($display_type == 0){
                                echo "<td>{$report['time']}</td>";
                            }elseif($display_type == 1){
                                echo "<td>{$report['project_name']}</td>";
                            }elseif($display_type == 2){
                                echo "<td>{$report['affid']}</td>";
                            }elseif(3 == $display_type){
                                echo "<td>{$report['siteid']}</td>";
                            }?>
                            <td><?php echo empty($report['click_count']) ? 0 : $report['click_count'];?></td>
                            <td><?php echo empty($report['revenue']) ? '0' : $report['revenue'];?></td>
                            <?php
                            if(in_array($this->user['groupid'],$this->manager_group)){
                                $payout = empty($report['payout']) ? 0 : $report['payout'];
                                echo "<td>{$payout}</td>";
                                $pro = $payout - $report['revenue'];
                                if($pro < 0){
                                    $pro = 0;
                                }
                                echo "<td>$pro</td>";
                            }?>
                       <td>
                              <?php if($display_type == 0 ){?>
                                <a  href="javascript:WinOpenByParams('<?php echo $report['time'];?>','<?php echo $display_type;?>');" class="am-btn am-btn-default am-btn-xs am-text-secondary" ><span class="fa fa-line-chart"></span> More</a>
                              <?php }elseif($display_type == 1){ ?>
                                  <a  href="javascript:WinOpenByParams('<?php echo $report['project_name'];?>','<?php echo $display_type;?>');" class="am-btn am-btn-default am-btn-xs am-text-secondary" ><span class="fa fa-line-chart"></span> More</a>
                              <?php }elseif($display_type == 2){?>
                                  <a  href="javascript:WinOpenByParams('<?php echo $report['affid'];?>','<?php echo $display_type;?>');" class="am-btn am-btn-default am-btn-xs am-text-secondary" ><span class="fa fa-line-chart"></span> More</a>
                              <?php }elseif($display_type == 3){?>
                                  <a  href="javascript:WinOpenByParams('<?php echo $report['siteid'];?>','<?php echo $display_type;?>');" class="am-btn am-btn-default am-btn-xs am-text-secondary" ><span class="fa fa-line-chart"></span> More</a>
                              <?php }if(in_array($this->user['groupid'],array(ADMIN_GROUP_ID))){
                                  if($display_type != 3){ ?>
                                  <a  href="javascript:dl('<?php echo $report['affid']?>','<?php echo $report['time'];?>','<?php echo $report['project_name'];?>');" class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only" ><span class="am-icon-trash-o"></span> Delete</a>
                              <?php }else{ ?>
                                  <a  href="javascript:dl('<?php echo $report['siteid']?>','<?php echo $report['time'];?>','<?php echo $report['project_name'];?>');" class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only" ><span class="am-icon-trash-o"></span> Delete</a>
                              <?php }} ?>
                            </td>
                        </tr>
                    <?php }} ?>
                    </tbody>
                </table>
                <hr>
        </div>
    </div>
</div>
<script language="JavaScript">
    $(function() {
        $('#crreport').DataTable(

        );
    });
    function WinOpenByParams(params,displayType) {
        var url = "<?php echo Yii::app()->createUrl('report/reportdetail');?>&params=" + params + '&sdate=' + '<?php echo $sdate;?>' + '&edate=' + '<?php echo $edate;?>' + '&relevance=' + '<?php echo empty($relevance) ? '' : $relevance?>' + '&display_type=' + displayType;
        mesg=open(url,"DisplayWindow","toolbar=no,menubar=no,location=no,scrollbars=yes",true);
    }
    function dl(affid,time,project){
        if(window.confirm('Are you sure to delete the data？')){
            window.location.href = '<?php echo $this->createUrl('report/dl') . "&display_type=$display_type&sdate=$sdate&edate=$edate&project_name=";?>' + project + '&time=' + time + '&affid='+affid;
        }
    }
</script>
<script language="javascript">
    var currentShowCity=0;
    $(document).ready(function(){
        $("#sites").change(function(){
            var i = $("#sites").val();
            var name = "#traffic_"+i;
            $('.am-my').hide();
            $(name).show();
            currentShowCity=i;
        });
        $("#province").change();
    });
</script>