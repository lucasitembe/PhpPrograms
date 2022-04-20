<?php
    session_start();
    include("./includes/connection.php");
    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }
    if(isset($_GET['Status_From'])){
        $Status_From = $_GET['Status_From'];
    }else{
        $Status_From = '';
    }
    
    if(isset($_GET['Sub_Department_ID'])){
        $Sub_Department_ID = $_GET['Sub_Department_ID'];
    }else{
        $Sub_Department_ID = '';
    }
    if(isset($_GET['Patient_Payment_ID'])){
        $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
    }else{
        $Patient_Payment_ID = '';
    }
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = '';
    }
    
    if(strtolower($Status_From)=="payment"){
        $qr=mysqli_query($conn,"SELECT ppl.Status,ppl.Transaction_Type FROM tbl_patient_payment_item_list ppl,tbl_patient_registration pr,tbl_patient_payments pp,tbl_items it
                        WHERE ppl.Patient_Payment_ID=pp.Patient_Payment_ID
                        AND ppl.Item_ID=it.Item_ID
                        AND pp.Registration_ID=pr.Registration_ID
                        AND pp.Registration_ID='$Registration_ID'
                        AND ppl.Item_ID='$Item_ID'
                        AND ppl.Patient_Payment_ID='$Patient_Payment_ID'");
    }
    if(strtolower($Status_From)=="cache"){
        $qr=mysqli_query($conn,"SELECT il.Status,il.Transaction_Type FROM tbl_item_list_cache il,tbl_patient_registration pr,tbl_payment_cache pc,tbl_items it
                        WHERE il.Payment_Cache_ID=pc.Payment_Cache_ID
                        AND il.Item_ID=it.Item_ID
                        AND pc.Registration_ID=pr.Registration_ID
                        AND pc.Registration_ID='$Registration_ID'
                        AND il.Item_ID='$Item_ID'
                        AND il.Payment_Cache_ID='$Patient_Payment_ID' AND il.Sub_Department_ID='$Sub_Department_ID'");
    }
    
    while($row=mysqli_fetch_array($qr)){
        echo $Item_Status=$row['Status'];
        
        //echo $Transaction_Type=$row['Transaction_Type'];
    }
    
    
    
    
?>