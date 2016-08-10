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
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">statistics</strong></div>
    </div>
    <div class="am-g">
        <div class="am-u-sm-12 am-u-md-12">
            <form class="am-form am-form-horizontal" action="<?php echo $this->createUrl('offer/statistics',array('type'=>'search'));?>" method="post" id="findAdver">
                <div class="am-input-group am-input-group-sm">
                    <div class="am-form-group">
                        <label for="offer_id" style="text-align: left" class="am-u-sm-3 am-form-label">Offer id:</label>
                        <div class="am-u-sm-6 am-u-end">
                        </div>
                    </div>

<!--                    <div class="am-form-group">-->
<!--                        <label for="name" style="text-align: left" class="am-u-sm-3 am-form-label">Offer's name</label>-->
<!--                        <div class="am-u-sm-6 am-u-end">-->
<!--                            <input type="text" class="am-form-field" name="name" value="--><?php //echo $offer['name']?><!--" placeholder="offer's name">-->
<!--                        </div>-->
<!--                    </div>-->
                    <div style="float: left">
                        <button class="am-btn am-btn-primary am-btn-sm" type="button" onclick="$('#findAdver').submit();">Search</button>
                    </div>
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
                            <th class="table-title">Offer Name</th>
                            <th class="table-title">Payout</th>
                            <th class="table-title">Offer Url</th>
                            <th class="table-title">Revenue</th>
                            <th class="table-title">Type</th>
                            <th class="table-title">PlatFrom</th>
                            <th class="table-title">Countries</th>
                            <th class="table-title">Description</th>
                            <th class="table-title">Change Time</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(!empty($offers)) {
                        foreach ($offers as $offer) {
                            ?>
                            <tr>
                                    <td><?php echo $offer['offer_id']; ?></td>
                                    <td>
                                        <a href="<?php echo $this->createUrl('offer/offerdetail', array('offer_id' => $offer['offer_id'])) ?>"><?php echo $offer['name']; ?></a>
                                    </td>
                                    <td><?php echo $offer['payout']; ?></td>
                                    <td><?php echo $offer['offer_url'];?></td>
                                    <td><?php echo $offer['revenue'] ?></td>
                                    <td><?php echo $offer['type'] ?></td>
                                    <td><?php echo $offer['platform'] ?></td>
                                    <td><?php echo $offer['geo_targeting'] ?></td>
                                    <td><?php echo $offer['description'] ?></td>
                                    <td><?php echo $offer['time'] ?></td>
                        <?php }
                    }else { ?>
                        <td>no data</td>
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
