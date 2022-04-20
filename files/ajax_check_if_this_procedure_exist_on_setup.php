<?php
include("./includes/connection.php");
if(isset($_POST['Item_ID'])){
    $Item_ID=$_POST['Item_ID'];
    $sql_check_if_item_exist_result=mysqli_query($conn,"SELECT Item_ID FROM tbl_procedure_setup WHERE Item_ID='$Item_ID'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_check_if_item_exist_result)>0){
        echo "item_ipo";
    }else{
        echo "item_haipo";
    }
}