<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Activity extends CI_Controller {

    public function __construct() {
        parent::__construct();

        if (!isset($_SESSION['userinfo'])) {
            redirect('account/login');
        }

        //if(!isset( $_SESSION['sess_id'])){redirect('Chop_login/logout');} 
        $this->load->model('Activity_model');
        $this->load->model('Chop_model');
    }

    public function reg_activity() {
        //$show1['data2']=$this->Trlots_model->m_num_offence();
        $show['val1'] = $this->Activity_model->m_show_yoa();
        $show['val3'] = $this->Chop_model->m_show_dept();

        $count['num'] = $this->Chop_model->m_count_dokezo();
        $this->load->view('shared/header');
        $this->load->view('activity/reg_activity', $show);
        $this->load->view('shared/footer');
    }

    public function reg_activity_action() {
        $this->Activity_model->m_reg_activity_action();
        redirect('activity/reg_activity');
    }

    public function show_reg_activity() {
        $data = $this->Activity_model->m_show_reg_activity();
        if (!$data) {
            echo "<center><h2 style='color:red'>No activity found!</h2></center>";
        } else {
            echo '
      <table style="background-color:white" class="table  table-bordered table-striped table-hover">
           <tr>
            <thead>
            <th>Activity code</th>
            <th>Activity name</th>
            <th>Year</th>
            </thead>
           </tr>
          <tbody>
          ';
            foreach ($data as $view) {

                echo "
                        <tr><td>" . $view['activity_id'] . "</td>
                        <td>" . $view['activity_name'] . "</td>
                        <td>" . $view['budget_year'] . "</td>
                        
                        </tr>";
            }
            echo "        
          </tbody>
          </table>
          ";
        }
    }

    public function upload_activities() {
        $filename = $_FILES["csv_act"]["tmp_name"];
        if ($_FILES["csv_act"]["size"] > 0) {
            $file = fopen($filename, "r");
            while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE) {

                //checking for duplication
                $check = $emapData[2];
                $count_act = $this->Activity_model->check_dep_act($check);
                if (!$count_act) {

                    redirect('activity/reg_activity?dept');
                } else {

                    $data = array(
                        'activity_name' => $emapData[0],
                        'budget_year' => $emapData[1],
                        'dept_ref' => $emapData[2]
                    );

                    $this->Activity_model->activity_insertCSV($data);
                }
            }
            fclose($file);
        }
        redirect('activity/reg_activity');
    }

}
