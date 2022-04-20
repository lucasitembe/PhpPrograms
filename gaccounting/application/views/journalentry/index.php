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
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i>Add Journal Entry Not Based on eHMS</h3>
            </div>
            <div class="panel-body">
                <div class="alert alert-success" role="alert" data-dismiss="alert" id="alertSuccess">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Success!</strong> <span id="succmsg"></span>
                </div>
                <div class="alert alert-danger" role="alert" data-dismiss="alert" id="alertError">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Error!</strong> <span id="errmsg"></span>
                </div>
                <?php
                $attributes = array('autocomplete' => 'off', 'class' => 'form-horizontal f', 'id' => 'defaultform');

                echo form_open(site_url("JournalEntry/index"), $attributes);
                ?> 
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="financeyear" class="control-label col-xs-4">Finance Year</label>
                            <div class="col-xs-8">
                                <span class="form-control"><?= (count($currentAccountYear) > 0)?$currentAccountYear->account_year:'Not Set' ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="acc_sections" class="control-label col-xs-4">Select Sections</label>
                            <div class="col-xs-8 validation-input">
                                <select onchange="getLedgersBySecId(this.value, 'acc_ledgers')"class="form-control col-md-7 col-xs-12 chosen-select" name="acc_sections" id='acc_sections'>
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
                            <label for="gstno" class="control-label col-xs-4">Select Ledger</label>
                            <div class="col-xs-8 validation-input">
                                <select class="form-control required col-md-7 col-xs-12 chosen-select" name="acc_ledgers" id="acc_ledgers">
                                    <option value='' selected='selected'></option>
                                    <?php
                                    foreach ($ledgers as $led) {
                                        echo '<option value="' . $led->ledger_id . '">' . $led->ledger_name . '       (' . $led->acc_name . ') </option>';
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
                            <label for="gstno" class="control-label col-xs-4">Debit / Credit</label>
                            <div class="col-xs-8 validation-input">
                                <select class="form-control col-md-7 required col-xs-12 chosen-select" name="trans_type" id="trans_type">
                                    <option selected value=""></option>
                                    <option value="0">Debit</option>
                                    <option value="1">Credit</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="gstno" class="control-label col-xs-4"></label>
                            <div class="col-xs-8 validation-input">
                                <input type="submit" class="btn btn-primary" value="Add Entry" /> </div>
                        </div>
                    </div>
                </div>
                </form>

                <div class="row">
                    <div class="col-lg-12">
                        <hr/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <label for="gstno" class="control-label">Comment(s) / Narration(s)</label>
                        <textarea class="form-control" name="comment" id="journal_comments" placeholder="Add Comment(s)"></textarea>
                    </div>
                    <div class="col-lg-3">
                        <label for="date_journal" class="control-label">Journal Entry Date</label>
                        <input type="text" value="<?= date('Y-m-d') ?>" required class="form-control readonlyinput" name="journal_date"  id="start_date">
                    </div>
                    <div class="col-lg-3">
                        <button type='button' style="margin-left: 70px;margin-top: 40px;" class='btn btn-primary' onclick='saveJournalEntrycache()'><i class='fa fa-save'></i> Save Entry</button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <br/>
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-12">
                        <table  class="table table-responsive table-bordered table-striped">
                            <thead>
                            <th>#</th>
                            <th>Ledger Name</th>
                            <th>Date Added</th>
                            <th class="text-right">Amount</th>
                            <th class="text-center">#</th>
                            </thead>
                            <tbody id="journalTableCache">

                                <?php
                                $i = 1;
                                $grandTotal = 0;
                                foreach ($currentUserLedgerJournal as $val) {
                                    $grandTotal += $val->amount;
                                    echo '<tr>';
                                    echo '<td>' . $i++ . '</td>';
                                    echo '<td>' . $val->ledger_name . '</td>';
                                    echo '<td>' . $val->date_time . '</td>';
                                    echo '<td class="journal_balance_total text-right">' . $val->amount . '</td>';
                                    echo "<td class='text-center'><button type='button' class='btn btn-xs btn-danger' onclick='deleteJournalEntrycache(" . $val->id . ")'><i class='fa fa-close'></i> Delete</button></td>";
                                    echo '<t/r>';
                                }

                                echo '<tr><td colspan="3" class="text-left"><label class="control-label">Balance</label></td><td class="text-right"><label class="control-label">' . $grandTotal . '</label></td></tr>';
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>