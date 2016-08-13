<?php
include_once dirname(dirname(__FILE__)) . '/sidebar.php';

?>
<!DOCTYPE html>
<!--[if IE 7]><html class="ie7" lang="zh"><![endif]-->
<html lang="zh">
<link href="assets/css/timestamp/style.css" rel="stylesheet" type="text/css" />
<body>
<div class="content">
  <div class="wrapper">
    <div class="light"><i></i></div>
    <hr class="line-left">
    <hr class="line-right">
    <div class="main">
      <h1 class="title">Manage Operate Record</h1>
      <?php if(!empty($records)){
        foreach($records as $key=>$time_arr){ ?>
      <div class="year">
        <h2><a href="#"><?php echo substr($key,0,strpos($key,'-'));?><i></i></a></h2>
        <div class="list">
          <ul>
            <li class="cls highlight">
              <p class="date"><?php echo $key;?></p>
              <p class="version">&nbsp;</p>
              <div class="more">
                <?php foreach($time_arr as $recode){ ?>
                  <p class="intro"><?php echo $recode;?></p>
                <?php } ?>
              </div>
            </li>
          </ul>
        </div>
      </div>
      <?php }}?>
      </div>
  </div>
</div>
<script>
	$(".main .year .list").each(function (e, target) {
	    var $target=  $(target),
	        $ul = $target.find("ul");
	    $target.height($ul.outerHeight()), $ul.css("position", "absolute");
	}); 
	$(".main .year>h2>a").click(function (e) {
	    e.preventDefault();
	    $(this).parents(".year").toggleClass("close");
	});
	</script>
</body>
</html>
