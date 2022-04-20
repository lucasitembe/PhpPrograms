<?php

include("./includes/header.php");

include("./includes/check_session.php");

include("./handlers/patient.php");

include("./handlers/employee.php");

include("./handlers/labor.php");

list($Sponsor_ID, $Member_Number, $Patient_Name, $Registration_ID, $region, $district, $Gender, $Guarantor_Name, $age, $tribe, $ten_cell, $religion) = get_patient_info_($patient_id, $conn);

list($dob, $sex, $wt, $apgar, $maturity, $membranes_ruptured, $amniotic_fluids, $antenatal_care, $diseases_complications, $delivery_type, $indication, $fhr, $placenta, $placenta_weight, $abnormalities, $drugs_given, $eye_drops, $sent_to, $delivered_by, $prem_unit_by, $recieved_by, $condition_on_arrival, $time) = get_neonatal_record($conn, $patient_id, $admision_id);

$Employee_array = get_employee_info($conn);

?>

<a href="preview_neonatal_records.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id; ?>" target="_blank" class="art-button-green">PREVIEW RECORDS</a>

<a href="labor_dashboard.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id; ?>" class="art-button-green">BACK</a>

<fieldset>

    <legend style="font-weight:bold" align=center>

        <div style="height:34px;margin:0px;padding:0px;font-weight:bold">

            <p style="margin:0px;padding:0px;" align=center>INFANTS LABOUR ROOM ADMISSION RECORD TO NEONATAL WARD</p>

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
            <td>BABY OF</td>
            <td><input type="text" name="baby_of" id="baby_of" value="<?= $Patient_Name; ?>" style="padding: 6px;"></td>
            <td>ADDRESS</td>
            <td><input type="text" name="address" id="address" value="<?= $region; ?>" style="padding: 6px;"></td>
            <td>RELIGION</td>
            <td><input type="text" name="religion" id="religion" value="<?= $religion; ?>" style="padding: 6px;"></td>
            <td>TRIBE</td>
            <td><input type="text" name="tribe" id="tribe" value="<?= $tribe; ?>" style="padding: 6px;"></td>
            <td>TEN CELL LEADER</td>
            <td><input type="text" name="ten_cell" id="ten_cell" value="<?= $ten_cell; ?>" style="padding: 6px;"></td>
        </tr>

        <tr>
            <td>DATE & TIME OF BIRTH</td>
            <td><input type="text" name="dob" id="dob" value="<?= $dob; ?>" style="padding: 6px;"></td>
            <td>SEX</td>
            <td>
                <select name="sex" id="sex" style="padding: 6px; width: 100%;">
                    <option value="<?php if ($sex) {echo $sex;} else { echo ""; } ?>"><?php if ($sex) {echo $sex;} else { ?>Select<?php } ?></option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </td>
            <td>WT AT BIRTH</td>
            <td>
                <input type="text" name="wt" id="wt" value="<?= $wt; ?>" style="padding: 6px;" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                <span style="margin-left: -30px;">gm</span>
            </td>
            <td>APGAR SCORE</td>
            <td><input type="text" name="apgar" id="apgar" value="<?= $apgar; ?>" style="padding: 6px;"></td>
            <td>MATURITY BY DATES</td>
            <td>
                <input type="text" name="maturity" id="maturity" value="<?= $maturity; ?>" style="padding: 6px;">
                <span style="margin-left: -50px;">week(s)</span>
            </td>
        </tr>

        <tr>
            <td>MEMBRANES RUPTURED FOR</td>
            <td>
                <select name="membranes_ruptured" id="membranes_ruptured" style="padding: 6px; width:100%;">
                    <option value="<?php if ($membranes_ruptured) {echo $membranes_ruptured;} else { echo ""; } ?>"><?php if ($membranes_ruptured) {echo $membranes_ruptured;} else { ?>Select<?php } ?></option>
                    <option value="Hours">Hours</option>
                    <option value="Days">Days</option>
                    <option value="Weeks">Weeks</option>
                </select>
            </td>
            <td>TYPES OF AMNIOTIC FLUID</td>
            <td>
                <select name="amniotic_fluids" id="amniotic_fluids" style="padding: 6px; width:100%;">
                    <option value="<?php if ($amniotic_fluids) {echo $amniotic_fluids;} else { echo ""; } ?>"><?php if ($amniotic_fluids) {echo $amniotic_fluids;} else { ?>Select<?php } ?></option>
                    <option value="Clear">Clear</option>
                    <option value="Meconeum">Meconeum</option>
                </select>
            </td>
            <td>ANTE-NATAL CARE</td>
            <td>
                <select name="antenatal_care" id="antenatal_care" style="padding: 6px; width:100%;">
                    <option value="<?php if ($antenatal_care) {echo $antenatal_care;} else { echo ""; } ?>"><?php if ($antenatal_care) {echo $antenatal_care;} else { ?>Select<?php } ?></option>
                    <option value="Attended">Attended</option>
                    <option value="Not Attended">Not Attended</option>
                </select>
            </td>
            <td colspan="2">MARTENAL DISEASES AND COMPLICATIONS</td>
            <td colspan="2"><input type="text" name="diseases_complications" id="diseases_complications" value="<?= $diseases_complications; ?>" style="padding: 6px;"></td>
        </tr>

        <tr>
            <td colspan="2">TYPE OF DELIVERY</td>
            <td colspan="2"><input type="text" name="delivery_type" id="delivery_type" value="<?= $delivery_type; ?>" style="padding: 6px;"></td>
            <td>INDICATION</td>
            <td><input type="text" name="indication" id="indication" value="<?= $indication; ?>" style="padding: 6px;"></td>
            <td colspan="2">FOETAL HEART BEATS AT DELIVERY</td>
            <td colspan="2"><input type="text" name="fhr" id="fhr" value="<?= $fhr; ?>" style="padding: 6px;"></td>
        </tr>
    </table>

    <table class="table table-striped table-hover" style="margin-top: 10px;">
        <tr>
            <td>PLACENTA</td>
            <td>
                <select name="placenta" id="placenta" style="padding: 6px; width: 100%;">
                    <option value="<?php if ($placenta) {echo $placenta;} else { echo ""; } ?>"><?php if ($placenta) {echo $placenta;} else { ?>Select<?php } ?></option>
                    <option value="Normal">Normal</option>
                    <option value="Abnormal">Abnormal</option>
                </select>
            </td>
            <td>WEIGHT OF PLACENTA</td>
            <td><input type="text" name="placenta_weight" id="placenta_weight" value="<?= $placenta_weight; ?>" style="padding: 6px;"></td>
            <td>BABY ABNORMALITIES</td>
            <td><input type="text" name="abnormalities" id="abnormalities" value="<?= $abnormalities; ?>" style="padding: 6px;"></td>
            <td>RESUCITATION METHOD</td>
            <td><input type="text" name="resucitation" id="resucitation" value="<?= $resucitation; ?>" style="padding: 6px;"></td>
        </tr>

        <tr>
            <td colspan="2">DRUGS GIVEN TO THE BABY (Type, Dose, Time)</td>
            <td colspan="2"><input type="text" name="drugs_given" id="drugs_given" value="<?= $drugs_given; ?>"  style="padding: 6px;"></td>
            <td>PROPHYLACTIC EYE DROPS</td>
            <td>
                <select name="eye_drops" id="eye_drops" style="padding: 6px; width: 100%;">
                    <option value="<?php if ($eye_drops) {echo $eye_drops;} else { echo ""; } ?>"><?php if ($eye_drops) {echo $eye_drops;} else { ?>Select<?php } ?></option>
                    <option value="Given">Given</option>
                    <option value="Not Given">Not Given</option>
                </select>
            </td>
            <td>SENT TO</td>
            <td>
                <select name="sent_to" id="sent_to" style="padding: 6px; width: 100%;">
                    <option value="<?php if ($sent_to) {echo $sent_to;} else { echo ""; } ?>"><?php if ($sent_to) {echo $sent_to;} else { ?>Select<?php } ?></option>
                    <option value="NICU">NICU</option>
                    <option value="PU">PU</option>
                </select>
            </td>
        </tr>

    </table>

    <table class="table table-striped table-hover" style="margin-top: 10px;">
        <tr>
            <td colspan="2">DELIVERED BY (Full name)</td>
            <td colspan="2">
                <select type="text" id="delivered_by" name="delivered_by" style="padding: 6px; width: 100%;">
                    <option value="<?php if ($delivered_by) {echo $delivered_by;} else { echo ""; } ?>"><?php if ($delivered_by) {echo $delivered_by;} else { ?>Select<?php } ?></option>
                    <?php for ($i = 0; $i < count($Employee_array); $i++) { ?>
                        <option value='<?php echo $Employee_array[$i]['employee_name'] ?>'><?php echo $Employee_array[$i]['employee_name']; ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>SENT TO PREM UNIT BY</td>
            <td>
                <select type="text" id="prem_unit_by" name="prem_unit_by" style="padding: 6px; width: 100%;">
                    <option value="<?php if ($prem_unit_by) {echo $prem_unit_by;} else { echo ""; } ?>"><?php if ($prem_unit_by) {echo $prem_unit_by;} else { ?>Select<?php } ?></option>
                    <?php for ($i = 0; $i < count($Employee_array); $i++) { ?>
                        <option value='<?php echo $Employee_array[$i]['employee_name'] ?>'><?php echo $Employee_array[$i]['employee_name']; ?></option>
                    <?php } ?>
                </select>
            </td>
            <td>RECIEVED BY</td>
            <td>
                <select type="text" id="recieved_by" name="recieved_by" style="padding: 6px; width: 100%;">
                    <option value="<?php if ($recieved_by) {echo $recieved_by;} else { echo ""; } ?>"><?php if ($recieved_by) {echo $recieved_by;} else { ?>Select<?php } ?></option>
                    <?php for ($i = 0; $i < count($Employee_array); $i++) { ?>
                        <option value='<?php echo $Employee_array[$i]['employee_name'] ?>'><?php echo $Employee_array[$i]['employee_name']; ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>CONDITION ON ARRIVAL</td>
            <td>
                <select name="condition_on_arrival" id="condition_on_arrival" style="padding: 6px; width: 100%;">
                    <option value="<?php if ($condition_on_arrival) {echo $condition_on_arrival;} else { echo ""; } ?>"><?php if ($condition_on_arrival) {echo $condition_on_arrival;} else { ?>Select<?php } ?></option>
                    <option value="Good">Good</option>
                    <option value="Fair">Fair</option>
                    <option value="Very Sick">Very Sick</option>
                </select>
            </td>
            <td>TIME</td>
            <td><input type="text" name="time" id="time" value="<?= $time; ?>" style="padding: 6px;"></td>
        </tr>
    </table>

    <br>

    <center>
        <span>
            <input type="button" value="SAVE" id="save_neonatal_record" class="art-button-green" style="width: 10%;">
        </span>
    </center>

</fieldset>



<link rel="stylesheet" type="text/css" href="../css/jquery.datetimepicker.css">

<link rel="stylesheet" href="../css/select2.min.css" media="screen">

<script type="text/javascript" src="../css/jquery.datetimepicker.js"></script>

<script src="../js/select2.min.js"></script>

<script type="text/javascript" src="./js/neonatal_record.js"></script>

<?php include("../includes/footer.php"); ?>