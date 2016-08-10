<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
<title>Welcome JoyDream</title>
</head>
<body>
<div id="re" style="margin-top: 10px;"></div>
<script type="text/javascript"> 
var connection = navigator.connection||navigator.mozConnection||navigator.webkitConnection||{tyep:'unknown'};
var type_text = ['unknown','ethernet','wifi','2g','3g','4g','none'];
if(typeof(connection.type) == "number"){
    connection.type_text = type_text[connection.type];
}else{
    connection.type_text = connection.type;
}
if(typeof(connection.bandwidth) == "number"){
    if(connection.bandwidth > 10){
        connection.type = 'wifi';
    }else if(connection.bandwidth > 2){
        connection.type = '3g';
    }else if(connection.bandwidth > 0){
        connection.type = '2g';
    }else if(connection.bandwidth == 0){
        connection.type = 'none';
    }else{
        connection.type = 'unknown';
    }
}
var url = "<?php
    if(empty($offer_id)){
        $offer_id = 0;
    }
    if(empty($aff_id)){
        $aff_id = 0;
    }
    if(empty($aff_sub)){
        $aff_sub = '';
    }
    if(empty($subid)){
        $subid = '';
    }
    if(empty($query)){
        $query = '';
    }
    echo $this->createUrl('redis/offerclick',array('offer_id'=>$offer_id,'aff_id'=>$aff_id,'aff_sub'=>$aff_sub,'subid'=>$subid,'query'=>$query));?>&net_type=" + connection.type;
window.location.href = url;
</script>
</body>
</html>
