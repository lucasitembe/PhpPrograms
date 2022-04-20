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
<a href='governmentReports.php' class='art-button-green'>
        BACK
    </a>
<fieldset style='margin-top:15px;'>
    <legend align="center" style="text-align:center;background-color:#006400;color:white;padding:5px;"><b>BED STATE REPORT</b></legend>
    <center>

        
        
        <fieldset style='margin-top:15px;'>
<!--    <legend align="right" style="text-align:right;background-color:#006400;color:white;padding:5px;"><b>DIAGNOSIS REPORTS</b></legend>-->
    <center>
        <table  class="hiv_table" style="width:100%;margin-top:5px;">
            <tr>
                <td style="width: 20px;text-align:center ">
                    <b>Date From:</b> <input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="date_From" placeholder="Start Date"/>
                    <b>To: </b><input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="date_To" placeholder="End Date"/>&nbsp;

                    <b>Select Category :</b>
                        <select id='report_category' style='text-align:center;padding:4px; width:15%;display:inline' onchange='Filter_OPD();'>
                            <option value='admission'>Admission</option>
                            <option value='Normal'>Discharge live</option>
                            <option value='Death'>Discharge death</option>
                            <option value='transfer_in'>Transfer In</option>
                            <option value='transfer_out'>Transfer Out</option>
                            <option value='transfer_pending'>Transfer Pending</option>
                            <option value='Absconded'>Absconded</option>
                            <option value='Inpatient_Days'>Occupied Bed Days</option>
                        </select>
                    <b>From age </b><input type="number" id="start_age" name="start_age" min="0" max="200" placeholder="From age" class="form-control numberonly" style='text-align: center;width:5%;display:inline;padding: 4px'/>
                 <b>To age </b><input type="number" id="end_age" name="end_age" min="0" max="200" placeholder="To age" class="form-control numberonly" style='text-align: center;width:5%;display:inline;padding: 4px'/>
                 
                        <select id='ipd_time' style='text-align:center;padding:4px; width:15%;display:inline'>
<!--                            <option value='all'>ALL</option>-->
                            <option value='YEAR'>Year</option>
                            <option value='MONTH'>Month</option>
                            <option value='DAY'>Days</option>
                        </select> 
                 <input type="button" name="filter" value="FILTER" class="art-button-green" onclick="Filter_OPD();"></br>
                      <b >Select Ward: </b>
                    <select id="Ward_ID" style='padding:4px;' >
                        <option value="all">ALL</option>
                        <?php
                        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
                        $clinic_result = mysqli_query($conn,"SELECT * FROM tbl_hospital_ward  WHERE ward_type != 'mortuary_ward' AND Hospital_Ward_ID IN (SELECT ward_id FROM tbl_sub_department_ward WHERE sub_department_id IN(SELECT Sub_Department_ID FROM tbl_employee_sub_department WHERE Employee_ID='$Employee_ID')) ORDER BY Hospital_Ward_Name ASC") or die(mysqli_error($conn));
                        while($row=mysqli_fetch_assoc($clinic_result)){
                            echo "<option value='".$row['Hospital_Ward_ID']."'>{$row['Hospital_Ward_Name']}</option>";
                        }
                    ?>
                    </select>
                        <b>Patient Type:</b>
                        <select id='patitent_type' style='text-align:center;padding:4px; width:15%;display:inline'>
                            <option value='all'>ALL</option>
                            <option value='New_Admission'>New Admission</option>
                            <option value='Return_Admission'>Return Admission</option>
                            <option value='Re_Admission'>Re-Admission</option>
                        </select>
                        <b>SPONSOR:</b>
                        		<select name="Sponsor_ID" id="Sponsor_ID">
        			<option selected="selected" value="0">All</option>
<?php
					$select = mysqli_query($conn,"select Sponsor_ID, Guarantor_Name from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
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
 <div id="displayPatientList" style="width:100%;overflow-x:hidden;height:520px;display:none;overflow-y:scroll;background-color:white;">
    <div id="patientList">
    </div>
</div>
<br/>
<center>
    <!--<input type="submit"  onclick="Excel_Report();" class="art-button-green" value='DOWNLOAD EXCEL REPORT'>-->
</center>

<br/>
        
        
  
        
<?php
include("./includes/footer.php");
?> 
  <link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
    <script src="js/jquery-1.8.0.min.js"></script>
    <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>    
    
     <link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script> 
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
    
    
       function Filter_OPD(){
        var fromDate=$("#date_From").val();
        var toDate=$("#date_To").val();
        var start_age=$("#start_age").val();
        var end_age=$("#end_age").val();
        var ipd_time =$('#ipd_time').val();
        var Ward_ID = $("#Ward_ID").val();
        var Sponsor_ID = $("#Sponsor_ID").val();
        var patitent_type = $("#patitent_type").val();
        var report_category =$("#report_category").val();
        if(fromDate.trim()!=='' && toDate.trim()!==''){
            if(checkAge(start_age,end_age)){
                $('#Search_Iframe').html('<div align="center" style="display:block;" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>');
                $.ajax({
                    url:'ajax_ipd_reports_new.php',
                    type:'post',
                    data:{fromDate:fromDate,toDate:toDate,start_age:start_age,end_age:end_age,ipd_time:ipd_time,Ward_ID:Ward_ID,patitent_type:patitent_type,report_category:report_category,Sponsor_ID:Sponsor_ID},
                    beforeSend:function(){$("#load_image").show();},
                    success:function(result){
                        if (result != '') {
                            console.log(result);
                            $('#Search_Iframe').html(result);
                        }
                    },
                    complete:function(){
                        $("#load_image").hide();
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
    
      function viewPatientList(Hospital_Ward_Name,Hospital_Ward_ID,fromDate,toDate,report_category,start_age,end_age,ipd_time,patitent_type,Sponsor_ID){
        $.ajax({
            url:'fetch_admitted_patient.php',
            type:'post',
            data:{Hospital_Ward_Name:Hospital_Ward_Name,Hospital_Ward_ID:Hospital_Ward_ID,fromDate:fromDate,toDate:toDate,report_category:report_category,start_age:start_age,end_age:end_age,ipd_time:ipd_time,patitent_type:patitent_type,Sponsor_ID:Sponsor_ID},
            success:function(result){
                console.log(result);
                $('#displayPatientList').html(result);
            }
        });
        $("#displayPatientList").dialog('open');

    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#displayPatientList").dialog({autoOpen: false, width: '90%',height:'550', title: 'PATIENTS LIST', modal: true, position: 'middle'});
        /*$('.numberTests').dataTable({
            "bJQueryUI": true
        });*/
 $('select').select2();
    });
</script>