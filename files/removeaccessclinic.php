<?php
    @session_start();
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_GET['Employee_ID'])){
        if(isset($_GET['Clinic_ID'])){
            $Clinic_ID = $_GET['Clinic_ID'];
            $Employee_ID = $_GET['Employee_ID'];
        }
    }else{
        $Employee_ID = 0;
        $Clinic_ID = 0;
    }
    
    if(mysqli_query($conn,"delete from tbl_clinic_employee where Employee_ID = '$Employee_ID' and Clinic_ID = '$Clinic_ID'")){
        $is_hr=((isset($_GET['HRWork']) && $_GET['HRWork']=='true')?'&HRWork=true':'');
        header("Location: ./assignclinic.php?Employee_ID=$Employee_ID&EditEmployee=EditEmployeeThisForm".$is_hr);
    }else{
        die(mysqli_error($conn));
    }
    ?>