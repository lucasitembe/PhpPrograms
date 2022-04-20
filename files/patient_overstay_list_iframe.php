<link rel="stylesheet" href="table.css" media="screen">

<?php
include("./includes/connection.php");
$temp = 1;

$Patient_Name = $_GET['Patient_Name'];
$Patient_Number = $_GET['Patient_Number'];
$Sponsor_ID = $_GET['Sponsor_ID'];
$Employee_ID = $_GET['Employee_ID'];
$date_From = $_GET['date_From'];
$date_To = $_GET['date_To'];
$number = $_GET['number'];


$filter = '';



//today function
$Today_Date = mysqli_query($conn, "SELECT now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    // $age ='';
}
//end

if (!empty($Patient_Number)) {
    $filter .= " AND inp.Registration_ID = '$Patient_Number'";
}

if (!empty($Patient_Name)) {
    $filter .= " AND pr.Patient_Name LIKE '%$Patient_Name%'";
}


if (!empty($date_From) && !empty($date_To)) {
    $filter .= " AND inp.Signed_Date_Time BETWEEN '$date_From' AND '$date_To'";
}





echo '<table width =100% border=0>';

echo '<tr id="thead"><td style="width:2%; text-align: center;"><b>SN</b></td>
            <td style="width:10%; text-align: center;"><b>PATIENT NAME</b></td>
            <td style="width:5%; text-align: center;"><b>PATIENT #</b></td>
            <td style="text-align: center;width:10%;"><b>AGE</b></td>
            <td style="text-align: center;width:4%;"><b>GENDER</b></td>
            <td style="text-align: center;width:5%;"><b>SPONSOR</b></td>
            <td style="text-align: center; width: 8%;"><b>WARD</b></td>
            <td style="width:6%;text-align: center;"><b>ROOM TYPE</b></td>
            <td style="text-align: center; width:10%"><b>SIGNED BY</b></td>
            <td style="text-align: center;width:8%;"><b>ADMITTED DATE</b></td>                   
            <td style="text-align: center;width:8%;"><b>SIGNED DATE</b></td>                   
	 </tr>';

$select_Filtered_Donors = mysqli_query($conn, "SELECT wr.Room_Type, inp.Admision_ID, inp.consultation_ID, inp.Check_In_ID, pr.Patient_Name, pr.Gender, em.Employee_Name, pr.Registration_ID, hw.Hospital_Ward_Name, pr.Date_Of_Birth, sp.Guarantor_Name, inp.Signed_Date_Time, ad.Admission_Date_Time FROM tbl_inpatient_overstaying inp, tbl_patient_registration pr, tbl_hospital_ward hw, tbl_sponsor sp, tbl_ward_rooms wr, tbl_employee em, tbl_admission ad WHERE sp.Sponsor_ID = inp.Sponsor_ID AND pr.Registration_ID = inp.Registration_ID AND em.Employee_ID = inp.Employee_ID AND hw.Hospital_Ward_ID = ad.Hospital_Ward_ID AND ad.Admision_ID = inp.Admision_ID AND wr.ward_room_id AND inp.ward_room_id AND em.Employee_ID = inp.Employee_ID $filter GROUP BY inp.Overstay_Form_ID ORDER BY inp.Overstay_Form_ID DESC LIMIT 100") or die(mysqli_error($conn));


while ($row = mysqli_fetch_array($select_Filtered_Donors)) {

    $date1 = new DateTime($Today);
    $date2 = new DateTime($row['Date_Of_Birth']);
    $diff = $date1->diff($date2);
    $age = $diff->y . " Years, ";
    $age .= $diff->m . " Months, ";
    $age .= $diff->d . " Days";

    $Registration_ID = $row['Registration_ID'];
    $Room_Type = $row['Room_Type'];
    $Check_In_ID = $row['Check_In_ID'];
    $Patient_Name = $row['Patient_Name'];
    $Gender = $row['Gender'];
    $Employee_Name = $row['Employee_Name'];
    $Hospital_Ward_Name = $row['Hospital_Ward_Name'];
    $Guarantor_Name = $row['Guarantor_Name'];
    $Signed_Date_Time = $row['Signed_Date_Time'];
    $Admission_Date_Time = $row['Admission_Date_Time'];
    $consultation_ID = $row['consultation_ID'];
    $Admision_ID = $row['Admision_ID'];

    // die("SELECT signature FROM tbl_check_in WHERE Check_In_ID = '$Check_In_ID'");
    $signature_check = mysqli_query($conn, "SELECT `signature` FROM tbl_check_in WHERE Check_In_ID = '$Check_In_ID'");
        $Signature = mysqli_fetch_assoc($signature_check)['signature'];

    $num = mysqli_num_rows($select_Filtered_Donors);
    if ($num > 0) {
        if ($Signature != 0 || !empty($Signature)) {
            echo "<tr style='background: green; color: white;'><td id='thead'>" . $temp . "</td>";
            echo "<td><a href='preview_overstay_form.php?Registration_ID=".$Registration_ID."&Overstay_Form_ID=".$Overstay_Form_ID."&consultation_ID=".$consultation_ID."&Check_In_ID=".$Check_In_ID."&Admision_ID=".$Admision_ID."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none; color: white;'>" . ucwords(strtolower($Patient_Name)) . "</a></td>";
            echo "<td><a href='preview_overstay_form.php?Registration_ID=".$Registration_ID."&Overstay_Form_ID=".$Overstay_Form_ID."&consultation_ID=".$consultation_ID."&Check_In_ID=".$Check_In_ID."&Admision_ID=".$Admision_ID."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none; color: white;'>" . $Registration_ID . "</a></td>";
            echo "<td><a href='preview_overstay_form.php?Registration_ID=".$Registration_ID."&Overstay_Form_ID=".$Overstay_Form_ID."&consultation_ID=".$consultation_ID."&Check_In_ID=".$Check_In_ID."&Admision_ID=".$Admision_ID."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none; color: white;'>" . $age . "</a></td>";
            echo "<td><a href='preview_overstay_form.php?Registration_ID=".$Registration_ID."&Overstay_Form_ID=".$Overstay_Form_ID."&consultation_ID=".$consultation_ID."&Check_In_ID=".$Check_In_ID."&Admision_ID=".$Admision_ID."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none; color: white;'>" . $Gender . "</a></td>";
            echo "<td><a href='preview_overstay_form.php?Registration_ID=".$Registration_ID."&Overstay_Form_ID=".$Overstay_Form_ID."&consultation_ID=".$consultation_ID."&Check_In_ID=".$Check_In_ID."&Admision_ID=".$Admision_ID."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none; color: white;'>" . $Guarantor_Name."</a></td>";
            echo "<td><a href='preview_overstay_form.php?Registration_ID=".$Registration_ID."&Overstay_Form_ID=".$Overstay_Form_ID."&consultation_ID=".$consultation_ID."&Check_In_ID=".$Check_In_ID."&Admision_ID=".$Admision_ID."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none; color: white;'>" . $Hospital_Ward_Name . "</a></td>";
            echo "<td><a href='preview_overstay_form.php?Registration_ID=".$Registration_ID."&Overstay_Form_ID=".$Overstay_Form_ID."&consultation_ID=".$consultation_ID."&Check_In_ID=".$Check_In_ID."&Admision_ID=".$Admision_ID."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none; color: white;'>" . $Room_Type . "</a></td>";
            echo "<td><a href='preview_overstay_form.php?Registration_ID=".$Registration_ID."&Overstay_Form_ID=".$Overstay_Form_ID."&consultation_ID=".$consultation_ID."&Check_In_ID=".$Check_In_ID."&Admision_ID=".$Admision_ID."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none; color: white;'>" . $Employee_Name . "</a></td>";
            echo "<td><a href='preview_overstay_form.php?Registration_ID=".$Registration_ID."&Overstay_Form_ID=".$Overstay_Form_ID."&consultation_ID=".$consultation_ID."&Check_In_ID=".$Check_In_ID."&Admision_ID=".$Admision_ID."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none; color: white;'>" . $Admission_Date_Time . "</a></td>";
            echo "<td><a href='preview_overstay_form.php?Registration_ID=".$Registration_ID."&Overstay_Form_ID=".$Overstay_Form_ID."&consultation_ID=".$consultation_ID."&Check_In_ID=".$Check_In_ID."&Admision_ID=".$Admision_ID."&PatientRegistration=PatientRegistrationThisForm' target='_blank' style='text-decoration: none; color: white;'>" . $Signed_Date_Time . "</a></td>";

            $temp++;
            echo "</tr>";
        } else {
            echo "<tr><td id='thead'>" . $temp . "</td>";
            echo "<td><a href='overstay_consent_form.php?Registration_ID=" . $Registration_ID . "&consultation_ID=" . $consultation_ID . "&Admision_ID=" . $Admision_ID . "&Check_In_ID=".$Check_In_ID."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>" . ucwords(strtolower($Patient_Name)) . "</a></td>";
            echo "<td><a href='overstay_consent_form.php?Registration_ID=" . $Registration_ID . "&consultation_ID=" . $consultation_ID . "&Admision_ID=" . $Admision_ID . "&Check_In_ID=".$Check_In_ID."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>" . $Registration_ID . "</a></td>";
            echo "<td><a href='overstay_consent_form.php?Registration_ID=" . $Registration_ID . "&consultation_ID=" . $consultation_ID . "&Admision_ID=" . $Admision_ID . "&Check_In_ID=".$Check_In_ID."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>" . $age . "</a></td>";
            echo "<td><a href='overstay_consent_form.php?Registration_ID=" . $Registration_ID . "&consultation_ID=" . $consultation_ID . "&Admision_ID=" . $Admision_ID . "&Check_In_ID=".$Check_In_ID."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>" . $Gender . "</a></td>";
            echo "<td><a href='overstay_consent_form.php?Registration_ID=" . $Registration_ID . "&consultation_ID=" . $consultation_ID . "&Admision_ID=" . $Admision_ID . "&Check_In_ID=".$Check_In_ID."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>" . $Guarantor_Name."</a></td>";
            echo "<td><a href='overstay_consent_form.php?Registration_ID=" . $Registration_ID . "&consultation_ID=" . $consultation_ID . "&Admision_ID=" . $Admision_ID . "&Check_In_ID=".$Check_In_ID."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>" . $Hospital_Ward_Name . "</a></td>";
            echo "<td><a href='overstay_consent_form.php?Registration_ID=" . $Registration_ID . "&consultation_ID=" . $consultation_ID . "&Admision_ID=" . $Admision_ID . "&Check_In_ID=".$Check_In_ID."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>" . $Room_Type . "</a></td>";
            echo "<td><a href='overstay_consent_form.php?Registration_ID=" . $Registration_ID . "&consultation_ID=" . $consultation_ID . "&Admision_ID=" . $Admision_ID . "&Check_In_ID=".$Check_In_ID."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>" . $Employee_Name . "</a></td>";
            echo "<td><a href='overstay_consent_form.php?Registration_ID=" . $Registration_ID . "&consultation_ID=" . $consultation_ID . "&Admision_ID=" . $Admision_ID . "&Check_In_ID=".$Check_In_ID."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>" . $Admission_Date_Time . "</a></td>";
            echo "<td><a href='overstay_consent_form.php?Registration_ID=" . $Registration_ID . "&consultation_ID=" . $consultation_ID . "&Admision_ID=" . $Admision_ID . "&Check_In_ID=".$Check_In_ID."&PatientRegistration=PatientRegistrationThisForm' target='_parent' style='text-decoration: none;'>" . $Signed_Date_Time . "</a></td>";

            $temp++;
            echo "</tr>";
        }
    }
    
}
?>
</table>
<div id="changeDateDiv" style="display: none">
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

    $('.Date_Time').on('click', function() {
        var id = $(this).attr('id');
        $('#date_From_val').val(id);
        $('#changeDateDiv').dialog({
            modal: true,
            width: '30%',
            resizable: true,
            draggable: true,
            title: 'Change Surgery Date'
            //            close: function (event, ui) {
            //               
            //            }
        });

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