<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Dialysis_Works'])) {
        if ($_SESSION['userinfo']['Dialysis_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } else {
            @session_start();
            if (!isset($_SESSION['Dialysis_Supervisor'])) {
                header("Location: ./deptsupervisorauthentication.php?SessionCategory=Dialysis&InvalidSupervisorAuthentication=yes");
            }
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>

<a href='./dialysisworkspage.php?section=Dialysis&DialysisWorks=DialysisWorksThisPage' class='art-button-green'>BACK</a>


<center>

    <fieldset style="background-color:white">
        <legend align="center" style="background-color:#006400;color:white;padding:5px;"><b>DIALYSIS CONSULTED PATIENTS</b></legend> 
        <center>
            <table width='99%' style="border:none !important; border-color:transparent !important;background-color:white;">
                <tr>
                    <td style="text-align:center">
                        <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="start_date" placeholder="Start Date"/>
                        <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="end_date" placeholder="End Date"/>&nbsp;
                        <select name='Sponsor_ID' id='Sponsor_ID' onchange="filterPatient()" style='text-align: center;width:10%;display:inline;padding: 4px;'>
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
                        <input type='text' name='Search_Patient' style='text-align: center;width:18%;display:inline' id='Search_Patient' oninput="cleaother('phone');filterPatient();" placeholder='~~~~~~~Search Patient Name~~~~~~~'>
                        <input type='text' name='Search_Patient_No' style='text-align: center;width:15%;display:inline' id='Search_Patient_No' oninput="cleaother('name');filterPatient();" placeholder='~~~~Search Patient Number~~~~'>
                        <input type="button" value="Filter" class="art-button-green" onclick="filterPatient()">
                        <a href="dialysyslist_report_print.php" id='print_preview' class='art-button-green' target='_blank'>PREVIEW DETAILS</a>
                    </td>
                </tr>
            </table>
        </center>

        <center>
            <table width=100% border=1>
                <tr>
                    <td>
                        <div align="center" style="display: none" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>
                        <div id="Search_Iframe" style="height: 400px;overflow-y: auto;overflow-x: hidden">
                            <?php include 'dialysisStatistics_Iframe_report.php'; ?>
                        </div>
                    </td>
                </tr>
            </table>
        </center>
    </fieldset><br/>
    <script>
      function cleaother(target){
         if(target=='phone'){
             $('#Search_Patient_No').val('');
         } else if(target=='name'){
              $('#Search_Patient').val('');
         }
      }
    </script>
 <script type="text/javascript">
        $(document).ready(function () {
            $('#patients-list').DataTable({
                "bJQueryUI": true
            });

            $('#start_date,#end_date').datetimepicker({
                dayOfWeekStart: 1,
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,
                lang: 'en',
                //startDate:    'now'
            });
            $('#start_date,#end_date').datetimepicker({value: '', step: 1});
           
        });
    </script>
    <script>
        function filterPatient() {
            var Date_From = document.getElementById('start_date').value;
            var Date_To = document.getElementById('end_date').value;
            var Patient_Name = document.getElementById("Search_Patient").value;
            var Patient_Number = document.getElementById("Search_Patient_No").value;
            var Sponsor = document.getElementById("Sponsor_ID").value;

            var datastring = 'Patient_Name=' + Patient_Name + '&Date_From=' + Date_From + '&Date_To=' + Date_To + '&Sponsor=' + Sponsor + '&Patient_Number=' + Patient_Number;

            $('#print_preview').attr('href', 'dialysyslist_report_print.php?' + datastring);

            $.ajax({
                type: 'GET',
                url: 'dialysiscreditpatientlist_Iframe_report.php',
                data: datastring,
                cache: false,
                beforeSend: function (xhr) {
                    $("#progressStatus").show();
                },
                success: function (html) {
                    if (html != '') {
                        $("#Search_Iframe").html(html);

                        $.fn.dataTableExt.sErrMode = 'throw';
                        $('#patients-list').DataTable({
                            "bJQueryUI": true

                        });
                    }
                }, complete: function (jqXHR, textStatus) {
                    $("#progressStatus").hide();
                }, error: function (html) {
                    $("#progressStatus").hide();
                }
            });

        }
    </script>


    <?php
    include("./includes/footer.php");
    ?>

    <link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
    <link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
    <script src="media/js/jquery.js" type="text/javascript"></script>
    <script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
    <script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
    <script src="css/jquery-ui.js"></script>