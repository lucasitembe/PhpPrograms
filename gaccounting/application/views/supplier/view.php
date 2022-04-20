<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="<?= base_url('gassets/assets') ?>"> Assets </a></li>
            
            <li><i class="fa fa-briefcase"></i> Supplier <em>Details</em></li>						  	
        </ol>
    </div>
</div>

<?php
//echo $id.'<br>';
//echo '<h2>Currently! This page is under construction</h2>';
//echo $supplier->suppliername;
?>
<!--<img style="margin-left:26%;" src="<?= base_url('assets/images/giphy.gif') ?>"> -->

<div class="row">
    <div class="col-lg-12">
    	<div id='table-options' class="pull-right">
            <a href="#" class="btn btn-primary"><i class="fa fa-print"></i> Print</a> 
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Asset Supplier Details </h3>
            </div>
            <div class="panel-body">
	            <div class="table-responsive"> 
                    <table class="table table-bordered  table-striped table-hover">
                    <thead>
                    	
                    </thead>
                    <tbody>
                    	<tr>
                    		<td width="30%"><b>Supplier Name</b></td>
                    		<td><?= $supplier->suppliername ?></td>
                    	</tr>
                    	<tr>
                    		<td width="30%"><b>Address</b></td>
                    		<td><?= $supplier->address ?></td>
                    	</tr>
                    	<tr>
                    		<td width="30%"><b>Country</b></td>
                    		<td><?= $supplier->country_name ?></td>
                    	</tr>
                    	<tr>
                    		<td width="30%"><b>Currency</b></td>
                    		<td><?= $supplier->currency_name .' ('.$supplier->currency_code.')' ?></td>
                    	</tr>
                    	<tr>
                    		<td width="30%"><b>Fax</b></td>
                    		<td><?= $supplier->fax ?></td>
                    	</tr>
                    	<tr>
                    		<td width="30%"><b>E-mail</b></td>
                    		<td><?= $supplier->email ?></td>
                    	</tr>
                    	<tr>
                    		<td width="30%"><b>Telephone</b></td>
                    		<td><?= $supplier->telephone ?></td>
                    	</tr>
                    	<tr>
                    		<td width="30%"><b>Website URL</b></td>
                    		<td><a href="<?= $supplier->url ?>" target="_blank"><?= $supplier->url ?></a></td>
                    	</tr>
                    	<tr>
                    		<td width="30%"><b>Contact Name</b></td>
                    		<td><?= $supplier->contactname ?></td>
                    	</tr>
                    	<tr>
                    		<td width="30%"><b>Contact Phone</b></td>
                    		<td><?= $supplier->contact_phone ?></td>
                    	</tr>
                    	<tr>
                    		<td width="30%"><b>Contact E-mail</b></td>
                    		<td><?= $supplier->contact_email ?></td>
                    	</tr>
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
