<input type="text" name="loc_id" value="<?= $location->loc_id ?>" hidden='hidden'>
<div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Location Name <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  type="text" id="location" name="loc_name" maxlength="45" required class="form-control col-md-7 col-xs-12" placeholder="type the location name here..." value="<?= $location->loc_name ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Country <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <!--<input  type="text" id="country" name="country_id" maxlength="45" required class="form-control col-md-7 col-xs-12">-->
                                            <select name="country_id"  id="country" class="form-control col-md-7 col-xs-12" required="required">
                                            <option value="" disabled="disabled" selected="selected">--Select Country--</option>
                                               <?php
                                            if($countries){
                                               foreach ($countries as $country) { ?>
<option <?php if($country->country_id==$location->country_id) echo "selected='selected'" ?> value="<?= $country->country_id ?>"><?= $country->country_name ?></option>
                                        <?php    }
                                            }
                                              ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Address <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  type="text" id="address" name="address" maxlength="45" required class="form-control col-md-7 col-xs-12" placeholder="ex. P O BOX xxxx Congo street" value="<?= $location->address ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Phone <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  type="number" id="phone" name="phone" maxlength="45" required class="form-control col-md-7 col-xs-12 numberonly" placeholder="ex. 255xxxxxx or 0717xxxxxx" value="<?= $location->phone ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Fax <span class="required"></span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  type="text" id="fax" name="fax" maxlength="45" class="form-control col-md-7 col-xs-12 numberonly" placeholder="Fax number" value="<?= $location->fax ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">E-mail <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  type="email" id="email" name="email" maxlength="45" required class="form-control col-md-7 col-xs-12" placeholder="ex. someone@gmail.com" value="<?= $location->email ?>">
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