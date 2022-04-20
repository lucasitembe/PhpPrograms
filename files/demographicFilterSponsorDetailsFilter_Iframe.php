
<?php
    include("./includes/connection.php");
    session_start();
?>
<center>
            <?php
		echo '<center><table width =100% border=0 style="background-color:white;">';
    echo "<tr id='thead'>
                <td style='width:5%'><b>SN</b></td>
                <td ><b>&nbsp;&nbsp;&nbsp;&nbsp;PATIENT NAME</b></td>
                <td style='text-align: left;' width=10%><b>PATIENT NO</b></td>
				<td style='text-align: left;' width=6%><b>GENDER</b></td>
				<td style='text-align: left;' width=20%><b>AGE</b></td>
                <td style='text-align: left;' width=10%><b>REGION</b></td>
				<td style='text-align: left;' width=10%><b>DISTRICT</b></td>
				<td style='text-align: left;' width=10%><b>REG DATE</b></td>
         </tr>";
    echo "<tr>
                <td colspan=8><hr></td></tr>";
                 $regionID=$_GET['Region_ID'];
                 $districtID=$_GET['District_ID'];
                 $ageFrom=$_GET['ageFrom'];
                 $ageTo=$_GET['ageTo'];
                 $Date_From=date('Y-m-d',strtotime($_GET['Date_From']));
                 $Date_To=date('Y-m-d',strtotime($_GET['Date_To']));
                 $sponsorID=$_GET['sponsorID'];
	         $gender=$_GET['gender'];
                if($regionID == 0){//if no region is selected  
                    if($gender == 'All'){//no gender is selected
			$select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr
                                                    WHERE Sponsor_ID='$sponsorID'
                                                    AND pr.Registration_Date BETWEEN '$Date_From' AND '$Date_To'
                                                    AND pr.Date_Of_Birth BETWEEN DATE_ADD(pr.Registration_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(pr.Registration_Date, INTERVAL -$ageFrom  year) ORDER BY Patient_Name,Registration_ID,Gender ASC
                                                    ") or die(mysqli_error($conn));
		    }else{//gender is selected
			$select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr
                                                    WHERE Sponsor_ID='$sponsorID'
						    AND Gender='$gender'
                                                    AND pr.Registration_Date BETWEEN '$Date_From' AND '$Date_To'
                                                    AND pr.Date_Of_Birth BETWEEN DATE_ADD(pr.Registration_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(pr.Registration_Date, INTERVAL -$ageFrom  year) ORDER BY Patient_Name,Registration_ID,Gender ASC
                                                    ") or die(mysqli_error($conn));
		    }
                 }
                else{//region is selected
                        if($districtID == 0){//if no district is selectd
                                          if($gender == 'All'){//no gender is selected
					    $select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_district d,tbl_regions r
                                                    WHERE Sponsor_ID='$sponsorID'
                                                    AND d.Region_ID=r.Region_ID
                                                    AND pr.District_ID=d.District_ID
                                                    AND d.Region_ID='$regionID'
                                                    AND pr.Registration_Date BETWEEN '$Date_From' AND '$Date_To'
                                                    AND pr.Date_Of_Birth BETWEEN DATE_ADD(pr.Registration_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(pr.Registration_Date, INTERVAL -$ageFrom  year) ORDER BY Patient_Name,Registration_ID,Gender ASC
                                                    ") or die(mysqli_error($conn));
					  }else{//gender is selected
					    $select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_district d,tbl_regions r
                                                    WHERE Sponsor_ID='$sponsorID'
                                                    AND d.Region_ID=r.Region_ID
                                                    AND pr.District_ID=d.District_ID
                                                    AND d.Region_ID='$regionID'
						    AND Gender='$gender'
                                                    AND pr.Registration_Date BETWEEN '$Date_From' AND '$Date_To'
                                                    AND pr.Date_Of_Birth BETWEEN DATE_ADD(pr.Registration_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(pr.Registration_Date, INTERVAL -$ageFrom  year) ORDER BY Patient_Name,Registration_ID,Gender ASC
                                                    ") or die(mysqli_error($conn));
					  }
                        }else{//district is selected
                                      if($gender == 'All'){//no gender is selected
					$select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_district d,tbl_regions r
                                                    WHERE Sponsor_ID='$sponsorID'
                                                    AND d.Region_ID=r.Region_ID
                                                    AND pr.District_ID=d.District_ID
                                                    AND d.Region_ID='$regionID'
                                                    AND d.District_ID='$districtID'
                                                    AND pr.Registration_Date BETWEEN '$Date_From' AND '$Date_To'
                                                    AND pr.Date_Of_Birth BETWEEN DATE_ADD(pr.Registration_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(pr.Registration_Date, INTERVAL -$ageFrom  year) ORDER BY Patient_Name,Registration_ID,Gender ASC
                                                    ") or die(mysqli_error($conn));
				      }else{//gender is selected
					$select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_district d,tbl_regions r
                                                    WHERE Sponsor_ID='$sponsorID'
                                                    AND d.Region_ID=r.Region_ID
                                                    AND pr.District_ID=d.District_ID
                                                    AND d.Region_ID='$regionID'
                                                    AND d.District_ID='$districtID'
						    AND Gender='$gender'
                                                    AND pr.Registration_Date BETWEEN '$Date_From' AND '$Date_To'
                                                    AND pr.Date_Of_Birth BETWEEN DATE_ADD(pr.Registration_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(pr.Registration_Date, INTERVAL -$ageFrom  year) ORDER BY Patient_Name,Registration_ID,Gender ASC
                                                    ") or die(mysqli_error($conn));
				      }
                        }
                    }
            if($select_patient){
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
                        $Registration_Date=$row['Registration_Date'];
                        
                        //these codes are here to determine the age of the patient
                        $date1 = new DateTime(date('Y-m-d'));
                        $date2 = new DateTime($dob);
                        $diff = $date1 -> diff($date2);
                        $age = $diff->y." Years, ";
                        $age .= $diff->m." Months, ";
                        $age .= $diff->d." Days";
                        
                        echo "<tr><td id='thead'>".($i+1)."</td>";
                        echo "<td >".ucwords(strtolower($patientName))."</td>";
						echo "<td >".$Registration_ID."</td>";
						 echo "<td >".$Gender."</td>";
                        echo "<td >".$age."</td>";
                        echo "<td >".$Region."</td>";
                        echo "<td >".$District."</td>";
						echo "<td >".$Registration_Date."</td>";
    }
}else{
                echo "<tr><td colspan='7' style='text-align:center;color: red;'>No patients for this sponsor</td></tr>";
            }
    
    
	    ?></table></center>
        </center>