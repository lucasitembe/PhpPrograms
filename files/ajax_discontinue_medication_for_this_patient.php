<?php
require_once('./includes/connection.php');
session_start();
if (isset($_POST['consultation_ID'])) {
    $consultation_ID = $_POST['consultation_ID'];
    $Registration_ID = $_POST['Registration_ID'];
    $Item_ID = $_POST['Item_ID'];
    $Payment_Item_Cache_List_ID=$_POST['Payment_Item_Cache_List_ID'];
    $discontinue_reason = $_POST['discontinue_reason'];
    $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
    $insert_services_given = "INSERT INTO tbl_inpatient_medicines_given( Item_ID,	Employee_ID,  	Registration_ID,    consultation_ID,	Discontinue_Status,  	Discontinue_Reason, Payment_Item_Cache_List_ID, Time_Given, medication_time	) 	VALUES('$Item_ID ','$Employee_ID',  	'$Registration_ID',      '$consultation_ID','yes',  '$discontinue_reason', '$Payment_Item_Cache_List_ID', NOW(), NOW())";
      $result_query=mysqli_query($conn,$insert_services_given) or die(mysqli_error($conn));
      if($result_query){
          echo "success";
      }else{
          echo "fail";
      }
}
