<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<?php

//error_reporting(0);
include("./includes/connection.php");

include_once("./functions/database.php");
include_once("./functions/stockledger.php");
include_once("./functions/sponsor.php");
include_once("./functions/items.php");

//    if(isset($_GET['Classification'])){
//        $Classification = mysqli_real_escape_string($conn,$_GET['Classification']);
//    }else{
//        $Classification = 0;
//    }
//
//    if(isset($_GET['Supplier_ID'])){
//        $Supplier_ID = mysqli_real_escape_string($conn,$_GET['Supplier_ID']);
//    }else{
//        $Supplier_ID = 0;
//    }
//
//    if(isset($_GET['Start_Date'])){
//        $Start_Date = mysqli_real_escape_string($conn,$_GET['Start_Date']);
//    }else{
//        $Start_Date = null;
//    }
//
//    if(isset($_GET['End_Date'])){
//        $End_Date = mysqli_real_escape_string($conn,$_GET['End_Date']);
//    }else{
//        $End_Date = null;
//    }
//
//    $Purchase_History_List = Get_Item_Purchase_History($Supplier_ID, $Classification, null, $Start_Date, $End_Date, 0);
//
//    $htm = "<table width ='100%' height = '30px'>";
//    $htm .= "<tr> <td> <img src='./branchBanner/branchBanner.png' width=100%> </td> </tr>";
//    $htm .= "<tr><td>&nbsp;</td></tr>";
//    $htm .= "<tr>";
//    $htm .= "<td style='text-align: center;'><h2> PURCHASE REPORT</h2></td>";
//    $htm .= "</tr>";
//    $htm .= "</table><br/>";
//
//    $htm .= "<table>";
//    $htm .= "<tr>";
//    $htm .= "<td width=20%><b>Start Date</b></td>";
//    $htm .= "<td width=20%> {$Start_Date} </td>";
//    $htm .= "<td width=20%><b>End Date</b></td>";
//    $htm .= "<td width=20%> {$End_Date} </td>";
//    $htm .= "</tr>";
//    $htm .= "</table>";
//    $htm .= "<br/>";
//
//    $htm .= "<table id='items' width='100%' >";
//    //$title = "<tr><td colspan='7'><hr></td></tr>";
//    $title .= "<tr>";
//    $title .= "<td width=5%><b>SN</b></td>";
//    $title .= "<td ><b>ITEM NAME</b></td>";
//    $title .= "<td width=8% style='text-align: right;'><b>ITEM CODE</b>&nbsp;&nbsp;&nbsp;</td>";
//    $title .= "<td width=8% style='text-align: right;'><b>UNIT OF MEASURE</b>&nbsp;&nbsp;&nbsp;</td>";
//    $title .= "<td width=8% style='text-align: right;'><b>SUPPLIER NAME</b>&nbsp;&nbsp;&nbsp;</td>";
//    $title .= "<td width=8% style='text-align: right;'><b>BUYING PRICE</b>&nbsp;&nbsp;&nbsp;</td>";
//    $title .= "<td width=8% style='text-align: right;'><b>QUANTITY</b>&nbsp;&nbsp;&nbsp;</td>";
//    $title .= "<td width=8% style='text-align: right;'><b>TOTAL</b>&nbsp;&nbsp;&nbsp;</td>";
//    $title .= "<td width=8% style='text-align: right;'><b>GRN NO</b>&nbsp;&nbsp;&nbsp;</td>";
//    $title .= "<td width=8% style='text-align: right;'><b>GRN DATE</b>&nbsp;&nbsp;&nbsp;</td>";
//    $title .= "</tr>";
//    //$title .= "<tr><td colspan='7'><hr></td></tr>";
//
//    $Grand_Total = 0;
//    $temp = 1;
//    $htm .= $title;
//
//    foreach($Purchase_History_List as $Purchase_History){
//        $Sub_Total = $Purchase_History['Buying_Price'] * $Purchase_History['Quantity'];
//        $Grand_Total += $Sub_Total;
//        
//        $htm .= "<tr>";
//        $htm .= "<td >".$temp."<b>.</b></td>";
//        $htm .= "<td > {$Purchase_History['Product_Name']} </td>";
//        $htm .= "<td style='text-align: right;'> {$Purchase_History['Product_Code']} </td>";
//        $htm .= "<td style='text-align: right;'> {$Purchase_History['Unit_Of_Measure']} </td>";
//        $htm .= "<td style='text-align: right;'> {$Purchase_History['Supplier_Name']} </td>";
//        $htm .= "<td style='text-align: right;'> {$Purchase_History['Buying_Price']} </td>";
//        $htm .= "<td style='text-align: right;'> {$Purchase_History['Quantity']} </td>";
//        $htm .= "<td style='text-align: right;'> {$Sub_Total} </td>";
//        $htm .= "<td style='text-align: right;'> {$Purchase_History['Grn_ID']} </td>";
//        $htm .= "<td style='text-align: right;'> {$Purchase_History['Purchase_Date']} </td>";
//        $htm .= "</tr>";
//
//        if(($temp%25) == 0){ $htm .= $title; }
//        $temp++;
//    }
//
//    $htm .= "<tr style='font-size: 18px'>";
//    $htm .= "<td colspan=7 style='text-align: right;'><b>GRAND TOTAL</b></td>";
//    $htm .= "<td colspan=1 style='text-align: right;'><b> {$Grand_Total} </b></td>";
//    $htm .= "<td colspan=2></td>";
//    $htm .= "</tr>";
//
//    $htm .= "</table>";
//
//    $htm .= "<style>";
//    $htm .= "body { font-size: 14px; }";
//    $htm .= "table#items tr td { font-size: 10px; }";
//    $htm .= "table#items { border-collapse: collapse; border: 1px solid black; }";
//    $htm .= "table#items td { border: 1px solid black; padding:3px 5px; }";
//    $htm .= "</style>";
//    
//    
//Start here boss bana

if (isset($_GET['Classification'])) {
    $Classification = mysqli_real_escape_string($conn,$_GET['Classification']);
} else {
    $Classification = 0;
}

if (isset($_GET['Report_Type'])) {
    $Report_Type = mysqli_real_escape_string($conn,$_GET['Report_Type']);
} else {
    $Report_Type = "SUMMARY";
}

if (isset($_GET['Supplier_ID'])) {
    $Supplier_ID = mysqli_real_escape_string($conn,$_GET['Supplier_ID']);
} else {
    $Supplier_ID = 0;
}

if (isset($_GET['Start_Date'])) {
    $Start_Date = mysqli_real_escape_string($conn,$_GET['Start_Date']);
} else {
    $Start_Date = null;
}

if (isset($_GET['End_Date'])) {
    $End_Date = mysqli_real_escape_string($conn,$_GET['End_Date']);
} else {
    $End_Date = null;
}

$Purchase_History_List = Get_Item_Purchase_History($Supplier_ID, $Classification, null, $Start_Date, $End_Date, 0);
?>

<?php

$htm = "<table width ='100%' height = '30px'>";
$htm .= "<tr> <td> <img src='./branchBanner/branchBanner.png' width=100%> </td> </tr>";
$htm .= "<tr><td>&nbsp;</td></tr>";
$htm .= "<tr>";
$htm .= "<td style='text-align: center;'><h2> PURCHASE REPORT</h2></td>";
$htm .= "</tr>";
$htm .= "</table><br/>";
$htm .= "<table style='width:100%;position:center'>";
$htm .= "<tr>";
$htm .= "<td width=20%><b>Start Date</b></td>";
$htm .= "<td width=20%> {$Start_Date} </td>";
$htm .= "<td width=20%><b>End Date</b></td>";
$htm .= "<td width=20%> {$End_Date} </td>";
$htm .= "</tr>";
$htm .= "</table>";
$htm .= "<br/>";

$htm .= "<table width ='100%' height = '30px'>";
$Grand_Total = 0;
if ($Report_Type == "SUMMARY") {
    $title_lines = "<tr><td colspan=3><hr></td></tr>";
    $Title = $title_lines;
    $Title .= "<tr>";
    $Title .= "<td width=5%><b>SN</b></td>";
    $Title .= "<td width=75% style='text-align: left;'><b>CATEGORY</b></td>";
    $Title .= "<td width=20% style='text-align: right;'><b>AMOUNT</b>&nbsp;&nbsp;&nbsp;</td>";
    $Title .= "</tr>";
    $Title .= $title_lines;
} else {
    $title_lines = "<tr><td colspan=6><hr></td></tr>";
    $Title = $title_lines;
    $Title .= "<tr>";
    $Title .= "<td width=5%><b>SN</b></td>";
    $Title .= "<td width=50% style='text-align: left;'><b>PRODUCT NAME</b></td>";
    $Title .= "<td width=15% style='text-align: left;'><b>PRODUCT CODE</b></td>";
    $Title .= "<td width=15% style='text-align: left;'><b>QUANTITY</b></td>";
    $Title .= "<td width=15% style='text-align: left;'><b>VAT</b></td>";
    $Title .= "<td width=15% style='text-align: right;'><b>AMOUNT</b>&nbsp;&nbsp;&nbsp;</td>";
    $Title .= "</tr>";
    $Title .= $title_lines;
}

$Purchase_By_Classification = array();
$Item_List = array();
$Product_Quantity_List = array();
$Product_Name_List = array();
$Product_Code_List = array();
$Product_Tax = array();
$Product_Classification_List = array();

foreach ($Purchase_History_List as $Purchase_History) {
    $Sub_Total = $Purchase_History['Buying_Price'] * $Purchase_History['Quantity'];
    $Grand_Total += $Sub_Total;

    if (array_key_exists($Purchase_History['Classification'], $Purchase_By_Classification)) {
        $Purchase_By_Classification[$Purchase_History['Classification']] += $Sub_Total;
    } else {
        $Purchase_By_Classification[$Purchase_History['Classification']] = $Sub_Total;
    }

    if (array_key_exists($Purchase_History['Item_ID'], $Item_List)) {
        $Item_List[$Purchase_History['Item_ID']] += $Sub_Total;
        $Product_Quantity_List[$Purchase_History['Item_ID']] += $Purchase_History['Quantity'];
        $Product_Tax[$Purchase_History['Item_ID']] = $Purchase_History['Tax'];
    } else {
        $Item_List[$Purchase_History['Item_ID']] = $Sub_Total;
        $Product_Name_List[$Purchase_History['Item_ID']] = $Purchase_History['Product_Name'];
        $Product_Tax[$Purchase_History['Item_ID']] = $Purchase_History['Tax'];
        $Product_Code_List[$Purchase_History['Item_ID']] = $Purchase_History['Product_Code'];
        $Product_Quantity_List[$Purchase_History['Item_ID']] = $Purchase_History['Quantity'];
        $Product_Classification_List[$Purchase_History['Item_ID']] = $Purchase_History['Classification'];
    }
}

if ($Report_Type == "SUMMARY") {
    $htm .= $Title;
    $temp = 1;
    foreach ($Purchase_By_Classification as $Purchase_Classification => $Purchase_Amount) {
        $htm .= "<tr>";
        $htm .= "<td ><b> {$temp}. </b></td>";
        $htm .= "<td > {$Purchase_Classification} </td>";
        $htm .= "<td style='text-align: right;'>" . number_format($Purchase_Amount, 2) . "</td>";
        $htm .= "</tr>";
        if (($temp % 25) == 0) {
            $htm .= $Title;
        }
        $temp++;
    }
    foreach ($Item_List as $Item_ID => $Total_Amount) {
        if ($Product_Tax[$Item_ID] == "taxable") {
            $vat = 0.18 * $Total_Amount;
            $Total_VAT = $Total_VAT + $vat;
        }
    }
} else {
    foreach ($Purchase_By_Classification as $Purchase_Classification => $Purchase_Amount) {
        $htm .= "<tr style='font-size: 18px'>";
        $htm .= "<td colspan=5 style='text-align: left;'><b>{$Purchase_Classification}</b></td>";
        $htm .= "</tr>";

        $htm .= $Title;
        $temp = 1;
        foreach ($Item_List as $Item_ID => $Total_Amount) {
            if ($Product_Classification_List[$Item_ID] == $Purchase_Classification) {
                if ($Product_Tax[$Item_ID] == "taxable") {
                    $vat = 0.18 * $Total_Amount;
                    $Total_VAT = $Total_VAT + $vat;
                    $vatcol = "<td>" . number_format($vat, 2) . "</td>";
                } else if ($Product_Tax[$Item_ID] == "non_taxable") {
                    $vatcol = "<td>---</td>";
                } else {
                    $vatcol = "<td>Error</td>";
                }
                $htm .= "<tr>";
                $htm .= "<td ><b> {$temp}. </b></td>";
                $htm .= "<td > {$Product_Name_List[$Item_ID]} </td>";
                $htm .= "<td > {$Product_Code_List[$Item_ID]} </td>";
                $htm .= "<td > {$Product_Quantity_List[$Item_ID]} </td>";
                $htm .= $vatcol;
                $htm .= "<td style='text-align: right;'>" . number_format($Total_Amount, 2) . "</td>";
                $htm .= "</tr>";
                if (($temp % 25) == 0) {
                    $htm .= $Title;
                }
                $temp++;
            }
        }
    }
}

$htm .= $title_lines;

if ($Report_Type == "SUMMARY") {
    $htm .= "<tr style='font-size: 18px'>";
    $total = $Grand_Total + $Total_VAT;
    $htm .= "<tr style='font-size: 18px'>";
    $htm .= "<td colspan=2 style='text-align: right;'><b>GRAND TOTAL</b></td>";
    $htm .= "<td colspan=1 style='text-align: right;'><b>" . number_format($Grand_Total, 2) . "</b></td>";
    $htm .= "</tr>";
    $htm .= "<tr style='font-size: 18px'>";
    $htm .= "<td colspan=2 style='text-align: right;'><b>TOTAL VAT</b></td>";
    $htm .= "<td colspan=1 style='text-align: right;'><b>" . number_format($Total_VAT, 2) . "</b></td>";
    $htm .= "</tr>";
    $htm .= "<tr style='font-size: 18px'>";
    $htm .= "<td colspan=2 style='text-align: right;'><b>GRAND TOTAL WITH VAT</b></td>";
    $htm .= "<td colspan=1 style='text-align: right;'><b>" . number_format($total, 2) . "</b></td>";
    $htm .= "</tr>";
} else {
    $total = $Grand_Total + $Total_VAT;
    $htm .= "<tr style='font-size: 18px'>";
    $htm .= "<td colspan=4 style='text-align: right;'><b>GRAND TOTAL</b></td>";
    $htm .= "<td colspan=2 style='text-align: right;'><b>" . number_format($Grand_Total, 2) . "</b></td>";
    $htm .= "</tr>";
    $htm .= "<tr style='font-size: 18px'>";
    $htm .= "<td colspan=4 style='text-align: right;'><b>TOTAL VAT</b></td>";
    $htm .= "<td colspan=2 style='text-align: right;'><b>" . number_format($Total_VAT, 2) . "</b></td>";
    $htm .= "</tr>";
    $htm .= "</tr>";
    $htm .= "<tr style='font-size: 18px'>";
    $htm .= "<td colspan=4 style='text-align: right;'><b>TOTAL WITH VAT</b></td>";
    $htm .= "<td colspan=2 style='text-align: right;'><b>" . number_format($total, 2) . "</b></td>";
    $htm .= "</tr>";
}

$htm .= $title_lines;
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