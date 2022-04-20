<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Admission_Works'])){
	    if($_SESSION['userinfo']['Admission_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    
    if(isset($_GET['section'])){ 
	$section = $_GET['section'];
    }else{
	$section = "Admission";
    }
  //  if($section=='Admission'){
        echo "<a href='admissionreports.php?section=&Admissionreports=AllReports&ActiveReports' class='art-button-green'>
            BACK
        </a>";
   // }
?>
<br><br>
    <style>
    .rows_list{
        cursor: pointer;
    }
    #male_list:active{
        color: #328CAF!important;
        font-weight:normal!important;
    }
    #male_list:hover{
        color:#00416a;
        background: #dedede;
        font-weight:bold;
        cursor: pointer;
    }

    #female_list:active{
        color: #328CAF!important;
        font-weight:normal!important;
    }
    #female_list:hover{
        color:#00416a;
        background: #dedede;
        font-weight:bold;
        cursor: pointer;
    }
    a{
        text-decoration: none;
    }
    .vertical {
        background-color: #DDDDDD;
        padding: 10px;
        border-radius: 0 0 5px 5px;
        float: right;
        -webkit-transform: rotate(90deg) translate(100%,100%);
                transform: rotate(90deg) translate(100%,100%);
        -webkit-transform-origin: 100% 100%;
            transform-origin: 100% 100%;
}
</style>
<fieldset style='margin-top:15px;'>
    <legend align="right" style="text-align:right;background-color:#006400;color:white;padding:5px;"><b>DHIS2 REPORTS</b></legend>
    <center>

        
        
        <fieldset style='margin-top:15px;'>
<!--    <legend align="right" style="text-align:right;background-color:#006400;color:white;padding:5px;"><b>DIAGNOSIS REPORTS</b></legend>-->
    <center>
        <table  class="hiv_table" style="width:100%;margin-top:5px;">
            <tr>
                <td style="width: 20px;text-align:center ">
               
                      <b >Select Ward: </b>
                    <select id="Hospital_Ward_ID" style='padding:4px;' >
                        <option value="">~~~~select~~~~</option>
                        <?php
                        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
                        $clinic_result = mysqli_query($conn,"SELECT * FROM tbl_hospital_ward  WHERE ward_type != 'mortuary_ward' AND Hospital_Ward_ID IN (SELECT ward_id FROM tbl_sub_department_ward WHERE sub_department_id IN(SELECT Sub_Department_ID FROM tbl_employee_sub_department WHERE Employee_ID='$Employee_ID')) ORDER BY Hospital_Ward_Name ASC") or die(mysqli_error($conn));
                        while($row=mysqli_fetch_assoc($clinic_result)){
                            echo "<option value='".$row['Hospital_Ward_ID']."'>{$row['Hospital_Ward_Name']}</option>";
                        }
                    ?>
                    </select>
                    <b>Date From:</b> <input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="date_From" placeholder="Start Date"/>
                    <b>To: </b><input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="date_To" placeholder="End Date"/>&nbsp;

                 <input type="button" name="filter" value="FILTER" class="art-button-green" onclick="Filter_daily_summary();">
                
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
        <div id="admissionDetails"></div> 
        <div id="transfer_in_summary_details"></div>
        <div id="open_details"></div>
  
        
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
    
    function Filter_daily_summary(){

        var fromDate=$("#date_From").val();
        var toDate=$("#date_To").val();
        var Hospital_Ward_ID = $("#Hospital_Ward_ID").val();
        if(Hospital_Ward_ID==""){
            alert("Select ward");
            $("#Hospital_Ward_ID").css("border", "1px solid red");
        }else
      //  alert(Hospital_Ward_ID);
        if(fromDate.trim()!=='' && toDate.trim()!==''){
            $('#Search_Iframe').html('<div align="center" style="display:block;" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>');
            $.ajax({
                type:'POST',
                url:'Ajax_Filter_daily_summary_1.php',
                data:{fromDate:fromDate,toDate:toDate, Hospital_Ward_ID:Hospital_Ward_ID },
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
    function open_summary_details(Hospital_Ward_ID ,Hospital_Ward_Name, clickedbox){
        var fromDate=$("#date_From").val();
        var toDate=$("#date_To").val();
     
       $.ajax({
           type:'POST',
           url:'ajax_open_summary_details.php',
           data:{fromDate:fromDate,toDate:toDate,Hospital_Ward_ID:Hospital_Ward_ID,Hospital_Ward_Name:Hospital_Ward_Name,clickedbox:clickedbox},
           success:function(data){
                $("#open_details").html(data);
               $("#open_details").dialog({
                        title: Hospital_Ward_Name+' :- DETAILS',
                        width: '70%',
                        height: 450,
                        modal: true,
                    }); 
                   
           }
       });
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