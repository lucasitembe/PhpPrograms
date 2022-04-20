<style>
    .linkstyle{
        color:#3EB1D3;
    }

    .linkstyle:hover{
        cursor:pointer;
    }
</style>
<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Management_Works'])) {
        if ($_SESSION['userinfo']['Management_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$Date_From = ''; //@$_POST['Date_From'];
$Date_To = ''; //@$_POST['Date_To'];
$Employee_ID = '';

if (!isset($_GET['Date_From'])) {
    $Date_From = date('Y-m-d H:m');
} else {
    $Date_From = $_GET['Date_From'];
}
if (!isset($_GET['Date_To'])) {
    $Date_To = date('Y-m-d H:m');
    ;
} else {
    $Date_To = $_GET['Date_To'];
}if (!isset($_GET['Employee_ID'])) {
    $Employee_ID = 0;
} else {
    $Employee_ID = $_GET['Employee_ID'];
}

if (!isset($_GET['Sponsor_ID'])) {
    $Sponsor = '';
} else {
    $Sponsor = $_GET['Sponsor_ID'];
}

$today = date('Y-m-d');
if (!empty($Sponsor) && $Sponsor != 'All') {
     $filter2 ="  AND pc.Sponsor_ID=$Sponsor";
}
else {
    $filter2 = "";
}
//run the query to select all data from the database according to the branch id
//  $select_doctor_query="SELECT  emp.Employee_ID,emp.Employee_Name,emp.Employee_Type FROM tbl_employee emp WHERE Employee_Type='Doctor' ORDER BY Employee_Name ASC";
//$select_doctor_result = mysqli_query($conn,$select_doctor_query);
//$checkIfHasFinalDiagnosis=mysqli_query($conn,"
// SELECT * FROM tbl_disease_consultation dc RIGHT JOIN tbl_consultation co ON co.consultation_ID=dc.consultation_ID WHERE dc.consultation_ID='$consultation_ID' AND co.Registration_ID='$Registration_ID' AND  co.employee_ID='$employeeID'
//") or die(mysql_error);

$employeeID = $employee_ID = $Employee_ID; //exit;
$EmployeeName = strtoupper(mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$employeeID'"))['Employee_Name']);

$filter = "  wr.Ward_Round_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' AND wr.Employee_ID='$employeeID'  AND wr.Process_Status='served' ";

if (!empty($Sponsor) && $Sponsor != 'All') {
    $filter .="  AND wr.Sponsor_ID=$Sponsor";
}
$sql = "SELECT COUNT(rg.Registration_ID) AS total_patient FROM tbl_patient_registration as rg, tbl_payment_cache as pc WHERE pc.Registration_ID = rg.Registration_ID AND pc.Employee_ID = $Employee_ID $filter2 AND (pc.Billing_Type='Inpatient Credit'  or pc.Billing_Type='Inpatient Cash') AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'";


$consultationPat = mysqli_query($conn,$sql) or die(mysqli_error($conn));
$totalAllPat = "TOTAL PATIENT <span class='dates'>" . mysqli_fetch_assoc($consultationPat)['total_patient']. '</span>';
?>
<a target="_blank" href='./printdoctorsperformancesummaryinpatient.php?<?php echo $_SERVER['QUERY_STRING'] ?>' class='art-button-green'>
    PRINT
</a>
<?php
echo "<a href='./doctorsPerformanceSummaryFilterInpatient.php?" . $_SERVER['QUERY_STRING'] . "' class='art-button-green'>
        BACK
    </a>
 <br/><br/>";
?>
<style>
    select{
        padding:5px;
    }

    .dates{
        color:#cccc00;
    }

    .daterange{
        background-color: rgb(3, 125, 176);
        color: white;
        display: block;
        width: 99.2%;
        padding: 4px;
        font-family: times;
        font-size: large;
        font-weight: bold;
    }
</style>
<fieldset style='overflow-y:scroll; height:500px'>
    <center>

        <legend  align="right" style="background-color:#006400;color:white;padding:5px;">
            <br/>
            <form action='doctorsPerformanceSummaryFilterInpatient.php' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data"></legend>

        <table width='75%' border='0' id='actionsTable'>
            <tr>

                <td style="text-align: center"><b>From</b></td>
                <td style="text-align: center">
                    <input type='text' name='Date_From' id='date_From' required='required'>
                </td>
                <td style="text-align: center">To</td>
                <td style="text-align: center"><input type='text' name='Date_To' id='date_To' required='required'></td>
                <td style="text-align: center">
                    <select name='Sponsor_ID' id='Sponsor_ID' onchange="filterPatient()" style='text-align: center;width:100%;display:inline'>
                        <option value="All">All Sponsors</option>
                        <?php
                        $qr = "SELECT * FROM tbl_sponsor";
                        $sponsor_results = mysqli_query($conn,$qr);
                        while ($sponsor_rows = mysqli_fetch_assoc($sponsor_results)) {
                            ?>
                            <option value='<?php echo $sponsor_rows['Sponsor_ID']; ?>'><?php echo $sponsor_rows['Guarantor_Name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
                <td style='text-align: center;'>
                    <input type='submit' name='Print_Filter'  class='art-button-green' value='FILTER'>
                </td>
            </tr>
        </table>
        </form>
    </center>
    <br>
    <legend align="center" style="background-color:#037DB0;color: white;padding: 5px;text-align: center">
        <b><?php echo $EmployeeName; ?> PERFORMANCE ROUND REPORT </b><b style="color:#e0d8d8;"><?php echo date('j F, Y H:i:s', strtotime($Date_From)) ?> </b><b>TO</b> <b style="color: #e0d8d8;"><?php echo date('j F, Y H:i:s', strtotime($Date_To)) ?></b>
         <br/><b><?php echo $totalAllPat; ?></b>
    </legend>

    <div >
        <?php
        $avoidDoctorNameDuplicate = 0;
        $Employee_Name_Cur = '';

        $dataRange = returnBetweenDates($Date_From, $Date_To);
        $totalPPP = 0;

          $get_patient = mysqli_query($conn,"SELECT DATE(pc.Payment_Date_And_Time) AS this_data, COUNT(rg.Registration_ID) AS thi_counter, rg.Patient_Name, rg.Phone_Number FROM tbl_patient_registration as rg, tbl_payment_cache as pc WHERE pc.Registration_ID = rg.Registration_ID $filter2 AND pc.Employee_ID = $Employee_ID AND (pc.Billing_Type='Inpatient Credit'  or pc.Billing_Type='Inpatient Cash') AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' GROUP BY DATE(pc.Payment_Date_And_Time)")  or die(mysqli_error($conn));

          // foreach ($dataRange as $value) {
          //   $thisDate = date('d, M y', strtotime($value)) . '';
//             $consultationDateRange = mysqli_query($conn,"SELECT wr.Registration_ID,pr.Patient_Name,pr.Phone_Number,wr.employee_ID,e.Employee_Name,wr.consultation_ID FROM tbl_ward_round wr JOIN tbl_employee e ON wr.Employee_ID=e.Employee_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=wr.Registration_ID  WHERE $filter   AND  DATE(wr.Ward_Round_Date_And_Time)='$value' ORDER BY wr.consultation_ID") or die(mysqli_error($conn));
//
//
//             $get_patient = mysqli_query($conn,"SELECT rg.Patient_Name, rg.Phone_Number FROM tbl_patient_registration as rg, tbl_payment_cache as pc WHERE pc.Registration_ID = rg.Registration_ID AND pc.Employee_ID = $Employee_ID AND (pc.Billing_Type='Inpatient Credit'  or pc.Billing_Type='Inpatient Cash')")  or die(mysqli_error($conn));
//
//
// //retrieve consultations for the employee
//             $empSN = 1;
//
        while($count_date = mysqli_fetch_assoc(  $get_patient)){
                $date = $count_date ['this_data'];
                $count = $count_date ['thi_counter'];

                echo "<div class='daterange'>$date<span style='float:right'></span></div>";

                echo '<center><table width =100% border="1" class="display" id="doctorsperformancetbl">';
                echo "<thead><tr>
			            <th width=3% style='text-align:left'>SN</th>
			  	        <th style='text-align:left'>PATIENT NAME</th>
			            <th style='text-align:left'>LAB</th>
				          <th style='text-align:left'>RADIOLOGY</th>
				          <th style='text-align:left'>PHARMACY</th>
                  <th style='text-align:left'>PROCEDURE</th>
                  <th style='text-align:left'>PATIENT PHONE</th>
			            </tr></thead>";

                  //die("SELECT rg.Patient_Name, rg.Phone_Number FROM tbl_patient_registration as rg, tbl_payment_cache as pc WHERE pc.Registration_ID = rg.Registration_ID AND pc.Employee_ID = $Employee_ID AND (pc.Billing_Type='Inpatient Credit'  or pc.Billing_Type='Inpatient Cash') AND  DATE(pc.Payment_Date_And_Time) = '$date'");
                  $get_patient_details = mysqli_query($conn,"SELECT rg.Registration_ID, rg.Patient_Name, rg.Phone_Number FROM tbl_patient_registration as rg, tbl_payment_cache as pc WHERE pc.Registration_ID = rg.Registration_ID $filter2 AND pc.Employee_ID = $Employee_ID AND (pc.Billing_Type='Inpatient Credit'  or pc.Billing_Type='Inpatient Cash') AND  DATE(pc.Payment_Date_And_Time) = '$date' GROUP BY pc.Registration_ID")  or die(mysqli_error($conn));
                  //die("SELECT rg.Registration_ID, rg.Patient_Name, rg.Phone_Number FROM tbl_patient_registration as rg, tbl_payment_cache as pc WHERE pc.Registration_ID = rg.Registration_ID $filter2 AND pc.Employee_ID = $Employee_ID AND (pc.Billing_Type='Inpatient Credit'  or pc.Billing_Type='Inpatient Cash') AND  DATE(pc.Payment_Date_And_Time) = '$date' GROUP BY pc.Registration_ID");

              //   $sql = "SELECT  pr.Patient_Name,pr.Phone_Number,c.Registration_ID,c.consultation_ID,ppl.Patient_Payment_Item_List_ID,ppl.Patient_Payment_ID ,e.Employee_Name,ch.employee_ID FROM tbl_consultation_history ch LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=c.Registration_ID JOIN tbl_patient_payment_item_list ppl ON c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID WHERE $filter  AND  DATE(ch.cons_hist_Date)='$value'";
              //
              // $consultations = mysqli_query($conn,"SELECT wr.Registration_ID,pr.Patient_Name,pr.Phone_Number,wr.employee_ID,e.Employee_Name,wr.consultation_ID FROM tbl_ward_round wr JOIN tbl_employee e ON wr.Employee_ID=e.Employee_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=wr.Registration_ID  WHERE $filter   AND  DATE(wr.Ward_Round_Date_And_Time)='$value' ORDER BY wr.consultation_ID DESC") or die(mysqli_error($conn));
              //
              // echo "SELECT pr.Patient_Name,pr.Phone_Number,c.Registration_ID,c.consultation_ID,ppl.Patient_Payment_Item_List_ID,ppl.Patient_Payment_ID ,e.Employee_Name,ch.employee_ID FROM tbl_consultation_history ch LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=c.Registration_ID JOIN tbl_patient_payment_item_list ppl ON c.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID WHERE ch.cons_hist_Date BETWEEN '$Date_From' AND '$Date_To' AND ch.employee_ID='$employee_ID'";
                  $empSN = 1;

                    while ($row = mysqli_fetch_array($get_patient_details)) {

                        //$Registration_ID = $row['Registration_ID'];
                        $Phone_Number = $row['Phone_Number'];
                        $patient_name = $row['Patient_Name'];
                        $Registration_ID = $row['Registration_ID'];

                        // $employeeName = $row['Employee_Name'];
                        //
                        // $Employee_Name_Cur = $row['Employee_Name'];
                        //
                        // $consultation_ID = $row['consultation_ID'];



    //                     $finalDiagnosis = "<span onclick='Show_Patient_File(" . $Registration_ID . "," . $consultation_ID . ")' class='linkstyle' >No</span>";
    //                     // $proviDiagnosis="<span onclick='Show_Patient_File(".$Registration_ID.")' class='linkstyle' >No</span>";
    //
    //                     $checkIfHasFinalDiagnosis = mysqli_query($conn,"
		//                     SELECT wr.Round_ID FROM tbl_ward_round_disease wrd INNER JOIN tbl_ward_round wr ON wr.Round_ID=wrd.Round_ID WHERE wr.consultation_ID='$consultation_ID' AND wr.Registration_ID='$Registration_ID' AND  wr.employee_ID='$employeeID' AND wrd.diagnosis_type='diagnosis'   AND  DATE(wr.Ward_Round_Date_And_Time)='$value'
		//                     ") or die(mysql_error);
    //
    //                     if (mysqli_num_rows($checkIfHasFinalDiagnosis) > 0) {
    //                         $finalDiagnosis = "<span onclick='Show_Patient_File(" . $Registration_ID . "," . $consultation_ID . ")' class='linkstyle' >Yes</span>";
    //                     }
    //
                          $select_checking_type = mysqli_query($conn,"SELECT
                          (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Laboratory' AND pc.Registration_ID = $Registration_ID AND pc.Employee_ID=$employeeID AND  (pc.Billing_Type='Inpatient Credit'  or pc.Billing_Type='Inpatient Cash') $filter2 AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To') as Laboratory,

                          (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Radiology' AND pc.Registration_ID = $Registration_ID AND pc.Employee_ID=$employeeID AND (pc.Billing_Type='Inpatient Credit'  or pc.Billing_Type='Inpatient Cash') $filter2 AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To') as Radiology,

                          (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Pharmacy' AND pc.Registration_ID = $Registration_ID AND pc.Employee_ID=$employeeID AND (pc.Billing_Type='Inpatient Credit'  or pc.Billing_Type='Inpatient Cash') $filter2 AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To') as Pharmacy,

                          (SELECT COUNT(Payment_Item_Cache_List_ID) FROM tbl_item_list_cache ilc RIGHT JOIN tbl_payment_cache pc  ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID WHERE ilc.Check_In_Type='Procedure' AND pc.Registration_ID = $Registration_ID AND pc.Employee_ID=$employeeID AND (pc.Billing_Type='Inpatient Credit'  or pc.Billing_Type='Inpatient Cash') $filter2 AND  pc.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To') as Procedur
                          ") or die(mysqli_error($conn));

                        $rowChkType = mysqli_fetch_assoc($select_checking_type);
                        $Laboratory = $rowChkType['Laboratory'];
                        $Radiology = $rowChkType['Radiology'];
                        $Pharmacy = $rowChkType['Pharmacy'];
                        $Procedur = $rowChkType['Procedur'];
                        //if( $avoidDoctorNameDuplicate==1){$employeeName='';}
                        echo "<tr><td>" . ($empSN++) . "</td>";
                        //echo "<td style='text-align:left'>".$employeeName."</td>";
                        echo "<td style='text-align:left'><span onclick='Show_Patient_File(" . $Registration_ID . "," . $consultation_ID . ")' class='linkstyle' >" . $patient_name . "</span></td>";
                        echo "<td style='text-align:center'><span onclick='Show_Patient_File(" . $Registration_ID . "," . $consultation_ID . ")' class='linkstyle' >" . ($Laboratory > 0 ? 'Yes' : 'No') . " (".$Laboratory.")</span></td>";

                        echo "<td style='text-align:center'><span onclick='Show_Patient_File(" . $Registration_ID . "," . $consultation_ID . ")' class='linkstyle' >" . ($Radiology > 0 ? 'Yes' : 'No') . " (".$Radiology.")</span></td>";

                        echo "<td style='text-align:center'><span onclick='Show_Patient_File(" . $Registration_ID . "," . $consultation_ID . ")' class='linkstyle' >" . ($Pharmacy > 0 ? 'Yes' : 'No') . " (".$Pharmacy.")</span></td>";

                        echo "<td style='text-align:center'><span onclick='Show_Patient_File(" . $Registration_ID . "," . $consultation_ID . ")' class='linkstyle' >" . ($Procedur > 0 ? 'Yes' : 'No') . "( ".$Procedur.")</span></td>";

                        echo "<td style='text-align:center'><span onclick='Show_Patient_File(" . $Registration_ID . "," . $consultation_ID . ")' class='linkstyle' >" . ($Phone_Number) . "</span></td></tr>";
                    }
            echo '</table></center><br/>';
        }

        function returnBetweenDates($startDate, $endDate) {
            $startStamp = strtotime($startDate);
            $endStamp = strtotime($endDate);

            if ($endStamp > $startStamp) {
                while ($endStamp >= $startStamp) {

                    $dateArr[] = date('Y-m-d', $startStamp);

                    $startStamp = strtotime(' +1 day ', $startStamp);
                }
                return $dateArr;
            } else {
                return $startDate;
            }
        }
        ?>

    </div>
</fieldset>

<?php
include("./includes/footer.php");
?>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
                        $('#date_From').datetimepicker({
                            dayOfWeekStart: 1,
                            lang: 'en',
                            startDate: 'now'
                        });
                        $('#date_From').datetimepicker({value: '', step: 30});
                        $('#date_To').datetimepicker({
                            dayOfWeekStart: 1,
                            lang: 'en',
                            startDate: 'now'
                        });
                        $('#date_To').datetimepicker({value: '', step: 30});
</script>

<script>
    $('.display').dataTable({
        "bJQueryUI": true,
    });
</script>
<script>
    function filterEmployeePatients() {
        //alert('hallow');
        var doctorID, date_From, date_To;

        doctorID = document.getElementById('doctorID').value;
        date_From = document.getElementById('date_From').value;
        date_To = document.getElementById('date_To').value;
        //ajax requests

        var mypostrequest = new ajaxRequest();
        mypostrequest.onreadystatechange = function () {
            if (mypostrequest.readyState == 4) {
                if (mypostrequest.status == 200 || window.location.href.indexOf("http") == -1) {
                    document.getElementById("doctorsperformancetbl").innerHTML = mypostrequest.responseText;

                }
                else {
                    alert("An error has occured making the request");
                }
            }
        }
        var parameters = "filterDoctorsPatient=true&doctorID=" + doctorID + "&date_From=" + date_From + "&date_To=" + date_To;
        mypostrequest.open("POST", "filterPerformanceDoctorPatient.php", true);
        mypostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        mypostrequest.send(parameters);
        //alert(parameters);


        //alert(Price +" "+Quantity+" "+Discount+" "+ppil);
    }
</script>
<script>
    function ajaxRequest() {
        var activexmodes = ["Msxml2.XMLHTTP", "Microsoft.XMLHTTP"]; //activeX versions to check for in IE
        if (window.ActiveXObject) { //Test for support for ActiveXObject in IE first (as XMLHttpRequest in IE7 is broken)
            for (var i = 0; i < activexmodes.length; i++) {
                try {
                    return new ActiveXObject(activexmodes[i]);
                }
                catch (e) {
                    //suppress error
                }
            }
        }
        else if (window.XMLHttpRequest) // if Mozilla, Safari etc
            return new XMLHttpRequest();
        else
            return false;
    }
</script>
<script>
    function Show_Patient_File(Registration_ID, consultation_ID) {
// var printWindow= window.open("http://www.w3schools.com", "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=400, height=400");
        var winClose = popupwindow('Patient_Record_Review_General.php?Section=Doctor&Registration_ID=' + Registration_ID + '&consultation_ID=' + consultation_ID + '#inpatient', 'Patient File', 1300, 700);
        //winClose.close();
        //openPrintWindow('http://www.google.com', 'windowTitle', 'width=820,height=600');

    }

    function popupwindow(url, title, w, h) {
        var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);//'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

        return mypopupWindow;
    }

</script>
