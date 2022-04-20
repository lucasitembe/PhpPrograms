<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Gasset model
 *
 * @author gpitg
 */
class Gassets_model extends CI_Model {

	/**
	 * contstructor
	 * 
	 */
    public function __construct()
    {
        parent::__construct();

    }
    /**
	 * getting all categories
	 * 
	 */
    public function getAssetCategories($limit, $start)
    {
    	 $this->db->limit($limit, $start);
    	$query = $this->db->get('tbl_asset_category');
    	if ($query->num_rows() > 0) {
          
            return $query->result();
        }
        
        return false;
    }

    /**
	 * inserting data to tbl_assets
	 * 
	 */
    public function createAsset($attributes) {


    	

        $suppl_ledg = $this->Helper->getLedgerByName($attributes['supplierName']);
        $asset_ledg = $this->getCategoryById($attributes['asset_catg']);

               if((is_array($suppl_ledg) || !is_null($suppl_ledg) || !empty($suppl_ledg) ) && ( isset($asset_ledg) || !is_null($asset_ledg) || !empty($asset_ledg))){
                         $suppl_ledger = $suppl_ledg->ledger_id;
                         $asset_ledger = $asset_ledg->ledger_id;
                        $this->db->trans_begin();
                        //create the asset
                        $result = $this->db->insert('tbl_assets',$attributes);
                        $asset_id = $this->db->insert_id();
                        $emp = $this->session->userinfo->fname.' '.$this->session->userinfo->lname.' ('.$this->session->userinfo->username.')';
                       //create journal entry
                        $je_attr = array(
                            'comment' => 'Being puchased of fixed asset '.strtoupper($attributes['asset_short_desc']).', with Serial Number '.$attributes['asset_serial_number'],
                            'journal_date'=>date($this->Helper->getConfigValue('DefaultDateFormat')),
                            'user_transactor' => $this->session->userinfo->user_id,
                            'user_type' => '0',
                            'Employee_name' => $emp,
                            );
                        $je = $this->db->insert('tbl_journal_entry',$je_attr);
                        $je_id = $this->db->insert_id();

                        //inserting data to journal entry details
                        $suppl_je_attr = array(
                            'trans_id' => $je_id,
                            'ledger_id' => $suppl_ledger,
                            'amount' => -$attributes['purchase_price'],
                            'source_id' => $asset_id,
                            'source_name' => 'local supplier',
                            );
                        $this->db->insert('tbl_journal_entry_details',$suppl_je_attr);

                        $asset_je_attr = array(
                            'trans_id' => $je_id,
                            'ledger_id' => $asset_ledger,
                            'amount' => $attributes['purchase_price'],
                            'source_id' => $asset_id,
                            'source_name' => 'local asset',
                            );
                        $this->db->insert('tbl_journal_entry_details',$asset_je_attr);

                        //checking if everything went fine
                        if ($this->db->trans_status() === FALSE) {
                            $this->db->trans_rollback();
                            $result = array(
                                'status' => $this->db->error()['message'], 
                                'trans_ref_no'=> '',
                                );
                            return $result;
                        } else {
                            $this->db->trans_commit();
                            $result = array(
                                'status' => 'success', 
                                'trans_ref_no'=> $je_id,
                                );
                            return $result;
                        }
                       
                    } else {
                        return 'ledgerNotFound';
                    }




    	/*if($result){
    		return 'success';
    	} else {
            return $this->db->error()['code'];
        }*/
    }
    /**
    * updaing asset info
    */
    public function updateAsset($attributes,$asset_id)
    {
    	$this->db->where('asset_id',$asset_id);
    	$result = $this->db->update('tbl_assets',$attributes);
    	if($result){
    		return 'success';
    	} else {
            return $this->db->error()['code'];
        }
    }

    public function updateCategory($attributes,$cat_id)
    {
        $this->db->where('cat_id',$cat_id);
        $result = $this->db->update('tbl_asset_category',$attributes);
        if($result){
            return 'success';
        } else {
            return $this->db->error()['code'];
        }
    }

    public function updateLocation($attributes,$loc_id)
    {
        $this->db->where('loc_id',$loc_id);
        $result = $this->db->update('tbl_locations',$attributes);
        if($result){
            return 'success';
        } else {
            return $this->db->error()['code'];
        }
    }

    /**
    * getting the asset list from the tbl_assets table
    *
    */
    public function getAssetList($limit=0, $start=0,$search='')
    {
    	 $this->db->limit($limit, $start);

    	$this->db->select('*');
    	$this->db->from('tbl_assets a,tbl_locations l,tbl_currency c,tbl_asset_category ct');
    	$this->db->where('ct.cat_id = a.asset_catg');
    	$this->db->where('a.asset_loc = l.loc_id');
    	$this->db->where('a.currency_id = c.currency_id');
        $this->db->join('tbl_supplier s','a.supplier_id = s.supplier_id','left');
        //filter the assets list
        if(isset($search) && $search!=''){
            if($search['asset_catg']!=''){
                $this->db->where('a.asset_catg',$search['asset_catg']);
            } 
            if($search['asset_loc']!=''){
                $this->db->where('a.asset_loc',$search['asset_loc']);
            }
            if($search['key_word']!=''){
                $key_word = $search['key_word'];
                $this->db->where("a.asset_short_desc like '%$key_word%'");
                //$this->db->where("a.supplierName like '%$key_word%'");
            }
        }
        
    	$query = $this->db->get();
    	if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function getAssetsByLedgerId($ledger_id)
    {
        $this->db->select('*');
        $this->db->from('tbl_assets a,tbl_locations l,tbl_currency c,tbl_asset_category ct');
        $this->db->where('ct.cat_id = a.asset_catg');
        $this->db->where('a.asset_loc = l.loc_id');
        $this->db->where('a.currency_id = c.currency_id');
        $this->db->where('ledger_id',$ledger_id);
        $this->db->join('tbl_supplier s','a.supplier_id = s.supplier_id','left');
    	$query = $this->db->get();
    	if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    /**
    * getting asset by specific id
    */
    public function getAssetById($id){
    	$this->db->select('*');
    	$this->db->from('tbl_assets a,tbl_locations l,tbl_currency c,tbl_asset_category ct');
    	$this->db->where('ct.cat_id = a.asset_catg');
    	$this->db->where('a.asset_loc = l.loc_id');
    	$this->db->where('a.currency_id = c.currency_id');
    	$this->db->where('asset_id',$id);

    	$query = $this->db->get();
    	if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    

    /**
    * getting location by specific id
    */
    public function getLocationById($id){
        $this->db->select('*');
        $this->db->from('tbl_country c,tbl_locations l');
        $this->db->where('c.country_id=l.country_id');
        $this->db->where('loc_id',$id);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }

    /**
	 * inserting data to tbl_asset_category
	 * 
	 */
    public function createCategory($attributes) {
    	$result = $this->db->insert('tbl_asset_category',$attributes);
    	if($result){
    		return 'success';
    	} else {
            return $this->db->error()['code'];
        }
    }

    public function createLocation($attributes)
    {
    	$result = $this->db->insert('tbl_locations',$attributes);
    	if($result){
    		return 'success';
    	} else {
            return $this->db->error()['code'];
        }
    }

    public function getLocations($limit, $start)
    {
    	$this->db->limit($limit, $start);
    	$this->db->select('*');
    	$this->db->from('tbl_country c,tbl_locations l');
    	$this->db->where('c.country_id=l.country_id');
    	$query = $this->db->get();
    	if ($query->num_rows() > 0) {
          
            return $query->result();
        }
        
        return false;
    }

    public function getDepreciatedAssetById($asset_id)
    {
    	$this->db->select('*');
    	$this->db->from('tbl_asset_depreciation');
    	$this->db->where('asset_id',$asset_id);
    	$query = $this->db->get();
    	if($query->num_rows() > 0){
    		return $query->result();
    	}
    	return false;
    }

    public function getAllLocations(){
    	$query = $this->db->get('tbl_locations');
    	if($query->num_rows() > 0){
    		 return $query->result();
    	}
        return false;
    }

    public function enableLocation($id,$enable)
    {
    	$this->db->set('enable',$enable);
    	$this->db->where('loc_id',$id);
    	$this->db->update('tbl_locations');
    }

    public function enableCategory($id,$enable)
    {
    	$this->db->set('enable',$enable);
    	$this->db->where('cat_id',$id);
    	$this->db->update('tbl_asset_category');
    }

    public function getCategoryById($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_asset_category');
        $this->db->where('cat_id',$id);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->row();
        }
        return false;
    }

    public function getAssetBySearchTerm($search_term)
    {
        $query = $this->db->query("Select * from tbl_assets a left join tbl_asset_category ac on a.asset_catg=ac.cat_id left join tbl_locations l on l.loc_id=a.asset_loc left join tbl_currency c on c.currency_id=a.currency_id  where asset_serial_number='$search_term' OR asset_bar_code='$search_term' ");
        return $query->row();
    }

    public function getAssettrackingByDateRange($start_date,$end_date,$search_term='')
    {
        $this->db->select("*")
                 ->from('tbl_asset_tracking at')
                 ->join('tbl_assets a','a.asset_id=at.asset_id','left')
                 ->join('tbl_asset_category ac','ac.cat_id=a.asset_catg')
                 ->where('tracking_date >=', $start_date)
                 ->where('tracking_date <=', $end_date);
        if($search_term!=''){
            $this->db->where("asset_serial_number='$search_term' OR asset_bar_code='$search_term' OR asset_short_desc='$search_term'");
        }
        $query = $this->db->get();
        return $query->result();
    }

}