<?php
include("./includes/connection.php");
$filter='';
if (isset($_GET['Item_Category_ID']) && !empty($_GET['Item_Category_ID'])) {
    $filter = " and  c.Item_Category_ID = '".$_GET['Item_Category_ID']."'";
} 


$Select_Items = "select * from tbl_items t, tbl_item_subcategory s, tbl_item_category c
                    where t.Item_Subcategory_ID = s.Item_Subcategory_ID and
                        s.Item_Category_ID = c.Item_Category_ID  $filter  order by t.Product_Name limit 200";

//echo $Select_Items;

$result = mysqli_query($conn,$Select_Items);
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