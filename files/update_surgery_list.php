
<?php
    include("../includes/connection.php");
       $Sub_Department_ID=$_POST['Sub_Department_ID'];  
       $Employee_ID=$_POST['Employee_ID'];
       $Action=$_POST['Action'];
     if(isset($Employee_ID)&& isset($Sub_Department_ID)){
         if($Action == 'Authorize'){
            $query=  mysqli_query($conn,"UPDATE tbl_surgery_appointment SET Surgery_Status='Authorized' WHERE Surgery_Status='pending'");
            if($query){
                echo 'Successfully changed!';
            }  else {
                echo 'Changing error,try again!';
            }
         }elseif($Action == 'Submit'){
            $query=  mysqli_query($conn,"UPDATE tbl_surgery_appointment SET Surgery_Status='Submitted', Anaesthesist_ID = '$Employee_ID', Submitted_At = NOW() WHERE Surgery_Status='Authorized' AND Anaesthesia_Type IS NOT NULL");
            if($query){
                echo 'Successfully changed!';
            }  else {
                echo 'No Anaesthesia Type Selected!';
            }
         }else{
            $query=  mysqli_query($conn,"UPDATE tbl_surgery_appointment SET Surgery_Status='Active', Approved_Date = NOW(), Approved_By  = '$Employee_ID' WHERE Surgery_Status='Submitted'");
            if($query){
                echo 'Successfully changed!';
            }  else {
                echo 'Changing error,try again!';
            }
         }

 }
 
 mysqli_close($conn);
?>
