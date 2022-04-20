<?php
@session_start();
include("./includes/connection.php");
$filter = " pl.Process_Status IN ('no show','signedoff')  ";
// $filter = ' AND DATE(pl.Transaction_Date_And_Time)=DATE(NOW())';

$Date_From = filter_input(INPUT_GET, 'Date_From');
$Date_To = filter_input(INPUT_GET, 'Date_To');
$Patient_Name = filter_input(INPUT_GET, 'Patient_Name');

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = "  AND pl.Transaction_Date_And_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
}

if (!empty($Patient_Name)) {
    $filter .="  AND pr.Patient_Name like '%$Patient_Name%'";
}

//echo $filter;exit;

$n = 1;

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
//Find the current date to filter check in list
?>
<script type='text/javascript'>
    function patientnoshow(Patient_Payment_Item_List_ID, Patient_Name) {
        var Confirm_SignOff = confirm("Are You Sure You Want To No Show " + Patient_Name + "?");
        if (Confirm_SignOff) {
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
            return true;
        } else {
            return false;
        }
    }
    function AJAXP() {
        var data = mm.responseText;
        if (mm.readyState == 4) {
            document.location.reload();
        }
    }
</script>
<?php
echo '<center><table width ="100%" id="myPatients">';
echo " <thead><tr ><th style='width:5%;'>SN</th><th><b>PATIENT NAME</b></th>
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

$select_Filtered_Patients = mysqli_query($conn,"
                SELECT pr.Registration_ID,pr.Gender,pr.Patient_Name,pr.Phone_Number,pr.Member_Number,pr.Date_Of_Birth,pl.Transaction_Date_And_Time,pl.Patient_Payment_Item_List_ID,pp.Patient_Payment_ID,sp.Guarantor_Name
                FROM  tbl_patient_payment_item_list pl INNER JOIN tbl_patient_payments pp ON  pp.Patient_Payment_ID = pl.Patient_Payment_ID
                JOIN tbl_patient_registration pr ON pp.Registration_ID = pr.Registration_ID
                JOIN tbl_sponsor sp ON sp.Sponsor_ID = pr.Sponsor_ID
                WHERE pl.Patient_Direction = 'Direct To Doctor' AND 
                      pl.Consultant_ID = " . $_SESSION['userinfo']['Employee_ID'] . " AND
                      pp.Branch_ID = '$Folio_Branch_ID' AND 
                      pl.Patient_Direction IN ('Direct To Doctor','Direct To Doctor Via Nurse Station','Direct To Clinic','Direct To Clinic Via Nurse Station')
		      
                      $filter
                GROUP BY pl.Patient_Payment_ID ORDER BY pl.Transaction_Date_And_Time LIMIT 100
            ") or die(mysqli_error($conn));


while ($row = mysqli_fetch_array($select_Filtered_Patients)) {
    $style = "";
    $startspan = "";
    $endspan = "";

    $age = floor((strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926) . " Years";
    // if($age == 0){

    $date1 = new DateTime($Today);
    $date2 = new DateTime($row['Date_Of_Birth']);
    $diff = $date1->diff($date2);
    $age = $diff->y . " Years, ";
    $age .= $diff->m . " Months, ";
    $age .= $diff->d . " Days";

    echo "<tr ><td >$startspan" . $n . "$endspan</td><td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>$startspan" . ucwords(strtolower($row['Patient_Name'])) . "$endspan</a></td>";
    echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>$startspan" . $row['Guarantor_Name'] . "$endspan</a></td>";
    echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>$startspan" . $age . "$endspan</a></td>";
    echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>$startspan" . $row['Gender'] . "$endspan</a></td>";
    echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>$startspan" . $row['Phone_Number'] . "$endspan</a></td>";
    echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>$startspan" . $row['Member_Number'] . "$endspan</a></td>";
    echo "<td><a href='doctorspageoutpatientwork.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $row['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $row['Patient_Payment_Item_List_ID'] . "&NR=true&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'>$startspan" . $row['Transaction_Date_And_Time'] . "$endspan</a></td>";
    ?>

    <td>
    <?php
    if ($hospitalConsultType == 'One patient to one doctor') {
        ?>
            <input type='button' value='SHOW' class='art-button-green'
                   onclick='patientnoshow("<?php echo $row['Patient_Payment_ID']; ?>", "<?php echo $row['Patient_Name'] ?>")'>
        <?php
    }
    ?>
    </td>
               <?php
               $n++;
           } echo "</tr>";
           ?></table></center>