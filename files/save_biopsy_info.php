<?php
include("./includes/connection.php");
$Biopsy_ID = $_GET['Biopsy_ID'];
$Priority = $_GET['Priority'];


if($Priority == 'Routine'){
    $Priority = 'Normal';
}

    if(!empty($Biopsy_ID)){    
        $Update_biopsy = mysqli_query($conn, "UPDATE tbl_histological_examination SET Biposy_Status='saved' WHERE Biopsy_ID='$Biopsy_ID' AND Biposy_Status = 'pending'");
    }

    if($Update_biopsy){
        echo "The Histological Request was successfully submitted!";
    }else{
        echo "Please Fill the Required Biopsy Fields First before Saving";
    }

?>