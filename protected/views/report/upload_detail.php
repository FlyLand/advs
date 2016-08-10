<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/25
 * Time: 14:08
 */
$key = md5(date('His').$this->user['userid']);
?>
<link rel="stylesheet" href="assets/css/amazeui.datatables.css"/>
<script src="assets/js/amazeui.datatables.min.js"></script>
<!-- content start -->
<div class="admin-content">
    <table class="am-table am-table-striped am-table-bordered am-table-compact" id="dbs">
        <thead>
        <tr>
            <td >AffiliateId</td>
            <td >Date</td>
            <td >Payout</td>
            <td >Revenue</td>
            <td> Impression </td>
            <td >Project</td>
        </tr>
        </thead>
        <tbody>
        <?php
        if(!empty($data)){
            $req_data = serialize($data);
            $result = Yii::app()->cache->set($key,$req_data);
            foreach ($data as $item){
                echo "<tr>";
                echo "<td>{$item['affid']}</td>";
                echo "<td>{$item['time']}</td>";
                echo "<td>{$item['revenue']}</td>";
                echo "<td>{$item['payout']}</td>";
                echo "<td>{$item['click_count']}</td>";
                echo "<td>{$item['project_name']}</td>";
                echo "</tr>";
            }}?>
        </tbody>
    </table>
    <hr>
    <div class="am-g">
        <div class="am-u-sm-12 am-u-md-6">
            <div class="am-btn-toolbar">
                <div class="am-btn-group am-btn-group-xs">
                    <button type="button" class="am-btn am-btn-primary" onclick="location.href='<?php echo $this->createUrl('report/saveexcel',array('key'=>$key));?>';return false;"><span class="am-icon-plus"></span> Submit</button>
                    <button type="button" class="am-btn am-btn-danger" onclick="location.href='<?php echo $this->createUrl('report/excelexport');?>';return false;"> Cancel</button>
                </div>
            </div>
        </div>
    </div>
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


