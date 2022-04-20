<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Gledgers
 *
 * @author ADE
 */
class Receivables extends CI_Model {

    private $start_date;
    private $end_date;

    public function __construct() {
        parent::__construct();
    }

    public function getDebtors() {
       $result=null;
        if (isset($_GET['start_date'])) {
            $this->start_date = $this->input->get('start_date');
            $this->end_date = $this->input->get('end_date');
            $json = file_get_contents($this->Helper->getConfigValue('EhmsUrl') . '/api/api.php?gacc=receivables&start_date=' . $this->start_date . '&end_date=' . $this->end_date);
            $result = json_decode($json);

          
        }

        return $result;
    }

}
