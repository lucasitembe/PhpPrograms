<?php
	session_start();
	include("./includes/connection.php");
	$temp = 0;
	if(!isset($_SESSION['userinfo'])){
		@session_destroy();
		header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
		if(isset($_SESSION['userinfo']['Revenue_Center_Works'])){
		    if($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes'){
			header("Location: ./index.php?InvalidPrivilege=yes");
		    }else{
			@session_start();
			if(!isset($_SESSION['supervisor'])){ 
			    header("Location: ./supervisorauthentication.php?InvalidSupervisorAuthentication=yes");
			}
		    }
		}else{
		    header("Location: ./index.php?InvalidPrivilege=yes");
		}
    }else{
		@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }

	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = 0;
	}

	if(isset($_GET['Patient_Name'])){
		$Patient_Name = $_GET['Patient_Name'];
	}else{
		$Patient_Name = 0;
	}

	//get branch id
    if(isset($_SESSION['userinfo']['Branch_ID'])){
    	$Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
    	$Branch_ID = 0;
    }
    $Title = '<tr><td colspan="8"><hr></td></tr>
    			<tr>
		    	<td width="5%"><b>SN</b></td>
		    	<td><b>PATIENT NAME</b></td>
		    	<td width="10%"><b>PATIENT#</b></td>
				<td width="15%"><b>SPONSOR</b></td>
				<td width="14%"><b>DATE OF BIRTH</b></td>
				<td width="10%"><b>GENDER</b></td>
				<td width="10%"><b>PHONE NUMBER</b></td>
				<td width="12%"><b>MEMBER NUMBER</b></td>
			</tr>
			<tr><td colspan="8"><hr></td></tr>';
?>

<legend align="right"><b>DIRECT CASH INPATIENT ~ PATIENTS LIST</b></legend>
	<table width = "100%">
<?php
	echo $Title;
	if($Sponsor_ID == 0){
		$select = mysqli_query($conn,"select * from tbl_patient_registration pr , tbl_admission ad,tbl_check_in ci, tbl_sponsor sp where
												pr.Registration_ID = ad.Registration_ID  and
												sp.Sponsor_ID = pr.Sponsor_ID and
												ci.Registration_ID=pr.Registration_ID AND
												ci.branch_id = '$Branch_ID' and
												ci.Check_In_Status IN ('saved','pending') and
												pr.Patient_Name like '%$Patient_Name%' and
												ad.admission_status IN ('Admitted','pending') GROUP BY pr.Registration_ID limit 200") or die(mysqli_error($conn));
	}else{
		$select = mysqli_query($conn,"select * from tbl_patient_registration pr , tbl_admission ad,tbl_check_in ci, tbl_sponsor sp where
												pr.Registration_ID = ad.Registration_ID  and
												sp.Sponsor_ID = pr.Sponsor_ID and
												ci.Registration_ID=pr.Registration_ID AND
												pr.Sponsor_ID = '$Sponsor_ID' and
												ci.branch_id = '$Branch_ID' and
												ci.Check_In_Status IN ('saved','pending') and
												pr.Patient_Name like '%$Patient_Name%' and
												ad.admission_status IN ('Admitted','pending') GROUP BY pr.Registration_ID limit 200") or die(mysqli_error($conn));
	}

	while($row = mysqli_fetch_array($select)){
		echo "<tr><td id='thead'>".++$temp."</td><td><a href='inpatientbillingdirectcash.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
		echo "<td><a href='inpatientbillingdirectcash.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
		echo "<td><a href='inpatientbillingdirectcash.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
		echo "<td><a href='inpatientbillingdirectcash.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Date_Of_Birth']."</a></td>";
		echo "<td><a href='inpatientbillingdirectcash.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
		echo "<td><a href='inpatientbillingdirectcash.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
		echo "<td><a href='inpatientbillingdirectcash.php?Registration_ID=".$row['Registration_ID']."&NR=true&CP=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td></tr>";
		if(($temp%21) == 0){
    		echo $Title;
    	}
    }
?>
