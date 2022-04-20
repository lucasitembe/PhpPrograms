<?php
include('../includes/connection.php');
$names = array("Total IV", "Ng/Oral", "Others", "Total", "Urine", "allegies", "Vomitus", "Stool", "Chest tube", "Insensible loss", "Hourly balance");
$patient_id = (isset($_GET['patient_id'])) ? $_GET['patient_id'] : 0;

# Get from six record
$get_form_five_record = mysqli_query($conn, "SELECT * FROM tbl_icu_form_three WHERE Registration_ID = $patient_id ORDER BY id DESC");

if (mysqli_num_rows($get_form_five_record) > 0) {

    while ($raw_data = mysqli_fetch_array($get_form_five_record)) {
        $Employee_ID = $raw_data['employee_id'];
        $created_at = $raw_data['created_at'];
        $medication_time_inputs_string = explode(',', $raw_data['medication_time_inputs_string']);
        $infusion_name_string = explode(',', $raw_data['infusion_name_string']);
        $infusion_inputs_string = explode(',', $raw_data['infusion_inputs_string']);
        $name_string = explode(',', $raw_data['name_string']);

        $select_employee_name = "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Employee_ID' ";
        $select_employee_name = mysqli_query($conn, $select_employee_name);
        while ($employee_row = mysqli_fetch_array($select_employee_name)):
            $get_employee_name = $employee_row['Employee_Name'];
        endwhile;
        ?>

        <table width="100%" class="table table-striped">

            <tr>
                <td colspan="8">
                    <span><center>Done By : <b><?= $get_employee_name ?></b> Done On : <b><?= $created_at ?></b></center></span>
                </td>
            </tr>
            <tr>
                <td style="text-align: right;"><span>Primary Attending</span></td>
                <td><input readonly class="form-control input_form_third_intro" type="text" id="credential"
                           value="<?= $credential_string[0] ?>"/></td>

                <td style="text-align: right;"><span>ICU Ateending</span></td>
                <td><input readonly class="form-control input_form_third_intro" type="text" id="credential"
                           value="<?= $credential_string[1] ?>"/></td>

                <td style="text-align: right;"><span>Other attending</span></td>
                <td><input readonly class="form-control input_form_third_intro" type="text" id="credential"
                           value="<?= $credential_string[2] ?>"/></td>
            </tr>

            <tr>

                <td style="text-align: right;"><span>DOA</span></td>
                <td><input readonly class="form-control input_form_third_intro" type="text" id="credential"
                           value="<?= $credential_string[3] ?>"/></td>

                <td style="text-align: right;"><span>Days in unit</span></td>
                <td><input readonly class="form-control input_form_third_intro" type="text" id="credential"
                           value="<?= $credential_string[4] ?>"/></td>

                <td style="text-align: right;"><span>Weight</span></td>
                <td><input readonly class="form-control input_form_third_intro" type="text" id="credential"
                           value="<?= $credential_string[5] ?>"/></td>

            </tr>

            <tr>

                <td style="text-align: right;"><span>Height</span></td>
                <td><input readonly class="form-control input_form_third_intro" type="text" id="credential"
                           value="<?= $credential_string[6] ?>"/></td>

                <td style="text-align: right;"><span>Date and Time of intubation</span></td>
                <td><input readonly class="form-control input_form_third_intro" type="text" id="credential"
                           value="<?= $credential_string[7] ?>"/></td>

                <td style="text-align: right;"><span>Inubated by</span></td>
                <td><input readonly class="form-control input_form_third_intro" type="text" id="credential"
                           value="<?= $credential_string[8] ?>"/></td>
            </tr>

            <tr>
                <td style="text-align: right;"><span>Extubated by</span></td>
                <td><input readonly class="form-control input_form_third_intro" type="text" id="credential"
                           value="<?= $credential_string[9] ?>"/></td>

                <td style="text-align: right;"><span>Size</span></td>
                <td><input readonly class="form-control input_form_third_intro" type="text" id="credential"
                           value="<?= $credential_string[10] ?>"/></td>

                <td style="text-align: right;"><span>Fixation</span></td>
                <td><input readonly class="form-control input_form_third_intro" type="text" id="credential"
                           value="<?= $credential_string[11] ?>"/></td>
            </tr>

            <tr>
                <td style="text-align: right;"><span>Cuff p:</span></td>
                <td><input readonly class="form-control input_form_third_intro" type="text" id="credential"
                           value="<?= $credential_string[13] ?>"/></td>

                <td style="text-align: right;"><span>Pt Spkes person</span></td>
                <td><input readonly class="form-control input_form_third_intro" type="text" id="credential"
                           value="<?= $credential_string[14] ?>"/></td>

                <td style="text-align: right;"><span>Relationship to pt</span></td>
                <td><input readonly class="form-control input_form_third_intro" type="text" id="credential"
                           value="<?= $credential_string[15] ?>"/></td>
            </tr>

            <tr>
                <td style="text-align: right;">Mobile No.</td>
                <td><input readonly class="form-control input_form_third_intro" type="text" id="credential"
                           value="<?= $credential_string[16] ?>"/></td>
            </tr>
        </table>

        <br>


        <br>

        <table id="table-form" width=100% class="table table-striped table-bordered">
            <thead class="table-light">
            <tr style="background-color:#eee">
                <th width="20%;">IV infusion</th>
                <th>0700</th>
                <th>0800</th>
                <th>0900</th>
                <th>1000</th>
                <th>1100</th>
                <th>1200</th>
                <th>1300</th>
                <th>1400</th>
                <th>1500</th>
                <th>1600</th>
                <th>1700</th>
                <th>1800</th>
                <th>1900</th>
                <th>2000</th>
                <th>2100</th>
                <th>2200</th>
                <th>2300</th>
                <th>0000</th>
                <th>0100</th>
                <th>0200</th>
                <th>0300</th>
                <th>0400</th>
                <th>0500</th>
                <th>0600</th>
            </tr>
            </thead>
            <tbody id="infusion_section">
            <?php
            $add = 0;
            $final = 0;
            for ($i = 0;
            $i < sizeof($infusion_name_string);
            $i++) {
            $counter = sizeof($infusion_inputs_string);
            ?>
            <tr class="content-medication">
                <td width="20%;"><?= $infusion_name_string[$i] ?></td>

                <?php for ($j = 0; $j < 24; $j++) { ?>
                    <td>
                        <?= $infusion_inputs_string[$j + ($add + $final)] ?>
                    </td>
                <?php }
                $add++;
                $final = 23 * $add;
                } ?>
            </tr>


            </tbody>
        </table>

        <br>

        <table id="table-form" width="100%" class="table table-striped table-bordered">
            <thead>
            <tr style="background-color:#eee">
                <th width="20%;">Names</th>
                <th>0700</th>
                <th>0800</th>
                <th>0900</th>
                <th>1000</th>
                <th>1100</th>
                <th>1200</th>
                <th>1300</th>
                <th>1400</th>
                <th>1500</th>
                <th>1600</th>
                <th>1700</th>
                <th>1800</th>
                <th>1900</th>
                <th>2000</th>
                <th>2100</th>
                <th>2200</th>
                <th>2300</th>
                <th>0000</th>
                <th>0100</th>
                <th>0200</th>
                <th>0300</th>
                <th>0400</th>
                <th>0500</th>
                <th>0600</th>
            </tr>
            </thead>

            <tr>
                <?php
                $add = 0;
                $final = 0;
                for ($i = 0;
                $i < sizeof($names);
                $i++) {
                $counter = sizeof($name_string)
                ?>
            <tr>
                <td width="20%;"><?= $names[$i] ?></td>
                <?php for ($j = 0; $j < 24; $j++) { ?>
                    <td>
                        <?= $name_string[$j + ($add + $final)] ?>
                    </td>
                <?php }
                $add++;
                $final = 23 * $add;
                } ?>
            </tr>
            </tr>
        </table>

        <br>
    <?php } ?>


    <?php
} else {
    echo "
            <div style='color:red;padding:2em'><center><h3>Data Not Found</h3></center></div>
        ";
}
?>