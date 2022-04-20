<?php
include("./includes/connection.php");
session_start();
$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
$Employee_Name=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID'"))['Employee_Name'];
?>
<?php
$html="<div align=center>";
$html.="<img src='branchBanner/branchBanner1.png'>";
$html.="</div>";
//$html.="<fieldset>";
$Date_From=date('Y-m-d H:i:s',strtotime($_GET['Date_From']));
$Date_To=date('Y-m-d H:i:s',strtotime($_GET['Date_To']));
$ageFrom=$_GET['ageFrom'];
$ageTo=$_GET['ageTo'];
$sponsorID=$_GET['sponsorID'];
$sponsorRow=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM tbl_sponsor WHERE Sponsor_ID='$sponsorID'"));
$sponsorName=$sponsorRow['Guarantor_Name'];$sponsorRow=mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM tbl_sponsor WHERE Sponsor_ID='$sponsorID'"));
$sponsorName=$sponsorRow['Guarantor_Name'];
$html.=" <div align=center><b>PATIENT VISIT FROM ".date('j F,   Y H:i:s',strtotime($Date_From))." TO ".date('j F,   Y H:i:s',strtotime($Date_To))." FOR ".$sponsorName." SPONSOR</b></div>";
$html.="<tr><td colspan='12'><hr></td></tr>"
?>
<?php

      $html.="<center>";
            $html.= '<table width =100% border=0>';
            $html.= "<tr>
                <td ><b>SN</b></td>
                <td ><b>PATIENT NAME</b></td>
                <td ><b>PATIENT NO</b></td>
				 <td ><b>GENDER</b></td>
				<td ><b>AGE</b></td>
                <td ><b>PHONE NO</b></td>
               <td ><b>MEMBER NO</b></td>
                <td ><b>CATEGORY</b></td>
				<td ><b>WARD</b></td>
				<td ><b>DISTRICT</b></td>
				<td ><b>REG DATE</b></td>  
         </tr>";
            $html.= "<tr>
                <td colspan=4></td></tr>";
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
							WHERE pr.Registration_Date=ci.Check_In_Date
							AND pr.Registration_ID=ci.Registration_ID
							AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Sponsor_ID='$sponsorID'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
							") or die(mysqli_error($conn));
                }
                else{//region is selected
                    if($districtID == 0){//if no district is selectd
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
                    }else{//district is selected
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
                    }
                }
            }else{//branch is selected
                if($regionID == 0){//if no region is selected
                    $select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci
							WHERE pr.Registration_Date=ci.Check_In_Date
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
							WHERE pr.Registration_Date=ci.Check_In_Date
							AND pr.Registration_ID=ci.Registration_ID
							AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
							AND ci.Branch_ID='$branchID'
                                                        AND d.Region_ID='$regionID'
							AND ci.check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Sponsor_ID='$sponsorID'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
							") or die(mysqli_error($conn));
                    }else{//district is selected
                        $select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r
							WHERE pr.Registration_Date=ci.Check_In_Date
							AND pr.Registration_ID=ci.Registration_ID
							AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
							AND ci.Branch_ID='$branchID'
                                                        AND d.Region_ID='$regionID'
							AND d.District_ID='$districtID'
							AND ci.check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
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
                $Phone_Number=$row['Phone_Number'];
                $VoteNo=$row['Employee_Vote_Number'];
                $Ward=$row['Ward'];
                $Member_Number=$row['Member_Number'];
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
                $age = $diff->y." Years, ";
                $age .= $diff->m." Months, ";
                $age .= $diff->d." Days";
                $Member_Numbers=substr($Member_Number,0,2);
                if($Member_Number !='01'){
                    $Member_Cat='Dependant';
                }else{
                    $Member_Cat='Worker';
                }

                $html.= "<tr><td>".($i+1)."</td>";
                $html.= "<td>".ucwords(strtolower($patientName))."</td>";
                $html.= "<td>".$Registration_ID."</td>";
				$html.= "<td>".$Gender."</td>";
				$html.= "<td >".$age."</td>";
                $html.= "<td>".$Phone_Number."</td>";
                //$html.= "<td style='text-align:left;'>".$VoteNo."</td>";
                //$html.= "<td >".$Ward."</td>";
                $html.= "<td>".$Member_Number."</td>";
                $html.= "<td>".$Member_Cat."</td>";
               
                //$html.= "<td >".$Region."</td>";
				$html.= "<td >".$Ward."</td>";
                $html.= "<td>".$District."</td>";
                
                $html.= "<td>".substr($Registration_Date_And_Time,0,10)."</td></tr>";
               // $html.= "<td style='text-align:left; width:10%'>".$Visit_Date."</td>";
            }
            }
$html.="<tr><td colspan='2' ><b>Prepared By:</b></td><td colspan='2'><b>$Employee_Name</b></td></tr>";
$html.="<tr><td colspan='2'><b>Printed By:</b></td><td colspan='2'><b>$Employee_Name</b></td></tr>";

            $html.="</table><center>";
//print PDF
include("MPDF/mpdf.php");
$mpdf=new mPDF('c','A4-L');
$mpdf->WriteHTML($html);
$mpdf->Output();
exit;
?>