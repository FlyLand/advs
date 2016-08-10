<?php
include_once dirname(dirname(__FILE__)).'/sidebar.php';
?>
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf">
            <strong class="am-text-primary am-text-lg">Offer Thumbnail </strong>
        </div>
    </div>
    <div class="am-tabs am-margin" data-am-tabs="">
        <div class="container">
            <p>Upload a thumbnail image for an offer. Supported image formats are gif, jpg, jpeg, and png. Thumbnails are scaled down to 100px width (proportionate height)</p>
            <form id="myfile" name="upform" enctype="multipart/form-data" method="post" action="<?php echo $this->createUrl('offer/offerthumbnail',array('id'=>$offer_id,'type'=>'upload'));?>">
                <div class="am-u-sm-12 am-u-am-centered">
                    <label class="am-u-sm-4" for="myfile">upload images:</label>
                    <div  class="am-u-sm-8 am-u-am-centered">
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