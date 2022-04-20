<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Gledger
 *
 * @author ADE
 */
class Gledger extends CI_Controller {

    public function __construct() {
        parent::__construct();

        if (!isset($_SESSION['userinfo'])) {
            redirect('account/login');
        }
        $this->load->model('Gledgers');
        $this->load->model('Supplier_model');   
    }

    public function index() {
        $this->load->view('shared/header');
        $this->load->view("gledger/index");
        $this->load->view('shared/footer');
    }

    public function profitloss() {

        //$data['profitloss'] = $this->Gledgers->getProfitLoss();
        $data['currentAccountYear'] = $this->Helper->getCurrentAccountYear();

        if (isset($_GET['drill_rev_account']) || isset($_GET['drill_exp_account'])) {
            if (isset($_GET['drill_rev_account'])) {
                $data['ledgersTransactions'] = $this->Gledgers->getLedgersBySection('Revenues', $this->input->get('id'), $this->input->get('acc_code'));
            } else if (isset($_GET['drill_exp_account'])) {
                $data['ledgersTransactions'] = $this->Gledgers->getLedgersBySection('Expenses', $this->input->get('id'), $this->input->get('acc_code'));
            }
        } else {
            $data['revenues'] = $this->Gledgers->getGLAccountsBySection('Revenues');
            $data['expenses'] = $this->Gledgers->getGLAccountsBySection('Expenses');
        }

        $data['start_date'] = $this->input->get('start_date');
        $data['end_date'] = $this->input->get('end_date');

        if ($this->input->is_ajax_request()) {
            $data['source'] = "";
            $this->load->view("gledger/reports/profitloss_partial", $data);
        } else if (isset($_GET['report']) && (!isset($_GET['drill_rev_account']) && !isset($_GET['drill_exp_account']))) {
            $data['source'] = "report";

            $pdf = "<h1 style='text-align:center;'>INCOME STATEMENT ( PROFIT & LOSS )<br/> REPORT</h1>";
            $pdf .= '<h3 style="text-align:center;font-weight:200"><strong>FROM</strong> ' . $data['start_date'] . ' <strong>TO</strong> ' . $data['end_date'] . '</h3><br/>';
            $pdf .= $this->load->view("gledger/reports/profitloss_partial", $data, TRUE);
            $this->Helper->pdfReport($pdf);
        } else {
            $this->load->view('shared/header');
            if (isset($_GET['drill_exp_account'])) {
                $data['source'] = "Expenses";
                $data['ref_code'] = $this->input->get('ref_code');

                if (isset($_GET['report'])) {
                    $pdf_content = $this->load->view('gledger/reports/profitloss_partial_drill_print', $data, true);
                    $this->Helper->pdfReport($pdf_content);
                } else {
                    $this->load->view("gledger/reports/profitloss_partial_drill", $data);
                }
            } else if (isset($_GET['drill_rev_account'])) {
                $data['source'] = "Revenue";
                $data['ref_code'] = $this->input->get('ref_code');
                if (isset($_GET['report'])) {
                    $pdf_content = $this->load->view('gledger/reports/profitloss_partial_drill_print', $data, true);
                    $this->Helper->pdfReport($pdf_content);
                } else {
                    $this->load->view("gledger/reports/profitloss_partial_drill", $data);
                }
            } else {
                $this->load->view("gledger/reports/profitloss", $data);
            }
            $this->load->view('shared/footer');
        }
    }

    public function cashBook() {
        //$data['journalreport'] = $this->Gledgers->getJournalEntryReport();
        //$data['JournalEntryIDs'] = $this->Gledgers->getJournalEntryIDs();
        $data['currentAccountYear'] = $this->Helper->getCurrentAccountYear();

        $trialBalance = array();
        if (isset($_GET['drill_ledger'])) {
            if (isset($_GET['drill_ledger'])) {
                $data['cashbook'] = $this->Gledgers->getCashBook($this->input->get('id'));
            }
        } else {
            $data['cashbook'] = $this->Gledgers->getCashBook();
        }
        $data['start_date'] = $this->input->get('start_date');
        $data['end_date'] = $this->input->get('end_date');



        if ($this->input->is_ajax_request()) {
            $data['source'] = "";
            $this->load->view("gledger/reports/cashbook_partial", $data);
        } else if (isset($_GET['report']) && (!isset($_GET['drill_ledger']))) {
            $data['source'] = "report";

            $start_date = $this->input->get('start_date');
            $end_date = $this->input->get('end_date');

            $pdf = "<h1 style='text-align:center;'>CASH BOOK</h1>";
            $pdf .= '<h3 style="text-align:center;font-weight:200;"><strong>FROM</strong> ' . $start_date . ' <strong>TO</strong> ' . $end_date . '</h3>';
            $pdf .= $this->load->view("gledger/reports/cashbook", $data, TRUE);

            $this->Helper->pdfReport($pdf);
        } else {
            $this->load->view('shared/header');
            if (isset($_GET['drill_ledger'])) {
                $data['ref_code'] = $this->input->get('ref_code');

                if (isset($_GET['report'])) {
                    $pdf_content = $this->load->view('gledger/reports/cashbook_partial', $data, true);
                    $this->Helper->pdfReport($pdf_content);
                } else {
                    $this->load->view("gledger/reports/cashbook_partial", $data);
                }
            } else {
                $this->load->view("gledger/reports/cashbook", $data);
            }
            $this->load->view('shared/footer');
        }
    }

    public function trialBalance() {
        //$data['journalreport'] = $this->Gledgers->getJournalEntryReport();
        //$data['JournalEntryIDs'] = $this->Gledgers->getJournalEntryIDs();
        $data['currentAccountYear'] = $this->Helper->getCurrentAccountYear();
        $data['source'] = ucfirst(strtolower((isset($_GET['drill_acc'])) ? $_GET['drill_acc'] : ''));

        $trialBalance = array();
        if (isset($_GET['drill_acc'])) {
            if (isset($_GET['drill_acc'])) {
                $data['ledgersTransactions'] = $this->Gledgers->getLedgersBySection($_GET['drill_acc'], $this->input->get('id'), $this->input->get('acc_code'));
            }
        } else {
            foreach ($this->Helper->getAccSections() as $val) {
                array_push($trialBalance, array("$val->sec_desc" => $this->Gledgers->getTrialBalanceReport($val->sec_desc)));
            }

            $data['trialBalance'] = $trialBalance;
        }
        $data['start_date'] = $this->input->get('start_date');
        $data['end_date'] = $this->input->get('end_date');



        if ($this->input->is_ajax_request()) {
            $data['source'] = "";
            $this->load->view("gledger/reports/trialbalancereport_partial", $data);
        } else if (isset($_GET['report']) && (!isset($_GET['drill_acc']) && !isset($_GET['drill_acc']))) {
            $data['source'] = "report";

            $start_date = $this->input->get('start_date');
            $end_date = $this->input->get('end_date');

            $pdf = "<h1 style='text-align:center;'>TRIAL BALANCE</h1>";
            $pdf .= '<h3 style="text-align:center;font-weight:200;"><strong>FROM</strong> ' . $start_date . ' <strong>TO</strong> ' . $end_date . '</h3>';
            $pdf .= $this->load->view("gledger/reports/trialbalancereport_partial", $data, TRUE);

            $this->Helper->pdfReport($pdf);
        } else {
            $this->load->view('shared/header');
            if (isset($_GET['drill_acc'])) {
                $data['ref_code'] = $this->input->get('ref_code');

                if (isset($_GET['report'])) {
                    $pdf_content = $this->load->view('gledger/reports/trialbalancereport_partial_drill_print', $data, true);
                    $this->Helper->pdfReport($pdf_content);
                } else {
                    $this->load->view("gledger/reports/trialbalancereport_partial_drill", $data);
                }
            } else if (isset($_GET['drill_acc'])) {
                $data['ref_code'] = $this->input->get('ref_code');
                if (isset($_GET['report'])) {
                    $pdf_content = $this->load->view('gledger/reports/trialbalancereport_partial_drill_print', $data, true);
                    $this->Helper->pdfReport($pdf_content);
                } else {
                    $this->load->view("gledger/reports/trialbalancereport_partial_drill", $data);
                }
            } else {
                $this->load->view("gledger/reports/trialbalancereport", $data);
            }
            $this->load->view('shared/footer');
        }
    }

    public function balancesheet() {
        $data['currentAccountYear'] = $this->Helper->getCurrentAccountYear();

        if (isset($_GET['drill_liab_account']) || isset($_GET['drill_asset_account'])) {
            if (isset($_GET['drill_liab_account'])) {
                $data['ledgersTransactions'] = $this->Gledgers->getLedgersBySection('Liabilities', $this->input->get('id'), $this->input->get('acc_code'));
            } else if (isset($_GET['drill_asset_account'])) {
                $data['ledgersTransactions'] = $this->Gledgers->getLedgersBySection('Assets', $this->input->get('id'), $this->input->get('acc_code'));
            }
        } else {
            $data['Assets'] = $this->Gledgers->getGLAccountsBySection('Assets');
            $data['Liabilities'] = $this->Gledgers->getGLAccountsBySection('Liabilities');
            $data['NetProfit'] = $this->Gledgers->getNetProfit();
        }
        $data['start_date'] = $this->input->get('start_date');
        $data['end_date'] = $this->input->get('end_date');

        if ($this->input->is_ajax_request()) {
            $data['source'] = "";
            $this->load->view("gledger/reports/balancesheet_partial", $data);
        } else if (isset($_GET['report']) && (!isset($_GET['drill_liab_account']) && !isset($_GET['drill_asset_account']))) {
            $start_date = $this->input->get('start_date');
            $end_date = $this->input->get('end_date');
            $data['source'] = "report";

            $pdf = "<h1 style='text-align:center;'>BALANCE SHEET REPORT</h1>";
            $pdf .= '<h3 style="text-align:center;font-weight:200"><strong>FROM</strong> ' . $start_date . ' <strong>TO</strong> ' . $end_date . '</h3><br/>';
            $pdf .= $this->load->view("gledger/reports/balancesheet_partial", $data, TRUE);
            $this->Helper->pdfReport($pdf);
        } else {
            $this->load->view('shared/header');

            if (isset($_GET['drill_liab_account'])) {
                $data['source'] = "Liablilities";
                $data['ref_code'] = $this->input->get('ref_code');

                if (isset($_GET['report'])) {
                    $pdf_content = $this->load->view('gledger/reports/balancesheet_partial_drill_print', $data, true);
                    $this->Helper->pdfReport($pdf_content);
                } else {
                    $this->load->view("gledger/reports/balancesheet_partial_drill", $data);
                }
            } else if (isset($_GET['drill_asset_account'])) {
                $data['source'] = "Assets";
                $data['ref_code'] = $this->input->get('ref_code');
                if (isset($_GET['report'])) {
                    $pdf_content = $this->load->view('gledger/reports/balancesheet_partial_drill_print', $data, true);
                    $this->Helper->pdfReport($pdf_content);
                } else {
                    $this->load->view("gledger/reports/balancesheet_partial_drill", $data);
                }
            } else {
                $this->load->view("gledger/reports/balancesheet", $data);
            }

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
                $result = $this->Gledgers->addGroup();

                if ($result == 'success') {
                    $msg = "Saved successifully";
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
            //$config["total_rows"] = $this->Helper->record_count('tbl_currency');


            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

            //$data['currencyList'] = $this->Gledgers->getCurrentList($this->pagination->per_page, $page);
            $data["links"] = $this->pagination->create_links();
            $data["page"] = $page;
            $data['values'] = $this->Helper->getList('tbl_acc_section');
            $data['acc_group'] = $this->Gledgers->getAccGroup();
            $this->load->view('shared/header');
            $this->load->view("gledger/acc_group", $data);
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
                $result = $this->Gledgers->addMainAcc();

                if ($result == 'success') {
                    $msg = "Saved successifully";
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
            //$config["base_url"] = base_url() . "Setting/currency";
            // $config["total_rows"] = $this->Helper->record_count('tbl_currency');


            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

            //$data['currencyList'] = $this->Gledgers->getCurrentList($this->pagination->per_page, $page);
            $data["links"] = $this->pagination->create_links();
            $data["page"] = $page;
            $data['mainacc'] = $this->Gledgers->getMainAcc();
            $data['values'] = $this->Helper->getList('tbl_acc_group');
            $this->load->view('shared/header');
            $this->load->view("gledger/main_account", $data);
            $this->load->view('shared/footer');
        }
    }

    public function accounts() {
        if (isset($_POST['Acc_name'])) {
            $msg = "";
            $status = "";
            //process input form 
            $this->form_validation->set_rules('Acc_name', 'Account name', 'trim|required');
            $this->form_validation->set_rules('group_id', 'Account Group', 'trim|required');
            $this->form_validation->set_rules('description', 'Description', 'trim');

            if ($this->form_validation->run() == FALSE) {
                $msg = validation_errors();
            } else {
                $result = $this->Gledgers->addAcc();

                if ($result == 'success') {
                    $msg = "Saved successifully";
                    $status = '1';
                } else {
                    if ($result == '23000/1062') {
                        $msg = "Account already exists.";
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
            //$config["base_url"] = base_url() . "Setting/currency";
            //$config["total_rows"] = $this->Helper->record_count('tbl_currency');


            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

            //$data['currencyList'] = $this->Gledgers->getCurrentList($this->pagination->per_page, $page);
            $data["links"] = $this->pagination->create_links();
            $data["page"] = $page;
            //$data['values'] = $this->Helper->getList('tbl_main_account');
            $data['values'] = $this->Helper->getList('tbl_acc_group');
            $data['accounts'] = $this->Gledgers->getAcc();
            $this->load->view('shared/header');
            $this->load->view("gledger/accounts", $data);
            $this->load->view('shared/footer');
        }
    }

    public function acc_section() {  //
        $data['acc_sections'] = $this->Helper->getList('tbl_acc_section');
        $this->load->view('shared/header');
        $this->load->view("gledger/account_section", $data);
        $this->load->view('shared/footer');
    }

    public function ledgers() {
        $this->load->view('shared/header');
        $this->load->view("gledger/ledgers");
        $this->load->view('shared/footer');
    }

    public function intLedger() {
        if (isset($_POST['ledger_name'])) {
            $msg = "";
            $status = "";
            //process input form 
            $this->form_validation->set_rules('ledger_name', 'Ledger name', 'trim|required');
            $this->form_validation->set_rules('acc_code', 'Account Name', 'trim|required');
            $this->form_validation->set_rules('discription', 'Description', 'trim');

            if ($this->form_validation->run() == FALSE) {
                $msg = validation_errors();
            } else {
                $result = $this->Gledgers->addIntLedger();

                if ($result == 'success') {
                    $msg = "Saved successifully";
                    $status = '1';
                } else {
                    if ($result == '23000/1062') {
                        $msg = "Account already exists.";
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
            //$config["base_url"] = base_url() . "Setting/currency";
            //$config["total_rows"] = $this->Helper->record_count('tbl_currency');

            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

            //$data['currencyList'] = $this->Gledgers->getCurrentList($this->pagination->per_page, $page);
            $data["links"] = $this->pagination->create_links();
            $data["page"] = $page;
            //$data['values'] = $this->Helper->getList('tbl_main_account');
            $data['values'] = $this->Helper->getList('tbl_accounts');
            $data['ledgers'] = $this->Gledgers->getIntLedger();
            $this->load->view('shared/header');
            $this->load->view("gledger/IntLedger", $data);
            $this->load->view('shared/footer');
        }
    }

    /*
     * ledger  based on Supplier
     */

    public function suppledger() {
        $ehms_based_type = 'supplier';
        if (isset($_POST['ledger_name'])) {
            $msg = "";
            $status = "";
            //process input form 
            $this->form_validation->set_rules('ledger_name', 'Ledger Name', 'trim|required');
            $this->form_validation->set_rules('supp_name', 'Supplier Name', 'trim|required');
            $this->form_validation->set_rules('acc_code', 'Account Name', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                $msg = validation_errors();
            } else {

                /*
                 * columns to be added to tbl_ledgers
                 */
                $ledger_name = $this->input->post('ledger_name');

                $ehms_based_value = $this->input->post('supp_name');
                $acc_code = $this->input->post('acc_code');
                $data = array(
                    'ledger_name' => $ledger_name,
                    'acc_code' => $acc_code,
                    'ehms_based_type' => $ehms_based_type,
                    'ehms_based_value' => $ehms_based_value,
                    'is_ehms' => 1,
                );

                $result = $this->Gledgers->addEhmsLedger($data);

                if ($result == 'success') {
                    $msg = "Saved successifully";
                    $status = '1';
                } else {
                    if ($result == '23000/1062') {
                        $msg = "Account already exists.";
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
            //$config["base_url"] = base_url() . "Setting/currency";
            //$config["total_rows"] = $this->Helper->record_count('tbl_currency');

            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

            $data["links"] = $this->pagination->create_links();
            $data["page"] = $page;

            $data['values'] = $this->Helper->getList('tbl_accounts');
            $data['suppliers'] = $this->Helper->getRemoteList('supplier');
            $data['ledgers'] = $this->Gledgers->getEhmsLedger($ehms_based_type);
            $this->load->view('shared/header');
            $this->load->view("gledger/suppledger", $data);
            $this->load->view('shared/footer');
        }
    }

    /*
     *  Ledgers based on consultation type
     */

    public function ledgerOnConsultationType() {
        $ehms_based_type = 'consultation type';
        if (isset($_POST['ledger_name'])) {
            $msg = "";
            $status = "";
            //process input form 
            $this->form_validation->set_rules('ledger_name', 'Ledger name', 'trim|required');
            $this->form_validation->set_rules('consultation_type', 'Consultation Type', 'trim|required');
            $this->form_validation->set_rules('acc_code', 'Account Name', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                $msg = validation_errors();
            } else {
                /*
                 * columns to be added to tbl_ledgers
                 */
                $ledger_name = $this->input->post('ledger_name');

                $ehms_based_value = $this->input->post('consultation_type');
                $acc_code = $this->input->post('acc_code');
                $data = array(
                    'ledger_name' => $ledger_name,
                    'acc_code' => $acc_code,
                    'ehms_based_type' => $ehms_based_type,
                    'ehms_based_value' => $ehms_based_value,
                    'is_ehms' => 1,
                );



                $result = $this->Gledgers->addEhmsLedger($data);

                if ($result == 'success') {
                    $msg = "Saved successifully";
                    $status = '1';
                } else {
                    if ($result == '23000/1062') {
                        $msg = "Legder already exists.";
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
            //$config["base_url"] = base_url() . "Setting/currency";
            //$config["total_rows"] = $this->Helper->record_count('tbl_currency');

            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

            //$data['currencyList'] = $this->Gledgers->getCurrentList($this->pagination->per_page, $page);
            $data["links"] = $this->pagination->create_links();
            $data["page"] = $page;
            //$data['values'] = $this->Helper->getList('tbl_main_account');
            $data['values'] = $this->Helper->getList('tbl_accounts');
            $data['consultationTypes'] = array(
                array("Name" => "Pharmacy", "Description" => "Pharmacy"),
                array("Name" => "Laboratory", "Description" => "Laboratory"),
                array("Name" => "Radiology", "Description" => "Radiology"),
                array("Name" => "Surgery", "Description" => "Surgery"),
                array("Name" => "Procedure", "Description" => "Procedure"),
                array("Name" => "Optical", "Description" => "Optical"),
                array("Name" => "Dialysis", "Description" => "Dialysis"),
                array("Name" => "Others", "Description" => "Others")
            );
            $data['ledgers'] = $this->Gledgers->getEhmsLedger($ehms_based_type);
            $this->load->view('shared/header');
            $this->load->view("gledger/ledgeronconsultation", $data);
            $this->load->view('shared/footer');
        }
    }

    /*
     *  Ledgers based on Sponsors
     */

    public function ledgerOnSponsors() {
        $ehms_based_type = 'sponsors';
        if (isset($_POST['ledger_name'])) {
            $msg = "";
            $status = "";
            //process input form 
            $this->form_validation->set_rules('ledger_name', 'Ledger name', 'trim|required');
            $this->form_validation->set_rules('sponsor', 'Sponsor Name', 'trim|required');
            $this->form_validation->set_rules('acc_code', 'Account Name', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                $msg = validation_errors();
            } else {
                /*
                 * columns to be added to tbl_ledgers
                 */
                $ledger_name = $this->input->post('ledger_name');

                $ehms_based_value = $this->input->post('sponsor');
                $acc_code = $this->input->post('acc_code');
                $data = array(
                    'ledger_name' => $ledger_name,
                    'acc_code' => $acc_code,
                    'ehms_based_type' => $ehms_based_type,
                    'ehms_based_value' => $ehms_based_value,
                    'is_ehms' => 1,
                );



                $result = $this->Gledgers->addEhmsLedger($data);

                if ($result == 'success') {
                    $msg = "Saved successifully";
                    $status = '1';
                } else {
                    if ($result == '23000/1062') {
                        $msg = "Legder already exists.";
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
            //$config["base_url"] = base_url() . "Setting/currency";
            //$config["total_rows"] = $this->Helper->record_count('tbl_currency');

            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

            //$data['currencyList'] = $this->Gledgers->getCurrentList($this->pagination->per_page, $page);
            $data["links"] = $this->pagination->create_links();
            $data["page"] = $page;
            //$data['values'] = $this->Helper->getList('tbl_main_account');
            $data['values'] = $this->Helper->getList('tbl_accounts');
            //getting sponsors from ehms remotely
            $data['sponsors'] = $this->Helper->getRemoteList('sponsors');


            $data['ledgers'] = $this->Gledgers->getEhmsLedger($ehms_based_type);
            $this->load->view('shared/header');
            $this->load->view("gledger/ledgeronsponsors", $data);
            $this->load->view('shared/footer');
        }
    }

//

    public function accountYear() {
        if (isset($_POST['acc_year'])) {
            $msg = "";
            $status = "";
            //process input form 
            $this->form_validation->set_rules('acc_year', 'Account year', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                $msg = validation_errors();
            } else {
                $result = $this->Gledgers->addAccYear();

                if ($result == 'success') {
                    $msg = "Saved successifully";
                    $status = '1';
                } else {
                    if ($result == '23000/1062') {
                        $msg = "Account Year already exists.";
                    } else {
                        $msg = $this->db->last_query(); //"An error has occured.Please try again";
                    }

                    $status = '0';
                }
            }

            echo json_encode(array('tokenhash' => $this->security->get_csrf_hash(), 'data' => $msg, 'status' => $status));
        } else {
            //display currencies
            $config = array();
            //$config["base_url"] = base_url() . "Setting/currency";
            //$config["total_rows"] = $this->Helper->record_count('tbl_currency');


            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

            //$data['currencyList'] = $this->Gledgers->getCurrentList($this->pagination->per_page, $page);
            $data["links"] = $this->pagination->create_links();
            $data["page"] = $page;

            $data['accountYear'] = $this->Helper->getAccountYear();
            $this->load->view('shared/header');
            $this->load->view("gledger/accountYear", $data);
            $this->load->view('shared/footer');
        }
    }

    public function modifyJournalMonths() {
        $month = $this->input->get('month');
        $src = $this->input->get('src');
        $result = $this->Gledgers->modifyJournalMonths($month, $src);

        echo $result;
    }

    public function modifyYear() {
        $id = $this->input->get('id');
        $src = $this->input->get('src');
        $result = $this->Gledgers->modifyYear($id, $src);

        echo $result;
    }

    public function CloseYearMonth() {
        $data['currentAccountYear'] = $this->Helper->getCurrentAccountYear();
        $this->load->view('shared/header');
        $this->load->view("gledger/accountyearclosing", $data);
        $this->load->view('shared/footer');
    }

    //Start of Reports

    public function journalReport() {
        $data['currentAccountYear'] = $this->Helper->getCurrentAccountYear();
        $data['journalreport'] = $this->Gledgers->getJournalEntryReport();
        $data['JournalEntryIDs'] = $this->Gledgers->getJournalEntryIDs();

        if ($this->input->is_ajax_request()) {
            $this->load->view("gledger/reports/journalreport_partial", $data);
        } else if (isset($_GET['report'])) {
            $journalreport = $this->Gledgers->getJournalEntryReport();
            $JournalEntryIDs = $this->Gledgers->getJournalEntryIDs();
            $start_date = $this->input->get('start_date');
            $end_date = $this->input->get('end_date');
            //print pdf
            $ledger_name = ""; //$this->Helper->getLedgerById($acc_ledger)->ledger_name;

            $pdf = "<h1 style='text-align:center;'>JOURNAL ENTRY STATEMENT REPORT</h1>";
            $pdf .= '<h3 style="text-align:center;font-weight:200"><strong>FROM</strong> ' . $start_date . ' <strong>TO</strong> ' . $end_date . '</h3><br/>';
            $pdf .= $this->load->view("gledger/reports/journalreport_partial", $data, TRUE);
            $this->Helper->pdfReport($pdf, 'journal_report_' . date('Y-m-d H:m:s'), true);
        } else {
            $this->load->view('shared/header');
            $this->load->view("gledger/reports/journalreport", $data);
            $this->load->view('shared/footer');
        }
    }

    public function LedgerStatementReport() {
        if ($this->input->is_ajax_request()) {
            $data['ledgerStatementReport'] = $this->Gledgers->getledgerStatementReport();

            $this->load->view("gledger/reports/Ledgerstatement_partial", $data);
        } else if (isset($_GET['report'])) {
            $start_date = $this->input->get('start_date');
            $end_date = $this->input->get('end_date');
            $acc_ledger = $this->input->get('acc_ledger');
            //print pdf
            $result = $this->Gledgers->getledgerStatementReport();
            $ledger_name = $this->Helper->getLedgerById($acc_ledger)->ledger_name;

            $pdf = "<h1 style='text-align:center;'>LEDGER STATEMENT REPORT</h1>";
            $pdf .= "<h2 style='text-align:center;'>" . ucfirst(strtolower($ledger_name)) . "</h2>";
            $pdf .= '<h3 style="text-align:center;font-weight:200"><strong>FROM</strong> ' . $start_date . ' <strong>TO</strong> ' . $end_date . '</h3><br/>';
            $pdf .= '<p class="text-right"><b>BF</b> 0</p>';
            $pdf .= '<table class="table table-hover"> 
                        <thead> 
                            <tr> 
                                <th>#</th> 
                                <th>Date n Time</th> 
                                <th>Narration</th> 
                                <th class="text-right">Amount</th> 
                            </tr> 
                        </thead> 
                        <tbody class="tbody-backc-color">';

            $grandTotal = 0;
            $i = 1;
            if ($result != null) {
                foreach ($result as $val) {
                    $grandTotal += $val->amount;
                    $pdf .= '<tr>';
                    $pdf .= '<td>' . $i++ . '</td>';

                    $pdf .= '<td>' . $val->trans_date_time . '</td>';

                    $pdf .= '<td>' . $val->comment . '</td>';

                    $pdf .= '<td  class="text-right">' . number_format($val->amount, 2) . '</td></tr>';

                    $pdf .= '</tr>';
                }

                $pdf .= '<tr class="grand-text"><td colspan="3" class="text-left"><label class="control-label">Balance</label></td><td class="text-right"><label class="control-label">' . number_format($grandTotal, 2) . '</label></td></tr>';
            }

            $pdf .= '</tbody></table> ';

            $this->Helper->pdfReport($pdf);
        } else {

            $data['currentAccountYear'] = $this->Helper->getCurrentAccountYear();
            $data['ledgers'] = $this->Helper->getLedgersList();

            $this->load->view('shared/header');
            $this->load->view("gledger/reports/Ledgerstatement", $data);
            $this->load->view('shared/footer');
        }
    }

    function chartOfLedgers() {
        $sections = $this->Helper->getList('tbl_acc_section');

        $dat = array();

        foreach ($sections as $sec) {
            $chartofacounts = $this->Gledgers->chartOfLedgers($sec->sec_id);

            array_push($dat, array($sec->sec_desc => $chartofacounts));
        }
        $data['chartofledgers'] = $dat;

        if (isset($_GET['report'])) {
            $pdf_content = "<h1 style='text-align:center;'>CHARTS OF LEDGERS REPORT</h1>";

            $pdf_content .= $this->load->view('gledger/reports/chartofLedgers_pdf', $data, true);

            $this->Helper->pdfReport($pdf_content);
        } else {

            $this->load->view('shared/header');
            $this->load->view("gledger/reports/chartofLedgers", $data);
            $this->load->view('shared/footer');
        }
    }

    function chartOfAccounts() {
        $sections = $this->Helper->getList('tbl_acc_section');

        $dat = array();

        foreach ($sections as $sec) {
            $chartofacounts = $this->Gledgers->chartOfAccounts($sec->sec_id);

            array_push($dat, array($sec->sec_desc => $chartofacounts));
        }
        $data['chartofaccounts'] = $dat;

        if (isset($_GET['report'])) {
            $pdf_content = "<h1 style='text-align:center;'>CHARTS OF ACCOUNTS REPORT</h1>";

            $pdf_content .= $this->load->view('gledger/reports/chartofaccounts_pdf', $data, true);

            $this->Helper->pdfReport($pdf_content);
        } else {

            $this->load->view('shared/header');
            $this->load->view("gledger/reports/chartofaccounts", $data);
            $this->load->view('shared/footer');
        }
    }

    public function bankReconciliation() {
        $data['currentAccountYear'] = $this->Helper->getCurrentAccountYear();
        $data['ledgers'] = $this->Helper->getLedgersList();

        $this->load->view('shared/header');
        $this->load->view("gledger/bank_reconciliation", $data);
        $this->load->view('shared/footer');
    }

    public function LedgerStatement() {
        if ($this->input->is_ajax_request()) {
            $data['ledgerStatementReport'] = $this->Gledgers->getledgerStatementReport();
            $data['start_date'] = $_GET['start_date'];
            $data['end_date'] = $_GET['end_date'];
            $data['ledger_id'] = $_GET['acc_ledger'];
            $this->load->view("gledger/reports/ledgerstatementforbankreconciliation", $data);
        }
    }

    public function saveBankReconciliation() {
        if (isset($_GET['ledger_count']) && $_GET['ledger_count'] != '') {
            if (isset($_GET['bank_balance']) && $_GET['bank_balance'] != '' && isset($_GET['amount_not_in_ledger']) && $_GET['amount_not_in_ledger'] != '') {
                $this->db->trans_begin();
                //create bank reconciliation entry
                $emp = $this->session->userinfo->fname . ' ' . $this->session->userinfo->lname . ' (' . $this->session->userinfo->username . ')';
                $b_attr = array(
                    'reconciliation_date' => date('Y-m-d'),
                    'bank_balance' => $_GET['bank_balance'],
                    'amount_not_in_ledger' => $_GET['amount_not_in_ledger'],
                    'ledger_id' => $_GET['ledger_id'],
                    'start_date' => $_GET['start_date'],
                    'end_date' => $_GET['end_date'],
                    'posted_by' => $emp,
                    'ledger_balance' => $_GET['ledger_total_amount'],
                );
                $this->db->insert('tbl_bank_reconciliation', $b_attr);
                $bank_reconc_id = $this->db->insert_id();
                for ($i = 1; $i <= $_GET['ledger_count']; $i++) {
                    $reconciliation_status = '';
                    $journal_e_d_id = '';
                    if (isset($_GET['reconcile_' . $i])) {
                        $reconciliation_status = 'found';
                        $journal_e_d_id = $_GET['reconcile_' . $i];
                    } else {
                        $reconciliation_status = 'not found';
                        $journal_e_d_id = $_GET['u_reconcile_' . $i];
                    }

                    $bank_recno_details_attr = array(
                        'bank_reconciliation_id' => $bank_reconc_id,
                        'transaction_id' => $journal_e_d_id,
                        'reconciliation_status' => $reconciliation_status,
                        'transaction_amount' => $_GET['amount_' . $i],
                    );
                    $this->db->insert('tbl_bank_reconciliation_details', $bank_recno_details_attr);
                    //reset the values
                    $reconciliation_status = '';
                    $journal_e_d_id = '';
                }

                if ($this->db->trans_status() == FALSE) {
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
                        'bank_reconc_id' => $bank_reconc_id,
                        'message' => "bank reconciliation completed successifully",
                    );
                    echo json_encode($response);
                }
            } else {
                $response = array(
                    'status' => '500',
                    'message' => "Please enter the bank balance and amount not found in Ledgers",
                );
                echo json_encode($response);
            }
            //print_r($_GET);
        }
    }

    public function viewBankReconciliationPdf($id = '') {
        //view a pdf ersion of reconciliation
        if (isset($id) && $id != '' && is_numeric($id)) {
            //get reconciliation details by id
            $data['reconciliation'] = $this->Gledgers->getbankReconciliationById($id);
            $data['reconciliation_details'] = $this->Gledgers->getbankReconciliationDetailsById($id);
            $pdf_content = "<h1 style='text-align:center;'>Bank Reconciliation</h1>";

            $pdf_content .= $this->load->view('gledger/reports/bank_reconciliation_report_pdf', $data, true);

            $this->Helper->pdfReport($pdf_content);
        } else {
            
        }
    }

    public function bankReconciliationReport() {
        if (isset($_GET['start_date']) && isset($_GET['end_date']) && $_GET['start_date'] != '' && $_GET['end_date'] != '') {

            $dt = $data['reconciliations'] = $this->Gledgers->getBankReconciliations($_GET['start_date'], $_GET['end_date']);
            if ($dt) {
                $this->load->view('gledger/reports/bank_reconciliation_report_partial', $data);
            } else {
                echo "<hr><p style='color:red;font-size:18px;text-align:center;'>No record found!</p>";
            }
        } else {
            $data['tracking_details'] = '';
            $this->load->view('shared/header');
            $this->load->view('gledger/reports/bank_reconciliation_report');
            $this->load->view('shared/footer');
        }
    }

    //invoices
    public function invoices() {



        $this->load->view('shared/header');
        $this->load->view('gledger/invoice/index');
        $this->load->view('shared/footer');
    }

    //invoices
    public function aging_reports() {
        $this->load->view('shared/header');
        $this->load->view('gledger/invoice/aging_home');
        $this->load->view('shared/footer');
    }

    public function customer_invoice() {

         $data['invoice_List'] = $this->Gledgers->getInvoices();

        $this->load->view('shared/header');
        $this->load->view('gledger/invoice/user_invoices_ehms', $data);
        $this->load->view('shared/footer');
    }

    public function non_sponsor() {
        $data['invoice_List'] = $this->Gledgers->getInvoices();
        $this->load->view('shared/header');
        $this->load->view('gledger/invoice/non_sponsor', $data);
        $this->load->view('shared/footer');
    }

    public function delete_invoice() {

        $invoice_id = $this->uri->segment('3');

        $result = $this->Gledgers->delete_invoices($invoice_id);

        if ($result == 'success') {
            echo "<script>
            alert('deleted');

            </script>";

            redirect('gledger/supplier_invoice');
        } else {

            echo "note deleted";
        }
    }

    public function edit_invoice($id) {

        // echo $id;
        // exit();

        $data['invoice'] = $this->Gledgers->getInvoiceById($id);
        // print_r($data);
        // exit();

        $this->load->view('gledger/invoice/invoce_edit', $data);
    }

    public function updateInvoice() {

        if (isset($_POST['invoice_id'])) {
            $msg = "";
            $status = "";

            //process input form 

            $this->form_validation->set_rules('supplier_name', 'Supplier name', 'trim|required');
            $this->form_validation->set_rules('invoice_number', 'Invoice Number', 'required');
            $this->form_validation->set_rules('amount', 'Invoice Amount', 'trim|required');
            $this->form_validation->set_rules('userfile', 'userfile', 'xss_clean');


            if ($this->form_validation->run() == FALSE) {
                $msg = validation_errors();
            } else {
                $config['upload_path'] = 'assets/invoice_image';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = 1000;
                $config['max_width'] = 1024;
                $config['max_height'] = 768;

                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('userfile')) {
                    $error = array('error' => $this->upload->display_errors());

                    $this->load->view('upload_form', $error);
                }
                $file_info = $this->upload->data();



                $file_name = $file_info['file_name'];




                $attributes = array(
                    'supplier_id' => $_POST['supplier_name'],
                    'invoice_no' => $_POST['invoice_number'],
                    'invoice_date' => $_POST['date'],
                    'amount' => $_POST['amount'],
                    'invoice_image' => $file_name
                );

                $result = $this->Gledgers->update_Invoice($id, $attributes);

                if ($result == 'success') {
                    $msg = "Updated successifully";
                    $status = '1';
                } else {
                    if ($result == '23000/1062') {
                        $msg = "Invoice already exists.";
                    } else {
                        $msg = "An error has occured.Please try again";
                    }

                    $status = '0';
                }
            }

            echo json_encode(array('tokenhash' => $this->security->get_csrf_hash(), 'data' => $msg, 'status' => $status));
            //redirect('gassets/categories');
        } else {
            
        }
    }

    public function supplier_invoice() {
        $data['invoice_List'] = $this->Gledgers->getInvoices();
        $this->load->view('shared/header');
        $this->load->view('gledger/invoice/invoice_record', $data);
        $this->load->view('shared/footer');
    }

    public function ageing_report() {

        if (!isset($_GET['report'])) {
            $data['ledgerStatementReport'] = $this->Gledgers->select_sum_supplier();
            $data['suppliers'] = $this->Supplier_model->suppliersList();
            // echo "<pre>";
            // print_r($data);
            // exit();

            $this->load->view('shared/header');
            $this->load->view('gledger/invoice/ageing_report', $data);
            $this->load->view('shared/footer');
        } else if (isset($_GET['report'])) {
            // $data['ledgerStatementReport'] = $this->Gledgers->select_sum_supplier();
            //print pdf
            $result = $this->Gledgers->select_sum_supplier();
            $supplier_name = $this->Helper->getSupplierById($supplier_id)->suppliername;

            $pdf = "<h1 style='text-align:center;'>GENERAL AGING STATEMENT REPORT</h1>";
            // $pdf .= "<h2 style='text-align:center;'>".'Supplier Name:' . ucfirst(strtolower($supplier_name)) . "</h2>";
            // $pdf .= '<h3 style="text-align:center;font-weight:200"><strong>FROM</strong> ' . $start_date . ' <strong>TO</strong> ' . $end_date . '</h3><br/>';
            $pdf .= '<div class="table-responsive">';
            $pdf .= '<table class="table table-hover" style="margin:10;"> 
                        <thead> 
                            <tr> 
                                <th>SN</th> 
                                <th>Supplier Name</th> 
                                <th>30 Days</th> 
                                <th>60 Days</th> 
                                <th>90 Days</th> 
                                <th>120 Days</th> 
                                <th>150 Days</th> 
                                <th>Over 150 Days</th> 
                                
                            </tr> 
                        </thead> 
                        <tbody class="tbody-backc-color">';

            $grandTotal = 0;
            $i = 1;

            $date2 = new DateTime(date('y-m-d'));
            $amount_30 = 0;
            $amount_60 = 0;
            $amount_90 = 0;
            $amount_120 = 0;
            $amount_150 = 0;
            $amount_other = 0;

            $grandTotal = 0;
            $total_30 = 0;
            $total_60 = 0;
            $total_90 = 0;
            $total_120 = 0;
            $total_150 = 0;
            $total_other = 0;

            if ($result != null) {
                foreach ($result as $val) {
                    $supplier_name = $this->Helper->getSupplierById($val['supplier_id'])->suppliername;

                    $date1 = new DateTime($val['trans_date']);
                    $interval = $date1->diff($date2);


                    $days = $interval->days;

                    $grandTotal += $val['amount'];
                    $pdf .= '<tr>';
                    $pdf .= '<td class="text-center">' . $i++ . '</td>';

                    $pdf .= '<td class="text-center">' . $supplier_name . '</td>';
                    if (0 <= $days && $days < 30) {
                        $amount_30 = $val['amount'];
                        $pdf .= '<td class="text-center">' . $amount_30 . '</td>';
                    } else {
                        $amount_30 = 0;
                        $pdf .= '<td class="text-center">' . $amount_30 . '</td>';
                    }
                    if (30 <= $days && $days < 60) {
                        $amount_60 = $val['amount'];
                        $pdf .= '<td class="text-center">' . $amount_60 . '</td>';
                    } else {
                        $amount_60 = 0;
                        $pdf .= '<td class="text-center">' . $amount_60 . '</td>';
                    }


                    if (60 <= $days && $days < 90) {
                        $amount_90 = $val['amount'];
                        $pdf .= '<td class="text-center">' . $amount_90 . '</td>';
                    } else {
                        $amount_90 = 0;
                        $pdf .= '<td class="text-center">' . $amount_90 . '</td>';
                    }

                    if (90 <= $days && $days < 120) {
                        $amount_120 = $val['amount'];
                        $pdf .= '<td class="text-center">' . $amount_120 . '</td>';
                    } else {
                        $amount_120 = 0;
                        $pdf .= '<td class="text-center">' . $amount_120 . '</td>';
                    }

                    if (120 <= $days && $days < 150) {
                        $amount_150 = $val['amount'];
                        $pdf .= '<td class="text-center">' . $amount_150 . '</td>';
                    } else {
                        $amount_150 = 0;
                        $pdf .= '<td class="text-center">' . $amount_150 . '</td>';
                    }
                    if (150 <= $days) {
                        $amount_other = $val['amount'];
                        $pdf .= '<td class="text-center">' . $amount_other . '</td></tr>';
                    } else {
                        $amount_other = 0;
                        $pdf .= '<td class="text-center">' . $amount_other . '</td>';
                    }
                    $pdf .= '</tr>';

                    $total_30 += $amount_30;
                    $total_60 += $amount_60;
                    $total_90 += $amount_90;
                    $total_120 += $amount_120;
                    $total_150 += $amount_150;
                    $total_other += $amount_other;
                }

                $pdf .= '<tr><td colspan="2" class="text-left"><label class="control-label">Total</label></td>
                <td class="text-center"><label class="control-label">' . $total_30 . '</label></td>
                <td class="text-center"><label class="control-label">' . $total_60 . '</label></td>
                <td class="text-center"><label class="control-label">' . $total_90 . '</label></td>
                <td><label class="control-label">' . $total_120 . '</label></td>
                <td><label class="control-label">' . $total_150 . '</label></td>
                <td><label class="control-label">' . $total_other . '</label></td>
                </tr>';
            }

            $pdf .= '</tbody></table></div>';

            $this->Helper->pdfReport($pdf);
        }
    }

    public function customer_aging_report_ehms() {

        $date1 = $_GET['start_date'];
        $date2 = $_GET['end_date'];
        $sponsor = $_GET['supplier_id'];

        $acc1 = file_get_contents("http://localhost/Final_One/files/sponsor_invoice_bydate.php?sponsor=NHIF&start_date=2017-05-01&end_date=2017-05-31");
        $data = json_decode($acc1);
        echo $date1;


        print_r($data);
        exit();
    }

    public function customer_aging_report() {
        if (!isset($_GET['report'])) {
            $data['invoice_List'] = $this->Gledgers->getInvoices();
            $this->load->view('shared/header');
            $this->load->view('gledger/invoice/customer_aging_report', $data);
            $this->load->view('shared/footer');
        } elseif (isset($_GET['supplier_id']) && isset($_GET['tart_date']) && isset($_GET['end_date'])) {


            // $this->load->view('shared/header');
            // $this->load->view('gledger/invoice/customer_aging_report',$data);
            // $this->load->view('shared/footer');
            //$length=count( $data);
        } else {
            $acc1 = file_get_contents("http://localhost/Final_One/files/get_sponsor_invoice.php");
            $data = json_decode($acc1);

            $length = count($data);


            $pdf = "<h1 style='text-align:center;'>CUSTOMER AGING STATEMENT REPORT</h1>";
            // $pdf .= "<h2 style='text-align:center;'>".'Supplier Name:' . ucfirst(strtolower($supplier_name)) . "</h2>";
            // $pdf .= '<h3 style="text-align:center;font-weight:200"><strong>FROM</strong> ' . $start_date . ' <strong>TO</strong> ' . $end_date . '</h3><br/>';
            $pdf .= '<div class="table-responsive">';
            $pdf .= '<table class="table table-hover" style="margin:10;"> 
                        <thead> 
                            <tr style="background:#f3f3f3;color:#000;"> 
                                <th>SN</th> 
                                <th>Supplier Name</th> 
                                <th>30 Days</th> 
                                <th>60 Days</th> 
                                <th>90 Days</th> 
                                <th>120 Days</th> 
                                <th>150 Days</th> 
                                <th>Over 150 Days</th> 
                                
                            </tr> 
                        </thead> 
                        <tbody class="tbody-backc-color">';


            $grandTotal = 0;
            $i = 1;

            $date2 = new DateTime(date('y-m-d'));
            $amount_30 = 0;
            $amount_60 = 0;
            $amount_90 = 0;
            $amount_120 = 0;
            $amount_150 = 0;
            $amount_other = 0;

            $grandTotal = 0;
            $total_30 = 0;
            $total_60 = 0;
            $total_90 = 0;
            $total_120 = 0;
            $total_150 = 0;
            $total_other = 0;
            $x = 1;
            // if ($ledgerStatementReport != null) {
            $amount_90 = 0;
            for ($i = 0; $i < $length; $i++) {

                // $supplier_name =$this->Helper->getSupplierById($val['supplier_id'])->suppliername;

                $date1 = new DateTime($data[$i]->trans_date);
                $interval = $date1->diff($date2);


                $days = $interval->days;

                $grandTotal += $data[$i]->amount;


                $pdf .= '<tr>';
                $pdf .= '<td class="text-center">' . $x++ . '</td>';

                $pdf .= '<td class="text-center">' . $data[$i]->sponsor . '</td>';
                if (0 <= $days && $days < 30) {
                    $amount_30 = $data[$i]->amount;
                    $pdf .= '<td class="text-center">' . number_format($amount_30) . '</td>';
                } else {
                    $amount_30 = 0;
                    $pdf .= '<td class="text-center">' . number_format($amount_30) . '</td>';
                }
                if (30 <= $days && $days < 60) {
                    $amount_60 = $data[$i]->amount;
                    $pdf .= '<td class="text-center">' . number_format($amount_60) . '</td>';
                } else {
                    $amount_60 = 0;
                    $pdf .= '<td class="text-center">' . number_format($amount_60) . '</td>';
                }


                if (60 <= $days && $days < 90) {
                    $amount_90 = $data[$i]->amount;
                    $pdf .= '<td class="text-center">' . number_format($amount_90) . '</td>';
                } else {
                    $amount_90 = 0;
                    $pdf .= '<td class="text-center">' . number_format($amount_90) . '</td>';
                }

                if (90 <= $days && $days < 120) {
                    $amount_120 = $data[$i]->amount;
                    $pdf .= '<td class="text-center">' . number_format($amount_120) . '</td>';
                } else {
                    $amount_120 = 0;
                    $pdf .= '<td class="text-center">' . number_format($amount_120) . '</td>';
                }

                if (120 <= $days && $days < 150) {
                    $amount_150 = $data[$i]->amount;
                    $pdf .= '<td class="text-center">' . number_format($amount_150) . '</td>';
                } else {
                    $amount_150 = 0;
                    $pdf .= '<td class="text-center">' . number_format($amount_150) . '</td>';
                }
                if (150 <= $days) {
                    $amount_other = $data[$i]->amount;
                    $pdf .= '<td class="text-center">' . number_format($amount_other) . '</td>';
                } else {
                    $amount_other = 0;
                    $pdf .= '<td class="text-center">' . number_format($amount_other) . '</td>';
                }






                $pdf .= '</tr>';

                $total_30 += $amount_30;
                $total_60 += $amount_60;
                $total_90 += $amount_90;
                $total_120 += $amount_120;
                $total_150 += $amount_150;
                $total_other += $amount_other;
            }




            $pdf .= '<tr style="background:#f3f3f3;color:#000;"><td colspan="2" class="text-center"><label class="control-label">Total</label></td>
                <td class="text-center"><label class="control-label">' . number_format($total_30) . '</label></td>
                <td class="text-center"><label class="control-label">' . number_format($total_60) . '</label></td>
                <td class="text-center"><label class="control-label">' . number_format($total_90) . '</label></td>
                <td class="text-center"><label class="control-label">' . number_format($total_120) . '</label></td>
                <td class="text-center"><label class="control-label">' . number_format($total_150) . '</label></td>
                <td class="text-center"><label class="control-label">' . number_format($total_other) . '</label></td>
                </tr>';

            $pdf .= '</tbody></table></div> ';

            $this->Helper->pdfReport($pdf);
        }

        // $this->load->view('shared/header');
        // $this->load->view('gledger/invoice/customer_aging_report',$data);
        // $this->load->view('shared/footer');
    }

    public function add_supplier_invoice() {

        if (isset($_POST['supplier_name'])) {
            $msg = "";
            $status = "";

            //process input form 

            $this->form_validation->set_rules('supplier_name', 'Supplier name', 'trim|required');
            $this->form_validation->set_rules('invoice_number', 'Invoice Number', 'required');
            $this->form_validation->set_rules('amount', 'Invoice Amount', 'trim|required');
            $this->form_validation->set_rules('userfile', 'userfile', 'xss_clean');


            if ($this->form_validation->run() == FALSE) {
                $msg = validation_errors();
            } else {
                $config['upload_path'] = 'assets/invoice_image';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = 1000;
                $config['max_width'] = 1024;
                $config['max_height'] = 768;

                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('userfile')) {
                    $error = array('error' => $this->upload->display_errors());

                    $this->load->view('upload_form', $error);
                }
                $file_info = $this->upload->data();



                $file_name = $file_info['file_name'];




                $attributes = array(
                    'supplier_id' => $_POST['supplier_name'],
                    'invoice_no' => $_POST['invoice_number'],
                    'invoice_date' => $_POST['date'],
                    'amount' => $_POST['amount'],
                    'invoice_image' => $file_name
                );

                $result = $this->Gledgers->create_invoice($attributes);

                if ($result == 'success') {
                    $msg = "Inserted successifully";
                    $status = '1';
                } else {
                    if ($result == '23000/1062') {
                        $msg = "Invoice already exists.";
                    } else {
                        $msg = "An error has occured.Please try again";
                    }

                    $status = '0';
                }
            }

            echo json_encode(array('tokenhash' => $this->security->get_csrf_hash(), 'data' => $msg, 'status' => $status));
            //redirect('gassets/categories');
        } else {
            
        }
    }

    public function view_invoice() {

        //  $id=$_GET['id'];
        $date = $_GET['date'];

        // echo $date.' and '.$id;
        // exit();
        $data['ledgerStatementReport'] = $this->Gledgers->get_invoice_summary($date);
        $this->load->view('shared/header');
        $this->load->view('gledger/invoice/invoice_record', $data);
        $this->load->view('shared/footer');
    }
    
  public function drill_ageingData( $supplier_id ,$number_days){
         $data=array();
             $date2 = new DateTime(date('y-m-d'));        
             $values = $this->Helper->getTransactionDate($supplier_id);
             foreach ($values as $value) {
                 $date1 = new DateTime($value->transaction_date);
                 $interval = $date1->diff($date2);   
                 $days=$interval->days;
                 if (0 <= $days  && $days < $number_days) {
                    $temp_data =$value;
                    array_push($data, $temp_data);
                    }
             }
             return $data;
    }

  public function agingStatmentReport1(){
            $number_days = $this->input->get('aging_type');
            $supplier_id = $this->input->get('supplier_id');
           if ($this->input->is_ajax_request()) {
            $datas['ageingReport'] = $this->drill_ageingData( $supplier_id ,$number_days);
            $this->load->view("gledger/invoice/ageingstatement_partial", $datas);
            }elseif (isset($_GET['report'])) {

        $datas['ageingReport'] = $this->drill_ageingData( $supplier_id ,$number_days);
            
        $pdf_content ="<h1 style='text-align:center;'>Agieng Statment </h1>";     
        $pdf_content .= $this->load->view('gledger/invoice/ageingstatement_partial_pdf', $datas, true);

        $this->Helper->pdfReport($pdf_content);      
        }
    }

     public function agingStatmentReport(){
         $supplier_id =$_GET['supplier_id'];
         $supplier_name =$this->Helper->getSupplierById($supplier_id)->suppliername;

           if ($this->input->is_ajax_request()) {

            $data['ageingReport'] = $this->Gledgers->getagingStatementReport();
            $this->load->view("gledger/invoice/ageingstatement_partial", $data);
        } else if (isset($_GET['report'])) {


         $datas['ageingReport'] = $this->Gledgers->getagingStatementReport();
            
        $pdf_content ="<h1 style='text-align:center;'>Agieng General Statment </h1>";     
        $pdf_content .="<h3 style='text-align:center;'>Supplier Name: $supplier_name </h3>";     

        $pdf_content .= $this->load->view('gledger/invoice/ageingstatement_partial_pdf', $datas, true);

        $this->Helper->pdfReport($pdf_content);  

             
            
        } else {

            $data['currentAccountYear'] = $this->Helper->getCurrentAccountYear();
            $data['ledgers'] = $this->Helper->getLedgersList();

            $this->load->view('shared/header');
            $this->load->view("gledger/reports/Ledgerstatement", $data);
            $this->load->view('shared/footer');
        }


    }




    public function cashbookentry() {
        if (isset($_GET['report'])) {
            if (isset($_GET['recieving'])) {
                $data['voucher'] = $this->Helper->getCustomerPayments($_GET['ref']);
                $pdf .= $this->load->view("gledger/reports/paymentreceipt", $data, TRUE);
                $this->Helper->pdfReport($pdf);
            } else if (isset($_GET['paying'])) {
                $data['voucher'] = $this->Helper->getSupplierPayments($_GET['ref']);
                $pdf .= $this->load->view("gledger/reports/paymentvoucher", $data, TRUE);
                $this->Helper->pdfReport($pdf);
            }
        } else {
            if ($this->input->post('checknumber') != null) {
                //save payments
                $payment = array(
                    'checknumber' => $this->input->post('checknumber'),
                    'debit_entry_ledger' => $this->input->post('acc_ledgers_pay'),
                    'journal_date' => $this->input->post('journal_date'),
                    'invoice_id' => $this->input->post('invoice_number_pay'),
                    'amount' => $this->input->post('amount_pay'),
                    'credit_entry_ledger' => $this->input->post('acc_ledgers_bank'),
                    'comment' => $this->input->post('comment')
                );

                $result = $this->Gledgers->saveCashbook($payment);

                $get_last_id = $this->Helper->get_last_id('tbl_invoice_payments', 'id');

                if ($result == 'success') {
                    $this->session->set_flashdata('success', 'Entry saved successifully');
                    redirect('gledger/cashbookentry?paying&ref=' . $get_last_id);
                } else {
                    $this->session->set_flashdata('error', 'An error has occured when saving the transaction.Please try again later.');
                    redirect('gledger/cashbookentry?paying');
                }
            } else if ($this->input->post('acc_ledgers_rev') != null) {
                //save receiving payments
                $payment = array(
                    'checknumber' => '',
                    'debit_entry_ledger' => $this->input->post('acc_ledgers_rev'),
                    'journal_date' => $this->input->post('journal_date'),
                    'invoice_id' => $this->input->post('invoice_number_recv'),
                    'amount' => $this->input->post('amount'),
                    'credit_entry_ledger' => $this->input->post('acc_ledgers_bank'),
                    'comment' => $this->input->post('comment')
                );

                $result = $this->Gledgers->saveCashbook($payment);
//echo $result;exit;
                if ($result == 'success') {
                    $get_last_id = $this->Helper->get_last_id('tbl_invoice_payments_customer', 'id');
                    $this->session->set_flashdata('success', 'Entry saved successifully');
                    redirect('gledger/cashbookentry?recieving&ref=' . $get_last_id);
                } else {
                    $this->session->set_flashdata('error', 'An error has occured when saving the transaction.Please try again later.');
                    redirect('gledger/cashbookentry?recieving');
                }
            } else {
                $this->load->view('shared/header');
                if (isset($_GET['recieving'])) {
                    $data['invoice_List'] = $this->Gledgers->getCustomerInvoices(null, array('unpaid', 'pending'));
                    $data['voucher'] = $this->Helper->getCustomerPayments();

//                    echo '<pre>';
//                    print_r($data['invoice_List']);
//                    exit;
//                    
                    $data['currentAccountYear'] = $this->Helper->getCurrentAccountYear();
                    $data['ledgers'] = $this->Helper->getLedgersList();
                    $data['acc_sections'] = $this->Helper->getList('tbl_acc_section');
                    $this->load->view("gledger/cashbookreceiving", $data);
                } else if (isset($_GET['paying'])) {
                    $data['voucher'] = $this->Helper->getSupplierPayments();
                    $data['invoice_List'] = $this->Gledgers->getInvoices(null, array('unpaid', 'pending'));
                    $data['currentAccountYear'] = $this->Helper->getCurrentAccountYear();
                    $data['ledgers'] = $this->Helper->getLedgersList();
                    $data['acc_sections'] = $this->Helper->getList('tbl_acc_section');
                    $this->load->view("gledger/cashbookpaying", $data);
                } else {
                    $this->load->view("gledger/cashbookentry");
                }
                $this->load->view('shared/footer');
            }
        }
    }

    public function creditnote() {
        if (isset($_GET['report'])) {
                $data['creditnote'] = $this->Helper->getCreditNotes($_GET['ref']);
                $pdf .= $this->load->view("gledger/reports/creditnoteprint", $data, TRUE);
                $this->Helper->pdfReport($pdf);
        } else {
            if (isset($_POST['supplier'])) {
                $msg = "";
                $status = "";
                //process input form 
                $this->form_validation->set_rules('credit_note_date', 'Credit Note Date', 'trim|required');
                $this->form_validation->set_rules('credit_note_number', 'Credit Note Number', 'trim|required');
                $this->form_validation->set_rules('invoice_id', 'Invoice no', 'trim|required');
                $this->form_validation->set_rules('amount_reduce', 'Amount to reduce', 'trim|required');
                $this->form_validation->set_rules('tax', 'Tax', 'trim|required');
                $this->form_validation->set_rules('remark', 'Remark', 'trim|required');
                if ($this->form_validation->run() == FALSE) {
                    $msg = validation_errors();
                } else {

                    $result = $this->Gledgers->add_creditnote();

                    if ($result == 'success') {
                        $msg = "Saved successifully";
                        $status = '1';
                    } else {
                        if ($result == '23000/1062') {
                            $msg = "Legder already exists.";
                        } else {
                            $msg = "An error has occured.Please try again";
                        }

                        $status = '0';
                    }

                    if ($status == '1') {
                        $this->session->set_flashdata('success', 'Entry saved successifully');
                        redirect('gledger/creditnote');
                    } elseif ($status == '0') {
                        $this->session->set_flashdata('error', 'An error has occured while saving the transaction.Please try again later.');
                        redirect('gledger/creditnote');
                    }
                }
            } else {
                if ($this->input->is_ajax_request()) {
                    $invo_id = $_GET['invo_id'];
                    $invoice = $this->Gledgers->getInvoiceDetails($invo_id);
                    $Amount = $invoice[0]->Amount;
                    $inv_date = $invoice[0]->invoice_date;
                    echo json_encode(array('Amount' => $Amount, 'invoice_date' => $inv_date));
                } else {
                    $data['creditnotes'] = $this->Helper->getCreditNotes();
                    $data['suppliers'] = $this->Helper->getList('tbl_supplier');
                    $data['invoices'] = $this->Helper->getList('tbl_invoice');
                    $this->load->view('shared/header');
                    $this->load->view("gledger/creditnote", $data);
                    $this->load->view('shared/footer');
                }
            }
        }
    }
    public function debtnote() {
        if (isset($_POST['invoice_id'])) {
            $msg = "";
            $status = "";
            //process input form 
            $this->form_validation->set_rules('invoice_id', 'Invoice no', 'trim|required');
            $this->form_validation->set_rules('amount_reduce', 'Amount to reduce', 'trim|required');
            $this->form_validation->set_rules('tax', 'Tax', 'trim|required');
            $this->form_validation->set_rules('remark', 'Remark', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                $msg = validation_errors();
            } else {

                $result = $this->Gledgers->add_debtnote();

                if ($result == 'success') {
                    $msg = "Saved successifully";
                    $status = '1';
                } else {
                    if ($result == '23000/1062') {
                        $msg = "Legder already exists.";
                    } else {
                        $msg = "An error has occured.Please try again";
                    }

                    $status = '0';
                }
            }
            
        } else {
            if ($this->input->is_ajax_request()) {
                $invo_id = $_GET['invo_id'];
                $invoice = $this->Gledgers->getInvoiceDetails($invo_id);
                $Amount = $invoice[0]->Amount;
                $inv_date = $invoice[0]->invoice_date;
                echo json_encode(array('Amount' => $Amount, 'invoice_date' => $inv_date));
            } else {
                $data['suppliers'] = $this->Helper->getList('tbl_supplier');
                $data['invoices'] = $this->Helper->getList('tbl_invoice');
                $data['debtnotes'] = $this->Helper->getDebtNotes();
                $this->load->view('shared/header');
                $this->load->view("gledger/debtnote", $data);
                $this->load->view('shared/footer');
            }
        }
    }
     function debtnote_partial($id) {
        $data['debtnote'] = $this->Helper->getDebtNotes($id);
        $pdf_content = "<h1 style='text-align:center;'>DEBIDT NOTE</h1>";
        $pdf_content .= $this->load->view('gledger/reports/debt_note_partial', $data, true);
        $this->Helper->pdfReport($pdf_content);
    }   
}
