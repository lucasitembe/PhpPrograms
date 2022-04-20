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
    echo '<tr ID="thead">	<td style="width:5%;"><b>SN</b></td>
				<td><b>PATIENT NAME</b></td>
				<td><b>PATIENT NO</b></td>
				 <td><b>GENDER</b></td>
				<td><b>AGE</b></td>
				 <td><b>SPONSOR</b></td>
                
				 <td><b>VISITED DATE</b></td>
                            </tr>';
    $select_Filtered_Patient = mysqli_query($conn,"select pr.Patient_Name,pr.Registration_ID,n.Nurse_DateTime,Date_Of_Birth,
				pr.Gender, n.Registration_ID,sp.Guarantor_Name
		from 
			tbl_patient_registration pr, tbl_sponsor sp,tbl_nurse n 
		where
		 pr.sponsor_id = sp.sponsor_id AND 
		 pr.Patient_Name like '%$Patient_Name%' AND
		 pr.Registration_ID = n.Registration_ID   ORDER BY Nurse_DateTime DESC") or die(mysqli_error($conn));   
		 
    while($row = mysqli_fetch_array($select_Filtered_Patient)){
	
	//AGE FUNCTION
	 $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";
	   // if($age == 0){
		
		$date1 = new DateTime($Today);
		$date2 = new DateTime($row['Date_Of_Birth']);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
		
      	  // echo "<tr><td id='thead'>".$temp."</td><td><a href='nurseform.php?Registration_ID=".$row['Registration_ID']."&Nurse_DateTime=".$row['Nurse_DateTime']."&NurseWorks=NurseWorksThisPage' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
		
		// echo "<td><a href='nurseform.php?Registration_ID=".$row['Registration_ID']."&Nurse_DateTime=".$row['Nurse_DateTime']."&NurseWorks=NurseWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
		
			
		// echo "<td><a href='nurseform.php?Registration_ID=".$row['Registration_ID']."&Nurse_DateTime=".$row['Nurse_DateTime']."&NurseWorks=NurseWorksThisPage' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
		
	
		
        // echo "<td><a href='nurseform.php?Registration_ID=".$row['Registration_ID']."&Nurse_DateTime=".$row['Nurse_DateTime']."&NurseWorks=NurseWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Guarantor_Name']."</a></td>";
        
        // echo "<td><a href='nurseform.php?Registration_ID=".$row['Registration_ID']."&Nurse_DateTime=".$row['Nurse_DateTime']."&NurseWorks=NurseWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
		
			// echo "<td><a href='nurseform.php?Registration_ID=".$row['Registration_ID']."&Nurse_DateTime=".$row['Nurse_DateTime']."&NurseWorks=NurseWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Nurse_DateTime']."</a></td>";
   
   // $temp++;
	// echo "</tr>";
     }   
?>
</table></center>