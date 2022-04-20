<?php
    include("./includes/connection.php");
    if(isset($_GET['Item_Category_Name'])&&($_GET['Item_Category_Name']!='')){
        $Item_Category_Name = $_GET['Item_Category_Name'];
    }else{
        $Item_Category_Name = 0;
    }
    
    $Select_Items = "select * from tbl_items t, tbl_item_subcategory s, tbl_item_category c
                        where t.Item_Subcategory_ID = s.Item_Subcategory_ID and
                            s.Item_Category_ID = c.Item_Category_ID and
                            c.Item_Category_Name = '$Item_Category_Name'  and t.Visible_Status <> 'Others'";
    $result = mysqli_query($conn,$Select_Items);
    ?>
    <option value=0 selected='selected'></option>
    <?php
    while($row = mysqli_fetch_array($result)){
        ?>
        <option value='<?php echo $row['Item_ID']; ?>'><?php echo $row['Product_Name']; ?></option>
    <?php
    }
?>