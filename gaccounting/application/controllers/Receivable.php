<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Gledger
 *
 * @author ADE
 */
class Receivable extends CI_Controller {

    public function __construct() {
        parent::__construct();

        if (!isset($_SESSION['userinfo'])) {
            redirect('account/login');
        }
        $this->load->model('Receivables');
    }

    public function index() {
        $this->load->view('shared/header');
        $this->load->view("receivable/index");
        $this->load->view('shared/footer');
    }

    public function debtors() {
        $data['debtors'] = $this->Receivables->getDebtors();

        if ($this->input->is_ajax_request()) {
            $this->load->view("receivable/debtors_partial", $data);
        } else {
            $this->load->view('shared/header');
            $this->load->view("receivable/debtors", $data);
            $this->load->view('shared/footer');
        }
    }

}
