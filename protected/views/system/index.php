<?php
include_once dirname(dirname(__FILE__)).'/sidebar.php';
?>

<!-- content start -->
	<div class="admin-content">
		<div class="am-cf am-padding">
			<div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">User Info</strong> </div>
		</div>
		<form class="am-form">
			<div class="am-tabs am-margin" data-am-tabs>
				<ul class="am-tabs-nav am-nav am-nav-tabs">
					<li class="am-active"><a href="#tab1">User Info</a></li>
				</ul>
				<div class="am-tabs-bd">
					<div class="am-tab-panel am-fade am-in am-active" id="tab1">
						<div class="am-g am-margin-top">
							<div class="am-u-sm-4 am-u-md-2 am-text-right">User Name / Email</div>
							<div class="am-u-sm-8 am-u-md-4">
								<?php echo $data['email']; ?>
							</div>
							<div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
					 	</div>
						<div class="am-g am-margin-top">
							<div class="am-u-sm-4 am-u-md-2 am-text-right">Company</div>
							<div class="am-u-sm-8 am-u-md-4">
								<?php echo $data['company']; ?>
							</div>
							<div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
					 	</div>
					 	<div class="am-g am-margin-top">
							<div class="am-u-sm-4 am-u-md-2 am-text-right">Last Login Time</div>
							<div class="am-u-sm-8 am-u-md-4">
								<?php echo $data['lastlogin']; ?>
							</div>
							<div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
					 	</div>
					 	<div class="am-g am-margin-top">
							<div class="am-u-sm-4 am-u-md-2 am-text-right">Last Login IPAddress</div>
							<div class="am-u-sm-8 am-u-md-4">
								<?php echo $data['loginip']; ?>
							</div>
							<div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
					 	</div>
					 	<div class="am-g am-margin-top">
							<div class="am-u-sm-4 am-u-md-2 am-text-right">Login Times</div>
							<div class="am-u-sm-8 am-u-md-4">
								<?php echo $data['logincount']; ?>
							</div>
							<div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
					 	</div>
					 	<div class="am-g am-margin-top">
							<div class="am-u-sm-4 am-u-md-2 am-text-right">The User Status</div>
							<div class="am-u-sm-8 am-u-md-4">
								<?php
									if(1 == $data['status']){
										echo 'Normal';
									}elseif(2 == $data['status']){
										echo 'Deleted';
									}else{
										echo 'Authstr';
									}
								?>
							</div>
							<div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
					 	</div>
					 	<div class="am-g am-margin-top">
							<div class="am-u-sm-4 am-u-md-2 am-text-right">Create Time</div>
							<div class="am-u-sm-8 am-u-md-4">
								<?php echo $data['createtime']; ?>
							</div>
							<div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
					 	</div>
					 	<div class="am-g am-margin-top">
							<div class="am-u-sm-4 am-u-md-2 am-text-right">Tel</div>
							<div class="am-u-sm-8 am-u-md-4">
								<?php echo $data['phone']; ?>
							</div>
							<div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
					 	</div>
					 	<div class="am-g am-margin-top">
							<div class="am-u-sm-4 am-u-md-2 am-text-right">Zipcode</div>
							<div class="am-u-sm-8 am-u-md-4">
								<?php echo $data['zipcode']; ?>
							</div>
							<div class="am-hide-sm-only am-u-md-6">&nbsp;&nbsp;</div>
					 	</div>
					</div>
				</div>
			</div>
		</form>
	</div>
<!-- content end -->
