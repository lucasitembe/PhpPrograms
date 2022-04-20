<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="<?= base_url('supplier') ?>"> Home</a></li>
            <li><i class="fa fa-briefcase"></i>Clients</li>						  	
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
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Clients </h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive"> 
                    <table class="table table-bordered  table-striped table-hover"> 
                        <thead> 
                            <tr> 
                                <th>#</th> 
                                <th>Client name</th> 
                                <th>Address</th> 
                                <th>Contact Name</th>
                                <th>Contact Phone</th>
                                <th>Contact Email</th>
                                <th>&nbsp;</th> 
                            </tr> </thead> 
                        <tbody> 
                            <?php
                            $i = 1 + $page;

                            if (!empty($supplierList)) {
                                foreach ($supplierList as $dt) {
                                    echo "<tr>";
                                    echo "<td>" . $i++ . "</td>";
                                    echo "<td>" . $dt->client_name . "</td>";
                                     echo "<td>" . $dt->address ."</td>";
                                    echo "<td>" . $dt->contactname . "</td>";
                                    echo "<td>" . $dt->contact_phone . "</td>";
                                    echo "<td>" . $dt->contact_email . "</td>";
                                   
                                    
                                    echo "<td>
                                    <div class='btn-group'>
                                      <a class='btn btn-primary btn-xs editClientBtn' href='".base_url('Clients/supplierform/'.$dt->client_id)."' ><i class='fa fa-pencil-square-o'></i> Edit</a>"; ?>

                                      <a class='btn btn-info btn-xs' href='<?= base_url() ?>Clients/invoice/<?= $dt->client_id ?>'><i class='fa fa-list-alt'></i> Create Invoice</a>
                                    <?php echo " <div>
                                  </td>";
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
                <div class="modal fade model-reload" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Add New Clients</h4>
                            </div>
                            <?php
                            $attributes = array('autocomplete' => 'off', 'class' => 'form-horizontal form-label-left', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'clientform');
                            echo form_open('Clients/index', $attributes);
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
                                    <div class="row">
                                    <div class="col-md-6">
                                    	
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="">Client name <span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                            <input  type="text" id="client_name" name="client_name" maxlength="45" required class="form-control col-md-7 col-xs-12">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" >Address <span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                            <input  type="text" id="address" name="address" maxlength="45" required class="form-control col-md-7 col-xs-12">
                                        </div>
                                    </div>
            
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" >Fax 
                                        </label>
                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                            <input  type="text" id="fax" name="fax" maxlength="45"  class="form-control col-md-7 col-xs-12 numberonly">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="phone">Phone <span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                            <input  type="text" id="phone" name="phone" maxlength="45" required class="form-control col-md-7 col-xs-12 numberonly">
                                        </div>
                                    </div>

                                     <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="email">Email 
                                        </label>
                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                            <input  type="email" id="email" name="email" data-error="Email address is invalid"  class="form-control col-md-7 col-xs-12">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-md-6">
                             
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12">Contact Name  <span class="required">*</span></label>
                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                             <input  type="text" id="contact_name" name="contact_name"  required class="form-control col-md-7 col-xs-12">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12">Contact Phone <span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                            <input  type="text" id="contact_phone" name="contact_phone" maxlength="45" required class="form-control col-md-7 col-xs-12 numberonly">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="contact email">Contact Email 
                                        </label>
                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                            <input  type="email" id="contact_email" name="contact_email" class="form-control col-md-7 col-xs-12">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="url">Website Url 
                                        </label>
                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                            <input  type="text" id="web_url" name="web_url" class="form-control col-md-7 col-xs-12">
                                        </div>
                                    </div>
                                      <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="ledger_id">Supplier List<span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                            <select id="ledger_id" name="ledger_id"  required class="form-control  col-md-12 col-xs-12">
                                            <option value="">Please select Ledger</option>
                                               <?php
                                               
                                                 $supplier = $this->db->get('tbl_ledgers')->result_array();
                                            
                                               foreach ($supplier as $value) {
                                                
                                                  echo '<option value="'.$value['ledger_id'].'">'.$value['ledger_name'].'</option>';
                                               
                                               }
                                            
                                               ?>
                                                
                                            </select>
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
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="submit" name="submitsupplier" class="btn btn-primary" value="submitSupplier">Save changes</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>           

            </div>
        </div>
    </div>


 <!-- update supplier modal -->
                 <!-- Modal -->
               <div class="modal fade  model-reload" id="editClientModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Update Category</h4>
                            </div>
                             <?php
                            $attributes = array('autocomplete' => 'off', 'class' => 'form-horizontal form-label-left', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'updateClientForm');
                            echo form_open('clients/updateClient', $attributes);
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
