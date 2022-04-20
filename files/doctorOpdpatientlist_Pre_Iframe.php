<!--<link rel="stylesheet" href="table34.css" media="screen">-->
<?php
@session_start();
include("./includes/connection.php");
if (isset($_GET['Patient_Name'])) {
    $Patient_Name = $_GET['Patient_Name'];
} else {
    $Patient_Name = '';
}
$temp = 1;

//GET BRANCH ID
$Folio_Branch_ID = $_SESSION['userinfo']['Branch_ID'];

//get number of opd display days
$days_number = $_SESSION['systeminfo']['opd_patients_days'];



if($days_number>0){
  $opd_query="AND TIMESTAMPDIFF(DAY,ppl.Transaction_Date_And_Time,NOW())<=$days_number";  
}else{
   $opd_query=""; 
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

$hospitalConsultType = $_SESSION['hospitalConsultaioninfo']['consultation_Type'];
$emp = '';
$isClinicOneToOne = false;
if ($hospitalConsultType == 'One patient to one doctor') {
    $emp = "AND ppl.Consultant_ID =" . $_SESSION['userinfo']['Employee_ID'] . " ";
    $isClinicOneToOne = true;
}

//die($emp);
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
//Get check-in ID



echo '<center><table width ="100%" id="consultpatients">';
echo " <thead><tr ><th style='width:5%;'>SN</th><th><b>PATIENT NAME</b></th>
                <th><b>SPONSOR</b></th>
                    <th><b>AGE</b></th>
                        <th><b>GENDER</b></th>
                            <th><b>PHONE NUMBER</b></th>
                                <th><b>MEMBER NUMBER</b></th>
                                <th><b>CONSULTED DATE</b></th>
				<th><b>ACTION</b></th>
				</tr>
                                </thead>";

$qr = "SELECT pr.Registration_ID,MAX(c.Consultation_ID),ch.cons_hist_Date,pr.Gender,pr.Patient_Name,pr.Phone_Number,pr.Member_Number,pr.Date_Of_Birth,ppl.Transaction_Date_And_Time,ppl.Patient_Payment_Item_List_ID,pp.Patient_Payment_ID,s.Guarantor_Name
 FROM tbl_patient_registration pr,tbl_sponsor s,
		  tbl_patient_payment_item_list ppl,tbl_consultation c,tbl_consultation_history ch,
		  tbl_patient_payments pp
		  WHERE
		  ch.Consultation_ID=c.Consultation_ID AND
		  pr.Registration_ID = c.Registration_ID AND
		  pr.Sponsor_ID = s.Sponsor_ID $emp AND
                  c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID AND
		  c.Process_Status = 'served' AND
		  pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND
                  (pp.Billing_Type='Outpatient Cash' || pp.Billing_Type='Inpatient Cash') AND 
		  pp.Registration_ID = pr.Registration_ID AND
		  pp.Branch_ID = " . $_SESSION['userinfo']['Branch_ID'] . " AND 
		  ppl.Process_Status = 'signedoff' AND pp.Check_In_ID IS NOT NULL AND
                  DATE(ppl.Transaction_Date_And_Time) = DATE(NOW())
		  GROUP BY pp.Registration_ID 
		  ORDER BY c.Consultation_ID DESC
                   
LIMIT 70
		  ";

 //die($qr);


$select_Filtered_Patients = mysqli_query($conn,$qr) or die( mysqli_error($conn));
//echo (mysqli_num_rows($select_Filtered_Patients));exit;
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
        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Guarantor_Name'] . "</a></td>";
        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $age . "</a></td>";
        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Gender'] . "</a></td>";
        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Phone_Number'] . "</a></td>";
        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Member_Number'] . "</a></td>";
        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['cons_hist_Date'] . "</a></td>";
        echo "<td>&nbsp;</td>";
        echo "</tr>";

        $temp++;
    }
}

if ($isClinicOneToOne) {
   // echo 'tru';
    //select list from clinic
    $get_clinics_query = "
                SELECT Clinic_ID FROM tbl_clinic_employee ce WHERE ce.Employee_ID =" . $_SESSION['userinfo']['Employee_ID'] . "
            ";

//die($get_clinics_query);

    $get_clinics_result = mysqli_query($conn,$get_clinics_query) or die(mysqli_error($conn));



    while ($rowClinic = mysqli_fetch_array($get_clinics_result)) {
        $Clinic_ID = $rowClinic['Clinic_ID'];
        $qr = "SELECT pr.Registration_ID,MAX(c.Consultation_ID),ch.cons_hist_Date,pr.Gender,pr.Patient_Name,pr.Phone_Number,pr.Member_Number,pr.Date_Of_Birth,ppl.Transaction_Date_And_Time,ppl.Patient_Payment_Item_List_ID,pp.Patient_Payment_ID,s.Guarantor_Name
 FROM tbl_patient_registration pr,tbl_sponsor s,
		  tbl_patient_payment_item_list ppl,tbl_consultation c,tbl_consultation_history ch,
		  tbl_patient_payments pp
		  WHERE
		  ch.Consultation_ID=c.Consultation_ID AND
		  pr.Registration_ID = c.Registration_ID AND
		  pr.Sponsor_ID = s.Sponsor_ID AND 
                  ppl.Clinic_ID = " . $Clinic_ID . " AND
                  c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID AND
		  c.Process_Status = 'served' AND
		  pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND
		  pp.Registration_ID = pr.Registration_ID AND
		  pp.Branch_ID = " . $_SESSION['userinfo']['Branch_ID'] . " AND 
		  ppl.Process_Status = 'signedoff' AND (pp.Billing_Type='Outpatient Cash' || pp.Billing_Type='Inpatient Cash') $opd_query AND
                  pp.Check_In_ID IS NOT NULL
		  GROUP BY pp.Registration_ID 
		  ORDER BY c.Consultation_ID DESC
                  LIMIT 70
		  ";

        // die($qr);


        $select_Filtered_Patients = mysqli_query($conn,$qr) or die( mysqli_error($conn));
        //echo (mysqli_num_rows($select_Filtered_Patients));exit;
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
                echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Guarantor_Name'] . "</a></td>";
                echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $age . "</a></td>";
                echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Gender'] . "</a></td>";
                echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Phone_Number'] . "</a></td>";
                echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Member_Number'] . "</a></td>";
                echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['cons_hist_Date'] . "</a></td>";
                echo "<td>&nbsp;</td>";
                echo "</tr>";

                $temp++;
            }
        }
    }
}
//end
?></table></center>