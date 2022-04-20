<?php

include("./includes/header.php");

include("./includes/check_session.php");

include("./handlers/patient.php");

list($Sponsor_ID, $Member_Number, $Patient_Name, $Registration_ID, $Gender, $Guarantor_Name, $age) = get_patient_info($patient_id, $conn);

?>

<fieldset>

    <legend style="font-weight:bold" align=center>

        <div style="height:34px;margin:0px;padding:0px;font-weight:bold">

            <p style="margin:0px;padding:0px;" align=center>PARTOGRAPH CHART REPORT</p>

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
        <!--###### END FETAL HEART RATE SECTION ######-->

        <hr style="border:10px solid #C0C0C0; width:90vw;">

        <!--###### LIQUOR STATE SECTION ######-->
        <div>
            <table id="table" width="100%;">

                <tr id="liquor_time_hour">
                    <td style="width:5%; font-weight:bold; padding: 10px;text-align:center;">Time</td>
                    <?php for ($i = 0; $i <= 24; $i++) { ?>
                        <td id="<?= ($i); ?>" style="text-align: center;"></td>
                    <?php } ?>
                </tr>
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
        <!--###### END LIQUOR STATE SECTION ######-->

        <br><br><br><br><br><br><br><br><br><br>

        <hr style="border:10px solid #C0C0C0; width:90vw;">

        <!--###### PROGRESS OF LABOR SECTION ######-->
        <h4 style="margin: 10px;">Progress Of Labor</h4>

        <div id="progress_of_labor_chart"></div>

        <!--###### END PROGRESS OF LABOR SECTION ######-->

        <hr style="border:10px solid #C0C0C0; width:90vw;">

        <!--###### CONTRACTION SECTION ######-->
        <div class="contraction">

            <h4>Contraction</h4>

            <div class="arrange-table">

                <table id="table">

                    <tr id="contraction_time_hour">
                        <td style="width:5%; font-weight:bold; padding: 10px;text-align: center;">Time</td>
                        <?php for ($i = 0; $i <= 24; $i++) { ?>
                            <td id="<?= ($i); ?>" style="text-align: center;"></td>
                        <?php } ?>
                    </tr>

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

            </div>

        </div>
        <!--###### END CONTRACTION SECTION ######-->

        <hr style="border:10px solid #C0C0C0; width:90vw;">

        <!--###### OXYTOCIN SECTION ######-->
        <div>
            <table id="table">

                <tr id="oxytocin_time_hour">
                    <td style="width:5%; font-weight:bold; padding: 10px;text-align:center;">Time</td>
                    <?php for ($i = 0; $i <= 24; $i++) { ?>
                        <td id="<?= ($i); ?>" style="text-align: center;"></td>
                    <?php } ?>
                </tr>
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
        </div>
        <!--###### END OXYTOCIN SECTION ######-->

        <br><br><br><br><br><br><br><br>

        <hr style="border:10px solid #C0C0C0; width:90vw;">

        <!--###### TEMPERATURE AND RESPIRATION SECTION ######-->
        <div>
            <table id="table">

                <tr id="temp_time_hour">
                    <td style="width:5%; font-weight:bold; padding: 10px;text-align:center;">Time</td>
                    <?php for ($i = 0; $i <= 24; $i++) { ?>
                        <td id="<?= ($i); ?>" style="text-align: center;"></td>
                    <?php } ?>
                </tr>

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
        </div>
        <!--###### TEMPERATURE AND RESPIRATION SECTION ######-->

        <br><br><br><br><br><br><br><br><br><br>

        <hr style="border:10px solid #C0C0C0; width:90vw;">

        <!--###### PROTEIN AND ACETONE SECTION ######-->
        <div>
            <table id="table">

                <tr id="protein_time_hour">
                    <td style="width:5%; font-weight:bold; padding: 10px;text-align:center;">Time</td>
                    <?php for ($i = 0; $i <= 24; $i++) { ?>
                        <td id="<?= ($i); ?>" style="text-align: center;"></td>
                    <?php } ?>
                </tr>

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
        </div>
        <!--###### END PROTEIN AND ACETONE SECTION ######-->

    </center>

</fieldset>

<link rel="stylesheet" href="./css/partograph_chart_record.css">

<link rel="stylesheet" type="text/css" href="../css/jquery.datetimepicker.css">

<link rel="stylesheet" href="../css/select2.min.css" media="screen">

<link rel="stylesheet" type="text/css" href="../jquery.jqplot.css" />

<link rel="stylesheet" href="../js/css/ui-lightness/jquery-ui-1.8.23.custom.css">

<script type="text/javascript" src="../js/jquery-1.8.0.min.js"></script>

<script type="text/javascript" src="../js/jquery-ui-1.8.23.custom.min.js"></script>

<script type="text/javascript" src="../jquery.min.js"></script>

<script type="text/javascript" src="../css/jquery.datetimepicker.js"></script>

<script src="../js/select2.min.js"></script>

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

<?php include("../includes/footer.php"); ?>
