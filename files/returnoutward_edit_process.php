<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php

    include_once("./functions/database.php");
    include_once("./functions/employee.php");
    include_once("./functions/department.php");
    include_once("./functions/returnoutward.php");
    include_once("./functions/items.php");
    include_once("./functions/parser.php");

    if (isset($_GET['Supervisor_Username'])) {
        $Supervisor_Username = mysqli_real_escape_string($conn,$_GET['Supervisor_Username']);
    }

    if (isset($_GET['Supervisor_Password'])) {
        $Supervisor_Password = mysqli_real_escape_string($conn,md5($_GET['Supervisor_Password']));
    }


    if (isset($_GET['Outward_ID'])) {
        $Outward_ID = $_GET['Outward_ID'];
    }

    if (isset($_SESSION['userinfo']['Employee_Name'])) {
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    } else {
        $Employee_Name = 'Unknown Employee';
    }

    $error_code = 0;
    if (Is_Logged_In_User($Supervisor_Username, $Supervisor_Password)) {
        Start_Transaction();

        $Document_Date = Get_Time_Now();
        $Return_Outward_Item_List = Get_Return_Outward_Items($Outward_ID);
        $Current_Edit = array(
            "Edit_Date" => Get_Time_Now(),
            "Edited_By" => $Employee_Name,
            "Previous_Data" => $Return_Outward_Item_List
        );

        $Return_Outward = Get_Return_Outward($Outward_ID);
        if ($Return_Outward['Outward_Status'] == "edited") {
            $Previous_Edit = jsonToArray($Return_Outward['Previous_Return_Outward_Data']);
            $Previous_Edit = array_merge($Previous_Edit, array($Current_Edit));
        } else {
            $Previous_Edit  = array_values(array("Previous_Return_Outward_Data" => $Current_Edit));
        }
        $Previous_Return_Outward_Data = toJson($Previous_Edit);

        if (Remove_Return_Outward_Items_By_Return_Outward_ID($Outward_ID)) {
            $Edited_Return_Outward_Items = $_SESSION['Return_Outward']['Items'];
            foreach($Edited_Return_Outward_Items as $Edited_IssueNoteManual_Item) {
                $Item_ID = $Edited_IssueNoteManual_Item['Item_ID'];
                $Quantity_Returned = $Edited_IssueNoteManual_Item['Quantity_Returned'];
                $Item_Remark = $Edited_IssueNoteManual_Item['Item_Remark'];
                $Sub_Department_ID = $Return_Outward['Sub_Department_ID'];
                $Supplier_ID = $Return_Outward['Supplier_ID'];

                $Item = Get_Item_Balance($Item_ID, $Sub_Department_ID);
                $Store_Balance = $Item['Item_Balance'];

                $Previous_Quantity_Returned = 0;
                foreach($Return_Outward_Item_List as $Temp_INM) {
                    if ($Temp_INM['Item_ID'] == $Item_ID) {
                        $Previous_Quantity_Returned = $Temp_INM['Quantity_Returned'];
                        break;
                    }
                }

                if ($Previous_Quantity_Returned == 0) {
                    /*if ($Quantity_Returned > $Store_Balance) {
                        $error_code = 2;
                        break;
                    } else {*/
                        if (!Update_Item_Balance($Item_ID, $Sub_Department_ID, "Return Outward", null, $Supplier_ID, null, $Outward_ID, $Document_Date, $Quantity_Returned, false)){
                            $error_code = 3;
                            break;
                        }
                    //}
                } else {
                    $Quantity_Returned_Diff = $Previous_Quantity_Returned - $Quantity_Returned;
                    if ($Quantity_Returned_Diff > 0) {
                        if (!Update_Item_Balance($Item_ID, $Sub_Department_ID, "Return Outward", null, $Supplier_ID, null, $Outward_ID, $Document_Date, $Quantity_Returned_Diff, true)){
                            $error_code = 3;
                            break;
                        }
                    } else if ($Quantity_Returned_Diff < 0) {
                        $Quantity_Returned_Diff = $Quantity_Returned - $Previous_Quantity_Returned;
                        if (!Update_Item_Balance($Item_ID, $Sub_Department_ID, "Return Outward", null, $Supplier_ID, null, $Outward_ID, $Document_Date, $Quantity_Returned_Diff, false)){
                            $error_code = 3;
                            break;
                        }
                    }
                }

                $Insert_IssueNoteManual_Item = Insert_DB("tbl_return_outward_items", array(
                    "Item_ID" => $Item_ID,
                    "Quantity_Returned" => $Quantity_Returned,
                    "Item_Remark" => $Item_Remark,
                    "Outward_ID" => $Outward_ID
                ));
                $hasError = $Insert_IssueNoteManual_Item["error"];
                if ($hasError) {
                    $error_code = 3;
                    echo $Insert_IssueNoteManual_Item['errorMsg'];
                }
            }

            //For Those items that existed in the previous document, but no longer exist after editing the document
            //The balance for these items need to returned back to the store in question
            foreach($Return_Outward_Item_List as $Return_Outward_Item){
                $Edited_Return_Outward_Items = $_SESSION['Return_Outward']['Items'];
                $Does_Not_Exist = true;
                foreach($Edited_Return_Outward_Items as $Edited_Return_Outward_Item) {
                    if ($Edited_Return_Outward_Item['Item_ID'] == $Return_Outward_Item['Item_ID']) {
                        $Does_Not_Exist = false;
                        break;
                    }
                }

                if ($Does_Not_Exist) {
                    $Item_ID = $Return_Outward_Item['Item_ID'];
                    $Quantity_Returned = $Return_Outward_Item['Quantity_Returned'];
                    $Quantity_Required = $Return_Outward_Item['Quantity_Required'];
                    $Item_Remark = $Return_Outward_Item['Item_Remark'];
                    $Sub_Department_ID = $Return_Outward['Sub_Department_ID'];
                    $Supplier_ID = $Return_Outward['Supplier_ID'];

                    $Item = Get_Item_Balance($Item_ID, $Sub_Department_ID);
                    $Store_Balance = $Item['Item_Balance'];

                    if (!Update_Item_Balance($Item_ID, $Sub_Department_ID, "Return Outward", null, $Supplier_ID, null, $Outward_ID, $Document_Date, $Quantity_Returned, false)){
                        $error_code = 3;
                        break;
                    }
                }
            }
        } else {
            $error_code = 5;
        }

        if ($error_code == 0) {
            $Update_Status = Update_Return_Outward($Outward_ID, array(
                "Previous_Return_Outward_Data" => $Previous_Return_Outward_Data,
                "Outward_Status" => "edited"
            ));
            $hasError = $Update_Status["error"];
            if (!$hasError) {
                $error_code = 1;
                Commit_Transaction();
                if (isset($_SESSION['Outward_ID'])) {
                    unset($_SESSION['Outward_ID']);
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