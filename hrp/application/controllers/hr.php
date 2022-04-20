<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Hr extends CI_Controller {

    var $basic_data = array();

    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->helper('url');
        $this->load->model('hr_model', 'HR');


        if ($this->session->userdata('loggedin')!='TRUE'|| empty($this->session->userdata('loggedin'))) {
            //redirect them to the login page
            redirect('auth/login', 'refresh');
        }


    }

    function randf() {
        // $employee = 


        $employee = $this->db->query("SELECT * from employee")->result();
        foreach ($employee as $key => $value) {



            $array = array(
                'Employee' => $value->id,
                'Amount' => (round(rand(400000, 4000000) / 1000)) * 1000,
                'SalaryGrade' => rand(1, 3),
            );
            $insert = $this->HR->addsalary($array);
        }
    }

    function index() {

        $this->data['leo'] = 1;
        $this->data['graph'] = array(
            'data_url_gender' => $this->config->item('base_url') . 'index.php/hr/graph_by_gender',
            'data_url_department' => $this->config->item('base_url') . 'index.php/hr/graph_by_department',
            'data_url_age' => $this->config->item('base_url') . 'index.php/hr/graph_by_age',
            'data_url_education' => $this->config->item('base_url') . 'index.php/hr/graph_by_education',
            'data_url' => $this->config->item('base_url') . 'index.php/hr/graph_by_gender',
        );

        $this->data['content'] = 'hr/welcome';
        $this->load->view('hr/template', $this->data);
    }

    function exportreport() {
        $export = array();
        if (count($_POST) > 0) {
            foreach ($_POST as $key => $value) {
                if ($value != '') {
                    $export[$key] = $value;
                }
            }
        }

        $available = $this->HR->exportdata($export);
        include 'hr_report.php';
        exit;
        redirect('hr/report', 'refresh');
    }

    function report() {
        $search = array();
        if (isset($_POST['Search'])) {
            $search['FirstName'] = trim($this->input->post('fname'));
            $search['LastName'] = trim($this->input->post('lname'));
            $search['EmployeeId'] = trim($this->input->post('employee'));
            $search['WorkStation'] = trim($this->input->post('station'));
            $search['Sex'] = trim($this->input->post('sex'));
            $search['Position'] = trim($this->input->post('position'));
            $search['Department'] = trim($this->input->post('department'));
            $search['Retere'] = trim($this->input->post('status'));
            if ($search['Retere'] == 1) {
                $search['Retere'] = 0;
            } else if ($search['Retere'] == 3) {
                $search['Retere'] = 1;
            } else {
                unset($search['Retere']);
            }
        }
        // echo $search['Retere'];
        $config["base_url"] = base_url() . "index.php/hr/report";
        $config["total_rows"] = $this->HR->record_count('employee_view');
        $config["per_page"] = 30;
        $config["uri_segment"] = 3;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->data['links'] = $this->pagination->create_links();

        $this->data['searchingdata'] = $search;

        $this->data['employeelist'] = $this->HR->employee($search, $config["per_page"], $page);
        $this->data['employeetotalnumber'] = $this->HR->record_count('employee_view');
        $this->data['station'] = $this->HR->workstation();
        $this->data['department'] = $this->HR->department();
        $this->data['position'] = $this->HR->position();
        $this->data['content'] = 'hr/employee/report';
        $this->load->view('hr/template', $this->data);
    }

    function graph_by_gender() {
        include APPPATH . '/libraries/charts.php';
        $title = new title('Gender Distribution');
        $data = $this->HR->gendergraph();
        $male = (double) $data['Male'];
        $female = (double) $data['Female'];
        $other = (double) $data['Unknown'];
        $pie = new pie(100);
        $pie->set_alpha(0.6);
        $pie->set_start_angle(10);
        $pie->add_animation(new pie_fade());
        $pie->set_tooltip('#val# of #total#<br>#percent# of 100%');
        $pie->set_colours(array('#1C9E05', '#FF368D', '#126784'));
        $pie->set_values(array(new pie_value($male, "Male (" . $male . ")"), new pie_value($female, "Female (" . $female . ")"), new pie_value($other, "Unknown (" . $other . ")")));

        $chart = new open_flash_chart();
        $chart->set_title($title);
        $chart->add_element($pie);
        echo $chart->toPrettyString();
    }

    function graph_by_education() {
        include APPPATH . '/libraries/charts.php';
        $data = $this->HR->educationgraph();
        $title = new title('Education Level');

        $bar = new bar_glass();
        $bar->set_values(array_values($data));
        $bar->set_on_show(new bar_on_show('grow-up', 2.5, 0));

        $bar->set_tooltip("Total: #val#");
        $y_axis = new y_axis();

        if (max($data) > 10) {
            $y_axis->set_range(0, max(array_values($data)), 2);
        } else {
            $y_axis->set_range(0, 10, 2);
        }

        $x_axis = new x_axis();
        $x_labels = new x_axis_labels();

        $x_labels->set_labels(array_keys($data));
        $x_labels->rotate(-45);
        $x_axis->set_labels($x_labels);


        $chart = new open_flash_chart();
        $chart->set_title($title);
        $chart->add_element($bar);
        $chart->add_y_axis($y_axis);
        $chart->set_x_axis($x_axis);

        echo $chart->toPrettyString();
    }

    function graph_by_department() {
        include APPPATH . '/libraries/charts.php';
        $data = $this->HR->departmentgraph();
        $title = new title('Department Distribution');

        $bar = new bar_glass();
        $bar->set_values(array_values($data));
        $bar->set_on_show(new bar_on_show('grow-up', 2.5, 0));

        $bar->set_tooltip("Total: #val#");
        $y_axis = new y_axis();

        if (max($data) > 10) {
            $y_axis->set_range(0, max(array_values($data)), 2);
        } else {
            $y_axis->set_range(0, 10, 2);
        }

        $x_axis = new x_axis();
        $x_labels = new x_axis_labels();

        $x_labels->set_labels(array_keys($data));
        $x_labels->rotate(-45);
        $x_axis->set_labels($x_labels);


        $chart = new open_flash_chart();
        $chart->set_title($title);
        $chart->add_element($bar);
        $chart->add_y_axis($y_axis);
        $chart->set_x_axis($x_axis);

        echo $chart->toPrettyString();
    }

    function graph_by_age() {
        include APPPATH . '/libraries/charts.php';
        $data = $this->HR->agegraph();
        $title = new title('Age Distribution');

        $bar = new bar_glass ();
        $bar->set_values(array_values($data));
        $bar->set_on_show(new bar_on_show('grow-up', 2.5, 0));

        $bar->set_tooltip("Total: #val#");
        $y_axis = new y_axis();

        if (max($data) > 10) {
            $y_axis->set_range(0, max(array_values($data)), 2);
        } else {
            $y_axis->set_range(0, 10, 2);
        }

        $x_axis = new x_axis();
        $x_labels = new x_axis_labels();

        $x_labels->set_labels(array_keys($data));
        $x_labels->rotate(-45);
        $x_axis->set_labels($x_labels);


        $chart = new open_flash_chart();
        $chart->set_title($title);
        $chart->add_element($bar);
        $chart->add_y_axis($y_axis);
        $chart->set_x_axis($x_axis);

        echo $chart->toPrettyString();
    }

    #upload file

    function upload_file($array, $name, $folder) {
        $filename = time() . $array[$name]['name'];

        $path = './uploads/' . $folder . '/';
        $path1 = './uploads/' . $folder . '/';
        $path = $path . basename($filename);

        if (move_uploaded_file($_FILES[$name]['tmp_name'], $path)) {
            // chmod($path1.$filename, 777);
            return $filename;
        } else {
            return 0;
        }
    }

    function getExtension($str) {
        $i = strrpos($str, ".");
        if (!$i) {
            return "";
        }
        $l = strlen($str) - $i;
        $ext = substr($str, $i + 1, $l);
        return $ext;
    }

    function draw_kpi_graph($category, $deprtment, $indicator=null, $status_type=null) {
        include APPPATH . '/libraries/charts.php';
        $start_date = $this->session->userdata('from_date');
        $end_date = $this->session->userdata('to_date');
        $data = $this->HR->kpi_general_report($start_date, $end_date, $category, $indicator);


        if ($indicator == null) {

            $chart = new open_flash_chart();

            $max = 0;
            $cat = $this->HR->kpicategorylist($category);
            $deprt = $this->HR->department($deprtment);
            $title = new title($cat[0]->name . ' -  Indicators');
            $label = array();
            $status = $this->HR->kpistatuslist();

            $main = array();





            foreach ($data[$category] as $key => $value) {
                $indicator_n = $this->HR->kpi_indicator_list($key);
                $label[] = $indicator_n[0]->name;




                foreach ($status as $xx => $yy) {
                    $main[$yy->name][] = (int) $value[$yy->name];


                    if ($max < 10) {
                        $max = $value[$yy->name];
                    } else if ($value[$yy->name] > $max) {
                        $max = $value[$yy->name];
                    }
                }
            }




            $x = 0;


            $i = 0;
            $p = 0;
            foreach ($main as $aa => $bb) {
                $color = get_color_bystatus($aa);

                $bar = new bar_glass ();
                $bar->set_colour($color);
                $bar->set_key($aa, 15);

                $bar->set_values($bb);
                $bar->set_on_show(new bar_on_show('grow-up', 2.5, 0));
                $bar->set_tooltip($aa . " =  #val#");

                $chart->add_element($bar);
            }



            $y_axis = new y_axis();

            if ($max > 10) {
                $y_axis->set_range(0, $max, 2);
            } else {
                $y_axis->set_range(0, 10, 2);
            }

            $x_axis = new x_axis();
            $x_labels = new x_axis_labels();

            $x_labels->set_labels($label);
            $x_labels->rotate(-45);
            $x_axis->set_labels($x_labels);

            $chart->set_title($title);

            $chart->add_y_axis($y_axis);
            $chart->set_x_axis($x_axis);

            echo $chart->toPrettyString();
        } else {


            $chart = new open_flash_chart();

            $max = 0;
            $cat = $this->HR->kpicategorylist($category);
            $in = $this->HR->kpi_indicator_list($indicator);
            $status_name = $this->HR->kpistatuslist($status_type);
            $title = new title($in[0]->name . ' Indicator with Status &nbsp;' . $status_name[0]->name);
            $label = array();
            $status = $this->HR->kpistatuslist();

            $main = array();


            $employee_list = $this->db->get_where('employee_view', array('Department' => $category))->result();
//            
//            echo '<pre>';
//            print_r($employee_list);
//            echo '</pre>';
//            
            foreach ($employee_list as $key => $value) {
                $label[] = $value->FirstName . ' ' . $value->LastName . ' (' . $value->EmployeeId . ')';
                $count_data = $this->db->query("SELECT COUNT(kpi_value) as kpi_value FROM kpi_record WHERE date >= '$start_date' AND employee_auto=" . $value->id . " AND date <= '$end_date' AND kpi_category=" . $category . " AND kpi_indicator=" . $indicator . " AND kpi_value=" . $status_type)->result();
                $main[] = (int) $count_data[0]->kpi_value;
            }


            if ($max < 10) {
                $max = 10;
            } else if (max($main) > $max) {
                $max = max($main);
            }


            //foreach ($main as $aa => $bb) {
            $color = get_color_bystatus($status_name[0]->name);

            $bar = new bar_glass ();
            $bar->set_colour($color);
            $bar->set_values($main);
            $bar->set_key($status_name[0]->name, 15);
            $bar->set_on_show(new bar_on_show('grow-up', 2.5, 0));
            $bar->set_tooltip($status_name[0]->name . " = #val#");

            $x = 0;

            $chart->add_element($bar);
            //$chart->add_element( $tags );
            //}



            $y_axis = new y_axis();

            if ($max > 10) {
                $y_axis->set_range(0, $max, 2);
            } else {
                $y_axis->set_range(0, 10, 2);
            }

            $x_axis = new x_axis();



            $x_labels = new x_axis_labels();

            $x_labels->set_labels($label);
            $x_labels->rotate(-45);
            $x_axis->set_labels($x_labels);

            $chart->set_title($title);

            $chart->add_y_axis($y_axis);
            $chart->set_x_axis($x_axis);

            echo $chart->toPrettyString();
        }
    }

    function kpireport($category=null, $indicator=null, $raw=null) {

        if ($indicator == 0) {
            $indicator = null;
        }

        if (!$this->input->post('set_date_range') && !$this->session->userdata('from_date')) {
            $week_number = date("W");
            $current_year = date('Y');

            $day = 1;
            $start_date = date('Y-m-d', strtotime($current_year . "W" . $week_number . $day));

            $day = 7;
            $end_date = date('Y-m-d', strtotime($current_year . "W" . $week_number . $day));

            $this->session->set_userdata('from_date', $start_date);
            $this->session->set_userdata('to_date', $end_date);
        } else if ($this->input->post('set_date_range')) {
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            $this->form_validation->set_rules('from_date', 'From Date', 'xss_clean|required|callback_validate_date');
            $this->form_validation->set_rules('to_date', 'Up to ', 'xss_clean|required|callback_validate_date');

            if ($this->form_validation->run() == TRUE) {
                $start_date = trim($this->input->post('from_date'));
                $end_date = trim($this->input->post('to_date'));
                if ($end_date < $start_date) {
                    $this->data['error_in'] = 'From date value should be less than Up to date value';
                } else {

                    $this->session->set_userdata('from_date', $start_date);
                    $this->session->set_userdata('to_date', $end_date);
                }
            }
        }

        $start_date = $this->session->userdata('from_date');
        $end_date = $this->session->userdata('to_date');

        $this->data['report'] = $this->HR->kpi_general_report($start_date, $end_date, $category, $indicator);

        if ($category != null && ($indicator == null || $indicator != null)) {
            $this->data['show_graph'] = 1;
            $this->data['main_cat'] = $category;
            if ($indicator == 0 || $indicator == null) {
                $this->data['main_cat'].='/0';
            } else {
                $this->data['main_cat'].='/' . $indicator;
            }
            $this->data['ind_ind'] = $indicator;
            $dep = $this->HR->department();
            if ($raw != null) {
                $this->data['report_raw'] = $this->HR->kpi_general_report_raw($start_date, $end_date, $category, $raw, $indicator);
                $this->data['raw'] = $raw;
                $this->data['ind_cat'] = $category;
                $this->data['ind_ind'] = $indicator;
            }




            $url = $this->config->item('base_url') . 'index.php/hr/draw_kpi_graph/' . $category . '/' . $category;
            if ($indicator != null) {
                $url.='/' . $indicator;
                $this->data['show_3_graph'] = 1;
            }

            $this->data['graph_path'] = $url;

            //$this->draw_kpi_graph($this->data['report'],$category,$indicator);
            //echo $this->data['gf'] = $this->draw_kpi_graph();
        }

        $this->data['content'] = 'hr/kpi/kpireport_graph';
        $this->load->view('hr/template', $this->data);
    }

    
    
    
    
    
    function trainingreport() {

        $search = array();
        if (isset($_POST['Search'])) {
            $search['startdate'] = trim($this->input->post('fromdate'));
            $search['enddate'] = trim($this->input->post('todate'));
            $search['Employee'] = trim($this->input->post('employee'));
            $search['trainingtype'] = trim($this->input->post('station'));
            $search['is_approved'] = trim($this->input->post('status'));
        }
        // echo $search['Retere'];
        $config["base_url"] = base_url() . "index.php/hr/trainingreport";
        $config["total_rows"] = $this->HR->record_count('training');
        $config["per_page"] = 30;
        $config["uri_segment"] = 3;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->data['links'] = $this->pagination->create_links();

        $this->data['searchingdata'] = $search;

        $this->data['employeelist'] = $this->HR->traininglist($search, $config["per_page"], $page);
        $this->data['station'] = $this->HR->trainingtype();
        $this->data['content'] = 'hr/training/trainingreport';
        $this->load->view('hr/template', $this->data);
    }

    
    
    
    
    

    function trainingtype($id=null){
         $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->form_validation->set_rules('name', 'Leave Type', 'xss_clean|required');
        $this->form_validation->set_rules('comment', 'Leave Type', 'xss_clean');
        if ($this->form_validation->run()) {
            $array = array(
                'Name' => trim($this->input->post('name')),
                'Comment' => trim($this->input->post('comment')),
            );
            $insert = $this->HR->addtrainingtype($array, $id);
            if ($insert == 1) {
                $this->session->set_flashdata('message', 'Leave Type  recorded');
                redirect('hr/trainingtype/', 'refresh');
            } else {
                $this->data['error_in'] = "Record Failed to save. Check if is duplicate Name";
            }
        }
        if ($id != null) {
            $this->data['departmentdata'] = $this->HR->trainingtype($id);
            $this->data['id'] = $id;
        }
        $this->data['department'] = $this->HR->trainingtype();
        $this->data['content'] = 'hr/training/trainingtype';
        $this->load->view('hr/template', $this->data);
    }

    
    function add_to_training($id=null){
         $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->form_validation->set_rules('name', 'Employee ID', 'xss_clean|required');
        $this->form_validation->set_rules('trainingtype', 'Training Type', 'xss_clean|required');
        $this->form_validation->set_rules('fromdate', 'From Date', 'xss_clean|required|callback_validate_date');
        $this->form_validation->set_rules('todate', 'To Date', 'xss_clean|required|callback_validate_date');
        if ($this->form_validation->run()) {

           $array = array(
                'Employee' => trim($this->input->post('name')),
                'trainingtype' => trim($this->input->post('trainingtype')),
                'startdate' => trim($this->input->post('fromdate')),
                'enddate' => trim($this->input->post('todate')),
                 
            );

            $employee = check_employee_number($array['Employee']);
            if (count($employee) == 1) {
                
                $array ['employee_auto'] = $employee[0]->id;
                  if( $array['enddate'] < $array['startdate']){
                      $this->data['error_in'] = 'End date should be less than start date !!';
                  }else{
                $insert = $this->HR->assigntraining($array, $id);
                   if ($insert == 1) {
                     $this->session->set_flashdata('message', 'Training Information Recorded');
                     redirect('hr/add_to_training/', 'refresh');
                } else if ($insert == 2) {
                    $this->data['error_in'] = "Duplicate data !!!!";
                }
                  }
            } else {
                $this->data['error_in'] = 'Employee ID does not exist !!';
            }
        }

        if ($id != null) {
            $this->data['id'] = $id;
            $this->data['traininginfo'] = $this->HR->traininghistory($id);
        }
        $this->data['trainingtype'] = $this->HR->trainingtype();
        $this->data['content'] = 'hr/training/addtotraining';
        $this->load->view('hr/template', $this->data);   
    }

    function rosterreport(){
        
        $search = array();
        if (isset($_POST['Search'])) {
            $search['Fromdate'] = trim($this->input->post('fromdate'));
            $search['Todate'] = trim($this->input->post('todate'));
            $search['Employee'] = trim($this->input->post('employee'));
            $search['LeaveType'] = trim($this->input->post('station'));
        }
        // echo $search['Retere'];
        $config["base_url"] = base_url() . "index.php/hr/rosterreport";
        $config["total_rows"] = $this->HR->record_count('leaveroster');
        $config["per_page"] = 30;
        $config["uri_segment"] = 3;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->data['links'] = $this->pagination->create_links();

        $this->data['searchingdata'] = $search;

        $this->data['employeelist'] = $this->HR->leavelist_roster($search, $config["per_page"], $page);
        $this->data['station'] = $this->HR->leavetype();
        
        $this->data['content'] = 'hr/leave/roster_report';
        $this->load->view('hr/template', $this->data);    
    }
    
    
    
    function addroster($id=null) {
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('name', 'Employee ID', 'xss_clean|required');
        $this->form_validation->set_rules('leavetype', 'Leave Type', 'xss_clean|required');
        $this->form_validation->set_rules('fromdate', 'From Date', 'xss_clean|required|callback_validate_date');
        $this->form_validation->set_rules('todate', 'To Date', 'xss_clean|required|callback_validate_date');
        if ($this->form_validation->run()) {
             $from = strtotime(trim($this->input->post('fromdate')));
            $upto = strtotime(trim($this->input->post('todate')));
            
            if($upto < $from){
                $this->data['error_in']=' End date should be greater than start date';
            }else{
            $diff = $upto - $from;

            $days_dif =  round($diff / (60*60*24));
            
            $leavet = trim($this->input->post('leavetype'));
            $from_leave_type = $this->HR->leavetype($leavet);
           $required_leave_days = $from_leave_type[0]->Days;
           
            $array = array(
                'Employee' => trim($this->input->post('name')),
                'LeaveType' => trim($this->input->post('leavetype')),
                'Fromdate' => trim($this->input->post('fromdate')),
                'Todate' => trim($this->input->post('todate')),
            );

            $employee = check_employee_number($array['Employee']);
            if (count($employee) == 1) {
                if($required_leave_days > 0){
                if($required_leave_days < $days_dif){
                   
                    $this->data['error_in']=' Only '.$required_leave_days.' days is maximum for the selected leave type';
                }else{
                    
                 $insert = $this->HR->assignleave_roster($array, $id);
                if ($insert == 1) {
                    $this->session->set_flashdata('message', 'Leave added in Roster successfully !! ');
                    redirect('hr/addroster/', 'refresh');
                } else if ($insert == 2) {
                    $this->data['error_in'] = "Employee has leave already added in Roster.";
                }    
                }
            }else{
                $insert = $this->HR->assignleave_roster($array, $id);
                if ($insert == 1) {
                    $this->session->set_flashdata('message', 'Leave added in Roster successfully !! ');
                    redirect('hr/addroster/', 'refresh');
                } else if ($insert == 2) {
                    $this->data['error_in'] = "Employee has leave already added in Roster.";
                }
            }
                
            } else {
                $this->data['error_in'] = 'Employee ID does not exist !!';
            }
            }
        }

        if ($id != null) {
            $this->data['id'] = $id;
            $this->data['leaveinfo'] = $this->HR->leavehistory_roster($id);
        }
        $this->data['leavetype'] = $this->HR->leavetype();
        $this->data['content'] = 'hr/leave/addroster';
        $this->load->view('hr/template', $this->data);
    }

    function kpireportraw() {
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('employee', 'Employee ID', 'xss_clean|required');
        $this->form_validation->set_rules('date', 'Date', 'xss_clean|required|callback_validate_date');
        $this->form_validation->set_rules('update', 'Up to ', 'xss_clean|required|callback_validate_date');

        if ($this->form_validation->run() == TRUE) {
            $array = array(
                'Employee' => trim($this->input->post('employee')),
                'date' => trim($this->input->post('date')),
                'update' => trim($this->input->post('update'))
            );

            $check_id = check_employee_number($array['Employee']);
            if (count($check_id) == 1) {
                $avaible = $this->HR->getkpi_raw_data($array);

                //echo '<pre>';
                //print_r($avaible);
                //echo '</pre>';


                $this->data['report'] = $avaible;
                $this->data['id'] = $check_id[0]->id;
                $this->data['from'] = $array['date'];
                $this->data['to'] = $array['update'];
            } else {
                $this->data['error_in'] = "Employee Number doesn't exist !!";
            }
        }
        $this->data['content'] = 'hr/kpi/kpireport_raw';
        $this->load->view('hr/template', $this->data);
    }

    function kpiemployee() {
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('employee', 'Employee ID', 'xss_clean|required');


        if ($this->form_validation->run() == TRUE) {
            $employee_ID = trim($this->input->post('employee'));
            $check_id = check_employee_number($employee_ID);

            if (count($check_id) == 1) {
                $this->data['id'] = $check_id[0]->id;
                $dept = $this->db->get_where('employee_view', array('id' => $check_id[0]->id))->result();
                if (count($dept) > 0) {
                    $this->data['kpicategory'] = $this->HR->kpicategorylist($dept[0]->Department);
                } else {
                    $this->data['kpicategory'] = array();
                }
                $this->data['employee'] = $employee_ID;

                if ($this->input->post('record_kpi')) {
                    $get_indicators = $this->input->post('indicators');
                    if ($this->input->post('indicators')) {
                        $this->db->delete('kpi_employee', array('employee_auto' => $check_id[0]->id, 'Employee' => $employee_ID));

                        foreach ($get_indicators as $key => $value) {
                            $insert_data = array(
                                'employee_auto' => $check_id[0]->id,
                                'Employee' => $employee_ID,
                                'date_recorded' => date('Y-m-d'),
                                'recorded_by' => $this->session->userdata('user_id'),
                                'kpi_indicator' => $value,
                                'kpi_category' => $dept[0]->Department
                            );

                            $this->db->insert('kpi_employee', $insert_data);
                        }
                        $this->data['error_in'] = "Employee assigned Indicators successfully !!";
                    } else {
                        $this->data['error_in'] = 'Please select at least one Indicator';
                    }
                }

                $kp = $this->db->get_where('kpi_employee', array('Employee' => $employee_ID, 'employee_auto' => $check_id[0]->id))->result();
                $ind = array();
                foreach ($kp as $kp_key => $kp_value) {
                    $ind[$kp_value->kpi_indicator] = $kp_value->kpi_indicator;
                }

                $this->data['assigned_kpi'] = $ind;
            }
        }
        $this->data['content'] = 'hr/kpi/kpiemployee';
        $this->load->view('hr/template', $this->data);
    }

    function kpilist($id=null) {
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('name', 'Indicator Name', 'xss_clean|required');

        if ($this->form_validation->run() == TRUE) {
            $array = array(
                'name' => trim($this->input->post('name')),
            );
            $insert = $this->HR->add_kpi_indicator($array, $id);
            if ($insert == 1) {
                $this->session->set_flashdata('message', 'KPI Indicator  saved successfully !!');
                redirect('hr/kpilist', 'refresh');
            } else {
                $this->data['error_in'] = 'Duplicate/Fail to save the KPI Indicator Informations';
            }
        }

        if ($id != null) {
            $this->data['id'] = $id;
            $this->data['kpikeydata'] = $this->HR->kpi_indicator_list($id);
        }
        $this->data['kpi_list'] = $this->HR->kpi_indicator_list();
        $this->data['content'] = 'hr/kpi/kpi_list';
        $this->load->view('hr/template', $this->data);
    }

    /*
     *   This function is not used anymore
     * 
      function assignkpi_employee() {
      $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
      $this->form_validation->set_rules('employee', 'Employee ID', 'xss_clean|required');
      $this->form_validation->set_rules('date', 'Date', 'xss_clean|required|callback_validate_date');
      $this->data['kpistatus'] = $this->HR->kpistatuslist();


      if ($this->form_validation->run() == TRUE) {
      $employee_ID = trim($this->input->post('employee'));
      $date = trim($this->input->post('date'));
      $check_id = check_employee_number($employee_ID);

      if (count($check_id) == 1) {
      $this->data['id'] = $check_id[0]->id;
      $this->data['kpicategory'] = $this->db->query("SELECT DISTINCT kpi_category FROM kpi_employee WHERE Employee='$employee_ID' AND employee_auto='".$check_id[0]->id."'")->result();

      $this->data['employee'] = $employee_ID;
      $this->data['date'] = $date;
      $insert_data = array();

      foreach ($this->data['kpicategory'] as $key => $value) {
      $list = $this->db->query("SELECT i.id,i.name,kp.kpi_indicator FROM kpi_indicator as i, kpi_employee as kp WHERE employee_auto=".$check_id[0]->id."  AND Employee='$employee_ID' AND i.id=kp.kpi_indicator ORDER BY id ASC ")->result();

      if (count($list) > 0) {
      foreach ($list as $k => $v) {
      $this->form_validation->set_rules('indicator_' . $v->id, $v->name, 'xss_clean');
      if ($this->input->post('indicator_' . $v->id)) {
      $insert_data[] = array(
      'employee_auto' => $check_id[0]->id,
      'Employee' => $employee_ID,
      'date' => $date,
      'date_recorded' => date('Y-m-d'),
      'recorded_by' => $this->session->userdata('user_id'),
      'kpi_indicator' => $v->id,
      'kpi_category' => $value->kpi_category,
      'kpi_value' => $this->input->post('indicator_' . $v->id),
      );
      }
      if (!$this->input->post('record_kpi')) {
      $exist_kpi = $this->HR->get_assignedkpi_employee(array('Employee' => $employee_ID, 'date' => $date, 'kpi_indicator' => $v->id));
      $_POST['indicator_' . $v->id] = ((count($exist_kpi) == 1 ) ? $exist_kpi[0]->kpi_value : '');
      }
      }
      }

      if ($this->form_validation->run() == TRUE) {
      if ($this->input->post('record_kpi')) {
      $insert_array = $this->HR->assignkpi_employee($insert_data);

      $this->data['error_in'] = 'KPIs data recorded successfully !!';
      }
      }
      }
      } else {
      $this->data['error_in'] = "Employee ID doesnot exist";
      }
      }


      $this->data['content'] = 'hr/kpi/assignkpi_employee';
      $this->load->view('hr/template', $this->data);
      }



     */

    function assignkpi() {
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('employee', 'Employee ID', 'xss_clean|required');
        $this->form_validation->set_rules('date', 'Date', 'xss_clean|required|callback_validate_date');
        $this->data['kpistatus'] = $this->HR->kpistatuslist();


        if ($this->form_validation->run() == TRUE) {
            $employee_ID = trim($this->input->post('employee'));
            $date = trim($this->input->post('date'));
            $check_id = check_employee_number($employee_ID);

            if (count($check_id) == 1) {
                $this->data['id'] = $check_id[0]->id;
                $this->data['employee'] = $employee_ID;
                $get_employee_info = $this->db->get_where('employee_view', array('id' => $check_id[0]->id, 'EmployeeId' => $employee_ID))->result();
                $this->data['date'] = $date;
                if ($get_employee_info[0]->Position > 0) {
                    $this->data['category'] = $this->HR->kpicategorylist($get_employee_info[0]->Position);
                    $this->data['kpi_cat_list'] = $this->HR->kpicategory_indicator(null, $get_employee_info[0]->Position);
                } else {
                    $this->data['category'] = array();
                }

                $insert_data = array();

                if ($this->input->post('record_kpi')) {
                    foreach ($this->data['kpi_cat_list'] as $key => $value) {
                        $this->form_validation->set_rules('indicator_' . $value->kpi_indicator, 'Status', 'xss_clean|required');
                        $insert_data[] = array(
                            'employee_auto' => $check_id[0]->id,
                            'Employee' => $employee_ID,
                            'date' => $date,
                            'date_recorded' => date('Y-m-d'),
                            'recorded_by' => $this->session->userdata('user_id'),
                            'kpi_indicator' => $value->kpi_indicator,
                            'kpi_category' => $get_employee_info[0]->Position,
                            'kpi_value' => $this->input->post('indicator_' . $value->kpi_indicator)
                        );
                    }


                    if ($this->form_validation->run()) {
                        // print_r($insert_data);
                        $this->HR->assignkpi($insert_data);
                        $this->data['error_found'] = 'KPI for Employee ID : <b>' . $employee_ID . '</b> recorded successfully !!';
                    }
                }

                $assigned_kpi = $this->HR->get_assignedkpi(array('Employee' => $employee_ID, 'employee_auto' => $check_id[0]->id, 'date' => $date));

                $get_assigned_kpi = array();

                foreach ($assigned_kpi as $key => $value) {
                    $get_assigned_kpi[$value->kpi_indicator] = $value->kpi_value;
                }

                $this->data['assigned_kpi'] = $get_assigned_kpi;
                /*   $insert_data[] = array(
                  'employee_auto' => $check_id[0]->id,
                  'Employee' => $employee_ID,
                  'date' => $date,
                  'date_recorded' => date('Y-m-d'),
                  'recorded_by' => $this->session->userdata('user_id'),
                  'kpi_indicator' => $v->id,
                  'kpi_category' => $value->kpi_category,
                  'kpi_value' => $this->input->post('indicator_' . $v->id),
                  ); */
            } else {
                $this->data['error_in'] = "Employee ID doesnot exist";
            }
        }


        $this->data['content'] = 'hr/kpi/assignkpi';
        $this->load->view('hr/template', $this->data);
    }

    function managekpi($id=null) {
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('category', 'KPI Category Name ', 'xss_clean|required');
        if ($this->form_validation->run() == TRUE && !$this->input->post('save_record')) {
            $category = $this->input->post('category');
            redirect('hr/managekpi/' . $category);
        }

        if ($id != null) {
            $this->data['id'] = $id;
            $this->data['kpikeydata'] = $this->HR->kpicategorylist($id);

            if ($this->input->post('save_record')) {
                if ($this->input->post('kpi_submited')) {
                    $submited_data = $this->input->post('kpi_submited');
                    $this->db->update('kpi_category_indicator', array('Active' => 0), array('kpi_category' => $id));

                    foreach ($submited_data as $key => $value) {
                        $array = array(
                            'kpi_category' => $id,
                            'kpi_indicator' => $value,
                        );
                        $insert = $this->HR->add_indicator_category($array);
                    }
                    $this->data['error_found'] = 'Selected Indicator(s) successfully assigned to :<b> ' . $this->data['kpikeydata'][0]->name . '</b>';
                } else {
                    $this->data['error_found'] = 'Selected at least one Indicator';
                }
            }
            $this->data['id'] = $id;
            $this->data['kpikeydata'] = $this->HR->kpicategorylist($id);
            $this->data['kpi_list'] = $this->HR->kpi_indicator_list();
            $kpi_assigned = $this->HR->kpicategory_indicator(null, $id);
            $kpi_assigned_array = array();
            foreach ($kpi_assigned as $key => $value) {
                $kpi_assigned_array[$value->kpi_indicator] = $value->kpi_indicator;
            }
            $this->data['assigned_kpi'] = $kpi_assigned_array;
        }


        $this->data['kpicategory'] = $this->HR->kpicategorylist();
        $this->data['content'] = 'hr/kpi/managekpi';
        $this->load->view('hr/template', $this->data);
    }

    function kpistatus($id=null) {
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('name', 'KPI Status Name', 'xss_clean|required');
        $this->form_validation->set_rules('point', 'KPI Point ', 'xss_clean|required|numeric');
        $this->form_validation->set_rules('color', 'Status Color ', 'xss_clean|required');

        if ($this->form_validation->run() == TRUE) {
            $array = array(
                'name' => trim($this->input->post('name')),
                'Point' => trim($this->input->post('point')),
                'Color' => trim($this->input->post('color'))
            );
            $insert = $this->HR->add_kpi_status($array, $id);
            if ($insert == 1) {
                $this->session->set_flashdata('message', 'KPI Status Information saved successfully !!');
                redirect('hr/kpistatus', 'refresh');
            } else {
                $this->data['error_in'] = 'Duplicate/Fail to save the KPI Status Informations';
            }
        }

        if ($id != null) {
            $this->data['id'] = $id;
            $this->data['kpistatusdata'] = $this->HR->kpistatuslist($id);
        }
        $this->data['kpistatuslist'] = $this->HR->kpistatuslist();
        $this->data['content'] = 'hr/kpi/kpistatus';
        $this->load->view('hr/template', $this->data);
    }

    function kpicategory($id=null) {
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('name', 'KPI Category Name', 'xss_clean|required');

        if ($this->form_validation->run() == TRUE) {
            $array = array('name' => trim($this->input->post('name')));
            $insert = $this->HR->add_kpi_category($array, $id);
            if ($insert == 1) {
                $this->session->set_flashdata('message', 'KPI Category Information saved successfully !!');
                redirect('hr/kpicategory', 'refresh');
            } else {
                $this->data['error_in'] = 'Duplicate/Fail to save the KPI Informations';
            }
        }

        if ($id != null) {
            $this->data['id'] = $id;
            $this->data['kpicategorydata'] = $this->HR->kpicategorylist($id);
        }
        $this->data['kpicategorylist'] = $this->HR->kpicategorylist();
        $this->data['content'] = 'hr/kpi/kpicategory';
        $this->load->view('hr/template', $this->data);
    }

    function candidates() {
        $search = array();
        $search_criteria = '';
        if (isset($_POST['Search'])) {
            $search['FirstName'] = trim($this->input->post('fname'));
            $search['LastName'] = trim($this->input->post('lname'));
            $search['EducationLevel'] = trim($this->input->post('education'));
            $search['status'] = trim($this->input->post('status'));
            $search['Sex'] = trim($this->input->post('sex'));
            $search_criteria = '?fname=' . $search['FirstName'];
        } else {
            $search['status'] = 0;
        }

        $this->data['search_c'] = $search_criteria;

        if ($this->input->post('change')) {
            $app_id = $this->input->post('app_id');
            $new_status = $this->input->post('new_status');
            $this->db->update('application', array('status' => $new_status), array('id' => $app_id));
        }

        $this->data['position'] = $this->HR->position();
        $this->data['educationlevel'] = $this->HR->educationlevel();
        $this->data['candidate_list'] = $this->HR->candidate_list($search);
        $this->data['content'] = 'hr/recruit/candidates';
        $this->load->view('hr/template', $this->data);
    }

    function employeelist_search($input=null) {
        if ($input != null) {
            $this->data['input'] = $input;
        }
        $search = array();
        if (isset($_POST['Search'])) {
            $search['FirstName'] = trim($this->input->post('fname'));
            $search['LastName'] = trim($this->input->post('lname'));
            $search['EmployeeId'] = trim($this->input->post('employee'));
            $search['WorkStation'] = trim($this->input->post('station'));
            $search['Sex'] = trim($this->input->post('sex'));
            $search['Position'] = trim($this->input->post('position'));
            $search['Department'] = trim($this->input->post('department'));
            $search['Retere'] = trim($this->input->post('status'));
            if ($search['Retere'] == 1) {
                $search['Retere'] = 0;
            } else if ($search['Retere'] == 3) {
                $search['Retere'] = 1;
            } else {
                unset($search['Retere']);
            }
        }
        $config["base_url"] = base_url() . "index.php/hr/employeelist_search/" . $input;
        $config["total_rows"] = $this->HR->record_count('employee_view');

        $config["per_page"] = 20;
        if ($input != null) {
            $config["uri_segment"] = 4;
        } else {
            $config["uri_segment"] = 3;
        }

        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);
        $this->pagination->initialize($config);

        if ($input != null) {
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        } else {
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        }



        $this->data['links'] = $this->pagination->create_links();
        $this->data['employeetotalnumber'] = $this->HR->record_count('employee');

        if (miltone_check("HR Manager") || miltone_check("admin")) {
            $this->data['department'] = $this->HR->department();
        } else {
            $this->data['department'] = $this->HR->department($this->session->userdata('department'));
            $search['Department'] = $this->session->userdata('department');
        }
        $this->data['employeelist'] = $this->HR->employee($search, $config["per_page"], $page);
        $this->data['station'] = $this->HR->workstation();


        $this->data['position'] = $this->HR->position();
        $this->load->view('hr/employee/employeelist_search', $this->data);
    }

    function employeelist() {
        $search = array();
        if (isset($_POST['Search'])) {
            $search['FirstName'] = trim($this->input->post('fname'));
            $search['LastName'] = trim($this->input->post('lname'));
            $search['EmployeeId'] = trim($this->input->post('employee'));
            $search['WorkStation'] = trim($this->input->post('station'));
            $search['Sex'] = trim($this->input->post('sex'));
            $search['Position'] = trim($this->input->post('position'));
            $search['Department'] = trim($this->input->post('department'));
            $search['Retere'] = trim($this->input->post('status'));
            if ($search['Retere'] == 1) {
                $search['Retere'] = 0;
            } else if ($search['Retere'] == 3) {
                $search['Retere'] = 1;
            } else {
                unset($search['Retere']);
            }
        }
        $config["base_url"] = base_url() . "index.php/hr/employeelist";
        $config["total_rows"] = $this->HR->record_count('employee_view');

        $config["per_page"] = 20;
        $config["uri_segment"] = 3;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->data['links'] = $this->pagination->create_links();
        $this->data['employeetotalnumber'] = $this->HR->record_count('employee');

        $this->data['employeelist'] = $this->HR->employee($search, $config["per_page"], $page);
        $this->data['station'] = $this->HR->workstation();
        $this->data['department'] = $this->HR->department();
        $this->data['position'] = $this->HR->position();
        $this->data['content'] = 'hr/employee/employeelist';
        $this->load->view('hr/template', $this->data);
    }

    function openvacancy($id=null) {
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('name', 'Job Title', 'xss_clean|required');
        $this->form_validation->set_rules('description', 'Job Description', 'xss_clean|required');
        $this->form_validation->set_rules('from_date', 'Open Date', 'xss_clean|required');
        $this->form_validation->set_rules('to_date', 'Expire Date', 'xss_clean|required');

        if ($id != null) {
            $this->data['id'] = $id;
            $this->data['vacancy'] = $this->HR->vacancy($id);
        }

        if ($this->form_validation->run() == TRUE) {

            $array = array(
                'Title' => rtrim($this->input->post('name')),
                'Description' => rtrim($this->input->post('description')),
                'from_date' => rtrim($this->input->post('from_date')),
                'to_date' => rtrim($this->input->post('to_date')),
            );


            if (isset($_FILES['attach']['name']) && $_FILES['attach']['name'] != '') {
                $file = $this->upload_file($_FILES, 'attach', 'vacancy');
                $array['Attach'] = $file;
            }

            $insert = $this->HR->add_vacancy($array, $id);

            if ($insert) {
                $this->session->set_flashdata('message', 'Information saved !!');
                redirect('hr/openvacancy/', 'refresh');
            } else {
                $this->data['error_in'] = 'Fail to save Informations !!';
            }
        }
        $this->data['vacancy_list'] = $this->HR->vacancy();
        $this->data['content'] = 'hr/recruit/vacancy';
        $this->load->view('hr/template', $this->data);
    }

    function leave($id) {
        $this->data['id'] = $id;
        $basic_info = $this->HR->personinfo($id);
        // echo $search['Retere'];
        $config["base_url"] = base_url() . "index.php/hr/leave";
        $config["total_rows"] = $this->HR->record_count('leave_view');
        $config["per_page"] = 30;
        $config["uri_segment"] = 3;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $this->data['links'] = $this->pagination->create_links();

        $search['Employee'] = $basic_info[0]->EmployeeId;

        $this->data['employeelist'] = $this->HR->leavelist($search, $config["per_page"], $page);

        $this->data['content'] = 'hr/employee/leave';
        $this->load->view('hr/template', $this->data);
    }

    function deleteovertime($id) {
        $this->db->delete('overtime', array('id' => $id));
        $this->session->set_flashdata('message', 'Data deleted');
        redirect('hr/overtimereport', 'refresh');
    }

    function overtimereport() {
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('from_date', 'From date', 'xss_clean|callback_validate_date');
        $this->form_validation->set_rules('to_date', 'Up to', 'xss_clean|callback_validate_date');
        $test = TRUE;
        if ($this->input->post('search')) {
            if (!$this->input->post('name') && !$this->input->post('from_date') && !$this->input->post('to_date')) {
                $test = FALSE;
                $this->data['error_in'] = "Please select at least one searching criteria";
            }
        }
        if ($this->form_validation->run() == TRUE && $test == TRUE) {

            $employee = trim($this->input->post('name'));
            $from_date = trim($this->input->post('from_date'));
            $to_date = trim($this->input->post('to_date'));
            $this->data['overtimereport'] = $this->HR->get_overtime($employee, $from_date, $to_date);
        }
        $this->data['content'] = 'hr/overtime/overtimereport';
        $this->load->view('hr/template', $this->data);
    }

    function addovertime() {
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('name', 'Employee Number', 'xss_clean|required');
        $this->form_validation->set_rules('hours', 'Overtime Hours', 'xss_clean|required|integer');
        $this->form_validation->set_rules('date', 'Overtime Date', 'xss_clean|required|callback_validate_date');
        if ($this->form_validation->run() == TRUE) {
            $employee = check_employee_number(trim($this->input->post('name')));
            if (count($employee) > 0) {
                $array = array(
                    'Employee' => trim($this->input->post('name')),
                    'hours' => trim($this->input->post('hours')),
                    'date' => trim($this->input->post('date'))
                );
                $insert = $this->HR->addovertime($array);
                if ($insert) {
                    $this->session->set_flashdata('message', 'Data saved !');
                    redirect('hr/addovertime', 'refresh');
                } else {
                    $this->data['error_in'] = 'Duplicate entry';
                }
            } else {
                $this->data['error_in'] = "Emplyee number does not exist in the system !";
            }
        }
        $this->data['content'] = 'hr/overtime/addovertime';
        $this->load->view('hr/template', $this->data);
    }

    function discipline($id, $edit=null) {
        $this->data['id'] = $id;
        if ($edit != null) {
            $this->data['edit_data'] = $edit;
            $this->data['discpline_info'] = $this->HR->discipline($edit);
        }

        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('violation', 'Violation Type', 'xss_clean|required');
        $this->form_validation->set_rules('violationdate', 'Violation Date', 'xss_clean|required|callback_validate_date');
        $this->form_validation->set_rules('employee', 'Employee Statement', 'xss_clean|required');
        $this->form_validation->set_rules('employer', 'Employer Statement', 'xss_clean|required');
        if ($this->form_validation->run() == TRUE) {
            $array = array(
                'Employee' => $id,
                'Violation' => $this->input->post('violation'),
                'ViolationDate' => $this->input->post('violationdate'),
                'EmployeeStatement' => $this->input->post('employee'),
                'EmployerStatement' => $this->input->post('employer'),
            );

            if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
                $file = $this->upload_file($_FILES, 'file', 'discipline');
                $array['Attachment'] = $file;
            }


            $insert = $this->HR->add_discipline($array, $edit);
            if ($insert == 1) {
                $this->session->set_flashdata('message', 'Discipline information saved !!');
                redirect('hr/discipline/' . $id . '/' . $edit, 'refresh');
            } else {
                $this->data['error_in'] = "Fail to save record !!";
            }
        }

        $this->data['discpline'] = $this->HR->discipline();
        $this->data['content'] = 'hr/employee/descipline';
        $this->load->view('hr/template', $this->data);
    }

    function qualification($id, $edit=null) {
        $this->data['id'] = $id;

        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('education', 'Education Level', 'xss_clean|required');
        $this->form_validation->set_rules('college', 'College', 'xss_clean|required');

        $upload_photo = TRUE;

        if ($edit == null) {
            if (isset($_POST['Save'])) {
                if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {

                    $upload_photo = TRUE;
                } else {
                    $this->data['photo'] = 'Please attach document';
                    $upload_photo = FALSE;
                }
            }
        }

        if ($this->form_validation->run() == TRUE && $upload_photo == TRUE) {
            $array = array(
                'Employee' => $id,
                'EducationLevel' => trim($this->input->post('education')),
                'College' => trim($this->input->post('college')),
                'Comment' => trim($this->input->post('comment')),
            );
            $insert = $this->HR->addqualification($array, $edit);
            if ($insert == 1) {
                if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
                    $file = $this->upload_file($_FILES, 'file', 'qualification');
                    if ($edit != null) {
                        $this->db->update('qualification', array('Attachment' => $file), array('id' => $edit));
                    } else {
                        $last_id = $this->db->query("SELECT id FROM qualification WHERE Employee='$id' ORDER BY id DESC")->result();
                        $last_id_inserted = $last_id[0]->id;
                    }$this->db->update('qualification', array('Attachment' => $file), array('id' => $last_id_inserted));
                }
                $this->session->set_flashdata('message', 'Qualification information saved !!');
                redirect('hr/qualification/' . $id . '/' . $edit, 'refresh');
            } else {
                $this->data['error_in'] = 'Fail to save.. Duplicate entry..';
            }
        }

        if ($edit != null) {
            $this->data['educationinfo'] = $this->HR->qualificationinfo($id, $edit);
            $this->data['edit_id'] = $edit;
        }
        $this->data['educationlevel'] = $this->HR->educationlevel();
        $this->data['qualificationlist'] = $this->HR->qualificationlist($id);
        $this->data['content'] = 'hr/employee/qualification';
        $this->load->view('hr/template', $this->data);
    }

    function attachment($id, $edit=null) {
        $this->data['id'] = $id;

        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('comment', 'Explanation', 'xss_clean|required');
        $upload_photo = TRUE;

        if ($edit == null) {
            if (isset($_POST['Save'])) {
                if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {

                    $upload_photo = TRUE;
                } else {
                    $this->data['photo'] = 'Please attach document';
                    $upload_photo = FALSE;
                }
            }
        }

        if ($this->form_validation->run() == TRUE && $upload_photo == TRUE) {
            $array = array(
                'Employee' => $id,
                'Comment' => trim($this->input->post('comment')),
            );
            $insert = $this->HR->addattachment($array, $edit);
            if ($insert == 1) {
                if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
                    $file = $this->upload_file($_FILES, 'file', 'attachment');
                    if ($edit != null) {
                        $this->db->update('attachment', array('Attachment' => $file), array('id' => $edit));
                    } else {
                        $last_id = $this->db->query("SELECT id FROM attachment WHERE Employee='$id' ORDER BY id DESC")->result();
                        $last_id_inserted = $last_id[0]->id;
                    }$this->db->update('attachment', array('Attachment' => $file), array('id' => $last_id_inserted));
                }
                $this->session->set_flashdata('message', 'Attachment saved !!');
                redirect('hr/attachment/' . $id . '/' . $edit, 'refresh');
            }
        }


        if ($edit != null) {
            $this->data['edit_id'] = $edit;
            $this->data['attachmentinfo'] = $this->HR->attachmentinfo($id, $edit);
        }
        $this->data['attachment'] = $this->HR->attachment($id);
        $this->data['content'] = 'hr/employee/attachment';
        $this->load->view('hr/template', $this->data);
    }

    function promotion($id, $edit=null) {
        $this->data['id'] = $id;

        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('position', 'Position', 'xss_clean|required');
        $this->form_validation->set_rules('workstation', 'WorkStation', 'xss_clean|required');
        $this->form_validation->set_rules('department', 'Department', 'xss_clean|required');
        $this->form_validation->set_rules('startdate', 'Start Date', 'xss_clean|required|callback_validate_date');
        $upload_photo = TRUE;

        if ($edit == null) {
            if (isset($_POST['Save'])) {
                if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {

                    $upload_photo = TRUE;
                } else {
                    $this->data['photo'] = 'Please attach promotion letter';
                    $upload_photo = FALSE;
                }
            }
        }
        if ($this->form_validation->run() == TRUE && $upload_photo == TRUE) {
            $array = array(
                'Employee' => $id,
                'Position' => $this->input->post('position'),
                'Department' => $this->input->post('department'),
                'WorkStation' => $this->input->post('workstation'),
                'Startdate' => $this->input->post('startdate'),
            );
            $insert = $this->HR->addpromotion($array, $edit);
            if ($insert == 1) {
                if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
                    $file = $this->upload_file($_FILES, 'file', 'promotion');
                    if ($edit != null) {
                        $this->db->update('promotion', array('PromotionLetter' => $file), array('id' => $edit));
                    } else {
                        $last_id = $this->db->query("SELECT id FROM promotion WHERE Employee='$id' ORDER BY id DESC")->result();
                        $last_id_inserted = $last_id[0]->id;
                    }$this->db->update('promotion', array('PromotionLetter' => $file), array('id' => $last_id_inserted));
                }
                $this->session->set_flashdata('message', 'Job Information saved !!');
                redirect('hr/promotion/' . $id . '/' . $edit, 'refresh');
            } else {
                $this->data['error_in'] = "Fail to save record..Duplicate entry";
            }
        }

        $this->data['promotionlist'] = $this->HR->promotionlist($id);

        if ($edit != null) {
            $this->data['edit_id'] = $edit;
            $this->data['promotion'] = $this->HR->promotioninfo($id, $edit);
        }
        $this->data['workstation'] = $this->HR->workstation();
        $this->data['department'] = $this->HR->department();
        $this->data['position'] = $this->HR->position();
        $this->data['content'] = 'hr/employee/promotion';
        $this->load->view('hr/template', $this->data);
    }

    function salary($id) {
        $this->data['id'] = $id;
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('paygrade', 'Salary Grade', 'xss_clean|required');
        $this->form_validation->set_rules('amount', 'Amount', 'xss_clean|required|numeric');
        if ($this->form_validation->run()) {
            $array = array(
                'Employee' => $id,
                'Amount' => trim($this->input->post('amount')),
                'SalaryGrade' => $this->input->post('paygrade'),
            );

            $insert = $this->HR->addsalary($array);
            if ($insert == 1) {
                $this->session->set_flashdata('message', 'Salary Information saved !!');
                redirect('hr/salary/' . $id, 'refresh');
            }
        }
        $this->data['salarygrade'] = $this->HR->salarygrade();
        $info = $this->HR->salaryinfo($id);

        if (count($info) > 0) {
            $this->data['salaryinfo'] = $info;
        }
        $this->data['content'] = 'hr/employee/salary';
        $this->load->view('hr/template', $this->data);
    }

    function job($id) {
        $this->data['id'] = $id;
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('position', 'Position', 'xss_clean|required');
        $this->form_validation->set_rules('workstation', 'WorkStation', 'xss_clean|required');
        $this->form_validation->set_rules('department', 'Department', 'xss_clean|required');
        $this->form_validation->set_rules('contract', 'Contract Type', 'xss_clean|required');
        $this->form_validation->set_rules('joindate', 'Join Date', 'xss_clean|required|callback_validate_date');
        $this->form_validation->set_rules('startdate', 'Start Date', 'xss_clean|required|callback_validate_date');
        $this->form_validation->set_rules('enddate', 'End Date', 'xss_clean|required|callback_validate_date');


        if ($this->form_validation->run()) {
            $array = array(
                'Employee' => $id,
                'Position' => $this->input->post('position'),
                'Department' => $this->input->post('department'),
                'WorkStation' => $this->input->post('workstation'),
                'ContractType' => $this->input->post('contract'),
                'Joindate' => $this->input->post('joindate'),
                'Startdate' => $this->input->post('startdate'),
                'Enddate' => $this->input->post('enddate'),
            );
            $insert = $this->HR->addjob($array);
            if ($insert == 1) {
                if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
                    $file = $this->upload_file($_FILES, 'file', 'contract');
                    $this->db->update('job', array('Contract' => $file), array('Employee' => $id));
                }
                $this->session->set_flashdata('message', 'Job Information saved !!');
                redirect('hr/job/' . $id, 'refresh');
            } else {
                $this->data['error_in'] = "Fail to save record..";
            }
        }
        $jobdata = $this->HR->job($id);
        if (count($jobdata) > 0) {
            $this->data['jobinfo'] = $jobdata;
        }
        $this->data['department'] = $this->HR->department();
        $this->data['position'] = $this->HR->position();
        $this->data['workstation'] = $this->HR->workstation();
        $this->data['contracttype'] = $this->HR->contracttype();
        $this->data['content'] = 'hr/employee/job';
        $this->load->view('hr/template', $this->data);
    }

    function dependentdelete($employee, $id) {
        $this->db->delete('dependent', array('id' => $id));
        redirect('hr/dependent/' . $employee, 'refresh');
    }

    function dependent($id) {
        $this->data['id'] = $id;
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('name', 'Name', 'xss_clean|required');
        $this->form_validation->set_rules('relation', 'Relation', 'xss_clean|required');
        $this->form_validation->set_rules('dob', 'Date of Birth', 'xss_clean|callback_validate_date');

        if ($this->form_validation->run()) {
            $array = array(
                'Employee' => $id,
                'Name' => trim($this->input->post('name')),
                'Relation' => trim($this->input->post('relation')),
                'dob' => trim($this->input->post('dob')),
            );

            $insert = $this->HR->adddependent($array);
            if ($insert == 1) {
                $this->session->set_flashdata('message', 'Dependents information saved !!');
                redirect('hr/dependent/' . $id, 'refresh');
            } else {
                $this->data['error_in'] = 'Fail to save record!!';
            }
        }
        $info = $this->HR->dependent($id);
        //if(count($info)>0){
        $this->data['dependentsinfo'] = $info;
        //}
        $this->data['content'] = 'hr/employee/dependent';
        $this->load->view('hr/template', $this->data);
    }

    function emergency($id) {
        $this->data['id'] = $id;
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('name', 'Name', 'xss_clean|required');
        $this->form_validation->set_rules('relation', 'Relation', 'xss_clean|required');
        $this->form_validation->set_rules('hometele', 'Home Telephone', 'xss_clean');
        $this->form_validation->set_rules('Mobile', 'Mobile', 'xss_clean');
        $this->form_validation->set_rules('name2', 'Name', 'xss_clean|required');
        $this->form_validation->set_rules('relation2', 'Relation', 'xss_clean|required');
        $this->form_validation->set_rules('hometele2', 'Home Telephone', 'xss_clean');
        $this->form_validation->set_rules('mobile2', 'Mobile', 'xss_clean');

        if ($this->form_validation->run()) {
            $array = array(
                'Employee' => $id,
                'Name_1' => trim($this->input->post('name')),
                'Relation_1' => trim($this->input->post('relation')),
                'LandLine_1' => trim($this->input->post('hometele')),
                'Mobile_1' => trim($this->input->post('mobile')),
                'Name_2' => trim($this->input->post('name2')),
                'Relation_2' => trim($this->input->post('relation2')),
                'LandLine_2' => trim($this->input->post('hometele2')),
                'Mobile_2' => trim($this->input->post('mobile2')),
            );

            $insert = $this->HR->addemergency($array);
            if ($insert == 1) {
                $this->session->set_flashdata('message', 'Emergency Contact information saved !!');
                redirect('hr/emergency/' . $id, 'refresh');
            } else {
                $this->data['error_in'] = 'Fail to save record!!';
            }
        }
        $info = $this->HR->emergency($id);
        if (count($info) == 1) {
            $this->data['employeeinfo'] = $info;
        }
        $this->data['content'] = 'hr/employee/emergency';
        $this->load->view('hr/template', $this->data);
    }

    function contact($id) {
        $this->data['id'] = $id;
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('street', 'Street', 'xss_clean');
        $this->form_validation->set_rules('postal', 'Postal', 'xss_clean');
        $this->form_validation->set_rules('region', 'Region', 'xss_clean');
        $this->form_validation->set_rules('district', 'District', 'xss_clean');
        $this->form_validation->set_rules('hometele', 'Home Telephone', 'xss_clean');
        $this->form_validation->set_rules('mobile', 'Mobile', 'xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'xss_clean|valid_email');

        if ($this->form_validation->run()) {
            $array = array(
                'Employee' => $id,
                'Street' => trim($this->input->post('street')),
                'Postal' => trim($this->input->post('postal')),
                'Region' => trim($this->input->post('region')),
                'District' => trim($this->input->post('district')),
                'Email' => trim($this->input->post('email')),
                'LandLine' => trim($this->input->post('hometele')),
                'Mobile' => trim($this->input->post('mobile')),
            );

            $insert = $this->HR->addcontact($array);
            if ($insert == 1) {
                $this->session->set_flashdata('message', 'Contact information saved !!');
                redirect('hr/contact/' . $id, 'refresh');
            } else {
                $this->data['error_in'] = 'Fail to save record!!';
            }
        }
        $info = $this->HR->contact($id);
        if (count($info) == 1) {
            $this->data['employeeinfo'] = $info;
        }
        $this->data['region'] = $this->HR->regions();
        $this->data['district'] = $this->HR->district();
        $this->data['content'] = 'hr/employee/contact';
        $this->load->view('hr/template', $this->data);
    }

    function personalinfo($id) {
        $this->data['id'] = $id;

        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('fname', 'First Name', 'xss_clean|required');
        $this->form_validation->set_rules('mname', 'Middle Name', 'xss_clean');
        $this->form_validation->set_rules('lname', 'Last Name', 'xss_clean|required');
        $this->form_validation->set_rules('dob', 'Date of Birth', 'xss_clean|required|callback_validate_date');
        $this->form_validation->set_rules('sex', 'Gender', 'xss_clean|required');
        $this->form_validation->set_rules('employee', 'Employee ID', 'xss_clean|required');
        $this->form_validation->set_rules('religion', 'Religion', 'xss_clean');
        $this->form_validation->set_rules('region', 'Region', 'xss_clean|required');
        $this->form_validation->set_rules('district', 'District', 'xss_clean|required');
        $this->form_validation->set_rules('marital', 'Marital Status', 'xss_clean|required');
        $this->form_validation->set_rules('education', 'Education Level', 'xss_clean|required');
        $upload_photo = true;
        if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
            $extension = $this->getExtension($_FILES['file']['name']);
            if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
                $this->data['photo'] = 'Invalid photo format, only jpg,jpeg,png and gif is supported';
                $upload_photo = FALSE;
            } else {
                $upload_photo = TRUE;
            }
        }
        if ($this->form_validation->run() == TRUE && $upload_photo == TRUE) {
            $subdata = array(
                'EmployeeId' => trim($this->input->post('employee')),
                'FirstName' => ucwords(strtolower(trim($this->input->post('fname')))),
                'MiddleName' => ucwords(strtolower(trim($this->input->post('mname')))),
                'LastName' => ucwords(strtolower(trim($this->input->post('lname')))),
                'Sex' => ucwords(strtolower(trim($this->input->post('sex')))),
                'MaritalStatus' => $this->input->post('marital'),
                'dob' => $this->input->post('dob'),
                'Religion' => $this->input->post('religion'),
                'Region' => $this->input->post('region'),
                'District' => $this->input->post('district'),
                'EducationLevel' => $this->input->post('education'),
            );
            $insert = $this->HR->addpersoninfo($subdata, $id);
            if ($insert == 1) {
                if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
                    $file = $this->upload_file($_FILES, 'file', 'photo');
                    $this->db->update('employee', array('photo' => $file), array('EmployeeId' => $subdata['EmployeeId']));
                }
                // unset ($_POST);
                $this->session->set_flashdata('message', 'Employee information saved !!');
                redirect('hr/personalinfo/' . $id, 'refresh');
            } else {
                $this->data['error_in'] = 'Employee ID already exist!!';
            }
        }

        if ($id != null) {

            $info = $this->HR->personinfo($id);
            if (count($info) == 1) {
                $this->data['employeeinfo'] = $info;
            }
            $this->data['id'] = $id;
        }
        $this->data['marital'] = $this->HR->marital();
        $this->data['region'] = $this->HR->regions();
        $this->data['educationlevel'] = $this->HR->educationlevel();
        $this->data['district'] = $this->HR->district();
        $this->data['religion'] = $this->HR->religion();
        $this->data['content'] = 'hr/employee/personalinfo';
        $this->load->view('hr/template', $this->data);
    }

    function addemployee($id=null) {
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('fname', 'First Name', 'xss_clean|required');
        $this->form_validation->set_rules('mname', 'Middle Name', 'xss_clean');
        $this->form_validation->set_rules('lname', 'Last Name', 'xss_clean|required');
        $this->form_validation->set_rules('dob', 'Date of Birth', 'xss_clean|required|callback_validate_date');
        $this->form_validation->set_rules('sex', 'Gender', 'xss_clean|required');
        $this->form_validation->set_rules('employee', 'Employee ID', 'xss_clean|required');
        $this->form_validation->set_rules('religion', 'Religion', 'xss_clean');
        $this->form_validation->set_rules('employee', 'Employee ID', 'xss_clean|required');
        $this->form_validation->set_rules('religion', 'Religion', 'xss_clean');
        $this->form_validation->set_rules('Employee_Type', 'Employee Type', 'xss_clean|required');
        $this->form_validation->set_rules('Employee_Title', 'Employee Title', 'xss_clean|required');
        $this->form_validation->set_rules('Job_Code', 'Job Code', 'xss_clean|required');
        $this->form_validation->set_rules('Employee_Department_Name', 'Department Name', 'xss_clean|required');
        $this->form_validation->set_rules('Employee_Branch_Name', 'Branch Name', 'xss_clean|required');
        $this->form_validation->set_rules('region', 'Region', 'xss_clean|required');
        $this->form_validation->set_rules('district', 'District', 'xss_clean|required');
        $this->form_validation->set_rules('marital', 'Marital Status', 'xss_clean|required');
        $this->form_validation->set_rules('education', 'Education Level', 'xss_clean|required');
        $upload_photo = true;
        
         $this->load->library('multipledb'); // loading library.

        if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
            $config['upload_path']          = 'uploads/photo/';
            $config['allowed_types']        = 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG';
            // $config['max_size']             = 100;
            // $config['max_width']            = 1024;
            // $config['max_height']           = 768;

            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('file'))
            {
                $this->data['photo'] = $this->upload->display_errors();
                $upload_photo = FALSE;
            }
            else
            {
                $uploadData = $this->upload->data();
                $file = $uploadData['file_name'];
                $upload_photo = TRUE;
                // echo 'file= '.$file;
            }
        }else{
            $file="";
            $upload_photo = FALSE;
            $this->data['photo'] = 'Failed to upload the photo, please try again';
            // echo 'failed';
        }
// exit;

        if ($this->form_validation->run() == TRUE && $upload_photo == TRUE) {
            $subdata = array(
                'EmployeeId' => trim($this->input->post('employee')),
                'FirstName' => ucwords(strtolower(trim($this->input->post('fname')))),
                'MiddleName' => ucwords(strtolower(trim($this->input->post('mname')))),
                'LastName' => ucwords(strtolower(trim($this->input->post('lname')))),
                'Sex' => ucwords(strtolower(trim($this->input->post('sex')))),
                'MaritalStatus' => $this->input->post('marital'),
                'dob' => $this->input->post('dob'),
                'Religion' => $this->input->post('religion'),
                'Region' => $this->input->post('Region'),
                'District' => $this->input->post('district'),
                'EducationLevel' => $this->input->post('education'),
                'photo' => $file,
            );
            $insert = $this->HR->addpersoninfo($subdata, $id);
            if ($insert) {
                
                // insert into users
                $select = $this->input->post('select');
                $pswd = $this->input->post('password');
                $uname = $this->input->post('username');
                if($select=='exported'){
                    $password = $pswd;
                }
                elseif($select=='manual'){
                    $password =MD5($pswd);
                }
                else{
                    $password=MD5('user'); //set default password = 'user'
                }

                if ($uname==''&&$uname==' '&&$uname=='NULL') {
                    $username = $subdata['LastName']; //set username=lastname
                } else {
                    $username = $uname;
                }
                
                $userdata = array(
                    'EmployeeId' => $subdata['EmployeeId'],
                    'username' => $username,
                    'password' => $password,
                    'active' => 1
                );

                $user = $this->db->insert('users',$userdata);
                 //end insert into users

                $user_id=$this->db->insert_id(); 

                //insert into users_from_ehms
                if($select=='exported'){
                    $users_from_ehmsdata = array(
                        'ehms_user_id' => $this->input->post('ehmsuserid'),
                        'hrp_user_id' => $user_id
                    );
                    $this->db->insert('users_from_ehms',$users_from_ehmsdata);
                }
                //end insert into users_from_ehms

                //insert into users_groups
                $grp = $this->input->post('group');
                if ($grp!='') {
                    $group = $grp;
                } else {
                    $group = 2; //general user
                }
                
                $users_groupsdata = array(
                    'user_id' =>  $user_id,
                    'group_id' => $group
                );
                $this->db->insert('users_groups',$users_groupsdata);
                // end insert into users_groups

                $this->session->set_flashdata('message', 'Employee information saved !!');
                redirect('hr/personalinfo/' . $insert, 'refresh');
            } else {
                $this->data['error_in'] = 'Employee ID already exist!!';
            }
        }
        
        // else{
        //     $this->data['photo'] = 'Failed to upload the photo, please try again';
        // }

        if ($id != null) {
            $this->data['employeeinfo'] = $this->HR->personinfo($id);
            $this->data['id'] = $id;
        }
            $this->data['marital'] = $this->HR->marital();
            $this->data['region'] = $this->HR->regions();
            $this->data['educationlevel'] = $this->HR->educationlevel();
            $this->data['district'] = $this->HR->district();
            $this->data['religion'] = $this->HR->religion();
        
        // Loading second db and running query.
//            $CI = &get_instance();
//            //setting the second parameter to TRUE (Boolean) the function will return the database object.
//            $this->db2 = $CI->load->database('ehms', TRUE);
//            $qry = $this->db2->query("SELECT * FROM tbl_department");
//            print_r($qry->result());
        
        //data from ehms
        
       
       // $query2 = $this->multipledb->db->query('your query goes here'); // running query using library.
        $this->data['Department'] = $this->multipledb->Department();
        $this->data['Branch'] = $this->multipledb->Branch();
        
       //print_r($this->multipledb->Department());
        
        $this->data['content'] = 'hr/employee/addemployee';
        $this->load->view('hr/template', $this->data);
    }

    function position($id=null) {
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->form_validation->set_rules('name', 'Name', 'xss_clean|required');
        if ($this->form_validation->run()) {
            $array = array('Name' => trim($this->input->post('name')));
            $insert = $this->HR->addposition($array, $id);
            if ($insert == 1) {
                $this->session->set_flashdata('message', 'Position/Job Title Information recorded');
                redirect('hr/position/', 'refresh');
            } else {
                $this->data['error_in'] = "Record Failed to save. Check if is duplicate Name";
            }
        }
        if ($id != null) {
            $this->data['positiondata'] = $this->HR->position($id);
            $this->data['id'] = $id;
        }
        $this->data['position'] = $this->HR->position();
        $this->data['content'] = 'hr/department/position';
        $this->load->view('hr/template', $this->data);
    }

    function department($id=null) {
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->form_validation->set_rules('name', 'Name', 'xss_clean|required');
        if ($this->form_validation->run()) {
            $array = array('Name' => trim($this->input->post('name')));
            $insert = $this->HR->adddepartment($array, $id);
            if ($insert == 1) {
                $this->session->set_flashdata('message', 'Department Name recorded');
                redirect('hr/department/', 'refresh');
            } else {
                $this->data['error_in'] = "Record Failed to save. Check if is duplicate Name";
            }
        }
        if ($id != null) {
            $this->data['departmentdata'] = $this->HR->department($id);
            $this->data['id'] = $id;
        }
        $this->data['department'] = $this->HR->department();
        $this->data['content'] = 'hr/department/department';
        $this->load->view('hr/template', $this->data);
    }

    function leavetype($id=null) {
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->form_validation->set_rules('name', 'Leave Type', 'xss_clean|required');
        $this->form_validation->set_rules('days', 'Leave Type', 'xss_clean|integer');
        if ($this->form_validation->run()) {
            $array = array(
                'Name' => trim($this->input->post('name')),
                'Days' => trim($this->input->post('days')),
            );
            $insert = $this->HR->addleavetype($array, $id);
            if ($insert == 1) {
                $this->session->set_flashdata('message', 'Leave Type  recorded');
                redirect('hr/leavetype/', 'refresh');
            } else {
                $this->data['error_in'] = "Record Failed to save. Check if is duplicate Name";
            }
        }
        if ($id != null) {
            $this->data['departmentdata'] = $this->HR->leavetype($id);
            $this->data['id'] = $id;
        }
        $this->data['department'] = $this->HR->leavetype();
        $this->data['content'] = 'hr/leave/leavetype';
        $this->load->view('hr/template', $this->data);
    }

    
    function leavelist() {
        $search = array();
        if (isset($_POST['Search'])) {
            $search['Fromdate'] = trim($this->input->post('fromdate'));
            $search['Todate'] = trim($this->input->post('todate'));
            $search['Employee'] = trim($this->input->post('employee'));
            $search['LeaveType'] = trim($this->input->post('station'));
            $search['is_approved'] = trim($this->input->post('status'));
        }
        // echo $search['Retere'];
        $config["base_url"] = base_url() . "index.php/hr/leavelist";
        $config["total_rows"] = $this->HR->record_count('leave_view');
        $config["per_page"] = 30;
        $config["uri_segment"] = 3;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->data['links'] = $this->pagination->create_links();

        $this->data['searchingdata'] = $search;

        $this->data['employeelist'] = $this->HR->leavelist($search, $config["per_page"], $page);
        $this->data['station'] = $this->HR->leavetype();
        $this->data['content'] = 'hr/leave/leavelist';
        $this->load->view('hr/template', $this->data);
    }

    function leavesummary() {
        $this->data['leave'] = $this->db->get_where('leave_view', array('is_active' => 1, 'is_approved' => 1, 'day_remain > ' => 0))->result();
        $this->data['content'] = 'hr/leave/leavesummary';
        $this->load->view('hr/template', $this->data);
    }

    function assignleave($id=null) {
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->form_validation->set_rules('name', 'Employee ID', 'xss_clean|required');
        $this->form_validation->set_rules('leavetype', 'Leave Type', 'xss_clean|required');
        $this->form_validation->set_rules('fromdate', 'From Date', 'xss_clean|required|callback_validate_date');
        $this->form_validation->set_rules('todate', 'To Date', 'xss_clean|required|callback_validate_date');
        if ($this->form_validation->run()) {

            $array = array(
                'Employee' => trim($this->input->post('name')),
                'LeaveType' => trim($this->input->post('leavetype')),
                'Fromdate' => trim($this->input->post('fromdate')),
                'Todate' => trim($this->input->post('todate')),
            );

            $employee = check_employee_number($array['Employee']);
            if (count($employee) == 1) {
                $insert = $this->HR->assignleave($array, $id);
                if ($insert == 1) {
                    $this->session->set_flashdata('message', 'Leave Information Recorded');
                    redirect('hr/assignleave/', 'refresh');
                } else if ($insert == 2) {
                    $this->data['error_in'] = "Employee has leave which is active";
                }
            } else {
                $this->data['error_in'] = 'Employee ID does not exist !!';
            }
        }

        if ($id != null) {
            $this->data['id'] = $id;
            $this->data['leaveinfo'] = $this->HR->leavehistory($id);
        }
        $this->data['leavetype'] = $this->HR->leavetype();
        $this->data['content'] = 'hr/leave/assignleave';
        $this->load->view('hr/template', $this->data);
    }

    function approveleave($id=null, $action=null) {

        if ($id != null) {
            if ($action != null) {
                if ($action == 2) {
                    $this->db->update('leave_info', array('is_active' => 2, 'is_approved' => $action), array('id' => $id));
                } else if ($action == 1) {
                    $this->db->update('leave_info', array('is_active' => 1, 'is_approved' => $action), array('id' => $id));
                }
            }
        }

        $this->data['leave'] = $this->db->get_where('leave_info', array('is_active' => 0, 'is_approved' => 0))->result();
        $this->data['content'] = 'hr/leave/approveleave';
        $this->load->view('hr/template', $this->data);
    }

    function locationdistrict($region, $id=null) {
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->data['region'] = $region;
        $this->form_validation->set_rules('name', 'Name', 'xss_clean|required');
        if ($this->form_validation->run()) {
            $array = array(
                'Name' => trim($this->input->post('name')),
                'parent' => $region
            );
            $insert = $this->HR->addlocation($array, $id);
            if ($insert == 1) {
                $this->session->set_flashdata('message', 'Location recorded');
                redirect('hr/locationdistrict/' . $region . '/', 'refresh');
            } else {
                $this->data['error_in'] = "Record Failed to save. Check if is duplicate Name";
            }
        }
        if ($id != null) {
            $this->data['locationdata'] = $this->HR->district($region, $id);
            $this->data['id'] = $id;
        }
        $this->data['location'] = $this->HR->district($region);
        $this->data['content'] = 'hr/workstation/locationdistrict';
        $this->load->view('hr/template', $this->data);
    }

    function location($id=null) {
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->form_validation->set_rules('name', 'Name', 'xss_clean|required');
        if ($this->form_validation->run()) {
            $array = array('Name' => trim($this->input->post('name')));
            $insert = $this->HR->addlocation($array, $id);
            if ($insert == 1) {
                $this->session->set_flashdata('message', 'Location recorded');
                redirect('hr/location/', 'refresh');
            } else {
                $this->data['error_in'] = "Record Failed to save. Check if is duplicate Name";
            }
        }
        if ($id != null) {
            $this->data['locationdata'] = $this->HR->regions($id);
            $this->data['id'] = $id;
        }
        $this->data['location'] = $this->HR->regions();
        $this->data['content'] = 'hr/workstation/location';
        $this->load->view('hr/template', $this->data);
    }

    function addworkstation($id=null) {
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('name', 'Name', 'xss_clean|required');
        $this->form_validation->set_rules('desc', 'Description', 'xss_clean|required');
        if ($id != null) {
            $this->data['workstation'] = $this->HR->workstation($id);
            $this->data['id'] = $id;
        }
        if ($this->form_validation->run()) {
            $insert = array(
                'Name' => trim($this->input->post('name')),
                'Description' => trim($this->input->post('desc'))
            );
            $in = $this->HR->addworkstation($insert, $id);
            if ($in == 1) {
                $this->session->set_flashdata('message', 'Workstation recorded');
                redirect('hr/addworkstation/' . $id, 'refresh');
            } else {
                $this->data['error_in'] = "Record Failed to save. Check if is duplicate Name";
            }
        }
        $this->data['content'] = 'hr/workstation/addworkstation';
        $this->load->view('hr/template', $this->data);
    }

    function workstation() {
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $config["base_url"] = base_url() . "index.php/hr/workstation";
        $config["total_rows"] = $this->HR->record_count('workstation');
        $config["per_page"] = 25;
        $config["uri_segment"] = 3;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);
        $this->pagination->initialize($config);
        $key = $this->input->post('key');
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->data['links'] = $this->pagination->create_links();
        $this->data['workstation'] = $this->HR->workstation(null, $key, $config["per_page"], $page);
        $this->data['content'] = 'hr/workstation/workstation';
        $this->load->view('hr/template', $this->data);
    }

    function validate_date($date) {
        $CI = & get_instance();
        if ($date != "") {
            if (preg_match("/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/", $date)) {
                $date_array = explode("-", $date);
                if (checkdate($date_array[1], $date_array[2], $date_array[0])) {
                    return TRUE;
                } else {
                    $CI->form_validation->set_message('validate_date', "The %s must contain YYYY-MM-DD");
                    return FALSE;
                }
            } else {
                $CI->form_validation->set_message('validate_date', "The %s must contain YYYY-MM-DD");
                return FALSE;
            }
        }
    }

    function set_properties($objPHPExcel, $papersize, $fontstyle, $font) {
// Set properties
        $objPHPExcel->getProperties()->setCreator("Miltone")
                ->setLastModifiedBy("Miltone Urassa")
                ->setSubject("HRP")
                ->setDescription("HRP")
                ->setKeywords("HRP software")
                ->setCategory("Report");

        $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(15);

        # Set protected sheets to 'true' kama hutaki waandike waziedit sheets zako. Kama unataka wazi-edit weka 'false'
        $objPHPExcel->getActiveSheet()->getProtection()->setSheet(false);

        #set worksheet orientation and size
        $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize($papersize);

        #Set page fit width to true
        $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);


        //$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&C&BPlease treat this document as confidential!');
        #Set footer page numbers
        $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B Prepared by  HRP Software' . '&RPage &P of &N');

        #Show or hide grid lines
        $objPHPExcel->getActiveSheet()->setShowGridlines(false);

        #Set sheet style (fonts and font size)
        $objPHPExcel->getDefaultStyle()->getFont()->setName($fontstyle);
        $objPHPExcel->getDefaultStyle()->getFont()->setSize($font);

        #Set page margins
        $objPHPExcel->getActiveSheet()->getPageMargins()->setTop(1);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.75);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.75);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(1);
        for ($clo = 'A'; $clo < 'ZZ'; $clo++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($clo)->setAutoSize(true);
        }
        # Set Rows to repeate in each page
        $objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 5);


        $objPHPExcel->getActiveSheet()->mergeCells('B2:M2');
        $objPHPExcel->getActiveSheet()->mergeCells('B3:M3');
        $objPHPExcel->getActiveSheet()->mergeCells('B4:M4');
        $objPHPExcel->getActiveSheet()
                ->setCellValue('B2', strtoupper(company_info()->Name));
        $objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setSize(25);
        $objPHPExcel->getActiveSheet()->getStyle('B3')->getFont()->setSize(20);
        $objPHPExcel->getActiveSheet()->getStyle('B4')->getFont()->setSize(15);
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(26);
        $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(26);
        $objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(26);
        $objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(26);
        $objPHPExcel->getActiveSheet()->getStyle('B1:B4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    }

}

?>