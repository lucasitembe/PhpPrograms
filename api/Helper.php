<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Helper
 *
 * @author ADE
 */
class Helper extends Db{
     function Get_Item_Last_Buying_Price_With_Supplier($Item_ID){
        $List_Of_Last_Buying_Price = array();
        $List_GRN_Without_PO = Get_Item_Last_Buying_Price_With_Supplier_From_GRN_Without_PO($Item_ID);
        $List_GRN_With_PO = Get_Item_Last_Buying_Price_With_Supplier_From_GRN_With_PO($Item_ID);
        if (!empty($List_GRN_Without_PO)) {
            foreach($List_GRN_Without_PO as $GRN_Without_PO) {
                $Last_Buying_Price = array("Supplier_ID"=>$GRN_Without_PO["Supplier_ID"],
                                            "Supplier_Name"=>$GRN_Without_PO["Supplier_Name"],
                                            "Grn_ID"=>$GRN_Without_PO["Grn_ID"],
                                            "Grn_Date"=>$GRN_Without_PO["Grn_Date_And_Time"],
                                            "Buying_Price"=>$GRN_Without_PO["Price"],
                                            "Grn_Type" => "GRN Without PO");
                $List_Of_Last_Buying_Price = array_merge($List_Of_Last_Buying_Price, array($Last_Buying_Price));
            }

        }

        if (!empty($List_GRN_With_PO)) {
            foreach($List_GRN_With_PO as $GRN_With_PO) {
                $Last_Buying_Price = array("Supplier_ID"=>$GRN_With_PO["supplier_id"],
                    "Supplier_Name"=>$GRN_With_PO["Supplier_Name"],
                    "Grn_ID"=>$GRN_With_PO["Grn_Purchase_Order_ID"],
                    "Grn_Date"=>$GRN_With_PO["Created_Date_Time"],
                    "Buying_Price"=>$GRN_With_PO["Buying_Price"],
                    "Grn_Type" => "GRN With PO");
                $List_Of_Last_Buying_Price = array_merge($List_Of_Last_Buying_Price, array($Last_Buying_Price));
            }

        }

        if (!empty($List_Of_Last_Buying_Price)) {
            usort($List_Of_Last_Buying_Price, function($a, $b) {
                return $a['Grn_Date'] - $b['Grn_Date'];
            });

            //This is to remove duplicates
            $Final_List_Of_Last_Buying_Price = array();
            $List_Of_Supplier_ID = array();
            foreach ($List_Of_Last_Buying_Price as $Last_Buying_Price) {
                if(!in_array($Last_Buying_Price['Supplier_ID'], $List_Of_Supplier_ID)) {
                    $Final_List_Of_Last_Buying_Price = array_merge($Final_List_Of_Last_Buying_Price, array($Last_Buying_Price));
                    $List_Of_Supplier_ID = array_merge($List_Of_Supplier_ID, array($Last_Buying_Price['Supplier_ID']));
                }
            }
            return $Final_List_Of_Last_Buying_Price;
        } else {
            return array();
        }
    }

    function Get_Item_Last_Buying_Price_With_Supplier_From_GRN_With_PO($Item_ID){
        $Item = array();

        $Supplier_List = Get_List_Of_Supplier_For_Item_In_GRN_With_PO($Item_ID);
        foreach($Supplier_List as $Supplier) {
            $Item_Result = Query_DB("SELECT gpo.supplier_id, poi.Buying_Price, gpo.Grn_Purchase_Order_ID, gpo.Created_Date_Time, s.Supplier_Name
                                    FROM tbl_purchase_order_items poi, tbl_grn_purchase_order gpo, tbl_supplier s
                                    WHERE poi.Grn_Purchase_Order_ID = gpo.Grn_Purchase_Order_ID
                                    AND poi.Item_ID = {$Item_ID}
                                    AND gpo.Supplier_ID = s.Supplier_ID
                                    AND gpo.Supplier_ID = {$Supplier['Supplier_ID']}
                                    ORDER BY gpo.Created_Date_Time DESC
                                    limit 1");
            $hasError = $Item_Result["error"];
            if (!$hasError) {
                if (!empty($Item_Result["data"])) {
                    $Item = array_merge($Item, $Item_Result["data"]);
                }
            } else {
                echo $Item_Result["errorMsg"];
            }
        }

        return $Item;
    }

    function Get_List_Of_Supplier_For_Item_In_GRN_With_PO($Item_ID){
        $Item = array();

        $Item_Result = Query_DB("SELECT gpo.Supplier_ID, s.Supplier_Name
                                    FROM tbl_purchase_order_items poi, tbl_grn_purchase_order gpo, tbl_supplier s
                                    WHERE poi.Grn_Purchase_Order_ID = gpo.Grn_Purchase_Order_ID
                                    AND poi.Item_ID = {$Item_ID}
                                    AND gpo.Supplier_ID = s.Supplier_ID
                                    GROUP BY gpo.Supplier_ID");
        $hasError = $Item_Result["error"];
        if (!$hasError) {
            $Item = array_merge($Item, $Item_Result["data"]);
        } else {
            echo $Item_Result["errorMsg"];
        }

        return $Item;
    }

    function Get_Item_Last_Buying_Price_With_Supplier_From_GRN_Without_PO($Item_ID){
        $Item = array();

        $Supplier_List = Get_List_Of_Supplier_For_Item_In_GRN_Without_PO($Item_ID);
        foreach($Supplier_List as $Supplier) {
            $Item_Result = Query_DB("SELECT gwpo.Supplier_ID, gwpoi.Price, gwpo.Grn_ID, gwpo.Grn_Date_And_Time, s.Supplier_Name
                                     FROM tbl_grn_without_purchase_order_items gwpoi, tbl_grn_without_purchase_order gwpo, tbl_supplier s
                                     WHERE gwpoi.Grn_ID = gwpo.Grn_ID
                                     AND gwpoi.Item_ID = {$Item_ID}
                                     AND gwpo.Supplier_ID = s.Supplier_ID
                                     AND gwpo.Supplier_ID = {$Supplier['Supplier_ID']}
                                     ORDER BY gwpo.Grn_Date_And_Time DESC
                                     LIMIT 1");
            $hasError = $Item_Result["error"];
            if (!$hasError) {
                if (!empty($Item_Result["data"])) {
                    $Item = array_merge($Item, $Item_Result["data"]);
                }
            } else {
                echo $Item_Result["errorMsg"];
            }
        }

        return $Item;
    }

    function Get_Item_Last_Buying_Price_From_Open_Balance($Item_ID){
        $Item = array();
        $Item_Result = Query_DB("SELECT grn.Saved_Date_Time, grni.Buying_Price
                                     FROM tbl_grn_open_balance grn, tbl_grn_open_balance_items grni
                                     WHERE grn.Grn_Open_Balance_ID = grni.Grn_Open_Balance_ID
                                     AND grni.Item_ID = {$Item_ID}
                                     AND grn.Grn_Open_Balance_Status = 'saved'
                                     ORDER BY grni.Open_Balance_Item_ID DESC
                                     LIMIT 1");
        $hasError = $Item_Result["error"];
        if (!$hasError) {
            if (!empty($Item_Result["data"])) {
                $Item = array_merge($Item, $Item_Result["data"]);
            }
        } else {
            echo $Item_Result["errorMsg"];
        }
        return $Item;
    }

    function Get_Last_Buy_Price($Item_ID){
        
        $Pr1 = 0; $Pr2 = 0; $Pr3 = 0;
        $PD1 = '0000/00/00 00:00'; $PD2 = '0000/00/00 00:00'; $PD3 = '0000/00/00 00:00';

        $Price1 = Get_Item_Last_Buying_Price_With_Supplier_From_GRN_Without_PO($Item_ID);
        foreach($Price1 as $D1) {
            $Pr1 = $D1['Price']; //Price 1 (Without Purchase Order)
            $PD1 = $D1['Grn_Date_And_Time']; //Date 1 (Without Purchase Order)
        }

        $Price2 = Get_Item_Last_Buying_Price_With_Supplier_From_GRN_With_PO($Item_ID);
        foreach($Price2 as $D2) {
            $Pr2 = $D2['Buying_Price']; //Price 2 (With Purchase Order)
            $PD2 = $D2['Created_Date_Time']; //Date 2 (With Purchase Order)
        }

        $Price3 = Get_Item_Last_Buying_Price_From_Open_Balance($Item_ID);
        foreach($Price3 as $D3) {
            $Pr3 = $D3['Buying_Price']; //Price 2 (As Open Balance)
            $PD3 = $D3['Saved_Date_Time']; //Date 2 (As Open Balance)
        }

        if($Pr1 == '' || $Pr1 == null){ $Pr1 = 0; }
        if($Pr2 == '' || $Pr2 == null){ $Pr2 = 0; }
        if($Pr3 == '' || $Pr3 == null){ $Pr3 = 0; }

        if($PD1 == '' || $PD1 == null){ $PD1 = '0000/00/00 00:00:00'; }
        if($PD2 == '' || $PD2 == null){ $PD2 = '0000/00/00 00:00:00'; }
        if($PD3 == '' || $PD3 == null){ $PD3 = '0000/00/00 00:00:00'; }

        if($PD1 > $PD2 && $PD1 > $PD3){
            return $Pr1;
        }else if($PD2 > $PD1 && $PD2 > $PD3){
            return $Pr2;
        }else{
            return $Pr3;
        }
    }
}
