<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="index.html"> Home</a></li>
            <li><i class="fa fa-laptop"></i> Dashboard</li>						  	
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-4 ">
        <div class="panel panel-primary ">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Transactions </h3>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <a class="list-group-item" href="<?= base_url('JournalEntry') ?>">Journal Entry</a>
                    <a class="list-group-item" href="<?= base_url('JournalEntry/ApproveJournal') ?>">Approve Journal Entry</a>
                    <a class="list-group-item" href="<?= base_url('Gassets/depreciationEntry') ?>">Depreciation Entry</a>
                    <a class="list-group-item" href="<?= base_url('gledger/bankReconciliation') ?>">Bank Reconciliation Entry</a>
		    <a class="list-group-item" href="<?= base_url('Gledger/invoices') ?>">Invoice</a>
                    <a class="list-group-item" href="<?= base_url('gledger/cashbookentry') ?>">Cash Book</a>
                    <a class="list-group-item" href="<?= base_url('gledger/creditnote') ?>">Credit Note</a>
                    <a class="list-group-item" href="<?= base_url('gledger/debtnote') ?>">Debit Note</a>
                    <a class="list-group-item" href="javascript:;">&nbsp;</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel panel-primary ">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Reports </h3>
            </div>
            <div class="panel-body">
                <div class="list-group">
                     <a class="list-group-item" href="<?= base_url('gledger/journalReport') ?>">Journal Entry Reports</a>
                    <a class="list-group-item" href="<?= base_url('gledger/LedgerStatementReport') ?>">Ledger Statement</a>
                    <a class="list-group-item" href="<?= base_url('gledger/trialBalance') ?>">Trial Balance</a>
                    <a class="list-group-item" href="<?= base_url('gledger/profitloss') ?>">Income Statement</a>
                    <a class="list-group-item" href="<?= base_url('gledger/balancesheet') ?>">Balance Sheet</a>
                    <a class="list-group-item" href="<?= base_url('gledger/chartOfAccounts') ?>">Chart of Accounts</a>
                    <a class="list-group-item" href="<?= base_url('gledger/chartOfLedgers') ?>">Chart of ledgers</a>
                   <a class="list-group-item" href="<?= base_url('gledger/cashBook') ?>">Cash Book Report</a>
		  <a class="list-group-item" href="<?= base_url('gledger/bankReconciliationReport') ?>">Bank Reconciliation</a>
		   <a class="list-group-item" href="<?= base_url('gledger/aging_reports') ?>">Ageing Report</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="panel panel-primary ">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Maintainace </h3>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <a class="list-group-item" href="<?= base_url('gledger/acc_section') ?>">Account Sections</a>
                    <a class="list-group-item" href="<?= base_url('gledger/acc_group') ?>">Account Groups</a>
                    <a class="list-group-item" href="<?= base_url('gledger/accounts') ?>">GL Accounts</a>
                    <a class="list-group-item" href="<?= base_url('gledger/ledgers') ?>">Manage Ledgers</a>
                    <a class="list-group-item" href="javascript:;">Budgets</a>
                    <a class="list-group-item" href="<?= base_url('gledger/accountYear') ?>">Accounting Year</a>
                </div>
            </div>
        </div>
    </div>