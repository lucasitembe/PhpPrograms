<?php
include("./includes/header.php");

if(isset($_GET['frompage']) && $_GET['frompage'] == "DIHSREPORT"){
    ?>
<a href="governmentReports.php?GovernmentReports=GovernmentReportsThisPage"  class="art-button-green">BACK</a>
<?php }else{ 
    if (!isset($_SESSION['userinfo'])) {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if (isset($_SESSION['userinfo'])) {
        if (isset($_SESSION['userinfo']['Radiology_Works'])) {
            if ($_SESSION['userinfo']['Radiology_Works'] != 'yes') {
                header("Location: ./index.php?InvalidPrivilege=yes");
            } else {
                if (!isset($_SESSION['Radiology_Supervisor'])) {
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
<a href="radiologyworkspage.php?RadiologyWorks=RadiologyWorksThisPage" class="art-button-green">BACK</a>
    <?php
}
?>
<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>
<br><br>
<br><br>
<fieldset>
    <legend align=center><b>RADIOLOGY REPORTS</b></legend>
    <center>
        <table width = 60%>  
       <tr>
            <td style='text-align: center; height: 40px; width: 33%;' colspan="2">
                        <?php if($_SESSION['userinfo']['Radiology_Works'] == 'yes'){ ?>
                            <a href='./Radiology_Management_Report.php'>
                                <button style='width: 100%; height: 100%'>
                                    Management Report
                                </button>
                            </a>
                        <?php }else{ ?>
                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Management Report
                            </button>
                        <?php } ?>
                    </td>
            </tr>
            <tr>
            <td style='text-align: center; height: 40px; width: 33%;' colspan="2">
			<?php if($_SESSION['userinfo']['Radiology_Works'] == 'yes'){ ?>
			<a href='General_Performance_Filtered_Control_Session.php?Location=Radiology'>
			    <button style='width: 100%; height: 100%'>
				General Performance Report
			    </button>
			</a>
			<?php }else{ ?>
			 
			    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
				General Performance Report
			    </button>
		      
			<?php } ?>
		    </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if (isset($_SESSION['userinfo']['Radiology_Works'])) {
                        ?>
                        <a href='Patients_Sent_To_Radiology.php?PatientsSentToRadiology=ThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Patients Sent To Radiology
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Patients Sent To Radiology
                        </button>
                    <?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if (isset($_SESSION['userinfo']['Radiology_Works'])) {
                        ?>
                        <a href='patient_done_radiology.php'>
                            <button style='width: 100%; height: 100%'>
                                Patient with Radiology done Report
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Patient with Radiology done Report
                        </button>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if (isset($_SESSION['userinfo']['Radiology_Works'])) {
                        ?>
                        <a href='number_of_test_taken_radg.php?TestNameCollection=ThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Number Of Radiology Done Report
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Number Of Radiology Done Report
                        </button>
                    <?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if (isset($_SESSION['userinfo']['Radiology_Works'])) {
                        ?>
                        <a href='patient_pendnotdone_radiology.php'>
                            <button style='width: 100%; height: 100%'>
                                Patient with Radiology active / pending / not done Report
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Patient with Radiology active / pending / not done Report
                        </button>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;' >
                    <?php if (isset($_SESSION['userinfo']['Radiology_Works'])) {
                        ?>
                        <a href='rad_performance_report.php'>
                            <button style='width: 100%; height: 100%'>
                                Radiology Performance Report
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Radiology Performance Report
                        </button>
                    <?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if (isset($_SESSION['userinfo']['Radiology_Works'])) {
                        ?>
                        <a href='rad_invest_report.php'>
                            <button style='width: 100%; height: 100%'>
                                Radiology Investigation Report
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Radiology Investigation Report
                        </button>
                    <?php } ?>
                </td>
            </tr>
            <?php
                        if(isset($_GET['frompage']) && $_GET['frompage'] == "DIHSREPORT"){
                            ?>
                      
                        <?php }else{
                            ?>
            <tr>
                
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if (isset($_SESSION['userinfo']['Radiology_Works'])) {
                        ?>
                        <a href='radiology_revenue_collection.php?TestNameCollection=ThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Radiology Revenue Collection Report
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Radiology Revenue Collection Report
                        </button>
                    <?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;' >
                    <?php if (isset($_SESSION['userinfo']['Radiology_Works'])) {
                        ?>
                        <a href='rad_doctor_test_report.php'>
                            <button style='width: 100%; height: 100%'>
                                Radiology Doctor Tests Report
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Radiology Doctor Tests Report
                        </button>
                    <?php } ?>
                </td>
            </tr>
             <?php
                        }
                        ?>

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
