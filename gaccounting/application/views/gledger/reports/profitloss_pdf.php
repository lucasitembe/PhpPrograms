
<table class="table table-hover table-bordered">
    <thead>
        <tr>
            <th colspan="3" align="center" style="padding:5px;background:#f0f0f0;">
                <h3>Revenues</h3>
            </th>
        </tr>
        <tr style="background:#f0f0f0;">
        
            <th>GL Account(s)</th>
            <th style="text-align:right;" colspan="2">Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $total_revenues = 0;
        if (count($revenues) > 0) {
            $sn = 0;

            foreach ($revenues as $value) {
                $total_revenues += $value->sub_total;
                echo '<tr>
                    
                    <td>' . $value->acc_name . '</td>
                    <td colspan="2" style="text-align:right;">' . number_format($value->sub_total, 2) . '</td>
                </tr>';
            }
        }
        ?>
        <tr style="background:#f0f0f0;">
            <td colspan="2" ><b>Total Revenues</b></td>

            <td style="text-align:right;"><b><?= number_format($total_revenues, 2) ?></b></td>
        </tr>
    </tbody>
</table>



<table class="table table-hover table-bordered">
    <thead>
        <tr>
            <th colspan="3" align="center" style="padding:5px;background:#f0f0f0;">
                <h3>Expenses</h3>
            </th>
        </tr>
        <tr style="background:#f0f0f0;">
            
            <th>GL Account(s)</th>
            <th style="text-align:right;" colspan="2">Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $total_expenses = 0;
        if (count($expenses) > 0) {
            $sn = 0;

            foreach ($expenses as $value) {
                $total_expenses += $value->sub_total;
                echo '<tr>
                    
                    <td>' . $value->acc_name . '</td>
                    <td colspan="2" style="text-align:right;">' . number_format($value->sub_total, 2) . '</td>
                </tr>';
            }
        }
        ?>
        <tr style="background:#f0f0f0;">
            <td colspan="2" ><b>Total Expenses</b></td>

            <td style="text-align:right;"><b><?= number_format($total_expenses, 2) ?></b></td>
        </tr>
    </tbody>
</table>

<p style="font-size:20px;font-weight:bold;margin-left:0px;">
    <i> RETAINING EARNING VALUE</i> : <?= number_format(($total_revenues + $total_expenses), 2) ?>
</p>