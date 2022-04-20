<?php
@session_start();
include("./includes/connection.php");
	$age_group = $_GET['age_group'];
	$disease_data = json_decode(base64_decode($_GET['disease_data']),true);
	$disease_ID = $disease_data['disease_ID'];
	$fromDate = $disease_data['fromDate'];
	$toDate = $disease_data['toDate'];
	$start_age = $disease_data['start_age'];
	$end_age = $disease_data['end_age'];
	$Clinic_ID = $disease_data['Clinic_ID'];
	$disease_name = $disease_data['disease_name'];
	$patient_type = $disease_data['patient_type'];
	$filter= ' ';
	$filterIn= ' ';
    $diagnosis_type = $disease_data['diagnosis_type'];
if($Clinic_ID!='all'){
    $filter=" AND c.Clinic_ID=$Clinic_ID ";
    $filterIn=" AND cl.Clinic_ID=$Clinic_ID ";
}
if($diagnosis_type!='all'){
    if($diagnosis_type=="differential")$diagnosis_type="diferential_diagnosis";
    if($diagnosis_type=="diagnosis")$diagnosis_type="diagnosis";
    if($diagnosis_type=="provisional_diagnosis")$diagnosis_type="provisional_diagnosis";
    
    $filterDiagnosis=" AND dc.diagnosis_type IN ('$diagnosis_type')";
}else{
    $filterDiagnosis=" AND dc.diagnosis_type IN ('diagnosis','provisional_diagnosis','diferential_diagnosis')";
}
	$age_group_selector=' ';
	if($age_group=="less"){
		$age_group_selector=' < '.$start_age;
		//if($patient_type=="Outpatient"){
		$select_patients=mysqli_query($conn,"select pr.Registration_ID, pr.patient_name,TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) as age, pr.Gender, dc.Disease_Consultation_Date_And_Time from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr WHERE
                                    d.disease_ID = dc.disease_ID and
                                    c.consultation_ID = dc.consultation_ID and
                                    c.Registration_ID = pr.Registration_ID and
                                    d.disease_ID=$disease_ID  $filterDiagnosis $filter and c.Consultation_Date_And_Time BETWEEN '$fromDate' and '$toDate' and TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) < $start_age  ORDER BY TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) ASC");
	//}
	// if($patient_type=="Inpatient"){
	// 	$select_patients=mysqli_query($conn,"select distinct pr.Registration_ID,pr.patient_name, TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) as age,pr.Gender from tbl_ward_round_disease wd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr,tbl_consultation c,tbl_clinic cl where wr.consultation_ID=c.consultation_ID AND d.disease_ID = wd.disease_ID and wr.Round_ID = wd.Round_ID and wr.Registration_ID = pr.Registration_ID and diagnosis_type = 'diagnosis' and d.disease_ID=$disease_ID  and
 //  cl.Clinic_ID=c.Clinic_ID $filterIn and wd.Round_Disease_Date_And_Time between '$fromDate' and '$toDate' and TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE())    
 //            < $start_age ORDER BY TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) ASC");
	// }
}
		
	if($age_group=="greater"){
		$age_group_selector=' &ge; '.$end_age;
	//	if($patient_type=="Outpatient"){
		$select_patients=mysqli_query($conn,"select pr.Registration_ID,pr.patient_name,TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) as age, pr.Gender, dc.Disease_Consultation_Date_And_Time from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr WHERE
                                    d.disease_ID = dc.disease_ID and
                                    c.consultation_ID = dc.consultation_ID and
                                    c.Registration_ID = pr.Registration_ID and
                                    d.disease_ID=$disease_ID $filterDiagnosis $filter and c.Consultation_Date_And_Time BETWEEN '$fromDate' and '$toDate' and TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) >= $end_age ORDER BY TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) ASC");
		// }
		// if($patient_type=="Inpatient"){
		// 	$select_patients=mysqli_query($conn,"select distinct pr.Registration_ID,pr.patient_name, TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) as age,pr.Gender from tbl_ward_round_disease wd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr,tbl_consultation c,tbl_clinic cl where wr.consultation_ID=c.consultation_ID AND d.disease_ID = wd.disease_ID and wr.Round_ID = wd.Round_ID and wr.Registration_ID = pr.Registration_ID and diagnosis_type = 'diagnosis' and d.disease_ID=$disease_ID  and
  // cl.Clinic_ID=c.Clinic_ID $filterIn and wd.Round_Disease_Date_And_Time between '$fromDate' and '$toDate' and TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE())	
		// 	>= $end_age ORDER BY TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) ASC");
		// }
	}

	$htm  = "<table width ='100%' height = '30px'>";
    $htm .= "<tr> <td> <img src='./branchBanner/branchBanner.png' width=100%> </td> </tr>";
    $htm .= "<tr>";
    $htm .= "<td style='text-align: center;'><h2> Patients List From ".$fromDate." To ".$toDate." With Age ".$age_group_selector."</h2></td>";
    $htm .= "</tr>";
    $htm .= "</table><br/>";

    $htm .= "<table>";
    $htm .= "<tr>";
    $htm .= "<td><b>Disease Name :</b> </td><td> {$disease_name} </td>";
    $htm .= "</tr>";
    $htm .= "<tr>";
    $htm .= "<td> </td><td> </td>";
    $htm .= "</tr>";
    $htm .= "</table>";
    $htm .= "<br/>";
    $htm .= "<table  width='100%' border='1' style='border-collapse: collapse;' cellpadding=5 cellspacing=10>";
    $title = "<thead><tr>";
    $title .= "<td width='10%'><b>SN</b></td>";
    $title .= "<td width='50%'><b>Patient Name</b></td>";
    $title .= "<td width='15%' style='text-align:center;'><b>Age</b></td>";
    $title .= "<td width='15%'><b>Gender</b></td>";
    $title .= "<td width='35%' style='text-align:center;'><b>Diagnosis Date</b></td>";
    $title .= "</tr></thead>";
    $htm.=$title;
    //print_r(mysqli_fetch_assoc($select_patients));
    $count=1;
    if(mysqli_num_rows($select_patients)==0){
    	$htm.="<tr><td colspan='4'><center><br><br><br><br><b>No Patient Found</b><br><br><br><br><br></center></td></tr>";
    }
    while ($row=mysqli_fetch_assoc($select_patients)) {
		$htm.= "<tr><td>".$count."</td><td>".ucwords(strtolower($row['patient_name']))."</td></td><td style='text-align:center;'>".$row['age']."</td><td  style='padding-left:10px;'>".ucfirst($row['Gender'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['Disease_Consultation_Date_And_Time'])."</td></tr>";
		$count++;
	}
    $htm .= "</table>";
    //$htm.=$title;
    include("MPDF/mpdf.php");
    $mpdf = new mPDF('s', 'A4');
    $mpdf->SetFooter('|Page {PAGENO} of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>