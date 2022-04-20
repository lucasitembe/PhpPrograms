<?php

include("./includes/functions.php");
include("./includes/header.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
$DateGiven = date('Y-m-d');;

$query = mysqli_query($conn, "SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
$dataSponsor = '';
$dataSponsor .= '<option value="All">All Sponsors</option>';
while ($row = mysqli_fetch_array($query)) {
    $dataSponsor .= '<option value="' . $row['Sponsor_ID'] . '">' . $row['Guarantor_Name'] . '</option>';
} 

if(isset($_SESSION['userinfo'])){
  if($_SESSION['userinfo']['Mtuha_Reports'] == 'yes'){
  ?>
<a href='map_disease_with_mtuha_group.php?section=DHIS&MapDiseaseGroup=MapDiseaseGroupThisPage' class='art-button'>
    MAP DISEASE TO GROUP
</a>
<?php 
   } 
  }
?>
<a href='./governmentReports.php?DhisWork=DhisWorkThisPage' class='art-button'>
    BACK
</a>
<style>
.bg-grey {
    background-color: #e0e0e0;
}
</style>
<fieldset>
    <legend align=center>MTUHA BOOK</legend>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="col-md-2"></div>
                <div class="col-md-5">
                    <label for="">Month</label>
                    <input type="text" name="month-year" id="month-year" class="form-control">
                </div>
                <div class="col-md-5">
                    <label for="">Report Type</label><br>
                    <select name="report" id="report" class="form-control">
                        <option value="opd">OPD Report</option>
                        <option value="ipd">IPD Report</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2" style="padding-top: 2rem;">
                <a href="#" onclick="filterMtuhaData()" class="art-button">Filter</a>
            </div>
        </div>
    </div>
    <hr>
    <div class="container-fluid">

        <table class="table table-bordered">
            <theader>
                <tr>
                    <th rowspan="2">Na.</th>
                    <th rowspan="2" colspan="2">Maelezo</th>
                    <th colspan="3">Umri chini ya mwezi 1</th>
                    <th colspan="3">Umri mwezi 1 hadi umri chini ya mwaka 1</th>
                    <th colspan="3">Umri mwaka 1 hadi umri chini ya miaka 5</th>
                    <th colspan="3">Umri Miaka 5 hadi miaka 60</th>
                    <th colspan="3">Umri Wa Miaka 60 na Kendelea</th>
                    <th colspan="3">Jumla Kuu</th>
                </tr>
                <tr>
                    <th>ME</th>
                    <th>KE</th>
                    <th>JUMLA</th>
                    <th>ME</th>
                    <th>KE</th>
                    <th>JUMLA</th>
                    <th>ME</th>
                    <th>KE</th>
                    <th>JUMLA</th>
                    <th>ME</th>
                    <th>KE</th>
                    <th>JUMLA</th>
                    <th>ME</th>
                    <th>KE</th>
                    <th>JUMLA</th>
                    <th>ME</th>
                    <th>KE</th>
                    <th>JUMLA</th>
                </tr>
            </theader>
            <?php include "./mtuha_book_report_holder.php"?>
            <tbody id="mtuha_body"></tbody>
        </table>
    </div>
</fieldset>
<?php
include("./includes/footer.php"); ?>
<script>
$("#mtuha_body").hide();
// $("#mtuha_body_loader").hide();
$("#month-year").datepicker({
    dateFormat: 'MM yy',
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
}).focus(function() {
    var thisCalendar = $(this);
    $('.ui-datepicker-calendar').detach();
    $('.ui-datepicker-close').click(function() {
        var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
        var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
        thisCalendar.datepicker('setDate', new Date(year, month, 1));
        // alert(month)
        // filterMtuhaData();
    });
});;
$("#report").select2();
$("#report").change(function(e) {
    e.preventDefault();
    filterMtuhaData();
});

function filterMtuhaData() {
    $("#mtuha_body_loader").show();
    var date = getSelectedDate();
    var report_type = $("#report").val();
    if (date == null) {
        alert("Please Select Date")
        // alert("Kuwa Serious...Nyie ndo mnasababishaga bugs kwenye system\nHalafu manalalamika")  
    } else if (report_type == '') {
        alert("Please Select Report Type")
    } else {
        data = {
            date: date,
            report_type: report_type
        }
        $.ajax({
            type: "GET",
            url: "./load_mtuha_book_data.php",
            data: data,
            success: function(response) {
                $("#mtuha_body_loader").hide();
                $("#mtuha_body").html(response);
                $("#mtuha_body").show();
            }
        });
    }

}

function getSelectedDate() {
    var monthValue = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
    var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
    var date = new Date(year, monthValue, 1);
    var day = date.getDay();
    var month = date.getMonth() + 1;
    year = date.getFullYear();
    if (monthValue == null) {
        return null;
    }
    return year + "-" + month + "-" + 1;
}
</script>