<?php
    include("./includes/connection.php");
    $filter="";
    if(isset($_GET['Item_Category_ID'])){
        $Item_Category_ID=$_GET['Item_Category_ID'];
            if($Item_Category_ID!="All"){
                $filter="WHERE Item_Category_ID='$Item_Category_ID'";
            }
    }
echo "<option value='All'>All</option>";
$sql_select_item_sub_category_result=mysqli_query($conn,"SELECT Item_Subcategory_Name,Item_Subcategory_ID FROM tbl_item_subcategory $filter") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_item_sub_category_result)>0){
   while($item_sub_rows=mysqli_fetch_assoc($sql_select_item_sub_category_result)){
       $Item_Subcategory_Name=$item_sub_rows['Item_Subcategory_Name'];
       $Item_Subcategory_ID=$item_sub_rows['Item_Subcategory_ID'];
       echo "<option value='$Item_Subcategory_ID'>$Item_Subcategory_Name</option>";
   } 
}

