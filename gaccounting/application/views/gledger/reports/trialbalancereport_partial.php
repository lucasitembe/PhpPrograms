
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

