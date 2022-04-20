<?php
defined('BASEPATH') OR exit('No direct script access allowed');

 class Budget_model extends CI_Model{
 
     public function m_create_bgt_action()
      {
      $nbr=$_POST['nbr'];
      $unit_cost=$_POST['unit_cost'];
      $total=$nbr*$unit_cost;
      $data=array(
  
      'cost_center_ref'=>$_POST['c_center'],
      'objective'=>$_POST['objective'],
      'intervention'=>$_POST['intervene'],
      'activity_ref'=>$_POST['activity'],
      'source_fund_ref'=>$_POST['source_fund'],
      'b_grf_code'=>$_POST['gfs_code'],
      'unit_cost'=>$_POST['unit_cost'],
      'nbr'=>$_POST['nbr'],
      'when'=>$_POST['when'],
      'by_who'=>$_POST['by_who'],
      'year_of_bgt'=>$_POST['bgt_year'],
      'bgt_amount'=>$total,
      'bgt_amount_left'=>$total,

          
      );

    $this->db->insert('budget',$data);
    
      }   















}