<?php
session_start();
include("./includes/connection.php");

//header("Access-Control-Allow-Origin: *");
if(strpos($_SERVER['HTTP_ORIGIN'], 'javascript') == false)
																			{
		header('Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN']);
}

//**************************save observation data ********************************************************************
$data=json_decode(file_get_contents("php://input"));

if ($data->action == 'save_observation') {

  $Registration_ID = mysqli_real_escape_string($conn,trim($data->Registration_ID));
  $Employee_ID = mysqli_real_escape_string($conn,trim($data->Employee_ID));
  $ward_no = mysqli_real_escape_string($conn,trim($data->ward_no));
  $bp = mysqli_real_escape_string($conn,trim($data->bp));
  $pr = mysqli_real_escape_string($conn,trim($data->pr));
  $fhr = mysqli_real_escape_string($conn,trim($data->fhr));
  $contraction = mysqli_real_escape_string($conn,trim($data->contraction));
  $pve = mysqli_real_escape_string($conn,trim($data->pve));
  $temp = mysqli_real_escape_string($conn,trim($data->temp));
  $observation_date = mysqli_real_escape_string($conn,trim($data->observation_date));
	$Admision_ID = mysqli_real_escape_string($conn,trim($data->Admision_ID));
  $consultation_id = mysqli_real_escape_string($conn,trim($data->consultation_id));

  $save_observ = "INSERT INTO tbl_labour_observation_chart(
                  Registration_ID,Employee_ID,Admision_ID,consultation_id,ward_no,bp,pr,fhr,contraction,pve,
                  temp,observation_date
                  )
                  VALUES('$Registration_ID','$Employee_ID','$Admision_ID','$consultation_id','$ward_no','$bp','$pr','$fhr','$contraction','$pve','$temp','$observation_date')";

  $execute = mysqli_query($conn,$save_observ);

  if ($execute) {
  echo "Record Saved Successfully!";
  }
  else {
    die("Failed: ".mysqli_error($conn));
  }
}

include("MPDF/mpdf.php");
//******************************retrieve observation details************************************************************
if ($_GET['action'] == 'get_data') {

  $regId = $_GET['regId'];
  $get_observ = "SELECT Registration_ID,o.Employee_ID,e.Employee_ID,e.Employee_Name,ward_no,bp,pr,fhr,contraction,pve,
                 temp,observation_date
                 FROM tbl_labour_observation_chart o
                 INNER JOIN tbl_employee e
                 ON o.Employee_ID = e.Employee_ID
                 WHERE Registration_ID = '$regId' ORDER BY saved_time DESC";
  $execute1 = mysqli_query($conn,$get_observ);
  $output = array();

  if (mysqli_num_rows($execute1) > 0) {

    while ($r = mysqli_fetch_assoc($execute1)) {

      $output[] = $r;
    }

    echo json_encode($output);
  }else{
    die("No record found".mysqli_error($conn));
  }
}
 ?>
