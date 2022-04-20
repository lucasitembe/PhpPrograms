<?php
 include("./includes/connection.php");
            
 if(isset($_POST['remove_item'])){
    $item_cancer_ID =$_POST['item_cancer_ID'];
    $cancer_id  = $_POST['cancer_id'];

    $delete_drug = mysqli_query($conn, "DELETE FROM tbl_items_cancer WHERE cancer_id='$cancer_id' AND item_cancer_ID='$item_cancer_ID'" ) or die(mysqli_error($conn));
    if(!$delete_drug){
       echo "Not deleted";
    }else{
       echo "Deleted";
    }
 }

 if(isset($_POST['remove_items'])){
    $cancer_drug_ID =$_POST['cancer_drug_ID'];
    $cancer_id  = $_POST['cancer_id'];

    $delete_drug = mysqli_query($conn, "DELETE FROM tbl_items_cancer_drug WHERE cancer_id='$cancer_id' AND cancer_drug_ID='$cancer_drug_ID'" ) or die(mysqli_error($conn));
    if(!$delete_drug){
       echo "Not deleted";
    }else{
       echo "Deleted";
    }
 }
?>