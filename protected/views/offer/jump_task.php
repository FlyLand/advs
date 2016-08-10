<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/24
 * Time: 15:09
 */
include_once dirname(dirname(__FILE__)).'/sidebar.php';
?>
<link rel="stylesheet" href="assets/css/amazeui.datatables.css"/>
<script src="assets/js/amazeui.datatables.min.js"></script>

<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf">
            <strong class="am-text-primary am-text-lg"><a href="<?php echo $this->createUrl('offer/list')?>">Offer Jump Task</a></strong>
        </div>
    </div>
    <table class="am-table am-table-striped am-table-bordered am-table-compact" id="example">
        <thead>
        <tr>
            <th>ID</th>
            <th>Affiliate ID</th>
            <th>Running Url</th>
            <th>Original Url</th>
            <th>Content</th>
            <th>Applicant</th>
            <th>Apply Date</th>
            <th>Auditor</th>
            <th>Audit Date</th>
            <th>Status</th>
            <th>Operation</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($tasks)){
            foreach($tasks as $task){ ?>
                <tr class="odd gradeX">
                    <td><?php echo $task['id'];?></td>
                    <td><?php echo $task['affid'];?></td>
                    <td><?php echo $task['now_url'];?></td>
                    <td><?php echo $task['back_url'];?></td>
                    <td><a class='am-badge am-badge-success' href="javascript:viewContent('<?php echo $task['content'];?>')">View</a></td>
                    <td><?php echo $task['applicant']['title'];?></td>
                    <td><?php echo $task['createtime'];?></td>
                    <td><?php echo $task['audit']['title'];?></td>
                    <td><?php echo $task['audit_date'];?></td>
                    <td><?php if(0 == $task['jump_status']){echo "<a class='am-badge am-badge-warning'>Processing</a>";
                        }else echo "<a class='am-badge am-badge-success'>Complete</a>";?></td>
                    <td>
                        <?php if(in_array($this->user['groupid'],array(ADMIN_GROUP_ID,AM_GROUP_ID)) && 0 == $task['jump_status']){ ?>
                            <a href="javascript:operation('<?php echo $task['id'];?>','audit','<?php echo $task['task_type']?>')" class="am-btn am-btn-default am-btn-xs am-text-secondary"><span class="am-icon-pencil-square-o"></span> Audit</a>
			<?php }if(in_array($this->user['groupid'],array(ADMIN_GROUP_ID,AM_GROUP_ID))){ ?>
                            <a href="javascript:operation('<?php echo $task['id'];?>','dl')" class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only"><span class="am-icon-trash-o"></span> Delete</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php }} ?>
        </tbody>
    </table>
</div>

<div class="am-modal am-modal-alert" tabindex="-1" id="my-alert">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">Content</div>
        <div class="am-modal-bd" id="alertContent">

        </div>
        <div class="am-modal-footer">
            <span class="am-modal-btn">OK</span>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('#example').DataTable();
    });

    function viewContent(content){
        $("#alertContent").html(content);
        $("#my-alert").modal();
    }

    function operation(id,type,task_type){
        var running_url = '';
        if(1 == task_type) {
            running_url = prompt('Please Enter The Original Url:');
        }else if(0 == task_type){
            running_url = prompt('Please Enter The Running Offer ID:');
        }else if(2 == task_type){
            running_url = prompt('Please Enter The Affiliate ID:');
        }
        if(confirm('Are you sure to audit this task?')){
            window.location.href = '<?php echo $this->createUrl('offer/jumptask')?>&taskid=' + id + '&type='+type + '&running_url=' + running_url;
        }
    }
</script>
