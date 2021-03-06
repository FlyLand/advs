<link href="<?php echo Yii::app()->params['cssPath']?>css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo Yii::app()->params['cssPath']?>css/animate.min.css" rel="stylesheet">
<link href="<?php echo Yii::app()->params['cssPath']?>css/style.min862f.css?v=4.1.0" rel="stylesheet">
<script>
    $(document).ready(function(){$(".dataTables-example").dataTable();var oTable=$("#editable").dataTable();oTable.$("td").editable("http://www.zi-han.net/theme/example_ajax.php",{"callback":function(sValue,y){var aPos=oTable.fnGetPosition(this);oTable.fnUpdate(sValue,aPos[0],aPos[1])},"submitdata":function(value,settings){return{"row_id":this.parentNode.getAttribute("id"),"column":oTable.fnGetPosition(this)[2]}},"width":"90%","height":"100%"})});function fnClickAddRow(){$("#editable").dataTable().fnAddData(["Custom row","New row","New row","New row","New row"])};
</script>
<script type="text/javascript" src="http://tajs.qq.com/stats?sId=9051096" charset="UTF-8"></script>

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
                    <th >ID</th>
                    <th >Offer Id</th>
                    <th >Affiliate Id</th>
                    <th >Offer Url</th>
                    <th >Countries</th>
                    <th >Status</th>
                    <th >Create Time</th>
                    <th >Create Name</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if(!empty($jumps)){
                    foreach ($jumps as $jump){?>
                        <tr>
                            <td><?php echo $jump['id']?></td>
                            <td><?php echo $jump['offerid'];?></td>
                            <td><?php echo $jump['affid'];?></td>
                            <td><?php echo $jump['offer_url']; ?></td>
                            <td><?php echo $jump['countries'];?></td>
                            <td><?php if(0 == $jump['status']){
                                    echo "<a class='am-badge am-badge-warning'>Closed</a>";
                                }else{
                                    echo "<a class='am-badge am-badge-success'>Open</a>";
                                }?></td>
                            <td><?php echo $jump['time'];?></td>
                            <td><?php echo $jump['users']['company'];?></td>
                            <td>
                                <div class="am-btn-toolbar">
                                    <div class="am-btn-group am-btn-group-xs">
                                        <a href="javascript:deletejump(0,<?php echo $jump['id'] . ',' . $jump['status'];?>)" class="am-btn am-btn-default am-btn-xs am-text-success am-hide-sm-only" ><span class="am-icon-close"></span> Close</a>
                                        <a href="javascript:deletejump(1,<?php echo $jump['id'].',' . $jump['status'];?>)" class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only" ><span class="am-icon-trash-o"></span> Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php }
                }else{ ?>
                    <td>no data!</td>
                <?php } ?>
                </tbody>
            </table>
            <?php $this->widget('CLinkPager',array(
                    'header'=>'',
                    'firstPageLabel' => 'firstPage',
                    'lastPageLabel' => 'endPage',
                    'prevPageLabel' => '<<',
                    'nextPageLabel' => '>>',
                    'pages' => $pages,
                    'maxButtonCount'=>8,
                )
            );?>
        </div>
    </div>
</div>

<?php
if( !empty($js_msg) ){
    echo '<script>alert("', $js_msg , '");';
    echo '</script>';
}
?>
</div>
<script>
    function deletejump(type,id,status){
        var url = "<?php echo $this->createUrl('offer/jumpoffer')?>";
        var msg,type_value;
        if(type == 1){
            msg = "Are you sure to delete it?";
            type_value = 'delete';
        }else if(type == 0){
            msg = "Are you sure to close it?";
            type_value = 'close';
        }
        if(window.confirm(msg)){
            window.location.href = url + '&type=' + type_value + '&id=' + id + '&status=' + status;
        }
    }
</script>