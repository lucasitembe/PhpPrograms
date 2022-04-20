
<hr>
<table class="table table-striped table-hover">
	<thead>
		<tr>
			<th>#</th>
			<th>ledger Name</th>
			<th>Reconciliation Date</th>
			<th>Posted By</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	<?php
		if($reconciliations){
			$sn=0;
			foreach ($reconciliations as $br) { ?>
				<tr>
					<th><?= ++$sn ?></th>
					<th><?= $br->ledger_name ?></th>
					<th><?= $br->reconciliation_date_time ?></th>
					<th><?= $br->posted_by ?></th>
					<td><a href="<?= base_url() ?>gledger/viewBankReconciliationPdf/<?= $br->id ?>" class="btn btn-success btn-xs" target="_blank">View Details</a></td>
					
				</tr>
		<?php	}
		}
	?>
	</tbody>
</table>
