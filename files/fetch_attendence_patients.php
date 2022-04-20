<?php

	@session_start();
	include("./includes/connection.php");
	$filter=" ";
	$filterIn=" ";
	$Registration_ID=$_POST['Registration_ID'];

	$Type_Of_patient = $_POST['Type_Of_patient'];
	$fromDate = $_POST['fromDate'];
	$toDate = $_POST['toDate'];
	$Sponsor_ID = $_POST['Sponsor_ID'];
	$given_date = $_POST['given_date'];
	$Type_Of_visit=$_POST['Type_Of_visit'];
	$agetype = mysqli_real_escape_string($conn, $_POST['agetype']);
	if(isset($_POST['ageFrom'])){
		$ageFrom = $_POST['ageFrom'];
	}else{
		$ageFrom = 0;
	}

	if(isset($_POST['ageTo'])){
		$ageTo = $_POST['ageTo'];
	}else{
		$ageTo = 0;
	}
	$filter=" AND TIMESTAMPDIFF($agetype ,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$ageFrom."' AND '".$ageTo."'";

	if($Sponsor_ID !="All"){    
		$filter = "AND sp.Sponsor_ID='$Sponsor_ID'";    
	}
	if($Type_Of_patient !="all"){
		
		$filter2 = "AND ck.Type_Of_Check_In='$Type_Of_patient'";
		
	}else{
		$filter2 ="";
	}
	if($Type_Of_visit!='all'){
		$filter3=" AND ck.visit_type='$Type_Of_visit' ";
		
	}else{
		$filter3="";
		
	}
 
    
   
      
    $select_patients=mysqli_query($conn,"select DISTINCT Visit_Date,pr.Registration_ID, pr.patient_name,pr.Date_Of_Birth,pr.Gender,pr.Phone_Number,pr.Emergence_Contact_Number,sp.Guarantor_Name,pr.Region,pr.District FROM tbl_patient_registration pr,tbl_sponsor sp,tbl_check_in ck WHERE ck.Registration_ID=pr.Registration_ID $filter AND sp.Sponsor_ID=pr.Sponsor_ID $filter2 AND ck.Visit_Date='$given_date' $filter3 AND ck.Check_In_Date_And_Time BETWEEN '$fromDate' and '$toDate' ORDER BY ck.Visit_Date ASC");  
        
  
    
   

echo "<br><fieldset style='background-color:white;'><legend>Type of Patients: ".$Type_Of_patient."</legend>";
echo "<div id='less_age'>";
echo "<center>List Of Patients attendence From:{$fromDate} To:{$toDate}</center>";
echo "<table width='100%;' border='1' style='font-size:18px;border-collapse: collapse;' cellpadding=5 cellspacing=10>";
echo "<thead>";
    
     
		echo"<tr><th>SN</th><th width='8%'>Reg No</th><th width='15%'>Patient Name</th><th width='13%'>Age</th><th width='13%'>Date of Birth</th><th width='10%'>Gender</th><th width='10%'>Phone Number</th><th width='10%'>Phone Relative</th><th width='10%'>Reginal</th><th width='15%'>District</th></tr>";
             
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
                
          
                  echo "<tr><td>".$count."</td><td style='text-align:center;'>".$Registration_ID."</td><td style='padding-left:10px;'><a href='javascript:void(0)' target='_blank' style='display:block;'>".ucwords(strtolower($row['patient_name']))."</a></td><td style='text-align:center;'>".$age."</td><td style='text-align:center;'>".ucwords(strtolower($row['Date_Of_Birth']))."</td><td  style='padding-left:10px;'>".ucfirst($row['Gender'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['Phone_Number'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['Emergence_Contact_Number'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['Region'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['District'])."</td></tr>";    
                
	
	$count++;
}}else{
	echo "<tr><td colspan='10'><center><br><br><br><br><b>No Patient Found</b><br><br><br><br></center></td></tr>";
}
echo "<tr><td colspan='9'></td><td colspan=''><a href='#' class='art-button-green' onclick='printviewpatientsattendence(\"$Registration_ID\",\"$Type_Of_patient\",\"$fromDate\",\"$toDate\",\"$Sponsor_ID\",\"$given_date\",\"$Type_Of_visit\");'  style='display:block;'>".$given_date.", ".date("l", strtotime($given_date))." IN PDF</a></td></tr></table>";

