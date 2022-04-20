<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Account
 *
 * @author Nassor Nassor
 */
class JournalEntry extends CI_Controller {

    public function __construct() {
        parent::__construct();

        if (!isset($_SESSION['userinfo'])) {
            redirect('account/login');
        }
        $this->loadModels();
    }

    public function loadModels() {
        $this->load->model('Gledgers');
        $this->load->model('JournalEntryModel');
    }

    public function index() {
        if ($this->input->post() != null) {
//            echo '<pre>';
//            echo print_r($_POST);exit;

            $msg = "";
            $status = "";
            $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
            $this->form_validation->set_rules('acc_ledgers', 'Ledger', 'trim|required');
            $this->form_validation->set_rules('trans_type', 'Debit / Credit', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                $msg = validation_errors();
            } else {

                $result = $this->JournalEntryModel->addJournal();

                if ($result == 'success') {
                    $msg = "Saved successifully";
                    //echo '<script>alert("added")</script>';
                    $status = '1';
                } else {
                    if ($result == '23000/1062') {
                        $msg = "Entry already exists.";
                    } else {
                        $msg = "An error has occured.Please try again";
                    }

                    $status = '0';
                }
            }


            echo json_encode(array('tokenhash' => $this->security->get_csrf_hash(), 'data' => $msg, 'status' => $status));
        } else {
            $data['ledgers'] = $this->Helper->getLedgersList();
            // echo $this->db->last_query();exit;
            $data['acc_sections'] = $this->Helper->getList('tbl_acc_section');
            $data['currentAccountYear'] = $this->Helper->getCurrentAccountYear();
            $data['currentUserLedgerJournal'] = $this->Helper->getCurrentUserLedgerJournal();
            $this->load->view('shared/header');
            $this->load->view("journalentry/index", $data);
            $this->load->view('shared/footer');
        }
    }

    function deleteJournalEntrycache() {
        $id = $this->input->get('id');
        $result = $this->JournalEntryModel->deleteJournalEntryCache($id);

        echo $result;
    }

    function saveJournalEntrycache() {
        if ($this->JournalEntryModel->isJournalEntrycacheValid()) {
            $comment = $this->input->get('comment');
            $journal_date = $this->input->get('journal_date');
            $result = $this->JournalEntryModel->saveJournalEntrycache($comment, $journal_date);
        } else {
            $result = "invalid";
        }

        echo $result;
    }

    function ApproveJournal() {
        $data['type'] = 'summery';
        if (isset($_GET['approve'])) {
            $this->JournalEntryModel->approveJournal($_GET['id']);
            $data['pendingJournalTransaction'] = $this->JournalEntryModel->getPendingJournalTransaction();
        } else {
            if (isset($_GET['id'])) {
                $data['type'] = 'details';
                $data['pendingJournalTransactionDetails'] = $this->JournalEntryModel->getPendingJournalTransaction($_GET['id']);
            } else {
                $data['pendingJournalTransaction'] = $this->JournalEntryModel->getPendingJournalTransaction();
            }
        }

        $this->load->view('shared/header');
        $this->load->view("journalentry/ApproveJournal", $data);
        $this->load->view('shared/footer');
    }

}
