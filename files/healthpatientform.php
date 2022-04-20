<?php
include("./includes/header.php");
include("./includes/connection.php");
include("./patient.php");
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

if (isset($_GET['Registration_ID'])) {
    $patient_id = $_GET['Registration_ID'];
}

list($Sponsor_ID, $Member_Number, $Patient_Name, $Registration_ID, $Gender, $Guarantor_Name, $age) = get_patient_info($patient_id, $conn);
?>

<a href='unconsultedpatients.php?HealthUnConsulted=HealthUnConsultedThisPage' class='art-button-green'>BACK</a>

<fieldset>

    <legend style="font-weight:bold" align=center>

        <div style="height:34px;margin:0px;padding:0px;font-weight:bold">

            <p style="margin:0px;padding:0px;" align=center>DIRECT HEALTH WORKS PATIENT FORM</p>

        </div>

    </legend>

    <input type="hidden" name="patient_id" id="patient_id" value="<?= $patient_id; ?>">

    <table class="table table-striped table-hover">

        <tr>
            <td>Patient name</td>
            <td><input type="text" name="patient_name" id="patient_name" value="<?= $Patient_Name ?>" style="padding: 6px;" disabled></td>
            <td>REG NO</td>
            <td><input type="text" name="patient_name" id="patient_name" value="<?= $Registration_ID ?>" style="padding: 6px;" disabled></td>
            <td>Sponsor</td>
            <td><input type="text" name="patient_name" id="patient_name" value="<?= $Guarantor_Name ?>" style="padding: 6px;" disabled></td>
        </tr>

        <tr>
            <td>Gender</td>
            <td><input type="text" name="patient_name" id="patient_name" value="<?= $Gender ?>" style="padding: 6px;" disabled></td>
            <td>Age</td>
            <td><input type="text" name="patient_name" id="patient_name" value="<?= $age ?>" style="padding: 6px;" disabled></td>
            <td>Member no</td>
            <td><input type="text" name="patient_name" id="patient_name" value="<?= $Member_Number ?>" style="padding: 6px;" disabled></td>
        </tr>

    </table>

    <br>

    <table class="table table-striped table-hover">

        <tr>
            <td>TYPE OF VACCINE</td>
            <td>
                <select name="vaccine" id="vaccine" style="padding: 6px; width: 100%;">
                    <option value="">Select</option>
                    <option value="Yellow Fever">Yellow Fever</option>
                    <option value="Menengitis">Menengitis</option>
                    <option value="Hepatitis B">Hepatitis B</option>
                    <option value="Anti rebis">Anti rebis</option>
                    <option value="Tetenus">Tetenus (TT)</option>
                    <option value="Anti Venom">Anti Venom</option>
                    <option value="Other">Other</option>
                </select>
                <input type="text" name="reason_for_other" id="reason_for_other" style="margin-top: 5px; padding: 6px;">
            </td>
            <td>BATCH NO</td>
            <td><input type="text" name="batch_no" id="batch_no"></td>
            <td>DOSE NO</td>
            <td><input type="text" name="dose_no" id="dose_no"></td>
        </tr>

        <tr>
            <td>DATE OF VACCINATION</td>
            <td><input type="text" name="date_of_vaccine" id="date_of_vaccine"></td>
            <td>DATE OF RETURN</td>
            <td><input type="text" name="date_of_return" id="date_of_return"></td>
            <td style="text-align: center;"><input type="checkbox" name="health_education" id="health_education"></td>
            <td>Given health education</td>
        </tr>

    </table>

    <br>

    <center>
        <span>
            <input type="button" value="SAVE" id="save_vaccine_info" class="art-button-green" style="width: 10%;">
        </span>
    </center>

</fieldset>
<script>
    $(document).ready(function() {
        $("#reason_for_other").hide();

        $("#date_of_vaccine").datetimepicker({});

        $("#date_of_return").datetimepicker({});

        $("#vaccine").click(function() {

            if ($("#vaccine :selected").text() == "Other") {
                $("#reason_for_other").show();
            } else {
                $("#reason_for_other").hide();
            }

        });

        $("#save_vaccine_info").click(function(e) {

            e.preventDefault();

            addVaccineInfo();

        });
    });

    var health_education;

    /********** FUNCTION TO ADD VACCINE DOSE **************/
    function addVaccineInfo() {

        var registration_id = $("#patient_id").val();

        var vaccine = $("#vaccine").val();

        var reason_for_other = $("#reason_for_other").val();

        var batch_no = $("#batch_no").val();

        var dose_no = $("#dose_no").val();

        var date_of_vaccine = $("#date_of_vaccine").val();

        var date_of_return = $("#date_of_return").val();

        if ($("#health_education").is(":checked")) {

            health_education = "Given";

        } else {
            health_education = "Not Given";
        }

        $.ajax({
            type: "POST",
            url: "add_health_patient.php",
            data: {
                registration_id: registration_id,
                vaccine: vaccine,
                reason_for_other: reason_for_other,
                batch_no: batch_no,
                dose_no: dose_no,
                date_of_vaccine: date_of_vaccine,
                date_of_return: date_of_return,
                health_education: health_education
            },
            success: function(response) {
                jsonData = JSON.parse(response);

                alert(jsonData.display);
            }
        });
    }
    /********** END FUNCTION TO ADD VACCINE DOSE **************/
</script>

<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css">

<script type="text/javascript" src="css/jquery.datetimepicker.js"></script>

<?php include("./includes/footer.php"); ?>