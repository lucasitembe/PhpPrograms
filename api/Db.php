<?php

include '../includes/connection.php';
include 'items.php';
include 'constants.php';
/*
 * DB Class
 * This class is used for database related (connect, insert, update, and delete) operations
 * with PHP Data Objects (PDO)
 * @author    CodexWorld.com
 * @url       http://www.codexworld.com
 * @license   http://www.codexworld.com/license */

class Db {

    public $db = null;

    function Db() {
        if (is_null($this->db)) {
            try {
                $DB_con = new PDO("mysql:host=" . HOST . ";port=" . PORT . ";dbname=" . DBNAME, USER, PASSWORD);
                $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $DB_con->exec("SET CHARACTER SET utf8");
                $this->db = $DB_con;
            } catch (PDOException $exception) {
                $this->errorHandiling("Failed to connect to the database server: " . $exception->getMessage());
            }
        } else {
            $this->db = $db;
        }
    }

    function getRecord($sql) {
        try {
            $query = $this->db->query($sql);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            $this->errorHandiling($ex->getMessage());
        } catch (Exception $ex) {
            $this->errorHandiling($ex->getMessage());
        }

        return null;
    }

    function saveUserInfor($sql) {
        try {
            $affectedRow = $this->db->exec($sql);
            return $affectedRow;
        } catch (PDOException $ex) {
            $this->errorHandiling($ex->getMessage());
        } catch (Exception $ex) {
            $this->errorHandiling($ex->getMessage());
        }
    }

    function errorHandiling($error_message) {
        die($error_message);
    }

    function getProfitLoss($start_date, $end_date, $is_balance = false) {
        $result = array(
            'income' => $this->getRevenueIncome($start_date, $end_date, $is_balance),
            'cost_of_sales' => $this->getCostOfSale($start_date, $end_date, $is_balance)
        );

        return $result;
    }

    function getBalanceSheet($start_date, $end_date) {
        $result = array(
            'current_assets' => $this->getCashInHand($start_date, $end_date) + $this->getDebtorsValue($start_date, $end_date) + $this->getStockValue($start_date, $end_date),
            'current_liabilities' => $this->getCurrentLiabilities($start_date, $end_date),
            'gross_profit' => $this->getGrossProfit($start_date, $end_date)
        );

        return $result;
    }

    function getSponsors() {
        $result = array();
        $sql = "SELECT Guarantor_Name FROM tbl_sponsor order by Guarantor_Name";

        $sponsors = $this->getRecord($sql);
        foreach ($sponsors as $val) {
            $result[] = $val['Guarantor_Name'];
        }

        return $result;
    }
    
    function getSuppliers() {
        $result = array();
        $sql = "SELECT Supplier_Name FROM tbl_supplier order by Supplier_Name";

        $sponsors = $this->getRecord($sql);

        foreach ($sponsors as $val) {
            $result[] = $val['Supplier_Name'];
        }

        return $result;
    }

    function getDebtors($start_date, $end_date) {
        $result = array();
        $sql = "SELECT DISTINCT(pp.Sponsor_ID),Guarantor_Name FROM tbl_sponsor sp JOIN tbl_Patient_Payments pp ON pp.Sponsor_ID=sp.Sponsor_ID WHERE Exemption='no' AND Free_Consultation_Sponsor='no' order by Guarantor_Name";

        $sponsors = $this->getRecord($sql);

        foreach ($sponsors as $val) {
            $result[] = array("name" => $val['Guarantor_Name'], "sponsor_id" => $val['Sponsor_ID'], 'amount' => $this->getDebtorsValue($start_date, $end_date, $val['Sponsor_ID']));
        }

        return $result;
    }

    function getPayables($start_date, $end_date) {
        $result = array();
        $Purchase_History_List = Get_Item_Purchase_History('All', "all", null, $start_date, $end_date, 0);

        $Grand_Total = 0;

        $Account_Payable = array();
        foreach ($Purchase_History_List as $Purchase_History) {
            $Sub_Total = $Purchase_History['Buying_Price'] * $Purchase_History['Quantity'];
            $Grand_Total += $Sub_Total;
            if (array_key_exists($Purchase_History['Supplier_ID'], $Account_Payable)) {
                $Account_Payable[$Purchase_History['Supplier_ID']] += $Sub_Total;
            } else {
                $Account_Payable[$Purchase_History['Supplier_ID']] = $Sub_Total;
            }
        }

        foreach ($Account_Payable as $Supplier_ID => $Payable_Amount) {
            $result[] = array("name" => Get_Supplier($Supplier_ID)['Supplier_Name'], 'amount' => $Payable_Amount);
        }

        return $result;
    }

    function getPurchases($start_date, $end_date) {
        $result = array();
        $Purchase_History_List = Get_Item_Purchase_History('all', 'all', null, $start_date, $end_date, 0);
        $Purchase_By_Classification = array();
        $Item_List = array();
        $Product_Quantity_List = array();
        $Product_Name_List = array();
        $Product_Code_List = array();
        $Product_Classification_List = array();
        foreach ($Purchase_History_List as $Purchase_History) {
            $Sub_Total = $Purchase_History['Buying_Price'] * $Purchase_History['Quantity'];

            if (array_key_exists($Purchase_History['Classification'], $Purchase_By_Classification)) {
                $Purchase_By_Classification[$Purchase_History['Classification']] += $Sub_Total;
            } else {
                $Purchase_By_Classification[$Purchase_History['Classification']] = $Sub_Total;
            }

            if (array_key_exists($Purchase_History['Item_ID'], $Item_List)) {
                $Item_List[$Purchase_History['Item_ID']] += $Sub_Total;
                $Product_Quantity_List[$Purchase_History['Item_ID']] += $Purchase_History['Quantity'];
            } else {
                $Item_List[$Purchase_History['Item_ID']] = $Sub_Total;
                $Product_Name_List[$Purchase_History['Item_ID']] = $Purchase_History['Product_Name'];
                $Product_Code_List[$Purchase_History['Item_ID']] = $Purchase_History['Product_Code'];
                $Product_Quantity_List[$Purchase_History['Item_ID']] = $Purchase_History['Quantity'];
                $Product_Classification_List[$Purchase_History['Item_ID']] = $Purchase_History['Classification'];
            }
        }

        foreach ($Purchase_By_Classification as $Purchase_Classification => $Purchase_Amount) {
            $result[] = array("name" => $Purchase_Classification, 'amount' => $Purchase_Amount);
        }

        return $result;
    }

    private function getRevenueIncome($start_date, $end_date, $is_balance = false) {
        if ($is_balance)
            $b = "DATE(pp.Payment_Date_And_Time) <= '$end_date'";
        else
            $b = " DATE(pp.Payment_Date_And_Time) between '$start_date' and '$end_date'";


        $filter = " and $b and Transaction_status <> 'cancelled' ";

        $sql = "select ppl.Quantity, ppl.Price, ppl.Discount,ts.Exemption FROM
	                                        tbl_patient_payment_item_list ppl, tbl_patient_payments pp ,tbl_sponsor as ts WHERE
	                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                                 ts.Sponsor_ID=pp.Sponsor_ID 
                                                $filter  ";

        $result = $this->getRecord($sql);
        $grandTotal = 0;

        if (!is_null($result) && count($result) > 0) {
            foreach ($result as $Det) {
                if ($Det['Exemption'] == 'no') {
                    $grandTotal += (($Det['Price'] - $Det['Discount']) * $Det['Quantity']);
                }
            }
        }

        // echo $sql;
        return $grandTotal;
    }

    private function getCostOfSale($start_date, $end_date, $is_balance = false) {
        if ($is_balance)
            $b = "DATE(Issue_Date) <= '$end_date'";
        else
            $b = " DATE(Issue_Date) BETWEEN '$start_date' AND '$end_date'";
        $filterIss = " AND $b AND rqi.Item_Status='received' and i.Item_Type !='Pharmacy'";

        if ($is_balance)
            $b = "DATE(Created_Date_And_Time) <= '$end_date'";
        else
            $b = " DATE(Created_Date_And_Time) BETWEEN '$start_date' AND '$end_date'";
        $filterIssManual = "  AND $b AND iss.status='saved' and i.Item_Type !='Pharmacy'";

        if ($is_balance)
            $b = "DATE(Dispense_Date_Time) <= '$end_date'";
        else
            $b = " DATE(Dispense_Date_Time) BETWEEN '$start_date' AND '$end_date'";
        $filterIssPharmacy = " $b AND il.Status='dispensed'";

        $grandTotal = 0;

        $classifications = array();
        foreach (Get_Item_Classification() as $value) {
            $classifications[] = $value['Name'];
        }

        // foreach ($classifications as $value) {
        //Phamaceutical
        $sql_select_pharmacy = "SELECT il.Item_ID,IF(il.Edited_Quantity > 0, il.Edited_Quantity,il.Quantity) AS Qty,il.Price FROM tbl_item_list_cache il
                                    JOIN  tbl_items i ON i.Item_ID = il.Item_ID WHERE  i.Classification='Pharmaceuticals' AND $filterIssPharmacy";

        $resultPhar = $this->getRecord($sql_select_pharmacy);

        if (!is_null($resultPhar) && count($resultPhar) > 0) {
            foreach ($resultPhar as $rowPharm) {
                $Quantity = $rowPharm['Qty'];
                $last_buying_price = $this->Get_Last_Buy_Price($rowPharm['Item_ID']);

                if (!is_numeric($last_buying_price)) {
                    $last_buying_price = 0;
                }

                $grandTotal += $Quantity * $last_buying_price;
            }
        }

        //End Pharmacy
        //Issue note

        $sql_select = "SELECT rqi.Item_ID,rqi.Quantity_Received FROM tbl_issues iss, tbl_requisition rq, tbl_requisition_items rqi, tbl_items i WHERE
                                    iss.Requisition_ID = rq.Requisition_ID AND
                                    rqi.Requisition_ID = rq.Requisition_ID AND
                                    i.Item_ID = rqi.Item_ID  $filterIss";

        $resultIssue = $this->getRecord($sql_select);

        if (!is_null($resultIssue) && count($resultIssue) > 0) {
            foreach ($resultIssue as $row) {
                $last_buying_price = $this->Get_Last_Buy_Price($row['Item_ID']);

                if (!is_numeric($last_buying_price)) {
                    $last_buying_price = 0;
                }

                $grandTotal += $row['Quantity_Received'] * $last_buying_price;
            }
        }

        //End Issue note
        //Issue note manual

        $sql_select_manual = "SELECT ii.Item_ID,ii.Quantity_Issued FROM tbl_issuesmanual iss,  tbl_issuemanual_items ii, tbl_items i WHERE
                                    iss.Issue_ID = ii.Issue_ID AND
                                    i.Item_ID = ii.Item_ID  $filterIssManual";

        $resultIssueManual = $this->getRecord($sql_select_manual);

        if (!is_null($resultIssueManual) && count($resultIssueManual) > 0) {
            foreach ($resultIssueManual as $rowManul) {
                $last_buying_price = $this->Get_Last_Buy_Price($rowManul['Item_ID']);

                if (!is_numeric($last_buying_price)) {
                    $last_buying_price = 0;
                }


                $grandTotal += $rowManul['Quantity_Issued'] * $last_buying_price;
            }
        }
        // }
        return $grandTotal;
        //End note
    }

    private function getCashInHand($start_date, $end_date) {
        $filter = " AND DATE(pp.Payment_Date_And_Time) <= '$end_date' and (Billing_Type = 'Outpatient Cash' or (Billing_Type = 'Inpatient Cash')) and Transaction_status <> 'cancelled' ";
        $sql = "select ppl.Quantity, ppl.Price, ppl.Discount,ts.Exemption,pp.Billing_Type,pp.payment_type FROM
	                                        tbl_patient_payment_item_list ppl, tbl_patient_payments pp ,tbl_sponsor as ts WHERE
	                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                                 ts.Sponsor_ID=pp.Sponsor_ID 
                                                $filter  ";

        $result = $this->getRecord($sql);
        $grandTotal = 0;

        if (!is_null($result) && count($result) > 0) {
            foreach ($result as $Det) {
                $Total = (($Det['Price'] - $Det['Discount']) * $Det['Quantity']);
                if ((strtolower($Det['Billing_Type']) == 'outpatient cash') or ( strtolower($Det['Billing_Type']) == 'inpatient cash' && strtolower($Det['payment_type']) == 'pre')) {
                    $grandTotal += $Total;
                }
            }
        }

        return $grandTotal;
    }

    private function getDebtorsValue($start_date, $end_date, $sponsor = '') {

        $filter = " AND DATE(pp.Payment_Date_And_Time)" . ((!empty($sponsor) && is_numeric($sponsor)) ? " BETWEEN '$start_date' AND '$end_date'" : " <= '$end_date'") . " and (Billing_Type = 'Outpatient Credit' or Billing_Type = 'Inpatient Credit' or (Billing_Type = 'Inpatient Cash')) and Transaction_status <> 'cancelled' ";

        if (!empty($sponsor) && is_numeric($sponsor)) {
            $filter .= " AND pp.Sponsor_ID='$sponsor'";
        }

        $sql = "select ppl.Quantity, ppl.Price, ppl.Discount,ts.Exemption,pp.Billing_Type,pp.payment_type FROM
	                                        tbl_patient_payment_item_list ppl, tbl_patient_payments pp ,tbl_sponsor as ts WHERE
	                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                                 ts.Sponsor_ID=pp.Sponsor_ID 
                                                $filter  ";

        $result = $this->getRecord($sql);
        $grandTotal = 0;

        if (!is_null($result) && count($result) > 0) {
            foreach ($result as $Det) {


                if ($Det['Exemption'] == 'no') {
                    if ((strtolower($Det['Billing_Type']) == 'outpatient cash' && $Det['Pre_Paid'] == '1') or ( strtolower($Det['Billing_Type']) == 'outpatient credit') or ( strtolower($Det['Billing_Type']) == 'inpatient credit') or ( strtolower($Det['Billing_Type']) == 'inpatient cash' && strtolower($Det['payment_type']) == 'post')) {
                        $grandTotal += (($Det['Price'] - $Det['Discount']) * $Det['Quantity']);
                    }
                }
            }
        }

        return $grandTotal;
    }

    private function getStockValue($start_date, $end_date) {
        $sql = "SELECT i.Item_ID,Product_Name FROM tbl_items i WHERE  i.Can_Be_Stocked = 'yes'";

        $result = $this->getRecord($sql);
        $grandTotal = 0;
        $i = 1;
        foreach ($result as $Det) {
            $Product_Name = $Det['Product_Name'];
            $Item_ID = $Det['Item_ID'];

            $Brought_Forward = 0;
            $Total_Inward = 0;
            $Total_Outward = 0;
            $Total_Disposal = 0;
            $Total_Balance = 0;
            //$Total_Average_Price = 0;
            $Total_Average_Price = $this->Get_Last_Buy_Price($Item_ID);
            $Sub_Department_ID = null;
            $Stock_Ledger_Details = Get_Stock_Ledger_Details($Item_ID, $Sub_Department_ID, $start_date, $end_date);

            if (is_null($Stock_Ledger_Details)) {
                echo $Sub_Department_ID . ' Doweri <br/>';
            }

            if (!empty($Stock_Ledger_Details)) {
                $Brought_Forward = $Stock_Ledger_Details[0]['Pre_Balance'];
                $Total_Balance = $Total_Balance + $Brought_Forward;

                foreach ($Stock_Ledger_Details as $Stock_Ledger_Detail) {
                    $Movement_Type = $Stock_Ledger_Detail['Movement_Type'];
                    if ($Movement_Type == 'Disposal') {
                        $Total_Disposal += $Stock_Ledger_Detail['Pre_Balance'] - $Stock_Ledger_Detail['Post_Balance'];
                        $Total_Balance -= $Stock_Ledger_Detail['Pre_Balance'] - $Stock_Ledger_Detail['Post_Balance'];
                    } else {
                        $Pre_Post_Diff = $Stock_Ledger_Detail['Post_Balance'] - $Stock_Ledger_Detail['Pre_Balance'];
                        if ($Pre_Post_Diff > 0) {
                            $Total_Inward += $Stock_Ledger_Detail['Post_Balance'] - $Stock_Ledger_Detail['Pre_Balance'];
                            $Total_Balance += $Stock_Ledger_Detail['Post_Balance'] - $Stock_Ledger_Detail['Pre_Balance'];
                        } else {
                            $Total_Outward += $Stock_Ledger_Detail['Pre_Balance'] - $Stock_Ledger_Detail['Post_Balance'];
                            $Total_Balance -= $Stock_Ledger_Detail['Pre_Balance'] - $Stock_Ledger_Detail['Post_Balance'];
                        }
                    }
                }

                $grandTotal += $Total_Balance * $Total_Average_Price;
            }
        }

        return $grandTotal;
    }

    private function getCurrentLiabilities($start_date, $end_date) {
        $Supplier_ID = 'all';
        $Purchase_History_List = Get_Item_Purchase_History($Supplier_ID, "all", null, null, $end_date, 0);

        $grandTotal = 0;

        foreach ($Purchase_History_List as $Purchase_History) {
            $grandTotal += $Purchase_History['Buying_Price'] * $Purchase_History['Quantity'];
        }

        return $grandTotal;
    }

    function Get_Last_Buy_Price($Item_ID) {
        $sql = "SELECT Last_Buy_Price FROM tbl_items i WHERE  Item_ID='$Item_ID'";

        $result = $this->getRecord($sql)[0];

        return $result['Last_Buy_Price'];
    }

    function updateItemLastBuyingPrice() {
        $sql = "SELECT i.Item_ID,Product_Name FROM tbl_items i WHERE  i.Can_Be_Stocked = 'yes' ORDER BY i.Product_Name";

        $result = $this->getRecord($sql);
        $grandTotal = 0;
        $i = 1;
        foreach ($result as $Det) {
            $Product_Name = $Det['Product_Name'];
            $Item_ID = $Det['Item_ID'];

            $Total_Average_Price = $this->Get_Last_Buy_Price($Item_ID);

            $sql2 = "UPDATE tbl_items SET Last_Buy_Price='$Total_Average_Price' WHERE Item_ID='$Item_ID'";
            // echo $sql2;
            $result2 = $this->saveUserInfor($sql2);

            echo "<p>" . $Product_Name . " => " . $Total_Average_Price . "</p>";
        }
    }

    private function getGrossProfit($start_date, $end_date) {
        $result = $this->getProfitLoss($start_date, $end_date, true);
        $grossprofit = $result['income'] - $result['cost_of_sales'];

        return $grossprofit;
    }

    public function getActualAmount($start_date, $end_date, $dept_name) {
        $filter = " AND DATE(pp.Payment_Date_And_Time) BETWEEN '$start_date' AND '$end_date' and Transaction_status <> 'cancelled' and ppl.Check_In_Type='$dept_name'";
        $sql = "select ppl.Quantity, ppl.Price, ppl.Discount,ts.Exemption,pp.Billing_Type,pp.payment_type FROM
	                                        tbl_patient_payment_item_list ppl, tbl_patient_payments pp ,tbl_sponsor as ts WHERE
	                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                                 ts.Sponsor_ID=pp.Sponsor_ID 
                                                $filter  ";

        $result = $this->getRecord($sql);
        $ActualgrandTotal = 0;

        if (!is_null($result) && count($result) > 0) {
            foreach ($result as $Det) {
                $Total = (($Det['Price'] - $Det['Discount']) * $Det['Quantity']);
                $ActualgrandTotal += $Total;
            }
        }

        return array('ActualgrandTotal' => $ActualgrandTotal);
    }

}
