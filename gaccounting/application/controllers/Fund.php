<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Fund extends CI_Controller {

    public function __construct() {
        parent::__construct();

        if (!isset($_SESSION['userinfo'])) {
            redirect('account/login');
        }
        $this->load->model('Budget_model');
        $this->load->model('Chop_model');
        $this->load->model('Fund_model');
    }

    public function reg_src_fund() {

        $show['val3'] = $this->Chop_model->m_show_dept();
        //$show['srcFund']=$this->Fund_model->m_show_src_fund();
        $count['num'] = $this->Chop_model->m_count_dokezo();
        $this->load->view('shared/header');
        $this->load->view('fund/reg_source_fund', $show);
        $this->load->view('shared/footer');
    }

    public function reg_src_fund_action() {
        $msg = "";
        $status = "";
        //process input form 
        $this->form_validation->set_rules('fund_code', 'Source of fund code', 'trim|required');
        $this->form_validation->set_rules('fund_name', 'Main account name', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $msg = validation_errors();
        } else {
            $result = $this->Fund_model->m_reg_src_fund();
            if ($result == 'success') {
                $msg = "Inserted successifully";
                $status = '1';
            } else {
                if ($result == '23000/1062') {
                    $msg = "Source of fund already exists.";
                } else {
                    $msg = "An error has occured.Please try again";
                }
                $status = '0';
            }
        }
        echo json_encode(array('tokenhash' => $this->security->get_csrf_hash(), 'data' => $msg, 'status' => $status));
        //redirect('fund/reg_src_fund');
    }

    public function fund_alloc() {
        //$show['val1'] = $this->Fund_model->m_show_src_fund();
        $id = array(
            'sec_id' => 1
        );
        $where = array('is_ehms' => '0');
        $wheredept = array('is_ehms' => '0');
        $wheredeptehms = array('is_ehms' => '1');
        $show['val'] = $this->Fund_model->m_selecBoxValues('tbl_ledgers', $where);
        $show['dept'] = $this->Fund_model->m_selecBoxValues('department', $wheredept);
        $show['deptEhms'] = $this->Fund_model->m_selecBoxValues('department', $wheredeptehms);
        $show['year'] = $this->Fund_model->m_show_yof();
        $count['num'] = $this->Chop_model->m_count_dokezo();
        $this->load->view('shared/header', $count);
        $this->load->view('fund/fund_alloc', $show);
        $this->load->view('shared/footer');
    }

    public function fundAllocAction() {
        $countAlloc = $this->Fund_model->m_check_fundAlloc();
        if ($countAlloc > 0) {
            redirect('fund/fund_alloc?Error');
        } else {
            $this->Fund_model->m_fundAllocAction();
        }
        redirect('fund/fund_alloc');
    }

    public function fundAllocAction_gacc() {
        $countAlloc = $this->Fund_model->m_check_fundAlloc();
        if ($countAlloc > 0) {
            redirect('fund/fund_alloc?Error');
        } else {
            $this->Fund_model->m_fundAllocAction_gacc();
        }
        redirect('fund/fund_alloc');
    }

    public function show_fundAlloc() {
        $data = $this->Fund_model->m_show_fundAlloc();
//        echo "<pre/>";
//        print_r($data);
//        exit;
        if (!$data) {
            echo "<center><h2 style='color:red'>No fund allocation found!</h2></center>";
        } else {
            echo '
      <table style="background-color:white" class="table  table-bordered table-striped table-hover">
           <tr>
            <thead>
            <th>Revenue center</th>
            <th>Year of budget</th>
            <th>Budgeted amount</th>
            <th>Actual amount</th>
            <th>Variance</th>
            </thead>
           </tr>
          <tbody>
          ';
            foreach ($data as $view => $key) {
                foreach ($key as $view1) {
                    $variance = $view1['actualAmount'] - $view1['budgetedAmount'];
                    echo "
                        <td>" . $view1['dept_name'] . "</td>
                        <td>" . $view1['yob'] . "</td>
                        <td>" . number_format($view1['budgetedAmount']) . "</td>
                        <td>" . number_format($view1['actualAmount']) . "</td>
                        <td>" . number_format($variance) . "</td>
                        </tr>";
                }
            }
            echo "        
          </tbody>
          </table>
          ";
        }
    }

    public function show_fundAlloc_gacc() {
        $data = $this->Fund_model->m_show_fundAlloc_gacc();
//        echo $data;
//        echo "<pre/>";
//        print_r($data);
//        exit;
        if (!$data) {
            echo "<center><h2 style='color:red'>No fund allocation found!</h2></center>";
        } else {
            echo '
      <table style="background-color:white" class="table  table-bordered table-striped table-hover">
           <tr>
            <thead>
            <th>Revenue center</th>
            <th>Ledger name</th>
            <th>Year of budget</th>
            <th>Budgeted amount</th>
            <th>Actual amount</th>
            <th>Variance</th>
            </thead>
           </tr>
          <tbody>
          ';
            foreach ($data as $view => $key) {
                foreach ($key as $view1) {
                    $variance = $view1['actualAmount'] - $view1['budgetedAmount'];
                    echo "
                        <td>" . $view1['dept_name'] . "</td>
                        <td>" . $view1['ledger'] . "</td>
                        <td>" . $view1['yob'] . "</td>
                        <td>" . number_format($view1['budgetedAmount']) . "</td>
                        <td>" . number_format($view1['actualAmount']) . "</td>
                        <td>" . number_format($variance) . "</td>
                        </tr>";
                }
            }
            echo "        
          </tbody>
          </table>
          ";
        }
    }

    public function actualProjectionIncome() {
        //$show['val1'] = $this->Fund_model->m_show_src_fund();
        $show['val1'] = $this->Fund_model->m_show_src_income();
        $show['year'] = $this->Fund_model->m_show_yof();
        $count['num'] = $this->Chop_model->m_count_dokezo();
        $this->load->view('shared/header', $count);
        $this->load->view('fund/actualProjectionIncome', $show);
        $this->load->view('shared/footer');
    }

    public function actualProjectionIncomegacc() {
        //$show['val1'] = $this->Fund_model->m_show_src_fund();
        $show['val1'] = $this->Fund_model->m_show_src_income();
        $show['year'] = $this->Fund_model->m_show_yof();
        $count['num'] = $this->Chop_model->m_count_dokezo();
        $this->load->view('shared/header', $count);
        $this->load->view('fund/actualProjectionIncomeGacc', $show);
        $this->load->view('shared/footer');
    }

}
