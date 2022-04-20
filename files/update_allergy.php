<?php

  include("./includes/connection.php");
  $allergy_id=$_POST['allergy_id'];
    $allergies_Name=$_POST['allergies_Name'];
    $sql="UPDATE allergies SET allergies_Name='$allergies_Name' WHERE allergies_ID='$allergy_id'";
    if(mysqli_query($conn,$sql) or die (mysqli_error($conn))){
        echo 'updated';
    }else{
        echo 'not updated';
    }
    

?>