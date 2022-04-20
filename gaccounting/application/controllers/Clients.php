<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Clients extends CI_Controller {

    public function __construct() {
        parent::__construct();

        if (!isset($_SESSION['userinfo'])) {
            redirect('account/login');
        }

        $this->load->model('Clients_model');
        $this->load->library('pagination');
        $this->modifier = NULL; //change this to user id if available
    }

    public function index() {
        //this is the index page
        if (isset($_POST['client_name'])) {
            $msg = "";
            $status = "";
            //process input form 
            $this->form_validation->set_rules('client_name', 'Client Name', 'trim|required');
            $this->form_validation->set_rules('address', 'Address', 'trim|required');
            //$this->form_validation->set_rules('country', 'Country', 'trim|required');
            //$this->form_validation->set_rules('currency', 'Currency', 'trim|required');
            $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
            $this->form_validation->set_rules('fax', 'Fax', 'trim');
            $this->form_validation->set_rules('email', 'E-mail', 'trim|valid_email');

            $this->form_validation->set_rules('contact_name', 'Contact Name', 'trim|required');
            $this->form_validation->set_rules('contact_phone', 'Contact Phone', 'trim|required');
            $this->form_validation->set_rules('contact_email', 'Contact E-mail', 'trim|valid_email');
            $this->form_validation->set_rules('web_url', 'Website url', 'trim');
            $this->form_validation->set_rules('ledger_id', 'Ledger Name', 'trim');




            if ($this->form_validation->run() == FALSE) {
                $msg = validation_errors();
            } else {
                //table attributes to be inserted
                $attributes = array(
                    'client_name' => $_POST['client_name'],
                    'address' => $_POST['address'],
                    'fax' => $_POST['fax'],
                    'email' => $_POST['email'],
                    'telephone' => $_POST['phone'],
                    'url' => $_POST['web_url'],
                    'contactname' => $_POST['contact_name'],
                    'contact_phone' => $_POST['contact_phone'],
                     'contact_email' => $_POST['contact_email'],
                    'ledger_id'=>$_POST['ledger_id'],
                );

              $result = $this->Clients_model->createClients($attributes);

                if ($result == 'success') {
                    $msg = "Inserted successifully";
                    $status = '1';
                } else {
                    if ($result == '23000/1062') {
                        $msg = "Supplier already exists.";
                    } else {
                        $msg = "An error has occured.Please try again";
                    }

                    $status = '0';
                }
            }

            echo json_encode(array('tokenhash' => $this->security->get_csrf_hash(), 'data' => $msg, 'status' => $status));
            //redirect('gassets/categories');
        } else {
            //display Suppliers
            $config = array();
            $config["base_url"] = base_url() . "Clients/index";
            $config["total_rows"] = $this->Helper->record_count('tbl_clients');


            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

            $data['supplierList'] = $this->Clients_model->clientsList($this->pagination->per_page, $page);
            // echo '<pre/>';
            // print_r($data['supplierList']);
            // exit();


            $data["links"] = $this->pagination->create_links();
            $data["page"] = $page;

            // $data['currencies'] = $this->Helper->getCurrencies();
            // $data['countries'] = $this->Helper->getCountries();


            $this->load->view('shared/header');
            $this->load->view('clients/index', $data);
            $this->load->view('shared/footer');
        }
    }

    public function supplierform($id) {
        $data['supplier'] = $this->Clients_model->getSupplierById($id);
        // $data['currencies'] = $this->Helper->getCurrencies();
        // $data['countries'] = $this->Helper->getCountries();
        $this->load->view('clients/forms/supplierform', $data);
    }

    public function updateClient() {
        if (isset($_POST['supplier_id'])) {
            $msg = "";
            $status = "";
            //process input form 
            $this->form_validation->set_rules('client_name', 'Client Name', 'trim|required');
            $this->form_validation->set_rules('address', 'Address', 'trim|required');
            $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
            $this->form_validation->set_rules('fax', 'Fax', 'trim');
            $this->form_validation->set_rules('email', 'E-mail', 'trim|required|valid_email');

            $this->form_validation->set_rules('contact_name', 'Contact Name', 'trim|required');
            $this->form_validation->set_rules('contact_phone', 'Contact Phone', 'trim|required');
            $this->form_validation->set_rules('contact_email', 'Contact E-mail', 'trim|required|valid_email');
            $this->form_validation->set_rules('web_url', 'Website url', 'trim');
            $this->form_validation->set_rules('ledger_id', 'Ledger Name', 'trim');




            if ($this->form_validation->run() == FALSE) {
                $msg = validation_errors();
            } else {
                //table attributes to be inserted
                $attributes = array(
                    'suppliername' => $_POST['supplier_name'],
                    'address' => $_POST['address'],
                    'telephone' => $_POST['phone'],
                    'fax' => $_POST['fax'],
                    'email' => $_POST['email'],
                    'contact_email' => $_POST['contact_email'],
                    'contactname' => $_POST['contact_name'],
                    'contact_phone' => $_POST['contact_phone'],
                    'url' => $_POST['web_url'],
                     'ledger_id'=>$_POST['ledger_id']
                );

                $result = $this->Clients_model->updateSupplier($attributes, $_POST['supplier_id']);

                if ($result == 'success') {
                    $msg = "Updated successifully";
                    $status = '1';
                } else {
                    if ($result == '23000/1062') {
                        $msg = "Supplier already exists.";
                    } else {
                        $msg = "An error has occured.Please try again";
                    }

                    $status = '0';
                }
            }

            echo json_encode(array('tokenhash' => $this->security->get_csrf_hash(), 'data' => $msg, 'status' => $status));
        } else {
            redirect('Clients/index');
        }
    }

    public function view($id = '') 
    {

        $this->load->view('shared/header');
        $data['supplier'] = $this->Clients_model->getSupplierById($id);
        $this->load->view('Clients/view', $data);
        $this->load->view('shared/footer');
    }

    public function invoice($client_id='')
    {
        if(isset($client_id) && $client_id!=''){
            $data['client'] = $this->Clients_model->getClientById($client_id);
            $data['invoices'] = $this->Clients_model->getInvoiceListByClientId($client_id);
            //creating the client invoice
            if(isset($_GET['action']) && $_GET['action']=='create'){
                $this->load->view('clients/create_invoice', $data,'true');
            } else {
                
                $this->load->view('shared/header');
                $this->load->view('clients/client_invoice', $data);
                $this->load->view('shared/footer');
            }
        } else {
            //redirect to client list
            redirect('clients/');
        } 
    }

    public function addInvoiceDetailsCache($client_id){
        if(isset($client_id) && $client_id!=''){
            $incvoice_data = array(
                'client_id' => $client_id,
                'narration' => $_GET['narration'], 
                'amount' => $_GET['amount'], 
                'is_taxable' => $_GET['taxable'], 
                'emp_id' => $this->session->userinfo->user_id, 
                );
            $result = $this->Clients_model->addInvoiceDetailsCache($incvoice_data);
            $response = array();
            if($result){
                $response = array(
                    'status' => '200',
                    'message' => 'added Successfully',
                    );
            } else {
                $response = array(
                    'status' => '500',
                    'message' => 'Failed to add invoice entry',
                    );
            }
            echo json_encode($response);
        }
    }
    public function getInvoiceDetailsCache($client_id){
        $data['invoice_cache'] = $this->Clients_model->getInvoiceDetailsCache($client_id,$this->session->userinfo->user_id);
        $this->load->view('clients/invoice_cache',$data);
    }
    public function submitInvoice($client_id){
        if(isset($client_id) && $client_id!=''){
            
$invoice_cache = $this->Clients_model->getInvoiceDetailsCache($client_id,$this->session->userinfo->user_id);
    
            if($invoice_cache && count($invoice_cache) > 0){

                $this->db->trans_begin();
                //create invoice
                $inv_attr = array(
                    'invoice_date' => date('Y-m-d'),
                    'client_id' => $client_id,
                    'created_by' => $this->session->userinfo->user_id,
                    );
                $this->db->insert('tbl_client_invoice',$inv_attr);
                $invoice_id = $this->db->insert_id();
                //for each entry in invoice cache of client_id and emp_id
                foreach ($invoice_cache as $value) {
                    //insert invoice details from invoice cache
                    $invoice_details_attr = array(
                        'narration' => $value->narration,
                        'is_taxable' => $value->is_taxable,
                        'amount' => $value->amount,
                        'invoice_id' => $invoice_id,
                        );
                    $this->db->insert('tbl_client_invoice_details',$invoice_details_attr);

                    //delete this entry from cache
                    $this->db->where('id',$value->id);
                    $this->db->delete('tbl_invoice_cache');
                }

                //check transaction status
                if($this->db->trans_status() == FALSE){
                    $this->db->trans_rollback();
                    $response = array(
                        'status' => '500',
                        'message' => "Failed to create this invoice",  
                        );
                    echo json_encode($response);
                } else {
                    $this->db->trans_commit();
                    $response = array(
                        'status' => '200',
                        'message' => "This invoice has been Created Successfully",  
                        );
                    echo json_encode($response);
                }
            } else {
                $response = array(
                        'status' => '500',
                        'message' => "Sorry! No invoice entry to submit",  
                        );
                    echo json_encode($response);
            }
        }
    }
    public function removeInvoiceEntryFromCache($entry_id)
    {
        if(isset($entry_id) && $entry_id!=''){
            $this->db->where("id",$entry_id);
            $this->db->where("emp_id",$this->session->userinfo->user_id);
            $result = $this->db->delete('tbl_invoice_cache');
            if($result){
                $response = array(
                        'status' => '200',
                        'message' => "Entry removed successfully",  
                        );
                    echo json_encode($response);
            } else {
                $response = array(
                        'status' => '500',
                        'message' => "Failed to remove invoice entry",  
                        );
                echo json_encode($response);
            }

        }
    }
    public function getInvoiceListByClientId($client_id)
    {
        if(isset($client_id) && $client_id!=''){
            $data['invoices'] = $this->Clients_model->getInvoiceListByClientId($client_id);
            $this->load->view('clients/client_invoice_list',$data);
        }
    }

    public function viewinvoiceDetails($client_id){
        $data['invoice'] = $this->Clients_model->get_client_invoice($client_id);

        // echo $client_id;

        // echo "<pre>";
        // print_r($data);
        // exit();
        $pdf_content = '';//"<h1 style='text-align:center;'>Bank Reconciliation</h1>";
             
        $pdf_content .= $this->load->view('clients/reports/client_invoice_pdf', $data, true);

        $this->Helper->pdfReport($pdf_content);
    }

     public function customer_non_sponsor(){

        $data['reports'] = $this->Clients_model->get_client_agig_report();
        $this->load->view('shared/header');
        $this->load->view("clients/customer_non_sponsor", $data);
        $this->load->view('shared/footer');


    }
    
}
