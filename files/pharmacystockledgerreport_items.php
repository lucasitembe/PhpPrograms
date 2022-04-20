<?php
include("./includes/connection.php");

include_once("./functions/department.php");
include_once("./functions/items.php");

if(isset($_GET['Classification'])){
    $Classification = $_GET['Classification'];
}else{
    $Classification = '';
}

if(isset($_GET['Sub_Department_ID'])){
    $Sub_Department_ID = $_GET['Sub_Department_ID'];
}else{
    $Sub_Department_ID = 0;
}

if(isset($_GET['Search_Value'])){
    $Search_Value = $_GET['Search_Value'];
}else{
    $Search_Value = '';
}
?>


<table width=100%>
    <?php
    $Grand_Stock = 0;

    $Title = '<tr><td width="4%"><b>SN</b><td><b>ITEM NAME</b></td>';
    $Title .= ' <td width="8%" style="text-align: right;"><b>BALANCE</b>&nbsp;&nbsp;&nbsp;</td>';

    echo '<tr><td colspan=3><hr></td></tr>';
    echo $Title;
    echo '<tr><td colspan=3><hr></td></tr>';

    $Classification_Statement = "";
    if (strtolower($Classification) != "all") {
        $Classification_Statement = "AND i.Classification = '{$Classification}'";
    }

    $Search_Value = Prepare_For_Like_Operator($Search_Value);

    $sql = "SELECT i.item_folio_number,i.Item_ID, i.Product_Name, ib.Item_Balance,i.Status
                FROM tbl_items i, tbl_items_balance ib
                WHERE Can_Be_Stocked = 'yes'
                AND i.Item_ID = ib.Item_ID
                AND i.Status != 'Not Available'
                AND ib.Sub_Department_ID = '{$Sub_Department_ID}'
                {$Classification_Statement}
                AND Product_Name LIKE '{$Search_Value}'
                ORDER BY Product_Name";

    $Item_List_Result = Query_DB($sql);

    $hasError = $Item_List_Result["error"];
    if (!$hasError) {
        $Item_List = $Item_List_Result["data"];
        if(!empty($Item_List)) {
            $temp = 0;
            foreach($Item_List as $Item) {$temp++;
                $Item_ID = $Item['Item_ID'];
                $Total_Items = 0;
                $item_folio_number=$Item['item_folio_number'];
                $product_name = $Item['Product_Name'];
				
				                $newItemName = str_replace('"','&#39;',$product_name);

                echo "<tr>";
                echo "<td><input type='radio' id='{$Item['Item_ID']}' name='Item'";
                echo " onclick='Get_Selected_Details(\"".$newItemName."\",{$Item['Item_ID']},\"$item_folio_number\")' /></td>";
                echo "<td><label for='{$Item['Item_ID']}'>".$newItemName."~~~( $item_folio_number )</label></td>";
                echo "<td style='text-align: right;'><label for='{$Item['Item_ID']}'>".$Item['Item_Balance']."&nbsp;&nbsp;&nbsp;</label></td>";

                if(($temp%20) == 0 && $temp != 200){
                    echo '<tr><td colspan=3><hr></td></tr>';
                    echo $Title;
                    echo '<tr><td colspan=3><hr></td></tr>';
                }

            }
        }
    } else {
        echo $Item_List_Result["errorMsg"];
    }
    ?>
</table>