<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Pharmacy'])) {
        if ($_SESSION['userinfo']['Pharmacy'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
}

if (isset($_SESSION['Pharmacy_ID'])) {
    $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
} else {
    $Sub_Department_ID = 0;
}
?>


<!-- new date function (Contain years, Months and days)--> 
<?php
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $Today_Date = mysqli_query($conn,"select now() as today");
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $age = '';
        $Start_Date = $Today . ' 00:00';
        $End_Date = $original_Date;
    }
}
?>
<!-- end of the function -->
<?php
if (isset($_GET['Section'])) {
    $Section = $_GET['Section'];
} else {
    $Section = 'yes';
}
?>


<?php
if (isset($_GET['Billing_Type'])) {
    $Billing_Type = $_GET['Billing_Type'];
    if ($Billing_Type == 'OutpatientCash') {
        $Page_Title = ' - Outpatient Cash';
    } elseif ($Billing_Type == 'OutpatientCredit') {
        $Page_Title = ' - Outpatient Credit';
    } elseif ($Billing_Type == 'InpatientCash') {
        $Page_Title = ' - Inpatient Cash';
    } elseif ($Billing_Type == 'InpatientCredit') {
        $Page_Title = ' - Inpatient Credit';
    } elseif ($Billing_Type == 'PatientFromOutside') {
        $Page_Title = ' - Patient From Outside';
    } else {
        $Page_Title = '';
    }
} else {
    $Billing_Type = '';
    $Page_Title = '';
}
?>


<script type="text/javascript">
    function gotolink() {
        var patientlist = document.getElementById('patientlist').value;
        if (patientlist == 'OUTPATIENT CASH') {
            document.location = "pharmacylist.php?Billing_Type=OutpatientCash&PharmacyList=PharmacyListThisForm";
        } else if (patientlist == 'OUTPATIENT CREDIT') {
            document.location = "pharmacylist.php?Billing_Type=OutpatientCredit&PharmacyList=PharmacyListThisForm";
        } else if (patientlist == 'INPATIENT CASH') {
            document.location = "pharmacylist.php?Billing_Type=InpatientCash&PharmacyList=PharmacyListThisForm";
        } else if (patientlist == 'INPATIENT CREDIT') {
            document.location = "pharmacylist.php?Billing_Type=InpatientCredit&PharmacyList=PharmacyListThisForm";
        } else if (patientlist == 'PATIENTS LIST') {
            //document.location = "pharmacylist.php?Billing_Type=PatientFromOutside&PharmacyList=PharmacyListThisForm";
            document.location = "pharmacypatientlist.php?PharmacyPatientsList=PharmacyPatientsListThisForm";
        } else if (patientlist == 'DISPENSED LIST') {
            document.location = "dispensedlist.php?Billing_Type=DispensedList&PharmacyList=PharmacyListThisForm";
        } else {
            alert("Choose Type Of Patients To View");
        }
    }
</script>
<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>

<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<a href="generalledgercenter.php?GeneralLedgerCenter=GeneralLedgerCenterThisPage" class="art-button-green">BACK</a>

<?php
  $query = mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
  $dataSponsor = '';
  $dataSponsor.='<option value="All">All Sponsors</option>';

  while ($row = mysqli_fetch_array($query)) {
      $dataSponsor.= '<option value="' . $row['Sponsor_ID'] . '">' . $row['Guarantor_Name'] . '</option>';
  }

  $queryEmp = mysqli_query($conn,"SELECT Employee_ID,Employee_Name FROM tbl_employee") or die(mysqli_error($conn));
  $dataEmployee = '';
  $dataEmployee.='<option value="All">All Dispensors</option>';

  while ($row = mysqli_fetch_array($queryEmp)) {
      $dataEmployee.= '<option value="' . $row['Employee_ID'] . '">' . $row['Employee_Name'] . '</option>';
  }
?>



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


<br/><br/>
<fieldset>
    <center>
        <table width=100%>
            <tr>
                <td style='text-align: right;' width="9%"><b>Bill Type</b></td>
                <td width="9%">
                    <select name='Bill_Type' id='Bill_Type' required='required' style='text-align: center;padding:5px; width:100%;display:inline'>
                        <option selected='selected'>All</option>
                        <option>Outpatient</option>
                        <option>Inpatient</option>
                    </select>
                </td>
                <td style='text-align: right;' width="9%"><b>Payment Mode</b></td>
                <td width="10%">
                    <select name='Payment_Mode' id='Payment_Mode' required='required' style='text-align: center;padding:5px; width:100%;display:inline'>
                        <option selected='selected'>All</option>
                        <option>Cash</option>
                        <option>Credit</option>
                    </select>
                </td>
                <td style='text-align: right;'><b>Start Date</b></td>
                <td>
                    <input type='text' name='Start_Date' id='dates_From' style='text-align: center;' placeholder='Start Date' readonly='readonly' value='<?php echo $Start_Date; ?>'>
                </td>
                <td style='text-align: right;'><b>End Date</b></td>
                <td>
                    <input type='text' name='Start_Date' id='dates_To' style='text-align: center;' placeholder='End Date' readonly='readonly' value='<?php echo $End_Date; ?>'>
                </td>
                <td style='text-align: center;' width=7%>
                    <input type='button' name='Filter' id='Filter' value='FILTER' class='art-button-green' onclick='filter_list()'>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <input type='text' name='Search_Patient' id='Search_Patient' oninput='filter_list()' placeholder='Enter Patient Name' style='text-align: center;'>
                </td>
                <td style="text-align: right;"><b>Dispensor Name</b></td>
                <td>
                    <select id="employeeID" style='text-align: center;padding:5px; width:100%;display:inline'>
                        <?php echo $dataEmployee ?>
                    </select>
                </td>
                <td style="text-align: right;"><b>Sponsor Name</b></td>
                <td colspan="2">
                    <select id="sponsorID" style='text-align: center;padding:5px; width:100%;display:inline'>
                        <?php echo $dataSponsor ?>
                    </select>
                </td>
            </tr>
        </table>
    </center>
</fieldset>

<fieldset style='overflow-y: scroll; height: 410px;background-color:white;margin-top:20px;' id='Dispensed_List_Area'>
    <legend align='right' style="background-color:#006400;color:white;padding:5px;"><b>ACTUAL QUANTITIES DISPENSED</b></legend>
        <table width="100%">
        </table>
</fieldset>
<table width="100%">
    <tr>     
        <td style="text-align:right;"><input type="button" class="art-button-green" value='Preview Report' onclick="Preview_Report()"></td>
    </tr>
</table>

<script type="text/javascript">
    function Preview_Report() {
        var Start_Date = document.getElementById("dates_From").value;
        var End_Date = document.getElementById("dates_To").value;
        var Search_Patient = document.getElementById("Search_Patient").value;
        var Sponsor = document.getElementById('sponsorID').value;
        var employeeID = document.getElementById('employeeID').value;
        var Bill_Type = document.getElementById("Bill_Type").value;
        var Payment_Mode = document.getElementById("Payment_Mode").value;
        window.open('previewpharmacyactualdispensed.php?Start_Date=' + Start_Date + '&End_Date=' + End_Date + '&Search_Patient=' + Search_Patient + '&Sponsor=' + Sponsor + '&employeeID=' + employeeID + '&Bill_Type=' + Bill_Type + '&Payment_Mode=' + Payment_Mode, '_blank');
    }
</script>

<script type="text/javascript">
    $('#dates_From').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#dates_From').datetimepicker({value: '', step: 01});
    $('#dates_To').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#dates_To').datetimepicker({value: '', step: 01});

</script>
<script type="text/javascript">
    function filter_list() {
        var Start_Date = document.getElementById("dates_From").value;
        var End_Date = document.getElementById("dates_To").value;
        var Search_Patient = document.getElementById("Search_Patient").value;
        var Sponsor = document.getElementById('sponsorID').value;
        var employeeID = document.getElementById('employeeID').value;
        var Bill_Type = document.getElementById("Bill_Type").value;
        var Payment_Mode = document.getElementById("Payment_Mode").value;
        document.getElementById('Dispensed_List_Area').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

        if (window.XMLHttpRequest) {
            myObjectFilterList = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectFilterList = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectFilterList.overrideMimeType('text/xml');
        }

        myObjectFilterList.onreadystatechange = function () {
            data100 = myObjectFilterList.responseText;
            if (myObjectFilterList.readyState == 4 && myObjectFilterList.status == 200) {
                document.getElementById('Dispensed_List_Area').innerHTML = data100;
            }
        }; //specify name of function that will handle server response........

        //$("#previewDispensedList").attr('href', 'previewDispensedList.php?Start_Date=' + Start_Date + '&End_Date=' + End_Date + '&Search_Patient=' + Search_Patient + '&Sponsor=' + Sponsor + '&employeeID=' + employeeID+'&Bill_Type='+Bill_Type+'&Payment_Mode='+Payment_Mode);
        myObjectFilterList.open('GET', 'Pharmacy_Actual_Dispensed.php?Start_Date=' + Start_Date + '&End_Date=' + End_Date + '&Search_Patient=' + Search_Patient + '&Sponsor=' + Sponsor + '&employeeID=' + employeeID + '&Bill_Type=' + Bill_Type + '&Payment_Mode=' + Payment_Mode, true);

        myObjectFilterList.send();
    }
</script>
<script>

    $(document).ready(function () {
        $('select').select2();
    })
</script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>
<?php
include("./includes/footer.php");
?>