<?php
include("./includes/connection.php");
if(isset($_GET['Registration_ID'])){
    $Registration_ID=$_GET['Registration_ID'];
}else{
    $Registration_ID='';
}
$sql_check_for_admission="SELECT Admission_Status,Hospital_Ward_Name FROM tbl_admission ad INNER JOIN tbl_hospital_ward hw ON ad.Hospital_Ward_ID=hw.Hospital_Ward_ID WHERE (Admission_Status='Admitted' OR Admission_Status='pending') AND Registration_ID='$Registration_ID' AND (ward_room_id<>'0' OR Bed_Name<>'' OR Bed_Name<>NULL)";
$sql_check_for_admission_result=mysqli_query($conn,$sql_check_for_admission) or die(mysqli_error($conn));
if(mysqli_num_rows($sql_check_for_admission_result)>0){
    $row=mysqli_fetch_assoc($sql_check_for_admission_result);
    $Hospital_Ward_Name=$row['Hospital_Ward_Name'];
   echo  "The Selected Patient Arleady Admitted to ~~>$Hospital_Ward_Name";
}else{
    echo "no";
}
