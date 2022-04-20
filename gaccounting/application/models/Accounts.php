<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Accounts
 *
 * @author ADE
 */
class Accounts extends CI_Model {

    private $salt = "YG]j]vA!6T#W]AxrDCFs8za";

    public function __construct() {
        parent::__construct();
    }

    public function addRole() {
        $role_name = $this->input->post('role_name');
        $role_desc = $this->input->post('role_desc');

        $data = array(
            'role_name' => $role_name,
            'role_desc' => $role_desc
        );
        $rs = $this->db->insert('tbl_roles', $data);

        if ($rs) {
            return 'success';
        } else {
            return $this->db->error()['code'];
        }
    }

    public function addNewAccount() {
        $fname = $this->input->post('fname');
        $lname = $this->input->post('lname');
        $phone = $this->input->post('phone');
        $email = $this->input->post('email');
        $gender = $this->input->post('gender');
        $acc_status = $this->input->post('acc_status');
        $username = $this->input->post('username');
        // $password = password_hash($this->input->post('password'), PASSWORD_BCRYPT, array('salt' => $this->salt));
        $role = $this->input->post('role');

        $select = $this->input->post('select');
        $pswd = $this->input->post('password');
        if($select=='fromehms'){
            $password = $pswd;
        }
        elseif($select=='manual'){
            $password =MD5($pswd);
        }
        else{
            $password=MD5('user'); //set default password = 'user'
        }

        if ($username==''||$username==' '||$username=='NULL') {
            $username = $lname; //set username=lastname
        }

        $data = array(
            'fname' => $fname,
            'lname' => $lname,
            'phone' => $phone,
            'email' => $email,
            'gender' => $gender,
            'account_status' => $acc_status,
            'username' => $username,
            'password' => $password,
            'role_id' => $role
        );
        $rs = $this->db->insert('tbl_users', $data);

        if ($rs) {
            return 'success';
        } else {
            return $this->db->error()['code'];
        }
    }

    function getRoleList($limit = '', $start = '') {
        if (!empty($limit)) {
            $this->db->limit($limit, $start);
        }
        $query = $this->db->get("tbl_roles");

        if ($query->num_rows() > 0) {

            return $query->result();
        }

        return false;
    }

    function getUserList($limit, $start) {
        $this->db->limit($limit, $start);
        $this->db->select('*');
        $this->db->from('tbl_users u');
        $this->db->join('tbl_roles r', 'r.role_id = u.role_id');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            return $query->result();
        }

        return false;
    }

    public function m_show_access() {
        $this->db->select('*');
        $this->db->from('tbl_access_levels');
        //$this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function m_access_value() {
        $user_role = $_GET['input_role'];
        $this->db->select('*');
        $this->db->from('tbl_role_permission');
        //$this->db->join('user_roles','user_roles.user_id_ref=chop_users.user_id');
        $this->db->join('tbl_roles', 'tbl_roles.role_id=tbl_role_permission.role_id');
        $this->db->join('tbl_access_levels', 'tbl_access_levels.access_leve_id=tbl_role_permission.access_leve_id');
        $this->db->where('tbl_role_permission.role_id', $user_role);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function m_show_roles() {
        $this->db->select('*');
        $this->db->from('tbl_roles');
        //$this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function m_role_permission() {

        $rol = $_POST['role_name'];
        $count = $_POST['count'];

        for ($j = 1; $j < $count; $j++) {
            if (isset($_POST['access' . $j])) {
                $data = array(
                    'role_id' => $_POST['role_name'],
                    'access_leve_id' => $_POST['access' . $j]
                );

                //checking existing access_role
                $this->db->select('*');
                $this->db->from('tbl_role_permission');
                $this->db->where($data);
                $query = $this->db->get();
                $exist = $query->num_rows();
                if ($exist > 0) {
                    
                } else {
                    $this->db->insert('tbl_role_permission', $data);
                }
            }
            if (!isset($_POST['access' . $j])) {
                $data1 = array(
                    'role_id' => $_POST['role_name'],
                    'access_leve_id' => $_POST['h_access' . $j]
                );
                $this->db->where($data1);
                $this->db->delete('tbl_role_permission');
            }
        }
    }

    
    //authorize new by Mfoy DN 05-02-2019
    
    // 1. authorize_user_from_ehms
    public function authorize_user_from_ehms($username,$password) {

        $this->db->select('*');
        $this->db->from('tbl_users u');
        $this->db->join('tbl_roles r', 'u.role_id=r.role_id');
        $where = array(
            'username' => $username,
            'password' => $password
        );

        $this->db->where($where);
        $query = $this->db->get();

        $result = $query->result()[0];
        
        if ($query->num_rows()==1) {
            $data['userinfo'] = $result;
            $this->session->set_userdata($data);
            return  'granted';
        } else {
            return 'denied';
        }
    }

    // 2.authorize_gaccounting_user
    public function authorize() {
        $userName = $this->input->post('userName');
        $userPassword = $this->input->post('userPassword');
        $password = MD5($userPassword);

        $this->db->select('*');
        $this->db->from('tbl_users u');
        $this->db->join('tbl_roles r', 'u.role_id=r.role_id');
        $where = array(
            'username' => $userName,
            'password' => $password
        );

        $this->db->where($where);
        $query = $this->db->get();

        $result = $query->result()[0];
        
        if ($query->num_rows()==1) {
            $data['userinfo'] = $result;
            $this->session->set_userdata($data);
            return  'granted';
        } else {
            return 'denied';
        }
    }
    //end authorize new by Mfoy DN 05-02-2019

    public function authorize_old() {
        $userName = $this->input->post('userName');
        $userPassword = $this->input->post('userPassword');

        $this->db->select('*');
        $this->db->from('tbl_users u');
        $this->db->join('tbl_roles r', 'u.role_id=r.role_id');
        $where = array(
            'username' => $userName
        );

        $this->db->where($where);
        $query = $this->db->get();

        $result = $query->result()[0];

        $password = password_hash($userPassword, PASSWORD_BCRYPT, array('salt' => $this->salt));

        if (password_verify($userPassword, $result->password)) {
            $data['userinfo'] = $result;
            $this->session->set_userdata($data);

            return 'granted';
        } else {
            return 'denied';
        }
    }

}
