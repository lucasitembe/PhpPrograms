<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Helper
 *
 * @author gpitg
 */
class Helpers extends CI_Controller {

    public function __construct() {
        parent::__construct();

        if (!isset($_SESSION['userinfo'])) {
            redirect('account/login');
        }
    }

    public function getJougnalRow() {
        $i = $this->input->get('current_index');
        $ledgers = $this->Helper->getList('tbl_ledgers');

        $result = '<tr id="' . $i . '" class="journal_row">
                                    <td>' . $i . '</td>
                                    <td>
                                        <select class="form-control" name="ledger[]" id="ledger' . $i . '" onchange="getAccountSectionByLedgerID(this.value,\'' . $i . '\')">
                                            <option></option>';
        foreach ($ledgers as $ledger) {
            $result .= '<option value="' . $ledger->ledger_id . '">' . $ledger->ledger_name . '</option>';
        }

        $result .= ' <img src="' . base_url() . 'assets/images/loader.gif" id="loader_' . $i . '" class="loadergif"></select>
                                    </td>
                                     <td> 
                                        <span id="acc_sect' . $i . '"  class="form-control  span_journal_text"></span> 
                                     </td>
                                    <td><input type="text" class="form-control" name="amount[]" id="amount' . $i . '" placeholder="Amount"></td>
                                    <td>
                                       	<select class="db_cr form-control" cur="' . $i . '" name="debit_credit[]" id="debit_credit' . $i . '">
	                                    	<option value=""></option>
	                                    	<option value="0">Debit</option>
	                                    	<option value="1">Credit</option>
	                                </select>
                                    </td>
                                    <td class="text-right ">
                                        <span id="total_debit' . $i . '" class="form-control  span_journal_text span_journal_text_debit">
                                           
                                        </span>    
                                   </td>
                                    <td class="text-right">
                                        <span id="total_credit' . $i . '"  class="form-control  span_journal_text span_journal_text_credit">
                                            
                                        </span> 
                                   </td>
                                </tr>';

        echo $result;
    }

    public function getAccountSectionByLedgerID() {
        $ledger_id = $this->input->get('ledger_id');
        $result = $this->Helper->getAccountSectionByLedgerID($ledger_id);

        echo $result->sec_desc;
    }

    public function getLedgersBySecId() {
        $sec_id = $this->input->get('sec_id');
        $result = $this->Helper->getLedgersBySecId($sec_id);

        $out = "<option></option>";
        foreach ($result as $led) {
            $out .= '<option value="' . $led->ledger_id . '">' . $led->ledger_name . '       (' . $led->acc_name . ') </option>';
        }

        echo $out;
    }

    function getCurrentUserLedgerJournalEntry() {
        $result = $this->Helper->getCurrentUserLedgerJournal();

        $data = '';
        $grandTotal = 0;
        $i = 1;
        foreach ($result as $val) {
            $grandTotal += $val->amount;
            $data .= '<tr>';
            $data .= '<td>' . $i++ . '</td>';
            $data .= '<td>' . $val->ledger_name . '</td>';
            $data .= '<td>' . $val->date_time . '</td>';
            $data .= '<td class="journal_balance_total  text-right">' . $val->amount . '</td>';
            $data .= "<td class='text-center'><button type='button' class='btn btn-xs btn-danger' onclick='deleteJournalEntrycache(" . $val->id . ")'><i class='fa fa-close'></i> Delete</button></td>";
            $data .= '<t/r>';
        }

        $data .= '<tr><td colspan="3" class="text-left"><label class="control-label">Balance</label></td><td class="text-right"><label class="control-label">' . $grandTotal . '</label></td></tr>';


        echo $data;
    }

}
