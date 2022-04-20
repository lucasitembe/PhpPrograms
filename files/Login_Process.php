<?php
	

	session_start();
    session_destroy();
	include("./includes/connection.php");
	
    require_once("./audittrail.php");
	// die("imefika bhana...ntaipa coka");
    if(isset($_GET['username'])){
    	$username = mysqli_real_escape_string($conn,trim($_GET['username']));
    }else{
    	$username = '';
    }

    
    if(isset($_GET['password'])){
    	$password = mysqli_real_escape_string($conn,trim(MD5($_GET['password'])));
    }else{
    	$password = '';
    }

    
    if(isset($_GET['Branch_Name'])){
    	$branch = mysqli_real_escape_string($conn,$_GET['Branch_Name']);
    }else{
    	$branch = '';
    }

    if($username != '' && $username != null && $password != null && $password != ''){
        $result = mysqli_query($conn,"SELECT * from tbl_branches b, tbl_branch_employee be, tbl_employee e, tbl_privileges p, tbl_department dep
			                	where b.branch_id = be.branch_id and
								e.employee_id = be.employee_id and
								dep.department_id = e.department_id
								and e.employee_id = p.employee_id and p.Given_Username = '{$username}' and
								p.Given_Password  = '{$password}' and b.Branch_Name = '{$branch}';") or die(mysqli_error($conn));
        
        //DML excution select from..
        $no=mysqli_num_rows($result);
		if($no > 0){
			$row=mysqli_fetch_assoc($result);
	    	@session_start();	    
            $_SESSION['userinfo']=$row;
	    
		    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
		    //select system configuration
		    $select_config = mysqli_query($conn,"SELECT * from tbl_system_configuration where Branch_ID = '$Branch_ID'") or die(mysqli_error($conn));
		    $row2 = mysqli_fetch_assoc($select_config);
		    $_SESSION['systeminfo'] = $row2;
            
			$hospcons = mysqli_query($conn,"select * from tbl_hospital_consult_type where Branch_ID='$Branch_ID'") or die(mysqli_error($conn));
	      	$hosp_cons_rows = mysqli_fetch_assoc($hospcons);
              
			$_SESSION['hospitalConsultaioninfo'] = $hosp_cons_rows;
               
			$hospcurrency=  mysqli_query($conn,"select c.currency_id,currency_name,currency_code,currency_symbol,employee_id,date_modified FROM tbl_currency c INNER JOIN tbl_system_configuration s ON c.currency_id=s.currency_id WHERE Branch_ID = '$Branch_ID'") or die(mysqli_error($conn));
			$hosp_currency_rows= mysqli_fetch_assoc($hospcurrency);
              
			$_SESSION['hospcurrency'] = $hosp_currency_rows;

			//selecting all cinfig values values from tbl_config
				$configResult = mysqli_query($conn,"SELECT * FROM tbl_config") or die(mysqli_error($conn));

				while($data = mysqli_fetch_assoc($configResult)){
					$configname = $data['configname'];
					$configvalue = $data['configvalue'];
					$_SESSION['configData'][$configname] = strtolower($configvalue);
				}
	    
	    	$get_client_ip = get_client_ip();
	    	$get_mac_address='';//get_mac_address();
	    	$hostname =get_client_ip();//get_host_name(); //gethostbyaddr($_SERVER['REMOTE_ADDR']);

	    	$_SESSION['NO_SHOW_SESSION_UPDATE'] = "No";
	    
	    	if(strtolower($_SESSION['userinfo']['Account_Status']) != 'inactive'){
				//process successfully
				$emp_id=$_SESSION['userinfo']['Employee_ID'];
				mysqli_query($conn,"INSERT INTO tbl_attendance (employee_id, check_in, check_out) VALUES($emp_id,(SELECT NOW()),'')");
			
				# Audit System Audit
				include 'audit_trail_file.php';
				$Audit = new Audit_Trail($emp_id,"");
				$Audit->perfomAuditLogin();
				
				echo '100'; //Successfully
	    	}else{
				//account blocked
				session_destroy();
				echo "200"; //Account blocked
	    	}
        } else {
        	//invalid information
        	echo "300";
        }
    }
?>