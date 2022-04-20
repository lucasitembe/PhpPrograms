<?php

include("./includes/header.php");

include("./includes/check_session.php");

include("./handlers/patient.php");

include("./handlers/employee.php");

include("./handlers/labor.php");

list($Sponsor_ID, $Member_Number, $Patient_Name, $Registration_ID, $Gender, $Guarantor_Name, $age) = get_patient_info($patient_id, $conn);

$Employee_array = get_employee_info($conn);

list($hospital_duration, $next_appointment, $peurperium, $uterus, $lochia, $midwife_name, $episiotomy, $breasts, $abdominal_scars, $general_condition, $anaemia, $breasts2, $cervix, $vagina, $episiotomy2, $stress_incontinence, $anus, $tenderness, $remarks, $temperature, $pulse, $bp, $baby_condition, $mother_remarks, $midwife_name2) = get_post_natal_record($conn, $patient_id, $admision_id);

?>

<a href="preview_post_natal_record.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id; ?>" target="_blank" class="art-button-green">PREVIOUS RECORDS</a>

<a href="labor_dashboard.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id; ?>" class="art-button-green">BACK</a>

<fieldset>

    <legend style="font-weight:bold" align=center>

        <div style="height:34px;margin:0px;padding:0px;font-weight:bold">

            <p style="margin:0px;padding:0px;" align=center>POST NATAL RECORD ON DISCHARGE</p>

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

    <fieldset>

        <legend align="center" style="font-weight:bold">DATE OF DISCHARGE</legend>

        <table class="table table-striped table-hover">

            <tr>
                <td>DURATION IN HOSPITAL</td>
                <td><input type="text" name="hospital_duration" id="hospital_duration" value="<?= $hospital_duration; ?>" style="padding: 6px;"></td>
                <td style="text-align: center; background-color:#bdb5ac;" colspan="6"><b>REPORT ON LACTATION</b></td>
            </tr>

            <tr>
                <td>DATE OF NEXT APPOINTMENT</td>
                <td><input type="text" name="next_appointment" id="next_appointment" value="<?= $next_appointment; ?>" style="padding: 6px;"></td>
                <td>PEURPERIUM</td>
                <td><input type="text" name="peurperium" id="peurperium" value="<?= $peurperium; ?>" style="padding: 6px;"></td>
                <td>UTERUS</td>
                <td><input type="text" name="uterus" id="uterus" value="<?= $uterus; ?>" style="padding: 6px;"></td>
                <td>LOCHIA</td>
                <td><input type="text" name="lochia" id="lochia" value="<?= $lochia; ?>" style="padding: 6px;"></td>
            </tr>

            <tr>
                <td>MIDWIFE'S NAME</td>
                <td>
                    <select type="text" id="midwife_name" name="midwife_name" style="padding: 6px; width: 100%;">
                        <option value="<?php if ($midwife_name) {echo $midwife_name;} else { echo ""; } ?>"><?php if ($midwife_name) {echo $midwife_name;} else { ?>Select<?php } ?></option>
                        <?php for ($i = 0; $i < count($Employee_array); $i++) { ?>
                            <option value='<?php echo $Employee_array[$i]['employee_name'] ?>'><?php echo $Employee_array[$i]['employee_name']; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td>EPISIOTOMY</td>
                <td><input type="text" name="episiotomy" id="episiotomy" value="<?= $episiotomy; ?>" style="padding: 6px;"></td>
                <td>BREASTS</td>
                <td><input type="text" name="breasts" id="breasts" value="<?= $breasts; ?>" style="padding: 6px;"></td>
                <td>ABDOMINAL SCARS</td>
                <td><input type="text" name="abdominal_scars" id="abdominal_scars" value="<?= $abdominal_scars; ?>" style="padding: 6px;"></td>
            </tr>

        </table>

    </fieldset>

    <br>

    <fieldset>

        <legend align="center" style="font-weight:bold">POST NATAL EXAMINATIONS BY MIDWIFE AFTER 6 WEEKS</legend>

        <table class="table table-striped table-hover">

            <tr>
                <td>GENERAL CONDITION</td>
                <td><input type="text" name="general_condition" id="general_condition" value="<?= $general_condition; ?>" style="padding: 6px;"></td>
                <td>ANAEMIA</td>
                <td><input type="text" name="anaemia" id="anaemia" value="<?= $anaemia; ?>" style="padding: 6px;"></td>
                <td>BREASTS</td>
                <td><input type="text" name="breasts2" id="breasts2" value="<?= $breasts2; ?>" style="padding: 6px;"></td>
            </tr>

            <tr>
                <td colspan="6" style="text-align: center; background-color:#bdb5ac;"><b>ABDOMINAL PALPATION</b></td>
            </tr>

            <tr>
                <td>CERVIX</td>
                <td><input type="text" name="cervix" id="cervix" value="<?= $cervix; ?>" style="padding: 6px;"></td>
                <td>VAGINA</td>
                <td><input type="text" name="vagina" id="vagina" value="<?= $vagina; ?>" style="padding: 6px;"></td>
                <td>EPISIOTOMY</td>
                <td><input type="text" name="episiotomy2" id="episiotomy2" value="<?= $episiotomy2; ?>" style="padding: 6px;"></td>
            </tr>

            <tr>
                <td>STRESS INCONTINENCE</td>
                <td><input type="text" name="stress_incontinence" id="stress_incontinence" value="<?= $stress_incontinence; ?>" style="padding: 6px;"></td>
                <td>ANUS</td>
                <td>
                    <select name="anus" id="anus" style="padding: 6px; width: 100%;">
                        <option value="<?php if ($anus) {echo $anus;} else { echo ""; } ?>"><?php if ($anus) {echo $anus;} else { ?>Select<?php } ?></option>
                        <option value="Hemorhoids">Hemorhoids</option>
                        <option value="Fissures">Fissures</option>
                    </select>
                </td>
                <td>TENDERNESS IN THE GROIN OF CALF MUSCLES</td>
                <td>
                    <select name="tenderness" id="tenderness" style="padding: 6px; width: 100%;">
                        <option value="<?php if ($tenderness) {echo $tenderness;} else { echo ""; } ?>"><?php if ($tenderness) {echo $tenderness;} else { ?>Select<?php } ?></option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>REMARKS</td>
                <td colspan="5"><textarea name="remarks" id="remarks" cols="30" rows="3" style="padding: 6px;"><?= $remarks; ?></textarea></td>
            </tr>

        </table>

    </fieldset>

    <br>

    <fieldset>

        <legend align="center" style="font-weight:bold">CLINICAL OBSERVATIONS</legend>

        <table class="table table-striped table-hover">

            <tr>
                <td>TEMP</td>
                <td>
                    <input type="text" name="temperature" id="temperature" value="<?= $temperature; ?>" style="padding: 6px;" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                    <span style="margin-left: -30px;">&#176C</span>
                </td>
                <td>PULSE</td>
                <td>
                    <input type="text" name="pulse" id="pulse" value="<?= $pulse; ?>" style="padding: 6px;" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                    <span style="margin-left: -40px;">bpm</span>
                </td>
                <td>BP</td>
                <td>
                    <input type="text" name="bp" id="bp" style="padding: 6px;" value="<?= $bp; ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                    <span style="margin-left: -50px;">mmHg</span>
                </td>
            </tr>

            <tr>
                <td>HOW IS HER BABY ?</td>
                <td><textarea name="baby_condition" id="baby_condition" cols="30" rows="3" style="padding: 6px;"><?= $baby_condition; ?></textarea></td>
                <td>GENERAL REMARKS ON THE MOTHER</td>
                <td colspan="3"><textarea name="mother_remarks" id="mother_remarks" cols="30" rows="3" style="padding: 6px;"><?= $mother_remarks; ?></textarea></td>
            </tr>

        </table>

    </fieldset>

    <br>

    <center>
        <span style="margin: 10px;">
            <b>MIDWIFE'S NAME : </b>
            <select type="text" id="midwife_name2" name="midwife_name2" style="padding: 6px; width: 30%;">
                <option value="<?php if ($midwife_name2) {echo $midwife_name2;} else { echo ""; } ?>"><?php if ($midwife_name2) {echo $midwife_name2;} else { ?>Select<?php } ?></option>
                <?php for ($i = 0; $i < count($Employee_array); $i++) { ?>
                    <option value='<?php echo $Employee_array[$i]['employee_name'] ?>'><?php echo $Employee_array[$i]['employee_name']; ?></option>
                <?php } ?>
            </select>
        </span>
    </center>

    <br>

    <center>
        <span>
            <input type="button" value="SAVE" id="save_post_natal_record" class="art-button-green" style="width: 10%;">
        </span>
    </center>

</fieldset>

<link rel="stylesheet" type="text/css" href="../css/jquery.datetimepicker.css">

<link rel="stylesheet" href="../css/select2.min.css" media="screen">

<script type="text/javascript" src="../css/jquery.datetimepicker.js"></script>

<script src="../js/select2.min.js"></script>

<script type="text/javascript" src="./js/post_natal_record.js"></script>

<?php include("../includes/footer.php"); ?>