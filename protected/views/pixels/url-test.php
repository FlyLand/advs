<?php
/**
 * pixelsURl测试页面
 * 
 */
include_once dirname(dirname(__FILE__)).'/sidebar.php';

?>
<div class="admin-content">
    <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">pixelsURl测试</strong></div>
    </div>

    <hr>

    <div class="am-g">
      <div class="am-u-sm-12 am-u-sm-centered">
        <h2>Test Conversion Pixel / URL</h2>
        <p>To test affiliate pixels or URLs you'll need to first start a tracking session with the affiliate's third-party system. Then you can test conversion tracking.</p>
        <p><b>Step 1: </b>Select the offer to test third-party conversion tracking tracking for the selected affiliate</p>
        <hr>
        <div class="am-g am-margin-top-sm">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">
              Offer:
            </div>
            <div class="am-u-sm-8 am-u-md-4 am-u-end">
              <input type="text" class="am-form-field" value="<?php echo $offer['name'];?>" disabled>
            </div>
          </div>
        <div class="am-g am-margin-top-sm">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">
              Affiliate:
            </div>
            <div class="am-u-sm-8 am-u-md-4 am-u-end">
              <input type="text" class="am-form-field" value="<?php echo $affiliate['company'];?>" disabled>
            </div>
         </div>
      <p><b>Step 2: </b>Input test link from affiliate. This is a link from their third-party system which should redirect to their tracking link for your network.</p>
       <div class="am-g am-margin-top-sm">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">
              Test Affiliate Link:
            </div>
            <div class="am-u-sm-8 am-u-md-4 am-u-end">
<!--              <input type="text" class="am-form-field" value="--><?php //echo Yii::app()->request->hostInfo.$this->createUrl('api/offerclick', array('offer_id'=>$pixels['offerid'], 'aff_id'=>$pixels['affid'], 'aff_sub'=>'test'));?><!--" id="openValue" />-->
                  <input type="text" class="am-form-field" value="<?php echo $pixels['tracking'] ?>" id="openValue"/>
            </div>
            <div class="am-hide-sm-only am-u-md-6"><a href="#" id="open">Open</a></div>
       </div> 
       <p><b>Step 3:</b>Test conversion tracking which will load the affiliate's pixel or URL.</p>
              <div class="am-g am-margin-top-sm">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">
              Test Conversion URL:
            </div>
            <div class="am-u-sm-8 am-u-md-4 am-u-end">
              <input type="text" class="am-form-field" value="<?php echo $testUrl?>">
            </div>
            <div class="am-hide-sm-only am-u-md-6"><a href="<?php echo $testUrl?>" target="_blank">Test</a></div>
       </div>
      </div>
    </div>
  </div>
<script type="text/javascript">
$(function(){
	$("#open").click(function(){
		var openValue =$("#openValue").val();
		window.open(openValue,'newwindow'); 
		});
});
</script>
  
  