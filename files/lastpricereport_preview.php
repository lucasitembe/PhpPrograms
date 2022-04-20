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

    if(isset($_GET['Search_Value'])){
        $Search_Value = mysqli_real_escape_string($conn,$_GET['Search_Value']);
    }else{
        $Search_Value = '';
    }

    if(isset($_GET['Sponsor_ID'])){
        $Sponsor_ID = mysqli_real_escape_string($conn,$_GET['Sponsor_ID']);
        $Sponsor = Get_Sponsor($Sponsor_ID);
        $Sponsor_Name = $Sponsor['Guarantor_Name'];
    }else{
        $Sponsor_ID = 0;
        $Sponsor_Name = "Unknown Sponsor";
    }

    $Item_List = Get_Stock_Item_By_Classification($Classification, $Search_Value, 0);

    $htm = "<table width ='100%' height = '30px'>";
    $htm .= "<tr> <td> <img src='./branchBanner/branchBanner.png' width=100%> </td> </tr>";
    $htm .= "<tr><td>&nbsp;</td></tr>";
    $htm .= "<tr>";
    $htm .= "<td style='text-align: center;'><h2> LAST PRICE REPORT</h2></td>";
    $htm .= "</tr>";
    $htm .= "</table><br/>";

    $htm .= "<table>";
    $htm .= "<tr>";
    $htm .= "<td><b>SPONSOR :</b> </td><td> {$Sponsor_Name} </td>";
    $htm .= "<td> </td><td> </td>";
    $htm .= "</tr>";
    $htm .= "</table>";
    $htm .= "<br/>";

    $htm .= "<table id='items' width='100%' >";
    //$title = "<tr><td colspan='7'><hr></td></tr>";
    $title .= "<tr>";
    $title .= "<td width=5%><b>SN</b></td>";
    $title .= "<td ><b>ITEM NAME</b></td>";
    $title .= "<td width=10% style='text-align: right;'><b>ITEM CODE</b>&nbsp;&nbsp;&nbsp;</td>";
    $title .= "<td width=10% style='text-align: right;'><b>UNIT OF MEASURE</b>&nbsp;&nbsp;&nbsp;</td>";
    $title .= "<td width=10% style='text-align: right;'><b>LAST BUYING PRICE</b>&nbsp;&nbsp;&nbsp;</td>";
    $title .= "<td width=10% style='text-align: right;'><b> {$Sponsor_Name} SELLING PRICE</b>&nbsp;&nbsp;&nbsp;</td>";
    $title .= "<td width=10% style='text-align: right;'><b>GRN NO</b>&nbsp;&nbsp;&nbsp;</td>";
    $title .= "<td width=10% style='text-align: right;'><b>GRN DATE</b>&nbsp;&nbsp;&nbsp;</td>";
    $title .= "<td width=10% style='text-align: right;'><b>GRN TYPE</b>&nbsp;&nbsp;&nbsp;</td>";
    $title .= "<td width=10% style='text-align: right;'><b>SUPPLIER NAME</b>&nbsp;&nbsp;&nbsp;</td>";
    $title .= "</tr>";
    //$title .= "<tr><td colspan='7'><hr></td></tr>";

    $Grand_Stock = 0;
    $temp = 1;
    $htm .= $title;

    foreach($Item_List as $Item){
        $Product_Name = $Item['Product_Name'];
        $Product_Code = $Item['Product_Code'];
        $Unit_Of_Measure = $Item['Unit_Of_Measure'];
        $Item_ID = $Item['Item_ID'];

        $List_Of_Last_Buying_Price = Get_Item_Last_Buying_Price_With_Supplier($Item_ID);
        if (!empty($List_Of_Last_Buying_Price)) {
            $Item_Selling_Price = Get_Item_Price($Item_ID, $Sponsor_ID);

            foreach($List_Of_Last_Buying_Price as $Last_Buying_Price) {
                $htm .= "<tr>";
                $htm .= "<td >".$temp."<b>.</b></td>";
                $htm .= "<td >{$Product_Name}</td>";
                $htm .= "<td style='text-align: right;'>{$Product_Code}</td>";
                $htm .= "<td style='text-align: right;'>{$Unit_Of_Measure}</td>";
                $htm .= "<td style='text-align: right;'> {$Last_Buying_Price['Buying_Price']} </td>";
                $htm .= "<td style='text-align: right;'> {$Item_Selling_Price['Items_Price']} </td>";
                $htm .= "<td style='text-align: right;'> {$Last_Buying_Price['Grn_ID']} </td>";
                $htm .= "<td style='text-align: right;'> {$Last_Buying_Price['Grn_Date']} </td>";
                $htm .= "<td style='text-align: right;'> {$Last_Buying_Price['Grn_Type']} </td>";
                $htm .= "<td style='text-align: right;'> {$Last_Buying_Price['Supplier_Name']} </td>";
                $htm .= "</tr>";
                if(($temp%25) == 0){ $htm .= $title; }
                $temp++;
            }
        }
    }

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