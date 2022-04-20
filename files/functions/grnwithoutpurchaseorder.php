<?php

include_once("./includes/connection.php");
include_once("./functions/database.php");

function Get_GRN_Without_Purchase_Order_List($Sub_Department_ID, $Start_Date, $End_Date, $Employee_ID, $Supplier_ID,$Order_No, $Limit) {
    $Purchase_Order_Item_List = array();

    $filter = " and sd.Sub_Department_ID = '$Sub_Department_ID' ";

    if (!empty($Start_Date) && !empty($End_Date)) {
        $filter = " and gpo.Grn_Date_And_Time between '$Start_Date' and '$End_Date' and sd.Sub_Department_ID = '$Sub_Department_ID' ";
    }

    if (!empty($Order_No)) {
        $filter = " and gpo.Grn_ID = '$Order_No".' dd'."$Limit' ";
    }

    if (!empty($Supplier_ID)) {
        $filter .=" and gpo.Supplier_ID = '$Supplier_ID' ";
    }
    
    if (!empty($Employee_ID)) {
        //$filter .=" and gpo.Employee_ID = '$Employee_ID' ";
    }
    
    
    $Purchase_Order_Item_Result = Query_DB("SELECT  gpo.Grn_ID, gpo.Grn_Date_And_Time, gpo.Supervisor_ID,
                                                     sd.Sub_Department_Name, sp.Supplier_Name
                                                      FROM
                                                      tbl_grn_without_purchase_order gpo, tbl_supplier sp,
                                                      tbl_sub_department sd
                                                      WHERE 
                                                       gpo.Supplier_ID = sp.Supplier_ID
                                                      AND gpo.Sub_Department_ID = sd.Sub_Department_ID
                                                      $filter
                                                      ORDER BY Grn_ID DESC
                                                      LIMIT {$Limit}");
    $hasError = $Purchase_Order_Item_Result["error"];
    if (!$hasError) {
        $Purchase_Order_Item_List = array_merge($Purchase_Order_Item_List, $Purchase_Order_Item_Result["data"]);
    } else {
        echo $Purchase_Order_Item_Result["errorMsg"];
    }

    return $Purchase_Order_Item_List;
}

?>