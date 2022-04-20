<?php
    include("./includes/connection.php");
    session_start();
?>


<?php
$html="<div align='center'><img src='branchBanner/branchBanner1.png'></div>";
$html.="<fieldset>";
		$Date_From=date('Y-m-d H:i:s',strtotime($_GET['Date_From']));
        $Date_To=date('Y-m-d H:i:s',strtotime($_GET['Date_To']));
		$ageFrom=$_GET['ageFrom'];
		$ageTo=$_GET['ageTo'];
		$sponsorID=$_GET['sponsorID'];
		
		
		
		$sponsorRow=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM tbl_sponsor WHERE Sponsor_ID='$sponsorID'"));
					      $sponsorName=$sponsorRow['Guarantor_Name'];
	 
         $html.="<div align='center'>".$sponsorName." PATIENT REVISITED FROM ".date('j F, Y H:i:s',strtotime($Date_From)). " TO ".date('j F, Y H:i:s',strtotime($Date_To))." </div>";   
		
		$html.="<center>";
            
		$html.= '<center><table width =100% border=0>';
		$html.= '<tr><td colspan="9"><hr></td></tr>';
		$html.= "<tr>
                <td width=3%><b>SN</b></td>
                <td style=''><b>NAME</b></td>
                <td style='text-align: left;' width=10%><b>PATIENT NO</b></td>
                <td style='text-align: left;' width=10%><b>REGION</b></td>
		<td style='text-align: left;' width=10%><b>DISTRICT</b></td>
		<td style='text-align: left;' width=10%><b>GENDER</b></td>
		<td style='text-align: left;' width=10%><b>AGE</b></td>
		<td style='text-align: left;' width=10%><b>REG DATE</b></td>
                <td style='text-align: left;' width=15%><b>CHECK IN</b></td>
         </tr>";
   
		$sponsorID=$_GET['sponsorID'];
                $Date_From=$_GET['Date_From'];
                $Date_To=$_GET['Date_To'];
		$branchID=$_GET['branchID'];
		$regionID=$_GET['Region_ID'];
		$districtID=$_GET['District_ID'];
		$ageFrom=$_GET['ageFrom'];
		$ageTo=$_GET['ageTo'];
    //run the query to select all data from the database according to the branch id
   $currentDate=date('Y-m-d');
    
    if($branchID == 0){//no branch is selected
                if($regionID == 0){//if no region is selected  
                         $select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci 
							WHERE pr.Registration_Date <> ci.Check_In_Date
							AND pr.Registration_ID=ci.Registration_ID
							AND .ci.Check_In_Date BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Sponsor_ID='$sponsorID'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
							") or die(mysqli_error($conn));               
                }
		else{//region is selected
		    if($districtID == 0){//if no district is selectd
			 $select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r 
							WHERE pr.Registration_Date <> ci.Check_In_Date
							AND pr.Registration_ID=ci.Registration_ID
							AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
                                                        AND d.Region_ID='$regionID'
							AND ci.check_In_Date BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Sponsor_ID='$sponsorID'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
							") or die(mysqli_error($conn));
            }else{//district is selected
		$select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r 
							WHERE pr.Registration_Date <> ci.Check_In_Date
							AND pr.Registration_ID=ci.Registration_ID
							AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
                                                        AND d.Region_ID='$regionID'
							AND pr.District_ID='$districtID'
							AND ci.check_In_Date BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Sponsor_ID='$sponsorID'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
							") or die(mysqli_error($conn));               
            }
        }    
}else{//branch is selected
        if($regionID == 0){//if no region is selected  
            $select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci 
							WHERE pr.Registration_Date <> ci.Check_In_Date
							AND pr.Registration_ID=ci.Registration_ID
							AND ci.Branch_ID='$branchID'
							AND ci.Check_In_Date BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Sponsor_ID='$sponsorID'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
							") or die(mysqli_error($conn));               
    }
    else{//region is selected
        if($districtID == 0){//if no district is selectd
            $select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r 
							WHERE pr.Registration_Date <> ci.Check_In_Date
							AND pr.Registration_ID=ci.Registration_ID
							AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
							AND ci.Branch_ID='$branchID'
                                                        AND d.Region_ID='$regionID'
							AND ci.check_In_Date BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Sponsor_ID='$sponsorID'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
							") or die(mysqli_error($conn));               
        }else{//district is selected
            $select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r 
							WHERE pr.Registration_Date <> ci.Check_In_Date
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
                $Visit_Date=$row['Check_In_Date_And_Time'];
                //these codes are here to determine the age of the patient
                $date1 = new DateTime(date('Y-m-d'));
                $date2 = new DateTime($dob);
                $diff = $date1 -> diff($date2);
                $age = $diff->y." Years ";
                //$age .= $diff->m." Months, ";
                //$age .= $diff->d." Days";
                
                $html.= "<tr><td>".($i+1)."</td>";
                $html.= "<td style='text-align:left; width:10%'>".ucwords(strtolower($patientName))."</td>";
                $html.= "<td style='text-align:left; width:10%'>".$Registration_ID."</td>";
                $html.= "<td style='text-align:left; width:10%'>".$Region."</td>";
                $html.= "<td style='text-align:left; width:10%'>".$District."</td>";
                $html.= "<td style='text-align:left; width:10%'>".$Gender."</td>";
                $html.= "<td style='text-align:left; width:15%'>".$age."</td>";
                $html.= "<td style='text-align:left; width:10%'>".$Registration_Date_And_Time."</td>";
                $html.= "<td style='text-align:left; width:10%'>".$Visit_Date."</td>";
        }
    }
    $html.="</table></center>
        </center>
</fieldset>";




include("MPDF/mpdf.php");
        $mpdf=new mPDF('c','A4-L'); 
        $mpdf->WriteHTML($html);
        $mpdf->Output();
        exit; 