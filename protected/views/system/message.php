<?php
include_once dirname(dirname(__FILE__)).'/sidebar.php';
?>
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Messages</strong> </div>
    </div>
<div class="am-panel am-panel-default am-u-sm-9">
    <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-2'}">Messages<span class="am-icon-chevron-down am-fr" ></span><span style="float: right"><a href="<?php echo $this->createUrl('system/addmessage');?>">Add Message</a></span>
    </div>
    <div id="collapse-panel-2" class="am-in">
        <table class="am-table am-table-bd am-table-bdrs am-table-striped am-table-hover">
            <tbody>
            <?php if(!empty($messages)){
                foreach($messages as $message){?>
                <tr id="content_<?php echo $message['msg']['id'];?>" onclick="getmsg(<?php echo $message['msg']['id'];?>)">
                    <td>From:<?php echo $message['fromuser']['company'];?></td>
                    <td style="font-size: 1.3rem;"><p><?php echo $message['msg']['title']?></p></td>
                    <td style="font-size: 1.3rem;"><?php echo date('Y-m-d',strtotime($message['msg']['time']));?></td>
                    <td><?php if(1 == $message['status'])echo "<a class='am-badge am-badge-success'>Read</a>";
                        else echo "<a class='am-badge am-badge-warning'>Unread</a>";?> </td>
                </tr>
            <?php if(!empty($msg)){
                        if($msg['id'] == $message['msg']['id']){
                            echo '<div class="am-g error-log"><div class="am-u-sm-12 am-u-sm-centered">';
                            echo "<pre class='am-pre-scrollable'>{$msg['content']}</pre>";
                            if(1 == $msg['type']){
                                $url = $this->createUrl('offer/jumptask');
                                echo "<a href='$url'>More</button>";
                            }
                            echo '</div></div>';
                        }
                    }}}else{ ?>
                <tr><td>Here is no message!</td></tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
</div>
<script>
    var getmsg = function(msg_id){
        window.location.href="<?php echo $this->createUrl('system/message');?>&msg_id="+msg_id;
    }
</script>
<!-- content end -->
<a href="#" class="am-icon-btn am-icon-th-list am-show-sm-only admin-menu" data-am-offcanvas="{target: '#admin-offcanvas'}"></a>