<?php require_once dirname(dirname(__FILE__)) . '/sidebar.php';?>
<link rel="stylesheet" href="assets/css/amazeui.datatables.css"/>
<script src="assets/js/amazeui.datatables.min.js"></script>
<!-- content start -->
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Affiliates</strong> </div>
    </div>
    <div class="am-g">
        <div class="am-u-sm-12 am-u-md-6">
            <div class="am-btn-toolbar">
                <div class="am-btn-group am-btn-group-xs">
 <!--                   <button type="button" class="am-btn am-btn-default" onclick="location.href='<?php echo $this->createUrl('affiliates/create');?>';return false;"><span class="am-icon-plus"></span> Add Affilite</button> -->
                    <!--                     <button type="button" class="am-btn am-btn-default"><span class="am-icon-trash-o"></span> É¾³ý</button>-->
                </div>
            </div>
        </div>
    </div>
                <table class="am-table am-table-striped am-table-bordered am-table-compact" id="dbs">
                <thead>
                    <tr>
                        <td >id</td>
                        <td >Affiliates</td>
                        <td >Status</td>
                        <!--<th 2">Activity Fraud</th> -->
                        <td >Offers</td>
                        <?php if(in_array($this->user['groupid'],array(ADMIN_GROUP_ID,AM_GROUP_ID,MANAGER_GROUP_ID))){ ?>
                            <td>Cutnum</td>
                        <?php } ?>
                        <td class="maxth">Clicks</td>
                        <td class="maxth">Conversions</td>
                        <td class="timeth">Cost</td>
                        <td >Revenue</td>
                        <td >Profit</td>
                        <td>Manager</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                <?php
                if(!empty($affiliates)){
                foreach ($affiliates as $affiliate){ ?>
                    <tr id="<?php echo $affiliate['id']?>">
                        <td><a href="<?php echo $this->createUrl('affiliates/edit',array('id'=>$affiliate['id']))?>"><?php echo $affiliate['id']?></a></td>
                        <td><a href="<?php echo $this->createUrl('report/affreport',array('id'=>$affiliate['id']))?>"><?php echo $affiliate['title']?></a></td>
                        <td><?php echo (1 == $affiliate['status']) ? 'active': 'pending';?></td>
                        <td><?php echo isset($affiliate['offer_num'])?$affiliate['offer_num']:0?></td>
                        <?php if(in_array($this->user['groupid'],array(ADMIN_GROUP_ID,MANAGER_GROUP_ID,AM_GROUP_ID))){?>
                            <td><input style="width: 40%" value="<?php echo isset($affiliate['cutcount'])?$affiliate['cutcount']:0?>">%</td>
                        <?php } ?>
                        <td><?php echo isset($affiliate['click']) ? $affiliate['click']:0?></td>
                        <td><?php echo  isset($affiliate['conversions'])?$affiliate['conversions']:0?></td>
                        <td>$<?php echo $pay_num = isset($affiliate['pay_num'])?$affiliate['pay_num']:0?></td>
                        <td>$<?php echo $re_num = isset($affiliate['re_num'])?$affiliate['re_num']:0?></td>
                        <td>$<?php echo $re_num - $pay_num;?></td>
                        <td><?php echo $affiliate['manager_userid'];?></td>
                        <td>
                            <div class="am-btn-toolbar">
                                <div class="am-btn-group am-btn-group-xs">
                                    <a href="<?php echo $this->createUrl('affiliates/edit',array('id'=>$affiliate['id']))?>" class="am-btn am-btn-default am-btn-xs am-text-secondary" ><span class="am-icon-pencil-square-o"></span> Edit</a>
                                    <?php if(in_array($this->user['groupid'],array('1','2','6'))){ ?>
                                        <button onclick="deleteaff(<?php echo $affiliate['id']?>)" class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only" ><span class="am-icon-trash-o"></span> Delete</button>
                                    <?php } ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php }}?>
                </tbody>
            </table>
            <button type="button" class="am-btn am-btn-warning am-round" onclick="savecut()">Save</button>
            <hr>
        </div>
<script>
    $(function() {
        $('#dbs').DataTable();
    });
    var deleteaff  =   function(id){
        if(confirm('are you sure to delete this one?')){
            location.href   =   '<?php echo $this->createUrl('affiliates/update',array('type'=>'delete'));?>&id='+id;
        }
    };

    var savecut =   function() {
        var i = $('#dbs tr').size();
        var str = new Array(i);
        for (var t = 0; t < i - 1; t++) {
            str[t] = new Array(6);
            str[t][1] = $('#dbs tr').eq(t + 1).attr('id');
            str[t][2] = $('#dbs tr').eq(t + 1).find("td").eq(2 + 2).find("input ").val();
        }

        var url_post = "<?php echo $this->createUrl('offer/updatecut');?>";
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
    }
</script>
