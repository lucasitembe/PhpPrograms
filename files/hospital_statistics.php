<?php
include("./includes/connection.php");
$fromDate=mysqli_real_escape_string($conn,$_POST['fromDate']);
$toDate=mysqli_real_escape_string($conn,$_POST['toDate']);
$Ward_ID=mysqli_real_escape_string($conn,$_POST['Ward_ID']);
//die($Ward_ID);
$filter_wards=" ";
$filter_wards_admission=" ";
if($Ward_ID !='all'){
	$filter_wards=" WHERE hw.Hospital_Ward_ID = $Ward_ID ";
	$filter_wards_admission=" hw.Hospital_Ward_ID = $Ward_ID AND ";
}
	echo "<center>";
	echo "<table width='80%' style='background-color:#fff;font-size:16px;'>";
	echo "<tr><td><b>Indicator</b></td><td><b>Male</b></td><td><b>Female</b></td><td><b>Total</b></td></tr>";
	/*while ( <= 10) {
		# code...
	}*/
	//beds statistics
	$male_beds=0;
	$female_beds=0;
	$both_beds=0;
	$select_bed_number=mysqli_query($conn,"SELECT ward_nature,SUM(Number_of_Beds) AS bed_number FROM tbl_hospital_ward hw  $filter_wards GROUP BY ward_nature");
	while($beds_info=mysqli_fetch_assoc($select_bed_number)){
		if($beds_info['ward_nature'] === 'Male'){
			$male_beds=$beds_info['bed_number'];
		}
		elseif($beds_info['ward_nature'] === 'Female'){
			$female_beds=$beds_info['bed_number'];
		}elseif ($beds_info['ward_nature'] === 'Both') {
			$both_beds=$beds_info['bed_number'];
		}

	}
	$male_beds+=$both_beds;
	$female_beds+=$both_beds;
	$total_beds=$male_beds+$female_beds-$both_beds;

	/***bed days****/
	$diff=(new DateTime($toDate))->diff(new DateTime($fromDate));
	$number_of_days=$diff->format("%a");
	$male_bed_days=$number_of_days*$male_beds;
	$female_bed_days=$number_of_days*$female_beds;
	$total_bed_days=$number_of_days*$total_beds;


	/*****************admission statistics  ****************/
	$total_admission=0;
	$male_admission=0;
	$female_admission=0;
	$select_admission=mysqli_query($conn,"SELECT pr.Gender,COUNT(ad.admision_ID) AS counts FROM tbl_admission ad, tbl_patient_registration pr, tbl_hospital_ward hw WHERE pr.Registration_ID=ad.Registration_ID  AND pr.Date_Of_Birth !='0000-00-00' AND hw.Hospital_Ward_ID=ad.Hospital_Ward_ID AND $filter_wards_admission ad.Admission_Date_Time BETWEEN '$fromDate' AND '$toDate' GROUP BY pr.Gender
");
	while($admission_results=mysqli_fetch_assoc($select_admission)){
		extract($admission_results);
		if($Gender =='Male'){
			$male_admission=$counts;
		}else if($Gender =='Female'){
			$female_admission=$counts;
		}
	}
	// daily admission
	$male_daily_admission=round($male_admission/$number_of_days,0,PHP_ROUND_HALF_UP);
	$female_daily_admission=round($female_admission/$number_of_days,0,PHP_ROUND_HALF_UP);
	$total_daily_admission=round(($male_admission+$female_admission)/$number_of_days,0,PHP_ROUND_HALF_UP);

	//daily discharge
	$male_discharge=0;
	$female_discharge=0;
	$total_discharge=0;
	$male_daily_discharge=0;
	$female_daily_discharge=0;
	$total_daily_discharge=0;
	$select_discharge=mysqli_query($conn,"SELECT pr.Gender,COUNT(Admision_ID) counts FROM tbl_admission ad, tbl_patient_registration pr, tbl_hospital_ward hw WHERE pr.Registration_ID=ad.Registration_ID  AND pr.Date_Of_Birth !='0000-00-00' AND ad.Admission_Status = 'Discharged' AND hw.Hospital_Ward_ID=ad.Hospital_Ward_ID AND $filter_wards_admission Discharge_Date_Time BETWEEN '$fromDate' AND '$toDate'  GROUP BY pr.Gender");
	while($discharge_results=mysqli_fetch_assoc($select_discharge)){
		extract($discharge_results);
		if($Gender == 'Male'){
			$male_discharge=$counts;
		}else if ($Gender == 'Female') {
			$female_discharge=$counts;
		}
	}
	$male_daily_discharge=round($male_discharge/$number_of_days,0,PHP_ROUND_HALF_UP);
	$female_daily_discharge=round($female_discharge/$number_of_days,0,PHP_ROUND_HALF_UP);
	$total_daily_discharge=round(($male_discharge+$female_discharge)/$number_of_days,0,PHP_ROUND_HALF_UP);

	// alive discharge
	$male_alive=0;
	$female_alive=0;
	$select_alive=mysqli_query($conn,"SELECT pr.Gender,COUNT(Admision_ID) counts FROM tbl_admission ad, tbl_discharge_reason dr, tbl_patient_registration pr, tbl_hospital_ward hw WHERE pr.Registration_ID=ad.Registration_ID  AND pr.Date_Of_Birth !='0000-00-00' AND dr.Discharge_Reason_ID=ad.Discharge_Reason_ID AND dr.Discharge_Reason = 'Normal' AND hw.Hospital_Ward_ID=ad.Hospital_Ward_ID AND $filter_wards_admission  Discharge_Date_Time BETWEEN '$fromDate' AND '$toDate'  GROUP BY pr.Gender");
	while($alive_results=mysqli_fetch_assoc($select_alive)){
		extract($alive_results);
		if($Gender == 'Male'){
			$male_alive=$counts;
		}else if ($Gender == 'Female') {
			$female_alive=$counts;
		}
	}
	//deaths
	$male_deaths=0;
	$female_deaths=0;
	$select_deaths=mysqli_query($conn,"SELECT pr.Gender,COUNT(Admision_ID) counts FROM tbl_admission ad, tbl_discharge_reason dr, tbl_patient_registration pr, tbl_hospital_ward hw WHERE pr.Registration_ID=ad.Registration_ID  AND pr.Date_Of_Birth !='0000-00-00' AND dr.Discharge_Reason_ID=ad.Discharge_Reason_ID AND dr.Discharge_Reason = 'Death' AND hw.Hospital_Ward_ID=ad.Hospital_Ward_ID AND $filter_wards_admission  Discharge_Date_Time BETWEEN '$fromDate' AND '$toDate'  GROUP BY pr.Gender");
	while($deaths_results=mysqli_fetch_assoc($select_deaths)){
		extract($deaths_results);
		if($Gender == 'Male'){
			$male_deaths=$counts;
		}else if ($Gender == 'Female') {
			$female_deaths=$counts;
		}
	}

	//abscondee
	$male_abscondee=0;
	$female_abscondee=0;
	$select_abscondee=mysqli_query($conn,"SELECT pr.Gender,COUNT(Admision_ID) counts FROM tbl_admission ad, tbl_discharge_reason dr, tbl_patient_registration pr, tbl_hospital_ward hw WHERE pr.Registration_ID=ad.Registration_ID  AND pr.Date_Of_Birth !='0000-00-00' AND dr.Discharge_Reason_ID=ad.Discharge_Reason_ID AND dr.Discharge_Reason IN('Absconded','Escape') AND hw.Hospital_Ward_ID=ad.Hospital_Ward_ID AND $filter_wards_admission  Discharge_Date_Time BETWEEN '$fromDate' AND '$toDate'  GROUP BY pr.Gender");
	while($abscondee_results=mysqli_fetch_assoc($select_abscondee)){
		extract($abscondee_results);
		if($Gender == 'Male'){
			$male_abscondee=$counts;
		}else if ($Gender == 'Female') {
			$female_abscondee=$counts;
		}
	}

	//transfer in
	$male_transfer_in=0;
	$female_transfer_in=0;
	$select_transfer_in=mysqli_query($conn,"SELECT pr.Gender,COUNT(Admision_ID) counts FROM tbl_admission ad, tbl_discharge_reason dr, tbl_patient_registration pr, tbl_hospital_ward hw WHERE pr.Registration_ID=ad.Registration_ID  AND pr.Date_Of_Birth !='0000-00-00' AND dr.Discharge_Reason_ID=ad.Discharge_Reason_ID AND dr.Discharge_Reason ='transfer in' AND hw.Hospital_Ward_ID=ad.Hospital_Ward_ID AND $filter_wards_admission  Discharge_Date_Time BETWEEN '$fromDate' AND '$toDate'  GROUP BY pr.Gender");
	while($transfer_in_results=mysqli_fetch_assoc($select_transfer_in)){
		extract($transfer_in_results);
		if($Gender == 'Male'){
			$male_transfer_in=$counts;
		}else if ($Gender == 'Female') {
			$female_transfer_in=$counts;
		}
	}
	//tranfer out
	$male_transfer_out=0;
	$female_transfer_out=0;
	$select_transfer_out=mysqli_query($conn,"SELECT pr.Gender,COUNT(Admision_ID) counts FROM tbl_admission ad, tbl_discharge_reason dr, tbl_patient_registration pr, tbl_hospital_ward hw WHERE pr.Registration_ID=ad.Registration_ID  AND pr.Date_Of_Birth !='0000-00-00' AND dr.Discharge_Reason_ID=ad.Discharge_Reason_ID AND dr.Discharge_Reason ='transfer out' AND hw.Hospital_Ward_ID=ad.Hospital_Ward_ID AND $filter_wards_admission  Discharge_Date_Time BETWEEN '$fromDate' AND '$toDate'  GROUP BY pr.Gender");
	while($transfer_out_results=mysqli_fetch_assoc($select_transfer_out)){
		extract($transfer_out_results);
		if($Gender == 'Male'){
			$male_transfer_out=$counts;
		}else if ($Gender == 'Female') {
			$female_transfer_out=$counts;
		}
	}
	//no of inpatient days (occupied beds days)
	$curr_male=0;
	$curr_female=0;
	$select_inpatients=mysqli_query($conn,"SELECT pr.Gender,COUNT(ad.admision_ID) AS counts FROM tbl_admission ad, tbl_patient_registration pr, tbl_hospital_ward hw WHERE pr.Registration_ID=ad.Registration_ID  AND pr.Date_Of_Birth !='0000-00-00' AND hw.Hospital_Ward_ID=ad.Hospital_Ward_ID AND $filter_wards_admission ad.Admission_Date_Time < '$fromDate'  GROUP BY pr.Gender");

	while ($row=mysqli_fetch_assoc($select_inpatients)) {
		extract($row);
		if($Gender =='Male'){
			$curr_male=$counts;
		}else if($Gender =='Female'){
			$curr_female=$counts;
		}
	}

	$male_occupied_beds_days=($curr_male+$male_admission+$male_transfer_in)-($male_alive+$male_deaths+$male_transfer_out+ $male_abscondee);
	$female_occupied_beds_days=($curr_female+$female_admission+$female_transfer_in)-($female_alive+$female_deaths+$female_transfer_out+$female_abscondee);
	//$total_occupied_beds_days=($curr_male+$curr_female+$male_admission+$female_admission)-($male_alive+$male_deaths+$female_alive+$female_deaths);
	$total_occupied_beds_days=$male_occupied_beds_days+$female_occupied_beds_days;



	//bed occupancy rate
	$male_occupied_beds=0;
	$female_occupied_beds=0;
	$total_occupied_beds=0;

	$percentage_male_beds_occupancy=0;
	$percentage_female_beds_occupancy=0;
	$percentage_total_beds_occupancy=0;

	// $select_occupied_beds=mysqli_query($conn,"SELECT pr.Gender, COUNT(ad.Bed_Name) AS occupied_beds FROM tbl_admission ad, tbl_patient_registration pr, tbl_hospital_ward hw WHERE ad.Registration_ID = pr.Registration_ID  AND pr.Date_Of_Birth !='0000-00-00' AND ad.Admission_Status = 'Admitted' AND ad.Bed_Name !='' AND hw.Hospital_Ward_ID=ad.Hospital_Ward_ID AND $filter_wards_admission ad.Admission_Date_Time BETWEEN '$fromDate' AND '$toDate' GROUP BY pr.Gender");
	// while ($occupied_beds_results=mysqli_fetch_assoc($select_occupied_beds)) {
	// 	extract($occupied_beds_results);
	// 	if($Gender == 'Male'){
	// 		$male_occupied_beds = $occupied_beds;
	// 	}elseif ($Gender == 'Female') {
	// 		$female_occupied_beds = $occupied_beds;
	// 	}
	// 	$total_occupied_beds+=$occupied_beds;
	// }
	$percentage_male_beds_occupancy=round(($male_occupied_beds_days/$male_bed_days)*100,1,PHP_ROUND_HALF_UP);
	$percentage_female_beds_occupancy=round(($female_occupied_beds_days/$female_bed_days)*100,1,PHP_ROUND_HALF_UP);
	$percentage_total_beds_occupancy=round(($total_occupied_beds_days/$total_bed_days)*100,1,PHP_ROUND_HALF_UP);


		//average length of stay (ALOS)
	// 	$male_alos=0;
	// 	$female_alos=0;
	// 	$total_alos=0;
	// 	$total_counts=0;
	// 	$total_hours=0;
	// 	$male_number=0;
	// 	$female_number=0;
	// 	$select_alos=mysqli_query($conn,"SELECT pr.Gender, count(pr.Gender) AS counts, SUM(timestampdiff(HOUR,`Admission_Date_Time`,`Discharge_Date_Time`)) AS stay_length FROM tbl_admission ad, tbl_patient_registration pr, tbl_hospital_ward hw WHERE pr.Registration_ID=ad.Registration_ID  AND pr.Date_Of_Birth !='0000-00-00 00:00:00' AND ad.Admission_Status != 'Admitted' AND hw.Hospital_Ward_ID=ad.Hospital_Ward_ID AND $filter_wards_admission Discharge_Date_Time BETWEEN '$fromDate' AND '$toDate' GROUP BY pr.Gender
	// ");
	// 	while ($alos_results=mysqli_fetch_assoc($select_alos)) {
	// 		extract($alos_results);
	// 		if($Gender == 'Male'){
	// 			$male_number=$counts;
	// 			if($counts > 0){
	// 				//$male_alos=round((($stay_length/24)/$male_number),1,PHP_ROUND_HALF_UP);
	// 			}
	// 		}else if ($Gender == 'Female') {
	// 			$female_number=$counts;
	// 			if($counts > 0){
	// 				$female_alos=round((($stay_length/24)/$female_number),1,PHP_ROUND_HALF_UP);
	// 			}
	// 		}
	// 		$total_counts+=$counts;
	// 		$total_hours+=$stay_length;
	// 	}
	// 	if($total_counts > 0){
	// 		$total_alos=round((($total_hours/24)/$total_counts),1,PHP_ROUND_HALF_UP);
	//
	// 	}

 // new calculation for ALOS
		$male_alos=round((($male_occupied_beds_days)/($male_alive+$male_deaths)),1,PHP_ROUND_HALF_UP);

		$female_alos=round((($female_occupied_beds_days)/($female_alive+$female_deaths)),1,PHP_ROUND_HALF_UP);

		$total_alos=round((($total_occupied_beds_days)/($male_alive+$male_deaths+$female_alive+$female_deaths)),1,PHP_ROUND_HALF_UP);



	// turnover interval (TOI)
	$male_toi=($male_bed_days - $male_occupied_beds_days)/($male_alive+$male_deaths);
	$female_toi=($female_bed_days - $female_occupied_beds_days)/($female_alive+$female_deaths);
	$total_toi=($total_bed_days - $total_occupied_beds_days)/($male_alive+$female_alive+$male_deaths+$female_deaths);
	$male_toi=round($male_toi,1,PHP_ROUND_HALF_UP);
	$female_toi=round($female_toi,1,PHP_ROUND_HALF_UP);
	$total_toi=round($total_toi,1,PHP_ROUND_HALF_UP);

	//turn over per beds
	$male_bed_turn_over = round((($male_alive+$male_deaths)/$male_beds),1,PHP_ROUND_HALF_UP);
	$female_bed_turn_over = round((($female_alive+$female_deaths)/$female_beds),1,PHP_ROUND_HALF_UP);
	$total_bed_turn_over = round((($male_alive+$female_alive+$male_deaths+$female_deaths)/$total_beds),1,PHP_ROUND_HALF_UP);
	//die('see '.$total_beds);
	echo "<tr><td>No. of beds</td><td>{$male_beds}</td><td>{$female_beds}</td><td>{$total_beds}</td></tr>";
	echo "<tr><td>Total number of inpatient days</td><td>{$male_occupied_beds_days}</td><td>{$female_occupied_beds_days}</td><td>{$total_occupied_beds_days}</td></tr>";
	echo "<tr><td>Total Admissions</td><td>{$male_admission}</td><td>{$female_admission}</td><td>".($male_admission+$female_admission)."</td></tr>";
	echo "<tr><td>Discharged live</td><td>{$male_alive}</td><td>{$female_alive}</td><td>".($male_alive+$female_alive)."</td></tr>";
	echo "<tr><td>Bed days</td><td>{$male_bed_days}</td><td>{$female_bed_days}</td><td>{$total_bed_days}</td></tr>";
	echo "<tr><td>Ave. length of stay</td><td>{$male_alos} days</td><td>{$female_alos} days</td><td>".($total_alos)." days</td></tr>";
	echo "<tr><td>Turnover interval</td><td>{$male_toi}</td><td>{$female_toi}</td><td>{$total_toi}</td></tr>";
	echo "<tr><td>Turnover Per Bed</td><td>{$male_bed_turn_over}</td><td>{$female_bed_turn_over}</td><td>{$total_bed_turn_over}</td></tr>";
	echo "<tr><td>Bed occupancy rate</td><td>{$percentage_male_beds_occupancy}%</td><td>{$percentage_female_beds_occupancy}%</td><td>{$percentage_total_beds_occupancy}%</td></tr>";
	echo "<tr><td>No. Deaths </td><td>{$male_deaths}</td><td>{$female_deaths}</td><td>".($male_deaths+$female_deaths)."</td></tr>";
	echo "<tr><td>Recovery Rate </td><td></td><td></td><td></td></tr>";
	echo "<tr><td>Av. Daily admission</td><td>{$male_daily_admission}</td><td>{$female_daily_admission}</td><td>{$total_daily_admission}</td></tr>";
	echo "<tr><td>Av. Daily Discharge</td><td>{$male_daily_discharge}</td><td>{$female_daily_discharge}</td><td>{$total_daily_discharge}</td></tr>";

	echo "<tr><td></td><td></td><td></td><td></td></tr>";
	echo "</table>";
	echo "<center>";
?>
