<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Budget_model extends CI_Model {

    public function m_create_bgt_action() {
        $nbr = $_POST['nbr'];
        $unit_cost = $_POST['unit_cost'];
        $total = $nbr * $unit_cost;
        $data = array(
            'cost_center_ref' => $_POST['c_center'],
            'objective' => $_POST['objective'],
//            'intervention' => $_POST['intervene'],
            'activity_ref' => $_POST['activity'],
//            'source_fund_ref' => $_POST['source_fund'],
            'b_grf_code' => $_POST['gfs_code'],
            'unit_cost' => $_POST['unit_cost'],
            'nbr' => $_POST['nbr'],
            'when' => $_POST['when'],
            'by_who' => $_POST['by_who'],
            'year_of_bgt' => $_POST['bgt_year'],
            'bgt_amount' => $total,
            'bgt_amount_left' => $total,
        );

        $this->db->insert('budget', $data);
    }

    public function m_selecBoxValues($table, $where) {
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function m_dept_budget() {
        if (isset($_GET['yob'])) {
            $data1 = $this->input->get('yob');
            $data2 = $this->input->get('c_center');
            $where = array
                (
                'year_of_bgt' => $data1,
                'cost_center_ref' => $data2,
                    //'activity_ref'=>$key['activity_ref']
            );
            $this->db->select('*');
            $this->db->from('budget');
            $this->db->join('grf_codes', 'grf_codes.code=budget.b_grf_code');
            $this->db->join('department', 'department.dept_id=budget.cost_center_ref');
            //$this->db->join('source_fund', 'source_fund.source_fund_id=budget.source_fund_ref');
            $this->db->join('activities', 'activities.activity_id=budget.activity_ref');
            $this->db->where($where);
            $query = $this->db->get();
            $return = $query->result_array();

            return $return;
        }
    }

    public function m_count_budget($yob, $dept) {
        $where = array
            (
            'year_of_bgt' => $yob,
            'cost_center_ref' => $dept
        );
        $this->db->select('year_of_bgt,cost_center_ref');
        $this->db->from('budget');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function distinct_id() {
        if (isset($_GET['yob'])) {
            $data3 = $this->input->get('yob');
            $data4 = $this->input->get('c_center');
            $where = array
                (
                'year_of_bgt' => $data3,
                'cost_center_ref' => $data4,
                    //'activity_ref'=>$key['activity_ref']
            );
            $this->db->distinct('activity_ref');
            $this->db->select('activity_ref');
            $this->db->from('budget');
            $this->db->where($where);
            $query = $this->db->get();
            $return = $query->result_array();
            return $return;
        }
    }

    public function insertCSV($data) {
        $this->db->insert('budget', $data);
        //return $this->db->insert_id();
    }

    public function m_ajax_activity() {
        $dept = $_GET['input_dept'];
        $yob = $_GET['input_yob'];
        $where = array
            (
            'budget_year' => $yob,
            'dept_ref' => $dept
        );
        $this->db->select('*');
        $this->db->from('activities');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    //Budgte excel validation..

    public function check_dept($dept) {

        $this->db->select('*');
        $this->db->from('department');
        $this->db->where('dept_id', $dept);
        $query = $this->db->get();
        return $query->num_rows();
    }

    //Budgte excel validation..

    public function check_act($activity) {

        $this->db->select('*');
        $this->db->from('activities');
        $this->db->where('activity_id', $activity);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function check_fundsrc($fund_src) {

        $this->db->select('*');
        $this->db->from('source_fund');
        $this->db->where('source_fund_id', $fund_src);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function check_gfs($gfs) {

        $this->db->select('*');
        $this->db->from('grf_codes');
        $this->db->where('code', $gfs);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function m_show_yob() {

        $this->db->select('year_of_bgt');
        $this->db->distinct();
        $this->db->from('budget');
        $this->db->order_by('year_of_bgt', 'desc');
        //$this->db->join('grf_codes','grf_codes.code=budget.b_grf_code');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function m_show_dept() {

        $this->db->select('*');
        $this->db->from('department');
        //$this->db->join('grf_codes','grf_codes.code=budget.b_grf_code');
        $query = $this->db->get();
        return $query->result_array();
    }

}
