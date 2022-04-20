<?php
include './includes/connection.php';
@session_start();
$pre_paid = $_SESSION['hospitalConsultaioninfo']['set_pre_paid'];
$Sub_Department_ID = $_SESSION['Radiology_Sub_Department_ID'];


$Date_From = '';
$Date_To = '';
$Sponsor = '';

$section = "section=Procedure&";
echo '<table width =100% border=0 class="display" id="patientLabList">
            <thead>
                <tr>
                    <th style="width:1%;"><b>SN</b></th>
                    <th style="width:20%;"><b>PATIENT</b></th>
                    <th style="width:5%;"><b>REG#</b></th>
                    <th style="width:10%;"><b>SPONSOR</b></th>
                    <th style="width:10%;"><b>AGE</b></th>
                    <th style="width:5%;"><b>GENDER</b></th>
                    <th style="width:14%;"><b>CONSULTED DATE</b></th>
                    <th style="width:8%;"><b>PHONE#</b></th>
                </tr>
            </thead>';
$Patient_Name = '';
$filter = "  AND DATE(Date_Time) = DATE(NOW()) AND il.Sub_Department_ID='$Sub_Department_ID'";

$SubCategory = 'All';

//if (isset($_GET['filterlabpatientdate']) && $_GET['filterlabpatientdate'] == true) {
    $Date_From = filter_input(INPUT_GET, 'Date_From');
    $Date_To = filter_input(INPUT_GET, 'Date_To');
    $Sponsor = filter_input(INPUT_GET, 'Sponsor');
    $Patient_Name = filter_input(INPUT_GET, 'Patient_Name');
    $Patient_Number = filter_input(INPUT_GET, 'Patient_Number');
    $SubCategory = filter_input(INPUT_GET, 'SubCategory');

    
    if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
        $filter = "  AND Date_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'  AND il.Sub_Department_ID='$Sub_Department_ID'";
    }
    
//die($filter);
    if (!empty($Sponsor) && strtolower($Sponsor) != 'all') {
        $filter .=" AND sp.Sponsor_ID='$Sponsor'";
    }

    if (!empty($Patient_Name)) {
        $filter .= " AND pr.Patient_Name LIKE '%" . $_GET['Patient_Name'] . "%'";
    }

    if (!empty($Patient_Number)) {
        $filter .= " AND pc.Registration_ID = '$Patient_Number'";
    }

    if (!empty($SubCategory) && $SubCategory != 'All') {
        $filter .=" AND i.Item_Subcategory_ID='$SubCategory'";
    }
//}

$select_Filtered_Patients = "SELECT pr.Patient_Name,pc.Sponsor_Name,pr.Date_Of_Birth,
                                           pr.Gender,pr.Phone_Number,pr.Registration_ID as registration_number,pc.Receipt_Date as Required_Date,
                                           pc.Payment_Cache_ID as payment_id,il.Status,il.Consultant as Doctors_Name,rad.Date_Time ,pc.Billing_Type,il.Transaction_Type
                                            FROM tbl_item_list_cache as il INNER JOIN tbl_payment_cache as pc ON pc.Payment_Cache_ID = il.Payment_Cache_ID
                                            JOIN tbl_items as i ON i.Item_ID = il.Item_ID 
                                            JOIN tbl_radiology_patient_tests as rad ON rad.Patient_Payment_Item_List_ID = il.Payment_Item_Cache_List_ID 
                                            JOIN tbl_patient_registration AS pr ON pr.Registration_ID =pc.Registration_ID
                                            JOIN tbl_sponsor AS sp ON sp.Sponsor_ID =pr.Sponsor_ID
                                            WHERE Check_In_Type = 'Radiology' AND il.Status='served' $filter GROUP BY pr.Registration_ID ORDER BY Date_Time ASC LIMIT 20";
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

$statusPay = '';
$num_rows = mysqli_num_rows($result);
//if ($num_rows > 0) {
$temp = 1;
while ($row = mysqli_fetch_array($result)) {
    $Date_Of_Birth = $row['Date_Of_Birth'];
    $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";

    $status = strtolower($row['Status']);
    $billing_Type = strtolower($row['Billing_Type']);
    $transaction_Type = strtolower($row['Transaction_Type']);
    $displ = '';
   
    
    $date1 = new DateTime($Today);
    $date2 = new DateTime($Date_Of_Birth);

    $diff = $date1->diff($date2);

    $age = $diff->y . " Years, ";
    $age .= $diff->m . " Months, ";
    $age .= $diff->d . " Days";



    echo "<tr><td>" . $temp . "</td>
                                    
            <td><a class='viewDetails' href='radiologyconsultedpatientinfo.php?Registration_id=" . $row['registration_number'] . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor . "&Sub_Department_ID=" . $Sub_Department_ID . "&SubCategory=" . $SubCategory . "' style='text-decoration: none;'>" . $row['Patient_Name'] . "</a></td>";

    echo "<td><a class='viewDetails'  href='radiologyconsultedpatientinfo.php?Registration_id=" . $row['registration_number'] . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor . "&Sub_Department_ID=" . $Sub_Department_ID . "&SubCategory=" . $SubCategory . "' style='text-decoration: none;'>" . $row['registration_number'] . "</a></td>";
    echo "<td><a class='viewDetails' href='radiologyconsultedpatientinfo.php?Registration_id=" . $row['registration_number'] . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor . "&Sub_Department_ID=" . $Sub_Department_ID . "&SubCategory=" . $SubCategory . "' style='text-decoration: none;'>" . $row['Sponsor_Name'] . "</a></td>";
    echo "<td><a class='viewDetails' href='radiologyconsultedpatientinfo.php?Registration_id=" . $row['registration_number'] . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor . "&Sub_Department_ID=" . $Sub_Department_ID . "&SubCategory=" . $SubCategory . "' style='text-decoration: none;'><center>" . $age . "</center></a></td>";
    echo "<td><a class='viewDetails' href='radiologyconsultedpatientinfo.php?Registration_id=" . $row['registration_number'] . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor . "&Sub_Department_ID=" . $Sub_Department_ID . "&SubCategory=" . $SubCategory . "' style='text-decoration: none;'>" . $row['Gender'] . "</a></td>";
    echo "<td><a class='viewDetails' href='radiologyconsultedpatientinfo.php?Registration_id=" . $row['registration_number'] . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor . "&Sub_Department_ID=" . $Sub_Department_ID . "&SubCategory=" . $SubCategory . "' style='text-decoration: none;'>" . $row['Date_Time'] . "</a></td>";
    echo "<td><a class='viewDetails' href='radiologyconsultedpatientinfo.php?Registration_id=" . $row['registration_number'] . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor . "&Sub_Department_ID=" . $Sub_Department_ID . "&SubCategory=" . $SubCategory . "' style='text-decoration: none;'>" . $row['Phone_Number'] . "</a></td>";
    //echo "<td><a class='viewDetails' href='radiologyconsultedpatientinfo.php?Registration_id=" . $row['registration_number'] . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor . "&Sub_Department_ID=" . $Sub_Department_ID . "&SubCategory=" . $SubCategory . "' style='text-decoration: none;'>" . $row['Doctors_Name'] . "</a></td></tr>";
    // echo"<td><input type='button' class='removeptnt' id='" . $row['payment_id'] . "' value='Remove'></td>";
    $temp++;
}
//}
echo "</table>";





