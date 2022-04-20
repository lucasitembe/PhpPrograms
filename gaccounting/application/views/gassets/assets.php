<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="<?= base_url('gassets') ?>">Assets Management</a></li>
          
            <li><i class='fa fa-archive'></i>  My Assets</li>
            <div class="pull-right"><b>Date: </b> <?= date('d-M-Y') ?></div>						  	
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div id='table-options' class="pull-right">
            <button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add New</button>
            <a href="#" class="btn btn-primary"><i class="fa fa-print"></i> Print</a> 
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> My Assets </h3>
            </div>
            <div class="panel-body">
            <div class="row form-inline ">
                <div class="pull-right">
                <div class="form-group">
                    <div class="col-md-6 col-sm-8 col-xs-10">
                                            <select id="asset_category" name="asset_category" class="form-control" required="required">
                                             <option selected="selected" value="" >--All Category--</option>
                                               <?php
                                            if($categories){
                                               foreach ($categories as $category) {
                                                if($category->enable){
                                                  echo '<option value="'.$category->cat_id.'">'.$category->cat_name.'</option>';
                                                }
                                               }
                                            }
                                               ?>
                                            </select>
                                        </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-8 col-xs-10">
                                            <select name="location_id"  id="location_id" class="form-control" required>
                                            <option selected="selected" value="" >--All Location--</option>
                                               <?php
                                            if($locations){
                                               foreach ($locations as $location) {
                                                if($location->enable){
                                                  echo '<option value="'.$location->loc_id.'">'.$location->loc_name.'</option>';
                                                }
                                               }
                                            }
                                               ?>
                                            </select>
                                        </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8">
                    <input type="text" name="search_key_word" id="search_key_word"  placeholder="Search Keyword" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-4">
                        <button class="btn btn-primary" onclick="filterAssets()"><i class="fa fa-search"></i> Filter</button>
                    </div>
                </div> 
                </div>
            </div>
            <hr>
                <div class="table-responsive" id="ajaxUpdateContainer"> 
                    <table class="table table-bordered  table-striped table-hover"> 
                        <thead> 
                            <tr> 
                                <th>#</th> 
                                <th>Asset Category</th>
                                <th>Description</th>
                                <th>Location</th> 
                                <th>Supplier</th>
                                <th>Purchase Date</th>
                                <th>Purchase Price</th>
                                <th>Carrying Amount</th>
                                <!--<th>Last update</th> -->
                                <th>Available</th>
                                <th>&nbsp;</th>

                            </tr> </thead> 
                        <tbody> 
                            <?php
                            
                            
                            
                            $i = 1+$page;
                            if($assetList){
                            foreach ($assetList as $dt) {
                                echo "<tr>";
                                echo "<td>" . $i++ . "</td>";
                                echo "<td>" . $dt->cat_name . "</td>";
                                echo "<td>" . $dt->asset_short_desc . "</td>";
                                echo "<td>" . $dt->loc_name .", ". $dt->address ."</td>";
                                ?>
                                <td><a href="<?= base_url('supplier/view/'.$dt->supplier_id) ?>"><?= $dt->supplierName; ?></a></td>
                                <?php
                                echo "<td>" . substr($dt->purchase_date,0,11) . "</td>";
                                echo "<td>" . number_format($dt->purchase_price)." (". $dt->currency_code .")</td>";
                                ?>
                                <td><?php
                                $depn_info = $this->Helper->calculateDepreciation($dt->asset_id,date('Y-m-d'));
                                echo number_format($depn_info['carrying_value'],2).' ('.$depn_info['currency_code'].')';
                                ?></td>
                                <td>
                                    <input type="checkbox" <?php if($dt->is_available=='YES'){echo 'checked';} ?> onchange="assetTracking(this,'<?= $dt->asset_id ?>')" style="width:25px;height:25px;margin-left:17px;">
                                </td>
                                <?php
                               // echo "<td>" . $dt->modified_date . "</td>";
                                echo "<td width='18%'>";
                                echo  "<div class='btn-group'>
                                      <a class='btn btn-primary btn-xs editAssetBtn'  href='".base_url('gassets/updateForm/'.$dt->asset_id)."'><i class='fa fa-pencil-square-o'></i> Edit</a> ";
                                
                               echo "<button class='btn btn-info btn-xs' data-toggle='modal' data-target='#assetModal".$dt->asset_id."'><i class='fa fa-eye'></i> View</button>
                                      <div>";
                            ?>
                            <!-- View Asset modal starts -->
    <div class="modal fade  model-reload" id="assetModal<?php echo $dt->asset_id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel"><i class='fa fa-archive'></i> My Asset</h4>
                </div>
                <div class="modal-body">
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
	                   </div>
        <!-- /third column -->
                   </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit"  class="btn btn-primary" value="submitAssetLocation">Edit</button>
                </div>
            </div>
        </div>
    </div>
                             <!-- View Asset modal ends -->
                            <?php     
                                 echo "</td>";
                                echo "</tr>";
                            }
                        }
                            ?>
                        </tbody> 
                    </table> 
                    
                    <center> <?php echo $links; ?></center>

                </div>
                
                <!--currency form model-->

                <!-- Modal -->
                <div class="modal fade  model-reload" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Add New Asset</h4>
                            </div>
                            <?php
                            $attributes = array('autocomplete' => 'off', 'class' => 'form-horizontal form-label-left', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'addAssetform','enctype' => 'multipart/form-data');
                            echo form_open('gassets/assets', $attributes);
                            ?>

                            <div class="modal-body">
                                <div class="x_content">
                                    <br />

                                    <div class="row">
                                    	<div class="col-md-12 col-sm-12">
	                                    	<div class="alert alert-success" role="alert" data-dismiss="alert" id="alertSuccess">
	                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                                        <strong>Success!</strong> <span id="succmsg"></span>
	                                    </div>
	                                    <div class="alert alert-danger" role="alert" data-dismiss="alert" id="alertError">
	                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                                        <strong>Error!</strong> <span id="errmsg"></span>
	                                    </div>
                                    	</div>
                                    </div>

                                    <div class="row">
                                <!-- asset first column -->
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <div class="col-md-11 col-md-offset-1">
                                        <!--<label  style="width:300px;height:300px;border:1px solid #999;margin-left:10px;text-align:center;"> -->
                                            <img id="asset_image" for="img_upload_btn" src="<?= base_url() ?>/assets/images/default.jpg" style="width:380px;height:300px;border-radius:3px;border:1px solid #999;">
                                       <!-- </label> -->
                                    </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-9 col-md-offset-1 col-sm-8 col-xs-12">
                                            <input  type="file" id="img_upload_btn" name="image_upload" class="col-md-7 col-xs-12">
                                        </div>
                                    </div>
                                   
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Asset Category <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-8 col-xs-10">
                                            <select id="asset_category" name="asset_category" class="form-control" required="required">
                                             <option selected="selected" value="" disabled="disabled">--Select Category--</option>
                                               <?php
                                            if($categories){
                                               foreach ($categories as $category) {
                                                if($category->enable){
                                                  echo '<option value="'.$category->cat_id.'">'.$category->cat_name.'</option>';
                                                }
                                               }
                                            }
                                               ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-sm-2 col-xs-2" style="padding:0;">
                                            <a class="btn btn-primary" href="<?= base_url('gassets/categories') ?>">Add</a>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Serail # <span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <input  type="text" id="serial_number" name="serial_number" maxlength="45" required class="form-control col-md-7 col-xs-12">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Asset Name <span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <input  type="text" id="short_description" name="short_description" maxlength="45" required class="form-control col-md-7 col-xs-12">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Description 
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                           <!--<input  type="text" id="country" name="country_id" maxlength="45" required class="form-control col-md-7 col-xs-12">-->
                                            <textarea name="long_description" id="long_description" class="form-control col-md-7 col-xs-12"></textarea>   
                                        </div>
                                    </div>
                                    
                                </div>
                                <!-- /asset first column -->
                                <!-- first column -->        
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Barcode <span class="required"></span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <input  type="text" id="barcode" name="barcode" maxlength="45" class="form-control col-md-7 col-xs-12">
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Fund
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <input  type="text" id="fund" name="fund" maxlength="45" class="form-control col-md-7 col-xs-12">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Users
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <input  type="text" id="asset_users" name="asset_users" maxlength="45" class="form-control col-md-7 col-xs-12">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Location <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-8 col-xs-10">
                                            <select name="location_id"  id="location_id" class="form-control" required>
                                            <option selected="selected" value="" disabled="disabled">--Select Location--</option>
                                               <?php
                                            if($locations){
                                               foreach ($locations as $location) {
                                               	if($location->enable){
                                                  echo '<option value="'.$location->loc_id.'">'.$location->loc_name.'</option>';
                                               	}
                                               }
                                           	}
                                               ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-sm-2 col-xs-2" style="padding:0;">
                                        	<a class="btn btn-primary" href="<?= base_url('gassets/assetlocations') ?>">Add</a>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Depreciation Type<span class="required"></span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <select type="text" id="depreciation" name="depreciation" maxlength="45" class="form-control col-md-7 col-xs-12" required="required">
                                            	<option value="" selected="selected" disabled="disabled">--Select Type</option>
                                            	<option value="0">Straight Line</option>
                                            	<option value="1">Diminishing Value</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Depreciation Rate<span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <input   type="text" id="depreciation_rate" name="depreciation_rate" maxlength="45" required class="form-control col-md-7 col-xs-12 numberonly">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Depreciation Period (days)<span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <input  type="number" min="0" max='365' id="depreciation_period" name="depreciation_period" maxlength="45" required class="form-control col-md-7 col-xs-12 numberonly" placeholder="Number of days">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12 " for="currncy-name">Salvage Value
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <input  type="number" min="0" id="salvage_value" name="salvage_value" maxlength="45"  class="form-control col-md-7 col-xs-12 numberonly" >
                                        </div>
                                    </div>
                                            <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Purchase Date <span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <input  type="text" id="purchase_date" name="purchase_date" maxlength="45" required class="form-control col-md-7 col-xs-12 date">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Purchase Price<span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <input  type="text" id="purchase_price" name="purchase_price" maxlength="45" required class="form-control col-md-7 col-xs-12 numberonly">
                                        </div>
                                    </div>
                                    
                                    </div>
                                    <!-- /first column -->
                                    <!-- second column -->
                                    <div class="col-md-4 col-sm-12">
                                    	
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Currency <span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <select name="currency_id"  id="currency_id" class="form-control col-md-7 col-xs-12" required>
                                            <option value="" disabled="disabled" selected="selected">--Select Currency--</option>
                                               <?php
                                            if($currencies) {
                                               foreach ($currencies as $currency) {
                                                if(strtolower($currency->currency_code)=='tzs'){
                                                  echo '<option value="'.$currency->currency_id.'">'.$currency->currency_name.' ('.$currency->currency_code.')</option>';
                                                }
                                               }
                                           	}
                                               ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Supplier <span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <select name="supplier_id" required  id="supplier_id" onchange="getSuppliername(this)" class="form-control col-md-7 col-xs-12">
                                            <option value="" disabled="disabled" selected="selected">--Select Supplier--</option>
                                               <?php
                                            if($suppliers) {
                                               foreach ($suppliers as $supplier) {
                                                  echo '<option value="'.$supplier->supplier_id.'">'.$supplier->suppliername.'</option>';
                                               }
                                            }
                                            //suppliers from ehms
                                             if($ehms_suppliers) {
                                               foreach ($ehms_suppliers as $supplier) {
                                                  echo '<option value="0">'.$supplier.'</option>';
                                               }
                                            }
                                               ?>
                                            </select>
                                            <input type="text" name="supplier_name1" id="supplier_name1" hidden="hidden">
                                            <script type="text/javascript">
                                                function getSuppliername(obj){
                                                    //console.log(obj.options[obj.selectedIndex].text);
                                                    $('#supplier_name1').val(obj.options[obj.selectedIndex].text);
                                                }
                                                
                                               
                                            </script>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="acquisition_method">Acquisition Method 
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <select name="acquisition_method"  id="acquisition_method" class="form-control col-md-7 col-xs-12" >
                                            <option value="" selected="selected">--Select Method--</option>
                                            <option value="Procurement">Procurement</option>
                                            <option value="Donation">Donation</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="disposition_method">Disposition Method 
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <select name="disposition_method"  id="disposition_method" class="form-control col-md-7 col-xs-12" >
                                            <option value="" selected="selected">--Select Method--</option>
                                               <option value="Sales">Sales</option>
                                               <option value="Donation">Donation</option>
                                               <option value="Auction">Auction</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Insurance Cover
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <input  type="text" id="insurance_cover" name="insurance_cover" maxlength="45" class="form-control col-md-7 col-xs-12">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Warrant Info <span class="required"></span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <textarea id="warrant_info" name="warrant_info" class="form-control col-md-7 col-xs-12"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Date Placed In Service
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <input  type="text" id="date_placed_in_service" name="date_placed_in_service" maxlength="45" class="form-control col-md-7 col-xs-12 date">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Maintanence Schedule (days)
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <input  type="number" min="0" id="maintanence_schedule" name="maintanence_schedule" maxlength="45"  class="form-control col-md-7 col-xs-12 numberonly" placeholder="Number of days">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Maintanence Reminder (days)
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <input  type="number" min="0" id="maintanence_reminder" name="maintanence_reminder" maxlength="45" class="form-control col-md-7 col-xs-12 numberonly" placeholder="Number of days">
                                        </div>
                                    </div>
                            </div>
                            <!-- /second column -->
                        </div>
                                    
                                    

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="submit" id="saveAsset" name="submitAssetLocation" class="btn btn-primary" value="submitAssetLocation">Save changes</button>
                            </div>

                            </form>
                        </div>
                    </div>
                </div>           

            </div>
        </div>
		
		<!-- /closing update div comes here -->
    </div>







    <!-- update asset modal -->
                 <!-- Modal -->
               <div class="modal fade  model-reload" id="editAssetModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Update Asset</h4>
                            </div>
                             <?php
                            $attributes = array('autocomplete' => 'off', 'class' => 'form-horizontal form-label-left', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'updateAssetform');
                            echo form_open('gassets/updateAsset', $attributes);
                            ?>
                             <div class="modal-body">
                                <div class="x_content">
                                    <br />

                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <div class="alert alert-success" role="alert" data-dismiss="alert" id="alertSuccess1">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <strong>Success!</strong> <span id="succmsg1"></span>
                                        </div>
                                        <div class="alert alert-danger" role="alert" data-dismiss="alert" id="alertError1">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <strong>Error!</strong> <span id="errmsg1"></span>
                                        </div>
                                        </div>
                                    </div>
                            <div id="modalBody">
                            	
                            </div>
                             </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="submit" name="" class="btn btn-primary" value="">Save changes</button>
                            </div>

                            </form>
                        </div>
                    </div>
                </div>
<input type="text" hidden="hidden" id="url" value="<?= base_url(); ?>">