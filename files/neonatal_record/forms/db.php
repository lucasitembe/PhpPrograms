<?php

include('../../../connection.php');

$sql = "SELECT HOUR(Date_Time_Of_Delivery) WHERE Registration_ID =16829";
$sql = "SELECT DATEPART(hour, Date_Time_Of_Delivery) AS DatePartInt FROM tbl_postnatal_after_delivery_records WHERE Registration_ID =16829";
$query = mysqli_query($conn,$sql);

if(mysqli_num_rows($query) > 0)
{
  echo "data available";
}else{
  echo "result not found.";
}


 ?>
