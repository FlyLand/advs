<?php
include_once dirname(dirname(__FILE__)).'/sidebar.php';
?>
<style>
    td input{
        width: 100%;
    }
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
</style>
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf">
            <strong class="am-text-primary am-text-lg">Offer Affiliate Settings</strong>
        </div>
    </div>
<div class="am-panel am-panel-default">
    <div class="am-panel-bd">
        <table class="am-table am-table-striped am-table-hover" id="dbs">
            <thead>
            <tr>
                <th class="minth">Affiliate</th>
                <th class="minth">Default Payout</th>
                <th class="minth2">Default Revenue</th>
                <th class="minth2">Payout</th>
                <th class="minth2">Revenue</th>
                <th class="maxth">Daily Conversions</th>
                <th class="timeth">Monthly Conversions</th>
                <th class="minth">Daily Payout</th>
                <th class="minth">Monthly Payout</th>
                <th class="minth">Daily Revenue</th>
                <th class="minth">Monthly Revenue</th>
                <th class="minth"></th>
                <th class="minth"></th>
            </tr>
            </thead>
            <tbody>
            <?php if($pixels){?>
                <?php foreach($pixels as $pixel){?>
                     <tr id="<?php echo $pixel['id']?>">
                        <td><a href="<?php echo $this->createUrl('affiliates/edit',array('id'=>$pixel['affiliate']['id']))?>"><?php echo $pixel['affiliate']['company'] ?></a></td>
                        <td><?php echo $pixel['offer']['payout'] ?></td>
                        <td><?php echo $pixel['offer']['revenue'] ?></td>
                        <td><input name="offerPayout" value="<?php echo $pixel['payout'];?>"></td>
                        <td><input name="offerRevenue" value="<?php echo $pixel['revenue'];?>"></td>
                        <td><input name="conversion_cap" value="<?php echo $pixel['daily_con'];?>"></td>
                        <td><input name="monthly_conversion_cap" value="<?php echo $pixel['month_con'];?>"></td>
                        <td><input name="payout_cap" value="<?php echo $pixel['daily_pay'];?>"></td>
                        <td><input name="monthly_payout_cap" value="<?php echo $pixel['month_pay'];?>"></td>
                        <td><input name="daily_rev" value="<?php echo $pixel['daily_rev'];?>"></td>
                        <td><input name="month_rev" value="<?php echo $pixel['month_rev'];?>"></td>
                        <td><a href="<?php echo $this->createUrl('offer/deleteaccount',array('pixel_id'=>$pixel['id'],'offer_id'=>$pixel['offer']['id']));?>">Delete</a></td>
                    </tr>
                <?php }?>
            <?php }else{
                echo '<td>no data!</td>';
            }?>
            </tbody>
        </table>
        <button type="button" class="am-btn am-btn-warning am-round" onclick="save_account()">Save</button>
        <div class="am-cf"> 共 <?php echo $count?> 条记录
            <div class="am-fr">
                <?php echo isset($fenyecode) ? $fenyecode : ''; ?>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    var save_account = function () {
        if (confirm('are you sure to save these data?')) {
            var revenues = $('input[name="offerRevenue"]').get();
            var payouts = $('input[name="offerPayout"]').get();
            var conversion_caps = $('input[name="conversion_cap"]').get();
            var monthly_conversion_caps = $('input[name="monthly_conversion_cap"]').get();
            var payout_caps = $('input[name="payout_cap"]').get();
            var monthly_payout_caps = $('input[name="monthly_payout_cap"]').get();
            var daily_revs = $('input[name="daily_rev"]').get();
            var month_revs = $('input[name="month_rev"]').get();
            var length = payouts.length;
            var reg = /^[0-9]+\.{0,1}[0-9]{0,2}$/;
            for(var i=0;i<length;i++){
                if(!reg.test(payouts[i].value)){
                    payouts[i].focus;
                    alert('Payout must be a number ');
                    return false;
                }
                if(!reg.test(revenues[i].value)){
                    revenues[i].focus;
                    alert('Revenue must be a number ');
                    return false;
                }
                if(!reg.test(conversion_caps[i].value)){
                    conversion_caps[i].focus;
                    alert('conversion_caps must be a number ');
                    return false;
                }
                if(!reg.test(monthly_conversion_caps[i].value)){
                    monthly_conversion_caps[i].focus;
                    alert('monthly_conversion_caps must be a number ');
                    return false;
                }
                if(!reg.test(payout_caps[i].value)){
                    payout_caps[i].focus;
                    alert('payout_caps must be a number ');
                    return false;
                }
                if(!reg.test(monthly_payout_caps[i].value)){
                    monthly_payout_caps[i].focus;
                    alert('monthly_payout_caps must be a number ');
                    return false;
                }
                if(!reg.test(daily_revs[i].value)){
                    daily_revs[i].focus;
                    alert('daily_revs must be a number ');
                    return false;
                }
                if(!reg.test(month_revs[i].value)){
                    month_revs[i].focus;
                    alert('month_revs must be a number ');
                    return false;
                }
            }
            var i = $('#dbs tr').size();
            var str = new Array(i);
            for (var t = 0; t < i; t++) {
                str[t] = new Array(11);
                str[t][0] = $('#dbs tr').eq(t).attr('id');
                for (var s = 1; s < 9; s++) {
                    str[t][s] = $('#dbs tr').eq(t).find("td").eq(s + 2).find("input ").val();
                }
            }
            var url_post = "<?php echo $this->createUrl('offer/updateaccount');?>";
            $.ajax({
                type: 'POST',
                url: url_post,
                async: false,
                data: {data: str, type: 'all'},
                success: function (msg) {
                    alert(msg);
                    location.reload(true);
                }
            });
        };
    }
</script>