                                   <input type="text" name="supplier_id" hidden="hidden" value="<?= $supplier->supplier_id ?>">
                                      <div class="row">
                                    <div class="col-md-6">
                                    	
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="">Client  name <span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                            <input  type="text" id="supplier_name" name="supplier_name" maxlength="45" required class="form-control col-md-7 col-xs-12" value="<?= $supplier->suppliername ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" >Address <span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                            <input  type="text" id="address" name="address" maxlength="45" required class="form-control col-md-7 col-xs-12" value="<?= $supplier->address ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" >Fax <span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                            <input  type="text" id="fax" name="fax" maxlength="45" required class="form-control col-md-7 col-xs-12 numberonly" value="<?= $supplier->fax ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="phone">Phone <span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                            <input  type="text" id="phone" name="phone" maxlength="45" required class="form-control col-md-7 col-xs-12 numberonly" value="<?= $supplier->telephone ?>">
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-md-6">
                                    
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="email">Email <span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                            <input  type="email" id="email" name="email" data-error="Email address is invalid" required class="form-control col-md-7 col-xs-12" value="<?= $supplier->email ?>">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12">Contact Name  <span class="required">*</span></label>
                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                             <input  type="text" id="contact_name" name="contact_name"  required class="form-control col-md-7 col-xs-12" value="<?= $supplier->contactname ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12">Contact Phone <span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                            <input  type="text" id="contact_phone" name="contact_phone" maxlength="45" required class="form-control col-md-7 col-xs-12 numberonly" value="<?= $supplier->contact_phone ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="contact email">Contact Email <span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                            <input  type="email" id="contact_email" name="contact_email" required class="form-control col-md-7 col-xs-12" value="<?= $supplier->contact_email ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="url">Website Url <span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                            <input  type="text" id="web_url" name="web_url" required class="form-control col-md-7 col-xs-12" value="<?= $supplier->url ?>">
                                        </div>
                                    </div>
                                    

                                    <div class="ln_solid"></div>
                                    <div class="form-group">
                                        <!--                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                                                  <button type="submit" class="btn btn-primary">Cancel</button>
                                                                  <button type="submit" class="btn btn-success">Submit</button>
                                                                </div>-->
                                    </div>
                                    </div>
                                    </div>
<script type="text/javascript">
    $(function(){
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