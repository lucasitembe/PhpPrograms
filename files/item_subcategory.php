<?php
include("./includes/connection.php");
if(isset($_GET['Item_Category_ID'])){
    $Item_Category_ID=$_GET['Item_Category_ID'];
}else{
    $Item_Category_ID="";
}
echo "<option value=''></option>";
$sql_subcategory_result=mysqli_query($conn,"SELECT *FROM tbl_item_subcategory WHERE Item_category_ID='$Item_Category_ID'") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_subcategory_result)>0){
     while($item_sub_c_row=mysqli_fetch_assoc($sql_subcategory_result)){
        $Item_Subcategory_ID=$item_sub_c_row['Item_Subcategory_ID'];
        $Item_Subcategory_Name=$item_sub_c_row['Item_Subcategory_Name'];
        echo "<option value='$Item_Subcategory_ID'>$Item_Subcategory_Name</option>";
    }
}
