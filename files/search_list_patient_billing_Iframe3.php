<link rel="stylesheet" href="table.css" media="screen"> 

<?php
    include("./includes/connection.php");
	$temp = 1;
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    }
$filter = '';
	if(isset($_GET['Patient_Number'])){
        $Patient_Number = $_GET['Patient_Number'];   
    }else{
        $Patient_Number = '';
    }
			
	//today function
	$Today_Date = mysqli_query($conn,"SELECT now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	// $age ='';
    }
	//end

if(!empty($Patient_Number)){
	$filter .= " AND pc.Registration_ID = '$Patient_Number'";
}else{
	$filter .= '';
}

if(!empty($Patient_Name)){
	$filter .= " AND pr.Patient_Name LIKE  '%$Patient_Name%'";
}else{
	$filter .= '';
}

	
    echo '<center><table width =100% border=0>';
	
    echo '<tr id="thead"><td style="width:3%; text-align: center;"><b>SN</b></td>
				<td style="width:15%; text-align: center;"><b>PATIENT NAME</b></td>
				<td style="width:6%; text-align: center;"><b>PATIENT NO</b></td>
				<td style="text-align: center;"><b>AGE</b></td>
				<td style="text-align: center;"><b>GENDER</b></td>
				<td style="width:7%;text-align: center;"><b>PHONE NUMBER</b></td>
				<td style="width:20%;text-align: center;"><b>SURGERY NAME</b></td>
				<td style="width:12%;text-align: center;"><b>ORDERED DOCTOR</b></td>
				<td style="text-align: center;"><b>OPERATION DATE TIME</b></td>
				<td style="text-align: center;"><b>CONSENT DATE TIME</b></td>
	 	</tr>';

    $select_Filtered_Donors = mysqli_query($conn, "SELECT pc.Registration_ID, pr.Patient_Name, ilc.Transaction_Date_And_Time, pr.Date_Of_Birth, pr.Gender, pc.consultation_ID, pr.Phone_Number, em.Employee_Name, ilc.Consultant_ID, i.Product_Name, ilc.Service_Date_And_Time, ilc.Payment_Item_Cache_List_ID FROM tbl_item_list_cache ilc, tbl_payment_cache pc, tbl_patient_registration pr, tbl_items i, tbl_employee em WHERE pr.Registration_ID = pc.Registration_ID AND pc.Payment_Cache_ID = ilc.Payment_Cache_ID AND ilc.Check_In_Type = 'Surgery' AND ilc.Status IN ('active','paid') AND i.Item_ID = ilc.Item_ID AND em.Employee_ID = ilc.Consultant_ID $filter ORDER BY ilc.Payment_Item_Cache_List_ID DESC LIMIT 100") or die(mysqli_error($conn));

	
    while($row = mysqli_fetch_array($select_Filtered_Donors)){
	
		//AGE FUNCTION
	//  $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";
	   // if($age == 0){
		
		$date1 = new DateTime($Today);
		$date2 = new DateTime($row['Date_Of_Birth']);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
	
		$Registration_ID = $row['Registration_ID'];
		//check if is available
		$consultation_id = $row['consultation_ID'];
		$Payment_Item_Cache_List_ID = $row['Payment_Item_Cache_List_ID'];
		$Service_Date_And_Time = $row['Service_Date_And_Time'];
        $Product_Name = $row['Product_Name'];

		// die("SELECT Admision_ID FROM tbl_admission WHERE Registration_ID = '$Registration_ID' AND Admision_Status = 'Admitted' AND Bed_Name <> '' ORDER BY Admision_ID DESC LIMIT 1");
		$Admision_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Admision_ID FROM tbl_admission WHERE Registration_ID = '$Registration_ID' AND Admission_Status = 'Admitted' AND Bed_Name <> '' ORDER BY Admision_ID DESC LIMIT 1 "))['Admision_ID'];


		$check = mysqli_query($conn,"SELECT `date`, consent_amputation from tbl_consert_forms_details where Registration_ID = '$Registration_ID' AND consultation_id='$consultation_id' AND Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
		$num = mysqli_num_rows($check);


		while($data = mysqli_fetch_assoc($check)){
			$consent_amputation = $data['consent_amputation'];
			$date = $data['date'];
		}
		if($num > 0){
			if($consent_amputation == 'Agree'){
						echo "<tr style='background: #ff8080; font-color: white'><td id='thead'>".$temp."</td>";
						echo "<td><a href='printrtheater.php?Registration_ID=".$row['Registration_ID']."&consultation_id=".$consultation_id."&Admision_ID=".$Admision_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&Product_Name=".$Product_Name."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
						echo "<td><a href='printrtheater.php?Registration_ID=".$row['Registration_ID']."&consultation_id=".$consultation_id."&Admision_ID=".$Admision_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&Product_Name=".$Product_Name."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
						echo "<td><a href='printrtheater.php?Registration_ID=".$row['Registration_ID']."&consultation_id=".$consultation_id."&Admision_ID=".$Admision_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&Product_Name=".$Product_Name."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none;'>".$age ."</a></td>";
						echo "<td><a href='printrtheater.php?Registration_ID=".$row['Registration_ID']."&consultation_id=".$consultation_id."&Admision_ID=".$Admision_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&Product_Name=".$Product_Name."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none;'>".$row['Gender']."</a></td>";
						echo "<td><a href='printrtheater.php?Registration_ID=".$row['Registration_ID']."&consultation_id=".$consultation_id."&Admision_ID=".$Admision_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&Product_Name=".$Product_Name."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
						echo "<td><a href='printrtheater.php?Registration_ID=".$row['Registration_ID']."&consultation_id=".$consultation_id."&Admision_ID=".$Admision_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&Product_Name=".$Product_Name."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none;'>".$row['Product_Name']."</a></td>";
						echo "<td><a href='printrtheater.php?Registration_ID=".$row['Registration_ID']."&consultation_id=".$consultation_id."&Admision_ID=".$Admision_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&Product_Name=".$Product_Name."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none;'>".$row['Employee_Name']."</a></td>";
						echo "<td><a href='printrtheater.php?Registration_ID=".$row['Registration_ID']."&consultation_id=".$consultation_id."&Admision_ID=".$Admision_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&Product_Name=".$Product_Name."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none;'>".$row['Service_Date_And_Time']."</a></td>";
						echo "<td><a href='printrtheater.php?Registration_ID=".$row['Registration_ID']."&consultation_id=".$consultation_id."&Admision_ID=".$Admision_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&Product_Name=".$Product_Name."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none;'>".$date."</a></td>";
					
				$temp++; 
				echo "</tr>";
			}else{
				echo "<tr style='background: green; font-color: white'><td id='thead'>".$temp."</td>";
				echo "<td><a href='printrtheater.php?Registration_ID=".$row['Registration_ID']."&consultation_id=".$consultation_id."&Admision_ID=".$Admision_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&Product_Name=".$Product_Name."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
				echo "<td><a href='printrtheater.php?Registration_ID=".$row['Registration_ID']."&consultation_id=".$consultation_id."&Admision_ID=".$Admision_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&Product_Name=".$Product_Name."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
				echo "<td><a href='printrtheater.php?Registration_ID=".$row['Registration_ID']."&consultation_id=".$consultation_id."&Admision_ID=".$Admision_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&Product_Name=".$Product_Name."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none;'>".$age ."</a></td>";
				echo "<td><a href='printrtheater.php?Registration_ID=".$row['Registration_ID']."&consultation_id=".$consultation_id."&Admision_ID=".$Admision_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&Product_Name=".$Product_Name."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none;'>".$row['Gender']."</a></td>";
				echo "<td><a href='printrtheater.php?Registration_ID=".$row['Registration_ID']."&consultation_id=".$consultation_id."&Admision_ID=".$Admision_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&Product_Name=".$Product_Name."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
				echo "<td><a href='printrtheater.php?Registration_ID=".$row['Registration_ID']."&consultation_id=".$consultation_id."&Admision_ID=".$Admision_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&Product_Name=".$Product_Name."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none;'>".$row['Product_Name']."</a></td>";
				echo "<td><a href='printrtheater.php?Registration_ID=".$row['Registration_ID']."&consultation_id=".$consultation_id."&Admision_ID=".$Admision_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&Product_Name=".$Product_Name."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none;'>".$row['Employee_Name']."</a></td>";
				echo "<td><a href='printrtheater.php?Registration_ID=".$row['Registration_ID']."&consultation_id=".$consultation_id."&Admision_ID=".$Admision_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&Product_Name=".$Product_Name."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none;'>".$row['Service_Date_And_Time']."</a></td>";
				echo "<td><a href='printrtheater.php?Registration_ID=".$row['Registration_ID']."&consultation_id=".$consultation_id."&Admision_ID=".$Admision_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&Product_Name=".$Product_Name."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none;'>".$date."</a></td>";
			
		$temp++; 
		echo "</tr>";
			}
		}else{
					echo "<tr><td id='thead'>".$temp."</td>";
					echo "<td><a href='theater_concert_form.php?Registration_ID=".$row['Registration_ID']."&consultation_id=".$consultation_id."&Admision_ID=".$Admision_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&Product_Name=".$Product_Name."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
					echo "<td><a href='theater_concert_form.php?Registration_ID=".$row['Registration_ID']."&consultation_id=".$consultation_id."&Admision_ID=".$Admision_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&Product_Name=".$Product_Name."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
					echo "<td><a href='theater_concert_form.php?Registration_ID=".$row['Registration_ID']."&consultation_id=".$consultation_id."&Admision_ID=".$Admision_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&Product_Name=".$Product_Name."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>".$age ."</a></td>";
					echo "<td><a href='theater_concert_form.php?Registration_ID=".$row['Registration_ID']."&consultation_id=".$consultation_id."&Admision_ID=".$Admision_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&Product_Name=".$Product_Name."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
					echo "<td><a href='theater_concert_form.php?Registration_ID=".$row['Registration_ID']."&consultation_id=".$consultation_id."&Admision_ID=".$Admision_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&Product_Name=".$Product_Name."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
					echo "<td><a href='theater_concert_form.php?Registration_ID=".$row['Registration_ID']."&consultation_id=".$consultation_id."&Admision_ID=".$Admision_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&Product_Name=".$Product_Name."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>".$row['Product_Name']."</a></td>";
					echo "<td><a href='theater_concert_form.php?Registration_ID=".$row['Registration_ID']."&consultation_id=".$consultation_id."&Admision_ID=".$Admision_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&Product_Name=".$Product_Name."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>".$row['Employee_Name']."</a></td>";
					echo "<td><a href='theater_concert_form.php?Registration_ID=".$row['Registration_ID']."&consultation_id=".$consultation_id."&Admision_ID=".$Admision_ID."&Payment_Item_Cache_List_ID=".$Payment_Item_Cache_List_ID."&Product_Name=".$Product_Name."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>".$row['Service_Date_And_Time']."</a></td>
					<td>NOT SIGNED</td>";
				
			$temp++; 
			echo "</tr>";
		}
    }   
?>
</table>
</center>
