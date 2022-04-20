<?php 

include("./includes/connection.php");

#get patient info
   $patient_registration_id = $_GET['Registration_ID'];
   $select_patient_registration_info = mysqli_query($conn, "SELECT Patient_Name,Date_Of_Birth,Gender FROM tbl_patient_registration WHERE Registration_ID = '$patient_registration_id' ");
   while ($patient_details = mysqli_fetch_array($select_patient_registration_info)) :
      $hospital_number = $patient_details['Registration_ID'];
      $name = $patient_details['Patient_Name'];
      $gender = $patient_details['Gender'];
      $age = date('Y') - $patient_details['Date_Of_Birth'];
      $district = $patient_details['District'];
   endwhile;


?>