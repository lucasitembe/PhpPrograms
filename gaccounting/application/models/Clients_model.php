<?php
/**
* Model name: Supplier_model	
* @author GPITG@2016
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Clients_model extends CI_model{
	public function __construct()
	{
		parent::__construct();
	}

	public function getClientById($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_clients s');
        //$this->db->where('ctr.country_id = s.country_id');
        //$this->db->where('s.currency_id = c.currency_id');
        $this->db->where('client_id',$id);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }

	public function clientsList($limit, $start)
	{
		$this->db->limit($limit, $start);

    	$this->db->select('*');
    	$this->db->from('tbl_clients s');
       //$this->db->join('tbl_country ctr','ctr.country_id = s.country_id','left');
    	//$this->db->join('tbl_currency c','s.currency_id = c.currency_id','left');

    	$query = $this->db->get();
    	if (count($query)>0) {
            return $query->result();
        }
        return false;
	}

	public function createClients($attributes)
	{
		$result = $this->db->insert('tbl_clients',$attributes);
    	if($result){
    		return 'success';
    	} else {
            return $this->db->error()['code'];
        }
	}
    public function updateClients($attributes,$id)
    {
        $this->db->where('supplier_id',$id);
        $result = $this->db->update('tbl_clients',$attributes);
        if($result){
            return 'success';
        } else {
            return $this->db->error()['code'];
        }
    }

    public function addInvoiceDetailsCache($data){
        return $this->db->insert('tbl_invoice_cache',$data);
    }
    public function getInvoiceDetailsCache($client_id,$emp_id){
        $this->db->select("*")
                 ->from('tbl_invoice_cache')
                 ->where('client_id',$client_id)
                 ->where('emp_id',$emp_id);
        $query = $this->db->get();
        if($query){
            return $query->result();
        }
        return false;
    }
    public function getInvoiceListByClientId($client_id){
        $this->db->select(" distinct(ci.id),invoice_date_time,fname,lname,sum(amount) as amount")
                 ->from('tbl_client_invoice ci')
                 ->join('tbl_client_invoice_details','invoice_id=ci.id')
                 ->join('tbl_users','user_id=created_by','left')
                 ->where('client_id',$client_id)
                 ->group_by('ci.id');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_client_invoice($client_id){
                $this->db->select("invoice_date ,narration,user_id,client_id ,fname,lname,invoice_id,is_taxable ,amount,cid.id as idd")
                ->from('tbl_client_invoice ci')
                 ->join('tbl_client_invoice_details cid','invoice_id=ci.id')
                 ->join('tbl_users','user_id=created_by','left')
                 ->where('invoice_id',$client_id)
                 ->group_by('idd');
        $query = $this->db->get();
        return $query->result_array();


    }

    public function get_client_agig_report(){


         $this->db->select("client_id,date(invoice_date_time) trans_date,sum(amount) as amount")
                 ->from('tbl_client_invoice ci')
                 ->join('tbl_client_invoice_details','invoice_id=ci.id') 
                 ->group_by('client_id') 
                 ->group_by('date(invoice_date_time)')
                 ->order_by('amount', 'desc');
        $query = $this->db->get();
        return $query->result_array();



    }
}