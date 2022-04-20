<?php
    @session_start();
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
    
    if($Registration_ID != 0){
        //delete the previous record
        //find the latest record
        $select = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_id = '$Registration_ID' limit 1") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if($no > 0){
            while($row = mysqli_fetch_array($select)){
                $Check_In_ID = $row['Check_In_ID'];
            }
        }else{
            $Check_In_ID = $row['Check_In_ID'];
        }
        
        //delete details based on check in id we found
        $delete_details = mysqli_query($conn,"delete from tbl_check_in where Check_In_ID = '$Check_In_ID'") or die(mysqli_error($conn));
    }
    
    
    
    <!--<a href='patientbillingprepare.php?Registration_ID=<?php echo $Temp_Registration_ID; ?>&NR=True&PatientBillingPrepare=PatientBillingPrepareThisForm' class='art-button-green' target='_Parent'>Back to previous patient</a>-->
?>