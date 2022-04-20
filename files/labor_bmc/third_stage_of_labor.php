<?php

include("./includes/header.php");

include("./includes/check_session.php");

include("./handlers/patient.php");

include("./handlers/labor.php");

list($Sponsor_ID, $Member_Number, $Patient_Name, $Registration_ID, $Gender, $Guarantor_Name, $age) = get_patient_info($patient_id, $conn);

list($placenta_method_of_delivery, $date_time, $duration, $placenta_weight, $stage_of_placenta, $colour, $cord, $membranes, $disposal, $state_of_cervix, $tear, $repaired_with_sutures, $total_blood_loss, $temperature, $pulse, $resp, $bp, $lochia, $state_of_uterus, $remarks) = get_third_stage($conn, $patient_id, $admision_id);

?>

<a href="preview_third_stage.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id; ?>" target="_blank" class="art-button-green">PREVIEW RECORDS</a>

<a href="labor_dashboard.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id; ?>" class="art-button-green">BACK</a>

<fieldset>

    <legend style="font-weight:bold" align=center>

        <div style="height:34px;margin:0px;padding:0px;font-weight:bold">

            <p style="margin:0px;padding:0px;" align=center>THIRD STATE OF LABOUR</p>

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
            <td>METHOD OF DELIVERY OF THE PLACENTA</td>
            <td>
                <select name="placenta_method_of_delivery" id="placenta_method_of_delivery" style="width: 100%; padding: 6px;">
                    <option value="<?php if ($placenta_method_of_delivery) {echo $placenta_method_of_delivery;} else { echo ""; } ?>"><?php if ($placenta_method_of_delivery) {echo $placenta_method_of_delivery;} else { ?>Select<?php } ?></option>
                    <option value="CCT">CCT</option>
                    <option value="Manual">Manual</option>
                </select>
            </td>
            <td>DATE AND TIME</td>
            <td><input type="text" name="date_time" id="date_time" value="<?= $date_time; ?>" style="padding: 6px;"></td>
            <td>DURATION</td>
            <td><input type="text" name="duration" id="duration" value="<?= $duration; ?>" style="padding: 6px;"></td>
            <td colspan="2">PLACENTA WEIGHT</td>
            <td colspan="2">
                <input type="text" name="placenta_weight" id="placenta_weight" value="<?= $placenta_weight; ?>" style="padding: 6px;" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                <span style="margin-left: -30px;">Kg</span>
            </td>
        </tr>

        <tr>
            <td>STATE OF THE PLACENTA</td>
            <td>
                <select name="stage_of_placenta" id="stage_of_placenta" style="width: 100%; padding: 6px;">
                    <option value="<?php if ($stage_of_placenta) {echo $stage_of_placenta;} else { echo ""; } ?>"><?php if ($stage_of_placenta) {echo $stage_of_placenta;} else { ?>Select<?php } ?></option>
                    <option value="Complete">Complete</option>
                    <option value="Not Complete">Not Complete</option>
                </select>
            </td>
            <td>COLOUR</td>
            <td><input type="text" name="colour" id="colour" value="<?= $colour; ?>" style="padding: 6px;"></td>
            <td>CORD</td>
            <td>
                <select name="cord" id="cord" style="width: 100%; padding: 6px;">
                    <option value="<?php if ($cord) {echo $cord;} else { echo ""; } ?>"><?php if ($cord) {echo $cord;} else { ?>Select<?php } ?></option>
                    <option value="Normal">Normal</option>
                    <option value="Abnormal">Abnormal</option>
                </select>
            </td>
            <td colspan="2">MEMBRANES</td>
            <td colspan="2">
                <select name="membranes" id="membranes" style="width: 100%; padding: 6px;">
                    <option value="<?php if ($membranes) {echo $membranes;} else { echo ""; } ?>"><?php if ($membranes) {echo $membranes;} else { ?>Select<?php } ?></option>
                    <option value="Complete">Complete</option>
                    <option value="Rugged">Rugged</option>
                    <option value="InComplete">InComplete</option>
                </select>
            </td>
        </tr>

        <tr>
            <td>DISPOSAL</td>
            <td>
                <select name="disposal" id="disposal" style="width: 100%; padding: 6px;">
                    <option value="<?php if ($disposal) {echo $disposal;} else { echo ""; } ?>"><?php if ($disposal) {echo $disposal;} else { ?>Select<?php } ?></option>
                    <option value="Lab">Lab</option>
                    <option value="Incinerator">Incinerator</option>
                </select>
            </td>
            <td>STATE OF CERVIX</td>
            <td><input type="text" name="state_of_cervix" id="state_of_cervix" value="<?= $state_of_cervix; ?>" style="padding: 6px;"></td>
            <td>EPISIOTOMY / TEAR</td>
            <td><input type="text" name="tear" id="tear" value="<?= $tear; ?>" style="padding: 6px;"></td>
            <td>REPAIRED WITH SUTURES</td>
            <td><input type="text" name="repaired_with_sutures" id="repaired_with_sutures" value="<?= $repaired_with_sutures; ?>" style="padding: 6px;"></td>
            <td>TOTAL BLOOD LOSS</td>
            <td><input type="text" name="total_blood_loss" id="total_blood_loss" value="<?= $total_blood_loss; ?>" style="padding: 6px;"></td>
        </tr>

        <tr>
            <td style="text-align: center; background-color:#bdb5ac;" colspan="10"><b>POST DELIVERY OBSERVATIONS</b></td>
        </tr>

        <tr>
            <td>T</td>
            <td>
                <input type="text" name="temperature" id="temperature" value="<?= $temperature; ?>" style="padding: 6px;" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                <span style="margin-left: -30px;">&#176C</span>
            </td>
            <td>P</td>
            <td colspan="2">
                <input type="text" name="pulse" id="pulse" style="padding: 6px;" value="<?= $pulse; ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                <span style="margin-left: -40px;">bpm</span>
            </td>
            <td>R</td>
            <td>
                <input type="text" name="resp" id="resp" style="padding: 6px;" value="<?= $resp; ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                <span style="margin-left: -40px;">bpm</span>
            </td>
            <td>BP</td>
            <td colspan="2">
                <input type="text" name="bp" id="bp" value="<?= $bp; ?>" style="padding: 6px;">
                <span style="margin-left: -50px;">mmHg</span>
            </td>
        </tr>

    </table>

    <table class="table table-striped table-hover">

        <tr>
            <td>LOCHIA</td>
            <td colspan="4">
                <select name="lochia" id="lochia" style="width: 100%; padding: 6px;">
                    <option value="<?php if ($lochia) {echo $lochia;} else { echo ""; } ?>"><?php if ($lochia) {echo $lochia;} else { ?>Select<?php } ?></option>
                    <option value="RUBRA">RUBRA</option>
                    <option value="ALBA">ALBA</option>
                    <option value="CEROSA">CEROSA</option>
                </select>
            </td>
            <td>STATE OF UTERUS</td>
            <td colspan="4"><input type="text" name="state_of_uterus" id="state_of_uterus" value="<?= $state_of_uterus; ?>" style="padding: 6px;"></td>
        </tr>

        <tr>
            <td>REMARKS</td>
            <td colspan="9"><textarea name="remarks" id="remarks" cols="30" rows="2" style="padding: 6px;"><?= $remarks; ?></textarea></td>
        </tr>

    </table>

    <br>

    <center>
        <span>
            <input type="button" value="SAVE" id="save_third_stage_of_labour" class="art-button-green" style="width: 10%;">
        </span>
    </center>

</fieldset>

<link rel="stylesheet" type="text/css" href="../css/jquery.datetimepicker.css">

<link rel="stylesheet" href="../css/select2.min.css" media="screen">

<script type="text/javascript" src="../css/jquery.datetimepicker.js"></script>

<script src="../js/select2.min.js"></script>

<script type="text/javascript" src="./js/third_stage_of_labor.js"></script>

<?php include("../includes/footer.php"); ?>
