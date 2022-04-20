<?php
@session_start();
include("./includes/connection.php");

$filter = '';//' AND DATE(pl.Transaction_Date_And_Time)=DATE(NOW())';

$Date_From = filter_input(INPUT_GET, 'Date_From');
$Date_To = filter_input(INPUT_GET, 'Date_To');
$Patient_Name = filter_input(INPUT_GET, 'Patient_Name');
$Sponsor = filter_input(INPUT_GET, 'Sponsor');
$Clinic = filter_input(INPUT_GET, 'Clinic');
$Patient_Number = filter_input(INPUT_GET, 'Patient_Number');
$Patient_Old_Number = filter_input(INPUT_GET, 'Patient_Old_Number');

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
  //  $filter = "  AND pl.Transaction_Date_And_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
    $filter2 = "  AND Payment_Date_And_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
}else{
   // $filter = "  AND DATE(pl.Transaction_Date_And_Time)=CURDATE()";
    $filter2 = "  AND DATE(Payment_Date_And_Time)=CURDATE()";
}

if (!empty($Sponsor) && $Sponsor != 'All') {
    $filter .="  AND pr.Sponsor_ID=$Sponsor";
   // $filter2 .="  AND Sponsor_ID=$Sponsor";
}

$clinic_filter = '';

if (!empty($Clinic) && $Clinic != 'All') {
    $clinic_filter = "  AND Clinic_ID  = $Clinic";
}

if (!empty($Patient_Name)) {
    $filter .="  AND pr.Patient_Name like '%$Patient_Name%'";
}

if (!empty($Patient_Number)) {
    $filter .="  AND pr.Registration_ID = '$Patient_Number'";
}

if (!empty($Patient_Old_Number)) {
    $filter .="  AND pr.Old_Registration_Number = '$Patient_Old_Number'";
}

//die($filter);
$temp = 1;
//GET BRANCH ID
$Folio_Branch_ID = $_SESSION['userinfo']['Branch_ID'];


///make update patient direction
//mysqli_query($conn,"UPDATE `tbl_patient_payment_item_list` SET `Patient_Direction`='Direct To Clinic' WHERE `Check_In_Type`='Doctor Room'  AND `Patient_Direction` NOT IN('Direct To Clinic','Direct To Doctor','Direct To Doctor Via Nurse Station','Direct To Clinic Via Nurse Station') AND `Transaction_Date_And_Time` BETWEEN '$Date_From' AND '$Date_To'") or die("Error!");
//Find the current date to filter check in list
?>
<script type='text/javascript'>
    function patientnoshow(Patient_Payment_Item_List_ID) {
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }
        mm.onreadystatechange = AJAXP; //specify name of function that will handle server response....
        mm.open('GET', 'patientnoshow.php?Patient_Payment_Item_List_ID=' + Patient_Payment_Item_List_ID, true);
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
//$clinic = $_SESSION['doctors_selected_clinic'];
//$clinic_filter = " AND Clinic_ID  = '$clinic'";
echo '<center><table width ="100%" id="clinicpatients">';
echo " <thead><tr ><th style='width:5%;'>SN</th><th><b>PATIENT NAME</b></th>
                <th><b>OLD PATIENT NUMBER</b></th>
                <th><b>PATIENT NUMBER</b></th>
                <th><b>SPONSOR</b></th>
                    <th><b>AGE</b></th>
                        <th><b>GENDER</b></th>
                            <th><b>PHONE NUMBER</b></th>
                                <th><b>MEMBER NUMBER</b></th>
                                <th><b>TRANS DATE</b></th>
				<th><b>ACTION</b></th>
				</tr>
                                </thead>";

$hospitalConsultType = $_SESSION['hospitalConsultaioninfo']['consultation_Type'];


//$get_clinics_query = "
//                SELECT * FROM tbl_clinic_employee ce WHERE ce.Employee_ID =" . $_SESSION['userinfo']['Employee_ID'] . " $clinic_filter
//            ";
//
////die($get_clinics_query);
//
//$get_clinics_result = mysqli_query($conn,$get_clinics_query) or die(mysqli_error($conn));
//
//$Clinic_IDs = array();
//
//while ($rowClinic = mysqli_fetch_array($get_clinics_result)) {
//    $Clinic_IDs[] = $rowClinic['Clinic_ID'];
//}
//
//if(count($Clinic_IDs)==0){
//	$Clinic_IDs = array(0);
//}
//$myqr = " SELECT pr.Registration_ID,pr.Old_Registration_Number,pr.Gender,pr.Patient_Name,pr.Phone_Number,pr.Member_Number,pl.Patient_Direction,pr.Date_Of_Birth,pl.Transaction_Date_And_Time,pl.Patient_Payment_Item_List_ID,pp.Patient_Payment_ID,sp.Guarantor_Name
//                FROM  tbl_patient_payment_item_list pl ,tbl_patient_payments pp,tbl_patient_registration pr,tbl_sponsor sp
//                
//                WHERE pl.Process_Status= 'not served' AND 
//                      pp.Patient_Payment_ID = pl.Patient_Payment_ID AND
//                      pp.Registration_ID = pr.Registration_ID AND
//                      pp.Transaction_status != 'cancelled' AND 
//                      sp.Sponsor_ID = pr.Sponsor_ID AND
//                      pp.Branch_ID = '$Folio_Branch_ID' and
//                     pl.Patient_Direction IN ('Direct To Clinic','Direct To Doctor','Direct To Doctor Via Nurse Station','Direct To Clinic Via Nurse Station')
//                      $filter
//                  GROUP BY pl.Patient_Payment_ID,pp.Registration_ID ORDER BY pl.Transaction_Date_And_Time  LIMIT 5
//            ";

//$sql_select_patient_info_result=mysqli_query($conn,"SELECT pr.Registration_ID,pr.Old_Registration_Number,pr.Gender,pr.Patient_Name,pr.Phone_Number,pr.Member_Number,pr.Date_Of_Birth,sp.Guarantor_Name,pl.Patient_Direction,pl.Transaction_Date_And_Time,pl.Patient_Payment_Item_List_ID,pp.Patient_Payment_ID 
//                            FROM  tbl_patient_registration pr,tbl_sponsor sp,tbl_patient_payments pp,tbl_patient_payment_item_list pl 
//                            WHERE
//                            pr.Sponsor_ID=sp.Sponsor_ID AND 
//                            sp.Sponsor_ID=PP.Sponsor_ID AND
//                            pr.Registration_ID=pp.Registration_ID AND 
//                            pp.Patient_Payment_ID=pl.Patient_Payment_ID AND
//                            pl.Patient_Direction IN ('Direct To Clinic','Direct To Doctor','Direct To Doctor Via Nurse Station','Direct To Clinic Via Nurse Station') AND
//                            pl.Process_Status= 'not served' AND 
//                            pp.Transaction_status != 'cancelled'
//                            $filter LIMIT 5") or die(mysqli_error($conn));

//$sql_get_paid_consultation_item_result=mysqli_query($conn,"SELECT Patient_Direction,Transaction_Date_And_Time,Patient_Payment_Item_List_ID FROM tbl_patient_payment_item_list WHERE Patient_Direction IN ('Direct To Clinic','Direct To Doctor','Direct To Doctor Via Nurse Station','Direct To Clinic Via Nurse Station') $filter2") or die(mysqli_error($conn));

//HEREEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE
//$sql_select_payment_id_n_reg_id_result=mysqli_query($conn,"SELECT pr.Old_Registration_Number,pr.Gender,pr.Patient_Name,pr.Phone_Number,pr.Member_Number,pr.Date_Of_Birth,pr.Sponsor_ID,pp.Patient_Payment_ID,pp.Registration_ID 
//FROM tbl_patient_payments pp,tbl_patient_registration pr 
//WHERE pp.Registration_ID=pr.Registration_ID 
//AND Transaction_status != 'cancelled' $filter2 $filter
//LIMIT 100") or die(mysqli_error($conn));

//NEW LINE FOR PATIENT TO APPEAR IN OPD CLINIC LIST IF FAILUNCOMMENT ABOVE LINE AND COMMENT THIS LINE ADDED DUE TO DUPLICATE SANGIRA NUMBER
$sql_select_payment_id_n_reg_id_result=mysqli_query($conn,"SELECT pr.Old_Registration_Number,pr.Gender,pr.Patient_Name,pr.Phone_Number,pr.Member_Number,pr.Date_Of_Birth,pr.Sponsor_ID,pp.Patient_Payment_ID,pp.Registration_ID 
FROM tbl_patient_payments pp,tbl_patient_registration pr 
WHERE pp.Registration_ID=pr.Registration_ID 
AND Transaction_status != 'cancelled' $filter2 $filter order by Patient_Payment_ID ASC
LIMIT 100") or die(mysqli_error($conn));




//$q = "SELECT * FROM `tbl_patient_payment_item_list` LEFT JOIN tbl_patient_payments pp ON  pp.Patient_Payment_ID = pl.Patient_Payment_ID
//            WHERE "
//        . "   Process_Status ='not served' ";

//die($myqr);
if(mysqli_num_rows($sql_select_payment_id_n_reg_id_result)>0){
   while($fetched_rows=mysqli_fetch_assoc($sql_select_payment_id_n_reg_id_result)){
       $Patient_Payment_ID=$fetched_rows['Patient_Payment_ID'];
       $Registration_ID=$fetched_rows['Registration_ID'];
//       echo "$Patient_Payment_ID ==>";
       $sql_get_paid_consultation_item_result=mysqli_query($conn,"SELECT Patient_Direction,Transaction_Date_And_Time,Patient_Payment_Item_List_ID 
       FROM tbl_patient_payment_item_list 
       WHERE  
       Check_In_Type='Doctor Room'
       AND Patient_Payment_ID='$Patient_Payment_ID' 
       AND Process_Status ='not served' $clinic_filter") or die(mysqli_error($conn));
//       $sql_get_paid_consultation_item_result=mysqli_query($conn,"SELECT Patient_Direction,Transaction_Date_And_Time,Patient_Payment_Item_List_ID 
//       FROM tbl_patient_payment_item_list 
//       WHERE Patient_Direction 
//       IN ('Direct To Clinic','Direct To Doctor','Direct To Doctor Via Nurse Station','Direct To Clinic Via Nurse Station') 
//       AND Patient_Payment_ID='$Patient_Payment_ID' 
//       AND Process_Status ='not served' $clinic_filter") or die(mysqli_error($conn));
       if(mysqli_num_rows($sql_get_paid_consultation_item_result)>0){
           while($item_data_rows=mysqli_fetch_assoc($sql_get_paid_consultation_item_result)){
             $Patient_Payment_Item_List_ID=$item_data_rows['Patient_Payment_Item_List_ID'];
             $Transaction_Date_And_Time=$item_data_rows['Transaction_Date_And_Time'];  
           }
//           $sql_select_patient_info_result=mysqli_query($conn,"SELECT pr.Registration_ID,pr.Old_Registration_Number,pr.Gender,pr.Patient_Name,pr.Phone_Number,pr.Member_Number,pr.Date_Of_Birth,sp.Guarantor_Name
//                                                        FROM  tbl_patient_registration pr,tbl_sponsor sp WHERE pr.Sponsor_ID=sp.Sponsor_ID AND Registration_ID='$Registration_ID' $filter LIMIT 1") or die(mysqli_error($conn));
//           if(mysqli_num_rows($sql_select_patient_info_result)>0){
//               while($user_info_rows=mysqli_fetch_assoc($sql_select_patient_info_result)){
                   $Old_Registration_Number=$fetched_rows['Old_Registration_Number'];
                   $Gender=$fetched_rows['Gender'];
                   $Patient_Name=$fetched_rows['Patient_Name'];
                   $Phone_Number=$fetched_rows['Phone_Number'];
                   $Member_Number=$fetched_rows['Member_Number'];
                   $Date_Of_Birth=$fetched_rows['Date_Of_Birth'];
                   $Sponsor_ID=$fetched_rows['Sponsor_ID'];
                   
                $Guarantor_Name=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'"))['Guarantor_Name'];
//        if ($emergency == 'yes') {
//           $style="style='background-color:#ddfdd'";
//            ="<span style='color:red'>";
//	    ="</span>";
//        
//        
                
                 //today function
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$age ='';
    }	
		$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
		

        echo "<tr><td>" . $temp . "</td><td ><a onclick='check_for_afyacard_configuration($Registration_ID,$Patient_Payment_ID,$Patient_Payment_Item_List_ID,\"Patient_Name\")' href='#' target='_parent' style='text-decoration: none;'>" . ucwords(strtolower($Patient_Name)) . "</a></td>";
        echo "<td><a onclick='check_for_afyacard_configuration($Registration_ID,$Patient_Payment_ID,$Patient_Payment_Item_List_ID,\"Patient_Name\")' href='#' target='_parent' style='text-decoration: none;'>" . $Old_Registration_Number . "</a></td>";
        echo "<td><a onclick='check_for_afyacard_configuration($Registration_ID,$Patient_Payment_ID,$Patient_Payment_Item_List_ID,\"Patient_Name\")' href='#' target='_parent' style='text-decoration: none;'>" . $Registration_ID . "</a></td>";
        echo "<td><a onclick='check_for_afyacard_configuration($Registration_ID,$Patient_Payment_ID,$Patient_Payment_Item_List_ID,\"Patient_Name\")' href='#' target='_parent' style='text-decoration: none;'>" . $Guarantor_Name . "</a></td>";
        echo "<td><a onclick='check_for_afyacard_configuration($Registration_ID,$Patient_Payment_ID,$Patient_Payment_Item_List_ID,\"Patient_Name\")' href='#' target='_parent' style='text-decoration: none;'>" . $age . "</a></td>";
        echo "<td><a onclick='check_for_afyacard_configuration($Registration_ID,$Patient_Payment_ID,$Patient_Payment_Item_List_ID,\"Patient_Name\")' href='#' target='_parent' style='text-decoration: none;'>" . $Gender . "</a></td>";
        echo "<td><a onclick='check_for_afyacard_configuration($Registration_ID,$Patient_Payment_ID,$Patient_Payment_Item_List_ID,\"Patient_Name\")' href='#' target='_parent' style='text-decoration: none;'>" . $Phone_Number . "</a></td>";
        echo "<td><a onclick='check_for_afyacard_configuration($Registration_ID,$Patient_Payment_ID,$Patient_Payment_Item_List_ID,\"Patient_Name\")' href='#' target='_parent' style='text-decoration: none;'>" . $Member_Number . "</a></td>";
        echo "<td><a onclick='check_for_afyacard_configuration($Registration_ID,$Patient_Payment_ID,$Patient_Payment_Item_List_ID,\"Patient_Name\")' href='#' target='_parent' style='text-decoration: none;'>" . $Transaction_Date_And_Time. "</a></td>";
               ?>
        <td>
            <?php
           if ($hospitalConsultType == 'One patient to one doctor') {
               ?>
                <input type='button' value='NO SHOW' class='art-button-green'
                       onclick='patientnoshow("<?php echo $Patient_Payment_Item_List_ID; ?>")'>

                <?php
           }
           ?>
        </td>
        </tr>
        <?php      
        $temp++;
//                }
//           }
       } 
       
   } 
}


////$select_Filtered_Patients = mysqli_query($conn,$myqr) or die(mysqli_error($conn));
//if(mysqli_num_rows($sql_select_patient_info_result)>0){
//while ($row = mysqli_fetch_array($sql_select_patient_info_result)) {
//  //  if ($row['Patient_Direction'] == 'Direct To Clinic') {
//   // if($row['Patient_Direction']=='Direct To Clinic'||$row['Patient_Direction']=='Direct To Doctor'||$row['Patient_Direction']=='Direct To Doctor Via Nurse Station'||$row['Patient_Direction']=='Direct To Clinic Via Nurse Station'){
////        $emeg = mysqli_query($conn,"SELECT emergency FROM tbl_nurse n WHERE  n.Patient_Payment_Item_List_ID='" . $row['Patient_Payment_Item_List_ID'] . "'") or die(mysqli_error($conn));
////        $emergency = mysqli_fetch_assoc($emeg)['emergency'];
//// 
////        $emergency='';
////         $style = "";
////             = "";
////             = "";
////        if ($emergency == 'yes') {
////           $style="style='background-color:#ddfdd'";
////            ="<span style='color:red'>";
////	    ="</span>";
////        }
//
//        echo "<tr><td>" . $temp . "</td><td ><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . ucwords(strtolower($row['Patient_Name'])) . "</a></td>";
//        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Old_Registration_Number'] . "</a></td>";
//        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Registration_ID'] . "</a></td>";
//        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Guarantor_Name'] . "</a></td>";
//        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Date_Of_Birth'] . "</a></td>";
//        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Gender'] . "</a></td>";
//        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Phone_Number'] . "</a></td>";
//        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Member_Number'] . "</a></td>";
//        echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>" . $row['Transaction_Date_And_Time'] . "</a></td>";
//        ?>
<!--        <td>-->
            <?php
//            if ($hospitalConsultType == 'One patient to one doctor') {
//                ?>
<!--                <input type='button' value='NO SHOW' class='art-button-green'
                       onclick='patientnoshow("//<?php echo $row['Patient_Payment_Item_List_ID']; ?>")'>-->

                <?php
//            }
//            ?>
        <!--</td>-->
        </tr>
        <?php
//        $temp++;
//   // }
//}
//}
//}
?></table></center>
