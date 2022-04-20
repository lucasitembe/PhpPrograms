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
include("allFunctions.php");
include("dhis2_functions.php");
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
 
	
    if($report_category=='Death'){  
        $dischargeType=" AND dr.discharge_condition='dead'";                 
    }
    if($report_category=='Normal'){
        $dischargeType=" AND dr.discharge_condition='alive'";
    }
    
    $fileterabsconded="";    
    if($report_category=='Absconded'){           
        $dischargeType=" AND dr.Discharge_Reason='Absconded'";           
    }else{
        $fileterabsconded=""; 
    }
       
    $filetersponsor="";    
    if($Sponsor_ID != 0){
        $filetersponsor="AND pr.Sponsor_ID='$Sponsor_ID'";
    }else{
        $filetersponsor=""; 
    }
    $filterward="";
	if($Ward_ID !='all'){    
        $filterward=" WHERE ward_type !='mortuary_ward' AND Hospital_Ward_ID='$Ward_ID'";
       
    }else {
        $filterward=" WHERE ward_type !='mortuary_ward'";
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
           $valuesType='';
   $select_wards=mysqli_query($conn,"SELECT DISTINCT Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_hospital_ward $filterward $deathward");
   if(mysqli_num_rows($select_wards)){
    while($row=mysqli_fetch_assoc($select_wards)){
        $Hospital_Ward_ID=$row['Hospital_Ward_ID'];
        $Hospital_Ward_Name=$row['Hospital_Ward_Name'];
        $male_count=0;
        $female_count=0;
        
        /** NEW FUNCTION USING FUNCTION */
        
        if($report_category=='admission'){
            $Admisiontime='within';
            $reporttype='within';
            $getData = json_decode(getAdmissioncount($fromDate, $toDate, $Sponsor_ID, $Hospital_Ward_ID,  $start_age, $end_age ,$Admisiontime, $ipd_time, $patitent_type, $reporttype, $valuesType), true);
    
        }else if($report_category=='transfer_in'){
            $transferType='in';
            $Admisiontime='within';
            $getData=json_decode(getTransferCount($fromDate, $toDate, $Sponsor_ID, $Hospital_Ward_ID,  $start_age, $end_age , $Admisiontime, $ipd_time, $patitent_type, $transferType, $valuesType), true);

        }else if($report_category=='transfer_out'){
            $transferType='out';
            $Admisiontime='within';
            $getData=json_decode(getTransferCount($fromDate, $toDate, $Sponsor_ID, $Hospital_Ward_ID,  $start_age, $end_age , $Admisiontime, $ipd_time, $patitent_type, $transferType, $valuesType), true);
        }else if($report_category=='Normal' || $report_category=='Death'){
            
            $Admisiontime='within';
            $getData=json_decode(getDischargeCount($fromDate, $toDate, $Sponsor_ID, $Hospital_Ward_ID,  $start_age, $end_age , $Admisiontime, $ipd_time, $patitent_type, $dischargeType, $valuesType), true);
        }else if($report_category=='transfer_pending'){
            $transferType='pending';
            $Admisiontime='within';
            $getData=json_decode(getTransferCount($fromDate, $toDate, $Sponsor_ID, $Hospital_Ward_ID,  $start_age, $end_age , $Admisiontime, $ipd_time, $patitent_type, $transferType, $valuesType), true);
        } else if($report_category=='Inpatient_Days'){
            $date_between= dateRange($fromDate, $toDate) ;
            $OBD = 0;
            $Admisiontime='BeforeObd';
            $reporttype='obd';

            $getData = json_decode(getAdmissioncount($fromDate, $toDate, $Sponsor_ID, $Hospital_Ward_ID,  $start_age, $end_age ,$Admisiontime, $ipd_time, $patitent_type, $reporttype, $valuesType), true);
            $male_countB_obd =$getData[0]['male_count'];
            $female_countB_obd =$getData[0]['female_count'];
            $last_OBD = $male_countB_obd + $female_countB_obd;
            // die(print_r($getData));
            foreach($date_between as $dateBetween){
            // for($i=0; $i<=sizeof($date_between); $i++){
                $fromDate = $dateBetween; 
                
                $Admisiontime='withinObd';                
                $Admisiondate = json_decode(getAdmissioncount($fromDate, $toDate, $Sponsor_ID, $Hospital_Ward_ID,  $start_age, $end_age ,$Admisiontime, $ipd_time, $patitent_type, $reporttype, $valuesType), true);
                $adMale_date = $Admisiondate[0]['male_count'];
                $adFemale_date = $Admisiondate[0]['female_count'];
                $withinadmin = $adFemale_date +$adMale_date;
                
                $transferType='in';
                $transferInDate=json_decode(getTransferCount($fromDate, $toDate, $Sponsor_ID, $Hospital_Ward_ID,  $start_age, $end_age , $Admisiontime, $ipd_time, $patitent_type, $transferType, $valuesType), true);
                $InMale_date = $transferInDate[0]['male_count'];
                $InFemale_date = $transferInDate[0]['female_count'];
                $withinInTransfer = $InFemale_date+$InMale_date;
                
                $transferType='out';
                $transferOutDate=json_decode(getTransferCount($fromDate, $toDate, $Sponsor_ID, $Hospital_Ward_ID,  $start_age, $end_age , $Admisiontime, $ipd_time, $patitent_type, $transferType, $valuesType), true);
                $OutMale_date = $transferOutDate[0]['male_count'];
                $OutFemale_date = $transferOutDate[0]['female_count'];
                $withinOutTransfer = $OutFemale_date+$OutMale_date;
                
                $dischargeDate=json_decode(getDischargeCount($fromDate, $toDate, $Sponsor_ID, $Hospital_Ward_ID,  $start_age, $end_age , $Admisiontime, $ipd_time, $patitent_type, $dischargeType, $valuesType), true);
                $dischargeMale_date = $transferOutDate[0]['male_count'];
                $dischargeFemale_date = $transferOutDate[0]['female_count'];
                $withinDischarge = $dischargeFemale_date+$dischargeMale_date;

                $male_count =(($last_OBD+ $adMale_date+$InMale_date) -($OutMale_date+$dischargeMale_date));
                $female_count=(($last_OBD+ $adFemale_date+$InFemale_date) -($OutFemale_date+$dischargeFemale_date));

                // $within_Obd =($last_OBD+ $withinadmin+$withinInTransfer -($withinOutTransfer+$withinDischarge));
               
            }
            
           
        }
        // die(print_r($Admisionwithin));
        /** END OF NEW FUNCTION */

        $male_count =$getData[0]['male_count'];
        $female_count =$getData[0]['female_count'];
        $subtotal=$male_count +$female_count;
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

}else{
    echo "<tr><td colspan='4' style='color:red;'>No result found</td></tr>";
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
  