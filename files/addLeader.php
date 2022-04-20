<?php
include("./includes/connection.php");
$data = array();

if(isset($_POST['leader'])){
    global $conn;

    $leader = mysqli_real_escape_string($conn,trim($_POST['leader']));
    $village = mysqli_real_escape_string($conn,trim($_POST['village']));

    $checkvillage = "SELECT `village_id` FROM `tbl_village` WHERE `village_name`='$village'";
    $resultCheckvilage = mysqli_query($conn,$checkvillage) or die(mysqli_error($conn));
    
    $village = mysqli_fetch_assoc($resultCheckvilage)['village_id'];
    $check = "Select * from tbl_leaders where leader_name like '$leader' and village_id='$village'";
    $resultCheck = mysqli_query($conn,$check) or die(mysqli_error($conn));
    $row = mysqli_num_rows($resultCheck);
    if ($row > 0){
        $data['leader_ID'] = "";
        $data['leader_name'] = "";
        $data['result'] = 'exist';
        echo json_encode($data);
    }else{    
        $sql = "INSERT INTO tbl_leaders(leader_name,village_id,date_added,added_by_user) VALUES('$leader','$village',now(),1)";
        $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
        $id = mysqli_insert_id();
        if($result){
            $data['leader_ID'] = $id;
            $data['leader_name'] = $leader;
            $data['result']  ="ok"; 
            echo json_encode($data);
        }else{
            echo mysqli_error($conn);
        }
    }
    
}
