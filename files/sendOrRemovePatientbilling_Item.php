<?php
    session_start();
    include("./includes/connection.php");
    
    $inserted = TRUE;
    $action = $_GET['action'];
    $Sub_Department_ID = $_GET['loc'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID']; 
    $Registration_ID = $_GET['Registration_ID'];
    $branch_id = $_SESSION['userinfo']['Branch_ID'];
    $Item_ID = $_GET['Item_ID'];
    $bill_type = $_GET['bill_type'];
    $Check_In_Type = $_GET['Type_Of_Check_In'];
    $Discount = $_GET['Discount'];
    $Quantity = $_GET['quantity'];
    $Patient_Direction = $_GET['Patient_Direction'];
    $Consultant_ID = '1';
    
    if($action=='ADD'){
        //Add Item
        $inSert = "INSERT INTO tbl_payment_item_list_cache(Employee_ID, Registration_ID, item_ID,
        Check_In_Type, Patient_Direction, Consultant_ID, Quantity,Discount,bill_type) VALUES('$Employee_ID', '$Registration_ID', '$Item_ID',
        '$Check_In_Type', '$Patient_Direction', '$Consultant_ID', '$Quantity','$Discount','$bill_type')";
        mysqli_query($conn,$inSert) or die(mysqli_error($conn));
        echo 'Added';
    }else{
        //Remove Item
        $delete_qr = "DELETE FROM tbl_payment_item_list_cache WHERE Employee_ID=$Employee_ID AND Registration_ID=$Registration_ID
        AND Item_ID=$Item_ID";
        mysqli_query($conn,$delete_qr) or die(mysqli_error($conn));
        echo "Removed";
    }
?>