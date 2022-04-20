<?php

  include("./includes/connection.php");
  $Sponsor_ID=$_POST['Sponsor_ID'];
    $optical_sponsor_setup_ID=$_POST['optical_sponsor_setup_ID'];
    $sql="UPDATE optical_sponsor_setup SET Sponsor_ID='$Sponsor_ID' WHERE optical_sponsor_setup_ID='$optical_sponsor_setup_ID'";
    if(mysqli_query($conn,$sql) or die (mysqli_error($conn))){
        echo 'Sponsor Updated Successfully';
    }else{
        echo 'not updated';
    }
    

?>