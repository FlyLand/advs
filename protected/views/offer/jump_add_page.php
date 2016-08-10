<?php require_once dirname(dirname(__FILE__)) . '/sidebar.php';?>
<style>
    .am-form div{
        padding-top: 10px;
    }
</style>
<!-- content start -->
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Add Offer Jump</strong> </div>
    </div>

    <form class="am-form" action="<?php echo $this->createUrl('offer/jumpoffer',array('type'=>'add'));?>" method="post" id="doForm">
        <div>
            <div class="am-u-sm-4 am-u-md-2 am-text-right">Offers:</div>
            <div class="am-u-sm-6 am-u-end">
                <select id="offers" name="offers[]" multiple data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary',maxHeight: 200,searchBox: 1}" style="display: none;padding-top: 20px">
                    <?php foreach($offers as $offer){ ?>
                        <option  value="<?php echo $offer['id'];?>"><?php echo
                            $offer['id'];?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="am-u-sm-12">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">Affiliates:</div>
            <div class="am-u-sm-6 am-u-end">
                <select id="affiliates" name="affiliates[]" multiple data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary',maxHeight: 200,searchBox: 1}" style="display: none;padding-top: 20px">
                    <?php foreach($affiliates as $affiliate){ ?>
                        <option  value="<?php echo $affiliate['id'];?>"><?php echo
                            $affiliate['id'] . '    ' . $affiliate['company'];?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="am-u-sm-12">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">Type:</div>
            <div  class="element am-u-sm-3 am-u-end">
                <select data-am-selected id="AdvertiserStatus"  onchange="changed(this)" name="offer_types" >
                    <option value="0">The Offer Id In System</option>
                    <option value="1">The Url Give By Yourself</option>
                </select>
            </div>
        </div>


        <div class="am-u-sm-12" id="jump_offer_id_div">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">Offer Id:</div>
            <div  class="am-u-sm-3 am-u-end">
                <input type="text" id="jump_offer_id" name="jump_offer_id" class="am-form-field">
            </div>
            <p>The offer id in the system</p>
        </div>

        <div id="offer_url_div" class="am-u-sm-12" style="display: none">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">Offer Url:</div>
            <div  class="am-u-sm-3 am-u-end">
                <input type="text" id="offer_url" name="offer_url" class="am-form-field">
            </div>
            <p>The url what you want to redirect like:www.baidu.com</p>
        </div>

        <div class="am-u-sm-12">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">Countries Status:</div>
            <div  class="element am-u-sm-3 am-u-end">
                <select data-am-selected id="CountryStatus" name="country_status" onchange="countryStatus(this)">
                    <option value="0">Close</option>
                    <option value="1">Open</option>
                </select>
            </div>
        </div>

        <div class="am-u-sm-12" id="countries" style="display: none">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">Countries:</div>
            <div  class="am-u-sm-3 am-u-end">
                <input type="text" id="countries" name="countries" class="am-form-field">
            </div>
            <p>If you want limit their jump just in these countries,you can edit it else not;
                you must use Chinese name and separate them with commas</p>
        </div>

        <div class="am-u-sm-12">
            <div class="am-u-sm-4 am-u-md-2 am-text-right">Status:</div>
            <div  class="element am-u-sm-3 am-u-end">
                <select data-am-selected id="AdvertiserStatus" name="status" >
                    <option value="1">Active</option>
                    <option value="0">Pending</option>
                </select>
            </div>
        </div>

        <div  class="am-vertical-align-middle" style="padding-top: 40%;padding-left: 100px;display: block">
            <button type="submit" class="am-btn am-btn-primary">Save</button>
        </div>
    </form>
</div>

<div class="am-modal am-modal-alert" tabindex="-1" id="my-alert">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">OFFER</div>
        <div class="am-modal-bd" id="alertContent">

        </div>
        <div class="am-modal-footer">
            <span class="am-modal-btn">OK</span>
        </div>
    </div>
</div>

<script>
    function changed(obj){
        if(obj.value == 0){
            $('#jump_offer_id_div').show();
            $('#offer_url_div').hide();
        }else{
            $('#jump_offer_id_div').hide();
            $('#offer_url_div').show();
        }
    }
</script>
<script>
    function countryStatus(obj){
        if(obj.value == 1){
            $('#countries').show();
        }else{
            $('#countries').hide();
        }
    }
</script>