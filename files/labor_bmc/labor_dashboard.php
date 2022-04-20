<?php

include("./includes/header.php");

include("./includes/check_session.php");

include("./handlers/patient.php");

list($Sponsor_ID, $Member_Number, $Patient_Name, $Registration_ID, $Gender, $Guarantor_Name, $age) = get_patient_info($patient_id, $conn);

$partograph_array = get_labor_summary($patient_id, $conn);

$check_gender = '';

if (strtolower($Gender) == 'male') {
    $check_gender = "onclick='notifyUser(this)'";
}

?>

<a href="../nursecommunicationpage.php?consultation_ID=<?= $consultation_id; ?>&Registration_ID=<?= $patient_id; ?>&Admision_ID=<?= $admision_id; ?>" class="art-button-green">BACK</a>

<fieldset>

    <legend style="font-weight:bold" align=center>

        <div style="height:34px;margin:0px;padding:0px;font-weight:bold">

            <p style="margin:0px;padding:0px;">LABOUR ANTENATAL RECORD</p>

            <p style="color:yellow;margin:0px;padding:0px; ">

                <span><?= $Patient_Name; ?></span> |

                <span><?= $Gender; ?></span> |

                <span><?= $age; ?></span> |

                <span><?= $Guarantor_Name; ?></span>

            </p>

        </div>

    </legend>

    <center>

        <table width=80%>

            <tr>
                <td style="text-align: center; height: 40px; width: 33%;">
                    <a href="obstretic_record.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id; ?>">
                        <button style="width: 100%; height: 100%">
                            Obstretic Record
                        </button>
                    </a>
                </td>

                <td style="text-align: center; height: 40px; width: 33%;">
                    <a href="labor_record.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id; ?>">
                        <button style="width: 100%; height: 100%">
                            Labour Record
                        </button>
                    </a>
                </td>

                <td style="text-align: center; height: 40px; width: 33%;">
                    <a href="partograph_chart_record.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id; ?>">
                        <button style="width: 100%; height: 100%">
                            Partograph Chart
                        </button>
                    </a>
                </td>
            </tr>

            <tr>
                <td style="text-align: center; height: 40px; width: 33%;">
                    <a href="first_stage_of_labor.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id; ?>">
                        <button style="width: 100%; height: 100%">
                            First state of Labour
                        </button>
                    </a>
                </td>

                <td style="text-align: center; height: 40px; width: 33%;">
                    <a href="second_stage_of_labor.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id; ?>">
                        <button style="width: 100%; height: 100%">
                            Second state of Labour
                        </button>
                    </a>
                </td>

                <td style="text-align: center; height: 40px; width: 33%;">
                    <a href="third_stage_of_labor.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id; ?>">
                        <button style="width: 100%; height: 100%">
                            Third state of Labour
                        </button>
                    </a>
                </td>
            </tr>

            <tr>
                <td style="text-align: center; height: 40px; width: 33%;">
                    <a href="fourth_stage_of_labor.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id; ?>">
                        <button style="width: 100%; height: 100%">
                            Fourth state of Labour
                        </button>
                    </a>
                </td>

                <td style="text-align: center; height: 40px; width: 33%;">
                    <a href="baby_record.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id; ?>">
                        <button style="width: 100%; height: 100%">
                            Baby's Record
                        </button>
                    </a>
                </td>

                <td style="text-align: center; height: 40px; width: 33%;">
                    <a href="post_natal_record.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id; ?>">
                        <button style=" width: 100%; height: 100%">
                            Post Natal Record on Discharge
                        </button>
                    </a>
                </td>
            </tr>

            <tr>
                <td style="text-align: center; height: 40px; width: 33%;">
                    <a href="neonatal_record.php?consultation_id=<?= $consultation_id; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $admision_id; ?>">
                        <button style="width: 100%; height: 100%">
                            Neonatal Record
                        </button>
                    </a>
                </td>
                <td style="text-align: center; height: 40px; width: 33%;">
                    <a>
                        <button id="partograph_report_open" style="width: 100%; height: 100%">
                            Partograph Chart Report
                        </button>
                    </a>
                </td>
            </tr>

        </table>

    </center>

</fieldset>

<?php include("./includes/dialogs/partograph_report_dialog.php"); ?>

<script type="text/javascript" src="./js/partograph_chart_report.js"></script>

<?php include("../includes/footer.php"); ?>