<style>
    select{
        padding:5px;
    }

    .dates{
        color:#cccc00;
    }
</style>
<style>
    .rows_list{
        cursor: pointer;
    }
    .rows_list:active{
        color: #328CAF!important;
        font-weight:normal!important;
    }
    .rows_list:hover{
        color:#00416a;
        background: #dedede;
        font-weight:bold;
    }
    a{
        text-decoration: none;
    }
</style>
<style>
    .list-items{
        background: #f5f5f5;
        margin-bottom: 1px;
        transition: all 0.3s ease;
    }

    .list-items{
        background: #f5f5f5;
        width: 90%;
        height: 100%;
        list-style: none;

    }
    .list-items li{
        padding-left: 40px;
        padding-right: 40px;
        line-height: 70px;
        height: 6%;
        border-top: 1px solid rgba(255,255,255,0.1);
        border-bottom: 1px solid #333;
        transition: all 0.3s ease;

    }
    .list-items li:hover{
        border-top: 1px solid transparent;
        border-bottom: 1px solid transparent;
        box-shadow: 0 0px 10px 3px #222;
    }
    .list-items a{
        color: white;
        font-weight: bold;
        font-size: 15px;
        text-decoration: none;
    }
    .content{
        position: absolute;
        left: 14.5%;
        width: 86%;
    }
    .list-items a:link {
        text-decoration: none;
    }
    .content label{
        font-size: 15px;
        font-weight: bold;
    }
</style>
<?php
include("./includes/header.php");
include("./includes/connection.php");
    @session_start();
   
    $employee_ID = $_SESSION['userinfo']['Employee_ID'];
$Today_Date = mysqli_query($conn, "select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $End_Date = $row['today'];
    $todaysDate = $row['today'];
    $new_Date = date("Y-m-d", strtotime($End_Date));
    $Start_Date = $new_Date . ' 00:00:00';
}

if (!isset($_SESSION['userinfo'])) {
    // @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])) {
        if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes') {
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
} else {
    $Registration_ID = '';
}
?>
<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') {
        ?>
        <a href='doctoremergencypage.php?RevenueCenterWork=RevenueCenterWorkThisPage' class='art-button-green'>
            BACK
        </a>
        <?php
    }
}
    if (isset($_GET['Patient_Payment_ID'])) {
       $Patient_Payment_ID=$_GET['Patient_Payment_ID'];
    }
    if (isset($_GET['Patient_Payment_Item_List_ID'])) {
        $Patient_Payment_Item_List_ID=$_GET['Patient_Payment_Item_List_ID'];
    }
?>
    <a href="all_patient_file_link_station.php?Registration_ID=<?= $Registration_ID ?>&Patient_Payment_ID=<?= $Patient_Payment_ID ?>&Patient_Payment_Item_List_ID=<?= $Patient_Payment_Item_List_ID ?>" class='art-button-green' target="_blank">PATIENT FILE</a>

<?php
    if (isset($_SESSION['doctors_selected_clinic']) && ($_SESSION['doctors_selected_clinic'] != null || $_SESSION['doctors_selected_clinic'] != 0 || $_SESSION['doctors_selected_clinic'] != "")) {
        $doctors_selected_clinic = $_SESSION['doctors_selected_clinic'];
        $finance_department_id = $_SESSION['finance_department_id'];
        $clinic_location_id = $_SESSION['clinic_location_id'];
    // echo "<script>alert('$doctors_selected_clinic');</script>";
    }else{
    echo "<script>select_clinic_dialog();</script>";
    die();
    }
?>
<?php
if (isset($_POST['fromDate'])) {
    $fromDate = $_POST['fromDate'];
} else {
    $fromDate = '';
}

if (isset($_POST['toDate'])) {
    $toDate = $_POST['toDate'];
} else {
    $toDate = '';
}

if (isset($_POST['Sponsor_ID'])) {
    $Sponsor_ID = $_POST['Sponsor_ID'];
} else {
    $Sponsor_ID = '';
}
if (isset($_GET['Patient_Payment_ID'])) {
    $Patient_Payment_ID= $_GET['Patient_Payment_ID'];
}
if (isset($_GET['Patient_Payment_Item_List_ID'])) {
    $Patient_Payment_Item_List_ID=$_GET['Patient_Payment_Item_List_ID'];
}

function getClinicSelected() {
    global $conn;
    $clinic_ID = $_SESSION['doctors_selected_clinic'];
    $sql = "SELECT Clinic_ID,Clinic_Name FROM tbl_clinic WHERE Clinic_Id='$clinic_ID'";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    $response = array();
    while ($row = mysqli_fetch_assoc($result)) {
        extract($row);
        $response['clinicID'] = $clinic_ID;
        $response['clinicName'] = $Clinic_Name;
    }
    return $response;
}

$Registration_ID = $_GET['Registration_ID'];
$select_Patient = mysqli_query($conn, "select
                            Title,Patient_Name,pr.Sponsor_ID,Date_Of_Birth,
                                    Gender,pr.Phone_Number,Guarantor_Name,Claim_Number_Status
                                      from tbl_patient_registration pr, tbl_sponsor sp 
                                        where pr.Sponsor_ID = sp.Sponsor_ID and 
                                        Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
$no = mysqli_num_rows($select_Patient);
if ($no > 0) {
    while ($row = mysqli_fetch_array($select_Patient)) {
        $Title = $row['Title'];
        $Patient_Name = $row['Patient_Name'];
        $Date_Of_Birth = $row['Date_Of_Birth'];
        $Gender = $row['Gender'];
        $Guarantor_Name = $row['Guarantor_Name'];
        $Phone_Number = $row['Phone_Number'];
    }
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
    }
}
include 'emergency/emergencyclinicalnotes.php';

    $consultation_query_result_cons = mysqli_query($conn,"SELECT consultation_ID FROM tbl_consultation WHERE  Registration_ID='$Registration_ID' AND DATE(Consultation_Date_And_Time)=CURDATE()") or die(mysqli_error($conn));
    if (mysqli_num_rows($consultation_query_result_cons) > 0) {
        // $consultation_query_result = mysqli_query($conn,"SELECT consultation_ID FROM tbl_consultation WHERE  Registration_ID='$Registration_ID' AND DATE(Consultation_Date_And_Time)=CURDATE()") or die(mysqli_error($conn));
        $consultation_ID=mysqli_fetch_assoc($consultation_query_result_cons)['consultation_ID'];
    } else {
        $insert_query = "INSERT INTO tbl_consultation(employee_ID, Registration_ID,Patient_Payment_Item_List_ID,Consultation_Date_And_Time,Clinic_ID)
            VALUES ('$employee_ID', '$Registration_ID','$Patient_Payment_Item_List_ID',NOW(),'$doctors_selected_clinic')";
        mysqli_query($conn,$insert_query) or die(mysqli_error($conn));

        $consultation_query_result_new = mysqli_query($conn,"SELECT consultation_ID FROM tbl_consultation WHERE  Registration_ID='$Registration_ID' AND DATE(Consultation_Date_And_Time)=CURDATE()") or die(mysqli_error($conn));
        $consultation_ID=mysqli_fetch_assoc($consultation_query_result_new)['consultation_ID'];

        mysqli_query($conn, "INSERT INTO  tbl_consultation_history(consultation_ID,employee_ID,cons_hist_Date)
        VALUES ('$consultation_ID','$employee_ID',NOW())") or die(mysqli_error($conn));

        // $consultation_query_result = mysqli_query($conn,"SELECT consultation_ID FROM tbl_consultation WHERE  Registration_ID='$Registration_ID' AND DATE(Consultation_Date_And_Time)=CURDATE()") or die(mysqli_error($conn));
        // $consultation_ID=mysqli_fetch_assoc($consultation_query_result)['consultation_ID'];
    }
    
    // $consultation_query_history = "SELECT consultation_ID FROM tbl_consultation_history WHERE consultation_ID = '$consultation_ID' AND DATE(cons_hist_Date)=CURDATE() AND employee_ID='$employee_ID'";


    // $consultation_query_result_history = mysqli_query($conn,$consultation_query_history) or die(mysqli_error($conn));

    // if (@mysqli_num_rows($consultation_query_result_history) > 0) {
    // //die("I am here");
    // } else {
    //     //echo $insert_query;
    //     mysqli_query($conn, "INSERT INTO  tbl_consultation_history(consultation_ID,employee_ID,cons_hist_Date)
    //     VALUES ('$consultation_ID','$employee_ID',NOW())") or die(mysqli_error($conn));
    // }

?>
<br/>
<div id="select_clinic" style="display:none;">
    <style type="text/css">
                #spu_lgn_tbl{
                    width:100%;
                    border:none!important;
                }
                #spu_lgn_tbl tr, #spu_lgn_tbl tr td{
                    border:none!important;
                    padding: 15px;
                    font-size: 14PX;
                }
    </style>
    <table  id="spu_lgn_tbl">
        <tr id="select_clinic">
                    <td style="text-align:right">
                        Select Your working Clinic
                    </td>
                    <td>
                        <select  style='width: 100%;height:30%'  name='Clinic_ID' id='Clinic_ID' value='<?php echo $Guarantor_Name; ?>' onchange='clearFocus(this);update_clinic_id()' onclick='clearFocus(this)' required='required'>
                            <option selected='selected'></option>
                            <?php
                             $Select_Consultant = "select * from tbl_clinic where Clinic_Status = 'Available'";
                            $result = mysqli_query($conn,$Select_Consultant);
                            ?> 
                            <?php
                            while ($row = mysqli_fetch_array($result)) {
                                ?>
                                <option value="<?php echo $row['Clinic_ID']; ?>"><?php echo $row['Clinic_Name']; ?></option>
                                <?php
                            } 
                            ?>
                        </select>
                    </td>
                    <tr>
                   <td style="text-align:right">
                        Select Your working Department
                   </td>
                   <td style="width:60%">
                       <select id="working_department" style="width:100%">
                            <option value=""></option>
                            <?php 
                                $sql_select_working_department_result=mysqli_query($conn,"SELECT finance_department_code,finance_department_id,finance_department_name FROM tbl_finance_department WHERE enabled_disabled='enabled'") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_select_working_department_result)>0){
                                    while($finance_dep_rows=mysqli_fetch_assoc($sql_select_working_department_result)){
                                       $finance_department_id=$finance_dep_rows['finance_department_id'];
                                       $finance_department_name=$finance_dep_rows['finance_department_name'];
                                       $finance_department_code=$finance_dep_rows['finance_department_code'];
                                       echo "<option value='$finance_department_id'>$finance_department_name-->$finance_department_code</option>";
                                    }
                                }
                            ?>
                        </select>
                   </td>
                </tr>
                </tr>
                <td colspan="2" align="right">
                    <input type="button" onclick="post_clinic_id()" class="art-button-green" value="Open"/>
                </td>
        </tr> 
    </table>
</div>
<fieldset> 
    <legend align="center" >
        <b>Patient Name:    <span class='dates'><?php echo $Title . " " . $Patient_Name; ?></span></b>
        <b>Age: <span class='dates'><?php echo $age; ?></span></b>
        <b>Gender:  <span class='dates'><?php echo $Gender; ?></span></b>
        <b>Sponsor: <span class='dates'><?php echo $Guarantor_Name; ?></span></b>
        <b>Phone:   <span class='dates'><?php echo $Phone_Number; ?></span></b>
    </legend>
    <div class="box box-primary" style="height: 670px;overflow-y: hidden;overflow-x: hidden">
        <table style="width: 100%;border: none; height: 100%;">
            <tr>
                <td style="widht: 15%;background: #f5f5f5;">
                    <ul class="list-items">
                        <li onclick="complain()"><a href="#" style="text-decoration: none;">Complain</a></li>
                        <li onclick="vitals()" ><a href="#" style="text-decoration: none;">Vitals</a></li>
                        <li onclick="diagnosis()"><a href="#" style="text-decoration: none;">Diagnosis</a></li>
                        <li onclick="investigation();"><a href="#" style="text-decoration: none;">Investigation & Results</a></li>
                        <li onclick="treatments()"><a href="#" style="text-decoration: none;">Treatment Orders</a></li>
                        
                        <!-- <li onclick="investigationresult()"><a href="#" style="text-decoration: none;">Investigation && Results</a></li> -->
                        <!-- <li><a href="#" style="text-decoration: none;">Consumables</a></li> -->
                    </ul>
                </td>
                <td style="width: 85%;height: 100%;">
                    <div class="box box-primary" style="height: 670px;overflow-y: scroll;overflow-x: hidden">
                        <?php include 'emergency/emergencyfile.php'; ?>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</fieldset>
<div id="complain" style="width:50%;margin-top: 5px;" >
    <?php include 'emergency/complain.php'; ?>
</div>
<div id="diagnosis" style="width:50%;margin-top: 10px;" >
    <?php include 'emergency/diagnosis.php'; ?>
</div>
<div id="vitals" style="width:50%;margin-top: 10px;" >
    <?php include 'emergency/vitals.php'; ?>
</div>
<div id="investigation" style="width:50%;margin-top: 10px;" >
    <?php include 'emergency/investigation.php'; ?>
</div>
<div id="treatments" style="width:50%;margin-top: 10px;" >
    <?php include 'emergency/treatments.php'; ?>
</div>
<div id="investigationresult" style="width:50%;margin-top: 10px;" >
    <?php // include 'emergency/treatments.php'; ?>
</div>
<div id="showdataConsult" style="width:100%;overflow-x:hidden;height:520px;display:none;overflow-y:scroll">
    <div id="myConsult">
    </div>
</div>
<input type='hidden' id='recentConsultaionTyp' value=''>
<script>
    function confirm_final_diagnosis(Consultation_Type) {
        var require_final_diagnosis = '<?= $require_final_diagnosis ?>';
        if ($('.final_diagnosis').val() == "" && require_final_diagnosis == "yes") {
            alert("YOU HAVE TO SELECT FINAL DIAGNOSIS FIRST,BEFORE ADD ANY TREATMENT");
        } else {
            getItem(Consultation_Type);
        }
    }

    function getDiseaseFinal(Consultation_Type) {
        if (Consultation_Type == '') {
            Consultation_Type = 'NotSet'
        }

        document.getElementById("recentConsultaionTyp").value = Consultation_Type;
        //alert('gsmmm');
        var ul = 'doctoritemselectajax.php';
        if (Consultation_Type == 'diagnosis' || Consultation_Type == 'provisional_diagnosis' || Consultation_Type == 'diferential_diagnosis') {
            ul = 'doctordiagnosisselect.php';
        }

        var url = './clinicalautosave_todisease.php?Consultation_Type=' + Consultation_Type + '&<?php
    if ($Registration_ID != '') {
        echo "Registration_ID=$Registration_ID&";
    }
    ?><?php
    if (isset($_GET['Patient_Payment_ID'])) {
        echo "Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&";
    }
    if ($consultation_ID != 0) {
        echo "consultation_ID=" . $consultation_ID . "&";
    }
    if (isset($_GET['Patient_Payment_Item_List_ID'])) {
        echo "Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "&";
    }
    ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage';
        // frm.action = url;
        // frm.method = 'POST';
        // frm.submit();
        // return false;
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            mm = new ActiveXObject('Microsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }

        mm.onreadystatechange = AJAXP2; //specify name of function that will handle server response....
        mm.open('POST', url, true);
        mm.send();
        function AJAXP2() {
            var data1 = mm.responseText;
            //document.getElementById('Item_Subcategory_ID').innerHTML = data1;
        }

        var url2 = 'Consultation_Type=' + Consultation_Type + '&<?php
    if ($Registration_ID != '') {
        echo "Registration_ID=$Registration_ID&";
    }
    ?><?php
    if (isset($_GET['Patient_Payment_ID'])) {
        echo "Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&";
    }
    if ($consultation_ID != 0) {
        echo "consultation_ID=" . $consultation_ID . "&";
    }
    if (isset($_GET['Patient_Payment_Item_List_ID'])) {
        echo "Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "&";
    }
    ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage';
        $.ajax({
            type: 'GET',
            url: ul,
            data: url2,
            success: function (html) {
                $('#myConsult').html(html);
                $("#showdataConsult").dialog("open");
            }
        });
    }
</script>
<script>
    function doneDiagonosisselect() {
        var Consultation_Type = document.getElementById("recentConsultaionTyp").value;
        //alert(Consultation_Type);
        if (Consultation_Type == 'provisional_diagnosis' || Consultation_Type == 'diferential_diagnosis' || Consultation_Type == 'diagnosis') {
            updateDoctorConsult();
        } else {
            updateConsult();
        }
        $("#showdataConsult").dialog("close");
    }

    function updateDoctorConsult() {
        //alert('I am here');
        var Consultation_Type = document.getElementById("recentConsultaionTyp").value;
        //alert(Consultation_Type);
        var url2 = "Consultation_Type=" + Consultation_Type + "&Registration_ID=<?php echo$Registration_ID ?>&Patient_Payment_ID='<?php echo$_GET['Patient_Payment_ID'] ?>'&consultation_ID='<?php echo $consultation_ID ?>'&Patient_Payment_Item_List_ID='<?php echo $_GET['Patient_Payment_Item_List_ID'] ?>'&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
        //alert(url2);
        $.ajax({
            type: 'GET',
            url: 'requests/itemdoctorselectupdate.php',
            data: url2,
            cache: false,
            success: function (html) {
                //alert(html);
                html = html.trim();
                var Consultation_Type = html.split('<$$$&&&&>');
                if (Consultation_Type[0].trim() == 'provisional_diagnosis') {
                    $('.provisional_diagnosis').attr('value', Consultation_Type[1]);
                    if ($('.provisional_diagnosis').val() != '') {
                        $('.confirmGetItem').attr("onclick", "getItem('Laboratory')");
                    } else {
                        $('.confirmGetItem').attr("onclick", "confirmDiagnosis('Laboratory')");
                    }
                } else if (Consultation_Type[0].trim() == 'diferential_diagnosis') {
                    //alert(Consultation_Type[0]+"  "+Consultation_Type[1]);
                    $('.diferential_diagnosis').attr('value', Consultation_Type[1]);
                } else if (Consultation_Type[0].trim() == 'diagnosis') {
                    $('.final_diagnosis').attr('value', Consultation_Type[1]);
                }
            }
        });
    }
</script>
<script>
    function complain() {
        $("#complain").dialog("open");
    }

    function diagnosis() {
        $("#diagnosis").dialog("open");
    }

    function vitals() {
        $("#vitals").dialog("open");
    }

    function physicalexamination() {
        $("#physicalexamination").dialog("open");
    }

    function investigation() {
        $("#investigation").dialog("open");
    }
    function treatments() {
        $("#treatments").dialog("open");
    }
    function investigationresult() {
        $("#investigationresult").dialog("open");
    }
</script>
<script>
    $(document).ready(function () {
        $("#complain").dialog({autoOpen: false, width: '85%', height: 400, title: 'PATIENT COMPLAIN', modal: true});
        $("#diagnosis").dialog({autoOpen: false, width: '85%', height: 400, title: 'PATIENT DIAGNOSIS', modal: true});
        $("#vitals").dialog({autoOpen: false, width: '85%', height: 500, title: 'PATIENT VITALS', modal: true});
        $("#investigation").dialog({autoOpen: false, width: '85%', height: 500, title: 'PATIENT INVESTIGATION ORDERS', modal: true});
        $("#treatments").dialog({autoOpen: false, width: '85%', height: 500, title: 'PATIENT TREATMENT ORDERS', modal: true});
        $("#showdataConsult").dialog({autoOpen: false, width: '90%', title: 'SELECT  ITEM FROM THIS CONSULTATION', modal: true, position: 'middle'});
        $("#investigationresult").dialog({autoOpen: false, width: '85%', title: 'INVESTIGATION RESULT', modal: true, position: 'middle'});

        $("#physicalexamination").dialog({autoOpen: false, width: '95%', height: 600, title: 'PHYSICAL EXAMINATION', modal: true});

        $("#Add_Disease_Dialog").dialog({autoOpen: false, width: '60%', height: 450, title: 'ADD DISEASES', modal: true});
        $("#Remove_Disease_Dialog").dialog({autoOpen: false, width: '60%', height: 450, title: 'REMOVE DISEASES', modal: true});

        $('.ui-dialog-titlebar-close').click(function () {
            Get_Transaction_List();
        });

    });
</script>

<script type="text/javascript">
    function getItem(Consultation_Type) {

        if (Consultation_Type == '') {
            Consultation_Type = 'NotSet'
        }



        document.getElementById("recentConsultaionTyp").value = Consultation_Type;
        var frm = document.getElementById("clinicalnotes");
        var url = './clinicalautosave.php?Consultation_Type=' + Consultation_Type + '&<?php
    if ($Registration_ID != '') {
        echo "Registration_ID=$Registration_ID&";
    }
    ?><?php
    if (isset($_GET['Patient_Payment_ID'])) {
        echo "Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&";
    }
    if ($consultation_ID != 0) {
        echo "consultation_ID=" . $consultation_ID . "&";
    }
    if (isset($_GET['Patient_Payment_Item_List_ID'])) {
        echo "Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "&";
    }
    ?>&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage';
        // frm.action = url;
        // frm.method = 'POST';
        // frm.submit();

        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            mm = new ActiveXObject('Microsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }

        mm.onreadystatechange = AJAXP2; //specify name of function that will handle server response....
        mm.open('POST', url, true);
        mm.send();
        function AJAXP2() {
            var data1 = mm.responseText;
            //alert(data1);
            //document.getElementById('Item_Subcategory_ID').innerHTML = data1;
        }

        //alert(Consultation_Type); 

        var url2 = 'Consultation_Type=' + Consultation_Type + '&<?php
    if ($Registration_ID != '') {
        echo "Registration_ID=$Registration_ID&";
    }
    ?><?php
    if (isset($_GET['Patient_Payment_ID'])) {
        echo "Patient_Payment_ID=" . $_GET['Patient_Payment_ID'] . "&";
    }
    if ($consultation_ID != 0) {
        echo "consultation_ID=" . $consultation_ID . "&";
    }
    if (isset($_GET['Patient_Payment_Item_List_ID'])) {
        echo "Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "";
    }
    ?>&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage';
        //  alert(url2);
        $.ajax({
            type: 'GET',
            url: 'doctoritemselectajax.php',
            data: url2,
            cache: false,
            success: function (html) {
                //alert(html);
                $('#myConsult').html(html);
                $("#showdataConsult").dialog("open");
            }
        });
    }

    function updateConsult() {
        //alert('I am here');
        var Consultation_Type = document.getElementById("recentConsultaionTyp").value;
        //alert(Consultation_Type);
        var url2 = "Consultation_Type=" + Consultation_Type + "&Registration_ID=<?php echo$Registration_ID ?>&Patient_Payment_ID='<?php echo$_GET['Patient_Payment_ID'] ?>'&consultation_ID='<?php echo $consultation_ID ?>'&Patient_Payment_Item_List_ID='<?php echo $_GET['Patient_Payment_Item_List_ID'] ?>'&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
        //alert(url2);
        $.ajax({
            type: 'GET',
            url: 'requests/itemselectupdate.php',
            data: url2,
            cache: false,
            success: function (html) {
                //alert(html);
                html = html.trim();
                var departs = html.split('tenganisha');
                for (var i = 0; i < departs.length; i++) {
                    var Consultation_Type = departs[i].split('<$$$&&&&>');
                    //alert(Consultation_Type[0]);
                    if (Consultation_Type[0].trim() == 'Radiology') {
                        $('.Radiology').html(Consultation_Type[1]);
                    } else if (Consultation_Type[0].trim() == 'Treatment') {
                        $('.Treatment').html(Consultation_Type[1]);
                    } else if (Consultation_Type[0].trim() == 'Laboratory') {
                        $('#laboratory').html(Consultation_Type[1]);
                        //alert(Consultation_Type[0]+"  "+Consultation_Type[1]);
                    } else if (Consultation_Type[0].trim() == 'Procedure') {
                        $('.Procedure').html(Consultation_Type[1]);
                    } else if (Consultation_Type[0].trim() == 'Surgery') {
                        $('.Surgery').html(Consultation_Type[1]);
                    } else if (Consultation_Type[0].trim() == 'Others') {
                        $('.otherconstype').html(Consultation_Type[1]);
                    }
                }
            }
        });
    }

    $(document).ready(function () {
        $('#clinicpatients').DataTable({
            "bJQueryUI": true

        });
        // filterPatient();
        $('#Date_From').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:    'now'
        });
        $('#Date_From').datetimepicker({value: '', step: 30});
        $('#Date_To').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:'now'
        });
        $('#Date_To').datetimepicker({value: '', step: 30});
    });
    function select_clinic_dialog(){
          $("#select_clinic").dialog({
                        title: 'SELECT CLINIC',
                        width: '40%',
                        height: 200,
                        modal: true,
                    });
    }
</script>

<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<script type="text/javascript" src="js/afya_card.js"></script>
<script type="text/javascript" src="js/finger_print.js"></script>
<?php
include("./includes/footer.php");
?>
