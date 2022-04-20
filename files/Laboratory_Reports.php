  <?php
include("./includes/connection.php");
include("./includes/header.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Laboratory_Works'])) {
        if ($_SESSION['userinfo']['Laboratory_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } else {
            
            if (!isset($_SESSION['Laboratory_Supervisor'])) {
                header("Location: ./deptsupervisorauthentication.php?SessionCategory=Laboratory&InvalidSupervisorAuthentication=yes");
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
<div id="displaytestrevenue" style="display: none">
    <table  class="hiv_table" style="width:100%;margin-top:3px;">
        <tr> 
            <td style="text-align:right;width:10px"><b>Date From<b></td>
                        <td width="70px"><input type='text' name='Date_From' id='date_From'  autocomplete="off"></td>
                        <td style="text-align:right;width:10px"><b>Date To<b></td>
                                    <td width="70px"><input type='text' name='Date_To' id='date_To'  autocomplete="off"></td>
                                    <td style="text-align:right;width:10px"><b>Sponsor<b></td>
                                                <td width="10px"><select id="sponsor">
                                                        <option value="All">All</option>
                                                        <?php
                                                        $getSponsor = mysqli_query($conn,"SELECT * FROM tbl_sponsor");
                                                        while ($result = mysqli_fetch_assoc($getSponsor)) {
                                                            echo '<option value="' . $result['Sponsor_ID'] . '">' . $result['Guarantor_Name'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                            <!--<td width="70px"><input type='text' name='Date_To' id='date_To'  autocomplete="off"></td>-->
                                                <td width="30px"><input type="submit" id="submit" name="submit" value="FILTER" class="art-button-green" />  <button class="art-button-green" id="ViewAllrevenuehere" style="display: none">View All</button>
                                                <!-- <input type='button' onclick="print_excell()" value='PRINT EXCELL' class='art-button-green'> -->
                                            </td>
                                                <!--<td></td>-->
                                                </tr> 
                                        <!--        <tr>
                                                    
                                                </tr>-->
                                                </table>
                                                <div id="showrevenue" style="height: 550px;overflow-x: hidden"> 

                                                </div>

                                                </div>
                                              
                                                <a href="laboratory.php?LaboratoryDashBoardThisPage=ThisPage" class="art-button-green">BACK</a>
                                                <script type='text/javascript'>
                                                    function access_Denied() {
                                                        alert("Access Denied");
                                                        document.location = "./index.php";
                                                    }
                                                </script>
                                                <br><br>
                                                <br><br>
                                                <fieldset>
                                                    <legend align=center><b>LABORATORY REPORTS</b></legend>
                                                    <center>
                                                        <table width = 60%>
                                                <!--                                        <tr>
                                                                <td style='text-align: center; height: 40px; width: 33%;'>
                                                            <?php if (isset($_SESSION['userinfo']['Laboratory_Works'])) {
                                                                ?>
                                                                        <a href='Today_Lab_Patients.php?TodayLaboratoryPatients=ThisPage'>
                                                                            <button style='width: 100%; height: 100%' id='Lab_Patients'>
                                                                            Laboratory Patients
                                                                            </button>
                                                                        </a>
                                                            <?php } else { ?>
                                                                                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                                                                        Laboratory Patients
                                                                                    </button>
                                                            <?php } ?>
                                                                </td>
                                                            </tr>-->
                                                            
                                                            <tr>
                                                                <td style='text-align: center; height: 40px; width: 33%;'>
                                                                    <?php if (isset($_SESSION['userinfo']['Laboratory_Works'])) {
                                                                        ?>
                                                                        <a href='Sample_Collection.php?SampleCollection=ThisPage'>
                                                                            <button style='width: 100%; height: 100%'>
                                                                                Patients Specimen Collection Report
                                                                            </button>
                                                                        </a>
                                                                    <?php } else { ?>
                                                                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                                                            Patients Specimen Collection Report
                                                                        </button>
                                                                    <?php } ?>
                                                                </td>
                                                                <td style='text-align: center; height: 40px; width: 33%;'>
                                                                    <?php if (isset($_SESSION['userinfo']['Laboratory_Works'])) {
                                                                        ?>
                                                                        <a href='Patients_Sent_To_Laboratory.php?PatientsSentToLaboratory=ThisPage'>
                                                                            <button style='width: 100%; height: 100%'>
                                                                                Patients Sent To Laboratory
                                                                            </button>
                                                                        </a>
                                                                    <?php } else { ?>
                                                                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                                                            Patients Sent To Laboratory
                                                                        </button>
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style='text-align: center; height: 40px; width: 33%;'>
                                                                    <?php if (isset($_SESSION['userinfo']['Laboratory_Works'])) {
                                                                        ?>
                                                                        <a href='Laboratory_Patient_Results.php?LaboratoryPatientResults=ThisPage'>
                                                                            <button style='width: 100%; height: 100%'>
                                                                                Laboratory Patient Results
                                                                            </button>
                                                                        </a>
                                                                    <?php } else { ?>
                                                                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                                                            Laboratory Patient Results
                                                                        </button>
                                                                    <?php } ?>
                                                                </td>
                                                                <td style='text-align: center; height: 40px; width: 33%;'>
                                                                    <?php if (isset($_SESSION['userinfo']['Laboratory_Works'])) {
                                                                        ?>
                                                                        <a href='Test_Collection.php?TestNameCollection=ThisPage'>
                                                                            <button style='width: 100%; height: 100%'>
                                                                                Patient Tests Taken Report
                                                                            </button>
                                                                        </a>
                                                                    <?php } else { ?>
                                                                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                                                            Patient Tests Taken Report
                                                                        </button>
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style='text-align: center; height: 40px; width: 33%;'>
                                                                    <?php if (isset($_SESSION['userinfo']['Laboratory_Works'])) {
                                                                        ?>
                                                                        <a href='number_of_test_taken.php?TestNameCollection=ThisPage'>
                                                                            <button style='width: 100%; height: 100%'>
                                                                                Number Of Tests Done per Category Report
                                                                            </button>
                                                                        </a>
                                                                    <?php } else { ?>
                                                                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                                                            Number Of Tests Done Report
                                                                        </button>
                                                                    <?php } ?>
                                                                </td>
                                                              <td style='text-align: center; height: 40px; width: 33%;'>
                                                                    <?php if (isset($_SESSION['userinfo']['Laboratory_Works'])) {
                                                                        ?>
                                                                        <!--<a href='Sample_Collection.php?RevenueCollection=ThisPage'>-->
                                                                        <button id="revueCollection" style='width: 100%; height: 100%'>
                                                                            Test Revenue Collection Report
                                                                        </button>
                                                                        <!--</a>-->
                                                                    <?php } else { ?>
                                                                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                                                            Test Revenue Collection Report
                                                                        </button>
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>






<!--<tr>-->  
<!--                                                                <td style='text-align: center; height: 40px; width: 33%;'>
            <?php if (isset($_SESSION['userinfo']['Laboratory_Works'])) {
                ?>
                <a href='speciem_rejection.php?TestNameCollection=ThisPage'>
                    <button style='width: 100%; height: 100%'>
                        Samples Rejected
                    </button>
                </a>
            <?php } else { ?>
                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                    Samples Rejected
                </button>
            <?php } ?>
    </td>-->

            <!--</tr>-->

            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']['Laboratory_Works'] == 'yes') { ?>
                        <a href='General_Performance_Filtered_Control_Session.php?Location=Laboratory'>
                            <button style='width: 100%; height: 100%'>
                                General Performance Report
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            General Performance Report
                        </button>

                    <?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']['Laboratory_Works'] == 'yes') { ?>
                        <a href='lab_invest_report.php'>
                            <button style='width: 100%; height: 100%'>
                                Investigation Report 
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Investigation Report
                        </button>

                    <?php } ?>
                </td>
            </tr>
            <tr>
<!--                                                                <td style='text-align: center; height: 40px; width: 33%;'>
                <?php if ($_SESSION['userinfo']['Laboratory_Works'] == 'yes') { ?>
                            <a href=' Patient_Rejected_Sample_Report.php'>
                                <button style='width: 100%; height: 100%'>
                                   Patient Rejected Sample Report
                                </button>
                            </a>
                <?php } else { ?>

                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                 Patient Rejected Sample Report
                            </button>

                <?php } ?>
                </td>-->
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if (isset($_SESSION['userinfo']['Laboratory_Works'])) {
                        ?>
                        <a href='speciem_rejection.php?TestNameCollection=ThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Specimen Rejection Report
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Specimen Rejection Report
                        </button>
                    <?php } ?>
                </td>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']['Laboratory_Works'] == 'yes') { ?>
                        <a href='Positive_negative_results.php?TestNameCollection=ThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Positive &amp; Negative Results with age limit
                            </button>
                        </a>
                    <?php } else { ?>

                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Positive &amp; Negative Results with age limit
                        </button>

                                                                    <?php } ?>
                                                                </td>
                                                            </tr>
                                                        
                                                        </table>
                                                        <table style="width: 60%">
                                                            <tr>
                                                                 <td style='text-align: center;width:50%; height: 40px;'>
                                                                    <?php if (isset($_SESSION['userinfo']['Laboratory_Works'])) {
                                                                        ?>
                                                                        <a href='Positive_negative_results_age_limit.php?TestNameCollection=ThisPage'>
                                                                            <button style='width: 100%; height: 100%'>
                                                                                Positive &amp; Negative without age limit
                                                                            </button>
                                                                        </a>
                                                                    <?php } else { ?>
                                                                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                                                            Positive &amp; Negative without age limit
                                                                        </button>
                                                                    <?php } ?>
                                                                </td>
                                                                <td>
                                                                    <a href='laboratory_number_of_test_done_report.php'>
                                                                        <button style='width: 100%; height: 100%'>
                                                                             Laboratory Number Of Test Done Report 
                                                                        </button>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                              <td>
                                                              <?php if (isset($_SESSION['userinfo']['Laboratory_Works'])) {
                                                                        ?>
                                                                        <a href='episode_of_care_lab_results.php?Episode_Of_Care=Episode_Of_CareThisPage'>
                                                                            <button style='width: 100%; height: 100%'>
                                                                                Episode Of Care (TAT Reports)
                                                                            </button>
                                                                        </a>
                                                                    <?php } else { ?>
                                                                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                                                            Episode Of Care (TAT Reports)
                                                                        </button>
                                                                    <?php } ?>
                                                                </td>
                                                                <td>
                                                                    <a href='completed_blood_transfusion.php'>
                                                                        <button style='width: 100%; height: 100%'>
                                                                             Processed Blood Transfusion Report 
                                                                        </button>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="display: none">
                                                                    <a href='tat_for_result_from_integrated_machine.php' disabled='disabled'>
                                                                        <button style='width: 100%; height: 100%' disabled='disabled' onclick="alert('This Works only with Intergrated Machines')">
                                                                             TAT For Result From Integrated Machine
                                                                        </button>
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    <a href='test_sent_to_laboratory.php'>
                                                                        <button style='width: 100%; height: 100%'>
                                                                             Laboratory Investigation Report
                                                                        </button>
                                                                    </a>
                                                                </td>
                                                            <!-- </tr>
                                                            <tr> -->
                                                                <td>
                                                                    <a href='new_lab_report.php'>
                                                                        <button style='width: 100%; height: 100%'>
                                                                             New Lab Report
                                                                        </button>
                                                                    </a>
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

//     function print_excell(){
//         fromDate= $('#date_From').val();
//         toDate=$('#date_To').val();
//         sponsorName=$('#sponsor').val();
// // alert("hii");
//         if(fromDate != '' && toDate != ''){
//             $.ajax({
//                 type: "GET",
//                 url: "TestrevenueCollection_excell.php",
//                 data: {
//                     fromDate:fromDate,
//                     toDate:toDate,
//                     sponsorName:sponsorName
//                 },
//                 cache: false,
//                 success: function (response) {
//                 }
//             });
//         }else{
//             alert("Please Select Dates To Generate the Report");
//         }
//     }
</script>

<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<!--<script src="css/jquery.js" type="text/javascript"></script>-->
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
