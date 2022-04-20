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

if(!empty($Inp_Sub_Department_ID)){
    $filter .= " AND ilc.Sub_Department_ID = '$Inp_Sub_Department_ID'";
}





// echo '<table width =100% border=0>';

// echo '<tr id="thead"><td style="width:2%; text-align: center;"><b>SN</b></td>
              
// 	 </tr>';


$select_Filtered_Donors = mysqli_query($conn, "SELECT pc.Registration_ID, pr.Patient_Name, ilc.Transaction_Date_And_Time, pr.Date_Of_Birth, pr.Gender, 
                                                pc.consultation_ID, pr.Phone_Number, em.Employee_Name, ilc.Consultant_ID, i.Product_Name, 
                                                ilc.Service_Date_And_Time,ilc.Payment_Cache_ID,ilc.theater_room_id, ilc.Priority,
                                                ilc.Payment_Item_Cache_List_ID, ilc.Sub_Department_ID FROM tbl_item_list_cache ilc, tbl_payment_cache pc, 
                                                tbl_patient_registration pr, tbl_items i, tbl_employee em WHERE pr.Registration_ID = pc.Registration_ID AND 
                                                pc.Payment_Cache_ID = ilc.Payment_Cache_ID AND ilc.Check_In_Type = 'Surgery' AND ilc.Status IN ('active','paid') AND 
                                                i.Item_ID = ilc.Item_ID AND em.Employee_ID = ilc.Consultant_ID AND ilc.Payment_Item_Cache_List_ID IN(SELECT Payment_Item_Cache_List_ID FROM tbl_surgery_appointment WHERE Surgery_Status IN('active', 'On Progress','Completed')) $filter ORDER BY ilc.Service_Date_And_Time DESC LIMIT 100") or die(mysqli_error($conn));


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
    $Priority = $row['Priority'];

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
        if ($consent_amputation == 'Disagree' || $consent_amputation == '') {
            echo "<tr style='background: green; color: white;'><td>" . $temp . "</td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none; color: white;'>" . ucwords(strtolower($Doctors_name)) . "</a></td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none; color: white;'>" . ucwords(strtolower($row['Patient_Name'])) . "</a></td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none; color: white;'>" . $row['Registration_ID'] . "</a></td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none; color: white;'>" . $age . "</a></td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none; color: white;'>" . $row['Gender'] . "</a></td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none; color: white;'>" . $row['Phone_Number'] . "</a></td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none; color: white;'>" . $row['Product_Name'] . "</a></td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none; color: white;'>" . $Service_Date_And_Time . "</a></td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none; color: white;'>" . $Sub_Department_Name . "</a></td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none; color: white;'>" . $My_theater_Room . "</a></td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none; color: white;'>" . $Operative_Date_Time . "</a></td>";

            
            $temp++;
            echo "</tr>";
        } else {
            echo "<tr style='background: #ff8080; color: white;'><td>" . $temp . "</td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>" . ucwords(strtolower($Doctors_name)) . "</a></td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>" . ucwords(strtolower($row['Patient_Name'])) . "</a></td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>" . $row['Registration_ID'] . "</a></td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>" . $age . "</a></td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>" . $row['Gender'] . "</a></td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>" . $row['Phone_Number'] . "</a></td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>" . $row['Product_Name'] . "</a></td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none; color: white;'>" . $Service_Date_And_Time . "</a></td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>" . $Sub_Department_Name . "</a></td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>" . $My_theater_Room . "</a></td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>" . $Operative_Date_Time . "</a></td>";
            

            $temp++;
            echo "</tr>";
        }
    } else {
        if($Priority == 'Urgent'){
            echo "<tr  title='This Surgery Patient Did not Sign Consent Form, You can Only perform this Surgery because It an Emergency' style='background: #bd0d0d; color: white; font-weight: bold;'>
            <td>" . $temp . "</td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none; color: white;'>" . ucwords(strtolower($Doctors_name)) . "</a></td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none; color: white;'>" . ucwords(strtolower($row['Patient_Name'])) . "</a></td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none; color: white;'>" . $row['Registration_ID'] . "</a></td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none; color: white;'>" . $age . "</a></td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none; color: white;'>" . $row['Gender'] . "</a></td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none; color: white;'>" . $row['Phone_Number'] . "</a></td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none; color: white;'>" . $row['Product_Name'] . "</a></td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none; color: white;'>" . $Service_Date_And_Time . "</a></td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none; color: white;'>" . $Sub_Department_Name . "</a></td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none; color: white;'>" . $My_theater_Room . "</a></td>";
            echo "<td><a href='perform_suggery_theater.php?Registration_ID=" . $row['Registration_ID'] . "&consultation_id=" . $consultation_id . "&Admision_ID=" . $Admision_ID . "&Payment_Item_Cache_List_ID=" . $Payment_Item_Cache_List_ID . "&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none; color: white;'><b>NO CONSENT SIGNED</b></a></td></tr>";
        }else{
            echo "<tr  title='This Surgery Patient Did not Sign Consent Form, You can not perform this Surgery' onclick='alert_consent()'><td>" . $temp . "</td>";
            echo "<td>" . ucwords(strtolower($Doctors_name)) . "</td>";
            echo "<td>" . ucwords(strtolower($row['Patient_Name'])) . "</td>";
            echo "<td>" . $row['Registration_ID'] . "</td>";
            echo "<td>" . $age . "</td>";
            echo "<td>" . $row['Gender'] . "</td>";
            echo "<td>" . $row['Phone_Number'] . "</td>";
            echo "<td>" . $row['Product_Name'] . "</td>";
            echo "<td>" . $Service_Date_And_Time . "</td>";
            echo "<td>" . $Sub_Department_Name . "</td>";
            echo "<td>" . $My_theater_Room . "</td>";
            echo "<td><b>NO CONSENT SIGNED</b></td>";
        }



        $temp++;
        echo "</tr>";
    }
}
?>
</table>
<div id="changeDateDiv" style="display: none; float: inherit;">
    <input type='text' name='Date_From' id='date_Fromx' required='required' style="padding-left:5px" autocomplete="off">
    <br /><br />
    <input type='hidden' name='Date_From' id='date_From_val' value="">
    <center> <input type="button" value="Save Changes" class="SaveChangedDate"></center>
</div>

<style>
    .Date_Time:hover {
        cursor: pointer;
    }
</style>

<script>
    function check_assign(Payment_Item_Cache_List_ID) {
        var assign = "assign"+Payment_Item_Cache_List_ID;
        var Payment_Item_Cache_List_ID = document.getElementById("assign" + Payment_Item_Cache_List_ID).value;
        var Employee_ID = '<?= $Current_Employee_ID ?>';

        $.ajax({
            type: "GET",
            url: "ajax_set_Surgery_appointment.php",
            data: {Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,Employee_ID:Employee_ID},
            cache: false,
            success: function (response) {
                
            }
        });
    }
    // function check_assign() {
    //     var items = [];
    //     $('.Mark_This_Item').each(function() {
    //         if(this.checked){
    //             var item_id = $(this).attr("id");
    //             items.push(item_id);                
    //         }
    //     });

    //     alert(items);
    // }
//     function Date_Time(Payment_Item_Cache_List_ID) {
//         var Date_Time = "Date_Time"+Payment_Item_Cache_List_ID;
//         var Current_Date = document.getElementById("Date_Time" + Payment_Item_Cache_List_ID).value;
//         $('#changeDateDiv').dialog({
//             modal: true,
//             width: '30%',
//             resizable: true,
//             draggable: true,
//             title: 'Change Surgery Date'
// //            close: function (event, ui) {
// //               
// //            }
//         });
//     }
    function set_room(Payment_Item_Cache_List_ID) {
        var Payment_Item_Cache_List_ID = Payment_Item_Cache_List_ID;
        var id = "room_name_" + Payment_Item_Cache_List_ID;
        var room_id = document.getElementById("room_name_" + Payment_Item_Cache_List_ID).value;

        if (room_id == 0) {
            alert("Please select room");
        } else {

            $.ajax({
                url: 'update_sugery_room.php',
                method: 'POST',
                dataType: 'text',
                data: {
                    update_request: "update",
                    Payment_Item_Cache_List_ID: Payment_Item_Cache_List_ID,
                    room_id: room_id
                },
                success: function(response) {
                    alert(response)
                }
            });
        }
    }
    $('#doctorperformancereportsummarised').dataTable({
        "bJQueryUI": true,
    });

    $('#date_Fromx').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#date_Fromx').datetimepicker({
        value: '',
        step: 1
    });

    $('.SaveChangedDate').on('click', function() {
        alert("oyooooooooooo");
        exit();
        var pay_ID = $('#date_From_val').val();
        var DateOfService = $('#date_Fromx').val();
        if (DateOfService == '' || DateOfService == 'NULL') {
            alert('Date cannot be empty,please select date');
            return false;
        }
        if (confirm('Are you sure you want change this date?')) {
            $.ajax({
                type: 'POST',
                url: 'requests/Update_sugery_date.php',
                data: 'action=update&pay_ID=' + pay_ID + '&DateOfService=' + DateOfService,
                cache: false,
                success: function(e) {
                    alert(e);
                }
            });
        } else {
            return false;
        }
    });
</script>

<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="css/jquery-ui.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script src="css/jquery-ui.js"></script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">

<script>
    $(document).ready(function() {
        $('select').select2();
    });
</script>