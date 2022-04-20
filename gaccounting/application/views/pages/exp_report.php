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
                <input type="text"  id="start_date" class="form-control form-inline" placeholder="Start Date" value="<?= date('Y-m-01', strtotime(date('Y-m'))) ?>"/>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <input type="text"  id="end_date" class="form-control form-inline" placeholder="End Date"  value="<?= date('Y-m-t', strtotime(date('Y-m'))) ?>">
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <input type="button" onclick="getEpenditures()" class="btn btn-primary" value="Get Data"/>
            </div>
        </div>
    </div>
</div>
<br/>
<div id="ajaxUpdateContainer">
</div>

<script>
    $(document).ready(function () {
        $('#start_date,#end_date').datetimepicker({
            format: 'Y-m-d H:i',
            timepicker: true
        });

    });
</script>  
<?php
if (isset($_GET['Error'])) {
    ?>
    <script>
        window.alert('Fund already allocated!');
    </script>
<?php }
?>
