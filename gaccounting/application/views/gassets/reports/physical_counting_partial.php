<?php
$depn_info = $this->Helper->calculateDepreciation($dt->asset_id,date('Y-m-d'));
?>
<input type="text" style="display:none;" id="asset_id" value="<?= $dt->asset_id ?>">
<hr>
<div class="row">
                   <!-- first column -->
                   <div class="col-md-3">
                        <?php
                            $image  = '';
                            if($dt->asset_image!='' && file_exists($dt->asset_image)){
                                $image = base_url().$dt->asset_image;
                            } else {
                                 $image = base_url('assets/images/default.jpg');
                            }
                        ?>
                       <img src="<?=  $image ?>" style="width:325px;height:300px;border-radius:3px;">
                       <br>
                       <table class="table table-striped" style="margin-top:34px;">
                           <tr>
                                <td><b>Asset Name</b></td> <td><?= $dt->asset_short_desc; ?></td>
                           </tr>
                           <tr>
                                        <td ><b>Category</b></td> <td><?= $dt->cat_name; ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Serial #</b></td> <td><?php
                                         if(!is_null($dt->asset_serial_number)) echo $dt->asset_serial_number; else echo '-----------';
                                          ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Barcode</b></td> <td><?php
                                         if(!empty($dt->asset_bar_code)) echo $dt->asset_bar_code; else echo '-----------';
                                          ?></td>
                                    </tr>
                            <tr>
                                        <td><b>Location</b></td> <td><?= $dt->loc_name.", ". $dt->address; ?></td>
                                    </tr>
                                    
                       </table>
                   </div>
                   <!-- /first column -->
                   <!-- second column -->
	                   <div class="col-md-5" id='asset_info_div'>
	                   		 <table class="table table-striped table-hover table-sm">
		                    	<thead class="thead-default">
		                    		<th colspan='2'>Asset Information</th>
		                    	</thead>
		                    	<tbody>
		                    		
                                    <tr>
                                        <td><b>Date Purchased</b></td> <td><?= $dt->purchase_date; ?></td>
                                    </tr>
		                    		
		                    		<tr>
		                    			<td><b>Description</b></td> <td><?= $dt->asset_long_desc; ?></td>
		                    		</tr>
		                    		
		                    		<tr>
		                    			<td><b>Date Placed in Service</b></td> <td><?= $dt->date_placed_in_service; ?></td>
		                    		</tr>
		                    		<tr>
		                    			<td><b>Price</b></td> <td><?= number_format($dt->purchase_price)." ( ". $dt->currency_code ." )"; ?></td>
		                    		</tr>
                                    <tr>
                                        <td><b>Acquisition Method</b></td> <td><?= $dt->acquisition_method; ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Disposition Method</b></td> <td><?= $dt->disposition_method; ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Asset Users</b></td> <td><?= $dt->asset_users; ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Supplier</b></td> <td><a href="<?= base_url('supplier/view/'.$dt->supplier_id) ?>"><?= $dt->supplierName; ?></a></td>
                                    </tr>
		                    		<tr>
		                    			<td><b>Depreciation Type</b></td> <td><?php
		                    			 if($dt->depn_type=='0') echo "Straight Line";
		                    			 else if($dt->depn_type=='1') echo 'Diminishing Value';
		                    			  ?></td>
		                    		</tr>
		                    		<tr>
		                    			<td><b>Depreciation Period</b></td> <td><?php
		                    			 if(!is_null($dt->depn_rate)) echo number_format($dt->depriacation_period) ." days "; else echo '-----------';
		                    			  ?></td>
		                    		</tr>
		                    		<tr>
		                    			<td><b>Depreciation Rate</b></td> <td><?php
		                    			 if(!is_null($dt->depn_rate)) echo $dt->depn_rate ."% "; else echo '-----------';
		                    			  ?></td>
		                    		</tr>
		                    		<tr>
		                    			<td><b>Salvage Value</b></td> <td><?php
		                    			 if(!is_null($dt->salvage_value)) echo $dt->salvage_value ." ( ".$dt->currency_code." )"; else echo '-----------';
		                    			  ?></td>
		                    		</tr>
		                    		<tr>
		                    			<td><b>Insurance Cover</b></td> <td><?php
		                    			 if(!is_null($dt->insurance_cover)) echo $dt->insurance_cover; else echo '-----------';
		                    			  ?></td>
		                    		</tr>
		                    		<tr>
		                    			<td><b>Warrant information</b></td> <td><?php
		                    			 if(!is_null($dt->warrant_information)) echo $dt->warrant_information; else echo '-----------';
		                    			  ?></td>
		                    		</tr>
		                    		<tr>
		                    			<td><b>Maintanence Schedule</b></td> <td><?php
		                    			 if(!is_null($dt->maintanence_schedule)) echo number_format($dt->maintanence_schedule) ." days "; else echo '-----------';
		                    			  ?></td>
		                    		</tr>
		                    		<tr>
		                    			<td><b>Maintanence Remider</b></td> <td><?php
		                    			 if(!is_null($dt->maintanence_reminder)) echo number_format($dt->maintanence_reminder) ." days "; else echo '-----------';
		                    			  ?></td>
		                    		</tr>

		                    	</tbody>
		                    </table>
	                   </div>
                       <!-- /second column -->
                       <!-- third column -->
	                   <div class="col-md-4" id='assessment_info_div'>
	                   <h2>Asset Assessment</h2>
                       
	                   		<table class="table table-hover table-sm">
		                    	<thead class="thead-default">
		                    		
		                    	</thead>
		                    	<tbody>
                                <tr>
                                   <td><b>Purchase Date</b></td>
                                   <td>
                                  
                                    <input readonly='readonly' type='text' value='<?= substr($dt->purchase_date,0,10) ?>'  name='depn_date' class='form-control'></td>
                               </tr>
                               <tr>
                                   <td><b>Depreciation Date</b></td>
                                   <td> <input type='text' value='<?php echo date('Y-m-d'); ?>'  name='<?= $dt->asset_id ?>' class='depn_date form-control'></td>
                               </tr>
                               
		                    	
		                    	</tbody>
		                    </table>
                            <table class='table table-hover table-sm' >
                                <tbody id='depn_info<?= $dt->asset_id ?>'>
                                    <?php
                                        echo '<tr>';
        echo '<td><b>Purchase Amount</b></td>';
        echo '<td>'.number_format($depn_info['purchase_amount'],2).' ('.$depn_info['currency_code'].')</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td><b>Depreciation Amount</b></td>';
        echo '<td>'.number_format($depn_info['depn_amount'],2).' ('.$depn_info['currency_code'].')</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td><b>Carrying Amount</b></td>';
        echo '<td>'.number_format($depn_info['carrying_value'],2).' ('.$depn_info['currency_code'].')</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td><b>Number of Days</b></td>';
        echo '<td>'.number_format($depn_info['day_diff'],2).' days</td>';
        echo '</tr>';
                                    ?>
                                </tbody>
                            </table>
                        <!-- tracking buttons -->
                       <div class="row">
                       	<div class="col-md-1">
                       		<input type="checkbox" name="available" value="available" id="available" style="width:30px;height:30px;">
                       	</div>
                       	<div class="col-md-4">
                       		<label for="available" style="font-size:20px;margin-top:5px;" title="Check this if Assets is Physically found">Available</label>
                       	</div>
                       	<div class="col-md-4">
                       	<input type="submit" name="submit" id="submitAssetTracking" onclick="submitAssetTracking()" class="btn btn-primary">
                       	</div>
                       </div>
                        	
                        
                        
                        <!-- /tracking buttons -->
	                   </div>
        <!-- /third column -->
                   </div>