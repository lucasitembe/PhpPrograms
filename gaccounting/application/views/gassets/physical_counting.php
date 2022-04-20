<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="<?= base_url('gassets') ?>">Assets Management</a></li>
          
            <li><i class="fa fa-search"></i> Asset Tracking</li>						  	
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div id='table-options' class="pull-right">
             
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Asset Tracking </h3>
            </div>
            <div class="panel-body">
            <div class="row form-inline ">
                <div class="pull-right">
                <div class="form-group">
                    <div class="col-md-8">
                    <input type="text" name="search_key_word" id="search_key_word"  placeholder="Search Asset" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-4">
                        <button class="btn btn-primary" onclick="getAssetDetails()"><i class="fa fa-search"></i> Search</button>
                    </div>
                </div> 
                </div>
            </div>
            <!-- Asset Details-->
            <div id="ajaxUpdateContainer">

            </div>
            <!-- /Asset Details -->
                    <center> <?php //echo $links; ?></center>
            </div>
        </div>