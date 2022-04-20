<?php
    include("./includes/connection.php");
    if (isset($_GET['Item_Category_ID'])) {
        $Item_Category_ID = $_GET['Item_Category_ID'];
    } else {
        $Item_Category_ID = 0;
    }

    if(isset($_GET['Item_Name'])){
        $Item_Name = $_GET['Item_Name'];
    }else{
        $Item_Name = '';
    }
    //get all selected items
    $Values = '';
    $select = mysqli_query($conn,"select Item_ID from tbl_initial_items") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($row = mysqli_fetch_array($select)) {
            $Values .= ','.$row['Item_ID'];
        }
    }else{
        $Values = '0';
    }

    $Filter_Value = substr($Values, 1);

    if ($Item_Category_ID == 'All') {
        $Select_Items = "select t.Item_ID, t.Product_Name from tbl_items t, tbl_item_subcategory s, tbl_item_category c
                            where t.Item_Subcategory_ID = s.Item_Subcategory_ID and
                            s.Item_Category_ID = c.Item_Category_ID and 
                            t.Visible_Status <> 'Others' and
                            Product_Name like '%$Item_Name%' order by t.Product_Name limit 100";
    } else {
        $Select_Items = "select t.Item_ID, t.Product_Name from tbl_items t, tbl_item_subcategory s, tbl_item_category c
                            where t.Item_Subcategory_ID = s.Item_Subcategory_ID and
                            s.Item_Category_ID = c.Item_Category_ID and
                            c.Item_Category_ID = '$Item_Category_ID' and
                            t.Visible_Status <> 'Others' and
                            Product_Name like '%$Item_Name%' order by t.Product_Name limit 100";
    }

    $result = mysqli_query($conn,$Select_Items);
?>


<table width=100%>
    <?php
    while ($row = mysqli_fetch_array($result)) {
        echo "<tr>
                    <td style='color:black; border:2px solid #ccc;text-align: left;'>";
        ?>

        <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="Add_Selected_Item('<?php echo $row['Item_ID']; ?>')">

        <?php
        echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='" . $row['Item_ID'] . "'>" . $row['Product_Name'] . "</label></td></tr>";
    }
    ?> 
</table>