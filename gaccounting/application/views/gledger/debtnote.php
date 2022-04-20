<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="<?= site_url('Home') ?>"> Dashboard</a></li>
            <li><i class="fa fa-laptop"></i><a href="<?= site_url('gledger') ?>"> General Ledger</a></li>
            <li><i class="fa fa-laptop"></i>Debit Note</li>
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
    <div class="alert alert-error alert-dismissable" style="display: block" role="alert"> <strong>Error!</strong> <?= $this->session->flashdata('error') ?> </div>
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
            <button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Debt note</button>
            <?php if (isset($_GET['ref'])) { ?>  
                <a href="<?= base_url('gledger/cashbookentry?report&paying&' . $_SERVER['QUERY_STRING']) ?>" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i> Print</a> 
            <?php } else { ?>
                <a href="" id="ajaxUpdateContainerPdf" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i> Print</a> 
            <?php } ?>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i>Debit Note</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive"> 
                    <table class="table table-bordered  table-striped table-hover"> 
                        <thead> 
                            <tr> 
                                <th>#</th> 
                                <th>Invoice #</th> 
                                <th>Supplier Name</th> 
                                <th>Invoice Amount</th> 
                                <th>Amount To Reduce</th> 
                                <th>Date</th>
                                <th>Prepared by</th> 
                                <th>&nbsp;</th> 
                            </tr> </thead> 
                        <tbody> 
                            <?php
                            $i = 1;
                            foreach ($debtnotes as $v) {
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $v->invoice_no; ?></td>
                                    <td><?php echo $v->suppliername; ?></td>
                                    <td><?php echo number_format($v->Amount, 2) ?></td>
                                    <td><?php echo number_format($v->amount_to_reduce, 2) ?></td>
                                    <td><?php echo $v->debt_note_date; ?></td>
                                    <td><?php echo $v->user; ?></td>
                                    <td>
                                        <a class='btn btn-primary' href='<?= base_url('gledger/debtnote_partial/'. $v->debt_note_id) ?>' target="_blank"><i class='fa fa-print'></i> Preview</a>
                                    </td>
                                </tr>
                                <?php
                                $i++;
                            }
                            ?>
                        </tbody> 
                    </table> 

                    <center> <?php //echo $links;      ?></center>

                </div>

                <!--currency form model-->

                <!-- Modal -->
                <div class="modal fade  model-reload" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document" style="width: 80%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Debt Note</h4>
                            </div>
                            <div class="modal-body">
               <?php  $attributes = array('autocomplete' => 'off', 'class' => 'form-horizontal', 'id' => 'defaultform');
                echo form_open(site_url("gledger/debtnote"), $attributes);
                ?> 
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="financeyear" class="control-label col-xs-4">Supplier</label>
                            <div class="col-xs-8">
                                <select class="form-control col-md-7 col-xs-12 chosen-select" name="supplier" id='supplier'>
                                    <option disabled="disabled"  value='' selected>All</option>
                                    <?php
                                    foreach ($suppliers as $supplier) {
                                        echo '<option value="' . $supplier->supplier_id . '">' . $supplier->suppliername . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="invoice_id" class="control-label col-xs-4">Invoice no</label>
                            <div class="col-xs-8">
                                <select required="required" onchange="getInvoiceDetails(this.value)" class="form-control col-md-7 col-xs-12 chosen-select" name="invoice_id" id='invoice_id'>
                                    <option value='' disabled="" selected>--Select Invoice no--</option>
                                    <?php
                                    foreach ($invoices as $invoice) {
                                        echo '<option value="' . $invoice->invoice_id . '">' . $invoice->invoice_no . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="invo_date" class="control-label col-xs-4">Invoice Date</label>
                            <div class="col-xs-8 validation-input">
                                <input type="text"  class="form-control col-md-7 col-xs-12" name="invo_date" id='invo_date'/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="invo_amount" class="control-label col-xs-4">Invoice Amount</label>
                            <div class="col-xs-8 validation-input">
                                <input type="text"  class="form-control col-md-7 col-xs-12" name="invo_amount" id='invo_amount'/>
                            </div>
                        </div>
                       
                    </div>
                    <div class="col-lg-6">
                         <div class="form-group">
                            <label for="invo_amount" class="control-label col-xs-4">Amount To Reduce</label>
                            <div class="col-xs-8 validation-input">
                                <input type="text"  class="form-control col-md-7 col-xs-12" name="amount_reduce" id='amount_reduce'/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tax" class="control-label col-xs-4">Tax (%)</label>
                            <div class="col-xs-8 validation-input">
                                <input type="text" value="" required class="form-control" name="tax" id="tax">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="remark" class="control-label col-xs-4">Remark(s)</label>
                            <div class="col-xs-8 validation-input">
                                <textarea required class="form-control" name="remark" id="journal_comments" placeholder="Add Remark(s)"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4 col-xs-offset-4">
                        <div class="form-group">
                            <label for="gstno" class="control-label col-xs-4"></label>
                            <div class="col-xs-8 validation-input">
                               <input type="submit" class="btn btn-primary" value="&nbsp;&nbsp; Save &nbsp;&nbsp;"  onclick="confirm('Are you sure yo want to save this?')"/> </div>
                        </div>
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

<!--
<script type="text/javascript">
    function make_payment() {
        var amouttopaid = parseInt($('#invoice_number_pay').find('option:selected').attr('amouttopaid'));
        var amount = parseInt($('#amount_pay').val());
        if (amount > amouttopaid) {
            alert("The amount entered is greater than the required amount");
            return false;
        } else {
            if (confirm("Are you sure you want to make this payment?")) {
                return true;
            } else {
                return false;
            }
        }
    }

</script>-->
