<?php
    include_once("./includes/connection.php");
    include_once("./functions/database.php");

    function Get_Outstanding_Store_Order_List($Limit) {
        $Store_Order_List = array();

        $Store_Order_Result = Query_DB("SELECT
                                                            gpo.Grn_ID, gpo.Grn_Date_And_Time, gpo.Supervisor_ID,
                                                            emp.Employee_Name, sd.Sub_Department_Name, sp.Supplier_Name
                                                      FROM
	    						                            tbl_grn_without_purchase_order gpo, tbl_supplier sp,
	    						                            tbl_sub_department sd, tbl_employee emp
                                                      WHERE gpo.Employee_ID = emp.Employee_ID
                                                      {$Employee_ID_Statement}
                                                      AND gpo.Supplier_ID = sp.Supplier_ID
                                                      {$Supplier_ID_Statement}
                                                      AND gpo.Sub_Department_ID = sd.Sub_Department_ID
                                                      AND gpo.Sub_Department_ID = '{$Sub_Department_ID}'
                                                      {$Start_End_Date_Statement}
                                                      ORDER BY Grn_ID DESC
                                                      LIMIT {$Limit}");
        $hasError = $Store_Order_Result["error"];
        if (!$hasError) {
            $Store_Order_List = array_merge($Store_Order_List, $Store_Order_Result["data"]);
        } else {
            echo $Store_Order_Result["errorMsg"];
        }

        return $Store_Order_List;
    }
?>