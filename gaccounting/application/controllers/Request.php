<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Request extends CI_Controller {

    public function __construct() {
        parent::__construct();

        if (!isset($_SESSION['userinfo'])) {
            redirect('account/login');
        }

        $this->load->model('Request_model');
        $this->load->library('pagination');
        $this->load->model('Chop_model');
        $this->load->model('Fund_model');
    }

    public function make_request() {
        $show['val'] = $this->Chop_model->m_show_gsf();
        //$show['val1'] = $this->Fund_model->m_show_src_fund();
        $show['val2'] = $this->Request_model->m_show_activity();
        $show['val3'] = $this->Chop_model->m_show_dept();

        $count['num'] = $this->Chop_model->m_count_dokezo();
        $this->load->view('shared/header', $count);
        $this->load->view('request/make_request', $show);
        $this->load->view('shared/footer');
    }

    public function make_rqst_action() {
        $checkamnt = $this->input->post('needed_amount');
        $countBgt = $this->Request_model->m_check_bgt();
        if ($countBgt['count'] > 0) {
            if ($countBgt['result'][0]['bgt_amount_left'] < $checkamnt) {
                redirect('request/make_request?neg');
            } else {
                $this->Request_model->m_make_rqst_action();
            }
        } else {
            redirect('request/make_request?Error');
        }
        redirect('request/make_request');
    }

    public function view_dokezo($page = 0) {

        $config = $this->config->item('pagination');
        $config['base_url'] = base_url('request/view_dokezo');
        $config['total_rows'] = $this->Chop_model->m_count_dokezo();
        $config['per_page'] = 2;

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $show['pagination'] = $this->pagination->create_links();

        $show['rqst'] = $this->Request_model->m_show_request($page);
        $count['num'] = $this->Chop_model->m_count_dokezo();
        $this->load->view('shared/header', $count);
        $this->load->view('request/view_dokezo', $show);
        $this->load->view('shared/footer');
    }

    public function more_dokezo($zoo) {

        $act = $this->uri->segment(4);
        $dept = $this->uri->segment(5);
        $gfs = $this->uri->segment(6);
//        $src_fund = $this->uri->segment(7);
        $show['data'] = $this->Request_model->m_more_dokezo($zoo);
        $show['data1'] = $this->Request_model->m_activity_bgt($act, $dept, $gfs);
        $this->load->view('request/more_dokezo', $show);
    }

    public function approve() {

        $amount = $this->input->post('needed_amount');
        $dokid = $this->input->post('rqst_id');
        $activity = $this->input->post('activity');
        $code = $this->input->post('gfs_code');
        $center = $this->input->post('dept');
        //$src_fund = $this->input->post('src_fund');
//        $yob = $this->input->post('yob');
        //selecting where to reduce from fund allocation
//        $whereFund = array(
//            'src_fund_id' => $src_fund,
//            'alloc_year' => $yob
//        );
//        $fundAlloc = $this->Request_model->m_deductFundAlloc($whereFund);
//        $this->db->trans_start();
//        //deducting from fund allocations
//        foreach ($fundAlloc as $deduct) {
//            $cashAlloc = $deduct['alloc_balance'] - $amount;
//            $updateAlloc = array
//                (
//                'alloc_balance' => $cashAlloc
//            );
//            $whereAlloc = array
//                (
//                'src_fund_id' => $src_fund,
//                'alloc_year' => $yob
//            );
//            $this->db->where($whereAlloc);
//            $this->db->update('fund_allocation', $updateAlloc);
//        }
        //selecting where to reduce from budget
        $this->db->trans_start();
        $reduce = array
            (
            'b_grf_code' => $code,
            //'source_fund_ref' => $src_fund,
            'activity_ref' => $activity,
            'cost_center_ref' => $center,
        );
        $comp = $this->Request_model->m_slct_deduct_bgt($reduce);
        //deducting from budget
        foreach ($comp as $minus) {
            $cash = $minus['bgt_amount_left'] - $amount;
            $update = array
                (
                'bgt_amount_left' => $cash
            );
            $where = array
                (
                'b_grf_code' => $code,
                //'source_fund_ref' => $src_fund,
                'activity_ref' => $activity,
                'cost_center_ref' => $center,
            );
            $this->db->where($where);
            $this->db->update('budget', $update);
        }
        //Inserting into expenditures
        $Expdata = array
            (
            'exp_bgt_ref' => $_POST['exp_bgt_id'],
            'exp_gfs_code_ref' => $code,
            'exp_dept_ref' => $center,
            //'exp_src_fund_ref' => $src_fund,
            'exp_act_ref' => $activity,
            'exp_amount' => $amount,
            'exp_bgt_balance' => $cash
        );
        $this->db->insert('expenditures', $Expdata);
        $this->db->trans_complete();
        //if ($this->Helper->has_access('Voucher')) {
        $this->create_voucher($dokid);
        // } else {
        // redirect('request/view_dokezo');
        //}
    }

    public function create_voucher($dok = 0) {
        if ($dok == 0) {
            redirect('request/view_dokezo');
        }
        $show['vou'] = $this->Request_model->m_val_voucher($dok);
        $count['num'] = $this->Chop_model->m_count_dokezo();
        $this->load->view('shared/header', $count);
        $this->load->view('request/create_voucher', $show);
        $this->load->view('shared/footer');
    }

    public function create_voucher_action() {
        $this->Request_model->m_create_voucher_action();
        redirect('request/view_dokezo');
    }

    public function view_vouchers($more) {
        $data['view'] = $this->Request_model->m_more_voucher($more);
        $this->load->view('request/view_vouchers', $data);
    }

    public function preview_vouchers() {
        $data['show'] = $this->Request_model->m_view_voucher();
        $count['num'] = $this->Chop_model->m_count_dokezo();
        $this->load->view('shared/header', $count);
        $this->load->view('request/prev_vouchers', $data);
        $this->load->view('shared/footer');
    }

}
