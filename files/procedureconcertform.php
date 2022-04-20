<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>

<!-- <a href='Pre_Operative_Patients.php?PreOperativeList=PreOperativeListThisPage' class='art-button-green'>
	REGISTERED
    </a> -->
<!-- <input type="button" value="BACK" onclick="history.go(-1)" class="art-button-green"> -->
<a href="Procedure.php?PatientsBillingWorks=PatientsBillingWorks" class="art-button-green">BACK</a>

<div style='float: right;'>
    <span style='font-weight: bold; font-size: 18px;'>COLOR CODE KEY: <span>
            <input type="button" style='background: green; padding: 10px; color: #fff; border: none;' value='SIGNED CONSENT' name="" id="">
            <input type="button" style='background: #ff8080; padding: 10px; border: none;' value='SIGNED & AGREED AMPUTATION' name="" id="">
</div>




<?php
$Today_Date = mysqli_query($conn, "select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;

    $Age = $Today - $original_Date;
}
?>

<script language="javascript" type="text/javascript">
    function searchPatient() {
        Patient_Name = document.getElementById('Patient_Name').value;
        Patient_Number = document.getElementById('Patient_Number').value;
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=380px src='search_list_patient_billing_Iframe4.php?Patient_Name=" + Patient_Name + "&Patient_Number=" + Patient_Number + "'></iframe>";
    }
</script>
<br /><br />
<center>
    <table width="60%">
        <tr>
            <td width="50%">
                <input type='text' name='Patient_Name' id='Patient_Name' style='text-align: center;' onkeyup='searchPatient()' placeholder='~~~~~~~~~~~~~~~~~~~~~~~~~Search Patient Name~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~'>
            </td>
            <td width="50%">
                <input type='text' name='Patient_Number' id='Patient_Number' style='text-align: center;' onkeyup='searchPatient()' placeholder='~~~~~~~~~~~~~~~~~~~~~~~~~Search Patient Number~~~~~~~~~~~~~~~~~~~~~~~~~~~~'>
            </td>
        </tr>
    </table>
</center>      
<br>
<fieldset>
    <legend align=center><b>PATIENT LIST FOR PROCEDURE CONSENT FORM</b></legend>
    <center>
        <table width=100% border=1>
            <tr>
                <td id='Search_Iframe'>
                    <iframe width='100%' height=380px src='search_list_patient_billing_Iframe4.php?Patient_Name='></iframe>      
                </td>
            </tr>
        </table>
    </center>
</fieldset><br />
<?php
include("./includes/footer.php");
?>