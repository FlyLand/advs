<?php
include_once dirname(dirname(__FILE__)).'/sidebar.php';
?>
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Offer Jump List</strong> </div>
    </div>
    <div class="am-g">
        <div class="am-u-sm-12 am-u-md-6">
            <div class="am-btn-toolbar">
                <div class="am-btn-group am-btn-group-xs">
                    <button type="button" class="am-btn am-btn-default" onclick="location.href='<?php echo $this->createUrl('offer/jumpoffer',array('type'=>'add_jump'));?>';return false;"><span class="am-icon-plus"></span> Add</button>
                </div>
            </div>
        </div>

        <div class="am-u-sm-12">
            <form class="am-form">
                <table class="am-table am-table-striped am-table-hover table-main">
                    <thead>
                        <tr>
                            <th class="table-title">ID</th>
                            <th class="table-title">Offer Id</th>
                            <th class="table-title">Affiliate Id</th>
                            <th class="table-title">Offer Url</th>
                            <th class="table-title">Countries</th>
                            <th class="table-title">Status</th>
                            <th class="table-title">Create Time</th>
                            <th class="table-title">Create Name</th>
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
                <hr>
            </form>
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