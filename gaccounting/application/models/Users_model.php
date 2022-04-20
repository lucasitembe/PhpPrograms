<?php
defined('BASEPATH') OR exit('No direct script access allowed');

 class Users_model extends CI_Model{


   public function m_user_reg()
   {
     $data=array(
  
      'full_name'=>$_POST['full_name'],
      'username'=>$_POST['user_name'],
      'user_role'=>$_POST['user_role'],
      'user_title'=>$_POST['user_title'],
      'password'=>$_POST['user_name'],
      'user_dept'=>$_POST['user_dept']
          
      );

    $this->db->insert('chop_users',$data);
    

   }


      public function m_show_users()
      {


      $this->db->select('*');
      $this->db->from('chop_users');
      //$this->db->join('user_roles','user_roles.user_id_ref=chop_users.user_id');
      $this->db->join('chop_roles','chop_roles.role_id=chop_users.user_role');
      $this->db->join('department','department.dept_id=chop_users.user_dept');
      //$this->db->join('roles_permissions','roles_permissions.role_ref=chop_roles.role_id');
      //$this->db->join('access_level','access_level.access_id=roles_permissions.access_ref');
     $query=$this->db->get();
     $return=$query->result_array();
       
      return $return;
      
      }
     public function m_more_users($user)
      {


      $this->db->select('*');
      $this->db->from('chop_users');
      //$this->db->join('user_roles','user_roles.user_id_ref=chop_users.user_id');
      $this->db->join('chop_roles','chop_roles.role_id=chop_users.user_role');
      $this->db->join('department','department.dept_id=chop_users.user_dept');
      $this->db->join('roles_permissions','roles_permissions.role_ref=chop_roles.role_id');
      //$this->db->join('access_level','access_level.access_id=roles_permissions.access_ref');
      $this->db->where('user_id',$user);
      $query=$this->db->get();
      $return=$query->result_array();
       
      return $return;
      
      }

    public function m_show_roles()
    {
     $this->db->select('*');
     $this->db->from('chop_roles');
     $query=$this->db->get();
     return $query->result_array();
    
    }

































 }
 