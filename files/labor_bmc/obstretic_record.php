<?php

include("./includes/header.php");

include("./includes/check_session.php");

include("./handlers/patient.php");

include("./handlers/employee.php");

include("./handlers/labor.php");

list($Sponsor_ID, $Member_Number, $Patient_Name, $Registration_ID, $Gender, $Guarantor_Name, $age) = get_patient_info($patient_id, $conn);

list($paeditrician, $anaesthetist, $surgeon, $physician, $date_of_admission, $date_of_anc, $drug_allergies, $date_of_discharge, $lmp_duration, $edd_duration, $ga_duration, $para, $gravida, $blood_group, $weight, $height, $medical_surgical_history, $family_history, $reason_for_admission) = get_obstretic_record($conn, $patient_id, $admision_id);

$Employee_array = get_employee_info($conn);

$obstretic_array = get_obstretic_history($conn, $patient_id, $admision_id);

?>

<a href="preview_obstretic_records.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id; ?>" target="_blank" class="art-button-green">PREVIEW RECORDS</a>

<a href="labor_dashboard.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id; ?>" class="art-button-green">BACK</a>

<fieldset>

    <legend style="font-weight:bold" align=center>

        <div style="height:34px;margin:0px;padding:0px;font-weight:bold">

            <p style="margin:0px;padding:0px;" align=center>OBSTRETIC HISTORY</p>

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
            <td>PAEDIATRICIAN</td>
            <td>
                <select type="text" id="paeditrician" name="paeditrician">
                    <option value="<?php if ($paeditrician) {
                                        echo $paeditrician;
                                    } else {
                                        echo "";
                                    } ?>"><?php if ($paeditrician) {
                                                                                                            echo $paeditrician;
                                                                                                        } else { ?>Select<?php } ?></option>
                    <?php for ($i = 0; $i < count($Employee_array); $i++) { ?>
                        <option value='<?php echo $Employee_array[$i]['employee_name'] ?>'><?php echo $Employee_array[$i]['employee_name']; ?></option>
                    <?php } ?>
                </select>
            </td>
            <td>ANAESTHETIST</td>
            <td>
                <select type="text" id="anaesthetist" name="anaesthetist">
                    <option value="<?php if ($anaesthetist) {
                                        echo $anaesthetist;
                                    } else {
                                        echo "";
                                    } ?>"><?php if ($anaesthetist) {
                                                                                                            echo $anaesthetist;
                                                                                                        } else { ?>Select<?php } ?></option>
                    <?php for ($i = 0; $i < count($Employee_array); $i++) { ?>
                        <option value='<?php echo $Employee_array[$i]['employee_name'] ?>'><?php echo $Employee_array[$i]['employee_name']; ?></option>
                    <?php } ?>
                </select>
            </td>
            <td>SURGEON</td>
            <td>
                <select type="text" id="surgeon" name="surgeon">
                    <option value="<?php if ($surgeon) {
                                        echo $surgeon;
                                    } else {
                                        echo "";
                                    } ?>"><?php if ($surgeon) {
                                                                                                    echo $surgeon;
                                                                                                } else { ?>Select<?php } ?></option>
                    <?php for ($i = 0; $i < count($Employee_array); $i++) { ?>
                        <option value='<?php echo $Employee_array[$i]['employee_name'] ?>'><?php echo $Employee_array[$i]['employee_name']; ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>

        <tr>
            <td>PHYSICIAN</td>
            <td>
                <select type="text" id="physician" name="physician">
                    <option value="<?php if ($physician) {
                                        echo $physician;
                                    } else {
                                        echo "";
                                    } ?>"><?php if ($physician) {
                                                                                                        echo $physician;
                                                                                                    } else { ?>Select<?php } ?></option>
                    <?php for ($i = 0; $i < count($Employee_array); $i++) { ?>
                        <option value='<?php echo $Employee_array[$i]['employee_name'] ?>'><?php echo $Employee_array[$i]['employee_name']; ?></option>
                    <?php } ?>
                </select>
            </td>
            <td>DATE OF ADMISSION</td>
            <td><input type="text" name="date_of_admission" id="date_of_admission" value="<?= $date_of_admission; ?>" style="padding: 6px;"></td>
            <td>DATE OF FIRST ATTENDANCE TO ANC</td>
            <td><input type="text" name="date_of_anc" id="date_of_anc" value="<?= $date_of_anc; ?>" style="padding: 6px;"></td>
        </tr>

        <tr>
            <td>DRUG ALLERGIES</td>
            <td colspan="2"><input type="text" name="drug_allergies" id="drug_allergies" value="<?= $drug_allergies; ?>" style="padding: 6px;"></td>
            <td>RISK FACTORS (Diabetic, PIH, Anemia)</td>
            <td colspan="2"><input type="text" name="risk_factors" id="risk_factors" style="padding: 6px;"></td>
        </tr>

        <tr>
            <td>PREVIOUS STEROID THERAPY</td>
            <td colspan="2"><input type="text" name="steroid_therapy" id="steroid_therapy" style="padding: 6px;"></td>
            <td>DATE OF DISCHARGE</td>
            <td colspan="2"><input type="text" name="date_of_discharge" id="date_of_discharge" value="<?= $date_of_discharge; ?>" style="padding: 6px;"></td>
        </tr>

        <tr>
            <td>PATIENT NO</td>
            <td><input type="text" name="patient_no" id="patient_no" value="<?= $Registration_ID; ?>" style="padding: 6px;"></td>
            <td>PATIENT NAME</td>
            <td><input type="text" name="patient_name" id="patient_name" value="<?= $Patient_Name; ?>" style="padding: 6px;"></td>
            <td>PATIENT AGE</td>
            <td><input type="text" name="patient_age" id="patient_age" value="<?= $age; ?>" style="padding: 6px;"></td>
        </tr>

    </table>

    <table class="table table-striped table-hover">

        <tr>
            <td style="text-align: center; background-color:#bdb5ac;" colspan="10"><b>OBSETRIC HISTORY</b></td>
        </tr>

        <tr>
            <td>LMP </td>
            <td colspan="2"><input type="text" name="lmp_date" id="lmp_date" value="<?= $lmp_duration; ?>" style="padding: 6px;"></td>
            <td>EDD </td>
            <td colspan="2"><input type="text" name="edd_date" id="edd_date" value="<?= $edd_duration; ?>" style="padding: 6px;"></td>
            <td colspan="2">GA </td>
            <td colspan="2">
                <input type="text" name="ga_duration" id="ga_duration" value="<?= $ga_duration; ?>" style="padding: 6px;">
                <span style="margin-left: -50px;">week(s)</span>
            </td>
        </tr>

        <tr>
            <td>GRAVIDA </td>
            <td><input type="text" name="gravida" id="gravida" value="<?= $gravida; ?>" style="padding: 6px;"></td>
            <td>PARA </td>
            <td><input type="text" name="para" id="para" value="<?= $para; ?>" style="padding: 6px;"></td>
            <td>BLOOD GROUP </td>
            <td>
                <select name="blood_group" id="blood_group" style="padding: 6px; width: 100%">
                    <option value="<?php if ($blood_group) {
                                        echo $blood_group;
                                    } else {
                                        echo "";
                                    } ?>"><?php if ($blood_group) {
                                                                                                            echo $blood_group;
                                                                                                        } else { ?>Select<?php } ?></option>
                    <option value="A+">A<sup>+</sup></option>
                    <option value="A-">A<sup>-</sup></option>
                    <option value="B+">B<sup>+</sup></option>
                    <option value="B-">B<sup>-</sup></option>
                    <option value="AB+">AB<sup>+</sup></option>
                    <option value="AB-">AB<sup>-</sup></option>
                    <option value="O+">O<sup>+</sup></option>
                    <option value="O-">O<sup>-</sup></option>
                </select>
            </td>
            <td>WEIGHT</td>
            <td>
                <input type="text" name="weight" id="weight" value="<?= $weight; ?>" style="padding: 6px;" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                <span style="margin-left: -30px;">Kg</span>
            </td>
            <td>HEIGHT</td>
            <td>
                <input type="text" name="height" id="height" value="<?= $height; ?>" style="padding: 6px;" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                <span style="margin-left: -30px;">CM</span>
            </td>
        </tr>

    </table>

    <table class="table table-striped table-hover">

        <tr style="background-color:#bdb5ac;">
            <td style="text-align: center;"><b>YEAR OF BIRTH</b></td>
            <td style="text-align: center;"><b>MATUNITY</b></td>
            <td style="text-align: center;"><b>SEX</b></td>
            <td style="text-align: center;"><b>MODE OF DELIVERY</b></td>
            <td style="text-align: center;"> <b>BIRTH WEIGHT</b></td>
            <td style="text-align: center;"> <b>PLACE OF BIRTH</b></td>
            <td style="text-align: center;"> <b>BREASTFED DURATION</b></td>
            <td style="text-align: center;"> <b>PUERPERIUM</b></td>
            <td style="text-align: center;"> <b>PRESENT CONDITION OF CHILD</b></td>
            <td style="text-align: center;"> <b>ACTION</b></td>
        </tr>

        <tr>
            <td><input type="text" name="year_of_birth" id="year_of_birth" style="padding: 6px;"></td>
            <td>
                <select name="matunity" id="matunity" style="padding: 6px; width: 100%;">
                    <option value="">Select</option>
                    <option value="Fullterm">Full Term</option>
                    <option value="Preterm">Pre Term</option>
                    <option value="Abortion">Arbortion</option>
                </select>
            </td>
            <td>
                <select name="sex" id="sex" style="padding: 6px; width: 100%;">
                    <option value="">Select</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </td>
            <td>
                <select name="mode_of_delivery" id="mode_of_delivery" style="padding: 6px; width: 100%;">
                    <option value="">Select</option>
                    <option value="Vaginal Delivery">Vaginal Delivery</option>
                    <option value="Cesarean Delivery">Cesarean Delivery</option>
                    <option value="Vaginal after Cesarean">Vaginal Birth After Cesarean</option>
                    <option value="Vacuum Extraction">Vacuum Extraction</option>
                    <option value="Forceps Delivery">Forceps Delivery</option>
                    <option value="Breech Delivery">Breech Delivery</option>
                </select>
            </td>
            <td>
                <input type="text" name="birth_weight" id="birth_weight" style="padding: 6px;">
                <span style="margin-left: -30px;">Kg</span>
            </td>
            <td><input type="text" name="place_of_birth" id="place_of_birth" style="padding: 6px;"></td>
            <td><input type="text" name="breastfed_duration" id="breastfed_duration" style="padding: 6px;"></td>
            <td><input type="text" name="puerperium" id="puerperium" style="padding: 6px;"></td>
            <td><input type="text" name="present_child_condition" id="present_child_condition" style="padding: 6px;"></td>
            <td><input type="button" value="ADD" id="save_obstetric_history" class="art-button-green"></td>
        </tr>

        <?php for ($i = 0; $i < count($obstretic_array); $i++) { ?>
            <tr>
                <td style="font-family: arial; font-size: 13px; text-align: center;"><?= $obstretic_array[$i]['year_of_birth'] ?></td>
                <td style="font-family: arial; font-size: 13px; text-align: center;"><?= $obstretic_array[$i]['matunity'] ?></td>
                <td style="font-family: arial; font-size: 13px; text-align: center;"><?= $obstretic_array[$i]['sex'] ?></td>
                <td style="font-family: arial; font-size: 13px; text-align: center;"><?= $obstretic_array[$i]['mode_of_delivery'] ?></td>
                <td style="font-family: arial; font-size: 13px; text-align: center;"><?= $obstretic_array[$i]['birth_weight'] ?></td>
                <td style="font-family: arial; font-size: 13px; text-align: center;"><?= $obstretic_array[$i]['place_of_birth'] ?></td>
                <td style="font-family: arial; font-size: 13px; text-align: center;"><?= $obstretic_array[$i]['breastfed_duration'] ?></td>
                <td style="font-family: arial; font-size: 13px; text-align: center;"><?= $obstretic_array[$i]['puerperium'] ?></td>
                <td colspan="2" style="font-family: arial; font-size: 13px; text-align: center;"><?= $obstretic_array[$i]['present_child_condition'] ?></td>
            </tr>
        <?php } ?>

    </table>

    <table class="table table-striped table-hover">

        <tr>
            <td>MEDICAL AND SURGICAL HSTORY</td>
            <td><textarea name="medical_surgical_history" id="medical_surgical_history" style="padding: 6px;" cols="30" rows="5"><?= $medical_surgical_history; ?></textarea></td>
            <td>FAMILY HISTORY</td>
            <td><textarea name="family_history" id="family_history" style="padding: 6px;" cols="30" rows="5"><?= $family_history; ?></textarea></td>
            <td>DIAGNOSIS AND REASON FOR ADMISSION</td>
            <td><textarea name="reason_for_admission" id="reason_for_admission" style="padding: 6px;" cols="30" rows="5"><?= $reason_for_admission; ?></textarea></td>
        </tr>

    </table>

    <br>

    <center>
        <span>
            <input type="button" value="SAVE" id="save_obstretic_record" class="art-button-green" style="width: 10%;">
        </span>
    </center>

</fieldset>

<link rel="stylesheet" type="text/css" href="../css/jquery.datetimepicker.css">

<link rel="stylesheet" href="../css/select2.min.css" media="screen">

<script type="text/javascript" src="../css/jquery.datetimepicker.js"></script>

<script src="../js/select2.min.js"></script>

<script type="text/javascript" src="./js/obstretic_record.js"></script>

<?php include("../includes/footer.php"); ?>