<?php
    session_start();
    include("./includes/connection.php");

    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
    	$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
    	$Employee_ID = 0;
    }

    if(isset($_GET['Certify_Comment'])){
        $Certify_Comment = $_GET['Certify_Comment'];
    }else{
        $Certify_Comment = '';
    }
    
    if(isset($_GET['Jobcard_ID'])){
    	$Jobcard_ID = $_GET['Jobcard_ID'];
    }else{
    	$Jobcard_ID = 0;
    }

    
    if($Employee_ID != 0 && $Jobcard_ID != 0){
    	//check if data available into tbl_Bronchoscopy_notes
	    //update
	    $update = mysqli_query($conn,"update tbl_jobcards set
                                status='Certified', Certified_by='$Employee_ID', Certify_Comment='$Certify_Comment', Certified_at=NOW() where Jobcard_ID = '$Jobcard_ID'") or die(mysqli_error($conn));
	}
?>







