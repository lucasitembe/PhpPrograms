<?php
    include("./includes/connection.php");
    if(isset($_GET['Item_Subcategory_ID'])&&($_GET['Item_Subcategory_ID']!='')){
        $Item_Subcategory_ID = $_GET['Item_Subcategory_ID'];
    }else{
        $Item_Subcategory_ID = 0;
    }
    
    $Select_Items = "select * from tbl_items WHERE Item_Subcategory_ID = $Item_Subcategory_ID";
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