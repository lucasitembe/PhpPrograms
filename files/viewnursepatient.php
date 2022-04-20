<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Nurse_Station_Works'])) {
        if ($_SESSION['userinfo']['Nurse_Station_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>

<?php
Error_reporting(E_ERROR | E_PARSE);

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Nurse_Station_Works'] == 'yes') {
        ?>
        <a href='viewnursepatient.php' class='art-button-green'>
            VIEW CHECKED
        </a>
    <?php }
}
?>



<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Nurse_Station_Works'] == 'yes') {
        ?>
        <a href='searchnurseform.php' class='art-button-green'>
            PATIENTS LISTS
        </a>
    <?php }
}
?>

<?php
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $Age = $Today - $original_Date;
}
?>

<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>
<style>
    select{
        padding:5px;
    }

    .dates{
        color:#cccc00;
    }
</style>
<br/><br/>
<center>
    <fieldset>  
    <table width="100%">
        <tr>
            <td style="text-align:center">
                <input type="text" autocomplete="off" style='text-align: center;width:20%;display:inline' id="Date_From" placeholder="Start Date"/>
                <input type="text" autocomplete="off" style='text-align: center;width:20%;display:inline' id="Date_To" placeholder="End Date"/>&nbsp;
                <select name='Sponsor_ID' id='Sponsor_ID' onchange="filterPatient()" style='text-align: center;width:20%;display:inline'>
                    <option value="All">All Sponsors</option>
                    <?php
                    $qr = "SELECT * FROM tbl_sponsor";
                    $sponsor_results = mysqli_query($conn,$qr);
                    while ($sponsor_rows = mysqli_fetch_assoc($sponsor_results)) {
                        ?>
                        <option value='<?php echo $sponsor_rows['Sponsor_ID']; ?>'><?php echo $sponsor_rows['Guarantor_Name']; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <input type="button" value="Filter" class="art-button-green" onclick="filterPatient()"><br/>
                <input type='text' name='Search_Patient' style='text-align: center;width:30%;display:inline' id='Search_Patient' oninput="filterPatient()" placeholder='~~~~~~~~~~~~~~Search Patient Name~~~~~~~~~~~~~~~~~~~~~'>
                 <input type='text' name='Search_Patient' style='text-align: center;width:20%;display:inline' id='Search_Patient_Number' oninput="filterPatient()" placeholder='~~Search Patient Number~~'>
                  <input type='text' name='Search_Patient' style='text-align: center;width:20%;display:inline' id='Search_Patient_Old_Number' oninput="filterPatient()" placeholder='~~Search Patient Old Number~~'>
            </td>
        </tr>
    </table>
        </fieldset>  
</center>
<br/>
<fieldset>  
    <legend align=center><b id="dateRange">CHECKED LIST <span class='dates'><?php echo date('Y-m-d')?></span> </b></legend>
    <center>
        <table width='100%' border='1'>
            <tr>
                <td >
                    <div id="Search_Iframe" style="height: 400px;overflow-y: auto;overflow-x: hidden">
                        <?php include 'viewnursepatient2.php'; ?>
                    </div>
                </td>
            </tr>
        </table>
    </center>
</fieldset><br/>
<script>
    function filterPatient() {
        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;
        var Sponsor = document.getElementById('Sponsor_ID').value;
        var Patient_Name = document.getElementById('Search_Patient').value;
        var Patient_Number = document.getElementById('Search_Patient_Number').value;
        var Patient_Old_Number = document.getElementById('Search_Patient_Old_Number').value;
        var range;
         if(Date_From !='' && Date_To !=''){
              range="FROM <span class='dates'>"+Date_From+"</span> TO <span class='dates'>"+Date_To+"</span>";
        }

        document.getElementById('dateRange').innerHTML = "PATIENT LIST "+range;

        document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

        $.ajax({
            type: "GET",
            url: "viewnursepatient2.php",
            data: 'Date_From=' + Date_From + '&Date_To=' + Date_To + '&Patient_Name=' + Patient_Name+'&Sponsor=' + Sponsor+ '&Patient_Number=' + Patient_Number + '&Patient_Old_Number=' +Patient_Old_Number,
            success: function (html) {
                if (html != '') {
                    $('#Search_Iframe').html(html);
                    $.fn.dataTableExt.sErrMode = 'throw';
                    $('#PatientsList').DataTable({
                        'bJQueryUI': true
                    });
                }
            }
        });
    }
</script>


<script type="text/javascript">
    $(document).ready(function () {
        $('#PatientsList').DataTable({
            "bJQueryUI": true

        });

        $('#Date_From').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:    'now'
        });
        $('#Date_From').datetimepicker({value: '', step: 30});
        $('#Date_To').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:'now'
        });
        $('#Date_To').datetimepicker({value: '', step: 30});
    });
</script>



<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<?php
    include("./includes/footer.php");
?>