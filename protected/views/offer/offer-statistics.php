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
/**
 * offer管理列表
 */
include_once dirname(dirname(__FILE__)).'/sidebar.php';

/**
 * 
 * 计算时间差
 */
function timediff($begin_time,$end_time)
{
	if($begin_time < $end_time){
		$starttime = $begin_time;
		$endtime = $end_time;
	}
	else{
		$starttime = $end_time;
		$endtime = $begin_time;
	}
	$timediff = $endtime-$starttime;
	$days = intval($timediff/86400);
	$remain = $timediff%86400;
	
	//$hours = intval($remain/3600);
	$hours = intval($timediff/3600);
	
	$remain = $remain%3600;
	$mins = intval($remain/60);
	$secs = $remain%60;
	$res = array("day" => $days,"hour" => $hours,"min" => $mins,"sec" => $secs);
	//return $res;
	
	$hour_str		=	$hours;
	if(strlen($hours) < 2){
		$hour_str	=	'0'.$hours;
	}
	$mins_str		=	$mins;
	if(strlen($mins) < 2){
		$mins_str	=	'0'.$mins;
	}
	$sesc_str		=	$secs;
	if(strlen($secs) < 2){
		$sesc_str	=	'0'.$secs;
	}
	
	return $hour_str.':'.$mins_str.':'.$sesc_str;
}
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
							<input id="offer_id" type="text" class="am-form-field" name="offer_id" value="<?php echo $offer_id?>" placeholder="offer's id">
						</div>
					</div>

					<?php if(in_array($this->user['groupid'],array('1','2','6'))){ ?>
						<div class="am-form-group">
							<label for="advertiser" style="text-align: left" class="am-u-sm-3 am-form-label">Advertiser:</label>
							<div class="am-u-sm-6 am-u-end">
								<select id="advertiser" name="advertiser" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" id="adver" style="display: none;padding-top: 20px">
									<option value="">All Advertiser</option>
									<?php foreach($advertisers as $advertiser){ ?>
										<option <?php echo
										$advertiser['select'];?> value="<?php echo $advertiser['id'];?>"><?php echo
											$advertiser['title'];?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					<?php } ?>
					<div class="am-form-group">
						<label for="name" style="text-align: left" class="am-u-sm-3 am-form-label">offer's name</label>
						<div class="am-u-sm-6 am-u-end">
							<input type="text" class="am-form-field" name="name" value="<?php echo $name?>" placeholder="offer's name">
						</div>
					</div>
					<div style="float: left">
						<button class="am-btn am-btn-primary am-btn-sm" type="button" onclick="$('#findAdver').submit();">search</button>
					</div>
				</div>
				<span class="am-input-group-btn" style="padding-top: 30px">
					<a class="am-btn am-btn-default" type="button" href="<?php echo $this->createUrl('offer/toexcel');?>">Download As Excel</a>
				</span>
			</form>
		</div>
	</div>
    <div class="am-g">
        <div class="am-u-sm-12">
            <form class="am-form">
                <table class="am-table am-table-striped am-table-hover table-main">
                    <thead>
                    <tr>
                        <?php if(in_array($this->user['groupid'], array('1','2','3'))){?>
                        <th class="table-title">ID</th>
                        <th class="table-title">Offer</th>
                        <th class="table-title">AffiliateID</th>
                        <th class="table-title">Affiliate</th>
                        <th class="table-title">AdvertiserID</th>
                        <th class="table-title">Advertiser</th>
                        <th class="table-title">Sesstion Date / Time</th>
                        <th class="table-title">Date / Time</th>
                        <th class="table-title">Date / Time Diff</th>
                        <th class="table-title">Payout</th>
                        <th class="table-title">Sesstion IP</th>
                        <th class="table-title">Conversion IP</th>
                        <th class="table-title">Transaction ID</th>
                        <?php }else{?>
                        <th class="table-title">ID</th>
                        <th class="table-title">Offer</th>
						<?php if (in_array($this->user['groupid'], array( '3'))) { ?>
							<th class="table-title">Affiliate</th>
						<?php } ?>
						<?php if (in_array($this->user['groupid'], array( '6'))) { ?>
							<th class="table-title">Advertiser</th>
						<?php }?>
                        <th class="table-title">Sesstion Date / Time</th>
                        <th class="table-title">Date / Time</th>
                        <th class="table-title">Date / Time Diff</th>
                        <th class="table-title">Payout</th>
                        <th class="table-title">Sesstion IP</th>
                        <th class="table-title">Conversion IP</th>
                        <th class="table-title">Transaction ID</th>
                        <?php }?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
					if(!empty($offers)) {
						foreach ($offers as $offer) {
							?>
							<tr>
								<?php if (in_array($this->user['groupid'], array('1', '2'))) { ?>
									<td><?php echo $offer['id'] ?></td>
									<td>
										<a href="<?php echo $this->createUrl('offer/offerdetail', array('offer_id' => $offer['offer']['id'])) ?>"><?php echo $offer['offer']['name']; ?></a>
									</td>
									<td><?php echo $offer['affid'] ?></td>
									<td>
										<a href="<?php echo $this->createUrl('affiliates/edit', array('id' => $offer['affid'])); ?>"><?php echo $offer['affiliate']['company']; ?></a>
									</td>
									<td><?php echo $offer['advid'] ?></td>
									<td>
										<a href="<?php echo $this->createUrl('advertiser/edit', array('id' => $offer['advid'])); ?>"><?php echo $offer['advertiser']['company']; ?></a>
									</td>
									<td><?php echo $offer['transactiontime'] ?></td>
									<td><?php echo $offer['createtime'] ?></td>
									<td><?php echo timediff(strtotime($offer['createtime']), strtotime($offer['transactiontime'])); ?></td>
									<td><?php echo $offer['payout'] ?></td>
									<td><?php echo $offer['serverip'] ?></td>
									<td><?php echo $offer['clientip'] ?></td>
									<td><?php echo $offer['transactionid'] ?></td>
								<?php } else { ?>
									<td><?php echo $offer['id'] ?></td>
									<td><?php echo $offer['offer']['name']; ?></td>
									<?php if (in_array($this->user['groupid'], array( '3'))) { ?>
										<td><?php echo $offer['affiliate']['company']; ?></td>
									<?php } ?>
									<?php if (in_array($this->user['groupid'], array( '6'))) { ?>
										<td><?php echo $offer['advertiser']['company']; ?></td>
									<?php } ?>
									<td><?php echo $offer['transactiontime'] ?></td>
									<td><?php echo $offer['createtime'] ?></td>
									<td><?php echo timediff(strtotime($offer['createtime']), strtotime($offer['transactiontime'])); ?></td>
									<td><?php echo $offer['payout'] ?></td>
									<td><?php echo $offer['serverip'] ?></td>
									<td><?php echo $offer['clientip'] ?></td>
									<td><?php echo $offer['transactionid'] ?></td>
								<?php } ?>
							</tr>
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
						'prevPageLabel' => 'lastPage',
						'nextPageLabel' => 'nextPage',
						'pages' => $pages,
						'maxButtonCount'=>8,
					)
				);?>
                <hr>
            </form>
        </div>
    </div>
</div>
