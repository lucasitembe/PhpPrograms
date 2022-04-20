<?php
    include_once("./includes/connection.php");
    include_once("./functions/items.php");

    if(isset($_GET['Classification'])){
        $Classification = $_GET['Classification'];
    }else{
        $Classification = 0;
    }

    if(isset($_GET['Current_Store_ID'])){
        $Current_Store_ID = $_GET['Current_Store_ID'];
    }else{
        $Current_Store_ID = 0;
    }

    if(isset($_GET['Item_Name'])){
        $Item_Name = $_GET['Item_Name'];
    }else{
        $Item_Name = "";
    }
?>

<table width='100%' style="background-color:white">
    <?php
    echo "<tr>";
    echo "<td colspan=2><b>Items</b></td>";
    echo "<td><b>OUM</b></td>";
    echo "<td><b>Balance</b></td>";
    echo "<td><b>last buying price</b></td>";
    echo "</tr>";

    if (strtolower($Classification) == "all") {
        $Item_Balance_List = Get_Item_Balance_By_All_Classification($Current_Store_ID, $Item_Name, 500);
    } else {
        $Item_Balance_List = Get_Item_Balance_By_Classification($Classification, $Current_Store_ID, $Item_Name, 0);
    }

    foreach($Item_Balance_List as $Item_Balance) {
        echo "<tr class='labefor' >";
        echo "<td style='color:black; border:2px solid #ccc;text-align: left;'>";
        echo "<input type='radio' name='selection' id='{$Item_Balance['Item_ID']}'";
        echo " value='{$Item_Balance['Item_ID']}'";
        echo " onclick='Add_To_Issue_Note(this.value)'";
        echo "/></td>";
        echo "<td style='color:black; border:2px solid #ccc;text-align: left;'>";
        echo "<label class='labefor' for='{$Item_Balance['Item_ID']}'>{$Item_Balance['Product_Name']}</label>";
        echo "</td>";
        echo "<td><label class='labefor' for='{$Item_Balance['Item_ID']}'>{$Item_Balance['Unit_Of_Measure']}</label></td>";
        echo "<td><label class='labefor' for='{$Item_Balance['Item_ID']}'>{$Item_Balance['Item_Balance']}</label></td>";
        echo "<td><label class='labefor' for='{$Item_Balance['Item_ID']}'>{$Item_Balance['Last_Buying_Price']}</label></td>";
        echo "</tr>";
    }
    ?>
</table>