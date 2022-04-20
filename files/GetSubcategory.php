<?php
    include("./includes/connection.php");
    if(isset($_GET['Item_Category_ID'])){
        $Item_Category_ID = $_GET['Item_Category_ID'];
    }else{
        $Item_Category_ID = 0;
    }
    
    $Select_SubCategory = "select * from tbl_item_category ic, tbl_item_subcategory isc
                            where ic.Item_category_ID = isc.Item_category_ID and isc.Item_Category_ID = '$Item_Category_ID'";
    $result = mysqli_query($conn,$Select_SubCategory);
    ?> 
    <?php
        //echo "<option selected='selected'></option>";
    while($row = mysqli_fetch_assoc($result)){
        ?>
      <option value='<?php echo $row['Item_Subcategory_ID']; ?>'><?php echo $row['Item_Subcategory_Name']; ?></option>
    <?php
    }
?>