<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    session_start();
?>
<center>
            <?php
		echo '<center><table width =100% border=0>';
    echo "<tr id='thead'>
                <td style='text-align:left; width:3%'><b>SN</b></td>
                <td style=''><b>&nbsp;&nbsp;&nbsp;&nbsp;PATIENT NAME</b></td>
                <td style='text-align: left;' width=10%><b>PATIENT NUMBER</b></td>
                <td style='text-align: left;' width=10%><b>REGION</b></td>
		<td style='text-align: left;' width=10%><b>DISTRICT</b></td>
		<td style='text-align: left;' width=10%><b>GENDER</b></td>
		<td style='text-align: left;' width=10%><b>AGE</b></td>
		<td style='text-align: left;' width=10%><b>REGISTRATION DATE</b></td>
         </tr>";
    echo "<tr>
                <td colspan=4></td></tr>";
		$regionID=$_GET['Region_ID'];
		$districtID=$_GET['District_ID'];
		$ageFrom=$_GET['ageFrom'];
		$ageTo=$_GET['ageTo'];
		$Date_From=$_GET['Date_From'];
		$Date_To=$_GET['Date_To'];
		$sponsorID=$_GET['sponsorID'];
		$gender=$_GET['gender'];
		
		
		if($regionID == 0){//no region is selected
				   if($gender == 'All'){//no gender is selected
					$select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration
								 WHERE Sponsor_ID='$sponsorID'
								 AND Registration_Date BETWEEN '$Date_From' AND '$Date_To'
								 AND Date_Of_Birth BETWEEN DATE_ADD(Registration_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(Registration_Date, INTERVAL -$ageFrom  year)
								 ")
				   or die(mysqli_error($conn));
				   }else{//gender is selected
					$select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr
								 WHERE Sponsor_ID='$sponsorID'
								 AND Gender='$gender'
								 AND pr.Registration_Date BETWEEN '$Date_From' AND '$Date_To'
								 AND pr.Date_Of_Birth BETWEEN DATE_ADD(pr.Registration_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(pr.Registration_Date, INTERVAL -$ageFrom  year)
								 ")
				   or die(mysqli_error($conn));
				   }
			      }else{//region is selected
				   if($districtID == 0){//no district is selected
					if($gender == 'All'){//no gender is selected
					     $select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_district d,tbl_regions r
									   WHERE pr.District_ID=d.District_ID
									   AND d.Region_ID=r.Region_ID
									   AND r.Region_ID='$regionID'
									   AND Sponsor_ID='$sponsorID'
									   AND pr.Registration_Date BETWEEN '$Date_From' AND '$Date_To'
									   AND pr.Date_Of_Birth BETWEEN DATE_ADD(pr.Registration_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(pr.Registration_Date, INTERVAL -$ageFrom  year)
									   ")
					     or die(mysqli_error($conn));
					}else{//gender is selected
					     $select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_district d,tbl_regions r
									   WHERE pr.District_ID=d.District_ID
									   AND d.Region_ID=r.Region_ID
									   AND r.Region_ID='$regionID'
									   AND Sponsor_ID='$sponsorID'
									   AND Gender='$gender'
									   AND pr.Registration_Date BETWEEN '$Date_From' AND '$Date_To'
									   AND pr.Date_Of_Birth BETWEEN DATE_ADD(pr.Registration_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(pr.Registration_Date, INTERVAL -$ageFrom  year)
									   ")
					     or die(mysqli_query($conn,));
					}
				   }else{//district is selected
					if($gender == 'All'){//no gender selected
					     $select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_district d,tbl_regions r
									   WHERE pr.District_ID=d.District_ID
									   AND d.Region_ID=r.Region_ID
									   AND pr.District_ID='$districtID'
									   AND Sponsor_ID='$sponsorID'
									   AND pr.Registration_Date BETWEEN '$Date_From' AND '$Date_To'
									   AND pr.Date_Of_Birth BETWEEN DATE_ADD(pr.Registration_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(pr.Registration_Date, INTERVAL -$ageFrom  year)
									   ")
					     or die(mysqli_error($conn));
					}else{//gender is selected
					     $select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_district d,tbl_regions r
									   WHERE pr.District_ID=d.District_ID
									   AND d.Region_ID=r.Region_ID
									   AND pr.District_ID='$districtID'
									   AND Sponsor_ID='$sponsorID'
									   AND Gender='$gender'
									   AND pr.Registration_Date BETWEEN '$Date_From' AND '$Date_To'
									   AND pr.Date_Of_Birth BETWEEN DATE_ADD(pr.Registration_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(pr.Registration_Date, INTERVAL -$ageFrom  year)
									   ")
					     or die(mysqli_error($conn));
					}
				   }
			      }
		
		
                
    $res=mysqli_num_rows($select_patient);
    for($i=0;$i<$res;$i++){
	$row=mysqli_fetch_array($select_patient);
	//return rows
	$registration_ID=$row['Registration_ID'];
	$patientName=$row['Patient_Name'];
	$Registration_ID=$row['Registration_ID'];
	$Region=$row['Region'];
	$District=$row['District'];
	$Gender=$row['Gender'];
	$dob=$row['Date_Of_Birth'];
	$Registration_Date_And_Time=$row['Registration_Date_And_Time'];
	
	//these codes are here to determine the age of the patient
	$date1 = new DateTime(date('Y-m-d'));
	$date2 = new DateTime($dob);
	$diff = $date1 -> diff($date2);
	$age = $diff->y." Years, ";
	$age .= $diff->m." Months, ";
	$age .= $diff->d." Days";
	
	echo "<tr><td style='text-align:left; width:3%'>".($i+1)."</td>";
        echo "<td style='text-align:left; width:10%'>".$patientName."</td>";
	echo "<td style='text-align:left; width:10%'>".$Registration_ID."</td>";
	echo "<td style='text-align:left; width:10%'>".$Region."</td>";
	echo "<td style='text-align:left; width:10%'>".$District."</td>";
	echo "<td style='text-align:left; width:10%'>".$Gender."</td>";
	echo "<td style='text-align:left; width:15%'>".$age."</td>";
	echo "<td style='text-align:left; width:10%'>".$Registration_Date_And_Time."</td>";
    }
	    ?></table></center>
        </center>