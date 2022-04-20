<div class="">
	<h3><b>Asset Depreciation Details</b></h3>
	<h4><b>ACCOUNT:</b> <i><?= $ledger_name ?></i></h4>
	<h4><b>On Date:</b> <i><?= date('d-M-Y') ?></i></h4>
</div>
<hr>

<div class="">
<table class="table table-bordered">
              		<thead style="background:#999;color:#fff;">
              			<tr>
              				<th>#</th>
              				<th>Asset Name</th>
              				<th>Asset Cost (TZS)</th>
              				<th>Depreciation Amount (TZS)</th>
              				<th>Caryying Amount (TZS)</th>
              			</tr>
              		</thead>
              		<tbody>

              		<?php if(isset($assets)){ 
              			$sn = 0;
              			$grandTotalAssetCost = 0;
              			$grandTotalDpnAmount = 0;
              			$grandTotalCarrAmount = 0;
              			foreach ($assets as $asset) { 
              				$depn_info = $this->Helper->calculateDepreciation($asset->asset_id,date('Y-m-d'));
              				$grandTotalAssetCost += $asset->purchase_price;
	              			$grandTotalDpnAmount += $depn_info['depn_amount'];
	              			$grandTotalCarrAmount += $depn_info['carrying_value'];
              			?>
              				<tr>
              					<td><?= ++$sn ?></td>
	              				<td><?= $asset->asset_short_desc ?></td>
	              				<td class='text-right'><?= number_format($asset->purchase_price,2) ?></td>
	              				<td class='text-right'><?= number_format($depn_info['depn_amount'],2) ?></td>
	              				<td class='text-right'><?= number_format($depn_info['carrying_value'],2) ?></td>
	              			</tr>
              			<?php }
              			} ?>
              			<tr>
              				<td colspan="2"><b>Grand Total</b></td>
              				<td class='text-right'><b><?= number_format($grandTotalAssetCost,2) ?></b></td>
	              			<td class='text-right'><b><?= number_format($grandTotalDpnAmount,2) ?></b></td>
	              			<td class='text-right'><b><?= number_format($grandTotalCarrAmount,2) ?></b></td>
              			</tr>
              		</tbody>
              	</table>
</div>
<?php if($isReport==false){ ?>
	<div class="pull-right"><a href="<?= base_url() ?>Gassets/assetByledgerReport/<?= $ledger_id ?>?ledger_name=<?= $ledger_name ?>" target='_blank'><button class="btn btn-primary"><span class="fa fa-print"></span> Generate PDF</button></a></div>
<?php } ?>

