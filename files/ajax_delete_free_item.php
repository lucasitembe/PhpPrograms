<?php

 include("./includes/connection.php");
 
if(isset($_POST['selected_free_items'])){
   $selected_free_items=$_POST['selected_free_items'];
   
    foreach ($selected_free_items as $free_item_id){
        
        $sql_delete = mysqli_query($conn,"DELETE FROM tbl_free_items WHERE free_item_id='$free_item_id'");
        if($sql_delete){
            echo "Data was deleted successfully.";
        }else{
            echo "There was an error, please try to delete again.";
        }
    }   
}