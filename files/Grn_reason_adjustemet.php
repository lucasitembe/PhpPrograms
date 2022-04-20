<?php
    include("./includes/connection.php");
    
    
    
//              $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    
  if(isset($_POST['reason_Name'])){
    $reason_Name=$_POST['reason_Name'];
}else{
    $reason_Name="";
}

   if(isset($_POST['Employee_ID'])){
    $Employee_ID = $_POST['Employee_ID'];
  }else{
   $Employee_ID = "";
   }


   $sql_reason =mysqli_query($conn,"INSERT INTO tbl_reasons_adjustment (reasons,Status,Employee_ID) VALUES('$reason_Name','enabled','$Employee_ID')");
   
   if( $sql_reason){
       echo "succefully";
       
   }else{
       echo "failed";
   }

?>

