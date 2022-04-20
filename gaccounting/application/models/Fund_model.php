<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Fund_model extends CI_Model {

    public function m_reg_src_fund() {
        $data = array
            (
            'fund_code' => $_POST['fund_code'],
            'source_name' => $_POST['fund_name']
        );
        $rs = $this->db->insert('source_fund', $data);
        if ($rs) {
            return 'success';
        } else {
            return $this->db->error()['code'];
        }
    }

    public function m_show_yof() {
        $this->db->select('alloc_year');
        $this->db->distinct();
        $this->db->from('fund_allocation');
        $this->db->order_by('alloc_year', 'desc');
        //$this->db->join('grf_codes','grf_codes.code=budget.b_grf_code');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function m_selecBoxValues($table, $where) {
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function m_show_src_income() {
        $this->db->select('*');
        $this->db->from('tbl_acc_group');
        $this->db->where('sec_id', '1');
        //$this->db->join('grf_codes','grf_codes.code=budget.b_grf_code');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function m_check_fundAlloc() {
        $where = array(
            'src_fund_id' => $_POST['source_fund'],
            'alloc_year' => $_POST['alloc_year']
        );
        $this->db->select('*');
        $this->db->from('fund_allocation');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function m_fundAllocAction() {
        $data = array(
            'src_fund_id' => $_POST['source_fund'],
            'alloc_amount' => $_POST['alloc_amount'],
            'alloc_balance' => $_POST['alloc_amount'],
            'alloc_year' => $_POST['alloc_year']
        );

        $this->db->insert('fund_allocation', $data);
    }

    public function m_fundAllocAction_gacc() {
        $data = array(
            'src_fund_id' => $_POST['source_fund'],
            'ledger_id' => $_POST['ledger_id'],
            'alloc_amount' => $_POST['alloc_amount'],
            'alloc_balance' => $_POST['alloc_amount'],
            'alloc_year' => $_POST['alloc_year']
        );

        $this->db->insert('fund_allocation', $data);
    }

    public function m_show_fundAlloc() {
        $start_date = $_GET['start_date'];
        $end_date = $_GET['end_date'];
        $alloc = $_GET['yob'];
        $this->db->select('*');
        $this->db->from('fund_allocation');
        $this->db->join('department', 'department.dept_id=fund_allocation.src_fund_id');
        $this->db->where('alloc_year', $alloc);
        $this->db->where('is_ehms', '1');
        $query = $this->db->get();
        $budgeted = $query->result_array();
        $AllbudgetedAndActual = array();
        foreach ($budgeted as $value) {
            $actualAmount = $this->getActualAmountEhms($start_date, $end_date, $value['dept_name']);
            $content = array('dept_name' => $value['dept_name'], 'yob' => $value['alloc_year'], 'budgetedAmount' => $value['alloc_amount'], 'actualAmount' => $actualAmount);
            $budgetedAndActual = array(
                $value['dept_name'] => $content
            );
            array_push($AllbudgetedAndActual, $budgetedAndActual);
        }
        return $AllbudgetedAndActual;
    }

    public function getActualAmountEhms($start_date, $end_date, $dept_name) {
        $json = file_get_contents($this->Helper->getConfigValue('EhmsUrl') . '/api/api.php?gacc=budget&start_date=' . $start_date . '&end_date=' . $end_date . '&dept_name=' . $dept_name);
        $decodedSum = json_decode($json);
        $actualAmount = $decodedSum->ActualgrandTotal;
        return $actualAmount;
    }

    public function m_show_fundAlloc_gacc() {
        $start_date = $_GET['start_date'];
        $end_date = $_GET['end_date'];
        $alloc = $_GET['yob'];
        $this->db->select('dept.dept_name,ldg.ledger_name,fund.alloc_amount,fund.alloc_year,fund.ledger_id');
        $this->db->from('fund_allocation fund');
        $this->db->join('department dept', 'dept.dept_id=fund.src_fund_id');
        $this->db->join('tbl_ledgers ldg', 'ldg.ledger_id=fund.ledger_id');
        $this->db->where('alloc_year', $alloc);
        $this->db->where('ldg.is_ehms', '0');
        $query = $this->db->get();
        $result = $query->result_array();
        $AllbudgetedAndActual = array();
        foreach ($result as $value) {
            $ledgerActualAmount = $this->getActualAmountLedgers($start_date, $end_date, $value['ledger_id']);
            $content = array('ledger'=>$value['ledger_name'],'dept_name' => $value['dept_name'], 'yob' => $value['alloc_year'], 'budgetedAmount' => $value['alloc_amount'], 'actualAmount' => $ledgerActualAmount);
            $budgetedAndActual = array($value['dept_name'] => $content);
            array_push($AllbudgetedAndActual, $budgetedAndActual);
        }
        return $AllbudgetedAndActual;
    }

    private function getActualAmountLedgers($start_date, $end_date, $id) {
        $this->db->select_sum('amount');
        $this->db->from('tbl_journal_entry je');
        $this->db->join('tbl_journal_entry_details jed', 'jed.trans_id=je.trans_id');
        $this->db->where('jed.ledger_id', $id);
        $this->db->where('je.journal_date BETWEEN  "' . $start_date . '"  AND  "' . $end_date . '"');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result[0]['amount'];
    }

}
