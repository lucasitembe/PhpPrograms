<?php
include("./includes/connection.php");
    if(isset($_GET['Billing_Type'])){
        $Billing_Type=$_GET['Billing_Type'];
    }else{
        $Billing_Type='';
    }
    if(isset($_GET['Clinic_ID'])){
        $Clinic_ID=$_GET['Clinic_ID'];
    }else{
        $Clinic_ID='';
    }
    if(isset($_GET['Registration_ID'])){
        $Registration_ID=$_GET['Registration_ID'];
    }else{
        $Registration_ID='';
    }
    if(isset($_GET['finance_department_id'])){
        $finance_department_id=$_GET['finance_department_id'];
    }else{
        $finance_department_id='';
    }
    if(isset($_GET['clinic_location_id'])){
        $clinic_location_id=$_GET['clinic_location_id'];
    }else{
        $clinic_location_id='';
    }
    $sql_update_clinic_id="UPDATE tbl_reception_items_list_cache SET Clinic_ID='$Clinic_ID',finance_department_id='$finance_department_id',clinic_location_id='$clinic_location_id' WHERE Registration_ID='$Registration_ID'";
     $sql_update_clinic_id_result=mysqli_query($conn, $sql_update_clinic_id) or die(mysqli_error($conn));
     if($sql_update_clinic_id_result){
       echo "success";  
     }else{
       echo "fail";  
     }
    