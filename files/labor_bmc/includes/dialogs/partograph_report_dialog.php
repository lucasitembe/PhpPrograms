<div id="show_dialog_partograph_report">

    <br>

    <center>

        <table class="table table-striped table-hover" >

            <tr>
                <td><b>Patient RegNo.</b></td>
                <td><?= $Registration_ID; ?></td>
                <td><b>Patient Name</b></td>
                <td><?= $Patient_Name; ?></td>
            </tr>

            <tr>
                <td><b>Patient Age</b></td>
                <td><?= $age; ?> years</td>
                <td><b>Patient Gender</b></td>
                <td><?= $Gender; ?></td>
            </tr>

        </table>

        <table class="table table-striped table-hover">

            <tr>
                <td style="text-align: center; background-color:#037db0; color: #ffffff;"><b>SAVED DATE</b></td>
                <td style="text-align: center; background-color:#037db0; color: #ffffff;"><b>ACTION</b></td>
            </tr>

            <?php for ($i = 0; $i < count($partograph_array); $i++) { ?>
                <tr>
                    <td style="font-family: arial; font-size: 13px; text-align: center;"><?= $partograph_array[$i]['date'] ?></td>
                    <td style="font-family: arial; font-size: 13px; text-align: center;"><a href="partograph_report.php?consultation_id=<?= $partograph_array[$i]['consultation_id']; ?>&patient_id=<?= $patient_id; ?>&admission_id=<?= $partograph_array[$i]['admission_id']; ?>" target="_blank">PREVIEW PARTOGRAPH</a></td>
                </tr>
            <?php } ?>

        </table>

    </center>

</div>