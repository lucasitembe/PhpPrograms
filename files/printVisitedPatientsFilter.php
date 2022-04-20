<?php
include("./includes/connection.php");
session_start();
$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
$Employee_Name=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID'"))['Employee_Name'];
?>
<?php
$html="<center>";
$html.="<img src='branchBanner/branchBanner1.png'>";
$html.="</center>";
$html.="<fieldset>";
$Date_From=date('Y-m-d H:i:s',strtotime($_GET['Date_From']));
$Date_To=date('Y-m-d H:i:s',strtotime($_GET['Date_To']));
$ageFrom=$_GET['ageFrom'];
$ageTo=$_GET['ageTo'];
   $html.=" <legend align=center><b>VISIT SUMMARY REPORT FROM ".date('j F, Y H:i:s',strtotime($Date_From))." TO ".date('j F, Y H:i:s',strtotime($Date_To))."</b></legend>";
$html.="<tr><td colspan='12'><hr></td></tr>"
?>
<?php
$html.= '<center><table width =100% border=0>';
$html.= "<tr>
                <td style='text-align:left;width:3%;border: 1px #ccc solid;'><b>SN</b></td>
                <td style='text-align:left;width:20%;border: 1px #ccc solid;'><b>SPONSOR NAME</b></td>
                <td style='text-align:center;width:20%;border: 1px #ccc solid;'><b>MALE</b></td>
                <td style='text-align:center;width:20%;border: 1px #ccc solid;'><b>FEMALE</b></td>
		<td style='text-align:center;width:3%;border: 1px #ccc solid;'><b>TOTAL</b></td>
         </tr>";
$html.= "<br>";
$html.= "<tr>
                <td colspan=5 style='border: 1px #ccc solid'><hr></td></tr>";
$branchID=$_GET['branchID'];
$regionID=$_GET['Region_ID'];
$districtID=$_GET['District_ID'];
$Date_From=date('Y-m-d H:i:s',strtotime($_GET['Date_From']));
$Date_To=date('Y-m-d H:i:s',strtotime($_GET['Date_To']));
$currentDate=date('Y-m-d H:i:s');
$ageFrom=$_GET['ageFrom'];
$ageTo=$_GET['ageTo'];

//The following are testing conditions to display the data according to filters
if($branchID == 0){//no branch is selected
    if($regionID == 0){//if no region is selected
        $select_demograph = mysqli_query($conn,"SELECT sp.Sponsor_ID,sp.Guarantor_Name,
                                                        (
                                                        SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci  
                                                        WHERE pr.Registration_ID=ci.Registration_ID
                                                            AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
                                                            AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                            AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Male'    
                                                        ) as male,
                                                        (
                                                        SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci    
                                                        WHERE pr.Registration_ID=ci.Registration_ID
                                                             AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
                                                             AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                             AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Female'    
                                                        ) as female
                                                    FROM tbl_sponsor sp ORDER BY sp.Sponsor_ID ASC"
        ) or die(mysqli_error($conn));
    }
    else{//region is selected
        if($districtID == 0){//if no district is selectd
            $select_demograph = mysqli_query($conn,"SELECT sp.Sponsor_ID,sp.Guarantor_Name,
                                                    (
                                                    SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r
                                                    WHERE pr.Registration_Date=ci.Check_In_Date AND
                                                        pr.Registration_ID=ci.Registration_ID
                                                        AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
                                                        AND d.Region_ID='$regionID'
                                                        AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                        AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Male'    
                                                    ) as male,
                                                    (
                                                    SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r    
                                                    WHERE pr.Registration_Date=ci.Check_In_Date AND
                                                        pr.Registration_ID=ci.Registration_ID
                                                        AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
                                                        AND d.Region_ID='$regionID'
                                                         AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
							 AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                         AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Female'    
                                                    ) as female
                                                FROM tbl_sponsor sp ORDER BY sp.Sponsor_ID ASC"
            ) or die(mysqli_error($conn));
        }else{//district is selected
            $select_demograph = mysqli_query($conn,"SELECT sp.Sponsor_ID,sp.Guarantor_Name,
                                                    (
                                                    SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r
                                                    WHERE pr.Registration_Date=ci.Check_In_Date AND
                                                        pr.Registration_ID=ci.Registration_ID
                                                        AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
                                                        AND pr.District_ID='$districtID'
                                                        AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                        AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Male'    
                                                    ) as male,
                                                    (
                                                    SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r    
                                                    WHERE pr.Registration_Date=ci.Check_In_Date AND 
                                                        pr.Registration_ID=ci.Registration_ID
                                                        AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
                                                        AND pr.District_ID='$districtID'
                                                         AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
							 AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                         AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Female'    
                                                    ) as female
                                                FROM tbl_sponsor sp ORDER BY sp.Sponsor_ID ASC"
            ) or die(mysqli_error($conn));
        }
    }
}else{//branch is selected
    if($regionID == 0){//if no region is selected
        $select_demograph = mysqli_query($conn,"SELECT sp.Sponsor_ID,sp.Guarantor_Name,
                                                (
                                                SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci  
                                                WHERE pr.Registration_Date=ci.Check_In_Date AND 
                                                    pr.Registration_ID=ci.Registration_ID
						    AND ci.Branch_ID=$branchID
                                                    AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
						    AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                    AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Male'    
                                                ) as male,
                                                (
                                                SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci    
                                                WHERE pr.Registration_Date=ci.Check_In_Date AND 
                                                    pr.Registration_ID=ci.Registration_ID
						    AND ci.Branch_ID=$branchID
                                                    AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
						    AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                    AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Female'    
                                                ) as female
                                            FROM tbl_sponsor sp ORDER BY sp.Sponsor_ID ASC"
        ) or die(mysqli_error($conn));
    }
    else{//region is selected
        if($districtID == 0){//if no district is selectd
            $select_demograph = mysqli_query($conn,"SELECT sp.Sponsor_ID,sp.Guarantor_Name,
                                                (
                                                SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r
                                                WHERE pr.Registration_Date=ci.Check_In_Date AND 
                                                    pr.Registration_ID=ci.Registration_ID
                                                    AND pr.District_ID=d.District_ID
                                                    AND d.Region_ID=r.Region_ID
						    AND ci.Branch_ID='$branchID'
                                                    AND d.Region_ID='$regionID'
                                                    AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
						    AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                    AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Male'    
                                                ) as male,
                                                (
                                                SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r    
                                                WHERE pr.Registration_Date=ci.Check_In_Date AND 
                                                    pr.Registration_ID=ci.Registration_ID
                                                    AND pr.District_ID=d.District_ID
                                                    AND d.Region_ID=r.Region_ID
						    AND ci.Branch_ID='$branchID'
                                                    AND d.Region_ID='$regionID'
                                                     AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
						     AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                     AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Female'    
                                                ) as female
                                            FROM tbl_sponsor sp ORDER BY sp.Sponsor_ID ASC"
                            ) or die(mysqli_error($conn));
        }else{//district is selected
            $select_demograph = mysqli_query($conn,"SELECT sp.Sponsor_ID,sp.Guarantor_Name,
                                                (
                                                SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r
                                                WHERE pr.Registration_Date=ci.Check_In_Date AND 
                                                    pr.Registration_ID=ci.Registration_ID
                                                    AND pr.District_ID=d.District_ID
                                                    AND d.Region_ID=r.Region_ID
						    AND ci.Branch_ID='$branchID'
                                                    AND d.Region_ID='$regionID'
                                                    AND pr.District_ID='$districtID'
                                                    AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
						    AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                    AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Male'    
                                                ) as male,
                                                (
                                                SELECT COUNT(Gender) FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r    
                                                WHERE pr.Registration_Date=ci.Check_In_Date AND 
                                                    pr.Registration_ID=ci.Registration_ID
                                                    AND pr.District_ID=d.District_ID
                                                    AND d.Region_ID=r.Region_ID
						    AND ci.Branch_ID='$branchID'
                                                    AND d.Region_ID='$regionID'
                                                    AND pr.District_ID='$districtID'
                                                     AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
						     AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                     AND pr.Sponsor_ID=sp.Sponsor_ID AND  pr.Gender='Female'    
                                                ) as female
                                            FROM tbl_sponsor sp ORDER BY sp.Sponsor_ID ASC"
            ) or die(mysqli_error($conn));
        }//end else district is selected
    }//end else region is selected
}//else if branch is selected


$total_Male=0;
$total_Female=0;
$res=mysqli_num_rows($select_demograph);
for($i=0;$i<$res;$i++){
    $row=mysqli_fetch_array($select_demograph);
    //return rows
    $sponsorID=$row['Sponsor_ID'];
    $sponsorName=$row['Guarantor_Name'];
    $male=$row['male'];
    $female=$row['female'];
    $html.= "<tr><td style='text-align:left;width:2%;border: 1px #ccc solid;'>".($i+1)."</td>";
    $html.= "<td style='text-align:left;width:2%;border: 1px #ccc solid;'>".$row['Guarantor_Name']."</td>";
    $total_Male=$total_Male + $male;
    $html.= "<td style='text-align:center;width:2%;border: 1px #ccc solid;'>".number_format($male)."</td>";
    $total_Female=$total_Female + $female;
    $html.= "<td style='text-align:center;width:2%;border: 1px #ccc solid;'>".number_format($female)."</td>";
    $total=$male+$female;
    $html.= "<td style='text-align:center;width:2%;border: 1px #ccc solid;'>".number_format($total)."</td>";
}//end for loop

$html.= "<tr>
                <td colspan=5 style='border: 1px #ccc solid'><hr></td></tr>";
$html.= "<tr><td colspan=2 style='text-align:right;width:2%;border: 1px #ccc solid;'><b>&nbsp;&nbsp;TOTAL</b></td>";
$html.= "<td style='text-align:center;width:2%;border: 1px #ccc solid;'><b>".number_format($total_Male)."</b></td>";
$html.= "<td style='text-align:center;width:2%;border: 1px #ccc solid;'><b>".number_format($total_Female)."</b></td>";
$total_Male_Female=$total_Male+$total_Female;
$html.= "<td style='text-align:center;width:2%;border: 1px #ccc solid;'><b>".number_format($total_Male_Female)."</b></td></tr>";

$html.= "<tr>
                <td colspan=5 style='border: 1px #ccc solid'><hr></td></tr>";

$html.="<tr><td><b>Prepared By:</b></td><td><b>$Employee_Name</b></td></tr>";
$html.="<tr><td><b>Printed By:</b></td><td><b>$Employee_Name</b></td></tr>";

            $html.="</table></center>";

//echo $html;
//print PDF
include("MPDF/mpdf.php");
$mpdf=new mPDF();
$mpdf->WriteHTML($html);
$mpdf->Output();
exit;
?>