<link href="<?php echo Yii::app()->params['cssPath']?>css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo Yii::app()->params['cssPath']?>css/animate.min.css" rel="stylesheet">
<link href="<?php echo Yii::app()->params['cssPath']?>css/style.min862f.css?v=4.1.0" rel="stylesheet">
<script>
    $(document).ready(function(){$(".dataTables-example").dataTable();var oTable=$("#editable").dataTable();oTable.$("td").editable("http://www.zi-han.net/theme/example_ajax.php",{"callback":function(sValue,y){var aPos=oTable.fnGetPosition(this);oTable.fnUpdate(sValue,aPos[0],aPos[1])},"submitdata":function(value,settings){return{"row_id":this.parentNode.getAttribute("id"),"column":oTable.fnGetPosition(this)[2]}},"width":"90%","height":"100%"})});function fnClickAddRow(){$("#editable").dataTable().fnAddData(["Custom row","New row","New row","New row","New row"])};
</script>
<script type="text/javascript" src="http://tajs.qq.com/stats?sId=9051096" charset="UTF-8"></script>

<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>基本 <small>分类，查找</small></h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="table_data_tables.html#">
                        <i class="fa fa-wrench"></i>
                    </a>
                    <a class="close-link">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <table class="table table-striped table-bordered table-hover dataTables-example">
                    <thead>
                    <tr>
                        <th >id</th>
                        <th >Affiliates</th>
                        <th >Status</th>
                        <!--<th 2">Activity Fraud</th> -->
                        <th >Offers</th>
                        <?php if(in_array($this->user['groupid'],array(ADMIN_GROUP_ID,AM_GROUP_ID,MANAGER_GROUP_ID))){ ?>
                            <td>Cutnum</td>
                        <?php } ?>
                        <th >Clicks</th>
                        <th >Conversions</th>
                        <th >Cost</th>
                        <th >Revenue</th>
                        <th >Profit</th>
                        <th>Manager</th>
                        <th></th>
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
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <button class="btn btn-primary" onclick="savecut()" type="button">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
