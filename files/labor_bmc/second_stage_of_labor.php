<?php

include("./includes/header.php");

include("./includes/check_session.php");

include("./handlers/patient.php");

include("./handlers/labor.php");

list($Sponsor_ID, $Member_Number, $Patient_Name, $Registration_ID, $Gender, $Guarantor_Name, $age) = get_patient_info($patient_id, $conn);

list($time_began, $date_time, $duration, $mode_of_delivery, $drugs, $remarks) = get_second_stage($conn, $patient_id, $admision_id);

?>

<a href="preview_second_stage.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id; ?>" target="_blank" class="art-button-green">PREVIEW RECORDS</a>

<a href="labor_dashboard.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id; ?>" class="art-button-green">BACK</a>

<fieldset>

    <legend style="font-weight:bold" align=center>

        <div style="height:34px;margin:0px;padding:0px;font-weight:bold">

            <p style="margin:0px;padding:0px;" align=center>SECOND STATE OF LABOUR</p>

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
            <td>TIME BEGAN</td>
            <td><input type="text" name="time_began" id="time_began" value="<?= $time_began; ?>" style="padding: 6px;"></td>
            <td>DATE AND TIME OF BIRTH</td>
            <td><input type="text" name="date_time" id="date_time" value="<?= $date_time; ?>" style="padding: 6px;"></td>
        </tr>

        <tr>
            <td>DURATION</td>
            <td><input type="text" name="duration" id="duration" value="<?= $duration; ?>" style="padding: 6px;"></td>
            <td>MODE OF DELIVERY</td>
            <td>
                <select name="mode_of_delivery" id="mode_of_delivery" style="padding: 6px; width: 100%;">
                    <option value="<?php if ($mode_of_delivery) {echo $mode_of_delivery;} else { echo ""; } ?>"><?php if ($mode_of_delivery) {echo $mode_of_delivery;} else { ?>Select<?php } ?></option>
                    <option value="Vaginal Delivery">Vaginal Delivery</option>
                    <option value="Cesarean Delivery">Cesarean Delivery</option>
                    <option value="Vaginal after Cesarean">Vaginal Birth After Cesarean</option>
                    <option value="Vacuum Extraction">Vacuum Extraction</option>
                    <option value="Forceps Delivery">Forceps Delivery</option>
                    <option value="Breech Delivery">Breech Delivery</option>
                </select>
            </td>
        </tr>

        <tr>
            <td>DRUGS</td>
            <td><textarea name="drugs" id="drugs" cols="30" rows="2"><?= $drugs; ?></textarea></td>
            <td>REMARKS</td>
            <td><textarea name="remarks" id="remarks" cols="30" rows="2"><?= $remarks; ?></textarea></td>
        </tr>

    </table>

<br>

<center>
    <span>
        <input type="button" value="SAVE" id="save_second_stage_of_labour" class="art-button-green" style="width: 10%;">
    </span>
</center>

</fieldset>

<link rel="stylesheet" type="text/css" href="../css/jquery.datetimepicker.css">

<link rel="stylesheet" href="../css/select2.min.css" media="screen">

<script type="text/javascript" src="../css/jquery.datetimepicker.js"></script>

<script src="../js/select2.min.js"></script>

<script type="text/javascript" src="./js/second_stage_of_labor.js"></script>

<?php include("../includes/footer.php"); ?>