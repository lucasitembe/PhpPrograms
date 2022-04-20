<?php
$admission_id=$_GET['admission_id'];
echo "<script>
    var admission_id=$admission_id;
</script>";
?>

<link rel="stylesheet" href="./lib/bootstrap/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="./lib/fontawesome/css/all.min.css">
<link rel="stylesheet" href="./lib/jquery-datetimepicker/build/jquery.datetimepicker.min.css">
<link rel="stylesheet" href="./lib/jquery-toast-plugin/dist/jquery.toast.min.css">
<link rel="stylesheet" href="./lib/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="./lib/sweetalert2/dist/sweetalert2.min.css">
<link rel="stylesheet" href="./lib/datatables/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="./lib/icheck-bootstrap/icheck-bootstrap.min.css">
<link rel="stylesheet" href="./laboratory/static/css/global_style.css">


<div class="container-fluid">
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
    <form id="dietSpecForm" class="init-hide">
        <div class="col-12">
            <input type="hidden" value="<?php echo $admission_id; ?>" id="admission_id" name="admission_id">
            <input type="hidden" id="specId" name="specId">
            <div class="row">
                <h6 class="fw-bold h5">
                    Sponsors
                </h6>
                <div class="col-12">
                    <div class="row mb-3">
                        <div class="col icheck-success d-inline">
                            <input type="radio" class="sponsor" name="sponsor" id="sold" value="Sold">
                            <label for="sold">
                                Sold
                            </label>
                        </div>
                        <div class="col icheck-success d-inline">
                            <input type="radio" class="sponsor" name="sponsor" id="guardian" value="Guardian">
                            <label for="guardian">
                                Guardian
                            </label>
                        </div>
                        <div class="col icheck-success d-inline">
                            <input type="radio" class="sponsor" name="sponsor" id="dep" value="DEP">
                            <label for="dep">
                                DEP
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <h6 class="fw-bold h5">
                    Diets
                </h6>
                <div class="col-12">
                    <div class="row mb-3">
                        <div class="col-4 icheck-success d-inline">
                            <input type="checkbox" id="highProtein" name="highProtein">
                            <label for="highProtein">
                                High Protein Diet
                            </label>
                        </div>
                        <div class="col-4 icheck-success d-inline">
                            <input type="checkbox" id="lightDiet" name="lightDiet">
                            <label for="lightDiet">
                                Light Diet
                            </label>
                        </div>
                        <div class="col-4 icheck-success d-inline">
                            <input type="checkbox" id="saltFree" name="saltFree">
                            <label for="saltFree">
                                Salt Free
                            </label>
                        </div>
                        <div class="col-4 icheck-success d-inline">
                            <input type="checkbox" id="fatFree" name="fatFree">
                            <label for="fatFree">
                                Fat Free
                            </label>
                        </div>
                        <div class="col-4 icheck-success d-inline">
                            <input type="checkbox" id="diabeticDiet" name="diabeticDiet">
                            <label for="diabeticDiet">
                                Diabetic Diet
                            </label>
                        </div>
                        <div class="col-4 icheck-success d-inline">
                            <input type="checkbox" id="doctorSpecial" name="doctorSpecial">
                            <label for="doctorSpecial">
                                Special Diet Doctor Advice
                            </label>
                        </div>
                        <div class="col-4 icheck-success d-inline">
                            <input type="checkbox" id="normalDiet" name="normalDiet">
                            <label for="normalDiet">
                                Normal Diet
                            </label>
                        </div>
                        <div class="col-4 icheck-success d-inline">
                            <input type="checkbox" id="milk" name="milk">
                            <label for="milk">
                                Milk
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <h6 class="fw-bold h5">
                    Meal Time
                </h6>
                <div class="col-12">
                    <div class="row mb-3">
                        <div class="col-4 icheck-success d-inline">
                            <input type="checkbox" id="breakFast" name="breakFast">
                            <label for="breakFast">
                                Break Fast
                            </label>
                        </div>
                        <div class="col-4 icheck-success d-inline">
                            <input type="checkbox" id="lunch" name="lunch">
                            <label for="lunch">
                                Lunch
                            </label>
                        </div>
                        <div class="col-4 icheck-success d-inline">
                            <input type="checkbox" id="dinner" name="dinner">
                            <label for="dinner">
                                Dinner
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-2" id="submit-btn">
                    <button type="submit" class="btn btn-outline-success btn-sm px-4">Submit</button>
                </div>
                <div class="col-2" id="update-btn">
                    <button type="submit" class="btn btn-outline-success btn-sm px-4">
                        Update
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>


<!-- <script src="./lib/bootstrap/dist/js/bootstrap.bundle.min.js"></script> -->
<script src="./lib/jquery-datetimepicker/build/jquery.datetimepicker.full.min.js"></script>
<script src="./lib/jquery-toast-plugin/dist/jquery.toast.min.js"></script>
<script src="./lib/select2/dist/js/select2.full.min.js"></script>
<script src="./lib/sweetalert2/dist/sweetalert2.all.min.js"></script>
<script src="./lib/datatables/js/dataTables.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    $.ajax({
        type: "GET",
        url: "./diet_specification_manager.php",
        data: {
            admission_id: admission_id,
            action: "getDietSpecs"
        },
        success: function(response) {
            response = JSON.parse(response)
            if (response.status == "has submitted") {
                $("#specId").val(response.data.id);
                if (response.data.sponsor == "Sold") {
                    $("#sold").attr("checked", true);
                } else if (response.data.sponsor == "Guardian") {
                    $("#guardian").attr("checked", true);
                } else {
                    $("#dep").attr("checked", true);
                }
                $("#highProtein").attr("checked", getValue(response.data.highProtein));
                $("#lightDiet").attr("checked", getValue(response.data.lightDiet));
                $("#saltFree").attr("checked", getValue(response.data.saltFree));
                $("#fatFree").attr("checked", getValue(response.data.fatFree));
                $("#diabeticDiet").attr("checked", getValue(response.data.diabeticDiet));
                $("#doctorSpecial").attr("checked", getValue(response.data.specialDiet));
                $("#normalDiet").attr("checked", getValue(response.data.normalDiet));
                $("#milk").attr("checked", getValue(response.data.milk));
                $("#breakFast").attr("checked", getValue(response.data.breakFast));
                $("#lunch").attr("checked", getValue(response.data.lunch));
                $("#dinner").attr("checked", getValue(response.data.dinner));
                $("#submit-btn").addClass("init-hide");
                $("#spinner-loaders").hide();
                $("#dietSpecForm").removeClass("init-hide");
            } else {
                $("#update-btn").addClass("init-hide");
                $("#spinner-loaders").hide();
                $("#dietSpecForm").removeClass("init-hide");
            }
        }
    });
    $("#dietSpecForm").submit(function(e) {
        e.preventDefault();
        var data = $(this).serialize();
        var specId = $("#specId").val();
        var action = "submit";
        if (specId != '') {
            action = "update";
        }
        $.ajax({
            type: "POST",
            url: "./diet_specification_manager.php?action=" + action,
            data: data,
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
                    }).then((result) => {
                        $("#diet_specs_dialog").dialog('close');
                    })
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: "<span class='fw-bold p-5'>" + response.data +
                            "</span>",
                        showDenyButton: false,
                        showCancelButton: false,
                        confirmButtonText: "<span class='p-5'>OK</span>",
                    })
                }
            }
        });
    });
});

function getValue(data) {
    if (data == 1) {
        return true;
    } else {
        return false;
    }
}
</script>