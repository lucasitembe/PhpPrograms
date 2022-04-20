<?php

@session_start();

include("./includes/connection.php");

include("allFunctions.php");
include("dhis2_functions.php");
$filter=" ";
$filterIn=" ";
$Hospital_Ward_Name=$_POST['Hospital_Ward_Name'];

$Hospital_Ward_ID = $_POST['Hospital_Ward_ID'];
$fromDate = $_POST['fromDate'];
$toDate = $_POST['toDate'];
$start_age = $_POST['start_age'];
$end_age = $_POST['end_age'];
$report_category = $_POST['report_category'];
$ipd_time = $_POST['ipd_time'];
$patitent_type = $_POST['patitent_type'];
$Sponsor_ID = $_POST['Sponsor_ID'];

     $filetersponsor="";
    
      if($Sponsor_ID != 0){
        $filetersponsor="AND pr.Sponsor_ID='$Sponsor_ID'";
          
      }else{
        $filetersponsor=""; 
      }

 $filterPatientype="";
	if($patitent_type!='all'){
       $filterPatientype="AND ad.New_return_admission='$patitent_type'";
    }else{
        $filterPatientype="";
    }
  $filterreportcategory="";
	if($report_category !='admission'){
   
}else {
    $filterreportcategory ="";
}
if($report_category=='Death'){  
  $dischargeType=" AND dr.discharge_condition='dead'";                 
}else if($report_category=='Absconded'){           
  $dischargeType=" AND dr.Discharge_Reason='Absconded'";           
}else if($report_category=='Normal'){
  $dischargeType=" AND dr.discharge_condition='alive'";                
}
$fileterabsconded="";    
if($report_category=='Absconded'){           
    $dischargeType=" AND dr.Discharge_Reason='Absconded'";           
}else{
    $fileterabsconded=""; 
}
$filterward="";
	if($Ward_ID!='all'){
     $filterward="WHERE Hospital_Ward_ID='$Ward_ID'";
    }else {
        $filterward="";
    }

 
        if($report_category=='admission'){ 
          $Admisiontime='within';
          $reporttype='within';
          $valuesType='patientData';
          $getData = json_decode(getAdmissioncount($fromDate, $toDate, $Sponsor_ID, $Hospital_Ward_ID,  $start_age, $end_age ,$Admisiontime, $ipd_time, $patitent_type, $reporttype, $valuesType), true);
          
  
          }else if($report_category=='transfer_in'){
            $transferType='in';
            $Admisiontime='within';
            $valuesType='patientData';
            $getData=json_decode(getTransferCount($fromDate, $toDate, $Sponsor_ID, $Hospital_Ward_ID,  $start_age, $end_age , $Admisiontime, $ipd_time, $patitent_type, $transferType, $valuesType), true);          
          
          }else if($report_category=='transfer_out'){
            $transferType='out';
            $Admisiontime='within';
            $valuesType='patientData';
            $getData=json_decode(getTransferCount($fromDate, $toDate, $Sponsor_ID, $Hospital_Ward_ID,  $start_age, $end_age , $Admisiontime, $ipd_time, $patitent_type, $transferType, $valuesType), true);
            
        }else if($report_category=='transfer_pending'){
          $transferType='pending';
          $Admisiontime='within';
          $valuesType='patientData';
          $getData=json_decode(getTransferCount($fromDate, $toDate, $Sponsor_ID, $Hospital_Ward_ID,  $start_age, $end_age , $Admisiontime, $ipd_time, $patitent_type, $transferType, $valuesType), true);
         
        }else if($report_category=='Normal'){

          $Admisiontime='within';
          $valuesType='patientData';
          $getData=json_decode(getDischargeCount($fromDate, $toDate, $Sponsor_ID, $Hospital_Ward_ID,  $start_age, $end_age , $Admisiontime, $ipd_time, $patitent_type, $dischargeType, $valuesType), true);
          // die(print_r($getData));
        }else if($report_category=='Death'){
          $Admisiontime='within';
          $valuesType='patientData';
          $getData=json_decode(getDischargeCount($fromDate, $toDate, $Sponsor_ID, $Hospital_Ward_ID,  $start_age, $end_age , $Admisiontime, $ipd_time, $patitent_type, $dischargeType, $valuesType), true);
        }else if($report_category=='Inpatient_Days'){
          $Admisiontime='obd';
          $reporttype='obd';
          $valuesType='patientData';
          $getData = json_decode(getAdmissioncount($fromDate, $toDate, $Sponsor_ID, $Hospital_Ward_ID,  $start_age, $end_age ,$Admisiontime, $ipd_time, $patitent_type, $reporttype, $valuesType), true);
    
        }

 
 $count=1;
echo "<br><fieldset style='background-color:white;'><legend>Ward Name: ".$Hospital_Ward_Name."</legend>";
echo "<div id='less_age'>";
echo "<center>List Of Patients From:{$fromDate} To:{$toDate} With Age From {$start_age} {$ipd_time}  To {$end_age} {$ipd_time}</center>";
echo "<table width='100%;' border='1' style='font-size:18px;border-collapse: collapse;' cellpadding=5 cellspacing=10>";
echo "<thead>";
    
              if($report_category=='admission'){
		          echo"<tr><th>SN</th><th>Reg No</th><th>Patient Name</th><th>Age</th><th>Gender</th><th>Admission Date</th></tr>";
              }else if($report_category=='Inpatient_Days'){
                echo"<tr><th>SN</th><th>Reg No</th><th>Patient Name</th><th>Age</th><th>Gender</th><th>Admission Date</th></tr>";
              }else if($report_category=='transfer_in'){
                echo"<tr><th>SN</th><th>Reg No</th><th>Patient Name</th><th>Age</th><th>Gender</th><th>Admission Date</th><th>Transfer in Date</th><th>Ward Transfer</th></tr>";  
              }else if($report_category=='transfer_out'){
                echo"<tr><th>SN</th><th>Reg No</th><th>Patient Name</th><th>Age</th><th>Gender</th><th>Admission Date</th><th>Transfer out Date</th><th>Ward Transfer</th></tr>";  
              }else if($report_category=='transfer_pending'){
                echo"<tr><th>SN</th><th>Reg No</th><th>Patient Name</th><th>Age</th><th>Gender</th><th>Admission Date</th><th>Transfer Date</th><th>Transfered From</th></tr>";  
              }else if($report_category=='Normal' || $report_category=='Death'){
                  echo"<tr><th>SN</th><th>Reg No</th><th>Patient Name</th><th>Age</th><th>Gender</th><th>Admission Date</th><th>Discharge Date</th></tr>";
              }else{
                  echo"<tr><th>SN</th><th>Reg No</th><th>Patient Name</th><th>Age</th><th>Gender</th><th>Admission Date</th><th>Discharge Date</th></tr>";
                  
              }
		echo "</thead>";

  if(sizeof($getData)>0){
    foreach($getData AS $row){
              $disease_name='';
              $Registration_ID=$row['Registration_ID'];
              $age =getCurrentPatientAge($row['Date_Of_Birth']);
               $Admision_ID= $row['Admision_ID'];
                
                if($report_category=='transfer_in'){
                  $ward_id_in = $row['out_ward_id'];
                 $ward_Name_in = mysqli_fetch_assoc(mysqli_query($conn,"SELECT wrd.Hospital_Ward_Name FROM tbl_transfer_out_in tou,tbl_hospital_ward wrd WHERE tou.out_ward_id=wrd.Hospital_Ward_ID AND tou.out_ward_id='$ward_id_in'"))['Hospital_Ward_Name'];
               echo "<tr><td>".$count."</td><td style='text-align:center;'>".$Registration_ID."</td><td style='padding-left:10px;'>".ucwords(strtolower($row['patient_name']))."</td><td style='text-align:center;'>".$age."</td><td  style='padding-left:10px;'>".ucfirst($row['Gender'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['Admission_Date_Time'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['transfer_in_date'])."</td><td style='text-align:center;width:20%;'>".$ward_Name_in."</td></tr>";
                    
                }else if($report_category=='transfer_out'){
                  $ward_id = $row['in_ward_id'];
                 $ward_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT wrd.Hospital_Ward_Name FROM tbl_transfer_out_in tin,tbl_hospital_ward wrd WHERE tin.in_ward_id=wrd.Hospital_Ward_ID AND tin.in_ward_id='$ward_id'"))['Hospital_Ward_Name'];
                    echo "<tr><td>".$count."</td><td style='text-align:center;'>".$Registration_ID."</td><td style='padding-left:10px;'>".ucwords(strtolower($row['patient_name']))."</td><td style='text-align:center;'>".$age."</td><td  style='padding-left:10px;'>".ucfirst($row['Gender'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['Admission_Date_Time'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['transfer_out_date'])."</td><td style='text-align:center;width:20%;'>".$ward_Name."</td></tr>";
                    
                        
                }else if($report_category=='transfer_pending'){
                      $ward_id = $row['in_ward_id'];
                    $ward_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT wrd.Hospital_Ward_Name FROM tbl_transfer_out_in tin,tbl_hospital_ward wrd WHERE tin.in_ward_id=wrd.Hospital_Ward_ID AND tin.in_ward_id='$ward_id'"))['Hospital_Ward_Name'];
                echo "<tr><td>".$count."</td><td style='text-align:center;'>".$Registration_ID."</td><td style='padding-left:10px;'>".ucwords(strtolower($row['patient_name']))."</td><td style='text-align:center;'>".$age."</td><td  style='padding-left:10px;'>".ucfirst($row['Gender'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['Admission_Date_Time'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['transfer_out_date'])."</td><td style='text-align:center;width:20%;'>".$ward_Name."</td></tr>";
                
                    
                }else if($report_category=='Normal' || $report_category=='Death'){
                 echo "<tr><td>".$count."</td><td style='text-align:center;'>".$Registration_ID."</td><td style='padding-left:10px;'>".ucwords(strtolower($row['patient_name']))."</td><td style='text-align:center;'>".$age."</td><td  style='padding-left:10px;'>".ucfirst($row['Gender'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['Admission_Date_Time'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['pending_set_time'])."</td>
                 
                 </tr>";      
                }else if($report_category=='admission'){                 
                  echo "<tr><td>".$count."</td><td style='text-align:center;'>".$Registration_ID."</td><td style='padding-left:10px;'>".ucwords(strtolower($row['patient_name']))."</td><td style='text-align:center;'>".$age."</td><td  style='padding-left:10px;'>".ucfirst($row['Gender'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['Admission_Date_Time'])."</td>
                  </tr>";   
                } else if($report_category=='Inpatient_Days'){
                  echo "<tr><td>".$count."</td><td style='text-align:center;'>".$Registration_ID."</td><td style='padding-left:10px;'>".ucwords(strtolower($row['patient_name']))."</td><td style='text-align:center;'>".$age."</td><td  style='padding-left:10px;'>".ucfirst($row['Gender'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['Admission_Date_Time'])."</td>
                  </tr>";    
                }
	
	$count++;
}}else{
	echo "<tr><td colspan='5'><center><br><br><br><br><b>No Patient Found</b><br><br><br><br></center></td></tr>";
}
echo "</table>";
echo "<br><button class='art-button-green' onclick='Preview_Listson(\"$Hospital_Ward_Name\",\"$Hospital_Ward_ID\",\"$fromDate\",\"$toDate\",\"$report_category\",\"$start_age\",\"$end_age\",\"$ipd_time\",\"$patitent_type\",\"$Sponsor_ID\");'>Preview In PDF</button>
<button class='art-button-green' onclick='Preview_Listexcel(\"$Hospital_Ward_Name\",\"$Hospital_Ward_ID\",\"$fromDate\",\"$toDate\",\"$report_category\",\"$start_age\",\"$end_age\",\"$ipd_time\",\"$patitent_type\",\"$Sponsor_ID\");'>Preview In Execel</button>";
 ?>
<script>
function Preview_Listson(Hospital_Ward_Name,Hospital_Ward_ID,fromDate,toDate,report_category,start_age,end_age,ipd_time,patitent_type,Sponsor_ID){
		window.open('preview_admited_patients.php?Hospital_Ward_Name='+Hospital_Ward_Name+'&Hospital_Ward_ID='+Hospital_Ward_ID+'&fromDate='+fromDate+'&toDate='+toDate+'&report_category='+report_category+'&start_age='+start_age+'&end_age='+end_age+'&ipd_time='+ipd_time+'&patitent_type='+patitent_type+'&Sponsor_ID='+Sponsor_ID, '_blank');
	}
  function Preview_Listexcel(Hospital_Ward_Name,Hospital_Ward_ID,fromDate,toDate,report_category,start_age,end_age,ipd_time,patitent_type,Sponsor_ID){
		window.open('preview_admited_patients_excel.php?Hospital_Ward_Name='+Hospital_Ward_Name+'&Hospital_Ward_ID='+Hospital_Ward_ID+'&fromDate='+fromDate+'&toDate='+toDate+'&report_category='+report_category+'&start_age='+start_age+'&end_age='+end_age+'&ipd_time='+ipd_time+'&patitent_type='+patitent_type+'&Sponsor_ID='+Sponsor_ID, '_blank');
	}     
</script>   
    

