<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Api_model extends CI_Model {

    public function __construct() {
        parent::__construct();

        $this->load->model('Gassets_model');
    }

    public function addEhmsLedger($data) {

        $rs = $this->db->insert('tbl_ledgers', $data);

        if ($rs) {
            return 'success';
        } else {
            return $this->db->error()['code'];
        }
    }

    function saveEhmsJournalEntry($rawdata) {
        $retain_ledger_id = $this->Helper->getLedgerByName('Retained Earnings')->ledger_id;
        $has_error = false;

        $this->db->trans_begin();

        foreach ($rawdata as $value) {
            $source_name = $value->source_name;
            $source_id = $value->source_id;
            $Employee_Name = $value->Employee_Name;
            $user_transactor = $value->Employee_ID;
            $user_type = 1;
            $sub_total = $value->sub_total;
            $comment = $value->comment;

            $debit_entry_ledger_id = $this->Helper->getLedgerByName($value->debit_entry_ledger)->ledger_id;
            $credit_entry_ledger_id = $this->Helper->getLedgerByName($value->credit_entry_ledger)->ledger_id;

            $this->db->query("INSERT INTO tbl_journal_entry(comment,journal_date,user_transactor,user_type,Employee_name) VALUES('$comment',NOW(),'$user_transactor','$user_type','$Employee_Name')");

            $query = $this->db->query("SELECT trans_id FROM tbl_journal_entry  WHERE user_transactor='$user_transactor' ORDER BY trans_id DESC LIMIT 1");

            $trans_id = $query->result()[0]->trans_id;
            $credit = 0 - $sub_total;
            $debt = $sub_total;
            $journal = $this->journal_entry_details($trans_id, $credit_entry_ledger_id, $debt, $retain_ledger_id, $source_id);
            $journal_ = $this->journal_entry_details($trans_id, $debit_entry_ledger_id, $credit, $retain_ledger_id, $source_id);

            if ($journal == true || $journal_ == true) {
                $has_error = true;
            }
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return $this->db->error()['message'];
        } else {
            if ($has_error) {
                $this->db->trans_rollback();
                return 'An error has occured.Please try again later';
            } else {
                $this->db->trans_commit();
                return 'success';
            }
        }
    }

    public function journal_entry_details($trans_id, $ledger_id, $debtCredit, $retain_ledger_id, $Payment_Item_ID) {

        $has_error = false;
        $check_error = $this->db->query("INSERT INTO tbl_journal_entry_details(trans_id,ledger_id,amount,source_id,source_name) VALUES('$trans_id','$ledger_id','$debtCredit','$Payment_Item_ID','ehms_phamarcy_despense')");
        if (!$check_error) {
            $has_error = true;
        }
        $ledger_info = $this->Helper->getSectionNameByLedgerId($ledger_id);
        if (strtolower($ledger_info->sec_desc) == 'expenses' || strtolower($ledger_info->sec_desc) == 'revenues') {
            $check_error1 = $this->db->query("INSERT INTO tbl_journal_entry_details(trans_id,ledger_id,amount,entry_type) VALUES('$trans_id','$retain_ledger_id','$debtCredit','1')");
            if (!$check_error1) {
                $has_error = true;
            }
        }

        return $has_error;
    }

}
