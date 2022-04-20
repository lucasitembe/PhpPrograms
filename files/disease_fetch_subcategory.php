<?php
include("./includes/connection.php");
if(isset($_GET['disease_category_ID'])){
    $disease_category_ID=$_GET['disease_category_ID'];
}else{
    $disease_category_ID="";
}

echo "<option value=''></option>";

$sql_subcategory_result=mysqli_query($conn,"SELECT * FROM tbl_disease_subcategory WHERE disease_category_ID='$disease_category_ID'") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_subcategory_result)>0){
     while($item_sub_c_row=mysqli_fetch_assoc($sql_subcategory_result)){
        $disease_category_ID=$item_sub_c_row['disease_category_ID'];
        $subcategory_description=$item_sub_c_row['subcategory_description'];
        echo "<option value='$disease_category_ID'>$subcategory_description</option>";
    }
}
