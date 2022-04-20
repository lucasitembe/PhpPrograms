<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="<?= site_url('Home') ?>"> Dashboard</a></li>
            <li><i class="fa fa-laptop"></i><a href="<?= site_url('gledger') ?>"> General Ledger</a></li>						  	
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
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Main accounts </h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive"> 
                    <table class="table table-bordered  table-striped table-hover"> 
                        <thead> 
                            <tr> 
                                <th>#</th> 
                                <th>Main account</th> 
                                <th>Group</th> 
                                <th>&nbsp;</th> 
                            </tr> </thead> 
                        <tbody> 
                            <?php
                            $i = 1;
                           foreach ($mainacc as $acc) { 
                            ?>
                            <tr>
                                <td><?php echo $i;?></td>
                                <td><?php echo $acc['main_acc_name'];  ?></td>
                                <td><?php echo $acc['group_name'];  ?></td>
                                <td>
                                    <div class='btn-group'>
                                        <a class='btn btn-primary' href='#'><i class='fa fa-pencil-square-o'></i> Edit</a>
                                        <a class='btn btn-danger' href='#'><i class='fa fa-close'></i> Delete</a>
                                    </div>
                                </td>
                            </tr>
<?php
$i++ ;

                           }
?>
                        </tbody> 
                    </table> 

                    <center> <?php echo $links; ?></center>

                </div>

                <!--currency form model-->

                <!-- Modal -->
                <div class="modal fade model-reload" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Add main account</h4>
                            </div>
<?php
$attributes = array('autocomplete' => 'off', 'class' => 'form-horizontal form-label-left', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'defaultform');
echo form_open('gledger/main_account', $attributes);
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
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="group-name">Main account name <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  type="text" id="group-name" name="mainAcc_name" maxlength="60" required class="form-control col-md-7 col-xs-12">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="section_name">Group name<span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            <select id="currency_code" name="group_id"   required class="form-control col-md-7 col-xs-12">
                                                <option></option>
                                                <?php foreach ($values as $value) {?>
                                                <option value="<?php echo $value->group_id; ?>"><?php echo $value->group_name; ?></option>
                                                <?php } ?>
                                            </select>
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
