<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="<?= site_url('Home') ?>"> Dashboard</a></li>
            <li><i class="fa fa-laptop"></i><a href="<?= site_url('gledger') ?>"> General Ledger</a></li>
            <li><i class="fa fa-laptop"></i><a href="<?= site_url('gledger/cashbookentry') ?>">Cash Book</a></li>
        </ol>
    </div>
</div>
<?php
if ($this->session->flashdata('success') != null) {
    ?>
    <div class="alert alert-success  alert-dismissable" style="display: block" role="alert"> <strong>Success!</strong> <?= $this->session->flashdata('success') ?> </div>

    <?php
} else if ($this->session->flashdata('error') != null) {
    ?>
    <div class="alert alert-danger alert-dismissable" style="display: block" role="alert"> <strong>Error!</strong> <?= $this->session->flashdata('error') ?> </div>
    <?php
}
?>
<div class="alert alert-success" role="alert" data-dismiss="alert" id="alertSuccess">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <strong>Success!</strong> <span id="succmsg"></span>
</div>
<div class="alert alert-danger" role="alert" data-dismiss="alert" id="alertError">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <strong>Error!</strong> <span id="errmsg"></span>
</div>
<div class="row">
    <div class="col-lg-12">
        <div id='table-options' class="pull-right">
            <button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add Payment</button>
             <?php if (isset($_GET['ref'])) { ?>  
                <a href="<?= base_url('gledger/cashbookentry?report&recieving&' . $_SERVER['QUERY_STRING']) ?>" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i> Print</a> 
            <?php } else { ?>
                <a href="" id="ajaxUpdateContainerPdf" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i> Print</a> 
            <?php } ?>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i>Payment Receipt</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive"> 
                    <table class="table table-bordered  table-striped table-hover"> 
                        <thead> 
                            <tr> 
                                <th>#</th> 
                                <th>Invoice #</th> 
                                <th>Invoice Date</th> 
                                <th>Invoice Amount</th> 
                                <th>Amount Paid</th> 
                                <th>Payment Date</th>
                                <th>Prepared by</th> 
                                <th>&nbsp;</th> 
                            </tr> </thead> 
                        <tbody> 
                            <?php
                            $i = 1;
                            foreach ($voucher as $v) {
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $v->Invoice_Number; ?></td>
                                    <td><?php echo $v->transaction_date; ?></td>
                                    <td><?php echo $v->amount; ?></td>
                                    <td><?php echo number_format($v->amount_paid, 2) ?></td>
                                    <td><?php echo $v->cashbook_date; ?></td>
                                    <td><?php echo $v->fname . ' ' . $v->lname; ?></td>
                                    <td>
                                        <a class='btn btn-primary' href='<?= base_url('gledger/cashbookentry?report&recieving&ref=' . $v->id) ?>' target="_blank"><i class='fa fa-print'></i> Preview</a>
                                    </td>
                                </tr>
                                <?php
                                $i++;
                            }
                            ?>
                        </tbody> 
                    </table> 

                    <center> <?php //echo $links;    ?></center>

                </div>

                <!--currency form model-->

                <!-- Modal -->
                <div class="modal fade  model-reload" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document" style="width: 80%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Add Payment</h4>
                            </div>
                            <div class="modal-body">
                            <?php
                            $attributes = array('autocomplete' => 'off', 'class' => 'form-horizontal', 'id' => 'defaultforms');

                            echo form_open(site_url("gledger/cashbookentry"), $attributes);
                            ?> 
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="financeyear" class="control-label col-xs-4">Finance Year</label>
                                        <div class="col-xs-8">
                                            <span class="form-control"><?= (count($currentAccountYear) > 0) ? $currentAccountYear->account_year : 'Not Set' ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="date_journal" class="control-label col-xs-4">Date</label>
                                        <div class="col-xs-8">
                                            <input type="text" value="<?= date('Y-m-d') ?>" required class="form-control readonlyinput" name="journal_date"  id="start_date">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="acc_sections" class="control-label col-xs-4">Select Sections</label>
                                        <div class="col-xs-8 validation-input">
                                            <select onchange="getLedgersBySecId(this.value, 'acc_ledgers_rev')"class="form-control col-md-7 col-xs-12 chosen-select" name="acc_sections" id='acc_sections'>
                                                <option value='all' selected>All</option>
                                                <?php
                                                foreach ($acc_sections as $acc_sec) {
                                                    echo '<option value="' . $acc_sec->sec_id . '">' . $acc_sec->sec_desc . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="gstno" class="control-label col-xs-4">Received By</label>
                                        <div class="col-xs-8 validation-input">
                                            <select required class="form-control required col-md-7 col-xs-12 chosen-select" name="acc_ledgers_rev" id="acc_ledgers_rev">
                                                <option value='' selected='selected'></option>
                                                <?php
                                                foreach ($ledgers as $led) {
                                                    echo '<option value="' . $led->ledger_id . '">' . $led->ledger_name . '       (' . $led->acc_name . ') </option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="invoice_number_recv" class="control-label col-xs-4">Invoices</label>
                                        <div class="col-xs-8 validation-input">
                                            <select required class="form-control required col-md-7 col-xs-12 chosen-select" name="invoice_number_recv" id="invoice_number_recv">
                                                <option value='' selected='selected'></option>
                                                <?php
                                                foreach ($invoice_List as $dt) {
                                                    echo '<option value="' . $dt->Invoice_ID . '" amouttopaid="' . trim($dt->amount) . '">' . $dt->Invoice_Number . '   (' . $dt->sponsor . ') -> ' . $dt->amount . ' </option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="amount" class="control-label col-xs-4">Amount</label>
                                        <div class="col-xs-8 validation-input">
                                            <input type="text" value="" required class="form-control numberonly" name="amount" name="amount" id="amount">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="acc_sections" class="control-label col-xs-4">Select Sections</label>
                                        <div class="col-xs-8 validation-input">
                                            <select required onchange="getLedgersBySecId(this.value, 'acc_ledgers_bank')"class="form-control col-md-7 col-xs-12 chosen-select" name="acc_sections_bank" id='acc_sections_bank'>
                                                <option value='all' selected>All</option>
                                                <?php
                                                foreach ($acc_sections as $acc_sec) {
                                                    echo '<option value="' . $acc_sec->sec_id . '">' . $acc_sec->sec_desc . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="gstno" class="control-label col-xs-4">Bank From</label>
                                        <div class="col-xs-8 validation-input">
                                            <select required class="form-control required col-md-7 col-xs-12 chosen-select" name="acc_ledgers_bank" id="acc_ledgers_bank">
                                                <option value='' selected='selected'></option>
                                                <?php
                                                foreach ($ledgers as $led) {
                                                    echo '<option value="' . $led->ledger_id . '">' . $led->ledger_name . '       (' . $led->acc_name . ') </option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="gstno" class="control-label col-xs-4">Comment(s) / Narration(s</label>
                                        <div class="col-xs-8 validation-input">
                                            <textarea required class="form-control" name="comment" id="journal_comments" placeholder="Add Comment(s)"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="gstno" class="control-label col-xs-4"></label>
                                        <div class="col-xs-8 validation-input">
                                            <input type="submit" class="btn btn-primary" value="Add Payment"  onclick=" return add_payment()"/> </div>
                                    </div>
                                </div>
                                  </form>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>           

            </div>
        </div>
    </div>
<script type="text/javascript">
    function add_payment() {
        if (confirm("Are you sure you want to add this payment?")) {
            return true;
        } else {
            return false;
        }
    }

</script>