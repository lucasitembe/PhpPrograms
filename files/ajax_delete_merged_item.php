<?php

 include("./includes/connection.php");
 
if(isset($_POST['merged_subdepartment'])){
   $merged_subdepartment=$_POST['merged_subdepartment'];
   
    foreach ($merged_subdepartment as $item_id){
        $sql_delete = mysqli_query($conn,"DELETE FROM tbl_sub_department_ward WHERE sub_department_ward_id='$item_id'");
        if($sql_delete){
            echo "Data was deleted successfully.";
        }else{
            echo "There was an error, please try to delete again.";
        }
    }   
}

if(isset($_POST['merged_department'])){
    $merged_department=$_POST['merged_department'];
    
     foreach ($merged_department as $item_id){
         $sql_delete = mysqli_query($conn,"DELETE FROM tbl_department_ward WHERE department_ward_id='$item_id'");
         if($sql_delete){
             echo "Data was deleted successfully.";
         }else{
             echo "There was an error, please try to delete again.";
         }
     }   
 }