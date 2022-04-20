<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include_once("./functions/database.php");
    include_once("./functions/employee.php");
    include_once("./functions/department.php");
    include_once("./functions/returnoutward.php");
    include_once("./functions/items.php");

    if (isset($_GET['Supervisor_Username'])) {
        $Supervisor_Username = mysqli_real_escape_string($conn,$_GET['Supervisor_Username']);
    }

    if (isset($_GET['Supervisor_Password'])) {
        $Supervisor_Password = mysqli_real_escape_string($conn,md5($_GET['Supervisor_Password']));
    }


    if (isset($_GET['Outward_ID'])) {
        $Outward_ID = $_GET['Outward_ID'];
    }

    $error_code = 0;
    if (Is_Logged_In_User($Supervisor_Username, $Supervisor_Password)) {
        $Return_Outward_Item_List = Get_Return_Outward_Items($Outward_ID);
        //$Return_Outward = Get_Return_Outward($Outward_ID);

        Start_Transaction();
        $Document_Date = Get_Time_Now();
        foreach($Return_Outward_Item_List as $Return_Outward_Item) {
            $Quantity_Returned = $Return_Outward_Item['Quantity_Returned'];
            $Item_ID = $Return_Outward_Item['Item_ID'];
            $Sub_Department_ID = $Return_Outward_Item['Sub_Department_ID'];
            $Supplier_ID = $Return_Outward_Item['Supplier_ID'];
            $Store_Balance = $Return_Outward_Item['Store_Balance'];

            /**if ($Quantity_Returned > $Store_Balance) { allow negative balance
                $error_code = 2;
                break;
            } else {**/
                if ($Quantity_Returned != 0) {
                    if (!Update_Item_Balance($Item_ID, $Sub_Department_ID, "Return Outward", null, $Supplier_ID, null, $Outward_ID, $Document_Date, $Quantity_Returned, false)){
                        $error_code = 3;
                        break;
                    }
                }
            //} allow negative balance
        }

        if ($error_code == 0) {
            $Update_Status = Update_Return_Outward_Status($Outward_ID, "saved");
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