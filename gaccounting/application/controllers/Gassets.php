<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Gassets extends CI_Controller {

    private $modifier;

    public function __construct() {
        parent::__construct();

        if (!isset($_SESSION['userinfo'])) {
            redirect('account/login');
        }

        $this->load->model('Gassets_model');
        $this->load->model('Supplier_model');
        $this->load->model('Gledgers');   
        $this->load->library('pagination');
        $this->modifier = NULL; //change this to user id if available
    }

    public function index() {
        $data['assets_counts'] = $this->Helper->record_count('tbl_assets');
        $data['location_counts'] = $this->Helper->record_count('tbl_locations');
        $data['category_counts'] = $this->Helper->record_count('tbl_asset_category');
        $this->load->view('shared/header');
        $this->load->view('gassets/index', $data);
        $this->load->view('shared/footer');
    }

    /*
     *
     * getting all categories from the assets category list
     * adding categories
     */

    public function categories() {
        if (isset($_POST['category_name'])) {
            $msg = "";
            $status = "";
            //process input form 
            $this->form_validation->set_rules('category_name', 'Category name', 'trim|required');
            $this->form_validation->set_rules('ledger_id', 'Ledger Name', 'required');

            if ($this->form_validation->run() == FALSE) {
                $msg = validation_errors();
            } else {
                //table attributes to be inserted
                $attributes = array(
                    'cat_name' => $_POST['category_name'],
                    'ledger_id' => $_POST['ledger_id'],
                    'modifier' => $this->modifier //this is user id if available
                );

                $result = $this->Gassets_model->createCategory($attributes);

                if ($result == 'success') {
                    $msg = "Inserted successifully";
                    $status = '1';
                } else {
                    if ($result == '23000/1062') {
                        $msg = "Category already exists.";
                    } else {
                        $msg = "An error has occured.Please try again";
                    }

                    $status = '0';
                }
            }

            echo json_encode(array('tokenhash' => $this->security->get_csrf_hash(), 'data' => $msg, 'status' => $status));
            //redirect('gassets/categories');
        } else {
            //display currencies
            $config = array();
            $config["base_url"] = base_url() . "Gassets/categories";
            $config["total_rows"] = $this->Helper->record_count('tbl_asset_category');


            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

            $data['categoryList'] = $this->Gassets_model->getAssetCategories($this->pagination->per_page, $page);
            $data["links"] = $this->pagination->create_links();
            $data["assetsLedgers"] = $this->Helper->getLedgersBySecId(1);
            $data["page"] = $page;


            $this->load->view('shared/header');
            $this->load->view('gassets/categories', $data);
            $this->load->view('shared/footer');
        }
    }

    /**
     * validating the phone number
     *
     */
    public function validate_phone($str) {
        $number_len = strlen($str);
        if ($number_len >= 10 and $number_len <= 13) {
            return true;
        } else {
            $this->form_validation->set_message('validate_phone', 'The {field} is not valid');
            return false;
        }
    }

    /*
     *
     * Assets locations
     *
     */

    public function assetlocations() {
        if (isset($_POST['loc_name'])) {
            $msg = "";
            $status = "";
            //process input form 
            $this->form_validation->set_rules('loc_name', 'Location Name', 'trim|required');
            $this->form_validation->set_rules('address', 'Address', 'trim|required');
            $this->form_validation->set_rules('country_id', 'Country', 'trim|required');
            $this->form_validation->set_rules('phone', 'Phone', 'trim|required|callback_validate_phone');
            $this->form_validation->set_rules('fax', 'Fax', 'trim');
            $this->form_validation->set_rules('email', 'E-mail', 'trim|required|valid_email');

            if ($this->form_validation->run() == FALSE) {
                $msg = validation_errors();
            } else {
                //table attributes to be inserted
                $attributes = array(
                    'loc_name' => $_POST['loc_name'],
                    'address' => $_POST['address'],
                    'country_id' => $_POST['country_id'],
                    'phone' => $_POST['phone'],
                    'fax' => $_POST['fax'],
                    'email' => $_POST['email'],
                    'modifier' => $this->modifier
                );

                $result = $this->Gassets_model->createLocation($attributes);

                if ($result == 'success') {
                    $msg = "Inserted successifully";
                    $status = '1';
                } else {
                    if ($result == '23000/1062') {
                        $msg = "Location already exists.";
                    } else {
                        $msg = "An error has occured.Please try again";
                    }

                    $status = '0';
                }
            }

            echo json_encode(array('tokenhash' => $this->security->get_csrf_hash(), 'data' => $msg, 'status' => $status));
            //redirect('gassets/categories');
        } else {
            //display currencies
            $config = array();
            $config["base_url"] = base_url() . "Gassets/assetlocations";
            $config["total_rows"] = $this->Helper->record_count('tbl_locations');


            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

            $data['locationList'] = $this->Gassets_model->getLocations($this->pagination->per_page, $page);
            $data["links"] = $this->pagination->create_links();
            $data["page"] = $page;
            $data['countries'] = $this->Helper->getCountries();


            $this->load->view('shared/header');
            $this->load->view('gassets/assetlocations', $data);
            $this->load->view('shared/footer');
        }
    }

    
    /*public function uploadImage($image_name,$image_tmp_name){
        if(isset($image_name) && isset($image_tmp_name)){

        }
    }*/
    /*
     *
     * Assets management
     *
     */

    public function assets() {

        if (isset($_POST['short_description'])) {

            $msg = "";
            $status = "";
            //process input form 
            $this->form_validation->set_rules('asset_category', 'Short Description', 'trim|required');
            $this->form_validation->set_rules('serial_number', 'Serial Number', 'trim|required');
            $this->form_validation->set_rules('short_description', 'Short Description', 'trim|required');
            $this->form_validation->set_rules('long_description', 'Long Description', 'trim');
            $this->form_validation->set_rules('location_id', 'Asset Location', 'trim|required');
            $this->form_validation->set_rules('depreciation', 'Depreciation Type', 'trim|required');
            $this->form_validation->set_rules('depreciation_rate', 'Depreciation Rate', 'trim|required');
            $this->form_validation->set_rules('depreciation_period', 'Depreciation Period', 'trim|required');
            $this->form_validation->set_rules('salvage_value', 'Salvage Value', 'trim');
            $this->form_validation->set_rules('purchase_date', 'Purchase Date', 'trim|required');

            $this->form_validation->set_rules('purchase_price', 'Purchase Price', 'trim|required');
            $this->form_validation->set_rules('currency_id', 'Currency', 'trim|required');

            $this->form_validation->set_rules('insurance_cover', 'Insurance Cover', 'trim');
            $this->form_validation->set_rules('warrant_info', 'Warrant Info', 'trim');
            $this->form_validation->set_rules('date_placed_in_service', 'Date Placed in Service', 'trim');
            $this->form_validation->set_rules('maintanence_schedule', 'Maintanence Schedule', 'trim');
            $this->form_validation->set_rules('maintanence_reminder', 'Maintanence Reminder', 'trim');
            $this->form_validation->set_rules('supplier_id', 'Asset Supplier', 'trim|required');
            $this->form_validation->set_rules('supplier_name1', '', '');
            $this->form_validation->set_rules('acquisition_method', 'Acquisition Method', 'trim');
            $this->form_validation->set_rules('disposition_method', 'Disposition Method', 'trim');
            $this->form_validation->set_rules('fund', 'Funds', 'trim');
            $this->form_validation->set_rules('asset_users', 'Asset Users', 'trim');
            $this->form_validation->set_rules('image_upload', 'Asset image', '');



            if ($this->form_validation->run() == FALSE) {
                $msg = validation_errors();
            } else {
                //table attributes to be inserted serial_number
                $supp = '';
                if(isset($_POST['supplier_id']) && $_POST['supplier_id']!='0'){
                    $supp = $_POST['supplier_id'];
                } else {
                    $supp = null;
                }

                //manipulating image details
                $generated_image_name = '';
                if(isset($_FILES['image_upload']['name']) && $_FILES['image_upload']['name']!=''){
                    $image_type = explode('/', $_FILES['image_upload']['type']);
                    $extension = '';
                    if(isset($image_type[1])){
                        $ext = $image_type[1];
                        if($ext=='jpeg'){
                            $extension = 'jpg';
                        } else {
                            $extension = 'png';
                        }
                    } else {
                        //set the deault extenstion to be png
                        $extension = 'png';
                    }
                    $generated_image_name = md5('asset'.date('Ymdhms').$_FILES['image_upload']['name']).'.'.$extension;
                    $image_path = 'assets/images/'.$generated_image_name;
                } else {
                    $image_path = '';
                }
                
                $attributes = array(
                    'asset_catg' => $_POST['asset_category'],
                    'asset_serial_number' => $_POST['serial_number'],
                    'asset_short_desc' => $_POST['short_description'],
                    'asset_long_desc' => $_POST['long_description'],
                    'asset_bar_code' => $_POST['barcode'],
                    'asset_loc' => $_POST['location_id'],
                    'depn_type' => $_POST['depreciation'],
                    'depn_rate' => $_POST['depreciation_rate'],
                    'depriacation_period' => $_POST['depreciation_period'],
                    'salvage_value' => $_POST['salvage_value'],
                    'purchase_date' => $_POST['purchase_date'],
                    'purchase_price' => $_POST['purchase_price'],
                    'currency_id' => $_POST['currency_id'],
                    'insurance_cover' => $_POST['insurance_cover'],
                    'warrant_information' => $_POST['warrant_info'],
                    'date_placed_in_service' => $_POST['date_placed_in_service'],
                    'maintanence_schedule' => $_POST['maintanence_schedule'],
                    'maintanence_reminder' => $_POST['maintanence_reminder'],
                    'supplier_id' => $supp,
                    'supplierName' => $_POST['supplier_name1'],
                    'modifier' => $this->modifier, //this is user id if available
                    'fund' => $_POST['fund'], 
                    'asset_users' => $_POST['asset_users'], 
                    'acquisition_method' => $_POST['acquisition_method'], 
                    'disposition_method' => $_POST['disposition_method'], 
                    'asset_image' => $image_path, 
                );

                $result = $this->Gassets_model->createAsset($attributes);

                if ($result['status'] == 'success') {

                    //upload the image file to asset/images/ directory
                    if($generated_image_name!='' && isset($_FILES['image_upload']['tmp_name'])){
                        $destination = 'assets/images/'.$generated_image_name;
                        move_uploaded_file($_FILES['image_upload']['tmp_name'],$destination);
                    }
                    
                    //update the corresponding ledger
                    //get ledger id for supplier and credit(-) amount
                     //get ledger id for asset and debit(+) amount
                   
                   


                    $msg = "Inserted successifully. Transaction refrence number : #".$result['trans_ref_no'];
                    $status = '1';
                } else {
                    if ($result == '23000/1062') {
                        $msg = "Asset already exists.";
                    } else if($result == 'ledgerNotFound'){
                        $msg = 'No ledger entry found for supplier';
                    } else {
                        $msg = "An error has occured.Please try again";
                    }

                    $status = '0';
                }
            }

            echo json_encode(array('tokenhash' => $this->security->get_csrf_hash(), 'data' => $msg, 'status' => $status));
            //redirect('gassets/categories');
        } else {
            //display Assets
            $config = array();
            $config["base_url"] = base_url() . "Gassets/assets";
            $config["total_rows"] = $this->Helper->record_count('tbl_assets');


            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

            $data['assetList'] = $this->Gassets_model->getAssetList($this->pagination->per_page, $page);

            $data["links"] = $this->pagination->create_links();
            $data["page"] = $page;

            $data['locations'] = $this->Gassets_model->getAllLocations();
            $data['currencies'] = $this->Helper->getCurrencies();
            $data['categories'] = $this->Gassets_model->getAssetCategories(0, 0);
            $data['suppliers'] = $this->Supplier_model->suppliersList(0, 0);
            $data['ehms_suppliers'] = $this->Helper->getRemoteList("supplier");
            $data['model'] = $this;

            $this->load->view('shared/header');
            $this->load->view('gassets/assets', $data);
            $this->load->view('shared/footer');
        }
    }

    /**
     * enable asset location method
     */
    public function enableAssetslocation() {
        $id = $this->uri->segment(3);
        if (isset($id) && !empty($id)) {
            $this->Gassets_model->enableLocation($id, true);
        }
        redirect('gassets/assetlocations');
    }

    /**
     * disable asset location method
     */
    public function disableAssetslocation() {
        $id = $this->uri->segment(3);
        if (isset($id) && !empty($id)) {
            $this->Gassets_model->enableLocation($id, false);
        }
        redirect('gassets/assetlocations');
    }

    /**
     * enable asset category method
     */
    public function enableAssetsCategory() {
        $id = $this->uri->segment(3);
        if (isset($id) && !empty($id)) {
            $this->Gassets_model->enableCategory($id, true);
        }
        redirect('gassets/categories');
    }

    /**
     * disable asset category method
     */
    public function disableAssetsCategory() {
        $id = $this->uri->segment(3);
        if (isset($id) && !empty($id)) {
            $this->Gassets_model->enableCategory($id, false);
        }
        redirect('gassets/categories');
    }

    /**
     * update asset location method
     */
    public function updateAssetLocation() {
        if (isset($_POST['loc_id'])) {
            $msg = "";
            $status = "";
            //process input form 
            $this->form_validation->set_rules('loc_name', 'Location Name', 'trim|required');
            $this->form_validation->set_rules('address', 'Address', 'trim|required');
            $this->form_validation->set_rules('country_id', 'Country', 'trim|required');
            $this->form_validation->set_rules('phone', 'Phone', 'trim|required|callback_validate_phone');
            $this->form_validation->set_rules('fax', 'Fax', 'trim');
            $this->form_validation->set_rules('email', 'E-mail', 'trim|required|valid_email');

            if ($this->form_validation->run() == FALSE) {
                $msg = validation_errors();
            } else {
                //table attributes to be inserted
                $attributes = array(
                    'loc_name' => $_POST['loc_name'],
                    'address' => $_POST['address'],
                    'country_id' => $_POST['country_id'],
                    'phone' => $_POST['phone'],
                    'fax' => $_POST['fax'],
                    'email' => $_POST['email'],
                    'modified_date' => date('Y-m-d h:i:s'),
                    'modifier' => $this->modifier
                );

                $result = $this->Gassets_model->updateLocation($attributes, $_POST['loc_id']);

                if ($result == 'success') {
                    $msg = "Updated successifully";
                    $status = '1';
                } else {
                    if ($result == '23000/1062') {
                        $msg = "Location already exists.";
                    } else {
                        $msg = "An error has occured.Please try again";
                    }

                    $status = '0';
                }
            }

            echo json_encode(array('tokenhash' => $this->security->get_csrf_hash(), 'data' => $msg, 'status' => $status));
        } else {
            redirect('gassets/assetlocations');
        }
    }

    /**
     * update asset info method
     */
    public function updateForm($id) {

        $data['asset'] = $this->Gassets_model->getAssetById($id);
        $data['locations'] = $this->Gassets_model->getAllLocations();
        $data['currencies'] = $this->Helper->getCurrencies();
        $data['categories'] = $this->Gassets_model->getAssetCategories(0, 0);
        $data['suppliers'] = $this->Supplier_model->suppliersList(0, 0);
        //$data['id'] = $id;
        $this->load->view('gassets/updateForm', $data);
    }

    public function locationform($id) {

        $data['location'] = $this->Gassets_model->getLocationById($id);

        $data['countries'] = $this->Helper->getCountries();


        //$data['id'] = $id;
        $this->load->view('gassets/locationform', $data);
    }

    public function updateAsset() {
        if (isset($_POST['asset_id'])) {

            $msg = "";
            $status = "";
            //process input form 
            $this->form_validation->set_rules('asset_category', 'Short Description', 'trim|required');
            $this->form_validation->set_rules('serial_number', 'Serial Number', 'trim|required');
            $this->form_validation->set_rules('short_description', 'Short Description', 'trim|required');
            $this->form_validation->set_rules('long_description', 'Long Description', 'trim|required');
            $this->form_validation->set_rules('location_id', 'Asset Location', 'trim|required');
            $this->form_validation->set_rules('depreciation', 'Depreciation Type', 'trim|required');
            $this->form_validation->set_rules('depreciation_rate', 'Depreciation Rate', 'trim|required');
            $this->form_validation->set_rules('depreciation_period', 'Depreciation Period', 'trim|required');
            $this->form_validation->set_rules('salvage_value', 'Salvage Value', 'trim|required');
            $this->form_validation->set_rules('purchase_date', 'Purchase Date', 'trim|required');

            $this->form_validation->set_rules('purchase_price', 'Purchase Price', 'trim|required');
            $this->form_validation->set_rules('currency_id', 'Currency', 'trim|required');

            $this->form_validation->set_rules('insurance_cover', 'Insurance Cover', 'trim');
            $this->form_validation->set_rules('warrant_info', 'Warrant Info', 'trim');
            $this->form_validation->set_rules('date_placed_in_service', 'Date Placed in Service', 'trim|required');
            $this->form_validation->set_rules('maintanence_schedule', 'Maintanence Schedule', 'trim|required');
            $this->form_validation->set_rules('maintanence_reminder', 'Maintanence Reminder', 'trim|required');
            $this->form_validation->set_rules('supplier_id', 'Asset Supplier', 'trim|required');




            if ($this->form_validation->run() == FALSE) {
                $msg = validation_errors();
            } else {
                //table attributes to be inserted serial_number
                $attributes = array(
                    'asset_catg' => $_POST['asset_category'],
                    'asset_serial_number' => $_POST['serial_number'],
                    'asset_short_desc' => $_POST['short_description'],
                    'asset_long_desc' => $_POST['long_description'],
                    'asset_bar_code' => $_POST['barcode'],
                    'asset_loc' => $_POST['location_id'],
                    'depn_type' => $_POST['depreciation'],
                    'depn_rate' => $_POST['depreciation_rate'],
                    'depriacation_period' => $_POST['depreciation_period'],
                    'salvage_value' => $_POST['salvage_value'],
                    'purchase_date' => $_POST['purchase_date'],
                    'purchase_price' => $_POST['purchase_price'],
                    'currency_id' => $_POST['currency_id'],
                    'insurance_cover' => $_POST['insurance_cover'],
                    'warrant_information' => $_POST['warrant_info'],
                    'date_placed_in_service' => $_POST['date_placed_in_service'],
                    'maintanence_schedule' => $_POST['maintanence_schedule'],
                    'maintanence_reminder' => $_POST['maintanence_reminder'],
                    'supplier_id' => $_POST['supplier_id'],
                    'modifier' => $this->modifier, //this is user id if available
                    'modified_date' => date("Y-m-d h:i:s")
                );
                $result = $this->Gassets_model->updateAsset($attributes, $_POST['asset_id']);

                if ($result == 'success') {
                    $msg = "Updated successifully";
                    $status = '1';
                } else {
                    if ($result == '23000/1062') {
                        $msg = "Asset already exists.";
                    } else {
                        $msg = "An error has occured.Please try again";
                    }

                    $status = '0';
                }
            }

            echo json_encode(array('tokenhash' => $this->security->get_csrf_hash(), 'data' => $msg, 'status' => $status));
            //redirect('gassets/categories');
        } else {
            redirect("gassets/assets");
        }
    }

    //calculating the asser depreciation

    public function asset_depreciation() {

        $depn_date = $_GET['depn_date'];
        $asset_id = $_GET['asset_id'];
        $depn_info = $this->Helper->calculateDepreciation($asset_id, $depn_date);
        echo '<tr>';
        echo '<td><b>Purchase Amount</b></td>';
        echo '<td>' . number_format($depn_info['purchase_amount'], 2) . ' (' . $depn_info['currency_code'] . ')</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td><b>Depreciation Amount</b></td>';
        echo '<td>' . number_format($depn_info['depn_amount'], 2) . ' (' . $depn_info['currency_code'] . ')</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td><b>Carrying Amount</b></td>';
        echo '<td>' . number_format($depn_info['carrying_value'], 2) . ' (' . $depn_info['currency_code'] . ')</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td><b>Number of Days</b></td>';
        echo '<td>' . number_format($depn_info['day_diff'], 2) . ' days</td>';
        echo '</tr>';
    }

    public function categoryform($id) {

        $data['category'] = $this->Gassets_model->getCategoryById($id);
        $this->load->view('gassets/categoryform', $data);
    }

    /**
     * update asset category method
     */
    public function updateAssetCategory() {
        if (isset($_POST['cat_id'])) {
            $msg = "";
            $status = "";
            //process input form 
            $this->form_validation->set_rules('category_name', 'Category name', 'trim|required');


            if ($this->form_validation->run() == FALSE) {
                $msg = validation_errors();
            } else {
                //table attributes to be inserted
                $attributes = array(
                    'cat_name' => $_POST['category_name'],
                    'modified_date' => date('Y-m-d h:i:s'),
                    'modifier' => $this->modifier //this is user id if available
                );

                $result = $this->Gassets_model->updateCategory($attributes, $_POST['cat_id']);

                if ($result == 'success') {
                    $msg = "Updated successifully";
                    $status = '1';
                } else {
                    if ($result == '23000/1062') {
                        $msg = "Category already exists.";
                    } else {
                        $msg = "An error has occured.Please try again";
                    }

                    $status = '0';
                }
            }

            echo json_encode(array('tokenhash' => $this->security->get_csrf_hash(), 'data' => $msg, 'status' => $status));
            //redirect('gassets/categories');
        } else {
            redirect('gassets/categories');
        }
    }
    public function depreciationEntry(){

        

        $data['depnPerLedger'] = $this->getTotalDepnPerLedger();


        $this->load->view('shared/header');
        $this->load->view('gassets/depreciationentry', $data);
        $this->load->view('shared/footer');
    }

    /*
    *   Depreciation for each ledger under fixed Asset Method
    */
    public function getTotalDepnPerLedger(){
        $assets = $this->Gassets_model->getAssetList();
        $data['ledgers'] = $ledgers = $this->Helper->getLedgerByGroupName('FIXED ASSET');
        $depnPerLedger = array();
        if($assets){

        
        foreach ($ledgers as $ledger) {
            $total_asset_cost = 0;
            $total_dpn_amount = 0;
            $total_carrying_amount = 0;

            foreach ($assets as $asset) {
                if($asset->ledger_id==$ledger->ledger_id){
                    $depn_info = $this->Helper->calculateDepreciation($asset->asset_id, date('Y-m-d'));
                    $total_asset_cost += $depn_info['purchase_amount'];
                    $total_dpn_amount += $depn_info['depn_amount'];
                    $total_carrying_amount += $depn_info['carrying_value'];
                }
            }
            //setting depreciation info per ledger
            $depn_data = array(
                'ledger_id' => $ledger->ledger_id,
                'ledger_name' => $ledger->ledger_name,
                'acc_name' => $ledger->acc_name,
                'total_asset_cost' => $total_asset_cost,
                'total_dpn_amount' => $total_dpn_amount,
                'total_carrying_amount' => $total_carrying_amount,
            );
            //pusing those data to an array against ledger name
            array_push($depnPerLedger, $depn_data);
        }
    }

        return $depnPerLedger;
    }

    public function getNetDepnPerLedger(){
        $assets = $this->Gassets_model->getAssetList();
        $data['ledgers'] = $ledgers = $this->Helper->getLedgerByGroupName('FIXED ASSET');
        $depnPerLedger = array();
        if($assets){

       
        foreach ($ledgers as $ledger) {
            $total_asset_cost = 0;
            $total_dpn_amount = 0;
            $total_carrying_amount = 0;

            //getting last depreciation date
            $lastDepnEntry = $this->db->like('comment','Depreciation','both')->order_by('journal_date DESC')->limit(1)->get('tbl_journal_entry')->row();
            foreach ($assets as $asset) {
                if($asset->ledger_id==$ledger->ledger_id){

                    if(!is_null($asset->last_depn_date)){
                        $depn_info1 = $this->Helper->calculateDepreciation($asset->asset_id, date('Y-m-d'));
                        $depn_info2 = $this->Helper->calculateDepreciation($asset->asset_id,  $asset->last_depn_date);

                        $total_asset_cost += $depn_info1['purchase_amount'];
                        $total_dpn_amount += ($depn_info1['depn_amount']-$depn_info2['depn_amount']);
                        $total_carrying_amount += $depn_info1['carrying_value'];


                    } else {
                        $depn_info1 = $this->Helper->calculateDepreciation($asset->asset_id, date('Y-m-d'));

                        $total_asset_cost += $depn_info1['purchase_amount'];
                        $total_dpn_amount += $depn_info1['depn_amount'];
                        $total_carrying_amount += $depn_info1['carrying_value'];
                    }

                    //update the last depreciation date
                    $this->db->set('last_depn_date', date('Y-m-d'));
                    $this->db->where('asset_id', $asset->asset_id);
                    $this->db->update('tbl_assets');
                       
                }
            }
            //setting depreciation info per ledger
            $depn_data = array(
                'ledger_id' => $ledger->ledger_id,
                'ledger_name' => $ledger->ledger_name,
                'acc_name' => $ledger->acc_name,
                'total_asset_cost' => $total_asset_cost,
                'total_dpn_amount' => $total_dpn_amount,
                'total_carrying_amount' => $total_carrying_amount,
            );
            //pusing those data to an array against ledger name
            array_push($depnPerLedger, $depn_data);
        }

    }

        return $depnPerLedger;
    }

    public function sendDepreciationToAccouting(){
        //echo json_encode($this->getDepnPerLedger());

        

        
        $emp = $this->session->userinfo->fname.' '.$this->session->userinfo->lname.' ('.$this->session->userinfo->username.')';
        $this->db->trans_begin();
        $ledgers = $this->getNetDepnPerLedger();
        //create journal entry

        $je_attr = array(
            'comment' => 'Depreciation of fixed assets on '.date($this->Helper->getConfigValue('DefaultDateFormat')),
            'journal_date'=>date($this->Helper->getConfigValue('DefaultDateFormat')),
            'user_transactor' => $this->session->userinfo->user_id,
            'user_type' => '0',
            'Employee_name' => $emp,
        );

        $je = $this->db->insert('tbl_journal_entry',$je_attr);
        $je_id = $this->db->insert_id();
        $expense_ledg = $this->Helper->getLedgerByName('DEPRECIATION EXPENSES');
        $retaing_earn_ledg = $this->Helper->getLedgerByName('Retained Earnings');
        //traverse each ledger and create journal entry details
        if(isset($expense_ledg) || !empty($expense_ledg)){
            $expense_ledger = $expense_ledg->ledger_id;
            $retaing_earn_ledger = $retaing_earn_ledg->ledger_id;
            foreach ($ledgers as $ledger) {
                //create journal entry details for each ledger and credit(-) amount
                //create journal entry details for each ledger and debit(-) amount
                //inserting data to journal entry details
                            $asset_je_attr = array(
                                'trans_id' => $je_id,
                                'ledger_id' => $ledger['ledger_id'],
                                'amount' => -$ledger['total_dpn_amount'],
                                
                                'source_name' => 'local asset',
                                );

                            $this->db->insert('tbl_journal_entry_details',$asset_je_attr);

                            $expense_je_attr = array(
                                'trans_id' => $je_id,
                                'ledger_id' => $expense_ledger,
                                'amount' => $ledger['total_dpn_amount'],
                               
                                'source_name' => 'local expense',
                                );

                            $this->db->insert('tbl_journal_entry_details',$expense_je_attr);

                            $retaing_earn_ledg_attr = array(
                                'trans_id' => $je_id,
                                'ledger_id' => $retaing_earn_ledger,
                                'amount' => $ledger['total_dpn_amount'],
                                'entry_type' => 1,
                                'source_name' => 'local retaining earns',
                                );

                            $this->db->insert('tbl_journal_entry_details',$retaing_earn_ledg_attr);
            }
                //checking if everything went fine

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $response = array(
                        'status' => '500',
                        'message' => $this->db->error()['message'],  
                        );
                    echo json_encode($response);
                } else {
                    $this->db->trans_commit();
                    $response = array(
                        'status' => '200',
                        'message' => 'Success! Depreciation has been submitted to Accounting Successfully with the reference #'.$je_id,  
                        );
                    echo json_encode($response);
                }

        } else {
            $response = array(
                        'status' => '500',
                        'message' => 'Attention! There is somthing wrong with these transactions',  
                        );
            echo json_encode($response);
        }
            
        
    }
    public function getAssetsByLedgerId($ledger_id)
    {
        //echo 'ready to go ledger id = '.$ledger_id;

        if(isset($ledger_id) && !empty($ledger_id)){
            $data['assets'] = $this->Gassets_model->getAssetsByLedgerId($ledger_id);
            $data['model'] = $this;
            $data['ledger_id'] = $ledger_id;
            $data['ledger_name'] = $_GET['ledger_name'];
            $data['isReport'] = false;
            $this->load->view('gassets/view_assets_depreciation_by_ledger',$data);
        } 
        
    }

    public function assetByledgerReport($ledger_id,$ledger_name='')
    {
        $pdf_content = "<h1 style='text-align:center;'>ASSET DEPRECIATION REPORT</h1>";
        $data['assets'] = $this->Gassets_model->getAssetsByLedgerId($ledger_id);
        $data['model'] = $this;
        $data['isReport'] = true;
        $data['ledger_id'] = $ledger_id;
        $data['ledger_name'] = $_GET['ledger_name'];
        $pdf_content .= $this->load->view('gassets/view_assets_depreciation_by_ledger', $data, true);

        $this->Helper->pdfReport($pdf_content);
    }

    public function physicalCounting()
    {
        if(isset($_GET['search_key_word']) && $_GET['search_key_word']!=''){
            $search_term = $_GET['search_key_word'];
            $asset = $data['dt'] = $this->Gassets_model->getAssetBySearchTerm($search_term);
            $data['model'] = $this;
            if($asset){
                $this->load->view('gassets/reports/physical_counting_partial',$data);
            } else {
                echo "<hr><p style='color:red;font-size:18px;text-align:center;'>No record found!</p>";
            }
           
        } else {
            $this->load->view('shared/header');
            $this->load->view('gassets/physical_counting');
            $this->load->view('shared/footer');
        }
    }

    public function sendAssetTracking()
    {
        if(isset($_GET['available']) && $_GET['available']!=''){
            $emp = $this->session->userinfo->fname.' '.$this->session->userinfo->lname.' ('.$this->session->userinfo->username.')';
            $attr = array(
                'available' => $_GET['available'],
                'asset_id' => $_GET['asset_id'],
                'tracking_date' => date('Y-m-d'),
                'tracked_by' => $emp,
                );
            $this->db->insert('tbl_asset_tracking',$attr);
            echo 'done';
        }
    }
    public function assetTrackingReport(){
        if(isset($_GET['start_date']) && isset($_GET['end_date']) && $_GET['start_date']!='' && $_GET['end_date']!=''){
            $search_term = $_GET['search_key_word'];
    $dt = $data['assets'] = $this->Gassets_model->getAssettrackingByDateRange($_GET['start_date'],$_GET['end_date'],$search_term);
            if($dt){
                $this->load->view('gassets/reports/asset_tracking_report_partial',$data);
            } else {
                echo "<hr><p style='color:red;font-size:18px;text-align:center;'>No record found!</p>";
            }
            
        } else {
            $data['tracking_details'] = '';
            $this->load->view('shared/header');
            $this->load->view('gassets/reports/asset_tracking_report');
            $this->load->view('shared/footer');
        }
    }
    public function assetTracking()
    {
        if(isset($_GET['available']) && $_GET['available']!='' && isset($_GET['asset_id']) && $_GET['asset_id']!=''){
            $attr = array(
                'is_available' => $_GET['available'],
                );
            $this->db->where('asset_id',$_GET['asset_id']);
            $this->db->update('tbl_assets',$attr);
            echo 'done';
        }
    }

    public function assetList()
    {
        //display Assets
            $config = array();
            $config["base_url"] = base_url() . "Gassets/assets";
            $config["total_rows"] = $this->Helper->record_count('tbl_assets');


            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

            $asset_catg = '';
            $asset_loc = '';
            $key_word = '';

            if(isset($_GET['asset_catg'])){
                $asset_catg = $_GET['asset_catg'];
            } else {
                $asset_catg='';
            }

            if(isset($_GET['asset_loc'])){
                $asset_loc = $_GET['asset_loc'];
            } else {
                $asset_loc ='';
            }

            if(isset($_GET['key_word'])){
                $key_word = $_GET['key_word'];
            } else {
                $key_word = '';
            }

            $search_terms = array(
                'asset_catg' => $asset_catg,
                'asset_loc' => $asset_loc,
                'key_word' => $key_word,
                );

    $data['assetList'] = $this->Gassets_model->getAssetList(0, 0,$search_terms);

            $data["links"] = $this->pagination->create_links();
            $data["page"] = $page;

            $data['locations'] = $this->Gassets_model->getAllLocations();
            $data['currencies'] = $this->Helper->getCurrencies();
            $data['categories'] = $this->Gassets_model->getAssetCategories(0, 0);
            $data['suppliers'] = $this->Supplier_model->suppliersList(0, 0);
            $data['ehms_suppliers'] = $this->Helper->getRemoteList("supplier");
            $data['model'] = $this;

            
            $this->load->view('gassets/asset_list_partial', $data);
    }
}
