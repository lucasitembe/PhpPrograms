<?php
$style = 'style="height:250px;overflow-y:auto;"';
if (strtolower($source) == 'report') {
    $style = '';
}
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
                    <th colspan="2" class="text-right">Amount</th> 
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
                                <?php if (strtolower($source) == 'report') { ?>  
                                    <td><?= $rev->acc_name ?></td>
                                    <td class="text-right"><?= number_format($rev->sub_total, 2) ?></td>
                                <?php } else { ?>
                                    <td><a href="<?= base_url("Gledger/profitloss?drill_rev_account&acc_code=" . $rev->acc_code . "&start_date=$start_date&end_date=$end_date") ?>"><?= $rev->acc_name ?></td>
                                    <td class="text-right"><a href="<?= base_url("Gledger/profitloss?drill_rev_account&acc_code=" . $rev->acc_code . "&start_date=$start_date&end_date=$end_date") ?>"><?= number_format($rev->sub_total, 2) ?></a></td>
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
                    <th colspan="2" class="text-right">Amount</th> 
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
                                <?php if (strtolower($source) == 'report') { ?>  
                                    <td><?= $exp->acc_name ?></td>
                                    <td class="text-right"><?= number_format($exp->sub_total, 2) ?></td>
                                <?php } else { ?>
                                    <td><a href="<?= base_url("Gledger/profitloss?drill_exp_account&acc_code=" . $exp->acc_code . "&start_date=$start_date&end_date=$end_date") ?>"><?= $exp->acc_name ?></td>
                                    <td class="text-right"><a href="<?= base_url("Gledger/profitloss?drill_exp_account&acc_code=" . $exp->acc_code . "&start_date=$start_date&end_date=$end_date") ?>"><?= number_format($exp->sub_total, 2) ?></a></td>
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
           
            
             <?php 
      $retaining_earning=($total_revenues + $total_expenses);
      if($retaining_earning < 0){
        ?>

      RETAINING EARNING PROFIT VALUE : <?= number_format($retaining_earning,2) ?>
<?php
    }else{
      ?>
      RETAINING EARNING LOSS VALUE : <?= number_format($retaining_earning,2) ?>

      <?php

    }
    ?>
        </p>
    </div>
</div>