<?php
    session_start();
    include("./includes/connection.php");

    
    if(isset($_SESSION['Disposal_ID'])){
        $Disposal_ID = $_SESSION['Disposal_ID'];
    }else{
        $Disposal_ID = 0;
    }
    
    if($Disposal_ID != 0 && $Disposal_ID != '' && $Disposal_ID != null){
        //get requisition created date
        $get_details = mysqli_query($conn,"select Created_Date_And_Time from tbl_disposal where Disposal_ID = '$Disposal_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($get_details);
        if($num > 0){
            while($row = mysqli_fetch_array($get_details)){
                $Created_Date_And_Time = $row['Created_Date_And_Time'];
            }
        }else{
            $Created_Date_And_Time = '';
        }
    }else{
        $Created_Date_And_Time = '';
    }
    echo $Created_Date_And_Time;
?>