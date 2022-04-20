<?php
@session_start();
include("./includes/connection.php");
	$Hospital_Ward_Name = $_GET['Hospital_Ward_Name'];
//	$disease_data = json_decode(base64_decode($_GET['disease_data']),true);
	$Hospital_Ward_ID = $_GET['Hospital_Ward_ID'];
	$fromDate = $_GET['fromDate'];
	$toDate = $_GET['toDate'];
	$start_age = $_GET['start_age'];
	$end_age = $_GET['end_age'];
	$report_category = $_GET['report_category'];
	$ipd_time = $_GET['ipd_time'];
	$patitent_type = $_GET['patitent_type'];
	$Sponsor_ID = $_GET['Sponsor_ID'];
	$filter= ' ';
	$filterIn= ' ';
        
        
        $filetersponsor="";
    
       if($Sponsor_ID != 0){
//             echo "hapa inafika sana tu";
            $filetersponsor="AND pr.Sponsor_ID='$Sponsor_ID'";
           
       }else{
//           echo "ndo nhje ";
//          echo  $Sponsor_ID; 
          $filetersponsor=""; 
       }
        
    $filterPatientype="";
	if($patitent_type!='all'){
       $filterPatientype="AND ad.New_return_admission='$patitent_type'";
    }else{
        $filterPatientype="";
    }
        
     $filterreportcategory="";
	if($report_category!='admission'){
            if($report_category=='Death'){
  
                 $filterreportcategory="AND dr.discharge_condition='dead'";
            }else{
                 $filterreportcategory="AND dr.discharge_condition='alive'";
            }
      
    }else {
        $filterreportcategory ="";
    }
    
     $fileterabsconded="";
    
       if($report_category=='Absconded'){
           
            $fileterabsconded="AND dr.Discharge_Reason='Absconded'";
           
       }else{
          $fileterabsconded=""; 
       }
  
  
  $htm  = "<table width ='100%' height = '30px'>";
    $htm .= "<tr> <td> <img src='./branchBanner/branchBanner.png' width=100%> </td> </tr>";
    $htm .= "<tr>";
    $htm .= "<td style='text-align: center;'><h5> Patients List From ".$fromDate." To ".$toDate." With Age Between ".$start_age." And ".$end_age."  $ipd_time</h5></td>";
    $htm .= "</tr>";
    $htm .= "</table><br/>";
//\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

  
 $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$age ='';
    }
  
//  if($report_category=='admission'){
//      
//     $select_patients=mysqli_query($conn,"select pr.Registration_ID, pr.patient_name,pr.Date_Of_Birth,pr.Gender,ad.Admission_Date_Time FROM tbl_admission ad,tbl_patient_registration pr WHERE ad.Registration_ID =pr.Registration_ID AND ad.Admission_Status='Admitted' $filterPatientype AND ad.Hospital_Ward_ID=$Hospital_Ward_ID AND ad.Admission_Date_Time BETWEEN '$fromDate' and '$toDate' AND TIMESTAMPDIFF($ipd_time,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$start_age."' AND '".$end_age."' ORDER BY ad.Admision_ID ASC");  
//        
//    }else {
//        
//     $select_patients=mysqli_query($conn,"select pr.Registration_ID, pr.patient_name,pr.Date_Of_Birth,pr.Gender,ad.Admission_Date_Time FROM tbl_admission ad,tbl_patient_registration pr,tbl_discharge_reason dr WHERE ad.Registration_ID =pr.Registration_ID AND ad.Discharge_Reason_ID=dr.Discharge_Reason_ID $filterreportcategory AND ad.Hospital_Ward_ID=$Hospital_Ward_ID AND ad.Admission_Date_Time BETWEEN '$fromDate' and '$toDate' AND TIMESTAMPDIFF($ipd_time,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$start_age."' AND '".$end_age."' ORDER BY ad.Admision_ID ASC");
//        
//    }

                 if($report_category=='admission'){
//                     echo"a";
                $select_patients=mysqli_query($conn,"select pr.Registration_ID, pr.patient_name,pr.Date_Of_Birth,pr.Gender,ad.Admission_Date_Time,ad.Discharge_Date_Time,ad.Hospital_Ward_ID FROM tbl_admission ad,tbl_patient_registration pr WHERE ad.Registration_ID =pr.Registration_ID $filterPatientype AND ad.Admission_Date_Time BETWEEN '$fromDate' and '$toDate' AND TIMESTAMPDIFF($ipd_time,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$start_age."' AND '".$end_age."' $filetersponsor ORDER BY ad.Hospital_Ward_ID ASC"); 
                 }else if($report_category=='Inpatient_Days'){
                    
                     $Admission_number=mysqli_query($conn,"SELECT pr.Registration_ID, pr.patient_name,pr.Date_Of_Birth,pr.Gender,ad.Admission_Date_Time,ad.Discharge_Date_Time,ad.Hospital_Ward_ID, COUNT(`Admision_ID`) AS idadi FROM `tbl_admission` ad,`tbl_patient_registration` pr WHERE ad.Registration_ID=pr.Registration_ID AND ad.Hospital_Ward_ID='$Hospital_Ward_ID' AND ad.Admission_Date_Time BETWEEN '$fromDate' and '$toDate' AND TIMESTAMPDIFF($ipd_time,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$start_age."' AND '".$end_age."' $filetersponsor GROUP BY pr.Gender") or die(mysqli_error($conn));
                     
                       $mysql_num = mysqli_num_rows($Admission_number);
//                       echo $mysql_num."<br>";
        $Femaleone=0;
$Maleone=0;             
                       while($admision = mysqli_fetch_assoc($Admission_number)){


                                                if(strtolower($admision['Gender']) =='female'){
//                                                      $Femaleone++;
                                                    $Femaleone=0;
                                                      $Femaleone=$admision['idadi'];

                                                }
                                                  if(strtolower($admision['Gender']) =='male'){
//                                                 $Maleone++;
                                                      $Maleone=0;
                                                 $Maleone=$admision['idadi'];
                                                  }
                           
                       }
//                       echo "===>female===>$Femaleone---->male==>$Maleone===>".($Femaleone+$Maleone)."<br>";
                        
                 
                       
                       $Admission_discharged=mysqli_query($conn,"SELECT pr.Gender, COUNT(`Admision_ID`) AS idaditwo FROM `tbl_admission` ad,`tbl_patient_registration` pr WHERE ad.Registration_ID=pr.Registration_ID AND ad.Hospital_Ward_ID='$Hospital_Ward_ID' AND ad.Admission_Date_Time BETWEEN '$fromDate' and '$toDate' AND TIMESTAMPDIFF($ipd_time,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$start_age."' AND '".$end_age."' AND ad.Discharge_Reason_ID IN(SELECT Discharge_Reason_ID From tbl_discharge_reason WHERE discharge_condition='alive') GROUP BY pr.Gender ") or die(mydql_error());
                             $Femaletwo=0;
                             $Maletwo=0;
                           while($discharged=mysqli_fetch_assoc($Admission_discharged)){
                                                      if(strtolower($discharged['Gender']) =='female'){
                                                         $Femaletwo=0;  
                                                      $Femaletwo=$discharged['idaditwo'];

                                                }
                                                  if(strtolower($discharged['Gender']) =='male'){
                                                     $Maletwo=0; 
                                                  $Maletwo=$discharged['idaditwo'];
                                                  }
                           }
                           
//                            echo "===>female===>$Femaletwo---->male==>$Maletwo===>".($Femaletwo+$Maletwo)."<br>";
                           
                           $Admission_discharged_death=mysqli_query($conn,"SELECT pr.Gender, COUNT(`Admision_ID`) AS idadithree FROM `tbl_admission` ad,`tbl_patient_registration` pr WHERE ad.Registration_ID=pr.Registration_ID AND ad.Hospital_Ward_ID='$Hospital_Ward_ID' AND ad.Admission_Date_Time BETWEEN '$fromDate' and '$toDate' AND TIMESTAMPDIFF($ipd_time,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$start_age."' AND '".$end_age."' AND ad.Discharge_Reason_ID IN(SELECT Discharge_Reason_ID From tbl_discharge_reason WHERE discharge_condition='dead') GROUP BY pr.Gender") or die(mysqli_error($conn));
                                   $Femalethree=0;
                                   $Malethree=0;
                                 while($row = mysqli_fetch_assoc($Admission_discharged_death)){
                                                 if(strtolower($row['Gender']) =='female'){
                                                      $Femalethree=0;
                                                      $Femalethree = $row['idadithree'];

                                                }
                                                  if(strtolower($row['Gender']) =='male'){
                                                  $Malethree=0;
                                                  $Malethree = $row['idadithree'];
                                                  }
                                 }
                          $Admission_transfer_out=mysqli_query($conn,"SELECT pr.Gender, COUNT(`Admision_ID`) AS idadifour FROM `tbl_admission` ad,`tbl_patient_registration` pr WHERE ad.Registration_ID=pr.Registration_ID AND ad.Hospital_Ward_ID='$Hospital_Ward_ID' AND ad.Admision_ID IN(SELECT Admision_ID From tbl_transfer_out_in WHERE transfer_status='pending' AND transfer_out_date BETWEEN '$fromDate' and '$toDate') GROUP BY pr.Gender");
                                $Femalefour=0;
                               $Malefour=0;
                            while($transfer = mysqli_fetch_assoc($Admission_transfer_out)){
                                
                                     if(strtolower($transfer['Gender']) =='female'){
                                                      $Femalefour=0;
                                                      $Femalefour =$transfer['idadifour'];

                                                }
                                                  if(strtolower($transfer['Gender']) =='male'){
                                                  $Malefour=0;
                                                  $Malefour=$transfer['idadifour'];
                                                  }
                            }
                          $Admission_transfer_in=mysqli_query($conn,"SELECT pr.Gender, COUNT(`Admision_ID`) AS idadifive FROM `tbl_admission` ad,`tbl_patient_registration` pr WHERE ad.Registration_ID=pr.Registration_ID AND ad.Hospital_Ward_ID='$Hospital_Ward_ID' AND ad.Admision_ID IN(SELECT Admision_ID From tbl_transfer_out_in WHERE transfer_status='received' AND transfer_out_date BETWEEN '$fromDate' and '$toDate') GROUP BY pr.Gender");
                             $Femalefive=0;
                             $Malefive=0;
                            while($transfer2 = mysqli_fetch_assoc($Admission_transfer_in)){
                                
                                   if(strtolower($transfer2['Gender']) =='female'){
                                                      $Femalefive=0;
                                                      $Femalefive=$transfer2['idadifive'];

                                                }
                                                  if(strtolower($transfer2['Gender']) =='male'){
                                                  $Malefive=0;
                                                  $Malefive=$transfer2['idadifive'];
                                                  }
                            }
////                            
//                                  
//              
//                           $male_count =  $Female - $Female1 + $Female4 - $Female3 - $Female2 ;
//                           $female_count =  $Male - $Male1 + $Male4 - $Male3 - $Male2;   
//                           $male_count =  $Maleone +  $Maletwo + $Malethree - $Malefour +  $Malefive;
//                           $female_count =  $Femaleone + $Femaletwo + $Femalethree - $Femalefour + $Femalefive;   
                           $male_count =  $Maleone +  $Maletwo + $Malethree - $Malefour + $Malefive;
                           $female_count =  $Femaleone + $Femaletwo + $Femalethree - $Femalefour + $Femalefive;   
                           

                 }

    
//  die($nums."ohuiuhu".$Hospital_Ward_ID."from".$fromDate."to".$toDate."timek".$ipd_time."start".$start_age."end".$end_age."category".$report_category);
 
 $count=1;
$htm .= "<br><fieldset style='background-color:white;'><legend>Ward Name: ALL ADMISSION PATIENTS</legend>";
//$htm .= "<div id='less_age'>";
//$htm .= "<center>List Of Patients From:{$fromDate} To:{$toDate} With Age From {$start_age} {$diagnosis_time}  To {$end_age} {$diagnosis_time} years</center>";
$htm .= "<table width='100%;' border='1' style='font-size:12px;border-collapse: collapse;' cellpadding=5 cellspacing=10>";
echo "<thead>";
    
              if($report_category=='admission'){
		$htm .="<tr><th>SN</th><th>Reg No</th><th>Patient Name</th><th>Age</th><th>Gender</th><th>Admission Date</th><th>Ward Name</th></tr>";
              }
		echo "</thead>";
		if(mysqli_num_rows($select_patients) > 0){
while ($row=mysqli_fetch_assoc($select_patients)) {
    $Registration_ID=$row['Registration_ID'];
    $Hospital_Ward_ID=$row['Hospital_Ward_ID'];
    
    $ward_name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_ID='$Hospital_Ward_ID'"))['Hospital_Ward_Name'];
    
     $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";
	   // if($age == 0){
		
		$date1 = new DateTime($Today);
		$date2 = new DateTime($row['Date_Of_Birth']);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
    
              if($report_category=='admission'){
               $htm .= "<tr><td>".$count."</td><td style='text-align:center;'>".$Registration_ID."</td><td style='padding-left:10px;'><a href='javascript:void(0)' target='_blank' style='display:block;'>".ucwords(strtolower($row['patient_name']))."</a></td><td style='text-align:center;'>".$age."</td><td  style='padding-left:10px;'>".ucfirst($row['Gender'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['Admission_Date_Time'])."</td><td style='text-align:center;'>".$ward_name."</td></tr>";   
                }
//	$htm .= "<tr><td>".$count."</td><td style='padding-left:10px;'>".ucwords(strtolower($row['patient_name']))."</td><td style='text-align:center;'>".$age."</td><td  style='padding-left:10px;'>".ucfirst($row['Gender'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['Admission_Date_Time'])."</td></tr>";
	$count++;
}}else{
	$htm .= "<tr><td colspan='5'><center><br><br><br><br><b>No Patient Found</b><br><br><br><br></center></td></tr>";
}
$htm .= "</table>";

    //$htm.=$title;
    include("MPDF/mpdf.php");
    $mpdf = new mPDF('s', 'A4');
    $mpdf->SetFooter('|Page {PAGENO} of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>