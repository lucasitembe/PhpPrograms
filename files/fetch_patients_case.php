<?php

@session_start();
include("./includes/connection.php");
$filter=" ";
$filterIn=" ";
$Registration_ID=$_POST['Registration_ID'];

$Type_patient_case = $_POST['Type_patient_case'];
$fromDate = $_POST['fromDate'];
$toDate = $_POST['toDate'];
$Sponsor_ID = $_POST['Sponsor_ID'];
$given_date = $_POST['given_date'];

if($Sponsor_ID !="All"){
    
    $filter = "AND sp.Sponsor_ID='$Sponsor_ID'";
    
}
if($Type_patient_case !="all"){
    
    $filter2 = "AND cl.Type_of_patient_case='$Type_patient_case'";
    
}

if($doctors!='all'){
	$filter3="AND cl.employee_ID='$doctors'";
}


    
   
      
//       $select_patients=mysqli_query($conn,"select DISTINCT Visit_Date,pr.Registration_ID, pr.patient_name,pr.Date_Of_Birth,pr.Gender,pr.Phone_Number,pr.Emergence_Contact_Number,sp.Guarantor_Name,pr.Region,pr.District FROM tbl_patient_registration pr,tbl_sponsor sp,tbl_check_in ck WHERE ck.Registration_ID=pr.Registration_ID $filter AND sp.Sponsor_ID=pr.Sponsor_ID $filter2 AND ck.Visit_Date='$given_date' AND ck.Check_In_Date_And_Time BETWEEN '$fromDate' and '$toDate' ORDER BY ck.Visit_Date ASC");  
       
       $select_patients=mysqli_query($conn,"SELECT DISTINCT cl.Consultation_Date_And_Time,pr.Registration_ID, pr.patient_name,pr.Date_Of_Birth,pr.Gender,pr.Phone_Number,pr.Emergence_Contact_Number,pr.Region,pr.District FROM tbl_consultation cl,tbl_patient_registration pr,tbl_sponsor sp WHERE cl.Registration_ID=pr.Registration_ID AND sp.Sponsor_ID=pr.Sponsor_ID $filter AND cl.Consultation_Date_And_Time='$given_date' AND cl.Consultation_Date_And_Time BETWEEN '$fromDate' AND '$toDate' $filter2 ORDER BY cl.Consultation_Date_And_Time ASC") or die(mysqli_error($conn));
        
  
    
   

echo "<br><fieldset style='background-color:white;'><legend>Type of Patients: ".$Type_Of_patient."</legend>";
echo "<div id='less_age'>";
echo "<center>List Of Patients attendence From:{$fromDate} To:{$toDate}</center>";
echo "<table width='100%;' border='1' style='font-size:18px;border-collapse: collapse;' cellpadding=5 cellspacing=10>";
echo "<thead>";
    
     
		echo"<tr><th>SN</th><th>Reg No</th><th>Patient Name</th><th>Age</th><th>Date of Birth</th><th>Gender</th><th>Provision Diagnosis</th><th>diferential diagnosis</th><th>diagnosis</th></tr>";
             
 echo "</thead>";
 $count=1;
		if(mysqli_num_rows($select_patients) > 0){
while ($row=mysqli_fetch_assoc($select_patients)) {
                    $Registration_ID=$row['Registration_ID'];
    
     $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";
	   // if($age == 0){
		
		$date1 = new DateTime($Today);
		$date2 = new DateTime($row['Date_Of_Birth']);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
                
                $patient_name=$row['patient_name'];
                
//                echo $row['Hospital_Ward_Name'];
                
          
                  echo "<tr><td>".$count."</td><td style='text-align:center;'>".$Registration_ID."</td><td style='padding-left:10px;'><a href='javascript:void(0)' target='_blank' style='display:block;'>".ucwords(strtolower($row['patient_name']))."</a></td><td style='text-align:center;'>".$age."</td><td style='text-align:center;'>".ucwords(strtolower($row['Date_Of_Birth']))."</td><td  style='padding-left:10px;'>".ucfirst($row['Gender'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['provisional_diagnosis'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['diferential_diagnosis'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['diagnosis'])."</td></tr>";    
                
	
	$count++;
}}else{
	echo "<tr><td colspan='10'><center><br><br><br><br><b>No Patient Found</b><br><br><br><br></center></td></tr>";
}
echo "</table>";
//echo "<br><button class='art-button-green' onclick='Preview_Lists_of_patient_cases(\"$doctors\",\"$given_date\",\"$Sponsor_ID\",\"$fromDate\",\"$toDate\",\"$Type_patient_case\",\"$Registration_ID\");'>Preview In PDF</button>";
 ?>
<!--<script>
//function Preview_Lists_of_patient_cases(given_date,doctors,fromDate,toDate,report_category,start_age,end_age,ipd_time,patitent_type){
//		window.open('preview_admited_patients.php?Hospital_Ward_Name='+Hospital_Ward_Name+'&Hospital_Ward_ID='+Hospital_Ward_ID+'&fromDate='+fromDate+'&toDate='+toDate+'&report_category='+report_category+'&start_age='+start_age+'&end_age='+end_age+'&ipd_time='+ipd_time+'&patitent_type='+patitent_type, '_blank');
//	}
        
</script> -->