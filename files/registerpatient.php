
<head>
    <!-- <title>Capture</title> -->

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" /> -->
    <!-- <script src="webcam1js"></script>
    <script src="webcam2.js"></script>
    <link rel="stylesheet" href="webcam.css" /> -->
    <style type="text/css">
        #results {
            padding: 20px;
            border: 1px solid;
            background: #ccc;
        }
    </style>
</head>
<?php

ini_set('display_error', 1);
ini_set('display_startup_error', 1);
error_reporting(E_ALL);

include("./includes/header.php");
include("./includes/connection.php");
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
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$villageId  = "";
$villageName = "";

$Today_Date = mysqli_query($conn, "select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}

if (isset($_GET['Section'])) {
    $Section = $_GET['Section'];
} else {
    $Section = '';
}


if (isset($_GET['patient_name'])) {
    $patient_names = explode(" ", ucwords($_GET['patient_name']));
    $patient_first_name = $patient_names[0];
    $patient_middle_name = $patient_names[1];
    $patient_last_name = $patient_names[2];
} else {
    $patient_first_name = "";
    $patient_middle_name = "";
    $patient_last_name = "";
}
if (isset($_GET['date_of_birth'])) {
    $patient_age = $_GET['date_of_birth'];
} else {
    $patient_age = "";
}
?>

<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Reception_Works'] == 'yes') {
?>
        <a href='searchvisitorsoutpatientlist.php?SearchVisitorsOutPatientList=SearchVisitorsOutPatientListThisPage' class='art-button-green'>
            VISITORS
        </a>
<?php
    }
}
?>

<?php
if (strtolower($Section) == 'reception' || strtolower($Section) == 'visitor') {
    echo "<a href='patientslist.php?Section=" . $Section . "&NewVisitors=NewVisitorsThisPage' class='art-button-green'>BACK</a>";
} else if (isset($_GET['from_directdepartmental'])) {
    echo "<a href='directdepartmentalpayments.php' class='art-button'>BACK</a>";
} else {
    echo "<a href='searchvisitorsoutpatientlist.php?RegisterPatient=RegisterPatientThisPage' class='art-button-green'>BACK</a>";
}
?>

<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>

<script type="text/javascript">
    function readImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#Patient_Picture').attr('src', e.target.result).width('50%').height('70%');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function clearPatientPicture() {
        document.getElementById('Patient_Picture_td').innerHTML = "<img src='./patientImages/default.png' id='Patient_Picture' name='Patient_Picture' width=50% height=50%>"
    }
</script>

<script language="javascript" type="text/javascript">
    function searchEmployee(Employee_Name) {
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=100% src='viewpatientsIframe.php?Employee_Name=" + Employee_Name + "'></iframe>";
    }
</script>


<script type="text/javascript" language="javascript">
    function getDistricts() {
        var Region_Name = document.getElementById("region").value;
        console.log(Region_Name);
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }

        mm.onreadystatechange = AJAXP; //specify name of function that will handle server response....
        mm.open('GET', 'GetDistricts.php?Region_Name=' + Region_Name + '&From=GetDistricts', true);
        mm.send();
        $('#District').val("");
        $('#select-ward').val("");
        $('#select-village').val("");
        $("#ten_cell_leader_name").html("<option>Select Leader</option>");
        $("#select-village").html("<option>Select Village</option>");
        $("#select-ward").html("<option>Select Ward</option>");
    }



    // function Wards() {
    //     var data = mm.responseText;
    //     document.getElementById('select-ward').innerHTML = data;
    // }
    function AJAXP() {
        var data = mm.responseText;
        document.getElementById('District').innerHTML = data;
    }

    //    function to verify NHIF STATUS
    function nhifVerify() {
        //code
    }
</script>

<script type="text/javascript">
    function get_Regions() {
        var country = document.getElementById("country").value;

        if (window.XMLHttpRequest) {
            myObjectRegs = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectRegs = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRegs.overrideMimeType('text/xml');
        }

        myObjectRegs.onreadystatechange = function() {
            dataReg = myObjectRegs.responseText;
            if (myObjectRegs.readyState == 4) {
                document.getElementById('region').innerHTML = dataReg;
                getDistricts();
            }
        }; //specify name of function that will handle server response........
        myObjectRegs.open('GET', 'get_Regions.php?country=' + country, true);
        myObjectRegs.send();
    }
</script>

<!--		NHIF VERIFICATION FUNCTION		-->
<script type="text/javascript" language="javascript">
    //get verification button
    function setVerify(sponsor) {
        if (sponsor == 'NHIF') {
            document.getElementById('eVerify_btn').style.visibility = "";
        } else {
            document.getElementById('eVerify_btn').style.visibility = "hidden";
            document.getElementById("Patient_Name").value = '';
            document.getElementById("Patient_Name").removeAttribute('readonly');
            document.getElementById("Employee_Vote_Number").value = '';
            document.getElementById("Employee_Vote_Number").removeAttribute('readonly');
            document.getElementById("date").value = '';
            document.getElementById("date").removeAttribute('disabled');
            document.getElementById("date2").value = '';
            document.getElementById("datetime").value = '';
            document.getElementById("date2").removeAttribute('disabled');
            document.getElementById("Gender").innerHTML = "<option></option><option>Male</option><option>Female</option>";
            document.getElementById("Member_Number").setAttribute('style', 'border-color:default;width: 150px;text-align: left;');
        }
    }
</script>
<script src="js/token.js"></script>
<script>
    function MemberNumberMandate(sponsor) {
        var Insurance = document.getElementById("Guarantor_Name").value;
        $.ajax({
            url: "./MemberNumberMandateStatus.php?sponsor=" + sponsor,
            type: "GET"
        }).done(function(result) {
            if (result.replace(" ", '') == "Mandatory") {
                document.getElementById('Member_Number').setAttribute('required', 'required');
                if (Insurance == 'NHIF') {
                    document.getElementById('Employee_Vote_Number').setAttribute('required', 'required');
                } else {
                    document.getElementById('Employee_Vote_Number').removeAttribute('required');
                }
            } else {
                document.getElementById('Member_Number').removeAttribute('required');
                if (Insurance == 'NHIF') {
                    document.getElementById('Employee_Vote_Number').setAttribute('required', 'required');
                } else {
                    document.getElementById('Employee_Vote_Number').removeAttribute('required');
                }
            }
        });
    }
</script>
<br />

<script>
    //disable member number when cash is selected
    function disable_member_number(Guarantor_Name) {
        if (Guarantor_Name == 'CASH') {
            document.getElementById("Member_Number").disabled = 'disabled';
        } else {
            document.getElementById('Member_Number').removeAttribute('disabled');
        }
    }
</script>
<fieldset>
    <legend align=center><b>PATIENT REGISTRATION</b></legend><br />
    <center>
        <table width=100%>
            <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm(); finger_print_status();" enctype="multipart/form-data">
                <tr>
                    <td width=35%>
                        <table width=100%>

                            <?php
                            $configvalue = "";
                            $result = mysqli_query($conn, "SELECT configvalue FROM tbl_config WHERE configname='Military'") or die(mysqli_error($conn));
                            $result_row = mysqli_fetch_assoc($result);
                            $configvalue = $result_row['configvalue'];
                            if ($configvalue == 'Yes') {
                            ?>
                                <tr>
                                    <td style="text-align: right;">Patient Type</td>
                                    <td>
                                        <label><input type="radio" class="patient_type_radio" name="patient_type" checked='checked' value="civilian">Civilian</label>
                                        <label><input type="radio" class="patient_type_radio" name="patient_type" id="military_personnel_radio" value="military">Military Personnel</label>
                                        <label><input type="radio" class="patient_type_radio" name="patient_type" value="military_dependant">Dependant</label>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>

                            <tr>
                                <td style='text-align: right'><b style='color: red'>Sponsor</td>
                                <td>
                                    <select name='Guarantor_Name' id='Guarantor_Name' required='required' style='border-color: red' onchange='MemberNumberMandate(this.value);setVerify(this.value); disable_member_number(this.value); Modify_Patient_Name();'>
                                        <option selected='selected'></option>
                                        <?php
                                        if (isset($_SESSION['systeminfo']['Include_Exemption_Sponsors_In_Normal_Registration']) && strtolower($_SESSION['systeminfo']['Include_Exemption_Sponsors_In_Normal_Registration']) == 'yes') {
                                            $data = mysqli_query($conn, "select * from tbl_sponsor") or die(mysqli_error($conn));
                                        } else {
                                            $data = mysqli_query($conn, "select * from tbl_sponsor WHERE Exemption = 'no'") or die(mysqli_error($conn));
                                        }

                                        while ($row = mysqli_fetch_array($data)) {

                                            echo '<option value="' . $row['Guarantor_Name'] . '">' . $row['Guarantor_Name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr id="military_row_data" style="display: none">
                                <td colspan="2">
                                    <table style="width: 100%">
                                        <tr>
                                            <td style='text-align: right'>Service Number</td>
                                            <td colspan="3"><input type="text" name="service_number" style="border:1px solid red"></td>
                                        </tr>
                                        <tr>
                                            <td style='text-align: right'>Rank</td>
                                            <td>
                                                <select style="width: 100%" name="military_rank" id="military_rank">
                                                    <option value="">~~Select Rank~~</option>
                                                    <?php
                                                    $sql_selct_rank_result = mysqli_query($conn, "SELECT *FROM tbl_ranks") or die(mysqli_error($conn));
                                                    if (mysqli_num_rows($sql_selct_rank_result) > 0) {
                                                        while ($rank_rows = mysqli_fetch_assoc($sql_selct_rank_result)) {
                                                            $rank_name = $rank_rows['rank_name'];
                                                            $rank_id = $rank_rows['rank_id'];
                                                            echo "
                                                                     <option value='$rank_name'>$rank_name</option>
                                                                   ";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td><input type="radio" name="active_status" checked="checked" value="active" style="border:1px solid red">Active</td>
                                            <td><input type="radio" name="active_status" value="retired" style="border:1px solid red">Retired</td>
                                        </tr>
                                        <tr>
                                            <td style='text-align: right'>Unit</td>
                                            <td colspan="3">
                                                <select name="unit" id="military_unit_name" style="width:50%;">
                                                    <option value="">~~Select Unit~~</option>
                                                    <?php
                                                    $count = 1;
                                                    $sql_selct_rank_result = mysqli_query($conn, "SELECT *FROM tbl_military_units") or die(mysqli_error($conn));
                                                    if (mysqli_num_rows($sql_selct_rank_result) > 0) {
                                                        while ($rank_rows = mysqli_fetch_assoc($sql_selct_rank_result)) {
                                                            $military_unit_name = $rank_rows['military_unit_name'];
                                                            $military_unit_id = $rank_rows['military_unit_id'];
                                                            echo "
                                                                           <option value='$military_unit_name'>$military_unit_name</option>
                                                                        ";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id="dependant_row_data" style="display: none">
                                <td colspan="2">
                                    <table style="width:100%">
                                        <tr>
                                            <td style='text-align: right'>Dependant Number</td>
                                            <td colspan="2"><input type="text" name="dependant_number" id="dependant_number" style="border:1px solid red"></td>
                                        </tr>
                                        <tr>
                                            <td style='text-align: right'>Service Number</td>
                                            <td><input type="text" name="dependent_service_number" id="dependent_service_number" style="border:1px solid red"></td>
                                            <td>
                                                <input type="button" class="art-button" value="SEARCH" onclick="open_militaary_personnel_info()" />
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
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
                            <tr>
                                <td style='text-align: right'>Member Number</td>
                                <td><input type='text' name='Member_Number' autocomplete='off' id='Member_Number' style="width: 150px;text-align: left;">
                                    <input type="button" value="NHIF-eVerify" id='eVerify_btn' onclick="verifyNHIF3('<?= $extenal_nhif_server_url ?>');" class="art-button" style="text-align: right;visibility: hidden;" />
                                </td>
                            </tr>
                            <tr>
                                <td style='text-align: right'>Member Card Expire Date</td>
                                <td><input type='text' name='Member_Card_Expire_Date' autocomplete='off' id='date'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right'>New Registration Number</td>
                                <td><input type='text' name='New_Registration_Number' disabled='disabled' id='New_Registration_Number'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right'>Old Registration Number</td>
                                <td><input type='text' name='Old_Registration_Number' autocomplete='off' id='Old_Registration_Number'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right'>Title</td>
                                <td>
                                    <select name='Patient_Title' id='Patient_Title'>
                                        <option selected='selected'></option>
                                        <option>Mr</option>
                                        <option>Mrs</option>
                                        <option>Miss</option>
                                        <option>Master</option>
                                        <option>Dr</option>
                                        <option>Prof</option>
                                        <option>Ms</option>
                                    </select>
                                </td>
                            </tr>
                            <?php if (isset($_SESSION['systeminfo']['Registration_Mode']) && strtolower($_SESSION['systeminfo']['Registration_Mode']) <> 'receiving patient names together') { ?>
                                <tr>
                                    <td style='text-align: right'><b style='color: red' id='F_Area'>Patient First Name</td>
                                    <td><input type='text' name='Patient_Name' autocomplete='off' id='Patient_Name' style='border-color: red' value="<?= $patient_first_name ?>" required='required'></td>
                                </tr>
                                <tr>
                                    <td style='text-align: right'><b <?php
                                                                        if (strlen($_SESSION['systeminfo']['Registration_Mode']) == 58) {
                                                                            echo "style='color: red'";
                                                                        }
                                                                        ?> id='M_Area'>Patient Middle Name</td>
                                    <td><input type='text' name='Patient_Middle_Name' autocomplete='off' value="<?= $patient_middle_name ?>" id='Patient_Middle_Name' <?php
                                                                                                                                                                        if (strlen($_SESSION['systeminfo']['Registration_Mode']) == 58) {
                                                                                                                                                                            echo "required='required' style='border-color: red'";
                                                                                                                                                                        }
                                                                                                                                                                        ?>></td>
                                </tr>
                                <tr>
                                    <td style='text-align: right'><b style='color: red' id='L_Area'>Patient Last Name</td>
                                    <td><input type='text' name='Patient_Last_Name' autocomplete='off' id='Patient_Last_Name' style='border-color: red' value="<?= $patient_last_name ?>" required='required'></td>
                                </tr>
                            <?php } else { ?>
                                <tr>
                                    <td style='text-align: right'><b style='color: red'>Patient Name</td>
                                    <td><input type='text' name='Patient_Name' autocomplete='off' id='Patient_Name' style='border-color: red' required='required'></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td style='text-align: right'><b style='color: red'>Date Of Birth</td>
                                <td><input type='text' name='Date_Of_Birth' autocomplete='off' id='date2' value="<?= $patient_age ?>" style='border-color: red;width:70%' required='required' />&nbsp;&nbsp;<input type='text' autocomplete='off' id='datetime' oninput='calculatedate(this.value)' style='width:25%;text-align:center' placeholder="enter age" maxlength="3" class="numberonly" /></td>
                            </tr>
                            <tr>
                                <td style='text-align: right'><b style='color: red'>Gender</td>
                                <td id='gender_dom'>
                                    <?php if (isset($_GET['gender'])) {
                                        $patient_gender = $_GET['gender'];
                                    } else {
                                        $patient_gender = "";
                                    } ?>
                                    <select name='Gender' id='Gender' required='required' style='border-color: red'>
                                        <option selected='selected'></option>
                                        <option <?php if ($patient_gender == "Male") {
                                                    echo "selected='selected'";
                                                } ?>>Male</option>
                                        <option <?php if ($patient_gender == "Female") {
                                                    echo "selected='selected'";
                                                } ?>>Female</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style='text-align: right'><b <?php
                                                                    if (isset($_SESSION['systeminfo']['Require_Patient_Phone_Number']) && strtolower($_SESSION['systeminfo']['Require_Patient_Phone_Number']) == 'yes') {
                                                                        echo "style='color: red'";
                                                                    }
                                                                    ?>>Patient Phone Number</b></td>
                                <td><input type='text' name='Phone_Number' autocomplete='off' id='Phone_Number' <?php
                                                                                                                if (isset($_SESSION['systeminfo']['Require_Patient_Phone_Number']) && strtolower($_SESSION['systeminfo']['Require_Patient_Phone_Number']) == 'yes') {
                                                                                                                    echo "required='required'  style='border-color: red'";
                                                                                                                }
                                                                                                                ?> onfocus="addCode()"></td>
                            </tr>
                            <tr>
                                <td style='text-align: right'>Employee Vote</td>
                                <td><input type='text' name='Employee_Vote_Number' autocomplete='off' id='Employee_Vote_Number'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right'>Religion</td>
                                <td> <select name='religion' id='religion' onchange="getDenominations(this.value)">
                                        <option value="Not Applicable" selected='selected'>--Select Religion--</option>
                                        <?php
                                        $religions = mysqli_query($conn, "select * from tbl_religions");
                                        while ($row = mysqli_fetch_array($religions)) {
                                        ?>
                                            <option value='<?php echo $row['Religion_ID']; ?>'>
                                                <?php echo $row['Religion_Name']; ?>
                                            </option>
                                        <?php }
                                        ?>
                                    </select></td>
                            </tr>
                            <tr>
                                <td style="text-align: right;">
                                    Denomination
                                </td>
                                <td style="text-align: left;">
                                    <select name='denomination' id='denomination'>
                                        <option value="Not Applicable" selected="selected"> --Select Denomination--</option>
                                    </select>
                                </td>

                        </table>
                    </td>
                    <td width=35%>
                        <table width=100%>
                            <tr>
                                <td style='text-align: right'>Country</td>
                                <td>
                                    <select name='country' id='country' onchange='get_Regions()'>
                                        <option selected='selected' value=''>Select Country</option>
                                        <?php
                                        $data = mysqli_query($conn, "select Country_Name, Country_ID, (select Country_ID from tbl_regions where Region_Status = 'Selected') as Country_ID2 from tbl_country ORDER BY Country_ID ASC");
                                        while ($row = mysqli_fetch_array($data)) {
                                        ?>
                                            <option value='<?php echo $row['Country_Name']; ?>' <?php
                                                                                                if ($row['Country_ID'] == $row['Country_ID2']) {
                                                                                                    echo "selected='selected'";
                                                                                                }
                                                                                                ?>><?php echo $row['Country_Name']; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>


                                <td style='text-align: right'>Region</td>
                                <td id="Regions_Area">
                                    <?php
                                    //get initial region
                                    $Control_Region = 'yes';
                                    $slct = mysqli_query($conn, "select Region_ID, Region_Name from tbl_regions where Region_Status = 'Selected'") or die(mysqli_error($conn));
                                    $num = mysqli_num_rows($slct);
                                    if ($num > 0) {
                                        while ($data = mysqli_fetch_array($slct)) {
                                            $Selected_Region = $data['Region_Name'];
                                            $Region_ID = $data['Region_ID'];
                                        }
                                    } else { // select Dar es salaam
                                        $Control_Region = 'no';
                                        $Selected_Region = 'Dar es salaam';
                                    }
                                    ?>
                                    <select name='region' id='region' onchange='getDistricts()'>
                                        <option selected='selected' value="<?php echo $Selected_Region; ?>"><?php echo $Selected_Region; ?></option>
                                        <?php
                                        $data = mysqli_query($conn, "select * from tbl_regions where Region_Status = 'Not Selected' and Country_ID = (select Country_ID from tbl_regions where Region_Status = 'Selected')");
                                        while ($row = mysqli_fetch_array($data)) {
                                        ?>
                                            <option value='<?php echo $row['Region_Name']; ?>'>
                                                <?php echo $row['Region_Name']; ?>
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style='text-align: right'>District</td>
                                <td>
                                    <select name='District' id='District' required='required' required onchange="getWards();">
                                        <option selected='selected'>Select District</option>
                                        <?php if ($Control_Region == 'no') { ?>
                                            <option>Kinondoni</option>
                                            <option>Ilala</option>
                                            <option>Temeke</option>
                                            <?php
                                        } else {
                                            $select_districts = mysqli_query($conn, "select District_ID,District_Name from tbl_district where Region_ID = '$Region_ID'") or die(mysqli_error($conn));
                                            $num_districts = mysqli_num_rows($select_districts);
                                            if ($num_districts > 0) {
                                                while ($dt = mysqli_fetch_array($select_districts)) {
                                            ?>
                                                    <option value="<?php echo $dt['District_ID']; ?>"><?php echo $dt['District_Name']; ?></option>
                                        <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style='text-align: right'>Ward</td>
                                <td>
                                    <!--input type='text' autocomplete='off' name='Ward' id='Ward'-->
                                    <nobr><select id="select-ward" name="Ward" onchange="getVillage_Street();" style="width:60%;" required>
                                            <option selected value="">Select Ward
                                            <option>
                                                <?php
                                                $select_ward = mysqli_query($conn, "SELECT *FROM tbl_ward") or die(mysqli_error($conn));
                                                while ($row = mysqli_fetch_assoc($select_ward)) {
                                                    $ward_ID = $row['Ward_ID'];
                                                    $Ward_Name = $row['Ward_Name'];
                                                    echo "<option value='$ward_ID'>$Ward_Name</option>";
                                                }
                                                ?>
                                            <option value='others'>Others</option>
                                        </select>
                                        <input type="button" name="add_ward" id="add_ward" value="Add Ward" class="art-button-green" style="display:none;">
                                    </nobr>
                                    <span>
                                        <a href="#" class="art-button-green" id="add-ward">Add Ward</a></span>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td style='text-align: right'>Village/Street</td>
                                <td>
                                    <select name="village" id="select-village" onchange="getVillage_leader();" style="width:60%;">
                                        <option selected value="">Select Village</option>
                                    </select>
                                    </span>
                                    <span style="width:20%">
                                        <a href="#" class="art-button-green" id="add-village">Add Village</a></span>
                                </td>
                            </tr>
                            <tr>
                                <td style='text-align:right;'>Ten Cell Leader</td>
                                <td>
                                    <span style="width:79%">
                                        <select name="Leader" id="ten_cell_leader_name" style="width:60%;">
                                            <option value="">Select Leader</option>

                                        </select>
                                    </span>
                                    <span style="width:20%">
                                        <a href="#" class="art-button-green" id="add_leader">Add Leader</a></span>
                                </td>
                            </tr>
                            <tr>
                                <td style='text-align: right'>Tribe</td>
                                <td>
                                    <select name="Tribe" id="Tribe" style="width:100%;">
                                        <option value="__">Select Tribe</option>

                                        <?php
                                        $tribeResult = mysqli_query($conn, "SELECT tribe_id,tribe_name
                                                                    FROM tbl_tribe")                         or die(mysqli_error($conn));
                                        while ($tribeRow = mysqli_fetch_assoc($tribeResult)) {
                                            extract($tribeRow);
                                        ?>
                                            <option value="<?= $tribe_id; ?>"><?= $tribe_name; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style='text-align: right'>E-Mail</td>
                                <td><input type='email' name='Email' autocomplete='off' id='Email'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right;'>Relative Contact Name</td>
                                <td><input type='text' name='Emergence_Contact_Name' autocomplete='off' id='Emergence_Contact_Name'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right;'>Relative Contact Number</td>
                                <td><input type='text' name='Emergence_Contact_Number' autocomplete='off' onfocus='addCode2()' id='Emergence_Contact_Number'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right;'>Relationship</td>
                                <td><input type='text' name='Relationship' autocomplete='off' id='Relationship'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right'>Occupation</td>
                                <td><input type='text' name='Occupation' autocomplete='off' id='Occupation'></td>
                            </tr>

                            <tr>
                                <td style='text-align: right'>Company</td>
                                <td><input type='text' name='Company' id='Company' autocomplete='off'></td>
                            </tr>
                            <?php
                            if (isset($_SESSION['userinfo']['Employee_Name'])) {
                                $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
                            } else {
                                $Employee_Name = "Unknown Employee";
                            }
                            ?>
                            <tr>
                                <td style='text-align: right'>Prepared By</td>
                                <td><input type='text' name='Prepared_By' disabled='disabled' id='Prepared_By' value='<?php echo $Employee_Name; ?>'></td>
                            </tr>
                            <tr>
                                <!--td style=" text-align: right;">
                                    <label for="Diseased">Deceased</label>
                                </td>
                                <td style="text-align: left;">
                                    <input type="checkbox" name="Diseased" id="Diseased" value="true" onclick="confirm_message(this)">
                                    </td-->
                                <?php
                                $select_finger_print_info = mysqli_query($conn, "SELECT configname, configvalue FROM tbl_config WHERE configname IN('Show_Finger_Print','Finger_Print_Mandatory') ");
                                //$finger_print_info = mysqli_fetch_assoc($select_finger_print_info);
                                while ($finger_print_info = mysqli_fetch_assoc($select_finger_print_info)) {
                                    if ($finger_print_info['configname'] == 'Finger_Print_Mandatory') {
                                        $Finger_Print_Mandatory = $finger_print_info['configvalue'];
                                    } else if ($finger_print_info['configname'] == 'Show_Finger_Print') {
                                        $finger_print_config = $finger_print_info['configvalue'];
                                    }
                                }
                                if ($finger_print_config == 'Yes') {
                                ?>
                                    <td style="text-align:right;">Finger Print</td>
                                    <td>
                                        <input type="button" name="finger_print" value="Take Finger Print" class="art-button" onclick="open_finger_print_dialog();">
                                    </td>
                                <?php } else { ?>
                                    <td>&emsp;</td>
                                    <td>&emsp;</td>
                                <?php } ?>
                            </tr>
                            <tr>
                                <td style="text-align: right;">Registration Type</td>
                                <td>
                                    <select name="Registration_Type" id="Registration_Type">
                                        <option selected="selected" value="Normal">Normal Registration</option>
                                        <option value="Pre_Paid">Pre Paid Registration</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right;">
                                    <label>National ID</label>
                                </td>
                                <td style="text-align: left;">
                                    <input type="text" name="national_id" id="national_id" value="">
                                </td>
                            </tr>
                            <!--tr>
                                <td style="text-align: right;">
                                    <label>Disability</label>
                                </td>
                                <td style="text-align: left;">
                                    <select id='disability' name='disability'>
                                        <option value=""></option>
                                        <option value="disable">Disable</option>
                                        <option value='not disable'>Not Disable</option>
                                    </select>
                                </td>
                            </tr-->

                        </table>
                    </td>
                    <td width=30%>
                        <table width=100%>
                            <tr>
                                <td style='text-align: center;'>Patient Picture</td>
                            </tr>
                            <tr>
                                <td id='Patient_Picture_td' style='text-align: center;'>
                                    <img src='./patientImages/default.png' id='Patient_Picture' name='Patient_Picture' id='Patient_Picture' width=50% height=50%>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <center>
                                        <div id="my_camera"></div>
                                        <br />
                                        <!-- <input type=button value="Take Picture" onClick="take_snapshot()">

                                      <input type="hidden" name="image" class="image-tag">
                                      <div id="results">Our image</div> -->
                                        SELECT PICTURE<input type="file" name="Patient_Picture" id="file" onchange='readImage(this)' title='SELECT PATIENT PICTURE' />
                                    </center>
                                </td>
                            </tr>
                            <tr>
                                <td colspan=2 style='text-align: center;'>
                                    <input type='hidden' id='finger_print_details' name='finger_print_details' value=''>
                                    <input type="text" hidden="hidden" name="from_search_military_info" id="from_search_military_info">
                                    <input type='submit' name='submit' id='submit' value='   SAVE   ' style='width: 30%' class='art-button-green' onclick="Validate_Date()">
                                    <input type='reset' name='clear' id='clear' value=' CLEAR ' style='width: 30%' class='art-button-green' onclick='clearPatientPicture()'>
                                    <input type='hidden' name='submittedAddNewPatientForm' style='width: 30%' value='true' />
                                </td>
                            </tr>
                            <!--<tr>
                <td style = 'text-align: right;'>
                <br><input type = 'button' id = 'verify' name = 'verify' class = 'art-button-green' value = 'Member Number Status'>
                <br><div id = 'dom_verify' name = 'dom_verify' onclick = 'nhifVerify()'>
                Status:
                </div>
                </td>
                </tr> -->
                        </table>
                    </td>
                </tr>
            </form>
        </table>
    </center>
</fieldset><br />



<!--  insert data from the form  -->

<?php
///decompression function
function compress_image($source_url, $destination_url, $quality)
{

    $info = getimagesize($source_url);

    if ($info['mime'] == 'image/jpeg')
        $image = imagecreatefromjpeg($source_url);

    elseif ($info['mime'] == 'image/gif')
        $image = imagecreatefromgif($source_url);

    elseif ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($source_url);

    imagejpeg($image, $destination_url, $quality);
    return $destination_url;
}
////////////////////////////////////////////////////////////////////////////////


if (isset($_POST['submittedAddNewPatientForm'])) {
    // for webca image
    $img = $_POST['image'];
    $folderPath = "./patientImages/";

    $image_parts = explode(";base64,", $img);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];

    $image_base64 = base64_decode($image_parts[1]);
    $Patient_Picture = date('Ymd') . "_" . mt_rand(999, 1000000) . '.png';
    $file = $folderPath . $Patient_Picture;
    file_put_contents($file, $image_base64);

    // print_r($Patient_Picture);
    $Patient_Picture = 'default.png';

    // ends here

    if (isset($_SESSION['userinfo'])) {
        if (isset($_SESSION['userinfo']['Employee_ID'])) {
            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        } else {
            $Employee_ID = 0;
        }
    }


    $Old_Registration_Number = mysqli_real_escape_string($conn, $_POST['Old_Registration_Number']);
    $Patient_Title = mysqli_real_escape_string($conn, $_POST['Patient_Title']);
    // $disability = mysqli_real_escape_string($conn, $_POST['disability']);

    $Date_Of_Birth = mysqli_real_escape_string($conn, $_POST['Date_Of_Birth']);
    $Gender = mysqli_real_escape_string($conn, $_POST['Gender']);
    $Country = mysqli_real_escape_string($conn, $_POST['country']);
    $region = mysqli_real_escape_string($conn, $_POST['region']);
    $District = mysqli_real_escape_string($conn, $_POST['District']);
    $Ward = mysqli_real_escape_string($conn, $_POST['Ward']);
    $Leader = mysqli_real_escape_string($conn, $_POST['Leader']);
    $Tribe = mysqli_real_escape_string($conn, $_POST['Tribe']);
    $Guarantor_Name = mysqli_real_escape_string($conn, $_POST['Guarantor_Name']);
    $Member_Number = mysqli_real_escape_string($conn, $_POST['Member_Number']);
    echo $Member_Card_Expire_Date = mysqli_real_escape_string($conn, $_POST['Member_Card_Expire_Date']);
    $Phone_Number = mysqli_real_escape_string($conn, $_POST['Phone_Number']);
    $Email = mysqli_real_escape_string($conn, $_POST['Email']);
    $Occupation = mysqli_real_escape_string($conn, $_POST['Occupation']);
    $Employee_Vote_Number = mysqli_real_escape_string($conn, $_POST['Employee_Vote_Number']);
    $Emergence_Contact_Name = mysqli_real_escape_string($conn, $_POST['Emergence_Contact_Name']);
    $Emergence_Contact_Number = mysqli_real_escape_string($conn, $_POST['Emergence_Contact_Number']); //
    $Relationship = mysqli_real_escape_string($conn, $_POST['Relationship']); //
    $Company = mysqli_real_escape_string($conn, $_POST['Company']);
    $denomination = mysqli_real_escape_string($conn, $_POST['denomination']);
    $religion = mysqli_real_escape_string($conn, $_POST['religion']);
    $village = mysqli_real_escape_string($conn, $_POST['village']);
    $finger_print_details = mysqli_real_escape_string($conn, $_POST['finger_print_details']);
    $ten_cell_leader_name = mysqli_real_escape_string($conn, $_POST['ten_cell_leader_name']);


    if ($religion == "Not Applicable") {
        $religion = "";
        $Religion_ID = "";
    } else {
        $Religion_ID = ",Religion_ID";
        $religion = ",'$religion'";
    }

    if ($denomination == "Not Applicable") {
        $denomination = "";
        $Denomination_ID = "";
    } else {
        $denomination = ",'$denomination'";
        $Denomination_ID = ",Denomination_ID";
    }
    $from_search_military_info = mysqli_real_escape_string($conn, $_POST['from_search_military_info']);

    $patient_type = mysqli_real_escape_string($conn, $_POST['patient_type']);

    $service_number_d_m = "";

    $military_rank = mysqli_real_escape_string($conn, $_POST['military_rank']);
    $active_status = mysqli_real_escape_string($conn, $_POST['active_status']);
    $unit = mysqli_real_escape_string($conn, $_POST['unit']);
    $dependant_number = mysqli_real_escape_string($conn, $_POST['dependant_number']);
    $dependent_service_number = mysqli_real_escape_string($conn, $_POST['dependent_service_number']);
    $service_number = mysqli_real_escape_string($conn, $_POST['service_number']);

    if ($patient_type == "military") {
        $service_number_d_m = $service_number;
    } else {
        $service_number_d_m = $dependent_service_number;
    }

    // if (isset($_FILES['Patient_Picture']) && !is_null($_FILES['Patient_Picture']['name'])) {
    //     $Patient_Picture = md5($_FILES['Patient_Picture']['name'] . date('Ymdhms')) . ".jpg";
    //     $Patient_Picture_temp = $_FILES['Patient_Picture']['tmp_name'];
    //     $Patient_Picture_path = "./patientImages/" . $Patient_Picture;
    // } else {
    //     $Patient_Picture = 'default.png';
    // }
    $nationalId = mysqli_real_escape_string($conn, $_POST['national_id']);

    if (isset($_SESSION['systeminfo']['Registration_Mode']) && strtolower($_SESSION['systeminfo']['Registration_Mode']) <> 'receiving patient names together') {
        $Patient_First_Name = mysqli_real_escape_string($conn, preg_replace('/\s+/', ' ', $_POST['Patient_Name']));
        $Patient_Middle_Name = mysqli_real_escape_string($conn, preg_replace('/\s+/', '', $_POST['Patient_Middle_Name']));
        $Patient_Last_Name = mysqli_real_escape_string($conn, preg_replace('/\s+/', '', $_POST['Patient_Last_Name']));
        if (strtolower($Guarantor_Name) == 'nhif') {
            //$Patient_Name = $Patient_First_Name;
            $Patient_Name = str_replace("'", "&#39;", $Patient_First_Name);
        } else {
            //$Patient_Name = $Patient_First_Name . ' ' . $Patient_Middle_Name . ' ' . $Patient_Last_Name;
            $Patient_Name = str_replace("'", "&#39;", $Patient_First_Name . ' ' . $Patient_Middle_Name . ' ' . $Patient_Last_Name);
        }
    } else {
        // $Patient_Name = mysqli_real_escape_string($conn,preg_replace('/\s+/', ' ', $_POST['Patient_Name']));
        $Patient_Name = mysqli_real_escape_string($conn, str_replace("'", "&#39;", preg_replace('/\s+/', ' ', $_POST['Patient_Name'])));
    }

    if (isset($_POST['Diseased'])) {
        $Diseased = 'yes';
    } else {
        $Diseased = 'no';
    }
    $Registration_Type = $_POST['Registration_Type'];

    //check if there is another patient based on entered member number
    $select_Membership_Id_Number_Status = mysqli_query($conn, "select Membership_Id_Number_Status from tbl_sponsor where Guarantor_Name = '$Guarantor_Name'") or die(mysqli_error($conn));
    $row = mysqli_fetch_assoc($select_Membership_Id_Number_Status);
    $Membership_Id_Number_Status = $row['Membership_Id_Number_Status'];

    if (strtolower($Membership_Id_Number_Status) == 'mandatory') {
        $check_info = mysqli_query($conn, "select * from tbl_patient_registration
					    where sponsor_id = (select Sponsor_ID from tbl_sponsor where Guarantor_Name = '$Guarantor_Name' limit 1) and
						Member_Number = '$Member_Number' limit 1") or die(mysqli_error($conn));

        $num_info = mysqli_num_rows($check_info);
        if ($num_info > 0) {
            while ($row = mysqli_fetch_array($check_info)) {
                $Temp_Patient_Name = $row['Patient_Name'];
                $Temp_Date_Of_Birth = $row['Date_Of_Birth'];
                $Temp_Gender = $row['Gender'];
                $Temp_Emergence_Contact_Name = $row['Emergence_Contact_Name'];
                $Registration_ID = $row['Registration_ID'];
            }
        } else {
            $Temp_Patient_Name = '';
            $Temp_Date_Of_Birth = '';
            $Temp_Gender = '';
            $Temp_Emergence_Contact_Name = '';
        }
    } else {
        $num_info = 0;
    }

    if ($num_info > 0) {
    ?>

        <script>
            var Temp_Patient_Name = '<?php echo htmlspecialchars($Temp_Patient_Name, ENT_QUOTES) ?>'
            var Patient_Name = '<?php echo htmlspecialchars($Patient_Name, ENT_QUOTES) ?>';
            var Temp_Date_Of_Birth = '<?php echo $Temp_Date_Of_Birth; ?>';
            var Temp_Gender = '<?php echo $Temp_Gender; ?>';
            var Temp_Entered_Patient_Name = '<?php echo $Patient_Name; ?>';
            var Old_Registration_Number = '<?php echo $Old_Registration_Number; ?>';
            var Patient_Title = '<?php echo $Patient_Title; ?>';
            var Date_Of_Birth = '<?php echo $Date_Of_Birth; ?>';
            var Gender = '<?php echo $Gender; ?>';
            var Country = '<?php echo $Country; ?>';
            var region = '<?php echo $region; ?>';
            var District = '<?php echo $District; ?>';
            var Ward = '<?php echo $Ward; ?>';
            var Leader = '<?php echo $Leader; ?>';
            var Tribe = '<?php echo $Tribe; ?>';
            var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
            var Member_Number = '<?php echo $Member_Number; ?>';
            var Member_Card_Expire_Date = '<?php echo $Member_Card_Expire_Date; ?>';
            var Phone_Number = '<?php echo $Phone_Number; ?>';
            var Email = '<?php echo $Email; ?>';
            var Occupation = '<?php echo $Occupation; ?>';
            var Employee_Vote_Number = '<?php echo $Employee_Vote_Number; ?>';
            var Emergence_Contact_Name = '<?php echo $Emergence_Contact_Name; ?>';
            var Emergence_Contact_Number = '<?php echo $Emergence_Contact_Number; ?>';
            var Company = '<?php echo $Company; ?>';
            var Registration_ID = '<?= $Registration_ID ?>';

            alert("SORRY, PROCESS FAIL!!!\n\nMay be This Patient Already Registrered\n\n\nThis MEMBER NUMBER already used by \n Patient Number : " + Registration_ID + "\nDate of birth : " + Temp_Date_Of_Birth + "\nGender : " + Temp_Gender + "\n\nIf The Patient number is Exactly (" + Registration_ID + ") Select him/her from the registred list\nTo proceed with services.\nOthewise enter the member number correctly");

            document.getElementById("Patient_Name").value = Patient_Name;
            document.getElementById("date2").value = Date_Of_Birth;
            document.getElementById("Gender").value = Gender;
            document.getElementById("Country").value = Country;
            document.getElementById("Ward").value = Ward;
            document.getElementById("ten_cell_leader_name").value = Leader;
            document.getElementById("Tribe").value = Tribe;
            document.getElementById("Guarantor_Name").value = Guarantor_Name;
            document.getElementById("Member_Number").value = Member_Number;
            document.getElementById("date").value = Member_Card_Expire_Date;
            document.getElementById("Phone_Number").value = Phone_Number;
            document.getElementById("Email").value = Email;
            document.getElementById("Occupation").value = Occupation;
            document.getElementById("Employee_Vote_Number").value = Employee_Vote_Number;
            document.getElementById("Emergence_Contact_Name").value = Emergence_Contact_Name;
            document.getElementById("Emergence_Contact_Number").value = Emergence_Contact_Number;
            document.getElementById("Company").value = Company;
            document.getElementById("Old_Registration_Number").value = Old_Registration_Number;
            document.getElementById("Patient_Title").value = Patient_Title;
            document.getElementById("Member_Number").focus();
            document.getElementById('eVerify_btn').style.visibility = "";
        </script>
        <?php
    } else {

        if (isset($_SESSION['userinfo'])) {
            if (isset($_SESSION['userinfo']['Employee_ID'])) {
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
            } else {
                $Employee_ID = 0;
            }
        }
        //die($Ward.' test');
        //select patient registration date and time
        $data = mysqli_query($conn, "select now() as Registration_Date_And_Time");
        while ($row = mysqli_fetch_array($data)) {
            $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
        }

        // Restrict if patients is already registered *************************************************************************
        $select_patient = mysqli_query($conn, "SELECT * FROM tbl_patient_registration
                                                         WHERE Patient_Name = '$Patient_Name' AND
                                                         Date_Of_Birth = '$Date_Of_Birth' AND
                                                         Gender = '$Gender' AND
                                                         Phone_Number = '$Phone_Number'");

        $found = mysqli_num_rows($select_patient);

        if ($found > 0) {
            $Registration_ID = mysqli_fetch_assoc($select_patient)['Registration_ID'];
            echo '<script>
                               alert("Sorry This Patient is Already Registered With Patient Number ' . $Registration_ID . ',Please Try Another One.");
                              </script>';

            exit;
        }
        // ********************************************************************************************************************


        /*
       		$date = date("Y-m-d");
			mysqli_query($conn,"INSERT INTO tbl_ten_cell_leader(leader_name,village_id,date) VALUES('$ten_cell_leader_name',(SELECT village_id FROM tbl_village WHERE
			village_name = '$village'),'$date')");
			*/
        $leader_id = NULL;
        $select_ten_cell = mysqli_query($conn, "SELECT leader_id FROM tbl_ten_cell_leader WHERE village_id = '$village' ORDER BY leader_id DESC LIMIT 1") or die(mysqli_error($conn));
        if (mysqli_num_rows($select_ten_cell) > 0) {
            $leader_id = mysqli_fetch_assoc($select_ten_cell)['leader_id'];
        } else {
            $date = date("Y-m-d");
            if ($village != '') {
                mysqli_query($conn, "INSERT INTO tbl_ten_cell_leader(leader_name,village_id,date) VALUES('$ten_cell_leader_name','$village','$date')") or die(mysqli_error($conn));
                $select_ten_cell = mysqli_query($conn, "SELECT leader_id FROM tbl_ten_cell_leader WHERE village_id = '$village'");
                $leader_id = mysqli_fetch_assoc($select_ten_cell)['leader_id'];
            }
        }

        $Insert_Sql = "INSERT into tbl_patient_registration(
			Old_Registration_Number,Title,Patient_Name,
			    Date_Of_Birth,Gender,Country,Region,District,Ward,ward_id,Tribe,
				Sponsor_ID,
				    Member_Number,Member_Card_Expire_Date,
					Phone_Number,Email_Address,Occupation,
					    Employee_Vote_Number,Emergence_Contact_Name,
						Emergence_Contact_Number,Relationship,Company,
						    Employee_ID,Registration_Date_And_Time,District_ID,Registration_Date,Diseased,national_Id,Patient_Picture$Religion_ID$Denomination_ID,village,patient_type,`rank`,`Status`,service_no,dependancy_id,dependecny_service_no,military_unit,leader_id,ten_cell_leader_name)

			values('$Old_Registration_Number','$Patient_Title','$Patient_Name',
			'$Date_Of_Birth',
			    '$Gender','$Country','$region',(SELECT District_Name FROM tbl_district WHERE District_ID= '$District' LIMIT 1 ),(SELECT Ward_Name FROM tbl_ward WHERE Ward_ID= '$Ward' LIMIT 1 ),'$Ward','$Tribe',
			    (select Sponsor_ID from tbl_sponsor where Guarantor_Name = '$Guarantor_Name'),
			    '$Member_Number',DATE('$Member_Card_Expire_Date'),
				'$Phone_Number','$Email','$Occupation',
				'$Employee_Vote_Number','$Emergence_Contact_Name',
				    '$Emergence_Contact_Number','$Relationship','$Company',
				    '$Employee_ID','$Registration_Date_And_Time','$District',(select now()),'$Diseased','$nationalId','$Patient_Picture'$religion$denomination,'$village','$patient_type','$military_rank','$active_status','$service_number','$dependant_number','$dependent_service_number','$unit','$Leader',(select leader_name from tbl_leaders where leader_ID = '$Leader' LIMIT 1))";

        // die($Insert_Sql);
        // $error = mysqli_query($conn,$Insert_Sql);

        $sql_rslt = mysqli_query($conn, $Insert_Sql) or die(mysqli_error($conn));
        if (!$sql_rslt) {
            $error = '1062yes';
            if (mysqli_errno($conn) . "yes" == $error) {
                $controlforminput = 'not valid';
            } else {
                die(mysqli_error($conn) . '===>moja');
            }
        } else {
            // die("wiiiiii");
            // if ($Patient_Picture != '') {
            //     //move_uploaded_file($Patient_Picture_temp, $Patient_Picture_path);
            //     $sql_select_image_quality_result = mysqli_query($conn, "SELECT image_quality_value FROM tbl_image_quality LIMIT 1") or die(mysqli_error($conn));
            //     if (mysqli_num_rows($sql_select_image_quality_result) > 0) {
            //         $image_quality = mysqli_fetch_assoc($sql_select_image_quality_result)['image_quality_value'];
            //     } else {
            //         $image_quality = 10;
            //     }
            //     compress_image($Patient_Picture_temp, $Patient_Picture_path, $image_quality);
            // }

            $selectThisRecord = mysqli_query($conn, "SELECT Registration_ID  from tbl_patient_registration where
			Patient_Name = '$Patient_Name' and
			    Emergence_Contact_Name = '$Emergence_Contact_Name' and
			    Registration_Date_And_Time = '$Registration_Date_And_Time' and
			    Date_Of_Birth = '$Date_Of_Birth'") or die(mysqli_error($conn));

            while ($row = mysqli_fetch_array($selectThisRecord)) {
                $Registration_ID = $row['Registration_ID'];
            }

            if (!empty($finger_print_details)) {
                mysqli_query($conn, "INSERT INTO
			tbl_finger_print_details(Registration_ID,finger_data,capture_location,Employee)
			VALUES($Registration_ID,'$finger_print_details',(SELECT Hospital_Name FROM tbl_system_configuration),$Employee_ID)");
            }
            if ($from_search_military_info == "from_search_military_info") {
                echo "<script type='text/javascript'>
                        alert('MILITARY PERSONEL INFORMATION ADDED SUCCESSFULLY');
                        document.location ='registerpatient.php?RegisterPatient=RegisterPatientThisPage'
                        </script>";
            } else {
                if ($Registration_Type == 'Pre_Paid') {
                    echo "<script type='text/javascript'>
                        alert('PATIENT ADDED SUCCESSFULLY');
                        document.location = './postpaidform.php?Registration_ID=" . $Registration_ID . "&Section=" . $Section . "&Section=Reception&PostPaid=PostPaidThisForm';
                        </script>";
                } else if (isset($_GET['from_directdepartmental'])) {
                    echo "<script type='text/javascript'>
                                alert('PATIENT ADDED SUCCESSFULLY');
                                document.location = './departmentalothersworkspage.php?Registration_ID=" . $Registration_ID . "&DepartmentalPatientBilling=DepartmentalPatientBillingThisForm';
                                </script>";
                } else {
                    echo "<script type='text/javascript'>
                                alert('PATIENT ADDED SUCCESSFULLY');
                                document.location = './visitorform.php?Registration_ID=" . $Registration_ID . "&VisitorForm=VisitorFormThisPage';
                                </script>";
                }
            }
        }
    }
}
?>
<div id="militsry_personnel_information">

</div>
<div id="militsry_personnel_registration">

</div>
<!-- add vllage -->
<div id="addvillage">
    <div style="margin-top:20px;">
        <input type="text" name="village_name" style="padding-left:10px;" placeholder="enter village name" id="village" />
    </div>
    <div style="text-align:center;margin-top:15px;">
        <input type="button" class="art-button" id="save-village" value="Save" />
    </div>
</div>

<div id="addward">
    <div style="margin-top:20px;">
        <input type="text" name="ward_name" style="padding-left:10px;" placeholder="Enter Ward Name" id="new_ward_name" />
    </div>
    <div style="text-align:center;margin-top:15px;">
        <input type="button" class="art-button" id="save_new_village" value="Save" />
    </div>
</div>

<div id="addleader">
    <div style="margin-top:20px;">
        <input type="text" name="leader_name" style="padding-left:10px;" placeholder="Enter Leader Name" id="new_leader_name" />
    </div>
    <div style="text-align:center;margin-top:15px;">
        <input type="button" class="art-button" id="save_new_leader" value="Save" />
    </div>
</div>


<?php
include "finger_print.php";
?>
<script>
    function open_militaary_personnel_info() {

        var dependent_service_number = $("#dependent_service_number").val();
        if (dependent_service_number == "") {
            $("#dependent_service_number").css("border", "2px solid red");
            $("#dependent_service_number").attr("placeholder", "Please Enter Service Number")
            $("#dependent_service_number").focus()
            exit;
        } else {
            $("#dependent_service_number").css("border", "");
        }
        $.ajax({
            type: 'GET',
            url: 'search_military_personnel_infomation.php',
            data: {
                dependent_service_number: dependent_service_number
            },
            success: function(data) {
                $("#militsry_personnel_information").html(data)
                $("#militsry_personnel_information").dialog("open")
            }
        });
    }
</script>
<script>
    $(document).ready(function() { //

        $("#addleader").dialog({
            autoOpen: false,
            width: '45%',
            height: 310,
            title: 'ADD NEW LEADER',
            modal: true,
            position: 'middle'
        })
        $("#addvillage").dialog({
            autoOpen: false,
            width: '45%',
            height: 310,
            title: 'ADD VILLAGE',
            modal: true,
            position: 'middle'
        })
        $("#addward").dialog({
            autoOpen: false,
            width: '45%',
            height: 310,
            title: 'ADD NEW WARD',
            modal: true,
            position: 'middle'
        })
        $("#militsry_personnel_information").dialog({
            autoOpen: false,
            width: '45%',
            height: 310,
            title: 'MILITARY PERSONEL INFORMATION',
            modal: true,
            position: 'middle'
        });
        $("#militsry_personnel_registration").dialog({
            autoOpen: false,
            width: '85%',
            height: 600,
            title: 'MILITARY PERSONEL REGISTRATION',
            modal: true,
            position: 'middle'
        });
    });
</script>
<script>
    $(".patient_type_radio").click(function() {
        var patient_type = $('input[name="patient_type"]:checked').val();
        if (patient_type == "military_dependant") {
            $("#Guarantor_Name option[value='TPDF']").prop('selected', true);
            $("#dependant_row_data").show();
        } else {
            $("#Guarantor_Name option[value='TPDF']").prop('selected', false);
            $("#dependant_row_data").hide();
        }

        if (patient_type == "military") {
            $("#Guarantor_Name option[value='TPDF']").prop('selected', true);
            $("#military_row_data").show();
        } else {
            $("#military_row_data").hide();
        }

    });
</script>
<script src="js/functions.js"></script>
<script src="pikaday.js"></script>
<script>
    var picker = new Pikaday({
        field: document.getElementById('xy'),
        firstDay: 1,
        minDate: new Date('1910-01-01'),
        maxDate: new Date('2020-12-31'),
        yearRange: [1910, 2020]
    });
</script>

<script>
    function addCode() {
        document.getElementById('Phone_Number').value = '0';
    }

    function addCode2() {
        document.getElementById('Emergence_Contact_Number').value = '0';
    }
</script>
<script>
    function confirm_message(state) {
        if (state.checked) {
            if (confirm("Patient is registered as diseased. Do you want to continue?")) {
                state.checked = true;
            } else {
                state.checked = false;
            }
        }
    }
</script>

<script type="text/javascript">
    function Modify_Patient_Name() {
        var Guarantor_Name = document.getElementById("Guarantor_Name").value;
        <?php if (isset($_SESSION['systeminfo']['Registration_Mode']) && strtolower($_SESSION['systeminfo']['Registration_Mode']) <> 'receiving patient names together') { ?>
            if (Guarantor_Name == 'NHIF') {
                document.getElementById("F_Area").innerHTML = 'Patient Name';
                document.getElementById("M_Area").innerHTML = '';
                document.getElementById("L_Area").innerHTML = '';
                document.getElementById("Patient_Middle_Name").removeAttribute("required");
                document.getElementById("Patient_Last_Name").removeAttribute("required");
                //document.getElementById("Patient_Middle_Name").value = '';
                //document.getElementById("Patient_Last_Name").value = '';
                document.getElementById('Patient_Middle_Name').style.visibility = "hidden";
                document.getElementById('Patient_Last_Name').style.visibility = "hidden";
            } else {
                document.getElementById("F_Area").innerHTML = 'Patient First Name';
                document.getElementById("M_Area").innerHTML = 'Patient Middle Name';
                document.getElementById("L_Area").innerHTML = 'Patient Last Name';
                document.getElementById("Patient_Middle_Name").value = '';
                document.getElementById("Patient_Last_Name").value = '';
                document.getElementById('Patient_Middle_Name').style.visibility = "visible";
                document.getElementById('Patient_Last_Name').style.visibility = "visible";
                document.getElementById("Patient_Last_Name").setAttribute('required', 'required');
            }
        <?php } ?>
    }
</script>

<script>
    function Validate_Date() {
        var Today = new Date(); //current date
        var Date_Of_Birth = new Date(document.getElementById("date2").value);
        var Initial_Date = new Date("1900, 01, 01");
        // var patient_type= $('input.patient_type:checked').val();
        var patient_type = $('input[name="patient_type"]:checked').val();
        if (patient_type == "") {
            alert("SELECT PATIENT TYPE");
            $(".patient_type_radio").css("border", "3px solid red")
            return false;
        }

        if (patient_type == "military") {
            var military_rank = $("#military_rank").val();
            var military_unit_name = $("#military_unit_name").val();

            if (military_rank == "") {

                $("#military_rank").css("border", "2px solid red");
            } else {
                $("#military_rank").css("border", "");
            }
            if (military_unit_name == "") {

                $("#military_unit_name").css("border", "2px solid red");
            } else {
                $("#military_unit_name").css("border", "");
            }
        }
        if (Date_Of_Birth < Initial_Date || Date_Of_Birth > Today) {
            alert("Invalid Date Of Birth");
            document.getElementById("date2").value = '';
            document.getElementById("datetime").value = '';
        }
    }

    function verify_finger_print() {
        alert("chek for the finger print");
    }

    function finger_print_status() {
        var Finger_Print_Mandatory = '<?= $Finger_Print_Mandatory; ?>';
        var Show_Finger_Print = '<?= $finger_print_config; ?>';
        alert
        if (Finger_Print_Mandatory == 'No' && Show_Finger_Print == 'Yes') {
            alert('am here ');
            return false;
        } else {
            alert('Take Finger Print First !!');
            return false;
        }
    }
</script>
<script>
    function calculatedate(age) {
        $.ajax({
            type: 'GET',
            url: 'getinfos.php',
            data: 'getage=' + age,
            cache: false,
            beforeSend: function(xhr) {
                $("#date2").attr('readonly', true);
            },
            success: function(html) {
                $("#date2").val(html);
            },
            complete: function(jqXHR, textStatus) {
                $("#date2").attr('readonly', false);
            },
            error: function(html) {

            }
        });
    }
</script>
<script>
    // $("#Tribe").select2();
    function getDenominations(Religion_ID) {
        $.ajax({
            type: 'GET',
            url: 'denominationOptions.php',
            data: {
                Religion_ID: Religion_ID
            },
            cache: false,
            beforeSend: function() {
                //                $("#date2").attr('readonly', true);
            },
            success: function(html) {
                $("#denomination").html(html);
            },
            complete: function() {
                //                $("#date2").attr('readonly', false);
            },
            error: function(html) {

            }
        });
    }


    $("#add-ward").click(function(e) {
        e.preventDefault();
        $("#addward").dialog('open');

    })

    $("#add-village").click(function() {
        $("#addvillage").dialog('open');
    })

    $("#add_ward").click(function() {
        $("#addward").dialog('open');
    })

    $("#add_leader").click(function() {
        $("#addleader").dialog('open');
    })


    $("select > option:first").hide();

    $("#save-village").click(function() {
        var village = $("#village").val();
        var Ward_ID = $("#select-ward").val();
        if (Ward_ID == "") {
            alert("SELECT WARD");
            exit;
        }
        if (village.trim() !== '') {
            village = village.replace(/ +/, " ");
            $.ajax({
                type: 'GET',
                url: 'addvillage.php',
                data: {
                    villageName: village,
                    Ward_ID: Ward_ID
                },
                success: function(data) {
                    var feedback = JSON.parse(data);
                    if (feedback.result === 'exist') {
                        alert("Village Already Exist");
                    } else {
                        console.log(data);
                        console.log(feedback.village_id)

                        $("#select-village").append("<option value='" + feedback.village_name + "'>" + feedback.village_name + "</option>");
                        if (feedback.village_id !== "") {
                            alert("village added successfully")
                            $("#ten_cell_leader_name").append("<option selected value=''>Add Leader</option>");
                        }
                        $("#village").val("")
                    }
                }
            });
        } else {
            alert("WRITE THE VILLAGE NAME");
        }
    });

    $("#save_new_leader").click(function() {
        var village = $("#select-village").val();
        var leader = $("#new_leader_name").val();
        if (village !== '') {
            if (leader.trim() !== '') {
                leader = leader.replace(/ +/, " ");
                $.ajax({
                    type: 'POST',
                    url: 'addLeader.php',
                    data: {
                        village: village,
                        leader: leader
                    },
                    success: function(data) {
                        console.log(data)
                        var feedback = JSON.parse(data);
                        if (feedback.result === 'exist') {
                            alert("Leader Already Exist");
                        } else {
                            console.log(data);
                            console.log(feedback.leader_ID)
                            $("#ten_cell_leader_name").append("<option value='" + feedback.leader_ID + "'>" + feedback.leader_name + "</option>");
                            if (feedback.result == "ok") {
                                $("#addleader").dialog('close');
                                alert("Leader Added Successfully");
                            }
                            $("#new_leader_name").val("")
                        }
                    }
                });
            } else {
                alert("WRITE THE LEADER NAME");
            }
        } else {
            alert("YOU MUST SPECIFY THE VILLAGE FIRST");
        }
    });

    $("#save_new_village").click(function() {
        var ward = $("#new_ward_name").val();
        var district = $("#District").val();

        if (district !== '') {
            if (ward.trim() !== '') {
                $.ajax({
                    type: 'POST',
                    url: 'addvillage.php',
                    data: {
                        wardName: ward,
                        district: district
                    },
                    success: function(data) {
                        console.log("===>" + data);
                        var feedback = JSON.parse(data);
                        console.log("---------->" + feedback.Ward_ID)

                        $("#select-ward").append("<option value='" + feedback.Ward_ID + "'>" + feedback.Ward_Name + "</option>");
                        if (feedback.result == "ok") {
                            $("#addward").dialog('close');
                            alert("Ward Added Successfully");
                        } else if (feedback.result == "Duplicate") {
                            alert("Ward Already Exist");
                        }
                        $("#new_ward_name").val("")

                    }
                });
            } else {
                alert("WRITE THE WARD NAME");
            }
        } else {
            alert("YOU MUST SPECIFY THE DISTRICT FIRST");
        }
    });
    $("#military_rank").select2();
    $("#military_unit_name").select2();
    // $("#select-village").select2();
    //$("#select-ward").select2();
    // $("#select-ward").on("change",function(){
    // 	if($("#select-ward").val() == 'others'){
    // 		$("#add_ward").show();
    // 	}else{
    // 		$("#add_ward").hide();
    // 	}
    // });

    function getWards() {
        var District_ID = document.getElementById("District").value;
        $.ajax({
            url: 'GetWards.php',
            type: 'get',
            data: {
                From: 'Get_Wards',
                District_ID: District_ID
            },
            success: function(results) {
                $("#select-ward").html(results);
                $("#select-village").val("");
                $('#ten_cell_leader_name').val("");
                $("#ten_cell_leader_name").html("<option selected value=''>Select Leader</option>");
                $("#select-village").html("<option>Select Village</option>");
            }
        });
    }

    function getVillage_Street() {
        var Ward_ID = $("#select-ward").val();
        $.ajax({
            url: 'getvillage.php',
            type: 'get',
            data: {
                From: 'Get_Village',
                Ward_ID: Ward_ID
            },
            success: function(results) {
                $("#select-village").html(results);
                $('#ten_cell_leader_name').val("");
                $("#ten_cell_leader_name").html("<option selected value=''>Select Leader</option>");
            }
        });
    }

    function getVillage_leader() {
        var village = $("#select-village").val();
        $.ajax({
            url: 'getLeader.php',
            type: 'get',
            data: {
                From: 'Get_Village',
                village: village
            },
            success: function(results) {
                $("#ten_cell_leader_name").html(results);
                // document.getElementById("ten_cell_leader_name").innerHTML = results;
            }
        });
    }
</script>
<script type="text/javascript" src="js/finger_print.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script>
    $(document).ready(function() {
        datetime = $("#datetime").val();
        $("#ten_cell_leader_name").select2();
        $("#Tribe").select2();
        $("#military_rank").select2();
        $("#military_unit_name").select2();
        $("#select-village").select2();
        $("#select-ward").select2();
        // calculatedate(datetime);
    });
</script>
<!-- Configure a few settings and attach camera -->
<script language="JavaScript">
    Webcam.set({
        width: 230,
        height: 250,
        image_format: 'jpeg',
        jpeg_quality: 90
    });

    Webcam.attach('#my_camera');

    function take_snapshot() {
        Webcam.snap(function(data_uri) {
            $(".image-tag").val(data_uri);
            document.getElementById('results').innerHTML = '<img src="' + data_uri + '"/>';
        });
    }
</script>
<!-- script for camera ends here -->
<?php
include("./includes/footer.php");

?>
