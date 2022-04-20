<?php

include("./includes/connection.php");
include("./functions/purchaseorder.php");
@session_start();

//get Purchase_Order_ID
if (isset($_GET['Purchase_Order_ID'])) {
    $Purchase_Order_ID = $_GET['Purchase_Order_ID'];
} else {
    $Purchase_Order_ID = 0;
}
$Employee_ID = '';

$htm = "<table width ='100%' height = '30px'>
		        <tr><td> <img src='./branchBanner/branchBanner.png' width=100%> </td></tr>
                <tr><td style='text-align: center;'><h2>PURCHASE ORDER OUTSTANGING</h2></td></tr>
            </table>";

$htm .= "<br/><br/><br/>";

$htm .= "<table width=100%>";

$Purchase_Order = Purchase_Order($Purchase_Order_ID);
if (!is_null($Purchase_Order)) {
    $htm .= "<tr><td width=25%><b>Order Number  </b></td><td width=25%>" . $Purchase_Order['Purchase_Order_ID'] . "</td>";
    $htm .= "<td width=25%><b>Order Date  </b></td><td>" . $Purchase_Order['Created_Date'] . "</td></tr>";
    $htm .= "<tr><td><b>Store Need  </b></td><td style='text-align: left;'>" . $Purchase_Order['Sub_Department_Name'] . "</td>";
    $htm .= "<td width=25%><b>Supplier  </b></td><td>" . $Purchase_Order['Supplier_Name'] . "</td></tr>";
    $htm .= "<tr><td><b>Prepared By  </b></td><td>" . $Purchase_Order['Employee_Name'] . "</td></tr>";
    $Employee_ID = $Purchase_Order['Employee_ID'];
    $Employee_Name = $Purchase_Order['Employee_Name'];
    $Employee_Type = $Purchase_Order['Employee_Type'];
    $Created_Date = $Purchase_Order['Created_Date'];
}

$htm .= "</table><br/><br/><br/><table width='100%'>
            <tr>
                <td width=5%><b>Sn</b></td>
                <td><b>Item Name</b></td>
                <!--td width=7% style='text-align: center;'><b>Containers</b></td-->
                <!--td width=15% style='text-align: center;'><b>Items per Container</b></td-->
                <td width=7% style='text-align: right;'><b>Quantity</b></td>
                <td width=14% style='text-align: right;'><b>Unit Price</b></td>
                <td width=14% style='text-align: right;'><b>Amount</b></td>
                </tr>";
$htm .= "<tr><td colspan=5><hr></td></tr>";

//select data from the table tbl_purchase_order_items ,
$temp = 1;
$Amount = 0;
$Grand_Total = 0;
$select_data = mysqli_query($conn,"select * from tbl_purchase_order_items poi, tbl_items it where
                                poi.item_id = it.item_id and Purchase_Order_ID = '$Purchase_Order_ID' AND  LOWER(poi.Grn_Status) = 'outstanding'") or die(mysqli_error($conn));
while ($row = mysqli_fetch_array($select_data)) {
    $htm .= "<tr><td>" . $temp . ".</td><td>" . $row['Product_Name'] . "</td>";
    $htm .= "<!--td style='text-align: right;'>" . $row['Containers_Required'] . "</td>";
    $htm .= "<td style='text-align: center;'>" . $row['Items_Per_Container_Required'] . "</td-->";
    $htm .= "<td style='text-align: right;'>" . $row['Quantity_Required'] . "</td>";
    $htm .= "<td style='text-align: right;'>" . number_format($row['Price']) . "</td>";
    $Amount = $row['Quantity_Required'] * $row['Price'];
    $Grand_Total = $Grand_Total + $Amount;
    $htm .= "<td style='text-align: right;'>" . number_format($Amount) . "</td></tr>";
    $temp++;
}
$htm .= "<tr><td colspan=5><hr></td></tr>";
$htm .= "<tr><td colspan=4 style='text-align: left;'><b>Grand Total : </td><td style='text-align: right;'><b>" . number_format($Grand_Total) . "</b></td></tr>";
$htm .= "<tr><td colspan=5><hr></td></tr>";
$htm .= "</table>";
$htm .= "<br/><br/><br/>";


$htm .= "</table>";
$htm .= "<style> body { font-size: 12px; } #approvalProgress { font-size: 8px; } </style>";

include("./functions/makepdf.php");
?>