
                           



                                    

                                    <div class="row">
                                    	<div class="col-md-6 col-sm-12">
                                    <input type="text" name="asset_id" hidden="hidden" id="asset_id" value="<?= $asset[0]['asset_id'] ?>">
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Asset Category <span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-10">
                                            <select id="asset_category" name="asset_category" class="form-control" required="required">
                                             <option selected="selected" value="" disabled="disabled">--Select Category--</option>
                                               <?php
                                            if($categories){
                                               foreach ($categories as $category) {
                                               	if($category->enable){ ?>
        <option <?php if($category->cat_id==$asset[0]['asset_catg']) echo "selected='selected'"; ?> value="<?php echo $category->cat_id; ?>"> <?php echo $category->cat_name; ?></option>
                                        <?php 	}
                                               }
                                           	}
                                          ?>

                                            </select>
                                        </div>
                                        
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Serial # <span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                   <input  type="text" id="serial_number" name="serial_number" maxlength="45" required class="form-control col-md-7 col-xs-12" value="<?= $asset[0]['asset_serial_number'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Heading <span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <input  type="text" id="short_description" name="short_description" maxlength="45" required class="form-control col-md-7 col-xs-12" value="<?= $asset[0]['asset_short_desc'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Description <span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                           <!--<input  type="text" id="country" name="country_id" maxlength="45" required class="form-control col-md-7 col-xs-12">-->
                                            <textarea name="long_description" id="long_description" required class="form-control col-md-7 col-xs-12"><?= $asset[0]['asset_long_desc']?></textarea>
                                            
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Barcode <span class="required"></span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <input  type="text" id="barcode" name="barcode" maxlength="45" class="form-control col-md-7 col-xs-12" value="<?= $asset[0]['asset_bar_code'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Location <span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-10">
                                            <select name="location_id"  id="location_id" class="form-control" required>
                                            <option selected="selected" value="" disabled="disabled">--Select Location--</option>
                                               <?php
                                            if($locations){
                                               foreach ($locations as $location) {
                                               	if($location->enable){ ?>
    <option <?php if($location->loc_id==$asset[0]['asset_loc']) echo "selected='selected'"; ?> value="<?= $location->loc_id ?>"><?= $location->loc_name ?></option>
                                        <?php  	}
                                               }
                                           	}
                                               ?>
                                            </select>
                                        </div>
                                        
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Depreciation Type<span class="required"></span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <select type="text" id="depreciation" name="depreciation" maxlength="45" class="form-control col-md-7 col-xs-12">
                                            	
<option  value="0" <?php if($asset[0]['depn_type']==0) echo "selected='selected'"; ?>>Straight Line</option>
<option value="1" <?php if($asset[0]['depn_type']==1) echo "selected='selected'"; ?>>Diminishing Value</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Depreciation Rate<span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
<input  type="text" id="depreciation_rate" name="depreciation_rate" maxlength="45" required class="form-control col-md-7 col-xs-12 numberonly" value="<?= $asset[0]['depn_rate'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Depreciation Period<span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
<input  type="number" min="0" id="depreciation_period" name="depreciation_period" maxlength="45" required class="form-control col-md-7 col-xs-12 numberonly" placeholder="Number of days" value="<?= $asset[0]['depriacation_period'] ?>">
                                        </div>
                                    </div>
                                    
                                    	</div>
                                    	<div class="col-md-6 col-sm-12">
                                    	<div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Salvage Value<span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
<input  type="number" min="0" id="salvage_value" name="salvage_value" maxlength="45" required class="form-control col-md-7 col-xs-12 numberonly" value="<?= $asset[0]['salvage_value'] ?>">
                                        </div>
                                    </div>
                                    		<div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Purchase Date <span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
<input  type="text" id="purchase_date" name="purchase_date" maxlength="45" required class="form-control col-md-7 col-xs-12" value="<?= $asset[0]['purchase_date'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Purchase Price<span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
<input  type="text" id="purchase_price" name="purchase_price" maxlength="45" required class="form-control col-md-7 col-xs-12 numberonly" value="<?= $asset[0]['purchase_price'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Currency <span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <select name="currency_id"  id="currency_id" class="form-control col-md-7 col-xs-12">
                                               <?php
                                            if($currencies) {
                                               foreach ($currencies as $currency) { ?>
    <option <?php if($currency->currency_id==$asset[0]['currency_id']) echo "selected='selected'"; ?> value="<?= $currency->currency_id ?>"> <?= $currency->currency_name.' ('.$currency->currency_code ?>)</option>
                                    <?php      }
                                           	}
                                               ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Supplier <span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <select name="supplier_id"  id="supplier_id" class="form-control col-md-7 col-xs-12">
                                               <?php
                                            if($suppliers) {
                                               foreach ($suppliers as $supplier) { ?>
<option <?php if($supplier->supplier_id==$asset[0]['supplier_id']) echo "selected='selected'"; ?> value="<?= $supplier->supplier_id ?>"><?= $supplier->suppliername ?></option>
                                              <?php }
                                            }
                                               ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Insurance Cover <span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
<input  type="text" id="insurance_cover" name="insurance_cover" maxlength="45" class="form-control col-md-7 col-xs-12" value="<?= $asset[0]['insurance_cover'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Warrant Info <span class="required"></span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <textarea id="warrant_info" name="warrant_info" class="form-control col-md-7 col-xs-12"><?= $asset[0]['warrant_information'] ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Date Placed In Service <span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
<input  type="text" id="date_placed_in_service" name="date_placed_in_service" maxlength="45" required class="form-control col-md-7 col-xs-12" value="<?= $asset[0]['date_placed_in_service'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Maintanence Schedule <span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
<input  type="number" min="0" id="maintanence_schedule" name="maintanence_schedule" maxlength="45" required class="form-control col-md-7 col-xs-12 numberonly" placeholder="Number of days" value="<?= $asset[0]['maintanence_schedule'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Maintanence Reminder<span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
<input  type="number" min="0" id="maintanence_reminder" name="maintanence_reminder" maxlength="45" required class="form-control col-md-7 col-xs-12 numberonly" placeholder="Number of days" value="<?= $asset[0]['maintanence_reminder'] ?>">
                                        </div>
                                    </div>
                                    	</div>
                                    </div>
                                    
                                    



<!-- ******************************************************************************* -->
                    
<script type="text/javascript">
    $(function(){
        jQuery('#purchase_date,#date_placed_in_service').datetimepicker({
                                    timepicker:true,
                                    format: 'Y-m-d H:i'
                                });
        /**
     * 
     * Allow number only
     * **/

    $(".numberonly").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                // Allow: Ctrl+A, Command+A
                        (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                        // Allow: home, end, left, right, down, up
                                (e.keyCode >= 35 && e.keyCode <= 40)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
    });

    });
</script>
