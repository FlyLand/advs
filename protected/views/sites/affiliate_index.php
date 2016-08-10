<?php require_once dirname(dirname(__FILE__)) . '/sidebar.php';?>
<link rel="stylesheet" href="assets/css/amazeui.datatables.css"/>
<script src="assets/js/amazeui.datatables.min.js"></script>
<style>
    div{
        padding-top: 10px;
    }
    .am-form label{
        color: red;
    }
</style>
<!-- content start -->
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Affiliates</strong> </div>
    </div>
    <div class="am-g">
        <div class="am-u-sm-12 am-u-md-6">
            <div class="am-btn-toolbar">
                <div class="am-btn-group am-btn-group-xs">
                </div>
            </div>
        </div>
    </div>
    <table class="am-table am-table-striped am-table-bordered am-table-compact" id="dbs">
        <thead>
        <tr>
            <td >Title</td>
            <td >Status</td>
            <td></td>
        </tr>
        </thead>
        <tbody>
        <?php
        if(!empty($rel)){
            foreach ($rel as $site){?>
                <tr id="<?php echo $site['id']?>">
                    <td><?php echo $site['title'];?></td>
                    <td><?php echo (1 == $site['status']) ? 'active': 'pending';?></td>
                    <td>
                        <div class="am-btn-toolbar">
                            <div class="am-btn-group am-btn-group-xs">
                                <a href="<?php echo $this->createUrl('affiliates/edit',array('id'=>$site['id']))?>" class="am-btn am-btn-default am-btn-xs am-text-secondary" ><span class="am-icon-pencil-square-o"></span> Edit</a>
                                <button onclick="deletesite(<?php echo $site['id']?>)" class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only" ><span class="am-icon-trash-o"></span> Delete</button>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php }}?>
        </tbody>
    </table>
    <hr>

    <form id="createAff" action="<?php echo $this->createUrl('affiliates/createaff');?>" method="post">
        <div class="am-u-md-12">
            <div class="am-panel am-panel-default">
                <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-2'}">Affiliates<span class="am-icon-chevron-down am-fr" ></span></div>
                <div id="collapse-panel-2" class="am-panel-bd am-collapse am-in">
                    <div class="am-g">
                        <div class="am-u-sm-12">
                            <div class="am-u-sm-5 am-u-md-5 am-text-right">Title:</div>
                            <div  class="element am-u-sm-3 am-u-end">
                                <input type="text"  name="title" id="title" class="am-form-field" value="">
                            </div>
                        </div>

                        <div class="am-u-sm-12">
                            <div class="am-u-sm-5 am-u-md-5 am-text-right">password:</div>
                            <div  class="element am-u-sm-3 am-u-end">
                                <input type="text"  name="password" id="password" class="am-form-field" value="">
                            </div>
                        </div>
                        <div class="am-u-sm-12">
                            <div class="am-u-sm-5 am-u-md-5 am-text-right">
                                <button type="button" onclick="createAff()" class="am-btn-m am-btn-primary">Create</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $(function() {
        $('#dbs').DataTable();
    });
    var deletesite  =   function(id){
        if(confirm('are you sure to delete this one?')){
            location.href   =   '<?php echo $this->createUrl('site/dl',array('type'=>'delete'));?>&id='+id;
        }
    };
    var createAff = function () {
        if(confirm('Are you sure to add the affiliate?')){
            $('#createAff').submit();
        }
    }
</script>
