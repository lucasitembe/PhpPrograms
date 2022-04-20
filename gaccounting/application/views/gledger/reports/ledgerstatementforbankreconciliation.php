<!--<p class="text-right"><b>BF</b> 0</p>-->
<form class="form" id="saveBankReconciliationForm" action="#" method="POST">
<div class="table-responsive"> 
    <table class="table table-hover"> 
        <thead> 
            <tr> 
                <th>#</th> 
                <th>Date n Time</th> 
                <th>Narration</th>
                <th class="text-right">Amount</th> 
                <th></th>
            </tr> 
        </thead> 
        <tbody class="tbody-backc-color"> 
            <?php
            $sn=0;
            $grandTotal = 0;
            $i = 1;
            if ($ledgerStatementReport != null) {
                
                echo '<input type="hidden" name="ledger_count" value="'.count($ledgerStatementReport).'">';
                echo '<input type="hidden" name="start_date" value="'.$start_date.'">';
                echo '<input type="hidden" name="end_date" value="'.$end_date.'">';
                echo '<input type="hidden" name="ledger_id" value="'.$ledger_id.'">';

                foreach ($ledgerStatementReport as $val) {
                    ++$sn;
                    $grandTotal += $val->amount;
                    echo '<tr>';
                    echo '<td>' . $i++ . '</td>';

                    echo '<td><label for="reconcile_'.$sn.'">' . $val->trans_date_time . '</label></td>';

                    echo '<td><label for="reconcile_'.$sn.'">' . $val->comment . '</label></td>';

                    echo '<td  class="text-right">' . number_format($val->amount,2) . '</td>'; 
            ?>
            <td class="text-right" style="width:5%;">
            <input type="checkbox" name="reconcile_<?= $sn ?>" id="reconcile_<?= $sn ?>" value="<?= $val->trans_det_id ?>" style="width:25px;height:25px;">
            <input type="hidden" name="u_reconcile_<?= $sn ?>" value="<?= $val->trans_det_id ?>">
            <input type="hidden" name="amount_<?= $sn ?>" value="<?= $val->amount ?>">
            </td>
            <?php    echo '</tr></tr>'; }

                echo '<tr><td colspan="3" class="text-left"><label class="control-label">Balance</label></td><td class="text-right"><label class="control-label">' . number_format($grandTotal,2) . '</label></td><td></td></tr>';
            }
            ?>
            <input type="hidden" name="ledger_total_amount" id="ledger_total_amount" value="<?= $grandTotal ?>" >

        </tbody> 
    </table> 
</div>
<div class="row">
    
        <div class="form-group col-md-offset-3">
            <div class="form-group">
                <label class="col-md-3">Bank Balance</label>
            <div class="col-md-4 col-sm-3 col-xs-12">
             
                <input type="text" name="bank_balance"  class="form-control" placeholder="Bank Balance"  >
            </div>
        </div>
        </div>
</div>
<div class="row">
        <div class="form-group col-md-offset-3">
            <div class="form-group">
            <label class="col-md-3">Amount Not in Ledger</label>
            <div class="col-md-4 col-sm-3 col-xs-12">
             
                <input type="text" name="amount_not_in_ledger"   class="form-control" placeholder="Amount Not in Ledger"  >
            </div>
        </div>
        </div>
        
            
</div>
<br>
</form>
<div class="row">
    <div class="col-md-3 col-sm-3 col-xs-12 col-md-offset-7">
             
                <button class="btn btn-primary" onclick="saveBankReconciliation(this)" style="width:35%;"><i class="fa fa-save"> Save</i></button>
            </div>
</div>

