<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Activity_model extends CI_Model {

    public function m_show_yoa() {
        $this->db->select('budget_year');
        $this->db->distinct();
        $this->db->from('activities');
        $this->db->order_by('budget_year', 'desc');
        //$this->db->join('grf_codes','grf_codes.code=budget.b_grf_code');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function m_show_reg_activity() {
        $dept = $_GET['input_dept'];
        $yoa = $_GET['input_yoa'];
        $where = array
            (
            'budget_year' => $yoa,
            'dept_ref' => $dept
        );
        $this->db->select('*');
        $this->db->from('activities');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function activity_insertCSV($data) {
        $this->db->insert('activities', $data);
        //return $this->db->insert_id();
    }

    public function m_reg_activity_action() {
        $data = array
            (
            'activity_name' => filter_input(INPUT_POST, 'act_desc'),
            'budget_year' => $_POST['act_yob'],
            'dept_ref' => $_POST['act_dept']
        );
        $this->db->insert('activities', $data);
    }

    public function check_dep_act($check) {
        $this->db->select('*');
        $this->db->from('department');
        $this->db->where('dept_id', $check);
        $query = $this->db->get();
        return $query->num_rows();
    }

}
