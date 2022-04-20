<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Gledgers
 *
 * @author ADE
 */
class Gledgers extends CI_Model {

    private $start_date;
    private $end_date;

    public function __construct() {
        parent::__construct();

        $this->load->model('Gassets_model');
    }

    public function getProfitLoss() {

        if (isset($_GET['start_date'])) {
            $start_date = $this->start_date = $this->input->get('start_date');
            $end_date = $this->end_date = $this->input->get('end_date');

            $this->db->select('acc_name,sum(amount) as sub_total,sec_desc,group_name');
            $this->db->from('tbl_journal_entry je');
            $this->db->join("tbl_journal_entry_details jd", "ON je.trans_id=jd.trans_id");
            $this->db->join("tbl_ledgers lg", "ON lg.ledger_id=jd.ledger_id");
            $this->db->join("tbl_accounts acc", "ON acc.acc_code=lg.acc_code");
            $this->db->join("tbl_acc_group ag", "ON acc.group_id=ag.group_id");

            $this->db->join("tbl_acc_section ase", "ON ase.sec_id=ag.sec_id");

            $this->db->where('journal_date >=', $start_date);
            $this->db->where('journal_date <=', $end_date);
             $this->db->where('jd.status', 'active');
            $this->db->group_by('lg.acc_code');

            $query = $this->db->get();

            return $query->result();
        }
        return null;
    }

    public function getGLAccountsBySection($section_name) {
        if (isset($_GET['start_date'])) {
            $start_date = $this->start_date = $this->input->get('start_date');
            $end_date = $this->end_date = $this->input->get('end_date');

            $this->db->select('acc_name,lg.acc_code,sum(amount) as sub_total,sec_desc,group_name');
            $this->db->from('tbl_journal_entry je');
            $this->db->join("tbl_journal_entry_details jd", "ON je.trans_id=jd.trans_id");
            $this->db->join("tbl_ledgers lg", "ON lg.ledger_id=jd.ledger_id");
            $this->db->join("tbl_accounts acc", "ON acc.acc_code=lg.acc_code");
            $this->db->join("tbl_acc_group ag", "ON acc.group_id=ag.group_id");

            $this->db->join("tbl_acc_section ase", "ON ase.sec_id=ag.sec_id");

            $this->db->where('journal_date >=', $start_date);
            $this->db->where('journal_date <=', $end_date);
            $this->db->where('LOWER(sec_desc) =', strtolower($section_name));
             $this->db->where('jd.status', 'active');
            $this->db->group_by('lg.acc_code');

            $query = $this->db->get();

            return $query->result();
        }
        return null;
    }

    public function create_invoice($attributes) {

        $result = $this->db->insert('tbl_invoice', $attributes);
        if ($result) {
            return 'success';
        } else {
            return $this->db->error()['code'];
        }
    }

    public function getInvoices($id = null, $status = null) {
        if ($id != null) {
            $this->db->where('invoice_id', $id);
        }
        if ($status != null) {
            if (is_array($status)) {
                $this->db->where_in('status', $status);
            } else {
                $this->db->where('status', strtolower($status));
            }
        }

        $query = $this->db->get('tbl_invoice');
        if ($query->num_rows() > 0) {
            if ($id != null) {
                return $query->result()[0];
            } else {
                return $query->result();
            }
        }

        return false;
    }

    public function getCustomerInvoices($id = null, $status = null) {
        if ($id != null) {
            $this->db->where('invoice_ID', $id);
        }
        if ($status != null) {
            if (is_array($status)) {
                $this->db->where_in('status', $status);
            } else {
                $this->db->where('status', strtolower($status));
            }
        }

        $query = $this->db->get('tbl_customer_invoice');
        if ($query->num_rows() > 0) {
            if ($id != null) {
                return $query->result()[0];
            } else {
                return $query->result();
            }
        }

        return false;
    }

    public function getLedgersBySection($section_name, $ledger_id = null, $acc_code = null) {
        if (isset($_GET['start_date'])) {
            $start_date = $this->start_date = $this->input->get('start_date');
            $end_date = $this->end_date = $this->input->get('end_date');

            $qr = "sum(amount)";
            if (!is_null($ledger_id)) {
                $qr = "amount";
            }

            $this->db->select("lg.ledger_id,ledger_name,$qr as sub_total,acc_name,sec_desc,group_name,je.comment");
            $this->db->from('tbl_journal_entry je');
            $this->db->join("tbl_journal_entry_details jd", "ON je.trans_id=jd.trans_id");
            $this->db->join("tbl_ledgers lg", "ON lg.ledger_id=jd.ledger_id");
            $this->db->join("tbl_accounts acc", "ON acc.acc_code=lg.acc_code");
            $this->db->join("tbl_acc_group ag", "ON acc.group_id=ag.group_id");

            $this->db->join("tbl_acc_section ase", "ON ase.sec_id=ag.sec_id");

            $this->db->where('journal_date >=', $start_date);
            $this->db->where('journal_date <=', $end_date);
            $this->db->where('LOWER(sec_desc) =', strtolower($section_name));

            if (!is_null($ledger_id)) {
                $this->db->where('jd.ledger_id=', $ledger_id);
            } else if (!is_null($acc_code)) {
                $this->db->where('lg.acc_code=', $acc_code);
            }

            if (is_null($ledger_id)) {
                $this->db->group_by('lg.ledger_id');
            }


            $query = $this->db->get();

            return $query->result();
        }
        return null;
    }

    public function getCashBook($ledger_id = null) {
        if (isset($_GET['start_date'])) {
            $start_date = $this->start_date = $this->input->get('start_date');
            $end_date = $this->end_date = $this->input->get('end_date');

            $qr = "sum(case when amount > 0 then amount else 0 end) as positive,
                 sum(case when amount < 0 then amount else 0 end) as negative";

            if (!is_null($ledger_id)) {
                $qr = "amount";
            }

            $this->db->select("lg.ledger_id,je.journal_date,ledger_name,$qr ,acc_name,sec_desc,group_name,je.comment");
            $this->db->from('tbl_journal_entry je');
            $this->db->join("tbl_journal_entry_details jd", "ON je.trans_id=jd.trans_id");
            $this->db->join("tbl_ledgers lg", "ON lg.ledger_id=jd.ledger_id");
            $this->db->join("tbl_accounts acc", "ON acc.acc_code=lg.acc_code");
            $this->db->join("tbl_acc_group ag", "ON acc.group_id=ag.group_id");

            $this->db->join("tbl_acc_section ase", "ON ase.sec_id=ag.sec_id");

            $this->db->where('journal_date >=', $start_date);
            $this->db->where('journal_date <=', $end_date);
            $this->db->where('lg.is_cashbook =', 1);

            if (!is_null($ledger_id)) {
                $this->db->where('jd.ledger_id=', $ledger_id);
            }

            if (is_null($ledger_id)) {
                $this->db->group_by('je.journal_date');
            }


            $query = $this->db->get();

            return $query->result();
        }
        return null;
    }

    public function getBalanceSheet() {
        $result = array(
            'Non-Current Assets' => 0,
            'Current Assets' => 0,
            'Current Liabilities' => 0,
            'Non-Current Liabilites' => 0,
            'Financed By' => 0
        );

        if (isset($_GET['start_date'])) {
            $this->start_date = $this->input->get('start_date');
            $this->end_date = $this->input->get('end_date');
            $json = file_get_contents($this->Helper->getConfigValue('EhmsUrl') . '/api/api.php?gacc=balancesheet&start_date=' . $this->start_date . '&end_date=' . $this->end_date);
            $obj = json_decode($json);

            $current_assets = (isset($obj->current_assets) ? $obj->current_assets : 0);
            $gross_profit = (isset($obj->gross_profit) ? $obj->gross_profit : 0);
            $current_liabilities = (isset($obj->current_liabilities) ? $obj->current_liabilities : 0);



            $result = array(
                'non_current_assets' => $this->getNonCurrentAssets(),
                'current_assets' => $current_assets,
                'current_liabilities' => $this->getTax($gross_profit) + $current_liabilities,
                'non_current_liabilites' => $this->getNonCurrentLiabilites(),
                'financed_by' => $this->getNetProfit($gross_profit)
            );
        }

        return $result;
    }

    public function getNetProfit() {
        if (isset($_GET['start_date'])) {
            $retain_ledger_id = $this->Helper->getLedgerByName('Retained Earnings')->ledger_id;
            $start_date = $this->start_date = $this->input->get('start_date');
            $end_date = $this->end_date = $this->input->get('end_date');

            $this->db->select('sum(amount) as netprofit');
            $this->db->from('tbl_journal_entry je');
            $this->db->join("tbl_journal_entry_details jd", "ON je.trans_id=jd.trans_id");

            $this->db->where('journal_date >=', $start_date);
            $this->db->where('journal_date <=', $end_date);
            $this->db->where('jd.ledger_id =', $retain_ledger_id);
            $this->db->where('jd.status', 'active');
            $query = $this->db->get();

            return $query->result()[0]->netprofit;
        }

        return 0;
    }

    private function getTax($grossprofit) {
        $profitBeforeTax = $grossprofit + $this->getOverheads();
        $tax = (((int) $this->Helper->getConfigValue('TaxRate')) / 100) * $profitBeforeTax;

        return $tax;
    }

    private function getOverheads($start_date = null) {
        $assetOverheads = $this->getAssetsOverhead($start_date);

        return 0 - ((empty($assetOverheads) || is_null($assetOverheads)) ? 0 : $assetOverheads);
    }

    private function getNonCurrentAssets() {
        $assets = $this->Helper->getAssetsByPurchaseeByDateRange(null, $this->end_date);

        $carrying_value_total = 0;
        if ($assets) {
            foreach ($assets as $value) {
                $asset_id = $value->asset_id;
                $carrying_value = $this->Helper->calculateDepreciation($asset_id, $this->end_date)['carrying_value'];
                $carrying_value_total += $carrying_value;
            }
        }
        return $carrying_value_total;
    }

    private function getNonCurrentLiabilites() {
        $assets = $this->Helper->getAssetsByPurchaseeByDateRange(null, $this->end_date);

        $purchase_amount_total = 0;
        if ($assets) {
            foreach ($assets as $value) {
                $asset_id = $value->asset_id;
                $purchase_amount = $this->Helper->calculateDepreciation($asset_id, $this->end_date)['purchase_amount'];
                $purchase_amount_total += $purchase_amount;
            }
        }
        return $purchase_amount_total;
    }

    private function getAssetsOverhead($start_date = null) {
        $st_date = $this->start_date;
        if (is_null($start_date)) {
            $st_date = null;
        }
        $assets = $this->Helper->getAssetsByPurchaseeByDateRange($st_date, $this->end_date);

        $depresciation_amount = 0;
        if ($assets) {
            foreach ($assets as $value) {
                $asset_id = $value->asset_id;
                $purchase_date = $value->purchase_date;

                $dept_amount = $this->Helper->calculateDepreciation($asset_id, $this->end_date)['depn_amount'];

                $depresciation_amount += $dept_amount;
            }
        }
        return $depresciation_amount;
    }

    public function addGroup() {
        $group_name = $this->input->post('group_name');
        $sec_id = $this->input->post('section_id');
        $data = array(
            'group_name' => $group_name,
            'sec_id' => $sec_id
        );
        $rs = $this->db->insert('tbl_acc_group', $data);

        if ($rs) {
            return 'success';
        } else {
            return $this->db->error()['code'];
        }
    }

    public function addMainAcc() {
        $mainAcc_name = $this->input->post('mainAcc_name');
        $group_id = $this->input->post('group_id');
        $data = array(
            'main_acc_name' => $mainAcc_name,
            'group_id' => $group_id
        );
        $rs = $this->db->insert('tbl_main_account', $data);

        if ($rs) {
            return 'success';
        } else {
            return $this->db->error()['code'];
        }
    }

    public function addAcc() {
        $Acc_name = $this->input->post('Acc_name');
        $group_id = $this->input->post('group_id');
        $description = $this->input->post('description');
        $data = array(
            'acc_name' => $Acc_name,
            'group_id' => $group_id,
            'description' => $description
        );
        $rs = $this->db->insert('tbl_accounts', $data);

        if ($rs) {
            return 'success';
        } else {
            return $this->db->error()['code'];
        }
    }

    public function addAccYear() {
        $acc_year = $this->input->post('acc_year');
        $descriptions = $this->input->post('descriptions');
        $data = array(
            'account_year' => $acc_year,
            'descriptions' => $descriptions,
            'added_by' => $this->session->userinfo->user_id
        );
        $rs = $this->db->insert('tbl_account_year', $data);

        if ($rs) {
            return 'success';
        } else {
            return $this->db->error()['code'];
        }
    }

    public function addIntLedger() {
        $ledger_name = $this->input->post('ledger_name');
        $acc_code = $this->input->post('acc_code');
        $discription = $this->input->post('discription');
        $is_cashbook = (($this->input->post('is_cashbook') != null) ? $this->input->post('is_cashbook') : "");

        $data = array(
            'ledger_name' => $ledger_name,
            'acc_code' => $acc_code,
            'discription' => $discription,
            'is_cashbook' => $is_cashbook
        );
        $rs = $this->db->insert('tbl_ledgers', $data);

        if ($rs) {
            return 'success';
        } else {
            return $this->db->error()['code'];
        }
    }

    /*
     * addEhmsLedger
     */

    public function addEhmsLedger($data) {

        $rs = $this->db->insert('tbl_ledgers', $data);

        if ($rs) {
            return 'success';
        } else {
            return $this->db->error()['code'];
        }
    }

    public function getEhmsLedger($ehms_based_type) {
        $this->db->select('*');
        $this->db->from('tbl_ledgers,tbl_accounts');
        $this->db->where("tbl_ledgers.acc_code=tbl_accounts.acc_code and ehms_based_type='$ehms_based_type'");
        $result = $this->db->get();
        return $result->result_array();
    }

    public function getAcc() {
        $this->db->select('*');
        $this->db->from('tbl_accounts,tbl_acc_group');
        $this->db->where('tbl_acc_group.group_id=tbl_accounts.group_id');
        $result = $this->db->get();
        return $result->result_array();
    }

    public function getIntLedger() {
        $this->db->select('*');
        $this->db->from('tbl_ledgers,tbl_accounts');
        $this->db->where('tbl_ledgers.acc_code=tbl_accounts.acc_code and is_ehms=0');
        $result = $this->db->get();
        return $result->result_array();
    }

    public function getMainAcc() {
        $this->db->select('*');
        $this->db->from('tbl_main_account,tbl_acc_group');
        $this->db->where('tbl_acc_group.group_id=tbl_main_account.group_id');
        $result = $this->db->get();
        return $result->result_array();
    }

    public function getAccGroup() {
        $this->db->select('*');
        $this->db->from('tbl_acc_group,tbl_acc_section');
        $this->db->where('tbl_acc_group.sec_id=tbl_acc_section.sec_id');
        $result = $this->db->get();
        return $result->result_array();
    }

    function modifyYear($id, $src) {
        $where = array('id' => $id);

        $this->db->trans_begin();
        if ($src == 'activate') {
            $this->db->update('tbl_account_year', array('status' => '0'));
            $data = array(
                'status' => '1'
            );
        } else if ($src == 'deactivate') {
            $data = array(
                'status' => '0'
            );
        }

        $this->db->update('tbl_account_year', $data, $where);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return $this->db->error()['message'];
        } else {
            $this->db->trans_commit();
            return 'success';
        }
    }

    function modifyJournalMonths($month, $src) {
        $accoutYear = $this->Helper->getCurrentAccountYear();
        $id = $accoutYear->id;
        $deactivated_month_indexes = trim($accoutYear->deactivated_month);

        $where = array('id' => $id);

        $yearMothsBelow = array($month);

        foreach ($this->Helper->getMonths() as $key => $mont) {
            // echo $key.' < '.$month.'<br/>';
            if ($key < $month) {
                $yearMothsBelow[] = $key;
            }
        }

        $this->db->trans_begin();
        if ($src == 'activate') {
            $deactivated_month_indexes = str_replace(',' . $month, '', $deactivated_month_indexes);
            $data = array(
                'deactivated_month' => $deactivated_month_indexes
            );
        } else if ($src == 'deactivate') {
            foreach ($yearMothsBelow as $val) {
                if (!preg_match('/' . $val . '/', $deactivated_month_indexes)) {
                    if (empty($deactivated_month_indexes)) {
                        $deactivated_month_indexes .= $val;
                    } else {
                        $deactivated_month_indexes .= ',' . $val;
                    }
                }
            }

            $data = array(
                'deactivated_month' => $deactivated_month_indexes
            );
        }

        $this->db->update('tbl_account_year', $data, $where);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return $this->db->error()['message'];
        } else {
            $this->db->trans_commit();
            return 'success';
        }
    }

    //Reports 

    public function getJournalEntryReport() {
        if (isset($_GET['start_date'])) {
            $start_date = $this->start_date = $this->input->get('start_date');
            $end_date = $this->end_date = $this->input->get('end_date');
            $retain_ledger_id = $this->Helper->getLedgerByName('Retained Earnings')->ledger_id;

            $this->db->select("sec.sec_desc,acc.acc_code,acc.acc_name,trans_date_time,jd.trans_id,jd.ref_no,
            je.user_type,je.Employee_name,comment,CONCAT (u.fname,' ',u.lname)as user,lg.ledger_name,jd.amount");
            $this->db->from('tbl_journal_entry je');
            $this->db->join("tbl_journal_entry_details jd", "ON je.trans_id=jd.trans_id");
            $this->db->join("tbl_ledgers lg", "ON lg.ledger_id=jd.ledger_id");
            $this->db->join("tbl_users u", "ON u.user_id=je.user_transactor", 'left');
            $this->db->join("tbl_accounts acc", "ON acc.acc_code=lg.acc_code");
            $this->db->join("tbl_acc_group grp", "ON grp.group_id=acc.group_id");
            $this->db->join("tbl_acc_section sec", "ON sec.sec_id=grp.sec_id");
            $this->db->where('journal_date >=', $start_date);
            $this->db->where('journal_date <=', $end_date);
            $this->db->where('entry_type !=', 1);
            $this->db->where('jd.status', 'active');

            $query = $this->db->get();

            return $query->result_array();
        }

        return null;
    }

    public function getAccSections() {
        $this->db->select('*');
        $this->db->from('tbl_acc_section');
        $query = $this->db->get();
        return $query->result();
    }

    public function getTrialBalanceReport($sec_name) {
        if (isset($_GET['start_date'])) {
            $start_date = $this->start_date = $this->input->get('start_date');
            $end_date = $this->end_date = $this->input->get('end_date');

            $this->db->select('sec.sec_desc,acc.acc_code,grp.sec_id,acc_name,sum(amount) as sub_total');
            $this->db->from('tbl_journal_entry je');
            $this->db->join("tbl_journal_entry_details jd", "ON je.trans_id=jd.trans_id");
            $this->db->join("tbl_ledgers lg", "ON lg.ledger_id=jd.ledger_id");
            $this->db->join("tbl_accounts acc", "ON acc.acc_code=lg.acc_code");
            $this->db->join("tbl_acc_group grp", "ON grp.group_id=acc.group_id");
            $this->db->join("tbl_acc_section sec", "ON sec.sec_id=grp.sec_id");
            $this->db->where('journal_date >=', $start_date);
            $this->db->where('journal_date <=', $end_date);
            $this->db->where('entry_type !=', 1);
            $this->db->where('sec_desc=', $sec_name);
            $this->db->where('jd.status', 'active');
            $this->db->group_by('lg.acc_code');

            $query = $this->db->get();

            return $query->result();
        }
        return null;
    }

    public function getJournalEntryIDs() {
        if (isset($_GET['start_date'])) {
            $start_date = $this->start_date = $this->input->get('start_date');
            $end_date = $this->end_date = $this->input->get('end_date');

            $this->db->select("trans_date_time,journal_date,trans_id");
            $this->db->from('tbl_journal_entry je');
            $this->db->where('journal_date >=', $start_date);
            $this->db->where('journal_date <=', $end_date);

            $query = $this->db->get();

            return $query->result_array();
        }

        return null;
    }

    function getagingStatementReport() {
        if (isset($_GET['start_date'])) {
            $start_date = $this->input->get('start_date');
            $end_date = $this->input->get('end_date');
            $supplier_id = $this->input->get('supplier_id');

            $this->db->select("iv.invoice_no,iv.invoice_image iv_image,iv.supplier_id ivid,iv.amount,date(iv.transaction_date) as trans_date,sp.suppliername");
            $this->db->from('tbl_invoice iv');
            $this->db->join("tbl_supplier sp", "ON iv.supplier_id = sp.supplier_id");
            $this->db->where('transaction_date >=', $start_date);
            $this->db->where('transaction_date <=', $end_date);
            $this->db->where('iv.supplier_id =', $supplier_id);
            $query = $this->db->get();

            return $query->result_array();
        }

        return null;
    }

    public function delete_invoices($invoice_id) {

        $rs = $this->db->where('invoice_id', $invoice_id);
        $this->db->delete('tbl_invoice');


        if ($rs) {
            return 'success';
        } else {
            return 'failed';
        }
    }

    public function getInvoiceById($id) {
        $this->db->select('*');
        $this->db->from('tbl_invoice');
        $this->db->where('invoice_id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }

    public function update_Invoice($invoice_id, $attributes) {


        $this->db->where('supplier_id', $invoice_id);
        $result = $this->db->update('tbl_invoice', $attributes);
        if ($result) {
            return 'success';
        } else {
            return $this->db->error()['code'];
        }
    }

    public function select_sum_supplier() {


        $this->db->select('supplier_id,date(transaction_date) AS trans_date,SUM(amount) AS amount');
        $this->db->group_by('supplier_id');
        $this->db->group_by('date(transaction_date)');
        $this->db->order_by('amount', 'desc');
        $query = $this->db->get('tbl_invoice', 10);

        // echo $this->db->last_query();
        return $query->result_array();
    }

    function getledgerStatementReport() {
        if (isset($_GET['start_date'])) {
            $start_date = $this->input->get('start_date');
            $end_date = $this->input->get('end_date');
            $acc_ledger = $this->input->get('acc_ledger');

            $this->db->select("trans_date_time,trans_det_id,jd.trans_id, comment,jd.amount");
            $this->db->from('tbl_journal_entry je');
            $this->db->join("tbl_journal_entry_details jd", "ON je.trans_id=jd.trans_id");
            $this->db->join("tbl_ledgers lg", "ON lg.ledger_id=jd.ledger_id");
            $this->db->where('journal_date >=', $start_date);
            $this->db->where('journal_date <=', $end_date);
            $this->db->where('jd.ledger_id =', $acc_ledger);
             $this->db->where('jd.status', 'active');
            $query = $this->db->get();

            return $query->result();
        }

        return null;
    }

    function chartOfAccounts($id) {
        $this->db->select('acc_name,group_name,description,sec_id');
        $this->db->from("tbl_accounts acc");
        $this->db->join("tbl_acc_group ag", "ON acc.group_id=ag.group_id");
        $this->db->where('ag.sec_id =', $id);

        $query = $this->db->get();

        return $query->result();
    }

    function chartOfLedgers($id) {
        $this->db->select('ledger_name,acc_name,group_name,acc.description dis');
        $this->db->from("tbl_ledgers lg");
        $this->db->join("tbl_accounts acc", "ON acc.acc_code=lg.acc_code");
        $this->db->join("tbl_acc_group ag", "ON acc.group_id=ag.group_id");
        $this->db->where('ag.sec_id =', $id);

        $query = $this->db->get();

        return $query->result();
    }

    public function getbankReconciliationById($id) {
        $this->db->select('*')
                ->from('tbl_bank_reconciliation tbc')
                ->join('tbl_ledgers tl', 'tbc.ledger_id=tl.ledger_id', 'left')
                ->where('tbc.id', $id);

        return $this->db->get()->row();
    }

    public function getbankReconciliationDetailsById($id) {
        $this->db->select('*')
                ->from('tbl_bank_reconciliation_details tbcd')
                ->join('tbl_journal_entry_details tjed', 'tbcd.transaction_id = tjed.trans_det_id', 'left')
                ->join('tbl_journal_entry tje', 'tje.trans_id=tjed.trans_id')
                ->where('bank_reconciliation_id', $id);
        return $this->db->get()->result();
    }

    public function getBankREconciliations($start_date, $end_date) {
        $this->db->select('*')
                ->from('tbl_bank_reconciliation tbc')
                ->join('tbl_ledgers tl', 'tbc.ledger_id=tl.ledger_id', 'left')
                ->where('reconciliation_date >=', $start_date)
                ->where('reconciliation_date <=', $end_date);

        return $this->db->get()->result();
    }

    public function saveCashbook($payment) {
        $retain_ledger_id = $this->Helper->getLedgerByName('Retained Earnings')->ledger_id;
        $has_error = false;

        $this->db->trans_begin();
        $debit_entry_ledger_id = $payment['debit_entry_ledger'];
        $credit_entry_ledger_id = $payment['credit_entry_ledger'];
        $comment = $payment['comment'];
        $credit = 0 - $payment['amount'];
        $debt = $payment['amount'];
        $invoice_id = $payment['invoice_id'];


        $this->db->query("INSERT INTO tbl_journal_entry(comment,journal_date,user_transactor) VALUES('$comment','" . $payment['journal_date'] . "','" . $this->session->userinfo->user_id . "')");

        $query = $this->db->query("SELECT trans_id FROM tbl_journal_entry  WHERE user_transactor='" . $this->session->userinfo->user_id . "' ORDER BY trans_id DESC LIMIT 1");

        $trans_id = $query->result()[0]->trans_id;
        $journal = $this->journal_entry_details($trans_id, $debit_entry_ledger_id, $debt, $retain_ledger_id);
        $journal_ = $this->journal_entry_details($trans_id, $credit_entry_ledger_id, $credit, $retain_ledger_id);

        if ($this->input->post('checknumber') != null) {
            $invoice_details = $this->Gledgers->getInvoices($invoice_id);
            $this->db->query("INSERT INTO tbl_invoice_payments(invoice_id,amount_paid,check_number,cashbook_date,employee_id) VALUES('$invoice_id','" . $payment['amount'] . "','" . $payment['checknumber'] . "','" . $payment['journal_date'] . "','" . $this->session->userinfo->user_id . "')");

            $current_paid_amount = $this->Helper->getInvoiceAmountPaid($invoice_id);
            if (($current_paid_amount) >= $invoice_details->Amount) {
                $this->db->query("UPDATE tbl_invoice SET status='paid' WHERE invoice_id='$invoice_id'");
            } else {
                $this->db->query("UPDATE tbl_invoice SET status='pending' WHERE invoice_id='$invoice_id'");
            }
        } else if ($this->input->post('acc_ledgers_rev') != null) {
            $invoice_details = $this->Gledgers->getCustomerInvoices($invoice_id);
            $this->db->query("INSERT INTO tbl_invoice_payments_customer(invoice_id,amount_paid,check_number,cashbook_date,employee_id) VALUES('$invoice_id','" . $payment['amount'] . "','" . $payment['checknumber'] . "','" . $payment['journal_date'] . "','" . $this->session->userinfo->user_id . "')");

            $current_paid_amount = $this->Helper->getInvoiceAmountPaid($invoice_id, 'tbl_invoice_payments_customer');
            if (($current_paid_amount) >= $invoice_details->amount) {
                $this->db->query("UPDATE tbl_customer_invoice SET status='paid' WHERE Invoice_ID='$invoice_id'");
            } else {
                $this->db->query("UPDATE tbl_customer_invoice SET status='pending' WHERE Invoice_ID='$invoice_id'");
            }
        }

        if ($journal == true || $journal_ == true) {
            $has_error = true;
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return $this->db->error()['message'];
        } else {
            if ($has_error) {
                $this->db->trans_rollback();
                return 'An error has occured.Please try again later';
            } else {
                $this->db->trans_commit();
                return 'success';
            }
        }
    }

    public function journal_entry_details($trans_id, $ledger_id, $debtCredit, $retain_ledger_id) {
        $has_error = false;

        $check_error = $this->db->query("INSERT INTO tbl_journal_entry_details(trans_id,ledger_id,amount) VALUES('$trans_id','$ledger_id','$debtCredit')");
        if (!$check_error) {
            $has_error = true;
        }
        $ledger_info = $this->Helper->getSectionNameByLedgerId($ledger_id);
        if (strtolower($ledger_info->sec_desc) == 'expenses' || strtolower($ledger_info->sec_desc) == 'revenues') {
            $check_error1 = $this->db->query("INSERT INTO tbl_journal_entry_details(trans_id,ledger_id,amount,entry_type) VALUES('$trans_id','$retain_ledger_id','$debtCredit','1')");
            if (!$check_error1) {
                $has_error = true;
            }
        }

        return $has_error;
    }

    function getInvoiceDetails($id) {
        $this->db->select('*');
        $this->db->from("tbl_invoice");
        $this->db->where('invoice_id =', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function add_creditnote() {
        $credit_note_date = $this->input->post('credit_note_date');
        $credit_note_number = $this->input->post('credit_note_number');
        $invoice_id = $this->input->post('invoice_id');
        $amount_reduce = $this->input->post('amount_reduce');
        $tax = $this->input->post('tax');
        $remark = $this->input->post('remark');
        
        $data = array(
            'credit_note_date' => $credit_note_date,
            'credit_note_number' => $credit_note_number,
            'invoice_id' => $invoice_id,
            'amount_to_reduce' => $amount_reduce,
            'tax' => $tax,
            'remarks' => $remark,
            'user_id' => $this->session->userinfo->user_id
        );
        
        $rs = $this->db->insert('tbl_credit_note', $data);

        if ($rs) {
            return 'success';
        } else {
            return $this->db->error()['message'];
        }
    }
    function add_debtnote() {
        $invoice_id = $this->input->post('invoice_id');
        $amount_reduce = $this->input->post('amount_reduce');
        $tax = $this->input->post('tax');
        $remark = $this->input->post('remark');
        $data = array(
            'invoice_id' => $invoice_id,
            'amount_to_reduce' => $amount_reduce,
            'tax' => $tax,
            'remarks' => $remark,
            'user_id'=>$this->session->userinfo->user_id
        );
        $rs = $this->db->insert('tbl_debt_note', $data);

        if ($rs) {
            return 'success';
        } else {
            return $this->db->error()['code'];
        }
    }
}
