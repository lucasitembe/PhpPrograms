<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
	$temp = 1;
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
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

	
    echo '<center><table width =100% border=0>';
	
    echo '<tr id="thead"><td style="width:5%;"><b>SN</b></td>
		<td><b>PATIENT NAME</b></td>
		<td><b>PATIENT NO</b></td>
		<td><b>AGE</b></td>
		<td><b>GENDER</b></td>
		<td><b>PHONE NO</b></td>
		<td><b>REGISTERED DATE</b></td>
        </tr>';
    $Select_Patient = mysqli_query($conn,
            "select DISTINCT Patient_Name,Phone_Number, chk.Registration_ID, Date_Of_Birth, Gender,Phone_Number,Registration_Date_And_Time, Nurse_DateTime,bmi 
			from
			tbl_patient_registration reg,tbl_pre_operative_checklist chk,tbl_nurse n,
			tbl_admission ad
			where Patient_Name LIKE '%$Patient_Name%' AND 
			chk.Registration_ID=reg.Registration_ID AND n.Registration_ID = chk.Registration_ID AND ad.Registration_ID=chk.Registration_ID 
			ORDER BY Nurse_DateTime ASC ") or die(mysqli_error($conn));

		    
     while($row = mysqli_fetch_array($Select_Patient)){
	 //AGE FUNCTION
	 $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";
	   // if($age == 0){
		
		$date1 = new DateTime($Today);
		$date2 = new DateTime($row['Date_Of_Birth']);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
	 
	 
	echo "<tr><td id='thead'>".$temp."</td>";
	
	echo "<td><a href='Pre_OperativeCompleted.php?Registration_ID=".$row['Registration_ID']."&Nurse_DateTime=".$row['Nurse_DateTime']."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
	
	echo "<td><a href='Pre_OperativeCompleted.php?Registration_ID=".$row['Registration_ID']."&Nurse_DateTime=".$row['Nurse_DateTime']."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
	
	echo "<td><a href='Pre_OperativeCompleted.php?Registration_ID=".$row['Registration_ID']."&Nurse_DateTime=".$row['Nurse_DateTime']."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
	
	
	echo "<td><a href='Pre_OperativeCompleted.php?Registration_ID=".$row['Registration_ID']."&Nurse_DateTime=".$row['Nurse_DateTime']."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
	
	echo "<td><a href='Pre_OperativeCompleted.php?Registration_ID=".$row['Registration_ID']."&Nurse_DateTime=".$row['Nurse_DateTime']."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
	
	echo "<td><a href='Pre_OperativeCompleted.php?Registration_ID=".$row['Registration_ID']."&Nurse_DateTime=".$row['Nurse_DateTime']."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>".$row['Nurse_DateTime']."</a></td>";
	
   $temp++; 
    }
	echo "</tr>";
?></table></center>

