<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="<?= base_url('clients/') ?>"> Clients</a></li>
            <li><i class="fa fa-list-alt"></i> Generate client invoice</li>						  	
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div id='table-options' class="pull-right">
            <button type="button" class="btn btn-primary" id="addInvoiceBtn" onclick="createClientInvoice(this,'<?= $client->client_id ?>')"><i class="fa fa-plus"></i> Create New Invoice</button>
            <a href="#" class="btn btn-primary"><i class="fa fa-print"></i> Print</a> 
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i>Client Invoices </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                	<div class="col-md-12">
                		<table class="table table-bordered ">
                			<thead>
	                			<tr style="background:#999;color:#fff;">
	                				<th colspan="5">Client details</th>
	                			</tr>
	                			<tr>
	                                <th>Client name</th> 
	                                <th>Address</th> 
	                                <th>Contact Name</th>
	                                <th>Contact Phone</th>
	                                <th>Contact Email</th>
	                            </tr>
                			</thead>
                			<tbody>
                				<tr>
                					<td><?= $client->client_name ?></td>
                					<td><?= $client->address ?></td>
                					<td><?= $client->contactname ?></td>
                					<td><?= $client->contact_phone ?></td>
                					<td><?= $client->email ?></td>
                				</tr>
                			</tbody>
                		</table>
                	</div>
                </div>
                <div class="table=responsive">
                	<table class="table table-striped table-condensed">
                		<thead>
                			<tr style="background:#999;color:#fff;">
                				<th colspan="6">Invoice List</th>
                			</tr>
                			<tr>
                				<th>Sn</th>
                				<th>invoice #</th>
                				<th>invoice Date</th>
                				<th>Amount</th>
                				<th>Prepared By</th>
                				<th>Actions</th>
                			</tr>
                		</thead>
                		<tbody id="client_invoice_list">
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
								} ?>

                		</tbody>
                	</table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="mainDialog" style="display:none;">this is default content</div>

