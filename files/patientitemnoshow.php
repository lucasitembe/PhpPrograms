<?php
    include("./includes/connection.php");
    session_start();
    
    if(isset($_GET['Patient_Payment_Item_List_ID'])){
        $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
        $Employee_ID = $_SESSION['userinfo']['Employee_ID']; 
    }else{
        $Patient_Payment_Item_List_ID = 0;
        $Employee_ID = 0;
    }
    $delete_qr = "UPDATE tbl_patient_payment_item_list SET Process_Status = 'no show' WHERE Patient_Payment_Item_List_ID = $Patient_Payment_Item_List_ID";
    if(!mysqli_query($conn,$delete_qr)){
        die(mysqli_error($conn));
    }else{
        echo "removed";
    }
?>