<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Api extends CI_Controller {

    public function __construct() {
        parent::__construct();

//        if (!isset($_SESSION['userinfo'])) {
//            redirect('account/login');
//        }
        $this->load->model('Api_model');
    }

    public function ledgerFromEhms() {

        $postdata = trim(file_get_contents("php://input"));
        $rawdata = json_decode($postdata);
        $ledger_name = $rawdata->Supplier_Name;
        $acc_code = $rawdata->account_name;
        $data = array(
            'ledger_name' => $ledger_name,
            'acc_code' => $acc_code,
//            'ehms_based_type' => '',
//            'ehms_based_value' => '',
            'is_ehms' => 1,
        );
        echo $this->Api_model->addEhmsLedger($data);
        //echo $acc_code;
    }

    public function accounts() {
        $data = $this->Helper->getList('tbl_accounts', 'array');
        $data1 = json_encode($data);
        echo $data1;
    }

    public function ledgerOnSponsorsFromEhms() {
        $postdata = trim(file_get_contents("php://input"));
        $rawdata = json_decode($postdata);
        $ledger_name = $rawdata->Ledger_Name;
        $acc_code = $rawdata->account_code;
        $data = array(
            'Ledger_Name' => $ledger_name,
            'acc_code' => $acc_code,
//            'ehms_based_type' => '',
//            'ehms_based_value' => '',
            'is_ehms' => 1,
        );
        echo $this->Api_model->addEhmsLedger($data);
        //echo $acc_code;     
    }
    public function ehmsJournalEntry(){
        $data = trim(file_get_contents("php://input"));
        $rawdata = json_decode($data);
        
        if(isset($_GET['edit'])){
             $status=$this->Api_model->editEhmsJournalEntry($rawdata); 
        }else if(isset($_GET['cancell'])){
            echo 'Cancell';
        }else{
           $status=$this->Api_model->saveEhmsJournalEntry($rawdata); 
        }
        
        echo $status;
//        echo "<pre/>";
//        print_r($rawdata);
      
    }

    //ladger from HRP
    public function ledgerOnSalaryFromhr() {

        $postdata = trim(file_get_contents("php://input"));
        $rawdata = json_decode($postdata);
          // echo "<pre>";
          // print_r($rawdata);
          // exit();
       
    $result= $this->Api_model->saveJournalEntryForHR($rawdata);

    echo $result;
 }

 public function ledger(){
    $query =$this->db->get('tbl_ledgers')->result_array();
    echo json_encode($query);

 }

 public function ledgerLoanFromhr(){
   $postdata = trim(file_get_contents("php://input"));
     $rawdata = json_decode($postdata);

    
       
    $result= $this->Api_model->saveJournalEntryForHRLoan($rawdata);

    echo $result; 


 }


}
