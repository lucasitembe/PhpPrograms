<?php
    include("./includes/connection.php");
    
    // $filter = "   AND Admission_Status = 'Admitted' ";

if (isset($_GET['Sponsor'])) {

    $Date_From = filter_input(INPUT_GET, 'Date_From');
    $Date_To = filter_input(INPUT_GET, 'Date_To');
    $Patient_Name = filter_input(INPUT_GET, 'Patient_Name');
    $Search_Patient_number = filter_input(INPUT_GET, 'Search_Patient_number');
    $Sponsor = filter_input(INPUT_GET, 'Sponsor');
    $ward = filter_input(INPUT_GET, 'ward');
    $filter =" AND Admission_Status IN ('Admitted') ";
    if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
        $filter .= "   AND ad.Admission_Date_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
    
    }

    if (!empty($Sponsor) && $Sponsor != 'All') {
        $filter .="  AND sp.Sponsor_ID=$Sponsor";
    }
//echo $ward;exit;
    if (!empty($ward) && $ward != 'All') {
        $filter .= " AND ad.Hospital_Ward_ID  = $ward";
    }

    if (!empty($Patient_Name)) {
        $filter .="  AND pr.Patient_Name = '$Patient_Name'";
    }
    if (!empty($Search_Patient_number)) {
        $filter .="  AND pr.Registration_ID = '$Search_Patient_number'";
    }
}


//today function
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}
//end
echo '<center><table width ="100%" id="patientList">';
echo "<thead>
             <tr >
                <th style='width:5%;'>SN</th>
                <th><b>PATIENT NAME</b></th>
                <th><b>PATIENT NO</b></th>
                <th><b>GENDER</b></th>
                <th><b>AGE</b></th>
                <th><b>SPONSOR</b></th>
                <th><b>PHONE NUMBER</b></th>
                <th><b>WARD</b></th>
                <th><b>NEXT OF KIN</b></th>
                <th><b>NEXT OF KIN NO</b></th>
                <th><b>ADMISSION DATE</b></th>
                <th><b>DISCHARGE REASON</b></th>
                <th>&nbsp;</th>
             </tr>
         </thead>";

$select_Filtered_Patients = mysqli_query($conn,
        "SELECT Doctor_Status, pr.Registration_ID,ad.Admision_ID,pr.Patient_Name,pr.Gender,pr.Date_Of_Birth,sp.Guarantor_Name,pr.Phone_Number,hw.Hospital_Ward_Name,ad.Kin_Name,ad.Kin_Phone, Discharge_Reason_ID, ad.Admission_Date_Time FROM tbl_patient_registration pr, tbl_admission ad,  tbl_check_in_details cd, tbl_sponsor sp,  tbl_hospital_ward hw 	WHERE NOT EXISTS (SELECT ma.Admision_ID FROM tbl_mortuary_admission ma WHERE ma.Admision_ID=ad.Admision_ID) AND pr.Registration_ID=ad.Registration_ID AND   pr.Sponsor_ID=sp.Sponsor_ID AND  ad.Admision_ID=cd.Admission_ID AND  ad.Hospital_Ward_ID = hw.Hospital_Ward_ID $filter GROUP BY pr.Registration_ID LIMIT 200") or die(mysqli_error($conn));

		
	$sn = 1;
    while($row = mysqli_fetch_array($select_Filtered_Patients)){
	$Doctor_Status = $row['Doctor_Status'];
     $age = floor((strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926) . " Years";
     $Discharge_Reason_ID = $row['Discharge_Reason_ID'];
        $Admision_ID = $row['Admision_ID'];
        if($Doctor_Status =='initial_pending'){
            $dischargereson = "<select id='reason_$Admision_ID' class='Discharge_Reason_ID' name='Discharge_Reason_ID' onchange='check_if_dead_reasons($Registration_ID,$Admision_ID)' required='required' syle='width:31%'>"; 
            $reslt = mysqli_query($conn,"SELECT * FROM tbl_discharge_reason WHERE Discharge_Reason_ID='$Discharge_Reason_ID'") or die(mysqli_error($conn));//
            while ($output = mysqli_fetch_assoc($reslt)){                      
               $Discharge_Reason_ID = $output["Discharge_Reason_ID"];
                $Discharge_Reason = $output["Discharge_Reason"];                 
                $dischargereson.= "<option value='$Discharge_Reason_ID'>$Discharge_Reason</option>";   
            }
            $dischargereson.= "</select> ";
        }else{
            $dischargereson = 
            
            "<select id='reason_$Admision_ID' class='Discharge_Reason_ID' name='Discharge_Reason_ID' onchange='check_if_dead_reasons(".$Registration_ID.",".$Admision_ID.")' required='required' syle='width:31%'><option value=''></option>"; 

            $reslt = mysqli_query($conn,"SELECT * FROM tbl_discharge_reason ") or die(mysqli_error($conn));
            while ($output = mysqli_fetch_assoc($reslt)){     
                $Discharge_Reason_ID = $output["Discharge_Reason_ID"];
                $Discharge_Reason = $output["Discharge_Reason"];                 
                $dischargereson.= "<option value='$Discharge_Reason_ID'>$Discharge_Reason</option>";   
                
            }
            $dischargereson.= "</select> ";
        }
    $date1 = new DateTime($Today);
    $date2 = new DateTime($row['Date_Of_Birth']);
    $diff = $date1->diff($date2);
    $age = $diff->y . " Years, ";
    $age .= $diff->m . " Months, ";
    $age .= $diff->d . " Days";
    
    echo "<tr><td>" . $sn . "</td>";                                                                                                
    echo "<td>" . ucwords(strtolower($row['Patient_Name'])) . "</td>";
    echo "<td>" . $row['Registration_ID'] . "</td>";
    echo "<td>" . $row['Gender'] . "</td>";

    echo "<td>" . $age . "</td>";

    echo "<td>" . $row['Guarantor_Name'] . "</td>";
    echo "<td>" . $row['Phone_Number'] . "</td>";
    echo "<td>" . $row['Hospital_Ward_Name'] . "</td>";
    
    echo "<td>" . $row['Kin_Name'] . "</td>";
    echo "<td>" . $row['Kin_Phone'] . "</td>";

    echo "<td>" . $row['Admission_Date_Time'] . "</td>";
        $Registration_ID=$row['Registration_ID'];
        $Admision_ID=$row['Admision_ID'];
	echo "<td>$dischargereson";            
    echo '</td>'; 

      
      
	echo "<td><button  type='button' class='allow art-button'  onclick='forceadmit(".$row['Admision_ID'].")'>Allow Discharge</button></td>";	
        $sn++;
    

	echo "</tr>";
 }
?>
</table></center>