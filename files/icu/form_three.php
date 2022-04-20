<?php
ini_set('display_errors', true);
include 'new_header.php';
include 'partials/get_patient_details.php';
include 'form_three_functions.php';

$date_of_admission = "";

$form_name = "MEDICATION ADMINISTRATION (eMAR)";

$now = time(); // or your date as well

$details = getFormDetails($consultation_ID, $Registration_ID, $Admission_ID);
$formId = $details['id'];
$preDetails = $details['preDetails'];
$data = $details['data'];
$metadata = $details['metadata'];
$check_if_patient_was_transfered  = mysqli_query($conn,"SELECT * FROM tbl_admission ad, `tbl_ward_rooms` wr WHERE ad.ward_room_id= wr.`ward_room_id` AND `Admision_ID`=$Admission_ID  AND Registration_ID='$Registration_ID' AND `room_type`='icu' AND Admision_ID NOT IN (SELECT Admision_ID FROM tbl_transfer_out_in) ") or die(mysqli_errno($conn).":Failed to fetch patient details");
if(mysqli_num_rows($check_if_patient_was_transfered) > 0){
	while($data = mysqli_fetch_assoc($check_if_patient_was_transfered)){
		$date_of_admission = $data['Admission_Date_Time'];

        $your_date = strtotime($date_of_admission);
        $datediff = $now - $your_date;

        $date_diff__ =  round($datediff / (60 * 60 * 24));
	}
}else{
	$get_patient_details = mysqli_query($conn,"SELECT `Received_Date` FROM `tbl_patient_transfer_details` ptd, tbl_beds b , tbl_ward_rooms wr WHERE `Admision_ID`=$Admission_ID AND b.`Bed_ID`=ptd.`Bed_ID` AND wr.ward_room_id=ptd.`room_id` AND `room_type`='icu'") or die(mysqli_error($conn)."Failed to fetch patient details : not transfered") or die(mysqli_error($conn));
        if(mysqli_num_rows($get_patient_details)>0){
            while($data = mysqli_fetch_assoc($get_patient_details)){
                $date_of_admission = $data['Received_Date'];

                $your_date = strtotime($date_of_admission);
                $datediff = $now - $your_date;

                $date_diff__ =  round($datediff / (60 * 60 * 24));
            }
        }else{
            $date_diff__ = "NOT ADMITTED IN ICU";
        }
}

//echo $date_of_admission." **";

// Repeating Rows i.e ng/oral
$infusionNames = getRowsNames($formId, 'infusion-name');
$bloodProductNames = getRowsNames($formId, 'blood-product-name');
$ngOralNames = getRowsNames($formId, 'ng-oral-name');

?>

<a href="#" id="form_three_preview_records" class="btn btn-danger font-weight-bold">PREVIOUS RECORD</a>
<a href="icu.php?consultation_ID=<?= $consultation_ID; ?>&Registration_ID=<?= $_GET['Registration_ID']; ?>&Admision_ID=<?= $Admision_ID ?>&Check_In_ID=<?=$_GET['Check_In_ID'];?>"
   class="btn btn-primary">BACK</a>
<a href='../pharmacyinpatientpage.php?Registration_ID=<?=$_GET['Registration_ID']?>&Check_In_ID=<?=$_GET['Check_In_ID']?>&Admision_ID=<?=$_GET['Admision_ID']?>&consultation_ID=<?=$_GET['consultation_ID']?>'  class='btn btn-primary' style='float:right'>Prescribe Medication</a>

<br>
<?php include "partials/new_patient_info.php"; ?>

    <div class="container-fluid bg-white pt-5 pb-3">
        <div class="row" style="width: 99%;">
            <div class="col-md-4 my-2">
                <div class="row">
                    <label class="font-weight-bold col-sm-4 col-form-label text-right pre-details-label">Primary Attending</label>
                    <div class="col-sm-8">
                        <input type="text" readonly class="form-control pre-details-data bg-white"
                               value="<?= isset($preDetails['primaryAttending']) ? $preDetails['primaryAttending'] : '' ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-4 my-2">
                <div class="row">
                    <label class="font-weight-bold col-sm-4 col-form-label text-right pre-details-label">ICU Attending</label>
                    <div class="col-sm-8">
                        <input value="<?= isset($metadata['icu-attending']) ? $metadata['icu-attending'] : '' ?>" type="text" class="form-control pre-details-data" name="icu-attending">
                    </div>
                </div>
            </div>
            <div class="col-md-4 my-2">
                <div class="row">
                    <label class="font-weight-bold col-sm-4 col-form-label text-right pre-details-label">Other Attending</label>
                    <div class="col-sm-8">
                        <input value="<?= isset($metadata['other-attending']) ? $metadata['other-attending'] : '' ?>" type="text" class="form-control pre-details-data" name="other-attending">
                    </div>
                </div>
            </div>
            <div class="col-md-4 my-2">
                <div class="row">
                    <label class="font-weight-bold col-sm-4 col-form-label text-right pre-details-label">DOA</label>
                    <div class="col-sm-8">
                        <input type="text" readonly class="form-control pre-details-data bg-white"
                               value="<?= $date_of_admission ?>">
                    </div>
                </div>
            </div>

            <div class="col-md-4 my-2">
                <div class="row">
                    <label class="font-weight-bold col-sm-4 col-form-label text-right pre-details-label">Days in unit</label>
                    <div class="col-sm-8">
                        <input type="text" readonly class="form-control pre-details-data bg-white" id="days-in-unit"
                               value="<?= $date_diff__ ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-4 my-2">
                <div class="row">
                    <label class="font-weight-bold col-sm-4 col-form-label text-right pre-details-label">Weight</label>
                    <div class="col-sm-8">
                        <input value="<?= isset($metadata['weight']) ? $metadata['weight'] : '' ?>" type="text" class="form-control pre-details-data" name="weight">
                    </div>
                </div>
            </div>
            <div class="col-md-4 my-2">
                <div class="row">
                    <label class="font-weight-bold col-sm-4 col-form-label text-right pre-details-label">Height</label>
                    <div class="col-sm-8">
                        <input value="<?= isset($metadata['height']) ? $metadata['height'] : '' ?>" type="text" class="form-control pre-details-data" name="height">
                    </div>
                </div>
            </div>
            <div class="col-md-4 my-2">
                <div class="row">
                    <label class="font-weight-bold col-sm-4 col-form-label text-right pre-details-label">Intubated At</label>
                    <div class="col-sm-8">
                        <input value="<?= isset($metadata['intubated-at']) ? $metadata['intubated-at'] : '' ?>" type="text" class="form-control" id="intubated-at" name="intubated-at">
                    </div>
                </div>
            </div>
            <div class="col-md-4 my-2">
                <div class="row">
                    <label class="font-weight-bold col-sm-4 col-form-label text-right pre-details-label">Intubated by</label>
                    <div class="col-sm-8">
                        <input value="<?= isset($metadata['intubated-by']) ? $metadata['intubated-by'] : '' ?>" type="text" class="form-control pre-details-data" name="intubated-by">
                    </div>
                </div>
            </div>
            <div class="col-md-4 my-2">
                <div class="row">
                    <label class="font-weight-bold col-sm-4 col-form-label text-right pre-details-label">Extubated by</label>
                    <div class="col-sm-8">
                        <input value="<?= isset($metadata['extubated-by']) ? $metadata['extubated-by'] : '' ?>" type="text" class="form-control pre-details-data" name="extubated-by">
                    </div>
                </div>
            </div>
            <div class="col-md-4 my-2">
                <div class="row">
                    <label class="font-weight-bold col-sm-4 col-form-label text-right pre-details-label">Size</label>
                    <div class="col-sm-8">
                        <input value="<?= isset($metadata['icu-attending']) ? $metadata['icu-attending'] : '' ?>" type="text" class="form-control pre-details-data" name="size">
                    </div>
                </div>
            </div>
            <div class="col-md-4 my-2">
                <div class="row">
                    <label class="font-weight-bold col-sm-4 col-form-label text-right pre-details-label">Fixation</label>
                    <div class="col-sm-8">
                        <input value="<?= isset($metadata['fixation']) ? $metadata['fixation'] : '' ?>" type="text" class="form-control pre-details-data" name="fixation">
                    </div>
                </div>
            </div>
            <div class="col-md-4 my-2">
                <div class="row">
                    <label class="font-weight-bold col-sm-4 col-form-label text-right pre-details-label">Cuff p</label>
                    <div class="col-sm-8">
                        <input value="<?= isset($metadata['cuff-p']) ? $metadata['cuff-p'] : '' ?>" type="text" class="form-control pre-details-data" name="cuff-p">
                    </div>
                </div>
            </div>
            <div class="col-md-4 my-2">
                <div class="row">
                    <label class="font-weight-bold col-sm-4 col-form-label text-right pre-details-label">Speaker Person</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control kin-details" id="speaker-person"
                               value="<?= $preDetails['kinName'] ?>" name="Kin_Name">
                    </div>
                </div>
            </div>
            <div class="col-md-4 my-2">
                <div class="row">
                    <label class="font-weight-bold col-sm-4 col-form-label text-right pre-details-label">Relationship</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control kin-details" id="relationship"
                               value="<?= $preDetails['kinRelation'] ?>" name="Kin_Relationship">
                    </div>
                </div>
            </div>
            <div class="col-md-4 my-2">
                <div class="row">
                    <label class="font-weight-bold col-sm-4 col-form-label text-right pre-details-label">Phone</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control kin-details" id="phone"
                               value="<?= $preDetails['kinPhone'] ?>" name="Kin_Phone">
                    </div>
                </div>
            </div>
            <div class="col-md-4 my-2">
                <div class="row">
                    <label class="font-weight-bold col-sm-4 col-form-label text-right pre-details-label">Allergies</label>
                    <div class="col-sm-8">
                        <input value="<?= isset($metadata['allergies']) ? $metadata['allergies'] : '' ?>" type="text" class="form-control pre-details-data" name="allergies">
                    </div>
                </div>
            </div>
            <div class="col-md-4 my-2">
                <div class="row">
                    <label class="font-weight-bold col-sm-4 col-form-label text-right pre-details-label">Nurse</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control pre-details-data bg-white" readonly
                               value="<?= $employeeName ?>">
                    </div>
                </div>
            </div>

        </div>

        <?php include "form_three_medication_administration.php" ?>

        <!-- IV Infusion Table -->
        <div class="table-responsive">
            <table id="table-form" class="table table-striped align-middle table-sm table-bordered">
                <thead class="table-light">
                <tr>
                    <th width="20%;">IV INFUSION</th>
                    <th class="text-center">0700</th>
                    <th class="text-center">0800</th>
                    <th class="text-center">0900</th>
                    <th class="text-center">1000</th>
                    <th class="text-center">1100</th>
                    <th class="text-center">1200</th>
                    <th class="text-center">1300</th>
                    <th class="text-center">1400</th>
                    <th class="text-center">1500</th>
                    <th class="text-center">1600</th>
                    <th class="text-center">1700</th>
                    <th class="text-center">1800</th>
                    <th class="text-center">1900</th>
                    <th class="text-center">2000</th>
                    <th class="text-center">2100</th>
                    <th class="text-center">2200</th>
                    <th class="text-center">2300</th>
                    <th class="text-center">0000</th>
                    <th class="text-center">0100</th>
                    <th class="text-center">0200</th>
                    <th class="text-center">0300</th>
                    <th class="text-center">0400</th>
                    <th class="text-center">0500</th>
                    <th class="text-center">0600</th>
                </tr>
                </thead>
                <tbody id="infusion-section">
                <?php
                foreach ($infusionNames as $name) {
                    ?>
                    <tr>
                        <td><input type="text" value="<?= $name['name'] ?>" class="form-control infusion-name tbl-input" id="infusion-name-<?= $name['index'] ?>"></td>
                        <?php
                            $id = 'infusion-input-' . $name['index'];
                            generateFields($id, 'infusion-input');
                        ?>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
                <tbody>
                <tr>
                    <td colspan="30">
                        <button class="btn btn-primary" id="add-infusion-row">ADD ROW</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Blood Products Table -->
        <div class="table-responsive">
            <table id="table-form" class="table table-striped align-middle table-sm table-bordered">
                <thead class="table-light">
                <tr>
                    <th width="20%;">BLOOD PRODUCTS</th>
                    <th class="text-center">0700</th>
                    <th class="text-center">0800</th>
                    <th class="text-center">0900</th>
                    <th class="text-center">1000</th>
                    <th class="text-center">1100</th>
                    <th class="text-center">1200</th>
                    <th class="text-center">1300</th>
                    <th class="text-center">1400</th>
                    <th class="text-center">1500</th>
                    <th class="text-center">1600</th>
                    <th class="text-center">1700</th>
                    <th class="text-center">1800</th>
                    <th class="text-center">1900</th>
                    <th class="text-center">2000</th>
                    <th class="text-center">2100</th>
                    <th class="text-center">2200</th>
                    <th class="text-center">2300</th>
                    <th class="text-center">0000</th>
                    <th class="text-center">0100</th>
                    <th class="text-center">0200</th>
                    <th class="text-center">0300</th>
                    <th class="text-center">0400</th>
                    <th class="text-center">0500</th>
                    <th class="text-center">0600</th>
                </tr>
                </thead>
                <tbody id="blood-product-section">
                <?php
                foreach ($bloodProductNames as $name) {
                    ?>
                    <tr>
                        <td><input type="text" value="<?= $name['name'] ?>" class="form-control blood-product-name tbl-input" id="blood-product-name-<?= $name['index'] ?>"></td>
                        <?php
                            $id = 'blood-product-input-' . $name['index'];
                            generateFields($id, 'blood-product-input');
                        ?>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
                <tbody>
                <tr>
                    <td colspan="30">
                        <button class="btn btn-primary" id="add-blood-product">ADD ROW</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- NG Oral Table -->
        <div class="table-responsive">
            <table id="table-form" class="table table-striped align-middle table-sm table-bordered">
                <thead class="table-light">
                <tr>
                    <th width="20%;">NG/ORAL</th>
                    <th class="text-center">0700</th>
                    <th class="text-center">0800</th>
                    <th class="text-center">0900</th>
                    <th class="text-center">1000</th>
                    <th class="text-center">1100</th>
                    <th class="text-center">1200</th>
                    <th class="text-center">1300</th>
                    <th class="text-center">1400</th>
                    <th class="text-center">1500</th>
                    <th class="text-center">1600</th>
                    <th class="text-center">1700</th>
                    <th class="text-center">1800</th>
                    <th class="text-center">1900</th>
                    <th class="text-center">2000</th>
                    <th class="text-center">2100</th>
                    <th class="text-center">2200</th>
                    <th class="text-center">2300</th>
                    <th class="text-center">0000</th>
                    <th class="text-center">0100</th>
                    <th class="text-center">0200</th>
                    <th class="text-center">0300</th>
                    <th class="text-center">0400</th>
                    <th class="text-center">0500</th>
                    <th class="text-center">0600</th>
                </tr>
                </thead>
                <tbody id="ng-oral-section">
                <?php
                foreach ($ngOralNames as $name) {
                    ?>
                    <tr>
                        <td>
                            <input type="text" value="<?= $name['name'] ?>" class="form-control ng-oral-name tbl-input" id="ng-oral-name-<?= $name['index'] ?>">
                        </td>
                        <?php
                            $id = 'ng-oral-input-' . $name['index'];
                            generateFields($id, 'ng-oral-input');
                        ?>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
                <tbody>
                <tr>
                    <td colspan="30">
                        <button class="btn btn-primary" id="add-ng-oral">ADD ROW</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>


        <div class="table-responsive">
            <table id="table-form" width="100%" class="table table-sm table-striped align-middle table-bordered table-hover">
                <thead class="table-light">
                <tr>
                    <th class="text-center" style="width: 13%;" colspan="2">INTAKE / OUTPUT</th>
                    <th class="text-center">0700</th>
                    <th class="text-center">0800</th>
                    <th class="text-center">0900</th>
                    <th class="text-center">1000</th>
                    <th class="text-center">1100</th>
                    <th class="text-center">1200</th>
                    <th class="text-center">1300</th>
                    <th class="text-center">1400</th>
                    <th class="text-center">1500</th>
                    <th class="text-center">1600</th>
                    <th class="text-center">1700</th>
                    <th class="text-center">1800</th>
                    <th class="text-center">1900</th>
                    <th class="text-center">2000</th>
                    <th class="text-center">2100</th>
                    <th class="text-center">2200</th>
                    <th class="text-center">2300</th>
                    <th class="text-center">0000</th>
                    <th class="text-center">0100</th>
                    <th class="text-center">0200</th>
                    <th class="text-center">0300</th>
                    <th class="text-center">0400</th>
                    <th class="text-center">0500</th>
                    <th class="text-center">0600</th>
                    <th class="text-center" style="width: 6%">Daily Total</th>
                </tr>
                </thead>

                <tbody>
                <tr>
                    <td rowspan="2" width="1%;" class="upright text-center font-weight-bold table-light">INTAKE</td>
                    <td class="text-center input-label">Total IV</td>
                    <?php generateFields('total-iv', 'total-iv', null, 'readonly') ?>

                    <td class="text-center align-bottom">
                        <input class="form-control bg-none border-0 px-0 text-center pre-details-data" name="daily-total-iv" id="daily-total-iv"
                               value="<?= isset($metadata['daily-total-iv']) ? $metadata['daily-total-iv'] : '' ?>">
                    </td>
                </tr>

                <tr>
                    <td class="text-center input-label">Total Intake</td>
                    <?php generateFields('input-total', 'input-total', null, 'readonly') ?>

                    <td class="text-center align-bottom">
                        <input class="form-control bg-none border-0 px-0 text-center pre-details-data" name="daily-total-intake" id="daily-total-intake"
                               value="<?= isset($metadata['daily-total-intake']) ? $metadata['daily-total-intake'] : '' ?>">
                    </td>
                </tr>

                <tr>
                    <td class="text-center upright font-weight-bold table-light" rowspan="6">OUTPUT</td>
                    <td class="text-center output-label">Urine</td>
                    <?php generateFields('urine-output', 'output') ?>

                    <td rowspan="6" class="text-center align-bottom">
                        <input class="form-control bg-none border-0 px-0 text-center pre-details-data" name="daily-output-total" id="daily-output-total"
                               value="<?= isset($metadata['daily-output-total']) ? $metadata['daily-output-total'] : '' ?>">
                    </td>
                </tr>

                <tr>
                    <td class="text-center output-label">Vomitus</td>
                    <?php generateFields('vomitus-output', 'output') ?>

                </tr>

                <tr>
                    <td class="text-center output-label">Stool</td>
                    <?php generateFields('stool-output', 'output') ?>

                </tr>

                <tr>
                    <td class="text-center output-label">Chest Tube</td>
                    <?php generateFields('chest-tube', 'output') ?>

                </tr>

                <tr>
                    <td class="text-center output-label">Insensible Loss</td>
                    <?php generateFields('insensible-loss', 'output') ?>

                </tr>

                <tr>
                    <td class="text-center output-label">Total Output</td>
                    <?php generateFields('output-total', 'output-total', null, 'readonly') ?>

                </tr>

                <tr>
                    <td class="text-center output-label" colspan="2">Hourly Balance</td>
                    <?php generateFields('balance-hourly', 'balance', null, 'readonly') ?>

                    <td id="daily-hourly-balance" class="text-center table-secondary">
                        <?= isset($metadata['24-hrs-fluid-balance']) ? $metadata['24-hrs-fluid-balance'] : '' ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="row pt-3">
            <div class="row form-group col-md-6 my-2">
                <label class="col-sm-4 col-form-label text-right post-details-label">24 Hrs Fluid Balance </label>
                <div class="col-sm-8">
                    <input value="<?= isset($metadata['24-hrs-fluid-balance']) ? $metadata['24-hrs-fluid-balance'] : '' ?>"
                            class="form-control pre-details-data" readonly type="text"
                            placeholder="24 Hrs Fluid Balance" id="24-hrs-fluid-balance" name="24-hrs-fluid-balance">
                </div>
            </div>
            <div class="row form-group col-md-6 my-2">
                <label class="col-sm-4 col-form-label text-right post-details-label">Previous Accumulated Balance</label>
                <div class="col-sm-8">
                    <input value="<?= isset($metadata['accumulated-balance']) ? $metadata['accumulated-balance'] : '' ?>"
                           class="form-control pre-details-data" type="text" placeholder="Previous Accumulated Balance"
                           id="accumulated_balance" name="accumulated-balance">
                </div>
            </div>
            <div class="row form-group col-md-6 my-2">
                <label class="col-sm-4 col-form-label text-right post-details-label">Day Accumulated Balance</label>
                <div class="col-sm-8">
                    <input value="<?= isset($metadata['day-accumulated']) ? $metadata['day-accumulated'] : '' ?>"
                           class="form-control pre-details-data" type="text" placeholder="Day Accumulated Balance"
                           id="day_accumulated" name="day-accumulated">
                </div>
            </div>
            <div class="row form-group col-md-6 my-2">
                <label class="col-sm-4 col-form-label text-right post-details-label">Fluid Restriction</label>
                <div class="col-sm-8">
                    <input value="<?= isset($metadata['fluid-restriction']) ? $metadata['fluid-restriction'] : '' ?>"
                            class="form-control pre-details-data" type="text" placeholder="Fluid Restriction"
                           id="fluid_restriction" name="fluid-restriction">
                </div>
            </div>

        </div>

    </div>

    <div id="form_three_records"></div>

    <script>
        // Initialization, calculations and preview
        $(document).ready(function (){
           $('#intubated-at').datetimepicker();
           $('#doa').datetimepicker({timepicker: true});

           // Calculate Total IV and trigger change
           $(document.body).on('keyup', '.blood-product-input, .infusion-input', function () {

               var details = getInputDetails($(this));

               var totalBlood = getTotal('.blood-product-input-' + details.hour);
               var totalInfusion = getTotal('.infusion-input-' + details.hour);
               console.log(totalInfusion);
               console.log($(this).val());

               $('#total-iv-' + details.hour).val(totalBlood + totalInfusion).trigger('keyup');

               // Daily Total IV
               $('#daily-total-iv').val(getTotal('.total-iv')).trigger('keyup');
           });

           // Calculate Total Input
           $(document.body).on('keyup', '.total-iv, .ng-oral-input', function () {
               var details = getInputDetails($(this));

               var totalInput = getTotal('.total-iv-' + details.hour);
               var totalNgOral = getTotal('.ng-oral-input-' + details.hour);

               $('#input-total-' + details.hour).val(totalInput + totalNgOral).trigger('keyup');

               // Daily total
               $('#daily-total-intake').val(getTotal('.input-total')).trigger('keyup');
           });

           // Calculate Total Output
           $(document.body).on('keyup', '.output', function () {
               var details = getInputDetails($(this));

               var totalOutput = getTotal('.output-' + details.hour);

               $('#output-total-' + details.hour).val(totalOutput).trigger('keyup');

               // Daily Output
               $('#daily-output-total').val(getTotal('.output-total')).trigger('keyup');
           });

           // Calculate hourly balance and 24 hourly balance
           $(document.body).on('keyup', '.output-total, .input-total', function () {
               var id = $(this).prop('id');
               var hour = id.split('-')[2];

               var balanceHourly = $('#input-total-' + hour).val() - $('#output-total-' + hour).val();

               $('#balance-hourly-' + hour).val(balanceHourly).trigger('keyup');
           })

           // Calculate Daily Hourly Balance
           $(document.body).on('keyup', '.balance', function () {
               var balanceDaily = getTotal('.balance');
               $('#24-hrs-fluid-balance').val(balanceDaily).trigger('keyup');
               $('#daily-hourly-balance').html(balanceDaily);
           });


           $('#add-blood-product').click(function () {
               var bloodProductsRow = '<?= count($ngOralNames) ?>';

               var canAdd = true;

               $('.blood-product-name').map(function () {
                   if (!$(this).val()) {
                       canAdd = false;
                       alert('Please fill the available blood products inputs first');
                       return;
                   }
               })

               if (!canAdd) return;

               bloodProductsRow++;

               $('#blood-product-section').append(getDynamicFields('blood-product', bloodProductsRow));
           });

           $('#add-infusion-row').click(function (){
               var infusionRows = '<?= count($infusionNames) ?>';

               var canAdd = true;

               $('.infusion-name').map(function () {
                   if (!$(this).val()) {
                       canAdd = false;
                       alert('Please fill the available infusion inputs first');
                       return;
                   }
               })

               if (!canAdd) return;

               infusionRows++;

               $('#infusion-section').append(getDynamicFields('infusion', infusionRows));
           });

           $('#add-ng-oral').click(function () {
               var ngOralRows = '<?= count($ngOralNames) ?>';

               var canAdd = true;

               $('.ng-oral-name').map(function () {
                   if (!$(this).val()) {
                       canAdd = false;
                       alert('Please fill the available ng/oral inputs first');
                       return;
                   }
               })

               if (!canAdd) return;

               ngOralRows++;

               $('#ng-oral-section').append(getDynamicFields('ng-oral', ngOralRows));
           });


            // Preview Records
            $('#form_three_preview_records').click(function (){
                var Registration_ID = '<?= $Registration_ID ?>';
                var consultation_ID = '<?= $consultation_ID ?>';

                $.get(
                    'form_three_preview_list.php', {
                        records_list: 'form_three',
                        Registration_ID: Registration_ID,
                        consultation_ID: consultation_ID
                    }, function (response){
                        $("#form_three_records").dialog({
                            title: "ICU FORM THREE RECORDS LIST",
                            width: "60%",
                            height: 500,
                            modal: true
                        });
                        $("#form_three_records").html(response);
                        $("#form_three_records").dialog("open");
                    }
                );
            })
        });

    </script>

    <script>
        // Saving Data
        $(document).ready(function () {
            var Registration_ID = '<?= $Registration_ID ?>';
            var consultation_ID = '<?= $consultation_ID ?>';
            var Admision_ID = '<?= $Admision_ID ?>';
            var form_id = '<?= $formId ?>';

            $(document.body).on('keyup', '.tbl-input', function () {
                var details = getInputDetails(this);

                $.post('form_three_store.php', {
                    form_id: form_id,
                    consultation_ID: consultation_ID,
                    Registration_ID: Registration_ID,
                    name: details.name,
                    hour: details.hour,
                    value: details.value,
                    store: 'form-three'
                }, function (response){
                    console.log(response)
                })
            });

            $('.pre-details-data').on('keyup', function (){
                var name = $(this).attr('name');
                var value = $(this).val();

                $.post('form_three_store.php', {
                    form_id: form_id,
                    consultation_ID: consultation_ID,
                    Registration_ID: Registration_ID,
                    name: name,
                    value: value,
                    store: 'form-three-metadata'
                }, function (response){
                    console.log(response)
                })

            });

            $('#intubated-at').on('change', function (){
                var name = $(this).attr('name');
                var value = $(this).val();

                $.post('form_three_store.php', {
                    form_id: form_id,
                    consultation_ID: consultation_ID,
                    Registration_ID: Registration_ID,
                    name: name,
                    value: value,
                    store: 'form-three-metadata'
                }, function (response){
                    console.log(response)
                })

            });

            $('.kin-details').on('keyup', function (){
                var name = $(this).attr('name');
                var value = $(this).val();

                $.post('form_three_store.php', {
                    Admision_ID: Admision_ID,
                    name: name,
                    value: value,
                    store: 'form-three-kin-details'
                }, function (response){
                    console.log(response)
                })

            });
        })
    </script>

    <script>
        function getTotal(selector) {
            var total = 0;
            $(selector).map(function () {
                var val = parseFloat($(this).val());
                if (isNaN(val)) {
                    val = 0;
                }
                total += val;
            })
            return total;
        }

        function getInputDetails(input) {
            var id = $(input).prop('id');
            var value = $(input).val();
            var splitted = id.split('-');
            var hour = splitted[splitted.length - 1];
            var name = '';
            for (var i = 0; i < splitted.length - 1; i++) {
                name += splitted[i];
                name += '-'
            }
            name = name.substring(0, name.length - 1)

            return {id: id, value: value, hour: hour, name: name};
        }

        function getDynamicFields(name, index){
            var hour = 7;
            var className = name + "-input form-control px-1 text-center tbl-input";

            var htm = "<tr>";
            htm += "<td><input type='text' class='form-control " + name + "-name tbl-input' id='" + name + "-name-" + index + "'></td>";

            for (var i = 0; i < 24; i++) {
                htm += "<td><input class='" + className + " " + name  + "-input-" + hour + "' type='text' id='" + name + "-input-" + index + "-" + hour + "'/></td>";
                hour === 23 ? hour = -1 : '';
                hour++;
            }

            return htm + "</tr>";
        }

    </script>

    <link rel="stylesheet" type="text/css" href="../css/jquery.datetimepicker.css"/>
    <!--<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">-->
    <!--<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">-->
    <script src="../media/js/jquery.js" type="text/javascript"></script>
    <script src="../media/js/jquery.dataTables.js" type="text/javascript"></script>
    <script src="../css/jquery.datetimepicker.js" type="text/javascript"></script>
    <script src="../css/jquery-ui.js"></script>


<?php include("footer.php"); ?>
