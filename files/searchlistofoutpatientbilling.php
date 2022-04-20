<?php

include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Revenue_Center_Works'])) {
        if ($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}
?>
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    #sss:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<!-- link menu -->
<script type="text/javascript">
    function gotolink() {
        var patientlist = document.getElementById('patientlist').value;
        if (patientlist == 'Checked In - Outpatient List') {
            document.location = "searchlistofoutpatientbilling.php?SearchListOfOutpatientBilling=SearchListOfOutpatientBillingThisPage";
        } else if (patientlist == 'Checked In - Inpatient List') {
            document.location = "searchlistofinpatientbilling.php?SearchListPatientBilling=SearchListPatientBillingThisPage";
        } else if (patientlist == 'Direct Cash - Inpatient') {
            document.location = "DirectCashsearchlistinpatientbilling.php?SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage";
        } else if (patientlist == 'Direct Cash - Outpatient') {
            document.location = "DirectCashsearchlistofoutpatientbilling.php?SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage";
        } else if (patientlist == 'AdHOC Payments - Outpatient') {
            document.location = "continueoutpatientbilling.php?ContinuePatientBilling=ContinuePatientBillingThisPage";
        } else if (patientlist == 'Patient from outside') {
            document.location = "tempregisterpatient.php?RegistrationNewPatient=RegistrationNewPatientThisPage";
        } else {
            alert("Choose Type Of Patients To View");
        }
    }
</script>

<!--<label style='border: 1px ;padding: 8px;margin-right: 7px;' class='art-button-green'>
    <select id='patientlist' name='patientlist'>
        <option selected='selected'>Chagua Orodha Ya Wagonjwa</option>
        <option> Checked In - Outpatient List</option>
        <option>Checked In - Inpatient List</option>
        <option>Direct Cash - Outpatient</option>
        <option>Direct Cash - Inpatient</option>
        <option>AdHOC Payments - Outpatient</option>
        <!--<option>Patient from outside</option>
    </select>
    <input type='button' value='VIEW' onclick='gotolink()'>
</label>-->

<a href='departmentpatientbillingpage.php?DepartmentPatientBilling=DepartmentPatientBillingThisPage' class='art-button-green'>BACK</a>

<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name) {
        document.getElementById("Patient_Number").value = '';
        document.getElementById("Phone_Number").value = '';
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=400px src='search_list_patient_billing_Iframe.php?Patient_Name=" + Patient_Name + "'></iframe>";
    }
</script>
<script language="javascript" type="text/javascript">
    function Search_Patient_Using_Number(Patient_Number) {
        document.getElementById("Search_Patient").value = '';
        document.getElementById("Phone_Number").value = '';
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=400px src='search_list_patient_billing_Iframe.php?Patient_Number=" + Patient_Number + "'></iframe>";
    }
</script>
<script language="javascript" type="text/javascript">
    function Search_Patient_Phone_Number(Phone_Number) {
        document.getElementById("Patient_Number").value = '';
        document.getElementById("Search_Patient").value = '';
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=400px src='search_list_patient_billing_Iframe.php?Phone_Number=" + Phone_Number + "'></iframe>";
    }
</script>
<br/><br/>
<center>
    <table width=100%>
        <tr>
            <td width=30%>
                <input type='text' name='Search_Patient' id='Search_Patient' style='text-align: center;' oninput='searchPatient(this.value)'  placeholder='~~~~~~~~~~~~~  Search Patient Name  ~~~~~~~~~~~~~~~~~~~'>
            </td>
            <td width=30%>
                <input type='text' name='Patient_Number' id='Patient_Number' style='text-align: center;' oninput='Search_Patient_Using_Number(this.value)'  placeholder='~~~~~~~~~~~  Search Patient Number  ~~~~~~~~~~~~~~~'>
            </td>
            <td width=30%>
                <input type='text' name='Phone_Number' id='Phone_Number' style='text-align:center;' oninput='Search_Patient_Phone_Number(this.value)' placeholder='~~~~~~~~~~~~~  Search Phone Number  ~~~~~~~~~~~~~~~'>
            </td>
        </tr>

    </table>
</center>

<fieldset >
    <legend align="left"><b>CONSULTATION PAYMENTS ~ OUTPATIENT LIST</b></legend>
    <table width=100% border=1>
        <tr>
            <td id='Search_Iframe'>
                <iframe width='100%' height=400px src='search_list_patient_billing_Iframe.php'></iframe>
            </td>
        </tr>
    </table>
</fieldset>

<!-- <fieldset>  
            <legend align=center><b>OUTPATIENT LIST</b></legend>
        <center>
            <table width=100% border=1>
                <tr>
            <td id='Search_Iframe'>
                <iframe width='100%' height=380px src='search_list_patient_billing_Pre_Iframe.php?Patient_Name="+Patient_Name+"'></iframe>
            </td>
        </tr>
            </table>
        </center>
</fieldset><br/> -->
<?php

include("./includes/footer.php");
?>