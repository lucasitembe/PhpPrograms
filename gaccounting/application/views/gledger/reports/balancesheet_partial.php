<?php
$style = 'style="height:250px;overflow-y:auto;"';
if (strtolower($source) == 'report') {
    $style = '';
}
?>
<div class="row">
    <div class="col-sm-6">
        <div class="table-responsive"> 

            <table class="table table-hover" style="margin-bottom:0;"> 
                <thead> 
                    <tr>
                        <th colspan="2"><h3>Assets</h3></th>
                    </tr>
                    <tr> 
                        <th>GL Account</th> 
                        <th class="text-right" >Amount</th> 
                    </tr> 
                </thead>  
            </table>
            <div <?= $style ?>>
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
                                    <?php if (strtolower($source) == 'report') { ?>  
                                        <td><?= $asset->acc_name ?></td>
                                        <td class="text-right"><?= number_format($asset->sub_total, 2) ?></td>
                                    <?php } else { ?>
                                        <td><a href="<?= base_url("Gledger/balancesheet?drill_asset_account&acc_code=" . $asset->acc_code . "&start_date=$start_date&end_date=$end_date") ?>"><?= $asset->acc_name ?></td>
                                        <td class="text-right"><a href="<?= base_url("Gledger/balancesheet?drill_asset_account&acc_code=" . $asset->acc_code . "&start_date=$start_date&end_date=$end_date") ?>"><?= number_format($asset->sub_total, 2) ?></a></td>
                                    <?php } ?>
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
    <?php if (strtolower($source) == 'report') { ?>  
        <br/>    <?php } ?>
    <div class="col-sm-6">
        <div class="table-responsive"> 
            <table class="table table-hover" style="margin-bottom:0;"> 
                <thead> 
                    <tr>
                        <th colspan="2"><h3>Liabilities</h3></th>
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
                        $total_liabilities = 0;
                        if (count($Liabilities)) {
                            $sn = 0;

                            foreach ($Liabilities as $liab) {
                                $total_liabilities += $liab->sub_total;
                                ?>
                                <tr>
                                    <?php if (strtolower($source) == 'report') { ?>  
                                        <td><?= $liab->acc_name ?></td>
                                        <td class="text-right"><?= number_format($liab->sub_total, 2) ?></td>
                                    <?php } else { ?>
                                        <td><a href="<?= base_url("Gledger/balancesheet?drill_liab_account&acc_code=" . $liab->acc_code . "&start_date=$start_date&end_date=$end_date") ?>"><?= $liab->acc_name ?></a></td>
                                        <td class="text-right"><a href="<?= base_url("Gledger/balancesheet?drill_liab_account&acc_code=" . $liab->acc_code . "&start_date=$start_date&end_date=$end_date") ?>"><?= number_format($liab->sub_total, 2) ?></a></td>
                                    <?php } ?>
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

</div>

<div class="row">
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
</div>