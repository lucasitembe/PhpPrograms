<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php

    include_once("./functions/database.php");
    include_once("./functions/employee.php");
    include_once("./functions/department.php");
    include_once("./functions/returninward.php");
    include_once("./functions/items.php");
    include_once("./functions/parser.php");

    if (isset($_GET['Supervisor_Username'])) {
        $Supervisor_Username = mysqli_real_escape_string($conn,$_GET['Supervisor_Username']);
    }

    if (isset($_GET['Supervisor_Password'])) {
        $Supervisor_Password = mysqli_real_escape_string($conn,md5($_GET['Supervisor_Password']));
    }


    if (isset($_GET['Inward_ID'])) {
        $Inward_ID = $_GET['Inward_ID'];
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
        $Return_Inward_Item_List = Get_Return_Inward_Items($Inward_ID);
        $Current_Edit = array(
            "Edit_Date" => Get_Time_Now(),
            "Edited_By" => $Employee_Name,
            "Previous_Data" => $Return_Inward_Item_List
        );

        $Return_Inward = Get_Return_Inward($Inward_ID);
        if ($Return_Inward['Inward_Status'] == "edited") {
            $Previous_Edit = jsonToArray($Return_Inward['Previous_Return_Inward_Data']);
            $Previous_Edit = array_merge($Previous_Edit, array($Current_Edit));
        } else {
            $Previous_Edit  = array_values(array("Previous_Return_Inward_Data" => $Current_Edit));
        }
        $Previous_Return_Inward_Data = toJson($Previous_Edit);

        if (Remove_Return_Inward_Items_By_Return_Inward_ID($Inward_ID)) {
            $Edited_Return_Inward_Items = $_SESSION['Return_Inward']['Items'];
            foreach($Edited_Return_Inward_Items as $Edited_IssueNoteManual_Item) {
                $Item_ID = $Edited_IssueNoteManual_Item['Item_ID'];
                $Quantity_Returned = $Edited_IssueNoteManual_Item['Quantity_Returned'];
                $Item_Remark = $Edited_IssueNoteManual_Item['Item_Remark'];
                $Store_Sub_Department_ID = $Return_Inward['Store_Sub_Department_ID'];
                $Return_Sub_Department_ID = $Return_Inward['Return_Sub_Department_ID'];

                $Item = Get_Item_Balance($Item_ID, $Store_Sub_Department_ID);
                $Store_Balance = $Item['Item_Balance'];

                $Previous_Quantity_Returned = 0;
                foreach($Return_Inward_Item_List as $Temp_INM) {
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
                        if (!Update_Item_Balance($Item_ID, $Store_Sub_Department_ID, "Return Inward", $Return_Sub_Department_ID, null, null, $Inward_ID, $Document_Date, $Quantity_Returned, true)){
                            $error_code = 3;
                            break;
                        }

                        //If your going to add anything to the other stock department
                        //Make sure if it uses system to do stock, if it does, do not allow to perform action
                        //if the number of items deducted is greater than the balance in the stock
                        $not_bad = true;
                        if (Sub_Department_Is_Eligible_For_Stock_Balance($Return_Sub_Department_ID)) {
                            $Return_Sub_Department_Balance = Get_Item_Balance($Item_ID, $Return_Sub_Department_ID);
                            if ($Return_Sub_Department_Balance['Item_Balance'] < $Quantity_Returned) {
                                $error_code = 2; $not_bad = false;
                                break;
                            }
                        }

                        if ($not_bad && !Update_Item_Balance($Item_ID, $Return_Sub_Department_ID, "Return Inward Outward", $Store_Sub_Department_ID, null, null, $Inward_ID, $Document_Date, $Quantity_Returned, false)){
                            $error_code = 3;
                            break;
                        }
                    //}
                } else {
                    $Quantity_Returned_Diff = $Previous_Quantity_Returned - $Quantity_Returned;
                    if ($Quantity_Returned_Diff > 0) {
                        if (!Update_Item_Balance($Item_ID, $Store_Sub_Department_ID, "Return Inward", $Return_Sub_Department_ID, null, null, $Inward_ID, $Document_Date, $Quantity_Returned_Diff, false)){
                            $error_code = 3;
                            break;
                        }

                        if (!Update_Item_Balance($Item_ID, $Return_Sub_Department_ID, "Return Inward Outward", $Store_Sub_Department_ID, null, null, $Inward_ID, $Document_Date, $Quantity_Returned_Diff, true)){
                            $error_code = 3;
                            break;
                        }
                    } else if ($Quantity_Returned_Diff < 0) {
                        $Quantity_Returned_Diff = $Quantity_Returned - $Previous_Quantity_Returned;
                        if (!Update_Item_Balance($Item_ID, $Store_Sub_Department_ID, "Return Inward", $Return_Sub_Department_ID, null, null, $Inward_ID, $Document_Date, $Quantity_Returned_Diff, true)){
                            $error_code = 3;
                            break;
                        }

                        //If your going to add anything to the other stock department
                        //Make sure if it uses system to do stock, if it does, do not allow to perform action
                        //if the number of items deducted is greater than the balance in the stock
                        $not_bad = true;
                        if (Sub_Department_Is_Eligible_For_Stock_Balance($Return_Sub_Department_ID)) {
                            $Return_Sub_Department_Balance = Get_Item_Balance($Item_ID, $Return_Sub_Department_ID);
                            if ($Return_Sub_Department_Balance['Item_Balance'] < $Quantity_Returned_Diff) {
                                $error_code = 2; $not_bad = false;
                                break;
                            }
                        }

                        if ($not_bad && !Update_Item_Balance($Item_ID, $Return_Sub_Department_ID, "Return Inward Outward", $Store_Sub_Department_ID, null, null, $Inward_ID, $Document_Date, $Quantity_Returned_Diff, false)){
                            $error_code = 3;
                            break;
                        }
                    }
                }

                $Insert_IssueNoteManual_Item = Insert_DB("tbl_return_inward_items", array(
                    "Item_ID" => $Item_ID,
                    "Quantity_Returned" => $Quantity_Returned,
                    "Item_Remark" => $Item_Remark,
                    "Inward_ID" => $Inward_ID
                ));
                $hasError = $Insert_IssueNoteManual_Item["error"];
                if ($hasError) {
                    $error_code = 3;
                    echo $Insert_IssueNoteManual_Item['errorMsg'];
                }
            }

            //For Those items that existed in the previous document, but no longer exist after editing the document
            //The balance for these items need to returned back to the store in question
            foreach($Return_Inward_Item_List as $Return_Inward_Item){
                $Edited_Return_Inward_Items = $_SESSION['Return_Inward']['Items'];
                $Does_Not_Exist = true;
                foreach($Edited_Return_Inward_Items as $Edited_Return_Inward_Item) {
                    if ($Edited_Return_Inward_Item['Item_ID'] == $Return_Inward_Item['Item_ID']) {
                        $Does_Not_Exist = false;
                        break;
                    }
                }

                if ($Does_Not_Exist) {
                    $Item_ID = $Return_Inward_Item['Item_ID'];
                    $Quantity_Returned = $Return_Inward_Item['Quantity_Returned'];
                    $Quantity_Required = $Return_Inward_Item['Quantity_Required'];
                    $Item_Remark = $Return_Inward_Item['Item_Remark'];
                    $Store_Sub_Department_ID = $Return_Inward['Store_Sub_Department_ID'];
                    $Return_Sub_Department_ID = $Return_Inward['Return_Sub_Department_ID'];

                    $Item = Get_Item_Balance($Item_ID, $Store_Sub_Department_ID);
                    $Store_Balance = $Item['Item_Balance'];

                    if (!Update_Item_Balance($Item_ID, $Store_Sub_Department_ID, "Return Inward", $Return_Sub_Department_ID, null, null, $Inward_ID, $Document_Date, $Quantity_Returned, true)){
                        $error_code = 3;
                        break;
                    }

                    //If your going to add anything to the other stock department
                    //Make sure if it uses system to do stock, if it does, do not allow to perform action
                    //if the number of items deducted is greater than the balance in the stock
                    $not_bad = true;
                    if (Sub_Department_Is_Eligible_For_Stock_Balance($Return_Sub_Department_ID)) {
                        $Return_Sub_Department_Balance = Get_Item_Balance($Item_ID, $Return_Sub_Department_ID);
                        if ($Return_Sub_Department_Balance['Item_Balance'] < $Quantity_Returned) {
                            $error_code = 2; $not_bad = false;
                            break;
                        }
                    }

                    if ($not_bad && !Update_Item_Balance($Item_ID, $Return_Sub_Department_ID, "Return Inward Outward", $Store_Sub_Department_ID, null, null, $Inward_ID, $Document_Date, $Quantity_Returned, false)){
                        $error_code = 3;
                        break;
                    }
                }
            }
        } else {
            $error_code = 5;
        }

        if ($error_code == 0) {
            $Update_Status = Update_Return_Inward($Inward_ID, array(
                "Previous_Return_Inward_Data" => $Previous_Return_Inward_Data,
                "Inward_Status" => "edited"
            ));
            $hasError = $Update_Status["error"];
            if (!$hasError) {
                $error_code = 1;
                Commit_Transaction();
                if (isset($_SESSION['Inward_ID'])) {
                    unset($_SESSION['Inward_ID']);
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