<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="<?= base_url('gassets') ?>"> Assets Management</a></li>
            
            <li><i class="fa fa-map-marker"></i> Locations</li>						  	
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
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Asset Location </h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive"> 
                    <table class="table table-bordered  table-striped table-hover"> 
                        <thead> 
                            <tr> 
                                <th>#</th> 
                                <th>Location</th>
                                <th>Country</th>
                                <th>Address</th> 
                                <th>Phone</th>
                                <th>Fax</th>
                                <th>E-mail</th>
                                <th>Last update</th> 
                                <th>&nbsp;</th> 
                            </tr> </thead> 
                        <tbody> 
                            <?php
                            
                            
                            
                            $i = 1+$page;
                            if($locationList){
                            foreach ($locationList as $dt) {
                                echo "<tr>";
                                echo "<td>" . $i++ . "</td>";
                                echo "<td>" . $dt->loc_name . "</td>";
                                echo "<td>" . $dt->country_name . "</td>";
                                echo "<td>" . $dt->address . "</td>";
                                echo "<td>" . $dt->phone . "</td>";
                                echo "<td>" . $dt->fax . "</td>";
                                echo "<td>" . $dt->email . "</td>";
                                echo "<td>" . $dt->modified_date . "</td>";
                                echo "<td width='18%'>";
                                echo  "<div class='btn-group'>
                                      <a class='btn btn-primary btn-xs editLocationBtn' href='".base_url('gassets/locationform/'.$dt->loc_id)."'><i class='fa fa-pencil-square-o'></i> Edit</a> ";
                                if($dt->enable){
                                   echo "<a class='btn btn-info btn-xs' href='".base_url('gassets/disableAssetslocation/'.$dt->loc_id)."'><i class='fa fa-check'></i> Enabled</a>
                                      <div>";
                                } else {
                                   echo "<a class='btn btn-danger btn-xs' href='".base_url('gassets/enableAssetslocation/'.$dt->loc_id)."'><i class='fa fa-close'></i> Disabled</a>
                                      <div>";
                                }
                                     
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
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Add New Location</h4>
                            </div>
                            <?php
                            $attributes = array('autocomplete' => 'off', 'class' => 'form-horizontal form-label-left', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'assetlocationform');
                            echo form_open('gassets/assetlocations', $attributes);
                            ?>

                            <div class="modal-body">
                                <div class="x_content" id="currform_modal">
                                    <br />

                                    <div class="alert alert-success" role="alert" data-dismiss="alert" id="alertSuccess">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <strong>Success!</strong> <span id="succmsg"></span>
                                    </div>
                                    <div class="alert alert-danger" role="alert" data-dismiss="alert" id="alertError">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <strong>Error!</strong> <span id="errmsg"></span>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Location Name <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  type="text" id="location" name="loc_name" maxlength="45" required class="form-control col-md-7 col-xs-12" placeholder="type the location name here...">
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
                                               foreach ($countries as $country) {
                                                  echo '<option value="'.$country->country_id.'">'.$country->country_name.'</option>';
                                               }
                                            }
                                               ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Address <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  type="text" id="address" name="address" maxlength="45" required class="form-control col-md-7 col-xs-12" placeholder="ex. P O BOX xxxx Congo street">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Phone <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  type="number" id="phone" name="phone" maxlength="45" required class="form-control col-md-7 col-xs-12 numberonly" placeholder="ex. 255xxxxxx or 0717xxxxxx">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Fax <span class="required"></span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  type="text" id="fax" name="fax" maxlength="45" class="form-control col-md-7 col-xs-12 numberonly" placeholder="Fax number">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">E-mail <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  type="email" id="email" name="email" maxlength="45" required class="form-control col-md-7 col-xs-12" placeholder="ex. someone@gmail.com">
                                        </div>
                                    </div>
                                    

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="submit" name="submitAssetLocation" class="btn btn-primary" value="submitAssetLocation">Save changes</button>
                            </div>

                            </form>
                        </div>
                    </div>
                </div>           

            </div>
        </div>
    </div>

     <!-- update category modal -->
                 <!-- Modal -->
               <div class="modal fade  model-reload" id="editLocationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Update Category</h4>
                            </div>
                             <?php
                            $attributes = array('autocomplete' => 'off', 'class' => 'form-horizontal form-label-left', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'updateLocationform');
                            echo form_open('gassets/updateAssetLocation', $attributes);
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
