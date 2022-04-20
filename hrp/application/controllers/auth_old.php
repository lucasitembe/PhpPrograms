<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->database();
		$this->load->helper('url');
	}
        
            function ajax() {
        $this->load->view('auth/help');
    }

	//redirect if needed, otherwise display the user list
	function index()
	{
		
		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		elseif (miltone_check('admin') == FALSE && miltone_check('HR Manager')== FALSE)
		{
			//redirect them to the home page because they must be an administrator to view this
			redirect(site_url(), 'refresh');
		}
		else
		{
			//set the flash data error message if there is one
			//$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

        $config["base_url"] = base_url()."index.php/auth/index";
        $config["total_rows"] = $this->db->count_all('users');
        $config["per_page"] = 20;
        $config["uri_segment"] = 3;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);
        $this->pagination->initialize($config);
        $key = $this->input->post('key');
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
       $this->data['links'] = $this->pagination->create_links();
			//list the users
			$this->data['users'] = $this->ion_auth->users()->result();
			foreach ($this->data['users'] as $k => $user)
			{
				$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
			}
	
			$this->data['content']='auth/index';
			$this->load->view('hr/template', $this->data);
		}
	}

	//authenticate user from ehms system
	function authenticate_user_from_ehms()
	{

		$ems_uid =$this->uri->segment(3);//get user id

		$this->session->set_userdata('ems_user_name', $this->uri->segment(4)); //set user name session

		$this->session->set_userdata('user_id', $ems_uid);//set user id session
		
		// $gp_id = $this->db->get_where('groups',array('name'=>$group))->result();
		$group = $this->db->select('name')->from('groups')
						->join('users_groups_ehms', 'users_groups_ehms.group_id = groups.id', 'inner')
						->where('ehms_user_id',$ems_uid)->get()->result();

		$group_name=$group[0]->name;


		$this->session->set_userdata('group_name', $group_name);

		if($group_name=='admin'||$group_name=='HR Manager'||$group_name=='Head Department'||$group_name=='Normal Employee'){
			redirect('hr','refresh');
		}elseif($group_name=='account'){
			redirect('account','refresh');
		}else{
			redirect('auth/login', 'refresh'); 
		}

		
	}

	//log the user in
	function login()
	{
		$this->data['title'] = "Login";

		//validate form input
		$this->form_validation->set_rules('identity', 'Identity', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == true)
		{ //check to see if the user is logging in
			//check for "remember me"
			$remember = (bool) $this->input->post('remember');

			if ($this->ion_auth->login($this->input->post('identity'),$this->input->post('password')))
			{ //if the login is successful
				//redirect them back to the home page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				//redirect($this->config->item('base_url'), 'refresh');

				//if($this->ion_auth->in_group('HR')){
				if($this->ion_auth->is_admin() || $this->ion_auth->in_group('HR Manager') || $this->ion_auth->in_group('Head Department') || $this->ion_auth->in_group('Normal Employee')){
					redirect('hr','refresh');
				}else if($this->ion_auth->in_group('account')){
					redirect('account','refresh');
				}
				
			}
			// elseif ($this->) {
			// 	$result = mysql_query("select * from tbl_branches b, tbl_branch_employee be, tbl_employee e, tbl_privileges p, tbl_department dep
			// 	where b.branch_id = be.branch_id and
			// 	e.employee_id = be.employee_id and
			// 	dep.department_id = e.department_id
			// 	and e.employee_id = p.employee_id and p.Given_Username = '{$username}' and
			// 	p.Given_Password  = '{$password}' and b.Branch_Name = '{$branch}';") or die(mysql_error());

			// 	//DML excution select from..
			// 	$no=mysql_num_rows($result);
			// 	if($no > 0){
			// 	$row=mysql_fetch_assoc($result);
			// 	@session_start();	    
			// 	$_SESSION['userinfo']=$row;

			// 	$Branch_ID = $_SESSION['userinfo']['Branch_ID'];
			// }
			else
			{ //if the login was un-successful
				//redirect them back to the login page
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/login', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		}
		else
		{  //the user is not logging in so display the login page
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['identity'] = array('name' => 'identity',
				'id' => 'identity',
				'type' => 'text',
				'value' => $this->form_validation->set_value('identity'),
			);
			$this->data['password'] = array('name' => 'password',
				'id' => 'password',
				'type' => 'password',
			);

			$this->load->view('auth/login', $this->data);
		}
	}

	//log the user out
	function logout()
	{
		$this->data['title'] = "Logout";

		//log the user out
		$logout = $this->ion_auth->logout();

		//redirect them back to the page they came from
		redirect('auth', 'refresh');
	}

	//change password
	function change_password()
	{
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); 
		$this->form_validation->set_rules('old', 'Old password', 'required');
		$this->form_validation->set_rules('new', 'New Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', 'Confirm New Password', 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}
		
		$user = $this->ion_auth->user()->row();

		if ($this->form_validation->run() == false)
		{ //display the form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['old_password'] = array(
				'name' => 'old',
				'id'   => 'old',
				'type' => 'password',
			);
			$this->data['new_password'] = array(
				'name' => 'new',
				'id'   => 'new',
				'type' => 'password',
			);
			$this->data['new_password_confirm'] = array(
				'name' => 'new_confirm',
				'id'   => 'new_confirm',
				'type' => 'password',
			);
			$this->data['user_id'] = array(
				'name'  => 'user_id',
				'id'    => 'user_id',
				'type'  => 'hidden',
				'value' => $user->id,
			);

			//render
		       // $this->load->view('auth/change_password', $this->data);
                        $this->data['content']= 'auth/change_password';
                        $this->load->view('hr/template',  $this->data);
		}
		else
		{
			$identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));

			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change)
			{ //if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				$this->logout();
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/change_password','refresh');
			}
		}
	}

	//forgot password
	function forgot_password()
	{
		$this->form_validation->set_rules('email', 'Email Address', 'required');
		if ($this->form_validation->run() == false)
		{
			//setup the input
			$this->data['email'] = array('name' => 'email',
				'id' => 'email',
			);
			//set any errors and display the form
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->load->view('auth/forgot_password', $this->data);
		}
		else
		{
			//run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($this->input->post('email'));

			if ($forgotten)
			{ //if there were no errors
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("auth/forgot_password", 'refresh');
			}
		}
	}

	//reset password - final step for forgotten password
	public function reset_password($code)
	{
		$reset = $this->ion_auth->forgotten_password_complete($code);

		if ($reset)
		{  //if the reset worked then send them to the login page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("auth/login", 'refresh');
		}
		else
		{ //if the reset didnt work then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	//activate the user
	function activate($id, $code=false)
	{
		if ($code !== false)
			$activation = $this->ion_auth->activate($id, $code);
		else if ($this->ion_auth->is_admin())
			$activation = $this->ion_auth->activate($id);

		if ($activation)
		{
			//redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("auth", 'refresh');
		}
		else
		{
			//redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	//deactivate the user
	function deactivate($id = NULL)
	{
		// no funny business, force to integer
		$id = (int) $id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', 'confirmation', 'required');
		$this->form_validation->set_rules('id', 'user ID', 'required|is_natural');

		if ($this->form_validation->run() == FALSE)
		{
			// insert csrf check
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$this->data['user'] = $this->ion_auth->user($id)->row();

			$this->data['content'] ='auth/deactivate_user';
                        $this->load->view('hr/template',  $this->data);
		}
		else
		{
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes')
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
				{
					show_404();
				}

				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
				{
					$this->ion_auth->deactivate($id);
				}
			}

			//redirect them back to the auth page
			redirect('auth', 'refresh');
		}
	}

        
        
        //create a new user
	function create_user()
	{
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->data['title'] = "Create User";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		//validate form input
		$this->form_validation->set_rules('EmployeeID', 'Employee ID', 'required|xss_clean');
		$this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
		$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('department', 'Department', 'required');
		$this->form_validation->set_rules('company', 'Company Name', 'required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');

		if ($this->form_validation->run() == true)
		{
			$username = strtolower($this->input->post('username'));
			$email = $this->input->post('email');
			$password = $this->input->post('password');

			$additional_data = array('first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'company' => $this->input->post('company'),
				'EmployeeID' => $this->input->post('EmployeeID'),
				'department' => $this->input->post('department'),
				'phone' => $this->input->post('phone1') . '-' . $this->input->post('phone2') . '-' . $this->input->post('phone3'),
			);
		}
                
                $group = array( $this->input->post('group'));
		if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data,$group))
		{ //check to see if we are creating the user
			//redirect them back to the admin page
			$this->session->set_flashdata('message', "User Created");
			redirect("auth/create_user", 'refresh');
		}
		else
		{ //display the create user form
			//set the flash data error message if there is one
			$this->data['message'] = $this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message');

			$this->data['first_name'] = array('name' => 'first_name',
				'id' => 'first_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('first_name'),
			);
			$this->data['last_name'] = array('name' => 'last_name',
				'id' => 'last_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('last_name'),
			);
			$this->data['email'] = array('name' => 'email',
				'id' => 'email',
				'type' => 'text',
				'value' => $this->form_validation->set_value('email'),
			);
			$this->data['company'] = array('name' => 'company',
				'id' => 'company',
				'type' => 'text',
				'value' => (company_info()->Name <> '' ? company_info()->Name : $this->form_validation->set_value('company')),
			);
			$this->data['phone1'] = array('name' => 'phone1',
				'id' => 'phone1',
				'type' => 'text',
				'value' => $this->form_validation->set_value('phone1'),
			);
			$this->data['username'] = array('name' => 'username',
				'id' => 'username',
				'type' => 'text',
				'value' => $this->form_validation->set_value('username'),
			);
			$this->data['EmployeeID'] = array('name' => 'EmployeeID',
				'id' => 'EmployeeID',
				'type' => 'text',
				'value' => $this->form_validation->set_value('EmployeeID'),
			);
			$this->data['password'] = array('name' => 'password',
				'id' => 'password',
				'type' => 'password',
				'value' => $this->form_validation->set_value('password'),
			);
			$this->data['password_confirm'] = array('name' => 'password_confirm',
				'id' => 'password_confirm',
				'type' => 'password',
				'value' => $this->form_validation->set_value('password_confirm'),
			);
                        $this->data['group'] = $this->ion_auth_model->groups()->result();
                        $this->data['department']=  $this->db->get('department')->result();
			$this->data['content']='auth/create_user';
			$this->load->view('hr/template', $this->data);
                        
		}
	}
        
        
        
        
        //edit user account
	function user_edit()
	{
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->data['title'] = "Edit User";

                $id = $this->uri->segment(3);
                   
                 $user = $this->ion_auth_model->user($id)->result();
                 $this->data['user_data_id'] =$id;  
                   
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth', 'refresh');
		}

		//validate form input
		$this->form_validation->set_rules('EmployeeID', 'EmployeeID', 'required|xss_clean');
		$this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
		$this->form_validation->set_rules('email', 'Username', 'required');
		$this->form_validation->set_rules('department', 'Department', 'required');
		$this->form_validation->set_rules('group', 'Privilege', 'required');
		$this->form_validation->set_rules('phone1', 'Phone#', 'xss_clean');
		$this->form_validation->set_rules('company', 'Company Name', 'required|xss_clean');
                if($this->input->post('force')){
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');
                }
		if ($this->form_validation->run() == true)
		{
		
                    $password =null;
                        $username = $user[0]->username;//$this->input->post('email');
			$email = $this->input->post('email');
                         if($this->input->post('force')){
			$password = $this->input->post('password');
                         }
			$additional_data = array(
			        'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'company' => $this->input->post('company'),
				'EmployeeID' => $this->input->post('EmployeeID'),
				'department' => $this->input->post('department'),
				'phone' => $this->input->post('phone') ,
			);
			
			$group = $this->input->post('group'); 
		}
		if ($this->form_validation->run() == true)
		{ //check to see if we are creating the user
			//redirect them back to the admin page
                     $edit = $this->ion_auth_model->edit_user($id,$username, $email,$group,$password,$additional_data);
                     if($edit == 1){
			$this->session->set_flashdata('message', "User Information is successfully edited");
			redirect("auth/user_edit/".$id, 'refresh');
                     }else if($edit == 2){
                    $this->session->set_flashdata('message', "Username Already exist");
			redirect("auth/user_edit/".$id, 'refresh');
                         
                     }
		}
		else
		{ //display the create user form
			//set the flash data error message if there is one
			$this->data['message'] = (($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['first_name'] = array('name' => 'first_name',
				'id' => 'first_name',
				'type' => 'text',
				'value' => $user[0]->first_name,
			);
			$this->data['last_name'] = array('name' => 'last_name',
				'id' => 'last_name',
				'type' => 'text',
				'value' => $user[0]->last_name,
			);
			$this->data['email'] = array('name' => 'email',
				'id' => 'email',
				'type' => 'text',
				'value' => $user[0]->email,
			);
			$this->data['company'] = array('name' => 'company',
				'id' => 'company',
				'type' => 'text',
				'value' => $user[0]->company,
			);
			$this->data['phone1'] = array('name' => 'phone',
				'id' => 'phone',
				'type' => 'text',
				'value' => $user[0]->phone,
			);
			
                        $this->data['EmployeeID'] = array('name' => 'EmployeeID',
				'id' => 'EmployeeID',
				'type' => 'text',
				'value' => $user[0]->EmployeeID,
			);
                        
                        
			$this->data['password'] = array('name' => 'password',
				'id' => 'password',
				'type' => 'password',
				'value' => $this->form_validation->set_value('password'),
			);
			$this->data['password_confirm'] = array('name' => 'password_confirm',
				'id' => 'password_confirm',
				'type' => 'password',
				'value' => $this->form_validation->set_value('password_confirm'),
			);
                        
                        $g =   $this->ion_auth_model->get_users_groups($user[0]->id)->result();
                        $this->data['user_g'] =$g[0]->id;
                        $this->data['department_g']=$user[0]->department;
                       $this->data['group'] = $this->ion_auth_model->groups()->result();
                  $this->data['department']=  $this->db->get('department')->result();
			$this->data['content']='auth/edit_user';
			$this->load->view('hr/template', $this->data);
		}
	}
	
	
        
        
        
        
	function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	function _valid_csrf_nonce()
	{
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
				$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

}
