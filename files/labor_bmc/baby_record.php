<?php

include("./includes/header.php");

include("./includes/check_session.php");

include("./handlers/patient.php");

include("./handlers/employee.php");

include("./handlers/labor.php");

list($Sponsor_ID, $Member_Number, $Patient_Name, $Registration_ID, $Gender, $Guarantor_Name, $age) = get_patient_info($patient_id, $conn);

$Employee_array = get_employee_info($conn);

list($sex, $state_of_birth, $apgar, $birth_weight, $length, $head_circumference, $abnormalities, $drugs, $paediatrician, $transferred_to, $reason, $transferred_by, $name, $temperature) = get_baby_record($conn, $patient_id, $admision_id);

?>

<a href="preview_baby_record.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id; ?>" target="_blank" class="art-button-green">PREVIOUS RECORDS</a>

<a href="labor_dashboard.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id; ?>" class="art-button-green">BACK</a>

<fieldset>

    <legend style="font-weight:bold" align=center>

        <div style="height:34px;margin:0px;padding:0px;font-weight:bold">

            <p style="margin:0px;padding:0px;" align=center>BABY'S RECORD</p>

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
            <td>SEX</td>
            <td>
                <select name="sex" id="sex" style="width: 100%; padding: 6px;">
                    <option value="<?php if ($sex) {echo $sex;} else { echo ""; } ?>"><?php if ($sex) {echo $sex;} else { ?>Select<?php } ?></option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </td>
            <td>STATE AT BIRTH</td>
            <td>
                <select name="state_of_birth" id="state_of_birth" style="width: 100%; padding: 6px;">
                    <option value="<?php if ($state_of_birth) {echo $state_of_birth;} else { echo ""; } ?>"><?php if ($state_of_birth) {echo $state_of_birth;} else { ?>Select<?php } ?></option>
                    <option value="Alive">Alive</option>
                    <option value="Still Birth">Still Birth</option>
                    <option value="Fresh">Fresh</option>
                    <option value="Macerated">Macerated</option>
                </select>
            </td>
            <td>APGAR SCORE</td>
            <td><input type="text" name="apgar" id="apgar" value="<?= $apgar; ?>" style="padding: 6px;"></td>
            <td>BIRTH WEIGHT</td>
            <td>
                <input type="text" name="birth_weight" id="birth_weight" value="<?= $birth_weight; ?>" style="padding: 6px;" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                <span style="margin-left: -30px;">Kg</span>
            </td>
            <td>LENGTH</td>
            <td>
                <input type="text" name="length" id="length" value="<?= $length; ?>" style="padding: 6px;" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                <span style="margin-left: -30px;">CM</span>
            </td>
        </tr>

        <tr>
            <td>HEAD CIRCUMFERENCE</td>
            <td><input type="text" name="head_circumference" id="head_circumference" value="<?= $head_circumference; ?>" style="padding: 6px;"></td>
            <td colspan="2">CONGENITAL ABNORMALITIES</td>
            <td colspan="2"><input type="text" name="abnormalities" id="abnormalities" value="<?= $abnormalities; ?>" style="padding: 6px;"></td>
            <td>DRUGS GIVEN</td>
            <td>
                <select name="drugs" id="drugs" style="width: 100%; padding: 6px;">
                    <option value="<?php if ($drugs) {echo $drugs;} else { echo ""; } ?>"><?php if ($drugs) {echo $drugs;} else { ?>Select<?php } ?></option>
                    <option value="Vitamin K">Vitamin K</option>
                    <option value="Tetracycline">Tetracycline</option>
                    <option value="Eye Ointment">Eye Ointment</option>
                    <option value="Nevirapine syrup">Nevirapine Syrup</option>
                </select>
            </td>
            <td>PAEDIATRICIAN PRESENT</td>
            <td>
                <select name="paediatrician" id="paediatrician" style="width: 100%; padding: 6px;">
                    <option value="<?php if ($paediatrician) {echo $paediatrician;} else { echo ""; } ?>"><?php if ($paediatrician) {echo $paediatrician;} else { ?>Select<?php } ?></option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </td>
        </tr>

        <tr>
            <td>TRANSFERRED TO</td>
            <td><input type="text" name="transferred_to" id="transferred_to" value="<?= $transferred_to; ?>" style="padding: 6px;"></td>
            <td>REASON</td>
            <td colspan="5"><textarea name="reason" id="reason" value="<?= $reason; ?>" cols="30" rows="2" style="padding: 6px;"></textarea></td>
            <td>TRANSFERRED BY</td>
            <td>
                <select type="text" id="transferred_by" name="transferred_by" style="padding: 6px; width: 100%;">
                    <option value="<?php if ($transferred_by) {echo $transferred_by;} else { echo ""; } ?>"><?php if ($transferred_by) {echo $transferred_by;} else { ?>Select<?php } ?></option>
                    <?php for ($i = 0; $i < count($Employee_array); $i++) { ?>
                        <option value='<?php echo $Employee_array[$i]['employee_name'] ?>'><?php echo $Employee_array[$i]['employee_name']; ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>

        <tr>
            <td>NAME</td>
            <td colspan="7"><input type="text" name="name" id="name" value="<?= $name; ?>" style="padding: 6px;"></td>
            <td>TEMPERATURE </td>
            <td>
                <input type="text" name="temperature" id="temperature" value="<?= $temperature; ?>" style="padding: 6px;" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                <span style="margin-left: -30px;">&#176C</span>
            </td>
        </tr>

    </table>

    <br>

    <center>
        <span>
            <input type="button" value="SAVE" id="save_baby_record" class="art-button-green" style="width: 10%;">
        </span>
    </center>

</fieldset>

<link rel="stylesheet" type="text/css" href="../css/jquery.datetimepicker.css">

<link rel="stylesheet" href="../css/select2.min.css" media="screen">

<script type="text/javascript" src="../css/jquery.datetimepicker.js"></script>

<script src="../js/select2.min.js"></script>

<script type="text/javascript" src="./js/baby_record.js"></script>

<?php include("../includes/footer.php"); ?>