<?php if(!$reconciliation){ exit;} ?>

	<h4 style='text-align:center;margin:0px;'>From <?= $reconciliation->start_date ?> To <?= $reconciliation->end_date ?></h4>
	<h4 style='text-align:center;margin:0px;'><?= $reconciliation->ledger_name ?></h4>
        <h4 style='text-align:center;margin-top:0px;'>Balance as per Bank Statement is <?= number_format($reconciliation->bank_balance,2) ?></h4>
        

<table class="table">
	<thead>
		<tr>
			<th colspan="4" align="">Unpresented Cheque</th>
		</tr>
		<tr>
			<th>Sn</th>
			<th>Date</th>
			<th>Narration</th>
			<th class="text-right">Amount</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$unpresented_cheque_total_amount = 0;
	if($reconciliation_details){
		$sn = 0;
		
		foreach ($reconciliation_details as $rd) {
			if($rd->reconciliation_status=='found') continue;
			$unpresented_cheque_total_amount+=$rd->amount
		?>
		<tr>
			<td><?= ++$sn ?></td>
			<td><?= $rd->trans_date_time ?></td>
			<td><?= $rd->comment ?></td>
			<td class="text-right"><?= number_format($rd->amount,2) ?></td>
		</tr>
	<?php	}
	}
	?>
		
		<tr style="background:#f1f1f1;">
			<th colspan="3">Total of Unpresented cheque</th>
			<th class="text-right"><?= number_format($unpresented_cheque_total_amount,2) ?></th>
		</tr>
                <tr style="border:0px;padding:10px;;">
			<th style="border:0px;color:#fff;" colspan="3">-</th>
			<th style="border:0px;color:#fff;" class="text-right">-</th>
		</tr>
                <tr style="border:0px;padding:10px;;">
			<th style="border:0px;color:#fff;" colspan="3">-</th>
			<th style="border:0px;color:#fff;" class="text-right">-</th>
		</tr>
		<tr style="border:0px;padding:10px;;">
			<th style="border:0px;" colspan="3">Amount Not in Cash Book (<?= $reconciliation->ledger_name ?>)</th>
			<th style="border:0px;" class="text-right"><?= number_format($reconciliation->amount_not_in_ledger,2) ?></th>
		</tr>
		<tr style="border:0px;">
			<th style="border:0px;" colspan="3">Balance as per Cash Book (<?= $reconciliation->ledger_name ?>)</th>
			<th style="border:0px;" class="text-right"><?= number_format(($reconciliation->bank_balance+$unpresented_cheque_total_amount-$reconciliation->amount_not_in_ledger),2) ?></th>
		</tr>
                <tr style="border:0px;">
			<th style="border:0px;" colspan="3">Balance as per Account (<?= $reconciliation->ledger_name ?>)</th>
			<th style="border:0px;" class="text-right"><?= number_format($reconciliation->ledger_balance,2) ?></th>
		</tr>
                <?php 
                $balance_as_per_ledger = ($reconciliation->bank_balance+$unpresented_cheque_total_amount-$reconciliation->amount_not_in_ledger);
                if($reconciliation->ledger_balance!=$balance_as_per_ledger){
                    echo '<tr style="border:0px;padding:10px;;">
			<th style="border:0px;color:#fff;" colspan="3">-</th>
			<th style="border:0px;color:#fff;" class="text-right">-</th>
		</tr>
                
                <tr style="border:0px;">
			<th style="border:0px;color:red;" colspan="4">Note: The Bank Reconciliation is not balanced, please check it again</th>
			
		</tr>';
                }
                ?>
                
                <tr style="border:0px;padding:10px;;">
			<th style="border:0px;color:#fff;" colspan="3">-</th>
			<th style="border:0px;color:#fff;" class="text-right">-</th>
		</tr>
                <tr style="border:0px;padding:10px;;">
			<th style="border:0px;color:#fff;" colspan="3">-</th>
			<th style="border:0px;color:#fff;" class="text-right">-</th>
		</tr>
                <tr style="border:0px;">
                    <th style="border:0px;" colspan="2"><i>Prepared by</i>: <?= $reconciliation->posted_by ?></th>
			<th style="border:0px;" colspan="2"><i>Signature</i>: ..........................</th>
			
		</tr>
                
	</tbody>
</table>