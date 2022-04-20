<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="<?= site_url('Home') ?>"> Dashboard</a></li>
            <li><i class="fa fa-laptop"></i><a href="<?= site_url('gledger') ?>"> General Ledger</a></li>
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
            <button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add Credit Note</button>
            <?php if (isset($_GET['ref'])) { ?>  
                <a href="<?= base_url('gledger/cashbookentry?report&recieving&' . $_SERVER['QUERY_STRING']) ?>" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i> Print</a> 
            <?php } else { ?>
                <a href="" id="ajaxUpdateContainerPdf" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i> Print</a> 
            <?php } ?>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i>Credit Notes</h3>
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
                                <th>Credit Note Date</th> 
                                <th>Credit Note #</th> 
                                <th>Amount Reduced</th>
                                <th>Tax ( % )</th> 
                                <th>Trans Date</th> 
                                <th>Prepared by</th> 
                                <th>&nbsp;</th> 
                            </tr> </thead> 
                        <tbody> 
                            <?php
                            $i = 1;
                            foreach ($creditnotes as $cr) {
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $cr->invoice_id; ?></td>
                                    <td><?php echo $cr->invoice_date; ?></td>
                                    <td><?php echo $cr->Amount; ?></td>
                                    <td><?php echo $cr->credit_note_date ?></td>
                                    <td><?php echo $cr->credit_note_number ?></td>
                                    <td><?php echo $cr->amount_to_reduce ?></td>
                                    <td><?php echo $cr->tax; ?></td>
                                    <td><?php echo $cr->trans_date_time; ?></td>
                                    <td><?php echo $cr->fname . ' ' . $cr->lname; ?></td>
                                    <td>
                                        <a class='btn btn-primary' href='<?= base_url('gledger/creditnote?report&ref=' . $cr->id) ?>' target="_blank"><i class='fa fa-print'></i> Preview</a>
                                    </td>
                                </tr>
                                <?php
                                $i++;
                            }
                            ?>
                        </tbody> 
                    </table> 

                    <center> <?php //echo $links;       ?></center>

                </div>

                <!--currency form model-->

                <!-- Modal -->
                <div class="modal fade  model-reload" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document" style="width: 80%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Add Credit Note</h4>
                            </div>
                            <div class="modal-body">

                                <?php
                                $attributes = array('autocomplete' => 'off', 'class' => 'form-horizontal', 'id' => 'defaultforms');
                                echo form_open(site_url("gledger/creditnote"), $attributes);
                                ?> 
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="financeyear" class="control-label col-xs-4">Supplier</label>
                                            <div class="col-xs-8">
                                                <select  class="form-control col-md-7 col-xs-12 chosen-select" name="supplier" id='supplier'>
                                                    <option value='' selected>Select supplier</option>
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
                                                <select required onchange="getInvoiceDetails(this.value)" class="form-control col-md-7 col-xs-12 chosen-select" name="invoice_id" id='invoice_id'>
                                                    <option value='' disabled="" selected>--Select Invoice no--</option>
                                                    <?php
                                                    foreach ($invoices as $dt) {
                                                        $supplier_name = $this->Helper->getSupplierById($dt->supplier_id)->suppliername;
                                                        echo '<option value="' . $dt->invoice_id . '" amouttopaid="' . trim($dt->Amount) . '">' . $dt->invoice_no . '   (' . $supplier_name . ') -> ' . $dt->Amount . ' </option>';
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
                                        <div class="form-group">
                                            <label for="credit_note_date" class="control-label col-xs-4">Credit Note Date</label>
                                            <div class="col-xs-8">
                                                <input type="text" value="<?= date('Y-m-d') ?>" required class="form-control readonlyinput" name="credit_note_date"  id="credit_note_date">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="credit_note_number" class="control-label col-xs-4">Credit Note #</label>
                                            <div class="col-xs-8 validation-input">
                                                <input type="text" value="" required class="form-control" name="credit_note_number" id="credit_note_number">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="amount_reduce" class="control-label col-xs-4">Amount To Reduced</label>
                                            <div class="col-xs-8 validation-input">
                                                <input type="text"  class="form-control col-md-7 col-xs-12" name="amount_reduce" id='amount_reduce' oninput="showchangedvalue(this.value)"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="new_invo" class="control-label col-xs-4">New Invoice Figure</label>
                                            <div class="col-xs-8 validation-input">
                                                <input type="text" value="" required class="form-control" name="new_invo" id="new_invo">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="tax" class="control-label col-xs-4">Tax</label>
                                            <div class="col-xs-8 validation-input">
                                                <input type="text" value="" required class="form-control" name="tax" id="tax">
                                            </div>
                                        </div>
                                        <!--                                        <div class="form-group">
                                                                                    <label for="new_grand_total" class="control-label col-xs-4">New Grand-Total</label>
                                                                                    <div class="col-xs-8 validation-input">
                                                                                        <input type="text" value="" required class="form-control" name="new_grand_total" id="new_grand_total">
                                                                                    </div>
                                                                                </div>-->
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
                                                <input type="submit" class="btn btn-primary" value="&nbsp;&nbsp; Save &nbsp;&nbsp;"  onclick="return add_credit_note()"/> </div>
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
</div>
<script type="text/javascript">
    function add_credit_note() {
        if (confirm("Are you sure you want to save this credit note?")) {
            return true;
        } else {
            return false;
        }
    }

    function showchangedvalue(amount) {
        if (amount != '' && amount != null) {
            $('#new_invo').val(parseInt($('#invo_amount').val()) - parseInt(amount))
        }
    }

</script>