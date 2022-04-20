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
class Purchase extends CI_Controller {

    public function __construct() {
        parent::__construct();

        if (!isset($_SESSION['userinfo'])) {
            redirect('account/login');
        }
        $this->load->model('Purchases');
    }

    public function index() {
        $this->load->view('shared/header');
        $this->load->view("purchase/index");
        $this->load->view('shared/footer');
    }

    public function purchase() {
        $data['purchases'] = $this->Purchases->getPurchases();

        if ($this->input->is_ajax_request()) {
            $this->load->view("purchase/purchase_partial", $data);
        } else {
            $this->load->view('shared/header');
            $this->load->view("purchase/purchase", $data);
            $this->load->view('shared/footer');
        }
    }

}
