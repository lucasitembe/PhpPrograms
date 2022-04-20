<?php
include("./includes/connection.php");
$Biopsy_ID = $_GET['Biopsy_ID'];
$Employee_ID = $_GET['Employee_ID'];


if($Priority == 'Routine'){
    $Priority = 'Normal';
}

    if(!empty($Biopsy_ID)){    
                $Update_biopsy = mysqli_query($conn, "UPDATE tbl_histological_examination SET Biposy_Status='Submitted', Submitted_By = '$Employee_ID' WHERE Biopsy_ID='$Biopsy_ID'");
    }

?>