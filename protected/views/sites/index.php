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
                        <th >Company</th>
                        <th >Email</th>
                        <th >Status</th>
                        <th >Affiliates</th>
                        <th >AM</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(!empty($sites)){
                        foreach ($sites as $site){?>
                            <tr id="<?php echo $site['id']?>" class="gradeX">
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
            </div>
        </div>
    </div>
</div>
<script>
    var deletesite  =   function(id){
        if(confirm('are you sure to delete this one?')){
            location.href   =   '<?php echo $this->createUrl('site/deletesite',array('type'=>'delete'));?>&id='+id;
        }
    };
</script>
