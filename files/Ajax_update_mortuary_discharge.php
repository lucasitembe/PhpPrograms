<?php

//Taken_By:Taken_By,Death_Certificate:Death_Certificate,Kin_Out_Relationship:Kin_Out_Relationship,Kin_Out:Kin_Out,Kin_Out_Phone:Kin_Out_Phone,Kin_Out_Address:Kin_Out_Address,Registration_ID:Registration_ID
	session_start();
	include("./includes/connection.php");

	if(isset($_POST['Taken_By'])){
		$Taken_By = $_POST['Taken_By'];
	}else{
		$Taken_By = 0;
	}
	if(isset($_POST['Death_Certificate'])){
		$Death_Certificate = $_POST['Death_Certificate'];
	}else{
		$Death_Certificate = 0;
	}
	if(isset($_POST['Kin_Out_Relationship'])){
		$Kin_Out_Relationship = $_POST['Kin_Out_Relationship'];
	}else{
		$Kin_Out_Relationship = 0;
	}
	if(isset($_POST['Kin_Out'])){
		$Kin_Out = $_POST['Kin_Out'];
	}else{
		$Kin_Out = 0;
	}
	if(isset($_POST['Kin_Out_Phone'])){
		$Kin_Out_Phone = $_POST['Kin_Out_Phone'];
	}else{
		$Kin_Out_Phone = 0;
	}
	if(isset($_POST['Kin_Out_Address'])){
		$Kin_Out_Address = $_POST['Kin_Out_Address'];
	}else{
		$Kin_Out_Address = 0;
	}
	if(isset($_POST['Registration_ID'])){
		$Registration_ID = $_POST['Registration_ID'];
	}else{
		$Registration_ID = 0;
	}

	$result = mysqli_query($conn,"update tbl_mortuary_admission set Taken_By='$Taken_By',Death_Certificate='$Death_Certificate',Kin_Out_Relationship='$Kin_Out_Relationship',Kin_Out='$Kin_Out',Kin_Out_Phone='$Kin_Out_Phone',Kin_Out_Address='$Kin_Out_Address' where Corpse_ID='$Registration_ID'") or die(mysqli_error($conn));
        
          if($result){
              echo "yes";
              
          }else {
              echo "no";
          }
?>