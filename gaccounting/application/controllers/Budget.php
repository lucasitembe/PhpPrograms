<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Budget extends CI_Controller {

    public function __construct() {

        parent::__construct();

        if (!isset($_SESSION['userinfo'])) {
            redirect('account/login');
        }

        /*
          if (!isset($_SESSION['sess_id'])) {
          redirect('Chop_login/logout');
          }
          $is_access=$this->uri->segment(2);
          if(!$this->Helper->has_access($is_access)){
          redirect('Error/accessdenied');
          }
         * 
         * 
         */
        $this->load->model('Budget_model');
        $this->load->model('Chop_model');
        $this->load->model('Fund_model');
    }

    public function create_budget() {
        $where = array('is_ehms' => '0');
        $show['val'] = $this->Chop_model->m_show_gsf();
        $show['val1'] = $this->Fund_model->m_selecBoxValues('tbl_ledgers', $where);
        //$show['val1'] = $this->Fund_model->m_show_src_fund();
        //$show['val2']=$this->Chop_model->m_show_activity();
        $show['val3'] = $this->Chop_model->m_show_dept();

        $count['num'] = $this->Chop_model->m_count_dokezo();
        $this->load->view('shared/header');
        $this->load->view('budget/create_budget', $show);
        $this->load->view('shared/footer');
    }

    public function create_bgt_action() {
        $this->Budget_model->m_create_bgt_action();
        redirect('budget/create_budget?sent');
    }

    public function dept_budget($xx = 0, $yy = 0) {
        $yob = $xx;
        $dept = $yy;
        $bgtcount = $this->Budget_model->m_count_budget($yob, $dept);
        if ($bgtcount == 0) {
            redirect('budget/view_budget?null');
        } else {
            $data['bgt'] = $this->Budget_model->m_dept_budget($yob, $dept);
            $data['bgt1'] = $this->Budget_model->distinct_id($yob, $dept);

            $count['num'] = $this->Chop_model->m_count_dokezo();
            $this->load->view('shared/header');
            $this->load->view('budget/dept_budget', $data);
            $this->load->view('shared/footer');
        }
    }

    public function view_bgt_action() {
        $year = $this->input->post('yob');
        $dept = $this->input->post('c_center');
        $this->dept_budget($year, $dept);
    }

    public function view_budget() {
        $show['bgt1'] = $this->Budget_model->distinct_id();
        $show['bgt'] = $this->Budget_model->m_dept_budget();
        if ($this->input->is_ajax_request()) {
            $this->load->view('budget/dept_budget', $show);
        } else {
            $show['val1'] = $this->Budget_model->m_show_yob();
            $show['val2'] = $this->Budget_model->m_show_dept();

            $count['num'] = $this->Chop_model->m_count_dokezo();
            $this->load->view('shared/header', $count);
            $this->load->view('budget/view_budget', $show);
            $this->load->view('shared/footer');
        }
    }

    public function upload_excel() {
        $filename = $_FILES["csv"]["tmp_name"];
        if ($_FILES["csv"]["size"] > 0) {
            $file = fopen($filename, "r");
            while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE) {
                //validating cost centre..
                $dept = $emapData[1];
                $activity = $emapData[4];
                $fund_src = $emapData[5];
                $gfs = $emapData[6];
                $count_dept = $this->Budget_model->check_dept($dept);
                $count_act = $this->Budget_model->check_act($activity);
                $count_fundsrc = $this->Budget_model->check_fundsrc($fund_src);
                $count_gfs = $this->Budget_model->check_gfs($gfs);
                if ($count_dept == 0) {
                    redirect('budget/create_budget?dept');
                } else {
                    if (!$count_act) {
                        redirect('budget/create_budget?act');
                    } else {
                        if (!$count_fundsrc) {
                            redirect('budget/create_budget?src');
                        } else {
                            if (!$count_gfs) {
                                redirect('budget/create_budget?gfs');
                            } else {
                                $bgt_amount = $emapData[8] * $emapData[7];
                                $data = array(
                                    'year_of_bgt' => $emapData[0],
                                    'cost_center_ref' => $emapData[1],
                                    'objective' => $emapData[2],
                                    'intervention' => $emapData[3],
                                    'activity_ref' => $emapData[4],
                                    'source_fund_ref' => $emapData[5],
                                    'b_grf_code' => $emapData[6],
                                    'unit_cost' => $emapData[7],
                                    'nbr' => $emapData[8],
                                    'bgt_amount' => $bgt_amount,
                                    'bgt_amount_left' => $bgt_amount,
                                    'when' => $emapData[9],
                                    'by_who' => $emapData[10],
                                );

                                $this->Budget_model->insertCSV($data);
                            }
                        }
                    }
                }
            }
            fclose($file);
        }
        redirect('budget/create_budget?sent');
    }

    public function ajax_activity() {
        $data = $this->Budget_model->m_ajax_activity();

        foreach ($data as $value) {
            echo '<option value="' . $value['activity_id'] . '" > ' . $value['activity_name'] . ' </option> ';
        }
    }

}
