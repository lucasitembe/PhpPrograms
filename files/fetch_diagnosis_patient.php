<?php

@session_start();
include("./includes/connection.php");
$filter=" ";
$filterIn=" ";
$disease_name=$_POST['disease_name'];
$Clinic_ID=$_POST['Clinic_ID'];
$disease_ID = $_POST['disease_ID'];
$fromDate = $_POST['fromDate'];
$toDate = $_POST['toDate'];
$start_age = $_POST['start_age'];
$end_age = $_POST['end_age'];
$diagnosis_report_category = $_POST['diagnosis_report_category'];
$diagnosis_time = $_POST['diagnosis_time'];
$diagnosis_report_case = $_POST['diagnosis_report_case'];
if($Clinic_ID != 'all'){
	$filterdiagnosis_type3="AND c.Clinic_ID='$Clinic_ID'";
//      echo "clinic ndani";
   }else {
	   $filterdiagnosis_type3 ="";
   }

 $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$age ='';
    }

if($diagnosis_report_category == "opd_diagnosis"){
	
 $select_patients=mysqli_query($conn,"SELECT pr.Registration_ID, pr.patient_name,pr.Date_Of_Birth, pr.Gender, dc.Disease_Consultation_Date_And_Time from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr WHERE    d.disease_ID = dc.disease_ID and   c.consultation_ID = dc.consultation_ID and   c.Registration_ID = pr.Registration_ID and     d.disease_ID=$disease_ID and c.Consultation_Date_And_Time BETWEEN '$fromDate' and '$toDate' $filterdiagnosis_type3 and TIMESTAMPDIFF($diagnosis_time,Date_Of_Birth,CURDATE())  BETWEEN $start_age AND $end_age ORDER BY TIMESTAMPDIFF($diagnosis_time,Date_Of_Birth,CURDATE()), pr.patient_name ASC" );

 
 $count=1;
echo "<br><fieldset style='background-color:white;'><legend>Disease Name: ".$disease_name."</legend>";
echo "<div id='less_age'>";
echo "<center>List Of Patients From:{$fromDate} To:{$toDate} With Age From {$start_age} {$diagnosis_time}  To {$end_age} {$diagnosis_time} years</center>";
echo "<table width='100%;' border='1' style='font-size:18px;border-collapse: collapse;' cellpadding=5 cellspacing=10>";
echo "<thead>
			<tr><th>SN</th><th>Patient Name</th><th>Registration #</th><th>Age</th><th>Gender</th><th>Diagnosis Date</th></tr>
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
	echo "<tr><td>".$count."</td><td style='padding-left:10px;'><a href='#' target='_blank' style='display:block;'>".ucwords(strtolower($row['patient_name']))."</a></td><td>$Registration_ID</td><td style='text-align:center;'>".$age."</td><td  style='padding-left:10px;'>".ucfirst($row['Gender'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['Disease_Consultation_Date_And_Time'])."</td></tr>";
	$count++;
}}else{
	echo "<tr><td colspan='6'><center><br><br><br><br><b>No Patient Found</b><br><br><br><br></center></td></tr>";
}
echo "</table>";
echo "<br><button class='art-button-green' onclick='Preview_Lists(\"$disease_name\",\"$disease_ID\",\"$fromDate\",\"$toDate\",\"$diagnosis_report_category\",\"$start_age\",\"$end_age\",\"$diagnosis_time\", \"$diagnosis_report_case\");'>Preview In PDF</button>";
   
    
}elseif($diagnosis_report_category == "ipd_diagnosis"){
    
     $select_patients=mysqli_query($conn,"select pr.Registration_ID, pr.patient_name,pr.Date_Of_Birth, pr.Gender, wrd.Round_Disease_Date_And_Time from tbl_ward_round_disease wrd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr WHERE
                                    d.disease_ID = wrd.disease_ID and
                                    wr.Round_ID = wrd.Round_ID and
                                    wr.Registration_ID = pr.Registration_ID and
                                    d.disease_ID=$disease_ID and wrd.Round_Disease_Date_And_Time BETWEEN '$fromDate' and '$toDate' and TIMESTAMPDIFF($diagnosis_time,Date_Of_Birth,CURDATE())  BETWEEN $start_age AND $end_age ORDER BY TIMESTAMPDIFF($diagnosis_time,Date_Of_Birth,CURDATE()), pr.patient_name ASC");
$count=1;
echo "<br><fieldset style='background-color:white;'><legend>Disease Name: ".$disease_name."</legend>";
echo "<div id='less_age'>";
echo "<center>List Of Patients From:{$fromDate} To:{$toDate} With Age From {$start_age} Years  To {$end_age} years</center>";
echo "<table width='100%;' border='1' style='font-size:18px;border-collapse: collapse;' cellpadding=5 cellspacing=10>";
echo "<thead>
			<tr><th>SN</th><th>Patient Name</th><th>Registration #</th><th>Age</th><th>Gender</th><th>Diagnosis Date</th></tr>
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
	echo "<tr><td>".$count."</td><td style='padding-left:10px;'><a href='javascript:void(0)' target='_blank' style='display:block;'>".ucwords(strtolower($row['patient_name']))."</a></td><td>$Registration_ID</td><td style='text-align:center;'>".$age."</td><td  style='padding-left:10px;'>".ucfirst($row['Gender'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['Round_Disease_Date_And_Time'])."</td></tr>";
	$count++;
}}else{
	echo "<tr><td colspan='6'><center><br><br><br><br><b>No Patient Found</b><br><br><br><br></center></td></tr>";
}
echo "</table>";
  echo "<br><button class='art-button-green' onclick='Preview_Lists(\"$disease_name\",\"$disease_ID\",\"$fromDate\",\"$toDate\",\"$diagnosis_report_category\",\"$start_age\",\"$end_age\",\"$diagnosis_time\", \"$diagnosis_report_case\");'>Preview In PDF</button>"; 
  
    
}

?>
<script>
function Preview_Lists(disease_name,disease_ID,fromDate,toDate,diagnosis_report_category,start_age,end_age,diagnosis_time, diagnosis_report_case){
		window.open('previewe_diagnosis_patient.php?disease_name='+disease_name+'&disease_ID='+disease_ID+'&fromDate='+fromDate+'&toDate='+toDate+'&diagnosis_report_category='+diagnosis_report_category+'&start_age='+start_age+'&end_age='+end_age+'&diagnosis_time='+diagnosis_time+'&diagnosis_report_case='+diagnosis_report_case, '_blank');
	}
        
</script>    
