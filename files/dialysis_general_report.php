<?php include ("./includes/connection.php");
$Branch_ID = 0;
$Gender = '';
$Region = '';
if (isset($_GET['Payment_Item_Cache_List_ID'])) {
    $Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
} else {
    $Payment_Item_Cache_List_ID = '';
}
if (isset($_GET['Patient_Payment_ID'])) {
    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
} else {
    $Patient_Payment_ID = '';
}
if (isset($_GET['dialysis_details_ID'])) {
    $dialysis_details_ID = $_GET['dialysis_details_ID'];
} else {
    $dialysis_details_ID = '';
}
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = '';
}
if (isset($_GET['Patient_Payment_ID'])) {
    $Payment_Cache_ID = $_GET['Patient_Payment_ID'];
} else {
    $Payment_Cache_ID = '';
}
if (isset($_GET['Attendance_Date'])) {
    $Attendance_Date = $_GET['Attendance_Date'];
} else {
    $Attendance_Date = '';
}
$consultation_id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT consultation_id FROM tbl_dialysis_details WHERE Patient_reg='$Registration_ID' AND dialysis_details_ID='$dialysis_details_ID'  order by dialysis_details_ID desc limit 1")) ['consultation_id'];
$end_date = '';
$start_date = '';

// $initianame = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Consultant_employee'")) ['Employee_Name'];
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
    $select_Patient = mysqli_query($conn, "select
    Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,Date_Of_Birth,
            Gender,pr.Region,pr.District,pr.Ward,
                Member_Number,Member_Card_Expire_Date,
                    pr.Phone_Number,Email_Address,Occupation,
                        Employee_Vote_Number,Emergence_Contact_Name,
                            Emergence_Contact_Number,Company,Registration_ID,
                                Employee_ID,Registration_Date_And_Time,Guarantor_Name,Claim_Number_Status,
                                Registration_ID
              from tbl_patient_registration pr, tbl_sponsor sp 
                where pr.Sponsor_ID = sp.Sponsor_ID and 
                Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) {
            $Registration_ID = $row['Registration_ID'];
            $Old_Registration_Number = $row['Old_Registration_Number'];
            $Title = $row['Title'];
            $Patient_Name = $row['Patient_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $Region = $row['Region'];
            $District = $row['District'];
            $Ward = $row['Ward'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Claim_Number_Status = $row['Claim_Number_Status'];
            $Member_Number = $row['Member_Number'];
            $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
            $Phone_Number = $row['Phone_Number'];
            $Email_Address = $row['Email_Address'];
            $Occupation = $row['Occupation'];
            $Employee_Vote_Number = $row['Employee_Vote_Number'];
            $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
            $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
            $Company = $row['Company'];
            $Employee_ID = $row['Employee_ID'];
            $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
        }
        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days.";
    } else {
        $Registration_ID = '';
        $Old_Registration_Number = '';
        $Title = '';
        $Patient_Name = '';
        $Sponsor_ID = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Region = '';
        $District = '';
        $Ward = '';
        $Guarantor_Name = '';
        $Claim_Number_Status = '';
        $Member_Number = '';
        $Member_Card_Expire_Date = '';
        $Phone_Number = '';
        $Email_Address = '';
        $Occupation = '';
        $Employee_Vote_Number = '';
        $Emergence_Contact_Name = '';
        $Emergence_Contact_Number = '';
        $Company = '';
        $Employee_ID = '';
        $Registration_Date_And_Time = '';
        $age = 0;
    }
}
$temp = 1;
$htm = '<table width ="100%" border="0" style="background-color:white;" class="nobordertable">
          <tr>
             <td style="text-align:center"><img src="branchBanner/branchBanner.png" width="100%" /></td>
          </tr>
          <tr>
             <td  style="text-align:center;font-size:17px"><b>DIALYSYS  GENERAL REPORT</b><br/> </td>
          </tr>';
    $htm.= '<tr>
             <td  style="text-align:center;font-size:17px"><b>PATIENT NAME: </b>' . strtoupper($Patient_Name) . '<br/><br/></td>
          </tr>';
          $htm.= '<tr>
             <td  style="text-align:center;font-size:17px"><b>DIALYSIS DATE : </b>' . $Attendance_Date . '<br/><br/></td>
          </tr>';
$htm.= '</table><br/>';
$htm.= '<center><table width ="100%" style="background-color:white;" id="patients-list">';
// $age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days.";

$sn = 1;
        $select_Filtered_Patients = mysqli_query($conn, "SELECT * FROM tbl_dialysis_vitals WHERE dialysis_details_ID='$dialysis_details_ID' AND Patient_reg='$Registration_ID' order by id desc limit 1") or die(mysqli_error($conn));

        $htm.= "<thead>
            <tr>
                <th style='font-size:17px;text-align:center;background-color:#006400;color:white'  colspan='13'><b>VITALS</b></th>
            </tr>
            </thead>";
        $htm.= "<thead>
            <tr>
                <td><b>VITALS</b></td>
                <td colspan='4'><b>Previous Post (Last Attendance Date:)</b></td>
                <td colspan='4'><b>PRE VITALS</b></td>
                <td colspan='4'><b>POST VITALS</b></td>
            </tr>
            </thead>";
            $htm .="<tbody>";

while ($vitals = mysqli_fetch_assoc($select_Filtered_Patients)) {
    $date1 = new DateTime(date('Y-m-d'));
        $date2 = new DateTime($row['Date_Of_Birth']);
        $diff = $date1->diff($date2);
        $htm.= "<tr>";
            $htm.= "<td>" .Pulse."</td>";
            $htm.= "<td colspan='4'>" . $vitals['Pulse_previous_post'] . "</td>";
            $htm.= "<td colspan='4'>" . $vitals['Pulse_pre'] . "</td>";
            $htm.= "<td colspan='4'>" . $vitals['Pulse_post'] . "</td>";
        $htm.= "</tr>";
        $htm.= "<tr>";
            $htm.= "<td>" .Respiration."</td>";
            $htm.= "<td colspan='4'>" . $vitals['Respiration_previous_post'] . "</td>";
            $htm.= "<td colspan='4'>" . $vitals['Respiration_pre'] . "</td>";
            $htm.= "<td colspan='4'>" . $vitals['Respiration_post'] . "</td>";
        $htm.= "</tr>";
        $htm.= "<tr>";
            $htm.= "<td>" .Temperature."</td>";
            $htm.= "<td colspan='4'>" . $vitals['Temperature_previous_post'] . "</td>";
            $htm.= "<td colspan='4'>" . $vitals['Temperature_pre'] . "</td>";
            $htm.= "<td colspan='4'>" . $vitals['Temperature_post'] . "</td>";
        $htm.= "</tr>";
        $htm.= "<tr>";
            $htm.= "<td>" .BP."</td>";
            $htm.= "<td>" .Sit."</td>";
            $htm.= "<td>" . $vitals['bpPrevious_pre_sit'] . "</td>";
            $htm.= "<td>" .Stand."</td>";
            $htm.= "<td>" . $vitals['bpPrevious_Post_stand'] . "</td>";
            $htm.= "<td>" .Sit."</td>";
            $htm.= "<td>" . $vitals['bpPre_sit'] . "</td>";
            $htm.= "<td>" .Stand."</td>";
            $htm.= "<td>" . $vitals['bpPre_stand'] . "</td>";
            $htm.= "<td>" .Sit."</td>";
            $htm.= "<td>" . $vitals['bpPost_sit'] . "</td>";
            $htm.= "<td>" .Stand."</td>";
            $htm.= "<td>" . $vitals['bpPost_stand'] . "</td>";
        $htm.= "</tr>";
            $htm.= "<tr>";
            $htm.= "<td>" .Weight."</td>";
            $htm.= "<td colspan='4'>" . $vitals['Weight_Previous_Post_stand'] . "</td>";
            $htm.= "<td  colspan='4'>" . $vitals['Weight_Pre_stand'] . "</td>";
            $htm.= "<td  colspan='4'>" . $vitals['Weight_Post_stand'] . "</td>";
        $htm.= "</tr>";
        $htm.= "<tr>";
            $htm.= "<td>" .''."</td>";
            $htm.= "<td colspan='2'>" .WeightGain . "</td>";
            $htm.= "<td colspan='2'>" . $vitals['Weight_Gain'] . "</td>";
            $htm.= "<td colspan='2'>" . WeightRemoval . "</td>";
            $htm.= "<td colspan='2'>" . $vitals['Weight_removal'] . "</td>";
            $htm.= "<td colspan='2'>" . TimeOff . "</td>";
            $htm.= "<td colspan='2'>" . $vitals['Time_Off '] . "</td>";
        $htm.= "</tr>";
        $htm.= "<tr>";
            $htm.= "<td>" .''."</td>";
            $htm.= "<td colspan='2'>" .EDW . "</td>";
            $htm.= "<td colspan='2'>" . $vitals['edw'] . "</td>";
            $htm.= "<td colspan='2'>" . TimeOn . "</td>";
            $htm.= "<td colspan='2'>" . $vitals['Time_On'] . "</td>";
        $htm.= "</tr>";
        $htm.= "<tr>";
            $htm.= "<td>" .Diagnosis."</td>";
            $htm.= "<td colspan='12'>" . $vitals['Diagnosis'] . "</td>";
        $htm.= "</tr>";
        $htm.= "<tr>";
            $htm.= "<td>" .Remarks."</td>";
            $htm.= "<td colspan='12'>" . $vitals['Remarks'] . "</td>";
        $htm.= "</tr>";
        $htm.= "<tr>";
            $htm.= "<td>" .Management."</td>";
            $htm.= "<td colspan='12'>" . $vitals['Management'] . "</td>";
        $htm.= "</tr>";
        
}
$htm .="</tbody>";
$htm.= "</table></center>";


$htm.= '<center><table width ="100%" style="background-color:white;" id="patients-list">';
        $machine_assesment = mysqli_query($conn, "SELECT * FROM tbl_save_machine_access WHERE  dialysis_details_ID ='$dialysis_details_ID' AND Patient_reg='$Registration_ID' order by id desc limit 1") or die(mysqli_error($conn));

        $htm.= "<thead>
            <tr>
                <th style='font-size:17px;text-align:center;background-color:#006400;color:white'  colspan='15'  colspan='9'><b>MACHINE ASSESMENT</b></th>
            </tr>
            </thead>";
        $htm.= "<thead>
            <tr>
                <td><b>Conductivity Machine</b></td>
                <td ><b>Temperature Machine</b></td>
                <td ><b>Alarm Test</b></td>
                <td ><b>Air Detector on</b></td>
                <td ><b>UF System</b></td>
                <td ><b>Positive Presence Test</b></td>
                <td ><b>Negative Residual Test</b></td>
                <td ><b>Dialyzer ID</b></td>
                <td ><b>Initial</b></td>
            </tr>
            </thead>";
            $htm .="<tbody>";

while ($machine = mysqli_fetch_assoc($machine_assesment)) {
    $date1 = new DateTime(date('Y-m-d'));
        $date2 = new DateTime($row['Date_Of_Birth']);
        $diff = $date1->diff($date2);

        // $age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days.";
        // $htm.= "<tr><td>" . $sn++ . "</td> <td>" . $row2['Patient_Name'] . "</td>";
        $htm.= "<tr>";
            $htm.= "<td>" . $machine['Conductivity_Machine']. "</td>";
            $htm.= "<td>" . $machine['Temperature_Machine'] . "</td>";
            $htm.= "<td>" . $machine['Alarm_Test'] . "</td>";
            $htm.= "<td>" . $machine['Air_Detector'] . "</td>";
            $htm.= "<td>" . $machine['UF_System'] . "</td>";
            $htm.= "<td>" . $machine['Positive_Presence'] . "</td>";
            $htm.= "<td>" . $machine['Negative_Residual'] . "</td>";
            $htm.= "<td>" . $machine['Dialyzer_ID'] . "</td>";
            $htm.= "<td>" . $machine['UF_System_initial']. "</td>";
        $htm.= "</tr>";
        // @gpitg20ehms;/
}
$htm .="</tbody>";
$htm.= "</table></center>";


$htm.= '<center><table width ="100%" style="background-color:white;" id="patients-list">';
        $heparin_assesment = mysqli_query($conn, "SELECT * FROM tbl_heparain_save WHERE  dialysis_details_ID ='$dialysis_details_ID' AND Patient_reg='$Registration_ID' order by id desc limit 1") or die(mysqli_error($conn));

        $htm.= "<thead>
            <tr>
                <th style='font-size:17px;text-align:center;background-color:#006400;color:white'   colspan='9'><b>HEPARIN</b></th>
            </tr>
            </thead>";
        $htm.= "<thead>
            <tr>
                <td><b>Type</b></td>
                <td ><b>Initial Bolus Units</b></td>
                <td ><b>Unit/Hr </b></td>
                <td ><b>Stop time</b></td>
                <td ><b>CVC Post Instil </b></td>
                <td ><b>Arterial</b></td>
                <td ><b>Venous</b></td>
            </tr>
            </thead>";
            $htm .="<tbody>";

while ($hyperline = mysqli_fetch_assoc($heparin_assesment)) {
    $date1 = new DateTime(date('Y-m-d'));
        $date2 = new DateTime($row['Date_Of_Birth']);
        $diff = $date1->diff($date2);

        // $age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days.";
        // $htm.= "<tr><td>" . $sn++ . "</td> <td>" . $row2['Patient_Name'] . "</td>";
        $htm.= "<tr>";
            $htm.= "<td>" . $hyperline['Type']. "</td>";
            $htm.= "<td>" . $hyperline['Initial_Bolus'] . "</td>";
            $htm.= "<td>" . $hyperline['Unit_Hr'] . "</td>";
            $htm.= "<td>" . $hyperline['Stop_time'] . "</td>";
            $htm.= "<td>" . $hyperline['CVC_Pos'] . "</td>";
            $htm.= "<td>" . $hyperline['Arterial'] . "</td>";
            $htm.= "<td>" . $hyperline['Venous'] . "</td>";
        $htm.= "</tr>";
        
}
$htm .="</tbody>";
$htm.= "</table></center>";

$htm.= '<center><table width ="100%" style="background-color:white;" id="patients-list">';
// $age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days.";

$sn = 1;
        $select_collectionrow = mysqli_query($conn, "SELECT *FROM tbl_data_collection WHERE dialysis_details_ID='$dialysis_details_ID' AND Patient_reg='$Registration_ID'  order by id desc limit 1") or die(mysqli_error($conn));

        $htm.= "<thead>
            <tr>
                <th style='font-size:17px;text-align:center;background-color:#006400;color:white'colspan='3'><b>DATA COLLECTION</b></th>
            </tr>
            </thead>";
        $htm.= "<thead>
            <tr>
                <td><b></b></td>
                <td><b>PRE</b></td>
                <td><b>POST</b></td>
            </tr>
            </thead>";
            $htm .="<tbody>";

while ($collectionrow = mysqli_fetch_assoc($select_collectionrow)) {
    $date1 = new DateTime(date('Y-m-d'));
        $date2 = new DateTime($row['Date_Of_Birth']);
        $diff = $date1->diff($date2);

        // $age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days.";
        // $htm.= "<tr><td>" . $sn++ . "</td> <td>" . $row2['Patient_Name'] . "</td>";
        $htm.= "<tr>";
            $htm.= "<td>" .Temperature."</td>";
            $htm.= "<td>" . $collectionrow['Temp_Pre_Assessment'] . "</td>";
            $htm.= "<td>" . $collectionrow['Temp_Post'] . "</td>";
        $htm.= "</tr>";
        $htm.= "<tr>";
            $htm.= "<td>" .Respiration."</td>";
            $htm.= "<td>" .  $collectionrow['Resp_Pre_Assessment'] . "</td>";
            $htm.= "<td>" . $collectionrow['Resp_Post'] . "</td>";
        $htm.= "</tr>";
        $htm.= "<tr>";
            $htm.= "<td>" .GI."</td>";
            $htm.= "<td>" . $collectionrow['GI_Pre_Assessment'] . "</td>";
            $htm.= "<td>" . $collectionrow['GI_Post'] . "</td>";
            $htm.= "</tr>";
        $htm.= "<tr>";
            $htm.= "<td>" .Cardiac."</td>";
            $htm.= "<td>" . $collectionrow['Cardiac_Pre_Assessment'] . "</td>";
            $htm.= "<td>" . $collectionrow['Cardiac_Post']. "</td>";
        $htm.= "</tr>";
            $htm.= "<tr>";
            $htm.= "<td>" .Edema."</td>";
            $htm.= "<td>" . $collectionrow['Edema_Pre_Assessment'] . "</td>";
            $htm.= "<td>" . $collectionrow['Edema_Post'] . "</td>";
        $htm.= "</tr>";
            $htm.= "<tr>";
            $htm.= "<td>" .Mental."</td>";
            $htm.= "<td>" . $collectionrow['Mental_Pre_Assessment'] . "</td>";
            $htm.= "<td>" . $collectionrow['Mental_Post'] . "</td>";
        $htm.= "</tr>";
        $htm.= "<tr>";
            $htm.= "<td>" .Mobility . "</td>";
            $htm.= "<td>" . $collectionrow['Mobility_Pre_Assessment'] . "</td>";
            $htm.= "<td>" . $collectionrow['Mobility_Post'] . "</td>";
        $htm.= "</tr>";
        $htm.= "<tr>";
            $htm.= "<td>" .Access . "</td>";
            $htm.= "<td>" . $collectionrow['Access_Pre_Assessment'] . "</td>";
            $htm.= "<td>" . $collectionrow['Access_Post'] . "</td>";
        $htm.= "</tr>";
        $htm.= "<tr>";
            $htm.= "<td>" .Time . "</td>";
            $htm.= "<td colspan='2'>" . $collectionrow['Temp_Time'] . "</td>";
        $htm.= "</tr>";
        $htm.= "<tr>";
            $htm.= "<td>" .Initials . "</td>";
            $htm.= "<td colspan='2'>" . $collectionrow['Temp_Initials']. "</td>";
        $htm.= "</tr>";
        
}
$htm .="</tbody>";
$htm.= "</table></center>";

$htm.= '<center><table width ="100%" style="background-color:white;" id="patients-list">';
        $observation_assesment = mysqli_query($conn, "SELECT * FROM tbl_observation_chart as obs,tbl_employee as emp WHERE  obs.dialysis_details_ID ='$dialysis_details_ID' AND obs.Patient_reg='$Registration_ID'  AND obs.Consultant_employee=emp.Employee_ID") or die(mysqli_error($conn));

        $htm.= "<thead>
            <tr style=''>
                <th style='font-size:17px;text-align:center;background-color:#006400;color:white'  colspan='15'><b>OBSERVATION CHART</b></th>
            </tr>
            </thead>";
        $htm.= "<thead>
            <tr>
                <td><b>TIME</b></td>
                <td><b>BP</b></td>
                <td ><b>HR</b></td>
                <td ><b>QB </b></td>
                <td ><b>QD</b></td>
                <td ><b>AP </b></td>
                <td ><b>VP </b></td>
                <td ><b>FldRmvd</b></td>
                <td ><b>Heparin</b></td>
                <td ><b>Saline</b></td>
                <td ><b>UFR</b></td>
                <td ><b>TMP</b></td>
                <td ><b>Access</b></td>
                <td ><b>Notes</b></td>
                <td ><b>Initials</b></td>
            </tr>
            </thead>";
            $htm .="<tbody>";

            while ($observ = mysqli_fetch_assoc($observation_assesment)) {
                $date1 = new DateTime(date('Y-m-d'));
                    $date2 = new DateTime($row['Date_Of_Birth']);
                    $diff = $date1->diff($date2);
            
                    // $age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days.";
                    // $htm.= "<tr><td>" . $sn++ . "</td> <td>" . $row2['Patient_Name'] . "</td>";
                    $htm.= "<tr>";
                        $htm.= "<td>" . $observ['Time']. "</td>";
                        $htm.= "<td>" . $observ['BP'] . "</td>";
                        $htm.= "<td>" . $observ['HR'] . "</td>";
                        $htm.= "<td>" . $observ['QB'] . "</td>";
                        $htm.= "<td>" . $observ['QD'] . "</td>";
                        $htm.= "<td>" . $observ['AP'] . "</td>";
                        $htm.= "<td>" . $observ['VP'] . "</td>";
                        $htm.= "<td>" . $observ['FldRmvd'] . "</td>";
                        $htm.= "<td>" . $observ['Heparin'] . "</td>";
                        $htm.= "<td>" . $observ['Saline'] . "</td>";
                        $htm.= "<td>" . $observ['UFR'] . "</td>";
                        $htm.= "<td>" . $observ['TMP'] . "</td>";
                        $htm.= "<td>" . $observ['Access'] . "</td>";
                        $htm.= "<td>" . $observ['Notes'] . "</td>";
                        $htm.= "<td>" . $observ['Employee_Name'] . "</td>";
        $htm.= "</tr>";
        
}
$htm .="</tbody>";
$htm.= "</table></center>";

$htm.= '<center><table width ="100%" style="background-color:white;" id="patients-list">';
        $medication_chart = mysqli_query($conn, "SELECT given_time,sg.route_type,drip_rate,From_outside_amount,Product_Name,Time_Given,Amount_Given,Nurse_Remarks,Discontinue_Status,Discontinue_Reason,Employee_Name "
        . "FROM tbl_inpatient_medicines_given sg, tbl_items it, tbl_employee em WHERE em.Employee_ID = sg.Employee_ID AND it.Item_ID = sg.Item_ID AND sg.Registration_ID='$Registration_ID' AND date(sg.Time_Given)='$Attendance_Date' ORDER BY sg.Time_Given DESC  ") or die(mysqli_error($conn));

        $htm.= "<thead>
            <tr style=''>
                <th style='font-size:17px;text-align:center;background-color:#006400;color:white'  colspan='15'><b>MEDICATIOON CHART</b></th>
            </tr>
            </thead>";
        $htm.= "<thead>
            <tr>
            <td><b>Medicine Name</b></td>
            <td><b>BP</b></td>
            <td ><b>Dose</b></td>
            <td ><b>Route </b></td>
            <td ><b>Amount Given</b></td>
            <td ><b>Given time </b></td>
            <td ><b>Nurse/Significant Events and Interventions </b></td>
            <td ><b>Discontinued</b></td>
            <td ><b>From Outside Amount</b></td>
            <td ><b>Given by</b></td>
            </tr>
            </thead>";
            $htm .="<tbody>";
    if(mysqli_num_rows($medication_chart) > 0){

    
while ($med = mysqli_fetch_assoc($medication_chart)) {
    $date1 = new DateTime(date('Y-m-d'));
        $date2 = new DateTime($row['Date_Of_Birth']);
        $diff = $date1->diff($date2);

        // $age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days.";
        // $htm.= "<tr><td>" . $sn++ . "</td> <td>" . $row2['Patient_Name'] . "</td>";
        $htm.= "<tr>";
        $htm.= "<td>" . $med['Product_Name']. "</td>";
        $htm.= "<td>" . $med['given_time'] . "</td>";
        $htm.= "<td>" . $med['route_type'] . "</td>";
        $htm.= "<td>" . $med['Time_Given'] . "</td>";
        $htm.= "<td>" . $med['Amount_Given'] . "</td>";
        $htm.= "<td>" . $med['Time_Given'] . "</td>";
        $htm.= "<td>" . $med['Nurse_Remarks'] . "</td>";
        $htm.= "<td>" . $med['Discontinue_Status'] . "</td>";
        $htm.= "<td>" . $med['From_outside_amount'] . "</td>";
        $htm.= "<td>" . $med['Employee_Name'] . "</td>";
        $htm.= "</tr>";
        
}
    }else{
        $htm.=  "<tr><td colspan='11' style='text-align:center;color:red'>No MEDICATION CHART </td></tr>";
    }
$htm .="</tbody>";
$htm.= "</table></center>";


include ("MPDF/mpdf.php");
$mpdf = new mPDF('s', 'A4');
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0;
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1);
$mpdf->WriteHTML($htm, 2);
$mpdf->Output();
exit;
?>