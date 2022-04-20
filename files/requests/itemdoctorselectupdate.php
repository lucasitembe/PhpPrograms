<?php
    include("../includes/connection.php");
     @session_start();
    if(isset($_GET['Consultation_Type'])){
	$Consultation_Type = $_GET['Consultation_Type'];
	
	if($Consultation_Type=='Surgery'){
	    //$Consultation_Type = 'Theater';
	    $Consultation_Type = 'Surgery';
	}
	if($Consultation_Type=='Treatment'){
	    $Consultation_Type = 'Pharmacy';
	}if(isset($_GET['consultation_ID'])){
	    $consultation_id = $_GET['consultation_ID'];
    }
	}
         $employee_ID=$_SESSION['userinfo']['Employee_ID'];
	
	//selecting diagnosois
	$diagnosis_qr = "SELECT * FROM tbl_disease_consultation dc,tbl_disease d
		    WHERE dc.consultation_ID =$consultation_id AND
		    dc.disease_ID = d.disease_ID"
                . " AND dc.employee_ID='$employee_ID'";
	$result = mysqli_query($conn,$diagnosis_qr);
	$provisional_diagnosis = '';
	$diferential_diagnosis = '';
	$diagnosis = '';
	if(@mysqli_num_rows($result)>0){
	    while($row = mysqli_fetch_assoc($result)){
			$disease_code = $row['disease_code'];
		if($row['diagnosis_type']=='provisional_diagnosis'){
		    $provisional_diagnosis.= ' '.$row['disease_name'].'('.$disease_code.');';
		}
		if($row['diagnosis_type']=='diferential_diagnosis'){
		    $diferential_diagnosis.= ' '.$row['disease_name'].'('.$disease_code.');';
		}
		if($row['diagnosis_type']=='diagnosis'){
		    $diagnosis.= ' '.$row['disease_name'].'('.$disease_code.');';
		}
	    }
   }		
	  //die($Consultation_Type);
	  if($Consultation_Type=='provisional_diagnosis'){
	    echo 'provisional_diagnosis<$$$&&&&>'.$provisional_diagnosis;
	  }elseif($Consultation_Type=='diferential_diagnosis'){
	    echo 'diferential_diagnosis<$$$&&&&>'.$diferential_diagnosis;
	  }elseif($Consultation_Type=='diagnosis'){
	    echo 'diagnosis<$$$&&&&>'.$diagnosis;
	  }
	  
	  
	