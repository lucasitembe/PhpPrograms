<?php
include("../includes/connection.php"); 
if(isset($_POST['spectacle'])){
    $spectacle = $_POST['spectacle'];
}

if(isset($_POST['patient_id'])){
  $patietnId = $_POST['patient_id'];  
}

$patient_status = "pending";

$today = Date("Y-m-d");

# check if data already filled

if(checkForExistance()){
    echo "true";
    updataData();
}else{
    echo "false";
    insertData();
}    

function checkForExistance(){
    global $patietnId;
    global $patient_status;
    global $today;
    $sql = "SELECT status FROM tbl_spectacle_status
            WHERE patient_id = '$patietnId'
            AND created_at='$today'";
    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
    $num = mysqli_num_rows($result);
    if($num > 0){
        return true;
    }else
   return false; 
}

function insertData(){
    global $patietnId;
    global $patient_status;
    global $spectacle;
    global $today;
        
        $sql = "INSERT INTO tbl_spectacle_status(patient_id,status,patient_status,created_at) 
            VALUES('$patietnId','$spectacle','$patient_status','$today')";
        $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
        
        if($result)
            echo "saved";
}

function updataData(){
    global $patietnId;
    global $patient_status;
    global $spectacle;
    global $today;
    $sql = "UPDATE tbl_spectacle_status 
            SET status = '$spectacle' 
            WHERE patient_id='$patietnId'
            AND created_at='$today'";
    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
    if($result)
        echo "save";
}

?>