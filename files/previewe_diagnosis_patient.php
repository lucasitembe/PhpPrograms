<?php
@session_start();
include("./includes/connection.php");
	$disease_name = $_GET['disease_name'];
//	$disease_data = json_decode(base64_decode($_GET['disease_data']),true);
	$disease_ID = $_GET['disease_ID'];
	$fromDate = $_GET['fromDate'];
	$toDate = $_GET['toDate'];
	$start_age = $_GET['start_age'];
	$end_age = $_GET['end_age'];
	$diagnosis_report_category = $_GET['diagnosis_report_category'];
	$diagnosis_time = $_GET['diagnosis_time'];
	$filter= ' ';
	$filterIn= ' ';
  ;

  
  $htm  = "<table width ='100%' height = '30px'>";
    $htm .= "<tr> <td> <img src='./branchBanner/branchBanner.png' width=100%> </td> </tr>";
    $htm .= "<tr>";
    $htm .= "<td style='text-align: center;'><h5> Patients List From ".$fromDate." To ".$toDate." With Age Between ".$start_age." And ".$end_age."</h5></td>";
    $htm .= "</tr>";
    $htm .= "</table><br/>";
//\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

  
 $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$age ='';
    }

if($diagnosis_report_category == "opd_diagnosis"){
    
 $select_patients=mysqli_query($conn,"select pr.Registration_ID, pr.patient_name,pr.Date_Of_Birth, pr.Gender, dc.Disease_Consultation_Date_And_Time from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr WHERE
                                    d.disease_ID = dc.disease_ID and
                                    c.consultation_ID = dc.consultation_ID and
                                    c.Registration_ID = pr.Registration_ID and
                                    d.disease_ID=$disease_ID and c.Consultation_Date_And_Time BETWEEN '$fromDate' and '$toDate' and TIMESTAMPDIFF($diagnosis_time,Date_Of_Birth,CURDATE())  BETWEEN $start_age AND $end_age ORDER BY TIMESTAMPDIFF($diagnosis_time,Date_Of_Birth,CURDATE()), pr.patient_name ASC");

 
 $count=1;
$htm .= "<br><fieldset style='background-color:white;'><legend>Disease Name: ".$disease_name."</legend>";
//$htm .= "<div id='less_age'>";
//$htm .= "<center>List Of Patients From:{$fromDate} To:{$toDate} With Age From {$start_age} {$diagnosis_time}  To {$end_age} {$diagnosis_time} years</center>";
$htm .= "<table width='100%;' border='1' style='font-size:12px;border-collapse: collapse;' cellpadding=5 cellspacing=10>";
$htm .= "<thead>
			<tr><th>SN</th><th>Patient Name</th><th>Age</th><th>Gender</th><th>Diagnosis Date</th></tr>
		</thead>";
		if(mysqli_num_rows($select_patients) > 0){
while ($row=mysqli_fetch_assoc($select_patients)) {
    
     $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";
	   // if($age == 0){
		$Registration_ID = $row['Registration_ID'];
		if($diagnosis_report_case=='newcase'){
		$resultQuery = mysqli_query($conn, "SELECT  c.Registration_ID  FROM tbl_disease_consultation dcw, tbl_consultation c , tbl_patient_registration pr   WHERE c.consultation_ID = dcw.consultation_ID AND  dcw.disease_ID='$disease_ID' AND c.Registration_ID = pr.Registration_ID AND c.Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
		$numrows = mysqli_num_rows($resultQuery);
			if($numrows>1){ continue; }
		}
		$date1 = new DateTime($Today);
		$date2 = new DateTime($row['Date_Of_Birth']);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
	$htm .= "<tr><td>".$count."</td><td style='padding-left:10px;'><a href='javascript:void(0)' target='_blank' style='display:block;'>".ucwords(strtolower($row['patient_name']))."</a></td><td style='text-align:center;'>".$age."</td><td  style='padding-left:10px;'>".ucfirst($row['Gender'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['Disease_Consultation_Date_And_Time'])."</td></tr>";
	$count++;
}}else{
	$htm .= "<tr><td colspan='5'><center><br><br><br><br><b>No Patient Found</b><br><br><br><br></center></td></tr>";
}
$htm .= "</table>";
//$htm .= "<br><button class='art-button-green' onclick='Preview_Lists(\"$disease_name\",\"$disease_ID\",\"$fromDate\",\"$toDate\",\"$diagnosis_report_category\",\"$start_age\",\"$end_age\",\"$diagnosis_time\");'>Preview In PDF</button>";
   
    
}elseif($diagnosis_report_category == "ipd_diagnosis"){
    
     $select_patients=mysqli_query($conn,"select pr.Registration_ID, pr.patient_name,pr.Date_Of_Birth, pr.Gender, wrd.Round_Disease_Date_And_Time from tbl_ward_round_disease wrd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr WHERE
                                    d.disease_ID = wrd.disease_ID and
                                    wr.Round_ID = wrd.Round_ID and
                                    wr.Registration_ID = pr.Registration_ID and
                                    d.disease_ID=$disease_ID and wrd.Round_Disease_Date_And_Time BETWEEN '$fromDate' and '$toDate' and TIMESTAMPDIFF($diagnosis_time,Date_Of_Birth,CURDATE())  BETWEEN $start_age AND $end_age ORDER BY TIMESTAMPDIFF($diagnosis_time,Date_Of_Birth,CURDATE()), pr.patient_name ASC");
$count=1;
$htm .= "<br><fieldset style='background-color:white;'><legend>Disease Name: ".$disease_name."</legend>";
$htm .= "<div id='less_age'>";
$htm .= "<center>List Of Patients From:{$fromDate} To:{$toDate} With Age From {$start_age} Years  To {$end_age} years</center>";
$htm .= "<table width='100%;' border='1' style='font-size:12px;border-collapse: collapse;' cellpadding=5 cellspacing=10>";
$htm .= "<thead>
			<tr><th>SN</th><th>Patient Name</th><th>Age</th><th>Gender</th><th>Diagnosis Date</th></tr>
		</thead>";
		if(mysqli_num_rows($select_patients) > 0){
while ($row=mysqli_fetch_assoc($select_patients)) {
    $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";
	   // if($age == 0){
		$Registration_ID = $row['Registration_ID'];
		if($diagnosis_report_case=='newcase'){ 
			$resultQuery = mysqli_query($conn, "SELECT  wr.Registration_ID  FROM tbl_ward_round_disease wrd, tbl_ward_round wr, tbl_patient_registration pr   WHERE wr.Round_ID = wrd.Round_ID AND wr.Registration_ID = pr.Registration_ID AND wrd.disease_ID=$disease_ID AND wr.Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
			$numrows = mysqli_num_rows($resultQuery);
			if($numrows>1){ continue; }
		}
		$date1 = new DateTime($Today);
		$date2 = new DateTime($row['Date_Of_Birth']);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
	$htm .= "<tr><td>".$count."</td><td style='padding-left:10px;'><a href='javascript:void(0)' target='_blank' style='display:block;'>".ucwords(strtolower($row['patient_name']))."</a></td><td style='text-align:center;'>".$age."</td><td  style='padding-left:10px;'>".ucfirst($row['Gender'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['Round_Disease_Date_And_Time'])."</td></tr>";
	$count++;
}}else{
	$htm .= "<tr><td colspan='5'><center><br><br><br><br><b>No Patient Found</b><br><br><br><br></center></td></tr>";
}
$htm .= "</table>";
//  $htm .= "<br><button class='art-button-green' onclick='Preview_Lists(\"$disease_name\",\"$disease_ID\",\"$fromDate\",\"$toDate\",\"$diagnosis_report_category\",\"$start_age\",\"$end_age\",\"$diagnosis_time\");'>Preview In PDF</button>"; 
  
    
}
  
//\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

	

    
    //$htm.=$title;
    include("MPDF/mpdf.php");
    $mpdf = new mPDF('s', 'A4');
    $mpdf->SetFooter('|Page {PAGENO} of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>