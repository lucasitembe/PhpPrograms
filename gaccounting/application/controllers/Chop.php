<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Chop extends CI_Controller {

    public function __construct() {
        parent::__construct();

        if (!isset($_SESSION['userinfo'])) {
            redirect('account/login');
        }

//        if (!isset($_SESSION['sess_id'])) {
//            redirect('Chop_login/logout');
//        }
//        $is_access=$this->uri->segment(2);
//        if(!$this->Helper->has_access($is_access)){
//            redirect('Error/accessdenied');
//        }
        $this->load->model('Budget_model');
        $this->load->model('Chop_model');
        $this->load->model('Fund_model');
    }

    public function index() {

        $count['num'] = $this->Chop_model->m_count_dokezo();
        $this->load->view('shared/header', $count);
        $this->load->view('pages/dashboard');
        $this->load->view('shared/footer');
    }

    public function view_report($startDat = 0, $endDat = 0) {

        $show['report'] = $this->Chop_model->m_view_report($startDat, $endDat);

        $count['num'] = $this->Chop_model->m_count_dokezo();
        $this->load->view('shared/header', $count);
        $this->load->view('pages/view_report', $show);
        $this->load->view('shared/footer');
    }

    public function exp_report() {
        $show['report'] = $this->Chop_model->m_view_report();
        if ($this->input->is_ajax_request()) {
            $this->load->view('pages/view_report', $show);
        } else {
            //$show['report'] = $this->Chop_model->m_view_report();
            $count['num'] = $this->Chop_model->m_count_dokezo();
            $this->load->view('shared/header', $count);
            $this->load->view('pages/exp_report', $show);
            $this->load->view('shared/footer');
        }
    }

    public function exp_report_action() {
        $startDate = $this->input->post('start_date');
        $endDate = $this->input->post('end_date');
        $this->view_report($startDate, $endDate);
    }

}
