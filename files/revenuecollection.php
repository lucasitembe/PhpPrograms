<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['General_Ledger'])) {
        if ($_SESSION['userinfo']['General_Ledger'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_GET['Start_Date'])) {
    $Start_Date = mysqli_real_escape_string($conn,$_GET['Start_Date']);
} else {
    $Start_Date = '';
}

if (isset($_GET['End_Date'])) {
    $End_Date = mysqli_real_escape_string($conn,$_GET['End_Date']);
} else {
    $End_Date = '';
}

if (isset($_GET['Currency_ID'])) {
    $Currency_ID = mysqli_real_escape_string($conn,$_GET['Currency_ID']);
} else {
    $Currency_ID = '';
}
?>
<a href="generalledgercenter.php" style=""><button type="button" class="art-button-green" style="color:#FFFFFF!important;height:27px!important">BACK</button></a>
<style>
    table,tr,td{
        border-collapse:collapse !important;

    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<br/><br/><br/>
<fieldset style="background-color:white;font-size:larger">
    <legend align='center'>REVENUE COLLECTION SUMMARY REPORT</legend>
    <center>
        <table width = '80%'>
            <tr>
                <td style="text-align: right" width="20%">Start Date</td>
                <td width="35%">
                    <input type="text" name="Start_Date" id="Start_Date" value="<?php echo $Start_Date; ?>" style="text-align: center;" placeholder = "~~~ ~~~ Start Date ~~~ ~~~">
                </td> 
                <td style="text-align: right" width="20%">End Date</td>
                <td>
                    <input type="text" name="End_Date" id="End_Date" value="<?php echo $End_Date; ?>" style="text-align: center;" placeholder = "~~~ ~~~ End Date ~~~ ~~~">
                </td>
            </tr>
            <tr>
                <td style="text-align: right">Sponsor Name</td>
                <td>
                    <input type="hidden" name="Sponsor_ID" id='Sponsor_ID' value="0">
                    <input type="text" name="Sponsor_Name" id="Sponsor_Name" value="All" readonly="readonly">
                </td>
                <td colspan="2" style="text-align: center">
                    <button type="button" style="width:86%;color:#FFFFFF!important;height:27px!important"class="art-button-green" id='selectSponsor' onclick="Open_Sponsors_Dialog()">SELECT SPONSOR</button>
                </td>
            </tr>
            <tr>
                <td style="text-align: right">Employee Name</td>
                <td>
                    <input type="hidden" name="Employee_ID" id='Employee_ID' value="0">
                    <input type="text" name="Employee_Name" id="Employee_Name" value="All" readonly="readonly">
                </td>
                <td colspan="2" style="text-align: center">
                    <button type="button" style="width:86%;color:#FFFFFF!important;height:27px!important"class="art-button-green" id='selectDataEntry' onclick="Open_Employee_Dialog()">SELECT EMPLOYEE</button>
                </td>
            </tr>
            <tr>    
                <td style="text-align: right">Customer Type</td>
                <td>
                    <select name="Patient_Type" id="Patient_Type" style="padding:5px;margin-bottom: 5px; font-size: 17px;width:100%;text-align:center" onchange="Update_Ward_List()">
                        <option value="All">OUTPATIENT AND INPATIENT</option>
                        <option value="Inpatient">INPATIENT</option>
                        <option value="Outpatient">OUTPATIENT</option>
                    </select>
                </td>
                <td style="text-align: right;">Ward Name</td>
                <td>
                    <select id="Hospital_Ward_ID" name="Hospital_Ward_ID" style="padding:5px;margin-bottom: 5px; font-size: 17px;width:100%;text-align:left">
                        <option selected="selected" value="none">Not Applicable</option>
                    </select>
                </td>
            </tr>
            <tr>    
                <td style="text-align: right">Clinic</td>
                <td>
                    <select name="clinic" id="clinic" style="padding:5px;margin-bottom: 5px; font-size: 17px;width:100%;text-align:center" onchange="Update_Ward_List()">
                        <option value="ALL">ALL</option>
                        <?php
                        $clinics = "select * from tbl_clinic where Clinic_Status = 'Available'";
                        $clinic = mysqli_query($conn,$clinics);
                        ?> 
                        <?php
                        while ($row = mysqli_fetch_array($clinic)) {
                            ?>
                            <option value="<?= $row['Clinic_ID'] ?>"><?php echo $row['Clinic_Name']; ?></option>
                        <?php }
                        ?>
                    </select>
                </td>
                <td style="text-align: right;"><!--Ward Name--></td>
                <td>
<!--                    <select id="Hospital_Ward_ID" name="Hospital_Ward_ID" style="padding:5px;margin-bottom: 5px; font-size: 17px;width:100%;text-align:left">
                        <option selected="selected" value="none">Not Applicable</option>
                    </select>-->
                </td>
            </tr>          
            <tr>
                <td colspan="4" style="text-align: right">
                    <input type="button" name="Cash_And_Credit_Button" id="Cash_And_Credit_Button" value="CASH AND CREDIT" class="art-button-green" onclick="Filter_Cash_And_Credit('CashCredit')">
                    <input type="button" name="Cash_Button" id="Cash_Button" value="CASH" class="art-button-green"  onclick="Filter_Cash_And_Credit('Cash')">
                    <input type="button" name="Credit_Button" id="Credit_Button" value="CREDIT" class="art-button-green"  onclick="Filter_Cash_And_Credit('Credit')">
                    <input type="button" name="Cancelled_Button" id="Cancelled_Button" value="CANCELLED" class="art-button-green"  onclick="Filter_Cash_And_Credit('Cancelled')">
                </td> 
            </tr>
        </table>
    </center>
</fieldset>

<div id="Sponsor_Dialog">

</div>

<div id="Employee_Dialog">

</div>
<?php
$Val = '';
$select = mysqli_query($conn,"select Hospital_Ward_ID, Hospital_Ward_Name from tbl_hospital_ward order by Hospital_Ward_Name") or die(mysqli_error($conn));
$nm = mysqli_num_rows($select);
if ($nm > 0) {
    $Val .= '<option selected="selected" value="0">ALL WARDS</option>';
    while ($dt = mysqli_fetch_array($select)) {
        $Val .= '<option value="' . $dt['Hospital_Ward_ID'] . '">' . strtoupper($dt['Hospital_Ward_Name']) . '</option>';
    }
} else {
    $Val .= '<option selected="selected" value="0">ALL WARDS</option>';
}
?>


<script type="text/javascript">
    function Update_Ward_List() {
        var Patient_Type = document.getElementById("Patient_Type").value;
        var Val = '<?php echo $Val; ?>';
        if (Patient_Type == 'Inpatient') {
            document.getElementById("Hospital_Ward_ID").innerHTML = Val;
        } else {
            document.getElementById("Hospital_Ward_ID").innerHTML = '<option selected="selected" value="none">Not Applicable</option>';
        }
    }
</script>

<script type="text/javascript">
    function Filter_Cash_And_Credit(Section) {
        var Start_Date = document.getElementById("Start_Date").value;
        var End_Date = document.getElementById("End_Date").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Employee_ID = document.getElementById("Employee_ID").value;
        var Patient_Type = document.getElementById("Patient_Type").value;
        var Hospital_Ward_ID = document.getElementById("Hospital_Ward_ID").value;
        var clinic = document.getElementById("clinic").value;

        if (Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '' && Sponsor_ID != null && Sponsor_ID != '' && Employee_ID != null && Employee_ID != '' && clinic != '') {
            document.getElementById("Start_Date").style = 'border: 1px solid black; text-align: center;';
            document.getElementById("End_Date").style = 'border: 1px solid black; text-align: center;';
            //window.open("generalgeneralledgerfilteredreport.php?Section=" + Section + "&Patient_Type=" + Patient_Type + "&Start_Date=" + Start_Date + "&End_Date=" + End_Date + "&Sponsor_ID=" + Sponsor_ID + "&Employee_ID=" + Employee_ID + "&Hospital_Ward_ID=" + Hospital_Ward_ID + "&clinic=" + clinic + "&GeneralLedgerCashAndCreditReport=GeneralLedgerCashAndCreditReportThisPage", "_parent");
            window.open("generalgeneralledgerfilteredmai_departmentreport.php?Section=" + Section + "&Patient_Type=" + Patient_Type + "&Start_Date=" + Start_Date + "&End_Date=" + End_Date + "&Sponsor_ID=" + Sponsor_ID + "&Employee_ID=" + Employee_ID + "&Hospital_Ward_ID=" + Hospital_Ward_ID + "&clinic=" + clinic + "&GeneralLedgerCashAndCreditReport=GeneralLedgerCashAndCreditReportThisPage", "_parent");
        } else {
            if (Start_Date == '' || Start_Date == null) {
                document.getElementById("Start_Date").style = 'border: 3px solid red; text-align: center;';
            } else {
                document.getElementById("Start_Date").style = 'border: 1px solid black; text-align: center;';
            }

            if (End_Date == '' || End_Date == null) {
                document.getElementById("End_Date").style = 'border: 3px solid red; text-align: center;';
            } else {
                document.getElementById("End_Date").style = 'border: 1px solid black; text-align: center;';
            }
        }
    }
</script>

<script type="text/javascript">
    function Open_Sponsors_Dialog() {
        if (window.XMLHttpRequest) {
            myObjectOpen = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectOpen = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectOpen.overrideMimeType('text/xml');
        }

        myObjectOpen.onreadystatechange = function () {
            data99 = myObjectOpen.responseText;
            if (myObjectOpen.readyState == 4) {
                document.getElementById("Sponsor_Dialog").innerHTML = data99;
                $("#Sponsor_Dialog").dialog("open");
            }
        }; //specify name of function that will handle server response........
        myObjectOpen.open('GET', 'Open_Sponsors_Dialog.php', true);
        myObjectOpen.send();
    }
</script>


<script type="text/javascript">
    function Open_Employee_Dialog() {
        if (window.XMLHttpRequest) {
            myObjectOpenEmp = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectOpenEmp = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectOpenEmp.overrideMimeType('text/xml');
        }

        myObjectOpenEmp.onreadystatechange = function () {
            data991 = myObjectOpenEmp.responseText;
            if (myObjectOpenEmp.readyState == 4) {
                document.getElementById("Employee_Dialog").innerHTML = data991;
                $("#Employee_Dialog").dialog("open");
            }
        }; //specify name of function that will handle server response........
        myObjectOpenEmp.open('GET', 'Open_Employees_Dialog.php', true);
        myObjectOpenEmp.send();
    }
</script>

<script type="text/javascript">
    function Get_Sponsor(Sponsor_ID, Guarantor_Name) {
        document.getElementById("Sponsor_ID").value = Sponsor_ID;
        document.getElementById("Sponsor_Name").value = Guarantor_Name;
        $("#Sponsor_Dialog").dialog("close");
    }
</script>

<script type="text/javascript">
    function Select_All_Sponsor() {
        document.getElementById("Sponsor_ID").value = 0;
        document.getElementById("Sponsor_Name").value = 'All';
        $("#Sponsor_Dialog").dialog("close");
    }
</script>

<script type="text/javascript">
    function Select_All_Employees() {
        document.getElementById("Employee_ID").value = 0;
        document.getElementById("Employee_Name").value = 'All';
        $("#Employee_Dialog").dialog("close");
    }
</script>

<script type="text/javascript">
    function Search_Sponsor() {
        var Sponsor_Name = document.getElementById("S_Name").value;
        if (window.XMLHttpRequest) {
            myObjectSearch = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectSearch = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearch.overrideMimeType('text/xml');
        }

        myObjectSearch.onreadystatechange = function () {
            data992 = myObjectSearch.responseText;
            if (myObjectSearch.readyState == 4) {
                document.getElementById("Sponsors_Fieldset").innerHTML = data992;
            }
        }; //specify name of function that will handle server response........
        myObjectSearch.open('GET', 'Search_Sponsor.php?Sponsor_Name=' + Sponsor_Name, true);
        myObjectSearch.send();
    }
</script>

<script type="text/javascript">
    function Search_Employee() {
        var Employee_Name = document.getElementById("E_Name").value;
        if (window.XMLHttpRequest) {
            myObjectSearch = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectSearch = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearch.overrideMimeType('text/xml');
        }

        myObjectSearch.onreadystatechange = function () {
            data995 = myObjectSearch.responseText;
            if (myObjectSearch.readyState == 4) {
                document.getElementById("Employees_Fieldset").innerHTML = data995;
            }
        }; //specify name of function that will handle server response........
        myObjectSearch.open('GET', 'Search_Emp.php?Employee_Name=' + Employee_Name, true);
        myObjectSearch.send();
    }
</script>

<script type="text/javascript">
    function Get_Employee(Employee_ID, Employee_Name) {
        document.getElementById("Employee_ID").value = Employee_ID;
        document.getElementById("Employee_Name").value = Employee_Name;
        $("#Employee_Dialog").dialog("close");
    }
</script>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('#Start_Date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('#Start_Date').datetimepicker({value: '', step: 01});
    $('#End_Date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:'now'
    });
    $('#End_Date').datetimepicker({value: '', step: 01});
</script>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="script.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="script.responsive.js"></script>
<script>
    $(document).ready(function () {
        $("#Sponsor_Dialog").dialog({autoOpen: false, width: '35%', height: 450, title: 'SELECT SPONSOR', modal: true});
        $("#Employee_Dialog").dialog({autoOpen: false, width: '35%', height: 450, title: 'SELECT SPONSOR', modal: true});
    });
</script>

<?php
include("./includes/footer.php");
?>