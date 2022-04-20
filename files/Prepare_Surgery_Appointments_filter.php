<link rel="stylesheet" href="table.css" media="screen">

<?php
include("./includes/connection.php");
$temp = 1;

$Patient_Name = $_GET['Patient_Name'];
$Patient_Number = $_GET['Patient_Number'];
$Sponsor_ID = $_GET['Sponsor_ID'];
$Employee_ID = $_GET['Employee_ID'];
$Current_Employee_ID = $_GET['Current_Employee_ID'];
$date_From = $_GET['date_From'];
$date_To = $_GET['date_To'];
$Inp_Sub_Department_ID = $_GET['Sub_Department_ID'];
$Surgical_Type = $_GET['Surgical_Type'];

$filter = '';



//today function
$Today_Date = mysqli_query($conn, "SELECT now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    // $age ='';
    $This_date_today = $Today. " 00:00";
}
//end

if (!empty($Patient_Number)) {
    $filter .= " AND pc.Registration_ID = '$Patient_Number'";
}

if (!empty($Patient_Name)) {
    $filter .= " AND pr.Patient_Name LIKE '%$Patient_Name%'";
}

if (($Sponsor_ID) != 'All' && !empty($Sponsor_ID)) {
    $filter .= " AND pc.Sponsor_ID =  '$Sponsor_ID'";
}

if (($Employee_ID) != 'All' && !empty($Employee_ID)) {
    $filter .= " AND ilc.Consultant_ID =  '$Employee_ID'";
}


if (!empty($date_From) && !empty($date_To)) {
    $filter .= " AND ilc.Service_Date_And_Time BETWEEN '$date_From' AND '$date_To'";
}else{
    $filter .= " AND ilc.Service_Date_And_Time  >= '$This_date_today'";

}

if($Inp_Sub_Department_ID != 'All'){
    $filter .= " AND ilc.finance_department_id = '$Inp_Sub_Department_ID'";
}

if($Surgical_Type != "All"){
    $check_status = "AND ilc.Payment_Item_Cache_List_ID IN(SELECT Payment_Item_Cache_List_ID FROM tbl_surgery_appointment WHERE Surgery_Status = 'removed' AND Final_Decision = 'Rejected')";
}else{
    $check_status = "AND ilc.Payment_Item_Cache_List_ID  NOT IN(SELECT Payment_Item_Cache_List_ID FROM tbl_surgery_appointment) ";
}


$select_Filtered_Donors = mysqli_query($conn, "SELECT pc.Registration_ID, pr.Patient_Name, ilc.Patient_Payment_ID, ilc.Transaction_Date_And_Time, ilc.Status, ilc.Transaction_Type, pr.Date_Of_Birth, pr.Gender, pc.consultation_ID, pr.Phone_Number, em.Employee_Name, ilc.Consultant_ID, i.Product_Name, ilc.Service_Date_And_Time,ilc.Payment_Cache_ID, ilc.Payment_Item_Cache_List_ID, ilc.Sub_Department_ID FROM tbl_item_list_cache ilc, tbl_payment_cache pc, tbl_patient_registration pr, tbl_items i, tbl_employee em WHERE pr.Registration_ID = pc.Registration_ID AND pc.Payment_Cache_ID = ilc.Payment_Cache_ID AND ilc.Check_In_Type = 'Surgery' AND ilc.Status IN ('active','paid') AND i.Item_ID = ilc.Item_ID AND em.Employee_ID = ilc.Consultant_ID $check_status $filter ORDER BY ilc.Service_Date_And_Time ASC LIMIT 50") or die(mysqli_error($conn));

if(mysqli_num_rows($select_Filtered_Donors) > 0){
while ($row = mysqli_fetch_array($select_Filtered_Donors)) {

    $date1 = new DateTime($Today);
    $date2 = new DateTime($row['Date_Of_Birth']);
    $diff = $date1->diff($date2);
    $age = $diff->y . " Years, ";
    $age .= $diff->m . " Months, ";
    $age .= $diff->d . " Days";

    $Registration_ID = $row['Registration_ID'];
    $my_theater_room_id = $row['theater_room_id'];
    //check if is available
    $consultation_id = $row['consultation_ID'];
    $Payment_Item_Cache_List_ID = $row['Payment_Item_Cache_List_ID'];
    $Service_Date_And_Time = $row['Service_Date_And_Time'];
    $Consultant_ID = $row['Consultant_ID'];
    $Sub_Department_ID = $row['Sub_Department_ID'];
    $Status = $row['Status'];
    $Transaction_Type = $row['Transaction_Type'];
    $Patient_Payment_ID = $row['Patient_Payment_ID'];
    $Transaction_Date_And_Time = $row['Transaction_Date_And_Time'];
    $Phone_Number = $row['Phone_Number'];

    if(empty($Phone_Number)){
        $Phone_Number = "<b>NOT INSERTED</b>";
    }

    if($Status != 'active'){
        $check_payment = mysqli_query($conn, "SELECT Billing_Type, Pre_Paid FROM tbl_patient_payments WHERE Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
        while($rceipt = mysqli_fetch_assoc($check_payment)){
            $Pre_Paid = $rceipt['Pre_Paid'];
            $Billing_Type = $rceipt['Billing_Type'];

            if(($Billing_Type == 'Inpatient Cash' || $Billing_Type == 'Outpatient Cash') && $Pre_Paid == 0){
                $malipo = "<span style='font-weight: bold;'>Paid Cash</span>";
            }elseif(($Billing_Type == 'Inpatient Cash' || $Billing_Type == 'Outpatient Cash') && $Pre_Paid == 1){
                $malipo = "<span style='font-weight: bold;'>Billed Cash</span>";
            }elseif(($Billing_Type == 'Inpatient Credit' || $Billing_Type == 'Outpatient Credit')){
                $malipo = "<span style='font-weight: bold;'>Approved</span>";
            }
        }
        $condition = '';
    }else{
        if($Transaction_Type == 'Cash'){
            $malipo = '<span style="color: red; font-weight: bold;">Not Paid</span>';
        }else{
            $malipo = '<span style="color: red; font-weight: bold;">Not Approved</span>';
        }
        $condition = "style='display: none;'";
    }

    $Doctors_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Consultant_ID'"))['Employee_Name'];
    $Sub_Department_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID = '$Sub_Department_ID'"))['Sub_Department_Name'];

    // die("SELECT Admision_ID FROM tbl_admission WHERE Registration_ID = '$Registration_ID' AND Admision_Status = 'Admitted' AND Bed_Name <> '' ORDER BY Admision_ID DESC LIMIT 1");
    $Admision_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Admision_ID FROM tbl_admission WHERE Registration_ID = '$Registration_ID' AND Admission_Status = 'Admitted' AND Bed_Name <> '' ORDER BY Admision_ID DESC LIMIT 1 "))['Admision_ID'];


    $check = mysqli_query($conn, "SELECT `date`, consent_amputation from tbl_consert_forms_details where Registration_ID = '$Registration_ID' AND Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($check);


    while ($data = mysqli_fetch_assoc($check)) {
        $consent_amputation = $data['consent_amputation'];
        $Operative_Date_Time = $data['date'];
    }

    // $Service_Date_And_Time = $row['Service_Date_And_Time'];
    if($Service_Date_And_Time == '0000-00-00 00:00:00'){
        $Service_Date_And_Time = "<span style='font-weight: bold;'>DATE NOT SELECTED</span>";
    }

    $Surgery_Status = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Surgery_Status FROM tbl_surgery_appointment WHERE Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'"))['Surgery_Status'];

    if (strtolower($Surgery_Status) == 'pending') {
        $check = "checked='checked'";
    }else{
        $check = '';
    }

    

    if ($num > 0) {
        if ($consent_amputation == 'Disagree' || $consent_amputation == '') {
            echo "<tr style='background: green; color: white;'><td>" . $temp . "</td>";
            echo "<td>" . ucwords(strtolower($Doctors_name)) . "</td>";
            echo "<td>" . ucwords(strtolower($row['Patient_Name'])) . "</td>";
            echo "<td>" . $row['Registration_ID'] . "</td>";
            echo "<td>" . $age . "</td>";
            echo "<td>" . $row['Gender'] . "</td>";
            echo "<td>" . $Phone_Number . "</td>";
            echo "<td>" . $row['Product_Name'] . "</td>";
            echo "<td>" . $Transaction_Date_And_Time . "</td>";
            echo "<td onclick='Date_Time(" . $Payment_Item_Cache_List_ID . ")' title='Click Surgery Date incase you want to edit it.' id='Date_Time" . $Payment_Item_Cache_List_ID . "'>" . $Service_Date_And_Time . "</td>";
            echo "<td>" . $Sub_Department_Name . "</td>";
            echo "<td><a href='Patientfile_Record_Detail.php?Registration_ID=".$Registration_ID."&Patient_Payment_ID=&Patient_Payment_Item_List_ID=&PatientFile=PatientFileThisForm&position=out&this_page_from=patient_record' target='_blank' class='art-button-green'>PATIENT FILE</a></td>";
            echo "<td>" . $malipo . "</td>";
            echo "<td>
                <select id='assign".$Payment_Item_Cache_List_ID."' $condition class='Mark_This_Item' onchange='add_reason(".$Payment_Item_Cache_List_ID.")'>
                    <option></option>
                    <option>Accept</option>
                    <option>Reject</option>
                </select>
            </td>
            <td>
            <select id='Type_Of_Anesthetic".$Payment_Item_Cache_List_ID."' $condition class='Mark_This_Item' onchange='add_Anaesthesia(".$Payment_Item_Cache_List_ID.")' style='text-align: center;width:100%;display:inline; height: 32px; border-radius: 5px; font-size: 12px;'>
                <option value=''>SELECT TYPE</option>";

                $Select_anasthetic = mysqli_query($conn, "SELECT Anaesthesia_Type FROM tbl_anaesthesia_type WHERE Status = 'enabled'");
                    while($dt = mysqli_fetch_assoc($Select_anasthetic)){
                        $Anaesthesia_Type = $dt['Anaesthesia_Type'];
                        echo "<option>".$Anaesthesia_Type."</option>";
                    }
            echo "</select>
        </td>";            
            
            $temp++;
            echo "</tr>";
        } else {
            echo "<tr style='background: #ff8080; color: white;'><td>" . $temp . "</td>";
            echo "<td>" . ucwords(strtolower($Doctors_name)) . "</td>";
            echo "<td>" . ucwords(strtolower($row['Patient_Name'])) . "</td>";
            echo "<td>" . $row['Registration_ID'] . "</td>";
            echo "<td>" . $age . "</td>";
            echo "<td>" . $row['Gender'] . "</td>";
            echo "<td>" . $Phone_Number . "</td>";
            echo "<td>" . $row['Product_Name'] . "</td>";
            echo "<td>" . $Transaction_Date_And_Time . "</td>";
            echo "<td onclick='Date_Time(" . $Payment_Item_Cache_List_ID . ")' title='Click Surgery Date incase you want to edit it.' id='Date_Time" . $Payment_Item_Cache_List_ID . "'>" . $Service_Date_And_Time . "</td>";
            echo "<td>" . $Sub_Department_Name . "</td>";
            echo "<td><a href='Patientfile_Record_Detail.php?Registration_ID=".$Registration_ID."&Patient_Payment_ID=&Patient_Payment_Item_List_ID=&PatientFile=PatientFileThisForm&position=out&this_page_from=patient_record' target='_blank' class='art-button-green'>PATIENT FILE</a></td>";
            echo "<td>" . $malipo . "</td>";
            echo "<td>
                    <select id='assign".$Payment_Item_Cache_List_ID."' $condition class='Mark_This_Item' onchange='add_reason(".$Payment_Item_Cache_List_ID.")'>
                        <option></option>
                        <option>Accept</option>
                        <option>Reject</option>
                    </select>
                </td>
                <td>
                <select id='Type_Of_Anesthetic".$Payment_Item_Cache_List_ID."' $condition class='Mark_This_Item' onchange='add_Anaesthesia(".$Payment_Item_Cache_List_ID.")' style='text-align: center;width:100%;display:inline; height: 32px; border-radius: 5px; font-size: 12px;'>
                    <option value=''>SELECT TYPE</option>";

                    $Select_anasthetic = mysqli_query($conn, "SELECT Anaesthesia_Type FROM tbl_anaesthesia_type WHERE Status = 'enabled'");
                        while($dt = mysqli_fetch_assoc($Select_anasthetic)){
                            $Anaesthesia_Type = $dt['Anaesthesia_Type'];
                            echo "<option>".$Anaesthesia_Type."</option>";
                        }
                echo "</select>
            </td>";
            

            $temp++;
            echo "</tr>";
        }
    } else {
        echo "<tr><td>" . $temp . "</td>";
        echo "<td>" . ucwords(strtolower($Doctors_name)) . "</td>";
        echo "<td>" . ucwords(strtolower($row['Patient_Name'])) . "</td>";
        echo "<td>" . $row['Registration_ID'] . "</td>";
        echo "<td>" . $age . "</td>";
        echo "<td>" . $row['Gender'] . "</td>";
        echo "<td>" . $Phone_Number . "</td>";
        echo "<td>" . $row['Product_Name'] . "</td>";
        echo "<td>" . $Transaction_Date_And_Time . "</td>";
        echo "<td onclick='Date_Time(".$Payment_Item_Cache_List_ID.")' title='Click Surgery Date incase you want to edit it.' id='Date_Time" . $Payment_Item_Cache_List_ID . "'>" . $Service_Date_And_Time . "</td>";
        echo "<td>" . $Sub_Department_Name . "</td>";
        echo "<td><a href='Patientfile_Record_Detail.php?Registration_ID=".$Registration_ID."&Patient_Payment_ID=&Patient_Payment_Item_List_ID=&PatientFile=PatientFileThisForm&position=out&this_page_from=patient_record' target='_blank' class='art-button-green'>PATIENT FILE</a></td>";
        echo "<td>" . $malipo . "</td>";
        echo "<td>
                <select id='assign".$Payment_Item_Cache_List_ID."' $condition class='Mark_This_Item' onchange='add_reason(".$Payment_Item_Cache_List_ID.")'>
                    <option></option>
                    <option>Accept</option>
                    <option>Reject</option>
                </select>
            </td>
            <td>
                <select id='Type_Of_Anesthetic".$Payment_Item_Cache_List_ID."' $condition class='Mark_This_Item' onchange='add_Anaesthesia(".$Payment_Item_Cache_List_ID.")' style='text-align: center;width:100%;display:inline; height: 32px; border-radius: 5px; font-size: 12px;'>
                    <option value=''>SELECT TYPE</option>";

                    $Select_anasthetic = mysqli_query($conn, "SELECT Anaesthesia_Type FROM tbl_anaesthesia_type WHERE Status = 'enabled'");
                        while($dt = mysqli_fetch_assoc($Select_anasthetic)){
                            $Anaesthesia_Type = $dt['Anaesthesia_Type'];
                            echo "<option>".$Anaesthesia_Type."</option>";
                        }
                echo "</select>
            </td>";

        $temp++;
        echo "</tr>";
        }
    }
}else{
    echo "<tr style=' background: #fff;'> 
            <td colspan='15'><span><h4 style='color: red; font-weight: bold; text-align: center; margin: 2%;'>THERE'S NO PENDING SURGERY SCHEDULED FOR THIS PERIOD OF TIME</h4></span></td>
        </tr>";
}

mysqli_close($conn);
?>

<script src="js/select2.min.js"></script>

<script>
        $(document).ready(function (e){
        $(".Surgeon_filled").select2();
    });
</script>