<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Dialysis_Works'])) {
        if ($_SESSION['userinfo']['Dialysis_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } else {
            @session_start();
            if (!isset($_SESSION['Dialysis_Supervisor'])) {
                header("Location: ./deptsupervisorauthentication.php?SessionCategory=Dialysis&InvalidSupervisorAuthentication=yes");
            }
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

//get the current date
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}
 //get today's date
 $sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
 while($date = mysqli_fetch_array($sql_date_time)){
     $Current_Date_Time = $date['Date_Time'];
 }
 $Filter_Value = substr($Current_Date_Time,0,11);
 $startoriginal = $Filter_Value.' 00:00';
 $End_Date = $Current_Date_Time;

$Attendance_Date = '';
$Consultant_employee = $_SESSION['userinfo']["Employee_ID"];
$initianame = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Consultant_employee'"))['Employee_Name'];
//    select patient information
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
    $select_Patient = mysqli_query($conn,"select
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID,
                                                        pr.Employee_ID,Registration_Date_And_Time,Guarantor_Name,Claim_Number_Status,
                                                        Registration_ID
                                      from tbl_patient_registration pr,tbl_sponsor sp,tbl_employee em 
                                        where pr.Sponsor_ID = sp.Sponsor_ID and 
                                        Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) {
            $Registration_ID = $row['Registration_ID'];
            $Old_Registration_Number = $row['Old_Registration_Number'];
            $Title = $row['Title'];
            $Patient_Name = $row['Patient_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $Region = $row['Region'];
            $District = $row['District'];
            $Ward = $row['Ward'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Claim_Number_Status = $row['Claim_Number_Status'];
            $Member_Number = $row['Member_Number'];
            $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
            $Phone_Number = $row['Phone_Number'];
            $Email_Address = $row['Email_Address'];
            $Occupation = $row['Occupation'];
            $Employee_Vote_Number = $row['Employee_Vote_Number'];
            $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
            $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
            $Company = $row['Company'];
            $Employee_ID = $row['Employee_ID'];
            $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
            // echo $Ward."  ".$District."  ".$Ward; exit;
        }

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days.";
    } else {
        $Registration_ID = '';
        $Old_Registration_Number = '';
        $Title = '';
        $Patient_Name = '';
        $Sponsor_ID = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Region = '';
        $District = '';
        $Ward = '';
        $Guarantor_Name = '';
        $Claim_Number_Status = '';
        $Member_Number = '';
        $Member_Card_Expire_Date = '';
        $Phone_Number = '';
        $Email_Address = '';
        $Occupation = '';
        $Employee_Vote_Number = '';
        $Emergence_Contact_Name = '';
        $Emergence_Contact_Number = '';
        $Company = '';
        $Employee_ID = '';
        $Registration_Date_And_Time = '';
        $age = 0;
    }
} else {
    $Registration_ID = '';
    $Old_Registration_Number = '';
    $Title = '';
    $Sponsor_ID = '';
    $Date_Of_Birth = '';
    $Gender = '';
    $Region = '';
    $District = '';
    $Ward = '';
    $Guarantor_Name = '';
    $Claim_Number_Status = '';
    $Member_Number = '';
    $Member_Card_Expire_Date = '';
    $Phone_Number = '';
    $Email_Address = '';
    $Occupation = '';
    $Employee_Vote_Number = '';
    $Emergence_Contact_Name = '';
    $Emergence_Contact_Number = '';
    $Company = '';
    $Employee_ID = '';
    $Registration_Date_And_Time = '';
    $age = 0;
}


if (isset($_GET['Payment_Item_Cache_List_ID'])) {

    $Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
} else {
    $Payment_Item_Cache_List_ID = '';
}

if (isset($_GET['Patient_Payment_ID'])) {

    $Payment_Cache_ID = $_GET['Patient_Payment_ID'];
    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
} else {
    $Payment_Cache_ID = '';
    $Patient_Payment_ID = '';
    
}

if (!empty($_GET['consultation_id'])) {

    $consultation_id = $_GET['consultation_id'];
} else {
    $check_consultation=mysqli_query($conn,"SELECT consultation_ID FROM tbl_consultation  WHERE Registration_ID='$Registration_ID' order by consultation_ID desc limit 1");
    $consultation_id =mysqli_fetch_assoc($check_consultation)['consultation_ID'] ;
}
// echo $consultation_id;
$payment_status = mysqli_query($conn,"SELECT 'cache' as Status_From,Patient_Payment_ID,pc.Payment_Cache_ID,pl.Priority,pc.Billing_Type,i.Product_Name as item_name,i.Item_ID as item_id,e.Employee_Name as Employee,pr.*,pl.Payment_Item_Cache_List_ID as Patient_Payment_Item_List_ID,pl.Status as Status,pl.Transaction_Type as transaction,payment_type,Require_Document_To_Sign_At_receiption "
        . "from tbl_sponsor sp,tbl_items as i,tbl_employee as e,tbl_patient_registration as pr,tbl_payment_cache as pc ,tbl_item_list_cache as pl \n"
        . "WHERE pc.Payment_Cache_ID =pl.Payment_Cache_ID \n"
        . "AND pr.Registration_ID =pc.Registration_ID \n"
        . "AND e.Employee_ID =pc.Employee_ID \n"
        . "AND i.Item_ID =pl.Item_ID \n"
        . "AND sp.Sponsor_ID=pc.Sponsor_ID "
        . "AND Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'");
while ($rows = mysqli_fetch_assoc($payment_status)) {

    $Item_ID = $rows['Item_ID'];
    $billing_Type = strtolower($rows['Billing_Type']);
    $status = strtolower($rows['Status']);
    $transaction_Type = strtolower($rows['transaction']);
    $payment_type = strtolower($rows['payment_type']);
    $Patient_Payment_Item_List_ID = $rows['Patient_Payment_Item_List_ID'];
    $Payment_Cache_ID_og = $rows['Patient_Payment_ID'];
    $finance_department_id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT finance_department_id FROM tbl_patient_payment_item_list WHERE Patient_Payment_ID='$Payment_Cache_ID_og' GROUP BY Patient_Payment_ID"))['finance_department_id'];


    if (($billing_Type == 'outpatient cash' && $status == 'active' && $transaction_Type == "cash")) {
        $payStatus = 'Not paid';
    } elseif (($billing_Type == 'outpatient cash' && $status == 'active' && $transaction_Type == "credit")) {
        $payStatus = 'Not Billed';
    } elseif (($billing_Type == 'outpatient credit' && $status == 'active') && $rows['Require_Document_To_Sign_At_receiption'] == 'Mandatory') {
        $payStatus = 'Not Approved';
    } elseif (($billing_Type == 'outpatient credit' && $status == 'active' && $transaction_Type == "cash")) {
        $payStatus = 'Not paid';
    } elseif (($billing_Type == 'inpatient cash' && $status == 'active') || ($billing_Type == 'inpatient credit' && $status == 'active' && $transaction_Type == "cash")) {

        if ($pre_paid == '1') {
            $payStatus = 'Not paid';
        } else {
            if ($payment_type == 'pre' && $status == 'active') {
                $payStatus = 'Not paid';
            } else {
                $payStatus = 'Not Billed';
            }
        }
    } elseif (($billing_Type == 'outpatient cash' && $status == 'paid') || ($billing_Type == 'outpatient credit' && $status == 'paid' && $transaction_Type == "cash")) {
        $payStatus = 'Paid';
    } elseif (($billing_Type == 'inpatient cash' && $status == 'paid') || ($billing_Type == 'inpatient credit' && $status == 'paid' && $transaction_Type == "cash")) {
        $payStatus = 'Paid';
    } else {
        if ($payment_type == 'pre') {
            $payStatus = 'Not paid';
        } else {
            $payStatus = 'Not Billed';
        }
    }
}

$Today = date('Y-m-d');
// $dialysis_details_ID = 0;

if (isset($_GET['Attendance_Date'])){
    $Attendance_Date = $_GET['Attendance_Date'];
?>
<a href="dialysisclinicalnotes_Dates.php?Registration_ID=<?= $_GET['Registration_ID'];?>" class="art-button-green">BACK</a>
<?php
}else{
 ?>
<a href="dialysiscashpatientlist.php?DialysisClinicalnotes=DialysisClinicalnotesThispage" class="art-button-green">BACK</a>
<?php
    // $Attendance_Date = $Today;
}
$Registration_ID = $_GET['Registration_ID'];
?>

<!-- @dnm -->

<!-- <a href="" class="art-button-green">PATIENT FILE</a> -->
<a href="#" onClick='$("#showIncidentRecord").dialog("open")' class="art-button-green">INCIDENT RECORD</a>
<a href="#" onClick='$("#showTempNeckLine").dialog("open")' class="art-button-green">TEMPORARY NECK LINE</a>
<a href="#" onClick='$("#patientresultsummary").dialog("open")' class="art-button-green">PATIENT RESULT SUMMARY</a>
<a href="#" onClick='$("#showMonthlyRounds").dialog("open")' class="art-button-green">MONTHLY HEMODIALYSIS ROUNDS</a>


<div id="showdataConsult" style="width:100%;overflow-x:hidden;height:520px;display:none;overflow-y:scroll">
    <div id="myConsult"> </div>
</div>



<center>
    <form action="#" method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data"> 
        <fieldset><legend align='right'><b>Dialysis Works</b></legend>
            <table width=100%>
                <tr>
                    <td><b>Patient Name</b></td><td><input type="text" name="" readonly='readonly' value='<?php
                        if (isset($Patient_Name)) {
                            echo $Patient_Name;
                        }
                        ?>' id=""></td>
                    <td><b>Visit Date</b></td><td><input type="text" name="" id="" readonly='readonly' value='<?php
                        if (isset($Transaction_Date_And_Time)) {
                            echo $Transaction_Date_And_Time;
                        }
                        ?>'></td>
                </tr>
                <tr>
                    <td><b>Patient Number</b></td><td><input type="text" name="" readonly='readonly' value='<?php
                        if (isset($Registration_ID)) {
                            echo $Registration_ID;
                        }
                        ?>' id="" ></td>
                    <td><b>Gender</b></td><td><input type="text" name="" id="" readonly='readonly' value='<?php
                        if (isset($Gender)) {
                            echo $Gender;
                        }
                        ?>' ></td></td>
                </tr>
                <tr>
                    <td><b>Sponsor</b></td><td><input type="text" name="" id="" readonly='readonly' value='<?php
                        if (isset($Guarantor_Name)) {
                            echo $Guarantor_Name;
                        }
                        ?>' ></td>
                    <td><b>Age</b></td><td><input type="text" name="" id="" readonly='readonly' value='<?php
                        if (isset($age)) {
                            echo $age;
                        }
                        ?>' ></td></td>
                </tr>
                <tr>
                    <td><b>Doctor</b></td><td><input type="text" name="" id="" readonly='readonly' value='<?php
                        if (isset($Consultant)) {
                            if ($Patient_Direction == 'Direct To Clinic') {
                                echo $_SESSION['userinfo']['Employee_Name'];
                            } else {
                                echo $Consultant;
                            }
                        }
                        ?>' ></td>
                    <td>&nbsp;</td><td>
                        <input type='hidden' id='recentConsultaionTyp' value=''>
                        <a href="#" onclick="verify_if_this_user_login_to_dispencing()" id="Add_Consumable_Phamacetical" class="art-button-green" > ADD CONSUMABLE AND PHAMACETICAL</a>
                        <input type="button" name="showItems" value="Order" class="art-button-green" id="showItems" onclick="addItems('<?php echo $Registration_ID; ?>')"/>
                        <img src="images/ajax-loader_1.gif" style="border-color:white;display:none;" id="verifyprogress">
                        <!-- @dnm -->
                        <?php $total_prescriptions = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(prescription_id) AS total_prescriptions FROM tbl_dialysis_inpatient_prescriptions WHERE Registration_ID='$Registration_ID' AND status='Not Done'"))['total_prescriptions']; ?>
                        <span  class="btn btn-primary btn-sm pull-right" onclick='$("#previousHemodialysisPrescriptions").dialog("open");filterPreviousHemodialysisPrescriptions();' >Dialysis Order(<b style="color:red;font-size:15px; font-weight:bold;" id="totalPrescriptionsHTML"> <?=$total_prescriptions?> </b>)</span>
                        <input type="hidden" value="<?=$Registration_ID?>" id="P_ID">
                        <!-- //dnm -->
                    </td>
                </tr>
            </table>

    </form>
</fieldset>
<?php include('dialysis_add_consumable.php');?>
<!-- @dnm -->
                <div id="previousHemodialysisPrescriptions" style="width:100%;display:none;">
                    <?php 
                    $toDate = date('Y-m-d');
                    $getStartDate = mysqli_fetch_assoc(mysqli_query($conn,"SELECT DATE_SUB('$toDate', INTERVAL 1 MONTH) AS start_date"))['start_date']; 
                    $startDate=date('Y/m/d', strtotime($getStartDate)).' '.date('H:m');
                    $endDate = date('Y/m/d H:m');
                    include("patient_demografic.php");
                    include("doctor_oder_form.php");
                    ?>
<!--                    <table class="table" style="background-color:#fff;">
                        <tr>
                            <td></td>
                            <td width="20%"><input type="text" autocomplete="off" readonly style='text-align:center;display:inline;' placeholder="Start Date" title="Start Date" id="prev_dh_start_date" value="<?=$startDate?>"></td>
                            <td width="20%"><input type="text" autocomplete="off" readonly style='text-align:center;display:inline;' placeholder="End Date" title="End Date" id="prev_dh_end_date" value="<?=$endDate?>"></td>
                            <td width="20%">
                                <center><input type="button" onclick="filterPreviousHemodialysisPrescriptions()" class="art-button-green" value="FILTER PRESCRIPTIONS"></center>
                            </td>
                            <td></td>
                        </tr>
                    </table>-->

<!--                    <div align="center" style="display: none" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>

                    <div id="previousHemodialysisPrescriptionsHTML" style="overflow-x:scroll; height:470px; overflow-y:scroll">
                        AJAX data  
                    </div>
                    <table class="table" style="background-color:#fff;">
                        <tr>
                            <td>
                                <input type="button" onclick='$("#previousHemodialysisPrescriptions").dialog("close");' class="art-button-green pull-right" value="CLOSE">
                            </td>
                        </tr>
                    </table>-->
                </div>

                <script>
                    function count_prescriptions() {
                        var Registration_ID = $('#P_ID').val();
                        var urlDt = 'Registration_ID=' + Registration_ID;
                        $.ajax({
                            type: 'GET',
                            url: 'save_dialysis_prescription.php',
                            data: urlDt,
                            success: function (html) {
                                if (html != '') {
                                    $("#totalPrescriptionsHTML").html(html);
                                }
                            }
                        });
                        
                    }
                    function filterPreviousHemodialysisPrescriptions() {
                        var prev_dh_start_date = $('#prev_dh_start_date').val();
                        var prev_dh_end_date = $('#prev_dh_end_date').val();
                       
                        if(prev_dh_start_date==''&&prev_dh_end_date==''){
                            alert('Please set start/end date.');
                            return false;
                        }
                        var Registration_ID = $('#P_ID').val();
                        var urlData = 'Registration_ID=' + Registration_ID + '&prev_dh_start_date=' + prev_dh_start_date + '&prev_dh_end_date=' + prev_dh_end_date+'&showActionBTN';
                        // alert(urlData);

                        $.ajax({
                            type: 'GET',
                            url: 'ajax_dialysis_prescriptions.php',
                            data: urlData,
                            cache: false,
                            beforeSend: function (xhr) {
                                $("#progressStatus").show();
                            },
                            success: function (html) {
                                $("#progressStatus").hide();
                                if (html != '') {
                                    $("#previousHemodialysisPrescriptionsHTML").html(html);

                                    $.fn.dataTableExt.sErrMode = 'throw';
                                    $('#patients-list').DataTable({
                                        "bJQueryUI": true

                                    });
                                   
                                }
                            }, complete: function (jqXHR, textStatus) {
                                $("#progressStatus").hide();
                            }, error: function (html) {
                                $("#progressStatus").hide();
                            }
                        });
                        count_prescriptions();
                    }

                    $(document).ready(function () {
                        $("#previousHemodialysisPrescriptions").dialog({autoOpen: false, width: '95%', title: 'INPATIENT HEMODIALYSIS PRESCRIPTION', modal: true, position: 'top'});
                   
                        $('#prev_dh_start_date,#prev_dh_end_date').datetimepicker({
                            dayOfWeekStart: 1, changeMonth: true, changeYear: true, 
                            showWeek: true, showOtherMonths: true, lang: 'en',
                            //startDate:    'now'
                        });
                        $('#prev_dh_start_date,#prev_dh_end_date').datetimepicker({value: '', step: 1});
                    });
                    </script>
<!-- //dnm -->
<?php


if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
    $attend_query = mysqli_query($conn,"SELECT * FROM tbl_dialysis_details WHERE Patient_reg='$Registration_ID' AND date(Attendance_Date)=CURDATE()");
    if(mysqli_num_rows($attend_query) > 0){
        $row = mysqli_fetch_assoc($attend_query);
        $dialysis_details_ID = $row['dialysis_details_ID'];
        $Attendance_Date = $row['Attendance_Date'];
    }
    else{
        // if (isset($_GET['Attendance_Date'])){
        //     $dates = $_GET['Attendance_Date'];
        //     $attend_query = mysqli_query($conn,"SELECT * FROM tbl_dialysis_details WHERE Patient_reg='$Registration_ID' AND date(Attendance_Date)=CURDATE()");
        //     $row = mysqli_fetch_assoc($attend_query);
        //     $dialysis_details_ID = $row['dialysis_details_ID'];
        //     $Attendance_Date = $row['Attendance_Date'];
        // }
        // else{
            $obj = mysqli_query($conn,"INSERT INTO `tbl_dialysis_details`(`Patient_reg`, `Patient_Payment_ID`,`consultation_id`,Payment_Item_Cache_List_ID) VALUES ('$Registration_ID','$Payment_Cache_ID','$consultation_id','$Payment_Item_Cache_List_ID')");
            // $dialysis_details_ID  = mysqli_insert_id($conn);
            $attend_query = mysqli_query($conn,"SELECT * FROM tbl_dialysis_details WHERE Patient_reg='$Registration_ID' order  by dialysis_details_ID desc limit 1");
            $row = mysqli_fetch_assoc($attend_query);
            $dialysis_details_ID = $row['dialysis_details_ID'];
            $Attendance_Date = $row['Attendance_Date'];
        // }
    }
    // $sql="SELECT bpPost_sit,bpPost_stand,Dry_Weight,Weight_Post_stand,Temperature_post,Respiration_post,Pulse_post,Attendance_Date FROM tbl_dialysis_details WHERE Patient_reg='$Registration_ID' AND Attendance_Date<>'$Today' ORDER BY Attendance_Date DESC LIMIT 1";

        // $sql="SELECT bpPost_sit,bpPost_stand,Dry_Weight,Weight_Post_stand,Temperature_post,Respiration_post,Pulse_post,Attendance_Date FROM tbl_dialysis_vitals WHERE Patient_reg='$Registration_ID'' ORDER BY id DESC LIMIT 1";

    // $prev_query = mysqli_query($conn,$sql);
    // $prev_row = mysqli_fetch_assoc($prev_query);
   
// print_r($prev_row);exit;

    //  NEW LINE 

    // $sql_attendence=mysqli_query($conn,"SELECT dialysis_details_ID FROM tbl_dialysis_details WHERE Patient_reg='$Registration_ID' AND Attendance_Date=CURRENT_DATE");
    // $check_attendence=mysqli_num_rows($sql_attendence);
    // if($check_attendence > 0){
    //     $vitals= mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `tbl_dialysis_vitals` WHERE `Patient_reg`='$Registration_ID' AND Attendance_Date='$Attendance_Date' ORDER BY id DESC LIMIT 1"));
    //     $machineAcces = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `tbl_save_machine_access` WHERE `Patient_reg`='$Registration_ID' AND Attendance_Date='$Attendance_Date' ORDER BY id DESC LIMIT 1"));
    //     $hyperline = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `tbl_heparain_save` WHERE `Patient_reg`='$Registration_ID' AND Attendance_Date='$Attendance_Date' GROUP BY id DESC LIMIT 1"));
    //     $AccessOrdersbtn = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `tbl_access_orders` WHERE `Patient_reg`='$Registration_ID' AND Attendance_Date='$Attendance_Date' GROUP BY id DESC LIMIT 1"));
    //     $doctorrow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `tbl_dialysis_doctor_notes` WHERE `Patient_reg`='$Registration_ID' AND Attendance_Date='$Attendance_Date' GROUP BY id DESC LIMIT 1"));
    //     $collectionrow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `tbl_data_collection` WHERE `Patient_reg`='$Registration_ID' AND Attendance_Date='$Attendance_Date' GROUP BY id DESC LIMIT 1"));
    // }else{
        $vitals= mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `tbl_dialysis_vitals` WHERE `Patient_reg`='$Registration_ID' ORDER BY id DESC LIMIT 1"));
        $machineAcces = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `tbl_save_machine_access` WHERE `Patient_reg`='$Registration_ID'  ORDER BY id DESC LIMIT 1"));
        $hyperline = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `tbl_heparain_save` WHERE `Patient_reg`='$Registration_ID' AND date(Attendance_Date)=CURDATE() order by id desc limit 1"));
        $AccessOrdersbtn = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `tbl_access_orders` WHERE `Patient_reg`='$Registration_ID'  GROUP BY id DESC LIMIT 1"));
        $doctorrow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `tbl_dialysis_doctor_notes` WHERE `Patient_reg`='$Registration_ID'   order by id desc limit 1"));
        $collectionrow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `tbl_data_collection` WHERE `Patient_reg`='$Registration_ID'  GROUP BY id DESC LIMIT 1"));
    // }


}
?>            
<center><div id="container" style="display:none">
        <div id="default">
            <h1>#{title}</h1>
            <p>#{text}</p>
        </div>
    </div></center>
<!-- <br/> -->

<style>
.radioby {
  -webkit-appearance: checkbox; /* Chrome, Safari, Opera */
  -moz-appearance: checkbox;    /* Firefox */
  -ms-appearance: checkbox;     /* not currently supported */
}
.labelby{
    cursor:pointer;
}
</style>
<?php 
if(isset($_COOKIE["tab"])) {
    $tabselected="checked";$scrollselected="";
}else{
    $tabselected="";$scrollselected="checked";
}
?>
<span class="" style="">VIEW BY: 
  <label class="labelby"><input type="radio" name="viewby" id="scroll_by" class="radioby" <?=$scrollselected?>> Scroll </label> &nbsp;&nbsp; 
  <label class="labelby"><input type="radio" name="viewby" id="tab_by" class="radioby"<?=$tabselected?>> Tabs </label> 
</span>


<fieldset style="padding-bottom: 5px;">
    <legend> <b>Attendance Date: <?php echo date('Y-m-d') . '  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Payment Status:  ' . '<span class="displayStatus" id="' . $payStatus . '">' . $payStatus . '</span>'; ?></b> </legend>
    
    <center><div style="font-weight:bold;color: rgb(255,0,0);display: none" id="warning_text">Payment for this service is not done,make sure the payment is done first </div></center>
    <div id="example-tabs" style="min-height: 550px;">
        <h4 class="h4"><b>Vitals</b></h4> 
        <section>
        <style>
            .td-vital{height:29px;}
            .btn-edw:hover{color:#ffffff; background-color:#000;}
        </style>
            <form name="saveData" action="Savedialysisclinicalnotes.php" id="SaveVitalsform" method="POST">
                <table align="left" style="width:100%;margin-top: -25px">

                    <tr>
                        <td>
                            <table  class="" border="0" style="margin-top:0px;width:100% " align="left" >
                                <tr>
                                    <td width="4%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Vitals</td> <td width="25%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Previous Post (Last Attendance Date: <?php echo $prev_row['Attendance_Date']; ?>)</td><td style="font-weight:bold; background-color:#006400;color:white" width="24%">Pre</td> <td style="font-weight:bold; background-color:#006400;color:white" width="25%">Post</td>
                                </tr>
                                <td width="3%"  colspan="" align="right" style="text-align:right;">
                                    <table width="100%">

                                        <tr>
                                            <td class="td-vital">
                                                Pulse 
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="td-vital">
                                                Respiration 
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="td-vital">
                                                Temperature 
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="td-vital">
                                                B/P 
                                            </td>
                                        </tr>


                                        <tr>
                                            <td class="td-vital">
                                                Weight 
                                            </td>

                                        </tr>

                                        <tr>
                                            <td class="td-vital">
                                                <!-- EDW -->
                                            </td>

                                        </tr>
                                        <tr>
                                            <td class="td-vital">
                                                <!-- Time -->
                                            </td>

                                        </tr>
                                    </table>


                                </td>

                                <td > 
                                
                                <?php
                                // die("SELECT * FROM `tbl_dialysis_vitals` WHERE `Patient_reg`='$Registration_ID' AND Attendance_Date=CURRENT_DATE ORDER BY id DESC LIMIT 1");
                                    $check_vital_attendence_date=mysqli_query($conn, "SELECT * FROM `tbl_dialysis_vitals` WHERE `Patient_reg`='$Registration_ID' AND date(Attendance_Date)=CURRENT_DATE ORDER BY id DESC LIMIT 1");
                                    $count_vital=mysqli_num_rows($check_vital_attendence_date);
                                ?>          
                                    <table width="100%">
                                        <tr>
                                            <td>
                                                
                                            </td>
                                            <td>
                                                <span class="pointer" id="spankidondaN"><input readonly type="text"  id="Pulse_previous_post" name="Pulse_previous_post" value="<?php echo $vitals['Pulse_post']; ?>"></span>
                                            </td>
                                            <!-- <td></td> -->
                                        </tr>
                                        <tr>
                                            <td>
                                                
                                            </td>
                                            <td>
                                                <span class="pointer" id="spankidondaN"><input readonly type="text"  id="Respiration_previous_post" name="Respiration_previous_post" value="<?php echo $vitals['Respiration_post']; ?>"></span>
                                            </td>
                                            <!-- <td></td> -->
                                        </tr>
                                        <tr>
                                            <td>
                                                
                                            </td>
                                            <td>
                                                <span class="pointer" id="spankidondaN"><input readonly type="text"  id="Temperature_previous_post" name="Temperature_previous_post" value="<?php echo $vitals['Temperature_post']; ?>"></span>
                                            </td>
                                            <!-- <td></td> -->
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:100px;">Sit</td>
                                            <td width="100%">
                                                <span class="pointer"><input type="text" readonly  id="BP_previous_post_sit" name="BP_previous_post_sit" value="<?php echo $vitals['bpPost_sit']; ?>"></span>
                                                <input type="hidden" name="Registration_ID" value="<?php echo $Registration_ID; ?>">
                                            </td>

                                            <td style="text-align:right;width:100px;">Stand</td>
                                            <td width="40%">
                                                <span class="pointer"><input type="text"  readonly id="BP_previous_post_stand" style="width:50px" name="BP_previous_post_stand" value="<?php echo $vitals['bpPost_stand']; ?>"></span>

                                            </td>
                                        </tr>

                                        <tr>
                                            <td></td>
                                            <td>
                                            <span class="pointer" id="spanBujeH"><input type="text" readonly name="Weight_previous_post_stand" onkeyup="calculate_weight_gain()" id="weight_prev_post_stand" value="<?php echo $vitals['Weight_Post_stand']; ?>"></span>
                                            </td>
                                            <!-- <td></td> -->
                                            <!-- <td>
                                                <span class="pointer" id="spanBujeN"><input type="text" readonly  id="BujeN" name="Weight_previous_post_sit" value="<?php echo $vitals['Weight_Post_sit']; ?>"></span>
                                                <span class="pointer" id="spanBujeH"><input type="text" readonly  id="BujeH" name="Weight_previous_post_stand" value="<?php echo $vitals['Weight_Post_stand']; ?>"></span>
                                            </td> -->
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:200px;">
                                                Weight&nbsp;Gain
                                            </td>
                                            <td>
                                                <span class="pointer" id="spankidondaN"><input type="text" readonly id="Weight_Gain" name="Weight_Gain" value="<?php echo $vitals['Weight_Gain']; ?>"></span>
                                            </td>
                                            <!-- <td></td> -->
                                        </tr>
                                        <tr>

                                        <td style="text-align:right;width:200px;" class="td-vital">
                                                EDW
                                            </td>
                                            <td class="td-vital">
                                                <span class="pointer" id=""><input type="text"  id="Dry_Weight" name="Dry_Weight" value="<?php if($vitals['Dry_Weight']!=''){echo $vitals['Dry_Weight'];}else{echo $vitals['Dry_Weight'];} ?>"></span>
                                            </td>
                                            <!-- <td colspan="2" class="td-vital"><a onclick="save_dry_weight();" class="btn btn-success" style="padding-top:1px !important;width:100%;max-height:25px;color:#fff;text-decoration:none;"><small>Save&nbsp;EDW</small></a></td> -->
                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <table width="100%">
                                        <tr>
                                            <td>
                                                
                                            </td>
                                            <td colspan="2">
                                                <!-- <span class="pointer" id="spankidondaN"><input type="text"  id="Pulse_pre" name="Pulse_pre" value="<?php echo $vitals['Pulse_pre']; ?>"></span> -->
                                                <span class="pointer" id="spankidondaN"><input type="text"  id="Pulse_pre" name="Pulse_pre"></span>
                                            </td>
                                            <!-- <td></td> -->
                                        </tr>
                                        <tr>
                                            <td>
                                                
                                            </td>
                                            <td colspan="2">
                                                <!-- <span class="pointer" id="spankidondaN"><input type="text"  id="Respiration_pre" name="Respiration_pre" value="<?php echo $vitals['Respiration_pre']; ?>"></span>
                                                 -->
                                                 <span class="pointer" id="spankidondaN"><input type="text"  id="Respiration_pre" name="Respiration_pre" ></span>
                                            </td>
                                            <!-- <td></td> -->
                                        </tr>
                                        <tr>
                                            <td>
                                                
                                            </td>
                                            <td colspan="2">
                                                <!-- <span class="pointer" id="spankidondaN"><input type="text"  id="Temperature_pre" name="Temperature_pre" value="<?php echo $vitals['Temperature_pre']; ?>"></span> -->

                                                <span class="pointer" id="spankidondaN"><input type="text"  id="Temperature_pre" name="Temperature_pre" ></span>
                                            </td>
                                            <!-- <td></td> -->
                                        </tr>
                                        
                                        <tr>
                                            <td style="text-align:right;width:100px;">Sit</td>
                                            <td width="40%">
                                                <!-- <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="BP_Pre_sit" name="BP_Pre_sit" value="<?php echo $vitals['bpPre_sit']; ?>"></span> -->

                                                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="BP_Pre_sit" name="BP_Pre_sit"></span>

                                            </td>

                                            <td style="text-align:right;width:100px;">Stand</td>
                                            <td width="40%">
                                                <!-- <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="BP_Pre_stand" name="BP_Pre_stand" value="<?php echo $vitals['bpPre_stand']; ?>"></span> -->

                                                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="BP_Pre_stand" name="BP_Pre_stand"></span>


                                            </td>
                                        </tr>


                                        <tr>
                                            <td></td>
                                            <td colspan="2">
                                                <!-- <span class="pointer" id="spanBujeH"><input type="text" onkeyup="calculate_weight_gain();calculate_weight_removal();" id="weight_pre_stand" name="weight_pre_stand" value="<?php echo $vitals['Weight_Pre_stand']; ?>"></span> -->

                                                <span class="pointer" id="spanBujeH"><input type="text" onkeyup="calculate_weight_gain();calculate_weight_removal();" id="weight_pre_stand" name="weight_pre_stand"></span>
                                            </td>
                                            <!-- <td></td> -->
                                            <td>
                                                <!-- <span class="pointer" id="spanBujeN"><input type="text"  id="weight_pre_sit" name="weight_pre_sit" value="<?php echo $vitals['Weight_Pre_sit']; ?>"></span> -->
                                                <!-- <span class="pointer" id="spanBujeH"><input type="text"  id="weight_pre_stand" name="weight_pre_stand" value="<?php echo $vitals['Weight_Pre_stand']; ?>"></span> -->
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:right;width:200px;">
                                                Weight&nbsp;removal
                                            </td>
                                            <td colspan="2">
                                                <!-- <span class="pointer" id="spankidondaN">
                                                    <input type="text"  id="Weight_Removal" name="Weight_removal" value="<?php echo $vitals['Weight_removal']; ?>"></span> -->

                                                    <span class="pointer" id="spankidondaN">
                                                    <input type="text"  id="Weight_Removal" name="Weight_removal"></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right;width:200px;">
                                                Time&nbsp;On
                                            </td>
                                            <td colspan="2">

                                                <!-- <span class="pointer" id="spanchuchu_damuN">
                                                    <input type="text" id="Time_On" name="Time_On" value="<?php echo $vitals['Time_On']; ?>"></span> -->

                                                    <span class="pointer" id="spanchuchu_damuN">
                                                    <input type="text" id="Time_On" name="Time_On"></span>
                                            </td>
                                        </tr>

                                    </table>   

                                </td>


                                <td>
                                <table width="100%">
                                        <tr>
                                            <td>
                                                
                                            </td>
                                            <td colspan="2">
                                                <span class="pointer" id="spankidondaN">
                                                    <input type="text"  id="Pulse_post" name="Pulse_post" ></span>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                
                                            </td>
                                            <td colspan="2">
                                                <span class="pointer" id="spankidondaN">
                                                    <input type="text"  id="Respiration_post" name="Respiration_post" ></span>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                
                                            </td>
                                            <td colspan="2">
                                                <span class="pointer" id="spankidondaN"><input type="text"  id="Temperature_post" name="Temperature_post" ></span>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right;width:100px;">Sit</td>
                                            <td width="40%">
                                                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Post_Pre_sit" name="Post_Pre_sit"></span>

                                            </td>

                                            <td style="text-align:right;width:100px;">Stand</td>
                                            <td width="40%">
                                                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  name="Post_Pre_stand" ></span>

                                            </td>
                                        </tr>

                                        <tr>
                                            <td></td>
                                            <td colspan="2">
                                            <span class="pointer" id="spanBujeH"><input type="text" name="Weight_Post_stand" id="Weight_Post_stand" onkeyup="calculate_weight_removal();" ></span>
                                            </td>
                                            <td><!-- <span class="pointer" id="spanBujeN"><input type="text"  id="Weight_Post_sit" name="Weight_Post_sit" value="<?php echo $vitals['Weight_Post_sit']; ?>"></span> -->
                                                <!-- <span class="pointer" id="spanBujeH"><input type="text"  id="" name="Weight_Post_stand" value="<?php echo $vitals['Weight_Post_stand']; ?>"></span> -->
                                            </td>
                                        </tr>

                                        <tr>
<!--                                            <td style="text-align:right;width:200px;">
                                                Area#
                                            </td>
                                            <td>
                                                <span class="pointer" id="spankidondaN"><input disabled placeholder="disabled" type="text"  id="Area" name="Area" value="<?php echo $vitals['Area']; ?>"></span>
                                            </td>

                                            <td style="text-align:right;width:200px;">
                                                Station#
                                            </td>
                                            <td>
                                                <span class="pointer" id="spankidondaN"><input disabled placeholder="disabled" type="text"  id="Station" name="Station" value="<?php echo $vitals['Station']; ?>"></span>
                                            </td>-->
                                        </tr>
                                        <tr>
                                            <td style="text-align:right;width:200px;">
                                                Time&nbsp;Off
                                            </td>
                                            <td>

                                                <span class="pointer" id="spanchuchu_damuN">
                                                    <input type="text"  id="Time_Off" name="Time_Off" ></span>
                                            </td>
                                            <td style="text-align:right;width:200px;">
                                                Machine#
                                            </td>
                                            <td>

                                                <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Machine" name="Machine" ></span>
                                            </td>
                                        </tr>

                                    </table>   

                                </td>

                    </tr>

                </table>    

                                </td>

                    </tr>

                </table> 
                <table style="width: 100%">
                    <tr>
                        <td style="text-align:right;width:100px;">Diagnosis(DX)</td>
                        <td>
                            <span class="pointer"><input type="text"  id="Diagnosis" name="Diagnosis" value="<?php echo $vitals['Diagnosis']; ?>"></span>
                        </td>
                        <td style="text-align:right;width:90px;">Management</td>
                        <td rowspan="2">
                            <span class="pointer">
                            <?php if($row['Management']!=''){?>
                                <textarea name="Management" id="Management"><?=$vitals['Management'];?></textarea>
                            <?php }else{ ?>
                                <textarea name="Management" id="Management"></textarea>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:100px;">Remarks</td>
                        <td>
                        <span class="pointer"><input type="text"  id="Remarks" name="Remarks" value="<?php echo $vitals['Remarks']; ?>"></span>
                        </td>
                    </tr>
                </table>
                <table style="width: 100%">
                    <tr>
                        <td style="text-align:right;">Hosp/ER/OP Procedure since last treatment?</td>
                        <?php
                        if ($vitals['Hosp'] == 'yes') {
                            $checked = 'yes';
                        } else {

                            $checked = 'no';
                        }
//                        
//                        if($row['Hosp']=='yes'){
//                            echo '<td><span><input type="radio" name="hosp" checked="true" value="Yes">&nbsp;&nbsp;Yes</span></td>';  
//                            echo '<td><span><input type="radio" name="hosp" value="No">&nbsp;&nbsp;No</span></td>';
//                            
//                        } else {
//                           echo '<td><span><input type="radio" name="hosp" checked="true" value="Yes">&nbsp;&nbsp;Yes</span></td>';  
//                           echo '<td><span><input type="radio" name="hosp" checked="true" value="No">&nbsp;&nbsp;No</span></td>';
//                        }
                        ?>

                        <td><span><input type="radio" name="hosp" <?php
                                if (strtolower($checked) == 'yes') {
                                    echo 'checked="checked"';
                                }
                                ?> value="Yes">&nbsp;&nbsp;Yes</span></td> 
                        <td><span><input type="radio" name="hosp" <?php
                                if (strtolower($checked) == 'no') {
                                    echo 'checked="checked"';
                                }
                                ?> value="No">&nbsp;&nbsp;No</span></td>

                    </tr>

                </table>
                </td>

                </tr>
                <tr>
                    <td>
                <center><input type="submit" value="Save Data" id="saveVitals" name="saveVitals" class="art-button-green"></center>
                <input type="hidden" name="SubmitVitals">
                <input type="hidden" name="dialysis_details_ID" value="<?php echo $dialysis_details_ID; ?>">
                <input type="hidden" name="Consultant_employee" value="<?php echo $Consultant_employee; ?>">
                <input type="hidden" name="Registration_ID" value="<?php echo $Registration_ID; ?>">
                <input type="hidden" name="Payment_Item_Cache_List_ID" value="<?php echo $Payment_Item_Cache_List_ID; ?>">
                <input type="hidden" name="Payment_Cache_ID" value="<?php echo $Payment_Cache_ID; ?>">

                </td>
                </tr>
                </table> 
            </form>
        </section>

        <h4 class="h4_"><b>Machine Assesment</b></h4>
        <section>
            <form id="MachineAccess" name="MachineAccess" action="Savedialysisclinicalnotes.php" method="POST">
                <table  class="" border="0" style="margin-top:-20px;width:100% " align="left" >
                    <tr>
                        <td >           
                            <table width="100%">
                                <tr>
                                    <td style="text-align:right;width:100px;">Conductivity Machine</td>
                                    <td width="40%">
                                        <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Conductivity_Machine" name="Conductivity_Machine"></span>

                                    </td>

<!--                                    <td style="text-align:right;width:100px;">Manual</td>
                                    <td width="40%">
                                        <span class="pointer" id="spanuchunguzi_titiN"><input disabled placeholder="disabled" type="text"  id="Conductivity_Manual" name="Conductivity_Manual" value="<?php echo $machineAcces['Conductivity_manual']; ?>"></span>

                                    </td>-->
                                </tr>

                                <tr>
<!--                                    <td style="text-align:right;width:200px;">pH Machine</td>
                                    <td>
                                        <span class="pointer" id="spanBujeN"><input disabled placeholder="disabled" type="text"  id="pH_Machine" name="pH_Machine" value="<?php echo $machineAcces['pH_Machine']; ?>"></span>
                                    </td>-->
<!--                                    <td style="text-align:right;width:100px;">Manual</td>
                                    <td>
                                        <span class="pointer" id="spanBujeH"><input disabled placeholder="disabled" type="text"  id="pH_Manual" name="pH_Manual" value="<?php echo $machineAcces['pH_Manual']; ?>"></span>
                                    </td>-->
                                </tr>

                                <tr>
                                    <td style="text-align:right;width:200px;">
                                        Temperature Machine
                                    </td>
                                    <td>
                                        <span class="pointer" id="spankidondaN"><input type="text"  id="Temperature_Machine" name="Temperature_Machine" ></span>
                                    </td>

<!--                                    <td style="text-align:right;width:100px;">Initial</td>
                                    <td>
                                        <span class="pointer" id="spanBujeH"><input disabled placeholder="disabled" type="text"  id="Temperature_Initial" name="Temperature_Initial" value="<?php echo $machineAcces['Temperature_Initial']; ?>"></span>
                                    </td>-->
                                </tr>

                                <tr>
                                    <td style="text-align:right;width:200px;">
                                        Alarm Test
                                    </td>
                                    <td>
                                        <?php
                                        if ($machineAcces['Alarm_Test'] == 'Pass') {
                                            echo '<span class="pointer" id="spanchuchu_damuN"><input type="checkbox"   id="Alarm_Test" name="Alarm_Test"> &nbsp;&nbsp;&nbsp;&nbsp;Pass</span>
                                   ';
                                        } else {
                                            echo '<span class="pointer" id="spanchuchu_damuN"><input type="checkbox"  id="Alarm_Test" name="Alarm_Test"> &nbsp;&nbsp;&nbsp;&nbsp;Pass</span>
                                   ';
                                        }
                                        ?>

                                    </td>
                                </tr>

                                <tr>
                                    <td style="text-align:right;width:200px;">
                                        Air Detector on
                                    </td>
                                    <td>
                                        <?php
                                        if ($machineAcces['Air_Detector'] == 'Yes') {
                                            echo '<span class="pointer" id="spanchuchu_damuN"><input type="checkbox"   id="Air_Detector" name="Air_Detector">&nbsp;&nbsp;&nbsp;&nbsp;Yes</span>
';
                                        } else {

                                            echo '<span class="pointer" id="spanchuchu_damuN"><input type="checkbox"  id="Air_Detector" name="Air_Detector">&nbsp;&nbsp;&nbsp;&nbsp;Yes</span>
';
                                        }
                                        ?>

                                    </td>
                                </tr>

                                <tr>
                                    <td style="text-align:right;width:200px;">
                                        UF System
                                    </td>
                                    <td>
                                        <?php
                                        if ($machineAcces['UF_System'] == 'Pass') {
                                            echo '<span class="pointer" id="spanchuchu_damuN"><input type="checkbox"  id="UF_System" name="UF_System">&nbsp;&nbsp;&nbsp;&nbsp;Pass</span>
';
                                        } else {
                                            echo '<span class="pointer" id="spanchuchu_damuN"><input type="checkbox"  id="UF_System" name="UF_System">&nbsp;&nbsp;&nbsp;&nbsp;Pass</span>
';
                                        }
                                        ?>
                                    </td>

                                </tr>


                                <tr>
                                    <td style="text-align:right;width:200px;">
                                        Positive Presence Test
                                    </td>
                                    <td>

                                        <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Positive_Presence" name="Positive_Presence"></span>
                                    </td>

                                    <td style="text-align:right;width:100px;"></td>
<!--                                    <td>
                                        <span class="pointer" id="spanBujeH"><input type="text"  id="BujeH" name="Buje"></span>
                                    </td>-->
                                </tr>


                                <tr>
                                    <td style="text-align:right;width:200px;">
                                        Negative Residual Test
                                    </td>
                                    <td>

                                        <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Negative_Residual" name="Negative_Residual"></span>
                                    </td>

                                    <td style="text-align:right;width:100px;"></td>
<!--                                    <td>
                                        <span class="pointer" id="spanBujeH"><input type="text"  id="BujeH" name="Buje"></span>
                                    </td>-->
                                </tr>
                                <tr>
                                    <td style="text-align:right;width:100px;">Initial</td>
                                    <!-- <td>
                                        <?php 
                                            if(!empty($machineAcces['UF_System_initial'])){
                                        ?>
                                        <span class="pointer" id="spanBujeH">
                                            <input type="text" readonly="readonly" id="UF_Initial" name="UF_Initial" value="< ?php echo $machineAcces['UF_System_initial']; ?>"></span>
                                        <?php }else{?>
                                        <span class="pointer" id="spanBujeH">
                                            <input type="text" readonly="readonly" id="UF_Initial" name="UF_Initial" value="< ?php echo $initianame; ?>"></span>
                                        <?php }?>
                                    </td> -->
                                    <td>
                                    <span class="pointer" id="spanBujeH">
                                            <input type="text" readonly="readonly" id="UF_Initial" name="UF_Initial" value="<?php echo $initianame; ?>"></span>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="text-align:right;width:200px;">
                                        Dialyzer ID
                                    </td>
                                    <td>

                                        <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Dialyzer_ID" name="Dialyzer_ID"></span>
                                    </td>
<!--                                    <td>
                            <span class="pointer" id="spanBujeH"><input type="text"  id="BujeH" name="Buje"></span>
                        </td>-->
                    </tr>
                    <tr>
                        <td></td>
                        <td style="text-align:right;width:100px;">
                                <center> <input type="submit" value="Save Data" name="SaveAcess" class="art-button-green"> </center>
                        </td>
                    </tr>
                    

                </table>
                </td>

                </tr>
                <tr>
                    <td>
                        <input type="hidden" name="Registration_ID" value="<?php echo $Registration_ID; ?>">
                        <input type="hidden" name="dialysis_details_ID" value="<?php echo $dialysis_details_ID; ?>">
                        <input type="hidden" name="Payment_Item_Cache_List_ID" value="<?php echo $Payment_Item_Cache_List_ID; ?>">
                         <input type="hidden" name="Payment_Cache_ID" value="<?php echo $Payment_Cache_ID; ?>">
                         <input type="hidden" name="Consultant_employee" value="<?php echo $Consultant_employee; ?>">
                        <input type="hidden" name="SaveMachineAccess">
                    </td>
                </tr>

                </table> 

            </form>
        </section>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <h4 class="h4_"><b>Heparin</b></h4>
        <section>
            <form action="Savedialysisclinicalnotes.php" name="Heparainform" id="Heparainform" method="POST">
                <table  class="" border="0" style="margin-top:-20px;width:100% " align="left" >
                    <tr>
                        <td >           
                            <table width="100%">
                                <tr>
                                    <td style="text-align:right;width:200px;">Type</td>
                                    <td width="40%">
                                        <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Type" name="Type"></span>
                                    </td>

                                    <td style="text-align:right;width:200px;">Initial Bolus Units</td>
                                    <td width="40%">
                                        <span class="pointer" id="spanBujeN"><input type="text"  id="Initial_Bolus" name="Initial_Bolus"></span>
                                    </td>

                                </tr>

                                <!-- <tr>
                                    <td style="text-align:right;width:200px;">Initial Bolus Units</td>
                                    <td>
                                        <span class="pointer" id="spanBujeN"><input type="text"  id="Initial_Bolus" name="Initial_Bolus" value="<?php echo $hyperline['Initial_Bolus']; ?>"></span>
                                    </td>

                                </tr> -->

                                <tr>
                                    <td style="text-align:right;width:200px;">
                                        Unit/Hr
                                    </td>
                                    <td>
                                        <span class="pointer" id="spankidondaN"><input type="text"  id="Unit_Hr" name="Unit_Hr"></span>
                                    </td>

<!--                                    <td style="text-align:right;width:200px;">Infusion/Bolus</td>
                                    <td>
                                        <span class="pointer" id="spanBujeH"><input type="text"  id="Infusion_Bolus" name="Infusion_Bolus" value="<?php echo $hyperline['Infusion_Bolus']; ?>"></span>
                                    </td>-->
                                </tr>

                                <tr>
                                    <td style="text-align:right;width:200px;">
                                        Stop time
                                    </td>
                                    <td>
                                        <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Stop_time" name="Stop_time"></span>
                                    </td>

                                    <td style="text-align:right;width:200px;">
                                        CVC Post Instil
                                    </td>
                                    <td>
                                        <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="CVC_Post" name="CVC_Post"></span>
                                    </td>
                                </tr>


                                <!-- <tr>
                                    <td style="text-align:right;width:200px;">
                                        CVC Post Instil
                                    </td>
                                    <td>
                                        <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="CVC_Post" name="CVC_Post" value="<?php echo $hyperline['CVC_Pos']; ?>"></span>
                                    </td>
                                </tr> -->


                                <tr>
                                    <td style="text-align:right;width:200px;">
                                        Arterial
                                    </td>
                                    <td>
                                        <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Arterial" name="Arterial"></span>
                                    </td>

                                    <td style="text-align:right;width:200px;">Venous</td>
                                    <td>
                                        <span class="pointer" id="spanBujeH"><input type="text"  id="Venous" name="Venous"></span>
                                    </td>
                                </tr>

                            </table>
                        </td>

                    </tr>

                    <tr>
                        <td>
                            <input type="hidden" name="Registration_ID" value="<?php echo $Registration_ID; ?>">
                    <center> <input type="submit" value="Save Data" class="art-button-green"> </center>

                    <input type="hidden" name="Payment_Item_Cache_List_ID" value="<?php echo $Payment_Item_Cache_List_ID; ?>">
                    <input type="hidden" name="Consultant_employee" value="<?php echo $Consultant_employee; ?>">
                    <input type="hidden" name="dialysis_details_ID" value="<?php echo $dialysis_details_ID; ?>">
                    <input type="hidden" name="Payment_Cache_ID" value="<?php echo $Payment_Cache_ID; ?>">
                    <input type="hidden" name="HeparainSave">

                    </td>
                    </tr>

                </table> 
            </form>
        </section>
        <br>
        <br>
        <br>
        <h4 class="h4_"><b>Dialysis Orders</b></h4> 
        <section id="dialysis_orders_form_table">
            <table class="table" style="background-color:#fff;">
                        <tr>
                            <td></td>
                            <td width="20%"><input type="text" autocomplete="off" readonly style='text-align:center;display:inline;' placeholder="Start Date" title="Start Date" id="prev_dh_start_date" value="<?=$startoriginal?>"></td>
                            <td width="20%"><input type="text" autocomplete="off" readonly style='text-align:center;display:inline;' placeholder="End Date" title="End Date" id="prev_dh_end_date" value="<?=$Current_Date_Time?>"></td>
                            <td width="20%">
                                <center><input type="button" onclick="filterPreviousHemodialysisPrescriptions()" class="art-button-green" value="FILTER PRESCRIPTIONS"></center>
                            </td>
                            <td></td>
                        </tr>
                    </table>

                    <div align="center" style="display: none" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>

                    <div id="previousHemodialysisPrescriptionsHTML" style="overflow-x:scroll; height:470px; overflow-y:scroll">
                       <!-- AJAX data  -->
                    </div>
        <?php //include("dialysis_orders_form.php");?>
        </section>

 <!--       <h4 class="h4"><b>Access Orders</b></h4> 
        <section>
            <form action="Savedialysisclinicalnotes.php" method="POST" id="AccessOrdersform">
                <textarea name="Orderstextarea" id="Orderstextarea" style="width: 100%;height: 100px;margin-top: -30px">
                        
<?php echo $AccessOrdersbtn['AccessOrdersNotes']; ?>
                </textarea>
                <table  class="" border="0" style="margin-top:10px;width:100% " align="left" >
                    <tr>
                        <td width="16%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white"></td> 
                        <td width="16%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Needle Gauge</td>
                        <td style="font-weight:bold; background-color:#006400;color:white" width="16%">Needle Length</td> 
                        <td style="font-weight:bold; background-color:#006400;color:white" width="16%">Static Pressure</td>
                         <td style="font-weight:bold; background-color:#006400;color:white" width="16%">Bleeding Stopped</td></tr>
                    <td  colspan="" align="right" style="text-align:right;">
                        <table width="100%">
                            <tr>
                                <td>
                                    Arterial
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Venous
                                </td>

                            </tr>

                            <tr>
                                <td>
                                    Canulated/Catheter accessed by
                                </td>

                            </tr>


                        </table>


                    </td>




                    <td >           
                        <table width="100%">
                            <tr>

                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Arterial_Needle_Gauge" name="Arterial_Needle_Gauge" value="<?php echo $AccessOrdersbtn['Arterial_Needle_Gauge']; ?>"></span>

                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="Venos_Needle_Gauge" name="Venos_Needle_Gauge" value="<?php echo $AccessOrdersbtn['Venous_Needle_Gauge']; ?>"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="Arterial_Needle_Length" name="Arterial_Needle_Length" value="<?php echo $AccessOrdersbtn['Arterial_Needle_Length']; ?>"></span>
                                </td>
                            </tr>

                        </table>
                    </td>
                    <td>
                        <table width="100%">
                            <tr>
                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input disabled placeholder="disabled" type="text"  id="Vonos_Needle_Length" name="Vonos_Needle_Length" value="< ?php echo $AccessOrdersbtn['Venous_Needle_Length']; ?>"></span>

                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  disabled placeholder="disabled" id="Arterial_Static_Pressuer" name="Arterial_Static_Pressuer" value="< ?php echo $AccessOrdersbtn['Arterial_Static_Pressuer']; ?>"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text" disabled placeholder="disabled"  id="Vonos_Static_Pressuer" name="Vonos_Static_Pressuer" value="< ?php echo $AccessOrdersbtn['Vonos_Static_Pressuer']; ?>"></span>
                                </td>
                            </tr>



                        </table>   

                    </td>

                    <td>
                        <table width="100%">
                            <tr>
                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input disabled placeholder="disabled" type="text"  id="Arterial_Bleeding_Stopped" name="Arterial_Bleeding_Stopped"></span>

                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text" disabled placeholder="disabled"  id="Vonos_Bleeding_Stopped" name="Vonos_Bleeding_Stopped"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input disabled placeholder="disabled" type="text"  id="kidondaN" name="kidonda"></span>
                                </td>
                            </tr>


                        </table>   

                    </td>

                    <td>
                        <table width="100%">
                            <tr>
                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="uchunguzi_titiN" name="uchunguzi_titi" value="< ?php echo $AccessOrdersbtn['Arterial_Bleeding_Stopped']; ?>"></span>

                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="BujeH" name="Buje" value="< ?php echo $AccessOrdersbtn['Venous_Bleeding_Stopped']; ?>"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="kidondaN" name="kidonda"></span>
                                </td>
                            </tr>

                        </table>   

                    </td>

                    </tr>

                </table>

                <table>
                    <tr>
                        <td>
                    <center> <input type="submit" value="Save Data" id="AccessOrdersbtn" class="art-button-green"> </center>
                    <input type="hidden" name="Registration_ID" value="<?php echo $Registration_ID; ?>">
                    <input type="hidden" name="dialysis_details_ID" value="<?php echo $dialysis_details_ID; ?>">
                    <input type="hidden" name="Consultant_employee" value="<?php echo $Consultant_employee; ?>">
                    <input type="hidden" name="Payment_Item_Cache_List_ID" value="<?php echo $Payment_Item_Cache_List_ID; ?>">
                     <input type="hidden" name="Payment_Cache_ID" value="<?php echo $Payment_Cache_ID; ?>">
                    <input type="hidden" name="AccessOrdersbtn">

                    </td>
                    </tr>
                </table>
            </form>
        </section>-->

<!--
        <h4 class="h4"><b>Doctor Notes</b></h4> 
        <section>
            <form action="Savedialysisclinicalnotes.php" method="POST" id="SaveNotesfrm">
                <table style="width: 100%">
                    <tr>
                        <td>

                            <textarea name="txtNotes" style="width: 100%;height: 200px;margin-top: -30px">
<?php echo $doctorrow['Notes']; ?>
                            </textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>
                    <center><input type="submit" value="Save Data" class="art-button-green" style="margin-top: 50px"></center>
                    <input type="hidden" name="dialysis_details_ID" value="<?php echo $dialysis_details_ID; ?>">
                    <input type="hidden" name="Consultant_employee" value="<?php echo $Consultant_employee; ?>">
                    <input type="hidden" name="Registration_ID" value="<?php echo $Registration_ID; ?>">
                    <input type="hidden" name="Payment_Item_Cache_List_ID" value="<?php echo $Payment_Item_Cache_List_ID; ?>">
                     <input type="hidden" name="Payment_Cache_ID" value="<?php echo $Payment_Cache_ID; ?>">
                    <input type="hidden" name="SaveNotesbtn">
                    </td>
                    </tr>
                </table>
            </form>
        </section>-->

        <h4 class="h4"><b>Data Collection</b></h4>
        <section>
            <form action="Savedialysisclinicalnotes.php" method="POST" id="SaveDataCollectionform">
                <table  class="" border="0" style="margin-top:-30px;width:100% " align="left" >
                    <tr>
                        <td width="16%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white"></td> 
                        <td width="16%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Pre Assessment</td>
                        <!--<td style="font-weight:bold; background-color:#006400;color:white" width="16%">Time</td>-->
                        <!--<td style="font-weight:bold; background-color:#006400;color:white" width="16%">Initials</td>--> 
                        <td style="font-weight:bold; background-color:#006400;color:white" width="16%">Post</td> 
                        <!--<td style="font-weight:bold; background-color:#006400;color:white" width="16%">Initials</td>-->
                    </tr>
                    <td  colspan="" align="right" style="text-align:right;">
                        <table width="100%">
                            <tr>
                                <td class="td-vital">
                                    Temp
                                </td>
                            </tr>

                            <tr>
                                <td class="td-vital">
                                    Resp
                                </td>

                            </tr>

                            <tr>
                                <td class="td-vital">
                                    GI
                                </td>

                            </tr>

                            <tr>
                                <td class="td-vital">
                                    Cardiac
                                </td>

                            </tr>

                            <tr>
                                <td class="td-vital">
                                    Edema
                                </td>

                            </tr>

                            <tr>
                                <td class="td-vital">
                                    Mental
                                </td>

                            </tr>

                            <tr>
                                <td class="td-vital">
                                    Mobility
                                </td>

                            </tr>

                            <tr>
                                <td class="td-vital">
                                    Access
                                </td>

                            </tr>
                            <tr>
                                <td class="td-vital">
                                    Time
                                </td>

                            </tr>
                            <tr>
                                <td class="td-vital">
                                    Initials
                                </td>

                            </tr>


                        </table>


                    </td>




                    <td >           
                        <table width="100%">
                            <tr>

                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Temp_Pre_Assessment" name="Temp_Pre_Assessment"></span>

                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="Resp_Pre_Assessment" name="Resp_Pre_Assessment"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="GI_Pre_Assessment" name="GI_Pre_Assessment" ></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Cardiac_Pre_Assessment" name="Cardiac_Pre_Assessment" ></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Edema_Pre_Assessment" name="Edema_Pre_Assessment"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Mental_Pre_Assessment" name="Mental_Pre_Assessment"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Mobility_Pre_Assessment" name="Mobility_Pre_Assessment"></span>
                                </td>
                            </tr>


                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Access_Pre_Assessment" name="Access_Pre_Assessment"></span>
                                </td>
                            </tr>
                            
                            <tr>
                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text" class="Data_Collection_time_1" <?php if(!empty($collectionrow['Temp_Time']))echo "disabled"; ?> id="Temp_Time" name="Temp_Time"></span>

                                </td>
                            </tr>
                            
                            <tr>
                                <td width="40%">
                                    <!-- <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Temp_Initials" name="Temp_Initials" value="<!--?php if(!empty($collectionrow['Temp_Initials'])){echo $collectionrow['Temp_Initials'];} else { echo $initianame;}?>"--><!--/span> -->

                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Temp_Initials" name="Temp_Initials" value="<?php echo $initianame;?>"></span>
                                </td>
                            </tr>
                        </table>
                    <!--</td>-->
                    <!--<td>-->
                        <!--<table width="100%">-->
<!--                        <tr>
                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text" class="Data_Collection_time_1" <?php if(!empty($collectionrow['Temp_Time']))echo "disabled"; ?> id="Temp_Time" name="Temp_Time" value="<?php echo $collectionrow['Temp_Time']; ?>"></span>

                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  class="Data_Collection_time_2" id="Resp_Time" name="Resp_Time" <?php if(!empty($collectionrow['Resp_Time']))echo "disabled"; ?> value="<?php echo $collectionrow['Resp_Time']; ?>"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text" class="Data_Collection_time_3" id="GI_Time" name="GI_Time" <?php if(!empty($collectionrow['GI_Time']))echo "disabled"; ?> value="<?php echo $collectionrow['GI_Time']; ?>"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text" class="Data_Collection_time_4" id="Cardiac_Time" name="Cardiac_Time" <?php if(!empty($collectionrow['Cardiac_Time']))echo "disabled"; ?> value="<?php echo $collectionrow['Cardiac_Time']; ?>"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text" class="Data_Collection_time_5" id="Edema_Time" name="Edema_Time" <?php if(!empty($collectionrow['Edema_Time']))echo "disabled"; ?> value="<?php echo $collectionrow['Edema_Time']; ?>"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text" class="Data_Collection_time_6" id="Mental_Time" name="Mental_Time" <?php if(!empty($collectionrow['Mental_Time']))echo "disabled"; ?> value="<?php echo $collectionrow['Mental_Time']; ?>"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text" class="Data_Collection_time_7" id="Mobility_Time" name="Mobility_Time" <?php if(!empty($collectionrow['Mobility_Time']))echo "disabled"; ?> value="<?php echo $collectionrow['Mobility_Time']; ?>"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text" class="Data_Collection_time_8" id="Access_Time" name="Access_Time" <?php if(!empty($collectionrow['Access_Time']))echo "disabled"; ?> value="<?php echo $collectionrow['Access_Time']; ?>"></span>
                                </td>
                            </tr>-->

                        <!--</table>-->   

                    <!--</td>-->


<!--                    <td>
                        <table width="100%">
                            <tr>
                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Temp_Initials" name="Temp_Initials" value="<?php if(!empty($collectionrow['Temp_Initials'])){echo $collectionrow['Temp_Initials'];} else { echo $initianame;}?>"></span>

                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="Resp_Initials" name="Resp_Initials" value="<?php if(!empty($collectionrow['Resp_Initials'])){echo $collectionrow['Resp_Initials'];} else { echo $initianame;}?>"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="GI_Initials" name="GI_Initials" value="<?php if(!empty($collectionrow['GI_Initials'])){echo $collectionrow['GI_Initials'];} else { echo $initianame;}?>"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Cardiac_Initials" name="Cardiac_Initials" value="<?php if(!empty($collectionrow['Cardiac_Initials'])){echo $collectionrow['Cardiac_Initials'];} else { echo $initianame;}?>"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Edema_Initials" name="Edema_Initials" value="<?php if(!empty($collectionrow['Edema_Initials'])){echo $collectionrow['Edema_Initials'];} else { echo $initianame;}?>"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Mental_Initials" name="Mental_Initials" value="<?php if(!empty($collectionrow['Mental_Initials'])){echo $collectionrow['Mental_Initials'];} else { echo $initianame;}?>"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Mobility_Initials" name="Mobility_Initials" value="<?php if(!empty($collectionrow['Mobility_Initials'])){echo $collectionrow['Mobility_Initials'];} else { echo $initianame;}?>"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Access_Initials" name="Access_Initials" value="<?php if(!empty($collectionrow['Access_Initials'])){echo $collectionrow['Access_Initials'];} else { echo $initianame;}?>"></span>
                                </td>
                            </tr>
                        </table>   

                    </td>-->

                    <td>
                        <table width="100%">
                            <tr>
                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Temp_Post" name="Temp_Post"></span>

                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="Resp_Post" name="Resp_Post"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="GI_Post" name="GI_Post"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Cardiac_Post" name="Cardiac_Post"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Edema_Post" name="Edema_Post"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Mental_Post" name="Mental_Post"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Mobility_Post" name="Mobility_Post"></span>
                                </td>
                            </tr>


                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Access_Post" name="Access_Post"></span>
                                </td>
                            </tr>

                        </table> 


                    </td>


<!--                    <td>
                        <table width="100%">
                            
                            <tr>
                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Temp_Initials2" name="Temp_Initials2" value="<?php if(!empty($collectionrow['Temp_Initials2'])){echo $collectionrow['Temp_Initials2'];} else { echo $initianame;}?>"></span>

                                </td>
                            </tr>
                            <tr>
                                <td width="40%">
                                    <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Resp_Initials2" name="Resp_Initials2" value="<?php if(!empty($collectionrow['Resp_Initials2'])){echo $collectionrow['Resp_Initials2'];} else { echo $initianame;}?>"></span>

                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spanBujeH"><input type="text"  id="GI_Initials2" name="GI_Initials2" value="<?php if(!empty($collectionrow['GI_Initials2'])){echo $collectionrow['GI_Initials2'];} else { echo $initianame;}?>"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>
                                    <span class="pointer" id="spankidondaN"><input type="text"  id="Cardiac_Initials2" name="Cardiac_Initials2" value="<?php if(!empty($collectionrow['Cardiac_Initials2'])){echo $collectionrow['Cardiac_Initials2'];} else { echo $initianame;}?>"></span>
                                </td>
                            </tr>
                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Edema_Initials2" name="Edema_Initials2" value="<?php if(!empty($collectionrow['Edema_Initials2'])){echo $collectionrow['Edema_Initials2'];} else { echo $initianame;}?>"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Mental_Initials2" name="Mental_Initials2" value="<?php if(!empty($collectionrow['Mental_Initials2'])){echo $collectionrow['Mental_Initials2'];} else { echo $initianame;}?>"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Mobility_Initials2" name="Mobility_Initials2" value="<?php if(!empty($collectionrow['Mobility_Initials2'])){echo $collectionrow['Mobility_Initials2'];} else { echo $initianame;}?>"></span>
                                </td>
                            </tr>

                            <tr>

                                <td>

                                    <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Access_Initials2" name="Access_Initials2" value="<?php if(!empty($collectionrow['Access_Initials2'])){echo $collectionrow['Access_Initials2'];} else { echo $initianame;}?>"></span>
                                </td>
                            </tr>

                        </table>   

                    </td>-->
                    </tr>

                </table>
                <table>
                    <tr>
                        <td>
                    <center> <input type="submit" value="Save Data" class="art-button-green"></center>
                    <input type="hidden" name="Registration_ID" value="<?php echo $Registration_ID; ?>">
                    <input type="hidden" name="dialysis_details_ID" value="<?php echo $dialysis_details_ID; ?>">
                    <input type="hidden" name="Consultant_employee" value="<?php echo $Consultant_employee; ?>">
                    <input type="hidden" name="Payment_Item_Cache_List_ID" value="<?php echo $Payment_Item_Cache_List_ID; ?>">
                     <input type="hidden" name="Payment_Cache_ID" value="<?php echo $Payment_Cache_ID; ?>">
                    <input type="hidden" name="SaveDataCollectionbtn">
                    </td>
                    </tr> 
                </table>
            </form>
        </section>

        <h4 class="h4"><b>Observation charts</b></h4>
        <section id="observation_section">
            <?php include("observatioin_chart.php"); ?>
        </section>
                                        
        <h4 class="h4"><b>Medication charts</b></h4>
        <section id="medication_chart_table">
            <?php // include("medication_chart.php"); ?>
            <?php include("medicationcharts.php"); ?>
        </section>
<style> .addTR{display:none;} </style>
<script> function add_more_rows(){$(".addTR").show();$(".addBTN").hide();} </script>
    </div>
</fieldset>
</center>
<br>
<style>
    label{
        display:inline;
    }
</style>
<!-- @dnm -->
<?php
include"patient_result_summary.php";
?>
<div id="showTempNeckLineReport" style="width:100%;overflow-x:hidden;height:520px;display:none;overflow-y:scroll">
</div>

<div id="showTempNeckLine" style="width:100%;overflow-x:hidden;height:520px;display:none;overflow-y:scroll">
<?php
        
        $neckLineSql = "SELECT * FROM tbl_dialysis_temporary_neck_line WHERE Registration_ID='$Registration_ID'";
        $selectNeckLine = mysqli_query($conn,$neckLineSql); $neckLine = mysqli_fetch_array($selectNeckLine);
        $procedure_date = "'".$neckLine['procedure_date']."'";$to_day = "'".date("Y-m-d")."'";
        $emp_ID="'".$neckLine['Employee_ID']."'"; $Empl_ID="'".$_SESSION['userinfo']['Employee_ID']."'";


    $sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
    while($date = mysqli_fetch_array($sql_date_time)){
        $Current_Date_Time = $date['Date_Time'];
    }
    $Filter_Value = substr($Current_Date_Time,0,11);
    $Start_Date = $Filter_Value.' 00:00';
    $End_Date = $Filter_Value.' 23:59';
    ?>
        <table>
            <tr>
                <td><input type='text' id='Date_From' style="text-align: center" value="<?= $Start_Date ?>" readonly="readonly" placeholder="Start Date"/></td>
                <td><input type='text' id="Date_To" style="text-align: center"value="<?= $End_Date ?>" readonly="readonly" placeholder="End Date"/></td>
                <td>
                    <input type="button" value="PREVIEW" class="art-button-green" onclick="filter_collected_report(<?php echo $Registration_ID; ?>)">
                </td>
            </tr>
        </table>
        <table width="100%" style="background-color:#fff;">
            <tr>
                <form name="" action="Savedialysisclinicalnotes.php" id="savePrepareTempNeckLine" method="post">
                    <td width="50%">
                        <h3>PREPARE FOR THE PROCEDURE</h3>
                        <label for="pre1"><input type="checkbox" name="Get_US_Neck" <?php if($neckLine['Get_US_Neck']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID)?> id="pre1">1. Get US Neck - Preferable with vasculaer probe. </label> <br>
                        <label for="pre2"><input type="checkbox" name="Get_catheter" <?php if($neckLine['Get_catheter']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID)?> id="pre2">2. Get catheter - 11 Fr (18G), 13.5cm average size. </label> <br>
                        <label for="pre3"><input type="checkbox" name="Get_sterile" <?php if($neckLine['Get_sterile']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID)?> id="pre3">3. Get sterile drapers.</label> <br>
                        <label for="pre4"><input type="checkbox" name="Lignocaine" <?php if($neckLine['Lignocaine']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID)?> id="pre4">4. Lignocaine. </label> <br>
                        <label for="pre5"><input type="checkbox" name="Sterile_KY_gel" <?php if($neckLine['Sterile_KY_gel']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID)?> id="pre5">5. Sterile KY gel. </label> <br>
                        <label for="pre6"><input type="checkbox" name="Syringes" <?php if($neckLine['Syringes']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID)?> id="pre6">6. Syringes - 10cc X 2</label> <br>
                        <label for="pre7"><input type="checkbox" name="Normal_saline" <?php if($neckLine['Normal_saline']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID)?> id="pre7">7. Normal saline. </label> <br>
                        <label for="pre8"><input type="checkbox" name="Heparin" <?php if($neckLine['Heparin']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID)?> id="pre8">8. Heparin 5000 unit/ml </label> <br>
                        <label for="pre9"><input type="checkbox" name="Surgical_Blade" <?php if($neckLine['Surgical_Blade']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID)?> id="pre9">9. Surgical Blade</label> <br>
                        <label for="pre10"><input type="checkbox" name="Povidone" <?php if($neckLine['Povidone']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID)?> id="pre10">10. Povidone - Iodine 20mls </label> <br>
                        <label for="pre11"><input type="checkbox" name="Sterile_gloves" <?php if($neckLine['Sterile_gloves']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID)?> id="pre11">11. Sterile gloves x3 pairs </label> <br>
                        <label for="pre12"><input type="checkbox" name="Female_condom" <?php if($neckLine['Female_condom']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID)?> id="pre12">12. Female condom </label> <br>
                        <label for="pre13"><input type="checkbox" name="Sutures" <?php if($neckLine['Sutures']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID)?> id="pre13">13. Sutures - Nylon 2-0 </label> <br>
                        <label for="pre14"><input type="checkbox" name="Dressing_Blandle" <?php if($neckLine['Dressing_Blandle']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID)?> id="pre14">14. Dressing Blandle 1 </label> <br>
                        <label for="pre15"><input type="checkbox" name="Suture_set" <?php if($neckLine['Suture_set']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID)?> id="pre15">15. Suture set 1 </label> 
                        <br><br><br><br><br>

                        REMARKS:
                        <textarea name="prepare_procedure_remarks" id="" <?php if($procedure_date!=$to_day||$emp_ID!=$Empl_ID)?>><?=$neckLine['prepare_procedure_remarks']?></textarea>
                        <!-- <center><input name="save_prepare_procedure" type="submit" value="SAVE" class="art-button-green"></center>  -->
                        <!-- <input type="hidden" name="Registration_ID" value="<?php echo $Registration_ID; ?>"> -->
                        <!-- <input type="hidden" name="prepareTempNeckLine"> -->
                    </td>
                    <td width="50%">
                        <h3>THE PROCEDURE</h3>
                        <label for="pro1"><input type="checkbox" name="Lie_patient" <?php if($neckLine['Lie_patient']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID){}?> id="pro1">1. Lie the compliant patient on a procedure bed. </label> <br>
                        <label for="pro2"><input type="checkbox" name="Explain_what_you_are_doing" <?php if($neckLine['Explain_what_you_are_doing']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID){}?> id="pro2">2. Explain what you are doing. </label> <br>
                        <label for="pro3"><input type="checkbox" name="Sterile_technique" <?php if($neckLine['Sterile_technique']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID){}?> id="pro3">3. Sterile technique - scrub, cap and face mask </label> <br>
                        <label for="pro4"><input type="checkbox" name="Paint" <?php if($neckLine['Paint']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID){}?> id="pro4">4. Paint with povidone and spirit </label> <br>
                        <label for="pro5"><input type="checkbox" name="Drape_the_patient" <?php if($neckLine['Drape_the_patient']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID){}?> id="pro5">5. Drape the patient </label> <br>                    
                        <label for="pro6"><input type="checkbox" name="Drape_the_US_probe" <?php if($neckLine['Drape_the_US_probe']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID){}?> id="pro6">6. Drape the US probe </label> <br>
                        <label for="pro7"><input type="checkbox" name="Arrange" <?php if($neckLine['Arrange']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID){}?> id="pro7">7. Arrange your instruments </label> <br>
                        <label for="pro8"><input type="checkbox" name="Look_for_the_vein" <?php if($neckLine['Look_for_the_vein']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID){}?> id="pro8">8. Look for the vein with the linear US probe </label> <br>
                        <label for="pro9"><input type="checkbox" name="Veno" <?php if($neckLine['Veno']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID){}?> id="pro9">9. Veno - Pucture and insert guide wire ....remove the needle </label> <br>
                        <label for="pro10"><input type="checkbox" name="Check_the_position" <?php if($neckLine['Check_the_position']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID){}?> id="pro10">10. Check the position of the guide wire </label> <br>
                        <label for="pro11"><input type="checkbox" name="Small_Cut" <?php if($neckLine['Small_Cut']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID){}?> id="pro11">11. Small Cut - on the skin </label> <br>
                        <label for="pro12"><input type="checkbox" name="Dilate_the_track" <?php if($neckLine['Dilate_the_track']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID){}?> id="pro12">12. Dilate the track </label> <br>
                        <label for="pro13"><input type="checkbox" name="Insert_the_catheter" <?php if($neckLine['Insert_the_catheter']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID){}?> id="pro13">13. Insert the catheter via the Seldinger method  </label> <br>
                        <label for="pro14"><input type="checkbox" name="Suture_the_cathete" <?php if($neckLine['Suture_the_cathete']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID){}?> id="pro14">14. Suture the catheter </label> <br>
                        <label for="pro15"><input type="checkbox" name="Heparin_lock" <?php if($neckLine['Heparin_lock']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID){}?> id="pro15">15. Heparin lock the catheter </label> <br>
                        <label for="pro16"><input type="checkbox" name="Get_ride" <?php if($neckLine['Get_ride']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID){}?> id="pro16">16. Get ride of the Sharps </label> <br>
                        <label for="pro17"><input type="checkbox" name="Control_CXR" <?php if($neckLine['Control_CXR']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID){}?> id="pro17">17. Control CXR </label> <br>
                        <label for="pro18"><input type="checkbox" name="Counsel_the_patient" <?php if($neckLine['Counsel_the_patient']=='on'){echo 'checked ';}  if($procedure_date!=$to_day||$emp_ID!=$Empl_ID){}?> id="pro18">18. Counsel the patient </label> <br>
                        
                        REMARKS:
                        <textarea name="procedure_remarks" id="" <?php if($procedure_date!=$to_day||$emp_ID!=$Empl_ID){ }?>><?=$neckLine['procedure_remarks']?></textarea>
                        <!-- <center><input name="save_procedure" type="submit" value="SAVE" class="art-button-green"></center> <br>
                        <input type="hidden" name="Registration_ID" value="<?php echo $Registration_ID; ?>">
                        <input type="hidden" name="procedureTempNeckLine"> -->
                    </td>
            </tr>
            <tr>
            <br>
            <td colspan="2">
                <center><input name="save_prepare_procedure" type="submit" value="SAVE" class="art-button-green"></center>  -->
                <input type="hidden" name="Registration_ID" value="<?php echo $Registration_ID; ?>">
                <input type="hidden" name="prepareTempNeckLine">
            </td>
            </tr>
            </form>
        </table>
</div>
<style>
input[type="radio"] {
  -webkit-appearance: checkbox; 
  -moz-appearance: checkbox;    
  -ms-appearance: checkbox;     
}
</style>

<div id="showMonthlyRounds" style="width:100%;overflow-x:hidden;height:520px;display:none;overflow-y:scroll">
    <!-- <center><p id="incidentFormError" class="text-danger"></p></center> -->
    <form name="" action="Savedialysisclinicalnotes.php" id="saveMonthlyHdForm" method="post" >

    <?php include("patient_demografic.php");?>
    <div id="PrevDIV" style="display:none;">
        <table class="table table-hover" style="background-color:#fff;">
            <tr>
                <td> <p> <b>PREVIOUS HEMODIALYSIS MONTHLY ROUNDS</b> <a class="art-button-green pull-right" onclick="$('#PrevDIV').hide();$('#formDIV').show();">CREATE NEW MONTHLY ROUNDS</a></p></td>
            </tr>
        </table>
        <hr>
        <center style="background-color:#fff; padding:20px;">
            <?php 
                $selected = mysqli_query($conn,"
                SELECT monthly_round_id, round_date FROM tbl_dialysis_monthly_rounds 
                        WHERE Registration_ID = '$Registration_ID' ORDER BY monthly_round_id DESC") or die(mysqli_error($conn));
            $no = mysqli_num_rows($selected);
            if ($no > 0) {
                $count_btn=1;
                while ($row = mysqli_fetch_array($selected)) {
                    $monthly_round_id = $row['monthly_round_id']; $round_date = $row['round_date'];?>
                
                    <a class="art-button-green" onclick="get_prev_round('<?=$monthly_round_id?>')"><?=$round_date?></a>
                
                    <?php
                    if($count_btn%6==0){ echo "<br/><br/>"; } $count_btn++;
            
                }
            }else{
                echo "No Previous Saved Fertility Assesssment(s).";  
            }
            ?>
        </center>
            <hr>
    </div>

    <?php
        $round_date = date("Y-m-d");
        $sql = "SELECT * FROM tbl_dialysis_monthly_rounds WHERE Registration_ID='$Registration_ID' AND round_date='$round_date'";
        $selectRound = mysqli_query($conn,$sql); $round = mysqli_fetch_array($selectRound);
    ?>
        <div id="formDIV">
            <table class="table table-hover" style="background-color:#fff;">
                <tr>
                    <td><a class="art-button-green" onclick="$('#PrevDIV').show();$('#formDIV').hide();">PREVIOUS MONTHLY ROUNDS</a></td>
                    <td width="12%">Monthly Round Date </td>
                    <td width="25%">
                        <input type="text" value="<?=$round_date?>" name="Monthly_Round_Date" id="Monthly_Round_Date" class="date" placeholder="Monthly Round Date" autocomplete="off" style="text-align: center;" readonly="readonly">
                    </td>
                </tr>
            </table>

            </table>
                <table class="table table-bordered table-hover" style="background-color:#fff;">
                    <tbody>
                        <tr>
                            <td width="15%">Cause of CKD</td>
                            <td colspan="2"><input type="text" name="Cause_of_CKD" value="<?=$round['Cause_of_CKD']?>"></td>
                            <td width="15%">Other co-morbidities</td>
                            <td colspan="2"><input type="text" name="Other_co_morbidities" value="<?=$round['Other_co_morbidities']?>"></td>
                        </tr>
                        <tr>
                            <td width="15%">Date commencing HD</td>
                            <td colspan="2"><input type="text" name="Date_commencing_HD" value="<?=$round['Date_commencing_HD']?>"></td>
                            <td width="15%">Prescription</td>
                            <td colspan="2"><input type="text" name="Prescription" value="<?=$round['Prescription']?>"></td>
                        </tr>
                        <tr>
                            <td width="15%">PREVIOUS TRANSPLANT</td>
                            <td><label for="previous_transplant_no"><input type="radio" name="previous_transplant" id="previous_transplant_no" <?php if($round['previous_transplant']=='NO'){echo 'checked ';}?> value="NO"> NO </label></td>
                            <td><label for="previous_transplant_yes"><input type="radio" name="previous_transplant" id="previous_transplant_yes" <?php if($round['previous_transplant']=='YES'){echo 'checked ';}?> value="YES"> YES </label></td>
                            <td width="15%">PARATHYROIDECTOMY</td>
                            <td><label for="parathyroidectomy_no"><input type="radio" name="parathyroidectomy" id="parathyroidectomy_no" <?php if($round['parathyroidectomy']=='NO'){echo 'checked ';}?> value="NO"> NO </label></td>
                            <td><label for="parathyroidectomy_yes"><input type="radio" name="parathyroidectomy" id="parathyroidectomy_yes" <?php if($round['parathyroidectomy']=='YES'){echo 'checked ';}?> value="YES"> YES </label></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered table-hover" style="background-color:#fff;">
                    
                    <tr>
                        <td>
                        Drugs
                        <textarea rows="1" id="" name="Antihypertensives_DM" class="Treatment"><?=$round['Antihypertensives_DM']?></textarea>
                        <input type="button" name="showItems" value="Order" class="art-button-green" id="showItems" onclick="addItems('<?php echo $Registration_ID; ?>')"/></td>
                    </tr>
                    <tr>
                        <td>
                        Investigation
                        <textarea rows="1" id="" name="alpha_Vitamin_D" class="laboratoryinvestidation"><?=$round['alpha_Vitamin_D']?></textarea>
                        <input type="button" name="showItems" value="Order" class="art-button-green" id="showItems" onclick="addItems('<?php echo $Registration_ID; ?>')"/></td>
                    </tr>
                    <tr>
                        <td colspan="2">Other<textarea id="" rows="1" name="Other_Medications"><?=$round['Other_Medications']?></textarea></td>
                    </tr>
                </table>
                
                <table class="table table-bordered table-hover" style="background-color:#fff;">
                    <tr>
                        <td width="20%">Concerns from last routine bloods</td>
                        <td><textarea id="" rows="1" name="Concerns_from_last_routine"><?=$round['Concerns_from_last_routine']?></textarea></td>
                        <td width="15%">Complaints today</td>
                        <td><textarea id="" rows="1" name="Complaints_today"><?=$round['Complaints_today']?></textarea></td>
                    </tr>
                    <tr>
                        <td>Examination</td>
                        <td><textarea id="" rows="1" name="Examination"><?=$round['Examination']?></textarea></td>
                        <td>Dry weight</td>
                        <td><textarea id="" rows="1" name="Dry_weight"><?=$round['Dry_weight']?></textarea></td>
                    </tr>
                </table>
                <table class="table table-bordered table-hover" style="background-color:#fff;">
                    <tr>
                        <td width="10%">URR > 70%</td>
                        <td width="23%"><input type="text" name="URR" value="<?=$round['URR']?>"></td>
                        <td width="21%">Vascular access current</td>
                        <td><input type="text" name="Vascular_access" value="<?=$round['Vascular_access']?>"></td>
                    </tr>
                    <tr>
                        <td>Kt/V</td>
                        <td><input type="text" name="Kt_V" value="<?=$round['Kt_V']?>"></td>
                        <td>Venous pressure (not more than BFR) </td>
                        <td><input type="text" name="Venous_pressure" value="<?=$round['Venous_pressure']?>"></td>
                    </tr> 
                    <tr>
                        <td>Clinically</td>
                        <td><input type="text" name="Clinically" value="<?=$round['Clinically']?>"></td>
                        <td>Arterial pressure (-250mmHg)</td>
                        <td><input type="text" name="Arterial_pressure" value="<?=$round['Arterial_pressure']?>"></td>
                    </tr>
                </table>
                <table class="table table-bordered table-hover" style="background-color:#fff;">
                    <tr>
                        <td>Anaemia</td>
                        <td><input type="text" name="Anaemia" value="<?=$round['Anaemia']?>"></td>
                        <td>Fluid status/Hemodynamic stabilty</td>
                        <td><input type="text" name="Fluid_status_Hemodynamic" value="<?=$round['Fluid_status_Hemodynamic']?>"></td>
                    </tr>
                    <tr>
                        <td>Hemoglobin</td>
                        <td><input type="text" name="Hemoglobin" value="<?=$round['Hemoglobin']?>"></td>
                        <td>
                            Dry weight/Fluid status(edema, SOB, JVP, crakles) <br>
                            Vital signs stable throughout treatment
                        </td>
                        <td><input type="text" name="Dry_weight_Fluid_status" value="<?=$round['Dry_weight_Fluid_status']?>"></td>
                    </tr>
                    <tr>
                        <td width="25%">
                            Iron status (at least every 2 months) <br>  
                            Ferritin less than 500ng/ml; TSAT less than 30%
                        </td>
                        <td><textarea name="Iron_status" id="" rows="1"><?=$round['Iron_status']?></textarea></td>
                        <td width="27%">
                            Fluid and salt discussed IDWG acceptable?
                        </td>
                        <td><textarea name="Fluid_and_salt" id="" rows="1"><?=$round['Fluid_and_salt']?></textarea></td>
                    </tr>
                </table>

                <table class="table table-bordered table-hover" style="background-color:#fff;">
                    <tr>
                        <td>PTH</td>
                        <td><input type="text" name="PTH" value="<?=$round['PTH']?>"></td>
                        <th colspan="2"> <span class="pull-right" style="margin-right:130px;">Electrolytes</span></th>
                    </tr>
                    <tr>
                        <td>Phosphate</td>
                        <td><input type="text" name="Phosphate" value="<?=$round['Phosphate']?>"></td>
                        <td>Potassium(3.5-6.0)</td>
                        <td><input type="text" name="Potassium" value="<?=$round['Potassium']?>"></td>
                    </tr>
                    <tr>
                        <td>Calcium(corrected)</td>
                        <td><input type="text" name="Calcium" value="<?=$round['Calcium']?>"></td>
                        <td>Sodium</td>
                        <td><input type="text" name="Sodium" value="<?=$round['Sodium']?>"></td>
                    </tr>
                    <tr>
                        <td>ALP</td>
                        <td><input type="text" name="ALP" value="<?=$round['ALP']?>"></td>
                        <td colspan="2"></td>
                    </tr>
                </table>
                <table class="table table-bordered table-hover" style="background-color:#fff;">
                    <tr>
                        <th colspan="3">Nutrition evaluation</th>
                    </tr>
                    <tr>
                        <td>Albumin</td>
                        <td><input type="text" name="Albumin" value="<?=$round['Albumin']?>"></td>
                        <th>Vaccinations & Serology</th>
                    </tr>
                    <tr>
                        <td>Knowledge on dietary restrictions</td>
                        <td><input type="text" name="Knowledge_on_dietary" value="<?=$round['Knowledge_on_dietary']?>"></td>
                        <td>
                            Hepatitis B <br>vaccination:
                                <input type="text" name="Hepatitis_B_vaccination" value="<?=$round['Hepatitis_B_vaccination']?>" style="width:230px">
                            <br>Pneumovac: 
                                <input type="text" style="width:230px" name="Pneumovac" value="<?=$round['Pneumovac']?>">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            Tranaminase: 
                                <br>ASAT 
                                    <input type="text" name="Tranaminase_ASAT" value="<?=$round['Tranaminase_ASAT']?>" style="width:130px"><br>
                                ALT
                                        <input type="text" name="Tranaminase_ALT" value="<?=$round['Tranaminase_ALT']?>" style="width:130px">
                        </td>
                        <td>
                            HIV HEP B HEPC status: 
                                <input type="text" name="HIV_HEP_B_HEPC_status" value="<?=$round['HIV_HEP_B_HEPC_status']?>" style="width:537px">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td>
                            Transplant plans: 
                                <textarea name="Transplant_plans" id=""><?=$round['Transplant_plans']?></textarea>
                        </td>
                    </tr>
                </table>
                <table class="table table-bordered table-hover" style="background-color:#fff;">
                    <tr>
                        <td>
                            Assessment/Plans
                            <textarea name="Assessment_Plans" id="" rows="3"><?=$round['Assessment_Plans']?></textarea>
                        </td>
                    </tr>
                    <tr><td><center>
                        <input type="submit" class="art-button-green" value="SAVE MONTHLY HEMODIALYSIS ROUND">
                        <input type="hidden" id="Registration_ID" name="Registration_ID" value="<?=$Registration_ID?>">
                        <input type="hidden" id="finance_department_id" name="finance_department_id" value="<?=$finance_department_id?>">
                        <input type="hidden" id="Payment_Cache_ID" name="Payment_Cache_ID" value="<?=$Payment_Cache_ID?>">
                        <input type="hidden" name="saveMonthlyHd">
                        </center></td></tr>
                </table>
        </div>
        </form>
    </div>

<div id="showIncidentRecord" style="width:100%;overflow-x:hidden;min-height:520px;display:none;overflow-y:scroll">
    <center><p id="incidentFormError" class="text-danger"></p></center>
    <form name="saveIncident" action="Savedialysisclinicalnotes.php" id="SaveIncidentform" method="post">
        <table class="table table-bordered table-hover">
            <tr>
                <td>INCITENT TYPE</td>
                <td>
                <label class="labelby"><input type="radio" name="viewby" id="check_patient" class="radioby" <?=$scrollselected?>> Patient </label> &nbsp;&nbsp; 
  <label class="labelby"><input type="radio" name="viewby" id="check_machine" class="radioby"<?=$tabselected?>> Machine </label> 
                </td>
            </tr>
            <tr id="show_patient_name">
                <td>PATIENT NAME</td>
                <td>
                    <input type="text" disabled value="<?=$Patient_Name?>">
                    <input type="hidden" id="Registration_ID" name="Registration_ID" value="<?=$Registration_ID?>">
                </td>
            </tr>
            <tr id="show_machine_name" style="display: none;">
                <td>MACHINE NAME</td>
                <td>
                    <textarea name="machine" id="machine"></textarea>
                </td>
            </tr>
            <tr>
                <td>INCIDENT/EVENT</td>
                <td>
                    <textarea name="event" id="event"></textarea>
                </td>
            </tr>
            <tr>
                <td>ACTION&nbsp;TAKEN</td>
                <td>
                    <textarea name="action" id="action"></textarea>
                </td>
            </tr>
            <tr>
                <td>DATE</td>
                <td>
                <input type="text" autocomplete="off" id="event_date" name="event_date" placeholder="Set an incident date"/>
                </td>
            </tr>
            <tr>
                <td>REPORTED BY</td>
                <td>
                    <input type="text"disabled  value="<?=$_SESSION['userinfo']['Employee_Name'];?>">
                    <input type="hidden" id="Employee_ID" name="Employee_ID" value="<?=$_SESSION['userinfo']['Employee_ID'];?>">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <center><input type="submit" class="art-button-green" value="SAVE INCIDENT"></center>
                    <input type="hidden" name="SubmitIncidents">
                </td>
            </tr>
        </table>
    </form>
</div>

<div id="prevMonthlyRounds" style="width:100%;overflow-x:hidden;min-height:520px;display:none;overflow-y:scroll">
    <div id="prevMonthlyRound"></div>
</div>

<div id="showpatientprescription" style="width:100%;overflow-x:hidden;min-height:520px;display:none;overflow-y:scroll">
    <div id="showpatientprescriptiondata"></div>
</div>


<link rel="stylesheet" href="css/normalize.css">
<link rel="stylesheet" href="css/main.css">
<!--<link rel="stylesheet" href="css/ui.notify.css" media="screen">-->
<link rel="stylesheet" href="css/jquery.steps.css">
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<link rel="stylesheet" href="css/ui.notify.css" media="screen">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script src="js/jquery.notify.min.js"></script> 
<script src="css/jquery.form.js"></script> 
<script src="css/lib/modernizr-2.6.2.min.js"></script>
<script src="css/lib/jquery.cookie-1.3.1.js"></script>
<script src="css/build/jquery.steps.js"></script>



<script>
    $(document).ready(function () {
        $container = $("#container").notify();
    });
</script>

<script>
    function create(template, vars, opts) {
        return $container.notify("create", template, vars, opts);
    }
</script>

<?php
include("./includes/footer.php");
?>

<!-- @dnm -->
<script>
$(document).ready(function () {
    filterPreviousHemodialysisPrescriptions();
    $('#event_date').datetimepicker({value: '', step: 1});
    $("#showIncidentRecord").dialog({autoOpen: false, width: '60%', title: 'INCIDENT RECORD FORM', modal: true, position: 'middle'});
    $("#showTempNeckLine").dialog({autoOpen: false, width: '90%', title: 'TEMPORARY NECK LINE', modal: true, position: 'middle'});
    $("#showTempNeckLineReport").dialog({autoOpen: false, width: '90%', title: 'TEMPORARY NECK LINE REPORT', modal: true, position: 'middle'});
    $("#patientresultsummary").dialog({autoOpen: false, width: '90%', title: 'PATIENT RESULT SUMMARY', modal: true, position: 'middle'});
    $("#showMonthlyRounds").dialog({autoOpen: false, width: '90%', title: 'MONTHLY HEMODIALYSIS ROUNDS', modal: true, position: 'middle'});
    $("#prevMonthlyRounds").dialog({autoOpen: false, width: '80%', title: 'PREVIOUS MONTHLY HEMODIALYSIS ROUNDS', modal: true, position: 'middle'});
    $("#showpatientprescription").dialog({autoOpen: false, width: '95%', title: 'PATIENT PRESCRIPTION', modal: true, position: 'middle'});
});

function get_prev_round(id) {
    if (id == '') {
        return false;
    } else {
        $("#prevMonthlyRounds").dialog("open");
        $.ajax({
            type: 'POST',
            url: 'dialysisPreviewMonthlyRound.php',
            data: {monthly_round_id:id},
            cache: false,
            success: function (html) {
                $('#prevMonthlyRound').html(html);
                // alert(data);
            }
        });
        return false;
    }
}

function calculate_weight_gain() {
    var weight_prev_post_stand = $('#weight_prev_post_stand').val();
    var weight_pre_stand = $('#weight_pre_stand').val();
    
    if(weight_pre_stand!='' && weight_prev_post_stand!=''){
        var weight_Gain = weight_pre_stand-weight_prev_post_stand;
        $('#Weight_Gain').val(weight_Gain);
    }else{
        $('#Weight_Gain').val('');
    }
}

function calculate_weight_removal() {
    var weight_post_stand = $('#Weight_Post_stand').val();
    var weight_pre_stand = $('#weight_pre_stand').val();
    
    if(weight_pre_stand!='' && weight_post_stand!=''){
        var weight_Removal = weight_pre_stand-weight_post_stand;
        $('#Weight_Removal').val(weight_Removal);
    }else{
        $('#Weight_Removal').val('');
    }
}

$('#saveDoctorsOder').submit(function () {
    $(this).ajaxSubmit({
        success: function (data) {
            // alert(data);
            if(data=='ok'){
                alert('Saved Successfully');
                // $("#").dialog("close");
                // create("default", {title: 'Success', text: 'Successfully Saved'});
            }else{
                create("default", {title: 'Error', text: 'An error occured, please try again'}); 
            }
        },

        error: function (data) {
            create("default", {title: 'Error', text: 'An error occured, please try again'});
        }
    });
    return false;
});

$('#savePrepareTempNeckLine').submit(function () {
    $(this).ajaxSubmit({
        success: function (data) {
            // alert(data);
            if(data=='ok'){
                alert('Saved Successfully');
                // $("#").dialog("close");
                // create("default", {title: 'Success', text: 'Successfully Saved'});
            }else{
                create("default", {title: 'Error', text: 'An error occured, please try again'}); 
            }
        },

        error: function (data) {
            create("default", {title: 'Error', text: 'An error occured, please try again'});
        }
    });
    return false;
});

$('#saveProcedureTempNeckLine').submit(function () {
    $(this).ajaxSubmit({
        success: function (data) {
            // alert(data);
            if(data=='ok'){
                alert('Procedure Saved Successfully');
                // $("#").dialog("close");
                // create("default", {title: 'Success', text: 'Successfully Saved'});
            }else{
                create("default", {title: 'Error', text: 'An error occured, please try again'}); 
            }
        },

        error: function (data) {
            create("default", {title: 'Error', text: 'An error occured, please try again'});
        }
    });
    return false;
});


$('#savePatientResultsummary').submit(function () {
    $(this).ajaxSubmit({
        success: function (data) {
            // alert(data);
            if(data=='ok'){
                alert('Procedure Saved Successfully');
                // $("#").dialog("close");
                // create("default", {title: 'Success', text: 'Successfully Saved'});
            }else{
                create("default", {title: 'Error', text: 'An error occured, please try again'}); 
            }
        },

        error: function (data) {
            create("default", {title: 'Error', text: 'An error occured, please try again'});
        }
    });
    return false;
});

$('#saveMonthlyHdForm').submit(function () {
    var Monthly_Round_Date = $('#Monthly_Round_Date').val();
    if(Monthly_Round_Date==''){
        alert("Please Set Monthly Round Date");
        return false;
    }

    var status = confirm("Are you sure you want to save this monthly round?");
    if (status == false) {
        return false;
    } else {
        $(this).ajaxSubmit({
            success: function (data) {
                // alert('data=> '+data);
                if(data=='ok'){
                    $("#showMonthlyRounds").dialog("close");
                    create("default", {title: 'Success', text: 'Successfully Saved'});
                }else{
                    create("default", {title: 'Error', text: 'An error occured, please try again'}); 
                }
            },
            error: function (data) {
                create("default", {title: 'Error', text: 'An error occured, please try again'});
            }
        });
        return false;
    }
});

$('#SaveIncidentform').submit(function () {
        $('#incidentFormError').html('');
        $event = $('#event').val();
        $date = $('#event_date').val();
        if($event==''){
            $('#incidentFormError').html('Please write an incident.');
            return false;
        }else if($date==''){
            $('#incidentFormError').html('Please select an insident date.');
            return false;
        }

        var status = confirm("Are you sure you want to save this?");
        if (status == false) {
            return false;
        } else {
            $(this).ajaxSubmit({
                success: function (data) {
                    if(data=='ok'){
                        $("#showIncidentRecord").dialog("close");
                        create("default", {title: 'Success', text: 'Successfully Saved'});
                    }else{
                        create("default", {title: 'Error', text: 'An error occured, please try again'}); 
                    }
                },

                error: function (data) {
                    create("default", {title: 'Error', text: 'An error occured, please try again'});
                }
            });
            return false;
        }
    });
    

function save_dry_weight(){
        var dry_weight = $("#Dry_Weight").val();
        if(dry_weight==''){
            alert("Dry weight cannot be empty.");
            exit;
        }
        var status = confirm("Are you sure you want to save/update EDW ?");
        if (status == false) {
            return false;
        } else {

            create("default", {title: 'Success', text: 'EDW save/updated successfully'});
        }
}
</script>

<?php
$cookie_name="tab";
if(isset($_COOKIE[$cookie_name])) { 
?>
<script>
    $("#example-tabs").steps({
        headerTag: "h4",
        bodyTag: "section",
        transitionEffect: "slide",
        enableFinishButton: false,
        enablePagination: false,
        enableAllSteps: true,
        titleTemplate: "#title#",
        cssClass: "tabcontrol"
    });
</script>

<style>
    .h4{
        padding:0px;
    }
</style>

<?php }else{ ?>
    <style>
        .h4{
            padding:50px;
        }
        .h4_{
            padding-top:100px;
            padding-bottom:50px;
        }
    </style>
<?php } ?>
<script>

    $('#tab_by').click(function () {

        var tabCookie = $.cookie("tab");
        if(tabCookie!=1){
            $.cookie("tab", 1, { expires : 30 });
        }
        location.reload(); 

    });
    $("#scroll_by").click(function(){
        
        if($.cookie("tab")){
            $.removeCookie("tab");
        }
        location.reload();  

    }); 
</script>


<script>

    $('#check_patient').click(function () {
        $("#show_machine_name").hide();
        $("#show_patient_name").show();

    });
    $("#check_machine").click(function(){
        $("#show_patient_name").hide();
        $("#show_machine_name").show();
    }); 
</script>

<script>


    $('#SaveVitalsform').submit(function () {
        var st = '';
        var status = confirm("Are you sure you want to save this ?");
        if (status == false) {
            return false;
        } else {
            $(this).ajaxSubmit({
                success: function (data) {

                    st = data;
                    alert(st);
                    create("default", {title: 'Success', text: st});

                },

                error: function (data) {
                    st = data;
                    create("default", {title: 'Success', text: st});
                }


            });


            return false;


        }


    });


    $('#MachineAccess').submit(function () {
        var st = '';
        var status = confirm("Are you sure you want to save this ?");
        if (status == false) {
            return false;
        } else {
            $(this).ajaxSubmit({
                success: function (data) {

                    st = data;
                    create("default", {title: 'Success', text: st});

                },

                error: function (data) {
                    st = data;
                    create("default", {title: 'Success', text: st});
                }


            });


            return false;
        }

    });


    $('#Heparainform').submit(function () {

        var st = '';
        var status = confirm("Are you sure you want to save this ?");
        if (status == false) {
            return false;
        } else {
            $(this).ajaxSubmit({
                success: function (data) {

                    st = data;
                    create("default", {title: 'Success', text: st});

                },

                error: function (data) {
                    st = data;
                    create("default", {title: 'Success', text: st});
                }


            });


            return false;


        }

    });


    $('#AccessOrdersform').submit(function () {

        var st = '';
        var status = confirm("Are you sure you want to save this ?");
        if (status == false) {
            return false;
        } else {
            $(this).ajaxSubmit({
                success: function (data) {
                    st = data;
                    create("default", {title: 'Success', text: st});

                },

                error: function (data) {
                    st = data;
                    create("default", {title: 'Success', text: st});
                }

            });

            return false;
        }
    });


    $('#SaveNotesfrm').submit(function () {
        var st = '';
        var status = confirm("Are you sure you want to save this ?");
        if (status == false) {
            return false;
        } else {
            $(this).ajaxSubmit({
                success: function (data) {
                    st = data;
                    create("default", {title: 'Success', text: st});

                },
                error: function (data) {
                    st = data;
                    create("default", {title: 'Success', text: st});
                }
            });
            return false;
        }

    });

    $('#SaveDataCollectionform').submit(function () {

        var st = '';
        var status = confirm("Are you sure you want to save this ?");
        if (status == false) {
            return false;
        } else {
            $(this).ajaxSubmit({
                success: function (data) {

                    st = data;
                    create("default", {title: 'Success', text: st});

                },

                error: function (data) {
                    st = data;
                    create("default", {title: 'Success', text: st});
                }


            });


            return false;


        }

    });




    $('#Observation_Charts').submit(function () {
        var st = '';
        var status = confirm("Are you sure you want to save this ?");
        if (status == false) {
            return false;
        } else {
            $(this).ajaxSubmit({
                success: function (data) {

                    st = data;
                    create("default", {title: 'Success', text: st});

                },

                error: function (data) {
                    st = data;
                    create("default", {title: 'Success', text: st});
                }


            });


            return false;


        }

    });

    $('#Medication_Charts').submit(function () {

        var st = '';
        var status = confirm("Are you sure you want to save this ?");
        if (status == false) {
            return false;
        } else {
            $(this).ajaxSubmit({
                success: function (data) {

                    st = data;
                    create("default", {title: 'Success', text: st});

                },

                error: function (data) {
                    st = data;
                    create("default", {title: 'Success', text: st});
                }


            });


            return false;


        }


    });

    $('#DialysisOrdersform').submit(function () {


        var st = '';
        var status = confirm("Are you sure you want to save this ?");
        if (status == false) {
            return false;
        } else {
            $(this).ajaxSubmit({
                success: function (data) {

                    st = data;
                    create("default", {title: 'Success', text: st});

                },

                error: function (data) {
                    st = data;
                    create("default", {title: 'Success', text: st});
                }


            });


            return false;


        }


    });


    $(document).ready(function () {
        var status = $('.displayStatus').attr('id');
        if (status == 'Not paid') {

            $('#example-tabs').hide();
            $('#warning_text').show();

        } else {

            $('#example-tabs').show();
            // $('#warning_text').show(); 
        }



    });
</script>
<script>
    function filter_patient_result_summary(Registration_ID){
       var Date_From= $('#Date_From1').val();
       var Date_To= $('#Date_To1').val();
       window.open("preview_patient_result_summary.php?Registration_ID="+Registration_ID+"&start_date="+Date_From+"&end_date="+Date_To, "_blank");
    }

    $(document).ready(function () {
        $("#showdataConsult").dialog({autoOpen: false, width: '90%', title: 'SELECT  ITEM TO ORDER', modal: true, position: 'middle'});
    });
   

    function addItems(Registration_ID) {
        var url2 = 'order_type=external&Consultation_Type=Pharmacy' + '&Registration_ID=' + Registration_ID + '&consultation_ID=<?= $consultation_id ?>';
//var url2 = 'order_type=external&Consultation_Type=Pharmacy' + '&Registration_ID=' + Registration_ID + '&External_Payment_Cache_ID=<?= $Payment_Cache_ID ?>&consultation_ID=<?= $consultation_id ?>';

        if (Registration_ID == null || Registration_ID == '') {
            alert('Select a patient to order items');
        }
        $.ajax({
            type: 'GET',
            url: 'doctoritemselectajax.php',
            data: url2,
            cache: false,
            beforeSend: function (xhr) {
                $('#verifyprogress').show();
                $('#myConsult').html('');
            },
            success: function (html) {
                $('#myConsult').html(html);
                $("#showdataConsult").dialog("open");
            }, complete: function (jqXHR, textStatus) {
                $('#verifyprogress').hide();
            }, error: function (jqXHR, textStatus, errorThrown) {
                $('#verifyprogress').hide();
            }
        });
    }
</script>

<script>
    function consultChange(consultation_type) {
        var url2 = 'order_type=external&Consultation_Type=' + consultation_type + '&Registration_ID=<?= $Registration_ID ?>&consultation_ID=<?= $consultation_id ?>';

    $.ajax({
            type: 'GET',
            url: 'doctoritemselectajax.php',
            data: url2,
            cache: false,
            success: function (html) {
                $('#myConsult').html(html);
            }
        });
    }
</script>
<script>
//    function doneDiagonosisselect() {
//        $("#showdataConsult").dialog("close");
//    }
</script>
<script>

    
    function filter_collected_report(Registration_ID){
       var Date_From= $('#Date_From').val();
       var Date_To= $('#Date_To').val();
       $.ajax({
            type: 'GET',
            url: 'temporary_neck_line_report.php',
            data: {Date_From:Date_From,Date_To:Date_To,Registration_ID:Registration_ID},
            success: function (html) {
                $('#showTempNeckLineReport').html(html);
                $("#showTempNeckLineReport").dialog("open");
            }
        });
    }

    function save_observation_chart(Registration_ID,Consultant_employee,dialysis_details_ID){
       var Time= document.getElementsByName('Time')[0].value;
       var BP= document.getElementsByName('BP')[0].value;
       var HR= document.getElementsByName('HR')[0].value;
       var QB= document.getElementsByName('QB')[0].value;
       var QD= document.getElementsByName('QD')[0].value;
       var AP= document.getElementsByName('AP')[0].value;
       var VP= document.getElementsByName('VP')[0].value;
       var FldRmvd= document.getElementsByName('FldRmvd')[0].value;
       var Saline= document.getElementsByName('Saline')[0].value;
       var UFR= document.getElementsByName('UFR')[0].value;
       var TMP= document.getElementsByName('TMP')[0].value;
//       var BVP= document.getElementsByName('BVP')[0].value;
       var Access= document.getElementsByName('Access')[0].value;
       var Notes= document.getElementsByName('Notes')[0].value;
       var Heparin= document.getElementsByName('Heparin')[0].value;
       var  Patient_Payment_ID = <?php echo $Patient_Payment_ID;?>;
       var  consultation_id = <?php echo $consultation_id;?>;
       var  Payment_Item_Cache_List_ID = <?php echo $Payment_Item_Cache_List_ID;?>;
       
       $.ajax({
            type: 'POST',
            url: 'observatioin_chart.php',
            data: {SaveObservationChartbtn:"SaveObservationChartbtn",Heparin:Heparin,Saline:Saline,Registration_ID:Registration_ID,Time:Time,BP:BP,HR:HR,QB:QB,QD:QD,AP:AP,VP:VP,FldRmvd:FldRmvd,UFR:UFR,TMP:TMP,Access:Access,Notes:Notes,Consultant_employee:Consultant_employee,dialysis_details_ID:dialysis_details_ID,Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID},
            success: function (html) {
                $('#observation_section').html(html);
                // alert("Data Succesfully Saved");
            }
        });
    }

    function save_dialysis_oders_form(Registration_ID,Consultant_employee,dialysis_details_ID){
       var Dialyzer= document.getElementsByName('Dialyzer')[0].value;
       var Dialysate= document.getElementsByName('Dialysate')[0].value;
       var Sodium= document.getElementsByName('Sodium')[0].value;
       var UD= document.getElementsByName('UD')[0].value;
       var Temp= document.getElementsByName('Temp')[0].value;
       $.ajax({
            type: 'POST',
            url: 'dialysis_orders_form.php',
            data: {SaveObservationChartbtn:"SaveObservationChartbtn",Registration_ID:Registration_ID,Dialyzer:Dialyzer,Dialysate:Dialysate,Sodium:Sodium,UD:UD,Temp:Temp,Consultant_employee:Consultant_employee,dialysis_details_ID:dialysis_details_ID},
            success: function (html) {
                $('#dialysis_orders_form_table').html(html);
            }
        });
    }

    function save_medication_chart(Registration_ID,dialysis_details_ID,Consultant_employee){
       var Ancillary= document.getElementsByName('Ancillary')[0].value;
       var Indication= document.getElementsByName('Indication')[0].value;
       var Dose= document.getElementsByName('Dose')[0].value;
       var Route= document.getElementsByName('Route')[0].value;
       var Time_chart= document.getElementsByName('Time_chart')[0].value;
       var Initials_charts= document.getElementsByName('Initials_charts')[0].value;
       var Ancillary_1= document.getElementsByName('Ancillary_1')[0].value;
       var Indication_1= document.getElementsByName('Indication_1')[0].value;
       var Dose_1= document.getElementsByName('Dose_1')[0].value;
       var Route_1= document.getElementsByName('Route_1')[0].value;
       var Time= document.getElementsByName('Times')[0].value;
       var Initials= document.getElementsByName('Initials')[0].value;
       $.ajax({
            type: 'POST',
            url: 'medication_chart.php',
            data: {SaveMedicationChartbtn:"SaveMedicationChartbtn",Registration_ID:Registration_ID,Ancillary:Ancillary,Indication:Indication,Dose:Dose,Route:Route,Time_chart:Time_chart,Initials_charts:Initials_charts,Ancillary_1:Ancillary_1,Indication_1:Indication_1,Dose_1:Dose_1,Route_1:Route_1,Time:Time,Initials:Initials,dialysis_details_ID:dialysis_details_ID,Consultant_employee:Consultant_employee},
            success: function (html) {
                $('#medication_chart_table').html(html);
            }
        });
    }

    $('#Date_From').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('#Date_From').datetimepicker({value: '', step: 1});
    $('#Date_To').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:'now'
    });
    
    $('#Date_To').datetimepicker({value: '', step: 1});
    $('#Date_From1').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('#Date_From1').datetimepicker({value: '', step: 1});
    $('#Date_To1').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:'now'
    });
    $('#Date_To1').datetimepicker({value: '', step: 1});
//


    $('#Time_On').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('#Time_On').datetimepicker({value: '', step: 1});
    
    $('#Time_Off').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('#Time_Off').datetimepicker({value: '', step: 1});
    
    $('#Stop_time').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('#Stop_time').datetimepicker({value: '', step: 1});
    
    $('.Time_old_time').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('.Time_old_time').datetimepicker({value: '', step: 1});
    
    
     $('.Time_chart').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('.Time_chart').datetimepicker({value: '', step: 1});
    
    $('.uchunguzi_titiN').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('.uchunguzi_titiN').datetimepicker({value: '', step: 1});
    
    $('.Data_Collection_time_1').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('.Data_Collection_time_1').datetimepicker({value: '', step: 1});
    
    $('.Data_Collection_time_2').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('.Data_Collection_time_2').datetimepicker({value: '', step: 1});
    
    $('.Data_Collection_time_3').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('.Data_Collection_time_3').datetimepicker({value: '', step: 1});
    
    $('.Data_Collection_time_4').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('.Data_Collection_time_4').datetimepicker({value: '', step: 1});
    
    $('.Data_Collection_time_5').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('.Data_Collection_time_5').datetimepicker({value: '', step: 1});
    
    $('.Data_Collection_time_6').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('.Data_Collection_time_6').datetimepicker({value: '', step: 1});
    
    $('.Data_Collection_time_7').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('.Data_Collection_time_7').datetimepicker({value: '', step: 1});
    
    $('.Data_Collection_time_8').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('.Data_Collection_time_8').datetimepicker({value: '', step: 1});
</script>
<script>
function save_medication(){
    var medication_data=[];
    var medication_type=$("#medication_type_new").val();
    var Round_ID='<?= $Round_ID ?>';
    var Registration_ID='<?= $Registration_ID ?>';
    var consultation_ID='<?= $consultation_id ?>';
    var validate_input=0;
      $(".Payment_Item_Cache_List_ID:checked").each(function() {
            var Payment_Item_Cache_List_ID=$(this).val();
//                  var Payment_Item_Cache_List_ID=medication_data.push($(this).val());
         var drip_rate_new =$("#drip_rate_new"+Payment_Item_Cache_List_ID).val()
         var remarks_new =$("#remarks_new"+Payment_Item_Cache_List_ID).val()
         var discontinue ="";
         var discontinue_reason_new =$("#discontinue_reason_new"+Payment_Item_Cache_List_ID).val()
         var route_type_new =$("#route_type_new"+Payment_Item_Cache_List_ID).val()
         var amount_given_new =$("#amount_given_new"+Payment_Item_Cache_List_ID).val()
         var medication_time_new =$("#medication_time_new"+Payment_Item_Cache_List_ID).val()
         var Item_ID =$("#Item_ID"+Payment_Item_Cache_List_ID).val()
         var dosage_new =$("#dosage_new"+Payment_Item_Cache_List_ID).val()
         if(dosage_new==""){
            $("#dosage_new"+Payment_Item_Cache_List_ID).css("border","2px solid red"); 
            validate_input++;
         }else{
             $("#dosage_new"+Payment_Item_Cache_List_ID).css("border",""); 
         }
         if(route_type_new==""){
            $("#route_td_"+Payment_Item_Cache_List_ID).css("border","2px solid red"); 
            validate_input++;
         }else{
             $("#route_td_"+Payment_Item_Cache_List_ID).css("border",""); 
         }
//               if(drip_rate_new==""){
//                  $("#drip_rate_new"+Payment_Item_Cache_List_ID).css("border","2px solid red"); 
//                  validate_input++;
//               }else{
//                  $("#drip_rate_new"+Payment_Item_Cache_List_ID).css("border",""); 
//               }
         if(remarks_new==""){
            $("#remarks_new"+Payment_Item_Cache_List_ID).css("border","2px solid red"); 
            validate_input++;
         }else{
            $("#remarks_new"+Payment_Item_Cache_List_ID).css("border",""); 
         }
         if(amount_given_new==""){
            $("#amount_given_new"+Payment_Item_Cache_List_ID).css("border","2px solid red"); 
            validate_input++;
         }else{
            $("#amount_given_new"+Payment_Item_Cache_List_ID).css("border",""); 
         }
         if(medication_time_new==""){
            $("#medication_time_new"+Payment_Item_Cache_List_ID).css("border","2px solid red"); 
            validate_input++;
         }else{
            $("#medication_time_new"+Payment_Item_Cache_List_ID).css("border",""); 
         }
         if($("#discontinue_"+Payment_Item_Cache_List_ID).is(":checked")){
             discontinue="yes";
             if(discontinue_reason_new==""){
                 $("#discontinue_reason_new"+Payment_Item_Cache_List_ID).css("border","2px solid red");
                 validate_input++;
             }
         }else{
             $("#discontinue_reason_new"+Payment_Item_Cache_List_ID).css("border","");
             discontinue="no";
         }
          medication_data.push(Payment_Item_Cache_List_ID+"unganisha"+drip_rate_new+"unganisha"+remarks_new+"unganisha"+discontinue+"unganisha"+discontinue_reason_new+"unganisha"+route_type_new+"unganisha"+amount_given_new+"unganisha"+medication_time_new+"unganisha"+Item_ID+"unganisha"+dosage_new)
    });
    if(medication_data.length>0){
          if(validate_input<=0){
              if(confirm("Are you sure you want to save these medications?")){
                  $.ajax({
                      type:'POST',
                      url:'ajax_save_inpatient_medication.php',
                      data:{medication_type:medication_type,Round_ID:Round_ID,Registration_ID:Registration_ID,medication_data:medication_data,consultation_ID:consultation_ID},
                      success:function(data){
                          $("#feedback_message2").html(data);

                          //Inpatient_Nurse_Medicine
                          setTimeout(function(){ location.reload() }, 1000);
                      }
                  });
              }
          }
      }else{
         $(".checkbox_select").css("border","2px solid red"); 
      }
  }
  function verify_if_this_user_login_to_dispencing() {
        var not_login_to_phamacy = '<?= $not_login_to_phamacy ?>';
        if (not_login_to_phamacy == "yes") {
            $("#login_to_phamacy_from_billing").dialog("open");
            console.log(not_login_to_phamacy);
        } else {
            document.location = "automatic_login_to_dispencing_from_bill.php?Registration_ID=<?= $Registration_ID ?>&Check_In_ID=<?= $Check_In_ID ?>";
        }
    }
    $(document).ready(function() {
        $("#login_to_phamacy_from_billing").dialog({
            autoOpen: false,
            width: "60%",
            height: 240,
            title: ' ~ Login To Dispecing Work',
            modal: true
        });
    });
</script>