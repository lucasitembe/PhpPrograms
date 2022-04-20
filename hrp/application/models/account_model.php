<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_model extends CI_Model{
    
    function overtime_amount($employee,$mwezi, $year){
      $where ='';
        if($employee !=null){
            $where.=" AND Employee='$employee'";
        }
        if($mwezi !=null){
            $where.=" AND date>='$year-$mwezi-01'";
            $where.=" AND date<='$year-$mwezi-31'";
        }
         $sql='SELECT SUM(hours) as hours FROM overtime WHERE 1=1 '.$where;
        $d = $this->db->query($sql)->result();   
        if(count($d) > 0){
            return $d[0]->hours;
        }else{
            return 0;
        }
    }
    
    function get_employee_loan($employee_id=null,$employee=null,$id=null){
       if($employee_id !=null){
            $this->db->where('Employeeid',$employee_id);
        }
        if($employee !=null){
            $this->db->where('Employee',$employee);
        }
        
        if($id !=null){
            $this->db->where('id',$id);
        }
        
        $this->db->order_by('id','ASC');
        return $this->db->get('loan')->result();  
    }
    
    
    function skiprepayment($data){
        $check = $this->db->get_where('skiprepayment',$data)->result();
        if(count($check) > 0){
            return 2;
        }  else {
            $this->db->insert('skiprepayment',$data);
            return 1;
        }
    }

    function get_skip($employee,$month,$year){
        return $this->db->get_where('skiprepayment',array('Employee'=>$employee,'Month'=>$month,'Year'=>$year))->result();
    }

    function getSkipRepayment(){
        
        $sql = "SELECT s.id,s.Employee,s.Month,s.Year FROM skiprepayment as s INNER JOIN loanclose_view ON s.Employee=loanclose_view.Employee WHERE loanclose_view.is_close=0";
        return $this->db->query($sql)->result();
        
    }

    function check_config_salary_item($Employee,$salary_item){
        $check = $this->db->get_where('salaryitemconfig',array('Employee'=>$Employee,'salaryitem_id'=>$salary_item))->result();
        if(count($check) == 1){
            return $check[0];
        }else{
            return '';
        }
    }

    function add_repayment($data){
        $check = $this->db->get_where('loan_payment',array('Employeeid'=>$data['Employeeid'],'Employee'=>$data['Employee'],'Loan'=>$data['Loan'],'Month_D'=>$data['Month_D'],'Year_D'=>$data['Year_D']))->result();
        if(count($check) == 1){
            $this->db->update('loan_payment',$data,array('Employeeid'=>$data['Employeeid'],'Employee'=>$data['Employee'],'Loan'=>$data['Loan'],'Month_D'=>$data['Month_D'],'Year_D'=>$data['Year_D']));
        }else{
            $this->db->insert('loan_payment',$data);
        }
        
    }


    function add_loan($data,$edit=null){
        if($edit !=null){
            $this->db->update('loan',$data,array('id'=>$edit));
            return 1;
        }else{
            $check_open = $this->db->get_where('loan',array('is_open'=>1,'Employeeid'=>$data['Employeeid']))->result();
            if(count($check_open) > 0){
                return 2;
            }else{
                $this->db->insert('loan',$data);
                return 1;
            }
        }
    }

    function repayment($employeeid,$employee,$loan_id){
        $this->db->where('Employeeid',$employeeid);
        $this->db->where('Employee',$employee);
        $this->db->where('Loan',$loan_id);
        $this->db->order_by('Month_D','ASC');
        $this->db->order_by('Year_D','ASC');
        return $this->db->get('loan_payment')->result();
    }

    function employee_basic_info($id=null,$employee=null){
        if($id !=null){
            $this->db->where('id',$id);
        }
        if($employee !=null){
            $this->db->where('EmployeeId',$employee);
        }
        
        return $this->db->get('employee')->result();
    }


    function add_payroll($data){
        $check = $this->db->get_where('payroll',array('Employee'=>$data['Employee'],'SalaryItem'=>$data['SalaryItem'],'Month'=>$data['Month'],'Year'=>$data['Year']))->result();
        if(count($check) == 0){
            if($data['Amount'] == ''){
                $data['Amount']=0;
            }
            
            $this->db->insert('payroll',$data);
            
        }else if(count($check) == 1){
            if($data['Amount'] == ''){
                $data['Amount']=0;
            }
            $this->db->update('payroll',$data,array('Employee'=>$data['Employee'],'SalaryItem'=>$data['SalaryItem'],'Month'=>$data['Month'],'Year'=>$data['Year']));
        }
    }

    function add_salary_item_config($data,$salary_item){
      $check = $this->db->get_where('salaryitemconfig',array('Employee'=>$data['Employee'],'salaryitem_id'=>$salary_item))->result();
        if(count($check) > 0){
            $this->db->update('salaryitemconfig',$data,array('Employee'=>$data['Employee'],'salaryitem_id'=>$salary_item));
        }else{
            $data['salaryitem_id']=$salary_item;
            $this->db->insert('salaryitemconfig',$data);
        }  
    }

    function add_nssf($data){
        $check = $this->db->get_where('nssf',array('Employee'=>$data['Employee']))->result();
        if(count($check) > 0){
            $this->db->update('nssf',$data,array('Employee'=>$data['Employee']));
        }else{
            $this->db->insert('nssf',$data);
        }
    }


    function salaryitem_process($ctegory){
        $this->db->where('Category',$ctegory);
        return $this->db->get('salaryitem')->result();
    }


    function employee_active_list(){
        $this->db->where('Retere',0);
        return $this->db->get('employee_view')->result();
    }

    

    function payee($id=null){
        if($id !=null){
            $this->db->where('id',$id);
        }
        return $this->db->get('payee_config')->result();
    }
    function salarycategory($id=null){
        if($id !=null){
            $this->db->where('id',$id);
        }
        return $this->db->get('salarycategory')->result();
    }
    
    function salaryitem($id=null){
        if($id !=null){
            $this->db->where('id',$id);
        }
        return $this->db->get('salaryitem')->result();
    }
    
    function year($id=null){
        if($id !=null){
            $this->db->where('id',$id);
        }
        $this->db->order_by('Name','Desc');
        return $this->db->get('year')->result();
    }


    function add_year($data,$edit=null){
        $check=$this->db->get_where('year',$data)->result();
        if($edit !=null){
            if(count($check) == 0){
             $this->db->update('year',$data,array('id'=>$edit));
             return 1;
            }else if(count($check) == 1 && $check[0]->id==$edit){
                $this->db->update('year',$data,array('id'=>$edit));
             return 1;
            }else{
                return 2;
            }
        }else{
            if(count($check) == 0){
             $this->db->insert('year',$data);
             return 1;
            }else{
                return 2;
            }
        }
    }
    function add_payee($data,$edit=null){
        if($edit !=null){
            return $this->db->update('payee_config',$data,array('id'=>$edit));
        }else{
            return $this->db->insert('payee_config',$data);
        }
    }
    
    function add_salaryitem($data,$edit=null){
        $check = $this->db->get_where('salaryitem',$data)->result();
        if($edit !=null){
            if(count($check) == 0){
             $this->db->update('salaryitem',$data,array('id'=>$edit));
             return 1;
            }else if(count($check) == 1 && $check[0]->id == $edit){
                $this->db->update('salaryitem',$data,array('id'=>$edit));
             return 1;
            }else{
                return 2;
            }
        }else{
            if(count($check) == 0){
            $this->db->insert('salaryitem',$data);
            return 1;
            }else{
                return 2;
            }
        }
    }
    
    
}

?>