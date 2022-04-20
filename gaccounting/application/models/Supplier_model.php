<?php
/**
* Model name: Supplier_model	
* @author GPITG@2016
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier_model extends CI_model{
	public function __construct()
	{
		parent::__construct();
	}

	public function getSupplierById($id)
	{
		$this->db->select('*');
    	$this->db->from('tbl_supplier s,tbl_currency c,tbl_country ctr');
    	$this->db->where('ctr.country_id = s.country_id');
    	$this->db->where('s.currency_id = c.currency_id');
    	$this->db->where('supplier_id',$id);

    	$query = $this->db->get();
    	if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
	}

	public function suppliersList($limit=0, $start=0)
	{
		$this->db->limit($limit, $start);

    	$this->db->select('*');
    	$this->db->from('tbl_supplier s,tbl_currency c,tbl_country ctr');
    	$this->db->where('ctr.country_id = s.country_id');
    	$this->db->where('s.currency_id = c.currency_id');

    	$query = $this->db->get();
    	if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
	}

	public function createSupplier($attributes)
	{
		$result = $this->db->insert('tbl_supplier',$attributes);
    	if($result){
    		return 'success';
    	} else {
            return $this->db->error()['code'];
        }
	}
    public function updateSupplier($attributes,$id)
    {
        $this->db->where('supplier_id',$id);
        $result = $this->db->update('tbl_supplier',$attributes);
        if($result){
            return 'success';
        } else {
            return $this->db->error()['code'];
        }
    }
}