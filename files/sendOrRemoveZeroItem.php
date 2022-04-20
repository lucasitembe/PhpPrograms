<?php
    include("./includes/connection.php");
    if(isset($_GET['Sponsor_ID'])){
        $Sponsor_ID = $_GET['Sponsor_ID'];
    }else{
        $Sponsor_ID = 0;
    }
    
    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }
    
    if(isset($_GET['action'])){
        $action = $_GET['action'];
    }else{
        $action = 'none';
    }
    
    if($action=='REMOVE'){
        $delete_qr = "DELETE FROM tbl_sponsor_allow_zero_items WHERE sponsor_id = $Sponsor_ID AND item_ID=$Item_ID";
        if(mysqli_query($conn,$delete_qr)){
            echo "removed";
        }
    }else{
        $Disease_Consultation_Date_And_Time = "(SELECT NOW())";
        $insert_qr = "INSERT INTO tbl_sponsor_allow_zero_items(sponsor_id, item_ID) VALUES ($Sponsor_ID,$Item_ID)";
        if(mysqli_query($conn,$insert_qr)){
            echo "added";
        }
    }
?>