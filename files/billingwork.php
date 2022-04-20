<?php
include("./includes/header.php");
include("./includes/connection.php");
echo "<link rel='stylesheet' href='fixHeader.css'>";


$from = "";
if(isset($_GET['from']) && $_GET['from'] == "cleared") {
    $from = $_GET['from'];
}

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Patients_Billing_Works'])) {
        if ($_SESSION['userinfo']['Patients_Billing_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if(!isset($_SESSION['Admission_Supervisor'])){
                header("Location:./deptsupervisorauthentication.php?SessionCategory=billing_works&InvalidSupervisorAuthentication=yes");
    }
?>
<?php

$from = "";
if(isset($_GET['from'])) {
    $from = $_GET['from'];
}

if (isset($_SESSION['userinfo'])) {
    echo "<a href='clearedpatientbillingwork.php?ClearedPatientsBillingWorks=ClearedPatientsBillingWorks&from=".$from."' class='art-button-green'>CLEARED BILLS</a>";
}

 if(isset($_SESSION['userinfo']['Admission_Works'])){ 
  if($_SESSION['userinfo']['Admission_Works'] == 'yes'){ 
    echo "<a href='searchlistofoutpatientadmited.php?section=Admission&ContinuePatientBilling=ContinuePatientBillingThisPage&from_billing=yes' class='art-button-green'>DISCHARGE PATIENT</a>"; 
    echo "<a href='searchlistofmortuaryadmited.php?section=Admission&ContinuePatientBilling=ContinuePatientBillingThisPage&from=cleared' class='art-button-green'>DISCHARGE MORTUARY</a>"; 
  }
}
?>
<a href='dischargereport.php?section=billing&status=discharge&PatientFile=PatientFileThisPage' class='art-button-green'>
                           PATIENT DISCHARGE REPORT                   
</a>
<?php
// if (isset($_SESSION['userinfo'])) {
//     echo "<a href='patientbillingwork.php?PatientsBillingWorks=PatientsBillingWorks' class='art-button-green'>BACK</a>";
// }

if(isset($_GET['section']) && $_GET['section'] == "Admission") {
?>
<a href='forceadmit.php?section=Admission' class='art-button-green'>BACK</a>
<?php
} else {

    if($from == "cleared") {
        echo "<a href='clearedpatientbillingwork.php?ClearedPatientsBillingWorks=ClearedPatientsBillingWorks&from=cleared' class='art-button-green'>BACK</a>";
    } else {
        if(isset($_GET['from']) && $_GET['from'] == "forceAdmit") {
            echo "<a href='forceadmit' class='art-button-green'>BACK</a>";
        } else if(isset($_GET['from']) && $_GET['from'] == "forceDischarge") {
            echo "<a href='mortuaryforcedischarge.php?from=forceDischarge' class='art-button-green'>BACK</a>";
        } else {
            echo "<a href='patientbillingwork.php?PatientsBillingWorks=PatientsBillingWorks' class='art-button-green'>BACK</a>";
        }
        
        
    }
?>

<!-- <a href='patientbillingwork.php?PatientsBillingWorks=PatientsBillingWorks' class='art-button-green'>BACK</a> -->
<?php
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
<link rel='stylesheet' href='fixHeader.css'>
<?php
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}
?>


<br/><br/>
<fieldset>

    <center>
        <table width=90%>
            <tr>
                <td style="text-align: center;">
                    <input type='text' name='Search_Patient' id='Search_Patient' style="text-align: center;width:20%" oninput='Patient_List_Search()' placeholder=' ~~~~~ Enter Patient Name ~~~~~'>

                    <input type="text" name="Patient_Number" id="Patient_Number" style="text-align: center;width:20%" oninput='Patient_List_Search2()' placeholder=' ~~~~~ Enter Patient Number ~~~~~'>
                        <input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="start_date" placeholder="Start Date"/>
                        <input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="end_date" placeholder="End Date"/>
                    <select id="Sponsor_ID" name="Sponsor_ID" onchange="Filter_Patient_List()"  style="width:20%">
                        <option selected="selected" value="0">All Sponsor</option>
                        <?php
                        $select = mysqli_query($conn,"select Sponsor_ID, Guarantor_Name from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
                        $num = mysqli_num_rows($select);
                        if ($num > 0) {
                            while ($data = mysqli_fetch_array($select)) {
                                echo '<option value="' . $data['Sponsor_ID'] . '">' . $data['Guarantor_Name'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                    <select id="patient_type" onchange="Filter_Patient_List()"   style="width:15%" >
                        <option value="All">All Patient Type</option>
                        <option value="Admitted">Admitted</option>
                        <option value="pending">To Bill</option>
                    </select>
                    <select id="Hospital_Ward_ID" name="Hospital_Ward_ID" onchange="Filter_Patient_List();" style="width:15%">
                        <option selected="selected" value="0">All Ward</option>
                        <?php
                        $select = mysqli_query($conn,"select Hospital_Ward_ID, Hospital_Ward_Name from tbl_hospital_ward order by Hospital_Ward_Name") or die(mysqli_error($conn));
                        $nm = mysqli_num_rows($select);
                        if ($nm > 0) {
                            while ($dt = mysqli_fetch_array($select)) {
                                ?>
                                <option value="<?php echo $dt['Hospital_Ward_ID']; ?>"><?php echo $dt['Hospital_Ward_Name']; ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                    <input type="button" name="Filter" id="Filter" value="FILTER" class="art-button-green" onclick="Patient_List_Search()">
            </tr>
        </table>
    </center>
</fieldset>
<br/>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
                        $('#date_From').datetimepicker({
                            dayOfWeekStart: 1,
                            lang: 'en',
                            startDate: 'now'
                        });
                        $('#date_From').datetimepicker({value: '', step: 5});
                        $('#date_To').datetimepicker({
                            dayOfWeekStart: 1,
                            lang: 'en',
                            startDate: 'now'
                        });
                        $('#date_To').datetimepicker({value: '', step: 5});
</script>


<fieldset style='overflow-y: scroll; height: 400px; background-color:white' id='Patients_Fieldset_List'>
    <legend style="background-color:#006400;color:white" align="right"><b>PENDING BILLS ~ INPATIENTS LIST</b></legend>
    <center>
        <table width =100% border=0 class="table table-condensed fixTableHead">
            <thead>
                <tr style="background-color: #ccc;">
                    <td width="5%"><b>SN</b></td>
                    <td><b>PATIENT NAME</b></td>
                    <td width="10%"><b>PATIENT NUMBER</b></td>
                    <td width="12%"><b>SPONSOR NAME</b></td>
                    <td width="14%"><b>PATIENT AGE</b></td>
                    <td width="9%"><b>GENDER</b></td>
                    <td width="12%"><b>MEMBER NUMBER</b></td>
                    <td width="12%"><b>WARD</b></td>
                    <td width="6%"><b>NO. DAYS</b></td>
                </tr>
            </thead>
            <?php
            $temp = 0;

            $select = mysqli_query($conn,"SELECT ad.Doctor_Status,ad.Discharge_Reason_ID,ad.Admision_ID,ad.Admission_Status,pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, cd.Check_In_ID, hw.Hospital_Ward_Name,TIMESTAMPDIFF(DAY,Admission_Date_Time,NOW()) AS NoOfDay
                                from tbl_admission ad, tbl_check_in_details cd, tbl_patient_registration pr, tbl_sponsor sp, tbl_hospital_ward hw where
                                cd.Admission_ID = ad.Admision_ID and
                                pr.Sponsor_ID = sp.Sponsor_ID and
                                pr.Registration_ID = ad.Registration_ID and
                                hw.Hospital_Ward_ID = ad.Hospital_Ward_ID and
                                pr.Registration_ID=cd.Registration_ID and
                                (ad.Admission_Status = 'pending' or ad.Admission_Status = 'Admitted') and
                                (ad.Discharge_Clearance_Status = 'not cleared' or ad.Discharge_Clearance_Status='pending') GROUP BY Admission_ID order by Admission_ID DESC limit 50 ") or die(mysqli_error($conn));
            $num = mysqli_num_rows($select);
            if ($num > 0) {
                while ($row = mysqli_fetch_array($select)) {
                    //calculate age
                    $date1 = new DateTime($Today);
                    $date2 = new DateTime($row['Date_Of_Birth']);
                    $diff = $date1->diff($date2);
                    $age = $diff->y . " Years, ";
                    $age .= $diff->m . " Months, ";
                    $age .= $diff->d . " Days";
                    $NoOfDay = $row['NoOfDay'];
                   $Check_In_ID=$row['Check_In_ID'];
                   $Discharge_Reason_ID=$row['Discharge_Reason_ID'];
                   $Doctor_Status=$row['Doctor_Status'];
                   $sql_select_discharge_reason_result=mysqli_query($conn,"SELECT discharge_condition FROM tbl_discharge_reason WHERE Discharge_Reason_ID='$Discharge_Reason_ID' AND discharge_condition='dead'") or die(mysqli_error($conn));
                   if(mysqli_num_rows($sql_select_discharge_reason_result)>0){
                     $back_color="red;color:#FFFFFF";  
                   }else{
                     $back_color="green;color:#FFFFFF";
                   }
                   if($Doctor_Status=="initial_pending"&&mysqli_num_rows($sql_select_discharge_reason_result)>0){
                      $back_color="greenyellow;color:#000000"; 
                   }
                   ?>
            <tr id="thead" <?php if($row['Admission_Status']=="pending" ||$Doctor_Status=="initial_pending"){echo "style='background:$back_color;padding:5px;'"; } ?>><td style="width:5%;"><?php echo ++$temp; ?><b>.</b></td>
                <td <?php if($row['Admission_Status']=="pending" ||$Doctor_Status=="initial_pending"){echo "style='background:$back_color;padding:5px;'"; } ?>><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" style="text-decoration: none;"><b><span <?php if($row['Admission_Status']=="pending" ||$Doctor_Status=="initial_pending"){echo "style='background:$back_color;padding:5px;'"; } ?>><?php echo ucwords(strtolower($row['Patient_Name'])); ?></span></b></a></td>
                        <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID=<?php echo $row['Registration_ID']; ?>&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" <?php if($row['Admission_Status']=="pending" ||$Doctor_Status=="initial_pending"){echo "style='background:$back_color;padding:5px; text-decoration: none;'"; } ?>><?php echo $row['Registration_ID']; ?>&</a></td>
                        <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID=<?php echo $row['Registration_ID']; ?>&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" <?php if($row['Admission_Status']=="pending" ||$Doctor_Status=="initial_pending"){echo "style='background:$back_color;padding:5px; text-decoration: none;'"; } ?>><?php echo $row['Guarantor_Name']; ?></a></td>
                        <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID=<?php echo $row['Registration_ID']; ?>&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" <?php if($row['Admission_Status']=="pending" ||$Doctor_Status=="initial_pending"){echo "style='background:$back_color;padding:5px; text-decoration: none;'"; } ?>><?php echo $age; ?></a></td>
                        <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID=<?php echo $row['Registration_ID']; ?>&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" <?php if($row['Admission_Status']=="pending" ||$Doctor_Status=="initial_pending"){echo "style='background:$back_color;padding:5px; text-decoration: none;'"; } ?>><?php echo $row['Gender']; ?></a></td>
                        <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID=<?php echo $row['Registration_ID']; ?>&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" <?php if($row['Admission_Status']=="pending" ||$Doctor_Status=="initial_pending"){echo "style='background:$back_color;padding:5px; text-decoration: none;'"; } ?>><?php echo $row['Member_Number']; ?></a></td>
                        <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID=<?php echo $row['Registration_ID']; ?>&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" <?php if($row['Admission_Status']=="pending" ||$Doctor_Status=="initial_pending"){echo "style='background:$back_color;padding:5px; text-decoration: none;'"; } ?>><?php echo $row['Hospital_Ward_Name']; ?></a></td>
                        <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID=<?php echo $row['Registration_ID']; ?>&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" <?php if($row['Admission_Status']=="pending" ||$Doctor_Status=="initial_pending"){echo "style='background:$back_color;padding:5px; text-decoration: none;'"; } ?>><?php echo $NoOfDay; ?></a></td>
                    </tr>
                    <?php
                    if (($temp % 31) == 0) {
                        // echo $Title;
                    }
                }
            }
            echo "</table>";
            ?>
            </fieldset>
            <div id="Get_Patient_Details" style="width:50%;" >

            </div>

            <div id="Preview_Transaction_Details" style="width:50%;" >

            </div>

            <link rel="stylesheet" href="css/select2.min.css" media="screen">
            <script src="media/js/jquery.js" type="text/javascript"></script>
            <script src="css/jquery-ui.js"></script>
            <script src="js/select2.min.js"></script>

            <script>
                                    $(document).ready(function () {
                                        $("#Get_Patient_Details").dialog({autoOpen: false, width: "90%", height: 630, title: 'INPATIENT BILLING DETAILS', modal: true});
                                        $("select").select2();
                                    });
            </script>

            <script>
                $(document).ready(function () {
                    $("#Preview_Transaction_Details").dialog({autoOpen: false, width: "75%", height: 450, title: 'TRANSACTION DETAILS', modal: true});
                });
            </script>

            <script>
                function Preview_Patient_Details(Check_In_ID) {
                    if (window.XMLHttpRequest) {
                        myObjectPreview = new XMLHttpRequest();
                    } else if (window.ActiveXObject) {
                        myObjectPreview = new ActiveXObject('Micrsoft.XMLHTTP');
                        myObjectPreview.overrideMimeType('text/xml');
                    }

                    myObjectPreview.onreadystatechange = function () {
                        data2000 = myObjectPreview.responseText;
                        if (myObjectPreview.readyState == 4) {
                            document.getElementById("Get_Patient_Details").innerHTML = data2000;
                            $("#Get_Patient_Details").dialog("open");
                        }
                    }; //specify name of function that will handle server response........
                    myObjectPreview.open('GET', 'Preview_Patient_Bill_Details.php?Check_In_ID=' + Check_In_ID, true);
                    myObjectPreview.send();
                }
            </script>

            <script type="text/javascript">
                function View_Details(Patient_Payment_ID, Patient_Payment_Item_List_ID) {
                    if (window.XMLHttpRequest) {
                        myObjectViewDetails = new XMLHttpRequest();
                    } else if (window.ActiveXObject) {
                        myObjectViewDetails = new ActiveXObject('Micrsoft.XMLHTTP');
                        myObjectViewDetails.overrideMimeType('text/xml');
                    }

                    myObjectViewDetails.onreadystatechange = function () {
                        data = myObjectViewDetails.responseText;
                        if (myObjectViewDetails.readyState == 4) {
                            document.getElementById("Preview_Transaction_Details").innerHTML = data;
                            $("#Preview_Transaction_Details").dialog("open");
                        }
                    }; //specify name of function that will handle server response........
                    myObjectViewDetails.open('GET', 'Preview_Transaction_Details.php?Patient_Payment_ID=' + Patient_Payment_ID + '&Patient_Payment_Item_List_ID=' + Patient_Payment_Item_List_ID, true);
                    myObjectViewDetails.send();
                }
            </script>
            <script>
                function Patient_List_Search() {
                    var Patient_Name = document.getElementById("Search_Patient").value;
                    var Sponsor_ID = document.getElementById("Sponsor_ID").value;
                    var Hospital_Ward_ID = document.getElementById("Hospital_Ward_ID").value;
                    var patient_type = document.getElementById("patient_type").value;
                    var start_date = document.getElementById("start_date").value;
                    var end_date = document.getElementById("end_date").value;
                    document.getElementById("Patient_Number").value = '';

                    if (window.XMLHttpRequest) {
                        myObjectSearchPatient = new XMLHttpRequest();
                    } else if (window.ActiveXObject) {
                        myObjectSearchPatient = new ActiveXObject('Micrsoft.XMLHTTP');
                        myObjectSearchPatient.overrideMimeType('text/xml');
                    }
                    myObjectSearchPatient.onreadystatechange = function () {
                        data28 = myObjectSearchPatient.responseText;
                        if (myObjectSearchPatient.readyState == 4) {
                            document.getElementById('Patients_Fieldset_List').innerHTML = data28;
                        }
                    }; //specify name of function that will handle server response........

                    myObjectSearchPatient.open('GET', 'Patient_Billing_List.php?Patient_Name=' + Patient_Name + '&Sponsor_ID=' + Sponsor_ID + '&Hospital_Ward_ID=' + Hospital_Ward_ID + '&patient_type=' + patient_type + '&start_date=' + start_date + '&end_date=' + end_date, true);
                    myObjectSearchPatient.send();
                }
            </script>

            <script>
                function Patient_List_Search2() {
                    var Patient_Number = document.getElementById("Patient_Number").value;
                    var Sponsor_ID = document.getElementById("Sponsor_ID").value;
                    var Hospital_Ward_ID = document.getElementById("Hospital_Ward_ID").value;
                    var start_date = document.getElementById("start_date").value;
                    var end_date = document.getElementById("end_date").value;
                    document.getElementById("Search_Patient").value = '';

                    if (window.XMLHttpRequest) {
                        myObjectSearchPatient2 = new XMLHttpRequest();
                    } else if (window.ActiveXObject) {
                        myObjectSearchPatient2 = new ActiveXObject('Micrsoft.XMLHTTP');
                        myObjectSearchPatient2.overrideMimeType('text/xml');
                    }
                    myObjectSearchPatient2.onreadystatechange = function () {
                        data1 = myObjectSearchPatient2.responseText;
                        if (myObjectSearchPatient2.readyState == 4) {
                            document.getElementById('Patients_Fieldset_List').innerHTML = data1;
                        }
                    }; //specify name of function that will handle server response........

                    myObjectSearchPatient2.open('GET', 'Patient_Billing_List.php?Patient_Number=' + Patient_Number + '&Sponsor_ID=' + Sponsor_ID + '&Hospital_Ward_ID=' + Hospital_Ward_ID + '&start_date=' + start_date + '&end_date=' + end_date, true);
                    myObjectSearchPatient2.send();
                }
            </script>

            <script type="text/javascript">
                function Sort_Mode(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Registration_ID) {
                    var Receipt_Mode = document.getElementById("Receipt_Mode").value;
                    var Transaction_Type = document.getElementById("Transaction_Type").value;

                    if (window.XMLHttpRequest) {
                        myObjectMode = new XMLHttpRequest();
                    } else if (window.ActiveXObject) {
                        myObjectMode = new ActiveXObject('Micrsoft.XMLHTTP');
                        myObjectMode.overrideMimeType('text/xml');
                    }
                    myObjectMode.onreadystatechange = function () {
                        data28812 = myObjectMode.responseText;
                        if (myObjectMode.readyState == 4) {
                            document.getElementById('Transaction_Items_Details').innerHTML = data28812;
                        }
                    }; //specify name of function that will handle server response........

                    myObjectMode.open('GET', 'Sort_Mode_Display.php?Patient_Bill_ID=' + Patient_Bill_ID + '&Folio_Number=' + Folio_Number + '&Sponsor_ID=' + Sponsor_ID + '&Check_In_ID=' + Check_In_ID + '&Receipt_Mode=' + Receipt_Mode + '&Transaction_Type=' + Transaction_Type + '&Registration_ID=' + Registration_ID, true);
                    myObjectMode.send();
                }
            </script>

            <script type="text/javascript">
                function Filter_Patient_List() {
                    var Sponsor_ID = document.getElementById("Sponsor_ID").value;
                    var Hospital_Ward_ID = document.getElementById("Hospital_Ward_ID").value;
                    var patient_type = document.getElementById("patient_type").value;
                    var end_date = document.getElementById("end_date").value;
                    var start_date = document.getElementById("start_date").value;
                    
                    document.getElementById("Patient_Number").value = '';
                    document.getElementById("Search_Patient").value = '';

                    if (window.XMLHttpRequest) {
                        myObjectFilter = new XMLHttpRequest();
                    } else if (window.ActiveXObject) {
                        myObjectFilter = new ActiveXObject('Micrsoft.XMLHTTP');
                        myObjectFilter.overrideMimeType('text/xml');
                    }
                    myObjectFilter.onreadystatechange = function () {
                        data28349 = myObjectFilter.responseText;
                        if (myObjectFilter.readyState == 4) {
                            document.getElementById('Patients_Fieldset_List').innerHTML = data28349;
                        }
                    }; //specify name of function that will handle server response........
                    myObjectFilter.open('GET', 'Patient_Billing_List.php?Sponsor_ID=' + Sponsor_ID + '&Hospital_Ward_ID=' + Hospital_Ward_ID + '&patient_type=' + patient_type + '&start_date=' + start_date + '&end_date=' + end_date, true);
                    myObjectFilter.send();
                }
            </script>

            <script type="text/javascript">
                function Display_Transaction(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID) {

                }
            </script>

            <script type="text/javascript">
                $(document).ready(function () {
                    $('#patients-list').DataTable({
                        "bJQueryUI": true
                    });

                    $('#start_date').datetimepicker({
                        dayOfWeekStart: 1,
                        changeMonth: true,
                        changeYear: true,
                        showWeek: true,
                        showOtherMonths: true,
                        lang: 'en',
                        //startDate:    'now'
                    });
                    $('#start_date').datetimepicker({value: '', step: 1});
                    $('#end_date').datetimepicker({
                        dayOfWeekStart: 1,
                        changeMonth: true,
                        changeYear: true,
                        showWeek: true,
                        showOtherMonths: true,
                        lang: 'en',
                        //startDate:'now'
                    });
                    $('#end_date').datetimepicker({value: '', step: 1});
                });
            </script>
            
            </script>

    <link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
    <link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
    <script src="media/js/jquery.js" type="text/javascript"></script>
    <script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
    <script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
    <script src="css/jquery-ui.js"></script>
            <br/>
            <?php
            include("./includes/footer.php");
            ?>