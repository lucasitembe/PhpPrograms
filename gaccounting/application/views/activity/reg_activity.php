<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="<?= base_url('chop') ?>"> Home</a></li>
            <li><i class="fa fa-laptop"></i> Dashboard</li>						  	
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xs-2"></div>
    <div class="col-xs-8">
        <div class="panel panel-primary ">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Create budget </h3>
            </div>
            <div class="panel-body">            <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a class="btn btn-primary" href="#reg" aria-controls="home" role="tab" data-toggle="tab">Register activities</a></li>
                    <li role="presentation"><a class="btn btn-primary" href="#upload" aria-controls="profile" role="tab" data-toggle="tab">Upload activities</a></li>
                    <li role="presentation"><a class="btn btn-primary" href="#view" aria-controls="profile" role="tab" data-toggle="tab">View activities</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="reg">
                        <?php
                        $attributes = array('class' => 'form-group');
                        echo form_open('activity/reg_activity_action', $attributes);
                        ?> 
                        <center style="font-family:Times new roman"><h2>Activities Registration </h2></center>
                        <div class="row" style="margin-top:0px">		   		
                            <div class="col-xs-3"><label>Activiy title:</label></div>
                            <div class="col-xs-7">
                                <div class="form-group ">
                                    <input required id="gfs_code" type="text" name="act_title"  class="form-control" placeholder="Activiy title" aria-describedby="basic-addon1" >
                                </div>
                            </div>
                            <div class="col-xs-2"></div>
                        </div>	
                        <div class="row" style="">		   		
                            <div class="col-xs-3"><label>Activiy description:</label></div>
                            <div class="col-xs-7">
                                <div class="form-group ">
                                    <input required id="gfs_code" type="text" name="act_desc"  class="form-control" placeholder="Activiy description" aria-describedby="basic-addon1" >
                                </div>
                            </div>
                            <div class="col-xs-2"></div>
                        </div>	
                        <div class="row" style="">

                            <div class="col-xs-3"><label>Year of budget:</label></div>
                            <div class="col-xs-7">
                                <div class="form-group ">
                                    <input required id="gfs_code" type="text" name="act_yob"  class="form-control" placeholder="Year of budget" aria-describedby="basic-addon1" >
                                </div>
                            </div>
                            <div class="col-xs-2"></div>
                        </div>
                        <div class="row" style="">

                            <div class="col-xs-3"><label>Cost centre:</label></div>
                            <div class="col-xs-7">
                                <div class="form-group">
                                    <select name="act_dept"  class="form-control" placeholder="GFS code" aria-describedby="basic-addon1" required >
                                        <option value="" disabled selected>--Select Department--</option>
                                        <?php foreach ($val3 as $value) { ?>
                                            <option value="<?php echo $value['dept_id']; ?>"><?php echo $value['dept_name']; ?></option>
                                        <?php } ?>	
                                    </select>			   </div>
                            </div>
                            <div class="col-xs-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-7"></div>
                            <div class="col-xs-3"> 
                                <p><a href="<?php echo base_url('chop/index'); ?>" class="btn btn-default" role="button">Cancel</a> <button class="btn btn-primary" role="button">Submit</button></p>
                            </div>
                        </div>
                        </form>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="upload" >
                        <?php
                        $attribute = array('class' => 'form-group', 'enctype' => 'multipart/form-data');
                        echo form_open('activity/upload_activities', $attribute);
                        ?> 
                        <div class="row" style="margin-top:30px">
                            <div class="col-xs-2"></div>
                            <div class="col-xs-8">
                                <label class="btn btn-default btn-file">
                                    <input type="file" name="csv_act" />
                                </label>
                                </br>
                                </br>
                                <p><a href="#" class="btn btn-default" role="button">Cancel</a><input name="import" type="submit" value="Upload" class="btn btn-primary" /></p>
                            </div>
                            <div class="col-xs-2"></div>
                        </div>
                        </form>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="view">
                        <div class="row"  style="margin-top:0px">
                            <center style="font-family:Times new roman"><h2>View registered activities </h2></center>
                            <div class="col-xs-3"><label>Year of budget:</label></div>
                            <div class="col-xs-7">
                                <div class="form-group ">
                                    <select id="yoa"name="yoa"  class="form-control" placeholder="GFS code" aria-describedby="basic-addon1" required >
                                        <option value="" disabled selected>--Select year of budget--</option>
                                        <?php foreach ($val1 as $value) { ?>
                                            <option value="<?php echo $value['budget_year']; ?>"><?php echo $value['budget_year']; ?></option>
                                        <?php } ?>	
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-2"></div>
                        </div>
                        <div class="row" style="">

                            <div class="col-xs-3"><label>Cost centre:</label></div>
                            <div class="col-xs-7">
                                <div class="form-group">
                                    <select id="act_dept" name="act_dept"  class="form-control" placeholder="GFS code" aria-describedby="basic-addon1" required >
                                        <option value="" disabled selected>--Select Department--</option>
                                        <?php foreach ($val3 as $value) { ?>
                                            <option value="<?php echo $value['dept_id']; ?>"><?php echo $value['dept_name']; ?></option>
                                        <?php } ?>	
                                    </select>			   </div>
                            </div>
                            <div class="col-xs-2"></div>
                        </div>
                        <div align="center" style="display:none" id="progressStatus"><img src="<?= base_url('assets/images/ajax-loader_1.gif'); ?>" width="" style="border-color:white "></div>
                        <div id="jibu">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-2"></div>
    </div>  
</div>

