<?php
    include("./includes/connection.php");
   
    $filter = "  AND cid.ToBe_Admitted = 'yes' AND cid.Admit_Status = 'not admitted' ";


    if(isset($_GET['Patient_Name'])){
    	$Patient_Name = str_replace(" ", "%", $_GET['Patient_Name']);
    }else{
    	$Patient_Name = '';
    }

    if(isset($_GET['Patient_Number'])){
    	$Patient_Number = $_GET['Patient_Number'];
    }else{
    	$Patient_Number = '';
    }
    
    if(isset($_GET['Ward_ID'])){
    	$Ward_ID = $_GET['Ward_ID'];
    }else{
    	$Ward_ID = '';
    }
    
    
    if(isset($_GET['Sponsor'])){
    	$Sponsor = $_GET['Sponsor'];
    }else{
    	$Sponsor = 'All';
    }
     
    if(isset($_GET['ward'])){
    	$ward = $_GET['ward'];
    }else{
    	$ward = 'All';
    }
  
    if (!empty($Sponsor) && $Sponsor != 'All') {
        $filter .="  AND sp.Sponsor_ID=$Sponsor";
    }
    
     if (!empty($Ward_ID) && $Ward_ID != 'All') {
        $filter .=" AND cid.Ward_suggested=$Ward_ID";
    }
    
//    

    if ($Patient_Name != '' && $Patient_Name != null) {
        $filter .="  AND pr.Patient_Name like '%$Patient_Name%'";
    }

	if ($Patient_Number != '' && $Patient_Number != null) {
        $filter .="  AND pr.Registration_ID = '$Patient_Number'";
    }
    
   	//today function
	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
		$age ='';
    }
	//end
    
    $patients_tobeadmitted = "SELECT pr.Date_Of_Birth,
    pr.Patient_Name,
    sp.Guarantor_Name,
    pr.Gender,
    pr.Phone_Number,
    pr.Member_Number,
    em.Employee_Name,
    cid.Check_In_ID,
    pr.Registration_ID,
    cid.Ward_suggested FROM tbl_check_in_details cid,tbl_patient_registration pr,	tbl_employee em,tbl_sponsor sp	WHERE cid.Registration_ID = pr.Registration_ID
        AND cid.Employee_ID = em.Employee_ID
        AND cid.Sponsor_ID = sp.Sponsor_ID	$filter GROUP BY cid.Registration_ID order by cid.Check_In_Details_ID DESC LIMIT 10";
    // die($patients_tobeadmitted);
        
//        echo $patients_tobeadmitted;
	echo "<link rel='stylesheet' href='fixHeader.css'>";


	$patients_tobeadmitted_qry = mysqli_query($conn,$patients_tobeadmitted) or die(mysqli_error($conn));
	$sn = 1;
	echo "<table class='fixTableHead' width='100%' id='patientList'>";
        echo "<thead>
             <tr >
                <th style='width:5%;'>SN</th>
                <th><b>PATIENT NAME</b></th>
                <th><b>PATIENT NO</b></th>
                <th><b>GENDER</b></th>
                <th><b>AGE</b></th>
                <th><b>SPONSOR</b></th>
                <th><b>PHONE NUMBER</b></th>
                <th><b>DOCTOR/NURSE</b></th>
             </tr>
         </thead>";
if (mysqli_num_rows($patients_tobeadmitted_qry) > 0) {
    while ($toadmit = mysqli_fetch_assoc($patients_tobeadmitted_qry)) {

        //AGE FUNCTION
        $age = floor((strtotime(date('Y-m-d')) - strtotime($toadmit['Date_Of_Birth'])) / 31556926) . " Years";
        // if($age == 0){

        $date1 = new DateTime($Today);
        $date2 = new DateTime($toadmit['Date_Of_Birth']);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";

        $patient_name = $toadmit['Patient_Name'];
        $sponsor = $toadmit['Guarantor_Name'];
        //$dob = $toadmit['Date_Of_Birth'];
        $gender = $toadmit['Gender'];
        $phone = $toadmit['Phone_Number'];
        $member_number = $toadmit['Member_Number'];
        $doctor = $toadmit['Employee_Name'];
        $Check_In_ID = $toadmit['Check_In_ID'];
        $Registration_ID = $toadmit['Registration_ID'];
        $suggested_ward = $toadmit['Ward_suggested'];
        $ward = '&Ward_suggested=' . $suggested_ward . '';

        if (isset($_GET['fromDoctorPage']) && $_GET['fromDoctorPage'] == 'fromDoctorPage') {

            $admit_link = "admit.php?Registration_ID=" . $Registration_ID . "&Check_In_ID=" . $Check_In_ID . "&AdmitPatient=AdmitPatientThisPage&fromDoctorPage=fromDoctorPage" . $ward;
        } else {
            $admit_link = "admit.php?Registration_ID=" . $Registration_ID . "&Check_In_ID=" . $Check_In_ID . "&AdmitPatient=AdmitPatientThisPage" . $ward;
        }
        $link_style = "style='text-decoration:none;'";

        echo "<tr>";
        echo "<td>" . $sn . "</td>";
        echo "<td><a href='" . $admit_link . "' target='_parent' " . $link_style . ">" . ucwords(strtolower($patient_name)) . "</a></td>";
        echo "<td><a href='" . $admit_link . "' target='_parent' " . $link_style . ">" . $Registration_ID . "</a></td>";
        echo "<td><a href='" . $admit_link . "' target='_parent' " . $link_style . ">" . $gender . "</a></td>";
        echo "<td><a href='" . $admit_link . "' target='_parent' " . $link_style . ">" . $age . "</a></td>";
        echo "<td><a href='" . $admit_link . "' target='_parent' " . $link_style . ">" . $sponsor . "</a></td>";
        echo "<td><a href='" . $admit_link . "' target='_parent' " . $link_style . ">" . $phone . "</a></td>";
        echo "<td><a href='" . $admit_link . "' target='_parent' " . $link_style . ">" . $doctor . "</a></td>";
        echo "</tr>";
        $sn++;
    }
} else {
    if (!empty($Patient_Number)) {
        $select_mgonjwa = mysqli_query($conn, "SELECT Patient_Name FROM tbl_patient_registration WHERE Registration_ID = '$Patient_Number'");
        // die($select_mgonjwa);
        $mgonjwa_available = mysqli_num_rows($select_mgonjwa);

        $check_if_not_discharged = mysqli_query($conn, "SELECT Admission_ID FROM ");

        if ($mgonjwa_available > 0) {
            $Patient_Name = mysqli_fetch_assoc($select_mgonjwa)['Patient_Name'];
            $find_if_still_admitted = mysqli_query($conn, "SELECT Admission_ID FROM `tbl_check_in_details` WHERE `Registration_ID`= '$Patient_Number' AND ToBe_Admitted = 'yes' AND Admit_Status = 'admitted' ORDER BY Check_In_Details_ID DESC LIMIT 1");
            $find_if_still_admitted_row = mysqli_num_rows($find_if_still_admitted);

            // $find_if_discharged = mysqli_query($conn, "SELECT Admission_ID FROM `tbl_check_in_details` WHERE `Registration_ID`= '$Patient_Number' AND ToBe_Admitted = 'yes' AND Admit_Status = 'discharged' ORDER BY Check_In_Details_ID DESC LIMIT 1");
            // $find_if_discharged_row = mysqli_num_rows($find_if_discharged);

            if ($find_if_still_admitted_row > 0) {
                $Admission_ID = mysqli_fetch_assoc($find_if_still_admitted)['Admission_ID'];
                $select_info = mysqli_query($conn, "SELECT Hospital_Ward_ID,Bed_Name,Admission_Date_Time,Admission_Employee_ID FROM `tbl_admission` WHERE `Admision_ID`='$Admission_ID' AND Admission_Status <> 'Discharged'");

                while ($infos = mysqli_fetch_assoc($select_info)) {
                    $Hospital_Ward_ID = $infos['Hospital_Ward_ID'];
                    $Bed_Name = $infos['Bed_Name'];
                    $Admission_Date_Time = $infos['Admission_Date_Time'];
                    $Admission_Employee_ID = $infos['Admission_Employee_ID'];
                }
                if ($Hospital_Ward_ID != '' && $Hospital_Ward_ID != null && !(empty($Hospital_Ward_ID))) {
                    $Hospital_Ward_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_ID='$Hospital_Ward_ID'"))['Hospital_Ward_Name'];
                    $nurse_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Admission_Employee_ID'"))['Employee_Name'];

                    echo "
					<tr>
						<td colspan='8' style='text-align: center;color: red;'><b>Patient with Number {$Patient_Number} ({$Patient_Name}) is already admitted by {$nurse_name} in ward {$Hospital_Ward_Name} , Bed: {$Bed_Name} at {$Admission_Date_Time}.</b></td>
					</tr>";
                } else {
                    echo "
					<tr>
						<td colspan='8' style='text-align: center;color: red;'><b>It seems order to admit {$Patient_Name} is not yet given by doctor. Please consult doctor or go to list of checked in patients to ADMIT yourself.</b></td>
					</tr>";
                }
            } else {
                echo "
			<tr>
				<td colspan='8' style='text-align: center;color: red;'><b>It seems order to admit {$Patient_Name} is not yet given by doctor. Please consult doctor or go to list of checked in patients to ADMIT yourself.</b></td>
			</tr>";
            }
        } else {
            echo "
			<tr>
				<td colspan='8' style='text-align: center;color: red;'><b>No patient with Number {$Patient_Number} in eHMS.</b></td>
			</tr>";
        }
    }
}
	echo "</table>";

mysqli_close($conn);
	
?>