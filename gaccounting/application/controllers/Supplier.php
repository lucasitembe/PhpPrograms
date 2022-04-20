<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier extends CI_Controller {

    public function __construct() {
        parent::__construct();

        if (!isset($_SESSION['userinfo'])) {
            redirect('account/login');
        }

        $this->load->model('Supplier_model');
        $this->load->library('pagination');
        $this->modifier = NULL; //change this to user id if available
    }

    public function index() {
        //this is the index page
        if (isset($_POST['supplier_name'])) {
            $msg = "";
            $status = "";
            //process input form 
            $this->form_validation->set_rules('supplier_name', 'Supplier Name', 'trim|required');
            $this->form_validation->set_rules('address', 'Address', 'trim|required');
            $this->form_validation->set_rules('country', 'Country', 'trim|required');
            $this->form_validation->set_rules('currency', 'Currency', 'trim|required');
            $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
            $this->form_validation->set_rules('fax', 'Fax', 'trim');
            $this->form_validation->set_rules('email', 'E-mail', 'trim|required|valid_email');

            $this->form_validation->set_rules('contact_name', 'Contact Name', 'trim|required');
            $this->form_validation->set_rules('contact_phone', 'Contact Phone', 'trim|required');
            $this->form_validation->set_rules('contact_email', 'Contact E-mail', 'trim|required|valid_email');
            $this->form_validation->set_rules('web_url', 'Website url', 'trim');



            if ($this->form_validation->run() == FALSE) {
                $msg = validation_errors();
            } else {
                //table attributes to be inserted
                $attributes = array(
                    'suppliername' => $_POST['supplier_name'],
                    'address' => $_POST['address'],
                    'country_id' => $_POST['country'],
                    'currency_id' => $_POST['currency'],
                    'telephone' => $_POST['phone'],
                    'fax' => $_POST['fax'],
                    'email' => $_POST['email'],
                    'contact_email' => $_POST['contact_email'],
                    'contactname' => $_POST['contact_name'],
                    'contact_phone' => $_POST['contact_phone'],
                    'url' => $_POST['web_url']
                );

                $result = $this->Supplier_model->createSupplier($attributes);

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
            $config["base_url"] = base_url() . "Supplier/index";
            $config["total_rows"] = $this->Helper->record_count('tbl_supplier');


            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

            $data['supplierList'] = $this->Supplier_model->suppliersList($this->pagination->per_page, $page);

            $data["links"] = $this->pagination->create_links();
            $data["page"] = $page;

            $data['currencies'] = $this->Helper->getCurrencies();
            $data['countries'] = $this->Helper->getCountries();


            $this->load->view('shared/header');
            $this->load->view('supplier/index', $data);
            $this->load->view('shared/footer');
        }
    }

    public function supplierform($id) {
        $data['supplier'] = $this->Supplier_model->getSupplierById($id);
        $data['currencies'] = $this->Helper->getCurrencies();
        $data['countries'] = $this->Helper->getCountries();
        $this->load->view('supplier/forms/supplierform', $data);
    }

    public function updateSupplier() {
        if (isset($_POST['supplier_id'])) {
            $msg = "";
            $status = "";
            //process input form 
            $this->form_validation->set_rules('supplier_name', 'Supplier Name', 'trim|required');
            $this->form_validation->set_rules('address', 'Address', 'trim|required');
            $this->form_validation->set_rules('country', 'Country', 'trim|required');
            $this->form_validation->set_rules('currency', 'Currency', 'trim|required');
            $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
            $this->form_validation->set_rules('fax', 'Fax', 'trim');
            $this->form_validation->set_rules('email', 'E-mail', 'trim|required|valid_email');

            $this->form_validation->set_rules('contact_name', 'Contact Name', 'trim|required');
            $this->form_validation->set_rules('contact_phone', 'Contact Phone', 'trim|required');
            $this->form_validation->set_rules('contact_email', 'Contact E-mail', 'trim|required|valid_email');
            $this->form_validation->set_rules('web_url', 'Website url', 'trim');



            if ($this->form_validation->run() == FALSE) {
                $msg = validation_errors();
            } else {
                //table attributes to be inserted
                $attributes = array(
                    'suppliername' => $_POST['supplier_name'],
                    'address' => $_POST['address'],
                    'country_id' => $_POST['country'],
                    'currency_id' => $_POST['currency'],
                    'telephone' => $_POST['phone'],
                    'fax' => $_POST['fax'],
                    'email' => $_POST['email'],
                    'contact_email' => $_POST['contact_email'],
                    'contactname' => $_POST['contact_name'],
                    'contact_phone' => $_POST['contact_phone'],
                    'url' => $_POST['web_url']
                );

                $result = $this->Supplier_model->updateSupplier($attributes, $_POST['supplier_id']);

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
            redirect('supplier/index');
        }
    }

    public function view($id = '') {

        $this->load->view('shared/header');
        $data['supplier'] = $this->Supplier_model->getSupplierById($id);
        $this->load->view('supplier/view', $data);
        $this->load->view('shared/footer');
    }

}
