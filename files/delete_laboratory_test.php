<?php

 include("./includes/connection.php");
 
if(isset($_POST['selected_laboratory_test'])){
   $selected_laboratory_test=$_POST['selected_laboratory_test'];
   $Age_ID=$_POST['Age_ID'];
    foreach ($selected_laboratory_test as $test_id){
        echo $test_id;
        $sql_delete = mysqli_query($conn,"DELETE FROM tbl_attach_age_laboratory_test WHERE attach_id='$test_id' AND age_range_id='$Age_ID'");
       $value = mysqli_num_rows($sql_delete);
        echo  $value;
        echo "jkdoieij";
       
    }   
}