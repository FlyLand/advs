<?php
include_once dirname(dirname(__FILE__)) . '/sidebar.php';
?>
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf">
            <strong class="am-text-primary am-text-lg">Excel Export </strong>
        </div>
    </div>
        <div class="container">
            <form id="myfile" name="upform" enctype="multipart/form-data" method="post" action="<?php echo $this->createUrl('report/excelexport');?>">
                <br>
                <div class="am-tabs am-margin" data-am-tabs="">
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">Type:</div>
                        <div class="am-u-sm-8 am-u-md-10">
                            <select name="type" id="type" data-am-selected="{btnWidth: 300, btnSize: 'sm', btnStyle: 'secondary',maxHeight: 200}" style="display: none;">
                                <option value="0">Invasion Android</option>
                                <option value="1">A1</option>
                                <option value="2">A2</option>
                                <option value="3">A3</option>
                                <option value="4">A4</option>
                                <option value="5">Search</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="am-tabs am-margin" data-am-tabs="">
                    <div class="am-g am-margin-top">
                        <div class="am-u-sm-4 am-u-md-2 am-text-right">Date:</div>
                        <div class="am-u-sm-4 am-u-md-10">
                            <p><input type="text" name="date" class="am-form-field" placeholder="日历组件" data-am-datepicker readonly required value="<?php echo $date_today;?>"/>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="am-u-sm-12 am-u-am-centered">
                    <div  class="am-u-sm-10 am-u-am-centered">
                        <input style="margin-top: 4%" class="am-btn am-btn-primary am-round" type="file" name="upfile" id="fileField" />
                        <input style="margin-top: 1%" class="am-btn am-btn-success am-round" id="submit" type="submit" value="upload"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $('#myfile').submit(function () {
        if(!$('#fileField').val()){
            alert('please select a photo!');
            return false;
        }
    });
</script>