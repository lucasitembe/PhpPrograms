<?php
ini_set('display_errors', true);
include('new_header.php');
include 'form_three_functions.php';
include 'partials/get_patient_details.php';
$form_name = "MEDICATION ADMINISTRATION PREVIEW";

if (isset($_GET['record_id'])) {
    $recordId = $_GET['record_id'];
} else {
    $recordId = '';
}

$data = getFormData($recordId);
$metadata = getFormMetadata($recordId);
$preDetails = getPreDetailsFromFormId($recordId);
$users = getFormUsers($recordId);
$infusionNames = getRowsNames($recordId, 'infusion-name');
$bloodProductNames = getRowsNames($recordId, 'blood-product-name');
$ngOralNames = getRowsNames($recordId, 'ng-oral-name');
echo "&nbsp;&nbsp;&nbsp;<input type='button' class='btn btn-success' onclick='close_window()' value='BACK'>";

include 'partials/new_patient_info.php';

?>
<script>
	function close_window(){
		close()
	}
</script>
<div class="pt-5 bg-white">
    <div class="row" style="width: 99%;">
        <div class="col-md-4 my-2">
            <div class="row">
                <label class="fw-bold col-sm-4 col-form-label text-end pre-details-label">Primary Attending</label>
                <div class="col-sm-8">
                    <input type="text" readonly class="form-control pre-details-data bg-white"
                           value="<?= isset($preDetails['primaryAttending']) ? $preDetails['primaryAttending'] : '' ?>">
                </div>
            </div>
        </div>
        <div class="col-md-4 my-2">
            <div class="row">
                <label class="fw-bold col-sm-4 col-form-label text-end pre-details-label">ICU Attending</label>
                <div class="col-sm-8">
                    <input value="<?= isset($metadata['icu-attending']) ? $metadata['icu-attending'] : '' ?>" type="text" class="form-control pre-details-data" name="icu-attending">
                </div>
            </div>
        </div>
        <div class="col-md-4 my-2">
            <div class="row">
                <label class="fw-bold col-sm-4 col-form-label text-end pre-details-label">Other Attending</label>
                <div class="col-sm-8">
                    <input value="<?= isset($metadata['other-attending']) ? $metadata['other-attending'] : '' ?>" type="text" class="form-control pre-details-data" name="other-attending">
                </div>
            </div>
        </div>
        <div class="col-md-4 my-2">
            <div class="row">
                <label class="fw-bold col-sm-4 col-form-label text-end pre-details-label">DOA</label>
                <div class="col-sm-8">
                    <input type="text" readonly class="form-control pre-details-data bg-white"
                           value="<?= $preDetails['doa'] ?>">
                </div>
            </div>
        </div>

        <div class="col-md-4 my-2">
            <div class="row">
                <label class="fw-bold col-sm-4 col-form-label text-end pre-details-label">Days in unit</label>
                <div class="col-sm-8">
                    <input type="text" readonly class="form-control pre-details-data bg-white" id="days-in-unit"
                           value="<?= $preDetails['daysInUnits'] ?>">
                </div>
            </div>
        </div>
        <div class="col-md-4 my-2">
            <div class="row">
                <label class="fw-bold col-sm-4 col-form-label text-end pre-details-label">Weight</label>
                <div class="col-sm-8">
                    <input value="<?= isset($metadata['weight']) ? $metadata['weight'] : '' ?>" type="text" class="form-control pre-details-data" name="weight">
                </div>
            </div>
        </div>
        <div class="col-md-4 my-2">
            <div class="row">
                <label class="fw-bold col-sm-4 col-form-label text-end pre-details-label">Height</label>
                <div class="col-sm-8">
                    <input value="<?= isset($metadata['height']) ? $metadata['height'] : '' ?>" type="text" class="form-control pre-details-data" name="height">
                </div>
            </div>
        </div>
        <div class="col-md-4 my-2">
            <div class="row">
                <label class="fw-bold col-sm-4 col-form-label text-end pre-details-label">Intubated At</label>
                <div class="col-sm-8">
                    <input value="<?= isset($metadata['intubated-at']) ? $metadata['intubated-at'] : '' ?>" type="text" class="form-control" id="intubated-at" name="intubated-at">
                </div>
            </div>
        </div>
        <div class="col-md-4 my-2">
            <div class="row">
                <label class="fw-bold col-sm-4 col-form-label text-end pre-details-label">Intubated by</label>
                <div class="col-sm-8">
                    <input value="<?= isset($metadata['intubated-by']) ? $metadata['intubated-by'] : '' ?>" type="text" class="form-control pre-details-data" name="intubated-by">
                </div>
            </div>
        </div>
        <div class="col-md-4 my-2">
            <div class="row">
                <label class="fw-bold col-sm-4 col-form-label text-end pre-details-label">Extubated by</label>
                <div class="col-sm-8">
                    <input value="<?= isset($metadata['extubated-by']) ? $metadata['extubated-by'] : '' ?>" type="text" class="form-control pre-details-data" name="extubated-by">
                </div>
            </div>
        </div>
        <div class="col-md-4 my-2">
            <div class="row">
                <label class="fw-bold col-sm-4 col-form-label text-end pre-details-label">Size</label>
                <div class="col-sm-8">
                    <input value="<?= isset($metadata['icu-attending']) ? $metadata['icu-attending'] : '' ?>" type="text" class="form-control pre-details-data" name="size">
                </div>
            </div>
        </div>
        <div class="col-md-4 my-2">
            <div class="row">
                <label class="fw-bold col-sm-4 col-form-label text-end pre-details-label">Fixation</label>
                <div class="col-sm-8">
                    <input value="<?= isset($metadata['fixation']) ? $metadata['fixation'] : '' ?>" type="text" class="form-control pre-details-data" name="fixation">
                </div>
            </div>
        </div>
        <div class="col-md-4 my-2">
            <div class="row">
                <label class="fw-bold col-sm-4 col-form-label text-end pre-details-label">Cuff p</label>
                <div class="col-sm-8">
                    <input value="<?= isset($metadata['cuff-p']) ? $metadata['cuff-p'] : '' ?>" type="text" class="form-control pre-details-data" name="cuff-p">
                </div>
            </div>
        </div>
        <div class="col-md-4 my-2">
            <div class="row">
                <label class="fw-bold col-sm-4 col-form-label text-end pre-details-label">Speaker Person</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control kin-details" id="speaker-person"
                           value="<?= $preDetails['kinName'] ?>" name="Kin_Name">
                </div>
            </div>
        </div>
        <div class="col-md-4 my-2">
            <div class="row">
                <label class="fw-bold col-sm-4 col-form-label text-end pre-details-label">Relationship</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control kin-details" id="relationship"
                           value="<?= $preDetails['kinRelation'] ?>" name="Kin_Relationship">
                </div>
            </div>
        </div>
        <div class="col-md-4 my-2">
            <div class="row">
                <label class="fw-bold col-sm-4 col-form-label text-end pre-details-label">Phone</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control kin-details" id="phone"
                           value="<?= $preDetails['kinPhone'] ?>" name="Kin_Phone">
                </div>
            </div>
        </div>
        <div class="col-md-4 my-2">
            <div class="row">
                <label class="fw-bold col-sm-4 col-form-label text-end pre-details-label">Allergies</label>
                <div class="col-sm-8">
                    <input value="<?= isset($metadata['allergies']) ? $metadata['allergies'] : '' ?>" type="text" class="form-control pre-details-data" name="allergies">
                </div>
            </div>
        </div>
        <div class="col-md-4 my-2">
            <div class="row">
                <label class="fw-bold col-sm-4 col-form-label text-end pre-details-label">Nurse</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control pre-details-data bg-white" readonly
                           value="<?= $employeeName ?>">
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-12 mt-3 mb-2">
            <table id="table-form" class="table table-striped align-middle table-sm table-bordered">
                <thead class="table-light">
                <tr>
                    <th width="15%;" class="text-center">IV INFUSION</th>
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
                <tbody>
                <?php
                foreach ($infusionNames as $infusion) {
                    ?>
                    <tr>
                        <td><?= $infusion['name'] ?></td>
                        <?php loopData('infusion-input-' . $infusion['index']); ?>
                    </tr>
                    <?php
                }
                ?>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-12 mt-3 mb-2">
            <table id="table-form" class="table table-striped align-middle table-sm table-bordered">
                <thead class="table-light">
                <tr>
                    <th width="15%;" class="text-center">BLOOD PRODUCT</th>
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
                <tbody>
                <?php
                foreach ($bloodProductNames as $bloodProduct) {
                    ?>
                    <tr>
                        <td><?= $bloodProduct['name'] ?></td>
                        <?php loopData('blood-product-input-' . $bloodProduct['index']); ?>
                    </tr>
                    <?php
                }
                ?>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-12 mt-3 mb-2">
            <table id="table-form" class="table table-striped align-middle table-sm table-bordered">
                <thead class="table-light">
                <tr>
                    <th width="15%;" class="text-center">NG/ORAL</th>
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
                <tbody>
                <?php
                foreach ($ngOralNames as $ngOral) {
                    ?>
                    <tr>
                        <td><?= $ngOral['name'] ?></td>
                        <?php loopData('ng-oral-input-' . $ngOral['index']); ?>
                    </tr>
                    <?php
                }
                ?>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="table-responsive">
        <table id="table-form" width="100%" class="table table-sm table-striped align-middle table-bordered">
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
                <td rowspan="2" width="1%;" class="upright text-center fw-bold table-light">INTAKE</td>
                <td class="text-center input-label">Total IV</td>
                <?php loopData('total-iv', 'total-iv', null, 'readonly') ?>

                <td class="text-center align-bottom">
                    <input class="form-control bg-none border-0 px-0 text-center pre-details-data" name="daily-total-iv" id="daily-total-iv"
                           value="<?= isset($metadata['daily-total-iv']) ? $metadata['daily-total-iv'] : '' ?>">
                </td>
            </tr>

            <tr>
                <td class="text-center input-label">Total Intake</td>
                <?php loopData('input-total', 'input-total', null, 'readonly') ?>

                <td class="text-center align-bottom">
                    <input class="form-control bg-none border-0 px-0 text-center pre-details-data" name="daily-total-intake" id="daily-total-intake"
                           value="<?= isset($metadata['daily-total-intake']) ? $metadata['daily-total-intake'] : '' ?>">
                </td>
            </tr>

            <tr>
                <td class="text-center upright fw-bold table-light" rowspan="6">OUTPUT</td>
                <td class="text-center output-label">Urine</td>
                <?php loopData('urine-output', 'output') ?>

                <td rowspan="6" class="text-center align-bottom">
                    <input class="form-control bg-none border-0 px-0 text-center pre-details-data" name="daily-output-total" id="daily-output-total"
                           value="<?= isset($metadata['daily-output-total']) ? $metadata['daily-output-total'] : '' ?>">
                </td>
            </tr>

            <tr>
                <td class="text-center output-label">Vomitus</td>
                <?php loopData('vomitus-output', 'output') ?>

            </tr>

            <tr>
                <td class="text-center output-label">Stool</td>
                <?php loopData('stool-output', 'output') ?>

            </tr>

            <tr>
                <td class="text-center output-label">Chest Tube</td>
                <?php loopData('chest-tube', 'output') ?>

            </tr>

            <tr>
                <td class="text-center output-label">Insensible Loss</td>
                <?php loopData('insensible-loss', 'output') ?>

            </tr>

            <tr>
                <td class="text-center output-label">Total Output</td>
                <?php loopData('output-total', 'output-total', null, 'readonly') ?>

            </tr>

            <tr>
                <td class="text-center output-label" colspan="2">Hourly Balance</td>
                <?php loopData('balance-hourly', 'balance', null, 'readonly') ?>

                <td id="daily-hourly-balance" class="text-center table-secondary">
                    <?= isset($metadata['24-hrs-fluid-balance']) ? $metadata['24-hrs-fluid-balance'] : '' ?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="row pt-3">
        <div class="row form-group col-md-6 my-2">
            <label class="col-sm-4 col-form-label text-end post-details-label">24 Hrs Fluid Balance </label>
            <div class="col-sm-8">
                <input value="<?= isset($metadata['24-hrs-fluid-balance']) ? $metadata['24-hrs-fluid-balance'] : '' ?>"
                       class="form-control pre-details-data bg-white" readonly type="text"
                       placeholder="24 Hrs Fluid Balance" id="24-hrs-fluid-balance" name="24-hrs-fluid-balance">
            </div>
        </div>
        <div class="row form-group col-md-6 my-2">
            <label class="col-sm-4 col-form-label text-end post-details-label">Previous Accumulated Balance</label>
            <div class="col-sm-8">
                <input value="<?= isset($metadata['accumulated-balance']) ? $metadata['accumulated-balance'] : '' ?>"
                       class="form-control pre-details-data bg-white" type="text" placeholder="Previous Accumulated Balance"
                       id="accumulated_balance" name="accumulated-balance" readonly>
            </div>
        </div>
        <div class="row form-group col-md-6 my-2">
            <label class="col-sm-4 col-form-label text-end post-details-label">Day Accumulated Balance</label>
            <div class="col-sm-8">
                <input value="<?= isset($metadata['day-accumulated']) ? $metadata['day-accumulated'] : '' ?>"
                       class="form-control pre-details-data bg-white" type="text" placeholder="Day Accumulated Balance"
                       id="day_accumulated" name="day-accumulated" readonly>
            </div>
        </div>
        <div class="row form-group col-md-6 my-2">
            <label class="col-sm-4 col-form-label text-end post-details-label">Fluid Restriction</label>
            <div class="col-sm-8">
                <input value="<?= isset($metadata['fluid-restriction']) ? $metadata['fluid-restriction'] : '' ?>"
                       class="form-control pre-details-data  bg-white" type="text" placeholder="Fluid Restriction"
                       id="fluid_restriction" name="fluid-restriction" readonly>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <h6 class="text-center mt-5 fw-bold">Form Data By:</h6>
            <table class="table table-bordered">
                <thead class="table-light">
                <tr>
                    <th class="text-center">SN</th>
                    <th class="text-center">Employee Name</th>
                    <th class="text-center">Last Updated At</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sn = 1;
                foreach($users as $user) { ?>
                    <tr>
                        <td style="width: 3.75%;" class="text-center"><?= $sn; ?></td>
                        <td class="text-center"><?= $user['name']; ?></td>
                        <td class="text-center"><?= $user['date']; ?></td>
                    </tr>
                    <?php $sn++;
                } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
