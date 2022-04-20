<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="<?= site_url('Home') ?>"> Dashboard</a></li>
            <li><i class="fa fa-laptop"></i><a href="<?= site_url('gledger') ?>"> General Ledger</a></li>							  	
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 form-inline ">
        <div class="form-group">
            <div class="col-md-2 col-sm-4 col-xs-12">
                <input type="text"  id="start_date" class="form-control form-inline" placeholder="Start Date" value="<?= (count($currentAccountYear) > 0) ? $currentAccountYear->account_year : 'Finance Year Not Set' ?>"/>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-2 col-sm-4 col-xs-12">
                <input type="text"  id="end_date" class="form-control form-inline" placeholder="End Date"  value="<?= date('Y-m-t', strtotime(date('Y-m'))) ?>">
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-2 col-sm-4 col-xs-12">
                <select class="form-control form-inline"  >
                    <option>--All Sections--</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-2 col-sm-4 col-xs-12">
                <select class="form-control form-inline"  >
                    <option>--All Accounts--</option>
                </select>
            </div>
        </div>

    </div>
</div>
<div style="margin-top: 15px" class="row">
    <div class="col-lg-12 form-inline ">
        <div class="form-group">
            <div class="col-md-2 col-sm-4 col-xs-12">
                <select class="form-control form-inline"  >
                    <option>--All Ledgers--</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-2 col-sm-4 col-xs-12">
                <select class="form-control form-inline"  >
                    <option>--All Groups--</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-8 col-sm-8 col-xs-12">
                <select class="form-control form-inline"  >
                    <option>--All Users--</option>
                </select>
            </div>
        </div>
        <?php
        if (count($currentAccountYear) > 0) {
            ?>
            <div class="form-group">
                <div class="col-md-2 col-sm-4 col-xs-12">
                    <input type="button" onclick="getJurnalReport()" class="btn btn-primary" value="Get Data"/>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<br/>
</div>
<div class="row">
    <div class="col-sm-12">
        <div id='table-options' class="pull-right">
            <a href="" id="ajaxUpdateContainerPdf" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i> Pdf</a> 
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Journal Entry Report </h3>
            </div>
            <div class="panel-body" id="ajaxUpdateContainer">
           
            </div>
        </div>
    </div>
</div>