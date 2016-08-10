<?php require_once dirname(dirname(__FILE__)) . '/sidebar.php';?>
<style>
    div{
        padding-top: 10px;
    }
    .am-form label{
        color: red;
    }
</style>
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">Affiliate Edit</strong> </div>
    </div>
    <div class="am-g am-g-fixed">
        <form id="doForm" action="<?php echo $this->createUrl('affiliates/update',array('id'=>$affiliate['id']));?>" method="post">
            <div class="am-u-md-12">
                <div class="am-panel am-panel-default">
                    <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-4'}">Details<span class="am-icon-chevron-down am-fr" ></span></div>
                    <div id="collapse-panel-4" class="am-panel-bd am-collapse am-in">
                        <div class="am-g">
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">Title:</div>
                                <div  class="am-u-sm-3 am-u-end">
                                    <input type="text" id="title" name="title" class="am-form-field" value="<?php echo $affiliate['title']?>">
                                </div>
                                <p>the title is the username for the system</p>
                            </div>
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">Account Status:</div>
                                <div  class="am-u-sm-3 am-u-end">
                                    <select data-am-selected id="AdvertiserStatus" name="status" >
                                        <option value="1">Active</option>
                                        <option value="0">Pending</option>
                                    </select>
                                </div>
                            </div>
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">Postback:</div>
                                <div  class="am-u-sm-3 am-u-end">
                                    <input type="text" id="Postback" name="postback" class="am-form-field" value="<?php echo $affiliate['postback']?>">
                                </div>
                            </div>
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">
                                    <button type="button" onclick="editAff()" class="am-btn am-btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <?php if(in_array($this->user['groupid'],$this->manager_group)){?>
            <form id="editConfig" action="<?php echo $this->createUrl('affiliates/config',array('id'=>$affiliate['id']));?>" method="post">
                <div class="am-u-md-12">
                    <div class="am-panel am-panel-default">
                        <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-2'}">Config<span class="am-icon-chevron-down am-fr" ></span></div>
                        <div id="collapse-panel-2" class="am-panel-bd am-collapse am-in">
                            <div class="am-g">
                                <div class="am-u-sm-12">
                                    <div class="am-u-sm-5 am-u-md-5 am-text-right">Optimal Offer:</div>
                                    <div   class="am-u-sm-3 am-u-end">
                                        <select id="optimal_offer" name="optimal_offer">
                                            <option>Non</option>
                                            <?php if(!empty($offers)){
                                                foreach($offers as $offer){
                                                    echo "<option value='{$offer['id']}'>{$offer['id']}{$offer['name']}</option>";
                                                }
                                            }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="am-u-sm-12">
                                    <div class="am-u-sm-5 am-u-md-5 am-text-right">Optimal Country:</div>
                                    <div  class="am-u-sm-3 am-u-end">
                                        <select id="optimal_country" name="optimal_country">
                                            <option>Non</option>
                                            <?php if(!empty($countries)){
                                                foreach($countries as $country){
                                                    echo "<option value='{$country['id']}'>{$country['name']}</option>";
                                                }
                                            }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="am-u-sm-12">
                                    <div class="am-u-sm-5 am-u-md-5 am-text-right">Optimal Connect:</div>
                                    <div  class="am-u-sm-3 am-u-end">
                                        <select id="optimal_country" name="optimal_country" data-am-selected>
                                            <option value='0'>Unknown</option>;
                                            <option value='1'>Wifi</option>;
                                            <option value='2'>Not Wifi</option>;
                                        </select>
                                    </div>
                                </div>
                                <div class="am-u-sm-12">
                                    <div class="am-u-sm-5 am-u-md-5 am-text-right">
                                        <button type="button" onclick="editConfig()" class="am-btn am-btn-primary">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        <?php } ?>
        <form id="change_pd" action="<?php echo $this->createUrl('affiliates/update',array('id'=>$affiliate['id']));?>" method="post">
            <div class="am-u-md-12">
                <div class="am-panel am-panel-default">
                    <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-1'}">Setting<span class="am-icon-chevron-down am-fr" ></span></div>
                    <div id="collapse-panel-1" class="am-panel-bd am-collapse am-in">
                        <div class="am-g">
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">Password:</div>
                                <label>*</label>
                                <div  class="am-u-sm-3 am-u-end">
                                    <input type="text" id="ad_password" name="password"  class="am-form-field" value="">
                                </div>
                            </div>
                            <div class="am-u-sm-12">
                                <div class="am-u-sm-5 am-u-md-5 am-text-right">
                                    <button type="button" onclick="editPassword()" class="am-btn am-btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
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
    var editPassword = function () {
        if(confirm('Are you sure to reset the password?')){
            $("#change_pd").submit();
        }
    };

    var editConfig = function(){

    };

    var chkEmail = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
    var editAff = function(){
        $("#doForm").submit();
    }
</script>