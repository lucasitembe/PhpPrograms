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
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Actual vs Projected </h3>
            </div>
            <div class="panel-body">
                        <div class="row"  style="margin-top:0px">
                            <center style="font-family:Times new roman"><h2>Actual vs Projected Income</h2></center>
                            <div class="col-xs-2"><label>Year of budget:</label></div>
                            <div class="col-xs-3">
                                <div class="form-group ">
                                    <select id="alloc_yob"name="alloc_yob"  class="form-control" placeholder="GFS code" aria-describedby="basic-addon1" required >
                                        <option value="" disabled selected>--Select Year of budget--</option>
                                        <?php foreach ($year as $value) { ?>
                                            <option value="<?php echo $value['alloc_year']; ?>"><?php echo $value['alloc_year']; ?></option>
                                        <?php } ?>	
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-2"> 
                                <input type="text"  id="start_date" class="form-control form-inline" placeholder="Start Date" value="<?= date('Y-m-01', strtotime(date('Y-m'))) ?>"/>
                            </div>
                            <div class="col-xs-2"> 
                                <input type="text"  id="end_date" class="form-control form-inline" placeholder="End Date"  value="<?= date('Y-m-t', strtotime(date('Y-m'))) ?>">
                            </div>
                            <div class="col-xs-3"> 
                                <input type="button" onclick="getBudgetedActual()" class="btn btn-primary" value="Get Data"/>
                            </div>
                        </div>
                        <br/>
                        <div class="row" style="">
                            <div id="ajaxUpdateContainer" class="col-xs-12">
                                <div align="center" style="display:none" id="progressStatus"><img src="<?= base_url('assets/images/ajax-loader_1.gif'); ?>" width="" style="border-color:white "></div>
                            </div>
                        </div>
             
                </div>
            </div>
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
