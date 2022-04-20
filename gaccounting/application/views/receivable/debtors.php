<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="<?= base_url()?>"> Dashboard</a></li>
            <li><i class="fa fa-reply"></i><a href="<?= base_url()?>/receivable"> Receivable</a></li>						  	
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 form-inline ">
        <div class="form-group col-lg-offset-3">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <input type="text"  id="start_date" class="form-control form-inline" placeholder="Start Date" value="<?= date('Y-m-01', strtotime(date('Y-m')))?>"/>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <input type="text"  id="end_date" class="form-control form-inline" placeholder="End Date"  value="<?= date('Y-m-t', strtotime(date('Y-m')))?>">
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <input type="button" onclick="getDebtors()" class="btn btn-primary" value="Get Data"/>
            </div>
        </div>
    </div>
</div>
<br/>
</div>
<div class="row">
    <div class="col-lg-12">
        <div id='table-options' class="pull-right">
            <a href="#" class="btn btn-primary"><i class="fa fa-print"></i> Print</a> 
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-reply"></i> Receivables </h3>
            </div>
            <div class="panel-body" id="ajaxUpdateContainer">
                <div class="table-responsive"> 
                    <table class="table table-hover"> 
                        <thead> 
                            <tr> 
                                <th>Debtor Name</th> 
                                <th class="text-right" class="col-xs-3">Amount</th> 
                            </tr> 
                        </thead> 
                        <tbody class="tbody-backc-color"> 
                        </tbody> 
                    </table> 

                </div>

            </div>
        </div>
    </div>
</div>