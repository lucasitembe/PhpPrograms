<?php
include './includes/connection.php';
@session_start();

$pre_paid = $_SESSION['hospitalConsultaioninfo']['set_pre_paid'];

$Sub_Department_ID= $_SESSION['Radiology_Sub_Department_ID'];

    $Date_From = '';
    $Date_To = '';
    $Sponsor = '';

$section = "section=Procedure&";
echo '<center>
            <table width =100% border=0 class="display" id="patientLabList">
            <thead>
                <tr>
                    <th style="width:2%;"><b>SN</b></th>
                    <th style="width:3%"><b>STATUS</b></th>
                    <th style="width:14%;"><b>PATIENT</b></th>
                    <th style="width:4%"><b>REG#</b></th>
                    <th><b>SPONSOR</b></th>
                    <th style="width:18%;"><b>AGE</b></th>
                    <th style="width:3%"><b>GENDER</b></th>
                    <th style="width:12%;"><b>TRANS DATE</b></th>
                    <th style="width:6%;"><b>PHONE#</b></th>
                    <th style="width:10%;"><b>DOCTOR</b></th>
		    <!--<th>ACTION</th>-->
                </tr>
            </thead>';
$Patient_Name = '';

$filter = "  AND DATE(Transaction_Date_And_Time)= DATE(NOW()) AND il.Sub_Department_ID='$Sub_Department_ID'";

$SubCategory='All';

if (isset($_GET['filterlabpatientdate']) && $_GET['filterlabpatientdate'] == true) {
    $Date_From = filter_input(INPUT_GET, 'Date_From');
    $Date_To = filter_input(INPUT_GET, 'Date_To');
    $Sponsor = filter_input(INPUT_GET, 'Sponsor');
    $Patient_Name = filter_input(INPUT_GET, 'Patient_Name');
    $SubCategory = filter_input(INPUT_GET, 'SubCategory');

  
    if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
        $filter = "  AND Transaction_Date_And_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'  AND il.Sub_Department_ID='$Sub_Department_ID'";
    }
    

    if ($Sponsor != 'All') {
        $filter .=" AND sp.Sponsor_ID='$Sponsor'";
    }
    
     if (!empty($Patient_Name)) {
       $filter .= " AND pr.Patient_Name LIKE '%" . $_GET['Patient_Name'] . "%'";
    }
    
    if ($SubCategory != 'All') {
        $filter .=" AND i.Item_Subcategory_ID='$SubCategory'";
    }

}

$select_Filtered_Patients = "SELECT pr.Patient_Name,pc.Sponsor_Name,pr.Date_Of_Birth,
                                           pr.Gender,pr.Phone_Number,pr.Registration_ID as registration_number,pc.Receipt_Date as Required_Date,
                                           pc.Payment_Cache_ID as payment_id,il.Status,il.Consultant as Doctors_Name,il.Transaction_Date_And_Time as Transaction_Date_And_Time,pc.Billing_Type,il.Transaction_Type
                                            FROM tbl_item_list_cache as il INNER JOIN tbl_payment_cache as pc ON pc.Payment_Cache_ID = il.Payment_Cache_ID
                                            JOIN tbl_items as i ON i.Item_ID = il.Item_ID 
                                            JOIN tbl_patient_registration AS pr ON pr.Registration_ID =pc.Registration_ID
                                            JOIN tbl_sponsor AS sp ON sp.Sponsor_ID =pr.Sponsor_ID
                                            WHERE Check_In_Type = 'Radiology' AND il.Status IN ('pending','not done') $filter GROUP BY pr.Registration_ID ORDER BY Transaction_Date_And_Time LIMIT 10";
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
    $displ = '';

    if (($billing_Type == 'outpatient cash' && $status == 'active') || ($billing_Type == 'outpatient credit' && $status == 'active' && $transaction_Type == "cash")) {
        $statusPay = 'Not paid';
    } elseif (($billing_Type == 'inpatient cash' && $status == 'active') || ($billing_Type == 'inpatient credit' && $status == 'active' && $transaction_Type == "cash")) {

        if ($pre_paid == '1') {
            $statusPay = 'Not paid';
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
                                    
            <td><a class='viewDetails' href='radiologypendingpatientinfo.php?Registration_id=" . $row['registration_number'] . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor."&Sub_Department_ID=" . $Sub_Department_ID. "&SubCategory=" . $SubCategory . " ' style='text-decoration: none;'>$statusPay</a></td>
            <td><a class='viewDetails' href='radiologypendingpatientinfo.php?Registration_id=" . $row['registration_number'] . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor."&Sub_Department_ID=" . $Sub_Department_ID . "&SubCategory=" . $SubCategory . "' style='text-decoration: none;'>" . $row['Patient_Name'] . "</a></td>";

        echo "<td><a class='viewDetails'  href='radiologypendingpatientinfo.php?Registration_id=" . $row['registration_number'] . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor."&Sub_Department_ID=" . $Sub_Department_ID. "&SubCategory=" . $SubCategory  . "' style='text-decoration: none;'>" . $row['registration_number'] . "</a></td>";
        echo "<td><a class='viewDetails' href='radiologypendingpatientinfo.php?Registration_id=" . $row['registration_number'] . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor."&Sub_Department_ID=" . $Sub_Department_ID. "&SubCategory=" . $SubCategory  . "' style='text-decoration: none;'>" . $row['Sponsor_Name'] . "</a></td>";
        echo "<td><a class='viewDetails' href='radiologypendingpatientinfo.php?Registration_id=" . $row['registration_number'] . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor."&Sub_Department_ID=" . $Sub_Department_ID. "&SubCategory=" . $SubCategory  . "' style='text-decoration: none;'><center>" . $age . "</center></a></td>";
        echo "<td><a class='viewDetails' href='radiologypendingpatientinfo.php?Registration_id=" . $row['registration_number'] . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor."&Sub_Department_ID=" . $Sub_Department_ID. "&SubCategory=" . $SubCategory  . "' style='text-decoration: none;'>" . $row['Gender'] . "</a></td>";
        echo "<td><a class='viewDetails' href='radiologypendingpatientinfo.php?Registration_id=" . $row['registration_number'] . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor."&Sub_Department_ID=" . $Sub_Department_ID. "&SubCategory=" . $SubCategory  . "' style='text-decoration: none;'>" . $row['Transaction_Date_And_Time'] . "</a></td>";
        echo "<td><a class='viewDetails' href='radiologypendingpatientinfo.php?Registration_id=" . $row['registration_number'] . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor."&Sub_Department_ID=" . $Sub_Department_ID. "&SubCategory=" . $SubCategory  . "' style='text-decoration: none;'>" . $row['Phone_Number'] . "</a></td>";
        echo "<td><a class='viewDetails' href='radiologypendingpatientinfo.php?Registration_id=" . $row['registration_number'] . "&Date_From=" . $Date_From . "&Date_To=" . $Date_To . "&Sponsor=" . $Sponsor."&Sub_Department_ID=" . $Sub_Department_ID. "&SubCategory=" . $SubCategory  . "' style='text-decoration: none;'>" . $row['Doctors_Name'] . "</a></td>";
       // echo"<td><input type='button' class='removeptnt' id='" . $row['payment_id'] . "' value='Remove'></td>";
        $temp++;
    }
//}
    echo "</tr></table>";





    
