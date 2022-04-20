<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="<?= base_url('gledger') ?>">General Ledger</a></li>
          
            <li><i class="fa fa-list-alt"></i> Bank Reconciliation Report</li>						  	
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div id='table-options' class="pull-right">
             
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Bank Reconciliation Report </h3>
            </div>
            <div class="panel-body">
            <div class="row form-inline ">
                <div class="pull-right">
                <div class="form-group">
                    <div class="col-md-8">
                    <input type="text" name="search_key_word" id="start_date"  placeholder="Start Date" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8">
                    <input type="text" name="search_key_word" id="end_date"  placeholder="End Date" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-4">
                        <button class="btn btn-primary" onclick="getBankeconciliationReport()"><i class="fa fa-search"></i> Filter</button>
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