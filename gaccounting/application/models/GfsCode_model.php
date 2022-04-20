<?php
defined('BASEPATH') OR exit('No direct script access allowed');

 class GfsCode_model extends CI_Model{

    public function check_gfs_dup($check)
      {

     $this->db->select('*');
     $this->db->from('grf_codes');
     $this->db->where('code',$check);
     $query=$this->db->get();
     return $query->num_rows();
      
      } 

    public function m_gfs_code_action()
    {
      $data=array
      (
       'code'=>$_POST['gfs_code'],
       'grf_desc'=>$_POST['gfs_desc'],
       'unit'=>$_POST['gfs_unit']
 
      );
    $this->db->insert('grf_codes',$data);
    }
    public function gfs_insertCSV($data)
      {
       $this->db->insert('grf_codes', $data);
       //return $this->db->insert_id();
      }



















 
    }