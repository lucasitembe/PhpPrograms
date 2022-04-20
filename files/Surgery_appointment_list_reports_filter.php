<link rel="stylesheet" href="table.css" media="screen">

<?php
include("./includes/connection.php");
$temp = 1;

$Patient_Name = $_GET['Patient_Name'];
$Patient_Number = $_GET['Patient_Number'];
$Sponsor_ID = $_GET['Sponsor_ID'];
$Surgical_Type = $_GET['Surgical_Type'];
$Current_Employee_ID = $_GET['Current_Employee_ID'];
$date_From = $_GET['date_From'];
$date_To = $_GET['date_To'];
$Inp_Sub_Department_ID = $_GET['Sub_Department_ID'];


$filter = '';



//today function
$Today_Date = mysqli_query($conn, "SELECT now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $Start = $Today." 00:00";
    $End = $Today." 23:59";
    // $age ='';
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
    $filter .= " AND ilc.Service_Date_And_Time BETWEEN '$Start' AND '$End'";
}

if($Inp_Sub_Department_ID != "All"){
    $filter .= " AND ilc.finance_department_id = '$Inp_Sub_Department_ID'";
}

if(!empty($Surgical_Type)){
    $filter .= " AND sap.Surgery_Status = '$Surgical_Type'";
}


$select_Filtered_Donors = mysqli_query($conn, "SELECT pc.Registration_ID, sap.Surgery_Status, pr.Patient_Name, sap.Remarks, ilc.Transaction_Date_And_Time, pr.Date_Of_Birth, pr.Gender, 
                                                pc.consultation_ID, pr.Phone_Number, em.Employee_Name, ilc.Consultant_ID, i.Product_Name, 
                                                ilc.Service_Date_And_Time,ilc.Payment_Cache_ID,ilc.theater_room_id, 
                                                ilc.Payment_Item_Cache_List_ID, ilc.Sub_Department_ID FROM tbl_item_list_cache ilc, tbl_payment_cache pc, 
                                                tbl_patient_registration pr, tbl_items i, tbl_employee em, tbl_surgery_appointment sap WHERE pr.Registration_ID = pc.Registration_ID AND 
                                                pc.Payment_Cache_ID = ilc.Payment_Cache_ID AND ilc.Check_In_Type = 'Surgery' AND 
                                                i.Item_ID = ilc.Item_ID AND em.Employee_ID = ilc.Consultant_ID AND ilc.Payment_Item_Cache_List_ID = sap.Payment_Item_Cache_List_ID $filter ORDER BY ilc.Service_Date_And_Time ASC") or die(mysqli_error($conn));

IF(mysqli_num_rows($select_Filtered_Donors)>0){
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
    $Surgery_Status = $row['Surgery_Status'];
    $Remarks = $row['Remarks'];

        if($Surgery_Status == "Completed"){
            $Style = "style='display: none;'";
            $Final_Status = "Completed";
        }elseif($Surgery_Status == "Active"){
            $Style = "style='display: none;'";
            $Final_Status = "Waiting";
        }elseif($Surgery_Status == "Death"){
            $Style ="";
            $Final_Status = "<b>DECEASED</b>";
        }elseif($Surgery_Status == 'removed'){
            $Style ="";
            $Final_Status = "Postponed";
        }

    $My_theater_Room = mysqli_fetch_assoc(mysqli_query($conn, "SELECT theater_room_name FROM tbl_theater_rooms WHERE theater_room_id = '$my_theater_room_id' AND room_status = 'active'"))['theater_room_name'];

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
        $Consent = $Operative_Date_Time;
    }else{
        $Consent = "<b>NO CONSENT SIGNED</b>";
    }

        echo "<tr><td>" . $temp . "</td>";
        echo "<td>" . ucwords(strtolower($Doctors_name)) . "</td>";
        echo "<td>" . ucwords(strtolower($row['Patient_Name'])) . "</td>";
        echo "<td>" . $row['Registration_ID'] . "</td>";
        echo "<td>" . $age . "</td>";
        echo "<td>" . $row['Gender'] . "</td>";
        echo "<td>" . $row['Phone_Number'] . "</td>";
        echo "<td>" . $row['Product_Name'] . "</td>";
        echo "<td onclick='Date_Time(".$Payment_Item_Cache_List_ID.")' title='Click Surgery Date incase you want to edit it.' id='Date_Time" . $Payment_Item_Cache_List_ID . "'>" . $Service_Date_And_Time . "</td>";
        echo "<td>" . $Sub_Department_Name . "</td>";
        echo "<td>" . $My_theater_Room . "</td>";
        echo "<td>".$Consent."</td>";
        echo "<td $Style>".$Remarks."</td>";
        echo "<td>".$Final_Status."</td>";


        $temp++;
        echo "</tr>";
    
}
}else{
    echo "<tr class='rows_list'>
    <td colspan='14' style='font-size: 17px; color: #bd0d0d; font-weight: bold; text-align: center;'>NO SURGERY SCHEDULED FOR THIS PERIOD</td>
    </tr> ";
}
?>
</table>