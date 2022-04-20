<hr>
<table class="table table-striped table-hover">
	<thead>
		<tr>
			<th>#</th>
			<th>Asset Name</th>
			<th>Serial #</th>
			<th>Barcode</th>
			<th>Tracking Date</th>
			<th>Tracked By</th>
			<th>Available</th>
		</tr>
	</thead>
	<tbody>
	<?php
		if($assets){
			$sn=0;
			foreach ($assets as $asset) { ?>
				<tr>
					<th><?= ++$sn ?></th>
					<th><?= $asset->asset_short_desc ?> (<?= $asset->cat_name ?>)</th>
					<td><?= $asset->asset_serial_number ?></td>
					<td><?= $asset->asset_bar_code ?></td>
					<th><?= $asset->tracking_date_time ?></th>
					<th><?= $asset->tracked_by ?></th>
					<th><?= $asset->available ?></th>
				</tr>
		<?php	}
		}
	?>
	</tbody>
</table>