<?php
include_once dirname(dirname(__FILE__)) . '/sidebar.php';
?>
<style type="text/css">
    .pagination {
        display: inline-block;
        padding-left: 0;
        margin: 0;
        border-radius: 4px;
        font-size:14px;
    }
    .pagination>li {
        display: inline;
    }
    .pagination>.active>a, .pagination>.active>span, .pagination>.active>a:hover, .pagination>.active>span:hover, .pagination>.active>a:focus, .pagination>.active>span:focus {
        z-index: 2;
        color: #fff;
        cursor: pointer;
        background-color: #428bca;
        border-color: #428bca;
    }
    .pagination>li>a, .pagination>li>span {
        position: relative;
        float: left;
        padding: 2px 10px;
        margin-left: -1px;
        line-height: 1.428571429;
        text-decoration: none;
        background-color: #fff;
        border: 1px solid #ddd;
    }
    td > a:hover{
        cursor:pointer;
    }
</style>
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Create white list</strong></div>
    </div>
    <div class="am-g">
        <div class="am-u-sm-12 am-u-md-6">
            <div class="am-btn-toolbar">
                <div class="am-btn-group am-btn-group-xs">
                    <button type="button" class="am-btn am-btn-default" onclick="location.href='<?php echo $this->createUrl('affiliateapi/addwhite');?>';return false;"><span class="am-icon-plus"></span> Add Affilite to white IP</button>
                    <button type="button" class="am-btn am-btn-default" onclick="location.href='<?php echo $this->createUrl('affiliates/create');?>';return false;"><span class="am-icon-plus"></span> Create Affiliate</button>
<!--                     <button type="button" class="am-btn am-btn-default"><span class="am-icon-trash-o"></span> É¾³ý</button>-->
                </div>
            </div>
        </div>

        <div class="am-u-sm-12 am-u-md-3">
            <form class="am-form" action="<?php echo $this->createUrl('affiliateapi/tokenlist',array('type'=>'search'));?>" method="post" id="findTok">
                <div class="am-input-group am-input-group-sm">
                    <input type="text" class="am-form-field" name="title" value="<?php echo $title?>" placeholder="affiliate title">
					<span class="am-input-group-btn">
 						<a class="am-btn am-btn-default" type="button" onclick="$('#findTok').submit();">search</a>
					</span>
                </div>
            </form>
        </div>
    </div>

    <div class="am-g">
        <div class="am-u-sm-12">
            <form class="am-form">
                <table class="am-table am-table-striped am-table-hover table-main">
                    <thead>
                    <tr>
                        <th class="table-title">ID</th>
                        <th class="table-title">Affiliate</th>
                        <th class="table-title">Ip</th>
                        <th class="table-title">Token</th>
                        <th class="table-title">Status</th>
                        <th class="table-title">create_time</th>
                        <th class="table-title">last_login_time</th>
                        <th class="table-title"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(!empty($whiteList)){
                        foreach ($whiteList as $white){?>
                            <tr>
                                <td><?php echo $white['id']?></td>
                                <td><a href="<?php echo $this->createUrl('affiliates/edit',array('offer_id'=>$white['affiliate']['id']))?>"><?php echo $white['affiliate']['title']?></a></td>
                                <td><?php echo $white['context'];?></td>
                                <td><?php echo $white['token'];?></td>
                                <td><?php if($white['status']==1){?>
                                    <a  onclick="chage_status()" target="_blank">Open</a></td>
                                <?php }else{ ?>
                                     <a onclick="chage_status()" target="_blank">Closed</a></td>
                                <?php } ?>
                                <td><?php echo $white['create_time'];?></td>
                                <td><?php echo $white['last_login_time'];?></td>
                                <td><a class="am-btn am-btn-primary am-btn-xs" onclick="deletewhite(<?php echo $white['id']?>)">Delete</a></td>
                            </tr>
                        <?php }
                    }else{ ?>
                        <td>no data!</td>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="am-cf"> All counts: <?php echo $count?>
                    <div class="am-fr">
                        <?php echo isset($fenyecode) ? $fenyecode : '';?>
                    </div>
                </div>
                <hr>
            </form>
        </div>
    </div>
</div>
<script>
    var deletewhite =  function(id){
        if(confirm("are you sure to delete the data?")) {
            var url = '<?php echo $this->createUrl('affiliateapi/deletewhite');?>&id='+id;
            location.href = url;
        }
    }

    var chage_status    =   function(){
        var status  =   <?php echo $white['status']?>;
        str =   'are you sure to ';
        if(status   ==  1){
            str +=   'close this whitelist?'
        }else{
            str +=   'open this whitelist?'
        }
        if(confirm(str)){
            var url   =   '<?php echo $this->createUrl('affiliateapi/deletewhite',array('id'=>$white['id'],'status'=>$white['status'],'type'=>'change'));?>&';
            location.href   =   url;
        }
    }


</script>