<?php

class JournalEntryModel extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function addJournal() {
        $amount = trim($this->input->post('amount'));
        $ledger_id = trim($this->input->post('acc_ledgers'));
        $trans_type = trim($this->input->post('trans_type'));
// $journal_date = trim($this->input->post('journal_date'));
        $user_id = '1';

        if ($trans_type == '1') {
            $amount = 0 - $amount;
        }

        $data = array(
            'ledger_id' => $ledger_id,
            'amount' => $amount,
            // 'journal_date' => journal_date,
            'user_id' => $this->session->userinfo->user_id //TODO USER ID FROM EITHER EHMS/GACCOUNTING
        );

        $rs = $this->db->insert('tbl_journal_entry_cache', $data);

        if ($rs) {
            return 'success';
        } else {
            return $this->db->error()['code'];
        }
    }

    function deleteJournalEntryCache($id) {
        $where = array(
            'id' => $id
        );

        $rs = $this->db->delete('tbl_journal_entry_cache', $where);

        if ($rs) {
            return 'success';
        } else {
            return $this->db->error()['code'];
        }
    }

    function saveJournalEntrycache($comment, $journal_date) {
        $result = $this->Helper->getCurrentUserLedgerJournal();
        $retain_ledger_id = $this->Helper->getLedgerByName('Retained Earnings')->ledger_id;

        if (is_array($result) && count($result) > 0) {

            $this->db->trans_begin();
//echo "INSERT INTO tbl_journal_entry(comment,journal_date,user_transactor) VALUES('$comment',',$journal_date',".$this->session->userinfo->user_id."')";
            $this->db->query("INSERT INTO tbl_journal_entry_pending(comment,journal_date,user_transactor) VALUES('$comment','$journal_date','" . $this->session->userinfo->user_id . "')");

            $query = $this->db->query("SELECT trans_id FROM tbl_journal_entry_pending  WHERE user_transactor='" . $this->session->userinfo->user_id . "' ORDER BY trans_id DESC LIMIT 1");

            $trans_id = $query->result()[0]->trans_id;

            foreach ($result as $val) {
                $this->db->query("INSERT INTO tbl_journal_entry_details_pending(trans_id,ledger_id,amount) VALUES('$trans_id','$val->ledger_id','$val->amount')");
                $ledger_info = $this->Helper->getSectionNameByLedgerId($val->ledger_id);

                if (strtolower($ledger_info->sec_desc) == 'expenses' || strtolower($ledger_info->sec_desc) == 'revenues') {
                    $this->db->query("INSERT INTO tbl_journal_entry_details_pending(trans_id,ledger_id,amount,entry_type) VALUES('$trans_id','$retain_ledger_id','$val->amount','1')");
                }
            }
//echo "DELETE FROM tbl_journal_entry_cache WEHERE user_id='1' <br/>";
            $this->db->query("DELETE FROM tbl_journal_entry_cache WHERE user_id='" . $this->session->userinfo->user_id . "'");


            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return $this->db->error()['message'];
            } else {
                $this->db->trans_commit();
                return 'success';
            }
        } else {
            return 'No ledger entry found.';
        }
    }

    function isJournalEntrycacheValid() {
        $result = $this->Helper->getCurrentUserLedgerJournal();

        $grandTotal = 0;
        foreach ($result as $val) {
            $grandTotal += $val->amount;
        }

        if ($grandTotal == 0) {
            return true;
        }

        return false;
    }

    function getPendingJournalTransaction($id = null) {

        if (is_null($id)) {
            $query = $this->db->query("SELECT trans_id,trans_date_time,journal_date,fname,lname,comment FROM tbl_journal_entry_pending j
                          JOIN tbl_users u ON u.user_id=j.user_transactor
                          ORDER BY trans_date_time DESC LIMIT 200
               ");
        } else {
            $query = $this->db->query("SELECT jd.trans_id,trans_date_time,journal_date,fname,lname,comment,ledger_name FROM tbl_journal_entry_pending j
                          JOIN tbl_users u ON u.user_id=j.user_transactor
                          JOIN tbl_journal_entry_details_pending jd ON jd.trans_id=j.trans_id
                           JOIN tbl_ledgers l ON l.ledger_id=jd.ledger_id
                          WHERE jd.trans_id='$id'
               ");
        }


        return $query->result();
    }

    public function approveJournal($id) {
        $query = $this->db->query("SELECT trans_id FROM tbl_journal_entry_pending WHERE trans_id='$id' ");
        $user_id = $this->session->userinfo->user_id;

        if ($query->num_rows()) {
            echo "Press";
            $this->db->trans_begin();
//echo "INSERT INTO tbl_journal_entry(comment,journal_date,user_transactor) VALUES('$comment',',$journal_date',".$this->session->userinfo->user_id."')";
            $this->db->query("INSERT INTO tbl_journal_entry(comment,journal_date,user_transactor,approved_by) SELECT comment,journal_date,user_transactor,'$user_id' FROM tbl_journal_entry_pending WHERE trans_id='$id'");

            $query = $this->db->query("SELECT trans_id FROM tbl_journal_entry  WHERE approved_by='" . $user_id . "' ORDER BY trans_id DESC LIMIT 1");

            $trans_id = $query->result()[0]->trans_id;

            $this->db->query("INSERT INTO tbl_journal_entry_details(trans_id,ledger_id,amount) SELECT '$trans_id',ledger_id,amount FROM tbl_journal_entry_details_pending WHERE trans_id='$id'");

            $this->db->query("DELETE FROM tbl_journal_entry_details_pending WHERE trans_id='$id'");
            $this->db->query("DELETE FROM tbl_journal_entry_pending WHERE trans_id='$id'");


            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return $this->db->error()['message'];
            } else {
                $this->db->trans_commit();
                return 'success';
            }
        } else {
            return 'No ledger entry found.';
        }
    }

}
