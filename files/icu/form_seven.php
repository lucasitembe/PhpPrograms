
<?php
include('new_header.php');
include 'form_seven_functions.php';
include 'partials/get_patient_details.php';

$form_name = "ROUTINE CARE";

$result = array();
$select_location = mysqli_query($conn,"SELECT * FROM icu_location_setup");
while($data = mysqli_fetch_assoc($select_location)){
	array_push($result,$data);
}

$details = getFormDetails($consultation_ID, $Registration_ID, $Admission_ID);
$formId = $details['id'];
$data = $details['data'];
?>

    <a href="#" class="btn btn-danger font-weight-bold" id="form_seven_prev_records">PREVIOUS RECORDS</a>
    <a style='display:none' href='icu_location_setup.php?consultation_ID=<?= $consultation_ID; ?>&Registration_ID=<?= $_GET['Registration_ID']; ?>&Admision_ID=<?= $Admission_ID ?>' class='btn btn-primary'>LOCATION SETUP</a>
    <a href="icu.php?consultation_ID=<?= $consultation_ID; ?>&Registration_ID=<?= $_GET['Registration_ID']; ?>&Admision_ID=<?= $Admission_ID ?>"
       class="btn btn-primary">BACK</a>

    <style media="screen">
        table tr td {
            height: 20px !important;
        }
    </style>

<?php include "partials/new_patient_info.php"; ?>

    <div class="bg-white pt-5 pb-4">
        <div class="row d-flex align-items-center">
            <div class="col-md-12 row d-flex justify-content-center my-3">
                <label class="col-form-label col-sm-2 text-right font-weight-bold label">Select Shift</label>
                <div class="col-sm-4">
                    <select class="form-control" id="select-shift">
                        <option disabled selected>Select Shift</option>
                        <option>AM</option>
                        <option>PM</option>
                        <option>Night</option>
                    </select>
                </div>

            </div>
            <div class="col-md-12">
                <h2 class="mb-0">
                    <button class="btn btn-outline-primary w-100 rounded-0 font-weight-bold py-3 border-left-0 border-right-0 border-top-0" type="button"
                            data-toggle="collapse" data-target="#routine-care-collapse" aria-expanded="true" aria-controls="routine-care-collapse">
                        ROUTINE CARE
                    </button>
                </h2>
                <div id="routine-care-collapse" class="collapse show">
                    <div class="row p-2 m-0">
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Bath</label>
                                <div class="col-sm-7">
                                    <select class="form-control form-inputs" type="text" id="bath">
                                        <option value="" selected disabled></option>
                                        <option>Done</option>
                                        <option>Not Done</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Back Care</label>
                                <div class="col-sm-7">
                                    <select class="form-control form-inputs" type="text" id="back-care">
                                        <option value="" selected disabled></option>
                                        <option>Done</option>
                                        <option>Not Done</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Mouth Care</label>
                                <div class="col-sm-7">
                                    <select class="form-control form-inputs" type="text" id="mouth-care">
                                        <option value="" selected disabled></option>
                                        <option>Done</option>
                                        <option>Not Done</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Eye Care</label>
                                <div class="col-sm-7">
                                    <select class="form-control form-inputs" type="text" id="eye-care">
                                        <option value="" selected disabled></option>
                                        <option>Done</option>
                                        <option>Not Done</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Catheter Care</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <select class="form-control form-inputs" type="text" id="catheter-care">
                                        <option value="" selected disabled></option>
                                        <option>Done</option>
                                        <option>Not Done</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Perinial Care</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <select class="form-control form-inputs" type="text" id="perinial-care">
                                        <option value="" selected disabled></option>
                                        <option>Done</option>
                                        <option>Not Done</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">N/G Care</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <select class="form-control form-inputs" type="text" id="ng-care">
                                        <option value="" selected disabled></option>
                                        <option>Done</option>
                                        <option>Not Done</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Nose / Ear Care</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <select class="form-control form-inputs" type="text" id="nose-ear-care">
                                        <option value="" selected disabled></option>
                                        <option>Done</option>
                                        <option>Not Done</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Physio</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <select class="form-control form-inputs" type="text" id="physio">
                                        <option value="" selected disabled></option>
                                        <option>Done</option>
                                        <option>Not Done</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Deep Breath Cough</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <select class="form-control form-inputs" type="text" id="deep-breath-cough">
                                        <option value="" selected disabled></option>
                                        <option>Done</option>
                                        <option>Not Done</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">OETT/TT Care</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <select class="form-control form-inputs" type="text" id="oett-tt-care">
                                        <option value="" selected disabled></option>
                                        <option>Done</option>
                                        <option>Not Done</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Line Care</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <select class="form-control form-inputs" type="text" id="line-care">
                                        <option value="" selected disabled></option>
                                        <option>Done</option>
                                        <option>Not Done</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <h2 class="mb-0">
                    <button class="btn btn-outline-primary w-100 rounded-0 font-weight-bold py-3 border-left-0 border-right-0 border-top-0" type="button"
                            data-toggle="collapse" data-target="#location-1-collapse" aria-expanded="true" aria-controls="location-1-collapse">
                        LOCATION 1
                    </button>
                </h2>
                <div id="location-1-collapse" class="collapse">
                    <div class="row p-2 m-0">
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Location</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <select class="form-control form-inputs" type="text" id="location-1">
                                        <option value="" selected disabled></option>
                                        <?php foreach($result as $location) { ?>
                                                <option><?=$location['location']?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Insertion Date</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-datetime" type="text" id="insertion-date-1" />
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Status of Site</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-inputs" type="text" id="status-of-site-1" />
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Redressed</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <select class="form-control form-inputs" type="text" id="redressed-1">
                                        <option value="" selected disabled></option>
                                        <option>Done</option>
                                        <option>Not Done</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <h2 class="mb-0">
                    <button class="btn btn-outline-primary w-100 rounded-0 font-weight-bold py-3 border-left-0 border-right-0 border-top-0" type="button"
                            data-toggle="collapse" data-target="#location-2-collapse" aria-expanded="true" aria-controls="location-2-collapse">
                        LOCATION 2
                    </button>
                </h2>
                <div id="location-2-collapse" class="collapse">
                    <div class="row m-0 p-2">
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Location</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <select class="form-control form-inputs" type="text" id="location-2">
                                        <option value="" selected disabled></option>
                                        <?php foreach($result as $location) { ?>
						<option><?=$location['location']?></option>
					<?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Insertion Date</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-datetime" type="text" id="insertion-date-2" />
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Status of Site</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-inputs" type="text" id="status-of-site-2" />
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Redressed</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <select class="form-control form-inputs" type="text" id="redressed-2">
                                        <option value="" selected disabled></option>
                                        <option>Done</option>
                                        <option>Not Done</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <h2 class="accordion-header">
                    <button class="btn btn-outline-primary w-100 rounded-0 font-weight-bold py-3 border-left-0 border-right-0 border-top-0" type="button"
                            data-toggle="collapse" data-target="#location-3-collapse" aria-expanded="true" aria-controls="location-3-collapse">
                        LOCATION 3
                    </button>
                </h2>
                <div id="location-3-collapse" class="collapse">
                    <div class="row m-0 p-2">
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Location</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <select class="form-control form-inputs" type="text" id="location-3">
                                        <option value="" selected disabled></option>
                                        <?php foreach($result as $location) { ?>
                                                <option><?=$location['location']?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Insertion Date</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-datetime" type="text" id="insertion-date-3" />
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Status of Site</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-inputs" type="text" id="status-of-site-3" />
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Redressed</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <select class="form-control form-inputs" type="text" id="redressed-3">
                                        <option value="" selected disabled></option>
                                        <option>Done</option>
                                        <option>Not Done</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="my-2 d-flex justify-content-center">
                    <button class="btn btn-primary" style="display: block;" id="save-shift">SAVE SHIFT</button>
                </div>
            </div>
        </div>
    </div>

    <div class="position-fixed bg-white m-0 py-2 px-3" style="display: none; top: 0; right: 0" id="loading">
        <i class="spinner-border spinner-border-sm text-primary" style="width: 1.2rem; height: 1.2rem;" role="status"></i>
    </div>

    <div id="form_seven_records"></div>

    <script>
        $(document).ready(function() {
            var form_id = '<?= $formId ?>';

            $('.form-inputs').on('change', delay(function () {
                var details = getInputDetails($(this));
                if (!details.shift) {
                    alert('Please select shift first.');
                    $(this).val('');
                    return;
                }
                saveData(details);
            }, 600));

            $('.form-datetime').on('change keyup', delay(function () {
                var details = getInputDetails($(this));
                if (!details.shift) {
                    console.log('Please select shift first.');
                    $(this).val('');
                    return;
                }
                saveData(details);
            }, 600));


            $('#select-shift').on('change', function (){
                loading(true);
                var shift = $(this).val();

                $.get('form_seven_store.php', {
                    form_id: form_id,
                    shift: shift,
                    retrieve: 'form-seven'
                }, function(response, status){
                    // Clear existing values
                    $('.form-inputs').val('');
                    $('.form-datetime').datetimepicker('destroy');

                    if (response.hasOwnProperty('data')){
                        var data = response.data;
                        for (var item in data){
                            console.log(data[item]);
                            $('#' + item).val(data[item].value);
                        }
                    }

                    if (response.data.hasOwnProperty('is_complete')){
                        $('.form-inputs, .form-datetime').attr('readonly', true);
                        $('.form-datetime').datetimepicker('destroy');
                        $('#save-shift').hide('fast');
                    } else {
                        $('.form-inputs, .form-datetime').attr('readonly', false);
                        $('#save-shift').show('fast');
                        $('.form-datetime').datetimepicker();
                    }

                    loading(false);
                });

            });

            $('#save-shift').on('click', function () {
                var shift = $('#select-shift').val();

                if (!shift) {
                    alert('Please select shift first.');
                    return;
                }

                var is_empty = true;
                $('.form-inputs').map(function (input){
                    if ($(this).val()){
                        is_empty = false;
                    }
                });

                $('.form-datetime').map(function (){
                    if ($(this).val()){
                        is_empty = false;
                    }
                });

                if (is_empty){
                    alert("Please fill atlease one input");
                    return;
                }

                loading(true);

                $.post('form_seven_store.php', {
                    form_id: form_id,
                    name: 'is_complete',
                    shift: shift,
                    value: '1',
                    store: 'form-seven'
                }, function (response) {
                    console.log(response);
                    $('.form-inputs, .form-datetime').attr('readonly', true);
                    $('.form-datetime').datetimepicker('destroy');
                    $('#save-shift').hide();
                    loading(false);
                });

            });

            function saveData(details){
                loading(true);

                $.post('form_seven_store.php', {
                    form_id: form_id,
                    name: details.name,
                    shift: details.shift,
                    value: details.value,
                    store: 'form-seven'
                }, function (response) {
                    console.log(response)
                    loading(false);
                });
            }

        });

    </script>

    <script>
        function getInputDetails(input) {
            var name = $(input).prop('id');
            var value = $(input).val();
            var shift  = $('#select-shift').val();
            return {name: name, value: value, shift: shift};
        }

        function loading(state){
            if (state){
                $('#loading').show('fast', 'linear');
            } else {
                $('#loading').hide('fast', 'linear');
            }
        }

        function delay(callback, ms) {
            var timer = 0;
            return function() {
                var context = this, args = arguments;
                clearTimeout(timer);
                timer = setTimeout(function () {
                    callback.apply(context, args);
                }, ms || 0);
            };
        }
    </script>

    <script>
        var Registration_ID = '<?= $Registration_ID ?>';
        var consultation_ID = '<?= $consultation_ID ?>';

        $(document).ready(function (){
            $('#form_seven_prev_records').click(function () {
                $.get(
                    'form_seven_preview_list.php', {
                        Registration_ID: Registration_ID,
                        consultation_ID: consultation_ID,
                        records_list: 'form_seven'
                    }, function (data) {
                        $("#form_seven_records").dialog({
                            title: "FORM SEVEN RECORDS LIST",
                            width: "70%",
                            height: 500,
                            modal: true
                        });
                        $("#form_seven_records").html(data);
                        $("#form_seven_records").dialog("open");
                    });
            });
        });
    </script>

<?php
include("footer.php");
?>
