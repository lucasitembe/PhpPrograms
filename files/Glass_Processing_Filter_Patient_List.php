<?php
	session_start();
	include("./includes/connection.php");
	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = 0;
	}

	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
		$age ='';
    }
	$Title = '<tr><td colspan="8"><hr></td></tr>
				<tr>
					<td width="5%"><b>SN</b></td>
					<td><b>PATIENT NAME</b></td>
					<td width="10%"><b>PATIENT NUMBER</b></td>
					<td width="12%"><b>SPONSOR</b></td>
					<td width="13%"><b>AGE</b></td>
					<td width="7%"><b>GENDER</b></td>
					<td width="12%"><b>PHONE NUMBER</b></td>
					<td width="10%"><b>LOCATION</b></td>
				</tr>
				<tr><td colspan="8"><hr></td></tr>';
?>
<legend><b>GLASS PROCESSING ~ OUTPATIENT CASH</b></legend>
	<table width="100%">
		<?php
			echo $Title; $temp = 0;
			if($Sponsor_ID == 0){
				$select = mysql_Query("select pc.Registration_ID, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID, sp.Guarantor_Name, sd.Sub_Department_Name,
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
									pc.Billing_Type = 'Outpatient Cash'
									group by pc.payment_cache_id order by pc.Payment_Cache_ID desc limit 100") or die(mysqli_error($conn));
			}else{
				$select = mysql_Query("select pc.Registration_ID, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID, sp.Guarantor_Name, sd.Sub_Department_Name,
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
									sp.Sponsor_ID = '$Sponsor_ID'
									group by pc.payment_cache_id order by pc.Payment_Cache_ID desc limit 100") or die(mysqli_error($conn));
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