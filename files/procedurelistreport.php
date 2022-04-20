<?php
include("./includes/header.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Procedure_Works'])) {
        if ($_SESSION['userinfo']['Procedure_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } else {
            if (!isset($_SESSION['Procedure_Supervisor'])) {
                header("Location: ./deptsupervisorauthentication.php?SessionCategory=Procedure&InvalidSupervisorAuthentication=yes");
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
<a href="Procedure.php" class="art-button-green">BACK</a>
<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>
<br><br>
<br><br>
<fieldset>
    <legend align=center><b>PROCEDURE REPORTS</b></legend>
    <center>
    <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']) { ?>
                        <a href='General_Performance_Filtered_Control_Session.php?Location=Procedure'>
                            <button style='width: 60%; height: 50%'>
                                General Performance Report
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            General Performance Report
                        </button>

                    <?php } ?>
                </td>
            </tr> 
        <table width = 60%> 
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if (isset($_SESSION['userinfo']['Procedure_Works'])) {
                        ?>
                        <a href='Patients_Sent_To_Procedure.php?PatientsSentToProcedure=ThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Patients Sent To Procedure
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Patients Sent To Procedure
                        </button>
                    <?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if (isset($_SESSION['userinfo']['Procedure_Works'])) {
                        ?>
                        <a href='patient_done_procedure.php'>
                            <button style='width: 100%; height: 100%'>
                                Patient with procedure done Report
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Patient with procedure done Report
                        </button>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if (isset($_SESSION['userinfo']['Procedure_Works'])) {
                        ?>
                        <a href='number_of_test_taken_proc.php?TestNameCollection=ThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Number Of Procedure Done Report
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Number Of Procedure Done Report
                        </button>
                    <?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if (isset($_SESSION['userinfo']['Procedure_Works'])) {
                        ?>
                        <a href='patient_pendapplic_procedure.php'>
                            <button style='width: 100%; height: 100%'>
                                Patient with procedure active / pending / not applicable Report
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Patient with procedure active / pending / not applicable Report
                        </button>
                    <?php } ?>
                </td>
            </tr>

            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if (isset($_SESSION['userinfo']['Procedure_Works'])) {
                        ?>
                        <a href='procedure_revenue_collection.php?TestNameCollection=ThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Procedure Revenue Collection Report
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Procedure Revenue Collection Report
                        </button>
                    <?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;' colspan="2">
                    <?php if (isset($_SESSION['userinfo']['Radiology_Works'])) {
                        ?>
                        <a href='proc_performance_report.php'>
                            <button style='width: 100%; height: 100%'>
                                Procedure Performance Report
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Procedure Performance Report
                        </button>
                    <?php } ?>
                </td>
            </tr>


        </table>
    </center>
</fieldset>
<br/>
<?php
include("./includes/footer.php");
?>
<!--<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script src="css/jquery-ui.js"></script>-->
<script>
    $('#revueCollection').click(function () {
        //  e.preventDefault();
        $('#displaytestrevenue').dialog({
            modal: true,
            width: '97%',
            // hight:600,
            resizable: true,
            draggable: true,
            title: 'Test Revenue Collection Report (Select date range to show data)',
            close: function (event, ui) {
                $('#date_From').val('');
                $('#date_To').val('');
                $('#sponsor').val('All');
            }
        });

        $.ajax({
            type: 'POST',
            url: 'requests/TestrevenueCollection.php',
            data: 'action=testrevenue&paymentID=',
            cache: false,
            success: function (html) {
                //  alert(html);
                $('#showrevenue').html(html);
            }
        });

    });


    $('#submit').click(function () {
        //$('#ViewAllrevenuehere').slideToggle();
        var sponsorName = $('#sponsor').val();
        var date_From = $('#date_From').val();
        var date_To = $('#date_To').val();
        //	        if(isDate(date_From)){
        //                  alert('Valid Date');   
        //                    
        //                }else{
        //	            alert('Invalid Date');
        //                }
        //
        //exit();
        //        if (Date.parse(date_From) {
        //           alert('its date');
        //        } else {
        //          alert('its not date');
        //        }

        //exit();

        if ((date_From == '') || (date_To == '')) {
            alert('Date cannot be empty');
            exit();
        }

        $.ajax({
            type: 'POST',
            url: 'requests/TestrevenueCollection.php',
            data: 'action=testrevenuebyDate&date_From=' + date_From + '&date_To=' + date_To + '&sponsorName=' + sponsorName,
            cache: false,
            success: function (html) {
                //  alert(html);
                $('#showrevenue').html(html);
            }
        });
    });
</script>

<script>
    //    $('#displaytestrevenue').on("dialogclose", function() {
    //        alert('mmmmmmm');
    //      window.location.href = 'Laboratory_Reports.php'; 
    //    });
</script>

<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<!--<script src="css/jquery.js" type="text/javascript"></script>-->
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
