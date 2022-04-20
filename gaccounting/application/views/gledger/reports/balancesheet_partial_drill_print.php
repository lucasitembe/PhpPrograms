<?php
?>

<div class="row">
    <div class="col-lg-12">
        <h1 style='text-align:center;'>BALANCE SHEET REPORT</h1>
        <h2 style='text-align:center;'>LEDGER DETAILS</h2>
        <h3 style="text-align:center;font-weight:200"><strong>FROM</strong> <?= $start_date ?> <strong>TO</strong> <?= $end_date ?></h3><br/>

        <div class="table-responsive"> 

            <table class="table table-hover" style="margin-bottom:0;"> 
                <thead> 
                    <tr>
                        <th colspan="3"><h3><?= $source ?></h3></th>
                    </tr>
                    <tr> 
                        <th>#</th>
                        <th>Ledger Name</th> 
                        <th>GL Account</th> 
                        <th>Narration</th> 
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
                            ?>
                            <tr>
                                <td><?= ++$sn ?></td>
                                <td><?= $val->ledger_name ?></td>
                                <td><?= $val->acc_name ?></td>
                                <td><?= $val->comment ?></td>
                                <td class="text-right"><?= number_format($val->sub_total, 2) ?></a></td>
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