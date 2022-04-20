<?php
include('new_header.php');
include 'form_four_functions.php';
include 'partials/get_patient_details.php';
$form_name = 'LABORATORY REPORT';

$names = array('Calcium','Magnesium','Chloride','Potassium','Sodium','Bicarbonate', 'Blood Glucose', 'HB', 'ABG');

$data = getPreviousData($Registration_ID, $consultation_ID);

?>

    <a href="#" class="btn btn-danger font-weight-bold" id="form_four_records">PREVIEW RECORDS</a>
    <button class="btn btn-success font-weight-bold" id="lab-results">LABORATORY RESULTS</button>
    <button class="btn btn-warning font-weight-bold" id="lab-records">LABORATORY RECORDS</button>

<a href="icu.php?consultation_ID=<?= $consultation_ID; ?>&Registration_ID=<?= $_GET['Registration_ID']; ?>&Admision_ID=<?= $Admision_ID ?>"
   class="btn btn-primary">BACK</a>

<?php include 'partials/new_patient_info.php' ?>
<div class="bg-white pb-5 pt-5 px-4">
    <div class="row border-bottom">
        <div class="col-md-4 px-1 border-bottom">
            <div class="container py-2">
                <div class="row">
                    <label class="col-form-label col-sm-4 text-right font-weight-bold label">Time</label>
                    <div class="col-sm-8">
                        <input class="form-control form-inputs datetime bg-white" type="text" readonly>
                    </div>
                </div>
            </div>
        </div>
        <?php for ($i = 0; $i < sizeof($names); $i++) : ?>
            <div class="col-md-4 px-1 border-bottom">
                <div class="container py-2">
                    <div class="row">
                        <label class="col-form-label col-sm-4 text-right font-weight-bold label"><?= $names[$i] ?></label>
                        <div class="col-sm-8">
                            <input class="form-control form-inputs" type="text">
                            <small><?= isset($data[$names[$i]]) ? 'Previous: ' . $data[$names[$i]] : '' ?></small>

                        </div>
                    </div>
                </div>
            </div>
        <?php endfor; ?>

    </div>
    <div class="row my-5">
        <div class="col d-flex flex-column align-items-center" style="min-width: 600px;">
            <b class="text-center">Location, Staging, Description and Treatment.</b>
            <div id="wPaint" class="my-2 mx-auto"
                 style="position:relative; width:508px; height:500px; background-color:#ffffff;">
            </div>
            <div>
                <button class="btn btn-danger py-1" id="clear-canvas">
                    <i class="fa fa-times-circle mr-2"></i>
                    Clear
                </button>
                <button class="btn btn-secondary text-gray py-1" id="undo">
                    <i class="fa fa-undo mr-2"></i>
                    Undo
                </button>
                <button class="btn btn-primary" id="redo">
                    <i class="fa fa-repeat mr-2"></i>
                    Redo
                </button>
<!--                <button class="btn btn-success" id="getimage">-->
<!--                    <i class="fa fa-save mr-2"></i>-->
<!--                    Get Image-->
<!--                </button>-->
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

            <div class="row mb-3">
                <div class="col-md-6">
                    <div>
                        <button class="btn my-1 me-2 pen-color"
                                style="width: 50px; height: 35px; background-color: red;" color="red"></button>
                        <span>Bedsore</span>
                    </div>
                    <div>
                        <button class="btn my-1 me-2 pen-color"
                                style="width: 50px; height: 35px; background-color: blue;" color="blue"></button>
                        <span>Lipsore</span>
                    </div>
                    <div>
                        <button class="btn my-1 me-2 pen-color"
                                style="width: 50px; height: 35px; background-color: teal;" color="teal"></button>
                        <span>Rash</span>
                    </div>
                    <div>
                        <button class="btn my-1 me-2 pen-color"
                                style="width: 50px; height: 35px; background-color: green;" color="green"></button>
                        <span>Bruise</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div>
                        <button class="btn my-1 me-2 pen-color"
                                style="width: 50px; height: 35px; background-color: orange;"
                                color="orange"></button>
                        <span>Blister</span></div>
                    <div>
                        <button class="btn my-1 me-2 pen-color"
                                style="width: 50px; height: 35px; background-color: purple;"
                                color="purple"></button>
                        <span>Edema</span></div>
                    <div>
                        <button class="btn my-1 me-2 pen-color"
                                style="width: 50px; height: 35px; background-color: pink;"
                                color="pink"></button>
                        <span>Burn</span></div>
                    <div>
                        <button class="btn my-1 me-2 pen-color"
                                style="width: 50px; height: 35px; background-color: black;"
                                color="black"></button>
                        <span>Fracture</span></div>
                </div>
            </div>


            <div class="my-2">
                <label class="my-2 font-weight-bold">Comments: </label>
                <textarea class="form-control" rows="8" id="comments"></textarea>
            </div>
        </div>

    </div>

    <div style="text-align: center;">
        <button class="btn btn-primary pl-2" id="submit_data" style="width: 180px;">
            <i class="fa fa-check mr-2"></i>
            SAVE
        </button>
    </div>
</div>

<div id="display_form_four"></div>

<div id="records_preview_dialog">
    <!-- Remove autofocus -->
    <input autofocus="true" type="hidden" autocomplete="off">
    <div class="row my-3 d-flex border-bottom mb-3 pb-3">
        <div class="col-md-5">
            <div class="d-flex align-items-center">
                <label class="mx-3 font-weight-bold">From</label>
                <input  type='date' style='width:100%' id="from-date" autocomplete="off">
            </div>
        </div>
        <div class="col-md-5">
            <div class="d-flex align-items-center">
                <label class="mx-3 font-weight-bold">To</label>
                <input type='date' style='width:100%'  id="to-date" autocomplete="off">
            </div>
        </div>
        <div class="col-md-2">
            <button class="btn btn-danger px-md-5 py-1" id="filter">Filter</button>
        </div>
    </div>
    <div id="dialog-content"></div>
</div>

<div id="laboratory-records">
    <!-- Remove autofocus -->
    <input autofocus="true" type="hidden" autocomplete="off">
    <div class="row my-3 d-flex border-bottom mb-3 pb-3">
        <div class="col-md-5">
            <div class="d-flex align-items-center">
                <label class="mx-3 font-weight-bold">From</label>
                <input class="form-control py-1 datetime" id="lr-from-date" autocomplete="off">
            </div>
        </div>
        <div class="col-md-5">
            <div class="d-flex align-items-center">
                <label class="mx-3 font-weight-bold">To</label>
                <input class="form-control py-1 datetime" id="lr-to-date" autocomplete="off">
            </div>
        </div>
        <div class="col-md-2">
            <button class="btn btn-danger px-md-5 py-1" id="lr-filter">Filter</button>
        </div>
    </div>
    <div id="laboratory-records-content"></div>
</div>

<div id="lab-results-dialog">
    <div class="d-flex align-items-center border-bottom py-3">
        <label class="font-weight-bold text-nowrap me-3">Select Consultation Type</label>
        <select onchange="consultResult(this.value)" id="consType" class="form-select">
            <option selected disabled>SELECT CONSULTATION TYPE</option>
            <option>Pharmacy</option>
            <option>Laboratory</option>
            <option>Radiology</option>
            <option>Surgery</option>
            <option>Procedure</option>
            <option>Others</option>
        <select>
    </div>

    <div id="progressStatus"  style="display: none;">
        <img class="mx-auto d-block my-5" src="../images/ajax-loader_1.gif" style="border-color:white;">
    </div>

    <div id="lab-results-content"></div>
</div>

<div id="labGeneral" style="display: none">
    <div id="showGeneral"></div>
</div>

<script>
    $(document).ready(function(){
        $('.datetime').datetimepicker();

        $("#records_preview_dialog").dialog({
            autoOpen: false,
            minWidth: 600,
            modal: true,
            title: 'ICU FORM FOUR RECORDS PREVIEW',
            width: "75%",
            height: 650,
        });

        $("#lab-results-dialog").dialog({
            autoOpen: false,
            minWidth: 600,
            modal: true,
            title: 'Laboratory Results',
            width: "75%",
            height: 650,
        });

        var dialog = $('#records_preview_dialog');

        // Initalize wpaint
        var wpaint = $('#wPaint').wPaint({
            menuOffsetLeft: -35,
            menuOffsetTop: -50,
            bg: 'assets/anatomical_position.png',
            imageStretch: true,
            // fillStyle: 'red',
            strokeStyle: 'red'
            // saveImg: saveImg,
            // loadImgBg: loadImgBg,
            // loadImgFg: loadImgFg,
            // theme: 'standard'
        });

        // Signature pad events
        $('#clear-canvas').click(function () {
            wpaint.wPaint('clear');
        });

        $('.pen-color').click(function () {
            wpaint.wPaint('strokeStyle', $(this).attr('color'));
        });

        $('#undo').click(function () {
            wpaint.wPaint('undo');
        });

        $('#redo').click(function () {
            wpaint.wPaint('redo');
        });

        $('#getimage').on('click', function (){
            var imageData = $("#wPaint").wPaint("image");

            console.log(imageData)
        })

        // Other events
        $('#form_four_records').click(function (){
            openLabReportsDialog(dialog);
        });

        $('#lab-results').click(function (){
            $('#lab-results-dialog').dialog('open');
        })

        // Submit form four
        $('#submit_data').click(function (){

            var labels = [];
            $('.label').map(function () {
                labels.push($(this).html());
            });

            var formInputs = [];
            var isEmpty = true;
            $('.form-inputs').map(function () {
                formInputs.push($(this).val());
                if ($(this).val()){
                    isEmpty = false;
                }
            });

            var comments = $('#comments').val();

            if (comments){
                isEmpty = false;
            }

            if (isEmpty) {
                alert('Please provide atleast one input.');
                return;
            }

            var imageInput = wpaint.wPaint("image");

            var employee_id = '<?=$_SESSION['userinfo']['Employee_ID']?>';
            var Registration_ID = '<?=$_GET['Registration_ID']?>';
            var consultation_ID = '<?= $consultation_ID ?>';
            labels = JSON.stringify(labels);
            formInputs = JSON.stringify(formInputs);

            if (confirm('Are you sure you want to save ?')) {
                $.ajax({
                    type: 'POST',
                    url: 'form_four_store.php',
                    data: {
                        Registration_ID: Registration_ID,
                        consultation_ID: consultation_ID,
                        employee_id: employee_id,
                        labels: labels,
                        form_inputs: formInputs,
                        anatomical_position_drawing: imageInput,
                        comments: comments,
                        form_type: 'form_four'
                    },
                    success: function (result) {
                        console.log(result);
                        location.reload();
                    },
                    error: function (err, msg, errorThrows) {
                        alert(err);
                    }
                });

            }
        });


        $('#filter').click(function () {
            // validate dates
            var from = $('#from-date').val();
            var to = $('#to-date').val();

            var Registration_ID = '<?= $Registration_ID ?>';
            var consultation_ID = '<?= $consultation_ID ?>';

            if (!from || !to){
                alert('Please provides both dates');
                return;
            }

            // make request
            $.get(
                'form_four_preview_list.php', {
                    records_list: 'form_four',
                    Registration_ID: Registration_ID,
                    consultation_ID: consultation_ID,
                    from: from,
                    to: to
                }, function (response){
                    $('#dialog-content').html(response)
                    // dialog.dialog('open');

                    // Initialize datatable
                    $('.data-table').dataTable()
                }
            );
            // raise modal
        });
    });


    // Preview Lab Report Records
    function openLabReportsDialog(dialog) {
        var Registration_ID = '<?= $Registration_ID ?>';
        var consultation_ID = '<?= $consultation_ID ?>';

        $.get(
            'form_four_preview_list.php', {
                records_list: 'form_four',
                Registration_ID: Registration_ID,
                consultation_ID: consultation_ID
            }, function (response){
                $('#dialog-content').html(response)
                dialog.dialog('open');

                // Initialize datatable
                $('.data-table').dataTable({
                    "bJQueryUI": true,
                    scrollY: '385px',
                    // scrollCollapse: true,
                    // paging:         false
                })
            }
        );
    }

    // Get Results
    function consultResult(consultType) {
        //alert(consultType+' '+href+' '+consultedDate+' '+Registration_ID);
        // var datastring = href + '&consultedDate=' + consultedDate + '&consultType=' + consultType;
        var Registration_ID = '<?= $Registration_ID ?>';
        var consultation_ID = '<?= $consultation_ID ?>';

        $.ajax({
            type: 'GET',
            url: '../requests/PatientDetailsResults.php',
            data: {
                consultType: consultType,
                Registration_ID: Registration_ID,
                consultation_ID: consultation_ID,
                from: 'icu'
            },
            beforeSend: function (xhr) {
                $("#progressStatus").show();
            },
            success: function (result) {
                $("#lab-results-content").html(result);
            }, complete: function () {
                $("#progressStatus").hide();
            }, error: function (err, msg, errorThrows) {
                alert(err);
            }
        });

    }

</script>

<script>
    // Previous Lab Records
    var Registration_ID = '<?= $Registration_ID ?>';
    var consultation_ID = '<?= $consultation_ID ?>';

    $(document).ready(function (){
        $("#laboratory-records").dialog({
            autoOpen: false,
            minWidth: 600,
            modal: true,
            title: 'Laboratory Records',
            width: "90%",
            height: 615
        });

        $('#lab-records').click(function(){
            getRecords({
                Registration_ID: Registration_ID,
                consultation_ID: consultation_ID
            });
        });

        $('#lr-filter').click(function (){
            var from = $('#lr-from-date').val();
            var to = $('#lr-to-date').val();

            if (!from || !to){
                alert('Please provides both dates');
                return;
            }

            getRecords({
                Registration_ID: Registration_ID,
                consultation_ID: consultation_ID,
                to: to,
                from: from
            });
        })
    })

    function getRecords(data){
        $.get('form_four_lab_records.php', data,
            function (response){
                $('#laboratory-records-content').html(response)
                $('#laboratory-records').dialog('open');

                // Initialize datatable
                $('.records-table').dataTable({
                    "bJQueryUI": true,
                    scrollY: '350px',
                    // scrollCollapse: true,
                    // paging:         false
                })
            }
        );
    }

</script>

<script src="../js/signature-pad/signature_pad.js" rel="script"></script>
<script type="text/javascript" src="../js/wpaint/lib/jquery.ui.core.1.10.3.min.js"></script>
<script type="text/javascript" src="../js/wpaint/lib/jquery.ui.widget.1.10.3.min.js"></script>
<script type="text/javascript" src="../js/wpaint/lib/jquery.ui.mouse.1.10.3.min.js"></script>
<script type="text/javascript" src="../js/wpaint/lib/jquery.ui.draggable.1.10.3.min.js"></script>

<!-- wColorPicker -->
<link rel="Stylesheet" type="text/css" href="../js/wpaint/lib/wColorPicker.min.css"/>
<script type="text/javascript" src="../js/wpaint/lib/wColorPicker.min.js"></script>

<!-- wPaint -->
<link rel="Stylesheet" type="text/css" href="../js/wpaint/wPaint.min.css"/>
<script type="text/javascript" src="../js/wpaint/wPaint.min.js"></script>
<script type="text/javascript" src="../js/wpaint/plugins/main/wPaint.menu.main.min.js"></script>

<link rel="stylesheet" href="../js/datatable/datatables.min.css" media="screen">
<script src="../js/datatable/datatables.min.js" type="text/javascript"></script>
<?php
include("footer.php");
?>
