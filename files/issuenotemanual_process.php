<?php

session_start();

include_once("./functions/database.php");
include_once("./functions/employee.php");
include_once("./functions/issuenotemanual.php");
include_once("./functions/items.php");

if (isset($_GET['Supervisor_Username'])) {
    $Supervisor_Username = mysqli_real_escape_string($conn,$_GET['Supervisor_Username']);
}

if (isset($_GET['Supervisor_Password'])) {
    $Supervisor_Password = mysqli_real_escape_string($conn,md5($_GET['Supervisor_Password']));
}


if (isset($_GET['IssueManual_ID'])) {
    $IssueManual_ID = $_GET['IssueManual_ID'];
}

$error_code = 0;
if (Is_Logged_In_User($Supervisor_Username, $Supervisor_Password)) {
    $Document_Date = Get_Time_Now();
    $Issue_Note_Manual_Item_List = Get_Issue_Note_Manual_Items($IssueManual_ID);
    //$Issue_Note_Manual = Get_Issue_Note_Manual($IssueManual_ID);
    $Issue_Array = array();
    Start_Transaction();
    foreach ($Issue_Note_Manual_Item_List as $Issue_Note_Manual_Item) {
        $Quantity_Issued = $Issue_Note_Manual_Item['Quantity_Issued'];
        $Item_ID = $Issue_Note_Manual_Item['Item_ID'];
        $Store_Issuing = $Issue_Note_Manual_Item['Store_Issuing'];
        $Store_Need = $Issue_Note_Manual_Item['Store_Need'];
        $Store_Balance = $Issue_Note_Manual_Item['Store_Balance'];
        $Requisition_Item_ID = $Issue_Note_Manual_Item['Requisition_Item_ID'];
        //$Last_Buying_Price = Get_Last_Buy_Price($Item_ID);

        if ($Quantity_Issued != 0) {
            if (!Update_Item_Balance($Item_ID, $Store_Issuing, "Issue Note Manual", $Store_Need, null, null, $IssueManual_ID, $Document_Date, $Quantity_Issued, false)) {
                $error_code = 3;
                break;
            }

            if (!Update_Issue_Note_Manual_Cost_Center($Item_ID, $Store_Need, "Received From Issue Note Manual", $Store_Issuing, $IssueManual_ID, $Document_Date, $Quantity_Issued)) {
                $error_code = 5;
                break;
            }

            $cons = mysqli_query($conn,"SELECT Consultation_Type,Item_Type FROM tbl_items WHERE Item_ID='" . $Item_ID . "'") or die(mysqli_error($conn));
            $consResult = mysqli_fetch_assoc($cons);

            $consultation_type = $consResult['Consultation_Type'];
            $cons_item_Type = $consResult['Item_Type'];

           // if (strtolower($cons_item_Type) != 'pharmacy') {
                $inventory_ledger = getInventoryLedgerByConsultationType($consultation_type);

                $subtotal = $Quantity_Issued * getItemLastBuyingPrice($Item_ID);
                $Product_Name_Array = array(
                    'ref_no' => $IssueManual_ID,
                    'source_name' => 'ehms_issuemanual',
                    'comment' => 'Issue Number: ' . $IssueManual_ID . ', Store issued:' . getSubdepartmentByID($Store_Issuing) . ', Store needed:' . getSubdepartmentByID($Store_Need) . ', Requested by:' . getEmployeeNameByIssueManualID($IssueManual_ID, 'needer') . ', Issued by:' . getEmployeeNameByIssueManualID($IssueManual_ID, 'issuer'),
                    'debit_entry_ledger' => 'ISSUENOTE-MANUAL-COST-OF-SALES',
                    'credit_entry_ledger' => $inventory_ledger,
                    'sub_total' => $subtotal,
                    'source_id' => $Requisition_Item_ID,
                    'Employee_Name' => $Employee_Name,
                    'Employee_ID' => $Employee_ID
                );

                array_push($Issue_Array, $Product_Name_Array);
           // }


            //Update Buying price
            //mysqli_query($conn,"update tbl_issuemanual_items set Buying_Price = '$Last_Buying_Price' where Requisition_Item_ID = '$Requisition_Item_ID'") or die(mysqli_error($conn));
        }
    }

    if (count($Issue_Array) > 0) {
        $endata = json_encode($Issue_Array);

        $acc = gAccJournalEntry($endata);

        if ($acc != "success") {
            $HAS_ERROR = true;
        }
    }

    if ($error_code == 0) {
        $Update_Status = Update_Issue_Note_Manual_Status($IssueManual_ID, "saved");
        $hasError = $Update_Status["error"];
        if (!$hasError) {
            $error_code = 1;
            Commit_Transaction();
            if (isset($_SESSION['IssueManual_ID'])) {
                unset($_SESSION['IssueManual_ID']);
            }
        } else {
            $error_code = 4;
            echo $Update_Status["errorMsg"];
            Rollback_Transaction();
        }
    } else {
        Rollback_Transaction();
    }
} else {
    $error_code = 0;
}

echo $error_code;
