<?php
include_once dirname(dirname(__FILE__)).'/sidebar.php';
?>
<script type="text/javascript">
    function check(){
        if( !confirm('Are you sure to change your password?') ){
            return false;
        }
        return true;
    }
</script>
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Offer Types</strong> </div>
    </div>
    <div class="am-g">
        <div class="am-u-sm-12 am-u-md-6">
            <div class="am-btn-toolbar">
                <div class="am-btn-group am-btn-group-xs">
                    <button type="button" class="am-btn am-btn-default" onclick="location.href='<?php echo $this->createUrl('offer/typemanager',array('type'=>'add'));?>';return false;"><span class="am-icon-plus"></span> Add</button>
                </div>
            </div>
        </div>

        <div class="am-u-sm-12">
            <form class="am-form">
                <table class="am-table am-table-striped am-table-hover table-main">
                    <thead>
                    <tr>
                        <th class="table-title">ID</th>
                        <th class="table-title">Type Name -en</th>
                        <th class="table-title">Key words</th>
                        <th class="table-title"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(!empty($types)){
                        foreach ($types as $type){?>
                            <tr>
                                <td><?php echo $type['id']?></td>
                                <td><?php echo $type['type_name_en'];?></td>
                                <td><?php echo $type['key_words'];?></td>
                                <td>
                                    <div class="am-btn-toolbar">
                                        <div class="am-btn-group am-btn-group-xs">
                                            <a href="<?php echo $this->createUrl('offer/typemanager',array('type'=>'edit','id'=>$type['id']))?>" class="am-btn am-btn-default am-btn-xs am-text-secondary" ><span class="am-icon-pencil-square-o"></span> Edit</a>
                                            <a href="<?php echo $this->createUrl('offer/typemanager',array('type'=>'delete','id'=>$type['id']));?>" class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only" ><span class="am-icon-trash-o"></span> Delete</a>
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
if( !empty($msg) ){
    echo '<script>alert("', $msg , '");';
    if( 0 == $ret ){
        echo 'location.href="'.$this->createUrl('offer/typemanager').'";';

    }
    echo '</script>';
}
?>
</div>