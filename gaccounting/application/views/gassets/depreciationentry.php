<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="<?= site_url('Home') ?>"> Dashboard</a></li>
            <li><i class="fa fa-laptop"></i><a href="<?= site_url('gledger') ?>"> General Ledger</a></li>                 
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div id='table-options' class="pull-right">
            <a disabled href="<?= base_url('gledger/chartofledgers') ?>?report" id="ajaxUpdateContainerPdf" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i> Pdf</a> 
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Fixed Asset Depreciation Entry on  <i> <?= date('d-M-Y') ?></i></h3>
            </div>
            <div class="panel-body" id="ajaxUpdateContainer">
              	<table class="table table-bordered">
              		<thead style="background:#999;color:#fff;">
              			<tr>
              				<th>#</th>
              				<th>Ledger</th>
              				<th>Total Asset Cost (TZS)</th>
              				<th>Depreciation Amount (TZS)</th>
              				<th>Caryying Amount (TZS)</th>
              			</tr>
              		</thead>
              		<tbody>

              		<?php if(isset($depnPerLedger)){ 
              			$sn = 0;
              			$grandTotalAssetCost = 0;
              			$grandTotalDpnAmount = 0;
              			$grandTotalCarrAmount = 0;
              			foreach ($depnPerLedger as $value) { 
              				$grandTotalAssetCost += $value['total_asset_cost'];
	              			$grandTotalDpnAmount += $value['total_dpn_amount'];
	              			$grandTotalCarrAmount += $value['total_carrying_amount'];
              			?>
              				<tr>
              					<td><?= ++$sn ?></td>
	              				<td><a style="text-decoration: none;" title="View Details" href='<?= $value['ledger_id'] ?>' class="getAssetsByLedger"><?= $value['ledger_name'] .' ('.$value['acc_name'].')' ?></a></td>
	              				<td class='text-right'><?= number_format($value['total_asset_cost'],2) ?></td>
	              				<td class='text-right'><?= number_format($value['total_dpn_amount'],2) ?></td>
	              				<td class='text-right'><?= number_format($value['total_carrying_amount'],2) ?></td>
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
           <div class="panel-footer" style="background:#f0f0f0;border:1px solid #c0c0c0;">
           	 <button class="btn btn-primary" id="sendDepreciation">Submit To Accounting As Depreciation Amount</button>
           </div>
        </div>
    </div>
</div>

<div id="myDiag" style="display:none;background:#f9f9f9;">Dialog content comes here....!</div>
