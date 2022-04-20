<?php
include("./includes/header.php");
include("./includes/connection.php");
echo "<link rel='stylesheet' href='fixHeader.css'>";
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

$from = "";
if(isset($_GET['from']) && $_GET['from'] == "cleared") {
    $from = $_GET['from'];
}

$from = "";
if(isset($_GET['from']) && $_GET['from'] == "forceDischarge") {
    $from = $_GET['from'];
}

?>
<?php
if (isset($_SESSION['userinfo'])) {
    echo "<a href='billingwork.php?BillingWork=BillingWorkThisPage&from=cleared' class='art-button-green'>PENDING BILLS</a>";
}
?>
<?php
if (isset($_SESSION['userinfo'])) {
    if($from == "forceDischarge") {
        echo "<a href='billingwork.php?from=forceDischarge&BillingWork=BillingWorkThisPage' class='art-button-green'>BACK</a>";
    } else {
        echo "<a href='patientbillingwork.php?PatientsBillingWorks=PatientsBillingWorks' class='art-button-green'>BACK</a>";
    }
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
    <table width="90%">
        <tr>
            <td width="10%" style="text-align: right;">Start Date</td>
            <td width="20%">
                <input type='text' name='Date_From' id='date_From' style="text-align: center;" value="<?php echo $original_Date; ?>" autocomplete='off' style="text-align: center;"readonly="readonly">
            </td>
            <td width="10%" style="text-align: right;">End Date</td>
            <td width="20%">
                <input type='text' name='Date_To' id='date_To' style="text-align: center;" value="<?php echo $original_Date; ?>" autocomplete='off'readonly="readonly">
            </td>
            <td width="10%" style="text-align: right;">Sponsor Name</td>
            <td>
                <!-- <select id="Sponsor_ID" name="Sponsor_ID" onchange="Filter_Patient_List()"> -->
                <select id="Sponsor_ID" name="Sponsor_ID">
                    <option selected="selected" value="0">All</option>
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
            </td>
            <td style="text-align: right;" width="10%">Ward Name</td>
            <td width="10%">
                <!-- <select id="Hospital_Ward_ID" name="Hospital_Ward_ID" onchange="Filter_Patient_List();"> -->
                <select id="Hospital_Ward_ID" name="Hospital_Ward_ID">
                    <option selected="selected" value="0">All</option>
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
            </td>
            <td width="9%" style="text-align: right;">
                <input type="button" name="Filter" id="Filter" value="FILTER" class="art-button-green" onclick="Patient_List_Search()">
            </td>
        </tr>
        <tr>
            <td width="18%" colspan="3">
                <input type='text' name='Search_Patient' id='Search_Patient' style="text-align: center;" oninput='Patient_List_Search()' onkeyup='Patient_List_Search()' placeholder=' ~~~~~ Enter Patient Name ~~~~~' autocomplete='off'>
            </td>
            <td width="18%" colspan="3">
                <input type="text" name="Patient_Number" id="Patient_Number" style="text-align: center;" oninput='Patient_List_Search2()' onkeyup='Patient_List_Search2()' placeholder=' ~~~~~ Enter Patient Number ~~~~~' autocomplete='off'>
            </td>
            <td width="18%" colspan="3">
                <input type="text" name="Patient_Number" id="Patient_Number" style="text-align: center;" oninput='Patient_List_Search2()' onkeyup='Patient_List_Search2()' placeholder=' ~~~~~ Enter Patient Phone Number ~~~~~' autocomplete='off' readonly="readonly">
            </td>
        </tr>
    </table>
</center>
</fieldset>
<!-- <br/> -->

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
<?php
$can_revoke = $_SESSION['userinfo']['can_revk_bill_status'];

$Title = '<thead>
                <tr style="background-color: #ccc;">
                    <td width="2%"><b>SN</b></td>
                    <td><b>PATIENT NAME</b></td>
                    <td width="7%"><b>PATIENT #</b></td>
                    <td width="11%"><b>SPONSOR NAME</b></td>
                    <td width="11%"><b>PATIENT AGE</b></td>
                    <td width="7%"><b>GENDER</b></td>
                    <td width="8%"><b>MEMBER NUMBER</b></td>
                    <td width="10%"><b>CASH CLEARED BY</b></td>
                    <td width="10%"><b>CREDIT CLEARED BY</b></td>
                    <td><b>WARD</b></td>
                    <td><b>DATE CLEARED</b></td>';

if ($can_revoke == 'yes') {
    $Title .= '<td width="">&nbsp;</td>';
}

$Title .= '</tr></thead>';
?>
<fieldset style='overflow-y: scroll; height: 400px; background-color:white' id='Patients_Fieldset_List'>
    <legend style="background-color:#006400;color:white" align="right"><b>CLEARED BILLS ~ INPATIENTS LIST</b></legend>
    <center>
        <table width =100% border=0 class="fixTableHead">

            <?php
            $temp = 0;
            echo $Title;
            $select = mysqli_query($conn,"SELECT ad.Cash_Clearer_ID,ad.Credit_Clearer_ID,ad.Admision_ID,pr.Patient_Name, Clearance_Date_Time,pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, cd.Check_In_ID, hw.Hospital_Ward_Name
                                from tbl_admission ad, tbl_check_in_details cd, tbl_patient_registration pr, tbl_sponsor sp, tbl_hospital_ward hw where
                                cd.Admission_ID = ad.Admision_ID and
                                pr.Sponsor_ID = sp.Sponsor_ID and
                                pr.Registration_ID = ad.Registration_ID and
                                hw.Hospital_Ward_ID = ad.Hospital_Ward_ID and
                                (ad.Admission_Status = 'Discharged' or ad.Discharge_Clearance_Status = 'cleared') order by Clearance_Date_Time desc limit 10") or die(mysqli_error($conn));
            $num = mysqli_num_rows($select);
            if ($num > 0) {
                while ($row = mysqli_fetch_array($select)) {
                    $Cash_Clearer_ID=$row['Cash_Clearer_ID'];
                    $Credit_Clearer_ID=$row['Credit_Clearer_ID'];
                    $Clearance_Date_Time=$row['Clearance_Date_Time'];

                    $sql_select_who_clear_bill_result=mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Credit_Clearer_ID'") or die(mysqli_error($conn));
                    if(mysqli_num_rows($sql_select_who_clear_bill_result)>0){
                        $clear_by_row=mysqli_fetch_assoc($sql_select_who_clear_bill_result);
                        $credit_cleared_by=$clear_by_row['Employee_Name'];
                    }else{
                        $credit_cleared_by="";
                    }
                    $sql_select_who_clear_bill_result2=mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Cash_Clearer_ID'") or die(mysqli_error($conn));
                    if(mysqli_num_rows($sql_select_who_clear_bill_result2)>0){
                        $clear_by_row2=mysqli_fetch_assoc($sql_select_who_clear_bill_result2);
                        $cash_cleared_by=$clear_by_row2['Employee_Name'];
                    }else{
                        $cash_cleared_by="";
                    }
                    //calculate age
                    $date1 = new DateTime($Today);
                    $date2 = new DateTime($row['Date_Of_Birth']);
                    $diff = $date1->diff($date2);
                    $age = $diff->y . " Years, ";
                    $age .= $diff->m . " Months, ";
                    $age .= $diff->d . " Days";
                    $check_in_id = $row['Check_In_ID'];
                    $Registration_ID=$row['Registration_ID'];
                    $delvrystatus = mysqli_fetch_assoc(mysqli_query($conn,"SELECT bl.e_bill_delivery_status FROM tbl_bills bl JOIN tbl_patient_payments pp ON bl.Bill_ID=pp.Bill_ID WHERE pp.Check_In_ID='$check_in_id' LIMIT 1"))['e_bill_delivery_status'];
                    ?>
                    <tr id="thead"><td style="width:5%;"><?php echo ++$temp; ?><b>.</b></td>
                        <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Status=cld&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" style="text-decoration: none;"><?php echo ucwords(strtolower($row['Patient_Name'])); ?></a></td>
                        <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID =<?php echo $Registration_ID?>&Status=cld&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" style="text-decoration: none;"><?php echo $row['Registration_ID']; ?></a></td>
                        <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID =<?php echo $Registration_ID?>&Status=cld&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" style="text-decoration: none;"><?php echo $row['Guarantor_Name']; ?></a></td>
                        <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID =<?php echo $Registration_ID?>&Status=cld&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" style="text-decoration: none;"><?php echo $age; ?></a></td>
                        <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID =<?php echo $Registration_ID?>&Status=cld&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" style="text-decoration: none;"><?php echo $row['Gender']; ?></a></td>
                        <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID =<?php echo $Registration_ID?>&Status=cld&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" style="text-decoration: none;"><?php echo $row['Member_Number']; ?></a></td>
                        <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID =<?php echo $Registration_ID?>&Status=cld&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" style="text-decoration: none;"><?php echo $cash_cleared_by ?></a></td>
                        <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID =<?php echo $Registration_ID?>&Status=cld&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" style="text-decoration: none;"><?php echo $credit_cleared_by?></a></td>
                        <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID =<?php echo $Registration_ID?>&Status=cld&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" style="text-decoration: none;"><?php echo $row['Hospital_Ward_Name']; ?></a></td>
                        <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID =<?php echo $Registration_ID?>&Status=cld&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" style="text-decoration: none;"><?php echo $Clearance_Date_Time; ?></a></td>
                        <?php
                        if ($can_revoke == 'yes') {
                            if ($delvrystatus == "1") {
                                ?>
                                <td></td>
                                <?php } else {
                                ?>
                                <td><button  type="button" class="art-button-green" onclick="unclearbill('<?php echo $row['Admision_ID']; ?>')">UN CLEAR BILL</button></td>   
                                <?php
                            }
                        }
                        ?>
                    </tr>
                    <?php
                    if (($temp % 31) == 0) {
                        echo $Title;
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

            <script src="js/jquery-1.8.0.min.js"></script>
            <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
            <script src="script.js"></script>
            <link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
            <script src="script.responsive.js"></script>

            <script>
                    $(document).ready(function () {
                        $("#Get_Patient_Details").dialog({autoOpen: false, width: "90%", height: 630, title: 'INPATIENT BILLING DETAILS', modal: true});
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
                    var Start_Date = document.getElementById("date_From").value;
                    var End_Date = document.getElementById("date_To").value;
                    document.getElementById("Patient_Number").value = '';
                    document.getElementById('Patients_Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

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

                    myObjectSearchPatient.open('GET', 'Cleared_Patient_Billing_List.php?Patient_Name=' + Patient_Name + '&Sponsor_ID=' + Sponsor_ID + '&Hospital_Ward_ID=' + Hospital_Ward_ID + '&Start_Date=' + Start_Date + '&End_Date=' + End_Date, true);
                    myObjectSearchPatient.send();
                }
            </script>

            <script>
                function Patient_List_Search2() {
                    var Patient_Number = document.getElementById("Patient_Number").value;
                    var Sponsor_ID = document.getElementById("Sponsor_ID").value;
                    var Hospital_Ward_ID = document.getElementById("Hospital_Ward_ID").value;
                    var Start_Date = document.getElementById("date_From").value;
                    var End_Date = document.getElementById("date_To").value;
                    document.getElementById("Search_Patient").value = '';
                    document.getElementById('Patients_Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

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

                    myObjectSearchPatient2.open('GET', 'Cleared_Patient_Billing_List.php?Patient_Number=' + Patient_Number + '&Sponsor_ID=' + Sponsor_ID + '&Hospital_Ward_ID=' + Hospital_Ward_ID + '&Start_Date=' + Start_Date + '&End_Date=' + End_Date, true);
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
                    var Start_Date = document.getElementById("date_From").value;
                    var End_Date = document.getElementById("date_To").value;
                    document.getElementById("Patient_Number").value = '';
                    document.getElementById("Search_Patient").value = '';
                    document.getElementById('Patients_Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

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
                    myObjectFilter.open('GET', 'Cleared_Patient_Billing_List.php?Sponsor_ID=' + Sponsor_ID + '&Hospital_Ward_ID=' + Hospital_Ward_ID + 'Start_Date=' + Start_Date + '&End_Date=' + End_Date, true);
                    myObjectFilter.send();
                }
            </script>

            <script type="text/javascript">
                function Display_Transaction(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID) {

                }
            </script>
            <script>
                function unclearbill(admitionid) {
                    if (confirm("Are you sure you want to return this patient to billing?")) {
                        $.ajax({
                            type: 'POST',
                            url: 'returnpatienttobilling.php',
                            data: 'action=returntobilling&admision_ID=' + admitionid,
                            cache: false,
                            beforeSend: function (xhr) {

                            },
                            success: function (result) {
                                if (parseInt(result) == 1) {
                                    document.location.reload();
                                } else {
                                    alertMsg('An error has occured.Try again later', "Internal Error", 'error', 0, false, 3000, "right + 20,top + 20", true, false, false, 0, false);
                                }
                            }, complete: function (jqXHR, textStatus) {
                            }, error: function (jqXHR, textStatus, errorThrown) {
                                alertMsg(errorThrown, "Interna Error", 'error', 0, false, 3000, "right + 20,top + 20", true, false, false, 0, false);
                            }
                        });
                    }
                }
            </script>
            <br/>
            <?php
            include("./includes/footer.php");
            ?>