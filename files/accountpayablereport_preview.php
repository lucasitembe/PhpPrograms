<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include("./includes/connection.php");

    include_once("./functions/database.php");
    include_once("./functions/stockledger.php");
    include_once("./functions/supplier.php");
    include_once("./functions/items.php");

    if(isset($_GET['Supplier_ID'])){
        $Supplier_ID = mysqli_real_escape_string($conn,$_GET['Supplier_ID']);
    }else{
        $Supplier_ID = 0;
    }

    if(isset($_GET['Start_Date'])){
        $Start_Date = mysqli_real_escape_string($conn,$_GET['Start_Date']);
    }else{
        $Start_Date = null;
    }

    if(isset($_GET['End_Date'])){
        $End_Date = mysqli_real_escape_string($conn,$_GET['End_Date']);
    }else{
        $End_Date = null;
    }

    $Purchase_History_List = Get_Item_Purchase_History($Supplier_ID, "all", null, $Start_Date, $End_Date, 0);

    $htm = "<table width ='100%' height = '30px'>";
    $htm .= "<tr> <td> <img src='./branchBanner/branchBanner.png' width=100%> </td> </tr>";
    $htm .= "<tr><td>&nbsp;</td></tr>";
    $htm .= "<tr>";
    $htm .= "<td style='text-align: center;'><h2> ACCOUNT PAYABLE REPORT</h2></td>";
    $htm .= "</tr>";
    $htm .= "</table><br/>";

    $htm .= "<table>";
    $htm .= "<tr>";
    $htm .= "<td width=20%><b>Start Date</b></td>";
    $htm .= "<td width=20%> {$Start_Date} </td>";
    $htm .= "<td width=20%><b>End Date</b></td>";
    $htm .= "<td width=20%> {$End_Date} </td>";
    $htm .= "</tr>";
    $htm .= "</table>";
    $htm .= "<br/>";

    $htm .= "<table id='items' width='100%' >";
    $title .= "<tr>";
    $title .= "<td width=5%><b>SN</b></td>";
    $title .= "<td width=75% style='text-align: left;'><b>SUPPLIER NAME</b>&nbsp;&nbsp;&nbsp;</td>";
    $title .= "<td width=20% style='text-align: right;'><b>TOTAL</b>&nbsp;&nbsp;&nbsp;</td>";
    $title .= "</tr>";

$Grand_Total = 0;
$temp = 1;
$htm .= $title;
$Product_Tax = array();
$Account_Payable = array();
foreach ($Purchase_History_List as $Purchase_History) {
    $Sub_Total = $Purchase_History['Buying_Price'] * $Purchase_History['Quantity'];
    $Grand_Total += $Sub_Total;
    if (array_key_exists($Purchase_History['Supplier_ID'], $Account_Payable)) {
        $Account_Payable[$Purchase_History['Supplier_ID']] += $Sub_Total;
        $Product_Tax[$Purchase_History['Supplier_ID']] = $Purchase_History['Tax'];
    } else {
        $Account_Payable[$Purchase_History['Supplier_ID']] = $Sub_Total;
        $Product_Tax[$Purchase_History['Supplier_ID']] = $Purchase_History['Tax'];
    }
}

$temp = 1;
foreach ($Account_Payable as $Supplier_ID => $Payable_Amount) {
    $htm .= "<tr>";
    $htm .= "<td ><b> {$temp}. </b></td>";
    $htm .= "<td >" . Get_Supplier($Supplier_ID)['Supplier_Name'] . "</td>";
    $htm .= "<td style='text-align: right;'>" . number_format($Payable_Amount, 2) . "</td>";
    $htm .= "</tr>";
    if (($temp % 25) == 0) {
        $htm .= $title;
    }
    $temp++;
    foreach ($Account_Payable as $Supplier_ID => $Total_Amount) {
        if ($Product_Tax[$Supplier_ID] == "taxable") {
            $vat = 0.18 * $Total_Amount;
            $Total_VAT = $Total_VAT + $vat;
        }
    }
}
$total = $Grand_Total + $Total_VAT;
$htm .= "<tr style='font-size: 18px'>";
$htm .= "<td colspan=2 style='text-align: right;'><b>VAT EXCLUSIVE</b></td>";
$htm .= "<td colspan=1 style='text-align: right;'><b>" . number_format($Grand_Total, 2) . "</b></td>";
$htm .= "</tr>";
$htm .= "<tr style='font-size: 18px'>";
$htm .= "<td colspan=2 style='text-align: right;'><b>VAT</b></td>";
$htm .= "<td colspan=1 style='text-align: right;'><b>" . number_format($Total_VAT, 2) . "</b></td>";
$htm .= "</tr>";
$htm .= "<tr style='font-size: 18px'>";
$htm .= "<td colspan=2 style='text-align: right;'><b>VAT INCLUSIVE</b></td>";
$htm .= "<td colspan=1 style='text-align: right;'><b>" . number_format($total, 2) . "</b></td>";
$htm .= "</tr>";
$htm .= "</table>";

    $htm .= "<style>";
    $htm .= "body { font-size: 14px; }";
    $htm .= "table#items tr td { font-size: 10px; }";
    $htm .= "table#items { border-collapse: collapse; border: 1px solid black; }";
    $htm .= "table#items td { border: 1px solid black; padding:3px 5px; }";
    $htm .= "</style>";

    ini_set('memory_limit', '256M');
    include("./functions/makepdf.php");
?>