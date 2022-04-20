<?php
include "../helpers/header.php";
$test=$_POST['test'];
$paymentID=$_POST['paymentID'];
// die("---------------------".$test."---------------------".$paymentID);
echo "<script>
    var test=$test;
    var paymentID=$paymentID;
</script>";
// die("------------------".$_POST['viewOnly']);
if (isset($_POST['viewOnly']) && $_POST['viewOnly']==true) {
    echo "<script>
    var viewOnly=true;
</script>";
}

else{ echo "<script>
    var viewOnly=false;
</script>";
}
?>
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
    <form id="cultureForm" class="init-hide">
        <input type="hidden" class="form-control" name="Culture_ID" id="Culture_ID" value="">
        <input type="hidden" class="form-control" name="paymentID" id="paymentID" value="<?php echo $paymentID?>">
        <input type="hidden" class="form-control" name="test" id="test" value="<?php echo $test?>">
        <div class="row border p-3 rounded mb-3">
            <div class="row">
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-2">
                            <span class="fw-bold">Sample Type</span>
                        </div>
                        <div class="col-6">
                            <select class='seleboxorg3' name='specimenType' id='specimenType'>
                                <?php
                 $query_sub_specimen = mysqli_query($conn,"SELECT Specimen_Name,Specimen_ID FROM tbl_laboratory_specimen WHERE Status='Active'") or die(mysqli_error($conn));
                  echo '<option value="All">~~~~~Select Specimen~~~~~</option>';
                 while ($row = mysqli_fetch_array($query_sub_specimen)) {
                  echo '<option value="' . $row['Specimen_ID'] . '">' . $row['Specimen_Name'] . '</option>';
                 }
                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-1">
                            <button type="button" class="btn btn-outline-success btn-sm px-4 py-0"
                                onclick='addspecimen();'>
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div class="col-1">
                            <button type="button" class="btn btn-outline-danger btn-sm px-4 py-0"
                                onclick='removeSpecimen();'>
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-2">
                            <span class="fw-bold">Anatomical Site</span>
                        </div>
                        <div class="col-5">
                            <select class='anatomical' name='anatomical' id='anatomical'>
                                <?php
                 $query_sub_specimen = mysqli_query($conn,"SELECT id,name FROM tbl_anatomical_site") or die(mysqli_error($conn));
                  echo '<option value="All">~~~~~Select Anatomical Site~~~~~</option>';
                 while ($row = mysqli_fetch_array($query_sub_specimen)) {
                  echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                 }
                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="ml-auto">
                            <button type="button" class="btn btn-outline-success btn-sm px-4 py-0"
                                onclick='addAnatomicalSite();'>
                                <i class="fas fa-plus"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm px-4 py-0">
                                <i class="fas fa-times"></i>
                            </button>
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
                        <div class="col-md-2"><span class="fw-bold">Colour:</span></div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="colour" id="colour">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col icheck-success d-inline">
                            <input type="radio" class="blood_check" name="blood_check" id="turbidity" value="Turbidity">
                            <label for="turbidity">
                                Turbidity
                            </label>
                        </div>
                        <div class="col icheck-success d-inline">
                            <input type="radio" class="blood_check" name="blood_check" id="blood_presence"
                                value="Blood Presence">
                            <label for="blood_presence">
                                Presence of blood
                            </label>
                        </div>
                        <div class="col icheck-success d-inline">
                            <input type="radio" class="blood_check" name="blood_check" id="other_blood" value="others">
                            <label for="other_blood">
                                Others
                            </label>
                        </div>
                    </div>
                    <div class="row other_bloods init-hide mb-3">
                        <label for="blood_specification">Specify Blood Check</label>
                        <input type="text" class="form-control" name="blood_specification" id="blood_specification"
                            placeholder="specify">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-md-6"><span class="fw-bold">WBC:</span></div>
                                <div class="col-md-6">
                                    <input type="number" class="form-control" name="wbc" id="wbc">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-md-6"><span class="fw-bold">RBC:</span></div>
                                <div class="col-md-6">
                                    <input type="number" class="form-control" name="rbc" id="rbc">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-md-6"><span class="fw-bold">Differential Count:</span></div>
                                <div class="col-md-6">
                                    <input type="number" class="form-control" name="deffCount" id="deffCount">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-md-6"><span class="fw-bold">Total Protein:</span></div>
                                <div class="col-md-6">
                                    <input type="number" class="form-control" name="totalProtein" id="totalProtein">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-md-6"><span class="fw-bold">Mononuclear:</span></div>
                                <div class="col-md-6">
                                    <input type="number" class="form-control" name="mononuclear" id="mononuclear">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-md-6"><span class="fw-bold">Total Cell Count:</span></div>
                                <div class="col-md-6">
                                    <input type="number" class="form-control" name="totalCells" id="totalCells">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-md-6"><span class="fw-bold">Polymonuclear:</span></div>
                                <div class="col-md-6">
                                    <input type="number" class="form-control" name="polymonuclear" id="polymonuclear">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-md-6"><span class="fw-bold">Glucose:</span></div>
                                <div class="col-md-6">
                                    <input type="number" class="form-control" name="glucose" id="glucose">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-md-6"><span class="fw-bold">Pandy's Test:</span></div>
                                <div class="col-md-6">
                                    <input type="number" class="form-control" name="pandyTest" id="pandyTest">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-md-6"><span class="fw-bold">Leucocytes:</span></div>
                                <div class="col-md-6">
                                    <input type="number" class="form-control" name="leucocytes" id="leucocytes">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="border mb-3 p-2">
                            <h5 class="text-center fw-bold my-auto">STAINS</h5>
                        </div>
                        <div class="col-12">
                            <div class="row mb-3">
                                <div class="col-md-4"><span class="fw-bold">Indian Ink:</span></div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="indianInk" id="indianInk">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row mb-3">
                                <div class="col-md-4"><span class="fw-bold">Gram Stain:</span></div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="macroGramStain" id="macroGramStain">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row mb-3">
                                <div class="col-md-4"><span class="fw-bold">Zn Stain:</span></div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="znStain" id="znStain">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row mb-3">
                                <div class="col-md-4"><span class="fw-bold">IMMY TEST(Cryptococcus):</span></div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="immyTest" id="immyTest">
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
                    <div class="row">
                        <div class="col-md-3"><span class="fw-bold">Wet Preparation:</span></div>
                        <div class="col-md-6 mb-3">
                            <div class="row">
                                <select class="form-control" name="wet_prep" id="wet_prep">
                                    <option value="">~~~~~Select Wet Preparation~~~~~</option>
                                    <option value="RBC">RBS</option>
                                    <option value="WBC">WBC</option>
                                    <option value="others">Others</option>
                                </select>
                            </div>
                            <div class="row other_wets init-hide">
                                <label for="wet_specification">Specify Wet Preparation</label>
                                <input type="text" class="form-control" name="wet_specification" id="wet_specification"
                                    placeholder="specify">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="border mb-3 p-2">
                            <h5 class="text-center fw-bold my-auto">STAINS</h5>
                        </div>
                        <div class="col-12">
                            <div class="row mb-3">
                                <div class="col-md-4"><span class="fw-bold">Gram Stain:</span></div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="microGramStain" id="microGramStain">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row mb-3">
                                <div class="col-md-4"><span class="fw-bold">Zn Stain:</span></div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="microZnStain" id="microZnStain">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row mb-3">
                                <div class="col-md-4"><span class="fw-bold">Giemsa:</span></div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="giemsa" id="giemsa">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row mb-3">
                                <div class="col-md-4"><span class="fw-bold">Others(Specify):</span>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="otherMicroStain" id="otherMicroStain">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="border bg-success mb-3 p-2">
                            <h5 class="text-center fw-bold my-auto text-light">SKIN SCRAPING</h5>
                        </div>
                        <div class="mb-3">
                            <label for="kohPrep" class="form-label">Microscopic Examination(KOH prep.):</label>
                            <textarea class="form-control" name="kohPrep" id="kohPrep" rows="5"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 border">
                    <div class="mb-3">
                        <label for="cultureResults" class="form-label">CULTURE RESULTS(Isolates):</label>
                        <textarea class="form-control" name="cultureResults" id="cultureResults" rows="5"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-10">
                            <div class="row">
                                <div class="col border">
                                    <h5 class="fw-bold">Sensitive to</h5>
                                </div>
                                <div class="col border">
                                    <h5 class="fw-bold">Intermediate / Partially Sensitive to</h5>
                                </div>
                                <div class="col border">
                                    <h5 class="fw-bold">Resistant to</h5>
                                </div>
                                <div class="col-1 border py-2">

                                </div>
                            </div>
                            <div class="reaction_check">
                                <div class="row">
                                    <div class="col border py-2">
                                        <input type="text" class="form-control" name="sensitive[]" id="sensitive">
                                    </div>
                                    <div class="col border py-2">
                                        <input type="text" class="form-control" name="intermidiate[]" id="intermidiate">
                                    </div>
                                    <div class="col border py-2">
                                        <input type="text" class="form-control" name="resistant[]" id="resistant">
                                    </div>
                                    <div class="col-1 border py-2">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-outline-success btn-sm px-4 py-0 add_reaction">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-1" id="save-btn">
                <button type="submit" class="btn btn-outline-success btn-sm p-5 py-0">Save</button>
            </div>
            <div class="col-1" id="update-btn">
                <button type="submit" class="btn btn-outline-success btn-sm p-5 py-0">Update</button>
            </div>
            <div class="col-1" id="validate-btn">
                <button type="button" class="btn btn-outline-primary btn-sm p-5 py-0">Validate</button>
            </div>
            <div class="col-1 mx-3" id="send-btn">
                <button type="button" class="btn btn-outline-primary btn-sm p-5 py-0">Send To Doctor</button>
            </div>
            <div class="col-1 mx-5" id="preview-btn">
                <button type="button" class="btn btn-outline-primary btn-sm p-5 py-0 preview_results">Preview</button>
            </div>
        </div>
    </form>
</div>
<?php
include "../helpers/footer.php";
?>
<script>
// $(document).ready(function() {
var hasToggledOtherWet = false;
var hasToggledBloodCheck = false;
$("#specimenType").select2();
$("#anatomical").select2();
$("#wet_prep").select2();
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

            $("#specimenType").prepend("<option value='" + results.sample +
                "' selected>" + results.Specimen_Name + "</option>");
            $("#specimenType").val(results.sample).change();


            $("#anatomical").prepend("<option value='" + results.anatomical +
                "' selected>" + results.anatomical_name + "</option>");
            $("#anatomical").val(results.anatomical).change();


            $("#wet_prep").prepend("<option value='" + results.wet_preparation +
                "' selected>" + results.wet_preparation + "</option>");
            $("#wet_prep").val(results.wet_preparation).change();

            if (results.wet_preparation == "others") {
                $("#other_wets").val(results.wet_specification);
                $(".other_wets").toggle("init-hide");
                hasToggledOtherWet = true;
            }
            if (results.blood_check == "others") {
                $("#other_blood").attr("checked", true);
                $("#blood_specification").val(results.blood_specification);
                $(".other_bloods").toggle("init-hide");
                hasToggledBloodCheck = true;
            } else {
                if (results.blood_check == "Turbidity") {
                    $("#turbidity").attr("checked", true);
                } else {
                    $("#blood_presence").attr("checked", true);
                }
            }
            $("#colour").val(results.colour);
            $("#Culture_ID").val(results.Culture_ID);
            $("#wbc").val(results.wbc);
            $("#rbc").val(results.rbc);
            $("#deffCount").val(results.deffCount);
            $("#totalProtein").val(results.totalProtein);
            $("#mononuclear").val(results.mononuclear);
            $("#totalCells").val(results.totalCells);
            $("#polymonuclear").val(results.polymonuclear);
            $("#glucose").val(results.glucose);
            $("#pandyTest").val(results.pandyTest);
            $("#leucocytes").val(results.leucocytes);
            $("#indianInk").val(results.indianInk);
            $("#macroGramStain").val(results.macroGramStain);
            $("#znStain").val(results.znStain);
            $("#immyTest").val(results.immyTest);
            $("#microGramStain").val(results.microGramStain);
            $("#microZnStain").val(results.microZnStain);
            $("#giemsa").val(results.giemsa);
            $("#otherMicroStain").val(results.otherMicroStain);
            $("#kohPrep").val(results.kohPrep);
            $("#cultureResults").val(results.cultureResults);
            $("#sensitive").val(sensitive[0]);
            $("#resistant").val(resistant[0]);
            $("#intermidiate").val(intermidiate[0]);

            if (sensitive.length > 0) {
                for (let i = 1; i < sensitive.length; i++) {
                    var content = "<div class = 'row'>" +
                        "<div class = 'col border py-2' >" +
                        "<input type = 'text'class = 'form-control'name = 'sensitive[]'id = 'sensitive'value='" +
                        sensitive[i] + "' >" +
                        "</div> <div class = 'col border py-2' >" +
                        "<input type = 'text'class = 'form-control'name = 'intermidiate[]' id='intermidiate'value='" +
                        intermidiate[i] + "' >" +
                        "</div> <div class = 'col border py-2' >" +
                        "<input type = 'text'class = 'form-control'name = 'resistant[]'id = 'resistant'value='" +
                        resistant[i] + "'  ></div>" +
                        "<div class=' border col-1'><button type = 'button'class = 'btn btn-outline-danger btn-sm py-0 remove_reaction' >" +
                        "<i class = 'fas fa-times'> </i>" + " </button></div>" +
                        "</div>";
                    $(".reaction_check").append(content);
                }
            }
            if (viewOnly) {
                $("#update-btn").addClass("init-hide");
                $("#preview-btn").addClass("init-hide");
                $("#validate-btn").addClass("init-hide");
                $("#save-btn").addClass("init-hide");
                $("#send-btn").addClass("init-hide");
            } else {
                $("#save-btn").addClass("init-hide");
            }
            $("#spinner-loaders").hide();
            $("#cultureForm").removeClass("init-hide");
        } else {
            if (viewOnly) {
                $("#update-btn").addClass("init-hide");
                $("#preview-btn").addClass("init-hide");
                $("#validate-btn").addClass("init-hide");
                $("#send-btn").addClass("init-hide");
            } else {
                $("#update-btn").addClass("init-hide");
                $("#preview-btn").addClass("init-hide");
                $("#validate-btn").addClass("init-hide");
                $("#send-btn").addClass("init-hide");
            }
            $("#spinner-loaders").hide();
            $("#cultureForm").removeClass("init-hide");
        }
    }
});


$("#validate-btn").click(function(e) {
    e.preventDefault();
    var Culture_ID = $("#Culture_ID").val();
    $.ajax({
        type: "GET",
        url: "./laboratory/services/manage_culture_results.php",
        data: {
            action: "validate",
            Culture_ID: Culture_ID,
        },
        success: function(response) {
            response = JSON.parse(response)
            if (response.status == "success") {
                Swal.fire({
                    icon: 'success',
                    title: "<span class='fw-bold p-5'>" + response.data +
                        "</span>",
                    showDenyButton: false,
                    showCancelButton: false,
                    confirmButtonText: "<span class='p-5'>OK</span>",
                }).then((result) => {})
            } else {
                alert("Failed to Send!!!")
            }
        }
    });

});

$("#send-btn").click(function(e) {
    e.preventDefault();
    var Culture_ID = $("#Culture_ID").val();
    $.ajax({
        type: "GET",
        url: "./laboratory/services/manage_culture_results.php",
        data: {
            action: "send",
            Culture_ID: Culture_ID,
        },
        success: function(response) {
            response = JSON.parse(response)
            if (response.status == "success") {
                Swal.fire({
                    icon: 'success',
                    title: "<span class='fw-bold p-5'>" + response.data +
                        "</span>",
                    showDenyButton: false,
                    showCancelButton: false,
                    confirmButtonText: "<span class='p-5'>OK</span>",
                }).then((result) => {})
            } else {
                alert("Failed to Send!!!")
            }
        }
    });

});

$(".blood_check").change(function(e) {
    e.preventDefault();
    var blood_check_val = $(this).val()
    if (blood_check_val == "others") {
        if (!hasToggledBloodCheck) {
            $(".other_bloods").toggle("init-hide");
            hasToggledBloodCheck = true;
        }
    } else {
        if (hasToggledBloodCheck) {
            $("#blood_specification").val("");
            $(".other_bloods").toggle("init-hide");
            hasToggledBloodCheck = false;
        }
    }

});
$("#wet_prep").change(function(e) {
    e.preventDefault();
    var wet_val = $(this).val()
    if (wet_val == "others") {
        if (!hasToggledOtherWet) {
            $(".other_wets").toggle("init-hide");
            hasToggledOtherWet = true;
        }
    } else {
        if (hasToggledOtherWet) {
            $(".other_wets").toggle("init-hide");
            hasToggledOtherWet = false;
        }
    }
});
$(".add_reaction").click(function(e) {
    e.preventDefault();
    var content = "<div class = 'row'>" +
        "<div class = 'col border py-2' >" +
        "<input type = 'text'class = 'form-control'name = 'sensitive[]'id = 'sensitive' >" +
        "</div> <div class = 'col border py-2' >" +
        "<input type = 'text'class = 'form-control'name = 'intermidiate[]' id='intermidiate'>" +
        "</div> <div class = 'col border py-2' >" +
        "<input type = 'text'class = 'form-control'name = 'resistant[]'id = 'resistant' ></div>" +
        "<div class=' border col-1'><button type = 'button'class = 'btn btn-outline-danger btn-sm py-0 remove_reaction' >" +
        "<i class = 'fas fa-times'> </i>" + " </button></div>" +
        "</div>";
    $(".reaction_check").append(content);
});
$(".reaction_check").on("click", ".remove_reaction", function() {
    $(this).parent("div").parent("div").remove();
});

$("#cultureForm").submit(function(e) {
    e.preventDefault();
    var data = $(this).serialize()
    var Culture_ID = $("#Culture_ID").val();
    var action = "save"
    if (Culture_ID != '') {
        action = "update"
    }
    $.ajax({
        type: "POST",
        url: "./laboratory/services/manage_culture_results.php?action=" + action,
        data: data,
        success: function(response) {
            response = JSON.parse(response)
            if (response.status == "success") {
                Swal.fire({
                    icon: 'success',
                    title: "<span class='fw-bold p-5'>" + response.data +
                        "</span>",
                    showDenyButton: false,
                    showCancelButton: true,
                    confirmButtonText: "<span class='p-5'>OK</span>",
                    cancelButtonText: "<span class='text-light'>Preview Results</span>",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $("#cultureResult").dialog('close');
                    } else if (result.isCancelled) {
                        window.open(
                            "./laboratory/views/reports/macro_micro_reports.php?test=" +
                            test + "&paymentID=" +
                            paymentID, "_blank")
                    }
                })
            } else {
                alert("Failed to Save!!!")
            }
        }
    });
});
$(".preview_results").click(function(e) {
    e.preventDefault();
    window.open("./laboratory/views/reports/macro_micro_reports.php?test=" + test + "&paymentID=" +
        paymentID, "_blank")
});
// });
</script>