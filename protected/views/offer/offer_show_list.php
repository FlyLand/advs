<?php require_once dirname(dirname(__FILE__)) . '/sidebar.php';?>
<link rel="stylesheet" href="assets/css/amazeui.datatables.css"/>
<script src="assets/js/amazeui.datatables.min.js"></script>
<link rel="stylesheet" href="assets/css/amazeui.datatables.css"/>
<script src="assets/js/amazeui.datatables.min.js"></script>
<script type="text/javascript" src="./assets/js/pdfobject.js"></script>
<!-- content start -->
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf">
            <strong class="am-text-primary am-text-lg">List</a></strong>
        </div>
    </div>
    <div class="am-u-sm-12 am-u-md-6">
        <div class="am-btn-toolbar">
            <div class="am-btn-group am-btn-group-xs">
                <button type="button" class="am-btn am-btn-default" onclick="location.href='/phps_new/offermgr_new/index.php?r=offer/offershow';return false;"><span class="am-icon-plus"></span> Create</button>
                 <button type="button" style="padding-left: 20px;margin-left: 20px" onclick="javascript:location.href='/phps_new/offermgr_new/index.php?r=offer/offershow&type=dlpage'" class="am-btn am-btn-default"><span class="am-icon-trash-o"></span> Edit</button>
            </div>
        </div>
    </div>
    <div class="am-u-3">
    </div>
    <table class="am-table am-table-striped am-table-bordered am-table-compact" id="show_list">
        <thead>
        <tr>
            <?php
                echo '<td>ID</td>
                    <td>Affiliate ID</td>
                   <td>Offer ID</td>
                    <td>Cut Num</td>
                    <td>Payout</td>
                    <td>Adv Id</td>
                    <td>Show</td> ';
            ?>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($list)){
            foreach($list as $item){
                echo "<tr>";
                echo "<td>{$item['id']}</td>";
                echo "<td>{$item['aff_id']}</td>";
                echo "<td>{$item['offer_id']}</td>";
                echo "<td>{$item['cut_num']}</td>";
                echo "<td>{$item['payout']}</td>";
                echo "<td>{$item['advid']}</td>";
                if($item['isshow'] == 1){
                    $status = '<a class="am-badge am-badge-success">Show</a>';
                }else{
                    $status = '<a class="am-badge am-badge-warning">Pending</a>';
                }
                echo "<td>$status</td>";
            }
        }else echo '';
        echo "</tr>";?>
        </tbody>
    </table>
</div>
<script>
    $(function() {
        $('#show_list').DataTable();
    });
</script>
