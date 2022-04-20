<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="index.html"> Home</a></li>
            <li><i class="fa fa-laptop"></i> Dashboard</li>						  	
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-4 ">
        <div class="panel panel-primary ">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Projection </h3>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <a class="list-group-item" href="<?= base_url('fund/fund_alloc') ?>">Income Projection</a>
                     <a class="list-group-item" href="<?= base_url('budget/create_budget') ?>">Expenses Projection</a>
                    <a class="list-group-item" href="<?= base_url('budget/view_budget') ?>">View projected Expenses</a>
                     <a class="list-group-item" href="<?= base_url('fund/reg_src_fund') ?>">&nbsp;</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel panel-primary ">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Reports </h3>
            </div>
            <div class="panel-body">
                <div class="list-group">
                     <a class="list-group-item" href="<?= base_url('request/preview_vouchers') ?>">View voucher</a>
                     <a class="list-group-item" href="<?= base_url('chop/exp_report') ?>">Expenditures</a>
                     <a class="list-group-item" href="<?= base_url('fund/actualProjectionIncomegacc') ?>">Projection & Actual Income</a>
                     <a class="list-group-item" href="<?= base_url('fund/actualProjectionIncome') ?>">eHMS based Projection & Actual Income</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="panel panel-primary ">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Other </h3>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <a class="list-group-item" href="<?= base_url('request/view_dokezo') ?>">View dokezo</a>
                    <a class="list-group-item" href="<?= base_url('activity/reg_activity') ?>">Activities</a> 
                      <a class="list-group-item" href="<?= base_url('request/make_request') ?>">Dokezo</a>
                      <a class="list-group-item" href="#">Revenue centres</a>                 
<!--                    <a class="list-group-item" href="<?= base_url('fund/reg_src_fund') ?>">&nbsp;</a>-->
                </div>
            </div>
        </div>
    </div>  
</div>