<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php

    include_once("./functions/database.php");
    include_once("./functions/employee.php");
    include_once("./functions/stocktaking.php");
    include_once("./functions/items.php");
    include_once("./functions/parser.php");

    if (isset($_GET['Supervisor_Username'])) {
        $Supervisor_Username = mysqli_real_escape_string($conn,$_GET['Supervisor_Username']);
    }

    if (isset($_GET['Supervisor_Password'])) {
        $Supervisor_Password = mysqli_real_escape_string($conn,md5($_GET['Supervisor_Password']));
    }


    if (isset($_GET['Stock_Taking_ID'])) {
        $Stock_Taking_ID = $_GET['Stock_Taking_ID'];
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
        $Stock_Taking_Item_List = Get_Stock_Taking_Items($Stock_Taking_ID);
        $Current_Edit = array(
            "Edit_Date" => Get_Time_Now(),
            "Edited_By" => $Employee_Name,
            "Previous_Data" => $Stock_Taking_Item_List
        );

        $Stock_Taking = Get_Stock_Taking($Stock_Taking_ID);
        if ($Stock_Taking['Stock_Taking_Status'] == "edited") {
            $Previous_Edit = jsonToArray($Stock_Taking['Previous_Stock_Taking_Data']);
            $Previous_Edit = array_merge($Previous_Edit, array($Current_Edit));
        } else {
            $Previous_Edit  = array_values(array("Previous_Stock_Taking_Data" => $Current_Edit));
        }
        $Previous_Stock_Taking_Data = toJson($Previous_Edit);

        if (Remove_Stock_Taking_Items_By_Stock_Taking_ID($Stock_Taking_ID)) {
            $Edited_Stock_Taking_Items = $_SESSION['Stock_Taking']['Items'];
            foreach($Edited_Stock_Taking_Items as $Edited_Stock_Taking_Item) {
                $Item_ID = $Edited_Stock_Taking_Item['Item_ID'];
                $Under_Quantity = $Edited_Stock_Taking_Item['Under_Quantity'];
                $Over_Quantity = $Edited_Stock_Taking_Item['Over_Quantity'];
                $Item_Remark = $Edited_Stock_Taking_Item['Item_Remark'];
                $Stock_Taking_Location = $Stock_Taking['Sub_Department_ID'];

                $Item = Get_Item_Balance($Item_ID, $Stock_Taking_Location);
                $Store_Balance = $Item['Item_Balance'];

                $Previous_Under_Quantity = 0;
                $Previous_Over_Quantity = 0;
                foreach($Stock_Taking_Item_List as $Temp_INM) {
                    if ($Temp_INM['Item_ID'] == $Item_ID) {
                        $Previous_Under_Quantity = $Temp_INM['Under_Quantity'];
                        $Previous_Over_Quantity = $Temp_INM['Over_Quantity'];
                        break;
                    }
                }

                //This covers if the item is new in the list
                if ($Previous_Under_Quantity == 0 && $Previous_Over_Quantity == 0) {
                    if ($Under_Quantity > $Store_Balance) {
                        $error_code = 2;
                        break;
                    } else {
                        if ($Under_Quantity > 0) {
                            if (!Update_Item_Balance($Item_ID, $Stock_Taking_Location, "Stock Taking Under", null, null, null, $Stock_Taking_ID, $Document_Date, $Under_Quantity, false)){
                                $error_code = 3;Rollback_Transaction();
                                break;
                            }
                        } else {
                            if (!Update_Item_Balance($Item_ID, $Stock_Taking_Location, "Stock Taking Over", null, null, null, $Stock_Taking_ID, $Document_Date, $Over_Quantity, true)){
                                $error_code = 3;Rollback_Transaction();
                                break;
                            }
                        }

                    }
                } else {
                    $Under_Quantity_Diff = $Previous_Under_Quantity - $Under_Quantity;
                    $Over_Quantity_Diff = $Previous_Over_Quantity - $Over_Quantity;

                    if ($Previous_Over_Quantity == 0 && $Previous_Under_Quantity > 0) {
                         if ($Under_Quantity == 0 && $Over_Quantity_Diff < 0) {
                             $Over_Quantity_Diff = $Over_Quantity + $Previous_Under_Quantity;
                             if (!Update_Item_Balance($Item_ID, $Stock_Taking_Location, "Stock Taking Under", null, null, null, $Stock_Taking_ID, $Document_Date, $Over_Quantity_Diff, true)){
                                 $error_code = 3;Rollback_Transaction();
                                 break;
                             }
                         } else if ($Over_Quantity == 0 ) {
                             if ($Under_Quantity_Diff < 0) {
                                 $Under_Quantity_Diff = $Under_Quantity - $Previous_Under_Quantity;
                                 if (!Update_Item_Balance($Item_ID, $Stock_Taking_Location, "Stock Taking Under", null, null, null, $Stock_Taking_ID, $Document_Date, $Under_Quantity_Diff, false)){
                                     $error_code = 3;Rollback_Transaction();
                                     break;
                                 }
                             } else if ($Under_Quantity_Diff > 0) {
                                 if (!Update_Item_Balance($Item_ID, $Stock_Taking_Location, "Stock Taking Under", null, null, null, $Stock_Taking_ID, $Document_Date, $Under_Quantity_Diff, true)){
                                     $error_code = 3;Rollback_Transaction();
                                     break;
                                 }
                             }
                         }
                    } else if ($Previous_Under_Quantity == 0 && $Previous_Over_Quantity > 0) {
                        if ($Over_Quantity == 0 && $Under_Quantity_Diff < 0) {
                            $Under_Quantity_Diff = $Under_Quantity + $Previous_Over_Quantity;
                            if (!Update_Item_Balance($Item_ID, $Stock_Taking_Location, "Stock Taking Over", null, null, null, $Stock_Taking_ID, $Document_Date, $Under_Quantity_Diff, false)){
                                $error_code = 3;Rollback_Transaction();
                                break;
                            }
                        } else if ($Under_Quantity == 0 ) {
                            if ($Over_Quantity_Diff > 0) {
                                if (!Update_Item_Balance($Item_ID, $Stock_Taking_Location, "Stock Taking Over", null, null, null, $Stock_Taking_ID, $Document_Date, $Over_Quantity_Diff, false)){
                                    $error_code = 3;Rollback_Transaction();
                                    break;
                                }
                            } else if ($Over_Quantity_Diff < 0) {
                                $Over_Quantity_Diff = $Over_Quantity - $Previous_Over_Quantity;
                                if (!Update_Item_Balance($Item_ID, $Stock_Taking_Location, "Stock Taking Over", null, null, null, $Stock_Taking_ID, $Document_Date, $Over_Quantity_Diff, true)){
                                    $error_code = 3;Rollback_Transaction();
                                    break;
                                }
                            }
                        }
                    }
                }

                $Insert_Stock_Taking_Item = Insert_DB("tbl_stocktaking_items", array(
                    "Item_ID" => $Item_ID,
                    "Under_Quantity" => $Under_Quantity,
                    "Item_Remark" => $Item_Remark,
                    "Over_Quantity" => $Over_Quantity,
                    "Stock_Taking_ID" => $Stock_Taking_ID
                ));
                $hasError = $Insert_Stock_Taking_Item["error"];
                if ($hasError) {
                    $error_code = 3;Rollback_Transaction();
                    echo $Insert_Stock_Taking_Item['errorMsg'];
                }
            }

            //For Those items that existed in the previous document, but no longer exist after editing the document
            //The balance for these items need to returned back to the store in question
            foreach($Stock_Taking_Item_List as $Stock_Taking_Item){
                $Edited_Stock_Taking_Items = $_SESSION['Stock_Taking']['Items'];
                $Does_Not_Exist = true;
                foreach($Edited_Stock_Taking_Items as $Edited_Stock_Taking_Item) {
                    if ($Edited_Stock_Taking_Item['Item_ID'] == $Stock_Taking_Item['Item_ID']) {
                        $Does_Not_Exist = false;
                        break;
                    }
                }

                if ($Does_Not_Exist) {
                    $Item_ID = $Stock_Taking_Item['Item_ID'];
                    $Under_Quantity = $Stock_Taking_Item['Under_Quantity'];
                    $Over_Quantity = $Stock_Taking_Item['Over_Quantity'];
                    $Item_Remark = $Stock_Taking_Item['Item_Remark'];
                    $Stock_Taking_Location = $Stock_Taking['Sub_Department_ID'];

                    $Item = Get_Item_Balance($Item_ID, $Stock_Taking_Location);
                    $Store_Balance = $Item['Item_Balance'];

                    if ($Under_Quantity > 0) {
                        if (!Update_Item_Balance($Item_ID, $Stock_Taking_Location, "Stock Taking Under", null, null, null, $Stock_Taking_ID, $Document_Date, $Under_Quantity, true)){
                            $error_code = 3;
                            break;
                        }
                    } else if ($Over_Quantity > 0) {
                        if (!Update_Item_Balance($Item_ID, $Stock_Taking_Location, "Stock Taking Over", null, null, null, $Stock_Taking_ID, $Document_Date, $Over_Quantity, false)){
                            $error_code = 3;
                            break;
                        }
                    }
                }
            }
        } else {
            $error_code = 5;Rollback_Transaction();
        }

        if ($error_code == 0) {
            $Update_Status = Update_Stock_Taking($Stock_Taking_ID, array(
                "Previous_Stock_Taking_Data" => $Previous_Stock_Taking_Data,
                "Stock_Taking_Status" => "edited"
            ));
            $hasError = $Update_Status["error"];
            if (!$hasError) {
                $error_code = 1;
                Commit_Transaction();
                //Rollback_Transaction();
                if (isset($_SESSION['Stock_Taking_ID'])) {
                    unset($_SESSION['Stock_Taking_ID']);
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