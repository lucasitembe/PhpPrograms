<?php
include 'includes/connection.php';

$rs = mysqli_query($conn,"SELECT id, date_of_birth from tbl_patient_excell") or die (mysqli_error($conn));

//print_r(mysqli_fetch_assoc($rs));exit;
while($row=mysqli_fetch_assoc($rs)){

if($row['date_of_birth'] != ''){
$date1=strtotime(date('Y-m-d'));
$time1=trim($row['date_of_birth'])*31556926;
$dob1 = $date1-$time1;
$dob=date('Y-m-d',$dob1);
  
   mysqli_query($conn,"update tbl_patient_excell set date_of_birth= '$dob' where id = ".$row['id']."") or die (mysqli_error($conn));
}

}
