<?php
/**
 * ����offer
 */
include_once dirname(dirname(__FILE__)) . '/sidebar.php';
?>
<script src="assets/js/ui/jquery.ui.core.js"></script>
<script src="assets/js/ui/jquery.ui.widget.js"></script>
<script src="assets/js/ui/jquery.ui.position.js"></script>
<script src="assets/js/ui/jquery.ui.menu.js"></script>
<script src="assets/js/ui/jquery.ui.autocomplete.js"></script>
<link rel="stylesheet" href="assets/css/ui/demos.css">
<style>
    img{
        width: 120px;height: 80px;overflow: hidden;
        min-width: 120px;  min-height: 80px
    }
</style>
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Stat Report</strong> / <small></small></div>
    </div>

    <div class="am-tabs am-margin" >
        <form action="<?php echo $this->createUrl('report/dailyreport',array('type'=>'search'));?>" method="post">
        <div class="am-panel am-panel-default am-form">
            <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-1'}"><b>Options:</b><span class="am-icon-chevron-down am-fr"></span>
<!--                <div style="margin-right:30px;float: right"><a href="--><?php //echo $this->createUrl('report/editdetail',array('id'=>$offers['id']));?><!--">Edit</a></div>-->
            </div>
            <div class="am-panel-bd am-collapse am-in" id="collapse-panel-1" style="height: 1000px">
                <div class="am-g am-margin-top">
                    <p>Data:</p>
                    <div class="am-u-sm-3">
                        <div class="am-u-sm-12">
                            <input type="checkbox" <?php echo in_array('company',$select) ? 'checked' : '' ?> name="company"> Affiliate
                        </div>
                        <div class="am-u-sm-12">
                            <input type="checkbox" <?php echo in_array('affiliate_id',$select) ? 'checked' : '' ?> name="affiliate_id"> Affiliate ID
                        </div>
                        <div class="am-u-sm-12">
                            <input type="checkbox" <?php echo in_array('reference_id',$select) ? 'checked' : '' ?> name="reference_id"> Affiliate Reference ID
                        </div>
<!--                        <div class="am-u-sm-12">-->
<!--                            <input type="checkbox" --><?php //echo in_array('affiliate_source',$select) ? 'checked' : '' ?><!-- name="affiliate_source"> Affiliate Source-->
<!--                        </div>-->
                        <div class="am-u-sm-12">
                            <input type="checkbox" <?php echo in_array('affiliate_manager',$select) ? 'checked' : '' ?> name="affiliate_manager">  Affiliate Manager
                        </div>
                    </div>

<!--                    <div class="am-u-sm-3">-->
<!--                        <div class="am-u-sm-12">-->
<!--                            <input type="checkbox" --><?php //echo in_array('sub1',$select) ? 'checked' : '' ?><!-- name="sub1"> Affiliate Sub ID 1-->
<!--                        </div>-->
<!--                        <div class="am-u-sm-12">-->
<!--                            <input type="checkbox" --><?php //echo in_array('sub2',$select) ? 'checked' : '' ?><!-- name="sub2"> Affiliate Sub ID 2-->
<!--                        </div>-->
<!--                        <div class="am-u-sm-12">-->
<!--                            <input type="checkbox" --><?php //echo in_array('sub3',$select) ? 'checked' : '' ?><!-- name="sub3"> Affiliate Sub ID 3-->
<!--                        </div>-->
<!--                        <div class="am-u-sm-12">-->
<!--                            <input type="checkbox" --><?php //echo in_array('sub4',$select) ? 'checked' : '' ?><!-- name="sub4"> Affiliate Sub ID 4-->
<!--                        </div>-->
<!--                        <div class="am-u-sm-12">-->
<!--                            <input type="checkbox" --><?php //echo in_array('sub5',$select) ? 'checked' : '' ?><!-- name="sub5"> Affiliate Sub ID 5-->
<!--                        </div>-->
<!--                    </div>-->

                    <div class="am-u-sm-3 am-u-end">
                        <div class="am-u-sm-12">
                            <input type="checkbox" <?php echo in_array('offer',$select) ? 'checked' : '' ?> name="offer"> Offer
                        </div>
<!--                        <div class="am-u-sm-12">-->
<!--                            <input type="checkbox" --><?php //echo in_array('reference_id',$select) ? 'checked' : '' ?><!-- name="reference_id"> Offer Reference ID-->
<!--                        </div>-->
<!--                        <div class="am-u-sm-12">-->
<!--                            <input type="checkbox" --><?php //echo in_array('goal',$select) ? 'checked' : '' ?><!-- name="goal"> Goal-->
<!--                        </div>-->
<!--                        <div class="am-u-sm-12">-->
<!--                            <input type="checkbox" --><?php //echo in_array('goal_id',$select) ? 'checked' : '' ?><!-- name="goal_id"> Goal ID-->
<!--                        </div>-->
<!--                        <div class="am-u-sm-12">-->
<!--                            <input type="checkbox" --><?php //echo in_array('offer_url',$select) ? 'checked' : '' ?><!-- name="offer_url"> Offer URL-->
<!--                        </div>-->
                    </div>
                </div>
                <br>
                <div class="am-g am-margin-top">
                    <div class="am-u-sm-3">
                        <div class="am-u-sm-12">
                            <input type="checkbox" <?php echo in_array('advertiser',$select) ? 'checked' : '' ?> name="advertiser"> Advertiser
                        </div>
                        <div class="am-u-sm-12">
                            <input type="checkbox" <?php echo in_array('advertiser_id',$select) ? 'checked' : '' ?> name="advertiser_id"> Advertiser ID
                        </div>
                        <div class="am-u-sm-12">
                            <input type="checkbox" <?php echo in_array('advertiser_manager',$select) ? 'checked' : '' ?> name="advertiser_manager"> Advertiser Manager
                        </div>
                    </div>
                    <div class="am-u-sm-3">
<!--                        <div class="am-u-sm-12">-->
<!--                            <input type="checkbox" --><?php //echo in_array('campaign',$select) ? 'checked' : '' ?><!-- name="campaign"> Campaign-->
<!--                        </div>-->
<!--                        <div class="am-u-sm-12">-->
<!--                            <input type="checkbox" --><?php //echo in_array('category',$select) ? 'checked' : '' ?><!-- name="category"> Category-->
<!--                        </div>-->
<!--                        <div class="am-u-sm-12">-->
<!--                            <input type="checkbox" --><?php //echo in_array('payout_type',$select) ? 'checked' : '' ?><!-- name="payout_type"> Payout Type-->
<!--                        </div>-->
<!--                        <div class="am-u-sm-12">-->
<!--                            <input type="checkbox" --><?php //echo in_array('revenue_type',$select) ? 'checked' : '' ?><!-- name="revenue_type"> Revenue Type-->
<!--                        </div>-->
                    </div>

                    <div class="am-u-sm-3 am-u-end">
                        <div class="am-u-sm-12">
                            <input type="checkbox" <?php echo in_array('country',$select) ? 'checked' : '' ?> name="country"> Country
                        </div>
<!--                        <div class="am-u-sm-12">-->
<!--                            <input type="checkbox" --><?php //echo in_array('browser',$select) ? 'checked' : '' ?><!-- name="browser"> Browser-->
<!--                        </div>-->
<!--                        <div class="am-u-sm-12">-->
<!--                            <input type="checkbox" --><?php //echo in_array('currency',$select) ? 'checked' : '' ?><!-- name="currency"> Currency-->
<!--                        </div>-->
                    </div>
                </div>

                <div class="am-g am-margin-top">
                    <p>Statistics:</p>
                    <div class="am-u-sm-3">
                        <div class="am-u-sm-12">
                            <input type="checkbox" <?php echo in_array('impressions',$select) ? 'checked' : '' ?> name="impressions"> Impressions
                        </div>
                        <div class="am-u-sm-12">
                            <input type="checkbox" <?php echo in_array('clicks',$select) ? 'checked' : '' ?> name="clicks"> Clicks
                        </div>
                        <div class="am-u-sm-12">
                            <input type="checkbox" <?php echo in_array('revenue',$select) ? 'checked' : '' ?> name="revenue"> Revenue
                        </div>
                    </div>
                    <div class="am-u-sm-3">
                        <div class="am-u-sm-12">
                            <input type="checkbox" <?php echo in_array('conversions',$select) ? 'checked' : '' ?> name="conversions"> Conversions
                        </div>
<!--                        <div class="am-u-sm-12">-->
<!--                            <input type="checkbox" --><?php //echo in_array('sales',$select) ? 'checked' : '' ?><!-- name="sales"> Sales-->
<!--                        </div>-->
                        <div class="am-u-sm-12">
                            <input type="checkbox" <?php echo in_array('unique_clicks',$select) ? 'checked' : '' ?> name="unique_clicks"> Unique Clicks
                        </div>
                    </div>
                    <div class="am-u-sm-3 am-u-end">
                        <div class="am-u-sm-12">
                            <input type="checkbox" <?php echo in_array('cost',$select) ? 'checked' : '' ?> name="cost"> Cost
                        </div>
                        <div class="am-u-sm-12">
                            <input type="checkbox" <?php echo in_array('profit',$select) ? 'checked' : '' ?> name="profit"> Profit
                        </div>
                    </div>
                </div>

                <div class="am-g am-margin-top">
                    <p>Calculations:</p>
                    <div class="am-u-sm-3">
                        <div class="am-u-sm-12">
                            <input type="checkbox" <?php echo in_array('CTR',$select) ? 'checked' : '' ?> name="CTR"> CTR
                        </div>
                        <div class="am-u-sm-12">
                            <input type="checkbox" <?php echo in_array('CPC',$select) ? 'checked' : '' ?> name="CPC"> CPC
                        </div>
                        <div class="am-u-sm-12">
                            <input type="checkbox" <?php echo in_array('RPC',$select) ? 'checked' : '' ?> name="RPC"> RPC
                        </div>
                    </div>
                    <div class="am-u-sm-3">
                        <div class="am-u-sm-12">
                            <input type="checkbox" <?php echo in_array('CR',$select) ? 'checked' : '' ?> name="CR"> CR
                        </div>
                        <div class="am-u-sm-12">
                            <input type="checkbox" <?php echo in_array('CPA',$select) ? 'checked' : '' ?> name="CPA"> CPA
                        </div>
                        <div class="am-u-sm-12">
                            <input type="checkbox" <?php echo in_array('RPA',$select) ? 'checked' : '' ?> name="RPA"> RPA
                        </div>
                    </div>
                    <div class="am-u-sm-3 am-u-end">
                        <div class="am-u-sm-12">
                            <input type="checkbox" <?php echo in_array('CPM',$select) ? 'checked' : '' ?> name="CPM"> CPM
                        </div>
                        <div class="am-u-sm-12">
                            <input type="checkbox" <?php echo in_array('RPM',$select) ? 'checked' : '' ?> name="RPM"> RPM
                        </div>
                    </div>
                </div>

                <div class="am-g am-margin-top">
                    <p>Interval:</p>
                    <div class="am-u-sm-3">
                        <div class="am-u-sm-12">
                            <input type="checkbox" <?php echo in_array('year',$select) ? 'checked' : '' ?> name="year"> Year
                        </div>
                        <div class="am-u-sm-12">
                            <input type="checkbox" <?php echo in_array('date',$select) ? 'checked' : '' ?> name="date"> Date
                        </div>
                    </div>
                    <div class="am-u-sm-3 am-u-end">
                        <div class="am-u-sm-12">
                            <input type="checkbox" <?php echo in_array('month',$select) ? 'checked' : '' ?> name="month"> Month
                        </div>
                        <div class="am-u-sm-12">
                            <input type="checkbox" <?php echo in_array('hour',$select) ? 'checked' : '' ?> name="hour"> Hour
                        </div>
                    </div>
                    <div class="am-u-sm-3 am-u-end">
                        <div class="am-u-sm-12">
                            <input type="checkbox" <?php echo in_array('week',$select) ? 'checked' : '' ?> name="week"> Week
                        </div>
                    </div>
                </div>

                    <div class="am-g am-margin-top">
                        <p>Filter:</p>
                        <div class="am-u-sm-3">
                            <div class="am-u-sm-12">
                                <input type="checkbox" <?php echo in_array('affiliates',$select) ? 'checked' : '' ?> onchange="change_status('aff')" id="check_affiliates" name="affiliates"> Affiliates
                            </div>
                            <div class="am-u-sm-12">
                                <input type="checkbox" <?php echo in_array('advertisers',$select) ? 'checked' : ''  ?> onchange="change_status('adv')" id="check_advertisers" name="advertisers"> Advertisers
                            </div>
<!--                            <div class="am-u-sm-12">-->
<!--                                <input type="checkbox" --><?php //echo in_array('payout_type',$select) ? 'checked' : '' ?><!-- onchange="change_status('pay')" id="check_payout_type" name="payout_type"> Payout Type-->
<!--                            </div>-->
                            <div class="am-u-sm-12">
                                <input type="checkbox" <?php echo in_array('creatives',$select) ? 'checked' : '' ?> onchange="change_status('creatives')" id="check_creatives" name="creatives"> Creatives
                            </div>
<!--                            <div class="am-u-sm-12">-->
<!--                                <input type="checkbox" --><?php //echo in_array('currencies',$select) ? 'checked' : '' ?><!-- onchange="change_status('curr')" id="check_currencies" name="currencies"> Currencies-->
<!--                            </div>-->
                        </div>

                        <div class="am-u-sm-3">
                            <div class="am-u-sm-12">
                                <input type="checkbox" <?php echo in_array('advertiser_managers',$select) ? 'checked' : '' ?> onchange="change_status('adv_mgr')" id="check_adv_mgr"  name="advertiser_managers"> Advertiser Managers
                            </div>
                            <div class="am-u-sm-12">
                                <input type="checkbox" <?php echo in_array('offers',$select) ? 'checked' : '' ?> onchange="change_status('offers')" id="check_offers" name="offers"> Offers
                            </div>
<!--                            <div class="am-u-sm-12">-->
<!--                                <input type="checkbox" --><?php //echo in_array('revenue_type',$select) ? 'checked' : '' ?><!-- onchange="change_status('revenue')" id="check_rev_t" name="revenue_type"> Revenue Type-->
<!--                            </div>-->
<!--                            <div class="am-u-sm-12">-->
<!--                                <input type="checkbox" --><?php //echo in_array('browsers',$select) ? 'checked' : '' ?><!-- onchange="change_status('browsers')" id="check_bro" name="browsers"> Browsers-->
<!--                            </div>-->
                        </div>
                        <div class="am-u-sm-3 am-u-end">
<!--                            <div class="am-u-sm-12">-->
<!--                                <input type="checkbox" --><?php //echo in_array('campaigns',$select) ? 'checked' : '' ?><!-- onchange="change_status('camp')" id="check_camp"  name="campaigns"> Campaigns-->
<!--                            </div>-->
                            <div class="am-u-sm-12">
                                <input type="checkbox" <?php echo in_array('countries',$select) ? 'checked' : '' ?> onchange="change_status('geo')" id="check_county" name="countries"> Countries
                            </div>
                            <div class="am-u-sm-12">
                                <input type="checkbox" <?php echo in_array('non_zero_revenue',$select) ? 'checked' : '' ?> id="check_non" name="non_zero_revenue"> Non-zero Revenue
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>
                    <div id="div_aff" class="am-u-sm-12" style="margin-top: 10px;display: none;">
                        <div class="am-u-sm-2"><p>Affiliates:</p></div>
                        <div class="am-u-sm-4 am-u-end">
                            <input type="text" id="input_aff" style="width: 50%;" name="aff" class="am-form-field" />
                        </div>
                    </div>
                    <div id="div_adv_mgr" class="am-u-sm-12" style="margin-top: 10px;display: none;">
                        <div class="am-u-sm-2"><p>Advertiser Managers:</p></div>
                        <div class="am-u-sm-4 am-u-end">
                            <input type="text" id="input_adv_mgr" style="width: 50%" name="adv_mgr" class="am-form-field" />
                        </div>
                    </div>

<!--                    <div id="div_input_camp" class="am-u-sm-12" style="margin-top: 10px;display: none;">-->
<!--                        <div class="am-u-sm-2"><p>Campaigns:</p></div>-->
<!--                        <div class="am-u-sm-4 am-u-end">-->
<!--                            <input type="text" id="input_camp" style="width: 50%;" name="camp" class="am-form-field" />-->
<!--                        </div>-->
<!--                    </div>-->

                    <div id="div_input_adv" class="am-u-sm-12" style="margin-top: 10px;display: none;">
                        <div class="am-u-sm-2"><p>Advertisers:</p></div>
                        <div class="am-u-sm-4 am-u-end">
                            <input type="text" id="input_adv" style="width: 50%;" name="adv" class="am-form-field" />
                        </div>
                    </div>
                    <div id="div_input_offers" class="am-u-sm-12" style="margin-top: 10px;display: none;">
                        <div class="am-u-sm-2"><p>Offers:</p></div>
                        <div class="am-u-sm-4 am-u-end">
                            <input type="text" id="input_offers" style="width: 50%;" name="offers" class="am-form-field" />
                        </div>
                    </div>
                    <div id="div_input_countries" class="am-u-sm-12" style="margin-top: 10px;display: none;">
                        <div class="am-u-sm-2"><p>Countries:</p></div>
                        <div class="am-u-sm-4 am-u-end">
                            <input type="text" id="input_countries" style="width: 50%;" name="countries" class="am-form-field" />
                        </div>
                    </div>
                    <div id="div_input_pay" class="am-u-sm-12" style="margin-top: 10px;display: none;">
                        <div class="am-u-sm-2"><p>Payout Type :</p></div>
                        <div class="am-u-sm-4 am-u-end">
                            <input type="text" id="input_pay" style="width: 50%;" name="pay" class="am-form-field" />
                        </div>
                    </div>
<!--                    <div id="div_input_rev" class="am-u-sm-12" style="margin-top: 10px;display: none;">-->
<!--                        <div class="am-u-sm-2"><p>Revenue Type :</p></div>-->
<!--                        <div class="am-u-sm-4 am-u-end">-->
<!--                            <input type="text" id="input_rev" style="width: 50%;" name="rev" class="am-form-field" />-->
<!--                        </div>-->
<!--                    </div>-->
                    <div id="div_input_non" class="am-u-sm-12" style="margin-top: 10px;display: none;">
                        <div class="am-u-sm-2"><p>Non-zero Revenue:</p></div>
                        <div class="am-u-sm-4 am-u-end">
                            <input type="text" id="input_non" style="width: 50%;" name="non" class="am-form-field" />
                        </div>
                    </div>
<!--                    <div id="div_input_creatives" class="am-u-sm-12" style="margin-top: 10px;display: none;">-->
<!--                        <div class="am-u-sm-2"><p>Creatives:</p></div>-->
<!--                        <div class="am-u-sm-4 am-u-end">-->
<!--                            <input type="text" id="input_creatives" style="width: 50%;" name="creatives" class="am-form-field" />-->
<!--                        </div>-->
<!--                    </div>-->
                    <div id="div_input_browsers" class="am-u-sm-12" style="margin-top: 10px;display: none;">
                        <div class="am-u-sm-2"><p>Browsers:</p></div>
                        <div class="am-u-sm-4 am-u-end">
                            <input type="text" id="input_browsers" style="width: 50%;" name="browser" class="am-form-field" />
                        </div>
                    </div>
                    <div id="div_input_currencies" class="am-u-sm-12" style="margin-top: 10px;display: none;">
                        <div class="am-u-sm-2"><p>Currencies:</p></div>
                        <div class="am-u-sm-4 am-u-end">
                            <input type="text" id="input_currencies" style="width: 50%;" name="currencies" class="am-form-field" />
                        </div>
                    </div>
                </div>
            </div>
        <h2>Schedule</h2>
        <hr data-am-widget="divider" style="" class="am-divider am-divider-default"/>
        <div class="am-u-sm-12">
            <div class="am-u-sm-3 text-align-right">
                <p>Get results:</p>
            </div>
        </div>

        <h2>Timeframe</h2>
        <hr data-am-widget="divider" style="" class="am-divider am-divider-default"/>
        <div class="am-u-sm-12">
            <div class="">
                <p> Start and End dates:</p>
                <div class="am-alert am-alert-danger" id="my-alert" style="display: none">
                <p>The end date should not before the start date��</p>
            </div>
            <div class="am-g">
                <div class="am-u-sm-2 text-align-right">
                    <p>View data for</p>
                </div>
                <div class="am-u-sm-4">
                    <button type="button" class="am-btn am-btn-default am-margin-left" id="my-start">Start Date</button>
                    <span id="my-startDate" class="am-u-sm-8">
                        <input type="text" value="<?php echo $start_date?>" style="width: 50%;float: right;" class="am-form-field" id="startDate" name="startDate" readonly/></span>
                </div>
                <div class="am-u-sm-4 am-u-end">
                    <button type="button" class="am-btn am-btn-default am-margin-left" id="my-end">End Date</button>
                    <span id="my-endDate" class="am-u-sm-8">
                        <input type="text" value="<?php echo $end_date?>"  id="endDate" style="width: 50%;float: right;" name="endDate" class="am-form-field" readonly/></span>
                </div>
            </div>
            </div>
        </div>
        <br>
        <br>
        <br>
        <br>
        <div>
            <button type="submit" class="am-btn am-btn-warning">Run Report</button>
            <button type="button" onclick="downloadasexcel()" class="am-btn am-btn-warning">Download As Excel</button>
        </div>
        </form>
    </div>
    <div class="am-tabs am-margin" >
        <div class="am-panel am-panel-default am-form">
            <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-2'}"><b>Statistics:</b><span class="am-icon-chevron-down am-fr"></span>
            </div>
            <div class="am-panel-bd am-collapse am-in" id="collapse-panel-2" style="height: 1000px">
                <div class="am-g am-margin-top">
                    <div class="am-tabs am-margin">
                        <div class="am-u-sm-2"><p>Load Saved Report:</p></div>
                        <select data-am-selected id="hash" name="hash" ><option>111</option></select>
                        <button class="am-btn am-btn-warning am-btn-xs">GO</button>
                    </div>
                </div>

                <div class="am-panel am-panel-default">
                    <div class="am-panel-bd">
                        <table class="am-table am-table-striped am-table-hover" id="dbs">
                            <thead>
                                <tr>
                                    <?php if(!empty($table)){
                                        foreach($table as $key=>$val){
                                            echo "<td>$val</td>";
                                        }
                                    }?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($data)){
                                    foreach($data as $key=>$val){
                                        echo '<tr>';
                                        $data_val = array_values($val);
                                        foreach($data_val as $r){
                                            if(empty($r)){
                                                echo "<td>0</td>";
                                            }else{
                                                echo "<td>$r</td>";
                                            }
                                        }
                                        echo '</tr>';
                                    }
                                }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        function log( message ) {
            $( "<div>" ).text( message ).prependTo( "#log" );
            $( "#log" ).scrollTop( 0 );
        }
        $( "#input_offers" ).autocomplete({
            source: function( request, response ) {
                $.ajax({
                    url: "<?php echo $this->createUrl('report/ajaxoffers');?>",
                    dataType: "json",
                    data: {
                        featureClass: "P",
                        style: "full",
                        maxRows: 12,
                        name_startsWith: request.term,
                    },
                    success: function( data ) {
                        response( $.map( data, function( item ) {
                            return {
                                label: item.label,
                                value: item.label
                            }
                        }));
                    }
                });
            },
            minLength: 2,
            select: function( event, ui ) {
                log( ui.item ?
                "Selected: " + ui.item.label :
                "Nothing selected, input was " + this.value);
            },
            open: function() {
                $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
            },
            close: function() {
                $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
            }
        });

        $( "#input_aff" ).autocomplete({
            source: function( request, response ) {
                $.ajax({
                    url: "<?php echo $this->createUrl('report/ajaxafflist');?>",
                    dataType: "json",
                    data: {
                        featureClass: "P",
                        style: "full",
                        maxRows: 12,
                        name_startsWith: request.term,
                    },
                    success: function( data ) {
                        response( $.map( data, function( item ) {
                            return {
                                label: item.label,
                                value: item.label
                            }
                        }));
                    }
                });
            },
            minLength: 2,
            select: function( event, ui ) {
                log( ui.item ?
                "Selected: " + ui.item.label :
                "Nothing selected, input was " + this.value);
            },
            open: function() {
                $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
            },
            close: function() {
                $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
            }
        });

        $( "#input_adv_mgr" ).autocomplete({
            source: function( request, response ) {
                $.ajax({
                    url: "<?php echo $this->createUrl('report/ajaxadvmgrlist');?>",
                    dataType: "json",
                    data: {
                        featureClass: "P",
                        style: "full",
                        maxRows: 12,
                        name_startsWith: request.term,
                    },
                    success: function( data ) {
                        response( $.map( data, function( item ) {
                            return {
                                label: item.label,
                                value: item.label
                            }
                        }));
                    }
                });
            },
            minLength: 2,
            select: function( event, ui ) {
                log( ui.item ?
                "Selected: " + ui.item.label :
                "Nothing selected, input was " + this.value);
            },
            open: function() {
                $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
            },
            close: function() {
                $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
            }
        });

        $( "#input_adv" ).autocomplete({
            source: function( request, response ) {
                $.ajax({
                    url: "<?php echo $this->createUrl('report/ajaxafflist');?>",
                    dataType: "json",
                    data: {
                        featureClass: "P",
                        style: "full",
                        maxRows: 12,
                        name_startsWith: request.term,
                    },
                    success: function( data ) {
                        response( $.map( data, function( item ) {
                            return {
                                label: item.label,
                                value: item.label
                            }
                        }));
                    }
                });
            },
            minLength: 2,
            select: function( event, ui ) {
                log( ui.item ?
                "Selected: " + ui.item.label :
                "Nothing selected, input was " + this.value);
            },
            open: function() {
                $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
            },
            close: function() {
                $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
            }
        });

        $( "#input_countries" ).autocomplete({
            source: function( request, response ) {
                $.ajax({
                    url: "<?php echo $this->createUrl('report/ajaxcountries');?>",
                    dataType: "json",
                    data: {
                        featureClass: "P",
                        style: "full",
                        maxRows: 12,
                        name_startsWith: request.term,
                    },
                    success: function( data ) {
                        response( $.map( data, function( item ) {
                            return {
                                label: item.label,
                                value: item.label
                            }
                        }));
                    }
                });
            },
            minLength: 2,
            select: function( event, ui ) {
                log( ui.item ?
                "Selected: " + ui.item.label :
                "Nothing selected, input was " + this.value);
            },
            open: function() {
                $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
            },
            close: function() {
                $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
            }
        });
    });

</script>
<script type="text/javascript">
    $(function() {
        var startDate = new Date();
        var endDate = new Date();
        var $alert = $('#my-alert');
        $('#my-start').datepicker().
            on('changeDate.datepicker.amui', function (event) {
                if (event.date.valueOf() > endDate.valueOf()) {
                    $alert.find('p').text('The start date should before the end date!').end().show();
                } else {
                    $alert.hide();
                    startDate = new Date(event.date);
                    $('#startDate').val($('#my-start').data('date'));
                }
                $(this).datepicker('close');
            });

        $('#my-end').datepicker().
            on('changeDate.datepicker.amui', function (event) {
                if (event.date.valueOf() < startDate.valueOf()) {
                    $alert.find('p').text('The end date should after the start date!').end().show();
                } else {
                    $alert.hide();
                    endDate = new Date(event.date);
                    $('#endDate').val($('#my-end').data('date'));
                }
                $(this).datepicker('close');
            });
//        $('#autocomplete').autocomplete(["c++","java", "php", "coldfusion","javascript"],{
//            width: 320,
//            max: 4,
//            highlight: false,
//            multiple: true,
//            multipleSeparator: "",
//            scroll: true,
//            scrollHeight: 300
        //��ȡ���������ݵ�url
//            serviceUrl: '<?php //echo $this->createUrl('report/getauto');?>//',
//            //�����������ؼ��ʵĲ�����,���������ajax����$.post(url, {'filter' : keywords} ,function(){})�е�filter
//            paramName : 'filter',
//            transformResult: function(response) {
//                //������������������json�ַ���
//                var obj = $.parseJSON(response);
//                return {
//                    suggestions: $.map(obj.list, function(dataItem) {
//                        return { value: dataItem.right, data: dataItem.left };
//                    })
//                };
//            },
//            //ѡ��ֵ�����ݴ���
//            onSelect: function (suggestion) {
//                beneficiaryCode=suggestion.data;
//                beneficiary=suggestion.value;
//            }
//        });
    });
    var change_status = function(name){
       switch (name){
           case 'aff' :
               if($("#div_aff").css("display") == 'none'){
                   $("#div_aff").show();
               }else{
                   $("#div_aff").hide();
               }
                break;
           case 'adv' :
               if($("#div_input_adv").css("display") == 'none'){
                   $("#div_input_adv").show();
               }else{
                   $("#div_input_adv").hide();
               }
               break;

           case 'pay' :
               if($("#div_input_pay").css("display") == 'none'){
                   $("#div_input_pay").show();
               }else{
                   $("#div_input_pay").hide();
               }
               break;
           case 'creatives' :
               if($("#div_input_creatives").css("display") == 'none'){
                   $("#div_input_creatives").show();
               }else{
                   $("#div_input_creatives").hide();
               }
               break;
           case 'curr' :
               if($("#div_input_currencies").css("display") == 'none'){
                   $("#div_input_currencies").show();
               }else{
                   $("#div_input_currencies").hide();
               }
               break;
           case 'adv_mgr' :
               if($("#div_adv_mgr").css("display") == 'none'){
                   $("#div_adv_mgr").show();
               }else{
                   $("#div_adv_mgr").hide();
               }
               break;
           case 'offers' :
               if($("#div_input_offers").css("display") == 'none'){
                   $("#div_input_offers").show();
               }else{
                   $("#div_input_offers").hide();
               }
               break;
           case 'revenue' :
               if($("#div_input_rev").css("display") == 'none'){
               $("#div_input_rev").show();
           }else{
               $("#div_input_rev").hide();
           }
               break;
           case 'browsers' :
               if($("#div_input_browsers").css("display") == 'none'){
                   $("#div_input_browsers").show();
               }else{
                   $("#div_input_browsers").hide();
               }
               break;
           case 'camp' :
               if($("#div_input_camp").css("display") == 'none'){
                   $("#div_input_camp").show();
               }else{
                   $("#div_input_camp").hide();
               }
               break;
           case 'geo' :
               if($("#div_input_countries").css("display") == 'none'){
                   $("#div_input_countries").show();
               }else{
                   $("#div_input_countries").hide();
               }
               break;
           case 'non_zero' :
               if($("#div_input_non").css("display") == 'none'){
                   $("#div_input_non").show();
               }else{
                   $("#div_input_non").hide();
               }
               break;
           default : alert('error!');
       }
    }
    var downloadasexcel = function(){
        window.location.href = '<?php echo $this->createUrl('report/downloadasexcel');?>';
    }
</script>