<?php
@session_start();
include("./includes/connection.php");
$filter = ' ';
$filterSub = ' ';
$filterDeathWard=' ';
@$fromDate =$_POST['fromDate'];
@$toDate=$_POST['toDate'];
@$Filter_Category=$_POST['Filter_Category'];
$death_ward=$_POST['death_ward'];
@$start_age_death=$_POST['start_age'];
@$end_age_death=$_POST['end_age'];
@$admittedfrom= $_POST['admittedfrom'];
@$agetype =$_POST['agetype'];
echo "<link rel='stylesheet' href='fixHeader.css'>";

if($death_ward!=='all'){
    $filterDeathWard =" AND ad.Hospital_Ward_ID='$death_ward'";
}
if($admittedfrom !='All' ){
	$filterDeathWard .=" AND admitted_from='$admittedfrom'";
}
// die($filterDeathWard);
if(isset($Filter_Category) && $Filter_Category=="yes"){
    $diseasesData=array();
    $sn=1;
	$total_male_death=0;
	$total_female_death=0;
	$grandTotal=0;
	
	
    $disease_caused_death_select=mysqli_query($conn,"SELECT DISTINCT dcd.disease_name from tbl_admission ad, tbl_patient_registration pr, tbl_disease_caused_death dcd where pr.Registration_ID=ad.Registration_ID and ad.Admision_ID=dcd.Admision_ID  AND pr.Registration_ID=dcd.Registration_ID  AND TIMESTAMPDIFF($agetype,Date_Of_Birth,CURDATE()) and ad.death_date_time BETWEEN '$fromDate' AND '$toDate' $filterDeathWard group by dcd.disease_name");

    echo "<div>";
        echo "<table width='100%;' style='font-size:15px;background-color:white;text-align:center;' class='fixTableHead'>";
            echo "<thead>";
            echo "<tr><th rowspan='2'>Diagnosis</th><th rowspan='2'>ICD</th><th colspan='2'>Number Of Death age From $start_age_death - $end_age_death</th ><th rowspan='2'>Total</th></tr>";
			
			echo "<tr><th>M</th><th>F</th></tr>";
            echo "</thead>";
            echo "<tbody>";
			$deathData=array();
			while($row=mysqli_fetch_assoc($disease_caused_death_select)){
				extract($row);
				$icd=($disease_name=='others')?"NIL":mysqli_fetch_assoc(mysqli_query($conn,"SELECT disease_code FROM tbl_disease WHERE disease_name='$disease_name'"))['disease_code'];
				//select less male
				$male_death=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(dcd.disease_name) AS total_death FROM tbl_disease_caused_death dcd, tbl_patient_registration pr, tbl_admission ad WHERE dcd.registration_ID=pr.registration_ID AND pr.Gender='Male' AND ad.Admision_ID=dcd.Admision_ID AND TIMESTAMPDIFF($agetype,Date_Of_Birth,CURDATE()) between $start_age_death  AND $end_age_death AND dcd.disease_name='$disease_name' $filterDeathWard"))['total_death'];
				//select less female
				$female_death=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(dcd.disease_name) AS total_death FROM tbl_disease_caused_death dcd, tbl_patient_registration pr, tbl_admission ad  WHERE dcd.registration_ID=pr.registration_ID AND pr.Gender='Female' AND ad.Admision_ID=dcd.Admision_ID AND TIMESTAMPDIFF($agetype,Date_Of_Birth,CURDATE()) between $start_age_death  AND $end_age_death AND dcd.disease_name='$disease_name' $filterDeathWard"))['total_death'];
				//select greater male
				echo "<tr><td>$disease_name</td><td>$icd</td><td>$male_death</td><td>$female_death</td><td>".($male_death + $female_death)."</td></tr>";
			$total_male_death += $male_death;
			$total_female_death += $female_death;
}
			echo "<tr><td colspan='9'><hr></td></tr>";
			echo "<tr><td colspan='2'>Total Death</td><td>{$total_male_death}</td><td>{$total_female_death}</td><td>".($total_female_death + $total_male_death)."</td></tr>";
			echo "<tr><td colspan='9'><hr></td></tr>";
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
}
?>