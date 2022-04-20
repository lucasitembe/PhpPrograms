<?php
include('new_header_icu.php');
include 'repository.php';
include 'partials/get_patient_details.php';
include 'partials/new_patient_info.php';

if (isset($_GET['record_id'])) {
    $recordId = $_GET['record_id'];
} else {
    $recordId = '';
}

$query = "SELECT * FROM tbl_icu_form_four WHERE id = '$recordId'";
$result = querySelectOne($query, $conn);

    $employee_id = $result['employee_id'];
    $labels = json_decode($result['labels']);
    $form_inputs = json_decode($result['form_inputs']);
    $anatomical_position_drawing = $result['anatomical_position_drawing'];
    $comments = $result['comments'];
    $save_date = $result['created_at'];

    $select_employee_name = "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$employee_id' ";
    $select_employee_name = mysqli_query($conn, $select_employee_name);
    while ($employee_row = mysqli_fetch_array($select_employee_name)):
        $get_employee_name = $employee_row['Employee_Name'];
    endwhile;

    ?>

    <div class="pt-5 bg-white">
        <div class="row">
            <?php for ($i = 0; $i < count($labels); $i++) { ?>
                <div class="col-md-4">
                    <div class="container py-2">
                        <div class="row">
                            <label class="col-form-label col-sm-4 text-right font-weight-bold"><?= $labels[$i] ?></label>
                            <div class="col-sm-8">
                                <input class="form-control bg-light" readonly value="<?= $form_inputs[$i] ?>">
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="row my-5 mx-0">
            <div class="col d-flex flex-column align-items-center">
                <b class="text-center">Location, Staging, Description and Treatment.</b>

                <div class="my-2" style="width: 508px; height: 500px;">
                    <img src="<?= 'anatomical-positions-drawings/' . $anatomical_position_drawing ?>" style="height: 500px; width: 500px; ">
                </div>
            </div>
            <div class="col" style="min-width: 300px; ">
                <p class="mb-2 font-weight-bold">Key for bedscore management.</p>
                <ol>
                    <li>Keep clean and open.</li>
                    <li>Wash with normal saline and cover with dry gauze.</li>
                    <li>Specified Management.</li>
                </ol>

                <p class="my-3 font-weight-bold">Skin Assessment</p>
                <div class="row">
                    <div class="col-md-6">
                        <div><button class="btn my-1 mr-2" style="width: 50px; height: 35px; background-color: red;"></button><span>Bedsore</span></div>
                        <div><button class="btn my-1 mr-2" style="width: 50px; height: 35px; background-color: blue;"></button><span>Lipsore</span></div>
                        <div><button class="btn my-1 mr-2" style="width: 50px; height: 35px; background-color: teal;"></button><span>Rash</span></div>
                        <div><button class="btn my-1 mr-2" style="width: 50px; height: 35px; background-color: green;"></button><span>Bruise</span></div>
                    </div>
                    <div class="col-md-6">
                        <div><button class="btn my-1 mr-2" style="width: 50px; height: 35px; background-color: orange;"></button><span>Blister</span></div>
                        <div><button class="btn my-1 mr-2" style="width: 50px; height: 35px; background-color: purple;"></button><span>Edema</span></div>
                        <div><button class="btn my-1 mr-2" style="width: 50px; height: 35px; background-color: pink;"></button><span>Burn</span></div>
                        <div><button class="btn my-1 mr-2" style="width: 50px; height: 35px; background-color: black;"></button><span>Fracture</span></div>
                    </div>
                </div>

                <div class="my-2">
                    <label class="my-2 font-weight-bold">Comments: </label>
                    <textarea readonly class="form-control bg-light" rows="8" id="comments"><?= $comments ?></textarea>
                </div>
            </div>
        </div>
    </div>
<?php
    include 'footer.php';
?>