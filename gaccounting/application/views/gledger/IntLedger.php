<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="<?= site_url('Home') ?>"> Dashboard</a></li>
            <li><i class="fa fa-laptop"></i><a href="<?= site_url('gledger/ledgers') ?>"> Manage ledgers</a></li>						  	
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
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i>Registered Ledgers </h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive"> 
                    <table class="table table-bordered  table-striped table-hover"> 
                        <thead> 
                            <tr> 
                                <th>#</th> 
                                <th>Ledger name</th> 
                                <th>Ledger origin</th> 
                                <th>Ledger based on</th> 
                                <th>Description</th> 
                                <th>&nbsp;</th> 
                            </tr> </thead> 
                        <tbody> 
                            <?php
                            $i = 1;
                            foreach ($ledgers as $ledger) {
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $ledger['ledger_name']; ?></td>
                                    <td><?php echo ($ledger['is_ehms'] == '1') ? 'eHMS' : 'gAccounting'; ?></td>
                                    <td><?php echo $ledger['ehms_based_value']; ?></td>
                                    <td><?php echo $ledger['discription'];?></td>
                                    <td>
                                        <?php
                                          if($ledger['ledger_type'] == '0'){
                                        ?>
                                        <div class='btn-group-sm'>
                                            <a class='btn btn-primary' href='#'><i class='fa fa-pencil-square-o'></i> Edit</a>
                                            <a class='btn btn-danger' href='#'><i class='fa fa-close'></i> Delete</a>
                                        <?php
                                          if($ledger['is_active'] == '1'){
                                              echo ' <button type="button" class="btn btn-warning" href="#"><i class="fa fa-close"></i> Disable</button>';
                                          }else if($ledger['is_active'] == '0'){
                                                   echo ' <button type="button" class="btn btn-warning" href="#"><i class="fa fa-close"></i> Activate</button>';
                                          }
                                        ?>
                                        </div>
                                          <?php
                                          }
                                          ?>
                                    </td>
                                </tr>
                                <?php
                                $i++;
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
                                <h4 class="modal-title" id="myModalLabel">Add an Ledgers</h4>
                            </div>
                            <?php
                            $attributes = array('autocomplete' => 'off', 'class' => 'form-horizontal form-label-left', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'defaultform');
                            echo form_open('gledger/intLedger', $attributes);
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
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="group-name">Ledger Name <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  type="text" id="ledger_name" name="ledger_name" maxlength="60" required class="form-control col-md-7 col-xs-12">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="section_name">Account Name<span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select id="currency_code" name="acc_code"   required class="form-control col-md-7 col-xs-12">
                                                <option></option>
                                                <?php foreach ($values as $value) { ?>
                                                    <option value="<?php echo $value->acc_code; ?>"><?php echo $value->acc_name; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="discription">Description 
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                             <textarea class="form-control col-md-7 col-xs-12" required id="discription" name="discription"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="cashbook">Appear in Cashbook?
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="checkbox" name="is_cashbook" id="is_cashbook" value="1"/>
                                         </div>
                                    </div>
                                    <div class="ln_solid"></div>
                                    <div class="form-group">
                                        <!--                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                                                  <button type="submit" class="btn btn-primary">Cancel</button>
                                                                  <button type="submit" class="btn btn-success">Submit</button>
                                                                </div>-->
                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="submit" name="submitCurrency" class="btn btn-primary" value="submitCurrency">Save changes</button>
                            </div>

                            </form>
                        </div>
                    </div>
                </div>           

            </div>
        </div>
    </div>
</div>