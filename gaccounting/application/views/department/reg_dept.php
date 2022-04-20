
<div class="container" style="min-height:647px">
    <div class="row" style="margin-top:100px;">
        <div class="col-xs-2"></div>
        <div class="col-xs-8">
            <div class="well" style="min-height:400px;box-shadow: 10px 10px 5px #888888;">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a class="btn btn-primary" href="#gfs" aria-controls="home" role="tab" data-toggle="tab">Register department</a></li>
                    <li role="presentation"><a class="btn btn-primary" href="#gfs1" aria-controls="profile" role="tab" data-toggle="tab">View departments</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="gfs">   
                        <center style="font-family:Times new roman"><h2>Departments Registration </h2></center>
                            <?php
                            $attributes = array('class' => 'form-group');
                            echo form_open('department/reg_dept_action', $attributes);
                            ?>  
                            <div class="row" style="margin-top:60px">
                                <div class="col-xs-3"><label>Department name:</label></div>
                                <div class="col-xs-7">
                                    <div class="form-group ">
                                        <input required id="dept_name" type="text" name="dept_name"  class="form-control" placeholder="Department name" aria-describedby="basic-addon1" >
                                    </div>
                                </div>
                                <div class="col-xs-2"></div>
                            </div>	
                            <div class="row" style="">
                                <div class="col-xs-3"><label>Description:</label></div>
                                <div class="col-xs-7">
                                    <div class="form-group ">
                                        <textarea required id="dept_desc" type="text" name="dept_desc"  class="form-control" placeholder="Description description" aria-describedby="basic-addon1" ></textarea>
                                    </div>
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
                    <div role="tabpanel" class="tab-pane " id="gfs1">  
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
if (isset($_GET['error'])) {
    ?>
    <script>
        window.alert('GFS code exits');
    </script>
<?php }
?>