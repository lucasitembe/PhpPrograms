<?php
include("./includes/connection.php");
if(isset($_GET['patient_id'])&&$_GET['patient_id']!=''){
    
    $inalala_bilakulala=$_GET['inalala_bilakulala'];
    $Patient_ID=$_GET['patient_id'];
    
$sql_inalala_bilakulala_type="UPDATE tbl_mortuary_admission SET inalala_bilakulala ='$inalala_bilakulala' WHERE Corpse_ID='$Patient_ID'";

$sql_inalala_bilakulala_type_result=mysqli_query($conn,$sql_inalala_bilakulala_type) or die(mysqli_error($conn));
if($sql_inalala_bilakulala_type_result){
   $inalala_bilakulala=mysqli_fetch_assoc(mysqli_query($conn,"SELECT inalala_bilakulala FROM `tbl_mortuary_admission` WHERE `Corpse_ID`='$Patient_ID'"))['inalala_bilakulala'];

   echo 'ok';
}else{
    echo "Error";
}

}