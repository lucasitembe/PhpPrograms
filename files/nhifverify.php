<?php
    session_start();
    unset($_SESSION['NHIF_Member']);
    $Member_Number = $_GET['Member_Number'];
    $_SESSION['NHIF_Member']['Member_Number'] = $Member_Number;
    
    $reply = @file("http://41.59.13.149/NHIFService/breeze/Verification/GetCard?CardNo=$Member_Number");
    
    if($reply){
    $response = explode(',',$reply[0]);
    $result = explode(':',$response[15])[1];
    $result = trim($result,'"');
        if(strtolower($result)=='active'){
            $_SESSION['NHIF_Member']['Status'] = 'Active';
        }elseif(strtolower($result)=='inactive'){
            $_SESSION['NHIF_Member']['Status'] = 'Inactive';
        }
    echo strtolower($result);
    }
?>