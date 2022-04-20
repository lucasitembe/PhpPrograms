<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Department extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!isset($_SESSION['userinfo'])) {
            redirect('account/login');
        }
        $this->load->model('Budget_model');
        $this->load->model('Chop_model');
        $this->load->model('Department_model');
    }

    public function dept_reg() {
        $show['val1'] = $this->Budget_model->m_show_yob();
        $show['val2'] = $this->Budget_model->m_show_dept();

        $count['num'] = $this->Chop_model->m_count_dokezo();
        $this->load->view('shared/header');
        $this->load->view('department/reg_dept', $show);
        $this->load->view('shared/footer');
    }

    public function reg_dept_action() {
        $this->Department_model->m_reg_dept_action();
        redirect('department/dept_reg');
    }

}
