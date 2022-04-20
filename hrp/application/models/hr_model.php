<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Hr_model extends CI_Model {

    function exportdata($search=array()) {
        if (count($search) > 0) {
            foreach ($search as $key => $value) {
                if ($value == '') {
                    unset($search[$key]);
                } else {
                    $this->db->like($key, $value);
                }
            }
        }
        return $this->db->get('employee_view')->result();
    }
    
    
    
    function kpi_indicator_list_data($indicator_id=null,$category_id=null){
        $sql="SELECT ind.name,act.kpi_indicator,ind.id,act.Active FROM kpi_indicator as ind,kpi_category_indicator as act WHERE act.Active=1 AND ind.id=act.kpi_indicator";
        if($indicator_id != null){
            $sql.= ' AND ind.id='.$indicator_id;
        }
        
        if($category_id != null){
            $sql.= ' AND act.kpi_category='.$category_id;
        }
        
        return $this->db->query($sql)->result();
    }

    
    
    function kpi_general_report_raw($from,$to,$category,$department,$indicator=null){
      
        $employee = $this->db->get_where('employee_view',array('Department'=>$department))->result();
       
        $data=array();
        foreach ($employee as $key => $value) {
         $indica = $this->kpi_indicator_list($indicator, $category); 
         
         foreach ($indica as $xx => $yy) {
             $status = $this->kpistatuslist();
             foreach ($status as $aa => $bb) {
               $sql= "SELECT COUNT(kpi_value) as count_status FROM kpi_record WHERE date >= '$from' AND date <= '$to' AND kpi_category=".$category." AND kpi_indicator=".$yy->id." AND kpi_value=".$bb->id." AND employee_auto = $value->id";
                  $res = $this->db->query($sql)->result(); 
                 
                $data[$value->id][$yy->name][$bb->name]=$res[0]->count_status;   
                  
             }
           
         }
        }
        
   
        return $data;
        
    }

    
    function kpi_general_report($from, $to,$kpi_cat=null,$indicator_id=null){
        $department = $this->department();
        $kpi_category = $this->kpicategorylist($kpi_cat);
        $status = $this->kpistatuslist();
        $data=array();
        foreach ($kpi_category as $key => $value) {
        $indicator = $this->kpi_indicator_list($indicator_id, $value->id);
        foreach ($indicator as $ind => $vl) {
                foreach ($status as $stat_key => $stat_val){
                 $sql= "SELECT COUNT(kpi_value) as count_status FROM kpi_record WHERE date >= '$from' AND date <= '$to' AND kpi_category=".$value->id." AND kpi_indicator=".$vl->id." AND kpi_value=".$stat_val->id;
                  $res = $this->db->query($sql)->result(); 
                 
                 $data[$value->id][$vl->id][$stat_val->name]=$res[0]->count_status;   
                
            
            }
            
        }
             
        }
        
       
        return $data;
    }

    function getkpi_raw_data($data) {
        $return = array();
        $where = " Employee = '" . $data['Employee'] . "' AND ";


        if ($data['date'] != '') {

            $where.= " date >='" . $data['date'] . "' AND ";
        }

        if ($data['update'] != '') {
            $where.= " date <='" . $data['update'] . "'";
        }

        $sql = "SELECT DISTINCT kpi_indicator,kpi_category,date FROM kpi_record WHERE " . $where . ' ORDER BY kpi_category,date ASC';

       
        
        $kpi_indicator = $this->db->query($sql)->result();

        
        $kpi_status = $this->kpistatuslist();


        if (count($kpi_indicator) > 0) {
            foreach ($kpi_indicator as $key => $value) {
                $where2 = $where . " AND kpi_indicator='$value->kpi_indicator' AND kpi_category='$value->kpi_category' AND date='$value->date'";

                $sql2 = "SELECT count(kpi_value) as kpi_value FROM kpi_record WHERE ";
               
                foreach ($kpi_status as $k => $v) {
                    
                      $sql3= $sql2. " " . $where2 . " AND kpi_value='$v->id'";
                    
                    $available = $this->db->query($sql3)->result();
                   $return[$value->kpi_category][$value->kpi_indicator][$value->date][$v->id] = $available[0]->kpi_value;
                }
            }
        }

      
        return $return;
        
    }

    function get_assignedkpi($array_where) {
        return $this->db->get_where('kpi_record', $array_where)->result();
    }
    function get_assignedkpi_employee($array_where) {
        return $this->db->get_where('kpi_record_employee', $array_where)->result();
    }

    function assignkpi_employee($data_array) {
        foreach ($data_array as $key => $data) {

            $check = $this->db->get_where('kpi_record_employee', array(
                        'kpi_indicator' => $data['kpi_indicator'],
                        'Employee' => $data['Employee'],
                        'employee_auto' => $data['employee_auto'],
                        'date' => $data['date'],
                    ))->result();

            if (count($check) == 1) {

                $this->db->update('kpi_record_employee', $data, array(
                    'kpi_indicator' => $data['kpi_indicator'],
                    'Employee' => $data['Employee'],
                    'employee_auto' => $data['employee_auto'],
                    'date' => $data['date'],
                ));
            } else {
                $this->db->insert('kpi_record_employee', $data);
            }
        }
    }

    
    function assignkpi($data_array) {
        foreach ($data_array as $key => $data) {

            $check = $this->db->get_where('kpi_record', array(
                        'kpi_indicator' => $data['kpi_indicator'],
                        'Employee' => $data['Employee'],
                        'employee_auto' => $data['employee_auto'],
                        'date' => $data['date'],
                    ))->result();

            if (count($check) == 1) {

                $this->db->update('kpi_record', $data, array(
                    'kpi_indicator' => $data['kpi_indicator'],
                    'Employee' => $data['Employee'],
                    'employee_auto' => $data['employee_auto'],
                    'date' => $data['date'],
                ));
            } else {
                $this->db->insert('kpi_record', $data);
            }
        }
    }

    
    function add_indicator_category($data){
        $get_where = $this->db->get_where('kpi_category_indicator',$data)->result();
        if(count($get_where) == 1){
            $this->db->update('kpi_category_indicator',array('Active'=>1),array('id'=>$get_where[0]->id));
        }else{
           $data['createdon'] =date('Y-m-d');
           $data['createdby'] =$this->session->userdata('user_id');
           $this->db->insert('kpi_category_indicator',$data);
        }
    }


    function kpicategory_indicator($id=null,$cat_id=null,$indicator_id=null) {
        if ($id != null) {
            $this->db->where('id', $id);
        }
        if ($cat_id != null) {
            $this->db->where('kpi_category', $cat_id);
        }
        
        if ($indicator_id != null) {
            $this->db->where('kpi_indicator', $indicator_id);
        }
        
            $this->db->where('Active', 1);
        return $this->db->get('kpi_category_indicator')->result();
    }
    
    
    function kpicategorylist($id=null) {
        if ($id != null) {
            $this->db->where('id', $id);
        }
        return $this->db->get('kpi_category')->result();
    }

    function kpi_indicator_list($id=null) {
        if ($id != null) {
            $this->db->where('id', $id);
        }
     
        return $this->db->get('kpi_indicator')->result();
    }

    
    
    function add_kpi_indicator($data, $id=null) {
        $check = $this->db->get_where('kpi_indicator', $data)->result();
        if ($id != null) {
            if (count($check) == 0 || (count($check) == 1 && $check[0]->id == $id )) {
                $this->db->update('kpi_indicator', $data, array('id' => $id));
                return 1;
            } else {
                return 2;
            }
        } else {
            if (count($check) == 0) {
                $this->db->insert('kpi_indicator', $data);
                return 1;
            } else {
                return 2;
            }
        }
    }

    
    // this function is not used anymore, the KPI Category is added direct when adding Position.
    function add_kpi_category($data, $id=null) {
        $check = $this->db->get_where('kpi_category', array('name' => $data['name']))->result();
        if ($id != null) {
            if (count($check) == 0 || (count($check) == 1 && $check[0]->id == $id )) {
                $this->db->update('kpi_category', $data, array('id' => $id));
                return 1;
            } else {
                return 2;
            }
        } else {
            if (count($check) == 0) {
                $this->db->insert('kpi_category', $data);
                return 1;
            } else {
                return 2;
            }
        }
    }

    
    
    
    function kpistatuslist($id=null) {
        if ($id != null) {
            $this->db->where('id', $id);
        }
        return $this->db->get('kpi_status')->result();
    }

    function add_kpi_status($data, $id=null) {
        $check = $this->db->get_where('kpi_status', array('name' => $data['name']))->result();
        if ($id != null) {
            if (count($check) == 0 || (count($check) == 1 && $check[0]->id == $id )) {
                $this->db->update('kpi_status', $data, array('id' => $id));
                return 1;
            } else {
                return 2;
            }
        } else {
            if (count($check) == 0) {
                $this->db->insert('kpi_status', $data);
                return 1;
            } else {
                return 2;
            }
        }
    }

    function addovertime($data) {
        $check = $this->db->get_where('overtime', $data)->result();
        if (count($check) > 0) {
            return FALSE;
        } else {
            $this->db->insert('overtime', $data);
            return 1;
        }
    }

    function add_vacancy($data, $id=null) {

        if ($id != null) {
            return $this->db->update('vacancy', $data, array('id' => $id));
        } else {
            return $this->db->insert('vacancy', $data);
        }
    }

    function apply_job($data) {
        return $this->db->insert('application', $data);
    }

    function get_overtime($employee=null, $from=null, $to=null) {
        $where = '';
        if ($employee != null) {
            $where.=" AND Employee='$employee'";
        }
        if ($from != null) {
            $where.=" AND date>='$from'";
        }
        if ($to != null) {
            $where.=" AND date <='$to'";
        }
        $sql = 'SELECT * FROM overtime WHERE 1=1 ' . $where;
        return $this->db->query($sql)->result();
    }

    function candidate_list($search) {

        if (count($search) > 0) {

            foreach ($search as $key => $value) {
                if ($value == '' && $value != 0) {
                    unset($search[$key]);
                } else {
                    $this->db->like($key, $value);
                }
            }
        }



        return $this->db->get('application')->result();
    }

    function vacancy($id=null) {
        if ($id != null) {
            $this->db->where('id', $id);
        }
        $this->db->order_by('to_date', 'desc');
        return $this->db->get('vacancy')->result();
    }

    function discipline($id=null) {
        if ($id != null) {
            $this->db->where('id', $id);
        }
        return $this->db->get('discipline')->result();
    }

    function add_discipline($data, $edit=null) {
        if ($edit != null) {
            return $this->db->update('discipline', $data, array('id' => $edit));
        } else {
            return $this->db->insert('discipline', $data);
        }
    }

    
    
       function leavelist_roster($search=array(), $limit=null, $start=null) {

        if (count($search) > 0) {
            foreach ($search as $key => $value) {
                if ($value == '') {
                    unset($search[$key]);
                } else {
                    if ($value == 'Fromdate') {
                        $this->db->where('Fromdate', $value);
                    }

                    if ($value == 'Todate') {
                        $this->db->where('Todate', $value);
                    }
                    if ($value != 'Fromdate' && $value != 'Todate') {
                        $this->db->like($key, $value);
                    }
                }
            }
        }


        if ($limit != null) {
            $this->db->limit($limit, $start);
        }


        return $this->db->get('leaveroster')->result();
    }
    
    
    
    
    function leavelist($search=array(), $limit=null, $start=null) {

        if (count($search) > 0) {
            foreach ($search as $key => $value) {
                if ($value == '') {
                    unset($search[$key]);
                } else {
                    if ($value == 'Fromdate') {
                        $this->db->where('Fromdate', $value);
                    }

                    if ($value == 'Todate') {
                        $this->db->where('Todate', $value);
                    }
                    if ($value != 'Fromdate' && $value != 'Todate') {
                        $this->db->like($key, $value);
                    }
                }
            }
        }


        if ($limit != null) {
            $this->db->limit($limit, $start);
        }


        return $this->db->get('leave_view')->result();
    }

    function traininglist($search=array(), $limit=null, $start=null) {

        if (count($search) > 0) {
            foreach ($search as $key => $value) {
                if ($value == '') {
                    unset($search[$key]);
                } else {
                    if ($value == 'startdate') {
                        $this->db->where('startdate', $value);
                    }

                    if ($value == 'enddate') {
                        $this->db->where('enddate', $value);
                    }
                    if ($value != 'startdate' && $value != 'enddate') {
                        $this->db->like($key, $value);
                    }
                }
            }
        }


        if ($limit != null) {
            $this->db->limit($limit, $start);
        }


        return $this->db->get('training')->result();
    }

    function employee($search=array(), $limit=null, $start=null) {

        if (count($search) > 0) {
            foreach ($search as $key => $value) {
                if ($key == 'Retere') {
                    $this->db->where('Retere', $value);
                } else {
                    if ($value == '') {
                        unset($search[$key]);
                    } else {
                        $this->db->like($key, $value);
                    }
                }
            }
        }

        if ($limit != null) {
            $this->db->limit($limit, $start);
        }


        return $this->db->get('employee_view')->result();
    }

    function agegraph($all=null) {
        $less_20 = 0;
        $between_20_29 = 0;
        $between_30_39 = 0;
        $between_40_49 = 0;
        $between_50_59 = 0;
        $greater_59 = 0;
        if ($all != null) {
            $this->db->where('Retere', 0);
        }
        $employee = $this->db->get('employee_view')->result();
        if (count($employee) > 0) {
            foreach ($employee as $key => $value) {
                $dob = $value->dob;
                $current = date('Y-m-d');
                $diff = abs(strtotime($current) - strtotime($dob));
                $years = floor($diff / (365 * 60 * 60 * 24));
                if ($years < 20) {
                    $less_20++;
                } else if ($years >= 20 && $years < 30) {
                    $between_20_29++;
                } else if ($years >= 30 && $years < 40) {
                    $between_30_39++;
                } else if ($years >= 40 && $years < 50) {
                    $between_40_49++;
                } else if ($years >= 50 && $years < 60) {
                    $between_50_59++;
                } else {
                    $greater_59++;
                }
            }
        }
        $data['Below 20'] = $less_20;
        $data['20 - 29'] = $between_20_29;
        $data['30 - 39'] = $between_30_39;
        $data['40 - 49'] = $between_40_49;
        $data['50 - 59'] = $between_50_59;
        $data['Above 59'] = $between_50_59;
        return $data;
    }

    function educationgraph($all=null) {

        $listall = '';
        if ($all != null) {
            $listall = ' AND Retere=0';
        }
        $edu = $this->educationlevel();
        $data = array();
        foreach ($edu as $key => $value) {
            $id = $value->id;
            $d = $this->db->query("SELECT count(EducationLevel) AS EducationLevel FROM employee_view WHERE EducationLevel='$id' $listall")->result();
            if (count($d) > 0) {
                $data[$value->Name] = (double) $d[0]->EducationLevel;
            } else {
                $data[$value->Name] = (double) 0;
            }
        }

        $d = $this->db->query("SELECT count(EducationLevel) AS EducationLevel FROM employee_view WHERE EducationLevel=0  $listall")->result();
        if (count($d) > 0) {
            $data['Unknown'] = (double) $d[0]->EducationLevel;
        }
        return $data;
    }

    function departmentgraph($all=null) {
        $listall = '';
        if ($all != null) {
            $listall = ' AND Retere=0';
        }
        $depart = $this->department();
        $data = array();
        foreach ($depart as $key => $value) {
            $id = $value->id;
            $d = $this->db->query("SELECT count(Department) AS department FROM employee_view WHERE Department='$id'  $listall")->result();
            if (count($d) > 0) {
                $data[$value->Name] = (double) $d[0]->department;
            } else {
                $data[$value->Name] = (double) 0;
            }
        }

        $d = $this->db->query("SELECT Department FROM employee_view WHERE ISNULL(Department) $listall")->result();
        if (count($d) > 0) {
            $data['Unknown'] = (double) count($d);
        }
        return $data;
    }

    function gendergraph($all=null) {
        $listall = '';
        if ($all != null) {
            $listall = ' AND Retere=0';
        }
        $male = $this->db->query("SELECT count(Sex) AS M FROM employee_view WHERE Sex='M' $listall ")->result();
        $fmale = $this->db->query("SELECT count(Sex) AS F FROM employee_view WHERE Sex='F' $listall")->result();
        $Other = $this->db->query("SELECT count(Sex) AS O FROM employee_view WHERE Sex !='M' AND Sex !='F' $listall")->result();
        $data['Male'] = $male[0]->M;
        $data['Female'] = $fmale[0]->F;
        $data['Unknown'] = $Other[0]->O;
        return $data;
    }

    function adddependent($data) {
        $check = $this->db->get_where('dependent', array('Employee' => $data['Employee'], 'Name' => $data['Name']))->result();
        if (count($check) == 1) {
            $this->db->update('dependent', $data, array('Employee' => $data['Employee']));
            return 1;
        } else {
            $this->db->insert('dependent', $data);
            return 1;
        }
    }

    function dependent($employee) {
        $this->db->where('Employee', $employee);
        return $this->db->get('dependent')->result();
    }

    function contracttype($id=null) {
        if ($id != null) {
            $this->db->where('id', $id);
        }
        return $this->db->get('contracttype')->result();
    }

    function addemergency($data) {
        $check = $this->db->get_where('emergencycontact', array('Employee' => $data['Employee']))->result();
        if (count($check) == 1) {
            $this->db->update('emergencycontact', $data, array('Employee' => $data['Employee']));
            return 1;
        } else {
            $this->db->insert('emergencycontact', $data);
            return 1;
        }
    }

    function addjob($data) {
        $check = $this->db->get_where('job', array('Employee' => $data['Employee']))->result();
        if (count($check) == 1) {
            $this->db->update('job', $data, array('Employee' => $data['Employee']));
            return 1;
        } else {
            $this->db->insert('job', $data);
            return 1;
        }
    }

    function job($employee) {
        $this->db->where('Employee', $employee);
        return $this->db->get('job')->result();
    }

    function emergency($employee) {
        $this->db->where('Employee', $employee);
        return $this->db->get('emergencycontact')->result();
    }

    function promotioninfo($employee, $edit) {
        $this->db->where('Employee', $employee);
        $this->db->where('id', $edit);
        return $this->db->get('promotion')->result();
    }

    function attachmentinfo($employee, $edit) {
        $this->db->where('Employee', $employee);
        $this->db->where('id', $edit);
        return $this->db->get('attachment')->result();
    }

    function attachment($employee) {
        $this->db->where('Employee', $employee);
        return $this->db->get('attachment')->result();
    }

    function promotionlist($employee) {
        $this->db->where('Employee', $employee);
        return $this->db->get('promotion')->result();
    }

    function qualificationlist($employee) {
        $this->db->where('Employee', $employee);
        return $this->db->get('qualification')->result();
    }

    function qualificationinfo($employee, $id) {
        $this->db->where('Employee', $employee);
        $this->db->where('id', $id);
        return $this->db->get('qualification')->result();
    }

    function educationlevel($id=null) {
        if ($id != null) {
            $this->db->where('id', $id);
        }
        return $this->db->get('education')->result();
    }

    function addattachment($data, $edit=null) {

        if ($edit == null) {
            $this->db->insert('attachment', $data);
            return 1;
        } else {
            $this->db->update('attachment', $data, array('id' => $edit));
            return 1;
        }
    }

    function addqualification($data, $edit=null) {
        if ($edit == null) {
            $check = $this->db->get_where('qualification', array('EducationLevel' => $data['EducationLevel'], 'Employee' => $data['Employee']))->result();
            if (count($check) > 0) {
                return 2;
            } else {

                $this->db->insert('qualification', $data);
                return 1;
            }
        } else {
            $check = $check = $this->db->get_where('qualification', array('EducationLevel' => $data['EducationLevel'], 'Employee' => $data['Employee']))->result();
            if (count($check) == 1 && $check[0]->id == $edit) {

                $this->db->update('qualification', $data, array('id' => $edit));
                return 1;
            } else if (count($check) == 0) {

                $this->db->update('qualification', $data, array('id' => $edit));
                return 1;
            } else {

                return 2;
            }
        }
    }

    function addpromotion($data, $edit=null) {
        $date = $data['Startdate'];
        unset($data['Startdate']);

        if ($edit == null) {
            $check = $this->db->get_where('promotion', $data)->result();
            if (count($check) > 0) {
                return 2;
            } else {
                $data['Startdate'] = $date;
                $this->db->insert('promotion', $data);
                return 1;
            }
        } else {
            $check = $this->db->get_where('promotion', $data)->result();
            if (count($check) == 1 && $check[0]->id == $edit) {
                $data['Startdate'] = $date;
                $this->db->update('promotion', $data, array('id' => $edit));
                return 1;
            } else if (count($check) == 0) {
                $data['Startdate'] = $date;
                $this->db->update('promotion', $data, array('id' => $edit));
                return 1;
            } else {

                return 2;
            }
        }
    }

    function addcontact($data) {
        $check = $this->db->get_where('contact', array('Employee' => $data['Employee']))->result();
        if (count($check) == 1) {
            $this->db->update('contact', $data, array('Employee' => $data['Employee']));
            return 1;
        } else {
            $this->db->insert('contact', $data);
            return 1;
        }
    }

    function addsalary($data) {
        $check = $this->db->get_where('salary', array('Employee' => $data['Employee']))->result();
        if (count($check) == 1) {
            $this->db->update('salary', $data, array('Employee' => $data['Employee']));
            return 1;
        } else {
            $this->db->insert('salary', $data);
            return 1;
        }
    }

    function contact($employee) {
        $this->db->where('Employee', $employee);
        return $this->db->get('contact')->result();
    }

    function addpersoninfo($data, $id=null) {
        if ($id == null) {
            $check = $this->db->get_where('employee', array('EmployeeId' => $data['EmployeeId']))->result();
            if (count($check) == 0) {
                $this->db->insert('employee', $data);
                return $this->db->insert_id();
            } else {
                false;
            }
        } else {
            $check_jina = $this->db->get_where('employee', array('EmployeeId' => $data['EmployeeId']))->result();
            if (count($check_jina) == 1 && $check_jina[0]->id == $id) {
                $this->db->update('employee', $data, array('id' => $id));
                return $id;
            } else if (count($check_jina) == 0) {
                $this->db->update('employee', $data, array('id' => $id));
                return $id;
            } else {
                return false;
            }
        }
    }

    function salarygrade($id=null) {
        if ($id != null) {
            $this->db->where('id', $id);
        }
        return $this->db->get('salarygrade')->result();
    }

    function salaryinfo($employee) {

        $this->db->where('Employee', $employee);

        return $this->db->get('salary')->result();
    }

    function personinfo($id=null) {
        if ($id != null) {
            $this->db->where('id', $id);
        }
        return $this->db->get('employee')->result();
    }

    function traininghistory($id=null, $employee=null) {
        if ($id != null) {
            $this->db->where('id', $id);
        }
        if ($employee != null) {
            $this->db->where('Employee', $employee);
        }

        return $this->db->get('training')->result();
    }
    
    
    function leavehistory($id=null, $employee=null) {
        if ($id != null) {
            $this->db->where('id', $id);
        }
        if ($employee != null) {
            $this->db->where('Employee', $employee);
        }

        return $this->db->get('leave_info')->result();
    }
    
    function leavehistory_roster($id=null, $employee=null) {
        if ($id != null) {
            $this->db->where('id', $id);
        }
        if ($employee != null) {
            $this->db->where('Employee', $employee);
        }

        return $this->db->get('leaveroster')->result();
    }

    function marital($id=null) {
        if ($id != null) {
            $this->db->where('id', $id);
        }
        return $this->db->get('maritalstatus')->result();
    }

    function position($id=null) {
        if ($id != null) {
            $this->db->where('id', $id);
        }
        return $this->db->get('position')->result();
    }

    function addposition($data, $id=null) {
        if ($id == null) {
            $check = $this->db->get_where('position', array('Name' => $data['Name']))->result();
            if (count($check) == 0) {
                $this->db->insert('position', $data);
                $kpi_cat =array(
                    'name'=>$data['Name']
                );
                
                $this->db->insert('kpi_category', $kpi_cat);
                return 1;
            } else {
                return 2;
            }
        } else {
            $check_jina = $this->db->get_where('position', array('Name' => $data['Name']))->result();
            if (count($check_jina) == 1 && $check_jina[0]->id == $id) {
                $this->db->update('position', $data, array('id' => $id));
                $kpi_cat =array(
                    'name'=>$data['Name']
                );
                
                $this->db->update('kpi_category', $kpi_cat,array('id'=>$id));
                return 1;
            } else if (count($check_jina) == 0) {
                $this->db->update('position', $data, array('id' => $id));
                 $kpi_cat =array(
                    'name'=>$data['Name']
                );
                
                $this->db->update('kpi_category', $kpi_cat,array('id'=>$id));
                return 1;
            } else {
                return 2;
            }
        }
    }

    function assigntraining($data, $id=null) {
        $check_active = $this->db->get_where('training', $data)->result();
        if ($id == null) {
            if (count($check_active) > 0) {
                return 2;
            } else {
                $data['createdon']=date('Y-m-d');
                $data['createdby']= $this->session->userdata('user_id');
                $this->db->insert('training', $data);
                return 1;
            }
        } else {
            $this->db->update('training', $data, array('id' => $id));
            return 1;
        }
    }
    
    function assignleave($data, $id=null) {
        $check_active = $this->db->get_where('leave_info', array('Employee' => $data['Employee'], 'is_active' => 1))->result();
        if ($id == null) {
            if (count($check_active) > 0) {
                return 2;
            } else {
                $this->db->insert('leave_info', $data);
                return 1;
            }
        } else {
            $this->db->update('leave_info', $data, array('id' => $id));
            return 1;
        }
    }
    
    function assignleave_roster($data, $id=null) {
        $check_active = $this->db->query("SELECT * FROM leaveroster WHERE Employee ='". $data['Employee']."' AND Fromdate >= '".date('Y')."-01-01' AND Fromdate <='".date('Y')."-12-30'")->result();
        if ($id == null) {
            if (count($check_active) > 0) {
                return 2;
            } else {
                $this->db->insert('leaveroster', $data);
                return 1;
            }
        } else {
            $this->db->update('leaveroster', $data, array('id' => $id));
            return 1;
        }
    }

    function leavetype($id=null) {
        if ($id != null) {
            $this->db->where('id', $id);
        }
        return $this->db->get('leavetype')->result();
    }
    function trainingtype($id=null) {
        if ($id != null) {
            $this->db->where('id', $id);
        }
        return $this->db->get('trainingtype')->result();
    }

    function department($id=null) {
        if ($id != null) {
            $this->db->where('id', $id);
        }
        return $this->db->get('department')->result();
    }

    function adddepartment($data, $id=null) {
        if ($id == null) {
            $check = $this->db->get_where('department', array('Name' => $data['Name']))->result();
            if (count($check) == 0) {
                $this->db->insert('department', $data);
                return 1;
            } else {
                return 2;
            }
        } else {
            $check_jina = $this->db->get_where('department', array('Name' => $data['Name']))->result();
            if (count($check_jina) == 1 && $check_jina[0]->id == $id) {
                $this->db->update('department', $data, array('id' => $id));
                return 1;
            } else if (count($check_jina) == 0) {
                $this->db->update('department', $data, array('id' => $id));
                return 1;
            } else {
                return 2;
            }
        }
    }

    function addleavetype($data, $id=null) {
        if ($id == null) {
            $check = $this->db->get_where('leavetype', array('Name' => $data['Name']))->result();
            if (count($check) == 0) {
                $this->db->insert('leavetype', $data);
                return 1;
            } else {
                return 2;
            }
        } else {
            $check_jina = $this->db->get_where('leavetype', array('Name' => $data['Name']))->result();
            if (count($check_jina) == 1 && $check_jina[0]->id == $id) {
                $this->db->update('leavetype', $data, array('id' => $id));
                return 1;
            } else if (count($check_jina) == 0) {
                $this->db->update('leavetype', $data, array('id' => $id));
                return 1;
            } else {
                return 2;
            }
        }
    }

    
    function addtrainingtype($data, $id=null) {
        if ($id == null) {
            $check = $this->db->get_where('trainingtype', array('Name' => $data['Name']))->result();
            if (count($check) == 0) {
                $this->db->insert('trainingtype', $data);
                return 1;
            } else {
                return 2;
            }
        } else {
            $check_jina = $this->db->get_where('trainingtype', array('Name' => $data['Name']))->result();
            if (count($check_jina) == 1 && $check_jina[0]->id == $id) {
                $this->db->update('trainingtype', $data, array('id' => $id));
                return 1;
            } else if (count($check_jina) == 0) {
                $this->db->update('trainingtype', $data, array('id' => $id));
                return 1;
            } else {
                return 2;
            }
        }
    }

    function addlocation($data, $id=null) {
        if ($id == null) {
            $check = $this->db->get_where('location', array('Name' => $data['Name']))->result();
            if (count($check) == 0) {
                $this->db->insert('location', $data);
                return 1;
            } else {
                return 2;
            }
        } else {
            $check_jina = $this->db->get_where('location', array('Name' => $data['Name']))->result();
            if (count($check_jina) == 1 && $check_jina[0]->id == $id) {
                $this->db->update('location', $data, array('id' => $id));
                return 1;
            } else if (count($check_jina) == 0) {
                $this->db->update('location', $data, array('id' => $id));
                return 1;
            } else {
                return 2;
            }
        }
    }

    function religion($id=null) {
        if ($id != null) {
            $this->db->where('id', $id);
        }

        return $this->db->get('religion')->result();
    }

    function regions($id=null) {
        if ($id != null) {
            $this->db->where('id', $id);
        }
        $this->db->where('parent', 0);

        return $this->db->get('location')->result();
    }

    function district($regionid=null, $id=null) {
        if ($regionid != null) {
            $this->db->where('parent', $regionid);
        } else {
            $this->db->where('parent !=', 0);
        }

        if ($id != null) {
            $this->db->where('id', $id);
        }
        return $this->db->get('location')->result();
    }

    //function for geting total row in the table
    function record_count($table) {
        return $this->db->count_all($table);
    }

    //get list of workstation
    function workstation($id=null, $key=null, $limit=null, $start=null) {

        if ($id != NULL) {
            $this->db->where('id', $id);
        }
        if ($key != null || $key != '') {

            $this->db->like('Name', $key);
        }
        if ($limit != null) {
            $this->db->limit($limit, $start);
        }

        return $this->db->get('workstation')->result();
    }

    // add work station
    function addworkstation($data, $id) {
        if ($id == null) {
            $check_jina = $this->db->get_where('workstation', array('Name' => $data['Name']))->result();
            if (count($check_jina) > 0) {
                return 2;
            } else {
                $this->db->insert('workstation', $data);
                return 1;
            }
        } else {
            $check_jina = $this->db->get_where('workstation', array('Name' => $data['Name']))->result();
            if (count($check_jina) == 1 && $check_jina[0]->id == $id) {
                $this->db->update('workstation', $data, array('id' => $id));
                return 1;
            } else if (count($check_jina) == 0) {
                $this->db->update('workstation', $data, array('id' => $id));
                return 1;
            } else {
                return 2;
            }
        }
    }

}

?>