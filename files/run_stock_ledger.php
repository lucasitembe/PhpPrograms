<?php
    include_once("./functions/database.php");
    include_once("./functions/items.php");
    include_once("./functions/stockledger.php");

    $Stock_Ledger_List = array();
    $Start_Date = "2016-02-01 00:00:00";
    $End_Date = "2016-03-31 00:00:00";
    $Store_ID = 7;

    /**
     * ISSUE NOTE MANUAL
     */
    $Issue_Manual_Item_Result = Query_DB("SELECT im.Issue_Date_And_Time as Movement_Date_Time, im.Issue_ID as Document_Number,
                                          ii.Item_ID, im.Store_Issuing as Sub_Department_ID,
                                          'Issue Note Manual' as Movement_Type, im.Store_Need as Internal_Destination,
                                          null as External_Source, null as Registration_ID,
                                          ii.Quantity_Issued as Outward_Quantity, 0 as Inward_Quantity
                                          FROM tbl_issuemanual_items ii, tbl_issuesmanual im
                                          WHERE  ii.Issue_ID = im.Issue_ID
                                          AND im.Issue_Date_And_Time BETWEEN '{$Start_Date}' AND '{$End_Date}'
                                          AND im.status in ('saved', 'edited')");
    $hasError = $Issue_Manual_Item_Result["error"];
    if (!$hasError) {
        $Stock_Ledger_List = array_merge($Stock_Ledger_List, $Issue_Manual_Item_Result["data"]);
    } else {
        echo $Issue_Manual_Item_Result["errorMsg"];
    }

    /**
     * ISSUE NOTE
     */
    $Issue_Note_Item_Result = Query_DB("SELECT im.Issue_Date_And_Time as Movement_Date_Time, im.Issue_ID as Document_Number,
                                          ii.Item_ID, r.Store_Issue as Sub_Department_ID,
                                          'Issue Note' as Movement_Type, r.Store_Need as Internal_Destination,
                                          null as External_Source, null as Registration_ID,
                                          ii.Quantity_Issued as Outward_Quantity, 0 as Inward_Quantity
                                          FROM tbl_requisition_items ii, tbl_issues im, tbl_requisition r
                                          WHERE  ii.Issue_ID = im.Issue_ID
                                          AND  im.Requisition_ID = r.Requisition_ID
                                          AND im.Issue_Date_And_Time BETWEEN '{$Start_Date}' AND '{$End_Date}'
                                          AND r.Requisition_Status in ('Served', 'Received')");
    $hasError = $Issue_Note_Item_Result["error"];
    if (!$hasError) {
        $Stock_Ledger_List = array_merge($Stock_Ledger_List, $Issue_Note_Item_Result["data"]);
    } else {
        echo $Issue_Note_Item_Result["errorMsg"];
    }

    /**
     * RETURN INWARD
     */
    $Return_Inward_Item_Result = Query_DB("SELECT im.Inward_Date as Movement_Date_Time, im.Inward_ID as Document_Number,
                                          ii.Item_ID, im.Store_Sub_Department_ID as Sub_Department_ID,
                                          'Return Inward' as Movement_Type, im.Return_Sub_Department_ID as Internal_Destination,
                                          null as External_Source, null as Registration_ID,
                                          ii.Quantity_Returned as Inward_Quantity, 0 as Outward_Quantity
                                          FROM tbl_return_inward_items ii, tbl_return_inward im
                                          WHERE  ii.Inward_ID = im.Inward_ID
                                          AND im.Inward_Date BETWEEN '{$Start_Date}' AND '{$End_Date}'
                                          AND im.Inward_Status in ('Submitted', 'Saved')");
    $hasError = $Return_Inward_Item_Result["error"];
    if (!$hasError) {
        $Stock_Ledger_List = array_merge($Stock_Ledger_List, $Return_Inward_Item_Result["data"]);
    } else {
        echo $Return_Inward_Item_Result["errorMsg"];
    }

    /**
     * RETURN OUTWARD
     */
    $Return_Outward_Item_Result = Query_DB("SELECT im.Outward_Date as Movement_Date_Time, im.Outward_ID as Document_Number,
                                              ii.Item_ID, im.Sub_Department_ID as Sub_Department_ID,
                                              'Return Outward' as Movement_Type, null as Internal_Destination,
                                              im.Supplier_ID as External_Source, null as Registration_ID,
                                              ii.Quantity_Returned as Outward_Quantity, 0 as Inward_Quantity
                                              FROM tbl_return_outward_items ii, tbl_return_outward im
                                              WHERE  ii.Outward_ID = im.Outward_ID
                                              AND im.Outward_Date BETWEEN '{$Start_Date}' AND '{$End_Date}'
                                              AND im.Outward_Status in ('Submitted', 'Saved')");
    $hasError = $Return_Outward_Item_Result["error"];
    if (!$hasError) {
        $Stock_Ledger_List = array_merge($Stock_Ledger_List, $Return_Outward_Item_Result["data"]);
    } else {
        echo $Return_Outward_Item_Result["errorMsg"];
    }

    /**
     * DISPOSAL
     */
    $Disposal_Item_Result = Query_DB("SELECT im.Disposed_Date as Movement_Date_Time, im.Disposal_ID as Document_Number,
                                          ii.Item_ID, im.Sub_Department_ID as Sub_Department_ID,
                                          'Disposal' as Movement_Type, null as Internal_Destination,
                                          null as External_Source, null as Registration_ID,
                                          ii.Quantity_Disposed as Outward_Quantity, 0 as Inward_Quantity
                                          FROM tbl_disposal_items ii, tbl_disposal im
                                          WHERE  ii.Disposal_ID = im.Disposal_ID
                                          AND im.Disposed_Date BETWEEN '{$Start_Date}' AND '{$End_Date}'
                                          AND im.Disposal_Status in ('Submitted', 'Saved', 'Edited')");
    $hasError = $Disposal_Item_Result["error"];
    if (!$hasError) {
        $Stock_Ledger_List = array_merge($Stock_Ledger_List, $Disposal_Item_Result["data"]);
    } else {
        echo $Disposal_Item_Result["errorMsg"];
    }

    /**
     * STOCK TAKING
     */
    $Stock_Taking_Item_Result = Query_DB("SELECT im.Stock_Taking_Date as Movement_Date_Time, im.Stock_Taking_ID as Document_Number,
                                      ii.Item_ID, im.Sub_Department_ID as Sub_Department_ID,
                                      IF( Under_Quantity > 0, 'Stock Taking Under', 'Stock Taking Over' ) as Movement_Type,
                                      null as Internal_Destination, null as External_Source, null as Registration_ID,
                                      ii.Under_Quantity as Outward_Quantity, ii.Over_Quantity as Inward_Quantity
                                      FROM tbl_stocktaking_items ii, tbl_stocktaking im
                                      WHERE  ii.Stock_Taking_ID = im.Stock_Taking_ID
                                      AND im.Stock_Taking_Date BETWEEN '{$Start_Date}' AND '{$End_Date}'
                                      AND im.Stock_Taking_Status in ('Submitted', 'Saved', 'Edited')");
    $hasError = $Stock_Taking_Item_Result["error"];
    if (!$hasError) {
        $Stock_Ledger_List = array_merge($Stock_Ledger_List, $Stock_Taking_Item_Result["data"]);
    } else {
        echo $Stock_Taking_Item_Result["errorMsg"];
    }

    /**
     * GRN WITHOUT PO
     */
    $GRN_Without_PO_Item_Result = Query_DB("SELECT im.Delivery_Date as Movement_Date_Time, im.GRN_ID as Document_Number,
                                          ii.Item_ID, im.Sub_Department_ID as Sub_Department_ID,
                                          'Without Purchase' as Movement_Type,
                                          null as Internal_Destination, im.Supplier_ID as External_Source, null as Registration_ID,
                                          0 as Outward_Quantity, ii.Quantity_Required as Inward_Quantity
                                          FROM tbl_grn_without_purchase_order_items ii, tbl_grn_without_purchase_order im
                                          WHERE  ii.GRN_ID = im.GRN_ID
                                          AND im.Delivery_Date BETWEEN '{$Start_Date}' AND '{$End_Date}'");
    $hasError = $GRN_Without_PO_Item_Result["error"];
    if (!$hasError) {
        $Stock_Ledger_List = array_merge($Stock_Ledger_List, $GRN_Without_PO_Item_Result["data"]);
    } else {
        echo $GRN_Without_PO_Item_Result["errorMsg"];
    }

    /**
     * GRN WITH PO
     */
    /*$GRN_With_PO_Item_Result = Query_DB("SELECT im.Delivery_Date as Movement_Date_Time, im.GRN_ID as Document_Number,
                                              ii.Item_ID, im.Sub_Department_ID as Sub_Department_ID,
                                              'From External' as Movement_Type,
                                              null as Internal_Destination, im.Supplier_ID as External_Source, null as Registration_ID,
                                              0 as Outward_Quantity, ii.Quantity_Required as Inward_Quantity
                                              FROM tbl_purchase_order_items poi, tbl_grn_purchase_order gpo
                                              WHERE  ii.GRN_ID = im.GRN_ID
                                              AND im.Delivery_Date BETWEEN '{$Start_Date}' AND '{$End_Date}'");
    $hasError = $GRN_With_PO_Item_Result["error"];
    if (!$hasError) {
        $Stock_Ledger_List = array_merge($Stock_Ledger_List, $GRN_With_PO_Item_Result["data"]);
    } else {
        echo $GRN_With_PO_Item_Result["errorMsg"];
    }*/

    usort($Stock_Ledger_List, function($a, $b) {
        if ( $a['Movement_Date_Time'] == $b['Movement_Date_Time']) { return 0; }
        return (strtotime($b['Movement_Date_Time']) < strtotime($a['Movement_Date_Time'])) ? 1 : -1;
    });

    Start_Transaction();
    $error_code = 0;

    //Clear Store Balance To Zero (All Items)
    $Clear_Store_Balance = Update_DB("tbl_items_balance",
        array("Item_Balance" => 0),
        array("Sub_Department_ID", "=", $Store_ID),
        array());

    $hasError = $Clear_Store_Balance["error"];
    if ($hasError) {
        echo $Clear_Store_Balance["errorMsg"];
        Rollback_Transaction(); $error_code = 1;
    }

    //Get Balance as EHMS 1
    $EHMS_ONE_Item_List = array();
    $EHMS_ONE_Item_Result = Query_DB("SELECT *
                                      FROM ehms_one_item_balance
                                      WHERE location = 'MainStore'");
    $hasError = $EHMS_ONE_Item_Result["error"];
    if (!$hasError) {
        $EHMS_ONE_Item_List = array_merge($EHMS_ONE_Item_List, $EHMS_ONE_Item_Result["data"]);

        foreach($EHMS_ONE_Item_List as $EHMS_ONE_Item) {
            $Item = Get_Item_By_Name_Code($EHMS_ONE_Item['name'], $EHMS_ONE_Item['id']);

            if (!empty($Item)) {
                $Brought_Forward_Balance = Update_DB("tbl_items_balance",
                    array("Item_Balance" => $EHMS_ONE_Item['balance']),
                    array("Sub_Department_ID", "=", $Store_ID),
                    array("Item_ID", "=", $Item['Item_ID']));

                $hasError = $Brought_Forward_Balance["error"];
                if ($hasError) {
                    echo $Brought_Forward_Balance["errorMsg"];
                    Rollback_Transaction(); $error_code = 1;
                }
            } else {
                echo "Product : {$EHMS_ONE_Item['id']} {$EHMS_ONE_Item['name']} <br/>";
            }
        }
    } else {
        echo $EHMS_ONE_Item_Result["errorMsg"];
        Rollback_Transaction(); $error_code = 1;
    }

    //Clear Store Ledger
    $Clear_Store_Ledger = Delete_From("tbl_stock_ledger_controler",
        array("Sub_Department_ID", "=", $Store_ID));

    $hasError = $Clear_Store_Ledger["error"];
    if ($hasError) {
        echo $Clear_Store_Ledger["errorMsg"];
        Rollback_Transaction(); $error_code = 1;
    }

    //Run Store Ledger
    echo "<table>";
    echo "<tr>";
    echo "<td>Movement Type</td>";
    echo "<td>Movement Date Time</td>";
    echo "<td>Document Number</td>";
    echo "<td>Sub Department ID</td>";
    echo "<td>Item ID</td>";
    echo "<td>INWARD FLOW</td>";
    echo "<td>OUTWARD FLOW</td>";
    echo "</tr>";
    foreach($Stock_Ledger_List as $Stock_Ledger) {
        echo "<tr>";
        echo "<td> {$Stock_Ledger['Movement_Type']} </td>";
        echo "<td> {$Stock_Ledger['Movement_Date_Time']} </td>";
        echo "<td> {$Stock_Ledger['Document_Number']} </td>";
        echo "<td> {$Stock_Ledger['Sub_Department_ID']} </td>";
        echo "<td> {$Stock_Ledger['Item_ID']} </td>";
        echo "<td> {$Stock_Ledger['Inward_Quantity']} </td>";
        echo "<td> {$Stock_Ledger['Outward_Quantity']} </td>";
        echo "</tr>";

        if ($Stock_Ledger['Inward_Quantity'] > 0) {
            if (!Update_Item_Balance($Stock_Ledger['Item_ID'],
                $Stock_Ledger['Sub_Department_ID'],
                $Stock_Ledger['Movement_Type'],
                $Stock_Ledger['Internal_Destination'],
                $Stock_Ledger['External_Source'],
                $Stock_Ledger['Registration_ID'],
                $Stock_Ledger['Document_Number'],
                $Stock_Ledger['Movement_Date_Time'],
                $Stock_Ledger['Inward_Quantity'], true)){
                Rollback_Transaction();$error_code = 1;
                break;
            }
        } else {
            if (!Update_Item_Balance($Stock_Ledger['Item_ID'],
                $Stock_Ledger['Sub_Department_ID'],
                $Stock_Ledger['Movement_Type'],
                $Stock_Ledger['Internal_Destination'],
                $Stock_Ledger['External_Source'],
                $Stock_Ledger['Registration_ID'],
                $Stock_Ledger['Document_Number'],
                $Stock_Ledger['Movement_Date_Time'],
                $Stock_Ledger['Outward_Quantity'], false)){
                Rollback_Transaction();$error_code = 1;
                break;
            }
        }
    }
    echo "</table>";

    if ($error_code == 0) {
        Commit_Transaction();
    }
?>