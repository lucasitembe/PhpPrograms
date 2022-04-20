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
        <?php if ($type == 'details') { ?>
            <div id='table-options' class="pull-right">
                <a class="btn btn-primary" href="<?= site_url('JournalEntry/ApproveJournal?approve&id=' . $_GET['id']) ?>" onclick="return confirm('Are you sure you want to approve this entry?')">APPROVE & POST</a>
            </div>
        <?php } ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i>Approving Journal Date </h3>
            </div>
            <div class="panel-body">
                <?php if ($type == 'summery') { ?>
                    <div class="table-responsive"> 
                        <table class="table table-bordered table-striped table-hover"> 
                            <thead> 
                                <tr> 
                                    <th>#</th> 
                                    <th>Trans Date</th> 
                                    <th>Created by</th> 
                                    <th>Description</th> 
                                    <th>&nbsp;</th> 
                                </tr> </thead> 
                            <tbody> 
                                <?php
                                $i = 1;

                                foreach ($pendingJournalTransaction as $trans) {
                                    ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $trans->trans_date_time; ?></td>
                                        <td><?php echo $trans->fname . ' ' . $trans->lname; ?></td>
                                        <td><?php echo $trans->comment ?></td>
                                        <td>
                                            <div class='btn-group'>
                                                <a class='btn btn-primary' href='<?= site_url('JournalEntry/ApproveJournal?id=' . $trans->trans_id) ?>'><i class='fa fa-pencil'></i> Process</a>
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
                    <?php } elseif($type == 'details'){ ?>
                    <div class="table-responsive"> 
                        <table class="table table-bordered table-striped table-hover"> 
                            <thead> 
                                <tr> 
                                    <th>#</th> 
                                    <th>Ledger Name</th> 
                                    <th>Trans Date</th> 
                                    <th>Created by</th> 
                                    <th>Description</th> 
                                </tr> </thead> 
                            <tbody> 
                                <?php
                                $i = 1;

                                foreach ($pendingJournalTransactionDetails as $trans) {
                                    ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $trans->ledger_name; ?></td>
                                        <td><?php echo $trans->trans_date_time; ?></td>
                                        <td><?php echo $trans->fname . ' ' . $trans->lname; ?></td>
                                        <td><?php echo $trans->comment ?></td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                                ?>
                            </tbody> 
                        </table> 

                    </div>
                <?php } ?>


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