
<style type="text/css">
    .patientList td,th{
        text-align: center;
		font-size:15px;
		background-color:white;
    }
</style>
<?php
@session_start();
include("./includes/connection.php");
@$fromDate =$_POST['fromDate'];
@$toDate=$_POST['toDate'];
@$start_age=$_POST['start_age'];
@$end_age=$_POST['end_age'];
@$ipd_time=$_POST['ipd_time'];
@$Ward_ID=$_POST['Ward_ID'];
@$patitent_type=$_POST['patitent_type'];
@$report_category=$_POST['report_category'];
@$Sponsor_ID=$_POST['Sponsor_ID'];
$male_count1 = 0;
$female_count1 =0;


$Employee_ID=$_SESSION['userinfo']['Employee_ID'];

$filterPatientype="";
	if($patitent_type!='all'){
       $filterPatientype="AND ad.New_return_admission='$patitent_type'";
    }else{
        $filterPatientype="AND Hospital_Ward_ID IN (SELECT ward_id FROM tbl_sub_department_ward WHERE sub_department_id IN(SELECT Sub_Department_ID FROM tbl_employee_sub_department WHERE Employee_ID='$Employee_ID'))";
    }
//    echo $patitent_type;
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
       
        $filetersponsor="";
    
       if($Sponsor_ID != 0){
//             echo "hapa inafika sana tu";
            $filetersponsor="AND pr.Sponsor_ID='$Sponsor_ID'";
           
       }else{
//           echo "ndo nhje ";
//          echo  $Sponsor_ID; 
          $filetersponsor=""; 
       }
$filterward="";
	if($Ward_ID !='all'){
    
     $filterward=" WHERE Hospital_Ward_ID='$Ward_ID'";
     if($report_category=='Death'){
      $deathward=" AND ward_type !='mortuary_ward'";
    }else{
      $deathward='';
    }
    }else {
        $filterward="";
        if($report_category=='Death'){
          $deathward=" WHERE ward_type !='mortuary_ward'";
        }else{
          $deathward='';
        }
    }
    
      echo "<br> <hr><table width='100%' class='patientList' class='table table-hover'>";
        echo "<thead>
             <tr >
                <th style='width:50%;' rowspan='4'>Wards</th>
                <th style='width:40%;' colspan='12'><b>Number Of Patients</b></th>
             </tr>
             <tr>
                <td colspan='12'> Age From $start_age To $end_age</td>
             </tr>
            <tr></tr>
             <tr>
               <td>Male</td><td>Female</td><td>Total</td>
             </tr>
         </thead>";
           $total_male_count=0;
           $total_female_count=0;
           $disease_name="";
           $disease_ID="";
           $num_count=1;
           $Femaleone=0;
           $Maleone=0;
        // die("SELECT DISTINCT Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_hospital_ward $filterward $deathward");
   $select_wards=mysqli_query($conn,"SELECT DISTINCT Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_hospital_ward $filterward $deathward");
    while($row=mysqli_fetch_assoc($select_wards)){
        $Hospital_Ward_ID=$row['Hospital_Ward_ID'];
        $Hospital_Ward_Name=$row['Hospital_Ward_Name'];
        $male_count=0;
        $female_count=0;
       
        
                 if($report_category=='admission'){               
                    $query_result=mysqli_query($conn,"SELECT ad.Admision_ID, pr.Gender, ad.Registration_ID FROM tbl_admission ad,tbl_patient_registration pr WHERE ad.Registration_ID =pr.Registration_ID $filterPatientype AND ad.Hospital_Ward_ID='$Hospital_Ward_ID' $filetersponsor AND  ad.Admission_Date_Time BETWEEN '$fromDate' and '$toDate' AND TIMESTAMPDIFF($ipd_time,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$start_age."' AND '".$end_age."'  ORDER BY ad.Admision_ID "); 
                 }else if($report_category=='transfer_in'){
                    $query_result=mysqli_query($conn,"SELECT tin.in_ward_id,pr.Registration_ID, pr.patient_name,pr.Date_Of_Birth,pr.Gender,ad.Admission_Date_Time,tin.transfer_in_date,ad.Discharge_Date_Time,tin.in_ward_id,wrd.Hospital_Ward_Name FROM tbl_admission ad,tbl_patient_registration pr,tbl_transfer_out_in tin,tbl_hospital_ward wrd WHERE tin.in_ward_id=wrd.Hospital_Ward_ID  AND ad.Registration_ID =pr.Registration_ID AND ad.Admision_ID=tin.Admision_ID AND transfer_status='received' AND tin.in_ward_id='$Hospital_Ward_ID' $filetersponsor AND tin.transfer_in_date BETWEEN '$fromDate' and '$toDate' AND TIMESTAMPDIFF($ipd_time,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$start_age."' AND '".$end_age."' ORDER BY ad.Admision_ID");  
                 }else if($report_category=='transfer_out'){
                     $query_result=mysqli_query($conn,"SELECT tou.out_ward_id,pr.Registration_ID, pr.patient_name,pr.Date_Of_Birth,pr.Gender,ad.Admission_Date_Time,tou.transfer_out_date,ad.Discharge_Date_Time,tou.out_ward_id,wrd.Hospital_Ward_Name FROM tbl_admission ad,tbl_patient_registration pr,tbl_transfer_out_in tou,tbl_hospital_ward wrd WHERE tou.out_ward_id=wrd.Hospital_Ward_ID AND ad.Registration_ID =pr.Registration_ID AND ad.Admision_ID=tou.Admision_ID AND transfer_status='received' AND tou.out_ward_id='$Hospital_Ward_ID' $filetersponsor AND tou.transfer_out_date BETWEEN '$fromDate' and '$toDate' AND TIMESTAMPDIFF($ipd_time,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$start_age."' AND '".$end_age."' ORDER BY ad.Admision_ID");
                 }else if($report_category=='transfer_pending'){
                  $query_result=mysqli_query($conn,"SELECT tou.out_ward_id,pr.Registration_ID, pr.patient_name,pr.Date_Of_Birth,pr.Gender,ad.Admission_Date_Time,tou.transfer_out_date,ad.Discharge_Date_Time,tou.out_ward_id,wrd.Hospital_Ward_Name FROM tbl_admission ad,tbl_patient_registration pr,tbl_transfer_out_in tou,tbl_hospital_ward wrd WHERE tou.out_ward_id=wrd.Hospital_Ward_ID AND ad.Registration_ID =pr.Registration_ID AND ad.Admision_ID=tou.Admision_ID AND transfer_status='pending' AND tou.out_ward_id='$Hospital_Ward_ID' $filetersponsor AND tou.transfer_out_date BETWEEN '$fromDate' and '$toDate' AND TIMESTAMPDIFF($ipd_time,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$start_age."' AND '".$end_age."' ORDER BY ad.Admision_ID");
                }else if($report_category=='Normal' || $report_category=='Death'){
                $query_result=mysqli_query($conn,"SELECT pr.Gender FROM tbl_admission ad,tbl_patient_registration pr,tbl_discharge_reason dr WHERE ad.Registration_ID =pr.Registration_ID AND ad.Discharge_Reason_ID=dr.Discharge_Reason_ID $filterreportcategory  AND ad.Hospital_Ward_ID='$Hospital_Ward_ID' $filetersponsor AND ad.pending_set_time BETWEEN '$fromDate' and '$toDate' AND TIMESTAMPDIFF($ipd_time,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$start_age."' AND '".$end_age."' ORDER BY ad.Admision_ID ");      
                 }else if($report_category=='Inpatient_Days'){                    
                     $Admission_number=mysqli_query($conn,"SELECT pr.Gender, COUNT(`Admision_ID`) AS idadi FROM `tbl_admission` ad,`tbl_patient_registration` pr WHERE ad.Registration_ID=pr.Registration_ID $filetersponsor AND ad.Hospital_Ward_ID='$Hospital_Ward_ID' AND ad.Admission_Date_Time BETWEEN '$fromDate' and '$toDate' AND TIMESTAMPDIFF($ipd_time,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$start_age."' AND '".$end_age."' GROUP BY pr.Gender") or die(mysqli_error($conn));
                     
                      $mysql_num = mysqli_num_rows($Admission_number);
                      $Femaleone=0;
                      $Maleone=0;             
                      while($admision = mysqli_fetch_assoc($Admission_number)){
                                              
                          if(strtolower($admision['Gender']) =='female'){
                              $Femaleone=0;
                              $Femaleone=$admision['idadi'];
                          }
                          if(strtolower($admision['Gender']) =='male'){
                              $Maleone=0;
                              $Maleone=$admision['idadi'];
                          }
                           
                       }
                        
                       $Admission_discharged=mysqli_query($conn,"SELECT pr.Gender, COUNT(`Admision_ID`) AS idaditwo FROM `tbl_admission` ad,`tbl_patient_registration` pr WHERE ad.Registration_ID=pr.Registration_ID AND ad.Hospital_Ward_ID='$Hospital_Ward_ID' AND ad.Admission_Date_Time BETWEEN '$fromDate' and '$toDate' AND TIMESTAMPDIFF($ipd_time,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$start_age."' AND '".$end_age."' AND ad.Discharge_Reason_ID IN(SELECT Discharge_Reason_ID From tbl_discharge_reason WHERE discharge_condition='alive') GROUP BY pr.Gender ") or die(mysqli_error($conn));
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
                           
                           $Admission_discharged_death=mysqli_query($conn,"SELECT pr.Gender, COUNT(`Admision_ID`) AS idadithree FROM `tbl_admission` ad,`tbl_patient_registration` pr WHERE ad.Registration_ID=pr.Registration_ID AND ad.Hospital_Ward_ID='$Hospital_Ward_ID'  AND ad.Admission_Date_Time BETWEEN '$fromDate' and '$toDate' AND TIMESTAMPDIFF($ipd_time,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$start_age."' AND '".$end_age."'  AND ad.Discharge_Reason_ID IN(SELECT Discharge_Reason_ID From tbl_discharge_reason WHERE discharge_condition='dead') GROUP BY pr.Gender") or die(mysqli_error($conn));
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

                            $Admission_transfer_pending=mysqli_query($conn,"SELECT pr.Gender, COUNT(`Admision_ID`) AS idadifour FROM `tbl_admission` ad,`tbl_patient_registration` pr WHERE ad.Registration_ID=pr.Registration_ID AND ad.Hospital_Ward_ID='$Hospital_Ward_ID' AND ad.Admision_ID IN(SELECT Admision_ID From tbl_transfer_out_in WHERE transfer_status='pending' AND transfer_out_date BETWEEN '$fromDate' and '$toDate') GROUP BY pr.Gender");
                              $Femalesix=0;
                             $Malesix=0;
                          while($transfer = mysqli_fetch_assoc($Admission_transfer_pending)){
                              
                              if(strtolower($transfer['Gender']) =='female'){
                                $Femalesix=0;
                                $Femalesix =$transfer['idadifour'];  
                              }
                              if(strtolower($transfer['Gender']) =='male'){
                              $Malesix=0;
                              $Malesix=$transfer['idadifour'];
                              }
                          }
                            
                            
                            
            $total_ward_number=mysqli_query($conn,"SELECT pr.Gender, COUNT(`Admision_ID`) AS idadinumber FROM `tbl_admission` ad,`tbl_patient_registration` pr WHERE ad.Registration_ID=pr.Registration_ID AND ad.Hospital_Ward_ID='$Hospital_Ward_ID' AND ad.Admission_Status='admitted' GROUP BY pr.Gender") or die(mysqli_error($conn));
                     
            $mysql_num = mysqli_num_rows($total_ward_number);
            $Femalenumber=0;
            $Malenumber=0;             
            while($number = mysqli_fetch_assoc($total_ward_number)){
              if(strtolower($number['Gender']) =='female'){
                  $Femalenumber=0;
                  $Femalenumber=$number['idadinumber'];
              }
              if(strtolower($number['Gender']) =='male'){
                  $Malenumber=0;
                  $Malenumber=$number['idadinumber'];
              }                           
            }
                            
                            
                  $male_count =  $Maleone +  $Maletwo + $Malethree - $Malefour + $Malefive + $Malesix + $Malenumber;
                  $female_count =  $Femaleone + $Femaletwo + $Femalethree - $Femalefour + $Femalefive + $Malesix + $Femalenumber;   
                           

                 }else{
                 $query_result=mysqli_query($conn,"SELECT pr.Gender FROM tbl_admission ad,tbl_patient_registration pr WHERE ad.Registration_ID =pr.Registration_ID $fileterabsconded AND ad.Hospital_Ward_ID='$Hospital_Ward_ID' AND ad.Admission_Date_Time BETWEEN '$fromDate' and '$toDate' AND TIMESTAMPDIFF($ipd_time,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$start_age."' AND '".$end_age."' ORDER BY ad.Admision_ID "); 
                 }

                 
              $number = mysqli_num_rows($query_result);
              while($result_data=mysqli_fetch_assoc($query_result)){
                $Registration_ID = $result_data['Registration_ID']; 
                $Admision_ID = $result_data['Admision_ID']; 
                if($report_category=='admission'){  
                  $selecttransfer = mysqli_query($conn, "SELECT Admision_ID ,out_ward_id FROM tbl_transfer_out_in WHERE Admision_ID='$Admision_ID'  AND transfer_status='received'") or die(mysqli_error($conn));
                  $numrws = mysqli_num_rows($selecttransfer);
                  if($numrws>0){continue ;}
                }
                 $result_data['Gender'];
                  if(strtolower($result_data['Gender']) =='male'){
                      $male_count++;
                  }
                  if(strtolower($result_data['Gender']) =='female'){
                      $female_count++;
                  }            
              } 
              if($report_category=='admission'){ 
              //transfered pt after admission 
              $selecttransfer = mysqli_query($conn, "SELECT DISTINCT  Gender FROM tbl_transfer_out_in toi , tbl_admission ad, tbl_patient_registration pr WHERE ad.Registration_ID =pr.Registration_ID AND  transfer_status='received' AND toi.Admision_ID=ad.Admision_ID AND out_ward_id='$Hospital_Ward_ID' AND ad.Admission_Date_Time BETWEEN '$fromDate' and '$toDate' AND TIMESTAMPDIFF($ipd_time,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$start_age."' AND '".$end_age."' GROUP BY  toi.Admision_ID ORDER BY transfer_id ASC ") or die(mysqli_error($conn));
              if(mysqli_num_rows($selecttransfer)>0){
                while($rws = mysqli_fetch_assoc($selecttransfer)){
                  $Gender = $rws['Gender'];
                  if(strtolower($Gender) =='male'){
                    $male_count_t++;
                  }
                  if(strtolower($Gender) =='female'){
                      $female_count_t++;
                  }
                }
              }else{
                $male_count_t=0;
                $female_count_t=0;
              }
            }
              $subtotal=$male_count +$male_count_t+$female_count+$female_count_t;
			if($subtotal===0){continue;}
        echo "<tr>";
            echo "<td style='text-align:left;'><a href='#' onclick='viewPatientList(\"$Hospital_Ward_Name\",\"$Hospital_Ward_ID\",\"$fromDate\",\"$toDate\",\"$report_category\",\"$start_age\",\"$end_age\",\"$ipd_time\",\"$patitent_type\",\"$Sponsor_ID\");'  style='display:block;'>".$num_count.". ".$Hospital_Ward_Name."</a></td>";
            echo "<td>".$male_count."</td>";
            echo "<td>".$female_count."</td>";
            echo "<td>".($female_count+$male_count)."</td>";
        echo "</tr>";
                $total_male_count+=$male_count;
                $total_female_count+=$female_count;
             $num_count++;
                
    }
    
       echo "<tr>";
            echo "<td style='text-align:left;'> Total </td>";
            echo "<td>".( $total_male_count)."</td>";
            echo "<td>".( $total_female_count)."</td>";
            echo "<td>".( $total_male_count+$total_female_count)."</td>";
        echo "</tr>";
       echo "<tr>";
            echo "<td style='text-align:left;' colspan='4'>  <br><button class='art-button-green' style='color:white !important; height:24px;' onclick='previewadmission(\"$Hospital_Ward_Name\",\"$Hospital_Ward_ID\",\"$fromDate\",\"$toDate\",\"$report_category\",\"$start_age\",\"$end_age\",\"$ipd_time\",\"$patitent_type\",\"$Sponsor_ID\");' >Preview</button> </td>";
         
        echo "</tr>";
     echo "</table>";
     ?>
     <script>
function previewadmission(Hospital_Ward_Name,Hospital_Ward_ID,fromDate,toDate,report_category,start_age,end_age,ipd_time,patitent_type,Sponsor_ID){
		window.open('preview_all_admission_patients.php?Hospital_Ward_Name='+Hospital_Ward_Name+'&Hospital_Ward_ID='+Hospital_Ward_ID+'&fromDate='+fromDate+'&toDate='+toDate+'&report_category='+report_category+'&start_age='+start_age+'&end_age='+end_age+'&ipd_time='+ipd_time+'&patitent_type='+patitent_type+'&Sponsor_ID='+Sponsor_ID, '_blank');
	}
        
</script> 