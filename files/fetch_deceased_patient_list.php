<?php
@session_start();
include("./includes/connection.php");
$filter=" ";
$filterIn=" ";
$deceased_data=$_POST['deceased_data'];

$disease_caused_death = $deceased_data['disease_caused_death'];
$fromDate = $deceased_data['fromDate'];
$toDate = $deceased_data['toDate'];
$start_age_death = $deceased_data['start_age_death'];
$end_age_death = $deceased_data['end_age_death'];
$Clinic_ID = $deceased_data['Clinic_ID'];
$ward_ID = $deceased_data['ward_ID'];
if($Clinic_ID!='all'){
        $filter=" AND c.Clinic_ID=$Clinic_ID ";
        $filterIn=" AND cl.Clinic_ID=$Clinic_ID ";
}
$filterDeathWard=' ';
if($ward_ID!=='all'){
    $filterDeathWard =" AND Hospital_Ward_ID='$ward_ID'";
}
echo "<div><button class='art-button-green' onclick='chooseAgeCategory(\"less_age\");'>View Age < $start_age_death </button><button class='art-button-green' onclick='chooseAgeCategory(\"greater_age\");'> View Age &ge; $end_age_death </button></div>";

$select_patients=mysqli_query($conn,"select pr.Registration_ID, pr.Patient_name, TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) as age,pr.Gender from tbl_patient_registration pr, tbl_admission ad WHERE pr.Registration_ID=ad.Registration_ID AND ad.death_date_time between '$fromDate' and '$toDate' and disease_caused_death ='$disease_caused_death' and TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) < $end_age_death ORDER BY TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) ASC
");
$count=1;
echo "<br><fieldset style='background-color:white;'><legend>Disease Name: ".$disease_caused_death."</legend>";
echo "<div id='less_age'>";
echo "<center>List Of Deceased Persons From: {$fromDate} To: {$toDate} With Age < {$start_age_death} Years</center>";
echo "<table width='100%;' border='1' style='font-size:18px;border-collapse: collapse;' cellpadding=5 cellspacing=10>";
echo "<thead>
			<tr><th>SN</th><th>Patient Name</th><th>Age</th><th>Gender</th></tr>
		</thead>";
		if(mysqli_num_rows($select_patients) > 0){
while ($row=mysqli_fetch_assoc($select_patients)) {
	echo "<tr><td>".$count."</td><td><a href='javascript:void(0)' target='_blank' style='display:block;'>".$row['Patient_name']."</a></td><td>".$row['age']."</td><td>".strtoupper($row['Gender'])."</td></tr>";
	$count++;
}}else{
	echo "<tr><td colspan='4'><center><br><br><br><br><b>No Patient Found</b><br><br><br><br></center></td></tr>";
}
echo "</table>";
$deceased_object=json_encode($deceased_data);
echo "<br><button class='art-button-green' onclick='Preview_List(".$deceased_object.",\"less\");'>Preview</button><br>";
echo "</div>";

$select_patients=mysqli_query($conn,"select pr.Registration_ID, pr.Patient_name, TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) as age,pr.Gender from tbl_patient_registration pr, tbl_admission ad WHERE pr.Registration_ID=ad.Registration_ID AND ad.death_date_time between '$fromDate' and '$toDate' and disease_caused_death ='$disease_caused_death' and TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= $end_age_death ORDER BY TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) ASC");
$count=1;
echo "<div id='greater_age' style='display:none;'>";
echo "<center>List Of Deceased Persons From: {$fromDate} To: {$toDate} With Age &ge; $end_age_death Years</center>";
echo "<table width='100%;' border='1' style='font-size:18px;border-collapse: collapse;' cellpadding=5 cellspacing=10>";
echo "<thead>
			<tr><th>SN</th><th>Patient Name</th><th>Age</th><th>Gender</th></tr>
		</thead>";
		if(mysqli_num_rows($select_patients) > 0){
while ($row=mysqli_fetch_assoc($select_patients)) {
	echo "<tr><td>".$count."</td><td><a href='javascript:void(0)' target='_blank' style='display:block;'>".$row['Patient_name']."</a></td><td>".$row['age']."</td><td>".strtoupper($row['Gender'])."</td></tr>";
	$count++;
}}else{
	echo "<tr><td colspan='4'><center><br><br><br><br><b>No Patient Found</b><br><br><br><br></center></td></tr>";
}
echo "</table>";

$deceased_object=json_encode($deceased_data);
echo "<br><button class='art-button-green' onclick='Preview_List(".$deceased_object.",\"greater\");'>Preview</button><br>";
echo "</div>";
echo "</fieldset>";

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
	function Preview_List(deceased_data,age_group){
		window.open('preview_deceased_patient_list.php?deceased_data='+encodeURI(window.btoa(JSON.stringify(deceased_data)))+'&age_group='+age_group, '_blank');
	}
 
</script>
<script language="javascript" type="text/javascript">
    function OpenNewTab(href) {
        document.getElementById('linkDynamic').href = href;
        document.getElementById('linkDynamic').click();
    }
</script>
