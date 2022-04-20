<?php include("./includes/header.php");
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
};
echo '
<script type=\'text/javascript\'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>
';
if (isset($_GET['Section'])) {
    $Section = $_GET['Section'];
} else {
    $Section = '';
}
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Reception_Works'] == 'yes') {;
        echo '        <a href=\'./receptionReports.php?Section=';
        echo $Section;;
        echo '&ReceptionReportThisPage\' class=\'art-button\'>
            BACK
        </a>
    ';
    }
};
echo '
';
$Today_Date = mysqli_query($conn, "select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d h:i:s", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
};
echo '

<br/><br/>
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;

    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style> 

<center>
<fieldset style="height: 100px; background-color:eeeeee">
    <legend style="background-color:#006400;color:white;padding:5px;" align="right"><b>LIST OF CHECKED IN PF3 PATIENTS</b></legend>
    <table width=100%>
        <tr>
            <td width=10% style="text-align: right;">
                &nbsp;&nbsp;&nbsp;<b>Pf3 Reason</b>
            </td>
            <td width=10%>
                <select style="width: 100%" name="Reason_ID" id="Reason_ID">
                    <option value="0" selected="selected">All</option>
                    ';
$select = mysqli_query($conn, "select * from tbl_pf3_reasons") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if ($num > 0) {
    while ($row = mysqli_fetch_array($select)) {;
        echo '                            <option value="';
        echo $row['Reason_ID'];;
        echo '">';
        echo $row['Reason_Name'];;
        echo '</option>
                            ';
    }
};
echo '                </select>
            </td>
            <td width=10% style="text-align: right;"><b>Gender</b></td>
            <td width=10%>
                <select style="width: 100%" id="gender">
                   <option value="All" selected="selected">All</option>
                   <option value="Female">Female</option>
                   <option value="Male">Male</option>
                </select>
            </td>
            <td width=5% style=\'text-align: right;\'><b>From</b></td>
            <td width=25%>
                <input type=\'text\' name=\'Date_From\' id=\'Date_From\' placeholder=\'Start Date\' style=\'text-align: center;\' autocomplete=\'off\' value=\'';
echo $Today;;
echo '\'>
            </td>
            <td width=5% style=\'text-align: right;\'><b>To</b></td>
            <td width=25%>
                <input type=\'text\' name=\'Date_To\' id=\'Date_To\' placeholder=\'End Date\' style=\'text-align: center;\' autocomplete=\'off\' value=\'';
echo $Today;;
echo '\'>
            </td>
            <td style=\'text-align: center;\' width=10%><input name=\'Filter\' type=\'button\' value=\'FILTER\' class=\'art-button\' onclick=\'Get_Filtered_Patients()\'></td>
        </tr>
        </table>
    </fieldset>
</center>
<fieldset style=\'overflow-y: scroll; height: 400px; background-color:#eeeeee\' id=\'Patients_Fieldset_List\'>
    <table id="myList" width=100%>
        <thead>
            <tr>
                <td width=2%><b>SN</b></td>
                <td><b>PATIENT NAME</b></td>
                <td width=8%><b>PATIENT#</b></td>
                <td width=5%><b>GENDER</b></td>
                <td width=5%><b>AGE</b></td>
                <td width=10%><b>SPONSOR</b></td>
                <td width=10%><b>PHONE#</b></td>
                <td><b>REASON</b></td>
                <td width=15%><b>CHECKED IN DATE</b></td>
                <td width=18%><b>EMPLOYEE NAME</b></td>
            </tr>
        </thead>
        ';
$temp = 0;
$Destination = '';
$Today = date('Y-m-d');
$select = mysqli_query($conn, "select pr.Patient_Name,pr.Gender,pr.Date_Of_Birth,pr.Registration_ID,Guarantor_Name, pr.Phone_Number, 
								p.Reason_Name, c.Visit_Date, emp.Employee_Name from
								tbl_check_in c, tbl_employee emp, tbl_patient_registration pr, 
								tbl_pf3_reasons p, tbl_sponsor sp, tbl_pf3_patients pp where 
								c.Check_In_ID = pp.Check_In_ID and
								pp.Registration_ID = pr.Registration_ID and
								c.Employee_ID = emp.Employee_ID and
								pp.Reason_ID = p.Reason_ID and
								sp.Sponsor_ID = pr.Sponsor_ID and
								c.Check_In_Date_And_Time between '$Today' and '$Today'
								") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if ($num > 0) {
    while ($row = mysqli_fetch_array($select)) {
        $Date_Of_Birth = $row['Date_Of_Birth'];
        $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
        if ($age == 0) {
            $date1 = new DateTime($Today);
            $date2 = new DateTime($Date_Of_Birth);
            $diff = $date1->diff($date2);
            $age = $diff->m . " Months";
        }
        if ($age == 0) {
            $date1 = new DateTime($Today);
            $date2 = new DateTime($Date_Of_Birth);
            $diff = $date1->diff($date2);
            $age = $diff->d . " Days";
        };
        echo "<tr>
				    <td>" . ++$temp . "</td>
				    <td>" . $row['Patient_Name'] . "</td>
				    <td>" . $row['Registration_ID'] . "</td>
                                    <td>" . $row['Gender'] . "</td>
                                    <td>" . $age . "</td>
				    <td>" . $row['Guarantor_Name'] . "</td>
				    <td>" . $row['Phone_Number'] . "</td>
				    <td>" . $row['Reason_Name'] . "</td>
				    <td>" . $row['Check_In_Date_And_Time'] . "</td>
				    <td>" . $row['Employee_Name'] . "</td>
				</tr>";
    }
};
echo '    </table>
</fieldset>


<script>
    function Get_Filtered_Patients() {

        var Date_From = document.getElementById("Date_From").value;
        var Date_To = document.getElementById("Date_To").value;
        var Reason_ID = document.getElementById("Reason_ID").value;
        var gender=document.getElementById("gender").value;

        if (window.XMLHttpRequest) {
            My_Object_Filter_Patient = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            My_Object_Filter_Patient = new ActiveXObject(\'Micrsoft.XMLHTTP\');
            My_Object_Filter_Patient.overrideMimeType(\'text/xml\');
        }

        My_Object_Filter_Patient.onreadystatechange = function () {
            document.getElementById("Patients_Fieldset_List").innerHTML = "<div align=\'center\' style=\'\' id=\'progressStatus\'><img src=\'./images/ajax-loader_1.gif\' width=\'\' style=\'border-color:white\'></div>";
                
            data6 = My_Object_Filter_Patient.responseText;
            if (My_Object_Filter_Patient.readyState == 4) {
                document.getElementById(\'Patients_Fieldset_List\').innerHTML = data6;
                $("#myList").DataTable({
                    "bJQueryUI": true
                });
            }
        }; //specify name of function that will handle server response........

        My_Object_Filter_Patient.open(\'GET\', \'Reception_Get_Pf3_Patients_List.php?Date_From=\' + Date_From + \'&Date_To=\' + Date_To + \'&Reason_ID=\'+Reason_ID+\'&gender=\'+gender, true);
        My_Object_Filter_Patient.send();
    }
</script>


<script>
    function print() {

        var Date_From = document.getElementById("Date_From").value;
        var Date_To = document.getElementById("Date_To").value;
        var Reason_ID = document.getElementById("Reason_ID").value;
        var gender=document.getElementById("gender").value;
        
        window.open(\'print_pf3_patient_list.php?Date_From=\' + Date_From + \'&Date_To=\' + Date_To + \'&Reason_ID=\'+Reason_ID+\'&gender=\'+gender,true);
    }
    function Get_Filtered_Patients_Filter() {

        var Date_From = document.getElementById("Date_From").value;
        var Date_To = document.getElementById("Date_To").value;
        var Search_Value = document.getElementById("Search_Value").value;
        var gender=document.getElementById("gender").value;

        if (window.XMLHttpRequest) {
            My_Object_Filter_Patient = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            My_Object_Filter_Patient = new ActiveXObject(\'Micrsoft.XMLHTTP\');
            My_Object_Filter_Patient.overrideMimeType(\'text/xml\');
        }

        My_Object_Filter_Patient.onreadystatechange = function () {
            data6 = My_Object_Filter_Patient.responseText;
            if (My_Object_Filter_Patient.readyState == 4) {
                document.getElementById(\'Patients_Fieldset_List\').innerHTML = data6;
            }
        }; //specify name of function that will handle server response........

        My_Object_Filter_Patient.open(\'GET\', \'Revenue_Get_Checked_Patients_List.php?Date_From=\' + Date_From + \'&Date_To=\' + Date_To + \'&Search_Value=\' + Search_Value, true);
        My_Object_Filter_Patient.send();
    }
</script>
<script type="text/javascript">
    $(document).ready(function () {

        $(\'#Date_From\').datetimepicker({
            dayOfWeekStart: 1,
            lang: \'en\',
            dateFormat: "Y-m-d",
            //startDate:    \'now\'
        });
        $(\'#Date_From\').datetimepicker({value: \'\', step: 30});
        $(\'#Date_To\').datetimepicker({
            dayOfWeekStart: 1,
            lang: \'en\',
            dateFormat: "Y-m-d",
            //startDate:\'now\'
        });
        $(\'#Date_To\').datetimepicker({value: \'\', step: 30});
    });
    
    
</script>
<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>


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
        $('#Reason_ID').select2();
        $('#gender').select2();
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
