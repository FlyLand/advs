<?php require_once dirname(dirname(__FILE__)) . '/sidebar.php';?>
<link rel="stylesheet" href="assets/css/amazeui.datatables.css"/>
<script src="assets/js/amazeui.datatables.min.js"></script>
<!-- content start -->
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Sites</strong> </div>
    </div>
    <div class="am-g">
        <div class="am-u-sm-12 am-u-md-6">
            <div class="am-btn-toolbar">
                <div class="am-btn-group am-btn-group-xs">
                    <button type="button" class="am-btn am-btn-default" onclick="location.href='<?php echo $this->createUrl('site/create');?>';return false;"><span class="am-icon-plus"></span> Add Sites</button>
                </div>
            </div>
        </div>
    </div>
    <table class="am-table am-table-striped am-table-bordered am-table-compact" id="dbs">
        <thead>
        <tr>
            <td >id</td>
            <td >Company</td>
            <td >Email</td>
            <td >Status</td>
            <td >Affiliates</td>
            <td >AM</td>
            <td></td>
        </tr>
        </thead>
        <tbody>
        <?php
        if(!empty($sites)){
            foreach ($sites as $site){?>
                <tr id="<?php echo $site['id']?>">
                    <td><a href="<?php echo $this->createUrl('site/edit',array('id'=>$site['id']))?>"><?php echo $site['id']?></a></td>
                    <td><?php
                        $site_company = $site['company'];
                        echo $site_company;
                        ?></td>
                    <td><?php echo $site['email'];?></td>
                    <td><?php echo (1 == $site['status']) ? 'active': 'pending';?></td>
                    <td><?php
                        $aff_ids = implode(',',$site['aff']);
                        $num = strlen($aff_ids);
                        $partition = 20;
                        if($num > $partition){
                            for($i = 1;$i * $partition < $num;$i++){
                                $aff_ids = substr($aff_ids,0,$i * $partition) . '</br>' . substr($aff_ids,$i * $partition,$num);
                            }
                        }
                        echo $aff_ids;
                        ;?></td>
                    <td><?php echo $site['am_name'];?></td>
                    <td>
                        <div class="am-btn-toolbar">
                            <div class="am-btn-group am-btn-group-xs">
                                <a href="<?php echo $this->createUrl('affiliates/edit',array('id'=>$site['id']))?>" class="am-btn am-btn-default am-btn-xs am-text-secondary" ><span class="am-icon-pencil-square-o"></span> Edit</a>
                                <?php if(in_array($this->user['groupid'],$this->manager_group)){ ?>
                                    <button onclick="deletesite(<?php echo $site['id']?>)" class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only" ><span class="am-icon-trash-o"></span> Delete</button>
                                <?php } ?>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php }}?>
        </tbody>
    </table>
    <hr>
</div>
<script>
    $(function() {
        $('#dbs').DataTable();
    });
    var deletesite  =   function(id){
        if(confirm('are you sure to delete this one?')){
            location.href   =   '<?php echo $this->createUrl('site/deletesite',array('type'=>'delete'));?>&id='+id;
        }
    };
</script>
