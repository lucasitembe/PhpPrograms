<div class="table-responsive"> 
    <table class="table table-hover"> 
        <thead> 
            <tr> 
                <th>Description</th> 
                <th class="text-right">Amount</th> 
            </tr> 
        </thead> 
        <tbody class="tbody-backc-color"> 
            <?php
            $profitloss;

            $grandTotal = 0;

            if (!empty($profitloss)) {
                echo "<tr><td>Income</td><td class='text-right'>" . number_format($profitloss['income'], 2) . "</td></tr>";
                echo "<tr><td>Cost of Sales</td><td  class='text-right'>" . number_format($profitloss['cost_of_sales'], 2) . "</td></tr>";
                
                $grossprofit=$profitloss['income'] + $profitloss['cost_of_sales'];
                
                echo '<tr><td><strong>Gross Profit</strong></td><td class="text-right"><strong>' . number_format($grossprofit, 2) . '</strong></td></tr>';
                echo "<tr><td>Overheads</td><td  class='text-right'>" . number_format($profitloss['overheads'],2) . "</td></tr>";
                
                $profitBeforeTax=$grossprofit+$profitloss['overheads'];
                echo '<tr><td><strong>Profit Before Taxation</strong></td><td class="text-right"><strong>' . number_format( $profitBeforeTax, 2) . '</strong></td></tr>';
               
                $tax=(((int)$profitloss['tax'])/100)*$profitBeforeTax;
                
                echo "<tr><td>Taxation</td><td  class='text-right'>" . number_format($tax,2) . "</td></tr>";
                
                $netProfit=$profitBeforeTax-$tax;
                
                echo '<tr><td><strong>Net Profit</strong></td><td class="text-right"><strong>' . number_format($netProfit , 2) . '</strong></td></tr>';
               
            }
            ?>

        </tbody> 
    </table> 
</div>