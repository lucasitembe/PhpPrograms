<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

if(!function_exists('get_color_bystatus')){
    function get_color_bystatus($name){
        $CI = & get_instance();
        
        $data = $CI->db->get_where('kpi_status',array('name'=>$name))->result();
        if(count($data) == 0){
            return "#D54C78";
        }else{
            return $data[0]->Color;
        }
        
    }
}


if(!function_exists('month_generator')){
    function month_generator($id=null){
        $CI = & get_instance();
        $array=array(
            '1'=>'January','2'=>'February',
            '3'=>'March','4'=>'April',
            '5'=>'May','6'=>'June',
            '7'=>'Jully','8'=>'August',
            '9'=>'September','10'=>'October',
            '11'=>'November','12'=>'December',
        );
        if($id !=null){
            return $array[$id];
        }else{
            return $array;
        }
        }
}


if(!function_exists('employee_photo')){
    function employee_photo($id){
        $CI = & get_instance();
        $ph=$CI->db->get_where('employee',array('id'=>$id))->result();
        if(count($ph) == 1){
        return $ph[0]->photo;
        }else{
            return '';
        }
    }
}

if(!function_exists('company_info')){
    function company_info(){
        $CI = & get_instance();
        return $CI->db->get('company')->row();
       
    }
}

if(!function_exists('employee_basic_salary')){
    function employee_basic_salary($employee_id){
        $CI = & get_instance();
        $ph=$CI->db->get_where('employee',array('EmployeeId'=>$employee_id))->result();
        if(count($ph) == 1){
         $ph1=$CI->db->get_where('salary',array('Employee'=>$ph[0]->id))->result();
         if(count($ph1) == 1){
             return $ph1[0]->Amount;
         }else{
             return 0;
         }
        }else{
            return 0;
        }
    }
}

if(!function_exists('is_loan_close')){
    function is_loan_close($loan_id){
        $CI = & get_instance();
        $ph=$CI->db->get_where('loanclose_view',array('Loan_Number'=>$loan_id))->result();
        if(count($ph) == 1){
        return $ph[0]->is_close;
        }else{
            return '';
        }
    }
}


if(!function_exists('employee_NSSF')){
    function employee_NSSF($employee_number){
        $CI = & get_instance();
        $ph=$CI->db->get_where('nssf',array('Employee'=>$employee_number))->result();
        return $ph;
    }
}
if(!function_exists('employee_salaryitem')){
    function employee_salaryitem($employee_number,$salary_item){
        $CI = & get_instance();
        $ph=$CI->db->get_where('salaryitemconfig',array('Employee'=>$employee_number,'salaryitem_id'=>$salary_item))->result();
        return $ph;
    }
}

if(!function_exists('check_employee_number')){
    function check_employee_number($employee_number){
        $CI = & get_instance();
        $ph=$CI->db->get_where('employee',array('EmployeeId'=>$employee_number))->result();
        return $ph;
    }
}

if(!function_exists('employee_basic_data')){
    function employee_basic_data($id){
        $CI = & get_instance();
        $ph=$CI->db->get_where('employee',array('id'=>$id))->result();
        return $ph;
    }
}




if(!function_exists('get_payroll_item')){
    function get_payroll_item($employee,$month,$year,$item){
        $CI = & get_instance();
        $ph=$CI->db->get_where('payroll',array('Employee'=>$employee,'SalaryItem'=>$item,'Month'=>$month,'Year'=>$year))->result();
       if(count($ph) == 0){
           return '';
       }else{
           return $ph[0]->Amount;
       }
    }
}

if(!function_exists('get_salaryitemid')){
    function get_salaryitemid($itemname){
        $CI = & get_instance();
        $ph=$CI->db->get_where('salaryitem',array('Name'=>$itemname))->result();
        return $ph[0]->id;
    }
}
if(!function_exists('application_status')){
    function application_status($id=null){
        $array=array('New','Called for Interview','Attend Interview','Accepted','Rejected');
        if($id !=null){
            return $array[$id];
        }
        return $array;
    }
}


if(!function_exists('miltone_check')){
    function miltone_check($group){
        $CI = & get_instance();
        $gp_id = $CI->db->get_where('groups',array('name'=>$group))->result();
        if(count($gp_id) > 0){
            $id = $CI->session->userdata('user_id');
            $check = $CI->db->get_where('users_groups',array('user_id'=>$id,'group_id'=>$gp_id[0]->id))->result();
            if(count($check) > 0){
                return TRUE;
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }
}

if(!function_exists('user_from_ehms_check')){
    function user_from_ehms_check($group){
        $CI = & get_instance();
        $gp_id = $CI->db->get_where('groups',array('name'=>$group))->result();
        
        if(count($gp_id) > 0){
            $id = $CI->session->userdata('user_id');
            $check = $CI->db->get_where('users_groups_ehms',array('ehms_user_id'=>$id,'group_id'=>$gp_id[0]->id))->result();
            if(count($check) > 0){
                return TRUE;
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }
}
?>
