<?php

class Setting extends CI_Controller {

    public function __construct() {
        parent::__construct();

        if (!isset($_SESSION['userinfo'])) {
            redirect('account/login');
        }

        $this->load->model('Settings');
        $this->load->library("pagination");
    }

    public function index() {
        $this->load->view('shared/header');
        $this->load->view('settings/index');
        $this->load->view('shared/footer');
    }

    public function currency() {
        if (isset($_POST['currency_name'])) {
            $msg = "";
            $status = "";
            //process input form 
            $this->form_validation->set_rules('currency_name', 'Currency name', 'trim|required');
            $this->form_validation->set_rules('currency_code', 'Currency Code', 'trim|required');
            $this->form_validation->set_rules('currency_symbol', 'Currency symbol', 'trim');
            $this->form_validation->set_rules('exch_rate', 'Excahange Rate', 'trim');
            $this->form_validation->set_rules('hudress_name', 'Hudress name', 'trim');

            if ($this->form_validation->run() == FALSE) {
                $msg = validation_errors();
            } else {
                $result = $this->Settings->addCurrency();

                if ($result == 'success') {
                    $msg = "Inserted successifully";
                    $status = '1';
                } else {
                    if ($result == '23000/1062') {
                        $msg = "Currency already exists.";
                    } else {
                        $msg = "An error has occured.Please try again";
                    }

                    $status = '0';
                }
            }

            echo json_encode(array('tokenhash' => $this->security->get_csrf_hash(), 'data' => $msg, 'status' => $status));
        } else {
            //display currencies
            $config = array();
            $config["base_url"] = base_url() . "Setting/currency";
            $config["total_rows"] = $this->Helper->record_count('tbl_currency');


            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

            $data['currencyList'] = $this->Settings->getCurrentList($this->pagination->per_page, $page);
            $data["links"] = $this->pagination->create_links();
            $data["page"] = $page;

            $this->load->view('shared/header');
            $this->load->view("settings/currency", $data);
            $this->load->view('shared/footer');
        }
    }

    function systemparams() {
        if (isset($_POST['submit'])) {
            $msg = "";
            $status = "";
            //process input form 

            $result = $this->Settings->updateSystemParams();

            redirect('setting/systemparams');

            if ($result == 'success') {
                $msg = "Inserted successifully";
                $status = '1';
            } else {
                if ($result == '23000/1062') {
                    $msg = "Currency already exists.";
                } else {
                    $msg = "An error has occured.Please try again";
                }

                $status = '0';
            }
        } else {
            $data['paramsList'] = $this->Settings->getSystemParamsList();

            $this->load->view('shared/header');
            $this->load->view("settings/systemparams", $data);
            $this->load->view('shared/footer');
        }
    }

    public function acc_group() {
        if (isset($_POST['group_name'])) {
            $msg = "";
            $status = "";
            //process input form 
            $this->form_validation->set_rules('group_name', 'group name', 'trim|required');
            $this->form_validation->set_rules('section_id', 'section name', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                $msg = validation_errors();
            } else {
                $result = $this->Settings->addGroup();

                if ($result == 'success') {
                    $msg = "Inserted successifully";
                    $status = '1';
                } else {
                    if ($result == '23000/1062') {
                        $msg = "Group already exists.";
                    } else {
                        $msg = "An error has occured.Please try again";
                    }

                    $status = '0';
                }
            }

            echo json_encode(array('tokenhash' => $this->security->get_csrf_hash(), 'data' => $msg, 'status' => $status));
        } else {
            //display currencies
            $config = array();
            $config["base_url"] = base_url() . "Setting/currency";
            $config["total_rows"] = $this->Helper->record_count('tbl_currency');


            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

            $data['currencyList'] = $this->Settings->getCurrentList($this->pagination->per_page, $page);
            $data["links"] = $this->pagination->create_links();
            $data["page"] = $page;

            $this->load->view('shared/header');
            $this->load->view("settings/acc_group", $data);
            $this->load->view('shared/footer');
        }
    }

    public function main_account() {
        if (isset($_POST['mainAcc_name'])) {
            $msg = "";
            $status = "";
            //process input form 
            $this->form_validation->set_rules('mainAcc_name', 'Main account name', 'trim|required');
            $this->form_validation->set_rules('group_id', 'Group name', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                $msg = validation_errors();
            } else {
                $result = $this->Settings->addMainAcc();

                if ($result == 'success') {
                    $msg = "Inserted successifully";
                    $status = '1';
                } else {
                    if ($result == '23000/1062') {
                        $msg = "Group already exists.";
                    } else {
                        $msg = "An error has occured.Please try again";
                    }

                    $status = '0';
                }
            }

            echo json_encode(array('tokenhash' => $this->security->get_csrf_hash(), 'data' => $msg, 'status' => $status));
        } else {
            //display currencies
            $config = array();
            $config["base_url"] = base_url() . "Setting/currency";
            $config["total_rows"] = $this->Helper->record_count('tbl_currency');


            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

            $data['currencyList'] = $this->Settings->getCurrentList($this->pagination->per_page, $page);
            $data["links"] = $this->pagination->create_links();
            $data["page"] = $page;

            $this->load->view('shared/header');
            $this->load->view("settings/main_account", $data);
            $this->load->view('shared/footer');
        }
    }

    public function accounts() {
        if (isset($_POST['Acc_name'])) {
            $msg = "";
            $status = "";
            //process input form 
            $this->form_validation->set_rules('Acc_name', 'Account name', 'trim|required');
            $this->form_validation->set_rules('mainAcc_id', 'Main account name', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                $msg = validation_errors();
            } else {
                $result = $this->Settings->addAcc();

                if ($result == 'success') {
                    $msg = "Inserted successifully";
                    $status = '1';
                } else {
                    if ($result == '23000/1062') {
                        $msg = "Group already exists.";
                    } else {
                        $msg = "An error has occured.Please try again";
                    }

                    $status = '0';
                }
            }

            echo json_encode(array('tokenhash' => $this->security->get_csrf_hash(), 'data' => $msg, 'status' => $status));
        } else {
            //display currencies
            $config = array();
            $config["base_url"] = base_url() . "Setting/currency";
            $config["total_rows"] = $this->Helper->record_count('tbl_currency');


            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

            $data['currencyList'] = $this->Settings->getCurrentList($this->pagination->per_page, $page);
            $data["links"] = $this->pagination->create_links();
            $data["page"] = $page;

            $this->load->view('shared/header');
            $this->load->view("settings/accounts", $data);
            $this->load->view('shared/footer');
        }
    }

    public function uom() {
        if ($this->input->post('submit') != null) {
            //process input form   
        } else {
            //display uom
        }
    }

    public function country() {
        if ($this->input->post('submit') != null) {
            //process input form   
        } else {
            //display country
        }
    }

    public function config() {
        if ($this->input->post('submit') != null) {
            //process input form   
        } else {
            //display config
        }
    }

}
