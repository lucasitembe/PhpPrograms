<?php
    include("../includes/connection.php");
    if(isset($_GET['Consultation_Type'])){
	$Consultation_Type = $_GET['Consultation_Type'];
	
	if($Consultation_Type=='Surgery'){
	    //$Consultation_Type = 'Theater';
	    $Consultation_Type = 'Surgery';
	}
	if($Consultation_Type=='Treatment'){
	    $Consultation_Type = 'Pharmacy';
	}if(isset($_GET['Round_ID'])){
	    $Round_ID = $_GET['Round_ID'];
    }
	}
	
	//selecting diagnosois
	$diagnosis_qr = "SELECT * FROM tbl_ward_round_disease wd,tbl_disease d
		    WHERE wd.Round_ID ='$Round_ID' AND
		    wd.disease_ID = d.disease_ID";
			
			//echo $diagnosis_qr;exit;
	$result = mysqli_query($conn,$diagnosis_qr);
	$provisional_diagnosis = '';
	$diferential_diagnosis = '';
	$diagnosis = '';
	if(@mysqli_num_rows($result)>0){
	    while($row = mysqli_fetch_assoc($result)){
		if($row['diagnosis_type']=='provisional_diagnosis'){
		    $provisional_diagnosis.= ' '.$row['disease_name'].';';
		}
		if($row['diagnosis_type']=='diferential_diagnosis'){
		    $diferential_diagnosis.= ' '.$row['disease_name'].';';
		}
		if($row['diagnosis_type']=='diagnosis'){
		    $diagnosis.= ' '.$row['disease_name'].';';
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
	  
	  
	