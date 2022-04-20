<?php
include './includes/connection.php';
@session_start();

$pre_paid = $_SESSION['hospitalConsultaioninfo']['set_pre_paid'];

//
//echo '<pre/>';
//print_r($_SESSION);
//exit;

$Sub_Department_ID= $_SESSION['Procedure_Sub_Department_ID'];

    $Date_From = '';
    $Date_To = '';
    $Sponsor = '';

$section = "section=Procedure&";
echo '<center>
            <table width =100% border=0 class="display" id="patientLabList">
            <thead>
                <tr>
                    <th><b>SN</b></th>
                    <th><b>STATUS</b></th>
                    <th style="width:12%;"><b>PATIENT</b></th>
                    <th><b>REG#</b></th>
                    <th style="width:2%;"><b>SPONSOR</b></th>
                    <th style="width:18%;"><b>AGE</b></th>
                    <th><b>GENDER</b></th>
                    <th style="width:14%;"><b>TRANS DATE</b></th>
                    <th><b>PHONE#</b></th>
                    <th style="width:10%;"><b>DOCTOR</b></th>
		    <th>ACTION</th>
                </tr>
            </thead>';
$Patient_Name = '';
$filter = "  AND DATE(Transaction_Date_And_Time) BETWEEN CURDATE()-INTERVAL 1 DAY AND DATE(NOW()) AND il.Sub_Department_ID='$Sub_Department_ID'";

if (isset($_GET['filterlabpatientdate']) && $_GET['filterlabpatientdate'] == true) {
    $Date_From = filter_input(INPUT_GET, 'Date_From');
    $Date_To = filter_input(INPUT_GET, 'Date_To');
    $Sponsor = filter_input(INPUT_GET, 'Sponsor');

    if (isset($Date_From) && !empty($Date_From)) {
        $filter = " AND Transaction_Date_And_Time >='" . $Date_From . "' AND il.Sub_Department_ID='$Sub_Department_ID'";
    }
    if (isset($Date_To) && !empty($Date_To)) {
        $filter = " AND Transaction_Date_And_Time <='" . $Date_To . "' AND il.Sub_Department_ID='$Sub_Department_ID'";
    }
    if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
        $filter = "  AND Transaction_Date_And_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "' AND il.Sub_Department_ID='$Sub_Department_ID'";
    }
    if (isset($Date_To) && empty($Date_To) && isset($Date_From) && empty($Date_From)) {
        $filter = "";
    }

    if ($Sponsor != 'All') {
        $filter .=" AND sp.Sponsor_ID='$Sponsor'";
    }

    //$filter=" AND Transaction_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'";
}

if (isset($_GET['Patient_Name'])) {
    $Date_From = filter_input(INPUT_GET, 'Date_From');
    $Date_To = filter_input(INPUT_GET, 'Date_To');
    $Sponsor = filter_input(INPUT_GET, 'Sponsor');

    // echo $Patient_Name;exit;

    if (isset($Date_From) && !empty($Date_From)) {
        $filter = " AND Transaction_Date_And_Time >='" . $Date_From . "'";
    }
    if (isset($Date_To) && !empty($Date_To)) {
        $filter = " AND Transaction_Date_And_Time <='" . $Date_To . "'";
    }
    if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
        $filter = "  AND Transaction_Date_And_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
    }
    if (isset($Date_To) && empty($Date_To) && isset($Date_From) && empty($Date_From)) {
        $filter = "";
    }

    if ($Sponsor != 'All') {
        $filter .=" AND sp.Sponsor_ID='$Sponsor'";
    }

    // $filter=" AND Transaction_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'";
    $Patient_Name = " AND pr.Patient_Name LIKE '%" . $_GET['Patient_Name'] . "%'";
}

$filter .=' ' . $Patient_Name;

$doctorproce = '';

if (isset($_GET['section']) && $_GET['section'] == 'doctor') {
        $doctorproce = " and il.Procedure_Location='Me' and
                                il.Consultant_ID='" . $_SESSION['userinfo']['Employee_ID'] . "'";
    }if (isset($_GET['sectionpatnt']) && $_GET['sectionpatnt'] == 'doctor_with_patnt') {
        $doctorproce = " and il.Procedure_Location='Me' and
                                il.Consultant_ID='" . $_SESSION['userinfo']['Employee_ID'] . "'";
    } elseif (isset($_GET['section']) && $_GET['section'] == 'Doctorlist') {
        $doctorproce = " and il.Procedure_Location='Me' and
                                il.Consultant_ID='" . $_SESSION['userinfo']['Employee_ID'] . "'";
    } else {
        $doctorproce = " and il.Procedure_Location <> 'Me'";
    }


$select_Filtered_Patients = "SELECT pr.Patient_Name,pc.Sponsor_Name,pr.Date_Of_Birth,il.payment_type,
                                           pr.Gender,pr.Phone_Number,pr.Registration_ID as registration_number,pc.Receipt_Date as Required_Date,
                                           pc.Payment_Cache_ID as payment_id,il.Status,il.Consultant as Doctors_Name,il.Transaction_Date_And_Time as Transaction_Date_And_Time,pc.Billing_Type,il.Transaction_Type
                                            FROM tbl_item_list_cache as il INNER JOIN tbl_payment_cache as pc ON pc.Payment_Cache_ID = il.Payment_Cache_ID
                                            JOIN tbl_items as i ON i.Item_ID = il.Item_ID 
                                            JOIN tbl_patient_registration AS pr ON pr.Registration_ID =pc.Registration_ID
                                            JOIN tbl_sponsor AS sp ON sp.Sponsor_ID =pr.Sponsor_ID
                                                WHERE Check_In_Type = 'Procedure' AND   il.status IN ('active','paid') AND  il.removing_status='yes'  $filter $doctorproce GROUP BY pr.Registration_ID ORDER BY Transaction_Date_And_Time ASC LIMIT 200";
//echo $select_Filtered_Patients;exit;
$result = mysqli_query($conn,$select_Filtered_Patients)or die(mysqli_error($conn));
//date manipulation to get the patient age
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}

$statusPay='';
$num_rows = mysqli_num_rows($result);
//if ($num_rows > 0) {
    $temp = 1;
    while ($row = mysqli_fetch_array($result)) {
        $Date_Of_Birth = $row['Date_Of_Birth'];
        $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
        
     $status = strtolower($row['Status']);
    $billing_Type = strtolower($row['Billing_Type']);
    $transaction_Type = strtolower($row['Transaction_Type']);
    $payment_type = strtolower($row['payment_type']);
    $displ = '';

     if (($billing_Type == 'outpatient cash' && $status == 'active') || ($billing_Type == 'outpatient credit' && $status == 'active' && $transaction_Type == "cash")) {
        $statusPay = 'Not paid';
    } elseif (($billing_Type == 'inpatient cash' && $status == 'active') || ($billing_Type == 'inpatient credit' && $status == 'active' && $transaction_Type == "cash")) {

        if ($pre_paid == '1') {
            if($payment_type=='post'){
                $statusPay = 'Bill';
            } else {
                $statusPay = 'Not paid';
            }
        } else {
            $statusPay = 'Bill';
        }
    } elseif (($billing_Type == 'outpatient cash' && $status == 'paid') || ($billing_Type == 'outpatient credit' && $status == 'paid' && $transaction_Type == "cash")) {
        $statusPay = 'Paid';
    } elseif (($billing_Type == 'inpatient cash' && $status == 'paid') || ($billing_Type == 'inpatient credit' && $status == 'paid' && $transaction_Type == "cash")) {
        $statusPay = 'Paid';
    } else {
        $statusPay = 'Bill';
    }

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);

        $diff = $date1->diff($date2);

        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";



        echo "<tr><td>" . $temp . "</td>
                                    
                                <td><a class='viewDetails' href='procedurepatientremovedinfo.php?" . $section . "Registration_id=" . $row['registration_number'] . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor."&Sub_Department_ID=" . $Sub_Department_ID . "' style='text-decoration: none;'>$statusPay</a></td>
                                <td><a class='viewDetails' href='procedurepatientremovedinfo.php?" . $section . "Registration_id=" . $row['registration_number'] . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor."&Sub_Department_ID=" . $Sub_Department_ID . "' style='text-decoration: none;'>" . $row['Patient_Name'] . "</a></td>";

        echo "<td><a class='viewDetails'  href='procedurepatientremovedinfo.php?" . $section . "Registration_id=" . $row['registration_number'] . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor."&Sub_Department_ID=" . $Sub_Department_ID . "' style='text-decoration: none;'>" . $row['registration_number'] . "</a></td>";
        echo "<td><a class='viewDetails' href='procedurepatientremovedinfo.php?" . $section . "Registration_id=" . $row['registration_number'] . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor."&Sub_Department_ID=" . $Sub_Department_ID . "' style='text-decoration: none;'>" . $row['Sponsor_Name'] . "</a></td>";
        echo "<td><a class='viewDetails' href='procedurepatientremovedinfo.php?" . $section . "Registration_id=" . $row['registration_number'] . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor."&Sub_Department_ID=" . $Sub_Department_ID . "' style='text-decoration: none;'><center>" . $age . "</center></a></td>";
        echo "<td><a class='viewDetails' href='procedurepatientremovedinfo.php?" . $section . "Registration_id=" . $row['registration_number'] . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor."&Sub_Department_ID=" . $Sub_Department_ID . "' style='text-decoration: none;'>" . $row['Gender'] . "</a></td>";
        echo "<td><a class='viewDetails' href='procedurepatientremovedinfo.php?" . $section . "Registration_id=" . $row['registration_number'] . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor."&Sub_Department_ID=" . $Sub_Department_ID . "' style='text-decoration: none;'>" . $row['Transaction_Date_And_Time'] . "</a></td>";
        echo "<td><a class='viewDetails' href='procedurepatientremovedinfo.php?" . $section . "Registration_id=" . $row['registration_number'] . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor."&Sub_Department_ID=" . $Sub_Department_ID . "' style='text-decoration: none;'>" . $row['Phone_Number'] . "</a></td>";
        echo "<td><a class='viewDetails' href='procedurepatientremovedinfo.php?" . $section . "Registration_id=" . $row['registration_number'] . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor."&Sub_Department_ID=" . $Sub_Department_ID . "' style='text-decoration: none;'>" . $row['Doctors_Name'] . "</a></td>";
        echo"<td><input type='button' class='removeptnt' id='" . $row['payment_id'] . "' value='Remove'></td>";
        $temp++;
    }
//}
    echo "</tr></table>";





    