<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['username'])){
		$username = mysqli_real_escape_string($conn,$_GET['username']);
	}else{
		$username = '';
	}

	if(isset($_GET['oldpassword'])){
		$oldpassword = mysqli_real_escape_string($conn,md5($_GET['oldpassword']));
	}else{
		$oldpassword = '';
	}

	if(isset($_GET['newpassword'])){
		$newpassword = mysqli_real_escape_string($conn,md5($_GET['newpassword']));
	}else{
		$newpassword = '';
	}

	if(isset($_GET['confirmpassword'])){
		$confirmpassword = mysqli_real_escape_string($conn,md5($_GET['confirmpassword']));
	}else{
		$confirmpassword = '';
	}

	$confirmNewPassword = mysqli_real_escape_string($conn,$_GET['newpassword']);
	$passwordLegth = strlen($confirmNewPassword);

	$Select_User_Info = mysqli_query($conn,"select p.Given_Username, p.Given_Password, b.Branch_ID, e.Employee_ID, b.Branch_ID from
									tbl_branches b, tbl_branch_employee be, tbl_employee e, tbl_privileges p, tbl_department dep
                					where b.Branch_ID = be.Branch_ID and
									e.Employee_ID = be.Employee_ID and
									dep.Department_ID = e.Department_ID
									and e.Employee_ID = p.Employee_ID and p.Given_Password = '$oldpassword' and p.Given_Username = '$username'") or die(mysqli_error($conn));
	$Number_Of_Rows_Affected = mysqli_num_rows($Select_User_Info);
	if($Number_Of_Rows_Affected > 0){
        while($row = mysqli_fetch_array($Select_User_Info)){
            $Given_Username = $row['Given_Username'];
		    $Given_Password = $row['Given_Password'];
		    $Employee_ID = $row['Employee_ID'];
            $Branch_ID =$row['Branch_ID'];
        }
    }else{
        $Given_Username = '';
	    $Given_Password = '';
	    $Employee_ID = '';
        $Branch_ID = '';
    }

    if($Number_Of_Rows_Affected > 0 && $oldpassword == $Given_Password && $username = $Given_Username){
    	$sysConfig = mysqli_query($conn,"select minimum_password_length, alphanumeric_password from tbl_system_configuration where Branch_ID='$Branch_ID'") or die(mysqli_error($conn));
        $sysrows = mysqli_fetch_assoc($sysConfig);
        $minpassLength = $sysrows['minimum_password_length'];
        $alphanumeric = $sysrows['alphanumeric_password'];
        
        if(($alphanumeric == 'yes') && (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z]/', $confirmNewPassword))){
            echo "Your Password must be alphanumeric";
		} elseif($minpassLength != 0 && $passwordLegth < $minpassLength) {
            echo "Your password must be at least ".$minpassLength." characters long";
		} else {
            $result = mysqli_query($conn,"update tbl_privileges set Given_Password = '$newpassword', Last_Password_Change = (select now()), Changed_first_login = 'yes' where
					    			Employee_ID = '$Employee_ID' and Given_Username = '$Given_Username' and Given_Password = '$Given_Password'");
            if($result){
            	echo "yes"; //changed successfully
            }
		}
    }else{
    	echo "no"; //Invalid old password or username
    }
?>