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
   
	//declare all category variables.......
	$Total_Age_Below_1_Month_Male = 0;
	$Total_Age_Below_1_Month_Female = 0;
	$Grand_Total_Age_Below_1_Month = 0;
	
	$Total_Age_Between_1_Month_But_Below_1_Year_Male = 0;
	$Total_Age_Between_1_Month_But_Below_1_Year_Female = 0;
	$Grand_Total_Age_Between_1_Month_But_Below_1_Year = 0;

	$Total_Age_Between_1_Year_But_Below_5_Year_Male = 0;
	$Total_Age_Between_1_Year_But_Below_5_Year_Female = 0;
	$Grand_Total_Age_Between_1_Year_But_Below_5_Year = 0;
	
	
	$Total_Five_Years_Or_Below_Sixty_Years_Male = 0;
	$Total_Five_Years_Or_Below_Sixty_Years_Female = 0;
	$Grand_Total_Five_Years_Or_Below_Sixty_Years = 0;
	
	$Total_Age_60_Years_And_Above_Male = 0;
	$Total_Age_60_Years_And_Above_Female = 0;
	$Grand_Total_Age_60_Years_And_Above = 0;

	$Total_Male = 0;
	$Total_Female = 0;
	$Grand_Total_Male = 0;
	$Grand_Total_Female = 0;
        $select_OPD='';
	

    $html.= '<table width="100%">
		<tr><td colspan=8><hr></td></tr>
		<tr>
			<td width="5%" style="text-align: center;"></td>
			<td style="text-align: center;"></td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr><td colspan="3" style="text-align: center;"><span style="font-size: x-small;">Umri chini ya mwezi 1</span></td></tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr><td colspan="3" style="text-align: center;"><span style="font-size: x-small;">Umri mwezi 1 hadi umri chini ya mwaka 1</span></td></tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr><td colspan="3" style="text-align: center;"><span style="font-size: x-small;">Umri mwaka 1 hadi umri chini ya miaka 5</span></td></tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr><td colspan="3" style="text-align: center;"><span style="font-size: x-small;">Umri miaka 5 hadi miaka chini ya 60</span></td></tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr><td colspan="3" style="text-align: center;"><span style="font-size: x-small;">Umri miaka 60 na kuendelea</span></td></tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr><td colspan="3" style="text-align: center;"><span style="font-size: x-small;">Jumla Kuu</span></td></tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width="5%" style="text-align: center;"><span style="font-size: x-small;">Na</span></td>
			<td style="text-align: center;"><span style="font-size: x-small;">Maelezo</span></td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;"><span style="font-size: x-small;">ME</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">KE</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">Jumla</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;"><span style="font-size: x-small;">ME</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">KE</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">Jumla</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;"><span style="font-size: x-small;">ME</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">KE</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">Jumla</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;"><span style="font-size: x-small;">ME</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">KE</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">Jumla</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;"><span style="font-size: x-small;">ME</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">KE</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">Jumla</span></td>
					</tr>
				</table>
			</td>
			<td>
				<table width="100%">
				<tr>
					<td style="text-align: center;"><span style="font-size: x-small;">ME</span></td>
					<td style="text-align: center;"><span style="font-size: x-small;">KE</span></td>
					<td style="text-align: center;"><span style="font-size: x-small;">Jumla</span></td>
				</tr>
				</table>
			</td></tr>
		<tr><td colspan=8><hr></td></tr>';
		


		//get total mahudhurio ya OPD
		$Total_OPD_Below_1_Month_Male = 0;
		$Total_OPD_Below_1_Month_Female = 0;
		$Grand_Total_OPD_Below_1_Month = 0;
		
		$Total_OPD_Between_1_Month_But_Below_1_Year_Male = 0;
		$Total_OPD_Between_1_Month_But_Below_1_Year_Female = 0;
		$Grand_Total_OPD_Between_1_Month_But_Below_1_Year = 0;

		$Total_OPD_Between_1_Year_But_Below_5_Year_Male = 0;
		$Total_OPD_Between_1_Year_But_Below_5_Year_Female = 0;
		$Grand_Total_OPD_Between_1_Year_But_Below_5_Year = 0;
		
		
		$Total_OPD_Five_Years_Or_Below_Sixty_Years_Male = 0;
		$Total_OPD_Five_Years_Or_Below_Sixty_Years_Female = 0;
		$Grand_Total__OPDFive_Years_Or_Below_Sixty_Years = 0;
		
		$Total_OPD_60_Years_And_Above_Male = 0;
		$Total_OPD_60_Years_And_Above_Female = 0;
		$Grand_Total_OPD_60_Years_And_Above = 0;

		$Total_OPD_Male = 0;
		$Total_OPD_Female = 0;
		$Grand_Total_OPD_Male = 0;
		$Grand_Total_OPD_Female = 0;

    if($branchID == 0){//no branch is selected
               if($regionID == 0){//if no region is selected  
            
             $select_Allqr="
                            SELECT * FROM tbl_patient_registration pr,tbl_check_in ci,tbl_sponsor sp 
                                                WHERE pr.Registration_ID=ci.Registration_ID
                                                    AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
                                                    AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                    AND pr.Sponsor_ID=sp.Sponsor_ID
                                                    AND pr.Sponsor_ID='$sponsorID'
                           
                                                    ";
                          $select_All= mysqli_query($conn,$select_Allqr) or die(mysqli_error($conn)); 
                              //echo $select_Allqr;
//           
    }
    else{//region is selected
        if($districtID == 0){//if no district is selected
            $select_Allqr = " SELECT * FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r,tbl_sponsor sp 
                                                    WHERE pr.Registration_Date=ci.Check_In_Date AND
                                                        pr.Registration_ID=ci.Registration_ID
                                                        AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
                                                        AND d.Region_ID='$regionID'
                                                        AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                        AND pr.Sponsor_ID=sp.Sponsor_ID
                                                        AND pr.Sponsor_ID='$sponsorID'
                                             "; 
            
              $select_All= mysqli_query($conn,$select_Allqr) or die(mysqli_error($conn)); 
          
        }else{//district is selected
            $select_Allqr = "SELECT * FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r,tbl_sponsor sp 
                                                    WHERE pr.Registration_Date=ci.Check_In_Date AND
                                                        pr.Registration_ID=ci.Registration_ID
                                                        AND pr.District_ID=d.District_ID
                                                        AND d.Region_ID=r.Region_ID
                                                        AND pr.District_ID='$districtID'
                                                        AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
							AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                        AND pr.Sponsor_ID=sp.Sponsor_ID 
                                                        AND pr.Sponsor_ID='$sponsorID'
                                            ";
               $select_All= mysqli_query($conn,$select_Allqr) or die(mysqli_error($conn)); 
              // echo $select_Allqr ;
          
        }//end else district is selected
     }//end else region is selected 
}else{//branch is selected
        if($regionID == 0){//if no region is selected  
            
             $select_Allqr="SELECT * FROM tbl_patient_registration pr,tbl_check_in ci ,tbl_sponsor sp 
                                                WHERE pr.Registration_Date=ci.Check_In_Date AND 
                                                    pr.Registration_ID=ci.Registration_ID
						    AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
						    AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                    AND pr.Sponsor_ID=sp.Sponsor_ID
                                                    AND pr.Sponsor_ID='$sponsorID'
                                                    ";
                          $select_All= mysqli_query($conn,$select_Allqr) or die(mysqli_error($conn)); 
                            //  echo $select_Allqr;
//           
    }
    else{//region is selected
        if($districtID == 0){//if no district is selected
            $select_Allqr = "SELECT * FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r,tbl_sponsor sp 
                                                WHERE pr.Registration_Date=ci.Check_In_Date AND 
                                                    pr.Registration_ID=ci.Registration_ID
                                                    AND pr.District_ID=d.District_ID
                                                    AND d.Region_ID=r.Region_ID
						    AND ci.Branch_ID='$branchID'
                                                    AND d.Region_ID='$regionID'
                                                    AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
						    AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                                    AND pr.Sponsor_ID=sp.Sponsor_ID 
                                                    AND pr.Sponsor_ID='$sponsorID'
                                             "; 
            
              $select_All= mysqli_query($conn,$select_Allqr) or die(mysqli_error($conn)); 
          
        }else{//district is selected
            $select_Allqr = " SELECT * FROM tbl_patient_registration pr,tbl_check_in ci,tbl_district d,tbl_regions r,tbl_sponsor sp 
                                       WHERE pr.Registration_Date=ci.Check_In_Date AND 
                                        pr.Registration_ID=ci.Registration_ID
                                        AND pr.District_ID=d.District_ID
                                        AND d.Region_ID=r.Region_ID
                                        AND ci.Branch_ID='$branchID'
                                        AND d.Region_ID='$regionID'
                                        AND pr.District_ID='$districtID'
                                        AND ci.Check_In_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
                                        AND pr.Date_Of_Birth BETWEEN DATE_ADD(ci.Check_In_Date, INTERVAL  -$ageTo  YEAR ) AND DATE_ADD(ci.Check_In_Date, INTERVAL -$ageFrom  year)
                                        AND pr.Sponsor_ID=sp.Sponsor_ID  
                                        AND pr.Sponsor_ID='$sponsorID'
                                            ";
               $select_All= mysqli_query($conn,$select_Allqr) or die(mysqli_error($conn)); 
          
        }//end else district is selected
     }//end else region is selected   
}//else if branch is selected
    
    
    
 $num_OPD = mysqli_num_rows($select_All);   
    if($num_OPD > 0){
			while ($pdata = mysqli_fetch_array($select_All)) {
				$Check_In_Date = $pdata['Check_In_Date'];
				$Date_Of_Birth = $pdata['Date_Of_Birth'];
				$Gender = $pdata['Gender'];
				$date1 = new DateTime($Check_In_Date);
				$date2 = new DateTime($Date_Of_Birth);
				$diff = $date1 -> diff($date2);
				$Years = $diff->y;
				$Months = $diff->m;
				$Days = $diff->d;
				

				//Chini Ya Mwezi mmoja
				if($Years == 0 && $Months == 0 && strtolower($Gender) == 'male'){
					$Total_OPD_Below_1_Month_Male++;
					$Grand_Total_OPD_Below_1_Month++;
					$Total_OPD_Male++;
				}

				if($Years == 0 && $Months == 0 && strtolower($Gender) == 'female'){
					$Total_OPD_Below_1_Month_Female++;
					$Grand_Total_OPD_Below_1_Month++;
					$Total_OPD_Female++;	
				}

				//Mwezi mmoja hadi Chini Ya Mwaka mmoja
				if($Years == 0 && $Months >= 1 && strtolower($Gender) == 'male'){
					$Total_OPD_Between_1_Month_But_Below_1_Year_Male++;
					$Grand_Total_OPD_Between_1_Month_But_Below_1_Year++;
					$Total_OPD_Male++;
				}

				if($Years == 0 && $Months >= 1 && strtolower($Gender) == 'female'){
					$Total_OPD_Between_1_Month_But_Below_1_Year_Female++;
					$Grand_Total_OPD_Between_1_Month_But_Below_1_Year++;
					$Total_OPD_Female++;
				}

				//Mwaka mmoja hadi Chini Ya Miaka Mitano
				if(($Years >=1 && $Years < 5) && strtolower($Gender)=='male'){
					$Total_OPD_Between_1_Year_But_Below_5_Year_Male++;
					$Grand_Total_OPD_Between_1_Year_But_Below_5_Year++;
					$Total_OPD_Male++;
				}

				if(($Years >= 1 && $Years < 5) && strtolower($Gender)=='female'){
					$Total_OPD_Between_1_Year_But_Below_5_Year_Female++;
					$Grand_Total_OPD_Between_1_Year_But_Below_5_Year++;
					$Total_OPD_Female++;
				}

				//Miaka 5 hadi chini ya miaka 60
				if(($Years >=5 && $Years < 60) && strtolower($Gender)=='male'){
					$Total_OPD_Five_Years_Or_Below_Sixty_Years_Male++;
					$Grand_Total__OPDFive_Years_Or_Below_Sixty_Years++;
					$Total_OPD_Male++;
				}

				if(($Years >=5 && $Years < 60) && strtolower($Gender) == 'female'){
					$Total_OPD_Five_Years_Or_Below_Sixty_Years_Female++;
					$Grand_Total__OPDFive_Years_Or_Below_Sixty_Years++;
					$Total_OPD_Female++;
				}

				//Miaka 60 na kuendelea
				if(($Years >= 60) && strtolower($Gender)=='male'){
					$Total_OPD_60_Years_And_Above_Male++;
					$Grand_Total_OPD_60_Years_And_Above++;
					$Total_OPD_Male++;
				}

				if(($Years >= 60) && strtolower($Gender)=='female'){
					$Total_OPD_60_Years_And_Above_Female++;
					$Grand_Total_OPD_60_Years_And_Above++;
					$Total_OPD_Female++;
				}
			}
		}  
	//display mahudhurio ya OPD

		$html.= '<tr>
			<td width="5%" style="text-align: center;"><span style="font-size: x-small;">1</span></td>
			<td><span style="font-size:large;">Mahudhurio yote</span></td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;"><span style="font-size: x-small;">'.$Total_OPD_Below_1_Month_Male .'</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">'.$Total_OPD_Below_1_Month_Female .'</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">'.$Grand_Total_OPD_Below_1_Month .'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;"><span style="font-size: x-small;">'.$Total_OPD_Between_1_Month_But_Below_1_Year_Male .'</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">'.$Total_OPD_Between_1_Month_But_Below_1_Year_Female .'</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">'.$Grand_Total_OPD_Between_1_Month_But_Below_1_Year .'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;"><span style="font-size: x-small;">'.$Total_OPD_Between_1_Year_But_Below_5_Year_Male .'</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">'.$Total_OPD_Between_1_Year_But_Below_5_Year_Female .'</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">'.$Grand_Total_OPD_Between_1_Year_But_Below_5_Year .'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;"><span style="font-size: x-small;">'.$Total_OPD_Five_Years_Or_Below_Sixty_Years_Male .'</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">'.$Total_OPD_Five_Years_Or_Below_Sixty_Years_Female .'</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">'.$Grand_Total__OPDFive_Years_Or_Below_Sixty_Years .'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;"><span style="font-size: x-small;">'.$Total_OPD_60_Years_And_Above_Male .'</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">'.$Total_OPD_60_Years_And_Above_Female .'</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">'.$Grand_Total_OPD_60_Years_And_Above .'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;"><span style="font-size: x-small;">'.$Total_OPD_Male .'</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">'.$Total_OPD_Female .'</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">'.($Total_OPD_Male + $Total_OPD_Female) .'</span></td>
					</tr>
				</table>
			</td>
		</tr>
  ';

$html.="<tr><td colspan='2' ><b>Prepared By:</b></td><td colspan='2'><b>$Employee_Name</b></td></tr>";
$html.="<tr><td colspan='2'><b>Printed By:</b></td><td colspan='2'><b>$Employee_Name</b></td></tr>";

            $html.="</table>
        </center>
</fieldset>";
//print PDF
include("MPDF/mpdf.php");
$mpdf=new mPDF('c','A4-L');
$mpdf->WriteHTML($html);
$mpdf->Output();
exit;
?>