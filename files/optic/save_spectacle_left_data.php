<?php
//developed by: malopa 
include("../includes/connection.php");
if(isset($_POST['name'])){
    
    if($_POST['name'] == "spectacle-sph-left")
        $fieldName = "sph";
    else if($_POST['name'] == "spectacle-cyl-left")
        $fieldName = "cyl";
    else if($_POST['name'] == "spectacle-prism-left")
        $fieldName = "prism_base";

}


if(isset($_POST['patient_id'])){
    $patientId = $_POST['patient_id'];
    
}

if(isset($_POST['nameValue'])){
    $nameValue = $_POST['nameValue'];
}
 
$today = Date("Y-m-d");

//if there data update content if no isert content

if(checkIfDataExist()){
    echo "true";    
    updateContent();
}else{
    echo "false";
    insertDataIfNotExist();
}


// check if patient data is there for particula day


function checkIfDataExist(){
    global $today;
    global $patientId;
    global $fieldName;
    $data = mysqli_query($conn,"SELECT * FROM  tbl_spectacle_distance_left WHERE patient_id='$patientId' AND datetime='$today' ") or die(mysql_errror());
    $num = mysqli_num_rows($data);
    if($num > 0){
        return true;
    }
   return false; 
}

// update content if patient data are there for particular day;
function updateContent(){
    global $today;
    global $patientId;
    global $fieldName;
    global $nameValue;
    $query = "UPDATE  tbl_spectacle_distance_left SET $fieldName='$nameValue' WHERE patient_id = '$patientId' AND datetime='$today'";

    $result = mysqli_query($conn,$query) or die(mysqli_error($conn));
    if($result)
    echo "successfully";
}

// insertData For new consultation
function insertDataIfNotExist(){
    global $today;
    global $patientId;
    global $fieldName;
    global $nameValue;

    $query = mysqli_query($conn,"INSERT INTO  tbl_spectacle_distance_left(patient_id,$fieldName,datetime) VALUES('$patientId','$nameValue','$today')");
    

    if($query){
        echo "successfully inserted";
    }else{
        die(mysqli_error($conn));
    }
    
}

?>  