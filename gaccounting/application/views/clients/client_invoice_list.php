<?php
if($invoices){
	$sn = 0;
	foreach ($invoices as $invoice) { 

	?>
	<tr>
									    <td><?= ++$sn ?></td>
									    <td><?= $invoice->id ?></td>
									    <td><?= $invoice->invoice_date_time ?></td>
									    <td><?= number_format($invoice->amount,2) ?></td>
									   	<td><?= $invoice->fname.' '.$invoice->lname ?></td>
									    <td width="10%"><a href="<?= base_url() ?>Clients/viewinvoiceDetails/<?= $invoice->id ?>"  class="btn btn-primary btn-xs" target="_blank">View Details</a></td>
									</tr>	

<?php }
}
?>
