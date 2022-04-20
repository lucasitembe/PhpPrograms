<div class="table-responsive"> 
    <table class="table table-hover"> 
        <thead> 
            <tr> 
                <th class="col-xs-1">S/n</th> 
                <th>Debtor Name</th> 
                <th class="text-right" class="col-xs-3">Amount</th> 
            </tr> 
        </thead> 
        <tbody class="tbody-backc-color"> 
            <?php
            $grandTotal=0;
            if (!empty($payable) && !is_null($payable)) {
                $i=1;
                foreach ($payable as $dt) {
                    echo '<tr>';
                    echo '<td class="col-xs-1">' . $i++ . '</td>';
                     echo '<td>' . $dt->name . '</td>';
                    echo '<td class="text-right col-xs-3">' .number_format($dt->amount, 2) . '</td>';
                    echo '</tr>';
                    
                    $grandTotal +=$dt->amount;
                }
            }
            
            echo '<tr><td colspan="2"><b style="margin-left:100px" >Grand Total</b></td><td class="text-right col-xs-3" ><b>'.number_format($grandTotal,2).'</b></td></tr>';
            ?>

        </tbody> 
    </table> 
</div>