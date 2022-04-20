<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class GfsCode extends CI_Controller {

    public function __construct() {
        parent::__construct();

        if (!isset($_SESSION['userinfo'])) {
            redirect('account/login');
        }

        $this->load->model('Budget_model');
        $this->load->model('Chop_model');
        $this->load->model('GfsCode_model');
    }

    public function gfs_code_reg() {
        $count['num'] = $this->Chop_model->m_count_dokezo();
        $this->load->view('shared/header');
        $this->load->view('gfscode/gfs_code_reg');
        $this->load->view('shared/footer');
    }

    public function gfs_code_action() {
        $check = $this->input->post('gfs_code');
        $count = $this->GfsCode_model->check_gfs_dup($check);
        if ($count > 0) {
            redirect('GfsCode/gfs_code_reg?error');
        } else {
            $this->GfsCode_model->m_gfs_code_action();
        }
        redirect('GfsCode/gfs_code_reg');
    }

    public function gfs_excel() {
        $filename = $_FILES["csv"]["tmp_name"];
        if ($_FILES["csv"]["size"] > 0) {
            $file = fopen($filename, "r");
            while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE) {

                //checking for duplication
                $check = $emapData[0];
                $count = $this->GfsCode_model->check_gfs_dup($check);
                if ($count > 0) {

                    redirect('GfsCode/gfs_code_reg?error');
                } else {

                    $data = array(
                        'code' => $emapData[0],
                        'grf_desc' => $emapData[1],
                        'unit' => $emapData[2]
                    );

                    $this->GfsCode_model->gfs_insertCSV($data);
                }
            }
            fclose($file);
        }


        redirect('GfsCode/gfs_code_reg?sent');
    }

}
