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
             <?php if (count($revenues) && count($expenses)) { ?>  
                <a href="<?= base_url('Gledger/profitloss?report&' . $_SERVER['QUERY_STRING']) ?>" id="ajaxUpdateContainerPdf" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i> Pdf</a> 
            <?php } else { ?>
                <a href="" id="ajaxUpdateContainerPdf" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i> Pdf</a> 
            <?php } ?>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i>Income Statement ( Profit & Loss ) </h3>
            </div>
            <div class="panel-body" id="ajaxUpdateContainer">
                <?php
                $style = 'style="height:250px;overflow-y:auto;"';
                ?>
                <div class="col-sm-6">
                    <div class="table-responsive"> 
                        <table class="table table-hover" style="margin-bottom:0;"> 
                            <thead> 
                                <tr>
                                    <th colspan="3"><h3>Revenues</h3></th>
                                </tr>
                                <tr> 

                                    <th>GL Account</th> 
                                    <th class="text-right">Amount</th> 
                                </tr> 
                            </thead>  
                        </table>
                        <div <?= $style ?>>
                            <table class="table table-hover" style="margin-bottom:0;"> 
                                <tbody class="tbody-backc-color"> 
                                    <?php
                                    $total_revenues = 0;
                                    //print_r($revenues);
                                    if (count($revenues)) {
                                        $sn = 0;
                                        foreach ($revenues as $rev) {
                                            $total_revenues += $rev->sub_total;
                                            ?>
                                            <tr>

                                                <td><a href="<?= base_url("Gledger/profitloss?drill_rev_account&acc_code=" . $rev->acc_code . "&start_date=$start_date&end_date=$end_date") ?>"><?= $rev->acc_name ?></td>
                                                <td class="text-right"><a href="<?= base_url("Gledger/profitloss?drill_rev_account&acc_code=" . $rev->acc_code . "&start_date=$start_date&end_date=$end_date") ?>"><?= number_format($rev->sub_total, 2) ?></a></td>

                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <table class="table table-hover" style="margin-bottom:0;"> 
                            <tr style="background:#f3f3f3;color:#000;">
                                <td colspan="2"><b>Total Revenues</b></td>
                                <td class="text-right"><b><?= number_format($total_revenues, 2) ?></b></td>
                            </tr>
                            </tbody> 
                        </table> 
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="table-responsive"> 
                        <table class="table table-hover" style="margin-bottom:0;"> 
                            <thead> 
                                <tr>
                                    <th colspan="3"><h3>Expenses</h3></th>
                                </tr>
                                <tr> 

                                    <th>GL Account</th> 
                                    <th class="text-right">Amount</th> 
                                </tr> 
                            </thead> 
                        </table>
                        <div <?= $style ?>>
                            <table class="table table-hover" style="margin-bottom:0;"> 
                                <tbody class="tbody-backc-color"> 
                                    <?php
                                    $total_expenses = 0;
                                    //print_r($revenues);
                                    if (count($expenses)) {
                                        $sn = 0;

                                        foreach ($expenses as $exp) {
                                            $total_expenses += $exp->sub_total;
                                            ?>
                                            <tr>
                                                <td><a href="<?= base_url("Gledger/profitloss?drill_exp_account&acc_code=" . $exp->acc_code . "&start_date=$start_date&end_date=$end_date") ?>"><?= $exp->acc_name ?></td>
                                                <td class="text-right"><a href="<?= base_url("Gledger/profitloss?drill_exp_account&acc_code=" . $exp->acc_code . "&start_date=$start_date&end_date=$end_date") ?>"><?= number_format($exp->sub_total, 2) ?></a></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <table class="table table-hover" style="margin-bottom:0;"> 
                            <tr style="background:#f3f3f3;color:#000;">
                                <td colspan="2"><b>Total Expenses</b></td>
                                <td class="text-right"><b><?= number_format($total_expenses, 2) ?></b></td>
                            </tr>
                            </tbody> 
                        </table> 

                    </div>
                </div>
                <div class="row" >
                    <div class="col-sm-12" style="margin-top:25px;">
                        <p style="font-size:20px;font-weight:bold;margin-left:18px;">
                            RETAINING EARNING VALUE : <?= number_format(($total_revenues + $total_expenses), 2) ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>