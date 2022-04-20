<?php
include("./includes/connection.php");
include_once("./functions/items.php");
include("./includes/new_header.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])) {
        if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') {
        ?>

<?php
    }
}
?>
<div class="p-3">
    <div class="container-fluid h-75">
        <div class="row mt-5 bg-light border rounded">
            <div class="position-relative">
                <div class="position-absolute top-0 start-50 translate-middle">
                    <div class="col-12 bg-success p-1">
                        <span class="fw-bold fs-6 text-light">KITCHEN WORKS</span>
                    </div>
                </div>
                <div class="row mt-5">
                    <span class="h5 fw-bold text-center">
                        LUGALO GENERAL MILITARY HOSPITAL DAILY RATION STATE
                    </span>
                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="col-6"><span class="fw-bold">Curent Session:</span></div>
                            <div class="col-2"><span class="text-success fw-bold text-uppercase"
                                    id="dietSession"></span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="col-5"><span class="fw-bold">Filter Session:</span></div>
                            <div class="col-5">
                                <select class="col-12" name="session" id="foodSession">
                                    <option value="breakfast">Breakfast</option>
                                    <option value="lunch">Lunch</option>
                                    <option value="dinner">Dinner</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="col-2 small">WARD</th>
                                        <th class="col-1 small">SOLD</th>
                                        <th class="col-1 small">GUARDIAN</th>
                                        <th class="col-1 small">DEP</th>
                                        <th class="col-1 small">HIGH PROTEIN DIET</th>
                                        <th class="col-1 small">LIGHT DIET</th>
                                        <th class="col-1 small">SALT FREE</th>
                                        <th class="col-1 small">FAT FREE</th>
                                        <th class="col-1 small">DIABETIC DIET</th>
                                        <th class="col-1 small">SPECIAL DIET DOCTOR ADVICE</th>
                                        <th class="col-1 small">NORMAL DIET</th>
                                        <th class="col-1 small">MILK</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="kitchenTable">
                                    <tr>
                                        <td colspan="12">
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
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-top h-100" tabindex="-1" aria-labelledby="recordViewLabel" id="recordView">
    <div class="offcanvas-header bg-success">
        <h5 class="text-light">
            <b>Ward Name: </b>
            <span id="recordViewLabel"></span>
            <h5>
                <button type="button" class="btn-close text-reset bg-white" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped rounded-bottom">
                <thead>
                    <tr>
                        <th class="col-1 small">No.</th>
                        <th class="col-1 small">ARMY NO.</th>
                        <th class="col-1 small">RANK</th>
                        <th class="col-2 small">PATIENT NAME</th>
                        <th class="col-1 small">UNIT</th>
                        <th class="col-1 small">AGE</th>
                        <th class="col-1 small">DIET</th>
                        <th class="col-1 small">MILK</th>
                        <th class="col-1 small">SIGNATURE</th>
                    </tr>
                </thead>
                <tbody id="wardDietTable">
                    <tr>
                        <td colspan="8">
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
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>
<?php
include("./includes/new_footer.php");
?>

<script>
var dietSession = getSessionNameFromTime();
$("#dietSession").html(dietSession);
$("#foodSession").select2();
$(document).ready(function() {
    getPatientDieData(dietSession)
    $("#foodSession").change(function(e) {
        e.preventDefault();
        dietSession = $(this).val();
        getPatientDieData(dietSession)
    });
    $("#kitchenTable").on("click", ".view-record", function() {
        $("#recordView").offcanvas("show");
        var ward = $(this).data("id")
        var wardName = $(this).data("ward")
        $("#recordViewLabel").html(wardName);
        $.ajax({
            type: "GET",
            url: "./diet_manager.php",
            data: {
                action: "getWardData",
                ward: ward,
                dietSession: dietSession
            },
            success: function(response) {
                $("#wardDietTable").html(response);
            }
        });
    });
});

function getSessionNameFromTime() {
    var now = new Date().toLocaleTimeString("sw-tz");
    var currentHr = now.split(":")[0]

    if (currentHr >= 7 && currentHr < 11) {
        return "breakfast";
    } else if (currentHr >= 11 && currentHr < 18) {
        return "lunch";
    } else {
        return "dinner";
    }
}

function getPatientDieData(dietSession) {
    $.ajax({
        type: "GET",
        url: "./diet_manager.php",
        data: {
            action: "getSessionData",
            dietSession: dietSession,
        },
        success: function(response) {
            $("#kitchenTable").html(response);
        }
    });
}
</script>