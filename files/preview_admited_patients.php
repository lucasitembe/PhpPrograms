<?php

@session_start();

include("./includes/connection.php");

include("allFunctions.php");
include("dhis2_functions.php");
$filter=" ";
$filterIn=" ";
$Hospital_Ward_Name=$_GET['Hospital_Ward_Name'];

$Hospital_Ward_ID = $_GET['Hospital_Ward_ID'];
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];
$start_age = $_GET['start_age'];
$end_age = $_GET['end_age'];
$report_category = $_GET['report_category'];
$ipd_time = $_GET['ipd_time'];
$patitent_type = $_GET['patitent_type'];
$Sponsor_ID = $_GET['Sponsor_ID'];

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
}else if($report_category=='alive'){
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

  $htm  = "<table width ='100%' height = '30px'>";
    $htm .= "<tr> <td> <img src='./branchBanner/branchBanner.png' width=100%> </td> </tr>";
    $htm .= "<tr>";
    $htm .= "<td style='text-align: center;'><h5> Patients List From ".$fromDate." To ".$toDate." With Age Between ".$start_age." And ".$end_age."  $ipd_time</h5></td>";
    $htm .= "</tr>";
    $htm .= "</table><br/>";
//\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    if($report_category=='admission'){ 
      $Admisiontime='within';
      $reporttype='within';
      $valuesType='patientData';
      $getData = json_decode(getAdmissioncount($fromDate, $toDate, $Sponsor_ID, $Hospital_Ward_ID,  $start_age, $end_age ,$Admisiontime, $ipd_time, $patitent_type, $reporttype, $valuesType), true);
      // die(print_r($getData));

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
     
    }else if($report_category=='Normal' || $report_category=='Death'){
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
$htm.= "<br><fieldset style='background-color:white;'><legend>Ward Name: ".$Hospital_Ward_Name."</legend>";
$htm.= "<div id='less_age'>";
$htm.= "<center>List Of Patients From:{$fromDate} To:{$toDate} With Age From {$start_age} {$ipd_time}  To {$end_age} {$ipd_time}</center>";
$htm.= "<table width='100%;' border='1' style='font-size:11px;border-collapse: collapse;' cellpadding=5 cellspacing=10>";
$htm.= "<thead>";

          if($report_category=='admission'){
          $htm.="<tr><th>SN</th><th>Reg No</th><th>Patient Name</th><th>Age</th><th>Gender</th><th>Admission Date</th></tr>";
          }else if($report_category=='Inpatient_Days'){
            $htm.="<tr><th>SN</th><th>Reg No</th><th>Patient Name</th><th>Age</th><th>Gender</th><th>Admission Date</th></tr>";
          }else if($report_category=='transfer_in'){
            $htm.="<tr><th>SN</th><th>Reg No</th><th>Patient Name</th><th>Age</th><th>Gender</th><th>Admission Date</th><th>Transfer in Date</th><th>Ward Transfer</th></tr>";  
          }else if($report_category=='transfer_out'){
            $htm.="<tr><th>SN</th><th>Reg No</th><th>Patient Name</th><th>Age</th><th>Gender</th><th>Admission Date</th><th>Transfer out Date</th><th>Ward Transfer</th></tr>";  
          }else if($report_category=='transfer_pending'){
            $htm.="<tr><th>SN</th><th>Reg No</th><th>Patient Name</th><th>Age</th><th>Gender</th><th>Admission Date</th><th>Transfer Date</th><th>Transfered From</th></tr>";  
          }else if($report_category=='Normal' || $report_category=='Death'){
              $htm.="<tr><th>SN</th><th>Reg No</th><th>Patient Name</th><th>Age</th><th>Gender</th><th>Admission Date</th><th>Discharge Date</th></tr>";
          }else{
              $htm.="<tr><th>SN</th><th>Reg No</th><th>Patient Name</th><th>Age</th><th>Gender</th><th>Admission Date</th><th>Discharge Date</th></tr>";
              
          }
$htm.= "</thead>";
if(sizeof($getData)>0){
  foreach($getData AS $row){
          $Registration_ID=$row['Registration_ID'];
          $age =getCurrentPatientAge($row['Date_Of_Birth']);
           $Admision_ID= $row['Admision_ID'];                
            if($report_category=='transfer_in'){
              $ward_id_in = $row['out_ward_id'];
             $ward_Name_in = mysqli_fetch_assoc(mysqli_query($conn,"SELECT wrd.Hospital_Ward_Name FROM tbl_transfer_out_in tou,tbl_hospital_ward wrd WHERE tou.out_ward_id=wrd.Hospital_Ward_ID AND tou.out_ward_id='$ward_id_in'"))['Hospital_Ward_Name'];
           $htm.= "<tr><td>".$count."</td><td style='text-align:center;'>".$Registration_ID."</td><td style='padding-left:10px;'>".ucwords(strtolower($row['patient_name']))."</td><td style='text-align:center;'>".$age."</td><td  style='padding-left:10px;'>".ucfirst($row['Gender'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['Admission_Date_Time'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['transfer_in_date'])."</td><td style='text-align:center;width:20%;'>".$ward_Name_in."</td></tr>";
                
            }else if($report_category=='transfer_out'){
              $ward_id = $row['in_ward_id'];
             $ward_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT wrd.Hospital_Ward_Name FROM tbl_transfer_out_in tin,tbl_hospital_ward wrd WHERE tin.in_ward_id=wrd.Hospital_Ward_ID AND tin.in_ward_id='$ward_id'"))['Hospital_Ward_Name'];
                $htm.= "<tr><td>".$count."</td><td style='text-align:center;'>".$Registration_ID."</td><td style='padding-left:10px;'>".ucwords(strtolower($row['patient_name']))."</td><td style='text-align:center;'>".$age."</td><td  style='padding-left:10px;'>".ucfirst($row['Gender'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['Admission_Date_Time'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['transfer_out_date'])."</td><td style='text-align:center;width:20%;'>".$ward_Name."</td></tr>";
                
                    
            }else if($report_category=='transfer_pending'){
                  $ward_id = $row['in_ward_id'];
                $ward_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT wrd.Hospital_Ward_Name FROM tbl_transfer_out_in tin,tbl_hospital_ward wrd WHERE tin.in_ward_id=wrd.Hospital_Ward_ID AND tin.in_ward_id='$ward_id'"))['Hospital_Ward_Name'];
            $htm.= "<tr><td>".$count."</td><td style='text-align:center;'>".$Registration_ID."</td><td style='padding-left:10px;'>".ucwords(strtolower($row['patient_name']))."</td><td style='text-align:center;'>".$age."</td><td  style='padding-left:10px;'>".ucfirst($row['Gender'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['Admission_Date_Time'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['transfer_out_date'])."</td><td style='text-align:center;width:20%;'>".$ward_Name."</td></tr>";
            
                
            }else if($report_category=='Normal' || $report_category=='Death'){
             $htm.= "<tr><td>".$count."</td><td style='text-align:center;'>".$Registration_ID."</td><td style='padding-left:10px;'>".ucwords(strtolower($row['patient_name']))."</td><td style='text-align:center;'>".$age."</td><td  style='padding-left:10px;'>".ucfirst($row['Gender'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['Admission_Date_Time'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['pending_set_time'])."</td></tr>";      
            }else if($report_category=='admission'){                 
              $htm.= "<tr><td>".$count."</td><td style='text-align:center;'>".$Registration_ID."</td><td style='padding-left:10px;'>".ucwords(strtolower($row['patient_name']))."</td><td style='text-align:center;'>".$age."</td><td  style='padding-left:10px;'>".ucfirst($row['Gender'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['Admission_Date_Time'])."</td></tr>";   
            } else if($report_category=='Inpatient_Days'){
              $htm.= "<tr><td>".$count."</td><td style='text-align:center;'>".$Registration_ID."</td><td style='padding-left:10px;'>".ucwords(strtolower($row['patient_name']))."</td><td style='text-align:center;'>".$age."</td><td  style='padding-left:10px;'>".ucfirst($row['Gender'])."</td><td style='text-align:center;width:20%;'>".strtoupper($row['Admission_Date_Time'])."</td></tr>";    
            }

$count++;
  }
}else{
$htm.= "<tr><td colspan='5'><center><br><br><br><br><b>No Patient Found</b><br><br><br><br></center></td></tr>";
}
$htm.= "</table>";    
    include("MPDF/mpdf.php");
    $mpdf = new mPDF('s', 'A4');
    $mpdf->SetFooter('|Page {PAGENO} of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>