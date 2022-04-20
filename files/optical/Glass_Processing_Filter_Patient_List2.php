<?php
	session_start();
	include("./includes/connection.php");
	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = 0;
	}

	if(isset($_GET['Start_Date'])){
		$Start_Date = $_GET['Start_Date'];
	}else{
		$Start_Date = '';
	}

	if(isset($_GET['End_Date'])){
		$End_Date = $_GET['End_Date'];
	}else{
		$End_Date = '';
	}

	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
		$age ='';
    }

?>
<legend align="right"><b>GLASS PROCESSING ~ OUTPATIENT CASH</b></legend>
    <table width="100%" class="table table-striped table-hover ">
        <thead style="background-color:#bdb5ac">
        <!-- bdb5ac -->
        <!-- e6eded -->
        <!-- a3c0cc -->
            <tr>
                <th style="text-align:left;"><b>SN</b></th>
                <th style="text-align:left;"><b>PATIENT NAME</b></th>
                <th style="text-align:left;"><b>PATIENT NUMBER</b></th>
                <th style="text-align:left;"><b>SPONSOR</b></th>
                <th style="text-align:left;"><b>AGE</b></th>
                <th style="text-align:left;"><b>GENDER</b></th>
                <th style="text-align:left;"><b>PHONE NUMBER</b></th>
                <th style="text-align:left;"><b>LOCATION</b></th>
            </tr>
        </thead>
		<?php
			echo $Title; $temp = 0;
			if(isset($_GET['Patient_Name'])){
				$Patient_Name = $_GET['Patient_Name'];
				if($Start_Date != null && $Start_Date != '' && $End_Date != null && $End_Date != ''){
					if($Sponsor_ID == 0){
						$select = mysqli_query($conn, "SELECT pc.Registration_ID, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID, sp.Guarantor_Name, sd.Sub_Department_Name,
											preg.Patient_Name, pc.Sponsor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number,
											preg.Member_Number, ilc.Transaction_Type from
											tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration preg, tbl_sponsor sp, tbl_sub_department sd where
											pc.payment_cache_id = ilc.payment_cache_id and
											sd.Sub_Department_ID = ilc.Sub_Department_ID and
											preg.registration_id = pc.registration_id and
											sp.Sponsor_ID = preg.Sponsor_ID and
											ilc.status <> 'dispensed' and
											ilc.status <> 'removed' and
											ilc.Check_In_Type = 'Optical' and
											pc.Payment_Date_And_Time between '$Start_Date' and '$End_Date' and
											pc.Billing_Type = 'Outpatient Cash' and
											preg.Patient_Name LIKE '%$Patient_Name%'
											group by pc.payment_cache_id order by pc.Payment_Cache_ID desc limit 100") or die(mysqli_error($conn));
					}else{
						$select = mysqli_query($conn, "select pc.Registration_ID, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID, sp.Guarantor_Name, sd.Sub_Department_Name,
											preg.Patient_Name, pc.Sponsor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number,
											preg.Member_Number, ilc.Transaction_Type from
											tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration preg, tbl_sponsor sp, tbl_sub_department sd where
											pc.payment_cache_id = ilc.payment_cache_id and
											sd.Sub_Department_ID = ilc.Sub_Department_ID and
											preg.registration_id = pc.registration_id and
											sp.Sponsor_ID = preg.Sponsor_ID and
											ilc.status <> 'dispensed' and
											ilc.status <> 'removed' and
											ilc.Check_In_Type = 'Optical' and
											pc.Payment_Date_And_Time between '$Start_Date' and '$End_Date' and
											pc.Billing_Type = 'Outpatient Cash' and
											sp.Sponsor_ID = '$Sponsor_ID' and
											preg.Patient_Name like '%$Patient_Name%'
											group by pc.payment_cache_id order by pc.Payment_Cache_ID desc limit 100") or die(mysqli_error($conn));
					}
				}else{
					if($Sponsor_ID == 0){
						$select =mysqli_query($conn, "SELECT pc.Registration_ID, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID, sp.Guarantor_Name, sd.Sub_Department_Name,
											preg.Patient_Name, pc.Sponsor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number,
											preg.Member_Number, ilc.Transaction_Type from
											tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration preg, tbl_sponsor sp, tbl_sub_department sd where
											pc.payment_cache_id = ilc.payment_cache_id and
											sd.Sub_Department_ID = ilc.Sub_Department_ID and
											preg.registration_id = pc.registration_id and
											sp.Sponsor_ID = preg.Sponsor_ID and
											ilc.status <> 'dispensed' and
											ilc.status <> 'removed' and
											ilc.Check_In_Type = 'Optical' and
											pc.Billing_Type = 'Outpatient Cash' and
											preg.Patient_Name like '%$Patient_Name%'
											group by pc.payment_cache_id order by pc.Payment_Cache_ID desc limit 100") or die(mysqli_error($conn));
					}else{
						$select =mysqli_query($conn, "select pc.Registration_ID, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID, sp.Guarantor_Name, sd.Sub_Department_Name,
											preg.Patient_Name, pc.Sponsor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number,
											preg.Member_Number, ilc.Transaction_Type from
											tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration preg, tbl_sponsor sp, tbl_sub_department sd where
											pc.payment_cache_id = ilc.payment_cache_id and
											sd.Sub_Department_ID = ilc.Sub_Department_ID and
											preg.registration_id = pc.registration_id and
											sp.Sponsor_ID = preg.Sponsor_ID and
											ilc.status <> 'dispensed' and
											ilc.status <> 'removed' and
											ilc.Check_In_Type = 'Optical' and
											pc.Billing_Type = 'Outpatient Cash' and
											sp.Sponsor_ID = '$Sponsor_ID' and
											preg.Patient_Name like '%$Patient_Name%'
											group by pc.payment_cache_id order by pc.Payment_Cache_ID desc limit 100") or die(mysqli_error($conn));
					}
				}
			}else{
				if($Sponsor_ID == 0){
					$select =mysqli_query($conn, "select pc.Registration_ID, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID, sp.Guarantor_Name, sd.Sub_Department_Name,
										preg.Patient_Name, pc.Sponsor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number,
										preg.Member_Number, ilc.Transaction_Type from
										tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration preg, tbl_sponsor sp, tbl_sub_department sd where
										pc.payment_cache_id = ilc.payment_cache_id and
										sd.Sub_Department_ID = ilc.Sub_Department_ID and
										preg.registration_id = pc.registration_id and
										sp.Sponsor_ID = preg.Sponsor_ID and
										ilc.status <> 'dispensed' and
										ilc.status <> 'removed' and
										ilc.Check_In_Type = 'Optical' and
										pc.Payment_Date_And_Time between '$Start_Date' and '$End_Date' and
										pc.Billing_Type = 'Outpatient Cash'
										group by pc.payment_cache_id order by pc.Payment_Cache_ID desc limit 100") or die(mysqli_error($conn));
				}else{
					$select =mysqli_query($conn, "select pc.Registration_ID, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID, sp.Guarantor_Name, sd.Sub_Department_Name,
										preg.Patient_Name, pc.Sponsor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number,
										preg.Member_Number, ilc.Transaction_Type from
										tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration preg, tbl_sponsor sp, tbl_sub_department sd where
										pc.payment_cache_id = ilc.payment_cache_id and
										sd.Sub_Department_ID = ilc.Sub_Department_ID and
										preg.registration_id = pc.registration_id and
										sp.Sponsor_ID = preg.Sponsor_ID and
										ilc.status <> 'dispensed' and
										ilc.status <> 'removed' and
										ilc.Check_In_Type = 'Optical' and
										pc.Payment_Date_And_Time between '$Start_Date' and '$End_Date' and
										pc.Billing_Type = 'Outpatient Cash' and
										sp.Sponsor_ID = '$Sponsor_ID'
										group by pc.payment_cache_id order by pc.Payment_Cache_ID desc limit 100") or die(mysqli_error($conn));
				}
			}
			$num = mysqli_num_rows($select);
			if($num > 0){
				while ($data = mysqli_fetch_array($select)) {
					//calculate patient age
					$date1 = new DateTime($Today);
					$date2 = new DateTime($data['Date_Of_Birth']);
					$diff = $date1 -> diff($date2);
					$age = $diff->y." Years, ";
					$age .= $diff->m." Months, ";
					$age .= $diff->d." Days";

					echo "<tr>";
					echo "<td><a href='glassprocessingpatient.php?Registration_ID=".$data['Registration_ID']."&Payment_Cache_ID=".$data['Payment_Cache_ID']."&GlassProcessingPatient=GlassProcessingPatientThisPage' style='text-decoration: none;' target='_parent'>".++$temp."</a></td>";
					echo "<td><a href='glassprocessingpatient.php?Registration_ID=".$data['Registration_ID']."&Payment_Cache_ID=".$data['Payment_Cache_ID']."&GlassProcessingPatient=GlassProcessingPatientThisPage' style='text-decoration: none;' target='_parent'>".ucwords(strtolower($data['Patient_Name']))."</a></td>";
					echo "<td><a href='glassprocessingpatient.php?Registration_ID=".$data['Registration_ID']."&Payment_Cache_ID=".$data['Payment_Cache_ID']."&GlassProcessingPatient=GlassProcessingPatientThisPage' style='text-decoration: none;' target='_parent'>".$data['Registration_ID']."</a></td>";
					echo "<td><a href='glassprocessingpatient.php?Registration_ID=".$data['Registration_ID']."&Payment_Cache_ID=".$data['Payment_Cache_ID']."&GlassProcessingPatient=GlassProcessingPatientThisPage' style='text-decoration: none;' target='_parent'>".$data['Guarantor_Name']."</a></td>";
					echo "<td><a href='glassprocessingpatient.php?Registration_ID=".$data['Registration_ID']."&Payment_Cache_ID=".$data['Payment_Cache_ID']."&GlassProcessingPatient=GlassProcessingPatientThisPage' style='text-decoration: none;' target='_parent'>".$age."</a></td>";
					echo "<td><a href='glassprocessingpatient.php?Registration_ID=".$data['Registration_ID']."&Payment_Cache_ID=".$data['Payment_Cache_ID']."&GlassProcessingPatient=GlassProcessingPatientThisPage' style='text-decoration: none;' target='_parent'>".$data['Gender']."</a></td>";
					echo "<td><a href='glassprocessingpatient.php?Registration_ID=".$data['Registration_ID']."&Payment_Cache_ID=".$data['Payment_Cache_ID']."&GlassProcessingPatient=GlassProcessingPatientThisPage' style='text-decoration: none;' target='_parent'>".$data['Phone_Number']."</a></td>";
					echo "<td><a href='glassprocessingpatient.php?Registration_ID=".$data['Registration_ID']."&Payment_Cache_ID=".$data['Payment_Cache_ID']."&GlassProcessingPatient=GlassProcessingPatientThisPage' style='text-decoration: none;' target='_parent'>".$data['Sub_Department_Name']."</a></td>";
					echo "</tr>";
				}
			}
		?>
	</table>