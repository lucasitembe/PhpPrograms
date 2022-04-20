<?php
	session_start();
    include_once("./includes/connection.php");
    include_once("./functions/employee.php");
	$Control = 'no';

	if(isset($_GET['Supervisor_Name'])){
        $Supervisor_Username = mysqli_real_escape_string($conn,$_GET['Supervisor_Name']);
	}else{
		$Supervisor_Name = '';
	}

	if(isset($_GET['Supervisor_Password'])){
        $Supervisor_Password = mysqli_real_escape_string($conn,md5($_GET['Supervisor_Password']));
	}else{
		$Supervisor_Password = '';
	}

	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}

	/*$select = mysqli_query($conn,"select e.Employee_ID from tbl_branches b, tbl_branch_employee be, tbl_employee e, tbl_privileges p
							where b.branch_id = be.branch_id and
							e.employee_id = be.employee_id and
							e.employee_id = p.employee_id and
							p.Given_Username = '$Supervisor_Name' and
							p.Given_Password  = '$Supervisor_Password' and
							p.Session_Master_Priveleges = 'yes'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		$Control = 'yes';
	}*/
    if (Is_Logged_In_User($Supervisor_Username, $Supervisor_Password)) {
        $Control = 'yes';
    }
	echo $Control;
?>