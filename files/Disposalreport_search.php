<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include_once("./includes/connection.php");

    include_once("./functions/database.php");
    include_once("./functions/stockledger.php");
    include_once("./functions/sponsor.php");
    include_once("./functions/items.php");

    if(isset($_GET['Classification'])){
        $Classification = mysqli_real_escape_string($conn,$_GET['Classification']);
    }else{
        $Classification = 0;
    }

    if(isset($_GET['Report_Type'])){
        $Report_Type = mysqli_real_escape_string($conn,$_GET['Report_Type']);
    } else{
        $Report_Type = "SUMMARY";
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
?>

<table width='100%'   border=0 id='Items_Fieldset'>
    <?php
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
            $title_lines = "<tr><td colspan=5><hr></td></tr>";
            $Title = $title_lines;
            $Title .= "<tr>";
            $Title .= "<td width=5%><b>SN</b></td>";
            $Title .= "<td width=50% style='text-align: left;'><b>PRODUCT NAME</b></td>";
            $Title .= "<td width=15% style='text-align: left;'><b>PRODUCT CODE</b></td>";
            $Title .= "<td width=15% style='text-align: left;'><b>QUANTITY</b></td>";
            $Title .= "<td width=15% style='text-align: right;'><b>AMOUNT</b>&nbsp;&nbsp;&nbsp;</td>";
            $Title .= "</tr>";
            $Title .= $title_lines;
        }

        $Purchase_By_Classification = array();
        $Item_List = array();
        $Product_Quantity_List = array();
        $Product_Name_List = array();
        $Product_Code_List = array();
        $Product_Classification_List = array();
        foreach($Purchase_History_List as $Purchase_History){
            $lastprice=  Get_Item_Last_Buying_Price_With_Supplier($Purchase_History['Item_ID']);
           if(empty($lastprice)){
             $buying=0; 
               
           }  else {
              $buying=$lastprice[0]['Buying_Price']; 
           }
            
            $Sub_Total = $buying * $Purchase_History['Quantity_Disposed'];
            $Grand_Total += $Sub_Total;

            if(array_key_exists($Purchase_History['Classification'], $Purchase_By_Classification)){
                $Purchase_By_Classification[$Purchase_History['Classification']] += $Sub_Total;
            }else{
                $Purchase_By_Classification[$Purchase_History['Classification']] = $Sub_Total;
            }

            if(array_key_exists($Purchase_History['Item_ID'], $Item_List)){
                $Item_List[$Purchase_History['Item_ID']] += $Sub_Total;
                $Product_Quantity_List[$Purchase_History['Item_ID']] += $Purchase_History['Quantity_Disposed'];
            }else{
                $Item_List[$Purchase_History['Item_ID']] = $Sub_Total;
                $Product_Name_List[$Purchase_History['Item_ID']] = $Purchase_History['Product_Name'];
                $Product_Code_List[$Purchase_History['Item_ID']] = $Purchase_History['Product_Code'];
                $Product_Quantity_List[$Purchase_History['Item_ID']] = $Purchase_History['Quantity_Disposed'];
                $Product_Classification_List[$Purchase_History['Item_ID']] = $Purchase_History['Classification'];
            }
        }

        if ($Report_Type == "SUMMARY") {
            echo $Title;
            $temp = 1;
            foreach($Purchase_By_Classification as $Purchase_Classification => $Purchase_Amount){
                echo "<tr>";
                echo "<td ><b> {$temp}. </b></td>";
                echo "<td > {$Purchase_Classification} </td>";
                echo "<td style='text-align: right;'>" . number_format($Purchase_Amount,2) . "</td>";
                echo "</tr>";
                if(($temp%25) == 0){ echo $Title; }
                $temp++;
            }
        } else {
            foreach($Purchase_By_Classification as $Purchase_Classification => $Purchase_Amount){
                echo "<tr style='font-size: 18px'>";
                echo "<td colspan=5 style='text-align: left;'><b>{$Purchase_Classification}</b></td>";
                echo "</tr>";

                echo $Title;
                $temp = 1;
                foreach($Item_List as $Item_ID => $Total_Amount){
                    if ($Product_Classification_List[$Item_ID] == $Purchase_Classification) {
                        echo "<tr>";
                        echo "<td ><b> {$temp}. </b></td>";
                        echo "<td > {$Product_Name_List[$Item_ID]} </td>";
                        echo "<td > {$Product_Code_List[$Item_ID]} </td>";
                        echo "<td > {$Product_Quantity_List[$Item_ID]} </td>";
                        echo "<td style='text-align: right;'>" . number_format($Total_Amount,2) . "</td>";
                        echo "</tr>";
                        if(($temp%25) == 0){ echo $Title; }
                        $temp++;
                    }
                }
            }
        }

        /*$temp = 1;
        foreach($Purchase_History_List as $Purchase_History){
            if (strtolower($Purchase_History['Type']) == strtolower("GRN Without PO")) {
                $Grn_Url = "previewgrnwithoutpurchaseorderreport.php?Grn_ID={$Purchase_History['Grn_ID']}";
            } else {
                $Grn_Url = "grnpurchaseorderreport.php?Grn_Purchase_Order_ID={$Purchase_History['Grn_ID']}";
            }
            $Sub_Total = $Purchase_History['Buying_Price'] * $Purchase_History['Quantity'];
            $Grand_Total += $Sub_Total;

            echo "<tr>";
            echo "<td >".$temp."<b>.</b></td>";
            echo "<td > {$Purchase_History['Product_Name']} </td>";
            echo "<td style='text-align: right;'> {$Purchase_History['Product_Code']} </td>";
            echo "<td style='text-align: right;'> {$Purchase_History['Unit_Of_Measure']} </td>";
            echo "<td style='text-align: right;'> {$Purchase_History['Supplier_Name']} </td>";
            echo "<td style='text-align: right;'> {$Purchase_History['Buying_Price']} </td>";
            echo "<td style='text-align: right;'> {$Purchase_History['Quantity']} </td>";
            echo "<td style='text-align: right;'> {$Sub_Total} </td>";
            echo "<td style='text-align: right;'> <a href='{$Grn_Url}' target='_blank'> {$Purchase_History['Grn_ID']} </a></td>";
            echo "<td style='text-align: right;'> <a href='{$Grn_Url}' target='_blank'> {$Purchase_History['Purchase_Date']} </a></td>";
            //echo "<td style='text-align: right;'> <a href='{$Grn_Url}' target='_blank'> {$Purchase_History['Type']} </a></td>";
            echo "</tr>";
            if(($temp%25) == 0){ echo $Title; }
            $temp++;
        }*/

        echo $title_lines;
        echo "<tr style='font-size: 18px'>";
        if ($Report_Type == "SUMMARY") {
            echo "<td colspan=2 style='text-align: right;'><b>GRAND TOTAL</b></td>";
            echo "<td colspan=1 style='text-align: right;'><b>" . number_format($Grand_Total,2) . "</b></td>";
        } else {
            echo "<td colspan=4 style='text-align: right;'><b>GRAND TOTAL</b></td>";
            echo "<td colspan=1 style='text-align: right;'><b>" . number_format($Grand_Total,2) . "</b></td>";
        }
        echo "</tr>";
        echo $title_lines;

    ?>
</table>
<style>
    table, tr, td { border: none !important; }
</style>