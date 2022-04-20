<?php
    session_start();
    include("./includes/connection.php");
    if(isset($_SESSION['Grn_Open_Balance_ID'])){
        $Grn_Open_Balance_ID = $_SESSION['Grn_Open_Balance_ID'];
        
        //get employee id
        if(isset($_SESSION['userinfo']['Employee_ID'])){
            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        }else{
            $Employee_ID = 0;
        }
        
        //get open balance grn description
        if(isset($_GET['Grn_Description'])){
            $Grn_Description = $_GET['Grn_Description'];
        }else{
            $Grn_Description = '';
        }
        
        if($Employee_ID != 0 && $Employee_ID != null && isset($_GET['Grn_Description'])){
            $update = mysqli_query($conn,"update tbl_grn_open_balance set Grn_Open_Balance_Description = '$Grn_Description'
                                    where Grn_Open_Balance_ID = '$Grn_Open_Balance_ID'") or die(mysqli_error($conn));
        }
    }
?>