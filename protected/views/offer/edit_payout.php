<?php require_once dirname(dirname(__FILE__)) . '/sidebar.php';?>
<style>
    .am-form div{
        padding-top: 10px;
    }
    .hide{
        display: none;
    }
</style>
<!-- content start -->
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Offer Payout: <?php echo $offer['name']?> - Offer </strong></div>
    </div>
    <am-tabs am-margin>
        <form class="am-form" action="<?php echo $this->createUrl('offer/updatepayout',array('id'=>$offer['id']));?>" method="post" id="doForm">
            <h2>Currency:</h2>
            <hr data-am-widget="divider" style="" class="am-divider am-divider-default"/>
            <div class="am-u-sm-12">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">Custom Currency:</div>
                <div  class="am-u-sm-3 am-u-end">
                    <select data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" id="offerCustomCurrency" name="custom_currency">
                        <option value="0">Disabled</option>
                        <option value="1">Enabled</option>
                    </select>
                    <div class="am-hide-sm-only am-u-md-6"></div>
                    <div id="explain">
                        <p>The default network currency is United States, Dollars. Enable a custom currency that will override the default network setting. This will not change any numbers, just the displayed symbol. </p>
                    </div>
                </div>
            </div>
            <div id="off_curr" class="am-u-sm-12 hide">
                <div class="am-u-sm-4 am-u-md-2 am-text-right"> Offer Currency:</div>
                <div  class="am-u-sm-3 am-u-end">
                    <select data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" id="offerCurrency" name="currency">
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

            <h2>Revenue</h2>
            <hr data-am-widget="divider" style="" class="am-divider am-divider-default"/>
            <div class="am-u-sm-12">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">Revenue Type:</div>
                <div  class="am-u-sm-3 am-u-end">
                    <select name="revenueType" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}">
                        <option selected value="cpa_flat">Revenue per Conversion (RPA)</option>
                        <option selected value="cpa_percentage">Revenue per Sale (RPS)</option>
                        <option selected value="cpa_both">Revenue per Conversion plus Revenue per Sale (RPA + RPS)</option>
                        <option selected value="cpc">Revenue per Click (RPC)</option>
                        <option selected value="cpm">Revenue per Thousand Impressions (RPM)</option>
                    </select>
                </div>
            </div>
            <div class="am-u-sm-12">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">Revenue Method:</div>
                <div  class="am-u-sm-3 am-u-end">
                    <label id="revenueMethodDefault" for="revenueMethod"></label>
                    <input type="radio" id="revenueMethod" name="revenueMethod" value="default" checked> Default
                </div>
            </div>
            <div class="am-u-sm-12">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">Revenue per Conversion:</div>
                <div  class="am-u-sm-3 am-u-end">
                    <input type="text" id="offerMaxRevenue" name="max_revenue" value="<?php echo $offer['revenue']?>" class="am-form-field">
                    <p>The amount paid by advertisers per conversion. </p>
                </div>
            </div>
            <div class="am-u-sm-12">
                <div class="am-u-sm-4 am-u-md-4 am-text-right">
                    <label id="revenueChange" for="immediateRevenueChange"></label>
                    <input type="radio" checked name="select" value="" id="immediateRevenueChange"> Update now
                    <label id="revenueMethodDefault" for="scheduleRevenueChange"></label>
                    <input type="radio"  value="" id="scheduleRevenueChange" name="select"> Schedule change
                </div>
            </div>
            <div id="revenue_black" class="am-u-sm-3 am-u-md-2 am-text-left hide"></div>
            <div id="revenue_div" class="am-u-sm-3 am-u-md-10 am-text-left hide">
                <div class="am-u-sm-3 am-u-md-2 am-text-left">
                    <input type="text" id= "expirationDate" class="am-form-field" name= "expiration_date"placeholder="Expiration Date" data-am-datepicker />
                </div>
                <div class="am-u-sm-3 am-u-md-1 am-text-left">
                    <select data-am-selected="{btnWidth: 100, btnSize: 'sm', btnStyle: 'secondary'}" name="ScheduledPayoutChangeHour" class="am-u-sm-3 am-u-md-2">
                        <?php for($i=1;$i<13;$i++){
                            echo "<option value='$i'>$i</option>";
                        }?>
                    </select>
                </div>
                <div class="am-u-sm-3 am-u-md-1 am-text-left">
                    <select data-am-selected="{btnWidth: 100, btnSize: 'sm', btnStyle: 'secondary'}" id="ScheduledPayoutChangeMeridian" class="am-u-sm-3 am-u-md-2">
                        <option value="am" selected>am</option>
                        <option value="pm">pm</option>
                    </select>
                </div>
                <div class="am-u-sm-3 am-u-md-4 am-text-left" style="float: left"><p>     Beijing, Chongqing, Hong Kong, Urumqi   </p></div>
            </div>


            <h2>Payout</h2>
            <hr data-am-widget="divider" style="" class="am-divider am-divider-default"/>
            <div class="am-u-sm-12">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">Payout Type:</div>
                <div  class="am-u-sm-3 am-u-end">
                    <select  data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" name="payout_type" id="offerPayoutType">
                        <option selected value="cpa_flat">Revenue per Conversion (RPA)</option>
                        <option selected value="cpa_percentage">Revenue per Sale (RPS)</option>
                        <option selected value="cpa_both">Revenue per Conversion plus Revenue per Sale (RPA + RPS)</option>
                        <option selected value="cpc">Revenue per Click (RPC)</option>
                        <option selected value="cpm">Revenue per Thousand Impressions (RPM)</option>
                    </select>
                </div>
            </div>
            <div class="am-u-sm-12">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">Payout Method:</div>
                <div  class="am-u-sm-3 am-u-end">
                    <label id="payoutMethodDefault" for="scheduleRevenueChange"></label>
                    <input type="radio" id="payoutMethodDefault" name="payoutMethod" value="default" checked> Default
                </div>
            </div>
            <div class="am-u-sm-12">
                <div class="am-u-sm-4 am-u-md-2 am-text-right">Cost per Conversion</div>
                <div  class="am-u-sm-3 am-u-end">
                    <input type="text" id="offerMaxPayout" name="max_payout" value="<?php echo $offer['payout']?>" class="am-form-field">
                    <p>The amount paid to affiliates per conversion.  </p>
                </div>
            </div>
            <div class="am-u-sm-12">
                <div class="am-u-sm-4 am-u-md-3 am-text-right">
                    <label id="revenueMethodDefault" for="immediateChange"></label>
                    <input type="radio" checked name="enabled" value="" id="immediateChange"> Update now
                </div>
                <div class="am-text-left">
                    <label id="revenueMethodDefault" for="scheduleChange"></label>
                    <input type="radio" value="" id="scheduleChange" name="enabled"> Schedule change
                </div>
            </div>

            <div id="expiration_black" class="am-u-sm-3 am-u-md-2 am-text-left hide"></div>
            <div id="expiration_div" class="am-u-sm-3 am-u-md-10 am-text-left hide">
                <div class="am-u-sm-3 am-u-md-2 am-text-left">
                    <input type="text" id= "expirationDate" class="am-form-field" name= "expiration_date"placeholder="Expiration Date" data-am-datepicker />
                </div>
                <div class="am-u-sm-3 am-u-md-1 am-text-left">
                    <select data-am-selected="{btnWidth: 100, btnSize: 'sm', btnStyle: 'secondary'}" name="ScheduledPayoutChangeHour" class="am-u-sm-3 am-u-md-2">
                        <?php for($i=1;$i<13;$i++){
                            echo "<option value='$i'>$i</option>";
                        }?>
                    </select>
                </div>
                <div class="am-u-sm-3 am-u-md-1 am-text-left">
                    <select data-am-selected="{btnWidth: 100, btnSize: 'sm', btnStyle: 'secondary'}" id="ScheduledPayoutChangeMeridian" class="am-u-sm-3 am-u-md-2">
                        <option value="am" selected>am</option>
                        <option value="pm">pm</option>
                    </select>
                </div>
                <div class="am-u-sm-3 am-u-md-4 am-text-left" style="float: left"><p>     Beijing, Chongqing, Hong Kong, Urumqi   </p></div>
            </div>

<!--            <h2>Goals</h2>-->
<!--            <hr data-am-widget="divider" style="" class="am-divider am-divider-default"/>-->
<!--            <div class="am-u-sm-12">-->
<!--                <div class="am-u-sm-4 am-u-md-2 am-text-left">Multiple Conversion Goals:</div>-->
<!--                <div  class="am-u-sm-3 am-u-end">-->
<!--                    <select data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary'}" id="has_goals_enabled"  name="has_goals_enabled">-->
<!--                        <option selected value="0">Disabled</option>-->
<!--                        <option value="1">Enabled</option>-->
<!--                    </select>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div id="goal" class="am-u-sm-12 hide">-->
<!--                <div class="am-u-sm-4 am-u-md-2 am-text-right">Default Goal Name:</div>-->
<!--                <div  class="am-u-sm-3 am-u-end">-->
<!--                    <input type="text" id="offerDefaultGoalName" name="default_goal_name" value="" class="am-form-field">-->
<!--                </div>-->
<!--            </div>-->
            <div style="padding-top: 300px" class="am-u-sm-4 am-u-sm-12">
                <button class="am-btn am-btn-primary am-btn-xs" id="save" name="submit" type="submit">Save</button>
            </div>
        </form>

</div>
<script>
    $("#offerCustomCurrency").change(function(){
        var currency = $("#offerCustomCurrency").val();
        if(currency == 1){
            $("#off_curr").removeClass('hide');
            $("#explain").addClass('hide');
        }
        if(currency == 0){
            $("#off_curr").addClass('hide');
            $("#explain").removeClass('hide');
        }
    });
    $("#has_goals_enabled").change(function(){
        var goals = $("#has_goals_enabled").val();
        if(goals == 1){
            $("#goal").removeClass('hide');
        }
        if(goals == 0){
            $("#goal").addClass('hide');
        }
    });
    $("#immediateChange").click(function(){
        $("#expiration_div").addClass('hide');
        $("#expiration_black").addClass('hide');

    });
    $("#scheduleChange").click(function(){
        $("#expiration_div").removeClass('hide');
        $("#expiration_black").removeClass('hide');
    });
    $("#immediateRevenueChange").click(function(){
        $("#revenue_div").addClass('hide');
        $("#revenue_black").addClass('hide');
    });
    $("#scheduleRevenueChange").click(function(){
        $("#revenue_div").removeClass('hide');
        $("#revenue_black").removeClass('hide');
    });


</script>