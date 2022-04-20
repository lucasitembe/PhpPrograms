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

    $Item_List = Get_Stock_Item_By_Classification($Classification, $Search_Value, 100);
?>

<table width='100%'   border=0 id='Items_Fieldset'>
    <?php
        $Grand_Stock = 0;

        $Title = "<tr><td colspan=10><hr></td></tr>";
        $Title .= "<tr>";
        $Title .= "<td width=5%><b>SN</b></td>";
        $Title .= "<td ><b>ITEM NAME</b></td>";
        $Title .= "<td width=10% style='text-align: right;'><b>ITEM CODE</b>&nbsp;&nbsp;&nbsp;</td>";
        $Title .= "<td width=10% style='text-align: right;'><b>UNIT OF MEASURE</b>&nbsp;&nbsp;&nbsp;</td>";
        $Title .= "<td width=10% style='text-align: right;'><b>LAST BUYING PRICE</b>&nbsp;&nbsp;&nbsp;</td>";
        $Title .= "<td width=10% style='text-align: right;'><b> {$Sponsor_Name} SELLING PRICE</b>&nbsp;&nbsp;&nbsp;</td>";
        $Title .= "<td width=10% style='text-align: right;'><b>GRN NO</b>&nbsp;&nbsp;&nbsp;</td>";
        $Title .= "<td width=10% style='text-align: right;'><b>GRN DATE</b>&nbsp;&nbsp;&nbsp;</td>";
        $Title .= "<td width=10% style='text-align: right;'><b>GRN TYPE</b>&nbsp;&nbsp;&nbsp;</td>";
        $Title .= "<td width=10% style='text-align: right;'><b>SUPPLIER NAME</b>&nbsp;&nbsp;&nbsp;</td>";
        $Title .= "</tr>";
        $Title .= "<tr><td colspan=10><hr></td></tr>";
        echo $Title;

        $temp = 1;
        foreach($Item_List as $Item){

            $Product_Name = $Item['Product_Name'];
            $Product_Code = $Item['Product_Code'];
            $Unit_Of_Measure = $Item['Unit_Of_Measure'];
            $Item_ID = $Item['Item_ID'];

            $List_Of_Last_Buying_Price = Get_Item_Last_Buying_Price_With_Supplier($Item_ID);
            if (!empty($List_Of_Last_Buying_Price)) {
                $Item_Selling_Price = Get_Item_Price($Item_ID, $Sponsor_ID);

                foreach($List_Of_Last_Buying_Price as $Last_Buying_Price) {
                    $Alert_Price = ($Item_Selling_Price['Items_Price'] >= $Last_Buying_Price['Buying_Price']) ? "alert_price" : "";

                    echo "<tr>";
                    echo "<td >".$temp."<b>.</b></td>";
                    echo "<td >{$Product_Name}</td>";
                    echo "<td style='text-align: right;'>{$Product_Code}</td>";
                    echo "<td style='text-align: right;'>{$Unit_Of_Measure}</td>";
                    echo "<td style='text-align: right;'> {$Last_Buying_Price['Buying_Price']} </td>";
                    echo "<td style='text-align: right;'> {$Item_Selling_Price['Items_Price']} </td>";
                    echo "<td style='text-align: right;'> {$Last_Buying_Price['Grn_ID']} </td>";
                    echo "<td style='text-align: right;'> {$Last_Buying_Price['Grn_Date']} </td>";
                    echo "<td style='text-align: right;'> {$Last_Buying_Price['Grn_Type']} </td>";
                    echo "<td style='text-align: right;'> {$Last_Buying_Price['Supplier_Name']} </td>";
                    echo "</tr>";
                    if(($temp%25) == 0){ echo $Title; }
                    $temp++;
                }
            }
        }
    ?>
</table>