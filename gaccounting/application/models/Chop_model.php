<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Chop_model extends CI_Model {

    public function m_show_gsf() {

        $this->db->select('*');
        $this->db->from('grf_codes');
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

    public function m_view_report() {
        if (isset($_GET['start_date'])) {
            $start_date = $this->input->get('start_date');
            $end_date = $this->input->get('end_date');
            $this->db->select('gr.code,gr.grf_desc,de.dept_name,'
                    . 'ex.exp_bgt_balance,ex.exp_amount,bu.bgt_amount');
            //$this->db->distinct();
            $this->db->from('expenditures ex');
            $this->db->join('grf_codes gr', 'gr.code=ex.exp_gfs_code_ref');
            $this->db->join('department de', 'de.dept_id=ex.exp_dept_ref');
            //$this->db->join('source_fund so','so.source_fund_id=ex.exp_src_fund_ref');
            $this->db->join('activities ac', 'ac.activity_id=ex.exp_act_ref');
            $this->db->join('budget bu', 'bu.budget_id=ex.exp_bgt_ref');
            $this->db->where('ex.exp_date >=', $start_date);
            $this->db->where('ex.exp_date <=', $end_date);
            //$this->db->where("ex.exp_date BETWEEN $startDate AND $endDate");     
            $query = $this->db->get();
            //echo $this->db->last_query();
            return $query->result_array();
        }
    }

    public function m_count_dokezo() {
        $this->db->select('*');
        $this->db->from('request');
        //$this->db->where($where);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function m_search_items($search_data) {


        $this->db->select('*');
        $this->db->from('existing_vehicles');
        //$this->db->order_by('ads_id','desc');
        //$this->db->join('existing_vehicles','existing_vehicles.num_plate=offence.off_plate');
        $this->db->join('car_owner', 'car_owner.owner_id=existing_vehicles.owner_id_');
        $this->db->join('vehicles', 'vehicles.car_id=existing_vehicles.car_id_ex');
        $this->db->like("num_plate", $search_data, 'both');
        $query = $this->db->get();
        return $query->result_array();
    }

}
