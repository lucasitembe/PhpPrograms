<?php
include('new_header.php');
include 'form_six_functions.php';
include 'partials/get_patient_details.php';
$form_name = 'PATIENT ASSESSMENT';

$formId = getFormDetails($consultation_ID, $Registration_ID, $Admission_ID)['id'];

?>

    <a href="#" class="btn btn-danger font-weight-bold" id='form_six_prev_records'>PREVIOUS RECORDS</a>
    <a href="icu.php?consultation_ID=<?= $consultation_ID; ?>&Registration_ID=<?= $Registration_ID; ?>&Admision_ID=<?= $Admision_ID ?>"
       class="btn btn-primary">BACK</a>

<?php include "partials/new_patient_info.php"; ?>
    <div class="bg-white pt-5 pb-4 px-0">

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
                            data-toggle="collapse" data-target="#respiratory-collapse" aria-expanded="true" aria-controls="respiratory-collapse">
                        RESPIRATORY
                    </button>
                </h2>
                <div id="respiratory-collapse" class="collapse show">
                    <div class="row m-0 p-2">
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Air entry</label>
                                <div class="col-sm-7">
                                    <input class="form-control form-inputs" type="text" id="air-entry">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Breath Sound</label>
                                <div class="col-sm-7">
                                    <input class="form-control form-inputs" type="text" id="breath-sound">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Chest Expansion</label>
                                <div class="col-sm-7">
                                    <input class="form-control form-inputs" type="text" id="chest-expansion">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Ability To Cough</label>
                                <div class="col-sm-7">
                                    <input class="form-control form-inputs" type="text" id="ability-to-cough">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Use of Accessory Muscle</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-inputs" type="text" id="use-of-accessory-muscle">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <h2 class="mb-0">
                    <button class="btn btn-outline-primary w-100 rounded-0 font-weight-bold py-3 border-left-0 border-right-0 border-top-0" type="button"
                            data-toggle="collapse" data-target="#cv-collapse" aria-expanded="true" aria-controls="cv-collapse">
                        CV
                    </button>
                </h2>
                <div id="cv-collapse" class="collapse">
                    <div class="row m-0 p-2">
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Rhythm</label>
                                <div class="col-sm-7">
                                    <input class="form-control form-inputs" type="text" id="rhythm">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Daily Weight</label>
                                <div class="col-sm-7">
                                    <input class="form-control form-inputs" type="text" id="daily-weight">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Capillary Refill</label>
                                <div class="col-sm-7">
                                    <input class="form-control form-inputs" type="text" id="capillary-refill">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Skin Condition</label>
                                <div class="col-sm-7">
                                    <input class="form-control form-inputs" type="text" id="skin-condition">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Color: Pink / Pale / Cynotic / Juandice</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-inputs" type="text" id="skin-color">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Turgor: Normal / Loose / Tight / Shiny</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-inputs" type="text" id="skin-turgor">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Texture Dry / Moist</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-inputs" type="text" id="skin-texture">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Odema[Sites]</label>
                                <div class="col-sm-7">
                                    <input class="form-control form-inputs" type="text" id="odema">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <h2 class="mb-0">
                    <button class="btn btn-outline-primary w-100 rounded-0 font-weight-bold py-3 border-left-0 border-right-0 border-top-0" type="button"
                            data-toggle="collapse" data-target="#gi-collapse" aria-expanded="true" aria-controls="gi-collapse">
                        GI
                    </button>
                </h2>
                <div id="gi-collapse" class="collapse">
                    <div class="row p-2 m-0">
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Abdomen:Soft / Hard / Distended / Tender</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-inputs" type="text" id="abdomen">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Bowel Sound: Normal / hyperactive	</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-inputs" type="text" id="bowel-sound">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Hypoactive / Absent</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-inputs" type="text" id="hypoactive">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">*NG Tube Insertion Date: NA / Clamped / Cont Suction / INT.Suction Gravity	</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-datetime" type="text" id="ngtube-insertion-date">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Diet: Restricted / Regular</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-inputs" type="text" id="diet">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Activity</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-inputs" type="text" id="activity">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Level Of Mobility	</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-inputs" type="text" id="level-of-mobility">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">(**CBR/up To Washroom)</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-inputs" type="text" id="cbr">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Activity (Assisted / Self)</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-inputs" type="text" id="activity-2">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Drains (NA / Type / Location)	</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-inputs" type="text" id="drains">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Character</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-inputs" type="text" id="character">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Vomitus: Amount / Colour	</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-inputs" type="text" id="vomitus">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Stool: Consistency / Colour / Pattern	</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-inputs" type="text" id="stool">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Amount: Small, Medium, Large, Nil	</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-inputs" type="text" id="stool-amount">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <h2 class="mb-0">
                    <button class="btn btn-outline-primary w-100 rounded-0 font-weight-bold py-3 border-left-0 border-right-0 border-top-0" type="button"
                            data-toggle="collapse" data-target="#gu-collapse" aria-expanded="true" aria-controls="gu-collapse">
                        GU
                    </button>
                </h2>
                <div id="gu-collapse" class="collapse">
                    <div class="row m-0 p-2">
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Urine: Colour / Sediments / Haematite</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-inputs" type="text" id="urine">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Foleys Insertion Date	</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-datetime" type="text" id="foleys-insertion-date">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Dialysis</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-inputs" type="text" id="dialysis">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Pulse Code</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-inputs" type="text" id="pulse-code">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <div class="col-sm-5 font-weight-bold col-form-label d-flex justify-content-around">
                                    <span>0 = Absent</span>
                                    <span>Radial: R/L</span>
                                </div>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-inputs" type="text" id="pc-radial">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <div class="col-form-label col-sm-5 font-weight-bold d-flex justify-content-around">
                                    <span>+1 = Weak</span>
                                    <span>Femoral: R/L</span>
                                </div>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-inputs" type="text" id="pc-femoral">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <div class="col-form-label col-sm-5 font-weight-bold d-flex justify-content-around">
                                    <span>+2 = Normal</span>
                                    <span>Dor Pedis: /R/L</span>
                                </div>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-inputs" type="text" id="pc-dor-pedis">
                                </div>
                            </div>
                        </div><div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <div class="col-form-label col-sm-5 font-weight-bold d-flex justify-content-around">
                                    <span>+3 = Strong</span>
                                    <span>Post Tib: R/L</span>
                                </div>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-inputs" type="text" id="pc-post-tib">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">+4 = Bounding</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-inputs" type="text" id="pc-bounding">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Nurse - Family Interaction</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-inputs" type="text" id="nurse-family-interaction">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 my-2">
                            <div class="row d-flex align-items-center">
                                <label class="col-form-label col-sm-5 text-right font-weight-bold label">Psychological Family Support</label>
                                <div class="col-sm-7 d-flex align-items-center">
                                    <input class="form-control form-inputs" type="text" id="pyschological-family-support">
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

    <div class="position-fixed bg-white m-0 py-2 px-3" style="display: none; top: 0; right: 0;" id="loading">
        <i class="spinner-border spinner-border-sm text-primary" style="width: 1.2rem; height: 1.2rem;" role="status"></i>
    </div>

    <div id="form_six_records"></div>

    <script>
        $(document).ready(function(){
            var form_id = '<?= $formId ?>';

            $('.form-inputs').on('keyup', delay(function () {
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
                    alert('Please select shift first.');
                    $(this).val('');
                    return;
                }
                saveData(details);
            }, 600));


            $('#select-shift').on('change', function (){
                loading(true);
                var shift = $(this).val();

                $.get('form_six_store.php', {
                    form_id: form_id,
                    shift: shift,
                    retrieve: 'form-six'
                }, function(response, status){
                    // Clear existing values
                    $('.form-inputs').val('');

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

                $.post('form_six_store.php', {
                    form_id: form_id,
                    name: 'is_complete',
                    shift: shift,
                    value: '1',
                    store: 'form-six'
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

                $.post('form_six_store.php', {
                    form_id: form_id,
                    name: details.name,
                    shift: details.shift,
                    value: details.value,
                    store: 'form-six'
                }, function (response) {
                    console.log(response)
                    loading(false);
                });
            }

        });
    </script>

    <!-- Helper Functions -->
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

    <!-- Preview Records-->
    <script>
        $('#form_six_prev_records').click(function(){
            var Registration_ID = '<?= $Registration_ID ?>';
            var consultation_ID = '<?= $consultation_ID ?>';
            $.get(
                'form_six_preview_list.php', {
                    Registration_ID: Registration_ID,
                    consultation_ID: consultation_ID,
                    records_list: 'form_six'
                }, (data) => {
                    $("#form_six_records").dialog({
                        title: "FORM SIX RECORDS LIST",
                        width: "70%",
                        height: 500,
                        modal: true
                    });
                    $("#form_six_records").html(data);
                    $("#form_six_records").dialog("open");
                }
            );
        })
    </script>

<?php
include("footer.php");
?>