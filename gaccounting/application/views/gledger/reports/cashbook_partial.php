<?php
$isLast = false;
if (isset($_GET['id'])) {
    $isLast = true;
}

if (!isset($_GET['report'])) {
    ?><div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="<?= site_url('Home') ?>"> Dashboard</a></li>
                <li><i class="fa fa-laptop"></i><a href="<?= site_url('gledger') ?>"> General Ledger</a></li>
                <li><i class="fa fa-laptop"></i><a href="<?= site_url("gledger/cashBook?start_date=$start_date&end_date=$end_date") ?>"> Cash Book</a></li>


            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div id='table-options' class="pull-right">
                <?php if (isset($_GET['id'])) { ?>  
                    <a href="<?= base_url('Gledger/cashBook?report&' . $_SERVER['QUERY_STRING']) ?>" id="ajaxUpdateContainerPdf" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i> Pdf</a> 
                <?php } else { ?>
                    <a href="" id="ajaxUpdateContainerPdf" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i> Pdf</a> 
                <?php } ?>
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Cash Book</h3>
                </div>
                <div class="panel-body" id="ajaxUpdateContainer">
                    <div class="table-responsive"> 
                        <div class="col-sm-12">
                            <?php
                        }
                        ?>
                        <div class="table-responsive"> 
                            <table class="table table-hover"> 
                                <thead> 
                                    <tr> 
                                        <th>Date</th> 
                                        <th>Description / Ledger</th> 
                                        <?php if ($isLast) { ?>
                                            <th>Narration</th> 
                                        <?php } ?>
                                        <th class="text-right">Amount</th> 
                                    </tr> 
                                </thead> 
                                <tbody class="tbody-backc-color"> 
                                    <?php
                                    $total = 0;
                                    $total_grand = 0;
                                    if (count($cashbook)) {
                                        $sn = 0;
                                        foreach ($cashbook as $val) {
                                            $total_grand += $val->amount;
                                            $href = '';
                                            $hrefclose = '</a>';
                                            if (!$isLast) {
                                                $href = '<a href="' . base_url("Gledger/cashBook?drill_ledger&ref_code=" . $this->input->get('acc_code') . "&start_date=$start_date&end_date=$end_date&id=" . $val->ledger_id) . '">';
                                            } else {
                                                
                                            }
                                            ?>
                                            <tr>
                                                <td><?= $href . $val->journal_date . $hrefclose ?></td>
                                                <td><?= $href . $val->ledger_name . $hrefclose ?></td>
                                                <?php if ($isLast) { ?>
                                                    <td><?= $href . $val->comment . $hrefclose ?></td>
                                                <?php } ?>
                                                <td class="text-right"><?= $href . number_format($val->amount, 2) . $hrefclose ?></a></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>

                                    <tr style="background:#f3f3f3;color:#000;">
                                        <td colspan="3">Balance</td>
                                        <td class="text-right"><?= number_format($total_grand) ?></td>
                                    </tr>
                                </tbody> 
                            </table> 
                        </div>

