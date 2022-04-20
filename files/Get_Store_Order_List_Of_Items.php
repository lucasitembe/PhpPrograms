<?php
include("./includes/connection.php");
include_once("./functions/items.php");

error_reporting(E_ALL && !E_NOTICE);

if (isset($_GET['Item_Category_ID'])) {
    $Item_Category_ID = $_GET['Item_Category_ID'];
} else {
    $Item_Category_ID = 0;
}

if (isset($_GET['Sub_Department_ID'])) {
    $Sub_Department_ID = $_GET['Sub_Department_ID'];
} else {
    $Sub_Department_ID = 0;
}

$filter = " AND Classification in ('Pharmaceuticals', 'Dental Materials',
                                                                 'Disposables', 'Laboratory Materials',
                                                                 'Radiology Materials', 'Stationaries')";
//   Classification = '{$Classification}
if (strtolower($Item_Category_ID) != "all") {
    $filter = " AND Classification = '$Item_Category_ID'";
}


$result = mysqli_query($conn,"SELECT Product_Name, t.Item_ID, t.Unit_Of_Measure, ib.Item_Balance
                                                FROM tbl_items t, tbl_items_balance ib
                                                WHERE
                                                           t.Item_ID = ib.Item_ID AND
                                                           ib.Sub_Department_ID = '$Sub_Department_ID'
                                                               $filter
                                                        ORDER BY Product_Name
                                                        LIMIT 500") or die(mysqli_error($conn));

echo "<table width=100%>";
echo "<tr>";
echo "<td colspan=2><b>Items</b></td>";
echo "<td><b>OUM</b></td>";
echo "<td><b>Balance</b></td>";
echo "<td><b>last buying price</b></td>";
echo "</tr>";
while ($row = mysqli_fetch_array($result)) {

   $last_buying_prices = Get_Item_Last_Buying_Price_With_Supplier($row['Item_ID']);
    if (count($last_buying_prices)> 0) {
        $last_buying_price =$last_buying_prices["Buying_Price"];
    } else {
        $last_buying_price = 0;
    }

    echo "<tr>";
    echo "<td style='color:black; border:2px solid #ccc;text-align: left;'>";
    ?>

    <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>'
           value='<?php echo $row['Product_Name']; ?>'
           onclick="Get_Item_Name(this.value,<?php echo $row['Item_ID']; ?>);">

    <?php
    echo "</td>";
    echo "<td style='color:black; border:2px solid #ccc;text-align: left;'>";
    echo "<label for='" . $row['Item_ID'] . "'>" . $row['Product_Name'] . "</label>";
    echo "</td>";
    echo "<td><label for='" . $row['Item_ID'] . "'>" . $row['Unit_Of_Measure'] . "</label></td>";
    echo "<td><label for='" . $row['Item_ID'] . "'>" . $row['Item_Balance'] . "</label></td>";
    echo "<td><label for='" . $row['Item_ID'] . "'>" . $last_buying_price . "</label></td>";
    echo "</tr>";
}

echo "</table>";


//    if ($Sub_Department_ID > 0) {
//        if (strtolower($Item_Category_ID) == "all") {
//            $Item_Balance_List = Get_Item_Balance_By_All_Classification($Sub_Department_ID, "", 500);
//        } else {
//            $Item_Balance_List = Get_Item_Balance_By_Classification($Item_Category_ID, $Sub_Department_ID, "", 0);
//        }
//
//        echo "<table width=100%>";
//        echo "<tr>";
//        echo "<td colspan=2><b>Items</b></td>";
//        echo "<td><b>OUM</b></td>";
//        echo "<td><b>Balance</b></td>";
//        echo "<td><b>last buying price</b></td>";
//        echo "</tr>";
//        echo '<pre>';
//        print_r($Item_Balance_List) ;exit;
//        foreach($Item_Balance_List as $Item_Balance) {
////             $lastBuyingPrices=Get_Item_Last_Buying_Price_With_Supplier($Item_Balance['Item_ID']);
////
////                            if (!empty($lastBuyingPrices)) {
////                                $last_buying_price = $lastBuyingPrices[0]["Buying_Price"];
////                            } else {
////                                $last_buying_price = 0;
////                            }
//
//            echo "<tr>";
//            echo "<td style='color:black; border:2px solid #ccc;text-align: left;'>";
//            echo "<input type='radio' name='selection' id='{$Item_Balance['Item_ID']}'
//                    value='{$Item_Balance['Product_Name']}' onclick='Get_Item_Name(this.value,{$Item_Balance['Item_ID']});'>";
//            echo "</td>";
//            echo "<td style='color:black; border:2px solid #ccc;text-align: left;'>";
//            echo "<label for='".$Item_Balance['Item_ID']."'>".$Item_Balance['Product_Name']."</label>";
//            echo "</td>";
//            echo "<td>".$Item_Balance['Unit_Of_Measure']."</td>";
//            echo "<td>".$Item_Balance['Item_Balance']."</td>";
//            echo "<td>".$last_buying_price."</td>";
//            echo "</tr>";
//        }
//        echo "</table>";
//    }
?>
