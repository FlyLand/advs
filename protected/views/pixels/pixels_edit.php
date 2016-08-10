<?php
include_once dirname(dirname(__FILE__)).'/sidebar.php';
?>
<style>
    form div{
        padding-top: 20px;
    }
</style>
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf">
            <strong class="am-text-primary am-text-lg">Edit: Conversion Pixel / URL  </strong>
        </div>
    </div>
    <div class="am-tabs am-margin" data-am-tabs="">
        <div class="am-panel am-panel-default am-form">
            <div class="am-u-sm-4 am-u-md-8 am-u-end">
                <p style="font-size: small">Input your third-party tracking pixel / server postback URL below. The system will dynamically replace the following variables when pixel is displayed: "{offer_id}" for ID of Offer, "{affiliate_id}" for ID of Affiliate, "{aff_sub}" for Affiliate Sub ID value from tracking link, "{source}" for Affiliate Source value from tracking link, "{payout}" for Offer Payout Affiliate receives for offer, "{transaction_id}" for a unique Transaction ID number and "{ran}" for a random number. </p>
            </div>
            <div style="padding-top: 100px" class="am-u-sm-4 am-u-md-4"></div>
                <form  id="edit_form" action="<?php echo $this->createUrl('pixels/pixelsedit',array('id'=>$pix['id'],'type'=>'update'))?>" method="post">
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">Offer:</div>
                        <div class="am-u-sm-8 am-u-md-6 am-u-end">
                            <select id="advertiser" disabled >
                                <option value="1"><?php echo $pix['offer']['name']?></option>
                            </select>
                        </div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">Affiliate:</div>
                        <div class="am-u-sm-8 am-u-md-6 am-u-end">
                            <select id="advertiser" disabled >
                                <option value=""><?php echo $pix['affiliate']['company']?></option>
                            </select>
                        </div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">
                            Type:
                        </div>
                        <div class="am-u-sm-8 am-u-md-6 am-u-end">
                            <select id="advertiser" disabled >
                                <option value="">Postback URL</option>
                            </select>
                        </div>
                        <div class="am-hide-sm-only am-u-md-6"></div>
                    </div>
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">
                            Postback URL:
                        </div>
                        <div class="am-u-sm-8 am-u-md-4 am-u-end">
                            <textarea name="pix_code"><?php echo $pix['code']?></textarea>
                        </div>
                    </div>
                    <div class="am-margin">
                        <button type="submit" class="am-btn am-btn-primary am-btn-xs">Save</button>
                    </div>
                   </form>
            </div>
        </div>
    </div>