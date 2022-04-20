<?php
    session_start();
    include("./includes/connection.php");

    if(isset($_SESSION['Laboratory_Consumption_ID'])){
        $Consumption_ID = $_SESSION['Laboratory_Consumption_ID'];
    }else{
        $Consumption_ID = 0;
    }

    if($Consumption_ID != 0 && $Consumption_ID != '' && $Consumption_ID != null){
        //get requisition created date
        $get_details = mysqli_query($conn,"select Created_Date_Time from tbl_consumption where Consumption_ID = '$Consumption_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($get_details);
        if($num > 0){
            while($row = mysqli_fetch_array($get_details)){
                $Created_Date_Time = $row['Created_Date_Time'];
            }
        }else{
            $Created_Date_Time = '';
        }
    }else{
        $Created_Date_Time = '';
    }
    echo $Created_Date_Time;
?>