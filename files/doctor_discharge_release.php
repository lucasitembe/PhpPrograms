.
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    <?php

 include("./includes/connection.php");
    $temp = 1;
    $total = 0;
    $Title = '';
    $consultation_ID=0;$Admission_Status='';
    
    if(isset($_GET['consultation_ID']) && !empty($_GET['consultation_ID'])){
	$consultation_ID = $_GET['consultation_ID'];
    }else{
	$consultation_ID = '';
    }if(isset($_GET['Registration_ID']) && !empty($_GET['Registration_ID'])){
	$Registration_ID = $_GET['Registration_ID'];
    }else{
	$Registration_ID = '';
    }if(isset($_GET['Folio_Number']) && !empty($_GET['Folio_Number'])){
	$Folio_Number = $_GET['Folio_Number'];
    }else{
	$Folio_Number = '';
    }if(isset($_GET['status']) && !empty($_GET['status'])){
	  $status = $_GET['status'];
    }
	
	if($status=='Continue'){
	 $Admission_Status='Admitted';
	}elseif($status=='Discharge'){
		 $Admission_Status='pending';
	}
	
	$qr="SELECT Admission_ID FROM tbl_check_in_details WHERE Registration_ID='$Registration_ID' AND Folio_Number='$Folio_Number' AND consultation_ID='$consultation_ID'";
	$rs=mysqli_query($conn,$qr) or die(mysqli_error($conn));
	
	 $Admission_ID=mysqli_fetch_assoc($rs)['Admission_ID'];
	
	 $query=mysqli_query($conn,"UPDATE tbl_admission SET Admission_Status='$Admission_Status' WHERE Admision_ID='$Admission_ID'") or die(mysqli_error($conn));
	
	 if($query){
	   echo 1;
	 }

?>