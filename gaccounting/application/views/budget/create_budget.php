<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="<?= base_url('chop') ?>"> Home</a></li>
            <li><i class="fa fa-laptop"></i> Dashboard</li>						  	
        </ol>
    </div>
</div>
<div class="row" >
    <div class="col-xs-12">
        <div class="panel panel-primary ">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i>Create budget (Expenses Projection) </h3>
            </div>
            <div class="panel-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a class="btn btn-primary" href="#manual" aria-controls="home" role="tab" data-toggle="tab">Create budget</a></li>
                    <li role="presentation"><a class="btn btn-primary" href="#excel" aria-controls="profile" role="tab" data-toggle="tab">Upload budget</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="manual">
                        <div class="row" style="margin-top:50px;">
                            <?php
                            $attributes = array('class' => 'form-group');
                            echo form_open('budget/create_bgt_action', $attributes);
                            ?>         
                            <div class="col-xs-6">
                                <div class="row">
                                    <div class="col-xs-3"><label>Year of budget:</label></div>
                                    <div class="col-xs-7">
                                        <div class="form-group ">
                                            <?php
                                            $starting_year = date('Y', strtotime('-10 year'));
                                            $ending_year = date('Y');
                                            $current_year = date('Y');
                                            $yearList = array();
                                            for ($starting_year; $starting_year <= $ending_year; $starting_year++) {
                                                $next = $starting_year + 1;
                                                $yearList[] = $starting_year . '/' . $next;
                                            }

                                            rsort($yearList);

                                            echo'<select required id="yob" type="text" name="bgt_year"  class="form-control" placeholder="Year of budget" aria-describedby="basic-addon1">';
                                            foreach ($yearList as $key => $value) {
                                                echo '<option>' . $value . '</option>';
                                            }
                                            echo'</select>';
                                            ?>

                                        </div>
                                    </div>			  
                                </div>
                                <div class="row">   		
                                    <div class="col-xs-3"><label>Cost centre:</label></div>
                                    <div class="col-xs-7">
                                        <div class="form-group ">
                                            <select disabld id="dept" name="c_center"  class="form-control" placeholder="GFS code" aria-describedby="basic-addon1" required >
                                                <option value="" disabled selected>--Select Department--</option>
                                                <?php foreach ($val3 as $value) { ?>
                                                    <option value="<?php echo $value['dept_id']; ?>"><?php echo $value['dept_name']; ?></option>
                                                <?php } ?>	
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-2"></div>	
                                </div>
                                <div class="row">
                                    <div class="col-xs-3"><label>Objective</label></div>
                                    <div class="col-xs-7">
                                        <div class="form-group ">
                                            <textarea  name="objective"  class="form-control" placeholder="Objective" aria-describedby="basic-addon1" ></textarea>
                                        </div>
                                    </div>
                                    <div class="col-xs-2"></div>
                                </div>	
                                <div class="row">
                                    <div class="col-xs-3"><label>Intervention</label></div>
                                    <div class="col-xs-7">
                                        <div class="form-group ">
                                            <textarea name="intervene"  class="form-control" placeholder="Intervention" aria-describedby="basic-addon1" ></textarea>
                                        </div>
                                    </div>
                                    <div class="col-xs-2"></div>
                                </div>	
                                <div class="row" style="">
                                    <div class="col-xs-3"><label>Activity:</label></div>
                                    <div class="col-xs-7">
                                        <div class="form-group ">
                                            <select id="jibu" name="activity"  class="form-control"  aria-describedby="basic-addon1" required >
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-2"></div>
                                </div>
                            </div>
                            <div class="col-xs-6">
<!--                                <div class="row" style="">
                                    <div class="col-xs-3"><label>Source of fund:</label></div>
                                    <div class="col-xs-7">
                                        <div class="form-group ">
                                            <select name="source_fund"  class="form-control" placeholder="GFS code" aria-describedby="basic-addon1" required >
                                                <option value="" disabled selected>--Source of fund--</option>
                                                <?php //foreach ($val1 as $value) { ?>
                                                    <option value="<?php //echo $value['source_fund_id']; ?>"><?php //echo $value['fund_code'] . ':' . $value['source_name']; ?></option>
                                                <?php // } ?>	
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-2"></div>
                                </div>-->
                                <div class="row" style="">		   		
                                    <div class="col-xs-3"><label>GFS code & input:</label></div>
                                    <div class="col-xs-7">
                                        <div class="form-group ">
                                            <select name="gfs_code"  class="form-control" placeholder="GFS code" aria-describedby="basic-addon1" required >
                                                <option value="" disabled selected>--Select GFS code--</option>
                                                <?php foreach ($val as $value) { ?>
                                                    <option value="<?php echo $value['code']; ?>"><?php echo $value['code'] . ':' . $value['grf_desc']; ?></option>
                                                <?php } ?>	
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-2"></div>
                                </div>
                                <div class="row" style="">	
                                    <div class="col-xs-3"><label>Unit cost:</label></div>
                                    <div class="col-xs-7">
                                        <div class="form-group ">
                                            <input type="text" name="unit_cost"  class="form-control" placeholder="Unit cost" aria-describedby="basic-addon1" >
                                        </div>
                                    </div>
                                    <div class="col-xs-2"></div>
                                </div>
                                <div class="row" style="">	
                                    <div class="col-xs-3"><label>Nbr:</label></div>
                                    <div class="col-xs-7">
                                        <div class="form-group ">
                                            <input type="text" name="nbr"  class="form-control" placeholder="Nbr" aria-describedby="basic-addon1" >
                                        </div>
                                    </div>
                                    <div class="col-xs-2"></div>
                                </div>
                                <div class="row" style="">	
                                    <div class="col-xs-3"><label>When:</label></div>
                                    <div class="col-xs-7">
                                        <div class="form-group ">
                                            <input type="text" name="when"  class="form-control" placeholder="When" aria-describedby="basic-addon1" >
                                        </div>
                                    </div>
                                    <div class="col-xs-2"></div>
                                </div>
                                <div class="row" style="">	
                                    <div class="col-xs-3"><label>By who:</label></div>
                                    <div class="col-xs-7">
                                        <div class="form-group ">
                                            <input type="text" name="by_who"  class="form-control" placeholder="By who" aria-describedby="basic-addon1" >
                                        </div>
                                    </div>
                                    <div class="col-xs-2"></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6"></div>
                                    <div class="col-xs-5"> 
                                        <p><a href="#" class="btn btn-default" role="button">Cancel</a> <button class="btn btn-primary" role="button">Submit</button></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-1"></div>
                            </form>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="excel">
                        <div class="row" style="margin-top:30px">
                            <div class="col-xs-2"></div>
                            <div class="col-xs-8">
                                <?php
                                $attribute = array('class' => 'form-group', 'enctype' => 'multipart/form-data');
                                echo form_open('budget/upload_excel', $attribute);
                                ?> 
                                <label class="btn btn-default btn-file">
                                    <input type="file" name="csv" />
                                </label>
                                </br>
                                </br>
                                <p><a href="#" class="btn btn-default" role="button">Cancel</a><input name="import" type="submit" value="Upload" class="btn btn-primary" /></p>
                            </div>
                            <div class="col-xs-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6"></div>
                            <div class="col-xs-5"> 

                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>


