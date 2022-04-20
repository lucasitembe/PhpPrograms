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
        if(isset($_GET['Sub_Department_ID'])){
            $Sub_Department_ID = $_GET['Sub_Department_ID'];
            $Employee_ID = $_GET['Employee_ID'];
        }
    }else{
        $Employee_ID = 0;
        $Sub_Department_ID = 0;
    }
    
    if(mysqli_query($conn,"delete from tbl_employee_sub_department where Employee_ID = '$Employee_ID' and Sub_Department_ID = '$Sub_Department_ID'")){
        header("Location: ./assignemployeesubdepartment.php?Employee_ID=$Employee_ID&EditEmployee=EditEmployeeThisForm");
    }else{
        die(mysqli_error($conn));
    }
    ?>