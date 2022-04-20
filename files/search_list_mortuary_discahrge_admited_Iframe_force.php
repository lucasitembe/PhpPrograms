<?php
    include("./includes/connection.php");
    
    $filter = "   AND Admission_Status = 'Admitted' ";

if (isset($_GET['Sponsor'])) {

    $Date_From = filter_input(INPUT_GET, 'Date_From');
    $Date_To = filter_input(INPUT_GET, 'Date_To');
    $Patient_Name = filter_input(INPUT_GET, 'Patient_Name');
    $Patient_Number = filter_input(INPUT_GET, 'Patient_Number');
    $Sponsor = filter_input(INPUT_GET, 'Sponsor');
    $ward = filter_input(INPUT_GET, 'ward');

    if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
        $filter = "  AND Admission_Status = 'Admitted'  AND ad.Admission_Date_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
    }

    if (!empty($Sponsor) && $Sponsor != 'All') {
        $filter .="  AND sp.Sponsor_ID=$Sponsor";
    }
//echo $ward;exit;
    if (!empty($ward) && $ward != 'All') {
        $filter .= " AND ad.Hospital_Ward_ID  = $ward";
    }

    if (!empty($Patient_Name)) {
        $filter .="  AND pr.Patient_Name LIKE '%$Patient_Name%'";
    }
    if (!empty($Patient_Number)) {
        $filter .="  AND ad.Registration_ID = '$Patient_Number'";
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
                <th><b>FULL NAME</b></th>
                <th><b>REG NO</b></th>
                <th><b>GENDER</b></th>
                <th><b>AGE</b></th>
                <th><b>CASE TYPE</b></th>
                <th><b>DEADLINE</b></th>
                <th><b>WARD</b></th>
                <th><b>NEXT OF KIN</b></th>
                <th><b>NEXT OF KIN NO</b></th>
                <th><b>ADMISSION DATE</b></th>
                <th><b>DISCHARGE REASON</b></th>
                 <th><b>CHANGE CONDITION OF BODY</b></th>
                <th>&nbsp;</th>
             </tr>
         </thead>";

$select_Filtered_Patients = mysqli_query($conn, "SELECT  pr.Registration_ID,ad.Admision_ID,pr.Patient_Name,pr.Gender,pr.Date_Of_Birth,ma.*,hw.Hospital_Ward_Name,ad.Kin_Name,ad.Kin_Phone,ad.Admission_Date_Time FROM tbl_patient_registration pr,
 tbl_admission ad, tbl_check_in_details cd, tbl_mortuary_admission ma, tbl_hospital_ward hw	WHERE pr.Registration_ID = ad.Registration_ID AND ma.Admision_ID=ad.Admision_ID AND	ad.Admision_ID = cd.Admission_ID AND ad.Hospital_Ward_ID = hw.Hospital_Ward_ID $filter GROUP BY pr.Registration_ID LIMIT 20") or die(mysqli_error($conn));
		
	$sn = 1;
    while($row = mysqli_fetch_array($select_Filtered_Patients)){
	
	 $age = floor((strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926) . " Years";
    // if($age == 0){

    
    $date1 = new DateTime($row['Date_Of_Death']);
    $date2 = new DateTime($row['Date_Of_Birth']);
    $diff = $date1->diff($date2);
    $age = $diff->y . " Years, ";
    $age .= $diff->m . " Months, ";
    $age .= $diff->d . " Days";
   $Registration_ID = $row['Registration_ID'];
   $Corpse_Brought_By = $row['Corpse_Brought_By'];
   $Corpse_Kin_Phone = $row['Corpse_Kin_Phone'];
   
   //KUNTA ADD QUEERY TO SELECT FOLIO NUMBER  CHECK IN FOR APPROVAL DISCAHARGE
$select_check=mysqli_query($conn,"SELECT Check_In_ID FROM tbl_check_in WHERE Registration_ID = '$Registration_ID' order by Check_In_ID  desc limit 1") or die(mysqli_error($conn));
$num_check = mysqli_fetch_assoc($select_check);
$Check_In_ID= $num_check['Check_In_ID'];
$select = mysqli_query($conn,"SELECT Patient_Bill_ID, Sponsor_ID, Folio_Number from tbl_patient_payments where Registration_ID = '$Registration_ID'  order by Patient_Payment_ID desc limit 1") or die(mysqli_error($conn));

//die("mambo");
if(mysqli_num_rows($select)>0){
    $num = mysqli_fetch_assoc($select);
$Patient_Bill_ID= $num['Patient_Bill_ID'];
$Sponsor_ID= $num['Sponsor_ID'];
$Folio_Number=$num['Folio_Number'];
}
// END OF KUNTACODE APPROVAL FUNCTION

   
   
     echo "<tr><td>" . $sn . "</td>";                                                                                                
    echo "<td>" . ucwords(strtolower($row['Patient_Name'])) . "</td>";
    echo "<td>" . $Registration_ID . "</td>";
    echo "<td>" . $row['Gender'] . "</td>";

    echo "<td>" . $age . "</td>";

    echo "<td>" . $row['case_type'] . "</td>";
	
	
			$deadline = $row['Admission_Date_Time'];
			$deadline = strtotime($deadline);
			$deadline = strtotime("+7 day", $deadline);
			//echo date('d/m/Y', strtotime('+7 days'));
			$deadline = date('Y-M-d', strtotime('+7 days'));

	
    echo "<td>" . $deadline . "</td>";
    echo "<td>" . $row['Hospital_Ward_Name'] . "</td>";
    
    echo "<td>" . $Corpse_Brought_By . "</td>";
    echo "<td>" . $Corpse_Kin_Phone . "</td>";

    echo "<td>" . $row['Admission_Date_Time'] . "</td>";
	$Discharge_Reason="death";
    // $DB_Discharge_Reason = $output['DB_Discharge_Reason'];
	
	echo "<td><select id='reason_".$row['Admision_ID']."' class='Discharge_Reason_ID' name='Discharge_Reason_ID' required='required' syle='width:31%'>"; 
                        //  echo '<option></option>';	
                        $select_discharge_reason = "SELECT Discharge_Reason_ID, Discharge_Reason FROM tbl_discharge_reason where Discharge_Reason = '$Discharge_Reason'";
                        $reslt = mysqli_query($conn,$select_discharge_reason);
                        while ($output = mysqli_fetch_assoc($reslt)) {
						   $DB_Discharge_Reason=$output["Discharge_Reason"];
                        //    $selected=($Discharge_Reason==strtolower($DB_Discharge_Reason)?"selected='selected'":"");							
                           echo '<option value="'.$output["Discharge_Reason_ID"].'">'.$output["Discharge_Reason"].'</option>';   
                            
                        }
                        
    echo '</select></td>';
    

      $Admision_ID=$row['Admision_ID'];
       echo "<td><select id='".$row['Admision_ID']."' class='inalala_bilakulala' name='inalala_bilakulala' required='required' syle='width:31%'>"; 
                         echo '<option></option>';	
                        $inalala_bilakulala = "SELECT inalala_bilakulala FROM tbl_mortuary_admission where Admision_ID = '$Admision_ID'";
                        $reslt = mysqli_query($conn,$inalala_bilakulala);
                        while ($row = mysqli_fetch_assoc($reslt)) {
						   $inalala_bilakulala=$row["inalala_bilakulala"];
                           $selected="selected='selected'";							
                           echo '<option '.$selected.' value="'.$row["inalala_bilakulala"].'">'.$row["inalala_bilakulala"].'</option>';   
                            
                        }
                        
    echo '</select></td>';
      
	echo "<td><button  type='button' class='allow art-button'  onclick='forceadmit(\"$Admision_ID\",\"$Registration_ID\",\"$Patient_Bill_ID\",\"$Sponsor_ID\",\"$Folio_Number\",\"$Check_In_ID\")'>Allow Discharge</button></td>";	
       
        
        

        
//        echo "<td>" . $row['inalala_bilakulala'] . "</td>";
        $sn++;
    

	echo "</tr>";
 }
?>
</table></center>