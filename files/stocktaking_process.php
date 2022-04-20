<?php

    session_start();

    include_once("./functions/database.php");
    include_once("./functions/employee.php");
    include_once("./functions/stocktaking.php");
    include_once("./functions/items.php");

    if (isset($_GET['Supervisor_Username'])) {
        $Supervisor_Username = mysqli_real_escape_string($conn,$_GET['Supervisor_Username']);
    }

    if (isset($_GET['Supervisor_Password'])) {
        $Supervisor_Password = mysqli_real_escape_string($conn,md5($_GET['Supervisor_Password']));
    }


    if (isset($_GET['Stock_Taking_ID'])) {
        $Stock_Taking_ID = $_GET['Stock_Taking_ID'];
    }

    $error_code = 0;
    if (Is_Logged_In_User($Supervisor_Username, $Supervisor_Password)) {
        $Stock_Taking_Items = Get_Stock_Taking_Items($Stock_Taking_ID);

        Start_Transaction();
        $Document_Date = Get_Time_Now();
        foreach($Stock_Taking_Items as $Stock_Taking_Item) {
            $Over_Quantity = $Stock_Taking_Item['Over_Quantity'];
            $Under_Quantity = $Stock_Taking_Item['Under_Quantity'];
            $Item_ID = $Stock_Taking_Item['Item_ID'];
            $Stock_Taking_Location = $Stock_Taking_Item['Sub_Department_ID'];
            
            $Store_Balance = $Stock_Taking_Item['Store_Balance'];

            if (($Store_Balance - ($Under_Quantity - $Over_Quantity)) < 0 ) {
                $error_code = 2;Rollback_Transaction();
                break;
            } else {
                if ($Under_Quantity > 0) {
                    if (!Update_Item_Balance($Item_ID, $Stock_Taking_Location, "Stock Taking Under", null, null, null, $Stock_Taking_ID, $Document_Date, $Under_Quantity, false)){
                        $error_code = 3;Rollback_Transaction();
                        break;
                    }
                } else if ($Over_Quantity > 0) {
                    if (!Update_Item_Balance($Item_ID, $Stock_Taking_Location, "Stock Taking Over", null, null, null, $Stock_Taking_ID, $Document_Date, $Over_Quantity, true)){
                        $error_code = 3;Rollback_Transaction();
                        break;
                    }
                } else {
                    $error_code = 5;Rollback_Transaction();
                    //echo "Item ID : " . $Item_ID;
                    break;
                }
            }
        }

        if ($error_code == 0) {
            $Update_Status = Update_Stock_Taking_Status($Stock_Taking_ID, "saved");
            $hasError = $Update_Status["error"];
            if (!$hasError) {
                $error_code = 1;
                Commit_Transaction();
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