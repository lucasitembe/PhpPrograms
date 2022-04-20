<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->database();
		$this->load->helper('url');
                $this->load->model('hr_model', 'HR');
                $this->load->model('account_model', 'account');
                
                
                
                if ($this->session->userdata('loggedin')!='TRUE'|| empty($this->session->userdata('loggedin'))) {
                    //redirect them to the login page
                    redirect('auth/login', 'refresh');
                }

        }
        
        
        function index(){
            $this->data['content']='account/welcome';
    $this->load->view('account/template',$this->data);
        }
        
        
        function year($id =null){
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); 
            $this->form_validation->set_rules('name', 'Year', 'xss_clean|required|integer');
            if ($this->form_validation->run()){
                $insert=array(
                    'Name'=>  $this->input->post('name')
                        );
                $in=$this->account->add_year($insert,$id);
                if($in == 1){
                     $this->session->set_flashdata('message', 'Configuration saved !!');
                    redirect('account/year/','refresh');
                }else if($in == 2){
                    $this->data['error_in']='Duplicated entry!!';
                }else{
                    $this->data['error_in']='Record fail to be saved!!';
                }
            }
            if($id != null){
                $this->data['id']=$id;
                $this->data['yearinfo']=$this->account->year($id);
            }
    
            $this->data['yearlist']=  $this->account->year();
     $this->data['content']='account/config/year';
    $this->load->view('account/template',$this->data);
            
            
                    
        }

        function salaryitemconfig($id){
            
              if($this->input->post('SAVE')){
                $employees=$this->input->post('employee');
             //   $employer=$this->input->post('employer');
                foreach ($employees as $key => $value) {
                  $array=array(
                      'Employee'=>$key,
                      'EmployeeContribution'=>$value
                      //'EmployerNSSF'=>$employer[$key],
                  );  
                  
                  $add = $this->account->add_salary_item_config($array,$id);
                }
                $this->data['error_in']="Record saved !!";
            }
            
            
        $this->data['item']=  $this->account->salaryitem($id);    
        $this->data['employeelist']=  $this->account->employee_active_list();       
        $this->data['content']='account/salary/salaryitemconfig';
        $this->load->view('account/template',$this->data);    
        }



        function payee($id=null){
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); 
            $this->form_validation->set_rules('min', 'Minimum Amount', 'xss_clean|required|numeric');
            $this->form_validation->set_rules('max', 'Maximum Amount', 'xss_clean|required|numeric');
            $this->form_validation->set_rules('formular', 'Formula', 'xss_clean|required');
            if ($this->form_validation->run()){
                $insert=array(
                    'Min_Value'=>  $this->input->post('min'),
                    'Max_Value'=>  $this->input->post('max'),
                    'Formula'=> strtoupper($this->input->post('formular')),
                );
                $in=$this->account->add_payee($insert,$id);
                if($in){
                    $this->session->set_flashdata('message', 'Payee configuration saved !!');
                    redirect('account/payee/','refresh');
                }else{
                    $this->data['error_in']=' Fail to save payee configurations!!';
                }
            }
            if($id !=null){
                $this->data['id']=$id;
                $this->data['payee']=$this->account->payee($id);
            }
         $this->data['payeelist']=$this->account->payee();   
     $this->data['content']='account/config/payee_config';
    $this->load->view('account/template',$this->data);
        }
        
        
        function configureovertime($id){
            $this->data['id']=$id;
            
             $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); 
            $this->form_validation->set_rules('hours_amount', 'Rate', 'xss_clean|required|numeric');
           if ($this->form_validation->run()){
             $rate = array('Percent'=>  trim($this->input->post('hours_amount')));
             $this->db->update('salaryitem',$rate,array('id'=>$id));
             $this->data['error_in']="Data saved !";
           }
           
           $this->data['item']=  $this->account->salaryitem($id);    
           $this->data['content']='account/config/configovertime';
           $this->load->view('account/template',  $this->data);
        }


        function salaryitem($id=null){
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); 
            $this->form_validation->set_rules('name', 'Name', 'xss_clean|required');
            $this->form_validation->set_rules('category', 'Category', 'xss_clean|required');
            $cat = $this->input->post('category');
            $ledger = $this->input->post('ledger');

            if($cat == 2){
             $this->form_validation->set_rules('percent', 'Percent', 'xss_clean|required');   
            }
              if ($this->form_validation->run()){
                $insert=array(
                    'Name'=>  $this->input->post('name'),
                    'Category'=>  $this->input->post('category'),
                    'ladger_id'=>  $this->input->post('ledger'),
                    'Percent'=>  $this->input->post('percent'),
                    
                );
                $in=$this->account->add_salaryitem($insert,$id);
                if($in ==1){
                    $this->session->set_flashdata('message', 'Item name saved !!');
                    redirect('account/salaryitem/','refresh');
                }else{
                    $this->data['error_in']=' Fail to save Item name!! Duplicate Entry';
                }
            }
            if($id !=null){
                $this->data['id']=$id;
                $this->data['payee']=$this->account->salaryitem($id);
            }
         $this->data['payeelist']=$this->account->salaryitem();   
         $this->data['salarycategory']=$this->account->salarycategory();   
     $this->data['content']='account/config/salaryitem';
    $this->load->view('account/template',$this->data);
        }
        
        
        
        function payroll(){
             $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); 
             $this->form_validation->set_rules('month', 'Month', 'xss_clean|required');
             $this->form_validation->set_rules('year', 'Year', 'xss_clean|required');
              if ($this->form_validation->run()){
                  $mwez=$this->input->post('month');
                  $year=$this->input->post('year');
                  redirect('account/processsalary/'.$mwez.'/'.$year,'refresh');    
              }
              $this->data['yearlist']=$this->account->year();
              $this->data['content']='account/salary/payroll';
    
    $this->load->view('account/template',$this->data);   
        }
        
        
         function monthreport(){
             $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); 
             $this->form_validation->set_rules('month', 'Month', 'xss_clean|required');
             $this->form_validation->set_rules('year', 'Year', 'xss_clean|required');
              if ($this->form_validation->run()){
                  $mwezi=$this->input->post('month');
                  $year=$this->input->post('year');
                  $check_payroll=  $this->db->query("SELECT DISTINCT Employee FROM payroll WHERE Month= '$mwezi' AND Year='$year' ORDER BY Employee ASC")->result();
                  if(count($check_payroll) > 0){
                      include 'include/monthlyreport.php';
                      exit;
                  }else{
                      $this->data['error_in']='Payroll for selected criteria not found!!';
                  }
              }
              $this->data['yearlist']=$this->account->year();
              $this->data['content']='account/salary/monthly';
              $this->load->view('account/template',$this->data);  
        }

       
        function payslip(){
             $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); 
             $this->form_validation->set_rules('month', 'Month', 'xss_clean|required');
             $this->form_validation->set_rules('year', 'Year', 'xss_clean|required');
              if ($this->form_validation->run()){
                  $mwez=$this->input->post('month');
                  $year=$this->input->post('year');
                  $check_payroll=  $this->db->get_where('payroll',array('Month'=>$mwez,'Year'=>$year))->result();
                  if(count($check_payroll) > 0){
                  redirect('account/processpayslip/'.$mwez.'/'.$year,'refresh');    
                  }else{
                      $this->data['error_in']='Payroll for selected criteria not found!!';
                  }
              }
              $this->data['yearlist']=$this->account->year();
              $this->data['content']='account/salary/payslip';
    
    $this->load->view('account/template',$this->data);   
        }
        
        function processpayslip($mwez,$year){
            $this->data['mwezi']=$mwez;
            $this->data['year']=$year;
            $check_payroll=  $this->db->query("SELECT DISTINCT Employee FROM payroll WHERE Month= '$mwez' AND Year='$year' ORDER BY Employee ASC")->result();
            $this->data['payroll']=  $check_payroll;
            $this->data['content']='account/salary/processpayslip'; 
            $this->load->view('account/template',$this->data);   
        }



        function nssf(){
            if($this->input->post('SAVE')){
                $employees=$this->input->post('employee');
                $employer=$this->input->post('employer');
                foreach ($employees as $key => $value) {
                  $array=array(
                      'Employee'=>$key,
                      'EmployeeNSSF'=>$value,
                      'EmployerNSSF'=>$employer[$key],
                  );  
                  
                  $add = $this->account->add_nssf($array);
                }
            }
     $this->data['employeelist']=  $this->account->employee_active_list();       
    $this->data['content']='account/salary/nssf';
    $this->load->view('account/template',$this->data);   
        }




    function processsalary($mwez,$year){
    $this->data['mwezi']=$mwez;
    $this->data['year']=$year;
    if($this->input->post('SAVE')){
        $salaryitem=$this->account->salaryitem();
        $emplyee_data=array();
        foreach ($salaryitem as $key => $value) {
           $name=str_replace(' ', '', $value->Name);
            if($this->input->post('salaryid_'.$value->id)){
               $input=$this->input->post('salaryid_'.$value->id);
               $emplyee_data=$input;
               $salaryitem=get_salaryitemid($value->Name);
               foreach ($input as $k => $v) {
                   
               
               $array=array(
                    'Employee'=>$k,
                    'SalaryItem'=>$salaryitem,
                    'Amount'=>$v,
                    'Month'=>$mwez,
                    'Year'=>$year,
                );
                
                $this->account->add_payroll($array);
           }
            }
        }
        
        
        foreach ($emplyee_data as $key => $value) {
             $employee_info=  $this->db->get_where('employee_view',array('EmployeeId'=>$key))->result();
             // Add basic salary
             $array=array(
                    'Employee'=>$key,
                    'SalaryItem'=>1,
                    'Amount'=>$employee_info[0]->Amount,
                    'Month'=>$mwez,
                    'Year'=>$year,
                );
                $this->account->add_payroll($array); 
                
                
                // calculate payee
               $basic_salary = (double)$employee_info[0]->Amount;
                $pa = $this->db->get('payee_config')->result();
                $hamna=0;
                foreach ($pa as $k => $v) {
                    if($v->Min_Value <= $basic_salary && $basic_salary < $v->Max_Value){
                        $formula =str_replace('X', $basic_salary, $v->Formula);
                        $payee_data=eval("return ($formula);"); 

                        
                         $array=array(
                    'Employee'=>$key,
                    'SalaryItem'=>5,
                    'Amount'=>$payee_data,
                    'Month'=>$mwez,
                    'Year'=>$year,
                );
                         $this->account->add_payroll($array); 
                         $hamna=1;
                    }
                }
                
                // kama payee hana
                if($hamna == 0){
                  $array=array(
                    'Employee'=>$key,
                    'SalaryItem'=>5,
                    'Amount'=>0,
                    'Month'=>$mwez,
                    'Year'=>$year,
                );
                $this->account->add_payroll($array);             
                }
                
                //check nssf configuration
                $check_nssf=  $this->db->get_where('nssf',array('Employee'=>$key))->result();
                $mwen_nssf=0;
                $mwaj_nssf=0;
                if(count($check_nssf) == 1){
                    $mwen_nssf=$check_nssf[0]->EmployeeNSSF;
                    $mwaj_nssf=$check_nssf[0]->EmployerNSSF;
                }
                // NSSF
                  $array=array(
                    'Employee'=>$key,
                    'SalaryItem'=>9,
                    'Amount'=>$mwen_nssf,
                    'Month'=>$mwez,
                    'Year'=>$year,
                );
                $this->account->add_payroll($array);             
                // EMPLOYER NSSF
                  $array=array(
                    'Employee'=>$key,
                    'SalaryItem'=>10,
                    'Amount'=>$mwaj_nssf,
                    'Month'=>$mwez,
                    'Year'=>$year,
                );
                $this->account->add_payroll($array);             
                
                
                // loan
                        // $check_loan=$this->db->get_where('loanclose_view',array('Employee'=>$key,'is_close'=> 0,'TEST <= '=>($mwez+$year)))->result();
                         $check_loan=$this->db->get_where('loanclose_view',array('Employee'=>$key,'is_close'=> 0))->result();
                           $skip = $this->account->get_skip($key,$mwez,$year);      
                         if(count($check_loan) > 0 && count($skip) == 0){
                             $loan_data=  $this->db->get_where('loan',array('Employee'=>$key,'Loan_Number'=>$check_loan[0]->Loan_Number))->result();
                           $array=array(
                    'Employee'=>$key,
                    'SalaryItem'=>8,
                    'Amount'=>$loan_data[0]->Installment_Amount,
                    'Month'=>$mwez,
                    'Year'=>$year,
                );  
                           $this->account->add_payroll($array);  
                           
                           $array=array(
                               'Employeeid'=>$check_loan[0]->Employeeid,
                               'Employee'=>$key,
                               'Loan'=>$check_loan[0]->Loan_Number,
                               'Amount'=>$loan_data[0]->Installment_Amount,
                               'Month_D'=>$mwez,
                               'Year_D'=>$year,
                           );
                           
                           $this->account->add_repayment($array);
                         }
        }

  // The data to send to the API
         $ladger_name = array('SALARY EXPENSES'  ,'ACCRUED PAYROLL');
         $naration =array('PAYROLL FOR'.' '.strtoupper(month_generator($mwez)).', '.$year);
         $source_id =array($mwez.'/'.$year);
         $ladger_total=array($this->input->post('grandtotal'),-($this->input->post('grandtotal')));
         $user_id=array($this->input->post('userid'));
         $name=array($this->input->post('name'));
         $loan=
          $total_nssf=array($this->input->post('total_nssf'),'NSSF');
          $total_payee=array($this->input->post('total_payee'),'PAYEE');
          $total_loan=array($this->input->post('total_loan'),'Loan');

        
        $data = array(
        'ladger_name' => $ladger_name,
        'ladger_amount' => $ladger_total,
        'naration'=>$naration,
        'userid'=>$user_id,
        'source_id'=>$source_id,
        'name'=>$name,
        'nssf'=>$total_nssf,
        'payee'=>$total_payee,
        'loan'=>$total_loan
         );

        $endata = json_encode($data);
        $opts = array('http' =>
        array(
            'method' => 'GET',
            'header' => 'Content-type: application/json',
            'content' => $endata
        )
    );

    $context = stream_context_create($opts);
    $acc = file_get_contents("http://localhost/Final_One/gaccounting/Api/ledgerOnSalaryFromhr", false, $context);
   // echo "<script>alert('" . $acc . "')</script>";

    // echo $acc;
    // exit();
       
        
    $this->data['error_in']="Payroll processed successfully !!";
    $this->data['error_hrp']=$acc;
    }
    $this->data['employeelist']=  $this->account->employee_active_list();       
    $this->data['content']='account/salary/processsalary';
    $this->load->view('account/template',$this->data);
        }
       
        
        
        function printpayslip($employee_id,$mwezi,$year){
            include 'include/payslip.php';
            exit;
        }

        
    function employeelist_search($input=null){
        if($input !=null){
            $this->data['input']=$input;
        }
        $search=array();
        if(isset ($_POST['Search'])){
         $search['FirstName']=trim($this->input->post('fname'));   
         $search['LastName']=trim($this->input->post('lname'));   
         $search['EmployeeId']=trim($this->input->post('employee'));   
         $search['WorkStation']=trim($this->input->post('station'));   
         $search['Sex']=trim($this->input->post('sex'));   
         $search['Position']=trim($this->input->post('position'));   
         $search['Department']=trim($this->input->post('department'));   
         $search['Retere']=trim($this->input->post('status')); 
         if($search['Retere'] == 1){
             $search['Retere']=0;
         }else if($search['Retere']== 3){
             $search['Retere']=1;
         }else{
             unset ($search['Retere']);
         }
        }
        $config["base_url"] = base_url() . "index.php/account/employeelist_search";
        $config["total_rows"] = $this->HR->record_count('employee_view');
        
        $config["per_page"] = 20;
        $config["uri_segment"] = 3;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->data['links'] = $this->pagination->create_links();
        $this->data['employeetotalnumber'] = $this->HR->record_count('employee');        
   $this->data['employeelist']=  $this->HR->employee($search, $config["per_page"], $page);
   $this->data['station']=  $this->HR->workstation();
   $this->data['department']=  $this->HR->department();
   $this->data['position']=  $this->HR->position();
   $this->load->view('account/employeelist_search',  $this->data);
        
    }



    function skiprepayment(){
     $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); 
     
      $this->form_validation->set_rules('name', 'Employee Number', 'xss_clean|required');
      $this->form_validation->set_rules('month', 'Month', 'xss_clean|required');
      $this->form_validation->set_rules('year', 'Year', 'xss_clean|required');
        if ($this->form_validation->run()){
            $employee = check_employee_number(trim($this->input->post('name')));
            if(count($employee) > 0){
           $array=array(
               'Employee'=>  trim($this->input->post('name')),
               'Month'=>  trim($this->input->post('month')),
               'Year'=>  trim($this->input->post('year')),
           );
           
           $insert = $this->account->skiprepayment($array);
           if($insert){
               $this->session->set_flashdata('message','Information saved successfully');
               redirect('account/skiprepayment','refresh');
           }else{
               $this->data['error_in']='Duplicate entry !';
           }
            }else{
                $this->data['error_in']='Invalid Employee number';
            }
        }
      $this->data['skiprepayment']  =  $this->account->getSkipRepayment();
    $this->data['yearlist']=$this->account->year();
    $this->data['content']='account/loan/skiprepayment';
    $this->load->view('account/template',$this->data);
        }

        
        function deleteskiprepay($id){
            $this->db->delete('skiprepayment',array('id'=>$id));
            $this->session->set_flashdata('message','Data deleted !');
            redirect('account/skiprepayment','refresh');
        }

        function createloan($employee_id=null,$edit=null){
           
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); 
            if($employee_id == null){
             $this->form_validation->set_rules('employee_id', 'Employee Number', 'xss_clean|required');
             
               if ($this->form_validation->run()){
                   $number=trim($this->input->post('employee_id'));
                 $employee=check_employee_number($number);
                 if(count($employee) > 0){
                     redirect('account/createloan/'.$employee[0]->id,'refresh');
                 }else{
                     $this->data['error_in']='Employee Number does not exist !!';
                 }
                  
              }
              $this->data['content']='account/loan/createloan';
            }else{
                 
                $this->data['employee_id']=$employee_id;
                $this->data['edit_data']=$edit;
                $this->form_validation->set_rules('bamount', 'Base Amount', 'xss_clean|required|numeric');
                $this->form_validation->set_rules('iamount', 'Interest Amount', 'xss_clean|required|numeric');
                $this->form_validation->set_rules('term', 'Loan term', 'xss_clean|required|integer');
             
               if ($this->form_validation->run()){
                  
                     $bamount= trim($this->input->post('bamount'));
                      $term=  trim($this->input->post('term'));
                     $Interest=  trim($this->input->post('iamount'));
                     $loan_amount=$bamount+$Interest;
                     $installment = ($loan_amount/$term);
                     $em = $this->account->employee_basic_info($employee_id);
                     
                    // sio editing
                     if($edit == null){
                     $this->db->select_max('id');
                    $query = $this->db->get('loan')->result();
                    $loan_id=0;
                    if(count($query) == 1){
                        $loan_id='LN'.($query[0]->id+1);
                    }else{
                        $loan_id='LN1';
                    }
                   $array=array(
                       'Base_Amount'=>  trim($this->input->post('bamount')),
                       'Terms'=>  trim($this->input->post('term')),
                       'Interest'=>  trim($this->input->post('iamount')),
                       'Employeeid'=>  $employee_id,
                       'Employee'=>  $em[0]->EmployeeId,
                       'Loan_Amount'=>  $loan_amount,
                       'Loan_Number'=>  $loan_id,
                       'Installment_Amount'=>  $installment,
                   );
                     }else{ // hapa unaedit
                         
                      $array=array(
                       'Base_Amount'=>  trim($this->input->post('bamount')),
                       'Terms'=>  trim($this->input->post('term')),
                       'Interest'=> trim($this->input->post('iamount')),
                       'Loan_Amount'=>  $loan_amount,
                       'Installment_Amount'=>  $installment,
                   );   
                         
                     }
                     
                    $add= $this->account->add_loan($array,$edit);
                    
                    
                    if($add == 1){
                         $this->session->set_flashdata('message', 'Loan Created successfully !!');
                    redirect('account/createloan/'.$employee_id,'refresh');
                    }else if($add == 2){
                        $this->data['error_in']='Employee has open loan!!';
                    }
               }
               
                if($edit !=null){
                        $this->data['loan_info']=$this->account->get_employee_loan(null,null,$edit);
                    }
               $this->data['employee_loan']=  $this->account->get_employee_loan($employee_id);
                $this->data['content']='account/loan/createloanform';
            }
            
            
     
    $this->load->view('account/template',$this->data);
        }
      
        
        
       



        function approveloan(){
            $this->data['employee_loan']=  $this->db->get_where('loan',array('is_open'=>1,'is_approved'=>0))->result();
            $this->data['content']='account/loan/approve';
            $this->load->view('account/template',$this->data);
        }
        
        
        function repayment($id=null){
          if($id !=null){
                include 'include/repayment.php';
                exit;
            }
            
            $this->db->order_by('id','DESC');
            $this->data['employee_loan']=  $this->db->get_where('loan',array('is_approved'=>1,'delivery'=>1))->result();
            $this->data['content']='account/loan/repayment';
            $this->load->view('account/template',$this->data);   
        }
        


        function repaymentschedule($id=null){
             if($id !=null){
                include 'include/repaymentschedule.php';
                exit;
            }
            
            $this->db->order_by('id','DESC');
            $this->data['employee_loan']=  $this->db->get_where('loan',array('is_approved'=>1,'delivery'=>1))->result();
            $this->data['content']='account/loan/repaymentschedule';
            $this->load->view('account/template',$this->data);
        }
        
        function approveaction($loanid,$action){
            $this->db->update('loan',array('is_approved'=>$action),array('id'=>$loanid));
            if($action == 2){
            $this->db->update('loan',array('is_open'=>0),array('id'=>$loanid));    
            }
            redirect('account/approveloan','refresh');
        }
        
        function deliverloan($id=null){
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); 
            
            if($id !=null){
                $this->data['id']=$id;
                 $this->form_validation->set_rules('month', 'Month', 'xss_clean|required');
                 $this->form_validation->set_rules('year', 'Year', 'xss_clean|required');
                  $this->form_validation->set_rules('ledger', 'Ledger', 'xss_clean|required');

               if ($this->form_validation->run()){
                 $array=array(
                     'delivery'=>1,
                     'Month_D'=>  $this->input->post('month'),
                     'Year_D'=>  $this->input->post('year'),
                 ); 
                 $this->db->update('loan',$array,array('id'=>$id));
                 //send loan information to gaccounting
         $ladger_id = array($this->input->post('ledger'),'INTEREST REVENUE','CASH');
         $loan_amount =array($this->input->post('loan_amount'));
         $Interest=array($this->input->post('interest'));
         $base_amount=array($this->input->post('base_amount'));
         $source_id=array($this->input->post('source'));
         $user_id=array($this->input->post('userid'));
         $name=array($this->input->post('name'));
        
        
        $data = array(
        'ladger_id' => $ladger_id,
        'interest' =>$Interest,
        'loan_amount' => $loan_amount,
        'base_amount' => $base_amount,
        'userid'=>$user_id,
        'source_id'=>$source_id,
        'name'=>$name
         );

        $endata = json_encode($data);
        $opts = array('http' =>
        array(
            'method' => 'GET',
            'header' => 'Content-type: application/json',
            'content' => $endata
        )
    );

    $context = stream_context_create($opts);
    $acc = file_get_contents("http://localhost/Final_One/gaccounting/Api/ledgerLoanFromhr", false, $context);
   
    //echo $acc;

   // exit();
       





//end send loan information to gaccounting



                  $this->session->set_flashdata('message', ' Delivery Information saved!!');
                   $this->session->set_flashdata('message1', $acc);
                    redirect('account/deliverloan/'.$id,'refresh');
               }
               
              $this->data['content']='account/loan/deliveryaction';
            }else{
            $this->data['employee_loan']=  $this->db->get_where('loan',array('is_open'=>1,'is_approved'=>1,'delivery'=>0))->result();
            $this->data['content']='account/loan/delivery';
            }
            $this->load->view('account/template',$this->data);  
        }
        
       
        }
?>       