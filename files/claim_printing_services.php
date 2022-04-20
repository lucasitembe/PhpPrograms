<?php
include("./includes/connection.php");
include("./includes/header.php");
$controlforminput = '';

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo']['Quality_Assurance'])) {
    if ($_SESSION['userinfo']['Quality_Assurance'] != 'yes') {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>


<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Quality_Assurance'] == 'yes') {
        ?>
<a href="claim_printing_services.php?NHIF_Received_Claims=NHIF_Received_Claims&amp;QualityAssuranceWorks=QualityAssuranceWorksThisPage" class="art-button-green">
            CLAIMS PRINTING SERVICES
        </a>
        <a href='billslist.php?Status=PreviousBills&Requisition=RequisitionThisPage' class='art-button-green'>
            BACK
        </a>
        <?php
    }
}
?>
<style type="text/css">
    .statistic-table td{
        text-align: center;
    }
</style>
<fieldset>
    <legend>FOLIO PRINTING SERVICES</legend>
<center>
        <?php
    function getMonth($month){
        $newMonth = '';
        switch ($month) {
            case '01':{$newMonth = 'January';}break;
            case '02':{$newMonth = 'February';}break;
            case '03':{$newMonth = 'March';}break;
            case '04':{$newMonth = 'April';}break;
            case '05':{$newMonth = 'May';}break;
            case '06':{$newMonth = 'June';}break;
            case '07':{$newMonth = 'July';}break;
            case '08':{$newMonth = 'August';}break;
            case '09':{$newMonth = 'September';}break;
            case '10':{$newMonth = 'October';}break;
            case '11':{$newMonth = 'November';}break;
            case '12':{$newMonth = 'December';}break;

        }
        return $newMonth;
    }
    function getMaxMinFolio($value){
        global $conn;
        $month = date("m");
        $year = date("Y");
        $query = mysqli_fetch_assoc(mysqli_query($conn,"SELECT MIN(Folio_No) MinFolio ,MAX(Folio_No) MaxFolio FROM tbl_claim_folio WHERE claim_month = '$month' AND claim_year = '$year'"));

        return $query[$value];
    }
     ?>


    <table class="table table-borderless">
        <tr>
            <td>
                <select id="year" class="form-control">
                    <option value="">Select Year</option>
                    <?php
                        $query = mysqli_query($conn,"SELECT DISTINCT YEAR(Visit_Date) AS Visit_Date FROM tbl_check_in ORDER BY Visit_Date DESC ");

                        while($year = mysqli_fetch_assoc($query)) {
                            $selected = '';
                            // if($year['Visit_Date'] == date('Y')){
                            //     $selected = 'selected';
                            // }
                            echo "<option value='".$year['Visit_Date']."' ".$selected.">".$year['Visit_Date']."</option>";
                        }
                     ?>
                </select>
            </td><td>
                <select id="month" class="form-control">
                    <option value="">Select Month</option>
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            </td>
            <td>
                First Folio
            </td>
            <td>
                <input type="number" name="" id="FirstFolio" class="form-control" placeholder="Enter First Folio No">
            </td>
            <td>
                Last Folio
            </td>
            <td>
                <input type="number" name="" id="LaststFolio" class="form-control"  placeholder="Enter Last Folio No">
            </td>
            <td>
                <input type="button" name="" value="Download PDF File" class="btn btn-primary" onclick="PrintFiles();">
            </td><td>
                <input type="button" name="" value="View Statistics" class="btn btn-primary" onclick="viewStatistics();">
            </td>
        </tr>
        <tr>
            <td colspan="6"></td>
        </tr>
    </table>


            <div class="" style="min-height: 200px; width: 100%; line-height: 100px; font-size: 20px; background-color: #fff;" id="folio_statistics">
                <b>FOLIO STATISTICS</b>
                <table width="50%" style="font-size: 18px;text-align: center;" class="statistic-table">
                    <tr>
                        <td style="text-align: center;"> <b>Year: &emsp;&emsp;<?=date('Y');?></b> </td>
                    </tr>
                    <tr>
                        <td> <b>Month: &emsp;&emsp;<?=date('m')."&nbsp;(".getMonth(date('m')).")";?></b> </td>
                    </tr>
                    <tr>
                        <td> <b>First Folio No: &emsp;&emsp;<?=getMaxMinFolio('MinFolio');?></b> </td>
                    </tr>
                    <tr>
                        <td> <b>Last Folio No: &emsp;&emsp;<?=getMaxMinFolio('MaxFolio');?></b> </td>
                    </tr>
                </table>
            </div>

</center>
</fieldset>
<script type="text/javascript">
    function PrintFiles(){
        var year = document.getElementById('year').value;
        var month = document.getElementById('month').value;
        var FirstFolio = document.getElementById('FirstFolio').value;
        var LastFolio = document.getElementById('LaststFolio').value;
        if(year == ''){
            alert("SELECT YEAR");
            return false;
        }
        if(month == ''){
            alert("SELECT MONTH");
            return false;
        }

        if(FirstFolio == '' || LastFolio == ''){
            alert("WRITE THE FIRST AND LAST FOLIO");
            return false;
        }
        var diff = (parseInt(LastFolio)) - (parseInt(FirstFolio));
        if(diff > 500){
            alert("THE SYSTEM CAN ONLY PRINT 500 FILES AT ONCE");
            return false;
        }
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if(xhttp.readyState == 4 && xhttp.status == 200){

                DownLoadFile();
            }
        }

        var url = 'generate_printing_files.php';

        var data = 'year='+year+'&month='+month+'&FirstFolio='+FirstFolio+'&LastFolio='+LastFolio;
        xhttp.open("POST",url,true);
        xhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
        xhttp.send(data);
    }

    function DownLoadFile(){
        window.open('download_merged_files.php');
    }


    function viewStatistics(){
        var folio_statistics = document.getElementById('folio_statistics');
        var year = document.getElementById('year').value;
        var month = document.getElementById('month').value;
        if(year == ''){
            alert("SELECT YEAR");
            return false;
        }
        if(month == ''){
            alert("SELECT MONTH");
            return false;
        }

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if(xhttp.readyState == 4 && xhttp.status == 200){
                folio_statistics.innerHTML = xhttp.responseText;
            }
        }

        var url = 'view_folio_statistics.php';

        var data = 'month='+month+'&year='+year;
        xhttp.open("POST",url,true);
        xhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
        xhttp.send(data);
    }
</script>

<?php
include("./includes/footer.php");
?>
