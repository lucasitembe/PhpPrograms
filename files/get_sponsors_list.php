<?php
include("./includes/connection.php");
$result = mysqli_query($conn,"SELECT * FROM `tbl_sponsor` ");
$data=array();

while ($jibu = mysqli_fetch_array($result)) {

	$data2=array(
		'id'=>$jibu['Sponsor_ID'],
		'sponsor'=>$jibu['Guarantor_Name'],
		);

	array_push($data,$data2);

		

	}

	echo json_encode($data);