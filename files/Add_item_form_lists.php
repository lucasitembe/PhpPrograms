<?php
include("./includes/connection.php");

if(isset($_POST['selected_item_cancer'])){
    $selected_items=$_POST['selected_item_cancer'];
    $cancer_id=$_POST['cancer_id'];
    foreach ($selected_items as $Item_cancer){
        $sql_check_exist = mysqli_query($conn,"SELECT item_id FROM tbl_items_cancer_drug WHERE item_id='$Item_cancer' AND Status='pending'");
        if(mysqli_num_rows($sql_check_exist)<=2){
            $sql_cancer_result=mysqli_query($conn,"INSERT INTO tbl_items_cancer_drug(item_id,cancer_id,Status)VALUES('$Item_cancer','$cancer_id','pending')") or die(mysqli_error($conn));
        }
    }   
}else{
    echo "Fail";
}