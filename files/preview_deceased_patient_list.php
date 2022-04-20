<?php
@session_start();
include("./includes/connection.php");
	$age_group = $_GET['age_group'];
	$deceased_data = json_decode(base64_decode($_GET['deceased_data']),true);
	$fromDate = $deceased_data['fromDate'];
	$toDate = $deceased_data['toDate'];
	$start_age_death = $deceased_data['start_age_death'];
	$end_age_death = $deceased_data['end_age_death'];
	$Clinic_ID = $deceased_data['Clinic_ID'];
	$disease_caused_death = $deceased_data['disease_caused_death'];
	$filter= ' ';
	$filterIn= ' ';
    if($Clinic_ID!='all'){
        $filter=" AND c.Clinic_ID=$Clinic_ID ";
        $filterIn=" AND cl.Clinic_ID=$Clinic_ID ";
    }
	$age_group_selector=' ';
	if($age_group=="less"){
		$age_group_selector=' < '.$start_age_death;
		$select_patients=mysqli_query($conn,"select pr.Registration_ID, pr.Patient_name, TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) as age,pr.Gender from tbl_patient_registration pr, tbl_admission ad WHERE pr.Registration_ID=ad.Registration_ID AND ad.death_date_time between '$fromDate' and '$toDate' and disease_caused_death ='$disease_caused_death' and TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) < $end_age_death ORDER BY TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) ASC")or die(mysqli_error($conn));
}
		
	if($age_group=="greater"){
		$age_group_selector=' &ge; '.$end_age_death;
		$select_patients=mysqli_query($conn,"select pr.Registration_ID, pr.Patient_name, TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) as age,pr.Gender from tbl_patient_registration pr, tbl_admission ad WHERE pr.Registration_ID=ad.Registration_ID AND ad.death_date_time between '$fromDate' and '$toDate' and disease_caused_death ='$disease_caused_death' and TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= $end_age_death ORDER BY TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) ASC")or die(mysqli_error($conn));	

	}

	$htm  = "<table width ='100%' height = '30px'>";
    $htm .= "<tr> <td> <img src='./branchBanner/branchBanner.png' width=100%> </td> </tr>";
    $htm .= "<tr>";
    $htm .= "<td style='text-align: center;'><h2> Patients List From ".$fromDate." To ".$toDate." With Age ".$age_group_selector."</h2></td>";
    $htm .= "</tr>";
    $htm .= "</table><br/>";

    $htm .= "<table>";
    $htm .= "<tr>";
    $htm .= "<td><b>Disease Name :</b> </td><td> {$disease_caused_death} </td>";
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
    $title .= "<td width='15%'><b>Age</b></td>";
    $title .= "<td width='25%'><b>Gender</b></td>";
    $title .= "</tr></thead>";
    $htm.=$title;
    //print_r(mysqli_fetch_assoc($select_patients));
    $count=1;
    if(mysqli_num_rows($select_patients)==0){
    	$htm.="<tr><td colspan='4'><center><br><br><br><br><b>No Patient Found</b><br><br><br><br><br></center></td></tr>";
    }
    while ($row=mysqli_fetch_assoc($select_patients)) {
		$htm.= "<tr><td>".$count."</td><td>".$row['Patient_name']."</td><td>".$row['age']."</td><td>".strtoupper($row['Gender'])."</td></tr>";
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