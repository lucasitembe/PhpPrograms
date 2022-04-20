<?php
@session_start();
include("./includes/connection.php");
$filter=" ";
$filterIn=" ";
$disease_data=$_POST['disease_data'];

$disease_ID = $disease_data['disease_ID'];
$fromDate = $disease_data['fromDate'];
$toDate = $disease_data['toDate'];
$start_age = $disease_data['start_age'];
$end_age = $disease_data['end_age'];
$Clinic_ID = $disease_data['Clinic_ID'];
$disease_name = $disease_data['disease_name'];
$patient_type = $disease_data['patient_type'];
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
echo "<div><button class='art-button-green' onclick='chooseAgeCategory(\"less_age\");'>View Age < $start_age </button><button class='art-button-green' onclick='chooseAgeCategory(\"greater_age\");'> View Age &ge; $end_age </button></div>";
$select_patients=mysqli_query($conn,"select pr.Registration_ID, pr.patient_name,TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) as age, pr.Gender, dc.Disease_Consultation_Date_And_Time from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr WHERE
                                    d.disease_ID = dc.disease_ID and
                                    c.consultation_ID = dc.consultation_ID and
                                    c.Registration_ID = pr.Registration_ID and
                                    d.disease_ID=$disease_ID $filterDiagnosis $filter and c.Consultation_Date_And_Time BETWEEN '$fromDate' and '$toDate' and TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) < $start_age  ORDER BY TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()), pr.patient_name ASC");
$count=1;
echo "<br><fieldset style='background-color:white;'><legend>Disease Name: ".$disease_name."</legend>";
echo "<div id='less_age'>";
echo "<center>List Of Patients From:{$fromDate} To:{$toDate} With Age < {$start_age} Years</center>";
echo "<table width='100%;' border='1' style='font-size:18px;border-collapse: collapse;' cellpadding=5 cellspacing=10>";
echo "<thead>
			<tr><th>SN</th><th>Patient Name</th><th>Age</th><th>Gender</th><th>Diagnosis Date</th></tr>
		</thead>";
		if(mysqli_num_rows($select_patients) > 0){
while ($row=mysqli_fetch_assoc($select_patients)) {
	echo "<tr><td>".$count."</td><td style='padding-left:10px;'><a href='javascript:void(0)' target='_blank' style='display:block;'>".ucwords(strtolower($row['patient_name']))."</a></td><td style='text-align:center;'>".$row['age']."</td><td  style='padding-left:10px;'>".ucfirst($row['Gender'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['Disease_Consultation_Date_And_Time'])."</td></tr>";
	$count++;
}}else{
	echo "<tr><td colspan='5'><center><br><br><br><br><b>No Patient Found</b><br><br><br><br></center></td></tr>";
}
echo "</table>";
$patients_object=json_encode($disease_data);
echo "<br><button class='art-button-green' onclick='Preview_List(".$patients_object.",\"less\");'>Preview In PDF</button>";
echo "<button class='art-button-green' onclick='Preview_Distict_List(".$patients_object.",\"less\");'>Download Patient List In Excel</button><br>";
echo "</div>";

$select_patients=mysqli_query($conn,"select pr.Registration_ID,pr.patient_name,TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) as age, pr.Gender, dc.Disease_Consultation_Date_And_Time from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr WHERE
                                    d.disease_ID = dc.disease_ID and
                                    c.consultation_ID = dc.consultation_ID and
                                    c.Registration_ID = pr.Registration_ID and
                                    d.disease_ID=$disease_ID $filterDiagnosis $filter and c.Consultation_Date_And_Time BETWEEN '$fromDate' and '$toDate' and TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) >= $end_age ORDER BY TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()), pr.patient_name ASC");
$count=1;
echo "<div id='greater_age' style='display:none;'>";
echo "<center>List Of Patients From: {$fromDate} To: {$toDate} With Age &ge; $end_age Years</center>";
echo "<table width='100%;' border='1' style='font-size:18px;border-collapse: collapse;' cellpadding=5 cellspacing=10>";
echo "<thead>
			<tr><th>SN</th><th>Patient Name</th><th>Age</th><th>Gender</th><th>Diagnosis Date</th></tr>
		</thead>";
		if(mysqli_num_rows($select_patients) > 0){
while ($row=mysqli_fetch_assoc($select_patients)) {
	echo "<tr><td>".$count."</td><td style='padding-left:10px;'><a href='javascript:void(0)' target='_blank' style='display:block;'>".ucwords(strtolower($row['patient_name']))."</a></td><td style='text-align:center;'>".$row['age']."</td><td  style='padding-left:10px;'>".ucfirst($row['Gender'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['Disease_Consultation_Date_And_Time'])."</td</tr>";
	$count++;
}}else{
	echo "<tr><td colspan='5'><center><br><br><br><br><b>No Patient Found</b><br><br><br><br></center></td></tr>";
}
echo "</table>";

$patients_object=json_encode($disease_data);
echo "<br><button class='art-button-green' onclick='Preview_List(".$patients_object.",\"greater\");'>Preview In PDF</button>";
echo "<button class='art-button-green' onclick='Preview_Distict_List(".$patients_object.",\"greater\");'>Download Patient List In Excel</button><br>";
echo "</div>";
echo "</fieldset>";

if($patient_type=="Inpatient"){
$select_patients=mysqli_query($conn,"select distinct pr.Registration_ID, pr.Patient_name ,TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) as age,pr.Gender from tbl_ward_round_disease wd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr,tbl_consultation c,tbl_clinic cl where wr.consultation_ID=c.consultation_ID AND d.disease_ID = wd.disease_ID and wr.Round_ID = wd.Round_ID and wr.Registration_ID = pr.Registration_ID and diagnosis_type = 'diagnosis' and d.disease_ID=$disease_ID  and
									cl.Clinic_ID=c.Clinic_ID $filterIn and wd.Round_Disease_Date_And_Time between '{$fromDate}' and '{$toDate}' and TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE())
 < $start_age  ORDER BY TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) ASC");
$count=1;
echo "<br><fieldset style='background-color:white;'><legend>Disease Name: ".$disease_name."</legend>";
echo "<div id='less_age'>";
echo "<center>List Of Patients From:{$fromDate} To:{$toDate} With Age < {$start_age} Years</center>";
echo "<table width='100%;'border='1' style='font-size:18px;border-collapse: collapse;' cellpadding=5 cellspacing=10>";
echo "<thead>
			<tr><th>SN</th><th>Patient Name</th><th>Age</th><th>Gender</th></tr>
		</thead>";
		if(mysqli_num_rows($select_patients) > 0){
while ($row=mysqli_fetch_assoc($select_patients)) {
	echo "<tr><td>".$count."</td><td><a href='javascript:void(0)' target='_blank' style='display:block;'>".$row['Patient_name']."</a></td><td>".$row['age']."</td><td>".strtoupper($row['Gender'])."</td></tr>";
	$count++;
}}else{
	echo "<tr><td colspan='5'><center><br><br><br><b>No Patient Found</b><br><br><br></center></td></tr>";
}
echo "</table>";
$patients_object=json_encode($disease_data);
echo "<br><button class='art-button-green' onclick='Preview_List(".$patients_object.",\"less\");'>Preview</button><br>";
echo "<br><button class='art-button-green' onclick='Preview_Distict_List(".$patients_object.",\"less\");'>Preview In Patient</button><br>";
echo "</div>";

$select_patients=mysqli_query($conn,"select distinct pr.Registration_ID, pr.Patient_name, TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) as age,pr.Gender from tbl_ward_round_disease wd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr,tbl_consultation c,tbl_clinic cl where wr.consultation_ID=c.consultation_ID AND d.disease_ID = wd.disease_ID and wr.Round_ID = wd.Round_ID and wr.Registration_ID = pr.Registration_ID and diagnosis_type = 'diagnosis' and d.disease_ID=$disease_ID and
									cl.Clinic_ID=c.Clinic_ID $filterIn and wd.Round_Disease_Date_And_Time between '$fromDate' and '$toDate' and TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE())
 >= $end_age ORDER BY TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) ASC");
$count=1;
echo "<div id='greater_age' style='display:none;'>";
echo "<center>List Of Patients From: {$fromDate} To: {$toDate} With Age &ge; $end_age Years</center>";
echo "<table width='100%;' border='1' style='font-size:18px;border-collapse: collapse;' cellpadding=5 cellspacing=10>";
echo "<thead>
			<tr><th>SN</th><th>Patient Name</th><th>Age</th><th>Gender</th></tr>
		</thead>";
		if(mysqli_num_rows($select_patients) > 0){
while ($row=mysqli_fetch_assoc($select_patients)) {
	echo "<tr><td>".$count."</td><td><a href='javascript:void(0)' target='_blank' style='display:block;'>".$row['Patient_name']."</a></td><td>".$row['age']."</td><td>".strtoupper($row['Gender'])."</td></tr>";
	$count++;
}}else{
	echo "<tr><td colspan='5'><center><br><br><br><b>No Patient Found</b><br><br><br></center></td></tr>";
}
echo "</table>";

$patients_object=json_encode($disease_data);
echo "<br><button class='art-button-green' onclick='Preview_List(".$patients_object.",\"greater\");'>Preview</button><br>";
echo "</div>";
echo "</fieldset>";
}

?>
<script type="text/javascript">
	function chooseAgeCategory(ageCategory){
		if(ageCategory==='less_age'){
			$('#greater_age').hide();
			$('#less_age').show();
		}
		if(ageCategory==='greater_age'){
			$('#less_age').hide();
			$('#greater_age').show();
		}
	}
	function Preview_List(disease_data,age_group){
		window.open('preview_disease_patient_list.php?disease_data='+encodeURI(window.btoa(JSON.stringify(disease_data)))+'&age_group='+age_group, '_blank');
	}
	function Preview_Distict_List(disease_data,age_group){
		window.open('preview_disease_distinct_patient_list.php?disease_data='+encodeURI(window.btoa(JSON.stringify(disease_data)))+'&age_group='+age_group, '_blank');
		return false;
	}
 
</script>
<script language="javascript" type="text/javascript">
    function OpenNewTab(href) {
        document.getElementById('linkDynamic').href = href;
        document.getElementById('linkDynamic').click();
    }
</script>
