<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="<?=base_url('chop')?>"> Home</a></li>
            <li><i class="fa fa-laptop"></i> Dashboard</li>						  	
        </ol>
    </div>
</div>
<div class="row" >
        <?php
        $attributes = array('class' => 'form-group');
        echo form_open('request/make_rqst_action', $attributes);
        ?> 
        <div class="col-xs-2"></div>
        <div class="col-xs-8">
       <div class="panel panel-primary ">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Request fund </h3>
            </div>
            <div class="panel-body">
                <center style="font-family:Times new roman"><h2>Request your fund here </h2></center>
                <div class="row" style="margin-top:60px">
                    <div class="col-xs-3"><label>Amount (TSH):</label></div>
                    <div class="col-xs-7">
                        <div class="form-group ">
                            <input required type="text" name="needed_amount"  class="form-control" placeholder="Requested amount" aria-describedby="basic-addon1" >
                        </div>
                    </div>
                    <div class="col-xs-2"></div>
                </div>	
<!--                <div class="row" style="">  		
                    <div class="col-xs-3"><label>Source of fund:</label></div>
                    <div class="col-xs-7">
                        <div class="form-group ">
                            <select name="source_fund"  class="form-control" placeholder="GFS code" aria-describedby="basic-addon1" required >
                                <option value="" disabled selected>--Source of fund--</option>
                                <?php //foreach ($val1 as $value) { ?>
                                    <option value="<?php //echo $value['source_fund_id']; ?>"><?php //echo $value['fund_code'] . ':' . $value['source_name']; ?></option>
                                <?php //} ?>	
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-2"></div>
                </div>-->
                <div class="row" style="">
                    <div class="col-xs-3"><label>Cost centre:</label></div>
                    <div class="col-xs-7">
                        <div class="form-group">
                            <select name="dept"  class="form-control" placeholder="GFS code" aria-describedby="basic-addon1" required >
                                <option value="" disabled selected>--Select Department--</option>
                                <?php foreach ($val3 as $value) { ?>
                                    <option value="<?php echo $value['dept_id']; ?>"><?php echo $value['dept_name']; ?></option>
                                <?php } ?>	
                            </select>			   </div>
                    </div>
                    <div class="col-xs-2"></div>
                </div>
                <div class="row" style="">
                    <div class="col-xs-3"><label>GFS code:</label></div>
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
                    <div class="col-xs-2"></div>
                </div>
                <div class="row">
                    <div class="col-xs-3"><label>Activity:</label></div>
                    <div class="col-xs-7">
                        <div class="form-group ">
                            <select name="activity"  class="form-control" placeholder="GFS code" aria-describedby="basic-addon1" required >
                                <option value="" disabled selected>--Select Activity--</option>
                                <?php foreach ($val2 as $value) { ?>
                                    <option value="<?php echo $value['activity_id']; ?>"><?php echo $value['activity_name']; ?></option>
                                <?php } ?>	
                            </select>
                        </div>
                        <button class="btn btn-primary" role="button">Submit</button> <a href="#" class="btn btn-default" role="button">Cancel</a></p>
                    </div>
                    <div class="col-xs-2"></div>
                </div>
            </div>
        </div>
        <div class="col-xs-2"></div>
        </form>
    </div>
  </div>    
<?php
if (isset($_GET['neg'])) {
    ?>
    <script>
        $(document).ready(function ()
        {
            window.alert('Requested amount is greater than the available amount');
        }
        );
    </script>
    <?php
}

if (isset($_GET['Error'])) {
    ?>
    <script>
        $(document).ready(function () {
            window.alert('No budget allocated for that item!');
        }
        );
    </script>
    <?php
}
