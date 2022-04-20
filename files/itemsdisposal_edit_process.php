<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php

    include_once("./functions/database.php");
    include_once("./functions/employee.php");
    include_once("./functions/itemsdisposal.php");
    include_once("./functions/items.php");
    include_once("./functions/parser.php");

    if (isset($_GET['Supervisor_Username'])) {
        $Supervisor_Username = mysqli_real_escape_string($conn,$_GET['Supervisor_Username']);
    }

    if (isset($_GET['Supervisor_Password'])) {
        $Supervisor_Password = mysqli_real_escape_string($conn,md5($_GET['Supervisor_Password']));
    }


    if (isset($_GET['Disposal_ID'])) {
        $Disposal_ID = $_GET['Disposal_ID'];
    }

    if (isset($_SESSION['userinfo']['Employee_Name'])) {
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    } else {
        $Employee_Name = 'Unknown Employee';
    }

    $error_code = 0;
    if (Is_Logged_In_User($Supervisor_Username, $Supervisor_Password)) {

        Start_Transaction();

        $Items_Disposal_Item_List = Get_Items_Disposal_Items($Disposal_ID);
        $Current_Edit = array(
            "Edit_Date" => Get_Time_Now(),
            "Edited_By" => $Employee_Name,
            "Previous_Data" => $Items_Disposal_Item_List
        );

        $Disposal = Get_Items_Disposal($Disposal_ID);
        if ($Disposal['Disposal_Status'] == "edited") {
            $Previous_Edit = jsonToArray($Disposal['Previous_Disposal_Data']);
            $Previous_Edit = array_merge($Previous_Edit, array($Current_Edit));
        } else {
            $Previous_Edit  = array_values(array("Previous_Disposal_Data" => $Current_Edit));
        }
        $Previous_Disposal_Data = toJson($Previous_Edit);

        $Document_Date = Get_Time_Now();
        if (Remove_Disposal_Items_By_Disposal_ID($Disposal_ID)) {
            $Edited_Disposal_Items = $_SESSION['Disposal_Edit']['Items'];
            foreach($Edited_Disposal_Items as $Edited_Disposal_Item) {
                $Item_ID = $Edited_Disposal_Item['Item_ID'];
                $Quantity_Disposed = $Edited_Disposal_Item['Quantity_Disposed'];
                $Item_Remark = $Edited_Disposal_Item['Item_Remark'];
                $Disposal_Location = $Disposal['Sub_Department_ID'];

                $Item = Get_Item_Balance($Item_ID, $Disposal_Location);
                $Store_Balance = $Item['Item_Balance'];

                $Previous_Disposal_Quantity = 0;
                foreach($Items_Disposal_Item_List as $Temp_Disposal) {
                    if ($Temp_Disposal['Item_ID'] == $Item_ID) {
                        $Previous_Disposal_Quantity = $Temp_Disposal['Quantity_Disposed'];
                        break;
                    }
                }

                if ($Previous_Disposal_Quantity == 0) {
                    if ($Quantity_Disposed > $Store_Balance) {
                        $error_code = 2;
                        break;
                    } else {
                        if (!Update_Item_Balance($Item_ID, $Disposal_Location, "Disposal", null, null, null, $Disposal_ID, $Document_Date, $Quantity_Disposed, false)){
                            $error_code = 3;
                            break;
                        }
                    }
                } else {
                    $Quantity_Disposed_Diff = $Previous_Disposal_Quantity - $Quantity_Disposed;
                    if ($Quantity_Disposed_Diff > 0) {
                        if (!Update_Item_Balance($Item_ID, $Disposal_Location, "Disposal", null, null, null, $Disposal_ID, $Document_Date, $Quantity_Disposed_Diff, true)){
                            $error_code = 3;
                            break;
                        }
                    } else if ($Quantity_Disposed_Diff < 0) {
                        $Quantity_Disposed_Diff = $Quantity_Disposed - $Previous_Disposal_Quantity;
                        if (!Update_Item_Balance($Item_ID, $Disposal_Location, "Disposal", null, null, null, $Disposal_ID, $Document_Date, $Quantity_Disposed_Diff, false)){
                            $error_code = 3;
                            break;
                        }
                    }
                }

                $Insert_Items_Disposal_Item = Insert_DB("tbl_disposal_items", array(
                    "Item_ID" => $Item_ID,
                    "Quantity_Disposed" => $Quantity_Disposed,
                    "Item_Remark" => $Item_Remark,
                    "Disposal_ID" => $Disposal_ID
                ));
                $hasError = $Insert_Items_Disposal_Item["error"];
                if ($hasError) {
                    $error_code = 3;
                    echo $Insert_Items_Disposal_Item['errorMsg'];
                }
            }



            foreach($Items_Disposal_Item_List as $Disposal_Item){
                $Edited_Disposal_Items = $_SESSION['Disposal_Edit']['Items'];
                $Does_Not_Exist = true;
                foreach($Edited_Disposal_Items as $Edited_Disposal_Item) {
                    if ($Edited_Disposal_Item['Item_ID'] == $Disposal_Item['Item_ID']) {
                        $Does_Not_Exist = false;
                        break;
                    }
                }

                if ($Does_Not_Exist) {
                    $Item_ID = $Disposal_Item['Item_ID'];
                    $Quantity_Disposed = $Disposal_Item['Quantity_Disposed'];
                    $Item_Remark = $Disposal_Item['Item_Remark'];
                    $Disposal_Location = $Disposal['Sub_Department_ID'];

                    $Item = Get_Item_Balance($Item_ID, $Disposal_Location);
                    $Store_Balance = $Item['Item_Balance'];

                    if (!Update_Item_Balance($Item_ID, $Disposal_Location, "Disposal", null, null, null, $Disposal_ID, $Document_Date, $Quantity_Disposed, true)){
                        $error_code = 3;
                        break;
                    }
                }
            }
        } else {
            $error_code = 5;
        }
        /*Start_Transaction();
        foreach($Items_Disposal_Item_List as $Items_Disposal_Item) {
            $Quantity_Disposed = $Items_Disposal_Item['Quantity_Disposed'];
            $Item_ID = $Items_Disposal_Item['Item_ID'];
            $Disposal_Location = $Items_Disposal_Item['Sub_Department_ID'];
            
            $Store_Balance = $Items_Disposal_Item['Store_Balance'];

            if ($Quantity_Disposed > $Store_Balance) {
                $error_code = 2;
                break;
            } else {
                if (!Update_Item_Balance($Item_ID, $Disposal_Location, "Disposal", null, null, null, $Disposal_ID, $Quantity_Disposed, false)){
                    $error_code = 3;
                    break;
                }
            }
        }

        */

        if ($error_code == 0) {
            $Update_Status = Update_Disposal($Disposal_ID, array(
                "Previous_Disposal_Data" => $Previous_Disposal_Data,
                "Disposal_Status" => "edited"
            ));
            $hasError = $Update_Status["error"];
            if (!$hasError) {
                $error_code = 1;
                Commit_Transaction();
                if (isset($_SESSION['Disposal_ID'])) {
                    unset($_SESSION['Disposal_ID']);
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