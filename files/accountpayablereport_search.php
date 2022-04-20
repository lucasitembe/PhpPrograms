<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include_once("./includes/connection.php");

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
?>

<table width='100%'   border=0 id='Items_Fieldset'>
    <?php
        $Grand_Total = 0;

    $Title = "<tr><td colspan=3><hr></td></tr>";
    $Title .= "<tr>";
    $Title .= "<td width=5%><b>SN</b></td>";
    $Title .= "<td width=75% style='text-align: left;'><b>SUPPLIER NAME</b>&nbsp;&nbsp;&nbsp;</td>";
    $Title .= "<td width=20% style='text-align: right;'><b>TOTAL</b>&nbsp;&nbsp;&nbsp;</td>";
    $Title .= "</tr>";
    $Title .= "<tr><td colspan=3><hr></td></tr>";
    echo $Title;
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
        echo "<tr>";
        echo "<td ><b> {$temp}. </b></td>";
        echo "<td >" . Get_Supplier($Supplier_ID)['Supplier_Name'] . "</td>";
        echo "<td style='text-align: right;'>" . number_format($Payable_Amount, 2) . "</td>";
        echo "</tr>";
        if (($temp % 25) == 0) {
            echo $Title;
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
    echo "<tr><td colspan=3><hr></td></tr>";
    echo "<tr style='font-size: 18px'>";
    echo "<td colspan=2 style='text-align: right;'><b>VAT EXCLUSIVE</b></td>";
    echo "<td colspan=1 style='text-align: right;'><b>" . number_format($Grand_Total, 2) . "</b></td>";
    echo "</tr>";
    echo "<tr><td colspan=3><hr></td></tr>";
    echo "<tr style='font-size: 18px'>";
    echo "<td colspan=2 style='text-align: right;'><b>VAT</b></td>";
    echo "<td colspan=1 style='text-align: right;'><b>" . number_format($Total_VAT, 2) . "</b></td>";
    echo "</tr>";
    echo "<tr><td colspan=3><hr></td></tr>";
    echo "<tr style='font-size: 18px'>";
    echo "<td colspan=2 style='text-align: right;'><b>VAT INCLUSIVE</b></td>";
    echo "<td colspan=1 style='text-align: right;'><b>" . number_format($total, 2) . "</b></td>";
    echo "</tr>";
    echo "<tr><td colspan=3><hr></td></tr>";
    ?>
</table>
<style>
    table, tr, td { border: none !important; }
</style>