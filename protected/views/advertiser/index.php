<script src="<?php echo Yii::app()->params['cssPath']?>js/jquery.min.js?v=2.1.4"></script>
<script src="<?php echo Yii::app()->params['cssPath']?>js/bootstrap.min.js?v=3.3.6"></script>
<script src="<?php echo Yii::app()->params['cssPath']?>js/plugins/jeditable/jquery.jeditable.js"></script>
<script src="<?php echo Yii::app()->params['cssPath']?>js/plugins/dataTables/jquery.dataTables.js"></script>
<script src="<?php echo Yii::app()->params['cssPath']?>js/plugins/dataTables/dataTables.bootstrap.js"></script>
<script src="<?php echo Yii::app()->params['cssPath']?>js/content.min.js?v=1.0.0"></script>
<script>
    $(document).ready(function(){$(".dataTables-example").dataTable();var oTable=$("#editable").dataTable();oTable.$("td").editable("http://www.zi-han.net/theme/example_ajax.php",{"callback":function(sValue,y){var aPos=oTable.fnGetPosition(this);oTable.fnUpdate(sValue,aPos[0],aPos[1])},"submitdata":function(value,settings){return{"row_id":this.parentNode.getAttribute("id"),"column":oTable.fnGetPosition(this)[2]}},"width":"90%","height":"100%"})});function fnClickAddRow(){$("#editable").dataTable().fnAddData(["Custom row","New row","New row","New row","New row"])};
</script>
<script type="text/javascript" src="http://tajs.qq.com/stats?sId=9051096" charset="UTF-8"></script>

<!-- Data Tables -->
<link href="<?php echo Yii::app()->params['cssPath']?>css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">

<link href="<?php echo Yii::app()->params['cssPath']?>css/animate.min.css" rel="stylesheet">
<link href="<?php echo Yii::app()->params['cssPath']?>css/style.min862f.css?v=4.1.0" rel="stylesheet">

<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Advertiser <small></small></h5>
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
                        <th>id</th>
                        <th>Advertiser</th>
                        <th>Status</th>
                        <th>Affiliates</th>
                        <th>Conversions</th>
                        <th>Cost</th>
                        <th>Revenue</th>
                        <th>Profit</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($advertisers as $advertiser){?>
                        <tr class="gradeX">
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
                                        <a href="<?php echo $this->createUrl('advertiser/edit',array('id'=>$advertiser['id']))?>" class="btn btn-outline btn-success" ><span class="am-icon-pencil-square-o"></span> Edit</a>
                                        <button onclick="deleteuser(<?php echo $advertiser['id'];?>)" class="btn btn-outline btn-danger" ><span class="am-icon-trash-o"></span> Delete</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php }?>
                    </tbody>
                </table>
            </div>
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