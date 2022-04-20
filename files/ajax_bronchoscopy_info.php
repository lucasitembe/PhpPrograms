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
        if (isset($_POST['Registration_ID'])) {
            $Registration_ID = $_POST['Registration_ID'];
        } else {
            $Registration_ID = 0;
        }
        if (isset($_POST['Payment_Item_Cache_List_ID'])){
            $Payment_Item_Cache_List_ID = $_POST['Payment_Item_Cache_List_ID'];
        } else {
            $Payment_Item_Cache_List_ID = 0;
        }
        if(isset($_POST['Employee_ID'])){ 
            $Employee_ID= $_POST['Employee_ID'];
            }else{
            $Employee_ID="";   
            }
        
        
        


            if(isset($_POST['Bronchoscopy_Notes_ID'])){
                $Bronchoscopy_Notes_ID= $_POST['Bronchoscopy_Notes_ID'];
            }else{
            $Bronchoscopy_Notes_ID="";    
            }

            if(isset($_POST['Sub_Department_ID'])){
                $Sub_Department_ID = $_POST['Sub_Department_ID'];
            }else{
                $Sub_Department_ID= "";
            }
            if(isset($_POST['add_item'])){
                $Bronchoscopy_Notes_ID=mysqli_real_escape_string($conn, $_POST['Bronchoscopy_Notes_ID']);
                $Employee_ID=$_SESSION['userinfo']['Employee_ID']; 
        
                //select anesthesia record id
                    $anasthesia_record = "SELECT Bronchoscopy_Notes_ID FROM tbl_Bronchoscopy_notes WHERE Registration_ID = '$Registration_ID' AND status='status'";
                    $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
                    if(mysqli_num_rows($anasthesia_record_result)>0){
                        $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['Bronchoscopy_Notes_ID'];
                    }else{
                        $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
                        $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTO tbl_Bronchoscopy_notes(Registration_ID, Payment_Item_Cache_List_ID, created_at, Employee_ID ) VALUES('$Registration_ID', '$Payment_Item_Cache_List_ID', NOW(), '$anasthesia_employee_id')") or die(mysqli_error($conn));
                        $anasthesia_record_id=mysqli_insert_id($conn);
                        
                }
   
    }
