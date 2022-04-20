<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Settings
 *
 * @author ADE
 */
class Settings extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function addCurrency() {
        $currency_name = $this->input->post('currency_name');
        $currency_code = $this->input->post('currency_code');
        $currency_symbol = $this->input->post('currency_symbol');
        $exch_rate = $this->input->post('exch_rate');
        $hudress_name = $this->input->post('hudress_name');
        $data = array(
            'currency_name' => $currency_name,
            'currency_code' => $currency_code,
            'currency_symbol' => $currency_symbol,
            'exchange_rate' => $exch_rate,
            'hundredsname' => $hudress_name
        );
        $rs = $this->db->insert('tbl_currency', $data);

        if ($rs) {
            return 'success';
        } else {
            return $this->db->error()['code'];
        }
    }

    public function updateSystemParams() {
        foreach ($_POST as $key => $value) {
            if ($key != 'submit') {
                // echo $key.' '.$value.'<br/>';
                $sql = "UPDATE tbl_config SET config_value='$value' WHERE config_name='$key'";
                $this->db->query($sql);
            }
        }
    }

    function getCurrentList($limit, $start) {
        $this->db->limit($limit, $start);
        $query = $this->db->get("tbl_currency");

        if ($query->num_rows() > 0) {

            return $query->result();
        }

        return false;
    }

    function getSystemParamsList() {
        $query = $this->db->get("tbl_config");

        if ($query->num_rows() > 0) {

            return $query->result();
        }

        return false;
    }

    public function addGroup() {
        $group_name = $this->input->post('group_name');
        $sec_id = $this->input->post('section_id');
        $data = array(
            'group_name' => $group_name,
            'sec_id' => $sec_id
        );
        $rs = $this->db->insert('tbl_acc_group', $data);

        if ($rs) {
            return 'success';
        } else {
            return $this->db->error()['code'];
        }
    }

    public function addMainAcc() {
        $mainAcc_name = $this->input->post('mainAcc_name');
        $group_id = $this->input->post('group_id');
        $data = array(
            'main_acc_name' => $mainAcc_name,
            'group_id' => $group_id
        );
        $rs = $this->db->insert('tbl_main_account', $data);

        if ($rs) {
            return 'success';
        } else {
            return $this->db->error()['code'];
        }
    }

    public function addAcc() {
        $Acc_name = $this->input->post('Acc_name');
        $mainAcc_id = $this->input->post('mainAcc_id');
        $data = array(
            'acc_name' => $Acc_name,
            'main_acc_id' => $mainAcc_id
        );
        $rs = $this->db->insert('tbl_accounts', $data);

        if ($rs) {
            return 'success';
        } else {
            return $this->db->error()['code'];
        }
    }

}
