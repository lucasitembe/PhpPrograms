<?php
include "./includes/connection.php";
$test=$_GET['test'];
$paymentID=$_GET['paymentID'];

echo "<script>
    var test=$test;
    var paymentID=$paymentID;
</script>";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Results View</title>
    <link rel="stylesheet" href="./lib/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./lib/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="./lib/jquery-datetimepicker/build/jquery.datetimepicker.min.css">
    <link rel="stylesheet" href="./lib/jquery-toast-plugin/dist/jquery.toast.min.css">
    <link rel="stylesheet" href="./lib/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="./lib/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="./lib/datatables/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="./lib/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="./laboratory/static/css/global_style.css">
</head>

<body>
    <div class="container-fluid mt-4">
        <div id="spinner-loaders">
            <div class="d-flex justify-content-center">
                <div class="spinner-grow text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="spinner-grow text-secondary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="spinner-grow text-success" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="spinner-grow text-danger" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="spinner-grow text-warning" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="spinner-grow text-info" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="spinner-grow text-light" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="spinner-grow text-dark" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
        <div class="row border p-3 rounded mb-3 init-hide" id="cultureData">
            <div id="content" class="bg-light">
                <div class="row">
                    <div class="col-sm-5 border rounded m-3">
                        <div class="row">
                            <div class="col-4">
                                <span class="fw-bold fs-4">Sample Type</span>
                            </div>
                            <div class="col-6">
                                <span id="specimenType" class="fs-4"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5 border rounded m-3">
                        <div class="row">
                            <div class="col-4">
                                <span class="fw-bold fs-4">Anatomical Site</span>
                            </div>
                            <div class="col-5">
                                <span id="anatomical" class="fs-4"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 border">
                        <div class="bg-success row p-2">
                            <h5 class="text-center fw-bold my-auto text-light">FLUIDS</h5>
                        </div>
                        <div class="border row mb-3 p-2">
                            <h5 class="text-center fw-bold my-auto">MACROSCOPIC EXAMINATION</h5>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-2"><span class="fw-bold fs-4">Colour:</span></div>
                            <div class="col-md-6">
                                <span id="colour" class="fs-4"></span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col d-inline">
                                <div class="row">
                                    <div class="col-1 rounded-circle bg-success init-hide" style="height: 2rem;"
                                        id="turbidity">
                                        <i class="fas fa-check text-light text-center"></i>
                                    </div>
                                    <div class="col">
                                        <label for="turbidity" class="fs-4">
                                            Turbidity
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col d-inline">
                                <div class="row">
                                    <div class="col-1 rounded-circle bg-success init-hide" style="height: 2rem;"
                                        id="blood_presence">
                                        <i class="fas fa-check text-light text-center"></i>
                                    </div>
                                    <div class="col">
                                        <label for="blood_presence" class="fs-4">
                                            Presence of blood
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col d-inline">
                                <div class="row">
                                    <div class="col-1 rounded-circle bg-success init-hide" style="height: 2rem;"
                                        id="other_blood">
                                        <i class="fas fa-check text-light text-center"></i>
                                    </div>
                                    <div class="col">
                                        <label for="other_blood" class="fs-4">
                                            Others
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="other_bloods mb-3 init-hide">
                            <div class="row">
                                <div class="col-md-4"><span class="fw-bold fs-4">Specified Blood Check:</span></div>
                                <div class="col-md-8">
                                    <p class="fs-4" id="blood_specification"></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row border rounded m-1">
                                    <div class="col-md-8"><span class="fw-bold fs-4">WBC:</span></div>
                                    <div class="col-md-4">
                                        <span id="wbc" class="fs-4"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row border rounded m-1">
                                    <div class="col-md-8"><span class="fw-bold fs-4">RBC:</span></div>
                                    <div class="col-md-4">
                                        <span id="rbc" class="fs-4"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row border rounded m-1">
                                    <div class="col-md-8"><span class="fw-bold fs-4">Differential Count:</span></div>
                                    <div class="col-md-4">
                                        <span id="deffCount" class="fs-4"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row border rounded m-1">
                                    <div class="col-md-8"><span class="fw-bold fs-4">Total Protein:</span></div>
                                    <div class="col-md-4">
                                        <span id="totalProtein" class="fs-4"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row border rounded m-1">
                                    <div class="col-md-8"><span class="fw-bold fs-4">Mononuclear:</span></div>
                                    <div class="col-md-4">
                                        <span id="mononuclear" class="fs-4"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row border rounded m-1">
                                    <div class="col-md-8"><span class="fw-bold fs-4">Total Cell Count:</span></div>
                                    <div class="col-md-4">
                                        <span id="totalCells" class="fs-4"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row border rounded m-1">
                                    <div class="col-md-8"><span class="fw-bold fs-4">Polymonuclear:</span></div>
                                    <div class="col-md-4">
                                        <span id="polymonuclear" class="fs-4"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row border rounded m-1">
                                    <div class="col-md-8"><span class="fw-bold fs-4">Glucose:</span></div>
                                    <div class="col-md-4">
                                        <span id="glucose" class="fs-4"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row border rounded m-1">
                                    <div class="col-md-8"><span class="fw-bold fs-4">Pandy's Test:</span></div>
                                    <div class="col-md-4">
                                        <span id="pandyTest" class="fs-4"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row border rounded m-1">
                                    <div class="col-md-8"><span class="fw-bold fs-4">Leucocytes:</span></div>
                                    <div class="col-md-4">
                                        <span id="leucocytes" class="fs-4"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="border p-1">
                                <h5 class="text-center fw-bold my-auto">STAINS</h5>
                            </div>
                            <div class="col-12">
                                <div class="row border-bottom rounded m-1">
                                    <div class="col-md-4"><span class="fw-bold fs-4">Indian Ink:</span></div>
                                    <div class="col-md-8">
                                        <p id="indianInk" class="fs-4"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row border-bottom rounded m-1">
                                    <div class="col-md-4"><span class="fw-bold fs-4">Gram Stain:</span></div>
                                    <div class="col-md-8">
                                        <p id="macroGramStain" class="fs-4"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row border-bottom rounded m-1">
                                    <div class="col-md-4"><span class="fw-bold fs-4">Zn Stain:</span></div>
                                    <div class="col-md-8">
                                        <p id="znStain" class="fs-4"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row border-bottom rounded m-1">
                                    <div class="col-md-4"><span class="fw-bold fs-4">IMMY TEST(Cryptococcus):</span>
                                    </div>
                                    <div class="col-md-8">
                                        <p id="immyTest" class="fs-4"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 border">
                        <div class="bg-success row p-2">
                            <h5 class="text-center fw-bold my-auto text-light">PUS SWABS/SKIN SCRAPINGS</h5>
                        </div>
                        <div class="border row  mb-3 p-2">
                            <h5 class="text-center fw-bold my-auto">MICROSCOPIC EXAMINATION</h5>
                        </div>
                        <div class="row border rounded">
                            <div class="row mb-3">
                                <div class="col-md-5"><span class="fw-bold fs-4">Wet Preparation:</span></div>
                                <div class="col-md-7">
                                    <span id="wet_prep" class="fs-4"></span>
                                </div>
                            </div>
                            <div class="row other_wets init-hide">
                                <div class="row">
                                    <div class="col-md-5"><span class="fw-bold">Specified Wet Preparation:</span></div>
                                    <div class="col-md-7">
                                        <p class="fs-4" id="wet_specification"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="border mb-3 p-2">
                                <h5 class="text-center fw-bold my-auto">STAINS</h5>
                            </div>
                            <div class="col-12">
                                <div class="row border-bottom rounded m-1">
                                    <div class="col-md-4"><span class="fw-bold fs-4">Gram Stain:</span></div>
                                    <div class="col-md-8">
                                        <span id="microGramStain" class="fs-4"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row border-bottom rounded m-1">
                                    <div class="col-md-4"><span class="fw-bold fs-4">Zn Stain:</span></div>
                                    <div class="col-md-8">
                                        <span id="microZnStain" class="fs-4"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row border-bottom rounded m-1">
                                    <div class="col-md-4"><span class="fw-bold fs-4">Giemsa:</span></div>
                                    <div class="col-md-8">
                                        <span id="giemsa" class="fs-4"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row border-bottom rounded m-1">
                                    <div class="col-md-4"><span class="fw-bold fs-4">Others(Specify):</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span id="otherMicroStain" class="fs-4"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="border bg-success mb-3 p-2">
                                <h5 class="text-center fw-bold my-auto text-light">SKIN SCRAPING</h5>
                            </div>
                            <div class="mb-3">
                                <span class="fw-bold fs-4">Microscopic Examination(KOH prep.):</span>
                                <div class="border rounded p-1">
                                    <p class="fs-4" id="kohPrep"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 border">
                        <div class="mb-3">
                            <span class="fw-bold fs-4">CULTURE RESULTS(Isolates):</span>
                            <div class="border rounded p-1" id="cultureResults">
                                <p class="fs-4"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col border">
                                        <h5 class="fw-bold fs-4">Sensitive to</h5>
                                    </div>
                                    <div class="col border">
                                        <h5 class="fw-bold fs-4">Intermediate / Partially Sensitive to</h5>
                                    </div>
                                    <div class="col border">
                                        <h5 class="fw-bold fs-4">Resistant to</h5>
                                    </div>
                                </div>
                                <div class="reaction_check">
                                    <div class="row">
                                        <div class="col border py-2">
                                            <span id="sensitive" class="fs-4"></span>
                                        </div>
                                        <div class="col border py-2">
                                            <span id="intermidiate" class="fs-4"></span>
                                        </div>
                                        <div class="col border py-2">
                                            <span id="resistant" class="fs-4"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="row">
                    <div class="col-2">
                        <span class="fw-bold fs-4">Performed BY:</span>
                    </div>
                    <div class="col">
                        <span class="fs-4" id="performer"></span>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
    <!-- <script src="./lib/jquery/dist/jquery.min.js"></script> -->
    <script src="./lib/jspdf/html_canvas.js"></script>
    <script src="./lib/jspdf/jspdf.debug.js"></script>

</body>
<script>
var hasToggledOtherWet = false;
var hasToggledBloodCheck = false;
// $(document).ready(function() {
$.ajax({
    type: "GET",
    url: "./laboratory/services/manage_culture_results.php",
    data: {
        action: "checkTestAvailability",
        test: test,
        paymentID: paymentID,
    },
    success: function(response) {
        response = JSON.parse(response)
        if (response.status == "results found") {
            var results = response.data;
            var reaction_check = JSON.parse(results.reaction_check);
            var sensitive = reaction_check.sensitive
            var resistant = reaction_check.resistant
            var intermidiate = reaction_check.intermidiate

            $("#specimenType").html(results.Specimen_Name);
            $("#anatomical").html(results.anatomical_name);
            $("#wet_prep").html(results.wet_preparation);

            if (results.wet_preparation == "others") {
                $("#wet_specification").html(results.wet_specification);
                $(".other_wets").toggle("init-hide");
                hasToggledOtherWet = true;
            }
            if (results.blood_check == "others") {
                $("#other_blood").removeClass("init-hide");
                $("#blood_specification").html(results.blood_specification);
                $(".other_bloods").toggle("init-hide");
                hasToggledBloodCheck = true;
            } else {
                if (results.blood_check == "Turbidity") {
                    $("#turbidity").removeClass("init-hide");
                } else {
                    $("#blood_presence").removeClass("init-hide");
                }
            }
            $("#colour").html(results.colour);
            $("#Culture_ID").html(results.Culture_ID);
            $("#wbc").html(results.wbc);
            $("#rbc").html(results.rbc);
            $("#deffCount").html(results.deffCount);
            $("#totalProtein").html(results.totalProtein);
            $("#mononuclear").html(results.mononuclear);
            $("#totalCells").html(results.totalCells);
            $("#polymonuclear").html(results.polymonuclear);
            $("#glucose").html(results.glucose);
            $("#pandyTest").html(results.pandyTest);
            $("#leucocytes").html(results.leucocytes);
            $("#indianInk").html(results.indianInk);
            $("#macroGramStain").html(results.macroGramStain);
            $("#znStain").html(results.znStain);
            $("#immyTest").html(results.immyTest);
            $("#microGramStain").html(results.microGramStain);
            $("#microZnStain").html(results.microZnStain);
            $("#giemsa").html(results.giemsa);
            $("#otherMicroStain").html(results.otherMicroStain);
            $("#kohPrep").html(results.kohPrep);
            $("#performer").html(results.Employee_Name);
            $("#cultureResults").html(results.cultureResults);
            $("#sensitive").html(sensitive[0]);
            $("#resistant").html(resistant[0]);
            $("#intermidiate").html(intermidiate[0]);

            if (sensitive.length > 0) {
                for (let i = 1; i < sensitive.length; i++) {
                    var content = "<div class = 'row'>" +
                        "<div class = 'col border py-2' >" +
                        "<span class='fs-4'>" + sensitive[i] + "</span>" +
                        "</div> <div class = 'col border py-2' >" +
                        "<span class='fs-4'>" + intermidiate[i] + "</span>" +
                        "</div> <div class = 'col border py-2' >" +
                        "<span class='fs-4'>" + resistant[i] + "</span></div>" +
                        "</div>";
                    $(".reaction_check").append(content);
                }
            }
            $("#spinner-loaders").hide();
            $("#cultureData").removeClass("init-hide");
            // let pdf = new jsPDF();
            // let section = $('#content').html();
            // let page = function() {
            //     pdf.save('macro_micro_report.pdf');
            // };
            // pdf.fromHTML(section, page);
            // pdf.output('datauri');
            // pdf.save('document.pdf');
            // var output = pdf.output();
            // return btoa(output);
        } else {
            $("#spinner-loaders").hide();
            $("#cultureData").removeClass("init-hide");
        }
    }
});
// });
</script>

</html>