<?php
session_start();
include("./includes/connection.php");
$temp = 1;

if (isset($_SESSION['Storage_Info'])) {
    $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
} else {
    $Sub_Department_ID = 0;
}

if (isset($_SESSION['Storage_Info'])) {
    $Sub_Department_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
} else {
    $Sub_Department_Name = 0;
}

if (isset($_GET['Start_Date'])) {
    $Start_Date = $_GET['Start_Date'];
}

if (isset($_GET['End_Date'])) {
    $End_Date = $_GET['End_Date'];
}

if (isset($_GET['Supplier_ID'])) {
    $Supplier_ID = $_GET['Supplier_ID'];
}

if (isset($_GET['Order_No'])) {
    $Order_No = $_GET['Order_No'];
}

$filter = " and sd.sub_department_id = '$Sub_Department_ID' ";

if (!empty($Start_Date) && !empty($End_Date)) {
    $filter = " and po.Sent_Date between '$Start_Date' and '$End_Date' and sd.sub_department_id = '$Sub_Department_ID' ";
}

if (!empty($Order_No)) {
    $filter = " and poi.Grn_Purchase_Order_ID = '$Order_No' ";
}

if (!empty($Supplier_ID)) {
    $filter .=" and po.supplier_id = '$Supplier_ID' ";
}
?>
<legend style="background-color:#006400;color:white;padding:5px;" align='right'><b><?php
        if (isset($_SESSION['Storage_Info'])) {
            echo $Sub_Department_Name;
        }
        ?>, Previous GRN Agains Purchase Order</b></legend>
<table width=100% style="border-collapse:collapse !important; border:none !important;">
    <tr><td colspan="9"><hr></td></tr>
    <tr>
        <td width=4% style='text-align: center;'><b>SN</b></td>
        <td width=6% style='text-align:center;'><b>Order N<u>o</u></b></td>
        <td width=6% style='text-align:center;'><b>Grn N<u>o</u></b></td>
        <td width=15%><b>Order Date & Time</b></td>
        <td width=10%><b>Store Need</b></td>
        <td width=20%><b>Supplier Name</b></td>
        <td width=40%><b>Order Description</b></td>
    </tr>
    <tr><td colspan="9"><hr></td></tr>
    <?php
    //select order data
//    $select_Order_Details = mysqli_query($conn,"select po.Purchase_Order_ID,Grn_Purchase_Order_ID,Created_Date,Sub_Department_Name,Supplier_Name,Order_Description from tbl_purchase_order po, tbl_sub_department sd, tbl_supplier sp where
//                                            po.sub_department_id = sd.sub_department_id and
//                                            po.Supplier_ID = sp.Supplier_ID and
//                                            po.order_status = 'served' 
//                                            $filter group by Purchase_Order_ID order by Purchase_Order_ID desc limit 100") or die(mysqli_error($conn));
//    
    //select order data
    $select_Order_Details = mysqli_query($conn,"select 'actual' as source, po.Purchase_Order_ID,poi.Grn_Purchase_Order_ID,Created_Date,Sub_Department_Name,Supplier_Name,Order_Description from tbl_purchase_order po,tbl_purchase_order_items poi, tbl_sub_department sd, tbl_supplier sp where
                                            po.sub_department_id = sd.sub_department_id and
                                            po.Purchase_Order_ID = poi.Purchase_Order_ID and
                                            po.Supplier_ID = sp.Supplier_ID and
                                            po.order_status = 'served'
                                            $filter
                                            
                                            UNION

                                            select 'pending' as source,  po.Purchase_Order_ID,poi.Grn_Purchase_Order_ID,Created_Date,Sub_Department_Name,Supplier_Name,Order_Description from tbl_purchase_order po,tbl_pending_purchase_order_items poi, tbl_sub_department sd, tbl_supplier sp where
                                               po.sub_department_id = sd.sub_department_id and
                                               po.Purchase_Order_ID = poi.Purchase_Order_ID and
                                               po.Supplier_ID = sp.Supplier_ID and
                                               po.order_status = 'served'
                                               $filter
                                         
                                         
               
                 group by Grn_Purchase_Order_ID order by Grn_Purchase_Order_ID desc limit 100") or die(mysqli_error($conn));

    $no = mysqli_num_rows($select_Order_Details);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Order_Details)) {
            $href = '';
            if (isset($_SESSION['userinfo']['can_edit']) && $_SESSION['userinfo']['can_edit'] == 'yes') {
                if ($row['source'] == 'pending') {
                    $href = "<a href='grnpendingpurchaseorderpreview.php?src=edit&Purchase_Order_ID=" . $row['Purchase_Order_ID'] . "&Grn_Purchase_Order_ID=" . $row['Grn_Purchase_Order_ID'] . "&GrnPurchaseOrder=GrnPurchaseOrderThisPage' target='_Parent' class='art-button-green'>Edit</a>";
                } else {
                    $href = "<a href='grnpurchaseorder.php?src=edit&Purchase_Order_ID=" . $row['Purchase_Order_ID'] . "&Grn_Purchase_Order_ID=" . $row['Grn_Purchase_Order_ID'] . "&GrnPurchaseOrder=GrnPurchaseOrderThisPage' target='_Parent' class='art-button-green'>Edit</a>";
                }
            }

            echo "<tr><td style='text-align:center;'>" . $temp . "</td>";
            echo "<td style='text-align: center;'>" . $row['Purchase_Order_ID'] . "</td>";
            echo "<td style='text-align: center;'>" . $row['Grn_Purchase_Order_ID'] . "</td>";
            echo "<td>" . $row['Created_Date'] . "</td>";
            echo "<td>" . $row['Sub_Department_Name'] . "</td>";
            echo "<td>" . $row['Supplier_Name'] . "</td>";
            echo "<td>" . $row['Order_Description'] . "</td>";
            echo "<td>"
            . "$href"
            . "</td>";

            echo "<td>"
            . "<a href='grnpurchaseorderreport.php?Grn_Purchase_Order_ID= " . $row['Grn_Purchase_Order_ID'] . "&GrnPurchaseOrder=GrnPurchaseOrderThisPage' target='_Blank' class='art-button-green'>Preview GRN </a>
                       "
            . "</td>";

            echo "</tr>";
            $temp++;
        }
        echo "</tr>";
    }
    ?>
</table>