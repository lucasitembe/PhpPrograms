<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="index.html"> Home</a></li>
            <li><i class="fa fa-laptop"></i><a href="<?= site_url('gledger') ?>"> General Ledger</a></li>					  	
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 ">
        <div class="panel panel-primary ">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Ledgers Based on eHMS </h3>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <a class="list-group-item" href="<?= base_url('gledger/ledgerOnConsultationType') ?>">Ledgers Based on Consultation Type</a>
                    <a class="list-group-item" href="<?= base_url('gledger/ledgerOnSponsors') ?>">Ledgers Based on Sponsor</a>
                    <a class="list-group-item" href="<?= base_url('gledger/suppledger') ?>">Ledgers Based on Suppliers</a>
                    <a class="list-group-item" href="javascript:;">&nbsp;</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel panel-primary ">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Ledgers Not Based on eHMS </h3>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <a class="list-group-item" href="<?= base_url('gledger/intledger') ?>">Manage Ledgers</a>
                    <a class="list-group-item" href="javascript:;">&nbsp;</a>
                    <a class="list-group-item" href="javascript:;">&nbsp;</a>
                </div>
            </div>
        </div>
    </div>
</div>