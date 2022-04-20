-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2015 at 03:26 PM
-- Server version: 5.5.36
-- PHP Version: 5.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `weberp`
--

-- --------------------------------------------------------

--
-- Table structure for table `accountgroups`
--

CREATE TABLE IF NOT EXISTS `accountgroups` (
  `groupname` char(30) NOT NULL DEFAULT '',
  `sectioninaccounts` int(11) NOT NULL DEFAULT '0',
  `pandl` tinyint(4) NOT NULL DEFAULT '1',
  `sequenceintb` smallint(6) NOT NULL DEFAULT '0',
  `parentgroupname` varchar(30) NOT NULL,
  PRIMARY KEY (`groupname`),
  KEY `SequenceInTB` (`sequenceintb`),
  KEY `sectioninaccounts` (`sectioninaccounts`),
  KEY `parentgroupname` (`parentgroupname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `accountgroups`
--

INSERT INTO `accountgroups` (`groupname`, `sectioninaccounts`, `pandl`, `sequenceintb`, `parentgroupname`) VALUES
('Cost of Goods Sold', 2, 1, 5000, ''),
('Current Assets', 20, 0, 1000, ''),
('Financed', 50, 0, 3000, ''),
('Fixed Assets', 10, 0, 500, ''),
('Giveaways', 5, 1, 6000, 'Promotions'),
('Income Tax', 5, 1, 9000, ''),
('Liabilities', 30, 0, 2000, ''),
('Marketing Expenses', 5, 1, 6000, ''),
('Operating Expenses', 5, 1, 7000, ''),
('Other Revenue and Expenses', 5, 1, 8000, ''),
('Outward Freight', 2, 1, 5000, 'Cost of Goods Sold'),
('Promotions', 5, 1, 6000, 'Marketing Expenses'),
('Revenue', 1, 1, 4000, ''),
('Sales', 1, 1, 10, '');

-- --------------------------------------------------------

--
-- Table structure for table `accountsection`
--

CREATE TABLE IF NOT EXISTS `accountsection` (
  `sectionid` int(11) NOT NULL DEFAULT '0',
  `sectionname` text NOT NULL,
  PRIMARY KEY (`sectionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `accountsection`
--

INSERT INTO `accountsection` (`sectionid`, `sectionname`) VALUES
(1, 'Income'),
(2, 'Cost Of Sales'),
(5, 'Overheads'),
(10, 'Fixed Assets'),
(15, 'Inventory'),
(20, 'Amounts Receivable'),
(25, 'Cash'),
(30, 'Amounts Payable'),
(50, 'Financed By');

-- --------------------------------------------------------

--
-- Table structure for table `areas`
--

CREATE TABLE IF NOT EXISTS `areas` (
  `areacode` char(3) NOT NULL,
  `areadescription` varchar(25) NOT NULL DEFAULT '',
  PRIMARY KEY (`areacode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `assetmanager`
--

CREATE TABLE IF NOT EXISTS `assetmanager` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `serialno` varchar(30) NOT NULL DEFAULT '',
  `location` varchar(15) NOT NULL DEFAULT '',
  `cost` double NOT NULL DEFAULT '0',
  `depn` double NOT NULL DEFAULT '0',
  `datepurchased` date NOT NULL DEFAULT '0000-00-00',
  `disposalvalue` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `audittrail`
--

CREATE TABLE IF NOT EXISTS `audittrail` (
  `transactiondate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `userid` varchar(20) NOT NULL DEFAULT '',
  `querystring` text,
  KEY `UserID` (`userid`),
  KEY `transactiondate` (`transactiondate`),
  KEY `transactiondate_2` (`transactiondate`),
  KEY `transactiondate_3` (`transactiondate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `audittrail`
--

INSERT INTO `audittrail` (`transactiondate`, `userid`, `querystring`) VALUES
('2015-02-25 16:11:27', 'admin', 'UPDATE config\n				SET confvalue=''2015-02-25''\n				WHERE confname=''DB_Maintenance_LastRun'''),
('2015-02-25 16:11:31', 'admin', 'UPDATE currencies SET rate=''1.2683''\n																		WHERE currabrev=''AUD'''),
('2015-02-25 16:11:36', 'admin', 'UPDATE currencies SET rate=''0.646''\n																		WHERE currabrev=''GBP'''),
('2015-02-25 16:11:45', 'admin', 'UPDATE currencies SET rate=''0.8816''\n																		WHERE currabrev=''EUR'''),
('2015-02-25 16:12:00', 'admin', 'UPDATE currencies SET rate=''91.407''\n																		WHERE currabrev=''KES'''),
('2015-02-25 16:12:03', 'admin', 'UPDATE currencies SET rate=''0.9493''\n																		WHERE currabrev=''CHF'''),
('2015-02-25 16:12:03', 'admin', 'UPDATE config SET confvalue = ''2015-02-25'' WHERE confname=''UpdateCurrencyRatesDaily'''),
('2015-02-25 16:12:04', 'admin', 'UPDATE www_users SET lastvisitdate=''2015-02-25 16:12:04''\n							WHERE www_users.userid=''admin''');

-- --------------------------------------------------------

--
-- Table structure for table `bankaccounts`
--

CREATE TABLE IF NOT EXISTS `bankaccounts` (
  `accountcode` varchar(20) NOT NULL DEFAULT '0',
  `currcode` char(3) NOT NULL,
  `invoice` smallint(2) NOT NULL DEFAULT '0',
  `bankaccountcode` varchar(50) NOT NULL DEFAULT '',
  `bankaccountname` char(50) NOT NULL DEFAULT '',
  `bankaccountnumber` char(50) NOT NULL DEFAULT '',
  `bankaddress` char(50) DEFAULT NULL,
  `importformat` varchar(10) NOT NULL DEFAULT '''''',
  PRIMARY KEY (`accountcode`),
  KEY `currcode` (`currcode`),
  KEY `BankAccountName` (`bankaccountname`),
  KEY `BankAccountNumber` (`bankaccountnumber`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bankaccounts`
--

INSERT INTO `bankaccounts` (`accountcode`, `currcode`, `invoice`, `bankaccountcode`, `bankaccountname`, `bankaccountnumber`, `bankaddress`, `importformat`) VALUES
('1010', 'GBP', 2, '123', 'GBP account', '123', '', ''),
('1030', 'AUD', 2, '12445', 'Cheque Account', '124455667789', '123 Straight Street', ''),
('1040', 'AUD', 0, '', 'Savings Account', '', '', ''),
('1060', 'USD', 1, '', 'USD Bank Account', '123', '', 'GIFTS');

-- --------------------------------------------------------

--
-- Table structure for table `bankaccountusers`
--

CREATE TABLE IF NOT EXISTS `bankaccountusers` (
  `accountcode` varchar(20) NOT NULL COMMENT 'Bank account code',
  `userid` varchar(20) NOT NULL COMMENT 'User code'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `banktrans`
--

CREATE TABLE IF NOT EXISTS `banktrans` (
  `banktransid` bigint(20) NOT NULL AUTO_INCREMENT,
  `type` smallint(6) NOT NULL DEFAULT '0',
  `transno` bigint(20) NOT NULL DEFAULT '0',
  `bankact` varchar(20) NOT NULL DEFAULT '0',
  `ref` varchar(50) NOT NULL DEFAULT '',
  `amountcleared` double NOT NULL DEFAULT '0',
  `exrate` double NOT NULL DEFAULT '1' COMMENT 'From bank account currency to payment currency',
  `functionalexrate` double NOT NULL DEFAULT '1' COMMENT 'Account currency to functional currency',
  `transdate` date NOT NULL DEFAULT '0000-00-00',
  `banktranstype` varchar(30) NOT NULL DEFAULT '',
  `amount` double NOT NULL DEFAULT '0',
  `currcode` char(3) NOT NULL DEFAULT '',
  PRIMARY KEY (`banktransid`),
  KEY `BankAct` (`bankact`,`ref`),
  KEY `TransDate` (`transdate`),
  KEY `TransType` (`banktranstype`),
  KEY `Type` (`type`,`transno`),
  KEY `CurrCode` (`currcode`),
  KEY `ref` (`ref`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Table structure for table `bom`
--

CREATE TABLE IF NOT EXISTS `bom` (
  `parent` char(20) NOT NULL DEFAULT '',
  `sequence` int(11) NOT NULL DEFAULT '0',
  `component` char(20) NOT NULL DEFAULT '',
  `workcentreadded` char(5) NOT NULL DEFAULT '',
  `loccode` char(5) NOT NULL DEFAULT '',
  `effectiveafter` date NOT NULL DEFAULT '0000-00-00',
  `effectiveto` date NOT NULL DEFAULT '9999-12-31',
  `quantity` double NOT NULL DEFAULT '1',
  `autoissue` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`parent`,`component`,`workcentreadded`,`loccode`),
  KEY `Component` (`component`),
  KEY `EffectiveAfter` (`effectiveafter`),
  KEY `EffectiveTo` (`effectiveto`),
  KEY `LocCode` (`loccode`),
  KEY `Parent` (`parent`,`effectiveafter`,`effectiveto`,`loccode`),
  KEY `Parent_2` (`parent`),
  KEY `WorkCentreAdded` (`workcentreadded`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `chartdetails`
--

CREATE TABLE IF NOT EXISTS `chartdetails` (
  `accountcode` varchar(20) NOT NULL DEFAULT '0',
  `period` smallint(6) NOT NULL DEFAULT '0',
  `budget` double NOT NULL DEFAULT '0',
  `actual` double NOT NULL DEFAULT '0',
  `bfwd` double NOT NULL DEFAULT '0',
  `bfwdbudget` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`accountcode`,`period`),
  KEY `Period` (`period`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `chartmaster`
--

CREATE TABLE IF NOT EXISTS `chartmaster` (
  `accountcode` varchar(20) NOT NULL DEFAULT '0',
  `accountname` char(50) NOT NULL DEFAULT '',
  `group_` char(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`accountcode`),
  KEY `AccountName` (`accountname`),
  KEY `Group_` (`group_`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `chartmaster`
--

INSERT INTO `chartmaster` (`accountcode`, `accountname`, `group_`) VALUES
('1', 'Default Sales/Discounts', 'Sales'),
('1010', 'Petty Cash', 'Current Assets'),
('1020', 'Cash on Hand', 'Current Assets'),
('1030', 'Cheque Accounts', 'Current Assets'),
('1040', 'Savings Accounts', 'Current Assets'),
('1050', 'Payroll Accounts', 'Current Assets'),
('1060', 'Special Accounts', 'Current Assets'),
('1070', 'Money Market Investments', 'Current Assets'),
('1080', 'Short-Term Investments (< 90 days)', 'Current Assets'),
('1090', 'Interest Receivable', 'Current Assets'),
('1100', 'Accounts Receivable', 'Current Assets'),
('1150', 'Allowance for Doubtful Accounts', 'Current Assets'),
('1200', 'Notes Receivable', 'Current Assets'),
('1250', 'Income Tax Receivable', 'Current Assets'),
('1300', 'Prepaid Expenses', 'Current Assets'),
('1350', 'Advances', 'Current Assets'),
('1400', 'Supplies Inventory', 'Current Assets'),
('1420', 'Raw Material Inventory', 'Current Assets'),
('1440', 'Work in Progress Inventory', 'Current Assets'),
('1460', 'Finished Goods Inventory', 'Current Assets'),
('1500', 'Land', 'Fixed Assets'),
('1550', 'Bonds', 'Fixed Assets'),
('1600', 'Buildings', 'Fixed Assets'),
('1620', 'Accumulated Depreciation of Buildings', 'Fixed Assets'),
('1650', 'Equipment', 'Fixed Assets'),
('1670', 'Accumulated Depreciation of Equipment', 'Fixed Assets'),
('1700', 'Furniture & Fixtures', 'Fixed Assets'),
('1710', 'Accumulated Depreciation of Furniture & Fixtures', 'Fixed Assets'),
('1720', 'Office Equipment', 'Fixed Assets'),
('1730', 'Accumulated Depreciation of Office Equipment', 'Fixed Assets'),
('1740', 'Software', 'Fixed Assets'),
('1750', 'Accumulated Depreciation of Software', 'Fixed Assets'),
('1760', 'Vehicles', 'Fixed Assets'),
('1770', 'Accumulated Depreciation Vehicles', 'Fixed Assets'),
('1780', 'Other Depreciable Property', 'Fixed Assets'),
('1790', 'Accumulated Depreciation of Other Depreciable Prop', 'Fixed Assets'),
('1800', 'Patents', 'Fixed Assets'),
('1850', 'Goodwill', 'Fixed Assets'),
('1900', 'Future Income Tax Receivable', 'Current Assets'),
('2010', 'Bank Indedebtedness (overdraft)', 'Liabilities'),
('2020', 'Retainers or Advances on Work', 'Liabilities'),
('2050', 'Interest Payable', 'Liabilities'),
('2100', 'Accounts Payable', 'Liabilities'),
('2150', 'Goods Received Suspense', 'Liabilities'),
('2200', 'Short-Term Loan Payable', 'Liabilities'),
('2230', 'Current Portion of Long-Term Debt Payable', 'Liabilities'),
('2250', 'Income Tax Payable', 'Liabilities'),
('2300', 'GST Payable', 'Liabilities'),
('2310', 'GST Recoverable', 'Liabilities'),
('2320', 'PST Payable', 'Liabilities'),
('2330', 'PST Recoverable (commission)', 'Liabilities'),
('2340', 'Payroll Tax Payable', 'Liabilities'),
('2350', 'Withholding Income Tax Payable', 'Liabilities'),
('2360', 'Other Taxes Payable', 'Liabilities'),
('2400', 'Employee Salaries Payable', 'Liabilities'),
('2410', 'Management Salaries Payable', 'Liabilities'),
('2420', 'Director / Partner Fees Payable', 'Liabilities'),
('2450', 'Health Benefits Payable', 'Liabilities'),
('2460', 'Pension Benefits Payable', 'Liabilities'),
('2470', 'Canada Pension Plan Payable', 'Liabilities'),
('2480', 'Employment Insurance Premiums Payable', 'Liabilities'),
('2500', 'Land Payable', 'Liabilities'),
('2550', 'Long-Term Bank Loan', 'Liabilities'),
('2560', 'Notes Payable', 'Liabilities'),
('2600', 'Building & Equipment Payable', 'Liabilities'),
('2700', 'Furnishing & Fixture Payable', 'Liabilities'),
('2720', 'Office Equipment Payable', 'Liabilities'),
('2740', 'Vehicle Payable', 'Liabilities'),
('2760', 'Other Property Payable', 'Liabilities'),
('2800', 'Shareholder Loans', 'Liabilities'),
('2900', 'Suspense', 'Liabilities'),
('3100', 'Capital Stock', 'Financed'),
('3200', 'Capital Surplus / Dividends', 'Financed'),
('3300', 'Dividend Taxes Payable', 'Financed'),
('3400', 'Dividend Taxes Refundable', 'Financed'),
('3500', 'Retained Earnings', 'Financed'),
('4100', 'Product / Service Sales', 'Revenue'),
('4200', 'Sales Exchange Gains/Losses', 'Revenue'),
('4500', 'Consulting Services', 'Revenue'),
('4600', 'Rentals', 'Revenue'),
('4700', 'Finance Charge Income', 'Revenue'),
('4800', 'Sales Returns & Allowances', 'Revenue'),
('4900', 'Sales Discounts', 'Revenue'),
('5000', 'Cost of Sales', 'Cost of Goods Sold'),
('5100', 'Production Expenses', 'Cost of Goods Sold'),
('5200', 'Purchases Exchange Gains/Losses', 'Cost of Goods Sold'),
('5500', 'Direct Labour Costs', 'Cost of Goods Sold'),
('5600', 'Freight Charges', 'Outward Freight'),
('5700', 'Inventory Adjustment', 'Cost of Goods Sold'),
('5800', 'Purchase Returns & Allowances', 'Cost of Goods Sold'),
('5900', 'Purchase Discounts', 'Cost of Goods Sold'),
('6100', 'Advertising', 'Marketing Expenses'),
('6150', 'Promotion', 'Promotions'),
('6200', 'Communications', 'Marketing Expenses'),
('6250', 'Meeting Expenses', 'Marketing Expenses'),
('6300', 'Travelling Expenses', 'Marketing Expenses'),
('6400', 'Delivery Expenses', 'Marketing Expenses'),
('6500', 'Sales Salaries & Commission', 'Marketing Expenses'),
('6550', 'Sales Salaries & Commission Deductions', 'Marketing Expenses'),
('6590', 'Benefits', 'Marketing Expenses'),
('6600', 'Other Selling Expenses', 'Marketing Expenses'),
('6700', 'Permits, Licenses & License Fees', 'Marketing Expenses'),
('6800', 'Research & Development', 'Marketing Expenses'),
('6900', 'Professional Services', 'Marketing Expenses'),
('7020', 'Support Salaries & Wages', 'Operating Expenses'),
('7030', 'Support Salary & Wage Deductions', 'Operating Expenses'),
('7040', 'Management Salaries', 'Operating Expenses'),
('7050', 'Management Salary deductions', 'Operating Expenses'),
('7060', 'Director / Partner Fees', 'Operating Expenses'),
('7070', 'Director / Partner Deductions', 'Operating Expenses'),
('7080', 'Payroll Tax', 'Operating Expenses'),
('7090', 'Benefits', 'Operating Expenses'),
('7100', 'Training & Education Expenses', 'Operating Expenses'),
('7150', 'Dues & Subscriptions', 'Operating Expenses'),
('7200', 'Accounting Fees', 'Operating Expenses'),
('7210', 'Audit Fees', 'Operating Expenses'),
('7220', 'Banking Fees', 'Operating Expenses'),
('7230', 'Credit Card Fees', 'Operating Expenses'),
('7240', 'Consulting Fees', 'Operating Expenses'),
('7260', 'Legal Fees', 'Operating Expenses'),
('7280', 'Other Professional Fees', 'Operating Expenses'),
('7300', 'Business Tax', 'Operating Expenses'),
('7350', 'Property Tax', 'Operating Expenses'),
('7390', 'Corporation Capital Tax', 'Operating Expenses'),
('7400', 'Office Rent', 'Operating Expenses'),
('7450', 'Equipment Rental', 'Operating Expenses'),
('7500', 'Office Supplies', 'Operating Expenses'),
('7550', 'Office Repair & Maintenance', 'Operating Expenses'),
('7600', 'Automotive Expenses', 'Operating Expenses'),
('7610', 'Communication Expenses', 'Operating Expenses'),
('7620', 'Insurance Expenses', 'Operating Expenses'),
('7630', 'Postage & Courier Expenses', 'Operating Expenses'),
('7640', 'Miscellaneous Expenses', 'Operating Expenses'),
('7650', 'Travel Expenses', 'Operating Expenses'),
('7660', 'Utilities', 'Operating Expenses'),
('7700', 'Ammortization Expenses', 'Operating Expenses'),
('7750', 'Depreciation Expenses', 'Operating Expenses'),
('7800', 'Interest Expense', 'Operating Expenses'),
('7900', 'Bad Debt Expense', 'Operating Expenses'),
('8100', 'Gain on Sale of Assets', 'Other Revenue and Expenses'),
('8200', 'Interest Income', 'Other Revenue and Expenses'),
('8300', 'Recovery on Bad Debt', 'Other Revenue and Expenses'),
('8400', 'Other Revenue', 'Other Revenue and Expenses'),
('8500', 'Loss on Sale of Assets', 'Other Revenue and Expenses'),
('8600', 'Charitable Contributions', 'Other Revenue and Expenses'),
('8900', 'Other Expenses', 'Other Revenue and Expenses'),
('9100', 'Income Tax Provision', 'Income Tax');

-- --------------------------------------------------------

--
-- Table structure for table `cogsglpostings`
--

CREATE TABLE IF NOT EXISTS `cogsglpostings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `area` char(3) NOT NULL DEFAULT '',
  `stkcat` varchar(6) NOT NULL DEFAULT '',
  `glcode` varchar(20) NOT NULL DEFAULT '0',
  `salestype` char(2) NOT NULL DEFAULT 'AN',
  PRIMARY KEY (`id`),
  UNIQUE KEY `Area_StkCat` (`area`,`stkcat`,`salestype`),
  KEY `Area` (`area`),
  KEY `StkCat` (`stkcat`),
  KEY `GLCode` (`glcode`),
  KEY `SalesType` (`salestype`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `cogsglpostings`
--

INSERT INTO `cogsglpostings` (`id`, `area`, `stkcat`, `glcode`, `salestype`) VALUES
(5, 'AN', 'ANY', '5000', 'AN'),
(6, '123', 'ANY', '6100', 'AN');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE IF NOT EXISTS `companies` (
  `coycode` int(11) NOT NULL DEFAULT '1',
  `coyname` varchar(50) NOT NULL DEFAULT '',
  `gstno` varchar(20) NOT NULL DEFAULT '',
  `companynumber` varchar(20) NOT NULL DEFAULT '0',
  `regoffice1` varchar(40) NOT NULL DEFAULT '',
  `regoffice2` varchar(40) NOT NULL DEFAULT '',
  `regoffice3` varchar(40) NOT NULL DEFAULT '',
  `regoffice4` varchar(40) NOT NULL DEFAULT '',
  `regoffice5` varchar(20) NOT NULL DEFAULT '',
  `regoffice6` varchar(15) NOT NULL DEFAULT '',
  `telephone` varchar(25) NOT NULL DEFAULT '',
  `fax` varchar(25) NOT NULL DEFAULT '',
  `email` varchar(55) NOT NULL DEFAULT '',
  `currencydefault` varchar(4) NOT NULL DEFAULT '',
  `debtorsact` varchar(20) NOT NULL DEFAULT '70000',
  `pytdiscountact` varchar(20) NOT NULL DEFAULT '55000',
  `creditorsact` varchar(20) NOT NULL DEFAULT '80000',
  `payrollact` varchar(20) NOT NULL DEFAULT '84000',
  `grnact` varchar(20) NOT NULL DEFAULT '72000',
  `exchangediffact` varchar(20) NOT NULL DEFAULT '65000',
  `purchasesexchangediffact` varchar(20) NOT NULL DEFAULT '0',
  `retainedearnings` varchar(20) NOT NULL DEFAULT '90000',
  `gllink_debtors` tinyint(1) DEFAULT '1',
  `gllink_creditors` tinyint(1) DEFAULT '1',
  `gllink_stock` tinyint(1) DEFAULT '1',
  `freightact` varchar(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`coycode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`coycode`, `coyname`, `gstno`, `companynumber`, `regoffice1`, `regoffice2`, `regoffice3`, `regoffice4`, `regoffice5`, `regoffice6`, `telephone`, `fax`, `email`, `currencydefault`, `debtorsact`, `pytdiscountact`, `creditorsact`, `payrollact`, `grnact`, `exchangediffact`, `purchasesexchangediffact`, `retainedearnings`, `gllink_debtors`, `gllink_creditors`, `gllink_stock`, `freightact`) VALUES
(1, 'weberp', 'not entered yet', '', '123 Web Way', 'PO Box 123', 'Queen Street', 'Melbourne', 'Victoria 3043', 'Australia', '+61 3 4567 8901', '+61 3 4567 8902', 'weberp@weberpdemo.com', 'USD', '1100', '4900', '2100', '2400', '2150', '4200', '5200', '3500', 1, 1, 1, '5600');

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `confname` varchar(35) NOT NULL DEFAULT '',
  `confvalue` text NOT NULL,
  PRIMARY KEY (`confname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`confname`, `confvalue`) VALUES
('AllowOrderLineItemNarrative', '1'),
('AllowSalesOfZeroCostItems', '0'),
('AutoAuthorisePO', '1'),
('AutoCreateWOs', '1'),
('AutoDebtorNo', '0'),
('AutoIssue', '1'),
('AutoSupplierNo', '0'),
('CheckCreditLimits', '1'),
('Check_Price_Charged_vs_Order_Price', '1'),
('Check_Qty_Charged_vs_Del_Qty', '1'),
('CountryOfOperation', 'US'),
('CreditingControlledItems_MustExist', '0'),
('DB_Maintenance', '1'),
('DB_Maintenance_LastRun', '2015-02-25'),
('DefaultBlindPackNote', '1'),
('DefaultCreditLimit', '1000'),
('DefaultCustomerType', '1'),
('DefaultDateFormat', 'd/m/Y'),
('DefaultDisplayRecordsMax', '50'),
('DefaultFactoryLocation', 'MEL'),
('DefaultPriceList', 'DE'),
('DefaultSupplierType', '1'),
('DefaultTaxCategory', '1'),
('Default_Shipper', '1'),
('DefineControlledOnWOEntry', '1'),
('DispatchCutOffTime', '14'),
('DoFreightCalc', '0'),
('EDIHeaderMsgId', 'D:01B:UN:EAN010'),
('EDIReference', 'WEBERP'),
('EDI_Incoming_Orders', 'companies/test/EDI_Incoming_Orders'),
('EDI_MsgPending', 'companies/test/EDI_Pending'),
('EDI_MsgSent', 'companies/test/EDI__Sent'),
('ExchangeRateFeed', 'Google'),
('Extended_CustomerInfo', '1'),
('Extended_SupplierInfo', '1'),
('FactoryManagerEmail', 'manager@company.com'),
('FreightChargeAppliesIfLessThan', '1000'),
('FreightTaxCategory', '1'),
('FrequentlyOrderedItems', '0'),
('geocode_integration', '0'),
('GoogleTranslatorAPIKey', ''),
('HTTPS_Only', '0'),
('InventoryManagerEmail', 'test@company.com'),
('InvoicePortraitFormat', '0'),
('InvoiceQuantityDefault', '1'),
('ItemDescriptionLanguages', 'fr_FR.utf8,'),
('LogPath', ''),
('LogSeverity', '0'),
('MaxImageSize', '300'),
('MonthsAuditTrail', '1'),
('NumberOfMonthMustBeShown', '6'),
('NumberOfPeriodsOfStockUsage', '12'),
('OverChargeProportion', '30'),
('OverReceiveProportion', '20'),
('PackNoteFormat', '1'),
('PageLength', '48'),
('part_pics_dir', 'companies/weberpdemo/part_pics'),
('PastDueDays1', '30'),
('PastDueDays2', '60'),
('PO_AllowSameItemMultipleTimes', '1'),
('ProhibitJournalsToControlAccounts', '1'),
('ProhibitNegativeStock', '0'),
('ProhibitPostingsBefore', '2013-12-31'),
('PurchasingManagerEmail', 'test@company.com'),
('QualityCOAText', ''),
('QualityLogSamples', '0'),
('QualityProdSpecText', ''),
('QuickEntries', '10'),
('RadioBeaconFileCounter', '/home/RadioBeacon/FileCounter'),
('RadioBeaconFTP_user_name', 'RadioBeacon ftp server user name'),
('RadioBeaconHomeDir', '/home/RadioBeacon'),
('RadioBeaconStockLocation', 'BL'),
('RadioBraconFTP_server', '192.168.2.2'),
('RadioBreaconFilePrefix', 'ORDXX'),
('RadionBeaconFTP_user_pass', 'Radio Beacon remote ftp server password'),
('reports_dir', 'companies/weberpdemo/reportwriter'),
('RequirePickingNote', '0'),
('RomalpaClause', 'Ownership will not pass to the buyer until the goods have been paid for in full.'),
('ShopAboutUs', 'This web-shop software has been developed by Logic Works Ltd for webERP. For support contact Phil Daintree by rn<a href=\\"mailto:support@logicworks.co.nz\\">email</a>rn'),
('ShopAllowBankTransfer', '1'),
('ShopAllowCreditCards', '1'),
('ShopAllowPayPal', '1'),
('ShopAllowSurcharges', '1'),
('ShopBankTransferSurcharge', '0.0'),
('ShopBranchCode', 'ANGRY'),
('ShopContactUs', 'For support contact Logic Works Ltd by rn<a href=\\"mailto:support@logicworks.co.nz\\">email</a>'),
('ShopCreditCardBankAccount', '1030'),
('ShopCreditCardGateway', 'SwipeHQ'),
('ShopCreditCardSurcharge', '2.95'),
('ShopDebtorNo', 'ANGRY'),
('ShopFreightMethod', 'NoFreight'),
('ShopFreightPolicy', 'Shipping information'),
('ShopManagerEmail', 'shopmanager@yourdomain.com'),
('ShopMode', 'test'),
('ShopName', 'webERP Demo Store'),
('ShopPayFlowMerchant', ''),
('ShopPayFlowPassword', ''),
('ShopPayFlowUser', ''),
('ShopPayFlowVendor', ''),
('ShopPayPalBankAccount', '1040'),
('ShopPaypalCommissionAccount', '1'),
('ShopPayPalPassword', ''),
('ShopPayPalProPassword', ''),
('ShopPayPalProSignature', ''),
('ShopPayPalProUser', ''),
('ShopPayPalSignature', ''),
('ShopPayPalSurcharge', '3.4'),
('ShopPayPalUser', ''),
('ShopPrivacyStatement', '<h2>We are committed to protecting your privacy.</h2><p>We recognise that your personal information is confidential and we understand that it is important for you to know how we treat your personal information. Please read on for more information about our Privacy Policy.</p><ul><li><h2>1. What information do we collect and how do we use it?</h2><br />We use the information it collects from you for the following purposes:<ul><li>To assist us in providing you with a quality service</li><li>To respond to, and process, your request</li><li>To notify competition winners or fulfil promotional obligations</li><li>To inform you of, and provide you with, new and existing products and services offered by us from time to time </li></ul><p>Any information we collect will not be used in ways that you have not consented to.</p><p>If you send us an email, we will store your email address and the contents of the email. This information will only be used for the purpose for which you have provided it. Electronic mail submitted to us is handled and saved according to the provisions of the the relevant statues.</p><p>When we offer contests and promotions, customers who choose to enter are asked to provide personal information. This information may then be used by us to notify winners, or to fulfil promotional obligations.</p><p>We may use the information we collect to occasionally notify you about important functionality changes to our website, new and special offers we think you will find valuable. If at any stage you no longer wish to receive these notifications you may opt out by sending us an email.</p><p>We do monitor this website in order to identify user trends and to improve the site if necessary. Any of this information, such as the type of site browser your computer has, will be used only in aggregate form and your individual details will not be identified.</p></li><li><h2>2. How do we store and protect your personal information and who has access to that information?</h2><p>As required by statute, we follow strict procedures when storing and using the information you have provided.</p><p>We do not sell, trade or rent your personal information to others. We may provide aggregate statistics about our customers and website trends. However, these statistics will not have any personal information which would identify you.</p><p>Only specific employees within our company are able to access your personal data.</p><p>This policy means that we may require proof of identity before we disclose any information to you.</p></li><li><h2>3. What should I do if I want to change my details or if I donÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢t want to be contacted any more?</h2><p>At any stage you have the right to access and amend or update your personal details. If you do not want to receive any communications from us you may opt out by contacting us see <a href=\\"index.php?Page=ContactUs\\">the Contact Us Page</a></p></li><li><h2>4. What happens if we decide to change this Privacy Policy?</h2><p>If we change any aspect of our Privacy Policy we will post these changes on this page so that you are always aware of how we are treating your personal information.</p></li><li><h2>5. How can you contact us if you have any questions, comments or concerns about our Privacy Policy?</h2><p>We welcome any questions or comments you may have please email us via the contact details provided on our <a href=\\"index.php?Page=ContactUs\\">Contact Us Page</a></p></li></ul><p>Please also refer to our <a href=\\"index.php?Page=TermsAndConditions\\">Terms and Conditions</a> for more information.</p>'),
('ShopShowOnlyAvailableItems', '0'),
('ShopShowQOHColumn', '1'),
('ShopStockLocations', 'MEL,TOR'),
('ShopSurchargeStockID', 'PAYTSURCHARGE'),
('ShopSwipeHQAPIKey', ''),
('ShopSwipeHQMerchantID', ''),
('ShopTermsConditions', '<p>These terms cover the use of this website. Use includes visits to our sites, purchases on our sites, participation in our database and promotions. These terms of use apply to you when you use our websites. Please read these terms carefully - if you need to refer to them again they can be accessed from the link at the bottom of any page of our websites.</p><br /><ul><li><h2>1. Content</h2><p>While we endeavour to supply accurate information on this site, errors and omissions may occur. We do not accept any liability, direct or indirect, for any loss or damage which may directly or indirectly result from any advice, opinion, information, representation or omission whether negligent or otherwise, contained on this site. You are solely responsible for the actions you take in reliance on the content on, or accessed, through this site.</p><p>We reserve the right to make changes to the content on this site at any time and without notice.</p><p>To the extent permitted by law, we make no warranties in relation to the merchantability, fitness for purpose, freedom from computer virus, accuracy or availability of this web site or any other web site.</p></li><li><h2>2. Making a contract with us</h2><p>When you place an order with us, you are making an offer to buy goods. We will send you an e-mail to confirm that we have received and accepted your order, which indicates that a contract has been made between us. We will take payment from you when we accept your order. In the unlikely event that the goods are no longer available, we will refund your payment to the account it originated from, and advise that the goods are no longer available.</p><p>An order is placed on our website via adding a product to the shopping cart and proceeding through our checkout process. The checkout process includes giving us delivery and any other relevant details for your order, entering payment information and submitting your order. The final step consists of a confirmation page with full details of your order, which you are able to print as a receipt of your order. We will also email you with confirmation of your order.</p><p>We reserve the right to refuse or cancel any orders that we believe, solely by our own judgement, to be placed for commercial purposes, e.g. any kind of reseller. We also reserve the right to refuse or cancel any orders that we believe, solely by our own judgement, to have been placed fraudulently.</p><p>We reserve the right to limit the number of an item customers can purchase in a single transaction.</p></li><li><h2>3. Payment options</h2><p>We currently accept the following credit cards:</p><ul><li>Visa</li><li>MasterCard</li><li>American Express</li></ul>You can also pay using PayPal and internet bank transfer. Surcharges may apply for payment by PayPal or credit cards.</p></li><li><h2>4. Pricing</h2><p>All prices listed are inclusive of relevant taxes.  All prices are correct when published. Please note that we reserve the right to alter prices at any time for any reason. If this should happen after you have ordered a product, we will contact you prior to processing your order. Online and in store pricing may differ.</p></li><li><h2>5. Website and Credit Card Security</h2><p>We want you to have a safe and secure shopping experience online. All payments via our sites are processed using SSL (Secure Socket Layer) protocol, whereby sensitive information is encrypted to protect your privacy.</p><p>You can help to protect your details from unauthorised access by logging out each time you finish using the site, particularly if you are doing so from a public or shared computer.</p><p>For security purposes certain transactions may require proof of identification.</p></li><li><h2>6. Delivery and Delivery Charges</h2><p>We do not deliver to Post Office boxes.</p><p>Please note that a signature is required for all deliveries. The goods become the recipientÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢s property and responsibility once they have been signed for at the time of delivery. If goods are lost or damaged in transit, please contact us within 7 business days <a href=\\"index.php?Page=ContactUs\\">see Contact Us page for contact details</a>. We will use this delivery information to make a claim against our courier company. We will offer you the choice of a replacement or a full refund, once we have received confirmation from our courier company that delivery was not successful.</p></li><li><h2>7. Restricted Products</h2><p>Some products on our site carry an age restriction, if a product you have selected is R16 or R18 a message will appear in the cart asking you to confirm you are an appropriate age to purchase the item(s).  Confirming this means that you are of an eligible age to purchase the selected product(s).  You are also agreeing that you are not purchasing the item on behalf of a person who is not the appropriate age.</p></li><li><h2>8. Delivery Period</h2><p>Delivery lead time for products may vary. Deliveries to rural addresses may take longer.  You will receive an email that confirms that your order has been dispatched.</p><p>To ensure successful delivery, please provide a delivery address where someone will be present during business hours to sign for the receipt of your package. You can track your order by entering the tracking number emailed to you in the dispatch email at the Courier\\''s web-site.</p></li><li><h2>9. Disclaimer</h2><p>Our websites are intended to provide information for people shopping our products and accessing our services, including making purchases via our website and registering on our database to receive e-mails from us.</p><p>While we endeavour to supply accurate information on this site, errors and omissions may occur. We do not accept any liability, direct or indirect, for any loss or damage which may directly or indirectly result from any advice, opinion, information, representation or omission whether negligent or otherwise, contained on this site. You are solely responsible for the actions you take in reliance on the content on, or accessed, through this site.</p><p>We reserve the right to make changes to the content on this site at any time and without notice.</p><p>To the extent permitted by law, we make no warranties in relation to the merchantability, fitness for purpose, freedom from computer virus, accuracy or availability of this web site or any other web site.</p></li><li><h2>10. Links</h2><p>Please note that although this site has some hyperlinks to other third party websites, these sites have not been prepared by us are not under our control. The links are only provided as a convenience, and do not imply that we endorse, check, or approve of the third party site. We are not responsible for the privacy principles or content of these third party sites. We are not responsible for the availability of any of these links.</p></li><li><h2>11. Jurisdiction</h2><p>This website is governed by, and is to be interpreted in accordance with, the laws of  ????.</p></li><li><h2>12. Changes to this Agreement</h2><p>We reserve the right to alter, modify or update these terms of use. These terms apply to your order. We may change our terms and conditions at any time, so please do not assume that the same terms will apply to future orders.</p></li></ul>'),
('ShopTitle', 'Shop Home'),
('ShowStockidOnImages', '0'),
('ShowValueOnGRN', '1'),
('Show_Settled_LastMonth', '1'),
('SmtpSetting', '0'),
('SO_AllowSameItemMultipleTimes', '1'),
('StandardCostDecimalPlaces', '2'),
('TaxAuthorityReferenceName', ''),
('UpdateCurrencyRatesDaily', '2015-02-25'),
('VersionNumber', '4.12.2'),
('WeightedAverageCosting', '0'),
('WikiApp', '0'),
('WikiPath', 'wiki'),
('WorkingDaysWeek', '5'),
('YearEnd', '3');

-- --------------------------------------------------------

--
-- Table structure for table `contractbom`
--

CREATE TABLE IF NOT EXISTS `contractbom` (
  `contractref` varchar(20) NOT NULL DEFAULT '0',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `workcentreadded` char(5) NOT NULL DEFAULT '',
  `quantity` double NOT NULL DEFAULT '1',
  PRIMARY KEY (`contractref`,`stockid`,`workcentreadded`),
  KEY `Stockid` (`stockid`),
  KEY `ContractRef` (`contractref`),
  KEY `WorkCentreAdded` (`workcentreadded`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `contractcharges`
--

CREATE TABLE IF NOT EXISTS `contractcharges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contractref` varchar(20) NOT NULL,
  `transtype` smallint(6) NOT NULL DEFAULT '20',
  `transno` int(11) NOT NULL DEFAULT '0',
  `amount` double NOT NULL DEFAULT '0',
  `narrative` text NOT NULL,
  `anticipated` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `contractref` (`contractref`,`transtype`,`transno`),
  KEY `contractcharges_ibfk_2` (`transtype`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contractreqts`
--

CREATE TABLE IF NOT EXISTS `contractreqts` (
  `contractreqid` int(11) NOT NULL AUTO_INCREMENT,
  `contractref` varchar(20) NOT NULL DEFAULT '0',
  `requirement` varchar(40) NOT NULL DEFAULT '',
  `quantity` double NOT NULL DEFAULT '1',
  `costperunit` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`contractreqid`),
  KEY `ContractRef` (`contractref`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `contracts`
--

CREATE TABLE IF NOT EXISTS `contracts` (
  `contractref` varchar(20) NOT NULL DEFAULT '',
  `contractdescription` text NOT NULL,
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `branchcode` varchar(10) NOT NULL DEFAULT '',
  `loccode` varchar(5) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `categoryid` varchar(6) NOT NULL DEFAULT '',
  `orderno` int(11) NOT NULL DEFAULT '0',
  `customerref` varchar(20) NOT NULL DEFAULT '',
  `margin` double NOT NULL DEFAULT '1',
  `wo` int(11) NOT NULL DEFAULT '0',
  `requireddate` date NOT NULL DEFAULT '0000-00-00',
  `drawing` varchar(50) NOT NULL DEFAULT '',
  `exrate` double NOT NULL DEFAULT '1',
  PRIMARY KEY (`contractref`),
  KEY `OrderNo` (`orderno`),
  KEY `CategoryID` (`categoryid`),
  KEY `Status` (`status`),
  KEY `WO` (`wo`),
  KEY `loccode` (`loccode`),
  KEY `DebtorNo` (`debtorno`,`branchcode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE IF NOT EXISTS `currencies` (
  `currency` char(20) NOT NULL DEFAULT '',
  `currabrev` char(3) NOT NULL DEFAULT '',
  `country` char(50) NOT NULL DEFAULT '',
  `hundredsname` char(15) NOT NULL DEFAULT 'Cents',
  `decimalplaces` tinyint(3) NOT NULL DEFAULT '2',
  `rate` double NOT NULL DEFAULT '1',
  `webcart` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'If 1 shown in weberp cart. if 0 no show',
  PRIMARY KEY (`currabrev`),
  KEY `Country` (`country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`currency`, `currabrev`, `country`, `hundredsname`, `decimalplaces`, `rate`, `webcart`) VALUES
('Australian Dollars', 'AUD', 'Australia', 'cents', 2, 1.2683, 0),
('Swiss Francs', 'CHF', 'Swizerland', 'centimes', 2, 0.9493, 0),
('Euro', 'EUR', 'Euroland', 'cents', 2, 0.8816, 1),
('Pounds', 'GBP', 'England', 'Pence', 2, 0.646, 0),
('Kenyian Shillings', 'KES', 'Kenya', 'none', 0, 91.407, 0),
('US Dollars', 'USD', 'United States', 'Cents', 2, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `custallocns`
--

CREATE TABLE IF NOT EXISTS `custallocns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amt` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `datealloc` date NOT NULL DEFAULT '0000-00-00',
  `transid_allocfrom` int(11) NOT NULL DEFAULT '0',
  `transid_allocto` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `DateAlloc` (`datealloc`),
  KEY `TransID_AllocFrom` (`transid_allocfrom`),
  KEY `TransID_AllocTo` (`transid_allocto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `custbranch`
--

CREATE TABLE IF NOT EXISTS `custbranch` (
  `branchcode` varchar(10) NOT NULL DEFAULT '',
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `brname` varchar(40) NOT NULL DEFAULT '',
  `braddress1` varchar(40) NOT NULL DEFAULT '',
  `braddress2` varchar(40) NOT NULL DEFAULT '',
  `braddress3` varchar(40) NOT NULL DEFAULT '',
  `braddress4` varchar(50) NOT NULL DEFAULT '',
  `braddress5` varchar(20) NOT NULL DEFAULT '',
  `braddress6` varchar(40) NOT NULL DEFAULT '',
  `lat` float(10,6) NOT NULL DEFAULT '0.000000',
  `lng` float(10,6) NOT NULL DEFAULT '0.000000',
  `estdeliverydays` smallint(6) NOT NULL DEFAULT '1',
  `area` char(3) NOT NULL,
  `salesman` varchar(4) NOT NULL DEFAULT '',
  `fwddate` smallint(6) NOT NULL DEFAULT '0',
  `phoneno` varchar(20) NOT NULL DEFAULT '',
  `faxno` varchar(20) NOT NULL DEFAULT '',
  `contactname` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(55) NOT NULL DEFAULT '',
  `defaultlocation` varchar(5) NOT NULL DEFAULT '',
  `taxgroupid` tinyint(4) NOT NULL DEFAULT '1',
  `defaultshipvia` int(11) NOT NULL DEFAULT '1',
  `deliverblind` tinyint(1) DEFAULT '1',
  `disabletrans` tinyint(4) NOT NULL DEFAULT '0',
  `brpostaddr1` varchar(40) NOT NULL DEFAULT '',
  `brpostaddr2` varchar(40) NOT NULL DEFAULT '',
  `brpostaddr3` varchar(40) NOT NULL DEFAULT '',
  `brpostaddr4` varchar(50) NOT NULL DEFAULT '',
  `brpostaddr5` varchar(20) NOT NULL DEFAULT '',
  `brpostaddr6` varchar(40) NOT NULL DEFAULT '',
  `specialinstructions` text NOT NULL,
  `custbranchcode` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`branchcode`,`debtorno`),
  KEY `BrName` (`brname`),
  KEY `DebtorNo` (`debtorno`),
  KEY `Salesman` (`salesman`),
  KEY `Area` (`area`),
  KEY `DefaultLocation` (`defaultlocation`),
  KEY `DefaultShipVia` (`defaultshipvia`),
  KEY `taxgroupid` (`taxgroupid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `custcontacts`
--

CREATE TABLE IF NOT EXISTS `custcontacts` (
  `contid` int(11) NOT NULL AUTO_INCREMENT,
  `debtorno` varchar(10) NOT NULL,
  `contactname` varchar(40) NOT NULL,
  `role` varchar(40) NOT NULL,
  `phoneno` varchar(20) NOT NULL,
  `notes` varchar(255) NOT NULL,
  `email` varchar(55) NOT NULL,
  PRIMARY KEY (`contid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `custitem`
--

CREATE TABLE IF NOT EXISTS `custitem` (
  `debtorno` char(10) NOT NULL DEFAULT '',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `cust_part` varchar(20) NOT NULL DEFAULT '',
  `cust_description` varchar(30) NOT NULL DEFAULT '',
  `customersuom` char(50) NOT NULL DEFAULT '',
  `conversionfactor` double NOT NULL DEFAULT '1',
  PRIMARY KEY (`debtorno`,`stockid`),
  KEY `StockID` (`stockid`),
  KEY `Debtorno` (`debtorno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `custnotes`
--

CREATE TABLE IF NOT EXISTS `custnotes` (
  `noteid` tinyint(4) NOT NULL AUTO_INCREMENT,
  `debtorno` varchar(10) NOT NULL DEFAULT '0',
  `href` varchar(100) NOT NULL,
  `note` text NOT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `priority` varchar(20) NOT NULL,
  PRIMARY KEY (`noteid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `debtorsmaster`
--

CREATE TABLE IF NOT EXISTS `debtorsmaster` (
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `name` varchar(40) NOT NULL DEFAULT '',
  `address1` varchar(40) NOT NULL DEFAULT '',
  `address2` varchar(40) NOT NULL DEFAULT '',
  `address3` varchar(40) NOT NULL DEFAULT '',
  `address4` varchar(50) NOT NULL DEFAULT '',
  `address5` varchar(20) NOT NULL DEFAULT '',
  `address6` varchar(40) NOT NULL DEFAULT '',
  `currcode` char(3) NOT NULL DEFAULT '',
  `salestype` char(2) NOT NULL DEFAULT '',
  `clientsince` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `holdreason` smallint(6) NOT NULL DEFAULT '0',
  `paymentterms` char(2) NOT NULL DEFAULT 'f',
  `discount` double NOT NULL DEFAULT '0',
  `pymtdiscount` double NOT NULL DEFAULT '0',
  `lastpaid` double NOT NULL DEFAULT '0',
  `lastpaiddate` datetime DEFAULT NULL,
  `creditlimit` double NOT NULL DEFAULT '1000',
  `invaddrbranch` tinyint(4) NOT NULL DEFAULT '0',
  `discountcode` char(2) NOT NULL DEFAULT '',
  `ediinvoices` tinyint(4) NOT NULL DEFAULT '0',
  `ediorders` tinyint(4) NOT NULL DEFAULT '0',
  `edireference` varchar(20) NOT NULL DEFAULT '',
  `editransport` varchar(5) NOT NULL DEFAULT 'email',
  `ediaddress` varchar(50) NOT NULL DEFAULT '',
  `ediserveruser` varchar(20) NOT NULL DEFAULT '',
  `ediserverpwd` varchar(20) NOT NULL DEFAULT '',
  `taxref` varchar(20) NOT NULL DEFAULT '',
  `customerpoline` tinyint(1) NOT NULL DEFAULT '0',
  `typeid` tinyint(4) NOT NULL DEFAULT '1',
  `language_id` varchar(10) NOT NULL DEFAULT 'en_GB.utf8',
  PRIMARY KEY (`debtorno`),
  KEY `Currency` (`currcode`),
  KEY `HoldReason` (`holdreason`),
  KEY `Name` (`name`),
  KEY `PaymentTerms` (`paymentterms`),
  KEY `SalesType` (`salestype`),
  KEY `EDIInvoices` (`ediinvoices`),
  KEY `EDIOrders` (`ediorders`),
  KEY `debtorsmaster_ibfk_5` (`typeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `debtortrans`
--

CREATE TABLE IF NOT EXISTS `debtortrans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transno` int(11) NOT NULL DEFAULT '0',
  `type` smallint(6) NOT NULL DEFAULT '0',
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `branchcode` varchar(10) NOT NULL DEFAULT '',
  `trandate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `inputdate` datetime NOT NULL,
  `prd` smallint(6) NOT NULL DEFAULT '0',
  `settled` tinyint(4) NOT NULL DEFAULT '0',
  `reference` varchar(20) NOT NULL DEFAULT '',
  `tpe` char(2) NOT NULL DEFAULT '',
  `order_` int(11) NOT NULL DEFAULT '0',
  `rate` double NOT NULL DEFAULT '0',
  `ovamount` double NOT NULL DEFAULT '0',
  `ovgst` double NOT NULL DEFAULT '0',
  `ovfreight` double NOT NULL DEFAULT '0',
  `ovdiscount` double NOT NULL DEFAULT '0',
  `diffonexch` double NOT NULL DEFAULT '0',
  `alloc` double NOT NULL DEFAULT '0',
  `invtext` text,
  `shipvia` int(11) NOT NULL DEFAULT '0',
  `edisent` tinyint(4) NOT NULL DEFAULT '0',
  `consignment` varchar(20) NOT NULL DEFAULT '',
  `packages` int(11) NOT NULL DEFAULT '1' COMMENT 'number of cartons',
  `salesperson` varchar(4) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `DebtorNo` (`debtorno`,`branchcode`),
  KEY `Order_` (`order_`),
  KEY `Prd` (`prd`),
  KEY `Tpe` (`tpe`),
  KEY `Type` (`type`),
  KEY `Settled` (`settled`),
  KEY `TranDate` (`trandate`),
  KEY `TransNo` (`transno`),
  KEY `Type_2` (`type`,`transno`),
  KEY `EDISent` (`edisent`),
  KEY `salesperson` (`salesperson`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

-- --------------------------------------------------------

--
-- Table structure for table `debtortranstaxes`
--

CREATE TABLE IF NOT EXISTS `debtortranstaxes` (
  `debtortransid` int(11) NOT NULL DEFAULT '0',
  `taxauthid` tinyint(4) NOT NULL DEFAULT '0',
  `taxamount` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`debtortransid`,`taxauthid`),
  KEY `taxauthid` (`taxauthid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `debtortype`
--

CREATE TABLE IF NOT EXISTS `debtortype` (
  `typeid` tinyint(4) NOT NULL AUTO_INCREMENT,
  `typename` varchar(100) NOT NULL,
  PRIMARY KEY (`typeid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `debtortypenotes`
--

CREATE TABLE IF NOT EXISTS `debtortypenotes` (
  `noteid` tinyint(4) NOT NULL AUTO_INCREMENT,
  `typeid` tinyint(4) NOT NULL DEFAULT '0',
  `href` varchar(100) NOT NULL,
  `note` varchar(200) NOT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `priority` varchar(20) NOT NULL,
  PRIMARY KEY (`noteid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `deliverynotes`
--

CREATE TABLE IF NOT EXISTS `deliverynotes` (
  `deliverynotenumber` int(11) NOT NULL,
  `deliverynotelineno` tinyint(4) NOT NULL,
  `salesorderno` int(11) NOT NULL,
  `salesorderlineno` int(11) NOT NULL,
  `qtydelivered` double NOT NULL DEFAULT '0',
  `printed` tinyint(4) NOT NULL DEFAULT '0',
  `invoiced` tinyint(4) NOT NULL DEFAULT '0',
  `deliverydate` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`deliverynotenumber`,`deliverynotelineno`),
  KEY `deliverynotes_ibfk_2` (`salesorderno`,`salesorderlineno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE IF NOT EXISTS `departments` (
  `departmentid` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) NOT NULL DEFAULT '',
  `authoriser` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`departmentid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `discountmatrix`
--

CREATE TABLE IF NOT EXISTS `discountmatrix` (
  `salestype` char(2) NOT NULL DEFAULT '',
  `discountcategory` char(2) NOT NULL DEFAULT '',
  `quantitybreak` int(11) NOT NULL DEFAULT '1',
  `discountrate` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`salestype`,`discountcategory`,`quantitybreak`),
  KEY `QuantityBreak` (`quantitybreak`),
  KEY `DiscountCategory` (`discountcategory`),
  KEY `SalesType` (`salestype`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ediitemmapping`
--

CREATE TABLE IF NOT EXISTS `ediitemmapping` (
  `supporcust` varchar(4) NOT NULL DEFAULT '',
  `partnercode` varchar(10) NOT NULL DEFAULT '',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `partnerstockid` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`supporcust`,`partnercode`,`stockid`),
  KEY `PartnerCode` (`partnercode`),
  KEY `StockID` (`stockid`),
  KEY `PartnerStockID` (`partnerstockid`),
  KEY `SuppOrCust` (`supporcust`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `edimessageformat`
--

CREATE TABLE IF NOT EXISTS `edimessageformat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partnercode` varchar(10) NOT NULL DEFAULT '',
  `messagetype` varchar(6) NOT NULL DEFAULT '',
  `section` varchar(7) NOT NULL DEFAULT '',
  `sequenceno` int(11) NOT NULL DEFAULT '0',
  `linetext` varchar(70) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `PartnerCode` (`partnercode`,`messagetype`,`sequenceno`),
  KEY `Section` (`section`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `edi_orders_segs`
--

CREATE TABLE IF NOT EXISTS `edi_orders_segs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `segtag` char(3) NOT NULL DEFAULT '',
  `seggroup` tinyint(4) NOT NULL DEFAULT '0',
  `maxoccur` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `SegTag` (`segtag`),
  KEY `SegNo` (`seggroup`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=96 ;

--
-- Dumping data for table `edi_orders_segs`
--

INSERT INTO `edi_orders_segs` (`id`, `segtag`, `seggroup`, `maxoccur`) VALUES
(1, 'UNB', 0, 1),
(2, 'UNH', 0, 1),
(3, 'BGM', 0, 1),
(4, 'DTM', 0, 35),
(5, 'PAI', 0, 1),
(6, 'ALI', 0, 5),
(7, 'FTX', 0, 99),
(8, 'RFF', 1, 1),
(9, 'DTM', 1, 5),
(10, 'NAD', 2, 1),
(11, 'LOC', 2, 99),
(12, 'FII', 2, 5),
(13, 'RFF', 3, 1),
(14, 'CTA', 5, 1),
(15, 'COM', 5, 5),
(16, 'TAX', 6, 1),
(17, 'MOA', 6, 1),
(18, 'CUX', 7, 1),
(19, 'DTM', 7, 5),
(20, 'PAT', 8, 1),
(21, 'DTM', 8, 5),
(22, 'PCD', 8, 1),
(23, 'MOA', 9, 1),
(24, 'TDT', 10, 1),
(25, 'LOC', 11, 1),
(26, 'DTM', 11, 5),
(27, 'TOD', 12, 1),
(28, 'LOC', 12, 2),
(29, 'PAC', 13, 1),
(30, 'PCI', 14, 1),
(31, 'RFF', 14, 1),
(32, 'DTM', 14, 5),
(33, 'GIN', 14, 10),
(34, 'EQD', 15, 1),
(35, 'ALC', 19, 1),
(36, 'ALI', 19, 5),
(37, 'DTM', 19, 5),
(38, 'QTY', 20, 1),
(39, 'RNG', 20, 1),
(40, 'PCD', 21, 1),
(41, 'RNG', 21, 1),
(42, 'MOA', 22, 1),
(43, 'RNG', 22, 1),
(44, 'RTE', 23, 1),
(45, 'RNG', 23, 1),
(46, 'TAX', 24, 1),
(47, 'MOA', 24, 1),
(48, 'LIN', 28, 1),
(49, 'PIA', 28, 25),
(50, 'IMD', 28, 99),
(51, 'MEA', 28, 99),
(52, 'QTY', 28, 99),
(53, 'ALI', 28, 5),
(54, 'DTM', 28, 35),
(55, 'MOA', 28, 10),
(56, 'GIN', 28, 127),
(57, 'QVR', 28, 1),
(58, 'FTX', 28, 99),
(59, 'PRI', 32, 1),
(60, 'CUX', 32, 1),
(61, 'DTM', 32, 5),
(62, 'RFF', 33, 1),
(63, 'DTM', 33, 5),
(64, 'PAC', 34, 1),
(65, 'QTY', 34, 5),
(66, 'PCI', 36, 1),
(67, 'RFF', 36, 1),
(68, 'DTM', 36, 5),
(69, 'GIN', 36, 10),
(70, 'LOC', 37, 1),
(71, 'QTY', 37, 1),
(72, 'DTM', 37, 5),
(73, 'TAX', 38, 1),
(74, 'MOA', 38, 1),
(75, 'NAD', 39, 1),
(76, 'CTA', 42, 1),
(77, 'COM', 42, 5),
(78, 'ALC', 43, 1),
(79, 'ALI', 43, 5),
(80, 'DTM', 43, 5),
(81, 'QTY', 44, 1),
(82, 'RNG', 44, 1),
(83, 'PCD', 45, 1),
(84, 'RNG', 45, 1),
(85, 'MOA', 46, 1),
(86, 'RNG', 46, 1),
(87, 'RTE', 47, 1),
(88, 'RNG', 47, 1),
(89, 'TAX', 48, 1),
(90, 'MOA', 48, 1),
(91, 'TDT', 49, 1),
(92, 'UNS', 50, 1),
(93, 'MOA', 50, 1),
(94, 'CNT', 50, 1),
(95, 'UNT', 50, 1);

-- --------------------------------------------------------

--
-- Table structure for table `edi_orders_seg_groups`
--

CREATE TABLE IF NOT EXISTS `edi_orders_seg_groups` (
  `seggroupno` tinyint(4) NOT NULL DEFAULT '0',
  `maxoccur` int(4) NOT NULL DEFAULT '0',
  `parentseggroup` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`seggroupno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `edi_orders_seg_groups`
--

INSERT INTO `edi_orders_seg_groups` (`seggroupno`, `maxoccur`, `parentseggroup`) VALUES
(0, 1, 0),
(1, 9999, 0),
(2, 99, 0),
(3, 99, 2),
(5, 5, 2),
(6, 5, 0),
(7, 5, 0),
(8, 10, 0),
(9, 9999, 8),
(10, 10, 0),
(11, 10, 10),
(12, 5, 0),
(13, 99, 0),
(14, 5, 13),
(15, 10, 0),
(19, 99, 0),
(20, 1, 19),
(21, 1, 19),
(22, 2, 19),
(23, 1, 19),
(24, 5, 19),
(28, 200000, 0),
(32, 25, 28),
(33, 9999, 28),
(34, 99, 28),
(36, 5, 34),
(37, 9999, 28),
(38, 10, 28),
(39, 999, 28),
(42, 5, 39),
(43, 99, 28),
(44, 1, 43),
(45, 1, 43),
(46, 2, 43),
(47, 1, 43),
(48, 5, 43),
(49, 10, 28),
(50, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `emailsettings`
--

CREATE TABLE IF NOT EXISTS `emailsettings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `host` varchar(30) NOT NULL,
  `port` char(5) NOT NULL,
  `heloaddress` varchar(20) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(30) DEFAULT NULL,
  `timeout` int(11) DEFAULT '5',
  `companyname` varchar(50) DEFAULT NULL,
  `auth` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `factorcompanies`
--

CREATE TABLE IF NOT EXISTS `factorcompanies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coyname` varchar(50) NOT NULL DEFAULT '',
  `address1` varchar(40) NOT NULL DEFAULT '',
  `address2` varchar(40) NOT NULL DEFAULT '',
  `address3` varchar(40) NOT NULL DEFAULT '',
  `address4` varchar(40) NOT NULL DEFAULT '',
  `address5` varchar(20) NOT NULL DEFAULT '',
  `address6` varchar(15) NOT NULL DEFAULT '',
  `contact` varchar(25) NOT NULL DEFAULT '',
  `telephone` varchar(25) NOT NULL DEFAULT '',
  `fax` varchar(25) NOT NULL DEFAULT '',
  `email` varchar(55) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `factor_name` (`coyname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `fixedassetcategories`
--

CREATE TABLE IF NOT EXISTS `fixedassetcategories` (
  `categoryid` char(6) NOT NULL DEFAULT '',
  `categorydescription` char(20) NOT NULL DEFAULT '',
  `costact` varchar(20) NOT NULL DEFAULT '0',
  `depnact` varchar(20) NOT NULL DEFAULT '0',
  `disposalact` varchar(20) NOT NULL DEFAULT '80000',
  `accumdepnact` varchar(20) NOT NULL DEFAULT '0',
  `defaultdepnrate` double NOT NULL DEFAULT '0.2',
  `defaultdepntype` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`categoryid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fixedassetlocations`
--

CREATE TABLE IF NOT EXISTS `fixedassetlocations` (
  `locationid` char(6) NOT NULL DEFAULT '',
  `locationdescription` char(20) NOT NULL DEFAULT '',
  `parentlocationid` char(6) DEFAULT '',
  PRIMARY KEY (`locationid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fixedassets`
--

CREATE TABLE IF NOT EXISTS `fixedassets` (
  `assetid` int(11) NOT NULL AUTO_INCREMENT,
  `serialno` varchar(30) NOT NULL DEFAULT '',
  `barcode` varchar(20) NOT NULL,
  `assetlocation` varchar(6) NOT NULL DEFAULT '',
  `cost` double NOT NULL DEFAULT '0',
  `accumdepn` double NOT NULL DEFAULT '0',
  `datepurchased` date NOT NULL DEFAULT '0000-00-00',
  `disposalproceeds` double NOT NULL DEFAULT '0',
  `assetcategoryid` varchar(6) NOT NULL DEFAULT '',
  `description` varchar(50) NOT NULL DEFAULT '',
  `longdescription` text NOT NULL,
  `depntype` int(11) NOT NULL DEFAULT '1',
  `depnrate` double NOT NULL,
  `disposaldate` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`assetid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `fixedassettasks`
--

CREATE TABLE IF NOT EXISTS `fixedassettasks` (
  `taskid` int(11) NOT NULL AUTO_INCREMENT,
  `assetid` int(11) NOT NULL,
  `taskdescription` text NOT NULL,
  `frequencydays` int(11) NOT NULL DEFAULT '365',
  `lastcompleted` date NOT NULL,
  `userresponsible` varchar(20) NOT NULL,
  `manager` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`taskid`),
  KEY `assetid` (`assetid`),
  KEY `userresponsible` (`userresponsible`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `fixedassettrans`
--

CREATE TABLE IF NOT EXISTS `fixedassettrans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assetid` int(11) NOT NULL,
  `transtype` tinyint(4) NOT NULL,
  `transdate` date NOT NULL,
  `transno` int(11) NOT NULL,
  `periodno` smallint(6) NOT NULL,
  `inputdate` date NOT NULL,
  `fixedassettranstype` varchar(8) NOT NULL,
  `amount` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `assetid` (`assetid`,`transtype`,`transno`),
  KEY `inputdate` (`inputdate`),
  KEY `transdate` (`transdate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `freightcosts`
--

CREATE TABLE IF NOT EXISTS `freightcosts` (
  `shipcostfromid` int(11) NOT NULL AUTO_INCREMENT,
  `locationfrom` varchar(5) NOT NULL DEFAULT '',
  `destinationcountry` varchar(40) NOT NULL,
  `destination` varchar(40) NOT NULL DEFAULT '',
  `shipperid` int(11) NOT NULL DEFAULT '0',
  `cubrate` double NOT NULL DEFAULT '0',
  `kgrate` double NOT NULL DEFAULT '0',
  `maxkgs` double NOT NULL DEFAULT '999999',
  `maxcub` double NOT NULL DEFAULT '999999',
  `fixedprice` double NOT NULL DEFAULT '0',
  `minimumchg` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`shipcostfromid`),
  KEY `Destination` (`destination`),
  KEY `LocationFrom` (`locationfrom`),
  KEY `ShipperID` (`shipperid`),
  KEY `Destination_2` (`destination`,`locationfrom`,`shipperid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `geocode_param`
--

CREATE TABLE IF NOT EXISTS `geocode_param` (
  `geocodeid` tinyint(4) NOT NULL AUTO_INCREMENT,
  `geocode_key` varchar(200) NOT NULL DEFAULT '',
  `center_long` varchar(20) NOT NULL DEFAULT '',
  `center_lat` varchar(20) NOT NULL DEFAULT '',
  `map_height` varchar(10) NOT NULL DEFAULT '',
  `map_width` varchar(10) NOT NULL DEFAULT '',
  `map_host` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`geocodeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `gltrans`
--

CREATE TABLE IF NOT EXISTS `gltrans` (
  `counterindex` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(6) NOT NULL DEFAULT '0',
  `typeno` bigint(16) NOT NULL DEFAULT '1',
  `chequeno` int(11) NOT NULL DEFAULT '0',
  `trandate` date NOT NULL DEFAULT '0000-00-00',
  `periodno` smallint(6) NOT NULL DEFAULT '0',
  `account` varchar(20) NOT NULL DEFAULT '0',
  `narrative` varchar(200) NOT NULL DEFAULT '',
  `amount` double NOT NULL DEFAULT '0',
  `posted` tinyint(4) NOT NULL DEFAULT '0',
  `jobref` varchar(20) NOT NULL DEFAULT '',
  `tag` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`counterindex`),
  KEY `Account` (`account`),
  KEY `ChequeNo` (`chequeno`),
  KEY `PeriodNo` (`periodno`),
  KEY `Posted` (`posted`),
  KEY `TranDate` (`trandate`),
  KEY `TypeNo` (`typeno`),
  KEY `Type_and_Number` (`type`,`typeno`),
  KEY `JobRef` (`jobref`),
  KEY `tag` (`tag`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=186 ;

-- --------------------------------------------------------

--
-- Table structure for table `grns`
--

CREATE TABLE IF NOT EXISTS `grns` (
  `grnbatch` smallint(6) NOT NULL DEFAULT '0',
  `grnno` int(11) NOT NULL AUTO_INCREMENT,
  `podetailitem` int(11) NOT NULL DEFAULT '0',
  `itemcode` varchar(20) NOT NULL DEFAULT '',
  `deliverydate` date NOT NULL DEFAULT '0000-00-00',
  `itemdescription` varchar(100) NOT NULL DEFAULT '',
  `qtyrecd` double NOT NULL DEFAULT '0',
  `quantityinv` double NOT NULL DEFAULT '0',
  `supplierid` varchar(10) NOT NULL DEFAULT '',
  `stdcostunit` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`grnno`),
  KEY `DeliveryDate` (`deliverydate`),
  KEY `ItemCode` (`itemcode`),
  KEY `PODetailItem` (`podetailitem`),
  KEY `SupplierID` (`supplierid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `holdreasons`
--

CREATE TABLE IF NOT EXISTS `holdreasons` (
  `reasoncode` smallint(6) NOT NULL DEFAULT '1',
  `reasondescription` char(30) NOT NULL DEFAULT '',
  `dissallowinvoices` tinyint(4) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`reasoncode`),
  KEY `ReasonDescription` (`reasondescription`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `holdreasons`
--

INSERT INTO `holdreasons` (`reasoncode`, `reasondescription`, `dissallowinvoices`) VALUES
(1, 'Good History', 0),
(20, 'Watch', 2),
(51, 'In liquidation', 1);

-- --------------------------------------------------------

--
-- Table structure for table `internalstockcatrole`
--

CREATE TABLE IF NOT EXISTS `internalstockcatrole` (
  `categoryid` varchar(6) NOT NULL,
  `secroleid` int(11) NOT NULL,
  PRIMARY KEY (`categoryid`,`secroleid`),
  KEY `internalstockcatrole_ibfk_1` (`categoryid`),
  KEY `internalstockcatrole_ibfk_2` (`secroleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `labelfields`
--

CREATE TABLE IF NOT EXISTS `labelfields` (
  `labelfieldid` int(11) NOT NULL AUTO_INCREMENT,
  `labelid` tinyint(4) NOT NULL,
  `fieldvalue` varchar(20) NOT NULL,
  `vpos` double NOT NULL DEFAULT '0',
  `hpos` double NOT NULL DEFAULT '0',
  `fontsize` tinyint(4) NOT NULL,
  `barcode` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`labelfieldid`),
  KEY `labelid` (`labelid`),
  KEY `vpos` (`vpos`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `labels`
--

CREATE TABLE IF NOT EXISTS `labels` (
  `labelid` tinyint(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(50) NOT NULL,
  `pagewidth` double NOT NULL DEFAULT '0',
  `pageheight` double NOT NULL DEFAULT '0',
  `height` double NOT NULL DEFAULT '0',
  `width` double NOT NULL DEFAULT '0',
  `topmargin` double NOT NULL DEFAULT '0',
  `leftmargin` double NOT NULL DEFAULT '0',
  `rowheight` double NOT NULL DEFAULT '0',
  `columnwidth` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`labelid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `lastcostrollup`
--

CREATE TABLE IF NOT EXISTS `lastcostrollup` (
  `stockid` char(20) NOT NULL DEFAULT '',
  `totalonhand` double NOT NULL DEFAULT '0',
  `matcost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `labcost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `oheadcost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `categoryid` char(6) NOT NULL DEFAULT '',
  `stockact` varchar(20) NOT NULL DEFAULT '0',
  `adjglact` varchar(20) NOT NULL DEFAULT '0',
  `newmatcost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `newlabcost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `newoheadcost` decimal(20,4) NOT NULL DEFAULT '0.0000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE IF NOT EXISTS `locations` (
  `loccode` varchar(5) NOT NULL DEFAULT '',
  `locationname` varchar(50) NOT NULL DEFAULT '',
  `deladd1` varchar(40) NOT NULL DEFAULT '',
  `deladd2` varchar(40) NOT NULL DEFAULT '',
  `deladd3` varchar(40) NOT NULL DEFAULT '',
  `deladd4` varchar(40) NOT NULL DEFAULT '',
  `deladd5` varchar(20) NOT NULL DEFAULT '',
  `deladd6` varchar(15) NOT NULL DEFAULT '',
  `tel` varchar(30) NOT NULL DEFAULT '',
  `fax` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(55) NOT NULL DEFAULT '',
  `contact` varchar(30) NOT NULL DEFAULT '',
  `taxprovinceid` tinyint(4) NOT NULL DEFAULT '1',
  `cashsalecustomer` varchar(10) DEFAULT '',
  `managed` int(11) DEFAULT '0',
  `cashsalebranch` varchar(10) DEFAULT '',
  `internalrequest` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Allow (1) or not (0) internal request from this location',
  `usedforwo` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`loccode`),
  UNIQUE KEY `locationname` (`locationname`),
  KEY `taxprovinceid` (`taxprovinceid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`loccode`, `locationname`, `deladd1`, `deladd2`, `deladd3`, `deladd4`, `deladd5`, `deladd6`, `tel`, `fax`, `email`, `contact`, `taxprovinceid`, `cashsalecustomer`, `managed`, `cashsalebranch`, `internalrequest`, `usedforwo`) VALUES
('AN', 'Anaheim', ' ', '', '', '', '', 'United States', '', '', '', 'Brett', 1, '', 0, '', 0, 1),
('MEL', 'Melbourne', '1234 Collins Street', 'Melbourne', 'Victoria 2345', '', '2345', 'Australia', '+(61) (3) 5678901', '+61 3 56789013', 'jacko@webdemo.com', 'Jack Roberts', 1, '', 0, '', 1, 1),
('TOR', 'Toronto', 'Level 100 ', 'CN Tower', 'Toronto', '', '', '', '', '', '', 'Clive Contrary', 1, '', 1, '', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `locationusers`
--

CREATE TABLE IF NOT EXISTS `locationusers` (
  `loccode` varchar(5) NOT NULL,
  `userid` varchar(20) NOT NULL,
  `canview` tinyint(4) NOT NULL DEFAULT '0',
  `canupd` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`loccode`,`userid`),
  KEY `UserId` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `locstock`
--

CREATE TABLE IF NOT EXISTS `locstock` (
  `loccode` varchar(5) NOT NULL DEFAULT '',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `quantity` double NOT NULL DEFAULT '0',
  `reorderlevel` bigint(20) NOT NULL DEFAULT '0',
  `bin` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`loccode`,`stockid`),
  KEY `StockID` (`stockid`),
  KEY `bin` (`bin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `loctransfers`
--

CREATE TABLE IF NOT EXISTS `loctransfers` (
  `reference` int(11) NOT NULL DEFAULT '0',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `shipqty` double NOT NULL DEFAULT '0',
  `recqty` double NOT NULL DEFAULT '0',
  `shipdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `recdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `shiploc` varchar(7) NOT NULL DEFAULT '',
  `recloc` varchar(7) NOT NULL DEFAULT '',
  KEY `Reference` (`reference`,`stockid`),
  KEY `ShipLoc` (`shiploc`),
  KEY `RecLoc` (`recloc`),
  KEY `StockID` (`stockid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores Shipments To And From Locations';

-- --------------------------------------------------------

--
-- Table structure for table `mailgroupdetails`
--

CREATE TABLE IF NOT EXISTS `mailgroupdetails` (
  `groupname` varchar(100) NOT NULL,
  `userid` varchar(20) NOT NULL,
  KEY `userid` (`userid`),
  KEY `groupname` (`groupname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mailgroups`
--

CREATE TABLE IF NOT EXISTS `mailgroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupname` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `groupname` (`groupname`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `manufacturers`
--

CREATE TABLE IF NOT EXISTS `manufacturers` (
  `manufacturers_id` int(11) NOT NULL AUTO_INCREMENT,
  `manufacturers_name` varchar(32) NOT NULL,
  `manufacturers_url` varchar(50) NOT NULL DEFAULT '',
  `manufacturers_image` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`manufacturers_id`),
  KEY `manufacturers_name` (`manufacturers_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `mrpcalendar`
--

CREATE TABLE IF NOT EXISTS `mrpcalendar` (
  `calendardate` date NOT NULL,
  `daynumber` int(6) NOT NULL,
  `manufacturingflag` smallint(6) NOT NULL DEFAULT '1',
  PRIMARY KEY (`calendardate`),
  KEY `daynumber` (`daynumber`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mrpdemands`
--

CREATE TABLE IF NOT EXISTS `mrpdemands` (
  `demandid` int(11) NOT NULL AUTO_INCREMENT,
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `mrpdemandtype` varchar(6) NOT NULL DEFAULT '',
  `quantity` double NOT NULL DEFAULT '0',
  `duedate` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`demandid`),
  KEY `StockID` (`stockid`),
  KEY `mrpdemands_ibfk_1` (`mrpdemandtype`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mrpdemandtypes`
--

CREATE TABLE IF NOT EXISTS `mrpdemandtypes` (
  `mrpdemandtype` varchar(6) NOT NULL DEFAULT '',
  `description` char(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`mrpdemandtype`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mrpplannedorders`
--

CREATE TABLE IF NOT EXISTS `mrpplannedorders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `part` char(20) DEFAULT NULL,
  `duedate` date DEFAULT NULL,
  `supplyquantity` double DEFAULT NULL,
  `ordertype` varchar(6) DEFAULT NULL,
  `orderno` int(11) DEFAULT NULL,
  `mrpdate` date DEFAULT NULL,
  `updateflag` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE IF NOT EXISTS `offers` (
  `offerid` int(11) NOT NULL AUTO_INCREMENT,
  `tenderid` int(11) NOT NULL DEFAULT '0',
  `supplierid` varchar(10) NOT NULL DEFAULT '',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `quantity` double NOT NULL DEFAULT '0',
  `uom` varchar(15) NOT NULL DEFAULT '',
  `price` double NOT NULL DEFAULT '0',
  `expirydate` date NOT NULL DEFAULT '0000-00-00',
  `currcode` char(3) NOT NULL DEFAULT '',
  PRIMARY KEY (`offerid`),
  KEY `offers_ibfk_1` (`supplierid`),
  KEY `offers_ibfk_2` (`stockid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `orderdeliverydifferenceslog`
--

CREATE TABLE IF NOT EXISTS `orderdeliverydifferenceslog` (
  `orderno` int(11) NOT NULL DEFAULT '0',
  `invoiceno` int(11) NOT NULL DEFAULT '0',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `quantitydiff` double NOT NULL DEFAULT '0',
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `branch` varchar(10) NOT NULL DEFAULT '',
  `can_or_bo` char(3) NOT NULL DEFAULT 'CAN',
  KEY `StockID` (`stockid`),
  KEY `DebtorNo` (`debtorno`,`branch`),
  KEY `Can_or_BO` (`can_or_bo`),
  KEY `OrderNo` (`orderno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `paymentmethods`
--

CREATE TABLE IF NOT EXISTS `paymentmethods` (
  `paymentid` tinyint(4) NOT NULL AUTO_INCREMENT,
  `paymentname` varchar(15) NOT NULL DEFAULT '',
  `paymenttype` int(11) NOT NULL DEFAULT '1',
  `receipttype` int(11) NOT NULL DEFAULT '1',
  `usepreprintedstationery` tinyint(4) NOT NULL DEFAULT '0',
  `opencashdrawer` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`paymentid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `paymentmethods`
--

INSERT INTO `paymentmethods` (`paymentid`, `paymentname`, `paymenttype`, `receipttype`, `usepreprintedstationery`, `opencashdrawer`) VALUES
(1, 'Cheque', 1, 1, 1, 0),
(2, 'Cash', 1, 1, 0, 0),
(3, 'Direct Credit', 1, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `paymentterms`
--

CREATE TABLE IF NOT EXISTS `paymentterms` (
  `termsindicator` char(2) NOT NULL DEFAULT '',
  `terms` char(40) NOT NULL DEFAULT '',
  `daysbeforedue` smallint(6) NOT NULL DEFAULT '0',
  `dayinfollowingmonth` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`termsindicator`),
  KEY `DaysBeforeDue` (`daysbeforedue`),
  KEY `DayInFollowingMonth` (`dayinfollowingmonth`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `paymentterms`
--

INSERT INTO `paymentterms` (`termsindicator`, `terms`, `daysbeforedue`, `dayinfollowingmonth`) VALUES
('20', 'Due 20th Of the Following Month', 0, 22),
('30', 'Due By End Of The Following Month', 0, 30),
('7', 'Payment due within 7 days', 7, 0),
('CA', 'Cash Only', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `pcashdetails`
--

CREATE TABLE IF NOT EXISTS `pcashdetails` (
  `counterindex` int(20) NOT NULL AUTO_INCREMENT,
  `tabcode` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `codeexpense` varchar(20) NOT NULL,
  `amount` double NOT NULL,
  `authorized` date NOT NULL COMMENT 'date cash assigment was revised and authorized by authorizer from tabs table',
  `posted` tinyint(4) NOT NULL COMMENT 'has (or has not) been posted into gltrans',
  `notes` text NOT NULL,
  `receipt` text COMMENT 'filename or path to scanned receipt or code of receipt to find physical receipt if tax guys or auditors show up',
  PRIMARY KEY (`counterindex`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pcexpenses`
--

CREATE TABLE IF NOT EXISTS `pcexpenses` (
  `codeexpense` varchar(20) NOT NULL COMMENT 'code for the group',
  `description` varchar(50) NOT NULL COMMENT 'text description, e.g. meals, train tickets, fuel, etc',
  `glaccount` varchar(20) NOT NULL DEFAULT '0',
  `tag` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codeexpense`),
  KEY `glaccount` (`glaccount`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pctabexpenses`
--

CREATE TABLE IF NOT EXISTS `pctabexpenses` (
  `typetabcode` varchar(20) NOT NULL,
  `codeexpense` varchar(20) NOT NULL,
  KEY `typetabcode` (`typetabcode`),
  KEY `codeexpense` (`codeexpense`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pctabs`
--

CREATE TABLE IF NOT EXISTS `pctabs` (
  `tabcode` varchar(20) NOT NULL,
  `usercode` varchar(20) NOT NULL COMMENT 'code of user employee from www_users',
  `typetabcode` varchar(20) NOT NULL,
  `currency` char(3) NOT NULL,
  `tablimit` double NOT NULL,
  `assigner` varchar(20) NOT NULL COMMENT 'Cash assigner for the tab',
  `authorizer` varchar(20) NOT NULL COMMENT 'code of user from www_users',
  `glaccountassignment` varchar(20) NOT NULL DEFAULT '0',
  `glaccountpcash` varchar(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tabcode`),
  KEY `usercode` (`usercode`),
  KEY `typetabcode` (`typetabcode`),
  KEY `currency` (`currency`),
  KEY `authorizer` (`authorizer`),
  KEY `glaccountassignment` (`glaccountassignment`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pctypetabs`
--

CREATE TABLE IF NOT EXISTS `pctypetabs` (
  `typetabcode` varchar(20) NOT NULL COMMENT 'code for the type of petty cash tab',
  `typetabdescription` varchar(50) NOT NULL COMMENT 'text description, e.g. tab for CEO',
  PRIMARY KEY (`typetabcode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `periods`
--

CREATE TABLE IF NOT EXISTS `periods` (
  `periodno` smallint(6) NOT NULL DEFAULT '0',
  `lastdate_in_period` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`periodno`),
  KEY `LastDate_in_Period` (`lastdate_in_period`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pickinglistdetails`
--

CREATE TABLE IF NOT EXISTS `pickinglistdetails` (
  `pickinglistno` int(11) NOT NULL DEFAULT '0',
  `pickinglistlineno` int(11) NOT NULL DEFAULT '0',
  `orderlineno` int(11) NOT NULL DEFAULT '0',
  `qtyexpected` double NOT NULL DEFAULT '0',
  `qtypicked` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`pickinglistno`,`pickinglistlineno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pickinglists`
--

CREATE TABLE IF NOT EXISTS `pickinglists` (
  `pickinglistno` int(11) NOT NULL DEFAULT '0',
  `orderno` int(11) NOT NULL DEFAULT '0',
  `pickinglistdate` date NOT NULL DEFAULT '0000-00-00',
  `dateprinted` date NOT NULL DEFAULT '0000-00-00',
  `deliverynotedate` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`pickinglistno`),
  KEY `pickinglists_ibfk_1` (`orderno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pricematrix`
--

CREATE TABLE IF NOT EXISTS `pricematrix` (
  `salestype` char(2) NOT NULL DEFAULT '',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `quantitybreak` int(11) NOT NULL DEFAULT '1',
  `price` double NOT NULL DEFAULT '0',
  `currabrev` char(3) NOT NULL DEFAULT '',
  `startdate` date NOT NULL DEFAULT '0000-00-00',
  `enddate` date NOT NULL DEFAULT '9999-12-31',
  PRIMARY KEY (`salestype`,`stockid`,`currabrev`,`quantitybreak`,`startdate`,`enddate`),
  KEY `SalesType` (`salestype`),
  KEY `currabrev` (`currabrev`),
  KEY `stockid` (`stockid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `prices`
--

CREATE TABLE IF NOT EXISTS `prices` (
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `typeabbrev` char(2) NOT NULL DEFAULT '',
  `currabrev` char(3) NOT NULL DEFAULT '',
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `price` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `branchcode` varchar(10) NOT NULL DEFAULT '',
  `startdate` date NOT NULL DEFAULT '0000-00-00',
  `enddate` date NOT NULL DEFAULT '9999-12-31',
  PRIMARY KEY (`stockid`,`typeabbrev`,`currabrev`,`debtorno`,`branchcode`,`startdate`,`enddate`),
  KEY `CurrAbrev` (`currabrev`),
  KEY `DebtorNo` (`debtorno`),
  KEY `StockID` (`stockid`),
  KEY `TypeAbbrev` (`typeabbrev`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `prodspecs`
--

CREATE TABLE IF NOT EXISTS `prodspecs` (
  `keyval` varchar(25) NOT NULL,
  `testid` int(11) NOT NULL,
  `defaultvalue` varchar(150) NOT NULL DEFAULT '',
  `targetvalue` varchar(30) NOT NULL DEFAULT '',
  `rangemin` float DEFAULT NULL,
  `rangemax` float DEFAULT NULL,
  `showoncert` tinyint(11) NOT NULL DEFAULT '1',
  `showonspec` tinyint(4) NOT NULL DEFAULT '1',
  `showontestplan` tinyint(4) NOT NULL DEFAULT '1',
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`keyval`,`testid`),
  KEY `testid` (`testid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `purchdata`
--

CREATE TABLE IF NOT EXISTS `purchdata` (
  `supplierno` char(10) NOT NULL DEFAULT '',
  `stockid` char(20) NOT NULL DEFAULT '',
  `price` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `suppliersuom` char(50) NOT NULL DEFAULT '',
  `conversionfactor` double NOT NULL DEFAULT '1',
  `supplierdescription` char(50) NOT NULL DEFAULT '',
  `leadtime` smallint(6) NOT NULL DEFAULT '1',
  `preferred` tinyint(4) NOT NULL DEFAULT '0',
  `effectivefrom` date NOT NULL,
  `suppliers_partno` varchar(50) NOT NULL DEFAULT '',
  `minorderqty` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`supplierno`,`stockid`,`effectivefrom`),
  KEY `StockID` (`stockid`),
  KEY `SupplierNo` (`supplierno`),
  KEY `Preferred` (`preferred`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `purchorderauth`
--

CREATE TABLE IF NOT EXISTS `purchorderauth` (
  `userid` varchar(20) NOT NULL DEFAULT '',
  `currabrev` char(3) NOT NULL DEFAULT '',
  `cancreate` smallint(2) NOT NULL DEFAULT '0',
  `authlevel` double NOT NULL DEFAULT '0',
  `offhold` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userid`,`currabrev`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `purchorderdetails`
--

CREATE TABLE IF NOT EXISTS `purchorderdetails` (
  `podetailitem` int(11) NOT NULL AUTO_INCREMENT,
  `orderno` int(11) NOT NULL DEFAULT '0',
  `itemcode` varchar(20) NOT NULL DEFAULT '',
  `deliverydate` date NOT NULL DEFAULT '0000-00-00',
  `itemdescription` varchar(100) NOT NULL,
  `glcode` varchar(20) NOT NULL DEFAULT '0',
  `qtyinvoiced` double NOT NULL DEFAULT '0',
  `unitprice` double NOT NULL DEFAULT '0',
  `actprice` double NOT NULL DEFAULT '0',
  `stdcostunit` double NOT NULL DEFAULT '0',
  `quantityord` double NOT NULL DEFAULT '0',
  `quantityrecd` double NOT NULL DEFAULT '0',
  `shiptref` int(11) NOT NULL DEFAULT '0',
  `jobref` varchar(20) NOT NULL DEFAULT '',
  `completed` tinyint(4) NOT NULL DEFAULT '0',
  `suppliersunit` varchar(50) DEFAULT NULL,
  `suppliers_partno` varchar(50) NOT NULL DEFAULT '',
  `assetid` int(11) NOT NULL DEFAULT '0',
  `conversionfactor` double NOT NULL DEFAULT '1',
  PRIMARY KEY (`podetailitem`),
  KEY `DeliveryDate` (`deliverydate`),
  KEY `GLCode` (`glcode`),
  KEY `ItemCode` (`itemcode`),
  KEY `JobRef` (`jobref`),
  KEY `OrderNo` (`orderno`),
  KEY `ShiptRef` (`shiptref`),
  KEY `Completed` (`completed`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `purchorders`
--

CREATE TABLE IF NOT EXISTS `purchorders` (
  `orderno` int(11) NOT NULL AUTO_INCREMENT,
  `supplierno` varchar(10) NOT NULL DEFAULT '',
  `comments` longblob,
  `orddate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `rate` double NOT NULL DEFAULT '1',
  `dateprinted` datetime DEFAULT NULL,
  `allowprint` tinyint(4) NOT NULL DEFAULT '1',
  `initiator` varchar(20) DEFAULT NULL,
  `requisitionno` varchar(15) DEFAULT NULL,
  `intostocklocation` varchar(5) NOT NULL DEFAULT '',
  `deladd1` varchar(40) NOT NULL DEFAULT '',
  `deladd2` varchar(40) NOT NULL DEFAULT '',
  `deladd3` varchar(40) NOT NULL DEFAULT '',
  `deladd4` varchar(40) NOT NULL DEFAULT '',
  `deladd5` varchar(20) NOT NULL DEFAULT '',
  `deladd6` varchar(15) NOT NULL DEFAULT '',
  `tel` varchar(30) NOT NULL DEFAULT '',
  `suppdeladdress1` varchar(40) NOT NULL DEFAULT '',
  `suppdeladdress2` varchar(40) NOT NULL DEFAULT '',
  `suppdeladdress3` varchar(40) NOT NULL DEFAULT '',
  `suppdeladdress4` varchar(40) NOT NULL DEFAULT '',
  `suppdeladdress5` varchar(20) NOT NULL DEFAULT '',
  `suppdeladdress6` varchar(15) NOT NULL DEFAULT '',
  `suppliercontact` varchar(30) NOT NULL DEFAULT '',
  `supptel` varchar(30) NOT NULL DEFAULT '',
  `contact` varchar(30) NOT NULL DEFAULT '',
  `version` decimal(3,2) NOT NULL DEFAULT '1.00',
  `revised` date NOT NULL DEFAULT '0000-00-00',
  `realorderno` varchar(16) NOT NULL DEFAULT '',
  `deliveryby` varchar(100) NOT NULL DEFAULT '',
  `deliverydate` date NOT NULL DEFAULT '0000-00-00',
  `status` varchar(12) NOT NULL DEFAULT '',
  `stat_comment` text NOT NULL,
  `paymentterms` char(2) NOT NULL DEFAULT '',
  `port` varchar(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`orderno`),
  KEY `OrdDate` (`orddate`),
  KEY `SupplierNo` (`supplierno`),
  KEY `IntoStockLocation` (`intostocklocation`),
  KEY `AllowPrintPO` (`allowprint`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

-- --------------------------------------------------------

--
-- Table structure for table `qasamples`
--

CREATE TABLE IF NOT EXISTS `qasamples` (
  `sampleid` int(11) NOT NULL AUTO_INCREMENT,
  `prodspeckey` varchar(25) NOT NULL DEFAULT '',
  `lotkey` varchar(25) NOT NULL DEFAULT '',
  `identifier` varchar(10) NOT NULL DEFAULT '',
  `createdby` varchar(15) NOT NULL DEFAULT '',
  `sampledate` date NOT NULL DEFAULT '0000-00-00',
  `comments` varchar(255) NOT NULL DEFAULT '',
  `cert` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sampleid`),
  KEY `prodspeckey` (`prodspeckey`,`lotkey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `qatests`
--

CREATE TABLE IF NOT EXISTS `qatests` (
  `testid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `method` varchar(20) DEFAULT NULL,
  `groupby` varchar(20) DEFAULT NULL,
  `units` varchar(20) NOT NULL,
  `type` varchar(15) NOT NULL,
  `defaultvalue` varchar(150) NOT NULL DEFAULT '''''',
  `numericvalue` tinyint(4) NOT NULL DEFAULT '0',
  `showoncert` int(11) NOT NULL DEFAULT '1',
  `showonspec` int(11) NOT NULL DEFAULT '1',
  `showontestplan` tinyint(4) NOT NULL DEFAULT '1',
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`testid`),
  KEY `name` (`name`),
  KEY `groupname` (`groupby`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `recurringsalesorders`
--

CREATE TABLE IF NOT EXISTS `recurringsalesorders` (
  `recurrorderno` int(11) NOT NULL AUTO_INCREMENT,
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `branchcode` varchar(10) NOT NULL DEFAULT '',
  `customerref` varchar(50) NOT NULL DEFAULT '',
  `buyername` varchar(50) DEFAULT NULL,
  `comments` longblob,
  `orddate` date NOT NULL DEFAULT '0000-00-00',
  `ordertype` char(2) NOT NULL DEFAULT '',
  `shipvia` int(11) NOT NULL DEFAULT '0',
  `deladd1` varchar(40) NOT NULL DEFAULT '',
  `deladd2` varchar(40) NOT NULL DEFAULT '',
  `deladd3` varchar(40) NOT NULL DEFAULT '',
  `deladd4` varchar(40) DEFAULT NULL,
  `deladd5` varchar(20) NOT NULL DEFAULT '',
  `deladd6` varchar(15) NOT NULL DEFAULT '',
  `contactphone` varchar(25) DEFAULT NULL,
  `contactemail` varchar(25) DEFAULT NULL,
  `deliverto` varchar(40) NOT NULL DEFAULT '',
  `freightcost` double NOT NULL DEFAULT '0',
  `fromstkloc` varchar(5) NOT NULL DEFAULT '',
  `lastrecurrence` date NOT NULL DEFAULT '0000-00-00',
  `stopdate` date NOT NULL DEFAULT '0000-00-00',
  `frequency` tinyint(4) NOT NULL DEFAULT '1',
  `autoinvoice` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`recurrorderno`),
  KEY `debtorno` (`debtorno`),
  KEY `orddate` (`orddate`),
  KEY `ordertype` (`ordertype`),
  KEY `locationindex` (`fromstkloc`),
  KEY `branchcode` (`branchcode`,`debtorno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `recurrsalesorderdetails`
--

CREATE TABLE IF NOT EXISTS `recurrsalesorderdetails` (
  `recurrorderno` int(11) NOT NULL DEFAULT '0',
  `stkcode` varchar(20) NOT NULL DEFAULT '',
  `unitprice` double NOT NULL DEFAULT '0',
  `quantity` double NOT NULL DEFAULT '0',
  `discountpercent` double NOT NULL DEFAULT '0',
  `narrative` text NOT NULL,
  KEY `orderno` (`recurrorderno`),
  KEY `stkcode` (`stkcode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `relateditems`
--

CREATE TABLE IF NOT EXISTS `relateditems` (
  `stockid` varchar(20) CHARACTER SET utf8 NOT NULL,
  `related` varchar(20) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`stockid`,`related`),
  UNIQUE KEY `Related` (`related`,`stockid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reportcolumns`
--

CREATE TABLE IF NOT EXISTS `reportcolumns` (
  `reportid` smallint(6) NOT NULL DEFAULT '0',
  `colno` smallint(6) NOT NULL DEFAULT '0',
  `heading1` varchar(15) NOT NULL DEFAULT '',
  `heading2` varchar(15) DEFAULT NULL,
  `calculation` tinyint(1) NOT NULL DEFAULT '0',
  `periodfrom` smallint(6) DEFAULT NULL,
  `periodto` smallint(6) DEFAULT NULL,
  `datatype` varchar(15) DEFAULT NULL,
  `colnumerator` tinyint(4) DEFAULT NULL,
  `coldenominator` tinyint(4) DEFAULT NULL,
  `calcoperator` char(1) DEFAULT NULL,
  `budgetoractual` tinyint(1) NOT NULL DEFAULT '0',
  `valformat` char(1) NOT NULL DEFAULT 'N',
  `constant` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`reportid`,`colno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reportfields`
--

CREATE TABLE IF NOT EXISTS `reportfields` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `reportid` int(5) NOT NULL DEFAULT '0',
  `entrytype` varchar(15) NOT NULL DEFAULT '',
  `seqnum` int(3) NOT NULL DEFAULT '0',
  `fieldname` varchar(80) NOT NULL DEFAULT '',
  `displaydesc` varchar(25) NOT NULL DEFAULT '',
  `visible` enum('1','0') NOT NULL DEFAULT '1',
  `columnbreak` enum('1','0') NOT NULL DEFAULT '1',
  `params` text,
  PRIMARY KEY (`id`),
  KEY `reportid` (`reportid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1805 ;

-- --------------------------------------------------------

--
-- Table structure for table `reportheaders`
--

CREATE TABLE IF NOT EXISTS `reportheaders` (
  `reportid` smallint(6) NOT NULL AUTO_INCREMENT,
  `reportheading` varchar(80) NOT NULL DEFAULT '',
  `groupbydata1` varchar(15) NOT NULL DEFAULT '',
  `newpageafter1` tinyint(1) NOT NULL DEFAULT '0',
  `lower1` varchar(10) NOT NULL DEFAULT '',
  `upper1` varchar(10) NOT NULL DEFAULT '',
  `groupbydata2` varchar(15) DEFAULT NULL,
  `newpageafter2` tinyint(1) NOT NULL DEFAULT '0',
  `lower2` varchar(10) DEFAULT NULL,
  `upper2` varchar(10) DEFAULT NULL,
  `groupbydata3` varchar(15) DEFAULT NULL,
  `newpageafter3` tinyint(1) NOT NULL DEFAULT '0',
  `lower3` varchar(10) DEFAULT NULL,
  `upper3` varchar(10) DEFAULT NULL,
  `groupbydata4` varchar(15) NOT NULL DEFAULT '',
  `newpageafter4` tinyint(1) NOT NULL DEFAULT '0',
  `upper4` varchar(10) NOT NULL DEFAULT '',
  `lower4` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`reportid`),
  KEY `ReportHeading` (`reportheading`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `reportlinks`
--

CREATE TABLE IF NOT EXISTS `reportlinks` (
  `table1` varchar(25) NOT NULL DEFAULT '',
  `table2` varchar(25) NOT NULL DEFAULT '',
  `equation` varchar(75) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `reportlinks`
--

INSERT INTO `reportlinks` (`table1`, `table2`, `equation`) VALUES
('accountgroups', 'accountsection', 'accountgroups.sectioninaccounts=accountsection.sectionid'),
('accountsection', 'accountgroups', 'accountsection.sectionid=accountgroups.sectioninaccounts'),
('bankaccounts', 'chartmaster', 'bankaccounts.accountcode=chartmaster.accountcode'),
('chartmaster', 'bankaccounts', 'chartmaster.accountcode=bankaccounts.accountcode'),
('banktrans', 'systypes', 'banktrans.type=systypes.typeid'),
('systypes', 'banktrans', 'systypes.typeid=banktrans.type'),
('banktrans', 'bankaccounts', 'banktrans.bankact=bankaccounts.accountcode'),
('bankaccounts', 'banktrans', 'bankaccounts.accountcode=banktrans.bankact'),
('bom', 'stockmaster', 'bom.parent=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.parent'),
('bom', 'stockmaster', 'bom.component=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.component'),
('bom', 'workcentres', 'bom.workcentreadded=workcentres.code'),
('workcentres', 'bom', 'workcentres.code=bom.workcentreadded'),
('bom', 'locations', 'bom.loccode=locations.loccode'),
('locations', 'bom', 'locations.loccode=bom.loccode'),
('buckets', 'workcentres', 'buckets.workcentre=workcentres.code'),
('workcentres', 'buckets', 'workcentres.code=buckets.workcentre'),
('chartdetails', 'chartmaster', 'chartdetails.accountcode=chartmaster.accountcode'),
('chartmaster', 'chartdetails', 'chartmaster.accountcode=chartdetails.accountcode'),
('chartdetails', 'periods', 'chartdetails.period=periods.periodno'),
('periods', 'chartdetails', 'periods.periodno=chartdetails.period'),
('chartmaster', 'accountgroups', 'chartmaster.group_=accountgroups.groupname'),
('accountgroups', 'chartmaster', 'accountgroups.groupname=chartmaster.group_'),
('contractbom', 'workcentres', 'contractbom.workcentreadded=workcentres.code'),
('workcentres', 'contractbom', 'workcentres.code=contractbom.workcentreadded'),
('contractbom', 'locations', 'contractbom.loccode=locations.loccode'),
('locations', 'contractbom', 'locations.loccode=contractbom.loccode'),
('contractbom', 'stockmaster', 'contractbom.component=stockmaster.stockid'),
('stockmaster', 'contractbom', 'stockmaster.stockid=contractbom.component'),
('contractreqts', 'contracts', 'contractreqts.contract=contracts.contractref'),
('contracts', 'contractreqts', 'contracts.contractref=contractreqts.contract'),
('contracts', 'custbranch', 'contracts.debtorno=custbranch.debtorno'),
('custbranch', 'contracts', 'custbranch.debtorno=contracts.debtorno'),
('contracts', 'stockcategory', 'contracts.branchcode=stockcategory.categoryid'),
('stockcategory', 'contracts', 'stockcategory.categoryid=contracts.branchcode'),
('contracts', 'salestypes', 'contracts.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'contracts', 'salestypes.typeabbrev=contracts.typeabbrev'),
('custallocns', 'debtortrans', 'custallocns.transid_allocfrom=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocfrom'),
('custallocns', 'debtortrans', 'custallocns.transid_allocto=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocto'),
('custbranch', 'debtorsmaster', 'custbranch.debtorno=debtorsmaster.debtorno'),
('debtorsmaster', 'custbranch', 'debtorsmaster.debtorno=custbranch.debtorno'),
('custbranch', 'areas', 'custbranch.area=areas.areacode'),
('areas', 'custbranch', 'areas.areacode=custbranch.area'),
('custbranch', 'salesman', 'custbranch.salesman=salesman.salesmancode'),
('salesman', 'custbranch', 'salesman.salesmancode=custbranch.salesman'),
('custbranch', 'locations', 'custbranch.defaultlocation=locations.loccode'),
('locations', 'custbranch', 'locations.loccode=custbranch.defaultlocation'),
('custbranch', 'shippers', 'custbranch.defaultshipvia=shippers.shipper_id'),
('shippers', 'custbranch', 'shippers.shipper_id=custbranch.defaultshipvia'),
('debtorsmaster', 'holdreasons', 'debtorsmaster.holdreason=holdreasons.reasoncode'),
('holdreasons', 'debtorsmaster', 'holdreasons.reasoncode=debtorsmaster.holdreason'),
('debtorsmaster', 'currencies', 'debtorsmaster.currcode=currencies.currabrev'),
('currencies', 'debtorsmaster', 'currencies.currabrev=debtorsmaster.currcode'),
('debtorsmaster', 'paymentterms', 'debtorsmaster.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'debtorsmaster', 'paymentterms.termsindicator=debtorsmaster.paymentterms'),
('debtorsmaster', 'salestypes', 'debtorsmaster.salestype=salestypes.typeabbrev'),
('salestypes', 'debtorsmaster', 'salestypes.typeabbrev=debtorsmaster.salestype'),
('debtortrans', 'custbranch', 'debtortrans.debtorno=custbranch.debtorno'),
('custbranch', 'debtortrans', 'custbranch.debtorno=debtortrans.debtorno'),
('debtortrans', 'systypes', 'debtortrans.type=systypes.typeid'),
('systypes', 'debtortrans', 'systypes.typeid=debtortrans.type'),
('debtortrans', 'periods', 'debtortrans.prd=periods.periodno'),
('periods', 'debtortrans', 'periods.periodno=debtortrans.prd'),
('debtortranstaxes', 'taxauthorities', 'debtortranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'debtortranstaxes', 'taxauthorities.taxid=debtortranstaxes.taxauthid'),
('debtortranstaxes', 'debtortrans', 'debtortranstaxes.debtortransid=debtortrans.id'),
('debtortrans', 'debtortranstaxes', 'debtortrans.id=debtortranstaxes.debtortransid'),
('discountmatrix', 'salestypes', 'discountmatrix.salestype=salestypes.typeabbrev'),
('salestypes', 'discountmatrix', 'salestypes.typeabbrev=discountmatrix.salestype'),
('freightcosts', 'locations', 'freightcosts.locationfrom=locations.loccode'),
('locations', 'freightcosts', 'locations.loccode=freightcosts.locationfrom'),
('freightcosts', 'shippers', 'freightcosts.shipperid=shippers.shipper_id'),
('shippers', 'freightcosts', 'shippers.shipper_id=freightcosts.shipperid'),
('gltrans', 'chartmaster', 'gltrans.account=chartmaster.accountcode'),
('chartmaster', 'gltrans', 'chartmaster.accountcode=gltrans.account'),
('gltrans', 'systypes', 'gltrans.type=systypes.typeid'),
('systypes', 'gltrans', 'systypes.typeid=gltrans.type'),
('gltrans', 'periods', 'gltrans.periodno=periods.periodno'),
('periods', 'gltrans', 'periods.periodno=gltrans.periodno'),
('grns', 'suppliers', 'grns.supplierid=suppliers.supplierid'),
('suppliers', 'grns', 'suppliers.supplierid=grns.supplierid'),
('grns', 'purchorderdetails', 'grns.podetailitem=purchorderdetails.podetailitem'),
('purchorderdetails', 'grns', 'purchorderdetails.podetailitem=grns.podetailitem'),
('locations', 'taxprovinces', 'locations.taxprovinceid=taxprovinces.taxprovinceid'),
('taxprovinces', 'locations', 'taxprovinces.taxprovinceid=locations.taxprovinceid'),
('locstock', 'locations', 'locstock.loccode=locations.loccode'),
('locations', 'locstock', 'locations.loccode=locstock.loccode'),
('locstock', 'stockmaster', 'locstock.stockid=stockmaster.stockid'),
('stockmaster', 'locstock', 'stockmaster.stockid=locstock.stockid'),
('loctransfers', 'locations', 'loctransfers.shiploc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.shiploc'),
('loctransfers', 'locations', 'loctransfers.recloc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.recloc'),
('loctransfers', 'stockmaster', 'loctransfers.stockid=stockmaster.stockid'),
('stockmaster', 'loctransfers', 'stockmaster.stockid=loctransfers.stockid'),
('orderdeliverydifferencesl', 'stockmaster', 'orderdeliverydifferenceslog.stockid=stockmaster.stockid'),
('stockmaster', 'orderdeliverydifferencesl', 'stockmaster.stockid=orderdeliverydifferenceslog.stockid'),
('orderdeliverydifferencesl', 'custbranch', 'orderdeliverydifferenceslog.debtorno=custbranch.debtorno'),
('custbranch', 'orderdeliverydifferencesl', 'custbranch.debtorno=orderdeliverydifferenceslog.debtorno'),
('orderdeliverydifferencesl', 'salesorders', 'orderdeliverydifferenceslog.branchcode=salesorders.orderno'),
('salesorders', 'orderdeliverydifferencesl', 'salesorders.orderno=orderdeliverydifferenceslog.branchcode'),
('prices', 'stockmaster', 'prices.stockid=stockmaster.stockid'),
('stockmaster', 'prices', 'stockmaster.stockid=prices.stockid'),
('prices', 'currencies', 'prices.currabrev=currencies.currabrev'),
('currencies', 'prices', 'currencies.currabrev=prices.currabrev'),
('prices', 'salestypes', 'prices.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'prices', 'salestypes.typeabbrev=prices.typeabbrev'),
('purchdata', 'stockmaster', 'purchdata.stockid=stockmaster.stockid'),
('stockmaster', 'purchdata', 'stockmaster.stockid=purchdata.stockid'),
('purchdata', 'suppliers', 'purchdata.supplierno=suppliers.supplierid'),
('suppliers', 'purchdata', 'suppliers.supplierid=purchdata.supplierno'),
('purchorderdetails', 'purchorders', 'purchorderdetails.orderno=purchorders.orderno'),
('purchorders', 'purchorderdetails', 'purchorders.orderno=purchorderdetails.orderno'),
('purchorders', 'suppliers', 'purchorders.supplierno=suppliers.supplierid'),
('suppliers', 'purchorders', 'suppliers.supplierid=purchorders.supplierno'),
('purchorders', 'locations', 'purchorders.intostocklocation=locations.loccode'),
('locations', 'purchorders', 'locations.loccode=purchorders.intostocklocation'),
('recurringsalesorders', 'custbranch', 'recurringsalesorders.branchcode=custbranch.branchcode'),
('custbranch', 'recurringsalesorders', 'custbranch.branchcode=recurringsalesorders.branchcode'),
('recurrsalesorderdetails', 'recurringsalesorders', 'recurrsalesorderdetails.recurrorderno=recurringsalesorders.recurrorderno'),
('recurringsalesorders', 'recurrsalesorderdetails', 'recurringsalesorders.recurrorderno=recurrsalesorderdetails.recurrorderno'),
('recurrsalesorderdetails', 'stockmaster', 'recurrsalesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'recurrsalesorderdetails', 'stockmaster.stockid=recurrsalesorderdetails.stkcode'),
('reportcolumns', 'reportheaders', 'reportcolumns.reportid=reportheaders.reportid'),
('reportheaders', 'reportcolumns', 'reportheaders.reportid=reportcolumns.reportid'),
('salesanalysis', 'periods', 'salesanalysis.periodno=periods.periodno'),
('periods', 'salesanalysis', 'periods.periodno=salesanalysis.periodno'),
('salescatprod', 'stockmaster', 'salescatprod.stockid=stockmaster.stockid'),
('stockmaster', 'salescatprod', 'stockmaster.stockid=salescatprod.stockid'),
('salescatprod', 'salescat', 'salescatprod.salescatid=salescat.salescatid'),
('salescat', 'salescatprod', 'salescat.salescatid=salescatprod.salescatid'),
('salesorderdetails', 'salesorders', 'salesorderdetails.orderno=salesorders.orderno'),
('salesorders', 'salesorderdetails', 'salesorders.orderno=salesorderdetails.orderno'),
('salesorderdetails', 'stockmaster', 'salesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'salesorderdetails', 'stockmaster.stockid=salesorderdetails.stkcode'),
('salesorders', 'custbranch', 'salesorders.branchcode=custbranch.branchcode'),
('custbranch', 'salesorders', 'custbranch.branchcode=salesorders.branchcode'),
('salesorders', 'shippers', 'salesorders.debtorno=shippers.shipper_id'),
('shippers', 'salesorders', 'shippers.shipper_id=salesorders.debtorno'),
('salesorders', 'locations', 'salesorders.fromstkloc=locations.loccode'),
('locations', 'salesorders', 'locations.loccode=salesorders.fromstkloc'),
('securitygroups', 'securityroles', 'securitygroups.secroleid=securityroles.secroleid'),
('securityroles', 'securitygroups', 'securityroles.secroleid=securitygroups.secroleid'),
('securitygroups', 'securitytokens', 'securitygroups.tokenid=securitytokens.tokenid'),
('securitytokens', 'securitygroups', 'securitytokens.tokenid=securitygroups.tokenid'),
('shipmentcharges', 'shipments', 'shipmentcharges.shiptref=shipments.shiptref'),
('shipments', 'shipmentcharges', 'shipments.shiptref=shipmentcharges.shiptref'),
('shipmentcharges', 'systypes', 'shipmentcharges.transtype=systypes.typeid'),
('systypes', 'shipmentcharges', 'systypes.typeid=shipmentcharges.transtype'),
('shipments', 'suppliers', 'shipments.supplierid=suppliers.supplierid'),
('suppliers', 'shipments', 'suppliers.supplierid=shipments.supplierid'),
('stockcheckfreeze', 'stockmaster', 'stockcheckfreeze.stockid=stockmaster.stockid'),
('stockmaster', 'stockcheckfreeze', 'stockmaster.stockid=stockcheckfreeze.stockid'),
('stockcheckfreeze', 'locations', 'stockcheckfreeze.loccode=locations.loccode'),
('locations', 'stockcheckfreeze', 'locations.loccode=stockcheckfreeze.loccode'),
('stockcounts', 'stockmaster', 'stockcounts.stockid=stockmaster.stockid'),
('stockmaster', 'stockcounts', 'stockmaster.stockid=stockcounts.stockid'),
('stockcounts', 'locations', 'stockcounts.loccode=locations.loccode'),
('locations', 'stockcounts', 'locations.loccode=stockcounts.loccode'),
('stockmaster', 'stockcategory', 'stockmaster.categoryid=stockcategory.categoryid'),
('stockcategory', 'stockmaster', 'stockcategory.categoryid=stockmaster.categoryid'),
('stockmaster', 'taxcategories', 'stockmaster.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'stockmaster', 'taxcategories.taxcatid=stockmaster.taxcatid'),
('stockmoves', 'stockmaster', 'stockmoves.stockid=stockmaster.stockid'),
('stockmaster', 'stockmoves', 'stockmaster.stockid=stockmoves.stockid'),
('stockmoves', 'systypes', 'stockmoves.type=systypes.typeid'),
('systypes', 'stockmoves', 'systypes.typeid=stockmoves.type'),
('stockmoves', 'locations', 'stockmoves.loccode=locations.loccode'),
('locations', 'stockmoves', 'locations.loccode=stockmoves.loccode'),
('stockmoves', 'periods', 'stockmoves.prd=periods.periodno'),
('periods', 'stockmoves', 'periods.periodno=stockmoves.prd'),
('stockmovestaxes', 'taxauthorities', 'stockmovestaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'stockmovestaxes', 'taxauthorities.taxid=stockmovestaxes.taxauthid'),
('stockserialitems', 'stockmaster', 'stockserialitems.stockid=stockmaster.stockid'),
('stockmaster', 'stockserialitems', 'stockmaster.stockid=stockserialitems.stockid'),
('stockserialitems', 'locations', 'stockserialitems.loccode=locations.loccode'),
('locations', 'stockserialitems', 'locations.loccode=stockserialitems.loccode'),
('stockserialmoves', 'stockmoves', 'stockserialmoves.stockmoveno=stockmoves.stkmoveno'),
('stockmoves', 'stockserialmoves', 'stockmoves.stkmoveno=stockserialmoves.stockmoveno'),
('stockserialmoves', 'stockserialitems', 'stockserialmoves.stockid=stockserialitems.stockid'),
('stockserialitems', 'stockserialmoves', 'stockserialitems.stockid=stockserialmoves.stockid'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocfrom=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocfrom'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocto=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocto'),
('suppliercontacts', 'suppliers', 'suppliercontacts.supplierid=suppliers.supplierid'),
('suppliers', 'suppliercontacts', 'suppliers.supplierid=suppliercontacts.supplierid'),
('suppliers', 'currencies', 'suppliers.currcode=currencies.currabrev'),
('currencies', 'suppliers', 'currencies.currabrev=suppliers.currcode'),
('suppliers', 'paymentterms', 'suppliers.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'suppliers', 'paymentterms.termsindicator=suppliers.paymentterms'),
('suppliers', 'taxgroups', 'suppliers.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'suppliers', 'taxgroups.taxgroupid=suppliers.taxgroupid'),
('supptrans', 'systypes', 'supptrans.type=systypes.typeid'),
('systypes', 'supptrans', 'systypes.typeid=supptrans.type'),
('supptrans', 'suppliers', 'supptrans.supplierno=suppliers.supplierid'),
('suppliers', 'supptrans', 'suppliers.supplierid=supptrans.supplierno'),
('supptranstaxes', 'taxauthorities', 'supptranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'supptranstaxes', 'taxauthorities.taxid=supptranstaxes.taxauthid'),
('supptranstaxes', 'supptrans', 'supptranstaxes.supptransid=supptrans.id'),
('supptrans', 'supptranstaxes', 'supptrans.id=supptranstaxes.supptransid'),
('taxauthorities', 'chartmaster', 'taxauthorities.taxglcode=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.taxglcode'),
('taxauthorities', 'chartmaster', 'taxauthorities.purchtaxglaccount=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.purchtaxglaccount'),
('taxauthrates', 'taxauthorities', 'taxauthrates.taxauthority=taxauthorities.taxid'),
('taxauthorities', 'taxauthrates', 'taxauthorities.taxid=taxauthrates.taxauthority'),
('taxauthrates', 'taxcategories', 'taxauthrates.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'taxauthrates', 'taxcategories.taxcatid=taxauthrates.taxcatid'),
('taxauthrates', 'taxprovinces', 'taxauthrates.dispatchtaxprovince=taxprovinces.taxprovinceid'),
('taxprovinces', 'taxauthrates', 'taxprovinces.taxprovinceid=taxauthrates.dispatchtaxprovince'),
('taxgrouptaxes', 'taxgroups', 'taxgrouptaxes.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'taxgrouptaxes', 'taxgroups.taxgroupid=taxgrouptaxes.taxgroupid'),
('taxgrouptaxes', 'taxauthorities', 'taxgrouptaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'taxgrouptaxes', 'taxauthorities.taxid=taxgrouptaxes.taxauthid'),
('workcentres', 'locations', 'workcentres.location=locations.loccode'),
('locations', 'workcentres', 'locations.loccode=workcentres.location'),
('worksorders', 'locations', 'worksorders.loccode=locations.loccode'),
('locations', 'worksorders', 'locations.loccode=worksorders.loccode'),
('worksorders', 'stockmaster', 'worksorders.stockid=stockmaster.stockid'),
('stockmaster', 'worksorders', 'stockmaster.stockid=worksorders.stockid'),
('www_users', 'locations', 'www_users.defaultlocation=locations.loccode'),
('locations', 'www_users', 'locations.loccode=www_users.defaultlocation'),
('accountgroups', 'accountsection', 'accountgroups.sectioninaccounts=accountsection.sectionid'),
('accountsection', 'accountgroups', 'accountsection.sectionid=accountgroups.sectioninaccounts'),
('bankaccounts', 'chartmaster', 'bankaccounts.accountcode=chartmaster.accountcode'),
('chartmaster', 'bankaccounts', 'chartmaster.accountcode=bankaccounts.accountcode'),
('banktrans', 'systypes', 'banktrans.type=systypes.typeid'),
('systypes', 'banktrans', 'systypes.typeid=banktrans.type'),
('banktrans', 'bankaccounts', 'banktrans.bankact=bankaccounts.accountcode'),
('bankaccounts', 'banktrans', 'bankaccounts.accountcode=banktrans.bankact'),
('bom', 'stockmaster', 'bom.parent=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.parent'),
('bom', 'stockmaster', 'bom.component=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.component'),
('bom', 'workcentres', 'bom.workcentreadded=workcentres.code'),
('workcentres', 'bom', 'workcentres.code=bom.workcentreadded'),
('bom', 'locations', 'bom.loccode=locations.loccode'),
('locations', 'bom', 'locations.loccode=bom.loccode'),
('buckets', 'workcentres', 'buckets.workcentre=workcentres.code'),
('workcentres', 'buckets', 'workcentres.code=buckets.workcentre'),
('chartdetails', 'chartmaster', 'chartdetails.accountcode=chartmaster.accountcode'),
('chartmaster', 'chartdetails', 'chartmaster.accountcode=chartdetails.accountcode'),
('chartdetails', 'periods', 'chartdetails.period=periods.periodno'),
('periods', 'chartdetails', 'periods.periodno=chartdetails.period'),
('chartmaster', 'accountgroups', 'chartmaster.group_=accountgroups.groupname'),
('accountgroups', 'chartmaster', 'accountgroups.groupname=chartmaster.group_'),
('contractbom', 'workcentres', 'contractbom.workcentreadded=workcentres.code'),
('workcentres', 'contractbom', 'workcentres.code=contractbom.workcentreadded'),
('contractbom', 'locations', 'contractbom.loccode=locations.loccode'),
('locations', 'contractbom', 'locations.loccode=contractbom.loccode'),
('contractbom', 'stockmaster', 'contractbom.component=stockmaster.stockid'),
('stockmaster', 'contractbom', 'stockmaster.stockid=contractbom.component'),
('contractreqts', 'contracts', 'contractreqts.contract=contracts.contractref'),
('contracts', 'contractreqts', 'contracts.contractref=contractreqts.contract'),
('contracts', 'custbranch', 'contracts.debtorno=custbranch.debtorno'),
('custbranch', 'contracts', 'custbranch.debtorno=contracts.debtorno'),
('contracts', 'stockcategory', 'contracts.branchcode=stockcategory.categoryid'),
('stockcategory', 'contracts', 'stockcategory.categoryid=contracts.branchcode'),
('contracts', 'salestypes', 'contracts.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'contracts', 'salestypes.typeabbrev=contracts.typeabbrev'),
('custallocns', 'debtortrans', 'custallocns.transid_allocfrom=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocfrom'),
('custallocns', 'debtortrans', 'custallocns.transid_allocto=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocto'),
('custbranch', 'debtorsmaster', 'custbranch.debtorno=debtorsmaster.debtorno'),
('debtorsmaster', 'custbranch', 'debtorsmaster.debtorno=custbranch.debtorno'),
('custbranch', 'areas', 'custbranch.area=areas.areacode'),
('areas', 'custbranch', 'areas.areacode=custbranch.area'),
('custbranch', 'salesman', 'custbranch.salesman=salesman.salesmancode'),
('salesman', 'custbranch', 'salesman.salesmancode=custbranch.salesman'),
('custbranch', 'locations', 'custbranch.defaultlocation=locations.loccode'),
('locations', 'custbranch', 'locations.loccode=custbranch.defaultlocation'),
('custbranch', 'shippers', 'custbranch.defaultshipvia=shippers.shipper_id'),
('shippers', 'custbranch', 'shippers.shipper_id=custbranch.defaultshipvia'),
('debtorsmaster', 'holdreasons', 'debtorsmaster.holdreason=holdreasons.reasoncode'),
('holdreasons', 'debtorsmaster', 'holdreasons.reasoncode=debtorsmaster.holdreason'),
('debtorsmaster', 'currencies', 'debtorsmaster.currcode=currencies.currabrev'),
('currencies', 'debtorsmaster', 'currencies.currabrev=debtorsmaster.currcode'),
('debtorsmaster', 'paymentterms', 'debtorsmaster.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'debtorsmaster', 'paymentterms.termsindicator=debtorsmaster.paymentterms'),
('debtorsmaster', 'salestypes', 'debtorsmaster.salestype=salestypes.typeabbrev'),
('salestypes', 'debtorsmaster', 'salestypes.typeabbrev=debtorsmaster.salestype'),
('debtortrans', 'custbranch', 'debtortrans.debtorno=custbranch.debtorno'),
('custbranch', 'debtortrans', 'custbranch.debtorno=debtortrans.debtorno'),
('debtortrans', 'systypes', 'debtortrans.type=systypes.typeid'),
('systypes', 'debtortrans', 'systypes.typeid=debtortrans.type'),
('debtortrans', 'periods', 'debtortrans.prd=periods.periodno'),
('periods', 'debtortrans', 'periods.periodno=debtortrans.prd'),
('debtortranstaxes', 'taxauthorities', 'debtortranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'debtortranstaxes', 'taxauthorities.taxid=debtortranstaxes.taxauthid'),
('debtortranstaxes', 'debtortrans', 'debtortranstaxes.debtortransid=debtortrans.id'),
('debtortrans', 'debtortranstaxes', 'debtortrans.id=debtortranstaxes.debtortransid'),
('discountmatrix', 'salestypes', 'discountmatrix.salestype=salestypes.typeabbrev'),
('salestypes', 'discountmatrix', 'salestypes.typeabbrev=discountmatrix.salestype'),
('freightcosts', 'locations', 'freightcosts.locationfrom=locations.loccode'),
('locations', 'freightcosts', 'locations.loccode=freightcosts.locationfrom'),
('freightcosts', 'shippers', 'freightcosts.shipperid=shippers.shipper_id'),
('shippers', 'freightcosts', 'shippers.shipper_id=freightcosts.shipperid'),
('gltrans', 'chartmaster', 'gltrans.account=chartmaster.accountcode'),
('chartmaster', 'gltrans', 'chartmaster.accountcode=gltrans.account'),
('gltrans', 'systypes', 'gltrans.type=systypes.typeid'),
('systypes', 'gltrans', 'systypes.typeid=gltrans.type'),
('gltrans', 'periods', 'gltrans.periodno=periods.periodno'),
('periods', 'gltrans', 'periods.periodno=gltrans.periodno'),
('grns', 'suppliers', 'grns.supplierid=suppliers.supplierid'),
('suppliers', 'grns', 'suppliers.supplierid=grns.supplierid'),
('grns', 'purchorderdetails', 'grns.podetailitem=purchorderdetails.podetailitem'),
('purchorderdetails', 'grns', 'purchorderdetails.podetailitem=grns.podetailitem'),
('locations', 'taxprovinces', 'locations.taxprovinceid=taxprovinces.taxprovinceid'),
('taxprovinces', 'locations', 'taxprovinces.taxprovinceid=locations.taxprovinceid'),
('locstock', 'locations', 'locstock.loccode=locations.loccode'),
('locations', 'locstock', 'locations.loccode=locstock.loccode'),
('locstock', 'stockmaster', 'locstock.stockid=stockmaster.stockid'),
('stockmaster', 'locstock', 'stockmaster.stockid=locstock.stockid'),
('loctransfers', 'locations', 'loctransfers.shiploc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.shiploc'),
('loctransfers', 'locations', 'loctransfers.recloc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.recloc'),
('loctransfers', 'stockmaster', 'loctransfers.stockid=stockmaster.stockid'),
('stockmaster', 'loctransfers', 'stockmaster.stockid=loctransfers.stockid'),
('orderdeliverydifferencesl', 'stockmaster', 'orderdeliverydifferenceslog.stockid=stockmaster.stockid'),
('stockmaster', 'orderdeliverydifferencesl', 'stockmaster.stockid=orderdeliverydifferenceslog.stockid'),
('orderdeliverydifferencesl', 'custbranch', 'orderdeliverydifferenceslog.debtorno=custbranch.debtorno'),
('custbranch', 'orderdeliverydifferencesl', 'custbranch.debtorno=orderdeliverydifferenceslog.debtorno'),
('orderdeliverydifferencesl', 'salesorders', 'orderdeliverydifferenceslog.branchcode=salesorders.orderno'),
('salesorders', 'orderdeliverydifferencesl', 'salesorders.orderno=orderdeliverydifferenceslog.branchcode'),
('prices', 'stockmaster', 'prices.stockid=stockmaster.stockid'),
('stockmaster', 'prices', 'stockmaster.stockid=prices.stockid'),
('prices', 'currencies', 'prices.currabrev=currencies.currabrev'),
('currencies', 'prices', 'currencies.currabrev=prices.currabrev'),
('prices', 'salestypes', 'prices.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'prices', 'salestypes.typeabbrev=prices.typeabbrev'),
('purchdata', 'stockmaster', 'purchdata.stockid=stockmaster.stockid'),
('stockmaster', 'purchdata', 'stockmaster.stockid=purchdata.stockid'),
('purchdata', 'suppliers', 'purchdata.supplierno=suppliers.supplierid'),
('suppliers', 'purchdata', 'suppliers.supplierid=purchdata.supplierno'),
('purchorderdetails', 'purchorders', 'purchorderdetails.orderno=purchorders.orderno'),
('purchorders', 'purchorderdetails', 'purchorders.orderno=purchorderdetails.orderno'),
('purchorders', 'suppliers', 'purchorders.supplierno=suppliers.supplierid'),
('suppliers', 'purchorders', 'suppliers.supplierid=purchorders.supplierno'),
('purchorders', 'locations', 'purchorders.intostocklocation=locations.loccode'),
('locations', 'purchorders', 'locations.loccode=purchorders.intostocklocation'),
('recurringsalesorders', 'custbranch', 'recurringsalesorders.branchcode=custbranch.branchcode'),
('custbranch', 'recurringsalesorders', 'custbranch.branchcode=recurringsalesorders.branchcode'),
('recurrsalesorderdetails', 'recurringsalesorders', 'recurrsalesorderdetails.recurrorderno=recurringsalesorders.recurrorderno'),
('recurringsalesorders', 'recurrsalesorderdetails', 'recurringsalesorders.recurrorderno=recurrsalesorderdetails.recurrorderno'),
('recurrsalesorderdetails', 'stockmaster', 'recurrsalesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'recurrsalesorderdetails', 'stockmaster.stockid=recurrsalesorderdetails.stkcode'),
('reportcolumns', 'reportheaders', 'reportcolumns.reportid=reportheaders.reportid'),
('reportheaders', 'reportcolumns', 'reportheaders.reportid=reportcolumns.reportid'),
('salesanalysis', 'periods', 'salesanalysis.periodno=periods.periodno'),
('periods', 'salesanalysis', 'periods.periodno=salesanalysis.periodno'),
('salescatprod', 'stockmaster', 'salescatprod.stockid=stockmaster.stockid'),
('stockmaster', 'salescatprod', 'stockmaster.stockid=salescatprod.stockid'),
('salescatprod', 'salescat', 'salescatprod.salescatid=salescat.salescatid'),
('salescat', 'salescatprod', 'salescat.salescatid=salescatprod.salescatid'),
('salesorderdetails', 'salesorders', 'salesorderdetails.orderno=salesorders.orderno'),
('salesorders', 'salesorderdetails', 'salesorders.orderno=salesorderdetails.orderno'),
('salesorderdetails', 'stockmaster', 'salesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'salesorderdetails', 'stockmaster.stockid=salesorderdetails.stkcode'),
('salesorders', 'custbranch', 'salesorders.branchcode=custbranch.branchcode'),
('custbranch', 'salesorders', 'custbranch.branchcode=salesorders.branchcode'),
('salesorders', 'shippers', 'salesorders.debtorno=shippers.shipper_id'),
('shippers', 'salesorders', 'shippers.shipper_id=salesorders.debtorno'),
('salesorders', 'locations', 'salesorders.fromstkloc=locations.loccode'),
('locations', 'salesorders', 'locations.loccode=salesorders.fromstkloc'),
('securitygroups', 'securityroles', 'securitygroups.secroleid=securityroles.secroleid'),
('securityroles', 'securitygroups', 'securityroles.secroleid=securitygroups.secroleid'),
('securitygroups', 'securitytokens', 'securitygroups.tokenid=securitytokens.tokenid'),
('securitytokens', 'securitygroups', 'securitytokens.tokenid=securitygroups.tokenid'),
('shipmentcharges', 'shipments', 'shipmentcharges.shiptref=shipments.shiptref'),
('shipments', 'shipmentcharges', 'shipments.shiptref=shipmentcharges.shiptref'),
('shipmentcharges', 'systypes', 'shipmentcharges.transtype=systypes.typeid'),
('systypes', 'shipmentcharges', 'systypes.typeid=shipmentcharges.transtype'),
('shipments', 'suppliers', 'shipments.supplierid=suppliers.supplierid'),
('suppliers', 'shipments', 'suppliers.supplierid=shipments.supplierid'),
('stockcheckfreeze', 'stockmaster', 'stockcheckfreeze.stockid=stockmaster.stockid'),
('stockmaster', 'stockcheckfreeze', 'stockmaster.stockid=stockcheckfreeze.stockid'),
('stockcheckfreeze', 'locations', 'stockcheckfreeze.loccode=locations.loccode'),
('locations', 'stockcheckfreeze', 'locations.loccode=stockcheckfreeze.loccode'),
('stockcounts', 'stockmaster', 'stockcounts.stockid=stockmaster.stockid'),
('stockmaster', 'stockcounts', 'stockmaster.stockid=stockcounts.stockid'),
('stockcounts', 'locations', 'stockcounts.loccode=locations.loccode'),
('locations', 'stockcounts', 'locations.loccode=stockcounts.loccode'),
('stockmaster', 'stockcategory', 'stockmaster.categoryid=stockcategory.categoryid'),
('stockcategory', 'stockmaster', 'stockcategory.categoryid=stockmaster.categoryid'),
('stockmaster', 'taxcategories', 'stockmaster.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'stockmaster', 'taxcategories.taxcatid=stockmaster.taxcatid'),
('stockmoves', 'stockmaster', 'stockmoves.stockid=stockmaster.stockid'),
('stockmaster', 'stockmoves', 'stockmaster.stockid=stockmoves.stockid'),
('stockmoves', 'systypes', 'stockmoves.type=systypes.typeid'),
('systypes', 'stockmoves', 'systypes.typeid=stockmoves.type'),
('stockmoves', 'locations', 'stockmoves.loccode=locations.loccode'),
('locations', 'stockmoves', 'locations.loccode=stockmoves.loccode'),
('stockmoves', 'periods', 'stockmoves.prd=periods.periodno'),
('periods', 'stockmoves', 'periods.periodno=stockmoves.prd'),
('stockmovestaxes', 'taxauthorities', 'stockmovestaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'stockmovestaxes', 'taxauthorities.taxid=stockmovestaxes.taxauthid'),
('stockserialitems', 'stockmaster', 'stockserialitems.stockid=stockmaster.stockid'),
('stockmaster', 'stockserialitems', 'stockmaster.stockid=stockserialitems.stockid'),
('stockserialitems', 'locations', 'stockserialitems.loccode=locations.loccode'),
('locations', 'stockserialitems', 'locations.loccode=stockserialitems.loccode'),
('stockserialmoves', 'stockmoves', 'stockserialmoves.stockmoveno=stockmoves.stkmoveno'),
('stockmoves', 'stockserialmoves', 'stockmoves.stkmoveno=stockserialmoves.stockmoveno'),
('stockserialmoves', 'stockserialitems', 'stockserialmoves.stockid=stockserialitems.stockid'),
('stockserialitems', 'stockserialmoves', 'stockserialitems.stockid=stockserialmoves.stockid'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocfrom=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocfrom'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocto=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocto'),
('suppliercontacts', 'suppliers', 'suppliercontacts.supplierid=suppliers.supplierid'),
('suppliers', 'suppliercontacts', 'suppliers.supplierid=suppliercontacts.supplierid'),
('suppliers', 'currencies', 'suppliers.currcode=currencies.currabrev'),
('currencies', 'suppliers', 'currencies.currabrev=suppliers.currcode'),
('suppliers', 'paymentterms', 'suppliers.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'suppliers', 'paymentterms.termsindicator=suppliers.paymentterms'),
('suppliers', 'taxgroups', 'suppliers.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'suppliers', 'taxgroups.taxgroupid=suppliers.taxgroupid'),
('supptrans', 'systypes', 'supptrans.type=systypes.typeid'),
('systypes', 'supptrans', 'systypes.typeid=supptrans.type'),
('supptrans', 'suppliers', 'supptrans.supplierno=suppliers.supplierid'),
('suppliers', 'supptrans', 'suppliers.supplierid=supptrans.supplierno'),
('supptranstaxes', 'taxauthorities', 'supptranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'supptranstaxes', 'taxauthorities.taxid=supptranstaxes.taxauthid'),
('supptranstaxes', 'supptrans', 'supptranstaxes.supptransid=supptrans.id'),
('supptrans', 'supptranstaxes', 'supptrans.id=supptranstaxes.supptransid'),
('taxauthorities', 'chartmaster', 'taxauthorities.taxglcode=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.taxglcode'),
('taxauthorities', 'chartmaster', 'taxauthorities.purchtaxglaccount=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.purchtaxglaccount'),
('taxauthrates', 'taxauthorities', 'taxauthrates.taxauthority=taxauthorities.taxid'),
('taxauthorities', 'taxauthrates', 'taxauthorities.taxid=taxauthrates.taxauthority'),
('taxauthrates', 'taxcategories', 'taxauthrates.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'taxauthrates', 'taxcategories.taxcatid=taxauthrates.taxcatid'),
('taxauthrates', 'taxprovinces', 'taxauthrates.dispatchtaxprovince=taxprovinces.taxprovinceid'),
('taxprovinces', 'taxauthrates', 'taxprovinces.taxprovinceid=taxauthrates.dispatchtaxprovince'),
('taxgrouptaxes', 'taxgroups', 'taxgrouptaxes.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'taxgrouptaxes', 'taxgroups.taxgroupid=taxgrouptaxes.taxgroupid'),
('taxgrouptaxes', 'taxauthorities', 'taxgrouptaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'taxgrouptaxes', 'taxauthorities.taxid=taxgrouptaxes.taxauthid'),
('workcentres', 'locations', 'workcentres.location=locations.loccode'),
('locations', 'workcentres', 'locations.loccode=workcentres.location'),
('worksorders', 'locations', 'worksorders.loccode=locations.loccode'),
('locations', 'worksorders', 'locations.loccode=worksorders.loccode'),
('worksorders', 'stockmaster', 'worksorders.stockid=stockmaster.stockid'),
('stockmaster', 'worksorders', 'stockmaster.stockid=worksorders.stockid'),
('www_users', 'locations', 'www_users.defaultlocation=locations.loccode'),
('locations', 'www_users', 'locations.loccode=www_users.defaultlocation'),
('accountgroups', 'accountsection', 'accountgroups.sectioninaccounts=accountsection.sectionid'),
('accountsection', 'accountgroups', 'accountsection.sectionid=accountgroups.sectioninaccounts'),
('bankaccounts', 'chartmaster', 'bankaccounts.accountcode=chartmaster.accountcode'),
('chartmaster', 'bankaccounts', 'chartmaster.accountcode=bankaccounts.accountcode'),
('banktrans', 'systypes', 'banktrans.type=systypes.typeid'),
('systypes', 'banktrans', 'systypes.typeid=banktrans.type'),
('banktrans', 'bankaccounts', 'banktrans.bankact=bankaccounts.accountcode'),
('bankaccounts', 'banktrans', 'bankaccounts.accountcode=banktrans.bankact'),
('bom', 'stockmaster', 'bom.parent=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.parent'),
('bom', 'stockmaster', 'bom.component=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.component'),
('bom', 'workcentres', 'bom.workcentreadded=workcentres.code'),
('workcentres', 'bom', 'workcentres.code=bom.workcentreadded'),
('bom', 'locations', 'bom.loccode=locations.loccode'),
('locations', 'bom', 'locations.loccode=bom.loccode'),
('buckets', 'workcentres', 'buckets.workcentre=workcentres.code'),
('workcentres', 'buckets', 'workcentres.code=buckets.workcentre'),
('chartdetails', 'chartmaster', 'chartdetails.accountcode=chartmaster.accountcode'),
('chartmaster', 'chartdetails', 'chartmaster.accountcode=chartdetails.accountcode'),
('chartdetails', 'periods', 'chartdetails.period=periods.periodno'),
('periods', 'chartdetails', 'periods.periodno=chartdetails.period'),
('chartmaster', 'accountgroups', 'chartmaster.group_=accountgroups.groupname'),
('accountgroups', 'chartmaster', 'accountgroups.groupname=chartmaster.group_'),
('contractbom', 'workcentres', 'contractbom.workcentreadded=workcentres.code'),
('workcentres', 'contractbom', 'workcentres.code=contractbom.workcentreadded'),
('contractbom', 'locations', 'contractbom.loccode=locations.loccode'),
('locations', 'contractbom', 'locations.loccode=contractbom.loccode'),
('contractbom', 'stockmaster', 'contractbom.component=stockmaster.stockid'),
('stockmaster', 'contractbom', 'stockmaster.stockid=contractbom.component'),
('contractreqts', 'contracts', 'contractreqts.contract=contracts.contractref'),
('contracts', 'contractreqts', 'contracts.contractref=contractreqts.contract'),
('contracts', 'custbranch', 'contracts.debtorno=custbranch.debtorno'),
('custbranch', 'contracts', 'custbranch.debtorno=contracts.debtorno'),
('contracts', 'stockcategory', 'contracts.branchcode=stockcategory.categoryid'),
('stockcategory', 'contracts', 'stockcategory.categoryid=contracts.branchcode'),
('contracts', 'salestypes', 'contracts.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'contracts', 'salestypes.typeabbrev=contracts.typeabbrev'),
('custallocns', 'debtortrans', 'custallocns.transid_allocfrom=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocfrom'),
('custallocns', 'debtortrans', 'custallocns.transid_allocto=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocto'),
('custbranch', 'debtorsmaster', 'custbranch.debtorno=debtorsmaster.debtorno'),
('debtorsmaster', 'custbranch', 'debtorsmaster.debtorno=custbranch.debtorno'),
('custbranch', 'areas', 'custbranch.area=areas.areacode'),
('areas', 'custbranch', 'areas.areacode=custbranch.area'),
('custbranch', 'salesman', 'custbranch.salesman=salesman.salesmancode'),
('salesman', 'custbranch', 'salesman.salesmancode=custbranch.salesman'),
('custbranch', 'locations', 'custbranch.defaultlocation=locations.loccode'),
('locations', 'custbranch', 'locations.loccode=custbranch.defaultlocation'),
('custbranch', 'shippers', 'custbranch.defaultshipvia=shippers.shipper_id'),
('shippers', 'custbranch', 'shippers.shipper_id=custbranch.defaultshipvia'),
('debtorsmaster', 'holdreasons', 'debtorsmaster.holdreason=holdreasons.reasoncode'),
('holdreasons', 'debtorsmaster', 'holdreasons.reasoncode=debtorsmaster.holdreason'),
('debtorsmaster', 'currencies', 'debtorsmaster.currcode=currencies.currabrev'),
('currencies', 'debtorsmaster', 'currencies.currabrev=debtorsmaster.currcode'),
('debtorsmaster', 'paymentterms', 'debtorsmaster.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'debtorsmaster', 'paymentterms.termsindicator=debtorsmaster.paymentterms'),
('debtorsmaster', 'salestypes', 'debtorsmaster.salestype=salestypes.typeabbrev'),
('salestypes', 'debtorsmaster', 'salestypes.typeabbrev=debtorsmaster.salestype'),
('debtortrans', 'custbranch', 'debtortrans.debtorno=custbranch.debtorno'),
('custbranch', 'debtortrans', 'custbranch.debtorno=debtortrans.debtorno'),
('debtortrans', 'systypes', 'debtortrans.type=systypes.typeid'),
('systypes', 'debtortrans', 'systypes.typeid=debtortrans.type'),
('debtortrans', 'periods', 'debtortrans.prd=periods.periodno'),
('periods', 'debtortrans', 'periods.periodno=debtortrans.prd'),
('debtortranstaxes', 'taxauthorities', 'debtortranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'debtortranstaxes', 'taxauthorities.taxid=debtortranstaxes.taxauthid'),
('debtortranstaxes', 'debtortrans', 'debtortranstaxes.debtortransid=debtortrans.id'),
('debtortrans', 'debtortranstaxes', 'debtortrans.id=debtortranstaxes.debtortransid'),
('discountmatrix', 'salestypes', 'discountmatrix.salestype=salestypes.typeabbrev'),
('salestypes', 'discountmatrix', 'salestypes.typeabbrev=discountmatrix.salestype'),
('freightcosts', 'locations', 'freightcosts.locationfrom=locations.loccode'),
('locations', 'freightcosts', 'locations.loccode=freightcosts.locationfrom'),
('freightcosts', 'shippers', 'freightcosts.shipperid=shippers.shipper_id'),
('shippers', 'freightcosts', 'shippers.shipper_id=freightcosts.shipperid'),
('gltrans', 'chartmaster', 'gltrans.account=chartmaster.accountcode'),
('chartmaster', 'gltrans', 'chartmaster.accountcode=gltrans.account'),
('gltrans', 'systypes', 'gltrans.type=systypes.typeid'),
('systypes', 'gltrans', 'systypes.typeid=gltrans.type'),
('gltrans', 'periods', 'gltrans.periodno=periods.periodno'),
('periods', 'gltrans', 'periods.periodno=gltrans.periodno'),
('grns', 'suppliers', 'grns.supplierid=suppliers.supplierid'),
('suppliers', 'grns', 'suppliers.supplierid=grns.supplierid'),
('grns', 'purchorderdetails', 'grns.podetailitem=purchorderdetails.podetailitem'),
('purchorderdetails', 'grns', 'purchorderdetails.podetailitem=grns.podetailitem'),
('locations', 'taxprovinces', 'locations.taxprovinceid=taxprovinces.taxprovinceid'),
('taxprovinces', 'locations', 'taxprovinces.taxprovinceid=locations.taxprovinceid'),
('locstock', 'locations', 'locstock.loccode=locations.loccode'),
('locations', 'locstock', 'locations.loccode=locstock.loccode'),
('locstock', 'stockmaster', 'locstock.stockid=stockmaster.stockid'),
('stockmaster', 'locstock', 'stockmaster.stockid=locstock.stockid'),
('loctransfers', 'locations', 'loctransfers.shiploc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.shiploc'),
('loctransfers', 'locations', 'loctransfers.recloc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.recloc'),
('loctransfers', 'stockmaster', 'loctransfers.stockid=stockmaster.stockid'),
('stockmaster', 'loctransfers', 'stockmaster.stockid=loctransfers.stockid'),
('orderdeliverydifferencesl', 'stockmaster', 'orderdeliverydifferenceslog.stockid=stockmaster.stockid'),
('stockmaster', 'orderdeliverydifferencesl', 'stockmaster.stockid=orderdeliverydifferenceslog.stockid'),
('orderdeliverydifferencesl', 'custbranch', 'orderdeliverydifferenceslog.debtorno=custbranch.debtorno'),
('custbranch', 'orderdeliverydifferencesl', 'custbranch.debtorno=orderdeliverydifferenceslog.debtorno'),
('orderdeliverydifferencesl', 'salesorders', 'orderdeliverydifferenceslog.branchcode=salesorders.orderno'),
('salesorders', 'orderdeliverydifferencesl', 'salesorders.orderno=orderdeliverydifferenceslog.branchcode'),
('prices', 'stockmaster', 'prices.stockid=stockmaster.stockid'),
('stockmaster', 'prices', 'stockmaster.stockid=prices.stockid'),
('prices', 'currencies', 'prices.currabrev=currencies.currabrev'),
('currencies', 'prices', 'currencies.currabrev=prices.currabrev'),
('prices', 'salestypes', 'prices.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'prices', 'salestypes.typeabbrev=prices.typeabbrev'),
('purchdata', 'stockmaster', 'purchdata.stockid=stockmaster.stockid'),
('stockmaster', 'purchdata', 'stockmaster.stockid=purchdata.stockid'),
('purchdata', 'suppliers', 'purchdata.supplierno=suppliers.supplierid'),
('suppliers', 'purchdata', 'suppliers.supplierid=purchdata.supplierno'),
('purchorderdetails', 'purchorders', 'purchorderdetails.orderno=purchorders.orderno'),
('purchorders', 'purchorderdetails', 'purchorders.orderno=purchorderdetails.orderno'),
('purchorders', 'suppliers', 'purchorders.supplierno=suppliers.supplierid'),
('suppliers', 'purchorders', 'suppliers.supplierid=purchorders.supplierno'),
('purchorders', 'locations', 'purchorders.intostocklocation=locations.loccode'),
('locations', 'purchorders', 'locations.loccode=purchorders.intostocklocation'),
('recurringsalesorders', 'custbranch', 'recurringsalesorders.branchcode=custbranch.branchcode'),
('custbranch', 'recurringsalesorders', 'custbranch.branchcode=recurringsalesorders.branchcode'),
('recurrsalesorderdetails', 'recurringsalesorders', 'recurrsalesorderdetails.recurrorderno=recurringsalesorders.recurrorderno'),
('recurringsalesorders', 'recurrsalesorderdetails', 'recurringsalesorders.recurrorderno=recurrsalesorderdetails.recurrorderno'),
('recurrsalesorderdetails', 'stockmaster', 'recurrsalesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'recurrsalesorderdetails', 'stockmaster.stockid=recurrsalesorderdetails.stkcode'),
('reportcolumns', 'reportheaders', 'reportcolumns.reportid=reportheaders.reportid'),
('reportheaders', 'reportcolumns', 'reportheaders.reportid=reportcolumns.reportid'),
('salesanalysis', 'periods', 'salesanalysis.periodno=periods.periodno'),
('periods', 'salesanalysis', 'periods.periodno=salesanalysis.periodno'),
('salescatprod', 'stockmaster', 'salescatprod.stockid=stockmaster.stockid'),
('stockmaster', 'salescatprod', 'stockmaster.stockid=salescatprod.stockid'),
('salescatprod', 'salescat', 'salescatprod.salescatid=salescat.salescatid'),
('salescat', 'salescatprod', 'salescat.salescatid=salescatprod.salescatid'),
('salesorderdetails', 'salesorders', 'salesorderdetails.orderno=salesorders.orderno'),
('salesorders', 'salesorderdetails', 'salesorders.orderno=salesorderdetails.orderno'),
('salesorderdetails', 'stockmaster', 'salesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'salesorderdetails', 'stockmaster.stockid=salesorderdetails.stkcode'),
('salesorders', 'custbranch', 'salesorders.branchcode=custbranch.branchcode'),
('custbranch', 'salesorders', 'custbranch.branchcode=salesorders.branchcode'),
('salesorders', 'shippers', 'salesorders.debtorno=shippers.shipper_id'),
('shippers', 'salesorders', 'shippers.shipper_id=salesorders.debtorno'),
('salesorders', 'locations', 'salesorders.fromstkloc=locations.loccode'),
('locations', 'salesorders', 'locations.loccode=salesorders.fromstkloc'),
('securitygroups', 'securityroles', 'securitygroups.secroleid=securityroles.secroleid'),
('securityroles', 'securitygroups', 'securityroles.secroleid=securitygroups.secroleid'),
('securitygroups', 'securitytokens', 'securitygroups.tokenid=securitytokens.tokenid'),
('securitytokens', 'securitygroups', 'securitytokens.tokenid=securitygroups.tokenid'),
('shipmentcharges', 'shipments', 'shipmentcharges.shiptref=shipments.shiptref'),
('shipments', 'shipmentcharges', 'shipments.shiptref=shipmentcharges.shiptref'),
('shipmentcharges', 'systypes', 'shipmentcharges.transtype=systypes.typeid'),
('systypes', 'shipmentcharges', 'systypes.typeid=shipmentcharges.transtype'),
('shipments', 'suppliers', 'shipments.supplierid=suppliers.supplierid'),
('suppliers', 'shipments', 'suppliers.supplierid=shipments.supplierid'),
('stockcheckfreeze', 'stockmaster', 'stockcheckfreeze.stockid=stockmaster.stockid'),
('stockmaster', 'stockcheckfreeze', 'stockmaster.stockid=stockcheckfreeze.stockid'),
('stockcheckfreeze', 'locations', 'stockcheckfreeze.loccode=locations.loccode'),
('locations', 'stockcheckfreeze', 'locations.loccode=stockcheckfreeze.loccode'),
('stockcounts', 'stockmaster', 'stockcounts.stockid=stockmaster.stockid'),
('stockmaster', 'stockcounts', 'stockmaster.stockid=stockcounts.stockid'),
('stockcounts', 'locations', 'stockcounts.loccode=locations.loccode'),
('locations', 'stockcounts', 'locations.loccode=stockcounts.loccode'),
('stockmaster', 'stockcategory', 'stockmaster.categoryid=stockcategory.categoryid'),
('stockcategory', 'stockmaster', 'stockcategory.categoryid=stockmaster.categoryid'),
('stockmaster', 'taxcategories', 'stockmaster.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'stockmaster', 'taxcategories.taxcatid=stockmaster.taxcatid'),
('stockmoves', 'stockmaster', 'stockmoves.stockid=stockmaster.stockid'),
('stockmaster', 'stockmoves', 'stockmaster.stockid=stockmoves.stockid'),
('stockmoves', 'systypes', 'stockmoves.type=systypes.typeid'),
('systypes', 'stockmoves', 'systypes.typeid=stockmoves.type'),
('stockmoves', 'locations', 'stockmoves.loccode=locations.loccode'),
('locations', 'stockmoves', 'locations.loccode=stockmoves.loccode'),
('stockmoves', 'periods', 'stockmoves.prd=periods.periodno'),
('periods', 'stockmoves', 'periods.periodno=stockmoves.prd'),
('stockmovestaxes', 'taxauthorities', 'stockmovestaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'stockmovestaxes', 'taxauthorities.taxid=stockmovestaxes.taxauthid'),
('stockserialitems', 'stockmaster', 'stockserialitems.stockid=stockmaster.stockid'),
('stockmaster', 'stockserialitems', 'stockmaster.stockid=stockserialitems.stockid'),
('stockserialitems', 'locations', 'stockserialitems.loccode=locations.loccode'),
('locations', 'stockserialitems', 'locations.loccode=stockserialitems.loccode'),
('stockserialmoves', 'stockmoves', 'stockserialmoves.stockmoveno=stockmoves.stkmoveno'),
('stockmoves', 'stockserialmoves', 'stockmoves.stkmoveno=stockserialmoves.stockmoveno'),
('stockserialmoves', 'stockserialitems', 'stockserialmoves.stockid=stockserialitems.stockid'),
('stockserialitems', 'stockserialmoves', 'stockserialitems.stockid=stockserialmoves.stockid'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocfrom=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocfrom'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocto=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocto'),
('suppliercontacts', 'suppliers', 'suppliercontacts.supplierid=suppliers.supplierid'),
('suppliers', 'suppliercontacts', 'suppliers.supplierid=suppliercontacts.supplierid'),
('suppliers', 'currencies', 'suppliers.currcode=currencies.currabrev'),
('currencies', 'suppliers', 'currencies.currabrev=suppliers.currcode'),
('suppliers', 'paymentterms', 'suppliers.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'suppliers', 'paymentterms.termsindicator=suppliers.paymentterms'),
('suppliers', 'taxgroups', 'suppliers.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'suppliers', 'taxgroups.taxgroupid=suppliers.taxgroupid'),
('supptrans', 'systypes', 'supptrans.type=systypes.typeid'),
('systypes', 'supptrans', 'systypes.typeid=supptrans.type'),
('supptrans', 'suppliers', 'supptrans.supplierno=suppliers.supplierid'),
('suppliers', 'supptrans', 'suppliers.supplierid=supptrans.supplierno'),
('supptranstaxes', 'taxauthorities', 'supptranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'supptranstaxes', 'taxauthorities.taxid=supptranstaxes.taxauthid');
INSERT INTO `reportlinks` (`table1`, `table2`, `equation`) VALUES
('supptranstaxes', 'supptrans', 'supptranstaxes.supptransid=supptrans.id'),
('supptrans', 'supptranstaxes', 'supptrans.id=supptranstaxes.supptransid'),
('taxauthorities', 'chartmaster', 'taxauthorities.taxglcode=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.taxglcode'),
('taxauthorities', 'chartmaster', 'taxauthorities.purchtaxglaccount=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.purchtaxglaccount'),
('taxauthrates', 'taxauthorities', 'taxauthrates.taxauthority=taxauthorities.taxid'),
('taxauthorities', 'taxauthrates', 'taxauthorities.taxid=taxauthrates.taxauthority'),
('taxauthrates', 'taxcategories', 'taxauthrates.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'taxauthrates', 'taxcategories.taxcatid=taxauthrates.taxcatid'),
('taxauthrates', 'taxprovinces', 'taxauthrates.dispatchtaxprovince=taxprovinces.taxprovinceid'),
('taxprovinces', 'taxauthrates', 'taxprovinces.taxprovinceid=taxauthrates.dispatchtaxprovince'),
('taxgrouptaxes', 'taxgroups', 'taxgrouptaxes.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'taxgrouptaxes', 'taxgroups.taxgroupid=taxgrouptaxes.taxgroupid'),
('taxgrouptaxes', 'taxauthorities', 'taxgrouptaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'taxgrouptaxes', 'taxauthorities.taxid=taxgrouptaxes.taxauthid'),
('workcentres', 'locations', 'workcentres.location=locations.loccode'),
('locations', 'workcentres', 'locations.loccode=workcentres.location'),
('worksorders', 'locations', 'worksorders.loccode=locations.loccode'),
('locations', 'worksorders', 'locations.loccode=worksorders.loccode'),
('worksorders', 'stockmaster', 'worksorders.stockid=stockmaster.stockid'),
('stockmaster', 'worksorders', 'stockmaster.stockid=worksorders.stockid'),
('www_users', 'locations', 'www_users.defaultlocation=locations.loccode'),
('locations', 'www_users', 'locations.loccode=www_users.defaultlocation'),
('accountgroups', 'accountsection', 'accountgroups.sectioninaccounts=accountsection.sectionid'),
('accountsection', 'accountgroups', 'accountsection.sectionid=accountgroups.sectioninaccounts'),
('bankaccounts', 'chartmaster', 'bankaccounts.accountcode=chartmaster.accountcode'),
('chartmaster', 'bankaccounts', 'chartmaster.accountcode=bankaccounts.accountcode'),
('banktrans', 'systypes', 'banktrans.type=systypes.typeid'),
('systypes', 'banktrans', 'systypes.typeid=banktrans.type'),
('banktrans', 'bankaccounts', 'banktrans.bankact=bankaccounts.accountcode'),
('bankaccounts', 'banktrans', 'bankaccounts.accountcode=banktrans.bankact'),
('bom', 'stockmaster', 'bom.parent=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.parent'),
('bom', 'stockmaster', 'bom.component=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.component'),
('bom', 'workcentres', 'bom.workcentreadded=workcentres.code'),
('workcentres', 'bom', 'workcentres.code=bom.workcentreadded'),
('bom', 'locations', 'bom.loccode=locations.loccode'),
('locations', 'bom', 'locations.loccode=bom.loccode'),
('buckets', 'workcentres', 'buckets.workcentre=workcentres.code'),
('workcentres', 'buckets', 'workcentres.code=buckets.workcentre'),
('chartdetails', 'chartmaster', 'chartdetails.accountcode=chartmaster.accountcode'),
('chartmaster', 'chartdetails', 'chartmaster.accountcode=chartdetails.accountcode'),
('chartdetails', 'periods', 'chartdetails.period=periods.periodno'),
('periods', 'chartdetails', 'periods.periodno=chartdetails.period'),
('chartmaster', 'accountgroups', 'chartmaster.group_=accountgroups.groupname'),
('accountgroups', 'chartmaster', 'accountgroups.groupname=chartmaster.group_'),
('contractbom', 'workcentres', 'contractbom.workcentreadded=workcentres.code'),
('workcentres', 'contractbom', 'workcentres.code=contractbom.workcentreadded'),
('contractbom', 'locations', 'contractbom.loccode=locations.loccode'),
('locations', 'contractbom', 'locations.loccode=contractbom.loccode'),
('contractbom', 'stockmaster', 'contractbom.component=stockmaster.stockid'),
('stockmaster', 'contractbom', 'stockmaster.stockid=contractbom.component'),
('contractreqts', 'contracts', 'contractreqts.contract=contracts.contractref'),
('contracts', 'contractreqts', 'contracts.contractref=contractreqts.contract'),
('contracts', 'custbranch', 'contracts.debtorno=custbranch.debtorno'),
('custbranch', 'contracts', 'custbranch.debtorno=contracts.debtorno'),
('contracts', 'stockcategory', 'contracts.branchcode=stockcategory.categoryid'),
('stockcategory', 'contracts', 'stockcategory.categoryid=contracts.branchcode'),
('contracts', 'salestypes', 'contracts.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'contracts', 'salestypes.typeabbrev=contracts.typeabbrev'),
('custallocns', 'debtortrans', 'custallocns.transid_allocfrom=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocfrom'),
('custallocns', 'debtortrans', 'custallocns.transid_allocto=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocto'),
('custbranch', 'debtorsmaster', 'custbranch.debtorno=debtorsmaster.debtorno'),
('debtorsmaster', 'custbranch', 'debtorsmaster.debtorno=custbranch.debtorno'),
('custbranch', 'areas', 'custbranch.area=areas.areacode'),
('areas', 'custbranch', 'areas.areacode=custbranch.area'),
('custbranch', 'salesman', 'custbranch.salesman=salesman.salesmancode'),
('salesman', 'custbranch', 'salesman.salesmancode=custbranch.salesman'),
('custbranch', 'locations', 'custbranch.defaultlocation=locations.loccode'),
('locations', 'custbranch', 'locations.loccode=custbranch.defaultlocation'),
('custbranch', 'shippers', 'custbranch.defaultshipvia=shippers.shipper_id'),
('shippers', 'custbranch', 'shippers.shipper_id=custbranch.defaultshipvia'),
('debtorsmaster', 'holdreasons', 'debtorsmaster.holdreason=holdreasons.reasoncode'),
('holdreasons', 'debtorsmaster', 'holdreasons.reasoncode=debtorsmaster.holdreason'),
('debtorsmaster', 'currencies', 'debtorsmaster.currcode=currencies.currabrev'),
('currencies', 'debtorsmaster', 'currencies.currabrev=debtorsmaster.currcode'),
('debtorsmaster', 'paymentterms', 'debtorsmaster.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'debtorsmaster', 'paymentterms.termsindicator=debtorsmaster.paymentterms'),
('debtorsmaster', 'salestypes', 'debtorsmaster.salestype=salestypes.typeabbrev'),
('salestypes', 'debtorsmaster', 'salestypes.typeabbrev=debtorsmaster.salestype'),
('debtortrans', 'custbranch', 'debtortrans.debtorno=custbranch.debtorno'),
('custbranch', 'debtortrans', 'custbranch.debtorno=debtortrans.debtorno'),
('debtortrans', 'systypes', 'debtortrans.type=systypes.typeid'),
('systypes', 'debtortrans', 'systypes.typeid=debtortrans.type'),
('debtortrans', 'periods', 'debtortrans.prd=periods.periodno'),
('periods', 'debtortrans', 'periods.periodno=debtortrans.prd'),
('debtortranstaxes', 'taxauthorities', 'debtortranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'debtortranstaxes', 'taxauthorities.taxid=debtortranstaxes.taxauthid'),
('debtortranstaxes', 'debtortrans', 'debtortranstaxes.debtortransid=debtortrans.id'),
('debtortrans', 'debtortranstaxes', 'debtortrans.id=debtortranstaxes.debtortransid'),
('discountmatrix', 'salestypes', 'discountmatrix.salestype=salestypes.typeabbrev'),
('salestypes', 'discountmatrix', 'salestypes.typeabbrev=discountmatrix.salestype'),
('freightcosts', 'locations', 'freightcosts.locationfrom=locations.loccode'),
('locations', 'freightcosts', 'locations.loccode=freightcosts.locationfrom'),
('freightcosts', 'shippers', 'freightcosts.shipperid=shippers.shipper_id'),
('shippers', 'freightcosts', 'shippers.shipper_id=freightcosts.shipperid'),
('gltrans', 'chartmaster', 'gltrans.account=chartmaster.accountcode'),
('chartmaster', 'gltrans', 'chartmaster.accountcode=gltrans.account'),
('gltrans', 'systypes', 'gltrans.type=systypes.typeid'),
('systypes', 'gltrans', 'systypes.typeid=gltrans.type'),
('gltrans', 'periods', 'gltrans.periodno=periods.periodno'),
('periods', 'gltrans', 'periods.periodno=gltrans.periodno'),
('grns', 'suppliers', 'grns.supplierid=suppliers.supplierid'),
('suppliers', 'grns', 'suppliers.supplierid=grns.supplierid'),
('grns', 'purchorderdetails', 'grns.podetailitem=purchorderdetails.podetailitem'),
('purchorderdetails', 'grns', 'purchorderdetails.podetailitem=grns.podetailitem'),
('locations', 'taxprovinces', 'locations.taxprovinceid=taxprovinces.taxprovinceid'),
('taxprovinces', 'locations', 'taxprovinces.taxprovinceid=locations.taxprovinceid'),
('locstock', 'locations', 'locstock.loccode=locations.loccode'),
('locations', 'locstock', 'locations.loccode=locstock.loccode'),
('locstock', 'stockmaster', 'locstock.stockid=stockmaster.stockid'),
('stockmaster', 'locstock', 'stockmaster.stockid=locstock.stockid'),
('loctransfers', 'locations', 'loctransfers.shiploc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.shiploc'),
('loctransfers', 'locations', 'loctransfers.recloc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.recloc'),
('loctransfers', 'stockmaster', 'loctransfers.stockid=stockmaster.stockid'),
('stockmaster', 'loctransfers', 'stockmaster.stockid=loctransfers.stockid'),
('orderdeliverydifferencesl', 'stockmaster', 'orderdeliverydifferenceslog.stockid=stockmaster.stockid'),
('stockmaster', 'orderdeliverydifferencesl', 'stockmaster.stockid=orderdeliverydifferenceslog.stockid'),
('orderdeliverydifferencesl', 'custbranch', 'orderdeliverydifferenceslog.debtorno=custbranch.debtorno'),
('custbranch', 'orderdeliverydifferencesl', 'custbranch.debtorno=orderdeliverydifferenceslog.debtorno'),
('orderdeliverydifferencesl', 'salesorders', 'orderdeliverydifferenceslog.branchcode=salesorders.orderno'),
('salesorders', 'orderdeliverydifferencesl', 'salesorders.orderno=orderdeliverydifferenceslog.branchcode'),
('prices', 'stockmaster', 'prices.stockid=stockmaster.stockid'),
('stockmaster', 'prices', 'stockmaster.stockid=prices.stockid'),
('prices', 'currencies', 'prices.currabrev=currencies.currabrev'),
('currencies', 'prices', 'currencies.currabrev=prices.currabrev'),
('prices', 'salestypes', 'prices.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'prices', 'salestypes.typeabbrev=prices.typeabbrev'),
('purchdata', 'stockmaster', 'purchdata.stockid=stockmaster.stockid'),
('stockmaster', 'purchdata', 'stockmaster.stockid=purchdata.stockid'),
('purchdata', 'suppliers', 'purchdata.supplierno=suppliers.supplierid'),
('suppliers', 'purchdata', 'suppliers.supplierid=purchdata.supplierno'),
('purchorderdetails', 'purchorders', 'purchorderdetails.orderno=purchorders.orderno'),
('purchorders', 'purchorderdetails', 'purchorders.orderno=purchorderdetails.orderno'),
('purchorders', 'suppliers', 'purchorders.supplierno=suppliers.supplierid'),
('suppliers', 'purchorders', 'suppliers.supplierid=purchorders.supplierno'),
('purchorders', 'locations', 'purchorders.intostocklocation=locations.loccode'),
('locations', 'purchorders', 'locations.loccode=purchorders.intostocklocation'),
('recurringsalesorders', 'custbranch', 'recurringsalesorders.branchcode=custbranch.branchcode'),
('custbranch', 'recurringsalesorders', 'custbranch.branchcode=recurringsalesorders.branchcode'),
('recurrsalesorderdetails', 'recurringsalesorders', 'recurrsalesorderdetails.recurrorderno=recurringsalesorders.recurrorderno'),
('recurringsalesorders', 'recurrsalesorderdetails', 'recurringsalesorders.recurrorderno=recurrsalesorderdetails.recurrorderno'),
('recurrsalesorderdetails', 'stockmaster', 'recurrsalesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'recurrsalesorderdetails', 'stockmaster.stockid=recurrsalesorderdetails.stkcode'),
('reportcolumns', 'reportheaders', 'reportcolumns.reportid=reportheaders.reportid'),
('reportheaders', 'reportcolumns', 'reportheaders.reportid=reportcolumns.reportid'),
('salesanalysis', 'periods', 'salesanalysis.periodno=periods.periodno'),
('periods', 'salesanalysis', 'periods.periodno=salesanalysis.periodno'),
('salescatprod', 'stockmaster', 'salescatprod.stockid=stockmaster.stockid'),
('stockmaster', 'salescatprod', 'stockmaster.stockid=salescatprod.stockid'),
('salescatprod', 'salescat', 'salescatprod.salescatid=salescat.salescatid'),
('salescat', 'salescatprod', 'salescat.salescatid=salescatprod.salescatid'),
('salesorderdetails', 'salesorders', 'salesorderdetails.orderno=salesorders.orderno'),
('salesorders', 'salesorderdetails', 'salesorders.orderno=salesorderdetails.orderno'),
('salesorderdetails', 'stockmaster', 'salesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'salesorderdetails', 'stockmaster.stockid=salesorderdetails.stkcode'),
('salesorders', 'custbranch', 'salesorders.branchcode=custbranch.branchcode'),
('custbranch', 'salesorders', 'custbranch.branchcode=salesorders.branchcode'),
('salesorders', 'shippers', 'salesorders.debtorno=shippers.shipper_id'),
('shippers', 'salesorders', 'shippers.shipper_id=salesorders.debtorno'),
('salesorders', 'locations', 'salesorders.fromstkloc=locations.loccode'),
('locations', 'salesorders', 'locations.loccode=salesorders.fromstkloc'),
('securitygroups', 'securityroles', 'securitygroups.secroleid=securityroles.secroleid'),
('securityroles', 'securitygroups', 'securityroles.secroleid=securitygroups.secroleid'),
('securitygroups', 'securitytokens', 'securitygroups.tokenid=securitytokens.tokenid'),
('securitytokens', 'securitygroups', 'securitytokens.tokenid=securitygroups.tokenid'),
('shipmentcharges', 'shipments', 'shipmentcharges.shiptref=shipments.shiptref'),
('shipments', 'shipmentcharges', 'shipments.shiptref=shipmentcharges.shiptref'),
('shipmentcharges', 'systypes', 'shipmentcharges.transtype=systypes.typeid'),
('systypes', 'shipmentcharges', 'systypes.typeid=shipmentcharges.transtype'),
('shipments', 'suppliers', 'shipments.supplierid=suppliers.supplierid'),
('suppliers', 'shipments', 'suppliers.supplierid=shipments.supplierid'),
('stockcheckfreeze', 'stockmaster', 'stockcheckfreeze.stockid=stockmaster.stockid'),
('stockmaster', 'stockcheckfreeze', 'stockmaster.stockid=stockcheckfreeze.stockid'),
('stockcheckfreeze', 'locations', 'stockcheckfreeze.loccode=locations.loccode'),
('locations', 'stockcheckfreeze', 'locations.loccode=stockcheckfreeze.loccode'),
('stockcounts', 'stockmaster', 'stockcounts.stockid=stockmaster.stockid'),
('stockmaster', 'stockcounts', 'stockmaster.stockid=stockcounts.stockid'),
('stockcounts', 'locations', 'stockcounts.loccode=locations.loccode'),
('locations', 'stockcounts', 'locations.loccode=stockcounts.loccode'),
('stockmaster', 'stockcategory', 'stockmaster.categoryid=stockcategory.categoryid'),
('stockcategory', 'stockmaster', 'stockcategory.categoryid=stockmaster.categoryid'),
('stockmaster', 'taxcategories', 'stockmaster.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'stockmaster', 'taxcategories.taxcatid=stockmaster.taxcatid'),
('stockmoves', 'stockmaster', 'stockmoves.stockid=stockmaster.stockid'),
('stockmaster', 'stockmoves', 'stockmaster.stockid=stockmoves.stockid'),
('stockmoves', 'systypes', 'stockmoves.type=systypes.typeid'),
('systypes', 'stockmoves', 'systypes.typeid=stockmoves.type'),
('stockmoves', 'locations', 'stockmoves.loccode=locations.loccode'),
('locations', 'stockmoves', 'locations.loccode=stockmoves.loccode'),
('stockmoves', 'periods', 'stockmoves.prd=periods.periodno'),
('periods', 'stockmoves', 'periods.periodno=stockmoves.prd'),
('stockmovestaxes', 'taxauthorities', 'stockmovestaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'stockmovestaxes', 'taxauthorities.taxid=stockmovestaxes.taxauthid'),
('stockserialitems', 'stockmaster', 'stockserialitems.stockid=stockmaster.stockid'),
('stockmaster', 'stockserialitems', 'stockmaster.stockid=stockserialitems.stockid'),
('stockserialitems', 'locations', 'stockserialitems.loccode=locations.loccode'),
('locations', 'stockserialitems', 'locations.loccode=stockserialitems.loccode'),
('stockserialmoves', 'stockmoves', 'stockserialmoves.stockmoveno=stockmoves.stkmoveno'),
('stockmoves', 'stockserialmoves', 'stockmoves.stkmoveno=stockserialmoves.stockmoveno'),
('stockserialmoves', 'stockserialitems', 'stockserialmoves.stockid=stockserialitems.stockid'),
('stockserialitems', 'stockserialmoves', 'stockserialitems.stockid=stockserialmoves.stockid'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocfrom=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocfrom'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocto=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocto'),
('suppliercontacts', 'suppliers', 'suppliercontacts.supplierid=suppliers.supplierid'),
('suppliers', 'suppliercontacts', 'suppliers.supplierid=suppliercontacts.supplierid'),
('suppliers', 'currencies', 'suppliers.currcode=currencies.currabrev'),
('currencies', 'suppliers', 'currencies.currabrev=suppliers.currcode'),
('suppliers', 'paymentterms', 'suppliers.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'suppliers', 'paymentterms.termsindicator=suppliers.paymentterms'),
('suppliers', 'taxgroups', 'suppliers.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'suppliers', 'taxgroups.taxgroupid=suppliers.taxgroupid'),
('supptrans', 'systypes', 'supptrans.type=systypes.typeid'),
('systypes', 'supptrans', 'systypes.typeid=supptrans.type'),
('supptrans', 'suppliers', 'supptrans.supplierno=suppliers.supplierid'),
('suppliers', 'supptrans', 'suppliers.supplierid=supptrans.supplierno'),
('supptranstaxes', 'taxauthorities', 'supptranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'supptranstaxes', 'taxauthorities.taxid=supptranstaxes.taxauthid'),
('supptranstaxes', 'supptrans', 'supptranstaxes.supptransid=supptrans.id'),
('supptrans', 'supptranstaxes', 'supptrans.id=supptranstaxes.supptransid'),
('taxauthorities', 'chartmaster', 'taxauthorities.taxglcode=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.taxglcode'),
('taxauthorities', 'chartmaster', 'taxauthorities.purchtaxglaccount=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.purchtaxglaccount'),
('taxauthrates', 'taxauthorities', 'taxauthrates.taxauthority=taxauthorities.taxid'),
('taxauthorities', 'taxauthrates', 'taxauthorities.taxid=taxauthrates.taxauthority'),
('taxauthrates', 'taxcategories', 'taxauthrates.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'taxauthrates', 'taxcategories.taxcatid=taxauthrates.taxcatid'),
('taxauthrates', 'taxprovinces', 'taxauthrates.dispatchtaxprovince=taxprovinces.taxprovinceid'),
('taxprovinces', 'taxauthrates', 'taxprovinces.taxprovinceid=taxauthrates.dispatchtaxprovince'),
('taxgrouptaxes', 'taxgroups', 'taxgrouptaxes.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'taxgrouptaxes', 'taxgroups.taxgroupid=taxgrouptaxes.taxgroupid'),
('taxgrouptaxes', 'taxauthorities', 'taxgrouptaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'taxgrouptaxes', 'taxauthorities.taxid=taxgrouptaxes.taxauthid'),
('workcentres', 'locations', 'workcentres.location=locations.loccode'),
('locations', 'workcentres', 'locations.loccode=workcentres.location'),
('worksorders', 'locations', 'worksorders.loccode=locations.loccode'),
('locations', 'worksorders', 'locations.loccode=worksorders.loccode'),
('worksorders', 'stockmaster', 'worksorders.stockid=stockmaster.stockid'),
('stockmaster', 'worksorders', 'stockmaster.stockid=worksorders.stockid'),
('www_users', 'locations', 'www_users.defaultlocation=locations.loccode'),
('locations', 'www_users', 'locations.loccode=www_users.defaultlocation'),
('accountgroups', 'accountsection', 'accountgroups.sectioninaccounts=accountsection.sectionid'),
('accountsection', 'accountgroups', 'accountsection.sectionid=accountgroups.sectioninaccounts'),
('bankaccounts', 'chartmaster', 'bankaccounts.accountcode=chartmaster.accountcode'),
('chartmaster', 'bankaccounts', 'chartmaster.accountcode=bankaccounts.accountcode'),
('banktrans', 'systypes', 'banktrans.type=systypes.typeid'),
('systypes', 'banktrans', 'systypes.typeid=banktrans.type'),
('banktrans', 'bankaccounts', 'banktrans.bankact=bankaccounts.accountcode'),
('bankaccounts', 'banktrans', 'bankaccounts.accountcode=banktrans.bankact'),
('bom', 'stockmaster', 'bom.parent=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.parent'),
('bom', 'stockmaster', 'bom.component=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.component'),
('bom', 'workcentres', 'bom.workcentreadded=workcentres.code'),
('workcentres', 'bom', 'workcentres.code=bom.workcentreadded'),
('bom', 'locations', 'bom.loccode=locations.loccode'),
('locations', 'bom', 'locations.loccode=bom.loccode'),
('buckets', 'workcentres', 'buckets.workcentre=workcentres.code'),
('workcentres', 'buckets', 'workcentres.code=buckets.workcentre'),
('chartdetails', 'chartmaster', 'chartdetails.accountcode=chartmaster.accountcode'),
('chartmaster', 'chartdetails', 'chartmaster.accountcode=chartdetails.accountcode'),
('chartdetails', 'periods', 'chartdetails.period=periods.periodno'),
('periods', 'chartdetails', 'periods.periodno=chartdetails.period'),
('chartmaster', 'accountgroups', 'chartmaster.group_=accountgroups.groupname'),
('accountgroups', 'chartmaster', 'accountgroups.groupname=chartmaster.group_'),
('contractbom', 'workcentres', 'contractbom.workcentreadded=workcentres.code'),
('workcentres', 'contractbom', 'workcentres.code=contractbom.workcentreadded'),
('contractbom', 'locations', 'contractbom.loccode=locations.loccode'),
('locations', 'contractbom', 'locations.loccode=contractbom.loccode'),
('contractbom', 'stockmaster', 'contractbom.component=stockmaster.stockid'),
('stockmaster', 'contractbom', 'stockmaster.stockid=contractbom.component'),
('contractreqts', 'contracts', 'contractreqts.contract=contracts.contractref'),
('contracts', 'contractreqts', 'contracts.contractref=contractreqts.contract'),
('contracts', 'custbranch', 'contracts.debtorno=custbranch.debtorno'),
('custbranch', 'contracts', 'custbranch.debtorno=contracts.debtorno'),
('contracts', 'stockcategory', 'contracts.branchcode=stockcategory.categoryid'),
('stockcategory', 'contracts', 'stockcategory.categoryid=contracts.branchcode'),
('contracts', 'salestypes', 'contracts.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'contracts', 'salestypes.typeabbrev=contracts.typeabbrev'),
('custallocns', 'debtortrans', 'custallocns.transid_allocfrom=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocfrom'),
('custallocns', 'debtortrans', 'custallocns.transid_allocto=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocto'),
('custbranch', 'debtorsmaster', 'custbranch.debtorno=debtorsmaster.debtorno'),
('debtorsmaster', 'custbranch', 'debtorsmaster.debtorno=custbranch.debtorno'),
('custbranch', 'areas', 'custbranch.area=areas.areacode'),
('areas', 'custbranch', 'areas.areacode=custbranch.area'),
('custbranch', 'salesman', 'custbranch.salesman=salesman.salesmancode'),
('salesman', 'custbranch', 'salesman.salesmancode=custbranch.salesman'),
('custbranch', 'locations', 'custbranch.defaultlocation=locations.loccode'),
('locations', 'custbranch', 'locations.loccode=custbranch.defaultlocation'),
('custbranch', 'shippers', 'custbranch.defaultshipvia=shippers.shipper_id'),
('shippers', 'custbranch', 'shippers.shipper_id=custbranch.defaultshipvia'),
('debtorsmaster', 'holdreasons', 'debtorsmaster.holdreason=holdreasons.reasoncode'),
('holdreasons', 'debtorsmaster', 'holdreasons.reasoncode=debtorsmaster.holdreason'),
('debtorsmaster', 'currencies', 'debtorsmaster.currcode=currencies.currabrev'),
('currencies', 'debtorsmaster', 'currencies.currabrev=debtorsmaster.currcode'),
('debtorsmaster', 'paymentterms', 'debtorsmaster.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'debtorsmaster', 'paymentterms.termsindicator=debtorsmaster.paymentterms'),
('debtorsmaster', 'salestypes', 'debtorsmaster.salestype=salestypes.typeabbrev'),
('salestypes', 'debtorsmaster', 'salestypes.typeabbrev=debtorsmaster.salestype'),
('debtortrans', 'custbranch', 'debtortrans.debtorno=custbranch.debtorno'),
('custbranch', 'debtortrans', 'custbranch.debtorno=debtortrans.debtorno'),
('debtortrans', 'systypes', 'debtortrans.type=systypes.typeid'),
('systypes', 'debtortrans', 'systypes.typeid=debtortrans.type'),
('debtortrans', 'periods', 'debtortrans.prd=periods.periodno'),
('periods', 'debtortrans', 'periods.periodno=debtortrans.prd'),
('debtortranstaxes', 'taxauthorities', 'debtortranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'debtortranstaxes', 'taxauthorities.taxid=debtortranstaxes.taxauthid'),
('debtortranstaxes', 'debtortrans', 'debtortranstaxes.debtortransid=debtortrans.id'),
('debtortrans', 'debtortranstaxes', 'debtortrans.id=debtortranstaxes.debtortransid'),
('discountmatrix', 'salestypes', 'discountmatrix.salestype=salestypes.typeabbrev'),
('salestypes', 'discountmatrix', 'salestypes.typeabbrev=discountmatrix.salestype'),
('freightcosts', 'locations', 'freightcosts.locationfrom=locations.loccode'),
('locations', 'freightcosts', 'locations.loccode=freightcosts.locationfrom'),
('freightcosts', 'shippers', 'freightcosts.shipperid=shippers.shipper_id'),
('shippers', 'freightcosts', 'shippers.shipper_id=freightcosts.shipperid'),
('gltrans', 'chartmaster', 'gltrans.account=chartmaster.accountcode'),
('chartmaster', 'gltrans', 'chartmaster.accountcode=gltrans.account'),
('gltrans', 'systypes', 'gltrans.type=systypes.typeid'),
('systypes', 'gltrans', 'systypes.typeid=gltrans.type'),
('gltrans', 'periods', 'gltrans.periodno=periods.periodno'),
('periods', 'gltrans', 'periods.periodno=gltrans.periodno'),
('grns', 'suppliers', 'grns.supplierid=suppliers.supplierid'),
('suppliers', 'grns', 'suppliers.supplierid=grns.supplierid'),
('grns', 'purchorderdetails', 'grns.podetailitem=purchorderdetails.podetailitem'),
('purchorderdetails', 'grns', 'purchorderdetails.podetailitem=grns.podetailitem'),
('locations', 'taxprovinces', 'locations.taxprovinceid=taxprovinces.taxprovinceid'),
('taxprovinces', 'locations', 'taxprovinces.taxprovinceid=locations.taxprovinceid'),
('locstock', 'locations', 'locstock.loccode=locations.loccode'),
('locations', 'locstock', 'locations.loccode=locstock.loccode'),
('locstock', 'stockmaster', 'locstock.stockid=stockmaster.stockid'),
('stockmaster', 'locstock', 'stockmaster.stockid=locstock.stockid'),
('loctransfers', 'locations', 'loctransfers.shiploc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.shiploc'),
('loctransfers', 'locations', 'loctransfers.recloc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.recloc'),
('loctransfers', 'stockmaster', 'loctransfers.stockid=stockmaster.stockid'),
('stockmaster', 'loctransfers', 'stockmaster.stockid=loctransfers.stockid'),
('orderdeliverydifferencesl', 'stockmaster', 'orderdeliverydifferenceslog.stockid=stockmaster.stockid'),
('stockmaster', 'orderdeliverydifferencesl', 'stockmaster.stockid=orderdeliverydifferenceslog.stockid'),
('orderdeliverydifferencesl', 'custbranch', 'orderdeliverydifferenceslog.debtorno=custbranch.debtorno'),
('custbranch', 'orderdeliverydifferencesl', 'custbranch.debtorno=orderdeliverydifferenceslog.debtorno'),
('orderdeliverydifferencesl', 'salesorders', 'orderdeliverydifferenceslog.branchcode=salesorders.orderno'),
('salesorders', 'orderdeliverydifferencesl', 'salesorders.orderno=orderdeliverydifferenceslog.branchcode'),
('prices', 'stockmaster', 'prices.stockid=stockmaster.stockid'),
('stockmaster', 'prices', 'stockmaster.stockid=prices.stockid'),
('prices', 'currencies', 'prices.currabrev=currencies.currabrev'),
('currencies', 'prices', 'currencies.currabrev=prices.currabrev'),
('prices', 'salestypes', 'prices.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'prices', 'salestypes.typeabbrev=prices.typeabbrev'),
('purchdata', 'stockmaster', 'purchdata.stockid=stockmaster.stockid'),
('stockmaster', 'purchdata', 'stockmaster.stockid=purchdata.stockid'),
('purchdata', 'suppliers', 'purchdata.supplierno=suppliers.supplierid'),
('suppliers', 'purchdata', 'suppliers.supplierid=purchdata.supplierno'),
('purchorderdetails', 'purchorders', 'purchorderdetails.orderno=purchorders.orderno'),
('purchorders', 'purchorderdetails', 'purchorders.orderno=purchorderdetails.orderno'),
('purchorders', 'suppliers', 'purchorders.supplierno=suppliers.supplierid'),
('suppliers', 'purchorders', 'suppliers.supplierid=purchorders.supplierno'),
('purchorders', 'locations', 'purchorders.intostocklocation=locations.loccode'),
('locations', 'purchorders', 'locations.loccode=purchorders.intostocklocation'),
('recurringsalesorders', 'custbranch', 'recurringsalesorders.branchcode=custbranch.branchcode'),
('custbranch', 'recurringsalesorders', 'custbranch.branchcode=recurringsalesorders.branchcode'),
('recurrsalesorderdetails', 'recurringsalesorders', 'recurrsalesorderdetails.recurrorderno=recurringsalesorders.recurrorderno'),
('recurringsalesorders', 'recurrsalesorderdetails', 'recurringsalesorders.recurrorderno=recurrsalesorderdetails.recurrorderno'),
('recurrsalesorderdetails', 'stockmaster', 'recurrsalesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'recurrsalesorderdetails', 'stockmaster.stockid=recurrsalesorderdetails.stkcode'),
('reportcolumns', 'reportheaders', 'reportcolumns.reportid=reportheaders.reportid'),
('reportheaders', 'reportcolumns', 'reportheaders.reportid=reportcolumns.reportid'),
('salesanalysis', 'periods', 'salesanalysis.periodno=periods.periodno'),
('periods', 'salesanalysis', 'periods.periodno=salesanalysis.periodno'),
('salescatprod', 'stockmaster', 'salescatprod.stockid=stockmaster.stockid'),
('stockmaster', 'salescatprod', 'stockmaster.stockid=salescatprod.stockid'),
('salescatprod', 'salescat', 'salescatprod.salescatid=salescat.salescatid'),
('salescat', 'salescatprod', 'salescat.salescatid=salescatprod.salescatid'),
('salesorderdetails', 'salesorders', 'salesorderdetails.orderno=salesorders.orderno'),
('salesorders', 'salesorderdetails', 'salesorders.orderno=salesorderdetails.orderno'),
('salesorderdetails', 'stockmaster', 'salesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'salesorderdetails', 'stockmaster.stockid=salesorderdetails.stkcode'),
('salesorders', 'custbranch', 'salesorders.branchcode=custbranch.branchcode'),
('custbranch', 'salesorders', 'custbranch.branchcode=salesorders.branchcode'),
('salesorders', 'shippers', 'salesorders.debtorno=shippers.shipper_id'),
('shippers', 'salesorders', 'shippers.shipper_id=salesorders.debtorno'),
('salesorders', 'locations', 'salesorders.fromstkloc=locations.loccode'),
('locations', 'salesorders', 'locations.loccode=salesorders.fromstkloc'),
('securitygroups', 'securityroles', 'securitygroups.secroleid=securityroles.secroleid'),
('securityroles', 'securitygroups', 'securityroles.secroleid=securitygroups.secroleid'),
('securitygroups', 'securitytokens', 'securitygroups.tokenid=securitytokens.tokenid'),
('securitytokens', 'securitygroups', 'securitytokens.tokenid=securitygroups.tokenid'),
('shipmentcharges', 'shipments', 'shipmentcharges.shiptref=shipments.shiptref'),
('shipments', 'shipmentcharges', 'shipments.shiptref=shipmentcharges.shiptref'),
('shipmentcharges', 'systypes', 'shipmentcharges.transtype=systypes.typeid'),
('systypes', 'shipmentcharges', 'systypes.typeid=shipmentcharges.transtype'),
('shipments', 'suppliers', 'shipments.supplierid=suppliers.supplierid'),
('suppliers', 'shipments', 'suppliers.supplierid=shipments.supplierid'),
('stockcheckfreeze', 'stockmaster', 'stockcheckfreeze.stockid=stockmaster.stockid'),
('stockmaster', 'stockcheckfreeze', 'stockmaster.stockid=stockcheckfreeze.stockid'),
('stockcheckfreeze', 'locations', 'stockcheckfreeze.loccode=locations.loccode'),
('locations', 'stockcheckfreeze', 'locations.loccode=stockcheckfreeze.loccode'),
('stockcounts', 'stockmaster', 'stockcounts.stockid=stockmaster.stockid'),
('stockmaster', 'stockcounts', 'stockmaster.stockid=stockcounts.stockid'),
('stockcounts', 'locations', 'stockcounts.loccode=locations.loccode'),
('locations', 'stockcounts', 'locations.loccode=stockcounts.loccode'),
('stockmaster', 'stockcategory', 'stockmaster.categoryid=stockcategory.categoryid'),
('stockcategory', 'stockmaster', 'stockcategory.categoryid=stockmaster.categoryid'),
('stockmaster', 'taxcategories', 'stockmaster.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'stockmaster', 'taxcategories.taxcatid=stockmaster.taxcatid'),
('stockmoves', 'stockmaster', 'stockmoves.stockid=stockmaster.stockid'),
('stockmaster', 'stockmoves', 'stockmaster.stockid=stockmoves.stockid'),
('stockmoves', 'systypes', 'stockmoves.type=systypes.typeid'),
('systypes', 'stockmoves', 'systypes.typeid=stockmoves.type'),
('stockmoves', 'locations', 'stockmoves.loccode=locations.loccode'),
('locations', 'stockmoves', 'locations.loccode=stockmoves.loccode'),
('stockmoves', 'periods', 'stockmoves.prd=periods.periodno'),
('periods', 'stockmoves', 'periods.periodno=stockmoves.prd'),
('stockmovestaxes', 'taxauthorities', 'stockmovestaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'stockmovestaxes', 'taxauthorities.taxid=stockmovestaxes.taxauthid'),
('stockserialitems', 'stockmaster', 'stockserialitems.stockid=stockmaster.stockid'),
('stockmaster', 'stockserialitems', 'stockmaster.stockid=stockserialitems.stockid'),
('stockserialitems', 'locations', 'stockserialitems.loccode=locations.loccode'),
('locations', 'stockserialitems', 'locations.loccode=stockserialitems.loccode'),
('stockserialmoves', 'stockmoves', 'stockserialmoves.stockmoveno=stockmoves.stkmoveno'),
('stockmoves', 'stockserialmoves', 'stockmoves.stkmoveno=stockserialmoves.stockmoveno'),
('stockserialmoves', 'stockserialitems', 'stockserialmoves.stockid=stockserialitems.stockid'),
('stockserialitems', 'stockserialmoves', 'stockserialitems.stockid=stockserialmoves.stockid'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocfrom=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocfrom'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocto=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocto'),
('suppliercontacts', 'suppliers', 'suppliercontacts.supplierid=suppliers.supplierid'),
('suppliers', 'suppliercontacts', 'suppliers.supplierid=suppliercontacts.supplierid'),
('suppliers', 'currencies', 'suppliers.currcode=currencies.currabrev'),
('currencies', 'suppliers', 'currencies.currabrev=suppliers.currcode'),
('suppliers', 'paymentterms', 'suppliers.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'suppliers', 'paymentterms.termsindicator=suppliers.paymentterms'),
('suppliers', 'taxgroups', 'suppliers.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'suppliers', 'taxgroups.taxgroupid=suppliers.taxgroupid'),
('supptrans', 'systypes', 'supptrans.type=systypes.typeid'),
('systypes', 'supptrans', 'systypes.typeid=supptrans.type'),
('supptrans', 'suppliers', 'supptrans.supplierno=suppliers.supplierid'),
('suppliers', 'supptrans', 'suppliers.supplierid=supptrans.supplierno'),
('supptranstaxes', 'taxauthorities', 'supptranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'supptranstaxes', 'taxauthorities.taxid=supptranstaxes.taxauthid'),
('supptranstaxes', 'supptrans', 'supptranstaxes.supptransid=supptrans.id'),
('supptrans', 'supptranstaxes', 'supptrans.id=supptranstaxes.supptransid'),
('taxauthorities', 'chartmaster', 'taxauthorities.taxglcode=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.taxglcode'),
('taxauthorities', 'chartmaster', 'taxauthorities.purchtaxglaccount=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.purchtaxglaccount'),
('taxauthrates', 'taxauthorities', 'taxauthrates.taxauthority=taxauthorities.taxid'),
('taxauthorities', 'taxauthrates', 'taxauthorities.taxid=taxauthrates.taxauthority'),
('taxauthrates', 'taxcategories', 'taxauthrates.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'taxauthrates', 'taxcategories.taxcatid=taxauthrates.taxcatid'),
('taxauthrates', 'taxprovinces', 'taxauthrates.dispatchtaxprovince=taxprovinces.taxprovinceid'),
('taxprovinces', 'taxauthrates', 'taxprovinces.taxprovinceid=taxauthrates.dispatchtaxprovince'),
('taxgrouptaxes', 'taxgroups', 'taxgrouptaxes.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'taxgrouptaxes', 'taxgroups.taxgroupid=taxgrouptaxes.taxgroupid'),
('taxgrouptaxes', 'taxauthorities', 'taxgrouptaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'taxgrouptaxes', 'taxauthorities.taxid=taxgrouptaxes.taxauthid'),
('workcentres', 'locations', 'workcentres.location=locations.loccode'),
('locations', 'workcentres', 'locations.loccode=workcentres.location'),
('worksorders', 'locations', 'worksorders.loccode=locations.loccode'),
('locations', 'worksorders', 'locations.loccode=worksorders.loccode'),
('worksorders', 'stockmaster', 'worksorders.stockid=stockmaster.stockid'),
('stockmaster', 'worksorders', 'stockmaster.stockid=worksorders.stockid'),
('www_users', 'locations', 'www_users.defaultlocation=locations.loccode'),
('locations', 'www_users', 'locations.loccode=www_users.defaultlocation'),
('accountgroups', 'accountsection', 'accountgroups.sectioninaccounts=accountsection.sectionid'),
('accountsection', 'accountgroups', 'accountsection.sectionid=accountgroups.sectioninaccounts'),
('bankaccounts', 'chartmaster', 'bankaccounts.accountcode=chartmaster.accountcode'),
('chartmaster', 'bankaccounts', 'chartmaster.accountcode=bankaccounts.accountcode'),
('banktrans', 'systypes', 'banktrans.type=systypes.typeid'),
('systypes', 'banktrans', 'systypes.typeid=banktrans.type'),
('banktrans', 'bankaccounts', 'banktrans.bankact=bankaccounts.accountcode'),
('bankaccounts', 'banktrans', 'bankaccounts.accountcode=banktrans.bankact'),
('bom', 'stockmaster', 'bom.parent=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.parent'),
('bom', 'stockmaster', 'bom.component=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.component'),
('bom', 'workcentres', 'bom.workcentreadded=workcentres.code'),
('workcentres', 'bom', 'workcentres.code=bom.workcentreadded'),
('bom', 'locations', 'bom.loccode=locations.loccode'),
('locations', 'bom', 'locations.loccode=bom.loccode'),
('buckets', 'workcentres', 'buckets.workcentre=workcentres.code'),
('workcentres', 'buckets', 'workcentres.code=buckets.workcentre'),
('chartdetails', 'chartmaster', 'chartdetails.accountcode=chartmaster.accountcode'),
('chartmaster', 'chartdetails', 'chartmaster.accountcode=chartdetails.accountcode'),
('chartdetails', 'periods', 'chartdetails.period=periods.periodno'),
('periods', 'chartdetails', 'periods.periodno=chartdetails.period'),
('chartmaster', 'accountgroups', 'chartmaster.group_=accountgroups.groupname'),
('accountgroups', 'chartmaster', 'accountgroups.groupname=chartmaster.group_'),
('contractbom', 'workcentres', 'contractbom.workcentreadded=workcentres.code'),
('workcentres', 'contractbom', 'workcentres.code=contractbom.workcentreadded'),
('contractbom', 'locations', 'contractbom.loccode=locations.loccode'),
('locations', 'contractbom', 'locations.loccode=contractbom.loccode'),
('contractbom', 'stockmaster', 'contractbom.component=stockmaster.stockid'),
('stockmaster', 'contractbom', 'stockmaster.stockid=contractbom.component'),
('contractreqts', 'contracts', 'contractreqts.contract=contracts.contractref'),
('contracts', 'contractreqts', 'contracts.contractref=contractreqts.contract'),
('contracts', 'custbranch', 'contracts.debtorno=custbranch.debtorno'),
('custbranch', 'contracts', 'custbranch.debtorno=contracts.debtorno'),
('contracts', 'stockcategory', 'contracts.branchcode=stockcategory.categoryid'),
('stockcategory', 'contracts', 'stockcategory.categoryid=contracts.branchcode'),
('contracts', 'salestypes', 'contracts.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'contracts', 'salestypes.typeabbrev=contracts.typeabbrev'),
('custallocns', 'debtortrans', 'custallocns.transid_allocfrom=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocfrom'),
('custallocns', 'debtortrans', 'custallocns.transid_allocto=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocto'),
('custbranch', 'debtorsmaster', 'custbranch.debtorno=debtorsmaster.debtorno'),
('debtorsmaster', 'custbranch', 'debtorsmaster.debtorno=custbranch.debtorno'),
('custbranch', 'areas', 'custbranch.area=areas.areacode'),
('areas', 'custbranch', 'areas.areacode=custbranch.area'),
('custbranch', 'salesman', 'custbranch.salesman=salesman.salesmancode'),
('salesman', 'custbranch', 'salesman.salesmancode=custbranch.salesman'),
('custbranch', 'locations', 'custbranch.defaultlocation=locations.loccode'),
('locations', 'custbranch', 'locations.loccode=custbranch.defaultlocation'),
('custbranch', 'shippers', 'custbranch.defaultshipvia=shippers.shipper_id'),
('shippers', 'custbranch', 'shippers.shipper_id=custbranch.defaultshipvia'),
('debtorsmaster', 'holdreasons', 'debtorsmaster.holdreason=holdreasons.reasoncode'),
('holdreasons', 'debtorsmaster', 'holdreasons.reasoncode=debtorsmaster.holdreason'),
('debtorsmaster', 'currencies', 'debtorsmaster.currcode=currencies.currabrev'),
('currencies', 'debtorsmaster', 'currencies.currabrev=debtorsmaster.currcode'),
('debtorsmaster', 'paymentterms', 'debtorsmaster.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'debtorsmaster', 'paymentterms.termsindicator=debtorsmaster.paymentterms'),
('debtorsmaster', 'salestypes', 'debtorsmaster.salestype=salestypes.typeabbrev'),
('salestypes', 'debtorsmaster', 'salestypes.typeabbrev=debtorsmaster.salestype'),
('debtortrans', 'custbranch', 'debtortrans.debtorno=custbranch.debtorno'),
('custbranch', 'debtortrans', 'custbranch.debtorno=debtortrans.debtorno'),
('debtortrans', 'systypes', 'debtortrans.type=systypes.typeid'),
('systypes', 'debtortrans', 'systypes.typeid=debtortrans.type'),
('debtortrans', 'periods', 'debtortrans.prd=periods.periodno'),
('periods', 'debtortrans', 'periods.periodno=debtortrans.prd'),
('debtortranstaxes', 'taxauthorities', 'debtortranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'debtortranstaxes', 'taxauthorities.taxid=debtortranstaxes.taxauthid'),
('debtortranstaxes', 'debtortrans', 'debtortranstaxes.debtortransid=debtortrans.id'),
('debtortrans', 'debtortranstaxes', 'debtortrans.id=debtortranstaxes.debtortransid'),
('discountmatrix', 'salestypes', 'discountmatrix.salestype=salestypes.typeabbrev'),
('salestypes', 'discountmatrix', 'salestypes.typeabbrev=discountmatrix.salestype'),
('freightcosts', 'locations', 'freightcosts.locationfrom=locations.loccode'),
('locations', 'freightcosts', 'locations.loccode=freightcosts.locationfrom'),
('freightcosts', 'shippers', 'freightcosts.shipperid=shippers.shipper_id'),
('shippers', 'freightcosts', 'shippers.shipper_id=freightcosts.shipperid'),
('gltrans', 'chartmaster', 'gltrans.account=chartmaster.accountcode'),
('chartmaster', 'gltrans', 'chartmaster.accountcode=gltrans.account'),
('gltrans', 'systypes', 'gltrans.type=systypes.typeid'),
('systypes', 'gltrans', 'systypes.typeid=gltrans.type'),
('gltrans', 'periods', 'gltrans.periodno=periods.periodno'),
('periods', 'gltrans', 'periods.periodno=gltrans.periodno'),
('grns', 'suppliers', 'grns.supplierid=suppliers.supplierid'),
('suppliers', 'grns', 'suppliers.supplierid=grns.supplierid'),
('grns', 'purchorderdetails', 'grns.podetailitem=purchorderdetails.podetailitem'),
('purchorderdetails', 'grns', 'purchorderdetails.podetailitem=grns.podetailitem'),
('locations', 'taxprovinces', 'locations.taxprovinceid=taxprovinces.taxprovinceid'),
('taxprovinces', 'locations', 'taxprovinces.taxprovinceid=locations.taxprovinceid'),
('locstock', 'locations', 'locstock.loccode=locations.loccode'),
('locations', 'locstock', 'locations.loccode=locstock.loccode'),
('locstock', 'stockmaster', 'locstock.stockid=stockmaster.stockid'),
('stockmaster', 'locstock', 'stockmaster.stockid=locstock.stockid'),
('loctransfers', 'locations', 'loctransfers.shiploc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.shiploc'),
('loctransfers', 'locations', 'loctransfers.recloc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.recloc'),
('loctransfers', 'stockmaster', 'loctransfers.stockid=stockmaster.stockid'),
('stockmaster', 'loctransfers', 'stockmaster.stockid=loctransfers.stockid'),
('orderdeliverydifferencesl', 'stockmaster', 'orderdeliverydifferenceslog.stockid=stockmaster.stockid'),
('stockmaster', 'orderdeliverydifferencesl', 'stockmaster.stockid=orderdeliverydifferenceslog.stockid'),
('orderdeliverydifferencesl', 'custbranch', 'orderdeliverydifferenceslog.debtorno=custbranch.debtorno'),
('custbranch', 'orderdeliverydifferencesl', 'custbranch.debtorno=orderdeliverydifferenceslog.debtorno'),
('orderdeliverydifferencesl', 'salesorders', 'orderdeliverydifferenceslog.branchcode=salesorders.orderno'),
('salesorders', 'orderdeliverydifferencesl', 'salesorders.orderno=orderdeliverydifferenceslog.branchcode'),
('prices', 'stockmaster', 'prices.stockid=stockmaster.stockid'),
('stockmaster', 'prices', 'stockmaster.stockid=prices.stockid'),
('prices', 'currencies', 'prices.currabrev=currencies.currabrev'),
('currencies', 'prices', 'currencies.currabrev=prices.currabrev'),
('prices', 'salestypes', 'prices.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'prices', 'salestypes.typeabbrev=prices.typeabbrev'),
('purchdata', 'stockmaster', 'purchdata.stockid=stockmaster.stockid'),
('stockmaster', 'purchdata', 'stockmaster.stockid=purchdata.stockid'),
('purchdata', 'suppliers', 'purchdata.supplierno=suppliers.supplierid'),
('suppliers', 'purchdata', 'suppliers.supplierid=purchdata.supplierno'),
('purchorderdetails', 'purchorders', 'purchorderdetails.orderno=purchorders.orderno'),
('purchorders', 'purchorderdetails', 'purchorders.orderno=purchorderdetails.orderno'),
('purchorders', 'suppliers', 'purchorders.supplierno=suppliers.supplierid'),
('suppliers', 'purchorders', 'suppliers.supplierid=purchorders.supplierno'),
('purchorders', 'locations', 'purchorders.intostocklocation=locations.loccode'),
('locations', 'purchorders', 'locations.loccode=purchorders.intostocklocation'),
('recurringsalesorders', 'custbranch', 'recurringsalesorders.branchcode=custbranch.branchcode'),
('custbranch', 'recurringsalesorders', 'custbranch.branchcode=recurringsalesorders.branchcode'),
('recurrsalesorderdetails', 'recurringsalesorders', 'recurrsalesorderdetails.recurrorderno=recurringsalesorders.recurrorderno'),
('recurringsalesorders', 'recurrsalesorderdetails', 'recurringsalesorders.recurrorderno=recurrsalesorderdetails.recurrorderno'),
('recurrsalesorderdetails', 'stockmaster', 'recurrsalesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'recurrsalesorderdetails', 'stockmaster.stockid=recurrsalesorderdetails.stkcode'),
('reportcolumns', 'reportheaders', 'reportcolumns.reportid=reportheaders.reportid'),
('reportheaders', 'reportcolumns', 'reportheaders.reportid=reportcolumns.reportid'),
('salesanalysis', 'periods', 'salesanalysis.periodno=periods.periodno'),
('periods', 'salesanalysis', 'periods.periodno=salesanalysis.periodno'),
('salescatprod', 'stockmaster', 'salescatprod.stockid=stockmaster.stockid'),
('stockmaster', 'salescatprod', 'stockmaster.stockid=salescatprod.stockid'),
('salescatprod', 'salescat', 'salescatprod.salescatid=salescat.salescatid'),
('salescat', 'salescatprod', 'salescat.salescatid=salescatprod.salescatid'),
('salesorderdetails', 'salesorders', 'salesorderdetails.orderno=salesorders.orderno'),
('salesorders', 'salesorderdetails', 'salesorders.orderno=salesorderdetails.orderno'),
('salesorderdetails', 'stockmaster', 'salesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'salesorderdetails', 'stockmaster.stockid=salesorderdetails.stkcode'),
('salesorders', 'custbranch', 'salesorders.branchcode=custbranch.branchcode'),
('custbranch', 'salesorders', 'custbranch.branchcode=salesorders.branchcode'),
('salesorders', 'shippers', 'salesorders.debtorno=shippers.shipper_id'),
('shippers', 'salesorders', 'shippers.shipper_id=salesorders.debtorno'),
('salesorders', 'locations', 'salesorders.fromstkloc=locations.loccode'),
('locations', 'salesorders', 'locations.loccode=salesorders.fromstkloc'),
('securitygroups', 'securityroles', 'securitygroups.secroleid=securityroles.secroleid'),
('securityroles', 'securitygroups', 'securityroles.secroleid=securitygroups.secroleid'),
('securitygroups', 'securitytokens', 'securitygroups.tokenid=securitytokens.tokenid'),
('securitytokens', 'securitygroups', 'securitytokens.tokenid=securitygroups.tokenid'),
('shipmentcharges', 'shipments', 'shipmentcharges.shiptref=shipments.shiptref'),
('shipments', 'shipmentcharges', 'shipments.shiptref=shipmentcharges.shiptref'),
('shipmentcharges', 'systypes', 'shipmentcharges.transtype=systypes.typeid'),
('systypes', 'shipmentcharges', 'systypes.typeid=shipmentcharges.transtype'),
('shipments', 'suppliers', 'shipments.supplierid=suppliers.supplierid'),
('suppliers', 'shipments', 'suppliers.supplierid=shipments.supplierid'),
('stockcheckfreeze', 'stockmaster', 'stockcheckfreeze.stockid=stockmaster.stockid'),
('stockmaster', 'stockcheckfreeze', 'stockmaster.stockid=stockcheckfreeze.stockid'),
('stockcheckfreeze', 'locations', 'stockcheckfreeze.loccode=locations.loccode'),
('locations', 'stockcheckfreeze', 'locations.loccode=stockcheckfreeze.loccode'),
('stockcounts', 'stockmaster', 'stockcounts.stockid=stockmaster.stockid'),
('stockmaster', 'stockcounts', 'stockmaster.stockid=stockcounts.stockid'),
('stockcounts', 'locations', 'stockcounts.loccode=locations.loccode'),
('locations', 'stockcounts', 'locations.loccode=stockcounts.loccode'),
('stockmaster', 'stockcategory', 'stockmaster.categoryid=stockcategory.categoryid'),
('stockcategory', 'stockmaster', 'stockcategory.categoryid=stockmaster.categoryid'),
('stockmaster', 'taxcategories', 'stockmaster.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'stockmaster', 'taxcategories.taxcatid=stockmaster.taxcatid'),
('stockmoves', 'stockmaster', 'stockmoves.stockid=stockmaster.stockid'),
('stockmaster', 'stockmoves', 'stockmaster.stockid=stockmoves.stockid'),
('stockmoves', 'systypes', 'stockmoves.type=systypes.typeid'),
('systypes', 'stockmoves', 'systypes.typeid=stockmoves.type'),
('stockmoves', 'locations', 'stockmoves.loccode=locations.loccode'),
('locations', 'stockmoves', 'locations.loccode=stockmoves.loccode'),
('stockmoves', 'periods', 'stockmoves.prd=periods.periodno'),
('periods', 'stockmoves', 'periods.periodno=stockmoves.prd'),
('stockmovestaxes', 'taxauthorities', 'stockmovestaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'stockmovestaxes', 'taxauthorities.taxid=stockmovestaxes.taxauthid'),
('stockserialitems', 'stockmaster', 'stockserialitems.stockid=stockmaster.stockid'),
('stockmaster', 'stockserialitems', 'stockmaster.stockid=stockserialitems.stockid');
INSERT INTO `reportlinks` (`table1`, `table2`, `equation`) VALUES
('stockserialitems', 'locations', 'stockserialitems.loccode=locations.loccode'),
('locations', 'stockserialitems', 'locations.loccode=stockserialitems.loccode'),
('stockserialmoves', 'stockmoves', 'stockserialmoves.stockmoveno=stockmoves.stkmoveno'),
('stockmoves', 'stockserialmoves', 'stockmoves.stkmoveno=stockserialmoves.stockmoveno'),
('stockserialmoves', 'stockserialitems', 'stockserialmoves.stockid=stockserialitems.stockid'),
('stockserialitems', 'stockserialmoves', 'stockserialitems.stockid=stockserialmoves.stockid'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocfrom=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocfrom'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocto=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocto'),
('suppliercontacts', 'suppliers', 'suppliercontacts.supplierid=suppliers.supplierid'),
('suppliers', 'suppliercontacts', 'suppliers.supplierid=suppliercontacts.supplierid'),
('suppliers', 'currencies', 'suppliers.currcode=currencies.currabrev'),
('currencies', 'suppliers', 'currencies.currabrev=suppliers.currcode'),
('suppliers', 'paymentterms', 'suppliers.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'suppliers', 'paymentterms.termsindicator=suppliers.paymentterms'),
('suppliers', 'taxgroups', 'suppliers.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'suppliers', 'taxgroups.taxgroupid=suppliers.taxgroupid'),
('supptrans', 'systypes', 'supptrans.type=systypes.typeid'),
('systypes', 'supptrans', 'systypes.typeid=supptrans.type'),
('supptrans', 'suppliers', 'supptrans.supplierno=suppliers.supplierid'),
('suppliers', 'supptrans', 'suppliers.supplierid=supptrans.supplierno'),
('supptranstaxes', 'taxauthorities', 'supptranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'supptranstaxes', 'taxauthorities.taxid=supptranstaxes.taxauthid'),
('supptranstaxes', 'supptrans', 'supptranstaxes.supptransid=supptrans.id'),
('supptrans', 'supptranstaxes', 'supptrans.id=supptranstaxes.supptransid'),
('taxauthorities', 'chartmaster', 'taxauthorities.taxglcode=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.taxglcode'),
('taxauthorities', 'chartmaster', 'taxauthorities.purchtaxglaccount=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.purchtaxglaccount'),
('taxauthrates', 'taxauthorities', 'taxauthrates.taxauthority=taxauthorities.taxid'),
('taxauthorities', 'taxauthrates', 'taxauthorities.taxid=taxauthrates.taxauthority'),
('taxauthrates', 'taxcategories', 'taxauthrates.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'taxauthrates', 'taxcategories.taxcatid=taxauthrates.taxcatid'),
('taxauthrates', 'taxprovinces', 'taxauthrates.dispatchtaxprovince=taxprovinces.taxprovinceid'),
('taxprovinces', 'taxauthrates', 'taxprovinces.taxprovinceid=taxauthrates.dispatchtaxprovince'),
('taxgrouptaxes', 'taxgroups', 'taxgrouptaxes.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'taxgrouptaxes', 'taxgroups.taxgroupid=taxgrouptaxes.taxgroupid'),
('taxgrouptaxes', 'taxauthorities', 'taxgrouptaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'taxgrouptaxes', 'taxauthorities.taxid=taxgrouptaxes.taxauthid'),
('workcentres', 'locations', 'workcentres.location=locations.loccode'),
('locations', 'workcentres', 'locations.loccode=workcentres.location'),
('worksorders', 'locations', 'worksorders.loccode=locations.loccode'),
('locations', 'worksorders', 'locations.loccode=worksorders.loccode'),
('worksorders', 'stockmaster', 'worksorders.stockid=stockmaster.stockid'),
('stockmaster', 'worksorders', 'stockmaster.stockid=worksorders.stockid'),
('www_users', 'locations', 'www_users.defaultlocation=locations.loccode'),
('locations', 'www_users', 'locations.loccode=www_users.defaultlocation'),
('accountgroups', 'accountsection', 'accountgroups.sectioninaccounts=accountsection.sectionid'),
('accountsection', 'accountgroups', 'accountsection.sectionid=accountgroups.sectioninaccounts'),
('bankaccounts', 'chartmaster', 'bankaccounts.accountcode=chartmaster.accountcode'),
('chartmaster', 'bankaccounts', 'chartmaster.accountcode=bankaccounts.accountcode'),
('banktrans', 'systypes', 'banktrans.type=systypes.typeid'),
('systypes', 'banktrans', 'systypes.typeid=banktrans.type'),
('banktrans', 'bankaccounts', 'banktrans.bankact=bankaccounts.accountcode'),
('bankaccounts', 'banktrans', 'bankaccounts.accountcode=banktrans.bankact'),
('bom', 'stockmaster', 'bom.parent=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.parent'),
('bom', 'stockmaster', 'bom.component=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.component'),
('bom', 'workcentres', 'bom.workcentreadded=workcentres.code'),
('workcentres', 'bom', 'workcentres.code=bom.workcentreadded'),
('bom', 'locations', 'bom.loccode=locations.loccode'),
('locations', 'bom', 'locations.loccode=bom.loccode'),
('buckets', 'workcentres', 'buckets.workcentre=workcentres.code'),
('workcentres', 'buckets', 'workcentres.code=buckets.workcentre'),
('chartdetails', 'chartmaster', 'chartdetails.accountcode=chartmaster.accountcode'),
('chartmaster', 'chartdetails', 'chartmaster.accountcode=chartdetails.accountcode'),
('chartdetails', 'periods', 'chartdetails.period=periods.periodno'),
('periods', 'chartdetails', 'periods.periodno=chartdetails.period'),
('chartmaster', 'accountgroups', 'chartmaster.group_=accountgroups.groupname'),
('accountgroups', 'chartmaster', 'accountgroups.groupname=chartmaster.group_'),
('contractbom', 'workcentres', 'contractbom.workcentreadded=workcentres.code'),
('workcentres', 'contractbom', 'workcentres.code=contractbom.workcentreadded'),
('contractbom', 'locations', 'contractbom.loccode=locations.loccode'),
('locations', 'contractbom', 'locations.loccode=contractbom.loccode'),
('contractbom', 'stockmaster', 'contractbom.component=stockmaster.stockid'),
('stockmaster', 'contractbom', 'stockmaster.stockid=contractbom.component'),
('contractreqts', 'contracts', 'contractreqts.contract=contracts.contractref'),
('contracts', 'contractreqts', 'contracts.contractref=contractreqts.contract'),
('contracts', 'custbranch', 'contracts.debtorno=custbranch.debtorno'),
('custbranch', 'contracts', 'custbranch.debtorno=contracts.debtorno'),
('contracts', 'stockcategory', 'contracts.branchcode=stockcategory.categoryid'),
('stockcategory', 'contracts', 'stockcategory.categoryid=contracts.branchcode'),
('contracts', 'salestypes', 'contracts.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'contracts', 'salestypes.typeabbrev=contracts.typeabbrev'),
('custallocns', 'debtortrans', 'custallocns.transid_allocfrom=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocfrom'),
('custallocns', 'debtortrans', 'custallocns.transid_allocto=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocto'),
('custbranch', 'debtorsmaster', 'custbranch.debtorno=debtorsmaster.debtorno'),
('debtorsmaster', 'custbranch', 'debtorsmaster.debtorno=custbranch.debtorno'),
('custbranch', 'areas', 'custbranch.area=areas.areacode'),
('areas', 'custbranch', 'areas.areacode=custbranch.area'),
('custbranch', 'salesman', 'custbranch.salesman=salesman.salesmancode'),
('salesman', 'custbranch', 'salesman.salesmancode=custbranch.salesman'),
('custbranch', 'locations', 'custbranch.defaultlocation=locations.loccode'),
('locations', 'custbranch', 'locations.loccode=custbranch.defaultlocation'),
('custbranch', 'shippers', 'custbranch.defaultshipvia=shippers.shipper_id'),
('shippers', 'custbranch', 'shippers.shipper_id=custbranch.defaultshipvia'),
('debtorsmaster', 'holdreasons', 'debtorsmaster.holdreason=holdreasons.reasoncode'),
('holdreasons', 'debtorsmaster', 'holdreasons.reasoncode=debtorsmaster.holdreason'),
('debtorsmaster', 'currencies', 'debtorsmaster.currcode=currencies.currabrev'),
('currencies', 'debtorsmaster', 'currencies.currabrev=debtorsmaster.currcode'),
('debtorsmaster', 'paymentterms', 'debtorsmaster.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'debtorsmaster', 'paymentterms.termsindicator=debtorsmaster.paymentterms'),
('debtorsmaster', 'salestypes', 'debtorsmaster.salestype=salestypes.typeabbrev'),
('salestypes', 'debtorsmaster', 'salestypes.typeabbrev=debtorsmaster.salestype'),
('debtortrans', 'custbranch', 'debtortrans.debtorno=custbranch.debtorno'),
('custbranch', 'debtortrans', 'custbranch.debtorno=debtortrans.debtorno'),
('debtortrans', 'systypes', 'debtortrans.type=systypes.typeid'),
('systypes', 'debtortrans', 'systypes.typeid=debtortrans.type'),
('debtortrans', 'periods', 'debtortrans.prd=periods.periodno'),
('periods', 'debtortrans', 'periods.periodno=debtortrans.prd'),
('debtortranstaxes', 'taxauthorities', 'debtortranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'debtortranstaxes', 'taxauthorities.taxid=debtortranstaxes.taxauthid'),
('debtortranstaxes', 'debtortrans', 'debtortranstaxes.debtortransid=debtortrans.id'),
('debtortrans', 'debtortranstaxes', 'debtortrans.id=debtortranstaxes.debtortransid'),
('discountmatrix', 'salestypes', 'discountmatrix.salestype=salestypes.typeabbrev'),
('salestypes', 'discountmatrix', 'salestypes.typeabbrev=discountmatrix.salestype'),
('freightcosts', 'locations', 'freightcosts.locationfrom=locations.loccode'),
('locations', 'freightcosts', 'locations.loccode=freightcosts.locationfrom'),
('freightcosts', 'shippers', 'freightcosts.shipperid=shippers.shipper_id'),
('shippers', 'freightcosts', 'shippers.shipper_id=freightcosts.shipperid'),
('gltrans', 'chartmaster', 'gltrans.account=chartmaster.accountcode'),
('chartmaster', 'gltrans', 'chartmaster.accountcode=gltrans.account'),
('gltrans', 'systypes', 'gltrans.type=systypes.typeid'),
('systypes', 'gltrans', 'systypes.typeid=gltrans.type'),
('gltrans', 'periods', 'gltrans.periodno=periods.periodno'),
('periods', 'gltrans', 'periods.periodno=gltrans.periodno'),
('grns', 'suppliers', 'grns.supplierid=suppliers.supplierid'),
('suppliers', 'grns', 'suppliers.supplierid=grns.supplierid'),
('grns', 'purchorderdetails', 'grns.podetailitem=purchorderdetails.podetailitem'),
('purchorderdetails', 'grns', 'purchorderdetails.podetailitem=grns.podetailitem'),
('locations', 'taxprovinces', 'locations.taxprovinceid=taxprovinces.taxprovinceid'),
('taxprovinces', 'locations', 'taxprovinces.taxprovinceid=locations.taxprovinceid'),
('locstock', 'locations', 'locstock.loccode=locations.loccode'),
('locations', 'locstock', 'locations.loccode=locstock.loccode'),
('locstock', 'stockmaster', 'locstock.stockid=stockmaster.stockid'),
('stockmaster', 'locstock', 'stockmaster.stockid=locstock.stockid'),
('loctransfers', 'locations', 'loctransfers.shiploc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.shiploc'),
('loctransfers', 'locations', 'loctransfers.recloc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.recloc'),
('loctransfers', 'stockmaster', 'loctransfers.stockid=stockmaster.stockid'),
('stockmaster', 'loctransfers', 'stockmaster.stockid=loctransfers.stockid'),
('orderdeliverydifferencesl', 'stockmaster', 'orderdeliverydifferenceslog.stockid=stockmaster.stockid'),
('stockmaster', 'orderdeliverydifferencesl', 'stockmaster.stockid=orderdeliverydifferenceslog.stockid'),
('orderdeliverydifferencesl', 'custbranch', 'orderdeliverydifferenceslog.debtorno=custbranch.debtorno'),
('custbranch', 'orderdeliverydifferencesl', 'custbranch.debtorno=orderdeliverydifferenceslog.debtorno'),
('orderdeliverydifferencesl', 'salesorders', 'orderdeliverydifferenceslog.branchcode=salesorders.orderno'),
('salesorders', 'orderdeliverydifferencesl', 'salesorders.orderno=orderdeliverydifferenceslog.branchcode'),
('prices', 'stockmaster', 'prices.stockid=stockmaster.stockid'),
('stockmaster', 'prices', 'stockmaster.stockid=prices.stockid'),
('prices', 'currencies', 'prices.currabrev=currencies.currabrev'),
('currencies', 'prices', 'currencies.currabrev=prices.currabrev'),
('prices', 'salestypes', 'prices.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'prices', 'salestypes.typeabbrev=prices.typeabbrev'),
('purchdata', 'stockmaster', 'purchdata.stockid=stockmaster.stockid'),
('stockmaster', 'purchdata', 'stockmaster.stockid=purchdata.stockid'),
('purchdata', 'suppliers', 'purchdata.supplierno=suppliers.supplierid'),
('suppliers', 'purchdata', 'suppliers.supplierid=purchdata.supplierno'),
('purchorderdetails', 'purchorders', 'purchorderdetails.orderno=purchorders.orderno'),
('purchorders', 'purchorderdetails', 'purchorders.orderno=purchorderdetails.orderno'),
('purchorders', 'suppliers', 'purchorders.supplierno=suppliers.supplierid'),
('suppliers', 'purchorders', 'suppliers.supplierid=purchorders.supplierno'),
('purchorders', 'locations', 'purchorders.intostocklocation=locations.loccode'),
('locations', 'purchorders', 'locations.loccode=purchorders.intostocklocation'),
('recurringsalesorders', 'custbranch', 'recurringsalesorders.branchcode=custbranch.branchcode'),
('custbranch', 'recurringsalesorders', 'custbranch.branchcode=recurringsalesorders.branchcode'),
('recurrsalesorderdetails', 'recurringsalesorders', 'recurrsalesorderdetails.recurrorderno=recurringsalesorders.recurrorderno'),
('recurringsalesorders', 'recurrsalesorderdetails', 'recurringsalesorders.recurrorderno=recurrsalesorderdetails.recurrorderno'),
('recurrsalesorderdetails', 'stockmaster', 'recurrsalesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'recurrsalesorderdetails', 'stockmaster.stockid=recurrsalesorderdetails.stkcode'),
('reportcolumns', 'reportheaders', 'reportcolumns.reportid=reportheaders.reportid'),
('reportheaders', 'reportcolumns', 'reportheaders.reportid=reportcolumns.reportid'),
('salesanalysis', 'periods', 'salesanalysis.periodno=periods.periodno'),
('periods', 'salesanalysis', 'periods.periodno=salesanalysis.periodno'),
('salescatprod', 'stockmaster', 'salescatprod.stockid=stockmaster.stockid'),
('stockmaster', 'salescatprod', 'stockmaster.stockid=salescatprod.stockid'),
('salescatprod', 'salescat', 'salescatprod.salescatid=salescat.salescatid'),
('salescat', 'salescatprod', 'salescat.salescatid=salescatprod.salescatid'),
('salesorderdetails', 'salesorders', 'salesorderdetails.orderno=salesorders.orderno'),
('salesorders', 'salesorderdetails', 'salesorders.orderno=salesorderdetails.orderno'),
('salesorderdetails', 'stockmaster', 'salesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'salesorderdetails', 'stockmaster.stockid=salesorderdetails.stkcode'),
('salesorders', 'custbranch', 'salesorders.branchcode=custbranch.branchcode'),
('custbranch', 'salesorders', 'custbranch.branchcode=salesorders.branchcode'),
('salesorders', 'shippers', 'salesorders.debtorno=shippers.shipper_id'),
('shippers', 'salesorders', 'shippers.shipper_id=salesorders.debtorno'),
('salesorders', 'locations', 'salesorders.fromstkloc=locations.loccode'),
('locations', 'salesorders', 'locations.loccode=salesorders.fromstkloc'),
('securitygroups', 'securityroles', 'securitygroups.secroleid=securityroles.secroleid'),
('securityroles', 'securitygroups', 'securityroles.secroleid=securitygroups.secroleid'),
('securitygroups', 'securitytokens', 'securitygroups.tokenid=securitytokens.tokenid'),
('securitytokens', 'securitygroups', 'securitytokens.tokenid=securitygroups.tokenid'),
('shipmentcharges', 'shipments', 'shipmentcharges.shiptref=shipments.shiptref'),
('shipments', 'shipmentcharges', 'shipments.shiptref=shipmentcharges.shiptref'),
('shipmentcharges', 'systypes', 'shipmentcharges.transtype=systypes.typeid'),
('systypes', 'shipmentcharges', 'systypes.typeid=shipmentcharges.transtype'),
('shipments', 'suppliers', 'shipments.supplierid=suppliers.supplierid'),
('suppliers', 'shipments', 'suppliers.supplierid=shipments.supplierid'),
('stockcheckfreeze', 'stockmaster', 'stockcheckfreeze.stockid=stockmaster.stockid'),
('stockmaster', 'stockcheckfreeze', 'stockmaster.stockid=stockcheckfreeze.stockid'),
('stockcheckfreeze', 'locations', 'stockcheckfreeze.loccode=locations.loccode'),
('locations', 'stockcheckfreeze', 'locations.loccode=stockcheckfreeze.loccode'),
('stockcounts', 'stockmaster', 'stockcounts.stockid=stockmaster.stockid'),
('stockmaster', 'stockcounts', 'stockmaster.stockid=stockcounts.stockid'),
('stockcounts', 'locations', 'stockcounts.loccode=locations.loccode'),
('locations', 'stockcounts', 'locations.loccode=stockcounts.loccode'),
('stockmaster', 'stockcategory', 'stockmaster.categoryid=stockcategory.categoryid'),
('stockcategory', 'stockmaster', 'stockcategory.categoryid=stockmaster.categoryid'),
('stockmaster', 'taxcategories', 'stockmaster.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'stockmaster', 'taxcategories.taxcatid=stockmaster.taxcatid'),
('stockmoves', 'stockmaster', 'stockmoves.stockid=stockmaster.stockid'),
('stockmaster', 'stockmoves', 'stockmaster.stockid=stockmoves.stockid'),
('stockmoves', 'systypes', 'stockmoves.type=systypes.typeid'),
('systypes', 'stockmoves', 'systypes.typeid=stockmoves.type'),
('stockmoves', 'locations', 'stockmoves.loccode=locations.loccode'),
('locations', 'stockmoves', 'locations.loccode=stockmoves.loccode'),
('stockmoves', 'periods', 'stockmoves.prd=periods.periodno'),
('periods', 'stockmoves', 'periods.periodno=stockmoves.prd'),
('stockmovestaxes', 'taxauthorities', 'stockmovestaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'stockmovestaxes', 'taxauthorities.taxid=stockmovestaxes.taxauthid'),
('stockserialitems', 'stockmaster', 'stockserialitems.stockid=stockmaster.stockid'),
('stockmaster', 'stockserialitems', 'stockmaster.stockid=stockserialitems.stockid'),
('stockserialitems', 'locations', 'stockserialitems.loccode=locations.loccode'),
('locations', 'stockserialitems', 'locations.loccode=stockserialitems.loccode'),
('stockserialmoves', 'stockmoves', 'stockserialmoves.stockmoveno=stockmoves.stkmoveno'),
('stockmoves', 'stockserialmoves', 'stockmoves.stkmoveno=stockserialmoves.stockmoveno'),
('stockserialmoves', 'stockserialitems', 'stockserialmoves.stockid=stockserialitems.stockid'),
('stockserialitems', 'stockserialmoves', 'stockserialitems.stockid=stockserialmoves.stockid'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocfrom=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocfrom'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocto=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocto'),
('suppliercontacts', 'suppliers', 'suppliercontacts.supplierid=suppliers.supplierid'),
('suppliers', 'suppliercontacts', 'suppliers.supplierid=suppliercontacts.supplierid'),
('suppliers', 'currencies', 'suppliers.currcode=currencies.currabrev'),
('currencies', 'suppliers', 'currencies.currabrev=suppliers.currcode'),
('suppliers', 'paymentterms', 'suppliers.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'suppliers', 'paymentterms.termsindicator=suppliers.paymentterms'),
('suppliers', 'taxgroups', 'suppliers.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'suppliers', 'taxgroups.taxgroupid=suppliers.taxgroupid'),
('supptrans', 'systypes', 'supptrans.type=systypes.typeid'),
('systypes', 'supptrans', 'systypes.typeid=supptrans.type'),
('supptrans', 'suppliers', 'supptrans.supplierno=suppliers.supplierid'),
('suppliers', 'supptrans', 'suppliers.supplierid=supptrans.supplierno'),
('supptranstaxes', 'taxauthorities', 'supptranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'supptranstaxes', 'taxauthorities.taxid=supptranstaxes.taxauthid'),
('supptranstaxes', 'supptrans', 'supptranstaxes.supptransid=supptrans.id'),
('supptrans', 'supptranstaxes', 'supptrans.id=supptranstaxes.supptransid'),
('taxauthorities', 'chartmaster', 'taxauthorities.taxglcode=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.taxglcode'),
('taxauthorities', 'chartmaster', 'taxauthorities.purchtaxglaccount=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.purchtaxglaccount'),
('taxauthrates', 'taxauthorities', 'taxauthrates.taxauthority=taxauthorities.taxid'),
('taxauthorities', 'taxauthrates', 'taxauthorities.taxid=taxauthrates.taxauthority'),
('taxauthrates', 'taxcategories', 'taxauthrates.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'taxauthrates', 'taxcategories.taxcatid=taxauthrates.taxcatid'),
('taxauthrates', 'taxprovinces', 'taxauthrates.dispatchtaxprovince=taxprovinces.taxprovinceid'),
('taxprovinces', 'taxauthrates', 'taxprovinces.taxprovinceid=taxauthrates.dispatchtaxprovince'),
('taxgrouptaxes', 'taxgroups', 'taxgrouptaxes.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'taxgrouptaxes', 'taxgroups.taxgroupid=taxgrouptaxes.taxgroupid'),
('taxgrouptaxes', 'taxauthorities', 'taxgrouptaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'taxgrouptaxes', 'taxauthorities.taxid=taxgrouptaxes.taxauthid'),
('workcentres', 'locations', 'workcentres.location=locations.loccode'),
('locations', 'workcentres', 'locations.loccode=workcentres.location'),
('worksorders', 'locations', 'worksorders.loccode=locations.loccode'),
('locations', 'worksorders', 'locations.loccode=worksorders.loccode'),
('worksorders', 'stockmaster', 'worksorders.stockid=stockmaster.stockid'),
('stockmaster', 'worksorders', 'stockmaster.stockid=worksorders.stockid'),
('www_users', 'locations', 'www_users.defaultlocation=locations.loccode'),
('locations', 'www_users', 'locations.loccode=www_users.defaultlocation'),
('accountgroups', 'accountsection', 'accountgroups.sectioninaccounts=accountsection.sectionid'),
('accountsection', 'accountgroups', 'accountsection.sectionid=accountgroups.sectioninaccounts'),
('bankaccounts', 'chartmaster', 'bankaccounts.accountcode=chartmaster.accountcode'),
('chartmaster', 'bankaccounts', 'chartmaster.accountcode=bankaccounts.accountcode'),
('banktrans', 'systypes', 'banktrans.type=systypes.typeid'),
('systypes', 'banktrans', 'systypes.typeid=banktrans.type'),
('banktrans', 'bankaccounts', 'banktrans.bankact=bankaccounts.accountcode'),
('bankaccounts', 'banktrans', 'bankaccounts.accountcode=banktrans.bankact'),
('bom', 'stockmaster', 'bom.parent=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.parent'),
('bom', 'stockmaster', 'bom.component=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.component'),
('bom', 'workcentres', 'bom.workcentreadded=workcentres.code'),
('workcentres', 'bom', 'workcentres.code=bom.workcentreadded'),
('bom', 'locations', 'bom.loccode=locations.loccode'),
('locations', 'bom', 'locations.loccode=bom.loccode'),
('buckets', 'workcentres', 'buckets.workcentre=workcentres.code'),
('workcentres', 'buckets', 'workcentres.code=buckets.workcentre'),
('chartdetails', 'chartmaster', 'chartdetails.accountcode=chartmaster.accountcode'),
('chartmaster', 'chartdetails', 'chartmaster.accountcode=chartdetails.accountcode'),
('chartdetails', 'periods', 'chartdetails.period=periods.periodno'),
('periods', 'chartdetails', 'periods.periodno=chartdetails.period'),
('chartmaster', 'accountgroups', 'chartmaster.group_=accountgroups.groupname'),
('accountgroups', 'chartmaster', 'accountgroups.groupname=chartmaster.group_'),
('contractbom', 'workcentres', 'contractbom.workcentreadded=workcentres.code'),
('workcentres', 'contractbom', 'workcentres.code=contractbom.workcentreadded'),
('contractbom', 'locations', 'contractbom.loccode=locations.loccode'),
('locations', 'contractbom', 'locations.loccode=contractbom.loccode'),
('contractbom', 'stockmaster', 'contractbom.component=stockmaster.stockid'),
('stockmaster', 'contractbom', 'stockmaster.stockid=contractbom.component'),
('contractreqts', 'contracts', 'contractreqts.contract=contracts.contractref'),
('contracts', 'contractreqts', 'contracts.contractref=contractreqts.contract'),
('contracts', 'custbranch', 'contracts.debtorno=custbranch.debtorno'),
('custbranch', 'contracts', 'custbranch.debtorno=contracts.debtorno'),
('contracts', 'stockcategory', 'contracts.branchcode=stockcategory.categoryid'),
('stockcategory', 'contracts', 'stockcategory.categoryid=contracts.branchcode'),
('contracts', 'salestypes', 'contracts.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'contracts', 'salestypes.typeabbrev=contracts.typeabbrev'),
('custallocns', 'debtortrans', 'custallocns.transid_allocfrom=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocfrom'),
('custallocns', 'debtortrans', 'custallocns.transid_allocto=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocto'),
('custbranch', 'debtorsmaster', 'custbranch.debtorno=debtorsmaster.debtorno'),
('debtorsmaster', 'custbranch', 'debtorsmaster.debtorno=custbranch.debtorno'),
('custbranch', 'areas', 'custbranch.area=areas.areacode'),
('areas', 'custbranch', 'areas.areacode=custbranch.area'),
('custbranch', 'salesman', 'custbranch.salesman=salesman.salesmancode'),
('salesman', 'custbranch', 'salesman.salesmancode=custbranch.salesman'),
('custbranch', 'locations', 'custbranch.defaultlocation=locations.loccode'),
('locations', 'custbranch', 'locations.loccode=custbranch.defaultlocation'),
('custbranch', 'shippers', 'custbranch.defaultshipvia=shippers.shipper_id'),
('shippers', 'custbranch', 'shippers.shipper_id=custbranch.defaultshipvia'),
('debtorsmaster', 'holdreasons', 'debtorsmaster.holdreason=holdreasons.reasoncode'),
('holdreasons', 'debtorsmaster', 'holdreasons.reasoncode=debtorsmaster.holdreason'),
('debtorsmaster', 'currencies', 'debtorsmaster.currcode=currencies.currabrev'),
('currencies', 'debtorsmaster', 'currencies.currabrev=debtorsmaster.currcode'),
('debtorsmaster', 'paymentterms', 'debtorsmaster.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'debtorsmaster', 'paymentterms.termsindicator=debtorsmaster.paymentterms'),
('debtorsmaster', 'salestypes', 'debtorsmaster.salestype=salestypes.typeabbrev'),
('salestypes', 'debtorsmaster', 'salestypes.typeabbrev=debtorsmaster.salestype'),
('debtortrans', 'custbranch', 'debtortrans.debtorno=custbranch.debtorno'),
('custbranch', 'debtortrans', 'custbranch.debtorno=debtortrans.debtorno'),
('debtortrans', 'systypes', 'debtortrans.type=systypes.typeid'),
('systypes', 'debtortrans', 'systypes.typeid=debtortrans.type'),
('debtortrans', 'periods', 'debtortrans.prd=periods.periodno'),
('periods', 'debtortrans', 'periods.periodno=debtortrans.prd'),
('debtortranstaxes', 'taxauthorities', 'debtortranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'debtortranstaxes', 'taxauthorities.taxid=debtortranstaxes.taxauthid'),
('debtortranstaxes', 'debtortrans', 'debtortranstaxes.debtortransid=debtortrans.id'),
('debtortrans', 'debtortranstaxes', 'debtortrans.id=debtortranstaxes.debtortransid'),
('discountmatrix', 'salestypes', 'discountmatrix.salestype=salestypes.typeabbrev'),
('salestypes', 'discountmatrix', 'salestypes.typeabbrev=discountmatrix.salestype'),
('freightcosts', 'locations', 'freightcosts.locationfrom=locations.loccode'),
('locations', 'freightcosts', 'locations.loccode=freightcosts.locationfrom'),
('freightcosts', 'shippers', 'freightcosts.shipperid=shippers.shipper_id'),
('shippers', 'freightcosts', 'shippers.shipper_id=freightcosts.shipperid'),
('gltrans', 'chartmaster', 'gltrans.account=chartmaster.accountcode'),
('chartmaster', 'gltrans', 'chartmaster.accountcode=gltrans.account'),
('gltrans', 'systypes', 'gltrans.type=systypes.typeid'),
('systypes', 'gltrans', 'systypes.typeid=gltrans.type'),
('gltrans', 'periods', 'gltrans.periodno=periods.periodno'),
('periods', 'gltrans', 'periods.periodno=gltrans.periodno'),
('grns', 'suppliers', 'grns.supplierid=suppliers.supplierid'),
('suppliers', 'grns', 'suppliers.supplierid=grns.supplierid'),
('grns', 'purchorderdetails', 'grns.podetailitem=purchorderdetails.podetailitem'),
('purchorderdetails', 'grns', 'purchorderdetails.podetailitem=grns.podetailitem'),
('locations', 'taxprovinces', 'locations.taxprovinceid=taxprovinces.taxprovinceid'),
('taxprovinces', 'locations', 'taxprovinces.taxprovinceid=locations.taxprovinceid'),
('locstock', 'locations', 'locstock.loccode=locations.loccode'),
('locations', 'locstock', 'locations.loccode=locstock.loccode'),
('locstock', 'stockmaster', 'locstock.stockid=stockmaster.stockid'),
('stockmaster', 'locstock', 'stockmaster.stockid=locstock.stockid'),
('loctransfers', 'locations', 'loctransfers.shiploc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.shiploc'),
('loctransfers', 'locations', 'loctransfers.recloc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.recloc'),
('loctransfers', 'stockmaster', 'loctransfers.stockid=stockmaster.stockid'),
('stockmaster', 'loctransfers', 'stockmaster.stockid=loctransfers.stockid'),
('orderdeliverydifferencesl', 'stockmaster', 'orderdeliverydifferenceslog.stockid=stockmaster.stockid'),
('stockmaster', 'orderdeliverydifferencesl', 'stockmaster.stockid=orderdeliverydifferenceslog.stockid'),
('orderdeliverydifferencesl', 'custbranch', 'orderdeliverydifferenceslog.debtorno=custbranch.debtorno'),
('custbranch', 'orderdeliverydifferencesl', 'custbranch.debtorno=orderdeliverydifferenceslog.debtorno'),
('orderdeliverydifferencesl', 'salesorders', 'orderdeliverydifferenceslog.branchcode=salesorders.orderno'),
('salesorders', 'orderdeliverydifferencesl', 'salesorders.orderno=orderdeliverydifferenceslog.branchcode'),
('prices', 'stockmaster', 'prices.stockid=stockmaster.stockid'),
('stockmaster', 'prices', 'stockmaster.stockid=prices.stockid'),
('prices', 'currencies', 'prices.currabrev=currencies.currabrev'),
('currencies', 'prices', 'currencies.currabrev=prices.currabrev'),
('prices', 'salestypes', 'prices.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'prices', 'salestypes.typeabbrev=prices.typeabbrev'),
('purchdata', 'stockmaster', 'purchdata.stockid=stockmaster.stockid'),
('stockmaster', 'purchdata', 'stockmaster.stockid=purchdata.stockid'),
('purchdata', 'suppliers', 'purchdata.supplierno=suppliers.supplierid'),
('suppliers', 'purchdata', 'suppliers.supplierid=purchdata.supplierno'),
('purchorderdetails', 'purchorders', 'purchorderdetails.orderno=purchorders.orderno'),
('purchorders', 'purchorderdetails', 'purchorders.orderno=purchorderdetails.orderno'),
('purchorders', 'suppliers', 'purchorders.supplierno=suppliers.supplierid'),
('suppliers', 'purchorders', 'suppliers.supplierid=purchorders.supplierno'),
('purchorders', 'locations', 'purchorders.intostocklocation=locations.loccode'),
('locations', 'purchorders', 'locations.loccode=purchorders.intostocklocation'),
('recurringsalesorders', 'custbranch', 'recurringsalesorders.branchcode=custbranch.branchcode'),
('custbranch', 'recurringsalesorders', 'custbranch.branchcode=recurringsalesorders.branchcode'),
('recurrsalesorderdetails', 'recurringsalesorders', 'recurrsalesorderdetails.recurrorderno=recurringsalesorders.recurrorderno'),
('recurringsalesorders', 'recurrsalesorderdetails', 'recurringsalesorders.recurrorderno=recurrsalesorderdetails.recurrorderno'),
('recurrsalesorderdetails', 'stockmaster', 'recurrsalesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'recurrsalesorderdetails', 'stockmaster.stockid=recurrsalesorderdetails.stkcode'),
('reportcolumns', 'reportheaders', 'reportcolumns.reportid=reportheaders.reportid'),
('reportheaders', 'reportcolumns', 'reportheaders.reportid=reportcolumns.reportid'),
('salesanalysis', 'periods', 'salesanalysis.periodno=periods.periodno'),
('periods', 'salesanalysis', 'periods.periodno=salesanalysis.periodno'),
('salescatprod', 'stockmaster', 'salescatprod.stockid=stockmaster.stockid'),
('stockmaster', 'salescatprod', 'stockmaster.stockid=salescatprod.stockid'),
('salescatprod', 'salescat', 'salescatprod.salescatid=salescat.salescatid'),
('salescat', 'salescatprod', 'salescat.salescatid=salescatprod.salescatid'),
('salesorderdetails', 'salesorders', 'salesorderdetails.orderno=salesorders.orderno'),
('salesorders', 'salesorderdetails', 'salesorders.orderno=salesorderdetails.orderno'),
('salesorderdetails', 'stockmaster', 'salesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'salesorderdetails', 'stockmaster.stockid=salesorderdetails.stkcode'),
('salesorders', 'custbranch', 'salesorders.branchcode=custbranch.branchcode'),
('custbranch', 'salesorders', 'custbranch.branchcode=salesorders.branchcode'),
('salesorders', 'shippers', 'salesorders.debtorno=shippers.shipper_id'),
('shippers', 'salesorders', 'shippers.shipper_id=salesorders.debtorno'),
('salesorders', 'locations', 'salesorders.fromstkloc=locations.loccode'),
('locations', 'salesorders', 'locations.loccode=salesorders.fromstkloc'),
('securitygroups', 'securityroles', 'securitygroups.secroleid=securityroles.secroleid'),
('securityroles', 'securitygroups', 'securityroles.secroleid=securitygroups.secroleid'),
('securitygroups', 'securitytokens', 'securitygroups.tokenid=securitytokens.tokenid'),
('securitytokens', 'securitygroups', 'securitytokens.tokenid=securitygroups.tokenid'),
('shipmentcharges', 'shipments', 'shipmentcharges.shiptref=shipments.shiptref'),
('shipments', 'shipmentcharges', 'shipments.shiptref=shipmentcharges.shiptref'),
('shipmentcharges', 'systypes', 'shipmentcharges.transtype=systypes.typeid'),
('systypes', 'shipmentcharges', 'systypes.typeid=shipmentcharges.transtype'),
('shipments', 'suppliers', 'shipments.supplierid=suppliers.supplierid'),
('suppliers', 'shipments', 'suppliers.supplierid=shipments.supplierid'),
('stockcheckfreeze', 'stockmaster', 'stockcheckfreeze.stockid=stockmaster.stockid'),
('stockmaster', 'stockcheckfreeze', 'stockmaster.stockid=stockcheckfreeze.stockid'),
('stockcheckfreeze', 'locations', 'stockcheckfreeze.loccode=locations.loccode'),
('locations', 'stockcheckfreeze', 'locations.loccode=stockcheckfreeze.loccode'),
('stockcounts', 'stockmaster', 'stockcounts.stockid=stockmaster.stockid'),
('stockmaster', 'stockcounts', 'stockmaster.stockid=stockcounts.stockid'),
('stockcounts', 'locations', 'stockcounts.loccode=locations.loccode'),
('locations', 'stockcounts', 'locations.loccode=stockcounts.loccode'),
('stockmaster', 'stockcategory', 'stockmaster.categoryid=stockcategory.categoryid'),
('stockcategory', 'stockmaster', 'stockcategory.categoryid=stockmaster.categoryid'),
('stockmaster', 'taxcategories', 'stockmaster.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'stockmaster', 'taxcategories.taxcatid=stockmaster.taxcatid'),
('stockmoves', 'stockmaster', 'stockmoves.stockid=stockmaster.stockid'),
('stockmaster', 'stockmoves', 'stockmaster.stockid=stockmoves.stockid'),
('stockmoves', 'systypes', 'stockmoves.type=systypes.typeid'),
('systypes', 'stockmoves', 'systypes.typeid=stockmoves.type'),
('stockmoves', 'locations', 'stockmoves.loccode=locations.loccode'),
('locations', 'stockmoves', 'locations.loccode=stockmoves.loccode'),
('stockmoves', 'periods', 'stockmoves.prd=periods.periodno'),
('periods', 'stockmoves', 'periods.periodno=stockmoves.prd'),
('stockmovestaxes', 'taxauthorities', 'stockmovestaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'stockmovestaxes', 'taxauthorities.taxid=stockmovestaxes.taxauthid'),
('stockserialitems', 'stockmaster', 'stockserialitems.stockid=stockmaster.stockid'),
('stockmaster', 'stockserialitems', 'stockmaster.stockid=stockserialitems.stockid'),
('stockserialitems', 'locations', 'stockserialitems.loccode=locations.loccode'),
('locations', 'stockserialitems', 'locations.loccode=stockserialitems.loccode'),
('stockserialmoves', 'stockmoves', 'stockserialmoves.stockmoveno=stockmoves.stkmoveno'),
('stockmoves', 'stockserialmoves', 'stockmoves.stkmoveno=stockserialmoves.stockmoveno'),
('stockserialmoves', 'stockserialitems', 'stockserialmoves.stockid=stockserialitems.stockid'),
('stockserialitems', 'stockserialmoves', 'stockserialitems.stockid=stockserialmoves.stockid'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocfrom=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocfrom'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocto=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocto'),
('suppliercontacts', 'suppliers', 'suppliercontacts.supplierid=suppliers.supplierid'),
('suppliers', 'suppliercontacts', 'suppliers.supplierid=suppliercontacts.supplierid'),
('suppliers', 'currencies', 'suppliers.currcode=currencies.currabrev'),
('currencies', 'suppliers', 'currencies.currabrev=suppliers.currcode'),
('suppliers', 'paymentterms', 'suppliers.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'suppliers', 'paymentterms.termsindicator=suppliers.paymentterms'),
('suppliers', 'taxgroups', 'suppliers.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'suppliers', 'taxgroups.taxgroupid=suppliers.taxgroupid'),
('supptrans', 'systypes', 'supptrans.type=systypes.typeid'),
('systypes', 'supptrans', 'systypes.typeid=supptrans.type'),
('supptrans', 'suppliers', 'supptrans.supplierno=suppliers.supplierid'),
('suppliers', 'supptrans', 'suppliers.supplierid=supptrans.supplierno'),
('supptranstaxes', 'taxauthorities', 'supptranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'supptranstaxes', 'taxauthorities.taxid=supptranstaxes.taxauthid'),
('supptranstaxes', 'supptrans', 'supptranstaxes.supptransid=supptrans.id'),
('supptrans', 'supptranstaxes', 'supptrans.id=supptranstaxes.supptransid'),
('taxauthorities', 'chartmaster', 'taxauthorities.taxglcode=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.taxglcode'),
('taxauthorities', 'chartmaster', 'taxauthorities.purchtaxglaccount=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.purchtaxglaccount'),
('taxauthrates', 'taxauthorities', 'taxauthrates.taxauthority=taxauthorities.taxid'),
('taxauthorities', 'taxauthrates', 'taxauthorities.taxid=taxauthrates.taxauthority'),
('taxauthrates', 'taxcategories', 'taxauthrates.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'taxauthrates', 'taxcategories.taxcatid=taxauthrates.taxcatid'),
('taxauthrates', 'taxprovinces', 'taxauthrates.dispatchtaxprovince=taxprovinces.taxprovinceid'),
('taxprovinces', 'taxauthrates', 'taxprovinces.taxprovinceid=taxauthrates.dispatchtaxprovince'),
('taxgrouptaxes', 'taxgroups', 'taxgrouptaxes.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'taxgrouptaxes', 'taxgroups.taxgroupid=taxgrouptaxes.taxgroupid'),
('taxgrouptaxes', 'taxauthorities', 'taxgrouptaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'taxgrouptaxes', 'taxauthorities.taxid=taxgrouptaxes.taxauthid'),
('workcentres', 'locations', 'workcentres.location=locations.loccode'),
('locations', 'workcentres', 'locations.loccode=workcentres.location'),
('worksorders', 'locations', 'worksorders.loccode=locations.loccode'),
('locations', 'worksorders', 'locations.loccode=worksorders.loccode'),
('worksorders', 'stockmaster', 'worksorders.stockid=stockmaster.stockid'),
('stockmaster', 'worksorders', 'stockmaster.stockid=worksorders.stockid'),
('www_users', 'locations', 'www_users.defaultlocation=locations.loccode'),
('locations', 'www_users', 'locations.loccode=www_users.defaultlocation'),
('accountgroups', 'accountsection', 'accountgroups.sectioninaccounts=accountsection.sectionid'),
('accountsection', 'accountgroups', 'accountsection.sectionid=accountgroups.sectioninaccounts'),
('bankaccounts', 'chartmaster', 'bankaccounts.accountcode=chartmaster.accountcode'),
('chartmaster', 'bankaccounts', 'chartmaster.accountcode=bankaccounts.accountcode'),
('banktrans', 'systypes', 'banktrans.type=systypes.typeid'),
('systypes', 'banktrans', 'systypes.typeid=banktrans.type'),
('banktrans', 'bankaccounts', 'banktrans.bankact=bankaccounts.accountcode'),
('bankaccounts', 'banktrans', 'bankaccounts.accountcode=banktrans.bankact'),
('bom', 'stockmaster', 'bom.parent=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.parent'),
('bom', 'stockmaster', 'bom.component=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.component'),
('bom', 'workcentres', 'bom.workcentreadded=workcentres.code'),
('workcentres', 'bom', 'workcentres.code=bom.workcentreadded'),
('bom', 'locations', 'bom.loccode=locations.loccode'),
('locations', 'bom', 'locations.loccode=bom.loccode'),
('buckets', 'workcentres', 'buckets.workcentre=workcentres.code'),
('workcentres', 'buckets', 'workcentres.code=buckets.workcentre'),
('chartdetails', 'chartmaster', 'chartdetails.accountcode=chartmaster.accountcode'),
('chartmaster', 'chartdetails', 'chartmaster.accountcode=chartdetails.accountcode'),
('chartdetails', 'periods', 'chartdetails.period=periods.periodno'),
('periods', 'chartdetails', 'periods.periodno=chartdetails.period'),
('chartmaster', 'accountgroups', 'chartmaster.group_=accountgroups.groupname'),
('accountgroups', 'chartmaster', 'accountgroups.groupname=chartmaster.group_'),
('contractbom', 'workcentres', 'contractbom.workcentreadded=workcentres.code'),
('workcentres', 'contractbom', 'workcentres.code=contractbom.workcentreadded'),
('contractbom', 'locations', 'contractbom.loccode=locations.loccode'),
('locations', 'contractbom', 'locations.loccode=contractbom.loccode'),
('contractbom', 'stockmaster', 'contractbom.component=stockmaster.stockid'),
('stockmaster', 'contractbom', 'stockmaster.stockid=contractbom.component'),
('contractreqts', 'contracts', 'contractreqts.contract=contracts.contractref'),
('contracts', 'contractreqts', 'contracts.contractref=contractreqts.contract'),
('contracts', 'custbranch', 'contracts.debtorno=custbranch.debtorno'),
('custbranch', 'contracts', 'custbranch.debtorno=contracts.debtorno'),
('contracts', 'stockcategory', 'contracts.branchcode=stockcategory.categoryid'),
('stockcategory', 'contracts', 'stockcategory.categoryid=contracts.branchcode'),
('contracts', 'salestypes', 'contracts.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'contracts', 'salestypes.typeabbrev=contracts.typeabbrev'),
('custallocns', 'debtortrans', 'custallocns.transid_allocfrom=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocfrom'),
('custallocns', 'debtortrans', 'custallocns.transid_allocto=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocto'),
('custbranch', 'debtorsmaster', 'custbranch.debtorno=debtorsmaster.debtorno'),
('debtorsmaster', 'custbranch', 'debtorsmaster.debtorno=custbranch.debtorno'),
('custbranch', 'areas', 'custbranch.area=areas.areacode'),
('areas', 'custbranch', 'areas.areacode=custbranch.area'),
('custbranch', 'salesman', 'custbranch.salesman=salesman.salesmancode'),
('salesman', 'custbranch', 'salesman.salesmancode=custbranch.salesman'),
('custbranch', 'locations', 'custbranch.defaultlocation=locations.loccode'),
('locations', 'custbranch', 'locations.loccode=custbranch.defaultlocation'),
('custbranch', 'shippers', 'custbranch.defaultshipvia=shippers.shipper_id'),
('shippers', 'custbranch', 'shippers.shipper_id=custbranch.defaultshipvia'),
('debtorsmaster', 'holdreasons', 'debtorsmaster.holdreason=holdreasons.reasoncode'),
('holdreasons', 'debtorsmaster', 'holdreasons.reasoncode=debtorsmaster.holdreason'),
('debtorsmaster', 'currencies', 'debtorsmaster.currcode=currencies.currabrev'),
('currencies', 'debtorsmaster', 'currencies.currabrev=debtorsmaster.currcode'),
('debtorsmaster', 'paymentterms', 'debtorsmaster.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'debtorsmaster', 'paymentterms.termsindicator=debtorsmaster.paymentterms'),
('debtorsmaster', 'salestypes', 'debtorsmaster.salestype=salestypes.typeabbrev'),
('salestypes', 'debtorsmaster', 'salestypes.typeabbrev=debtorsmaster.salestype'),
('debtortrans', 'custbranch', 'debtortrans.debtorno=custbranch.debtorno'),
('custbranch', 'debtortrans', 'custbranch.debtorno=debtortrans.debtorno'),
('debtortrans', 'systypes', 'debtortrans.type=systypes.typeid'),
('systypes', 'debtortrans', 'systypes.typeid=debtortrans.type'),
('debtortrans', 'periods', 'debtortrans.prd=periods.periodno'),
('periods', 'debtortrans', 'periods.periodno=debtortrans.prd'),
('debtortranstaxes', 'taxauthorities', 'debtortranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'debtortranstaxes', 'taxauthorities.taxid=debtortranstaxes.taxauthid'),
('debtortranstaxes', 'debtortrans', 'debtortranstaxes.debtortransid=debtortrans.id'),
('debtortrans', 'debtortranstaxes', 'debtortrans.id=debtortranstaxes.debtortransid'),
('discountmatrix', 'salestypes', 'discountmatrix.salestype=salestypes.typeabbrev'),
('salestypes', 'discountmatrix', 'salestypes.typeabbrev=discountmatrix.salestype'),
('freightcosts', 'locations', 'freightcosts.locationfrom=locations.loccode'),
('locations', 'freightcosts', 'locations.loccode=freightcosts.locationfrom'),
('freightcosts', 'shippers', 'freightcosts.shipperid=shippers.shipper_id'),
('shippers', 'freightcosts', 'shippers.shipper_id=freightcosts.shipperid'),
('gltrans', 'chartmaster', 'gltrans.account=chartmaster.accountcode'),
('chartmaster', 'gltrans', 'chartmaster.accountcode=gltrans.account'),
('gltrans', 'systypes', 'gltrans.type=systypes.typeid'),
('systypes', 'gltrans', 'systypes.typeid=gltrans.type'),
('gltrans', 'periods', 'gltrans.periodno=periods.periodno'),
('periods', 'gltrans', 'periods.periodno=gltrans.periodno'),
('grns', 'suppliers', 'grns.supplierid=suppliers.supplierid'),
('suppliers', 'grns', 'suppliers.supplierid=grns.supplierid'),
('grns', 'purchorderdetails', 'grns.podetailitem=purchorderdetails.podetailitem'),
('purchorderdetails', 'grns', 'purchorderdetails.podetailitem=grns.podetailitem'),
('locations', 'taxprovinces', 'locations.taxprovinceid=taxprovinces.taxprovinceid'),
('taxprovinces', 'locations', 'taxprovinces.taxprovinceid=locations.taxprovinceid'),
('locstock', 'locations', 'locstock.loccode=locations.loccode'),
('locations', 'locstock', 'locations.loccode=locstock.loccode'),
('locstock', 'stockmaster', 'locstock.stockid=stockmaster.stockid'),
('stockmaster', 'locstock', 'stockmaster.stockid=locstock.stockid'),
('loctransfers', 'locations', 'loctransfers.shiploc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.shiploc'),
('loctransfers', 'locations', 'loctransfers.recloc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.recloc'),
('loctransfers', 'stockmaster', 'loctransfers.stockid=stockmaster.stockid'),
('stockmaster', 'loctransfers', 'stockmaster.stockid=loctransfers.stockid'),
('orderdeliverydifferencesl', 'stockmaster', 'orderdeliverydifferenceslog.stockid=stockmaster.stockid'),
('stockmaster', 'orderdeliverydifferencesl', 'stockmaster.stockid=orderdeliverydifferenceslog.stockid'),
('orderdeliverydifferencesl', 'custbranch', 'orderdeliverydifferenceslog.debtorno=custbranch.debtorno'),
('custbranch', 'orderdeliverydifferencesl', 'custbranch.debtorno=orderdeliverydifferenceslog.debtorno'),
('orderdeliverydifferencesl', 'salesorders', 'orderdeliverydifferenceslog.branchcode=salesorders.orderno'),
('salesorders', 'orderdeliverydifferencesl', 'salesorders.orderno=orderdeliverydifferenceslog.branchcode'),
('prices', 'stockmaster', 'prices.stockid=stockmaster.stockid'),
('stockmaster', 'prices', 'stockmaster.stockid=prices.stockid'),
('prices', 'currencies', 'prices.currabrev=currencies.currabrev'),
('currencies', 'prices', 'currencies.currabrev=prices.currabrev'),
('prices', 'salestypes', 'prices.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'prices', 'salestypes.typeabbrev=prices.typeabbrev'),
('purchdata', 'stockmaster', 'purchdata.stockid=stockmaster.stockid'),
('stockmaster', 'purchdata', 'stockmaster.stockid=purchdata.stockid'),
('purchdata', 'suppliers', 'purchdata.supplierno=suppliers.supplierid'),
('suppliers', 'purchdata', 'suppliers.supplierid=purchdata.supplierno'),
('purchorderdetails', 'purchorders', 'purchorderdetails.orderno=purchorders.orderno'),
('purchorders', 'purchorderdetails', 'purchorders.orderno=purchorderdetails.orderno'),
('purchorders', 'suppliers', 'purchorders.supplierno=suppliers.supplierid'),
('suppliers', 'purchorders', 'suppliers.supplierid=purchorders.supplierno'),
('purchorders', 'locations', 'purchorders.intostocklocation=locations.loccode'),
('locations', 'purchorders', 'locations.loccode=purchorders.intostocklocation'),
('recurringsalesorders', 'custbranch', 'recurringsalesorders.branchcode=custbranch.branchcode'),
('custbranch', 'recurringsalesorders', 'custbranch.branchcode=recurringsalesorders.branchcode'),
('recurrsalesorderdetails', 'recurringsalesorders', 'recurrsalesorderdetails.recurrorderno=recurringsalesorders.recurrorderno'),
('recurringsalesorders', 'recurrsalesorderdetails', 'recurringsalesorders.recurrorderno=recurrsalesorderdetails.recurrorderno'),
('recurrsalesorderdetails', 'stockmaster', 'recurrsalesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'recurrsalesorderdetails', 'stockmaster.stockid=recurrsalesorderdetails.stkcode'),
('reportcolumns', 'reportheaders', 'reportcolumns.reportid=reportheaders.reportid'),
('reportheaders', 'reportcolumns', 'reportheaders.reportid=reportcolumns.reportid'),
('salesanalysis', 'periods', 'salesanalysis.periodno=periods.periodno'),
('periods', 'salesanalysis', 'periods.periodno=salesanalysis.periodno'),
('salescatprod', 'stockmaster', 'salescatprod.stockid=stockmaster.stockid'),
('stockmaster', 'salescatprod', 'stockmaster.stockid=salescatprod.stockid'),
('salescatprod', 'salescat', 'salescatprod.salescatid=salescat.salescatid'),
('salescat', 'salescatprod', 'salescat.salescatid=salescatprod.salescatid'),
('salesorderdetails', 'salesorders', 'salesorderdetails.orderno=salesorders.orderno'),
('salesorders', 'salesorderdetails', 'salesorders.orderno=salesorderdetails.orderno'),
('salesorderdetails', 'stockmaster', 'salesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'salesorderdetails', 'stockmaster.stockid=salesorderdetails.stkcode'),
('salesorders', 'custbranch', 'salesorders.branchcode=custbranch.branchcode'),
('custbranch', 'salesorders', 'custbranch.branchcode=salesorders.branchcode'),
('salesorders', 'shippers', 'salesorders.debtorno=shippers.shipper_id'),
('shippers', 'salesorders', 'shippers.shipper_id=salesorders.debtorno'),
('salesorders', 'locations', 'salesorders.fromstkloc=locations.loccode'),
('locations', 'salesorders', 'locations.loccode=salesorders.fromstkloc'),
('securitygroups', 'securityroles', 'securitygroups.secroleid=securityroles.secroleid'),
('securityroles', 'securitygroups', 'securityroles.secroleid=securitygroups.secroleid'),
('securitygroups', 'securitytokens', 'securitygroups.tokenid=securitytokens.tokenid'),
('securitytokens', 'securitygroups', 'securitytokens.tokenid=securitygroups.tokenid'),
('shipmentcharges', 'shipments', 'shipmentcharges.shiptref=shipments.shiptref'),
('shipments', 'shipmentcharges', 'shipments.shiptref=shipmentcharges.shiptref'),
('shipmentcharges', 'systypes', 'shipmentcharges.transtype=systypes.typeid'),
('systypes', 'shipmentcharges', 'systypes.typeid=shipmentcharges.transtype'),
('shipments', 'suppliers', 'shipments.supplierid=suppliers.supplierid');
INSERT INTO `reportlinks` (`table1`, `table2`, `equation`) VALUES
('suppliers', 'shipments', 'suppliers.supplierid=shipments.supplierid'),
('stockcheckfreeze', 'stockmaster', 'stockcheckfreeze.stockid=stockmaster.stockid'),
('stockmaster', 'stockcheckfreeze', 'stockmaster.stockid=stockcheckfreeze.stockid'),
('stockcheckfreeze', 'locations', 'stockcheckfreeze.loccode=locations.loccode'),
('locations', 'stockcheckfreeze', 'locations.loccode=stockcheckfreeze.loccode'),
('stockcounts', 'stockmaster', 'stockcounts.stockid=stockmaster.stockid'),
('stockmaster', 'stockcounts', 'stockmaster.stockid=stockcounts.stockid'),
('stockcounts', 'locations', 'stockcounts.loccode=locations.loccode'),
('locations', 'stockcounts', 'locations.loccode=stockcounts.loccode'),
('stockmaster', 'stockcategory', 'stockmaster.categoryid=stockcategory.categoryid'),
('stockcategory', 'stockmaster', 'stockcategory.categoryid=stockmaster.categoryid'),
('stockmaster', 'taxcategories', 'stockmaster.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'stockmaster', 'taxcategories.taxcatid=stockmaster.taxcatid'),
('stockmoves', 'stockmaster', 'stockmoves.stockid=stockmaster.stockid'),
('stockmaster', 'stockmoves', 'stockmaster.stockid=stockmoves.stockid'),
('stockmoves', 'systypes', 'stockmoves.type=systypes.typeid'),
('systypes', 'stockmoves', 'systypes.typeid=stockmoves.type'),
('stockmoves', 'locations', 'stockmoves.loccode=locations.loccode'),
('locations', 'stockmoves', 'locations.loccode=stockmoves.loccode'),
('stockmoves', 'periods', 'stockmoves.prd=periods.periodno'),
('periods', 'stockmoves', 'periods.periodno=stockmoves.prd'),
('stockmovestaxes', 'taxauthorities', 'stockmovestaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'stockmovestaxes', 'taxauthorities.taxid=stockmovestaxes.taxauthid'),
('stockserialitems', 'stockmaster', 'stockserialitems.stockid=stockmaster.stockid'),
('stockmaster', 'stockserialitems', 'stockmaster.stockid=stockserialitems.stockid'),
('stockserialitems', 'locations', 'stockserialitems.loccode=locations.loccode'),
('locations', 'stockserialitems', 'locations.loccode=stockserialitems.loccode'),
('stockserialmoves', 'stockmoves', 'stockserialmoves.stockmoveno=stockmoves.stkmoveno'),
('stockmoves', 'stockserialmoves', 'stockmoves.stkmoveno=stockserialmoves.stockmoveno'),
('stockserialmoves', 'stockserialitems', 'stockserialmoves.stockid=stockserialitems.stockid'),
('stockserialitems', 'stockserialmoves', 'stockserialitems.stockid=stockserialmoves.stockid'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocfrom=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocfrom'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocto=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocto'),
('suppliercontacts', 'suppliers', 'suppliercontacts.supplierid=suppliers.supplierid'),
('suppliers', 'suppliercontacts', 'suppliers.supplierid=suppliercontacts.supplierid'),
('suppliers', 'currencies', 'suppliers.currcode=currencies.currabrev'),
('currencies', 'suppliers', 'currencies.currabrev=suppliers.currcode'),
('suppliers', 'paymentterms', 'suppliers.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'suppliers', 'paymentterms.termsindicator=suppliers.paymentterms'),
('suppliers', 'taxgroups', 'suppliers.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'suppliers', 'taxgroups.taxgroupid=suppliers.taxgroupid'),
('supptrans', 'systypes', 'supptrans.type=systypes.typeid'),
('systypes', 'supptrans', 'systypes.typeid=supptrans.type'),
('supptrans', 'suppliers', 'supptrans.supplierno=suppliers.supplierid'),
('suppliers', 'supptrans', 'suppliers.supplierid=supptrans.supplierno'),
('supptranstaxes', 'taxauthorities', 'supptranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'supptranstaxes', 'taxauthorities.taxid=supptranstaxes.taxauthid'),
('supptranstaxes', 'supptrans', 'supptranstaxes.supptransid=supptrans.id'),
('supptrans', 'supptranstaxes', 'supptrans.id=supptranstaxes.supptransid'),
('taxauthorities', 'chartmaster', 'taxauthorities.taxglcode=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.taxglcode'),
('taxauthorities', 'chartmaster', 'taxauthorities.purchtaxglaccount=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.purchtaxglaccount'),
('taxauthrates', 'taxauthorities', 'taxauthrates.taxauthority=taxauthorities.taxid'),
('taxauthorities', 'taxauthrates', 'taxauthorities.taxid=taxauthrates.taxauthority'),
('taxauthrates', 'taxcategories', 'taxauthrates.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'taxauthrates', 'taxcategories.taxcatid=taxauthrates.taxcatid'),
('taxauthrates', 'taxprovinces', 'taxauthrates.dispatchtaxprovince=taxprovinces.taxprovinceid'),
('taxprovinces', 'taxauthrates', 'taxprovinces.taxprovinceid=taxauthrates.dispatchtaxprovince'),
('taxgrouptaxes', 'taxgroups', 'taxgrouptaxes.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'taxgrouptaxes', 'taxgroups.taxgroupid=taxgrouptaxes.taxgroupid'),
('taxgrouptaxes', 'taxauthorities', 'taxgrouptaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'taxgrouptaxes', 'taxauthorities.taxid=taxgrouptaxes.taxauthid'),
('workcentres', 'locations', 'workcentres.location=locations.loccode'),
('locations', 'workcentres', 'locations.loccode=workcentres.location'),
('worksorders', 'locations', 'worksorders.loccode=locations.loccode'),
('locations', 'worksorders', 'locations.loccode=worksorders.loccode'),
('worksorders', 'stockmaster', 'worksorders.stockid=stockmaster.stockid'),
('stockmaster', 'worksorders', 'stockmaster.stockid=worksorders.stockid'),
('www_users', 'locations', 'www_users.defaultlocation=locations.loccode'),
('locations', 'www_users', 'locations.loccode=www_users.defaultlocation'),
('accountgroups', 'accountsection', 'accountgroups.sectioninaccounts=accountsection.sectionid'),
('accountsection', 'accountgroups', 'accountsection.sectionid=accountgroups.sectioninaccounts'),
('bankaccounts', 'chartmaster', 'bankaccounts.accountcode=chartmaster.accountcode'),
('chartmaster', 'bankaccounts', 'chartmaster.accountcode=bankaccounts.accountcode'),
('banktrans', 'systypes', 'banktrans.type=systypes.typeid'),
('systypes', 'banktrans', 'systypes.typeid=banktrans.type'),
('banktrans', 'bankaccounts', 'banktrans.bankact=bankaccounts.accountcode'),
('bankaccounts', 'banktrans', 'bankaccounts.accountcode=banktrans.bankact'),
('bom', 'stockmaster', 'bom.parent=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.parent'),
('bom', 'stockmaster', 'bom.component=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.component'),
('bom', 'workcentres', 'bom.workcentreadded=workcentres.code'),
('workcentres', 'bom', 'workcentres.code=bom.workcentreadded'),
('bom', 'locations', 'bom.loccode=locations.loccode'),
('locations', 'bom', 'locations.loccode=bom.loccode'),
('buckets', 'workcentres', 'buckets.workcentre=workcentres.code'),
('workcentres', 'buckets', 'workcentres.code=buckets.workcentre'),
('chartdetails', 'chartmaster', 'chartdetails.accountcode=chartmaster.accountcode'),
('chartmaster', 'chartdetails', 'chartmaster.accountcode=chartdetails.accountcode'),
('chartdetails', 'periods', 'chartdetails.period=periods.periodno'),
('periods', 'chartdetails', 'periods.periodno=chartdetails.period'),
('chartmaster', 'accountgroups', 'chartmaster.group_=accountgroups.groupname'),
('accountgroups', 'chartmaster', 'accountgroups.groupname=chartmaster.group_'),
('contractbom', 'workcentres', 'contractbom.workcentreadded=workcentres.code'),
('workcentres', 'contractbom', 'workcentres.code=contractbom.workcentreadded'),
('contractbom', 'locations', 'contractbom.loccode=locations.loccode'),
('locations', 'contractbom', 'locations.loccode=contractbom.loccode'),
('contractbom', 'stockmaster', 'contractbom.component=stockmaster.stockid'),
('stockmaster', 'contractbom', 'stockmaster.stockid=contractbom.component'),
('contractreqts', 'contracts', 'contractreqts.contract=contracts.contractref'),
('contracts', 'contractreqts', 'contracts.contractref=contractreqts.contract'),
('contracts', 'custbranch', 'contracts.debtorno=custbranch.debtorno'),
('custbranch', 'contracts', 'custbranch.debtorno=contracts.debtorno'),
('contracts', 'stockcategory', 'contracts.branchcode=stockcategory.categoryid'),
('stockcategory', 'contracts', 'stockcategory.categoryid=contracts.branchcode'),
('contracts', 'salestypes', 'contracts.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'contracts', 'salestypes.typeabbrev=contracts.typeabbrev'),
('custallocns', 'debtortrans', 'custallocns.transid_allocfrom=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocfrom'),
('custallocns', 'debtortrans', 'custallocns.transid_allocto=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocto'),
('custbranch', 'debtorsmaster', 'custbranch.debtorno=debtorsmaster.debtorno'),
('debtorsmaster', 'custbranch', 'debtorsmaster.debtorno=custbranch.debtorno'),
('custbranch', 'areas', 'custbranch.area=areas.areacode'),
('areas', 'custbranch', 'areas.areacode=custbranch.area'),
('custbranch', 'salesman', 'custbranch.salesman=salesman.salesmancode'),
('salesman', 'custbranch', 'salesman.salesmancode=custbranch.salesman'),
('custbranch', 'locations', 'custbranch.defaultlocation=locations.loccode'),
('locations', 'custbranch', 'locations.loccode=custbranch.defaultlocation'),
('custbranch', 'shippers', 'custbranch.defaultshipvia=shippers.shipper_id'),
('shippers', 'custbranch', 'shippers.shipper_id=custbranch.defaultshipvia'),
('debtorsmaster', 'holdreasons', 'debtorsmaster.holdreason=holdreasons.reasoncode'),
('holdreasons', 'debtorsmaster', 'holdreasons.reasoncode=debtorsmaster.holdreason'),
('debtorsmaster', 'currencies', 'debtorsmaster.currcode=currencies.currabrev'),
('currencies', 'debtorsmaster', 'currencies.currabrev=debtorsmaster.currcode'),
('debtorsmaster', 'paymentterms', 'debtorsmaster.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'debtorsmaster', 'paymentterms.termsindicator=debtorsmaster.paymentterms'),
('debtorsmaster', 'salestypes', 'debtorsmaster.salestype=salestypes.typeabbrev'),
('salestypes', 'debtorsmaster', 'salestypes.typeabbrev=debtorsmaster.salestype'),
('debtortrans', 'custbranch', 'debtortrans.debtorno=custbranch.debtorno'),
('custbranch', 'debtortrans', 'custbranch.debtorno=debtortrans.debtorno'),
('debtortrans', 'systypes', 'debtortrans.type=systypes.typeid'),
('systypes', 'debtortrans', 'systypes.typeid=debtortrans.type'),
('debtortrans', 'periods', 'debtortrans.prd=periods.periodno'),
('periods', 'debtortrans', 'periods.periodno=debtortrans.prd'),
('debtortranstaxes', 'taxauthorities', 'debtortranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'debtortranstaxes', 'taxauthorities.taxid=debtortranstaxes.taxauthid'),
('debtortranstaxes', 'debtortrans', 'debtortranstaxes.debtortransid=debtortrans.id'),
('debtortrans', 'debtortranstaxes', 'debtortrans.id=debtortranstaxes.debtortransid'),
('discountmatrix', 'salestypes', 'discountmatrix.salestype=salestypes.typeabbrev'),
('salestypes', 'discountmatrix', 'salestypes.typeabbrev=discountmatrix.salestype'),
('freightcosts', 'locations', 'freightcosts.locationfrom=locations.loccode'),
('locations', 'freightcosts', 'locations.loccode=freightcosts.locationfrom'),
('freightcosts', 'shippers', 'freightcosts.shipperid=shippers.shipper_id'),
('shippers', 'freightcosts', 'shippers.shipper_id=freightcosts.shipperid'),
('gltrans', 'chartmaster', 'gltrans.account=chartmaster.accountcode'),
('chartmaster', 'gltrans', 'chartmaster.accountcode=gltrans.account'),
('gltrans', 'systypes', 'gltrans.type=systypes.typeid'),
('systypes', 'gltrans', 'systypes.typeid=gltrans.type'),
('gltrans', 'periods', 'gltrans.periodno=periods.periodno'),
('periods', 'gltrans', 'periods.periodno=gltrans.periodno'),
('grns', 'suppliers', 'grns.supplierid=suppliers.supplierid'),
('suppliers', 'grns', 'suppliers.supplierid=grns.supplierid'),
('grns', 'purchorderdetails', 'grns.podetailitem=purchorderdetails.podetailitem'),
('purchorderdetails', 'grns', 'purchorderdetails.podetailitem=grns.podetailitem'),
('locations', 'taxprovinces', 'locations.taxprovinceid=taxprovinces.taxprovinceid'),
('taxprovinces', 'locations', 'taxprovinces.taxprovinceid=locations.taxprovinceid'),
('locstock', 'locations', 'locstock.loccode=locations.loccode'),
('locations', 'locstock', 'locations.loccode=locstock.loccode'),
('locstock', 'stockmaster', 'locstock.stockid=stockmaster.stockid'),
('stockmaster', 'locstock', 'stockmaster.stockid=locstock.stockid'),
('loctransfers', 'locations', 'loctransfers.shiploc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.shiploc'),
('loctransfers', 'locations', 'loctransfers.recloc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.recloc'),
('loctransfers', 'stockmaster', 'loctransfers.stockid=stockmaster.stockid'),
('stockmaster', 'loctransfers', 'stockmaster.stockid=loctransfers.stockid'),
('orderdeliverydifferencesl', 'stockmaster', 'orderdeliverydifferenceslog.stockid=stockmaster.stockid'),
('stockmaster', 'orderdeliverydifferencesl', 'stockmaster.stockid=orderdeliverydifferenceslog.stockid'),
('orderdeliverydifferencesl', 'custbranch', 'orderdeliverydifferenceslog.debtorno=custbranch.debtorno'),
('custbranch', 'orderdeliverydifferencesl', 'custbranch.debtorno=orderdeliverydifferenceslog.debtorno'),
('orderdeliverydifferencesl', 'salesorders', 'orderdeliverydifferenceslog.branchcode=salesorders.orderno'),
('salesorders', 'orderdeliverydifferencesl', 'salesorders.orderno=orderdeliverydifferenceslog.branchcode'),
('prices', 'stockmaster', 'prices.stockid=stockmaster.stockid'),
('stockmaster', 'prices', 'stockmaster.stockid=prices.stockid'),
('prices', 'currencies', 'prices.currabrev=currencies.currabrev'),
('currencies', 'prices', 'currencies.currabrev=prices.currabrev'),
('prices', 'salestypes', 'prices.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'prices', 'salestypes.typeabbrev=prices.typeabbrev'),
('purchdata', 'stockmaster', 'purchdata.stockid=stockmaster.stockid'),
('stockmaster', 'purchdata', 'stockmaster.stockid=purchdata.stockid'),
('purchdata', 'suppliers', 'purchdata.supplierno=suppliers.supplierid'),
('suppliers', 'purchdata', 'suppliers.supplierid=purchdata.supplierno'),
('purchorderdetails', 'purchorders', 'purchorderdetails.orderno=purchorders.orderno'),
('purchorders', 'purchorderdetails', 'purchorders.orderno=purchorderdetails.orderno'),
('purchorders', 'suppliers', 'purchorders.supplierno=suppliers.supplierid'),
('suppliers', 'purchorders', 'suppliers.supplierid=purchorders.supplierno'),
('purchorders', 'locations', 'purchorders.intostocklocation=locations.loccode'),
('locations', 'purchorders', 'locations.loccode=purchorders.intostocklocation'),
('recurringsalesorders', 'custbranch', 'recurringsalesorders.branchcode=custbranch.branchcode'),
('custbranch', 'recurringsalesorders', 'custbranch.branchcode=recurringsalesorders.branchcode'),
('recurrsalesorderdetails', 'recurringsalesorders', 'recurrsalesorderdetails.recurrorderno=recurringsalesorders.recurrorderno'),
('recurringsalesorders', 'recurrsalesorderdetails', 'recurringsalesorders.recurrorderno=recurrsalesorderdetails.recurrorderno'),
('recurrsalesorderdetails', 'stockmaster', 'recurrsalesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'recurrsalesorderdetails', 'stockmaster.stockid=recurrsalesorderdetails.stkcode'),
('reportcolumns', 'reportheaders', 'reportcolumns.reportid=reportheaders.reportid'),
('reportheaders', 'reportcolumns', 'reportheaders.reportid=reportcolumns.reportid'),
('salesanalysis', 'periods', 'salesanalysis.periodno=periods.periodno'),
('periods', 'salesanalysis', 'periods.periodno=salesanalysis.periodno'),
('salescatprod', 'stockmaster', 'salescatprod.stockid=stockmaster.stockid'),
('stockmaster', 'salescatprod', 'stockmaster.stockid=salescatprod.stockid'),
('salescatprod', 'salescat', 'salescatprod.salescatid=salescat.salescatid'),
('salescat', 'salescatprod', 'salescat.salescatid=salescatprod.salescatid'),
('salesorderdetails', 'salesorders', 'salesorderdetails.orderno=salesorders.orderno'),
('salesorders', 'salesorderdetails', 'salesorders.orderno=salesorderdetails.orderno'),
('salesorderdetails', 'stockmaster', 'salesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'salesorderdetails', 'stockmaster.stockid=salesorderdetails.stkcode'),
('salesorders', 'custbranch', 'salesorders.branchcode=custbranch.branchcode'),
('custbranch', 'salesorders', 'custbranch.branchcode=salesorders.branchcode'),
('salesorders', 'shippers', 'salesorders.debtorno=shippers.shipper_id'),
('shippers', 'salesorders', 'shippers.shipper_id=salesorders.debtorno'),
('salesorders', 'locations', 'salesorders.fromstkloc=locations.loccode'),
('locations', 'salesorders', 'locations.loccode=salesorders.fromstkloc'),
('securitygroups', 'securityroles', 'securitygroups.secroleid=securityroles.secroleid'),
('securityroles', 'securitygroups', 'securityroles.secroleid=securitygroups.secroleid'),
('securitygroups', 'securitytokens', 'securitygroups.tokenid=securitytokens.tokenid'),
('securitytokens', 'securitygroups', 'securitytokens.tokenid=securitygroups.tokenid'),
('shipmentcharges', 'shipments', 'shipmentcharges.shiptref=shipments.shiptref'),
('shipments', 'shipmentcharges', 'shipments.shiptref=shipmentcharges.shiptref'),
('shipmentcharges', 'systypes', 'shipmentcharges.transtype=systypes.typeid'),
('systypes', 'shipmentcharges', 'systypes.typeid=shipmentcharges.transtype'),
('shipments', 'suppliers', 'shipments.supplierid=suppliers.supplierid'),
('suppliers', 'shipments', 'suppliers.supplierid=shipments.supplierid'),
('stockcheckfreeze', 'stockmaster', 'stockcheckfreeze.stockid=stockmaster.stockid'),
('stockmaster', 'stockcheckfreeze', 'stockmaster.stockid=stockcheckfreeze.stockid'),
('stockcheckfreeze', 'locations', 'stockcheckfreeze.loccode=locations.loccode'),
('locations', 'stockcheckfreeze', 'locations.loccode=stockcheckfreeze.loccode'),
('stockcounts', 'stockmaster', 'stockcounts.stockid=stockmaster.stockid'),
('stockmaster', 'stockcounts', 'stockmaster.stockid=stockcounts.stockid'),
('stockcounts', 'locations', 'stockcounts.loccode=locations.loccode'),
('locations', 'stockcounts', 'locations.loccode=stockcounts.loccode'),
('stockmaster', 'stockcategory', 'stockmaster.categoryid=stockcategory.categoryid'),
('stockcategory', 'stockmaster', 'stockcategory.categoryid=stockmaster.categoryid'),
('stockmaster', 'taxcategories', 'stockmaster.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'stockmaster', 'taxcategories.taxcatid=stockmaster.taxcatid'),
('stockmoves', 'stockmaster', 'stockmoves.stockid=stockmaster.stockid'),
('stockmaster', 'stockmoves', 'stockmaster.stockid=stockmoves.stockid'),
('stockmoves', 'systypes', 'stockmoves.type=systypes.typeid'),
('systypes', 'stockmoves', 'systypes.typeid=stockmoves.type'),
('stockmoves', 'locations', 'stockmoves.loccode=locations.loccode'),
('locations', 'stockmoves', 'locations.loccode=stockmoves.loccode'),
('stockmoves', 'periods', 'stockmoves.prd=periods.periodno'),
('periods', 'stockmoves', 'periods.periodno=stockmoves.prd'),
('stockmovestaxes', 'taxauthorities', 'stockmovestaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'stockmovestaxes', 'taxauthorities.taxid=stockmovestaxes.taxauthid'),
('stockserialitems', 'stockmaster', 'stockserialitems.stockid=stockmaster.stockid'),
('stockmaster', 'stockserialitems', 'stockmaster.stockid=stockserialitems.stockid'),
('stockserialitems', 'locations', 'stockserialitems.loccode=locations.loccode'),
('locations', 'stockserialitems', 'locations.loccode=stockserialitems.loccode'),
('stockserialmoves', 'stockmoves', 'stockserialmoves.stockmoveno=stockmoves.stkmoveno'),
('stockmoves', 'stockserialmoves', 'stockmoves.stkmoveno=stockserialmoves.stockmoveno'),
('stockserialmoves', 'stockserialitems', 'stockserialmoves.stockid=stockserialitems.stockid'),
('stockserialitems', 'stockserialmoves', 'stockserialitems.stockid=stockserialmoves.stockid'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocfrom=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocfrom'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocto=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocto'),
('suppliercontacts', 'suppliers', 'suppliercontacts.supplierid=suppliers.supplierid'),
('suppliers', 'suppliercontacts', 'suppliers.supplierid=suppliercontacts.supplierid'),
('suppliers', 'currencies', 'suppliers.currcode=currencies.currabrev'),
('currencies', 'suppliers', 'currencies.currabrev=suppliers.currcode'),
('suppliers', 'paymentterms', 'suppliers.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'suppliers', 'paymentterms.termsindicator=suppliers.paymentterms'),
('suppliers', 'taxgroups', 'suppliers.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'suppliers', 'taxgroups.taxgroupid=suppliers.taxgroupid'),
('supptrans', 'systypes', 'supptrans.type=systypes.typeid'),
('systypes', 'supptrans', 'systypes.typeid=supptrans.type'),
('supptrans', 'suppliers', 'supptrans.supplierno=suppliers.supplierid'),
('suppliers', 'supptrans', 'suppliers.supplierid=supptrans.supplierno'),
('supptranstaxes', 'taxauthorities', 'supptranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'supptranstaxes', 'taxauthorities.taxid=supptranstaxes.taxauthid'),
('supptranstaxes', 'supptrans', 'supptranstaxes.supptransid=supptrans.id'),
('supptrans', 'supptranstaxes', 'supptrans.id=supptranstaxes.supptransid'),
('taxauthorities', 'chartmaster', 'taxauthorities.taxglcode=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.taxglcode'),
('taxauthorities', 'chartmaster', 'taxauthorities.purchtaxglaccount=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.purchtaxglaccount'),
('taxauthrates', 'taxauthorities', 'taxauthrates.taxauthority=taxauthorities.taxid'),
('taxauthorities', 'taxauthrates', 'taxauthorities.taxid=taxauthrates.taxauthority'),
('taxauthrates', 'taxcategories', 'taxauthrates.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'taxauthrates', 'taxcategories.taxcatid=taxauthrates.taxcatid'),
('taxauthrates', 'taxprovinces', 'taxauthrates.dispatchtaxprovince=taxprovinces.taxprovinceid'),
('taxprovinces', 'taxauthrates', 'taxprovinces.taxprovinceid=taxauthrates.dispatchtaxprovince'),
('taxgrouptaxes', 'taxgroups', 'taxgrouptaxes.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'taxgrouptaxes', 'taxgroups.taxgroupid=taxgrouptaxes.taxgroupid'),
('taxgrouptaxes', 'taxauthorities', 'taxgrouptaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'taxgrouptaxes', 'taxauthorities.taxid=taxgrouptaxes.taxauthid'),
('workcentres', 'locations', 'workcentres.location=locations.loccode'),
('locations', 'workcentres', 'locations.loccode=workcentres.location'),
('worksorders', 'locations', 'worksorders.loccode=locations.loccode'),
('locations', 'worksorders', 'locations.loccode=worksorders.loccode'),
('worksorders', 'stockmaster', 'worksorders.stockid=stockmaster.stockid'),
('stockmaster', 'worksorders', 'stockmaster.stockid=worksorders.stockid'),
('www_users', 'locations', 'www_users.defaultlocation=locations.loccode'),
('locations', 'www_users', 'locations.loccode=www_users.defaultlocation'),
('accountgroups', 'accountsection', 'accountgroups.sectioninaccounts=accountsection.sectionid'),
('accountsection', 'accountgroups', 'accountsection.sectionid=accountgroups.sectioninaccounts'),
('bankaccounts', 'chartmaster', 'bankaccounts.accountcode=chartmaster.accountcode'),
('chartmaster', 'bankaccounts', 'chartmaster.accountcode=bankaccounts.accountcode'),
('banktrans', 'systypes', 'banktrans.type=systypes.typeid'),
('systypes', 'banktrans', 'systypes.typeid=banktrans.type'),
('banktrans', 'bankaccounts', 'banktrans.bankact=bankaccounts.accountcode'),
('bankaccounts', 'banktrans', 'bankaccounts.accountcode=banktrans.bankact'),
('bom', 'stockmaster', 'bom.parent=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.parent'),
('bom', 'stockmaster', 'bom.component=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.component'),
('bom', 'workcentres', 'bom.workcentreadded=workcentres.code'),
('workcentres', 'bom', 'workcentres.code=bom.workcentreadded'),
('bom', 'locations', 'bom.loccode=locations.loccode'),
('locations', 'bom', 'locations.loccode=bom.loccode'),
('buckets', 'workcentres', 'buckets.workcentre=workcentres.code'),
('workcentres', 'buckets', 'workcentres.code=buckets.workcentre'),
('chartdetails', 'chartmaster', 'chartdetails.accountcode=chartmaster.accountcode'),
('chartmaster', 'chartdetails', 'chartmaster.accountcode=chartdetails.accountcode'),
('chartdetails', 'periods', 'chartdetails.period=periods.periodno'),
('periods', 'chartdetails', 'periods.periodno=chartdetails.period'),
('chartmaster', 'accountgroups', 'chartmaster.group_=accountgroups.groupname'),
('accountgroups', 'chartmaster', 'accountgroups.groupname=chartmaster.group_'),
('contractbom', 'workcentres', 'contractbom.workcentreadded=workcentres.code'),
('workcentres', 'contractbom', 'workcentres.code=contractbom.workcentreadded'),
('contractbom', 'locations', 'contractbom.loccode=locations.loccode'),
('locations', 'contractbom', 'locations.loccode=contractbom.loccode'),
('contractbom', 'stockmaster', 'contractbom.component=stockmaster.stockid'),
('stockmaster', 'contractbom', 'stockmaster.stockid=contractbom.component'),
('contractreqts', 'contracts', 'contractreqts.contract=contracts.contractref'),
('contracts', 'contractreqts', 'contracts.contractref=contractreqts.contract'),
('contracts', 'custbranch', 'contracts.debtorno=custbranch.debtorno'),
('custbranch', 'contracts', 'custbranch.debtorno=contracts.debtorno'),
('contracts', 'stockcategory', 'contracts.branchcode=stockcategory.categoryid'),
('stockcategory', 'contracts', 'stockcategory.categoryid=contracts.branchcode'),
('contracts', 'salestypes', 'contracts.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'contracts', 'salestypes.typeabbrev=contracts.typeabbrev'),
('custallocns', 'debtortrans', 'custallocns.transid_allocfrom=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocfrom'),
('custallocns', 'debtortrans', 'custallocns.transid_allocto=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocto'),
('custbranch', 'debtorsmaster', 'custbranch.debtorno=debtorsmaster.debtorno'),
('debtorsmaster', 'custbranch', 'debtorsmaster.debtorno=custbranch.debtorno'),
('custbranch', 'areas', 'custbranch.area=areas.areacode'),
('areas', 'custbranch', 'areas.areacode=custbranch.area'),
('custbranch', 'salesman', 'custbranch.salesman=salesman.salesmancode'),
('salesman', 'custbranch', 'salesman.salesmancode=custbranch.salesman'),
('custbranch', 'locations', 'custbranch.defaultlocation=locations.loccode'),
('locations', 'custbranch', 'locations.loccode=custbranch.defaultlocation'),
('custbranch', 'shippers', 'custbranch.defaultshipvia=shippers.shipper_id'),
('shippers', 'custbranch', 'shippers.shipper_id=custbranch.defaultshipvia'),
('debtorsmaster', 'holdreasons', 'debtorsmaster.holdreason=holdreasons.reasoncode'),
('holdreasons', 'debtorsmaster', 'holdreasons.reasoncode=debtorsmaster.holdreason'),
('debtorsmaster', 'currencies', 'debtorsmaster.currcode=currencies.currabrev'),
('currencies', 'debtorsmaster', 'currencies.currabrev=debtorsmaster.currcode'),
('debtorsmaster', 'paymentterms', 'debtorsmaster.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'debtorsmaster', 'paymentterms.termsindicator=debtorsmaster.paymentterms'),
('debtorsmaster', 'salestypes', 'debtorsmaster.salestype=salestypes.typeabbrev'),
('salestypes', 'debtorsmaster', 'salestypes.typeabbrev=debtorsmaster.salestype'),
('debtortrans', 'custbranch', 'debtortrans.debtorno=custbranch.debtorno'),
('custbranch', 'debtortrans', 'custbranch.debtorno=debtortrans.debtorno'),
('debtortrans', 'systypes', 'debtortrans.type=systypes.typeid'),
('systypes', 'debtortrans', 'systypes.typeid=debtortrans.type'),
('debtortrans', 'periods', 'debtortrans.prd=periods.periodno'),
('periods', 'debtortrans', 'periods.periodno=debtortrans.prd'),
('debtortranstaxes', 'taxauthorities', 'debtortranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'debtortranstaxes', 'taxauthorities.taxid=debtortranstaxes.taxauthid'),
('debtortranstaxes', 'debtortrans', 'debtortranstaxes.debtortransid=debtortrans.id'),
('debtortrans', 'debtortranstaxes', 'debtortrans.id=debtortranstaxes.debtortransid'),
('discountmatrix', 'salestypes', 'discountmatrix.salestype=salestypes.typeabbrev'),
('salestypes', 'discountmatrix', 'salestypes.typeabbrev=discountmatrix.salestype'),
('freightcosts', 'locations', 'freightcosts.locationfrom=locations.loccode'),
('locations', 'freightcosts', 'locations.loccode=freightcosts.locationfrom'),
('freightcosts', 'shippers', 'freightcosts.shipperid=shippers.shipper_id'),
('shippers', 'freightcosts', 'shippers.shipper_id=freightcosts.shipperid'),
('gltrans', 'chartmaster', 'gltrans.account=chartmaster.accountcode'),
('chartmaster', 'gltrans', 'chartmaster.accountcode=gltrans.account'),
('gltrans', 'systypes', 'gltrans.type=systypes.typeid'),
('systypes', 'gltrans', 'systypes.typeid=gltrans.type'),
('gltrans', 'periods', 'gltrans.periodno=periods.periodno'),
('periods', 'gltrans', 'periods.periodno=gltrans.periodno'),
('grns', 'suppliers', 'grns.supplierid=suppliers.supplierid'),
('suppliers', 'grns', 'suppliers.supplierid=grns.supplierid'),
('grns', 'purchorderdetails', 'grns.podetailitem=purchorderdetails.podetailitem'),
('purchorderdetails', 'grns', 'purchorderdetails.podetailitem=grns.podetailitem'),
('locations', 'taxprovinces', 'locations.taxprovinceid=taxprovinces.taxprovinceid'),
('taxprovinces', 'locations', 'taxprovinces.taxprovinceid=locations.taxprovinceid'),
('locstock', 'locations', 'locstock.loccode=locations.loccode'),
('locations', 'locstock', 'locations.loccode=locstock.loccode'),
('locstock', 'stockmaster', 'locstock.stockid=stockmaster.stockid'),
('stockmaster', 'locstock', 'stockmaster.stockid=locstock.stockid'),
('loctransfers', 'locations', 'loctransfers.shiploc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.shiploc'),
('loctransfers', 'locations', 'loctransfers.recloc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.recloc'),
('loctransfers', 'stockmaster', 'loctransfers.stockid=stockmaster.stockid'),
('stockmaster', 'loctransfers', 'stockmaster.stockid=loctransfers.stockid'),
('orderdeliverydifferencesl', 'stockmaster', 'orderdeliverydifferenceslog.stockid=stockmaster.stockid'),
('stockmaster', 'orderdeliverydifferencesl', 'stockmaster.stockid=orderdeliverydifferenceslog.stockid'),
('orderdeliverydifferencesl', 'custbranch', 'orderdeliverydifferenceslog.debtorno=custbranch.debtorno'),
('custbranch', 'orderdeliverydifferencesl', 'custbranch.debtorno=orderdeliverydifferenceslog.debtorno'),
('orderdeliverydifferencesl', 'salesorders', 'orderdeliverydifferenceslog.branchcode=salesorders.orderno'),
('salesorders', 'orderdeliverydifferencesl', 'salesorders.orderno=orderdeliverydifferenceslog.branchcode'),
('prices', 'stockmaster', 'prices.stockid=stockmaster.stockid'),
('stockmaster', 'prices', 'stockmaster.stockid=prices.stockid'),
('prices', 'currencies', 'prices.currabrev=currencies.currabrev'),
('currencies', 'prices', 'currencies.currabrev=prices.currabrev'),
('prices', 'salestypes', 'prices.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'prices', 'salestypes.typeabbrev=prices.typeabbrev'),
('purchdata', 'stockmaster', 'purchdata.stockid=stockmaster.stockid'),
('stockmaster', 'purchdata', 'stockmaster.stockid=purchdata.stockid'),
('purchdata', 'suppliers', 'purchdata.supplierno=suppliers.supplierid'),
('suppliers', 'purchdata', 'suppliers.supplierid=purchdata.supplierno'),
('purchorderdetails', 'purchorders', 'purchorderdetails.orderno=purchorders.orderno'),
('purchorders', 'purchorderdetails', 'purchorders.orderno=purchorderdetails.orderno'),
('purchorders', 'suppliers', 'purchorders.supplierno=suppliers.supplierid'),
('suppliers', 'purchorders', 'suppliers.supplierid=purchorders.supplierno'),
('purchorders', 'locations', 'purchorders.intostocklocation=locations.loccode'),
('locations', 'purchorders', 'locations.loccode=purchorders.intostocklocation'),
('recurringsalesorders', 'custbranch', 'recurringsalesorders.branchcode=custbranch.branchcode'),
('custbranch', 'recurringsalesorders', 'custbranch.branchcode=recurringsalesorders.branchcode'),
('recurrsalesorderdetails', 'recurringsalesorders', 'recurrsalesorderdetails.recurrorderno=recurringsalesorders.recurrorderno'),
('recurringsalesorders', 'recurrsalesorderdetails', 'recurringsalesorders.recurrorderno=recurrsalesorderdetails.recurrorderno'),
('recurrsalesorderdetails', 'stockmaster', 'recurrsalesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'recurrsalesorderdetails', 'stockmaster.stockid=recurrsalesorderdetails.stkcode'),
('reportcolumns', 'reportheaders', 'reportcolumns.reportid=reportheaders.reportid'),
('reportheaders', 'reportcolumns', 'reportheaders.reportid=reportcolumns.reportid'),
('salesanalysis', 'periods', 'salesanalysis.periodno=periods.periodno'),
('periods', 'salesanalysis', 'periods.periodno=salesanalysis.periodno'),
('salescatprod', 'stockmaster', 'salescatprod.stockid=stockmaster.stockid'),
('stockmaster', 'salescatprod', 'stockmaster.stockid=salescatprod.stockid'),
('salescatprod', 'salescat', 'salescatprod.salescatid=salescat.salescatid'),
('salescat', 'salescatprod', 'salescat.salescatid=salescatprod.salescatid'),
('salesorderdetails', 'salesorders', 'salesorderdetails.orderno=salesorders.orderno'),
('salesorders', 'salesorderdetails', 'salesorders.orderno=salesorderdetails.orderno'),
('salesorderdetails', 'stockmaster', 'salesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'salesorderdetails', 'stockmaster.stockid=salesorderdetails.stkcode'),
('salesorders', 'custbranch', 'salesorders.branchcode=custbranch.branchcode'),
('custbranch', 'salesorders', 'custbranch.branchcode=salesorders.branchcode'),
('salesorders', 'shippers', 'salesorders.debtorno=shippers.shipper_id'),
('shippers', 'salesorders', 'shippers.shipper_id=salesorders.debtorno'),
('salesorders', 'locations', 'salesorders.fromstkloc=locations.loccode'),
('locations', 'salesorders', 'locations.loccode=salesorders.fromstkloc'),
('securitygroups', 'securityroles', 'securitygroups.secroleid=securityroles.secroleid'),
('securityroles', 'securitygroups', 'securityroles.secroleid=securitygroups.secroleid'),
('securitygroups', 'securitytokens', 'securitygroups.tokenid=securitytokens.tokenid'),
('securitytokens', 'securitygroups', 'securitytokens.tokenid=securitygroups.tokenid'),
('shipmentcharges', 'shipments', 'shipmentcharges.shiptref=shipments.shiptref'),
('shipments', 'shipmentcharges', 'shipments.shiptref=shipmentcharges.shiptref'),
('shipmentcharges', 'systypes', 'shipmentcharges.transtype=systypes.typeid'),
('systypes', 'shipmentcharges', 'systypes.typeid=shipmentcharges.transtype'),
('shipments', 'suppliers', 'shipments.supplierid=suppliers.supplierid'),
('suppliers', 'shipments', 'suppliers.supplierid=shipments.supplierid'),
('stockcheckfreeze', 'stockmaster', 'stockcheckfreeze.stockid=stockmaster.stockid'),
('stockmaster', 'stockcheckfreeze', 'stockmaster.stockid=stockcheckfreeze.stockid'),
('stockcheckfreeze', 'locations', 'stockcheckfreeze.loccode=locations.loccode'),
('locations', 'stockcheckfreeze', 'locations.loccode=stockcheckfreeze.loccode'),
('stockcounts', 'stockmaster', 'stockcounts.stockid=stockmaster.stockid'),
('stockmaster', 'stockcounts', 'stockmaster.stockid=stockcounts.stockid'),
('stockcounts', 'locations', 'stockcounts.loccode=locations.loccode'),
('locations', 'stockcounts', 'locations.loccode=stockcounts.loccode'),
('stockmaster', 'stockcategory', 'stockmaster.categoryid=stockcategory.categoryid'),
('stockcategory', 'stockmaster', 'stockcategory.categoryid=stockmaster.categoryid'),
('stockmaster', 'taxcategories', 'stockmaster.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'stockmaster', 'taxcategories.taxcatid=stockmaster.taxcatid'),
('stockmoves', 'stockmaster', 'stockmoves.stockid=stockmaster.stockid'),
('stockmaster', 'stockmoves', 'stockmaster.stockid=stockmoves.stockid'),
('stockmoves', 'systypes', 'stockmoves.type=systypes.typeid'),
('systypes', 'stockmoves', 'systypes.typeid=stockmoves.type'),
('stockmoves', 'locations', 'stockmoves.loccode=locations.loccode'),
('locations', 'stockmoves', 'locations.loccode=stockmoves.loccode'),
('stockmoves', 'periods', 'stockmoves.prd=periods.periodno'),
('periods', 'stockmoves', 'periods.periodno=stockmoves.prd'),
('stockmovestaxes', 'taxauthorities', 'stockmovestaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'stockmovestaxes', 'taxauthorities.taxid=stockmovestaxes.taxauthid'),
('stockserialitems', 'stockmaster', 'stockserialitems.stockid=stockmaster.stockid'),
('stockmaster', 'stockserialitems', 'stockmaster.stockid=stockserialitems.stockid'),
('stockserialitems', 'locations', 'stockserialitems.loccode=locations.loccode'),
('locations', 'stockserialitems', 'locations.loccode=stockserialitems.loccode'),
('stockserialmoves', 'stockmoves', 'stockserialmoves.stockmoveno=stockmoves.stkmoveno'),
('stockmoves', 'stockserialmoves', 'stockmoves.stkmoveno=stockserialmoves.stockmoveno'),
('stockserialmoves', 'stockserialitems', 'stockserialmoves.stockid=stockserialitems.stockid'),
('stockserialitems', 'stockserialmoves', 'stockserialitems.stockid=stockserialmoves.stockid'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocfrom=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocfrom'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocto=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocto'),
('suppliercontacts', 'suppliers', 'suppliercontacts.supplierid=suppliers.supplierid'),
('suppliers', 'suppliercontacts', 'suppliers.supplierid=suppliercontacts.supplierid'),
('suppliers', 'currencies', 'suppliers.currcode=currencies.currabrev'),
('currencies', 'suppliers', 'currencies.currabrev=suppliers.currcode'),
('suppliers', 'paymentterms', 'suppliers.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'suppliers', 'paymentterms.termsindicator=suppliers.paymentterms'),
('suppliers', 'taxgroups', 'suppliers.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'suppliers', 'taxgroups.taxgroupid=suppliers.taxgroupid'),
('supptrans', 'systypes', 'supptrans.type=systypes.typeid'),
('systypes', 'supptrans', 'systypes.typeid=supptrans.type'),
('supptrans', 'suppliers', 'supptrans.supplierno=suppliers.supplierid'),
('suppliers', 'supptrans', 'suppliers.supplierid=supptrans.supplierno'),
('supptranstaxes', 'taxauthorities', 'supptranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'supptranstaxes', 'taxauthorities.taxid=supptranstaxes.taxauthid'),
('supptranstaxes', 'supptrans', 'supptranstaxes.supptransid=supptrans.id'),
('supptrans', 'supptranstaxes', 'supptrans.id=supptranstaxes.supptransid'),
('taxauthorities', 'chartmaster', 'taxauthorities.taxglcode=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.taxglcode'),
('taxauthorities', 'chartmaster', 'taxauthorities.purchtaxglaccount=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.purchtaxglaccount'),
('taxauthrates', 'taxauthorities', 'taxauthrates.taxauthority=taxauthorities.taxid'),
('taxauthorities', 'taxauthrates', 'taxauthorities.taxid=taxauthrates.taxauthority'),
('taxauthrates', 'taxcategories', 'taxauthrates.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'taxauthrates', 'taxcategories.taxcatid=taxauthrates.taxcatid'),
('taxauthrates', 'taxprovinces', 'taxauthrates.dispatchtaxprovince=taxprovinces.taxprovinceid'),
('taxprovinces', 'taxauthrates', 'taxprovinces.taxprovinceid=taxauthrates.dispatchtaxprovince'),
('taxgrouptaxes', 'taxgroups', 'taxgrouptaxes.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'taxgrouptaxes', 'taxgroups.taxgroupid=taxgrouptaxes.taxgroupid'),
('taxgrouptaxes', 'taxauthorities', 'taxgrouptaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'taxgrouptaxes', 'taxauthorities.taxid=taxgrouptaxes.taxauthid'),
('workcentres', 'locations', 'workcentres.location=locations.loccode'),
('locations', 'workcentres', 'locations.loccode=workcentres.location'),
('worksorders', 'locations', 'worksorders.loccode=locations.loccode'),
('locations', 'worksorders', 'locations.loccode=worksorders.loccode'),
('worksorders', 'stockmaster', 'worksorders.stockid=stockmaster.stockid'),
('stockmaster', 'worksorders', 'stockmaster.stockid=worksorders.stockid'),
('www_users', 'locations', 'www_users.defaultlocation=locations.loccode'),
('locations', 'www_users', 'locations.loccode=www_users.defaultlocation'),
('accountgroups', 'accountsection', 'accountgroups.sectioninaccounts=accountsection.sectionid'),
('accountsection', 'accountgroups', 'accountsection.sectionid=accountgroups.sectioninaccounts'),
('bankaccounts', 'chartmaster', 'bankaccounts.accountcode=chartmaster.accountcode'),
('chartmaster', 'bankaccounts', 'chartmaster.accountcode=bankaccounts.accountcode'),
('banktrans', 'systypes', 'banktrans.type=systypes.typeid'),
('systypes', 'banktrans', 'systypes.typeid=banktrans.type'),
('banktrans', 'bankaccounts', 'banktrans.bankact=bankaccounts.accountcode'),
('bankaccounts', 'banktrans', 'bankaccounts.accountcode=banktrans.bankact'),
('bom', 'stockmaster', 'bom.parent=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.parent'),
('bom', 'stockmaster', 'bom.component=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.component'),
('bom', 'workcentres', 'bom.workcentreadded=workcentres.code'),
('workcentres', 'bom', 'workcentres.code=bom.workcentreadded'),
('bom', 'locations', 'bom.loccode=locations.loccode'),
('locations', 'bom', 'locations.loccode=bom.loccode'),
('buckets', 'workcentres', 'buckets.workcentre=workcentres.code'),
('workcentres', 'buckets', 'workcentres.code=buckets.workcentre'),
('chartdetails', 'chartmaster', 'chartdetails.accountcode=chartmaster.accountcode'),
('chartmaster', 'chartdetails', 'chartmaster.accountcode=chartdetails.accountcode'),
('chartdetails', 'periods', 'chartdetails.period=periods.periodno'),
('periods', 'chartdetails', 'periods.periodno=chartdetails.period'),
('chartmaster', 'accountgroups', 'chartmaster.group_=accountgroups.groupname'),
('accountgroups', 'chartmaster', 'accountgroups.groupname=chartmaster.group_'),
('contractbom', 'workcentres', 'contractbom.workcentreadded=workcentres.code'),
('workcentres', 'contractbom', 'workcentres.code=contractbom.workcentreadded'),
('contractbom', 'locations', 'contractbom.loccode=locations.loccode'),
('locations', 'contractbom', 'locations.loccode=contractbom.loccode'),
('contractbom', 'stockmaster', 'contractbom.component=stockmaster.stockid'),
('stockmaster', 'contractbom', 'stockmaster.stockid=contractbom.component'),
('contractreqts', 'contracts', 'contractreqts.contract=contracts.contractref'),
('contracts', 'contractreqts', 'contracts.contractref=contractreqts.contract'),
('contracts', 'custbranch', 'contracts.debtorno=custbranch.debtorno'),
('custbranch', 'contracts', 'custbranch.debtorno=contracts.debtorno'),
('contracts', 'stockcategory', 'contracts.branchcode=stockcategory.categoryid'),
('stockcategory', 'contracts', 'stockcategory.categoryid=contracts.branchcode'),
('contracts', 'salestypes', 'contracts.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'contracts', 'salestypes.typeabbrev=contracts.typeabbrev'),
('custallocns', 'debtortrans', 'custallocns.transid_allocfrom=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocfrom'),
('custallocns', 'debtortrans', 'custallocns.transid_allocto=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocto'),
('custbranch', 'debtorsmaster', 'custbranch.debtorno=debtorsmaster.debtorno'),
('debtorsmaster', 'custbranch', 'debtorsmaster.debtorno=custbranch.debtorno'),
('custbranch', 'areas', 'custbranch.area=areas.areacode'),
('areas', 'custbranch', 'areas.areacode=custbranch.area'),
('custbranch', 'salesman', 'custbranch.salesman=salesman.salesmancode'),
('salesman', 'custbranch', 'salesman.salesmancode=custbranch.salesman'),
('custbranch', 'locations', 'custbranch.defaultlocation=locations.loccode'),
('locations', 'custbranch', 'locations.loccode=custbranch.defaultlocation'),
('custbranch', 'shippers', 'custbranch.defaultshipvia=shippers.shipper_id'),
('shippers', 'custbranch', 'shippers.shipper_id=custbranch.defaultshipvia'),
('debtorsmaster', 'holdreasons', 'debtorsmaster.holdreason=holdreasons.reasoncode'),
('holdreasons', 'debtorsmaster', 'holdreasons.reasoncode=debtorsmaster.holdreason'),
('debtorsmaster', 'currencies', 'debtorsmaster.currcode=currencies.currabrev'),
('currencies', 'debtorsmaster', 'currencies.currabrev=debtorsmaster.currcode'),
('debtorsmaster', 'paymentterms', 'debtorsmaster.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'debtorsmaster', 'paymentterms.termsindicator=debtorsmaster.paymentterms'),
('debtorsmaster', 'salestypes', 'debtorsmaster.salestype=salestypes.typeabbrev'),
('salestypes', 'debtorsmaster', 'salestypes.typeabbrev=debtorsmaster.salestype'),
('debtortrans', 'custbranch', 'debtortrans.debtorno=custbranch.debtorno'),
('custbranch', 'debtortrans', 'custbranch.debtorno=debtortrans.debtorno'),
('debtortrans', 'systypes', 'debtortrans.type=systypes.typeid'),
('systypes', 'debtortrans', 'systypes.typeid=debtortrans.type'),
('debtortrans', 'periods', 'debtortrans.prd=periods.periodno'),
('periods', 'debtortrans', 'periods.periodno=debtortrans.prd'),
('debtortranstaxes', 'taxauthorities', 'debtortranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'debtortranstaxes', 'taxauthorities.taxid=debtortranstaxes.taxauthid'),
('debtortranstaxes', 'debtortrans', 'debtortranstaxes.debtortransid=debtortrans.id'),
('debtortrans', 'debtortranstaxes', 'debtortrans.id=debtortranstaxes.debtortransid'),
('discountmatrix', 'salestypes', 'discountmatrix.salestype=salestypes.typeabbrev'),
('salestypes', 'discountmatrix', 'salestypes.typeabbrev=discountmatrix.salestype'),
('freightcosts', 'locations', 'freightcosts.locationfrom=locations.loccode'),
('locations', 'freightcosts', 'locations.loccode=freightcosts.locationfrom'),
('freightcosts', 'shippers', 'freightcosts.shipperid=shippers.shipper_id'),
('shippers', 'freightcosts', 'shippers.shipper_id=freightcosts.shipperid'),
('gltrans', 'chartmaster', 'gltrans.account=chartmaster.accountcode'),
('chartmaster', 'gltrans', 'chartmaster.accountcode=gltrans.account'),
('gltrans', 'systypes', 'gltrans.type=systypes.typeid'),
('systypes', 'gltrans', 'systypes.typeid=gltrans.type'),
('gltrans', 'periods', 'gltrans.periodno=periods.periodno'),
('periods', 'gltrans', 'periods.periodno=gltrans.periodno'),
('grns', 'suppliers', 'grns.supplierid=suppliers.supplierid'),
('suppliers', 'grns', 'suppliers.supplierid=grns.supplierid'),
('grns', 'purchorderdetails', 'grns.podetailitem=purchorderdetails.podetailitem'),
('purchorderdetails', 'grns', 'purchorderdetails.podetailitem=grns.podetailitem'),
('locations', 'taxprovinces', 'locations.taxprovinceid=taxprovinces.taxprovinceid'),
('taxprovinces', 'locations', 'taxprovinces.taxprovinceid=locations.taxprovinceid'),
('locstock', 'locations', 'locstock.loccode=locations.loccode'),
('locations', 'locstock', 'locations.loccode=locstock.loccode'),
('locstock', 'stockmaster', 'locstock.stockid=stockmaster.stockid'),
('stockmaster', 'locstock', 'stockmaster.stockid=locstock.stockid'),
('loctransfers', 'locations', 'loctransfers.shiploc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.shiploc'),
('loctransfers', 'locations', 'loctransfers.recloc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.recloc'),
('loctransfers', 'stockmaster', 'loctransfers.stockid=stockmaster.stockid'),
('stockmaster', 'loctransfers', 'stockmaster.stockid=loctransfers.stockid'),
('orderdeliverydifferencesl', 'stockmaster', 'orderdeliverydifferenceslog.stockid=stockmaster.stockid'),
('stockmaster', 'orderdeliverydifferencesl', 'stockmaster.stockid=orderdeliverydifferenceslog.stockid'),
('orderdeliverydifferencesl', 'custbranch', 'orderdeliverydifferenceslog.debtorno=custbranch.debtorno'),
('custbranch', 'orderdeliverydifferencesl', 'custbranch.debtorno=orderdeliverydifferenceslog.debtorno'),
('orderdeliverydifferencesl', 'salesorders', 'orderdeliverydifferenceslog.branchcode=salesorders.orderno'),
('salesorders', 'orderdeliverydifferencesl', 'salesorders.orderno=orderdeliverydifferenceslog.branchcode'),
('prices', 'stockmaster', 'prices.stockid=stockmaster.stockid'),
('stockmaster', 'prices', 'stockmaster.stockid=prices.stockid'),
('prices', 'currencies', 'prices.currabrev=currencies.currabrev'),
('currencies', 'prices', 'currencies.currabrev=prices.currabrev'),
('prices', 'salestypes', 'prices.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'prices', 'salestypes.typeabbrev=prices.typeabbrev'),
('purchdata', 'stockmaster', 'purchdata.stockid=stockmaster.stockid'),
('stockmaster', 'purchdata', 'stockmaster.stockid=purchdata.stockid'),
('purchdata', 'suppliers', 'purchdata.supplierno=suppliers.supplierid'),
('suppliers', 'purchdata', 'suppliers.supplierid=purchdata.supplierno'),
('purchorderdetails', 'purchorders', 'purchorderdetails.orderno=purchorders.orderno'),
('purchorders', 'purchorderdetails', 'purchorders.orderno=purchorderdetails.orderno'),
('purchorders', 'suppliers', 'purchorders.supplierno=suppliers.supplierid'),
('suppliers', 'purchorders', 'suppliers.supplierid=purchorders.supplierno'),
('purchorders', 'locations', 'purchorders.intostocklocation=locations.loccode'),
('locations', 'purchorders', 'locations.loccode=purchorders.intostocklocation'),
('recurringsalesorders', 'custbranch', 'recurringsalesorders.branchcode=custbranch.branchcode'),
('custbranch', 'recurringsalesorders', 'custbranch.branchcode=recurringsalesorders.branchcode'),
('recurrsalesorderdetails', 'recurringsalesorders', 'recurrsalesorderdetails.recurrorderno=recurringsalesorders.recurrorderno'),
('recurringsalesorders', 'recurrsalesorderdetails', 'recurringsalesorders.recurrorderno=recurrsalesorderdetails.recurrorderno'),
('recurrsalesorderdetails', 'stockmaster', 'recurrsalesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'recurrsalesorderdetails', 'stockmaster.stockid=recurrsalesorderdetails.stkcode'),
('reportcolumns', 'reportheaders', 'reportcolumns.reportid=reportheaders.reportid'),
('reportheaders', 'reportcolumns', 'reportheaders.reportid=reportcolumns.reportid'),
('salesanalysis', 'periods', 'salesanalysis.periodno=periods.periodno');
INSERT INTO `reportlinks` (`table1`, `table2`, `equation`) VALUES
('periods', 'salesanalysis', 'periods.periodno=salesanalysis.periodno'),
('salescatprod', 'stockmaster', 'salescatprod.stockid=stockmaster.stockid'),
('stockmaster', 'salescatprod', 'stockmaster.stockid=salescatprod.stockid'),
('salescatprod', 'salescat', 'salescatprod.salescatid=salescat.salescatid'),
('salescat', 'salescatprod', 'salescat.salescatid=salescatprod.salescatid'),
('salesorderdetails', 'salesorders', 'salesorderdetails.orderno=salesorders.orderno'),
('salesorders', 'salesorderdetails', 'salesorders.orderno=salesorderdetails.orderno'),
('salesorderdetails', 'stockmaster', 'salesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'salesorderdetails', 'stockmaster.stockid=salesorderdetails.stkcode'),
('salesorders', 'custbranch', 'salesorders.branchcode=custbranch.branchcode'),
('custbranch', 'salesorders', 'custbranch.branchcode=salesorders.branchcode'),
('salesorders', 'shippers', 'salesorders.debtorno=shippers.shipper_id'),
('shippers', 'salesorders', 'shippers.shipper_id=salesorders.debtorno'),
('salesorders', 'locations', 'salesorders.fromstkloc=locations.loccode'),
('locations', 'salesorders', 'locations.loccode=salesorders.fromstkloc'),
('securitygroups', 'securityroles', 'securitygroups.secroleid=securityroles.secroleid'),
('securityroles', 'securitygroups', 'securityroles.secroleid=securitygroups.secroleid'),
('securitygroups', 'securitytokens', 'securitygroups.tokenid=securitytokens.tokenid'),
('securitytokens', 'securitygroups', 'securitytokens.tokenid=securitygroups.tokenid'),
('shipmentcharges', 'shipments', 'shipmentcharges.shiptref=shipments.shiptref'),
('shipments', 'shipmentcharges', 'shipments.shiptref=shipmentcharges.shiptref'),
('shipmentcharges', 'systypes', 'shipmentcharges.transtype=systypes.typeid'),
('systypes', 'shipmentcharges', 'systypes.typeid=shipmentcharges.transtype'),
('shipments', 'suppliers', 'shipments.supplierid=suppliers.supplierid'),
('suppliers', 'shipments', 'suppliers.supplierid=shipments.supplierid'),
('stockcheckfreeze', 'stockmaster', 'stockcheckfreeze.stockid=stockmaster.stockid'),
('stockmaster', 'stockcheckfreeze', 'stockmaster.stockid=stockcheckfreeze.stockid'),
('stockcheckfreeze', 'locations', 'stockcheckfreeze.loccode=locations.loccode'),
('locations', 'stockcheckfreeze', 'locations.loccode=stockcheckfreeze.loccode'),
('stockcounts', 'stockmaster', 'stockcounts.stockid=stockmaster.stockid'),
('stockmaster', 'stockcounts', 'stockmaster.stockid=stockcounts.stockid'),
('stockcounts', 'locations', 'stockcounts.loccode=locations.loccode'),
('locations', 'stockcounts', 'locations.loccode=stockcounts.loccode'),
('stockmaster', 'stockcategory', 'stockmaster.categoryid=stockcategory.categoryid'),
('stockcategory', 'stockmaster', 'stockcategory.categoryid=stockmaster.categoryid'),
('stockmaster', 'taxcategories', 'stockmaster.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'stockmaster', 'taxcategories.taxcatid=stockmaster.taxcatid'),
('stockmoves', 'stockmaster', 'stockmoves.stockid=stockmaster.stockid'),
('stockmaster', 'stockmoves', 'stockmaster.stockid=stockmoves.stockid'),
('stockmoves', 'systypes', 'stockmoves.type=systypes.typeid'),
('systypes', 'stockmoves', 'systypes.typeid=stockmoves.type'),
('stockmoves', 'locations', 'stockmoves.loccode=locations.loccode'),
('locations', 'stockmoves', 'locations.loccode=stockmoves.loccode'),
('stockmoves', 'periods', 'stockmoves.prd=periods.periodno'),
('periods', 'stockmoves', 'periods.periodno=stockmoves.prd'),
('stockmovestaxes', 'taxauthorities', 'stockmovestaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'stockmovestaxes', 'taxauthorities.taxid=stockmovestaxes.taxauthid'),
('stockserialitems', 'stockmaster', 'stockserialitems.stockid=stockmaster.stockid'),
('stockmaster', 'stockserialitems', 'stockmaster.stockid=stockserialitems.stockid'),
('stockserialitems', 'locations', 'stockserialitems.loccode=locations.loccode'),
('locations', 'stockserialitems', 'locations.loccode=stockserialitems.loccode'),
('stockserialmoves', 'stockmoves', 'stockserialmoves.stockmoveno=stockmoves.stkmoveno'),
('stockmoves', 'stockserialmoves', 'stockmoves.stkmoveno=stockserialmoves.stockmoveno'),
('stockserialmoves', 'stockserialitems', 'stockserialmoves.stockid=stockserialitems.stockid'),
('stockserialitems', 'stockserialmoves', 'stockserialitems.stockid=stockserialmoves.stockid'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocfrom=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocfrom'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocto=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocto'),
('suppliercontacts', 'suppliers', 'suppliercontacts.supplierid=suppliers.supplierid'),
('suppliers', 'suppliercontacts', 'suppliers.supplierid=suppliercontacts.supplierid'),
('suppliers', 'currencies', 'suppliers.currcode=currencies.currabrev'),
('currencies', 'suppliers', 'currencies.currabrev=suppliers.currcode'),
('suppliers', 'paymentterms', 'suppliers.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'suppliers', 'paymentterms.termsindicator=suppliers.paymentterms'),
('suppliers', 'taxgroups', 'suppliers.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'suppliers', 'taxgroups.taxgroupid=suppliers.taxgroupid'),
('supptrans', 'systypes', 'supptrans.type=systypes.typeid'),
('systypes', 'supptrans', 'systypes.typeid=supptrans.type'),
('supptrans', 'suppliers', 'supptrans.supplierno=suppliers.supplierid'),
('suppliers', 'supptrans', 'suppliers.supplierid=supptrans.supplierno'),
('supptranstaxes', 'taxauthorities', 'supptranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'supptranstaxes', 'taxauthorities.taxid=supptranstaxes.taxauthid'),
('supptranstaxes', 'supptrans', 'supptranstaxes.supptransid=supptrans.id'),
('supptrans', 'supptranstaxes', 'supptrans.id=supptranstaxes.supptransid'),
('taxauthorities', 'chartmaster', 'taxauthorities.taxglcode=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.taxglcode'),
('taxauthorities', 'chartmaster', 'taxauthorities.purchtaxglaccount=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.purchtaxglaccount'),
('taxauthrates', 'taxauthorities', 'taxauthrates.taxauthority=taxauthorities.taxid'),
('taxauthorities', 'taxauthrates', 'taxauthorities.taxid=taxauthrates.taxauthority'),
('taxauthrates', 'taxcategories', 'taxauthrates.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'taxauthrates', 'taxcategories.taxcatid=taxauthrates.taxcatid'),
('taxauthrates', 'taxprovinces', 'taxauthrates.dispatchtaxprovince=taxprovinces.taxprovinceid'),
('taxprovinces', 'taxauthrates', 'taxprovinces.taxprovinceid=taxauthrates.dispatchtaxprovince'),
('taxgrouptaxes', 'taxgroups', 'taxgrouptaxes.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'taxgrouptaxes', 'taxgroups.taxgroupid=taxgrouptaxes.taxgroupid'),
('taxgrouptaxes', 'taxauthorities', 'taxgrouptaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'taxgrouptaxes', 'taxauthorities.taxid=taxgrouptaxes.taxauthid'),
('workcentres', 'locations', 'workcentres.location=locations.loccode'),
('locations', 'workcentres', 'locations.loccode=workcentres.location'),
('worksorders', 'locations', 'worksorders.loccode=locations.loccode'),
('locations', 'worksorders', 'locations.loccode=worksorders.loccode'),
('worksorders', 'stockmaster', 'worksorders.stockid=stockmaster.stockid'),
('stockmaster', 'worksorders', 'stockmaster.stockid=worksorders.stockid'),
('www_users', 'locations', 'www_users.defaultlocation=locations.loccode'),
('locations', 'www_users', 'locations.loccode=www_users.defaultlocation');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `reportname` varchar(30) NOT NULL DEFAULT '',
  `reporttype` char(3) NOT NULL DEFAULT 'rpt',
  `groupname` varchar(9) NOT NULL DEFAULT 'misc',
  `defaultreport` enum('1','0') NOT NULL DEFAULT '0',
  `papersize` varchar(15) NOT NULL DEFAULT 'A4,210,297',
  `paperorientation` enum('P','L') NOT NULL DEFAULT 'P',
  `margintop` int(3) NOT NULL DEFAULT '10',
  `marginbottom` int(3) NOT NULL DEFAULT '10',
  `marginleft` int(3) NOT NULL DEFAULT '10',
  `marginright` int(3) NOT NULL DEFAULT '10',
  `coynamefont` varchar(20) NOT NULL DEFAULT 'Helvetica',
  `coynamefontsize` int(3) NOT NULL DEFAULT '12',
  `coynamefontcolor` varchar(11) NOT NULL DEFAULT '0,0,0',
  `coynamealign` enum('L','C','R') NOT NULL DEFAULT 'C',
  `coynameshow` enum('1','0') NOT NULL DEFAULT '1',
  `title1desc` varchar(50) NOT NULL DEFAULT '%reportname%',
  `title1font` varchar(20) NOT NULL DEFAULT 'Helvetica',
  `title1fontsize` int(3) NOT NULL DEFAULT '10',
  `title1fontcolor` varchar(11) NOT NULL DEFAULT '0,0,0',
  `title1fontalign` enum('L','C','R') NOT NULL DEFAULT 'C',
  `title1show` enum('1','0') NOT NULL DEFAULT '1',
  `title2desc` varchar(50) NOT NULL DEFAULT 'Report Generated %date%',
  `title2font` varchar(20) NOT NULL DEFAULT 'Helvetica',
  `title2fontsize` int(3) NOT NULL DEFAULT '10',
  `title2fontcolor` varchar(11) NOT NULL DEFAULT '0,0,0',
  `title2fontalign` enum('L','C','R') NOT NULL DEFAULT 'C',
  `title2show` enum('1','0') NOT NULL DEFAULT '1',
  `filterfont` varchar(10) NOT NULL DEFAULT 'Helvetica',
  `filterfontsize` int(3) NOT NULL DEFAULT '8',
  `filterfontcolor` varchar(11) NOT NULL DEFAULT '0,0,0',
  `filterfontalign` enum('L','C','R') NOT NULL DEFAULT 'L',
  `datafont` varchar(10) NOT NULL DEFAULT 'Helvetica',
  `datafontsize` int(3) NOT NULL DEFAULT '10',
  `datafontcolor` varchar(10) NOT NULL DEFAULT 'black',
  `datafontalign` enum('L','C','R') NOT NULL DEFAULT 'L',
  `totalsfont` varchar(10) NOT NULL DEFAULT 'Helvetica',
  `totalsfontsize` int(3) NOT NULL DEFAULT '10',
  `totalsfontcolor` varchar(11) NOT NULL DEFAULT '0,0,0',
  `totalsfontalign` enum('L','C','R') NOT NULL DEFAULT 'L',
  `col1width` int(3) NOT NULL DEFAULT '25',
  `col2width` int(3) NOT NULL DEFAULT '25',
  `col3width` int(3) NOT NULL DEFAULT '25',
  `col4width` int(3) NOT NULL DEFAULT '25',
  `col5width` int(3) NOT NULL DEFAULT '25',
  `col6width` int(3) NOT NULL DEFAULT '25',
  `col7width` int(3) NOT NULL DEFAULT '25',
  `col8width` int(3) NOT NULL DEFAULT '25',
  `col9width` int(3) NOT NULL DEFAULT '25',
  `col10width` int(3) NOT NULL DEFAULT '25',
  `col11width` int(3) NOT NULL DEFAULT '25',
  `col12width` int(3) NOT NULL DEFAULT '25',
  `col13width` int(3) NOT NULL DEFAULT '25',
  `col14width` int(3) NOT NULL DEFAULT '25',
  `col15width` int(3) NOT NULL DEFAULT '25',
  `col16width` int(3) NOT NULL DEFAULT '25',
  `col17width` int(3) NOT NULL DEFAULT '25',
  `col18width` int(3) NOT NULL DEFAULT '25',
  `col19width` int(3) NOT NULL DEFAULT '25',
  `col20width` int(3) NOT NULL DEFAULT '25',
  `table1` varchar(25) NOT NULL DEFAULT '',
  `table2` varchar(25) DEFAULT NULL,
  `table2criteria` varchar(75) DEFAULT NULL,
  `table3` varchar(25) DEFAULT NULL,
  `table3criteria` varchar(75) DEFAULT NULL,
  `table4` varchar(25) DEFAULT NULL,
  `table4criteria` varchar(75) DEFAULT NULL,
  `table5` varchar(25) DEFAULT NULL,
  `table5criteria` varchar(75) DEFAULT NULL,
  `table6` varchar(25) DEFAULT NULL,
  `table6criteria` varchar(75) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`reportname`,`groupname`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=136 ;

-- --------------------------------------------------------

--
-- Table structure for table `salesanalysis`
--

CREATE TABLE IF NOT EXISTS `salesanalysis` (
  `typeabbrev` char(2) NOT NULL DEFAULT '',
  `periodno` smallint(6) NOT NULL DEFAULT '0',
  `amt` double NOT NULL DEFAULT '0',
  `cost` double NOT NULL DEFAULT '0',
  `cust` varchar(10) NOT NULL DEFAULT '',
  `custbranch` varchar(10) NOT NULL DEFAULT '',
  `qty` double NOT NULL DEFAULT '0',
  `disc` double NOT NULL DEFAULT '0',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `area` varchar(3) NOT NULL,
  `budgetoractual` tinyint(1) NOT NULL DEFAULT '0',
  `salesperson` char(3) NOT NULL DEFAULT '',
  `stkcategory` varchar(6) NOT NULL DEFAULT '',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `CustBranch` (`custbranch`),
  KEY `Cust` (`cust`),
  KEY `PeriodNo` (`periodno`),
  KEY `StkCategory` (`stkcategory`),
  KEY `StockID` (`stockid`),
  KEY `TypeAbbrev` (`typeabbrev`),
  KEY `Area` (`area`),
  KEY `BudgetOrActual` (`budgetoractual`),
  KEY `Salesperson` (`salesperson`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `salescat`
--

CREATE TABLE IF NOT EXISTS `salescat` (
  `salescatid` tinyint(4) NOT NULL AUTO_INCREMENT,
  `parentcatid` tinyint(4) DEFAULT NULL,
  `salescatname` varchar(50) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1' COMMENT '1 if active 0 if inactive',
  PRIMARY KEY (`salescatid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `salescatprod`
--

CREATE TABLE IF NOT EXISTS `salescatprod` (
  `salescatid` tinyint(4) NOT NULL DEFAULT '0',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `manufacturers_id` int(11) NOT NULL,
  `featured` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`salescatid`,`stockid`),
  KEY `salescatid` (`salescatid`),
  KEY `stockid` (`stockid`),
  KEY `manufacturer_id` (`manufacturers_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `salescattranslations`
--

CREATE TABLE IF NOT EXISTS `salescattranslations` (
  `salescatid` tinyint(4) NOT NULL DEFAULT '0',
  `language_id` varchar(10) NOT NULL DEFAULT 'en_GB.utf8',
  `salescattranslation` varchar(40) NOT NULL,
  PRIMARY KEY (`salescatid`,`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `salesglpostings`
--

CREATE TABLE IF NOT EXISTS `salesglpostings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `area` varchar(3) NOT NULL,
  `stkcat` varchar(6) NOT NULL DEFAULT '',
  `discountglcode` varchar(20) NOT NULL DEFAULT '0',
  `salesglcode` varchar(20) NOT NULL DEFAULT '0',
  `salestype` char(2) NOT NULL DEFAULT 'AN',
  PRIMARY KEY (`id`),
  UNIQUE KEY `Area_StkCat` (`area`,`stkcat`,`salestype`),
  KEY `Area` (`area`),
  KEY `StkCat` (`stkcat`),
  KEY `SalesType` (`salestype`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `salesglpostings`
--

INSERT INTO `salesglpostings` (`id`, `area`, `stkcat`, `discountglcode`, `salesglcode`, `salestype`) VALUES
(1, 'AN', 'ANY', '4900', '4100', 'AN'),
(2, 'AN', 'AIRCON', '5000', '4800', 'DE'),
(3, 'AN', 'ZPAYT', '7230', '7230', 'AN');

-- --------------------------------------------------------

--
-- Table structure for table `salesman`
--

CREATE TABLE IF NOT EXISTS `salesman` (
  `salesmancode` varchar(4) NOT NULL DEFAULT '',
  `salesmanname` char(30) NOT NULL DEFAULT '',
  `smantel` char(20) NOT NULL DEFAULT '',
  `smanfax` char(20) NOT NULL DEFAULT '',
  `commissionrate1` double NOT NULL DEFAULT '0',
  `breakpoint` decimal(10,0) NOT NULL DEFAULT '0',
  `commissionrate2` double NOT NULL DEFAULT '0',
  `current` tinyint(4) NOT NULL COMMENT 'Salesman current (1) or not (0)',
  PRIMARY KEY (`salesmancode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `salesorderdetails`
--

CREATE TABLE IF NOT EXISTS `salesorderdetails` (
  `orderlineno` int(11) NOT NULL DEFAULT '0',
  `orderno` int(11) NOT NULL DEFAULT '0',
  `stkcode` varchar(20) NOT NULL DEFAULT '',
  `qtyinvoiced` double NOT NULL DEFAULT '0',
  `unitprice` double NOT NULL DEFAULT '0',
  `quantity` double NOT NULL DEFAULT '0',
  `estimate` tinyint(4) NOT NULL DEFAULT '0',
  `discountpercent` double NOT NULL DEFAULT '0',
  `actualdispatchdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  `narrative` text,
  `itemdue` date DEFAULT NULL COMMENT 'Due date for line item.  Some customers require \r\nacknowledgements with due dates by line item',
  `poline` varchar(10) DEFAULT NULL COMMENT 'Some Customers require acknowledgements with a PO line number for each sales line',
  PRIMARY KEY (`orderlineno`,`orderno`),
  KEY `OrderNo` (`orderno`),
  KEY `StkCode` (`stkcode`),
  KEY `Completed` (`completed`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `salesorders`
--

CREATE TABLE IF NOT EXISTS `salesorders` (
  `orderno` int(11) NOT NULL,
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `branchcode` varchar(10) NOT NULL DEFAULT '',
  `customerref` varchar(50) NOT NULL DEFAULT '',
  `buyername` varchar(50) DEFAULT NULL,
  `comments` longblob,
  `orddate` date NOT NULL DEFAULT '0000-00-00',
  `ordertype` char(2) NOT NULL DEFAULT '',
  `shipvia` int(11) NOT NULL DEFAULT '0',
  `deladd1` varchar(40) NOT NULL DEFAULT '',
  `deladd2` varchar(40) NOT NULL DEFAULT '',
  `deladd3` varchar(40) NOT NULL DEFAULT '',
  `deladd4` varchar(40) DEFAULT NULL,
  `deladd5` varchar(20) NOT NULL DEFAULT '',
  `deladd6` varchar(15) NOT NULL DEFAULT '',
  `contactphone` varchar(25) DEFAULT NULL,
  `contactemail` varchar(40) DEFAULT NULL,
  `deliverto` varchar(40) NOT NULL DEFAULT '',
  `deliverblind` tinyint(1) DEFAULT '1',
  `freightcost` double NOT NULL DEFAULT '0',
  `fromstkloc` varchar(5) NOT NULL DEFAULT '',
  `deliverydate` date NOT NULL DEFAULT '0000-00-00',
  `confirmeddate` date NOT NULL DEFAULT '0000-00-00',
  `printedpackingslip` tinyint(4) NOT NULL DEFAULT '0',
  `datepackingslipprinted` date NOT NULL DEFAULT '0000-00-00',
  `quotation` tinyint(4) NOT NULL DEFAULT '0',
  `quotedate` date NOT NULL DEFAULT '0000-00-00',
  `poplaced` tinyint(4) NOT NULL DEFAULT '0',
  `salesperson` varchar(4) NOT NULL,
  PRIMARY KEY (`orderno`),
  KEY `DebtorNo` (`debtorno`),
  KEY `OrdDate` (`orddate`),
  KEY `OrderType` (`ordertype`),
  KEY `LocationIndex` (`fromstkloc`),
  KEY `BranchCode` (`branchcode`,`debtorno`),
  KEY `ShipVia` (`shipvia`),
  KEY `quotation` (`quotation`),
  KEY `poplaced` (`poplaced`),
  KEY `salesperson` (`salesperson`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `salestypes`
--

CREATE TABLE IF NOT EXISTS `salestypes` (
  `typeabbrev` char(2) NOT NULL DEFAULT '',
  `sales_type` varchar(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`typeabbrev`),
  KEY `Sales_Type` (`sales_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sampleresults`
--

CREATE TABLE IF NOT EXISTS `sampleresults` (
  `resultid` bigint(20) NOT NULL AUTO_INCREMENT,
  `sampleid` int(11) NOT NULL,
  `testid` int(11) NOT NULL,
  `defaultvalue` varchar(150) NOT NULL,
  `targetvalue` varchar(30) NOT NULL,
  `rangemin` float DEFAULT NULL,
  `rangemax` float DEFAULT NULL,
  `testvalue` varchar(30) NOT NULL DEFAULT '',
  `testdate` date NOT NULL DEFAULT '0000-00-00',
  `testedby` varchar(15) NOT NULL DEFAULT '',
  `comments` varchar(255) NOT NULL DEFAULT '',
  `isinspec` tinyint(4) NOT NULL DEFAULT '0',
  `showoncert` tinyint(4) NOT NULL DEFAULT '1',
  `showontestplan` tinyint(4) NOT NULL DEFAULT '1',
  `manuallyadded` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`resultid`),
  KEY `sampleid` (`sampleid`),
  KEY `testid` (`testid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `scripts`
--

CREATE TABLE IF NOT EXISTS `scripts` (
  `script` varchar(78) NOT NULL DEFAULT '',
  `pagesecurity` int(11) NOT NULL DEFAULT '1',
  `description` text NOT NULL,
  PRIMARY KEY (`script`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `scripts`
--

INSERT INTO `scripts` (`script`, `pagesecurity`, `description`) VALUES
('AccountGroups.php', 10, 'Defines the groupings of general ledger accounts'),
('AccountSections.php', 10, 'Defines the sections in the general ledger reports'),
('AddCustomerContacts.php', 3, 'Adds customer contacts'),
('AddCustomerNotes.php', 3, 'Adds notes about customers'),
('AddCustomerTypeNotes.php', 3, ''),
('AgedControlledInventory.php', 11, 'Report of Controlled Items and their age'),
('AgedDebtors.php', 2, 'Lists customer account balances in detail or summary in selected currency'),
('AgedSuppliers.php', 2, 'Lists supplier account balances in detail or summary in selected currency'),
('Areas.php', 3, 'Defines the sales areas - all customers must belong to a sales area for the purposes of sales analysis'),
('AuditTrail.php', 15, 'Shows the activity with SQL statements and who performed the changes'),
('AutomaticTranslationDescriptions.php', 15, 'Translates via Google Translator all empty translated descriptions'),
('BankAccounts.php', 10, 'Defines the general ledger code for bank accounts and specifies that bank transactions be created for these accounts for the purposes of reconciliation'),
('BankAccountUsers.php', 15, 'Maintains table bankaccountusers (Authorized users to work with a bank account in webERP)'),
('BankMatching.php', 7, 'Allows payments and receipts to be matched off against bank statements'),
('BankReconciliation.php', 7, 'Displays the bank reconciliation for a selected bank account'),
('BOMExtendedQty.php', 2, 'Shows the component requirements to make an item'),
('BOMIndented.php', 2, 'Shows the bill of material indented for each level'),
('BOMIndentedReverse.php', 2, ''),
('BOMInquiry.php', 2, 'Displays the bill of material with cost information'),
('BOMListing.php', 2, 'Lists the bills of material for a selected range of items'),
('BOMs.php', 9, 'Administers the bills of material for a selected item'),
('COGSGLPostings.php', 10, 'Defines the general ledger account to be used for cost of sales entries'),
('CompanyPreferences.php', 10, 'Defines the settings applicable for the company, including name, address, tax authority reference, whether GL integration used etc.'),
('ConfirmDispatchControlled_Invoice.php', 2, 'Specifies the batch references/serial numbers of items dispatched that are being invoiced'),
('ConfirmDispatch_Invoice.php', 2, 'Creates sales invoices from entered sales orders based on the quantities dispatched that can be modified'),
('ContractBOM.php', 6, 'Creates the item requirements from stock for a contract as part of the contract cost build up'),
('ContractCosting.php', 6, 'Shows a contract cost - the components and other non-stock costs issued to the contract'),
('ContractOtherReqts.php', 4, 'Creates the other requirements for a contract cost build up'),
('Contracts.php', 6, 'Creates or modifies a customer contract costing'),
('CopyBOM.php', 9, 'Allows a bill of material to be copied between items'),
('CostUpdate', 10, 'NB Not a script but allows users to maintain item costs from withing StockCostUpdate.php'),
('CounterReturns.php', 5, 'Allows credits and refunds from the default Counter Sale account for an inventory location'),
('CounterSales.php', 1, 'Allows sales to be entered against a cash sale customer account defined in the users location record'),
('CreditItemsControlled.php', 3, 'Specifies the batch references/serial numbers of items being credited back into stock'),
('CreditStatus.php', 3, 'Defines the credit status records. Each customer account is given a credit status from this table. Some credit status records can prohibit invoicing and new orders being entered.'),
('Credit_Invoice.php', 3, 'Creates a credit note based on the details of an existing invoice'),
('Currencies.php', 9, 'Defines the currencies available. Each customer and supplier must be defined as transacting in one of the currencies defined here.'),
('CustEDISetup.php', 11, 'Allows the set up the customer specified EDI parameters for server, email or ftp.'),
('CustItem.php', 11, 'Customer Items'),
('CustLoginSetup.php', 15, ''),
('CustomerAllocations.php', 3, 'Allows customer receipts and credit notes to be allocated to sales invoices'),
('CustomerBalancesMovement.php', 3, 'Allow customers to be listed in local currency with balances and activity over a date range'),
('CustomerBranches.php', 3, 'Defines the details of customer branches such as delivery address and contact details - also sales area, representative etc'),
('CustomerInquiry.php', 1, 'Shows the customers account transactions with balances outstanding, links available to drill down to invoice/credit note or email invoices/credit notes'),
('CustomerPurchases.php', 5, 'Shows the purchases a customer has made.'),
('CustomerReceipt.php', 3, 'Entry of both customer receipts against accounts receivable and also general ledger or nominal receipts'),
('Customers.php', 3, 'Defines the setup of a customer account, including payment terms, billing address, credit status, currency etc'),
('CustomerTransInquiry.php', 2, 'Lists in html the sequence of customer transactions, invoices, credit notes or receipts by a user entered date range'),
('CustomerTypes.php', 15, ''),
('CustWhereAlloc.php', 2, 'Shows to which invoices a receipt was allocated to'),
('DailyBankTransactions.php', 8, 'Allows you to view all bank transactions for a selected date range, and the inquiry can be filtered by matched or unmatched transactions, or all transactions can be chosen'),
('DailySalesInquiry.php', 2, 'Shows the daily sales with GP in a calendar format'),
('Dashboard.php', 1, 'Display outstanding debtors, creditors etc'),
('DebtorsAtPeriodEnd.php', 2, 'Shows the debtors control account as at a previous period end - based on system calendar monthly periods'),
('DeliveryDetails.php', 1, 'Used during order entry to allow the entry of delivery addresses other than the defaulted branch delivery address and information about carrier/shipping method etc'),
('Departments.php', 1, 'Create business departments'),
('DiscountCategories.php', 11, 'Defines the items belonging to a discount category. Discount Categories are used to allow discounts based on quantities across a range of producs'),
('DiscountMatrix.php', 11, 'Defines the rates of discount applicable to discount categories and the customer groupings to which the rates are to apply'),
('EDIMessageFormat.php', 10, 'Specifies the EDI message format used by a customer - administrator use only.'),
('EDIProcessOrders.php', 11, 'Processes incoming EDI orders into sales orders'),
('EDISendInvoices.php', 15, 'Processes invoiced EDI customer invoices into EDI messages and sends using the customers preferred method either ftp or email attachments.'),
('EmailConfirmation.php', 2, ''),
('EmailCustTrans.php', 2, 'Emails selected invoice or credit to the customer'),
('ExchangeRateTrend.php', 2, 'Shows the trend in exchange rates as retrieved from ECB'),
('Factors.php', 5, 'Defines supplier factor companies'),
('FixedAssetCategories.php', 11, 'Defines the various categories of fixed assets'),
('FixedAssetDepreciation.php', 10, 'Calculates and creates GL transactions to post depreciation for a period'),
('FixedAssetItems.php', 11, 'Allows fixed assets to be defined'),
('FixedAssetLocations.php', 11, 'Allows the locations of fixed assets to be defined'),
('FixedAssetRegister.php', 11, 'Produces a csv, html or pdf report of the fixed assets over a period showing period depreciation, additions and disposals'),
('FixedAssetTransfer.php', 11, 'Allows the fixed asset locations to be changed in bulk'),
('FormDesigner.php', 14, ''),
('FormMaker.php', 1, 'Allows running user defined Forms'),
('FreightCosts.php', 11, 'Defines the setup of the freight cost using different shipping methods to different destinations. The system can use this information to calculate applicable freight if the items are defined with the correct kgs and cubic volume'),
('FTP_RadioBeacon.php', 2, 'FTPs sales orders for dispatch to a radio beacon software enabled warehouse dispatching facility'),
('geocode.php', 3, ''),
('GeocodeSetup.php', 3, ''),
('geocode_genxml_customers.php', 3, ''),
('geocode_genxml_suppliers.php', 3, ''),
('geo_displaymap_customers.php', 3, ''),
('geo_displaymap_suppliers.php', 3, ''),
('GetStockImage.php', 1, ''),
('GLAccountCSV.php', 8, 'Produces a CSV of the GL transactions for a particular range of periods and GL account'),
('GLAccountInquiry.php', 8, 'Shows the general ledger transactions for a specified account over a specified range of periods'),
('GLAccountReport.php', 8, 'Produces a report of the GL transactions for a particular account'),
('GLAccounts.php', 10, 'Defines the general ledger accounts'),
('GLBalanceSheet.php', 8, 'Shows the balance sheet for the company as at a specified date'),
('GLBudgets.php', 10, 'Defines GL Budgets'),
('GLCodesInquiry.php', 8, 'Shows the list of general ledger codes defined with account names and groupings'),
('GLJournal.php', 10, 'Entry of general ledger journals, periods are calculated based on the date entered here'),
('GLJournalInquiry.php', 15, 'General Ledger Journal Inquiry'),
('GLProfit_Loss.php', 8, 'Shows the profit and loss of the company for the range of periods entered'),
('GLTagProfit_Loss.php', 8, ''),
('GLTags.php', 10, 'Allows GL tags to be defined'),
('GLTransInquiry.php', 8, 'Shows the general ledger journal created for the sub ledger transaction specified'),
('GLTrialBalance.php', 8, 'Shows the trial balance for the month and the for the period selected together with the budgeted trial balances'),
('GLTrialBalance_csv.php', 8, 'Produces a CSV of the Trial Balance for a particular period'),
('GoodsReceived.php', 11, 'Entry of items received against purchase orders'),
('GoodsReceivedControlled.php', 11, 'Entry of the serial numbers or batch references for controlled items received against purchase orders'),
('GoodsReceivedNotInvoiced.php', 2, 'Shows the list of goods received but not yet invoiced, both in supplier currency and home currency. Total in home curency should match the GL Account for Goods received not invoiced. Any discrepancy is due to multicurrency errors.'),
('HistoricalTestResults.php', 16, 'Historical Test Results'),
('ImportBankTrans.php', 11, 'Imports bank transactions'),
('ImportBankTransAnalysis.php', 11, 'Allows analysis of bank transactions being imported'),
('index.php', 1, 'The main menu from where all functions available to the user are accessed by clicking on the links'),
('InternalStockCategoriesByRole.php', 15, 'Maintains the stock categories to be used as internal for any user security role'),
('InternalStockRequest.php', 1, 'Create an internal stock request'),
('InternalStockRequestAuthorisation.php', 1, 'Authorise internal stock requests'),
('InternalStockRequestFulfill.php', 1, 'Fulfill an internal stock request'),
('InventoryPlanning.php', 2, 'Creates a pdf report showing the last 4 months use of items including as a component of assemblies together with stock quantity on hand, current demand for the item and current quantity on sales order.'),
('InventoryPlanningPrefSupplier.php', 2, 'Produces a report showing the inventory to be ordered by supplier'),
('InventoryPlanningPrefSupplier_CSV.php', 2, 'Inventory planning spreadsheet'),
('InventoryQuantities.php', 2, ''),
('InventoryValuation.php', 2, 'Creates a pdf report showing the value of stock at standard cost for a range of product categories selected'),
('Labels.php', 15, 'Produces item pricing labels in a pdf from a range of selected criteria'),
('Locations.php', 11, 'Defines the inventory stocking locations or warehouses'),
('LocationUsers.php', 15, 'Allows users that have permission to access a location to be defined'),
('Logout.php', 1, 'Shows when the user logs out of webERP'),
('MailingGroupMaintenance.php', 15, 'Mainting mailing lists for items to mail'),
('MailInventoryValuation.php', 1, 'Meant to be run as a scheduled process to email the stock valuation off to a specified person. Creates the same stock valuation report as InventoryValuation.php'),
('MailSalesReport_csv.php', 15, 'Mailing the sales report'),
('MaintenanceReminders.php', 1, 'Sends email reminders for scheduled asset maintenance tasks'),
('MaintenanceTasks.php', 1, 'Allows set up and edit of scheduled maintenance tasks'),
('MaintenanceUserSchedule.php', 1, 'List users or managers scheduled maintenance tasks and allow to be flagged as completed'),
('Manufacturers.php', 15, 'Maintain brands of sales products'),
('MaterialsNotUsed.php', 4, 'Lists the items from Raw Material Categories not used in any BOM (thus, not used at all)'),
('MRP.php', 9, ''),
('MRPCalendar.php', 9, ''),
('MRPCreateDemands.php', 9, ''),
('MRPDemands.php', 9, ''),
('MRPDemandTypes.php', 9, ''),
('MRPPlannedPurchaseOrders.php', 2, ''),
('MRPPlannedWorkOrders.php', 2, ''),
('MRPReport.php', 2, ''),
('MRPReschedules.php', 2, ''),
('MRPShortages.php', 2, ''),
('NoSalesItems.php', 2, 'Shows the No Selling (worst) items'),
('OffersReceived.php', 4, ''),
('OrderDetails.php', 1, 'Shows the detail of a sales order'),
('OrderEntryDiscountPricing', 13, 'Not a script but an authority level marker - required if the user is allowed to enter discounts and special pricing against a customer order'),
('OutstandingGRNs.php', 2, 'Creates a pdf showing all GRNs for which there has been no purchase invoice matched off against.'),
('PageSecurity.php', 15, ''),
('PaymentAllocations.php', 5, ''),
('PaymentMethods.php', 15, ''),
('Payments.php', 5, 'Entry of bank account payments either against an AP account or a general ledger payment - if the AP-GL link in company preferences is set'),
('PaymentTerms.php', 10, 'Defines the payment terms records, these can be expressed as either a number of days credit or a day in the following month. All customers and suppliers must have a corresponding payment term recorded against their account'),
('PcAssignCashToTab.php', 6, ''),
('PcAuthorizeExpenses.php', 6, ''),
('PcClaimExpensesFromTab.php', 6, ''),
('PcExpenses.php', 15, ''),
('PcExpensesTypeTab.php', 15, ''),
('PcReportTab.php', 6, ''),
('PcTabs.php', 15, ''),
('PcTypeTabs.php', 15, ''),
('PDFBankingSummary.php', 3, 'Creates a pdf showing the amounts entered as receipts on a specified date together with references for the purposes of banking'),
('PDFChequeListing.php', 3, 'Creates a pdf showing all payments that have been made from a specified bank account over a specified period. This can be emailed to an email account defined in config.php - ie a financial controller'),
('PDFCOA.php', 0, 'PDF of COA'),
('PDFCustomerList.php', 2, 'Creates a report of the customer and branch information held. This report has options to print only customer branches in a specified sales area and sales person. Additional option allows to list only those customers with activity either under or over a specified amount, since a specified date.'),
('PDFCustTransListing.php', 3, ''),
('PDFDeliveryDifferences.php', 3, 'Creates a pdf report listing the delivery differences from what the customer requested as recorded in the order entry. The report calculates a percentage of order fill based on the number of orders filled in full on time'),
('PDFDIFOT.php', 3, 'Produces a pdf showing the delivery in full on time performance'),
('PDFFGLabel.php', 11, 'Produces FG Labels'),
('PDFGLJournal.php', 15, 'General Ledger Journal Print'),
('PDFGrn.php', 2, 'Produces a GRN report on the receipt of stock'),
('PDFLowGP.php', 2, 'Creates a pdf report showing the low gross profit sales made in the selected date range. The percentage of gp deemed acceptable can also be entered'),
('PDFOrdersInvoiced.php', 3, 'Produces a pdf of orders invoiced based on selected criteria'),
('PDFOrderStatus.php', 3, 'Reports on sales order status by date range, by stock location and stock category - producing a pdf showing each line items and any quantites delivered'),
('PDFPeriodStockTransListing.php', 3, 'Allows stock transactions of a specific transaction type to be listed over a single day or period range'),
('PDFPickingList.php', 2, ''),
('PDFPriceList.php', 2, 'Creates a pdf of the price list applicable to a given sales type and customer. Also allows the listing of prices specific to a customer'),
('PDFPrintLabel.php', 10, ''),
('PDFProdSpec.php', 0, 'PDF OF Product Specification'),
('PDFQALabel.php', 2, 'Produces a QA label on receipt of stock'),
('PDFQuotation.php', 2, ''),
('PDFQuotationPortrait.php', 2, 'Portrait quotation'),
('PDFReceipt.php', 2, ''),
('PDFRemittanceAdvice.php', 2, ''),
('PDFSellThroughSupportClaim.php', 9, 'Reports the sell through support claims to be made against all suppliers for a given date range.'),
('PDFStockCheckComparison.php', 2, 'Creates a pdf comparing the quantites entered as counted at a given range of locations against the quantity stored as on hand as at the time a stock check was initiated.'),
('PDFStockLocTransfer.php', 1, 'Creates a stock location transfer docket for the selected location transfer reference number'),
('PDFStockNegatives.php', 1, 'Produces a pdf of the negative stocks by location'),
('PDFStockTransfer.php', 2, 'Produces a report for stock transfers'),
('PDFSuppTransListing.php', 3, ''),
('PDFTestPlan.php', 16, 'PDF of Test Plan'),
('PDFTopItems.php', 2, 'Produces a pdf report of the top items sold'),
('PDFWOPrint.php', 11, 'Produces W/O Paperwork'),
('PeriodsInquiry.php', 2, 'Shows a list of all the system defined periods'),
('POReport.php', 2, ''),
('PO_AuthorisationLevels.php', 15, ''),
('PO_AuthoriseMyOrders.php', 4, ''),
('PO_Header.php', 4, 'Entry of a purchase order header record - date, references buyer etc'),
('PO_Items.php', 4, 'Entry of a purchase order items - allows entry of items with lookup of currency cost from Purchasing Data previously entered also allows entry of nominal items against a general ledger code if the AP is integrated to the GL'),
('PO_OrderDetails.php', 2, 'Purchase order inquiry shows the quantity received and invoiced of purchase order items as well as the header information'),
('PO_PDFPurchOrder.php', 2, 'Creates a pdf of the selected purchase order for printing or email to one of the supplier contacts entered'),
('PO_SelectOSPurchOrder.php', 2, 'Shows the outstanding purchase orders for selecting with links to receive or modify the purchase order header and items'),
('PO_SelectPurchOrder.php', 2, 'Allows selection of any purchase order with links to the inquiry'),
('PriceMatrix.php', 11, 'Mantain stock prices according to quantity break and sales types'),
('Prices.php', 9, 'Entry of prices for a selected item also allows selection of sales type and currency for the price'),
('PricesBasedOnMarkUp.php', 11, ''),
('PricesByCost.php', 11, 'Allows prices to be updated based on cost'),
('Prices_Customer.php', 11, 'Entry of prices for a selected item and selected customer/branch. The currency and sales type is defaulted from the customer''s record'),
('PrintCheque.php', 5, ''),
('PrintCustOrder.php', 2, 'Creates a pdf of the dispatch note - by default this is expected to be on two part pre-printed stationery to allow pickers to note discrepancies for the confirmer to update the dispatch at the time of invoicing'),
('PrintCustOrder_generic.php', 2, 'Creates two copies of a laser printed dispatch note - both copies need to be written on by the pickers with any discrepancies to advise customer of any shortfall and on the office copy to ensure the correct quantites are invoiced'),
('PrintCustStatements.php', 2, 'Creates a pdf for the customer statements in the selected range'),
('PrintCustTrans.php', 1, 'Creates either a html invoice or credit note or a pdf. A range of invoices or credit notes can be selected also.'),
('PrintCustTransPortrait.php', 1, ''),
('PrintSalesOrder_generic.php', 2, ''),
('PrintWOItemSlip.php', 4, 'PDF WO Item production Slip '),
('ProductSpecs.php', 16, 'Product Specification Maintenance'),
('PurchaseByPrefSupplier.php', 2, 'Purchase ordering by preferred supplier'),
('PurchData.php', 4, 'Entry of supplier purchasing data, the suppliers part reference and the suppliers currency cost of the item'),
('QATests.php', 16, 'Quality Test Maintenance'),
('RecurringSalesOrders.php', 1, ''),
('RecurringSalesOrdersProcess.php', 1, 'Process Recurring Sales Orders'),
('RelatedItemsUpdate.php', 2, 'Maintains Related Items'),
('ReorderLevel.php', 2, 'Allows reorder levels of inventory to be updated'),
('ReorderLevelLocation.php', 2, ''),
('ReportCreator.php', 13, 'Report Writer and Form Creator script that creates templates for user defined reports and forms'),
('ReportMaker.php', 1, 'Produces reports from the report writer templates created'),
('reportwriter/admin/ReportCreator.php', 15, 'Report Writer'),
('ReprintGRN.php', 11, 'Allows selection of a goods received batch for reprinting the goods received note given a purchase order number'),
('ReverseGRN.php', 11, 'Reverses the entry of goods received - creating stock movements back out and necessary general ledger journals to effect the reversal'),
('RevisionTranslations.php', 15, 'Human revision for automatic descriptions translations'),
('SalesAnalReptCols.php', 2, 'Entry of the definition of a sales analysis report''s columns.'),
('SalesAnalRepts.php', 2, 'Entry of the definition of a sales analysis report headers'),
('SalesAnalysis_UserDefined.php', 2, 'Creates a pdf of a selected user defined sales analysis report'),
('SalesByTypePeriodInquiry.php', 2, 'Shows sales for a selected date range by sales type/price list'),
('SalesCategories.php', 11, ''),
('SalesCategoryDescriptions.php', 15, 'Maintain translations for sales categories'),
('SalesCategoryPeriodInquiry.php', 2, 'Shows sales for a selected date range by stock category'),
('SalesGLPostings.php', 10, 'Defines the general ledger accounts used to post sales to based on product categories and sales areas'),
('SalesGraph.php', 6, ''),
('SalesInquiry.php', 2, ''),
('SalesPeople.php', 3, 'Defines the sales people of the business'),
('SalesTopCustomersInquiry.php', 1, 'Shows the top customers'),
('SalesTopItemsInquiry.php', 2, 'Shows the top item sales for a selected date range'),
('SalesTypes.php', 15, 'Defines the sales types - prices are held against sales types they can be considered price lists. Sales analysis records are held by sales type too.'),
('SecurityTokens.php', 15, 'Administration of security tokens'),
('SelectAsset.php', 2, 'Allows a fixed asset to be selected for modification or viewing'),
('SelectCompletedOrder.php', 1, 'Allows the selection of completed sales orders for inquiries - choices to select by item code or customer'),
('SelectContract.php', 6, 'Allows a contract costing to be selected for modification or viewing'),
('SelectCreditItems.php', 3, 'Entry of credit notes from scratch, selecting the items in either quick entry mode or searching for them manually'),
('SelectCustomer.php', 2, 'Selection of customer - from where all customer related maintenance, transactions and inquiries start'),
('SelectGLAccount.php', 8, 'Selection of general ledger account from where all general ledger account maintenance, or inquiries are initiated'),
('SelectOrderItems.php', 1, 'Entry of sales order items with both quick entry and part search functions'),
('SelectProduct.php', 2, 'Selection of items. All item maintenance, transactions and inquiries start with this script'),
('SelectQASamples.php', 16, 'Select  QA Samples'),
('SelectRecurringSalesOrder.php', 2, ''),
('SelectSalesOrder.php', 2, 'Selects a sales order irrespective of completed or not for inquiries'),
('SelectSupplier.php', 2, 'Selects a supplier. A supplier is required to be selected before any AP transactions and before any maintenance or inquiry of the supplier'),
('SelectWorkOrder.php', 2, ''),
('SellThroughSupport.php', 9, 'Defines the items, period and quantum of support for which supplier has agreed to provide.'),
('ShipmentCosting.php', 11, 'Shows the costing of a shipment with all the items invoice values and any shipment costs apportioned. Updating the shipment has an option to update standard costs of all items on the shipment and create any general ledger variance journals'),
('Shipments.php', 11, 'Entry of shipments from outstanding purchase orders for a selected supplier - changes in the delivery date will cascade into the different purchase orders on the shipment'),
('Shippers.php', 15, 'Defines the shipping methods available. Each customer branch has a default shipping method associated with it which must match a record from this table'),
('ShiptsList.php', 2, 'Shows a list of all the open shipments for a selected supplier. Linked from POItems.php'),
('Shipt_Select.php', 11, 'Selection of a shipment for displaying and modification or updating'),
('ShopParameters.php', 15, 'Maintain web-store configuration and set up'),
('SMTPServer.php', 15, ''),
('SpecialOrder.php', 4, 'Allows for a sales order to be created and an indent order to be created on a supplier for a one off item that may never be purchased again. A dummy part is created based on the description and cost details given.'),
('StockAdjustments.php', 11, 'Entry of quantity corrections to stocks in a selected location.'),
('StockAdjustmentsControlled.php', 11, 'Entry of batch references or serial numbers on controlled stock items being adjusted'),
('StockCategories.php', 11, 'Defines the stock categories. All items must refer to one of these categories. The category record also allows the specification of the general ledger codes where stock items are to be posted - the balance sheet account and the profit and loss effect of any adjustments and the profit and loss effect of any price variances'),
('StockCheck.php', 2, 'Allows creation of a stock check file - copying the current quantites in stock for later comparison to the entered counts. Also produces a pdf for the count sheets.'),
('StockClone.php', 11, 'Script to copy a stock item and associated properties, image, price, purchase and cost data'),
('StockCostUpdate.php', 9, 'Allows update of the standard cost of items producing general ledger journals if the company preferences stock GL interface is active'),
('StockCounts.php', 2, 'Allows entry of stock counts'),
('StockDispatch.php', 2, ''),
('StockLocMovements.php', 2, 'Inquiry shows the Movements of all stock items for a specified location'),
('StockLocStatus.php', 2, 'Shows the stock on hand together with outstanding sales orders and outstanding purchase orders by stock location for all items in the selected stock category'),
('StockLocTransfer.php', 11, 'Entry of a bulk stock location transfer for many parts from one location to another.'),
('StockLocTransferReceive.php', 11, 'Effects the transfer and creates the stock movements for a bulk stock location transfer initiated from StockLocTransfer.php'),
('StockMovements.php', 2, 'Shows a list of all the stock movements for a selected item and stock location including the price at which they were sold in local currency and the price at which they were purchased for in local currency'),
('StockQties_csv.php', 5, 'Makes a comma separated values (CSV)file of the stock item codes and quantities'),
('StockQuantityByDate.php', 2, 'Shows the stock on hand for each item at a selected location and stock category as at a specified date'),
('StockReorderLevel.php', 4, 'Entry and review of the re-order level of items by stocking location'),
('Stocks.php', 11, 'Defines an item - maintenance and addition of new parts'),
('StockSerialItemResearch.php', 3, ''),
('StockSerialItems.php', 2, 'Shows a list of the serial numbers or the batch references and quantities of controlled items. This inquiry is linked from the stock status inquiry'),
('StockStatus.php', 2, 'Shows the stock on hand together with outstanding sales orders and outstanding purchase orders by stock location for a selected part. Has a link to show the serial numbers in stock at the location selected if the item is controlled'),
('StockTransferControlled.php', 11, 'Entry of serial numbers/batch references for controlled items being received on a stock transfer. The script is used by both bulk transfers and point to point transfers'),
('StockTransfers.php', 11, 'Entry of point to point stock location transfers of a single part'),
('StockUsage.php', 2, 'Inquiry showing the quantity of stock used by period calculated from the sum of the stock movements over that period - by item and stock location. Also available over all locations'),
('StockUsageGraph.php', 2, ''),
('SuppContractChgs.php', 5, ''),
('SuppCreditGRNs.php', 5, 'Entry of a supplier credit notes (debit notes) against existing GRN which have already been matched in full or in part'),
('SuppFixedAssetChgs.php', 5, ''),
('SuppInvGRNs.php', 5, 'Entry of supplier invoices against goods received'),
('SupplierAllocations.php', 5, 'Entry of allocations of supplier payments and credit notes to invoices'),
('SupplierBalsAtPeriodEnd.php', 2, ''),
('SupplierContacts.php', 5, 'Entry of supplier contacts and contact details including email addresses'),
('SupplierCredit.php', 5, 'Entry of supplier credit notes (debit notes)'),
('SupplierInquiry.php', 2, 'Inquiry showing invoices, credit notes and payments made to suppliers together with the amounts outstanding'),
('SupplierInvoice.php', 5, 'Entry of supplier invoices'),
('SupplierPriceList.php', 4, 'Maintain Supplier Price Lists'),
('Suppliers.php', 5, 'Entry of new suppliers and maintenance of existing suppliers'),
('SupplierTenderCreate.php', 4, 'Create or Edit tenders'),
('SupplierTenders.php', 9, ''),
('SupplierTransInquiry.php', 2, ''),
('SupplierTypes.php', 4, ''),
('SuppLoginSetup.php', 15, ''),
('SuppPaymentRun.php', 5, 'Automatic creation of payment records based on calculated amounts due from AP invoices entered'),
('SuppPriceList.php', 2, ''),
('SuppShiptChgs.php', 5, 'Entry of supplier invoices against shipments as charges against a shipment'),
('SuppTransGLAnalysis.php', 5, 'Entry of supplier invoices against general ledger codes'),
('SystemParameters.php', 15, ''),
('Tax.php', 2, 'Creates a report of the ad-valoerm tax - GST/VAT - for the period selected from accounts payable and accounts receivable data'),
('TaxAuthorities.php', 15, 'Entry of tax authorities - the state intitutions that charge tax'),
('TaxAuthorityRates.php', 11, 'Entry of the rates of tax applicable to the tax authority depending on the item tax level'),
('TaxCategories.php', 15, 'Allows for categories of items to be defined that might have different tax rates applied to them'),
('TaxGroups.php', 15, 'Allows for taxes to be grouped together where multiple taxes might apply on sale or purchase of items'),
('TaxProvinces.php', 15, 'Allows for inventory locations to be defined so that tax applicable from sales in different provinces can be dealt with'),
('TestPlanResults.php', 16, 'Test Plan Results Entry'),
('TopItems.php', 2, 'Shows the top selling items'),
('UnitsOfMeasure.php', 15, 'Allows for units of measure to be defined'),
('UpgradeDatabase.php', 15, 'Allows for the database to be automatically upgraded based on currently recorded DBUpgradeNumber config option'),
('UserLocations.php', 15, 'Location User Maintenance'),
('UserSettings.php', 1, 'Allows the user to change system wide defaults for the theme - appearance, the number of records to show in searches and the language to display messages in'),
('WhereUsedInquiry.php', 2, 'Inquiry showing where an item is used ie all the parents where the item is a component of'),
('WOCanBeProducedNow.php', 4, 'List of WO items that can be produced with available stock in location'),
('WorkCentres.php', 9, 'Defines the various centres of work within a manufacturing company. Also the overhead and labour rates applicable to the work centre and its standard capacity'),
('WorkOrderCosting.php', 11, ''),
('WorkOrderEntry.php', 10, 'Entry of new work orders'),
('WorkOrderIssue.php', 11, 'Issue of materials to a work order'),
('WorkOrderReceive.php', 11, 'Allows for receiving of works orders'),
('WorkOrderStatus.php', 11, 'Shows the status of works orders'),
('WOSerialNos.php', 10, ''),
('WWW_Access.php', 15, ''),
('WWW_Users.php', 15, 'Entry of users and security settings of users'),
('Z_BottomUpCosts.php', 15, ''),
('Z_ChangeBranchCode.php', 15, 'Utility to change the branch code of a customer that cascades the change through all the necessary tables'),
('Z_ChangeCustomerCode.php', 15, 'Utility to change a customer code that cascades the change through all the necessary tables'),
('Z_ChangeGLAccountCode.php', 15, 'Script to change a GL account code accross all tables necessary'),
('Z_ChangeLocationCode.php', 15, 'Change a locations code and in all tables where the old code was used to the new code'),
('Z_ChangeStockCategory.php', 15, ''),
('Z_ChangeStockCode.php', 15, 'Utility to change an item code that cascades the change through all the necessary tables'),
('Z_ChangeSupplierCode.php', 15, 'Script to change a supplier code accross all tables necessary'),
('Z_CheckAllocationsFrom.php', 15, ''),
('Z_CheckAllocs.php', 2, ''),
('Z_CheckDebtorsControl.php', 15, 'Inquiry that shows the total local currency (functional currency) balance of all customer accounts to reconcile with the general ledger debtors account'),
('Z_CheckGLTransBalance.php', 15, 'Checks all GL transactions balance and reports problem ones'),
('Z_CreateChartDetails.php', 9, 'Utility page to create chart detail records for all general ledger accounts and periods created - needs expert assistance in use'),
('Z_CreateCompany.php', 15, 'Utility to insert company number 1 if not already there - actually only company 1 is used - the system is not multi-company'),
('Z_CreateCompanyTemplateFile.php', 15, ''),
('Z_CurrencyDebtorsBalances.php', 15, 'Inquiry that shows the total foreign currency together with the total local currency (functional currency) balances of all customer accounts to reconcile with the general ledger debtors account'),
('Z_CurrencySuppliersBalances.php', 15, 'Inquiry that shows the total foreign currency amounts and also the local currency (functional currency) balances of all supplier accounts to reconcile with the general ledger creditors account'),
('Z_DataExport.php', 15, ''),
('Z_DeleteCreditNote.php', 15, 'Utility to reverse a customer credit note - a desperate measure that should not be used except in extreme circumstances'),
('Z_DeleteInvoice.php', 15, 'Utility to reverse a customer invoice - a desperate measure that should not be used except in extreme circumstances'),
('Z_DeleteOldPrices.php', 15, 'Deletes all old prices'),
('Z_DeleteSalesTransActions.php', 15, 'Utility to delete all sales transactions, sales analysis the lot! Extreme care required!!!'),
('Z_DescribeTable.php', 11, ''),
('Z_ImportChartOfAccounts.php', 11, ''),
('Z_ImportDebtors.php', 15, 'Import debtors by csv file'),
('Z_ImportFixedAssets.php', 15, 'Allow fixed assets to be imported from a csv'),
('Z_ImportGLAccountGroups.php', 11, ''),
('Z_ImportGLAccountSections.php', 11, ''),
('Z_ImportGLTransactions.php', 15, 'Import General Ledger Transactions'),
('Z_ImportPartCodes.php', 11, 'Allows inventory items to be imported from a csv'),
('Z_ImportPriceList.php', 15, 'Loads a new price list from a csv file'),
('Z_ImportStocks.php', 15, ''),
('Z_index.php', 15, 'Utility menu page'),
('Z_ItemsWithoutPicture.php', 15, 'Shows the list of curent items without picture in webERP'),
('Z_MakeLocUsers.php', 15, 'Create User Location records'),
('Z_MakeNewCompany.php', 15, ''),
('Z_MakeStockLocns.php', 15, 'Utility to make LocStock records for all items and locations if not already set up.'),
('Z_poAddLanguage.php', 15, 'Allows a new language po file to be created'),
('Z_poAdmin.php', 15, 'Allows for a gettext language po file to be administered'),
('Z_poEditLangHeader.php', 15, ''),
('Z_poEditLangModule.php', 15, ''),
('Z_poEditLangRemaining.php', 15, ''),
('Z_poRebuildDefault.php', 15, ''),
('Z_PriceChanges.php', 15, 'Utility to make bulk pricing alterations to selected sales type price lists or selected customer prices only'),
('Z_ReApplyCostToSA.php', 15, 'Utility to allow the sales analysis table to be updated with the latest cost information - the sales analysis takes the cost at the time the sale was made to reconcile with the enteries made in the gl.'),
('Z_RePostGLFromPeriod.php', 15, 'Utility to repost all general ledger transaction commencing from a specified period. This can take some time in busy environments. Normally GL transactions are posted automatically each time a trial balance or profit and loss account is run'),
('Z_ReverseSuppPaymentRun.php', 15, 'Utility to reverse an entire Supplier payment run'),
('Z_SalesIntegrityCheck.php', 15, ''),
('Z_UpdateChartDetailsBFwd.php', 15, 'Utility to recalculate the ChartDetails table B/Fwd balances - extreme care!!'),
('Z_UpdateItemCosts.php', 15, 'Use CSV of item codes and costs to update webERP item costs'),
('Z_UpdateSalesAnalysisWithLatestCustomerData.php', 15, 'Updates the salesanalysis table with the latest data from the customer debtorsmaster salestype and custbranch sales area and sales person irrespective of the sales type, area, salesperson at the time when the sale was made'),
('Z_Upgrade3.10.php', 15, ''),
('Z_Upgrade_3.01-3.02.php', 15, ''),
('Z_Upgrade_3.04-3.05.php', 15, ''),
('Z_Upgrade_3.05-3.06.php', 15, ''),
('Z_Upgrade_3.07-3.08.php', 15, ''),
('Z_Upgrade_3.08-3.09.php', 15, ''),
('Z_Upgrade_3.09-3.10.php', 15, ''),
('Z_Upgrade_3.10-3.11.php', 15, ''),
('Z_Upgrade_3.11-4.00.php', 15, ''),
('Z_UploadForm.php', 15, 'Utility to upload a file to a remote server'),
('Z_UploadResult.php', 15, 'Utility to upload a file to a remote server');

-- --------------------------------------------------------

--
-- Table structure for table `securitygroups`
--

CREATE TABLE IF NOT EXISTS `securitygroups` (
  `secroleid` int(11) NOT NULL DEFAULT '0',
  `tokenid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`secroleid`,`tokenid`),
  KEY `secroleid` (`secroleid`),
  KEY `tokenid` (`tokenid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `securitygroups`
--

INSERT INTO `securitygroups` (`secroleid`, `tokenid`) VALUES
(1, 0),
(1, 1),
(1, 2),
(1, 5),
(2, 0),
(2, 1),
(2, 2),
(2, 11),
(3, 0),
(3, 1),
(3, 2),
(3, 3),
(3, 4),
(3, 5),
(3, 11),
(4, 0),
(4, 1),
(4, 2),
(4, 5),
(5, 0),
(5, 1),
(5, 2),
(5, 3),
(5, 11),
(6, 0),
(6, 1),
(6, 2),
(6, 3),
(6, 4),
(6, 5),
(6, 6),
(6, 7),
(6, 8),
(6, 9),
(6, 10),
(6, 11),
(7, 0),
(7, 1),
(8, 0),
(8, 1),
(8, 2),
(8, 3),
(8, 4),
(8, 5),
(8, 6),
(8, 7),
(8, 8),
(8, 9),
(8, 10),
(8, 11),
(8, 12),
(8, 13),
(8, 14),
(8, 15),
(8, 16),
(9, 0),
(9, 9);

-- --------------------------------------------------------

--
-- Table structure for table `securityroles`
--

CREATE TABLE IF NOT EXISTS `securityroles` (
  `secroleid` int(11) NOT NULL AUTO_INCREMENT,
  `secrolename` text NOT NULL,
  PRIMARY KEY (`secroleid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `securityroles`
--

INSERT INTO `securityroles` (`secroleid`, `secrolename`) VALUES
(1, 'Inquiries/Order Entry'),
(2, 'Manufac/Stock Admin'),
(3, 'Purchasing Officer'),
(4, 'AP Clerk'),
(5, 'AR Clerk'),
(6, 'Accountant'),
(7, 'Customer Log On Only'),
(8, 'System Administrator'),
(9, 'Supplier Log On Only');

-- --------------------------------------------------------

--
-- Table structure for table `securitytokens`
--

CREATE TABLE IF NOT EXISTS `securitytokens` (
  `tokenid` int(11) NOT NULL DEFAULT '0',
  `tokenname` text NOT NULL,
  PRIMARY KEY (`tokenid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `securitytokens`
--

INSERT INTO `securitytokens` (`tokenid`, `tokenname`) VALUES
(0, 'Main Index Page'),
(1, 'Order Entry/Inquiries customer access only'),
(2, 'Basic Reports and Inquiries with selection options'),
(3, 'Credit notes and AR management'),
(4, 'Purchasing data/PO Entry/Reorder Levels'),
(5, 'Accounts Payable'),
(6, 'Petty Cash'),
(7, 'Bank Reconciliations'),
(8, 'General ledger reports/inquiries'),
(9, 'Supplier centre - Supplier access only'),
(10, 'General Ledger Maintenance, stock valuation & Configuration'),
(11, 'Inventory Management and Pricing'),
(12, 'Prices Security'),
(13, 'Customer services Price modifications'),
(14, 'Unknown'),
(15, 'User Management and System Administration'),
(16, 'QA');

-- --------------------------------------------------------

--
-- Table structure for table `sellthroughsupport`
--

CREATE TABLE IF NOT EXISTS `sellthroughsupport` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supplierno` varchar(10) NOT NULL,
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `categoryid` char(6) NOT NULL DEFAULT '',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `narrative` varchar(20) NOT NULL DEFAULT '',
  `rebatepercent` double NOT NULL DEFAULT '0',
  `rebateamount` double NOT NULL DEFAULT '0',
  `effectivefrom` date NOT NULL,
  `effectiveto` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `supplierno` (`supplierno`),
  KEY `debtorno` (`debtorno`),
  KEY `effectivefrom` (`effectivefrom`),
  KEY `effectiveto` (`effectiveto`),
  KEY `stockid` (`stockid`),
  KEY `categoryid` (`categoryid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `shipmentcharges`
--

CREATE TABLE IF NOT EXISTS `shipmentcharges` (
  `shiptchgid` int(11) NOT NULL AUTO_INCREMENT,
  `shiptref` int(11) NOT NULL DEFAULT '0',
  `transtype` smallint(6) NOT NULL DEFAULT '0',
  `transno` int(11) NOT NULL DEFAULT '0',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `value` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`shiptchgid`),
  KEY `TransType` (`transtype`,`transno`),
  KEY `ShiptRef` (`shiptref`),
  KEY `StockID` (`stockid`),
  KEY `TransType_2` (`transtype`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `shipments`
--

CREATE TABLE IF NOT EXISTS `shipments` (
  `shiptref` int(11) NOT NULL DEFAULT '0',
  `voyageref` varchar(20) NOT NULL DEFAULT '0',
  `vessel` varchar(50) NOT NULL DEFAULT '',
  `eta` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `accumvalue` double NOT NULL DEFAULT '0',
  `supplierid` varchar(10) NOT NULL DEFAULT '',
  `closed` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shiptref`),
  KEY `ETA` (`eta`),
  KEY `SupplierID` (`supplierid`),
  KEY `ShipperRef` (`voyageref`),
  KEY `Vessel` (`vessel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `shippers`
--

CREATE TABLE IF NOT EXISTS `shippers` (
  `shipper_id` int(11) NOT NULL AUTO_INCREMENT,
  `shippername` char(40) NOT NULL DEFAULT '',
  `mincharge` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`shipper_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `shippers`
--

INSERT INTO `shippers` (`shipper_id`, `shippername`, `mincharge`) VALUES
(1, 'Default Shipper', 0);

-- --------------------------------------------------------

--
-- Table structure for table `stockcategory`
--

CREATE TABLE IF NOT EXISTS `stockcategory` (
  `categoryid` char(6) NOT NULL DEFAULT '',
  `categorydescription` char(20) NOT NULL DEFAULT '',
  `stocktype` char(1) NOT NULL DEFAULT 'F',
  `stockact` varchar(20) NOT NULL DEFAULT '0',
  `adjglact` varchar(20) NOT NULL DEFAULT '0',
  `issueglact` varchar(20) NOT NULL DEFAULT '0',
  `purchpricevaract` varchar(20) NOT NULL DEFAULT '80000',
  `materialuseagevarac` varchar(20) NOT NULL DEFAULT '80000',
  `wipact` varchar(20) NOT NULL DEFAULT '0',
  `defaulttaxcatid` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`categoryid`),
  KEY `CategoryDescription` (`categorydescription`),
  KEY `StockType` (`stocktype`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stockcatproperties`
--

CREATE TABLE IF NOT EXISTS `stockcatproperties` (
  `stkcatpropid` int(11) NOT NULL AUTO_INCREMENT,
  `categoryid` char(6) NOT NULL,
  `label` text NOT NULL,
  `controltype` tinyint(4) NOT NULL DEFAULT '0',
  `defaultvalue` varchar(100) NOT NULL DEFAULT '''''',
  `maximumvalue` double NOT NULL DEFAULT '999999999',
  `reqatsalesorder` tinyint(4) NOT NULL DEFAULT '0',
  `minimumvalue` double NOT NULL DEFAULT '-999999999',
  `numericvalue` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`stkcatpropid`),
  KEY `categoryid` (`categoryid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `stockcheckfreeze`
--

CREATE TABLE IF NOT EXISTS `stockcheckfreeze` (
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `loccode` varchar(5) NOT NULL DEFAULT '',
  `qoh` double NOT NULL DEFAULT '0',
  `stockcheckdate` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`stockid`,`loccode`),
  KEY `LocCode` (`loccode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stockcounts`
--

CREATE TABLE IF NOT EXISTS `stockcounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `loccode` varchar(5) NOT NULL DEFAULT '',
  `qtycounted` double NOT NULL DEFAULT '0',
  `reference` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `StockID` (`stockid`),
  KEY `LocCode` (`loccode`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `stockdescriptiontranslations`
--

CREATE TABLE IF NOT EXISTS `stockdescriptiontranslations` (
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `language_id` varchar(10) NOT NULL DEFAULT 'en_GB.utf8',
  `descriptiontranslation` varchar(50) DEFAULT NULL COMMENT 'Item''s short description',
  `longdescriptiontranslation` text COMMENT 'Item''s long description',
  `needsrevision` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`stockid`,`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stockitemproperties`
--

CREATE TABLE IF NOT EXISTS `stockitemproperties` (
  `stockid` varchar(20) NOT NULL,
  `stkcatpropid` int(11) NOT NULL,
  `value` varchar(50) NOT NULL,
  PRIMARY KEY (`stockid`,`stkcatpropid`),
  KEY `stockid` (`stockid`),
  KEY `value` (`value`),
  KEY `stkcatpropid` (`stkcatpropid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stockmaster`
--

CREATE TABLE IF NOT EXISTS `stockmaster` (
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `categoryid` varchar(6) NOT NULL DEFAULT '',
  `description` varchar(50) NOT NULL DEFAULT '',
  `longdescription` text NOT NULL,
  `units` varchar(20) NOT NULL DEFAULT 'each',
  `mbflag` char(1) NOT NULL DEFAULT 'B',
  `actualcost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `lastcost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `materialcost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `labourcost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `overheadcost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `lowestlevel` smallint(6) NOT NULL DEFAULT '0',
  `discontinued` tinyint(4) NOT NULL DEFAULT '0',
  `controlled` tinyint(4) NOT NULL DEFAULT '0',
  `eoq` double NOT NULL DEFAULT '0',
  `volume` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `grossweight` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `barcode` varchar(50) NOT NULL DEFAULT '',
  `discountcategory` char(2) NOT NULL DEFAULT '',
  `taxcatid` tinyint(4) NOT NULL DEFAULT '1',
  `serialised` tinyint(4) NOT NULL DEFAULT '0',
  `appendfile` varchar(40) NOT NULL DEFAULT 'none',
  `perishable` tinyint(1) NOT NULL DEFAULT '0',
  `decimalplaces` tinyint(4) NOT NULL DEFAULT '0',
  `pansize` double NOT NULL DEFAULT '0',
  `shrinkfactor` double NOT NULL DEFAULT '0',
  `nextserialno` bigint(20) NOT NULL DEFAULT '0',
  `netweight` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `lastcostupdate` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`stockid`),
  KEY `CategoryID` (`categoryid`),
  KEY `Description` (`description`),
  KEY `MBflag` (`mbflag`),
  KEY `StockID` (`stockid`,`categoryid`),
  KEY `Controlled` (`controlled`),
  KEY `DiscountCategory` (`discountcategory`),
  KEY `taxcatid` (`taxcatid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stockmoves`
--

CREATE TABLE IF NOT EXISTS `stockmoves` (
  `stkmoveno` int(11) NOT NULL AUTO_INCREMENT,
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `type` smallint(6) NOT NULL DEFAULT '0',
  `transno` int(11) NOT NULL DEFAULT '0',
  `loccode` varchar(5) NOT NULL DEFAULT '',
  `trandate` date NOT NULL DEFAULT '0000-00-00',
  `userid` varchar(20) NOT NULL,
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `branchcode` varchar(10) NOT NULL DEFAULT '',
  `price` decimal(21,5) NOT NULL DEFAULT '0.00000',
  `prd` smallint(6) NOT NULL DEFAULT '0',
  `reference` varchar(100) NOT NULL DEFAULT '',
  `qty` double NOT NULL DEFAULT '1',
  `discountpercent` double NOT NULL DEFAULT '0',
  `standardcost` double NOT NULL DEFAULT '0',
  `show_on_inv_crds` tinyint(4) NOT NULL DEFAULT '1',
  `newqoh` double NOT NULL DEFAULT '0',
  `hidemovt` tinyint(4) NOT NULL DEFAULT '0',
  `narrative` text,
  PRIMARY KEY (`stkmoveno`),
  KEY `DebtorNo` (`debtorno`),
  KEY `LocCode` (`loccode`),
  KEY `Prd` (`prd`),
  KEY `StockID_2` (`stockid`),
  KEY `TranDate` (`trandate`),
  KEY `TransNo` (`transno`),
  KEY `Type` (`type`),
  KEY `Show_On_Inv_Crds` (`show_on_inv_crds`),
  KEY `Hide` (`hidemovt`),
  KEY `reference` (`reference`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

-- --------------------------------------------------------

--
-- Table structure for table `stockmovestaxes`
--

CREATE TABLE IF NOT EXISTS `stockmovestaxes` (
  `stkmoveno` int(11) NOT NULL DEFAULT '0',
  `taxauthid` tinyint(4) NOT NULL DEFAULT '0',
  `taxrate` double NOT NULL DEFAULT '0',
  `taxontax` tinyint(4) NOT NULL DEFAULT '0',
  `taxcalculationorder` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`stkmoveno`,`taxauthid`),
  KEY `taxauthid` (`taxauthid`),
  KEY `calculationorder` (`taxcalculationorder`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stockrequest`
--

CREATE TABLE IF NOT EXISTS `stockrequest` (
  `dispatchid` int(11) NOT NULL AUTO_INCREMENT,
  `loccode` varchar(5) NOT NULL DEFAULT '',
  `departmentid` int(11) NOT NULL DEFAULT '0',
  `despatchdate` date NOT NULL DEFAULT '0000-00-00',
  `authorised` tinyint(4) NOT NULL DEFAULT '0',
  `closed` tinyint(4) NOT NULL DEFAULT '0',
  `narrative` text NOT NULL,
  PRIMARY KEY (`dispatchid`),
  KEY `loccode` (`loccode`),
  KEY `departmentid` (`departmentid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `stockrequestitems`
--

CREATE TABLE IF NOT EXISTS `stockrequestitems` (
  `dispatchitemsid` int(11) NOT NULL DEFAULT '0',
  `dispatchid` int(11) NOT NULL DEFAULT '0',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `quantity` double NOT NULL DEFAULT '0',
  `qtydelivered` double NOT NULL DEFAULT '0',
  `decimalplaces` int(11) NOT NULL DEFAULT '0',
  `uom` varchar(20) NOT NULL DEFAULT '',
  `completed` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`dispatchitemsid`,`dispatchid`),
  KEY `dispatchid` (`dispatchid`),
  KEY `stockid` (`stockid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stockserialitems`
--

CREATE TABLE IF NOT EXISTS `stockserialitems` (
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `loccode` varchar(5) NOT NULL DEFAULT '',
  `serialno` varchar(30) NOT NULL DEFAULT '',
  `expirationdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `quantity` double NOT NULL DEFAULT '0',
  `qualitytext` text NOT NULL,
  PRIMARY KEY (`stockid`,`serialno`,`loccode`),
  KEY `StockID` (`stockid`),
  KEY `LocCode` (`loccode`),
  KEY `serialno` (`serialno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stockserialmoves`
--

CREATE TABLE IF NOT EXISTS `stockserialmoves` (
  `stkitmmoveno` int(11) NOT NULL AUTO_INCREMENT,
  `stockmoveno` int(11) NOT NULL DEFAULT '0',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `serialno` varchar(30) NOT NULL DEFAULT '',
  `moveqty` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`stkitmmoveno`),
  KEY `StockMoveNo` (`stockmoveno`),
  KEY `StockID_SN` (`stockid`,`serialno`),
  KEY `serialno` (`serialno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `suppallocs`
--

CREATE TABLE IF NOT EXISTS `suppallocs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amt` double NOT NULL DEFAULT '0',
  `datealloc` date NOT NULL DEFAULT '0000-00-00',
  `transid_allocfrom` int(11) NOT NULL DEFAULT '0',
  `transid_allocto` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `TransID_AllocFrom` (`transid_allocfrom`),
  KEY `TransID_AllocTo` (`transid_allocto`),
  KEY `DateAlloc` (`datealloc`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `suppliercontacts`
--

CREATE TABLE IF NOT EXISTS `suppliercontacts` (
  `supplierid` varchar(10) NOT NULL DEFAULT '',
  `contact` varchar(30) NOT NULL DEFAULT '',
  `position` varchar(30) NOT NULL DEFAULT '',
  `tel` varchar(30) NOT NULL DEFAULT '',
  `fax` varchar(30) NOT NULL DEFAULT '',
  `mobile` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(55) NOT NULL DEFAULT '',
  `ordercontact` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`supplierid`,`contact`),
  KEY `Contact` (`contact`),
  KEY `SupplierID` (`supplierid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `supplierdiscounts`
--

CREATE TABLE IF NOT EXISTS `supplierdiscounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supplierno` varchar(10) NOT NULL,
  `stockid` varchar(20) NOT NULL,
  `discountnarrative` varchar(20) NOT NULL,
  `discountpercent` double NOT NULL,
  `discountamount` double NOT NULL,
  `effectivefrom` date NOT NULL,
  `effectiveto` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `supplierno` (`supplierno`),
  KEY `effectivefrom` (`effectivefrom`),
  KEY `effectiveto` (`effectiveto`),
  KEY `stockid` (`stockid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE IF NOT EXISTS `suppliers` (
  `supplierid` varchar(10) NOT NULL DEFAULT '',
  `suppname` varchar(40) NOT NULL DEFAULT '',
  `address1` varchar(40) NOT NULL DEFAULT '',
  `address2` varchar(40) NOT NULL DEFAULT '',
  `address3` varchar(40) NOT NULL DEFAULT '',
  `address4` varchar(50) NOT NULL DEFAULT '',
  `address5` varchar(20) NOT NULL DEFAULT '',
  `address6` varchar(40) NOT NULL DEFAULT '',
  `supptype` tinyint(4) NOT NULL DEFAULT '1',
  `lat` float(10,6) NOT NULL DEFAULT '0.000000',
  `lng` float(10,6) NOT NULL DEFAULT '0.000000',
  `currcode` char(3) NOT NULL DEFAULT '',
  `suppliersince` date NOT NULL DEFAULT '0000-00-00',
  `paymentterms` char(2) NOT NULL DEFAULT '',
  `lastpaid` double NOT NULL DEFAULT '0',
  `lastpaiddate` datetime DEFAULT NULL,
  `bankact` varchar(30) NOT NULL DEFAULT '',
  `bankref` varchar(12) NOT NULL DEFAULT '',
  `bankpartics` varchar(12) NOT NULL DEFAULT '',
  `remittance` tinyint(4) NOT NULL DEFAULT '1',
  `taxgroupid` tinyint(4) NOT NULL DEFAULT '1',
  `factorcompanyid` int(11) NOT NULL DEFAULT '1',
  `taxref` varchar(20) NOT NULL DEFAULT '',
  `phn` varchar(50) NOT NULL DEFAULT '',
  `port` varchar(200) NOT NULL DEFAULT '',
  `email` varchar(55) DEFAULT NULL,
  `fax` varchar(25) DEFAULT NULL,
  `telephone` varchar(25) DEFAULT NULL,
  `url` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`supplierid`),
  KEY `CurrCode` (`currcode`),
  KEY `PaymentTerms` (`paymentterms`),
  KEY `SuppName` (`suppname`),
  KEY `taxgroupid` (`taxgroupid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `suppliertype`
--

CREATE TABLE IF NOT EXISTS `suppliertype` (
  `typeid` tinyint(4) NOT NULL AUTO_INCREMENT,
  `typename` varchar(100) NOT NULL,
  PRIMARY KEY (`typeid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `supptrans`
--

CREATE TABLE IF NOT EXISTS `supptrans` (
  `transno` int(11) NOT NULL DEFAULT '0',
  `type` smallint(6) NOT NULL DEFAULT '0',
  `supplierno` varchar(10) NOT NULL DEFAULT '',
  `suppreference` varchar(20) NOT NULL DEFAULT '',
  `trandate` date NOT NULL DEFAULT '0000-00-00',
  `duedate` date NOT NULL DEFAULT '0000-00-00',
  `inputdate` datetime NOT NULL,
  `settled` tinyint(4) NOT NULL DEFAULT '0',
  `rate` double NOT NULL DEFAULT '1',
  `ovamount` double NOT NULL DEFAULT '0',
  `ovgst` double NOT NULL DEFAULT '0',
  `diffonexch` double NOT NULL DEFAULT '0',
  `alloc` double NOT NULL DEFAULT '0',
  `transtext` text,
  `hold` tinyint(4) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `TypeTransNo` (`transno`,`type`),
  KEY `DueDate` (`duedate`),
  KEY `Hold` (`hold`),
  KEY `SupplierNo` (`supplierno`),
  KEY `Settled` (`settled`),
  KEY `SupplierNo_2` (`supplierno`,`suppreference`),
  KEY `SuppReference` (`suppreference`),
  KEY `TranDate` (`trandate`),
  KEY `TransNo` (`transno`),
  KEY `Type` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Table structure for table `supptranstaxes`
--

CREATE TABLE IF NOT EXISTS `supptranstaxes` (
  `supptransid` int(11) NOT NULL DEFAULT '0',
  `taxauthid` tinyint(4) NOT NULL DEFAULT '0',
  `taxamount` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`supptransid`,`taxauthid`),
  KEY `taxauthid` (`taxauthid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `systypes`
--

CREATE TABLE IF NOT EXISTS `systypes` (
  `typeid` smallint(6) NOT NULL DEFAULT '0',
  `typename` char(50) NOT NULL DEFAULT '',
  `typeno` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`typeid`),
  KEY `TypeNo` (`typeno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `systypes`
--

INSERT INTO `systypes` (`typeid`, `typename`, `typeno`) VALUES
(0, 'Journal - GL', 0),
(1, 'Payment - GL', 0),
(2, 'Receipt - GL', 0),
(3, 'Standing Journal', 0),
(10, 'Sales Invoice', 0),
(11, 'Credit Note', 0),
(12, 'Receipt', 0),
(15, 'Journal - Debtors', 0),
(16, 'Location Transfer', 0),
(17, 'Stock Adjustment', 0),
(18, 'Purchase Order', 0),
(19, 'Picking List', 0),
(20, 'Purchase Invoice', 0),
(21, 'Debit Note', 0),
(22, 'Creditors Payment', 0),
(23, 'Creditors Journal', 0),
(25, 'Purchase Order Delivery', 0),
(26, 'Work Order Receipt', 0),
(28, 'Work Order Issue', 0),
(29, 'Work Order Variance', 0),
(30, 'Sales Order', 0),
(31, 'Shipment Close', 0),
(32, 'Contract Close', 0),
(35, 'Cost Update', 0),
(36, 'Exchange Difference', 0),
(37, 'Tenders', 0),
(38, 'Stock Requests', 0),
(40, 'Work Order', 0),
(41, 'Asset Addition', 0),
(42, 'Asset Category Change', 0),
(43, 'Delete w/down asset', 0),
(44, 'Depreciation', 0),
(49, 'Import Fixed Assets', 0),
(50, 'Opening Balance', 0),
(500, 'Auto Debtor Number', 0),
(600, 'Auto Supplier Number', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `tagref` tinyint(4) NOT NULL AUTO_INCREMENT,
  `tagdescription` varchar(50) NOT NULL,
  PRIMARY KEY (`tagref`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `taxauthorities`
--

CREATE TABLE IF NOT EXISTS `taxauthorities` (
  `taxid` tinyint(4) NOT NULL AUTO_INCREMENT,
  `description` varchar(20) NOT NULL DEFAULT '',
  `taxglcode` varchar(20) NOT NULL DEFAULT '0',
  `purchtaxglaccount` varchar(20) NOT NULL DEFAULT '0',
  `bank` varchar(50) NOT NULL DEFAULT '',
  `bankacctype` varchar(20) NOT NULL DEFAULT '',
  `bankacc` varchar(50) NOT NULL DEFAULT '',
  `bankswift` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`taxid`),
  KEY `TaxGLCode` (`taxglcode`),
  KEY `PurchTaxGLAccount` (`purchtaxglaccount`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `taxauthorities`
--

INSERT INTO `taxauthorities` (`taxid`, `description`, `taxglcode`, `purchtaxglaccount`, `bank`, `bankacctype`, `bankacc`, `bankswift`) VALUES
(1, 'Australian GST', '2300', '2310', '', '', '', ''),
(5, 'Sales Tax', '2300', '2310', '', '', '', ''),
(11, 'Canadian GST', '2300', '2310', '', '', '', ''),
(12, 'Ontario PST', '2300', '2310', '', '', '', ''),
(13, 'UK VAT', '2300', '2310', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `taxauthrates`
--

CREATE TABLE IF NOT EXISTS `taxauthrates` (
  `taxauthority` tinyint(4) NOT NULL DEFAULT '1',
  `dispatchtaxprovince` tinyint(4) NOT NULL DEFAULT '1',
  `taxcatid` tinyint(4) NOT NULL DEFAULT '0',
  `taxrate` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`taxauthority`,`dispatchtaxprovince`,`taxcatid`),
  KEY `TaxAuthority` (`taxauthority`),
  KEY `dispatchtaxprovince` (`dispatchtaxprovince`),
  KEY `taxcatid` (`taxcatid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `taxauthrates`
--

INSERT INTO `taxauthrates` (`taxauthority`, `dispatchtaxprovince`, `taxcatid`, `taxrate`) VALUES
(1, 1, 1, 0.1),
(1, 1, 2, 0),
(1, 1, 5, 0),
(5, 1, 1, 0.2),
(5, 1, 2, 0.35),
(5, 1, 5, 0),
(11, 1, 1, 0.07),
(11, 1, 2, 0.12),
(11, 1, 5, 0.07),
(12, 1, 1, 0.05),
(12, 1, 2, 0.075),
(12, 1, 5, 0),
(13, 1, 1, 0),
(13, 1, 2, 0),
(13, 1, 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `taxcategories`
--

CREATE TABLE IF NOT EXISTS `taxcategories` (
  `taxcatid` tinyint(4) NOT NULL AUTO_INCREMENT,
  `taxcatname` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`taxcatid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `taxcategories`
--

INSERT INTO `taxcategories` (`taxcatid`, `taxcatname`) VALUES
(1, 'Taxable supply'),
(2, 'Luxury Items'),
(4, 'Exempt'),
(5, 'Freight');

-- --------------------------------------------------------

--
-- Table structure for table `taxgroups`
--

CREATE TABLE IF NOT EXISTS `taxgroups` (
  `taxgroupid` tinyint(4) NOT NULL AUTO_INCREMENT,
  `taxgroupdescription` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`taxgroupid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `taxgroups`
--

INSERT INTO `taxgroups` (`taxgroupid`, `taxgroupdescription`) VALUES
(1, 'Default'),
(2, 'Ontario'),
(3, 'UK Inland Revenue');

-- --------------------------------------------------------

--
-- Table structure for table `taxgrouptaxes`
--

CREATE TABLE IF NOT EXISTS `taxgrouptaxes` (
  `taxgroupid` tinyint(4) NOT NULL DEFAULT '0',
  `taxauthid` tinyint(4) NOT NULL DEFAULT '0',
  `calculationorder` tinyint(4) NOT NULL DEFAULT '0',
  `taxontax` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`taxgroupid`,`taxauthid`),
  KEY `taxgroupid` (`taxgroupid`),
  KEY `taxauthid` (`taxauthid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `taxprovinces`
--

CREATE TABLE IF NOT EXISTS `taxprovinces` (
  `taxprovinceid` tinyint(4) NOT NULL AUTO_INCREMENT,
  `taxprovincename` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`taxprovinceid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `taxprovinces`
--

INSERT INTO `taxprovinces` (`taxprovinceid`, `taxprovincename`) VALUES
(1, 'Default Tax province');

-- --------------------------------------------------------

--
-- Table structure for table `tenderitems`
--

CREATE TABLE IF NOT EXISTS `tenderitems` (
  `tenderid` int(11) NOT NULL DEFAULT '0',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `quantity` varchar(40) NOT NULL DEFAULT '',
  `units` varchar(20) NOT NULL DEFAULT 'each',
  PRIMARY KEY (`tenderid`,`stockid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tenders`
--

CREATE TABLE IF NOT EXISTS `tenders` (
  `tenderid` int(11) NOT NULL DEFAULT '0',
  `location` varchar(5) NOT NULL DEFAULT '',
  `address1` varchar(40) NOT NULL DEFAULT '',
  `address2` varchar(40) NOT NULL DEFAULT '',
  `address3` varchar(40) NOT NULL DEFAULT '',
  `address4` varchar(40) NOT NULL DEFAULT '',
  `address5` varchar(20) NOT NULL DEFAULT '',
  `address6` varchar(15) NOT NULL DEFAULT '',
  `telephone` varchar(25) NOT NULL DEFAULT '',
  `closed` int(2) NOT NULL DEFAULT '0',
  `requiredbydate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`tenderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tendersuppliers`
--

CREATE TABLE IF NOT EXISTS `tendersuppliers` (
  `tenderid` int(11) NOT NULL DEFAULT '0',
  `supplierid` varchar(10) NOT NULL DEFAULT '',
  `email` varchar(40) NOT NULL DEFAULT '',
  `responded` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tenderid`,`supplierid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `unitsofmeasure`
--

CREATE TABLE IF NOT EXISTS `unitsofmeasure` (
  `unitid` tinyint(4) NOT NULL AUTO_INCREMENT,
  `unitname` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`unitid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `unitsofmeasure`
--

INSERT INTO `unitsofmeasure` (`unitid`, `unitname`) VALUES
(1, 'each'),
(2, 'meters'),
(3, 'kgs'),
(4, 'litres'),
(5, 'length'),
(6, 'hours'),
(7, 'feet');

-- --------------------------------------------------------

--
-- Table structure for table `woitems`
--

CREATE TABLE IF NOT EXISTS `woitems` (
  `wo` int(11) NOT NULL,
  `stockid` char(20) NOT NULL DEFAULT '',
  `qtyreqd` double NOT NULL DEFAULT '1',
  `qtyrecd` double NOT NULL DEFAULT '0',
  `stdcost` double NOT NULL,
  `nextlotsnref` varchar(20) DEFAULT '',
  `comments` longblob,
  PRIMARY KEY (`wo`,`stockid`),
  KEY `stockid` (`stockid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `worequirements`
--

CREATE TABLE IF NOT EXISTS `worequirements` (
  `wo` int(11) NOT NULL,
  `parentstockid` varchar(20) NOT NULL,
  `stockid` varchar(20) NOT NULL,
  `qtypu` double NOT NULL DEFAULT '1',
  `stdcost` double NOT NULL DEFAULT '0',
  `autoissue` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`wo`,`parentstockid`,`stockid`),
  KEY `stockid` (`stockid`),
  KEY `worequirements_ibfk_3` (`parentstockid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `workcentres`
--

CREATE TABLE IF NOT EXISTS `workcentres` (
  `code` char(5) NOT NULL DEFAULT '',
  `location` char(5) NOT NULL DEFAULT '',
  `description` char(20) NOT NULL DEFAULT '',
  `capacity` double NOT NULL DEFAULT '1',
  `overheadperhour` decimal(10,0) NOT NULL DEFAULT '0',
  `overheadrecoveryact` varchar(20) NOT NULL DEFAULT '0',
  `setuphrs` decimal(10,0) NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`),
  KEY `Description` (`description`),
  KEY `Location` (`location`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `workorders`
--

CREATE TABLE IF NOT EXISTS `workorders` (
  `wo` int(11) NOT NULL,
  `loccode` char(5) NOT NULL DEFAULT '',
  `requiredby` date NOT NULL DEFAULT '0000-00-00',
  `startdate` date NOT NULL DEFAULT '0000-00-00',
  `costissued` double NOT NULL DEFAULT '0',
  `closed` tinyint(4) NOT NULL DEFAULT '0',
  `closecomments` longblob,
  PRIMARY KEY (`wo`),
  KEY `LocCode` (`loccode`),
  KEY `StartDate` (`startdate`),
  KEY `RequiredBy` (`requiredby`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `woserialnos`
--

CREATE TABLE IF NOT EXISTS `woserialnos` (
  `wo` int(11) NOT NULL,
  `stockid` varchar(20) NOT NULL,
  `serialno` varchar(30) NOT NULL,
  `quantity` double NOT NULL DEFAULT '1',
  `qualitytext` text NOT NULL,
  PRIMARY KEY (`wo`,`stockid`,`serialno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `www_users`
--

CREATE TABLE IF NOT EXISTS `www_users` (
  `userid` varchar(20) NOT NULL DEFAULT '',
  `password` text NOT NULL,
  `realname` varchar(35) NOT NULL DEFAULT '',
  `customerid` varchar(10) NOT NULL DEFAULT '',
  `supplierid` varchar(10) NOT NULL DEFAULT '',
  `salesman` char(3) NOT NULL,
  `phone` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(55) DEFAULT NULL,
  `defaultlocation` varchar(5) NOT NULL DEFAULT '',
  `fullaccess` int(11) NOT NULL DEFAULT '1',
  `cancreatetender` tinyint(1) NOT NULL DEFAULT '0',
  `lastvisitdate` datetime DEFAULT NULL,
  `branchcode` varchar(10) NOT NULL DEFAULT '',
  `pagesize` varchar(20) NOT NULL DEFAULT 'A4',
  `modulesallowed` varchar(25) NOT NULL,
  `showdashboard` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Display dashboard after login',
  `blocked` tinyint(4) NOT NULL DEFAULT '0',
  `displayrecordsmax` int(11) NOT NULL DEFAULT '0',
  `theme` varchar(30) NOT NULL DEFAULT 'fresh',
  `language` varchar(10) NOT NULL DEFAULT 'en_GB.utf8',
  `pdflanguage` tinyint(1) NOT NULL DEFAULT '0',
  `department` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userid`),
  KEY `CustomerID` (`customerid`),
  KEY `DefaultLocation` (`defaultlocation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `www_users`
--

INSERT INTO `www_users` (`userid`, `password`, `realname`, `customerid`, `supplierid`, `salesman`, `phone`, `email`, `defaultlocation`, `fullaccess`, `cancreatetender`, `lastvisitdate`, `branchcode`, `pagesize`, `modulesallowed`, `showdashboard`, `blocked`, `displayrecordsmax`, `theme`, `language`, `pdflanguage`, `department`) VALUES
('admin', '$2y$10$pRS4NpFo6EWaBFbtpBdzh.S0t4dYlzfu0v5zeDzxQOrH0q6fzE2OS', 'Demonstration user', '', '', '', '', 'olafsson.sequeira@gmail.com', 'MEL', 8, 1, '2015-02-25 17:25:32', '', 'A4', '1,1,1,1,1,1,1,1,1,1,1,', 0, 0, 50, 'xenos', 'en_US.utf8', 0, 0);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accountgroups`
--
ALTER TABLE `accountgroups`
  ADD CONSTRAINT `accountgroups_ibfk_1` FOREIGN KEY (`sectioninaccounts`) REFERENCES `accountsection` (`sectionid`);

--
-- Constraints for table `audittrail`
--
ALTER TABLE `audittrail`
  ADD CONSTRAINT `audittrail_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `www_users` (`userid`);

--
-- Constraints for table `bankaccounts`
--
ALTER TABLE `bankaccounts`
  ADD CONSTRAINT `bankaccounts_ibfk_1` FOREIGN KEY (`accountcode`) REFERENCES `chartmaster` (`accountcode`);

--
-- Constraints for table `banktrans`
--
ALTER TABLE `banktrans`
  ADD CONSTRAINT `banktrans_ibfk_1` FOREIGN KEY (`type`) REFERENCES `systypes` (`typeid`),
  ADD CONSTRAINT `banktrans_ibfk_2` FOREIGN KEY (`bankact`) REFERENCES `bankaccounts` (`accountcode`);

--
-- Constraints for table `bom`
--
ALTER TABLE `bom`
  ADD CONSTRAINT `bom_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `bom_ibfk_2` FOREIGN KEY (`component`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `bom_ibfk_3` FOREIGN KEY (`workcentreadded`) REFERENCES `workcentres` (`code`),
  ADD CONSTRAINT `bom_ibfk_4` FOREIGN KEY (`loccode`) REFERENCES `locations` (`loccode`);

--
-- Constraints for table `chartdetails`
--
ALTER TABLE `chartdetails`
  ADD CONSTRAINT `chartdetails_ibfk_1` FOREIGN KEY (`accountcode`) REFERENCES `chartmaster` (`accountcode`),
  ADD CONSTRAINT `chartdetails_ibfk_2` FOREIGN KEY (`period`) REFERENCES `periods` (`periodno`);

--
-- Constraints for table `chartmaster`
--
ALTER TABLE `chartmaster`
  ADD CONSTRAINT `chartmaster_ibfk_1` FOREIGN KEY (`group_`) REFERENCES `accountgroups` (`groupname`);

--
-- Constraints for table `contractbom`
--
ALTER TABLE `contractbom`
  ADD CONSTRAINT `contractbom_ibfk_1` FOREIGN KEY (`workcentreadded`) REFERENCES `workcentres` (`code`),
  ADD CONSTRAINT `contractbom_ibfk_3` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`);

--
-- Constraints for table `contractcharges`
--
ALTER TABLE `contractcharges`
  ADD CONSTRAINT `contractcharges_ibfk_1` FOREIGN KEY (`contractref`) REFERENCES `contracts` (`contractref`),
  ADD CONSTRAINT `contractcharges_ibfk_2` FOREIGN KEY (`transtype`) REFERENCES `systypes` (`typeid`);

--
-- Constraints for table `contractreqts`
--
ALTER TABLE `contractreqts`
  ADD CONSTRAINT `contractreqts_ibfk_1` FOREIGN KEY (`contractref`) REFERENCES `contracts` (`contractref`);

--
-- Constraints for table `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `contracts_ibfk_1` FOREIGN KEY (`debtorno`, `branchcode`) REFERENCES `custbranch` (`debtorno`, `branchcode`),
  ADD CONSTRAINT `contracts_ibfk_2` FOREIGN KEY (`categoryid`) REFERENCES `stockcategory` (`categoryid`),
  ADD CONSTRAINT `contracts_ibfk_3` FOREIGN KEY (`loccode`) REFERENCES `locations` (`loccode`);

--
-- Constraints for table `custallocns`
--
ALTER TABLE `custallocns`
  ADD CONSTRAINT `custallocns_ibfk_1` FOREIGN KEY (`transid_allocfrom`) REFERENCES `debtortrans` (`id`),
  ADD CONSTRAINT `custallocns_ibfk_2` FOREIGN KEY (`transid_allocto`) REFERENCES `debtortrans` (`id`);

--
-- Constraints for table `custbranch`
--
ALTER TABLE `custbranch`
  ADD CONSTRAINT `custbranch_ibfk_1` FOREIGN KEY (`debtorno`) REFERENCES `debtorsmaster` (`debtorno`),
  ADD CONSTRAINT `custbranch_ibfk_2` FOREIGN KEY (`area`) REFERENCES `areas` (`areacode`),
  ADD CONSTRAINT `custbranch_ibfk_3` FOREIGN KEY (`salesman`) REFERENCES `salesman` (`salesmancode`),
  ADD CONSTRAINT `custbranch_ibfk_4` FOREIGN KEY (`defaultlocation`) REFERENCES `locations` (`loccode`),
  ADD CONSTRAINT `custbranch_ibfk_6` FOREIGN KEY (`defaultshipvia`) REFERENCES `shippers` (`shipper_id`),
  ADD CONSTRAINT `custbranch_ibfk_7` FOREIGN KEY (`taxgroupid`) REFERENCES `taxgroups` (`taxgroupid`);

--
-- Constraints for table `custitem`
--
ALTER TABLE `custitem`
  ADD CONSTRAINT ` custitem _ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT ` custitem _ibfk_2` FOREIGN KEY (`debtorno`) REFERENCES `debtorsmaster` (`debtorno`);

--
-- Constraints for table `debtorsmaster`
--
ALTER TABLE `debtorsmaster`
  ADD CONSTRAINT `debtorsmaster_ibfk_1` FOREIGN KEY (`holdreason`) REFERENCES `holdreasons` (`reasoncode`),
  ADD CONSTRAINT `debtorsmaster_ibfk_2` FOREIGN KEY (`currcode`) REFERENCES `currencies` (`currabrev`),
  ADD CONSTRAINT `debtorsmaster_ibfk_3` FOREIGN KEY (`paymentterms`) REFERENCES `paymentterms` (`termsindicator`),
  ADD CONSTRAINT `debtorsmaster_ibfk_4` FOREIGN KEY (`salestype`) REFERENCES `salestypes` (`typeabbrev`),
  ADD CONSTRAINT `debtorsmaster_ibfk_5` FOREIGN KEY (`typeid`) REFERENCES `debtortype` (`typeid`);

--
-- Constraints for table `debtortrans`
--
ALTER TABLE `debtortrans`
  ADD CONSTRAINT `debtortrans_ibfk_2` FOREIGN KEY (`type`) REFERENCES `systypes` (`typeid`),
  ADD CONSTRAINT `debtortrans_ibfk_3` FOREIGN KEY (`prd`) REFERENCES `periods` (`periodno`);

--
-- Constraints for table `debtortranstaxes`
--
ALTER TABLE `debtortranstaxes`
  ADD CONSTRAINT `debtortranstaxes_ibfk_1` FOREIGN KEY (`taxauthid`) REFERENCES `taxauthorities` (`taxid`),
  ADD CONSTRAINT `debtortranstaxes_ibfk_2` FOREIGN KEY (`debtortransid`) REFERENCES `debtortrans` (`id`);

--
-- Constraints for table `deliverynotes`
--
ALTER TABLE `deliverynotes`
  ADD CONSTRAINT `deliverynotes_ibfk_1` FOREIGN KEY (`salesorderno`) REFERENCES `salesorders` (`orderno`),
  ADD CONSTRAINT `deliverynotes_ibfk_2` FOREIGN KEY (`salesorderno`, `salesorderlineno`) REFERENCES `salesorderdetails` (`orderno`, `orderlineno`);

--
-- Constraints for table `discountmatrix`
--
ALTER TABLE `discountmatrix`
  ADD CONSTRAINT `discountmatrix_ibfk_1` FOREIGN KEY (`salestype`) REFERENCES `salestypes` (`typeabbrev`);

--
-- Constraints for table `freightcosts`
--
ALTER TABLE `freightcosts`
  ADD CONSTRAINT `freightcosts_ibfk_1` FOREIGN KEY (`locationfrom`) REFERENCES `locations` (`loccode`),
  ADD CONSTRAINT `freightcosts_ibfk_2` FOREIGN KEY (`shipperid`) REFERENCES `shippers` (`shipper_id`);

--
-- Constraints for table `gltrans`
--
ALTER TABLE `gltrans`
  ADD CONSTRAINT `gltrans_ibfk_1` FOREIGN KEY (`account`) REFERENCES `chartmaster` (`accountcode`),
  ADD CONSTRAINT `gltrans_ibfk_2` FOREIGN KEY (`type`) REFERENCES `systypes` (`typeid`),
  ADD CONSTRAINT `gltrans_ibfk_3` FOREIGN KEY (`periodno`) REFERENCES `periods` (`periodno`);

--
-- Constraints for table `grns`
--
ALTER TABLE `grns`
  ADD CONSTRAINT `grns_ibfk_1` FOREIGN KEY (`supplierid`) REFERENCES `suppliers` (`supplierid`),
  ADD CONSTRAINT `grns_ibfk_2` FOREIGN KEY (`podetailitem`) REFERENCES `purchorderdetails` (`podetailitem`);

--
-- Constraints for table `internalstockcatrole`
--
ALTER TABLE `internalstockcatrole`
  ADD CONSTRAINT `internalstockcatrole_ibfk_1` FOREIGN KEY (`categoryid`) REFERENCES `stockcategory` (`categoryid`),
  ADD CONSTRAINT `internalstockcatrole_ibfk_2` FOREIGN KEY (`secroleid`) REFERENCES `securityroles` (`secroleid`),
  ADD CONSTRAINT `internalstockcatrole_ibfk_3` FOREIGN KEY (`categoryid`) REFERENCES `stockcategory` (`categoryid`),
  ADD CONSTRAINT `internalstockcatrole_ibfk_4` FOREIGN KEY (`secroleid`) REFERENCES `securityroles` (`secroleid`);

--
-- Constraints for table `locations`
--
ALTER TABLE `locations`
  ADD CONSTRAINT `locations_ibfk_1` FOREIGN KEY (`taxprovinceid`) REFERENCES `taxprovinces` (`taxprovinceid`);

--
-- Constraints for table `locstock`
--
ALTER TABLE `locstock`
  ADD CONSTRAINT `locstock_ibfk_1` FOREIGN KEY (`loccode`) REFERENCES `locations` (`loccode`),
  ADD CONSTRAINT `locstock_ibfk_2` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`);

--
-- Constraints for table `loctransfers`
--
ALTER TABLE `loctransfers`
  ADD CONSTRAINT `loctransfers_ibfk_1` FOREIGN KEY (`shiploc`) REFERENCES `locations` (`loccode`),
  ADD CONSTRAINT `loctransfers_ibfk_2` FOREIGN KEY (`recloc`) REFERENCES `locations` (`loccode`),
  ADD CONSTRAINT `loctransfers_ibfk_3` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`);

--
-- Constraints for table `mailgroupdetails`
--
ALTER TABLE `mailgroupdetails`
  ADD CONSTRAINT `mailgroupdetails_ibfk_1` FOREIGN KEY (`groupname`) REFERENCES `mailgroups` (`groupname`),
  ADD CONSTRAINT `mailgroupdetails_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `www_users` (`userid`);

--
-- Constraints for table `mrpdemands`
--
ALTER TABLE `mrpdemands`
  ADD CONSTRAINT `mrpdemands_ibfk_1` FOREIGN KEY (`mrpdemandtype`) REFERENCES `mrpdemandtypes` (`mrpdemandtype`),
  ADD CONSTRAINT `mrpdemands_ibfk_2` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`);

--
-- Constraints for table `offers`
--
ALTER TABLE `offers`
  ADD CONSTRAINT `offers_ibfk_1` FOREIGN KEY (`supplierid`) REFERENCES `suppliers` (`supplierid`),
  ADD CONSTRAINT `offers_ibfk_2` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`);

--
-- Constraints for table `orderdeliverydifferenceslog`
--
ALTER TABLE `orderdeliverydifferenceslog`
  ADD CONSTRAINT `orderdeliverydifferenceslog_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `orderdeliverydifferenceslog_ibfk_2` FOREIGN KEY (`debtorno`, `branch`) REFERENCES `custbranch` (`debtorno`, `branchcode`),
  ADD CONSTRAINT `orderdeliverydifferenceslog_ibfk_3` FOREIGN KEY (`orderno`) REFERENCES `salesorders` (`orderno`);

--
-- Constraints for table `pcexpenses`
--
ALTER TABLE `pcexpenses`
  ADD CONSTRAINT `pcexpenses_ibfk_1` FOREIGN KEY (`glaccount`) REFERENCES `chartmaster` (`accountcode`);

--
-- Constraints for table `pctabexpenses`
--
ALTER TABLE `pctabexpenses`
  ADD CONSTRAINT `pctabexpenses_ibfk_1` FOREIGN KEY (`typetabcode`) REFERENCES `pctypetabs` (`typetabcode`),
  ADD CONSTRAINT `pctabexpenses_ibfk_2` FOREIGN KEY (`codeexpense`) REFERENCES `pcexpenses` (`codeexpense`);

--
-- Constraints for table `pctabs`
--
ALTER TABLE `pctabs`
  ADD CONSTRAINT `pctabs_ibfk_1` FOREIGN KEY (`usercode`) REFERENCES `www_users` (`userid`),
  ADD CONSTRAINT `pctabs_ibfk_2` FOREIGN KEY (`typetabcode`) REFERENCES `pctypetabs` (`typetabcode`),
  ADD CONSTRAINT `pctabs_ibfk_3` FOREIGN KEY (`currency`) REFERENCES `currencies` (`currabrev`),
  ADD CONSTRAINT `pctabs_ibfk_4` FOREIGN KEY (`authorizer`) REFERENCES `www_users` (`userid`),
  ADD CONSTRAINT `pctabs_ibfk_5` FOREIGN KEY (`glaccountassignment`) REFERENCES `chartmaster` (`accountcode`);

--
-- Constraints for table `pickinglistdetails`
--
ALTER TABLE `pickinglistdetails`
  ADD CONSTRAINT `pickinglistdetails_ibfk_1` FOREIGN KEY (`pickinglistno`) REFERENCES `pickinglists` (`pickinglistno`);

--
-- Constraints for table `pickinglists`
--
ALTER TABLE `pickinglists`
  ADD CONSTRAINT `pickinglists_ibfk_1` FOREIGN KEY (`orderno`) REFERENCES `salesorders` (`orderno`);

--
-- Constraints for table `prices`
--
ALTER TABLE `prices`
  ADD CONSTRAINT `prices_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `prices_ibfk_2` FOREIGN KEY (`currabrev`) REFERENCES `currencies` (`currabrev`),
  ADD CONSTRAINT `prices_ibfk_3` FOREIGN KEY (`typeabbrev`) REFERENCES `salestypes` (`typeabbrev`);

--
-- Constraints for table `prodspecs`
--
ALTER TABLE `prodspecs`
  ADD CONSTRAINT `prodspecs_ibfk_1` FOREIGN KEY (`testid`) REFERENCES `qatests` (`testid`);

--
-- Constraints for table `purchdata`
--
ALTER TABLE `purchdata`
  ADD CONSTRAINT `purchdata_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `purchdata_ibfk_2` FOREIGN KEY (`supplierno`) REFERENCES `suppliers` (`supplierid`);

--
-- Constraints for table `purchorderdetails`
--
ALTER TABLE `purchorderdetails`
  ADD CONSTRAINT `purchorderdetails_ibfk_1` FOREIGN KEY (`orderno`) REFERENCES `purchorders` (`orderno`);

--
-- Constraints for table `purchorders`
--
ALTER TABLE `purchorders`
  ADD CONSTRAINT `purchorders_ibfk_1` FOREIGN KEY (`supplierno`) REFERENCES `suppliers` (`supplierid`),
  ADD CONSTRAINT `purchorders_ibfk_2` FOREIGN KEY (`intostocklocation`) REFERENCES `locations` (`loccode`);

--
-- Constraints for table `qasamples`
--
ALTER TABLE `qasamples`
  ADD CONSTRAINT `qasamples_ibfk_1` FOREIGN KEY (`prodspeckey`) REFERENCES `prodspecs` (`keyval`);

--
-- Constraints for table `recurringsalesorders`
--
ALTER TABLE `recurringsalesorders`
  ADD CONSTRAINT `recurringsalesorders_ibfk_1` FOREIGN KEY (`branchcode`, `debtorno`) REFERENCES `custbranch` (`branchcode`, `debtorno`);

--
-- Constraints for table `recurrsalesorderdetails`
--
ALTER TABLE `recurrsalesorderdetails`
  ADD CONSTRAINT `recurrsalesorderdetails_ibfk_1` FOREIGN KEY (`recurrorderno`) REFERENCES `recurringsalesorders` (`recurrorderno`),
  ADD CONSTRAINT `recurrsalesorderdetails_ibfk_2` FOREIGN KEY (`stkcode`) REFERENCES `stockmaster` (`stockid`);

--
-- Constraints for table `reportcolumns`
--
ALTER TABLE `reportcolumns`
  ADD CONSTRAINT `reportcolumns_ibfk_1` FOREIGN KEY (`reportid`) REFERENCES `reportheaders` (`reportid`);

--
-- Constraints for table `salesanalysis`
--
ALTER TABLE `salesanalysis`
  ADD CONSTRAINT `salesanalysis_ibfk_1` FOREIGN KEY (`periodno`) REFERENCES `periods` (`periodno`);

--
-- Constraints for table `salescatprod`
--
ALTER TABLE `salescatprod`
  ADD CONSTRAINT `salescatprod_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `salescatprod_ibfk_2` FOREIGN KEY (`salescatid`) REFERENCES `salescat` (`salescatid`);

--
-- Constraints for table `salesorderdetails`
--
ALTER TABLE `salesorderdetails`
  ADD CONSTRAINT `salesorderdetails_ibfk_1` FOREIGN KEY (`orderno`) REFERENCES `salesorders` (`orderno`),
  ADD CONSTRAINT `salesorderdetails_ibfk_2` FOREIGN KEY (`stkcode`) REFERENCES `stockmaster` (`stockid`);

--
-- Constraints for table `salesorders`
--
ALTER TABLE `salesorders`
  ADD CONSTRAINT `salesorders_ibfk_1` FOREIGN KEY (`branchcode`, `debtorno`) REFERENCES `custbranch` (`branchcode`, `debtorno`),
  ADD CONSTRAINT `salesorders_ibfk_2` FOREIGN KEY (`shipvia`) REFERENCES `shippers` (`shipper_id`),
  ADD CONSTRAINT `salesorders_ibfk_3` FOREIGN KEY (`fromstkloc`) REFERENCES `locations` (`loccode`);

--
-- Constraints for table `sampleresults`
--
ALTER TABLE `sampleresults`
  ADD CONSTRAINT `sampleresults_ibfk_1` FOREIGN KEY (`testid`) REFERENCES `qatests` (`testid`);

--
-- Constraints for table `securitygroups`
--
ALTER TABLE `securitygroups`
  ADD CONSTRAINT `securitygroups_secroleid_fk` FOREIGN KEY (`secroleid`) REFERENCES `securityroles` (`secroleid`),
  ADD CONSTRAINT `securitygroups_tokenid_fk` FOREIGN KEY (`tokenid`) REFERENCES `securitytokens` (`tokenid`);

--
-- Constraints for table `shipmentcharges`
--
ALTER TABLE `shipmentcharges`
  ADD CONSTRAINT `shipmentcharges_ibfk_1` FOREIGN KEY (`shiptref`) REFERENCES `shipments` (`shiptref`),
  ADD CONSTRAINT `shipmentcharges_ibfk_2` FOREIGN KEY (`transtype`) REFERENCES `systypes` (`typeid`);

--
-- Constraints for table `shipments`
--
ALTER TABLE `shipments`
  ADD CONSTRAINT `shipments_ibfk_1` FOREIGN KEY (`supplierid`) REFERENCES `suppliers` (`supplierid`);

--
-- Constraints for table `stockcatproperties`
--
ALTER TABLE `stockcatproperties`
  ADD CONSTRAINT `stockcatproperties_ibfk_1` FOREIGN KEY (`categoryid`) REFERENCES `stockcategory` (`categoryid`);

--
-- Constraints for table `stockcheckfreeze`
--
ALTER TABLE `stockcheckfreeze`
  ADD CONSTRAINT `stockcheckfreeze_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `stockcheckfreeze_ibfk_2` FOREIGN KEY (`loccode`) REFERENCES `locations` (`loccode`);

--
-- Constraints for table `stockcounts`
--
ALTER TABLE `stockcounts`
  ADD CONSTRAINT `stockcounts_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `stockcounts_ibfk_2` FOREIGN KEY (`loccode`) REFERENCES `locations` (`loccode`);

--
-- Constraints for table `stockitemproperties`
--
ALTER TABLE `stockitemproperties`
  ADD CONSTRAINT `stockitemproperties_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `stockitemproperties_ibfk_2` FOREIGN KEY (`stkcatpropid`) REFERENCES `stockcatproperties` (`stkcatpropid`),
  ADD CONSTRAINT `stockitemproperties_ibfk_3` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `stockitemproperties_ibfk_4` FOREIGN KEY (`stkcatpropid`) REFERENCES `stockcatproperties` (`stkcatpropid`),
  ADD CONSTRAINT `stockitemproperties_ibfk_5` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `stockitemproperties_ibfk_6` FOREIGN KEY (`stkcatpropid`) REFERENCES `stockcatproperties` (`stkcatpropid`);

--
-- Constraints for table `stockmaster`
--
ALTER TABLE `stockmaster`
  ADD CONSTRAINT `stockmaster_ibfk_1` FOREIGN KEY (`categoryid`) REFERENCES `stockcategory` (`categoryid`),
  ADD CONSTRAINT `stockmaster_ibfk_2` FOREIGN KEY (`taxcatid`) REFERENCES `taxcategories` (`taxcatid`);

--
-- Constraints for table `stockmoves`
--
ALTER TABLE `stockmoves`
  ADD CONSTRAINT `stockmoves_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `stockmoves_ibfk_2` FOREIGN KEY (`type`) REFERENCES `systypes` (`typeid`),
  ADD CONSTRAINT `stockmoves_ibfk_3` FOREIGN KEY (`loccode`) REFERENCES `locations` (`loccode`),
  ADD CONSTRAINT `stockmoves_ibfk_4` FOREIGN KEY (`prd`) REFERENCES `periods` (`periodno`);

--
-- Constraints for table `stockmovestaxes`
--
ALTER TABLE `stockmovestaxes`
  ADD CONSTRAINT `stockmovestaxes_ibfk_1` FOREIGN KEY (`taxauthid`) REFERENCES `taxauthorities` (`taxid`),
  ADD CONSTRAINT `stockmovestaxes_ibfk_2` FOREIGN KEY (`stkmoveno`) REFERENCES `stockmoves` (`stkmoveno`),
  ADD CONSTRAINT `stockmovestaxes_ibfk_3` FOREIGN KEY (`stkmoveno`) REFERENCES `stockmoves` (`stkmoveno`),
  ADD CONSTRAINT `stockmovestaxes_ibfk_4` FOREIGN KEY (`stkmoveno`) REFERENCES `stockmoves` (`stkmoveno`);

--
-- Constraints for table `stockrequest`
--
ALTER TABLE `stockrequest`
  ADD CONSTRAINT `stockrequest_ibfk_1` FOREIGN KEY (`loccode`) REFERENCES `locations` (`loccode`),
  ADD CONSTRAINT `stockrequest_ibfk_2` FOREIGN KEY (`departmentid`) REFERENCES `departments` (`departmentid`),
  ADD CONSTRAINT `stockrequest_ibfk_3` FOREIGN KEY (`loccode`) REFERENCES `locations` (`loccode`),
  ADD CONSTRAINT `stockrequest_ibfk_4` FOREIGN KEY (`departmentid`) REFERENCES `departments` (`departmentid`);

--
-- Constraints for table `stockrequestitems`
--
ALTER TABLE `stockrequestitems`
  ADD CONSTRAINT `stockrequestitems_ibfk_1` FOREIGN KEY (`dispatchid`) REFERENCES `stockrequest` (`dispatchid`),
  ADD CONSTRAINT `stockrequestitems_ibfk_2` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `stockrequestitems_ibfk_3` FOREIGN KEY (`dispatchid`) REFERENCES `stockrequest` (`dispatchid`),
  ADD CONSTRAINT `stockrequestitems_ibfk_4` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`);

--
-- Constraints for table `stockserialitems`
--
ALTER TABLE `stockserialitems`
  ADD CONSTRAINT `stockserialitems_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `stockserialitems_ibfk_2` FOREIGN KEY (`loccode`) REFERENCES `locations` (`loccode`);

--
-- Constraints for table `stockserialmoves`
--
ALTER TABLE `stockserialmoves`
  ADD CONSTRAINT `stockserialmoves_ibfk_1` FOREIGN KEY (`stockmoveno`) REFERENCES `stockmoves` (`stkmoveno`),
  ADD CONSTRAINT `stockserialmoves_ibfk_2` FOREIGN KEY (`stockid`, `serialno`) REFERENCES `stockserialitems` (`stockid`, `serialno`);

--
-- Constraints for table `suppallocs`
--
ALTER TABLE `suppallocs`
  ADD CONSTRAINT `suppallocs_ibfk_1` FOREIGN KEY (`transid_allocfrom`) REFERENCES `supptrans` (`id`),
  ADD CONSTRAINT `suppallocs_ibfk_2` FOREIGN KEY (`transid_allocto`) REFERENCES `supptrans` (`id`);

--
-- Constraints for table `suppliercontacts`
--
ALTER TABLE `suppliercontacts`
  ADD CONSTRAINT `suppliercontacts_ibfk_1` FOREIGN KEY (`supplierid`) REFERENCES `suppliers` (`supplierid`);

--
-- Constraints for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD CONSTRAINT `suppliers_ibfk_1` FOREIGN KEY (`currcode`) REFERENCES `currencies` (`currabrev`),
  ADD CONSTRAINT `suppliers_ibfk_2` FOREIGN KEY (`paymentterms`) REFERENCES `paymentterms` (`termsindicator`),
  ADD CONSTRAINT `suppliers_ibfk_3` FOREIGN KEY (`taxgroupid`) REFERENCES `taxgroups` (`taxgroupid`);

--
-- Constraints for table `supptrans`
--
ALTER TABLE `supptrans`
  ADD CONSTRAINT `supptrans_ibfk_1` FOREIGN KEY (`type`) REFERENCES `systypes` (`typeid`),
  ADD CONSTRAINT `supptrans_ibfk_2` FOREIGN KEY (`supplierno`) REFERENCES `suppliers` (`supplierid`);

--
-- Constraints for table `supptranstaxes`
--
ALTER TABLE `supptranstaxes`
  ADD CONSTRAINT `supptranstaxes_ibfk_1` FOREIGN KEY (`taxauthid`) REFERENCES `taxauthorities` (`taxid`),
  ADD CONSTRAINT `supptranstaxes_ibfk_2` FOREIGN KEY (`supptransid`) REFERENCES `supptrans` (`id`);

--
-- Constraints for table `taxauthorities`
--
ALTER TABLE `taxauthorities`
  ADD CONSTRAINT `taxauthorities_ibfk_1` FOREIGN KEY (`taxglcode`) REFERENCES `chartmaster` (`accountcode`),
  ADD CONSTRAINT `taxauthorities_ibfk_2` FOREIGN KEY (`purchtaxglaccount`) REFERENCES `chartmaster` (`accountcode`);

--
-- Constraints for table `taxauthrates`
--
ALTER TABLE `taxauthrates`
  ADD CONSTRAINT `taxauthrates_ibfk_1` FOREIGN KEY (`taxauthority`) REFERENCES `taxauthorities` (`taxid`),
  ADD CONSTRAINT `taxauthrates_ibfk_2` FOREIGN KEY (`taxcatid`) REFERENCES `taxcategories` (`taxcatid`),
  ADD CONSTRAINT `taxauthrates_ibfk_3` FOREIGN KEY (`dispatchtaxprovince`) REFERENCES `taxprovinces` (`taxprovinceid`);

--
-- Constraints for table `taxgrouptaxes`
--
ALTER TABLE `taxgrouptaxes`
  ADD CONSTRAINT `taxgrouptaxes_ibfk_1` FOREIGN KEY (`taxgroupid`) REFERENCES `taxgroups` (`taxgroupid`),
  ADD CONSTRAINT `taxgrouptaxes_ibfk_2` FOREIGN KEY (`taxauthid`) REFERENCES `taxauthorities` (`taxid`);

--
-- Constraints for table `woitems`
--
ALTER TABLE `woitems`
  ADD CONSTRAINT `woitems_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `woitems_ibfk_2` FOREIGN KEY (`wo`) REFERENCES `workorders` (`wo`);

--
-- Constraints for table `worequirements`
--
ALTER TABLE `worequirements`
  ADD CONSTRAINT `worequirements_ibfk_1` FOREIGN KEY (`wo`) REFERENCES `workorders` (`wo`),
  ADD CONSTRAINT `worequirements_ibfk_2` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `worequirements_ibfk_3` FOREIGN KEY (`wo`, `parentstockid`) REFERENCES `woitems` (`wo`, `stockid`);

--
-- Constraints for table `workcentres`
--
ALTER TABLE `workcentres`
  ADD CONSTRAINT `workcentres_ibfk_1` FOREIGN KEY (`location`) REFERENCES `locations` (`loccode`);

--
-- Constraints for table `workorders`
--
ALTER TABLE `workorders`
  ADD CONSTRAINT `worksorders_ibfk_1` FOREIGN KEY (`loccode`) REFERENCES `locations` (`loccode`);

--
-- Constraints for table `www_users`
--
ALTER TABLE `www_users`
  ADD CONSTRAINT `www_users_ibfk_1` FOREIGN KEY (`defaultlocation`) REFERENCES `locations` (`loccode`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
