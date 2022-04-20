<?php
    include("./includes/connection.php");
    if(isset($_GET['sponsor'])){
        $sponsor = $_GET['sponsor'];
    }else{
        $sponsor = 0;
    }
    // Membership_Id_Number_Status removed for a while
    $Select_District = "SELECT Verification FROM tbl_sponsor WHERE Guarantor_Name = '$sponsor'";
    $result = mysqli_query($conn,$Select_District);
    echo mysqli_fetch_array($result)['Verification'];
?> 