<script type="text/javascript">
    function  delete_invoice(){
        con = confirm("Are you sure you want to delete this invoice ??");
        if(con){
            return true;
        }else{
            return false;
        }
    }
    
</script>

<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="index.html"> Home</a></li>
            <li><i class="fa fa-laptop"></i><a href="<?php echo base_url();?>gledger">General Ledger</a></li>						  	
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
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Suplier Invoice </h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive"> 
                    <table class="table table-bordered  table-striped table-hover"> 
                        <thead> 
                            <tr> 
                                <th>SN</th> 
                                <th>Supplier</th> 
                                <th>Invoice No.</th> 
                                <th>Amount</th> 
                                <th>Invoice</th> 
                                <th>Action</th> 
                              
                            </tr> </thead> 
                        <tbody> 

                             <?php
                            
                            
                            
                            $i =1;
                            if($invoice_List){
                            foreach ($invoice_List as $dt) {
                                 $supplier_name =$this->Helper->getSupplierById($dt->supplier_id)->suppliername;
                                echo "<tr>";
                                echo "<td>" . $i++ . "</td>";
                                echo "<td>" . $supplier_name. "</td>";
                                echo "<td>" . $dt->invoice_no. "</td>";
                                echo "<td>" . $dt->Amount."</td>";
                                echo "<td><a href='../assets/invoice_image/".$dt->invoice_image. "' target='blank'>".$dt->invoice_image. "</a></td>";

                                  echo"<td>
                                  <div class='btn-group-sm'>
                                            <a class='btn btn-primary editInvoiceBtn' href='".base_url('gledger/edit_invoice/'.$dt->invoice_id)."'><i class='fa fa-pencil-square-o'></i> Edit</a>
                                            <a class='btn btn-danger' onclick='return delete_invoice()' href='./delete_invoice/".$dt->invoice_id."'><i class='fa fa-close'></i> Delete</a>
                                        </div>


                                  </td>";
                                echo "</tr>";
                            }
                        }
                            ?>

                
                        </tbody> 
                    </table> 
                    
                   
                </div>

                <!--currency form model-->

                <!-- Modal -->
                <div class="modal fade  model-reload" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Add new Invoice</h4>
                            </div>
                            <?php
                            $attributes = array('autocomplete' => 'off', 'class' => 'form-horizontal form-label-left', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'assetcategoryform');
                            echo form_open_multipart('gledger/add_supplier_invoice', $attributes);
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
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="ledger_id">Supplier List<span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select id="supplier_name" name="supplier_name"  required class="form-control  col-md-12 col-xs-12">
                                            <option value="">Please select Supplier</option>
                                               <?php
                                               
                                                 $supplier = $this->db->get('tbl_supplier')->result_array();
                                            
                                               foreach ($supplier as $value) {
                                               	
                                                  echo '<option value="'.$value['supplier_id'].'">'.$value['suppliername'].'</option>';
                                               
                                               }
                                           	
                                               ?>
                                                
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Invoice Number <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  type="text" id="invoice_number" name="invoice_number" maxlength="45" required class="form-control col-md-7 col-xs-12">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Invoice Date <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
  
                                     <input type="text"  id="date" name="date" class="form-control form-inline" placeholder="End Date"  value="<?= date('Y-m-t') ?>">
                                               </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Amount <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  type="text" id="amount" name="amount" maxlength="45" required class="form-control col-md-7 col-xs-12">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Invoice File
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  type="file" id="userfile" name="userfile" class="col-md-7 col-xs-12">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="submit" name="submitAssetCategory" class="btn btn-primary" value="submitAssetCategory">Save changes</button>
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
               <div class="modal fade  model-reload" id="editInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Update Invoice</h4>
                            </div>
                             <?php
                            $attributes = array('autocomplete' => 'off', 'class' => 'form-horizontal form-label-left', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'updateInvoiceform');
                            echo form_open('gledger/updateInvoice', $attributes);
                            ?>
                             <div class="modal-body">
                                <div class="x_content">
                                    <br/>

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
