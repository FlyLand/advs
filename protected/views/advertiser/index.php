<?php require_once dirname(dirname(__FILE__)) . '/sidebar.php';?>
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
<!-- content start -->
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Advertiser</strong> </div>
    </div>
    <div class="am-g">
        <div class="am-u-sm-12 am-u-md-6">
            <div class="am-btn-toolbar">
                <div class="am-btn-group am-btn-group-xs">
                    <button type="button" class="am-btn am-btn-default" onclick="location.href='<?php echo $this->createUrl('advertiser/create');?>';return false;"><span class="am-icon-plus"></span> Add Advertiser</button>
                    <!--                     <button type="button" class="am-btn am-btn-default"><span class="am-icon-trash-o"></span> É¾³ý</button>-->
                </div>
            </div>
        </div>
    </div>
    <div class="am-panel am-panel-default">
        <div class="am-panel-bd">
            <table class="am-table am-table-striped am-table-hover" id="dbs">
                <thead>
                    <tr>
                        <th class="minth">id</th>
                        <th class="minth">Advertiser</th>
                        <th class="minth2">Status</th>
                        <th class="minth2">Affiliates</th>
                        <th class="minth2">Conversions</th>
                        <th class="maxth">Cost</th>
                        <th class="timeth">Revenue</th>
                        <th class="minth">Profit</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach ($advertisers as $advertiser){?>
                    <tr>
                        <td><?php echo $advertiser['id']?></td>
                        <td><a href="<?php echo $this->createUrl('advertiser/edit',array('id'=>$advertiser['id']))?>"><?php echo $advertiser['company']?></a></td>
                        <td><?php echo (1 == $advertiser['status'])? 'active': 'pending';?></td>
                        <td><?php echo isset($advertiser['aff'])?$advertiser['aff']:0?></td>
                        <td><?php echo  isset($advertiser['conversions'])?$advertiser['conversions']:0?></td>
                        <td>$<?php echo $pay_num = isset($advertiser['pay_num'])?$advertiser['pay_num']:0?></td>
                        <td>$<?php echo $re_num = isset($advertiser['re_num'])?$advertiser['re_num']:0?></td>
                        <td>$<?php echo $re_num  -   $pay_num ?></td>
                        <td>
                            <div class="am-btn-toolbar">
                                <div class="am-btn-group am-btn-group-xs">
                                    <a href="<?php echo $this->createUrl('advertiser/edit',array('id'=>$advertiser['id']))?>" class="am-btn am-btn-default am-btn-xs am-text-secondary" ><span class="am-icon-pencil-square-o"></span> Edit</a>
                                    <button onclick="deleteuser(<?php echo $advertiser['id'];?>)" class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only" ><span class="am-icon-trash-o"></span> Delete</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php }?>

                </tbody>
            </table>
            <div class="am-cf"> 共 <?php echo $count?> 条记录
                <div class="am-fr">
                    <?php echo isset($fenyecode) ? $fenyecode : ''; ?>
                </div>
            </div>
            <hr>
            </div>
        </div>
    </div>
<script>
    var deleteuser  =   function(id){
        if(confirm('are you sure to delete this one?')){
            location.href   =   '<?php echo $this->createUrl('advertiser/update',array('type'=>'delete'));?>&id='+id;
        }
    }

</script>