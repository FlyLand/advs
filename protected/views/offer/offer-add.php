<?php
/**
 * 增加offer
 */ 
include_once dirname(dirname(__FILE__)).'/sidebar.php';
?>
<style>
.hide{
	display: none;
}
</style>
<div class="admin-content">
		<div class="am-cf am-padding">
			<div class="am-fl am-cf">
			<strong class="am-text-primary am-text-lg"><a href="<?php echo $this->createUrl('offer/list')?>">创建offer</a>/</strong>
			<small>add</small>
			</div>
		</div>
		<div class="am-tabs am-margin" data-am-tabs=""> 

<div class="am-panel am-panel-default am-form">
  <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-1'}"><b>detail</b><span class="am-icon-chevron-down am-fr"></span></div>
      <div class="am-panel-bd am-collapse am-in" id="collapse-panel-1">
      
      		<div class="am-g am-margin-top">
				<div class="am-u-sm-4 am-u-md-2 am-text-right">Advertiser:</div>
					<div class="am-u-sm-8 am-u-md-10">
						<select id="advertiser" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
						<?php foreach ($advertises as $ad){
						    echo '<option value="'.$ad['id'].'">'.$ad['company'].'</option>';
						}?> 
						</select>
					</div>
			</div> 
             <div class="am-g am-margin-top">
        			<div class="am-u-sm-4 am-u-md-2 am-text-right">
        			Name:
        			</div>
        			<div class="am-u-sm-8 am-u-md-4">
        			<input type="text" class="am-input-sm" name="name" id="name">
        			</div>
    			<div class="am-hide-sm-only am-u-md-6"></div>
    		</div>
			
			<div class="am-g am-margin-top">
					<div class="am-u-sm-4 am-u-md-2 am-text-right">
							Description:
					</div>
					<div class="am-u-sm-8 am-u-md-4">
						<textarea id="description" name="description" rows="5"  placeholder="description"></textarea>
					</div>
					<div class="am-hide-sm-only am-u-md-6"></div>
			</div>
<!-- 			示例url -->
             <div class="am-g am-margin-top">
        			<div class="am-u-sm-4 am-u-md-2 am-text-right">
        			Preview URL:
        			</div>
        			<div class="am-u-sm-8 am-u-md-4">
        			<input type="text" class="am-input-sm" name="preview_url" id="preview_url">
        			</div>
    			<div class="am-hide-sm-only am-u-md-6">Link to landing page with no geo targeting so Affiliates can see landing page example.</div>
    		</div>
<!--     		  offer URL  		   -->
             <div class="am-g am-margin-top">
        			<div class="am-u-sm-4 am-u-md-2 am-text-right">
        			Default Offer URL:
        			</div>
        			<div class="am-u-sm-8 am-u-md-4">
        			<input type="text" class="am-input-sm" name="default_offer_url" id="offer_url">
        			</div>
    			<div class="am-hide-sm-only am-u-md-6">The Offer URL where traffic will be directed to. You must specify at least the Default Offer URL. The optional variables below can be used in Offer URLs.</div>
    		</div> 
<!--     		 协议 -->
      		<div class="am-g am-margin-top">
				<div class="am-u-sm-4 am-u-md-2 am-text-right">Conversion Tracking:</div>
					<div class="am-u-sm-8 am-u-md-10">
						<select name="conversion_tracking"  id="OfferProtocol" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
                            <option value="http">HTTP iFrame Pixel</option>
                            <option value="https">HTTPS iFrame Pixel</option>
                            <option value="http_img">HTTP Image Pixel</option>
                            <option value="https_img">HTTPS Image Pixel</option>
                            <option value="server">Server Postback w/ Transaction ID</option>
                            <option value="server_affiliate">Server Postback w/ Affiliate ID</option>
						</select>
						<p>iFrame and Image conversion pixels use client-based cookie or cookieless tracking while Server Postback uses server-based cookieless tracking URLs. </p>
					</div>
			</div>   		  
      		<div class="am-g am-margin-top">
				<div class="am-u-sm-4 am-u-md-2 am-text-right">Status:</div>
					<div class="am-u-sm-8 am-u-md-10">
 						<select name="status" id="status" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
							<option value="2">Deleted</option>
                            <option value="1">Active</option>
                            <option value="0" selected="selected">Pending</option>
						</select>
						<p>Active allows all Affiliates to view the offer. Pending allows the offer to be tested as if it were active except Affiliates won't be able to see it. Conversion pixels can be tested with the pending status. Paused removes the offer for Affiliates to view and redirects traffic to the redirect offer if set. Deleted causes all jump links to return dead.</p>
					</div>
			</div>
<!-- 			过期时间 -->
             <div class="am-g am-margin-top">
        			<div class="am-u-sm-4 am-u-md-2 am-text-right">
        			Expiration Date:
        			</div>
        			<div class="am-u-sm-8 am-u-md-4">
        			<input type="text" id= "expirationDate" class="am-form-field" name= "expiration_date"placeholder="Expiration Date" data-am-datepicker />
        			</div>
    			<div class="am-hide-sm-only am-u-md-6">Offer will expire at 11:59 pm of selected date.</div>
    		</div>
<!--     		offer分类 -->
      		<div class="am-g am-margin-top">
				<div class="am-u-sm-4 am-u-md-2 am-text-right">Categories:</div>
					<div class="am-u-sm-8 am-u-md-10">
						<select id="types" name="types" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
                            <option value="0">Others</option>
							<?php foreach($types as $type){ ?>
								<option value="<?php echo $type['id']?>"><?php echo $type['type_name_en']?></option>
							<?php } ?>
						</select>
						<p>Categorize offer for Affiliates to search and group by.</p>
					</div>
			</div>
             <div class="am-g am-margin-top">
        			<div class="am-u-sm-4 am-u-md-2 am-text-right">
        			Reference ID:
        			</div>
        			<div class="am-u-sm-8 am-u-md-4">
        			<input type="number" class="am-input-sm" name="reference_id" id="reference_id">
        			</div>
    			<div class="am-hide-sm-only am-u-md-6"> Assign a reference ID to this offer and pass this value into Offer URLs.</div>
    		</div>
			<div class="am-g am-margin-top">
					<div class="am-u-sm-4 am-u-md-2 am-text-right">
							Note:
					</div>
					<div class="am-u-sm-8 am-u-md-4">
						<textarea class="" name="note" id="note" rows="5" id="note" placeholder="note"></textarea>
					</div>
					<div class="am-hide-sm-only am-u-md-6">The contents of this note will not be displayed to Affiliates</div>
			</div>  	 		  
      </div>
</div>
<div class="am-panel am-panel-default">
  <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-2'}"><b>Currency</b><span class="am-icon-chevron-down am-fr"></span></div>
      <div class="am-panel-bd am-collapse am-in" id="collapse-panel-2">
      
<!--       		<div class="am-g am-margin-top"> -->
<!-- 				<div class="am-u-sm-4 am-u-md-2 am-text-right">Custom Currency:</div> -->
<!-- 					<div class="am-u-sm-8 am-u-md-10"> -->
<!--						<select name="custom_currency" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
<!-- 							 <option value="disable">Disable</option> -->
<!-- 							 <option value="enable">Enable</option>  -->
<!-- 						</select> -->
<!-- 						<p>The default network currency is United States, Dollars. Enable a custom currency that will override the default network setting. This will not change any numbers, just the displayed symbol.</p> -->
<!-- 					</div> -->
<!-- 			</div>  -->
<!--       货币，默认USD美元 -->
      		<div class="am-g am-margin-top">
				<div class="am-u-sm-4 am-u-md-2 am-text-right">Offer Currency:</div>
					<div class="am-u-sm-8 am-u-md-10">
						<select name="custom_currency" id="currency" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
                            <option value="USD">United States, Dollars</option>
                            <option value="EUR">Euro</option>
                            <option value="ARS">Argentina, Pesos</option>
                            <option value="AUD">Australia, Dollars</option>
                            <option value="BSD">Bahamas, Dollars</option>
                            <option value="BBD">Barbados, Dollars</option>
                            <option value="BMD">Bermuda, Dollars</option>
                            <option value="BGN">Bulgaria, Leva</option>
                            <option value="BRL">Brazil, Reais</option>
                            <option value="GBP">Britain (United Kingdom), Pounds</option>
                            <option value="BND">Brunei Darussalam, Dollars</option>
                            <option value="CAD">Canada, Dollars</option>
                            <option value="KYD">Cayman Islands, Dollars</option>
                            <option value="CLP">Chile, Pesos</option>
                            <option value="CNY">China, Yuan Renminbi</option>
                            <option value="COL">Colombia, Pesos</option>
                            <option value="HRK">Croatia, Kuna</option>
                            <option value="CZK">Czech Republic, Koruny</option>
                            <option value="DKK">Denmark, Kroner</option>
                            <option value="DOP">Dominican Republic, Pesos</option>
                            <option value="XCD">East Caribbean, Dollars</option>
                            <option value="EGP">Egypt, Pounds</option>
                            <option value="EEK">Estonia, Krooni</option>
                            <option value="FJD">Fiji, Dollars</option>
                            <option value="GYD">Guyana, Dollars</option>
                            <option value="HKD">Hong Kong, Dollars</option>
                            <option value="HUF">Hungary, Forint</option>
                            <option value="INR">India, Rupees</option>
                            <option value="IDR">Indonesia, Rupiahs</option>
                            <option value="IRR">Iran, Rials</option>
                            <option value="ILS">Israel, New Shekels</option>
                            <option value="JMD">Jamaica, Dollars</option>
                            <option value="JPY">Japan, Yen</option>
                            <option value="KZT">Kazakhstan, Tenge</option>
                            <option value="KRW">South Korea, Won</option>
                            <option value="LVL">Latvia, Lati</option>
                            <option value="LTL">Lithuania, Litai</option>
                            <option value="MYR">Malaysia, Ringgits</option>
                            <option value="MXN">Mexico, Pesos</option>
                            <option value="MZN">Meticais, Meticais</option>
                            <option value="ANG">Netherlands Antilles, Guilders (also called Florins)</option>
                            <option value="NZD">New Zealand, Dollars</option>
                            <option value="NOK">Norway, Krone</option>
                            <option value="OMR">Oman, Rials</option>
                            <option value="PKR">Pakistan, Rupees</option>
                            <option value="PAB">Panama, Balboa</option>
                            <option value="PEN">Peru, Nuevos Soles</option>
                            <option value="PHP">Philippines, Pesos</option>
                            <option value="PLN">Poland, Zlotych</option>
                            <option value="QAR">Qatar, Rials</option>
                            <option value="RON">Romani, New Lei</option>
                            <option value="RUB">Russia, Rubles</option>
                            <option value="SAR">Saudi Arabia, Riyals</option>
                            <option value="RSD">Serbia, Dinars</option>
                            <option value="SGD">Singapore, Dollars</option>
                            <option value="ZAR">South Africa, Rand</option>
                            <option value="SEK">Sweden, Kronor</option>
                            <option value="CHF">Switzerland/Liechtenstein, Francs</option>
                            <option value="SYP">Syria, Pounds</option>
                            <option value="TWD">Taiwan, New Dollars</option>
                            <option value="THB">Thailand, Baht</option>
                            <option value="TTD">Trinidad/Tobago, Dollars</option>
                            <option value="TRY">Turkey, Lira</option>
                            <option value="UAH">Ukraine, Hryvnia</option>
                            <option value="AED">United Arab Emirates, Dirham</option>
                            <option value="VEF">Venezuela, Bolivares Fuertes</option>
                            <option value="VND">Vietnam, Dong</option>
                            <option value="YER">Yemen, Rials</option>
                            <option value="PTS">Points</option> 
						</select>
					</div>
			</div>  
      </div>
</div>
<div class="am-panel am-panel-default">
  <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-3'}"><b>Revenue</b><span class="am-icon-chevron-down am-fr"></span></div>
      <div class="am-panel-bd am-collapse am-in" id="collapse-panel-3">
<!--       收入类型 -->
      		<div class="am-g am-margin-top">
				<div class="am-u-sm-4 am-u-md-2 am-text-right">Revenue Type:</div>
					<div class="am-u-sm-8 am-u-md-10">
						<select name="revenue_type" id="revenue_type" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
                            <option value="cpa_flat">Revenue per Conversion (RPA)</option>
                            <option value="cpa_percentage">Revenue per Sale (RPS)</option>
                            <option value="cpa_both">Revenue per Conversion plus Revenue per Sale (RPA + RPS)</option>
                            <option value="cpc">Revenue per Click (RPC)</option>
                            <option value="cpm">Revenue per Thousand Impressions (RPM)</option> 
						</select>
						<p>The default network currency is United States, Dollars. Enable a custom currency that will override the default network setting. This will not change any numbers, just the displayed symbol.</p>
					</div>
			</div>
			 
             <div class="am-g am-margin-top">
        			<div class="am-u-sm-4 am-u-md-2 am-text-right">
        			Revenue per Conversion:
        			</div>
        			<div class="am-u-sm-8 am-u-md-4">
        			<input type="text" name="revenue" id="revenue" value="" placeholder="%">
        			</div>
    			<div class="am-hide-sm-only am-u-md-6">The amount paid by advertisers per conversion.</div>
    		</div> 
      </div>
</div>
<div class="am-panel am-panel-default">
  <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-4'}"><b>Payout</b><span class="am-icon-chevron-down am-fr"></span></div>
      <div class="am-panel-bd am-collapse am-in" id="collapse-panel-4">
<!--       支出类型 -->
      		<div class="am-g am-margin-top">
				<div class="am-u-sm-4 am-u-md-2 am-text-right">Payout Type:</div>
					<div class="am-u-sm-8 am-u-md-10">
						<select name="payout_type" id = "payout_type" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
                            <option value="cpa_flat">Cost per Conversion (CPA)</option>
                            <option value="cpa_percentage">Cost per Sale (CPS)</option>
                            <option value="cpa_both">Cost per Conversion plus Cost per Sale (CPA + CPS)</option>
                            <option value="cpc">Cost per Click (CPC)</option>
                            <option value="cpm">Cost per Thousand Impressions (CPM)</option>
						</select>
					</div>
			</div>
			
<!--              <div class="am-g am-margin-top"> -->
<!--         			<div class="am-u-sm-4 am-u-md-2 am-text-right"> -->
<!--         			Payout Method: -->
<!--         			</div> -->
<!--         			<div class="am-u-sm-8 am-u-md-4"> -->
<!--         			<input type="radio" name="payout_method" id="payout_method" value="default" checked="">default -->
<!--         			</div> -->
<!--     			<div class="am-hide-sm-only am-u-md-6"></div> -->
<!--     		</div>  -->
<!-- 			支出所占的比例 -->
             <div class="am-g am-margin-top">
        			<div class="am-u-sm-4 am-u-md-2 am-text-right">
        			Cost per Conversion:
        			</div>
        			<div class="am-u-sm-8 am-u-md-4">
        			<input type="text" name="cost_per_conversion" id="cost_per_conversion"  checked=""placeholder="%"> 
        			</div>
    			<div class="am-hide-sm-only am-u-md-6">The amount paid to affiliates per conversion.</div>
    		</div> 
      </div>
</div>
<!-- <div class="am-panel am-panel-default"> -->
<!--   <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-5'}"><b>Goals</b><span class="am-icon-chevron-down am-fr"></span></div> -->
<!--       <div class="am-panel-bd am-collapse am-in" id="collapse-panel-5"> -->
      
<!--       		<div class="am-g am-margin-top"> -->
<!-- 				<div class="am-u-sm-4 am-u-md-2 am-text-right">Multiple Conversion Goals:</div> -->
<!-- 					<div class="am-u-sm-8 am-u-md-10"> -->
<!-- 						<select name="multiple_conversion_goals" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
<!-- 							 <option value="disable">Disable</option> -->
<!-- 							 <option value="enable">Enable</option>  -->
<!-- 						</select> -->
<!-- 					</div> -->
<!-- 			</div> -->
<!--       </div> -->
<!-- </div> -->
<div class="am-panel am-panel-default">
  <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-6'}"><b>Settings</b>&nbsp;&nbsp;&nbsp;<small>Control how affiliates are able to access your offer (optional)</small><span class="am-icon-chevron-down am-fr"></span></div>
      <div class="am-panel-bd am-collapse am-in" id="collapse-panel-6">
      
      		<div class="am-g am-margin-top">
				<div class="am-u-sm-4 am-u-md-2 am-text-right">Private:</div>
					<div class="am-u-sm-8 am-u-md-10">
						<select name="private" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" id="private"style="display: none;">
							 <option value="0">Disable</option>
							 <option value="1">Enable</option> 
						</select>
						<p>Setting an offer to private hides the offer from affiliates and allows you to grant access to specific affiliates.</p>
					</div>
			</div>
      		<div class="am-g am-margin-top">
				<div class="am-u-sm-4 am-u-md-2 am-text-right">Require Approval:</div>
					<div class="am-u-sm-8 am-u-md-10">
						<select name="require_approval" id="require_approval" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
							 <option value="0">Disable</option>
							 <option value="1">Enable</option> 
						</select>
						<p>Requires Affiliates to apply and get approval before pushing traffic to this offer.</p>
					</div>
			</div>
<!-- 			需明确附加条款 -->
      		<div class="am-g am-margin-top">
				<div class="am-u-sm-4 am-u-md-2 am-text-right">Terms and Conditions:</div>
					<div class="am-u-sm-8 am-u-md-10">
						<select name="terms" id="terms"data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
							 <option value="0">Disable</option>
							 <option value="1">Enable</option> 
						</select>
						<p>Requires affiliates to read and explicitly accept the additional Terms and Conditions specified.</p>
					</div>
			</div>
      		<div class="am-g am-margin-top">
				<div class="am-u-sm-4 am-u-md-2 am-text-right">SEO Friendly Links</div>
					<div class="am-u-sm-8 am-u-md-10">
						<select name="seo" id="seo" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
							 <option value="0">Disable</option>
							 <option value="1">Enable</option> 
						</select>
						<p>Enable tracking links to be SEO friendly. This option is recommended for direct offers only. All types of redirects including geo targeting, browser targeting, and conversion caps are disabled because all tracking links must always be redirected to the same offer URL. Affiliate tracking links are forced to be encoded SEO friendly.</p>
					</div>
			</div>
      		<div class="am-g am-margin-top">
				<div class="am-u-sm-4 am-u-md-2 am-text-right">Caps:</div>
					<div class="am-u-sm-8 am-u-md-10">
							<select id="caps" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
							 <option value="0">Disable</option>
							 <option value="1">Enable</option> 
						</select>
					</div>
			</div>
			<?php 
			//TODO
			?>
<!-- 			每天的转化 -->
             <div class="am-g am-margin-top daily_con hide">
        			<div class="am-u-sm-4 am-u-md-2 am-text-right">
        			Daily Conversions:
        			</div>
        			<div class="am-u-sm-8 am-u-md-4">
        			<input type="text" name="daily_con" id="daily_con" >
        			</div>
    			<div class="am-hide-sm-only am-u-md-6">Max number of conversions offer can receive per day. Leave blank or set to 0 for no conversion cap.</div>
    		</div> 
<!--     		每月的转化 --> 
             <div class="am-g am-margin-top month_con hide">
        			<div class="am-u-sm-4 am-u-md-2 am-text-right">
        			Monthly Conversions:
        			</div>
        			<div class="am-u-sm-8 am-u-md-4">
        			<input type="text" name="month_con" id="month_con" >
        			</div>
    			<div class="am-hide-sm-only am-u-md-6">Max number of conversions offer can receive per month. Leave blank or set to 0 for no monthly conversion cap.</div>
    		</div> 
             <div class="am-g am-margin-top daily_pay hide">
        			<div class="am-u-sm-4 am-u-md-2 am-text-right">
        			Daily Payout:
        			</div>
        			<div class="am-u-sm-8 am-u-md-4">
        			<input type="text" name="daily_pay" id="daily_pay" placeholder="$">
        			</div>
    			<div class="am-hide-sm-only am-u-md-6">Max payout amount offer can post per day. Leave blank or set to 0 for no payout cap.</div>
    		</div> 
             <div class="am-g am-margin-top month_pay hide">
        			<div class="am-u-sm-4 am-u-md-2 am-text-right">
        			Monthly Payout:
        			</div>
        			<div class="am-u-sm-8 am-u-md-4">
        			<input type="text" name="month_pay" id="month_pay" placeholder="$">
        			</div>
    			<div class="am-hide-sm-only am-u-md-6">Max payout amount offer can post per month. Leave blank or set to 0 for no monthly payout cap.</div>
    		</div> 
             <div class="am-g am-margin-top daily_re hide">
        			<div class="am-u-sm-4 am-u-md-2 am-text-right">
        			Daily Revenue:
        			</div>
        			<div class="am-u-sm-8 am-u-md-4">
        			<input type="text" name="daily_re" id="daily_re" placeholder="$">
        			</div>
    			<div class="am-hide-sm-only am-u-md-6">Max revenue amount offer can generate per day. Leave blank or set to 0 for no revenue cap.</div>
    		</div> 
             <div class="am-g am-margin-top month_re hide">
        			<div class="am-u-sm-4 am-u-md-2 am-text-right">
        			Monthly Revenue:
        			</div>
        			<div class="am-u-sm-8 am-u-md-4">
        			<input type="text" name="month_re" id="month_re" placeholder="$">
        			</div>
    			<div class="am-hide-sm-only am-u-md-6">Max revenue amount offer can generate per month. Leave blank or set to 0 for no monthly revenue cap.</div>
    		</div> 
    					
      		<div class="am-g am-margin-top">
				<div class="am-u-sm-4 am-u-md-2 am-text-right">Email Instructions:</div>
					<div class="am-u-sm-8 am-u-md-10">
						<select name="email" id="email"data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
							 <option value="0">Disable</option>
							 <option value="1">Enable</option> 
						</select>
						<p>Specify criteria for affiliates relating to the subject and from lines they may use while promoting this offer.</p>
					</div>
			</div>
<!-- 			是否可以退订邮件 -->
      		<div class="am-g am-margin-top">
				<div class="am-u-sm-4 am-u-md-2 am-text-right">Suppression List:</div>
					<div class="am-u-sm-8 am-u-md-10">
						<select id="suppression_list" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
							 <option value="0">Disable</option>
							 <option value="1">Enable</option> 
						</select>
						<p>Enabling suppression list allows a suppression list to be downloaded for the offer and provides an unsubscribe link.</p>
					</div>
			</div>
      </div>
</div>
<div class="am-panel am-panel-default">
  <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-7'}"><b>Tracking</b>&nbsp;&nbsp;&nbsp;<small>Define advanced tracking metrics for your offer (optional).</small><span class="am-icon-chevron-down am-fr"></span></div>
      <div class="am-panel-bd am-collapse am-in" id="collapse-panel-7">
      
<!--       		<div class="am-g am-margin-top"> -->
<!-- 				<div class="am-u-sm-4 am-u-md-2 am-text-right">Tracking Domain:</div> -->
<!-- 					<div class="am-u-sm-8 am-u-md-10"> -->
<!-- 						<select name="private" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
<!-- 							 <option value="default">Default</option> -->
<!-- 						</select> -->
<!-- 						<p>Select an alternative tracking domain. Default tracking domain is: track.adxmi.com.</p> -->
<!-- 					</div> -->
<!-- 			</div> -->
<!-- 			过期或者超标跳转offerid -->
      		<div class="am-g am-margin-top">
				<div class="am-u-sm-4 am-u-md-2 am-text-right">Redirect Offer:</div>
					<div class="am-u-sm-8 am-u-md-10">
						<select name="redirect_offer_id" id="redirect_offer_id" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
                            <option value="3078">(Offline Soon)Ziptel-Android-Global Except CN IN-Incentive</option>
                            <option value="784">9apps-IN-Android-Non-Incentive</option>
                            <option value="2240">123Guo-IOS-HK,MO,TW-Incentive</option>
                            <option value="2620">360 Security-Android-14 Countries-Non-incentive</option>
                            <option value="3094">360 Security-Android-ES,NL-Non-incentive</option>
                            <option value="748">360 Security-Android-Global Except-Non-incentive</option>
                            <option value="2790">360 Security-Android-IN,ID,MY,SA,AE-Non-incentive</option>
                            <option value="2468">360 Security-Android-JP-Non-incentive</option>
                            <option value="2466">360 Security-Android-NZ,PT,SG-Non-incentive</option>
                            <option value="2998">360 Security-Android-RU-Non-incentive</option>
                            <option value="3050">360 Security-Android-TH,TR,VN,UA-Non-incentive</option>
						</select>
						<p>Current offer is redirected to this offer if paused, passed expiration date, or conversion cap is exceeded.</p>
					</div>
			</div>
<!--       		<div class="am-g am-margin-top"> -->
<!-- 				<div class="am-u-sm-4 am-u-md-2 am-text-right">Secondary Offer:</div> -->
<!-- 					<div class="am-u-sm-8 am-u-md-10"> -->
<!-- 						<select name="terms" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
<!-- 							 <option value="disable">Disable</option> -->
<!-- 							 <option value="enable">Enable</option>  -->
<!-- 						</select> -->
<!-- 						<p>An offer to redirect users to if a user has already generated a conversion for this specific offer. Select 'Network Offer' to choose an offer already loaded into the system. If 'Network Offer' is selected, all tracking information will be maintained and affiliates will get credit for conversions generated on the converted offer. Select 'Offer URL' to enter a url for a custom converted offer. Affiliate tracking information will not be passed to the URL automatically and affiliates will not be credited.</p> -->
<!-- 					</div> -->
<!-- 			</div> -->
<!-- 			session_hours -->
      		<div class="am-g am-margin-top">
				<div class="am-u-sm-4 am-u-md-2 am-text-right">Click Session Lifespan:</div>
					<div class="am-u-sm-8 am-u-md-10">
						<select name="lifespan" id="session_hours" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
                            <option value="24">1 Day</option>
                            <option value="168">1 Week</option>
                            <option value="336">2 Weeks</option>
                            <option value="672">1 Month</option>
                            <option value="1344">2 Months</option>
                            <option value="2016">3 Months</option>
                            <option value="4032">6 Months</option>
                            <option value="8064">1 Year</option> 
						</select>
						<p>Duration of time to keep the click session active for this offer.</p>
					</div>
			</div>
<!--       		<div class="am-g am-margin-top"> -->
<!-- 				<div class="am-u-sm-4 am-u-md-2 am-text-right">Custom Variables:</div> -->
<!-- 					<div class="am-u-sm-8 am-u-md-10"> -->
<!-- 						<select name="custom_variables" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
<!-- 							 <option value="disable">Disable</option> -->
<!-- 							 <option value="enable">Enable</option>  -->
<!-- 						</select> -->
<!-- 						<p>Allow affiliates to insert custom variables into the tracking link that are passed to the Offer URLs. Simply update the offer URLs to include the name of your variable wrapped in { } like {email}</p> -->
<!-- 					</div> -->
<!-- 			</div> -->
<!--       		<div class="am-g am-margin-top"> -->
<!-- 				<div class="am-u-sm-4 am-u-md-2 am-text-right">Direct Links:</div> -->
<!-- 					<div class="am-u-sm-8 am-u-md-10"> -->
<!-- 						<select name="direct_links" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
<!-- 							 <option value="disable">Disable</option> -->
<!-- 							 <option value="enable">Enable</option>  -->
<!-- 						</select> -->
<!-- 						<p>Enable affiliates to link directly to your website without using a tracking link. Instead the affiliate ID is included in the direct link and JavaScript code will track the click when user lands on your website. </p> -->
<!-- 					</div> -->
<!-- 			</div> -->
<!--       		<div class="am-g am-margin-top"> -->
<!-- 				<div class="am-u-sm-4 am-u-md-2 am-text-right">Deep Links:</div> -->
<!-- 					<div class="am-u-sm-8 am-u-md-10"> -->
<!-- 						<select name="deep_links" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
<!-- 							 <option value="disable">Disable</option> -->
<!-- 							 <option value="enable">Enable</option>  -->
<!-- 						</select> -->
<!-- 						<p>Allow affiliates to pull links from the offer website and redirect their tracking links to specific pages.</p> -->
<!-- 					</div> -->
<!-- 			</div> -->
<!--       		<div class="am-g am-margin-top"> -->
<!-- 				<div class="am-u-sm-4 am-u-md-2 am-text-right">Approve Conversions:</div> -->
<!-- 					<div class="am-u-sm-8 am-u-md-10"> -->
<!-- 						<select name="approve_conversions" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
<!-- 							 <option value="disable">Disable</option> -->
<!-- 							 <option value="enable">Enable</option>  -->
<!-- 						</select> -->
<!-- 						<p>Enable 'Approve Conversions' to require each conversion for this offer to be approved. Conversions will be set to 'pending' by default and will be excluded from billing and stats until approved.</p> -->
<!-- 					</div> -->
<!-- 			</div> -->
<!--       		<div class="am-g am-margin-top"> -->
<!-- 				<div class="am-u-sm-4 am-u-md-2 am-text-right">Multiple Conversions:</div> -->
<!-- 					<div class="am-u-sm-8 am-u-md-10"> -->
<!-- 						<select name="multiple_conversions" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
<!-- 							 <option value="disable">Disable</option> -->
<!-- 							 <option value="enable">Enable</option>  -->
<!-- 						</select> -->
<!-- 						<p>Enable multiple conversions to be recorded per user for only one active session. An active session is created each time a user clicks on a tracking link.</p> -->
<!-- 					</div> -->
<!-- 			</div> -->
      		<div class="am-g am-margin-top">
				<div class="am-u-sm-4 am-u-md-2 am-text-right">Start Session Tracking:</div>
					<div class="am-u-sm-8 am-u-md-10">
						<select name="start_session_tracking" id="session_impression_hours" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
                            <option value="0">Clicks</option>
                            <option value="1">Impressions</option>
						</select>
						<p>Start conversion tracking on the impression pixel. Any tracking link clicks will overwrite sessions started on the impression pixel.</p>
					</div>
			</div>
<!--       		<div class="am-g am-margin-top"> -->
<!-- 				<div class="am-u-sm-4 am-u-md-2 am-text-right">Subscription:</div> -->
<!-- 					<div class="am-u-sm-8 am-u-md-10"> -->
<!-- 						<select name="deep_links" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
<!-- 							 <option value="0">Disable</option> -->
<!-- 							 <option value="1">Enable</option>  -->
<!-- 						</select> -->
<!-- 						<p>Allows this offer to be tracked as a subscription-based offer.</p> -->
<!-- 					</div> -->
<!-- 			</div> -->
<!--       		<div class="am-g am-margin-top"> -->
<!-- 				<div class="am-u-sm-4 am-u-md-2 am-text-right">Customer List:</div> -->
<!-- 					<div class="am-u-sm-8 am-u-md-10"> -->
<!-- 						<select name="deep_links" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
<!--                             <option value="0">None</option> -->
<!--                             <option value="2">cYpes Huang</option> -->
<!-- 						</select> -->
<!-- 						<p>List to append converted customers to. </p> -->
<!-- 					</div> -->
<!-- 			</div> -->
<!-- 			白名单 -->
		  <div class="am-g am-margin-top">
			  <div class="am-u-sm-4 am-u-md-2 am-text-right">Offer Country:</div>
			  <div class="am-u-sm-8 am-u-md-10">
				  <input type="text" name="country" id="country" placeholder="">
			  </div>
		  </div>
      		<div class="am-g am-margin-top">
				<div class="am-u-sm-4 am-u-md-2 am-text-right">Offer Whitelist:</div>
					<div class="am-u-sm-8 am-u-md-10">
						<select name="deep_links"  id ="enable_offer_whitelist" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" style="display: none;">
                            <option value="0">Disabled</option>
                            <option value="1">Enabled</option>
						</select>
						<p>Requires a conversion request to be validated against a list of acceptable IP addresses or referrer domains.	(Server Postback offers only have the option to whitelist IP addresses)</p>
					</div>
			</div>
      </div>
</div>
      </div>
    	  	<div class="am-margin">
    			<button type="button" id="submit" onclick="submit()" class="am-btn am-btn-primary am-btn-xs">Save</button>
    		</div>
</div>
<script type="text/javascript"> 

$("#caps").change(function(){
	var caps2 = $("#caps").val();
	if(caps2 == 1){ 
		$(".daily_con").removeClass('hide');
		$(".month_con").removeClass('hide');
		$(".daily_pay").removeClass('hide');
		$(".month_pay").removeClass('hide');
		$(".daily_re").removeClass('hide');
		$(".month_re").removeClass('hide'); 
	}
	if(caps2 == 0){ 
		$(".daily_con").addClass('hide');
		$(".month_con").addClass('hide');
		$(".daily_pay").addClass('hide');
		$(".month_pay").addClass('hide');
		$(".daily_re").addClass('hide');
		$(".month_re").addClass('hide'); 
	}
});

function submit(){ 
	var isTrue = true;
	if(!isTrue){
		alert("请勿重复提交");
		return;
		}
	var advertiser = $("#advertiser").val();
	var name = $("#name").val(); 
	var description = $("#description").val();
	var preview_url = $("#preview_url").val(); 
	var offer_url = $("#offer_url").val();
	var protocol = $("#OfferProtocol").val();
	var expirationDate = $("#expirationDate").val();
	var offer_category = $("#categories").val();
	var ref_id = $("#reference_id").val();
	var currency = $("#currency").val();
	var revenue_type = $("#revenue_type").val();
	var revenue = $("#revenue").val();
	var payout_type = $("#payout_type").val();
	var cost_per_conversion = $("#cost_per_conversion").val();
	var Private = $("#private").val();
	var require_approval = $("#require_approval").val();
	var terms = $("#terms").val();
	var seo = $("#seo").val();
	var email = $("#email").val();
	var suppression_list = $("#suppression_list").val();
	var redirect_offer_id = $("#redirect_offer_id").val();
	var session_impression_hours = $("#session_impression_hours").val();
	var session_hours = $("#session_hours").val();
	var enable_offer_whitelist = $("#enable_offer_whitelist").val();
	var note = $("#note").val();
	var status = $("#status").val(); 
	var caps = $("#caps").val(); 
	if(!advertiser){
		alert("advertiser can't be null！");
		return;
		}

	if(!name){
		alert("offer can't be null！");
		return;
		}
	if(!offer_url){
		alert("offer_url can't be null！");
		return;
		}
	if(!protocol){
		alert("protocol can't be null！");
		return;
		}
	if(!revenue){
		alert("revenue can't be null！");
		return;
		}
	if(caps == 1){//增加限制
		var daily_con = $("#daily_con").val(); 
		var month_con = $("#month_con").val(); 
		var daily_pay = $("#daily_pay").val(); 
		var month_pay = $("#month_pay").val(); 
		var daily_re = $("#daily_re").val(); 
		var month_re = $("#month_re").val();
		if(!daily_con || !month_con|| !daily_pay|| !month_pay|| !daily_re || !month_re ){
			alert("the caps can't be null！");
			return;
			}
		 $.ajax({    
			    url:'<?php echo $this->createUrl('offer/add');?>',// 跳转到 action    
			    data:{
				    //增加限制字段 
			    	daily_con : daily_con,     
			    	month_con : month_con,     
			    	daily_pay : daily_pay,     
			    	month_pay : month_pay,     
			    	daily_re : daily_re,     
			    	month_re : month_re,     
			    	advertiser : advertiser,
			    	name : name,
			    	description : description,
			    	preview_url : preview_url,
			    	offer_url : offer_url,
			    	protocol : protocol,
			    	expirationDate : expirationDate,
			    	offer_category : offer_category,
			    	ref_id : ref_id,
			    	currency : currency,
			    	revenue_type : revenue_type,
			    	revenue : revenue,
			    	payout_type : payout_type,
			    	cost_per_conversion : cost_per_conversion,
			    	Private : Private,
			    	require_approval : require_approval,
			    	terms : terms,
			    	seo : seo,
			    	email : email,
			    	caps : caps,
			    	suppression_list : suppression_list,
			    	redirect_offer_id : redirect_offer_id,
			    	session_impression_hours : session_impression_hours,
			    	session_hours : session_hours,
			    	enable_offer_whitelist : enable_offer_whitelist,
			    	note : note,
			    	status : status 
			    },    
			    type:'post',    
			    dataType:'json', 
			    complete:function(){
				    isTrue = false;
				    },   
			    success:function(data) { 
				    if(data.error_code == 1){
					    alert(data.msg);
					    window.location.href ="<?php echo $this->createUrl('offer/list');?>";
					    }else{
						alert(data.msg);
//						window.location.reload();
							}
				     
			     },
			     error:function(data){
			    	 console.log(data);
				     }   
			}); 
		}else{
			 $.ajax({    
				    url:'<?php echo $this->createUrl('offer/add');?>',// 跳转到 action    
				    data:{   
				    	advertiser : advertiser,
				    	name : name,
				    	description : description,
				    	preview_url : preview_url,
				    	offer_url : offer_url,
				    	protocol : protocol,
				    	expirationDate : expirationDate,
				    	offer_category : offer_category,
				    	ref_id : ref_id,
				    	currency : currency,
				    	revenue_type : revenue_type,
				    	revenue : revenue,
				    	payout_type : payout_type,
				    	cost_per_conversion : cost_per_conversion,
				    	Private : Private,
				    	require_approval : require_approval,
				    	terms : terms,
				    	seo : seo,
				    	email : email,
				    	caps : 0,
				    	suppression_list : suppression_list,
				    	redirect_offer_id : redirect_offer_id,
				    	session_impression_hours : session_impression_hours,
				    	session_hours : session_hours,
				    	enable_offer_whitelist : enable_offer_whitelist,
				    	note : note,
				    	status : status 
				    },    
				    type:'post',    
				    dataType:'json',
				    complete:function(){
					    isTrue = false;
					    },       
				    success:function(data) { 
					    if(data.error_code == 1){
						    alert(data.msg);
						    window.location.href ="<?php echo $this->createUrl('offer/list');?>";
						    }else{
							alert(data.msg);
//							window.location.reload();
								}
					     
				     },
				     error:function(data){
				    	 console.log(data);
					     }   
				}); 
			} 
}  
</script>