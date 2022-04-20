<?php
	session_start();
	include("./includes/connection.php");
	if(isset($_GET['bed_id'])){
		$bed_id = $_GET['bed_id'];
	}else{
		$bed_id = 0;
	}   
	if(isset($_GET['ward_room_id'])){
		$ward_room_id = $_GET['ward_room_id'];
	}else{
		$ward_room_id = 0;
	}   
    if(isset($_GET['Hospital_Ward_ID'])){
		$Hospital_Ward_ID = $_GET['Hospital_Ward_ID'];
	}else{
		$Hospital_Ward_ID = 0;
	}
  $set_duplicate_bed_assign = $_SESSION['hospitalConsultaioninfo']['set_duplicate_bed_assign'];
  
  
  $bedStat="";      
  if ($set_duplicate_bed_assign == '0') {
      $bedStat=" AND Status = 'available'";
  }
?>

<?php
//  AND Hospital_Ward_ID='$Hospital_Ward_ID'
// AND Hospital_Ward_ID='$Hospital_Ward_ID'
         
        // $not_available_beds = "SELECT Admision_ID FROM tbl_admission ad, tbl_discharge_reason dr WHERE Bed_Name='$bed_id' AND ward_room_id='$ward_room_id' AND ad.Discharge_Reason_ID =dr.Discharge_Reason_ID  AND Admission_Status != 'Discharged' AND  Discharge_Reason NOT IN ('Abscond', 'Death') ";
  		
        // $qry=mysqli_query($conn,$not_available_beds) or die(mysqli_error($conn));
        
		// $selectadmitted = mysqli_query($conn, "SELECT Admision_ID FROM tbl_admission ad WHERE Bed_Name='$bed_id' AND ward_room_id='$ward_room_id'    AND Admission_Status = 'Admitted'  " ) or die(mysqli_error($conn));
  				

		//   echo (mysqli_num_rows($qry) + mysqli_num_rows($selectadmitted));

		//BUGANDO (ROBERT+EDSON WAMEOMBA HIVI ISIANGALIE VITANDA)
		echo 0;
?>
