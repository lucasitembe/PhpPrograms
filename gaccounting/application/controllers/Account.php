<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Account
 *
 * @author ADE
 */
class Account extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('Accounts');
        $this->load->model('Chop_model');

        $this->load->library("pagination");
    }

    public function index() {
        $this->load->view('shared/header');
        $this->load->view("account/index");
        $this->load->view('shared/footer');
    }

    public function role() {
        if (isset($_POST['role_name'])) {
            $msg = "";
            $status = "";
            //process input form 
            $this->form_validation->set_rules('role_name', 'Role name', 'trim|required');
            $this->form_validation->set_rules('role_desc', 'Role Description', 'trim');

            if ($this->form_validation->run() == FALSE) {
                $msg = validation_errors();
            } else {
                $result = $this->Accounts->addRole();

                if ($result == 'success') {
                    $msg = "Inserted successifully";
                    $status = '1';
                } else {
                    if ($result == '23000/1062') {
                        $msg = "Role already exists.";
                    } else {
                        $msg = "An error has occured.Please try again";
                    }

                    $status = '0';
                }
            }

            echo json_encode(array('tokenhash' => $this->security->get_csrf_hash(), 'data' => $msg, 'status' => $status));
        } else {
            //display currencies
            $config = array();
            $config["base_url"] = base_url() . "account/role";
            $config["total_rows"] = $this->Helper->record_count('tbl_roles');
            $config["per_page"] = $this->Helper->getConfigValue('PerPageRecordSize');

            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

            $data['roleList'] = $this->Accounts->getRoleList($this->pagination->per_page, $page);
            $data["links"] = $this->pagination->create_links();
            $data["page"] = $page;

            $this->load->view('shared/header');
            $this->load->view("account/role", $data);
            $this->load->view('shared/footer');
        }
    }

    public function account() {
        if (isset($_POST['email'])) {
            $msg = "";
            $status = "";
            //process input form 
            $this->form_validation->set_rules('fname', 'First name', 'trim|required');
            $this->form_validation->set_rules('lname', 'Last name', 'trim|required');
            $this->form_validation->set_rules('phone', 'Phone number', 'trim|required');
            $this->form_validation->set_rules('email', 'Email address', 'trim|required|valid_email');
            $this->form_validation->set_rules('gender', 'Gender', 'trim|required');
            $this->form_validation->set_rules('acc_status', 'Account status', 'trim|required');
            $this->form_validation->set_rules('username', 'Username', 'trim|required');
            $this->form_validation->set_rules('password', 'password', 'trim|required');
            $this->form_validation->set_rules('role', 'Role name', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                $msg = validation_errors();
            } else {
                $result = $this->Accounts->addNewAccount();

                if ($result == 'success') {
                    $msg = "Inserted successifully";
                    $status = '1';
                } else {
                    if ($result == '23000/1062') {
                        $msg = "Username " . $this->input->post('username') . " already exists.";
                    } else {
                        $msg = "An error has occured.Please try again";
                    }

                    $status = '0';
                }
            }

            echo json_encode(array('tokenhash' => $this->security->get_csrf_hash(), 'data' => $msg, 'status' => $status));
        } else {
            //display currencies
            $config = array();
            $config["base_url"] = base_url() . "account/account";
            $config["total_rows"] = $this->Helper->record_count('tbl_users');
            $config["per_page"] = $this->Helper->getConfigValue('PerPageRecordSize');

            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

            $data['roleList'] = $this->Accounts->getRoleList();
            $data['userList'] = $this->Accounts->getUserList($this->pagination->per_page, $page);
            $data["links"] = $this->pagination->create_links();
            $data["page"] = $page;

            $this->load->view('shared/header');
            $this->load->view("account/account", $data);
            $this->load->view('shared/footer');
        }
    }

    public function access_control() {
        $show['roles'] = $this->Accounts->m_show_roles();
        $count['num'] = $this->Chop_model->m_count_dokezo();
        $this->load->view('shared/header', $count);
        $this->load->view('account/access_control', $show);
        $this->load->view('shared/footer');
    }

    public function assign_access() {
        $check = $this->Accounts->m_access_value();
        $access = $this->Accounts->m_show_access();
        //echo "<pre>";
        //print_r($check);
        //exit();

        echo"
       <table style='background-color:white' class='table  table-bordered table-striped table-hover'>
        <tr>
        <thead>
        <th>Access name</th>
        <th>Check</th>
        </thead>
        </tr>
        <tbody> ";
        $i = 1;
        foreach ($access as $view) {
            echo " <tr> <td>
                " . $view['access_desc'] . "
                </td>
                <td>";
            if ($this->is_available($check, $view["access_leve_id"])) {
                echo " <input checked type='checkbox' name='access" . $i . "' value='" . $view["access_leve_id"] . "'/>
                  <input type='hidden' name='h_access" . $i . "' value='" . $view["access_leve_id"] . "'>";
            } else {
                echo " <input  type='checkbox' name='access" . $i . "' value='" . $view["access_leve_id"] . "'/>
                      <input type='hidden' name='h_access" . $i . "' value='" . $view["access_leve_id"] . "'>";
            }
            echo "</td>
            </tr> ";
            $i++;
        }
        echo " 
          <input type='hidden' name='count' value='$i'/>
       </tbody>";
    }

    public function is_available($check, $role = '') {
        $is_avalaible = false;
        $check_access = $check;
        $rol = $role;
        if (!empty($rol)) {
            foreach ($check_access as $value) {
                if ($value['access_leve_id'] == $rol) {
                    $is_avalaible = true;
                }
            }
        }
        return $is_avalaible;
    }

    public function role_permission() {
        $this->Accounts->m_role_permission();
        redirect('Account/access_control');
    }

    public function login($msg = '') {
        $data['login_error'] = '';

        if (!empty($msg)) {
            $data['login_error'] = $msg;
        }

        $this->load->view("account/login", $data);
    }

    //search ehms user || on 22-02-2019 @mfoydn
    public function search_user(){
        $get_search_value=$this->uri->segment(3);
        if(!empty($get_search_value)){
            $search_value = str_replace(" ", "%", $get_search_value);
        }else{
            $search_value = '';
        }

        $ehmsdb = $this->load->database('ehms', TRUE); // load ehms d
        
        $sql= "SELECT `tbl_employee`.`Employee_ID`, `Employee_Name`, `Given_Password`, `Given_Username` FROM `tbl_employee` INNER JOIN `tbl_privileges` ON `tbl_privileges`.`Employee_ID` = `tbl_employee`.`Employee_ID` WHERE `Employee_Name` LIKE '%$search_value%' OR `Given_Username` LIKE '%$search_value%' ORDER BY `Employee_Name` ASC";
        $query = $ehmsdb->query($sql);

        $html = '

                <div id="users_list">
                <table class="table table-hover table-responsive table-bordered" id="tableID">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee Name</th>
                            <th>Username</th>
                            <th style="display: none;"></th>
                        </tr>
                    </thead>
                    <tbody>';
                        foreach($query->result() as $key => $row){
            $html .= ' <tr style="cursor: pointer;" class="tr_ajax" onclick="select_tr_()">
                            <td>'. ($key+1) .'</td>
                            <td>'. $row->Employee_Name .'</td>
                            <td>'. $row->Given_Username .'</td>
                            <td style="display: none;">'. $row->Given_Password .'</td>
                        </tr>';
                        }
        $html .= ' </tbody>
                </table>
            </div>
        ';
        
        echo $html;
    }
    //END search ehms user || on 22-02-2019 @mfoydn

    //authenticate user from ehms system by Mfoy_dn 05-02-2017
	function authenticate_user_from_ehms()
	{
		$ems_username =$this->uri->segment(3);//get ehms user username
        $ems_password =$this->uri->segment(4);//get ehms user password
        
        // echo 'uri3= '.$ems_username; echo 'uri4= '.$ems_password; exit;

        $result = $this->Accounts->authorize_user_from_ehms($ems_username,$ems_password);

        if ($result == 'granted') {
            redirect('Home');
		}else{
            $msg = "Invalid Username or Password";
            $this->session->set_flashdata('login_error', $msg);
            redirect("Account/login"); 
        }
    }
    // end authenticate user from ehms system by Mfoy_dn 05-02-2017

    public function authorize() {
        if ($this->input->post() != null) {
            $this->form_validation->set_rules('userName', 'Username', 'trim|required');
            $this->form_validation->set_rules('userPassword', 'Password', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                $msg = validation_errors();
                $this->session->set_flashdata('login_error', $msg);

                redirect('Account/login');
            } else {
                $result = $this->Accounts->authorize();

                if ($result == 'granted') {
                    redirect('Home');
                } else {
                    $msg = "Invalid Username or password";
                    $this->session->set_flashdata('login_error', $msg);

                    redirect("Account/login");
                }
            }
        } else {
            redirect("Account/login");
        }
    }

    function logout() {
        $this->session->sess_destroy();
        redirect("Account/login");
    }

}
