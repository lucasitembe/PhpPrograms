<?php
include("includes/connection.php");
// if(!isset($_SESSION['Employee_ID'])){
//     header("Location:./index.php");
// }
$clinic_id = isset($_GET['clinic_id'])?$_GET['clinic_id']:"";
$sql = "SELECT Sub_Department_ID,Sub_Department_Name 
        FROM tbl_sub_department
        WHERE clinic_id='$clinic_id'";
$result = mysqli_query($conn,$sql) or die(mysqli_error($conn)); 
$data = [];
while($row=mysqli_fetch_assoc($result)){
    extract($row);
    $data['clinic_location_id'] = $Sub_Department_ID;
    $data['clinic_location_name'] = $Sub_Department_Name;
}
echo json_encode($data);
?>