<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php

    include_once("./functions/database.php");
    include_once("./functions/employee.php");
    include_once("./functions/issuenotemanual.php");
    include_once("./functions/items.php");
    include_once("./functions/parser.php");

    if (isset($_GET['Supervisor_Username'])) {
        $Supervisor_Username = mysqli_real_escape_string($conn,$_GET['Supervisor_Username']);
    }

    if (isset($_GET['Supervisor_Password'])) {
        $Supervisor_Password = mysqli_real_escape_string($conn,md5($_GET['Supervisor_Password']));
    }

    if (isset($_GET['IssueManual_ID'])) {
        $IssueManual_ID = $_GET['IssueManual_ID'];
    }

    if (isset($_GET['Store_Need_ID'])) {
        $Store_Need_ID = $_GET['Store_Need_ID'];
    } else {
        $Store_Need_ID = 0;
    }

    if (isset($_GET['Employee_Requested'])) {
        $Employee_Requested = $_GET['Employee_Requested'];
    } else {
        $Employee_Requested = 0;
    }

    if (isset($_GET['Issue_Date'])) {
        $Issue_Date = $_GET['Issue_Date'];
    } else {
        $Issue_Date = "";
    }

    if (isset($_SESSION['userinfo']['Employee_Name'])) {
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    } else {
        $Employee_Name = 'Unknown Employee';
    }

    $error_code = 0;
    if (Is_Logged_In_User($Supervisor_Username, $Supervisor_Password)) {
        Start_Transaction();

        $Issue_Note_Manual = Get_Issue_Note_Manual($IssueManual_ID);
        $Issue_Note_Manual_Item_List = Get_Issue_Note_Manual_Items($IssueManual_ID);
        $Current_Edit = array(
            "Edit_Date" => Get_Time_Now(),
            "Edited_By" => $Employee_Name,
            "Document" => $Issue_Note_Manual,
            "Previous_Data" => $Issue_Note_Manual_Item_List
        );
        if ($Issue_Note_Manual['status'] == "edited") {
            $Previous_Edit = jsonToArray($Issue_Note_Manual['Previous_Issue_Note_Manual_Data']);
            $Previous_Edit = array_merge($Previous_Edit, array($Current_Edit));
        } else {
            $Previous_Edit  = array_values(array("Previous_Issue_Note_Manual_Data" => $Current_Edit));
        }
        $Previous_Issue_Note_Manual_Data = toJson($Previous_Edit);

        $Document_Date = Get_Time_Now();
        if (Remove_Issue_Note_Manual_Items_By_Issue_Note_Manual_ID($IssueManual_ID)) {
            $Edited_Issue_Note_Manual_Items = $_SESSION['Issue_Note_Manual']['Items'];
            foreach($Edited_Issue_Note_Manual_Items as $Edited_IssueNoteManual_Item) {
                $Item_ID = $Edited_IssueNoteManual_Item['Item_ID'];
                $Quantity_Issued = $Edited_IssueNoteManual_Item['Quantity_Issued'];
                $Quantity_Required = $Edited_IssueNoteManual_Item['Quantity_Required'];
                $Item_Remark = $Edited_IssueNoteManual_Item['Item_Remark'];
                $Store_Issuing = $Issue_Note_Manual['Store_Issuing'];
                $Store_Need = $Issue_Note_Manual['Store_Need'];

                $Item = Get_Item_Balance($Item_ID, $Store_Issuing);
                $Store_Balance = $Item['Item_Balance'];

                $Previous_Quantity_Issued = 0;
                foreach($Issue_Note_Manual_Item_List as $Temp_INM) {
                    if ($Temp_INM['Item_ID'] == $Item_ID) {
                        $Previous_Quantity_Issued = $Temp_INM['Quantity_Issued'];
                        break;
                    }
                }

                if ($Previous_Quantity_Issued == 0) {
                    if ($Quantity_Issued > $Store_Balance) {
                        $error_code = 2;
                        break;
                    } else {
                        if (!Update_Item_Balance($Item_ID, $Store_Issuing, "Issue Note Manual", $Store_Need, null, null, $IssueManual_ID, $Document_Date, $Quantity_Issued, false)){
                            $error_code = 3;
                            break;
                        }
                    }
                } else {
                    $Quantity_Issued_Diff = $Previous_Quantity_Issued - $Quantity_Issued;
                    if ($Quantity_Issued_Diff > 0) {
                        if (!Update_Item_Balance($Item_ID, $Store_Issuing, "Issue Note Manual", $Store_Need, null, null, $IssueManual_ID, $Document_Date, $Quantity_Issued_Diff, true)){
                            $error_code = 3;
                            break;
                        }
                    } else if ($Quantity_Issued_Diff < 0) {
                        $Quantity_Issued_Diff = $Quantity_Issued - $Previous_Quantity_Issued;
                        if (!Update_Item_Balance($Item_ID, $Store_Issuing, "Issue Note Manual", $Store_Need, null, null, $IssueManual_ID, $Document_Date, $Quantity_Issued_Diff, false)){
                            $error_code = 3;
                            break;
                        }
                    }
                }

                $Insert_IssueNoteManual_Item = Insert_DB("tbl_issuemanual_items", array(
                    "Item_ID" => $Item_ID,
                    "Quantity_Issued" => $Quantity_Issued,
                    "Item_Remark" => $Item_Remark,
                    "Quantity_Required" => $Quantity_Required,
                    "Issue_ID" => $IssueManual_ID
                ));
                $hasError = $Insert_IssueNoteManual_Item["error"];
                if ($hasError) {
                    $error_code = 3;
                    echo $Insert_IssueNoteManual_Item['errorMsg'];
                }
            }

            //For Those items that existed in the previous document, but no longer exist after editing the document
            //The balance for these items need to returned back to the store in question
            foreach($Issue_Note_Manual_Item_List as $Issue_Note_Manual_Item){
                $Edited_Issue_Note_Manual_Items = $_SESSION['Issue_Note_Manual']['Items'];
                $Does_Not_Exist = true;
                foreach($Edited_Issue_Note_Manual_Items as $Edited_Issue_Note_Manual_Item) {
                    if ($Edited_Issue_Note_Manual_Item['Item_ID'] == $Issue_Note_Manual_Item['Item_ID']) {
                        $Does_Not_Exist = false;
                        break;
                    }
                }

                if ($Does_Not_Exist) {
                    $Item_ID = $Issue_Note_Manual_Item['Item_ID'];
                    $Quantity_Issued = $Issue_Note_Manual_Item['Quantity_Issued'];
                    $Quantity_Required = $Issue_Note_Manual_Item['Quantity_Required'];
                    $Item_Remark = $Issue_Note_Manual_Item['Item_Remark'];
                    $Store_Issuing = $Issue_Note_Manual['Store_Issuing'];
                    $Store_Need = $Issue_Note_Manual['Store_Need'];

                    $Item = Get_Item_Balance($Item_ID, $Store_Issuing);
                    $Store_Balance = $Item['Item_Balance'];

                    if (!Update_Item_Balance($Item_ID, $Store_Issuing, "Issue Note Manual Edit", $Store_Need, null, null, $IssueManual_ID, $Document_Date, $Quantity_Issued, true)){
                        $error_code = 3;
                        break;
                    }
                }
            }
        } else {
            $error_code = 5;
        }

        if ($error_code == 0) {
            $Update_Status = Update_Issue_Note_Manual($IssueManual_ID, array(
                "Previous_Issue_Note_Manual_Data" => $Previous_Issue_Note_Manual_Data,
                "Store_Need" => $Store_Need_ID,
                "Employee_Receiving" => $Employee_Requested,
                "Issue_Date_And_Time" => $Issue_Date,
                "status" => "edited"
            ));
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