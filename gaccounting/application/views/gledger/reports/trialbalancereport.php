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
                <input type="text"  id="start_date" class="form-control form-inline" placeholder="Start Date" value="<?= (count($currentAccountYear) > 0) ? $currentAccountYear->account_year : 'Finance Year Not Set' ?>"/>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <input type="text"  id="end_date" class="form-control form-inline" placeholder="End Date"  value="<?= date('Y-m-t', strtotime(date('Y-m'))) ?>">
            </div>
        </div>
        <?php
        if (count($currentAccountYear) > 0) {
            ?>
            <div class="form-group">
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <input type="button" onclick="getTrialBalanceReport()" class="btn btn-primary" value="Get Data"/>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<br/>
</div>

<div class="row">
    <div class="col-lg-12">
        <div id='table-options' class="pull-right">
             <?php if (count($trialBalance)) { ?>  
                <a href="<?= base_url('Gledger/balancesheet?report&' . $_SERVER['QUERY_STRING']) ?>" id="ajaxUpdateContainerPdf" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i> Pdf</a> 
            <?php } else { ?>
                <a href="" id="ajaxUpdateContainerPdf" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i> Pdf</a> 
            <?php } ?> 
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Trial Balance </h3>
            </div>
            <div class="panel-body" id="ajaxUpdateContainer">
                <div class="table-responsive"> 
                    <table class="table table-hover"> 
                <!--        <thead> 
                            <tr> 
                                <th>Account code</th> 
                                <th>GL Accounts</th> 
                                <th class="text-right">Amount</th> 
                            </tr> 
                        </thead> -->
                        <tbody class="tbody-backc-color"> 
                            <?php
                            if (count($trialBalance) > 0) {
                                $sn = 0;
                                $total = 0;

                                foreach ($trialBalance as $tb => $val) {
                                    foreach ($val as $key => $value) {
                                        if (count($value)) {
                                            echo " <tr style='background:#f3f3f3;color:#000;'><th colspan='3'><b>" . $key . "</b></th><tr>";
                                            echo '<tr style="background:#f3f3f3;color:#000;"> 
                                    <th>Account code</th> 
                                    <th>GL Accounts</th> 
                                    <th class="text-right">Amount</th> 
                                </tr> ';
                                            $subTotal = 0;
                                            foreach ($value as $acc => $acct) {
                                                $acc_code = $acct->acc_code;
                                                $prefix = strtoupper(substr($acct->sec_desc, 0, 2));
                                                $code = $prefix . "-" . $acc_code;
                                                $total += $acct->sub_total;
                                                $subTotal += $acct->sub_total;
                                                ?>
                                                <tr>
                                                    <?php if (strtolower($source) == 'report') { ?>  
                                                        <td><?= $code ?></td>
                                                        <td><?= $acct->acc_name ?></td>
                                                        <td class="text-right"><?= number_format($acct->sub_total, 2) ?></td>
                                                    <?php } else { ?>
                                                        <td><a href="<?= base_url("Gledger/trialbalance?drill_acc=$key&acc_code=" . $acct->acc_code . "&start_date=$start_date&end_date=$end_date") ?>"><?= $code ?></td>
                                                        <td><a href="<?= base_url("Gledger/trialbalance?drill_acc=$key&acc_code=" . $acct->acc_code . "&start_date=$start_date&end_date=$end_date") ?>"><?= $acct->acc_name ?></td>
                                                        <td class="text-right"><a href="<?= base_url("Gledger/trialbalance?drill_acc=$key&acc_code=" . $acct->acc_code . "&start_date=$start_date&end_date=$end_date") ?>"><?= number_format($acct->sub_total, 2) ?></a></td>
                                                    <?php } ?>
                                                </tr>
                                                <?php
                                            }
                                            echo "<tr><td>Sub Total</td><td></td><td class='text-right'><b>" . number_format($subTotal, 2) . "</b></td></tr>";
                                        }
                                    }
                                }
                            }
                            ?>

                            <tr style="background:#f3f3f3;color:#000;">
                                <td colspan="2">Balance</td>
                                <td class="text-right"><?= number_format($total) ?></td>
                            </tr>
                        </tbody> 
                    </table> 
                </div>
            </div>
        </div>
    </div>
</div>