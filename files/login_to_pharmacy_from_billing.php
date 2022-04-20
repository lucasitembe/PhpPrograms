<?php
    include("./includes/connection.php");
    $Supervisor_Username = mysqli_real_escape_string($conn,$_POST['Supervisor_Username']);
            $Supervisor_Password = mysqli_real_escape_string($conn,md5($_POST['Supervisor_Password']));
            $Sub_Department_Name = $_POST['Sub_Department_Name'];
            
           
	if($Supervisor_Username != null && $Supervisor_Username != '' && $Supervisor_Password != null && $Supervisor_Password != '' && $Sub_Department_Name != null && $Sub_Department_Name != ''){
	    $query="select * from tbl_branches b, tbl_branch_employee be, tbl_employee e, tbl_privileges p
                where b.branch_id = be.branch_id and e.employee_id = be.employee_id
                and e.employee_id = p.employee_id and p.Given_Username = '{$Supervisor_Username}' and
		p.Given_Password  = '{$Supervisor_Password}' and p.Session_Master_Priveleges = 'yes';
            ";
            //exit($query);
            //DML excution select from..
            $result= mysqli_query($conn,$query);
            $no=mysqli_num_rows($result);
	    
            if($no>0){
                $row=mysqli_fetch_assoc($result);
                @session_start();
                $_SESSION['Pharmacy_Supervisor'] = $row;
                $_SESSION['Pharmacy'] = $Sub_Department_Name;
		
		//get sub department
		$sub_dep_result = mysqli_query($conn,"select Sub_Department_ID, Sub_Department_Name from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name' limit 1") or die(mysqli_error($conn));
		$row = mysqli_fetch_array($sub_dep_result);
		$_SESSION['Pharmacy_Name'] = $row['Sub_Department_Name'];
                $_SESSION['Pharmacy_ID'] = $row['Sub_Department_ID'];
		
		
		//function to audit
//                $Authentication_Date_And_Time=date('Y-m-d H:i:s');
//                authentication($_SESSION['Radiology_Supervisor']['Employee_ID'],$Authentication_Date_And_Time,$Sub_Department_Name);
		//audit($_SESSION['Pharmacy_Supervisor']['Employee_ID'],'Authentication',$Sub_Department_ID,$_SESSION['userinfo']['Employee_ID'],$_SESSION['Pharmacy_Supervisor']['Branch_ID']);
//                header("Location:./pharmacyworks.php?PharmacyWorkPage=PharmacyWorkPageThisPage");
                echo "success";
            }
            else { 
                
                echo "invalid_information";
                
            }
	}else{
            echo "fill_all_fields";
	}