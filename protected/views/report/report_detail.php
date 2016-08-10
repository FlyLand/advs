<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Report Detail</strong></div>
        <hr>
        <div class="am-panel-hd am-cf">
            <p class="am-u-sm-3" style="width: 100px">Sort:</p>
            <?php
            foreach($page_module as $key=>$item){
                $checked = '';
                if($key == $sort){
                    $checked = 'checked';
                }
                echo '<label class="am-radio am-u-sm-3" style="width: 100px;margin-top: 10px;float: left">';
                echo "<input class='am-ucheck-radio' name='sort' value='$key' data-am-ucheck='' $checked type='radio'><span class='am-ucheck-icons'><i class='am-icon-unchecked'></i><i class='am-icon-checked'></i></span>$item</label>";
            } ?>
        </div>
    </div>
    <div class="am-g">
        <div class="am-u-sm-12">
            <form class="am-form">
                <table class="am-table am-table-striped am-table-hover table-main">
                    <thead>
                    <th class="table-title">TIME</th>
                    <?php if($this->user['groupid'] == ADMIN_GROUP_ID){ ?>
                        <th class="table-title">OfferID</th>
                    <?php } ?>
                        <th class="table-title">Project Name</th>
                            <?php if(!in_array($this->user['groupid'],array(AFF_GROUP_ID))){ ?>
                            <th class="table-title">Affiliate ID</th>
                        <?php } ?>
                        <th class="table-title">Impression</th>
                        <?php if(in_array($this->user['groupid'],$this->manager_group)){ ?>
                            <th class="table-title">Payout</th>
                            <th class="table-title">Revenue</th>
                            <?php if($this->user['groupid'] == ADMIN_GROUP_ID){
                            echo "<th class=\"table-title\"><a href='javascript:editColum()'>Edit</a></th>";
                            }?>
                        <?php }elseif($this->user['groupid'] == AFF_GROUP_ID){
                            echo '<th class="table-title">Revenue</th>';
                        }elseif($this->user['groupid'] == SITE_GROUP_ID){
                            echo '<th class="table-title">Revenue</th>';
                        } ?>
                    </thead>
                    <tbody>
                    <?php if(!empty($counts)){
                    foreach($counts as $report){ ?>
                        <tr>
                            <td><?php echo $report['time'];?></td>
                            <?php if($this->user['groupid'] == 1){ ?>
                                  <td><?php echo $report['offerid']?></td>
                            <?php } ?>
                            <td><?php echo $report['project_name'];?></td>
                            <?php if($this->user['groupid'] != 5){
                                echo "<td>{$report['affid']}</td>";
                            }?>
                            <td id="impression_<?php echo $report['id']?>"><?php echo empty($report['click_count']) ? 0 : $report['click_count'];?></td>
                            <td style="display: none" id="edit_impression_<?php echo $report['id']?>"><input  type="text" value="<?php echo empty($report['click_count']) ? 0 : $report['click_count'];?>"></td>
                            <td id="revenue_<?php echo $report['id']?>"><?php echo empty($report['revenue']) ? 0 : $report['revenue'];?></td>
                            <td style="display: none" id="edit_revenue_<?php echo $report['id']?>"><input type="text" value="<?php echo empty($report['revenue']) ? 0 : $report['revenue'];?>"></td>
                            <?php if(in_array($this->user['groupid'],array(ADMIN_GROUP_ID,MANAGER_GROUP_ID,AM_GROUP_ID))){ ?>
                                <td id="payout_<?php echo $report['id']?>"><?php echo empty($report['payout']) ? 0 : $report['payout'];?></td>
                                <td class="editColum" style="display: none">
                                    <a id="edit_<?php echo $report['id'];?>" href="javascript:editCount(<?php echo $report['id']?>);" class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only" ><span class="am-icon-trash-o"></span> Edit</a>
                                    <a style="display: none" id="submit_<?php echo $report['id'];?>" href="javascript:submitCount(<?php echo $report['id']?>);" class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only" ><span class="am-icon-trash-o"></span> Submit</a>
                                    <a style="display: none;" id="cancel_<?php echo $report['id'];?>" href="javascript:cancelCount(<?php echo $report['id']?>);" class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only" ><span class="am-icon-trash-o"></span> Cancel</a>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php }
                        echo "";
                    }else{
                        echo '<td>No Data</td>';
                    } ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>
<script>
    var status = 1;
    function  editColum(){
        if(status == 1){
            $('.editColum').show();
            status = 0;
        }else{
            $('.editColum').hide();
            status = 1;
        }
    }

    function editCount(id){
        var edit_impression = '#edit_impression_' + id;
        var impression = '#impression_' + id;
        var edit_revenue = '#edit_revenue_' + id;
        var revenue = '#revenue_' + id;
        $(edit_impression).show();
        $(edit_revenue).show();
        $(impression).hide();
        $(revenue).hide();
    }
    function cancelCount(id){
        var edit_impression = '#edit_impression_' + id;
        var impression = '#impression_' + id;
        var edit_revenue = '#edit_revenue_' + id;
        var revenue = '#revenue_' + id;
        $(edit_impression).hide();
        $(edit_revenue).hide();
        $(impression).show();
        $(revenue).show();
    }

    function submitCount(id){
        var url = '<?php echo $this->createUrl('report/edit');?>';
        var edit_impression = '#edit_impression_' + id;
        var impression = '#impression_' + id;
        var edit_revenue = '#edit_revenue_' + id;
        var revenue = '#revenue_' + id;
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                type:'update',
                impression: $(edit_impression).children('input').val(),
                revenue:$(edit_revenue).children('input').val(),
                id: id
            },
            datatype:'json',
            success: function(data){
                var ret_data = eval('('+data+')');
                if(0 == ret_data.rs){
                    alert('Failed');
                }else{
                    alert('Success');
                }
            }
        });
        $(edit_impression).hide();
        $(edit_revenue).hide();
        $(impression).show();
        $(revenue).show();
        $('.editColum').hide();
    }
    $("[name='sort']").on("change",
        function (e) {
            var sort = $(e.target).val();
            top.location.href = '<?php echo $route?>' + sort;
        })
</script>
