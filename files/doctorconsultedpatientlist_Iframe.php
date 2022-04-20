<?php
@session_start();
include("./includes/connection.php");
$filter =''; 
$Date_From = filter_input(INPUT_GET, 'Date_From');
$Date_To = filter_input(INPUT_GET, 'Date_To');
$Patient_Name = filter_input(INPUT_GET, 'Patient_Name');
$Patient_Number = filter_input(INPUT_GET, 'Patient_Number');
$Patient_Old_Number = filter_input(INPUT_GET, 'Patient_Old_Number');

$filter2="";
if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter2 = "  AND Clinic_consultation_date_time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
}else{
    $filter2 = "  AND DATE(Clinic_consultation_date_time)=CURDATE()";
}

$doctors_selected_clinic = $_SESSION['doctors_selected_clinic'];

if (!empty($Patient_Name)) {
    $filter .="  AND pr.Patient_Name like '%$Patient_Name%'";
}

if (!empty($Patient_Number)) {
    $filter .="  AND pr.Registration_ID = '$Patient_Number'";
}

if (!empty($Patient_Old_Number)) {
    $filter .="  AND pr.Old_Registration_Number = '$Patient_Old_Number'";
}

$temp = 1;
//GET BRANCH ID
$Folio_Branch_ID = $_SESSION['userinfo']['Branch_ID'];

//today function
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}
//end
?>
<script type='text/javascript'>
    function patientsignoff(Patient_Payment_Item_List_ID) {
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }
        mm.onreadystatechange = AJAXP; //specify name of function that will handle server response....
        mm.open('GET', 'patientsignoff.php?Patient_Payment_Item_List_ID=' + Patient_Payment_Item_List_ID, true);
        mm.send();
    }
    function AJAXP() {
        var data = mm.responseText;
        if (mm.readyState == 4) {
            document.location.reload();
        }
    }
</script>
<?php
$hospitalConsultType = $_SESSION['hospitalConsultaioninfo']['consultation_Type'];
$emp = '';
$isClinicOneToOne = false;
if ($hospitalConsultType == 'One patient to one doctor') {
    $emp = "AND ppl.Consultant_ID =" . $_SESSION['userinfo']['Employee_ID'] . " ";
    $isClinicOneToOne = true;
}

echo '<center><table width ="100%" id="consultpatients">';
echo " <thead>
            <tr >
                <th style='width:5%;'>SN</th>
                <th><b>PATIENT NAME</b></th>
                <th><b>OLD PATIENT NUMBER</b></th>
                <th><b>PATIENT NUMBER</b></th>
                <th><b>SPONSOR</b></th>
                <th><b>AGE</b></th>
                <th><b>GENDER</b></th>
                <th><b>PHONE NUMBER</b></th>
                <th><b>MEMBER NUMBER</b></th>
                <th><b>CONSULTED CLINIC</b></th>
                <th><b>LATEST CONSULTATION DATE</b></th>
                <th><b>CONSULTED DATE</b></th>
			</tr>
        </thead>";

$cons_query = mysqli_query($conn,"SELECT consulted_patient_display_max_time FROM tbl_hospital_consult_type WHERE Branch_ID='" . $_SESSION['userinfo']['Branch_ID'] . "'") or die(mysqli_error($conn));
$cons= mysqli_fetch_array($cons_query);
$consulted_patient_display_max_time=$cons['consulted_patient_display_max_time'];

$sql_select_payment_id_n_reg_id_result=mysqli_query($conn,"SELECT Check_In_ID,  c.Clinic_ID,Consultation_Date_And_Time,Clinic_consultation_date_time, Patient_Payment_Item_List_ID,pr.Old_Registration_Number,pr.Gender,pr.Patient_Name,pr.Phone_Number,pr.Member_Number,pr.Date_Of_Birth,pr.Sponsor_ID,pp.Patient_Payment_ID,pp.Registration_ID FROM tbl_patient_payments pp,tbl_patient_registration pr,tbl_consultation c WHERE pp.Registration_ID=pr.Registration_ID AND pp.Registration_ID=c.Registration_ID AND Transaction_status != 'cancelled' AND Process_Status='served' AND TIMESTAMPDIFF(DAY,c.Clinic_consultation_date_time,CURRENT_TIMESTAMP())<=$consulted_patient_display_max_time $filter2 $filter GROUP BY Clinic_consultation_date_time LIMIT 25") or die(mysqli_error($conn));

if(mysqli_num_rows($sql_select_payment_id_n_reg_id_result)>0){
   while($fetched_rows=mysqli_fetch_assoc($sql_select_payment_id_n_reg_id_result)){
       $Patient_Payment_ID=$fetched_rows['Patient_Payment_ID'];
       $Registration_ID=$fetched_rows['Registration_ID'];
       $Transaction_Date_And_Time=$fetched_rows['Consultation_Date_And_Time'];
       $Patient_Payment_Item_List_ID=$fetched_rows['Patient_Payment_Item_List_ID'];
        $Clinic_consultation_date_time = $fetched_rows['Clinic_consultation_date_time'];
        $Old_Registration_Number=$fetched_rows['Old_Registration_Number'];
        $Clinic_ID=$fetched_rows['Clinic_ID'];
        $Check_In_ID =$fetched_rows['Check_In_ID'];
        $Gender=$fetched_rows['Gender'];
        $Patient_Name=$fetched_rows['Patient_Name'];
        $Phone_Number=$fetched_rows['Phone_Number'];
        $Member_Number=$fetched_rows['Member_Number'];
        $Date_Of_Birth=$fetched_rows['Date_Of_Birth'];
        $Sponsor_ID=$fetched_rows['Sponsor_ID'];
        
        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";
    $Guarantor_Name=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'"))['Guarantor_Name'];

    $Clinic_Name=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Clinic_Name FROM tbl_clinic WHERE Clinic_ID='$Clinic_ID'"))['Clinic_Name'];

    $count=0;
    $select_checkin_qry = mysqli_query($conn,"SELECT Check_In_ID,Type_Of_Check_In FROM tbl_check_in WHERE Registration_ID = '" . $Registration_ID . "' ORDER BY Check_In_ID DESC LIMIT 4") or die(mysqli_error($conn));
    while($checkin = mysqli_fetch_assoc($select_checkin_qry)){
        if($Clinic_ID != $doctors_selected_clinic){
            $style="background-color:red; color:white;";
        }else{
            $style="background-color:green; color:white;";
        }
        
    $Check_In_ID = $checkin['Check_In_ID'];
    $Type_Of_Check_In = $checkin['Type_Of_Check_In'];
    $ToBe_Admitted_results = mysqli_query($conn,"SELECT ToBe_Admitted FROM tbl_check_in_details WHERE Registration_ID = '" . $Registration_ID . "' AND Check_In_ID = '$Check_In_ID'") or die(mysqli_error($conn));
    $ToBe_Admitted = mysqli_fetch_assoc($ToBe_Admitted_results)['ToBe_Admitted'];
    if($count>=1)break;
        if ($ToBe_Admitted == 'no') {
            $count++;           
                    
        echo "<tr><td>" . $temp . "</td><td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $Registration_ID . "&Check_In_ID=" . $Check_In_ID . "&Patient_Payment_ID=" . $Patient_Payment_ID . "&Patient_Payment_Item_List_ID=" . $Patient_Payment_Item_List_ID . "&NR=true&PatientBilling=PatientBillingThisForm&from_consulted=yes' target='_parent' style='text-decoration: none;'>" . ucwords(strtolower($Patient_Name)) . "</a></td>";
            echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $Registration_ID . "&Check_In_ID=" . $Check_In_ID . "&Patient_Payment_ID=" . $Patient_Payment_ID . "&Patient_Payment_Item_List_ID=" . $Patient_Payment_Item_List_ID . "&NR=true&PatientBilling=PatientBillingThisForm&from_consulted=yes' target='_parent' style='text-decoration: none;'>" . $Old_Registration_Number . "</a></td>";
            echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $Registration_ID . "&Check_In_ID=" . $Check_In_ID . "&Patient_Payment_ID=" . $Patient_Payment_ID . "&Patient_Payment_Item_List_ID=" . $Patient_Payment_Item_List_ID . "&NR=true&PatientBilling=PatientBillingThisForm&from_consulted=yes' target='_parent' style='text-decoration: none;'>" . $Registration_ID . "</a></td>";
            echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $Registration_ID . "&Check_In_ID=" . $Check_In_ID . "&Patient_Payment_ID=" . $Patient_Payment_ID . "&Patient_Payment_Item_List_ID=" . $Patient_Payment_Item_List_ID . "&NR=true&PatientBilling=PatientBillingThisForm&from_consulted=yes' target='_parent' style='text-decoration: none;'>" . $Guarantor_Name . "</a></td>";
            echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $Registration_ID . "&Check_In_ID=" . $Check_In_ID . "&Patient_Payment_ID=" . $Patient_Payment_ID . "&Patient_Payment_Item_List_ID=" . $Patient_Payment_Item_List_ID . "&NR=true&PatientBilling=PatientBillingThisForm&from_consulted=yes' target='_parent' style='text-decoration: none;'>" . $age . "</a></td>";
            echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $Registration_ID . "&Check_In_ID=" . $Check_In_ID . "&Patient_Payment_ID=" . $Patient_Payment_ID . "&Patient_Payment_Item_List_ID=" . $Patient_Payment_Item_List_ID . "&NR=true&PatientBilling=PatientBillingThisForm&from_consulted=yes' target='_parent' style='text-decoration: none;'>" . $Gender . "</a></td>";
            echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $Registration_ID . "&Check_In_ID=" . $Check_In_ID . "&Patient_Payment_ID=" . $Patient_Payment_ID . "&Patient_Payment_Item_List_ID=" . $Patient_Payment_Item_List_ID . "&NR=true&PatientBilling=PatientBillingThisForm&from_consulted=yes' target='_parent' style='text-decoration: none;'>" . $Phone_Number. "</a></td>";
            echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $Registration_ID . "&Check_In_ID=" . $Check_In_ID . "&Patient_Payment_ID=" . $Patient_Payment_ID . "&Patient_Payment_Item_List_ID=" . $Patient_Payment_Item_List_ID . "&NR=true&PatientBilling=PatientBillingThisForm&from_consulted=yes' target='_parent' style='text-decoration: none;'>" .$Member_Number . " </a></td>";
            echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $Registration_ID . "&Check_In_ID=" . $Check_In_ID . "&Check_In_ID=" . $Check_In_ID . "&Patient_Payment_ID=" . $Patient_Payment_ID . "&Patient_Payment_Item_List_ID=" . $Patient_Payment_Item_List_ID . "&NR=true&PatientBilling=PatientBillingThisForm&from_consulted=yes' target='_parent' style='text-decoration: none; $style'>" .$Clinic_Name . "</a></td>";

            echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $Registration_ID . "&Check_In_ID=" . $Check_In_ID . "&Patient_Payment_ID=" . $Patient_Payment_ID . "&Patient_Payment_Item_List_ID=" . $Patient_Payment_Item_List_ID . "&NR=true&PatientBilling=PatientBillingThisForm&from_consulted=yes' target='_parent' style='text-decoration: none;'>" . $Clinic_consultation_date_time . "</a></td>";
            echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $Registration_ID . "&Check_In_ID=" . $Check_In_ID . "&Patient_Payment_ID=" . $Patient_Payment_ID . "&Patient_Payment_Item_List_ID=" . $Patient_Payment_Item_List_ID . "&NR=true&PatientBilling=PatientBillingThisForm&from_consulted=yes' target='_parent' style='text-decoration: none;'>" . $Transaction_Date_And_Time . "</a></td>";
            
            echo "</tr>";     
            $temp++;
        }
        }
       
   } 
}
/////////////////////////////////////////////////


if ($isClinicOneToOne) {
   
    //select list from clinic
    $get_clinics_query = " SELECT Clinic_ID FROM tbl_clinic_employee ce WHERE ce.Employee_ID =" . $_SESSION['userinfo']['Employee_ID'] . "
            ";

//die($get_clinics_query);

    $get_clinics_result = mysqli_query($conn,$get_clinics_query) or die(mysqli_error($conn));



    while ($rowClinic = mysqli_fetch_array($get_clinics_result)) {
        $Clinic_ID = $rowClinic['Clinic_ID'];
        $qr = "SELECT pr.Registration_ID,MAX(c.Consultation_ID),ch.cons_hist_Date,pr.Gender,pr.Patient_Name,pr.Phone_Number,pr.Member_Number,pr.Date_Of_Birth,ppl.Transaction_Date_And_Time,ppl.Patient_Payment_Item_List_ID,pp.Patient_Payment_ID,s.Guarantor_Name
        FROM tbl_patient_registration pr,tbl_sponsor s, tbl_patient_payment_item_list ppl,tbl_consultation c,tbl_consultation_history ch,  tbl_patient_payments pp WHERE ch.Consultation_ID=c.Consultation_ID AND pr.Registration_ID = c.Registration_ID AND   pr.Sponsor_ID = s.Sponsor_ID AND ppl.Clinic_ID = " . $Clinic_ID . " AND c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID AND
		  c.Process_Status = 'served' AND pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND
		  pp.Registration_ID = pr.Registration_ID AND   pp.Branch_ID = " . $_SESSION['userinfo']['Branch_ID'] . " AND 
		  ppl.Process_Status = 'served' AND pp.Check_In_ID IS NOT NULL $filter
		  GROUP BY pp.Registration_ID  ORDER BY c.Consultation_ID DESC LIMIT 10 ";


        $select_Filtered_Patients = mysqli_query($conn,$qr) or die('eee ' . mysqli_error($conn));
        while ($row = mysqli_fetch_array($select_Filtered_Patients)) {

            //AGE FUNCTION
            $age = floor((strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926) . " Years";
            // if($age == 0){

            $date1 = new DateTime($Today);
            $date2 = new DateTime($row['Date_Of_Birth']);
            $diff = $date1->diff($date2);
            $age = $diff->y . " Years, ";
            $age .= $diff->m . " Months, ";
            $age .= $diff->d . " Days";

            $select_checkin = "SELECT Check_In_ID,Type_Of_Check_In FROM tbl_check_in WHERE Registration_ID = '" . $row['Registration_ID'] . "' ORDER BY Check_In_ID DESC LIMIT 1";
            //echo $select_checkin;exit;
            $select_checkin_qry = mysqli_query($conn,$select_checkin) or die(mysqli_error($conn));
            $checkin = mysqli_fetch_assoc($select_checkin_qry);
            $Check_In_ID = $checkin['Check_In_ID'];
            $Type_Of_Check_In = $checkin['Type_Of_Check_In'];
            $ToBe_Admitted_results = mysqli_query($conn,"SELECT ToBe_Admitted FROM tbl_check_in_details WHERE Registration_ID = '" . $row['Registration_ID'] . "' AND Check_In_ID = '$Check_In_ID'") or die(mysqli_error($conn));
            $ToBe_Admitted = mysqli_fetch_assoc($ToBe_Admitted_results)['ToBe_Admitted'];

            if ($ToBe_Admitted == 'no') {

                echo "<tr><td>" . $temp . "</td><td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . ucwords(strtolower($row['Patient_Name'])) . "</a></td>";
                echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Registration_ID'] . "</a></td>";
                echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Registration_ID'] . "</a></td>";
                echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Guarantor_Name'] . "</a></td>";
                echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $age . "</a></td>";
                echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Gender'] . "</a></td>";
                echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Phone_Number'] . "</a></td>";
                echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Member_Number'] . "</a></td>";
                echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $Clinic_Name . "</a></td>";
                echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['cons_hist_Date'] . "</a></td>";
                echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Transaction_Date_And_Time'] . "</a></td>";
                
                echo "</tr>";

                $temp++;
            }
        }
    }
}
//end


echo "</table></center>";
?>
