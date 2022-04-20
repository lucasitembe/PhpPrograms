<?php
    include("./includes/connection.php");
    if(isset($_GET['Item_Category_ID'])&&($_GET['Item_Category_ID']!='')){
        $Item_Category_ID = $_GET['Item_Category_ID'];
    }else{
        $Item_Category_ID = 0;
    }
    
    $Select_Items = "SELECT * FROM tbl_item_subcategory";
    if($Item_Category_ID!='All'){
        $Select_Items.= " WHERE Item_category_ID=$Item_Category_ID";
    }
    $result = mysqli_query($conn,$Select_Items);
    ?>
    <option value=0 selected='selected'>All</option>
    <?php
    while($row = mysqli_fetch_array($result)){
        ?>
        <option value='<?php echo $row['Item_Subcategory_ID']; ?>'><?php echo $row['Item_Subcategory_Name']; ?></option>
    <?php
    }
?>