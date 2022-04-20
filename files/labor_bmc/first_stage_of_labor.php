<?php

include("./includes/header.php");

include("./includes/check_session.php");

include("./handlers/patient.php");

include("./handlers/labor.php");

list($Sponsor_ID, $Member_Number, $Patient_Name, $Registration_ID, $Gender, $Guarantor_Name, $age) = get_patient_info($patient_id, $conn);

list($onset_labor, $admitted_at, $membrane_liquor, $rapture_date, $rapture_duration, $arm, $no_of_examinations, $abnormalities, $induction_of_labor, $induction_of_labor_reason, $first_stage_duration, $drugs_given, $remarks) = get_first_stage($conn, $patient_id, $admision_id);

?>

<a href="preview_first_stage.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id; ?>" target="_blank" class="art-button-green">PREVIEW RECORDS</a>

<a href="labor_dashboard.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id; ?>" class="art-button-green">BACK</a>

<fieldset>

    <legend style="font-weight:bold" align=center>

        <div style="height:34px;margin:0px;padding:0px;font-weight:bold">

            <p style="margin:0px;padding:0px;" align=center>FIRST STATE OF LABOUR</p>

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
            <td>ON SET OF LABOUR</td>
            <td><input type="text" name="onset_labor" id="onset_labor" value="<?= $onset_labor; ?>" style="padding: 6px;"></td>
            <td>ADMITTED AT</td>
            <td><input type="text" name="admitted_at" id="admitted_at" value="<?= $admitted_at; ?>" style="padding: 6px;"></td>
            <td>CERVIX DILATION</td>
            <td><input type="text" name="cervix_dilation" id="cervix_dilation"  style="padding: 6px;"></td>
            <td>MEMBRANE LIQUOR </td>
            <td>
                <select name="membrane_liquor" id="membrane_liquor" style="padding: 6px; width: 100%;">
                    <option value="<?php if ($membrane_liquor) {echo $membrane_liquor;} else { echo ""; } ?>"><?php if ($membrane_liquor) {echo $membrane_liquor;} else { ?>Select<?php } ?></option>
                    <option value="Rapture">Rapture</option>
                    <option value="Intact">Intact</option>
                </select>
                <input type="text" name="rapture_date" id="rapture_date" value="<?= $rapture_date; ?>" style="margin-top: 5px; padding: 6px;">
                <input type="text" name="rapture_duration" id="rapture_duration" value="<?= $rapture_duration; ?>" placeholder="Total time elapsed" style="margin-top: 5px; padding: 6px;">
            </td>
        </tr>

        <tr>
            <td>ARM</td>
            <td>
                <select name="arm" id="arm"  style="padding: 6px; width: 100%;">
                    <option value="<?php if ($arm) {echo $arm;} else { echo ""; } ?>"><?php if ($arm) {echo $arm;} else { ?>Select<?php } ?></option>
                    <option value="Done">Done</option>
                    <option value="Not Done">Not Done</option>
                </select>
                <input type="text" name="arm_date" id="arm_date" style="margin-top: 5px; padding: 6px;">
            </td>
            <td>NUMBER OF EXAMINATIONS DONE</td>
            <td><input type="text" name="no_of_examinations" value="<?= $no_of_examinations; ?>" id="no_of_examinations" style="padding: 6px;"></td>
            <td>ANY ABNORMALITIES OF 1<sup>st</sup> STAGE OF LABOUR</td>
            <td colspan="3"><textarea name="abnormalities" id="abnormalities" cols="30" rows="2"><?= $abnormalities; ?></textarea></td>
        </tr>

        <tr>
            <td>INDUCTION OF LABOUR</td>
            <td>
                <select name="induction_of_labor" id="induction_of_labor" style="padding: 6px; width: 100%;">
                    <option value="<?php if ($induction_of_labor) {echo $induction_of_labor;} else { echo ""; } ?>"><?php if ($induction_of_labor) {echo $induction_of_labor;} else { ?>Select<?php } ?></option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <textarea name="induction_of_labor_reason" id="induction_of_labor_reason" cols="30" rows="3" style="padding: 6px; margin-top: 5px;"><?= $induction_of_labor_reason; ?></textarea>
            </td>
            <td>TOTAL DURATION OF 1<sup>st</sup> STAGE OF LABOUR</td>
            <td><input type="text" name="first_stage_duration" id="first_stage_duration" value="<?= $first_stage_duration; ?>" style="padding: 6px;"></td>
            <td>DRUGS GIVEN</td>
            <td colspan="3"><textarea name="drugs_given" id="drugs_given" cols="30" rows="2" style="padding: 6px;"><?= $drugs_given; ?></textarea></td>
        </tr>

        <tr>
            <td>REMARKS</td>
            <td colspan="7"><textarea name="remarks" id="remarks" cols="30" rows="2" style="padding: 6px;"><?= $remarks; ?></textarea></td>
        </tr>

    </table>

    <br>

    <center>
        <span>
            <input type="button" value="SAVE" id="save_first_stage_of_labour" class="art-button-green" style="width: 10%;">
        </span>
    </center>

</fieldset>

<link rel="stylesheet" type="text/css" href="../css/jquery.datetimepicker.css">

<link rel="stylesheet" href="../css/select2.min.css" media="screen">

<script type="text/javascript" src="../css/jquery.datetimepicker.js"></script>

<script src="../js/select2.min.js"></script>

<script type="text/javascript" src="./js/first_stage_of_labor.js"></script>

<?php include("../includes/footer.php"); ?>