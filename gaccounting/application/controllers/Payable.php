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
class Payable extends CI_Controller {

    public function __construct() {
        parent::__construct();

        if (!isset($_SESSION['userinfo'])) {
            redirect('account/login');
        }
        $this->load->model('Payables');
    }

    public function index() {
        $this->load->view('shared/header');
        $this->load->view("payable/index");
        $this->load->view('shared/footer');
    }

    public function payable() {
        $data['payable'] = $this->Payables->getPayables();

        if ($this->input->is_ajax_request()) {
            $this->load->view("payable/payable_partial", $data);
        } else {
            $this->load->view('shared/header');
            $this->load->view("payable/payable", $data);
            $this->load->view('shared/footer');
        }
    }

}
