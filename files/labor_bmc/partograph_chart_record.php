<?php

include("./includes/header.php");

include("./includes/check_session.php");

include("./handlers/patient.php");

include("./handlers/employee.php");

include("./handlers/labor.php");

list($Sponsor_ID, $Member_Number, $Patient_Name, $Registration_ID, $Gender, $Guarantor_Name, $age) = get_patient_info($patient_id, $conn);

$Employee_array = get_employee_info($conn);

list($sex, $weight, $abnormalities, $resuscitation, $drugs, $eye_drop) = get_summary_of_labor($conn, $Registration_ID, $admision_id);

?>

<input class="art-button-green" value="SET ALERT AND ACTION" id="alert_action_open">

<a href="labor_dashboard.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id; ?>" class="art-button-green">BACK</a>

<fieldset>

    <legend style="font-weight:bold" align=center>

        <div style="height:34px;margin:0px;padding:0px;font-weight:bold">

            <p style="margin:0px;padding:0px;" align=center>PARTOGRAPH CHART</p>

            <p style="color:yellow;margin:0px;padding:0px; ">

                <span><?= $Patient_Name; ?></span> |

                <span><?= $Gender; ?></span> |

                <span><?= $age; ?></span> |

                <span><?= $Guarantor_Name; ?></span>

            </p>

        </div>

    </legend>

    <center>

        <input type="hidden" name="patient_id" id="patient_id" value="<?= $patient_id; ?>">

        <input type="hidden" name="admission_id" id="admission_id" value="<?= $admision_id; ?>">

        <input type="hidden" name="consultation_id" id="consultation_id" value="<?= $consultation_id; ?>">

        <!--###### FETAL HEART RATE SECTION ######-->
        <div id="fetal_heart_rate_chart"></div>

        <div>
            <span>Fetal Heart Rate: </span>
            <span>
                <input type="text" name="fetal_heart_rate" id="pointx" style="width: 10%;">
            </span>
            <span>Hour: </span>
            <span>
                <select name="hour" id="pointy" style="width: 10%; height:30px !important;">
                    <?php for ($i = 0; $i <= 48; $i++) { ?>
                        <option value="<?= ($i / 2); ?>"><?= ($i / 2); ?></option>
                    <?php } ?>
                </select>
            </span>
            <span>Baby no: </span>
            <span>
                <select name="baby_no" id="baby_no" style="width: 10%; height:30px !important;">
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select>
            </span>
            <!-- <span>Time: </span>
            <span>
                <input type="text" name="time" id="time_remark" placeholder="Select time" style="text-align: center;width:10%;display:inline;">
            </span> -->
            <span>
                <button id="add_fetal_heart_rate" type="button" style="width:100px; height:30px !important;">Add</button>
            </span>
        </div>
        <!--###### END FETAL HEART RATE SECTION ######-->

        <br>

        <hr style="border:10px solid #C0C0C0; width:90vw;">

        <br>

        <!--###### LIQUOR STATE SECTION ######-->
        <div>
            <table id="table">

                <!-- <tr id="liquor_time_hour">
                    <td style="width:5%; font-weight:bold; padding: 10px;text-align:center;">Time</td>
                    <?php for ($i = 0; $i <= 24; $i++) { ?>
                        <td id="<?= ($i); ?>" style="text-align: center;"></td>
                    <?php } ?>
                </tr> -->
                <tr id="liqour_remark">
                    <td style="width:5%; font-weight:bold; padding: 10px;text-align:center;">State Of Liqour</td>
                    <?php for ($i = 0; $i <= 24; $i++) { ?>
                        <td id="<?= ($i); ?>" style="text-align: center;"></td>
                    <?php } ?>
                </tr>
                <tr id="moulding">
                    <td style="width:5%; font-weight:bold; padding: 10px;text-align:center;">Moulding</td>
                    <?php for ($i = 0; $i <= 24; $i++) { ?>
                        <td id="<?= ($i); ?>" style="text-align: center;"></td>
                    <?php } ?>
                </tr>
                <tr id="caput">
                    <td style="width:5%; font-weight:bold; padding: 10px;text-align:center;">Caput</td>
                    <?php for ($i = 0; $i <= 24; $i++) { ?>
                        <td id="<?= ($i); ?>" style="text-align: center;"></td>
                    <?php } ?>
                </tr>
                <tr id="time">
                    <td style="width:5%; font-weight:bold; padding: 10px;text-align:center;"></td>
                    <?php for ($i = 0; $i <= 24; $i++) { ?>
                        <td id="<?= ($i); ?>" style="text-align: center;"><?= ($i); ?></td>
                    <?php } ?>
                </tr>

            </table>
        </div>

        <div>
            <table id='table-input' width="100%;">

                <tr>
                    <td>
                        <p style="width:100px !important; margin:0px; padding:0px;">State of liquor</p>
                    </td>
                    <td><button class="btn-inputl">C</button></td>
                    <td><button class="btn-inputl">I</button></td>
                    <td><button class="btn-inputl">M</button></td>
                    <td><button class="btn-inputl">B</button></td>
                    <td>
                        <p style="width:100px !important; margin:0px; padding:0px;">Moulding</p>
                    </td>
                    <td><button class="btn-inputm">0</button></td>
                    <td><button class="btn-inputm">+1</button></td>
                    <td><button class="btn-inputm">+2</button></td>
                    <td><button class="btn-inputm">+3</button></td>
                    <td><input class="art-button-green" value="Caput" id="caput_open"></td>
                    <!-- <td><input class="art-button-green" value="Time" id="liquor_time_open"></td> -->
                </tr>

            </table>
        </div>
        <!--###### END LIQUOR STATE SECTION ######-->

        <br>

        <hr style="border:10px solid #C0C0C0; width:90vw;">

        <!--###### PROGRESS OF LABOR SECTION ######-->
        <h4 style="margin: 10px;">Progress Of Labor</h4>

        <div id="progress_of_labor_chart"></div>

        <div class="input-to-graph">

            <span style="font-weight:bold;">Cervical Dilation</span>

            <span>X: <input type="text" name="x" class="input-fy" style="width: 10%;"></span>

            <span style="font-weight:bold;">Descent</span>

            <span>X: <input type="text" name="x" class="input-sy" style="width: 10%;"></span>

            <span>Fetal presentation:
                <select name="fetal_position" id="fetal_position" style="width: 10%; height:30px !important;">
                    <option value="head">Head</option>
                    <option value="buttock">Buttock</option>
                </select>
            </span>

            <span>Hour: </span>
            <span>
                <select name="o" class="input-fx" style="width: 10%; height:30px !important;">
                    <?php for ($i = 0; $i <= 48; $i++) { ?>
                        <option value="<?= ($i / 2); ?>"><?= ($i / 2); ?></option>
                    <?php } ?>
                </select>
            </span>

            <span>Time: </span>
            <span>
                <input type="text" name="time" id="labour_time_remark" placeholder="Select time" style="text-align: center;width:10%;display:inline;">
            </span>

            <span>
                <button type="button" name="button" style="width:100px; height:30px !important;" id="add_progress_of_labor">Add</button>
            </span>

        </div>
        <!--###### END PROGRESS OF LABOR SECTION ######-->

        <hr style="border:10px solid #C0C0C0; width:90vw;">

        <!--###### CONTRACTION SECTION ######-->
        <div class="contraction">

            <h4>Contraction</h4>

            <div class="arrange-table">

                <table id="table">

                    <!-- <tr id="contraction_time_hour">
                        <td style="width:5%; font-weight:bold; padding: 10px;text-align: center;">Time</td>
                        <?php for ($i = 0; $i <= 24; $i++) { ?>
                            <td id="<?= ($i); ?>" style="text-align: center;"></td>
                        <?php } ?>
                    </tr> -->

                    <tr id="five">
                        <td style="width:5%; font-weight:bold; padding: 10px;text-align: center;">5</td>
                        <?php for ($i = 0; $i <= 24; $i++) { ?>
                            <td id="<?= ($i); ?>" style="text-align: center; margin:0px;padding:0px;"></td>
                        <?php } ?>
                    </tr>

                    <tr id="four">
                        <td style="width:5%; font-weight:bold; padding: 10px;text-align: center;">4</td>
                        <?php for ($i = 0; $i <= 24; $i++) { ?>
                            <td id="<?= ($i); ?>" style="text-align: center; margin:0px;padding:0px;"></td>
                        <?php } ?>
                    </tr>

                    <tr id="three">
                        <td style="width:5%; font-weight:bold; padding: 10px;text-align: center;">3</td>
                        <?php for ($i = 0; $i <= 24; $i++) { ?>
                            <td id="<?= ($i); ?>" style="text-align: center; margin:0px;padding:0px;"></td>
                        <?php } ?>
                    </tr>

                    <tr id="two">
                        <td style="width:5%; font-weight:bold; padding: 10px;text-align: center;">2</td>
                        <?php for ($i = 0; $i <= 24; $i++) { ?>
                            <td id="<?= ($i); ?>" style="text-align: center; margin:0px;padding:0px;"></td>
                        <?php } ?>
                    </tr>

                    <tr id="one">
                        <td style="width:5%; font-weight:bold; padding: 10px;text-align: center;">1</td>
                        <?php for ($i = 0; $i <= 24; $i++) { ?>
                            <td id="<?= ($i); ?>" style="text-align: center; margin:0px;padding:0px;"></td>
                        <?php } ?>
                    </tr>

                    <tr id="time">
                        <td style="width:5%; font-weight:bold; padding: 10px;text-align: center;"></td>
                        <?php for ($i = 0; $i <= 24; $i++) { ?>
                            <td id="<?= ($i); ?>" style="text-align: center;"><?= ($i); ?></td>
                        <?php } ?>
                    </tr>

                </table>

                <span>
                    <input id="contraction" class="art-button-green" value="Contraction per min" style="width: 10%;">
                </span>

            </div>

        </div>
        <!--###### END CONTRACTION SECTION ######-->

        <br>

        <hr style="border:10px solid #C0C0C0; width:90vw;">

        <!--###### OXYTOCIN SECTION ######-->
        <div>
            <table id="table">

                <!-- <tr id="oxytocin_time_hour">
                    <td style="width:5%; font-weight:bold; padding: 10px;text-align:center;">Time</td>
                    <?php for ($i = 0; $i <= 24; $i++) { ?>
                        <td id="<?= ($i); ?>" style="text-align: center;"></td>
                    <?php } ?>
                </tr> -->
                <tr id="oxytocine_fill">
                    <td style="width:5%; font-weight:bold; padding: 10px;text-align:center;">Oxyticin IU</td>
                    <?php for ($i = 0; $i <= 24; $i++) { ?>
                        <td id="<?= ($i); ?>" style="text-align: center;"></td>
                    <?php } ?>
                </tr>
                <tr id="drop_fill">
                    <td style="width:5%; font-weight:bold; padding: 10px;text-align:center;">Drops/Minute Pulse</td>
                    <?php for ($i = 0; $i <= 24; $i++) { ?>
                        <td id="<?= ($i); ?>" style="text-align: center;"></td>
                    <?php } ?>
                </tr>
                <tr id="time">
                    <td style="width:5%; font-weight:bold; padding: 10px;text-align:center;"></td>
                    <?php for ($i = 0; $i <= 24; $i++) { ?>
                        <td id="<?= ($i); ?>" style="text-align: center;"><?= ($i); ?></td>
                    <?php } ?>
                </tr>

            </table>

            <div>
                <span>
                    <input id="oxyticin" class="art-button-green" value="Oxytocin" style="width:10%;">
                </span>

                <span>
                    <input id="drop" class="art-button-green" value="Drops/mm" style="width:10%;">
                </span>

                <!-- <span>
                    <input id="oxytocin_time_open" class="art-button-green" value="Time">
                </span> -->
            </div>
        </div>
        <!--###### END OXYTOCIN SECTION ######-->

        <br>

        <hr style="border:10px solid #C0C0C0; width:90vw;">

        <!--###### TEMPERATURE AND RESPIRATION SECTION ######-->
        <div>
            <table id="table">

                <!-- <tr id="temp_time_hour">
                    <td style="width:5%; font-weight:bold; padding: 10px;text-align:center;">Time</td>
                    <?php for ($i = 0; $i <= 24; $i++) { ?>
                        <td id="<?= ($i); ?>" style="text-align: center;"></td>
                    <?php } ?>
                </tr> -->

                <tr id="bpressure">
                    <td style="width:5%; font-weight:bold; padding: 10px;text-align:center;">Blood Pressure</td>
                    <?php for ($i = 0; $i <= 24; $i++) { ?>
                        <td id="<?= ($i); ?>" style="text-align: center;"></td>
                    <?php } ?>
                </tr>

                <tr id="temp_fill">
                    <td style="width:5%; font-weight:bold; padding: 10px;text-align:center;">TEMP</td>
                    <?php for ($i = 0; $i <= 24; $i++) { ?>
                        <td id="<?= ($i); ?>" style="text-align: center;"></td>
                    <?php } ?>
                </tr>

                <tr id="res_fill">
                    <td style="width:5%; font-weight:bold; padding: 10px;text-align:center;">RESP</td>
                    <?php for ($i = 0; $i <= 24; $i++) { ?>
                        <td id="<?= ($i); ?>" style="text-align: center;"></td>
                    <?php } ?>
                </tr>

                <tr id="time">
                    <td style="width:5%; font-weight:bold; padding: 10px;text-align:center;"></td>
                    <?php for ($i = 0; $i <= 24; $i++) { ?>
                        <td id="<?= ($i); ?>" style="text-align: center;"><?= ($i); ?></td>
                    <?php } ?>
                </tr>

            </table>

            <div>
                <span>
                    <button id="temperature" class="art-button-green" style="color:#fff !important; height:30px !important;width:70px;" class="art-button-green">Temperature</button>
                </span>
                <span>
                    <button id="resp" class="art-button-green" style="color:#fff !important; height:30px !important;width:70px;">Resp </button>
                </span>
                <span>
                    <button id="bp" class="art-button-green" style="width:70px; height:30px !important; color:#fff !important;">Blood Pressure</button>
                </span>

                <!-- <span>
                    <input id="temp_time_open" class="art-button-green" value="Time">
                </span> -->
            </div>
        </div>
        <!--###### TEMPERATURE AND RESPIRATION SECTION ######-->

        <br>

        <hr style="border:10px solid #C0C0C0; width:90vw;">

        <!--###### PROTEIN AND ACETONE SECTION ######-->
        <div>
            <table id="table">

                <!-- <tr id="protein_time_hour">
                    <td style="width:5%; font-weight:bold; padding: 10px;text-align:center;">Time</td>
                    <?php for ($i = 0; $i <= 24; $i++) { ?>
                        <td id="<?= ($i); ?>" style="text-align: center;"></td>
                    <?php } ?>
                </tr> -->

                <tr id="protein_fill">
                    <td style="width:5%; font-weight:bold; padding: 10px;text-align:center;">Protein</td>
                    <?php for ($i = 0; $i <= 24; $i++) { ?>
                        <td id="<?= ($i); ?>" style="text-align: center;"></td>
                    <?php } ?>
                </tr>

                <tr id="acetone_fill">
                    <td style="width:5%; font-weight:bold; padding: 10px;text-align:center;">Acetone</td>
                    <?php for ($i = 0; $i <= 24; $i++) { ?>
                        <td id="<?= ($i); ?>" style="text-align: center;"></td>
                    <?php } ?>
                </tr>

                <tr id="volume_fill">
                    <td style="width:5%; font-weight:bold; padding: 10px;text-align:center;">Volume</td>
                    <?php for ($i = 0; $i <= 24; $i++) { ?>
                        <td id="<?= ($i); ?>" style="text-align: center;"></td>
                    <?php } ?>
                </tr>

                <tr id="time">
                    <td style="width:5%; font-weight:bold; padding: 10px;text-align:center;"></td>
                    <?php for ($i = 0; $i <= 24; $i++) { ?>
                        <td id="<?= ($i); ?>" style="text-align: center;"><?= ($i); ?></td>
                    <?php } ?>
                </tr>

            </table>

            <div>
                <span>
                    <button id="protein" class="art-button-green" style="color:#fff !important; height:30px !important;width:70px;" class="art-button-green">Protein</button>
                </span>

                <span>
                    <button id="acetone" class="art-button-green" style="color:#fff !important; height:30px !important;width:70px;">Acetone</button>
                </span>

                <span>
                    <button id="volume" class="art-button-green" style="width:70px; height:30px !important; color:#fff !important;">Volume</button>
                </span>

                <!-- <span>
                    <input id="protein_time_open" class="art-button-green" value="Time">
                </span> -->

            </div>
        </div>
        <!--###### END PROTEIN AND ACETONE SECTION ######-->

        <br>

        <hr style="border:10px solid #C0C0C0; width:90vw;">

        <!--###### BABY STATUS SECTION ######-->
        <table class="table table-striped table-hover" style="width:90vw;">

            <tr>
                <td colspan="7" style="text-align: center; background-color:#bdb5ac;"><b>BABY STATUS</b></td>
            </tr>

            <tr>
                <td style="text-align: center;"><b>APGAR</b></td>
                <td colspan="3" style="text-align: center;"><b>AFTER ONE MINUTE</b></td>
                <td colspan="3" style="text-align: center;"><b>AFTER FIVE MINUTES</b></td>
            </tr>

            <tr>
                <td style="text-align: center;"><b>Sign</b></td>
                <td style="text-align: center;"><b>0</b></td>
                <td style="text-align: center;"><b>1</b></td>
                <td style="text-align: center;"><b>2</b></td>
                <td style="text-align: center;"><b>0</b></td>
                <td style="text-align: center;"><b>1</b></td>
                <td style="text-align: center;"><b>2</b></td>
            </tr>

            <tr>
                <td style="text-align: center;">Heart rate </td>
                <td style="text-align: center;">None > 60 <input type="checkbox" name="heart_rate_less_60" id="heart_rate_less_60" style="margin: 5px;"></td>
                <td style="text-align: center;">Below 60 - 100 <input type="checkbox" name="heart_rate_60" id="heart_rate_60" style="margin: 5px;"></td>
                <td style="text-align: center;">Over 100 <input type="checkbox" name="heart_rate_greater_60" id="heart_rate_greater_60" style="margin: 5px;"></td>
                <td style="text-align: center;"><input type="checkbox" name="five_heart_rate_less_60" id="five_heart_rate_less_60" style="margin: 5px;"></td>
                <td style="text-align: center;"><input type="checkbox" name="five_heart_rate_60" id="five_heart_rate_60" style="margin: 5px;"></td>
                <td style="text-align: center;"> <input type="checkbox" name="five_heart_rate_greater_60" id="five_heart_rate_greater_60" style="margin: 5px;"></td>
            </tr>

            <tr>
                <td style="text-align: center;">Respiration </td>
                <td style="text-align: center;">None <input type="checkbox" name="respiration_none" id="respiration_none" style="margin: 5px;"></td>
                <td style="text-align: center;">Gasp <input type="checkbox" name="respiration_gasp" id="respiration_gasp" style="margin: 5px;"></td>
                <td style="text-align: center;">Crying <input type="checkbox" name="respiration_cry" id="respiration_cry" style="margin: 5px;"></td>
                <td style="text-align: center;"><input type="checkbox" name="five_respiration_none" id="five_respiration_none" style="margin: 5px;"></td>
                <td style="text-align: center;"><input type="checkbox" name="five_respiration_gasp" id="five_respiration_gasp" style="margin: 5px;"></td>
                <td style="text-align: center;"> <input type="checkbox" name="five_respiration_cry" id="five_respiration_cry" style="margin: 5px;"></td>
            </tr>

            <tr>
                <td style="text-align: center;">Muscle Tone </td>
                <td style="text-align: center;">None <input type="checkbox" name="muscle_tone_none" id="muscle_tone_none" style="margin: 5px;"></td>
                <td style="text-align: center;">A bil <input type="checkbox" name="muscle_tone_bil" id="muscle_tone_bil" style="margin: 5px;"></td>
                <td style="text-align: center;">Active movement <input type="checkbox" name="muscle_tone_mvt" id="muscle_tone_mvt" style="margin: 5px;"></td>
                <td style="text-align: center;"><input type="checkbox" name="five_muscle_tone_none" id="five_muscle_tone_none" style="margin: 5px;"></td>
                <td style="text-align: center;"><input type="checkbox" name="five_muscle_tone_bil" id="five_muscle_tone_bil" style="margin: 5px;"></td>
                <td style="text-align: center;"> <input type="checkbox" name="five_muscle_tone_mvt" id="five_muscle_tone_mvt" style="margin: 5px;"></td>
            </tr>

            <tr>
                <td style="text-align: center;">Reflexe </td>
                <td style="text-align: center;">None <input type="checkbox" name="reflexe_none" id="reflexe_none" style="margin: 5px;"></td>
                <td style="text-align: center;">Grimance <input type="checkbox" name="reflexe_grimance" id="reflexe_grimance" style="margin: 5px;"></td>
                <td style="text-align: center;">Cough / Sneeze <input type="checkbox" name="reflexe_cough" id="reflexe_cough" style="margin: 5px;"></td>
                <td style="text-align: center;"><input type="checkbox" name="five_reflexe_none" id="five_reflexe_none" style="margin: 5px;"></td>
                <td style="text-align: center;"><input type="checkbox" name="five_reflexe_grimance" id="five_reflexe_grimance" style="margin: 5px;"></td>
                <td style="text-align: center;"> <input type="checkbox" name="five_reflexe_cough" id="five_reflexe_cough" style="margin: 5px;"></td>
            </tr>

            <tr>
                <td style="text-align: center;">Color </td>
                <td style="text-align: center;">None <input type="checkbox" name="color_none" id="color_none" style="margin: 5px;"></td>
                <td style="text-align: center;">Blue <input type="checkbox" name="color_blue" id="color_blue" style="margin: 5px;"></td>
                <td style="text-align: center;">Pink <input type="checkbox" name="color_pink" id="color_pink" style="margin: 5px;"></td>
                <td style="text-align: center;"><input type="checkbox" name="five_color_none" id="five_color_none" style="margin: 5px;"></td>
                <td style="text-align: center;"><input type="checkbox" name="five_color_blue" id="five_color_blue" style="margin: 5px;"></td>
                <td style="text-align: center;"> <input type="checkbox" name="five_color_pink" id="five_color_pink" style="margin: 5px;"></td>
            </tr>

            <tr>
                <td colspan="3" style="text-align: center;">
                    <span>
                        <input id="one_minute" class="art-button-green" value="SAVE AFTER ONE MINUTE">
                    </span>
                </td>
                <td style="text-align: center;">
                    <span>
                        <input type="text" name="total_one_min" id="total_one_min" style="text-align: center;">
                    </span>
                </td>
                <td colspan="2" style="text-align: center;">
                    <span>
                        <input id="five_minutes" class="art-button-green" value="SAVE AFTER FIVE MINUTES">
                    </span>
                </td>
                <td style="text-align: center;">
                    <span>
                        <input type="text" name="total_five_min" id="total_five_min" style="text-align: center;">
                    </span>
                </td>
            </tr>
        </table>
        <!--###### END BABY STATUS SECTION ######-->

        <br>

        <hr style="border:10px solid #C0C0C0; width:90vw;">

        <!--######LAST SECTION ######-->
        <table class="table table-striped table-hover" style="width:90vw;">

            <tr>
                <td>SEX</td>
                <td>
                    <select name="sex" id="sex" style="padding: 6px; width: 100%;">
                        <option value="<?php if ($sex) {echo $sex;} else { echo ""; } ?>"><?php if ($sex) {echo $sex;} else { ?>Select<?php } ?></option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </td>
                <td>WEIGHT</td>
                <td>
                    <input type="text" name="weight" id="weight" value="<?= $weight; ?>" style="padding: 6px;" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                    <span style="margin-left: -30px;">gm</span>
                </td>
                <td>BABY ABNORMALITIES</td>
                <td><input type="text" name="abnormalities" id="abnormalities" value="<?= $abnormalities; ?>" style="padding: 6px;"></td>
            </tr>

            <tr>
                <td>RESUSCITATION METHOD</td>
                <td><input type="text" name="resuscitation" id="resuscitation" value="<?= $resuscitation; ?>" style="padding: 6px;"></td>
                <td colspan="2">DRUGS GIVEN TO BABY (type, dose, time)</td>
                <td colspan="2"><input type="text" name="drugs" id="drugs" value="<?= $drugs; ?>" style="padding: 6px;"></td>
            </tr>

            <tr>
                <td>PROPHYTAXIS EYE DROP OINTMENT</td>
                <td>
                    <select name="eye_drop" id="eye_drop" style="padding: 6px; width: 100%;">
                        <option value="<?php if ($eye_drop) {echo $eye_drop;} else { echo ""; } ?>"><?php if ($eye_drop) {echo $eye_drop;} else { ?>Select<?php } ?></option>
                        <option value="Given">Given</option>
                        <option value="Not Given">Not Given</option>
                    </select>
                </td>
                <td colspan="4" style="text-align: center;">
                    <span>
                        <input id="save_labour" class="art-button-green" value="SAVE">
                    </span>
                </td>
            </tr>

        </table>
        <!--###### END LAST SECTION ######-->

    </center>

</fieldset>

<?php

include("./includes/dialogs/alert_action_dialog.php");

include("./includes/dialogs/caput_dialog.php");

include("./includes/dialogs/liquor_time_dialog.php");

include("./includes/dialogs/state_of_liquor_dialog.php");

include("./includes/dialogs/moulding_dialog.php");

include("./includes/dialogs/protein_dialog.php");

include("./includes/dialogs/acetone_dialog.php");

include("./includes/dialogs/volume_dialog.php");

include("./includes/dialogs/blood_pressure_dialog.php");

include("./includes/dialogs/temperature_dialog.php");

include("./includes/dialogs/resp_dialog.php");

include("./includes/dialogs/oxytocin_dialog.php");

include("./includes/dialogs/drops_dialog.php");

include("./includes/dialogs/contraction_dialog.php");

include("./includes/dialogs/oxytocin_time_dialog.php");

include("./includes/dialogs/temperature_time_dialog.php");

include("./includes/dialogs/protein_time_dialog.php");

?>

<link rel="stylesheet" href="./css/partograph_chart_record.css">

<link rel="stylesheet" type="text/css" href="../css/jquery.datetimepicker.css">

<link rel="stylesheet" type="text/css" href="../jquery.jqplot.css" />

<link rel="stylesheet" href="../js/css/ui-lightness/jquery-ui-1.8.23.custom.css">

<script type="text/javascript" src="../js/jquery-1.8.0.min.js"></script>

<script type="text/javascript" src="../js/jquery-ui-1.8.23.custom.min.js"></script>

<script type="text/javascript" src="../jquery.min.js"></script>

<script type="text/javascript" src="../css/jquery.datetimepicker.js"></script>

<script type="text/javascript" src="../booststrapcollapse/jquery-ui.min.js"></script>

<script type="text/javascript" src="../jquery.jqplot.js"></script>

<script type="text/javascript" src="../plugins/jqplot.canvasTextRenderer.js"></script>

<script type="text/javascript" src="../plugins/jqplot.canvasAxisLabelRenderer.js"></script>

<script type="text/javascript" src="../plugins/jqplot.canvasTextRenderer.js"></script>

<script type="text/javascript" src="../plugins/jqplot.canvasAxisLabelRenderer.js"></script>

<script type="text/javascript" src="../plugins/jqplot.highlighter.js"></script>

<script type="text/javascript" src="../plugins/jqplot.pointLabels.min.js"></script>

<script type="text/javascript" src="./js/partograph_chart_record.js"></script>

<script type="text/javascript" src="./js/partograph/fetal_heart_rate.js"></script>

<script type="text/javascript" src="./js/partograph/progress_of_labor.js"></script>

<script type="text/javascript" src="./js/partograph/alert_action.js"></script>

<script type="text/javascript" src="./js/partograph/state_of_liquor.js"></script>

<script type="text/javascript" src="./js/partograph/moulding.js"></script>

<script type="text/javascript" src="./js/partograph/caput.js"></script>

<script type="text/javascript" src="./js/partograph/liquor_time.js"></script>

<script type="text/javascript" src="./js/partograph/contraction.js"></script>

<script type="text/javascript" src="./js/partograph/oxytocine.js"></script>

<script type="text/javascript" src="./js/partograph/drops.js"></script>

<script type="text/javascript" src="./js/partograph/oxytocine_time.js"></script>

<script type="text/javascript" src="./js/partograph/temperature.js"></script>

<script type="text/javascript" src="./js/partograph/resp.js"></script>

<script type="text/javascript" src="./js/partograph/blood_pressure.js"></script>

<script type="text/javascript" src="./js/partograph/temp_time.js"></script>

<script type="text/javascript" src="./js/partograph/protein.js"></script>

<script type="text/javascript" src="./js/partograph/acetone.js"></script>

<script type="text/javascript" src="./js/partograph/volume.js"></script>

<script type="text/javascript" src="./js/partograph/protein_time.js"></script>

<script type="text/javascript" src="./js/partograph/baby_status.js"></script>

<script type="text/javascript" src="./js/partograph/summary_of_labor.js"></script>

<?php include("../includes/footer.php"); ?>