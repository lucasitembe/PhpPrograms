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
        }else{
            @session_start();
            if(!isset($_SESSION['Admission_Supervisor'])){
                header("Location:./deptsupervisorauthentication.php?SessionCategory=Admission&InvalidSupervisorAuthentication=yes");
            }
        }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
	

?>
<a href="admissionworkspage.php" class="art-button-green">BACK</a>
<link href="editor.css" type="text/css" rel="stylesheet"/>
<br/>
<br/>
<?php 
    $SubDepWardID = $_SESSION['Admission_Sub_Department_ID'];
    $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
    $Select_Ward=mysqli_query($conn,"SELECT Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_ID IN (SELECT ward_id FROM tbl_sub_department_ward WHERE sub_department_id='$SubDepWardID')") or die(mysqli_error($conn));
    while($Ward_Row=mysqli_fetch_array($Select_Ward)){
        $ward_id=$Ward_Row['Hospital_Ward_ID'];
        $Hospital_Ward_Name=$Ward_Row['Hospital_Ward_Name'];
    }
?>

<fieldset>
    <legend align="center" ><b>NURSES WARD REPORT ~~ </b><b style="color: yellow;font-size: 17px"><?= $Hospital_Ward_Name ?></b></legend>
    <div class="container-fluid" >
        <div class="row">
                <div class="container">
                        <div class="row" >
                                <div class="col-lg-12 nopadding">
                                    <textarea id="nurse_ward_report" placeholder="Write Nurse Report" rows="4"></textarea> 
                                </div>
                        </div>
                </div>
        </div>
        <div class="col-md-12">
            <input type="button" value="SAVE REPORT" onclick="save_nurse_report()" class="art-button-green pull-right">
        </div>
    </div>
</fieldset>
<fieldset style="height: 350px;overflow-y:scroll;;overflow-x:scroll;">
    <legend align="center" ><b>SAVED REPORT</b></legend>
    <center>
        <table>
            <tr>
                <td><input class="form-control" id="start_date" type="text" placeholder="Start Date" style="text-align: center"></td>
                <td><input class="form-control" id="end_date" type="text" placeholder="End Date" style="text-align: center"></td>
                <td><input type="button" value="FILTER" onclick="filter_saved_nurser_ward_report()" class="art-button-green"></td>
                <td><input type="button" value="PREVIEW" onclick="preview_saved_nurser_ward_report()" class="art-button-green"></td>
            </tr>
        </table>
    </center>
    <table style="background: #FFFFFF" class="table table-responsive table-hover">
        <thead>
            <th width='50px'><b>S/No.</b></th>
            <th width='50%'><b>Nurse Report</b></th>
            <th><b>Report Date</b></th>
            <th><b>Reported By</b></th>
        </thead>
        <tbody id="nurse_report_body"></tbody>
    </table>
</fieldset>
<?php
    include("./includes/footer.php");
?>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<script src="editor.js"></script>
<script>
        $(document).ready(function() {
            //$("#nurse_ward_report").Editor();
            filter_saved_nurser_ward_report("yes")
        });
        function save_nurse_report(){
           var nurse_ward_report=$("#nurse_ward_report").val(); 
           var ward_id='<?= $ward_id ?>';
           if(nurse_ward_report==""){
               $("#nurse_ward_report").css("border","2px solid red");
               $("#nurse_ward_report").focus();
               exit;
           }
           $("#nurse_ward_report").css("border","");
           if(confirm("Are you sure you want to save this report?")){
                $.ajax({
                    type:'POST',
                    url:'ajax_save_nurse_report.php',
                    data:{nurse_ward_report:nurse_ward_report,ward_id:ward_id},
                    success:function(data){
                        if(data=="success"){
                            alert("SAVED SUCCESSFULLY");
                            filter_saved_nurser_ward_report("yes")

                        }else{
                            alert("PROCESS FAIL...TRY AGAIN"+data);
                        }
                    }
                });
            }
        }
        function filter_saved_nurser_ward_report(after_save="no"){
            var ward_id='<?= $ward_id ?>';
            var start_date=$("#start_date").val();
            var end_date=$("#end_date").val();
            if((start_date==""||end_date=="")&&after_save=="no"){
                $("#start_date").css("border","2px solid red");
                $("#end_date").css("border","2px solid red");
                exit;
            }
             $("#start_date").css("border","");
             $("#end_date").css("border","");
            document.getElementById('nurse_report_body').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            $.ajax({
               type:'POST',
               url:'ajax_filter_saved_nurser_ward_report.php',
               data:{ward_id:ward_id,start_date:start_date,end_date:end_date},
               success:function(data){
                   $("#nurse_report_body").html(data);
               }
           });
        }
        function preview_saved_nurser_ward_report(){
            var ward_id='<?= $ward_id ?>';
            var start_date=$("#start_date").val();
            var end_date=$("#end_date").val();
            if((start_date ==""||end_date =="")){
                $("#start_date").css("border","2px solid red");
                $("#end_date").css("border","2px solid red");
                aler("fill");
                return false;
            }
            else{
                window.open("ajax_preview_saved_nurser_ward_report.php?start_date="+start_date+"&end_date="+end_date+"&ward_id="+ward_id+"","_blank");
            }
        }
        
        
    $('#start_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('#start_date').datetimepicker({value: '', step: 01});
    $('#end_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:'now'
    });
    $('#end_date').datetimepicker({value: '', step: 01});
    
</script>