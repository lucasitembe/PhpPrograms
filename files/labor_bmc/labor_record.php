<?php

include("./includes/header.php");

include("./includes/check_session.php");

include("./handlers/patient.php");

include("./handlers/employee.php");

include("./handlers/labor.php");

list($Sponsor_ID, $Member_Number, $Patient_Name, $Registration_ID, $Gender, $Guarantor_Name, $age) = get_patient_info($patient_id, $conn);

list($date_time, $state_of_admission, $temperature, $pulse, $resp, $bp, $colour, $specific_gravity, $ph, $albumin, $sugar, $blood, $leucocytes, $ketones, $clinical_appearance, $varicose_veins, $blood2, $oedema, $mental_status, $inspection, $hb, $vdrl, $elisa, $shape, $scars, $fundal_height, $lie, $presentation, $position, $brim, $contraction, $fhr, $date_time2, $cervic_state, $dilation, $presenting_part, $station, $position2, $moulding, $caput, $membrane_liquor, $rapture_date, $admitted_by, $examinaer, $sacral_promontory, $sacral_curve, $ischial_spine, $subpubic_angle, $sacral_tuberosites, $expected_mode_of_delivery, $remarks, $informed_by) = get_labour_record($conn, $patient_id, $admision_id);

$Employee_array = get_employee_info($conn);

?>

<a href="preview_labor_records.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id; ?>" target="_blank" class="art-button-green">PREVIEW RECORDS</a>

<a href="labor_dashboard.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id; ?>" class="art-button-green">BACK</a>

<fieldset>

    <legend style="font-weight:bold" align=center>

        <div style="height:34px;margin:0px;padding:0px;font-weight:bold">

            <p style="margin:0px;padding:0px;" align=center>LABOUR RECORD</p>

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
            <td style="text-align: center; background-color:#bdb5ac;" colspan="2"></td>
            <td style="text-align: center; background-color:#bdb5ac;" colspan="2"><b>CLINICAL OBSERVATION</b></td>
            <td style="text-align: center; background-color:#bdb5ac;" colspan="4"><b>URINALYSIS</b></td>
        </tr>

        <tr>
            <td rowspan="3">DATE TIME</td>
            <td rowspan="3"><input type="text" name="date_time" id="date_time" value="<?= $date_time; ?>" style="padding: 6px;"></td>
            <td>TEMPERATURE </td>
            <td>
                <input type="text" name="temperature" id="temperature" value="<?= $temperature; ?>" style="padding: 6px;">
                <span style="margin-left: -30px;">&#176C</span>
            </td>
            <td>COLOUR</td>
            <td><input type="text" name="colour" id="colour" value="<?= $colour; ?>" style="padding: 6px;"></td>
            <td>SPECIFIC GRAVITY</td>
            <td><input type="text" name="specific_gravity" id="specific_gravity" value="<?= $specific_gravity; ?>" style="padding: 6px;"></td>
        </tr>

        <tr>
            <td>PULSE</td>
            <td>
                <input type="text" name="pulse" id="pulse" style="padding: 6px;" value="<?= $pulse; ?>">
                <span style="margin-left: -40px;">bpm</span>
            </td>
            <td>PH</td>
            <td><input type="text" name="ph" id="ph" value="<?= $ph; ?>" style="padding: 6px;"></td>
            <td>BLOOD</td>
            <td><input type="text" name="blood" id="blood" value="<?= $blood; ?>" style="padding: 6px;"></td>
        </tr>

        <tr>
            <td>RESP</td>
            <td>
                <input type="text" name="resp" id="resp" style="padding: 6px;" value="<?= $resp; ?>" >
                <span style="margin-left: -40px;">bpm</span>
            </td>
            <td>ALBUMIN</td>
            <td><input type="text" name="albumin" id="albumin" value="<?= $albumin; ?>" style="padding: 6px;"></td>
            <td>SUGAR</td>
            <td><input type="text" name="sugar" id="sugar" value="<?= $sugar; ?>" style="padding: 6px;"></td>
        </tr>

        <tr>
            <td>GENERAL STATE OF ADMISSION</td>
            <td><textarea name="state_of_admission" id="state_of_admission" style="padding: 6px;" cols="40" rows="3"><?= $state_of_admission; ?></textarea></td>
            <td>BLOOD PRESSURE</td>
            <td>
                <input type="text" name="bp" id="bp" style="padding: 6px;" value="<?= $bp; ?>">
                <span style="margin-left: -50px;">mmHg</span>
            </td>
            <td>LEUCOCYTES</td>
            <td><input type="text" name="leucocytes" id="leucocytes" value="<?= $leucocytes; ?>" style="padding: 6px;"></td>
            <td>KETONES</td>
            <td><input type="text" name="ketones" id="ketones" value="<?= $ketones; ?>" style="padding: 6px;"></td>
        </tr>

    </table>

    <table class="table table-striped table-hover">

        <tr>
            <td style="text-align: center; background-color:#bdb5ac;" colspan="2"><b>PHYSICAL EXAMINATION<b></td>
            <td style="text-align: center; background-color:#bdb5ac;" colspan="6"><b>INVESTIGATIONS DONE</b></td>
        </tr>

        <tr>
            <td>CLINICAL APPEARANCE</td>
            <td><input type="text" name="clinical_appearance" id="clinical_appearance" value="<?= $clinical_appearance; ?>" style="padding: 6px;"></td>
            <td>HB</td>
            <td><input type="text" name="hb" id="hb" value="<?= $hb; ?>" style="padding: 6px;"></td>
            <td>VDRL</td>
            <td><input type="text" name="vdrl" id="vdrl" value="<?= $vdrl; ?>" style="padding: 6px;"></td>
            <td>PMTCT</td>
            <td>
                <select name="elisa" id="elisa" style="padding: 6px; width: 100%;">
                    <option value="<?php if ($elisa) {echo $elisa;} else { echo ""; } ?>"><?php if ($elisa) {echo $elisa;} else { ?>Select<?php } ?></option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="Discontent">Discontent</option>
                </select>
            </td>
        </tr>

        <tr>
            <td colspan="2">VARICOSE VEINS</td>
            <td colspan="2"><input type="text" name="varicose_veins" id="varicose_veins" value="<?= $varicose_veins; ?>" style="padding: 6px;"></td>
            <td colspan="2">BLOOD</td>
            <td colspan="2"><input type="text" name="blood2" id="blood2" value="<?= $blood2; ?>" style="padding: 6px;"></td>
        </tr>

        <tr>
            <td>OEDEMA</td>
            <td><input type="text" name="oedema" id="oedema" value="<?= $oedema; ?>" style="padding: 6px;"></td>
            <td style="text-align: center; background-color:#bdb5ac;" colspan="6"><b>ABDOMINAL INSPECTION</b></td>
        </tr>

        <tr>
            <td>MENTAL STATUS</td>
            <td><input type="text" name="mental_status" id="mental_status" value="<?= $mental_status; ?>" style="padding: 6px;"></td>
            <td rowspan="2">SHAPE</td>
            <td colspan="2" rowspan="2">
                <select name="shape" id="shape" style="padding: 6px; width: 100%;">
                    <option value="<?php if ($shape) {echo $shape;} else { echo ""; } ?>"><?php if ($shape) {echo $shape;} else { ?>Select<?php } ?></option>
                    <option value="Pendulous">Pendulous</option>
                    <option value="Oval">Oval</option>
                </select>
            </td>
            <td rowspan="2">SCARS</td>
            <td colspan="2" rowspan="2"><input type="text" name="scars" id="scars" value="<?= $scars; ?>" style="padding: 6px;"></td>
        </tr>

        <tr>
            <td>INSPECTION</td>
            <td><input type="text" name="inspection" id="inspection" value="<?= $inspection; ?>" style="padding: 6px;"></td>
        </tr>

    </table>

    <table class="table table-striped table-hover">

        <tr>
            <td style="text-align: center; background-color:#bdb5ac;" colspan="8"><b>ABDOMINAL PALPATION</b></td>
        </tr>

        <tr>
            <td>FUNDAL HEIGHT</td>
            <td><input type="text" name="fundal_height" id="fundal_height" value="<?= $fundal_height; ?>" style="padding: 6px;"></td>
            <td>LIE</td>
            <td><input type="text" name="lie" id="lie" value="<?= $lie; ?>" style="padding: 6px;"></td>
            <td>PRESENTATION</td>
            <td><input type="text" name="presentation" id="presentation" value="<?= $presentation; ?>" style="padding: 6px;"></td>
            <td>POSITION</td>
            <td><input type="text" name="position" id="position" value="<?= $position; ?>" style="padding: 6px;"></td>
        </tr>

        <tr>
            <td colspan="2">ENGAGEMENT IN RELATION TO THE BRIM</td>
            <td><input type="text" name="brim" id="brim" value="<?= $brim; ?>" style="padding: 6px;"></td>
            <td colspan="2">FREQUENCY AND TYPE OF CONTRACTION</td>
            <td><input type="text" name="contraction" id="contraction" value="<?= $contraction; ?>" style="padding: 6px;"></td>
            <td>FOETAL HEART RATE</td>
            <td><input type="text" name="fhr" id="fhr" value="<?= $fhr; ?>" style="padding: 6px;"></td>
        </tr>

    </table>

    <br>

    <fieldset>

        <legend align="center" style="font-weight:bold">INITIAL VAGINAL EXAMINATION AND PELVIC ASSESSMENT</legend>

        <table class="table table-striped table-hover">

            <tr>
                <td>DATE AND TIME </td>
                <td><input type="text" name="date_time2" id="date_time2"  value="<?= $date_time2; ?>" style="padding: 6px;"></td>
                <td>EXAMINAER</td>
                <td>
                    <select type="text" id="examinaer" name="examinaer" style="padding: 6px; width: 100%;">
                        <option value="<?php if ($examinaer) {echo $examinaer;} else { echo ""; } ?>"><?php if ($examinaer) {echo $examinaer;} else { ?>Select<?php } ?></option>
                        <?php for ($i = 0; $i < count($Employee_array); $i++) { ?>
                            <option value='<?php echo $Employee_array[$i]['employee_name'] ?>'><?php echo $Employee_array[$i]['employee_name']; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td>CERVIC STATE </td>
                <td>
                    <select name="cervic_state" id="cervic_state" style="padding: 6px; width: 100%;">
                        <option value="<?php if ($cervic_state) {echo $cervic_state;} else { echo ""; } ?>"><?php if ($cervic_state) {echo $cervic_state;} else { ?>Select<?php } ?></option>
                        <option value="Effected">Effected</option>
                        <option value="Not effected">Not effected</option>
                    </select>
                </td>
                <td>DILATION </td>
                <td><input type="text" name="dilation" id="dilation" value="<?= $dilation; ?>" style="padding: 6px;"></td>
            </tr>

            <tr>
                <td>PRESENTING PART </td>
                <td><input type="text" name="presenting_part" id="presenting_part" value="<?= $presenting_part; ?>" style="padding: 6px;"></td>
                <td>STATION</td>
                <td><input type="text" name="station" id="station" value="<?= $station; ?>" style="padding: 6px;"></td>
                <td>POSITION :</td>
                <td>
                    <select name="position2" id="position2"  style="padding: 6px; width: 100%;">
                        <option value="<?php if ($position2) {echo $position2;} else { echo ""; } ?>"><?php if ($position2) {echo $position2;} else { ?>Select<?php } ?></option>
                        <option value="ROA">ROA</option>
                        <option value="LOA">LOA</option>
                        <option value="ROP">ROP</option>
                        <option value="LOP">LOP</option>
                        <option value="ROT">ROT</option>
                        <option value="Other">Other</option>
                    </select>
                </td>
                <td>MOULDING </td>
                <td>
                    <select name="moulding" id="moulding" style="padding: 6px; width: 100%;">
                        <option value="<?php if ($moulding) {echo $moulding;} else { echo ""; } ?>"><?php if ($moulding) {echo $moulding;} else { ?>Select<?php } ?></option>
                        <option value="No Moulding">No Moulding</option>
                        <option value="Moulding+">Moulding+</option>
                        <option value="Moulding++">Moulding++</option>
                        <option value="Moulding+++">Moulding+++</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>CAPUT </td>
                <td>
                    <select name="caput" id="caput" style="padding: 6px; width: 100%;">
                        <option value="<?php if ($caput) {echo $caput;} else { echo ""; } ?>"><?php if ($caput) {echo $caput;} else { ?>Select<?php } ?></option>
                        <option value="No Caput">No Caput</option>
                        <option value="Caput+">Caput+</option>
                        <option value="Caput++">Caput++</option>
                        <option value="Caput+++">Caput+++</option>
                    </select>
                </td>
                <td>MEMBRANE LIQUOR </td>
                <td>
                    <select name="membrane_liquor" id="membrane_liquor" style="padding: 6px; width: 100%;">
                        <option value="<?php if ($membrane_liquor) {echo $membrane_liquor;} else { echo ""; } ?>"><?php if ($membrane_liquor) {echo $membrane_liquor;} else { ?>Select<?php } ?></option>
                        <option value="Rapture">Rapture</option>
                        <option value="Intact">Intact</option>
                    </select>
                    <select name="rapture" id="rapture" style="margin-top: 5px; padding: 6px; width: 100%;">
                        <option value="">Select</option>
                        <option value="Clear">Clear</option>
                        <option value="Meconium Stand">Meconium Stand</option>
                        <option value="Blood">Blood</option>
                    </select>
                    <input type="text" name="rapture_date" id="rapture_date" value="<?= $rapture_date; ?>" style="margin-top: 5px; padding: 6px;">
                </td>
                <td>SACRAL PROMONTORY </td>
                <td>
                    <select name="sacral_promontory" id="sacral_promontory" style="padding: 6px; width: 100%;">
                        <option value="<?php if ($sacral_promontory) {echo $sacral_promontory;} else { echo ""; } ?>"><?php if ($sacral_promontory) {echo $sacral_promontory;} else { ?>Select<?php } ?></option>
                        <option value="Not Reached">Not Reached</option>
                        <option value="Just Reached">Just Reached</option>
                    </select>
                </td>
                <td>SACRAL CURVE </td>
                <td>
                    <select name="sacral_curve" id="sacral_curve" style="padding: 6px; width: 100%;">
                        <option value="<?php if ($sacral_curve) {echo $sacral_curve;} else { echo ""; } ?>"><?php if ($sacral_curve) {echo $sacral_curve;} else { ?>Select<?php } ?></option>
                        <option value="Flat">Flat</option>
                        <option value="Normal">Normal</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>ISCHIAL SPINES </td>
                <td>
                    <select name="ischial_spine" id="ischial_spine" style="padding: 6px; width: 100%;">
                        <option value="<?php if ($ischial_spine) {echo $ischial_spine;} else { echo ""; } ?>"><?php if ($ischial_spine) {echo $ischial_spine;} else { ?>Select<?php } ?></option>
                        <option value="Prominent">Prominent</option>
                        <option value="Normal">Normal</option>
                    </select>
                </td>
                <td>SUBPUBIC ANGLE </td>
                <td>
                    <select name="subpubic_angle" id="subpubic_angle" style="padding: 6px; width: 100%;">
                        <option value="<?php if ($subpubic_angle) {echo $subpubic_angle;} else { echo ""; } ?>"><?php if ($subpubic_angle) {echo $subpubic_angle;} else { ?>Select<?php } ?></option>
                        <option value="Narrow">Narrow</option>
                        <option value="Normal">Normal</option>
                    </select>
                </td>
                <td>SACRAL TUBEROSITES </td>
                <td>
                    <select name="sacral_tuberosites" id="sacral_tuberosites" style="padding: 6px; width: 100%;">
                        <option value="<?php if ($sacral_tuberosites) {echo $sacral_tuberosites;} else { echo ""; } ?>"><?php if ($sacral_tuberosites) {echo $sacral_tuberosites;} else { ?>Select<?php } ?></option>
                        <option value="4 Knuckles">4 Knuckles</option>
                        <option value="3 Knuckles">3 Knuckles</option>
                        <option value="2 Knuckles">2 Knuckles</option>
                        <option value="Others">Others</option>
                    </select>
                </td>
                <td>EXPECTED MODE OF DELIVERY </td>
                <td>
                    <select name="expected_mode_of_delivery" id="expected_mode_of_delivery" style="padding: 6px; width: 100%;">
                        <option value="<?php if ($expected_mode_of_delivery) {echo $expected_mode_of_delivery;} else { echo ""; } ?>"><?php if ($expected_mode_of_delivery) {echo $expected_mode_of_delivery;} else { ?>Select<?php } ?></option>
                        <option value="Vaginal Delivery">Vaginal Delivery</option>
                        <option value="Cesarean Delivery">Cesarean Delivery</option>
                        <option value="Vacuum Extraction">Vacuum Extraction</option>
                        <option value="Forceps Delivery">Forceps Delivery</option>
                        <option value="Breech Delivery">Breech Delivery</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>REMARKS</td>
                <td colspan="7"><textarea name="remarks" id="remarks" cols="30" rows="2" style="padding: 6px;"><?= $remarks; ?></textarea></td>
            </tr>

        </table>

    </fieldset>

    <br>

    <center>
        <span style="margin: 10px;">
            <b>ADMITTED BY : </b>
            <select type="text" id="admitted_by" name="admitted_by" style="padding: 6px; width: 30%;">
                <option value="<?php if ($admitted_by) {echo $admitted_by;} else { echo ""; } ?>"><?php if ($admitted_by) {echo $admitted_by;} else { ?>Select<?php } ?></option>
                <?php for ($i = 0; $i < count($Employee_array); $i++) { ?>
                    <option value='<?php echo $Employee_array[$i]['employee_name'] ?>'><?php echo $Employee_array[$i]['employee_name']; ?></option>
                <?php } ?>
            </select>
        </span>
        <span>
            <b>OBSTETRICIAN AND PAEDIATRICIAN INFORMED BY :</b>
            <select type="text" id="informed_by" name="informed_by" style="padding: 6px; width: 30%;">
                <option value="<?php if ($informed_by) {echo $informed_by;} else { echo ""; } ?>"><?php if ($informed_by) {echo $informed_by;} else { ?>Select<?php } ?></option>
                <?php for ($i = 0; $i < count($Employee_array); $i++) { ?>
                    <option value='<?php echo $Employee_array[$i]['employee_name'] ?>'><?php echo $Employee_array[$i]['employee_name']; ?></option>
                <?php } ?>
            </select>
        </span>
    </center>

    <br>

    <center>
        <span>
            <input type="button" value="SAVE" id="save_labor_record" class="art-button-green" style="width: 10%;">
        </span>
    </center>

</fieldset>

<link rel="stylesheet" type="text/css" href="../css/jquery.datetimepicker.css">

<link rel="stylesheet" href="../css/select2.min.css" media="screen">

<script type="text/javascript" src="../css/jquery.datetimepicker.js"></script>

<script src="../js/select2.min.js"></script>

<script type="text/javascript" src="./js/labor_record.js"></script>

<?php include("../includes/footer.php"); ?>
