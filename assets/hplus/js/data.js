/**
 * Created by Duo Nuo on 2016/8/14.
 */
    $(function(){
        var e={series:{lines:{show:!0,lineWidth:2,fill:!0,
                fillColor:{colors:[{opacity:0},{opacity:0}]}}},
                xaxis:{tickDecimals:0},
                colors:["#1ab394"],
                grid:{color:"#999999",hoverable:!0,clickable:!0,tickColor:"#D4D4D4",borderWidth:0},
                legend:{show:!1},
                tooltip:!0,
                tooltipOpts:{content:"x: %x, y: %y"}},
            o={label:"bar",data:[[1,34],[2,25],[3,19],[4,34],[5,32],[6,44],[7,33]]};
        $.plot($("#flot-line-chart"),[o],e)})
