<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="<?= base_url('chop') ?>"> Home</a></li>
            <li><i class="fa fa-laptop"></i> Dashboard</li>						  	
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 form-inline ">
        <div class="form-group col-lg-offset-3">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <select id="yob" name="yob"  class="form-control" placeholder="GFS code" aria-describedby="basic-addon1" required >
                    <option value="" disabled selected>--Select Year of budget--</option>
                    <?php foreach ($val1 as $value) { ?>
                        <option value="<?php echo $value['year_of_bgt']; ?>"><?php echo $value['year_of_bgt']; ?></option>
                    <?php } ?>	
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <select id="c_center" name="c_center"  class="form-control" placeholder="GFS code" aria-describedby="basic-addon1" required >
                    <option value="" disabled selected>--Select Department--</option>
                    <?php foreach ($val2 as $value) { ?>
                        <option value="<?php echo $value['dept_id']; ?>"><?php echo $value['dept_name']; ?></option>
                    <?php } ?>	
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <input type="button" onclick="getBudget()" class="btn btn-primary" value="Get Data"/>
            </div>
        </div>
    </div>
</div>
<br/>
<div id="ajaxUpdateContainer">
</div>

<?php
if (isset($_GET['null'])) {
    ?>
    <script>
        window.alert('No budget found');
    </script>
<?php }
?>