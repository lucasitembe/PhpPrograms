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
        if(isset($_GET['Branch_ID'])){
            $Branch_ID = $_GET['Branch_ID'];
            $Employee_ID = $_GET['Employee_ID'];
        }
    }else{
        $Employee_ID = 0;
        $Branch_ID = 0;
    }
    $is_hr="";
    if(isset($_GET['HRWork']) && $_GET['HRWork']=='true'){
        $is_hr = "&HRWork=true";   
    }
    if(mysqli_query($conn,"delete from tbl_branch_employee where Employee_ID = '$Employee_ID' and Branch_ID = '$Branch_ID'")){
        header("Location: ./assignaccessbranch.php?Employee_ID=$Employee_ID&AssignAccessBranch=AssignAccessBranchThisForm".$is_hr);
    }
    ?>