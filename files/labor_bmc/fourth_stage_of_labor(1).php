<?php

include("./includes/header.php");

include("./includes/check_session.php");

include("./handlers/patient.php");

include("./handlers/labor.php");

list($Sponsor_ID, $Member_Number, $Patient_Name, $Registration_ID, $Gender, $Guarantor_Name, $age) = get_patient_info($patient_id, $conn);

list($temperature, $pr, $bp, $fundal_height, $state_of_cervix, $state_of_perinium, $blood_loss, $recommendations) = get_fourth_stage($conn, $patient_id, $admision_id);

?>

<a href="preview_fourth_stage.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id; ?>" target="_blank" class="art-button-green">PREVIEW RECORDS</a>

<a href="labor_dashboard.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id; ?>" class="art-button-green">BACK</a>

<fieldset>

    <legend style="font-weight:bold" align=center>

        <div style="height:34px;margin:0px;padding:0px;font-weight:bold">

            <p style="margin:0px;padding:0px;" align=center>FOURTH STATE OF LABOUR</p>

            <p style="color:yellow;margin:0px;padding:0px; ">

                <span><?= $Patient_Name; ?></span> |

                <span><?= $Gender; ?></span> |

                <span><?= $age; ?></span> |

                <span><?= $Guarantor_Name; ?></span>

            </p>

        </div>

    </legend>

    <input type="hidden" name="patient_id" id="patient_id" value="<?= $patient_id; ?>">

    <input type="hidden" name="admission_id" id="admission_id" value="<?= $admision_id; ?>">

    <input type="hidden" name="consultation_id" id="consultation_id" value="<?= $consultation_id; ?>">

    <table class="table table-striped table-hover">

        <tr>
            <td>TEMP</td>
            <td>
                <input type="text" name="temperature" id="temperature" value="<?= $temperature; ?>" style="padding: 6px;" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                <span style="margin-left: -30px;">&#176C</span>
            </td>
            <td>PR</td>
            <td colspan="2">
                <input type="text" name="pr" id="pr" value="<?= $pr; ?>" style="padding: 6px;" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                <span style="margin-left: -50px;">mmHg</span>
            </td>
            <td>BP</td>
            <td colspan="2">
                <input type="text" name="bp" id="bp" value="<?= $bp; ?>" style="padding: 6px;">
                <span style="margin-left: -50px;">mmHg</span>
            </td>
            <td>FUNDAL HEIGHT</td>
            <td>
                <input type="text" name="fundal_height" id="fundal_height" value="<?= $fundal_height; ?>" style="padding: 6px;" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                <span style="margin-left: -30px;">CM</span>
            </td>
        </tr>

        <tr>
            <td>STATE OF CERVIX</td>
            <td><input type="text" name="state_of_cervix" id="state_of_cervix" value="<?= $state_of_cervix; ?>" style="padding: 6px;"></td>
            <td colspan="2">STATE OF PERINIUM</td>
            <td colspan="2"><input type="text" name="state_of_perinium" id="state_of_perinium" value="<?= $state_of_perinium; ?>" style="padding: 6px;"></td>
            <td colspan="2">BLOOD LOSS</td>
            <td colspan="2"><input type="text" name="blood_loss" id="blood_loss" value="<?= $blood_loss; ?>" style="padding: 6px;"></td>
        </tr>

        <tr>
            <td>DOCTOR'S / MIDWIFE'S RECOMMENDATIONS</td>
            <td colspan="9"><textarea name="recommendations" id="recommendations" cols="30" rows="3" style="padding: 6px;"><?= $recommendations; ?></textarea></td>
        </tr>

    </table>

    <br>

    <center>
        <span>
            <input type="button" value="SAVE" id="save_fourth_stage_of_labour" class="art-button-green" style="width: 10%;">
        </span>
    </center>

</fieldset>

<link rel="stylesheet" type="text/css" href="../css/jquery.datetimepicker.css">

<link rel="stylesheet" href="../css/select2.min.css" media="screen">

<script type="text/javascript" src="../css/jquery.datetimepicker.js"></script>

<script src="../js/select2.min.js"></script>

<script type="text/javascript" src="./js/fourth_stage_of_labour.js"></script>

<?php include("../includes/footer.php"); ?>
