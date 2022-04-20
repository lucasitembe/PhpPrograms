<div class="container" style="min-height:647px">
    <div class="row" style="margin-top:100px;">
        <div class="col-xs-2"></div>
        <div class="col-xs-8">
            <div class="well" style="min-height:400px;box-shadow: 10px 10px 5px #888888;">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a class="btn btn-primary" href="#gfs" aria-controls="home" role="tab" data-toggle="tab">Register GFS codes</a></li>
                    <li role="presentation"><a class="btn btn-primary" href="#gfs1" aria-controls="profile" role="tab" data-toggle="tab">Upload GFS codes</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="gfs">   
                        <center style="font-family:Times new roman"><h2>GFS codes Registration </h2></center>
                        <?php
                        $attributes = array('class' => 'form-group');
                        echo form_open('GfsCode/gfs_code_action', $attributes);
                        ?>
                        <div class="row" style="margin-top:60px">
                            <div class="col-xs-3"><label>GFS code:</label></div>
                            <div class="col-xs-7">
                                <div class="form-group ">
                                    <input required id="gfs_code" type="text" name="gfs_code"  class="form-control" placeholder="Enter GSF code" aria-describedby="basic-addon1" >
                                </div>
                            </div>
                            <div class="col-xs-2"></div>
                        </div>	
                        <div class="row" style="">
                            <div class="col-xs-3"><label>GFS description:</label></div>
                            <div class="col-xs-7">
                                <div class="form-group ">
                                    <input required id="gfs_code" type="text" name="gfs_desc"  class="form-control" placeholder="Enter GSF code description" aria-describedby="basic-addon1" >
                                </div>
                            </div>
                            <div class="col-xs-2"></div>
                        </div>
                        <div class="row" style="">
                            <div class="col-xs-3"><label>Unit:</label></div>
                            <div class="col-xs-7">
                                <div class="form-group ">
                                    <input  id="gfs_code" type="text" name="gfs_unit"  class="form-control" placeholder="Enter GSF unit" aria-describedby="basic-addon1" >
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
                        <div class="row" style="margin-top:30px">
                            <div class="col-xs-2"></div>
                            <div class="col-xs-8">
                                <form method="POST" action="<?php echo base_url('GfsCode/gfs_excel'); ?>" enctype="multipart/form-data" >
                                    <label class="btn btn-default btn-file">
                                        <input type="file" name="csv" />
                                    </label>
                                    </br>
                                    </br>
                                    <p><a href="#" class="btn btn-default" role="button">Cancel</a><input  type="submit" value="Upload" class="btn btn-primary" /></p>
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
<div class="col-xs-2"></div>
</div>
</div>

<?php
if (isset($_GET['error'])) {
    ?>
    <script>
        window.alert('GFS code exits');
    </script>
<?php
}
