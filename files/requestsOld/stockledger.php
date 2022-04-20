<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include_once("../includes/connection.php");
    include_once("database.php");

function Get_Stock_Ledger_Details($Item_ID, $Sub_Department_ID, $Start_Date, $End_Date) {
        $Item_List = array();

        $Item_Result = Query_DB("SELECT * FROM tbl_stock_ledger_controler
                                   WHERE Movement_Date BETWEEN '$Start_Date' AND '$End_Date'
                                   AND Item_ID = '$Item_ID'
                                   AND Sub_Department_ID = '$Sub_Department_ID'
                                   ORDER BY Controler_ID");

        $hasError = $Item_Result["error"];
        if (!$hasError) {
            $Item_List = array_merge($Item_List, $Item_Result["data"]);
        } else {
            echo $Item_Result["errorMsg"];
        }

        return $Item_List;
    }

    function Get_Stock_Ledger_Records_After($Controler_ID, $Item_ID, $Sub_Department_ID) {
        $Item_List = array();

        $Item_Result = Query_DB("SELECT * FROM tbl_stock_ledger_controler
                                    WHERE Controler_ID > '{$Controler_ID}'
                                    AND Item_ID = '{$Item_ID}'
                                    AND Sub_Department_ID = '{$Sub_Department_ID}'");

        $hasError = $Item_Result["error"];
        if (!$hasError) {
            $Item_List = array_merge($Item_List, $Item_Result["data"]);
        } else {
            echo $Item_Result["errorMsg"];
        }

        return $Item_List;
    }

    function Get_Stock_Ledger_Record($Item_ID, $Sub_Department_ID, $Document_Number, $Movement_Type) {
        $Item = array();

        $Item_Result = Query_DB("SELECT * FROM tbl_stock_ledger_controler
                                   WHERE Item_ID = '{$Item_ID}'
                                   AND Sub_Department_ID = '{$Sub_Department_ID}'
                                   AND Document_Number = '{$Document_Number}'
                                   AND Movement_Type = '{$Movement_Type}'
                                   ORDER BY Controler_ID ASC
                                   LIMIT 1");                          

        $hasError = $Item_Result["error"];
        if (!$hasError) {
            if (!empty($Item_Result["data"])) {
                $Item = $Item_Result["data"][0];
            }
        } else {
            echo $Item_Result["errorMsg"];
        }

        return $Item;
    }

    function Update_Stock_Ledger($Previous_Balance, $Post_Balance, $Current_Stock_Ledger_Record){
        $Quantity_Adjustment = $Post_Balance - $Previous_Balance;
        $Current_Controler_ID = $Current_Stock_Ledger_Record['Controler_ID'];
        $Item_ID = $Current_Stock_Ledger_Record['Item_ID'];
        $Sub_Department_ID = $Current_Stock_Ledger_Record['Sub_Department_ID'];

        if ($Quantity_Adjustment >= 0) {
            $Post_Balance_Adjustment = array("Post_Balance", "+", $Quantity_Adjustment);
            $Pre_Balance_Adjustment = array("Pre_Balance", "+", $Quantity_Adjustment);
        } else {
            $Post_Balance_Adjustment = array("Post_Balance", "", $Quantity_Adjustment);
            $Pre_Balance_Adjustment = array("Pre_Balance", "", $Quantity_Adjustment);
        }

        $Update_Stock_Ledger = Update_DB("tbl_stock_ledger_controler",
            array("Post_Balance" => $Post_Balance_Adjustment),
            array("Controler_ID", "=", $Current_Controler_ID),
            array());

        $hasError = $Update_Stock_Ledger["error"];
        if (!$hasError) {
            $Stock_Ledger_Records_After = Get_Stock_Ledger_Records_After($Current_Controler_ID, $Item_ID, $Sub_Department_ID);
            foreach($Stock_Ledger_Records_After as $Stock_Ledger_Record) {
                $Update_Stock_Ledger_Record = Update_DB("tbl_stock_ledger_controler",
                    array("Post_Balance" => $Post_Balance_Adjustment, "Pre_Balance" => $Pre_Balance_Adjustment),
                    array("Controler_ID", "=", $Stock_Ledger_Record['Controler_ID']),
                    array());

                $hasError = $Update_Stock_Ledger_Record["error"];
                if ($hasError) {
                    echo $Update_Stock_Ledger_Record["errorMsg"];
                    return false;
                }
            }
            return true;
        } else {
            echo $Update_Stock_Ledger["errorMsg"];
            return false;
        }
    }

    function Create_Stock_Ledger($Item_ID, $Sub_Department_ID, $Movement_Type, $Internal_Destination,
                                 $External_Source, $Registration_ID, $Document_Number, $Document_Date, $Previous_Balance, $Post_Balance){

        $Create_Stock_Ledger = Insert_DB("tbl_stock_ledger_controler",
            array(
                "Item_ID" => $Item_ID,
                "Movement_Type" => $Movement_Type,
                "Sub_Department_ID" => $Sub_Department_ID,
                "Internal_Destination" => $Internal_Destination,
                "External_Source" => $External_Source,
                "Registration_ID" => $Registration_ID,
                "Pre_Balance" => $Previous_Balance,
                "Post_Balance" => $Post_Balance,
                "Movement_Date" => $Document_Date,
                "Movement_Date_Time" => Get_Time_Now(),
                "Document_Number" => $Document_Number
            ));
        $hasError = $Create_Stock_Ledger["error"];
        if (!$hasError) {
            return true;
        } else {
            echo $Create_Stock_Ledger["errorMsg"];
            return false;
        }
    }
?>