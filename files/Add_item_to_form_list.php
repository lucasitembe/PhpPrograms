<?php
include("./includes/connection.php");

if(isset($_POST['selected_item'])){
    $selected_items=$_POST['selected_item'];
    $cancer_id=$_POST['cancer_id'];
    foreach ($selected_items as $Item_cancer){
        $sql_check_exist = mysqli_query($conn,"SELECT item_ID FROM tbl_items_cancer WHERE item_ID='$Item_cancer' AND Status='pending'");
        if(mysqli_num_rows($sql_check_exist)<=0){
            $sql_cancer_result=mysqli_query($conn,"INSERT INTO tbl_items_cancer(item_ID,cancer_id,Status)VALUES('$Item_cancer','$cancer_id','pending')") or dei(mysqli_error($conn));
        }
    }   
}else{
    echo "Fail";
}

// $insert_item = mysqli_query($conn,"INSERT INTO tbl_supportive_treatment(supportive_treatment_ID,status)VALUES('$Item_ID','pending')");
// 
// if($insert_item){
//     echo "successful";
//     
// }else{
//     echo "fail";
// }


 

