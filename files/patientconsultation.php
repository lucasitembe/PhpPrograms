<?php include("./includes/header.php");
include("./includes/connection.php");

echo "<link rel='stylesheet' href='fixHeader.css'>";

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Reception_Works'])) {
        if ($_SESSION['userinfo']['Reception_Works'] != 'yes') {
            if (isset($_SESSION['userinfo']['Management_Works']) && $_SESSION['userinfo']['Management_Works'] != 'yes') {
                header("Location: ./index.php?InvalidPrivilege=yes");
            }
        }
    } elseif (isset($_SESSION['userinfo']['Management_Works'])) {
        if ($_SESSION['userinfo']['Management_Works'] != 'yes') {
            if (isset($_SESSION['userinfo']['Reception_Works']) && $_SESSION['userinfo']['Reception_Works'] != 'yes') {
                header("Location: ./index.php?InvalidPrivilege=yes");
            }
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_GET['Section'])) {
    $Section = $_GET['Section'];
} else {
    $Section = '';
}
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Reception_Works'] == 'yes') {
        echo "<a href='receptionReports.php?Section=" . $Section . "&ReceptionReportThisPage' class='art-button-green'>BACK</a>";
    }
};
echo '<!--START HERE-->

';
$Today_Date = mysqli_query($conn, "select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
};
echo '<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;

    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style> 

<br/><br/>
<!-- get employee id-->
';
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
};
echo '


<!--Getting employee name -->
';
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = 'Unknown Employee';
};

echo '
<script type=\'text/javascript\'>       
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>
<center>
    <fieldset style=\'height: 100px;background-color:#eeeeee;\'>
    <legend style="background-color:#006400;color:white;padding:5px;" align="right"><b>LIST OF CONSULTED PATIENTS</b></legend>
        <table width=90%> 
            <tr>
                <td width=33%>
                    <input type=\'text\' name=\'Search_Value\' id=\'Search_Value\' placeholder=\'~~~~~Search Patient~~~~~\' style=\'text-align: center;\' autocomplete=\'off\'>
                </td>
                <td style=\'text-align: right;\' width=7%><b>From</b></td>
                <td width=23%>
                    <input type=\'text\' name=\'Date_From\' id=\'date\' placeholder=\'Start Date\' style=\'text-align: center;\' value=\'';
echo $Today;;
echo '\' readonly=\'readonly\' autocomplete=\'off\'>
                </td>
                <td style=\'text-align: right;\' width=7%><b>To</b></td>
                <td width=23%>
                    <input type=\'text\' name=\'Date_To\' id=\'date2\' placeholder=\'End Date\' style=\'text-align: center;\' value=\'';
echo $Today;;
echo '\' readonly=\'readonly\' autocomplete=\'off\'>
                </td>
                <td style=\'text-align: center;\' width=7%><input name=\'Filter\' type=\'button\' value=\'FILTER\' class=\'art-button\' onclick=\'Get_Previous_Requisition()\'></td>
            </tr>
        </table>
    </fieldset>
</center>
<center>
    <fieldset style=\'overflow-y: scroll; height: 400px;background-color:#eeeeee;\' id=\'Patient_List_Area\'>


        <table id="myList" width=100% class="fixTableHead">
            <thead>
                <tr style="background-color: #ccc;">
                    <td width="5%"><b>SN</b></td>
                    <td><b>PATIENT NAME</b></td>
                    <td><b>SPONSOR NAME</b></td>
                    <td><b>CONSULTING DR / CLINIC</b></td>
                    <td><b>IN CLINIC CONSULTING DR</b></td>
                    <td><b>CONSULTATION DATE</b></td>
                </tr>
            </thead>
            ';
$temp = 0;
$select_details = mysqli_query($conn, "SELECT sp.Guarantor_Name, pr.Patient_Name, 
	ci.Check_In_Date_And_Time, ppl.Check_In_Type,ppl.Patient_Payment_Item_List_ID,ppl.Clinic_ID,
				    ppl.Patient_Direction, ppl.Consultant, Consultant_ID
				    FROM
					tbl_patient_payments pp, tbl_patient_payment_item_list ppl,
				    tbl_patient_registration pr, tbl_check_in ci, tbl_sponsor sp where
				    pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
				    pp.Registration_ID = pr.Registration_ID and
				    ci.Check_In_ID = pp.Check_In_ID and
				    sp.Sponsor_ID = pr.Sponsor_ID and
				    Receipt_Date between '$Today' and '$Today' and Check_In_Type='Doctor Room' GROUP BY pr.Registration_ID") or die(mysqli_error($conn));
$num = mysqli_num_rows($select_details);
if ($num > 0) {
    while ($data = mysqli_fetch_array($select_details)) {
        $consultingdoctor = $data['Consultant_ID'];
        $Patient_Direction = $data['Patient_Direction'];
        $consultingdoctor = $data['Consultant_ID'];
        $Clinic_ID = $data['Clinic_ID'];
        $select_clinic_name = mysqli_query($conn, "SELECT Clinic_Name FROM tbl_clinic WHERE Clinic_ID = '$Clinic_ID'");
        $Clinic_Name = mysqli_fetch_assoc($select_clinic_name)['Clinic_Name'];
        $Patient_Payment_Item_List_ID = $data['Patient_Payment_Item_List_ID'];
        $queryforconsultant = mysqli_query($conn, "SELECT
		Employee_Name,Employee_ID 
		FROM tbl_employee where 
		Employee_ID='$consultingdoctor' ") or die(mysqli_error($conn));
        $consult = mysqli_fetch_array($queryforconsultant);;
        echo '                    <tr>
                        <td>';
        echo ++$temp;;
        echo '</td>
                        <td>';
        echo $data['Patient_Name'];;
        echo '</td>
                        <td>';
        echo $data['Guarantor_Name'];;
        echo '</td>

                        ';
        if ($Patient_Direction == 'Direct To Doctor' || $Patient_Direction == 'Direct To Doctor Via Nurse Station') {
            $queryforconsultant = mysqli_query($conn, "SELECT
			Employee_Name,Employee_ID 
			FROM tbl_employee where 
			Employee_ID='$consultingdoctor' ") or die(mysqli_error($conn));
            $consult = mysqli_fetch_array($queryforconsultant);;
            echo '                            <td>Dr. ';
            echo $consult['Employee_Name'];;
            echo '</td><td>' . $Clinic_Name . '</td>
                            ';
        } else {
            echo '   <td>' . $Clinic_Name . '</td>                         <td>';
            echo $Clinic_Name;
            echo '</td>
                        ';
        };
        echo '<td>
                        ';
        // $querytocheck = mysqli_query($conn, "SELECT ppl.Patient_Payment_Item_List_ID,ppl.Patient_Direction,
        // c.Patient_Payment_Item_List_ID,c.employee_ID,Employee_Name,ppl.Consultant,ppl.Clinic_ID
        // FROM tbl_consultation c ,tbl_patient_payment_item_list ppl,tbl_employee e
        // WHERE c.Patient_Payment_Item_List_ID=ppl.Patient_Payment_Item_List_ID AND
        // c.Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID' AND
        // e.Employee_ID=c.employee_ID AND 
        // ppl.Patient_Direction='Direct To Clinic'") or die(mysqli_error($conn));
        // $rowconsult = mysqli_fetch_array($querytocheck);;
        echo $data['Check_In_Date_And_Time'];;
        echo '</td>
                    </tr>
        ';
    }
};
echo '	
        </table>
    </fieldset>
</center>
<!--END HERE-->

<script>       
    function Get_Previous_Requisition() {
        var Start_Date = document.getElementById("date").value;
        var End_Date = document.getElementById("date2").value;
   
        if (Start_Date != null && Start_Date != \'\' && End_Date != null && End_Date != \'\') {
            if (window.XMLHttpRequest) {
                myObjectGetPrevious = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectGetPrevious = new ActiveXObject(\'Micrsoft.XMLHTTP\');
                myObjectGetPrevious.overrideMimeType(\'text/xml\');
            }
            
            myObjectGetPrevious.onreadystatechange = function () {
                document.getElementById("Patient_List_Area").innerHTML = "<div align=\'center\' style=\'\' id=\'progressStatus\'><img src=\'./images/ajax-loader_1.gif\' width=\'\' style=\'border-color:white\'></div>";
                data80 = myObjectGetPrevious.responseText;
                if (myObjectGetPrevious.readyState == 4) {
                    document.getElementById(\'Patient_List_Area\').innerHTML = data80;
                    $("#myList").DataTable({
                        "bJQueryUI": true
                    });
                }
            }; //specify name of function that will handle server response........

            myObjectGetPrevious.open(\'GET\', \'get_consultation_patient.php?Start_Date=\' + Start_Date + \'&End_Date=\' + End_Date, true);
            myObjectGetPrevious.send();
        } else {

            if (Start_Date == null || Start_Date == \'\') {
                document.getElementById("date").style = \'border: 3px solid red; text-align: center;\';
                document.getElementById("date").focus();
            } else {
                document.getElementById("date").style = \'border: 3px; text-align: center;\';
            }

            if (End_Date == null || End_Date == \'\') {
                document.getElementById("date2").style = \'border: 3px solid red; text-align: center;\';
                document.getElementById("date2").focus();
            } else {
                document.getElementById("date2").style = \'border: 3px; text-align: center;\';
            }
        }
    }

    function Get_Consulted_Patient() {
        var Date_From = document.getElementById("date").value;
        var Date_To = document.getElementById("date2").value;
        
        window.open(\'print_patient_consultation.php?Date_From=\' + Date_From + \'&Date_To=\' + Date_To,true);
    }
</script>
';

echo "

<!-- <td></td> -->
<script src='js/jquery-1.8.0.min.js'></script>
<script src='js/jquery-ui-1.8.23.custom.min.js'></script>
<link rel='stylesheet' href='js/css/ui-lightness/jquery-ui-1.8.23.custom.css'>
<script>
	$(document).ready(function() {
        $('#myList').DataTable({
            'bJQueryUI': true
        });
	});
</script>
<link rel='stylesheet' type='text/css' href='css/jquery.datetimepicker.css'/>
<link rel='stylesheet' href='media/css/jquery.dataTables.css' media='screen'>
<link rel='stylesheet' href='css/select2.min.css' media='screen'>
<link rel='stylesheet' href='css/dialog/zebra_dialog.css' media='screen'>
<link rel='stylesheet' href='media/themes/smoothness/dataTables.jqueryui.css' media='screen'>
<script src='media/js/jquery.js' type='text/javascript'></script>
<script src='media/js/jquery.dataTables.js' type='text/javascript'></script>
<script src='css/jquery.datetimepicker.js' type='text/javascript'></script>
<script src='js/select2.min.js'></script>
<script src='js/zebra_dialog.js'></script>
<script src='css/jquery-ui.js'></script>

";


include("./includes/footer.php");

?>
