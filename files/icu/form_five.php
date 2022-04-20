<?php
include('new_header.php');
include('../includes/connection.php');
include('form_five_records.php');
include 'partials/get_patient_details.php';

$form_name = "HANDOVER ISSUE";

?>

    <a href="#" class="btn btn-danger fw-bold" id="preview_records">PREVIEW RECORDS</a>
    <a href="icu.php?consultation_ID=<?= $consultation_ID; ?>&Registration_ID=<?= $_GET['Registration_ID']; ?>&Admision_ID=<?= $Admision_ID ?>"
    class="btn btn-primary">BACK</a>

<?php include 'partials/new_patient_info.php'; ?>

    <div class="bg-white pt-5">
        <div class="alert alert-secondary fade show mx-2" role="alert" style="display: none;">
            <span id="alert-message"></span>
            <button type="button" class="btn-close" id="alert-dismiss"></button>
        </div>
        <form class="" method="post" id="fifth_form">

            <input type="hidden" id="registration_id" name="registration_id" value="<?= $Registration_ID ?>">

            <table width="100%" style="background-color: #fff;" class="table table-striped align-middle">
                <thead>
                <tr>
                    <th class="fw-bold text-center py-2" style="width:30% !important; ">Handover issues</th>
                    <th class="fw-bold text-center py-2" style="width:70% !important; ">Events</th>
                </tr>
                </thead>

                <tbody>
                <tr>
                    <td class="text-center label">Time</td>
                    <td>
                        <input class="form-control time-inputs" type="text" id="time">
                    </td>
                </tr>
                <tr>
                    <td class="text-center label">LOC / Mood</td>
                    <td>
                        <input class="events-inputs form-control loc_mood" type="text" name="LOC_Moodevents" value="">
                    </td>
                </tr>
                <tr>
                    <td class="text-center label">Sensation</td>
                    <td>
                        <input class="events-inputs form-control sensation" type="text" name="sensationevents" value="">
                    </td>
                </tr>
                <tr>
                    <td class="text-center label">ECG (Rate Rhythm)</td>
                    <td>
                        <input class="events-inputs form-control ecg" type="text" name="ecg_rate_rhythmevents" value="">
                    </td>
                </tr>
                <tr>
                    <td class="text-center label">BP</td>
                    <td>
                        <input class="events-inputs form-control bp" type="text" name="bpevents" value="">
                    </td>
                </tr>
                <tr>
                    <td class="text-center label">Urine Output</td>
                    <td>
                        <input class="events-inputs form-control urine_output" type="text" name="urine_outputevents" value="">
                    </td>
                </tr>
                <tr>
                    <td class="text-center label">Temperature</td>
                    <td>
                        <input class="events-inputs form-control temperature" type="text" name="temperatureevents" value="">
                    </td>
                </tr>
                <tr>
                    <td class="text-center label">Breathing</td>
                    <td>
                        <input class="events-inputs form-control breathing" type="text" name="breathingevents" value="">
                    </td>
                </tr>
                <tr>
                    <td class="text-center label">Activity</td>
                    <td>
                        <input class="events-inputs form-control activity" type="text" name="activityevents" value="">
                    </td>
                </tr>
                <tr>
                    <td class="text-center label">Diet And Elimination</td>
                    <td>
                        <input class="events-inputs form-control diet_elimination" type="text" name="diet_And_eliminationevents"
                               value="">
                    </td>
                </tr>
                <tr>
                    <td class="text-center label">Skin</td>
                    <td>
                        <input class="events-inputs form-control skin" type="text" name="Skinevents" value="">
                    </td>
                </tr>
                <tr>
                    <td class="text-center label">Infection</td>
                    <td>
                        <input class="events-inputs form-control infection" type="text" name="infectionevents" value="">
                    </td>
                </tr>
                <tr>
                    <td class="text-center label">Comfort</td>
                    <td>
                        <input class="events-inputs form-control comfort" type="text" name="comfortevents" value="">
                    </td>
                </tr>
                <tr>
                    <td class="text-center label">Bleeding</td>
                    <td>
                        <input class="events-inputs form-control bleeding" type="text" name="bleedingevents" value="">
                    </td>
                </tr>
                <tr>
                    <td class="text-center label">Patient Complaint</td>
                    <td>
                        <input class="events-inputs form-control patient_complaint" type="text" name="patient_complaintevents" value="">
                    </td>
                </tr>
                <tr>
                    <td class="text-center label">Family Concern</td>
                    <td>
                        <input class="events-inputs form-control family_concern" type="text" name="family_Concernevents" value="">
                    </td>
                </tr>
                <tr>
                    <td class="text-center label">Socio culture issues</td>
                    <td>
                        <input class="events-inputs form-control socio_culture_issues" type="text" name="socio_culture_issuesevents"
                               value="">
                    </td>
                </tr>
                <tr>
                    <td class="text-center label">Fluid and Electrolyte</td>
                    <td>
                        <input class="events-inputs form-control fluid_electrolyte" type="text" name="fluid_and_electrolyteevents"
                               value="">
                    </td>
                </tr>
                <tr>
                    <td class="text-center label">Labs / Investigation</td>
                    <td>
                        <input class="events-inputs form-control labs_investigation" type="text" name="labs_investigationevents" value="">
                    </td>
                </tr>
                <tr>
                    <td class="text-center label">Leading Needs</td>
                    <td>
                        <input class="events-inputs form-control leading_needs" type="text" name="leading_needsevents" value="">
                    </td>
                </tr>

                <tr>
                    <td class="text-center">Summary</td>
                    <td colspan="3">
                        <textarea id="summary" class="form-control" name="summary" rows="2" cols="18"></textarea>
                    </td>
                </tr>
                <tr>
                    <td class="text-center">Comment</td>
                    <td colspan="3">
                        <textarea id="comments" class="form-control" name="comment" rows="2" cols="18"></textarea>
                    </td>
                </tr>
                </tbody>

            </table>

            <br>

            <div class="d-flex justify-content-center pb-4">
                <button id="submit" class="button px-5">SAVE RECORDS</button>
<!--                <button type="button" class="btn btn-primary" id="liveToastBtn">Show live toast</button>-->
            </div>
        </form>
    </div>

    <div id="records_preview_dialog"></div>

    <script>
        $(document).ready(function(){
            $('#liveToastBtn').click(function (){
                notify("Hello");
            });

            $('.time-inputs').datetimepicker();

            $("#records_preview_dialog").dialog({
                autoOpen: false,
                minWidth: 600,
                modal: true,
                title: 'ICU FORM FIVE RECORDS PREVIEW',
                width: "75%",
                height: 600
            });

            var dialog = $('#records_preview_dialog')

            $('#preview_records').on('click', function () {
                var Registration_ID = '<?= $Registration_ID ?>';
                var consultation_ID = '<?= $consultation_ID ?>';

                $.get(
                    'form_five_preview_list.php', {
                        records_list: 'form_five',
                        Registration_ID: Registration_ID,
                        consultation_ID: consultation_ID
                    }, function(response){
                        dialog.html(response)
                        dialog.dialog('open');

                    }
                );
            })

            $('#submit').on('click', function (e){
                e.preventDefault();

                var comments = $('#comments').val();
                var summary = $('#summary').val();
                var time = $('#time').val();

                var loc_mood = $('.loc_mood').val();

                var sensation = $('.sensation').val();

                var ecg = $('.ecg').val();

                var bp = $('.bp').val();

                var urine_output = $('.urine_output').val();

                var temperature = $('.temperature').val();

                var breathing = $('.breathing').val();

                var activity = $('.activity').val();

                var diet_elimination = $('.diet_elimination').val();

                var skin = $('.skin').val();

                var infection = $('.infection').val();

                var comfort = $('.comfort').val();

                var bleeding = $('.bleeding').val();

                var patient_complaint = $('.patient_complaint').val();

                var family_concern = $('.family_concern').val();

                var socio_culture_issues = $('.socio_culture_issues').val();

                var fluid_electrolyte = $('.fluid_electrolyte').val();

                var labs_investigation = $('.labs_investigation').val();

                var leading_needs = $('.leading_needs').val();

                var employee_id = '<?=$_SESSION['userinfo']['Employee_ID']?>';
                var Registration_ID = '<?= $Registration_ID ?>';
                var consultation_ID = '<?= $consultation_ID ?>';

                if (comments == "" || summary == "") {
                    alert("Summary and Comments are required.")
                } else {
                    if (confirm('Are you sure you want to save ?')){
                        $.post('form_five_store.php', {
                                Registration_ID: Registration_ID,
                                consultation_ID: consultation_ID,
                                employee_id: employee_id,
                                loc_mood: loc_mood,
                                sensation: sensation,
                                ecg: ecg,
                                bp: bp,
                                urine_output: urine_output,
                                temperature: temperature,
                                breathing: breathing,
                                activity: activity,
                                diet_elimination: diet_elimination,
                                skin: skin,
                                infection: infection,
                                comfort: comfort,
                                bleeding: bleeding,
                                patient_complaint: patient_complaint,
                                family_concern: family_concern,
                                socio_culture_issues: socio_culture_issues,
                                fluid_electrolyte: fluid_electrolyte,
                                labs_investigation: labs_investigation,
                                leading_needs: leading_needs,
                                time: time,
                                summary: summary,
                                comments: comments,
                                store: 'form_five'
                            }, function (response){
                                console.log(response);
                                window.scrollTo(0, 0);
                                $('#alert-message').html(response);
                                $('.alert').show();
                            }
                        );
                    }
                }
            });

            $('#alert-dismiss').click(function(){

            })
        });

    </script>


<?php
include("footer.php");
?>