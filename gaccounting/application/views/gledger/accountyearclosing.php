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
            <a class="btn btn-primary" href="<?= site_url('Gledger/CloseYearMonth') ?>">Months Closing</a>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i>Account year Month(s)- Activation / Deactivation </h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive"> 
                    <table class="table table-bordered table-striped table-hover"> 
                        <thead> 
                            <tr> 
                                <th>#</th> 
                                <th>Month Name</th> 
                                
                                <th>&nbsp;</th> 
                            </tr> </thead> 
                        <tbody> 
                            <?php
                            $i = 1;
                            $status = '';
                            
                           $accoutYear = $this->Helper->getCurrentAccountYear();
                           $deactivated_month_indexes = trim($accoutYear->deactivated_month);

                            foreach ($this->Helper->getMonths() as $key=>$month) {
                               
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $month ?></td>
                                    
                                    <td width="10%">
                                        <div class='btn-group'>
                                            <?php
                                            if (preg_match('/'.$key.'/',$deactivated_month_indexes)) {
                                                ?>
                                                <button onclick="modifyJournalMonths('<?= $key ?>', 'activate')" class='btn btn-success' ><i class='fa fa-check'></i> Activate</button>
                                                <?php
                                            } else if (!preg_match('/'.$key.'/',$deactivated_month_indexes)) {
                                                ?>
                                                <button onclick="modifyJournalMonths('<?= $key ?>', 'deactivate')" class='btn btn-danger' ><i class='fa fa-close'></i> Deactivate</button>
                                                <?php
                                            }
                                            ?>
                                                 
                                        </div>
                                    </td>
                                </tr>
                                <?php
                                $i++;
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
                                <h4 class="modal-title" id="myModalLabel">Add an account year</h4>
                            </div>
                            <?php
                            $attributes = array('autocomplete' => 'off', 'class' => 'form-horizontal form-label-left', 'id' => 'defaultform');
                            echo form_open('gledger/accountYear', $attributes);
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
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="acc_year">Account Year <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="text" value="<?= date('Y-m-d') ?>" required class="form-control col-md-7 col-xs-12 readonlyinput" name="acc_year"  id="start_date" >
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="acc_end_year">End Account Year
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <label class="form-control col-md-7 col-xs-12" id="acc_end_year"></label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="descriptions">Description 
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <textarea class="form-control" name="descriptions" id="descriptions" placeholder="Add description(s)"></textarea>
                                        </div>
                                    </div>
                                    <div class="ln_solid"></div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="submit" name="submitCurrency" class="btn btn-primary" value="submitCurrency">Save Year</button>
                            </div>

                            </form>
                        </div>
                    </div>
                </div>           

            </div>
        </div>
    </div>
</div>