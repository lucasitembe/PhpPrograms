<?php
include("./includes/connection.php");
$filter = '';

// if (isset($_GET['Item_Category_ID']) && !empty($_GET['Item_Category_ID']) && $_GET['Item_Category_ID'] != 'All') {
//     $filter = " and  c.Item_Category_ID = '" . $_GET['Item_Category_ID'] . "'";
// }


if (isset($_GET['Item_Name'])  && !empty($_GET['Item_Name'])) {
    $Item_Name = mysqli_real_escape_string($conn, $_GET['Item_Name']);
    $filter .= " and Product_Name like '%" . $_GET['Item_Name'] . "%' ";
}

// $filter .= " and (t.Item_Type = 'Pharmacy' or t.Item_Type = 'Others') ";

if (isset($_GET['target'])  && $_GET['target'] == 'departmental') {
    $filter .= "";
}

$Select_Items = "SELECT * from tbl_items t, tbl_item_subcategory s, tbl_item_category c
                        where t.Item_Subcategory_ID = s.Item_Subcategory_ID and
                        s.Item_Category_ID = c.Item_Category_ID and 
                        t.Status='Available'  
                        $filter
                        order by t.Product_Name limit 200";


// die("SELECT * from tbl_items t, tbl_item_subcategory s, tbl_item_category c
// where t.Item_Subcategory_ID = s.Item_Subcategory_ID and
// s.Item_Category_ID = c.Item_Category_ID and 
// t.Status='Available'  AND Item_ID IN (SELECT Item_ID FROM tbl_item_price WHERE Items_Price != '-1')
// $filter
// order by t.Product_Name limit 200");
//echo $Select_Items;

$result = mysqli_query($conn, $Select_Items);
?>


<table width='100%' style="background-color:white">
    <?php
    while ($row = mysqli_fetch_array($result)) {
        echo "<tr>
                <td style='color:black; border:2px solid #ccc;text-align: left;'>";
    ?>

        <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="Get_Item_Name(this.value,<?php echo $row['Item_ID']; ?>);">

    <?php
        echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label class='labefor' for='" . $row['Item_ID'] . "'>" . $row['Product_Name'] . "</label></td></tr>";
    }
    ?>
</table>