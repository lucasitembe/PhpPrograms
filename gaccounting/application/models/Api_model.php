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
            $ref_no = $value->ref_no;
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
            $journal = $this->journal_entry_details($trans_id, $debit_entry_ledger_id, $debt, $retain_ledger_id, $source_id, $source_name, $ref_no);
            $journal_ = $this->journal_entry_details($trans_id, $credit_entry_ledger_id, $credit, $retain_ledger_id, $source_id, $source_name, $ref_no);

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

    public function journal_entry_details($trans_id, $ledger_id, $debtCredit, $retain_ledger_id, $source_id, $source_name, $ref_no) {
        $has_error = false;

        $check_error = $this->db->query("INSERT INTO tbl_journal_entry_details(trans_id,ledger_id,amount,source_id,source_name,ref_no) VALUES('$trans_id','$ledger_id','$debtCredit','$source_id','$source_name','$ref_no')");
        if (!$check_error) {
            $has_error = true;
        }
        $ledger_info = $this->Helper->getSectionNameByLedgerId($ledger_id);
        if (strtolower($ledger_info->sec_desc) == 'expenses' || strtolower($ledger_info->sec_desc) == 'revenues') {
            $check_error1 = $this->db->query("INSERT INTO tbl_journal_entry_details(trans_id,ledger_id,amount,entry_type,source_id,source_name) VALUES('$trans_id','$retain_ledger_id','$debtCredit','1','$source_id','$source_name')");
            if (!$check_error1) {
                $has_error = true;
            }
        }

        return $has_error;
    }
//save journal from journal entry



     function saveJournalEntryForHR($rawdata) {
        
        $retain_ledger_id = $this->Helper->getLedgerByName('Retained Earnings')->ledger_id;
        $pay_roll_ledger_id= $this->Helper->getLedgerByName($rawdata->ladger_name[0])->ledger_id;
        $accured_payroll_ledger_id= $this->Helper->getLedgerByName($rawdata->ladger_name[1])->ledger_id;
        $nssf_ladger_id=$this->Helper->getLedgerByName($rawdata->nssf[1])->ledger_id;
        $payee_ladger_id=$this->Helper->getLedgerByName($rawdata->payee[1])->ledger_id;
        $loan_ladger_id=$this->Helper->getLedgerByName($rawdata->loan[1])->ledger_id;

        $payroll_ladger_amount=$rawdata->ladger_amount[0];
        $accured_ladger_amount=$rawdata->ladger_amount[1];
        $nssf_ladger_amount=$rawdata->nssf[0];
        $payee_ladger_amount=$rawdata->payee[0];
        $loan_ladger_amount=$rawdata->loan[0];


        $naration =$rawdata->naration[0];



      //  check if payroll alresy exist in gaccounting
        $query=$this->db->get_where('tbl_journal_entry',array('comment'=>$naration))->result();
        if($query){

            return 'payroll already exist in gAccounting';

        }else{

                  $userid=$rawdata->userid[0];
        $name=$rawdata->name[0];
        $source_id=$rawdata->source_id[0];

       
        $this->db->trans_begin();
            //echo "INSERT INTO tbl_journal_entry(comment,journal_date,user_transactor) VALUES('$comment',',$journal_date',".$this->session->userinfo->user_id."')";
            $this->db->query("INSERT INTO tbl_journal_entry(comment,journal_date,user_transactor,user_type,employee_name) VALUES('$naration',CURDATE(),'$userid',2,'$name')");

            $query = $this->db->query("SELECT trans_id FROM tbl_journal_entry  WHERE user_transactor='$userid' ORDER BY trans_id DESC LIMIT 1");

            $trans_id = $query->result()[0]->trans_id;
      

                 $this->db->query("INSERT INTO tbl_journal_entry_details(trans_id,ledger_id,amount,source_id) VALUES('$trans_id','$pay_roll_ledger_id','$payroll_ladger_amount','$source_id')");
                 $this->db->query("INSERT INTO tbl_journal_entry_details(trans_id,ledger_id,amount,source_id) VALUES('$trans_id','$pay_roll_ledger_id','-$loan_ladger_amount','$source_id')");
                 $this->db->query("INSERT INTO tbl_journal_entry_details(trans_id,ledger_id,amount,source_id) VALUES('$trans_id','$pay_roll_ledger_id','$nssf_ladger_amount','$source_id')");
                 $this->db->query("INSERT INTO tbl_journal_entry_details(trans_id,ledger_id,amount,source_id) VALUES('$trans_id','$pay_roll_ledger_id','$payee_ladger_amount','$source_id')");
                 $this->db->query("INSERT INTO tbl_journal_entry_details(trans_id,ledger_id,amount,source_id) VALUES('$trans_id','$accured_payroll_ledger_id','$accured_ladger_amount','$source_id')");
                 $this->db->query("INSERT INTO tbl_journal_entry_details(trans_id,ledger_id,amount,source_id) VALUES('$trans_id','$loan_ladger_id','$loan_ladger_amount','$source_id')");
                 $this->db->query("INSERT INTO tbl_journal_entry_details(trans_id,ledger_id,amount,source_id) VALUES('$trans_id','$nssf_ladger_id','-$nssf_ladger_amount','$source_id')");
                 $this->db->query("INSERT INTO tbl_journal_entry_details(trans_id,ledger_id,amount,source_id) VALUES('$trans_id','$payee_ladger_id','-$payee_ladger_amount','$source_id')");
                 $this->db->query("INSERT INTO tbl_journal_entry_details(trans_id,ledger_id,amount,source_id,entry_type) VALUES('$trans_id','$retain_ledger_id','$payroll_ladger_amount','$source_id',1)");



            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return 'Data not sent to gAccounting';
            } else {
                $this->db->trans_commit();
                return 'success';
            }


        }
        
    
    }
//hr loan
  public function saveJournalEntryForHRLoan($rawdata){
        $employee_ladger_id=$rawdata->ladger_id[0];
        $cash_ledger_id= $this->Helper->getLedgerByName($rawdata->ladger_id[1])->ledger_id;
        $interest_ledger_id= $this->Helper->getLedgerByName($rawdata->ladger_id[2])->ledger_id;
        $ledger_name= $this->Helper->getLedgerById($employee_ladger_id)->ledger_name;


        $employee_ladger_amount=$rawdata->loan_amount[0];
         $cash_ledger_amount= -$rawdata->interest[0];
         $interest_ledger_amount=-$rawdata->base_amount[0];
         $naration ="Loan for ".$ledger_name;
         $userid=$rawdata->userid[0];
         $name=$rawdata->name[0];
         $source_id=$rawdata->source_id[0];

        $this->db->trans_begin();
            //echo "INSERT INTO tbl_journal_entry(comment,journal_date,user_transactor) VALUES('$comment',',$journal_date',".$this->session->userinfo->user_id."')";
            $this->db->query("INSERT INTO tbl_journal_entry(comment,journal_date,user_transactor,user_type,employee_name) VALUES('$naration',CURDATE(),'$userid',2,'$name')");

            $query = $this->db->query("SELECT trans_id FROM tbl_journal_entry  WHERE user_transactor='$userid' ORDER BY trans_id DESC LIMIT 1");

            $trans_id = $query->result()[0]->trans_id;

                $this->db->query("INSERT INTO tbl_journal_entry_details(trans_id,ledger_id,amount,source_id) VALUES('$trans_id','$employee_ladger_id','$employee_ladger_amount','$source_id')");
                $this->db->query("INSERT INTO tbl_journal_entry_details(trans_id,ledger_id,amount,source_id) VALUES('$trans_id',' $cash_ledger_id','$cash_ledger_amount','$source_id')");
                $this->db->query("INSERT INTO tbl_journal_entry_details(trans_id,ledger_id,amount,source_id) VALUES('$trans_id','$interest_ledger_id','$interest_ledger_amount','$source_id')");



            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return 'Data not sent to gAccounting';
            } else {
                $this->db->trans_commit();
                return 'success';
            }




    }



    public function editEhmsJournalEntry($rawdata) {
        $retain_ledger_id = $this->Helper->getLedgerByName('Retained Earnings')->ledger_id;

        $has_error = false;
        foreach ($rawdata as $value) {
            $ref_no = $value->ref_no;
            $source_name = $value->source_name;
            $source_id = $value->source_id;
            $Employee_Name = $value->Employee_Name;
            $user_transactor = $value->Employee_ID;
            $user_type = 1;
            $sub_total = $value->sub_total;
            $comment = $value->comment;

            $debit_entry_ledger_id = $this->Helper->getLedgerByName($value->debit_entry_ledger)->ledger_id;
            $credit_entry_ledger_id = $this->Helper->getLedgerByName($value->credit_entry_ledger)->ledger_id;

            $query = $this->db->query("SELECT trans_id FROM tbl_journal_entry_details  WHERE source_id='$source_id' AND  source_name='$source_name' LIMIT 1");
//ECHO "SELECT trans_id FROM journal_entry_details  WHERE source_id='$source_id' AND  source_name='$source_name' LIMIT 1";
           
            $trans_id = $query->result()[0]->trans_id;
            
            $deleteStatus = $this->deleteJournalEntry($source_id, $source_name);

            if (!$deleteStatus) {
                $has_error = true;
            }

            $credit = 0 - $sub_total;    
            $debt = $sub_total;
            $journal = $this->journal_entry_details($trans_id, $debit_entry_ledger_id, $debt, $retain_ledger_id, $source_id, $source_name, $ref_no);
            $journal_ = $this->journal_entry_details($trans_id, $credit_entry_ledger_id, $credit, $retain_ledger_id, $source_id, $source_name, $ref_no);

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

    public function deleteJournalEntry($source_id, $source_name) {
        $this->db->delete('tbl_journal_entry_details', array('source_id' => $source_id, 'source_name' => $source_name));
    }
 public function deleteEhmsJournalEntry($rawdata) {
         foreach ($rawdata as $value) {
            $ref_no = $value->ref_no;
            $source_name = $value->source_name;
            $source_id = $value->source_id;
            $Employee_Name = $value->Employee_Name;
            $user_transactor = $value->Employee_ID;
            $user_type = 1;
            $sub_total = $value->sub_total;
            $comment = $value->comment;
       $data=array('status' => "cancelled");
       $this->db->where(array('source_id' => $source_id));
       $this->db->update('tbl_journal_entry_details',$data);
           
        }    
 }
}
