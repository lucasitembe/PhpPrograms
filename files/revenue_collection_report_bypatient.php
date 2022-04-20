<?php
include("./includes/header.php");
@session_start();
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if(isset($_SESSION['from']) &&  $_SESSION['from']=="ebill"){
    unset($_SESSION['from']);
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['General_Ledger'])) {
        if ($_SESSION['userinfo']['General_Ledger'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_GET['Section']) && $_GET['Section'] == 'managementworkspage') {

    $_SESSION['Section_managementworkspage'] = true;
}


$Today_Date = mysqli_query($conn,"select now() as today");
while($row = mysqli_fetch_array($Today_Date)){
    $Today = $row['today'];
}
$today_start_date=mysqli_query($conn,"select cast(current_date() as datetime)");
while($start_dt_row=mysqli_fetch_assoc($today_start_date)){
    $today_start=$start_dt_row['cast(current_date() as datetime)'];
}
?>
<a href="#" onclick="goBack()"class="art-button-green">BACK</a>    
    <script>
        function goBack(){
            window.history.back();
        }
    </script>
<style>
    #thead{
        text-align: center;
		font-size:10px;
		background-color:#ccc;
    }
    #theadtotal {
        text-align: center;
		font-size:10px;
		background-color:#bcd;
    }
</style>
<br><br>
<center>
<fieldset >
    <table class="table">
        <td width='100%'>
            <input type="text" style="width:10%" placeholder="Patient Number" onkeyup="search_by_patientno()" id='Registration_ID'>
            <input class="input" style="width:10%; display:inline; "  type="text" value="<?= $today_start ?>" class="form-control" id="start_date" autocomplete="off">
            <input class="input"style="width:10%; display:inline; "  type="text" value="<?= $Today ?>" class="form-control" id="end_date" autocomplete="off">
                <b>Sponsor</b>
                <select name="Sponsor_ID" id="Sponsor_ID" style="width:10%; display:inline; " onchange="filter_revenuebycollection_report()" >
                    <option selected="selected" value="All" >All</option>
                    <?php
                        $select = mysqli_query($conn,"select Guarantor_Name, Sponsor_ID from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
                        $num = mysqli_num_rows($select);
                        if($num > 0){
                            while ($data = mysqli_fetch_array($select)) {
                    ?>
                                <option value="<?php echo $data['Sponsor_ID']; ?>"><?php echo $data['Guarantor_Name']; ?></option>
                    <?php
                            }
                        }
                    ?>
                </select>  
                <b>Bill type</b>
                <select name="" id="Billtype" style="width:10%; display:inline;"  onchange="filter_revenuebycollection_report()">
                    <option value="All">All</option>
                    <option value="Inpatient">Inpatient</option>
                    <option value="Outpatient">Outpatient</option>
                </select>
                <input type="button" value="FILTER" style="width:8%; display:inline; "  class='art-button-green' onclick="filter_revenuebycollection_report()">
                <input type="button" value="IN PDF" style="width:8%; display:inline; "  class='art-button-green' onclick="filter_revenuebycollection_report_pdf()">
                <input type="button" value="IN EXCEL" style="width:8%; display:inline; "  class='art-button-green' onclick="filter_revenuebycollection_report_excel()">
         </td>
    </table>
</fieldset> 
</center>
<fieldset style='height:70vh;'>
    <legend align='center'>REVENUE COLLECTION PER PATIENT.</legend>    
    <div style="width:100%; height:500px;overflow-x: hidden;overflow-y: auto;margin: 2px 2px 20px 2px;" id='Revenuetbody'></div>
           
</fieldset>

<script src="css/jquery.datetimepicker.js"></script>
<script>

// $("#select").select2()
    $('#start_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en', //startDate:    'now'
    });
    $('#start_date').datetimepicker({value: '', step: 01});
    $('#end_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en', //startDate:'now'
    });
    $('#end_date').datetimepicker({value: '', step: 01});
</script>
<script>
    $(document).ready(function(){
        filter_revenuebycollection_report();
    })
    function filter_revenuebycollection_report(){
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        var Sponsor_ID= $("#Sponsor_ID").val();
        var Billtype =$("#Billtype").val();
        $('#Revenuetbody').html('<div align="center" style="display:block;" id="progressStatus"><img src="images/ajax-loader-focus.gif" width="" style="border-color:white "></div>');
        $.ajax({
            method:"POST",
            url:"Ajax_revenuecollection_per_patient.php",
            data:{start_date:start_date,end_date:end_date,Sponsor_ID:Sponsor_ID,Billtype:Billtype, Revenuecollectionfilter:''},
            success:function(data){
                $("#Revenuetbody").html(data);
            }
        })
    }
    function search_by_patientno(){
        var Registration_ID = $("#Registration_ID").val();
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        var Sponsor_ID= $("#Sponsor_ID").val();
        var Billtype =$("#Billtype").val();
        $('#Revenuetbody').html('<div align="center" style="display:block;" id="progressStatus"><img src="images/ajax-loader-focus.gif" width="" style="border-color:white "></div>');
        $.ajax({
            method:"POST",
            url:"Ajax_revenuecollection_per_patient.php",
            data:{start_date:start_date,end_date:end_date,Sponsor_ID:Sponsor_ID,Billtype:Billtype,Registration_ID:Registration_ID, Revenuecollectionfilter:''},
            success:function(data){
                $("#Revenuetbody").html(data);
            }
        })
    }

    function filter_revenuebycollection_report_pdf(){
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        var Sponsor_ID= $("#Sponsor_ID").val();
        var Registration_ID = $("#Registration_ID").val();
        var Billtype =$("#Billtype").val();
        window.open('revenuecollection_per_patient_pdf.php?start_date='+start_date+'&end_date='+end_date+'&Sponsor_ID='+Sponsor_ID+'&Registration_ID='+Registration_ID+'&Discount=discountpage','_blank');
     
    }

    function filter_revenuebycollection_report_excel(){
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        var Sponsor_ID= $("#Sponsor_ID").val();
        var Registration_ID = $("#Registration_ID").val();
        var Billtype =$("#Billtype").val();
        window.open('revenuecollection_per_patient_excel.php?start_date='+start_date+'&end_date='+end_date+'&Sponsor_ID='+Sponsor_ID+'&Registration_ID='+Registration_ID+'&Discount=discountpage','_blank');
    }
</script>
<?php

include("./includes/footer.php");
?>