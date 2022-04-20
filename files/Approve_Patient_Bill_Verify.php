<?php
	session_start();
	include("./includes/connection.php");
	
	if(isset($_GET['Transaction_Type'])){
		$Transaction_Type = $_GET['Transaction_Type'];
	}else{
		$Transaction_Type = '';
	}

	if(isset($_GET['Admision_ID'])){
		$Admision_ID = $_GET['Admision_ID'];
	}else{
		$Admision_ID ='';
	}
	
        $reg_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Registration_ID FROM tbl_admission WHERE Admision_ID='$Admision_ID'"))['Registration_ID'];
        $SPONSOR_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Sponsor_ID FROM  tbl_patient_registration WHERE Registration_ID='$reg_ID'"))['Sponsor_ID'];
        $TYPE = mysqli_fetch_assoc(mysqli_query($conn,"SELECT payment_method FROM tbl_sponsor WHERE Sponsor_ID='$SPONSOR_ID'"))['payment_method'];
//==================CHECKING FROM MORGUE, DONE BY FULL STACK DEVELOPERS===================
$morgueDetails=mysqli_query($conn,"SELECT Date_Of_Death,case_type FROM tbl_mortuary_admission WHERE Admision_ID='$Admision_ID'") or die(mysqli_error($conn));
$num=mysqli_num_rows($morgueDetails);
if ($num > 0) {	

		//check if status allows bill to be approved
		$select = mysqli_query($conn,"SELECT Admission_Status, Cash_Bill_Status, Credit_Bill_Status, Discharge_Clearance_Status from tbl_admission where Admision_ID = '$Admision_ID'") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
			while ($data = mysqli_fetch_array($select)) {
				$Admission_Status = $data['Admission_Status'];
				$Discharge_Clearance_Status = $data['Discharge_Clearance_Status'];
				$Cash_Bill_Status = $data['Cash_Bill_Status'];
				$Credit_Bill_Status = $data['Credit_Bill_Status'];

			}
		}else{
			$Admission_Status = '';
			$Discharge_Clearance_Status = '';
			$Cash_Bill_Status = '';
			$Credit_Bill_Status = '';
		}

                 if($TYPE != "credit"){ 
		if($Transaction_Type == 'Cash_Bill_Details'){
			if(strtolower($Admission_Status) == 'pending'){
				if(strtolower($Cash_Bill_Status) == 'pending'){
					echo "yes"; //ready to be cleared
				}else if(strtolower($Cash_Bill_Status) == 'cleared'){
					echo "true"; //already cleared
				}else{
					echo "no"; //other status 
				}
			}else{
				echo "mortuary_not"; //not ready
			}
		}else{
			if(strtolower($Admission_Status) == 'pending'){
				if(strtolower($Credit_Bill_Status) == 'pending'){
					echo "yes"; //ready to be cleared
				}else if(strtolower($Credit_Bill_Status) == 'cleared'){
					echo "true"; // already cleared
				}else{
					echo "no"; //other status
				}
			}else{
				echo "not"; //not ready
			}
                 }}else{
                       echo "change";
                     
                 }
                
} else 
	{
		//check if status allows bill to be approved
		$select = mysqli_query($conn,"SELECT Admission_Status, Cash_Bill_Status, Credit_Bill_Status, Discharge_Clearance_Status from tbl_admission where Admision_ID = '$Admision_ID'") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
			while ($data = mysqli_fetch_array($select)) {
				$Admission_Status = $data['Admission_Status'];
				$Discharge_Clearance_Status = $data['Discharge_Clearance_Status'];
				$Cash_Bill_Status = $data['Cash_Bill_Status'];
				$Credit_Bill_Status = $data['Credit_Bill_Status'];

			}
		}else{
			$Admission_Status = '';
			$Discharge_Clearance_Status = ''; 
			$Cash_Bill_Status = ''; 
			$Credit_Bill_Status = '';
		}

		if($Transaction_Type == 'Cash_Bill_Details'){
			if(strtolower($Admission_Status) == 'pending'){
				if(strtolower($Cash_Bill_Status) == 'pending'){
					echo "yes"; //ready to be cleared
				}else if(strtolower($Cash_Bill_Status) == 'not cleared'){
					echo "yes";
				}else if(strtolower($Cash_Bill_Status) == 'cleared'){
					echo "true"; //already cleared
				}else{
					echo "no"; //other status
				}
			}else{
				echo "not"; //not ready
			}
		}else{
			if(strtolower($Admission_Status) == 'pending'){
				if(strtolower($Credit_Bill_Status) == 'pending'){
					echo "yes"; //ready to be cleared
				}else if(strtolower($Credit_Bill_Status) == 'cleared'){
					echo "true"; // already cleared
				}else{
					echo "no"; //other status
				}
			}else{
				echo "not"; //not ready
			}
		}
	}
?>