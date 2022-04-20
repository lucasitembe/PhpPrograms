<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Helper
 *
 * @author ADE
 */
class Helper extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library("pagination");
        //set auto value
        $this->pagination->per_page = $this->getConfigValue('PerPageRecordSize');
    }

    public function record_count($table) {
        return $this->db->count_all($table);
    }

    public function getList($table, $type = 'object') {
        $query = $this->db->get($table);

        if ($type == 'array') {
            return $query->result_array();
        }
        return $query->result();
    }

    public function getRemoteList($list_type) {
        $json = $this->getAPIJason("gacc=$list_type");
        $obj = json_decode($json);

        return $obj;
    }

    public function getConfigValue($config_name) {
        $this->db->select("config_value");
        $this->db->where('config_name', $config_name);
        $query = $this->db->get('tbl_config');

        $result = $query->result()[0];

        return $result->config_value;
    }

//chop helper
    private $exceptions = array(
        'view_bgt_action', 'exp_report_action', 'index'
    );

    function has_access($page = '') {
        $has_access = false;
        if (!empty($page)) {
            if (in_array($page, $this->exceptions)) {
                $has_access = true;
            } else {
                foreach ($_SESSION['sess_id'] as $value) {
                    if (strtolower($value['access_name']) == strtolower($page)) {
                        $has_access = true;
                    }
                }
            }
        }
        return $has_access;
    }

    public function getCountries() {
        $query = $this->db->get('tbl_country');
        if ($query->num_rows() > 0)
            return $query->result();

        return false;
    }

    public function calculateDepreciation($asset_id, $depn_date) {
        $myAsset = $this->Gassets_model->getAssetById($asset_id);
        $purchase_date = $myAsset[0]['purchase_date'];
        $depn_rate = $myAsset[0]['depn_rate'];
        $depn_type = $myAsset[0]['depn_type'];
        $purchase_amount = $myAsset[0]['purchase_price'];
        $currency_code = $myAsset[0]['currency_code'];
        //current year
        $date = DateTime::createFromFormat("Y-m-d", $depn_date);
        $year = $date->format("Y");
        $additional_day = 0;
        if ($this->days_in_month(2, $year) == 29) {
            $additional_day = 1;
        } else if ($this->days_in_month(2, $year) == 28) {
            $additional_day = 0;
        }

        //$day_diff = (strtotime($depn_date)-strtotime($purchase_date))/(24*3600); //days
        $date1 = new DateTime($purchase_date);
        $date2 = new DateTime($depn_date);

        $day_diff = $date2->diff($date1)->format("%a");



        //caluclating depreciation rate and amount per day
        $depn_rate_per_day = $depn_rate / 365;

        $depn_amount_per_day = 0;
        if ($myAsset[0]['depn_type'] == 0) {
            $depn_amount_per_day = ($purchase_amount * $depn_rate_per_day) / 100;
            if ($day_diff > 0) {
                $depn_amount = $depn_amount_per_day * $day_diff;
            } else {
                $depn_amount = $purchase_amount;
            }

            $carrying_value = $purchase_amount - $depn_amount;
        } else if ($myAsset[0]['depn_type'] == 1) {

            $carrying_value = $purchase_amount;
            $depn_amount = 0;
            $total_depn_amount = 0;
            $days = $day_diff;

            while ($days > 0) {
                if ($days >= (365 + $additional_day)) {
                    $depn_rate_per_day = $depn_rate / (365 + $additional_day);
                    $depn_amount_per_day = ($carrying_value * $depn_rate_per_day) / 100;
                    if ($days > 0) {
                        $depn_amount = $depn_amount_per_day * (365 + $additional_day);
                    } else {
                        //$depn_amount += $carrying_value;
                    }
                    $total_depn_amount += $depn_amount;

                    //$carrying_value = $carrying_value-$depn_amount;
                } else if ($days < (365 + $additional_day)) {
                    $depn_rate_per_day = $depn_rate / (365 + $additional_day);
                    $depn_amount_per_day = ($carrying_value * $depn_rate_per_day) / 100;
                    if ($days > 0) {
                        $depn_amount = $depn_amount_per_day * $days;
                    } else {
                        //$depn_amount += $carrying_value;
                    }
                    $total_depn_amount += $depn_amount;
                    //$carrying_value = $carrying_value-$depn_amount;
                }

                $carrying_value = $purchase_amount - $total_depn_amount;

                $days = $days - (365 + $additional_day);
            }//end while
            $depn_amount = $total_depn_amount;
        }


        $data = array(
            'purchase_amount' => $purchase_amount,
            'depn_amount' => $depn_amount,
            'carrying_value' => $carrying_value,
            'day_diff' => $day_diff,
            'currency_code' => $currency_code
        );
        return $data;
    }

    public function getAssetsByPurchaseeByDateRange($start_date, $end_date) {

        $filter = " BETWEEN '$start_date' AND '$end_date'";

        if (is_null($start_date)) {
            $filter = " <='$end_date'";
        }

        $query = $this->db->query("SELECT * FROM tbl_assets WHERE DATE(purchase_date) $filter ");

        if ($query->num_rows() > 0)
            return $query->result();

        return false;
    }

    public function days_in_month($month, $year) {
        return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
    }

    public function getCurrencies() {
        $query = $this->db->get('tbl_currency');
        if ($query->num_rows() > 0)
            return $query->result();

        return false;
    }
    
   public function getTransactionDate($supplierid) {
         $query = $this->db->query("SELECT * FROM  tbl_invoice WHERE supplier_id='$supplierid'");
        // echo $this->db->last_query();
        return $query->result();
    }

    public function getAPIJason($params) {
        return file_get_contents($this->getConfigValue('EhmsUrl') . '/api/api.php?' . $params);
    }

    public function getLedgersList() {
        $query = $this->db->query("SELECT ledger_id,ledger_name,acc_name,ld.acc_code FROM tbl_ledgers ld "
                . "JOIN tbl_accounts acc ON acc.acc_code=ld.acc_code ");

        return $query->result();
    }

    public function getAccountSectionByLedgerID($id) {
        $query = $this->db->query("SELECT acc_sec.sec_id,acc_sec.sec_desc FROM tbl_ledgers ld "
                . "JOIN tbl_accounts acc ON acc.acc_code=ld.acc_code "
                . "JOIN tbl_acc_group gr ON gr.group_id=acc.group_id "
                . "JOIN tbl_acc_section  acc_sec ON acc_sec.sec_id=gr.sec_id "
                . "WHERE ledger_id='$id'");

        return $query->result()[0];
    }

    public function getLedgersBySecId($id) {
        $where = " ";

        if ($id != 'all') {
            $where = " WHERE acc_sec.sec_id='$id'";
        }

        $query = $this->db->query("SELECT ledger_id,ledger_name,acc_name,ld.acc_code FROM tbl_ledgers ld "
                . "JOIN tbl_accounts acc ON acc.acc_code=ld.acc_code "
                . "JOIN tbl_acc_group gr ON gr.group_id=acc.group_id "
                . "JOIN tbl_acc_section  acc_sec ON acc_sec.sec_id=gr.sec_id "
                . "$where");

        //  echo $this->db->last_query();

        return $query->result();
    }

    public function getLedgerById($id) {
        $query = $this->db->query("SELECT ledger_id,ledger_name,ledger_type FROM tbl_ledgers WHERE ledger_id='$id'");
        // echo $this->db->last_query();
        return $query->result()[0];
    }

    public function getSupplierById($id) {
        $query = $this->db->query("SELECT supplier_id,suppliername FROM tbl_supplier WHERE supplier_id='$id'");
        // echo $this->db->last_query();
        return $query->result()[0];
    }
     public function getClientById($id) {
        $query = $this->db->query("SELECT client_id,client_name FROM tbl_clients WHERE client_id='$id'");
        // echo $this->db->last_query();
        return $query->result()[0];
    }
    
    public function getLedgerByName($name) {
        $query = $this->db->query("SELECT ledger_id,ledger_name,ledger_type FROM tbl_ledgers WHERE LOWER(ledger_name)='" . strtolower($name) . "'");
        // echo $this->db->last_query();

        return $query->row();
    }

    public function getLedgerByGroupName($name) {
        $this->db->select("l.ledger_id,l.ledger_name,a.acc_name")
                ->from('tbl_ledgers l')
                ->join('tbl_accounts a', 'l.acc_code=a.acc_code', 'left')
                ->join('tbl_acc_group g', 'a.group_id=g.group_id', 'left')
                ->where('group_name', $name);

        return $this->db->get()->result();
    }

    public function getSectionNameByLedgerId($id) {
        $query = $this->db->query("SELECT ledger_id,ledger_name,acc_sec.sec_id,acc_sec.sec_desc FROM tbl_ledgers ld "
                . "JOIN tbl_accounts acc ON acc.acc_code=ld.acc_code "
                . "JOIN tbl_acc_group gr ON gr.group_id=acc.group_id "
                . "JOIN tbl_acc_section  acc_sec ON acc_sec.sec_id=gr.sec_id "
                . " WHERE ld.ledger_id='$id'");
        return $query->result()[0];
    }

    public function getCurrentUserLedgerJournal() {
        $query = $this->db->query("SELECT * FROM tbl_journal_entry_cache j JOIN tbl_ledgers l ON l.ledger_id=j.ledger_id WHERE user_id='" . $this->session->userinfo->user_id . "' ORDER BY date_time ASC");

        return $query->result();
    }

    public function getAccountYear($id = null) {
        $where = '';

        if (!is_null($id)) {
            //get Current set Account Year
            $where = "WHERE id='$id'";
        }

        $query = $this->db->query("SELECT id,account_year,end_account_year,descriptions,created_at,deactivated_month,added_by,status,fname,lname FROM tbl_account_year a JOIN tbl_users u ON a.added_by=u.user_id "
                . "$where");

        if (!is_null($id)) {
            return $query->result()[0];
        }

        return $query->result();
    }

    public function getCurrentAccountYear() {
        $where = '';

        $query = $this->db->query("SELECT id,account_year,end_account_year,descriptions,created_at,deactivated_month,added_by,status,fname,lname FROM tbl_account_year a JOIN tbl_users u ON a.added_by=u.user_id WHERE status='1'");
        $rs = $query->result();
        return (count($rs) > 0) ? $query->result()[0] : array();
    }

    public function pdfReport($data, $filename = 'report', $is_land = false) {
        header('Content-type:application/pdf');
        // header('Content-Disposition:attachment; filename="'.$filename.'"');
        $html = "<img   width='100%' src='" . base_url() . "assets/images/organizationbanner.png'>";
        $html .= $data;

        $pdfFilePath = $filename . ".pdf";
        $param[] = $is_land;
        //load mPDF library
        $this->load->library('m_pdf', $param);

        //generate the PDF from the given html
        $stylesheet = file_get_contents(base_url() . 'assets/css/mpdf.css'); // external css
        $this->m_pdf->pdf->WriteHTML($stylesheet, 1);
        $this->m_pdf->pdf->WriteHTML($html, 2);

        //download it.
        $this->m_pdf->pdf->Output($pdfFilePath, "I");
    }

    public function excellReport($data, $filename = 'attachment.pdf') {
        $file = "statistics.xls";

        $data = "<h1 style='text-align:center;'>INFECTIOUS DISEASES WEEKLY ENDING</h1>";
        $data .= '<h5 style="text-align:center;font-weight:200">' . $facinfo . '</h5><br/>';
        $data .= $display;

        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$file");

//The header for .xlsx files is Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet

        echo $data;
    }

    public function getAccSections() {
        $this->db->select('*');
        $this->db->from('tbl_acc_section');
        $query = $this->db->get();
        return $query->result();
    }

    public function getMonths() {
        return array("1" => "January", "2" => "February", "3" => "March", "4" => "April", "5" => "May", "6" => "June", "7" => "July", "8" => "August", "9" => "September", "10" => "October", "11" => "November", "12" => "December");
    }

    public function getInvoiceAmountPaid($id, $table = 'tbl_invoice_payments') {
        $query = $this->db->query("SELECT SUM(amount_paid) AS amount_paid  FROM $table WHERE invoice_id='$id'");

        if ($query->num_rows() > 0) {
            return $query->result()[0]->amount_paid;
        } else {
            return 0;
        }
    }

    public function get_last_id($table, $column) {
        $query = $this->db->query("SELECT $column  FROM $table WHERE employee_id='" . $this->session->userinfo->user_id . "' ORDER BY $column DESC LIMIT 1");

        if ($query->num_rows() > 0) {
            return $query->result()[0]->$column;
        } else {
            return null;
        }
    }

    public function getSupplierPayments($id = null) {
        $where = '';
        if (!is_null($id)) {
            $where = " WHERE id='$id'";
        }
        
        $query = $this->db->query("SELECT * FROM tbl_invoice_payments ip
            JOIN tbl_invoice i ON i.invoice_id = ip.invoice_id
            JOIN tbl_supplier s ON s.supplier_id = i.supplier_id
            JOIN tbl_users u ON u.user_id = ip.employee_id
            $where");

        if (!is_null($id)) {
            return $query->result()[0];
        } else {
            return $query->result();
        }
    }

    public function getCustomerPayments($id = null) {
        $where = '';
        if (!is_null($id)) {
            $where = " WHERE id='$id'";
        }
        $query = $this->db->query("SELECT * FROM tbl_invoice_payments_customer ip
            JOIN tbl_customer_invoice i ON i.Invoice_ID = ip.invoice_id
            JOIN tbl_users u ON u.user_id = ip.employee_id
            $where
           ");

        if (!is_null($id)) {
            return $query->result()[0];
        } else {
            return $query->result();
        }
    }
    
    public function getCreditNotes($id = null) {
        $where = '';
        if (!is_null($id)) {
            $where = " WHERE id='$id'";
        }
        $query = $this->db->query("SELECT * FROM tbl_credit_note c
            JOIN tbl_invoice i ON i.Invoice_ID = c.invoice_id
            JOIN tbl_users u ON u.user_id = c.user_id
            JOIN tbl_supplier s ON s.supplier_id = i.supplier_id
            $where
           ");

        if (!is_null($id)) {
            return $query->result()[0];
        } else {
            return $query->result();
        }
    }
 function getDebtNotes($id=null) {
      $this->db->select('debt_note_id,in.invoice_no,sup.suppliername,in.Amount,sup.address,sup.contact_phone,sup.fax,	
      tax,dn.amount_to_reduce,CONCAT(us.fname," ",us.lname) as user,debt_note_date,dn.remarks');
        $this->db->from("tbl_debt_note dn");
        $this->db->join("tbl_invoice in", "ON in.invoice_id=dn.invoice_id");
        $this->db->join("tbl_supplier sup", "ON sup.supplier_id=in.supplier_id");
        $this->db->join("tbl_users us", "ON us.user_id=dn.user_id");
        if(!is_null($id)){
        $this->db->where('debt_note_id =', $id);
        }
        $query = $this->db->get();

        return $query->result();
    }
      public function getInvoicesBySupplierId($supplier_id){
        $this->db->select("*")
                 ->from("tbl_invoice")
                 ->where('supplier_id',$supplier_id);
        return $this->db->get()->result();
    }
}
