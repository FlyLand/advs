<?php
/**
 * 非Yii函数公共静态类库
 * @since 2011-8-22
 */
class Fc {
	public static function renderChart($chartSWF, $strURL, $strXML, $chartId, $chartWidth, $chartHeight, $debugMode=false, $registerWithJS=false, $setTransparent="") {
	$chartSWF = FCPATH .'Charts/'. $chartSWF;
	static $load_script = true;
	$script_str = '';
	if($load_script) {
		$script_str = '<SCRIPT LANGUAGE="Javascript" SRC="'.FCPATH.'JSClass/FusionCharts.js"></SCRIPT>';
		$load_script = false;
	}
	if ($strXML=="")
        $tempData = "//Set the dataURL of the chart\n\t\tchart_$chartId.setDataURL(\"$strURL\")";
    else
        $tempData = "//Provide entire XML data using dataXML method\n\t\tchart_$chartId.setDataXML(\"$strXML\")";
	// Set up necessary variables for the RENDERCAHRT
    $chartIdDiv = $chartId . "Div";
    $ndebugMode = self::boolToNum($debugMode);
    $nregisterWithJS = self::boolToNum($registerWithJS);
	$nsetTransparent=($setTransparent?"true":"false");
	// create a string for outputting by the caller
$render_chart = <<<RENDERCHART
	$script_str
	<!-- START Script Block for Chart $chartId -->
	<div id="$chartIdDiv" align="center">
		Chart.
	</div>
	<script type="text/javascript">	
		//Instantiate the Chart	
		var chart_$chartId = new FusionCharts("$chartSWF", "$chartId", "$chartWidth", "$chartHeight", "$ndebugMode", "$nregisterWithJS");
      chart_$chartId.setTransparent("$nsetTransparent");
    
		$tempData
		//Finally, render the chart.
		chart_$chartId.render("$chartIdDiv");
	</script>	
	<!-- END Script Block for Chart $chartId -->
RENDERCHART;
	return $render_chart;
}

public static function renderChartHTML($chartSWF, $strURL, $strXML, $chartId, $chartWidth, $chartHeight, $debugMode=false,$registerWithJS=false, $setTransparent="") {
    // Generate the FlashVars string based on whether dataURL has been provided
    // or dataXML.
    $strFlashVars = "&chartWidth=" . $chartWidth . "&chartHeight=" . $chartHeight . "&debugMode=" . boolToNum($debugMode);
    if ($strXML=="")
        // DataURL Mode
        $strFlashVars .= "&dataURL=" . $strURL;
    else
        //DataXML Mode
        $strFlashVars .= "&dataXML=" . $strXML;
    
    $nregisterWithJS = boolToNum($registerWithJS);
    if($setTransparent!=""){
      $nsetTransparent=($setTransparent==false?"opaque":"transparent");
    }else{
      $nsetTransparent="window";
    }
$HTML_chart = <<<HTMLCHART
	<!-- START Code Block for Chart $chartId -->
	<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="$chartWidth" height="$chartHeight" id="$chartId">
		<param name="allowScriptAccess" value="always" />
		<param name="movie" value="$chartSWF"/>		
		<param name="FlashVars" value="$strFlashVars&registerWithJS=$nregisterWithJS" />
		<param name="quality" value="high" />
		<param name="wmode" value="$nsetTransparent" />
		<embed src="$chartSWF" FlashVars="$strFlashVars&registerWithJS=$nregisterWithJS" quality="high" width="$chartWidth" height="$chartHeight" name="$chartId" allowScriptAccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" wmode="$nsetTransparent" />
	</object>
	<!-- END Code Block for Chart $chartId -->
HTMLCHART;

  return $HTML_chart;
}
	public static function boolToNum($bVal) {
        return (($bVal==true) ? 1 : 0);
    }
    
    public static function dataToxml($data = array(),$keyname='date',$keyvalue='recharge_total',$npix = '',$caption='Monthly Sales Summary',$xname='Month',$yname='Sales',$ymin='15000') {
   	$xmldata = '';
    foreach($data as &$da) {
    	$xmldata .= "<set name='$da[$keyname]' value='$da[$keyvalue]' hoverText='$da[$keyname]'/>";
    } 
    $xml = "<graph caption='$caption' subcaption='' xAxisName='$xname' yAxisMinValue='$ymin' yAxisName='$yname' decimalPrecision='0' formatNumberScale='0' numberPrefix='$npix' showNames='1' showValues='0' showAlternateHGridColor='1' AlternateHGridColor='ff5904' divLineColor='ff5904' divLineAlpha='20' alternateHGridAlpha='5'>".$xmldata."</graph>";
    return $xml;
    }
    
    public static function dataToxml_pie($data = array()) {
   	$xmldata = '';
    foreach($data as $key=>$val) {
    	$xmldata .= "<set name='$key' value='$val'/>";
    } 
    $xml = "<graph showNames='1' decimalPrecision='0'>".$xmldata."</graph>";
    return $xml;
    }
}