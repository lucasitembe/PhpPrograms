<?php
$isLast = false;
if (isset($_GET['id'])) {
    $isLast = true;
}

$src = '';
if (strtolower($source) == 'revenue')
    $src = 'drill_rev_account';
if (strtolower($source) == 'expenses')
    $src = 'drill_exp_account';
?>
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="<?= site_url('Home') ?>"> Dashboard</a></li>
            <li><i class="fa fa-laptop"></i><a href="<?= site_url('gledger') ?>"> General Ledger</a></li>

            <?php if ($isLast) { ?>
                <li><i class="fa fa-laptop"></i><a href="<?= site_url("gledger/profitloss?$src&acc_code=$ref_code&start_date=$start_date&end_date=$end_date") ?>"> Profit & Loss</a></li>
            <?php } else { ?>
                <li><i class="fa fa-laptop"></i><a href="<?= site_url("gledger/profitloss?start_date=$start_date&end_date=$end_date") ?>"> Profit & Loss</a></li>	
            <?php } ?>

        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div id='table-options' class="pull-right">
            <?php if (!empty($source)) { ?>  
                <a href="<?= base_url('Gledger/profitloss?report&' . $_SERVER['QUERY_STRING']) ?>" id="ajaxUpdateContainerPdf" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i> Pdf</a> 
            <?php } else { ?>
                <a href="" id="ajaxUpdateContainerPdf" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i> Pdf</a> 
            <?php } ?>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Statement of Financial Position (Balance sheet)</h3>
            </div>
            <div class="panel-body" id="ajaxUpdateContainer">
                <div class="table-responsive"> 
                    <div class="col-sm-12">
                        <div class="table-responsive"> 

                            <table class="table table-hover" style="margin-bottom:0;"> 
                                <thead> 
                                    <tr>
                                        <th colspan="4"><h3><?= $source ?></h3></th>
                                    </tr>
                                    <tr> 
                                        <th>Ledger Name</th> 
                                        <th>GL Account</th> 
                                        <?php if ($isLast) { ?>
                                            <th>Narration</th> 
                                        <?php } ?>

                                        <th class="text-right">Amount</th> 
                                    </tr> 
                                </thead>  
                                <tbody class="tbody-backc-color"> 
                                    <?php
                                    $total_grand = 0;
                                    if (count($ledgersTransactions)) {
                                        $sn = 0;
                                        foreach ($ledgersTransactions as $val) {
                                            $total_grand += $val->sub_total;
                                            $href = '';
                                            $hrefclose = '</a>';
                                            if (!$isLast) {
                                                $href = '<a href="' . base_url("Gledger/profitloss?$src&ref_code=".$this->input->get('acc_code')."&start_date=$start_date&end_date=$end_date&id=" . $val->ledger_id) . '">';
                                            }
                                            ?>
                                            <tr>
                                                <td><?= $href . $val->ledger_name . $hrefclose ?></td>
                                                <td><?= $href . $val->acc_name . $hrefclose ?></td>
                                                <?php if ($isLast) { ?>
                                                    <td><?= $href . $val->comment . $hrefclose ?></td>
                                                <?php } ?>
                                                <td class="text-right"><?= $href . number_format($val->sub_total, 2) . $hrefclose ?></a></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="table-responsive"> 
                            <table class="table table-hover" style="margin-bottom:0;"> 
                                <tr style="background:#f3f3f3;color:#000;">
                                    <td colspan="2"><b>Total</b></td>
                                    <td class="text-right"><b><?= number_format($total_grand, 2) ?></b></td>
                                </tr>
                                </tbody> 
                            </table> 
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>