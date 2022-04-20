<?php
    session_start();
    include("./includes/header.php");
    include("./includes/connection.php");
        if (!isset($_SESSION['userinfo'])) {
            @session_destroy();
            header("Location: ../index.php?InvalidPrivilege=yes");
        }
        if (isset($_POST['consultation_ID'])) {
            $consultation_ID = $_POST['consultation_ID'];
        } else {
            $consultation_ID = 0;
        }
        if(isset($_POST['Employee_ID'])){ 
            $Employee_ID= $_POST['Employee_ID'];
            }else{
            $Employee_ID="";   
            }
        
        
        


            if(isset($_POST['Jobcard_ID'])){
                $Jobcard_ID= $_POST['Jobcard_ID'];
            }else{
            $Jobcard_ID="";    
            }
            if(isset($_POST['Sub_Department_ID'])){
                $Sub_Department_ID = $_POST['Sub_Department_ID'];
            }else{
                $Sub_Department_ID= "";
            }
        if(isset($_POST['consent_signed_pre'])){
            $Jobcard_ID=mysqli_real_escape_string($conn, $_POST['Jobcard_ID']);
            $Sub_Department_ID=mysqli_real_escape_string($conn, $_POST['Sub_Department_ID']);
            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
            //select anesthesia record id
            $anasthesia_record = "SELECT Jobcard_Order_ID FROM tbl_jobcard_orders WHERE Jobcard_ID = '$Jobcard_ID'";
                $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
                // if(mysqli_num_rows($anasthesia_record_result)>0){
                //     $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['list_ID'];
                // }else{
                //     $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
                //     $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_anasthesia_record_chart(Registration_ID, Payment_Cache_ID, anasthesia_created_at, anasthesia_employee_id ) VALUES('$Registration_ID','$Payment_Cache_ID', NOW(), '$anasthesia_employee_id')") or die(mysqli_error($conn));
                //     $anasthesia_record_id=mysqli_insert_id($conn);
                    
                // }
   
    }
