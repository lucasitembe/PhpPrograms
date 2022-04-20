<?php
$client_id = '';
	if($invoice_cache){
		foreach ($invoice_cache as $value) { 
			$client_id = $value->client_id;
			?>
		<tr>
		    <td><?=  $value->narration ?></td>
		    <td><input type="checkbox" name="taxable" style="width:33px;height:20px;" disabled <?php if($value->is_taxable){echo 'checked';} ?>></td>
		    <th><?= number_format($value->amount,2) ?></th>
		    <td><input type="submit" value="Remove" onclick="removeInvoiceEntry('<?= $value->id ?>')" class="btn btn-danger btn-sm"></td>
		</tr>	
<?php	} ?>

<tr>
	<td colspan="4"><input type="Submit" class="btn btn-primary" onclick="submitInvoice('<?= $client_id ?>')"></td>
</tr>
<?php	}
?>
