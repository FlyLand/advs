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
</style>
<?php
include_once dirname(dirname(__FILE__)).'/sidebar.php';
?>
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Affiliate Report</strong></div>
    </div>
    <div class="am-g">
        <div class="am-u-sm-12 am-u-md-12">
        </div>
    </div>
    <div class="am-g">
        <div class="am-u-sm-12">
            <form class="am-form">
                <table class="am-table am-table-striped am-table-hover table-main">
                    <thead>
                    <tr>
                            <th class="table-title">OfferId</th>
                            <th class="table-title">Offer Name</th>
                            <th class="table-title">Payout</th>
                            <th class="table-title">Price</th>
                            <th class="table-title">Clicks</th>
                            <th class="table-title">Convertions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(!empty($data)) {
                        foreach ($data as $value) {
                            ?>
                            <tr>
                                <td><a href="<?php echo $this->createUrl('offer/offerdetail',array('offer_id'=>$value['offerid']));?>"><?php echo $value['offerid'];?></a></td>
                                <td><?php echo $value['name']; ?></td>
                                <td><?php echo empty($value['sys_payout']) ? 0 : $value['sys_payout']; ?></td>
                                <td><?php echo empty($value['payout']) ? 0 : $value['payout']; ?></td>
                                <td><?php echo empty($value['sum_click']) ? 0 : $value['sum_click']; ?></td>
                                <td><?php echo empty($value['sum_con']) ? 0 : $value['sum_con']; ?></td>
                            <?php } ?>
                            </tr>
                        <?php
                    }else { ?>
                        <td>no data</td>
                    <?php } ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>
