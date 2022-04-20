<?php
defined('BASEPATH') OR exit('No direct script access allowed');

 class Request_model extends CI_Model{
     
    public function m_check_bgt(){
       $where=array(
         'b_grf_code'=>$_POST['gfs_code'], 
         //'source_fund_ref'=>$_POST['source_fund'],
         'year_of_bgt'=>$_POST['bgt_year'],
         'activity_ref'=>$_POST['activity'],
         'cost_center_ref'=> $_POST['dept']  
       );
     $this->db->select('*');
     $this->db->from('budget');
     $this->db->where($where);
     $query=$this->db->get();
     $bgt['result']=$query->result_array();
     $bgt['count']=$query->num_rows();
     return $bgt;  
     }
     
    public function m_make_rqst_action()
      {
      $data=array(
      'ref_gfs_code'=>$_POST['gfs_code'],
      'amount'=>$_POST['needed_amount'],
      //'source_of_fund_id'=>$_POST['source_fund'],
      'rqst_bgt_year'=>$_POST['bgt_year'],
      'description'=>$_POST['activity'],
      'cost_centre'=>$_POST['dept']
      );

    $this->db->insert('request',$data);
    
      } 

   public function m_show_request($offset)
    {
     $this->db->select('*');
     $this->db->join('department','department.dept_id=request.cost_centre');
     $this->db->from('request');
    if($offset>0)
    {
     $this->db->limit('2',$offset); 
    }else
    {
    $this->db->limit('2'); 
    }  
     $this->db->order_by('date_posted','desc');
     $query=$this->db->get();
     return $query->result_array();
      
    }
	  
     public function m_slct_deduct_bgt($where)
      {

     $this->db->select('*');
     $this->db->from('budget');
     $this->db->where($where);
     $query=$this->db->get();
     return $query->result_array();
      
      }	

    public function m_deductFundAlloc($fund)
    {
     $this->db->select('*');
     $this->db->from('fund_allocation');
     $this->db->where($fund);
     $query=$this->db->get();
     return $query->result_array();



    }

    public function m_more_dokezo($zoo)
      {
        
     $this->db->select('*');
     $this->db->from('request');
     $this->db->join('grf_codes','grf_codes.code=request.ref_gfs_code');
     $this->db->join('department','department.dept_id=request.cost_centre');
     //$this->db->join('source_fund','source_fund.source_fund_id=request.source_of_fund_id');
     $this->db->join('activities','activities.activity_id=request.description');
     $this->db->where('request_id',$zoo);
     $query=$this->db->get();
     return $query->result_array();
      
      }
    public function m_activity_bgt($act,$dept,$gfs)
    {
      $where=array
      (
       'activity_ref'=>$act,
       'cost_center_ref'=>$dept,
      //'source_fund_ref'=>$src_fund,
       'b_grf_code'=>$gfs
      );
     $this->db->select('*');
     $this->db->from('budget');
     $this->db->where($where);
     $query=$this->db->get();
     return $query->result_array();  
    }
    public function m_count_dokezo()
      {
     $this->db->select('*');
     $this->db->from('request');
     //$this->db->where($where);
     $query=$this->db->get();
     return $query->num_rows();    
      }	
      
    public function m_show_activity()
    {
     $this->db->select('*');
     $this->db->from('activities');
   //$this->db->join('grf_codes','grf_codes.code=budget.b_grf_code');
     $query=$this->db->get();
     return $query->result_array();   
    }
    public function m_val_voucher($dokezo)
    {
     $this->db->select('*');
     $this->db->from('request');
     $this->db->join('grf_codes','grf_codes.code=request.ref_gfs_code');
     $this->db->join('department','department.dept_id=request.cost_centre');
//   $this->db->join('source_fund','source_fund.source_fund_id=request.source_of_fund_id');
     $this->db->join('activities','activities.activity_id=request.description');
     $this->db->where('request_id',$dokezo);
     $query=$this->db->get();
     return $query->result_array();
    }

    public function m_create_voucher_action()
      {
      $data=array(
      'vou_rqst_ref'=>$_POST['request_id'],
      'check_no'=>$_POST['check_no'],
      'written_by'=>$_SESSION['sess_id2']
      );
    $this->db->insert('vouchers',$data);
      } 
    public function m_more_voucher($oo)
     {      
     $this->db->select('*');
     $this->db->from('vouchers');
     $this->db->join('request','request.request_id=vouchers.vou_rqst_ref');
     $this->db->join('grf_codes','grf_codes.code=request.ref_gfs_code');
     $this->db->join('department','department.dept_id=request.cost_centre');
     $this->db->join('source_fund','source_fund.source_fund_id=request.source_of_fund_id');
     $this->db->join('activities','activities.activity_id=request.description');
     $this->db->where('voucher_id',$oo);
     $query=$this->db->get();
     //echo $this->db->last_query();
     return $query->result_array();     
      }

    public function m_view_voucher()
      {
     $this->db->select('*');
     $this->db->from('vouchers');
     $this->db->join('request','request.request_id=vouchers.vou_rqst_ref');
     $this->db->join('grf_codes','grf_codes.code=request.ref_gfs_code');
     $this->db->join('department','department.dept_id=request.cost_centre');
     $this->db->join('source_fund','source_fund.source_fund_id=request.source_of_fund_id');
     $this->db->join('activities','activities.activity_id=request.description');
     //$this->db->where('voucher_id',$zoo);
     $query=$this->db->get();
     //echo $this->db->last_query();
     return $query->result_array();     
      }

    public function m_approve_dokezo()
    {
        
        
    }      













 }