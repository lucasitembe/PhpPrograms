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
                <input type="button" onclick="getProfitLoss()" class="btn btn-primary" value="Get Data"/>
            </div>
        </div>
    </div>
</div>
<br/>
</div>
<div class="row">
    <div class="col-lg-12">
        <div id='table-options' class="pull-right">
            <a href="./profitloss/?report&start_date=2017-03-01&end_date=2017-05-31" class="btn btn-primary"><i class="fa fa-print"></i> Print</a> 
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Profit & Loss </h3>
            </div>
            <div class="panel-body" id="ajaxUpdateContainer">
                <div class="table-responsive"> 
                    <table class="table table-hover"> 
                        <thead> 
                            <tr> 
                                <th>Description</th> 
                                <th class="text-right">Amount</th> 
                            </tr> 
                        </thead> 
                        <tbody class="tbody-backc-color"> 
                            <?php
                            $profitloss;

                            $grandTotal = 0;

                            if (!empty($profitloss)) {
                                foreach ($profitloss as $key => $value) {
                                    echo "<tr>";
                                    echo "<td>" . $key . "</td>";
                                    echo "<td class='text-right'>" . $value . "</td>";
                                    echo "</tr>";

                                    $grandTotal += $value;
                                }
                            }
                            echo '<tr><td><strong>Profit/Loss</strong></td><td class="text-right"><strong>' . $grandTotal . '</strong></td></tr>';
                            ?>

                        </tbody> 
                    </table> 

                </div>

                <!--currency form model-->

            </div>
        </div>
    </div>
</div>