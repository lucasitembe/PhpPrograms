<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    session_start();
?>
<center>
<?php
		echo '<center><table width =100% border=0>';
    echo "<tr id='thead'>
                <td style='text-align:center;width:3%;'><b>SN</b></td>
                <td style=''><b>&nbsp;&nbsp;&nbsp;&nbsp;PATIENT NAME</b></td>
                <td style='text-align: left;' width=10%><b>PATIENT NUMBER</b></td>
                <td style='text-align: left;' width=10%><b>REGION</b></td>
		<td style='text-align: left;' width=10%><b>DISTRICT</b></td>
		<td style='text-align: left;' width=10%><b>GENDER</b></td>
		<td style='text-align: left;' width=10%><b>AGE</b></td>
		<td style='text-align: left;' width=10%><b>REGISTRATION DATE</b></td>
                <td style='text-align: left;' width=10%><b>CHECK IN DATE</b></td>
         </tr>";
    echo "<tr>
                <td colspan=4></td></tr>";
                
                
		if(isset($_GET)){
		    $branchID=$_GET['branchID'];
		    $regionID=$_GET['Region_ID'];
		    $districtID=$_GET['District_ID'];
		    $Date_From=date('Y-m-d',strtotime($_GET['Date_From']));
		    $Date_To=date('Y-m-d',strtotime($_GET['Date_To']));
		    $sponsorID=$_GET['sponsorID'];
		    $currentDate=date('Y-m-d');
		    $ageFrom=$_GET['ageFrom'];
		    $ageTo=$_GET['ageTo'];
		    $gender=$_GET['gender'];
		    }else{
			$branchID='';
		    $regionID='';
		    $districtID='';
		    $Date_From='';
		    $Date_To='';
		    $sponsorID='';
		    $currentDate='';
		    $ageFrom='';
		    $ageTo='';
		    $gender='';
		}
            //The following are testing conditions to display the data according to filters
	    if($branchID == 0){//no branch is selected
                if($regionID == 0){//if no region is selected  
                         if($gender == 'All'){//no gender is selected
			    $select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci 
							WHERE pr.Registration_Date=ci.Check_In_Date
							AND pr.Registration_ID=ci.Registration_ID
							AND ci.Check_In_Date BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Sponsor_ID='$sponsorID'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
							") or die(mysqli_error($conn));
			 }else{//gender is selected
			    $select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci 
							WHERE pr.Registration_Date=ci.Check_In_Date
							AND pr.Registration_ID=ci.Registration_ID
							AND ci.Check_In_Date BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Sponsor_ID='$sponsorID'
							AND pr.Gender='$gender'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
							") or die(mysqli_error($conn));
			 }
                }
		else{//region is selected
		    if($districtID == 0){//if no district is selectd
			 if($gender == 'All'){
			    $select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r 
							WHERE pr.Registration_Date=ci.Check_In_Date
							AND pr.Registration_ID=ci.Registration_ID
							AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
                                                        AND d.Region_ID='$regionID'
							AND ci.check_In_Date BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Sponsor_ID='$sponsorID'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
							") or die(mysqli_error($conn));
			 }else{//gender is selected
			    $select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r 
							WHERE pr.Registration_Date=ci.Check_In_Date
							AND pr.Registration_ID=ci.Registration_ID
							AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
                                                        AND d.Region_ID='$regionID'
							AND ci.check_In_Date BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Sponsor_ID='$sponsorID'
							AND pr.Gender='$gender'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
							") or die(mysqli_error($conn));
			 }
            }else{//district is selected
		if($gender == 'All'){//gender is selected
		    $select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r 
							WHERE pr.Registration_Date=ci.Check_In_Date
							AND pr.Registration_ID=ci.Registration_ID
							AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
                                                        AND d.Region_ID='$regionID'
							AND pr.District_ID='$districtID'
							AND ci.check_In_Date BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Sponsor_ID='$sponsorID'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
							") or die(mysqli_error($conn));
		}else{//no gender is selected
		    $select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r 
							WHERE pr.Registration_Date=ci.Check_In_Date
							AND pr.Registration_ID=ci.Registration_ID
							AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
                                                        AND d.Region_ID='$regionID'
							AND pr.District_ID='$districtID'
							AND ci.check_In_Date BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Sponsor_ID='$sponsorID'
							AND pr.Gender='$gender'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
							") or die(mysqli_error($conn));
		}
            }
        }    
}else{//branch is selected
        if($regionID == 0){//if no region is selected  
            if($gender == 'All'){//no gender is selected
		$select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci 
							WHERE pr.Registration_Date=ci.Check_In_Date
							AND pr.Registration_ID=ci.Registration_ID
							AND ci.Branch_ID='$branchID'
							AND ci.Check_In_Date BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Sponsor_ID='$sponsorID'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
							") or die(mysqli_error($conn));
	    }else{//gender is selected
		$select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci 
							WHERE pr.Registration_Date=ci.Check_In_Date
							AND pr.Registration_ID=ci.Registration_ID
							AND ci.Branch_ID='$branchID'
							AND ci.Check_In_Date BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Sponsor_ID='$sponsorID'
							AND pr.Gender='$gender'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
							") or die(mysqli_error($conn));
	    }
    }
    else{//region is selected
        if($districtID == 0){//if no district is selectd
            if($gender == 'All'){//no gender is selected
		$select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r 
							WHERE pr.Registration_Date=ci.Check_In_Date
							AND pr.Registration_ID=ci.Registration_ID
							AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
							AND ci.Branch_ID='$branchID'
                                                        AND d.Region_ID='$regionID'
							AND ci.check_In_Date BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Sponsor_ID='$sponsorID'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
							") or die(mysqli_error($conn));
	    }else{//gender is selected
		$select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r 
							WHERE pr.Registration_Date=ci.Check_In_Date
							AND pr.Registration_ID=ci.Registration_ID
							AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
							AND ci.Branch_ID='$branchID'
                                                        AND d.Region_ID='$regionID'
							AND ci.check_In_Date BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Sponsor_ID='$sponsorID'
							AND pr.Gender='$gender'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
							") or die(mysqli_error($conn));
	    }
        }else{//district is selected
            if($gender == 'All'){//no gender is selected
		$select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r 
							WHERE pr.Registration_Date=ci.Check_In_Date
							AND pr.Registration_ID=ci.Registration_ID
							AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
							AND ci.Branch_ID='$branchID'
                                                        AND d.Region_ID='$regionID'
							AND d.District_ID='$districtID'
							AND ci.check_In_Date BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Sponsor_ID='$sponsorID'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
							") or die(mysqli_error($conn));
	    }else{
		$select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r 
							WHERE pr.Registration_Date=ci.Check_In_Date
							AND pr.Registration_ID=ci.Registration_ID
							AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
							AND ci.Branch_ID='$branchID'
                                                        AND d.Region_ID='$regionID'
							AND d.District_ID='$districtID'
							AND ci.check_In_Date BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Sponsor_ID='$sponsorID'
							AND pr.Gender='$gender'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
							") or die(mysqli_error($conn));
	    }
        }//end else district is selected
     }//end else region is selected   
}//else if branch is selected
   
   
if(mysqli_num_rows($select_patient) > 0){
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
                $Visit_Date=$row['Visit_Date'];
                //these codes are here to determine the age of the patient
                $date1 = new DateTime(date('Y-m-d'));
                $date2 = new DateTime($dob);
                $diff = $date1 -> diff($date2);
                $age = $diff->y." Years, ";
                $age .= $diff->m." Months, ";
                $age .= $diff->d." Days";
                
                echo "<tr><td>".($i+1)."</td>";
                echo "<td style='text-align:left; width:10%'>".$patientName."</td>";
                echo "<td style='text-align:left; width:10%'>".$Registration_ID."</td>";
                echo "<td style='text-align:left; width:10%'>".$Region."</td>";
                echo "<td style='text-align:left; width:10%'>".$District."</td>";
                echo "<td style='text-align:left; width:10%'>".$Gender."</td>";
                echo "<td style='text-align:left; width:15%'>".$age."</td>";
                echo "<td style='text-align:left; width:10%'>".$Registration_Date_And_Time."</td>";
                echo "<td style='text-align:left; width:10%'>".$Visit_Date."</td>";
        }
//        echo "<form action='printPatientsVisitedPreviousFilterDays.php?PrintPatientsVisitedPreviousThisPage' method='POST'>
//		<tr>
//		    <td style='border:0'><input type='submit' name='printPatientsVisitedToday' value='Print'/>
//                        <input type='hidden' name='Date_From' value='$Date_From'/>
//                        <input type='hidden' name='Date_To' value='$Date_To'/>
//                    </td>
//		</tr>
//	    </form>";
    }
    else{
        echo "<tr><td colspan='8' style='text-align:center'><b style='color:red'>No patient visited on the dates specified.</b></td></tr>";
    }
	    ?></table></center>
        </center>