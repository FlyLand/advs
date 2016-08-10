<?php
/**
 * pixels管理列表
 */
include_once dirname(dirname(__FILE__)).'/sidebar.php';
?>
<style>
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
	      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Conversion Pixels / URLs</strong></div>
	    </div>

    	<div class="am-g">
      		<div class="am-u-sm-12">
        		<form class="am-form">
          		<table class="am-table am-table-striped am-table-hover table-main">
            		<thead>
              			<tr>
                			<th class="table-title">ID</th>
                			<th class="table-title">Offer</th>
                			<th class="table-title">Affiliate</th>
                			<th class="table-title">Advertiser</th>
                			<th class="table-title">Type</th>
                			<th class="table-title">Status</th>
                			<th class="table-title">Test</th>
                			<th class="table-title">Code</th>
                			<th class="table-set">操作</th> 
              			</tr>
          			</thead>
          			<tbody>
          			<?php foreach ($pixels as $pixel){
          			    $offer = joy_offers::model()->findByAttributes(array('id'=>$pixel['offerid']));
          			    $affiliate = JoySystemUser::model()->findByPk($pixel['affid']);
						$advertiser	=	JoySystemUser::model()->findByPk($pixel['advid']);
          			    ?>
          			     <tr>
          				    <td><?php echo $pixel['id']?></td>
          				    <td><a href="<?php echo $this->createUrl('offer/offerdetail',array('offer_id'=>$offer['id']))?>"><?php echo $offer['name']?></a></td>
                			<td><a href="<?php echo $this->createUrl('affiliates/edit',array('id'=>$affiliate['id']));?>"><?php echo $affiliate['company'];?></a></td>
                			<td><?php echo $advertiser['title']?></td>
							 <td><?php echo $pixel['type']?></td>
                			<td><?php 
                			if($offer['status'] == 0){
                			    echo 'Pending';
                			}elseif ($offer['status'] == 1){
                			    echo 'Active';
                			}elseif ($offer['status'] == 2){
                			    echo 'Deleted';
                			}
                			?></td>
                			<td><a href="<?php echo $this->createUrl('pixels/urltest',array('pixelsId'=>$pixel['id']))?>">Test</a></td>
                			<td><a href="" data-am-modal="{target: '#doc-modal-<?php echo $pixel['id']?>', closeViaDimmer: 0, width: 400, height: 56}">
									<?php echo empty($pixel['code']) ? $pixel['advertiser']['postback'] : $pixel['code'];?>
								</a></td>
                			<td>
								<div class="am-btn-toolbar">
									<div class="am-btn-group am-btn-group-xs">
										<a href="<?php echo $this->createUrl('pixels/pixelsedit',array('id'=>$pixel['id']))?>" class="am-btn am-btn-default am-btn-xs am-text-secondary" ><span class="am-icon-pencil-square-o"></span> Edit</a>
										<a href="<?php echo $this->createUrl('pixels/pixelsdelete',array('id'=>$pixel['id']));?>" class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only" ><span class="am-icon-trash-o"></span> Delete</a>
									</div>
								</div>
							</td>
          				 
          				 </tr>
          			<?php }?>

							 
				   </tbody>
				</table>
				<div class="am-cf"> 共 <?php echo $count?> 条记录
					<div class="am-fr">
						<?php echo isset($fenyecode) ? $fenyecode : ''; ?>
					</div>
				</div>
				<hr>
				</form>
			</div>
		</div>
	</div>
<?php 
foreach ($pixels as $pixel){
    $affiliate = JoySystemUser::model()->findByPk($pixel['affid']);?>

    <div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-<?php echo $pixel['id']?>">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd">
      <?php echo $affiliate['postback']?>
    </div>
  </div>
</div> 
    <?php }
?>	
