<?php
include("./includes/functions.php");

include("./includes/header.php");

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$DateGiven = date('Y-m-d');
?>
<?php
//get sub department id


$query = mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
$dataSponsor = '';
$dataSponsor.='<option value="All">All Sponsors</option>';

while ($row = mysqli_fetch_array($query)) {
    $dataSponsor.= '<option value="' . $row['Sponsor_ID'] . '">' . $row['Guarantor_Name'] . '</option>';
}


?>
<a href="dailyPatientAttendance.php" class='art-button-green'>DAILY PATIENT ATTENDANCE</a>
<?php 

    $Sub_Department_Name = $_SESSION['Admission'];

    $qr = "SELECT Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_Name LIKE  '%$Sub_Department_Name%'";
                $ward_results = mysqli_query($conn,$qr);
                if(mysqli_num_rows($ward_results)>0){
                    while ($ward_rows = mysqli_fetch_assoc($ward_results)) {
                        $Hospital_Ward_ID = $ward_rows['Hospital_Ward_ID'];
                        $Hospital_Ward_Name = $ward_rows['Hospital_Ward_Name'];
                        
                        $Display = "<option name='duty_ward' value='$Hospital_Ward_ID' selected='selected'>$Hospital_Ward_Name</option>";

                    }
                }

    if(isset($_GET['from_admission'])){
      echo "<a href='admissionreports.php?section=&Admissionreports=AllReports&ActiveReports'  class='art-button-green'>BACK</a>";  
    }else if(isset($_GET['from_doctors'])){
         echo "<a href='doctorsworkspage.php?RevenueCenterWork=RevenueCenterWorkThisPage'  class='art-button-green'>BACK</a>";  
    }else{
?>
<a href='admissionreports.php?GovernmentReports=GovernmentReportsThisPage' class='art-button-green'>
        BACK
    </a>
<?php } ?>

<fieldset style='margin-top:15px;'>
    <legend align="right" style="text-align:right;background-color:#006400;color:white;padding:5px;"><b>DHIS2 REPORTS</b></legend>
    <center>
        <table  class="hiv_table" style="width:100%;margin-top:5px;">
            <tr> 
                <td style="width: 20px;text-align:center ">
                    <b>Date From:</b> <input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="date_From" placeholder="Start Date"/>
                    <b>To: </b><input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="date_To" placeholder="End Date"/>&nbsp;
                
                    Category: <select id="death_cause" style='text-align: center;padding:4px; width:15%;display:inline'>
                    <option>All</option>
                    <option>Cause 1</option>
                    <option>Cause 2</option>
                    <option>Cause 3</option>
                </select>
                <b>Select Ward: </b> 
                <select id="death_ward" style='padding:4px;'>
                    <?php
                        $result = mysqli_query($conn, "SELECT Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_hospital_ward WHERE ward_status='active'") or die(mysqli_error($conn));
                        echo $Display;
                        while($row=mysqli_fetch_assoc($result)){
                            echo "<option value='".$row['Hospital_Ward_ID']."'>{$row['Hospital_Ward_Name']}</option>";
                        }
                    ?>
                </select>
                    <b>Age < </b><input type="number" id="start_age" name="start_age" min="0" max="200" placeholder="Start age" class="numberonly" style='text-align: center;width:5%;display:inline;padding: 4px'/>
                 <b>Age &ge; </b><input type="number" id="end_age" name="end_age" min="0" max="200" placeholder="End age" class="numberonly" style='text-align: center;width:5%;display:inline;padding: 4px'/>
                    <input type="button" name="filter" value="FILTER" class="art-button-green" onclick="Filter_Death();"> 
                </td>
            </tr>
        </table>
    </center>
    <center>
        <table  class="hiv_table" style="width:100%">
            <tr>
                <td>
                    <div style="width:100%; height:500px;overflow-x: hidden;overflow-y: auto;margin: 2px 2px 20px 2px;"  id="Search_Iframe">
                    </div>
                </td>
            </tr>
        </table>

    </center>
</fieldset>

<br/>
<center> 
    <input type="submit"  onclick="Excel_Report();" class="art-button-green" value='DOWNLOAD EXCEL REPORT'>
</center> 

<br/>
<?php
include("./includes/footer.php");
?>

<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
    <script src="js/jquery-1.8.0.min.js"></script>
    <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>

<script>
    $('#date_From').datetimepicker({
    dayOfWeekStart: 1,
    lang: 'en',
    startDate: 'now'
    });
    $('#date_From').datetimepicker({value: '', step: 1});
    $('#date_To').datetimepicker({
    dayOfWeekStart: 1,
    lang: 'en',
    startDate: 'now'
    });
    $('#date_To').datetimepicker({value: '', step: 1});
</script>
<script type="text/javascript">
    $("#ipd_report_category").on("change",function(){
        if($("#ipd_report_category").val()=='ipd_attendance'){
            $("#second_row").hide();
        }
        if($("#ipd_report_category").val()=='ipd_diagnosis'){
            $("#second_row").show();
        }
    });

    function Filter_Death(){
        var fromDate=$("#date_From").val();
        var toDate=$("#date_To").val();;
        var start_age=$("#start_age").val();;
        var end_age=$("#end_age").val();
        var death_cause =$("#death_cause").val();
        var death_ward =$("#death_ward").val();
        if(fromDate.trim()!=='' && toDate.trim()!==''){
            if(checkAge(start_age,end_age)){
                $('#Search_Iframe').html('<div align="center" style="display:block;" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>');
                $.ajax({
                    url:'government_death_reports.php',
                    type:'post',
                    data:{fromDate:fromDate,toDate:toDate,start_age:start_age,end_age:end_age,Filter_Category:'yes',death_ward:death_ward},
                    success:function(result){
                       if (result != '') {
                            $('#Search_Iframe').html(result);
                        }
                    }
                });
            }
        }else{
            alert('FILL THE START DATE AND END DATE');
        }
    }
    function checkAge(start_age,end_age){
        if(start_age==='' || end_age===''){
            alert('Select the Age range');
            return false;
        }
        return true;
    }
   

  
</script>
<script type="text/javascript">
    function Excel_Report(){
        var fromDate=$("#date_From").val();
        var toDate=$("#date_To").val();;
        var start_age=$("#start_age").val();;
        var end_age=$("#end_age").val();
        var death_cause =$("#death_cause").val();
        var death_ward =$("#death_ward").val();
        if(fromDate.trim()!=='' && toDate.trim()!==''){
            if(checkAge(start_age,end_age))window.location.href='print_death_excel_report.php?fromDate=' + fromDate + '&toDate=' + toDate + '&death_cause=' + death_cause + '&start_age_death=' + start_age+ '&end_age_death=' + end_age+'&death_ward='+death_ward;
        }else{
            alert('FILL THE START DATE AND END DATE');
        }
    }
</script>