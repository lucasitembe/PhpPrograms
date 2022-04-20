<?php
	session_start();
	include("./includes/connection.php");

    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }

    if(isset($_GET['Clinic_ID'])){
    	$Clinic_ID = $_GET['Clinic_ID'];
    }else{
    	$Clinic_ID = 'All';
    }

	if(isset($_GET['Patient_Name'])){
		$Patient_Name = $_GET['Patient_Name'];
	}else{
		$Patient_Name = '';
	}

	if(isset($_GET['Date_From'])){
		$Date_From = $_GET['Date_From'];
	}else{
		$Date_From = '';
	}

	if(isset($_GET['Date_To'])){
		$Date_To = $_GET['Date_To'];
	}else{
		$Date_To = '';
	}

	if(isset($_GET['Patient_Number'])){
		$Patient_Number = $_GET['Patient_Number'];
	}else{
		$Patient_Number = '';
	}

	//get doctor's clinics
	$C_Value = '';
	if($Clinic_ID == 'All'){
		$select = mysqli_query($conn,"select Clinic_ID from tbl_clinic_employee where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
	}else{
		$select = mysqli_query($conn,"select Clinic_ID from tbl_clinic_employee where Employee_ID = '$Employee_ID' and Clinic_ID = '$Clinic_ID'") or die(mysqli_error($conn));
	}
	
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$C_Value .= ','.$data['Clinic_ID'];
		}
	}
	$Filter_Value = substr($C_Value, 1);

    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date; 
    }
	if(isset($_GET['Patient_Number']) && $_GET['Patient_Number'] != null && $_GET['Patient_Number'] != ''){
		$sql = mysqli_query($conn,"select pr.Registration_ID, pr.Gender, pr.Patient_Name, pr.Phone_Number, pr.Member_Number, pr.Date_Of_Birth, sp.Guarantor_Name from
								tbl_patient_payments pp, tbl_patient_registration pr, tbl_sponsor sp where
								sp.Sponsor_ID = pr.Sponsor_ID and
	                    		pp.Registration_ID = pr.Registration_ID and
	                    		pr.Registration_ID = '$Patient_Number' and
	                    		pp.Patient_Payment_ID") or die(mysqli_error($conn));
	}else{
		$sql = "select pr.Registration_ID, pr.Gender, pr.Patient_Name, pr.Phone_Number, pr.Member_Number, pr.Date_Of_Birth, sp.Guarantor_Name from
								tbl_patient_payments pp, tbl_patient_registration pr, tbl_sponsor sp where
								sp.Sponsor_ID = pr.Sponsor_ID and
	                    		pp.Registration_ID = pr.Registration_ID and
	                    		pr.Patient_Name like '%$Patient_Name%' and
	                    		pp.Patient_Payment_ID";
	}

?>

<legend align="right"><b>CLINIC PATIENT LIST</b></legend>
    <table width="100%">
		<tr id='thead'><td style='width:5%;'><b>SN</b></td><td><b>PATIENT NAME</b></td>
			<td><b>SPONSOR</b></td>
			<td><b>AGE</b></td>
			<td><b>GENDER</b></td>
			<td><b>PHONE NUMBER</b></td>
			<td><b>MEMBER NUMBER</b></td>
			<td><b>TRANS DATE</b></td>
			<td><b>ACTION</b></td>
		</tr>
<?php
		if(isset($_SESSION['hospitalConsultaioninfo']['consultation_Type'])){
			$hospitalConsultType = $_SESSION['hospitalConsultaioninfo']['consultation_Type'];
		}else{
			$hospitalConsultType = '';
		}
		
	    
	    $result = mysqli_query($conn,"select Transaction_Date_And_Time, Patient_Payment_Item_List_ID, Patient_Payment_ID from
	    						tbl_patient_payment_item_list where
	    						Consultant_ID in ($Filter_Value) and
	    						Process_Status= 'not served' and
	    						Transaction_Date_And_Time between '$Date_From' and '$Date_To' and
	    						Patient_Direction = 'Direct To Clinic'
	    						GROUP BY Patient_Payment_ID ORDER BY Transaction_Date_And_Time limit 200") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($result);
	    $n = 0;
	    if($num > 0){
		    if($num > 0){
		    	while ($data = mysqli_fetch_array($result)) {
		    		$Patient_Payment_ID = $data['Patient_Payment_ID'];

		    		//get patient details
		    		$select = mysqli_query($conn,$sql." = '$Patient_Payment_ID'") or die(mysqli_error($conn));
		    		$no = mysqli_num_rows($select);
		    		if($no > 0){
			    		while ($row = mysqli_fetch_array($select)) {

			    			$date1 = new DateTime($Today);
							$date2 = new DateTime($row['Date_Of_Birth']);
							$diff = $date1 -> diff($date2);
							$age = $diff->y." Years, ";
							$age .= $diff->m." Months, ";
							$age .= $diff->d." Days";

							echo "<tr><td id='thead'>".++$n."</td><td><a href='doctorspageoutpatientwork.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$data['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$data['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
					        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$data['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$data['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
					        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$data['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$data['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
					        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$data['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$data['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
					        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$data['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$data['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
					        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$data['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$data['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td>";
					        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$data['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$data['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>".$data['Transaction_Date_And_Time']."</a></td>";
			    		}
			    	}
		    	}
		    }
	    }
?>
</table>