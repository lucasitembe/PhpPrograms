<?php 
include("./includes/connection.php");
#get today vital sign 
function returnTodayVitalSign($patientId,$date){
	global $conn;
	$response = array();
	 $patientId;
	$sql = "SELECT Nurse_ID 
			FROM tbl_nurse n  
			WHERE n.Registration_ID = '$patientId' 
			";

	$result = mysqli_query($conn,$sql) or die(mysqli_query($conn));

	// var_dump($result);
	if ($result) {
		while ($row = mysqli_fetch_assoc($result)) {
			$value = $row['Nurse_ID'];
			array_push($response, $row['Nurse_ID']);
		}
		return json_encode($response);
	}else{
		return "name";
	}
}

// echo $today = Date("Y-m-d");
returnTodayVitalSign(16900,$today);
//print_r(returnTodayVitalSign(16900,$today));

 ?>
