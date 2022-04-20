<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include("./includes/connection.php");

    include_once("./functions/database.php");
    include_once("./functions/stockledger.php");
    include_once("./functions/sponsor.php");
    include_once("./functions/items.php");

    if(isset($_GET['Classification'])){
        $Classification = mysqli_real_escape_string($conn,$_GET['Classification']);
    }else{
        $Classification = 0;
    }

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

    $Purchase_History_List = Get_Disposal_Item_History($Supplier_ID, $Classification, null, $Start_Date, $End_Date, 0);

    $htm = "<table width ='100%' height = '30px'>";
    $htm .= "<tr> <td> <img src='./branchBanner/branchBanner.png' width=100%> </td> </tr>";
    $htm .= "<tr><td>&nbsp;</td></tr>";
    $htm .= "<tr>";
    $htm .= "<td style='text-align: center;'><h2> DISPOSAL REPORT</h2></td>";
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
    //$title = "<tr><td colspan='7'><hr></td></tr>";
    $title .= "<tr>";
    $title .= "<td width=5%><b>SN</b></td>";
    $title .= "<td ><b>PRODUCT NAME</b></td>";
    $title .= "<td width=8% style='text-align: right;'><b>PRODUCT CODE</b>&nbsp;&nbsp;&nbsp;</td>";
    $title .= "<td width=8% style='text-align: right;'><b>QUANTITY</b>&nbsp;&nbsp;&nbsp;</td>";
    $title .= "<td width=8% style='text-align: right;'><b>PRICE</b>&nbsp;&nbsp;&nbsp;</td>";
    $title .= "<td width=8% style='text-align: right;'><b>TOTAL</b>&nbsp;&nbsp;&nbsp;</td>";
    $title .= "</tr>";
    //$title .= "<tr><td colspan='7'><hr></td></tr>";

    $Grand_Total = 0;
    $temp = 1;
    $htm .= $title;

    foreach($Purchase_History_List as $Purchase_History){
         $lastprice=  Get_Item_Last_Buying_Price_With_Supplier($Purchase_History['Item_ID']);
           if(empty($lastprice)){
             $buying=0; 
               
           }  else {
              $buying=$lastprice[0]['Buying_Price']; 
           }
        $Sub_Total = $buying * $Purchase_History['Quantity_Disposed'];
        $Grand_Total += $Sub_Total;
        
        $htm .= "<tr>";
        $htm .= "<td >".$temp."<b>.</b></td>";
        $htm .= "<td > {$Purchase_History['Product_Name']} </td>";
        $htm .= "<td style='text-align: right;'> {$Purchase_History['Product_Code']} </td>";
        $htm .= "<td style='text-align: right;'> {$Purchase_History['Quantity_Disposed']} </td>";
        $htm .= "<td style='text-align: right;'> {$buying} </td>";
        $htm .= "<td style='text-align: right;'> {$Sub_Total} </td>";
//        $htm .= "<td style='text-align: right;'> {$Purchase_History['Grn_ID']} </td>";
//        $htm .= "<td style='text-align: right;'> {$Purchase_History['Purchase_Date']} </td>";
        $htm .= "</tr>";

        if(($temp%25) == 0){ $htm .= $title; }
        $temp++;
    }

    $htm .= "<tr style='font-size: 18px'>";
    $htm .= "<td colspan=5 style='text-align: right;'><b>GRAND TOTAL</b></td>";
    $htm .= "<td colspan=1 style='text-align: right;'><b> {$Grand_Total} </b></td>";
//    $htm .= "<td colspan=2></td>";
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