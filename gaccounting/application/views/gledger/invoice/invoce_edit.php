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
                                            <input  type="text" id="invoice_number" name="invoice_number" maxlength="45" required class="form-control col-md-7 col-xs-12" value="<?= $invoice->invoice_no ?>">
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
                                            <input  type="text" id="amount" name="amount" maxlength="45" required class="form-control col-md-7 col-xs-12" value="<?= $invoice->Amount ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Invoice File
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  type="file" id="userfile" name="userfile" class="col-md-7 col-xs-12" >
                                        </div>
                                    </div>