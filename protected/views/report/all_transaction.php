
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Offer Statistics</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="graph_flot.html#">
                        <i class="fa fa-wrench"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="graph_flot.html#">选项1</a>
                        </li>
                        <li><a href="graph_flot.html#">选项2</a>
                        </li>
                    </ul>
                    <a class="close-link">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <div class="panel-body">
                    <div class="panel-group" id="accordion">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="<?php echo Yii::app()->request->getUrl();?>#collapseOne">DETAILS</a>
                                </h5>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <div class="col-sm-8">
                                        <form action="<?php echo Yii::app()->request->getUrl()?>" method="post">
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Day：</label>
                                                <div class="col-sm-10">
                                                    <input id="hello" name="count_date" value="<?php echo $count_date?>" class="laydate-icon form-control layer-date">
                                                    <button class="btn btn-primary"  type="submit">Search</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-sm-12">
                                        <table class="table table-striped table-bordered table-hover dataTables-example">
                                            <thead>
                                            <tr>
                                                <td>Affiliate Id</td>
                                                <td>Offer Id</td>
                                                <td>Impression</td>
                                                <td>Transaction Income Count</td>
                                                <td>Revenue</td>
                                                <td>Payout</td>
                                                <td>isPostbacked</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if(!empty($count_result)){
                                                foreach($count_result as $item){
                                                    echo "<tr class='gradeX'>";
                                                    echo "<td>{$item['affid']}</td>";
                                                    echo "<td>{$item['offerid']}</td>";
                                                    echo "<td>{$item['impression']}</td>";
                                                    echo "<td>{$item['revenue']}</td>";
                                                    echo "<td>{$item['payout']}</td>";
                                                    echo "<td>{$item['coun']}</td>";
                                                    if($item['ispostbacked']){
                                                        echo "<td>postbacked</td>";
                                                    }else{
                                                        echo "<td>not postbacked</td>";
                                                    }
                                                    echo "</tr>";
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
            </div>
        </div>
    </div>
</div>

<script src="<?php echo Yii::app()->params['cssPath']?>js/jquery.min.js?v=2.1.4"></script>
<script src="<?php echo Yii::app()->params['cssPath']?>js/bootstrap.min.js?v=3.3.6"></script>
<script src="<?php echo Yii::app()->params['cssPath']?>js/plugins/echarts/echarts-all.js"></script>
<script src="<?php echo Yii::app()->params['cssPath']?>js/content.min.js?v=1.0.0"></script>
<script type="text/javascript" src="http://tajs.qq.com/stats?sId=9051096" charset="UTF-8"></script>

<script src="<?php echo Yii::app()->params['cssPath']?>js/plugins/layer/laydate/laydate.js"></script>
<script>
    laydate({elem:"#hello",event:"focus"});var start={elem:"#start",format:"YYYY/MM/DD hh:mm:ss",min:laydate.now(),max:"2099-06-16 23:59:59",istime:true,istoday:false,choose:function(datas){end.min=datas;end.start=datas}};var end={elem:"#end",format:"YYYY/MM/DD hh:mm:ss",min:laydate.now(),max:"2099-06-16 23:59:59",istime:true,istoday:false,choose:function(datas){start.max=datas}};laydate(start);laydate(end);
</script>
<script type="text/javascript" src="http://tajs.qq.com/stats?sId=9051096" charset="UTF-8"></script>