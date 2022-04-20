<?php
session_start();
include("./includes/header.php");
include("./includes/connection.php");
$Patient_Picture = "";
$national_id = "";
$Religion_Name = "";
$village = "";
$Denomination_Name = "";
$item_update_api = "";
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Reception_Works'])) {
        if ($_SESSION['userinfo']['Reception_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
}
$pfs3_checked_true = '';
$Employee_Name = $_SESSION['userinfo']['Employee_Name'];

$is_perf_by_signe_off = $_SESSION['hospitalConsultaioninfo']['req_perf_by_signed_off'];
$is_const_per_day_count = $_SESSION['hospitalConsultaioninfo']['en_const_per_day_count'];



if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = '';
}
$result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Date_Of_Birth FROM   tbl_patient_registration WHERE Registration_ID='$Registration_ID'"));
$Date_Of_Birth_check = $result['Date_Of_Birth'];

$pf3_ID = '';

if (isset($_POST['pf3_Description'])) {
    $pf3_Description = mysqli_real_escape_string($conn, $_POST['pf3_Description']);
    $Police_Station = mysqli_real_escape_string($conn, $_POST['Police_Station']);
    $Pf3_Number = mysqli_real_escape_string($conn, $_POST['Pf3_Number']);
    $Relative = mysqli_real_escape_string($conn, $_POST['Relative']);
    $Phone_No_Relative = mysqli_real_escape_string($conn, $_POST['Phone_No_Relative']);
    $Reason_ID = mysqli_real_escape_string($conn, $_POST['P_Reason']);

    $file_name = $_FILES['pf3attachment']['name'];
    $file_size = $_FILES['pf3attachment']['size'];
    $file_type = $_FILES['pf3attachment']['type'];
    $file_tmp_name = $_FILES['pf3attachment']['tmp_name'];

    if (!empty($file_name)) {
        $file_name = $Registration_ID . date('Ymdhsi') . $file_name;
    } else {
        $file_name = '';
    }

    $mysql = mysqli_query($conn, "INSERT INTO tbl_pf3_patients VALUES('','$Registration_ID','','$file_name',NOW(),'$pf3_Description','$Police_Station','$Pf3_Number','$Relative','$Phone_No_Relative','$Reason_ID')") or die(mysqli_error($conn));
    if ($mysql) {
        $pfs3_checked_true = "checked='true'";
    }
    if (!empty($file_name)) {
        move_uploaded_file($file_tmp_name, 'pf3_attachment/' . $file_name);
    }

    $rs = mysqli_query($conn, "SELECT pf3_ID FROM tbl_pf3_patients ORDER BY pf3_ID DESC LIMIT 1");
    $row = mysqli_fetch_assoc($rs);

    $pf3_ID = $row['pf3_ID'];
}
?>

<input type="hidden" value="<?php echo $Date_Of_Birth_check; ?>" id="Date_Of_Birth_check">
<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Reception_Works'] == 'yes') {
?>
        <a href='searchvisitorsoutpatientlist.php?SearchVisitorsOutPatientList=SearchVisitorsOutPatientListThisPage' class='art-button-green'>
            REGISTERED PATIENTS
        </a>
    <?php

    }
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Reception_Works'] == 'yes') {
    ?>
        <a href='searchvisitorsdeceasedpatientlist.php?SearchVisitorsOutPatientList=SearchVisitorsOutPatientListThisPage' class='art-button-green'>
            DECEASED PATIENTS
        </a>
<?php

    }
}
?>
<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Reception_Works'] == 'yes') {
?>
        <!--<a href='registerpatient.php?RegisterPatient=RegisterPatientThisPage' class='art-button-green'>
            ADD NEW PATIENT
        </a>-->
<?php

    }
}
?>


<?php
if (isset($_SESSION['userinfo']) && isset($_GET['Registration_ID']) && $_GET['Registration_ID'] != null && $_GET['Registration_ID'] != '') {
?>
    <a href='editpatient.php?Registration_ID=<?php echo $Registration_ID; ?>&VisitorFormThisPatient=VisitorFormThisPatientThisPage' class='art-button-green'>
        EDIT PATIENT
    </a>
<?php
} ?>

<?php
if (isset($_SESSION['userinfo'])) {
?>
    <a href='./receptionpricelist.php?PriceList=PriceListThisPage' class='art-button-green'>
        PRICE LIST
    </a>
<?php
} ?>

<?php
if (strtolower($_SESSION['systeminfo']['Mobile_Payment']) == 'yes') {
?>
    <input type="button" name="Adhock_Search" id="Adhock_Search" value="Adhock SEARCH" class="art-button hide" onclick="Search_ePayment_Details()">
<?php

}
?>
<hr />
<a href='viewappointmentPage.php?from_reception=yes' class='art-button-green'>VIEW APPOINTMENT</a>
<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes' || $_SESSION['userinfo']['Reception_Works'] == 'yes') {
?>
        <a href='receptionworkspage.php?ReceptionWork=ReceptionWorkThisPage' class='art-button-green'>
            BACK
        </a>
<?php

    }
}
?>

<?php
/*  $Today_Date = mysqli_query($conn,"select now() as today");
  while($row = mysqli_fetch_array($Today_Date)){
  $original_Date = $row['today'];
  $new_Date = date("Y-m-d", strtotime($original_Date));
  $Today = $new_Date;

  $age = $Today - $original_Date;
  } */
?>

<!-- new date function (Contain years, Months and days)-->
<?php
$Today_Date = mysqli_query($conn, "select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}
?>
<!-- end of the function -->

<?php
//    select patient information to perform check in process
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
    $select_Patient = mysqli_query($conn, "SELECT
                            pr.patient_type,pr.rank,pr.Status,pr.service_no,pr.dependancy_id,pr.dependecny_service_no,pr.military_unit,
                            pr.Old_Registration_Number,pr.Title,pr.Patient_Name,
                                pr.Date_Of_Birth,pr.Patient_Picture,
                                    pr.Gender,Religion_Name,Denomination_Name,
					pr.Country,pr.Region,pr.District,pr.Ward,pr.Tribe,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID,
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,
                                                        Registration_ID,sp.Sponsor_ID, sp.Exemption,pr.Diseased,pr.national_id,
                                                        village
                                      FROM tbl_patient_registration pr LEFT JOIN tbl_denominations de ON de.Denomination_ID=pr.Denomination_ID LEFT JOIN tbl_religions re  ON re.Religion_ID=de.Religion_ID,
                                      tbl_sponsor sp
                                      WHERE pr.Sponsor_ID = sp.Sponsor_ID AND
                                      Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);

    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) { //
            $patient_type = $row['patient_type'];
            $rank = $row['rank'];
            // $Status = $row['Status'];
            $service_no = $row['service_no'];
            $dependecny_service_no = $row['dependecny_service_no'];
            $military_unit = $row['military_unit'];
            $dependancy_id = $row['dependancy_id'];



            $Registration_ID = $row['Registration_ID'];
            $Old_Registration_Number = $row['Old_Registration_Number'];
            $Title = $row['Title'];
            $Patient_Name = ucwords(strtolower($row['Patient_Name']));
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $village = mysqli_real_escape_string($conn, $row['village']);
            $Country = $row['Country'];
            $Region = $row['Region'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $District = $row['District'];
            $Ward = $row['Ward'];
            $Tribe = $row['Tribe'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Member_Number = $row['Member_Number'];
            $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
            $Phone_Number = $row['Phone_Number'];
            $Email_Address = $row['Email_Address'];
            $Occupation = $row['Occupation'];
            $Employee_Vote_Number = $row['Employee_Vote_Number'];
            $Emergence_Contact_Name = ucwords(strtolower($row['Emergence_Contact_Name']));
            $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
            $Company = $row['Company'];
            $Employee_ID = $row['Employee_ID'];
            $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
            $Exemption = strtolower($row['Exemption']); //
            $Diseased = strtolower($row['Diseased']);
            $national_id = $row['national_id'];
            $Patient_Picture = $row['Patient_Picture'];
            $Religion_Name = $row['Religion_Name'];
            $Denomination_Name = $row['Denomination_Name'];
            // echo $Ward."  ".$District."  ".$Ward; exit;
        }

        /* $age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
          if($age == 0){
          $date1 = new DateTime($Today);
          $date2 = new DateTime($Date_Of_Birth);
          $diff = $date1 -> diff($date2);
          $age = $diff->m." Months";
          }
          if($age == 0){
          $date1 = new DateTime($Today);
          $date2 = new DateTime($Date_Of_Birth);
          $diff = $date1 -> diff($date2);
          $age = $diff->d." Days";
          } */
        $select_ward = mysqli_query($conn, "SELECT w.Ward_ID, w.Ward_Name FROM tbl_ward w, tbl_patient_registration pr WHERE  w.Ward_ID = pr.Ward_ID  AND pr.Registration_ID = $Registration_ID") or die(mysqli_error($conn));
        $ward = mysqli_fetch_assoc($select_ward)['Ward_Name'];
        $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
        // if($age == 0){

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";

        /* }
          if($age == 0){
          $date1 = new DateTime($Today);
          $date2 = new DateTime($Date_Of_Birth);
          $diff = $date1 -> diff($date2);
          $age = $diff->d." Days";
          } */
    } else {
        $Religion_Name = "";
        $Denomination_Name = "";
        $Registration_ID = '';
        $Old_Registration_Number = '';
        $Title = '';
        $Patient_Name = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Country = '';
        $Region = '';
        $District = '';
        $Ward = '';
        $Tribe = '';
        $Sponsor_ID = '';
        $Guarantor_Name = '';
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
        $Exemption = 'no';
        $Diseased = '';
        $patient_type = "";
        $rank = "";
        $Status = "";
        $service_no = "0";
        $dependecny_service_no = "";
        $military_unit = "";
        $dependancy_id = "";
    }
} else {
    $Registration_ID = '';
    $Old_Registration_Number = '';
    $Title = '';
    $Patient_Name = '';
    $Date_Of_Birth = '';
    $Gender = '';
    $Country = '';
    $Region = '';
    $District = '';
    $Ward = '';
    $Tribe = '';
    $Sponsor_ID = '';
    $Guarantor_Name = '';
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
    $Exemption = 'no';
    $Diseased = '';
    $service_no = "0";
}
if ($service_no == "") {
    $service_no = "-";
}
//Check if Exemption details available
$Exemption_Details = 'available';
if ($Exemption == 'yes') {
    $verify = mysqli_query($conn, "select msamaha_ID from tbl_msamaha where Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($verify);
    if ($nm < 1) {
        $Exemption_Details = 'not available';
    }
}
//SELECT LAST VISIT DATE
$lastvisitdate = mysqli_query($conn, "select Check_In_Date from tbl_check_in where Registration_ID = '$Registration_ID' ORDER BY Check_In_ID DESC LIMIT 1") or die(mysqli_error($conn));
$lastvisit = mysqli_fetch_assoc($lastvisitdate);
$Last_Check_In_Date = $lastvisit['Check_In_Date'];
?>
<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>

<?php
//get branch id
if (isset($_SESSION['userinfo']['Branch_ID'])) {
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
} else {
    $Branch_ID = 0;
}

$get_reception_setting = mysqli_query($conn, "select Reception_Picking_Items from tbl_system_configuration where branch_id = '$Branch_ID'") or die(mysqli_error($conn));
$no = mysqli_num_rows($get_reception_setting);
if ($no > 0) {
    while ($data = mysqli_fetch_array($get_reception_setting)) {
        $Reception_Picking_Items = $data['Reception_Picking_Items'];
    }
} else {
    $Reception_Picking_Items = 'no';
}
?>

<style>
    table,
    tr,
    td {
        border-collapse: collapse !important;
        border: none !important;
    }
</style>

<br>
<form action='#' method='post' name='myForm' id='checkmyForm' enctype="multipart/form-data">
    <fieldset style="margin-top:16px">
        <legend align=center><b>VISITORS PAGE</b></legend>
        <center>

            <table width=100%>
                <tr>
                    <td>
                        <table width=100%>
                            <tr>

                                <td style='text-align: right;color:red; width:12% !important;'><b>Last Visit Date</b></td>
                                <td style='text-align: right; width:17% !important'>
                                    <input type='text' name='Visit_Date' style="width:100%;" readonly='readonly' id='dateee' value='<?= $Last_Check_In_Date; ?>'>
                                </td>


                                <td style="text-align: right;" width="12%"><b>Patient Number</b></td>
                                <td style="width:17%">
                                    <input type="text" name="Patient_No" id="Patient_No" readonly="readonly" value="<?= $Registration_ID; ?>" placeholder="Patient Number">
                                </td>

                                <td style="text-align: right; width:12%">
                                    <label for="Diseased" style="font-weight:normal">
                                        National ID
                                    </label>
                                </td>
                                <td style="width:17%">
                                    <input type="text" value="<?= $national_id ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td style='text-align: right; width:12%'>Patient Name</td>
                                <?php $Patient_Name = htmlspecialchars($Patient_Name, ENT_QUOTES) ?>
                                <td style="width:17%"><input type='text' name='Patient_Name' id='Patient_Name' disabled='disabled' value='<?php echo ucwords(strtolower($Patient_Name)); ?>'></td>

                                <td style='text-align: right;width:12%'>Patient Old Number</td>
                                <td style="width:17%">
                                    <input type='text' name='Old_Registration_Number' disabled='disabled' id='Old_Registration_Number' value='<?php echo $Old_Registration_Number; ?>'>
                                </td>

                                <td style='text-align: right; width:12%;'>Visit Date</td>
                                <td style="width:17%"><input type='text' name='Visit_Date' readonly='readonly' id='dateee' value='<?php echo $Today; ?>'></td>
                            </tr>
                            <tr>
                                <?php
                                $select_mil_personnel = mysqli_query($conn, "SELECT p.Patient_Name,p.service_no,p.rank,p.military_unit FROM tbl_patient_registration p WHERE service_no='$service_no' LIMIT 1") or die(mysqli_error($conn));
                                if (mysqli_num_rows($select_mil_personnel) > 0) {
                                    $rows_mlp = mysqli_fetch_assoc($select_mil_personnel);
                                    $miltry_psrnel = $rows_mlp['Patient_Name'];
                                    $service_no = $rows_mlp['service_no'];
                                    $rank = $rows_mlp['rank'];
                                    $military_unit = $rows_mlp['military_unit'];
                                } else {
                                    $miltry_psrnel = "";
                                }
                                ?>
                                <td style='text-align: right; width:12%'>Military Personnel</td>
                                <td style="widht:17%;"><input type="text" readonly='readonly' value="<?= $miltry_psrnel ?>"></td>

                                <td style='text-align: right'>Country</td>
                                <td><input type='text' name='Country' disabled='disabled' id='Country' value='<?php echo $Country; ?>'></td>


                                <td style='text-align: right'>Relative Contact Number</td>
                                <td><input type='text' name='Ward' id='Ward' disabled='disabled' value='<?php echo $Emergence_Contact_Number; ?>'></td>

                            </tr>
                            <tr>
                                <td style='text-align: right'>Service No</td>
                                <td><input type="text" readonly='readonly' value="<?= $service_no ?>"></td>

                                <td style='text-align: right'>Region</td>
                                <td><input type='text' name='Region' id='Region' disabled='disabled' value='<?php echo $Region; ?>'></td>

                                <td style='text-align: right'>Relative Contact Name</td>
                                <td><input type='text' name='Relative Contact Name' disabled='disabled' id='Relative Contact Name' value='<?php echo $Emergence_Contact_Name; ?>'></td>

                            </tr>
                            <tr>
                                <td style='text-align: right'>Rank</td>
                                <td><input type="text" readonly='readonly' value="<?= $rank ?>"></td>

                                <?php
                                $str = $District;
                                $pattern = "/^[1|2|3|4|5|6|7|8|9]/i";
                                $check = preg_match($pattern, $str);
                                if ($check == 1) {
                                    //select district name
                                    $sql_select_district_name_result = mysqli_query($conn, "SELECT District_Name FROM tbl_district WHERE District_ID='$District'") or die(mysqli_error($conn));
                                    $District_Name = "";
                                    if (mysqli_num_rows($sql_select_district_name_result) > 0) {
                                        $District_Name = mysqli_fetch_assoc($sql_select_district_name_result)['District_Name'];
                                        $District = $District_Name;
                                    }
                                } else {
                                    $District = trim($District);
                                    if ($District == '' || $District == NULL) {
                                        $District = '';
                                    }
                                }
                                ?>
                                <td style='text-align: right'>District</td>
                                <td><input type='text' name='District' id='District' disabled='disabled' value='<?php echo $District; ?>'></td>
                                <td style="text-align: right;">
                                    <label for="Diseased" style="font-weight:normal">Religion</label>
                                </td>
                                <td>
                                    <input disabled='disabled' type="text" value="<?= $Religion_Name ?>" />
                                </td>
                            </tr>
                            <tr>




                                <td style='text-align: right'>Unit</td>
                                <td><input type="text" readonly='readonly' value="<?= $military_unit ?>"></td>


                                <td style='text-align: right'>Ward</td>
                                <td>
                                    <?php
                                    $str = $Ward;
                                    $pattern = "/^[1|2|3|4|5|6|7|8|9]/i";
                                    $check = preg_match($pattern, $str);
                                    if ($check == 1) {
                                        $select_ward = mysqli_query($conn, "SELECT Ward_Name FROM tbl_ward WHERE Ward_ID = '$Ward'");
                                        $ward_name = mysqli_fetch_assoc($select_ward)['Ward_Name'];
                                    } else {
                                        $select_ward = mysqli_query($conn, "SELECT Ward FROM tbl_patient_registration WHERE Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                                        $ward_name = mysqli_fetch_assoc($select_ward)['Ward'];

                                        if(trim($ward_name) == "Select Ward") {
                                            $ward_name = "";
                                        } else if(trim($ward_name) == "Select") {
                                            $ward_name = "";
                                        } else {}
                                    }

                                    ?>
                                    <input type='text' name='Ward' id='Ward' disabled='disabled' value='<?= $ward_name; ?>'>
                                </td>
                                <td style="text-align: right;">
                                    <label for="village" style="font-weight:normal">Denomination</label>
                                </td>
                                <td> <input type='text' name='village' id='village' disabled='disabled' value='<?php echo $Denomination_Name; ?>'></td>

                            </tr>
                            <tr>
                                <td style='text-align: right'>Dependant ID</td>
                                <td><input type="text" readonly='readonly' value="<?= $dependancy_id ?>"></td>
                                <?php
                                $str = $village;
                                $pattern = "/^[1|2|3|4|5|6|7|8|9]/i";
                                $check = preg_match($pattern, $str);          
                                if ($check == 1) {
                                    $select_village = mysqli_query($conn, "SELECT village_name FROM tbl_village WHERE village_id = '$village'");
                                    $village = mysqli_fetch_assoc($select_village)['village_name'];
                                } else {
                                    $select_village = mysqli_query($conn, "SELECT village FROM tbl_patient_registration WHERE Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                                    $village = mysqli_fetch_assoc($select_village)['village'];

                                    if(trim($village) == "Select Village") {
                                        $village = "";
                                    } else if(trim($village) == "Select") {
                                        $village = "";
                                    } else {}
                                }

                                ?>

                                <td style="text-align: right;">
                                    <label for="village" style="font-weight:normal">Village/Street</label>
                                </td>
                                <td> <input type='text' name='village' id='village' disabled='disabled' value='<?= $village ?>'></td>
                                <!-- <td> <input type='text' name='village' id='village' disabled='disabled' value='<?= $village ?><?php echo getVillageName($village); ?>'></td> -->


                                <td style='text-align: right'>Tribe</td>
                                <td>
                                    <?php
                                    $Tribe = mysqli_real_escape_string($conn, $Tribe);
                                    ?>
                                    <input type='text' name='Tribe' id='Tribe' disabled='disabled' value='<?php echo ucwords(strtolower(getTribeName($Tribe))); ?>'>
                                </td>

                            </tr>
                            <tr>
                                <td style='text-align: right'>Gender</td>
                                <td><input type='text' name='Gender' id='Gender' disabled='disabled' value='<?php echo $Gender; ?>'>
                                </td>
                                <td style='text-align: right'>Sponsor</td>
                                <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>

                                <td style='text-align: right'>Occupation</td>
                                <td><input type='text' name='Occupation' id='Occupation' disabled='disabled' value='<?php echo $Occupation; ?>'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right'>Age</td>
                                <td><input type='text' name='Age' id='Age' disabled='disabled' value='<?php echo $age; ?>'></td>

                                <td style='text-align: right; width:12%'>Card Number</td>
                                <td width="17%">
                                    <table width="100%" cellpadding="0" cellspacing="0" style="margin:0px; border:0px;" height="80%">
                                        <tr>

                                            <?php
                                            $from_afya_cardMember_Number = "";
                                            if (isset($_GET['from_afya_cardMember_Number'])) {
                                                $from_afya_cardMember_Number = mysqli_real_escape_string($conn, $_GET['from_afya_cardMember_Number']);
                                            }
                                            ?>

                                            <?php
                                            //if(empty($from_afya_cardMember_Number)){
                                            ?>
                                            <!-- <td><input type='text' name='Member_Number' disabled='disabled' id='Member_Number' value='<?php echo $Member_Number; ?>'> -->
                                            <?php
                                            // }else{

                                            ?>

                                            <td width="200px"><input type='text' name='Member_Number' value="<?= $Member_Number ?>" id='Member_Number' placeholder='Write Card Number'>
                                                <input type="hidden" id="hidden_card_number" value="<?= $Member_Number; ?>">
                                            </td>

                                            <?php
                                            //}
                                            ?>





                                            <!--
                                        <td width="40">
                                            <?php
                                            if (isset($_GET['Registration_ID'])) {
                                                if ((strpos(strtolower($Guarantor_Name), 'nhif')) !== false) {
                                            ?>
                                                    <input type="button" value="NHIF-Authorize" onclick="authorizeNHIF('<?php echo $Member_Number; ?>');" class="art-button" />
                                                    <?php

                                                }
                                            }
                                                    ?>
                                        </td> -->
                                            <?php

                                            $nhif_server_configuration = mysqli_query($conn, "SELECT configvalue FROM tbl_config WHERE configname='NhifApiConfiguration'") or die(mysqli_error($conn));
                                            if (mysqli_num_rows($nhif_server_configuration) > 0) {
                                                $nhif_server_configuration = mysqli_fetch_assoc($nhif_server_configuration)['configvalue'];

                                                $sql_select_external_nhif_server_url_result = mysqli_query($conn, "SELECT configvalue FROM tbl_config WHERE configname='NhifExternalServerUrl'") or die(mysqli_error($conn));
                                                $extenal_nhif_server_url = mysqli_fetch_assoc($sql_select_external_nhif_server_url_result)['configvalue'];
                                                if ($nhif_server_configuration == "singleserver") {
                                                    $extenal_nhif_server_url = "";
                                                }
                                            }
                                            ?>
                                            <td width="20">
                                                <?php
                                                if (isset($_GET['Registration_ID'])) {
                                                    if ((strpos(strtolower($Guarantor_Name), 'nhif')) !== false) {
                                                ?>
                                                        <input type="button" value="NHIF-Authorize" onclick="authorizeNHIF('<?php echo $Member_Number; ?>','<?= $extenal_nhif_server_url ?>');" class="btn btn-primary btn-xs" />
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </td>

                                        </tr>
                                    </table>
                                </td>

                                <td style='text-align: right'>Telephone</td>
                                <td><input type='text' name='Phone_Number' disabled='disabled' id='Phone_Number' value='<?php echo $Phone_Number; ?>'>
                                </td>
                            </tr>
                            <tr>
                                <!-- <td style='text-align: right'>Patient Number</td>
                                <td><input type='text' name='Patient_Number' disabled='disabled' id='Patient_Number' value='<?php echo $Registration_ID; ?>'></td> -->
                                <!-- <td style='text-align: right'>Country</td>
                                <td><input type='text' name='Country' disabled='disabled' id='Country' value='<?php echo $Country; ?>'></td> -->

                            </tr>
                            <tr>
                                <!-- <td style='text-align: right'>Patient Old Number</td>
                                <td>
                                <input type='text' name='Old_Registration_Number' disabled='disabled' id='Old_Registration_Number' value='<?php echo $Old_Registration_Number; ?>'>
                                </td> -->

                                <!-- <td style='text-align: right'>Region</td>
                                <td><input type='text' name='Region' id='Region' disabled='disabled' value='<?php echo $Region; ?>'></td>  -->
                            </tr>
                            <tr>
                                <!-- <td style='text-align: right'>Sponsor</td>
                                <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td> -->

                                <!-- <td style='text-align: right'>District</td>
                                <td><input type='text' name='District' id='District' disabled='disabled' value='<?php echo $District; ?>'></td>  -->
                            </tr>
                            <tr id="referral_row" style="display: none">
                                <td style='text-align: right'>Refferal number</td>
                                <td>
                                    <input type="text" id="referral_no_txt" name="refferal_number" placeholder="Enter Refferal number" />
                                </td>
                                <td style='text-align:right'>Referral Reason</td>
                                <td><input type="text" placeholder="Enter Refferal Reason" id="referral_reason" name="referral_reason" /></td>
                                <td style='text-align:right'>Referral Letter</td>
                                <td><input type="file" id="referral_reason" name="referral_letter" /></td>
                                </r>
                            <tr>
                                <?php
                                unset($_SESSION['NHIF_Member']);
                                if (isset($_GET['Registration_ID'])) {
                                    if ((strpos(strtolower($Guarantor_Name), 'nhif')) !== false) {
                                ?>
                                        <!--		NHIF VERIFICATION FUNCTION		-->
                                        <script src="js/token.js"></script>
                                <?php

                                    }
                                }
                                ?>
                                <!-- <td style='text-align: right'>Membership Number</td>
                            <td width="35%">
                                <table width="100%" cellpadding="0" cellspacing="0" style="margin:0px; border:0px;" height="80%">
                                    <tr>
                                        <td><input type='text' name='Member_Number' disabled='disabled' id='Member_Number' value='<?php echo $Member_Number; ?>'>
                                        </td>


                                        <td width="40">
                                            <?php
                                            if (isset($_GET['Registration_ID'])) {
                                                if (strtolower($Guarantor_Name) == 'nhif') {
                                            ?>
                                                    <input type="button" value="NHIF-Authorize" onclick="authorizeNHIF('<?php echo $Member_Number; ?>');" class="art-button" />
                                                    <?php

                                                }
                                            }
                                                    ?>
                                        </td>
                                    </tr>
                                </table>
                            </td> -->
                                <!-- <td style='text-align: right'>Ward</td>
                            <td><input type='text' name='Ward' id='Ward' disabled='disabled' value='<?php echo $Ward; ?>'>
                            </td>  -->
                            </tr>

                            <tr>
                                <!-- <td style="text-align: right;">
                        <label for="Diseased" style="font-weight:normal">National ID</label>
                    </td>
                    <td>
                        <input type="text" value="<?= $national_id ?>" />
                    </td> -->



                                <!-- <td style='text-align: right'>Employee Vote</td>
                    <td><input type='text' name='Employee_Vote_Number' autocomplete='off' id='Employee_Vote_Number' value="<?php echo $Employee_Vote_Number; ?>" readonly='readonly'>
                </td> -->
                                <td style='text-align: right'>Employee Vote</td>
                                <td><input type='text' name='Employee_Vote_Number' autocomplete='off' id='Employee_Vote_Number' value="<?php echo $Employee_Vote_Number; ?>" readonly='readonly'>
                                </td>
                                <td style="text-align:right;">Finger Print</td>
                                <td>
                                    <nobr>
                                        <?php
                                        $finger_print_details = mysqli_query($conn, "SELECT Registration_ID FROM tbl_finger_print_details WHERE Registration_ID= $Registration_ID");
                                        if (mysqli_num_rows($finger_print_details) > 0) {
                                        ?>
                                            <input type='text' name='finger_print' id='finger_print' disabled='disabled' value='Taken' style='width:50%;'>&emsp;<input type='button' name='verify' value='VERIFY' class='art-button' onclick="verify_finger_print('<?= $Registration_ID; ?>')">
                                        <?php
                                        } else {
                                        ?>
                                            <input type='text' name='finger_print' id='finger_print' disabled='disabled' value='Not taken' class="hide" style='width:50%;'><input type="button" name="finger_print" value="TAKE FINGER PRINT" class="art-button-green" onclick="open_finger_print_dialog();">
                                    </nobr>
                                </td>
                            <?php
                                        }
                            ?>



                            <td style="text-align:right;">
                                <?php
                                $select_afya_card_info = mysqli_query($conn, "SELECT configname, configvalue FROM tbl_config WHERE configname IN('Show_Afya_Card') ");
                                $afya_card_info = mysqli_fetch_assoc($select_afya_card_info);
                                $afya_card_config = $afya_card_info['configvalue'];

                                $check_card = mysqli_query($conn, "SELECT * FROM tbl_member_afya_card WHERE Registration_ID = $Registration_ID");
                                if ($afya_card_config == 'Yes') {
                                    if (mysqli_num_rows($check_card) > 0) {
                                ?>

                                        <input type="button" value="VERIFY CARD" class="art-button-green" onclick="open_afya_card_dialog('<?= $Registration_ID; ?>','verify');">
                                    <?php  } else {
                                    ?>
                                        <input type="button" value="ISSUE CARD" class="art-button-green" onclick="open_afya_card_dialog('<?= $Registration_ID; ?>','issue');">
                                <?php }
                                } ?>
                            </td>
                            <td>
                                <nobr>
                                    <input type='button' name='check_out' value='CHECK OUT' class='art-button-green' style='width: 30%;' onclick="write_check_up_results('<?= $Registration_ID; ?>')">
                                    <a href='print_PatientID.php?print=<?php echo $Registration_ID; ?>' target="_blank" class='art-button-green' style='width: 30%'>PRINT CARD</a>

                                </nobr>

                                <!-- <a href='index.php?print=".$c['ID']."' class='btn btn-info btn-sm' > Preview  </a> -->
                            </td>

                            </tr>
                            <!-- <tr>
                   <td style='text-align: right'>Tribe</td>
                    <td><input type='text' name='Tribe' id='Tribe' disabled='disabled' value='<?php echo $Tribe; ?>'></td>

                </tr> -->
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>

                                </td>
                            </tr>

                            <tr>
                                <?php
                                //Get Type of exemption if Msamaha patient
                                if ($Exemption == 'yes' && isset($_SESSION['systeminfo']['Reception_Must_Fill_Exemption_Missing_Information']) && strtolower($_SESSION['systeminfo']['Reception_Must_Fill_Exemption_Missing_Information']) == 'yes') {
                                ?>
                                    <td style="text-align:right;"><b style='color: red'>AINA YA MSAMAHA</b></td>
                                    <td width="35%">
                                        <table width="100%">
                                            <tr>
                                                <td>
                                                    <select name="msamaha_Items" id="msamaha_Items" required="required">
                                                        <option selected="selected"></option>
                                                        <?php
                                                        $Query = mysqli_query($conn, "SELECT * from tbl_msamaha_items") or die(mysqli_error($conn));
                                                        while ($row = mysqli_fetch_assoc($Query)) {
                                                            echo '<option value="' . $row['msamaha_Items'] . '">' . $row['msamaha_aina'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    Anayependekeza : <b><?php echo ucwords(strtolower($Employee_Name)); ?></b>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                <?php

                                } else {
                                ?>
                                    <input type="hidden" name="msamaha_Items" id="msamaha_Items" value="yes">
                                <?php
                                }
                                ?>

                                <td style="text-align:right" colspan="2">
                                    <?php
                                    //                            function is_connected()
                                    //                            {
                                    //                              $connected = fopen("http://www.google.com:80/","r");
                                    //                              if($connected)
                                    //                              {
                                    //                                 return true;
                                    //                              } else {
                                    //                               return false;
                                    //                              }
                                    //
                                    //                            }
                                    //                            if(is_connected()){
                                    ?>
                                    <!--                            <label class="pull-right">
                                Server Internet Status <span ><input type="button" style="border:1px solid #FFFFFF" class=" btn btn-success" /><span style="color:green"> ~Server Inayo internet </span></span>
                            </label>-->
                                    <?php // }else{
                                    ?>
                                    <!--                                <label class="pull-right">
                                    Server Internet Status <span ><input type="button" style="border:1px solid #FFFFFF;background: red" class=" btn btn-danger" /> <span style="color:red">~Server Haina internet</span></span>
                                </label>-->
                                    <?php
                                    //                            }
                                    ?>

                                </td>
                            </tr>


                        </table>
                    </td>
                    <td>
                        <table width=100%>
                            <tr>
                                <td style="text-align: center;">
                                    <?php
                                    if ($Patient_Picture != '') {
                                        if (file_exists("./patientImages/" . $Patient_Picture)) {
                                            echo "<img src='./patientImages/{$Patient_Picture}' id='Patient_Picture' name='Patient_Picture' style='width:180px; height:180px; ' onclick='viewPatientPhoto({$Registration_ID})' title='Click To View Photo'>";
                                        } else {
                                            echo "<img src='./patientImages/default.png' id='Patient_Picture' name='Patient_Picture' style='width:180px; height:180px; '>";
                                        }
                                    } else {
                                        echo "<img src='./patientImages/default.png' id='Patient_Picture' name='Patient_Picture' style='width:180px; height:180px; '>";
                                    }
                                    ?>

                                </td>
                            </tr>
                            <tr>
                                <td style='text-align: center'>
                                    Visit Type
                                    <select style="height:27px" onchange="show_refferal_number_row()" required="" name="visity_type_id" id="VisitTypeID">
                                        <option></option>
                                        <?php
                                        // section
                                        $check_status2 = mysqli_query($conn, "select Registration_ID from tbl_check_in where Registration_ID = '$Registration_ID' AND (Visit_Date)<>CURDATE()") or die(mysqli_error($conn));
                                        $num_rows = mysqli_num_rows($check_status2);
                                        if ($num_rows <= 0) {
                                        ?>

                                        <option value="5">Start</option>

                                        <?php
                                        } else {
                                        ?>

                                        <option value="1">Normal Visit</option>
                                        <option value="2">Emergency</option>
                                        <option value="3">Referral</option>
                                        <option value="4">Follow up Visit</option>

                                        <?php
                                        }
                                        // section end here
                                        ?>
                                    </select>
                                    <input type='checkbox' value="yes" hidden="hidden" id="referral_status" name="referral_status" onclick="displayHospitals()">
                                </td>

                            </tr>
                            <tr>
                                <td style='text-align: center'>
                                    <select class="select2" style="display:none;height:30px" name="referred_from_hospital_id" id="referred_from_hospital_id">
                                        <option value="" selected="selected" disabled="disabled">--Select Hospital --</option>
                                        <?php
                                        $Query = mysqli_query($conn, "select * from tbl_referred_from_hospital") or die(mysqli_error($conn));
                                        while ($row = mysqli_fetch_assoc($Query)) {
                                            echo '<option value="' . $row['referred_from_hospital_id'] . '">' . $row['hospital_name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <button style="display:none" class="art-button-green" id="addHospitals">ADD HOSPITAL</button>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:center">
                                    Type Of Check In
                                    <?php
                                    //check if new patient then block continue option
                                    // die("select Registration_ID from tbl_check_in where Registration_ID = '$Registration_ID' AND (Visit_Date)<>CURDATE()");
                                    $check_status = mysqli_query($conn, "select Registration_ID from tbl_check_in where Registration_ID = '$Registration_ID' AND (Visit_Date)<>CURDATE()") or die(mysqli_error($conn));
                                    $num_rows = mysqli_num_rows($check_status);
                                    if ($num_rows > 0) {
                                    ?>
                                        <select name='Type_Of_Check_In' id='Type_Of_Check_In' required='required'>
                                            <!--<option selected='selected'></option>-->
                                            <!--<option value="Afresh">New Patient</option>-->
                                            <option value="Continuous" selected="selected">Reattend Patient</option>
                                        </select>

                                    <?php
                                    } else { ?>
                                        <select name='Type_Of_Check_In' id='Type_Of_Check_In' required='required' title="Select type of check in">
                                            <option selected="selected" value=""></option>
                                            <option value="Afresh">New Patient</option>
                                            <!-- <option value="Continuous">Reattend Patient</option> -->
                                        </select>
                                    <?php
                                    } ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center;">
                                    <!--<label for="Diseased" style="color:red">Death</label>-->

                                    <?php
                                    if ($Diseased == 'yes') {
                                        //                        echo '<input type="checkbox" name="Diseased" id="Diseased" checked=true value="true">';
                                    } else {
                                        //                        echo '<input type="checkbox" name="Diseased" id="Diseased" value="true">';
                                    }
                                    ?>
                                    <b style="color:red">Pf3</b>
                                    <input type='hidden' name='pf3_ID' id='pf3_ID' value="<?php echo $pf3_ID; ?>">
                                    <input type='checkbox' name='pf3' <?php echo $pfs3_checked_true; ?> id='pf3'>
                                </td>
                            </tr>
                            <!--tr>
                        <td style='text-align: center'><b style="color:red">Pf3</b>

                        <input type='hidden' name='pf3_ID' id='pf3_ID' value="<?php echo $pf3_ID; ?>">
                        <input type='checkbox' name='pf3'<?php echo $pfs3_checked_true; ?> id='pf3'>

                    </td>
                    </tr-->
                            <tr>
                                <td style='text-align: center;'>
                                    <?php
                                    $auto_item_update_api = mysqli_fetch_object(mysqli_query($conn, "SELECT auto_item_update_api FROM tbl_sponsor WHERE Sponsor_ID = '$Sponsor_ID' AND api_item_package = '103'"))->auto_item_update_api;

                                    if ($auto_item_update_api == 1) {
                                        $sponsor_package_list = mysqli_query($conn, "SELECT * FROM tbl_nhif_scheme_package");
                                        echo "<select name='package_id'  id='select_package'>
                				 <option value=''>Select Package</option>";
                                        while ($package = mysqli_fetch_object($sponsor_package_list)) {
                                            echo "<option value='" . $package->package_id . "'>" . $package->package_name . "</option>";
                                        }
                                        echo "</select>
                				<br>
                				<br>
                				";
                                    }
                                    ?>


                                    <?php
                                    $check_patient_limit = mysqli_query(
                                        $conn,
                                        "SELECT ppl.Patient_Payment_ID,Patient_Direction,Consultant_ID,Clinic_ID,Process_Status,Transaction_Date_And_Time FROM tbl_patient_payment_item_list ppl
                                         JOIN tbl_patient_payments pp  ON pp.Patient_Payment_ID = ppl.Patient_Payment_ID
                                         WHERE pp.Registration_ID = '$Registration_ID' AND
                                             pp.Sponsor_ID = '$Sponsor_ID' AND
                                             ppl.Process_Status = 'signedoff' AND
                                             DATE(Transaction_Date_And_Time) = DATE (NOW()) AND
                                             ppl.Patient_Direction IN ('Direct To Doctor','Direct To Doctor Via Nurse Station','Direct To Clinic','Direct To Clinic Via Nurse Station')
                                     GROUP BY ppl.Patient_Payment_ID
                                     ORDER BY ppl.Patient_Payment_ID DESC

                                     "
                                    ) or die(mysqli_error($conn));

                                    $no_limit_check = mysqli_num_rows($check_patient_limit);

                                    $select_limit = mysqli_query($conn, "SELECT consult_per_day FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'") or die(mysqli_error($conn));

                                    $consult_per_day = mysqli_fetch_assoc($select_limit)['consult_per_day'];

                                    //echo 'no_lmt='.$no_limit_check.' and cons='.$consult_per_day;exit;
                                    //echo $is_const_per_day_count;
                                    if (!empty($consult_per_day) && ($no_limit_check >= $consult_per_day) && $is_const_per_day_count == '1') {
                                        $fetch2 = mysqli_fetch_assoc($check_patient_limit);

                                        if ($fetch2['Patient_Direction'] == 'Direct To Doctor' || $fetch2['Patient_Direction'] == 'Direct To Doctor Via Nurse Station') {
                                            $doctorsName = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='" . $fetch2['Consultant_ID'] . "'"))['Employee_Name'];
                                        } else if ($fetch2['Patient_Direction'] == 'Direct To Clinic' || $fetch2['Patient_Direction'] == 'Direct To Clinic Via Nurse Station') {
                                            $doctorsName = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee e JOIN tbl_clinic_employee ce ON ce.Employee_ID=e.Employee_ID WHERE Clinic_ID='" . $fetch2['Clinic_ID'] . "' GROUP BY e.Employee_ID"))['Employee_Name'];
                                        }

                                        echo "<input type='button' name='submit_warning' id='submit_warning' value='CHECK IN' style='width: 50%;height:55px;' class='art-button-green' onclick='Check_In_Limit_SignedOff(\"" . str_replace("'", "", $doctorsName) . "\",\"" . $fetch['Transaction_Date_And_Time'] . "\",\"" . $consult_per_day . "\")'>";
                                    } else {

                                        $check_patient_if_signedoff = mysqli_query(
                                            $conn,
                                            "SELECT ppl.Patient_Payment_ID,Patient_Direction,Consultant_ID,Clinic_ID,Process_Status FROM tbl_patient_payment_item_list ppl
                                         JOIN tbl_patient_payments pp  ON pp.Patient_Payment_ID = ppl.Patient_Payment_ID
                                         WHERE pp.Registration_ID = '$Registration_ID' AND
                                             ppl.Process_Status IN ('not served','served') AND
                                             ppl.Patient_Direction IN ('Direct To Doctor','Direct To Doctor Via Nurse Station','Direct To Clinic','Direct To Clinic Via Nurse Station')

                                    GROUP BY ppl.Patient_Payment_ID"
                                        ) or die(mysqli_error($conn));

                                        $num_check_signedoff = mysqli_num_rows($check_patient_if_signedoff);
                                        if ($num_check_signedoff > 0 && $is_perf_by_signe_off == '1') {
                                            $fetch = mysqli_fetch_assoc($check_patient_if_signedoff);

                                            if ($fetch['Patient_Direction'] == 'Direct To Doctor' || $fetch['Patient_Direction'] == 'Direct To Doctor Via Nurse Station') {
                                                $doctorsName = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='" . $fetch['Consultant_ID'] . "'"))['Employee_Name'];
                                            } else if ($fetch['Patient_Direction'] == 'Direct To Clinic' || $fetch['Patient_Direction'] == 'Direct To Clinic Via Nurse Station') {
                                                $doctorsName = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee e JOIN tbl_clinic_employee ce ON ce.Employee_ID=e.Employee_ID WHERE Clinic_ID='" . $fetch['Clinic_ID'] . "' GROUP BY e.Employee_ID"))['Employee_Name'];
                                            }
                                            // die("consult".$doctorsName);

                                            echo "<input type='button' name='submit_warning' id='submit_warning' value='CHECK IN ' style='width: 50%;height:55px;' class='art-button' onclick='Check_In_Warning_SignedOff(\"" . str_replace("'", "", $doctorsName) . "\",\"" . $fetch['Process_Status'] . "\")'>";
                                        } else {

                                            if ($Exemption == 'yes' && $Exemption_Details == 'not available' && isset($_SESSION['systeminfo']['Reception_Must_Fill_Exemption_Missing_Information']) && strtolower($_SESSION['systeminfo']['Reception_Must_Fill_Exemption_Missing_Information']) == 'yes') {
                                                echo "<input type='button' name='Exemption_Details_Button' id='Exemption_Details_Button' value='CHECK IN ' style='width: 50%;height:55px;' class='art-button' onclick='Exemption_Details_Dialog()'>";
                                            } else {
                                                if (isset($_SESSION['systeminfo']['Enable_Inpatient_To_Check_Again']) && strtolower($_SESSION['systeminfo']['Enable_Inpatient_To_Check_Again']) == 'no') {
                                                    //check if patient exists as inpatient
                                                    $check_patient = mysqli_query($conn, "select Admision_ID from tbl_admission where Registration_ID = '$Registration_ID' and Admission_Status <> 'Discharged'") or die(mysqli_error($conn));
                                                    $num_check = mysqli_num_rows($check_patient);
                                                    if ($num_check > 0) {
                                                        echo "<input type='button' name='submit_warning' id='submit_warning' value='CHECK IN ' style='width: 50%;height:55px;' class='art-button' onclick='Check_In_Warning()'>";
                                                    } else {
                                                        echo "<input type='button' name='submita' id='submita'onclick='check_if_already_checked_in_today($Registration_ID)' value='CHECK IN ' style='width: 50%;height:55px;' class='art-button-green'>";
                                                    }
                                                } else {
                                                    echo "<input type='submit' name='submit' id='submit'onclick='return validate_refferal_number_txtbox()' value='CHECK IN' style='width: 50%;height:55px;' class='art-button'>";
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                    <input type='hidden' name='submittedCheckInPatientForm' value='true' />


                                </td>
                            </tr>
                            <tr>
                                <td style='text-align: center;'>
                                    <a href='./barcode/BCGcode39.php?Registration_ID=<?php echo $Registration_ID; ?>' class='art-button-green' target='_blank' style='width: 50%'>PRINT BARCODE</a>
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>
            </table>

        </center>
    </fieldset>

    <fieldset>
        <legend align=center><b>PATIENT'S VERIFIED SPONSOR DETAILS</b></legend>
        <center>
            <div class="msg" style='color:red;font-weight:  bold'></div>

            <?php
            $sql = mysqli_query($conn, "SELECT configvalue FROM tbl_config WHERE configname='NhifAuthorization'");
            $write_auth_num = mysqli_fetch_assoc($sql)['configvalue'];
            $readyonly = "";
            if ($write_auth_num == 'Yes') {
                $readyonly = "readonly";
            }
            ?>
            <div align="center" style="display:none" id="verifyprogress"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>
            <table width=90%>
                <tr>
                    <td style="text-align: right;" width='14%'>Authorization Status</td>
                    <td><input type='text' name='CardStatus' readonly="readonly" id='CardStatus' value=''></td>
                    <td style='text-align: right'>Authorization Number</td>
                    <td><input type='text' name='AuthorizationNo' id='AuthorizationNo' <?= $readyonly; ?>></td>
                </tr>
                <tr>
                    <td style='text-align: right'>Remarks</td>
                    <td colspan=3><textarea name="Remarks" rows="1" id="Remarks" readonly="readonly" style='resize: none;'></textarea></td>
                </tr>
            </table>
        </center>
    </fieldset>
</form>
<div id="pf3dialog" style="width:100%;overflow:hidden;display: none;">
    <form action="" id="pf3form" method="post" enctype="multipart/form-data">
        <center>
            <table width=90%>
                <tr>
                    <td style="text-align: right;" width='14%'>Pf3 Attachment</td>
                    <td><input type='file' name='pf3attachment' id='pf3attachment' value=''></td>
                </tr>
                <tr>
                    <td style='text-align: right'>Pf3 Description</td>
                    <td>
                        <textarea name='pf3_Description' id='pf3_Description' rows="2" cols="20">
                        </textarea>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right;">Pf3 Reason</td>
                    <td>
                        <select name="P_Reason" id="P_Reason" required="required">
                            <option value="">Select Reason</option>
                            <?php
                            $select = mysqli_query($conn, "select Reason_ID, Reason_Name from tbl_pf3_reasons") or die(mysqli_error($conn));
                            $num = mysqli_num_rows($select);
                            if ($num > 0) {
                                while ($data = mysqli_fetch_array($select)) {
                            ?>
                                    <option value="<?php echo $data['Reason_ID']; ?>"><?php echo $data['Reason_Name']; ?></option>
                            <?php

                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: right'>Police Station</td>
                    <td colspan=3><input type='text' name="Police_Station" rows="1" id="Police_Station" style='resize: none;' required='required' /></td>
                </tr>
                <tr>
                    <td style='text-align: right'>Pf3 Number</td>
                    <td colspan=3><input type='text' name="Pf3_Number" rows="1" id="Pf3_Number" style='resize: none;' /></td>
                </tr>
                <tr>
                    <td style='text-align: right'>Relative</td>
                    <td colspan=3><input type='text' name="Relative" rows="1" id="Relative" style='resize: none;' /></td>
                </tr>
                <tr>
                    <td style='text-align: right'>Relative Phone Number</td>
                    <td colspan=3><input type='text' name="Phone_No_Relative" rows="1" id="Phone_No_Relative" style='resize: none;' /></td>
                </tr>
                <tr>
                    <td style='text-align:center ' colspan="2">
                        <input type="submit" name="pf3submit" id="pf3submit" class="art-button" value="Save Information" />
                    </td>

                </tr>
            </table>
        </center>
    </form>
</div>

<!--deceased dialog-->

<!--check if exist-->
<?php
$query = mysqli_query($conn, "SELECT * FROM tbl_diceased_patients WHERE Patient_ID='$Registration_ID'");
$row = mysqli_fetch_assoc($query);
$relative_name = $row['relative_name'];
$relationship_type = $row['relationship_type'];
$relative_phone_number = $row['relative_phone_number'];
$relative_Address = $row['relative_Address'];
$death_reason = $row['death_reason'];
$death_date = $row['death_date'];
?>


<div id="deceased_dialog" style="width:100%;overflow:hidden;display: none;">
    <form action="" id="deceasedform" method="post">
        <center>
            <table width=100% class="table table-condensed">

                <tr>
                    <td style="text-align: right;">Death Reason</td>
                    <td>
                        <select id="Death_Reason" style="width:400px;">
                            <option value="<?php echo $death_reason; ?>"></option>
                            <?php
                            $reasonQuery = mysqli_query($conn, "SELECT * FROM tbl_deceased_reasons");
                            while ($result = mysqli_fetch_assoc($reasonQuery)) {
                                echo '<option value="' . $result['deceased_reasons_ID'] . '">' . $result['deceased_reasons'] . '</option>';
                            }
                            ?>

                        </select>

                    </td>

                    <td>
                        <input type="button" style="float:left" value="Add reason" id="add_death_reason" class="art-button">
                    </td>
                </tr>

                <tr>
                    <td style='text-align: right'>Date of death</td>
                    <td colspan=3><input type='text' name="death_date" autocomplete='off' value="<?php echo $death_date; ?>" rows="1" id="death_date" style='resize: none;' /></td>
                </tr>
                <tr>
                    <td style='text-align: right'>Place Of Death</td>
                    <td colspan=3>
                        <input type="text" id="place_of_death" />
                    </td>
                </tr>
                <tr>
                    <td style='text-align: right'>Doctor Confirm Death</td>
                    <td colspan=3>
                        <select id="doctor_confirm_death" style="width:100%">
                            <option value=""></option>
                            <?php
                            $sql_select_doctor_result = mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_Type='Doctor'") or die(mysqli_error($conn));
                            if (mysqli_num_rows($sql_select_doctor_result) > 0) {
                                while ($doctors_rows = mysqli_fetch_assoc($sql_select_doctor_result)) {
                                    $Employee_Name = $doctors_rows['Employee_Name'];
                                    echo "<option value='$Employee_Name'>$Employee_Name</option>";
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: right'>Relative Name</td>
                    <td colspan=3><input type='text' name="Relative_Name" rows="1" id="Relative_Name" value="<?php echo $relative_name; ?>" style='resize: none;' /></td>
                </tr>

                <tr>
                    <td style='text-align: right'>Relationship type</td>
                    <td colspan=3><input type='text' name="Relationship_type" rows="1" id="Relationship_type" value="<?php echo $relationship_type; ?>" style='resize: none;' /></td>
                </tr>
                <tr>
                    <td style='text-align: right'>Relative Phone Number</td>
                    <td colspan=3><input type='text' name="Phone_No_pt" rows="1" id="Phone_No_pt" value="<?php echo $relative_phone_number; ?>" style='resize: none;' /></td>
                </tr>

                <tr>
                    <td style='text-align: right'>Address</td>
                    <td colspan=3><input type='text' name="relative_address" rows="1" id="relative_address" value="<?php echo $relative_Address; ?>" style='resize: none;' required='required' /></td>
                </tr>

                <tr>
                    <td style='text-align:center ' colspan="2">
                        <input type="submit" name="deceasedsubmit" id="deceasedsubmit" class="art-button" value="Save Information" />
                    </td>

                </tr>
            </table>
        </center>
    </form>
</div>

<!--deathreason_dialog-->

<div id="deathreason_dialog" style="width:100%;overflow:hidden;display: none;">
    <form action="" id="deathreasonform" method="post">
        <center>
            <table width=100%>

                <tr>
                    <td style="text-align: right;">Death Reason</td>
                    <td>

                        <input type="text" autocomplete="off" style="width:200px" id="Death_Reason_value" value="">

                    </td>

                    <td style='text-align:center'>
                        <input type="submit" name="deathreasonsubmit" id="deathreasonsubmit" class="art-button" value="Save Information" />
                    </td>

                </tr>


            </table>
        </center>
    </form>
</div>

<?php
include "finger_print.php";
?>
<div width="100%" style="text-align:center;">
    <?php
    if (isset($_SESSION['userinfo'])) {
        if ($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes') {
    ?>
            <!-- WILL BE USED WHEN LAN TO MSAMAHA IS DONE
            <a href='wagongwawamsamaha.php' class='art-button'>
                WAGONJWA WA MSAMAHA
             </a>-->
    <?php

        }
    }
    ?>
    <?php
    if (isset($_SESSION['userinfo'])) {
        if ($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes') {
    ?>

    <?php

        }
    }
    ?>

    <?php
    if (isset($_SESSION['userinfo'])) {
        if ($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes') {
    ?>
            <a href="departmentpatientbillingpage.php?Section=Reception&DepartmentalPayment=DepartmentalPaymentThisPage" class="art-button-green">DEPARTMENTAL PAYMENTS</a>

            <?php //if(isset($Registration_ID)){    
            ?>
            <button class="art-button-green" onClick="SendSMS('Registration', '<?php echo $Phone_Number; ?>', '<?php echo $Registration_ID; ?>')" style="height:27px!important;color:#FFFFFF!important">SEND SMS ALERT</button>
            <span id="SMSRespond"></span>
            <?php //}      
            ?>
        <?php
        } else { ?>
            <input type="button" name="Direct_Department" id="Direct_Department" value="DIRECT DEPARTMENTAL PAYMENTS" class="art-button-green" onclick="Get_Selected_Patient()">
    <?php

        }
    }
    ?>

</div>

<div id="No_Patient_Available">
    <center>NO PATIENT SELECTED</center>
</div>

<div id="Check_In_Warning">
    <center>

        <?php
        $check_patient = mysqli_query($conn, "select Hospital_Ward_Name,ward_room_id,Discharge_Reason_ID from tbl_admission ad INNER JOIN tbl_hospital_ward hw ON ad.Hospital_Ward_ID=hw.Hospital_Ward_ID  where Registration_ID = '$Registration_ID' and Admission_Status <> 'Discharged' ORDER BY Admision_ID DESC") or die(mysqli_error($conn));
        if (mysqli_num_rows($check_patient) > 0) {
            $ward_name_row = mysqli_fetch_assoc($check_patient);
            $Hospital_Ward_Name = $ward_name_row['Hospital_Ward_Name'];
            $ward_room_id = $ward_name_row['ward_room_id'];
            $Discharge_Reason_ID = $ward_name_row['Discharge_Reason_ID'];
            $select_discharge_condition_result = mysqli_query($conn, "SELECT discharge_condition FROM tbl_discharge_reason WHERE Discharge_Reason_ID='$Discharge_Reason_ID'") or die(mysqli_error($conn));
            $discharge_condition = "";
            if (mysqli_num_rows($select_discharge_condition_result) > 0) {
                $discharge_condition = mysqli_fetch_assoc($select_discharge_condition_result)['discharge_condition'];
            }
            if ($ward_room_id == 0) {
                echo "Please note, Doctor suggest the selected patient to be Admitted  to  <br/>"
                    . "<b>$Hospital_Ward_Name</b> Ward<br/>";
                echo "<b style='color:red'>Do you want to Cancel this Admission ?</b><br/><input type='button' value='YES' class='btn btn-primary' onclick='cancel_patient_admission()' /><span style='color:#DEDEDE'>.   .</span><input type='button' value='NO' class='btn btn-default' onclick='close_this_cance_ad_dialogy()'>";
            } else {
                if ($discharge_condition == "dead") {
                    echo "<b>Please note, this patient is <b style='font-size:20px;color:red'>Dead</b></b> <br/><br/>Was Admitted to <b> $Hospital_Ward_Name</b> Ward";
                } else if ($discharge_condition == "absconde") {
                    echo "<b>Please note, this patient <b style='font-size:20px;color:red'>Absconded</b></b> <br/><br/>Was Admitted to <b> $Hospital_Ward_Name</b> Ward";
                } else {
                    echo "<b>Please note, the selected patient already has INPATIENT STATUS and not yet discharged</b> <br/><br/>Admitted to <b> $Hospital_Ward_Name</b> Ward";
                }
            }
            //            echo "";
        }
        ?>
    </center>
</div>
<div id="Check_In_Warning_SignedOff">
    <center>
        <div id="consultedbydoctor"></div>
    </center>
</div>

<div id="Check_In_Limit">
    <center>
        <div id="consultation_limit"></div>
    </center>
</div>

<div id="Exemption_Details_Dialog">

</div>

<div id="Update_Error">
    <b>Hazijahifadhiwa sawa. Tafadhali jaribu tena</b>
</div>

<div id="ePayment_Details_Area">
    <center id="Details_Area">
        <fieldset>
            <table width="100%">
                <tr>
                    <td width="20%"><b>Patient Name</b></td>
                    <td width="25%"><input type="text" name="P_Name" id="P_Name" readonly="readonly" value="" placeholder="Patient Name"></td>



                    <td style="text-align: right;" width="22%"><b>Patient Number</b></td>
                    <td>
                        <input type="text" name="Patient_No" id="Patient_No" readonly="readonly" value="" placeholder="Patient Number">
                    </td>
                </tr>
                <tr>
                    <td width="20%"><b>Gender</b></td>
                    <td width="25%"><input type="text" name="P_Gender" id="P_Gender" readonly="readonly" value="" placeholder="Gender"></td>



                    <td style="text-align: right;"><b>Phone Number</b></td>
                    <td><input type="text" name="Phone_No" id="Phone_No" readonly="readonly" value="" placeholder="Phone Number"></td>
                </tr>
                <tr>
                    <td width="15%"><b>Age</b></td>
                    <td width="20%"><input type="text" name="Patient_Age" id="Patient_Age" readonly="readonly" value="" placeholder="Patient Age"></td>



                    <td style="text-align: right;"><b>Occupation</b></td>
                    <td><input type="text" name="Patient_Occupation" id="Patient_Occupation" readonly="readonly" value="" placeholder="Patient Occupation"></td>



                </tr>

            </table>
        </fieldset>
        <fieldset>
            <table width="100%">
                <tr>
                    <td width="20%"><b>Invoice Number</b></td>
                    <td width="25%"><input type="text" name="Invoice_No" id="Invoice_No" readonly="readonly" value="" placeholder="Invoice Number"></td>
                    <td style="text-align: right;" width="22%"><b>Amount Required</b></td>
                    <td><input type="text" name="Amount_Required" id="Amount_Required" readonly="readonly" value="" placeholder="Amount"></td>
                </tr>
                <tr>
                    <td width="20%"><b>Transaction Reference</b></td>
                    <td width="25%"><input type="text" name="Transaction_Ref" id="Transaction_Ref" readonly="readonly" value="" placeholder="Transaction Reference"></td>
                    <td style="text-align: right;" width="22%"><b>Reference Date</b></td>
                    <td><input type="text" name="Reference_Date" id="Reference_Date" readonly="readonly" value="" placeholder="Reference Date"></td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <table width="100%">
                <tr>
                    <td width="20%"><b>Transaction Status</b></td>
                    <td width="25%"><input type="text" name="Transaction_Status" id="Transaction_Status" readonly="readonly" value="" placeholder="Transaction Status"></td>
                    <td style="text-align: center;" id="ePayment_Button_Area">

                    </td>
                </tr>
            </table>
        </fieldset>
    </center>

    <br /><br />
    <fieldset>
        <table width="100%">
            <tr>
                <td width="20%"><b>Enter Invoice Number</b></td>
                <td>
                    <input type="text" name="Invoice_Number" id="Invoice_Number" autocomplete="off" placeholder="~~~ ~~~ ~~~ ~~~ Enter Invoice Number ~~~ ~~~ ~~~ ~~~" style="text-align: center;" onkeypress="Clear_Current_Contents()" oninput="Clear_Current_Contents()">
                </td>
                <td width="20%" style="text-align: center;">
                    <input type="button" name="Search_Details" id="Search_Details" class="art-button" value="SEARCH DETAILS" onclick="Get_ePayment_Details()">
                </td>
            </tr>
        </table>
    </fieldset>
</div>
<!--HOSPITAL DIALOG-->
<div id="hospitals">
    <div id="hospitalIframe">
        <fieldset>
            <table width=80%>
                <form action='#' method='post' name='myForm' id='myForm'>

                    <tr>
                        <td width=40% style='text-align: right;'><b>HOSPITAL NAME</b></td>
                        <td width=80%><input type='text' name='non_ref_hosp_name' required='required' id='non_ref_hosp_name' placeholder='Enter Non Referral Hospital Name'></td>
                    </tr>
                    <tr>
                        <td colspan=2 style='text-align: right;'>
                            <input type='submit' name='non_referral_submit' id='submit' value='   SAVE   ' class='art-button'>
                            <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button'>
                        </td>
                    </tr>
                </form>
            </table>
        </fieldset>
    </div>
</div>
<!--check in process-->
<?php
if (isset($_POST['non_referral_submit'])) {
    $non_ref_hosp_name = mysqli_real_escape_string($conn, $_POST['non_ref_hosp_name']);

    $sql = "insert into tbl_referred_from_hospital(hospital_name)
                    values('$non_ref_hosp_name')";

    if (!mysqli_query($conn, $sql)) {
        $error = '1062yes';
        if (mysqli_errno($conn) . "yes" == $error) {
?>

            <script type='text/javascript'>
                alert('NON REFERRAL HOSPITAL NAME ALREADY EXISTS! \nTRY ANOTHER NAME');
            </script>

<?php

        }
    } else {
        echo "<script type='text/javascript'>
			    alert('NON REFERRAL HOSPITAL NAME ADDED SUCCESSFUL');
			    </script>";
        // header("Location: .//visitorform.php?Registration_ID=$Registration_ID&PatientBilling=PatientBillingThisForm");
    }
}
function upload($image, $path)
{
    global $conn;
    $ext = substr(strrchr($_FILES[$image]['name'], '.'), 1);
    $picName = rand() . ".$ext";
    if (move_uploaded_file($_FILES[$image]['tmp_name'], $path . $picName)) {
        return $picName;
    } else {
        echo '.';
    }
}
if (isset($_POST['submittedCheckInPatientForm'])) {
    if ($Registration_ID != '') {
        $Visit_Date = mysqli_real_escape_string($conn, $_POST['Visit_Date']);
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
        $Type_Of_Check_In = mysqli_real_escape_string($conn, $_POST['Type_Of_Check_In']);
        $CardStatus = mysqli_real_escape_string($conn, $_POST['CardStatus']);
        $AuthorizationNo = mysqli_real_escape_string($conn, $_POST['AuthorizationNo']);
        $package_id = mysqli_real_escape_string($conn, $_POST['package_id']);
        $pf3_ID = $_POST['pf3_ID'];
        $refferal_number = $_POST['refferal_number'];
        $referral_reason = mysqli_real_escape_string($conn, $_POST['referral_reason']);
        //$referral_letter = $_POST['referral_letter'];
        $referral_letter = upload("referral_letter", "excelfiles/");
        $visity_type_id = $_POST['visity_type_id'];
        echo $visity_type_id;
        $referred_from_hospital_id = mysqli_real_escape_string($conn, $_POST['referred_from_hospital_id']);

        $referral_status = mysqli_real_escape_string($conn, $_POST['referral_status']);
        if ($referral_status != "yes") {
            $referral_status = "no";
        }
        if (isset($_POST['Diseased'])) {
            $Diseased = 'yes';
        } else {
            $Diseased = 'no';
        }
        if (isset($_POST['pf3'])) {
            $pf3 = 'yes';
        } else {
            $pf3 = 'no';
        }
        if (($visity_type_id == "1" || $visity_type_id == "2") && $Type_Of_Check_In == "Afresh") {

            $sql_check_if_patient_sponsor_can_be_changed_result = mysqli_query($conn, "SELECT to_sponsor_id FROM tbl_patient_auto_sponsor_change WHERE from_sponsor_id='$Sponsor_ID'") or die(mysqli_error($conn));
            if (mysqli_num_rows($sql_check_if_patient_sponsor_can_be_changed_result) > 0) {
                $to_sp_id_row = mysqli_fetch_assoc($sql_check_if_patient_sponsor_can_be_changed_result);
                $to_sponsor_id = $to_sp_id_row['to_sponsor_id'];
                $sql_change_sponsor_result = mysqli_query($conn, "UPDATE tbl_patient_registration SET Sponsor_ID='$to_sponsor_id' WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
            }
        }
        //give patient consultation id oon checkin---still it has some complecation thats y commented
        //$sql_insert_reg="INSERT INTO tbl_consultation(Registration_ID,employee_ID) VALUES($Registration_ID,$Employee_ID)";
        //mysqli_query($conn,$sql_insert_reg) or die(mysqli_error($conn));
        //$referred_from_hospital_id = mysqli_real_escape_string($conn,$_POST['referred_from_hospital_id']);

        //get exemption details if needed
        if ($Exemption == 'yes' && isset($_SESSION['systeminfo']['Reception_Must_Fill_Exemption_Missing_Information']) && strtolower($_SESSION['systeminfo']['Reception_Must_Fill_Exemption_Missing_Information']) == 'yes') {
            $msamaha_Items = mysqli_real_escape_string($conn, $_POST['msamaha_Items']);
            $Anayependekeza_Msamaha = $Employee_ID;
        } else {
            $msamaha_Items = null;
            $Anayependekeza_Msamaha = null;
        }
        //check if patient checked in
        $select = mysqli_query($conn, "SELECT Check_In_ID FROM tbl_check_in where Registration_ID = '$Registration_ID' and Visit_Date = '$Today'") or die(mysqli_error($conn));
        $num_check_in = mysqli_num_rows($select);

        if ($num_check_in < 1) {
            if ($Exemption == 'yes' && isset($_SESSION['systeminfo']['Reception_Must_Fill_Exemption_Missing_Information']) && strtolower($_SESSION['systeminfo']['Reception_Must_Fill_Exemption_Missing_Information']) == 'yes') {
                $Check_In_Process = mysqli_query($conn, "INSERT into tbl_check_in(
                                    Registration_ID,Check_In_Date_And_Time,Visit_Date,
                                    Employee_ID,Branch_ID,Check_In_Date,Type_Of_Check_In,
                                    AuthorizationNo,CardStatus,msamaha_Items,Anayependekeza_Msamaha,Referral_Status,refferal_number,visit_type,Diceased,pf3,package_id)
                                values(
                                    '$Registration_ID',(select now()),'$Visit_Date',
                                    '$Employee_ID','$Branch_ID',(select now()),'$Type_Of_Check_In',
                                    '$AuthorizationNo','$CardStatus','$msamaha_Items','$Anayependekeza_Msamaha','$referral_status','$refferal_number','$visity_type_id','$Diseased','$pf3','$package_id'
                                )") or die(mysqli_error($conn));
            } else {
                $Check_In_Process = mysqli_query($conn, "INSERT into tbl_check_in(
                                    Registration_ID,Check_In_Date_And_Time,Visit_Date,
                                    Employee_ID,Branch_ID,Check_In_Date,Type_Of_Check_In,
                                    AuthorizationNo,CardStatus,Referral_Status,refferal_number,visit_type,Diceased,pf3,referral_reason,referral_letter,package_id)
                                values(
                                    '$Registration_ID',(select now()),'$Visit_Date',
                                    '$Employee_ID','$Branch_ID',(select now()),'$Type_Of_Check_In',
                                    '$AuthorizationNo','$CardStatus','$referral_status','$refferal_number','$visity_type_id','$Diseased','$pf3','$referral_reason','$referral_letter','$package_id'
                                )") or die(mysqli_error($conn));
            }
        }




        if (strtolower($Type_Of_Check_In) == 'afresh') {
            $insert_bill_info = mysqli_query($conn, "INSERT INTO tbl_patient_bill(
                                            Registration_ID,Date_Time) VALUES ('$Registration_ID',(select now()))") or die(mysqli_error($conn));
        }

        $rs = mysqli_query($conn, "SELECT Check_In_ID FROM tbl_check_in where Registration_ID = '$Registration_ID' ORDER BY Check_In_ID DESC LIMIT 1");
        $row = mysqli_fetch_assoc($rs);
        $Check_In_ID = $row['Check_In_ID'];
        //REFRRED PATIENT QUERIES
        if ($referral_status == "yes") {
            $referral = mysqli_query($conn, "INSERT INTO tbl_referred_patients(
                                            Registration_ID,Check_In_ID,Referral_Hospital_ID) VALUES ('$Registration_ID','$Check_In_ID','$referred_from_hospital_id')") or die(mysqli_error($conn));
        }

        mysqli_query($conn, "UPDATE tbl_pf3_patients SET Check_In_ID='$Check_In_ID'WHERE pf3_ID='$pf3_ID'");
        if (strtolower($Reception_Picking_Items) == 'yes') {
            //echo 'imetumbukaaa';
            //            if (strtolower($Guarantor_Name) != 'cash') {
            echo '<script>
				    alert("Patient Checked In Successfully");
				    document.location = "./patientbillingreception.php?Registration_ID=' . $Registration_ID . '&NR=True&CreditPatientBilling=CreditPatientBillingThisPage";
				    </script>';
            //            } else {
            //                if (strtolower($_SESSION['systeminfo']['Departmental_Collection']) == 'yes') {
            //                    if (strtolower($_SESSION['userinfo']['Cash_Transactions']) == 'yes') {
            //                        echo '<script>
            //					    alert("Patient Checked In Successfully");
            //					    document.location = "./patientbillingreception.php?Registration_ID=' . $Registration_ID . '&NR=True&PatientBillingReception=PatientBillingReceptionThisForm";
            //					    </script>';
            //                    } else {
            //                        echo '<script>
            //					    alert("Patient Checked In Successfully");
            //					    document.location = "./patientbillingprepare.php?Registration_ID=' . $Registration_ID . '&NR=True&PatientBillingPrepare=PatientBillingPrepareThisForm";
            //					    </script>';
            //                    }
            //                } else {
            //                    echo '<script>
            //					alert("Patient Checked In Successfully");
            //					document.location = "./patientbillingprepare.php?Registration_ID=' . $Registration_ID . '&NR=True&PatientBillingPrepare=PatientBillingPrepareThisForm";
            //					</script>';
            //                }
            //                /* echo '<script>
            //                  alert("Patient Checked In Successfully");
            //                  document.location = "./patientbillingprepare.php?Registration_ID='.$Registration_ID.'&NR=True&PatientBillingPrepare=PatientBillingPrepareThisForm";
            //                  </script>'; */
            //            }
        } else {
            //            if (strtolower($Guarantor_Name) != 'cash') {
            echo '<script>
					alert("Patient Checked In Successfully");
					document.location = "./patientbillingreception.php?Registration_ID=' . $Registration_ID . '&NR=True&CreditPatientBilling=CreditPatientBillingThisPage";
					</script>';
            //            } else {
            //                echo '<script>
            //					alert("Patient Checked In Successfully");
            //					document.location = "./visitorform.php?Visitor=VisitorThisPage";
            //					</script>';
            //            }
        }
    }
}
$rs = mysqli_query($conn, "SELECT Check_In_ID FROM tbl_check_in where Registration_ID = '$Registration_ID' ORDER BY Check_In_ID DESC LIMIT 1");
$row = mysqli_fetch_assoc($rs);
$Check_In_ID = $row['Check_In_ID'];
?>

<!-- dialog to show the patient details from NHIF after Authorization -->
<div id="verification_dialog" style="width:100%;overflow:hidden;display: none;font-size: 20px;">
    <div class="form-group">
        <strong>Patient Name : </strong><strong><span id="ver_patient_name"></span></strong>
    </div>
    <div class="form-group">
        <strong>Card Number : </strong><strong><span id="ver_card_no"></span></strong>
    </div>
    <div class="form-group">
        <strong>Status : </strong><strong><span id="ver_status"></span></strong>
    </div>
    <div class="form-group">
        <strong>Expire Date : </strong><strong><span id="ver_expire_date"></span></strong>
    </div>
    <div class="form-group">
        <strong>Authorization No : </strong><strong><span id="ver_authorization_no"></span></strong>
    </div>
    <div class="form-group">
        <strong>Package : </strong><strong><span id="ver_package"></span></strong>
    </div>

    <div class="form-group">
        <strong>Scheme Name : </strong><strong><span id="ver_scheme_name"></span></strong>
    </div>
</div>

<script type="text/javascript">
    function cancel_patient_admission() {
        var Check_In_ID = '<?= $Check_In_ID ?>';
        var Registration_ID = '<?= $Registration_ID ?>';
        if (confirm("Are you sure you want to cancel this admission?")) {
            $.ajax({
                type: 'GET',
                url: 'Cancel_This_Admission.php',
                data: {
                    Check_In_ID: Check_In_ID,
                    Registration_ID: Registration_ID
                },
                success: function(data) {
                    if (data == "success") {
                        console.log(data + "--" + Check_In_ID + ':::' + Registration_ID);
                        alert("Process Successfully");
                        location.reload();
                    } else {
                        alert("Process Fail...Try again");
                    }
                }
            });
        }
    }

    function Update_Msamaha_Details() {
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Na_ya_Wodi = document.getElementById("Na_ya_Wodi").value;
        var Name_Balozi = document.getElementById("Name_Balozi").value;
        var Aina_ya_msamaha = document.getElementById("Aina_ya_msamaha").value;
        var Education_Level = document.getElementById("Education_Level").value;
        var Work_Wife = document.getElementById("Work_Wife").value;
        var Mahudhulio = document.getElementById("Mahudhulio").value;
        var Idadi_Mahudhulio = document.getElementById("Idadi_Mahudhulio").value;
        var Ni_ndugu_yako_yupi = document.getElementById("Ni_ndugu_yako_yupi").value;
        var amewahi_kutibiwa_mahali = document.getElementById("amewahi_kutibiwa_mahali").value;
        var amevaa_nguo_za_thamani = document.getElementById("amevaa_nguo_za_thamani").value;
        var mengineyo_yanayoonyesha_uwezo_wa_kuchangia = document.getElementById("mengineyo_yanayoonyesha_uwezo_wa_kuchangia").value;
        var Mapendekezo_ya_msamaha = document.getElementById("Mapendekezo_ya_msamaha").value;
        var anastahili_kupata_msamaha = document.getElementById("anastahili_kupata_msamaha").value;
        var sahihi_anayependekeza_msamaha = document.getElementById("sahihi_anayependekeza_msamaha").value;
        var Imehadhimishwa = document.getElementById("Imehadhimishwa").value;
        var Jina_la_anayehadhimisha = document.getElementById("Jina_la_anayehadhimisha").value;
        var cheo_anayehadhimisha = document.getElementById("cheo_anayehadhimisha").value;
        var sahihi_anayehadhimisha = document.getElementById("sahihi_anayehadhimisha").value;
        var Namba_katika_Rejista_ya_kupatiwa_msamaha = document.getElementById("Namba_katika_Rejista_ya_kupatiwa_msamaha").value;

        if (Aina_ya_msamaha != null && Aina_ya_msamaha != '' && Jina_la_anayehadhimisha != null && Jina_la_anayehadhimisha != '' && cheo_anayehadhimisha != null && cheo_anayehadhimisha != '') {
            if (window.XMLHttpRequest) {
                myObjectUpdate = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectUpdate = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectUpdate.overrideMimeType('text/xml');
            }
            //alert(data);

            myObjectUpdate.onreadystatechange = function() {
                dataUpdate = myObjectUpdate.responseText;
                if (myObjectUpdate.readyState == 4) {
                    var feedback = dataUpdate;
                    if (feedback == 'yes') {
                        alert("Taarifa zimehifadhiwa vyema");
                        document.location = 'visitorform.php?Registration_ID=' + Registration_ID + '&PatientBilling=PatientBillingThisForm';
                    } else {
                        $("#Update_Error").dialog("open");
                    }
                }
            }; //specify name of function that will handle server response........
            myObjectUpdate.open('GET', 'Update_Msamaha_Details.php?Registration_ID=' + Registration_ID + '&Na_ya_Wodi=' + Na_ya_Wodi + '&Name_Balozi=' + Name_Balozi +
                '&Aina_ya_msamaha=' + Aina_ya_msamaha + '&Education_Level=' + Education_Level + '&Work_Wife=' + Work_Wife + '&Mahudhulio=' + Mahudhulio +
                '&Idadi_Mahudhulio=' + Idadi_Mahudhulio + '&Ni_ndugu_yako_yupi=' + Ni_ndugu_yako_yupi + '&amewahi_kutibiwa_mahali=' + amewahi_kutibiwa_mahali + '&amevaa_nguo_za_thamani=' + amevaa_nguo_za_thamani +
                '&mengineyo_yanayoonyesha_uwezo_wa_kuchangia=' + mengineyo_yanayoonyesha_uwezo_wa_kuchangia + '&Mapendekezo_ya_msamaha=' + Mapendekezo_ya_msamaha + '&anastahili_kupata_msamaha=' + anastahili_kupata_msamaha +
                '&sahihi_anayependekeza_msamaha=' + sahihi_anayependekeza_msamaha + '&Imehadhimishwa=' + Imehadhimishwa + '&Jina_la_anayehadhimisha=' + Jina_la_anayehadhimisha + '&cheo_anayehadhimisha=' + cheo_anayehadhimisha +
                '&sahihi_anayehadhimisha=' + sahihi_anayehadhimisha + '&Namba_katika_Rejista_ya_kupatiwa_msamaha=' + Namba_katika_Rejista_ya_kupatiwa_msamaha, true);
            myObjectUpdate.send();
        } else {
            document.getElementById("Error_Message").innerHTML = '<span style="color: #037CB0;"><b>SAMAHANI JAZA SEHEMU ZOTE ZENYE RANGI NYEKUNDU</b></span>';
            if (Aina_ya_msamaha == null || Aina_ya_msamaha == '') {
                document.getElementById("Aina_ya_msamaha").style = 'border: 2px solid red';
            }
            if (Jina_la_anayehadhimisha == null || Jina_la_anayehadhimisha == '') {
                document.getElementById("Jina_la_anayehadhimisha").style = 'border: 2px solid red';
            }
            if (cheo_anayehadhimisha == null || cheo_anayehadhimisha == '') {
                document.getElementById("cheo_anayehadhimisha").style = 'border: 2px solid red';
            }
        }
    }
</script>

<script type="text/javascript">
    function Exemption_Details_Dialog() {
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        var Patient_Name = '<?php echo $Patient_Name; ?>';
        var age = '<?php echo $age; ?>';
        var Gender = '<?php echo $Gender; ?>';

        if (window.XMLHttpRequest) {
            myObjectMissingDets = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectMissingDets = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectMissingDets.overrideMimeType('text/xml');
        }
        //alert(data);

        myObjectMissingDets.onreadystatechange = function() {
            dataMsamaha = myObjectMissingDets.responseText;
            if (myObjectMissingDets.readyState == 4) {
                document.getElementById('Exemption_Details_Dialog').innerHTML = dataMsamaha;
                $("#Exemption_Details_Dialog").dialog("open");
            }
        }; //specify name of function that will handle server response........
        myObjectMissingDets.open('GET', 'Exemption_Details_Dialog.php?Registration_ID=' + Registration_ID + '&Patient_Name=' + Patient_Name + '&age=' + age + '&Gender=' + Gender, true);
        myObjectMissingDets.send();
    }
</script>

<script type="text/javascript">
    function Close_Exemption_Details_Dialog() {
        $("#Exemption_Details_Dialog").dialog("close");
    }
</script>

<script type="text/javascript">
    function Get_ePayment_Details() {
        var Invoice_Number = document.getElementById("Invoice_Number").value;
        if (Invoice_Number != null && Invoice_Number != '') {
            document.getElementById("Invoice_Number").style = 'border: 1px solid black; text-align: center;';
            if (window.XMLHttpRequest) {
                myObject_Get_Details = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject_Get_Details = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject_Get_Details.overrideMimeType('text/xml');
            }
            //alert(data);

            myObject_Get_Details.onreadystatechange = function() {
                data24 = myObject_Get_Details.responseText;
                if (myObject_Get_Details.readyState == 4) {
                    document.getElementById('Details_Area').innerHTML = data24;
                }
            }; //specify name of function that will handle server response........
            myObject_Get_Details.open('GET', 'Get_ePayment_Details.php?Invoice_Number=' + Invoice_Number, true);
            myObject_Get_Details.send();
        } else {
            if (Invoice_Number == null || Invoice_Number == '') {
                document.getElementById("Invoice_Number").style = 'border: 3px solid red; text-align: center;';
                document.getElementById("Invoice_Number").focus();
            } else {
                document.getElementById("Invoice_Number").style = 'border: 1px solid black; text-align: center;';
            }
        }
    }
</script>
<script type='text/javascript'>
    function SendSMS(department, receiver, RegNo = 0) {
        //alert(department + receiver);
        //exit;
        if (window.XMLHttpRequest) {
            sms = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            sms = new ActiveXObject('Micrsoft.XMLHTTP');
            sms.overrideMimeType('text/xml');
        }
        sms.onreadystatechange = AJAXSMS;
        sms.open('GET', 'SendSMS.php?Department=' + department + '&Receiver=' + receiver + '&Registration_ID=' + RegNo, true);
        sms.send();

        function AJAXSMS() {
            var smsrespond = sms.responseText;
            document.getElementById('SMSRespond').innerHTML = smsrespond;
        }
    }


    $(document).ready(function() {
        $("#verification_dialog").dialog({
            autoOpen: false,
            width: 900,
            height: 400,
            title: 'AUTHORIZATION DETAILS FOR <?php echo strtoupper($Patient_Name); ?>',
            modal: true
        });

        $("#pf3dialog").dialog({
            autoOpen: false,
            width: 900,
            height: 560,
            title: 'Fill Pf3 Details For <?php echo $Patient_Name; ?>',
            modal: true
        });
        //       $(".ui-widget-header").css("background-color","blue");
        $("#doctor_confirm_death").select2();
        $("#pf3").live("click", function() {
            //alert("chosen");
            if ($(this).is(':checked')) {
                $("#pf3dialog").dialog("open");
            }
        });

        $(".ui-icon-closethick").click(function() {
            //         $(this).hide();
            $("#pf3").attr("checked", false);
        });

        $("#pf3submit").click(function(e) {
            e.preventDefault();
            if ($("#Police_Station").val() !== '' && $("#P_Reason").val() !== '') {
                $("#pf3form").submit();
            } else {

                alert("Police station name and Reason are required");
                if ($("Police_Station").val() == '') {
                    $("#Police_Station").focus();
                } else if ($("P_Reason").val() == '') {
                    $("#P_Reason").focus();
                }
                $("#Police_Station").css("border-color", "red");
                $("#P_Reason").css("border-color", "red");

            }

        });

    });

    //Deceased patient

    $(document).ready(function() {
        $("#deceased_dialog").dialog({
            autoOpen: false,
            width: 900,
            height: 400,
            title: 'Death Details For <?php echo $Patient_Name; ?>',
            modal: true
        });
        $("#deathreason_dialog").dialog({
            autoOpen: false,
            width: 500,
            height: 100,
            title: 'Add death reason',
            modal: true
        });
        $("#issue_card_dialog").dialog({
            autoOpen: false,
            width: '40%',
            height: 300,
            title: 'ISSUE AFYA CARD ',
            modal: true
        });
        $("#verify_card_dialog").dialog({
            autoOpen: false,
            width: '60%',
            height: 650,
            title: 'VERIFY AFYA CARD ',
            modal: true
        });
        //       $(".ui-widget-header").css("background-color","blue");

        $("#Diseased").live("click", function() {
            //alert("chosen");

            if ($(this).is(':checked')) {
                $("#deceased_dialog").dialog("open");
            }
        });

        $(".ui-icon-closethick").click(function() {
            //         $(this).hide();
            // $("#Diseased").attr("checked", false);
        });

        $("#deceasedsubmit").click(function(e) {
            e.preventDefault();
            //            if ($("#P_Reason").val() !== '') {
            //
            //                $("#deceasedform").submit();
            //            } else {
            //               // alert("Fill all the information to proceed!");
            //                if ($("Relative").val() == '') {
            //                    $("#Relative").focus();
            //                } else if ($("P_Reason").val() == '') {
            //                    $("#P_Reason").focus();
            //                }
            //                $("#Relative").css("border-color", "red");
            //                $("#P_Reason").css("border-color", "red");
            //
            //            }

            var Registration_ID = '<?php echo $Registration_ID; ?>';

            if (Registration_ID == '' || Registration_ID == null) {
                alert('Patient is not selected,select a patient and try again!');
                return false;
            }

            var death_date = $('#death_date').val();
            if (death_date == '' || death_date == null) {
                $("#death_date").css("border-color", "red");
                return false;
            }
            $("#doctor_confirm_death").select2();
            var doctor_confirm_death = $('#doctor_confirm_death').val();
            if (doctor_confirm_death == '' || doctor_confirm_death == null) {
                alert("Select Doctor Who Confirm Dearth");
                $("#doctor_confirm_death").css("border-color", "red");
                return false;
            }
            var place_of_death = $('#place_of_death').val();
            if (place_of_death == '' || place_of_death == null) {
                $("#place_of_death").css("border-color", "red");
                return false;
            }


            var P_Reason = $('#Death_Reason').val();
            if (P_Reason == '' || P_Reason == null) {
                $("#Death_Reason").css("border-color", "red");
                return false;
            }

            var Relative = $('#Relative_Name').val();

            if (Relative == '' || Relative == null) {
                $("#Relative_Name").css("border-color", "red");
                alert('Relative name is required');
                return false;
            }

            var Relationship_type = $('#Relationship_type').val();

            var Phone_No_Relative = $('#Phone_No_pt').val();
            if (Phone_No_Relative == '' || Phone_No_Relative == null) {
                alert('Relative phone number is required');
                return false;
            }
            var relative_address = $('#relative_address').val();

            if (confirm('Are you sure this patient is deceased?')) {
                $.ajax({
                    type: 'POST',
                    url: 'save_deceased_patients.php',
                    data: 'action=save&P_Reason=' + P_Reason + '&Relative=' + Relative + '&Relationship_type=' + Relationship_type + '&Phone_No_Relative=' + Phone_No_Relative + '&Address=' + relative_address + '&$Registration_ID=' + Registration_ID + '&death_date=' + death_date + "&doctor_confirm_death=" + doctor_confirm_death + "&place_of_death=" + place_of_death,
                    cache: false,
                    success: function(z) {
                        alert(z);
                        $("#Diseased").attr("checked", true);
                    }
                });
            }

        });

    });


    $('#add_death_reason').on('click', function() {
        $("#deathreason_dialog").dialog("open");
    });


    $('#deathreasonsubmit').on('click', function(e) {
        e.preventDefault();
        var Death_Reason = $('#Death_Reason_value').val();
        if (Death_Reason == '' || Death_Reason == null) {
            alert('Write death reason to continue');
            return false;
        }
        $.ajax({
            type: 'POST',
            url: 'save_deceased_patients.php',
            data: 'action=save_reason&Death_Reason=' + Death_Reason,
            cache: false,
            success: function(z) {
                $('#Death_Reason').html(z);
                $('#Death_Reason_value').val('');
            }
        });
    });
</script>

<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('#death_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#death_date').datetimepicker({
        value: '',
        step: 30
    });
</script>


<script type="text/javascript">
    function Get_Selected_Patient() {
        var Registration_ID = '<?php echo $Registration_ID; ?>';
        if (Registration_ID != null && Registration_ID != '') {
            document.location = 'receptiondepartmentalothersworkspage.php?Registration_ID=' + Registration_ID + '&ReceptionDepartmentalPatientBilling=ReceptionDepartmentalPatientBillingThisForm';
        } else {
            $("#No_Patient_Available").dialog("open");
        }
    }
</script>

<script type="text/javascript">
    function Search_ePayment_Details() {
        document.getElementById("P_Name").value = '';
        document.getElementById("Patient_No").value = '';
        document.getElementById("P_Gender").value = '';
        document.getElementById("Phone_No").value = '';
        document.getElementById("Patient_Age").value = '';
        document.getElementById("Patient_Occupation").value = '';
        document.getElementById("Invoice_No").value = '';
        document.getElementById("Amount_Required").value = '';
        document.getElementById("Reference_Date").value = '';
        document.getElementById("Transaction_Ref").value = '';
        document.getElementById("Reference_Date").value = '';
        document.getElementById("Transaction_Status").value = '';
        document.getElementById("Invoice_Number").value = '';
        document.getElementById("ePayment_Button_Area").innerHTML = '&nbsp';
        $("#ePayment_Details_Area").dialog("open");
    }
</script>


<script type="text/javascript">
    function Clear_Current_Contents() {
        document.getElementById("P_Name").value = '';
        document.getElementById("Patient_No").value = '';
        document.getElementById("P_Gender").value = '';
        document.getElementById("Phone_No").value = '';
        document.getElementById("Patient_Age").value = '';
        document.getElementById("Patient_Occupation").value = '';
        document.getElementById("Invoice_No").value = '';
        document.getElementById("Amount_Required").value = '';
        document.getElementById("Reference_Date").value = '';
        document.getElementById("Transaction_Ref").value = '';
        document.getElementById("Reference_Date").value = '';
        document.getElementById("Transaction_Status").value = '';
        document.getElementById("ePayment_Button_Area").innerHTML = '&nbsp;';
    }
</script>

<script type="text/javascript">
    function Print_Payment_Code(Payment_Code) {
        var winClose = popupwindow('paymentcodepreview.php?Payment_Code=' + Payment_Code + '&PaymentCodePreview=PaymentCodePreviewThisPage', 'INVOICE NUMBER', 530, 400);
    }

    function popupwindow(url, title, w, h) {
        var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);
        return mypopupWindow;
    }
</script>


<script type="text/javascript">
    function Print_Receipt_Payment(Patient_Payment_ID) {
        var winClose = popupwindow('invidualsummaryreceiptprint.php?Patient_Payment_ID=' + Patient_Payment_ID + '&IndividualPaymentReport=IndividualPaymentReportThisPage', 'Receipt Patient', 530, 400);
    }

    function popupwindow(url, title, w, h) {
        var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);
        return mypopupWindow;
    }
</script>

<script type="text/javascript">
    function Check_In_Warning() {
        $("#Check_In_Warning").dialog("open");
    }

    function close_this_cance_ad_dialogy() {
        $("#Check_In_Warning").dialog("close");
    }
</script>
<script>
    function Check_In_Limit_SignedOff(doctorname, sentday, consult_per_day) {
        $("#consultation_limit").html("<b>Can't checking, <?php echo $Guarantor_Name ?> checking is " + consult_per_day + " times a day. </b><br/><b>Last sent to doctor is on " + sentday + ". <br/>Consulant: " + doctorname + "</b>");
        $("#Check_In_Limit").dialog("open");
    }
</script>

<script type="text/javascript">
    function Check_In_Warning_SignedOff(doctorname, status) {
        var st, ns;
        if (status == 'not served') {
            st = 'ATTENDED BY THE DOCTOR';
            ns = 'NO SHOW';
        } else if (status == 'served') {
            st = 'SIGNED OFF';
            ns = 'SIGNE OFF';
        }
        //alert(doctorname);
        $("#consultedbydoctor").html("<b>Please note, the selected patient is not " + st + ". Doctor should " + ns + " the patient before checking again!</b><br/><b>Consulant: " + doctorname + "</b>");
        $("#Check_In_Warning_SignedOff").dialog("open");
    }
</script>

<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css" />
<link rel="stylesheet" href="style.css" media="screen">
<link rel="stylesheet" href="style.responsive.css" media="all">
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script>
    $(document).ready(function() {
        $("#No_Patient_Available").dialog({
            autoOpen: false,
            width: '30%',
            height: 150,
            title: 'eHMS 2.0 ~ Information!',
            modal: true
        });
        $("#Update_Error").dialog({
            autoOpen: false,
            width: '40%',
            height: 120,
            title: 'eHMS 2.0 ~ Error!',
            modal: true
        });
    });
</script>

<script>
    $(document).ready(function() {
        $("#Check_In_Warning,#Check_In_Warning_SignedOff,#Check_In_Limit").dialog({
            autoOpen: false,
            width: '35%',
            height: 180,
            title: 'eHMS 2.0 ~ Information!',
            modal: true
        });
    });
</script>

<script>
    $(document).ready(function() {
        $("#ePayment_Details_Area").dialog({
            autoOpen: false,
            width: '60%',
            height: 450,
            title: 'ePAYMENT DETAILS',
            modal: true
        });
        $("#Exemption_Details_Dialog").dialog({
            autoOpen: false,
            width: '80%',
            height: 550,
            title: 'Taarifa za <?php echo strtoupper($Patient_Name); ?> za msamaha zinahitajika',
            modal: true
        });
    });
</script>

<!--end of ceck in process-->
<?php

function getTribeName($Tribe)
{
    global $conn;
    $sql = "SELECT tribe_name FROM tbl_tribe WHERE tribe_id = '$Tribe'";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    while ($row = mysqli_fetch_assoc($result)) {
        extract($row);
    }
    return $tribe_name;
}


function getVillageName($villageId)
{
    global $conn;
    $result = mysqli_query($conn, "SELECT village_name FROM tbl_village WHERE village_id='$villageId'") or die(mysqli_error($conn));
    while ($row = mysqli_fetch_assoc($result)) {
        extract($row);
    }
    return $village_name;
}

//check Membership ID Number Status
$mandatory_auth_no = "";
//die($Sponsor_ID);
//$sql_check_membership_id_number_status_result=mysqli_query($conn,"SELECT Membership_Id_Number_Status FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID' AND Membership_Id_Number_Status='Mandatory'") or die(mysqli_error($conn));
$sql = "SELECT Membership_Id_Number_Status FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID' AND Membership_Id_Number_Status='Mandatory'";
$sql_check_membership_id_number_status_result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
if (mysqli_num_rows($sql_check_membership_id_number_status_result) > 0) {
    $mandatory_auth_no = "Mandatory";
}


include("./includes/footer.php");
?>
<style>
    #issue_card_dialog table {
        width: 100%;
        border: 1 px solid black;
    }

    #issue_card_dialog table td {
        padding: 5px;
        border: 1 px;
        font-weight: bold;
    }

    #issue_card_dialog table tr:hover {
        background-color: #C0C0C0;
        color: #000;
    }

    #save_afya_card {}

    .review_image {
        width: 40px;
        height: 40px;
        float: right;
    }

    #verify_card_dialog {
        overflow: auto !important;
        border: 1 px solid black;
    }

    #verify_card_dialog table {
        width: 100%;
        border: 1 px solid black;
    }
</style>
<div id="issue_card_dialog">
    <table>
        <tr>
            <td>Full Name: </td>
            <td><?= $Patient_Name; ?></td>
        </tr>
        <tr>
            <td>Gender: </td>
            <td><?= $Gender; ?></td>
        </tr>
        <tr>
            <td>Age: </td>
            <td><?= $diff->y; ?></td>
        </tr>
        <tr>
            <td>Card Number: </td>
            <td>
                <nobr>
                    <input type='text' id='card_number' name='card_number' value='' disabled style='width:70%;'>
                    <input type='button' id='get_card_no' name='get_card_no' value='Get Card No' class='art-button' onclick='get_card_number();'>
                </nobr>
            </td>
        </tr>
    </table>
    <br>
    <br>
    <center>
        <input type="button" name="save_afya_card" id="save_afya_card" class="art-button" value="Save" onclick="write_afya_card('<?= $Registration_ID; ?>','register','<?= $Employee_ID; ?>');">
    </center>
</div>

<div id="verify_card_dialog" style="height:400px;overflow:scroll;">
    <div class="row">
        <div class="col-md-12" id="detail_information">



        </div>
    </div>
</div>

<?php

?>
<script type="text/javascript" src="js/afya_card.js"></script>
<script type="text/javascript" src="js/finger_print.js"></script>
<script type="text/javascript">
    $('#referred_from_hospital_id').select2();
    $('.select2').hide();

    function displayHospitals() {
        if ($('#referral_status').is(":checked")) {
            $('#referred_from_hospital_id,#addHospitals').show();
            $('.select2').show();
            $('#referred_from_hospital_id').addClass("select2");
            $('#referred_from_hospital_id').attr("required", "required");
        } else {
            $('#referred_from_hospital_id').removeAttr("required", "required");
            $('#referred_from_hospital_id,#addHospitals').hide();
            $('.select2').hide();
        }
    }
    $('#addHospitals').click(function(e) {
        if (confirm("DID YOU SEARCH AND MISSED IT FROM THE LIST?")) {
            e.preventDefault();
            $("#hospitals").dialog("open");

        }
    });
    $(document).ready(function() {
        $("#hospitals").dialog({
            autoOpen: false,
            width: '50%',
            height: 150,
            title: 'ADD NEW HOSPITAL',
            modal: true
        });
    })

    function validate_refferal_number_txtbox() {
        var VisitTypeID = $("#VisitTypeID").val();
        var referral_no_txt = $("#referral_no_txt").val();
        var referral_reason = $("#referral_reason").val();
        var referred_from_hospital_id = $("#referred_from_hospital_id").val();
        if (VisitTypeID == 3) {
            if (referral_no_txt == '') {
                $("#referral_no_txt").focus();
                $("#referral_no_txt").css("border", "3px solid red");

                return false;
            }
            if (referral_reason == "") {
                $("#referral_reason").css("border", "2px solid red");
                $("#referral_reason").focus();
                return false;
            }
            if (referred_from_hospital_id == "") {
                alert("Select Referral Hospital");
                return false;
            }
        }
        return true;
    }

    function check_if_already_checked_in_today(Registration_ID) {
        var Date_Of_Birth_check = $("#Date_Of_Birth_check").val();
        if (document.getElementById('Phone_Number').value.length != 10) {
            alert('EDIT PHONE NUMBER, ONLY 10 DIGITS PHONE NUMBERS ARE ALLOWED !!\n e.g 0712xxxxxx');
            return false;
        }
        if (Date_Of_Birth_check == '0000-00-00') {
            alert('EDIT PATIENT DATE OF BIRTH !!');
            $("#Age").css("border", "3px solid red");
            return false;
        }
        var VisitTypeID = $("#VisitTypeID").val();
        var Type_Of_Check_In = $("#Type_Of_Check_In").val();

        var AuthorizationNo = $("#AuthorizationNo").val();
        var mandatory_auth_no = '<?= $mandatory_auth_no ?>';
        if (mandatory_auth_no == "Mandatory") {
            if (AuthorizationNo === "") {
                $(".msg").html("Please Write Autorization number first");
                return false;
            }
        }
        if (!check_sponsor_package()) {
            return false;
        }
        $.ajax({
            type: 'GET',
            url: 'check_if_already_checked_in_today.php',
            // syn:true,
            data: {
                Registration_ID: Registration_ID
            },
            success: function(data) {
                if (data == "already") {
                    if (confirm("The patient is already checked in...Are you sure you want to check in this patient again?\n\n click ok to continue or cancel to stop the check in process")) {
                        if (validate_refferal_number_txtbox()) {
                            if (VisitTypeID == "") {
                                $("#VisitTypeID").css("border", "2px solid red");
                                exit;
                            }
                            if (VisitTypeID == "") {
                                $("#VisitTypeID").css("border", "2px solid red");
                                exit;
                            }

                            $("#checkmyForm").submit()
                        }
                    } else {

                    }
                } else {
                    if (validate_refferal_number_txtbox()) {
                        if (VisitTypeID == "") {
                            $("#VisitTypeID").css("border", "2px solid red");
                            exit;
                        }
                        if (Type_Of_Check_In == "") {
                            $("#Type_Of_Check_In").css("border", "2px solid red");
                            exit;
                        }
                        $("#checkmyForm").submit()
                    }

                }

            }
        });

    }

    function show_refferal_number_row() {
        var VisitTypeID = $("#VisitTypeID").val();
        //        alert(VisitTypeID);
        if (VisitTypeID == 3) {
            //            alert("hhhhh");
            $("#referral_row").show();
            $("#referral_no_txt").focus();
            $("#referral_no_txt").css("border", "1px solid blue");
            $('#referral_status').attr('checked', true);
            displayHospitals();
        } else {
            $("#referral_row").hide();
            $('#referral_status').attr('checked', false);
            displayHospitals();
        }
    }
</script>
<script type="text/javascript">
    function check_sponsor_package() {
        var package_selected = document.getElementById("select_package");
        var item_update_api = "<?= $auto_item_update_api; ?>";
        if (item_update_api == 1 && package_selected.value == '') {
            alert('SELECT PACKAGE');
            package_selected.style = 'border:2px solid red;'
            return false;
        }
        return true;
    }
</script>
<script type="text/javascript" src="js/finger_print.js"></script>
<input type='hidden' id='finger_print_details' value='visitors'>
<input type='hidden' id='Registration_ID' value='<?= $Registration_ID ?>'>
