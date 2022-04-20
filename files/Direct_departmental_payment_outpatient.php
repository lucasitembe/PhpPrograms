<?php
include("./includes/connection.php");
$location = '';
if (isset($_GET['location']) && $_GET['location'] == 'otherdepartment') {
    include("./includes/header_general.php");
    $location = 'location=otherdepartment&';
} else {
    include("./includes/header.php");
    include("./includes/connection.php");
}
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    if (isset($_GET['location']) && $_GET['location'] == 'otherdepartment') {
        die("<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this resource.Please contact administrator for support!<p>");
    } else {
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Revenue_Center_Works'])) {
        if ($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes') {
            if (isset($_GET['location']) && $_GET['location'] == 'otherdepartment') {
                die("<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this resource.Please contact administrator for support!<p>");
            } else {
                header("Location: ../index.php?InvalidPrivilege=yes");
            }
        }
    } else {
        if (isset($_GET['location']) && $_GET['location'] == 'otherdepartment') {
            die("<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this resource.Please contact administrator for support!<p>");
        } else {
            header("Location: ../index.php?InvalidPrivilege=yes");
        }
    }
} else {
    @session_destroy();
    if (isset($_GET['location']) && $_GET['location'] == 'otherdepartment') {
        die("<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this resource.Please contact administrator for support!<p>");
    } else {
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
}
    if(isset($_SESSION['systeminfo']['Direct_departmental_payments'])){
        if(strtolower($_SESSION['systeminfo']['Direct_departmental_payments']) != 'yes'){
            //header("Location: ./departmentpatientbillingpage.php?DepartmentPatientBilling=DepartmentPatientBillingThisPage");
        }
    }else{
        //header("Location: ./departmentpatientbillingpage.php?DepartmentPatientBilling=DepartmentPatientBillingThisPage");
    }
?>


<!-- link menu -->
<script type="text/javascript">
//     function gotolink(){
// 	var patientlist = document.getElementById('patientlist').value;
// 	if(patientlist=='OUTPATIENT CASH'){
// 	    document.location = "revenuecenterlaboratorylist.php?Billing_Type=OutpatientCash&PharmacyList=PharmacyListThisForm";
// 	}else if (patientlist=='OUTPATIENT CREDIT') {
// 	    document.location = "revenuecenterlaboratorylist.php?Billing_Type=OutpatientCredit&PharmacyList=PharmacyListThisForm";
// 	}else if (patientlist=='INPATIENT CASH') {
// 	    document.location = "revenuecenterlaboratorylist.php?Billing_Type=InpatientCash&PharmacyList=PharmacyListThisForm";
// 	}else if (patientlist=='INPATIENT CREDIT') {
// 	    document.location = "revenuecenterlaboratorylist.php?Billing_Type=InpatientCredit&PharmacyList=PharmacyListThisForm";
// 	}else if (patientlist=='PATIENT LIST') {
// 	    document.location = "laboratorypatientlist.php?LaboratoryPatientList=laboratorypatientlistThisPage";
// 	}else{
// 	    alert("Choose Type Of Patients To View");
// 	}
//     }
</script>

<!-- <label style='border: 1px ;padding: 8px;margin-right: 7px;' class='art-button-green'>
<select id='patientlist' name='patientlist' onchange='gotolink()'>
    <option> Select List To View</option>
    <option>
                OUTPATIENT CASH
    </option>
    <option>
                INPATIENT CASH
    </option>
    <option>
                PATIENT LIST
    </option>
</select>
</label>  -->





<?php
    if(isset($_SESSION['systeminfo']['Direct_departmental_payments']) && strtolower($_SESSION['systeminfo']['Direct_departmental_payments']) == 'yes'){
?>
    <a href='departmentalregisterpatient.php?DepartmentalRegisterPatient=DepartmentalRegisterPatientThisPage' class='art-button-green'>
        ADD NEW PATIENT
    </a>
<?php  } ?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='patient_direct_item_payment.php?DepartmentPatientBilling=DepartmentPatientBillingThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>


<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        
        $age = $Today - $original_Date; 
    }
?>

<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name) {
        var Patient_Number = document.getElementById("Patient_Number").value;

        if (Patient_Number != '' && Patient_Number != null) {
            document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=400px src='Departmental_search_patient_list.php?<?php echo $location ?>Patient_Number=" + Patient_Number + "&Patient_Name=" + Patient_Name + "'></iframe>";
        } else {
            document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=400px src='Departmental_search_patient_list.php?<?php echo $location ?>Patient_Name=" + Patient_Name + "'></iframe>";
        }
    }
</script>
<script language="javascript" type="text/javascript">
    function Search_Patient_Using_Number(Patient_Number) {
        var Patient_Name = document.getElementById("Search_Patient").value;

        if (Patient_Name != '' && Patient_Name != null) {
            document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=400px src='Departmental_search_patient_list.php?<?php echo $location ?>Patient_Number=" + Patient_Number + "&Patient_Name=" + Patient_Name + "'></iframe>";
        } else {
            document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=400px src='Departmental_search_patient_list.php?<?php echo $location ?>Patient_Number=" + Patient_Number + "'></iframe>";
        }
    }
</script>
<script language="javascript" type="text/javascript">
    function Search_Patient_Phone_Number(Phone_Number) {

        var Patient_Name = document.getElementById("Search_Patient").value;
        var Patient_Number = document.getElementById("Search_Patient").value;

        if ((Patient_Name != '' && Patient_Name != null) && (Patient_Number != '' && Patient_Number != null)) {//All set
            document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=400px src='Departmental_search_list_patient_billing_Iframe2.php?<?php echo $location ?>Patient_Number=" + Patient_Number + "&Patient_Name=" + Patient_Name + "&Phone_Number=" + Phone_Number + "'></iframe>";
        } else if ((Patient_Name != '' && Patient_Name != null) && (Patient_Number == '' || Patient_Number == null)) {//Patient_Number not set
            document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=400px src='Departmental_search_list_patient_billing_Iframe2.php?<?php echo $location ?>Patient_Name=" + Patient_Name + "&Phone_Number=" + Phone_Number + "'></iframe>";
        } else if ((Patient_Name == '' || Patient_Name == null) && (Patient_Number != '' && Patient_Number != null)) {
            document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=400px src='Departmental_search_list_patient_billing_Iframe2.php?<?php echo $location ?>Patient_Number=" + Patient_Number + "&Phone_Number=" + Phone_Number + "'></iframe>";
        } else {
            document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=400px src='Departmental_search_list_patient_billing_Iframe2.php?<?php echo $location ?>Phone_Number=" + Phone_Number + "'></iframe>";
        }
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
<!--            <td width=30%>
                <input type='text' name='Phone_Number' id='Phone_Number' style='text-align:center;' oninput='Search_Patient_Phone_Number(this.value)' placeholder='~~~~~~~~~~~~~  Search Phone Number  ~~~~~~~~~~~~~~~'>
            </td>-->
        </tr>

    </table>
</center>
<br/>
<fieldset >  
        <legend align='right'><b>Direct Departmental Payments ~ OUTPATIENTS LIST: REVENUE CENTER</b></legend>
        <center>
            <table width=100% border=1>
                <tr>
                    <td id='Search_Iframe'>
                        <?php if (isset($_SESSION['systeminfo']['Direct_departmental_payments']) && strtolower($_SESSION['systeminfo']['Direct_departmental_payments']) == 'yes') { ?>
                            <iframe width='100%' height=400px src='Departmental_search_outpatient_iframe.php?Patient_Name="+Patient_Name+"'></iframe>
                        <?php } else { ?>
                            <iframe width='100%' height=400px src='Adhoc_Department_search_outpatient_iframe.php?Patient_Name="+Patient_Name+"'></iframe>
                        <?php } ?>
                    </td>
                </tr>
            </table>
        </center>
</fieldset>
<br/>
<?php
if (isset($_GET['location']) && $_GET['location'] == 'otherdepartment') {
    //include("./includes/footer.php");
} else {
    include("./includes/footer.php");
}
?>