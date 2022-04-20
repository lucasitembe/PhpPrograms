<?php
 include("./includes/connection.php");
   $admission_ID=0;
    if(isset($_GET['Discharge_Reason_ID'])){
	  $Discharge_Reason_ID = $_GET['Discharge_Reason_ID'];
          
          $select_discharge_condition_result=mysqli_query($conn,"SELECT discharge_condition FROM tbl_discharge_reason WHERE Discharge_Reason_ID='$Discharge_Reason_ID'") or die(mysqli_error($conn));
    
          if(mysqli_num_rows($select_discharge_condition_result)>0){
              echo $discharge_condition=mysqli_fetch_assoc($select_discharge_condition_result)['discharge_condition'];
          }
    }


?>