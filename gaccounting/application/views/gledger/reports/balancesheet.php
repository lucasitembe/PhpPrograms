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
                <input type="text"  id="start_date" readonly class="form-control form-inline" placeholder="Start Date" value="<?= (count($currentAccountYear) > 0) ? $currentAccountYear->account_year : 'Finance Year Not Set' ?>"/>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <input type="text"  id="end_date" class="form-control form-inline" placeholder="End Date"  value="<?= date('Y-m-t', strtotime(date('Y-m'))) ?>">
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <input type="button" onclick="getBalanceSheet()" class="btn btn-primary" value="Get Data"/>
            </div>
        </div>
    </div>
</div>
<br/>
</div>
<div class="row">
    <div class="col-lg-12">
        <div id='table-options' class="pull-right">
            <?php if (count($Liabilities) && count($Assets)) { ?>  
                <a href="<?= base_url('Gledger/balancesheet?report&' . $_SERVER['QUERY_STRING']) ?>" id="ajaxUpdateContainerPdf" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i> Pdf</a> 
            <?php } else { ?>
                <a href="#" id="ajaxUpdateContainerPdf" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i> Pdf</a> 
            <?php } ?>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Statement of Financial Position (Balance sheet)</h3>
            </div>
            <div class="panel-body" id="ajaxUpdateContainer">
                <?php if (count($Liabilities) && count($Assets)) { ?>
                    <div class="col-sm-6">
                        <div class="table-responsive"> 

                            <table class="table table-hover" style="margin-bottom:0;"> 
                                <thead> 
                                    <tr>
                                        <th colspan="3"><h3>Assets</h3></th>
                                    </tr>
                                    <tr> 
                                        <th>GL Account</th> 
                                        <th class="text-right">Amount</th> 
                                    </tr> 
                                </thead>  
                            </table>
                            <div style="height:250px;overflow-y:auto;">
                                <table class="table table-hover" style="margin-bottom:0;"> 
                                    <tbody class="tbody-backc-color"> 
                                        <?php
                                        $total_assets = 0;
                                        if (count($Assets)) {
                                            $sn = 0;
                                            foreach ($Assets as $asset) {
                                                $total_assets += $asset->sub_total;
                                                ?>
                                                <tr>
                                                    <td><a href="<?= base_url("Gledger/balancesheet?drill_asset_account&acc_code=" . $asset->acc_code . "&start_date=$start_date&end_date=$end_date") ?>"><?= $asset->acc_name ?></td>
                                                    <td class="text-right"><a href="<?= base_url("Gledger/balancesheet?drill_asset_account&acc_code=" . $asset->acc_code . "&start_date=$start_date&end_date=$end_date") ?>"><?= number_format($asset->sub_total, 2) ?></a></td>
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
                                    <td colspan="2"><b>Total Assets</b></td>
                                    <td class="text-right"><b><?= number_format($total_assets, 2) ?></b></td>
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
                                        <th colspan="3"><h3>Liabilities</h3></th>
                                    </tr>
                                    <tr> 
                                        <th>GL Account</th> 
                                        <th class="text-right">Amount</th> 
                                    </tr> 
                                </thead> 
                            </table>
                            <div style="height:250px;overflow-y:auto;">
                                <table class="table table-hover" style="margin-bottom:0;"> 
                                    <tbody class="tbody-backc-color"> 
                                        <?php
                                        $total_liabilities = 0;
                                        if (count($Liabilities)) {
                                            $sn = 0;

                                            foreach ($Liabilities as $liab) {
                                                $total_liabilities += $liab->sub_total;
                                                ?>
                                                <tr>
                                                    <td><a href="<?= base_url("Gledger/balancesheet?drill_liab_account&acc_code=" . $liab->acc_code . "&start_date=$start_date&end_date=$end_date") ?>"><?= $liab->acc_name ?></a></td>
                                                    <td class="text-right"><a href="<?= base_url("Gledger/balancesheet?drill_liab_account&acc_code=" . $liab->acc_code . "&start_date=$start_date&end_date=$end_date") ?>"><?= number_format($liab->sub_total, 2) ?></a></td>
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
                                    <td colspan="2"><b>Total Liabilities</b></td>
                                    <td class="text-right"><b><?= number_format($total_liabilities, 2) ?></b></td>
                                </tr>
                                </tbody> 
                            </table> 
                            <br/>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="table-responsive"> 
                            <table class="table table-hover" style="margin-bottom:0;"> 
                                <tr style="background:#f3f3f3;color:#000;">
                                    <td colspan="2"><b>Retained Earnings</b></td>
                                    <td class="text-right"><b><?= number_format($NetProfit, 2) ?></b></td>
                                </tr>
                                </tbody> 
                            </table> 
                            <br/>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="table-responsive"> 
                            <table class="table table-hover" style="margin-bottom:0;"> 
                                <tr style="background:#f3f3f3;color:#000;">
                                    <td colspan="2"><b>Balance</b></td>
                                    <td class="text-right"><b><?= number_format($total_assets + $total_liabilities + $NetProfit) ?></b></td>
                                </tr>
                                </tbody> 
                            </table> 
                        </div>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>
</div>