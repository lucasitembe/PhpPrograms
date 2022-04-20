<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ./index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Admission_Works'])) {
        if ($_SESSION['userinfo']['Admission_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_GET['section'])) {
    $section = $_GET['section'];
} else {
    $section = "Admission";      
}
if (strtolower($section) == 'admission') {
    if (isset($_SESSION['manageflag']) && $_SESSION['manageflag'] == "management") {
        
    } else {
        echo "<a href='admissionworkspage.php?Section=" . $section . "&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
            ADMISSION MAIN WORKPAGE
            </a>
            <a href='admissionreports.php?Section=" . $section . "&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
            ADMISSION REPORTS
            </a>
            ";
    }
}
$Sub_Department_Name = $_SESSION['Admission'];

$qr = "SELECT Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_Name LIKE  '%$Sub_Department_Name%'";
                            $ward_results = mysqli_query($conn,$qr);
                            if(mysqli_num_rows($ward_results)>0){
                                while ($ward_rows = mysqli_fetch_assoc($ward_results)) {
                                    $Hospital_Ward_ID = $ward_rows['Hospital_Ward_ID'];
                                    $Hospital_Ward_Name = $ward_rows['Hospital_Ward_Name'];
                                    
                                    $Display = "<option name='duty_ward' value='$Hospital_Ward_ID' selected='selected'>$Hospital_Ward_Name</option>";
    
                                }
                                echo " <a href='admissionreports.php?section=&Admissionreports=AllReports&ActiveReports' class='art-button-green'>
                                    BACK
                                </a>";
                            }else{
                                echo "<a href='receptionReports.php?Section=" . $section . "&ReceptionReportThisPage' class='art-button-green'>BACK</a>";
                            }  
// if (strtolower($section) == 'reception') {
//     echo "<a href='receptionReports.php?Section=" . $section . "&ReceptionReportThisPage' class='art-button-green'>BACK</a>";
// }
//  else {
//    //  echo "<a href='receptionReports.php?Section=" . $section . "&ReceptionReportThisPage' class='art-button-green'>BACK</a>";
//     echo "<a href='admissionreports.php?section=&Admissionreports=AllReports&ActiveReports' class='art-button-green'>BACK
// 				  </a>";
// }
?>

<style>
    select{
        padding:5px;
    }

    .dates{
        color:#cccc00;
    }
</style> 
<br/><br/>
<fieldset style="background-color:white;">
    <legend align="center" style="background-color:#006400;color:white;padding:5px;"><b>CURRENT INPATIENT LIST</b></legend>
    <center>
        <table width='99%' style="border:none !important; border-color:transparent !important;background-color:white;">
            <tr>
                <td style="text-align:center">
                    <select name='Sponsor_ID' id='Sponsor_ID' onchange="filterPatient()" style='text-align: center;width:10%;display:inline;'>
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
                            <?php 
                                $sql_check_if_military_result=mysqli_query($conn,"SELECT configname FROM tbl_config WHERE configname='Military' AND configvalue='Yes'") or die(mysqli_error($conn));
                            ?>
                             <select name='patient_type' id='patient_type' onchange="filterPatient()" style='text-align: center;width:10%;'>
                                <option value="All">All Patient Status</option>
                                <option value="Admitted">Admitted</option>
                                <option value="Pending">On Discharge Request</option>
                                <?php ?>
                            </select>
                     <!-- <select id='mil_rank' type='text' name='rank' onchange="filterPatient()" style='text-align: center;width:10%;' >
                        <option value="All">All Ranks</option>  -->
                         <?php 
                                //     $sql_select_unit=mysqli_query($conn,"SELECT pr.rank FROM tbl_patient_registration pr WHERE rank<>'' GROUP BY rank ORDER BY Registration_ID DESC") or die(mysqli_error($conn));
                                //    if(mysqli_num_rows($sql_select_unit)>0){
                                //       while($unit_rows=mysqli_fetch_assoc($sql_select_unit)){
                                //           $rank=$unit_rows['rank'];
                                //           echo "<option value='$rank'>$rank</option>";
                                //       } 
                                //    }else{
                                //        echo "<option>Nothing</option>";
                                //    }
                                ?>
                      
                    <!-- </select>  -->
                   
                    
                    <select id='Gender' name='Gender' onchange="filterPatient()">
                        <option value="All">ALL</option>
                        <option>Male</option>
                        <option>Female</option>
                    </select>
                    <select width="15%"  name='Ward_id' style='text-align: center;width:10%;display:inline' onchange="filterPatient()" id="Ward_id">
                        <!-- <option value="All">All Ward</option> -->
                        <?php
                        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
                        $Select_Ward = mysqli_query($conn,"SELECT Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_ID IN (SELECT ward_id FROM tbl_sub_department_ward WHERE sub_department_id IN(SELECT Sub_Department_ID FROM tbl_employee_sub_department WHERE Employee_ID='$Employee_ID'))");
                        echo $Display;
                        while ($Ward_Row = mysqli_fetch_array($Select_Ward)) {
                            $ward_id = $Ward_Row['Hospital_Ward_ID'];
                            $Hospital_Ward_Name = $Ward_Row['Hospital_Ward_Name'];
                            ?>
                            <option value="<?php echo $ward_id; ?>"><?php echo $Hospital_Ward_Name; ?></option>
                        <?php }
                        ?>
                    </select>
                    <input type='text' name='Search_Patient' style='text-align: center;width:20%;display:inline' id='Search_Patient' oninput="filterPatient()" placeholder='~~~~~~~Search Patient Name~~~~~~~'>
                    <input type='text' name='Search_Patient' style='text-align: center;width:20%;display:inline' id='Search_Patient_number' onkeyup="filterPatient()" placeholder='~~~~~Patient Number~~~~'>
                    <input type="button" value="Filter" class="art-button-green" onclick="filterPatient()">
                    <!-- <a href="currentinpatientreportprint.php" id='print_preview' class='art-button-green' target='_blank'>PREVIEW DETAILS</a> -->
                    <a href="currentinpatientreportprintexcel.php" id='printexcel_preview' class='art-button-green' target='_blank'>PREVIEW EXCEL</a>
                </td>
            </tr>
        </table>
    </center>
    <center>
        <table width=100% border=1>
            <tr>
                <td>
                    <div id="Search_Iframe" style="height: 400px;overflow-y: auto;overflow-x: hidden">
                        <?php // include 'currentinpatientreport_iframe.php'; ?>
                    </div>
                </td>
            </tr>
        </table>
    </center>
</fieldset>
<br/>

<script type="text/javascript">
    $(document).ready(function () {
        $('#patients-list').DataTable({
            "bJQueryUI": true
        });
    });
</script>

<script>
    function filterPatient() {
        var ward = document.getElementById('Ward_id').value;
        var Gender = document.getElementById('Gender').value;
        // var Branch_ID = document.getElementById('Branch_ID').value;
        var Patient_Name = document.getElementById("Search_Patient").value;
        var Sponsor = document.getElementById("Sponsor_ID").value;
        var ptn_type = document.getElementById("patient_type").value;
        var Registration_ID = document.getElementById('Search_Patient_number').value;
        // var unit = document.getElementById('unit').value;

        $('#print_preview').attr('href', 'currentinpatientreportprint.php?Patient_Name=' + Patient_Name + '&ward=' + ward + '&Gender=' + Gender +  '&Sponsor=' + Sponsor + '&patient_type=' + ptn_type+ '&Registration_ID='+Registration_ID);

        $('#printexcel_preview').attr('href', 'currentinpatientreportprintexcel.php?Patient_Name=' + Patient_Name + '&ward=' + ward + '&Gender=' + Gender +  '&Sponsor=' + Sponsor + '&patient_type=' + ptn_type+ '&Registration_ID='+Registration_ID);

        document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';


        $.ajax({
            type: 'GET',
            url: 'currentinpatientreport_iframe.php',
            data: 'Patient_Name=' + Patient_Name + '&ward=' + ward + '&Gender=' + Gender +  '&Sponsor=' + Sponsor + '&patient_type=' + ptn_type +  '&Registration_ID='+Registration_ID,
            cache: false,
            beforeSend: function (xhr) {
                //$("#progressStatus").show();
            },
            success: function (html) {
                if (html != '') {
                    // $("#progressStatus").hide();
                    $("#Search_Iframe").html(html);

                    $.fn.dataTableExt.sErrMode = 'throw';
                    $('#patients-list').DataTable({
                        "bJQueryUI": true

                    });
                }
            }, error: function (html) {

            }
        });

    }
</script>


<?php
include("./includes/footer.php");
?>
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>                
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="script.responsive.js"></script>


<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
