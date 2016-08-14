<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Offer Details</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="tabs_panels.html#">
                        <i class="fa fa-wrench"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="tabs_panels.html#">选项1</a>
                        </li>
                        <li><a href="tabs_panels.html#">选项2</a>
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
                                    <a data-toggle="collapse" data-parent="#accordion" href="tabs_panels.html#collapseOne">DETAILS</a>
                                </h5>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in">
                                <form method="post" action="<?php echo $this->createUrl('offer/updatedetail',array('id'=>$offers['id']))?>" id="edit_form"></form>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="col-sm-4">Advertiser:</div>
                                        <div class="col-sm-8">
                                            <select name="advertiser" id="advertiser" class="form-control m-b">
                                                <?php foreach ($advertises as $ad){
                                                    echo '<option  '.$ad['select'].'  value="'.$ad['id'].'">'.$ad['company'].'</option>';
                                                }?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>

                                    <div class="form-group">
                                        <div class="col-sm-4">Name:</div>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="name" id="name" value="<?php echo $offers['name']?>">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>

                                    <div class="form-group">
                                        <div class="col-sm-4">Description:</div>
                                        <div class="col-sm-8">
                                            <textarea id="description" class="form-control" name="description" rows="5"  placeholder="description" ><?php echo $offers['description']?></textarea>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>

                                    <div class="form-group">
                                        <div class="col-sm-4">Preview URL:</div>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="preview_url" id="preview_url" value="<?php echo $offers['preview_url']?>">
                                        </div>
                                    </div>

                                    <div class="hr-line-dashed"></div>

                                    <div class="form-group">
                                        <div class="col-sm-4">Default Offer URL:</div>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="offer_url" id="offer_url" value="<?php echo $offers['offer_url']?>">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>

                                    <div class="form-group">
                                        <div class="col-sm-4">Status:</div>
                                        <div class="col-sm-8">
                                            <select class="form-control m-b" name="status" id="status">
                                                <?php if($offers['status'] == '2'){
                                                    echo '<option value="2" selected>Deleted</option>';
                                                    echo '  <option value="1">Active</option>
                                    <option value="0">Pending</option>';
                                                }else if($offers['status'] == '1'){
                                                    echo '<option value="2">Deleted</option>';
                                                    echo '  <option value="1" selected>Active</option>
                                    <option value="0">Pending</option>';
                                                }else{
                                                    echo ' <option value="2">Deleted</option>
                                    <option value="1">Active</option>
                                    <option value="0" selected="selected">Pending</option>';
                                                }?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <div class="col-sm-4"> Expiration Date:</div>
                                        <div class="col-sm-8">

                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <div class="col-sm-4"> Country:</div>
                                        <div class="col-sm-8">
                                            <input class="form-control" name="country" id="country" value="<?php echo $offers['geo_targeting'] ?>" placeholder="country">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <button class="btn btn-primary" onclick="editsubmit()" type="button">Save</button>
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
</div>
<script>
    var editsubmit = function(){
        var isTrue = true;
        if(!isTrue){
            alert("do not submit repeat!");
            return;
        }

        var advertiser = $("#advertiser").val();
        var name = $("#name").val();
        var offer_url = $("#offer_url").val();

        if(!advertiser){
            alert("advertiser could't be null！");
            return ;
        }

        if(!name){
            alert("offer name could't be null！");
            return ;
        }
        document.getElementById("edit_form").submit();
    }
</script>