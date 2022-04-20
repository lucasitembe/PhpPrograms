<?php
include("includes/connection.php");
// if(!isset($_SESSION['Employee_ID'])){
//     header("Location:./index.php");
// }
$clinic_id = isset($_GET['clinic_id'])?$_GET['clinic_id']:"";
$sql = "SELECT finance_department_id,finance_department_name 
        FROM tbl_finance_department 
        WHERE clinic_id='$clinic_id'";
$result = mysqli_query($conn,$sql) or die(mysqli_error($conn)); 
$data = [];
while($row=mysqli_fetch_assoc($result)){
    extract($row);
    $data['clinic_location_id'] = $finance_department_id;
    $data['clinic_location_name'] = $finance_department_name;
}
echo json_encode($data);
?>