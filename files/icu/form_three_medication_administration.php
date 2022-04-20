<style media="screen">
    button.medication-item {
        font-size: 14px;
        width: 100%;
        background: #ebf4fa;
        height: 26px !important;
        font-weight: bold;
        color: #4d555b !important;
        border: 1px solid #989b9d;
        font-size: 1em;
    }

    button.medication-item:hover {
        background-color: #1c7db0;
        color: white !important;
        border-color: white;
    }

</style>

<div class=" py-3">

    <div class="row v-center d-flex justify-content-center">
        <div class="col-md-3 mt-3 mb-5">
            <select class="form-control" name="medication_type" id="medication_type_new" onchange='change_medication_type()'>
                <option value="" selected disabled>Please Select Medication Type to Continue</option>
                <option value='dispensed'>Received</option>
                <option value='Pending'>Dispensed & Not Received</option>
                <option value='active'>Not Dispensed</option>
                <option value='outside'>From Outside (Dawa alizokujanazo mgonjwa)</option>
                <option value='others'>Others (Emergency drugs)</option>
            </select>
        </div>
    </div>



    <div id="new_drag_sheet" class="py-2"></div>

    <div class="row border py-2 m-1 mb-3" id='old_drug_table' style="display: none;">
        <div class="col-md-6 offset-md-3 alert alert-danger" id="feedback-error" style="display: none;"></div>
        <div class="col-md-6 offset-md-3 alert alert-success" id="feedback-success" style="display: none;"></div>
        <div class="col-md-4">
            <div class="row my-2">
                <label class="col-sm-4 col-form-label text-end">Medication</label>
                <div class="col-sm-8">
                    <select class="form-control" id="ot-medication-name">
                    </select>
                </div>
            </div>
            <div class="row my-2">
                <label class="col-sm-4 col-form-label text-end">Time Lapsed</label>
                <div class="col-sm-8">
                    <input type="text" readonly class="form-control bg-white" id="ot-time-lapsed">
                </div>
            </div>
            <div class="row my-2">
                <label class="col-sm-4 col-form-label text-end">Time Saved</label>
                <div class="col-sm-8">
                    <input type="text" readonly class="form-control bg-white" id="ot-time-saved">
                </div>
            </div>
            <div class="row my-2">
                <label class="col-sm-4 col-form-label text-end">Time Given</label>
                <div class="col-sm-8">
                    <input placeholder="Enter medication time" readonly class="form-control bg-white"
                           id="ot-time-given">
                </div>
            </div>

        </div>
        <div class="col-md-4">
            <div class="row my-2">
                <label class="col-sm-4 col-form-label text-end">Route</label>
                <div class="col-sm-8">
                    <select class='form-control'  id="ot-route">
                        <option>SELECT</option>
                        <option value='Injection'>Injection</option>
                        <option value='Oral'>Oral</option>
                        <option value='Sublingual'>Sublingual</option>
                        <option value='Rectal'>Rectal</option>
                        <option value='Avaginal'>Avaginal</option>
                        <option value='Obular'>Obular</option>
                        <option value='Otic'>Otic</option>
                        <option value='Nasal'>Nasal</option>
                        <option value='Inhalation'>Inhalation</option>
                        <option value='Nebulazation'>Nebulazation</option>
                        <option value='Very_rarely_transdermal'>Very rarely transdermal</option>
                        <option value='Cutaneous'>Cutaneous</option>
                        <option value='Intramuscular'>Intramuscular</option>
                        <option value='Intravenous'>Intravenous</option>
                        <option value='Topical'>Topical</option>
                    </select>
                </div>
            </div>

            <div class="row my-2">
                <label class="col-sm-4 col-form-label text-end">Dosage</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="ot-dosage">
                </div>
            </div>
            <div class="row my-2">
                <label class="col-sm-4 col-form-label text-end">Quantity</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="ot-quantity">
                </div>
            </div>
            <div class="row my-2">
                <label class="col-sm-4 col-form-label text-end">Drip Rate</label>
                <div class="col-sm-8">
                    <input type="number" class="form-control" id="ot-drip-rate">
                </div>
            </div>

        </div>
        <div class="col-md-4">
            <div class="row my-2">
                <label class="col-sm-4 col-form-label text-end">Remarks</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="ot-remarks">
                </div>
            </div>
            <div class="row my-2">
                <label class="col-sm-4 col-form-label text-end">From Outside Amount</label>
                <div class="col-sm-8">
                    <input type="number" id="ot-outside-amount" class="form-control">
                </div>
            </div>
            <div class="row my-2">
                <label class="col-sm-4 col-form-label text-end" for="ot-discontinue"></label>
                <div class="col-sm-8">
                    <input type="checkbox" id="ot-discontinue" class="form-check-input">
                    <label for="ot-discontinue" class="fw-bold mx-2">
                        Discontinue ?</label>
                </div>
            </div>
            <div class="row my-2">
                <label class="col-sm-4 col-form-label text-end">Discontinue Reason</label>
                <div class="col-sm-8">
                    <input type="text" id="ot-discontinue-reason" class="form-control" readonly>
                </div>
            </div>

        </div>

        <div class="col-md-12 my-2 d-flex justify-content-center">
            <input type="submit" id="submitOthersMedications" value="SAVE MEDICATION" class="btn btn-primary d-block">
        </div>
    </div>


    <div id="Search_Iframe" <?php echo isset($divStyle) ? $divStyle : '' ?>>
        <?php include 'ajax/medication_history.php'; ?>
    </div>

    <div class="row">
        <div class="col-md-12 my-2 justify-content-center d-flex">
            <button type="button" class="btn btn-success mx-1" id="view-completed">View Completed Medicines</button>
            <button type="button" class="btn btn-danger" id="view-discontinued">View Discontinued Medicines</button>
        </div>
    </div>
</div>

<!-- Dialogs -->
<div id="dialog" title="Dialog Title" id="feedback-message  ">
    <input class="form-control" type="hidden" id="item-id">
    <input class="form-control" type="hidden" id="dispensed">
    <input class="form-control" type="hidden" id="remaining">
    <input class="form-control" type="hidden" id="time">
    <input class="form-control" type="hidden" id="cache-id">
    <input class="form-control" type="hidden" id="route-type">
    <input class="form-control" type="hidden" id="dosage">
    <div id="feedback-message"></div>
    <div class="row">
        <div class="col-md-6 my-2">
            <label class="fw-bold mb-2" for="mi-quantity">Quantity **</label>
            <input class="form-control" id="mi-quantity">
        </div>
        <div class="col-md-6 my-2">
            <label class="fw-bold mb-2">Route*</label>
            <select id="" class='form-control'>
                <option></option>
                <option value='Injection'>Injection</option>
                <option value='Oral'>Oral</option>
                <option value='Sublingual'>Sublingual</option>
                <option value='Rectal'>Rectal</option>
                <option value='Avaginal'>Avaginal</option>
                <option value='Obular'>Obular</option>
                <option value='Ocular'>Ocular</option>
                <option value='Otic'>Otic</option>
                <option value='Nasal'>Nasal</option>
                <option value='Inhalation'>Inhalation</option>
                <option value='Nebulazation'>Nebulazation</option>
                <option value='Very_rarely_transdermal'>Very rarely transdermal</option>
                <option value='Cutaneous'>Cutaneous</option>
                <option value='Intramuscular'>Intramuscular</option>
                <option value='Intravenous'>Intravenous</option>
		<option value='Topical'>Topical</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 my-2">
            <label class="fw-bold mb-2">Drip Rate</label>
            <input class="form-control" id="mi-drip-rate">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 my-2">
            <div class="checkbox">
                <label class="fw-bold">
                    <input type="checkbox" id="mi-discontinue"> Discontinue
                </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 my-2">
            <label class="fw-bold mb-2">Discontinue Reason</label>
            <textarea class="form-control" readonly style="min-height: 60px !important;" id="mi-discontinue-reasons"></textarea>
        </div>
        <div class="col-md-6 my-2">
            <label class="fw-bold mb-2">Remarks *</label>
            <textarea class="form-control" id="mi-remarks" style="min-height: 60px !important;"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 d-flex justify-content-center my-2">
            <button class="btn btn-primary" id="mi-save">SAVE MEDICATION</button>
        </div>
    </div>
</div>

<div id="discontinued-services-list"></div>

<div id="completed-services-list"></div>


<script>
    $(document).ready(() => {
        change_medication_type();
        // Initialize medication modal
        $("#dialog").dialog({autoOpen: false, minWidth: 800});

        $('#mi-discontinue').click(function (){
            if ($(this).prop('checked')){
                $('#mi-discontinue-reasons').prop('readonly', false);
            } else {
                $('#mi-discontinue-reasons').prop('readonly', true);
            }
        });

        $('#ot-discontinue').click(function (){
            if ($(this).prop('checked')){
                $('#ot-discontinue-reason').prop('readonly', false);
            } else {
                $('#ot-discontinue-reason').prop('readonly', true);
            }
        });

        // Handle medication type change
        $('#medication_type_new').on('change', function () {
            let status = this.value;

            if (status === "outside" || status === "others") {
                $("#old_drug_table").show();
                $("#new_drag_sheet").hide();

                $('#ot-time-given').datetimepicker({
                    dayOfWeekStart: 1,
                    lang: 'en',
                    startDate: 'now'
                });

                $('#ot-route').select2();

                initializeRemoteSelect2();

            } else {
                $("#old_drug_table").hide();
                $("#new_drag_sheet").show();
            }

            const registrationId = '<?= $Registration_ID ?>';
            const consultationId = '<?= $consultation_ID ?>';

            $.get('form_three_inpatient_medications.php', {
                Status: status,
                Reg_ID: registrationId,
                consultation_ID: consultationId
            }, response => {
                $('#new_drag_sheet').html(response);
            })

        });

        function change_medication_type(){
            let status = $("#medication_type_new").val();

            if (status === "outside" || status === "others") {
                $("#old_drug_table").show();
                $("#new_drag_sheet").hide();

                $('#ot-time-given').datetimepicker({
                    dayOfWeekStart: 1,
                    lang: 'en',
                    startDate: 'now'
                });

                $('#ot-route').select2();

                initializeRemoteSelect2();

            } else {
                $("#old_drug_table").hide();
                $("#new_drag_sheet").show();
            }

            const registrationId = '<?= $Registration_ID ?>';
            const consultationId = '<?= $consultation_ID ?>';

            $.get('form_three_inpatient_medications.php', {
                Status: status,
                Reg_ID: registrationId,
                consultation_ID: consultationId
            }, response => {
                $('#new_drag_sheet').html(response);
            })
        }
        // Validate NaN fields
        let qtyField = $('#mi-quantity');
        qtyField.on('keyup', function () {
            if (isNaN(this.value)) {
                let parsed = parseInt(this.value)
                isNaN(parsed) ? qtyField.val('') : qtyField.val(parsed)
            }
        })

        let dripRateField = $('#mi-drip-rate');
        dripRateField.on('keyup', function () {
            if (isNaN(this.value)) {
                let parsed = parseInt(this.value)
                isNaN(parsed) ? dripRateField.val('') : dripRateField.val(parsed)
            }
        })

        // Raise medication item model
        $(document.body).on('click', '.medication-item', function () {
            const itemName = $(this).data('name');
            const time = $(this).data('time');
            const itemId = $(this).data('id');
            const remaining = $(this).data('remaining')
            const dispensed = $(this).data('dispensed')
            const cacheId = $(this).data('cache-id')
            const route = $(this).data('route')
            const dosage = $(this).data('dosage')

            $('#remaining').val(remaining)
            $('#dispensed').val(dispensed)
            $('#item-id').val(itemId)
            $('#time').val(time)
            $('#cache-id').val(cacheId)
            $('#route-type').val(route)
            $('#dosage').val(dosage)


            let dialog = $('#dialog')

            dialog.dialog({
                title: `${itemName} - ${time}HRS`,
                modal: true,
            });

            dialog.dialog('open');

            // Set default route from medicine
            $('#mi-route').val(route);

            $('#mi-route').select2();

        });

        // Submit Internal Medications
        $('#mi-save').on('click', function () {
            let medication_data = [];
            let dosage = $('#dosage').val();
            let itemId = $('#item-id').val();
            let Round_ID = '<?= isset($Round_ID) ? $Round_ID : '' ?>';
            let remarks = $('#mi-remarks').val();
            let time = $('#time').val();
            let routeType = $('#mi-route').val() ?? $('#route-type').val();
            let dripRate = $('#mi-drip-rate').val();
            let remainingQuantity = $('#remaining').val();
            let registrationId = '<?= $Registration_ID ?>';
            let consultationId = '<?= $consultation_ID ?>';
            let paymentItemCacheListId = $('#cache-id').val();
            let medication_type = $("#medication_type_new").val();
            let nurseQuantity = Math.abs($('#mi-quantity').val());
            let discontinue = $('#mi-discontinue').prop('checked') ? 'yes' : 'no';
            let discontinueReasons = $('#mi-discontinue-reasons').val();

            if (!nurseQuantity) {
                alert('Please select medication quantity.');
                $('#mi-quantity').parent().addClass('has-error')
                return;
            } else {
                $('#mi-quantity').parent().removeClass('has-error')
            }

            // validate max dispensed amount
            if (nurseQuantity > remainingQuantity) {
                alert('Submitted amount is greated than remaining quantity.');
                $('#mi-quantity').parent().addClass('has-error')
                return;
            } else {
                $('#mi-quantity').parent().removeClass('has-error');
            }

            // if discontinued, validate reason
            if (discontinue === 'yes' && !discontinueReasons) {
                alert('Please provide a reason to discontinue.');
                $('#mi-discontinue-reasons').parent().addClass('has-error');
                return;
            } else {
                $('#mi-discontinue-reasons').parent().removeClass('has-error');
            }

            // Route required
            if (!routeType) {
                alert('Please specify route type.');
                $('#mi-route').parent().addClass('has-error');
                return;
            } else {
                $('#mi-route').parent().removeClass('has-error');
            }

            // prepare record
            medication_data.push(
                paymentItemCacheListId + "unganisha" +
                dripRate + "unganisha" +
                remarks + "unganisha" +
                discontinue + "unganisha" +
                discontinueReasons + "unganisha" +
                routeType + "unganisha" +
                nurseQuantity + "unganisha" +
                time + "unganisha" +
                itemId + "unganisha" +
                dosage
            )

            if (confirm("Are you sure you want to save these medications?")) {
                $.ajax({
                    type: 'POST',
                    url: '../ajax_save_inpatient_medication.php',
                    data: {
                        medication_type: medication_type,
                        Round_ID: Round_ID,
                        Registration_ID: registrationId,
                        medication_data: medication_data,
                        consultation_ID: consultationId
                    },
                    success: function (data) {
                        $("#feedback-message").html(data);

                        setTimeout(function () {
                            location.reload()
                        }, 2000);
                    }
                });
            }
        });

        // Submit external medications
        $('#submitOthersMedications').on('click', function () {
            $('#feedback-error').hide();
            $('#feedback-success').hide();

            let medicationName = $('#ot-medication-name').val();
            let timeGiven = $('#ot-time-given').val();
            let outsideAmount = $('#ot-outside-amount').val();
            let dosage = $('#ot-dosage').val();
            let quantity = $('#ot-quantity').val();
            let route = $('#ot-route').val();
            let dripRate = $('#ot-drip-rate').val();
            let remarks = $('#ot-remarks').val();
            let discontinue = $('#ot-discontinue').prop('checked') ? 'yes' : 'no';
            let discontinueReasons = $('#ot-discontinue-reason').val();
            let medicationType = $('#medication_type_new').val();
            let registrationId = '<?= $Registration_ID ?>'
            let consultationId = '<?= $consultation_ID ?>'
            let employeeId = '<?= $_SESSION['userinfo']['Employee_ID'] ?>'
            let roundId = '<?= isset($Round_ID) ? $Round_ID : '' ?>'

            if (!validateExternalMedications()) return;

            // TODO: Onchange medication name, fetch last given time and call testFunction ??????
            // TODO: Display medication time given

            if (confirm("Are you sure you want to save these medications?")) {
                $.ajax({
                    type: 'POST',
                    url: 'form_three_store.php',
                    data: {
                        Round_ID: roundId,
                        Registration_ID: registrationId,
                        consultation_ID: consultationId,
                        medication_type: medicationType,
                        medication_time: timeGiven,
                        given_time: dosage,
                        Payment_Item_Cache_List_ID: medicationName,
                        amount_given: quantity,
                        route_type: route,
                        drip_rate: dripRate,
                        remarks: remarks,
                        discontinue: discontinue,
                        discontinue_reason: discontinueReasons,
                        fromOutsideAmount: outsideAmount,
                        'submit-others': 'true'
                    },
                    success: function (data) {

                        let response = JSON.parse(data);

                        if (response.hasOwnProperty('error')) {
                            $('#feedback-error').html(response.error);
                            $('#feedback-error').show();
                        } else if (response.hasOwnProperty('success')) {
                            $('#feedback-success').html(response.success);
                            $('#feedback-success').show();
                        }

                        // Inpatient_Nurse_Medicine
                        setTimeout(function () {
                            location.reload()
                        }, 3000);
                        console.log(response)
                    },
                    error: function (error) {
                        console.log(error)
                    }
                });


            }

        })

        // View discontinued medications
        $('#view-discontinued').on('click', function () {
            const registrationId = '<?= $Registration_ID ?>';
            const consultationId = '<?= $consultation_ID ?>';
            $.ajax({
                type: 'POST',
                url: '../Inpatient_nurse_medicineChart.php',
                data: {
                    Registration_ID: registrationId,
                    consultation_ID: consultationId,
                    medicationCanceled: ''
                },
                success: function (responce) {
                    $("#discontinued-services-list").dialog({
                        title: 'DISCOUNTINUED MEDICINE LIST',
                        width: '80%',
                        height: 550,
                        modal: true
                    });
                    $("#discontinued-services-list").html(responce);
                }
            });
        });

        // View completed medications
        $('#view-completed').on('click', function () {
            const registrationId = '<?= $Registration_ID ?>';
            const consultationId = '<?= $consultation_ID ?>';

            $.ajax({
                type: 'POST',
                url: '../Inpatient_nurse_medicineChart.php',
                data: {
                    Registration_ID: registrationId,
                    consultation_ID: consultationId,
                    CompletedMedication: ''
                },
                success: function (responce) {
                    $("#completed-services-list").dialog({
                        title: 'COMPLETED MEDICINE LIST',
                        width: '80%',
                        height: 550,
                        modal: true
                    });
                    $("#completed-services-list").html(responce);
                }
            });
        });

    });

    function validateExternalMedications() {
        let medicationName = $('#ot-medication-name');
        let timeGiven = $('#ot-time-given');
        let outsideAmount = $('#ot-outside-amount');
        let dosage = $('#ot-dosage');
        let quantity = $('#ot-quantity');
        let route = $('#ot-route');
        let dripRate = $('#ot-drip-rate');
        let remarks = $('#ot-remarks');
        let discontinue = $('#ot-discontinue');
        let discontinueReasons = $('#ot-discontinue-reason');

        if (!medicationName.val()) {
            medicationName.parent().addClass('has-error');
            alert('Please provide medication name.')
            return false;
        } else {
            medicationName.parent().removeClass('has-error');
        }

        if (!timeGiven.val()) {
            timeGiven.parent().addClass('has-error');
            alert('Please provide medication time.')
            return false;
        } else {
            timeGiven.parent().removeClass('has-error');
        }

        if (!dosage.val()) {
            dosage.parent().addClass('has-error');
            alert('Please provide medication dosage.')
            return false;
        } else {
            dosage.parent().removeClass('has-error');
        }

        if (!quantity.val()) {
            quantity.parent().addClass('has-error');
            alert('Please provide medication quantity.')
            return false;
        } else {
            quantity.parent().removeClass('has-error');
        }

        if (!route.val()) {
            route.parent().addClass('has-error');
            alert('Please provide medication route.')
            return false;
        } else {
            route.parent().removeClass('has-error');
        }

        if (discontinue.prop('checked') && !discontinueReasons.val()) {
            discontinueReasons.parent().addClass('has-error');
            alert('Please provide discontinuation reasons.')
            return false;
        } else {
            discontinueReasons.parent().removeClass('has-error');
        }

        return true;
    }

    function initializeRemoteSelect2() {
        let Registration_ID = '<?= $Registration_ID ?>';
        let consultation_ID = '<?= $consultation_ID ?>';

        $("#ot-medication-name").select2({
            ajax: {
                url: "../outside_others_medics.php",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        consultationType: 'Pharmacy', //consultation type
                        Reg_ID: Registration_ID,
                        consultation_ID: consultation_ID
                    };
                },
                processResults: function (data) {
                    // parse the results into the format expected by Select2.
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data
                    return {
                        results: data
                    };
                },
                cache: true
            },
            minimumInputLength: 1,
            placeholder: "Select medication name",
            allowClear: true
        });
    }

    function receive_medication() {

        var Consultation_ID = '<?= $consultation_ID; ?>';
        var selected_mediccation = [];
        $(".Payment_Item_Cache_List_ID:checked").each(function () {
            // selected_mediccation.push($(this).val());
            // $("#uncheckcheck"+id).prop('checked', false);
            var Payment_Item_Cache_List_ID = $(this).val();
            var remarks_new = $("#remarks_new" + Payment_Item_Cache_List_ID).val();
            selected_mediccation.push(Payment_Item_Cache_List_ID + "unganisha" + remarks_new)
        });
        if (selected_mediccation == '') {
            alert('Please select medication you want to receive');
            exit;
        }
        console.log(selected_mediccation)
        var medication_type_new = $("#medication_type_new").val();
        if (confirm("Are you sure you want to receive this Mediction?")) {
            $.ajax({
                type: 'POST',
                url: '../Inpatient_nurse_medicineChart.php',
                data: {
                    selected_mediccation: selected_mediccation,
                    Consultation_ID: Consultation_ID,
                    receivemedicationid: ''
                },
                success: function (data) {
                    // alert(data);
                    // location.reload();
                    Get_Medicines_Type(medication_type_new)
                }
            });
        }
    }

    function returnMedication() {

        var Consultation_ID = '<?= $consultation_ID; ?>';
        var selected_mediccation = [];
        $(".Payment_Item_Cache_List_ID:checked").each(function () {
            // selected_mediccation.push($(this).val());
            var Payment_Item_Cache_List_ID = $(this).val();
            var Quantity_remained = $("#Quantity_remained" + Payment_Item_Cache_List_ID).val();
            var remainedID = $("#Quantity_remainedID" + Payment_Item_Cache_List_ID).val();
            selected_mediccation.push(Payment_Item_Cache_List_ID + "unganisha" + Quantity_remained + 'unganisha' + remainedID)

        });
        if (selected_mediccation == '') {
            alert('Please select medication you want to receive');
            exit;
        }
        var medication_type_new = $("#medication_type_new").val();
        if (confirm("Are you sure you want to return this Mediction?")) {
            $.ajax({
                type: 'POST',
                url: '../Inpatient_nurse_medicineChart.php',
                data: {
                    selected_mediccation: selected_mediccation,
                    Consultation_ID: Consultation_ID,
                    returndicationid: ''
                },
                success: function (data) {
                    // alert(data);
                    // location.reload();
                    Get_Medicines_Type(medication_type_new)
                }
            });
        }
    }

    function RequireReason(instance) {
        if (instance.checked) {
            document.getElementById('discontinue_status').value = 'yes';
            $("#Display_Discontinue_Details").dialog('open');

        } else {
            $('#discontinue').attr("checked", false);
            document.getElementById('discontinue_status').value = 'no';
        }
        return true;
    }

    function rejectMedications() {
        const Consultation_ID = '<?= $consultation_ID; ?>';

        let selected_medications = [];
        let validated = true;

        $(".rejected_medications:checked").each(function () {
            const ids = $(this).val().split(',');
            const Payment_Item_Cache_List_ID = ids[0];
            const id = ids[1];
            let rejectionRemarks = $("#remarks_new" + id).val();
            if (!rejectionRemarks) {
                $("#remarks_new" + id).parent().addClass('has-error')
                validated = false;
            } else {
                $("#remarks_new" + id).parent().removeClass('has-error')
            }
            selected_medications.push(id + 'unganisha' + Payment_Item_Cache_List_ID + 'unganisha' + rejectionRemarks)
        });

        if (!validated) {
            alert('Please provide rejection reasons.');
            return;
        }

        if (!selected_medications.length) {
            alert('Please select medications to reject');
            return;
        }

        let medication_type_new = $("#medication_type_new").val();
        if (confirm("Are you sure you want to reject these medications ?")) {
            $.ajax({
                type: 'POST',
                url: '../Inpatient_nurse_medicineChart.php',
                data: {
                    selected_medications: selected_medications,
                    Consultation_ID: Consultation_ID,
                    reject_medications: 'reject_medications'
                },
                success: function (data) {
                    Get_Medicines_Type(medication_type_new);

                    setTimeout(function () {
                        location.reload()
                    }, 1000);
                }
            });
        }

    }

    function Get_Last_Given_Time(Payment_Item_Cache_List_ID) {
        var medication_type = document.getElementById('medication_type').value;
        supported(Payment_Item_Cache_List_ID, medication_type);

        check_if_medicine_has_been_discontinued(Payment_Item_Cache_List_ID, medication_type);
    }

    function check_if_medicine_has_been_discontinued(Item_ID, medication_type) {
        var Registration_ID = '<?= $Registration_ID ?>';
        $.ajax({
            type: 'POST',
            url: '../ajax_check_if_medicine_has_been_discontinued.php',
            data: {Item_ID: Item_ID, Registration_ID: Registration_ID},
            success: function (data) {
                if (data == "yes") {
                    $("#drug_discontinued_alaert").html("The Selected Medicine Has been discontinued to this patient&nbsp;&nbsp;&nbsp;&nbsp;<input type='button' value='CANCEL DISCONTINUATION' onclick='cancel_discontinuetion(" + Item_ID + "," + Registration_ID + ")' class='art-button-green' style='width:200px'>");
                } else {
                    $("#drug_discontinued_alaert").html("");
                }
            }
        });
    }

    function cancel_discontinuetion(Item_ID, Registration_ID, Payment_Item_Cache_List_ID) {
        if (confirm("Are you sure you want to undo Medicine Discontinuation")) {
            $.ajax({
                type: 'POST',
                url: '../ajax_cancel_discontinuetion.php',
                data: {Item_ID: Item_ID, Registration_ID: Registration_ID, Payment_Item_Cache_List_ID},
                success: function (data) {
                    if (data === "success") {
                        alert("Process Successfully");
                        location.reload();
                    } else {
                        alert("Process Fail...Try again");
                    }
                }
            });
        }
    }

    function supported(Item_ID, medication_type) {
        var registration_ID = '<?= $Registration_ID; ?>';

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                var resp = xhttp.responseText;

                if (resp == 'yes' && medication_type == 'others') {
                    // document.getElementById('demo').value='';
                    if (window.confirm('This item is not supported by this sponsor,the patient must pay cash.Are you sure you want to continue?')) {
                        takeItem(Item_ID);
                    } else {
                        window.location = "Inpatient_Nurse_Medicine.php?<?php echo $_SERVER['QUERY_STRING'] ?>";
                    }
                } else {
                    takeItem(Item_ID);
                }
            }
        }
        xhttp.open('GET', '../checkIfSupported.php?action&id=' + Item_ID + '&registration_ID=' + registration_ID, true);
        xhttp.send();
    }

    function takeItem(ItemID) {
        var Registration_ID = <?php echo $Registration_ID; ?>;
        var medication_type = document.getElementById('medication_type').value;
        var doseid = 'i' + Item_ID;
        var Item_ID = '';
        var dosage = '';
        if (medication_type == 'outside' || medication_type == 'others') {
            Item_ID = ItemID;
        } else {
            Item_ID = document.getElementById('t_' + ItemID).value;
            doseid = 'i' + ItemID;
            dosage = document.getElementById(doseid).value;
        }

        var consultation_ID = '<?php echo $consultation_ID; ?>';

        document.getElementById('dosage').value = dosage;
        document.getElementById('dosage').setAttribute('title', dosage);

        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }
        mm.onreadystatechange = function () {
            var LastDate = mm.responseText;

            document.getElementById('Last_Given_Time').innerHTML = "<input size='50' type='text' disabled='disabled' title='" + LastDate + "' value='" + LastDate + "' />";

            Get_Lapsed_Time(LastDate);
        };

        mm.open('GET', '../Medicine_Last_Given_Time.php?Item_ID=' + Item_ID + '&Reg_ID=' + Registration_ID + '&consultation_ID=' + consultation_ID, true);
        mm.send();
    }

    function Get_Lapsed_Time(LastTimeGiven) {
        if (window.XMLHttpRequest) {
            lt = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            lt = new ActiveXObject('Micrsoft.XMLHTTP');
            lt.overrideMimeType('text/xml');
        }

        lt.onreadystatechange = function () {
            var Lapsed_Time = lt.responseText;
            //alert(Lapsed_Time);
            document.getElementById('Lapsed_Time').innerHTML = "<input size='50' type='text' disabled='disabled' title='" + Lapsed_Time + "' value='" + Lapsed_Time + "' />";
        };

        lt.open('GET', '../Service_Lapsed_Time.php?LastTimeGiven=' + LastTimeGiven, true);
        lt.send();
    }

    function showStatus() {
        var medicationname = document.getElementById('medication_name').value;
        var amount_givenval = document.getElementById('amount_given_val').value;
        var status = document.getElementById("savechart").value;
        var disc_reason = document.getElementById("discontinue_reason_dialog").value;
        var medication_time = document.getElementById("medication_time").value;

        if (status == 'save') {
            if ($('#discontinue').prop('checked')) {
                //document.getElementById('amount_given_val').value=0;
                return true;
            }
            if (medication_time == "") {
                alert("Please select time given");
                return false;
            }
            if (medicationname == '') {
                alert("Please select medication to continue");
                return false;
            } else if (isNaN(amount_givenval)) {
                alert("Please enter a valid quantity number");
                return false;
            } else if (amount_givenval <= 0 || amount_givenval == '') {
                if (confirm("Quantity should be grater than 0.Are sure you want to continue")) {
                    return true;
                }
                //alert("Quantity should be grater than 0");
                return false;
            } else {
                if (confirm('Are you sure you want to save patient medication administration?')) {
                    //document.getElementById('myFormMedication').submit();
                    return true;
                }
            }
        } else if (status == 'cant') {
            alert("You can't save the medication. You must first collect it from pharmacy.");
            return false;
        }
    }

    function Get_Medicines_Type(Status) {
        if (Status == 'active') {
            document.getElementById("savechart").value = 'cant';
        } else {
            document.getElementById("savechart").value = 'save';
        }
        if (Status == "outside" || Status == "others") {
            $("#old_drug_table").show();
            $("#new_drag_sheet").hide();
            if (Status == "outside") {
                $("#medication_type").html("<option>outside</option>");
            }
            if (Status == "others") $("#medication_type").html("<option>others</option>");
        } else {
            $("#old_drug_table").hide();
            $("#new_drag_sheet").show();
        }

        var Registration_ID = '<?= $Registration_ID; ?>';
        var consultation_ID = '<?= $consultation_ID; ?>';
        if (window.XMLHttpRequest) {
            medics = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            medics = new ActiveXObject('Micrsoft.XMLHTTP');
            medics.overrideMimeType('text/xml');
        }
        document.getElementById('new_drag_sheet').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

        medics.onreadystatechange = function () {
            var medicines_select = medics.responseText;
            if (Status == "outside" || Status == "others") {
                document.getElementById('medicine_here').innerHTML = medicines_select;
            } else {
                document.getElementById('new_drag_sheet').innerHTML = medicines_select;
            }

            $('select').select2();


            $('.date_n_time').datetimepicker({
                dayOfWeekStart: 1,
                lang: 'en',
                startDate: 'now'
            });
            $('.date_n_time').datetimepicker({value: '', step: 1});
            if (Status == 'outside' || Status == 'others') {
                initializeRemoteSelect2();
            }


        };

        medics.open('GET', '../Get_Inpatient_Medication.php?Status=' + Status + '&Reg_ID=' + Registration_ID + '&consultation_ID=' + consultation_ID, true);
        medics.send();
    }
</script>

