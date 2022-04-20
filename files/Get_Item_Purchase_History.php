<?php
    @session_start();
    include("./includes/connection.php");
    include("./functions/database.php");
    include("./functions/items.php");
    
    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = '';
    }

    if(isset($_GET['Start_Date'])){
        $Start_Date = $_GET['Start_Date'];
    }else{
        $Start_Date = null;
    }

    if(isset($_GET['End_Date'])){
        $End_Date = $_GET['End_Date'];
    }else{
        $End_Date = null;
    }

    echo "<center>";
    echo "<table width='100%' border='0'><tbody><tr id='thead'>";
    echo "<td width='30%'><b>Supplier</b></td>";
    echo "<td width='20%'><b>Purchase Date</b></td>";
    echo "<td width='20%'><b>Buying Price</b></td>";
    echo "<td width='10%'><b>Grn No</b></td>";
    echo "<td width='20%'><b>Document Type</b></td>";
    echo "</tr><tr>";
    echo "<td colspan=5><hr></td></tr>";

    if ($Item_ID != '') {
        $Purchase_History_List = Get_Item_Purchase_History(null, "", $Item_ID, $Start_Date, $End_Date, 100);
        if (!empty($Purchase_History_List)) {
            foreach($Purchase_History_List as $Purchase_History) {
                if (strtolower($Purchase_History['Type']) == strtolower("GRN Without PO")) {
                    $Grn_Url = "previewgrnwithoutpurchaseorderreport.php?Grn_ID={$Purchase_History['Grn_ID']}";
                } else {
                    $Grn_Url = "grnpurchaseorderreport.php?Grn_Purchase_Order_ID={$Purchase_History['Grn_ID']}";
                }

                echo "<tr>";
                echo "<td> {$Purchase_History['Supplier_Name']} </td>";
                echo "<td> {$Purchase_History['Purchase_Date']} </td>";
                echo "<td> {$Purchase_History['Buying_Price']} </td>";
                echo "<td> <a href='{$Grn_Url}' target='_blank'> {$Purchase_History['Grn_ID']} </a></td>";
                echo "<td> <a href='{$Grn_Url}' target='_blank'> {$Purchase_History['Type']} </a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td style='text-align: center;' colspan=5><h1>No Purchasing History Found</h1></td></tr>";
        }
    }

    echo "</tbody></table>";
    echo "</center>";
?>