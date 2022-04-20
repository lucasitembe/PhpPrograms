
<?php
require_once("nhif3/constants.php");

include("./includes/connection.php");
include("nhif3/ServiceManager.php");
header("Content-Type:application/json");

$sm =  new ServiceManager();
$Item_ID = $_POST['Item_ID'];

$Registration_ID = $_POST['Registration_ID'];
$RefferenceNo = $_POST['treatment_authorization_no'];

$ItemCode = mysqli_fetch_assoc(mysqli_query($conn, "SELECT  Product_Code FROM tbl_items WHERE Item_ID = $Item_ID"))['Product_Code'];

$CardNo = mysqli_fetch_assoc(mysqli_query($conn, "SELECT  Member_Number FROM tbl_patient_registration WHERE Registration_ID = $Registration_ID"))['Member_Number'];
$response=json_decode($sm->ServiceVerification($CardNo,$RefferenceNo,$ItemCode), TRUE);

// die(print_r($sm->ServiceVerification($CardNo,$RefferenceNo,$ItemCode)));

if($response['Status'] == 'INVALID'){
    echo json_encode(array("code"=>400));
}else if($response['StatusCode'] ==0){
    echo json_encode(array("code"=>$response));    
}else if($response['StatusCode'] ==200){
    echo json_encode(array("code"=>200));    
}else{
    echo json_encode(array("code"=>500));
}
?>
