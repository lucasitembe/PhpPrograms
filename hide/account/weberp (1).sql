-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2015 at 10:36 AM
-- Server version: 5.6.21
-- PHP Version: 5.5.19

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
  `parentgroupname` varchar(30) NOT NULL
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
  `sectionname` text NOT NULL
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
(23, 'Good Stuff'),
(25, 'Cash'),
(30, 'Amounts Payable'),
(50, 'Financed By');

-- --------------------------------------------------------

--
-- Table structure for table `areas`
--

CREATE TABLE IF NOT EXISTS `areas` (
  `areacode` char(3) NOT NULL,
  `areadescription` varchar(25) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `areas`
--

INSERT INTO `areas` (`areacode`, `areadescription`) VALUES
('230', 'Kigoma');

-- --------------------------------------------------------

--
-- Table structure for table `assetmanager`
--

CREATE TABLE IF NOT EXISTS `assetmanager` (
`id` int(11) NOT NULL,
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `serialno` varchar(30) NOT NULL DEFAULT '',
  `location` varchar(15) NOT NULL DEFAULT '',
  `cost` double NOT NULL DEFAULT '0',
  `depn` double NOT NULL DEFAULT '0',
  `datepurchased` date NOT NULL DEFAULT '0000-00-00',
  `disposalvalue` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `audittrail`
--

CREATE TABLE IF NOT EXISTS `audittrail` (
  `transactiondate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `userid` varchar(20) NOT NULL DEFAULT '',
  `querystring` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `audittrail`
--

INSERT INTO `audittrail` (`transactiondate`, `userid`, `querystring`) VALUES
('2015-05-25 10:05:20', 'admin', 'UPDATE config\n				SET confvalue=''2015-05-25''\n				WHERE confname=''DB_Maintenance_LastRun'''),
('2015-05-25 10:05:20', 'admin', 'UPDATE currencies SET rate=''0''\n																		WHERE currabrev=''AUD'''),
('2015-05-25 10:05:20', 'admin', 'UPDATE currencies SET rate=''0''\n																		WHERE currabrev=''GBP'''),
('2015-05-25 10:05:20', 'admin', 'UPDATE currencies SET rate=''0''\n																		WHERE currabrev=''EUR'''),
('2015-05-25 10:05:20', 'admin', 'UPDATE currencies SET rate=''0''\n																		WHERE currabrev=''KES'''),
('2015-05-25 10:05:20', 'admin', 'UPDATE currencies SET rate=''0''\n																		WHERE currabrev=''CHF'''),
('2015-05-25 10:05:20', 'admin', 'UPDATE config SET confvalue = ''2015-05-25'' WHERE confname=''UpdateCurrencyRatesDaily'''),
('2015-05-25 11:18:25', 'admin', 'UPDATE www_users SET lastvisitdate=''2015-05-25 11:18:25''\n							WHERE www_users.userid=''admin'''),
('2015-05-25 11:19:16', 'admin', 'UPDATE www_users SET lastvisitdate=''2015-05-25 11:19:16''\n							WHERE www_users.userid=''admin'''),
('2015-05-25 11:23:43', 'admin', 'UPDATE www_users SET lastvisitdate=''2015-05-25 11:23:43''\n							WHERE www_users.userid=''admin'''),
('2015-05-25 13:54:47', 'admin', 'INSERT INTO periods VALUES (0,''2015-05-31'')'),
('2015-05-25 13:54:48', 'admin', 'INSERT INTO periods VALUES (1,''2015-06-30'')'),
('2015-05-25 14:23:53', 'admin', 'INSERT INTO chartmaster (accountcode,\n						accountname,\n						group_)\n					VALUES (''1011'',\n							''CONSULTATION XRAY'',\n							''Revenue'')'),
('2015-05-25 14:26:56', 'admin', 'DELETE FROM chartmaster WHERE accountcode= ''1011'''),
('2015-05-25 14:27:18', 'admin', 'DELETE FROM chartmaster WHERE accountcode= ''1'''),
('2015-05-25 14:27:55', 'admin', 'DELETE FROM chartmaster WHERE accountcode= ''1020'''),
('2015-05-25 14:28:17', 'admin', 'DELETE FROM chartmaster WHERE accountcode= ''2050'''),
('2015-05-25 14:32:37', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''2'',\n													''2015-07-31'')'),
('2015-05-25 14:32:37', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''3'',\n													''2015-08-31'')'),
('2015-05-25 14:32:37', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''4'',\n													''2015-09-30'')'),
('2015-05-25 14:32:37', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''5'',\n													''2015-10-31'')'),
('2015-05-25 14:32:37', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''6'',\n													''2015-11-30'')'),
('2015-05-25 14:32:37', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''7'',\n													''2015-12-31'')'),
('2015-05-25 14:32:37', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''8'',\n													''2016-01-31'')'),
('2015-05-25 14:32:37', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''9'',\n													''2016-02-29'')'),
('2015-05-25 14:32:37', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''10'',\n													''2016-03-31'')'),
('2015-05-25 14:32:37', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''11'',\n													''2016-04-30'')'),
('2015-05-25 14:34:58', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''-1'',\n													''2015-04-30'')'),
('2015-05-25 14:34:59', 'admin', 'INSERT INTO chartdetails (accountcode, period)\n					SELECT chartmaster.accountcode, periods.periodno\n					FROM (chartmaster CROSS JOIN periods)\n					LEFT JOIN chartdetails ON chartmaster.accountcode = chartdetails.accountcode\n					AND periods.periodno = chartdetails.period\n					WHERE (periods.periodno BETWEEN ''-1'' AND ''11'')\n					AND chartdetails.accountcode IS NULL'),
('2015-05-27 08:50:46', 'admin', 'UPDATE config\n				SET confvalue=''2015-05-27''\n				WHERE confname=''DB_Maintenance_LastRun'''),
('2015-05-27 08:50:47', 'admin', 'UPDATE currencies SET rate=''1.2873''\n																		WHERE currabrev=''AUD'''),
('2015-05-27 08:50:47', 'admin', 'UPDATE currencies SET rate=''0.6481''\n																		WHERE currabrev=''GBP'''),
('2015-05-27 08:50:48', 'admin', 'UPDATE currencies SET rate=''0.9151''\n																		WHERE currabrev=''EUR'''),
('2015-05-27 08:50:49', 'admin', 'UPDATE currencies SET rate=''98.4495''\n																		WHERE currabrev=''KES'''),
('2015-05-27 08:50:50', 'admin', 'UPDATE currencies SET rate=''0.9476''\n																		WHERE currabrev=''CHF'''),
('2015-05-27 08:50:50', 'admin', 'UPDATE config SET confvalue = ''2015-05-27'' WHERE confname=''UpdateCurrencyRatesDaily'''),
('2015-05-27 09:08:15', 'admin', 'INSERT INTO salestypes (typeabbrev,\n											sales_type)\n							VALUES (''1'',\n									''Credit Items'')'),
('2015-05-27 09:08:15', 'admin', 'UPDATE config\n					SET confvalue=''1''\n					WHERE confname=''DefaultPriceList'''),
('2015-05-27 09:08:28', 'admin', 'INSERT INTO salestypes (typeabbrev,\n											sales_type)\n							VALUES (''2'',\n									''Cash Items'')'),
('2015-05-27 09:09:22', 'admin', 'INSERT INTO debtortype\n						(typename)\n					VALUES (''Patients'')'),
('2015-05-27 13:19:24', 'admin', 'INSERT INTO fixedassetcategories (categoryid,\n												categorydescription,\n												costact,\n												depnact,\n												disposalact,\n												accumdepnact)\n								VALUES (''COMP10'',\n										''computers'',\n										''2720'',\n										''4100'',\n										''4100'',\n										''1670'')'),
('2015-05-27 14:49:36', 'admin', 'DELETE FROM fixedassetcategories WHERE categoryid=''COMP10'''),
('2015-05-27 14:52:45', 'admin', 'INSERT INTO fixedassetcategories (categoryid,\n												categorydescription,\n												costact,\n												depnact,\n												disposalact,\n												accumdepnact)\n								VALUES (''COMP20'',\n										''Computers'',\n										''1650'',\n										''7500'',\n										''7500'',\n										''1700'')'),
('2015-05-27 14:56:11', 'admin', 'INSERT INTO fixedassetcategories (categoryid,\n												categorydescription,\n												costact,\n												depnact,\n												disposalact,\n												accumdepnact)\n								VALUES (''COMP30'',\n										''Server'',\n										''1730'',\n										''7500'',\n										''7500'',\n										''2720'')'),
('2015-05-27 15:07:53', 'admin', 'INSERT INTO fixedassetlocations\n				VALUES (''23'',\n						''Third Floor'',\n						'''')'),
('2015-05-27 15:08:16', 'admin', 'INSERT INTO fixedassetlocations\n				VALUES (''24'',\n						''First Floor'',\n						''23'')'),
('2015-05-27 15:11:03', 'admin', 'DELETE FROM fixedassetlocations WHERE locationid = ''24'''),
('2015-05-27 15:24:27', 'admin', 'UPDATE fixedassetlocations\n					SET locationdescription=''Third Floor'',\n						parentlocationid=''23''\n					WHERE locationid =''23'''),
('2015-05-27 16:15:34', 'admin', 'UPDATE www_users SET lastvisitdate=''2015-05-27 16:15:34''\n							WHERE www_users.userid=''admin'''),
('2015-05-27 16:17:04', 'admin', 'UPDATE www_users SET lastvisitdate=''2015-05-27 16:17:04''\n							WHERE www_users.userid=''admin'''),
('2015-05-27 16:17:21', 'admin', 'UPDATE www_users SET lastvisitdate=''2015-05-27 16:17:20''\n							WHERE www_users.userid=''admin'''),
('2015-05-27 16:18:22', 'admin', 'UPDATE www_users SET lastvisitdate=''2015-05-27 16:18:21''\n							WHERE www_users.userid=''admin'''),
('2015-05-27 16:18:44', 'admin', 'UPDATE www_users SET lastvisitdate=''2015-05-27 16:18:44''\n							WHERE www_users.userid=''admin'''),
('2015-05-27 16:59:26', 'admin', 'INSERT INTO fixedassetlocations\n				VALUES (''22'',\n						''Second Floor'',\n						'''')'),
('2015-05-27 16:59:41', 'admin', 'INSERT INTO fixedassetlocations\n				VALUES (''21'',\n						''First Floor'',\n						'''')'),
('2015-05-27 16:59:48', 'admin', 'UPDATE fixedassetlocations\n					SET locationdescription=''Third Floor'',\n						parentlocationid=''''\n					WHERE locationid =''23'''),
('2015-05-27 17:00:09', 'admin', 'INSERT INTO fixedassetlocations\n				VALUES (''24'',\n						''Forth Floor'',\n						'''')'),
('2015-05-27 17:02:52', 'admin', 'INSERT INTO fixedassetcategories (categoryid,\n												categorydescription,\n												costact,\n												depnact,\n												disposalact,\n												accumdepnact)\n								VALUES (''SEC200'',\n										''CCTV Cameras'',\n										''1700'',\n										''7500'',\n										''7500'',\n										''1700'')'),
('2015-05-27 17:05:31', 'admin', 'INSERT INTO fixedassetcategories (categoryid,\n												categorydescription,\n												costact,\n												depnact,\n												disposalact,\n												accumdepnact)\n								VALUES (''SEC201'',\n										''Computer Desks'',\n										''1700'',\n										''7500'',\n										''7500'',\n										''1700'')'),
('2015-05-27 17:10:56', 'admin', 'INSERT INTO fixedassets (description,\n											longdescription,\n											assetcategoryid,\n											assetlocation,\n											depntype,\n											depnrate,\n											barcode,\n											serialno)\n						VALUES (\n							''Computer Desks'',\n							''Computers desks used in reception '',\n							''COMP20'',\n							''21'',\n							''0'',\n							''0'',\n							''WBGTK0A1RY90RN'',\n							'''' )'),
('2015-05-27 17:12:31', 'admin', 'INSERT INTO fixedassets (description,\n											longdescription,\n											assetcategoryid,\n											assetlocation,\n											depntype,\n											depnrate,\n											barcode,\n											serialno)\n						VALUES (\n							''Laptop cables'',\n							''Power cables for laptop computers'',\n							''COMP20'',\n							''24'',\n							''0'',\n							''0.09'',\n							''WBGTK0A1RY90RN'',\n							'''' )'),
('2015-05-27 17:14:43', 'admin', 'UPDATE fixedassets\n					SET longdescription=''Computers desks used in reception '',\n						description=''Computer Desks'',\n						assetcategoryid=''COMP20'',\n						assetlocation=''21'',\n						depntype=''0'',\n						depnrate=''80'',\n						barcode=''WBGTK0A1RY90RN'',\n						serialno=''''\n					WHERE assetid=''1'''),
('2015-05-27 17:16:32', 'admin', 'INSERT INTO fixedassets (description,\n											longdescription,\n											assetcategoryid,\n											assetlocation,\n											depntype,\n											depnrate,\n											barcode,\n											serialno)\n						VALUES (\n							''Computer Desks'',\n							''Computer desks for use by reception unit'',\n							''SEC201'',\n							''21'',\n							''0'',\n							''50'',\n							'''',\n							'''' )'),
('2015-05-27 17:17:34', 'admin', 'INSERT INTO fixedassets (description,\n											longdescription,\n											assetcategoryid,\n											assetlocation,\n											depntype,\n											depnrate,\n											barcode,\n											serialno)\n						VALUES (\n							''Computer Servers'',\n							''Computer servers used by eHMS'',\n							''COMP30'',\n							''21'',\n							''0'',\n							''70'',\n							'''',\n							'''' )'),
('2015-05-27 17:18:08', 'admin', 'INSERT INTO fixedassets (description,\n											longdescription,\n											assetcategoryid,\n											assetlocation,\n											depntype,\n											depnrate,\n											barcode,\n											serialno)\n						VALUES (\n							''CCTY Camera'',\n							''CCTY Camera system used by the facility'',\n							''SEC200'',\n							''21'',\n							''0'',\n							''70'',\n							'''',\n							'''' )'),
('2015-05-27 17:21:30', 'admin', 'DELETE FROM fixedassets WHERE assetid=''1'''),
('2015-05-27 17:25:31', 'admin', 'INSERT INTO debtorsmaster (\n							debtorno,\n							name,\n							address1,\n							address2,\n							address3,\n							address4,\n							address5,\n							address6,\n							currcode,\n							clientsince,\n							holdreason,\n							paymentterms,\n							discount,\n							discountcode,\n							pymtdiscount,\n							creditlimit,\n							salestype,\n							invaddrbranch,\n							taxref,\n							customerpoline,\n							typeid,\n							language_id)\n				VALUES (''1'',\n						''Adelard Kiliba'',\n						''Mbezi Jogoo'',\n						'''',\n						'''',\n						'''',\n						'''',\n						''Tanzania, United Rep. of'',\n						''USD'',\n						''2015-05-27'',\n						''1'',\n						''20'',\n						''0'',\n						'''',\n						''0'',\n						''1000'',\n						''2'',\n						''0'',\n						'''',\n						''0'',\n						''1'',\n						''sw_KE.utf8'')'),
('2015-05-27 17:29:21', 'admin', 'INSERT INTO debtortype\n						(typename)\n					VALUES (''NHIF'')'),
('2015-05-27 17:29:31', 'admin', 'INSERT INTO debtortype\n						(typename)\n					VALUES (''AAR'')'),
('2015-05-27 17:29:40', 'admin', 'INSERT INTO debtortype\n						(typename)\n					VALUES (''TANESCO'')'),
('2015-05-27 17:31:52', 'admin', 'INSERT INTO debtortype\n						(typename)\n					VALUES (''GPITG LTD'')'),
('2015-05-27 17:41:28', 'admin', 'INSERT INTO debtortype\n						(typename)\n					VALUES (''Strategies'')'),
('2015-05-27 17:42:13', 'admin', 'INSERT INTO debtortype\n						(typename)\n					VALUES (''UDSM'')'),
('2015-05-27 18:34:15', 'admin', 'INSERT INTO debtorsmaster (\n							debtorno,\n							name,\n							address1,\n							address2,\n							address3,\n							address4,\n							address5,\n							address6,\n							currcode,\n							clientsince,\n							holdreason,\n							paymentterms,\n							discount,\n							discountcode,\n							pymtdiscount,\n							creditlimit,\n							salestype,\n							invaddrbranch,\n							taxref,\n							customerpoline,\n							typeid,\n							language_id)\n				VALUES (''2'',\n						''Haruna Duniz'',\n						''Kinondoni'',\n						'''',\n						''Dar-es-salaam'',\n						'''',\n						'''',\n						''Tanzania, United Rep. of'',\n						''USD'',\n						''2015-05-27'',\n						''1'',\n						''CA'',\n						''0'',\n						'''',\n						''0'',\n						''1000'',\n						''2'',\n						''0'',\n						'''',\n						''0'',\n						''1'',\n						''en_US.utf8'')'),
('2015-05-27 18:38:20', 'admin', 'INSERT INTO debtorsmaster (\n							debtorno,\n							name,\n							address1,\n							address2,\n							address3,\n							address4,\n							address5,\n							address6,\n							currcode,\n							clientsince,\n							holdreason,\n							paymentterms,\n							discount,\n							discountcode,\n							pymtdiscount,\n							creditlimit,\n							salestype,\n							invaddrbranch,\n							taxref,\n							customerpoline,\n							typeid,\n							language_id)\n				VALUES (''3'',\n						''Alex Ntimigihwa'',\n						''Kijogole Road'',\n						'''',\n						''Dar-es-salaam'',\n						'''',\n						'''',\n						''Tanzania, United Rep. of'',\n						''USD'',\n						''2015-05-27'',\n						''1'',\n						''20'',\n						''0'',\n						'''',\n						''0'',\n						''1000'',\n						''2'',\n						''0'',\n						'''',\n						''0'',\n						''1'',\n						''sw_KE.utf8'')'),
('2015-05-27 18:40:31', 'admin', 'INSERT INTO debtorsmaster (\n							debtorno,\n							name,\n							address1,\n							address2,\n							address3,\n							address4,\n							address5,\n							address6,\n							currcode,\n							clientsince,\n							holdreason,\n							paymentterms,\n							discount,\n							discountcode,\n							pymtdiscount,\n							creditlimit,\n							salestype,\n							invaddrbranch,\n							taxref,\n							customerpoline,\n							typeid,\n							language_id)\n				VALUES (''4'',\n						''Mohamed Kijilugola'',\n						''Usukumizwe Avenue'',\n						'''',\n						''Dar-es-salaam'',\n						'''',\n						'''',\n						''Tanzania, United Rep. of'',\n						''USD'',\n						''2015-05-27'',\n						''1'',\n						''CA'',\n						''0'',\n						'''',\n						''0'',\n						''1000'',\n						''2'',\n						''0'',\n						'''',\n						''0'',\n						''1'',\n						''sw_KE.utf8'')'),
('2015-05-27 18:56:15', 'admin', 'INSERT INTO areas (areacode,\n									areadescription\n								) VALUES (\n									''230'',\n									''Kigoma''\n								)'),
('2015-05-27 19:00:42', 'admin', 'INSERT INTO suppliers (supplierid,\n										suppname,\n										address1,\n										address2,\n										address3,\n										address4,\n										address5,\n										address6,\n										telephone,\n										fax,\n										email,\n										url,\n										supptype,\n										currcode,\n										suppliersince,\n										paymentterms,\n										bankpartics,\n										bankref,\n										bankact,\n										remittance,\n										taxgroupid,\n										factorcompanyid,\n										lat,\n										lng,\n										taxref)\n								 VALUES (''23'',\n								 	''adek'',\n									'''',\n									'''',\n									'''',\n									'''',\n									'''',\n									'''',\n									'''',\n									'''',\n									'''',\n									'''',\n									'''',\n									''USD'',\n									''2015-05-27'',\n									''20'',\n									'''',\n									''0'',\n									'''',\n									''0'',\n									''1'',\n									''0'',\n									''0'',\n									''0'',\n									'''')'),
('2015-05-27 19:03:08', 'admin', 'INSERT INTO suppliercontacts (supplierid,\n											contact,\n											position,\n											tel,\n											fax,\n											email,\n											mobile)\n				VALUES (''23'',\n					''Ade'',\n					'''',\n					'''',\n					'''',\n					'''',\n					'''')'),
('2015-05-27 19:03:28', 'admin', 'DELETE FROM suppliercontacts\n			WHERE contact=''Ade''\n			AND supplierid = ''23'''),
('2015-05-27 19:05:03', 'admin', 'INSERT INTO suppliercontacts (supplierid,\n											contact,\n											position,\n											tel,\n											fax,\n											email,\n											mobile)\n				VALUES (''23'',\n					''were'',\n					'''',\n					'''',\n					'''',\n					'''',\n					'''')'),
('2015-05-27 19:05:09', 'admin', 'DELETE FROM suppliercontacts\n			WHERE contact=''were''\n			AND supplierid = ''23'''),
('2015-05-27 19:05:22', 'admin', 'INSERT INTO suppliers (supplierid,\n										suppname,\n										address1,\n										address2,\n										address3,\n										address4,\n										address5,\n										address6,\n										telephone,\n										fax,\n										email,\n										url,\n										supptype,\n										currcode,\n										suppliersince,\n										paymentterms,\n										bankpartics,\n										bankref,\n										bankact,\n										remittance,\n										taxgroupid,\n										factorcompanyid,\n										lat,\n										lng,\n										taxref)\n								 VALUES (''235'',\n								 	''455'',\n									'''',\n									'''',\n									'''',\n									'''',\n									'''',\n									'''',\n									'''',\n									'''',\n									'''',\n									'''',\n									'''',\n									''USD'',\n									''2015-05-27'',\n									''20'',\n									'''',\n									''0'',\n									'''',\n									''0'',\n									''1'',\n									''0'',\n									''0'',\n									''0'',\n									'''')'),
('2015-05-27 21:56:44', 'admin', 'UPDATE companies SET coyname=''Kairuki Referral Hospital'',\n									companynumber = '''',\n									gstno=''not entered yet'',\n									regoffice1=''Mikocheni B'',\n									regoffice2=''PO Box 123'',\n									regoffice3=''Mikocheni'',\n									regoffice4=''Dar-es-salaam'',\n									regoffice5='''',\n									regoffice6=''Tanzania'',\n									telephone=''+255717531539'',\n									fax=''+255653302114'',\n									email=''infor@kairukihospital.com'',\n									currencydefault=''USD'',\n									debtorsact=''1100'',\n									pytdiscountact=''4900'',\n									creditorsact=''2100'',\n									payrollact=''2400'',\n									grnact=''2150'',\n									exchangediffact=''4200'',\n									purchasesexchangediffact=''5200'',\n									retainedearnings=''3500'',\n									gllink_debtors=''1'',\n									gllink_creditors=''1'',\n									gllink_stock=''1'',\n									freightact=''5600''\n								WHERE coycode=1'),
('2015-05-27 22:55:53', 'admin', 'UPDATE fixedassets\n						SET assetlocation=''21''\n						WHERE assetid=''2'''),
('2015-05-27 22:55:57', 'admin', 'UPDATE fixedassets\n						SET assetlocation=''23''\n						WHERE assetid=''2'''),
('2015-05-27 22:56:00', 'admin', 'UPDATE fixedassets\n						SET assetlocation=''24''\n						WHERE assetid=''2'''),
('2015-05-28 05:40:46', 'admin', 'UPDATE www_users SET lastvisitdate=''2015-05-28 05:40:46''\n							WHERE www_users.userid=''admin'''),
('2015-05-28 06:13:32', 'admin', 'UPDATE www_users SET lastvisitdate=''2015-05-28 06:13:32''\n							WHERE www_users.userid=''admin'''),
('2015-05-28 06:17:23', 'admin', 'UPDATE www_users SET lastvisitdate=''2015-05-28 06:17:23''\n							WHERE www_users.userid=''admin'''),
('2015-05-28 06:19:21', 'admin', 'UPDATE config\n				SET confvalue=''2015-05-28''\n				WHERE confname=''DB_Maintenance_LastRun'''),
('2015-05-28 06:19:21', 'admin', 'UPDATE currencies SET rate=''0''\n																		WHERE currabrev=''AUD'''),
('2015-05-28 06:19:21', 'admin', 'UPDATE currencies SET rate=''0''\n																		WHERE currabrev=''GBP'''),
('2015-05-28 06:19:21', 'admin', 'UPDATE currencies SET rate=''0''\n																		WHERE currabrev=''EUR'''),
('2015-05-28 06:19:22', 'admin', 'UPDATE currencies SET rate=''0''\n																		WHERE currabrev=''KES'''),
('2015-05-28 06:19:22', 'admin', 'UPDATE currencies SET rate=''0''\n																		WHERE currabrev=''CHF'''),
('2015-05-28 06:19:22', 'admin', 'UPDATE config SET confvalue = ''2015-05-28'' WHERE confname=''UpdateCurrencyRatesDaily'''),
('2015-05-28 06:19:22', 'admin', 'UPDATE www_users SET lastvisitdate=''2015-05-28 06:19:22''\n							WHERE www_users.userid=''admin'''),
('2015-05-28 12:32:44', 'admin', 'INSERT INTO accountsection (sectionid,\n											sectionname\n										) VALUES (\n											''23'',\n											''rtyy'')'),
('2015-05-28 12:33:54', 'admin', 'UPDATE accountsection SET sectionname=''Good Stuff''\n				WHERE sectionid = ''23'''),
('2015-05-28 12:38:53', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''12'',\n													''2016-05-31'')'),
('2015-05-28 12:38:53', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''-2'',\n													''2015-03-31'')'),
('2015-05-28 12:38:53', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''-3'',\n													''2015-02-28'')'),
('2015-05-28 12:38:54', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''-4'',\n													''2015-01-31'')'),
('2015-05-28 12:38:54', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''-5'',\n													''2014-12-31'')'),
('2015-05-28 12:38:54', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''-6'',\n													''2014-11-30'')'),
('2015-05-28 12:38:54', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''-7'',\n													''2014-10-31'')'),
('2015-05-28 12:38:54', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''-8'',\n													''2014-09-30'')'),
('2015-05-28 12:38:54', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''-9'',\n													''2014-08-31'')'),
('2015-05-28 12:38:54', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''-10'',\n													''2014-07-31'')'),
('2015-05-28 12:38:54', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''-11'',\n													''2014-06-30'')'),
('2015-05-28 12:38:54', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''-12'',\n													''2014-05-31'')'),
('2015-05-28 12:38:54', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''-13'',\n													''2014-04-30'')'),
('2015-05-28 12:38:54', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''13'',\n													''2016-06-30'')'),
('2015-05-28 12:38:54', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''14'',\n													''2016-07-31'')'),
('2015-05-28 12:38:54', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''15'',\n													''2016-08-31'')'),
('2015-05-28 12:38:54', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''16'',\n													''2016-09-30'')'),
('2015-05-28 12:38:54', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''17'',\n													''2016-10-31'')'),
('2015-05-28 12:38:54', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''18'',\n													''2016-11-30'')'),
('2015-05-28 12:38:54', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''19'',\n													''2016-12-31'')'),
('2015-05-28 12:38:54', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''20'',\n													''2017-01-31'')'),
('2015-05-28 12:38:54', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''21'',\n													''2017-02-28'')'),
('2015-05-28 12:38:54', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''22'',\n													''2017-03-31'')'),
('2015-05-28 12:38:54', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''23'',\n													''2017-04-30'')'),
('2015-05-28 12:38:56', 'admin', 'INSERT INTO chartdetails (accountcode, period)\n					SELECT chartmaster.accountcode, periods.periodno\n					FROM (chartmaster CROSS JOIN periods)\n					LEFT JOIN chartdetails ON chartmaster.accountcode = chartdetails.accountcode\n					AND periods.periodno = chartdetails.period\n					WHERE (periods.periodno BETWEEN ''-13'' AND ''23'')\n					AND chartdetails.accountcode IS NULL'),
('2015-05-28 12:39:02', 'admin', 'INSERT INTO periods (periodno,\n													lastdate_in_period)\n												VALUES (\n													''24'',\n													''2017-05-31'')'),
('2015-05-28 12:39:02', 'admin', 'INSERT INTO chartdetails (accountcode, period)\n					SELECT chartmaster.accountcode, periods.periodno\n					FROM (chartmaster CROSS JOIN periods)\n					LEFT JOIN chartdetails ON chartmaster.accountcode = chartdetails.accountcode\n					AND periods.periodno = chartdetails.period\n					WHERE (periods.periodno BETWEEN ''-13'' AND ''24'')\n					AND chartdetails.accountcode IS NULL'),
('2015-05-28 12:42:06', 'admin', 'INSERT INTO tags values(NULL, ''stuff 2'')'),
('2015-05-28 12:42:30', 'admin', 'INSERT INTO tags values(NULL, ''stuff 2'')'),
('2015-05-28 20:11:31', 'admin', 'INSERT INTO suppliers (supplierid,\n										suppname,\n										address1,\n										address2,\n										address3,\n										address4,\n										address5,\n										address6,\n										telephone,\n										fax,\n										email,\n										url,\n										supptype,\n										currcode,\n										suppliersince,\n										paymentterms,\n										bankpartics,\n										bankref,\n										bankact,\n										remittance,\n										taxgroupid,\n										factorcompanyid,\n										lat,\n										lng,\n										taxref)\n								 VALUES (''12'',\n								 	''2333'',\n									'''',\n									'''',\n									'''',\n									'''',\n									'''',\n									'''',\n									'''',\n									'''',\n									'''',\n									'''',\n									'''',\n									''USD'',\n									''2015-05-28'',\n									''20'',\n									'''',\n									''0'',\n									'''',\n									''0'',\n									''1'',\n									''0'',\n									''0'',\n									''0'',\n									'''')'),
('2015-05-28 20:13:12', 'admin', 'INSERT INTO suppliercontacts (supplierid,\n											contact,\n											position,\n											tel,\n											fax,\n											email,\n											mobile)\n				VALUES (''12'',\n					''234'',\n					'''',\n					'''',\n					'''',\n					'''',\n					'''')'),
('2015-05-29 10:58:32', 'admin', 'UPDATE www_users SET lastvisitdate=''2015-05-29 10:58:32''\n							WHERE www_users.userid=''admin''');

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
  `importformat` varchar(10) NOT NULL DEFAULT ''''''
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
`banktransid` bigint(20) NOT NULL,
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
  `currcode` char(3) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `autoissue` tinyint(4) NOT NULL DEFAULT '0'
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
  `bfwdbudget` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `chartdetails`
--

INSERT INTO `chartdetails` (`accountcode`, `period`, `budget`, `actual`, `bfwd`, `bfwdbudget`) VALUES
('1010', -13, 0, 0, 0, 0),
('1010', -12, 0, 0, 0, 0),
('1010', -11, 0, 0, 0, 0),
('1010', -10, 0, 0, 0, 0),
('1010', -9, 0, 0, 0, 0),
('1010', -8, 0, 0, 0, 0),
('1010', -7, 0, 0, 0, 0),
('1010', -6, 0, 0, 0, 0),
('1010', -5, 0, 0, 0, 0),
('1010', -4, 0, 0, 0, 0),
('1010', -3, 0, 0, 0, 0),
('1010', -2, 0, 0, 0, 0),
('1010', -1, 0, 0, 0, 0),
('1010', 0, 0, 0, 0, 0),
('1010', 1, 0, 0, 0, 0),
('1010', 2, 0, 0, 0, 0),
('1010', 3, 0, 0, 0, 0),
('1010', 4, 0, 0, 0, 0),
('1010', 5, 0, 0, 0, 0),
('1010', 6, 0, 0, 0, 0),
('1010', 7, 0, 0, 0, 0),
('1010', 8, 0, 0, 0, 0),
('1010', 9, 0, 0, 0, 0),
('1010', 10, 0, 0, 0, 0),
('1010', 11, 0, 0, 0, 0),
('1010', 12, 0, 0, 0, 0),
('1010', 13, 0, 0, 0, 0),
('1010', 14, 0, 0, 0, 0),
('1010', 15, 0, 0, 0, 0),
('1010', 16, 0, 0, 0, 0),
('1010', 17, 0, 0, 0, 0),
('1010', 18, 0, 0, 0, 0),
('1010', 19, 0, 0, 0, 0),
('1010', 20, 0, 0, 0, 0),
('1010', 21, 0, 0, 0, 0),
('1010', 22, 0, 0, 0, 0),
('1010', 23, 0, 0, 0, 0),
('1010', 24, 0, 0, 0, 0),
('1030', -13, 0, 0, 0, 0),
('1030', -12, 0, 0, 0, 0),
('1030', -11, 0, 0, 0, 0),
('1030', -10, 0, 0, 0, 0),
('1030', -9, 0, 0, 0, 0),
('1030', -8, 0, 0, 0, 0),
('1030', -7, 0, 0, 0, 0),
('1030', -6, 0, 0, 0, 0),
('1030', -5, 0, 0, 0, 0),
('1030', -4, 0, 0, 0, 0),
('1030', -3, 0, 0, 0, 0),
('1030', -2, 0, 0, 0, 0),
('1030', -1, 0, 0, 0, 0),
('1030', 0, 0, 0, 0, 0),
('1030', 1, 0, 0, 0, 0),
('1030', 2, 0, 0, 0, 0),
('1030', 3, 0, 0, 0, 0),
('1030', 4, 0, 0, 0, 0),
('1030', 5, 0, 0, 0, 0),
('1030', 6, 0, 0, 0, 0),
('1030', 7, 0, 0, 0, 0),
('1030', 8, 0, 0, 0, 0),
('1030', 9, 0, 0, 0, 0),
('1030', 10, 0, 0, 0, 0),
('1030', 11, 0, 0, 0, 0),
('1030', 12, 0, 0, 0, 0),
('1030', 13, 0, 0, 0, 0),
('1030', 14, 0, 0, 0, 0),
('1030', 15, 0, 0, 0, 0),
('1030', 16, 0, 0, 0, 0),
('1030', 17, 0, 0, 0, 0),
('1030', 18, 0, 0, 0, 0),
('1030', 19, 0, 0, 0, 0),
('1030', 20, 0, 0, 0, 0),
('1030', 21, 0, 0, 0, 0),
('1030', 22, 0, 0, 0, 0),
('1030', 23, 0, 0, 0, 0),
('1030', 24, 0, 0, 0, 0),
('1040', -13, 0, 0, 0, 0),
('1040', -12, 0, 0, 0, 0),
('1040', -11, 0, 0, 0, 0),
('1040', -10, 0, 0, 0, 0),
('1040', -9, 0, 0, 0, 0),
('1040', -8, 0, 0, 0, 0),
('1040', -7, 0, 0, 0, 0),
('1040', -6, 0, 0, 0, 0),
('1040', -5, 0, 0, 0, 0),
('1040', -4, 0, 0, 0, 0),
('1040', -3, 0, 0, 0, 0),
('1040', -2, 0, 0, 0, 0),
('1040', -1, 0, 0, 0, 0),
('1040', 0, 0, 0, 0, 0),
('1040', 1, 0, 0, 0, 0),
('1040', 2, 0, 0, 0, 0),
('1040', 3, 0, 0, 0, 0),
('1040', 4, 0, 0, 0, 0),
('1040', 5, 0, 0, 0, 0),
('1040', 6, 0, 0, 0, 0),
('1040', 7, 0, 0, 0, 0),
('1040', 8, 0, 0, 0, 0),
('1040', 9, 0, 0, 0, 0),
('1040', 10, 0, 0, 0, 0),
('1040', 11, 0, 0, 0, 0),
('1040', 12, 0, 0, 0, 0),
('1040', 13, 0, 0, 0, 0),
('1040', 14, 0, 0, 0, 0),
('1040', 15, 0, 0, 0, 0),
('1040', 16, 0, 0, 0, 0),
('1040', 17, 0, 0, 0, 0),
('1040', 18, 0, 0, 0, 0),
('1040', 19, 0, 0, 0, 0),
('1040', 20, 0, 0, 0, 0),
('1040', 21, 0, 0, 0, 0),
('1040', 22, 0, 0, 0, 0),
('1040', 23, 0, 0, 0, 0),
('1040', 24, 0, 0, 0, 0),
('1050', -13, 0, 0, 0, 0),
('1050', -12, 0, 0, 0, 0),
('1050', -11, 0, 0, 0, 0),
('1050', -10, 0, 0, 0, 0),
('1050', -9, 0, 0, 0, 0),
('1050', -8, 0, 0, 0, 0),
('1050', -7, 0, 0, 0, 0),
('1050', -6, 0, 0, 0, 0),
('1050', -5, 0, 0, 0, 0),
('1050', -4, 0, 0, 0, 0),
('1050', -3, 0, 0, 0, 0),
('1050', -2, 0, 0, 0, 0),
('1050', -1, 0, 0, 0, 0),
('1050', 0, 0, 0, 0, 0),
('1050', 1, 0, 0, 0, 0),
('1050', 2, 0, 0, 0, 0),
('1050', 3, 0, 0, 0, 0),
('1050', 4, 0, 0, 0, 0),
('1050', 5, 0, 0, 0, 0),
('1050', 6, 0, 0, 0, 0),
('1050', 7, 0, 0, 0, 0),
('1050', 8, 0, 0, 0, 0),
('1050', 9, 0, 0, 0, 0),
('1050', 10, 0, 0, 0, 0),
('1050', 11, 0, 0, 0, 0),
('1050', 12, 0, 0, 0, 0),
('1050', 13, 0, 0, 0, 0),
('1050', 14, 0, 0, 0, 0),
('1050', 15, 0, 0, 0, 0),
('1050', 16, 0, 0, 0, 0),
('1050', 17, 0, 0, 0, 0),
('1050', 18, 0, 0, 0, 0),
('1050', 19, 0, 0, 0, 0),
('1050', 20, 0, 0, 0, 0),
('1050', 21, 0, 0, 0, 0),
('1050', 22, 0, 0, 0, 0),
('1050', 23, 0, 0, 0, 0),
('1050', 24, 0, 0, 0, 0),
('1060', -13, 0, 0, 0, 0),
('1060', -12, 0, 0, 0, 0),
('1060', -11, 0, 0, 0, 0),
('1060', -10, 0, 0, 0, 0),
('1060', -9, 0, 0, 0, 0),
('1060', -8, 0, 0, 0, 0),
('1060', -7, 0, 0, 0, 0),
('1060', -6, 0, 0, 0, 0),
('1060', -5, 0, 0, 0, 0),
('1060', -4, 0, 0, 0, 0),
('1060', -3, 0, 0, 0, 0),
('1060', -2, 0, 0, 0, 0),
('1060', -1, 0, 0, 0, 0),
('1060', 0, 0, 0, 0, 0),
('1060', 1, 0, 0, 0, 0),
('1060', 2, 0, 0, 0, 0),
('1060', 3, 0, 0, 0, 0),
('1060', 4, 0, 0, 0, 0),
('1060', 5, 0, 0, 0, 0),
('1060', 6, 0, 0, 0, 0),
('1060', 7, 0, 0, 0, 0),
('1060', 8, 0, 0, 0, 0),
('1060', 9, 0, 0, 0, 0),
('1060', 10, 0, 0, 0, 0),
('1060', 11, 0, 0, 0, 0),
('1060', 12, 0, 0, 0, 0),
('1060', 13, 0, 0, 0, 0),
('1060', 14, 0, 0, 0, 0),
('1060', 15, 0, 0, 0, 0),
('1060', 16, 0, 0, 0, 0),
('1060', 17, 0, 0, 0, 0),
('1060', 18, 0, 0, 0, 0),
('1060', 19, 0, 0, 0, 0),
('1060', 20, 0, 0, 0, 0),
('1060', 21, 0, 0, 0, 0),
('1060', 22, 0, 0, 0, 0),
('1060', 23, 0, 0, 0, 0),
('1060', 24, 0, 0, 0, 0),
('1070', -13, 0, 0, 0, 0),
('1070', -12, 0, 0, 0, 0),
('1070', -11, 0, 0, 0, 0),
('1070', -10, 0, 0, 0, 0),
('1070', -9, 0, 0, 0, 0),
('1070', -8, 0, 0, 0, 0),
('1070', -7, 0, 0, 0, 0),
('1070', -6, 0, 0, 0, 0),
('1070', -5, 0, 0, 0, 0),
('1070', -4, 0, 0, 0, 0),
('1070', -3, 0, 0, 0, 0),
('1070', -2, 0, 0, 0, 0),
('1070', -1, 0, 0, 0, 0),
('1070', 0, 0, 0, 0, 0),
('1070', 1, 0, 0, 0, 0),
('1070', 2, 0, 0, 0, 0),
('1070', 3, 0, 0, 0, 0),
('1070', 4, 0, 0, 0, 0),
('1070', 5, 0, 0, 0, 0),
('1070', 6, 0, 0, 0, 0),
('1070', 7, 0, 0, 0, 0),
('1070', 8, 0, 0, 0, 0),
('1070', 9, 0, 0, 0, 0),
('1070', 10, 0, 0, 0, 0),
('1070', 11, 0, 0, 0, 0),
('1070', 12, 0, 0, 0, 0),
('1070', 13, 0, 0, 0, 0),
('1070', 14, 0, 0, 0, 0),
('1070', 15, 0, 0, 0, 0),
('1070', 16, 0, 0, 0, 0),
('1070', 17, 0, 0, 0, 0),
('1070', 18, 0, 0, 0, 0),
('1070', 19, 0, 0, 0, 0),
('1070', 20, 0, 0, 0, 0),
('1070', 21, 0, 0, 0, 0),
('1070', 22, 0, 0, 0, 0),
('1070', 23, 0, 0, 0, 0),
('1070', 24, 0, 0, 0, 0),
('1080', -13, 0, 0, 0, 0),
('1080', -12, 0, 0, 0, 0),
('1080', -11, 0, 0, 0, 0),
('1080', -10, 0, 0, 0, 0),
('1080', -9, 0, 0, 0, 0),
('1080', -8, 0, 0, 0, 0),
('1080', -7, 0, 0, 0, 0),
('1080', -6, 0, 0, 0, 0),
('1080', -5, 0, 0, 0, 0),
('1080', -4, 0, 0, 0, 0),
('1080', -3, 0, 0, 0, 0),
('1080', -2, 0, 0, 0, 0),
('1080', -1, 0, 0, 0, 0),
('1080', 0, 0, 0, 0, 0),
('1080', 1, 0, 0, 0, 0),
('1080', 2, 0, 0, 0, 0),
('1080', 3, 0, 0, 0, 0),
('1080', 4, 0, 0, 0, 0),
('1080', 5, 0, 0, 0, 0),
('1080', 6, 0, 0, 0, 0),
('1080', 7, 0, 0, 0, 0),
('1080', 8, 0, 0, 0, 0),
('1080', 9, 0, 0, 0, 0),
('1080', 10, 0, 0, 0, 0),
('1080', 11, 0, 0, 0, 0),
('1080', 12, 0, 0, 0, 0),
('1080', 13, 0, 0, 0, 0),
('1080', 14, 0, 0, 0, 0),
('1080', 15, 0, 0, 0, 0),
('1080', 16, 0, 0, 0, 0),
('1080', 17, 0, 0, 0, 0),
('1080', 18, 0, 0, 0, 0),
('1080', 19, 0, 0, 0, 0),
('1080', 20, 0, 0, 0, 0),
('1080', 21, 0, 0, 0, 0),
('1080', 22, 0, 0, 0, 0),
('1080', 23, 0, 0, 0, 0),
('1080', 24, 0, 0, 0, 0),
('1090', -13, 0, 0, 0, 0),
('1090', -12, 0, 0, 0, 0),
('1090', -11, 0, 0, 0, 0),
('1090', -10, 0, 0, 0, 0),
('1090', -9, 0, 0, 0, 0),
('1090', -8, 0, 0, 0, 0),
('1090', -7, 0, 0, 0, 0),
('1090', -6, 0, 0, 0, 0),
('1090', -5, 0, 0, 0, 0),
('1090', -4, 0, 0, 0, 0),
('1090', -3, 0, 0, 0, 0),
('1090', -2, 0, 0, 0, 0),
('1090', -1, 0, 0, 0, 0),
('1090', 0, 0, 0, 0, 0),
('1090', 1, 0, 0, 0, 0),
('1090', 2, 0, 0, 0, 0),
('1090', 3, 0, 0, 0, 0),
('1090', 4, 0, 0, 0, 0),
('1090', 5, 0, 0, 0, 0),
('1090', 6, 0, 0, 0, 0),
('1090', 7, 0, 0, 0, 0),
('1090', 8, 0, 0, 0, 0),
('1090', 9, 0, 0, 0, 0),
('1090', 10, 0, 0, 0, 0),
('1090', 11, 0, 0, 0, 0),
('1090', 12, 0, 0, 0, 0),
('1090', 13, 0, 0, 0, 0),
('1090', 14, 0, 0, 0, 0),
('1090', 15, 0, 0, 0, 0),
('1090', 16, 0, 0, 0, 0),
('1090', 17, 0, 0, 0, 0),
('1090', 18, 0, 0, 0, 0),
('1090', 19, 0, 0, 0, 0),
('1090', 20, 0, 0, 0, 0),
('1090', 21, 0, 0, 0, 0),
('1090', 22, 0, 0, 0, 0),
('1090', 23, 0, 0, 0, 0),
('1090', 24, 0, 0, 0, 0),
('1100', -13, 0, 0, 0, 0),
('1100', -12, 0, 0, 0, 0),
('1100', -11, 0, 0, 0, 0),
('1100', -10, 0, 0, 0, 0),
('1100', -9, 0, 0, 0, 0),
('1100', -8, 0, 0, 0, 0),
('1100', -7, 0, 0, 0, 0),
('1100', -6, 0, 0, 0, 0),
('1100', -5, 0, 0, 0, 0),
('1100', -4, 0, 0, 0, 0),
('1100', -3, 0, 0, 0, 0),
('1100', -2, 0, 0, 0, 0),
('1100', -1, 0, 0, 0, 0),
('1100', 0, 0, 0, 0, 0),
('1100', 1, 0, 0, 0, 0),
('1100', 2, 0, 0, 0, 0),
('1100', 3, 0, 0, 0, 0),
('1100', 4, 0, 0, 0, 0),
('1100', 5, 0, 0, 0, 0),
('1100', 6, 0, 0, 0, 0),
('1100', 7, 0, 0, 0, 0),
('1100', 8, 0, 0, 0, 0),
('1100', 9, 0, 0, 0, 0),
('1100', 10, 0, 0, 0, 0),
('1100', 11, 0, 0, 0, 0),
('1100', 12, 0, 0, 0, 0),
('1100', 13, 0, 0, 0, 0),
('1100', 14, 0, 0, 0, 0),
('1100', 15, 0, 0, 0, 0),
('1100', 16, 0, 0, 0, 0),
('1100', 17, 0, 0, 0, 0),
('1100', 18, 0, 0, 0, 0),
('1100', 19, 0, 0, 0, 0),
('1100', 20, 0, 0, 0, 0),
('1100', 21, 0, 0, 0, 0),
('1100', 22, 0, 0, 0, 0),
('1100', 23, 0, 0, 0, 0),
('1100', 24, 0, 0, 0, 0),
('1150', -13, 0, 0, 0, 0),
('1150', -12, 0, 0, 0, 0),
('1150', -11, 0, 0, 0, 0),
('1150', -10, 0, 0, 0, 0),
('1150', -9, 0, 0, 0, 0),
('1150', -8, 0, 0, 0, 0),
('1150', -7, 0, 0, 0, 0),
('1150', -6, 0, 0, 0, 0),
('1150', -5, 0, 0, 0, 0),
('1150', -4, 0, 0, 0, 0),
('1150', -3, 0, 0, 0, 0),
('1150', -2, 0, 0, 0, 0),
('1150', -1, 0, 0, 0, 0),
('1150', 0, 0, 0, 0, 0),
('1150', 1, 0, 0, 0, 0),
('1150', 2, 0, 0, 0, 0),
('1150', 3, 0, 0, 0, 0),
('1150', 4, 0, 0, 0, 0),
('1150', 5, 0, 0, 0, 0),
('1150', 6, 0, 0, 0, 0),
('1150', 7, 0, 0, 0, 0),
('1150', 8, 0, 0, 0, 0),
('1150', 9, 0, 0, 0, 0),
('1150', 10, 0, 0, 0, 0),
('1150', 11, 0, 0, 0, 0),
('1150', 12, 0, 0, 0, 0),
('1150', 13, 0, 0, 0, 0),
('1150', 14, 0, 0, 0, 0),
('1150', 15, 0, 0, 0, 0),
('1150', 16, 0, 0, 0, 0),
('1150', 17, 0, 0, 0, 0),
('1150', 18, 0, 0, 0, 0),
('1150', 19, 0, 0, 0, 0),
('1150', 20, 0, 0, 0, 0),
('1150', 21, 0, 0, 0, 0),
('1150', 22, 0, 0, 0, 0),
('1150', 23, 0, 0, 0, 0),
('1150', 24, 0, 0, 0, 0),
('1200', -13, 0, 0, 0, 0),
('1200', -12, 0, 0, 0, 0),
('1200', -11, 0, 0, 0, 0),
('1200', -10, 0, 0, 0, 0),
('1200', -9, 0, 0, 0, 0),
('1200', -8, 0, 0, 0, 0),
('1200', -7, 0, 0, 0, 0),
('1200', -6, 0, 0, 0, 0),
('1200', -5, 0, 0, 0, 0),
('1200', -4, 0, 0, 0, 0),
('1200', -3, 0, 0, 0, 0),
('1200', -2, 0, 0, 0, 0),
('1200', -1, 0, 0, 0, 0),
('1200', 0, 0, 0, 0, 0),
('1200', 1, 0, 0, 0, 0),
('1200', 2, 0, 0, 0, 0),
('1200', 3, 0, 0, 0, 0),
('1200', 4, 0, 0, 0, 0),
('1200', 5, 0, 0, 0, 0),
('1200', 6, 0, 0, 0, 0),
('1200', 7, 0, 0, 0, 0),
('1200', 8, 0, 0, 0, 0),
('1200', 9, 0, 0, 0, 0),
('1200', 10, 0, 0, 0, 0),
('1200', 11, 0, 0, 0, 0),
('1200', 12, 0, 0, 0, 0),
('1200', 13, 0, 0, 0, 0),
('1200', 14, 0, 0, 0, 0),
('1200', 15, 0, 0, 0, 0),
('1200', 16, 0, 0, 0, 0),
('1200', 17, 0, 0, 0, 0),
('1200', 18, 0, 0, 0, 0),
('1200', 19, 0, 0, 0, 0),
('1200', 20, 0, 0, 0, 0),
('1200', 21, 0, 0, 0, 0),
('1200', 22, 0, 0, 0, 0),
('1200', 23, 0, 0, 0, 0),
('1200', 24, 0, 0, 0, 0),
('1250', -13, 0, 0, 0, 0),
('1250', -12, 0, 0, 0, 0),
('1250', -11, 0, 0, 0, 0),
('1250', -10, 0, 0, 0, 0),
('1250', -9, 0, 0, 0, 0),
('1250', -8, 0, 0, 0, 0),
('1250', -7, 0, 0, 0, 0),
('1250', -6, 0, 0, 0, 0),
('1250', -5, 0, 0, 0, 0),
('1250', -4, 0, 0, 0, 0),
('1250', -3, 0, 0, 0, 0),
('1250', -2, 0, 0, 0, 0),
('1250', -1, 0, 0, 0, 0),
('1250', 0, 0, 0, 0, 0),
('1250', 1, 0, 0, 0, 0),
('1250', 2, 0, 0, 0, 0),
('1250', 3, 0, 0, 0, 0),
('1250', 4, 0, 0, 0, 0),
('1250', 5, 0, 0, 0, 0),
('1250', 6, 0, 0, 0, 0),
('1250', 7, 0, 0, 0, 0),
('1250', 8, 0, 0, 0, 0),
('1250', 9, 0, 0, 0, 0),
('1250', 10, 0, 0, 0, 0),
('1250', 11, 0, 0, 0, 0),
('1250', 12, 0, 0, 0, 0),
('1250', 13, 0, 0, 0, 0),
('1250', 14, 0, 0, 0, 0),
('1250', 15, 0, 0, 0, 0),
('1250', 16, 0, 0, 0, 0),
('1250', 17, 0, 0, 0, 0),
('1250', 18, 0, 0, 0, 0),
('1250', 19, 0, 0, 0, 0),
('1250', 20, 0, 0, 0, 0),
('1250', 21, 0, 0, 0, 0),
('1250', 22, 0, 0, 0, 0),
('1250', 23, 0, 0, 0, 0),
('1250', 24, 0, 0, 0, 0),
('1300', -13, 0, 0, 0, 0),
('1300', -12, 0, 0, 0, 0),
('1300', -11, 0, 0, 0, 0),
('1300', -10, 0, 0, 0, 0),
('1300', -9, 0, 0, 0, 0),
('1300', -8, 0, 0, 0, 0),
('1300', -7, 0, 0, 0, 0),
('1300', -6, 0, 0, 0, 0),
('1300', -5, 0, 0, 0, 0),
('1300', -4, 0, 0, 0, 0),
('1300', -3, 0, 0, 0, 0),
('1300', -2, 0, 0, 0, 0),
('1300', -1, 0, 0, 0, 0),
('1300', 0, 0, 0, 0, 0),
('1300', 1, 0, 0, 0, 0),
('1300', 2, 0, 0, 0, 0),
('1300', 3, 0, 0, 0, 0),
('1300', 4, 0, 0, 0, 0),
('1300', 5, 0, 0, 0, 0),
('1300', 6, 0, 0, 0, 0),
('1300', 7, 0, 0, 0, 0),
('1300', 8, 0, 0, 0, 0),
('1300', 9, 0, 0, 0, 0),
('1300', 10, 0, 0, 0, 0),
('1300', 11, 0, 0, 0, 0),
('1300', 12, 0, 0, 0, 0),
('1300', 13, 0, 0, 0, 0),
('1300', 14, 0, 0, 0, 0),
('1300', 15, 0, 0, 0, 0),
('1300', 16, 0, 0, 0, 0),
('1300', 17, 0, 0, 0, 0),
('1300', 18, 0, 0, 0, 0),
('1300', 19, 0, 0, 0, 0),
('1300', 20, 0, 0, 0, 0),
('1300', 21, 0, 0, 0, 0),
('1300', 22, 0, 0, 0, 0),
('1300', 23, 0, 0, 0, 0),
('1300', 24, 0, 0, 0, 0),
('1350', -13, 0, 0, 0, 0),
('1350', -12, 0, 0, 0, 0),
('1350', -11, 0, 0, 0, 0),
('1350', -10, 0, 0, 0, 0),
('1350', -9, 0, 0, 0, 0),
('1350', -8, 0, 0, 0, 0),
('1350', -7, 0, 0, 0, 0),
('1350', -6, 0, 0, 0, 0),
('1350', -5, 0, 0, 0, 0),
('1350', -4, 0, 0, 0, 0),
('1350', -3, 0, 0, 0, 0),
('1350', -2, 0, 0, 0, 0),
('1350', -1, 0, 0, 0, 0),
('1350', 0, 0, 0, 0, 0),
('1350', 1, 0, 0, 0, 0),
('1350', 2, 0, 0, 0, 0),
('1350', 3, 0, 0, 0, 0),
('1350', 4, 0, 0, 0, 0),
('1350', 5, 0, 0, 0, 0),
('1350', 6, 0, 0, 0, 0),
('1350', 7, 0, 0, 0, 0),
('1350', 8, 0, 0, 0, 0),
('1350', 9, 0, 0, 0, 0),
('1350', 10, 0, 0, 0, 0),
('1350', 11, 0, 0, 0, 0),
('1350', 12, 0, 0, 0, 0),
('1350', 13, 0, 0, 0, 0),
('1350', 14, 0, 0, 0, 0),
('1350', 15, 0, 0, 0, 0),
('1350', 16, 0, 0, 0, 0),
('1350', 17, 0, 0, 0, 0),
('1350', 18, 0, 0, 0, 0),
('1350', 19, 0, 0, 0, 0),
('1350', 20, 0, 0, 0, 0),
('1350', 21, 0, 0, 0, 0),
('1350', 22, 0, 0, 0, 0),
('1350', 23, 0, 0, 0, 0),
('1350', 24, 0, 0, 0, 0),
('1400', -13, 0, 0, 0, 0),
('1400', -12, 0, 0, 0, 0),
('1400', -11, 0, 0, 0, 0),
('1400', -10, 0, 0, 0, 0),
('1400', -9, 0, 0, 0, 0),
('1400', -8, 0, 0, 0, 0),
('1400', -7, 0, 0, 0, 0),
('1400', -6, 0, 0, 0, 0),
('1400', -5, 0, 0, 0, 0),
('1400', -4, 0, 0, 0, 0),
('1400', -3, 0, 0, 0, 0),
('1400', -2, 0, 0, 0, 0),
('1400', -1, 0, 0, 0, 0),
('1400', 0, 0, 0, 0, 0),
('1400', 1, 0, 0, 0, 0),
('1400', 2, 0, 0, 0, 0),
('1400', 3, 0, 0, 0, 0),
('1400', 4, 0, 0, 0, 0),
('1400', 5, 0, 0, 0, 0),
('1400', 6, 0, 0, 0, 0),
('1400', 7, 0, 0, 0, 0),
('1400', 8, 0, 0, 0, 0),
('1400', 9, 0, 0, 0, 0),
('1400', 10, 0, 0, 0, 0),
('1400', 11, 0, 0, 0, 0),
('1400', 12, 0, 0, 0, 0),
('1400', 13, 0, 0, 0, 0),
('1400', 14, 0, 0, 0, 0),
('1400', 15, 0, 0, 0, 0),
('1400', 16, 0, 0, 0, 0),
('1400', 17, 0, 0, 0, 0),
('1400', 18, 0, 0, 0, 0),
('1400', 19, 0, 0, 0, 0),
('1400', 20, 0, 0, 0, 0),
('1400', 21, 0, 0, 0, 0),
('1400', 22, 0, 0, 0, 0),
('1400', 23, 0, 0, 0, 0),
('1400', 24, 0, 0, 0, 0),
('1420', -13, 0, 0, 0, 0),
('1420', -12, 0, 0, 0, 0),
('1420', -11, 0, 0, 0, 0),
('1420', -10, 0, 0, 0, 0),
('1420', -9, 0, 0, 0, 0),
('1420', -8, 0, 0, 0, 0),
('1420', -7, 0, 0, 0, 0),
('1420', -6, 0, 0, 0, 0),
('1420', -5, 0, 0, 0, 0),
('1420', -4, 0, 0, 0, 0),
('1420', -3, 0, 0, 0, 0),
('1420', -2, 0, 0, 0, 0),
('1420', -1, 0, 0, 0, 0),
('1420', 0, 0, 0, 0, 0),
('1420', 1, 0, 0, 0, 0),
('1420', 2, 0, 0, 0, 0),
('1420', 3, 0, 0, 0, 0),
('1420', 4, 0, 0, 0, 0),
('1420', 5, 0, 0, 0, 0),
('1420', 6, 0, 0, 0, 0),
('1420', 7, 0, 0, 0, 0),
('1420', 8, 0, 0, 0, 0),
('1420', 9, 0, 0, 0, 0),
('1420', 10, 0, 0, 0, 0),
('1420', 11, 0, 0, 0, 0),
('1420', 12, 0, 0, 0, 0),
('1420', 13, 0, 0, 0, 0),
('1420', 14, 0, 0, 0, 0),
('1420', 15, 0, 0, 0, 0),
('1420', 16, 0, 0, 0, 0),
('1420', 17, 0, 0, 0, 0),
('1420', 18, 0, 0, 0, 0),
('1420', 19, 0, 0, 0, 0),
('1420', 20, 0, 0, 0, 0),
('1420', 21, 0, 0, 0, 0),
('1420', 22, 0, 0, 0, 0),
('1420', 23, 0, 0, 0, 0),
('1420', 24, 0, 0, 0, 0),
('1440', -13, 0, 0, 0, 0),
('1440', -12, 0, 0, 0, 0),
('1440', -11, 0, 0, 0, 0),
('1440', -10, 0, 0, 0, 0),
('1440', -9, 0, 0, 0, 0),
('1440', -8, 0, 0, 0, 0),
('1440', -7, 0, 0, 0, 0),
('1440', -6, 0, 0, 0, 0),
('1440', -5, 0, 0, 0, 0),
('1440', -4, 0, 0, 0, 0),
('1440', -3, 0, 0, 0, 0),
('1440', -2, 0, 0, 0, 0),
('1440', -1, 0, 0, 0, 0),
('1440', 0, 0, 0, 0, 0),
('1440', 1, 0, 0, 0, 0),
('1440', 2, 0, 0, 0, 0),
('1440', 3, 0, 0, 0, 0),
('1440', 4, 0, 0, 0, 0),
('1440', 5, 0, 0, 0, 0),
('1440', 6, 0, 0, 0, 0),
('1440', 7, 0, 0, 0, 0),
('1440', 8, 0, 0, 0, 0),
('1440', 9, 0, 0, 0, 0),
('1440', 10, 0, 0, 0, 0),
('1440', 11, 0, 0, 0, 0),
('1440', 12, 0, 0, 0, 0),
('1440', 13, 0, 0, 0, 0),
('1440', 14, 0, 0, 0, 0),
('1440', 15, 0, 0, 0, 0),
('1440', 16, 0, 0, 0, 0),
('1440', 17, 0, 0, 0, 0),
('1440', 18, 0, 0, 0, 0),
('1440', 19, 0, 0, 0, 0),
('1440', 20, 0, 0, 0, 0),
('1440', 21, 0, 0, 0, 0),
('1440', 22, 0, 0, 0, 0),
('1440', 23, 0, 0, 0, 0),
('1440', 24, 0, 0, 0, 0),
('1460', -13, 0, 0, 0, 0),
('1460', -12, 0, 0, 0, 0),
('1460', -11, 0, 0, 0, 0),
('1460', -10, 0, 0, 0, 0),
('1460', -9, 0, 0, 0, 0),
('1460', -8, 0, 0, 0, 0),
('1460', -7, 0, 0, 0, 0),
('1460', -6, 0, 0, 0, 0),
('1460', -5, 0, 0, 0, 0),
('1460', -4, 0, 0, 0, 0),
('1460', -3, 0, 0, 0, 0),
('1460', -2, 0, 0, 0, 0),
('1460', -1, 0, 0, 0, 0),
('1460', 0, 0, 0, 0, 0),
('1460', 1, 0, 0, 0, 0),
('1460', 2, 0, 0, 0, 0),
('1460', 3, 0, 0, 0, 0),
('1460', 4, 0, 0, 0, 0),
('1460', 5, 0, 0, 0, 0),
('1460', 6, 0, 0, 0, 0),
('1460', 7, 0, 0, 0, 0),
('1460', 8, 0, 0, 0, 0),
('1460', 9, 0, 0, 0, 0),
('1460', 10, 0, 0, 0, 0),
('1460', 11, 0, 0, 0, 0),
('1460', 12, 0, 0, 0, 0),
('1460', 13, 0, 0, 0, 0),
('1460', 14, 0, 0, 0, 0),
('1460', 15, 0, 0, 0, 0),
('1460', 16, 0, 0, 0, 0),
('1460', 17, 0, 0, 0, 0),
('1460', 18, 0, 0, 0, 0),
('1460', 19, 0, 0, 0, 0),
('1460', 20, 0, 0, 0, 0),
('1460', 21, 0, 0, 0, 0),
('1460', 22, 0, 0, 0, 0),
('1460', 23, 0, 0, 0, 0),
('1460', 24, 0, 0, 0, 0),
('1500', -13, 0, 0, 0, 0),
('1500', -12, 0, 0, 0, 0),
('1500', -11, 0, 0, 0, 0),
('1500', -10, 0, 0, 0, 0),
('1500', -9, 0, 0, 0, 0),
('1500', -8, 0, 0, 0, 0),
('1500', -7, 0, 0, 0, 0),
('1500', -6, 0, 0, 0, 0),
('1500', -5, 0, 0, 0, 0),
('1500', -4, 0, 0, 0, 0),
('1500', -3, 0, 0, 0, 0),
('1500', -2, 0, 0, 0, 0),
('1500', -1, 0, 0, 0, 0),
('1500', 0, 0, 0, 0, 0),
('1500', 1, 0, 0, 0, 0),
('1500', 2, 0, 0, 0, 0),
('1500', 3, 0, 0, 0, 0),
('1500', 4, 0, 0, 0, 0),
('1500', 5, 0, 0, 0, 0),
('1500', 6, 0, 0, 0, 0),
('1500', 7, 0, 0, 0, 0),
('1500', 8, 0, 0, 0, 0),
('1500', 9, 0, 0, 0, 0),
('1500', 10, 0, 0, 0, 0),
('1500', 11, 0, 0, 0, 0),
('1500', 12, 0, 0, 0, 0),
('1500', 13, 0, 0, 0, 0),
('1500', 14, 0, 0, 0, 0),
('1500', 15, 0, 0, 0, 0),
('1500', 16, 0, 0, 0, 0),
('1500', 17, 0, 0, 0, 0),
('1500', 18, 0, 0, 0, 0),
('1500', 19, 0, 0, 0, 0),
('1500', 20, 0, 0, 0, 0),
('1500', 21, 0, 0, 0, 0),
('1500', 22, 0, 0, 0, 0),
('1500', 23, 0, 0, 0, 0),
('1500', 24, 0, 0, 0, 0),
('1550', -13, 0, 0, 0, 0),
('1550', -12, 0, 0, 0, 0),
('1550', -11, 0, 0, 0, 0),
('1550', -10, 0, 0, 0, 0),
('1550', -9, 0, 0, 0, 0),
('1550', -8, 0, 0, 0, 0),
('1550', -7, 0, 0, 0, 0),
('1550', -6, 0, 0, 0, 0),
('1550', -5, 0, 0, 0, 0),
('1550', -4, 0, 0, 0, 0),
('1550', -3, 0, 0, 0, 0),
('1550', -2, 0, 0, 0, 0),
('1550', -1, 0, 0, 0, 0),
('1550', 0, 0, 0, 0, 0),
('1550', 1, 0, 0, 0, 0),
('1550', 2, 0, 0, 0, 0),
('1550', 3, 0, 0, 0, 0),
('1550', 4, 0, 0, 0, 0),
('1550', 5, 0, 0, 0, 0),
('1550', 6, 0, 0, 0, 0),
('1550', 7, 0, 0, 0, 0),
('1550', 8, 0, 0, 0, 0),
('1550', 9, 0, 0, 0, 0),
('1550', 10, 0, 0, 0, 0),
('1550', 11, 0, 0, 0, 0),
('1550', 12, 0, 0, 0, 0),
('1550', 13, 0, 0, 0, 0),
('1550', 14, 0, 0, 0, 0),
('1550', 15, 0, 0, 0, 0),
('1550', 16, 0, 0, 0, 0),
('1550', 17, 0, 0, 0, 0),
('1550', 18, 0, 0, 0, 0),
('1550', 19, 0, 0, 0, 0),
('1550', 20, 0, 0, 0, 0),
('1550', 21, 0, 0, 0, 0),
('1550', 22, 0, 0, 0, 0),
('1550', 23, 0, 0, 0, 0),
('1550', 24, 0, 0, 0, 0),
('1600', -13, 0, 0, 0, 0),
('1600', -12, 0, 0, 0, 0),
('1600', -11, 0, 0, 0, 0),
('1600', -10, 0, 0, 0, 0),
('1600', -9, 0, 0, 0, 0),
('1600', -8, 0, 0, 0, 0),
('1600', -7, 0, 0, 0, 0),
('1600', -6, 0, 0, 0, 0),
('1600', -5, 0, 0, 0, 0),
('1600', -4, 0, 0, 0, 0),
('1600', -3, 0, 0, 0, 0),
('1600', -2, 0, 0, 0, 0),
('1600', -1, 0, 0, 0, 0),
('1600', 0, 0, 0, 0, 0),
('1600', 1, 0, 0, 0, 0),
('1600', 2, 0, 0, 0, 0),
('1600', 3, 0, 0, 0, 0),
('1600', 4, 0, 0, 0, 0),
('1600', 5, 0, 0, 0, 0),
('1600', 6, 0, 0, 0, 0),
('1600', 7, 0, 0, 0, 0),
('1600', 8, 0, 0, 0, 0),
('1600', 9, 0, 0, 0, 0),
('1600', 10, 0, 0, 0, 0),
('1600', 11, 0, 0, 0, 0),
('1600', 12, 0, 0, 0, 0),
('1600', 13, 0, 0, 0, 0),
('1600', 14, 0, 0, 0, 0),
('1600', 15, 0, 0, 0, 0),
('1600', 16, 0, 0, 0, 0),
('1600', 17, 0, 0, 0, 0),
('1600', 18, 0, 0, 0, 0),
('1600', 19, 0, 0, 0, 0),
('1600', 20, 0, 0, 0, 0),
('1600', 21, 0, 0, 0, 0),
('1600', 22, 0, 0, 0, 0),
('1600', 23, 0, 0, 0, 0),
('1600', 24, 0, 0, 0, 0),
('1620', -13, 0, 0, 0, 0),
('1620', -12, 0, 0, 0, 0),
('1620', -11, 0, 0, 0, 0),
('1620', -10, 0, 0, 0, 0),
('1620', -9, 0, 0, 0, 0),
('1620', -8, 0, 0, 0, 0),
('1620', -7, 0, 0, 0, 0),
('1620', -6, 0, 0, 0, 0),
('1620', -5, 0, 0, 0, 0),
('1620', -4, 0, 0, 0, 0),
('1620', -3, 0, 0, 0, 0),
('1620', -2, 0, 0, 0, 0),
('1620', -1, 0, 0, 0, 0),
('1620', 0, 0, 0, 0, 0),
('1620', 1, 0, 0, 0, 0),
('1620', 2, 0, 0, 0, 0),
('1620', 3, 0, 0, 0, 0),
('1620', 4, 0, 0, 0, 0),
('1620', 5, 0, 0, 0, 0),
('1620', 6, 0, 0, 0, 0),
('1620', 7, 0, 0, 0, 0),
('1620', 8, 0, 0, 0, 0),
('1620', 9, 0, 0, 0, 0),
('1620', 10, 0, 0, 0, 0),
('1620', 11, 0, 0, 0, 0),
('1620', 12, 0, 0, 0, 0),
('1620', 13, 0, 0, 0, 0),
('1620', 14, 0, 0, 0, 0),
('1620', 15, 0, 0, 0, 0),
('1620', 16, 0, 0, 0, 0),
('1620', 17, 0, 0, 0, 0),
('1620', 18, 0, 0, 0, 0),
('1620', 19, 0, 0, 0, 0),
('1620', 20, 0, 0, 0, 0),
('1620', 21, 0, 0, 0, 0),
('1620', 22, 0, 0, 0, 0),
('1620', 23, 0, 0, 0, 0),
('1620', 24, 0, 0, 0, 0),
('1650', -13, 0, 0, 0, 0),
('1650', -12, 0, 0, 0, 0),
('1650', -11, 0, 0, 0, 0),
('1650', -10, 0, 0, 0, 0),
('1650', -9, 0, 0, 0, 0),
('1650', -8, 0, 0, 0, 0),
('1650', -7, 0, 0, 0, 0),
('1650', -6, 0, 0, 0, 0),
('1650', -5, 0, 0, 0, 0),
('1650', -4, 0, 0, 0, 0),
('1650', -3, 0, 0, 0, 0),
('1650', -2, 0, 0, 0, 0),
('1650', -1, 0, 0, 0, 0),
('1650', 0, 0, 0, 0, 0),
('1650', 1, 0, 0, 0, 0),
('1650', 2, 0, 0, 0, 0),
('1650', 3, 0, 0, 0, 0),
('1650', 4, 0, 0, 0, 0),
('1650', 5, 0, 0, 0, 0),
('1650', 6, 0, 0, 0, 0),
('1650', 7, 0, 0, 0, 0),
('1650', 8, 0, 0, 0, 0),
('1650', 9, 0, 0, 0, 0),
('1650', 10, 0, 0, 0, 0),
('1650', 11, 0, 0, 0, 0),
('1650', 12, 0, 0, 0, 0),
('1650', 13, 0, 0, 0, 0),
('1650', 14, 0, 0, 0, 0),
('1650', 15, 0, 0, 0, 0),
('1650', 16, 0, 0, 0, 0),
('1650', 17, 0, 0, 0, 0),
('1650', 18, 0, 0, 0, 0),
('1650', 19, 0, 0, 0, 0),
('1650', 20, 0, 0, 0, 0),
('1650', 21, 0, 0, 0, 0),
('1650', 22, 0, 0, 0, 0),
('1650', 23, 0, 0, 0, 0),
('1650', 24, 0, 0, 0, 0),
('1670', -13, 0, 0, 0, 0),
('1670', -12, 0, 0, 0, 0),
('1670', -11, 0, 0, 0, 0),
('1670', -10, 0, 0, 0, 0),
('1670', -9, 0, 0, 0, 0),
('1670', -8, 0, 0, 0, 0),
('1670', -7, 0, 0, 0, 0),
('1670', -6, 0, 0, 0, 0),
('1670', -5, 0, 0, 0, 0),
('1670', -4, 0, 0, 0, 0),
('1670', -3, 0, 0, 0, 0),
('1670', -2, 0, 0, 0, 0),
('1670', -1, 0, 0, 0, 0),
('1670', 0, 0, 0, 0, 0),
('1670', 1, 0, 0, 0, 0),
('1670', 2, 0, 0, 0, 0),
('1670', 3, 0, 0, 0, 0),
('1670', 4, 0, 0, 0, 0),
('1670', 5, 0, 0, 0, 0),
('1670', 6, 0, 0, 0, 0),
('1670', 7, 0, 0, 0, 0),
('1670', 8, 0, 0, 0, 0),
('1670', 9, 0, 0, 0, 0),
('1670', 10, 0, 0, 0, 0),
('1670', 11, 0, 0, 0, 0),
('1670', 12, 0, 0, 0, 0),
('1670', 13, 0, 0, 0, 0),
('1670', 14, 0, 0, 0, 0),
('1670', 15, 0, 0, 0, 0),
('1670', 16, 0, 0, 0, 0),
('1670', 17, 0, 0, 0, 0),
('1670', 18, 0, 0, 0, 0),
('1670', 19, 0, 0, 0, 0),
('1670', 20, 0, 0, 0, 0),
('1670', 21, 0, 0, 0, 0),
('1670', 22, 0, 0, 0, 0),
('1670', 23, 0, 0, 0, 0),
('1670', 24, 0, 0, 0, 0),
('1700', -13, 0, 0, 0, 0),
('1700', -12, 0, 0, 0, 0),
('1700', -11, 0, 0, 0, 0),
('1700', -10, 0, 0, 0, 0),
('1700', -9, 0, 0, 0, 0),
('1700', -8, 0, 0, 0, 0),
('1700', -7, 0, 0, 0, 0),
('1700', -6, 0, 0, 0, 0),
('1700', -5, 0, 0, 0, 0),
('1700', -4, 0, 0, 0, 0),
('1700', -3, 0, 0, 0, 0),
('1700', -2, 0, 0, 0, 0),
('1700', -1, 0, 0, 0, 0),
('1700', 0, 0, 0, 0, 0),
('1700', 1, 0, 0, 0, 0),
('1700', 2, 0, 0, 0, 0),
('1700', 3, 0, 0, 0, 0),
('1700', 4, 0, 0, 0, 0),
('1700', 5, 0, 0, 0, 0),
('1700', 6, 0, 0, 0, 0),
('1700', 7, 0, 0, 0, 0),
('1700', 8, 0, 0, 0, 0),
('1700', 9, 0, 0, 0, 0),
('1700', 10, 0, 0, 0, 0),
('1700', 11, 0, 0, 0, 0),
('1700', 12, 0, 0, 0, 0),
('1700', 13, 0, 0, 0, 0),
('1700', 14, 0, 0, 0, 0),
('1700', 15, 0, 0, 0, 0),
('1700', 16, 0, 0, 0, 0),
('1700', 17, 0, 0, 0, 0),
('1700', 18, 0, 0, 0, 0),
('1700', 19, 0, 0, 0, 0),
('1700', 20, 0, 0, 0, 0),
('1700', 21, 0, 0, 0, 0),
('1700', 22, 0, 0, 0, 0),
('1700', 23, 0, 0, 0, 0),
('1700', 24, 0, 0, 0, 0),
('1710', -13, 0, 0, 0, 0),
('1710', -12, 0, 0, 0, 0),
('1710', -11, 0, 0, 0, 0),
('1710', -10, 0, 0, 0, 0),
('1710', -9, 0, 0, 0, 0),
('1710', -8, 0, 0, 0, 0),
('1710', -7, 0, 0, 0, 0),
('1710', -6, 0, 0, 0, 0),
('1710', -5, 0, 0, 0, 0),
('1710', -4, 0, 0, 0, 0),
('1710', -3, 0, 0, 0, 0),
('1710', -2, 0, 0, 0, 0),
('1710', -1, 0, 0, 0, 0),
('1710', 0, 0, 0, 0, 0),
('1710', 1, 0, 0, 0, 0),
('1710', 2, 0, 0, 0, 0),
('1710', 3, 0, 0, 0, 0),
('1710', 4, 0, 0, 0, 0),
('1710', 5, 0, 0, 0, 0),
('1710', 6, 0, 0, 0, 0),
('1710', 7, 0, 0, 0, 0),
('1710', 8, 0, 0, 0, 0),
('1710', 9, 0, 0, 0, 0),
('1710', 10, 0, 0, 0, 0),
('1710', 11, 0, 0, 0, 0),
('1710', 12, 0, 0, 0, 0),
('1710', 13, 0, 0, 0, 0),
('1710', 14, 0, 0, 0, 0),
('1710', 15, 0, 0, 0, 0),
('1710', 16, 0, 0, 0, 0),
('1710', 17, 0, 0, 0, 0),
('1710', 18, 0, 0, 0, 0),
('1710', 19, 0, 0, 0, 0),
('1710', 20, 0, 0, 0, 0),
('1710', 21, 0, 0, 0, 0),
('1710', 22, 0, 0, 0, 0),
('1710', 23, 0, 0, 0, 0),
('1710', 24, 0, 0, 0, 0),
('1720', -13, 0, 0, 0, 0),
('1720', -12, 0, 0, 0, 0),
('1720', -11, 0, 0, 0, 0),
('1720', -10, 0, 0, 0, 0),
('1720', -9, 0, 0, 0, 0),
('1720', -8, 0, 0, 0, 0),
('1720', -7, 0, 0, 0, 0),
('1720', -6, 0, 0, 0, 0),
('1720', -5, 0, 0, 0, 0),
('1720', -4, 0, 0, 0, 0),
('1720', -3, 0, 0, 0, 0),
('1720', -2, 0, 0, 0, 0),
('1720', -1, 0, 0, 0, 0),
('1720', 0, 0, 0, 0, 0),
('1720', 1, 0, 0, 0, 0),
('1720', 2, 0, 0, 0, 0),
('1720', 3, 0, 0, 0, 0),
('1720', 4, 0, 0, 0, 0),
('1720', 5, 0, 0, 0, 0),
('1720', 6, 0, 0, 0, 0),
('1720', 7, 0, 0, 0, 0),
('1720', 8, 0, 0, 0, 0),
('1720', 9, 0, 0, 0, 0),
('1720', 10, 0, 0, 0, 0),
('1720', 11, 0, 0, 0, 0),
('1720', 12, 0, 0, 0, 0),
('1720', 13, 0, 0, 0, 0),
('1720', 14, 0, 0, 0, 0),
('1720', 15, 0, 0, 0, 0),
('1720', 16, 0, 0, 0, 0),
('1720', 17, 0, 0, 0, 0),
('1720', 18, 0, 0, 0, 0),
('1720', 19, 0, 0, 0, 0),
('1720', 20, 0, 0, 0, 0),
('1720', 21, 0, 0, 0, 0),
('1720', 22, 0, 0, 0, 0),
('1720', 23, 0, 0, 0, 0),
('1720', 24, 0, 0, 0, 0),
('1730', -13, 0, 0, 0, 0),
('1730', -12, 0, 0, 0, 0),
('1730', -11, 0, 0, 0, 0),
('1730', -10, 0, 0, 0, 0),
('1730', -9, 0, 0, 0, 0),
('1730', -8, 0, 0, 0, 0),
('1730', -7, 0, 0, 0, 0),
('1730', -6, 0, 0, 0, 0),
('1730', -5, 0, 0, 0, 0),
('1730', -4, 0, 0, 0, 0),
('1730', -3, 0, 0, 0, 0),
('1730', -2, 0, 0, 0, 0),
('1730', -1, 0, 0, 0, 0),
('1730', 0, 0, 0, 0, 0),
('1730', 1, 0, 0, 0, 0),
('1730', 2, 0, 0, 0, 0),
('1730', 3, 0, 0, 0, 0),
('1730', 4, 0, 0, 0, 0),
('1730', 5, 0, 0, 0, 0),
('1730', 6, 0, 0, 0, 0),
('1730', 7, 0, 0, 0, 0),
('1730', 8, 0, 0, 0, 0),
('1730', 9, 0, 0, 0, 0),
('1730', 10, 0, 0, 0, 0),
('1730', 11, 0, 0, 0, 0),
('1730', 12, 0, 0, 0, 0),
('1730', 13, 0, 0, 0, 0),
('1730', 14, 0, 0, 0, 0),
('1730', 15, 0, 0, 0, 0),
('1730', 16, 0, 0, 0, 0),
('1730', 17, 0, 0, 0, 0),
('1730', 18, 0, 0, 0, 0),
('1730', 19, 0, 0, 0, 0),
('1730', 20, 0, 0, 0, 0),
('1730', 21, 0, 0, 0, 0),
('1730', 22, 0, 0, 0, 0),
('1730', 23, 0, 0, 0, 0),
('1730', 24, 0, 0, 0, 0),
('1740', -13, 0, 0, 0, 0),
('1740', -12, 0, 0, 0, 0),
('1740', -11, 0, 0, 0, 0),
('1740', -10, 0, 0, 0, 0),
('1740', -9, 0, 0, 0, 0),
('1740', -8, 0, 0, 0, 0),
('1740', -7, 0, 0, 0, 0),
('1740', -6, 0, 0, 0, 0),
('1740', -5, 0, 0, 0, 0),
('1740', -4, 0, 0, 0, 0),
('1740', -3, 0, 0, 0, 0),
('1740', -2, 0, 0, 0, 0),
('1740', -1, 0, 0, 0, 0),
('1740', 0, 0, 0, 0, 0),
('1740', 1, 0, 0, 0, 0),
('1740', 2, 0, 0, 0, 0),
('1740', 3, 0, 0, 0, 0),
('1740', 4, 0, 0, 0, 0),
('1740', 5, 0, 0, 0, 0),
('1740', 6, 0, 0, 0, 0),
('1740', 7, 0, 0, 0, 0),
('1740', 8, 0, 0, 0, 0),
('1740', 9, 0, 0, 0, 0),
('1740', 10, 0, 0, 0, 0),
('1740', 11, 0, 0, 0, 0),
('1740', 12, 0, 0, 0, 0),
('1740', 13, 0, 0, 0, 0),
('1740', 14, 0, 0, 0, 0),
('1740', 15, 0, 0, 0, 0),
('1740', 16, 0, 0, 0, 0),
('1740', 17, 0, 0, 0, 0),
('1740', 18, 0, 0, 0, 0),
('1740', 19, 0, 0, 0, 0),
('1740', 20, 0, 0, 0, 0),
('1740', 21, 0, 0, 0, 0),
('1740', 22, 0, 0, 0, 0),
('1740', 23, 0, 0, 0, 0),
('1740', 24, 0, 0, 0, 0),
('1750', -13, 0, 0, 0, 0),
('1750', -12, 0, 0, 0, 0),
('1750', -11, 0, 0, 0, 0),
('1750', -10, 0, 0, 0, 0),
('1750', -9, 0, 0, 0, 0),
('1750', -8, 0, 0, 0, 0),
('1750', -7, 0, 0, 0, 0),
('1750', -6, 0, 0, 0, 0),
('1750', -5, 0, 0, 0, 0),
('1750', -4, 0, 0, 0, 0),
('1750', -3, 0, 0, 0, 0),
('1750', -2, 0, 0, 0, 0),
('1750', -1, 0, 0, 0, 0),
('1750', 0, 0, 0, 0, 0),
('1750', 1, 0, 0, 0, 0),
('1750', 2, 0, 0, 0, 0),
('1750', 3, 0, 0, 0, 0),
('1750', 4, 0, 0, 0, 0),
('1750', 5, 0, 0, 0, 0),
('1750', 6, 0, 0, 0, 0),
('1750', 7, 0, 0, 0, 0),
('1750', 8, 0, 0, 0, 0),
('1750', 9, 0, 0, 0, 0),
('1750', 10, 0, 0, 0, 0),
('1750', 11, 0, 0, 0, 0),
('1750', 12, 0, 0, 0, 0),
('1750', 13, 0, 0, 0, 0),
('1750', 14, 0, 0, 0, 0),
('1750', 15, 0, 0, 0, 0),
('1750', 16, 0, 0, 0, 0),
('1750', 17, 0, 0, 0, 0),
('1750', 18, 0, 0, 0, 0),
('1750', 19, 0, 0, 0, 0),
('1750', 20, 0, 0, 0, 0),
('1750', 21, 0, 0, 0, 0),
('1750', 22, 0, 0, 0, 0),
('1750', 23, 0, 0, 0, 0),
('1750', 24, 0, 0, 0, 0),
('1760', -13, 0, 0, 0, 0),
('1760', -12, 0, 0, 0, 0),
('1760', -11, 0, 0, 0, 0),
('1760', -10, 0, 0, 0, 0),
('1760', -9, 0, 0, 0, 0),
('1760', -8, 0, 0, 0, 0),
('1760', -7, 0, 0, 0, 0),
('1760', -6, 0, 0, 0, 0),
('1760', -5, 0, 0, 0, 0),
('1760', -4, 0, 0, 0, 0),
('1760', -3, 0, 0, 0, 0),
('1760', -2, 0, 0, 0, 0),
('1760', -1, 0, 0, 0, 0),
('1760', 0, 0, 0, 0, 0),
('1760', 1, 0, 0, 0, 0),
('1760', 2, 0, 0, 0, 0),
('1760', 3, 0, 0, 0, 0),
('1760', 4, 0, 0, 0, 0),
('1760', 5, 0, 0, 0, 0),
('1760', 6, 0, 0, 0, 0),
('1760', 7, 0, 0, 0, 0),
('1760', 8, 0, 0, 0, 0),
('1760', 9, 0, 0, 0, 0),
('1760', 10, 0, 0, 0, 0),
('1760', 11, 0, 0, 0, 0),
('1760', 12, 0, 0, 0, 0),
('1760', 13, 0, 0, 0, 0),
('1760', 14, 0, 0, 0, 0),
('1760', 15, 0, 0, 0, 0),
('1760', 16, 0, 0, 0, 0),
('1760', 17, 0, 0, 0, 0),
('1760', 18, 0, 0, 0, 0),
('1760', 19, 0, 0, 0, 0),
('1760', 20, 0, 0, 0, 0),
('1760', 21, 0, 0, 0, 0),
('1760', 22, 0, 0, 0, 0),
('1760', 23, 0, 0, 0, 0),
('1760', 24, 0, 0, 0, 0),
('1770', -13, 0, 0, 0, 0),
('1770', -12, 0, 0, 0, 0),
('1770', -11, 0, 0, 0, 0),
('1770', -10, 0, 0, 0, 0),
('1770', -9, 0, 0, 0, 0),
('1770', -8, 0, 0, 0, 0),
('1770', -7, 0, 0, 0, 0),
('1770', -6, 0, 0, 0, 0),
('1770', -5, 0, 0, 0, 0),
('1770', -4, 0, 0, 0, 0),
('1770', -3, 0, 0, 0, 0),
('1770', -2, 0, 0, 0, 0),
('1770', -1, 0, 0, 0, 0),
('1770', 0, 0, 0, 0, 0),
('1770', 1, 0, 0, 0, 0),
('1770', 2, 0, 0, 0, 0),
('1770', 3, 0, 0, 0, 0),
('1770', 4, 0, 0, 0, 0),
('1770', 5, 0, 0, 0, 0),
('1770', 6, 0, 0, 0, 0),
('1770', 7, 0, 0, 0, 0),
('1770', 8, 0, 0, 0, 0),
('1770', 9, 0, 0, 0, 0),
('1770', 10, 0, 0, 0, 0),
('1770', 11, 0, 0, 0, 0),
('1770', 12, 0, 0, 0, 0),
('1770', 13, 0, 0, 0, 0),
('1770', 14, 0, 0, 0, 0),
('1770', 15, 0, 0, 0, 0),
('1770', 16, 0, 0, 0, 0),
('1770', 17, 0, 0, 0, 0),
('1770', 18, 0, 0, 0, 0),
('1770', 19, 0, 0, 0, 0),
('1770', 20, 0, 0, 0, 0),
('1770', 21, 0, 0, 0, 0),
('1770', 22, 0, 0, 0, 0),
('1770', 23, 0, 0, 0, 0),
('1770', 24, 0, 0, 0, 0),
('1780', -13, 0, 0, 0, 0),
('1780', -12, 0, 0, 0, 0),
('1780', -11, 0, 0, 0, 0),
('1780', -10, 0, 0, 0, 0),
('1780', -9, 0, 0, 0, 0),
('1780', -8, 0, 0, 0, 0),
('1780', -7, 0, 0, 0, 0),
('1780', -6, 0, 0, 0, 0),
('1780', -5, 0, 0, 0, 0),
('1780', -4, 0, 0, 0, 0),
('1780', -3, 0, 0, 0, 0),
('1780', -2, 0, 0, 0, 0),
('1780', -1, 0, 0, 0, 0),
('1780', 0, 0, 0, 0, 0),
('1780', 1, 0, 0, 0, 0),
('1780', 2, 0, 0, 0, 0),
('1780', 3, 0, 0, 0, 0),
('1780', 4, 0, 0, 0, 0),
('1780', 5, 0, 0, 0, 0),
('1780', 6, 0, 0, 0, 0),
('1780', 7, 0, 0, 0, 0),
('1780', 8, 0, 0, 0, 0),
('1780', 9, 0, 0, 0, 0),
('1780', 10, 0, 0, 0, 0),
('1780', 11, 0, 0, 0, 0),
('1780', 12, 0, 0, 0, 0),
('1780', 13, 0, 0, 0, 0),
('1780', 14, 0, 0, 0, 0),
('1780', 15, 0, 0, 0, 0),
('1780', 16, 0, 0, 0, 0),
('1780', 17, 0, 0, 0, 0),
('1780', 18, 0, 0, 0, 0),
('1780', 19, 0, 0, 0, 0),
('1780', 20, 0, 0, 0, 0),
('1780', 21, 0, 0, 0, 0),
('1780', 22, 0, 0, 0, 0),
('1780', 23, 0, 0, 0, 0),
('1780', 24, 0, 0, 0, 0),
('1790', -13, 0, 0, 0, 0),
('1790', -12, 0, 0, 0, 0),
('1790', -11, 0, 0, 0, 0),
('1790', -10, 0, 0, 0, 0),
('1790', -9, 0, 0, 0, 0),
('1790', -8, 0, 0, 0, 0),
('1790', -7, 0, 0, 0, 0),
('1790', -6, 0, 0, 0, 0),
('1790', -5, 0, 0, 0, 0),
('1790', -4, 0, 0, 0, 0),
('1790', -3, 0, 0, 0, 0),
('1790', -2, 0, 0, 0, 0),
('1790', -1, 0, 0, 0, 0),
('1790', 0, 0, 0, 0, 0),
('1790', 1, 0, 0, 0, 0),
('1790', 2, 0, 0, 0, 0),
('1790', 3, 0, 0, 0, 0),
('1790', 4, 0, 0, 0, 0),
('1790', 5, 0, 0, 0, 0),
('1790', 6, 0, 0, 0, 0),
('1790', 7, 0, 0, 0, 0),
('1790', 8, 0, 0, 0, 0),
('1790', 9, 0, 0, 0, 0),
('1790', 10, 0, 0, 0, 0),
('1790', 11, 0, 0, 0, 0),
('1790', 12, 0, 0, 0, 0),
('1790', 13, 0, 0, 0, 0),
('1790', 14, 0, 0, 0, 0),
('1790', 15, 0, 0, 0, 0),
('1790', 16, 0, 0, 0, 0),
('1790', 17, 0, 0, 0, 0),
('1790', 18, 0, 0, 0, 0),
('1790', 19, 0, 0, 0, 0),
('1790', 20, 0, 0, 0, 0),
('1790', 21, 0, 0, 0, 0),
('1790', 22, 0, 0, 0, 0),
('1790', 23, 0, 0, 0, 0),
('1790', 24, 0, 0, 0, 0),
('1800', -13, 0, 0, 0, 0),
('1800', -12, 0, 0, 0, 0),
('1800', -11, 0, 0, 0, 0),
('1800', -10, 0, 0, 0, 0),
('1800', -9, 0, 0, 0, 0),
('1800', -8, 0, 0, 0, 0),
('1800', -7, 0, 0, 0, 0),
('1800', -6, 0, 0, 0, 0),
('1800', -5, 0, 0, 0, 0),
('1800', -4, 0, 0, 0, 0),
('1800', -3, 0, 0, 0, 0),
('1800', -2, 0, 0, 0, 0),
('1800', -1, 0, 0, 0, 0),
('1800', 0, 0, 0, 0, 0),
('1800', 1, 0, 0, 0, 0),
('1800', 2, 0, 0, 0, 0),
('1800', 3, 0, 0, 0, 0),
('1800', 4, 0, 0, 0, 0),
('1800', 5, 0, 0, 0, 0),
('1800', 6, 0, 0, 0, 0),
('1800', 7, 0, 0, 0, 0),
('1800', 8, 0, 0, 0, 0),
('1800', 9, 0, 0, 0, 0),
('1800', 10, 0, 0, 0, 0),
('1800', 11, 0, 0, 0, 0),
('1800', 12, 0, 0, 0, 0),
('1800', 13, 0, 0, 0, 0),
('1800', 14, 0, 0, 0, 0),
('1800', 15, 0, 0, 0, 0),
('1800', 16, 0, 0, 0, 0),
('1800', 17, 0, 0, 0, 0),
('1800', 18, 0, 0, 0, 0),
('1800', 19, 0, 0, 0, 0),
('1800', 20, 0, 0, 0, 0),
('1800', 21, 0, 0, 0, 0),
('1800', 22, 0, 0, 0, 0),
('1800', 23, 0, 0, 0, 0),
('1800', 24, 0, 0, 0, 0),
('1850', -13, 0, 0, 0, 0),
('1850', -12, 0, 0, 0, 0),
('1850', -11, 0, 0, 0, 0),
('1850', -10, 0, 0, 0, 0),
('1850', -9, 0, 0, 0, 0),
('1850', -8, 0, 0, 0, 0),
('1850', -7, 0, 0, 0, 0),
('1850', -6, 0, 0, 0, 0),
('1850', -5, 0, 0, 0, 0),
('1850', -4, 0, 0, 0, 0),
('1850', -3, 0, 0, 0, 0),
('1850', -2, 0, 0, 0, 0),
('1850', -1, 0, 0, 0, 0),
('1850', 0, 0, 0, 0, 0),
('1850', 1, 0, 0, 0, 0),
('1850', 2, 0, 0, 0, 0),
('1850', 3, 0, 0, 0, 0),
('1850', 4, 0, 0, 0, 0),
('1850', 5, 0, 0, 0, 0),
('1850', 6, 0, 0, 0, 0),
('1850', 7, 0, 0, 0, 0),
('1850', 8, 0, 0, 0, 0),
('1850', 9, 0, 0, 0, 0),
('1850', 10, 0, 0, 0, 0),
('1850', 11, 0, 0, 0, 0),
('1850', 12, 0, 0, 0, 0),
('1850', 13, 0, 0, 0, 0),
('1850', 14, 0, 0, 0, 0),
('1850', 15, 0, 0, 0, 0),
('1850', 16, 0, 0, 0, 0),
('1850', 17, 0, 0, 0, 0),
('1850', 18, 0, 0, 0, 0),
('1850', 19, 0, 0, 0, 0),
('1850', 20, 0, 0, 0, 0),
('1850', 21, 0, 0, 0, 0),
('1850', 22, 0, 0, 0, 0),
('1850', 23, 0, 0, 0, 0),
('1850', 24, 0, 0, 0, 0),
('1900', -13, 0, 0, 0, 0),
('1900', -12, 0, 0, 0, 0),
('1900', -11, 0, 0, 0, 0),
('1900', -10, 0, 0, 0, 0),
('1900', -9, 0, 0, 0, 0),
('1900', -8, 0, 0, 0, 0),
('1900', -7, 0, 0, 0, 0),
('1900', -6, 0, 0, 0, 0),
('1900', -5, 0, 0, 0, 0),
('1900', -4, 0, 0, 0, 0),
('1900', -3, 0, 0, 0, 0),
('1900', -2, 0, 0, 0, 0),
('1900', -1, 0, 0, 0, 0),
('1900', 0, 0, 0, 0, 0),
('1900', 1, 0, 0, 0, 0),
('1900', 2, 0, 0, 0, 0),
('1900', 3, 0, 0, 0, 0),
('1900', 4, 0, 0, 0, 0),
('1900', 5, 0, 0, 0, 0),
('1900', 6, 0, 0, 0, 0),
('1900', 7, 0, 0, 0, 0),
('1900', 8, 0, 0, 0, 0),
('1900', 9, 0, 0, 0, 0),
('1900', 10, 0, 0, 0, 0),
('1900', 11, 0, 0, 0, 0),
('1900', 12, 0, 0, 0, 0),
('1900', 13, 0, 0, 0, 0),
('1900', 14, 0, 0, 0, 0),
('1900', 15, 0, 0, 0, 0),
('1900', 16, 0, 0, 0, 0),
('1900', 17, 0, 0, 0, 0),
('1900', 18, 0, 0, 0, 0),
('1900', 19, 0, 0, 0, 0),
('1900', 20, 0, 0, 0, 0),
('1900', 21, 0, 0, 0, 0),
('1900', 22, 0, 0, 0, 0),
('1900', 23, 0, 0, 0, 0),
('1900', 24, 0, 0, 0, 0),
('2010', -13, 0, 0, 0, 0),
('2010', -12, 0, 0, 0, 0),
('2010', -11, 0, 0, 0, 0),
('2010', -10, 0, 0, 0, 0),
('2010', -9, 0, 0, 0, 0),
('2010', -8, 0, 0, 0, 0),
('2010', -7, 0, 0, 0, 0),
('2010', -6, 0, 0, 0, 0),
('2010', -5, 0, 0, 0, 0),
('2010', -4, 0, 0, 0, 0),
('2010', -3, 0, 0, 0, 0),
('2010', -2, 0, 0, 0, 0),
('2010', -1, 0, 0, 0, 0),
('2010', 0, 0, 0, 0, 0),
('2010', 1, 0, 0, 0, 0),
('2010', 2, 0, 0, 0, 0),
('2010', 3, 0, 0, 0, 0),
('2010', 4, 0, 0, 0, 0),
('2010', 5, 0, 0, 0, 0),
('2010', 6, 0, 0, 0, 0),
('2010', 7, 0, 0, 0, 0),
('2010', 8, 0, 0, 0, 0),
('2010', 9, 0, 0, 0, 0),
('2010', 10, 0, 0, 0, 0),
('2010', 11, 0, 0, 0, 0),
('2010', 12, 0, 0, 0, 0),
('2010', 13, 0, 0, 0, 0),
('2010', 14, 0, 0, 0, 0),
('2010', 15, 0, 0, 0, 0),
('2010', 16, 0, 0, 0, 0),
('2010', 17, 0, 0, 0, 0),
('2010', 18, 0, 0, 0, 0),
('2010', 19, 0, 0, 0, 0),
('2010', 20, 0, 0, 0, 0),
('2010', 21, 0, 0, 0, 0),
('2010', 22, 0, 0, 0, 0),
('2010', 23, 0, 0, 0, 0),
('2010', 24, 0, 0, 0, 0),
('2020', -13, 0, 0, 0, 0),
('2020', -12, 0, 0, 0, 0),
('2020', -11, 0, 0, 0, 0),
('2020', -10, 0, 0, 0, 0),
('2020', -9, 0, 0, 0, 0),
('2020', -8, 0, 0, 0, 0),
('2020', -7, 0, 0, 0, 0),
('2020', -6, 0, 0, 0, 0),
('2020', -5, 0, 0, 0, 0),
('2020', -4, 0, 0, 0, 0),
('2020', -3, 0, 0, 0, 0),
('2020', -2, 0, 0, 0, 0),
('2020', -1, 0, 0, 0, 0),
('2020', 0, 0, 0, 0, 0),
('2020', 1, 0, 0, 0, 0),
('2020', 2, 0, 0, 0, 0),
('2020', 3, 0, 0, 0, 0),
('2020', 4, 0, 0, 0, 0),
('2020', 5, 0, 0, 0, 0),
('2020', 6, 0, 0, 0, 0),
('2020', 7, 0, 0, 0, 0),
('2020', 8, 0, 0, 0, 0),
('2020', 9, 0, 0, 0, 0),
('2020', 10, 0, 0, 0, 0),
('2020', 11, 0, 0, 0, 0),
('2020', 12, 0, 0, 0, 0),
('2020', 13, 0, 0, 0, 0),
('2020', 14, 0, 0, 0, 0),
('2020', 15, 0, 0, 0, 0),
('2020', 16, 0, 0, 0, 0),
('2020', 17, 0, 0, 0, 0),
('2020', 18, 0, 0, 0, 0),
('2020', 19, 0, 0, 0, 0),
('2020', 20, 0, 0, 0, 0),
('2020', 21, 0, 0, 0, 0),
('2020', 22, 0, 0, 0, 0),
('2020', 23, 0, 0, 0, 0),
('2020', 24, 0, 0, 0, 0),
('2100', -13, 0, 0, 0, 0),
('2100', -12, 0, 0, 0, 0),
('2100', -11, 0, 0, 0, 0),
('2100', -10, 0, 0, 0, 0),
('2100', -9, 0, 0, 0, 0),
('2100', -8, 0, 0, 0, 0),
('2100', -7, 0, 0, 0, 0),
('2100', -6, 0, 0, 0, 0),
('2100', -5, 0, 0, 0, 0),
('2100', -4, 0, 0, 0, 0),
('2100', -3, 0, 0, 0, 0),
('2100', -2, 0, 0, 0, 0),
('2100', -1, 0, 0, 0, 0),
('2100', 0, 0, 0, 0, 0),
('2100', 1, 0, 0, 0, 0),
('2100', 2, 0, 0, 0, 0),
('2100', 3, 0, 0, 0, 0),
('2100', 4, 0, 0, 0, 0),
('2100', 5, 0, 0, 0, 0),
('2100', 6, 0, 0, 0, 0),
('2100', 7, 0, 0, 0, 0),
('2100', 8, 0, 0, 0, 0),
('2100', 9, 0, 0, 0, 0),
('2100', 10, 0, 0, 0, 0),
('2100', 11, 0, 0, 0, 0),
('2100', 12, 0, 0, 0, 0),
('2100', 13, 0, 0, 0, 0),
('2100', 14, 0, 0, 0, 0),
('2100', 15, 0, 0, 0, 0),
('2100', 16, 0, 0, 0, 0),
('2100', 17, 0, 0, 0, 0),
('2100', 18, 0, 0, 0, 0),
('2100', 19, 0, 0, 0, 0),
('2100', 20, 0, 0, 0, 0),
('2100', 21, 0, 0, 0, 0),
('2100', 22, 0, 0, 0, 0),
('2100', 23, 0, 0, 0, 0),
('2100', 24, 0, 0, 0, 0),
('2150', -13, 0, 0, 0, 0),
('2150', -12, 0, 0, 0, 0),
('2150', -11, 0, 0, 0, 0),
('2150', -10, 0, 0, 0, 0),
('2150', -9, 0, 0, 0, 0),
('2150', -8, 0, 0, 0, 0),
('2150', -7, 0, 0, 0, 0),
('2150', -6, 0, 0, 0, 0),
('2150', -5, 0, 0, 0, 0),
('2150', -4, 0, 0, 0, 0),
('2150', -3, 0, 0, 0, 0),
('2150', -2, 0, 0, 0, 0),
('2150', -1, 0, 0, 0, 0),
('2150', 0, 0, 0, 0, 0),
('2150', 1, 0, 0, 0, 0),
('2150', 2, 0, 0, 0, 0),
('2150', 3, 0, 0, 0, 0),
('2150', 4, 0, 0, 0, 0),
('2150', 5, 0, 0, 0, 0),
('2150', 6, 0, 0, 0, 0),
('2150', 7, 0, 0, 0, 0),
('2150', 8, 0, 0, 0, 0),
('2150', 9, 0, 0, 0, 0),
('2150', 10, 0, 0, 0, 0),
('2150', 11, 0, 0, 0, 0),
('2150', 12, 0, 0, 0, 0),
('2150', 13, 0, 0, 0, 0),
('2150', 14, 0, 0, 0, 0),
('2150', 15, 0, 0, 0, 0),
('2150', 16, 0, 0, 0, 0),
('2150', 17, 0, 0, 0, 0),
('2150', 18, 0, 0, 0, 0),
('2150', 19, 0, 0, 0, 0),
('2150', 20, 0, 0, 0, 0),
('2150', 21, 0, 0, 0, 0),
('2150', 22, 0, 0, 0, 0),
('2150', 23, 0, 0, 0, 0),
('2150', 24, 0, 0, 0, 0),
('2200', -13, 0, 0, 0, 0),
('2200', -12, 0, 0, 0, 0),
('2200', -11, 0, 0, 0, 0),
('2200', -10, 0, 0, 0, 0),
('2200', -9, 0, 0, 0, 0),
('2200', -8, 0, 0, 0, 0),
('2200', -7, 0, 0, 0, 0),
('2200', -6, 0, 0, 0, 0),
('2200', -5, 0, 0, 0, 0),
('2200', -4, 0, 0, 0, 0),
('2200', -3, 0, 0, 0, 0),
('2200', -2, 0, 0, 0, 0),
('2200', -1, 0, 0, 0, 0),
('2200', 0, 0, 0, 0, 0),
('2200', 1, 0, 0, 0, 0),
('2200', 2, 0, 0, 0, 0),
('2200', 3, 0, 0, 0, 0),
('2200', 4, 0, 0, 0, 0),
('2200', 5, 0, 0, 0, 0),
('2200', 6, 0, 0, 0, 0),
('2200', 7, 0, 0, 0, 0),
('2200', 8, 0, 0, 0, 0),
('2200', 9, 0, 0, 0, 0),
('2200', 10, 0, 0, 0, 0),
('2200', 11, 0, 0, 0, 0),
('2200', 12, 0, 0, 0, 0),
('2200', 13, 0, 0, 0, 0),
('2200', 14, 0, 0, 0, 0),
('2200', 15, 0, 0, 0, 0),
('2200', 16, 0, 0, 0, 0),
('2200', 17, 0, 0, 0, 0),
('2200', 18, 0, 0, 0, 0),
('2200', 19, 0, 0, 0, 0),
('2200', 20, 0, 0, 0, 0),
('2200', 21, 0, 0, 0, 0),
('2200', 22, 0, 0, 0, 0),
('2200', 23, 0, 0, 0, 0),
('2200', 24, 0, 0, 0, 0),
('2230', -13, 0, 0, 0, 0),
('2230', -12, 0, 0, 0, 0),
('2230', -11, 0, 0, 0, 0),
('2230', -10, 0, 0, 0, 0),
('2230', -9, 0, 0, 0, 0),
('2230', -8, 0, 0, 0, 0),
('2230', -7, 0, 0, 0, 0),
('2230', -6, 0, 0, 0, 0),
('2230', -5, 0, 0, 0, 0),
('2230', -4, 0, 0, 0, 0),
('2230', -3, 0, 0, 0, 0),
('2230', -2, 0, 0, 0, 0),
('2230', -1, 0, 0, 0, 0),
('2230', 0, 0, 0, 0, 0),
('2230', 1, 0, 0, 0, 0),
('2230', 2, 0, 0, 0, 0),
('2230', 3, 0, 0, 0, 0),
('2230', 4, 0, 0, 0, 0),
('2230', 5, 0, 0, 0, 0),
('2230', 6, 0, 0, 0, 0),
('2230', 7, 0, 0, 0, 0),
('2230', 8, 0, 0, 0, 0),
('2230', 9, 0, 0, 0, 0),
('2230', 10, 0, 0, 0, 0),
('2230', 11, 0, 0, 0, 0),
('2230', 12, 0, 0, 0, 0),
('2230', 13, 0, 0, 0, 0),
('2230', 14, 0, 0, 0, 0),
('2230', 15, 0, 0, 0, 0),
('2230', 16, 0, 0, 0, 0),
('2230', 17, 0, 0, 0, 0),
('2230', 18, 0, 0, 0, 0),
('2230', 19, 0, 0, 0, 0),
('2230', 20, 0, 0, 0, 0),
('2230', 21, 0, 0, 0, 0),
('2230', 22, 0, 0, 0, 0),
('2230', 23, 0, 0, 0, 0),
('2230', 24, 0, 0, 0, 0),
('2250', -13, 0, 0, 0, 0),
('2250', -12, 0, 0, 0, 0),
('2250', -11, 0, 0, 0, 0),
('2250', -10, 0, 0, 0, 0),
('2250', -9, 0, 0, 0, 0),
('2250', -8, 0, 0, 0, 0),
('2250', -7, 0, 0, 0, 0),
('2250', -6, 0, 0, 0, 0),
('2250', -5, 0, 0, 0, 0),
('2250', -4, 0, 0, 0, 0),
('2250', -3, 0, 0, 0, 0),
('2250', -2, 0, 0, 0, 0),
('2250', -1, 0, 0, 0, 0),
('2250', 0, 0, 0, 0, 0),
('2250', 1, 0, 0, 0, 0),
('2250', 2, 0, 0, 0, 0),
('2250', 3, 0, 0, 0, 0),
('2250', 4, 0, 0, 0, 0),
('2250', 5, 0, 0, 0, 0),
('2250', 6, 0, 0, 0, 0),
('2250', 7, 0, 0, 0, 0),
('2250', 8, 0, 0, 0, 0),
('2250', 9, 0, 0, 0, 0),
('2250', 10, 0, 0, 0, 0),
('2250', 11, 0, 0, 0, 0),
('2250', 12, 0, 0, 0, 0),
('2250', 13, 0, 0, 0, 0),
('2250', 14, 0, 0, 0, 0),
('2250', 15, 0, 0, 0, 0),
('2250', 16, 0, 0, 0, 0),
('2250', 17, 0, 0, 0, 0),
('2250', 18, 0, 0, 0, 0),
('2250', 19, 0, 0, 0, 0),
('2250', 20, 0, 0, 0, 0),
('2250', 21, 0, 0, 0, 0),
('2250', 22, 0, 0, 0, 0),
('2250', 23, 0, 0, 0, 0),
('2250', 24, 0, 0, 0, 0),
('2300', -13, 0, 0, 0, 0),
('2300', -12, 0, 0, 0, 0),
('2300', -11, 0, 0, 0, 0),
('2300', -10, 0, 0, 0, 0),
('2300', -9, 0, 0, 0, 0),
('2300', -8, 0, 0, 0, 0),
('2300', -7, 0, 0, 0, 0),
('2300', -6, 0, 0, 0, 0),
('2300', -5, 0, 0, 0, 0),
('2300', -4, 0, 0, 0, 0),
('2300', -3, 0, 0, 0, 0),
('2300', -2, 0, 0, 0, 0),
('2300', -1, 0, 0, 0, 0),
('2300', 0, 0, 0, 0, 0),
('2300', 1, 0, 0, 0, 0),
('2300', 2, 0, 0, 0, 0),
('2300', 3, 0, 0, 0, 0),
('2300', 4, 0, 0, 0, 0),
('2300', 5, 0, 0, 0, 0),
('2300', 6, 0, 0, 0, 0),
('2300', 7, 0, 0, 0, 0),
('2300', 8, 0, 0, 0, 0),
('2300', 9, 0, 0, 0, 0),
('2300', 10, 0, 0, 0, 0),
('2300', 11, 0, 0, 0, 0),
('2300', 12, 0, 0, 0, 0),
('2300', 13, 0, 0, 0, 0),
('2300', 14, 0, 0, 0, 0),
('2300', 15, 0, 0, 0, 0),
('2300', 16, 0, 0, 0, 0),
('2300', 17, 0, 0, 0, 0),
('2300', 18, 0, 0, 0, 0),
('2300', 19, 0, 0, 0, 0),
('2300', 20, 0, 0, 0, 0),
('2300', 21, 0, 0, 0, 0),
('2300', 22, 0, 0, 0, 0),
('2300', 23, 0, 0, 0, 0),
('2300', 24, 0, 0, 0, 0),
('2310', -13, 0, 0, 0, 0),
('2310', -12, 0, 0, 0, 0),
('2310', -11, 0, 0, 0, 0),
('2310', -10, 0, 0, 0, 0),
('2310', -9, 0, 0, 0, 0),
('2310', -8, 0, 0, 0, 0),
('2310', -7, 0, 0, 0, 0),
('2310', -6, 0, 0, 0, 0),
('2310', -5, 0, 0, 0, 0),
('2310', -4, 0, 0, 0, 0),
('2310', -3, 0, 0, 0, 0),
('2310', -2, 0, 0, 0, 0),
('2310', -1, 0, 0, 0, 0),
('2310', 0, 0, 0, 0, 0),
('2310', 1, 0, 0, 0, 0),
('2310', 2, 0, 0, 0, 0),
('2310', 3, 0, 0, 0, 0),
('2310', 4, 0, 0, 0, 0),
('2310', 5, 0, 0, 0, 0),
('2310', 6, 0, 0, 0, 0),
('2310', 7, 0, 0, 0, 0),
('2310', 8, 0, 0, 0, 0),
('2310', 9, 0, 0, 0, 0),
('2310', 10, 0, 0, 0, 0),
('2310', 11, 0, 0, 0, 0),
('2310', 12, 0, 0, 0, 0),
('2310', 13, 0, 0, 0, 0),
('2310', 14, 0, 0, 0, 0),
('2310', 15, 0, 0, 0, 0),
('2310', 16, 0, 0, 0, 0),
('2310', 17, 0, 0, 0, 0),
('2310', 18, 0, 0, 0, 0),
('2310', 19, 0, 0, 0, 0),
('2310', 20, 0, 0, 0, 0),
('2310', 21, 0, 0, 0, 0),
('2310', 22, 0, 0, 0, 0),
('2310', 23, 0, 0, 0, 0),
('2310', 24, 0, 0, 0, 0),
('2320', -13, 0, 0, 0, 0),
('2320', -12, 0, 0, 0, 0),
('2320', -11, 0, 0, 0, 0),
('2320', -10, 0, 0, 0, 0),
('2320', -9, 0, 0, 0, 0),
('2320', -8, 0, 0, 0, 0),
('2320', -7, 0, 0, 0, 0),
('2320', -6, 0, 0, 0, 0),
('2320', -5, 0, 0, 0, 0),
('2320', -4, 0, 0, 0, 0),
('2320', -3, 0, 0, 0, 0),
('2320', -2, 0, 0, 0, 0),
('2320', -1, 0, 0, 0, 0),
('2320', 0, 0, 0, 0, 0),
('2320', 1, 0, 0, 0, 0),
('2320', 2, 0, 0, 0, 0),
('2320', 3, 0, 0, 0, 0),
('2320', 4, 0, 0, 0, 0),
('2320', 5, 0, 0, 0, 0),
('2320', 6, 0, 0, 0, 0),
('2320', 7, 0, 0, 0, 0),
('2320', 8, 0, 0, 0, 0),
('2320', 9, 0, 0, 0, 0),
('2320', 10, 0, 0, 0, 0),
('2320', 11, 0, 0, 0, 0),
('2320', 12, 0, 0, 0, 0),
('2320', 13, 0, 0, 0, 0),
('2320', 14, 0, 0, 0, 0),
('2320', 15, 0, 0, 0, 0),
('2320', 16, 0, 0, 0, 0),
('2320', 17, 0, 0, 0, 0),
('2320', 18, 0, 0, 0, 0),
('2320', 19, 0, 0, 0, 0),
('2320', 20, 0, 0, 0, 0),
('2320', 21, 0, 0, 0, 0),
('2320', 22, 0, 0, 0, 0),
('2320', 23, 0, 0, 0, 0),
('2320', 24, 0, 0, 0, 0),
('2330', -13, 0, 0, 0, 0),
('2330', -12, 0, 0, 0, 0),
('2330', -11, 0, 0, 0, 0),
('2330', -10, 0, 0, 0, 0),
('2330', -9, 0, 0, 0, 0),
('2330', -8, 0, 0, 0, 0),
('2330', -7, 0, 0, 0, 0),
('2330', -6, 0, 0, 0, 0),
('2330', -5, 0, 0, 0, 0),
('2330', -4, 0, 0, 0, 0),
('2330', -3, 0, 0, 0, 0),
('2330', -2, 0, 0, 0, 0),
('2330', -1, 0, 0, 0, 0),
('2330', 0, 0, 0, 0, 0),
('2330', 1, 0, 0, 0, 0),
('2330', 2, 0, 0, 0, 0),
('2330', 3, 0, 0, 0, 0),
('2330', 4, 0, 0, 0, 0),
('2330', 5, 0, 0, 0, 0),
('2330', 6, 0, 0, 0, 0),
('2330', 7, 0, 0, 0, 0),
('2330', 8, 0, 0, 0, 0),
('2330', 9, 0, 0, 0, 0),
('2330', 10, 0, 0, 0, 0),
('2330', 11, 0, 0, 0, 0),
('2330', 12, 0, 0, 0, 0),
('2330', 13, 0, 0, 0, 0),
('2330', 14, 0, 0, 0, 0),
('2330', 15, 0, 0, 0, 0),
('2330', 16, 0, 0, 0, 0),
('2330', 17, 0, 0, 0, 0),
('2330', 18, 0, 0, 0, 0),
('2330', 19, 0, 0, 0, 0),
('2330', 20, 0, 0, 0, 0),
('2330', 21, 0, 0, 0, 0),
('2330', 22, 0, 0, 0, 0),
('2330', 23, 0, 0, 0, 0),
('2330', 24, 0, 0, 0, 0),
('2340', -13, 0, 0, 0, 0),
('2340', -12, 0, 0, 0, 0),
('2340', -11, 0, 0, 0, 0),
('2340', -10, 0, 0, 0, 0),
('2340', -9, 0, 0, 0, 0),
('2340', -8, 0, 0, 0, 0),
('2340', -7, 0, 0, 0, 0),
('2340', -6, 0, 0, 0, 0),
('2340', -5, 0, 0, 0, 0),
('2340', -4, 0, 0, 0, 0),
('2340', -3, 0, 0, 0, 0),
('2340', -2, 0, 0, 0, 0),
('2340', -1, 0, 0, 0, 0),
('2340', 0, 0, 0, 0, 0),
('2340', 1, 0, 0, 0, 0),
('2340', 2, 0, 0, 0, 0),
('2340', 3, 0, 0, 0, 0),
('2340', 4, 0, 0, 0, 0),
('2340', 5, 0, 0, 0, 0),
('2340', 6, 0, 0, 0, 0),
('2340', 7, 0, 0, 0, 0),
('2340', 8, 0, 0, 0, 0),
('2340', 9, 0, 0, 0, 0),
('2340', 10, 0, 0, 0, 0),
('2340', 11, 0, 0, 0, 0),
('2340', 12, 0, 0, 0, 0),
('2340', 13, 0, 0, 0, 0),
('2340', 14, 0, 0, 0, 0),
('2340', 15, 0, 0, 0, 0),
('2340', 16, 0, 0, 0, 0),
('2340', 17, 0, 0, 0, 0),
('2340', 18, 0, 0, 0, 0),
('2340', 19, 0, 0, 0, 0),
('2340', 20, 0, 0, 0, 0),
('2340', 21, 0, 0, 0, 0),
('2340', 22, 0, 0, 0, 0),
('2340', 23, 0, 0, 0, 0),
('2340', 24, 0, 0, 0, 0),
('2350', -13, 0, 0, 0, 0),
('2350', -12, 0, 0, 0, 0),
('2350', -11, 0, 0, 0, 0),
('2350', -10, 0, 0, 0, 0),
('2350', -9, 0, 0, 0, 0),
('2350', -8, 0, 0, 0, 0),
('2350', -7, 0, 0, 0, 0),
('2350', -6, 0, 0, 0, 0),
('2350', -5, 0, 0, 0, 0),
('2350', -4, 0, 0, 0, 0),
('2350', -3, 0, 0, 0, 0),
('2350', -2, 0, 0, 0, 0),
('2350', -1, 0, 0, 0, 0),
('2350', 0, 0, 0, 0, 0),
('2350', 1, 0, 0, 0, 0),
('2350', 2, 0, 0, 0, 0),
('2350', 3, 0, 0, 0, 0),
('2350', 4, 0, 0, 0, 0),
('2350', 5, 0, 0, 0, 0),
('2350', 6, 0, 0, 0, 0),
('2350', 7, 0, 0, 0, 0),
('2350', 8, 0, 0, 0, 0),
('2350', 9, 0, 0, 0, 0),
('2350', 10, 0, 0, 0, 0),
('2350', 11, 0, 0, 0, 0),
('2350', 12, 0, 0, 0, 0),
('2350', 13, 0, 0, 0, 0),
('2350', 14, 0, 0, 0, 0),
('2350', 15, 0, 0, 0, 0),
('2350', 16, 0, 0, 0, 0),
('2350', 17, 0, 0, 0, 0),
('2350', 18, 0, 0, 0, 0),
('2350', 19, 0, 0, 0, 0),
('2350', 20, 0, 0, 0, 0),
('2350', 21, 0, 0, 0, 0),
('2350', 22, 0, 0, 0, 0),
('2350', 23, 0, 0, 0, 0),
('2350', 24, 0, 0, 0, 0),
('2360', -13, 0, 0, 0, 0),
('2360', -12, 0, 0, 0, 0),
('2360', -11, 0, 0, 0, 0),
('2360', -10, 0, 0, 0, 0),
('2360', -9, 0, 0, 0, 0),
('2360', -8, 0, 0, 0, 0),
('2360', -7, 0, 0, 0, 0),
('2360', -6, 0, 0, 0, 0),
('2360', -5, 0, 0, 0, 0),
('2360', -4, 0, 0, 0, 0),
('2360', -3, 0, 0, 0, 0),
('2360', -2, 0, 0, 0, 0),
('2360', -1, 0, 0, 0, 0),
('2360', 0, 0, 0, 0, 0),
('2360', 1, 0, 0, 0, 0),
('2360', 2, 0, 0, 0, 0),
('2360', 3, 0, 0, 0, 0),
('2360', 4, 0, 0, 0, 0),
('2360', 5, 0, 0, 0, 0),
('2360', 6, 0, 0, 0, 0),
('2360', 7, 0, 0, 0, 0),
('2360', 8, 0, 0, 0, 0),
('2360', 9, 0, 0, 0, 0),
('2360', 10, 0, 0, 0, 0),
('2360', 11, 0, 0, 0, 0),
('2360', 12, 0, 0, 0, 0),
('2360', 13, 0, 0, 0, 0),
('2360', 14, 0, 0, 0, 0),
('2360', 15, 0, 0, 0, 0),
('2360', 16, 0, 0, 0, 0),
('2360', 17, 0, 0, 0, 0),
('2360', 18, 0, 0, 0, 0),
('2360', 19, 0, 0, 0, 0),
('2360', 20, 0, 0, 0, 0),
('2360', 21, 0, 0, 0, 0),
('2360', 22, 0, 0, 0, 0),
('2360', 23, 0, 0, 0, 0),
('2360', 24, 0, 0, 0, 0),
('2400', -13, 0, 0, 0, 0),
('2400', -12, 0, 0, 0, 0),
('2400', -11, 0, 0, 0, 0),
('2400', -10, 0, 0, 0, 0),
('2400', -9, 0, 0, 0, 0),
('2400', -8, 0, 0, 0, 0),
('2400', -7, 0, 0, 0, 0),
('2400', -6, 0, 0, 0, 0),
('2400', -5, 0, 0, 0, 0),
('2400', -4, 0, 0, 0, 0),
('2400', -3, 0, 0, 0, 0),
('2400', -2, 0, 0, 0, 0),
('2400', -1, 0, 0, 0, 0),
('2400', 0, 0, 0, 0, 0),
('2400', 1, 0, 0, 0, 0),
('2400', 2, 0, 0, 0, 0),
('2400', 3, 0, 0, 0, 0),
('2400', 4, 0, 0, 0, 0),
('2400', 5, 0, 0, 0, 0),
('2400', 6, 0, 0, 0, 0),
('2400', 7, 0, 0, 0, 0),
('2400', 8, 0, 0, 0, 0),
('2400', 9, 0, 0, 0, 0),
('2400', 10, 0, 0, 0, 0),
('2400', 11, 0, 0, 0, 0),
('2400', 12, 0, 0, 0, 0),
('2400', 13, 0, 0, 0, 0),
('2400', 14, 0, 0, 0, 0),
('2400', 15, 0, 0, 0, 0),
('2400', 16, 0, 0, 0, 0),
('2400', 17, 0, 0, 0, 0),
('2400', 18, 0, 0, 0, 0),
('2400', 19, 0, 0, 0, 0),
('2400', 20, 0, 0, 0, 0),
('2400', 21, 0, 0, 0, 0),
('2400', 22, 0, 0, 0, 0),
('2400', 23, 0, 0, 0, 0),
('2400', 24, 0, 0, 0, 0),
('2410', -13, 0, 0, 0, 0),
('2410', -12, 0, 0, 0, 0),
('2410', -11, 0, 0, 0, 0),
('2410', -10, 0, 0, 0, 0),
('2410', -9, 0, 0, 0, 0),
('2410', -8, 0, 0, 0, 0),
('2410', -7, 0, 0, 0, 0),
('2410', -6, 0, 0, 0, 0),
('2410', -5, 0, 0, 0, 0),
('2410', -4, 0, 0, 0, 0),
('2410', -3, 0, 0, 0, 0),
('2410', -2, 0, 0, 0, 0),
('2410', -1, 0, 0, 0, 0),
('2410', 0, 0, 0, 0, 0),
('2410', 1, 0, 0, 0, 0),
('2410', 2, 0, 0, 0, 0),
('2410', 3, 0, 0, 0, 0),
('2410', 4, 0, 0, 0, 0),
('2410', 5, 0, 0, 0, 0),
('2410', 6, 0, 0, 0, 0),
('2410', 7, 0, 0, 0, 0),
('2410', 8, 0, 0, 0, 0),
('2410', 9, 0, 0, 0, 0),
('2410', 10, 0, 0, 0, 0),
('2410', 11, 0, 0, 0, 0),
('2410', 12, 0, 0, 0, 0),
('2410', 13, 0, 0, 0, 0),
('2410', 14, 0, 0, 0, 0),
('2410', 15, 0, 0, 0, 0),
('2410', 16, 0, 0, 0, 0),
('2410', 17, 0, 0, 0, 0),
('2410', 18, 0, 0, 0, 0),
('2410', 19, 0, 0, 0, 0),
('2410', 20, 0, 0, 0, 0),
('2410', 21, 0, 0, 0, 0),
('2410', 22, 0, 0, 0, 0),
('2410', 23, 0, 0, 0, 0),
('2410', 24, 0, 0, 0, 0),
('2420', -13, 0, 0, 0, 0),
('2420', -12, 0, 0, 0, 0),
('2420', -11, 0, 0, 0, 0),
('2420', -10, 0, 0, 0, 0),
('2420', -9, 0, 0, 0, 0),
('2420', -8, 0, 0, 0, 0),
('2420', -7, 0, 0, 0, 0),
('2420', -6, 0, 0, 0, 0),
('2420', -5, 0, 0, 0, 0),
('2420', -4, 0, 0, 0, 0),
('2420', -3, 0, 0, 0, 0),
('2420', -2, 0, 0, 0, 0),
('2420', -1, 0, 0, 0, 0),
('2420', 0, 0, 0, 0, 0),
('2420', 1, 0, 0, 0, 0),
('2420', 2, 0, 0, 0, 0),
('2420', 3, 0, 0, 0, 0),
('2420', 4, 0, 0, 0, 0),
('2420', 5, 0, 0, 0, 0),
('2420', 6, 0, 0, 0, 0),
('2420', 7, 0, 0, 0, 0),
('2420', 8, 0, 0, 0, 0),
('2420', 9, 0, 0, 0, 0),
('2420', 10, 0, 0, 0, 0),
('2420', 11, 0, 0, 0, 0),
('2420', 12, 0, 0, 0, 0),
('2420', 13, 0, 0, 0, 0),
('2420', 14, 0, 0, 0, 0),
('2420', 15, 0, 0, 0, 0),
('2420', 16, 0, 0, 0, 0),
('2420', 17, 0, 0, 0, 0),
('2420', 18, 0, 0, 0, 0),
('2420', 19, 0, 0, 0, 0),
('2420', 20, 0, 0, 0, 0),
('2420', 21, 0, 0, 0, 0),
('2420', 22, 0, 0, 0, 0),
('2420', 23, 0, 0, 0, 0),
('2420', 24, 0, 0, 0, 0),
('2450', -13, 0, 0, 0, 0),
('2450', -12, 0, 0, 0, 0),
('2450', -11, 0, 0, 0, 0),
('2450', -10, 0, 0, 0, 0),
('2450', -9, 0, 0, 0, 0),
('2450', -8, 0, 0, 0, 0),
('2450', -7, 0, 0, 0, 0),
('2450', -6, 0, 0, 0, 0),
('2450', -5, 0, 0, 0, 0),
('2450', -4, 0, 0, 0, 0),
('2450', -3, 0, 0, 0, 0),
('2450', -2, 0, 0, 0, 0),
('2450', -1, 0, 0, 0, 0),
('2450', 0, 0, 0, 0, 0),
('2450', 1, 0, 0, 0, 0),
('2450', 2, 0, 0, 0, 0),
('2450', 3, 0, 0, 0, 0),
('2450', 4, 0, 0, 0, 0),
('2450', 5, 0, 0, 0, 0),
('2450', 6, 0, 0, 0, 0),
('2450', 7, 0, 0, 0, 0),
('2450', 8, 0, 0, 0, 0),
('2450', 9, 0, 0, 0, 0),
('2450', 10, 0, 0, 0, 0),
('2450', 11, 0, 0, 0, 0),
('2450', 12, 0, 0, 0, 0),
('2450', 13, 0, 0, 0, 0),
('2450', 14, 0, 0, 0, 0),
('2450', 15, 0, 0, 0, 0),
('2450', 16, 0, 0, 0, 0),
('2450', 17, 0, 0, 0, 0),
('2450', 18, 0, 0, 0, 0),
('2450', 19, 0, 0, 0, 0),
('2450', 20, 0, 0, 0, 0),
('2450', 21, 0, 0, 0, 0),
('2450', 22, 0, 0, 0, 0),
('2450', 23, 0, 0, 0, 0),
('2450', 24, 0, 0, 0, 0),
('2460', -13, 0, 0, 0, 0),
('2460', -12, 0, 0, 0, 0);
INSERT INTO `chartdetails` (`accountcode`, `period`, `budget`, `actual`, `bfwd`, `bfwdbudget`) VALUES
('2460', -11, 0, 0, 0, 0),
('2460', -10, 0, 0, 0, 0),
('2460', -9, 0, 0, 0, 0),
('2460', -8, 0, 0, 0, 0),
('2460', -7, 0, 0, 0, 0),
('2460', -6, 0, 0, 0, 0),
('2460', -5, 0, 0, 0, 0),
('2460', -4, 0, 0, 0, 0),
('2460', -3, 0, 0, 0, 0),
('2460', -2, 0, 0, 0, 0),
('2460', -1, 0, 0, 0, 0),
('2460', 0, 0, 0, 0, 0),
('2460', 1, 0, 0, 0, 0),
('2460', 2, 0, 0, 0, 0),
('2460', 3, 0, 0, 0, 0),
('2460', 4, 0, 0, 0, 0),
('2460', 5, 0, 0, 0, 0),
('2460', 6, 0, 0, 0, 0),
('2460', 7, 0, 0, 0, 0),
('2460', 8, 0, 0, 0, 0),
('2460', 9, 0, 0, 0, 0),
('2460', 10, 0, 0, 0, 0),
('2460', 11, 0, 0, 0, 0),
('2460', 12, 0, 0, 0, 0),
('2460', 13, 0, 0, 0, 0),
('2460', 14, 0, 0, 0, 0),
('2460', 15, 0, 0, 0, 0),
('2460', 16, 0, 0, 0, 0),
('2460', 17, 0, 0, 0, 0),
('2460', 18, 0, 0, 0, 0),
('2460', 19, 0, 0, 0, 0),
('2460', 20, 0, 0, 0, 0),
('2460', 21, 0, 0, 0, 0),
('2460', 22, 0, 0, 0, 0),
('2460', 23, 0, 0, 0, 0),
('2460', 24, 0, 0, 0, 0),
('2470', -13, 0, 0, 0, 0),
('2470', -12, 0, 0, 0, 0),
('2470', -11, 0, 0, 0, 0),
('2470', -10, 0, 0, 0, 0),
('2470', -9, 0, 0, 0, 0),
('2470', -8, 0, 0, 0, 0),
('2470', -7, 0, 0, 0, 0),
('2470', -6, 0, 0, 0, 0),
('2470', -5, 0, 0, 0, 0),
('2470', -4, 0, 0, 0, 0),
('2470', -3, 0, 0, 0, 0),
('2470', -2, 0, 0, 0, 0),
('2470', -1, 0, 0, 0, 0),
('2470', 0, 0, 0, 0, 0),
('2470', 1, 0, 0, 0, 0),
('2470', 2, 0, 0, 0, 0),
('2470', 3, 0, 0, 0, 0),
('2470', 4, 0, 0, 0, 0),
('2470', 5, 0, 0, 0, 0),
('2470', 6, 0, 0, 0, 0),
('2470', 7, 0, 0, 0, 0),
('2470', 8, 0, 0, 0, 0),
('2470', 9, 0, 0, 0, 0),
('2470', 10, 0, 0, 0, 0),
('2470', 11, 0, 0, 0, 0),
('2470', 12, 0, 0, 0, 0),
('2470', 13, 0, 0, 0, 0),
('2470', 14, 0, 0, 0, 0),
('2470', 15, 0, 0, 0, 0),
('2470', 16, 0, 0, 0, 0),
('2470', 17, 0, 0, 0, 0),
('2470', 18, 0, 0, 0, 0),
('2470', 19, 0, 0, 0, 0),
('2470', 20, 0, 0, 0, 0),
('2470', 21, 0, 0, 0, 0),
('2470', 22, 0, 0, 0, 0),
('2470', 23, 0, 0, 0, 0),
('2470', 24, 0, 0, 0, 0),
('2480', -13, 0, 0, 0, 0),
('2480', -12, 0, 0, 0, 0),
('2480', -11, 0, 0, 0, 0),
('2480', -10, 0, 0, 0, 0),
('2480', -9, 0, 0, 0, 0),
('2480', -8, 0, 0, 0, 0),
('2480', -7, 0, 0, 0, 0),
('2480', -6, 0, 0, 0, 0),
('2480', -5, 0, 0, 0, 0),
('2480', -4, 0, 0, 0, 0),
('2480', -3, 0, 0, 0, 0),
('2480', -2, 0, 0, 0, 0),
('2480', -1, 0, 0, 0, 0),
('2480', 0, 0, 0, 0, 0),
('2480', 1, 0, 0, 0, 0),
('2480', 2, 0, 0, 0, 0),
('2480', 3, 0, 0, 0, 0),
('2480', 4, 0, 0, 0, 0),
('2480', 5, 0, 0, 0, 0),
('2480', 6, 0, 0, 0, 0),
('2480', 7, 0, 0, 0, 0),
('2480', 8, 0, 0, 0, 0),
('2480', 9, 0, 0, 0, 0),
('2480', 10, 0, 0, 0, 0),
('2480', 11, 0, 0, 0, 0),
('2480', 12, 0, 0, 0, 0),
('2480', 13, 0, 0, 0, 0),
('2480', 14, 0, 0, 0, 0),
('2480', 15, 0, 0, 0, 0),
('2480', 16, 0, 0, 0, 0),
('2480', 17, 0, 0, 0, 0),
('2480', 18, 0, 0, 0, 0),
('2480', 19, 0, 0, 0, 0),
('2480', 20, 0, 0, 0, 0),
('2480', 21, 0, 0, 0, 0),
('2480', 22, 0, 0, 0, 0),
('2480', 23, 0, 0, 0, 0),
('2480', 24, 0, 0, 0, 0),
('2500', -13, 0, 0, 0, 0),
('2500', -12, 0, 0, 0, 0),
('2500', -11, 0, 0, 0, 0),
('2500', -10, 0, 0, 0, 0),
('2500', -9, 0, 0, 0, 0),
('2500', -8, 0, 0, 0, 0),
('2500', -7, 0, 0, 0, 0),
('2500', -6, 0, 0, 0, 0),
('2500', -5, 0, 0, 0, 0),
('2500', -4, 0, 0, 0, 0),
('2500', -3, 0, 0, 0, 0),
('2500', -2, 0, 0, 0, 0),
('2500', -1, 0, 0, 0, 0),
('2500', 0, 0, 0, 0, 0),
('2500', 1, 0, 0, 0, 0),
('2500', 2, 0, 0, 0, 0),
('2500', 3, 0, 0, 0, 0),
('2500', 4, 0, 0, 0, 0),
('2500', 5, 0, 0, 0, 0),
('2500', 6, 0, 0, 0, 0),
('2500', 7, 0, 0, 0, 0),
('2500', 8, 0, 0, 0, 0),
('2500', 9, 0, 0, 0, 0),
('2500', 10, 0, 0, 0, 0),
('2500', 11, 0, 0, 0, 0),
('2500', 12, 0, 0, 0, 0),
('2500', 13, 0, 0, 0, 0),
('2500', 14, 0, 0, 0, 0),
('2500', 15, 0, 0, 0, 0),
('2500', 16, 0, 0, 0, 0),
('2500', 17, 0, 0, 0, 0),
('2500', 18, 0, 0, 0, 0),
('2500', 19, 0, 0, 0, 0),
('2500', 20, 0, 0, 0, 0),
('2500', 21, 0, 0, 0, 0),
('2500', 22, 0, 0, 0, 0),
('2500', 23, 0, 0, 0, 0),
('2500', 24, 0, 0, 0, 0),
('2550', -13, 0, 0, 0, 0),
('2550', -12, 0, 0, 0, 0),
('2550', -11, 0, 0, 0, 0),
('2550', -10, 0, 0, 0, 0),
('2550', -9, 0, 0, 0, 0),
('2550', -8, 0, 0, 0, 0),
('2550', -7, 0, 0, 0, 0),
('2550', -6, 0, 0, 0, 0),
('2550', -5, 0, 0, 0, 0),
('2550', -4, 0, 0, 0, 0),
('2550', -3, 0, 0, 0, 0),
('2550', -2, 0, 0, 0, 0),
('2550', -1, 0, 0, 0, 0),
('2550', 0, 0, 0, 0, 0),
('2550', 1, 0, 0, 0, 0),
('2550', 2, 0, 0, 0, 0),
('2550', 3, 0, 0, 0, 0),
('2550', 4, 0, 0, 0, 0),
('2550', 5, 0, 0, 0, 0),
('2550', 6, 0, 0, 0, 0),
('2550', 7, 0, 0, 0, 0),
('2550', 8, 0, 0, 0, 0),
('2550', 9, 0, 0, 0, 0),
('2550', 10, 0, 0, 0, 0),
('2550', 11, 0, 0, 0, 0),
('2550', 12, 0, 0, 0, 0),
('2550', 13, 0, 0, 0, 0),
('2550', 14, 0, 0, 0, 0),
('2550', 15, 0, 0, 0, 0),
('2550', 16, 0, 0, 0, 0),
('2550', 17, 0, 0, 0, 0),
('2550', 18, 0, 0, 0, 0),
('2550', 19, 0, 0, 0, 0),
('2550', 20, 0, 0, 0, 0),
('2550', 21, 0, 0, 0, 0),
('2550', 22, 0, 0, 0, 0),
('2550', 23, 0, 0, 0, 0),
('2550', 24, 0, 0, 0, 0),
('2560', -13, 0, 0, 0, 0),
('2560', -12, 0, 0, 0, 0),
('2560', -11, 0, 0, 0, 0),
('2560', -10, 0, 0, 0, 0),
('2560', -9, 0, 0, 0, 0),
('2560', -8, 0, 0, 0, 0),
('2560', -7, 0, 0, 0, 0),
('2560', -6, 0, 0, 0, 0),
('2560', -5, 0, 0, 0, 0),
('2560', -4, 0, 0, 0, 0),
('2560', -3, 0, 0, 0, 0),
('2560', -2, 0, 0, 0, 0),
('2560', -1, 0, 0, 0, 0),
('2560', 0, 0, 0, 0, 0),
('2560', 1, 0, 0, 0, 0),
('2560', 2, 0, 0, 0, 0),
('2560', 3, 0, 0, 0, 0),
('2560', 4, 0, 0, 0, 0),
('2560', 5, 0, 0, 0, 0),
('2560', 6, 0, 0, 0, 0),
('2560', 7, 0, 0, 0, 0),
('2560', 8, 0, 0, 0, 0),
('2560', 9, 0, 0, 0, 0),
('2560', 10, 0, 0, 0, 0),
('2560', 11, 0, 0, 0, 0),
('2560', 12, 0, 0, 0, 0),
('2560', 13, 0, 0, 0, 0),
('2560', 14, 0, 0, 0, 0),
('2560', 15, 0, 0, 0, 0),
('2560', 16, 0, 0, 0, 0),
('2560', 17, 0, 0, 0, 0),
('2560', 18, 0, 0, 0, 0),
('2560', 19, 0, 0, 0, 0),
('2560', 20, 0, 0, 0, 0),
('2560', 21, 0, 0, 0, 0),
('2560', 22, 0, 0, 0, 0),
('2560', 23, 0, 0, 0, 0),
('2560', 24, 0, 0, 0, 0),
('2600', -13, 0, 0, 0, 0),
('2600', -12, 0, 0, 0, 0),
('2600', -11, 0, 0, 0, 0),
('2600', -10, 0, 0, 0, 0),
('2600', -9, 0, 0, 0, 0),
('2600', -8, 0, 0, 0, 0),
('2600', -7, 0, 0, 0, 0),
('2600', -6, 0, 0, 0, 0),
('2600', -5, 0, 0, 0, 0),
('2600', -4, 0, 0, 0, 0),
('2600', -3, 0, 0, 0, 0),
('2600', -2, 0, 0, 0, 0),
('2600', -1, 0, 0, 0, 0),
('2600', 0, 0, 0, 0, 0),
('2600', 1, 0, 0, 0, 0),
('2600', 2, 0, 0, 0, 0),
('2600', 3, 0, 0, 0, 0),
('2600', 4, 0, 0, 0, 0),
('2600', 5, 0, 0, 0, 0),
('2600', 6, 0, 0, 0, 0),
('2600', 7, 0, 0, 0, 0),
('2600', 8, 0, 0, 0, 0),
('2600', 9, 0, 0, 0, 0),
('2600', 10, 0, 0, 0, 0),
('2600', 11, 0, 0, 0, 0),
('2600', 12, 0, 0, 0, 0),
('2600', 13, 0, 0, 0, 0),
('2600', 14, 0, 0, 0, 0),
('2600', 15, 0, 0, 0, 0),
('2600', 16, 0, 0, 0, 0),
('2600', 17, 0, 0, 0, 0),
('2600', 18, 0, 0, 0, 0),
('2600', 19, 0, 0, 0, 0),
('2600', 20, 0, 0, 0, 0),
('2600', 21, 0, 0, 0, 0),
('2600', 22, 0, 0, 0, 0),
('2600', 23, 0, 0, 0, 0),
('2600', 24, 0, 0, 0, 0),
('2700', -13, 0, 0, 0, 0),
('2700', -12, 0, 0, 0, 0),
('2700', -11, 0, 0, 0, 0),
('2700', -10, 0, 0, 0, 0),
('2700', -9, 0, 0, 0, 0),
('2700', -8, 0, 0, 0, 0),
('2700', -7, 0, 0, 0, 0),
('2700', -6, 0, 0, 0, 0),
('2700', -5, 0, 0, 0, 0),
('2700', -4, 0, 0, 0, 0),
('2700', -3, 0, 0, 0, 0),
('2700', -2, 0, 0, 0, 0),
('2700', -1, 0, 0, 0, 0),
('2700', 0, 0, 0, 0, 0),
('2700', 1, 0, 0, 0, 0),
('2700', 2, 0, 0, 0, 0),
('2700', 3, 0, 0, 0, 0),
('2700', 4, 0, 0, 0, 0),
('2700', 5, 0, 0, 0, 0),
('2700', 6, 0, 0, 0, 0),
('2700', 7, 0, 0, 0, 0),
('2700', 8, 0, 0, 0, 0),
('2700', 9, 0, 0, 0, 0),
('2700', 10, 0, 0, 0, 0),
('2700', 11, 0, 0, 0, 0),
('2700', 12, 0, 0, 0, 0),
('2700', 13, 0, 0, 0, 0),
('2700', 14, 0, 0, 0, 0),
('2700', 15, 0, 0, 0, 0),
('2700', 16, 0, 0, 0, 0),
('2700', 17, 0, 0, 0, 0),
('2700', 18, 0, 0, 0, 0),
('2700', 19, 0, 0, 0, 0),
('2700', 20, 0, 0, 0, 0),
('2700', 21, 0, 0, 0, 0),
('2700', 22, 0, 0, 0, 0),
('2700', 23, 0, 0, 0, 0),
('2700', 24, 0, 0, 0, 0),
('2720', -13, 0, 0, 0, 0),
('2720', -12, 0, 0, 0, 0),
('2720', -11, 0, 0, 0, 0),
('2720', -10, 0, 0, 0, 0),
('2720', -9, 0, 0, 0, 0),
('2720', -8, 0, 0, 0, 0),
('2720', -7, 0, 0, 0, 0),
('2720', -6, 0, 0, 0, 0),
('2720', -5, 0, 0, 0, 0),
('2720', -4, 0, 0, 0, 0),
('2720', -3, 0, 0, 0, 0),
('2720', -2, 0, 0, 0, 0),
('2720', -1, 0, 0, 0, 0),
('2720', 0, 0, 0, 0, 0),
('2720', 1, 0, 0, 0, 0),
('2720', 2, 0, 0, 0, 0),
('2720', 3, 0, 0, 0, 0),
('2720', 4, 0, 0, 0, 0),
('2720', 5, 0, 0, 0, 0),
('2720', 6, 0, 0, 0, 0),
('2720', 7, 0, 0, 0, 0),
('2720', 8, 0, 0, 0, 0),
('2720', 9, 0, 0, 0, 0),
('2720', 10, 0, 0, 0, 0),
('2720', 11, 0, 0, 0, 0),
('2720', 12, 0, 0, 0, 0),
('2720', 13, 0, 0, 0, 0),
('2720', 14, 0, 0, 0, 0),
('2720', 15, 0, 0, 0, 0),
('2720', 16, 0, 0, 0, 0),
('2720', 17, 0, 0, 0, 0),
('2720', 18, 0, 0, 0, 0),
('2720', 19, 0, 0, 0, 0),
('2720', 20, 0, 0, 0, 0),
('2720', 21, 0, 0, 0, 0),
('2720', 22, 0, 0, 0, 0),
('2720', 23, 0, 0, 0, 0),
('2720', 24, 0, 0, 0, 0),
('2740', -13, 0, 0, 0, 0),
('2740', -12, 0, 0, 0, 0),
('2740', -11, 0, 0, 0, 0),
('2740', -10, 0, 0, 0, 0),
('2740', -9, 0, 0, 0, 0),
('2740', -8, 0, 0, 0, 0),
('2740', -7, 0, 0, 0, 0),
('2740', -6, 0, 0, 0, 0),
('2740', -5, 0, 0, 0, 0),
('2740', -4, 0, 0, 0, 0),
('2740', -3, 0, 0, 0, 0),
('2740', -2, 0, 0, 0, 0),
('2740', -1, 0, 0, 0, 0),
('2740', 0, 0, 0, 0, 0),
('2740', 1, 0, 0, 0, 0),
('2740', 2, 0, 0, 0, 0),
('2740', 3, 0, 0, 0, 0),
('2740', 4, 0, 0, 0, 0),
('2740', 5, 0, 0, 0, 0),
('2740', 6, 0, 0, 0, 0),
('2740', 7, 0, 0, 0, 0),
('2740', 8, 0, 0, 0, 0),
('2740', 9, 0, 0, 0, 0),
('2740', 10, 0, 0, 0, 0),
('2740', 11, 0, 0, 0, 0),
('2740', 12, 0, 0, 0, 0),
('2740', 13, 0, 0, 0, 0),
('2740', 14, 0, 0, 0, 0),
('2740', 15, 0, 0, 0, 0),
('2740', 16, 0, 0, 0, 0),
('2740', 17, 0, 0, 0, 0),
('2740', 18, 0, 0, 0, 0),
('2740', 19, 0, 0, 0, 0),
('2740', 20, 0, 0, 0, 0),
('2740', 21, 0, 0, 0, 0),
('2740', 22, 0, 0, 0, 0),
('2740', 23, 0, 0, 0, 0),
('2740', 24, 0, 0, 0, 0),
('2760', -13, 0, 0, 0, 0),
('2760', -12, 0, 0, 0, 0),
('2760', -11, 0, 0, 0, 0),
('2760', -10, 0, 0, 0, 0),
('2760', -9, 0, 0, 0, 0),
('2760', -8, 0, 0, 0, 0),
('2760', -7, 0, 0, 0, 0),
('2760', -6, 0, 0, 0, 0),
('2760', -5, 0, 0, 0, 0),
('2760', -4, 0, 0, 0, 0),
('2760', -3, 0, 0, 0, 0),
('2760', -2, 0, 0, 0, 0),
('2760', -1, 0, 0, 0, 0),
('2760', 0, 0, 0, 0, 0),
('2760', 1, 0, 0, 0, 0),
('2760', 2, 0, 0, 0, 0),
('2760', 3, 0, 0, 0, 0),
('2760', 4, 0, 0, 0, 0),
('2760', 5, 0, 0, 0, 0),
('2760', 6, 0, 0, 0, 0),
('2760', 7, 0, 0, 0, 0),
('2760', 8, 0, 0, 0, 0),
('2760', 9, 0, 0, 0, 0),
('2760', 10, 0, 0, 0, 0),
('2760', 11, 0, 0, 0, 0),
('2760', 12, 0, 0, 0, 0),
('2760', 13, 0, 0, 0, 0),
('2760', 14, 0, 0, 0, 0),
('2760', 15, 0, 0, 0, 0),
('2760', 16, 0, 0, 0, 0),
('2760', 17, 0, 0, 0, 0),
('2760', 18, 0, 0, 0, 0),
('2760', 19, 0, 0, 0, 0),
('2760', 20, 0, 0, 0, 0),
('2760', 21, 0, 0, 0, 0),
('2760', 22, 0, 0, 0, 0),
('2760', 23, 0, 0, 0, 0),
('2760', 24, 0, 0, 0, 0),
('2800', -13, 0, 0, 0, 0),
('2800', -12, 0, 0, 0, 0),
('2800', -11, 0, 0, 0, 0),
('2800', -10, 0, 0, 0, 0),
('2800', -9, 0, 0, 0, 0),
('2800', -8, 0, 0, 0, 0),
('2800', -7, 0, 0, 0, 0),
('2800', -6, 0, 0, 0, 0),
('2800', -5, 0, 0, 0, 0),
('2800', -4, 0, 0, 0, 0),
('2800', -3, 0, 0, 0, 0),
('2800', -2, 0, 0, 0, 0),
('2800', -1, 0, 0, 0, 0),
('2800', 0, 0, 0, 0, 0),
('2800', 1, 0, 0, 0, 0),
('2800', 2, 0, 0, 0, 0),
('2800', 3, 0, 0, 0, 0),
('2800', 4, 0, 0, 0, 0),
('2800', 5, 0, 0, 0, 0),
('2800', 6, 0, 0, 0, 0),
('2800', 7, 0, 0, 0, 0),
('2800', 8, 0, 0, 0, 0),
('2800', 9, 0, 0, 0, 0),
('2800', 10, 0, 0, 0, 0),
('2800', 11, 0, 0, 0, 0),
('2800', 12, 0, 0, 0, 0),
('2800', 13, 0, 0, 0, 0),
('2800', 14, 0, 0, 0, 0),
('2800', 15, 0, 0, 0, 0),
('2800', 16, 0, 0, 0, 0),
('2800', 17, 0, 0, 0, 0),
('2800', 18, 0, 0, 0, 0),
('2800', 19, 0, 0, 0, 0),
('2800', 20, 0, 0, 0, 0),
('2800', 21, 0, 0, 0, 0),
('2800', 22, 0, 0, 0, 0),
('2800', 23, 0, 0, 0, 0),
('2800', 24, 0, 0, 0, 0),
('2900', -13, 0, 0, 0, 0),
('2900', -12, 0, 0, 0, 0),
('2900', -11, 0, 0, 0, 0),
('2900', -10, 0, 0, 0, 0),
('2900', -9, 0, 0, 0, 0),
('2900', -8, 0, 0, 0, 0),
('2900', -7, 0, 0, 0, 0),
('2900', -6, 0, 0, 0, 0),
('2900', -5, 0, 0, 0, 0),
('2900', -4, 0, 0, 0, 0),
('2900', -3, 0, 0, 0, 0),
('2900', -2, 0, 0, 0, 0),
('2900', -1, 0, 0, 0, 0),
('2900', 0, 0, 0, 0, 0),
('2900', 1, 0, 0, 0, 0),
('2900', 2, 0, 0, 0, 0),
('2900', 3, 0, 0, 0, 0),
('2900', 4, 0, 0, 0, 0),
('2900', 5, 0, 0, 0, 0),
('2900', 6, 0, 0, 0, 0),
('2900', 7, 0, 0, 0, 0),
('2900', 8, 0, 0, 0, 0),
('2900', 9, 0, 0, 0, 0),
('2900', 10, 0, 0, 0, 0),
('2900', 11, 0, 0, 0, 0),
('2900', 12, 0, 0, 0, 0),
('2900', 13, 0, 0, 0, 0),
('2900', 14, 0, 0, 0, 0),
('2900', 15, 0, 0, 0, 0),
('2900', 16, 0, 0, 0, 0),
('2900', 17, 0, 0, 0, 0),
('2900', 18, 0, 0, 0, 0),
('2900', 19, 0, 0, 0, 0),
('2900', 20, 0, 0, 0, 0),
('2900', 21, 0, 0, 0, 0),
('2900', 22, 0, 0, 0, 0),
('2900', 23, 0, 0, 0, 0),
('2900', 24, 0, 0, 0, 0),
('3100', -13, 0, 0, 0, 0),
('3100', -12, 0, 0, 0, 0),
('3100', -11, 0, 0, 0, 0),
('3100', -10, 0, 0, 0, 0),
('3100', -9, 0, 0, 0, 0),
('3100', -8, 0, 0, 0, 0),
('3100', -7, 0, 0, 0, 0),
('3100', -6, 0, 0, 0, 0),
('3100', -5, 0, 0, 0, 0),
('3100', -4, 0, 0, 0, 0),
('3100', -3, 0, 0, 0, 0),
('3100', -2, 0, 0, 0, 0),
('3100', -1, 0, 0, 0, 0),
('3100', 0, 0, 0, 0, 0),
('3100', 1, 0, 0, 0, 0),
('3100', 2, 0, 0, 0, 0),
('3100', 3, 0, 0, 0, 0),
('3100', 4, 0, 0, 0, 0),
('3100', 5, 0, 0, 0, 0),
('3100', 6, 0, 0, 0, 0),
('3100', 7, 0, 0, 0, 0),
('3100', 8, 0, 0, 0, 0),
('3100', 9, 0, 0, 0, 0),
('3100', 10, 0, 0, 0, 0),
('3100', 11, 0, 0, 0, 0),
('3100', 12, 0, 0, 0, 0),
('3100', 13, 0, 0, 0, 0),
('3100', 14, 0, 0, 0, 0),
('3100', 15, 0, 0, 0, 0),
('3100', 16, 0, 0, 0, 0),
('3100', 17, 0, 0, 0, 0),
('3100', 18, 0, 0, 0, 0),
('3100', 19, 0, 0, 0, 0),
('3100', 20, 0, 0, 0, 0),
('3100', 21, 0, 0, 0, 0),
('3100', 22, 0, 0, 0, 0),
('3100', 23, 0, 0, 0, 0),
('3100', 24, 0, 0, 0, 0),
('3200', -13, 0, 0, 0, 0),
('3200', -12, 0, 0, 0, 0),
('3200', -11, 0, 0, 0, 0),
('3200', -10, 0, 0, 0, 0),
('3200', -9, 0, 0, 0, 0),
('3200', -8, 0, 0, 0, 0),
('3200', -7, 0, 0, 0, 0),
('3200', -6, 0, 0, 0, 0),
('3200', -5, 0, 0, 0, 0),
('3200', -4, 0, 0, 0, 0),
('3200', -3, 0, 0, 0, 0),
('3200', -2, 0, 0, 0, 0),
('3200', -1, 0, 0, 0, 0),
('3200', 0, 0, 0, 0, 0),
('3200', 1, 0, 0, 0, 0),
('3200', 2, 0, 0, 0, 0),
('3200', 3, 0, 0, 0, 0),
('3200', 4, 0, 0, 0, 0),
('3200', 5, 0, 0, 0, 0),
('3200', 6, 0, 0, 0, 0),
('3200', 7, 0, 0, 0, 0),
('3200', 8, 0, 0, 0, 0),
('3200', 9, 0, 0, 0, 0),
('3200', 10, 0, 0, 0, 0),
('3200', 11, 0, 0, 0, 0),
('3200', 12, 0, 0, 0, 0),
('3200', 13, 0, 0, 0, 0),
('3200', 14, 0, 0, 0, 0),
('3200', 15, 0, 0, 0, 0),
('3200', 16, 0, 0, 0, 0),
('3200', 17, 0, 0, 0, 0),
('3200', 18, 0, 0, 0, 0),
('3200', 19, 0, 0, 0, 0),
('3200', 20, 0, 0, 0, 0),
('3200', 21, 0, 0, 0, 0),
('3200', 22, 0, 0, 0, 0),
('3200', 23, 0, 0, 0, 0),
('3200', 24, 0, 0, 0, 0),
('3300', -13, 0, 0, 0, 0),
('3300', -12, 0, 0, 0, 0),
('3300', -11, 0, 0, 0, 0),
('3300', -10, 0, 0, 0, 0),
('3300', -9, 0, 0, 0, 0),
('3300', -8, 0, 0, 0, 0),
('3300', -7, 0, 0, 0, 0),
('3300', -6, 0, 0, 0, 0),
('3300', -5, 0, 0, 0, 0),
('3300', -4, 0, 0, 0, 0),
('3300', -3, 0, 0, 0, 0),
('3300', -2, 0, 0, 0, 0),
('3300', -1, 0, 0, 0, 0),
('3300', 0, 0, 0, 0, 0),
('3300', 1, 0, 0, 0, 0),
('3300', 2, 0, 0, 0, 0),
('3300', 3, 0, 0, 0, 0),
('3300', 4, 0, 0, 0, 0),
('3300', 5, 0, 0, 0, 0),
('3300', 6, 0, 0, 0, 0),
('3300', 7, 0, 0, 0, 0),
('3300', 8, 0, 0, 0, 0),
('3300', 9, 0, 0, 0, 0),
('3300', 10, 0, 0, 0, 0),
('3300', 11, 0, 0, 0, 0),
('3300', 12, 0, 0, 0, 0),
('3300', 13, 0, 0, 0, 0),
('3300', 14, 0, 0, 0, 0),
('3300', 15, 0, 0, 0, 0),
('3300', 16, 0, 0, 0, 0),
('3300', 17, 0, 0, 0, 0),
('3300', 18, 0, 0, 0, 0),
('3300', 19, 0, 0, 0, 0),
('3300', 20, 0, 0, 0, 0),
('3300', 21, 0, 0, 0, 0),
('3300', 22, 0, 0, 0, 0),
('3300', 23, 0, 0, 0, 0),
('3300', 24, 0, 0, 0, 0),
('3400', -13, 0, 0, 0, 0),
('3400', -12, 0, 0, 0, 0),
('3400', -11, 0, 0, 0, 0),
('3400', -10, 0, 0, 0, 0),
('3400', -9, 0, 0, 0, 0),
('3400', -8, 0, 0, 0, 0),
('3400', -7, 0, 0, 0, 0),
('3400', -6, 0, 0, 0, 0),
('3400', -5, 0, 0, 0, 0),
('3400', -4, 0, 0, 0, 0),
('3400', -3, 0, 0, 0, 0),
('3400', -2, 0, 0, 0, 0),
('3400', -1, 0, 0, 0, 0),
('3400', 0, 0, 0, 0, 0),
('3400', 1, 0, 0, 0, 0),
('3400', 2, 0, 0, 0, 0),
('3400', 3, 0, 0, 0, 0),
('3400', 4, 0, 0, 0, 0),
('3400', 5, 0, 0, 0, 0),
('3400', 6, 0, 0, 0, 0),
('3400', 7, 0, 0, 0, 0),
('3400', 8, 0, 0, 0, 0),
('3400', 9, 0, 0, 0, 0),
('3400', 10, 0, 0, 0, 0),
('3400', 11, 0, 0, 0, 0),
('3400', 12, 0, 0, 0, 0),
('3400', 13, 0, 0, 0, 0),
('3400', 14, 0, 0, 0, 0),
('3400', 15, 0, 0, 0, 0),
('3400', 16, 0, 0, 0, 0),
('3400', 17, 0, 0, 0, 0),
('3400', 18, 0, 0, 0, 0),
('3400', 19, 0, 0, 0, 0),
('3400', 20, 0, 0, 0, 0),
('3400', 21, 0, 0, 0, 0),
('3400', 22, 0, 0, 0, 0),
('3400', 23, 0, 0, 0, 0),
('3400', 24, 0, 0, 0, 0),
('3500', -13, 0, 0, 0, 0),
('3500', -12, 0, 0, 0, 0),
('3500', -11, 0, 0, 0, 0),
('3500', -10, 0, 0, 0, 0),
('3500', -9, 0, 0, 0, 0),
('3500', -8, 0, 0, 0, 0),
('3500', -7, 0, 0, 0, 0),
('3500', -6, 0, 0, 0, 0),
('3500', -5, 0, 0, 0, 0),
('3500', -4, 0, 0, 0, 0),
('3500', -3, 0, 0, 0, 0),
('3500', -2, 0, 0, 0, 0),
('3500', -1, 0, 0, 0, 0),
('3500', 0, 0, 0, 0, 0),
('3500', 1, 0, 0, 0, 0),
('3500', 2, 0, 0, 0, 0),
('3500', 3, 0, 0, 0, 0),
('3500', 4, 0, 0, 0, 0),
('3500', 5, 0, 0, 0, 0),
('3500', 6, 0, 0, 0, 0),
('3500', 7, 0, 0, 0, 0),
('3500', 8, 0, 0, 0, 0),
('3500', 9, 0, 0, 0, 0),
('3500', 10, 0, 0, 0, 0),
('3500', 11, 0, 0, 0, 0),
('3500', 12, 0, 0, 0, 0),
('3500', 13, 0, 0, 0, 0),
('3500', 14, 0, 0, 0, 0),
('3500', 15, 0, 0, 0, 0),
('3500', 16, 0, 0, 0, 0),
('3500', 17, 0, 0, 0, 0),
('3500', 18, 0, 0, 0, 0),
('3500', 19, 0, 0, 0, 0),
('3500', 20, 0, 0, 0, 0),
('3500', 21, 0, 0, 0, 0),
('3500', 22, 0, 0, 0, 0),
('3500', 23, 0, 0, 0, 0),
('3500', 24, 0, 0, 0, 0),
('4100', -13, 0, 0, 0, 0),
('4100', -12, 0, 0, 0, 0),
('4100', -11, 0, 0, 0, 0),
('4100', -10, 0, 0, 0, 0),
('4100', -9, 0, 0, 0, 0),
('4100', -8, 0, 0, 0, 0),
('4100', -7, 0, 0, 0, 0),
('4100', -6, 0, 0, 0, 0),
('4100', -5, 0, 0, 0, 0),
('4100', -4, 0, 0, 0, 0),
('4100', -3, 0, 0, 0, 0),
('4100', -2, 0, 0, 0, 0),
('4100', -1, 0, 0, 0, 0),
('4100', 0, 0, 0, 0, 0),
('4100', 1, 0, 0, 0, 0),
('4100', 2, 0, 0, 0, 0),
('4100', 3, 0, 0, 0, 0),
('4100', 4, 0, 0, 0, 0),
('4100', 5, 0, 0, 0, 0),
('4100', 6, 0, 0, 0, 0),
('4100', 7, 0, 0, 0, 0),
('4100', 8, 0, 0, 0, 0),
('4100', 9, 0, 0, 0, 0),
('4100', 10, 0, 0, 0, 0),
('4100', 11, 0, 0, 0, 0),
('4100', 12, 0, 0, 0, 0),
('4100', 13, 0, 0, 0, 0),
('4100', 14, 0, 0, 0, 0),
('4100', 15, 0, 0, 0, 0),
('4100', 16, 0, 0, 0, 0),
('4100', 17, 0, 0, 0, 0),
('4100', 18, 0, 0, 0, 0),
('4100', 19, 0, 0, 0, 0),
('4100', 20, 0, 0, 0, 0),
('4100', 21, 0, 0, 0, 0),
('4100', 22, 0, 0, 0, 0),
('4100', 23, 0, 0, 0, 0),
('4100', 24, 0, 0, 0, 0),
('4200', -13, 0, 0, 0, 0),
('4200', -12, 0, 0, 0, 0),
('4200', -11, 0, 0, 0, 0),
('4200', -10, 0, 0, 0, 0),
('4200', -9, 0, 0, 0, 0),
('4200', -8, 0, 0, 0, 0),
('4200', -7, 0, 0, 0, 0),
('4200', -6, 0, 0, 0, 0),
('4200', -5, 0, 0, 0, 0),
('4200', -4, 0, 0, 0, 0),
('4200', -3, 0, 0, 0, 0),
('4200', -2, 0, 0, 0, 0),
('4200', -1, 0, 0, 0, 0),
('4200', 0, 0, 0, 0, 0),
('4200', 1, 0, 0, 0, 0),
('4200', 2, 0, 0, 0, 0),
('4200', 3, 0, 0, 0, 0),
('4200', 4, 0, 0, 0, 0),
('4200', 5, 0, 0, 0, 0),
('4200', 6, 0, 0, 0, 0),
('4200', 7, 0, 0, 0, 0),
('4200', 8, 0, 0, 0, 0),
('4200', 9, 0, 0, 0, 0),
('4200', 10, 0, 0, 0, 0),
('4200', 11, 0, 0, 0, 0),
('4200', 12, 0, 0, 0, 0),
('4200', 13, 0, 0, 0, 0),
('4200', 14, 0, 0, 0, 0),
('4200', 15, 0, 0, 0, 0),
('4200', 16, 0, 0, 0, 0),
('4200', 17, 0, 0, 0, 0),
('4200', 18, 0, 0, 0, 0),
('4200', 19, 0, 0, 0, 0),
('4200', 20, 0, 0, 0, 0),
('4200', 21, 0, 0, 0, 0),
('4200', 22, 0, 0, 0, 0),
('4200', 23, 0, 0, 0, 0),
('4200', 24, 0, 0, 0, 0),
('4500', -13, 0, 0, 0, 0),
('4500', -12, 0, 0, 0, 0),
('4500', -11, 0, 0, 0, 0),
('4500', -10, 0, 0, 0, 0),
('4500', -9, 0, 0, 0, 0),
('4500', -8, 0, 0, 0, 0),
('4500', -7, 0, 0, 0, 0),
('4500', -6, 0, 0, 0, 0),
('4500', -5, 0, 0, 0, 0),
('4500', -4, 0, 0, 0, 0),
('4500', -3, 0, 0, 0, 0),
('4500', -2, 0, 0, 0, 0),
('4500', -1, 0, 0, 0, 0),
('4500', 0, 0, 0, 0, 0),
('4500', 1, 0, 0, 0, 0),
('4500', 2, 0, 0, 0, 0),
('4500', 3, 0, 0, 0, 0),
('4500', 4, 0, 0, 0, 0),
('4500', 5, 0, 0, 0, 0),
('4500', 6, 0, 0, 0, 0),
('4500', 7, 0, 0, 0, 0),
('4500', 8, 0, 0, 0, 0),
('4500', 9, 0, 0, 0, 0),
('4500', 10, 0, 0, 0, 0),
('4500', 11, 0, 0, 0, 0),
('4500', 12, 0, 0, 0, 0),
('4500', 13, 0, 0, 0, 0),
('4500', 14, 0, 0, 0, 0),
('4500', 15, 0, 0, 0, 0),
('4500', 16, 0, 0, 0, 0),
('4500', 17, 0, 0, 0, 0),
('4500', 18, 0, 0, 0, 0),
('4500', 19, 0, 0, 0, 0),
('4500', 20, 0, 0, 0, 0),
('4500', 21, 0, 0, 0, 0),
('4500', 22, 0, 0, 0, 0),
('4500', 23, 0, 0, 0, 0),
('4500', 24, 0, 0, 0, 0),
('4600', -13, 0, 0, 0, 0),
('4600', -12, 0, 0, 0, 0),
('4600', -11, 0, 0, 0, 0),
('4600', -10, 0, 0, 0, 0),
('4600', -9, 0, 0, 0, 0),
('4600', -8, 0, 0, 0, 0),
('4600', -7, 0, 0, 0, 0),
('4600', -6, 0, 0, 0, 0),
('4600', -5, 0, 0, 0, 0),
('4600', -4, 0, 0, 0, 0),
('4600', -3, 0, 0, 0, 0),
('4600', -2, 0, 0, 0, 0),
('4600', -1, 0, 0, 0, 0),
('4600', 0, 0, 0, 0, 0),
('4600', 1, 0, 0, 0, 0),
('4600', 2, 0, 0, 0, 0),
('4600', 3, 0, 0, 0, 0),
('4600', 4, 0, 0, 0, 0),
('4600', 5, 0, 0, 0, 0),
('4600', 6, 0, 0, 0, 0),
('4600', 7, 0, 0, 0, 0),
('4600', 8, 0, 0, 0, 0),
('4600', 9, 0, 0, 0, 0),
('4600', 10, 0, 0, 0, 0),
('4600', 11, 0, 0, 0, 0),
('4600', 12, 0, 0, 0, 0),
('4600', 13, 0, 0, 0, 0),
('4600', 14, 0, 0, 0, 0),
('4600', 15, 0, 0, 0, 0),
('4600', 16, 0, 0, 0, 0),
('4600', 17, 0, 0, 0, 0),
('4600', 18, 0, 0, 0, 0),
('4600', 19, 0, 0, 0, 0),
('4600', 20, 0, 0, 0, 0),
('4600', 21, 0, 0, 0, 0),
('4600', 22, 0, 0, 0, 0),
('4600', 23, 0, 0, 0, 0),
('4600', 24, 0, 0, 0, 0),
('4700', -13, 0, 0, 0, 0),
('4700', -12, 0, 0, 0, 0),
('4700', -11, 0, 0, 0, 0),
('4700', -10, 0, 0, 0, 0),
('4700', -9, 0, 0, 0, 0),
('4700', -8, 0, 0, 0, 0),
('4700', -7, 0, 0, 0, 0),
('4700', -6, 0, 0, 0, 0),
('4700', -5, 0, 0, 0, 0),
('4700', -4, 0, 0, 0, 0),
('4700', -3, 0, 0, 0, 0),
('4700', -2, 0, 0, 0, 0),
('4700', -1, 0, 0, 0, 0),
('4700', 0, 0, 0, 0, 0),
('4700', 1, 0, 0, 0, 0),
('4700', 2, 0, 0, 0, 0),
('4700', 3, 0, 0, 0, 0),
('4700', 4, 0, 0, 0, 0),
('4700', 5, 0, 0, 0, 0),
('4700', 6, 0, 0, 0, 0),
('4700', 7, 0, 0, 0, 0),
('4700', 8, 0, 0, 0, 0),
('4700', 9, 0, 0, 0, 0),
('4700', 10, 0, 0, 0, 0),
('4700', 11, 0, 0, 0, 0),
('4700', 12, 0, 0, 0, 0),
('4700', 13, 0, 0, 0, 0),
('4700', 14, 0, 0, 0, 0),
('4700', 15, 0, 0, 0, 0),
('4700', 16, 0, 0, 0, 0),
('4700', 17, 0, 0, 0, 0),
('4700', 18, 0, 0, 0, 0),
('4700', 19, 0, 0, 0, 0),
('4700', 20, 0, 0, 0, 0),
('4700', 21, 0, 0, 0, 0),
('4700', 22, 0, 0, 0, 0),
('4700', 23, 0, 0, 0, 0),
('4700', 24, 0, 0, 0, 0),
('4800', -13, 0, 0, 0, 0),
('4800', -12, 0, 0, 0, 0),
('4800', -11, 0, 0, 0, 0),
('4800', -10, 0, 0, 0, 0),
('4800', -9, 0, 0, 0, 0),
('4800', -8, 0, 0, 0, 0),
('4800', -7, 0, 0, 0, 0),
('4800', -6, 0, 0, 0, 0),
('4800', -5, 0, 0, 0, 0),
('4800', -4, 0, 0, 0, 0),
('4800', -3, 0, 0, 0, 0),
('4800', -2, 0, 0, 0, 0),
('4800', -1, 0, 0, 0, 0),
('4800', 0, 0, 0, 0, 0),
('4800', 1, 0, 0, 0, 0),
('4800', 2, 0, 0, 0, 0),
('4800', 3, 0, 0, 0, 0),
('4800', 4, 0, 0, 0, 0),
('4800', 5, 0, 0, 0, 0),
('4800', 6, 0, 0, 0, 0),
('4800', 7, 0, 0, 0, 0),
('4800', 8, 0, 0, 0, 0),
('4800', 9, 0, 0, 0, 0),
('4800', 10, 0, 0, 0, 0),
('4800', 11, 0, 0, 0, 0),
('4800', 12, 0, 0, 0, 0),
('4800', 13, 0, 0, 0, 0),
('4800', 14, 0, 0, 0, 0),
('4800', 15, 0, 0, 0, 0),
('4800', 16, 0, 0, 0, 0),
('4800', 17, 0, 0, 0, 0),
('4800', 18, 0, 0, 0, 0),
('4800', 19, 0, 0, 0, 0),
('4800', 20, 0, 0, 0, 0),
('4800', 21, 0, 0, 0, 0),
('4800', 22, 0, 0, 0, 0),
('4800', 23, 0, 0, 0, 0),
('4800', 24, 0, 0, 0, 0),
('4900', -13, 0, 0, 0, 0),
('4900', -12, 0, 0, 0, 0),
('4900', -11, 0, 0, 0, 0),
('4900', -10, 0, 0, 0, 0),
('4900', -9, 0, 0, 0, 0),
('4900', -8, 0, 0, 0, 0),
('4900', -7, 0, 0, 0, 0),
('4900', -6, 0, 0, 0, 0),
('4900', -5, 0, 0, 0, 0),
('4900', -4, 0, 0, 0, 0),
('4900', -3, 0, 0, 0, 0),
('4900', -2, 0, 0, 0, 0),
('4900', -1, 0, 0, 0, 0),
('4900', 0, 0, 0, 0, 0),
('4900', 1, 0, 0, 0, 0),
('4900', 2, 0, 0, 0, 0),
('4900', 3, 0, 0, 0, 0),
('4900', 4, 0, 0, 0, 0),
('4900', 5, 0, 0, 0, 0),
('4900', 6, 0, 0, 0, 0),
('4900', 7, 0, 0, 0, 0),
('4900', 8, 0, 0, 0, 0),
('4900', 9, 0, 0, 0, 0),
('4900', 10, 0, 0, 0, 0),
('4900', 11, 0, 0, 0, 0),
('4900', 12, 0, 0, 0, 0),
('4900', 13, 0, 0, 0, 0),
('4900', 14, 0, 0, 0, 0),
('4900', 15, 0, 0, 0, 0),
('4900', 16, 0, 0, 0, 0),
('4900', 17, 0, 0, 0, 0),
('4900', 18, 0, 0, 0, 0),
('4900', 19, 0, 0, 0, 0),
('4900', 20, 0, 0, 0, 0),
('4900', 21, 0, 0, 0, 0),
('4900', 22, 0, 0, 0, 0),
('4900', 23, 0, 0, 0, 0),
('4900', 24, 0, 0, 0, 0),
('5000', -13, 0, 0, 0, 0),
('5000', -12, 0, 0, 0, 0),
('5000', -11, 0, 0, 0, 0),
('5000', -10, 0, 0, 0, 0),
('5000', -9, 0, 0, 0, 0),
('5000', -8, 0, 0, 0, 0),
('5000', -7, 0, 0, 0, 0),
('5000', -6, 0, 0, 0, 0),
('5000', -5, 0, 0, 0, 0),
('5000', -4, 0, 0, 0, 0),
('5000', -3, 0, 0, 0, 0),
('5000', -2, 0, 0, 0, 0),
('5000', -1, 0, 0, 0, 0),
('5000', 0, 0, 0, 0, 0),
('5000', 1, 0, 0, 0, 0),
('5000', 2, 0, 0, 0, 0),
('5000', 3, 0, 0, 0, 0),
('5000', 4, 0, 0, 0, 0),
('5000', 5, 0, 0, 0, 0),
('5000', 6, 0, 0, 0, 0),
('5000', 7, 0, 0, 0, 0),
('5000', 8, 0, 0, 0, 0),
('5000', 9, 0, 0, 0, 0),
('5000', 10, 0, 0, 0, 0),
('5000', 11, 0, 0, 0, 0),
('5000', 12, 0, 0, 0, 0),
('5000', 13, 0, 0, 0, 0),
('5000', 14, 0, 0, 0, 0),
('5000', 15, 0, 0, 0, 0),
('5000', 16, 0, 0, 0, 0),
('5000', 17, 0, 0, 0, 0),
('5000', 18, 0, 0, 0, 0),
('5000', 19, 0, 0, 0, 0),
('5000', 20, 0, 0, 0, 0),
('5000', 21, 0, 0, 0, 0),
('5000', 22, 0, 0, 0, 0),
('5000', 23, 0, 0, 0, 0),
('5000', 24, 0, 0, 0, 0),
('5100', -13, 0, 0, 0, 0),
('5100', -12, 0, 0, 0, 0),
('5100', -11, 0, 0, 0, 0),
('5100', -10, 0, 0, 0, 0),
('5100', -9, 0, 0, 0, 0),
('5100', -8, 0, 0, 0, 0),
('5100', -7, 0, 0, 0, 0),
('5100', -6, 0, 0, 0, 0),
('5100', -5, 0, 0, 0, 0),
('5100', -4, 0, 0, 0, 0),
('5100', -3, 0, 0, 0, 0),
('5100', -2, 0, 0, 0, 0),
('5100', -1, 0, 0, 0, 0),
('5100', 0, 0, 0, 0, 0),
('5100', 1, 0, 0, 0, 0),
('5100', 2, 0, 0, 0, 0),
('5100', 3, 0, 0, 0, 0),
('5100', 4, 0, 0, 0, 0),
('5100', 5, 0, 0, 0, 0),
('5100', 6, 0, 0, 0, 0),
('5100', 7, 0, 0, 0, 0),
('5100', 8, 0, 0, 0, 0),
('5100', 9, 0, 0, 0, 0),
('5100', 10, 0, 0, 0, 0),
('5100', 11, 0, 0, 0, 0),
('5100', 12, 0, 0, 0, 0),
('5100', 13, 0, 0, 0, 0),
('5100', 14, 0, 0, 0, 0),
('5100', 15, 0, 0, 0, 0),
('5100', 16, 0, 0, 0, 0),
('5100', 17, 0, 0, 0, 0),
('5100', 18, 0, 0, 0, 0),
('5100', 19, 0, 0, 0, 0),
('5100', 20, 0, 0, 0, 0),
('5100', 21, 0, 0, 0, 0),
('5100', 22, 0, 0, 0, 0),
('5100', 23, 0, 0, 0, 0),
('5100', 24, 0, 0, 0, 0),
('5200', -13, 0, 0, 0, 0),
('5200', -12, 0, 0, 0, 0),
('5200', -11, 0, 0, 0, 0),
('5200', -10, 0, 0, 0, 0),
('5200', -9, 0, 0, 0, 0),
('5200', -8, 0, 0, 0, 0),
('5200', -7, 0, 0, 0, 0),
('5200', -6, 0, 0, 0, 0),
('5200', -5, 0, 0, 0, 0),
('5200', -4, 0, 0, 0, 0),
('5200', -3, 0, 0, 0, 0),
('5200', -2, 0, 0, 0, 0),
('5200', -1, 0, 0, 0, 0),
('5200', 0, 0, 0, 0, 0),
('5200', 1, 0, 0, 0, 0),
('5200', 2, 0, 0, 0, 0),
('5200', 3, 0, 0, 0, 0),
('5200', 4, 0, 0, 0, 0),
('5200', 5, 0, 0, 0, 0),
('5200', 6, 0, 0, 0, 0),
('5200', 7, 0, 0, 0, 0),
('5200', 8, 0, 0, 0, 0),
('5200', 9, 0, 0, 0, 0),
('5200', 10, 0, 0, 0, 0),
('5200', 11, 0, 0, 0, 0),
('5200', 12, 0, 0, 0, 0),
('5200', 13, 0, 0, 0, 0),
('5200', 14, 0, 0, 0, 0),
('5200', 15, 0, 0, 0, 0),
('5200', 16, 0, 0, 0, 0),
('5200', 17, 0, 0, 0, 0),
('5200', 18, 0, 0, 0, 0),
('5200', 19, 0, 0, 0, 0),
('5200', 20, 0, 0, 0, 0),
('5200', 21, 0, 0, 0, 0),
('5200', 22, 0, 0, 0, 0),
('5200', 23, 0, 0, 0, 0),
('5200', 24, 0, 0, 0, 0),
('5500', -13, 0, 0, 0, 0),
('5500', -12, 0, 0, 0, 0),
('5500', -11, 0, 0, 0, 0),
('5500', -10, 0, 0, 0, 0),
('5500', -9, 0, 0, 0, 0),
('5500', -8, 0, 0, 0, 0),
('5500', -7, 0, 0, 0, 0),
('5500', -6, 0, 0, 0, 0),
('5500', -5, 0, 0, 0, 0),
('5500', -4, 0, 0, 0, 0),
('5500', -3, 0, 0, 0, 0),
('5500', -2, 0, 0, 0, 0),
('5500', -1, 0, 0, 0, 0),
('5500', 0, 0, 0, 0, 0),
('5500', 1, 0, 0, 0, 0),
('5500', 2, 0, 0, 0, 0),
('5500', 3, 0, 0, 0, 0),
('5500', 4, 0, 0, 0, 0),
('5500', 5, 0, 0, 0, 0),
('5500', 6, 0, 0, 0, 0),
('5500', 7, 0, 0, 0, 0),
('5500', 8, 0, 0, 0, 0),
('5500', 9, 0, 0, 0, 0),
('5500', 10, 0, 0, 0, 0),
('5500', 11, 0, 0, 0, 0),
('5500', 12, 0, 0, 0, 0),
('5500', 13, 0, 0, 0, 0),
('5500', 14, 0, 0, 0, 0),
('5500', 15, 0, 0, 0, 0),
('5500', 16, 0, 0, 0, 0),
('5500', 17, 0, 0, 0, 0),
('5500', 18, 0, 0, 0, 0),
('5500', 19, 0, 0, 0, 0),
('5500', 20, 0, 0, 0, 0),
('5500', 21, 0, 0, 0, 0),
('5500', 22, 0, 0, 0, 0),
('5500', 23, 0, 0, 0, 0),
('5500', 24, 0, 0, 0, 0),
('5600', -13, 0, 0, 0, 0),
('5600', -12, 0, 0, 0, 0),
('5600', -11, 0, 0, 0, 0),
('5600', -10, 0, 0, 0, 0),
('5600', -9, 0, 0, 0, 0),
('5600', -8, 0, 0, 0, 0),
('5600', -7, 0, 0, 0, 0),
('5600', -6, 0, 0, 0, 0),
('5600', -5, 0, 0, 0, 0),
('5600', -4, 0, 0, 0, 0),
('5600', -3, 0, 0, 0, 0),
('5600', -2, 0, 0, 0, 0),
('5600', -1, 0, 0, 0, 0),
('5600', 0, 0, 0, 0, 0),
('5600', 1, 0, 0, 0, 0),
('5600', 2, 0, 0, 0, 0),
('5600', 3, 0, 0, 0, 0),
('5600', 4, 0, 0, 0, 0),
('5600', 5, 0, 0, 0, 0),
('5600', 6, 0, 0, 0, 0),
('5600', 7, 0, 0, 0, 0),
('5600', 8, 0, 0, 0, 0),
('5600', 9, 0, 0, 0, 0),
('5600', 10, 0, 0, 0, 0),
('5600', 11, 0, 0, 0, 0),
('5600', 12, 0, 0, 0, 0),
('5600', 13, 0, 0, 0, 0),
('5600', 14, 0, 0, 0, 0),
('5600', 15, 0, 0, 0, 0),
('5600', 16, 0, 0, 0, 0),
('5600', 17, 0, 0, 0, 0),
('5600', 18, 0, 0, 0, 0),
('5600', 19, 0, 0, 0, 0),
('5600', 20, 0, 0, 0, 0),
('5600', 21, 0, 0, 0, 0),
('5600', 22, 0, 0, 0, 0),
('5600', 23, 0, 0, 0, 0),
('5600', 24, 0, 0, 0, 0),
('5700', -13, 0, 0, 0, 0),
('5700', -12, 0, 0, 0, 0),
('5700', -11, 0, 0, 0, 0),
('5700', -10, 0, 0, 0, 0),
('5700', -9, 0, 0, 0, 0),
('5700', -8, 0, 0, 0, 0),
('5700', -7, 0, 0, 0, 0),
('5700', -6, 0, 0, 0, 0),
('5700', -5, 0, 0, 0, 0),
('5700', -4, 0, 0, 0, 0),
('5700', -3, 0, 0, 0, 0),
('5700', -2, 0, 0, 0, 0),
('5700', -1, 0, 0, 0, 0),
('5700', 0, 0, 0, 0, 0),
('5700', 1, 0, 0, 0, 0),
('5700', 2, 0, 0, 0, 0),
('5700', 3, 0, 0, 0, 0),
('5700', 4, 0, 0, 0, 0),
('5700', 5, 0, 0, 0, 0),
('5700', 6, 0, 0, 0, 0),
('5700', 7, 0, 0, 0, 0),
('5700', 8, 0, 0, 0, 0),
('5700', 9, 0, 0, 0, 0),
('5700', 10, 0, 0, 0, 0),
('5700', 11, 0, 0, 0, 0),
('5700', 12, 0, 0, 0, 0),
('5700', 13, 0, 0, 0, 0),
('5700', 14, 0, 0, 0, 0),
('5700', 15, 0, 0, 0, 0),
('5700', 16, 0, 0, 0, 0),
('5700', 17, 0, 0, 0, 0),
('5700', 18, 0, 0, 0, 0),
('5700', 19, 0, 0, 0, 0),
('5700', 20, 0, 0, 0, 0),
('5700', 21, 0, 0, 0, 0),
('5700', 22, 0, 0, 0, 0),
('5700', 23, 0, 0, 0, 0),
('5700', 24, 0, 0, 0, 0),
('5800', -13, 0, 0, 0, 0),
('5800', -12, 0, 0, 0, 0),
('5800', -11, 0, 0, 0, 0),
('5800', -10, 0, 0, 0, 0),
('5800', -9, 0, 0, 0, 0),
('5800', -8, 0, 0, 0, 0),
('5800', -7, 0, 0, 0, 0),
('5800', -6, 0, 0, 0, 0),
('5800', -5, 0, 0, 0, 0),
('5800', -4, 0, 0, 0, 0),
('5800', -3, 0, 0, 0, 0),
('5800', -2, 0, 0, 0, 0),
('5800', -1, 0, 0, 0, 0),
('5800', 0, 0, 0, 0, 0),
('5800', 1, 0, 0, 0, 0),
('5800', 2, 0, 0, 0, 0),
('5800', 3, 0, 0, 0, 0),
('5800', 4, 0, 0, 0, 0),
('5800', 5, 0, 0, 0, 0),
('5800', 6, 0, 0, 0, 0),
('5800', 7, 0, 0, 0, 0),
('5800', 8, 0, 0, 0, 0),
('5800', 9, 0, 0, 0, 0),
('5800', 10, 0, 0, 0, 0),
('5800', 11, 0, 0, 0, 0),
('5800', 12, 0, 0, 0, 0),
('5800', 13, 0, 0, 0, 0),
('5800', 14, 0, 0, 0, 0),
('5800', 15, 0, 0, 0, 0),
('5800', 16, 0, 0, 0, 0),
('5800', 17, 0, 0, 0, 0),
('5800', 18, 0, 0, 0, 0),
('5800', 19, 0, 0, 0, 0),
('5800', 20, 0, 0, 0, 0),
('5800', 21, 0, 0, 0, 0),
('5800', 22, 0, 0, 0, 0),
('5800', 23, 0, 0, 0, 0),
('5800', 24, 0, 0, 0, 0),
('5900', -13, 0, 0, 0, 0),
('5900', -12, 0, 0, 0, 0),
('5900', -11, 0, 0, 0, 0),
('5900', -10, 0, 0, 0, 0),
('5900', -9, 0, 0, 0, 0),
('5900', -8, 0, 0, 0, 0),
('5900', -7, 0, 0, 0, 0),
('5900', -6, 0, 0, 0, 0),
('5900', -5, 0, 0, 0, 0),
('5900', -4, 0, 0, 0, 0),
('5900', -3, 0, 0, 0, 0),
('5900', -2, 0, 0, 0, 0),
('5900', -1, 0, 0, 0, 0),
('5900', 0, 0, 0, 0, 0),
('5900', 1, 0, 0, 0, 0),
('5900', 2, 0, 0, 0, 0),
('5900', 3, 0, 0, 0, 0),
('5900', 4, 0, 0, 0, 0),
('5900', 5, 0, 0, 0, 0),
('5900', 6, 0, 0, 0, 0),
('5900', 7, 0, 0, 0, 0),
('5900', 8, 0, 0, 0, 0),
('5900', 9, 0, 0, 0, 0),
('5900', 10, 0, 0, 0, 0),
('5900', 11, 0, 0, 0, 0),
('5900', 12, 0, 0, 0, 0),
('5900', 13, 0, 0, 0, 0),
('5900', 14, 0, 0, 0, 0),
('5900', 15, 0, 0, 0, 0),
('5900', 16, 0, 0, 0, 0),
('5900', 17, 0, 0, 0, 0),
('5900', 18, 0, 0, 0, 0),
('5900', 19, 0, 0, 0, 0),
('5900', 20, 0, 0, 0, 0),
('5900', 21, 0, 0, 0, 0),
('5900', 22, 0, 0, 0, 0),
('5900', 23, 0, 0, 0, 0),
('5900', 24, 0, 0, 0, 0),
('6100', -13, 0, 0, 0, 0),
('6100', -12, 0, 0, 0, 0),
('6100', -11, 0, 0, 0, 0),
('6100', -10, 0, 0, 0, 0),
('6100', -9, 0, 0, 0, 0),
('6100', -8, 0, 0, 0, 0),
('6100', -7, 0, 0, 0, 0),
('6100', -6, 0, 0, 0, 0),
('6100', -5, 0, 0, 0, 0),
('6100', -4, 0, 0, 0, 0),
('6100', -3, 0, 0, 0, 0),
('6100', -2, 0, 0, 0, 0),
('6100', -1, 0, 0, 0, 0),
('6100', 0, 0, 0, 0, 0),
('6100', 1, 0, 0, 0, 0),
('6100', 2, 0, 0, 0, 0),
('6100', 3, 0, 0, 0, 0),
('6100', 4, 0, 0, 0, 0),
('6100', 5, 0, 0, 0, 0),
('6100', 6, 0, 0, 0, 0),
('6100', 7, 0, 0, 0, 0),
('6100', 8, 0, 0, 0, 0),
('6100', 9, 0, 0, 0, 0),
('6100', 10, 0, 0, 0, 0),
('6100', 11, 0, 0, 0, 0),
('6100', 12, 0, 0, 0, 0),
('6100', 13, 0, 0, 0, 0),
('6100', 14, 0, 0, 0, 0),
('6100', 15, 0, 0, 0, 0),
('6100', 16, 0, 0, 0, 0),
('6100', 17, 0, 0, 0, 0),
('6100', 18, 0, 0, 0, 0),
('6100', 19, 0, 0, 0, 0),
('6100', 20, 0, 0, 0, 0),
('6100', 21, 0, 0, 0, 0),
('6100', 22, 0, 0, 0, 0),
('6100', 23, 0, 0, 0, 0),
('6100', 24, 0, 0, 0, 0),
('6150', -13, 0, 0, 0, 0),
('6150', -12, 0, 0, 0, 0),
('6150', -11, 0, 0, 0, 0),
('6150', -10, 0, 0, 0, 0),
('6150', -9, 0, 0, 0, 0),
('6150', -8, 0, 0, 0, 0),
('6150', -7, 0, 0, 0, 0),
('6150', -6, 0, 0, 0, 0),
('6150', -5, 0, 0, 0, 0),
('6150', -4, 0, 0, 0, 0),
('6150', -3, 0, 0, 0, 0),
('6150', -2, 0, 0, 0, 0),
('6150', -1, 0, 0, 0, 0),
('6150', 0, 0, 0, 0, 0),
('6150', 1, 0, 0, 0, 0),
('6150', 2, 0, 0, 0, 0),
('6150', 3, 0, 0, 0, 0),
('6150', 4, 0, 0, 0, 0),
('6150', 5, 0, 0, 0, 0),
('6150', 6, 0, 0, 0, 0),
('6150', 7, 0, 0, 0, 0),
('6150', 8, 0, 0, 0, 0),
('6150', 9, 0, 0, 0, 0),
('6150', 10, 0, 0, 0, 0),
('6150', 11, 0, 0, 0, 0),
('6150', 12, 0, 0, 0, 0),
('6150', 13, 0, 0, 0, 0),
('6150', 14, 0, 0, 0, 0),
('6150', 15, 0, 0, 0, 0),
('6150', 16, 0, 0, 0, 0),
('6150', 17, 0, 0, 0, 0),
('6150', 18, 0, 0, 0, 0),
('6150', 19, 0, 0, 0, 0),
('6150', 20, 0, 0, 0, 0),
('6150', 21, 0, 0, 0, 0),
('6150', 22, 0, 0, 0, 0),
('6150', 23, 0, 0, 0, 0),
('6150', 24, 0, 0, 0, 0),
('6200', -13, 0, 0, 0, 0),
('6200', -12, 0, 0, 0, 0),
('6200', -11, 0, 0, 0, 0),
('6200', -10, 0, 0, 0, 0),
('6200', -9, 0, 0, 0, 0),
('6200', -8, 0, 0, 0, 0),
('6200', -7, 0, 0, 0, 0),
('6200', -6, 0, 0, 0, 0),
('6200', -5, 0, 0, 0, 0),
('6200', -4, 0, 0, 0, 0),
('6200', -3, 0, 0, 0, 0),
('6200', -2, 0, 0, 0, 0),
('6200', -1, 0, 0, 0, 0),
('6200', 0, 0, 0, 0, 0),
('6200', 1, 0, 0, 0, 0),
('6200', 2, 0, 0, 0, 0),
('6200', 3, 0, 0, 0, 0),
('6200', 4, 0, 0, 0, 0),
('6200', 5, 0, 0, 0, 0),
('6200', 6, 0, 0, 0, 0),
('6200', 7, 0, 0, 0, 0),
('6200', 8, 0, 0, 0, 0),
('6200', 9, 0, 0, 0, 0),
('6200', 10, 0, 0, 0, 0),
('6200', 11, 0, 0, 0, 0),
('6200', 12, 0, 0, 0, 0),
('6200', 13, 0, 0, 0, 0),
('6200', 14, 0, 0, 0, 0),
('6200', 15, 0, 0, 0, 0),
('6200', 16, 0, 0, 0, 0),
('6200', 17, 0, 0, 0, 0),
('6200', 18, 0, 0, 0, 0),
('6200', 19, 0, 0, 0, 0),
('6200', 20, 0, 0, 0, 0),
('6200', 21, 0, 0, 0, 0),
('6200', 22, 0, 0, 0, 0),
('6200', 23, 0, 0, 0, 0),
('6200', 24, 0, 0, 0, 0),
('6250', -13, 0, 0, 0, 0),
('6250', -12, 0, 0, 0, 0),
('6250', -11, 0, 0, 0, 0),
('6250', -10, 0, 0, 0, 0),
('6250', -9, 0, 0, 0, 0),
('6250', -8, 0, 0, 0, 0),
('6250', -7, 0, 0, 0, 0),
('6250', -6, 0, 0, 0, 0),
('6250', -5, 0, 0, 0, 0),
('6250', -4, 0, 0, 0, 0),
('6250', -3, 0, 0, 0, 0),
('6250', -2, 0, 0, 0, 0),
('6250', -1, 0, 0, 0, 0),
('6250', 0, 0, 0, 0, 0),
('6250', 1, 0, 0, 0, 0),
('6250', 2, 0, 0, 0, 0),
('6250', 3, 0, 0, 0, 0),
('6250', 4, 0, 0, 0, 0),
('6250', 5, 0, 0, 0, 0),
('6250', 6, 0, 0, 0, 0),
('6250', 7, 0, 0, 0, 0),
('6250', 8, 0, 0, 0, 0),
('6250', 9, 0, 0, 0, 0),
('6250', 10, 0, 0, 0, 0),
('6250', 11, 0, 0, 0, 0),
('6250', 12, 0, 0, 0, 0),
('6250', 13, 0, 0, 0, 0),
('6250', 14, 0, 0, 0, 0),
('6250', 15, 0, 0, 0, 0),
('6250', 16, 0, 0, 0, 0),
('6250', 17, 0, 0, 0, 0),
('6250', 18, 0, 0, 0, 0),
('6250', 19, 0, 0, 0, 0),
('6250', 20, 0, 0, 0, 0),
('6250', 21, 0, 0, 0, 0),
('6250', 22, 0, 0, 0, 0),
('6250', 23, 0, 0, 0, 0),
('6250', 24, 0, 0, 0, 0),
('6300', -13, 0, 0, 0, 0),
('6300', -12, 0, 0, 0, 0),
('6300', -11, 0, 0, 0, 0),
('6300', -10, 0, 0, 0, 0),
('6300', -9, 0, 0, 0, 0),
('6300', -8, 0, 0, 0, 0),
('6300', -7, 0, 0, 0, 0),
('6300', -6, 0, 0, 0, 0),
('6300', -5, 0, 0, 0, 0),
('6300', -4, 0, 0, 0, 0),
('6300', -3, 0, 0, 0, 0),
('6300', -2, 0, 0, 0, 0),
('6300', -1, 0, 0, 0, 0),
('6300', 0, 0, 0, 0, 0),
('6300', 1, 0, 0, 0, 0),
('6300', 2, 0, 0, 0, 0),
('6300', 3, 0, 0, 0, 0),
('6300', 4, 0, 0, 0, 0),
('6300', 5, 0, 0, 0, 0),
('6300', 6, 0, 0, 0, 0),
('6300', 7, 0, 0, 0, 0),
('6300', 8, 0, 0, 0, 0),
('6300', 9, 0, 0, 0, 0),
('6300', 10, 0, 0, 0, 0),
('6300', 11, 0, 0, 0, 0),
('6300', 12, 0, 0, 0, 0),
('6300', 13, 0, 0, 0, 0),
('6300', 14, 0, 0, 0, 0),
('6300', 15, 0, 0, 0, 0),
('6300', 16, 0, 0, 0, 0),
('6300', 17, 0, 0, 0, 0),
('6300', 18, 0, 0, 0, 0),
('6300', 19, 0, 0, 0, 0),
('6300', 20, 0, 0, 0, 0),
('6300', 21, 0, 0, 0, 0),
('6300', 22, 0, 0, 0, 0),
('6300', 23, 0, 0, 0, 0),
('6300', 24, 0, 0, 0, 0),
('6400', -13, 0, 0, 0, 0),
('6400', -12, 0, 0, 0, 0),
('6400', -11, 0, 0, 0, 0),
('6400', -10, 0, 0, 0, 0),
('6400', -9, 0, 0, 0, 0),
('6400', -8, 0, 0, 0, 0),
('6400', -7, 0, 0, 0, 0),
('6400', -6, 0, 0, 0, 0),
('6400', -5, 0, 0, 0, 0),
('6400', -4, 0, 0, 0, 0),
('6400', -3, 0, 0, 0, 0),
('6400', -2, 0, 0, 0, 0),
('6400', -1, 0, 0, 0, 0),
('6400', 0, 0, 0, 0, 0),
('6400', 1, 0, 0, 0, 0),
('6400', 2, 0, 0, 0, 0),
('6400', 3, 0, 0, 0, 0),
('6400', 4, 0, 0, 0, 0),
('6400', 5, 0, 0, 0, 0),
('6400', 6, 0, 0, 0, 0),
('6400', 7, 0, 0, 0, 0),
('6400', 8, 0, 0, 0, 0),
('6400', 9, 0, 0, 0, 0),
('6400', 10, 0, 0, 0, 0),
('6400', 11, 0, 0, 0, 0),
('6400', 12, 0, 0, 0, 0),
('6400', 13, 0, 0, 0, 0),
('6400', 14, 0, 0, 0, 0),
('6400', 15, 0, 0, 0, 0),
('6400', 16, 0, 0, 0, 0),
('6400', 17, 0, 0, 0, 0),
('6400', 18, 0, 0, 0, 0),
('6400', 19, 0, 0, 0, 0),
('6400', 20, 0, 0, 0, 0),
('6400', 21, 0, 0, 0, 0),
('6400', 22, 0, 0, 0, 0),
('6400', 23, 0, 0, 0, 0),
('6400', 24, 0, 0, 0, 0),
('6500', -13, 0, 0, 0, 0),
('6500', -12, 0, 0, 0, 0),
('6500', -11, 0, 0, 0, 0),
('6500', -10, 0, 0, 0, 0),
('6500', -9, 0, 0, 0, 0),
('6500', -8, 0, 0, 0, 0),
('6500', -7, 0, 0, 0, 0),
('6500', -6, 0, 0, 0, 0),
('6500', -5, 0, 0, 0, 0),
('6500', -4, 0, 0, 0, 0),
('6500', -3, 0, 0, 0, 0),
('6500', -2, 0, 0, 0, 0),
('6500', -1, 0, 0, 0, 0),
('6500', 0, 0, 0, 0, 0),
('6500', 1, 0, 0, 0, 0),
('6500', 2, 0, 0, 0, 0),
('6500', 3, 0, 0, 0, 0),
('6500', 4, 0, 0, 0, 0),
('6500', 5, 0, 0, 0, 0),
('6500', 6, 0, 0, 0, 0),
('6500', 7, 0, 0, 0, 0),
('6500', 8, 0, 0, 0, 0),
('6500', 9, 0, 0, 0, 0),
('6500', 10, 0, 0, 0, 0),
('6500', 11, 0, 0, 0, 0),
('6500', 12, 0, 0, 0, 0),
('6500', 13, 0, 0, 0, 0),
('6500', 14, 0, 0, 0, 0),
('6500', 15, 0, 0, 0, 0),
('6500', 16, 0, 0, 0, 0),
('6500', 17, 0, 0, 0, 0),
('6500', 18, 0, 0, 0, 0),
('6500', 19, 0, 0, 0, 0),
('6500', 20, 0, 0, 0, 0),
('6500', 21, 0, 0, 0, 0),
('6500', 22, 0, 0, 0, 0),
('6500', 23, 0, 0, 0, 0),
('6500', 24, 0, 0, 0, 0),
('6550', -13, 0, 0, 0, 0),
('6550', -12, 0, 0, 0, 0),
('6550', -11, 0, 0, 0, 0),
('6550', -10, 0, 0, 0, 0),
('6550', -9, 0, 0, 0, 0),
('6550', -8, 0, 0, 0, 0),
('6550', -7, 0, 0, 0, 0),
('6550', -6, 0, 0, 0, 0),
('6550', -5, 0, 0, 0, 0),
('6550', -4, 0, 0, 0, 0),
('6550', -3, 0, 0, 0, 0),
('6550', -2, 0, 0, 0, 0),
('6550', -1, 0, 0, 0, 0),
('6550', 0, 0, 0, 0, 0),
('6550', 1, 0, 0, 0, 0),
('6550', 2, 0, 0, 0, 0),
('6550', 3, 0, 0, 0, 0),
('6550', 4, 0, 0, 0, 0),
('6550', 5, 0, 0, 0, 0),
('6550', 6, 0, 0, 0, 0),
('6550', 7, 0, 0, 0, 0),
('6550', 8, 0, 0, 0, 0),
('6550', 9, 0, 0, 0, 0),
('6550', 10, 0, 0, 0, 0),
('6550', 11, 0, 0, 0, 0),
('6550', 12, 0, 0, 0, 0),
('6550', 13, 0, 0, 0, 0),
('6550', 14, 0, 0, 0, 0),
('6550', 15, 0, 0, 0, 0),
('6550', 16, 0, 0, 0, 0),
('6550', 17, 0, 0, 0, 0),
('6550', 18, 0, 0, 0, 0),
('6550', 19, 0, 0, 0, 0),
('6550', 20, 0, 0, 0, 0),
('6550', 21, 0, 0, 0, 0),
('6550', 22, 0, 0, 0, 0),
('6550', 23, 0, 0, 0, 0),
('6550', 24, 0, 0, 0, 0),
('6590', -13, 0, 0, 0, 0),
('6590', -12, 0, 0, 0, 0),
('6590', -11, 0, 0, 0, 0),
('6590', -10, 0, 0, 0, 0),
('6590', -9, 0, 0, 0, 0),
('6590', -8, 0, 0, 0, 0),
('6590', -7, 0, 0, 0, 0),
('6590', -6, 0, 0, 0, 0),
('6590', -5, 0, 0, 0, 0),
('6590', -4, 0, 0, 0, 0),
('6590', -3, 0, 0, 0, 0),
('6590', -2, 0, 0, 0, 0),
('6590', -1, 0, 0, 0, 0),
('6590', 0, 0, 0, 0, 0),
('6590', 1, 0, 0, 0, 0),
('6590', 2, 0, 0, 0, 0),
('6590', 3, 0, 0, 0, 0),
('6590', 4, 0, 0, 0, 0),
('6590', 5, 0, 0, 0, 0),
('6590', 6, 0, 0, 0, 0),
('6590', 7, 0, 0, 0, 0),
('6590', 8, 0, 0, 0, 0),
('6590', 9, 0, 0, 0, 0),
('6590', 10, 0, 0, 0, 0),
('6590', 11, 0, 0, 0, 0),
('6590', 12, 0, 0, 0, 0),
('6590', 13, 0, 0, 0, 0),
('6590', 14, 0, 0, 0, 0),
('6590', 15, 0, 0, 0, 0),
('6590', 16, 0, 0, 0, 0),
('6590', 17, 0, 0, 0, 0),
('6590', 18, 0, 0, 0, 0),
('6590', 19, 0, 0, 0, 0),
('6590', 20, 0, 0, 0, 0),
('6590', 21, 0, 0, 0, 0),
('6590', 22, 0, 0, 0, 0),
('6590', 23, 0, 0, 0, 0),
('6590', 24, 0, 0, 0, 0),
('6600', -13, 0, 0, 0, 0),
('6600', -12, 0, 0, 0, 0),
('6600', -11, 0, 0, 0, 0),
('6600', -10, 0, 0, 0, 0),
('6600', -9, 0, 0, 0, 0),
('6600', -8, 0, 0, 0, 0),
('6600', -7, 0, 0, 0, 0),
('6600', -6, 0, 0, 0, 0),
('6600', -5, 0, 0, 0, 0),
('6600', -4, 0, 0, 0, 0),
('6600', -3, 0, 0, 0, 0),
('6600', -2, 0, 0, 0, 0),
('6600', -1, 0, 0, 0, 0),
('6600', 0, 0, 0, 0, 0),
('6600', 1, 0, 0, 0, 0),
('6600', 2, 0, 0, 0, 0),
('6600', 3, 0, 0, 0, 0),
('6600', 4, 0, 0, 0, 0),
('6600', 5, 0, 0, 0, 0),
('6600', 6, 0, 0, 0, 0),
('6600', 7, 0, 0, 0, 0),
('6600', 8, 0, 0, 0, 0),
('6600', 9, 0, 0, 0, 0),
('6600', 10, 0, 0, 0, 0),
('6600', 11, 0, 0, 0, 0),
('6600', 12, 0, 0, 0, 0),
('6600', 13, 0, 0, 0, 0),
('6600', 14, 0, 0, 0, 0),
('6600', 15, 0, 0, 0, 0),
('6600', 16, 0, 0, 0, 0),
('6600', 17, 0, 0, 0, 0),
('6600', 18, 0, 0, 0, 0),
('6600', 19, 0, 0, 0, 0),
('6600', 20, 0, 0, 0, 0),
('6600', 21, 0, 0, 0, 0),
('6600', 22, 0, 0, 0, 0),
('6600', 23, 0, 0, 0, 0),
('6600', 24, 0, 0, 0, 0),
('6700', -13, 0, 0, 0, 0),
('6700', -12, 0, 0, 0, 0),
('6700', -11, 0, 0, 0, 0),
('6700', -10, 0, 0, 0, 0),
('6700', -9, 0, 0, 0, 0),
('6700', -8, 0, 0, 0, 0),
('6700', -7, 0, 0, 0, 0),
('6700', -6, 0, 0, 0, 0),
('6700', -5, 0, 0, 0, 0),
('6700', -4, 0, 0, 0, 0),
('6700', -3, 0, 0, 0, 0),
('6700', -2, 0, 0, 0, 0),
('6700', -1, 0, 0, 0, 0),
('6700', 0, 0, 0, 0, 0),
('6700', 1, 0, 0, 0, 0),
('6700', 2, 0, 0, 0, 0),
('6700', 3, 0, 0, 0, 0),
('6700', 4, 0, 0, 0, 0),
('6700', 5, 0, 0, 0, 0),
('6700', 6, 0, 0, 0, 0),
('6700', 7, 0, 0, 0, 0),
('6700', 8, 0, 0, 0, 0),
('6700', 9, 0, 0, 0, 0),
('6700', 10, 0, 0, 0, 0),
('6700', 11, 0, 0, 0, 0),
('6700', 12, 0, 0, 0, 0),
('6700', 13, 0, 0, 0, 0),
('6700', 14, 0, 0, 0, 0),
('6700', 15, 0, 0, 0, 0),
('6700', 16, 0, 0, 0, 0),
('6700', 17, 0, 0, 0, 0),
('6700', 18, 0, 0, 0, 0),
('6700', 19, 0, 0, 0, 0),
('6700', 20, 0, 0, 0, 0),
('6700', 21, 0, 0, 0, 0),
('6700', 22, 0, 0, 0, 0),
('6700', 23, 0, 0, 0, 0),
('6700', 24, 0, 0, 0, 0),
('6800', -13, 0, 0, 0, 0),
('6800', -12, 0, 0, 0, 0),
('6800', -11, 0, 0, 0, 0),
('6800', -10, 0, 0, 0, 0),
('6800', -9, 0, 0, 0, 0),
('6800', -8, 0, 0, 0, 0),
('6800', -7, 0, 0, 0, 0),
('6800', -6, 0, 0, 0, 0),
('6800', -5, 0, 0, 0, 0),
('6800', -4, 0, 0, 0, 0),
('6800', -3, 0, 0, 0, 0),
('6800', -2, 0, 0, 0, 0),
('6800', -1, 0, 0, 0, 0),
('6800', 0, 0, 0, 0, 0),
('6800', 1, 0, 0, 0, 0),
('6800', 2, 0, 0, 0, 0),
('6800', 3, 0, 0, 0, 0),
('6800', 4, 0, 0, 0, 0),
('6800', 5, 0, 0, 0, 0),
('6800', 6, 0, 0, 0, 0),
('6800', 7, 0, 0, 0, 0),
('6800', 8, 0, 0, 0, 0),
('6800', 9, 0, 0, 0, 0),
('6800', 10, 0, 0, 0, 0),
('6800', 11, 0, 0, 0, 0),
('6800', 12, 0, 0, 0, 0),
('6800', 13, 0, 0, 0, 0),
('6800', 14, 0, 0, 0, 0),
('6800', 15, 0, 0, 0, 0),
('6800', 16, 0, 0, 0, 0),
('6800', 17, 0, 0, 0, 0),
('6800', 18, 0, 0, 0, 0),
('6800', 19, 0, 0, 0, 0),
('6800', 20, 0, 0, 0, 0),
('6800', 21, 0, 0, 0, 0),
('6800', 22, 0, 0, 0, 0),
('6800', 23, 0, 0, 0, 0),
('6800', 24, 0, 0, 0, 0),
('6900', -13, 0, 0, 0, 0),
('6900', -12, 0, 0, 0, 0),
('6900', -11, 0, 0, 0, 0),
('6900', -10, 0, 0, 0, 0),
('6900', -9, 0, 0, 0, 0),
('6900', -8, 0, 0, 0, 0),
('6900', -7, 0, 0, 0, 0),
('6900', -6, 0, 0, 0, 0),
('6900', -5, 0, 0, 0, 0),
('6900', -4, 0, 0, 0, 0),
('6900', -3, 0, 0, 0, 0),
('6900', -2, 0, 0, 0, 0),
('6900', -1, 0, 0, 0, 0),
('6900', 0, 0, 0, 0, 0),
('6900', 1, 0, 0, 0, 0),
('6900', 2, 0, 0, 0, 0),
('6900', 3, 0, 0, 0, 0),
('6900', 4, 0, 0, 0, 0),
('6900', 5, 0, 0, 0, 0),
('6900', 6, 0, 0, 0, 0),
('6900', 7, 0, 0, 0, 0),
('6900', 8, 0, 0, 0, 0),
('6900', 9, 0, 0, 0, 0),
('6900', 10, 0, 0, 0, 0),
('6900', 11, 0, 0, 0, 0),
('6900', 12, 0, 0, 0, 0),
('6900', 13, 0, 0, 0, 0),
('6900', 14, 0, 0, 0, 0),
('6900', 15, 0, 0, 0, 0),
('6900', 16, 0, 0, 0, 0),
('6900', 17, 0, 0, 0, 0),
('6900', 18, 0, 0, 0, 0),
('6900', 19, 0, 0, 0, 0),
('6900', 20, 0, 0, 0, 0),
('6900', 21, 0, 0, 0, 0),
('6900', 22, 0, 0, 0, 0),
('6900', 23, 0, 0, 0, 0),
('6900', 24, 0, 0, 0, 0),
('7020', -13, 0, 0, 0, 0),
('7020', -12, 0, 0, 0, 0),
('7020', -11, 0, 0, 0, 0),
('7020', -10, 0, 0, 0, 0),
('7020', -9, 0, 0, 0, 0),
('7020', -8, 0, 0, 0, 0),
('7020', -7, 0, 0, 0, 0),
('7020', -6, 0, 0, 0, 0),
('7020', -5, 0, 0, 0, 0),
('7020', -4, 0, 0, 0, 0),
('7020', -3, 0, 0, 0, 0),
('7020', -2, 0, 0, 0, 0),
('7020', -1, 0, 0, 0, 0),
('7020', 0, 0, 0, 0, 0),
('7020', 1, 0, 0, 0, 0),
('7020', 2, 0, 0, 0, 0),
('7020', 3, 0, 0, 0, 0),
('7020', 4, 0, 0, 0, 0),
('7020', 5, 0, 0, 0, 0),
('7020', 6, 0, 0, 0, 0),
('7020', 7, 0, 0, 0, 0),
('7020', 8, 0, 0, 0, 0),
('7020', 9, 0, 0, 0, 0),
('7020', 10, 0, 0, 0, 0),
('7020', 11, 0, 0, 0, 0),
('7020', 12, 0, 0, 0, 0),
('7020', 13, 0, 0, 0, 0),
('7020', 14, 0, 0, 0, 0),
('7020', 15, 0, 0, 0, 0),
('7020', 16, 0, 0, 0, 0),
('7020', 17, 0, 0, 0, 0),
('7020', 18, 0, 0, 0, 0),
('7020', 19, 0, 0, 0, 0),
('7020', 20, 0, 0, 0, 0),
('7020', 21, 0, 0, 0, 0),
('7020', 22, 0, 0, 0, 0),
('7020', 23, 0, 0, 0, 0),
('7020', 24, 0, 0, 0, 0),
('7030', -13, 0, 0, 0, 0),
('7030', -12, 0, 0, 0, 0),
('7030', -11, 0, 0, 0, 0),
('7030', -10, 0, 0, 0, 0),
('7030', -9, 0, 0, 0, 0),
('7030', -8, 0, 0, 0, 0),
('7030', -7, 0, 0, 0, 0),
('7030', -6, 0, 0, 0, 0),
('7030', -5, 0, 0, 0, 0),
('7030', -4, 0, 0, 0, 0),
('7030', -3, 0, 0, 0, 0),
('7030', -2, 0, 0, 0, 0),
('7030', -1, 0, 0, 0, 0),
('7030', 0, 0, 0, 0, 0),
('7030', 1, 0, 0, 0, 0),
('7030', 2, 0, 0, 0, 0),
('7030', 3, 0, 0, 0, 0),
('7030', 4, 0, 0, 0, 0),
('7030', 5, 0, 0, 0, 0),
('7030', 6, 0, 0, 0, 0),
('7030', 7, 0, 0, 0, 0),
('7030', 8, 0, 0, 0, 0),
('7030', 9, 0, 0, 0, 0),
('7030', 10, 0, 0, 0, 0),
('7030', 11, 0, 0, 0, 0),
('7030', 12, 0, 0, 0, 0),
('7030', 13, 0, 0, 0, 0),
('7030', 14, 0, 0, 0, 0),
('7030', 15, 0, 0, 0, 0),
('7030', 16, 0, 0, 0, 0),
('7030', 17, 0, 0, 0, 0),
('7030', 18, 0, 0, 0, 0),
('7030', 19, 0, 0, 0, 0),
('7030', 20, 0, 0, 0, 0),
('7030', 21, 0, 0, 0, 0),
('7030', 22, 0, 0, 0, 0),
('7030', 23, 0, 0, 0, 0),
('7030', 24, 0, 0, 0, 0),
('7040', -13, 0, 0, 0, 0),
('7040', -12, 0, 0, 0, 0),
('7040', -11, 0, 0, 0, 0),
('7040', -10, 0, 0, 0, 0),
('7040', -9, 0, 0, 0, 0),
('7040', -8, 0, 0, 0, 0),
('7040', -7, 0, 0, 0, 0),
('7040', -6, 0, 0, 0, 0),
('7040', -5, 0, 0, 0, 0),
('7040', -4, 0, 0, 0, 0),
('7040', -3, 0, 0, 0, 0),
('7040', -2, 0, 0, 0, 0),
('7040', -1, 0, 0, 0, 0),
('7040', 0, 0, 0, 0, 0),
('7040', 1, 0, 0, 0, 0),
('7040', 2, 0, 0, 0, 0),
('7040', 3, 0, 0, 0, 0),
('7040', 4, 0, 0, 0, 0),
('7040', 5, 0, 0, 0, 0),
('7040', 6, 0, 0, 0, 0),
('7040', 7, 0, 0, 0, 0),
('7040', 8, 0, 0, 0, 0),
('7040', 9, 0, 0, 0, 0),
('7040', 10, 0, 0, 0, 0),
('7040', 11, 0, 0, 0, 0),
('7040', 12, 0, 0, 0, 0),
('7040', 13, 0, 0, 0, 0),
('7040', 14, 0, 0, 0, 0),
('7040', 15, 0, 0, 0, 0),
('7040', 16, 0, 0, 0, 0),
('7040', 17, 0, 0, 0, 0),
('7040', 18, 0, 0, 0, 0),
('7040', 19, 0, 0, 0, 0),
('7040', 20, 0, 0, 0, 0),
('7040', 21, 0, 0, 0, 0),
('7040', 22, 0, 0, 0, 0),
('7040', 23, 0, 0, 0, 0),
('7040', 24, 0, 0, 0, 0),
('7050', -13, 0, 0, 0, 0),
('7050', -12, 0, 0, 0, 0),
('7050', -11, 0, 0, 0, 0),
('7050', -10, 0, 0, 0, 0),
('7050', -9, 0, 0, 0, 0),
('7050', -8, 0, 0, 0, 0),
('7050', -7, 0, 0, 0, 0),
('7050', -6, 0, 0, 0, 0),
('7050', -5, 0, 0, 0, 0),
('7050', -4, 0, 0, 0, 0),
('7050', -3, 0, 0, 0, 0),
('7050', -2, 0, 0, 0, 0),
('7050', -1, 0, 0, 0, 0),
('7050', 0, 0, 0, 0, 0),
('7050', 1, 0, 0, 0, 0),
('7050', 2, 0, 0, 0, 0),
('7050', 3, 0, 0, 0, 0),
('7050', 4, 0, 0, 0, 0),
('7050', 5, 0, 0, 0, 0),
('7050', 6, 0, 0, 0, 0),
('7050', 7, 0, 0, 0, 0),
('7050', 8, 0, 0, 0, 0),
('7050', 9, 0, 0, 0, 0),
('7050', 10, 0, 0, 0, 0),
('7050', 11, 0, 0, 0, 0),
('7050', 12, 0, 0, 0, 0),
('7050', 13, 0, 0, 0, 0),
('7050', 14, 0, 0, 0, 0),
('7050', 15, 0, 0, 0, 0),
('7050', 16, 0, 0, 0, 0),
('7050', 17, 0, 0, 0, 0),
('7050', 18, 0, 0, 0, 0),
('7050', 19, 0, 0, 0, 0),
('7050', 20, 0, 0, 0, 0),
('7050', 21, 0, 0, 0, 0),
('7050', 22, 0, 0, 0, 0),
('7050', 23, 0, 0, 0, 0),
('7050', 24, 0, 0, 0, 0),
('7060', -13, 0, 0, 0, 0),
('7060', -12, 0, 0, 0, 0),
('7060', -11, 0, 0, 0, 0),
('7060', -10, 0, 0, 0, 0),
('7060', -9, 0, 0, 0, 0),
('7060', -8, 0, 0, 0, 0),
('7060', -7, 0, 0, 0, 0),
('7060', -6, 0, 0, 0, 0),
('7060', -5, 0, 0, 0, 0),
('7060', -4, 0, 0, 0, 0),
('7060', -3, 0, 0, 0, 0),
('7060', -2, 0, 0, 0, 0),
('7060', -1, 0, 0, 0, 0),
('7060', 0, 0, 0, 0, 0),
('7060', 1, 0, 0, 0, 0),
('7060', 2, 0, 0, 0, 0),
('7060', 3, 0, 0, 0, 0),
('7060', 4, 0, 0, 0, 0),
('7060', 5, 0, 0, 0, 0),
('7060', 6, 0, 0, 0, 0),
('7060', 7, 0, 0, 0, 0),
('7060', 8, 0, 0, 0, 0),
('7060', 9, 0, 0, 0, 0),
('7060', 10, 0, 0, 0, 0),
('7060', 11, 0, 0, 0, 0),
('7060', 12, 0, 0, 0, 0),
('7060', 13, 0, 0, 0, 0),
('7060', 14, 0, 0, 0, 0),
('7060', 15, 0, 0, 0, 0),
('7060', 16, 0, 0, 0, 0),
('7060', 17, 0, 0, 0, 0),
('7060', 18, 0, 0, 0, 0),
('7060', 19, 0, 0, 0, 0),
('7060', 20, 0, 0, 0, 0),
('7060', 21, 0, 0, 0, 0),
('7060', 22, 0, 0, 0, 0),
('7060', 23, 0, 0, 0, 0),
('7060', 24, 0, 0, 0, 0),
('7070', -13, 0, 0, 0, 0),
('7070', -12, 0, 0, 0, 0),
('7070', -11, 0, 0, 0, 0),
('7070', -10, 0, 0, 0, 0),
('7070', -9, 0, 0, 0, 0),
('7070', -8, 0, 0, 0, 0),
('7070', -7, 0, 0, 0, 0),
('7070', -6, 0, 0, 0, 0),
('7070', -5, 0, 0, 0, 0),
('7070', -4, 0, 0, 0, 0),
('7070', -3, 0, 0, 0, 0),
('7070', -2, 0, 0, 0, 0),
('7070', -1, 0, 0, 0, 0),
('7070', 0, 0, 0, 0, 0),
('7070', 1, 0, 0, 0, 0),
('7070', 2, 0, 0, 0, 0),
('7070', 3, 0, 0, 0, 0),
('7070', 4, 0, 0, 0, 0),
('7070', 5, 0, 0, 0, 0),
('7070', 6, 0, 0, 0, 0),
('7070', 7, 0, 0, 0, 0),
('7070', 8, 0, 0, 0, 0),
('7070', 9, 0, 0, 0, 0),
('7070', 10, 0, 0, 0, 0),
('7070', 11, 0, 0, 0, 0),
('7070', 12, 0, 0, 0, 0),
('7070', 13, 0, 0, 0, 0),
('7070', 14, 0, 0, 0, 0),
('7070', 15, 0, 0, 0, 0),
('7070', 16, 0, 0, 0, 0),
('7070', 17, 0, 0, 0, 0),
('7070', 18, 0, 0, 0, 0),
('7070', 19, 0, 0, 0, 0),
('7070', 20, 0, 0, 0, 0),
('7070', 21, 0, 0, 0, 0),
('7070', 22, 0, 0, 0, 0),
('7070', 23, 0, 0, 0, 0),
('7070', 24, 0, 0, 0, 0),
('7080', -13, 0, 0, 0, 0),
('7080', -12, 0, 0, 0, 0),
('7080', -11, 0, 0, 0, 0),
('7080', -10, 0, 0, 0, 0),
('7080', -9, 0, 0, 0, 0),
('7080', -8, 0, 0, 0, 0),
('7080', -7, 0, 0, 0, 0),
('7080', -6, 0, 0, 0, 0),
('7080', -5, 0, 0, 0, 0),
('7080', -4, 0, 0, 0, 0),
('7080', -3, 0, 0, 0, 0),
('7080', -2, 0, 0, 0, 0),
('7080', -1, 0, 0, 0, 0),
('7080', 0, 0, 0, 0, 0),
('7080', 1, 0, 0, 0, 0),
('7080', 2, 0, 0, 0, 0),
('7080', 3, 0, 0, 0, 0),
('7080', 4, 0, 0, 0, 0),
('7080', 5, 0, 0, 0, 0),
('7080', 6, 0, 0, 0, 0),
('7080', 7, 0, 0, 0, 0),
('7080', 8, 0, 0, 0, 0),
('7080', 9, 0, 0, 0, 0),
('7080', 10, 0, 0, 0, 0),
('7080', 11, 0, 0, 0, 0),
('7080', 12, 0, 0, 0, 0),
('7080', 13, 0, 0, 0, 0),
('7080', 14, 0, 0, 0, 0),
('7080', 15, 0, 0, 0, 0),
('7080', 16, 0, 0, 0, 0),
('7080', 17, 0, 0, 0, 0),
('7080', 18, 0, 0, 0, 0),
('7080', 19, 0, 0, 0, 0),
('7080', 20, 0, 0, 0, 0),
('7080', 21, 0, 0, 0, 0),
('7080', 22, 0, 0, 0, 0),
('7080', 23, 0, 0, 0, 0),
('7080', 24, 0, 0, 0, 0),
('7090', -13, 0, 0, 0, 0),
('7090', -12, 0, 0, 0, 0),
('7090', -11, 0, 0, 0, 0),
('7090', -10, 0, 0, 0, 0),
('7090', -9, 0, 0, 0, 0),
('7090', -8, 0, 0, 0, 0),
('7090', -7, 0, 0, 0, 0),
('7090', -6, 0, 0, 0, 0),
('7090', -5, 0, 0, 0, 0),
('7090', -4, 0, 0, 0, 0),
('7090', -3, 0, 0, 0, 0),
('7090', -2, 0, 0, 0, 0),
('7090', -1, 0, 0, 0, 0),
('7090', 0, 0, 0, 0, 0),
('7090', 1, 0, 0, 0, 0),
('7090', 2, 0, 0, 0, 0),
('7090', 3, 0, 0, 0, 0),
('7090', 4, 0, 0, 0, 0),
('7090', 5, 0, 0, 0, 0),
('7090', 6, 0, 0, 0, 0),
('7090', 7, 0, 0, 0, 0),
('7090', 8, 0, 0, 0, 0),
('7090', 9, 0, 0, 0, 0),
('7090', 10, 0, 0, 0, 0),
('7090', 11, 0, 0, 0, 0),
('7090', 12, 0, 0, 0, 0),
('7090', 13, 0, 0, 0, 0),
('7090', 14, 0, 0, 0, 0),
('7090', 15, 0, 0, 0, 0),
('7090', 16, 0, 0, 0, 0),
('7090', 17, 0, 0, 0, 0),
('7090', 18, 0, 0, 0, 0),
('7090', 19, 0, 0, 0, 0),
('7090', 20, 0, 0, 0, 0),
('7090', 21, 0, 0, 0, 0),
('7090', 22, 0, 0, 0, 0),
('7090', 23, 0, 0, 0, 0),
('7090', 24, 0, 0, 0, 0),
('7100', -13, 0, 0, 0, 0),
('7100', -12, 0, 0, 0, 0),
('7100', -11, 0, 0, 0, 0),
('7100', -10, 0, 0, 0, 0),
('7100', -9, 0, 0, 0, 0),
('7100', -8, 0, 0, 0, 0),
('7100', -7, 0, 0, 0, 0),
('7100', -6, 0, 0, 0, 0),
('7100', -5, 0, 0, 0, 0),
('7100', -4, 0, 0, 0, 0),
('7100', -3, 0, 0, 0, 0),
('7100', -2, 0, 0, 0, 0),
('7100', -1, 0, 0, 0, 0),
('7100', 0, 0, 0, 0, 0),
('7100', 1, 0, 0, 0, 0),
('7100', 2, 0, 0, 0, 0),
('7100', 3, 0, 0, 0, 0),
('7100', 4, 0, 0, 0, 0),
('7100', 5, 0, 0, 0, 0),
('7100', 6, 0, 0, 0, 0),
('7100', 7, 0, 0, 0, 0),
('7100', 8, 0, 0, 0, 0),
('7100', 9, 0, 0, 0, 0),
('7100', 10, 0, 0, 0, 0),
('7100', 11, 0, 0, 0, 0),
('7100', 12, 0, 0, 0, 0),
('7100', 13, 0, 0, 0, 0),
('7100', 14, 0, 0, 0, 0),
('7100', 15, 0, 0, 0, 0),
('7100', 16, 0, 0, 0, 0),
('7100', 17, 0, 0, 0, 0),
('7100', 18, 0, 0, 0, 0),
('7100', 19, 0, 0, 0, 0),
('7100', 20, 0, 0, 0, 0),
('7100', 21, 0, 0, 0, 0),
('7100', 22, 0, 0, 0, 0),
('7100', 23, 0, 0, 0, 0),
('7100', 24, 0, 0, 0, 0),
('7150', -13, 0, 0, 0, 0),
('7150', -12, 0, 0, 0, 0),
('7150', -11, 0, 0, 0, 0),
('7150', -10, 0, 0, 0, 0);
INSERT INTO `chartdetails` (`accountcode`, `period`, `budget`, `actual`, `bfwd`, `bfwdbudget`) VALUES
('7150', -9, 0, 0, 0, 0),
('7150', -8, 0, 0, 0, 0),
('7150', -7, 0, 0, 0, 0),
('7150', -6, 0, 0, 0, 0),
('7150', -5, 0, 0, 0, 0),
('7150', -4, 0, 0, 0, 0),
('7150', -3, 0, 0, 0, 0),
('7150', -2, 0, 0, 0, 0),
('7150', -1, 0, 0, 0, 0),
('7150', 0, 0, 0, 0, 0),
('7150', 1, 0, 0, 0, 0),
('7150', 2, 0, 0, 0, 0),
('7150', 3, 0, 0, 0, 0),
('7150', 4, 0, 0, 0, 0),
('7150', 5, 0, 0, 0, 0),
('7150', 6, 0, 0, 0, 0),
('7150', 7, 0, 0, 0, 0),
('7150', 8, 0, 0, 0, 0),
('7150', 9, 0, 0, 0, 0),
('7150', 10, 0, 0, 0, 0),
('7150', 11, 0, 0, 0, 0),
('7150', 12, 0, 0, 0, 0),
('7150', 13, 0, 0, 0, 0),
('7150', 14, 0, 0, 0, 0),
('7150', 15, 0, 0, 0, 0),
('7150', 16, 0, 0, 0, 0),
('7150', 17, 0, 0, 0, 0),
('7150', 18, 0, 0, 0, 0),
('7150', 19, 0, 0, 0, 0),
('7150', 20, 0, 0, 0, 0),
('7150', 21, 0, 0, 0, 0),
('7150', 22, 0, 0, 0, 0),
('7150', 23, 0, 0, 0, 0),
('7150', 24, 0, 0, 0, 0),
('7200', -13, 0, 0, 0, 0),
('7200', -12, 0, 0, 0, 0),
('7200', -11, 0, 0, 0, 0),
('7200', -10, 0, 0, 0, 0),
('7200', -9, 0, 0, 0, 0),
('7200', -8, 0, 0, 0, 0),
('7200', -7, 0, 0, 0, 0),
('7200', -6, 0, 0, 0, 0),
('7200', -5, 0, 0, 0, 0),
('7200', -4, 0, 0, 0, 0),
('7200', -3, 0, 0, 0, 0),
('7200', -2, 0, 0, 0, 0),
('7200', -1, 0, 0, 0, 0),
('7200', 0, 0, 0, 0, 0),
('7200', 1, 0, 0, 0, 0),
('7200', 2, 0, 0, 0, 0),
('7200', 3, 0, 0, 0, 0),
('7200', 4, 0, 0, 0, 0),
('7200', 5, 0, 0, 0, 0),
('7200', 6, 0, 0, 0, 0),
('7200', 7, 0, 0, 0, 0),
('7200', 8, 0, 0, 0, 0),
('7200', 9, 0, 0, 0, 0),
('7200', 10, 0, 0, 0, 0),
('7200', 11, 0, 0, 0, 0),
('7200', 12, 0, 0, 0, 0),
('7200', 13, 0, 0, 0, 0),
('7200', 14, 0, 0, 0, 0),
('7200', 15, 0, 0, 0, 0),
('7200', 16, 0, 0, 0, 0),
('7200', 17, 0, 0, 0, 0),
('7200', 18, 0, 0, 0, 0),
('7200', 19, 0, 0, 0, 0),
('7200', 20, 0, 0, 0, 0),
('7200', 21, 0, 0, 0, 0),
('7200', 22, 0, 0, 0, 0),
('7200', 23, 0, 0, 0, 0),
('7200', 24, 0, 0, 0, 0),
('7210', -13, 0, 0, 0, 0),
('7210', -12, 0, 0, 0, 0),
('7210', -11, 0, 0, 0, 0),
('7210', -10, 0, 0, 0, 0),
('7210', -9, 0, 0, 0, 0),
('7210', -8, 0, 0, 0, 0),
('7210', -7, 0, 0, 0, 0),
('7210', -6, 0, 0, 0, 0),
('7210', -5, 0, 0, 0, 0),
('7210', -4, 0, 0, 0, 0),
('7210', -3, 0, 0, 0, 0),
('7210', -2, 0, 0, 0, 0),
('7210', -1, 0, 0, 0, 0),
('7210', 0, 0, 0, 0, 0),
('7210', 1, 0, 0, 0, 0),
('7210', 2, 0, 0, 0, 0),
('7210', 3, 0, 0, 0, 0),
('7210', 4, 0, 0, 0, 0),
('7210', 5, 0, 0, 0, 0),
('7210', 6, 0, 0, 0, 0),
('7210', 7, 0, 0, 0, 0),
('7210', 8, 0, 0, 0, 0),
('7210', 9, 0, 0, 0, 0),
('7210', 10, 0, 0, 0, 0),
('7210', 11, 0, 0, 0, 0),
('7210', 12, 0, 0, 0, 0),
('7210', 13, 0, 0, 0, 0),
('7210', 14, 0, 0, 0, 0),
('7210', 15, 0, 0, 0, 0),
('7210', 16, 0, 0, 0, 0),
('7210', 17, 0, 0, 0, 0),
('7210', 18, 0, 0, 0, 0),
('7210', 19, 0, 0, 0, 0),
('7210', 20, 0, 0, 0, 0),
('7210', 21, 0, 0, 0, 0),
('7210', 22, 0, 0, 0, 0),
('7210', 23, 0, 0, 0, 0),
('7210', 24, 0, 0, 0, 0),
('7220', -13, 0, 0, 0, 0),
('7220', -12, 0, 0, 0, 0),
('7220', -11, 0, 0, 0, 0),
('7220', -10, 0, 0, 0, 0),
('7220', -9, 0, 0, 0, 0),
('7220', -8, 0, 0, 0, 0),
('7220', -7, 0, 0, 0, 0),
('7220', -6, 0, 0, 0, 0),
('7220', -5, 0, 0, 0, 0),
('7220', -4, 0, 0, 0, 0),
('7220', -3, 0, 0, 0, 0),
('7220', -2, 0, 0, 0, 0),
('7220', -1, 0, 0, 0, 0),
('7220', 0, 0, 0, 0, 0),
('7220', 1, 0, 0, 0, 0),
('7220', 2, 0, 0, 0, 0),
('7220', 3, 0, 0, 0, 0),
('7220', 4, 0, 0, 0, 0),
('7220', 5, 0, 0, 0, 0),
('7220', 6, 0, 0, 0, 0),
('7220', 7, 0, 0, 0, 0),
('7220', 8, 0, 0, 0, 0),
('7220', 9, 0, 0, 0, 0),
('7220', 10, 0, 0, 0, 0),
('7220', 11, 0, 0, 0, 0),
('7220', 12, 0, 0, 0, 0),
('7220', 13, 0, 0, 0, 0),
('7220', 14, 0, 0, 0, 0),
('7220', 15, 0, 0, 0, 0),
('7220', 16, 0, 0, 0, 0),
('7220', 17, 0, 0, 0, 0),
('7220', 18, 0, 0, 0, 0),
('7220', 19, 0, 0, 0, 0),
('7220', 20, 0, 0, 0, 0),
('7220', 21, 0, 0, 0, 0),
('7220', 22, 0, 0, 0, 0),
('7220', 23, 0, 0, 0, 0),
('7220', 24, 0, 0, 0, 0),
('7230', -13, 0, 0, 0, 0),
('7230', -12, 0, 0, 0, 0),
('7230', -11, 0, 0, 0, 0),
('7230', -10, 0, 0, 0, 0),
('7230', -9, 0, 0, 0, 0),
('7230', -8, 0, 0, 0, 0),
('7230', -7, 0, 0, 0, 0),
('7230', -6, 0, 0, 0, 0),
('7230', -5, 0, 0, 0, 0),
('7230', -4, 0, 0, 0, 0),
('7230', -3, 0, 0, 0, 0),
('7230', -2, 0, 0, 0, 0),
('7230', -1, 0, 0, 0, 0),
('7230', 0, 0, 0, 0, 0),
('7230', 1, 0, 0, 0, 0),
('7230', 2, 0, 0, 0, 0),
('7230', 3, 0, 0, 0, 0),
('7230', 4, 0, 0, 0, 0),
('7230', 5, 0, 0, 0, 0),
('7230', 6, 0, 0, 0, 0),
('7230', 7, 0, 0, 0, 0),
('7230', 8, 0, 0, 0, 0),
('7230', 9, 0, 0, 0, 0),
('7230', 10, 0, 0, 0, 0),
('7230', 11, 0, 0, 0, 0),
('7230', 12, 0, 0, 0, 0),
('7230', 13, 0, 0, 0, 0),
('7230', 14, 0, 0, 0, 0),
('7230', 15, 0, 0, 0, 0),
('7230', 16, 0, 0, 0, 0),
('7230', 17, 0, 0, 0, 0),
('7230', 18, 0, 0, 0, 0),
('7230', 19, 0, 0, 0, 0),
('7230', 20, 0, 0, 0, 0),
('7230', 21, 0, 0, 0, 0),
('7230', 22, 0, 0, 0, 0),
('7230', 23, 0, 0, 0, 0),
('7230', 24, 0, 0, 0, 0),
('7240', -13, 0, 0, 0, 0),
('7240', -12, 0, 0, 0, 0),
('7240', -11, 0, 0, 0, 0),
('7240', -10, 0, 0, 0, 0),
('7240', -9, 0, 0, 0, 0),
('7240', -8, 0, 0, 0, 0),
('7240', -7, 0, 0, 0, 0),
('7240', -6, 0, 0, 0, 0),
('7240', -5, 0, 0, 0, 0),
('7240', -4, 0, 0, 0, 0),
('7240', -3, 0, 0, 0, 0),
('7240', -2, 0, 0, 0, 0),
('7240', -1, 0, 0, 0, 0),
('7240', 0, 0, 0, 0, 0),
('7240', 1, 0, 0, 0, 0),
('7240', 2, 0, 0, 0, 0),
('7240', 3, 0, 0, 0, 0),
('7240', 4, 0, 0, 0, 0),
('7240', 5, 0, 0, 0, 0),
('7240', 6, 0, 0, 0, 0),
('7240', 7, 0, 0, 0, 0),
('7240', 8, 0, 0, 0, 0),
('7240', 9, 0, 0, 0, 0),
('7240', 10, 0, 0, 0, 0),
('7240', 11, 0, 0, 0, 0),
('7240', 12, 0, 0, 0, 0),
('7240', 13, 0, 0, 0, 0),
('7240', 14, 0, 0, 0, 0),
('7240', 15, 0, 0, 0, 0),
('7240', 16, 0, 0, 0, 0),
('7240', 17, 0, 0, 0, 0),
('7240', 18, 0, 0, 0, 0),
('7240', 19, 0, 0, 0, 0),
('7240', 20, 0, 0, 0, 0),
('7240', 21, 0, 0, 0, 0),
('7240', 22, 0, 0, 0, 0),
('7240', 23, 0, 0, 0, 0),
('7240', 24, 0, 0, 0, 0),
('7260', -13, 0, 0, 0, 0),
('7260', -12, 0, 0, 0, 0),
('7260', -11, 0, 0, 0, 0),
('7260', -10, 0, 0, 0, 0),
('7260', -9, 0, 0, 0, 0),
('7260', -8, 0, 0, 0, 0),
('7260', -7, 0, 0, 0, 0),
('7260', -6, 0, 0, 0, 0),
('7260', -5, 0, 0, 0, 0),
('7260', -4, 0, 0, 0, 0),
('7260', -3, 0, 0, 0, 0),
('7260', -2, 0, 0, 0, 0),
('7260', -1, 0, 0, 0, 0),
('7260', 0, 0, 0, 0, 0),
('7260', 1, 0, 0, 0, 0),
('7260', 2, 0, 0, 0, 0),
('7260', 3, 0, 0, 0, 0),
('7260', 4, 0, 0, 0, 0),
('7260', 5, 0, 0, 0, 0),
('7260', 6, 0, 0, 0, 0),
('7260', 7, 0, 0, 0, 0),
('7260', 8, 0, 0, 0, 0),
('7260', 9, 0, 0, 0, 0),
('7260', 10, 0, 0, 0, 0),
('7260', 11, 0, 0, 0, 0),
('7260', 12, 0, 0, 0, 0),
('7260', 13, 0, 0, 0, 0),
('7260', 14, 0, 0, 0, 0),
('7260', 15, 0, 0, 0, 0),
('7260', 16, 0, 0, 0, 0),
('7260', 17, 0, 0, 0, 0),
('7260', 18, 0, 0, 0, 0),
('7260', 19, 0, 0, 0, 0),
('7260', 20, 0, 0, 0, 0),
('7260', 21, 0, 0, 0, 0),
('7260', 22, 0, 0, 0, 0),
('7260', 23, 0, 0, 0, 0),
('7260', 24, 0, 0, 0, 0),
('7280', -13, 0, 0, 0, 0),
('7280', -12, 0, 0, 0, 0),
('7280', -11, 0, 0, 0, 0),
('7280', -10, 0, 0, 0, 0),
('7280', -9, 0, 0, 0, 0),
('7280', -8, 0, 0, 0, 0),
('7280', -7, 0, 0, 0, 0),
('7280', -6, 0, 0, 0, 0),
('7280', -5, 0, 0, 0, 0),
('7280', -4, 0, 0, 0, 0),
('7280', -3, 0, 0, 0, 0),
('7280', -2, 0, 0, 0, 0),
('7280', -1, 0, 0, 0, 0),
('7280', 0, 0, 0, 0, 0),
('7280', 1, 0, 0, 0, 0),
('7280', 2, 0, 0, 0, 0),
('7280', 3, 0, 0, 0, 0),
('7280', 4, 0, 0, 0, 0),
('7280', 5, 0, 0, 0, 0),
('7280', 6, 0, 0, 0, 0),
('7280', 7, 0, 0, 0, 0),
('7280', 8, 0, 0, 0, 0),
('7280', 9, 0, 0, 0, 0),
('7280', 10, 0, 0, 0, 0),
('7280', 11, 0, 0, 0, 0),
('7280', 12, 0, 0, 0, 0),
('7280', 13, 0, 0, 0, 0),
('7280', 14, 0, 0, 0, 0),
('7280', 15, 0, 0, 0, 0),
('7280', 16, 0, 0, 0, 0),
('7280', 17, 0, 0, 0, 0),
('7280', 18, 0, 0, 0, 0),
('7280', 19, 0, 0, 0, 0),
('7280', 20, 0, 0, 0, 0),
('7280', 21, 0, 0, 0, 0),
('7280', 22, 0, 0, 0, 0),
('7280', 23, 0, 0, 0, 0),
('7280', 24, 0, 0, 0, 0),
('7300', -13, 0, 0, 0, 0),
('7300', -12, 0, 0, 0, 0),
('7300', -11, 0, 0, 0, 0),
('7300', -10, 0, 0, 0, 0),
('7300', -9, 0, 0, 0, 0),
('7300', -8, 0, 0, 0, 0),
('7300', -7, 0, 0, 0, 0),
('7300', -6, 0, 0, 0, 0),
('7300', -5, 0, 0, 0, 0),
('7300', -4, 0, 0, 0, 0),
('7300', -3, 0, 0, 0, 0),
('7300', -2, 0, 0, 0, 0),
('7300', -1, 0, 0, 0, 0),
('7300', 0, 0, 0, 0, 0),
('7300', 1, 0, 0, 0, 0),
('7300', 2, 0, 0, 0, 0),
('7300', 3, 0, 0, 0, 0),
('7300', 4, 0, 0, 0, 0),
('7300', 5, 0, 0, 0, 0),
('7300', 6, 0, 0, 0, 0),
('7300', 7, 0, 0, 0, 0),
('7300', 8, 0, 0, 0, 0),
('7300', 9, 0, 0, 0, 0),
('7300', 10, 0, 0, 0, 0),
('7300', 11, 0, 0, 0, 0),
('7300', 12, 0, 0, 0, 0),
('7300', 13, 0, 0, 0, 0),
('7300', 14, 0, 0, 0, 0),
('7300', 15, 0, 0, 0, 0),
('7300', 16, 0, 0, 0, 0),
('7300', 17, 0, 0, 0, 0),
('7300', 18, 0, 0, 0, 0),
('7300', 19, 0, 0, 0, 0),
('7300', 20, 0, 0, 0, 0),
('7300', 21, 0, 0, 0, 0),
('7300', 22, 0, 0, 0, 0),
('7300', 23, 0, 0, 0, 0),
('7300', 24, 0, 0, 0, 0),
('7350', -13, 0, 0, 0, 0),
('7350', -12, 0, 0, 0, 0),
('7350', -11, 0, 0, 0, 0),
('7350', -10, 0, 0, 0, 0),
('7350', -9, 0, 0, 0, 0),
('7350', -8, 0, 0, 0, 0),
('7350', -7, 0, 0, 0, 0),
('7350', -6, 0, 0, 0, 0),
('7350', -5, 0, 0, 0, 0),
('7350', -4, 0, 0, 0, 0),
('7350', -3, 0, 0, 0, 0),
('7350', -2, 0, 0, 0, 0),
('7350', -1, 0, 0, 0, 0),
('7350', 0, 0, 0, 0, 0),
('7350', 1, 0, 0, 0, 0),
('7350', 2, 0, 0, 0, 0),
('7350', 3, 0, 0, 0, 0),
('7350', 4, 0, 0, 0, 0),
('7350', 5, 0, 0, 0, 0),
('7350', 6, 0, 0, 0, 0),
('7350', 7, 0, 0, 0, 0),
('7350', 8, 0, 0, 0, 0),
('7350', 9, 0, 0, 0, 0),
('7350', 10, 0, 0, 0, 0),
('7350', 11, 0, 0, 0, 0),
('7350', 12, 0, 0, 0, 0),
('7350', 13, 0, 0, 0, 0),
('7350', 14, 0, 0, 0, 0),
('7350', 15, 0, 0, 0, 0),
('7350', 16, 0, 0, 0, 0),
('7350', 17, 0, 0, 0, 0),
('7350', 18, 0, 0, 0, 0),
('7350', 19, 0, 0, 0, 0),
('7350', 20, 0, 0, 0, 0),
('7350', 21, 0, 0, 0, 0),
('7350', 22, 0, 0, 0, 0),
('7350', 23, 0, 0, 0, 0),
('7350', 24, 0, 0, 0, 0),
('7390', -13, 0, 0, 0, 0),
('7390', -12, 0, 0, 0, 0),
('7390', -11, 0, 0, 0, 0),
('7390', -10, 0, 0, 0, 0),
('7390', -9, 0, 0, 0, 0),
('7390', -8, 0, 0, 0, 0),
('7390', -7, 0, 0, 0, 0),
('7390', -6, 0, 0, 0, 0),
('7390', -5, 0, 0, 0, 0),
('7390', -4, 0, 0, 0, 0),
('7390', -3, 0, 0, 0, 0),
('7390', -2, 0, 0, 0, 0),
('7390', -1, 0, 0, 0, 0),
('7390', 0, 0, 0, 0, 0),
('7390', 1, 0, 0, 0, 0),
('7390', 2, 0, 0, 0, 0),
('7390', 3, 0, 0, 0, 0),
('7390', 4, 0, 0, 0, 0),
('7390', 5, 0, 0, 0, 0),
('7390', 6, 0, 0, 0, 0),
('7390', 7, 0, 0, 0, 0),
('7390', 8, 0, 0, 0, 0),
('7390', 9, 0, 0, 0, 0),
('7390', 10, 0, 0, 0, 0),
('7390', 11, 0, 0, 0, 0),
('7390', 12, 0, 0, 0, 0),
('7390', 13, 0, 0, 0, 0),
('7390', 14, 0, 0, 0, 0),
('7390', 15, 0, 0, 0, 0),
('7390', 16, 0, 0, 0, 0),
('7390', 17, 0, 0, 0, 0),
('7390', 18, 0, 0, 0, 0),
('7390', 19, 0, 0, 0, 0),
('7390', 20, 0, 0, 0, 0),
('7390', 21, 0, 0, 0, 0),
('7390', 22, 0, 0, 0, 0),
('7390', 23, 0, 0, 0, 0),
('7390', 24, 0, 0, 0, 0),
('7400', -13, 0, 0, 0, 0),
('7400', -12, 0, 0, 0, 0),
('7400', -11, 0, 0, 0, 0),
('7400', -10, 0, 0, 0, 0),
('7400', -9, 0, 0, 0, 0),
('7400', -8, 0, 0, 0, 0),
('7400', -7, 0, 0, 0, 0),
('7400', -6, 0, 0, 0, 0),
('7400', -5, 0, 0, 0, 0),
('7400', -4, 0, 0, 0, 0),
('7400', -3, 0, 0, 0, 0),
('7400', -2, 0, 0, 0, 0),
('7400', -1, 0, 0, 0, 0),
('7400', 0, 0, 0, 0, 0),
('7400', 1, 0, 0, 0, 0),
('7400', 2, 0, 0, 0, 0),
('7400', 3, 0, 0, 0, 0),
('7400', 4, 0, 0, 0, 0),
('7400', 5, 0, 0, 0, 0),
('7400', 6, 0, 0, 0, 0),
('7400', 7, 0, 0, 0, 0),
('7400', 8, 0, 0, 0, 0),
('7400', 9, 0, 0, 0, 0),
('7400', 10, 0, 0, 0, 0),
('7400', 11, 0, 0, 0, 0),
('7400', 12, 0, 0, 0, 0),
('7400', 13, 0, 0, 0, 0),
('7400', 14, 0, 0, 0, 0),
('7400', 15, 0, 0, 0, 0),
('7400', 16, 0, 0, 0, 0),
('7400', 17, 0, 0, 0, 0),
('7400', 18, 0, 0, 0, 0),
('7400', 19, 0, 0, 0, 0),
('7400', 20, 0, 0, 0, 0),
('7400', 21, 0, 0, 0, 0),
('7400', 22, 0, 0, 0, 0),
('7400', 23, 0, 0, 0, 0),
('7400', 24, 0, 0, 0, 0),
('7450', -13, 0, 0, 0, 0),
('7450', -12, 0, 0, 0, 0),
('7450', -11, 0, 0, 0, 0),
('7450', -10, 0, 0, 0, 0),
('7450', -9, 0, 0, 0, 0),
('7450', -8, 0, 0, 0, 0),
('7450', -7, 0, 0, 0, 0),
('7450', -6, 0, 0, 0, 0),
('7450', -5, 0, 0, 0, 0),
('7450', -4, 0, 0, 0, 0),
('7450', -3, 0, 0, 0, 0),
('7450', -2, 0, 0, 0, 0),
('7450', -1, 0, 0, 0, 0),
('7450', 0, 0, 0, 0, 0),
('7450', 1, 0, 0, 0, 0),
('7450', 2, 0, 0, 0, 0),
('7450', 3, 0, 0, 0, 0),
('7450', 4, 0, 0, 0, 0),
('7450', 5, 0, 0, 0, 0),
('7450', 6, 0, 0, 0, 0),
('7450', 7, 0, 0, 0, 0),
('7450', 8, 0, 0, 0, 0),
('7450', 9, 0, 0, 0, 0),
('7450', 10, 0, 0, 0, 0),
('7450', 11, 0, 0, 0, 0),
('7450', 12, 0, 0, 0, 0),
('7450', 13, 0, 0, 0, 0),
('7450', 14, 0, 0, 0, 0),
('7450', 15, 0, 0, 0, 0),
('7450', 16, 0, 0, 0, 0),
('7450', 17, 0, 0, 0, 0),
('7450', 18, 0, 0, 0, 0),
('7450', 19, 0, 0, 0, 0),
('7450', 20, 0, 0, 0, 0),
('7450', 21, 0, 0, 0, 0),
('7450', 22, 0, 0, 0, 0),
('7450', 23, 0, 0, 0, 0),
('7450', 24, 0, 0, 0, 0),
('7500', -13, 0, 0, 0, 0),
('7500', -12, 0, 0, 0, 0),
('7500', -11, 0, 0, 0, 0),
('7500', -10, 0, 0, 0, 0),
('7500', -9, 0, 0, 0, 0),
('7500', -8, 0, 0, 0, 0),
('7500', -7, 0, 0, 0, 0),
('7500', -6, 0, 0, 0, 0),
('7500', -5, 0, 0, 0, 0),
('7500', -4, 0, 0, 0, 0),
('7500', -3, 0, 0, 0, 0),
('7500', -2, 0, 0, 0, 0),
('7500', -1, 0, 0, 0, 0),
('7500', 0, 0, 0, 0, 0),
('7500', 1, 0, 0, 0, 0),
('7500', 2, 0, 0, 0, 0),
('7500', 3, 0, 0, 0, 0),
('7500', 4, 0, 0, 0, 0),
('7500', 5, 0, 0, 0, 0),
('7500', 6, 0, 0, 0, 0),
('7500', 7, 0, 0, 0, 0),
('7500', 8, 0, 0, 0, 0),
('7500', 9, 0, 0, 0, 0),
('7500', 10, 0, 0, 0, 0),
('7500', 11, 0, 0, 0, 0),
('7500', 12, 0, 0, 0, 0),
('7500', 13, 0, 0, 0, 0),
('7500', 14, 0, 0, 0, 0),
('7500', 15, 0, 0, 0, 0),
('7500', 16, 0, 0, 0, 0),
('7500', 17, 0, 0, 0, 0),
('7500', 18, 0, 0, 0, 0),
('7500', 19, 0, 0, 0, 0),
('7500', 20, 0, 0, 0, 0),
('7500', 21, 0, 0, 0, 0),
('7500', 22, 0, 0, 0, 0),
('7500', 23, 0, 0, 0, 0),
('7500', 24, 0, 0, 0, 0),
('7550', -13, 0, 0, 0, 0),
('7550', -12, 0, 0, 0, 0),
('7550', -11, 0, 0, 0, 0),
('7550', -10, 0, 0, 0, 0),
('7550', -9, 0, 0, 0, 0),
('7550', -8, 0, 0, 0, 0),
('7550', -7, 0, 0, 0, 0),
('7550', -6, 0, 0, 0, 0),
('7550', -5, 0, 0, 0, 0),
('7550', -4, 0, 0, 0, 0),
('7550', -3, 0, 0, 0, 0),
('7550', -2, 0, 0, 0, 0),
('7550', -1, 0, 0, 0, 0),
('7550', 0, 0, 0, 0, 0),
('7550', 1, 0, 0, 0, 0),
('7550', 2, 0, 0, 0, 0),
('7550', 3, 0, 0, 0, 0),
('7550', 4, 0, 0, 0, 0),
('7550', 5, 0, 0, 0, 0),
('7550', 6, 0, 0, 0, 0),
('7550', 7, 0, 0, 0, 0),
('7550', 8, 0, 0, 0, 0),
('7550', 9, 0, 0, 0, 0),
('7550', 10, 0, 0, 0, 0),
('7550', 11, 0, 0, 0, 0),
('7550', 12, 0, 0, 0, 0),
('7550', 13, 0, 0, 0, 0),
('7550', 14, 0, 0, 0, 0),
('7550', 15, 0, 0, 0, 0),
('7550', 16, 0, 0, 0, 0),
('7550', 17, 0, 0, 0, 0),
('7550', 18, 0, 0, 0, 0),
('7550', 19, 0, 0, 0, 0),
('7550', 20, 0, 0, 0, 0),
('7550', 21, 0, 0, 0, 0),
('7550', 22, 0, 0, 0, 0),
('7550', 23, 0, 0, 0, 0),
('7550', 24, 0, 0, 0, 0),
('7600', -13, 0, 0, 0, 0),
('7600', -12, 0, 0, 0, 0),
('7600', -11, 0, 0, 0, 0),
('7600', -10, 0, 0, 0, 0),
('7600', -9, 0, 0, 0, 0),
('7600', -8, 0, 0, 0, 0),
('7600', -7, 0, 0, 0, 0),
('7600', -6, 0, 0, 0, 0),
('7600', -5, 0, 0, 0, 0),
('7600', -4, 0, 0, 0, 0),
('7600', -3, 0, 0, 0, 0),
('7600', -2, 0, 0, 0, 0),
('7600', -1, 0, 0, 0, 0),
('7600', 0, 0, 0, 0, 0),
('7600', 1, 0, 0, 0, 0),
('7600', 2, 0, 0, 0, 0),
('7600', 3, 0, 0, 0, 0),
('7600', 4, 0, 0, 0, 0),
('7600', 5, 0, 0, 0, 0),
('7600', 6, 0, 0, 0, 0),
('7600', 7, 0, 0, 0, 0),
('7600', 8, 0, 0, 0, 0),
('7600', 9, 0, 0, 0, 0),
('7600', 10, 0, 0, 0, 0),
('7600', 11, 0, 0, 0, 0),
('7600', 12, 0, 0, 0, 0),
('7600', 13, 0, 0, 0, 0),
('7600', 14, 0, 0, 0, 0),
('7600', 15, 0, 0, 0, 0),
('7600', 16, 0, 0, 0, 0),
('7600', 17, 0, 0, 0, 0),
('7600', 18, 0, 0, 0, 0),
('7600', 19, 0, 0, 0, 0),
('7600', 20, 0, 0, 0, 0),
('7600', 21, 0, 0, 0, 0),
('7600', 22, 0, 0, 0, 0),
('7600', 23, 0, 0, 0, 0),
('7600', 24, 0, 0, 0, 0),
('7610', -13, 0, 0, 0, 0),
('7610', -12, 0, 0, 0, 0),
('7610', -11, 0, 0, 0, 0),
('7610', -10, 0, 0, 0, 0),
('7610', -9, 0, 0, 0, 0),
('7610', -8, 0, 0, 0, 0),
('7610', -7, 0, 0, 0, 0),
('7610', -6, 0, 0, 0, 0),
('7610', -5, 0, 0, 0, 0),
('7610', -4, 0, 0, 0, 0),
('7610', -3, 0, 0, 0, 0),
('7610', -2, 0, 0, 0, 0),
('7610', -1, 0, 0, 0, 0),
('7610', 0, 0, 0, 0, 0),
('7610', 1, 0, 0, 0, 0),
('7610', 2, 0, 0, 0, 0),
('7610', 3, 0, 0, 0, 0),
('7610', 4, 0, 0, 0, 0),
('7610', 5, 0, 0, 0, 0),
('7610', 6, 0, 0, 0, 0),
('7610', 7, 0, 0, 0, 0),
('7610', 8, 0, 0, 0, 0),
('7610', 9, 0, 0, 0, 0),
('7610', 10, 0, 0, 0, 0),
('7610', 11, 0, 0, 0, 0),
('7610', 12, 0, 0, 0, 0),
('7610', 13, 0, 0, 0, 0),
('7610', 14, 0, 0, 0, 0),
('7610', 15, 0, 0, 0, 0),
('7610', 16, 0, 0, 0, 0),
('7610', 17, 0, 0, 0, 0),
('7610', 18, 0, 0, 0, 0),
('7610', 19, 0, 0, 0, 0),
('7610', 20, 0, 0, 0, 0),
('7610', 21, 0, 0, 0, 0),
('7610', 22, 0, 0, 0, 0),
('7610', 23, 0, 0, 0, 0),
('7610', 24, 0, 0, 0, 0),
('7620', -13, 0, 0, 0, 0),
('7620', -12, 0, 0, 0, 0),
('7620', -11, 0, 0, 0, 0),
('7620', -10, 0, 0, 0, 0),
('7620', -9, 0, 0, 0, 0),
('7620', -8, 0, 0, 0, 0),
('7620', -7, 0, 0, 0, 0),
('7620', -6, 0, 0, 0, 0),
('7620', -5, 0, 0, 0, 0),
('7620', -4, 0, 0, 0, 0),
('7620', -3, 0, 0, 0, 0),
('7620', -2, 0, 0, 0, 0),
('7620', -1, 0, 0, 0, 0),
('7620', 0, 0, 0, 0, 0),
('7620', 1, 0, 0, 0, 0),
('7620', 2, 0, 0, 0, 0),
('7620', 3, 0, 0, 0, 0),
('7620', 4, 0, 0, 0, 0),
('7620', 5, 0, 0, 0, 0),
('7620', 6, 0, 0, 0, 0),
('7620', 7, 0, 0, 0, 0),
('7620', 8, 0, 0, 0, 0),
('7620', 9, 0, 0, 0, 0),
('7620', 10, 0, 0, 0, 0),
('7620', 11, 0, 0, 0, 0),
('7620', 12, 0, 0, 0, 0),
('7620', 13, 0, 0, 0, 0),
('7620', 14, 0, 0, 0, 0),
('7620', 15, 0, 0, 0, 0),
('7620', 16, 0, 0, 0, 0),
('7620', 17, 0, 0, 0, 0),
('7620', 18, 0, 0, 0, 0),
('7620', 19, 0, 0, 0, 0),
('7620', 20, 0, 0, 0, 0),
('7620', 21, 0, 0, 0, 0),
('7620', 22, 0, 0, 0, 0),
('7620', 23, 0, 0, 0, 0),
('7620', 24, 0, 0, 0, 0),
('7630', -13, 0, 0, 0, 0),
('7630', -12, 0, 0, 0, 0),
('7630', -11, 0, 0, 0, 0),
('7630', -10, 0, 0, 0, 0),
('7630', -9, 0, 0, 0, 0),
('7630', -8, 0, 0, 0, 0),
('7630', -7, 0, 0, 0, 0),
('7630', -6, 0, 0, 0, 0),
('7630', -5, 0, 0, 0, 0),
('7630', -4, 0, 0, 0, 0),
('7630', -3, 0, 0, 0, 0),
('7630', -2, 0, 0, 0, 0),
('7630', -1, 0, 0, 0, 0),
('7630', 0, 0, 0, 0, 0),
('7630', 1, 0, 0, 0, 0),
('7630', 2, 0, 0, 0, 0),
('7630', 3, 0, 0, 0, 0),
('7630', 4, 0, 0, 0, 0),
('7630', 5, 0, 0, 0, 0),
('7630', 6, 0, 0, 0, 0),
('7630', 7, 0, 0, 0, 0),
('7630', 8, 0, 0, 0, 0),
('7630', 9, 0, 0, 0, 0),
('7630', 10, 0, 0, 0, 0),
('7630', 11, 0, 0, 0, 0),
('7630', 12, 0, 0, 0, 0),
('7630', 13, 0, 0, 0, 0),
('7630', 14, 0, 0, 0, 0),
('7630', 15, 0, 0, 0, 0),
('7630', 16, 0, 0, 0, 0),
('7630', 17, 0, 0, 0, 0),
('7630', 18, 0, 0, 0, 0),
('7630', 19, 0, 0, 0, 0),
('7630', 20, 0, 0, 0, 0),
('7630', 21, 0, 0, 0, 0),
('7630', 22, 0, 0, 0, 0),
('7630', 23, 0, 0, 0, 0),
('7630', 24, 0, 0, 0, 0),
('7640', -13, 0, 0, 0, 0),
('7640', -12, 0, 0, 0, 0),
('7640', -11, 0, 0, 0, 0),
('7640', -10, 0, 0, 0, 0),
('7640', -9, 0, 0, 0, 0),
('7640', -8, 0, 0, 0, 0),
('7640', -7, 0, 0, 0, 0),
('7640', -6, 0, 0, 0, 0),
('7640', -5, 0, 0, 0, 0),
('7640', -4, 0, 0, 0, 0),
('7640', -3, 0, 0, 0, 0),
('7640', -2, 0, 0, 0, 0),
('7640', -1, 0, 0, 0, 0),
('7640', 0, 0, 0, 0, 0),
('7640', 1, 0, 0, 0, 0),
('7640', 2, 0, 0, 0, 0),
('7640', 3, 0, 0, 0, 0),
('7640', 4, 0, 0, 0, 0),
('7640', 5, 0, 0, 0, 0),
('7640', 6, 0, 0, 0, 0),
('7640', 7, 0, 0, 0, 0),
('7640', 8, 0, 0, 0, 0),
('7640', 9, 0, 0, 0, 0),
('7640', 10, 0, 0, 0, 0),
('7640', 11, 0, 0, 0, 0),
('7640', 12, 0, 0, 0, 0),
('7640', 13, 0, 0, 0, 0),
('7640', 14, 0, 0, 0, 0),
('7640', 15, 0, 0, 0, 0),
('7640', 16, 0, 0, 0, 0),
('7640', 17, 0, 0, 0, 0),
('7640', 18, 0, 0, 0, 0),
('7640', 19, 0, 0, 0, 0),
('7640', 20, 0, 0, 0, 0),
('7640', 21, 0, 0, 0, 0),
('7640', 22, 0, 0, 0, 0),
('7640', 23, 0, 0, 0, 0),
('7640', 24, 0, 0, 0, 0),
('7650', -13, 0, 0, 0, 0),
('7650', -12, 0, 0, 0, 0),
('7650', -11, 0, 0, 0, 0),
('7650', -10, 0, 0, 0, 0),
('7650', -9, 0, 0, 0, 0),
('7650', -8, 0, 0, 0, 0),
('7650', -7, 0, 0, 0, 0),
('7650', -6, 0, 0, 0, 0),
('7650', -5, 0, 0, 0, 0),
('7650', -4, 0, 0, 0, 0),
('7650', -3, 0, 0, 0, 0),
('7650', -2, 0, 0, 0, 0),
('7650', -1, 0, 0, 0, 0),
('7650', 0, 0, 0, 0, 0),
('7650', 1, 0, 0, 0, 0),
('7650', 2, 0, 0, 0, 0),
('7650', 3, 0, 0, 0, 0),
('7650', 4, 0, 0, 0, 0),
('7650', 5, 0, 0, 0, 0),
('7650', 6, 0, 0, 0, 0),
('7650', 7, 0, 0, 0, 0),
('7650', 8, 0, 0, 0, 0),
('7650', 9, 0, 0, 0, 0),
('7650', 10, 0, 0, 0, 0),
('7650', 11, 0, 0, 0, 0),
('7650', 12, 0, 0, 0, 0),
('7650', 13, 0, 0, 0, 0),
('7650', 14, 0, 0, 0, 0),
('7650', 15, 0, 0, 0, 0),
('7650', 16, 0, 0, 0, 0),
('7650', 17, 0, 0, 0, 0),
('7650', 18, 0, 0, 0, 0),
('7650', 19, 0, 0, 0, 0),
('7650', 20, 0, 0, 0, 0),
('7650', 21, 0, 0, 0, 0),
('7650', 22, 0, 0, 0, 0),
('7650', 23, 0, 0, 0, 0),
('7650', 24, 0, 0, 0, 0),
('7660', -13, 0, 0, 0, 0),
('7660', -12, 0, 0, 0, 0),
('7660', -11, 0, 0, 0, 0),
('7660', -10, 0, 0, 0, 0),
('7660', -9, 0, 0, 0, 0),
('7660', -8, 0, 0, 0, 0),
('7660', -7, 0, 0, 0, 0),
('7660', -6, 0, 0, 0, 0),
('7660', -5, 0, 0, 0, 0),
('7660', -4, 0, 0, 0, 0),
('7660', -3, 0, 0, 0, 0),
('7660', -2, 0, 0, 0, 0),
('7660', -1, 0, 0, 0, 0),
('7660', 0, 0, 0, 0, 0),
('7660', 1, 0, 0, 0, 0),
('7660', 2, 0, 0, 0, 0),
('7660', 3, 0, 0, 0, 0),
('7660', 4, 0, 0, 0, 0),
('7660', 5, 0, 0, 0, 0),
('7660', 6, 0, 0, 0, 0),
('7660', 7, 0, 0, 0, 0),
('7660', 8, 0, 0, 0, 0),
('7660', 9, 0, 0, 0, 0),
('7660', 10, 0, 0, 0, 0),
('7660', 11, 0, 0, 0, 0),
('7660', 12, 0, 0, 0, 0),
('7660', 13, 0, 0, 0, 0),
('7660', 14, 0, 0, 0, 0),
('7660', 15, 0, 0, 0, 0),
('7660', 16, 0, 0, 0, 0),
('7660', 17, 0, 0, 0, 0),
('7660', 18, 0, 0, 0, 0),
('7660', 19, 0, 0, 0, 0),
('7660', 20, 0, 0, 0, 0),
('7660', 21, 0, 0, 0, 0),
('7660', 22, 0, 0, 0, 0),
('7660', 23, 0, 0, 0, 0),
('7660', 24, 0, 0, 0, 0),
('7700', -13, 0, 0, 0, 0),
('7700', -12, 0, 0, 0, 0),
('7700', -11, 0, 0, 0, 0),
('7700', -10, 0, 0, 0, 0),
('7700', -9, 0, 0, 0, 0),
('7700', -8, 0, 0, 0, 0),
('7700', -7, 0, 0, 0, 0),
('7700', -6, 0, 0, 0, 0),
('7700', -5, 0, 0, 0, 0),
('7700', -4, 0, 0, 0, 0),
('7700', -3, 0, 0, 0, 0),
('7700', -2, 0, 0, 0, 0),
('7700', -1, 0, 0, 0, 0),
('7700', 0, 0, 0, 0, 0),
('7700', 1, 0, 0, 0, 0),
('7700', 2, 0, 0, 0, 0),
('7700', 3, 0, 0, 0, 0),
('7700', 4, 0, 0, 0, 0),
('7700', 5, 0, 0, 0, 0),
('7700', 6, 0, 0, 0, 0),
('7700', 7, 0, 0, 0, 0),
('7700', 8, 0, 0, 0, 0),
('7700', 9, 0, 0, 0, 0),
('7700', 10, 0, 0, 0, 0),
('7700', 11, 0, 0, 0, 0),
('7700', 12, 0, 0, 0, 0),
('7700', 13, 0, 0, 0, 0),
('7700', 14, 0, 0, 0, 0),
('7700', 15, 0, 0, 0, 0),
('7700', 16, 0, 0, 0, 0),
('7700', 17, 0, 0, 0, 0),
('7700', 18, 0, 0, 0, 0),
('7700', 19, 0, 0, 0, 0),
('7700', 20, 0, 0, 0, 0),
('7700', 21, 0, 0, 0, 0),
('7700', 22, 0, 0, 0, 0),
('7700', 23, 0, 0, 0, 0),
('7700', 24, 0, 0, 0, 0),
('7750', -13, 0, 0, 0, 0),
('7750', -12, 0, 0, 0, 0),
('7750', -11, 0, 0, 0, 0),
('7750', -10, 0, 0, 0, 0),
('7750', -9, 0, 0, 0, 0),
('7750', -8, 0, 0, 0, 0),
('7750', -7, 0, 0, 0, 0),
('7750', -6, 0, 0, 0, 0),
('7750', -5, 0, 0, 0, 0),
('7750', -4, 0, 0, 0, 0),
('7750', -3, 0, 0, 0, 0),
('7750', -2, 0, 0, 0, 0),
('7750', -1, 0, 0, 0, 0),
('7750', 0, 0, 0, 0, 0),
('7750', 1, 0, 0, 0, 0),
('7750', 2, 0, 0, 0, 0),
('7750', 3, 0, 0, 0, 0),
('7750', 4, 0, 0, 0, 0),
('7750', 5, 0, 0, 0, 0),
('7750', 6, 0, 0, 0, 0),
('7750', 7, 0, 0, 0, 0),
('7750', 8, 0, 0, 0, 0),
('7750', 9, 0, 0, 0, 0),
('7750', 10, 0, 0, 0, 0),
('7750', 11, 0, 0, 0, 0),
('7750', 12, 0, 0, 0, 0),
('7750', 13, 0, 0, 0, 0),
('7750', 14, 0, 0, 0, 0),
('7750', 15, 0, 0, 0, 0),
('7750', 16, 0, 0, 0, 0),
('7750', 17, 0, 0, 0, 0),
('7750', 18, 0, 0, 0, 0),
('7750', 19, 0, 0, 0, 0),
('7750', 20, 0, 0, 0, 0),
('7750', 21, 0, 0, 0, 0),
('7750', 22, 0, 0, 0, 0),
('7750', 23, 0, 0, 0, 0),
('7750', 24, 0, 0, 0, 0),
('7800', -13, 0, 0, 0, 0),
('7800', -12, 0, 0, 0, 0),
('7800', -11, 0, 0, 0, 0),
('7800', -10, 0, 0, 0, 0),
('7800', -9, 0, 0, 0, 0),
('7800', -8, 0, 0, 0, 0),
('7800', -7, 0, 0, 0, 0),
('7800', -6, 0, 0, 0, 0),
('7800', -5, 0, 0, 0, 0),
('7800', -4, 0, 0, 0, 0),
('7800', -3, 0, 0, 0, 0),
('7800', -2, 0, 0, 0, 0),
('7800', -1, 0, 0, 0, 0),
('7800', 0, 0, 0, 0, 0),
('7800', 1, 0, 0, 0, 0),
('7800', 2, 0, 0, 0, 0),
('7800', 3, 0, 0, 0, 0),
('7800', 4, 0, 0, 0, 0),
('7800', 5, 0, 0, 0, 0),
('7800', 6, 0, 0, 0, 0),
('7800', 7, 0, 0, 0, 0),
('7800', 8, 0, 0, 0, 0),
('7800', 9, 0, 0, 0, 0),
('7800', 10, 0, 0, 0, 0),
('7800', 11, 0, 0, 0, 0),
('7800', 12, 0, 0, 0, 0),
('7800', 13, 0, 0, 0, 0),
('7800', 14, 0, 0, 0, 0),
('7800', 15, 0, 0, 0, 0),
('7800', 16, 0, 0, 0, 0),
('7800', 17, 0, 0, 0, 0),
('7800', 18, 0, 0, 0, 0),
('7800', 19, 0, 0, 0, 0),
('7800', 20, 0, 0, 0, 0),
('7800', 21, 0, 0, 0, 0),
('7800', 22, 0, 0, 0, 0),
('7800', 23, 0, 0, 0, 0),
('7800', 24, 0, 0, 0, 0),
('7900', -13, 0, 0, 0, 0),
('7900', -12, 0, 0, 0, 0),
('7900', -11, 0, 0, 0, 0),
('7900', -10, 0, 0, 0, 0),
('7900', -9, 0, 0, 0, 0),
('7900', -8, 0, 0, 0, 0),
('7900', -7, 0, 0, 0, 0),
('7900', -6, 0, 0, 0, 0),
('7900', -5, 0, 0, 0, 0),
('7900', -4, 0, 0, 0, 0),
('7900', -3, 0, 0, 0, 0),
('7900', -2, 0, 0, 0, 0),
('7900', -1, 0, 0, 0, 0),
('7900', 0, 0, 0, 0, 0),
('7900', 1, 0, 0, 0, 0),
('7900', 2, 0, 0, 0, 0),
('7900', 3, 0, 0, 0, 0),
('7900', 4, 0, 0, 0, 0),
('7900', 5, 0, 0, 0, 0),
('7900', 6, 0, 0, 0, 0),
('7900', 7, 0, 0, 0, 0),
('7900', 8, 0, 0, 0, 0),
('7900', 9, 0, 0, 0, 0),
('7900', 10, 0, 0, 0, 0),
('7900', 11, 0, 0, 0, 0),
('7900', 12, 0, 0, 0, 0),
('7900', 13, 0, 0, 0, 0),
('7900', 14, 0, 0, 0, 0),
('7900', 15, 0, 0, 0, 0),
('7900', 16, 0, 0, 0, 0),
('7900', 17, 0, 0, 0, 0),
('7900', 18, 0, 0, 0, 0),
('7900', 19, 0, 0, 0, 0),
('7900', 20, 0, 0, 0, 0),
('7900', 21, 0, 0, 0, 0),
('7900', 22, 0, 0, 0, 0),
('7900', 23, 0, 0, 0, 0),
('7900', 24, 0, 0, 0, 0),
('8100', -13, 0, 0, 0, 0),
('8100', -12, 0, 0, 0, 0),
('8100', -11, 0, 0, 0, 0),
('8100', -10, 0, 0, 0, 0),
('8100', -9, 0, 0, 0, 0),
('8100', -8, 0, 0, 0, 0),
('8100', -7, 0, 0, 0, 0),
('8100', -6, 0, 0, 0, 0),
('8100', -5, 0, 0, 0, 0),
('8100', -4, 0, 0, 0, 0),
('8100', -3, 0, 0, 0, 0),
('8100', -2, 0, 0, 0, 0),
('8100', -1, 0, 0, 0, 0),
('8100', 0, 0, 0, 0, 0),
('8100', 1, 0, 0, 0, 0),
('8100', 2, 0, 0, 0, 0),
('8100', 3, 0, 0, 0, 0),
('8100', 4, 0, 0, 0, 0),
('8100', 5, 0, 0, 0, 0),
('8100', 6, 0, 0, 0, 0),
('8100', 7, 0, 0, 0, 0),
('8100', 8, 0, 0, 0, 0),
('8100', 9, 0, 0, 0, 0),
('8100', 10, 0, 0, 0, 0),
('8100', 11, 0, 0, 0, 0),
('8100', 12, 0, 0, 0, 0),
('8100', 13, 0, 0, 0, 0),
('8100', 14, 0, 0, 0, 0),
('8100', 15, 0, 0, 0, 0),
('8100', 16, 0, 0, 0, 0),
('8100', 17, 0, 0, 0, 0),
('8100', 18, 0, 0, 0, 0),
('8100', 19, 0, 0, 0, 0),
('8100', 20, 0, 0, 0, 0),
('8100', 21, 0, 0, 0, 0),
('8100', 22, 0, 0, 0, 0),
('8100', 23, 0, 0, 0, 0),
('8100', 24, 0, 0, 0, 0),
('8200', -13, 0, 0, 0, 0),
('8200', -12, 0, 0, 0, 0),
('8200', -11, 0, 0, 0, 0),
('8200', -10, 0, 0, 0, 0),
('8200', -9, 0, 0, 0, 0),
('8200', -8, 0, 0, 0, 0),
('8200', -7, 0, 0, 0, 0),
('8200', -6, 0, 0, 0, 0),
('8200', -5, 0, 0, 0, 0),
('8200', -4, 0, 0, 0, 0),
('8200', -3, 0, 0, 0, 0),
('8200', -2, 0, 0, 0, 0),
('8200', -1, 0, 0, 0, 0),
('8200', 0, 0, 0, 0, 0),
('8200', 1, 0, 0, 0, 0),
('8200', 2, 0, 0, 0, 0),
('8200', 3, 0, 0, 0, 0),
('8200', 4, 0, 0, 0, 0),
('8200', 5, 0, 0, 0, 0),
('8200', 6, 0, 0, 0, 0),
('8200', 7, 0, 0, 0, 0),
('8200', 8, 0, 0, 0, 0),
('8200', 9, 0, 0, 0, 0),
('8200', 10, 0, 0, 0, 0),
('8200', 11, 0, 0, 0, 0),
('8200', 12, 0, 0, 0, 0),
('8200', 13, 0, 0, 0, 0),
('8200', 14, 0, 0, 0, 0),
('8200', 15, 0, 0, 0, 0),
('8200', 16, 0, 0, 0, 0),
('8200', 17, 0, 0, 0, 0),
('8200', 18, 0, 0, 0, 0),
('8200', 19, 0, 0, 0, 0),
('8200', 20, 0, 0, 0, 0),
('8200', 21, 0, 0, 0, 0),
('8200', 22, 0, 0, 0, 0),
('8200', 23, 0, 0, 0, 0),
('8200', 24, 0, 0, 0, 0),
('8300', -13, 0, 0, 0, 0),
('8300', -12, 0, 0, 0, 0),
('8300', -11, 0, 0, 0, 0),
('8300', -10, 0, 0, 0, 0),
('8300', -9, 0, 0, 0, 0),
('8300', -8, 0, 0, 0, 0),
('8300', -7, 0, 0, 0, 0),
('8300', -6, 0, 0, 0, 0),
('8300', -5, 0, 0, 0, 0),
('8300', -4, 0, 0, 0, 0),
('8300', -3, 0, 0, 0, 0),
('8300', -2, 0, 0, 0, 0),
('8300', -1, 0, 0, 0, 0),
('8300', 0, 0, 0, 0, 0),
('8300', 1, 0, 0, 0, 0),
('8300', 2, 0, 0, 0, 0),
('8300', 3, 0, 0, 0, 0),
('8300', 4, 0, 0, 0, 0),
('8300', 5, 0, 0, 0, 0),
('8300', 6, 0, 0, 0, 0),
('8300', 7, 0, 0, 0, 0),
('8300', 8, 0, 0, 0, 0),
('8300', 9, 0, 0, 0, 0),
('8300', 10, 0, 0, 0, 0),
('8300', 11, 0, 0, 0, 0),
('8300', 12, 0, 0, 0, 0),
('8300', 13, 0, 0, 0, 0),
('8300', 14, 0, 0, 0, 0),
('8300', 15, 0, 0, 0, 0),
('8300', 16, 0, 0, 0, 0),
('8300', 17, 0, 0, 0, 0),
('8300', 18, 0, 0, 0, 0),
('8300', 19, 0, 0, 0, 0),
('8300', 20, 0, 0, 0, 0),
('8300', 21, 0, 0, 0, 0),
('8300', 22, 0, 0, 0, 0),
('8300', 23, 0, 0, 0, 0),
('8300', 24, 0, 0, 0, 0),
('8400', -13, 0, 0, 0, 0),
('8400', -12, 0, 0, 0, 0),
('8400', -11, 0, 0, 0, 0),
('8400', -10, 0, 0, 0, 0),
('8400', -9, 0, 0, 0, 0),
('8400', -8, 0, 0, 0, 0),
('8400', -7, 0, 0, 0, 0),
('8400', -6, 0, 0, 0, 0),
('8400', -5, 0, 0, 0, 0),
('8400', -4, 0, 0, 0, 0),
('8400', -3, 0, 0, 0, 0),
('8400', -2, 0, 0, 0, 0),
('8400', -1, 0, 0, 0, 0),
('8400', 0, 0, 0, 0, 0),
('8400', 1, 0, 0, 0, 0),
('8400', 2, 0, 0, 0, 0),
('8400', 3, 0, 0, 0, 0),
('8400', 4, 0, 0, 0, 0),
('8400', 5, 0, 0, 0, 0),
('8400', 6, 0, 0, 0, 0),
('8400', 7, 0, 0, 0, 0),
('8400', 8, 0, 0, 0, 0),
('8400', 9, 0, 0, 0, 0),
('8400', 10, 0, 0, 0, 0),
('8400', 11, 0, 0, 0, 0),
('8400', 12, 0, 0, 0, 0),
('8400', 13, 0, 0, 0, 0),
('8400', 14, 0, 0, 0, 0),
('8400', 15, 0, 0, 0, 0),
('8400', 16, 0, 0, 0, 0),
('8400', 17, 0, 0, 0, 0),
('8400', 18, 0, 0, 0, 0),
('8400', 19, 0, 0, 0, 0),
('8400', 20, 0, 0, 0, 0),
('8400', 21, 0, 0, 0, 0),
('8400', 22, 0, 0, 0, 0),
('8400', 23, 0, 0, 0, 0),
('8400', 24, 0, 0, 0, 0),
('8500', -13, 0, 0, 0, 0),
('8500', -12, 0, 0, 0, 0),
('8500', -11, 0, 0, 0, 0),
('8500', -10, 0, 0, 0, 0),
('8500', -9, 0, 0, 0, 0),
('8500', -8, 0, 0, 0, 0),
('8500', -7, 0, 0, 0, 0),
('8500', -6, 0, 0, 0, 0),
('8500', -5, 0, 0, 0, 0),
('8500', -4, 0, 0, 0, 0),
('8500', -3, 0, 0, 0, 0),
('8500', -2, 0, 0, 0, 0),
('8500', -1, 0, 0, 0, 0),
('8500', 0, 0, 0, 0, 0),
('8500', 1, 0, 0, 0, 0),
('8500', 2, 0, 0, 0, 0),
('8500', 3, 0, 0, 0, 0),
('8500', 4, 0, 0, 0, 0),
('8500', 5, 0, 0, 0, 0),
('8500', 6, 0, 0, 0, 0),
('8500', 7, 0, 0, 0, 0),
('8500', 8, 0, 0, 0, 0),
('8500', 9, 0, 0, 0, 0),
('8500', 10, 0, 0, 0, 0),
('8500', 11, 0, 0, 0, 0),
('8500', 12, 0, 0, 0, 0),
('8500', 13, 0, 0, 0, 0),
('8500', 14, 0, 0, 0, 0),
('8500', 15, 0, 0, 0, 0),
('8500', 16, 0, 0, 0, 0),
('8500', 17, 0, 0, 0, 0),
('8500', 18, 0, 0, 0, 0),
('8500', 19, 0, 0, 0, 0),
('8500', 20, 0, 0, 0, 0),
('8500', 21, 0, 0, 0, 0),
('8500', 22, 0, 0, 0, 0),
('8500', 23, 0, 0, 0, 0),
('8500', 24, 0, 0, 0, 0),
('8600', -13, 0, 0, 0, 0),
('8600', -12, 0, 0, 0, 0),
('8600', -11, 0, 0, 0, 0),
('8600', -10, 0, 0, 0, 0),
('8600', -9, 0, 0, 0, 0),
('8600', -8, 0, 0, 0, 0),
('8600', -7, 0, 0, 0, 0),
('8600', -6, 0, 0, 0, 0),
('8600', -5, 0, 0, 0, 0),
('8600', -4, 0, 0, 0, 0),
('8600', -3, 0, 0, 0, 0),
('8600', -2, 0, 0, 0, 0),
('8600', -1, 0, 0, 0, 0),
('8600', 0, 0, 0, 0, 0),
('8600', 1, 0, 0, 0, 0),
('8600', 2, 0, 0, 0, 0),
('8600', 3, 0, 0, 0, 0),
('8600', 4, 0, 0, 0, 0),
('8600', 5, 0, 0, 0, 0),
('8600', 6, 0, 0, 0, 0),
('8600', 7, 0, 0, 0, 0),
('8600', 8, 0, 0, 0, 0),
('8600', 9, 0, 0, 0, 0),
('8600', 10, 0, 0, 0, 0),
('8600', 11, 0, 0, 0, 0),
('8600', 12, 0, 0, 0, 0),
('8600', 13, 0, 0, 0, 0),
('8600', 14, 0, 0, 0, 0),
('8600', 15, 0, 0, 0, 0),
('8600', 16, 0, 0, 0, 0),
('8600', 17, 0, 0, 0, 0),
('8600', 18, 0, 0, 0, 0),
('8600', 19, 0, 0, 0, 0),
('8600', 20, 0, 0, 0, 0),
('8600', 21, 0, 0, 0, 0),
('8600', 22, 0, 0, 0, 0),
('8600', 23, 0, 0, 0, 0),
('8600', 24, 0, 0, 0, 0),
('8900', -13, 0, 0, 0, 0),
('8900', -12, 0, 0, 0, 0),
('8900', -11, 0, 0, 0, 0),
('8900', -10, 0, 0, 0, 0),
('8900', -9, 0, 0, 0, 0),
('8900', -8, 0, 0, 0, 0),
('8900', -7, 0, 0, 0, 0),
('8900', -6, 0, 0, 0, 0),
('8900', -5, 0, 0, 0, 0),
('8900', -4, 0, 0, 0, 0),
('8900', -3, 0, 0, 0, 0),
('8900', -2, 0, 0, 0, 0),
('8900', -1, 0, 0, 0, 0),
('8900', 0, 0, 0, 0, 0),
('8900', 1, 0, 0, 0, 0),
('8900', 2, 0, 0, 0, 0),
('8900', 3, 0, 0, 0, 0),
('8900', 4, 0, 0, 0, 0),
('8900', 5, 0, 0, 0, 0),
('8900', 6, 0, 0, 0, 0),
('8900', 7, 0, 0, 0, 0),
('8900', 8, 0, 0, 0, 0),
('8900', 9, 0, 0, 0, 0),
('8900', 10, 0, 0, 0, 0),
('8900', 11, 0, 0, 0, 0),
('8900', 12, 0, 0, 0, 0),
('8900', 13, 0, 0, 0, 0),
('8900', 14, 0, 0, 0, 0),
('8900', 15, 0, 0, 0, 0),
('8900', 16, 0, 0, 0, 0),
('8900', 17, 0, 0, 0, 0),
('8900', 18, 0, 0, 0, 0),
('8900', 19, 0, 0, 0, 0),
('8900', 20, 0, 0, 0, 0),
('8900', 21, 0, 0, 0, 0),
('8900', 22, 0, 0, 0, 0),
('8900', 23, 0, 0, 0, 0),
('8900', 24, 0, 0, 0, 0),
('9100', -13, 0, 0, 0, 0),
('9100', -12, 0, 0, 0, 0),
('9100', -11, 0, 0, 0, 0),
('9100', -10, 0, 0, 0, 0),
('9100', -9, 0, 0, 0, 0),
('9100', -8, 0, 0, 0, 0),
('9100', -7, 0, 0, 0, 0),
('9100', -6, 0, 0, 0, 0),
('9100', -5, 0, 0, 0, 0),
('9100', -4, 0, 0, 0, 0),
('9100', -3, 0, 0, 0, 0),
('9100', -2, 0, 0, 0, 0),
('9100', -1, 0, 0, 0, 0),
('9100', 0, 0, 0, 0, 0),
('9100', 1, 0, 0, 0, 0),
('9100', 2, 0, 0, 0, 0),
('9100', 3, 0, 0, 0, 0),
('9100', 4, 0, 0, 0, 0),
('9100', 5, 0, 0, 0, 0),
('9100', 6, 0, 0, 0, 0),
('9100', 7, 0, 0, 0, 0),
('9100', 8, 0, 0, 0, 0),
('9100', 9, 0, 0, 0, 0),
('9100', 10, 0, 0, 0, 0),
('9100', 11, 0, 0, 0, 0),
('9100', 12, 0, 0, 0, 0),
('9100', 13, 0, 0, 0, 0),
('9100', 14, 0, 0, 0, 0),
('9100', 15, 0, 0, 0, 0),
('9100', 16, 0, 0, 0, 0),
('9100', 17, 0, 0, 0, 0),
('9100', 18, 0, 0, 0, 0),
('9100', 19, 0, 0, 0, 0),
('9100', 20, 0, 0, 0, 0),
('9100', 21, 0, 0, 0, 0),
('9100', 22, 0, 0, 0, 0),
('9100', 23, 0, 0, 0, 0),
('9100', 24, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `chartmaster`
--

CREATE TABLE IF NOT EXISTS `chartmaster` (
  `accountcode` varchar(20) NOT NULL DEFAULT '0',
  `accountname` char(50) NOT NULL DEFAULT '',
  `group_` char(30) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `chartmaster`
--

INSERT INTO `chartmaster` (`accountcode`, `accountname`, `group_`) VALUES
('1010', 'Petty Cash', 'Current Assets'),
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
`id` int(11) NOT NULL,
  `area` char(3) NOT NULL DEFAULT '',
  `stkcat` varchar(6) NOT NULL DEFAULT '',
  `glcode` varchar(20) NOT NULL DEFAULT '0',
  `salestype` char(2) NOT NULL DEFAULT 'AN'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

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
  `freightact` varchar(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`coycode`, `coyname`, `gstno`, `companynumber`, `regoffice1`, `regoffice2`, `regoffice3`, `regoffice4`, `regoffice5`, `regoffice6`, `telephone`, `fax`, `email`, `currencydefault`, `debtorsact`, `pytdiscountact`, `creditorsact`, `payrollact`, `grnact`, `exchangediffact`, `purchasesexchangediffact`, `retainedearnings`, `gllink_debtors`, `gllink_creditors`, `gllink_stock`, `freightact`) VALUES
(1, 'Kairuki Referral Hospital', 'not entered yet', '', 'Mikocheni B', 'PO Box 123', 'Mikocheni', 'Dar-es-salaam', '', 'Tanzania', '+255717531539', '+255653302114', 'infor@kairukihospital.com', 'USD', '1100', '4900', '2100', '2400', '2150', '4200', '5200', '3500', 1, 1, 1, '5600');

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `confname` varchar(35) NOT NULL DEFAULT '',
  `confvalue` text NOT NULL
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
('DB_Maintenance_LastRun', '2015-05-28'),
('DefaultBlindPackNote', '1'),
('DefaultCreditLimit', '1000'),
('DefaultCustomerType', '1'),
('DefaultDateFormat', 'd/m/Y'),
('DefaultDisplayRecordsMax', '50'),
('DefaultFactoryLocation', 'MEL'),
('DefaultPriceList', '1'),
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
('UpdateCurrencyRatesDaily', '2015-05-28'),
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
  `quantity` double NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `contractcharges`
--

CREATE TABLE IF NOT EXISTS `contractcharges` (
`id` int(11) NOT NULL,
  `contractref` varchar(20) NOT NULL,
  `transtype` smallint(6) NOT NULL DEFAULT '20',
  `transno` int(11) NOT NULL DEFAULT '0',
  `amount` double NOT NULL DEFAULT '0',
  `narrative` text NOT NULL,
  `anticipated` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `contractreqts`
--

CREATE TABLE IF NOT EXISTS `contractreqts` (
`contractreqid` int(11) NOT NULL,
  `contractref` varchar(20) NOT NULL DEFAULT '0',
  `requirement` varchar(40) NOT NULL DEFAULT '',
  `quantity` double NOT NULL DEFAULT '1',
  `costperunit` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `exrate` double NOT NULL DEFAULT '1'
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
  `webcart` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'If 1 shown in weberp cart. if 0 no show'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`currency`, `currabrev`, `country`, `hundredsname`, `decimalplaces`, `rate`, `webcart`) VALUES
('Australian Dollars', 'AUD', 'Australia', 'cents', 2, 0, 0),
('Swiss Francs', 'CHF', 'Swizerland', 'centimes', 2, 0, 0),
('Euro', 'EUR', 'Euroland', 'cents', 2, 0, 1),
('Pounds', 'GBP', 'England', 'Pence', 2, 0, 0),
('Kenyian Shillings', 'KES', 'Kenya', 'none', 0, 0, 0),
('US Dollars', 'USD', 'United States', 'Cents', 2, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `custallocns`
--

CREATE TABLE IF NOT EXISTS `custallocns` (
`id` int(11) NOT NULL,
  `amt` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `datealloc` date NOT NULL DEFAULT '0000-00-00',
  `transid_allocfrom` int(11) NOT NULL DEFAULT '0',
  `transid_allocto` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `custbranchcode` varchar(30) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `custcontacts`
--

CREATE TABLE IF NOT EXISTS `custcontacts` (
`contid` int(11) NOT NULL,
  `debtorno` varchar(10) NOT NULL,
  `contactname` varchar(40) NOT NULL,
  `role` varchar(40) NOT NULL,
  `phoneno` varchar(20) NOT NULL,
  `notes` varchar(255) NOT NULL,
  `email` varchar(55) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `conversionfactor` double NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `custnotes`
--

CREATE TABLE IF NOT EXISTS `custnotes` (
`noteid` tinyint(4) NOT NULL,
  `debtorno` varchar(10) NOT NULL DEFAULT '0',
  `href` varchar(100) NOT NULL,
  `note` text NOT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `priority` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `language_id` varchar(10) NOT NULL DEFAULT 'en_GB.utf8'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `debtorsmaster`
--

INSERT INTO `debtorsmaster` (`debtorno`, `name`, `address1`, `address2`, `address3`, `address4`, `address5`, `address6`, `currcode`, `salestype`, `clientsince`, `holdreason`, `paymentterms`, `discount`, `pymtdiscount`, `lastpaid`, `lastpaiddate`, `creditlimit`, `invaddrbranch`, `discountcode`, `ediinvoices`, `ediorders`, `edireference`, `editransport`, `ediaddress`, `ediserveruser`, `ediserverpwd`, `taxref`, `customerpoline`, `typeid`, `language_id`) VALUES
('1', 'Adelard Kiliba', 'Mbezi Jogoo', '', '', '', '', 'Tanzania, United Rep. of', 'USD', '2', '2015-05-27 00:00:00', 1, '20', 0, 0, 0, NULL, 1000, 0, '', 0, 0, '', 'email', '', '', '', '', 0, 1, 'sw_KE.utf8'),
('2', 'Haruna Duniz', 'Kinondoni', '', 'Dar-es-salaam', '', '', 'Tanzania, United Rep. of', 'USD', '2', '2015-05-27 00:00:00', 1, 'CA', 0, 0, 0, NULL, 1000, 0, '', 0, 0, '', 'email', '', '', '', '', 0, 1, 'en_US.utf8'),
('3', 'Alex Ntimigihwa', 'Kijogole Road', '', 'Dar-es-salaam', '', '', 'Tanzania, United Rep. of', 'USD', '2', '2015-05-27 00:00:00', 1, '20', 0, 0, 0, NULL, 1000, 0, '', 0, 0, '', 'email', '', '', '', '', 0, 1, 'sw_KE.utf8'),
('4', 'Mohamed Kijilugola', 'Usukumizwe Avenue', '', 'Dar-es-salaam', '', '', 'Tanzania, United Rep. of', 'USD', '2', '2015-05-27 00:00:00', 1, 'CA', 0, 0, 0, NULL, 1000, 0, '', 0, 0, '', 'email', '', '', '', '', 0, 1, 'sw_KE.utf8');

-- --------------------------------------------------------

--
-- Table structure for table `debtortrans`
--

CREATE TABLE IF NOT EXISTS `debtortrans` (
`id` int(11) NOT NULL,
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
  `salesperson` varchar(4) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `debtortranstaxes`
--

CREATE TABLE IF NOT EXISTS `debtortranstaxes` (
  `debtortransid` int(11) NOT NULL DEFAULT '0',
  `taxauthid` tinyint(4) NOT NULL DEFAULT '0',
  `taxamount` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `debtortype`
--

CREATE TABLE IF NOT EXISTS `debtortype` (
`typeid` tinyint(4) NOT NULL,
  `typename` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `debtortype`
--

INSERT INTO `debtortype` (`typeid`, `typename`) VALUES
(1, 'Patients'),
(2, 'NHIF'),
(3, 'AAR'),
(4, 'TANESCO'),
(5, 'GPITG LTD'),
(6, 'Strategies'),
(7, 'UDSM');

-- --------------------------------------------------------

--
-- Table structure for table `debtortypenotes`
--

CREATE TABLE IF NOT EXISTS `debtortypenotes` (
`noteid` tinyint(4) NOT NULL,
  `typeid` tinyint(4) NOT NULL DEFAULT '0',
  `href` varchar(100) NOT NULL,
  `note` varchar(200) NOT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `priority` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `deliverydate` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE IF NOT EXISTS `departments` (
`departmentid` int(11) NOT NULL,
  `description` varchar(100) NOT NULL DEFAULT '',
  `authoriser` varchar(20) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `discountmatrix`
--

CREATE TABLE IF NOT EXISTS `discountmatrix` (
  `salestype` char(2) NOT NULL DEFAULT '',
  `discountcategory` char(2) NOT NULL DEFAULT '',
  `quantitybreak` int(11) NOT NULL DEFAULT '1',
  `discountrate` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ediitemmapping`
--

CREATE TABLE IF NOT EXISTS `ediitemmapping` (
  `supporcust` varchar(4) NOT NULL DEFAULT '',
  `partnercode` varchar(10) NOT NULL DEFAULT '',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `partnerstockid` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `edimessageformat`
--

CREATE TABLE IF NOT EXISTS `edimessageformat` (
`id` int(11) NOT NULL,
  `partnercode` varchar(10) NOT NULL DEFAULT '',
  `messagetype` varchar(6) NOT NULL DEFAULT '',
  `section` varchar(7) NOT NULL DEFAULT '',
  `sequenceno` int(11) NOT NULL DEFAULT '0',
  `linetext` varchar(70) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `edi_orders_segs`
--

CREATE TABLE IF NOT EXISTS `edi_orders_segs` (
`id` int(11) NOT NULL,
  `segtag` char(3) NOT NULL DEFAULT '',
  `seggroup` tinyint(4) NOT NULL DEFAULT '0',
  `maxoccur` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8;

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
  `parentseggroup` tinyint(4) NOT NULL DEFAULT '0'
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
`id` int(11) NOT NULL,
  `host` varchar(30) NOT NULL,
  `port` char(5) NOT NULL,
  `heloaddress` varchar(20) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(30) DEFAULT NULL,
  `timeout` int(11) DEFAULT '5',
  `companyname` varchar(50) DEFAULT NULL,
  `auth` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `factorcompanies`
--

CREATE TABLE IF NOT EXISTS `factorcompanies` (
`id` int(11) NOT NULL,
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
  `email` varchar(55) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `defaultdepntype` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fixedassetcategories`
--

INSERT INTO `fixedassetcategories` (`categoryid`, `categorydescription`, `costact`, `depnact`, `disposalact`, `accumdepnact`, `defaultdepnrate`, `defaultdepntype`) VALUES
('COMP20', 'Computers', '1650', '7500', '7500', '1700', 0.2, 1),
('COMP30', 'Server', '1730', '7500', '7500', '2720', 0.2, 1),
('SEC200', 'CCTV Cameras', '1700', '7500', '7500', '1700', 0.2, 1),
('SEC201', 'Computer Desks', '1700', '7500', '7500', '1700', 0.2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `fixedassetlocations`
--

CREATE TABLE IF NOT EXISTS `fixedassetlocations` (
  `locationid` char(6) NOT NULL DEFAULT '',
  `locationdescription` char(20) NOT NULL DEFAULT '',
  `parentlocationid` char(6) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fixedassetlocations`
--

INSERT INTO `fixedassetlocations` (`locationid`, `locationdescription`, `parentlocationid`) VALUES
('21', 'First Floor', ''),
('22', 'Second Floor', ''),
('23', 'Third Floor', ''),
('24', 'Forth Floor', '');

-- --------------------------------------------------------

--
-- Table structure for table `fixedassets`
--

CREATE TABLE IF NOT EXISTS `fixedassets` (
`assetid` int(11) NOT NULL,
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
  `disposaldate` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fixedassets`
--

INSERT INTO `fixedassets` (`assetid`, `serialno`, `barcode`, `assetlocation`, `cost`, `accumdepn`, `datepurchased`, `disposalproceeds`, `assetcategoryid`, `description`, `longdescription`, `depntype`, `depnrate`, `disposaldate`) VALUES
(2, '', 'WBGTK0A1RY90RN', '24', 0, 0, '0000-00-00', 0, 'COMP20', 'Laptop cables', 'Power cables for laptop computers', 0, 0.09, '0000-00-00'),
(3, '', '', '21', 0, 0, '0000-00-00', 0, 'SEC201', 'Computer Desks', 'Computer desks for use by reception unit', 0, 50, '0000-00-00'),
(4, '', '', '21', 0, 0, '0000-00-00', 0, 'COMP30', 'Computer Servers', 'Computer servers used by eHMS', 0, 70, '0000-00-00'),
(5, '', '', '21', 0, 0, '0000-00-00', 0, 'SEC200', 'CCTY Camera', 'CCTY Camera system used by the facility', 0, 70, '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `fixedassettasks`
--

CREATE TABLE IF NOT EXISTS `fixedassettasks` (
`taskid` int(11) NOT NULL,
  `assetid` int(11) NOT NULL,
  `taskdescription` text NOT NULL,
  `frequencydays` int(11) NOT NULL DEFAULT '365',
  `lastcompleted` date NOT NULL,
  `userresponsible` varchar(20) NOT NULL,
  `manager` varchar(20) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fixedassettrans`
--

CREATE TABLE IF NOT EXISTS `fixedassettrans` (
`id` int(11) NOT NULL,
  `assetid` int(11) NOT NULL,
  `transtype` tinyint(4) NOT NULL,
  `transdate` date NOT NULL,
  `transno` int(11) NOT NULL,
  `periodno` smallint(6) NOT NULL,
  `inputdate` date NOT NULL,
  `fixedassettranstype` varchar(8) NOT NULL,
  `amount` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `freightcosts`
--

CREATE TABLE IF NOT EXISTS `freightcosts` (
`shipcostfromid` int(11) NOT NULL,
  `locationfrom` varchar(5) NOT NULL DEFAULT '',
  `destinationcountry` varchar(40) NOT NULL,
  `destination` varchar(40) NOT NULL DEFAULT '',
  `shipperid` int(11) NOT NULL DEFAULT '0',
  `cubrate` double NOT NULL DEFAULT '0',
  `kgrate` double NOT NULL DEFAULT '0',
  `maxkgs` double NOT NULL DEFAULT '999999',
  `maxcub` double NOT NULL DEFAULT '999999',
  `fixedprice` double NOT NULL DEFAULT '0',
  `minimumchg` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `geocode_param`
--

CREATE TABLE IF NOT EXISTS `geocode_param` (
`geocodeid` tinyint(4) NOT NULL,
  `geocode_key` varchar(200) NOT NULL DEFAULT '',
  `center_long` varchar(20) NOT NULL DEFAULT '',
  `center_lat` varchar(20) NOT NULL DEFAULT '',
  `map_height` varchar(10) NOT NULL DEFAULT '',
  `map_width` varchar(10) NOT NULL DEFAULT '',
  `map_host` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gltrans`
--

CREATE TABLE IF NOT EXISTS `gltrans` (
`counterindex` int(11) NOT NULL,
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
  `tag` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `grns`
--

CREATE TABLE IF NOT EXISTS `grns` (
  `grnbatch` smallint(6) NOT NULL DEFAULT '0',
`grnno` int(11) NOT NULL,
  `podetailitem` int(11) NOT NULL DEFAULT '0',
  `itemcode` varchar(20) NOT NULL DEFAULT '',
  `deliverydate` date NOT NULL DEFAULT '0000-00-00',
  `itemdescription` varchar(100) NOT NULL DEFAULT '',
  `qtyrecd` double NOT NULL DEFAULT '0',
  `quantityinv` double NOT NULL DEFAULT '0',
  `supplierid` varchar(10) NOT NULL DEFAULT '',
  `stdcostunit` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `holdreasons`
--

CREATE TABLE IF NOT EXISTS `holdreasons` (
  `reasoncode` smallint(6) NOT NULL DEFAULT '1',
  `reasondescription` char(30) NOT NULL DEFAULT '',
  `dissallowinvoices` tinyint(4) NOT NULL DEFAULT '-1'
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
  `secroleid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `labelfields`
--

CREATE TABLE IF NOT EXISTS `labelfields` (
`labelfieldid` int(11) NOT NULL,
  `labelid` tinyint(4) NOT NULL,
  `fieldvalue` varchar(20) NOT NULL,
  `vpos` double NOT NULL DEFAULT '0',
  `hpos` double NOT NULL DEFAULT '0',
  `fontsize` tinyint(4) NOT NULL,
  `barcode` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `labels`
--

CREATE TABLE IF NOT EXISTS `labels` (
`labelid` tinyint(11) NOT NULL,
  `description` varchar(50) NOT NULL,
  `pagewidth` double NOT NULL DEFAULT '0',
  `pageheight` double NOT NULL DEFAULT '0',
  `height` double NOT NULL DEFAULT '0',
  `width` double NOT NULL DEFAULT '0',
  `topmargin` double NOT NULL DEFAULT '0',
  `leftmargin` double NOT NULL DEFAULT '0',
  `rowheight` double NOT NULL DEFAULT '0',
  `columnwidth` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `usedforwo` tinyint(4) NOT NULL DEFAULT '1'
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
  `canupd` tinyint(4) NOT NULL DEFAULT '0'
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
  `bin` varchar(10) NOT NULL DEFAULT ''
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
  `recloc` varchar(7) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores Shipments To And From Locations';

-- --------------------------------------------------------

--
-- Table structure for table `mailgroupdetails`
--

CREATE TABLE IF NOT EXISTS `mailgroupdetails` (
  `groupname` varchar(100) NOT NULL,
  `userid` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mailgroups`
--

CREATE TABLE IF NOT EXISTS `mailgroups` (
`id` int(11) NOT NULL,
  `groupname` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `manufacturers`
--

CREATE TABLE IF NOT EXISTS `manufacturers` (
`manufacturers_id` int(11) NOT NULL,
  `manufacturers_name` varchar(32) NOT NULL,
  `manufacturers_url` varchar(50) NOT NULL DEFAULT '',
  `manufacturers_image` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mrpcalendar`
--

CREATE TABLE IF NOT EXISTS `mrpcalendar` (
  `calendardate` date NOT NULL,
  `daynumber` int(6) NOT NULL,
  `manufacturingflag` smallint(6) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mrpdemands`
--

CREATE TABLE IF NOT EXISTS `mrpdemands` (
`demandid` int(11) NOT NULL,
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `mrpdemandtype` varchar(6) NOT NULL DEFAULT '',
  `quantity` double NOT NULL DEFAULT '0',
  `duedate` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mrpdemandtypes`
--

CREATE TABLE IF NOT EXISTS `mrpdemandtypes` (
  `mrpdemandtype` varchar(6) NOT NULL DEFAULT '',
  `description` char(30) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mrpplannedorders`
--

CREATE TABLE IF NOT EXISTS `mrpplannedorders` (
`id` int(11) NOT NULL,
  `part` char(20) DEFAULT NULL,
  `duedate` date DEFAULT NULL,
  `supplyquantity` double DEFAULT NULL,
  `ordertype` varchar(6) DEFAULT NULL,
  `orderno` int(11) DEFAULT NULL,
  `mrpdate` date DEFAULT NULL,
  `updateflag` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE IF NOT EXISTS `offers` (
`offerid` int(11) NOT NULL,
  `tenderid` int(11) NOT NULL DEFAULT '0',
  `supplierid` varchar(10) NOT NULL DEFAULT '',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `quantity` double NOT NULL DEFAULT '0',
  `uom` varchar(15) NOT NULL DEFAULT '',
  `price` double NOT NULL DEFAULT '0',
  `expirydate` date NOT NULL DEFAULT '0000-00-00',
  `currcode` char(3) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `can_or_bo` char(3) NOT NULL DEFAULT 'CAN'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `paymentmethods`
--

CREATE TABLE IF NOT EXISTS `paymentmethods` (
`paymentid` tinyint(4) NOT NULL,
  `paymentname` varchar(15) NOT NULL DEFAULT '',
  `paymenttype` int(11) NOT NULL DEFAULT '1',
  `receipttype` int(11) NOT NULL DEFAULT '1',
  `usepreprintedstationery` tinyint(4) NOT NULL DEFAULT '0',
  `opencashdrawer` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

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
  `dayinfollowingmonth` smallint(6) NOT NULL DEFAULT '0'
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
`counterindex` int(20) NOT NULL,
  `tabcode` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `codeexpense` varchar(20) NOT NULL,
  `amount` double NOT NULL,
  `authorized` date NOT NULL COMMENT 'date cash assigment was revised and authorized by authorizer from tabs table',
  `posted` tinyint(4) NOT NULL COMMENT 'has (or has not) been posted into gltrans',
  `notes` text NOT NULL,
  `receipt` text COMMENT 'filename or path to scanned receipt or code of receipt to find physical receipt if tax guys or auditors show up'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pcexpenses`
--

CREATE TABLE IF NOT EXISTS `pcexpenses` (
  `codeexpense` varchar(20) NOT NULL COMMENT 'code for the group',
  `description` varchar(50) NOT NULL COMMENT 'text description, e.g. meals, train tickets, fuel, etc',
  `glaccount` varchar(20) NOT NULL DEFAULT '0',
  `tag` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pctabexpenses`
--

CREATE TABLE IF NOT EXISTS `pctabexpenses` (
  `typetabcode` varchar(20) NOT NULL,
  `codeexpense` varchar(20) NOT NULL
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
  `glaccountpcash` varchar(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pctypetabs`
--

CREATE TABLE IF NOT EXISTS `pctypetabs` (
  `typetabcode` varchar(20) NOT NULL COMMENT 'code for the type of petty cash tab',
  `typetabdescription` varchar(50) NOT NULL COMMENT 'text description, e.g. tab for CEO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `periods`
--

CREATE TABLE IF NOT EXISTS `periods` (
  `periodno` smallint(6) NOT NULL DEFAULT '0',
  `lastdate_in_period` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `periods`
--

INSERT INTO `periods` (`periodno`, `lastdate_in_period`) VALUES
(-13, '2014-04-30'),
(-12, '2014-05-31'),
(-11, '2014-06-30'),
(-10, '2014-07-31'),
(-9, '2014-08-31'),
(-8, '2014-09-30'),
(-7, '2014-10-31'),
(-6, '2014-11-30'),
(-5, '2014-12-31'),
(-4, '2015-01-31'),
(-3, '2015-02-28'),
(-2, '2015-03-31'),
(-1, '2015-04-30'),
(0, '2015-05-31'),
(1, '2015-06-30'),
(2, '2015-07-31'),
(3, '2015-08-31'),
(4, '2015-09-30'),
(5, '2015-10-31'),
(6, '2015-11-30'),
(7, '2015-12-31'),
(8, '2016-01-31'),
(9, '2016-02-29'),
(10, '2016-03-31'),
(11, '2016-04-30'),
(12, '2016-05-31'),
(13, '2016-06-30'),
(14, '2016-07-31'),
(15, '2016-08-31'),
(16, '2016-09-30'),
(17, '2016-10-31'),
(18, '2016-11-30'),
(19, '2016-12-31'),
(20, '2017-01-31'),
(21, '2017-02-28'),
(22, '2017-03-31'),
(23, '2017-04-30'),
(24, '2017-05-31');

-- --------------------------------------------------------

--
-- Table structure for table `pickinglistdetails`
--

CREATE TABLE IF NOT EXISTS `pickinglistdetails` (
  `pickinglistno` int(11) NOT NULL DEFAULT '0',
  `pickinglistlineno` int(11) NOT NULL DEFAULT '0',
  `orderlineno` int(11) NOT NULL DEFAULT '0',
  `qtyexpected` double NOT NULL DEFAULT '0',
  `qtypicked` double NOT NULL DEFAULT '0'
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
  `deliverynotedate` date NOT NULL DEFAULT '0000-00-00'
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
  `enddate` date NOT NULL DEFAULT '9999-12-31'
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
  `enddate` date NOT NULL DEFAULT '9999-12-31'
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
  `active` tinyint(4) NOT NULL DEFAULT '1'
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
  `minorderqty` int(11) NOT NULL DEFAULT '1'
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
  `offhold` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `purchorderdetails`
--

CREATE TABLE IF NOT EXISTS `purchorderdetails` (
`podetailitem` int(11) NOT NULL,
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
  `conversionfactor` double NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `purchorders`
--

CREATE TABLE IF NOT EXISTS `purchorders` (
`orderno` int(11) NOT NULL,
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
  `port` varchar(40) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `qasamples`
--

CREATE TABLE IF NOT EXISTS `qasamples` (
`sampleid` int(11) NOT NULL,
  `prodspeckey` varchar(25) NOT NULL DEFAULT '',
  `lotkey` varchar(25) NOT NULL DEFAULT '',
  `identifier` varchar(10) NOT NULL DEFAULT '',
  `createdby` varchar(15) NOT NULL DEFAULT '',
  `sampledate` date NOT NULL DEFAULT '0000-00-00',
  `comments` varchar(255) NOT NULL DEFAULT '',
  `cert` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `qatests`
--

CREATE TABLE IF NOT EXISTS `qatests` (
`testid` int(11) NOT NULL,
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
  `active` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `recurringsalesorders`
--

CREATE TABLE IF NOT EXISTS `recurringsalesorders` (
`recurrorderno` int(11) NOT NULL,
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
  `autoinvoice` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `narrative` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `relateditems`
--

CREATE TABLE IF NOT EXISTS `relateditems` (
  `stockid` varchar(20) CHARACTER SET utf8 NOT NULL,
  `related` varchar(20) CHARACTER SET utf8 NOT NULL
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
  `constant` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reportfields`
--

CREATE TABLE IF NOT EXISTS `reportfields` (
`id` int(8) NOT NULL,
  `reportid` int(5) NOT NULL DEFAULT '0',
  `entrytype` varchar(15) NOT NULL DEFAULT '',
  `seqnum` int(3) NOT NULL DEFAULT '0',
  `fieldname` varchar(80) NOT NULL DEFAULT '',
  `displaydesc` varchar(25) NOT NULL DEFAULT '',
  `visible` enum('1','0') NOT NULL DEFAULT '1',
  `columnbreak` enum('1','0') NOT NULL DEFAULT '1',
  `params` text
) ENGINE=MyISAM AUTO_INCREMENT=1805 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reportheaders`
--

CREATE TABLE IF NOT EXISTS `reportheaders` (
`reportid` smallint(6) NOT NULL,
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
  `lower4` varchar(10) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
`id` int(5) NOT NULL,
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
  `table6criteria` varchar(75) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=136 DEFAULT CHARSET=utf8;

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
`id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `salescat`
--

CREATE TABLE IF NOT EXISTS `salescat` (
`salescatid` tinyint(4) NOT NULL,
  `parentcatid` tinyint(4) DEFAULT NULL,
  `salescatname` varchar(50) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1' COMMENT '1 if active 0 if inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `salescatprod`
--

CREATE TABLE IF NOT EXISTS `salescatprod` (
  `salescatid` tinyint(4) NOT NULL DEFAULT '0',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `manufacturers_id` int(11) NOT NULL,
  `featured` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `salescattranslations`
--

CREATE TABLE IF NOT EXISTS `salescattranslations` (
  `salescatid` tinyint(4) NOT NULL DEFAULT '0',
  `language_id` varchar(10) NOT NULL DEFAULT 'en_GB.utf8',
  `salescattranslation` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `salesglpostings`
--

CREATE TABLE IF NOT EXISTS `salesglpostings` (
`id` int(11) NOT NULL,
  `area` varchar(3) NOT NULL,
  `stkcat` varchar(6) NOT NULL DEFAULT '',
  `discountglcode` varchar(20) NOT NULL DEFAULT '0',
  `salesglcode` varchar(20) NOT NULL DEFAULT '0',
  `salestype` char(2) NOT NULL DEFAULT 'AN'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

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
  `current` tinyint(4) NOT NULL COMMENT 'Salesman current (1) or not (0)'
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
  `poline` varchar(10) DEFAULT NULL COMMENT 'Some Customers require acknowledgements with a PO line number for each sales line'
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
  `salesperson` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `salestypes`
--

CREATE TABLE IF NOT EXISTS `salestypes` (
  `typeabbrev` char(2) NOT NULL DEFAULT '',
  `sales_type` varchar(40) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `salestypes`
--

INSERT INTO `salestypes` (`typeabbrev`, `sales_type`) VALUES
('2', 'Cash Items'),
('1', 'Credit Items');

-- --------------------------------------------------------

--
-- Table structure for table `sampleresults`
--

CREATE TABLE IF NOT EXISTS `sampleresults` (
`resultid` bigint(20) NOT NULL,
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
  `manuallyadded` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `scripts`
--

CREATE TABLE IF NOT EXISTS `scripts` (
  `script` varchar(78) NOT NULL DEFAULT '',
  `pagesecurity` int(11) NOT NULL DEFAULT '1',
  `description` text NOT NULL
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
  `tokenid` int(11) NOT NULL DEFAULT '0'
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
`secroleid` int(11) NOT NULL,
  `secrolename` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

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
  `tokenname` text NOT NULL
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
`id` int(11) NOT NULL,
  `supplierno` varchar(10) NOT NULL,
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `categoryid` char(6) NOT NULL DEFAULT '',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `narrative` varchar(20) NOT NULL DEFAULT '',
  `rebatepercent` double NOT NULL DEFAULT '0',
  `rebateamount` double NOT NULL DEFAULT '0',
  `effectivefrom` date NOT NULL,
  `effectiveto` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `shipmentcharges`
--

CREATE TABLE IF NOT EXISTS `shipmentcharges` (
`shiptchgid` int(11) NOT NULL,
  `shiptref` int(11) NOT NULL DEFAULT '0',
  `transtype` smallint(6) NOT NULL DEFAULT '0',
  `transno` int(11) NOT NULL DEFAULT '0',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `value` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `closed` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `shippers`
--

CREATE TABLE IF NOT EXISTS `shippers` (
`shipper_id` int(11) NOT NULL,
  `shippername` char(40) NOT NULL DEFAULT '',
  `mincharge` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

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
  `defaulttaxcatid` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stockcatproperties`
--

CREATE TABLE IF NOT EXISTS `stockcatproperties` (
`stkcatpropid` int(11) NOT NULL,
  `categoryid` char(6) NOT NULL,
  `label` text NOT NULL,
  `controltype` tinyint(4) NOT NULL DEFAULT '0',
  `defaultvalue` varchar(100) NOT NULL DEFAULT '''''',
  `maximumvalue` double NOT NULL DEFAULT '999999999',
  `reqatsalesorder` tinyint(4) NOT NULL DEFAULT '0',
  `minimumvalue` double NOT NULL DEFAULT '-999999999',
  `numericvalue` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stockcheckfreeze`
--

CREATE TABLE IF NOT EXISTS `stockcheckfreeze` (
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `loccode` varchar(5) NOT NULL DEFAULT '',
  `qoh` double NOT NULL DEFAULT '0',
  `stockcheckdate` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stockcounts`
--

CREATE TABLE IF NOT EXISTS `stockcounts` (
`id` int(11) NOT NULL,
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `loccode` varchar(5) NOT NULL DEFAULT '',
  `qtycounted` double NOT NULL DEFAULT '0',
  `reference` varchar(20) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stockdescriptiontranslations`
--

CREATE TABLE IF NOT EXISTS `stockdescriptiontranslations` (
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `language_id` varchar(10) NOT NULL DEFAULT 'en_GB.utf8',
  `descriptiontranslation` varchar(50) DEFAULT NULL COMMENT 'Item''s short description',
  `longdescriptiontranslation` text COMMENT 'Item''s long description',
  `needsrevision` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stockitemproperties`
--

CREATE TABLE IF NOT EXISTS `stockitemproperties` (
  `stockid` varchar(20) NOT NULL,
  `stkcatpropid` int(11) NOT NULL,
  `value` varchar(50) NOT NULL
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
  `lastcostupdate` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stockmoves`
--

CREATE TABLE IF NOT EXISTS `stockmoves` (
`stkmoveno` int(11) NOT NULL,
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
  `narrative` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stockmovestaxes`
--

CREATE TABLE IF NOT EXISTS `stockmovestaxes` (
  `stkmoveno` int(11) NOT NULL DEFAULT '0',
  `taxauthid` tinyint(4) NOT NULL DEFAULT '0',
  `taxrate` double NOT NULL DEFAULT '0',
  `taxontax` tinyint(4) NOT NULL DEFAULT '0',
  `taxcalculationorder` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stockrequest`
--

CREATE TABLE IF NOT EXISTS `stockrequest` (
`dispatchid` int(11) NOT NULL,
  `loccode` varchar(5) NOT NULL DEFAULT '',
  `departmentid` int(11) NOT NULL DEFAULT '0',
  `despatchdate` date NOT NULL DEFAULT '0000-00-00',
  `authorised` tinyint(4) NOT NULL DEFAULT '0',
  `closed` tinyint(4) NOT NULL DEFAULT '0',
  `narrative` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `completed` tinyint(4) NOT NULL DEFAULT '0'
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
  `qualitytext` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stockserialmoves`
--

CREATE TABLE IF NOT EXISTS `stockserialmoves` (
`stkitmmoveno` int(11) NOT NULL,
  `stockmoveno` int(11) NOT NULL DEFAULT '0',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `serialno` varchar(30) NOT NULL DEFAULT '',
  `moveqty` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `suppallocs`
--

CREATE TABLE IF NOT EXISTS `suppallocs` (
`id` int(11) NOT NULL,
  `amt` double NOT NULL DEFAULT '0',
  `datealloc` date NOT NULL DEFAULT '0000-00-00',
  `transid_allocfrom` int(11) NOT NULL DEFAULT '0',
  `transid_allocto` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `ordercontact` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `suppliercontacts`
--

INSERT INTO `suppliercontacts` (`supplierid`, `contact`, `position`, `tel`, `fax`, `mobile`, `email`, `ordercontact`) VALUES
('12', '234', '', '', '', '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `supplierdiscounts`
--

CREATE TABLE IF NOT EXISTS `supplierdiscounts` (
`id` int(11) NOT NULL,
  `supplierno` varchar(10) NOT NULL,
  `stockid` varchar(20) NOT NULL,
  `discountnarrative` varchar(20) NOT NULL,
  `discountpercent` double NOT NULL,
  `discountamount` double NOT NULL,
  `effectivefrom` date NOT NULL,
  `effectiveto` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `url` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supplierid`, `suppname`, `address1`, `address2`, `address3`, `address4`, `address5`, `address6`, `supptype`, `lat`, `lng`, `currcode`, `suppliersince`, `paymentterms`, `lastpaid`, `lastpaiddate`, `bankact`, `bankref`, `bankpartics`, `remittance`, `taxgroupid`, `factorcompanyid`, `taxref`, `phn`, `port`, `email`, `fax`, `telephone`, `url`) VALUES
('12', '2333', '', '', '', '', '', '', 0, 0.000000, 0.000000, 'USD', '2015-05-28', '20', 0, NULL, '', '0', '', 0, 1, 0, '', '', '', '', '', '', ''),
('23', 'adek', '', '', '', '', '', '', 0, 0.000000, 0.000000, 'USD', '2015-05-27', '20', 0, NULL, '', '0', '', 0, 1, 0, '', '', '', '', '', '', ''),
('235', '455', '', '', '', '', '', '', 0, 0.000000, 0.000000, 'USD', '2015-05-27', '20', 0, NULL, '', '0', '', 0, 1, 0, '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `suppliertype`
--

CREATE TABLE IF NOT EXISTS `suppliertype` (
`typeid` tinyint(4) NOT NULL,
  `typename` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
`id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `supptranstaxes`
--

CREATE TABLE IF NOT EXISTS `supptranstaxes` (
  `supptransid` int(11) NOT NULL DEFAULT '0',
  `taxauthid` tinyint(4) NOT NULL DEFAULT '0',
  `taxamount` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `systypes`
--

CREATE TABLE IF NOT EXISTS `systypes` (
  `typeid` smallint(6) NOT NULL DEFAULT '0',
  `typename` char(50) NOT NULL DEFAULT '',
  `typeno` int(11) NOT NULL DEFAULT '1'
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
(43, 'Delete w/down asset', 1),
(44, 'Depreciation', 5),
(49, 'Import Fixed Assets', 0),
(50, 'Opening Balance', 0),
(500, 'Auto Debtor Number', 0),
(600, 'Auto Supplier Number', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
`tagref` tinyint(4) NOT NULL,
  `tagdescription` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`tagref`, `tagdescription`) VALUES
(1, 'stuff 2'),
(2, 'stuff 2');

-- --------------------------------------------------------

--
-- Table structure for table `taxauthorities`
--

CREATE TABLE IF NOT EXISTS `taxauthorities` (
`taxid` tinyint(4) NOT NULL,
  `description` varchar(20) NOT NULL DEFAULT '',
  `taxglcode` varchar(20) NOT NULL DEFAULT '0',
  `purchtaxglaccount` varchar(20) NOT NULL DEFAULT '0',
  `bank` varchar(50) NOT NULL DEFAULT '',
  `bankacctype` varchar(20) NOT NULL DEFAULT '',
  `bankacc` varchar(50) NOT NULL DEFAULT '',
  `bankswift` varchar(30) NOT NULL DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

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
  `taxrate` double NOT NULL DEFAULT '0'
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
`taxcatid` tinyint(4) NOT NULL,
  `taxcatname` varchar(30) NOT NULL DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

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
`taxgroupid` tinyint(4) NOT NULL,
  `taxgroupdescription` varchar(30) NOT NULL DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

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
  `taxontax` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `taxprovinces`
--

CREATE TABLE IF NOT EXISTS `taxprovinces` (
`taxprovinceid` tinyint(4) NOT NULL,
  `taxprovincename` varchar(30) NOT NULL DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

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
  `units` varchar(20) NOT NULL DEFAULT 'each'
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
  `requiredbydate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tendersuppliers`
--

CREATE TABLE IF NOT EXISTS `tendersuppliers` (
  `tenderid` int(11) NOT NULL DEFAULT '0',
  `supplierid` varchar(10) NOT NULL DEFAULT '',
  `email` varchar(40) NOT NULL DEFAULT '',
  `responded` int(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `unitsofmeasure`
--

CREATE TABLE IF NOT EXISTS `unitsofmeasure` (
`unitid` tinyint(4) NOT NULL,
  `unitname` varchar(15) NOT NULL DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

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
  `comments` longblob
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
  `autoissue` tinyint(4) NOT NULL DEFAULT '0'
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
  `setuphrs` decimal(10,0) NOT NULL DEFAULT '0'
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
  `closecomments` longblob
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
  `qualitytext` text NOT NULL
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
  `department` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `www_users`
--

INSERT INTO `www_users` (`userid`, `password`, `realname`, `customerid`, `supplierid`, `salesman`, `phone`, `email`, `defaultlocation`, `fullaccess`, `cancreatetender`, `lastvisitdate`, `branchcode`, `pagesize`, `modulesallowed`, `showdashboard`, `blocked`, `displayrecordsmax`, `theme`, `language`, `pdflanguage`, `department`) VALUES
('admin', '$2y$10$2DcQml/KLRkj76G1wusgs.X9qjmS9zJEXEezdwJiTC6hpyo46m5JC', 'Demonstration user', '', '', '', '', 'admin@weberp.org', 'MEL', 8, 1, '2015-05-29 11:08:19', '', 'A4', '1,1,1,1,1,1,1,1,1,1,1,', 0, 0, 50, 'xenos', 'en_US.utf8', 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accountgroups`
--
ALTER TABLE `accountgroups`
 ADD PRIMARY KEY (`groupname`), ADD KEY `SequenceInTB` (`sequenceintb`), ADD KEY `sectioninaccounts` (`sectioninaccounts`), ADD KEY `parentgroupname` (`parentgroupname`);

--
-- Indexes for table `accountsection`
--
ALTER TABLE `accountsection`
 ADD PRIMARY KEY (`sectionid`);

--
-- Indexes for table `areas`
--
ALTER TABLE `areas`
 ADD PRIMARY KEY (`areacode`);

--
-- Indexes for table `assetmanager`
--
ALTER TABLE `assetmanager`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `audittrail`
--
ALTER TABLE `audittrail`
 ADD KEY `UserID` (`userid`), ADD KEY `transactiondate` (`transactiondate`), ADD KEY `transactiondate_2` (`transactiondate`), ADD KEY `transactiondate_3` (`transactiondate`);

--
-- Indexes for table `bankaccounts`
--
ALTER TABLE `bankaccounts`
 ADD PRIMARY KEY (`accountcode`), ADD KEY `currcode` (`currcode`), ADD KEY `BankAccountName` (`bankaccountname`), ADD KEY `BankAccountNumber` (`bankaccountnumber`);

--
-- Indexes for table `banktrans`
--
ALTER TABLE `banktrans`
 ADD PRIMARY KEY (`banktransid`), ADD KEY `BankAct` (`bankact`,`ref`), ADD KEY `TransDate` (`transdate`), ADD KEY `TransType` (`banktranstype`), ADD KEY `Type` (`type`,`transno`), ADD KEY `CurrCode` (`currcode`), ADD KEY `ref` (`ref`);

--
-- Indexes for table `bom`
--
ALTER TABLE `bom`
 ADD PRIMARY KEY (`parent`,`component`,`workcentreadded`,`loccode`), ADD KEY `Component` (`component`), ADD KEY `EffectiveAfter` (`effectiveafter`), ADD KEY `EffectiveTo` (`effectiveto`), ADD KEY `LocCode` (`loccode`), ADD KEY `Parent` (`parent`,`effectiveafter`,`effectiveto`,`loccode`), ADD KEY `Parent_2` (`parent`), ADD KEY `WorkCentreAdded` (`workcentreadded`);

--
-- Indexes for table `chartdetails`
--
ALTER TABLE `chartdetails`
 ADD PRIMARY KEY (`accountcode`,`period`), ADD KEY `Period` (`period`);

--
-- Indexes for table `chartmaster`
--
ALTER TABLE `chartmaster`
 ADD PRIMARY KEY (`accountcode`), ADD KEY `AccountName` (`accountname`), ADD KEY `Group_` (`group_`);

--
-- Indexes for table `cogsglpostings`
--
ALTER TABLE `cogsglpostings`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `Area_StkCat` (`area`,`stkcat`,`salestype`), ADD KEY `Area` (`area`), ADD KEY `StkCat` (`stkcat`), ADD KEY `GLCode` (`glcode`), ADD KEY `SalesType` (`salestype`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
 ADD PRIMARY KEY (`coycode`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
 ADD PRIMARY KEY (`confname`);

--
-- Indexes for table `contractbom`
--
ALTER TABLE `contractbom`
 ADD PRIMARY KEY (`contractref`,`stockid`,`workcentreadded`), ADD KEY `Stockid` (`stockid`), ADD KEY `ContractRef` (`contractref`), ADD KEY `WorkCentreAdded` (`workcentreadded`);

--
-- Indexes for table `contractcharges`
--
ALTER TABLE `contractcharges`
 ADD PRIMARY KEY (`id`), ADD KEY `contractref` (`contractref`,`transtype`,`transno`), ADD KEY `contractcharges_ibfk_2` (`transtype`);

--
-- Indexes for table `contractreqts`
--
ALTER TABLE `contractreqts`
 ADD PRIMARY KEY (`contractreqid`), ADD KEY `ContractRef` (`contractref`);

--
-- Indexes for table `contracts`
--
ALTER TABLE `contracts`
 ADD PRIMARY KEY (`contractref`), ADD KEY `OrderNo` (`orderno`), ADD KEY `CategoryID` (`categoryid`), ADD KEY `Status` (`status`), ADD KEY `WO` (`wo`), ADD KEY `loccode` (`loccode`), ADD KEY `DebtorNo` (`debtorno`,`branchcode`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
 ADD PRIMARY KEY (`currabrev`), ADD KEY `Country` (`country`);

--
-- Indexes for table `custallocns`
--
ALTER TABLE `custallocns`
 ADD PRIMARY KEY (`id`), ADD KEY `DateAlloc` (`datealloc`), ADD KEY `TransID_AllocFrom` (`transid_allocfrom`), ADD KEY `TransID_AllocTo` (`transid_allocto`);

--
-- Indexes for table `custbranch`
--
ALTER TABLE `custbranch`
 ADD PRIMARY KEY (`branchcode`,`debtorno`), ADD KEY `BrName` (`brname`), ADD KEY `DebtorNo` (`debtorno`), ADD KEY `Salesman` (`salesman`), ADD KEY `Area` (`area`), ADD KEY `DefaultLocation` (`defaultlocation`), ADD KEY `DefaultShipVia` (`defaultshipvia`), ADD KEY `taxgroupid` (`taxgroupid`);

--
-- Indexes for table `custcontacts`
--
ALTER TABLE `custcontacts`
 ADD PRIMARY KEY (`contid`);

--
-- Indexes for table `custitem`
--
ALTER TABLE `custitem`
 ADD PRIMARY KEY (`debtorno`,`stockid`), ADD KEY `StockID` (`stockid`), ADD KEY `Debtorno` (`debtorno`);

--
-- Indexes for table `custnotes`
--
ALTER TABLE `custnotes`
 ADD PRIMARY KEY (`noteid`);

--
-- Indexes for table `debtorsmaster`
--
ALTER TABLE `debtorsmaster`
 ADD PRIMARY KEY (`debtorno`), ADD KEY `Currency` (`currcode`), ADD KEY `HoldReason` (`holdreason`), ADD KEY `Name` (`name`), ADD KEY `PaymentTerms` (`paymentterms`), ADD KEY `SalesType` (`salestype`), ADD KEY `EDIInvoices` (`ediinvoices`), ADD KEY `EDIOrders` (`ediorders`), ADD KEY `debtorsmaster_ibfk_5` (`typeid`);

--
-- Indexes for table `debtortrans`
--
ALTER TABLE `debtortrans`
 ADD PRIMARY KEY (`id`), ADD KEY `DebtorNo` (`debtorno`,`branchcode`), ADD KEY `Order_` (`order_`), ADD KEY `Prd` (`prd`), ADD KEY `Tpe` (`tpe`), ADD KEY `Type` (`type`), ADD KEY `Settled` (`settled`), ADD KEY `TranDate` (`trandate`), ADD KEY `TransNo` (`transno`), ADD KEY `Type_2` (`type`,`transno`), ADD KEY `EDISent` (`edisent`), ADD KEY `salesperson` (`salesperson`);

--
-- Indexes for table `debtortranstaxes`
--
ALTER TABLE `debtortranstaxes`
 ADD PRIMARY KEY (`debtortransid`,`taxauthid`), ADD KEY `taxauthid` (`taxauthid`);

--
-- Indexes for table `debtortype`
--
ALTER TABLE `debtortype`
 ADD PRIMARY KEY (`typeid`);

--
-- Indexes for table `debtortypenotes`
--
ALTER TABLE `debtortypenotes`
 ADD PRIMARY KEY (`noteid`);

--
-- Indexes for table `deliverynotes`
--
ALTER TABLE `deliverynotes`
 ADD PRIMARY KEY (`deliverynotenumber`,`deliverynotelineno`), ADD KEY `deliverynotes_ibfk_2` (`salesorderno`,`salesorderlineno`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
 ADD PRIMARY KEY (`departmentid`);

--
-- Indexes for table `discountmatrix`
--
ALTER TABLE `discountmatrix`
 ADD PRIMARY KEY (`salestype`,`discountcategory`,`quantitybreak`), ADD KEY `QuantityBreak` (`quantitybreak`), ADD KEY `DiscountCategory` (`discountcategory`), ADD KEY `SalesType` (`salestype`);

--
-- Indexes for table `ediitemmapping`
--
ALTER TABLE `ediitemmapping`
 ADD PRIMARY KEY (`supporcust`,`partnercode`,`stockid`), ADD KEY `PartnerCode` (`partnercode`), ADD KEY `StockID` (`stockid`), ADD KEY `PartnerStockID` (`partnerstockid`), ADD KEY `SuppOrCust` (`supporcust`);

--
-- Indexes for table `edimessageformat`
--
ALTER TABLE `edimessageformat`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `PartnerCode` (`partnercode`,`messagetype`,`sequenceno`), ADD KEY `Section` (`section`);

--
-- Indexes for table `edi_orders_segs`
--
ALTER TABLE `edi_orders_segs`
 ADD PRIMARY KEY (`id`), ADD KEY `SegTag` (`segtag`), ADD KEY `SegNo` (`seggroup`);

--
-- Indexes for table `edi_orders_seg_groups`
--
ALTER TABLE `edi_orders_seg_groups`
 ADD PRIMARY KEY (`seggroupno`);

--
-- Indexes for table `emailsettings`
--
ALTER TABLE `emailsettings`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `factorcompanies`
--
ALTER TABLE `factorcompanies`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `factor_name` (`coyname`);

--
-- Indexes for table `fixedassetcategories`
--
ALTER TABLE `fixedassetcategories`
 ADD PRIMARY KEY (`categoryid`);

--
-- Indexes for table `fixedassetlocations`
--
ALTER TABLE `fixedassetlocations`
 ADD PRIMARY KEY (`locationid`);

--
-- Indexes for table `fixedassets`
--
ALTER TABLE `fixedassets`
 ADD PRIMARY KEY (`assetid`);

--
-- Indexes for table `fixedassettasks`
--
ALTER TABLE `fixedassettasks`
 ADD PRIMARY KEY (`taskid`), ADD KEY `assetid` (`assetid`), ADD KEY `userresponsible` (`userresponsible`);

--
-- Indexes for table `fixedassettrans`
--
ALTER TABLE `fixedassettrans`
 ADD PRIMARY KEY (`id`), ADD KEY `assetid` (`assetid`,`transtype`,`transno`), ADD KEY `inputdate` (`inputdate`), ADD KEY `transdate` (`transdate`);

--
-- Indexes for table `freightcosts`
--
ALTER TABLE `freightcosts`
 ADD PRIMARY KEY (`shipcostfromid`), ADD KEY `Destination` (`destination`), ADD KEY `LocationFrom` (`locationfrom`), ADD KEY `ShipperID` (`shipperid`), ADD KEY `Destination_2` (`destination`,`locationfrom`,`shipperid`);

--
-- Indexes for table `geocode_param`
--
ALTER TABLE `geocode_param`
 ADD PRIMARY KEY (`geocodeid`);

--
-- Indexes for table `gltrans`
--
ALTER TABLE `gltrans`
 ADD PRIMARY KEY (`counterindex`), ADD KEY `Account` (`account`), ADD KEY `ChequeNo` (`chequeno`), ADD KEY `PeriodNo` (`periodno`), ADD KEY `Posted` (`posted`), ADD KEY `TranDate` (`trandate`), ADD KEY `TypeNo` (`typeno`), ADD KEY `Type_and_Number` (`type`,`typeno`), ADD KEY `JobRef` (`jobref`), ADD KEY `tag` (`tag`);

--
-- Indexes for table `grns`
--
ALTER TABLE `grns`
 ADD PRIMARY KEY (`grnno`), ADD KEY `DeliveryDate` (`deliverydate`), ADD KEY `ItemCode` (`itemcode`), ADD KEY `PODetailItem` (`podetailitem`), ADD KEY `SupplierID` (`supplierid`);

--
-- Indexes for table `holdreasons`
--
ALTER TABLE `holdreasons`
 ADD PRIMARY KEY (`reasoncode`), ADD KEY `ReasonDescription` (`reasondescription`);

--
-- Indexes for table `internalstockcatrole`
--
ALTER TABLE `internalstockcatrole`
 ADD PRIMARY KEY (`categoryid`,`secroleid`), ADD KEY `internalstockcatrole_ibfk_1` (`categoryid`), ADD KEY `internalstockcatrole_ibfk_2` (`secroleid`);

--
-- Indexes for table `labelfields`
--
ALTER TABLE `labelfields`
 ADD PRIMARY KEY (`labelfieldid`), ADD KEY `labelid` (`labelid`), ADD KEY `vpos` (`vpos`);

--
-- Indexes for table `labels`
--
ALTER TABLE `labels`
 ADD PRIMARY KEY (`labelid`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
 ADD PRIMARY KEY (`loccode`), ADD UNIQUE KEY `locationname` (`locationname`), ADD KEY `taxprovinceid` (`taxprovinceid`);

--
-- Indexes for table `locationusers`
--
ALTER TABLE `locationusers`
 ADD PRIMARY KEY (`loccode`,`userid`), ADD KEY `UserId` (`userid`);

--
-- Indexes for table `locstock`
--
ALTER TABLE `locstock`
 ADD PRIMARY KEY (`loccode`,`stockid`), ADD KEY `StockID` (`stockid`), ADD KEY `bin` (`bin`);

--
-- Indexes for table `loctransfers`
--
ALTER TABLE `loctransfers`
 ADD KEY `Reference` (`reference`,`stockid`), ADD KEY `ShipLoc` (`shiploc`), ADD KEY `RecLoc` (`recloc`), ADD KEY `StockID` (`stockid`);

--
-- Indexes for table `mailgroupdetails`
--
ALTER TABLE `mailgroupdetails`
 ADD KEY `userid` (`userid`), ADD KEY `groupname` (`groupname`);

--
-- Indexes for table `mailgroups`
--
ALTER TABLE `mailgroups`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `groupname` (`groupname`);

--
-- Indexes for table `manufacturers`
--
ALTER TABLE `manufacturers`
 ADD PRIMARY KEY (`manufacturers_id`), ADD KEY `manufacturers_name` (`manufacturers_name`);

--
-- Indexes for table `mrpcalendar`
--
ALTER TABLE `mrpcalendar`
 ADD PRIMARY KEY (`calendardate`), ADD KEY `daynumber` (`daynumber`);

--
-- Indexes for table `mrpdemands`
--
ALTER TABLE `mrpdemands`
 ADD PRIMARY KEY (`demandid`), ADD KEY `StockID` (`stockid`), ADD KEY `mrpdemands_ibfk_1` (`mrpdemandtype`);

--
-- Indexes for table `mrpdemandtypes`
--
ALTER TABLE `mrpdemandtypes`
 ADD PRIMARY KEY (`mrpdemandtype`);

--
-- Indexes for table `mrpplannedorders`
--
ALTER TABLE `mrpplannedorders`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
 ADD PRIMARY KEY (`offerid`), ADD KEY `offers_ibfk_1` (`supplierid`), ADD KEY `offers_ibfk_2` (`stockid`);

--
-- Indexes for table `orderdeliverydifferenceslog`
--
ALTER TABLE `orderdeliverydifferenceslog`
 ADD KEY `StockID` (`stockid`), ADD KEY `DebtorNo` (`debtorno`,`branch`), ADD KEY `Can_or_BO` (`can_or_bo`), ADD KEY `OrderNo` (`orderno`);

--
-- Indexes for table `paymentmethods`
--
ALTER TABLE `paymentmethods`
 ADD PRIMARY KEY (`paymentid`);

--
-- Indexes for table `paymentterms`
--
ALTER TABLE `paymentterms`
 ADD PRIMARY KEY (`termsindicator`), ADD KEY `DaysBeforeDue` (`daysbeforedue`), ADD KEY `DayInFollowingMonth` (`dayinfollowingmonth`);

--
-- Indexes for table `pcashdetails`
--
ALTER TABLE `pcashdetails`
 ADD PRIMARY KEY (`counterindex`);

--
-- Indexes for table `pcexpenses`
--
ALTER TABLE `pcexpenses`
 ADD PRIMARY KEY (`codeexpense`), ADD KEY `glaccount` (`glaccount`);

--
-- Indexes for table `pctabexpenses`
--
ALTER TABLE `pctabexpenses`
 ADD KEY `typetabcode` (`typetabcode`), ADD KEY `codeexpense` (`codeexpense`);

--
-- Indexes for table `pctabs`
--
ALTER TABLE `pctabs`
 ADD PRIMARY KEY (`tabcode`), ADD KEY `usercode` (`usercode`), ADD KEY `typetabcode` (`typetabcode`), ADD KEY `currency` (`currency`), ADD KEY `authorizer` (`authorizer`), ADD KEY `glaccountassignment` (`glaccountassignment`);

--
-- Indexes for table `pctypetabs`
--
ALTER TABLE `pctypetabs`
 ADD PRIMARY KEY (`typetabcode`);

--
-- Indexes for table `periods`
--
ALTER TABLE `periods`
 ADD PRIMARY KEY (`periodno`), ADD KEY `LastDate_in_Period` (`lastdate_in_period`);

--
-- Indexes for table `pickinglistdetails`
--
ALTER TABLE `pickinglistdetails`
 ADD PRIMARY KEY (`pickinglistno`,`pickinglistlineno`);

--
-- Indexes for table `pickinglists`
--
ALTER TABLE `pickinglists`
 ADD PRIMARY KEY (`pickinglistno`), ADD KEY `pickinglists_ibfk_1` (`orderno`);

--
-- Indexes for table `pricematrix`
--
ALTER TABLE `pricematrix`
 ADD PRIMARY KEY (`salestype`,`stockid`,`currabrev`,`quantitybreak`,`startdate`,`enddate`), ADD KEY `SalesType` (`salestype`), ADD KEY `currabrev` (`currabrev`), ADD KEY `stockid` (`stockid`);

--
-- Indexes for table `prices`
--
ALTER TABLE `prices`
 ADD PRIMARY KEY (`stockid`,`typeabbrev`,`currabrev`,`debtorno`,`branchcode`,`startdate`,`enddate`), ADD KEY `CurrAbrev` (`currabrev`), ADD KEY `DebtorNo` (`debtorno`), ADD KEY `StockID` (`stockid`), ADD KEY `TypeAbbrev` (`typeabbrev`);

--
-- Indexes for table `prodspecs`
--
ALTER TABLE `prodspecs`
 ADD PRIMARY KEY (`keyval`,`testid`), ADD KEY `testid` (`testid`);

--
-- Indexes for table `purchdata`
--
ALTER TABLE `purchdata`
 ADD PRIMARY KEY (`supplierno`,`stockid`,`effectivefrom`), ADD KEY `StockID` (`stockid`), ADD KEY `SupplierNo` (`supplierno`), ADD KEY `Preferred` (`preferred`);

--
-- Indexes for table `purchorderauth`
--
ALTER TABLE `purchorderauth`
 ADD PRIMARY KEY (`userid`,`currabrev`);

--
-- Indexes for table `purchorderdetails`
--
ALTER TABLE `purchorderdetails`
 ADD PRIMARY KEY (`podetailitem`), ADD KEY `DeliveryDate` (`deliverydate`), ADD KEY `GLCode` (`glcode`), ADD KEY `ItemCode` (`itemcode`), ADD KEY `JobRef` (`jobref`), ADD KEY `OrderNo` (`orderno`), ADD KEY `ShiptRef` (`shiptref`), ADD KEY `Completed` (`completed`);

--
-- Indexes for table `purchorders`
--
ALTER TABLE `purchorders`
 ADD PRIMARY KEY (`orderno`), ADD KEY `OrdDate` (`orddate`), ADD KEY `SupplierNo` (`supplierno`), ADD KEY `IntoStockLocation` (`intostocklocation`), ADD KEY `AllowPrintPO` (`allowprint`);

--
-- Indexes for table `qasamples`
--
ALTER TABLE `qasamples`
 ADD PRIMARY KEY (`sampleid`), ADD KEY `prodspeckey` (`prodspeckey`,`lotkey`);

--
-- Indexes for table `qatests`
--
ALTER TABLE `qatests`
 ADD PRIMARY KEY (`testid`), ADD KEY `name` (`name`), ADD KEY `groupname` (`groupby`,`name`);

--
-- Indexes for table `recurringsalesorders`
--
ALTER TABLE `recurringsalesorders`
 ADD PRIMARY KEY (`recurrorderno`), ADD KEY `debtorno` (`debtorno`), ADD KEY `orddate` (`orddate`), ADD KEY `ordertype` (`ordertype`), ADD KEY `locationindex` (`fromstkloc`), ADD KEY `branchcode` (`branchcode`,`debtorno`);

--
-- Indexes for table `recurrsalesorderdetails`
--
ALTER TABLE `recurrsalesorderdetails`
 ADD KEY `orderno` (`recurrorderno`), ADD KEY `stkcode` (`stkcode`);

--
-- Indexes for table `relateditems`
--
ALTER TABLE `relateditems`
 ADD PRIMARY KEY (`stockid`,`related`), ADD UNIQUE KEY `Related` (`related`,`stockid`);

--
-- Indexes for table `reportcolumns`
--
ALTER TABLE `reportcolumns`
 ADD PRIMARY KEY (`reportid`,`colno`);

--
-- Indexes for table `reportfields`
--
ALTER TABLE `reportfields`
 ADD PRIMARY KEY (`id`), ADD KEY `reportid` (`reportid`);

--
-- Indexes for table `reportheaders`
--
ALTER TABLE `reportheaders`
 ADD PRIMARY KEY (`reportid`), ADD KEY `ReportHeading` (`reportheading`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
 ADD PRIMARY KEY (`id`), ADD KEY `name` (`reportname`,`groupname`);

--
-- Indexes for table `salesanalysis`
--
ALTER TABLE `salesanalysis`
 ADD PRIMARY KEY (`id`), ADD KEY `CustBranch` (`custbranch`), ADD KEY `Cust` (`cust`), ADD KEY `PeriodNo` (`periodno`), ADD KEY `StkCategory` (`stkcategory`), ADD KEY `StockID` (`stockid`), ADD KEY `TypeAbbrev` (`typeabbrev`), ADD KEY `Area` (`area`), ADD KEY `BudgetOrActual` (`budgetoractual`), ADD KEY `Salesperson` (`salesperson`);

--
-- Indexes for table `salescat`
--
ALTER TABLE `salescat`
 ADD PRIMARY KEY (`salescatid`);

--
-- Indexes for table `salescatprod`
--
ALTER TABLE `salescatprod`
 ADD PRIMARY KEY (`salescatid`,`stockid`), ADD KEY `salescatid` (`salescatid`), ADD KEY `stockid` (`stockid`), ADD KEY `manufacturer_id` (`manufacturers_id`);

--
-- Indexes for table `salescattranslations`
--
ALTER TABLE `salescattranslations`
 ADD PRIMARY KEY (`salescatid`,`language_id`);

--
-- Indexes for table `salesglpostings`
--
ALTER TABLE `salesglpostings`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `Area_StkCat` (`area`,`stkcat`,`salestype`), ADD KEY `Area` (`area`), ADD KEY `StkCat` (`stkcat`), ADD KEY `SalesType` (`salestype`);

--
-- Indexes for table `salesman`
--
ALTER TABLE `salesman`
 ADD PRIMARY KEY (`salesmancode`);

--
-- Indexes for table `salesorderdetails`
--
ALTER TABLE `salesorderdetails`
 ADD PRIMARY KEY (`orderlineno`,`orderno`), ADD KEY `OrderNo` (`orderno`), ADD KEY `StkCode` (`stkcode`), ADD KEY `Completed` (`completed`);

--
-- Indexes for table `salesorders`
--
ALTER TABLE `salesorders`
 ADD PRIMARY KEY (`orderno`), ADD KEY `DebtorNo` (`debtorno`), ADD KEY `OrdDate` (`orddate`), ADD KEY `OrderType` (`ordertype`), ADD KEY `LocationIndex` (`fromstkloc`), ADD KEY `BranchCode` (`branchcode`,`debtorno`), ADD KEY `ShipVia` (`shipvia`), ADD KEY `quotation` (`quotation`), ADD KEY `poplaced` (`poplaced`), ADD KEY `salesperson` (`salesperson`);

--
-- Indexes for table `salestypes`
--
ALTER TABLE `salestypes`
 ADD PRIMARY KEY (`typeabbrev`), ADD KEY `Sales_Type` (`sales_type`);

--
-- Indexes for table `sampleresults`
--
ALTER TABLE `sampleresults`
 ADD PRIMARY KEY (`resultid`), ADD KEY `sampleid` (`sampleid`), ADD KEY `testid` (`testid`);

--
-- Indexes for table `scripts`
--
ALTER TABLE `scripts`
 ADD PRIMARY KEY (`script`);

--
-- Indexes for table `securitygroups`
--
ALTER TABLE `securitygroups`
 ADD PRIMARY KEY (`secroleid`,`tokenid`), ADD KEY `secroleid` (`secroleid`), ADD KEY `tokenid` (`tokenid`);

--
-- Indexes for table `securityroles`
--
ALTER TABLE `securityroles`
 ADD PRIMARY KEY (`secroleid`);

--
-- Indexes for table `securitytokens`
--
ALTER TABLE `securitytokens`
 ADD PRIMARY KEY (`tokenid`);

--
-- Indexes for table `sellthroughsupport`
--
ALTER TABLE `sellthroughsupport`
 ADD PRIMARY KEY (`id`), ADD KEY `supplierno` (`supplierno`), ADD KEY `debtorno` (`debtorno`), ADD KEY `effectivefrom` (`effectivefrom`), ADD KEY `effectiveto` (`effectiveto`), ADD KEY `stockid` (`stockid`), ADD KEY `categoryid` (`categoryid`);

--
-- Indexes for table `shipmentcharges`
--
ALTER TABLE `shipmentcharges`
 ADD PRIMARY KEY (`shiptchgid`), ADD KEY `TransType` (`transtype`,`transno`), ADD KEY `ShiptRef` (`shiptref`), ADD KEY `StockID` (`stockid`), ADD KEY `TransType_2` (`transtype`);

--
-- Indexes for table `shipments`
--
ALTER TABLE `shipments`
 ADD PRIMARY KEY (`shiptref`), ADD KEY `ETA` (`eta`), ADD KEY `SupplierID` (`supplierid`), ADD KEY `ShipperRef` (`voyageref`), ADD KEY `Vessel` (`vessel`);

--
-- Indexes for table `shippers`
--
ALTER TABLE `shippers`
 ADD PRIMARY KEY (`shipper_id`);

--
-- Indexes for table `stockcategory`
--
ALTER TABLE `stockcategory`
 ADD PRIMARY KEY (`categoryid`), ADD KEY `CategoryDescription` (`categorydescription`), ADD KEY `StockType` (`stocktype`);

--
-- Indexes for table `stockcatproperties`
--
ALTER TABLE `stockcatproperties`
 ADD PRIMARY KEY (`stkcatpropid`), ADD KEY `categoryid` (`categoryid`);

--
-- Indexes for table `stockcheckfreeze`
--
ALTER TABLE `stockcheckfreeze`
 ADD PRIMARY KEY (`stockid`,`loccode`), ADD KEY `LocCode` (`loccode`);

--
-- Indexes for table `stockcounts`
--
ALTER TABLE `stockcounts`
 ADD PRIMARY KEY (`id`), ADD KEY `StockID` (`stockid`), ADD KEY `LocCode` (`loccode`);

--
-- Indexes for table `stockdescriptiontranslations`
--
ALTER TABLE `stockdescriptiontranslations`
 ADD PRIMARY KEY (`stockid`,`language_id`);

--
-- Indexes for table `stockitemproperties`
--
ALTER TABLE `stockitemproperties`
 ADD PRIMARY KEY (`stockid`,`stkcatpropid`), ADD KEY `stockid` (`stockid`), ADD KEY `value` (`value`), ADD KEY `stkcatpropid` (`stkcatpropid`);

--
-- Indexes for table `stockmaster`
--
ALTER TABLE `stockmaster`
 ADD PRIMARY KEY (`stockid`), ADD KEY `CategoryID` (`categoryid`), ADD KEY `Description` (`description`), ADD KEY `MBflag` (`mbflag`), ADD KEY `StockID` (`stockid`,`categoryid`), ADD KEY `Controlled` (`controlled`), ADD KEY `DiscountCategory` (`discountcategory`), ADD KEY `taxcatid` (`taxcatid`);

--
-- Indexes for table `stockmoves`
--
ALTER TABLE `stockmoves`
 ADD PRIMARY KEY (`stkmoveno`), ADD KEY `DebtorNo` (`debtorno`), ADD KEY `LocCode` (`loccode`), ADD KEY `Prd` (`prd`), ADD KEY `StockID_2` (`stockid`), ADD KEY `TranDate` (`trandate`), ADD KEY `TransNo` (`transno`), ADD KEY `Type` (`type`), ADD KEY `Show_On_Inv_Crds` (`show_on_inv_crds`), ADD KEY `Hide` (`hidemovt`), ADD KEY `reference` (`reference`);

--
-- Indexes for table `stockmovestaxes`
--
ALTER TABLE `stockmovestaxes`
 ADD PRIMARY KEY (`stkmoveno`,`taxauthid`), ADD KEY `taxauthid` (`taxauthid`), ADD KEY `calculationorder` (`taxcalculationorder`);

--
-- Indexes for table `stockrequest`
--
ALTER TABLE `stockrequest`
 ADD PRIMARY KEY (`dispatchid`), ADD KEY `loccode` (`loccode`), ADD KEY `departmentid` (`departmentid`);

--
-- Indexes for table `stockrequestitems`
--
ALTER TABLE `stockrequestitems`
 ADD PRIMARY KEY (`dispatchitemsid`,`dispatchid`), ADD KEY `dispatchid` (`dispatchid`), ADD KEY `stockid` (`stockid`);

--
-- Indexes for table `stockserialitems`
--
ALTER TABLE `stockserialitems`
 ADD PRIMARY KEY (`stockid`,`serialno`,`loccode`), ADD KEY `StockID` (`stockid`), ADD KEY `LocCode` (`loccode`), ADD KEY `serialno` (`serialno`);

--
-- Indexes for table `stockserialmoves`
--
ALTER TABLE `stockserialmoves`
 ADD PRIMARY KEY (`stkitmmoveno`), ADD KEY `StockMoveNo` (`stockmoveno`), ADD KEY `StockID_SN` (`stockid`,`serialno`), ADD KEY `serialno` (`serialno`);

--
-- Indexes for table `suppallocs`
--
ALTER TABLE `suppallocs`
 ADD PRIMARY KEY (`id`), ADD KEY `TransID_AllocFrom` (`transid_allocfrom`), ADD KEY `TransID_AllocTo` (`transid_allocto`), ADD KEY `DateAlloc` (`datealloc`);

--
-- Indexes for table `suppliercontacts`
--
ALTER TABLE `suppliercontacts`
 ADD PRIMARY KEY (`supplierid`,`contact`), ADD KEY `Contact` (`contact`), ADD KEY `SupplierID` (`supplierid`);

--
-- Indexes for table `supplierdiscounts`
--
ALTER TABLE `supplierdiscounts`
 ADD PRIMARY KEY (`id`), ADD KEY `supplierno` (`supplierno`), ADD KEY `effectivefrom` (`effectivefrom`), ADD KEY `effectiveto` (`effectiveto`), ADD KEY `stockid` (`stockid`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
 ADD PRIMARY KEY (`supplierid`), ADD KEY `CurrCode` (`currcode`), ADD KEY `PaymentTerms` (`paymentterms`), ADD KEY `SuppName` (`suppname`), ADD KEY `taxgroupid` (`taxgroupid`);

--
-- Indexes for table `suppliertype`
--
ALTER TABLE `suppliertype`
 ADD PRIMARY KEY (`typeid`);

--
-- Indexes for table `supptrans`
--
ALTER TABLE `supptrans`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `TypeTransNo` (`transno`,`type`), ADD KEY `DueDate` (`duedate`), ADD KEY `Hold` (`hold`), ADD KEY `SupplierNo` (`supplierno`), ADD KEY `Settled` (`settled`), ADD KEY `SupplierNo_2` (`supplierno`,`suppreference`), ADD KEY `SuppReference` (`suppreference`), ADD KEY `TranDate` (`trandate`), ADD KEY `TransNo` (`transno`), ADD KEY `Type` (`type`);

--
-- Indexes for table `supptranstaxes`
--
ALTER TABLE `supptranstaxes`
 ADD PRIMARY KEY (`supptransid`,`taxauthid`), ADD KEY `taxauthid` (`taxauthid`);

--
-- Indexes for table `systypes`
--
ALTER TABLE `systypes`
 ADD PRIMARY KEY (`typeid`), ADD KEY `TypeNo` (`typeno`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
 ADD PRIMARY KEY (`tagref`);

--
-- Indexes for table `taxauthorities`
--
ALTER TABLE `taxauthorities`
 ADD PRIMARY KEY (`taxid`), ADD KEY `TaxGLCode` (`taxglcode`), ADD KEY `PurchTaxGLAccount` (`purchtaxglaccount`);

--
-- Indexes for table `taxauthrates`
--
ALTER TABLE `taxauthrates`
 ADD PRIMARY KEY (`taxauthority`,`dispatchtaxprovince`,`taxcatid`), ADD KEY `TaxAuthority` (`taxauthority`), ADD KEY `dispatchtaxprovince` (`dispatchtaxprovince`), ADD KEY `taxcatid` (`taxcatid`);

--
-- Indexes for table `taxcategories`
--
ALTER TABLE `taxcategories`
 ADD PRIMARY KEY (`taxcatid`);

--
-- Indexes for table `taxgroups`
--
ALTER TABLE `taxgroups`
 ADD PRIMARY KEY (`taxgroupid`);

--
-- Indexes for table `taxgrouptaxes`
--
ALTER TABLE `taxgrouptaxes`
 ADD PRIMARY KEY (`taxgroupid`,`taxauthid`), ADD KEY `taxgroupid` (`taxgroupid`), ADD KEY `taxauthid` (`taxauthid`);

--
-- Indexes for table `taxprovinces`
--
ALTER TABLE `taxprovinces`
 ADD PRIMARY KEY (`taxprovinceid`);

--
-- Indexes for table `tenderitems`
--
ALTER TABLE `tenderitems`
 ADD PRIMARY KEY (`tenderid`,`stockid`);

--
-- Indexes for table `tenders`
--
ALTER TABLE `tenders`
 ADD PRIMARY KEY (`tenderid`);

--
-- Indexes for table `tendersuppliers`
--
ALTER TABLE `tendersuppliers`
 ADD PRIMARY KEY (`tenderid`,`supplierid`);

--
-- Indexes for table `unitsofmeasure`
--
ALTER TABLE `unitsofmeasure`
 ADD PRIMARY KEY (`unitid`);

--
-- Indexes for table `woitems`
--
ALTER TABLE `woitems`
 ADD PRIMARY KEY (`wo`,`stockid`), ADD KEY `stockid` (`stockid`);

--
-- Indexes for table `worequirements`
--
ALTER TABLE `worequirements`
 ADD PRIMARY KEY (`wo`,`parentstockid`,`stockid`), ADD KEY `stockid` (`stockid`), ADD KEY `worequirements_ibfk_3` (`parentstockid`);

--
-- Indexes for table `workcentres`
--
ALTER TABLE `workcentres`
 ADD PRIMARY KEY (`code`), ADD KEY `Description` (`description`), ADD KEY `Location` (`location`);

--
-- Indexes for table `workorders`
--
ALTER TABLE `workorders`
 ADD PRIMARY KEY (`wo`), ADD KEY `LocCode` (`loccode`), ADD KEY `StartDate` (`startdate`), ADD KEY `RequiredBy` (`requiredby`);

--
-- Indexes for table `woserialnos`
--
ALTER TABLE `woserialnos`
 ADD PRIMARY KEY (`wo`,`stockid`,`serialno`);

--
-- Indexes for table `www_users`
--
ALTER TABLE `www_users`
 ADD PRIMARY KEY (`userid`), ADD KEY `CustomerID` (`customerid`), ADD KEY `DefaultLocation` (`defaultlocation`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assetmanager`
--
ALTER TABLE `assetmanager`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `banktrans`
--
ALTER TABLE `banktrans`
MODIFY `banktransid` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cogsglpostings`
--
ALTER TABLE `cogsglpostings`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `contractcharges`
--
ALTER TABLE `contractcharges`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `contractreqts`
--
ALTER TABLE `contractreqts`
MODIFY `contractreqid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `custallocns`
--
ALTER TABLE `custallocns`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `custcontacts`
--
ALTER TABLE `custcontacts`
MODIFY `contid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `custnotes`
--
ALTER TABLE `custnotes`
MODIFY `noteid` tinyint(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `debtortrans`
--
ALTER TABLE `debtortrans`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `debtortype`
--
ALTER TABLE `debtortype`
MODIFY `typeid` tinyint(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `debtortypenotes`
--
ALTER TABLE `debtortypenotes`
MODIFY `noteid` tinyint(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
MODIFY `departmentid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `edimessageformat`
--
ALTER TABLE `edimessageformat`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `edi_orders_segs`
--
ALTER TABLE `edi_orders_segs`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=96;
--
-- AUTO_INCREMENT for table `emailsettings`
--
ALTER TABLE `emailsettings`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `factorcompanies`
--
ALTER TABLE `factorcompanies`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fixedassets`
--
ALTER TABLE `fixedassets`
MODIFY `assetid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `fixedassettasks`
--
ALTER TABLE `fixedassettasks`
MODIFY `taskid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fixedassettrans`
--
ALTER TABLE `fixedassettrans`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `freightcosts`
--
ALTER TABLE `freightcosts`
MODIFY `shipcostfromid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `geocode_param`
--
ALTER TABLE `geocode_param`
MODIFY `geocodeid` tinyint(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gltrans`
--
ALTER TABLE `gltrans`
MODIFY `counterindex` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `grns`
--
ALTER TABLE `grns`
MODIFY `grnno` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `labelfields`
--
ALTER TABLE `labelfields`
MODIFY `labelfieldid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `labels`
--
ALTER TABLE `labels`
MODIFY `labelid` tinyint(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mailgroups`
--
ALTER TABLE `mailgroups`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `manufacturers`
--
ALTER TABLE `manufacturers`
MODIFY `manufacturers_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mrpdemands`
--
ALTER TABLE `mrpdemands`
MODIFY `demandid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mrpplannedorders`
--
ALTER TABLE `mrpplannedorders`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
MODIFY `offerid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `paymentmethods`
--
ALTER TABLE `paymentmethods`
MODIFY `paymentid` tinyint(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `pcashdetails`
--
ALTER TABLE `pcashdetails`
MODIFY `counterindex` int(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `purchorderdetails`
--
ALTER TABLE `purchorderdetails`
MODIFY `podetailitem` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `purchorders`
--
ALTER TABLE `purchorders`
MODIFY `orderno` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `qasamples`
--
ALTER TABLE `qasamples`
MODIFY `sampleid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `qatests`
--
ALTER TABLE `qatests`
MODIFY `testid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `recurringsalesorders`
--
ALTER TABLE `recurringsalesorders`
MODIFY `recurrorderno` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `reportfields`
--
ALTER TABLE `reportfields`
MODIFY `id` int(8) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1805;
--
-- AUTO_INCREMENT for table `reportheaders`
--
ALTER TABLE `reportheaders`
MODIFY `reportid` smallint(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
MODIFY `id` int(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=136;
--
-- AUTO_INCREMENT for table `salesanalysis`
--
ALTER TABLE `salesanalysis`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `salescat`
--
ALTER TABLE `salescat`
MODIFY `salescatid` tinyint(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `salesglpostings`
--
ALTER TABLE `salesglpostings`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `sampleresults`
--
ALTER TABLE `sampleresults`
MODIFY `resultid` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `securityroles`
--
ALTER TABLE `securityroles`
MODIFY `secroleid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `sellthroughsupport`
--
ALTER TABLE `sellthroughsupport`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `shipmentcharges`
--
ALTER TABLE `shipmentcharges`
MODIFY `shiptchgid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `shippers`
--
ALTER TABLE `shippers`
MODIFY `shipper_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `stockcatproperties`
--
ALTER TABLE `stockcatproperties`
MODIFY `stkcatpropid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `stockcounts`
--
ALTER TABLE `stockcounts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `stockmoves`
--
ALTER TABLE `stockmoves`
MODIFY `stkmoveno` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `stockrequest`
--
ALTER TABLE `stockrequest`
MODIFY `dispatchid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `stockserialmoves`
--
ALTER TABLE `stockserialmoves`
MODIFY `stkitmmoveno` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `suppallocs`
--
ALTER TABLE `suppallocs`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `supplierdiscounts`
--
ALTER TABLE `supplierdiscounts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `suppliertype`
--
ALTER TABLE `suppliertype`
MODIFY `typeid` tinyint(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `supptrans`
--
ALTER TABLE `supptrans`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
MODIFY `tagref` tinyint(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `taxauthorities`
--
ALTER TABLE `taxauthorities`
MODIFY `taxid` tinyint(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `taxcategories`
--
ALTER TABLE `taxcategories`
MODIFY `taxcatid` tinyint(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `taxgroups`
--
ALTER TABLE `taxgroups`
MODIFY `taxgroupid` tinyint(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `taxprovinces`
--
ALTER TABLE `taxprovinces`
MODIFY `taxprovinceid` tinyint(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `unitsofmeasure`
--
ALTER TABLE `unitsofmeasure`
MODIFY `unitid` tinyint(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
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
