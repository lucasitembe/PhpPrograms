<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="<?= base_url('chop') ?>"> Home</a></li>
            <li><i class="fa fa-laptop"></i> Dashboard</li>						  	
        </ol>
    </div>
</div>
<div class="row" >
    <div class="col-xs-2"></div>
    <div class="col-xs-8">
        <div class="panel panel-primary ">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Create budget </h3>
            </div>
            <div class="panel-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a class="btn btn-primary" href="#alloc" aria-controls="home" role="tab" data-toggle="tab">Income Projection</a></li>
                    <li role="presentation"><a class="btn btn-primary" href="#ehms" aria-controls="profile" role="tab" data-toggle="tab"> eHMS based Income Projection</a></li>
                    <!--                    <li role="presentation"><a class="btn btn-primary" href="#gfs1" aria-controls="profile" role="tab" data-toggle="tab">
                                                View Projection</a></li>-->
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="alloc">   
                        <center style="font-family:Times new roman"><h2>Revenue Projection</h2></center>
                        <?php
                        $attributes = array('class' => 'form-group');
                        echo form_open('fund/fundAllocAction_gacc', $attributes);
                        ?> 
                        <div class="row" style="">  		
                            <div class="col-xs-3"><label>Revenue centre:</label></div>
                            <div class="col-xs-7">
                                <div class="form-group ">
                                    <select name="source_fund"  class="form-control" placeholder="GFS code" aria-describedby="basic-addon1" required >
                                        <option value="" disabled selected>--Select Revenue centre--</option>
                                        <?php foreach ($dept as $value) { ?>
                                            <option value="<?php echo $value['dept_id']; ?>"><?php echo $value['dept_name']; ?></option>
                                        <?php } ?>	
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-2"></div>
                        </div>
                        <div class="row" style="">  		
                            <div class="col-xs-3"><label>Ledger:</label></div>
                            <div class="col-xs-7">
                                <div class="form-group ">
                                    <select name="ledger_id"  class="form-control" placeholder="GFS code" aria-describedby="basic-addon1" required >
                                        <option value="" disabled selected>--Select ledger--</option>
                                        <?php foreach ($val as $value) { ?>
                                            <option value="<?php echo $value['ledger_id']; ?>"><?php echo $value['ledger_name']; ?></option>
                                        <?php } ?>	
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-2"></div>
                        </div>
                        <div class="row" style="">
                            <div class="col-xs-3"><label>Amount:</label></div>
                            <div class="col-xs-7">
                                <div class="form-group ">
                                    <input required  type="text" name="alloc_amount"  class="form-control" placeholder="Amount" aria-describedby="basic-addon1" >
                                </div>
                            </div>
                            <div class="col-xs-2"></div>
                        </div>   
                        <div class="row" style="">
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
                                    echo'<select required id="yob" type="text" name="alloc_year"  class="form-control" placeholder="Year of budget" aria-describedby="basic-addon1">';
                                    foreach ($yearList as $key => $value) {
                                        echo '<option>' . $value . '</option>';
                                    }
                                    echo'</select>';
                                    ?>

                                </div>
                            </div>
                            <div class="col-xs-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6"></div>

                            <div class="col-xs-4"> 
                                <p><a href="<?php echo base_url('chop/index'); ?>" class="btn btn-default" role="button">Cancel</a> <button class="btn btn-primary" role="button">Submit</button></p>
                            </div>
                        </div>
                        </form>
                    </div>
                    <div role="tabpanel" class="tab-pane " id="ehms">
                        <center style="font-family:Times new roman"><h2>Revenue Projection</h2></center>
                        <?php
                        $attributes = array('class' => 'form-group');
                        echo form_open('fund/fundAllocAction', $attributes);
                        ?> 
                        <div class="row" style="">  		
                            <div class="col-xs-3"><label>Revenue centre:</label></div>
                            <div class="col-xs-7">
                                <div class="form-group ">
                                    <select name="source_fund"  class="form-control" placeholder="GFS code" aria-describedby="basic-addon1" required >
                                        <option value="" disabled selected>--Select Revenue centre--</option>
                                        <?php foreach ($deptEhms as $value) { ?>
                                            <option value="<?php echo $value['dept_id']; ?>"><?php echo $value['dept_name']; ?></option>
                                        <?php } ?>	
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-2"></div>
                        </div>
                        <div class="row" style="">
                            <div class="col-xs-3"><label>Amount:</label></div>
                            <div class="col-xs-7">
                                <div class="form-group ">
                                    <input required  type="text" name="alloc_amount"  class="form-control" placeholder="Amount" aria-describedby="basic-addon1" >
                                </div>
                            </div>
                            <div class="col-xs-2"></div>
                        </div>
                        <div class="row" style="">

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

                                    echo'<select required id="yob" type="text" name="alloc_year"  class="form-control" placeholder="Year of budget" aria-describedby="basic-addon1">';
                                    foreach ($yearList as $key => $value) {
                                        echo '<option>' . $value . '</option>';
                                    }
                                    echo'</select>';
                                    ?>

                                </div>
                            </div>
                            <div class="col-xs-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6"></div>

                            <div class="col-xs-4"> 
                                <p><a href="<?php echo base_url('chop/index'); ?>" class="btn btn-default" role="button">Cancel</a> <button class="btn btn-primary" role="button">Submit</button></p>
                            </div>
                        </div>
                        </form>
                    </div>                  
                    <div role="tabpanel" class="tab-pane " id="gfs1">  
                        <div class="row"  style="margin-top:0px">
                            <center style="font-family:Times new roman"><h2>View fund allocation </h2></center>
                            <div class="col-xs-3"><label>Year of budget:</label></div>
                            <div class="col-xs-7">
                                <div class="form-group ">
                                    <select id="alloc_yob"name="alloc_yob"  class="form-control" placeholder="GFS code" aria-describedby="basic-addon1" required >
                                        <option value="" disabled selected>--Select Year of budget--</option>
                                        <?php foreach ($year as $value) { ?>
                                            <option value="<?php echo $value['alloc_year']; ?>"><?php echo $value['alloc_year']; ?></option>
                                        <?php } ?>	
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-2"></div>
                        </div>
                        <div class="row" style="">

                            <div id="view_alloc" class="col-xs-12">
                                <div align="center" style="display:none" id="progressStatus"><img src="<?= base_url('assets/images/ajax-loader_1.gif'); ?>" width="" style="border-color:white "></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-2"></div>
    </div>
</div>
<?php
if (isset($_GET['Error'])) {
    ?>
    <script>
        window.alert('Fund already allocated!');
    </script>
<?php }
?>