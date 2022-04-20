<?php
defined('BASEPATH') OR exit('No direct script access allowed');

 class Department_model extends CI_Model{
     
      public function m_reg_dept_action()
      {
      $data=array(
  
      'dept_name'=>$_POST['dept_name'],
      'dept_desc'=>$_POST['dept_desc']     
      );

    $this->db->insert('department',$data);
    
      }
     
     
     
     
     
     
     
     
     
     
     
     
 }