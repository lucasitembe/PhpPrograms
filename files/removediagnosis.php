<?php
    include("./includes/connection.php");
    if(isset($_GET['Disease_Consultation_ID'])){
        $Disease_Consultation_ID = $_GET['Disease_Consultation_ID'];
    }else{
        $Disease_Consultation_ID = 0;
    }
    $delete_qr = "DELETE FROM tbl_disease_consultation WHERE Disease_Consultation_ID = $Disease_Consultation_ID";
    if(mysqli_query($conn,$delete_qr)){
        echo "removed";
    }
?>